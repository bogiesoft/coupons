<?php

class RunController extends Controller{
    
    public function init(){
        
    }
    /**
     * 生成二维码
     */
//     public function actionCreateQrcode() {
//     	//引入phpqrcode库文件
//     	Yii::import('application.extensions.qrcode.*');
//     	include('phpqrcode.php');
    	
//     	// 二维码数据
//     	$api = new WxpaySC();
//     	$ret = $api->prePayUrl('201605061118');
//     	$result = json_decode($ret, true);
//     	if ($result['status'] != ERROR_NONE) {
//     		exit('二维码生成失败');
//     	}
//     	$url = $result['url'];
// //     	echo $url;exit;
    	
//     	//输入二维码到浏览器
//     	QRcode::png($url);
//     }
    
//     public function actionNativeNotify() {
//     	$xml = file_get_contents("php://input");
//     	//xml解析
//     	libxml_disable_entity_loader(true);
//     	$arr = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    	
//     	$appid = $arr['appid']; //appid
//     	$mchid = $arr['mch_id']; //mchid
//     	$product_id = $arr['product_id']; //商品id
//     	$is_subscribe = $arr['is_subscribe']; //是否关注
//     	$open_id = $arr['openid']; //用户标识
//     	$sub_open_id = isset($arr['sub_openid']) ? $arr['sub_openid'] : ''; //用户子标识
    	
//     	if ($product_id != '201605061118') {
//     		exit();
//     	}
    	
//     	//处理结果
//     	$flag = false;
//     	$msg = 'OK';
    	
//     	//回调函数
//     	$callback = function () use($product_id){
//     		$array = array('flag' => false, 'msg' => '');
//     		$wxpay = new WxpaySC();
//     		$ret = $wxpay->qrCodePay1();
//     		$result = json_decode($ret, true);
//     		if ($result['status'] == ERROR_NONE) {
//     			$prepay_id = $result['prepay_id'];
//     			$array['flag'] = true;
//     			$array['prepay_id'] = $prepay_id;
//     		}
//     		return $array;
//     	};
    	
    	
//     	$api = new WxpaySC();
//     	$api->wxpayNativeNotify($product_id, $callback);
//     }
    
    /**
     * 微信支付结果异步通知
     */
//     public function actionPayNotify() {
//         Yii::log('debug_wechat_demo','warning');
//     	$xml = file_get_contents("php://input");
//     	//xml解析
//     	libxml_disable_entity_loader(true);
//     	$arr = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    	
//     	$out_trade_no = $arr['out_trade_no']; //获取订单号
//     	$result_code = $arr['result_code']; //业务结果码
//     	$trade_no = $arr['transaction_id']; //微信支付订单号
//     	$open_id = $arr['openid']; //用户标识
//     	$sub_open_id = isset($arr['sub_openid']) ? $arr['sub_openid'] : ''; //用户子标识
//     	$pay_time = isset($arr['time_end']) ? $arr['time_end'] : date('Y-m-d H:i:s');
//     	Yii::log('debug_wechat_demo_'.$open_id,'warning');
//     	$api = new WxpaySC();
//     	$re = json_decode($api->wxpaySampleHandle($open_id));
//     	Yii::log('debug_wechat_demo_'.$re -> errMsg,'warning');
    	
//     	//会场通过扫二维码成功支付5元的用户，如果该用户已关注且未发送红包，则发红包给该用户
//     	$userC = new UserC();
//     	$re = json_decode($userC -> checkOpenid($open_id));
//     	if($re -> status == ERROR_NONE){
//     	    if($re -> data == 1){
//     	        $packet = new Packet();
//     	        //调用发红包接口
//     	       $re = $packet -> wxpacket($open_id);
//     	       if($re -> result_code == "SUCCESS"){
//     	           $userC -> changeIfget($open_id);
//     	       }
//     	    }
//     	}
//     }

    
    
