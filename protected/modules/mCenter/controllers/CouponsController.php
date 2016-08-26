<?php

/**
 * 红包管理
 */
class CouponsController extends mCenterController {
	
	//public $layout='column2';
	/**
	 * 创建红包
	 */
	public function actionCreateRedEnvelope() {
		// 面额类型
		$face_value_type = array ();
		$face_value_type [''] = '请选择';
		$face_value_type ['1'] = '固定面额';
		$face_value_type ['2'] = '随机面额';
		
		// 有效时间类型
		$valid_time_type = array ();
		$valid_time_type [''] = '请选择';
		$valid_time_type ['1'] = '固定时间';
		$valid_time_type ['2'] = '相对时间';
		
		//获取某个商户下的所有门店
		$store_array = array();
		$couponc = new CouponsC ();
		$merchant_id = Yii::app ()->session ['merchant_id'];
		$store_array = $couponc->getStoreForMerchangt($merchant_id);
		
		if (isset ( $_POST ['Coupons'] ) && ! empty ( $_POST ['Coupons'] )) {
			$couponc = new CouponsC ();
			$post = $_POST ['Coupons'];
			$merchant_id = Yii::app ()->session ['merchant_id'];
			$value_money = '';
			if (isset ( $_POST ['value_money'] )) {
				$value_money = $_POST ['value_money'];
			}
			$money = '';
			if (isset ( $_POST ['money'] )) {
				$money = $_POST ['money'];
			}
			$start_time = '';
			$end_time = '';
			if (isset ( $_POST ['Time'] ) && ! empty ( $_POST ['Time'] )) {
				$Time = array ();
				$Time = explode ( "-", $_POST ['Time'] );
				$start_time = $Time [0];
				$end_time = $Time [1];
			}
			$valid_time = '';
			if (isset ( $_POST ['valid_time'] )) {
				$valid_time = $_POST ['valid_time'];
			}
			
		    $date = '';
			if (isset ( $_POST ['Birth'] )) {
				$date = $_POST ['Birth'];
			}
			$start_money = '';
			$end_money = '';
			if (isset ( $_POST ['start_money'] ) && isset ( $_POST ['end_money'] )) {
				$start_money = $_POST ['start_money'];
				$end_money = $_POST ['end_money'];
			}
			$result = $couponc->createRedEnvelope ( $post, $date, $start_money, $end_money, $merchant_id, $value_money, $money, $start_time, $end_time, $valid_time );
			$result = json_decode ( $result, true );

			if ($result ['status'] == ERROR_NONE) {

				$this->redirect ( array (
						'redEnvelopeList'
				) );
			} else {
				$status = $result ['status'];
				$msg = $result ['errMsg'];
			}
		}
		$this->render ( 'createRedEnvelope', array (
				'face_value_type' => $face_value_type,
				'valid_time_type' => $valid_time_type ,'store_array'=>$store_array
		) );
	}
	
	/**
	 * 红包列表
	 */
	public function actionRedEnvelopeList() {
		$list = array ();
		
		$keyword_name = '';
		if(isset($_GET['keyword_name']) && !empty($_GET['keyword_name'])){
			$keyword_name = $_GET['keyword_name'];
		}
		
		$couponc = new CouponsC ();
		$merchant_id = Yii::app ()->session ['merchant_id'];
		$result = $couponc->getRedEnvelopeList ( $merchant_id ,$keyword_name);
		$result = json_decode ( $result, true );
		if ($result ['status'] == ERROR_NONE) {
			if (isset ( $result ['data'] ['list'] )) {
				$list = $result ['data'] ['list'];
			}
		}
		

		$this->render ( 'redEnvelopeList', array (
				'list' => $list,
				'pages'=>$couponc ->page
		) );
	}
	
	/**
	 * 红包删除
	 */
	public function actionDelRedEnvelope($id) {
		$couponc = new CouponsC ();
		$result = $couponc->delRedEnvelope ( $id );
		$result = json_decode ( $result, true );
		if ($result ['status'] == ERROR_NONE) {
			$this->actionRedEnvelopeList ();
		} else {
			var_dump ( $result ['errMsg'] );
		}
	}
	
