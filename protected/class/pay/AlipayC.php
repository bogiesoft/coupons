<?php
/*
 * 时间：2015-6-27 创建人：王乃晓
 */
include_once (dirname ( __FILE__ ) . '/../mainClass.php');
class AlipayC extends mainClass {
	// 支付宝
	/**
	 * $orderNo 订单号
	 * $money 金额
	 * $productName 套餐条数名称
	 * $synNotifyUrl 同步通知页面路径
	 * $asyNotifyUrl 异步通知页面路径
	 */
	public function ToAlipay($orderNo, $productName, $synNotifyUrl, $asyNotifyUrl) {
		Yii::import ( 'application.extensions.alipay.*' );
		require_once ("lib/alipay_submit.class.php");
		require_once ("alipay.config.php");
		header ( "content-Type: text/html; charset=Utf-8" );
		
		// 支付类型
		$payment_type = "1";
		// 必填，不能修改
		// 服务器异步通知页面路径
		$notify_url = $asyNotifyUrl;
		// 需http://格式的完整路径，不能加?id=123这类自定义参数
		// 页面跳转同步通知页面路径
		$return_url = $synNotifyUrl;
		// 需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/
		
		// 商户订单号
		$out_trade_no = $orderNo;
		// 商户网站订单系统中唯一订单号，必填
		
		// 订单名称
		$subject = $productName;
		// 必填
		
		if (substr ( $orderNo, 0, 2 ) == 'WQ') {
			$order = GjOrder::model ()->find ( 'order_no=:order_no and pay_status =:pay_status and order_status=:order_status', array (
					':order_no' => $orderNo,
					':pay_status' => GJORDER_PAY_STATUS_NUPAID,
					':order_status' => GJORDER_STATUS_NORMAL 
			) );
			if ($order) {
				// 付款金额
				$total_fee = $order->pay_money;
				// 必填
			}
		} elseif (substr ( $orderNo, 0, 2 ) == 'DX') {
			$messageOrder = MessageOrder::model ()->find ( 'order_no=:order_no and pay_status =:pay_status', array (
					':order_no' => $orderNo,
					':pay_status' => ORDER_STATUS_UNPAID 
			) );
			if ($messageOrder) {
				// 付款金额
				$total_fee = $messageOrder->pay_money;
				// 必填
			}
		} elseif (substr ( $orderNo, 0, 2 ) == 'AO') {
			$order = AgentOrder::model ()->find ( 'order_no=:order_no and pay_status =:pay_status', array (
					':order_no' => $orderNo,
					':pay_status' => GJORDER_PAY_STATUS_NUPAID
			) );
			if ($order) {
				// 付款金额
				$total_fee = $order->pay_money;
				// 必填
			}
		}elseif (substr ( $orderNo, 0, 2 ) == 'SO'){
		    $storeOrder = StoreOrder::model() -> find('order_no = :order_no and pay_status =:pay_status',array(
		        ':order_no' => $orderNo,
		        ':pay_status' => ORDER_STATUS_UNPAID
		    ));
		    if($storeOrder){
		        // 付款金额
		        $total_fee = $storeOrder->order_money;
		        // 必填
		    }
		}
		
		// 订单描述
		
		$body = $productName;
		// 商品展示地址
		$show_url = '';
		// 需以http://开头的完整路径，例如：http://www.商户网址.com/myorder.html
		
		// 防钓鱼时间戳
		$anti_phishing_key = "";
		// 若要使用请调用类文件submit中的query_timestamp函数
		
		// 客户端的IP地址
		$exter_invoke_ip = "";
		// 非局域网的外网IP地址，如：221.0.0.1
		
		/**
		 * *********************************************************
		 */
		
		// 构造要请求的参数数组，无需改动
		$parameter = array (
				"service" => "create_direct_pay_by_user",
				"partner" => trim ( $alipay_config ['partner'] ),
				"seller_email" => trim ( $alipay_config ['seller_email'] ),
				"payment_type" => $payment_type,
				"notify_url" => $notify_url,
				"return_url" => $return_url,
				"out_trade_no" => $out_trade_no,
				"subject" => $subject,
				"total_fee" => $total_fee,
				"body" => $body,
				"show_url" => $show_url,
				"anti_phishing_key" => $anti_phishing_key,
				"exter_invoke_ip" => $exter_invoke_ip,
				"_input_charset" => trim ( strtolower ( $alipay_config ['input_charset'] ) ) 
		);
		
		// 建立请求
		$alipaySubmit = new AlipaySubmit ( $alipay_config );
		$html_text = $alipaySubmit->buildRequestForm ( $parameter, "get", "确认" );
		echo $html_text;
	}

