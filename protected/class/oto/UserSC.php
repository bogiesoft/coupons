<?php
include_once(dirname(__FILE__).'/../mainClass.php');
/**
 * 会员类
 *
 */
class UserSC extends mainClass{
	/**
	 * 会员列表
	 */
	public function getUserList() {
		
	}
	
	/**
	 * 会员详情
	 * @param $operator_id  用户id
	 * @param $account		账号（手机号或会员卡号）
	 * @param $user_id      会员id
	 * @return array
	 */
	public function getUserDetails($operator_id, $account, $user_id = NULL) {
		$result = array();
		try {
			//参数验证
			//TODO
			/*
			$cmd = Yii::app()->db->createCommand();
			$cmd->select('u.id, u.name, u.sex, u.money, u.free_secret, g.discount, m.points_rule, g.name gname'); //查询字段
			$cmd->from(array('wq_operator o','wq_store s', 'wq_user u', 'wq_user_grade g', 'wq_merchant m')); //查询表名
			$cmd->where(array(
					'AND',  //and操作
					'o.store_id = s.id', //联表
					's.merchant_id = u.merchant_id = m.id',
					'u.membershipgrade_id = g.id',
					'o.id = :operator_id', //操作员id
					array(
							'OR', 
							'u.account = :account', 
							'u.membership_card_no = :account'
					)
			));
			//查询参数
			$cmd->params = array(
					':operator_id' => $operator_id,
					':account' => $account,
					':card_no' => $account
			);
			//执行sql，获取所有行数据
			$model = $cmd->queryRow();
			$data = array();
			if (!empty($model)) {
				$data['user_id'] = $model['id']; //用户id
				$data['name'] = $model['name']; //用户名
				$data['sex'] = $model['sex']; // 用户性别
				$data['money'] = $model['money']; //用户储值金额
				$data['free_secret'] = $model['free_secret'] ; //免密金额
				$data['discount'] = $model['discount']; //会员折扣
				$data['points_rule'] = $model['points_rule']; //积分规则(积分=实收金额*积分规则)
				$data['gname'] = $model['gname']; //会员折扣
			}
			*/
			
			$data = array();
			//查询操作员所属门店id
			$operator = Operator::model()->findByPk($operator_id);
			if (empty($operator)) {
				$result['status'] = ERROR_NO_DATA;
				throw new Exception('无效的操作员信息');
			}
			//查询门店所属商户id
			$store = Store::model()->findByPk($operator['store_id']);
			if (empty($store)) {
				$result['status'] = ERROR_NO_DATA;
				throw new Exception('所属门店不存在');
			}
			if (!empty($account)) {
				//查询会员信息(根据商户id和会员手机号/卡号)
				$user = User::model()->find('merchant_id = :merchant_id AND (account = :account OR membership_card_no = :account)',
						array(':merchant_id' => $store['merchant_id'], ':account' => $account));
			}else {
				$user = User::model()->findByPk($user_id);
			}
			if (empty($user)) {
				$result['status'] = ERROR_NO_DATA;
				throw new Exception('该会员不存在');
			}
			$data['user_id'] = $user['id']; //用户id
			$data['account'] = $user['account']; //会员账号
			$data['name'] = $user['name']; //用户名
			$data['avatar'] = $user['avatar']; //头像
			$data['sex'] = $user['sex']; // 用户性别
			$data['money'] = $user['money']; //用户储值金额
			$data['free_secret'] = $user['free_secret'] ; //免密金额
			$data['nick_name'] = $user['nickname']; //昵称
			$data['card_no'] = $user['membership_card_no']; //会员卡号
			$data['birthday'] = $user['birthday']; //生日
			$data['register_time'] = $user['regist_time']; //注册时间
			$data['address'] = ''; //地址
			$province = $user['province']; //省
			$city = $user['city']; //市
			if (!empty($province) && !empty($city)) {
				$data['address'] = $province.'-'.$city;
			}
			$data['identity_card_no'] = $user['social_security_number'];
			$data['email'] = $user['email'];
			$data['marital_status'] = $user['marital_status'];
			$data['job'] = $user['work'];
			//计算累计消费金额和消费次数
			$data['order_paid'] = 0;
			$data['order_count'] = 0;
			$cmd = Yii::app()->db->createCommand();
			$cmd->andWhere('t.flag = :flag');
			$cmd->params[':flag'] = FLAG_NO;
			$cmd->andWhere('pay_status = :custom_pay_status');
			$cmd->params[':custom_pay_status'] = ORDER_STATUS_PAID; //已支付订单
			$cmd->andWhere('order_type = :custom_order_type');
			$cmd->params[':custom_order_type'] = ORDER_TYPE_CASHIER; //收银订单
			$cmd->andWhere('user_id = :user_id');
			$cmd->params[':user_id'] = $user_id;
			$cmd->from = 'wq_order t';
			
			$cmd1 = clone $cmd;
			$cmd2 = clone $cmd;
			$cmd3 = clone $cmd;
			
			//查询1
			$select1 = 'user_id';
			$select1 .= ', SUM(order_paymoney) AS order_sum';
			$select1 .= ', SUM(coupons_money) AS coupons_sum';
			$select1 .= ', SUM(discount_money) AS discount_sum';
			$select1 .= ', SUM(merchant_discount_money) AS m_discount_sum';
			$select1 .= ', COUNT(*) AS trade_sum';
			$cmd1->select = $select1;
			
			$query1 = $cmd1->queryRow();
			
			//查询2
			$select2 = 'SUM(r.refund_money) AS refund_sum';
			$cmd2->select = $select2;
			
			//联表
			$join = 'JOIN wq_refund_record r ON t.id = r.order_id';
			$join .= ' AND r.flag = '.FLAG_NO.' AND r.status != '.REFUND_STATUS_FAIL.' AND r.type = '.REFUND_TYPE_REFUND;
			$cmd2->join = $join;
			
			//执行sql查询:统计退款金额
			$query2 = $cmd2->queryRow();
			
			//累计消费金额统计
			$data['order_paid'] = $query1['order_sum'] - $query1['coupons_sum'] - $query1['discount_sum'] - $query1['m_discount_sum'] - $query2['refund_sum'] + 0;
			//累计消费笔数
			$data['order_count'] = $query1['trade_sum'];
			
			//计算当前积分和累计积分
			$data['current_points'] = $user['points']; //当前积分
			$data['total_points'] = $user['points']; //累计积分（待修改）
			
			//查询商户信息
// 			$merchant = Merchant::model()->findByPk($user['merchant_id']);
// 			if (empty($merchant)) {
// 				$result['status'] = ERROR_NO_DATA;
// 				throw new Exception('会员所属商户不存在');
// 			}
// 			$data['points_rule'] = $merchant['points_rule']; //积分规则(积分=实收金额*积分规则)
			//查询会员等级信息
			$data['discount'] = 1; //会员折扣
			$data['gname'] = '';
			$data['points_ratio'] = 0; //积分比率
			$grade = UserGrade::model()->findByPk($user['membershipgrade_id']);
			if (!empty($grade)) {
				$data['discount'] = $grade['discount']; //会员折扣
				$data['gname'] = $grade['name'];
				$data['points_ratio'] = empty($grade['points_ratio']) ? 0 : $grade['points_ratio'];
			}
			
			$result['data'] = $data;
			$result['status'] = ERROR_NONE; //状态码
			$result['errMsg'] = ''; //错误信息
		} catch (Exception $e) {
			$result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
			$result['errMsg'] = $e->getMessage(); //错误信息
		}
		
		return json_encode($result);
	}
	
