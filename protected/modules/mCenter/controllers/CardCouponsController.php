<?php

/**
 * 卡券管理
 */
class CardCouponsController extends mCenterController
{
	public $layout = 'newMain';

	public function actionIndex(){
		
	}
	/**
	 * 创建卡券
	 */
	public function actionCardCouponsAdd()
	{
		$start_days_arr = array(); //领取后几天生效 当天代表0最高90天
		$effective_days_arr = array(); //有效天数最少1天最多90天
		for($i=0;$i<=90;$i++){
			if ($i==0){
			  $start_days_arr[0] = '当天';
			}else {
			  $start_days_arr[$i] = $i;
			}
		}
		for($i=1;$i<=90;$i++){
			$effective_days_arr[$i] = $i;
		}
		
		$coupon_type = array ();
		$coupon_type ['2'] = '代金券';
		$coupon_type ['3'] = '折扣券';
		$coupon_type ['4'] = '兑换券';
		
		$merchant_id = Yii::app()->session['merchant_id'];
		$cardCouponsC = new CardCouponsC();
		
		if (isset ( $_POST ['Coupons'] ) && ! empty ( $_POST ['Coupons'] )) {
			$post = $_POST['Coupons'];
			//券类型
			$type = '';
			if (isset ( $post ['type'] )) {
				$type = $post ['type'];
			}
			
			//是否同步到微信卡包 1不开启 2开启
			$if_wechat = '';
			if (isset ( $post ['if_wechat'] ) && ! empty ( $post ['if_wechat'] )) {
				$if_wechat = $post ['if_wechat'];
			}
			
			//券颜色
			$color = '';
			if(isset($post['color']) && !empty($post['color'])){
				$color = $post['color'];
			}

			//券标题
			$title = '';
		    if (isset ( $post ['title'] ) && ! empty ( $post ['title'] )) {
				$title = $post ['title'];
			}
			
			//副标题
			$vice_title = '';
			if (isset ( $post ['vice_title'] ) && ! empty ( $post ['vice_title'] )) {
				$vice_title = $post ['vice_title'];
			}
			
 			//券金额类型 1固定 2随机
			$money_type = '';
			if (isset ( $post ['money_type'] ) && ! empty ( $post ['money_type'] )) {
				$money_type = $post ['money_type'];
			}
			
			//固定金额
			$money = '';
			if (isset ( $post ['money'] ) && ! empty ( $post ['money'] )) {
				$money = $post ['money'];
			}
			
			//随机金额开始金额     结束金额
			$start_money = '';
			$end_money = '';
			if (isset ( $post ['start_money'] ) && ! empty ( $post ['start_money'] )) {
				$start_money = $post ['start_money'];
			}
			if (isset ( $post ['end_money'] ) && ! empty ( $post ['end_money'] )) {
				$end_money = $post ['end_money'];
			}

			//券折扣
			$discount = '';
			if (isset ( $post ['discount'] ) && ! empty ( $post ['discount'] )) {
				$discount = $post ['discount'];
			}
			
			//提示操作
			$prompt = '';
			if (isset ( $post ['prompt'] ) && ! empty ( $post ['prompt'] )) {
				$prompt = $post ['prompt'];
			}
			
			//用户是否可以分享领取链接 1可以 2不可以
			$if_share = '';
			if (isset ( $post ['if_share'] ) && ! empty ( $post ['if_share'] )) {
				$if_share = $post ['if_share'];
			}
			
			//可否转增其他好友 1 能 2不能
			$if_give = '';
			if (isset ( $post ['if_give'] ) && ! empty ( $post ['if_give'] )) {
				$if_give = $post ['if_give'];
			}
			
			//发放数量
			$num = '';
			if (isset ( $post ['num'] ) && ! empty ( $post ['num'] )) {
				$num = $post ['num'];
			}
			
			//有效时间类型1固定时间 2相对时间
			$time_type = '';
			if (isset ( $post ['time_type'] ) && ! empty ( $post ['time_type'] )) {
				$time_type = $post ['time_type'];
			}
			
			//固定时间时的有效开始时间       固定时间有效结束时间
			$start_time = '';
			$end_time = '';
			if (isset ( $_POST ['Time'] ) && ! empty ( $_POST ['Time'] )) {
				$arr = explode('-', $_POST ['Time']);
				$start_time = $arr[0];
				$end_time = $arr[1];
			}
			
			//领取后几天生效 当天代表0最高90天
			$start_days = '';
			if (isset ( $post ['start_days'] ) && ! empty ( $post ['start_days'] )) {
				$start_days = $post ['start_days'];
			}
			
			//有效天数最少1天最多90天
			$effective_days = '';
			if (isset ( $post ['effective_days'] ) && ! empty ( $post ['effective_days'] )) {
				$effective_days = $post ['effective_days'];
			}
			
			//每个用户领取数量
			$receive_num = '';
			if (isset ( $post ['receive_num'] ) && ! empty ( $post ['receive_num'] )) {
				$receive_num = $post ['receive_num'];
			}
			
			//最低消费
			$mini_consumption = '';
			if (isset ( $post ['mini_consumption'] ) && ! empty ( $post ['mini_consumption'] )) {
				$mini_consumption = $post ['mini_consumption'];
			}
			
			//使用限制
			$use_restriction = '';
			if (isset ( $post ['use_restriction'] ) && ! empty ( $post ['use_restriction'] )) {
				$use_restriction = $post ['use_restriction'];
			}
			
			//是否能与会员折扣同用1不能 2能
			$if_with_userdiscount = '';
			if (isset ( $post ['if_with_userdiscount'] ) && ! empty ( $post ['if_with_userdiscount'] )) {
				$if_with_userdiscount = $post ['if_with_userdiscount'];
			}
			
			//核销渠道限制
			$use_channel = '';
			if (isset($post['use_channel']) && !empty($post['use_channel'])) {
				$use_channel = $post['use_channel'];
			}
			
			//门店限制
			$store_limit = '';
		    if (isset ( $post ['store_limit'] ) && ! empty ( $post ['store_limit'] )) {
				$store_limit = $post ['store_limit'];
			}
			
			//客服电话
			$tel = '';
			if (isset ( $post ['tel'] ) && ! empty ( $post ['tel'] )) {
				$tel = $post ['tel'];
			}
			
			//使用须知
			$use_illustrate = '';
			if (isset ( $post ['use_illustrate'] ) && ! empty ( $post ['use_illustrate'] )) {
				$use_illustrate = $post ['use_illustrate'];
			}
			
			//优惠说明
			$discount_illustrate = '';
			if (isset ( $post ['discount_illustrate'] ) && ! empty ( $post ['discount_illustrate'] )) {
				$discount_illustrate = $post ['discount_illustrate'];
			}
			
			//商户简称
			$short_name = '';
			if (isset ( $post ['merchant_short_name'] ) && ! empty ( $post ['merchant_short_name'] )) {
				$short_name = $post ['merchant_short_name'];
			}
			
			$result = $cardCouponsC->cardCouponsAdd ( $merchant_id, $type, $if_wechat, $title, $vice_title, $discount, $prompt, 
					                                  $if_share, $if_give, $num, $time_type, $start_time, $end_time, $start_days, 
					                                  $effective_days, $receive_num, $mini_consumption, $if_with_userdiscount, $tel, 
					                                  $use_illustrate, $discount_illustrate, $money_type, $start_money, $end_money, $money,$store_limit,$color,$use_restriction,$short_name,$use_channel);
			
			$result = json_decode ( $result, true );
			if ($result ['status'] == ERROR_NONE) {
				if(isset($result ['errcode'])){
					$url =  Yii::app()->createUrl('mCenter/cardCoupons/cardCouponsList');
					if($result ['errcode'] == 0){
						echo "<script>alert('卡券同步成功');window.location.href='$url'</script>";
					}else{
						echo "<script>alert('卡券同步失败');window.location.href='$url'</script>";
					}
				}else{
				        $this->redirect ( array ('cardCouponsList' ) );
				}
			} else {
				$status = $result ['status'];
				$msg = $result ['errMsg'];
				//echo $status.$msg;
			}
		}
		
		$merchant_name = $cardCouponsC -> getMerchantName($merchant_id); //获取商户名
		$merchant_logo = $cardCouponsC -> getMerchantLogo($merchant_id); //获取商户Logo
		$count_store = $cardCouponsC -> getCountStore($merchant_id); //商户对应的总门店数
		$merchant_short_name = $cardCouponsC -> getMerchantShortName($merchant_id); //获取商户简称
		
		$this->render('cardCouponsAdd',array (
				'start_days_arr' => $start_days_arr,
				'effective_days_arr' => $effective_days_arr,
				'coupon_type' => $coupon_type,
				'merchant_name' => $merchant_name,
				'merchant_logo' => $merchant_logo,
				'count_store' => $count_store,
				'merchant_short_name'=>$merchant_short_name
		));
	}
	
	
	/**
	 * 编辑卡券
	 */
	public function actionEditCoupons($coupons_id)
	{
		$start_days_arr = array(); //领取后几天生效 当天代表0最高90天
		$effective_days_arr = array(); //有效天数最少1天最多90天
		for($i=0;$i<=90;$i++){
			if ($i==0){
				$start_days_arr[0] = '当天';
			}else {
				$start_days_arr[$i] = $i;
			}
		}
		for($i=1;$i<=90;$i++){
			$effective_days_arr[$i] = $i;
		}
		
		$coupon_type = array ();
		$coupon_type ['2'] = '代金券';
		$coupon_type ['3'] = '折扣券';
		$coupon_type ['4'] = '兑换券';
		
		$cardCouponsC = new CardCouponsC();
		$merchant_id = Yii::app()->session['merchant_id'];
		
		if (isset ( $_POST ['Coupons'] ) && ! empty ( $_POST ['Coupons'] )) {
			$post = $_POST['Coupons'];
			//券类型
// 			$type = '';
// 			if (isset ( $post ['type'] )) {
// 				$type = $post ['type'];
// 			}
				
			//是否同步到微信卡包 1不开启 2开启
			$if_wechat = '';
			if (isset ( $post ['if_wechat'] ) && ! empty ( $post ['if_wechat'] )) {
				$if_wechat = $post ['if_wechat'];
			}
				
			//券颜色
			$color = '';
			if(isset($post['color']) && !empty($post['color'])){
				$color = $post['color'];
			}
		
			//券标题
			$title = '';
			if (isset ( $post ['title'] ) && ! empty ( $post ['title'] )) {
				$title = $post ['title'];
			}
				
			//副标题
			$vice_title = '';
			if (isset ( $post ['vice_title'] ) && ! empty ( $post ['vice_title'] )) {
				$vice_title = $post ['vice_title'];
			}
				
			//券金额类型 1固定 2随机
			$money_type = '';
			if (isset ( $post ['money_type'] ) && ! empty ( $post ['money_type'] )) {
				$money_type = $post ['money_type'];
			}
				
			//固定金额
			$money = '';
			if (isset ( $post ['money'] ) && ! empty ( $post ['money'] )) {
				$money = $post ['money'];
			}
				
			//随机金额开始金额     结束金额
			$start_money = '';
			$end_money = '';
			if (isset ( $post ['start_money'] ) && ! empty ( $post ['start_money'] )) {
				$start_money = $post ['start_money'];
			}
			if (isset ( $post ['end_money'] ) && ! empty ( $post ['end_money'] )) {
				$end_money = $post ['end_money'];
			}
		
			//券折扣
			$discount = '';
			if (isset ( $post ['discount'] ) && ! empty ( $post ['discount'] )) {
				$discount = $post ['discount'];
			}
				
			//提示操作
			$prompt = '';
			if (isset ( $post ['prompt'] ) && ! empty ( $post ['prompt'] )) {
				$prompt = $post ['prompt'];
			}
				
			//用户是否可以分享领取链接 1可以 2不可以
			$if_share = '';
			if (isset ( $post ['if_share'] ) && ! empty ( $post ['if_share'] )) {
				$if_share = $post ['if_share'];
			}
				
			//可否转增其他好友 1 能 2不能
			$if_give = '';
			if (isset ( $post ['if_give'] ) && ! empty ( $post ['if_give'] )) {
				$if_give = $post ['if_give'];
			}
				
			//发放数量
			$num = '';
			if (isset ( $post ['num'] ) && ! empty ( $post ['num'] )) {
				$num = $post ['num'];
			}
				
			//有效时间类型1固定时间 2相对时间
			$time_type = '';
			if (isset ( $post ['time_type'] ) && ! empty ( $post ['time_type'] )) {
				$time_type = $post ['time_type'];
			}
				
			//固定时间时的有效开始时间       固定时间有效结束时间
			$start_time = '';
			$end_time = '';
			if (isset ( $_POST ['Time'] ) && ! empty ( $_POST ['Time'] )) {
				$arr = explode('-', $_POST ['Time']);
				$start_time = $arr[0];
				$end_time = $arr[1];
			}
				
			//领取后几天生效 当天代表0最高90天
			$start_days = '';
			if (isset ( $post ['start_days'] ) && ! empty ( $post ['start_days'] )) {
				$start_days = $post ['start_days'];
			}
				
			//有效天数最少1天最多90天
			$effective_days = '';
			if (isset ( $post ['effective_days'] ) && ! empty ( $post ['effective_days'] )) {
				$effective_days = $post ['effective_days'];
			}
				
			//每个用户领取数量
			$receive_num = '';
			if (isset ( $post ['receive_num'] ) && ! empty ( $post ['receive_num'] )) {
				$receive_num = $post ['receive_num'];
			}
				
			//最低消费
			$mini_consumption = '';
			if (isset ( $post ['mini_consumption'] ) && ! empty ( $post ['mini_consumption'] )) {
				$mini_consumption = $post ['mini_consumption'];
			}
			
			//使用限制
			$use_restriction = '';
			if (isset ( $post ['use_restriction'] ) && ! empty ( $post ['use_restriction'] )) {
				$use_restriction = $post ['use_restriction'];
			}
				
			//是否能与会员折扣同用1不能 2能
			$if_with_userdiscount = '';
			if (isset ( $post ['if_with_userdiscount'] ) && ! empty ( $post ['if_with_userdiscount'] )) {
				$if_with_userdiscount = $post ['if_with_userdiscount'];
			}
				
			//门店限制
			$store_limit = '';
			if (isset ( $post ['store_limit'] ) && ! empty ( $post ['store_limit'] )) {
				$store_limit = $post ['store_limit'];
			}
				
			//客服电话
			$tel = '';
			if (isset ( $post ['tel'] ) && ! empty ( $post ['tel'] )) {
				$tel = $post ['tel'];
			}
				
			//使用须知
			$use_illustrate = '';
			if (isset ( $post ['use_illustrate'] ) && ! empty ( $post ['use_illustrate'] )) {
				$use_illustrate = $post ['use_illustrate'];
			}
				
			//优惠说明
			$discount_illustrate = '';
			if (isset ( $post ['discount_illustrate'] ) && ! empty ( $post ['discount_illustrate'] )) {
				$discount_illustrate = $post ['discount_illustrate'];
			}
			
			//商户简称
			$short_name = '';
			if (isset ( $post ['merchant_short_name'] ) && ! empty ( $post ['merchant_short_name'] )) {
				$short_name = $post ['merchant_short_name'];
			}
				
			$result = $cardCouponsC->editCardCoupons ( $coupons_id,$merchant_id, $if_wechat, $title, $vice_title, $discount, $prompt,
					$if_share, $if_give, $num, $time_type, $start_time, $end_time, $start_days,
					$effective_days, $receive_num, $mini_consumption, $if_with_userdiscount, $tel,
					$use_illustrate, $discount_illustrate, $money_type, $start_money, $end_money, $money,$store_limit,$color,$use_restriction,$short_name);
				
			$result = json_decode ( $result, true );
			if ($result ['status'] == ERROR_NONE) {
				$this->redirect ( array (
						'cardCouponsList'
				) );
			} else {
				$status = $result ['status'];
				$msg = $result ['errMsg'];
				echo $msg;//exit;
			}
		}
		
		$model = array();
		$store_id_str = '';
		$result = $cardCouponsC -> getEditCoupons($coupons_id);
		$result = json_decode ( $result, true );
		if ($result ['status'] == ERROR_NONE) {
			$model = $result['data']['list'];
			if(!empty($model['store_limit_name'])){
				foreach ( $model ['store_limit_name'] as $key => $val ) {
					$store_id_str = $store_id_str . ',' . $key; //获取数据库已经添加过的门店id（字符串形式(,16)）
				}
			}
		}
				
		$merchant_name = $cardCouponsC -> getMerchantName($merchant_id);
		$merchant_logo = $cardCouponsC -> getMerchantLogo($merchant_id);
		$count_store = $cardCouponsC -> getCountStore($merchant_id); //商户对应的总门店数
		
		$this->render('editCoupons',array('model'=>$model,
				      'start_days_arr'=>$start_days_arr,
				      'effective_days_arr'=>$effective_days_arr,
				      'coupon_type' =>$coupon_type,
				      'merchant_name' => $merchant_name,
				      'merchant_logo' => $merchant_logo,
				      'store_id_str' => $store_id_str,
				      'count_store' => $count_store
				
		));
	}
	
