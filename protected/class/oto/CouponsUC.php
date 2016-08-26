<?php
include_once (dirname ( __FILE__ ) . '/../mainClass.php');

/**
 * 卡券类
*/

class CouponsUC extends mainClass{
	/**
	 * 获取卡券信息
	 * @param unknown $coupon_no
	 * @param unknown $user_id
	 * @throws Exception
	 * @return string
	 */
	public function getCouponsInfo($coupon_no, $user_id) {
		$result = array();
		try {
			//参数验证
			//TODO
			//获取用户信息
			$user = User::model()->findByPk($user_id);
			if (empty($user)) {
				$result['status'] = ERROR_NO_DATA;
				throw new Exception('会员不存在');
			}
				
			$criteria = new CDbCriteria();
			$criteria->addCondition('code = :code');
			$criteria->addCondition('flag = :flag');
			$criteria->params = array(
					':code' => $coupon_no,
					':flag' => FLAG_NO
			);
			$model = UserCoupons::model()->find($criteria);
			if (empty($model)) {
				$result['status'] = ERROR_NO_DATA;
				throw new Exception('优惠券不存在');
			}
				
			//优惠券状态
			if ($model['status'] == COUPONS_USE_STATUS_USED) {
				$result['status'] = ERROR_EXCEPTION;
				throw new Exception('优惠券已被使用');
			}
			if ($model['status'] == COUPONS_USE_STATUS_EXPIRED) {
				$result['status'] = ERROR_EXCEPTION;
				throw new Exception('优惠券已过期');
			}
			//已转赠
			if ($model['status'] == COUPONS_USE_STATUS_GAVE) {
				$result['status'] = ERROR_EXCEPTION;
				throw new Exception('优惠券已转赠');
			}
				
			//优惠券有效期
			if (strtotime($model['end_time']) < time()) {
				$result['status'] = ERROR_EXCEPTION;
				throw new Exception('优惠券已过期');
			}
				
			$coupon = Coupons::model()->findByPk($model['coupons_id']);
			if (empty($coupon)) {
				$result['status'] = ERROR_EXCEPTION;
				throw new Exception('系统内部错误');
			}
				
			//是否该商户优惠券
			if ($user['merchant_id'] != $coupon['merchant_id']) {
				$result['status'] = ERROR_EXCEPTION;
				throw new Exception('非该商户发放的优惠券');
			}
			//渠道限制
			if ($coupon['use_channel'] != COUPONS_USE_CHANNEL_ALL && $coupon['use_channel'] != COUPONS_USE_CHANNEL_ONLINE) {
				$result['status'] = ERROR_EXCEPTION;
				throw new Exception('优惠券不能在线上使用');
			}
				
			$data = array();
			$data['id'] = $model['id']; //用户优惠券表id
			$data['cid'] = $model['coupons_id']; //优惠券id
			$data['user_id'] = $model['user_id']; //拥有者会员id
			$data['money'] = $model['money']; //抵扣金额
			$data['start_time'] = $model['start_time']; //开始时间
			$data['end_time'] = $model['end_time']; //结束时间
			$data['code'] = $model['code']; //卡券编码
				
			$data['type'] = $coupon['type']; //类型
			$data['discount'] = $coupon['discount']; //折扣
			$data['title'] = $coupon['title']; //标题
			$data['vice_title'] = $coupon['vice_title']; //副标题
			$data['prompt'] = $coupon['prompt']; //提示操作
			$data['use_restriction'] = $coupon['use_restriction']; //单订单可使用数
			$data['mini_consumption'] = $coupon['mini_consumption']; //最小消费
			$data['if_with_userdiscount'] = $coupon['if_with_userdiscount']; //是否能与会员折扣同用
			$data['store_limit'] = $coupon['store_limit']; //门店限制
			$data['use_illustrate'] = $coupon['use_illustrate']; //使用须知
			$data['discount_illustrate'] = $coupon['discount_illustrate']; //优惠说明
			$data['if_invalid'] = $coupon['if_invalid']; //是否失效
				
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
	 * 会员优惠券列表(可用优惠券)
	 * @param $user_id 会员id
	 * @return array 返回数组
	 */
	public function getUserCouponList($user_id) {
		$result = array();
		try {
			//参数验证
			//TODO
			//过期检查
			$criteria = new CDbCriteria();
			$criteria->addCondition('UNIX_TIMESTAMP(end_time) < UNIX_TIMESTAMP()');
			$criteria->addCondition('user_id = :user_id');
			$criteria->addCondition('status = :status');
			$criteria->addCondition('flag = :flag');
			$criteria->params = array(
					':user_id' => $user_id,
					':status' => COUPONS_USE_STATUS_UNUSE,
					':flag' => FLAG_NO
			);
			$model = UserCoupons::model()->findAll($criteria);
			//将所有过期的用户优惠券状态修改为已过期
			foreach ($model as $k => $v) {
				$v['status'] = COUPONS_USE_STATUS_EXPIRED;
				$v->save();
			}
	
			$cmd = Yii::app()->db->createCommand();
			$cmd->select('c.id cid, c.use_restriction, c.mini_consumption, u.id, title, type, u.money, c.discount, u.start_time, u.end_time'); //查询字段
			$cmd->order = 'type asc';
			$cmd->from(array('wq_user_coupons u','wq_coupons c')); //查询表名
			$cmd->where(array(
					'AND',  //and操作
					'u.coupons_id = c.id', //联表
					'u.flag = :flag', //未删除
					'u.status = :status', //未使用
					'u.user_id = :user_id', //用户id
					'c.use_channel != :use_channel', //核销渠道
					'UNIX_TIMESTAMP(u.start_time) < UNIX_TIMESTAMP()',
					'UNIX_TIMESTAMP(u.end_time) > UNIX_TIMESTAMP()',
					//'IFNULL(c.mini_consumption, 0) <= :money', //大于最小消费金额(NULL转0)
			));
			//查询参数
			$cmd->params = array(
					':flag' => FLAG_NO,
					':status' => COUPONS_USE_STATUS_UNUSE,
					':user_id' => $user_id,
					':use_channel' => COUPONS_USE_CHANNEL_OFFLINE
			);
			//执行sql，获取所有行数据
			$model = $cmd->queryAll();
			//数据封装
			$data = array('list' => array());
			foreach ($model as $key => $value) {
				$data['list'][$key]['id'] = $value['id']; //id
				$data['list'][$key]['cid'] = $value['cid']; //cid
				$data['list'][$key]['title'] = $value['title']; //优惠券标题
				$data['list'][$key]['type'] = $value['type']; //优惠券类型
				$data['list'][$key]['money'] = $value['money']; //优惠券金额面值
				$data['list'][$key]['discount'] = $value['discount']; //折扣
				$data['list'][$key]['illustrate'] = $value['discount_illustrate']; //优惠说明
				$data['list'][$key]['mini_consumption'] = $value['mini_consumption']; //最小消费
				$data['list'][$key]['use_restriction'] = $value['use_restriction']; //单个订单可使用张数
				//$data['list'][$key]['max_discount_money'] = $value['max_discount_money']; //最大折扣金额
				//$data['list'][$key]['many'] = $value['allow_many']; //允许和其他优惠券同时使用
				$data['list'][$key]['start_time'] = $value['start_time']; //开始时间
				$data['list'][$key]['end_time'] = $value['end_time']; //过期时间
			}
			//分页
			//TODO
			$data['page'] = '';
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
	 * 获取用户优惠券详情
	 * @param unknown $user_coupons_id
	 * @throws Exception
	 * @return string
	 */
	public function getCouponsDetail($user_coupons_id) {
		$result = array();
		try {
			//参数验证
			if (empty($user_coupons_id)) {
				throw new Exception('用户优惠券id不能为空');
			}
			//优惠券查询
			$model = UserCoupons::model()->findByPk($user_coupons_id);
			if (empty($model)) {
				$result['status'] = ERROR_NO_DATA;
				throw new Exception('优惠券不存在');
			}
			//是否删除
			if ($model['flag'] == FLAG_YES) {
				$result['status'] = ERROR_NO_DATA;
				throw new Exception('无效的优惠券');
			}
			//优惠券活动信息
			$cou = Coupons::model()->findByPk($model['coupons_id']);
			if (empty($cou)) {
				$result['status'] = ERROR_NO_DATA;
				throw new Exception('无效的优惠券');
			}
			//核销渠道
			if ($cou['use_channel'] != COUPONS_USE_CHANNEL_ALL && $cou['use_channel'] != COUPONS_USE_CHANNEL_ONLINE) {
				$result['status'] = ERROR_NO_DATA;
				throw new Exception('优惠券不能在线上使用');
			}
			//可用门店
			$stores = '';
				
			$data = array();
			$data['title'] = $cou['title']; // 优惠券名称
			$data['type'] = $cou['type']; //优惠券类型
			$data['start_time'] = $model['start_time']; //开始时间
			$data['end_time'] = $model['end_time']; //结束时间
			$data['discount'] = $cou['discount']; //折扣券折扣
			$data['coupon_illustrate'] = $cou['discount_illustrate']; //优惠说明
			$data['money'] = $model['money']; //红包或代金券的金额
			$data['store'] = $stores; //可用门店名称
			$data['min_pay'] = $cou['mini_consumption']; //最低消费
			$data['use_restriction'] = $cou['use_restriction']; //单个订单使用张数
			$data['with_discount'] = $cou['if_with_userdiscount']; //能否与会员折扣同用
			//$data['with_coupons'] = $cou['if_with_coupons']; //红包能否与优惠券(代金券或折扣券)同用
	
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
	 * 优惠券计算
	 * @param $user_id 用户id
	 * @param $uc_list 用户优惠券列表
	 * @param $money 需付款金额
	 * @param $use_discount 是否使用会员折扣 (true,false)
	 * @return array 返回数组
	 */
	public function getCouponsPay($user_id, $uc_list, $money, $use_discount) {
		$result = array();
		try {
			$red = 0;
			$coupons = 0;
			$data = array('need' => $money, 'coupons' => $coupons);
			//查询操作员,获取所在门店id
// 			$operator = Operator::model()->findByPk($operator_id);
// 			$store_id = $operator['store_id'];
				
			//折扣券折扣
			$discount = 1;
			//红包数组,red_arr[红包id]=红包数量
			$red_arr = array();
			//代金券数组，cash_arr[代金券id]=代金券数量
			$cash_arr = array();
			//折扣券数量
			$dis_num = 0;
			//如果有红包，是否与优惠券冲突
			$red_only = false;
			//原订单总金额
			$original = $money;
				
			//生成待查询sql语句
			$cmd = Yii::app()->db->createCommand();
			$cmd->select('c.id, c.title, c.type, c.use_channel, u.start_time, u.end_time, u.money, c.discount, c.mini_consumption, c.store_limit, c.use_restriction, c.if_with_userdiscount'); //查询字段
			$cmd->from(array('wq_user_coupons u','wq_coupons c')); //查询表名
			$where = array(
					'AND',  //and操作
					'u.coupons_id = c.id', //联表
					'u.id = :uid',
					'u.flag = :flag', //未删除
					'u.status = :status', //未使用
			);
			//查询参数
			$cmd->params = array(
					':flag' => FLAG_NO,
					':status' => COUPONS_USE_STATUS_UNUSE
			);
// 			if (!empty($user_id)) {
// 				array_push($where, 'u.user_id = :user_id'); //用户id
// 				$cmd->params[':user_id'] = $user_id;
// 			}
			$cmd->where($where);
				
			//遍历优惠券列表
			$list = explode(",", $uc_list);
			foreach ($list as $val) {
				if (empty($val)) {
					continue;
				}
				$cmd->params[':uid'] = $val;
				//执行sql
				$model = $cmd->queryRow(); //查询优惠券
				//是否存在
				if (empty($model)) {
					$result['status'] = ERROR_YHQ_INVALID_DATA;
					throw new Exception($GLOBALS['MSG_YHQ'][$result['status']]);
				}
				//优惠券是否有所有者，如果有，是否与使用者一致
				if (!empty($model['user_id']) && $model['user_id'] != $user_id) {
					$result['status'] = ERROR_YHQ_INVALID_BELONG;
					throw new Exception($GLOBALS['MSG_YHQ'][$result['status']]);
				}
				//渠道可用
				if ($model['use_channel'] != COUPONS_USE_CHANNEL_ALL && $model['use_channel'] != COUPONS_USE_CHANNEL_ONLINE) {
					$result['status'] = ERROR_YHQ_INVALID_CHANNEL;
					throw new Exception($GLOBALS['MSG_YHQ'][$result['status']]);
				}
				//门店可用
// 				if (!strstr($model['store_limit'], $store_id)) {
// 					$result['status'] = ERROR_YHQ_INVALID_STORE;
// 					throw new Exception($GLOBALS['MSG_YHQ'][$result['status']]);
// 				}
				//有效期
				if (strtotime($model['start_time']) > time() || strtotime($model['end_time']) < time()) {
					$result['status'] = ERROR_YHQ_INVALID_DATE;
					throw new Exception($GLOBALS['MSG_YHQ'][$result['status']]);
				}
				//最小消费金额
				if (empty($model['mini_consumption'])) {
					$min = 0;
				}else {
					$min = $model['mini_consumption'];
				}
				if ($original < $min) {
					$result['status'] = ERROR_YHQ_INVALID_MONEY;
					throw new Exception($GLOBALS['MSG_YHQ'][$result['status']]);
				}
				//优惠券类型
				switch ($model['type']) {
// 					case COUPON_TYPE_REDENVELOPE: { //红包
// 						//会员折扣互斥
// 						if ($use_discount && $model['if_with_userdiscount'] == IF_WITH_USERDISCOUNT_NO) {
// 							$result['status'] = ERROR_YHQ_NO_USER_DISCOUNT;
// 							throw new Exception($GLOBALS['MSG_YHQ'][$result['status']]);
// 						}
// 						//保存红包id与数量
// 						if (!isset($red_arr[$model['id']])) {
// 							$red_arr[$model['id']] = 1;
// 						}else {
// 							$red_arr[$model['id']] ++;
// 						}
// 						//单个订单使用张数
// 						if ($red_arr[$model['id']] > $model['use_restriction']) {
// 							$result['status'] = ERROR_YHQ_INVALID_NUM;
// 							throw new Exception($GLOBALS['MSG_YHQ'][$result['status']]);
// 						}
// 						//多种红包冲突
// 						if (count($red_arr) > 1) {
// 							$result['status'] = ERROR_YHQ_NO_REDS;
// 							throw new Exception($GLOBALS['MSG_YHQ'][$result['status']]);
// 						}
// 						//设置与其他优惠券同用的标志
// 						if ($model['if_with_coupons'] == IF_WITH_COUPONS_NO) {
// 							$red_only = true;
// 						}
// 						//与其他优惠券同用
// 						if ($red_only && (count($cash_arr) > 0 || $dis_num > 0)) {
// 							$result['status'] = ERROR_YHQ_NO_COUPONS;
// 							throw new Exception($GLOBALS['MSG_YHQ'][$result['status']]);
// 						}
// 						//金额计算
// 						$red += $model['money'];
// 						$money -= $model['money'];
// 						break;
// 					}
					case COUPON_TYPE_CASH: { //代金券
						//会员折扣互斥
						if ($use_discount && $model['if_with_userdiscount'] == IF_WITH_USERDISCOUNT_NO) {
							$result['status'] = ERROR_YHQ_NO_USER_DISCOUNT;
							throw new Exception($GLOBALS['MSG_YHQ'][$result['status']]);
						}
						//保存代金券id与数量
						if (!isset($cash_arr[$model['id']])) {
							$cash_arr[$model['id']] = 1;
						}else {
							$cash_arr[$model['id']] ++;
						}
						//单个订单使用张数
						if ($cash_arr[$model['id']] > $model['use_restriction']) {
							$result['status'] = ERROR_YHQ_INVALID_NUM;
							throw new Exception($GLOBALS['MSG_YHQ'][$result['status']]);
						}
						//是否有红包冲突
// 						if ($red_only) {
// 							$result['status'] = ERROR_YHQ_HAS_RED_ONLY;
// 							throw new Exception($GLOBALS['MSG_YHQ'][$result['status']]);
// 						}
						//与其他优惠券冲突
						if (count($cash_arr) > 1 || $dis_num > 0) {
							$result['status'] = ERROR_YHQ_NO_COUPONS;
							throw new Exception($GLOBALS['MSG_YHQ'][$result['status']]);
						}
						//最小消费金额
						if ($original < ($model['mini_consumption'] * $cash_arr[$model['id']])) {
							$result['status'] = ERROR_YHQ_INVALID_MONEY;
							throw new Exception($GLOBALS['MSG_YHQ'][$result['status']]);
						}
						//代金券计算
						$coupons += $model['money'];
						$money -= $model['money'];
						break;
					}
					case COUPON_TYPE_DISCOUNT: { //折扣券
						//会员折扣互斥
						if ($use_discount && $model['if_with_userdiscount'] == IF_WITH_USERDISCOUNT_NO) {
							$result['status'] = ERROR_YHQ_NO_USER_DISCOUNT;
							throw new Exception($GLOBALS['MSG_YHQ'][$result['status']]);
						}
						//折扣券张数
						$dis_num ++;
						if ($dis_num > 1) {
							$result['status'] = ERROR_YHQ_NO_DISCOUNTS;
							throw new Exception($GLOBALS['MSG_YHQ'][$result['status']]);
						}
						//红包冲突
// 						if ($red_only) {
// 							$result['status'] = ERROR_YHQ_HAS_RED_ONLY;
// 							throw new Exception($GLOBALS['MSG_YHQ'][$result['status']]);
// 						}
						//其他优惠券冲突
						if (count($cash_arr) > 0) {
							$result['status'] = ERROR_YHQ_NO_COUPONS;
							throw new Exception($GLOBALS['MSG_YHQ'][$result['status']]);
						}
						//保存折扣
						if (!empty($model['discount']) && $model['discount'] > 0 && $model['discount'] <= 1) {
							$discount = $model['discount'];
						}
						break;
					}
					case COUPON_TYPE_EXCHANGE: { //兑换券
						break;
					}
					default:{
						$result['status'] = ERROR_YHQ_INVALID_TYPE;
						throw new Exception($GLOBALS['MSG_YHQ'][$result['status']]);
					}
				}
			}
			//计算折扣后的金额
			$coupons += $money * (1 - $discount);
			$money *= $discount;
			$data['need'] = $money > 0 ? $money : 0;
			//$data['red'] = $red;
			$data['coupons'] = $coupons;
				
			$result['data'] = $data;
			$result['status'] = ERROR_NONE;
			$result['errMsg'] = '';
		} catch (Exception $e) {
			$result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
			$result['errMsg'] = $e->getMessage(); //错误信息
		}
		return json_encode($result);
	}
	
	/**
	 * 修改优惠券使用状态
	 * @param unknown $uc_list
	 * @param unknown $order_id
	 * @return string
	 */
	public function setUseStatus($uc_list, $order_id, $status) {
		$result = array();
		try {
			//参数验证
			if (empty($status)) {
				throw new Exception('优惠券状态不能为空');
			}
			if ($status != COUPONS_USE_STATUS_UNUSE && $status != COUPONS_USE_STATUS_USED && $status != COUPONS_USE_STATUS_EXPIRED && $status != COUPONS_USE_STATUS_GAVE) {
				throw new Exception('无效的优惠券使用状态');
			}
				
			$list = explode(",", $uc_list);
			foreach ($list as $v) {
				if (empty($v)) {
					continue;
				}
				$model = UserCoupons::model()->findByPk($v);
				if (!empty($model)) {
					$model['order_id'] = $order_id;
					$model['status'] = $status;
					if ($status == COUPONS_USE_STATUS_USED) {
						$model['use_time'] = date('Y-m-d H:i:s');
					}
					if (!$model->save()) {
						throw new Exception('优惠券修改失败');
					}
				}
			}
	
			$result['data'] = '';
			$result['status'] = ERROR_NONE; //状态码
			$result['errMsg'] = ''; //错误信息
		} catch (Exception $e) {
			$result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
			$result['errMsg'] = $e->getMessage(); //错误信息
		}
	
		return json_encode($result);
	}
	
	/**
	 * 获取订单中使用到得微信卡券
	 * @param unknown $order_id
	 * @throws Exception
	 * @return string
	 */
	public function getWxCoupons($order_id) {
		$result = array();
		try {
			//参数验证
			if (empty($order_id)) {
				throw new Exception('订单id不能为空');
			}
	
			$list = array();
			$model = UserCoupons::model()->findAll('order_id = :order_id and if_wechat = :if_wechat', array(':order_id' => $order_id, ':if_wechat' => COUPONS_IF_WECHAT_YES));
			foreach ($model as $k => $v) {
				$list[] = array('code' => $v['code']);
			}
				
			$result['list'] = $list;
			$result['status'] = ERROR_NONE; //状态码
			$result['errMsg'] = ''; //错误信息
		} catch (Exception $e) {
			$result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
			$result['errMsg'] = $e->getMessage(); //错误信息
		}
	
		return json_encode($result);
	}
	
	/**
	 * 根据id获取券信息
	 * @param unknown $id
	 */
	public function getCouponsById($id){
	    $result = array();
	    $data = array();
	    try {
	        if (empty($id)){
	            throw new Exception('券id不能为空');
	        }
	        $model = Coupons::model()->find('id = :id',array(':id'=>$id));
	        if (!empty($model)){
            foreach($model as $k => $v){
                $data[$k] = $v;
            }
	        $result['status'] = ERROR_NONE;
	        $result['errMsg'] = '';
	        }else{
	            throw new Exception('此券不存在');
	        }
	    }catch (Exception $e){
	        $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
	        $result['errMsg'] = $e->getMessage();
	    }
	    $result['data'] = $data;
	    return json_encode($result);
	}
	
}