	/**
	 * 红包详情
	 */
	public function actionRedEnvelopeDetails($id) {
		$list = array ();
		
		$couponc = new CouponsC ();
		$result = $couponc->getRedEnvelopeDetails ( $id );
		$result = json_decode ( $result, true );
		if ($result ['status'] == ERROR_NONE) {
			if (isset ( $result ['data'] ['list'] )) {
				$list = $result ['data'] ['list'];
			}
		} else {
			var_dump ( $result ['errMsg'] );
		}
		$this->render ( 'redEnvelopeDetails', array (
				'list' => $list 
		) );
	}
	
	/**
	 * 红包发放明细
	 */
	public function actionRedEnvelopeUsed() {
		$list = array ();
		$merchant_id = Yii::app ()->session ['merchant_id'];
		$couponc = new CouponsC ();
		$result = $couponc->getRedEnvelopeUsed ( $merchant_id );
		$result = json_decode ( $result, true );
		
		if ($result ['status'] == ERROR_NONE) {
			if (isset ( $result ['data'] ['list'] )) {
				$list = $result ['data'] ['list'];
			}
		}
		$this->render ( 'redEnvelopeUsed', array (
				'list' => $list,
				'pages' => $couponc -> page
		) );
	}
	
	/**
	 * 创建优惠券
	 */
	public function actionCreateYhq() {
		// 券类型
		$coupon_type = array ();
		$coupon_type ['2'] = '代金券';
		$coupon_type ['3'] = '折扣券';
		$coupon_type ['4'] = '兑换券';
		
		// 面额类型
		$face_value_type = array ();
		$face_value_type [''] = '请选择';
		$face_value_type ['1'] = '固定面额';
		$face_value_type ['2'] = '随机面额';
		
		// 有效时间类型
		$valid_time_type = array ();
		$valid_time_type [''] = '请选择';
		$valid_time_type ['1'] = '固定时间';
		$valid_time_type ['2'] = '相对时间';
		
		//获取某个商户下的所有门店
		$store_array = array();
		$couponc = new CouponsC ();
		$merchant_id = Yii::app ()->session ['merchant_id'];
		$store_array = $couponc->getStoreForMerchangt($merchant_id);
		
		if (isset ( $_POST ['Coupons'] ) && ! empty ( $_POST ['Coupons'] )) {
			$couponc = new CouponsC ();
			$post = $_POST ['Coupons'];
			$merchant_id = Yii::app ()->session ['merchant_id'];
			
			$value_money = '';
			if (isset ( $_POST ['value_money'] )) {
				$value_money = $_POST ['value_money'];
			}
			$money = '';
			if (isset ( $_POST ['money'] )) {
				$money = $_POST ['money'];
			}
			$start_time = '';
			$end_time = '';
			if (isset ( $_POST ['Time'] ) && ! empty ( $_POST ['Time'] )) {
				$Time = array ();
				$Time = explode ( "-", $_POST ['Time'] );
				$start_time = $Time [0];
				$end_time = $Time [1];
			}
			$valid_time = '';
			if (isset ( $_POST ['valid_time'] )) {
				$valid_time = $_POST ['valid_time'];
			}
				
			$date = '';
			if (isset ( $_POST ['Birth'] )) {
				$date = $_POST ['Birth'];
			}
			$start_money = '';
			$end_money = '';
			if (isset ( $_POST ['start_money'] ) && isset ( $_POST ['end_money'] )) {
				$start_money = $_POST ['start_money'];
				$end_money = $_POST ['end_money'];
			}
			
			
			$result = $couponc->createYhq ( $post, $date, $start_money, $end_money, $merchant_id, $value_money, $money, $start_time, $end_time, $valid_time  );
			$result = json_decode ( $result, true );
			
			if ($result ['status'] == ERROR_NONE) {
				$this->redirect ( array (
						'yhqList'
				) );
			} else {
				$status = $result ['status'];
				$msg = $result ['errMsg'];
			}
		}
		$this->render ( 'createYhq', array (
				'coupon_type' => $coupon_type,
				'face_value_type' => $face_value_type,
				'valid_time_type' => $valid_time_type ,'store_array'=>$store_array
		) );
	}
	
