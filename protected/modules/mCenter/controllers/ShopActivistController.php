<?php
/**
 * 商城订单维权
 * @author xyf
 * @date   2015/09/19
 */

class ShopActivistController extends mCenterController{
	
	/**
	 * 维权订单列表
	 */
	public function actionActivistList()
	{
		$list = array();
		
		$activistC = new ActivistC();
		$result = $activistC -> getActivistList();
		$result = json_decode($result,true);
		if($result['status'] == ERROR_NONE){
			$list = $result['data']['list'];
		}
		$this->render('activistList',array(
				'list'=>$list,
				'pages'=>$activistC->page
		));
	}
	
	/**
	 * 退款订单sku详情
	 * $order_sku_id  订单sku Id
	 * $order_status  订单状态
	 */
	public function actionActivistDetail($order_id,$order_sku_id,$order_status)
	{
		$activistC = new ActivistC();
		$list = array();
		$result = $activistC -> refundOrderSkuDetail($order_id,$order_sku_id,$order_status);
		$result = CJSON::decode($result);
		if($result['status'] == ERROR_NONE){
			$list = $result['data'];
		}
		$this->render('activistDetail',array('list'=>$list,'order_id'=>$order_id,'order_status'=>$order_status));
	}
	
	/**
	 * 商家同意退款
	 */
	public function actionAgreeRefund($order_id,$order_sku_id,$order_status)
	{
                $activistC = new ActivistC();
                $merchantId = Yii::app()->session['merchant_id'];
                //查询商户pid
                $user = new UserUC();
                $result_merchant = $user->findMerchantPid($merchantId);
                $sellerId = $result_merchant->partner;
                $seller_email=$result_merchant->seller_email;
                $key = $result_merchant->key;//安全校验码
                $wappayC = new WappayC();
                $ret = $wappayC -> wappayRefund($order_id, $order_sku_id, $order_status);
                $list = json_decode($ret,true);
                if($list['status'] == ERROR_NONE)
                {
                    $list = $list['data'];
                }
                //先判断该订单是否已经退款，已经退款就不调用支付宝接口
                if($order_status != REQUEST_ORDER_STATUS_REFUND){
                    //支付宝退款
                    if($list['pay_channel'] == ORDER_PAY_CHANNEL_ALIPAY){
                        echo("<script>parent.location.href='".Yii::app() -> createUrl('mCenter/ShopActivist/WappayUrl',array('sellerId'=>$sellerId,'trade_no'=>$list['trade_no'],'order_paymoney'=>$list['order_paymoney'],'remark'=>$list['remark'],'key'=>$key,'refund_order_no'=>$list['refund_order_no'],'seller_email'=>$seller_email))."'</script>");
                    }
                    //微信退款
                    if($list['pay_channel'] == ORDER_PAY_CHANNEL_WXPAY){
                        $wappayC ->MallRefund($order_id, $order_sku_id, $order_status);
                    }
                } else {
                    
                }

	}
        
                /**
	 * 商家拒绝退款
	 */
	public function actionRefuseRefund()
	{
		$activistC = new ActivistC();
        if(isset($_POST))
        {
            $order_id=$_POST['refuse_orderid'];
            $order_sku_id=$_POST['refuse_skuid'];
            $order_status=$_POST['refuse_status'];
            $refuse_refund_remark=$_POST['refuse_refund_remark'];
            $result = $activistC -> refuseRefund($order_id,$order_sku_id,$refuse_refund_remark);
            $result = json_decode($result,true);
            if($result['status'] == ERROR_NONE){
                $this->redirect(Yii::app()->createUrl('mCenter/shopActivist/activistDetail',array('order_id'=>$order_id,'order_sku_id'=>$order_sku_id,'order_status'=>$order_status)));
            }
        }

	}
	
