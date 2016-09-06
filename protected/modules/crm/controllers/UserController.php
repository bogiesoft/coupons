<?php
/**
 * 用户管理控制器
 * crm管理模块
 * 作者：李晨
 * 编写日期：2016-08-29
 */
// error_reporting(E_ALL);
class UserManageController extends CrmController
{

    /**
     * 用户列表
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
                $choseCities[$k]['pCode'] = $provinces[$k];
                $choseCities[$k]['pName'] = $storeC->getProvinceCityName($provinces[$k]);
                $choseCities[$k]['cCode'] = $cities[$k];
                $choseCities[$k]['cName'] = $storeC->getProvinceCityName($cities[$k]);
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
        //微信支付宝筛选条件
        $weChatAliPaySql = ' 1 = 1 ';
        //排序条件
        $orderSql = '';

        //关键字模糊查询
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
        if (!empty($keyword)) {
            $subSql .= ' AND (nickname LIKE "%' . $keyword . '%" OR account LIKE "%' . $keyword . '%" OR name LIKE "%' . $keyword . '%" OR wechat_nickname LIKE "%' . $keyword . '%" OR alipay_nickname LIKE "%' . $keyword . '%") ';
        }

        //会员等级查询
        $grades = isset($_GET['grade']) ? $_GET['grade'] : '';
        if (!empty($grades)) {
            $subSql .= ' AND a.membershipgrade_id IN (' . $this -> changeArray($grades) . ') AND a.type = ' . USER_TYPE_WANQUAN_MEMBER;
        }

        //支付宝状态查询条件
        $aliPay_status = isset($_GET['aliPay_status']) ? $_GET['aliPay_status'] : '';
        if (!empty($aliPay_status)) {
            $weChatAliPaySql .= ' AND aliPay_status IN (' . $this -> changeArray($aliPay_status) . ')';
        }

        //微信状态查询条件
        $weChat_status = isset($_GET['weChat_status']) ? $_GET['weChat_status'] : '';
        if (!empty($weChat_status)) {
            $weChatAliPaySql .= ' AND weChat_status IN (' . $this -> changeArray($weChat_status) . ')';
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
            $subSql .= ' AND a.id IN(' . $this -> changeArray($ids) . ')';
        }
        //性别查询
        $sexes = isset($_GET['sex']) ? $_GET['sex'] : '';
        if (!empty($sexes)) {
            $subSql .= ' AND IFNULL(a.sex, 3) IN (' . $this -> changeArray($sexes) .')';
        }

        //门店查询
        $store_ids = isset($_GET['store_id']) ? $_GET['store_id'] : '';
        $store_names = isset($_GET['store_name']) ? $_GET['store_name'] : '';
        if (!empty($store_ids)) {
            $criteria3 = new CDbCriteria();
            $criteria3->select = 'DISTINCT(user_id)';
            $criteria3->addInCondition('store_id', $store_ids);
            $orders = Order::model()->findAll($criteria3);
            $ids = array();
            foreach ($orders as $order) {
                $ids[] = $order['user_id'];
            }
            $subSql .= ' AND a.id IN(' . $this -> changeArray($ids) . ')';
        }

        //年龄查询
        $age_min = isset($_GET['age_min']) ? $_GET['age_min'] : '';
        $age_max = isset($_GET['age_max']) ? $_GET['age_max'] : '';
        if ((!empty($age_min) || $age_min === '0') && (!empty($age_max) || $age_max === '0') && $age_max >= $age_min) {
            $s_data = date('Y') - $age_max;
            $e_data = date('Y') - $age_min;
            $subSql .= ' AND YEAR(a.birthday) BETWEEN "' . $s_data . '" AND "' . $e_data . '"';
        }
        if ($age_min > $age_max)
            Yii::app()->user->setFlash('age_err_tip', '前者不可大于后者！');

        //注册时间查询
        $reg_time = isset($_GET['reg_time']) ? $_GET['reg_time'] : '';
        if (!empty($reg_time)) {
            $register_time = explode('-', $reg_time);
            $start = date('Y-m-d' . ' 00:00:00', strtotime($register_time[0]));
            $end = date('Y-m-d' . ' 23:59:59', strtotime($register_time[1]));
            $reg_time_start = $start < $end ? $start : $end;
            $reg_time_end = $end > $start ? $end : $start;
            $subSql .= ' AND a.register_time BETWEEN "' . $reg_time_start . '" AND "' . $reg_time_end . '"';
        }

        //支付宝服务窗关注时间查询
        $fw_time = isset($_GET['fw_time']) ? $_GET['fw_time'] : '';
        if (!empty($fw_time)) {
            $aliPay_time = explode('-', $fw_time);
            $start = date('Y-m-d' . ' 00:00:00', strtotime($aliPay_time[0]));
            $end = date('Y-m-d' . ' 23:59:59', strtotime($aliPay_time[1]));
            $aliPay_time_start = $start < $end ? $start : $end;
            $aliPay_time_end = $end > $start ? $end : $start;
            $weChatAliPaySql .= ' AND c.aliPay_subscribe_time BETWEEN "' . $aliPay_time_start . '" AND "' . $aliPay_time_end . '"';
        }

        //微信公众号关注时间查询
        $wx_time = isset($_GET['wx_time']) ? $_GET['wx_time'] : '';
        if (!empty($wx_time)) {
            $weChat_time = explode('-', $wx_time);
            $start = date('Y-m-d' . ' 00:00:00', strtotime($weChat_time[0]));
            $end = date('Y-m-d' . ' 23:59:59', strtotime($weChat_time[1]));
            $weChat_time_start = $start < $end ? $start : $end;
            $weChat_time_end = $end > $start ? $end : $start;
            $weChatAliPaySql .= ' AND b.wechat_subscribe_time BETWEEN "' . $weChat_time_start . '" AND "' . $weChat_time_end . '"';
        }

        //积分查询
        $score_min = isset($_GET['score_min']) ? $_GET['score_min'] : '';
        $score_max = isset($_GET['score_max']) ? $_GET['score_max'] : '';
        if ((!empty($score_min) || $score_min === '0') && (!empty($score_max) || $score_max === '0') && ($score_max >= $score_min)) {
            $subSql .= ' AND IFNULL(a.points, 0) BETWEEN "' . $score_min . '" AND "' . $score_max . '"';
        }
        if ($score_min > $score_max)
            Yii::app()->user->setFlash('score_err_tip', '前者不可大于后者！');

        //生日查询
        $birth = isset($_GET['birth']) ? $_GET['birth'] : '';
        if (!empty($birth)) {
            $birth_time = explode('-', trim($birth));
            $birth_start = date('m-d', strtotime('1970/' . trim($birth_time[0])));
            $birth_end = date('m-d', strtotime('1970/' . trim($birth_time[1])));
            $subSql .= ' AND DATE_FORMAT(a.birthday,"%m-%d") BETWEEN "' . $birth_start . '" AND "' . $birth_end . '"';
        }

        //排序
        $ageOrder = isset($_GET['sort']) ? $_GET['sort'] : '';
        $scoreOrder = isset($_GET['integ']) ? $_GET['integ'] : '';
        $focusOrder = isset($_GET['Focus']) ? $_GET['Focus'] : '';
        $registerOrder = isset($_GET['register']) ? $_GET['register'] : '';
        //生日时间排序
        if (!empty($ageOrder)) {
            if ($ageOrder == 'asc'){
                $subSql .= ' ORDER BY IFNULL(a.birthday, "1970-01-01 00:00:00") DESC';

            }
            if ($ageOrder == 'desc') {
                $subSql .= ' ORDER BY IFNULL(a.birthday, NOW()) ASC';

            }
        }
        //积分排序
        if (!empty($scoreOrder)) {
            if ($scoreOrder == 'asc') {
                $subSql .= ' ORDER BY IFNULL(a.points, 0) ASC';

            }

            if ($scoreOrder == 'desc') {
                $subSql .= ' ORDER BY IFNULL(a.points, 0) DESC';

            }

        }
        //支付宝服务窗, 微信公众号关注时间排序
        if (!empty($focusOrder)) {
            if ($focusOrder == 'asc') {
                $subSql .= ' ORDER BY IFNULL(a.weChat_subscribe_time, NOW()) ASC, IFNULL(a.aliPay_subscribe_time, NOW()) ASC';

            }

            if ($focusOrder == 'desc') {
                $subSql .= ' ORDER BY IFNULL(a.wechat_subscribe_time, NOW()) DESC, IFNULL(a.alipay_subscribe_time, NOW()) DESC';

            }
        }
        //注册时间排序
        if (!empty($registerOrder)) {
            if ($registerOrder == 'asc') {
                $subSql .= ' ORDER BY IFNULL(a.register_time, NOW()) ASC';

            }

            if ($registerOrder == 'desc') {
                $subSql .= ' ORDER BY IFNULL(a.register_time, "1970-01-01 00:00:00") DESC';

            }
        }

        //默认按照注册，关注时间降序
        if (empty($focusOrder) && empty($registOrder) && empty($scoreOrder) && empty($ageOrder)) {
            $subSql .= ' ORDER BY a.wechat_subscribe_time DESC, a.alipay_subscribe_time DESC, a.create_time DESC';

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

        //连接数据库
        $users = Yii::app()->db->createCommand()
        -> select ('t.id, t.avatar, t.sex, t.nickname, t.name, t.birthday,
        t.city, t.type, t.points, t.account, t.membershipgrade_id, t.create_time, t.regist_time,
        t.total_trade, t.last_trade_store, t.last_trade_time, ')
            ->from('wq_user t')
            ->group ('t.id')

            -> queryAll();

        //分页处理
        $cmd1 = clone $users;
        $cmd1 -> select = 'count(t.id)';
        $cmd1 -> from = '(SELECT * FROM wq_user a WHERE a.merchant_id = ' . $merchant_id . ' AND a.flag = ' . FLAG_NO . ' AND a.bind_status = ' . USER_BIND_STATUS_UNBIND . $subSql . ') AS t ';
        $cmd1 -> join = '';
        $user_num = $cmd1->queryColumn();
        $pages = new CPagination($user_num[0]);


        


        $count = count($users);

        //查询门店
        $stores = Yii::app()->db->createCommand()
                            ->select('a.id, a.name, a.branch_name')
                             ->from('wq_store a')
                             ->where('a.merchant_id = :merchant_id', array(':merchant_id' => $merchant_id))
                             ->queryAll();
        foreach ($stores as $store) {
            $store_lists[$store['id']]['name'] = empty($store['branch_name']) ? $store['name'] : $store['name']."-".$store['branch_name'];
        }

        //查询会员等级
        $userGrades = Yii::app()->db->createCommand()
                                     ->select('a.id, a.merchant_id, a.name AS grade_name')
                                     ->from('wq_user_grade a')
                                     ->where('a.merchant_id = :merchant_id', array(':merchant_id' => $merchant_id))
                                      ->queryAll();
        foreach ($userGrades as $userGrade) {
            $userGrade[$userGrade['id']]['grade_name'] = $userGrade['grade_name'];
        }

        $user_groups = UserGroup::model()->findAll('merchant_id=' . $merchant_id . ' AND flag=' . FLAG_NO);
        $user_grades = UserGrade::model()->findAll('merchant_id=' . $merchant_id . ' AND flag=' . FLAG_NO);

        $this->render('userLists', array(
            'users' => $users,
            'store_lists' => $stores,
            'grade_lists' => $userGrades,
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
        $command->select('a.*, b.*, c.*, d.name as grade_name, d.membercard_img');
        $command->from('wq_user a');
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

        $command->where('a.id = :id', array(':id' => $user_id));
        $user = $command->queryRow();
        if (empty($user))
            exit('该用户不存在！');

        //获取成长记录
        $command2 = Yii::app()->db->createCommand();
        $command2->select('a.user_id, a.user_grade_id, a.user_grade_name, a.create_time');
        $command2->from('wq_user_growup_record a');
        $command2->leftJoin('wq_user_grade b', 'a.user_grade_id = b.id');
        $command2->where('a.user_id = :user_id and a.flag = :flag', array(
            ':user_id' => $user_id,
            ':flag' => FLAG_NO
        ));
        $command2->order('a.create_time ASC');
        $growupRecord = $command2->queryAll();

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
        $user_store_orders = Order::model()->findAll('(user_id=:user_id or wechat_user_id=:wechat_user_id or alipay_user_id=:alipay_user_id) AND flag=:flag AND order_type=:order_type AND pay_status=:pay_status AND order_status IN ' . $allowed_types_Str . ' order by create_time asc', array(':user_id' => $user_id, ':wechat_user_id' => $wechat_user_id, ':alipay_user_id' => $alipay_user_id, ':flag' => FLAG_NO, ':order_type' => ORDER_TYPE_CASHIER, ':pay_status' => ORDER_STATUS_PAID));
        //累计门店消费金额
        $total_store_xf_money = 0;
        //消费次数
        $total_store_xf_num = 0;
        foreach ($user_store_orders as $v) {
            if ($v->order_status == ORDER_STATUS_NORMAL) {
                $shishou = $v->order_paymoney - $v->coupons_money - $v->discount_money - $v->merchant_discount_money;
                $total_store_xf_money += $shishou;
                $total_store_xf_num++;
            }
        }

        //累计获得积分
        $user_total_get_score = 0;

        $command3 = Yii::app()->db->createCommand();
        $command3->select('a.user_id, a.points, a.balance_of_payments');
        $command3->from('wq_user_pointsdetail a');
        $command3->where('a.user_id = :user_id', array(
            ':user_id' => $user_id
        ));
        $userPointsDetail = $command3->queryAll();

        if (!empty($userPointsDetail)) {
            foreach ($userPointsDetail as $v) {
                if ($v['balance_of_payments'] == POINT_PAYMENT_PAY)
                    $user_total_get_score += $v['points'];
                if ($v->balance_of_payments == BALANCE_OF_PAYMENTS_REFUND)
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
        $criteria->addCondition('order_type=:order_type AND pay_status=:pay_status AND (user_id=:user_id or wechat_user_id=:wechat_user_id or alipay_user_id=:alipay_user_id)');
        $criteria->addInCondition('order_status', $allowed_types);
        $criteria->params[':order_type'] = ORDER_TYPE_CASHIER;
        $criteria->params[':user_id'] = $user_id;
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
        $user_store_orders = Order::model()->findAll('(user_id=:user_id or wechat_user_id=:wechat_user_id or alipay_user_id=:alipay_user_id) AND flag=:flag AND order_type=:order_type AND pay_status=:pay_status AND order_status IN ' . $allowed_types_Str . ' order by create_time asc', array(
            ':user_id' => $user_id,
            ':wechat_user_id' => $wechat_user_id,
            ':alipay_user_id' => $alipay_user_id,
            ':flag' => FLAG_NO,
            ':order_type' => ORDER_TYPE_CASHIER,
            ':pay_status' => ORDER_STATUS_PAID
        ));
        $user_xf_total_money = 0;
        $user_xf_total_num = 0;
        $user_xf_first_time = '未消费';
        if (!empty($user_store_orders))
            $user_xf_first_time = $user_store_orders[0]['create_time'];

        if (!empty($user_store_orders)) {
            foreach ($user_store_orders as $order) {
                if ($order->order_status == ORDER_STATUS_NORMAL) {
                    $shishou = $order->order_paymoney - $order->coupons_money - $order->discount_money - $order->merchant_discount_money;
                    $user_xf_total_money += $shishou;
                    $user_xf_total_num++;
                }
            }
        }


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
//         var_dump($users);
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

        $pointDetailes = UserPointsdetail::model()->findAll($criteria);

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
//                 var_dump($users);
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
//             'total_stored'=>$total_stored,
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
//         $criteria->addCondition('flag='.FLAG_NO);
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