    // 支付宝
    /**
     * $orderNo 订单号
     * $money 金额
     * $productName 套餐条数名称
     * $synNotifyUrl 同步通知页面路径
     * $asyNotifyUrl 异步通知页面路径
     */
    public function ToAgentAlipay($orderNo, $productName, $synNotifyUrl, $asyNotifyUrl) {
        Yii::import ( 'application.extensions.alipay.*' );
        require_once ("lib/alipay_submit.class.php");
        require_once ("alipay.config.php");
        header ( "content-Type: text/html; charset=Utf-8" );

        // 支付类型
        $payment_type = "1";
        // 必填，不能修改
        // 服务器异步通知页面路径
        $notify_url = $asyNotifyUrl;
        // 需http://格式的完整路径，不能加?id=123这类自定义参数
        // 页面跳转同步通知页面路径
        $return_url = $synNotifyUrl;
        // 需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/

        // 商户订单号
        $out_trade_no = $orderNo;
        // 商户网站订单系统中唯一订单号，必填

        // 订单名称
        $subject = $productName;
        // 必填

//        $total_fee = 0.01;
        if (substr ( $orderNo, 0, 2 ) == 'WQ') {
            $order = GjOrder::model ()->find ( 'order_no=:order_no and pay_status =:pay_status and order_status=:order_status', array (
                ':order_no' => $orderNo,
                ':pay_status' => GJORDER_PAY_STATUS_NUPAID,
                ':order_status' => GJORDER_STATUS_NORMAL
            ) );
            if ($order) {
                // 付款金额
                $total_fee = $order->pay_money;
                // 必填
            }
        } elseif (substr ( $orderNo, 0, 2 ) == 'DX') {
            $messageOrder = MessageOrder::model ()->find ( 'order_no=:order_no and pay_status =:pay_status', array (
                ':order_no' => $orderNo,
                ':pay_status' => ORDER_STATUS_UNPAID
            ) );
            if ($messageOrder) {
                // 付款金额
                $total_fee = $messageOrder->pay_money;
                // 必填
            }
        } elseif (substr ( $orderNo, 0, 2 ) == 'AO') {
            $order = AgentOrder::model ()->find ( 'order_no=:order_no and pay_status =:pay_status', array (
                ':order_no' => $orderNo,
                ':pay_status' => FX_PAY_STATUS_NUPAID
            ) );
            $order2 = Agent::model() -> find('pay_status = :pay_status', array(
                ':pay_status' => AGENT_PAY_STATUS_PAID
            ));
            if ($order && $order2) {
                // 付款金额
                $total_fee = $order->pay_money;
                // 必填
            }
        }

        // 订单描述

        $body = $productName;
        // 商品展示地址
        $show_url = '';
        // 需以http://开头的完整路径，例如：http://www.商户网址.com/myorder.html

        // 防钓鱼时间戳
        $anti_phishing_key = "";
        // 若要使用请调用类文件submit中的query_timestamp函数

        // 客户端的IP地址
        $exter_invoke_ip = "";
        // 非局域网的外网IP地址，如：221.0.0.1

        /**
         * *********************************************************
         */

        // 构造要请求的参数数组，无需改动
        $parameter = array (
            "service" => "create_direct_pay_by_user",
            "partner" => trim ( $alipay_config ['partner'] ),
            "seller_email" => trim ( $alipay_config ['seller_email'] ),
            "payment_type" => $payment_type,
            "notify_url" => $notify_url,
            "return_url" => $return_url,
            "out_trade_no" => $out_trade_no,
            "subject" => $subject,
            "total_fee" => $total_fee,
            "body" => $body,
            "show_url" => $show_url,
            "anti_phishing_key" => $anti_phishing_key,
            "exter_invoke_ip" => $exter_invoke_ip,
            "_input_charset" => trim ( strtolower ( $alipay_config ['input_charset'] ) )
        );

        // 建立请求
        $alipaySubmit = new AlipaySubmit ( $alipay_config );
        $html_text = $alipaySubmit->buildRequestForm ( $parameter, "get", "确认" );
        echo $html_text;
    }


