<?php

class PayController extends Controller
{
    public $layout='';
    /**
     * 支付宝
     */
    public function actionAlipay()
    {
        $orderNo     = isset($_GET['orderNo']) ? $_GET['orderNo'] : '';
        $productName = isset($_GET['productName']) ? $_GET['productName'].'条套餐' : '';   
        $synNotifyUrl = ALIPAY_DX_SYNNOTIFY;//服务器同步通知页面路径
        $asyNotifyUrl = ALIPAY_DX_ASYNOTIFY;//页面跳转异步通知页面路径
        $Ali         = new AlipayC();
        $pay         = $Ali->ToAlipay($orderNo,$productName,$synNotifyUrl,$asyNotifyUrl);
    }
}