    /**
     * 将粉丝信息拉取到会员中
     */
   public function actionFansToUser(){
        set_time_limit(0);
        if(!isset($_GET['verify']) || empty($_GET['verify'])){
            echo 'fail';
            exit;
        }else {
            if($_GET['verify'] != 'qwert12345%%%@QQDqFGTd'){
                echo 'fail';
                exit;
            }
        }
       
       	//找到需要补充信息的会员
       	$user = User::model() -> findAll('type=:type and wechat_id IS NOT NULL and avatar IS NULL and flag=:flag',array(
       			':type' => 1,
       			':flag' => 1
       	));
       	//依次找到会员对应的粉丝信息
       	foreach ($user as $k => $v){
       		$fans = User::model() ->find('wechat_id=:wechat_id',array(
       				'wechat_id'=>$v['wechat_id']
       		));
    
       		//找到对应粉丝，判断要填写的字段是否可赋值
       		if (!empty($fans)){
       			if (!empty($fans['avatar']) && empty($v->avatar)){
       				$v->avatar = $fans['avatar'];
       			}
       			if (!empty($fans['nickname']) && empty($v->nickname)){
       				$v->nickname = $fans['nickname'];
       			}
       			if (!empty($fans['sex']) && empty($v->sex)){
       				$v->sex = $fans['sex'];
       			}
       			if (!empty($fans['country']) && empty($v->country)){
       				$v->country = $fans['country'];
       			}
       			if (!empty($fans['province']) && empty($v->province)){
       				$v->province = $fans['province'];
       			}
       			if (!empty($fans['province_code']) && empty($v->province_code)){
       				$v->province_code = $fans['province_code'];
       			}
       			if (!empty($fans['wechat_nickname']) && empty($v->wechat_nickname)){
       				$v->wechat_nickname = $fans['wechat_nickname'];
       			}
       			if (!empty($fans['wechat_sex']) && empty($v->wechat_sex)){
       				$v->wechat_sex = $fans['wechat_sex'];
       			}
       			if (!empty($fans['wechat_country']) && empty($v->wechat_country)){
       				$v->wechat_country = $fans['wechat_country'];
       			}
       			if (!empty($fans['wechat_province']) && empty($v->wechat_province)){
       				$v->wechat_province = $fans['wechat_province'];
       			}
       			if (!empty($fans['wechat_city']) && empty($v->wechat_city)){
       				$v->wechat_city = $fans['wechat_city'];
       			}
       			if (!empty($fans['wechat_language']) && empty($v->wechat_language)){
       				$v->wechat_language = $fans['wechat_language'];
       			}
       			if (!empty($fans['wechat_headimgurl']) && empty($v->wechat_headimgurl)){
       				$v->wechat_headimgurl = $fans['wechat_headimgurl'];
       			}
                if(!empty($fans['wechat_status'])) {
                    $v->wechat_status = $fans['wechat_status'];
                }
                if(!empty($fans['wechat_subscribe_time'])) {
                    $v->wechat_subscribe_time = $fans['wechat_subscribe_time'];
                }
                if(!empty($fans['wechat_cancel_subscribe_time'])) {
                    $v->wechat_cancel_subscribe_time = $fans['wechat_cancel_subscribe_time'];
                }
       			$v->update();
       		}
       	}
   }
   
   
   /**
    * 优惠券统计
    */
   public function actionCouponStatistics(){
       if (isset($_GET['Yesterday']) && $_GET['Yesterday'] == 'CouponStatistics160625') {
           $db = Yii::app()->db;
   
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
                    WHERE UNIX_TIMESTAMP(a.create_time) < " . strtotime(date('Ymd', time())) . "
                    GROUP BY a.coupons_id
                ) b ON a.id = b.coupons_id
                LEFT JOIN (
                    SELECT
                        b.coupons_id, COUNT(b.coupons_id) AS use_num
                    FROM wq_coupon_validate_record a
                    LEFT JOIN wq_user_coupons b ON a.user_coupon_id = b.id
                    WHERE UNIX_TIMESTAMP(a.validate_time) < " . strtotime(date('Ymd', time())) . "
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
                    AND UNIX_TIMESTAMP(b.pay_time) < " . strtotime(date('Ymd', time())) . "
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
                    AND UNIX_TIMESTAMP(b.pay_time) < " . strtotime(date('Ymd', time())) . "
                    AND UNIX_TIMESTAMP(b.pay_time) >= " . strtotime(date('Ymd', strtotime('-1 days'))) . "
                    GROUP BY a.coupons_id
                ) e ON a.id = e.coupons_id
                LEFT JOIN (
                    SELECT
                        a.coupon_id, COUNT(a.coupon_id) AS total_browse_person_num
                    FROM wq_coupon_ip a
                    WHERE UNIX_TIMESTAMP(a.create_time) < " . strtotime(date('Ymd', time())) . "
                    GROUP BY a.coupon_id
                ) f ON a.id = f.coupon_id
                LEFT JOIN (
                    SELECT
                        a.coupon_id, COUNT(a.coupon_id) AS new_browse_person_num
                    FROM wq_coupon_ip a
                    WHERE UNIX_TIMESTAMP(a.create_time) < " . strtotime(date('Ymd', time())) . "
                    AND UNIX_TIMESTAMP(a.create_time) >= " . strtotime(date('Ymd', strtotime('-1 days'))) . "
                    GROUP BY a.coupon_id
                ) g ON a.id = g.coupon_id
                LEFT JOIN(
                    SELECT
                        a.coupons_id, COUNT(a.coupons_id) AS new_get_num
                    FROM wq_user_coupons a
                    LEFT JOIN wq_coupons b ON a.coupons_id = b.id
                    WHERE UNIX_TIMESTAMP(a.create_time) < " . strtotime(date('Ymd', time())) . "
                    AND UNIX_TIMESTAMP(a.create_time) >= " . strtotime(date('Ymd', strtotime('-1 days'))) . "
                    GROUP BY a.coupons_id
                ) h ON a.id = h.coupons_id
                LEFT JOIN (
                    SELECT
                        b.coupons_id, COUNT(b.coupons_id) AS new_use_num
                    FROM wq_coupon_validate_record a
                    LEFT JOIN wq_user_coupons b ON a.user_coupon_id = b.id
                    WHERE UNIX_TIMESTAMP(a.validate_time) < " . strtotime(date('Ymd', time())) . "
                    AND UNIX_TIMESTAMP(a.validate_time) >= " . strtotime(date('Ymd', strtotime('-1 days'))) . "
                    GROUP BY b.coupons_id
                ) i ON a.id = i.coupons_id
                LEFT JOIN (
                    SELECT
                        a.coupons_id, a.user_id
                    FROM wq_user_coupons a
                    WHERE UNIX_TIMESTAMP(a.create_time) < " . strtotime(date('Ymd', time())) . "
                    AND UNIX_TIMESTAMP(a.create_time) >= " . strtotime(date('Ymd', strtotime('-1 days'))) . "
                    GROUP BY a.coupons_id, a.user_id
                ) j ON a.id = j.coupons_id
                LEFT JOIN (
                    SELECT
                        b.coupons_id, b.user_id
                    FROM wq_coupon_validate_record a
                    LEFT JOIN wq_user_coupons b ON a.user_coupon_id = b.id
                    WHERE UNIX_TIMESTAMP(a.validate_time) < " . strtotime(date('Ymd', time())) . "
                    AND UNIX_TIMESTAMP(a.validate_time) >= " . strtotime(date('Ymd', strtotime('-1 days'))) . "
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
                   ':date' => date('Y-m-d 00:00:00', strtotime('-1 days'))
               ));
   
