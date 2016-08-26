<?php
include_once(dirname(__FILE__) . '/../mainClass.php');

/**
 *  管家首页
 *  1.获取商户基本信息
 *  2.获取商户统计数据
 */
class MerchantClass extends mainClass {

    /**
     *  获取商户基本信息
     * @parmas int $merchant_id  商户id
     */
    public function getMerchantIndex($merchant_id) {
        //返回数据
        $result = array();

        //收银版门店数
        $store_cashier_num = Store::model() -> count('merchant_id = :merchant_id AND edition = :edition AND flag = :flag', array(':merchant_id' => $merchant_id, ':edition' => STORE_EDITION_CASHIER, ':flag' => FLAG_NO));
        //营销版门店数
        $store_marketing_num = Store::model() -> count('merchant_id = :merchant_id AND edition = :edition AND flag = :flag', array(':merchant_id' => $merchant_id, ':edition' => STORE_EDITION_MARKETING, ':flag' => FLAG_NO));
        //门店数据
        $result['store_cashier_num'] = $store_cashier_num;
        $result['store_marketing_num'] = $store_marketing_num;
        //总门店数门店数
        $result['store_num'] = $store_cashier_num + $store_marketing_num;;


        //商户信息
        $merchant = Merchant::model() -> findByPk($merchant_id);
        //支付宝开通状态
        $result = $this -> getAlipayStatus($merchant, $result);

        //微信开通状态
        $result = $this -> getWxStatus($merchant, $result);

        //门店店员设置状态
        $operator_status = IS_NO;
        $cmdOperator = Yii::app() -> db -> createCommand();
        $cmdOperator -> select = 'o.id';
        $cmdOperator -> from = 'wq_operator AS o';
        $cmdOperator -> andWhere('o.store_id IN (SELECT id FROM wq_store WHERE merchant_id = :merchant_id)');
        $cmdOperator -> params[':merchant_id'] = $merchant_id;
        $operator = $cmdOperator -> queryAll();
        if (!empty($operator)) {
            $operator_status = IS_YES;
        }
        //门店店员设置状态
        $result['operator_status'] = $operator_status;

        //收款通知设置状态
        $trade_status = IS_NO;
        $cmdTrade = Yii::app() -> db -> createCommand();
        $cmdTrade -> select = 't.id';
        $cmdTrade -> from = 'wq_trade_notice_user AS t';
        $cmdTrade -> andWhere('t.store_id IN (SELECT id FROM wq_store WHERE merchant_id = :merchant_id)');
        $cmdTrade -> params[':merchant_id'] = $merchant_id;
        $operator = $cmdTrade -> queryAll();
        if (!empty($operator)) {
            $trade_status = IS_YES;
        }
        //门店店员设置状态
        $result['trade_status'] = $trade_status;

        //会员等级设置状态
        $level_status = IS_NO;
        $level = UserGrade::model() -> findAll('merchant_id = :merchant_id', array(':merchant_id' => $merchant_id));
        if (!empty($level)) {
            $level_status = IS_YES;
        }
        $result['level_status'] = $level_status;

        return $result;
    }

