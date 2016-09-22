<?php
/**
 * 用户管理
 */
// error_reporting(E_ALL);
class UserController extends CrmController
{

    /**
     * 用户管理
     */
    public function actionUserLists()
    {
        $start_time = microtime(true);
        $merchant_id = Yii::app()->session['merchant_id'];
        $storeC = new StoreC();
        $choseCities = array();
        if (isset($_GET['city']) && !empty($_GET['city'])) {
            $cities = $_GET['city'];
            $provinces = $_GET['province'];
            foreach ($cities as $k => $v) {
                $choseCities[$k]['pcode'] = $provinces[$k];
                $choseCities[$k]['pname'] = $storeC->getProvinceCityName($provinces[$k]);
                $choseCities[$k]['ccode'] = $cities[$k];
                $choseCities[$k]['cname'] = $storeC->getProvinceCityName($cities[$k]);
            }
            unset($cities);
            unset($provinces);
        }
        //设置省市区数据源
        $province = json_decode($storeC->getProvince());
        $province = $province->data;

        //分页
        $pageSize = Yii::app()->params['perPage'];
        $page = isset($_GET['page']) ? (CHtml::encode($_GET['page']) - 1) * $pageSize : 0;
        $limit = $page . ', ' . $pageSize;

        $subSql = '';
        $whereSql = '1=1 ';
        //微信支付宝筛选条件
        $wechatAlipaySql = ' 1 = 1 ';
        //排序条件
        $orderSql = '';

        //关键字模糊查询
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
        if (!empty($keyword)) {
            $whereSql .= ' AND (nickname LIKE "%' . $keyword . '%" OR account LIKE "%' . $keyword . '%" OR name LIKE "%' . $keyword . '%" OR wechat_nickname LIKE "%' . $keyword . '%" OR alipay_nickname LIKE "%' . $keyword . '%") ';
        }

        //会员等级查询
        $grades = isset($_GET['grade']) ? $_GET['grade'] : '';
        if (!empty($grades)) {
            $whereSql .= ' AND t.membershipgrade_id IN (' . $this -> changeArray($grades) . ') AND t.type = ' . USER_TYPE_WANQUAN_MEMBER;
        }

        //支付宝状态查询条件
        $alipay_status = isset($_GET['alipay_status']) ? $_GET['alipay_status'] : '';
        if (!empty($alipay_status)) {
            $wechatAlipaySql .= ' AND alipay_status IN (' . $this -> changeArray($alipay_status) . ')';
        }

        //微信状态查询条件
        $wechat_status = isset($_GET['wechat_status']) ? $_GET['wechat_status'] : '';
        if (!empty($wechat_status)) {
            $wechatAlipaySql .= ' AND wechat_status IN (' . $this -> changeArray($wechat_status) . ')';
        }

        //分组查询
        $groups = isset($_GET['group']) ? $_GET['group'] : '';
        if (!empty($groups)) {
            $criteria2 = new CDbCriteria();
            $criteria2->addCondition('flag=' . FLAG_NO);
            $criteria2->addInCondition('group_id', $groups);
            $getGroups = Group::model()->findAll($criteria2);
            $ids = array();
            foreach ($getGroups as $v) {
                $ids[] = $v['user_id'];
            }
            $whereSql .= ' AND t.id IN(' . $this -> changeArray($ids) . ')';
        }
        //性别查询
        $sexes = isset($_GET['sex']) ? $_GET['sex'] : '';
        if (!empty($sexes)) {
            $whereSql .= ' AND IFNULL(t.sex, 3) IN (' . $this -> changeArray($sexes) .')';
        }

        //门店查询
        $store_ids = isset($_GET['store_id']) ? $_GET['store_id'] : '';
        $store_names = isset($_GET['store_name']) ? $_GET['store_name'] : '';
        if (!empty($store_ids)) {
            $store_ids_str = implode(',', $store_ids);
            $whereSql .= ' AND t.last_trade_store IN(' . $store_ids_str . ')';
            /*$criteria3 = new CDbCriteria();
            $criteria3->select = 'DISTINCT(user_id)';
            $criteria3->addInCondition('store_id', $store_ids);
            $orders = Order::model()->findAll($criteria3);
            $ids = array();
            foreach ($orders as $order) {
                $ids[] = $order['user_id'];
            }
            $whereSql .= ' AND t.id IN(' . $this -> changeArray($ids) . ')';*/
        }

        //年龄查询
        $age_min = isset($_GET['age_min']) ? $_GET['age_min'] : '';
        $age_max = isset($_GET['age_max']) ? $_GET['age_max'] : '';
        if ((!empty($age_min) || $age_min === '0') && (!empty($age_max) || $age_max === '0') && $age_max >= $age_min) {
            $s_data = date('Y') - $age_max;
            $e_data = date('Y') - $age_min;
            $whereSql .= ' AND YEAR(t.birthday) BETWEEN "' . $s_data . '" AND "' . $e_data . '"';
        }
        if ($age_min > $age_max)
            Yii::app()->user->setFlash('age_err_tip', '前者不可大于后者！');

        //注册时间查询
        $reg_time = isset($_GET['reg_time']) ? $_GET['reg_time'] : '';
        if (!empty($reg_time)) {
            $regist_time = explode('-', $reg_time);
            $start = date('Y-m-d' . ' 00:00:00', strtotime($regist_time[0]));
            $end = date('Y-m-d' . ' 23:59:59', strtotime($regist_time[1]));
            $reg_time_start = $start < $end ? $start : $end;
            $reg_time_end = $end > $start ? $end : $start;
            $whereSql .= ' AND t.regist_time BETWEEN "' . $reg_time_start . '" AND "' . $reg_time_end . '"';
        }

        //支付宝服务窗关注时间查询
        $fw_time = isset($_GET['fw_time']) ? $_GET['fw_time'] : '';
        if (!empty($fw_time)) {
            $alipay_time = explode('-', $fw_time);

            $start = date('Y-m-d' . ' 00:00:00', strtotime($alipay_time[0]));
            $end = date('Y-m-d' . ' 23:59:59', strtotime($alipay_time[1]));
            $alipay_time_start = $start < $end ? $start : $end;
            $alipay_time_end = $end > $start ? $end : $start;
           $whereSql .= ' AND t.alipay_status=2  ';
            $wechatAlipaySql .= ' AND c.alipay_subscribe_time BETWEEN "' . $alipay_time_start . '" AND "' . $alipay_time_end . '"';
        }

        //微信公众号关注时间查询
        $wx_time = isset($_GET['wx_time']) ? $_GET['wx_time'] : '';
        if (!empty($wx_time)) {
            $wechat_time = explode('-', $wx_time);
            $wechat_status_start=1;
            $wechat_status_end=2;
            $start = date('Y-m-d' . ' 00:00:00', strtotime($wechat_time[0]));
            $end = date('Y-m-d' . ' 23:59:59', strtotime($wechat_time[1]));
            $wechat_time_start = $start < $end ? $start : $end;
            $wechat_time_end = $end > $start ? $end : $start;
            $whereSql .= ' AND t.wechat_status=2 ';
            $wechatAlipaySql .= ' AND b.wechat_subscribe_time BETWEEN "' . $wechat_time_start . '" AND "' . $wechat_time_end . '"';
        }

        //积分查询
        $score_min = isset($_GET['score_min']) ? $_GET['score_min'] : '';
        $score_max = isset($_GET['score_max']) ? $_GET['score_max'] : '';
        if ((!empty($score_min) || $score_min === '0') && (!empty($score_max) || $score_max === '0') && ($score_max >= $score_min)) {
            $whereSql .= ' AND type = '.USER_TYPE_WANQUAN_MEMBER.' AND IFNULL(t.points, 0) BETWEEN "' . $score_min . '" AND "' . $score_max . '"';
        }
        if ($score_min > $score_max)
            Yii::app()->user->setFlash('score_err_tip', '前者不可大于后者！');

        //生日查询
        $birth = isset($_GET['birth']) ? $_GET['birth'] : '';
        if (!empty($birth)) {
            $birth_time = explode('-', trim($birth));
            $birth_start = date('m-d', strtotime('1970/' . trim($birth_time[0])));
            $birth_end = date('m-d', strtotime('1970/' . trim($birth_time[1])));
            $whereSql .= ' AND DATE_FORMAT(t.birthday,"%m-%d") BETWEEN "' . $birth_start . '" AND "' . $birth_end . '"';
        }

        //排序
        $ageOrder = isset($_GET['sort']) ? $_GET['sort'] : '';
        $scoreOrder = isset($_GET['integ']) ? $_GET['integ'] : '';
        $focusOrder = isset($_GET['Focus']) ? $_GET['Focus'] : '';
        $registOrder = isset($_GET['regist']) ? $_GET['regist'] : '';
        //生日时间排序
        if (!empty($ageOrder)) {
            if ($ageOrder == 'asc'){
                $subSql .= 't.birthday DESC';
                //$orderSql .= 'IFNULL(t.birthday, "1970-01-01 00:00:00") DESC';
            }
            if ($ageOrder == 'desc') {
                $subSql .= ' t.birthday ASC';
                //$orderSql .= 'IFNULL(t.birthday, NOW()) ASC';
            }
        }
        //积分排序
        if (!empty($scoreOrder)) {
            if ($scoreOrder == 'asc') {
                $subSql .= ' IFNULL(t.points, 0) ASC,IFNULL(t.type, 1) DESC';
                //$orderSql .= 'IFNULL(t.points, 0) ASC';
            }

            if ($scoreOrder == 'desc') {
                $subSql .= ' IFNULL(t.points, 0) DESC,IFNULL(t.type, 1) ASC';
                //$orderSql .= 'IFNULL(t.points, 0) DESC';
            }

        }
        //支付宝服务窗, 微信公众号关注时间排序
        if (!empty($focusOrder)) {
            if ($focusOrder == 'asc') {
                $subSql .= ' b.wechat_subscribe_time ASC';
                //$subSql .= ' t.wechat_subscribe_time ASC, t.alipay_subscribe_time ASC';
                //$orderSql .= 'IFNULL(c.alipay_subscribe_time, NOW()) ASC, IFNULL(b.wechat_subscribe_time, NOW()) ASC';
            }

            if ($focusOrder == 'desc') {
                $subSql .= ' b.wechat_subscribe_time DESC';
                //$subSql .= ' t.wechat_subscribe_time DESC, t.alipay_subscribe_time DESC';
                //$orderSql .= 'IFNULL(c.alipay_subscribe_time, NOW()) DESC, IFNULL(b.wechat_subscribe_time, NOW()) DESC';
            }
        }
        //注册时间排序
        if (!empty($registOrder)) {
            if ($registOrder == 'asc') {
                $subSql .= ' t.regist_time ASC, t.type asc,';
                //$orderSql .= 'IFNULL(t.regist_time, NOW()) ASC';
            }

            if ($registOrder == 'desc') {
                $subSql .= ' t.regist_time DESC,t.type asc';
                //$orderSql .= 'IFNULL(t.regist_time, "1970-01-01 00:00:00") DESC';
            }
        }

        //默认按照注册，关注时间降序
        if (empty($focusOrder) && empty($registOrder) && empty($scoreOrder) && empty($ageOrder)) {
            $subSql .= ' b.wechat_subscribe_time DESC, c.alipay_subscribe_time DESC, t.create_time DESC';
            //$orderSql .= 'b.wechat_subscribe_time DESC, c.alipay_subscribe_time DESC, t.create_time DESC';
        }

        $provinces = isset($_GET['province']) ? $_GET['province'] : '';
        $cities = isset($_GET['city']) ? $_GET['city'] : '';

        //最近一次消费时间排序
        $xf_tOrder = isset($_GET['consumption']) ? $_GET['consumption'] : '';
        if (!empty($xf_tOrder)) {

        }
        //消费总金额排序
        $xf_mOrder = isset($_GET['xf_money']) ? $_GET['xf_money'] : '';
        if (!empty($xf_mOrder)) {

        }

        //数据查询
        $cmd = Yii::app()->db->createCommand();
        $cmd->select = 't.id, t.avatar, t.sex, t.nickname, t.name, t.birthday, t.from, 
        t.city, t.type, t.points, t.account, t.membershipgrade_id, t.create_time, t.regist_time,
        t.total_trade, t.last_trade_store, t.last_trade_time, IFNULL(b.wechat_status, 1) AS wechat_status, b.wechat_subscribe_time, b.wechat_cancel_subscribe_time, IFNULL(c.alipay_status, 1) AS alipay_status, c.alipay_subscribe_time, c.alipay_cancel_subscribe_time';
        $cmd->from = '(SELECT * FROM wq_user a WHERE a.merchant_id = ' . $merchant_id . ' AND a.flag = ' . FLAG_NO . ' AND a.bind_status = ' . USER_BIND_STATUS_UNBIND  . ') AS t ';
        $cmd->join = 'LEFT JOIN (
            SELECT
                a.wechat_id, a.wechat_status AS wechat_status,
                a.wechat_subscribe_time AS wechat_subscribe_time,
                a.wechat_cancel_subscribe_time AS wechat_cancel_subscribe_time
            FROM wq_user a
            WHERE a.type = ' . USER_TYPE_WECHAT_FANS . '
            AND a.merchant_id = ' . $merchant_id . '
        ) b ON t.wechat_id = b.wechat_id
        LEFT JOIN (
            SELECT
                a.alipay_fuwu_id, a.alipay_status AS alipay_status,
                a.alipay_subscribe_time AS alipay_subscribe_time,
                a.alipay_cancel_subscribe_time AS alipay_cancel_subscribe_time
            FROM wq_user a
            WHERE a.type = ' . USER_TYPE_ALIPAY_FANS . '
            AND a.merchant_id = ' . $merchant_id . '
        ) c ON t.alipay_fuwu_id = c.alipay_fuwu_id';