	/**
	 * 获取用户今日已免密的总金额
	 * @param unknown $user_id
	 * @return string
	 */
	public function getTotalSecretMoney($user_id) {
		$criteria = new CDbCriteria();
		$criteria->addCondition('flag = :flag');
		$criteria->params[':flag'] = FLAG_NO;
		$criteria->addCondition('user_id = :user_id');
		$criteria->params[':user_id'] = $user_id;
		$criteria->addCondition('stored_confirm_status = :confirm');
		$criteria->params[':confirm'] = ORDER_PAY_NUCONFIRM;
		$criteria->addCondition('create_time > TO_DAYS(NOW())');
			
		$model = Order::model()->findAll($criteria);
			
		$total = 0;
		foreach ($model as $v) {
			$total += $v['stored_paymoney'];
		}
		return $total;
	}
	
	/**
	 * 增加用户消费积分
	 * @param unknown $user_id
	 * @param unknown $money
	 * @param unknown $from
	 * @param string $order_id
	 * @return multitype:string
	 */
	public function addUserPoints($user_id, $money, $from, $order_id) {
		$result = array();
		//查询用户信息
		$user = User::model()->findByPk($user_id);
		if (empty($user)) {
			$result['status'] = ERROR_NO_DATA;
			$result['errMsg'] = '用户不存在';
			return $result;
		}
		$merchant_id = $user['merchant_id'];
		//查询商户信息
// 		$merchant = Merchant::model()->findByPk($merchant_id);
// 		if (empty($merchant)) {
// 			$result['status'] = ERROR_NO_DATA;
// 			$result['errMsg'] = '该用户所属商户不存在';
// 			return $result;
// 		}
// 		$rule = $merchant['points_rule'];
		
		//查询用户的会员等级
		$criteria = new CDbCriteria();
		$criteria->addCondition('merchant_id = :merchant_id');
		$criteria->params[':merchant_id'] = $merchant_id;
		$criteria->addCondition('id = :id');
		$criteria->params[':id'] = $user['membershipgrade_id'];
		$criteria->addCondition('flag = :flag');
		$criteria->params[':flag'] = FLAG_NO;
		$user_grade = UserGrade::model()->find($criteria);
		if (empty($user_grade)) {
			$result['status'] = ERROR_NO_DATA;
			$result['errMsg'] = '用户所属会员等级不存在';
			return $result;
		}
		$ratio = !empty($user_grade['points_ratio']) ? $user_grade['points_ratio'] : 0;
		
		if ($from == USER_POINTS_DETAIL_FROM_STORED) {
			//如果是储值获得积分，则使用points_rule表的积分比率
			$rule = PointsRule::model()->find('merchant_id = :merchant_id and flag = :flag',
					array(':merchant_id' => $merchant_id, ':flag' => FLAG_NO));
			if (!empty($rule)) {
				$type = $rule['type'];
				if ($type == '1') {
					$ratio = !empty($rule['stored_points']) ? $rule['stored_points'] : 0;
				}
			}
		}
		
		//计算积分
		$points = $user['points'] + 0;
		$increase = floor($money * $ratio); //金额*积分比率，向下取整
		//所得积分小于或等于0，则不累计
		if ($increase <= 0) {
			$result['status'] = ERROR_NONE;
			return $result;
		}
		$points += $increase;
		$user['points'] = $points;
		//更新用户等级
		//查询商户的会员
		$criteria = new CDbCriteria();
		$criteria->order = 'points_rule desc';
		$criteria->addCondition('merchant_id = :merchant_id');
		$criteria->params[':merchant_id'] = $merchant_id;
		$criteria->addCondition('points_rule <= :points');
		$criteria->params[':points'] = $points;
		$criteria->addCondition('flag = :flag');
		$criteria->params[':flag'] = FLAG_NO;
		$grade = UserGrade::model()->find($criteria);
		if (!empty($grade)) {
			//会员等级是否受积分限制
			//受限制：会员积分必须大于等于会员等级的积分要求
			//不受限制：会员积分可以小于会员等级的积分要求，且等级不会改变，但当积分大于等于更高等级的积分要求时，会员依然可以升级
			if ($user['switch'] == POINTS_LIMIT_NO) { //不受限制
				//当前会员等级是否高于可升级的会员等级
				if ($user_grade['points_rule'] < $grade['points_rule']) { //会员可升级
					$user['membershipgrade_id'] = $grade['id'];
				}
			}else { //受限制
				$user['membershipgrade_id'] = $grade['id'];
			}
		}else {
			//查询默认会员等级
			$criteria = new CDbCriteria();
			$criteria->addCondition('merchant_id = :merchant_id');
			$criteria->params[':merchant_id'] = $merchant_id;
			$criteria->addCondition('if_default = :if_default');
			$criteria->params[':if_default'] = USER_GRADE_DEFAULT_YES;
			$criteria->addCondition('flag = :flag');
			$criteria->params[':flag'] = FLAG_NO;
			$grade = UserGrade::model()->find($criteria);
			
			//会员等级是否受积分限制
			//受限制：会员积分必须大于等于会员等级的积分要求
			//不受限制：会员积分可以小于会员等级的积分要求，且等级不会改变，但当积分大于等于更高等级的积分要求时，会员依然可以升级
			if ($user['switch'] != POINTS_LIMIT_NO) { //受限制
				$user['membershipgrade_id'] = $grade['id'];
			}
		}
		if ($user->save()) {
			//添加用户积分记录
			$record = new UserPointsdetail();
			$record['create_time'] = date('Y-m-d H:i:s');
			$record['user_id'] = $user_id;
			$record['order_id'] = $order_id;
			$record['points'] = $increase;
			$record['ratio'] = $ratio;
			$record['balance_of_payments'] = BALANCE_OF_PAYMENTS_INCOME;
			$record['from'] = $from;
			
			if ($record->save()) {
				$result['status'] = ERROR_NONE;
			}else {
				$result['status'] = ERROR_SAVE_FAIL;
				$result['errMsg'] = '数据保存失败';
			}
		}else {
			$result['status'] = ERROR_SAVE_FAIL;
			$result['errMsg'] = '数据保存失败';
		}
		
		return $result;
	}
	