    /**
     *  获取商户统计
     * @parmas int $merchant_id  商户id
     */
    public function getMerchantStatistics($merchant_id) {
        $data = array();
        $end_time = date('Y-m-d H:i:s');
        $start_time = date('Y-m-d 00:00:00');
        //获取交易金额和交易量:今日
        $cmd1 = Yii::app()->db->createCommand();
        $cmd1->select = 'COUNT(*) AS num,
                SUM(order_paymoney) AS order_paymoney,
                SUM(coupons_money) AS coupons_money,
                SUM(discount_money) AS discount_money,
                SUM(merchant_discount_money) AS merchant_discount_money
                ';
        $cmd1->from = 'wq_order';
        $cmd1->andWhere('pay_status = :pay_status');
        $cmd1->params[':pay_status'] = ORDER_STATUS_PAID;//已支付订单
        $cmd1->andWhere('order_type = :order_type');
        $cmd1->params[':order_type'] = ORDER_TYPE_CASHIER;//收银订单
        $cmd1->andWhere('order_status != :order_status');
        $cmd1->params[':order_status'] = ORDER_STATUS_REVOKE;//已撤销
        $cmd1->andWhere('merchant_id = :merchant_id');
        $cmd1->params[':merchant_id'] = $merchant_id;//商户id
        $cmd1->andWhere('flag = :flag');
        $cmd1->params[':flag'] = FLAG_NO;
        $cmd1->andWhere('pay_time <= :end_time');
        $cmd1->params[':end_time'] = $end_time;
        $cmd1->andWhere('pay_time >= :start_time');
        $cmd1->params[':start_time'] = $start_time;
        //退款金额
        $cmd2 = Yii::app()->db->createCommand();
        $cmd2->select = 'SUM(refund_money) AS refund_money';
        $cmd2->from = 'wq_refund_record';
        $cmd2->andWhere('status != :status');
        $cmd2->params[':status'] = REFUND_STATUS_FAIL;//退款失败
        $cmd2->andWhere('type = :type');
        $cmd2->params[':type'] = REFUND_TYPE_REFUND;//退款
        $cmd2->andWhere('flag = :flag');
        $cmd2->params[':flag'] = FLAG_NO;
        $cmd2->andWhere('merchant_id = :merchant_id');
        $cmd2->params[':merchant_id'] = $merchant_id;//商户id
        $cmd2->andWhere('refund_time <= :end_time');
        $cmd2->params[':end_time'] = $end_time;
        $cmd2->andWhere('refund_time >= :start_time');
        $cmd2->params[':start_time'] = $start_time;
        $list1 = $cmd1->queryROW();
        $list2 = $cmd2->queryROW();
        $data['yesterday_new_trade_actual_money'] = empty($list1['num'])?0:$list1['order_paymoney']-$list1['coupons_money']-$list1['discount_money']-$list1['merchant_discount_money']-$list2['refund_money'];
        $data['yesterday_new_trade_num'] = empty($list1['num'])>0?0:$list1['num'];
        //实时统计今日粉丝数
        $cmd = Yii::app()->db->createCommand();
        $cmd->andWhere('flag = :flag');
        $cmd->params[':flag'] = FLAG_NO;
        $cmd->andWhere('create_time <= :end_time');
        $cmd->params[':end_time'] = $end_time;
        $cmd->andWhere('create_time >= :start_time');
        $cmd->params[':start_time'] = $start_time;
        $cmd->andWhere('merchant_id = :merchant_id');
        $cmd->params[':merchant_id'] = $merchant_id;
        //指定查询表
        $cmd->from = 'wq_user';
        //查询
        $select = 'COUNT(*) AS num';
        $cmd->select = $select;
        //设置多个查询语句
        $cmd3 = clone $cmd; //今日玩券会员
        $cmd4 = clone $cmd; //今日支付宝粉丝
        $cmd5 = clone $cmd; //今日微信粉丝
        //筛选今日玩券会员
        $cmd3->andWhere('type = :type');
        $cmd3->params[':type'] = USER_TYPE_WANQUAN_MEMBER;
        //筛选今日支付宝粉丝（已关注的）
        $cmd4->andWhere('type = :type');
        $cmd4->params[':type'] = USER_TYPE_ALIPAY_FANS;
        $cmd4->andWhere('alipay_status = :alipay_status');
        $cmd4->params[':alipay_status'] = ALIPAY_USER_SUBSCRIBE;
        //筛选今日微信粉丝（已关注的）
        $cmd5->andWhere('type = :type');
        $cmd5->params[':type'] = USER_TYPE_WECHAT_FANS;
        $cmd5->andWhere('wechat_status = :wechat_status');
        $cmd5->params[':wechat_status'] = WECHAT_USER_SUBSCRIBE;
        //执行sql
        $list3 = $cmd3->queryROW();
        $list4 = $cmd4->queryROW();
        $list5 = $cmd5->queryROW();
        //粉丝数据
        $data['yesterday_new_member_num'] = empty($list3['num'])?0:$list3['num'];
        $data['yesterday_new_alipayfans_num'] = empty($list4['num'])?0:$list4['num'];
        $data['yesterday_new_wechatfans_num'] = empty($list5['num'])?0:$list5['num'];
        
        //今日数据  交易总额, 交易数量
        $yesterday_date = date('Y-m-d', strtotime('-1 day'));
        $yesterday = MStatistics::model() -> find('merchant_id = :merchant_id AND date LIKE :date', array(':merchant_id' => $merchant_id, ':date' => "$yesterday_date%"));
        //本月数据  交易总额, 交易数量  新增支付宝粉丝数, 微信粉丝数, 会员数
        $s_date = date('Y-m-d 00:00:00', strtotime('-1 month'));
        $e_date = date('Y-m-d 23:59:59', time());
        $month = MStatistics::model() -> findAll('merchant_id = :merchant_id AND date BETWEEN :s_date AND :e_date', array(':merchant_id' => $merchant_id, ':s_date' => $s_date, ':e_date' => $e_date));
        $new_trade_actual_money = 0;
        $new_trade_num = 0;
        $new_alipayfans_num = 0;
        $new_wechatfans_num = 0;
        $new_member_num = 0;
        if (!empty($month)) {
            foreach ($month as $v) {
                $new_trade_actual_money += $v->new_trade_actual_money;
                $new_trade_num += $v->new_trade_num;
                $new_alipayfans_num += $v->new_alipayfans_num;
                $new_wechatfans_num += $v->new_wechatfans_num;
                $new_member_num += $v->new_member_num;
            }
        }
        //交易数据
        
        $data['month_new_trade_actual_money'] = $new_trade_actual_money + $data['yesterday_new_trade_actual_money'];
        $data['month_new_trade_num'] = $new_trade_num + $data['yesterday_new_trade_num'];
        //粉丝数据
        $data['month_new_alipayfans_num'] = $new_alipayfans_num + $data['yesterday_new_alipayfans_num'];
        $data['month_new_wechatfans_num'] = $new_wechatfans_num + $data['yesterday_new_wechatfans_num'];
        $data['month_new_member_num'] = $new_member_num + $data['yesterday_new_member_num'];

        //累计数据  交易总额, 交易数量  新增支付宝粉丝数, 微信粉丝数, 会员数
        $total = MStatistics::model() -> find('merchant_id = :merchant_id ORDER BY date DESC', array(':merchant_id' => $merchant_id));
        //交易数据
        $data['total_trade_actual_money'] = !empty($total -> total_trade_actual_money) ? $total -> total_trade_actual_money : 0;
        $data['total_trade_num'] = !empty($total ->total_trade_num) ? $total ->total_trade_num : 0;
        $data['total_trade_actual_money'] += $data['yesterday_new_trade_actual_money'];
        $data['total_trade_num'] += $data['yesterday_new_trade_num'];
        //粉丝数据
        $data['total_alipayfans_num'] = !empty($total -> total_alipayfans_num) ? $total -> total_alipayfans_num : 0;
        $data['total_wechatfans_num'] = !empty($total -> total_wechatfans_num) ? $total -> total_wechatfans_num : 0;
        $data['total_member_num'] = !empty($total -> total_member_num) ? $total -> total_member_num : 0;
        $data['total_alipayfans_num'] += $data['yesterday_new_alipayfans_num'];
        $data['total_wechatfans_num'] += $data['yesterday_new_wechatfans_num'];
        $data['total_member_num'] += $data['yesterday_new_member_num'];

        return $data;
    }