               if (empty($res)) {
                   $CouponStatisticsModel = new CouponStatistics();
   
                   //保存总数据
                   $CouponStatisticsModel->coupon_id = $v['id'];
                   $CouponStatisticsModel->merchant_id = $v['merchant_id'];
                   $CouponStatisticsModel->date = date('Y-m-d', strtotime('-1 days')) . ' 00:00:00';
                   //计算新增浏览量
                   //查询前天是否添加过记录
                   $res_couponstatistics = CouponStatistics::model()->find('coupon_id = :coupon_id and date = :date', array(
                       ':coupon_id' => $v['id'],
                       ':date' => date('Y-m-d 00:00:00', strtotime('-2 days'))
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
       } else {
       }
   }

   /**
    * 营销活动发券计划任务
    */
   public function actionMarketingSendCoupon(){
       //查找未开始、进行中的活动
       $criteria = new CDbCriteria;
	   $criteria->addCondition('status = :status1');
       $criteria->params[':status1'] = MARKETING_ACTIVITY_STATUS_NOSTART;
	   $criteria->addCondition('status = :status2', 'OR');
       $criteria->params[':status2'] = MARKETING_ACTIVITY_STATUS_START;
	   $criteria->addCondition('flag = :flag', 'AND');
       $criteria->params[':flag'] = FLAG_NO;

       $criteria->addCondition('time_type = :time_type', 'AND');
       $criteria->params[':time_type'] = MARKETING_ACTIVITY_TIME_TYPE_PERIODICITY;

       $criteria->addCondition('type = :type', 'AND');
       $criteria->params[':type'] = MARKETING_ACTIVITY_TYPE_BASICS_JZMA;

       $all_marketing = MarketingActivity::model()->findAll($criteria);

       $flag = false;
       foreach ($all_marketing as $v) {
           //判断是否符合发送条件
           if ($v['status'] == MARKETING_ACTIVITY_STATUS_NOSTART) { //未开始状态，判断开始时间是否和今天一致，一致则符合发送条件，且修改状态为进行中
               if (date('Ymd', strtotime($v['start_time'])) == date('Ymd', time())) {
                   $flag = true;
                   $v['status'] = MARKETING_ACTIVITY_STATUS_START;
               }
           } elseif ($v['status'] == MARKETING_ACTIVITY_STATUS_START) { //进行状态，判断结束时间是否和今天一致，一致则修改状态为已结束
               $flag = true;
               if (!empty($v['end_time'])) { //定义周期
                   if (date('Ymd', strtotime($v['end_time'])) == date('Ymd', time())) {
                       $v['status'] = MARKETING_ACTIVITY_STATUS_END;
                   }
               }
           }

           if ($flag) { //符合发送条件
               //查找分组下所有的用户open_id
               $all_group_user = Group::model()->findAll('group_id = :group_id and flag = :flag', array(
                   ':group_id' => $v['group_id'],
                   ':flag' => FLAG_NO
               ));

               foreach ($all_group_user as $v2) {
                   if (!empty($v2['wechat_id'])) {
                       $openid_list[] = $v2['wechat_id'];
                   }
               }

               //查找分组的支付宝标签
               $group_info = UserGroup::model()->findByPk($v['group_id']);
               $alipay_label_id = $group_info['label_id'];

               //查询优惠券信息
               $coupon_info = Coupons::model()->findByPk($v['coupon_id']);

               //获取access_token
               $merchantC = new MobileMerchantC();
               $merchant = $merchantC->getMerchantInfo($v['merchant_id']);
               $access_token = WechatWebAuth::getTokenByMerchant($merchant);

               //判断活动的发送渠道
               if ($v['send_channel'] == MARKETING_ACTIVITY_SEND_CHANNEL_WECHAT) { //仅微信
                   //判断优惠券在微信是否审核通过
                   if ($coupon_info['status'] == WX_CHECK_PASS) { //已通过，发送原生卡券消息
                       $message['type'] = 'wxcard';
                       $message['info'] = array(
                           'card_id' => $coupon_info['card_id']
                       );

                       $message = urldecode(json_encode($message));
                       $wechat_mass_send = new WechatMassSend();
                       $wechat_send_res = $wechat_mass_send->sendMessage($message, $openid_list, $access_token);
                   } else { //未通过，发送图文消息
                       /*$message['type'] = 'wqcard';
                       $message['info'] = array(
                           'material' => array(
                               'img_path' => WECHAT_UPLOAD_IMG_PATH . $v['image_text_imageurl'],
                               'title' => $v['name'],
                               'content_source_url' => WAP_DOMAIN . '/coupon_' . $coupon_info['code'] . '.html',
                               'content' => $v['image_text_title']
                           )
                       );*/

                       $url = WAP_DOMAIN . '/coupon_' . $coupon_info['code'] . '.html';
                       $a_text = urlencode('点击领取优惠券');
                       $type = 'text';
                       $msg = array('touser' => $openid_list);
                       $msg[$type] = array(
                           'content' => urlencode($v['image_text_title']) . "，<a href='" . $url . "'>" . $a_text . "</a>"
                       );
                       $msg['msgtype'] = $type;
                       $wechat_mass_send = new WechatMassSend();
                       //$result = $wechat_mass_send->massSendPreview($access_token, urldecode(json_encode($msg)));
                       $wechat_send_res = $wechat_mass_send->massSendGroud($access_token, urldecode(json_encode($msg)));
                   }

                   //Yii::log('wechat_send_errcode:' . $wechat_send_res['errcode'], 'error');
                   //Yii::log('wechat_send_errmsg:' . $wechat_send_res['errmsg'], 'error');
               } elseif ($v['send_channel'] == MARKETING_ACTIVITY_SEND_CHANNEL_ALIPAY) { //仅支付宝
                   $message['type'] = 'wqcard';
                   //保存素材获取id
                   $alipay = new AliServiceWindowMessageSend();

                   //素材信息
                   $array = array(array(
                       'title' => $v['name'],
                       'cover_img' => $v['image_text_imageurl'],
                       'content' => $v['image_text_title'],
                       'link_content' => WAP_DOMAIN . '/coupon_' . $coupon_info['code'] . '.html'
                   ));
                   $material_id = $alipay->saveMaterial($array);

                   //调用支付宝接口分组发送图文消息
                   $alipay_send_res = $alipay->messageLabelSend('', $material_id, $alipay_label_id);
                   //Yii::log('alipay_send_errcode:' . $alipay_send_res['errcode'], 'error');
                   //Yii::log('alipay_send_errmsg:' . $alipay_send_res['errmsg'], 'error');
               } elseif ($v['send_channel'] == MARKETING_ACTIVITY_SEND_CHANNEL_BOTH) { //所有渠道
                   /*$message['type'] = 'wqcard';
                   $message['info'] = array(
                       'material' => array(
                           'img_path' => WECHAT_UPLOAD_IMG_PATH . $v['image_text_imageurl'],
                           'title' => $v['name'],
                           'content_source_url' => WAP_DOMAIN . '/coupon_' . $coupon_info['code'] . '.html',
                           'content' => $v['image_text_title']
                       )
                   );

                   $message = json_encode($message);*/

                   //调用微信图文接口
                   /*$wechat_mass_send = new WechatMassSend();
                   $wechat_send_res = $wechat_mass_send->sendMessage($message, $openid_list, $access_token);*/
                   $url = WAP_DOMAIN . '/coupon_' . $coupon_info['code'] . '.html';
                   $a_text = urlencode('点击领取优惠券');
                   $type = 'text';
                   $msg = array(
                       'touser' => $openid_list
                   );
                   $msg[$type] = array(
                       'content' => urlencode($v['image_text_title']) . "，<a href='" . $url . "'>" . $a_text . "</a>"
                   );
                   $msg['msgtype'] = $type;
                   $wechat_mass_send = new WechatMassSend();
                   $wechat_send_res = $wechat_mass_send->massSendGroud($access_token, urldecode(json_encode($msg)));

                   //Yii::log('wechat_send_errcode:' . $wechat_send_res['errcode'], 'error');
                   //Yii::log('wechat_send_errmsg:' . $wechat_send_res['errmsg'], 'error');

                   //保存素材获取id
                   $alipay = new AliServiceWindowMessageSend();

                   //素材信息
                   $array = array(array(
                       'title' => $v['name'],
                       'cover_img' => $v['image_text_imageurl'],
                       'content' => $v['image_text_title'],
                       'link_content' => WAP_DOMAIN . '/coupon_' . $coupon_info['code'] . '.html'
                   ));
                   $material_id = $alipay->saveMaterial($array);

                   //调用支付宝接口分组发送图文消息
                   $alipay_send_res = $alipay->messageLabelSend('', $material_id, $alipay_label_id);

                   //Yii::log('alipay_send_errcode:' . $alipay_send_res['errcode'], 'error');
                   //Yii::log('alipay_send_errmsg:' . $alipay_send_res['errmsg'], 'error');
               }

               //改变活动状态
               if ($v->save()) {
                   echo 'success';
               }
           }
       }
   }

    /**
     *精准营销  一次性活动  定时推送 定时在每天的下午4点05分发送
     */
    public function actionMarketingJzmaSendCoupon()
    {
        set_time_limit(0);

        if (isset($_GET['validate_char']) && $_GET['validate_char'] == 'yhnsk5ds84dskjh5sad511dhgb8g1c') {
            $criteria = new CDbCriteria;
            $criteria->addCondition('type = :type', 'AND');
            $criteria->params[':type'] = MARKETING_ACTIVITY_TYPE_BASICS_JZMA;
            $criteria->addCondition('time_type = :time_type', 'AND');
            $criteria->params[':time_type'] = MARKETING_ACTIVITY_TIME_TYPE_ONETIME;
            $criteria->addCondition('time_send_type = :time_send_type', 'AND');
            $criteria->params[':time_send_type'] = MARKETING_ACTIVITY_SEND_TYPE_TIMING;

            //活动状态 不等于 已结束 已停止
            $criteria->addCondition('status != :status1 and status != :status2');
            $criteria->params[':status1'] = MARKETING_ACTIVITY_STATUS_END;
            $criteria->params[':status2'] = MARKETING_ACTIVITY_STATUS_STOP;
            //没有被删除的活动
            $criteria->addCondition('flag = :flag', 'AND');
            $criteria->params[':flag'] = FLAG_NO;
            //定时时间小于当前时间
            $criteria->addCondition('UNIX_TIMESTAMP(start_time) < UNIX_TIMESTAMP(now())', 'AND');
            $all_marketing = MarketingActivity::model()->findAll($criteria);
            if (!empty($all_marketing)) {
                foreach ($all_marketing as $v) {
                    $data['merchant_id'] = $v['merchant_id'];
                    $data['group_id'] = $v['group_id'];
                    $data['coupon_id'] = $v['coupon_id'];
                    $data['send_channel'] = $v['send_channel'];
                    $data['image_text_title'] = $v['image_text_title'];
                    $data['name'] = $v['name'];
                    $data['image_text_imageurl'] = $v['image_text_imageurl'];
                    $data['time_type'] = $v['time_type'];
                    $data['time_send_type'] = $v['time_send_type'];
                    $data['marketing_id'] = $v['id'];

                    $marketingClass = new MarketingClass();
                    $marketingClass->sendChannelMsg($data);

                    $v->status = MARKETING_ACTIVITY_STATUS_END;
                    $v->update();
                }
            }
        }
    }

    /**
     * 精准营销 周期活动 定义周期
     */
    public function actionMarketingJzmaPeriodicitySendCoupon() {
        set_time_limit(0);
        if (isset($_GET['validate_char']) && $_GET['validate_char'] == 'yjod5s1dsa7fds95d3sads514fsd9p') {
            $criteria = new CDbCriteria;
            $criteria->addCondition('type = :type', 'AND');
            $criteria->params[':type'] = MARKETING_ACTIVITY_TYPE_BASICS_JZMA;
            $criteria->addCondition('time_type = :time_type', 'AND');
            $criteria->params[':time_type'] = MARKETING_ACTIVITY_TIME_TYPE_PERIODICITY;
            $criteria->addCondition('time_send_type = :time_send_type', 'AND');
            $criteria->params[':time_send_type'] = MARKETING_ACTIVITY_SEND_TYPE_CRONTAB;

            //活动状态 不等于 已结束 已停止
            $criteria->addCondition('status != :status1 and status != :status2');
            $criteria->params[':status1'] = MARKETING_ACTIVITY_STATUS_END;
            $criteria->params[':status2'] = MARKETING_ACTIVITY_STATUS_STOP;
            //没有被删除的活动
            $criteria->addCondition('flag = :flag', 'AND');
            $criteria->params[':flag'] = FLAG_NO;
            //当前时间 大于等于  开始时间 小于等于 结束时间
            $criteria->addCondition('UNIX_TIMESTAMP(start_time) <= UNIX_TIMESTAMP(now()) and UNIX_TIMESTAMP(now()) <= UNIX_TIMESTAMP(end_time) ', 'AND');
            $all_marketing = MarketingActivity::model()->findAll($criteria);
            if (!empty($all_marketing)) {
                foreach ($all_marketing as $v) {
                    $data['merchant_id'] = $v['merchant_id'];
                    $data['group_id'] = $v['group_id'];
                    $data['coupon_id'] = $v['coupon_id'];
                    $data['send_channel'] = $v['send_channel'];
                    $data['image_text_title'] = $v['image_text_title'];
                    $data['name'] = $v['name'];
                    $data['image_text_imageurl'] = $v['image_text_imageurl'];
                    $data['time_type'] = $v['time_type'];
                    $data['time_send_type'] = $v['time_send_type'];
                    $data['marketing_id'] = $v['id'];

                    $marketingClass = new MarketingClass();
                    $marketingClass->sendChannelMsg($data);

                    //当前时间等于结束时间就修改
                    if (date('Y-m-d', strtotime($v->end_time)) == date('Y-m-d', time())) {
                        $v->status = MARKETING_ACTIVITY_STATUS_END;
                        $v->update();
                    }
                }
            }
        }
    }

    /**
     * 精准营销 周期活动 长期
     */
    public function actionMarketingJzmaLongSendCoupon() {
        set_time_limit(0);

        if (isset($_GET['validate_char']) && $_GET['validate_char'] == 'bndshkdslajkl8564sdadsa5dsa46l') {
            $criteria = new CDbCriteria;
            $criteria->addCondition('type = :type', 'AND');
            $criteria->params[':type'] = MARKETING_ACTIVITY_TYPE_BASICS_JZMA;
            $criteria->addCondition('time_type = :time_type', 'AND');
            $criteria->params[':time_type'] = MARKETING_ACTIVITY_TIME_TYPE_PERIODICITY;
            $criteria->addCondition('time_send_type = :time_send_type', 'AND');
            $criteria->params[':time_send_type'] = MARKETING_ACTIVITY_SEND_TYPE_LONG;

            //活动状态 不等于 已结束 已停止
            $criteria->addCondition('status != :status1 and status != :status2');
            $criteria->params[':status1'] = MARKETING_ACTIVITY_STATUS_END;
            $criteria->params[':status2'] = MARKETING_ACTIVITY_STATUS_STOP;
            //没有被删除的活动
            $criteria->addCondition('flag = :flag', 'AND');
            $criteria->params[':flag'] = FLAG_NO;

            $all_marketing = MarketingActivity::model()->findAll($criteria);
            if (!empty($all_marketing)) {
                foreach ($all_marketing as $v) {
                    $data['merchant_id'] = $v['merchant_id'];
                    $data['group_id'] = $v['group_id'];
                    $data['coupon_id'] = $v['coupon_id'];
                    $data['send_channel'] = $v['send_channel'];
                    $data['image_text_title'] = $v['image_text_title'];
                    $data['name'] = $v['name'];
                    $data['image_text_imageurl'] = $v['image_text_imageurl'];
                    $data['time_type'] = $v['time_type'];
                    $data['time_send_type'] = $v['time_send_type'];
                    $data['marketing_id'] = $v['id'];

                    $marketingClass = new MarketingClass();
                    $marketingClass->sendChannelMsg($data);
                }
            }
        }
    }

    /**
     * 更新动态分组
     */
    public function actionUpdateDynamicGroup() {
        set_time_limit(0);
        $filterUserClass = new FilterUserClass();
        $filterUserClass->GroupTask();
    }

    /**
     * 更新分组人数
     */
    public function actionUpdateGroupNum()
    {
        $all_group = UserGroup::model()->findAll();

        foreach ($all_group as $v) {
            $user_num = Group::model()->count('group_id = :group_id', array(
                ':group_id' => $v['id']
            ));

            if (empty($v['type'])) {
                $v->type = GROUP_TYPE_CUSTOM;
            }

            $v->num = $user_num;
            $v->save();
        }
        echo 'success';
    }


    /**
     * 积分活动报表
     */
    public function actionPointActivityStatistics(){
        if (isset($_GET['Yesterday']) && $_GET['Yesterday'] == 'PointActivityStatistics160804') {
            $db = Yii::app()->db;

            $start_time = strtotime(date('Ymd', time())); //开始时间
            $end_time = strtotime(date('Ymd', strtotime('-1 days'))); //结束时间

            //查询前一天总数据
            $sql = "
                SELECT
                    a.id, a.merchant_id, a.pv AS total_pv,
                    IFNULL(b.browse_person_num, 0) AS browse_person_num, 
                    IFNULL(c.exchange_num, 0) AS exchange_num, 
                    IFNULL(d.exchange_person_num, 0) AS exchange_person_num,
                    IFNULL(e.use_num, 0) AS use_num,
                    IFNULL(COUNT(f.user_id), 0) AS use_person_num 
                FROM wq_point_activity a
                LEFT JOIN (
                    SELECT
                        a.activity_id, COUNT(a.activity_id) AS browse_person_num
                    FROM wq_point_activity_ip a
                    WHERE UNIX_TIMESTAMP(a.create_time) < " . $start_time . "
                    AND UNIX_TIMESTAMP(a.create_time) >= " . $end_time . "
                    GROUP BY a.activity_id
                ) b ON a.id = b.activity_id
                LEFT JOIN (
                    SELECT
                        a.activity_id, COUNT(a.activity_id) AS exchange_num
                    FROM wq_user_coupons a
                    WHERE a.activity_id IS NOT NULL
                    AND a.channel_type = " . POINT_DETAIL_ACTIVITY_TYPE_JFHQ . "
                    AND UNIX_TIMESTAMP(a.create_time) < " . $start_time . "
                    AND UNIX_TIMESTAMP(a.create_time) >= " . $end_time . "
                    GROUP BY a.activity_id
                ) c ON a.id = c.activity_id
                LEFT JOIN (
                    SELECT
                        a.activity_id, COUNT(a.activity_id) AS exchange_person_num
                    FROM wq_point_activity_ip a
                    WHERE UNIX_TIMESTAMP(a.create_time) < " . $start_time . "
                    AND UNIX_TIMESTAMP(a.create_time) >= " . $end_time . "
                    GROUP BY a.activity_id
                ) d ON a.id = d.activity_id
                LEFT JOIN (
                    SELECT
                        b.activity_id, COUNT(b.activity_id) AS use_num
                    FROM wq_coupon_validate_record a
                    LEFT JOIN wq_user_coupons b ON a.user_coupon_id = b.id
                    WHERE b.activity_id IS NOT NULL
                    AND b.channel_type = " . POINT_DETAIL_ACTIVITY_TYPE_JFHQ . "
                    AND UNIX_TIMESTAMP(a.validate_time) < " . $start_time . "
                    AND UNIX_TIMESTAMP(a.validate_time) >= " . $end_time . "
                    GROUP BY b.activity_id
                ) e ON a.id = e.activity_id
                LEFT JOIN (
                    SELECT
                        b.activity_id, b.user_id
                    FROM wq_coupon_validate_record a
                    LEFT JOIN wq_user_coupons b ON a.user_coupon_id = b.id
                    WHERE b.activity_id IS NOT NULL
                    AND b.channel_type = " . POINT_DETAIL_ACTIVITY_TYPE_JFHQ . "
                    AND UNIX_TIMESTAMP(a.validate_time) < " . $start_time . "
                    AND UNIX_TIMESTAMP(a.validate_time) >= " . $end_time . "
                    GROUP BY b.activity_id, b.user_id
                ) f ON a.id = f.activity_id
                WHERE a.flag = " . FLAG_NO . "
                GROUP BY a.id";

            $command = $db->createCommand($sql);
            $result = $command->queryAll();

            foreach ($result as $v) {
                //查询该积分活动该天是否添加过记录
                $res = PointActivityStatistics::model()->find('point_activity_id = :point_activity_id and date = :date', array(
                    ':point_activity_id' => $v['id'],
                    ':date' => date('Y-m-d 00:00:00', strtotime('-1 days'))
                ));

                if (empty($res)) {
                    $PointActivityStatisticsModel = new PointActivityStatistics();

                    //保存总数据
                    $PointActivityStatisticsModel->merchant_id = $v['merchant_id'];
                    $PointActivityStatisticsModel->point_activity_id = $v['id'];
                    $PointActivityStatisticsModel->date = date('Y-m-d', strtotime('-1 days')) . ' 00:00:00';
                    //计算新增浏览量
                    //查询前天是否添加过记录
                    $res_pointActivityStatistics = PointActivityStatistics::model()->find('point_activity_id = :point_activity_id and date = :date', array(
                        ':point_activity_id' => $v['activity_id'],
                        ':date' => date('Y-m-d 00:00:00', strtotime('-1 days'))
                    ));
                    if (!empty($res_pointActivityStatistics)) {
                        //前天添加过记录，昨天新增的浏览量等于昨天的总浏览量减去前天的浏览量
                        $PointActivityStatisticsModel->browse_num = $v['total_pv'] - $res_pointActivityStatistics->total_browse_num;
                    } else {
                        //没有添加过记录，昨天新增的浏览量等于昨天的总浏览量
                        $PointActivityStatisticsModel->browse_num = $v['total_pv'];
                    }
                    $PointActivityStatisticsModel->total_browse_num = $v['total_pv'];
                    $PointActivityStatisticsModel->browse_person_num = $v['browse_person_num'];
                    $PointActivityStatisticsModel->exchange_num = $v['exchange_num'];
                    $PointActivityStatisticsModel->exchange_person_num = $v['exchange_person_num'];
                    $PointActivityStatisticsModel->use_num = $v['use_num'];
                    $PointActivityStatisticsModel->use_person_num = $v['use_person_num'];

                    !empty($v['total_pv']) ? $v['total_pv'] : 0;
                    $PointActivityStatisticsModel->exchange_rate = !empty($v['total_pv']) ? round($v['exchange_num'] / $v['total_pv'], 2) : 0;
                    $PointActivityStatisticsModel->use_rate = !empty($v['exchange_num']) ? round($v['use_num'] / $v['exchange_num'], 2) : 0;
                    $PointActivityStatisticsModel->create_time = date('Y-m-d H:i:s', time());

                    $PointActivityStatisticsModel->save();
                }
            }
            echo 'success';
        } else {
        }
    }


    /**
     * 基础营销--新会员 定时活动  7.29
     */
    public function actionMarketingXhymaSendCoupon() {
        set_time_limit(0);

        $criteria = new CDbCriteria;
        $criteria->addCondition('type = :type', 'AND');
        $criteria->params[':type'] = MARKETING_ACTIVITY_TYPE_BASICS_XHYMA;
        $criteria->addCondition('time_type = :time_type', 'AND');
        $criteria->params[':time_type'] = MARKETING_ACTIVITY_TIME_TYPE_ONETIME;
        $criteria->addCondition('time_send_type = :time_send_type', 'AND');
        $criteria->params[':time_send_type'] = MARKETING_ACTIVITY_SEND_TYPE_TIMING;

        //活动状态 不等于 已结束 已停止
        $criteria->addCondition('status != :status1');
        $criteria->params[':status1'] = MARKETING_ACTIVITY_STATUS_END;
        $criteria->addCondition('status != :status2', 'OR');
        $criteria->params[':status2'] = MARKETING_ACTIVITY_STATUS_STOP;
        //没有被删除的活动
        $criteria->addCondition('flag = :flag', 'AND');
        $criteria->params[':flag'] = FLAG_NO;
        //定时时间小于当前时间
        $criteria->addCondition('UNIX_TIMESTAMP(start_time) < UNIX_TIMESTAMP(now())', 'AND');
        $all_marketing = MarketingActivity::model()->findAll($criteria);
        if (!empty($all_marketing)) {
            foreach ($all_marketing as $v) {
                $data['merchant_id'] = $v['merchant_id'];
                $data['coupon_id'] = $v['coupon_id'];
                $data['send_channel'] = $v['send_channel'];
                $data['image_text_title'] = $v['image_text_title'];
                $data['name'] = $v['name'];
                $data['image_text_imageurl'] = $v['image_text_imageurl'];
                $data['time_type'] = $v['time_type'];
                $data['time_send_type'] = $v['time_send_type'];
                $data['marketing_id'] = $v['marketing_id'];
                $data['marketing_type'] = MARKETING_ACTIVITY_TYPE_BASICS_XHYMA;

                $marketingClass = new MarketingClass();
                $marketingClass->sendChannelMsgElse($data);

                $v->status = MARKETING_ACTIVITY_STATUS_END;
                $v->update();
            }
        }

    }

    /**
     * 基础营销--流失客户 定时活动  7.29
     */
    public function actionMarketingLskhSendCoupon() {
        set_time_limit(0);

        $criteria = new CDbCriteria;
        $criteria->addCondition('type = :type', 'AND');
        $criteria->params[':type'] = MARKETING_ACTIVITY_TYPE_BASICS_LSKHMA;
        $criteria->addCondition('time_type = :time_type', 'AND');
        $criteria->params[':time_type'] = MARKETING_ACTIVITY_TIME_TYPE_ONETIME;
        $criteria->addCondition('time_send_type = :time_send_type', 'AND');
        $criteria->params[':time_send_type'] = MARKETING_ACTIVITY_SEND_TYPE_TIMING;

        //活动状态 不等于 已结束 已停止
        $criteria->addCondition('status != :status1');
        $criteria->params[':status1'] = MARKETING_ACTIVITY_STATUS_END;
        $criteria->addCondition('status != :status2', 'OR');
        $criteria->params[':status2'] = MARKETING_ACTIVITY_STATUS_STOP;
        //没有被删除的活动
        $criteria->addCondition('flag = :flag', 'AND');
        $criteria->params[':flag'] = FLAG_NO;
        //定时时间小于当前时间
        $criteria->addCondition('UNIX_TIMESTAMP(start_time) < UNIX_TIMESTAMP(now())', 'AND');
        $all_marketing = MarketingActivity::model()->findAll($criteria);
        if (!empty($all_marketing)) {
            foreach ($all_marketing as $v) {
                $data['merchant_id'] = $v['merchant_id'];
                $data['coupon_id'] = $v['coupon_id'];
                $data['send_channel'] = $v['send_channel'];
                $data['image_text_title'] = $v['image_text_title'];
                $data['name'] = $v['name'];
                $data['image_text_imageurl'] = $v['image_text_imageurl'];
                $data['time_type'] = $v['time_type'];
                $data['time_send_type'] = $v['time_send_type'];
                $data['marketing_id'] = $v['marketing_id'];
                $data['marketing_type'] = MARKETING_ACTIVITY_TYPE_BASICS_LSKHMA;

                $marketingClass = new MarketingClass();
                $marketingClass->sendChannelMsgElse($data);

                $v->status = MARKETING_ACTIVITY_STATUS_END;
                $v->update();
            }
        }

    }

    /**
     * 基础营销--老客户 定时活动  7.29
     */
    public function actionMarketingLhySendCoupon() {
        set_time_limit(0);

        $criteria = new CDbCriteria;
        $criteria->addCondition('type = :type', 'AND');
        $criteria->params[':type'] = MARKETING_ACTIVITY_TYPE_BASICS_LHYMA;
        $criteria->addCondition('time_type = :time_type', 'AND');
        $criteria->params[':time_type'] = MARKETING_ACTIVITY_TIME_TYPE_ONETIME;
        $criteria->addCondition('time_send_type = :time_send_type', 'AND');
        $criteria->params[':time_send_type'] = MARKETING_ACTIVITY_SEND_TYPE_TIMING;

        //活动状态 不等于 已结束 已停止
        $criteria->addCondition('status != :status1');
        $criteria->params[':status1'] = MARKETING_ACTIVITY_STATUS_END;
        $criteria->addCondition('status != :status2', 'OR');
        $criteria->params[':status2'] = MARKETING_ACTIVITY_STATUS_STOP;
        //没有被删除的活动
        $criteria->addCondition('flag = :flag', 'AND');
        $criteria->params[':flag'] = FLAG_NO;
        //定时时间小于当前时间
        $criteria->addCondition('UNIX_TIMESTAMP(start_time) < UNIX_TIMESTAMP(now())', 'AND');
        $all_marketing = MarketingActivity::model()->findAll($criteria);
        if (!empty($all_marketing)) {
            foreach ($all_marketing as $v) {
                $data['merchant_id'] = $v['merchant_id'];
                $data['coupon_id'] = $v['coupon_id'];
                $data['send_channel'] = $v['send_channel'];
                $data['image_text_title'] = $v['image_text_title'];
                $data['name'] = $v['name'];
                $data['image_text_imageurl'] = $v['image_text_imageurl'];
                $data['time_type'] = $v['time_type'];
                $data['time_send_type'] = $v['time_send_type'];
                $data['marketing_id'] = $v['marketing_id'];
                $data['marketing_type']= MARKETING_ACTIVITY_TYPE_BASICS_LHYMA;

                $marketingClass = new MarketingClass();
                $marketingClass->sendChannelMsgElse($data);

                $v->status = MARKETING_ACTIVITY_STATUS_END;
                $v->update();
            }
        }

    }
    /**
     * 基础营销--活跃客户 定时活动  7.29
     */
    public function actionMarketingHykhSendCoupon() {
        set_time_limit(0);

        $criteria = new CDbCriteria;
        $criteria->addCondition('type = :type', 'AND');
        $criteria->params[':type'] = MARKETING_ACTIVITY_TYPE_BASICS_HYKHMA;
        $criteria->addCondition('time_type = :time_type', 'AND');
        $criteria->params[':time_type'] = MARKETING_ACTIVITY_TIME_TYPE_ONETIME;
        $criteria->addCondition('time_send_type = :time_send_type', 'AND');
        $criteria->params[':time_send_type'] = MARKETING_ACTIVITY_SEND_TYPE_TIMING;

        //活动状态 不等于 已结束 已停止
        $criteria->addCondition('status != :status1');
        $criteria->params[':status1'] = MARKETING_ACTIVITY_STATUS_END;
        $criteria->addCondition('status != :status2', 'OR');
        $criteria->params[':status2'] = MARKETING_ACTIVITY_STATUS_STOP;
        //没有被删除的活动
        $criteria->addCondition('flag = :flag', 'AND');
        $criteria->params[':flag'] = FLAG_NO;
        //定时时间小于当前时间
        $criteria->addCondition('UNIX_TIMESTAMP(start_time) < UNIX_TIMESTAMP(now())', 'AND');
        $all_marketing = MarketingActivity::model()->findAll($criteria);
        if (!empty($all_marketing)) {
            foreach ($all_marketing as $v) {
                $data['merchant_id'] = $v['merchant_id'];
                $data['coupon_id'] = $v['coupon_id'];
                $data['send_channel'] = $v['send_channel'];
                $data['image_text_title'] = $v['image_text_title'];
                $data['name'] = $v['name'];
                $data['image_text_imageurl'] = $v['image_text_imageurl'];
                $data['time_type'] = $v['time_type'];
                $data['time_send_type'] = $v['time_send_type'];
                $data['marketing_id'] = $v['marketing_id'];
                $data['marketing_type']= MARKETING_ACTIVITY_TYPE_BASICS_HYKHMA;

                $marketingClass = new MarketingClass();
                $marketingClass->sendChannelMsgElse($data);

                $v->status = MARKETING_ACTIVITY_STATUS_END;
                $v->update();
            }
        }

    }

}