<?php

class PromotionsController extends mCenterController
{
	/**
	 * 大转盘列表
	 */
	public function actionTurntableList()
	{
		$promotions = new PromotionsC();
		$merchant_id = Yii::app()->session['merchant_id'];
		$result = $promotions->getPromotionsList($merchant_id, PROMOTIONS_TYPE_TURNTABLE);
		if ($result['status'] == ERROR_NONE) {
			$list = $result['data'];
		}
		$now = date("Y-m-d h:i:s");
		
		$this->render('turntableList', array('list'=>$list, 'now'=>$now, 'pages' => $promotions->page));
	}
	
	/**
	 * 刮刮卡列表
	 */
	public function actionScratchList()
	{
		$promotions = new PromotionsC();
		$merchant_id = Yii::app()->session['merchant_id'];
		$result = $promotions->getPromotionsList($merchant_id, PROMOTIONS_TYPE_SCRATCH);
		if ($result['status'] == ERROR_NONE) {
			$list = $result['data'];
		}
		$now = date("Y-m-d h:i:s");
	
		$this->render('scratchList', array('list'=>$list, 'now'=>$now, 'pages' => $promotions->page));
	}
	
	/**
	 * 增加 修改 大转盘
	 */
	public function actionTurntableAdd()
	{
		$promotions = new PromotionsC();
		$title_arr = array();
		$id = !empty($_GET['id']) ? $_GET['id'] : '';
		
		if (!empty($_GET['id'])) {
			$model = $promotions -> getPromotions($id);
			$time = date('Y/m/d',strtotime($model['start_time'])).'-'.date('Y/m/d',strtotime($model['end_time']));
			$title_res = $promotions -> getCouponsTitle($id);
			if ($title_res['status'] == ERROR_NONE) {
				$title_arr = $title_res['data'];
			}
		}else{
			$model = $promotions->getPromotionsAttributes();
			$time = '';
		}
		
		if (isset($_POST['Activity']) && !empty($_POST['Activity'])) {
			$post = $_POST['Activity'];
			
			$model['name'] = $name = $post['name'];
			$type = PROMOTIONS_TYPE_TURNTABLE;
			
			$time = $post['time'];
			$arr_time = explode('-', $post['time']);
			$start_time = $arr_time[0];
			$end_time = $arr_time[1].' 23:59:59';

			$model['first_prize'] = $first_prize = $post['first_prize'];
			$model['first_prize_num'] = $first_prize_num = $post['first_prize_num'];
			$model['first_prize_probability'] = $first_prize_probability = $post['first_prize_probability'];
			$model['second_prize'] = $second_prize = $post['second_prize'];
			$model['second_prize_num'] = $second_prize_num = $post['second_prize_num'];
			$model['second_prize_probability'] = $second_prize_probability = $post['second_prize_probability'];
			$model['third_prize'] = $third_prize = $post['third_prize'];
			$model['third_prize_num'] = $third_prize_num = $post['third_prize_num'];
			$model['third_prize_probability'] = $third_prize_probability = $post['third_prize_probability'];
			$fourth_prize = '';
			$fourth_prize_num = '';
			$fourth_prize_probability = '';
			$fifth_prize = '';
			$fifth_prize_num = '';
			$fifth_prize_probability = '';
			$model['if_show_num'] = $if_show_num = !empty($post['if_show_num']) ? SHOW_PRIZE_NUM : '1';
			$model['everyday_num'] = $everyday_num = $post['everyday_num'];
			$model['everyone_num'] = $everyone_num = $post['everyone_num'];
			$model['everyone_everyday_num'] = $everyone_everyday_num = $post['everyone_everyday_num'];
			$model['illustrate'] = $illustrate = $post['illustrate'];

			$result = $promotions->addPromotions($id, $type, $name, $start_time, $end_time, $first_prize, $first_prize_num, $first_prize_probability, $second_prize, $second_prize_num, $second_prize_probability, $third_prize, $third_prize_num, $third_prize_probability, $fourth_prize, $fourth_prize_num, $fourth_prize_probability, $fifth_prize, $fifth_prize_num, $fifth_prize_probability, $if_show_num, $everyday_num, $everyone_num, $everyone_everyday_num, $illustrate);
			if ($result['status'] == ERROR_NONE) {
				$this->redirect('turntableList');
			}else{
				
			}
			
		}
		
		$this->render('turntableAdd', array('model' => $model, 'time'=>$time, 'title_arr'=>$title_arr));
	}
	