    /**
     * 支付宝支付,
     * 支付宝服务窗状态,
     * 支付宝口碑门店,
     * 支付宝授权,
     * 支付宝服务窗菜单
     * @param $merchant     商户数据
     * @param $result       返回数据
     * @return mixed
     */
    private function getAlipayStatus($merchant, $result) {
        $alipay_version_status = IS_NO;
        //支付宝支付开通状态
        switch($merchant -> alipay_api_version) {
            //支付宝1.0接口
            case ALIPAY_API_VERSION_1_API:
                if (!empty($merchant -> partner))
                    $alipay_version_status = IS_YES;
                break;
            //支付宝2.0接口
            case ALIPAY_API_VERSION_2_API:
                if (!empty($merchant -> appid))
                    $alipay_version_status = IS_YES;
                break;
            //支付宝2.0授权接口
            case ALIPAY_API_VERSION_2_AUTH_API:
                if (!empty($merchant -> alipay_auth_token))
                    $alipay_version_status = IS_YES;
                break;

            default:
                break;
        }

        //支付宝服务窗开通状态
        if (!empty($merchant -> appid)) {
            $alipay_service_status = IS_YES;
        } else {
            $alipay_service_status = IS_NO;
        }

        //支付宝口碑门店开通状态
        $alipay_store_status = IS_NO;
        $store = Store::model() -> findAll('merchant_id = :merchant_id AND alipay_sync_verify_status = :alipay_sync_verify_status', array(':merchant_id' => $merchant -> id, ':alipay_sync_verify_status' => STORE_ALIPAY_SYNC_STATUS_PASS));
        if (!empty($store)) {
            $alipay_store_status = IS_YES;
        }

        //支付宝授权状态
        $alipay_auth_status = IS_NO;
        if ($merchant -> alipay_api_version == ALIPAY_API_VERSION_2_AUTH_API) {
            if (!empty($merchant -> alipay_auth_token)) {
                $alipay_auth_status = IS_YES;
            }
        }

        //支付宝服务窗菜单
        $alipay_menu_status = IS_NO;
        $menu = Menu::model() -> findAll('merchant_id = :merchant_id AND channel_type = :channel_type', array(':merchant_id' => $merchant -> id, 'channel_type' => MATERIAL_CHANNEL_TYPE_ALIPAY));
        if (!empty($menu)) {
            $alipay_menu_status = IS_YES;
        }

        //支付宝数据
        $result['alipay_version_status'] = $alipay_version_status;
        $result['alipay_service_status'] = $alipay_service_status;
        $result['alipay_store_status'] = $alipay_store_status;
        $result['alipay_auth_status'] = $alipay_auth_status;
        $result['alipay_menu_status'] = $alipay_menu_status;

        return $result;
    }