	/**
	 * 商家填写退款地址
	 */
	public function actionAddRefundAddress()
	{
		$refund_address = '';
        $order_sku_id='';
        $refund_status='';
        $refund_id='';
        $refund_money=0;
		if(!empty($_POST))
        {
            $refuse_orderid=$_POST['refuse_orderid'];
            $refund_address=$_POST['refund_address'];
            $order_sku_id=$_POST['refund_skuid'];
            $refund_status=$_POST['refund_status'];
            $refund_id=$_POST['refund_refundid'];
            $refund_money=$_POST['refund_money'];
        }
		$activistC = new ActivistC();
		$result = $activistC -> addRefundAddress($refuse_orderid,$order_sku_id,$refund_id,$refund_address,$refund_status,$refund_money);
		$result = json_decode($result,true);
		if($result['status'] == ERROR_NONE){
			$this->redirect(Yii::app()->createUrl('mCenter/shopActivist/activistDetail',array('order_id'=>$refuse_orderid,'order_sku_id'=>$order_sku_id,'order_status'=>$refund_status)));
		}
	}
	
	
	/**
     * 维权订单详情
     */
    public function actionRefundDetail()
    {
        if(!empty($_GET['order_id']))
        {
            $order_id=$_GET['order_id'];
            $merchant_id = Yii::app()->session['merchant_id'];
            $activistC=new ActivistC();
            $result=json_decode($activistC->getRefundDetail($merchant_id,$order_id),true);

            $this->render('refundDetail');
        }
        else
        {
            $this->redirect('ActivistList');
        }

    }

    /**
     * 同意收货
     */
    public function actionAgreeTake()
    {
        $result=array();
        if(!empty($_GET))
        {
            $order_id=$_GET['order_id'];
            $order_sku_id=$_GET['order_sku_id'];
            $order_status=$_GET['order_status'];
            /*$order=new ActivistC();
            $result=json_decode($order->AgreeTake($order_id,$order_sku_id,$order_status),true);
            if($result['status']==ERROR_NONE)
            {
                $this->redirect(Yii::app()->createUrl('mCenter/shopActivist/activistDetail',array('order_id'=>$order_id,'order_sku_id'=>$order_sku_id,'order_status'=>$order_status)));
            }*/

            $activistC = new ActivistC();
            $merchantId = Yii::app()->session['merchant_id'];
            //查询商户pid
            $user = new UserUC();
            $result_merchant = $user->findMerchantPid($merchantId);
            $sellerId = $result_merchant->partner;
            $seller_email=$result_merchant->seller_email;
            $key = $result_merchant->key;//安全校验码
            $wappayC = new WappayC();
            $ret = $wappayC -> wappayRefund($order_id, $order_sku_id, $order_status);
            $list = json_decode($ret,true);
            if($list['status'] == ERROR_NONE)
            {
                $list = $list['data'];
            }
            //先判断该订单是否已经退款，已经退款就不调用支付宝接口
            if($order_status != REQUEST_ORDER_STATUS_REFUND){
                //支付宝退款
                if($list['pay_channel'] == ORDER_PAY_CHANNEL_ALIPAY){
                    echo("<script>parent.location.href='".Yii::app() -> createUrl('mCenter/ShopActivist/WappayUrl',array('sellerId'=>$sellerId,'trade_no'=>$list['trade_no'],'order_paymoney'=>$list['order_paymoney'],'remark'=>$list['remark'],'key'=>$key,'refund_order_no'=>$list['refund_order_no'],'seller_email'=>$seller_email))."'</script>");
                }
                //微信退款
                if($list['pay_channel'] == ORDER_PAY_CHANNEL_WXPAY){
                    $wappayC ->MallRefund($order_id, $order_sku_id, $order_status);
                }
            } else {

            }
        }
    }

    /**
     * 退款跳转页面
     */
    public function actionWappayUrl($sellerId,$trade_no,$order_paymoney,$remark,$key,$refund_order_no,$seller_email)
    {
        $wappayC=new WappayC();
        $wappayC->ToWappayRefund($sellerId,$trade_no,$order_paymoney,$remark,$key,$refund_order_no,$seller_email);
    }

    /**
     *拒绝收货
     */
    public function actionRefuseTake()
    {
        $result = array();
        if (!empty($_GET)) {
            $order_id=$_GET['order_id'];
            $order_sku_id = $_GET['order_sku_id'];
            $order_status = $_GET['order_status'];
            $order=new ActivistC();
            $result=json_decode($order->RefuseTake($order_id,$order_sku_id,$order_status),true);
            if($result['status']==ERROR_NONE)
            {
                $this->redirect(Yii::app()->createUrl('mCenter/shopActivist/activistDetail',array('order_id'=>$order_id,'order_sku_id'=>$order_sku_id,'order_status'=>$order_status)));
            }
        }
    }



    /*public function actionShopTest()
    {
        $order=new ActivistC();
        $order->ShopTest();
    }*/
}