	/**
	 * 卡券列表
	 */
	public function actionCardCouponsList()
	{
		$list = array();
		$merchant_id = Yii::app()->session['merchant_id'];
		
		$key_woed = '';
		if(isset($_GET['key_word'])){
			$key_woed = $_GET['key_word'];
		}
		
		$cardCouponsC = new CardCouponsC();
		$result = $cardCouponsC -> getCardCouponsList($merchant_id,$key_woed);
		$result = json_decode($result,true);
		if($result['status'] == ERROR_NONE){
			$list = $result['data']['list'];
		}
		$this->render('cardCouponsList',array(
				'list'=>$list,
				'pages'=>$cardCouponsC->page
		));
	}
	
	/**
	 * 使失效操作
	 * $coupons_id  券id
	 */
	public function actionInvalid($coupons_id)
	{
		$cardCouponsC = new CardCouponsC();
		$result = $cardCouponsC -> invalid($coupons_id);
		$result = json_decode($result,true);
		if($result['status'] == ERROR_NONE){
			$this->redirect(array('CouponList'));
		}
	}
	
	/**
	 * 修改剩余量
	 * $coupons_id  券id
	 */
	public function actionEditNum($coupons_id)
	{
		//由于form表单是动态添加的   所以每个input都必需加个coupons_id做标示
		$edit_num = ''; //定义修改量
		if(isset($_POST['edit_num'.$coupons_id])){
			$edit_num = $_POST['edit_num'.$coupons_id];
		}
		$change_type = '';//定义增减类型
		if(isset($_POST['stock_type'.$coupons_id]) && !empty($_POST['stock_type'.$coupons_id])){
			$change_type = $_POST['stock_type'.$coupons_id]; 
		}
		
		$cardCouponsC = new CardCouponsC();
		$result = $cardCouponsC -> editNum($coupons_id,$edit_num,$change_type);
		$result = json_decode($result,true);
		if($result['status'] == ERROR_NONE){
			$this->redirect(array('CouponList'));
		}
	}
	
	/**
	 * 券详情
	 * $coupons_id  券id
	 */
	public function actionDetail($coupons_id)
	{
		$coupon_type = array ();
		$coupon_type ['2'] = '代金券';
		$coupon_type ['3'] = '折扣券';
		$coupon_type ['4'] = '兑换券';
		
		$start_days_arr = array(); //领取后几天生效 当天代表0最高90天
		$effective_days_arr = array(); //有效天数最少1天最多90天
		for($i=0;$i<=90;$i++){
			if ($i==0){
				$start_days_arr[0] = '当天';
			}else {
				$start_days_arr[$i] = $i;
			}
		}
		for($i=1;$i<=90;$i++){
			$effective_days_arr[$i] = $i;
		}
		
		$list = array();
		$cardCouponsC = new CardCouponsC();
		$result = $cardCouponsC -> detail($coupons_id);
		$result = json_decode($result,true);
		if($result['status'] == ERROR_NONE){
			$list = $result['data']['list'];
		}
		$merchant_id = Yii::app()->session['merchant_id'];
		$count_store = $cardCouponsC -> getCountStore($merchant_id); //商户对应的总门店数
		
		$this->render('detailCoupons',array('model'=>$list,
				                            'coupon_type'=>$coupon_type,
				                            'start_days_arr'=>$start_days_arr,
				                            'effective_days_arr'=>$effective_days_arr,
				                            'count_store' => $count_store
		));
	}
	
	/**
	 * 添加门店弹出框
	 */
	public function actionAddStoreDialog()
	{
		$list = array();
		$key_word = '';
		if(isset($_GET['key_word'])){
			$key_word = $_GET['key_word'];
		}
		
		$type = '';
		if(isset($_GET['type'])){
			$type = $_GET['type'];
		}
		
		if (empty ( $type )) { //编辑操作
// 			$arr_store = ''; // 已经选中的门店
// 			if (isset ( $_GET ['arr_store'] )) {
// 				$arr_store = $_GET ['arr_store'];
// 				$arr_store = json_decode ( $arr_store, true );
// 			}
			$arr_store = array(); // 已经选中的门店
			if (isset ( $_GET ['arr_store'] )) {
				$arr = json_decode($_GET ['arr_store'],true);
				foreach ($arr as $v){
					$arr_store[$v['id']] = $v['name'];
				}
			}//var_dump($arr_store);//exit;
		}else{ //添加操作
			$arr_store = array(); // 已经选中的门店
			if (isset ( $_GET ['arr_store'] )) {
				$arr = json_decode($_GET ['arr_store'],true);
				foreach ($arr as $v){
					$arr_store[$v['id']] = $v['name'];
				}
			}
		}
		//var_dump($arr_store);//exit;
		$merchant_id = Yii::app()->session['merchant_id'];
		$cardCouponsC = new CardCouponsC();
		$result = $cardCouponsC -> addStore($merchant_id,$key_word);
		$result = json_decode($result,true);
		
		if($result['status'] == ERROR_NONE){
			$list = $result['data']['list'];
		}
		$this->render('addStoreDialog',array(
				'list'=>$list,
				'pages'=>$cardCouponsC->page,
				'arr_store'=>$arr_store,
				'type' => $type
		));
	}
	