    /**
     * 微信支付,
     * 微信公众号,
     * 微信公众号授权状态,
     * 微信公众号菜单
     * @param $merchant     商户数据
     * @param $result       返回数据
     * @return mixed
     */
    private function getWxStatus($merchant, $result) {
        //微信支付开通状态
        $wxpay_status = IS_NO;
        switch ($merchant -> wxpay_merchant_type) {
            //自助商户
            case WXPAY_MERCHANT_TYPE_SELF:
                if (!empty($merchant -> wechat_appid) && !empty($merchant -> wechat_appsecret) && !empty($merchant -> wechat_mchid) && !empty($merchant -> wechat_key)) {
                    $wxpay_status = IS_YES;
                }
                break;

            //特约商户
            case WXPAY_MERCHANT_TYPE_AFFILIATE:
                if (!empty($merchant -> t_wx_mchid)) {
                    $wxpay_status = IS_YES;
                }
                break;

            default:
                break;
        }

        //微信公众号开通状态
        $wechat_status = IS_NO;
        switch ($merchant -> wechat_thirdparty_authorizer_if_auth) {
            //未授权
            case IS_NO:
                if (!empty($merchant -> wechat_subscription_appid) && !empty($merchant -> wechat_subscription_appsecret)){
                    $wechat_status = IS_YES;
                }
                break;
            //已授权
            case IS_YES:
                if (!empty($merchant -> wechat_thirdparty_authorizer_appid)) {
                    $wechat_status = IS_YES;
                }
                break;

            default:
                break;
        }

        //微信公众号授权开通状态
        $wechat_auth_status = IS_NO;
        if ($merchant -> wechat_thirdparty_authorizer_if_auth == IS_YES && !empty($merchant -> wechat_thirdparty_authorizer_appid)) {
            $wechat_auth_status = IS_YES;
        }

        //公众号菜单设置状态
        $wechat_menu_status = IS_NO;
        $menu = Menu::model() -> findAll('merchant_id = :merchant_id AND channel_type = :channel_type', array(':merchant_id' => $merchant -> id, 'channel_type' => MATERIAL_CHANNEL_TYPE_WECHAT));
        if (!empty($menu)) {
            $wechat_menu_status = IS_YES;
        }

        //微信数据
        $result['wxpay_status'] = $wxpay_status;
        $result['wechat_status'] = $wechat_status;
        $result['wechat_auth_status'] = $wechat_auth_status;
        $result['wechat_menu_status'] = $wechat_menu_status;

        return $result;
    }

