<?php
class InitDataController extends Controller{
    
    //初始化门店版本及有效时间
    public function actionInitStoreEdition(){
        $transaction = Yii::app()->db->beginTransaction(); //开启事务
        try {
            //查询所有营销版商户
            $merchant = Merchant::model() -> findAll('gj_product_id = :gj_product_id and flag = :flag and gj_open_status = :gj_open_status and if_tryout = :if_tryout',array(
                ':gj_product_id' => 2,
                ':flag' => 1,
                ':gj_open_status' => 2,
                ':if_tryout' => 1
            ));
            $count_merchant = 0;
            foreach ($merchant as $k => $v){
                $count_merchant ++;
                $count_store = 0;
                $store = Store::model() -> findAll('flag = :flag and merchant_id = :merchant_id',array(
                    ':flag' => 1,
                    ':merchant_id' => $v -> id
                ));
                foreach ($store as $x => $y){
                    $y -> edition = 2;
                    $y -> marking_start_time = $v -> gj_start_time;
                    $y -> marking_end_time = $v -> gj_end_time;
                    if($y -> update()){
                        $count_store ++;
                    }else{
                        throw new Exception('数据库保存失败'.$y -> id);
                    }
                }
                echo $v -> wq_m_name.':'.$count_store.'<br>';
            }
            echo '总商户数：'.$count_merchant;
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollback();
            echo $e -> getMessage();
        }
    }
    
    /*
     * 初始化会员最近消费门店情况
     */
    public function actionInitFansAndMemberLastTrade(){
        $transaction = Yii::app()->db->beginTransaction(); //开启事务
        try {
            $criteria = new CDbCriteria();
            $criteria->addCondition('flag = :flag');
            $criteria->params[':flag'] = 1;
            $criteria->addCondition('type = :type');
            $criteria->params[':type'] = 1;
            //查询所有会员
            $user = User::model()->findAll($criteria);
            $count_user = count($user);
            $count = 0;
            foreach ($user as $k => $v){
                $order = Order::model() -> find('pay_status = :pay_status and order_type = :order_type and flag = :flag and user_id = :user_id order by pay_time desc',array(
                    ':pay_status' => 2,
                    ':flag' => 1,
                    ':order_type' => 3,
                    ':user_id' => $v -> id
                ));
                if(!empty($order)){
                    $v -> last_trade_time = $order -> pay_time;
                    $v -> last_trade_store = $order -> store_id;
                    if($v -> update()){
                        $count ++;
                    }else{
                        throw new Exception('更新失败:'.$v -> id);
                    }
                }
            }
            echo '初始化会员数：'.$count_user;
            echo '更新成功会员数：'.$count;
            $transaction->commit();
        }catch (Exception $e){
            $transaction->rollback();
            echo $e -> getMessage();
        }
    }

    /**
     * 初始化粉丝绑定
     */
    public function actionInitFansBind()
    {
        $criteria = new CDbCriteria();
        $criteria->addCondition('flag = :flag');
        $criteria->params[':flag'] = 1;
        $criteria->addCondition('type = :type');
        $criteria->params[':type'] = 1;
        $user = User::model()->findAll($criteria);
        $count_user = count($user);
        $count = 0;
        foreach ($user as $v) {
            $fans = User::model()->find('wechat_id = :wechat_id and type != :type and merchant_id = :merchant_id', array(
                ':wechat_id' => $v->wechat_id,
                ':type' => 1,
                ':merchant_id' => $v -> merchant_id
            ));
            if (!empty($fans)) {
                $fans->bind_status = 2;
                if ($fans->save()) {
                    $count ++;
                }
            }
        }
        echo '总会员数：'.$count_user.'<br>';
        echo '成功绑定粉丝的会员数：'.$count.'<br>';
    }