        //分页处理
        $cmd1 = clone $cmd;
        $cmd1->select = 't.id, IFNULL(b.wechat_status, 1) AS wechat_status, b.wechat_subscribe_time, b.wechat_cancel_subscribe_time, IFNULL(c.alipay_status, 1) AS alipay_status, c.alipay_subscribe_time, c.alipay_cancel_subscribe_time';
        
        $cmd1->from = '(SELECT * FROM wq_user a WHERE a.merchant_id = ' . $merchant_id . ' AND a.flag = ' . FLAG_NO . ' AND a.bind_status = ' . USER_BIND_STATUS_UNBIND. ') AS t ';
        $cmd1->join = 'LEFT JOIN (
            SELECT
                a.wechat_id, a.wechat_status AS wechat_status,
                a.wechat_subscribe_time AS wechat_subscribe_time,
                a.wechat_cancel_subscribe_time AS wechat_cancel_subscribe_time
            FROM wq_user a
            WHERE a.type = ' . USER_TYPE_WECHAT_FANS . '
            AND a.merchant_id = ' . $merchant_id . '
        ) b ON t.wechat_id = b.wechat_id
        LEFT JOIN (
            SELECT
                a.alipay_fuwu_id, a.alipay_status AS alipay_status,
                a.alipay_subscribe_time AS alipay_subscribe_time,
                a.alipay_cancel_subscribe_time AS alipay_cancel_subscribe_time
            FROM wq_user a
            WHERE a.type = ' . USER_TYPE_ALIPAY_FANS . '
            AND a.merchant_id = ' . $merchant_id . '
        ) c ON t.alipay_fuwu_id = c.alipay_fuwu_id';
        $cmd1->having = $wechatAlipaySql;
        $cmd1->group = 't.id';
        $cmd1->where = $whereSql;
        $user_num = count($cmd1->queryAll());
        $pages = new CPagination($user_num);
        $pages->pageSize = Yii::app()->params['perPage'];;
        $this->page = $pages;
        $cmd->offset = (isset($_GET['page']) ? ($_GET['page'] - 1) : 0) * $pages->pageSize;
        $cmd->limit = $pages->pageSize;

        $cmd->group = 't.id';
        $cmd->order = $subSql;
        $cmd->having = $wechatAlipaySql;
        $cmd->where = $whereSql;
        $users = $cmd->queryAll();


        $count = count($users);
        //查询门店
        $store_arr = Yii::app()->db->createCommand()
            ->select('a.id, a.name, a.branch_name')
            ->from('wq_store a')
            ->where('a.merchant_id = :merchant_id', array(':merchant_id' => $merchant_id))
            ->queryAll();
        foreach ($store_arr as $v) {
            $store_lists[$v['id']]['name'] = empty($v['branch_name']) ? $v['name'] : $v['name']."-".$v['branch_name'];
        }

        //查询会员等级
        $grade_arr = Yii::app()->db->createCommand()->select('a.id, a.merchant_id, a.name AS grade_name')
        ->from('wq_user_grade a')
        ->where('a.merchant_id = :merchant_id', array(':merchant_id' => $merchant_id))
       ->queryAll();
        foreach ($grade_arr as $v) {
            $grade_lists[$v['id']]['grade_name'] = $v['grade_name'];
        }

        $user_groups = UserGroup::model()->findAll('merchant_id=' . $merchant_id . ' AND flag=' . FLAG_NO);
        $user_grades = UserGrade::model()->findAll('merchant_id=' . $merchant_id . ' AND flag=' . FLAG_NO);

        $this->render('userLists', array(
            'users' => $users,
            'store_lists' => $store_lists,
            'grade_lists' => $grade_lists,
            'pages' => $pages,
            'user_groups' => $user_groups,
            'user_grades' => $user_grades,
            'province' => $province,
            'count' => $count,
            'choseCities' => $choseCities,
            'store_ids' => $store_ids,
            'store_names' => $store_names,
        ));
    }

    /**
     * 用户信息
     */
    public function actionUserInfo()
    {

        $user_login_clients = $GLOBALS['__USER_LOGIN_CLIENT'];
        $merchant_id = Yii::app()->session['merchant_id'];
        $user_id = isset($_GET['id']) ? $_GET['id'] : '';

        if (empty($user_id))
            exit('非法访问！');

        $command = Yii::app()->db->createCommand();
        $command->select('a.*, b.*, c.*, d.name as grade_name, d.membercard_img,e.flag, f.name as group_name,f.flag');
        $command->from('wq_user a');
        $command->andWhere('a.id = :id');
        $command->params[':id'] = $user_id;
        $command->andWhere('a.flag = :flag');
        $command->params[':flag'] = FLAG_NO;

        $command->leftJoin('(
                            SELECT
                                a.wechat_id, a.wechat_status AS rel_wechat_status,
                                a.wechat_subscribe_time AS rel_wechat_subscribe_time,
                                a.wechat_cancel_subscribe_time AS rel_wechat_cancel_subscribe_time
                            FROM wq_user a
                            WHERE a.type = ' . USER_TYPE_WECHAT_FANS . '
                            AND a.merchant_id = ' . $merchant_id . '
                        ) b', 'a.wechat_id = b.wechat_id');
        $command->leftJoin('(
                            SELECT
                                a.alipay_fuwu_id, a.alipay_status AS rel_alipay_status,
                                a.alipay_subscribe_time AS rel_alipay_subscribe_time,
                                a.alipay_cancel_subscribe_time AS rel_alipay_cancel_subscribe_time
                            FROM wq_user a
                            WHERE a.type = ' . USER_TYPE_ALIPAY_FANS . '
                            AND a.merchant_id = ' . $merchant_id . '
                        ) c', 'a.alipay_fuwu_id = c.alipay_fuwu_id');
        $command->leftJoin('wq_user_grade d', 'a.membershipgrade_id = d.id');
        $command->leftJoin('wq_group e', 'a.id = e.user_id');
        $command->leftJoin('(SELECT * from wq_user_group where flag=1) f', 'e.group_id = f.id');
        $user_all = $command->queryAll();
        
        if (empty($user_all))
            exit('该用户不存在！');

        $user = $user_all[0];
        $user['group_name'] = '';
        foreach ($user_all as $key => $v) {
            $user['group_name'] .= $v['group_name'] . '、';
        }
        $user['group_name'] = rtrim($user['group_name'], '、');
        //获取成长记录
        $growupRecord = Yii::app()->db->createCommand()
       ->select('a.user_id, a.user_grade_id, a.user_grade_name, a.create_time')
       ->from('wq_user_growup_record a')
        ->leftJoin('wq_user_grade b', 'a.user_grade_id = b.id')
        ->where('a.user_id = :user_id and a.flag = :flag', array(
            ':user_id' => $user_id,
            ':flag' => FLAG_NO
        ))
        ->order('a.create_time ASC')
        ->queryAll();
        $user = $this->arrayToObject($user);
        $user->growupRecord = $growupRecord;
        $time_line = array();
        $tmpTimesArr = array();
        $tmpThingsArr = array();

        if (!empty($user->growupRecord)) {
            foreach ($user->growupRecord as $record) {
                $tmpTimesArr[] = $record['create_time'];
                $tmpThingsArr[] = $record['user_grade_name'];
            }
        }
        if (!empty($user->regist_time)) {
            $tmpTimesArr[] = $user->regist_time;
            $tmpThingsArr[] = '注册成为会员';
        }
        if (!empty($user->rel_alipay_subscribe_time)) {
            $tmpTimesArr[] = $user->rel_alipay_subscribe_time;
            $tmpThingsArr[] = '关注服务窗';
        }
        if (!empty($user->rel_wechat_subscribe_time)) {
            $tmpTimesArr[] = $user->rel_wechat_subscribe_time;
            $tmpThingsArr[] = '关注微信公众号';
        }
        if (!empty($user->order)) {
            $tmpTimesArr[] = $user->order[count($user->order) - 1]['create_time'];
            $tmpThingsArr[] = '首次购物';
        }
        arsort($tmpTimesArr);
        $things = array();
        $times = array();
        foreach ($tmpTimesArr as $k => $v) {
            $times[] = $v;
            $things[] = $tmpThingsArr[$k];
        }

        $i = 0; //计数器
        foreach ($times as $k => $v) {
            $key = date('Y', strtotime($v));
            $time_line[$key][$i]['time'] = $v;
            $time_line[$key][$i]['thing'] = $things[$k];
            $i++;
        }

        //门店消费记录
        $tk_types = array(ORDER_STATUS_HANDLE_REFUND, ORDER_STATUS_REFUND, ORDER_STATUS_PART_REFUND);
        $xf_types = array(ORDER_STATUS_NORMAL);
        $allowed_types = array_merge($tk_types, $xf_types);
        $allowed_types_Str = "('" . implode("','", $allowed_types) . "')";
        $wechat_user_id = $user->wechat_id;
        $alipay_user_id = $user->alipay_fuwu_id;
        $user_store_orders = Order::model()->findAll('merchant_id=:merchant_id and (user_id=:user_id or wechat_user_id=:wechat_user_id or alipay_user_id=:alipay_user_id) AND flag=:flag AND order_type=:order_type AND pay_status=:pay_status AND order_status IN ' . $allowed_types_Str . ' order by create_time asc', array(
            ':user_id' => $user_id,
            ':merchant_id' => $merchant_id,
            ':wechat_user_id' => $wechat_user_id,
            ':alipay_user_id' => $alipay_user_id,
            ':flag' => FLAG_NO,
            ':order_type' => ORDER_TYPE_CASHIER,
            ':pay_status' => ORDER_STATUS_PAID
        ));

        //累计门店消费金额
        $total_store_xf_money = 0;
        //消费次数
        $total_store_xf_num = 0;
        
        $order_ids = array();
        foreach ($user_store_orders as $v) {
                $shishou = $v->order_paymoney - $v->coupons_money - $v->discount_money - $v->merchant_discount_money;
                $total_store_xf_money += $shishou;
                $total_store_xf_num++;
                $order_ids[] = $v['id'];
        }
        
        //累计获得积分
        $user_total_get_score = 0;
        $userPointsDetail = Yii::app()->db->createCommand()
        ->select('a.user_id, a.points, a.balance_of_payments')
        ->from('wq_user_pointsdetail a')
       ->where('a.user_id = :user_id', array(
            ':user_id' => $user_id
        ))
        ->queryAll();
  
        //退款金额
        $user_store_orders_refund=Yii::app()->db->createCommand()
           ->select('id, order_id, refund_money,status')
           ->from('wq_refund_record')
           ->where('merchant_id = :merchant_id and type = :type and status != :status', array(
               ':merchant_id' => $merchant_id,
               ':type' => REFUND_TYPE_REFUND,
               ':status' => REFUND_STATUS_FAIL,
           ))
           ->andWhere(array('IN','order_id',$order_ids))
           ->queryAll();
        $user_xf_refund_money = 0;
        if (!empty($user_store_orders_refund)) {
            foreach ($user_store_orders_refund as $order) {
                $user_xf_refund_money += $order['refund_money'];
                $total_store_xf_num++;
            }
        }
        $total_store_xf_money = $total_store_xf_money - $user_xf_refund_money;
        
        if (!empty($userPointsDetail)) {
            foreach ($userPointsDetail as $v) {
                if ($v['balance_of_payments'] == POINT_PAYMENT_PAY)
                    $user_total_get_score += $v['points'];
                if ($v['balance_of_payments'] == BALANCE_OF_PAYMENTS_REFUND)
                    $user_total_get_score -= $v['points'];
            }
        }
        
        $this->render('userInfo', array(
            'user' => $user,
            'user_login_clients' => $user_login_clients,
            'total_store_xf_money' => $total_store_xf_money,
            'total_store_xf_num' => $total_store_xf_num,
            'user_total_get_score' => $user_total_get_score,
            'time_line' => $time_line,
        ));
    }

    /**
     * 消费记录
     */
    public function actionUserExpenses()
    {
        //退款，消费（门店）
        $tk_types = array(ORDER_STATUS_HANDLE_REFUND, ORDER_STATUS_REFUND, ORDER_STATUS_PART_REFUND);
        $xf_types = array(ORDER_STATUS_NORMAL);
        $allowed_types = array_merge($tk_types, $xf_types);

        $merchant_id = Yii::app()->session['merchant_id'];
        $user_id = isset($_GET['id']) ? $_GET['id'] : '';
        if (empty($user_id))
            exit('非法访问！');

        $user = User::model()->findByPk($user_id);

        $pay_time = isset($_GET['pay_time']) ? $_GET['pay_time'] : '';
        $store_id = isset($_GET['store_id']) ? $_GET['store_id'] : '';
        $pay_channel = isset($_GET['pay_channel']) ? $_GET['pay_channel'] : '';
        $order_status = isset($_GET['order_status']) ? $_GET['order_status'] : '';

        $criteria = new CDbCriteria();
        $criteria->addCondition('merchant_id=:merchant_id and order_type=:order_type AND pay_status=:pay_status AND (user_id=:user_id or wechat_user_id=:wechat_user_id or alipay_user_id=:alipay_user_id)');
        $criteria->addInCondition('order_status', $allowed_types);
        $criteria->params[':order_type'] = ORDER_TYPE_CASHIER;
        $criteria->params[':user_id'] = $user_id;
        $criteria->params[':merchant_id'] = $merchant_id;
        $wechat_user_id = $user->wechat_id ? $user->wechat_id : -1;
        $alipay_user_id = $user->alipay_fuwu_id ? $user->alipay_fuwu_id : -1;
        $criteria->params[':wechat_user_id'] = $wechat_user_id;
        $criteria->params[':alipay_user_id'] = $alipay_user_id;
        $criteria->params[':pay_status'] = ORDER_STATUS_PAID;

        if (!empty($pay_time)) {
            $pay_time_arr = explode('-', $pay_time);
            $pay_time_start = date('Y-m-d' . ' 00:00:00', strtotime($pay_time_arr[0]));
            $pay_time_end = date('Y-m-d' . ' 23:59:59', strtotime($pay_time_arr[1]));
            $criteria->addBetweenCondition('pay_time', $pay_time_start, $pay_time_end);
        }
        if (!empty($store_id)) {
            $criteria->addCondition('store_id=:store_id');
            $criteria->params[':store_id'] = $store_id;
        }

        if (!empty($order_status)) {
            if ($order_status == 1)
                $criteria->addInCondition('order_status', $tk_types);
            if ($order_status == 2)
                $criteria->addInCondition('order_status', $xf_types);
        }

        if (!empty($pay_channel)) {
            $criteria->addCondition('pay_channel=:pay_channel');
            $criteria->params[':pay_channel'] = $pay_channel;
        }

        $criteria->order = 'create_time desc, last_time desc';

        $pages = new CPagination(Order::model()->count($criteria));
        $pages->pageSize = Yii::app()->params['perPage'];
        $pages->applyLimit($criteria);
        $orders = Order::model()->findAll($criteria);
        $criteria2 = new CDbCriteria();
        $criteria2->addCondition('merchant_id=:merchant_id and flag=:flag');
        $criteria2->params[':merchant_id'] = $merchant_id;
        $criteria2->params[':flag'] = FLAG_NO;
        $stores = Store::model()->findAll($criteria2);
        
        //门店消费记录
        $allowed_types_Str = "('" . implode("','", $allowed_types) . "')";
        $user_store_orders = Order::model()->findAll('merchant_id=:merchant_id and (user_id=:user_id or wechat_user_id=:wechat_user_id or alipay_user_id=:alipay_user_id) AND flag=:flag AND order_type=:order_type AND pay_status=:pay_status AND order_status IN ' . $allowed_types_Str . ' order by create_time asc', array(
            ':user_id' => $user_id,
            ':merchant_id' => $merchant_id,
            ':wechat_user_id' => $wechat_user_id,
            ':alipay_user_id' => $alipay_user_id,
            ':flag' => FLAG_NO,
            ':order_type' => ORDER_TYPE_CASHIER,
            ':pay_status' => ORDER_STATUS_PAID
        ));
        
        $user_xf_total_money = 0;
        $user_xf_total_num = 0;
        $user_xf_refund_money=0;
        $user_xf_first_time = '未消费';
        $order_ids = array();
        if (!empty($user_store_orders)){
            $user_xf_first_time = $user_store_orders[0]['create_time'];
            foreach ($user_store_orders as $v){
                $order_ids[] = $v['id'];
            }
        }
        $user_store_orders_refund=Yii::app()->db->createCommand()
        ->select('id, order_id, refund_money,status')
        ->from('wq_refund_record')
        ->where('merchant_id = :merchant_id and type = :type and status != :status', array(
            ':merchant_id' => $merchant_id,
            ':type' => REFUND_TYPE_REFUND,
            ':status' => REFUND_STATUS_FAIL,
        ))
        ->andWhere(array('IN','order_id',$order_ids))
        ->queryAll();
        
        if (!empty($user_store_orders)) {
            foreach ($user_store_orders as $order) {
                $shishou = $order->order_paymoney - $order->coupons_money - $order->discount_money - $order->merchant_discount_money;
                $user_xf_total_money += $shishou;
                $user_xf_total_num++;
            }
        }
        if (!empty($user_store_orders_refund)) {
            foreach ($user_store_orders_refund as $order) {
                $user_xf_refund_money += $order['refund_money'];
                $user_xf_total_num++;
            }
        }

        $user_xf_total_money = $user_xf_total_money - $user_xf_refund_money;
        $order_statuses = $GLOBALS['ORDER_STATUS'];
        $pay_channels = $GLOBALS['ORDER_PAY_CHANNEL'];

        $this->render('user_expenses', array(
            'user_store_orders' => $user_store_orders,
            'user_xf_total_money' => $user_xf_total_money,
            'user_xf_total_num' => $user_xf_total_num,
            'user_xf_first_time' => $user_xf_first_time,
            'orders' => $orders,
            'stores' => $stores,
            'order_statuses' => $order_statuses,
            'tk_types' => $tk_types,
            'xf_types' => $xf_types,
            'pay_channels' => $pay_channels,
            'pages' => $pages,
        ));
    }

    /**
     * 积分记录
     */
    public function actionUserPoints()
    {
        $merchant_id = Yii::app()->session['merchant_id'];
        $user_id = isset($_GET['id']) ? $_GET['id'] : '';
        $point_time = isset($_GET['point_time']) ? $_GET['point_time'] : '';
        $point_type = isset($_GET['point_type']) ? $_GET['point_type'] : '';
        $point_from = isset($_GET['point_from']) ? $_GET['point_from'] : '';

        $user = User::model()->findByPk($user_id);

        //数据合并
        $user_wechat_user_id = $user->wechat_id ? $user->wechat_id : -1;
        $user_alipay_user_id = $user->alipay_fuwu_id ? $user->alipay_fuwu_id : -1;
        $users = User::model()->findAll('id=' . $user_id . ' OR wechat_id=\'' . $user_wechat_user_id . '\' OR alipay_fuwu_id=\'' . $user_alipay_user_id . '\'');

        $uids = array();
        foreach ($users as $v) {
            $uids[] = $v->id;
        }
        $inStr = "('" . implode("','", $uids) . "')";

        if (empty($user_id))
            exit('非法访问！');


        $criteria = new CDbCriteria();
        $criteria->addInCondition('user_id', $uids);

        if (!empty($point_time)) {
            $point_time_arr = explode('-', $point_time);
            $point_time_start = date('Y-m-d 00:00:00', strtotime($point_time_arr[0]));
            $point_time_end = date('Y-m-d 23:59:59', strtotime($point_time_arr[1]));
            $criteria->addBetweenCondition('last_time', $point_time_start, $point_time_end);
        }
        if (!empty($point_type)) {
            $criteria->addCondition('balance_of_payments=:point_type');
            $criteria->params[':point_type'] = $point_type;
        }
        if (!empty($point_from)) {
            $criteria->addCondition('`from`=:from');
            $criteria->params[':from'] = $point_from;
        }

        $criteria->order = 'last_time desc, create_time desc';

        $pages = new CPagination(UserPointsdetail::model()->count($criteria));
        $pages->pageSize = Yii::app()->params['perPage'];
        $pages->applyLimit($criteria);
        $pointDetailes = UserPointsdetail::model()->findAll($criteria);//积分详细记录
        $point_types = $GLOBALS['__BALANCE_OF_PAYMENTS'];
        $point_froms = $GLOBALS['__USER_POINTS_DETAIL_FROM'];
        $pointAllDetailes = UserPointsdetail::model()->findAll('user_id IN ' . $inStr);

        $total_get_points = 0;
        foreach ($pointAllDetailes as $k => $v) {
            if ($v->balance_of_payments == BALANCE_OF_PAYMENTS_INCOME)
                $total_get_points += $v->points;
            if ($v->balance_of_payments == BALANCE_OF_PAYMENTS_REFUND)
                $total_get_points -= $v->points;
        }
//模板渲染
        $this->render('user_points', array(
            'user' => $user,
            'pointDetailes' => $pointDetailes,
            'total_get_points' => $total_get_points,
            'point_types' => $point_types,
            'point_froms' => $point_froms,
            'pages' => $pages,
        ));
    }

    /**
     * 储值记录
     */
    public function actionUserStored()
    {
        $merchant_id = Yii::app()->session['merchant_id'];
        $user_id = isset($_GET['id'])?$_GET['id']:'';
        if(empty($user_id))
            exit('非法访问！');

        $user = User::model()->findByPk($user_id);

        //数据合并
        $user_wechat_user_id = $user->wechat_id?$user->wechat_id:-1;
        $user_alipay_user_id = $user->alipay_fuwu_id?$user->alipay_fuwu_id:-1;
        $users = User::model()->findAll('id='.$user_id.' OR wechat_id=\''.$user_wechat_user_id.'\' OR alipay_fuwu_id=\''.$user_alipay_user_id.'\'');

        $uids = array();
        foreach ($users as $v)
        {
            $uids[] = $v->id;
        }
        $inStr = "('".implode("','", $uids)."')";
        $stored_time = isset($_GET['stored_time'])?$_GET['stored_time']:'';
        $criteria = new CDbCriteria();
        if(!empty($stored_time))
        {
            $stored_time_arr = explode('-', $stored_time);
            $stored_time_start = date('Y-m-d 00:00:00', strtotime($stored_time_arr[0]));
            $stored_time_end = date('Y-m-d 23:59:59', strtotime($stored_time_arr[1]));
            $criteria->addBetweenCondition('pay_time', $stored_time_start, $stored_time_end);
        }
        $criteria->addInCondition('user_id', $uids);
        //获取当前商户储值活动
        $storeds = Stored::model()->findAll('merchant_id='.$merchant_id.' and flag='.FLAG_NO);
        $stored_ids = array();
        foreach($storeds as $v)
        {
            $stored_ids[] = $v->id;
        }
        $criteria->addInCondition('stored_id', $stored_ids);

        $criteria->addCondition('flag=:flag');
        $criteria->params[':flag'] = FLAG_NO;
        $criteria->addCondition('pay_status='.ORDER_STATUS_PAID);
        $criteria->order = 'create_time desc, last_time desc, pay_time desc';

        $pages = new CPagination(StoredOrder::model()->count($criteria));
        $pages->pageSize = Yii::app()->params['perPage'];
        $pages->applyLimit($criteria);

        $order_status = $GLOBALS['ORDER_STATUS'];

        $stored_lists = StoredOrder::model()->findAll($criteria);

        //当前用户所有储值记录，累计储值
        $all_stored_lists = StoredOrder::model()->findAll('user_id IN'.$inStr.' AND pay_status='.ORDER_STATUS_PAID.' AND flag='.FLAG_NO);

        $total_money = 0;
        $total_money_leiji = 0;
        $total_money_shishou = 0;
        if(!empty($all_stored_lists))
        {
            foreach($all_stored_lists as $item)
            {
                if($item->order_status == ORDER_STATUS_NORMAL)
                {
                    $total_money += $item->num*$item->stored->get_money;
                    $total_money_shishou += $item->num*$item->stored->stored_money;
                }
            }
        }
        $total_money_leiji = $total_money+$total_money_shishou;

        $this->render('user_stored', array(
            'stored_lists'=>$stored_lists,
            'all_stored_lists'=>$all_stored_lists,
            'total_money_shishou'=>$total_money_shishou,
            'total_money_leiji'=>$total_money_leiji,
            'user'=>$user,

            'pages'=>$pages,
            'order_status'=>$order_status,
        ));
    }

    /**
     * 商城购买记录
     */
    public function actionUserPurchase()
    {
        $user_id = isset($_GET['id'])?$_GET['id']:'';
        $merchant_id = Yii::app()->session['merchant_id'];
        if(empty($user_id))
            exit('非法访问');

        $user = User::model()->findByPk($user_id);
        $wechat_user_id = $user->wechat_id;
        $alipay_user_id = $user->alipay_fuwu_id;
        $criteria = new CDbCriteria();
        $criteria->addInCondition('order_type', array(ORDER_TYPE_OBJECT, ORDER_TYPE_VIRTUAL));
        $criteria->addCondition('flag='.FLAG_NO);
        if(!empty($wechat_user_id) || !empty($alipay_user_id))
        {
            $criteria->addCondition('user_id=:user_id or wechat_user_id=:wechat_user_id or alipay_user_id=:alipay_user_id');
            $criteria->params[':user_id'] = $user_id;
            $criteria->params[':wechat_user_id'] = $wechat_user_id;
            $criteria->params[':alipay_user_id'] = $alipay_user_id;
        }

        $user_shop_orders = Order::model()->findAll($criteria);
        $terminal_types = $GLOBALS['__TERMINAL_TYPE_POS'];
        $this->render('user_purchase', array(
            'user'=>$user,
            'user_shop_orders'=>$user_shop_orders,
            'terminal_types'=>$terminal_types,
        ));
    }

    /**
     * 用户详情
     */
    public function actionUserDetail()
    {
        $detail = '';
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        $merchantId = Yii::app()->session['merchant_id'];
        $ret = new UserC();
        $rat = $ret->UserDetail($merchantId,$id);
        $detail = json_decode($rat,true);
        if($detail['status'] == ERROR_NONE)
        {
            $detail = $detail['data'];
        }
        $assign['detail'] = $detail;
        $assign['pages']  = $ret -> page;
        $assign['page1'] = $ret -> page1;
        $assign['page2']  = $ret -> page2;
        $this -> render('userDetail',$assign);
    }

    /**
     * 把用户添加到分组
     */
    public function actionAddtoGroup()
    {
        $userStr   = $_POST['users'];
        $groupStr = $_POST['groups'];
        $groups    = explode(',', $groupStr);

        $merchant_id = Yii::app()->session['merchant_id']; //商户id
        $user = new UserC();

        //如果是一个人则覆盖操作
        if(substr_count($userStr, ',')<1){
            $db = Yii::app()->db;
            $sql = "delete from wq_group where user_id=".$userStr;
            $command = $db->createCommand($sql);
            $res = $command->query();
        }

        foreach ($groups as $group){
            //重用 注意参数
            $ret = $user->regroupUser($merchant_id, "add", $userStr, 'all-ALL', 'custom-'.$group);
            $result = json_decode($ret, true);
            if ($result['status'] != ERROR_NONE) {
                Yii::app()->user->setFlash('error', $result['errMsg']);
            }
        }
        echo "<script>alert('添加到分组成功');history.go(-1);</script>";
    }

    /**
     * 给用户打标签
     */
    public function actionAddTag()
    {
        $userc = new UserC();
        $userStr    = $_POST['users'];
        $tagStr    = $_POST['tags'];
        $users      = explode(',', $userStr);
        $tags       = explode(',', $tagStr);
//         print_r($tags);print_r($users);
        /*
         * $user = new UserC();
                $tag_value = array();
                if($_POST){
                    $user_id = $_GET['id'];
                    if(!empty($_POST['tag']) && $_POST['tag']){
                        foreach($_POST['tag'] as $v){
                            $tag_value[] = $v;
                        }
                    }
                    $ret = $user->UserTag($user_id, $tag_value);
                    $result = json_decode($ret,true);
                    if($result['status'] == ERROR_NONE){
                        $this->redirect($_GET['goUrl']);
                    }
                }
         *  */
        if(substr_count($userStr, ',')<1){
            $db = Yii::app()->db;
            $sql = "delete from wq_user_tag where user_id=".$userStr;
            $command = $db->createCommand($sql);
            $res = $command->query();
        }
        foreach ($users as $v)
        {
            $ret = $userc->UserTag($v, $tags);
            $result = json_decode($ret,true);
            if($result['status'] == ERROR_NONE){
//                 $this->redirect($_GET['goUrl']);
                echo "<script>self.history.go(-1);</script>";
            }
        }
        echo "<script>alert('打标签成功');history.go(-1);</script>";
    }

    /**
     * @param string $array
     * @return string
     *  数组转换字符串
     */
    private function changeArray($array = '') {
        $value = '';
        if ($array) {
            foreach ($array as $v) {
                if (!empty($value)) {
                    $value .= ', ' . $v;
                } else {
                    $value .= $v;
                }
            }
        } else {
            $value = 0;
        }
        return $value;
    }

    /** 数组转对象
     * @param $e
     * @return object|void
     */
    private function arrayToObject($e)
    {
        if (gettype($e) != 'array') return;
        foreach ($e as $k => $v) {
            if (gettype($v) == 'array' || getType($v) == 'object')
                $e[$k] = (object)arrayToObject($v);
        }
        return (object)$e;
    }

    public function actionPopStore()
    {
//         echo '弹窗';
        $merchant_id = Yii::app()->session['merchant_id'];
        $storeC = new StoreC();
        $pca = new Selector();

        $list = array();

        //搜索：省市区
        $address = '';
        if (!empty($_GET['Selector']['liveplaceProvince'])) {
            $address = $_GET['Selector']['liveplaceProvince'];
            if (!empty($_GET['Selector']['liveplaceCity'])) {
                $address = $address . ',' . $_GET['Selector']['liveplaceCity'] . ',' . $_GET['Selector']['liveplaceArea'];
            }
        }

        $criteria = new CDbCriteria();
        $criteria->addCondition('merchant_id='.$merchant_id);

        $province = json_decode($storeC -> getProvince());
        $province = $province->data;

        $store_name = isset($_GET['store_name'])?$_GET['store_name']:'';

        if(!empty($store_name))
            $criteria->addCondition("name like '%{$store_name}%'");

        if(!empty($address))
            $criteria->addCondition("address like '%{$address}%'");

        $count = Store::model()->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = Yii::app() -> params['perPage'];
        $pages->applyLimit($criteria);

        $stores = Store::model()->findAll($criteria);

        $this->render('popstore',array(
            'province'=>$province,
            'pages'=>$pages,
            'pca' => $pca,
            'stores'=>$stores,
            'count'=>$count,
        ));
    }
}