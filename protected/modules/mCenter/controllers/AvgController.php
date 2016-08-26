<?php

class AvgController extends mCenterController
{
    public function actionDown()
    {
        $down = new ExcelC();
        $down = $down->Dump();
    }
    /**
     * 订单明细导出excel,订单状态 正常 已退款 已部分退款 已付款 及退款数据
     * 10月
     */
    public function actionMainXi()
    {
        set_time_limit(0);
        $mx = new ExcelC(); 
        $r = $mx ->MianXiOrder();        
    }
    
    /**
     * 订单明细导出excel,订单状态 正常 已退款 已部分退款 已付款 及退款数据
     * 10月
     * 按支付宝，微信，银联的订单
     */
    public function actionAlipayWechant()
    {
        set_time_limit(0);
        $mx = new ExcelC(); 
        $r = $mx ->AlipayWechat();        
    }
}