    /*
     * 初始化积分记录
     */
    public function actionInitPointsRecord() {
        set_time_limit(0);
        ini_set('memory_limit', '500M');
    
        try {
            $record_count = 0;
            $error_arr = array();
    
            $criteria = new CDbCriteria();
            $criteria->addCondition('flag = :flag');
            $criteria->params[':flag'] = 1;
            $criteria->addCondition('user_id is not null or user_id != ""');
            $criteria->addCondition('pay_status = :pay_status');
            $criteria->params[':pay_status'] = 2;
            $criteria->addCondition('order_type = :order_type');
            $criteria->params[':order_type'] = 3;
            $list = Order::model()->findAll($criteria);
    
            foreach ($list as $order) {
                //查询会员信息
                $user = User::model()->findByPk($order['user_id']);
                if (empty($user)) {
                    $error_arr[] = 'user_id:'.$order['user_id'].'不存在';
                    continue;
                }
                //查询会员所属等级
                $grade = UserGrade::model()->find('merchant_id = :merchant_id and id = :id',
                    array(':merchant_id' => $user['merchant_id'], ':id' => $user['membershipgrade_id']));
                if (empty($grade)) {
                    $error_arr[] = 'user_grade_id:'.$user['membershipgrade_id'].'在merchant_id:'.$user['merchant_id'].'下不存在';
                }
    
                //实收金额
                $money = $order['order_paymoney'] - $order['coupons_money'] - $order['discount_money'] - $order['merchant_discount_money'];
    
                //新增加分记录
                $points_record = new UserPointsdetail();
                $points_record['create_time'] = $order['pay_time'];
                $points_record['user_id'] = $order['user_id'];
                $points_record['order_id'] = $order['id'];
                $points_record['points'] = floor($money * $grade['points_ratio']);
                $points_record['ratio'] = $grade['points_ratio'];
                $points_record['balance_of_payments'] = 1;
                $points_record['from'] = 1;
                if (!$points_record->save()) {
                    throw new Exception('save error:'.CJSON::encode($points_record->getErrors()));
                }
    
                //退款记录
                $cmd = Yii::app()->db->createCommand();
                //查询条件
                $cmd->andWhere('flag = :flag');
                $cmd->params[':flag'] = 1;
                $cmd->andWhere('order_id = :order_id');
                $cmd->params[':order_id'] = $order['id'];
                $cmd->andWhere('status != :status');
                $cmd->params[':status'] = 2;
                $cmd->from = 'wq_refund_record';
                $cmd->select = 'SUM(refund_money) AS refund_money, refund_time';
                $record = $cmd->queryRow();
    
                if (!empty($record)) {
                    $money = $record['refund_money'];
                    if (empty($money)) {
                        continue;
                    }
    
                    //新增减分记录
                    $points_record = new UserPointsdetail();
                    $points_record['create_time'] = $record['refund_time'];
                    $points_record['user_id'] = $order['user_id'];
                    $points_record['order_id'] = $order['id'];
                    $points_record['points'] = floor($money * $grade['points_ratio']);
                    $points_record['ratio'] = $grade['points_ratio'];
                    $points_record['balance_of_payments'] = 3;
                    $points_record['from'] = 7;
                    if (!$points_record->save()) {
                        throw new Exception('save error:'.CJSON::encode($points_record->getErrors()));
                    }
                }
            }
    
            echo 'record count:'.$record_count."\n";
            echo 'error_descript:'.json_encode($error_arr);
        } catch (Exception $e) {
            echo 'exception:'.$e->getMessage();
        }
    }