    // 短信服务器异步通知//页面跳转同步通知
	/**
	 * out_trade_no 订单号
	 * trade_no 支付交易号
	 * merchantId 商户id
	 * $pay_channel 支付渠道
	 */
	public function DuanXinSearch($out_trade_no, $trade_no, $merchantId, $pay_channel) {
		// 查找短信订单
		$messageOrder = MessageOrder::model ()->find ( 'order_no=:order_no', array (
				':order_no' => $out_trade_no 
		) );
		$transaction = Yii::app ()->db->beginTransaction (); // 开启事务
		try {
			// 如果短信订单为未付款就修改状态
			if ($messageOrder->pay_status == ORDER_STATUS_UNPAID) {
				
				$messageOrder->pay_status = ORDER_STATUS_PAID;
				$messageOrder->pay_time = new CDbExpression ( 'now()' );
				$messageOrder->trade_no = $trade_no;
				$messageOrder->pay_channel = $pay_channel;
				// 短信订单状态修改成功后给商户增加短信条数
				if ($messageOrder->update ()) {
					$merchant = Merchant::model ()->find ( 'id=:id and flag =:flag', array (
							':id' => $merchantId,
							':flag' => FLAG_NO 
					) );
					$merchant->msg_num = $merchant->msg_num + $messageOrder->message_num;
					if ($merchant->update ()) {
						$transaction->commit ();
						$result ['status'] = ERROR_NONE;
						return json_encode ( $result );
					} else {
						$transaction->rollBack ();
						$result ['status'] = ERROR_SAVE_FAIL;
						$result ['errMsg'] = '商户短信条数增加失败';
						return json_encode ( $result );
					}
				} else {
					$transaction->rollBack ();
					$result ['status'] = ERROR_SAVE_FAIL;
					$result ['errMsg'] = '短信订单修改失败';
					return json_encode ( $result );
				}
			}
		} catch ( Exception $e ) {
			$transaction->rollBack ();
		}
	}
	
	/**
	 * 门店升级续费支付
	 * @param unknown $order_no
	 * @throws Exception
	 * @return string
	 */
	public function payForStoreOrder($order_no) {
		$result = array();
		try {
			//订单信息查询
			$order = StoreOrder::model() -> find('order_no = :order_no and flag =:flag',array(
		        ':order_no' => $order_no,
		        ':flag' => FLAG_NO
		    ));
			if (empty($order)) {
				$result['status'] = ERROR_NO_DATA;
				throw new Exception('订单数据不存在');
			}
			if ($order['pay_status'] == ORDER_STATUS_PAID) {
				throw new Exception('订单已付款，请勿重复支付');
			}
			$money = $order['order_money'];
			
			//支付参数
			$appid = $this->getAppidForStoreOrder($order['merchant_id']);
			$isv = '2088701036418655';
			$subject = '门店升级续费';
			$notify_url = GJ_DOMAIN.'/mCenter/AlipayNotify/NotifyForStoreOrder2';
			
			$api = new AlipayApi();
			$response = $api->qrCodeApiV2($order_no, $money, 0, $appid, null, null, null, $isv, $subject, $notify_url);
			
			//返回请求结果
			$result_code = $api->getVal($response, 'result_code'); //结果码
			$out_trade_no = $api->getVal($response, 'out_trade_no'); //订单号
			$pic_url = $api->getVal($response, 'small_pic_url'); //图片地址
			$detail_error_des = $api->getVal($response, 'detail_error_des'); //详细报错信息
			$qr_code = $api->getVal($response, 'qr_code'); //二维码
			$error = $api->getVal($response, 'error'); //其他错误
			
			//成功
			if ($result_code == _SUCCESS || $result_code == ALIPAY_V2_CODE_SUCCESS) {
				//返回二维码图片地址
				$result['status'] = ERROR_NONE;
				$result['data'] = $pic_url;
				$result['code'] = $qr_code;
			}
			//失败或未知
			if ($result_code == _FAIL ||
				$result_code == _UNKNOWN ||
				$result_code == ALIPAY_V2_CODE_FAIL ||
				$result_code == ALIPAY_V2_CODE_UNKNOWN ) {
				$result['status'] = ERROR_REQUEST_FAIL;
				throw new Exception($detail_error_des);
			}
			//其他接口错误
			if (!empty($error)) {
				$result['status'] = ERROR_REQUEST_FAIL;
				throw new Exception($error);
			}
		} catch (Exception $e) {
			$result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
			$result['errMsg'] = $e->getMessage(); //错误信息
		}
		
		return json_encode($result);
	}
	