    /**
     *  计算今日交易金额
     */
    public function getTodayOrder($data, $merchant_id) {
        //查询1条件
        $select1 = 'o.merchant_id, o.id AS order_id, o.order_no, o.create_time,
            o.store_id, s.name AS store_name, s.branch_name,s.number AS store_number,
            o.operator_id, op.name AS operator_name, op.number AS operator_number,
            o.pay_time, o.pay_channel, o.order_paymoney, o.alipay_account, o.wxpay_discount_money,
            o.coupons_money, o.discount_money, o.merchant_discount_money, o.alipay_discount_money,
            o.flag AS refund_money, o.order_status,o.flag AS order_flag';
        $from1 = 'wq_order AS o';
        $join1 = ' INNER JOIN wq_store AS s ON s.id = o.store_id';
        $join1 .= ' LEFT JOIN wq_operator AS op ON op.id = o.operator_id';
        $where1 = 'o.pay_status = '.ORDER_STATUS_PAID;//已支付订单
        $where1 .= ' AND o.order_type = '.ORDER_TYPE_CASHIER;//收银订单
        $where1 .= ' AND o.order_status != '.ORDER_STATUS_REVOKE;//已撤销
        $where1 .= ' AND o.merchant_id = '.$merchant_id;
        $where1 .= ' AND o.flag = '.FLAG_NO;
        //查询2条件
        $select2 = 'o.merchant_id, o.id AS order_id, o.order_no, r.create_time,
            o.store_id, s.name AS store_name, s.branch_name,s.number AS store_number,
            r.operator_id, op.name AS operator_name, op.number AS operator_number,
            r.refund_time, r.refund_channel, o.order_paymoney, o.alipay_account, o.wxpay_discount_money,
            o.coupons_money, o.discount_money, o.merchant_discount_money, o.alipay_discount_money,
            r.refund_money, o.order_status, o.pay_status AS order_flag';
        $from2 = 'wq_refund_record AS r';
        $join2 = ' INNER JOIN wq_order AS o ON r.order_id = o.id';
        $join2 .= ' INNER JOIN wq_store AS s ON s.id = o.store_id';
        $join2 .= ' LEFT JOIN wq_operator AS op ON op.id = r.operator_id';
        $where2 = 'r.status != '.REFUND_STATUS_FAIL;//退款失败
        $where2 .= ' AND r.type = '.REFUND_TYPE_REFUND;//退款
        $where2 .= ' AND o.order_status != '.ORDER_STATUS_REVOKE;//已撤销
        $where2 .= ' AND o.merchant_id = '.$merchant_id;
        $where2 .= ' AND r.flag = '.FLAG_NO;
        $where2 .= ' AND o.flag = '.FLAG_NO;
        //查询3条件
        $orderBy3 = 't.pay_time DESC';

        $start_time = date("Y-m-d 00:00:00");
        $end_time = date("Y-m-d 23:59:59");
        $where1 .= " AND o.pay_time >= '".$start_time."'";
        $where2 .= " AND r.refund_time >= '".$start_time."'";
        $where1 .= " AND o.pay_time <= '".$end_time."'";
        $where2 .= " AND r.refund_time <= '".$end_time."'";

        $sql1 = 'SELECT '.$select1.' FROM '.$from1.$join1.' WHERE '.$where1;
        $sql2 = 'SELECT '.$select2.' FROM '.$from2.$join2.' WHERE '.$where2;
        $sql3 = 'SELECT * FROM ( '.$sql1.' UNION ALL '.$sql2.') AS t ORDER BY '.$orderBy3;
        $list = Yii::app()->db->createCommand($sql3)->queryAll();

        //初始化数据
        $data['yesterday_new_trade_actual_money'] = 0;
        $data['yesterday_new_trade_num'] = 0;
        foreach ($list as $v){
            $pay_channel = $v['pay_channel']; //支付方式
            $order_paymoney = $v['order_paymoney']; //订单总金额
            $coupons_money = $v['coupons_money']; //优惠券优惠金额
            $discount_money = $v['discount_money']; //会员优惠
            $m_discount_money = $v['merchant_discount_money']; //商家优惠
            $refund_money = $v['refund_money']; //退款金额
            $order_flag = $v['order_flag']; //交易类型: 收/退款

            if ($order_flag == 1){//收款记录
                //优惠金额
                $discount_money_total = $coupons_money + $discount_money + $m_discount_money;
                //实收金额
                $receipt_money = $order_paymoney - $discount_money_total;

                //计算交易金额和实收金额和交易笔数
                switch ($pay_channel) {
                    case ORDER_PAY_CHANNEL_ALIPAY_SM: //支付宝扫码
                        $data['yesterday_new_trade_actual_money'] += $receipt_money;
                        $data['yesterday_new_trade_num'] += 1;
                        break;
                    case ORDER_PAY_CHANNEL_ALIPAY_TM: //支付宝条码
                        $data['yesterday_new_trade_actual_money'] += $receipt_money;
                        $data['yesterday_new_trade_num'] += 1;
                        break;
                    case ORDER_PAY_CHANNEL_WXPAY_SM: //微信扫码
                        $data['yesterday_new_trade_actual_money'] += $receipt_money;
                        $data['yesterday_new_trade_num'] += 1;
                        break;
                    case ORDER_PAY_CHANNEL_WXPAY_TM: //微信条码
                        $data['yesterday_new_trade_actual_money'] += $receipt_money;
                        $data['yesterday_new_trade_num'] += 1;
                        break;
                    case ORDER_PAY_CHANNEL_CASH: //现金支付
                        $data['yesterday_new_trade_actual_money'] += $receipt_money;
                        $data['yesterday_new_trade_num'] += 1;
                        break;
                    case ORDER_PAY_CHANNEL_UNIONPAY: //银联支付
                        $data['yesterday_new_trade_actual_money'] += $receipt_money;
                        $data['yesterday_new_trade_num'] += 1;
                        break;
                    case ORDER_PAY_CHANNEL_STORED: //储值支付
                        $data['yesterday_new_trade_actual_money'] += $receipt_money;
                        $data['yesterday_new_trade_num'] += 1;
                        break;
                    default:
                        break;
                }
            }else if ($order_flag == 2){//退款记录
                //计算交易金额和实收金额和交易笔数
                switch ($pay_channel) {
                    case ORDER_PAY_CHANNEL_ALIPAY_SM: //支付宝扫码
                        $data['yesterday_new_trade_actual_money'] -= $refund_money;
                        break;
                    case ORDER_PAY_CHANNEL_ALIPAY_TM: //支付宝条码
                        $data['yesterday_new_trade_actual_money'] -= $refund_money;
                        break;
                    case ORDER_PAY_CHANNEL_WXPAY_SM: //微信扫码
                        $data['yesterday_new_trade_actual_money'] -= $refund_money;
                        break;
                    case ORDER_PAY_CHANNEL_WXPAY_TM: //微信条码
                        $data['yesterday_new_trade_actual_money'] -= $refund_money;
                        break;
                    case ORDER_PAY_CHANNEL_CASH: //现金支付
                        $data['yesterday_new_trade_actual_money'] -= $refund_money;
                        break;
                    case ORDER_PAY_CHANNEL_UNIONPAY: //银联支付
                        $data['yesterday_new_trade_actual_money'] -= $refund_money;
                        break;
                    case ORDER_PAY_CHANNEL_STORED: //储值支付
                        $data['yesterday_new_trade_actual_money'] -= $refund_money;
                        break;
                    default:
                        break;
                }
            }
        }
        return $data;
    }

    /**
     *  计算今日粉丝数据
     */
    public function getTodayFans($data, $merchant_id) {
        $data['yesterday_new_alipayfans_num'] = 0;
        $data['yesterday_new_wechatfans_num'] = 0;
        $data['yesterday_new_member_num'] = 0;

        $date = date('Y-m-d', time());

        $users = User::model() -> findAll('merchant_id = :merchant_id AND create_time LIKE :create_time', array(':merchant_id' => $merchant_id, ':create_time' => "$date%"));

        if (!empty($users)) {
            foreach($users as $v) {
                switch($v -> type) {
                    case USER_TYPE_WANQUAN_MEMBER:
                        $data['yesterday_new_member_num'] += 1;
                        break;
                    case USER_TYPE_WECHAT_FANS:
                        if ($v -> wechat_status == WECHAT_USER_SUBSCRIBE) {
                            $data['yesterday_new_wechatfans_num'] += 1;
                        }
                        break;
                    case USER_TYPE_ALIPAY_FANS:
                        if ($v -> wechat_status == ALIPAY_USER_SUBSCRIBE) {
                            $data['yesterday_new_alipayfans_num'] += 1;
                        }
                        break;
                    default:
                        break;
                }
            }
        }

        return $data;
    }
}