	/**
	 * 获取门店列表
	 */
	public function actionGetStoreList()
	{
		$list = array();
		$merchant_id = Yii::app()->session['merchant_id'];
		$cardCouponsC = new CardCouponsC();
		$result = $cardCouponsC -> getStoreList($merchant_id);
		$result = json_decode($result,true);
		if($result['status'] == ERROR_NONE){
			$list = $result['data']['list'];
		}
		echo json_encode($list);
	}
	
	/**
	 * 弹出框选中的门店加载到主页面上
	 */
	public function actionLoadStoreOnPage()
	{
		$store_id = '';
		if(isset($_POST['store_id'])){
			$store_id = $_POST['store_id'];
		}
		
		$cardCouponsC = new CardCouponsC();
		$result = $cardCouponsC -> getStore($store_id);
		$result = json_decode($result,true);
		if($result['status'] == ERROR_NONE){
		  echo json_encode($result['data']['list']);
		}
	}
	
	/**
	 * 删除已经选中的门店(去掉复选框勾选)
	 */
	public function actionDelChooseStore()
	{
		$store_str = ''; //获取已经选中的门店id集
		$delData = ''; //要删除的门店id
		if(isset($_POST['store_str'])){
			$store_str = $_POST['store_str'];
		}
		if(isset($_POST['delData'])){
			$delData = $_POST['delData'];
		}
		
		$store_str_arr = explode(',',$store_str);
		//array_pop($store_str_arr);
		array_splice($store_str_arr, array_search($delData,$store_str_arr), 1); //删除指定元素  并重新排序索引
		echo json_encode($store_str_arr);
	}
	
	/**
	 * 延长有效期操作
	 */
	public function actionExtendTime()
	{
		$date = '';
		if(isset($_POST['date'])){
			$date = $_POST['date'];
		}
		$coupons_id = '';
		if(isset($_POST['coupons_id'])){
			$coupons_id = $_POST['coupons_id'];
		}
		$cardCouponsC = new CardCouponsC();
		$result = $cardCouponsC -> extendedTime($coupons_id,$date);
		$result = json_decode($result,true);
		if($result['status'] == ERROR_NONE){
			echo 'success';
		}else{
			echo 'fail';
		}
		
	}
	
	/**
	 * 创建卡券二维码
	 */
	public function actionCreateQr()
	{
		//引入phpqrcode库文件
		Yii::import('application.extensions.qrcode.*');
		include('phpqrcode.php');
				
		// 二维码数据
		$data = '';
		if(isset($_GET['getCouponUrl'])){
			$data = $_GET['getCouponUrl'];
		}
		
		// 生成的文件名
		//$filename = 'qrcode'.$id.'.png';
		// 纠错级别：L、M、Q、H
		$errorCorrectionLevel = 'L';
		// 点的大小：1到10
		$matrixPointSize = 8;
		//创建一个二维码文件
		//QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 4);
		
		//输入二维码到浏览器
		QRcode::png($data);
	}
	