	/**
	 * 支付宝查询
	 * @param unknown $order_no
	 * @throws Exception
	 * @return string
	 */
	public function alipaySearch($order_no) {
		$result = array();
		try {
			//订单信息查询
			$order = StoreOrder::model() -> find('order_no = :order_no and flag =:flag',array(
					':order_no' => $order_no,
					':flag' => FLAG_NO
			));
			if (empty($order)) {
				$result['status'] = ERROR_NO_DATA;
				throw new Exception('订单数据不存在');
			}
			
			//支付参数
			$appid = $this->getAppidForStoreOrder($order['merchant_id']);
				
			$api = new AlipayApi();
			$response = $api->searchApiV2($order_no, $appid);
				
			//返回请求结果
			$result_code = $api->getVal($response, 'result_code');
			$trade_status = $api->getVal($response, 'trade_status'); //交易状态
			$detail_error_code = $api->getVal($response, 'detail_error_code');//详细错误码
			$detail_error_des = $api->getVal($response, 'detail_error_des');//详细错误描述
			$trade_no = $api->getVal($response, 'trade_no'); //支付流水号
			$buyer_user_id = $api->getVal($response, 'buyer_user_id');//买家支付宝用户号
			$send_pay_date = $api->getVal($response, 'send_pay_date');//本次交易打款到卖家账户的时间
			$buyer_logon_id = $api->getVal($response, 'buyer_logon_id');//买家支付宝账号
			$error = $api->getVal($response, 'error'); //其他错误
			$fund_bill_list = $api->getVal($response, 'fund_bill_list'); //交易资金明细信息
				
			//成功
			if ($result_code == _SUCCESS || $result_code == ALIPAY_V2_CODE_SUCCESS) {
				if ($order['pay_status'] == ORDER_STATUS_UNPAID && $trade_status == SEARCH_TRADE_SUCCESS) {
					$storeC = new StoreC();
					$ret = $storeC->editStoreOrderAndStoreEdition($order_no, $trade_no);
					$res = json_decode($ret, true);
					if ($res['status'] != ERROR_NONE) {
						throw new Exception($res['errMsg']);
					}
				}
				
				$result['status'] = ERROR_NONE;
				$result['order_id'] = $order['id'];
				$result['trade_no'] = $trade_no;
				$result['trade_status'] = $trade_status;
				$result['buyer_id'] = $buyer_user_id;
				$result['pay_time'] = $send_pay_date;
				$result['alipay_account'] = $buyer_logon_id;
			}
			//失败或未知
			if ($result_code == _FAIL ||
				$result_code == _UNKNOWN ||
				$result_code == ALIPAY_V2_CODE_FAIL ||
				$result_code == ALIPAY_V2_CODE_UNKNOWN ) {
				if ($detail_error_code == 'TRADE_NOT_EXIST' || $detail_error_code == 'ACQ.TRADE_NOT_EXIST') {
					$result['status'] = ERROR_NONE;
					$result['order_id'] = $order['id'];
				}else {
					$result['status'] = ERROR_REQUEST_FAIL;
					throw new Exception($detail_error_des);
				}
			}
			//其他接口错误
			if (!empty($error)) {
				$result['status'] = ERROR_REQUEST_FAIL;
				throw new Exception($error);
			}
		} catch (Exception $e) {
			$result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
			$result['errMsg'] = $e->getMessage(); //错误信息
		}
		
		return json_encode($result);
	}
	
	/**
	 * 支付宝异步通知验证
	 * @param unknown $order_no
	 * @throws Exception
	 * @return string
	 */
	public function alipayVerifyNotify($order_no) {
		$result = array();
		try {
			//订单信息查询
			$order = StoreOrder::model() -> find('order_no = :order_no and flag =:flag',array(
					':order_no' => $order_no,
					':flag' => FLAG_NO
			));
			if (empty($order)) {
				$result['status'] = ERROR_NO_DATA;
				throw new Exception('订单数据不存在');
			}
			
			//支付参数
			$appid = $this->getAppidForStoreOrder($order['merchant_id']);
			
			$api = new AlipayApi();
			$response = $api->verifyNotifyV2($_POST['seller_id'], $_POST['notify_id']);
			if (!$response) {
				$result['status'] = ERROR_REQUEST_FAIL;
				throw new Exception('请求验证不通过');
			}
			
			$result['status'] = ERROR_NONE;
			$result['pay_status'] = $order['pay_status'];
			$result['errMsg'] = '';
		} catch (Exception $e) {
			$result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
			$result['errMsg'] = $e->getMessage(); //错误信息
		}
		
		return json_encode($result);
	}
	
	/**
	 * 获取对应收款账户信息
	 * @param unknown $merchant_id
	 * @throws Exception
	 * @return string
	 */
	private function getAppidForStoreOrder($merchant_id) {
		$appid = '';
		//查询商户信息，根据商户所属agent设置对应的收款账号
		$merchant = Merchant::model()->findByPk($merchant_id);
		if (empty($merchant)) {
			throw new Exception('该续费升级的商户不存在');
		}
		$agent_id = $merchant['agent_id'];
		//支付参数
		if ($agent_id == '118') {
			$appid = '2016062101537663';
		}else {
			$appid = '2015050800064427';
		}
		
		return $appid;
	}
	
}

