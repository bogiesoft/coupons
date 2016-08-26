<?php

class TianShiController extends mCenterController
{
    /**
     * 天时门票列表
     */
    public function actionTicketList($merchant_id)
    {
        $ret = new TianShiApi();
        $page     = '';
        $size     = '';
        $g_relate = '';
        $cate_id  = '';
        $zone     = '';
        $item_id  = '';
        $key_word = '';
        $list = $ret ->TicketList($page,$size,$g_relate,$cate_id,$zone,$item_id,$key_word); 
        $list = json_decode($list,true);
        $shopC = new ShopC();
        $group = json_decode($shopC -> addGroup($merchant_id),true);
        if($group['status'] == ERROR_NONE){
            $groupId = $group['id'];
        }
        foreach ($list['list'] as $k => $v){            
            $ts_product_id = $v['id'];
            $name = $v['title'];
            $amount = $v['amount'];
            $original_price = $v['original_price'];
            $image = $v['image'];           
            $nett_price = isset($v['nett_price']) ? $v['nett_price'] : '';
            $nett_price2 = isset($v['nett_price2']) ? $v['nett_price2'] : '';
            $shop = $shopC ->AddTianShiProduct($ts_product_id,$name,$amount,$original_price,$image,$nett_price,$nett_price2,$merchant_id,$groupId);                      
        }
        $ret = json_decode($shop,true);
        if($ret['status'] == ERROR_NONE){
            $this->redirect(Yii::app()->createUrl('mCenter/shopProduct/productList'));
        }  
    }
    
    /**
     * 订单列表
     */
    public function actionOrderList()
    {
        $ret = new TianShiApi();
        $page      = '';
        $size      = '';
        $item_id   = '';
        $begin     = '';
        $end       = '';
        $g_relate  = '';
        $orders_id = '';
        $r = $ret ->OrderList($page,$size,$item_id,$begin,$end,$g_relate,$orders_id);
    }
    
    /**
     * 创建订单
     */
    public function actionCreateOrder()
    {
        $ret = new TianShiApi();
        $item_id         = '143497';//必填 要购买的票ID
        $name            = '汪';//【必填】 购票人名称
        $mobile          = '13738485394';//必填 购票人手机号(成功后短信将发送门票码号到该手机号)
        $is_pay          = '';
        $orders_id       = '';
        $size            = '';
        $start_date      = '';
        $start_date_auto = '';
        $price_type      = '';
        $remark          = '';
        $price           = '';
        $back_cash       = '';
        $id_number       = '';
        $r = $ret ->CreateOrder($item_id,$name,$mobile,$is_pay,$orders_id,$size,$start_date,$start_date_auto,$price_type,$remark,$price,$back_cash,$id_number);
    }
    
    /**
     * 订单退款
     */
    public function actionRefund()
    {
        $ret = new TianShiApi();
        $orders_id = '8908259';//必填 要退票的订单号  
        $size      = '';//退票数,缺省退票所有未使用票数         
        $r = $ret ->Refund($orders_id,$size);
    }
    
    /**
     * 修改订单接口(重发接口)
     */
    public function actionModifyOrder()
    {
        $ret = new TianShiApi();
        $orders_id   = '8908259';//必填 本系统订单ID或第三方请求下单接口时传的订单ID  
        $is_pay      = '';
        $sms_send    = '';
        $name        = '';
        $mobile      = '';
        $re_code     = '';
        $start_time  = '';
        $expire_time = '';
        $id_number   = '';
        $r = $ret ->ModifyOrder($orders_id,$is_pay,$sms_send,$name,$mobile,$re_code,$start_time,$expire_time,$id_number);
    }
    
    /**
     * 码号查询
     */
    public function actionOrdersQuery()
    {
        $ret = new TianShiApi();
        $code = '';//必填  码号
        $r = $ret ->OrdersQuery($code);
    }
    
    /*
     * 验证通知
     */
    public function actionValidate()
    {
        $ret = new TianShiApi();
        $supplier_id       = '';//必填  验证景区ID
        $code              = '';//必填 验证码号
        $amount            = '';//必填 验证数量
        $my_orders_id      = '';//必填 本平台订单ID
        $another_orders_id = '';//第三方平台订单ID，下单时有提交orders_id时才会返回
        $r = $ret ->Validate($supplier_id, $code, $amount, $my_orders_id, $another_orders_id);
    }
    
    /**
     * @param $key
     * @param $xmlData
     * @return string
     * 获取接口返回XML元素的值
     */
    private function getXmlVal ($key, $xmlData)
    {
        $result   = '';
        $document = new DOMDocument();
        $document->loadXML($xmlData);
        if (!empty($document->getElementsByTagName("{$key}")->item(0)->nodeValue)) {
            $result = $document->getElementsByTagName("{$key}")->item(0)->nodeValue;
        }
        return $result;
    }    
}