	/**
	 * 优惠券列表
	 */
	public function actionYhqList()
	{
		// 券类型
		$coupon_type = array ();
		$coupon_type ['2'] = '代金券';
		$coupon_type ['3'] = '折扣券';
		$coupon_type ['4'] = '兑换券';
		
		$list = array();
		$keyword_name = '';
		if(isset($_GET['keyword_name']) && !empty($_GET['keyword_name'])){
			$keyword_name = $_GET['keyword_name'];
		}
		$cou_type = '';
		if(isset($_GET['cou_type']) && !empty($_GET['cou_type'])){
			$cou_type = $_GET['cou_type'];
		}
		$coupons = new CouponsC();
		$merchant_id = Yii::app ()->session ['merchant_id'];
		$result = $coupons -> getYhqList($merchant_id,$keyword_name,$cou_type);
		$result = json_decode($result,true);
		if($result['status'] == ERROR_NONE){
			if(isset($result['data']['list'])){
				$list = $result['data']['list'];
			}
		}		
		
		$this->render('yhqList',array('list'=>$list,'coupon_type'=>$coupon_type,'pages'=>$coupons -> page));
	}
	
	/**
	 * 优惠券删除
	 */
	public function actionDelYhq($id)
	{
		$coupons = new CouponsC();
		$result = $coupons -> delYhq($id);
		$result = json_decode($result,true);
		
		if($result['status'] == ERROR_NONE){
			$this -> actionYhqList();
		}else {
			var_dump ( $result ['errMsg'] );
		}
	}
	
	/**
	 * 优惠券详情
	 */
	public function actionYhqDetails($id)
	{
		$coupons = new CouponsC();
		$result = $coupons -> getRedEnvelopeDetails($id);
		$result = json_decode($result,true);
		
		if ($result ['status'] == ERROR_NONE) {
			if (isset ( $result ['data'] ['list'] )) {
				$list = $result ['data'] ['list'];
			}
		} else {
			var_dump ( $result ['errMsg'] );
		}
		$this->render ( 'yhqDetails', array (
				'list' => $list
		) );
	}
	
	/**
	 * 优惠券发放明细
	 */
	public function actionYhqUsed()
	{
		$list = array ();
		$merchant_id = Yii::app ()->session ['merchant_id'];
		$couponc = new CouponsC ();
		$result = $couponc->getYhqUsed( $merchant_id );
		$result = json_decode ( $result, true );
		
		if ($result ['status'] == ERROR_NONE) {
			if (isset ( $result ['data'] ['list'] )) {
				$list = $result ['data'] ['list'];
			}
		}
		$this->render ( 'yhqUsed', array (
				'list' => $list,
				'pages' => $couponc -> page
		) );
	}
	
	/**
	 * 验证规则
	 */
	public function actionCheckCoupons()
	{
		$time = '';
		$valid_time = '';
		
		if(!empty($_POST['time']) && !empty($_POST['valid_time'])){
			$time = $_POST['time'];
			$valid_time = $_POST['valid_time'];
			$time_array = explode('-', $time);
			$valid_time_array = explode('-', $valid_time);
			if(date('Y-m-d'.' 00:00:00',strtotime($time_array[0])) > date('Y-m-d'.' 00:00:00',strtotime($valid_time_array[0])) ||
			(date('Y-m-d'.' 23:59:59',strtotime($time_array[1]))> date('Y-m-d'.' 23:59:59',strtotime($valid_time_array[1]))))
			{
				$arr = array('type'=>'error','v'=>'有效日期与发放日期不合逻辑');
				echo json_encode($arr);
			}else{
				$arr = array('type'=>'right','v'=>'');
				echo json_encode($arr);
			}
		}
		
// 		if(empty($_POST['valid_time'])){
// 			$arr = array('v'=>'有效日期必填');
// 			echo json_encode($arr);
// 		}
		
		
	}
}