	//创建优惠券
	public function actionCreateCoupon()
	{
		
		$start_days_arr = array(); //领取后几天生效 当天代表0最高90天
		$effective_days_arr = array(); //有效天数最少1天最多90天
		for($i=0;$i<=90;$i++){
			if ($i==0){
				$start_days_arr[0] = '当';
			}else {
				$start_days_arr[$i] = $i;
			}
		}
		for($i=1;$i<=90;$i++){
			$effective_days_arr[$i] = $i;
		}
		$coupon_type = array ();
		$coupon_type ['2'] = '代金券';
		$coupon_type ['3'] = '折扣券';
		$coupon_type ['4'] = '兑换券';
		
		$merchant_id = Yii::app()->session['merchant_id'];
		$cardCouponsC = new CardCouponsC();
		

		
		
		$card_info = array();
		if (!empty($_POST)){
		//var_dump($_POST);
			$post = $_POST['Coupons'];
			//商户简称
			$card_info['merchant_short_name'] = '';
			if (isset ( $post ['merchant_short_name'] ) && ! empty ( $post ['merchant_short_name'] )) {
				$card_info['merchant_short_name'] = $post ['merchant_short_name'];
			}
			//商户logo
			$card_info['logo'] = '';
			if (isset ( $post ['logo'] ) && ! empty ( $post ['logo'] )) {
				$card_info['logo'] = $post ['logo'];
			}
			//券类型
			$card_info['type'] = '';
			if (isset ( $post ['type'] )) {
				$card_info['type'] = $post ['type'];
			}
				
			//券颜色
			$card_info['color'] = '';
			if(isset($post['color']) && !empty($post['color'])){
				$card_info['color'] = $post['color'];
			}
			
			//券标题
			$card_info['title'] = '';
			if (isset ( $post ['title'] ) && ! empty ( $post ['title'] )) {
				$card_info['title'] = $post ['title'];
			}
				
			//副标题
			$card_info['vice_title'] = '';
			if (isset ( $post ['vice_title'] ) && ! empty ( $post ['vice_title'] )) {
				$card_info['vice_title'] = $post ['vice_title'];
			}
				
			//券折扣
			$card_info['discount'] = '';
			if (isset ( $post ['discount'] ) && ! empty ( $post ['discount'] )) {
				$card_info['discount'] = $post ['discount'];
			}
				
			//提示操作
			$card_info['prompt'] = '';
			if (isset ( $post ['prompt'] ) && ! empty ( $post ['prompt'] )) {
				$card_info['prompt'] = $post ['prompt'];
			}
				
			//用户是否可以分享领取链接 1可以 2不可以
			$card_info['if_share'] = '';
			if (isset ( $post ['if_share'] ) && ! empty ( $post ['if_share'] )) {
				$card_info['if_share'] = $post ['if_share'];
			}
				
			//可否转增其他好友 1 能 2不能
			$card_info['if_give'] = '';
			if (isset ( $post ['if_give'] ) && ! empty ( $post ['if_give'] )) {
				$card_info['if_give'] = $post ['if_give'];
			}
			
				
			//发放数量
			$card_info['num'] = '0';
			if (isset ( $post ['num'] ) && ! empty ( $post ['num'] )) {
				$card_info['num'] = $post ['num'];
			}
				
			//有效时间类型1固定时间 2相对时间
			$card_info['time_type'] = '';
			if (isset ( $post ['time_type'] ) && ! empty ( $post ['time_type'] )) {
				$card_info['time_type'] = $post ['time_type'];
			}
				
			//固定时间时的有效开始时间       固定时间有效结束时间
			$card_info['start_time'] = '';
			$card_info['end_time'] = '';
			if (isset ( $post ['time'] ) && ! empty ( $post ['time'] )) {
				$arr = explode('-', $post ['time']);
				$card_info['start_time'] = $arr[0];
				$card_info['end_time'] = $arr[1];
			}
				
			//领取后几天生效 当天代表0最高90天
			$card_info['start_days'] = '';
			if (isset ( $post ['start_days'] ) && ! empty ( $post ['start_days'] )) {
				$card_info['start_days'] = $post ['start_days'];
			}
				
			//有效天数最少1天最多90天
			$card_info['effective_days'] = '';
			if (isset ( $post ['effective_days'] ) && ! empty ( $post ['effective_days'] )) {
				$card_info['effective_days'] = $post ['effective_days'];
			}
			
			//可用时段
			$card_info['available_time'] = '';
			$available_time = array();
			$day = array();
			if($post['available_time'] = '2'){
				if (isset ( $post ['day'] ) && ! empty ( $post ['day'] )) {
					$day = $post ['day'];
					if (! empty ( $post ['begin_hour'] ) && !empty( $post ['end_hour'] )) {
						if(in_array("周一",$day)){
							$available_time[] = array('type'=>'MONDAY','begin_hour'=>$post ['begin_hour'],'end_hour'=>$post ['end_hour']); 
						}
						if (in_array("周二",$day)){
							$available_time[] = array('type'=>'TUESDAY','begin_hour'=>$post ['begin_hour'],'end_hour'=>$post ['end_hour']);
						}
						if (in_array("周三",$day)){
							$available_time[] = array('type'=>'WEDNESDAY','begin_hour'=>$post ['begin_hour'],'end_hour'=>$post ['end_hour']);
						}
						if (in_array("周四",$day)){
							$available_time[] = array('type'=>'THURSDAY','begin_hour'=>$post ['begin_hour'],'end_hour'=>$post ['end_hour']);
						}
						if (in_array("周五",$day)){
							$available_time[] = array('type'=>'FRIDAY','begin_hour'=>$post ['begin_hour'],'end_hour'=>$post ['end_hour']);
						}
						if (in_array("周六",$day)){
							$available_time[] = array('type'=>'SATURDAY','begin_hour'=>$post ['begin_hour'],'end_hour'=>$post ['end_hour']);
						}
						if (in_array("周日",$day)){
							$available_time[] = array('type'=>'SUNDAY','begin_hour'=>$post ['begin_hour'],'end_hour'=>$post ['end_hour']);
						}
					}else {
						if(in_array("周一",$day)){
							$available_time[] = array('type'=>'MONDAY');
						}
						if (in_array("周二",$day)){
							$available_time[] = array('type'=>'TUESDAY');
						}
						if (in_array("周三",$day)){
							$available_time[] = array('type'=>'WEDNESDAY');
						}
						if (in_array("周四",$day)){
							$available_time[] = array('type'=>'THURSDAY');
						}
						if (in_array("周五",$day)){
							$available_time[] = array('type'=>'FRIDAY');
						}
						if (in_array("周六",$day)){
							$available_time[] = array('type'=>'SATURDAY');
						}
						if (in_array("周日",$day)){
							$available_time[] = array('type'=>'SUNDAY');
						}
					}
					$card_info['available_time'] = $available_time;
				}
			}else{
				$card_info['available_time'] = '';
			}
				
			
			
			//兑换信息
			$card_info['gift_info'] = '';
			if (isset ( $post ['gift_info'] ) && ! empty ( $post ['gift_info'] )) {
				$card_info['gift_info'] = $post ['gift_info'];
			}

			
			//减免金额
			$card_info['money'] = '';
			if (isset ( $post ['reduce_cost'] ) && ! empty ( $post ['reduce_cost'] )) {
				$card_info['money'] = $post ['reduce_cost'];
			}
			
			//最低消费
			$card_info['least_cost'] = '';
			if (isset ( $post ['least_cost'] ) && ! empty ( $post ['least_cost'] )) {
				$card_info['least_cost'] = $post ['least_cost'];
			}
			$card_info['discount_rule'] = '';
			if (isset ( $post ['discount_rule'] ) && ! empty ( $post ['discount_rule'] )) {
				$card_info['discount_rule'] = $post ['discount_rule'];
			}
			$card_info['gift_rule'] = '';
			if (isset ( $post ['gift_rule'] ) && ! empty ( $post ['gift_rule'] )) {
				$card_info['gift_rule'] = $post ['gift_rule'];
			}
			
			//是否能与会员折扣同用1不能 2能
			$card_info['if_with_userdiscount'] = '';
			if (isset ( $post ['if_with_userdiscount'] ) && ! empty ( $post ['if_with_userdiscount'] )) {
				$card_info['if_with_userdiscount'] = $post ['if_with_userdiscount'];
			}
			
			//使用须知
			$card_info['use_illustrate'] = '';
			if (isset ( $post ['use_illustrate'] ) && ! empty ( $post ['use_illustrate'] )) {
				$card_info['use_illustrate'] = $post ['use_illustrate'];
			}
			

			
			//客服电话
			$card_info['tel'] = '';
			if (isset ( $post ['tel'] ) && ! empty ( $post ['tel'] )) {
				$card_info['tel'] = $post ['tel'];
			}
			

			
			//优惠说明
			$card_info['discount_illustrate'] = '';
			if (isset ( $post ['discount_illustrate'] ) && ! empty ( $post ['discount_illustrate'] )) {
				$card_info['discount_illustrate'] = $post ['discount_illustrate'];
			}
			
			//使用限制
			$card_info['use_restriction'] = '';
			if (isset ( $post ['use_restriction'] ) && ! empty ( $post ['use_restriction'] )) {
				$card_info['use_restriction'] = $post ['use_restriction'];
			}
				
			//每个用户领取数量
			$card_info['receive_num'] = '1';
			if (isset ( $post ['receive_num'] ) && ! empty ( $post ['receive_num'] )) {
				$card_info['receive_num'] = $post ['receive_num'];
			}
				
			//核销渠道限制
			$card_info['use_channel'] = COUPONS_USE_CHANNEL_ALL;
			if (isset($post['channel1']) && isset($post['channel2'])) {
				$card_info['use_channel'] = COUPONS_USE_CHANNEL_ALL;
			}
			if (isset($post['channel1']) && !isset($post['channel2'])) {
				$card_info['use_channel'] = COUPONS_USE_CHANNEL_OFFLINE;
			}
			if (!isset($post['channel1']) && isset($post['channel2'])) {
				$card_info['use_channel'] = COUPONS_USE_CHANNEL_ONLINE;
			}
				
			//门店限制
			$card_info['store_limit'] = '';
			if (isset ( $post ['store_limit'] ) && ! empty ( $post ['store_limit'] )) {
				$card_info['store_limit'] = $post ['store_limit'];
			}
			//门店类型
			$card_info['store_limit_type'] = '';
			if (isset ( $_POST['applicable-store'] ) && ! empty ( $_POST['applicable-store'] )) {
				$card_info['store_limit_type'] = $_POST['applicable-store'];
			}
			
			//封面图片
			$card_info['cover_img'] = '';
			if (isset ( $post ['cover_img'] ) && ! empty ( $post ['cover_img'] )) {
				$card_info['cover_img'] = $post ['cover_img'];
			}
			
			//封面图片
			$card_info['cover_img_text'] = '';
			if (isset ( $post ['cover_img_text'] ) && ! empty ( $post ['cover_img_text'] )) {
				$card_info['cover_img_text'] = $post ['cover_img_text'];
			}
			
			$img_arr = array();
			$txt_arr = array();
			//图文列表-图
			$card_info['img_text'] = array();
			if (isset ( $_POST['img_text']) && ! empty ( $_POST['img_text'] )) {
				$img_arr = $_POST['img_text'];
				$card_info['img_text'] = $img_arr;
			}
			
			//图文列表-文
			$card_info['img_text_title'] = array();
			if (isset ( $_POST['img_text_title'] ) && ! empty ( $_POST['img_text_title'] )) {
				$txt_arr = $_POST['img_text_title'];
				$card_info['img_text_title'] = $txt_arr;
			}
			

			
			
			//营销入口
			$card_info['custom_url_name'] = '';
			if (isset ( $post ['custom_url_name'] ) && ! empty ( $post ['custom_url_name'] )) {
				$card_info['custom_url_name'] = $post ['custom_url_name'];
			}
			$card_info['custom_url_sub_title'] = '';
			if (isset ( $post ['custom_url_sub_title'] ) && ! empty ( $post ['custom_url_sub_title'] )) {
				$card_info['custom_url_sub_title'] = $post ['custom_url_sub_title'];
			}
			$card_info['custom_url'] = '';
			if (isset ( $post ['custom_url'] ) && ! empty ( $post ['custom_url'] )) {
				$card_info['custom_url'] = $post ['custom_url'];
			}
			
			$card_info['promotion_url_name'] = '';
			if (isset ( $post ['promotion_url_name'] ) && ! empty ( $post ['promotion_url_name'] )) {
				$card_info['promotion_url_name'] = $post ['promotion_url_name'];
			}
			$card_info['promotion_url'] = '';
			if (isset ( $post ['promotion_url'] ) && ! empty ( $post ['promotion_url'] )) {
				$card_info['promotion_url'] = $post ['promotion_url'];
			}
		
			
			
			
		
 			//var_dump($card_info);
			$merchant_name = $cardCouponsC -> getMerchantName($merchant_id); //获取商户名
			$merchant_logo = $cardCouponsC -> getMerchantLogo($merchant_id); //获取商户Logo
			$count_store = $cardCouponsC -> getCountStore($merchant_id); //商户对应的总门店数
			$merchant_short_name = $cardCouponsC -> getMerchantShortName($merchant_id); //获取商户简称
			$adminC = new AdminC();
			$manage_arr = $adminC->getManageArr($merchant_id);
				
			//获取未分组的门店
			$re = json_decode($adminC -> getMerchantStoreWithoutMamagement($merchant_id));
			if($re -> status == ERROR_NONE){
				$store_list = $re -> data;
			}
			
			
			
			$result = $cardCouponsC->cardCouponsCreate($card_info,$merchant_id);
				
			$result = json_decode ( $result, true );
			//var_dump($result);exit();
			if ($result ['status'] == ERROR_NONE) {
				$res = 1;
				$this->render('createcoupon',array ('start_days_arr' => $start_days_arr,
				'effective_days_arr' => $effective_days_arr,
				'coupon_type' => $coupon_type,
				'merchant_name' => $merchant_name,
				'merchant_logo' => $merchant_logo,
				'count_store' => $count_store,
				'merchant_short_name'=>$merchant_short_name,
				'manage_arr'=>$manage_arr,
    	    	'store_list' => $store_list,
				'result' => $res));
// 				if(isset($result ['errcode'])){			
// 					if($result ['errcode'] == 0){						
// 					}
// 				}else{
// 					$this->redirect ( array ('CouponsList' ) );
// 				}
			} else if ($result['status'] == ERROR_PARAMETER_MISS){
				$res = 2;
				$this->render('createcoupon',array ('start_days_arr' => $start_days_arr,
				'effective_days_arr' => $effective_days_arr,
				'coupon_type' => $coupon_type,
				'merchant_name' => $merchant_name,
				'merchant_logo' => $merchant_logo,
				'count_store' => $count_store,
				'merchant_short_name'=>$merchant_short_name,
				'manage_arr'=>$manage_arr,
    	    	'store_list' => $store_list,
				'result' => $res));
				
			}else {
				$res = 3;
				$this->render('createcoupon',array ('start_days_arr' => $start_days_arr,
				'effective_days_arr' => $effective_days_arr,
				'coupon_type' => $coupon_type,
				'merchant_name' => $merchant_name,
				'merchant_logo' => $merchant_logo,
				'count_store' => $count_store,
				'merchant_short_name'=>$merchant_short_name,
				'manage_arr'=>$manage_arr,
    	    	'store_list' => $store_list,
				'result' => $res));
			}

 		}


 		//获取商家基本信息
 		$merchant_name = $cardCouponsC -> getMerchantName($merchant_id); //获取商户名
 		$merchant_logo = $cardCouponsC -> getMerchantLogo($merchant_id); //获取商户Logo
 		$count_store = $cardCouponsC -> getCountStore($merchant_id); //商户对应的总门店数
 		$merchant_short_name = $cardCouponsC -> getMerchantShortName($merchant_id); //获取商户简称
 		
 		$adminC = new AdminC();
 		$manage_arr = $adminC->getManageArr($merchant_id);
 		//var_dump($manage_arr);
 		//获取未分组的门店
 		$re = json_decode($adminC -> getMerchantStoreWithoutMamagement($merchant_id));
 		if($re -> status == ERROR_NONE){
 			$store_list = $re -> data;
 		}
    	//var_dump($store_list);


		$res = 0;//弹窗返回结果
		//var_dump($merchant_name,$merchant_logo,$count_store,$merchant_short_name);
		$this->render('createcoupon',array (
				'start_days_arr' => $start_days_arr,
				'effective_days_arr' => $effective_days_arr,
				'coupon_type' => $coupon_type,
				'merchant_name' => $merchant_name,
				'merchant_logo' => $merchant_logo,
				'count_store' => $count_store,
				'merchant_short_name'=>$merchant_short_name,
				'manage_arr'=>$manage_arr,
    	    	'store_list' => $store_list,
				'result' => $res
		));
	}
	
	/**
	 * 卡券列表
	 */
	public function actionCouponList()
	{
		$list = array();
		$merchant_id = Yii::app()->session['merchant_id'];
	
		$search_title = '';
		if(isset($_GET['search_title'])){
			$search_title = $_GET['search_title'];
		}
		
		$search_status = '';
		$status_type = '';
		$search_status_arr = array();
		if(isset($_GET['Coupon']['status'])){
			$search_status_arr = explode('_',$_GET['Coupon']['status']);
			if ($search_status_arr[0] == 'wx'){
				$search_status = $search_status_arr[1];
				$status_type = '1';
			}else if ($search_status_arr[0] == 'tf'){
				$search_status = $search_status_arr[1];
				$status_type = '2';
			}
		}
		
		$search_type = '';
		if(isset($_GET['Coupon']['type'])){
			$search_type = $_GET['Coupon']['type'];
		}
		
		$cardCouponsC = new CardCouponsC();
		$result = $cardCouponsC -> getCouponList($merchant_id,$search_title,$search_status,$status_type,$search_type);
		$result = json_decode($result,true);
		if($result['status'] == ERROR_NONE){
			$list = $result['data']['list'];
		}
		//删除红包
		unset($GLOBALS['COUPON_TYPE'][COUPON_TYPE_REDENVELOPE]);
		
		$status_arr = array('wx'=>$GLOBALS['WX_CHECK'],'tf'=>$GLOBALS['COUPON_RELEASE_STATUS']);
		
		$this->render('couponlist',array(
				'list'=>$list,
				'coupon_type' => $GLOBALS['COUPON_TYPE'],
				'status_arr' => $status_arr,
				'pages'=>$cardCouponsC->page
		));
	}
	