    /**
     * 优惠券统计
     */
    public function actionCouponStatistics()
    {
        set_time_limit(0);
        ini_set('memory_limit', '500M');
        if (isset($_GET['Yesterday']) && $_GET['Yesterday'] == 'CouponStatistics160625') {
            $db = Yii::app()->db;

            $start_time = $_GET['s'];
            $end_time = $_GET['e'];

            $start_day = (strtotime($end_time) - strtotime($start_time)) / 86400;

            for ($i = $start_day; $i >= 0; $i--) {
                $start_time = date('Ymd', strtotime('-' . $i . ' days'));
                //查询前一天总数据
                $sql = "
                SELECT
                    a.merchant_id, a.id, a.pv AS total_pv,
                    IFNULL(b.get_num, 0) AS total_get_num,
                    IFNULL(c.use_num, 0) AS total_use_num,
                    IFNULL(d.total_order_num, 0) AS total_order_num,
                    IFNULL(d.total_trade_money, 0) AS total_trade_money,
                    IFNULL(d.total_discount_money, 0) AS total_discount_money,
                    IFNULL(d.total_cash_money, 0) AS total_cash_money,
                    IFNULL(d.total_trade_money - d.total_cash_money, 0) AS total_notcash_money,
                    IFNULL(e.new_order_num, 0) AS new_order_num,
                    IFNULL(e.new_trade_money, 0) AS new_trade_money,
                    IFNULL(e.new_discount_money, 0) AS new_discount_money,
                    IFNULL(e.new_cash_money, 0) AS new_cash_money,
                    IFNULL(e.new_trade_money - e.new_cash_money, 0) AS new_notcash_money,
                    IFNULL(f.total_browse_person_num, 0) AS total_browse_person_num,
                    IFNULL(g.new_browse_person_num, 0) AS new_browse_person_num,
                    IFNULL(h.new_get_num, 0) AS new_get_num,
	                IFNULL(i.new_use_num, 0) AS new_use_num,
	                IFNULL(COUNT(j.user_id), 0) AS new_receive_person_num,
	                IFNULL(COUNT(k.user_id), 0) AS new_consume_person_num
                FROM wq_coupons a
                LEFT JOIN (
                    SELECT
                        a.coupons_id, COUNT(a.coupons_id) AS get_num
                    FROM wq_user_coupons a
                    LEFT JOIN wq_coupons b ON a.coupons_id = b.id
                    WHERE UNIX_TIMESTAMP(a.create_time) < " . strtotime($start_time) . "
                    GROUP BY a.coupons_id
                ) b ON a.id = b.coupons_id
                LEFT JOIN (
                    SELECT
                        b.coupons_id, COUNT(b.coupons_id) AS use_num
                    FROM wq_coupon_validate_record a
                    LEFT JOIN wq_user_coupons b ON a.user_coupon_id = b.id
                    WHERE UNIX_TIMESTAMP(a.validate_time) < " . strtotime($start_time) . "
                    GROUP BY b.coupons_id
                ) c ON a.id = c.coupons_id
                LEFT JOIN (
                    SELECT
                        a.coupons_id,
                        COUNT(a.coupons_id) AS total_order_num,
                        SUM(b.order_paymoney) AS total_trade_money,
                        SUM(b.coupons_money) AS total_discount_money,
                        SUM(b.cash_paymoney) AS total_cash_money
                    FROM
                        wq_user_coupons a
                    LEFT JOIN wq_order b ON a.order_id = b.id
                    WHERE b.pay_status = 2
                    AND UNIX_TIMESTAMP(b.pay_time) < " . strtotime($start_time) . "
                    GROUP BY a.coupons_id
                ) d ON a.id = d.coupons_id
                LEFT JOIN (
                    SELECT
                        a.coupons_id,
                        COUNT(a.coupons_id) AS new_order_num,
                        SUM(b.order_paymoney) AS new_trade_money,
                        SUM(b.coupons_money) AS new_discount_money,
                        SUM(b.cash_paymoney) AS new_cash_money
                    FROM
                        wq_user_coupons a
                    LEFT JOIN wq_order b ON a.order_id = b.id
                    WHERE b.pay_status = 2
                    AND UNIX_TIMESTAMP(b.pay_time) < " . strtotime($start_time) . "
                    AND UNIX_TIMESTAMP(b.pay_time) >= " . (strtotime($start_time) - 86400) . "
                    GROUP BY a.coupons_id
                ) e ON a.id = e.coupons_id
                LEFT JOIN (
                    SELECT
                        a.coupon_id, COUNT(a.coupon_id) AS total_browse_person_num
                    FROM wq_coupon_ip a
                    WHERE UNIX_TIMESTAMP(a.create_time) < " . strtotime($start_time) . "
                    GROUP BY a.coupon_id
                ) f ON a.id = f.coupon_id
                LEFT JOIN (
                    SELECT
                        a.coupon_id, COUNT(a.coupon_id) AS new_browse_person_num
                    FROM wq_coupon_ip a
                    WHERE UNIX_TIMESTAMP(a.create_time) < " . strtotime($start_time) . "
                    AND UNIX_TIMESTAMP(a.create_time) >= " . (strtotime($start_time) - 86400) . "
                    GROUP BY a.coupon_id
                ) g ON a.id = g.coupon_id
                LEFT JOIN(
                    SELECT
                        a.coupons_id, COUNT(a.coupons_id) AS new_get_num
                    FROM wq_user_coupons a
                    LEFT JOIN wq_coupons b ON a.coupons_id = b.id
                    WHERE UNIX_TIMESTAMP(a.create_time) < " . strtotime($start_time) . "
                    AND UNIX_TIMESTAMP(a.create_time) >= " . (strtotime($start_time) - 86400) . "
                    GROUP BY a.coupons_id
                ) h ON a.id = h.coupons_id
                LEFT JOIN (
                    SELECT
                        b.coupons_id, COUNT(b.coupons_id) AS new_use_num
                    FROM wq_coupon_validate_record a
                    LEFT JOIN wq_user_coupons b ON a.user_coupon_id = b.id
                    WHERE UNIX_TIMESTAMP(a.validate_time) < " . strtotime($start_time) . "
                    AND UNIX_TIMESTAMP(a.validate_time) >= " . (strtotime($start_time) - 86400) . "
                    GROUP BY b.coupons_id
                ) i ON a.id = i.coupons_id
                LEFT JOIN (
                    SELECT
                        a.coupons_id, a.user_id
                    FROM wq_user_coupons a
                    WHERE UNIX_TIMESTAMP(a.create_time) < " . strtotime($start_time) . "
                    AND UNIX_TIMESTAMP(a.create_time) >= " . (strtotime($start_time) - 86400) . "
                    GROUP BY a.coupons_id, a.user_id
                ) j ON a.id = j.coupons_id
                LEFT JOIN (
                    SELECT
                        b.coupons_id, b.user_id
                    FROM wq_coupon_validate_record a
                    LEFT JOIN wq_user_coupons b ON a.user_coupon_id = b.id
                    WHERE UNIX_TIMESTAMP(a.validate_time) < " . strtotime($start_time) . "
                    AND UNIX_TIMESTAMP(a.validate_time) >= " . (strtotime($start_time) - 86400) . "
                    GROUP BY b.coupons_id, b.user_id
                ) k ON a.id = k.coupons_id
                WHERE a.flag = " . FLAG_NO . "
                GROUP BY a.id";

                $command = $db->createCommand($sql);
                $result = $command->queryAll();

                //计算当天数据
                foreach ($result as $key => $v) {
                    //查询该优惠券该天是否添加过记录
                    $res = CouponStatistics::model()->find('coupon_id = :coupon_id and date = :date', array(
                        ':coupon_id' => $v['id'],
                        ':date' => date('Y-m-d 00:00:00', strtotime($start_time) - 86400)
                    ));

                    if (empty($res)) {
                        $CouponStatisticsModel = new CouponStatistics();

                        //保存总数据
                        $CouponStatisticsModel->coupon_id = $v['id'];
                        $CouponStatisticsModel->merchant_id = $v['merchant_id'];
                        $CouponStatisticsModel->date = date('Y-m-d', strtotime($start_time) - 86400) . ' 00:00:00';
                        //计算新增浏览量
                        //查询前天是否添加过记录
                        $res_couponstatistics = CouponStatistics::model()->find('coupon_id = :coupon_id and date = :date', array(
                            ':coupon_id' => $v['id'],
                            ':date' => date('Y-m-d 00:00:00', strtotime($start_time) - 2 * 86400)
                        ));
                        if (!empty($res_couponstatistics)) {
                            //前天添加过记录，昨天新增的浏览量等于昨天的总浏览量减去前天的浏览量
                            $CouponStatisticsModel->new_browse_num = $v['total_pv'] - $res_couponstatistics->total_browse_num;
                        } else {
                            //没有添加过记录，昨天新增的浏览量等于昨天的总浏览量
                            $CouponStatisticsModel->new_browse_num = $v['total_pv'];
                        }
                        $CouponStatisticsModel->total_browse_num = $v['total_pv'];
                        $CouponStatisticsModel->new_receive_num = $v['new_get_num'];
                        $CouponStatisticsModel->total_receive_num = $v['total_get_num'];
                        $CouponStatisticsModel->new_use_num = $v['new_use_num'];
                        $CouponStatisticsModel->total_use_num = $v['total_use_num'];
                        $CouponStatisticsModel->new_order_num = $v['new_order_num'];
                        $CouponStatisticsModel->total_order_num = $v['total_order_num'];
                        $CouponStatisticsModel->new_trade_money = $v['new_trade_money'];
                        $CouponStatisticsModel->total_trade_money = $v['total_trade_money'];
                        $CouponStatisticsModel->new_discount_money = $v['new_discount_money'];
                        $CouponStatisticsModel->total_discount_money = $v['total_discount_money'];
                        $CouponStatisticsModel->new_cash_money = $v['new_cash_money'];
                        $CouponStatisticsModel->total_cash_money = $v['total_cash_money'];
                        $CouponStatisticsModel->new_notcash_money = $v['new_notcash_money'];
                        $CouponStatisticsModel->total_notcash_money = $v['total_notcash_money'];
                        $CouponStatisticsModel->new_browse_person_num = $v['new_browse_person_num'];
                        $CouponStatisticsModel->total_browse_person_num = $v['total_browse_person_num'];
                        $CouponStatisticsModel->new_receive_person_num = $v['new_receive_person_num'];
                        $CouponStatisticsModel->new_consume_person_num = $v['new_consume_person_num'];

                        $CouponStatisticsModel->create_time = date('Y-m-d H:i:s', time());

                        $CouponStatisticsModel->save();
                    }
                }
            }
        }
    }

}