	/**
	 * 增加 修改刮刮卡
	 */
	public function actionScratchAdd()
	{
		$promotions = new PromotionsC();
		$title_arr = array();
		$id = !empty($_GET['id']) ? $_GET['id'] : '';
	
		if (!empty($_GET['id'])) {
			$model = $promotions -> getPromotions($id);
			$time = date('Y/m/d',strtotime($model['start_time'])).'-'.date('Y/m/d',strtotime($model['end_time']));
			$title_res = $promotions -> getCouponsTitle($id);
			if ($title_res['status'] == ERROR_NONE) {
				$title_arr = $title_res['data'];
			}
		}else{
			$model = $promotions->getPromotionsAttributes();
			$time = '';
		}
	
		if (isset($_POST['Activity']) && !empty($_POST['Activity'])) {
			$post = $_POST['Activity'];
				
			$model['name'] = $name = $post['name'];
			$type = PROMOTIONS_TYPE_SCRATCH;
				
			$time = $post['time'];
			$arr_time = explode('-', $post['time']);
			$start_time = $arr_time[0];
			$end_time = $arr_time[1].' 23:59:59';
	
			$model['first_prize'] = $first_prize = $post['first_prize'];
			$model['first_prize_num'] = $first_prize_num = $post['first_prize_num'];
			$model['first_prize_probability'] = $first_prize_probability = $post['first_prize_probability'];
			$model['second_prize'] = $second_prize = $post['second_prize'];
			$model['second_prize_num'] = $second_prize_num = $post['second_prize_num'];
			$model['second_prize_probability'] = $second_prize_probability = $post['second_prize_probability'];
			$model['third_prize'] = $third_prize = $post['third_prize'];
			$model['third_prize_num'] = $third_prize_num = $post['third_prize_num'];
			$model['third_prize_probability'] = $third_prize_probability = $post['third_prize_probability'];
			$model['fourth_prize'] = $fourth_prize = $post['fourth_prize'];
			$model['fourth_prize_num'] = $fourth_prize_num = $post['fourth_prize_num'];
			$model['fourth_prize_probability'] = $fourth_prize_probability = $post['fourth_prize_probability'];
			$model['fifth_prize'] = $fifth_prize = $post['fifth_prize'];
			$model['fifth_prize_num'] = $fifth_prize_num = $post['fifth_prize_num'];
			$model['fifth_prize_probability'] = $fifth_prize_probability = $post['fifth_prize_probability'];
			$model['if_show_num'] = $if_show_num = !empty($post['if_show_num']) ? SHOW_PRIZE_NUM : '1';
			$model['everyday_num'] = $everyday_num = $post['everyday_num'];
			$model['everyone_num'] = $everyone_num = $post['everyone_num'];
			$model['everyone_everyday_num'] = $everyone_everyday_num = $post['everyone_everyday_num'];
			$model['illustrate'] = $illustrate = $post['illustrate'];
				
			$result = $promotions->addPromotions($id, $type, $name, $start_time, $end_time, $first_prize, $first_prize_num, $first_prize_probability, $second_prize, $second_prize_num, $second_prize_probability, $third_prize, $third_prize_num, $third_prize_probability, $fourth_prize, $fourth_prize_num, $fourth_prize_probability, $fifth_prize, $fifth_prize_num, $fifth_prize_probability, $if_show_num, $everyday_num, $everyone_num, $everyone_everyday_num, $illustrate);
			if ($result['status'] == ERROR_NONE) {
				$this->redirect('scratchList');
			}else{
	
			}
				
		}
	
		$this->render('scratchAdd', array('model' => $model, 'time'=>$time, 'title_arr'=>$title_arr));
	}
	
	/**
	 * 删除大转盘活动
	 */
	public function actionTurntableDel()
	{
		$promotions = new PromotionsC();
		$id = $_GET['id'];
		
		$result =$promotions->promotionsDel($id); 
		if ($result['status'] == ERROR_NONE){
			$this->redirect('turntableList');
		}
	}
	
	/**
	 * 删除刮刮卡活动
	 */
	public function actionScratchDel()
	{
		$promotions = new PromotionsC();
		$id = $_GET['id'];
	
		$result =$promotions->promotionsDel($id);
		if ($result['status'] == ERROR_NONE){
			$this->redirect('scratchList');
		}
	}
	
	/**
	 * 抽奖记录
	 */
	public function actionPlayRecord()
	{
		$promotions = new PromotionsC();
		$prize_type_arr = array();
		$prize_type_arr = $GLOBALS['PRIZE_TYPE'];
		$prize_type_arr[0] = "请选择奖项";
		ksort($prize_type_arr);
		
		$id = $_GET['id'];
		$prize_type = isset($_POST['ActivityRecord']['prize_type']) ? $_POST['ActivityRecord']['prize_type'] : '';
		$phone_num = isset($_POST['ActivityRecord']['phone_num']) ? $_POST['ActivityRecord']['phone_num'] : '';
		$result = $promotions->getRecord($id, $prize_type, $phone_num);
		
		if ($result['status'] == ERROR_NONE) {
			$record = $result['data'];
		}else {
		}
		
		$this->render('playRecord', array('prize_type_arr'=>$prize_type_arr, 'record'=>$record, 'pages'=>$promotions->page));
	}
	
	/**
	 * 添加大转盘活动-添加奖品 卡券
	 */
	public function actionDialogCoupons()
	{
		$promotions = new PromotionsC();
		$prize_type = $_GET['prize_type'];
		$merchant_id = Yii::app()->session['merchant_id'];
		$title = empty($_POST['title']) ? '' : $_POST['title'];
		
		$result = $promotions->getCouponsList($merchant_id, $title);
		if ($result['status'] == ERROR_NONE) {
			$coupons = $result['data'];
		}
		
		$this->render('dialogCoupons', array('coupons'=>$coupons, 'title'=>$title, 'pages' => $promotions->page, 'prize_type'=>$prize_type));
	}
	
}