	/**
	 * 扣除用户积分
	 * @param unknown $user_id
	 * @param unknown $money
	 * @param unkonwn $from
	 * @param unknown $order_id
	 * @return multitype:string
	 */
	public function reduceUserPoints($user_id, $money, $from, $order_id) {
		$result = array();
		//查询用户信息
		$user = User::model()->findByPk($user_id);
		if (empty($user)) {
			$result['status'] = ERROR_NO_DATA;
			$result['errMsg'] = '用户不存在';
			return $result;
		}
		$merchant_id = $user['merchant_id'];
		
		//查询用户的会员等级
		$criteria = new CDbCriteria();
		$criteria->addCondition('merchant_id = :merchant_id');
		$criteria->params[':merchant_id'] = $merchant_id;
		$criteria->addCondition('id = :id');
		$criteria->params[':id'] = $user['membershipgrade_id'];
		$criteria->addCondition('flag = :flag');
		$criteria->params[':flag'] = FLAG_NO;
		$user_grade = UserGrade::model()->find($criteria);
		if (empty($user_grade)) {
			$result['status'] = ERROR_NO_DATA;
			$result['errMsg'] = '用户所属会员等级不存在';
			return $result;
		}
		
		//查询积分的消费比率
		if($from == USER_POINTS_DETAIL_FROM_TRADE)  {
			$newFrom = USER_POINTS_DETAIL_FROM_REFUND_TRADE;
		} elseif($from == USER_POINTS_DETAIL_FROM_STORED) {
			$newFrom = USER_POINTS_DETAIL_FROM_REFUND_STORED;
		}
		$record = UserPointsdetail::model()->find('user_id = :user_id and order_id = :order_id and t.from = :from',
				array(':user_id' => $user_id, ':order_id' => $order_id, ':from' => $from));
		$ratio = $record['ratio'];
		
		//计算积分
		$points = $user['points'] + 0;
		$reduce = floor($money * $ratio); //金额*积分比率，向下取整
		//所得积分小于或等于0，则不累计
		if ($reduce <= 0) {
			$result['status'] = ERROR_NONE;
			return $result;
		}
		$points -= $reduce;
		//用户积分不能小于0
		$points = $points < 0 ? 0 : $points;
		$user['points'] = $points;
		//更新用户等级
		//查询商户的会员
		$criteria = new CDbCriteria();
		$criteria->order = 'points_rule desc';
		$criteria->addCondition('merchant_id = :merchant_id');
		$criteria->params[':merchant_id'] = $merchant_id;
		$criteria->addCondition('points_rule <= :points');
		$criteria->params[':points'] = $points;
		$criteria->addCondition('flag = :flag');
		$criteria->params[':flag'] = FLAG_NO;
		$grade = UserGrade::model()->find($criteria);
		if (!empty($grade)) {
			//会员等级是否受积分限制
			//受限制：会员积分必须大于等于会员等级的积分要求
			//不受限制：会员积分可以小于会员等级的积分要求，且等级不会改变，但当积分大于等于更高等级的积分要求时，会员依然可以升级
			if ($user['switch'] == POINTS_LIMIT_NO) { //不受限制
				//当前会员等级是否高于可升级的会员等级
				if ($user_grade['points_rule'] < $grade['points_rule']) { //会员可升级
					$user['membershipgrade_id'] = $grade['id'];
				}
			}else { //受限制
				$user['membershipgrade_id'] = $grade['id'];
			}
		}else {
			//查询默认会员等级
			$criteria = new CDbCriteria();
			$criteria->addCondition('merchant_id = :merchant_id');
			$criteria->params[':merchant_id'] = $merchant_id;
			$criteria->addCondition('if_default = :if_default');
			$criteria->params[':if_default'] = USER_GRADE_DEFAULT_YES;
			$criteria->addCondition('flag = :flag');
			$criteria->params[':flag'] = FLAG_NO;
			$grade = UserGrade::model()->find($criteria);
			
			//会员等级是否受积分限制
			//受限制：会员积分必须大于等于会员等级的积分要求
			//不受限制：会员积分可以小于会员等级的积分要求，且等级不会改变，但当积分大于等于更高等级的积分要求时，会员依然可以升级
			if ($user['switch'] != POINTS_LIMIT_NO) { //受限制
				$user['membershipgrade_id'] = $grade['id'];
			}
		}
		if ($user->save()) {
			//添加用户积分记录
			$record = new UserPointsdetail();
			$record['create_time'] = date('Y-m-d H:i:s');
			$record['user_id'] = $user_id;
			$record['order_id'] = $order_id;
			$record['points'] = $reduce;
			$record['ratio'] = $ratio;
			$record['balance_of_payments'] = BALANCE_OF_PAYMENTS_REFUND;
			$record['from'] = $newFrom;
				
			if ($record->save()) {
				$result['status'] = ERROR_NONE;
			}else {
				$result['status'] = ERROR_SAVE_FAIL;
				$result['errMsg'] = '数据保存失败';
			}
		}else {
			$result['status'] = ERROR_SAVE_FAIL;
			$result['errMsg'] = '数据保存失败';
		}
		
		return $result;
	}
	
