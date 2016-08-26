<?php
/*
 * 支付宝支付通知
 */
class AlipayNotifyController extends mCenterController{
    
    /**
     * 重写init方法， 不执行父类的 未登录就提示登录程序
     */
    public function init() {
    
    }
    
    //支付宝异步通知 用于门店升级续费
    public function actionNotifyForStoreOrder(){
        Yii::import('application.extensions.alipay.*');
        require_once("lib/alipay_notify.class.php");
        require_once("alipay.config.php");
        $alipayNotify = new AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyNotify();
        Yii::log('订单状态事件接受','warning');
        if($verify_result){
            //商户订单号
            $out_trade_no = $_POST['out_trade_no'];
            //支付宝交易号
            $trade_no = $_POST['trade_no'];
            //交易状态
            $trade_status = $_POST['trade_status'];
            if($_POST['trade_status'] == 'TRADE_FINISHED' || $_POST['trade_status'] == 'TRADE_SUCCESS'){
                $storeC = new StoreC();
                $result   = json_decode($storeC -> editStoreOrderAndStoreEdition($out_trade_no, $trade_no));
                if($result -> status == ERROR_NONE){
                    Yii::log($out_trade_no.'订单状态修改成功','warning');
                }else{
                    Yii::log($result -> errMsg.'订单状态修改成功','warning');
                }
            } else {
                echo "success";		//请不要修改或删除
            }
        } else {
            Yii::log('验证失败'.$verify_result,'warning');
        }
    }
    
    /**
     * 支付宝异步通知 用于门店升级续费（当面付版）
     * @throws Exception
     */
    public function actionNotifyForStoreOrder2() {
    	header("content-Type: text/html; charset=Utf-8");
    	
    	//商户订单号
    	$out_trade_no = $_POST['out_trade_no'];
    	//支付宝交易号
    	$trade_no = $_POST['trade_no'];
    	//交易状态
    	$trade_status = $_POST['trade_status'];
    	//买家支付宝邮箱
    	$buyer_email = isset($_POST['buyer_email']) ? $_POST['buyer_email'] : '';
    	//买家支付宝账号
    	$buyer_logon_id = isset($_POST['buyer_logon_id']) ? $_POST['buyer_logon_id'] : '';
    	//买家支付宝用户号
    	$buyer_user_id = isset($_POST['buyer_id']) ? $_POST['buyer_id'] : '';
    	
    	$api = new AlipayC();
    	$ret = $api->alipayVerifyNotify($out_trade_no);
    	$result = json_decode($ret, true);
    	if ($result['status'] == ERROR_NONE) { //验证成功
    		$pay_status = isset($result['pay_status']) ? $result['pay_status'] : '';
    		if (($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') && $pay_status == ORDER_STATUS_UNPAID) { //支付宝返回已完成且订单为未支付状态时则修改订单支付状态
    			$storeC = new StoreC();
    			$ret = $storeC->editStoreOrderAndStoreEdition($out_trade_no, $trade_no);
    			$result = json_decode($ret, true);
    			if ($result['status'] != ERROR_NONE) {
    				echo 'fail';
    				exit();
    			}else {
    			    $merchant_id = $result['merchant_id'];
    			    $res = json_decode($storeC -> editStoreOrderAfterPay($merchant_id));
    			    if($res -> status != ERROR_NONE){
    			        echo 'fail';
    			        exit();
    			    }
    			}
    		}
    		echo 'success'; //请不要修改或删除
    	}else { //验证失败
    		echo 'fail';
    	}
    }
    
}