	/**
	 * 卡券编辑
	 */
	public function actionCouponEdit($coupons_id)
	{
		$start_days_arr = array(); //领取后几天生效 当天代表0最高90天
		$effective_days_arr = array(); //有效天数最少1天最多90天
		for($i=0;$i<=90;$i++){
			if ($i==0){
				$start_days_arr[0] = '当';
			}else {
				$start_days_arr[$i] = $i;
			}
		}
		for($i=1;$i<=90;$i++){
			$effective_days_arr[$i] = $i;
		}
		$coupon_type = array ();
		$coupon_type ['2'] = '代金券';
		$coupon_type ['3'] = '折扣券';
		$coupon_type ['4'] = '兑换券';
		
		$merchant_id = Yii::app()->session['merchant_id'];
		$cardCouponsC = new CardCouponsC();
		$card_info = array();
		if (!empty($_POST)){
			//var_dump($_POST);
			$post = $_POST['Coupons'];
			//商户简称
			$card_info['merchant_short_name'] = '';
			if (isset ( $post ['merchant_short_name'] ) && ! empty ( $post ['merchant_short_name'] )) {
				$card_info['merchant_short_name'] = $post ['merchant_short_name'];
			}
			//商户logo
			$card_info['logo'] = '';
			if (isset ( $post ['logo'] ) && ! empty ( $post ['logo'] )) {
				$card_info['logo'] = $post ['logo'];
			}
			//券类型
			$card_info['type'] = '';
			if (isset ( $post ['type'] )) {
				$card_info['type'] = $post ['type'];
			}
		
			//券颜色
			$card_info['color'] = '';
			if(isset($post['color']) && !empty($post['color'])){
				$card_info['color'] = $post['color'];
			}
				
			//券标题
			$card_info['title'] = '';
			if (isset ( $post ['title'] ) && ! empty ( $post ['title'] )) {
				$card_info['title'] = $post ['title'];
			}
		
			//副标题
			$card_info['vice_title'] = '';
			if (isset ( $post ['vice_title'] ) && ! empty ( $post ['vice_title'] )) {
				$card_info['vice_title'] = $post ['vice_title'];
			}
		
			//券折扣
			$card_info['discount'] = '';
			if (isset ( $post ['discount'] ) && ! empty ( $post ['discount'] )) {
				$card_info['discount'] = $post ['discount'];
			}
		
			//提示操作
			$card_info['prompt'] = '';
			if (isset ( $post ['prompt'] ) && ! empty ( $post ['prompt'] )) {
				$card_info['prompt'] = $post ['prompt'];
			}
		
			//用户是否可以分享领取链接 1可以 2不可以
			$card_info['if_share'] = '';
			if (isset ( $post ['if_share'] ) && ! empty ( $post ['if_share'] )) {
				$card_info['if_share'] = $post ['if_share'];
			}
		
			//可否转增其他好友 1 能 2不能
			$card_info['if_give'] = '';
			if (isset ( $post ['if_give'] ) && ! empty ( $post ['if_give'] )) {
				$card_info['if_give'] = $post ['if_give'];
			}
				
		
			//发放数量
// 			$card_info['num'] = '0';
// 			if (isset ( $post ['num'] ) && ! empty ( $post ['num'] )) {
// 				$card_info['num'] = $post ['num'];
// 			}
		
			//有效时间类型1固定时间 2相对时间
			$card_info['time_type'] = '';
			if (isset ( $post ['time_type'] ) && ! empty ( $post ['time_type'] )) {
				$card_info['time_type'] = $post ['time_type'];
			}
		
			//固定时间时的有效开始时间       固定时间有效结束时间
			$card_info['start_time'] = '';
			$card_info['end_time'] = '';
			if (isset ( $post ['time'] ) && ! empty ( $post ['time'] )) {
				$arr = explode('-', $post ['time']);
				$card_info['start_time'] = $arr[0];
				$card_info['end_time'] = $arr[1];
			}
		
			//领取后几天生效 当天代表0最高90天
			$card_info['start_days'] = '';
			if (isset ( $post ['start_days'] ) && ! empty ( $post ['start_days'] )) {
				$card_info['start_days'] = $post ['start_days'];
			}
		
			//有效天数最少1天最多90天
			$card_info['effective_days'] = '';
			if (isset ( $post ['effective_days'] ) && ! empty ( $post ['effective_days'] )) {
				$card_info['effective_days'] = $post ['effective_days'];
			}
				
			//可用时段
			$card_info['available_time'] = '';
			$available_time = array();
			$day = array();
			$card_info['available_time_type'] = '';
			$card_info['available_time_type'] = $post['available_time'];
			if($post['available_time'] = '2'){
				if (isset ( $post ['day'] ) && ! empty ( $post ['day'] )) {
					$day = $post ['day'];
					if (! empty ( $post ['begin_hour'] ) && !empty( $post ['end_hour'] )) {
						if(in_array("周一",$day)){
							$available_time[] = array('type'=>'MONDAY','begin_hour'=>$post ['begin_hour'],'end_hour'=>$post ['end_hour']);
						}
						if (in_array("周二",$day)){
							$available_time[] = array('type'=>'TUESDAY','begin_hour'=>$post ['begin_hour'],'end_hour'=>$post ['end_hour']);
						}
						if (in_array("周三",$day)){
							$available_time[] = array('type'=>'WEDNESDAY','begin_hour'=>$post ['begin_hour'],'end_hour'=>$post ['end_hour']);
						}
						if (in_array("周四",$day)){
							$available_time[] = array('type'=>'THURSDAY','begin_hour'=>$post ['begin_hour'],'end_hour'=>$post ['end_hour']);
						}
						if (in_array("周五",$day)){
							$available_time[] = array('type'=>'FRIDAY','begin_hour'=>$post ['begin_hour'],'end_hour'=>$post ['end_hour']);
						}
						if (in_array("周六",$day)){
							$available_time[] = array('type'=>'SATURDAY','begin_hour'=>$post ['begin_hour'],'end_hour'=>$post ['end_hour']);
						}
						if (in_array("周日",$day)){
							$available_time[] = array('type'=>'SUNDAY','begin_hour'=>$post ['begin_hour'],'end_hour'=>$post ['end_hour']);
						}
					}else {
						if(in_array("周一",$day)){
							$available_time[] = array('type'=>'MONDAY');
						}
						if (in_array("周二",$day)){
							$available_time[] = array('type'=>'TUESDAY');
						}
						if (in_array("周三",$day)){
							$available_time[] = array('type'=>'WEDNESDAY');
						}
						if (in_array("周四",$day)){
							$available_time[] = array('type'=>'THURSDAY');
						}
						if (in_array("周五",$day)){
							$available_time[] = array('type'=>'FRIDAY');
						}
						if (in_array("周六",$day)){
							$available_time[] = array('type'=>'SATURDAY');
						}
						if (in_array("周日",$day)){
							$available_time[] = array('type'=>'SUNDAY');
						}
					}
					$card_info['available_time'] = $available_time;
				}
			}else{
				$card_info['available_time'] = '';
			}
		
				
				
			//兑换信息
			$card_info['gift_info'] = '';
			if (isset ( $post ['gift_info'] ) && ! empty ( $post ['gift_info'] )) {
				$card_info['gift_info'] = $post ['gift_info'];
			}
		
				
			//减免金额
			$card_info['money'] = '';
			if (isset ( $post ['reduce_cost'] ) && ! empty ( $post ['reduce_cost'] )) {
				$card_info['money'] = $post ['reduce_cost'];
			}
				
			//最低消费
			$card_info['least_cost'] = '';
			if (isset ( $post ['least_cost'] ) && ! empty ( $post ['least_cost'] )) {
				$card_info['least_cost'] = $post ['least_cost'];
			}
			$card_info['discount_rule'] = '';
			if (isset ( $post ['discount_rule'] ) && ! empty ( $post ['discount_rule'] )) {
				$card_info['discount_rule'] = $post ['discount_rule'];
			}
			$card_info['gift_rule'] = '';
			if (isset ( $post ['gift_rule'] ) && ! empty ( $post ['gift_rule'] )) {
				$card_info['gift_rule'] = $post ['gift_rule'];
			}
				
			//是否能与会员折扣同用1不能 2能
			$card_info['if_with_userdiscount'] = '';
			if (isset ( $post ['if_with_userdiscount'] ) && ! empty ( $post ['if_with_userdiscount'] )) {
				$card_info['if_with_userdiscount'] = $post ['if_with_userdiscount'];
			}
				
			//使用须知
			$card_info['use_illustrate'] = '';
			if (isset ( $post ['use_illustrate'] ) && ! empty ( $post ['use_illustrate'] )) {
				$card_info['use_illustrate'] = $post ['use_illustrate'];
			}
				
		
				
			//客服电话
			$card_info['tel'] = '';
			if (isset ( $post ['tel'] ) && ! empty ( $post ['tel'] )) {
				$card_info['tel'] = $post ['tel'];
			}
				
		
				
			//优惠说明
			$card_info['discount_illustrate'] = '';
			if (isset ( $post ['gift_info'] ) && ! empty ( $post ['gift_info'] )) {
				$card_info['discount_illustrate'] = $post ['gift_info'];
			}
				
			//使用限制
			$card_info['use_restriction'] = '';
			if (isset ( $post ['use_restriction'] ) && ! empty ( $post ['use_restriction'] )) {
				$card_info['use_restriction'] = $post ['use_restriction'];
			}
		
			//每个用户领取数量
			$card_info['receive_num'] = '1';
			if (isset ( $post ['receive_num'] ) && ! empty ( $post ['receive_num'] )) {
				$card_info['receive_num'] = $post ['receive_num'];
			}

			//核销渠道限制
			$card_info['use_channel'] = COUPONS_USE_CHANNEL_ALL;
			if (isset($post['channel1']) && isset($post['channel2'])) {
				$card_info['use_channel'] = COUPONS_USE_CHANNEL_ALL;
			}
			if (isset($post['channel1']) && !isset($post['channel2'])) {
				$card_info['use_channel'] = COUPONS_USE_CHANNEL_OFFLINE;
			}
			if (!isset($post['channel1']) && isset($post['channel2'])) {
				$card_info['use_channel'] = COUPONS_USE_CHANNEL_ONLINE;
			}
		
			//门店限制
			$card_info['store_limit'] = '';
			if (isset ( $post ['store_limit'] ) && ! empty ( $post ['store_limit'] )) {
				$card_info['store_limit'] = $post ['store_limit'];
			}
			//门店类型
			$card_info['store_limit_type'] = '';
			if (isset ( $_POST['applicable-store'] ) && ! empty ( $_POST['applicable-store'] )) {
				$card_info['store_limit_type'] = $_POST['applicable-store'];
			}
				
			//封面图片
			$card_info['cover_img'] = '';
			if (isset ( $post ['cover_img'] ) && ! empty ( $post ['cover_img'] )) {
				$card_info['cover_img'] = $post ['cover_img'];
			}
				
			//封面图片
			$card_info['cover_img_text'] = '';
			if (isset ( $post ['cover_img_text'] ) && ! empty ( $post ['cover_img_text'] )) {
				$card_info['cover_img_text'] = $post ['cover_img_text'];
			}
				
			$img_arr = array();
			$txt_arr = array();
			//图文列表-图
			$card_info['img_text'] = '';
			if (isset ( $_POST['img_text']) && ! empty ( $_POST['img_text'] )) {
				$img_arr = $_POST['img_text'];
				$card_info['img_text'] = $img_arr;
			}
				
			//图文列表-文
			$card_info['img_text_title'] = '';
			if (isset ( $_POST['img_text_title'] ) && ! empty ( $_POST['img_text_title'] )) {
				$txt_arr = $_POST['img_text_title'];
				$card_info['img_text_title'] = $txt_arr;
			}
				
		
				
				
			//营销入口
			$card_info['custom_url_name'] = '';
			if (isset ( $post ['custom_url_name'] ) && ! empty ( $post ['custom_url_name'] )) {
				$card_info['custom_url_name'] = $post ['custom_url_name'];
			}
			$card_info['custom_url_sub_title'] = '';
			if (isset ( $post ['custom_url_sub_title'] ) && ! empty ( $post ['custom_url_sub_title'] )) {
				$card_info['custom_url_sub_title'] = $post ['custom_url_sub_title'];
			}
			$card_info['custom_url'] = '';
			if (isset ( $post ['custom_url'] ) && ! empty ( $post ['custom_url'] )) {
				$card_info['custom_url'] = $post ['custom_url'];
			}
				
			$card_info['promotion_url_name'] = '';
			if (isset ( $post ['promotion_url_name'] ) && ! empty ( $post ['promotion_url_name'] )) {
				$card_info['promotion_url_name'] = $post ['promotion_url_name'];
			}
			$card_info['promotion_url'] = '';
			if (isset ( $post ['promotion_url'] ) && ! empty ( $post ['promotion_url'] )) {
				$card_info['promotion_url'] = $post ['promotion_url'];
			}
				
			 			//var_dump($card_info);
		//获取商家基本信息
		$merchant_name = $cardCouponsC -> getMerchantName($merchant_id); //获取商户名
		$merchant_logo = $cardCouponsC -> getMerchantLogo($merchant_id); //获取商户Logo
		$count_store = $cardCouponsC -> getCountStore($merchant_id); //商户对应的总门店数
		$merchant_short_name = $cardCouponsC -> getMerchantShortName($merchant_id); //获取商户简称
		
		$adminC = new AdminC();
		$manage_arr = $adminC->getManageArr($merchant_id);
		
		//获取未分组的门店
		$re = json_decode($adminC -> getMerchantStoreWithoutMamagement($merchant_id));
		if($re -> status == ERROR_NONE){
			$store_list = $re -> data;
		}
		
		$model = Coupons::model()->findByPk($coupons_id);
		//var_dump($model);
		
		$weeks = array('MONDAY'=>'周一','TUESDAY'=>'周二','WEDNESDAY'=>'周三','THURSDAY'=>'周四','FRIDAY'=>'周五','SATURDAY'=>'周六','SUNDAY'=>'周日');
		$interval_arr = array();
		$interval = '';
		$hour = '';
		$model_info['interval'] = '';
		$model_info['begin_hour'] = '';
		$model_info['end_hour'] = '';
		if(!empty($model['use_time_interval'])){
			$interval_json = json_decode($model['use_time_interval'],true);
			if(is_array($interval_json)){
				foreach ($interval_json as $k=>$v){
					$interval_arr[] = $weeks[$v['type']];
					$model_info['interval']=$interval_arr;
					if(isset($v['begin_hour']) && isset($v['end_hour'])){
						$hour = $v['begin_hour'].':00 至'.$v['end_hour'].':00';
						$model_info['begin_hour'] = $v['begin_hour'];
						$model_info['end_hour'] = $v['end_hour'];
					}
				}
			
			foreach ($interval_arr as $k=>$v){
				$interval = $interval.'、'.$v;
			}
			$interval = ltrim($interval,'、');
			}
		}
		

		$model_info['cover_img_text']='';
		$model_info['abstract']='';
		$abstract = array();
		$abstract['cover_img'] = '';
		$abstract['cover_img_text'] = '';
		
		if (!empty($model['cover_img']) && !empty($model['cover_title'])){
			$abstract['cover_img'] = $model['cover_img'];
			$abstract['cover_img_text'] = $model['cover_title'];
			
		}
		if(!empty($model['image_text'])){
			$image_text_json = json_decode($model['image_text'],true);
			foreach ($image_text_json as $k=> $v){
				if(isset($v['image_url']) && !empty($v['image_url']) ){
					$abstract['image_url'][] = $v['image_url'];
				}
				if(isset($v['text']) && !empty($v['text']) ){
					$abstract['text'][] = $v['text'];
				}
			}
			
		}
		$model_info['abstract'] = $abstract;
		
		$model_info['custom_url_name'] = '';
		$model_info['custom_url_sub_title'] = '';
		$model_info['custom_url'] = '';
		$model_info['promotion_url_name'] = '';
		$model_info['promotion_url'] = '';
		
		//营销入口
		if(!empty($model->marketing_entrance)){
			$marketing_entrance = json_decode($model->marketing_entrance);
			if (!empty($marketing_entrance->custom_url_name)){
				$model_info['custom_url_name'] = urldecode($marketing_entrance->custom_url_name);
			}
			if (!empty($marketing_entrance->custom_url_sub_title)){
				$model_info['custom_url_sub_title'] =urldecode($marketing_entrance->custom_url_sub_title);
			}
			if (!empty($marketing_entrance->custom_url)){
				$model_info['custom_url'] = $marketing_entrance->custom_url;
			}
			if (!empty($marketing_entrance->promotion_url_name)){
				$model_info['promotion_url_name'] = urldecode($marketing_entrance->promotion_url_name);
			}
			if (!empty($marketing_entrance->promotion_url)){
				$model_info['promotion_url'] = $marketing_entrance->promotion_url;
			}
				
		}
		
		//获取已选门店信息
		$model_info['store_info'] = '';
		if(!empty($model['store_limit'])){
			$model_info['store_info'] = $cardCouponsC->getStorelimit($model['store_limit']);		
		}

			//文本框换行问题
			$model_info['use_illustrate'] = '';
			$model_info['gift_info'] = '';
			if(!empty($model['use_illustrate'])){
				$model_info['use_illustrate'] = str_replace(PHP_EOL, '', $model['use_illustrate']);
				//var_dump($model_info['use_illustrate']);
			}

			if(!empty($model['discount_illustrate'])){
				$model_info['gift_info'] = str_replace(PHP_EOL, '', $model['discount_illustrate']);
			}
		
		
   		$result = $cardCouponsC->cardCouponsEdit($card_info,$coupons_id);
			
			$result = json_decode ( $result, true );
			//var_dump($result);exit();
			if ($result ['status'] == ERROR_NONE) {
				$res = 1;
				$this->render('couponedit',array (
				'start_days_arr' => $start_days_arr,
				'effective_days_arr' => $effective_days_arr,
				'coupon_type' => $coupon_type,
				'merchant_name' => $merchant_name,
				'merchant_logo' => $merchant_logo,
				'count_store' => $count_store,
				'merchant_short_name'=>$merchant_short_name,
				'manage_arr'=>$manage_arr,
				'store_list' => $store_list,
				'model' => $model,
				'model_info' => $model_info,
				'interval' => $interval,
				'hour'=> $hour,
				'result'=> $res));

			} else if ($result['status'] == ERROR_PARAMETER_MISS){
				$res = 2;
				$this->render('couponedit',array (
				'start_days_arr' => $start_days_arr,
				'effective_days_arr' => $effective_days_arr,
				'coupon_type' => $coupon_type,
				'merchant_name' => $merchant_name,
				'merchant_logo' => $merchant_logo,
				'count_store' => $count_store,
				'merchant_short_name'=>$merchant_short_name,
				'manage_arr'=>$manage_arr,
				'store_list' => $store_list,
				'model' => $model,
				'model_info' => $model_info,
				'interval' => $interval,
				'hour'=> $hour,
				'result'=> $res));
				
			}else {
				$res = 3;
				$this->render('couponedit',array (
				'start_days_arr' => $start_days_arr,
				'effective_days_arr' => $effective_days_arr,
				'coupon_type' => $coupon_type,
				'merchant_name' => $merchant_name,
				'merchant_logo' => $merchant_logo,
				'count_store' => $count_store,
				'merchant_short_name'=>$merchant_short_name,
				'manage_arr'=>$manage_arr,
				'store_list' => $store_list,
				'model' => $model,
				'model_info' => $model_info,
				'interval' => $interval,
				'hour'=> $hour,
				'result'=> $res));
			}
			
		
		}
		//获取商家基本信息
		$merchant_name = $cardCouponsC -> getMerchantName($merchant_id); //获取商户名
		$merchant_logo = $cardCouponsC -> getMerchantLogo($merchant_id); //获取商户Logo
		$count_store = $cardCouponsC -> getCountStore($merchant_id); //商户对应的总门店数
		$merchant_short_name = $cardCouponsC -> getMerchantShortName($merchant_id); //获取商户简称
		
		$adminC = new AdminC();
		$manage_arr = $adminC->getManageArr($merchant_id);
		
		//获取未分组的门店
		$re = json_decode($adminC -> getMerchantStoreWithoutMamagement($merchant_id));
		if($re -> status == ERROR_NONE){
			$store_list = $re -> data;
		}
		
		$model = Coupons::model()->findByPk($coupons_id);
		//var_dump($model);
		
		$weeks = array('MONDAY'=>'周一','TUESDAY'=>'周二','WEDNESDAY'=>'周三','THURSDAY'=>'周四','FRIDAY'=>'周五','SATURDAY'=>'周六','SUNDAY'=>'周日');
		$interval_arr = array();
		$interval = '';
		$hour = '';
		$model_info['interval'] = '';
		if(!empty($model['use_time_interval'])){
			$interval_json = json_decode($model['use_time_interval'],true);
			if(is_array($interval_json)){
				foreach ($interval_json as $k=>$v){
					$interval_arr[] = $weeks[$v['type']];
					$model_info['interval']=$interval_arr;
					if(isset($v['begin_hour']) && isset($v['end_hour'])){
						$hour = $v['begin_hour'].':00 至'.$v['end_hour'].':00';
						$model_info['begin_hour'] = $v['begin_hour'];
						$model_info['end_hour'] = $v['end_hour'];
					}
				}
			
			foreach ($interval_arr as $k=>$v){
				$interval = $interval.'、'.$v;
			}
			$interval = ltrim($interval,'、');
			}
		}

		$model_info['cover_img_text']='';
		$model_info['abstract']='';
		$abstract = array();
		$abstract['cover_img'] = '';
		$abstract['cover_img_text'] = '';
		
		if (!empty($model['cover_img']) && !empty($model['cover_title'])){
			$abstract['cover_img'] = $model['cover_img'];
			$abstract['cover_img_text'] = $model['cover_title'];
			
		}
		//详情页面中的图文展示列
		$model_info['text_image_list'] = '';
		$text_image_list = array();
		if (!empty($model['image_text'])){
			$model2 = json_decode($model['image_text'],true);
			$arr = array();
			foreach ($model2 as $k => $v)
			{
				if (!empty($v['image_url'])){
					$arr['image_url'] = $v['image_url'];
				}else {
					$arr['image_url'] = '';
				}
				if (!empty($v['text'])){
					$arr['text'] = urldecode($v['text']);
				}else {
					$arr['text'] = '';
				}
				$text_image_list[] = $arr;
			}
			$model_info['text_image_list'] = $text_image_list;
		}
		//var_dump($model_info['text_image_list']);
		$model_info['abstract'] = $abstract;
		
		$model_info['custom_url_name'] = '';
		$model_info['custom_url_sub_title'] = '';
		$model_info['custom_url'] = '';
		$model_info['promotion_url_name'] = '';
		$model_info['promotion_url'] = '';
		
		//营销入口
		if(!empty($model->marketing_entrance)){
			$marketing_entrance = json_decode($model->marketing_entrance);
			if (!empty($marketing_entrance->custom_url_name)){
				$model_info['custom_url_name'] = urldecode($marketing_entrance->custom_url_name);
			}
			if (!empty($marketing_entrance->custom_url_sub_title)){
				$model_info['custom_url_sub_title'] =urldecode($marketing_entrance->custom_url_sub_title);
			}
			if (!empty($marketing_entrance->custom_url)){
				$model_info['custom_url'] = $marketing_entrance->custom_url;
			}
			if (!empty($marketing_entrance->promotion_url_name)){
				$model_info['promotion_url_name'] = urldecode($marketing_entrance->promotion_url_name);
			}
			if (!empty($marketing_entrance->promotion_url)){
				$model_info['promotion_url'] = $marketing_entrance->promotion_url;
			}
				
		}

		//文本框换行问题
		$model_info['use_illustrate'] = '';
		$model_info['gift_info'] = '';
		if(!empty($model['use_illustrate'])){
			$model_info['use_illustrate'] = str_replace(array("\r\n", "\r", "\n"), "", $model['use_illustrate']);
			//var_dump($model_info['use_illustrate']);
		}

		if(!empty($model['discount_illustrate'])){
			$model_info['gift_info'] = str_replace(array("\r\n", "\r", "\n"), "", $model['discount_illustrate']);
		}


		//获取已选门店信息
		$model_info['store_info'] = '';
		if(!empty($model['store_limit'])){
			$model_info['store_info'] = $cardCouponsC->getStorelimit($model['store_limit']);		
		}
		$res = 0;
		//var_dump($model_info['store_info']);
		//var_dump($merchant_name,$merchant_logo,$count_store,$merchant_short_name);
		$this->render('couponedit',array (
				'start_days_arr' => $start_days_arr,
				'effective_days_arr' => $effective_days_arr,
				'coupon_type' => $coupon_type,
				'merchant_name' => $merchant_name,
				'merchant_logo' => $merchant_logo,
				'count_store' => $count_store,
				'merchant_short_name'=>$merchant_short_name,
				'manage_arr'=>$manage_arr,
				'store_list' => $store_list,
				'model' => $model,
				'model_info' => $model_info,
				'interval' => $interval,
				'hour'=> $hour,
				'result'=> $res
		));
	}
	