	/**
	 * 更新用户储值
	 * @param unknown $user_id
	 * @param unknown $add_stored
	 * @return multitype:string
	 */
	public function updateUserStored($user_id, $add_stored) {
		$result = array();
		//查询用户信息
		$user = User::model()->findByPk($user_id);
		if (empty($user)) {
			$result['status'] = ERROR_NO_DATA;
			$result['errMsg'] = '用户不存在';
			return $result;
		}
		//当为扣除储值时，检查储值金额是否小于扣除金额
		if (bcsub(-$add_stored, $user['money'], 2) > 0) {
			$result['status'] = ERROR_NO_DATA;
			$result['errMsg'] = '储值金额不足，无法进行扣除';
			return $result;
		}
		
		$user['money'] += $add_stored;
		
		if ($user->save()) {
			$result['status'] = ERROR_NONE;
		}else {
			$result['status'] = ERROR_SAVE_FAIL;
			$result['errMsg'] = '数据保存失败';
		}
		
		return $result;
	}
	
	/**
	 * 获取所有可用会员等级
	 * @param unknown $merchant_id
	 * @return string
	 */
	public function getMemberGrades($merchant_id) {
		$result = array();
		try {
			//参数验证
			//TODO
			$list = UserGrade::model()->findAll('merchant_id = :merchant_id and flag = :flag',
					array(':merchant_id' => $merchant_id, ':flag' => FLAG_NO));
			
			$data = array();
			foreach ($list as $k => $v) {
				$data[] = array(
						'id' => $v['id'], //会员id
						'name' => $v['name'], //会员等级名称
				);
			}
				
			$result['data'] = $data;
			$result['status'] = ERROR_NONE; //状态码
			$result['errMsg'] = ''; //错误信息
		} catch (Exception $e) {
			$result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
			$result['errMsg'] = $e->getMessage(); //错误信息
		}
		
		return json_encode($result);
	}
	
