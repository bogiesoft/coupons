<?php

class NotifyController extends mCenterController
{
    //服务器异步通知页面路径
    public function actionAsyNotify() 
    {
        Yii::import('application.extensions.alipay.*');
        require_once("lib/alipay_notify.class.php");
        require_once("alipay.config.php");
        $alipayNotify = new AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyReturn();
        if($verify_result) 
        {
            //商户订单号
            $out_trade_no = $_POST['out_trade_no'];
            //支付宝交易号
            $trade_no = $_POST['trade_no'];
            //交易状态
            $trade_status = $_POST['trade_status'];
            if($_POST['trade_status'] == 'TRADE_FINISHED' || $_POST['trade_status'] == 'TRADE_SUCCESS') 
            {
                $merchantId = Yii::app()->session['merchant_id'];
                $duanXin = new AlipayC();
                $pay_channel = ORDER_PAY_CHANNEL_ALIPAY;
                $model   = $duanXin->DuanXinSearch($out_trade_no,$trade_no,$merchantId,$pay_channel); 
                $note = json_decode($model,true);
                if($note['status'] == ERROR_NONE)
                {
                    
                }
            } else {
                echo "success";		//请不要修改或删除
            }                
        } else {
            echo "验证失败";
        }
    }
    
    /*
     * 页面跳转同步通知页面路径
     */
    public function actionSynNotify() 
    {
        Yii::import('application.extensions.alipay.*');
        require_once("lib/alipay_notify.class.php");
        require_once("alipay.config.php");
        //计算得出通知验证结果
        $alipayNotify  = new AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyReturn();
        if($verify_result) 
        {//验证成功
            //商户订单号
            $out_trade_no = $_GET['out_trade_no'];
            //支付宝交易号
            $trade_no = $_GET['trade_no'];
            //交易状态
            $trade_status = $_GET['trade_status'];

            if($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') 
            {
                $merchantId = Yii::app()->session['merchant_id'];
                $duanXin = new AlipayC();
                $pay_channel = ORDER_PAY_CHANNEL_ALIPAY;
                $result   = $duanXin->DuanXinSearch($out_trade_no,$trade_no,$merchantId,$pay_channel);
               	$note = json_decode($result,true);
                if($note['status'] == ERROR_NONE)
                {
                    $this->redirect(Yii::app() -> createUrl('mCenter/Index/Duanxin'));
                }
            } else{
            	$this->redirect(Yii::app() -> createUrl('mCenter/Index/Duanxin'));
            }
        } else {
            //验证失败
            echo "fail";
        }
    }
    
    /**
     * 商城支付宝退款
     */
    public function actionNotifyUrl()
    {
        Yii::import('application.extensions.wappay.*');
        require_once("alipay.config.php");
        require_once("lib/alipay_notify.class.php");
        $merchantId = Yii::app()->session['merchant_id'];
        //查询商户pid
        $user = new UserUC();
        $result_merchant = $user->findMerchantPid($merchantId);
        $alipay_config['partner']=$result_merchant->partner;
        $alipay_config['key']=$result_merchant->key;
        //计算得出通知验证结果
        $alipayNotify = new AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyNotify();
        if($verify_result) {//验证成功 
            //批次号 	
            $batch_no = $_POST['batch_no'];
            //批量退款数据中转账成功的笔数 	
            $success_num = $_POST['success_num'];
            //批量退款数据中的详细信息
            $result_details = $_POST['result_details'];
            $trade_no = explode('^', $result_details);
            $wappay = new WappayC();
            $wappay ->wappayNotifyUrl($trade_no[0]);
            echo "success";		//请不要修改或删除                  
        } else {
            //验证失败
            echo "fail";            
        }
    }
}