	/**
	 * 卡券详情
	 */
	public function actionCouponDetail($coupons_id)
	{
		$merchant_id = Yii::app()->session['merchant_id'];
		$cardCouponsC = new CardCouponsC();
		//获取商家基本信息
		$merchant_name = $cardCouponsC -> getMerchantName($merchant_id); //获取商户名
		$merchant_logo = $cardCouponsC -> getMerchantLogo($merchant_id); //获取商户Logo
		$count_store = $cardCouponsC -> getCountStore($merchant_id); //商户对应的总门店数
		$merchant_short_name = $cardCouponsC -> getMerchantShortName($merchant_id); //获取商户简称
		
		$adminC = new AdminC();
		$manage_arr = $adminC->getManageArr($merchant_id);
		
		//获取未分组的门店
		$re = json_decode($adminC -> getMerchantStoreWithoutMamagement($merchant_id));
		if($re -> status == ERROR_NONE){
			$store_list = $re -> data;
		}
		
		$model = Coupons::model()->findByPk($coupons_id);
		//var_dump($model);
		
		$weeks = array('MONDAY'=>'周一','TUESDAY'=>'周二','WEDNESDAY'=>'周三','THURSDAY'=>'周四','FRIDAY'=>'周五','SATURDAY'=>'周六','SUNDAY'=>'周日');
		$interval_arr = array();
		$interval = '';
		$hour = '';
		$model_info['interval'] = '';
		if(!empty($model['use_time_interval'])){
			$interval_json = json_decode($model['use_time_interval'],true);
			if(is_array($interval_json)){
				foreach ($interval_json as $k=>$v){
					$interval_arr[] = $weeks[$v['type']];
					$model_info['interval']=$interval_arr;
					if(isset($v['begin_hour']) && isset($v['end_hour'])){
						$hour = $v['begin_hour'].':00 至'.$v['end_hour'].':00';
						$model_info['begin_hour'] = $v['begin_hour'];
						$model_info['end_hour'] = $v['end_hour'];
					}
				}
			
			foreach ($interval_arr as $k=>$v){
				$interval = $interval.'、'.$v;
			}
			$interval = ltrim($interval,'、');
			}
		}
		
		
		
		$model_info['abstract']='';
		$abstract = array();
		
		if (!empty($model['cover_img']) && !empty($model['cover_title'])){
			$abstract['cover_img'] = $model['cover_img'];
			$abstract['cover_title'] = $model['cover_title'];
			$model_info['abstract'] = $abstract;
		}

		
		 
		//详情页面中的图文展示列
		$model_info['text_image_list'] = '';
		$text_image_list = array();
		if (!empty($model['image_text'])){
			$model2 = json_decode($model['image_text'],true);
			$arr = array();
			foreach ($model2 as $k => $v)
			{
				if (!empty($v['image_url'])){
					$arr['image_url'] = $v['image_url'];
				}else {
					$arr['image_url'] = '';
				}
				if (!empty($v['text'])){
					$arr['text'] = urldecode($v['text']);
				}else {
					$arr['text'] = '';
				}
				$text_image_list[] = $arr;
			}
			$model_info['text_image_list'] = $text_image_list;
		}
		//var_dump($model_info['text_image_list']);
		$model_info['custom_url_name'] = '';
		$model_info['custom_url_sub_title'] = '';
		$model_info['custom_url'] = '';
		$model_info['promotion_url_name'] = '';
		$model_info['promotion_url'] = '';
		
		//营销入口
		if(!empty($model->marketing_entrance)){
			$marketing_entrance = json_decode($model->marketing_entrance);
			if (!empty($marketing_entrance->custom_url_name)){
				$model_info['custom_url_name'] = urldecode($marketing_entrance->custom_url_name);
			}
			if (!empty($marketing_entrance->custom_url_sub_title)){
				$model_info['custom_url_sub_title'] =urldecode($marketing_entrance->custom_url_sub_title);
			}
			if (!empty($marketing_entrance->custom_url)){
				$model_info['custom_url'] = $marketing_entrance->custom_url;
			}
			if (!empty($marketing_entrance->promotion_url_name)){
				$model_info['promotion_url_name'] = urldecode($marketing_entrance->promotion_url_name);
			}
			if (!empty($marketing_entrance->promotion_url)){
				$model_info['promotion_url'] = $marketing_entrance->promotion_url;
			}
				
		}
		
		//获取已选门店信息
		$model_info['store_info'] = '';
		if(!empty($model['store_limit'])){
			$model_info['store_info'] = $cardCouponsC->getStorelimit($model['store_limit']);
		}
		//var_dump($interval);
		//var_dump($model_info['store_info']);
		//var_dump($merchant_name,$merchant_logo,$count_store,$merchant_short_name);
		$this->render('coupondetail',array (


				'merchant_name' => $merchant_name,
				'merchant_logo' => $merchant_logo,
				'count_store' => $count_store,
				'merchant_short_name'=>$merchant_short_name,
				'manage_arr'=>$manage_arr,
				'store_list' => $store_list,
				'model' => $model,
				'model_info' => $model_info,
				'interval' => $interval,
				'hour'=> $hour
		));
	}
	
