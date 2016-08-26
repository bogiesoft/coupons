<?php
//支付宝优惠券测试代码
class AliCouponsController extends mCenterController
{
	function actionTest(){
		//echo phpinfo();exit;
		$aliCoupons = new AliCoupons();
		//$aliCoupons ->createCoupons();
		//$aliCoupons ->updateCoupons();
		$aliCoupons->sendCoupons();
	}
}