	/**
	 * 统计用户数
	 * @param unknown $merchant_id
	 * @return string
	 */
	public function getUserCount($merchant_id) {
		$result = array();
		try {
			//参数验证
			//TODO
			//统计总用户数
			$criteria = new CDbCriteria();
			$criteria->addCondition('flag = :flag');
			$criteria->params[':flag'] = FLAG_NO;
			$criteria->addCondition('merchant_id = :merchant_id');
			$criteria->params[':merchant_id'] = $merchant_id;
			
			//用户数
			$user_count = User::model()->count($criteria);
			
			//统计总会员数
			$criteria->addCondition('type = :type');
			$criteria->params[':type'] = USER_TYPE_WANQUAN_MEMBER;
			
			//会员数
			$member_count = User::model()->count($criteria);
			
			$data = array('user_count' => $user_count, 'member_count' => $member_count);
		
			$result['data'] = $data;
			$result['status'] = ERROR_NONE; //状态码
			$result['errMsg'] = ''; //错误信息
		} catch (Exception $e) {
			$result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
			$result['errMsg'] = $e->getMessage(); //错误信息
		}
		
		return json_encode($result);
	}
	
	/**
	 * 获取用户列表 （app）
	 * @param unknown $merchant_id
	 * @param unknown $limit_id
	 * @param unknown $start_date
	 * @param unknown $end_date
	 * @param unknown $select_sex
	 * @param unknown $select_grade
	 * @param unknown $sort_points
	 * @param unknown $sort_join_time
	 * @param unknown $keyword
	 * @throws Exception
	 * @return string
	 */
	public function getUserList4App($merchant_id, $limit_id, $start_date, $end_date, $select_sex, $select_grade, $sort_points, $sort_join_time, $keyword) {
		$result = array();
		try {
			//参数验证
			//TODO
			$merchant = Merchant::model()->find('id = :id and flag = :flag',
					array(':id' => $merchant_id, ':flag' => FLAG_NO));
			if (empty($merchant)) {
				$result['status'] = ERROR_NO_DATA;
				throw new Exception('商户信息有误');
			}
	
			//查询会员记录列表
			$criteria = new CDbCriteria();
			$criteria->addCondition('merchant_id = :merchant_id');
			$criteria->params[':merchant_id'] = $merchant_id;
			$criteria->addCondition('flag = :flag');
			$criteria->params[':flag'] = FLAG_NO;
			$criteria->addCondition('type = :type');
			$criteria->params[':type'] = USER_TYPE_WANQUAN_MEMBER;
			$criteria->order = 'create_time desc';
	
			if (!empty($limit_id) && $limit_id != 'ALL') {
				$criteria->addCondition('id < :id');
				$criteria->params[':id'] = $limit_id;
			}
			if (!empty($start_date)) {
				$criteria->addCondition('create_time >= :start_time');
				$criteria->params[':start_time'] = $start_date;
			}
			if (!empty($end_date)) {
				$criteria->addCondition('create_time <= :end_time');
				$criteria->params[':end_time'] = $end_date;
			}
			if (!empty($select_sex)) {
				$criteria->addCondition('sex = :sex');
				$criteria->params[':sex'] = $select_sex;
			}
			if (!empty($select_grade)) {
				$criteria->addCondition('membershipgrade_id = :membershipgrade_id');
				$criteria->params[':membershipgrade_id'] = $select_grade;
			}
			if (!empty($sort_points)) {
				if ($sort_points == 'ASC') {
					$criteria->order = 'points asc';
				}
				if ($sort_points == 'DESC') {
					$criteria->order = 'points desc';
				}
			}
			if (!empty($sort_join_time)) {
				if ($sort_join_time == 'ASC') {
					$criteria->order = 'create_time asc';
				}
				if ($sort_join_time == 'DESC') {
					$criteria->order = 'create_time desc';
				}
			}
			if (!empty($keyword)) {
				$criteria->addCondition("(account like :account) OR (name like :name) OR (membership_card_no like :membership_card_no)");
				$criteria->params[':account'] = '%'.$keyword.'%';
				$criteria->params[':name'] = '%'.$keyword.'%';
				$criteria->params[':membership_card_no'] = '%'.$keyword.'%';
			}
			//是否获取只获取指定页码的数据，ALL：获取所有数据, 数字：指定页的数据
			if ($limit_id != 'ALL') {
				$page_size = Yii::app()->params['perPage'];
				$criteria->limit = $page_size ? : 10;
			}
			//$pages = new CPagination(Order::model()->count($criteria));
			//$pages->pageSize = isset($perPage) && $perPage ? $perPage : Yii::app() -> params['perPage'];
			//$pages->applyLimit($criteria);
			$list = User::model()->findAll($criteria);
			//$data = $this->returnOrder($list);
			$data = array();
			foreach($list as $v) {
				$grade = '';
				if (!empty($v['membershipgrade_id'])) {
					$user_grade = UserGrade::model()->findByPk($v['membershipgrade_id']);
					if (!empty($user_grade)) {
						$grade = $user_grade['name'];
					}
				}
	
				$tmp = $v->getAttributes();
				$tmp['grade'] = $grade;
	
				$data[] = $tmp;
			}
	
			$result['item_count'] = count($data);
			$result['page_count'] = '';
			$result['data'] = $data;
			$result['status'] = ERROR_NONE; //状态码
			$result['errMsg'] = ''; //错误信息
		} catch (Exception $e) {
			$result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
			$result['errMsg'] = $e->getMessage(); //错误信息
		}
	
		return json_encode($result);
	}
	
	
	/**
	 * 获取会员列表 （pc）
	 * @param unknown $merchant_id
	 * @param unknown $page_num
	 * @param unknown $start_date
	 * @param unknown $end_date
	 * @param unknown $select_sex
	 * @param unknown $select_grade
	 * @param unknown $sort_points
	 * @param unknown $sort_join_time
	 * @param unknown $keyword
	 * @throws Exception
	 * @return string
	 */
	public function getUserList4PC($merchant_id, $page_num, $start_date, $end_date, $select_sex, $select_grade, $sort_points, $sort_join_time, $keyword) {
		$result = array();
		try {
			//参数验证
			//TODO
			$merchant = Merchant::model()->find('id = :id and flag = :flag',
					array(':id' => $merchant_id, ':flag' => FLAG_NO));
			if (empty($merchant)) {
				$result['status'] = ERROR_NO_DATA;
				throw new Exception('商户信息有误');
			}
			 
			//查询会员记录列表
			$criteria = new CDbCriteria();
			$criteria->addCondition('merchant_id = :merchant_id');
			$criteria->params[':merchant_id'] = $merchant_id;
			$criteria->addCondition('flag = :flag');
			$criteria->params[':flag'] = FLAG_NO;
			$criteria->addCondition('type = :type');
			$criteria->params[':type'] = USER_TYPE_WANQUAN_MEMBER;
			$criteria->order = 'create_time desc';
		
			if (!empty($start_date)) {
				$criteria->addCondition('create_time >= :start_time');
				$criteria->params[':start_time'] = $start_date;
			}
			if (!empty($end_date)) {
				$criteria->addCondition('create_time <= :end_time');
				$criteria->params[':end_time'] = $end_date;
			}
			if (!empty($select_sex)) {
				$criteria->addCondition('sex = :sex');
				$criteria->params[':sex'] = $select_sex;
			}
			if (!empty($select_grade)) {
				$criteria->addCondition('membershipgrade_id = :membershipgrade_id');
				$criteria->params[':membershipgrade_id'] = $select_grade;
			}
			if (!empty($sort_points)) {
				if ($sort_points == 'ASC') {
					$criteria->order = 'points asc';
				}
				if ($sort_points == 'DESC') {
					$criteria->order = 'points desc';
				}
			}
			if (!empty($sort_join_time)) {
				if ($sort_join_time == 'ASC') {
					$criteria->order = 'create_time asc';
				}
				if ($sort_join_time == 'DESC') {
					$criteria->order = 'create_time desc';
				}
			}
			if (!empty($keyword)) {
				$criteria->addCondition("(account like :account) OR (name like :name) OR (membership_card_no like :membership_card_no)");
				$criteria->params[':account'] = '%'.$keyword.'%';
				$criteria->params[':name'] = '%'.$keyword.'%';
				$criteria->params[':membership_card_no'] = '%'.$keyword.'%';
			}
		
			//是否获取只获取指定页码的数据，ALL：获取所有数据, 数字：指定页的数据
			$total_page = 1;
			if ($page_num != 'ALL') {
				//配置的每页显示数量
				$page_size = Yii::app()->params['perPage'];
				//计算总页数
				$total_num = User::model()->count($criteria);
				$total_page = ceil($total_num / $page_size);
				//翻页
				$criteria->limit = $page_size ? : 10;
				$page_num += 0;
				if (!empty($page_num) && $page_num > 0) {
					$criteria->offset = $page_size * ($page_num -1);
				}
			}
		
			//$pages = new CPagination(Order::model()->count($criteria));
			//$pages->pageSize = isset($perPage) && $perPage ? $perPage : Yii::app() -> params['perPage'];
			//$pages->applyLimit($criteria);
			$list = User::model()->findAll($criteria);
			//$data = $this->returnOrder($list);
			$data = array();
			foreach($list as $v) {
				$grade = '';
				if (!empty($v['membershipgrade_id'])) {
					$user_grade = UserGrade::model()->findByPk($v['membershipgrade_id']);
					if (!empty($user_grade)) {
						$grade = $user_grade['name'];
					}
				}
				
				$tmp = $v->getAttributes();
				$tmp['grade'] = $grade;
		
				$data[] = $tmp;
			}
		
			$result['item_count'] = count($data);
			$result['page_count'] = $total_page;
			$result['data'] = $data;
			$result['status'] = ERROR_NONE; //状态码
			$result['errMsg'] = ''; //错误信息
		} catch (Exception $e) {
			$result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
			$result['errMsg'] = $e->getMessage(); //错误信息
		}
		
		return json_encode($result);
	}
	
}