	/**
	 * 卡券核销列表
	 */
	public function actionVerification()
	{
		$merchant_id = Yii::app()->session['merchant_id'];
		$search = '';

		if (isset($_GET) && !empty($_GET)) {
			$post = $_GET['Coupon'];
			
			if (!empty($post['type'])) {
				$type = $post['type'];
				$search['type'] = $type;
			}

			if (!empty($post['time'])) {
				$time = $post['time'];
				$search['time'] = $time;
			}

			if (!empty($post['store'])) {
				$store = $post['store'];
				$search['store'] = $store;
			}
			
			if (!empty($post['code'])) {
				$code = $post['code'];
				$search['code'] = $code;
			}
		}

		$cardCouponsC = new CardCouponsC();
		$result = json_decode($cardCouponsC->getVerification($merchant_id, $search), true);
		
		//获取商户门店
		$merchant = new MerchantC();
		$store_lists = $merchant->getStoreArrW($merchant_id);

		//删除红包
		unset($GLOBALS['COUPON_TYPE'][COUPON_TYPE_REDENVELOPE]);
		
		$this->render('verification-record', array(
			'list' => $result,
			'coupon_type' => $GLOBALS['COUPON_TYPE'],
			'store_lists' => $store_lists,
			'pages' => $cardCouponsC->page
		));
	}

	/**
	 * 导出表格
	 */
	public function actionExportExcel()
	{
		$merchant_id = Yii::app()->session['merchant_id'];
		$search = '';

		if (isset($_GET) && !empty($_GET)) {
			$post = $_GET['Coupon'];

			if (!empty($post['type'])) {
				$type = $post['type'];
				$search['type'] = $type;
			}

			if (!empty($post['time'])) {
				$time = $post['time'];
				$search['time'] = $time;
			}

			if (!empty($post['store'])) {
				$store = $post['store'];
				$search['store'] = $store;
			}

			if (!empty($post['code'])) {
				$code = $post['code'];
				$search['code'] = $code;
			}
		}

		$cardCouponsC = new CardCouponsC();
		$cardCouponsC->exportExcel($merchant_id, $search);
	}

	/**
	 * 卡券核销详情
	 */
	public function actionVerificationDetail()
	{
		$id = $_GET['id'];
		$cardCouponsC = new CardCouponsC();
		$result = json_decode($cardCouponsC->getVerificationDetail($id), true);
		if ($result['status'] == ERROR_NONE) {
			$data = $result['data'][0];
		}

		$this->render('verification-record-detail', array(
			'data' => $data
		));
	}
	
	/**
	 * 卡券核销列表
	 */
	public function actionVerificationMode()
	{
		$this->render('verification-mode');
	}
	
	/**
	 * 卡券报表
	 */
	public function actionCouponCount()
	{
		$merchant_id = Yii::app()->session['merchant_id'];

		$cardCouponsC = new CardCouponsC();
		
		//获取商户所有优惠券的浏览量
		$res_coupon_pv = json_decode($cardCouponsC->getCouponPv($merchant_id), true);
		if ($res_coupon_pv['status'] == ERROR_NONE) {
			$total_pv = $res_coupon_pv['data'];
		}

		$begin_date = date('Y-m-d', strtotime('-62 days'));
		$end_date = date('Y-m-d', strtotime('-1 days'));

		//获取该商户下微信所有优惠券的浏览量（无条件）
		$wx_coupon = $cardCouponsC->getWxMerchantCardInfo($begin_date, $end_date);
		$all_view_cnt = 0;

		if (!empty($wx_coupon)) {
			if (isset($wx_coupon['list']) && !empty($wx_coupon['list'])) {
				foreach ($wx_coupon['list'] as $v) {
					$all_view_cnt += $v['view_cnt'];
				}
			}
		}

		$total_pv = $total_pv + $all_view_cnt;
		
		//获取商户所有优惠券的领取量
		$res_coupon_receive = json_decode($cardCouponsC->getCouponReceiveNum($merchant_id), true);
		if ($res_coupon_receive['status'] == ERROR_NONE) {
			$total_receive = $res_coupon_receive['data'];
		}
		
		//获取商户所有优惠券的使用量
		$res_coupon_use = json_decode($cardCouponsC->getCouponUseNum($merchant_id), true);
		if ($res_coupon_use['status'] == ERROR_NONE) {
			$total_use = $res_coupon_use['data'];
		}
		
		$search = '';

		//搜索条件
		if (isset($_GET['Coupon']) && !empty($_GET['Coupon'])) {
			$post = $_GET['Coupon'];

			if (!empty($post['type'])) {
				$type = $post['type'];
				$search['type'] = $type;
			}

			if (!empty($post['time'])) {
				$time = $post['time'];
				$search['time'] = $time;
			}
		}

		//默认时间段
		if (!isset($_GET['Coupon']['time']) || empty($_GET['Coupon']['time'])) {
			$search['time'] = date('Y-m-d', strtotime('-7 days')) . ' - ' . date('Y-m-d', strtotime('-1 days'));
		}
		
		$res_coupon_data = json_decode($cardCouponsC->getCouponData($merchant_id, $search), true);

		$times = explode(' - ', $search['time']);
		$begin_date = $times[0];
		$end_date = $times[1];

		//该商户下微信所有优惠券的浏览量（有条件）
		//如果查询时间区域大于62天
		if (strtotime($end_date) - strtotime($begin_date) > 86400 * 61) {
			$begin_date = date('Y-m-d', (strtotime($end_date) - 86400 * 61));
		}

		$wx_coupon = $cardCouponsC->getWxMerchantCardInfo($begin_date, $end_date);
		if (!empty($wx_coupon)) {
			if (isset($wx_coupon['list']) && !empty($wx_coupon['list'])) {
				//取出有数据的日期
				foreach ($wx_coupon['list'] as $v) {
					$arr_date[] = $v['ref_date'];
				}
			}
		}

		//添加列表的微信浏览量
		if (!empty($res_coupon_data) && !empty($arr_date)) {
			foreach ($res_coupon_data as &$v) {
				$key = array_search(date('Y-m-d', strtotime($v['date'])), $arr_date);
				$v['total_browse_num'] += $wx_coupon['list'][$key]['view_cnt'];
			}
		}
		
		//对时间进行处理，添加图表的日期
		$start_date = strtotime($times[0]);
		$end_date = strtotime($times[1]);

		while ($start_date <= $end_date) {
			$img_data[date('Y-m-d', $start_date)]['new_browse_num'] = 0;

			//添加微信优惠券当天浏览量
			if (!empty($arr_date)) {
				$key  = array_search(date('Y-m-d', $start_date), $arr_date);
				$img_data[date('Y-m-d', $start_date)]['new_browse_num'] += $wx_coupon['list'][$key]['view_cnt'];
			}

			$img_data[date('Y-m-d', $start_date)]['new_receive_num'] = 0;
			$img_data[date('Y-m-d', $start_date)]['new_use_num'] = 0;
			$start_date += 86400;
		}
		
		//获取全部信息
		$res_coupon_alldata = json_decode($cardCouponsC->getCouponAllData($merchant_id, $search), true);

		foreach ($img_data as $key => $v1) {
			foreach ($res_coupon_alldata as $v2) {
				if (date('Y-m-d', strtotime($v2['date'])) == $key) {
					$img_data[$key]['new_browse_num'] += $v2['new_browse_num'];
					$img_data[$key]['new_receive_num'] += $v2['new_receive_num'];
					$img_data[$key]['new_use_num'] += $v2['new_use_num'];
				}
			}
		}
		
		unset($GLOBALS['COUPON_TYPE'][COUPON_TYPE_REDENVELOPE]);
		$this->render('coupon-count', array(
			'list' => $res_coupon_data,
			'pages' => $cardCouponsC->page,
			'total_pv' => $total_pv,
			'total_receive' => $total_receive,
			'total_use' => $total_use,
			'coupon_type' => $GLOBALS['COUPON_TYPE'],
			'img_data' => $img_data
		));
	}
	
	
	
	/**
	 * 删除操作
	 * $coupons_id  券id
	 */
	public function actionDelCoupons($coupons_id)
	{
		$cardCouponsC = new CardCouponsC();
		$result = $cardCouponsC ->delCoupons($coupons_id);
		$result = json_decode($result,true);
		if($result['status'] == ERROR_NONE){
			$this->redirect(array('CouponList'));
		}
	}
	
	public function actionimageurl()
	{
		$cardCouponsC = new CardCouponsC();
		$result = $cardCouponsC->getImageUrl(11111);
		//$cardCouponsC->TestCard();
		//var_dump($result);
	}
	
	public function actionCardInfo()
	{
		$cardCouponsC = new CardCouponsC();
		if (!empty($_GET))
		{
			$card_id = $_GET['id'];
			$result = $cardCouponsC->searchCard($card_id);
			return $result;
		}
		
	}
	
	/**
	 * 卡券投放
	 * 
	 */
	public function actionlunchCoupons()
	{
		$cardCouponsC = new CardCouponsC();
// 		var_dump($_POST);
// 		if(!empty($_POST['couponid'])){
// 			if(isset($_POST['cp-type']) && $_POST['cp-type'] == 2 && $_POST['launch-cp-type'] == 'WQ2'){
// 				//$cardCouponsC->createQr($_POST['WQ_link'],$_POST['couponid']);
// 			}
// 		}
		
		$coupons_code = '';
		if(isset($_POST['coupons_code'])){
			$coupons_code = $_POST['coupons_code'];
		}
		$cardCouponsC = new CardCouponsC();
		$result = $cardCouponsC -> changeReleaseStatus($coupons_code);
		$result = json_decode($result,true);
		if($result['status'] == ERROR_NONE){
			echo 'success';
		}else{
			echo 'fail';
		}
		
	}
	
	//检查微信库存
	public function actionCheckSku(){
		$coupons_code = $_POST['coupons_code'];
		$cardCouponsC = new CardCouponsC();
		$result = $cardCouponsC->getWxSku($coupons_code);
	
		if($result['status'] == ERROR_NONE){
			if($result['data'] == 'ok'){
				echo 'ok';
			}else {
				echo 'no';
			}
				
		}else if ($result['status'] == ERROR_REQUEST_FAIL){
			echo 'no';
		}
	}	
	
	//微信的二维码投放
	public function actionWxQr($coupons_code){
		$cardCouponsC = new CardCouponsC();
		$result = $cardCouponsC->getWxQrcode($coupons_code);
		
		if($result['status'] == ERROR_NONE){
			echo  $result['data'] ;
			
		}else{
			echo $result['msg'];
		}
	}
	
	//卡券报表(单张券)
	public function actionCouponDataOne(){
		
		$code = '';
		if(isset($_GET) && !empty($_GET)){
			$code = $_GET['code'];
		}
		
		$search = '';
		$search['type'] = 1;
		//搜索条件
		if (isset($_GET['Coupon']) && !empty($_GET['Coupon'])) {
			$post = $_GET['Coupon'];
		
			if (!empty($post['type'])) {
				$type = $post['type'];
				$search['type'] = $type;
			}else {
				$search['type'] = 1;
			}
		
			if (!empty($post['time'])) {
				$time = $post['time'];
				$search['time'] = $time;
			}
			
			if (!empty($post['time2'])) {
				$time2 = $post['time2'];
				$search['time2'] = $time2;
			}
		}

		
		//默认时间段
		if (!isset($_GET['Coupon']['time']) || empty($_GET['Coupon']['time'])) {
			$search['time'] = date('Y-m-d', strtotime('-7 days')) . ' - ' . date('Y-m-d', strtotime('-1 days'));
		}
		
		//默认时间段2
		if (!isset($_GET['Coupon']['time2']) || empty($_GET['Coupon']['time2'])) {
			$search['time2'] = date('Y-m-d', strtotime('-7 days')) . ' - ' . date('Y-m-d', strtotime('-1 days'));
		}
		
		$type_arr = array('1'=>' 浏览人数/次数趋势  ','2'=>' 领取人数/次数趋势  ','3'=>' 使用人数/次数趋势  ');
		//浏览趋势数据中间变量数组
		$person_trend_arr = array();
		$num_trend_arr = array();
		//var_dump($_GET);
		$cardCouponsC = new CardCouponsC();
		$res = $cardCouponsC -> getCoupondataOne($code,$search);
		$coupon_info = $res['coupon_info'];
		$coupon_data = $res['coupon_data'];
		$this->render('coupon-data',array(
				'code'=>$code,
				'coupon_info'=>$coupon_info,
				'coupon_data'=>$coupon_data,
				'type_arr'=>$type_arr,
				'person_trend_arr'=>$person_trend_arr,
				'num_trend_arr'=>$num_trend_arr,
				'pages'=>$cardCouponsC->page
		));
	}
	
	public function actiontest()
	{
		$code_list = array();
		$code_list[] = '781414184215';
		$code_list[] = '781414184216';
		$code_list[] = '781414184217';
		$code_list[] = '781414184218';
		$code_list[] = '781414184219';
		$code_list[] = '781414184220';
		$code_list[] = '781414184221';
		$code_list[] = '781414184222';
 		$cardCouponsC = new CardCouponsC();
// 		$card_id = 'pK3d9wvSqYm2xVl-j18u0NjnQ6ls';
// 		$coupon_id = '32';
// 		$res = $cardCouponsC->importCode($card_id, $code_list);
// 		var_dump($res);

//  		$res = $cardCouponsC->searchCode($card_id);
//  		var_dump($res);
//  		$num = '7';
//  		$res = $cardCouponsC->cardModifystock($card_id, $num);
//  		var_dump($res);
 		
//  		$res = $cardCouponsC->qrcodeCreateOne($card_id);
//  		var_dump($res); 		

//  		$num = 5;
//  		$res = $cardCouponsC->createCode($coupon_id, $num);

//  		$res = $cardCouponsC->wxQrcodeCreate($card_id);
//  		var_dump($res);

//  		$res = $cardCouponsC->searchCard($card_id);
//  		var_dump($res); 		
//  		var_dump($res['card']['cash']['base_info']['sku']['quantity']);

 		$begin_date = '2016-05-20';
 		$end_date = '2016-07-04';
//  		$res = $cardCouponsC->getWxCardInfo($card_id, $begin_date, $end_date);
//  		var_dump($res);
 		
//  		$res = $cardCouponsC->getWxMerchantCardInfo($begin_date, $end_date);
//  		var_dump($res);
		
		$res = $cardCouponsC->codeinit();
		
	}
	
	
	
}