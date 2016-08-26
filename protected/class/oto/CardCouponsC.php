<?php
include_once (dirname ( __FILE__ ) . '/../mainClass.php');
include_once $_SERVER['DOCUMENT_ROOT'] . '/protected/components/Component.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/protected/class/wechat/Wechat.php';
/**
 * 卡券
 */

class CardCouponsC extends mainClass
{
	public $page = null;
	
	/**
	 * 添加券
	 * $merchant_id,   商户id
	 * $type,          券类型
	 * $if_wechat,     是否同步到微信卡包 1不开启 2开启
	 * $title,         券标题
	 * $vice_title,    副标题
	 * $discount       券折扣
	 * $prompt,        提示操作
	 * $if_share,      用户是否可以分享领取链接 1可以 2不可以
	 * $if_give,       可否转增其他好友 1 能 2不能
	 * $num,           发放数量
	 * $time_type,     有效时间类型1固定时间 2相对时间
	 * $start_time,    固定时间时的有效开始时间      
	 * $end_time,      固定时间有效结束时间
	 * $start_days,    领取后几天生效 当天代表0最高90天
	 * $effective_days, 有效天数最少1天最多90天
	 * $receive_num,    每个用户领取数量
	 * $mini_consumption,  最低消费
	 * $if_with_userdiscount, 是否能与会员折扣同用1不能 2能
	 * $tel,                   客服电话
	 * $use_illustrate,        使用须知
	 * $discount_illustrate,    优惠说明
	 * $money_type,             券金额类型 1固定 2随机
	 * $strat_money,            随机金额开始金额
	 * $end_money,              随机金额结束金额
	 * $money                   固定金额
	 * $store_limit             门店限制
	 * $use_restriction         使用限制
	 * $short_name              商户简称
	 * $use_channel				核销渠道
	 */
	public function cardCouponsAdd($merchant_id, $type, $if_wechat, $title, $vice_title, $discount, 
			                       $prompt, $if_share, $if_give, $num, $time_type, $start_time, $end_time, 
			                       $start_days, $effective_days, $receive_num, $mini_consumption, $if_with_userdiscount, 
			                       $tel, $use_illustrate, $discount_illustrate, $money_type, $start_money, $end_money, $money,$store_limit,$color,$use_restriction,$short_name,$use_channel=NULL) 
	{
		$result = array ();
		$errMsg = '';
		$flag = 0;
		$model = new Coupons();
		
		//验证商户简称
		if(empty($short_name)){
			$result ['status'] = ERROR_PARAMETER_MISS;
			$flag = 1;
			$errMsg =  $errMsg .' 商户简称必填';
			Yii::app()->user -> setFlash('short_name_error','商户简称必填');
		}else{
			$len = mb_strlen($short_name,'utf-8');
			if($len > 12){
				$result ['status'] = ERROR_PARAMETER_MISS;
				$flag = 1;
				$errMsg =  $errMsg .' 商户简称不得超过12';
				Yii::app()->user -> setFlash('short_name_error','商户简称不得超过12');
			}
		}
		
		//验证券标题
		if(empty($title)){
			$result ['status'] = ERROR_PARAMETER_MISS;
			$flag = 1;
			$errMsg =  $errMsg .' 券标题必填';
			Yii::app()->user -> setFlash('title_error','券标题必填');
		}
		
		//验证折扣数
		if($type == COUPON_TYPE_DISCOUNT){//券类型是折扣券的时候进行验证
			if(empty($discount)){
				$result ['status'] = ERROR_PARAMETER_MISS;
				$flag = 1;
				$errMsg =  $errMsg .' 折扣数必填';
				Yii::app()->user -> setFlash('discount_error','折扣数必填');
			}else{
				if($discount < 1 || $discount > 9.9){
					$result ['status'] = ERROR_PARAMETER_MISS;
					$errMsg = $errMsg . ' 折扣设置不合法';
					$flag = 1;
					Yii::app()->user -> setFlash('discount_error','折扣设置不合法');
				}
				
				if(!preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $discount)){ //验证数字正则
					$result ['status'] = ERROR_PARAMETER_MISS;
					$errMsg = $errMsg . ' 折扣设置不合法';
					$flag = 1;
					Yii::app()->user -> setFlash('discount_error','折扣设置不合法');
				}
			}
		}
		
		//验证券金额
		if($type == COUPON_TYPE_CASH){ //券类型是代金券的时候进行验证
			if(empty($money_type)){ //券金额类型为空   则默认是随机金额
				//验证随机金额开始金额与结束金额
				if(empty($if_wechat)){
				if(empty($start_money) || empty($end_money)){
					$result ['status'] = ERROR_PARAMETER_MISS;
					$flag = 1;
					$errMsg =  $errMsg .' 随机金额必填';
					Yii::app()->user -> setFlash('money_random_error','随机金额必填');
				}else{
					if(!preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $start_money)){
						$result ['status'] = ERROR_PARAMETER_MISS;
						$flag = 1;
						$errMsg =  $errMsg .' 随机金额设置不合法';
						Yii::app()->user -> setFlash('money_random_error','随机金额设置不合法');
					}
					if(!preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $end_money)){
						$result ['status'] = ERROR_PARAMETER_MISS;
						$flag = 1;
						$errMsg =  $errMsg .' 随机金额设置不合法';
						Yii::app()->user -> setFlash('money_random_error','随机金额设置不合法');
					}
					if($start_money < 0 || $end_money <= 0){
						$result ['status'] = ERROR_PARAMETER_MISS;
						$flag = 1;
						$errMsg =  $errMsg .' 随机金额设置不合法';
						Yii::app()->user -> setFlash('money_random_error','随机金额设置不合法');
					}else{
						if($start_money > $end_money){
							$result ['status'] = ERROR_PARAMETER_MISS;
							$flag = 1;
							$errMsg =  $errMsg .' 两金额大小设置不合法';
							Yii::app()->user -> setFlash('money_random_error','两金额大小设置不合法');
						}
					}
				}
			}
			}else{ //券金额类型不为空   则是固定金额
				if(empty($money)){
					$result ['status'] = ERROR_PARAMETER_MISS;
					$flag = 1;
					$errMsg =  $errMsg .' 固定金额必填';
					Yii::app()->user -> setFlash('money_error','固定金额必填');
				}else {
					if(!preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $money)){
						$result ['status'] = ERROR_PARAMETER_MISS;
						$flag = 1;
						$errMsg =  $errMsg .' 固定金额设置不合法';
						Yii::app()->user -> setFlash('money_error','固定金额设置不合法');
					}
					if($money <= 0){
						$result ['status'] = ERROR_PARAMETER_MISS;
						$flag = 1;
						$errMsg =  $errMsg .' 固定金额设置不合法';
						Yii::app()->user -> setFlash('money_error','固定金额设置不合法');
					}
				}
			}
		}
		
		//验证使用限制
		if($type == COUPON_TYPE_CASH){  //券类型是代金券的时候进行验证
			if(!empty($use_restriction)){
				if(!preg_match('/^[1-9]\d*$/', $use_restriction)){ //判断匹配大于0的正整数正则
					$result ['status'] = ERROR_PARAMETER_MISS;
					$errMsg = $errMsg .' 使用限制不合法';
					$flag = 1;
					Yii::app()->user -> setFlash('use_restriction_error','使用限制不合法');
				}
			}
		}
		
		//验证发放数量
		if (! isset ( $num ) || empty ( $num )) {
			$result ['status'] = ERROR_PARAMETER_MISS;
			$errMsg = $errMsg .' 发放量必填';
			$flag = 1;
			Yii::app()->user -> setFlash('num_error','发放量必填');
		}else{
			if($num <= 0){
				$result ['status'] = ERROR_PARAMETER_MISS;
				$errMsg = $errMsg .' 发放量不合法';
				$flag = 1;
				Yii::app()->user -> setFlash('num_error','发放量不合法');
			}
			if(!preg_match('/^[1-9]\d*$/', $num)){ //判断匹配大于0的正整数正则
				$result ['status'] = ERROR_PARAMETER_MISS;
				$errMsg = $errMsg .' 发放量不合法';
				$flag = 1;
				Yii::app()->user -> setFlash('num_error','发放量不合法');
			}
		}
		
		//验证提示操作
		if (! isset ( $prompt ) || empty ( $prompt )) {
			$result ['status'] = ERROR_PARAMETER_MISS;
			$errMsg = $errMsg .' 提示操作必填';
			$flag = 1;
			Yii::app()->user -> setFlash('prompt_error','提示操作必填');
		}
		
		//验证固定时间或相对时间
		if($time_type == VALID_TIME_TYPE_FIXED){ //如果是固定时间
			if(empty($end_time) || empty($start_time)){
				$result ['status'] = ERROR_PARAMETER_MISS;
				$errMsg = $errMsg .' 固定时间必填';
				$flag = 1;
				Yii::app()->user -> setFlash('valid_time_error','固定时间必填');
			}
		}else { //如果是相对时间
			
		}
		
		//验证优惠说明
		if($type == COUPON_TYPE_EXCHANGE){ //券类型是兑换券的时候进行验证
			if(empty($discount_illustrate)){
				$result ['status'] = ERROR_PARAMETER_MISS;
				$errMsg = $errMsg .' 优惠说明必填';
				$flag = 1;
				Yii::app()->user -> setFlash('discount_illustrate_error','优惠说明必填');
			}
		}
		
		//验证使用须知
		if(empty($use_illustrate)){
			$result ['status'] = ERROR_PARAMETER_MISS;
			$errMsg = $errMsg .' 使用须知必填';
			$flag = 1;
			Yii::app()->user -> setFlash('use_illustrate_error','使用须知必填');
		}
		
		if ($flag == 1) {
			$result ['errMsg'] = $errMsg;
			return json_encode ( $result );
		}
		 
		$model -> merchant_id = $merchant_id;
		$model -> merchant_short_name = $short_name;
		$model -> type = $type; //券类型
		$model -> if_wechat = !empty($if_wechat)?IF_WECHAT_YES:IF_WECHAT_NO; //是否同步到微信卡包 1不开启 2开启
		$model -> title = $title; //券标题
		$model -> vice_title = $vice_title; //副标题
		$model -> discount = $discount/10; //折扣数
		$model -> prompt = $prompt; //提示操作
		$model -> if_share = !empty($if_share)?IF_SHARE_YES:IF_SHARE_NO; //用户是否可以分享领取链接 1可以 2不可以
		$model -> if_give = !empty($if_give)?IF_GIVE_YES:IF_GIVE_NO; //可否转增其他好友 1 能 2不能
		$model -> num = $num; //发放数量
		$model -> time_type = $time_type; //有效时间类型1固定时间 2相对时间
		if(!empty($start_time)){//固定时间时的有效开始时间
			$model -> start_time =$start_time.' 00:00:00';
		}
		if(!empty($end_time)){//固定时间有效结束时间
			$model -> end_time =$end_time.' 23:59:59';
		}
		  
		if($time_type == VALID_TIME_TYPE_RELATIVE){
		 $model -> start_days = empty($start_days)?0:$start_days; //领取后几天生效 当天代表0最高90天
		 $model -> effective_days = $effective_days;  //有效天数最少1天最多90天
		}
		$model -> receive_num = $receive_num; //每个用户领取数量
		$model -> mini_consumption = $mini_consumption; //最低消费
		$model -> if_with_userdiscount = !empty($if_with_userdiscount)?IF_WITH_USERDISCOUNT_YES:IF_WITH_USERDISCOUNT_NO; //是否能与会员折扣同用1不能 2能
		$model -> tel = $tel; //客服电话
		$model -> use_illustrate = $use_illustrate; //使用须知
		
		if($type == COUPON_TYPE_EXCHANGE){
		    $model -> discount_illustrate = $discount_illustrate;  //优惠说明
		}elseif ($type == COUPON_TYPE_DISCOUNT){
			if(!empty($mini_consumption)){
			    $model -> discount_illustrate = $discount.'折折扣券'.','.'满'.$mini_consumption.'元可用';
			}else{
				$model -> discount_illustrate = $discount.'折折扣券'.','.'任意金额可用';
			}
		}else{
			if (empty ( $if_wechat )) {
				if (! empty ( $money_type )) { // 固定金额
					if (! empty ( $mini_consumption )) {
						$model->discount_illustrate = $money . '元代金券' . ',' . '满' . $mini_consumption . '元可用';
					} else {
						$model->discount_illustrate = $money . '元代金券' . ',' . '任意金额可用';
					}
				} else { // 随机金额
					if (! empty ( $mini_consumption )) {
						$model->discount_illustrate = $start_money . '元到' . $end_money . '元随机券' . ',' . '满' . $mini_consumption . '元可用';
					} else {
						$model->discount_illustrate = $start_money . '元到' . $end_money . '元随机券' . ',' . '任意金额可用';
					}
				}
			} else {
				if (! empty ( $mini_consumption )) {
					$model->discount_illustrate = $money . '元代金券' . ',' . '满' . $mini_consumption . '元可用';
				} else {
					$model->discount_illustrate = $money . '元代金券' . ',' . '任意金额可用';
				}
			}
		}
		if(empty($if_wechat)){
		    $model -> money_type = !empty($money_type)?FACE_VALUE_TYPE_FIXED:FACE_VALUE_TYPE_RANDOM; //券金额类型 1固定 2随机
		}else{
			$model -> money_type = FACE_VALUE_TYPE_FIXED;
		}
		if(!empty($start_money) && !empty($end_money)){
			$model -> money_random = ','.$start_money.','.$end_money.','; //代金券的随机金额，两金额用逗号分开
		}
		
		$model -> money = $money; //代金券的固定金额
		$model -> color = $color; //券颜色
		$model -> use_restriction = empty($use_restriction)?'1':$use_restriction; //使用限制
		$model -> use_channel = $use_channel; //核销渠道
		
		if(empty($store_limit)){
			$storeId = '';
			$store = Store::model()->findAll('flag=:flag and merchant_id=:merchant_id',array(':flag'=>FLAG_NO,':merchant_id'=>$merchant_id));
			foreach ($store as $key => $value){
			  $storeId = $storeId .','.($value -> id);
			}
			$model -> store_limit = $storeId.',';
		}else{
			$model -> store_limit = $store_limit .',';
		}
		
		$transaction = Yii::app()->db->beginTransaction();
		try {
			if ($model->save ()) {
				//如果该券是同步到微信的     则调用创建卡券接口
				if($model -> if_wechat == IF_WECHAT_YES){
					$logo_url = $this->getLogoUrl($model->id,$model->color);
					$t = $this->getToken();
					$apiResult = $this->getCardId($model->id,$model->color,$logo_url,$t);
					if ($apiResult['errcode'] == 0){ //微信成功返回码0
						$coupons = Coupons::model()->findByPk($model->id);
						$coupons['card_id'] = $apiResult['card_id'];
						if($coupons -> save()){
							$result ['errcode'] = 0;
						}
					}else{
//						throw new Exception('微信卡券同步失败');
//	 					Yii::log('创建卡券接口错误信息:'.$apiResult['errmsg'].'创建卡券接口错误码：'.$apiResult['errcode'].'Token:'.$t, 'warning');
						$result ['errcode'] = 1;
// 						Coupons::model()->findByPk($model->id)->delete();
					}
				}
				$transaction -> commit();
				$result ['status'] = ERROR_NONE; // 状态码
				$result ['errMsg'] = ''; // 错误信息
			} else {
				$result ['status'] = ERROR_SAVE_FAIL; // 状态码
				$result ['errMsg'] = '数据保存失败'; // 错误信息
			}
		}
		catch (Exception $e) {
              $transaction->rollback(); //如果操作失败, 数据回滚
              $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
              $result['errMsg'] = $e->getMessage(); //错误信息
              
        }
		
		return json_encode ( $result );
		
		
	}
	
	/**
	 * 获取商户简称
	 * $merchant_id   商户id
	 */
	public function getMerchantShortName($merchant_id)
	{
		$merchant_short_name = '';
		$onlineShop = Onlineshop::model()->find('merchant_id=:merchant_id and flag=:flag',
				array(':merchant_id'=>$merchant_id,':flag'=>FLAG_NO));
		$merchant = Merchant::model()->findByPk($merchant_id);
		
		if(!empty($onlineShop)){
			if(!empty($onlineShop->name)){
			  $merchant_short_name = $onlineShop->name;
			}
		}else{
			$merchant_short_name = $merchant->name;
		}
		return $merchant_short_name;
	}
	
	/**
	 * 获取券的信息
	 * $coupons_id  券id
	 */
	public function getEditCoupons($coupons_id)
	{
		$result = array();
		$data = array();
		try {
			$model = Coupons::model()->findByPk($coupons_id);
			
			if(!empty($model)){
				$result['status'] = ERROR_NONE;
				$result['errMsg'] = '';
				
				$data['list']['id'] = $model['id'];
				$data['list']['merchant_short_name'] = $model['merchant_short_name']; //商户简称
				$data['list']['type'] = $model['type']; //券类型
				$data['list']['if_wechat'] = $model['if_wechat']; //是否同步到微信卡包 1不开启 2开启
				$data['list']['title'] = $model['title']; //券标题
				$data['list']['vice_title'] = $model['vice_title']; //副标题
				$data['list']['money_type'] = $model['money_type']; //券金额类型 1固定 2随机
				$data['list']['money_random'] = $model['money_random']; //代金券的随机金额
				$data['list']['money'] = $model['money']; //代金券的固定金额
				$data['list']['discount'] = $model['discount']; //券折扣
				$data['list']['prompt'] = $model['prompt']; //提示操作
				$data['list']['color'] = $model['color']; //创建时间
				$data['list']['if_share'] = $model['if_share']; //用户是否可以分享领取链接 1可以 2不可以
				$data['list']['if_give'] = $model['if_give']; //可否转增其他好友 1 能 2不能
				$data['list']['num'] = $model['num']; //发放数量
				$data['list']['get_num'] = $model['get_num']; //已领取数量
				$data['list']['time_type'] = $model['time_type']; //有效时间类型1固定时间 2相对时间
				$data['list']['start_time'] = $model['start_time']; //固定时间时的有效开始时间
				$data['list']['end_time'] = $model['end_time']; //固定时间有效结束时间
				$data['list']['start_days'] = $model['start_days']; //领取后几天生效 当天代表0最高90天
				$data['list']['effective_days'] = $model['effective_days']; //有效天数最少1天最多90天
				$data['list']['receive_num'] = $model['receive_num']; //每个用户领取数量
				$data['list']['mini_consumption'] = $model['mini_consumption']; //最低消费
				$data['list']['use_restriction'] = $model['use_restriction']; //使用限制
				$data['list']['if_with_userdiscount'] = $model['if_with_userdiscount']; //是否能与会员折扣同用1不能 2能
				$data['list']['store_limit'] = $model['store_limit']; //门店限制
				$data['list']['tel'] = $model['tel']; //客服电话
				$data['list']['use_illustrate'] = $model['use_illustrate']; //使用须知
				$data['list']['discount_illustrate'] = $model['discount_illustrate']; //优惠说明
				$data['list']['if_invalid'] = $model['if_invalid']; //是否失效 1未失效 2已失效
				$data['list']['create_time'] = $model['create_time']; //创建时间
				$data['list']['store_limit_name'] = $this->getStoreName($model['store_limit']); //门店限制名称
			}else{
				$result['status'] = ERROR_NO_DATA;
				throw new Exception('数据不存在');
			}
			
		} catch (Exception $e) {
			$result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
			$result['errMsg'] = $e->getMessage(); //错误信息
		}
		$result['data'] = $data;
		return json_encode ( $result );		
	}
	
	/**
	 * 获取门店限制名称
	 * $store_limit   门店id集合（字符串形式）
	 */
	public function getStoreName($store_limit)
	{
		$store_name = array();
		$arr = explode(',', $store_limit);
		for($i=1;$i<count($arr)-1;$i++){
			$store = Store::model()->findByPk($arr[$i]);
			$store_name[$store->id] = $store->name;
		}
		return $store_name;
	}
	
	/**
	 * 获取门店列表
	 */
	public function getStoreList($merchant_id)
	{
		$result = array();
		$data = array();
		try {
			$criteria = new CDbCriteria();
			$criteria -> addCondition('merchant_id=:merchant_id and flag=:flag');
			$criteria -> params = array(':merchant_id'=>$merchant_id,':flag'=>FLAG_NO);
		
			$store = Store::model()->findAll($criteria);
			if(!empty($store)){
				foreach ($store as $k => $v){
					$data['list'][$k]['id'] = $v['id'];
					$data['list'][$k]['name'] = $v['name']; //门店名
					$data['list'][$k]['address'] = $v['address']; //门店地址
				}
				$result['data'] = $data;
				$result ['status'] = ERROR_NONE;
			}else{
				$result ['status'] = ERROR_NO_DATA;
				$result['errMsg'] = '无此数据'; //错误信息
			}
		} catch (Exception $e) {
			$result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
			$result['errMsg'] = $e->getMessage(); //错误信息
		}
		
		return json_encode($result);
	}
	
	/**
	 * 编辑券
	 * $coupons_id     券id
	 * $merchant_id,   商户id
	 * $if_wechat,     是否同步到微信卡包 1不开启 2开启
	 * $title,         券标题
	 * $vice_title,    副标题
	 * $discount       券折扣
	 * $prompt,        提示操作
	 * $if_share,      用户是否可以分享领取链接 1可以 2不可以
	 * $if_give,       可否转增其他好友 1 能 2不能
	 * $num,           发放数量
	 * $time_type,     有效时间类型1固定时间 2相对时间
	 * $start_time,    固定时间时的有效开始时间      
	 * $end_time,      固定时间有效结束时间
	 * $start_days,    领取后几天生效 当天代表0最高90天
	 * $effective_days, 有效天数最少1天最多90天
	 * $receive_num,    每个用户领取数量
	 * $mini_consumption,  最低消费
	 * $if_with_userdiscount, 是否能与会员折扣同用1不能 2能
	 * $tel,                   客服电话
	 * $use_illustrate,        使用须知
	 * $discount_illustrate,    优惠说明
	 * $money_type,             券金额类型 1固定 2随机
	 * $strat_money,            随机金额开始金额
	 * $end_money,              随机金额结束金额
	 * $money                   固定金额
	 * $store_limit             门店限制
	 */
	public function editCardCoupons ( $coupons_id,$merchant_id, $if_wechat, $title, $vice_title, $discount, $prompt,
					                  $if_share, $if_give, $num, $time_type, $start_time, $end_time, $start_days,
					                  $effective_days, $receive_num, $mini_consumption, $if_with_userdiscount, $tel,
					                  $use_illustrate, $discount_illustrate, $money_type, $start_money, $end_money, $money,$store_limit,$color,$use_restriction,$short_name)
    {
    	$result = array ();
    	$errMsg = '';
    	$flag = 0;
    	$model = Coupons::model()->findByPk($coupons_id);
    	
    	//验证商户简称
    	if(empty($short_name)){
    		$result ['status'] = ERROR_PARAMETER_MISS;
    		$flag = 1;
    		$errMsg =  $errMsg .' 商户简称必填';
    		Yii::app()->user -> setFlash('short_name_error','商户简称必填');
    	}else{
    		$len = mb_strlen($short_name,'utf-8');
    		if($len > 12){
    			$result ['status'] = ERROR_PARAMETER_MISS;
    			$flag = 1;
    			$errMsg =  $errMsg .' 商户简称不得超过12';
    			Yii::app()->user -> setFlash('short_name_error','商户简称不得超过12');
    		}
    	}
    	
    	//验证券标题
    	if(empty($title)){
    		$result ['status'] = ERROR_PARAMETER_MISS;
    		$flag = 1;
    		$errMsg =  $errMsg .' 券标题必填';
    		Yii::app()->user -> setFlash('title_error','券标题必填');
    	}
    	
    	//验证折扣数
    	if($model -> type == COUPON_TYPE_DISCOUNT){//券类型是折扣券的时候进行验证
    		if(empty($discount)){
    			$result ['status'] = ERROR_PARAMETER_MISS;
    			$flag = 1;
    			$errMsg =  $errMsg .' 折扣数必填';
    			Yii::app()->user -> setFlash('discount_error','折扣数必填');
    		}else{
    			if($discount < 1 || $discount > 9.9){
    				$result ['status'] = ERROR_PARAMETER_MISS;
    				$errMsg = $errMsg . ' 折扣设置不合法';
    				$flag = 1;
    				Yii::app()->user -> setFlash('discount_error','折扣设置不合法');
    			}
    			
    			if(!preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $discount)){ //验证数字正则
    				$result ['status'] = ERROR_PARAMETER_MISS;
    				$errMsg = $errMsg . ' 折扣设置不合法';
    				$flag = 1;
    				Yii::app()->user -> setFlash('discount_error','折扣设置不合法');
    			}
    		}
    	}
    	//echo $if_wechat;exit;
    	//验证券金额
    	if(empty($if_wechat)){
    	if($model -> type == COUPON_TYPE_CASH){ //券类型是代金券的时候进行验证
    		if(empty($money_type)){ //券金额类型为空   则默认是随机金额
    			//验证随机金额开始金额与结束金额
    			if(empty($if_wechat)){
    			if(empty($start_money) || empty($end_money)){
    				$result ['status'] = ERROR_PARAMETER_MISS;
    				$flag = 1;
    				$errMsg =  $errMsg .' 随机金额必填';
    				Yii::app()->user -> setFlash('money_random_error','随机金额必填');
    			}else{
    				if(!preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $start_money)){
    					$result ['status'] = ERROR_PARAMETER_MISS;
    					$flag = 1;
    					$errMsg =  $errMsg .' 随机金额设置不合法';
    					Yii::app()->user -> setFlash('money_error','随机金额设置不合法');
    				}
    				if(!preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $end_money)){
    					$result ['status'] = ERROR_PARAMETER_MISS;
    					$flag = 1;
    					$errMsg =  $errMsg .' 随机金额设置不合法';
    					Yii::app()->user -> setFlash('money_error','随机金额设置不合法');
    				}
    				if($start_money < 0 || $end_money <= 0){
    					$result ['status'] = ERROR_PARAMETER_MISS;
    					$flag = 1;
    					$errMsg =  $errMsg .' 随机金额设置不合法';
    					Yii::app()->user -> setFlash('money_random_error','随机金额设置不合法');
    				}else{
    					if($start_money > $end_money){
    						$result ['status'] = ERROR_PARAMETER_MISS;
    						$flag = 1;
    						$errMsg =  $errMsg .' 两金额大小设置不合法';
    						Yii::app()->user -> setFlash('money_random_error','两金额大小设置不合法');
    					}
    				}
    			}
    			}
    		}else{ //券金额类型不为空   则是固定金额
    			
    			if(empty($money)){echo 'ppp';exit;
    				$result ['status'] = ERROR_PARAMETER_MISS;
    				$flag = 1;
    				$errMsg =  $errMsg .' 固定金额必填';
    				Yii::app()->user -> setFlash('money_error','固定金额必填');
    			}else {
    				if(!preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $money)){
    					$result ['status'] = ERROR_PARAMETER_MISS;
    					$flag = 1;
    					$errMsg =  $errMsg .' 固定金额设置不合法';
    					Yii::app()->user -> setFlash('money_error','固定金额设置不合法');
    				}
    				if($money <= 0){
    					$result ['status'] = ERROR_PARAMETER_MISS;
    					$flag = 1;
    					$errMsg =  $errMsg .' 固定金额设置不合法';
    					Yii::app()->user -> setFlash('money_error','固定金额设置不合法');
    				}
    			}
    		
    	}
    	}
    	}else{
    		if($model -> type == COUPON_TYPE_CASH){
    		if(empty($money)){
    			$result ['status'] = ERROR_PARAMETER_MISS;
    			$flag = 1;
    			$errMsg =  $errMsg .' 固定金额必填';
    			Yii::app()->user -> setFlash('money_error','固定金额必填');
    		}else {
    			if(!preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $money)){
    				$result ['status'] = ERROR_PARAMETER_MISS;
    				$flag = 1;
    				$errMsg =  $errMsg .' 固定金额设置不合法';
    				Yii::app()->user -> setFlash('money_error','固定金额设置不合法');
    			}
    			if($money <= 0){
    				$result ['status'] = ERROR_PARAMETER_MISS;
    				$flag = 1;
    				$errMsg =  $errMsg .' 固定金额设置不合法';
    				Yii::app()->user -> setFlash('money_error','固定金额设置不合法');
    			}
    		}
    	}
    	}
    	
    	
    	//验证使用限制
    	if($model -> type == COUPON_TYPE_CASH){  //券类型是代金券的时候进行验证
    		if(!empty($use_restriction)){
    			if(!preg_match('/^[1-9]\d*$/', $use_restriction)){ //判断匹配大于0的正整数正则
    				$result ['status'] = ERROR_PARAMETER_MISS;
    				$errMsg = $errMsg .' 使用限制不合法';
    				$flag = 1;
    				Yii::app()->user -> setFlash('use_restriction_error','使用限制不合法');
    			}
    		}
    	}
    	
    	//验证发放数量
    	if (! isset ( $num ) || empty ( $num )) {
    		$result ['status'] = ERROR_PARAMETER_MISS;
    		$errMsg = $errMsg .' 发放量必填';
    		$flag = 1;
    		Yii::app()->user -> setFlash('num_error','发放量必填');
    	}else{
    		if($num <= 0){
    			$result ['status'] = ERROR_PARAMETER_MISS;
    			$errMsg = $errMsg .' 发放量不合法';
    			$flag = 1;
    			Yii::app()->user -> setFlash('num_error','发放量不合法');
    		}
    		
    		if(!preg_match('/^[1-9]\d*$/', $num)){ //判断匹配大于0的正整数正则
    			$result ['status'] = ERROR_PARAMETER_MISS;
    			$errMsg = $errMsg .' 发放量不合法';
    			$flag = 1;
    			Yii::app()->user -> setFlash('num_error','发放量不合法');
    		}
    	}
    	
    	//验证提示操作
    	if (! isset ( $prompt ) || empty ( $prompt )) {
    		$result ['status'] = ERROR_PARAMETER_MISS;
    		$errMsg = $errMsg .' 提示操作必填';
    		$flag = 1;
    		Yii::app()->user -> setFlash('prompt_error','提示操作必填');
    	}
    	
    	//验证固定时间或相对时间
    	if($time_type == VALID_TIME_TYPE_FIXED){ //如果是固定时间
    		if(empty($end_time) || empty($start_time)){
    			$result ['status'] = ERROR_PARAMETER_MISS;
    			$errMsg = $errMsg .' 固定时间必填';
    			$flag = 1;
    			Yii::app()->user -> setFlash('valid_time_error','固定时间必填');
    		}
    	}else { //如果是相对时间
    			
    	}
    	
    	//验证优惠说明
    	if($model -> type == COUPON_TYPE_EXCHANGE){ //券类型是兑换券的时候进行验证
    		if(empty($discount_illustrate)){
    			$result ['status'] = ERROR_PARAMETER_MISS;
    			$errMsg = $errMsg .' 优惠说明必填';
    			$flag = 1;
    			Yii::app()->user -> setFlash('discount_illustrate_error','优惠说明必填');
    		}
    	}
    	
    	//验证使用须知
    	if(empty($use_illustrate)){
    		$result ['status'] = ERROR_PARAMETER_MISS;
    		$errMsg = $errMsg .' 使用须知必填';
    		$flag = 1;
    		Yii::app()->user -> setFlash('use_illustrate_error','使用须知必填');
    	}
    	
    	if ($flag == 1) {
    		$result ['errMsg'] = $errMsg;
    		return json_encode ( $result );
    	}	

    	$model -> if_wechat = !empty($if_wechat)?IF_WECHAT_YES:IF_WECHAT_NO; //是否同步到微信卡包 1不开启 2开启
    	$model -> title = $title; //券标题
    	$model -> merchant_short_name = $short_name;
    	$model -> vice_title = $vice_title; //副标题
    	$model -> discount = $discount/10; //折扣数
    	$model -> prompt = $prompt; //提示操作
    	$model -> if_share = !empty($if_share)?IF_SHARE_YES:IF_SHARE_NO; //用户是否可以分享领取链接 1可以 2不可以
    	$model -> if_give = !empty($if_give)?IF_GIVE_YES:IF_GIVE_NO; //可否转增其他好友 1 能 2不能
    	$model -> num = $num; //发放数量
    	$model -> time_type = $time_type; //有效时间类型1固定时间 2相对时间
    	if(!empty($start_time)){//固定时间时的有效开始时间
    		$model -> start_time =$start_time.' 00:00:00';
    	}
    	if(!empty($end_time)){//固定时间有效结束时间
    		$model -> end_time =$end_time.' 23:59:59';
    	}
    	
    	if($time_type == VALID_TIME_TYPE_RELATIVE){
    		$model -> start_days = empty($start_days)?0:$start_days; //领取后几天生效 当天代表0最高90天
    		$model -> effective_days = $effective_days;  //有效天数最少1天最多90天
    	}
    	$model -> receive_num = $receive_num; //每个用户领取数量
    	$model -> mini_consumption = $mini_consumption; //最低消费
    	$model -> if_with_userdiscount = !empty($if_with_userdiscount)?IF_WITH_USERDISCOUNT_YES:IF_WITH_USERDISCOUNT_NO; //是否能与会员折扣同用1不能 2能
    	$model -> tel = $tel; //客服电话
    	$model -> use_illustrate = $use_illustrate; //使用须知
    	
        if($model -> type == COUPON_TYPE_EXCHANGE){
		    $model -> discount_illustrate = $discount_illustrate;  //优惠说明
		}elseif ($model -> type == COUPON_TYPE_DISCOUNT){
			if(!empty($mini_consumption)){
			    $model -> discount_illustrate = $discount.'折折扣券'.','.'满'.$mini_consumption.'元可用';
			}else{
				$model -> discount_illustrate = $discount.'折折扣券'.','.'任意金额可用';
			}
		}else{
		if (empty ( $if_wechat )) {
				if (! empty ( $money_type )) { // 固定金额
					if (! empty ( $mini_consumption )) {
						$model->discount_illustrate = $money . '元代金券' . ',' . '满' . $mini_consumption . '元可用';
					} else {
						$model->discount_illustrate = $money . '元代金券' . ',' . '任意金额可用';
					}
				} else { // 随机金额
					if (! empty ( $mini_consumption )) {
						$model->discount_illustrate = $start_money . '元到' . $end_money . '元随机券' . ',' . '满' . $mini_consumption . '元可用';
					} else {
						$model->discount_illustrate = $start_money . '元到' . $end_money . '元随机券' . ',' . '任意金额可用';
					}
				}
			} else {
				if (! empty ( $mini_consumption )) {
					$model->discount_illustrate = $money . '元代金券' . ',' . '满' . $mini_consumption . '元可用';
				} else {
					$model->discount_illustrate = $money . '元代金券' . ',' . '任意金额可用';
				}
			}
		}
		
        if(empty($if_wechat)){
		  $model -> money_type = !empty($money_type)?FACE_VALUE_TYPE_FIXED:FACE_VALUE_TYPE_RANDOM; //券金额类型 1固定 2随机
		}else{
			$model -> money_type = FACE_VALUE_TYPE_FIXED;
		}
    	if(!empty($start_money) && !empty($end_money)){
    		$model -> money_random = ','.$start_money.','.$end_money.','; //代金券的随机金额，两金额用逗号分开
    	}
    	
    	$model -> money = $money; //代金券的固定金额
    	$model -> color = $color; //券颜色
    	$model -> use_restriction = empty($use_restriction)?'1':$use_restriction; //使用限制
    	//echo $store_limit.'a';exit;
    	if(empty($store_limit)){
    		$storeId = '';
    		$store = Store::model()->findAll('flag=:flag and merchant_id=:merchant_id',array(':flag'=>FLAG_NO,':merchant_id'=>$merchant_id));
    		foreach ($store as $key => $value){
    			$storeId = $storeId .','.($value -> id);
    		}
    		$model -> store_limit = $storeId.',';
    	}else{
    		$model -> store_limit = $store_limit .',';
    	}
    	
    	//调用卡券接口
    	$coupon = Coupons::model()->findByPk($coupons_id);
    	if($coupon['if_wechat'] == IF_WECHAT_YES){
    		//如果原来是同步微信的     现在（!empty($if_wechat)）也是同步的   调用修改卡券接口
    		if(!empty($if_wechat)){
    			//调用修改卡券接口
    			$apiResult = $this -> cardUpdate($coupons_id, $if_wechat, $title, $vice_title, $discount, $prompt,
					                  $if_share, $if_give, $num, $time_type, $start_time, $end_time, $start_days,
					                  $effective_days, $receive_num, $mini_consumption, $if_with_userdiscount, $tel,
					                  $use_illustrate, $discount_illustrate, $money_type, $start_money, $end_money, $money,$store_limit,$color);
    			if($apiResult['errcode'] == 0){ //微信返回成功码为0
    				if ($model->save ()) {
    					$result ['status'] = ERROR_NONE; // 状态码
    					$result ['errMsg'] = ''; // 错误信息
    				} else {
    					$result ['status'] = ERROR_SAVE_FAIL; // 状态码
    					$result ['errMsg'] = '数据保存失败'; // 错误信息
    				}
    			}else{
    				$result ['status'] = ERROR_EXCEPTION; // 状态码
    				$result ['errMsg'] = '微信接口返回错误信息'.$apiResult['errcode']; // 错误信息
    			}
    			
    		}else{ //如果原来是同步微信的     现在（empty($if_wechat)）不同步的   调用删除卡券接口
    			$apiResult3 = $this->cardDelete($coupons_id);
    			if($apiResult3['errcode'] == 0){ //微信返回成功码为0
    				$model -> if_wechat = IF_WECHAT_NO;
    				$model -> card_id = '';
    				if ($model->save ()) {
    					$result ['status'] = ERROR_NONE; // 状态码
    					$result ['errMsg'] = ''; // 错误信息
    				} else {
    					$result ['status'] = ERROR_SAVE_FAIL; // 状态码
    					$result ['errMsg'] = '数据保存失败'; // 错误信息
    				}
    			}else{
    				$result ['status'] = ERROR_EXCEPTION; // 状态码
    				$result ['errMsg'] = '微信接口返回错误信息'.$apiResult3['errcode']; // 错误信息
    			}
    		}
    	}else {
    		//如果原来不是同步微信的     现在（!empty($if_wechat)）是同步的   调用创建卡券接口
    		if(!empty($if_wechat)){
    			//调用创建卡券接口
    			$logo_url = $this->getLogoUrl($coupon->id,$coupon->color);
    			$apiResult2 = $this->getCardId($coupon->id,$coupon->color,$logo_url,$this->getToken());
    			if ($apiResult2['errcode'] == 0){ //微信成功返回码0
    				$model['card_id'] = $apiResult2['card_id'];
    				if ($model->save ()) {
    					$result ['status'] = ERROR_NONE; // 状态码
    					$result ['errMsg'] = ''; // 错误信息
    				} else {
    					$result ['status'] = ERROR_SAVE_FAIL; // 状态码
    					$result ['errMsg'] = '数据保存失败'; // 错误信息
    				}
    			}else{
    				$result ['status'] = ERROR_EXCEPTION; // 状态码
    				$result ['errMsg'] = '微信接口返回错误信息'.$apiResult2['errcode']; // 错误信息
    			}
    			 
    		}else{ //如果原来不是同步微信的     现在（empty($if_wechat)）不同步的   不调用接口
    			if ($model->save ()) {
					$result ['status'] = ERROR_NONE; // 状态码
					$result ['errMsg'] = ''; // 错误信息
				} else {
					$result ['status'] = ERROR_SAVE_FAIL; // 状态码
					$result ['errMsg'] = '数据保存失败'; // 错误信息
				}
    		}
    	}
    	return json_encode ( $result );
	}
	
	/**
	 * 获取商户名称
	 * $merchant_id  商户id
	 */
	public function getMerchantName($merchant_id)
	{
		$name = '';
		$model = Merchant::model()->findByPk($merchant_id);
		if(!empty($model)){
			if(!empty($model['name'])){
			  $name = $model['name'];
			}
		}
		return $name;
	}
	
	/**
	 * 获取商户Logo
	 * $merchant_id  商户id
	 */
	public function getMerchantLogo($merchant_id)
	{
		$logo_img = '';
		$model = Onlineshop::model()->find('merchant_id=:merchant_id and flag=:flag',array(':merchant_id'=>$merchant_id,':flag'=>FLAG_NO));
		if(!empty($model)){
			if(!empty($model['logo_img'])){
				$logo_img = $model['logo_img'];
			}
		}
		return $logo_img;
	}
	
	/**
	 * 修改卡券审核状态字段
	 * $cardId    卡券cardId
	 * $card_check   审核状态
	 */
	public function editCheckStatus($cardId,$card_check)
	{
		$result = array();
		try {			
			$model = Coupons::model()->find('card_id = :card_id',array(':card_id'=>$cardId));
			if (empty ( $model )) {
				$result ['status'] = ERROR_NO_DATA;
				throw new Exception ( '查询的数据不存在' );
			}
			$model ['status'] = $card_check=='card_pass_check'?WX_CHECK_PASS:WX_CHECK_NOTPASS;
			if ($model->update ()) {
				$result ['status'] = ERROR_NONE; // 状态码
				$result ['errMsg'] = ''; // 错误信息
			} else {
				$result ['status'] = ERROR_SAVE_FAIL; // 状态码
				$result ['errMsg'] = '数据保存失败'; // 错误信息
			}
			
		} catch (Exception $e) {
			$result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
			$result['errMsg'] = $e->getMessage(); //错误信息
			Yii::log('卡券事件推送错误信息:'.$result['errMsg'],'warning');
		}	
			
	}
	
	
	/**
	 * 获取卡券列表
	 * $merchant_id  商户id
	 * $key_woed     输入优惠券名称搜索
	 */
	public function getCardCouponsList($merchant_id,$key_woed)
	{
		$result = array();
		try {
			
			$criteria = new CDbCriteria();
			$criteria -> addCondition('merchant_id=:merchant_id and flag=:flag');
			$criteria -> params = array(':merchant_id'=>$merchant_id,':flag' => FLAG_NO);
			
			//输入优惠券名称搜索
			if(!empty($key_woed)){
// 				$criteria -> addCondition('title=:title');
// 				$criteria -> params[':title'] = $key_woed;
                $criteria -> addSearchCondition('title',$key_woed);
			}
			
			$criteria -> order = 'create_time desc';
			
			//分页
			$pages = new CPagination(Coupons::model()->count($criteria));
			$pages -> pageSize = Yii::app()-> params['perPage'];
			$pages -> applyLimit($criteria);
			$this->page = $pages;
			
			$model = Coupons::model()->findall($criteria); 
			$data = array ();
			if(!empty($model)){
				foreach ($model as $k => $v){
					$data['list'][$k]['id'] = $v['id'];
					$data['list'][$k]['type'] = $v['type']; //券类型
					$data['list'][$k]['if_wechat'] = $v['if_wechat']; //是否同步到微信卡包 1不开启 2开启
					$data['list'][$k]['title'] = $v['title']; //券标题
					$data['list'][$k]['vice_title'] = $v['vice_title']; //副标题
					$data['list'][$k]['money_type'] = $v['money_type']; //券金额类型 1固定 2随机
					$data['list'][$k]['money_random'] = $v['money_random']; //代金券的随机金额
					$data['list'][$k]['money'] = $v['money']; //代金券的固定金额
					$data['list'][$k]['discount'] = $v['discount']; //券折扣
					$data['list'][$k]['prompt'] = $v['prompt']; //提示操作
					$data['list'][$k]['if_share'] = $v['if_share']; //用户是否可以分享领取链接 1可以 2不可以
					$data['list'][$k]['if_give'] = $v['if_give']; //可否转增其他好友 1 能 2不能
					$data['list'][$k]['num'] = $v['num']; //发放数量
					$data['list'][$k]['get_num'] = $v['get_num']; //已领取数量
					$data['list'][$k]['time_type'] = $v['time_type']; //有效时间类型1固定时间 2相对时间
					$data['list'][$k]['start_time'] = $v['start_time']; //固定时间时的有效开始时间
					$data['list'][$k]['end_time'] = $v['end_time']; //固定时间有效结束时间
					$data['list'][$k]['start_days'] = $v['start_days']; //领取后几天生效 当天代表0最高90天
					$data['list'][$k]['effective_days'] = $v['effective_days']; //有效天数最少1天最多90天
					$data['list'][$k]['receive_num'] = $v['receive_num']; //每个用户领取数量
					$data['list'][$k]['mini_consumption'] = $v['mini_consumption']; //最低消费
					$data['list'][$k]['use_restriction'] = $v['use_restriction']; //使用限制
					$data['list'][$k]['if_with_userdiscount'] = $v['if_with_userdiscount']; //是否能与会员折扣同用1不能 2能
					$data['list'][$k]['store_limit'] = $v['store_limit']; //门店限制
					$data['list'][$k]['tel'] = $v['tel']; //客服电话
					$data['list'][$k]['use_illustrate'] = $v['use_illustrate']; //使用须知
					$data['list'][$k]['discount_illustrate'] = $v['discount_illustrate']; //优惠说明
					$data['list'][$k]['if_invalid'] = $v['if_invalid']; //是否失效 1未失效 2已失效
					$data['list'][$k]['create_time'] = $v['create_time']; //创建时间
					$data['list'][$k]['get_receive_num'] = $this->getReceiveNum($v['id']); //券领取次数
					$data['list'][$k]['use_receive_num'] = $this->getUseReceiveNum($v['id']); //券使用次数
					$data['list'][$k]['receive_per'] = $this->getReceivePer($v['id']); //券领取人数
					$data['list'][$k]['status'] = $v['status']; //微信审核状态   1审核中 2已通过 3未通过
				}
				$result['data'] = $data;
				$result['status'] = ERROR_NONE;
			}else{
				$result ['status'] = ERROR_NO_DATA;
			    $result['errMsg'] = '无此数据'; //错误信息 
			}
			
		} catch (Exception $e) {
			$result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
			$result['errMsg'] = $e->getMessage(); //错误信息
		}
		
		return json_encode($result);
	}
	
	/**
	 * 获取单张券领取人数
	 * $coupons_id  券id
	 */
	public function getReceivePer($coupons_id)
	{
		$total_num = '0';
		$user_num = '0';
		$no_user_num = '0';
		//有user_id的领取人数
		$user_num = UserCoupons::model()->countBySql('select count(distinct user_id) as num from wq_user_coupons where coupons_id =:coupons_id',array(':coupons_id'=>$coupons_id));
		//没有user_id的领取人数
		$model = UserCoupons::model()->findAll(array(
			'condition' => 'coupons_id=:coupons_id and ISNULL(user_id) and flag=:flag',
    		'group' => 'get_openid',
    		'params' => array(':coupons_id'=>$coupons_id,':flag'=>FLAG_NO)
		));
		if(!empty($model)){
			$no_user_num = count($model);
		}
		$total_num = $user_num + $no_user_num;
		//$sql = 'select count(distinct user_id) as num from wq_user_coupons where coupons_id ='.$coupons_id;
		//$model = Yii::app()->db->createCommand($sql)->queryAll();
		return $total_num;//[0]['num'];
	}
	
	/**
	 * 获取券领取次数
	 *  $coupons_id  券id
	 */
	public function getReceiveNum($coupons_id)
	{
		$count = 0;
		$model = UserCoupons::model()->findAll('coupons_id=:coupons_id and flag=:flag',array(':coupons_id'=>$coupons_id,':flag'=>FLAG_NO));
		if(!empty($model)){
			$count = count($model);
		}
		return $count;
	}
	
	/**
	 * 获取领取的券使用的数量
	 *  $coupons_id  券id
	 */
	public function getUseReceiveNum($coupons_id)
	{
		$count = 0;
		$model = UserCoupons::model()->findAll('coupons_id=:coupons_id and flag=:flag and status=:status'
				,array(':coupons_id'=>$coupons_id,':flag'=>FLAG_NO,':status'=>COUPONS_USE_STATUS_USED));
		if(!empty($model)){
			$count = count($model);
		}
		return $count;
	}
	
	/**
	 * 使失效操作
	 * $coupons_id  券id
	 */
	public function invalid($coupons_id)
	{
		$result = array();
		try {
			$model = Coupons::model()->findByPk($coupons_id);
		//	if($model -> if_wechat == IF_WECHAT_NO){ //如果该券没有同步到微信的
			if (empty ( $model )) {
					$result ['status'] = ERROR_NO_DATA;
					throw new Exception ( '删除的数据不存在' );
				}
				$model ['if_invalid'] = IF_INVALID_YES;
				if ($model->save ()) {
					$result ['status'] = ERROR_NONE; // 状态码
					$result ['errMsg'] = ''; // 错误信息
					$result ['data'] = '';
				} else {
					$result ['status'] = ERROR_SAVE_FAIL; // 状态码
					$result ['errMsg'] = '数据保存失败'; // 错误信息
					$result ['data'] = '';
				}
			//}
// 			else{  //如果该券有同步到微信的 
				
// 			}
		} catch (Exception $e) {
			$result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
			$result['errMsg'] = $e->getMessage(); //错误信息
		}
		
		return json_encode($result);
	}
	
	
	/**
	 * 修改剩余量
	 * $coupons_id  券id
	 * $edit_num    修改量
	 * $change_type 改变类型   1增加 2修改
	 */
	public function editNum($coupons_id,$edit_num,$change_type)
	{
		$result = array();
		try {
			$model = Coupons::model()->findByPk($coupons_id);
			
			if (empty ( $model )) {
				$result ['status'] = ERROR_NO_DATA;
				throw new Exception ( '删除的数据不存在' );
			}else {
				
				if(empty($model -> card_id)){ //如果该券没有同步到微信的
					
					if ($change_type == 1){
						$model ['num'] = $model ['num'] + $edit_num;
					}else if ($change_type == 2){
						$model ['num'] = $model ['num'] - $edit_num;
					}
					if ($model->save ()) {
						$result ['status'] = ERROR_NONE; // 状态码
						$result ['errMsg'] = ''; // 错误信息
						$result ['data'] = '';
					} else {
						$result ['status'] = ERROR_SAVE_FAIL; // 状态码
						$result ['errMsg'] = '数据保存失败'; // 错误信息
						$result ['data'] = '';
					}
				}else{ //如果该券有同步到微信的    则调用修改库存接口
					$apiResult = $this->cardModifystock($coupons_id,$edit_num,$change_type);
					
					if($apiResult['errcode'] == 0){  //微信返回成功码
						if ($change_type == 1){
							$model ['num'] = $model ['num'] + $edit_num;
						}else if ($change_type == 2){
							$model ['num'] = $model ['num'] - $edit_num;
						}
						if ($model->save ()) {
							$result ['status'] = ERROR_NONE; // 状态码
							$result ['errMsg'] = ''; // 错误信息
							$result ['data'] = '';
						} else {
							$result ['status'] = ERROR_SAVE_FAIL; // 状态码
							$result ['errMsg'] = '数据保存失败'; // 错误信息
							$result ['data'] = '';
						}
					}
				}
			
			}
		} catch (Exception $e) {
			$result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
			$result['errMsg'] = $e->getMessage(); //错误信息
		}
		return json_encode($result);
	}
	
	/**
	 * 券详情
	 * $coupons_id  券id
	 */
	public function detail($coupons_id)
	{
		$result = array();
		$data = array();
		try {
			$model = Coupons::model()->findByPk($coupons_id);
			if (empty($model)) {
				$result['status'] = ERROR_NO_DATA;
				throw new Exception('删除的数据不存在');
			}else{
				$result['status'] = ERROR_NONE;
				$result['errMsg'] = '';
				
				$data['list']['id'] = $model['id'];
				$data['list']['merchant_short_name'] = $model['merchant_short_name'];
				$data['list']['type'] = $model['type']; //券类型
				$data['list']['if_wechat'] = $model['if_wechat']; //是否同步到微信卡包 1不开启 2开启
				$data['list']['title'] = $model['title']; //券标题
				$data['list']['vice_title'] = $model['vice_title']; //副标题
				$data['list']['money_type'] = $model['money_type']; //券金额类型 1固定 2随机
				$data['list']['money_random'] = $model['money_random']; //代金券的随机金额
				$data['list']['money'] = $model['money']; //代金券的固定金额
				$data['list']['discount'] = $model['discount']; //券折扣
				$data['list']['prompt'] = $model['prompt']; //提示操作
				$data['list']['if_share'] = $model['if_share']; //用户是否可以分享领取链接 1可以 2不可以
				$data['list']['if_give'] = $model['if_give']; //可否转增其他好友 1 能 2不能
				$data['list']['num'] = $model['num']; //发放数量
				$data['list']['get_num'] = $model['get_num']; //已领取数量
				$data['list']['time_type'] = $model['time_type']; //有效时间类型1固定时间 2相对时间
				$data['list']['start_time'] = $model['start_time']; //固定时间时的有效开始时间
				$data['list']['end_time'] = $model['end_time']; //固定时间有效结束时间
				$data['list']['start_days'] = $model['start_days']; //领取后几天生效 当天代表0最高90天
				$data['list']['effective_days'] = $model['effective_days']; //有效天数最少1天最多90天
				$data['list']['receive_num'] = $model['receive_num']; //每个用户领取数量
				$data['list']['mini_consumption'] = $model['mini_consumption']; //最低消费
				$data['list']['use_restriction'] = $model['use_restriction']; //使用限制
				$data['list']['if_with_userdiscount'] = $model['if_with_userdiscount']; //是否能与会员折扣同用1不能 2能
				$data['list']['store_limit'] = $model['store_limit']; //门店限制
				$data['list']['tel'] = $model['tel']; //客服电话
				$data['list']['use_illustrate'] = $model['use_illustrate']; //使用须知
				$data['list']['discount_illustrate'] = $model['discount_illustrate']; //优惠说明
				$data['list']['if_invalid'] = $model['if_invalid']; //是否失效 1未失效 2已失效
				$data['list']['create_time'] = $model['create_time']; //创建时间
				$data['list']['color'] = $model['color']; //券颜色
				$data['list']['store_limit_name'] = $this->getStoreName($model['store_limit']); //门店限制名称
				$data['list']['merchant_name'] = $this->getMerchantName($model['merchant_id']); //商户名称
				$data['list']['merchant_logo'] = $this->getMerchantLogo($model['merchant_id']); //商户logo
			}
						
		} catch (Exception $e) {
			$result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
			$result['errMsg'] = $e->getMessage(); //错误信息
		}
		$result['data'] = $data;
		return json_encode($result);
	}
	
	/**
	 * 添加门店
	 * $merchant_id  商户id
	 * $key_word     门店名搜索
	 */
	public function addStore($merchant_id,$key_word)
	{
		$result = array();
		$data = array();
		try {
			$criteria = new CDbCriteria();
			$criteria -> addCondition('merchant_id=:merchant_id and flag=:flag');
			$criteria -> params = array(':merchant_id'=>$merchant_id,':flag'=>FLAG_NO);
			
			//门店名搜索
			if(!empty($key_word)){
				$criteria -> addCondition('name=:name');
				$criteria -> params[':name'] = $key_word;
			}
			
			$criteria -> order = 'create_time desc';
			
			//分页
			$pages = new CPagination(Store::model()->count($criteria));
			$pages -> pageSize = 5;
			$pages -> applyLimit($criteria);
			$this->page = $pages;
			
			$store = Store::model()->findAll($criteria);
			if(!empty($store)){
				foreach ($store as $k => $v){
					$data['list'][$k]['id'] = $v['id'];
					$data['list'][$k]['name'] = $v['name']; //门店名
					$data['list'][$k]['address'] = $v['address']; //门店地址
				}
				$result['data'] = $data;
				$result ['status'] = ERROR_NONE;
			}else{
				$result ['status'] = ERROR_NO_DATA;
				$result['errMsg'] = '无此数据'; //错误信息
			}
		} catch (Exception $e) {
			$result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
			$result['errMsg'] = $e->getMessage(); //错误信息
		}
		
		return json_encode($result);
	}
	
	/**
	 * 获取门店总数
	 */
	public function getCountStore($merchant_id)
	{
		$model = Store::model()->findAll('merchant_id = :merchant_id and flag =:flag',array(':merchant_id'=>$merchant_id,':flag'=>FLAG_NO));
		return  count($model);
	}
	
	/**
	 * 根据主键获取门店信息
	 * $store_id  门店id
	 */
	public function getStore($store_id)
	{
		$result = array();
		$data = array();
		try {
			$model = Store::model()->findByPk($store_id);
			if(!empty($model)){
				$result['status'] = ERROR_NONE;
				$result['errMsg'] = '';
				
				$data['list']['id'] = $model['id'];
				$data['list']['name'] = $model['name'];
			}else{
				$result ['status'] = ERROR_NO_DATA;
				$result['errMsg'] = '无此数据'; //错误信息
			}
		} catch (Exception $e) {
			$result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
			$result['errMsg'] = $e->getMessage(); //错误信息
		}
		$result['data'] = $data;
		return json_encode($result);
	}
	
	/**
	 * 延长固定日期
	 * $coupons_id  券id
	 * $date        修改后的固定日期
	 */
	public function extendedTime($coupons_id,$date)
	{
		$result = array();
		$date_arr = explode('-', $date);		
		
		try {
			$model = Coupons::model()->findByPk($coupons_id);
			if($model -> if_wechat == IF_WECHAT_NO){ //如果没有同步到微信的
					$model -> end_time = $date_arr[1].' 23:59:59';
			    if ($model->save ()) {
					$result ['status'] = ERROR_NONE; // 状态码
					$result ['errMsg'] = ''; // 错误信息
				} else {
					$result ['status'] = ERROR_SAVE_FAIL; // 状态码
					$result ['errMsg'] = '数据保存失败'; // 错误信息
				}
			}else{ //如果同步到微信的   调用修改接口
				$apiResult = $this->cardUpdateForDate($model -> card_id,$date,$model -> type);
				if($apiResult['errcode'] == 0){
					$model -> end_time = $date_arr[1].' 23:59:59';
					if ($model->save ()) {
						$result ['status'] = ERROR_NONE; // 状态码
						$result ['errMsg'] = ''; // 错误信息
					} else {
						$result ['status'] = ERROR_SAVE_FAIL; // 状态码
						$result ['errMsg'] = '数据保存失败'; // 错误信息
					}
				}else {
					$result ['status'] = ERROR_EXCEPTION; // 状态码
					$result ['errMsg'] = '微信接口返回错误信息'.$apiResult['errcode']; // 错误信息
				}
			}
		} catch (Exception $e) {
			$result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
			$result['errMsg'] = $e->getMessage(); //错误信息
		}
		return json_encode($result);
	}
	
	/**
	 * 延长固定日期接口
	 * $card_id     微信卡券id
	 * $date        修改后的固定日期
	 * $type        券类型
	 */
	public function cardUpdateForDate($card_id,$date,$type)
	{		
		$date_arr = explode('-', $date);
		$begin_timestamp = strtotime($date_arr[0].' 00:00:00'); //固定时间开始时间的时间戳
		$end_timestamp = strtotime($date_arr[1].' 23:59:59');  //固定时间结束时间的时间戳
		
		//生成提交给微信服务器的数据BEGINING
		$array = array();
		$array['card_id'] = $card_id;
		
		
		$base_info = array();
		
			
	    $base_info['date_info'] = array('type'=>'DATE_TYPE_FIX_TIME_RANGE','begin_timestamp'=>$begin_timestamp,'end_timestamp'=>$end_timestamp);
		
		if ($type == COUPON_TYPE_CASH) {
			$array['cash']['base_info'] = $base_info;
		} elseif ($type == COUPON_TYPE_DISCOUNT) {
			$array['discount']['base_info'] = $base_info;
		} else {
			$array['gift']['base_info'] = $base_info;
		}
		
		//生成提交给微信服务器的数据END
		
		$url = 'https://api.weixin.qq.com/card/update?access_token='.$this->getToken();
		$postData = json_encode($array);
		$json_data = $this->postData($url, $postData);
		$json_data = json_decode($json_data,true);
		//var_dump($json_data);exit;
		return $json_data;
	}
	
	/**
	 * 获取logo_url
	 * $coupons_id  券id
	 * $color       券颜色
	 */
	public function getLogoUrl($coupons_id,$color)
	{
		$merchant_id = Yii::app()->session['merchant_id'];
		$merchant = Merchant::model()->findByPk($merchant_id);
		$onlineshop = Onlineshop::model()->find('merchant_id=:merchant_id',array(':merchant_id'=>$merchant_id));
 		$logo_img = '@'.YII::app()->basePath.'/../../upload/images/gj/source/'.$onlineshop -> logo_img;//'@http://upload.test.51wanquan.com/images/gj/source/20150921/150921093404282586.jpg';

		//获取token
		$access_token = $this->getToken();
		
		$postdata = array('buffer' => ($logo_img));
		$url = 'https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token='.$access_token;
		$json_data = $this->postData($url, $postdata);
		$json_data = json_decode($json_data,true);
		return $json_data['url'];
		//return $this->getCardId($coupons_id,$color,$json_data['url'],$access_token['access_token']);
	}
	
	/**
	 * 获取imageurl
	 */
	public function getImageUrl($image_url)
	{

		if (!empty($image_url)) {
			$img = '@'.UPLOAD_SYSTEM_PATH.'images/gj/source/'.$image_url;
			
			// if (class_exists('\CURLFile')) {
			// 	$postdata = array('buffer' => new \CURLFile($img));
			// } else {
			// 	$postdata = array('buffer' => '@' . $img);
			// }
			$postdata = array('buffer' => $img);
			//获取token
			$access_token = $this->getToken();
			$url = 'https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token='.$access_token;
			$json_data = $this->postData($url, $postdata);
			$json_data = json_decode($json_data,true);
			$upload_url = '';
			if (isset($json_data['url']) && !empty($json_data['url'])) {
				$upload_url = $json_data['url'];
			}
		}
		
		return $upload_url;
	}

	
	/**
	 * 获取token
	 */
	public function getToken()
	{	
		$merchant_id = Yii::app()->session['merchant_id'];
		$merchant = Merchant::model()->findByPk($merchant_id);
		//获取access_token
		$access_token = Wechat::getTokenByMerchant($merchant);
		return $access_token;
	}
	
	/**
	 * 创建卡券接口   返回card_id
	 * $coupons_id  券id
	 * $color       券颜色
	 * $logo_url    卡券logo  Url
	 * $access_token    token
	 */
	public function getCardId($coupons_id,$color,$logo_url,$access_token)
	{
		$model = Coupons::model()->findByPk($coupons_id);
		$card_type = '';
		$time_type = '';
		$begin_timestamp = ''; //固定时间开始时间的时间戳
		$end_timestamp = '';  //固定时间结束时间的时间戳
		$fixed_term = '';    //相对时间有效天数
		$fixed_begin_term = '';  //相对时间领取后几天生效 
		//DATE_TYPE_FIX_TIME_RANGE 表示固定日期区间，DATE_TYPE_FIX_TERM表示固定时长（自领取后按天算
		if($model->time_type == VALID_TIME_TYPE_FIXED){
			$time_type = 'DATE_TYPE_FIX_TIME_RANGE';
			$begin_timestamp = strtotime ( $model->start_time );
			$end_timestamp = strtotime ( $model->end_time );
		}else{
			$time_type = 'DATE_TYPE_FIX_TERM';
			$fixed_begin_term = $model -> start_days;
			$fixed_term = $model -> effective_days;
		}
		//券类型。
		if($model->type == COUPON_TYPE_CASH){
			$card_type = 'CASH';
		}elseif ($model->type == COUPON_TYPE_DISCOUNT){
			$card_type = 'DISCOUNT';
		}else{
			$card_type = 'GIFT';
		}
		
		//卡券领取页面是否可分享。
		$can_share = true;
		if($model->if_share == IF_SHARE_YES){
			$can_share = true;
		}else{
			$can_share = false;
		}
		
		//卡券是否可转赠
		$can_give_friend = true;
		if($model->if_give == IF_GIVE_YES){
			$can_give_friend = true;
		}else{
			$can_give_friend = false;
		}
		
		//生成提交给微信服务器的数据BEGINING
		$array = array();
		$array['card']['card_type'] = $card_type;
		
		$groupon = array();
		
		
		$base_info = array();
		$base_info['logo_url'] = $logo_url;
		$base_info['brand_name'] = urlencode($model->merchant_short_name);
		$base_info['code_type'] = 'CODE_TYPE_BARCODE'; //一维码 
		$base_info['title'] = urlencode($model->title);
		$base_info['sub_title'] = urlencode($model->vice_title);
		$base_info['color'] = $color;
		$base_info['notice'] = urlencode($model->prompt);
		$base_info['service_phone'] = ($model->tel);
		$base_info['description'] = urlencode($model->use_illustrate);
		
		if($model->time_type == VALID_TIME_TYPE_FIXED){
			$base_info['date_info'] = array('type'=>'DATE_TYPE_FIX_TIME_RANGE','begin_timestamp'=>$begin_timestamp,'end_timestamp'=>$end_timestamp);
		}else{
			$base_info['date_info'] = array('type'=>'DATE_TYPE_FIX_TERM','fixed_term'=>$fixed_term,'fixed_begin_term'=>$fixed_begin_term);
		}
		
		$base_info['sku'] = array('quantity'=>$model->num);
		$base_info['get_limit'] = $model->receive_num;
		$base_info['use_custom_code'] = false;
		$base_info['bind_openid'] = false;
// 		$base_info['custom_url_name'] = '入口1';
// 		$base_info['custom_url_sub_title'] ='自定义入口1';
// 		$base_info['custom_url'] = 'http://www.baidu.com';
// 		$base_info['promotion_url_name'] = '更多优惠';
// 		$base_info['promotion_url_sub_title'] = '营销活动1';
// 		$base_info['promotion_url'] = 'http://www.baidu.com';
		$base_info['can_share'] = $can_share;
		$base_info['can_give_friend'] = $can_give_friend;
		
		
		$groupon['base_info'] = $base_info;
		
		//券类型不同    参数有所不同
		if($model->type == COUPON_TYPE_CASH){
			$groupon['least_cost'] = ($model -> mini_consumption)*100; //代金券专用，表示起用金额（单位为分）,如果无起用门槛则填0。
			$groupon['reduce_cost'] = ($model -> money)*100; //代金券专用，表示减免金额。（单位为分）
		}elseif ($model->type == COUPON_TYPE_DISCOUNT){
			$groupon['discount'] = 100-($model -> discount)*100; //折扣券专用，表示打折额度（百分比）。填30就是七折
		}else{
			$groupon['gift'] = urlencode($model -> discount_illustrate);
		}
		
		if($model->type == COUPON_TYPE_CASH){ //代金券
			$array['card']['cash'] = $groupon;
		}elseif ($model->type == COUPON_TYPE_DISCOUNT){ //折扣券
			$array['card']['discount'] = $groupon;
		}else{ //兑换券
			$array['card']['gift'] = $groupon;
		}
		
		//生成提交给微信服务器的数据END
		
		$url = 'https://api.weixin.qq.com/card/create?access_token='.$access_token;
		//用PHP的json_encode来处理中文的时候，中文都会被编码，变成不可读的, 类似”/u***”的格式，微信服务器端不能接受。
		//PHP 5.4中对json_encode对options可选参数增加JSON_UNESCAPED_UNICODE常量，即不编码为unicode
		$postData = urldecode(json_encode($array));
		
		$json_data = $this->postData($url, $postData);
		$json_data = json_decode($json_data,true);
		//var_dump($json_data);exit;

		return $json_data;
		
	}
	
	/**
	 * 修改卡券接口
	 */
	public function cardUpdate($coupons_id, $if_wechat, $title, $vice_title, $discount, $prompt,
					                  $if_share, $if_give, $num, $time_type, $start_time, $end_time, $start_days,
					                  $effective_days, $receive_num, $mini_consumption, $if_with_userdiscount, $tel,
					                  $use_illustrate, $discount_illustrate, $money_type, $start_money, $end_money, $money,$store_limit,$color)
	{
		
		$model = Coupons::model()->findByPk($coupons_id);
		
		$begin_timestamp = ''; //固定时间开始时间的时间戳
		$end_timestamp = '';  //固定时间结束时间的时间戳
		
		if(!empty($start_time) && !empty($end_time)){
			$start_time = $start_time.' 00:00:00';
			$end_time = $end_time.' 23:59:59';
			$begin_timestamp = strtotime($start_time);
			$end_timestamp = strtotime($end_time);
		}
		
		if(!empty($if_share)){ //卡券原生领取页面是否可分享。
			$if_share = true;
		}else{ //卡券是否可转赠。
			$if_share = false;
		}
		
		if(!empty($if_give)){
			$if_give = true;
		}else{
			$if_give = false;
		}
		
		//生成提交给微信服务器的数据BEGINING
		$array = array();
		$array['card_id'] = $model['card_id'];
		

		$base_info = array();
		$base_info['logo_url'] = $this->getLogoUrl($coupons_id, $model->color);
		//echo $base_info['logo_url'];exit;
		$base_info['code_type'] = 'CODE_TYPE_BARCODE';
		$base_info['color'] = $color;
		$base_info['notice'] = urlencode($prompt);
		$base_info['service_phone'] = $tel;
		$base_info['description'] = urlencode($use_illustrate);
		
		if($model->time_type == VALID_TIME_TYPE_FIXED){
			$base_info['date_info'] = array('type'=>'DATE_TYPE_FIX_TIME_RANGE','begin_timestamp'=>$begin_timestamp,'end_timestamp'=>$end_timestamp);
		}else{
			//$base_info['date_info'] = array('type'=>'DATE_TYPE_FIX_TERM','fixed_term'=>'','fixed_begin_term'=>'');
		}
		
		//$base_info['sku'] = array('quantity'=>$model->num);
		$base_info['get_limit'] = $receive_num;
		//$base_info['use_custom_code'] = false;
		//$base_info['bind_openid'] = false;
		$base_info['can_share'] = $if_share;
		$base_info['can_give_friend'] = $if_give;
		
		if ($model->type == COUPON_TYPE_CASH) {
			$array['cash']['base_info'] = $base_info;
		} elseif ($model->type == COUPON_TYPE_DISCOUNT) {
			$array['discount']['base_info'] = $base_info;
		} else {
			$array['gift']['base_info'] = $base_info;
		}
		
		//生成提交给微信服务器的数据END
		
		$url = 'https://api.weixin.qq.com/card/update?access_token='.$this->getToken();
		$postData = urldecode(json_encode($array));
		$json_data = $this->postData($url, $postData);
		$json_data = json_decode($json_data,true);
		//var_dump($json_data);exit;
		return $json_data;
		
	}
	
	/**
	 * 删除卡券接口
	 * $coupons_id  券id
	 */
	public function cardDelete($coupons_id)
	{
		$model = Coupons::model()->findByPk($coupons_id);
		$card_id = $model['card_id'];
		$url = 'https://api.weixin.qq.com/card/delete?access_token='.$this->getToken();
		$postData = array('card_id'=>$card_id);
		$postData = json_encode($postData);
		$json_data = $this->postData($url, $postData);
		$json_data = json_decode($json_data,true);
		//var_dump($json_data);exit;
		return $json_data;
	}
	
	/**
	 * 修改卡券库存接口
	 * $coupons_id  券id
	 * $edit_num    新的库存量
	 */
	public function cardModifystock($coupons_id,$edit_num,$change_type)
	{
		$model = Coupons::model()->findByPk($coupons_id);
		$card_id = $model['card_id'];
		$old_edit_num = $model ['num']; //原库存量
 		$increase_stock_value = 0; //增加多少库存
 		$reduce_stock_value = 0; //减少多少库存
		
		$postData = array();
		if($change_type == 1){ //增加库存
			$increase_stock_value = $edit_num;
			$postData = array('card_id'=>$card_id,'increase_stock_value'=>$increase_stock_value);
		}else{ //减少库存
			$reduce_stock_value = $edit_num;
			$postData = array('card_id'=>$card_id,'reduce_stock_value'=>$reduce_stock_value);
		}
		
		$url = 'https://api.weixin.qq.com/card/modifystock?access_token='.$this->getToken();

		$postData = json_encode($postData);
		$json_data = $this->postData($url, $postData);
		$json_data = json_decode($json_data,true);
		//var_dump($json_data);exit;
		return $json_data;
	}
	
	
	
	/**
	 * 调用线下核销接口
	 * 步骤一：查询Code接口
	 * $code  单张卡券的唯一标准
	 */
	public function getCode($code)
	{
		$result = array();
		try {
			$user_coupons = UserCoupons::model ()->find ( 'code = :code and flag = :flag', array (
					':code' => $code ,
					':flag' => FLAG_NO
			) );
			
			$coupons = Coupons::model ()->findByPk ( $user_coupons->coupons_id );
			if(empty($coupons)){
				throw new Exception('卡券不存在');
			}
			$card_id = $coupons -> card_id;
			Yii::app()->session['merchant_id'] = $coupons['merchant_id'];
			$url = 'https://api.weixin.qq.com/card/code/get?access_token=' . $this->getToken ();
			$postData = array (
					'card_id' => $card_id,
					'code' => $code,
					'check_consume' => true 
			);
			$postData = json_encode ( $postData );
			$json_data = $this->postData ( $url, $postData );
			$json_data = json_decode ( $json_data, true );
			//return $json_data;
			$result['status'] = ERROR_NONE;
			$result['errcode'] = $json_data['errcode'];
			$result['can_consume'] = $json_data['can_consume'];  //返回can_consume是否可以核销
			
		} catch (Exception $e) {
			$result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
			$result['errMsg'] = $e->getMessage(); //错误信息
		}
		
		return json_encode($result);
		
	}
	
	/**
	 * 调用线下核销接口
	 * 步骤二：核销Code接口
	 * $code  单张卡券的唯一标准
	 */
	public function consumeCoupons($code)
	{
		$result = array();
		try {
			$getCodeResult = json_decode($this->getCode ($code),true); // 查询Code接口
			if ($getCodeResult ['errcode'] == 0) { // 查询Code接口返回正常状态
				if ($getCodeResult ['can_consume']) { // 如果可以核销
					$url = 'https://api.weixin.qq.com/card/code/consume?access_token=' . $this->getToken ();
					$postData = array (
							'code' => $code 
					);
					$postData = json_encode ( $postData );
					$json_data = $this->postData ( $url, $postData );
					$json_data = json_decode ( $json_data, true );
					
					$result['status'] = ERROR_NONE;
					$result['errMsg'] = '';
					$result['openId'] = $json_data['openid'];  //返回openid
				}else{
					throw new Exception('该卡券不能核销');
				}
			}else{
				$result['status'] = $getCodeResult['errcode'];
				$result['errMsg'] = $getCodeResult['errMsg'];
				throw new Exception('微信返回错误信息：'.$getCodeResult['errMsg']);
			}
		} catch (Exception $e) {
			$result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
			$result['errMsg'] = $e->getMessage(); //错误信息
		}
		return json_encode($result);
		
	}
	
	/**
	 * post提交数据
	 * @param type $url
	 * @param type $data
	 * @return type
	 */
	function  postData($url, $data)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//跳过SSL证书检查  https方式
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果成功只将结果返回，不自动输出任何内容。如果失败返回FALSE,不加这句返回始终是1
// 		curl_setopt ( $ch, CURLOPT_SAFE_UPLOAD, false);
		curl_setopt($ch, CURLOPT_POST, 1); //启用POST提交
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$file_contents = curl_exec($ch);
		if (curl_errno($ch)) {
			return 'Errno' . curl_error($ch);
		}
		curl_close($ch);
	
		return $file_contents;
	}
	
	public function getData($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//跳过SSL证书检查  https方式
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$output = curl_exec($ch);

		//释放curl句柄
		curl_close($ch);
		return $output; 		
	}
	
	//开通当前appid的券点
	public function cardPayActivate()
	{
		$access_token = $this->getToken();
		$url = 'https://api.weixin.qq.com/card/pay/activate?access_token='.$access_token;
		$json_data = $this->getData($url);
		$result = json_decode($json_data,true);
		return $result;
	}
	
	//新创建卡券
	public function TestCard($id)
	{
		
		$model = Coupons::model()->findByPk($id);
		$card_type = '';
		$time_type = '';
		$begin_timestamp = ''; //固定时间开始时间的时间戳
		$end_timestamp = '';  //固定时间结束时间的时间戳
		$fixed_term = '';    //相对时间有效天数
		$fixed_begin_term = '';  //相对时间领取后几天生效
		//DATE_TYPE_FIX_TIME_RANGE 表示固定日期区间，DATE_TYPE_FIX_TERM表示固定时长（自领取后按天算
		if($model->time_type == VALID_TIME_TYPE_FIXED){
			$time_type = 'DATE_TYPE_FIX_TIME_RANGE';
			$begin_timestamp = strtotime ( $model->start_time );
			$end_timestamp = strtotime ( $model->end_time );
		}else{
			$time_type = 'DATE_TYPE_FIX_TERM';
			$fixed_begin_term = $model -> start_days;
			$fixed_term = $model -> effective_days;
		}
		//券类型。
		if($model->type == COUPON_TYPE_CASH){
			$card_type = 'CASH';
		}elseif ($model->type == COUPON_TYPE_DISCOUNT){
			$card_type = 'DISCOUNT';
		}else{
			$card_type = 'GIFT';
		}
	
		//卡券领取页面是否可分享。
		$can_share = true;
		if($model->if_share == IF_SHARE_YES){
			$can_share = true;
		}else{
			$can_share = false;
		}
	
		//卡券是否可转赠
		$can_give_friend = true;
		if($model->if_give == IF_GIVE_YES){
			$can_give_friend = true;
		}else{
			$can_give_friend = false;
		}
	
		//生成提交给微信服务器的数据BEGINING
		$array = array();
		$array['card']['card_type'] = $card_type;
	
		$groupon = array();
	
		$logo_url = $model->logo_url;
		$color = $model->color;
		$base_info = array();
		$base_info['logo_url'] = $logo_url;
		$base_info['brand_name'] = urlencode($model->merchant_short_name);
		$base_info['code_type'] = 'CODE_TYPE_BARCODE'; //一维码
		$base_info['title'] = urlencode($model->title);
		$base_info['sub_title'] = urlencode($model->vice_title);
		$base_info['color'] = $color;
		$base_info['notice'] = urlencode($model->prompt);
		$base_info['service_phone'] = ($model->tel);
		$base_info['description'] = urlencode($model->use_illustrate);
	
		if($model->time_type == VALID_TIME_TYPE_FIXED){
			$base_info['date_info'] = array('type'=>'DATE_TYPE_FIX_TIME_RANGE','begin_timestamp'=>$begin_timestamp,'end_timestamp'=>$end_timestamp);
		}else{
			$base_info['date_info'] = array('type'=>'DATE_TYPE_FIX_TERM','fixed_term'=>$fixed_term,'fixed_begin_term'=>$fixed_begin_term);
		}
	
		$base_info['sku'] = array('quantity'=>$model->num);
		$base_info['get_limit'] = $model->receive_num;
		//创建自定义code码必填项
		$base_info['use_custom_code'] = false;
		//$base_info['get_custom_code_mode'] = 'GET_CUSTOM_CODE_MODE_DEPOSIT';
		
		$base_info['bind_openid'] = false;
		//营销入口
		if(!empty($model->marketing_entrance)){
			$marketing_entrance = json_decode($model->marketing_entrance);
			if (!empty($marketing_entrance->custom_url_name)){
			$base_info['custom_url_name'] = urlencode($marketing_entrance->custom_url_name);
			}
			if (!empty($marketing_entrance->custom_url_sub_title)){
				$base_info['custom_url_sub_title'] =urlencode($marketing_entrance->custom_url_sub_title);
			}
			if (!empty($marketing_entrance->custom_url)){
				$base_info['custom_url'] = $marketing_entrance->custom_url;
			}
			if (!empty($marketing_entrance->promotion_url_name)){
				$base_info['promotion_url_name'] = urlencode($marketing_entrance->promotion_url_name);
			}
			if (!empty($marketing_entrance->promotion_url)){
				$base_info['promotion_url'] = $marketing_entrance->promotion_url;
			}
			
		}
		$base_info['can_share'] = $can_share;
		$base_info['can_give_friend'] = $can_give_friend;
	
	
		$groupon['base_info'] = $base_info;


	
		//券类型不同    优惠参数有所不同
		if($model->type == COUPON_TYPE_CASH){
			$groupon['least_cost'] = ($model -> mini_consumption)*100; //代金券专用，表示起用金额（单位为分）,如果无起用门槛则填0。
			$groupon['reduce_cost'] = ($model -> money)*100; //代金券专用，表示减免金额。（单位为分）
		}elseif ($model->type == COUPON_TYPE_DISCOUNT){
			$groupon['discount'] = 100-($model -> discount)*100; //折扣券专用，表示打折额度（百分比）。填30就是七折
		}else{
			$groupon['gift'] = urlencode($model -> discount_illustrate);
		}
		//高级字段设置
		$advanced_info = array();
		//详情说明及配图
		$abstract = array();
		if (!empty($model->cover_img_url)){
			$model1 = json_decode($model->cover_img_url);
			if (!empty($model1->abstract)){
				$abstract_title = $model1->abstract;
				$abstract['abstract'] = urlencode($abstract_title);
			}
			if (!empty($model1->icon_url_list)){
				$abstract['icon_url_list'] = $model1->icon_url_list;
			}
			$advanced_info['abstract'] = $abstract;
		}
		//详情页面中的图文展示列
		$text_image_list = array();
		if (!empty($model['image_text_url'])){
			$model2 = json_decode($model['image_text_url']);
			$arr = array();
			foreach ($model2 as $k => $v)
			{
				if (!empty($v->image_url)){
					$arr['image_url'] = $v->image_url;
				}
				if (!empty($v->text)){
					$arr['text'] = urlencode($v->text);
				}
				$text_image_list[] = $arr;
			}
			$advanced_info['text_image_list'] = $text_image_list;
		}
		//使用时段限制
		$use_time_interval = array();
		if (!empty($model['use_time_interval'])){
			$model3 = json_decode($model['use_time_interval'],true);
			
			$arr2 = array();
			if (isset($model3) && !empty($model3)) {
				
				foreach ($model3 as $k => $v)
				{
				if (!empty($v['type'])){
					$arr2['type'] = $v['type'];
				}
				if (!empty($v['begin_hour'])){
				$arr2['begin_hour'] = $v['begin_hour'];
				}
				if (!empty($v['end_hour'])){
				$arr2['end_hour'] = $v['end_hour'];
				}
				if (!empty($v['begin_minute'])){
				$arr2['begin_minute'] = $v['begin_minute'];
				}
				if (!empty($v['end_minute'])){
				$arr2['end_minute'] = $v['end_minute'];
				}
				$use_time_interval[] = $arr2;
				}
			}
			
			$advanced_info['time_limit'] = $use_time_interval;
		}
		
		$groupon['advanced_info'] = $advanced_info;
		
		//
		if($model->type == COUPON_TYPE_CASH){ //代金券
			$array['card']['cash'] = $groupon;
		}elseif ($model->type == COUPON_TYPE_DISCOUNT){ //折扣券
			$array['card']['discount'] = $groupon;
		}else{ //兑换券
			$array['card']['gift'] = $groupon;
		}
		
		//生成提交给微信服务器的数据END
		$access_token = $this->getToken();
		$url = 'https://api.weixin.qq.com/card/create?access_token='.$access_token;
		//用PHP的json_encode来处理中文的时候，中文都会被编码，变成不可读的, 类似”/u***”的格式，微信服务器端不能接受。
		//PHP 5.4中对json_encode对options可选参数增加JSON_UNESCAPED_UNICODE常量，即不编码为unicode
		$postData = urldecode(json_encode($array));
		//var_dump($postData);
 		$json_data = $this->postData($url, $postData);
 		$json_data = json_decode($json_data,true);
		return $json_data;
	
	}
	
	//新编辑卡券
	public function CardCouponEdit($id)
	{
	
		$model = Coupons::model()->findByPk($id);
		$card_id = $model['card_id'];
		$card_type = '';
		$time_type = '';
		$begin_timestamp = ''; //固定时间开始时间的时间戳
		$end_timestamp = '';  //固定时间结束时间的时间戳
		$fixed_term = '';    //相对时间有效天数
		$fixed_begin_term = '';  //相对时间领取后几天生效
		//DATE_TYPE_FIX_TIME_RANGE 表示固定日期区间，DATE_TYPE_FIX_TERM表示固定时长（自领取后按天算
		if($model->time_type == VALID_TIME_TYPE_FIXED){
			$time_type = 'DATE_TYPE_FIX_TIME_RANGE';
			$begin_timestamp = strtotime ( $model->start_time );
			$end_timestamp = strtotime ( $model->end_time );
		}else{
			$time_type = 'DATE_TYPE_FIX_TERM';
			$fixed_begin_term = $model -> start_days;
			$fixed_term = $model -> effective_days;
		}
		//券类型。
		if($model->type == COUPON_TYPE_CASH){
			$card_type = 'CASH';
		}elseif ($model->type == COUPON_TYPE_DISCOUNT){
			$card_type = 'DISCOUNT';
		}else{
			$card_type = 'GIFT';
		}
	
		//卡券领取页面是否可分享。
		$can_share = true;
		if($model->if_share == IF_SHARE_YES){
			$can_share = true;
		}else{
			$can_share = false;
		}
	
		//卡券是否可转赠
		$can_give_friend = true;
		if($model->if_give == IF_GIVE_YES){
			$can_give_friend = true;
		}else{
			$can_give_friend = false;
		}
	
		//生成提交给微信服务器的数据BEGINING
		$array = array();
		
	
		$groupon = array();
	
		$logo_url = $model->logo_url;
		$color = $model->color;
		$base_info = array();
		$base_info['logo_url'] = $logo_url;
		//$base_info['brand_name'] = urlencode($model->merchant_short_name);
		$base_info['code_type'] = 'CODE_TYPE_BARCODE'; //一维码
		//$base_info['title'] = urlencode($model->title);
		//$base_info['sub_title'] = urlencode($model->vice_title);
		$base_info['color'] = $color;
		$base_info['notice'] = urlencode($model->prompt);
		$base_info['service_phone'] = ($model->tel);
		$base_info['description'] = urlencode($model->use_illustrate);
	
		if($model->time_type == VALID_TIME_TYPE_FIXED){
			$base_info['date_info'] = array('type'=>'DATE_TYPE_FIX_TIME_RANGE','begin_timestamp'=>$begin_timestamp,'end_timestamp'=>$end_timestamp);
		}else{
			$base_info['date_info'] = array('type'=>'DATE_TYPE_FIX_TERM','fixed_term'=>$fixed_term,'fixed_begin_term'=>$fixed_begin_term);
		}
	
		//$base_info['sku'] = array('quantity'=>$model->num);
		$base_info['get_limit'] = $model->receive_num;
		//创建自定义code码必填项
		//$base_info['use_custom_code'] = false;
		//$base_info['get_custom_code_mode'] = 'GET_CUSTOM_CODE_MODE_DEPOSIT';
	
		$base_info['bind_openid'] = false;
		//营销入口
		if(!empty($model->marketing_entrance)){
			$marketing_entrance = json_decode($model->marketing_entrance);
			if (!empty($marketing_entrance->custom_url_name)){
				$base_info['custom_url_name'] = urlencode($marketing_entrance->custom_url_name);
			}
			if (!empty($marketing_entrance->custom_url_sub_title)){
				$base_info['custom_url_sub_title'] =urlencode($marketing_entrance->custom_url_sub_title);
			}
			if (!empty($marketing_entrance->custom_url)){
				$base_info['custom_url'] = $marketing_entrance->custom_url;
			}
			if (!empty($marketing_entrance->promotion_url_name)){
				$base_info['promotion_url_name'] = urlencode($marketing_entrance->promotion_url_name);
			}
			if (!empty($marketing_entrance->promotion_url)){
				$base_info['promotion_url'] = $marketing_entrance->promotion_url;
			}
				
		}
		$base_info['can_share'] = $can_share;
		$base_info['can_give_friend'] = $can_give_friend;
	
	
		$groupon['base_info'] = $base_info;
	
	
	
// 		//券类型不同    优惠参数有所不同
// 		if($model->type == COUPON_TYPE_CASH){
// 			$groupon['least_cost'] = ($model -> mini_consumption)*100; //代金券专用，表示起用金额（单位为分）,如果无起用门槛则填0。
// 			$groupon['reduce_cost'] = ($model -> money)*100; //代金券专用，表示减免金额。（单位为分）
// 		}elseif ($model->type == COUPON_TYPE_DISCOUNT){
// 			$groupon['discount'] = 100-($model -> discount)*100; //折扣券专用，表示打折额度（百分比）。填30就是七折
// 		}else{
// 			$groupon['gift'] = urlencode($model -> discount_illustrate);
// 		}
		//高级字段设置
		$advanced_info = array();
		//详情说明及配图
		$abstract = array();
		if (!empty($model->cover_img_url)){
			$model1 = json_decode($model->cover_img_url);
			if (!empty($model1->abstract)){
				$abstract_title = $model1->abstract;
				$abstract['abstract'] = urlencode($abstract_title);
			}
			if (!empty($model1->icon_url_list)){
				$abstract['icon_url_list'] = $model1->icon_url_list;
			}
			$advanced_info['abstract'] = $abstract;
		}
		//详情页面中的图文展示列
		$text_image_list = array();
		if (!empty($model['image_text_url'])){
			$model2 = json_decode($model['image_text_url']);
			$arr = array();
			foreach ($model2 as $k => $v)
			{
				if (!empty($v->image_url)){
					$arr['image_url'] = $v->image_url;
				}
				if (!empty($v->text)){
					$arr['text'] = urlencode($v->text);
				}
				$text_image_list[] = $arr;
			}
			$advanced_info['text_image_list'] = $text_image_list;
		}
		//使用时段限制
		$use_time_interval = array();
		if (!empty($model['use_time_interval'])){
			$model3 = json_decode($model['use_time_interval'],true);
				
			$arr2 = array();
			if (isset($model3) && !empty($model3)) {
	
				foreach ($model3 as $k => $v)
				{
					if (!empty($v['type'])){
						$arr2['type'] = $v['type'];
					}
					if (!empty($v['begin_hour'])){
						$arr2['begin_hour'] = $v['begin_hour'];
					}
					if (!empty($v['end_hour'])){
						$arr2['end_hour'] = $v['end_hour'];
					}
					if (!empty($v['begin_minute'])){
						$arr2['begin_minute'] = $v['begin_minute'];
					}
					if (!empty($v['end_minute'])){
						$arr2['end_minute'] = $v['end_minute'];
					}
					$use_time_interval[] = $arr2;
				}
			}
				
			$advanced_info['time_limit'] = $use_time_interval;
		}
		//高级字段
		$groupon['advanced_info'] = $advanced_info;
		//id
		$array['card_id'] = $card_id;
		
		if($model->type == COUPON_TYPE_CASH){ //代金券
			$array['cash'] = $groupon;
		}elseif ($model->type == COUPON_TYPE_DISCOUNT){ //折扣券
			$array['discount'] = $groupon;
		}else{ //兑换券
			$array['gift'] = $groupon;
		}
	
		//生成提交给微信服务器的数据END
		$access_token = $this->getToken();
		$url = 'https://api.weixin.qq.com/card/update?access_token='.$access_token;
		//用PHP的json_encode来处理中文的时候，中文都会被编码，变成不可读的, 类似”/u***”的格式，微信服务器端不能接受。
		//PHP 5.4中对json_encode对options可选参数增加JSON_UNESCAPED_UNICODE常量，即不编码为unicode
		$postData = urldecode(json_encode($array));
		//var_dump($postData);
		$json_data = $this->postData($url, $postData);
		$json_data = json_decode($json_data,true);
		return $json_data;
	
	}
	

	
	/**
	 * 创建卡券
	 * $merchant_id,   商户id
	 * $type,          券类型
	 * $if_wechat,     是否同步到微信卡包 1不开启 2开启
	 * $title,         券标题
	 * $vice_title,    副标题
	 * $discount       券折扣
	 * $prompt,        提示操作
	 * $if_share,      用户是否可以分享领取链接 1可以 2不可以
	 * $if_give,       可否转增其他好友 1 能 2不能
	 * $num,           发放数量
	 * $time_type,     有效时间类型1固定时间 2相对时间
	 * $start_time,    固定时间时的有效开始时间      
	 * $end_time,      固定时间有效结束时间
	 * $start_days,    领取后几天生效 当天代表0最高90天
	 * $effective_days, 有效天数最少1天最多90天
	 * $receive_num,    每个用户领取数量
	 * $mini_consumption,  最低消费
	 * $if_with_userdiscount, 是否能与会员折扣同用1不能 2能
	 * $tel,                   客服电话
	 * $use_illustrate,        使用须知
	 * $discount_illustrate,    优惠说明
	 * $money_type,             券金额类型 1固定 2随机
	 * $strat_money,            随机金额开始金额
	 * $end_money,              随机金额结束金额
	 * $money                   固定金额
	 * $store_limit             门店限制
	 * $use_restriction         使用限制
	 * $short_name              商户简称
	 * $use_channel				核销渠道
	 */
	public function cardCouponsCreate($card_info,$merchant_id)
	{

		$result = array ();
		$errMsg = '';
		$flag = 0;
		$model = new Coupons();
		
		//验证商户简称
		if(empty($card_info['merchant_short_name'])){
			$result ['status'] = ERROR_PARAMETER_MISS;
			$flag = 1;
			$errMsg =  $errMsg .' 商户简称必填';
			Yii::app()->user -> setFlash('short_name_error','商户简称必填');
		}else{
			$len = mb_strlen($card_info['merchant_short_name'],'utf-8');
			if($len > 12){
				$result ['status'] = ERROR_PARAMETER_MISS;
				$flag = 1;
				$errMsg =  $errMsg .' 商户简称不得超过12';
				Yii::app()->user -> setFlash('short_name_error','商户简称不得超过12');
			}
		}
		
		//验证券标题
		if(empty($card_info['title'])){
			$result ['status'] = ERROR_PARAMETER_MISS;
			$flag = 1;
			$errMsg =  $errMsg .' 券标题必填';
			Yii::app()->user -> setFlash('title_error','券标题必填');
		}
		

		
		//验证折扣数
		if($card_info['type'] == COUPON_TYPE_DISCOUNT){//券类型是折扣券的时候进行验证
			if(empty($card_info['discount'])){
				$result ['status'] = ERROR_PARAMETER_MISS;
				$flag = 1;
				$errMsg =  $errMsg .' 折扣数必填';
				Yii::app()->user -> setFlash('discount_error','折扣数必填');
			}else{
				if($card_info['discount'] < 1 || $card_info['discount'] > 9.9){
					$result ['status'] = ERROR_PARAMETER_MISS;
					$errMsg = $errMsg . ' 折扣设置不合法';
					$flag = 1;
					Yii::app()->user -> setFlash('discount_error','折扣设置不合法');
				}
		
				if(!preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $card_info['discount'])){ //验证数字正则
					$result ['status'] = ERROR_PARAMETER_MISS;
					$errMsg = $errMsg . ' 折扣设置不合法';
					$flag = 1;
					Yii::app()->user -> setFlash('discount_error','折扣设置不合法');
				}
			}
		}
		
		//验证券金额
		if($card_info['type'] == COUPON_TYPE_CASH){ //券类型是代金券的时候进行验证

				if(empty($card_info['money'])){
					$result ['status'] = ERROR_PARAMETER_MISS;
					$flag = 1;
					$errMsg =  $errMsg .' 减免金额必填';
					Yii::app()->user -> setFlash('money_error','减免金额必填');
				}else {
					if(!preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $card_info['money'])){
						$result ['status'] = ERROR_PARAMETER_MISS;
						$flag = 1;
						$errMsg =  $errMsg .' 减免金额设置不合法';
						Yii::app()->user -> setFlash('money_error','减免金额设置不合法');
					}
					if($card_info['money'] <= 0){
						$result ['status'] = ERROR_PARAMETER_MISS;
						$flag = 1;
						$errMsg =  $errMsg .' 减免金额设置不合法';
						Yii::app()->user -> setFlash('money_error','减免金额设置不合法');
					}
				}
		}
		
		//验证使用限制
		if($card_info['type'] == COUPON_TYPE_CASH){ //券类型是代金券的时候进行验证
		
			if(empty($card_info['least_cost'])){
				$result ['status'] = ERROR_PARAMETER_MISS;
				$flag = 1;
				$errMsg =  $errMsg .' 减免条件必填';
				Yii::app()->user -> setFlash('least_cost_error','减免条件必填');
			}else {
				if(!preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $card_info['least_cost'])){
					$result ['status'] = ERROR_PARAMETER_MISS;
					$flag = 1;
					$errMsg =  $errMsg .' 减免条件设置不合法';
					Yii::app()->user -> setFlash('least_cost_error','减免条件设置不合法');
				}
				if($card_info['least_cost'] <= 0){
					$result ['status'] = ERROR_PARAMETER_MISS;
					$flag = 1;
					$errMsg =  $errMsg .' 减免条件设置不合法';
					Yii::app()->user -> setFlash('least_cost_error','减免条件设置不合法');
				}
			}
		}

		
		//验证个人领取限制数量
		if (! isset ( $card_info['receive_num'] ) || empty ( $card_info['receive_num'] )) {
			if($card_info['receive_num'] <= 0){
				$result ['status'] = ERROR_PARAMETER_MISS;
				$errMsg = $errMsg .' 领取限制不合法';
				$flag = 1;
				Yii::app()->user -> setFlash('receive_num_error','领取限制不合法');
			}
			if(!preg_match('/^[1-9]\d*$/', $card_info['receive_num'])){ //判断匹配大于0的正整数正则
				$result ['status'] = ERROR_PARAMETER_MISS;
				$errMsg = $errMsg .' 领取限制不合法';
				$flag = 1;
				Yii::app()->user -> setFlash('receive_num_error','领取限制不合法');
			}
		}
		
		//验证提示操作
		if (! isset ( $card_info['prompt'] ) || empty ( $card_info['prompt'] )) {
			$result ['status'] = ERROR_PARAMETER_MISS;
			$errMsg = $errMsg .' 提示操作必填';
			$flag = 1;
			Yii::app()->user -> setFlash('prompt_error','提示操作必填');
		}
		
		//验证固定时间或相对时间
		if($card_info['time_type'] == VALID_TIME_TYPE_FIXED){ //如果是固定时间
			if(empty($card_info['end_time']) || empty($card_info['start_time'])){
				$result ['status'] = ERROR_PARAMETER_MISS;
				$errMsg = $errMsg .' 固定时间必填';
				$flag = 1;
				Yii::app()->user -> setFlash('valid_time_error','固定时间必填');
			}
		}else { //如果是相对时间
				
		}
		
		//验证优惠说明
		if($card_info['type'] == COUPON_TYPE_EXCHANGE){ //券类型是兑换券的时候进行验证
			if(empty($card_info['gift_info'])){
				$result ['status'] = ERROR_PARAMETER_MISS;
				$errMsg = $errMsg .' 优惠说明必填';
				$flag = 1;
				Yii::app()->user -> setFlash('gift_info','优惠说明必填');
			}
		}
		
		//验证发放数量
		if (! isset ( $card_info['num'] ) || empty ( $card_info['num'] )) {
			$result ['status'] = ERROR_PARAMETER_MISS;
			$errMsg = $errMsg .' 发放量必填';
			$flag = 1;
			Yii::app()->user -> setFlash('num_error','发放量必填');
		}else{
			if($card_info['num'] <= 0){
				$result ['status'] = ERROR_PARAMETER_MISS;
				$errMsg = $errMsg .' 发放量不合法';
				$flag = 1;
				Yii::app()->user -> setFlash('num_error','发放量不合法');
			}
			if(!preg_match('/^[1-9]\d*$/', $card_info['num'])){ //判断匹配大于0的正整数正则
				$result ['status'] = ERROR_PARAMETER_MISS;
				$errMsg = $errMsg .' 发放量不合法';
				$flag = 1;
				Yii::app()->user -> setFlash('num_error','发放量不合法');
			}
		}
		
		if ($flag == 1) {
			$result ['status'] = ERROR_PARAMETER_MISS; // 状态码
			$result ['errMsg'] = $errMsg;
			
			//var_dump($result);
			return json_encode ( $result );
		}
		
		//验证结束
		$model = new Coupons();
		
		
		
		$code_model = Yii::app()->db->createCommand()
		->select('code')
		->from('wq_coupons')
		->where('`code` is NOT NULL')
		->queryAll();
		
		$code_arr = array();
		foreach ($code_model as $k => $v){
			$code_arr[] = $v['code'];
		}
		$code = $this->randomSTR(6);
		while(in_array($code,$code_arr)){
			$code = $this->randomSTR(6);
		}
		$model -> code = $code;
		
		$model -> logo = $card_info['logo'];
		$logo_url = $this->getImageUrl($card_info['logo']);
		$model -> logo_url = $logo_url;
		$model -> merchant_id = $merchant_id;
		$model -> merchant_short_name = $card_info['merchant_short_name'];
		$model -> type = $card_info['type']; //券类型
		$model -> title = $card_info['title']; //券标题
		$model -> vice_title = $card_info['vice_title']; //副标题
		$model -> discount = $card_info['discount']/10; //折扣数
		$model -> discount_illustrate = $card_info['gift_info'];//优惠说明
		$model -> prompt = $card_info['prompt']; //提示操作
		$model -> if_share = !empty($card_info['if_share'])?IF_SHARE_YES:IF_SHARE_NO; //用户是否可以分享领取链接 1可以 2不可以
		$model -> if_give = !empty($card_info['if_give'])?IF_GIVE_YES:IF_GIVE_NO; //可否转增其他好友 1 能 2不能
		$model -> num = $card_info['num']; //发放数量
		$model -> time_type = $card_info['time_type']; //有效时间类型1固定时间 2相对时间
		if(!empty($card_info['start_time'])){//固定时间时的有效开始时间
			$model -> start_time =$card_info['start_time'].' 00:00:00';
		}
		if(!empty($card_info['end_time'])){//固定时间有效结束时间
			$model -> end_time =$card_info['end_time'].' 23:59:59';
		}
		
		if($card_info['time_type'] == VALID_TIME_TYPE_RELATIVE){
			$model -> start_days = empty($card_info['start_days'])?0:$card_info['start_days']; //领取后几天生效 当天代表0最高90天
			$model -> effective_days = $card_info['effective_days'];  //有效天数最少1天最多90天
		}
		
		if(!empty($card_info['available_time'])){
			$model -> use_time_interval = json_encode($card_info['available_time']);
		}
		$model -> receive_num = $card_info['receive_num']; //每个用户领取数量
		if($card_info['type'] == 2){
			$model -> mini_consumption = $card_info['least_cost']; //最低消费
		}else if($card_info['type'] == 3){
			$model -> mini_consumption = $card_info['discount_rule'];
		}else if($card_info['type'] == 4){
			$model -> mini_consumption = $card_info['gift_rule'];
		}
		$model -> if_with_userdiscount = !empty($card_info['if_with_userdiscount'])?IF_WITH_USERDISCOUNT_YES:IF_WITH_USERDISCOUNT_NO; //是否能与会员折扣同用1不能 2能
		$model -> tel = $card_info['tel']; //客服电话
		$model -> use_illustrate = $card_info['use_illustrate']; //使用须知
		
		$model -> money = $card_info['money']; //代金券的固定金额
		$model -> color = $card_info['color']; //券颜色
		$model -> use_restriction = empty($card_info['use_restriction'])?'1':$card_info['use_restriction']; //使用限制
		$model -> use_channel = $card_info['use_channel']; //核销渠道
		
		if($card_info['store_limit_type'] == 1){
			$storeId = '';
			$store = Store::model()->findAll('flag=:flag and merchant_id=:merchant_id',array(':flag'=>FLAG_NO,':merchant_id'=>$merchant_id));
			foreach ($store as $key => $value){
				$storeId = $storeId .','.($value -> id);
			}
			$model -> store_limit = $storeId.',';
		}else {
			$model -> store_limit = $card_info['store_limit'] .',';
		}
		$model -> store_limit_type = $card_info['store_limit_type'];
		//封面图文
		$cover_img_url = array();
		if(!empty($card_info['cover_img']) && !empty($card_info['cover_img_text'])){
			$model->cover_img = $card_info['cover_img'];//封面图片
			$model->cover_title = $card_info['cover_img_text'];//封面图文
			$icon_url_list = $this->getImageUrl($card_info['cover_img']);//上传封面图片至微信返回的图片路径
			$cover_img_url['abstract'] = $card_info['cover_img_text'];
			$cover_img_url['icon_url_list'] = $icon_url_list;
			$model->cover_img_url = json_encode($cover_img_url);//微信的封面图文json字段
		}
		
		//图文

		$img_list = array();
		$txt_list = array();
		$image_text = array();
		$image_text_url = array();
		$image_url = '';

		if(!empty($card_info['img_text']) && !empty($card_info['img_text_title'])){
			$img_list = $card_info['img_text'];
			$txt_list = $card_info['img_text_title'];
			$length = count($img_list);	
			for ($i=0;$i<$length;$i++){
				$image_text[] = array('image_url'=>$img_list[$i],'text'=>$txt_list[$i]);
			}

			$model->image_text = json_encode($image_text);
			
			for ($j=0;$j<$length;$j++){
				if(!empty($img_list[$j])){
				$image_url = $this->getImageUrl($img_list[$j]);//上传封面图片至微信返回的图片路径
				$image_text_url[] = array('image_url'=>$image_url,'text'=>$txt_list[$j]);
				}
			}
			
			$model->image_text_url = json_encode($image_text_url);//微信的封面图文json字段
		}
		
		//营销入口
		$marketing_entrance = array();
		if(!empty($card_info['custom_url_name'])){
			$marketing_entrance['custom_url_name'] = $card_info['custom_url_name'];
		}
		if(!empty($card_info['custom_url_sub_title'])){
			$marketing_entrance['custom_url_sub_title'] = $card_info['custom_url_sub_title'];
		}
		if(!empty($card_info['custom_url'])){
			$marketing_entrance['custom_url'] = $card_info['custom_url'];
		}
		
		if(!empty($card_info['promotion_url_name'])){
			$marketing_entrance['promotion_url_name'] = $card_info['promotion_url_name'];
		}
		if(!empty($card_info['promotion_url'])){
			$marketing_entrance['promotion_url'] = $card_info['promotion_url'];
		}
		if(!empty($marketing_entrance)){
			$model ->marketing_entrance = json_encode($marketing_entrance);
		}
		
		$model -> create_time = date('Y-m-d H:i:s',time());
		
		$transaction = Yii::app()->db->beginTransaction();
		try {
			if ($model->save ()) {
				$apiResult = $this->TestCard($model->id);//调接口创建券
				//var_dump($apiResult);
				if ($apiResult['errcode'] == 0){ //微信成功返回码0
					$coupons = Coupons::model()->findByPk($model->id);
					$coupons['card_id'] = $apiResult['card_id'];
					if($coupons -> save()){
						$result ['errcode'] = 0;
					}
				}else{
					$coupons = Coupons::model()->findByPk($model->id);
					$coupons['status'] = WX_CHECK_NOTPASS;
					$coupons -> save();
					//						throw new Exception('微信卡券同步失败');
						 					Yii::log('创建卡券接口错误信息:'.$apiResult['errmsg'].'创建卡券接口错误码：', 'warning');
					$result ['errcode'] = 1;
					// 						Coupons::model()->findByPk($model->id)->delete();
				}
				$transaction -> commit();
				$result ['status'] = ERROR_NONE; // 状态码
				$result ['errMsg'] = ''; // 错误信息
			} else {
				$result ['status'] = ERROR_SAVE_FAIL; // 状态码
				$result ['errMsg'] = '数据保存失败'; // 错误信息
			}
		}
		catch (Exception $e) {
			$transaction->rollback(); //如果操作失败, 数据回滚
			$result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
			$result['errMsg'] = $e->getMessage(); //错误信息
		
		}
		return json_encode ( $result );
	}
	
	/**
	 * 编辑卡券
	 */
	public function cardCouponsEdit($card_info,$coupons_id)
	{
	
		$result = array ();
		$errMsg = '';
		$flag = 0;
		$model = Coupons::model()->findByPk($coupons_id);
		$merchant_id = $model['merchant_id'];
		//验证商户简称
		if(empty($card_info['merchant_short_name'])){
			$result ['status'] = ERROR_PARAMETER_MISS;
			$flag = 1;
			$errMsg =  $errMsg .' 商户简称必填';
			Yii::app()->user -> setFlash('short_name_error','商户简称必填');
		}else{
			$len = mb_strlen($card_info['merchant_short_name'],'utf-8');
			if($len > 12){
				$result ['status'] = ERROR_PARAMETER_MISS;
				$flag = 1;
				$errMsg =  $errMsg .' 商户简称不得超过12';
				Yii::app()->user -> setFlash('short_name_error','商户简称不得超过12');
			}
		}
	
		//验证券标题
		if(empty($card_info['title'])){
			$result ['status'] = ERROR_PARAMETER_MISS;
			$flag = 1;
			$errMsg =  $errMsg .' 券标题必填';
			Yii::app()->user -> setFlash('title_error','券标题必填');
		}
	
	
	
		//验证折扣数
		if($card_info['type'] == COUPON_TYPE_DISCOUNT){//券类型是折扣券的时候进行验证
			if(empty($card_info['discount'])){
				$result ['status'] = ERROR_PARAMETER_MISS;
				$flag = 1;
				$errMsg =  $errMsg .' 折扣数必填';
				Yii::app()->user -> setFlash('discount_error','折扣数必填');
			}else{
				if($card_info['discount'] < 1 || $card_info['discount'] > 9.9){
					$result ['status'] = ERROR_PARAMETER_MISS;
					$errMsg = $errMsg . ' 折扣设置不合法';
					$flag = 1;
					Yii::app()->user -> setFlash('discount_error','折扣设置不合法');
				}
	
				if(!preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $card_info['discount'])){ //验证数字正则
					$result ['status'] = ERROR_PARAMETER_MISS;
					$errMsg = $errMsg . ' 折扣设置不合法';
					$flag = 1;
					Yii::app()->user -> setFlash('discount_error','折扣设置不合法');
				}
			}
		}
	
		//验证券金额
// 		if($card_info['type'] == COUPON_TYPE_CASH){ //券类型是代金券的时候进行验证
	
// 			if(empty($card_info['money'])){
// 				$result ['status'] = ERROR_PARAMETER_MISS;
// 				$flag = 1;
// 				$errMsg =  $errMsg .' 减免金额必填';
// 				Yii::app()->user -> setFlash('money_error','减免金额必填');
// 			}else {
// 				if(!preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $card_info['money'])){
// 					$result ['status'] = ERROR_PARAMETER_MISS;
// 					$flag = 1;
// 					$errMsg =  $errMsg .' 减免金额设置不合法';
// 					Yii::app()->user -> setFlash('money_error','减免金额设置不合法');
// 				}
// 				if($card_info['money'] <= 0){
// 					$result ['status'] = ERROR_PARAMETER_MISS;
// 					$flag = 1;
// 					$errMsg =  $errMsg .' 减免金额设置不合法';
// 					Yii::app()->user -> setFlash('money_error','减免金额设置不合法');
// 				}
// 			}
// 		}
	
		//验证使用限制
// 	if($card_info['type'] == COUPON_TYPE_CASH){ //券类型是代金券的时候进行验证
		
// 		if(empty($card_info['least_cost'])){
// 			$result ['status'] = ERROR_PARAMETER_MISS;
// 			$flag = 1;
// 			$errMsg =  $errMsg .' 减免条件必填';
// 			Yii::app()->user -> setFlash('least_cost_error','减免条件必填');
// 		}else {
// 			if(!preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $card_info['least_cost'])){
// 				$result ['status'] = ERROR_PARAMETER_MISS;
// 				$flag = 1;
// 				$errMsg =  $errMsg .' 减免条件设置不合法';
// 				Yii::app()->user -> setFlash('least_cost_error','减免条件设置不合法');
// 			}
// 			if($card_info['least_cost'] <= 0){
// 				$result ['status'] = ERROR_PARAMETER_MISS;
// 				$flag = 1;
// 				$errMsg =  $errMsg .' 减免条件设置不合法';
// 				Yii::app()->user -> setFlash('least_cost_error','减免条件设置不合法');
// 			}
// 		}
// 	}
	
	
		//验证个人领取限制数量
		if (! isset ( $card_info['receive_num'] ) || empty ( $card_info['receive_num'] )) {
			if($card_info['receive_num'] <= 0){
				$result ['status'] = ERROR_PARAMETER_MISS;
				$errMsg = $errMsg .' 领取限制不合法';
				$flag = 1;
				Yii::app()->user -> setFlash('receive_num_error','领取限制不合法');
			}
			if(!preg_match('/^[1-9]\d*$/', $card_info['receive_num'])){ //判断匹配大于0的正整数正则
				$result ['status'] = ERROR_PARAMETER_MISS;
				$errMsg = $errMsg .' 领取限制不合法';
				$flag = 1;
				Yii::app()->user -> setFlash('receive_num_error','领取限制不合法');
			}
		}
	
		//验证提示操作
		if (! isset ( $card_info['prompt'] ) || empty ( $card_info['prompt'] )) {
			$result ['status'] = ERROR_PARAMETER_MISS;
			$errMsg = $errMsg .' 提示操作必填';
			$flag = 1;
			Yii::app()->user -> setFlash('prompt_error','提示操作必填');
		}
	
		//验证固定时间或相对时间
		if($card_info['time_type'] == VALID_TIME_TYPE_FIXED){ //如果是固定时间
			if(empty($card_info['end_time']) || empty($card_info['start_time'])){
				$result ['status'] = ERROR_PARAMETER_MISS;
				$errMsg = $errMsg .' 固定时间必填';
				$flag = 1;
				Yii::app()->user -> setFlash('valid_time_error','固定时间必填');
			}
		}else { //如果是相对时间
	
		}
	
		//验证优惠说明
		if($card_info['type'] == COUPON_TYPE_EXCHANGE){ //券类型是兑换券的时候进行验证
			if(empty($card_info['discount_illustrate'])){
				$result ['status'] = ERROR_PARAMETER_MISS;
				$errMsg = $errMsg .' 优惠说明必填';
				$flag = 1;
				Yii::app()->user -> setFlash('discount_illustrate_error','优惠说明必填');
			}
		}
	
			//验证发放数量
// 		if (! isset ( $card_info['num'] ) || empty ( $card_info['num'] )) {
// 			$result ['status'] = ERROR_PARAMETER_MISS;
// 			$errMsg = $errMsg .' 发放量必填';
// 			$flag = 1;
// 			Yii::app()->user -> setFlash('num_error','发放量必填');
// 		}else{
// 			if($card_info['num'] <= 0){
// 				$result ['status'] = ERROR_PARAMETER_MISS;
// 				$errMsg = $errMsg .' 发放量不合法';
// 				$flag = 1;
// 				Yii::app()->user -> setFlash('num_error','发放量不合法');
// 			}
// 			if(!preg_match('/^[1-9]\d*$/', $card_info['num'])){ //判断匹配大于0的正整数正则
// 				$result ['status'] = ERROR_PARAMETER_MISS;
// 				$errMsg = $errMsg .' 发放量不合法';
// 				$flag = 1;
// 				Yii::app()->user -> setFlash('num_error','发放量不合法');
// 			}
// 		}
	
		if ($flag == 1) {
			$result ['status'] = ERROR_PARAMETER_MISS; // 状态码
			$result ['errMsg'] = $errMsg;
			//var_dump($result);
			return json_encode ( $result );
		}
	
		//验证结束
		
	
	
	

	
		$model -> logo = $card_info['logo'];
		$logo_url = $this->getImageUrl($card_info['logo']);
		$model -> logo_url = $logo_url;
		$model -> merchant_id = $merchant_id;
		$model -> merchant_short_name = $card_info['merchant_short_name'];
		$model -> type = $card_info['type']; //券类型
		//$model -> title = $card_info['title']; //券标题
		//$model -> vice_title = $card_info['vice_title']; //副标题
		//$model -> discount = $card_info['discount']/10; //折扣数
		//$model -> discount_illustrate = $card_info['gift_info'];//优惠说明
		$model -> prompt = $card_info['prompt']; //提示操作
		$model -> if_share = !empty($card_info['if_share'])?IF_SHARE_YES:IF_SHARE_NO; //用户是否可以分享领取链接 1可以 2不可以
		$model -> if_give = !empty($card_info['if_give'])?IF_GIVE_YES:IF_GIVE_NO; //可否转增其他好友 1 能 2不能
		//$model -> num = $card_info['num']; //发放数量
		$model -> time_type = $card_info['time_type']; //有效时间类型1固定时间 2相对时间
		if(!empty($card_info['start_time'])){//固定时间时的有效开始时间
			$model -> start_time =$card_info['start_time'].' 00:00:00';
		}
		if(!empty($card_info['end_time'])){//固定时间有效结束时间
			$model -> end_time =$card_info['end_time'].' 23:59:59';
		}
	
		if($card_info['time_type'] == VALID_TIME_TYPE_RELATIVE){
			$model -> start_days = empty($card_info['start_days'])?0:$card_info['start_days']; //领取后几天生效 当天代表0最高90天
			$model -> effective_days = $card_info['effective_days'];  //有效天数最少1天最多90天
		}
	
		if($card_info['available_time_type'] == 1){
			$model -> use_time_interval = '';
		}else {
			if(!empty($card_info['available_time'])){
				$model -> use_time_interval = json_encode($card_info['available_time']);
			}else{
				$model -> use_time_interval = '';
			}
		}
		
		$model -> receive_num = $card_info['receive_num']; //每个用户领取数量
		if($card_info['type'] == 2){
			$model -> mini_consumption = $card_info['least_cost']; //最低消费
		}else if($card_info['type'] == 3){
			$model -> mini_consumption = $card_info['discount_rule'];
		}else if($card_info['type'] == 4){
			$model -> mini_consumption = $card_info['gift_rule'];
		}
		$model -> if_with_userdiscount = !empty($card_info['if_with_userdiscount'])?IF_WITH_USERDISCOUNT_YES:IF_WITH_USERDISCOUNT_NO; //是否能与会员折扣同用1不能 2能
		$model -> tel = $card_info['tel']; //客服电话
		$model -> use_illustrate = $card_info['use_illustrate']; //使用须知
	
		//$model -> money = $card_info['money']; //代金券的固定金额
		$model -> color = $card_info['color']; //券颜色
		$model -> use_restriction = empty($card_info['use_restriction'])?'1':$card_info['use_restriction']; //使用限制
		$model -> use_channel = $card_info['use_channel']; //核销渠道
	
		if($card_info['store_limit_type'] == 1){
			$storeId = '';
			$store = Store::model()->findAll('flag=:flag and merchant_id=:merchant_id',array(':flag'=>FLAG_NO,':merchant_id'=>$merchant_id));
			foreach ($store as $key => $value){
				$storeId = $storeId .','.($value -> id);
			}
			$model -> store_limit = $storeId.',';
		}else {
			$model -> store_limit = $card_info['store_limit'] .',';
		}
		//封面图文
		$cover_img_url = array();
		if(!empty($card_info['cover_img']) && !empty($card_info['cover_img_text'])){
			$model->cover_img = $card_info['cover_img'];//封面图片
			$model->cover_title = $card_info['cover_img_text'];//封面图文
			$icon_url_list = $this->getImageUrl($card_info['cover_img']);//上传封面图片至微信返回的图片路径
			$cover_img_url['abstract'] = $card_info['cover_img_text'];
			$cover_img_url['icon_url_list'] = $icon_url_list;
			$model->cover_img_url = json_encode($cover_img_url);//微信的封面图文json字段
		}
	
		//图文
	
		$img_list = array();
		$txt_list = array();
		$image_text = array();
		$image_text_url = array();
		$image_url = '';
	
		if(!empty($card_info['img_text']) && !empty($card_info['img_text_title'])){
			$img_list = $card_info['img_text'];
			$txt_list = $card_info['img_text_title'];
			
			foreach ($img_list as $k => $v){
				
					if(isset($v) && !empty($v)){
						$image_text[] = array('image_url'=>$v,'text'=>$txt_list[$k]);
						$image_url = $this->getImageUrl($v);//上传封面图片至微信返回的图片路径
						$image_text_url[] = array('image_url'=>$image_url,'text'=>$txt_list[$k]);
					}
				
			}
	
			$model->image_text = json_encode($image_text);			
	
			$model->image_text_url = json_encode($image_text_url);//微信的封面图文json字段
		}
	
		//营销入口
		$marketing_entrance = array();
		if(!empty($card_info['custom_url_name'])){
			$marketing_entrance['custom_url_name'] = $card_info['custom_url_name'];
		}
		if(!empty($card_info['custom_url_sub_title'])){
			$marketing_entrance['custom_url_sub_title'] = $card_info['custom_url_sub_title'];
		}
		if(!empty($card_info['custom_url'])){
			$marketing_entrance['custom_url'] = $card_info['custom_url'];
		}
	
		if(!empty($card_info['promotion_url_name'])){
			$marketing_entrance['promotion_url_name'] = $card_info['promotion_url_name'];
		}
		if(!empty($card_info['promotion_url'])){
			$marketing_entrance['promotion_url'] = $card_info['promotion_url'];
		}
		if(!empty($marketing_entrance)){
			$model ->marketing_entrance = json_encode($marketing_entrance);
		}
	
		$model -> last_time = date('Y-m-d H:i:s',time());
		//var_dump( $model->save());
		$transaction = Yii::app()->db->beginTransaction();
		try {
			if ($model->save()) {

				//如果原来是同步微信的        调用修改卡券接口
				if (!empty($model->card_id)) {
					//调用修改卡券接口
					$apiResult = $this->CardCouponEdit($coupons_id);
					//var_dump($apiResult);
					if ($apiResult['errcode'] == 0) { //微信返回成功码为0
						$transaction->commit();
						$result ['status'] = ERROR_NONE; // 状态码
						$result ['errMsg'] = ''; // 错误信息
					} else {
						$transaction->rollback(); //如果操作失败, 数据回滚
						$result ['status'] = ERROR_SAVE_FAIL; // 状态码
						$result ['errMsg'] = '微信接口返回错误信息' . $apiResult['errcode']; // 错误信息
						Yii::log('修改卡券接口错误信息:' . $apiResult['errmsg'] . '创建卡券接口错误码：' . $apiResult['errcode'], 'warning');
					}
				} else {
					$transaction->commit();
					$result ['status'] = ERROR_NONE; // 状态码
					$result ['errMsg'] = ''; // 错误信息
				}
			} else {
				$result ['status'] = ERROR_SAVE_FAIL; // 状态码
				$result ['errMsg'] = '数据保存失败'; // 错误信息
			}
		}
		catch (Exception $e) {
              $transaction->rollback(); //如果操作失败, 数据回滚
              $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
              $result['errMsg'] = $e->getMessage(); //错误信息
              
        }
		return json_encode ( $result );
	}
	
	/**
	 * 获取卡券列表
	 * $merchant_id  商户id
	 * $key_woed     输入优惠券名称搜索
	 */
	public function getCouponList($merchant_id,$search_title,$search_status,$status_type,$search_type)
	{
		$result = array();
		try {
				
			$criteria = new CDbCriteria();
			$criteria -> addCondition('merchant_id=:merchant_id and flag=:flag');
			$criteria -> params = array(':merchant_id'=>$merchant_id,':flag' => FLAG_NO);
				
			//输入优惠券名称搜索
			if(!empty($search_title)){
				// 				$criteria -> addCondition('title=:title');
				// 				$criteria -> params[':title'] = $key_woed;
				$criteria -> addSearchCondition('title',$search_title);
			}
			
			//输入优惠券状态搜索
			if(!empty($search_status) && !empty($status_type)){
				// 				$criteria -> addCondition('title=:title');
				// 				$criteria -> params[':title'] = $key_woed;
				if ($status_type == 1){
					$criteria -> addSearchCondition('status',$search_status);
				}else if ($status_type == 2){
					$criteria -> addSearchCondition('release_status',$search_status);
				}
			}
			
			//输入优惠券类型搜索
			if(!empty($search_type)){
				// 				$criteria -> addCondition('title=:title');
				// 				$criteria -> params[':title'] = $key_woed;
				$criteria -> addSearchCondition('type',$search_type);
			}
				
			
			if(IF_INVALID_NO > IF_INVALID_YES){
				$criteria -> order = 'if_invalid desc ,create_time desc';
			}else {
				$criteria -> order = 'if_invalid asc ,create_time desc ';
			}
				
			//分页
			$pages = new CPagination(Coupons::model()->count($criteria));
			$pages -> pageSize = Yii::app()-> params['perPage'];
			$pages -> applyLimit($criteria);
			$this->page = $pages;
				
			$model = Coupons::model()->findall($criteria);
			$data = array ();
			if(!empty($model)){
				foreach ($model as $k => $v){
					$data['list'][$k]['id'] = $v['id'];
					$data['list'][$k]['type'] = $v['type']; //券类型
					$data['list'][$k]['if_wechat'] = $v['if_wechat']; //是否同步到微信卡包 1不开启 2开启
					$data['list'][$k]['title'] = $v['title']; //券标题
					$data['list'][$k]['vice_title'] = $v['vice_title']; //副标题
					$data['list'][$k]['money'] = $v['money']; //代金券的固定金额
					$data['list'][$k]['discount'] = $v['discount']; //券折扣
					$data['list'][$k]['prompt'] = $v['prompt']; //提示操作
					$data['list'][$k]['if_share'] = $v['if_share']; //用户是否可以分享领取链接 1可以 2不可以
					$data['list'][$k]['if_give'] = $v['if_give']; //可否转增其他好友 1 能 2不能
					$data['list'][$k]['num'] = $v['num']; //发放数量
					$data['list'][$k]['get_num'] = $v['get_num']; //已领取数量
					$data['list'][$k]['time_type'] = $v['time_type']; //有效时间类型1固定时间 2相对时间
					$data['list'][$k]['start_time'] = $v['start_time']; //固定时间时的有效开始时间
					$data['list'][$k]['end_time'] = $v['end_time']; //固定时间有效结束时间
					$data['list'][$k]['start_days'] = $v['start_days']; //领取后几天生效 当天代表0最高90天
					$data['list'][$k]['effective_days'] = $v['effective_days']; //有效天数最少1天最多90天
					$data['list'][$k]['receive_num'] = $v['receive_num']; //每个用户领取数量
					$data['list'][$k]['mini_consumption'] = $v['mini_consumption']; //最低消费
					$data['list'][$k]['use_restriction'] = $v['use_restriction']; //使用限制
					$data['list'][$k]['if_with_userdiscount'] = $v['if_with_userdiscount']; //是否能与会员折扣同用1不能 2能
					$data['list'][$k]['store_limit'] = $v['store_limit']; //门店限制
					$data['list'][$k]['tel'] = $v['tel']; //客服电话
					$data['list'][$k]['use_illustrate'] = $v['use_illustrate']; //使用须知
					$data['list'][$k]['discount_illustrate'] = $v['discount_illustrate']; //优惠说明
					$data['list'][$k]['if_invalid'] = $v['if_invalid']; //是否失效 1未失效 2已失效
					$data['list'][$k]['create_time'] = $v['create_time']; //创建时间
					$data['list'][$k]['get_receive_num'] = $this->getReceiveNum($v['id']); //券领取次数
					$data['list'][$k]['use_receive_num'] = $this->getUseReceiveNum($v['id']); //券使用次数
					$data['list'][$k]['receive_per'] = $this->getReceivePer($v['id']); //券领取人数
					$data['list'][$k]['status'] = $v['status']; //微信审核状态   1审核中 2已通过 3未通过
					$data['list'][$k]['release_status'] = $v['release_status']; //投放状态   1未投放 2已投放 
					$data['list'][$k]['code'] = $v['code']; //卡券链接参数，id对应的code
				}
				$result['data'] = $data;
				$result['status'] = ERROR_NONE;
			}else{
				$result ['status'] = ERROR_NO_DATA;
				$result['errMsg'] = '无此数据'; //错误信息
			}
				
		} catch (Exception $e) {
			$result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
			$result['errMsg'] = $e->getMessage(); //错误信息
		}
	
		return json_encode($result);
	}
	
	/**
	 * 卡券报表(单张券)
	 * $code  券code
	 */
	public function getCoupondataOne($code,$search)
	{
		$result = array();
		
		$coupon_info = array();
		$coupon_data = array();
		$coupon_id = '';
		
		$coupon_model = Coupons::model()->find('code=:code and flag=:flag',array(':code'=>$code,':flag'=>FLAG_NO));
		$coupon_id = $coupon_model['id'];
		$coupon_type = array ();
		$coupon_type ['2'] = '代金券';
		$coupon_type ['3'] = '折扣券';
		$coupon_type ['4'] = '兑换券';
		
			$coupon_info['title'] = $coupon_model['title'];
			$coupon_id = $coupon_model['id'];
			$coupon_info['num'] = $coupon_model['num'];
			$coupon_info['type'] = $coupon_model['type'];
			$result['coupon_info'] = $coupon_info;
		
		
		//券最新的一条记录
		$coupon_new_data = CouponStatistics::model()->find(array(
				'select' => '*',
				'order' => 'date DESC',
				'condition' => 'coupon_id = :coupon_id AND flag = :flag',
				'params' => array(':coupon_id'=>$coupon_id,':flag' => FLAG_NO)
		));
		
		//浏览总量
		$wx_browse_num = '0';
		$coupon_data['total_browse_num'] = '0';
		if(!empty($coupon_new_data['total_browse_num'])){
			$coupon_data['total_browse_num'] = $coupon_new_data['total_browse_num'];
		}
		if(!empty($coupon_model['card_id'])){
			$card_id = $coupon_model['card_id'];
			$wx_begin_date = date('Y-m-d', strtotime('-60 days'));
			$wx_end_date = date('Y-m-d', strtotime('-1 days'));
			$wx_result = $this->getWxCardInfo($card_id, $wx_begin_date, $wx_end_date);
			if(isset($wx_result['list']) && !empty($wx_result['list'])){
				foreach ($wx_result['list'] as $k=>$v){
					$wx_browse_num += $v['view_cnt'];
				}
			}
		}
		$coupon_data['total_browse_num'] += $wx_browse_num;
		
		//领取总量
		$coupon_data['total_receive_num'] = '0';
		if(!empty($coupon_new_data['total_receive_num'])){
			$coupon_data['total_receive_num'] = $coupon_new_data['total_receive_num'];
		}
		//使用总量
		$coupon_data['total_use_num'] = '0';
		if(!empty($coupon_new_data['total_use_num'])){
			$coupon_data['total_use_num'] = $coupon_new_data['total_use_num'];
		}
		//累计用券订单笔数
		$coupon_data['total_order_num'] = '0';
		if(!empty($coupon_new_data['total_order_num'])){
			$coupon_data['total_order_num'] = $coupon_new_data['total_order_num'];
		}
		//累计消费金额
		$coupon_data['total_trade_money'] = '0';
		if(!empty($coupon_new_data['total_trade_money'])){
			$coupon_data['total_trade_money'] = $coupon_new_data['total_trade_money'];
		}
		//累计券抵扣金额
		$coupon_data['total_discount_money'] = '0';
		if(!empty($coupon_new_data['total_discount_money'])){
			$coupon_data['total_discount_money'] = $coupon_new_data['total_discount_money'];
		}
		//累计现金消费金额
		$coupon_data['total_cash_money'] = '0';
		if(!empty($coupon_new_data['total_cash_money'])){
			$coupon_data['total_cash_money'] = $coupon_new_data['total_cash_money'];
		}
		//累计非现金消费金额
		$coupon_data['total_notcash_money'] = '0';
		if(!empty($coupon_new_data['total_notcash_money'])){
			$coupon_data['total_notcash_money'] = $coupon_new_data['total_notcash_money'];
		}
		
		
		$start_date = '';
		$end_date = '';
		$times = array();
		if(isset($search['time']) && !empty($search['time'])){
			$times = explode(' - ', $search['time']);
			$begin_time = $times[0];
			$end_time = $times[1];

			$begin_time = strtotime($begin_time);
			$end_time = strtotime($end_time) + 86400;
		}
		
		//对时间进行处理，添加图表的日期
		$start_date = strtotime($times[0]);
		$end_date = strtotime($times[1]);
		
		$time_data = array();
		while ($start_date <= $end_date) {
			$time_data[date('Y-m-d', $start_date)]['new_browse_num'] = 0;
			$time_data[date('Y-m-d', $start_date)]['new_browse_person_num'] = 0;

			$start_date += 86400;
		}
		
		//浏览趋势数据
		$browse_model = CouponStatistics::model()->findAll(array(
				'select'=>array('new_browse_num','new_browse_person_num','date'),
				'order' => 'date ASC',
				'condition' => 'coupon_id = :coupon_id AND flag = :flag AND unix_timestamp(date)>=:begin_time AND unix_timestamp(date)<=:end_time',
				'params' => array(':coupon_id'=>$coupon_id,':flag' => FLAG_NO,':begin_time' =>$begin_time ,':end_time' =>$end_time)
		));
// 		$coupon_data['browse_data'] = array();
// 		if (!empty($browse_model)){
// 			foreach ($time_data as $key => $v1) {
// 				foreach ($browse_model as $k => $v2){

// 						if (date('Y-m-d', strtotime($v2['date'])) == $key) {
// 							$time_data[$key]['new_browse_person_num'] = $v2['new_browse_person_num'];
// 							$time_data[$key]['new_browse_num'] = $v2['new_browse_num'];
// 						}
					
// 				}
				
// 				$coupon_data['browse_data'] = $time_data;
// 	 		}
// 		}
		//var_dump($coupon_data['browse_data']);
		//趋势数据
		$cmd = Yii::app()->db->createCommand();
		$cmd->select('
				a.coupon_id,
			a.date,
			a.new_browse_num,a.new_browse_person_num,
			a.new_receive_num,a.new_receive_person_num,
			a.new_use_num,a.new_consume_person_num
			');
		$cmd->from('wq_coupon_statistics a');
		$cmd->andWhere('a.coupon_id = :coupons_id');
		$cmd->params[':coupons_id'] = $coupon_id;
		
		$cmd->andWhere('a.flag = :flag');
		$cmd->params[':flag'] = FLAG_NO;
		
		$cmd->andWhere('unix_timestamp(a.date) >= :begin_time');
		$cmd->andWhere('unix_timestamp(a.date) < :end_time');
		$cmd->params[':begin_time'] = $begin_time;
		$cmd->params[':end_time'] = $end_time;
		$cmd->order('a.date DESC');
		
		
		$results = $cmd->queryAll();
		
		

		//循环加日期
		$coupon_data['trend_data'] = array();
		if (!empty($browse_model)){
			foreach ($time_data as $key => $v1) {
				foreach ($results as $k => $v2){
					
					if (date('Y-m-d', strtotime($v2['date'])) == $key) {
						if($search['type'] == 1){
							$time_data[$key]['new_browse_person_num'] = $v2['new_browse_person_num'];
							$time_data[$key]['new_browse_num'] = $v2['new_browse_num'];
						}else if($search['type'] == 2){
							$time_data[$key]['new_browse_person_num'] = $v2['new_receive_person_num'];
							$time_data[$key]['new_browse_num'] = $v2['new_receive_num'];
						}else if($search['type'] == 3){
							$time_data[$key]['new_browse_person_num'] = $v2['new_consume_person_num'];
							$time_data[$key]['new_browse_num'] = $v2['new_use_num'];
						}
					}	
				}
		
				$coupon_data['trend_data'] = $time_data;
			}
		}
		
		
		//第二个时间段
		$begin_time2 = '0';
		$end_time2 = '0';
		$times2 = array();
		if(isset($search['time2']) && !empty($search['time2'])){
			$times2 = explode(' - ', $search['time2']);
			$begin_time2 = $times2[0];
			$end_time2 = $times2[1];
		
			$begin_time2 = strtotime($begin_time2);
			$end_time2 = strtotime($end_time2) + 86400;
		}
		
		//历史数据数据
		$cmd = Yii::app()->db->createCommand();
		$cmd->select('
				a.coupon_id,
			a.date,
			a.new_browse_num,a.new_browse_person_num,
			a.new_receive_num,a.new_receive_person_num,
			a.new_use_num,a.new_consume_person_num
			');
		$cmd->from('wq_coupon_statistics a');
		$cmd->andWhere('a.coupon_id = :coupons_id');
		$cmd->params[':coupons_id'] = $coupon_id;

		$cmd->andWhere('a.flag = :flag');
		$cmd->params[':flag'] = FLAG_NO;
		
		$cmd->andWhere('unix_timestamp(a.date) >= :begin_time2');
		$cmd->andWhere('unix_timestamp(a.date) < :end_time2');
		$cmd->params[':begin_time2'] = $begin_time2;
		$cmd->params[':end_time2'] = $end_time2;
		$cmd->order('a.date DESC');
		$cmd1 = clone $cmd;
		$result1 = $cmd1->queryAll();
		
		$pages = new CPagination(count($result1));
		$pages->pageSize = Yii::app()->params['perPage'];
		$this->page = $pages;
		
		$cmd->offset = (isset($_GET['page']) ? ($_GET['page'] - 1) : 0) * $pages->pageSize;
		$cmd->limit = $pages->pageSize;
		
		$result2 = $cmd->queryAll();
		
		
		
		
		$coupon_data['history_data'] = '';
		if(!empty($result2)){
			$coupon_data['history_data'] = $result2;
		}
		
		
		$result['coupon_data'] = $coupon_data;
		
	
		return $result;
	}
	
	/**
	 * 删除卡券操作
	 * $coupons_id  券id
	 */
	public function delCoupons($coupons_id)
	{
		$result = array();
		try {
			$model = Coupons::model()->findByPk($coupons_id);
			//	if($model -> if_wechat == IF_WECHAT_NO){ //如果该券没有同步到微信的
			if (empty ( $model )) {
				$result ['status'] = ERROR_NO_DATA;
				throw new Exception ( '删除的数据不存在' );
			}
			$model->flag = FLAG_YES;

			if ($model->save ()) {
				if ($model->status == WX_CHECK_AUDIT){
					$apiResult3 = $this->cardDelete($coupons_id);	
				}
				$result ['status'] = ERROR_NONE; // 状态码
				$result ['errMsg'] = ''; // 错误信息
				$result ['data'] = '';
			} else {
				$result ['status'] = ERROR_SAVE_FAIL; // 状态码
				$result ['errMsg'] = '数据保存失败'; // 错误信息
				$result ['data'] = '';
			}
		} catch (Exception $e) {
			$result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
			$result['errMsg'] = $e->getMessage(); //错误信息
		}
	
		return json_encode($result);
	}
	
	/**
	 * 导入code接口
	 * @param unknown $card_id
	 * @return unknown
	 */
	public function importCode($card_id,$code_list)
	{
		$arr = array();
		$arr['card_id'] = $card_id;
		$arr['code'] = $code_list;

		$postData = json_encode($arr);
		$access_token = $this->getToken();
		$url = 'http://api.weixin.qq.com/card/code/deposit?access_token='.$access_token;
		$json_data = $this->postData($url, $postData);
		$json_data = json_decode($json_data,true);
		return $json_data;
	}
	
	/**
	 * 查询导入code接口
	 * @param unknown $card_id
	 * @return unknown
	 */
	public function searchCode($card_id)
	{	
		$postData = json_encode(array('card_id'=>$card_id));
		$access_token = $this->getToken();
		$url = 'http://api.weixin.qq.com/card/code/getdepositcount?access_token='.$access_token;
		$json_data = $this->postData($url, $postData);
		$json_data = json_decode($json_data,true);
		var_dump($json_data);
		return $json_data;
	}
	
	/**
	 * 获取卡券(自定义code码)二维码接口
	 */
	public function qrcodeCreate($card_id,$code_list)
	{
		$result = array();
		$postData = array();
		$card = array();
		$card['card_id'] = $card_id;
		$card['is_unique_code'] = false;
	
	
		$action_info = array();
		$card_list = array();
		$length = count($code_list);
		for ($i=0;$i<$length;$i++){
			$card_list[] = array('card_id'=>$card_id,'code'=>$code_list[$i]);
		}
		$action_info['multiple_card']['card_list'] = $card_list;

		$postData['action_name'] = 'QR_MULTIPLE_CARD';
		$postData['action_info'] = $action_info;
		
		$postData = json_encode($postData);

		$access_token = $this->getToken();
		$url = 'https://api.weixin.qq.com/card/qrcode/create?access_token='.$access_token;
		$json_data = $this->postData($url,$postData);
		$result = json_decode($json_data,true);
		return $result;
	}
	
	/**
	 * 检查微信库存
	 */
	public function getWxSku($coupons_code)
	{
		$result = array();
		$coupon = Coupons::model()->find('code=:code and flag=:flag',array(':code'=>$coupons_code,':flag'=>FLAG_NO));
		if(!empty($coupon['card_id'])){
			
				$card_id = $coupon['card_id'];
				$res = $this->searchCard($card_id);
				if($res['errcode'] == 0){
					if (	$coupon['type'] == 2 ){
						$sku = $res['card']['cash']['base_info']['sku']['quantity'];
					}else if (	$coupon['type'] == 3 ){
						$sku = $res['card']['discount']['base_info']['sku']['quantity'];
					}else if (	$coupon['type'] == 4 ){
						$sku = $res['card']['gift']['base_info']['sku']['quantity'];
					}
					
					if($sku >= 1){
						$result['status'] = ERROR_NONE;
						$result['data'] = 'ok';
					}else {
						$result['status'] = ERROR_NONE;
						$result['data'] = 'no';
					}
					
				}else {
					$result['status'] = ERROR_REQUEST_FAIL;
					$result['msg'] = $res['errmsg'];
				}
			
			
		}else {
			$result['status'] = ERROR_NONE;
			$result['data'] = 'ok';
		}
		return $result;
	}
	
	/**
	 * 获取微信卡券信息
	 */
	public function searchCard($card_id)
	{
		$postData = json_encode(array('card_id'=>$card_id));
		$access_token = $this->getToken();
		$url = 'https://api.weixin.qq.com/card/get?access_token='.$access_token;
		$json_data = $this->postData($url, $postData);
		$json_data = json_decode($json_data,true);
		return $json_data;
	}
	
	/**
	 * 获取微信卡券二维码
	 */
	public function getWxQrcode($coupons_code)
	{
		$result = array();
		$coupon = Coupons::model()->find('code=:code and flag=:flag',array(':code'=>$coupons_code,':flag'=>FLAG_NO));
		$card_id = $coupon['card_id'];
		$res = $this->wxQrcodeCreate($card_id);
		if($res['errcode'] == 0){
			$result['status'] = ERROR_NONE;
			$result['data'] = $res['url'];
		}else {
			$result['status'] = ERROR_REQUEST_FAIL;
			$result['msg'] = $res['errmsg'];
		}
		return $result;
	}
	
	/**
	 * 获取微信卡券二维码接口
	 * car
	 */	
	public function wxQrcodeCreate($card_id)
	{
		$result = array();
		$postData = array();
		$card = array();
		$card['card_id'] = $card_id;
		$card['is_unique_code'] = false;


		$action_info = array();
		$action_info['card'] = $card;
		$postData['action_name'] = 'QR_CARD';
		//$postData['expire_seconds'] = 1800;//不填则有效一年
		$postData['action_info'] = $action_info;

		$postData = json_encode($postData);
		$access_token = $this->getToken();
		$url = 'https://api.weixin.qq.com/card/qrcode/create?access_token='.$access_token;
		$json_data = $this->postData($url,$postData);
		$result = json_decode($json_data,true);
		return $result;
	}
	
	/**
	 * 获取微信单张券信息接口
	 * @param $card_id 卡券id
	 * @param $begin_date 开始时间  （时间格式为2015-06-15）
	 * @param $end_date 结束时间
	 */
	public function getWxCardInfo($card_id,$begin_date,$end_date)
	{
		$result = array();
		$postData = array();
		
		$postData['begin_date'] = $begin_date;
		$postData['end_date'] = $end_date;
		$postData['cond_source'] = 1;
		$postData['card_id'] = $card_id;
	
		$postData = json_encode($postData);
		$access_token = $this->getToken();
		$url = 'https://api.weixin.qq.com/datacube/getcardcardinfo?access_token='.$access_token;
		$json_data = $this->postData($url,$postData);
		$result = json_decode($json_data,true);
		return $result;
	}
	
	/**
	 * 获取微信商户卡券总数据接口
	 * @param $begin_date 开始时间  （时间格式为2015-06-15）
	 * @param $end_date 结束时间
	 */
	public function getWxMerchantCardInfo($begin_date,$end_date)
	{
		$result = array();
		$postData = array();
	
		$postData['begin_date'] = $begin_date;
		$postData['end_date'] = $end_date;
		$postData['cond_source'] = 1;
		
	
		$postData = json_encode($postData);
		$access_token = $this->getToken();
		$url = 'https://api.weixin.qq.com/datacube/getcardbizuininfo?access_token='.$access_token;
		$json_data = $this->postData($url,$postData);
		$result = json_decode($json_data,true);
		return $result;
	}
	
	/**
	 * 生成卡券二维码图片
	 */
	public function phpCreateQr($url)
	{
		//引入phpqrcode库文件
		Yii::import('application.extensions.qrcode.*');
		include('phpqrcode.php');
	
		// 二维码数据
		$data = $url;
		
	
		// 生成的文件名
		//$filename = 'qrcode'.$id.'.png';
		// 纠错级别：L、M、Q、H
		$errorCorrectionLevel = 'L';
		// 点的大小：1到10
		$matrixPointSize = 4;
		//创建一个二维码文件
		//QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 4);
	
		//输入二维码到浏览器
		QRcode::png($data);
	}
	
	/**
	 * 获取选中的门店
	 * $store_ids  门店id
	 */
	public function getStorelimit($store_limit)
	{
		$data = array();
		$arr = explode(',', $store_limit);
		
		$criteria = new CDbCriteria();  
		$criteria->addInCondition('id',$arr);       
		$model= Store::model()->findAll($criteria);
		if(!empty($model)){
			foreach ($model as $k=>$v){
				$data[$k]['id'] = $v['id'];
				$data[$k]['name'] = $v['name'];
				$data[$k]['address'] = $v['address'];
			}
		}
		return $data;
	}
	
	/**
	 * 改变投放状态
	 * $coupons_code  卡券code
	 */
	public function changeReleaseStatus($coupons_code)
	{
		$result = array();
		$coupon = Coupons::model()->find('code=:code and flag=:flag',array(':code'=>$coupons_code,':flag'=>FLAG_NO));
		if(!empty($coupon)){
			$coupon->release_status = COUPON_RELEASE_STATUS_RELEASED;
			
			if ($coupon->save()) {
				$result['status'] = ERROR_NONE;
			}else {
				$result['status'] = ERROR_SAVE_FAIL;
			}
		}else {
			$result['status'] = ERROR_SAVE_FAIL;
		}
		
		return json_encode($result);
	}
	
	//产生code码
	public function createCode($coupon_id,$num) {
		

		$code_model = Yii::app()->db->createCommand()
		->select('code')
		->from('wq_coupon_code')
		->where('status=:status and flag=:flag', array('status'=>COUPON_CODE_STATUS_NOTUSED,':flag'=>FLAG_NO))
		->queryAll();
		
		$code_arr = array();
		$code_arr[] = '111'; 
		foreach ($code_model as $k => $v){
			$code_arr[] = $v['code'];
		}
				

		for ( $i = 0; $i < $num; ++ $i ) {
			$model = new CouponCode();
			$model -> coupon_id = $coupon_id;
			$model -> create_time = date('Y-m-d H:i:s',time());
			$code = $this->randomNUM(12);

			if(in_array($code,$code_arr)){
				
				$code = $this->randomNUM(12);
				$model -> code = $code;
				$model -> save();
			}else{
				
				$model -> code = $code;
				$model -> save();
			}
					
		}
		
	}
	
	public function randomSTR($length) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
		for ($i = 0; $i < $length; $i++) {
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $str;
	}
	
	public function randomNUM($length) {
		$chars = "0123456789";
		$str = "";
		for ($i = 0; $i < $length; $i++) {
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $str;
	}

	/** 获取核销记录
	 * @param $merchant_id
	 * @param string $search
	 * @return string
	 */
	public function getVerification($merchant_id, $search = '')
	{
		$cmd = Yii::app()->db->createCommand();
		$cmd->select('a.id, a.validate_time, c.title, a.validate_channel, d.name AS store_name, e.name AS operator_name, f.order_paymoney, f.coupons_money, f.discount_money, f.merchant_discount_money');
		$cmd->from('wq_coupon_validate_record a');
		$cmd->leftJoin('wq_user_coupons b', 'a.user_coupon_id = b.id');
		$cmd->leftJoin('wq_coupons c', 'b.coupons_id = c.id');
		$cmd->leftJoin('wq_store d', 'a.store_id = d.id');
		$cmd->leftJoin('wq_operator e', 'a.operator_id = e.id');
		$cmd->leftJoin('wq_order f', 'a.order_id = f.id');
		
		$cmd->andWhere('a.merchant_id = :merchant_id');
		$cmd->params[':merchant_id'] = $merchant_id;

		$cmd->andWhere('a.flag = :flag');
		$cmd->params[':flag'] = FLAG_NO;

		if (!empty($search)) {
			if (!empty($search['type'])) {
				$cmd->andWhere('c.type = :type');
				$cmd->params[':type'] = $search['type'];
			}

			if (!empty($search['time'])) {
				$times = explode(' - ', $search['time']);
				$start_time = $times[0];
				$end_time = $times[1];

				$start_time = strtotime($start_time);
				$end_time = strtotime($end_time) + 86400;

				$cmd->andWhere('unix_timestamp(a.validate_time) >= :start_time');
				$cmd->andWhere('unix_timestamp(a.validate_time) <= :end_time');
				$cmd->params[':start_time'] = $start_time;
				$cmd->params[':end_time'] = $end_time;
			} else {
				$start_time = strtotime(date('Y-m-d', strtotime('-6 days')));
				$end_time = time();

				$cmd->andWhere('unix_timestamp(a.validate_time) >= :start_time');
				$cmd->andWhere('unix_timestamp(a.validate_time) <= :end_time');
				$cmd->params[':start_time'] = $start_time;
				$cmd->params[':end_time'] = $end_time;
			}

			if (!empty($search['store'])) {
				$cmd->andWhere('a.store_id = :store');
				$cmd->params[':store'] = $search['store'];
			}

			if (!empty($search['code'])) {
				$cmd->andWhere('b.code = :code');
				$cmd->params[':code'] = $search['code'];
			}
		} else {
			$start_time = strtotime(date('Y-m-d', strtotime('-6 days')));
			$end_time = time();

			$cmd->andWhere('unix_timestamp(a.validate_time) >= :start_time');
			$cmd->andWhere('unix_timestamp(a.validate_time) <= :end_time');
			$cmd->params[':start_time'] = $start_time;
			$cmd->params[':end_time'] = $end_time;
		}

		$cmd->order('a.validate_time DESC');

		$cmd1 = clone $cmd;
		$cmd1->select = 'count(*)';
		$count = $cmd1->queryColumn();
		if($count[0] > 0){
    		$pages = new CPagination($count[0]);
    		$pages->pageSize = Yii::app()->params['perPage'];
    		$this->page = $pages;
		}
		$cmd->offset = (isset($_GET['page']) ? ($_GET['page'] - 1) : 0) * $pages->pageSize;
		$cmd->limit = $pages->pageSize;
		
		$result = $cmd->queryAll();
		
		return json_encode($result);
	}

	/** 获取核销记录详情
	 * @param $id
	 * @return string
	 */
	public function getVerificationDetail($id)
	{
		try {
			$cmd = Yii::app()->db->createCommand();
			$cmd->select('a.id, a.validate_time, c.type, c.title, b.code, a.validate_channel, d.name AS store_name, d.branch_name AS store_branch_name, e.name AS operator_name, f.order_paymoney, f.coupons_money, f.discount_money, f.merchant_discount_money, g.wechat_nickname, a.remark, h.points');
			$cmd->from('wq_coupon_validate_record a');
			$cmd->leftJoin('wq_user_coupons b', 'a.user_coupon_id = b.id');
			$cmd->leftJoin('wq_coupons c', 'b.coupons_id = c.id');
			$cmd->leftJoin('wq_store d', 'a.store_id = d.id');
			$cmd->leftJoin('wq_operator e', 'a.operator_id = e.id');
			$cmd->leftJoin('wq_order f', 'a.order_id = f.id');
			$cmd->leftJoin('wq_user g', 'f.user_id = g.id');
			$cmd->leftJoin('wq_user_pointsdetail h', 'a.order_id = h.order_id');

			$cmd->andWhere('a.id = :id');
			$cmd->params[':id'] = $id;

			$cmd->andWhere('a.flag = :flag');
			$cmd->params[':flag'] = FLAG_NO;
			$data = $cmd->queryAll();
			
			if (!empty($data)) {
				$result['status'] = ERROR_NONE;
				$result['data'] = $data;
			}
		} catch (Exception $e) {
			$result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
			$result['msg'] = $e->getMessage();
		}
		
		return json_encode($result);
	}

	/** 导出excel
	 * @param $merchant_id
	 * @param string $search
	 */
	public function exportExcel($merchant_id, $search = '')
	{
		$cmd = Yii::app()->db->createCommand();
		$cmd->select('a.id, a.validate_time, c.title, a.validate_channel, d.name AS store_name, e.name AS operator_name, f.order_paymoney, f.coupons_money, f.discount_money, f.merchant_discount_money');
		$cmd->from('wq_coupon_validate_record a');
		$cmd->leftJoin('wq_user_coupons b', 'a.user_coupon_id = b.id');
		$cmd->leftJoin('wq_coupons c', 'b.coupons_id = c.id');
		$cmd->leftJoin('wq_store d', 'a.store_id = d.id');
		$cmd->leftJoin('wq_operator e', 'a.operator_id = e.id');
		$cmd->leftJoin('wq_order f', 'a.order_id = f.id');

		$cmd->andWhere('a.merchant_id = :merchant_id');
		$cmd->params[':merchant_id'] = $merchant_id;

		$cmd->andWhere('a.flag = :flag');
		$cmd->params[':flag'] = FLAG_NO;

		if (!empty($search)) {
			if (!empty($search['type'])) {
				$cmd->andWhere('c.type = :type');
				$cmd->params[':type'] = $search['type'];
			}

			if (!empty($search['time'])) {
				$times = explode(' - ', $search['time']);
				$start_time = $times[0];
				$end_time = $times[1];

				$start_time = strtotime($start_time);
				$end_time = strtotime($end_time) + 86400;

				$cmd->andWhere('unix_timestamp(a.validate_time) >= :start_time');
				$cmd->andWhere('unix_timestamp(a.validate_time) <= :end_time');
				$cmd->params[':start_time'] = $start_time;
				$cmd->params[':end_time'] = $end_time;
			} else {
				$start_time = strtotime(date('Y-m-d', strtotime('-6 days')));
				$end_time = time();

				$cmd->andWhere('unix_timestamp(a.validate_time) >= :start_time');
				$cmd->andWhere('unix_timestamp(a.validate_time) <= :end_time');
				$cmd->params[':start_time'] = $start_time;
				$cmd->params[':end_time'] = $end_time;
			}

			if (!empty($search['store'])) {
				$cmd->andWhere('a.store_id = :store');
				$cmd->params[':store'] = $search['store'];
			}

			if (!empty($search['code'])) {
				$cmd->andWhere('b.code = :code');
				$cmd->params[':code'] = $search['code'];
			}
		} else {
			$start_time = strtotime(date('Y-m-d', strtotime('-6 days')));
			$end_time = time();

			$cmd->andWhere('unix_timestamp(a.validate_time) >= :start_time');
			$cmd->andWhere('unix_timestamp(a.validate_time) <= :end_time');
			$cmd->params[':start_time'] = $start_time;
			$cmd->params[':end_time'] = $end_time;
		}

		$cmd->order('a.validate_time DESC');
		$result = $cmd->queryAll();

		$list = array();
		foreach ($result as $k => $v) {
			$list[$k]['validate_time'] = $v['validate_time'];
			$list[$k]['title'] = $v['title'];
			$list[$k]['validate_channel'] = array_key_exists($v['validate_channel'], $GLOBALS['__TERMINAL_TYPE_POS']) ? '收银端核销' : '核销小助手核销';
			$list[$k]['store_name'] = $v['store_name'];
			$list[$k]['operator_name'] = $v['operator_name'];
			$list[$k]['real_pay'] = sprintf("%.2f", $v['order_paymoney'] - $v['coupons_money'] - $v['discount_money'] - $v['merchant_discount_money']);
			$list[$k]['order_paymoney'] = sprintf("%.2f", $v['order_paymoney']);
		}

		$this->getExcel($list);
	}

	/**
	 * 获取excel
	 */
	public function getExcel($model)
	{
		include 'PHPExcel/Reader/Excel2007.php';
		include 'PHPExcel/Reader/Excel5.php';
		include 'PHPExcel/IOFactory.php';

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1', '时间')
			->setCellValue('B1', '券名')
			->setCellValue('C1', '核销方式')
			->setCellValue('D1', '门店')
			->setCellValue('E1', '核销员')
			->setCellValue('F1', '实收金额')
			->setCellValue('G1', '应收金额');
		//设置列宽
		$objActSheet = $objPHPExcel->getActiveSheet();

		//设置sheet名称
		$objActSheet->setTitle('核销记录列表');


		//数据添加
		$i = 2;
		foreach ($model as $k => $v) {
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueExplicit('A' . $i, $v['validate_time'])
				->setCellValue('B' . $i, $v['title'])
				->setCellValue('C' . $i, $v['validate_channel'])
				->setCellValue('D' . $i, $v['store_name'])
				->setCellValue('E' . $i, $v['operator_name'])
				->setCellValue('F' . $i, $v['real_pay'])
				->setCellValue('G' . $i, $v['order_paymoney']);
			$i++;
		}

		$filename = date('YmdHis') . '-核销记录';//定义文件名

		$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
		$this->outPut($filename);
		$objWriter->save("php://output");

	}

	/**
	 * 到浏览器  浏览器下载excel
	 */
	public function outPut($filename)
	{
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		header("Content-Disposition:attachment;filename={$filename}.xls");
		header("Content-Transfer-Encoding:binary");
	}

	/** 获取商户所有优惠券的浏览量
	 * @param $merchant_id
	 * @return string
	 */
	public function getCouponPv($merchant_id)
	{
		try {
			$cmd = Yii::app()->db->createCommand();
			$cmd->select('sum(a.new_browse_num) AS total_browse_num');
			$cmd->from('wq_coupon_statistics a');

			$cmd->andWhere('a.merchant_id = :merchant_id');
			$cmd->params[':merchant_id'] = $merchant_id;

			$cmd->andWhere('UNIX_TIMESTAMP(a.date) < :date');
			$cmd->params[':date'] = strtotime(date('Ymd', time()));

			$cmd->andWhere('a.flag = :flag');
			$cmd->params[':flag'] = FLAG_NO;

			$cmd->group('a.merchant_id');
			$cmd->order('UNIX_TIMESTAMP(a.date) DESC');

			$res_coupon = $cmd->queryRow();

			if (!empty($res_coupon)) {
				$result['status'] = ERROR_NONE;
				$result['data'] = $res_coupon['total_browse_num'];
			}
		} catch (Exception $e) {
			$result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
			$result['msg'] = $e->getMessage();
		}
		
		return json_encode($result);
	}

	/** 获取商户所有优惠券的领取量
	 * @param $merchant_id
	 * @return string
	 */
	public function getCouponReceiveNum($merchant_id)
	{
		try {
			$cmd = Yii::app()->db->createCommand();
			$cmd->select('COUNT(a.coupons_id) AS total_get_num');
			$cmd->from('wq_user_coupons a');
			$cmd->leftJoin('wq_coupons b', 'a.coupons_id = b.id');

			$cmd->andWhere('b.merchant_id = :merchant_id');
			$cmd->params[':merchant_id'] = $merchant_id;
			$cmd->andWhere('UNIX_TIMESTAMP(a.create_time) < :time');
			$cmd->params[':time'] = strtotime(date('Ymd', time()));
			$cmd->andWhere('a.flag = :flag');
			$cmd->params[':flag'] = FLAG_NO;

			$res_coupon = $cmd->queryRow();

			if (!empty($res_coupon)) {
				$result['status'] = ERROR_NONE;
				$result['data'] = $res_coupon['total_get_num'];
			}
		} catch (Exception $e) {
			$result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
			$result['msg'] = $e->getMessage();
		}

		return json_encode($result);
	}

	/** 获取商户所有优惠券的使用量
	 * @param $merchant_id
	 * @return string
	 */
	public function getCouponUseNum($merchant_id)
	{
		try {
			$res_user_coupon = CouponValidateRecord::model()->count('merchant_id = :merchant_id and flag = :flag and validate_time < :time', array(
				':merchant_id' => $merchant_id,
				':flag' => FLAG_NO,
				':time' => date('Y-m-d', strtotime(date('Ymd', time())))
			));

			$result['status'] = ERROR_NONE;
			$result['data'] = $res_user_coupon;
		} catch (Exception $e) {
			$result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
			$result['msg'] = $e->getMessage();
		}

		return json_encode($result);
	}

	/** 获取该商户所有优惠券数据（有分页，按日期）
	 * @param $merchant_id
	 * @param string $search
	 * @return string
	 */
	public function getCouponData($merchant_id, $search = '')
	{
		if (!empty($search)) {
			if (!empty($search['time'])) {
				$times = explode(' - ', $search['time']);
				$start_time = $times[0];
				$end_time = $times[1];

				$start_time = strtotime($start_time);
				$end_time = strtotime($end_time) + 86400;
			}
		} else {
			$start_time = strtotime(date('Y-m-d', strtotime('-7 days')));
			$end_time = strtotime(date('Y-m-d', time()));
		}

		$cmd = Yii::app()->db->createCommand();
		$cmd->select('
			a.date, SUM(a.new_browse_num) AS total_browse_num, 
			SUM(a.new_receive_num) AS total_receive_num, 
			SUM(a.new_use_num) AS total_use_num, 
			SUM(IFNULL(c.total_order_paymoney, 0)) AS total_order_paymoney, 
			SUM(IFNULL(c.total_relpay, 0)) AS total_relpay');
		$cmd->from('wq_coupon_statistics a');
		$cmd->leftJoin('wq_coupons b', 'a.coupon_id = b.id');
		$cmd->leftJoin("(
			SELECT 
				a.coupons_id, 
				FROM_UNIXTIME(UNIX_TIMESTAMP(b.pay_time), '%Y-%m-%d 00:00:00') AS date,
				SUM(IFNULL(b.order_paymoney, 0)) AS total_order_paymoney,
				SUM(IFNULL(b.order_paymoney, 0) - IFNULL(b.coupons_money, 0) - IFNULL(b.discount_money, 0) - IFNULL(b.merchant_discount_money, 0)) AS total_relpay 
			FROM wq_user_coupons a 
			LEFT JOIN wq_order b ON a.order_id = b.id 
			WHERE b.pay_status = " . GJORDER_PAY_STATUS_PAID . "
			AND UNIX_TIMESTAMP(b.pay_time) < $end_time
			AND UNIX_TIMESTAMP(b.pay_time) >= $start_time
			GROUP BY FROM_UNIXTIME(UNIX_TIMESTAMP(b.pay_time), '%Y-%m-%d'), a.coupons_id
			) c", 'a.coupon_id = c.coupons_id and a.date = c.date');

		$cmd->andWhere('a.merchant_id = :merchant_id');
		$cmd->params[':merchant_id'] = $merchant_id;

		$cmd->andWhere('a.flag = :flag');
		$cmd->params[':flag'] = FLAG_NO;

		if (!empty($search)) {
			if (!empty($search['type'])) {
				$cmd->andWhere('b.type = :type');
				$cmd->params[':type'] = $search['type'];
			}

			if (!empty($search['time'])) {
				$times = explode(' - ', $search['time']);
				$start_time = $times[0];
				$end_time = $times[1];

				$start_time = strtotime($start_time);
				$end_time = strtotime($end_time) + 86400;

				$cmd->andWhere('unix_timestamp(a.date) >= :start_time');
				$cmd->andWhere('unix_timestamp(a.date) < :end_time');
				$cmd->params[':start_time'] = $start_time;
				$cmd->params[':end_time'] = $end_time;
			}
		} else {
			$start_time = strtotime(date('Y-m-d', strtotime('-7 days')));
			$end_time = strtotime(date('Y-m-d', time()));

			$cmd->andWhere('unix_timestamp(a.date) >= :start_time');
			$cmd->andWhere('unix_timestamp(a.date) < :end_time');
			$cmd->params[':start_time'] = $start_time;
			$cmd->params[':end_time'] = $end_time;
		}

		$cmd->group('a.date');
		$cmd->order('a.date DESC');

		$cmd1 = clone $cmd;
		$result1 = $cmd1->queryAll();

		$pages = new CPagination(count($result1));
		$pages->pageSize = Yii::app()->params['perPage'];
		$this->page = $pages;

		$cmd->offset = (isset($_GET['page']) ? ($_GET['page'] - 1) : 0) * $pages->pageSize;
		$cmd->limit = $pages->pageSize;

		$result = $cmd->queryAll();

		return json_encode($result);
	}

	/** 获取该商户所有优惠券数据（无分页）
	 * @param $merchant_id
	 * @param string $search
	 * @return string
	 */
	public function getCouponAllData($merchant_id, $search = '')
	{
		$cmd = Yii::app()->db->createCommand();
		$cmd->select('a.*, c.total_order_paymoney, c.total_relpay');
		$cmd->from('wq_coupon_statistics a');
		$cmd->leftJoin('wq_coupons b', 'a.coupon_id = b.id');
		$cmd->leftJoin('(SELECT a.coupons_id, SUM(IFNULL(b.order_paymoney, 0)) AS total_order_paymoney, SUM(IFNULL(b.order_paymoney, 0) - IFNULL(b.coupons_money, 0) - IFNULL(b.discount_money, 0) - IFNULL(b.merchant_discount_money, 0)) AS total_relpay FROM wq_user_coupons a LEFT JOIN wq_order b ON a.order_id = b.id GROUP BY a.coupons_id) c', 'a.coupon_id = c.coupons_id');

		$cmd->andWhere('a.merchant_id = :merchant_id');
		$cmd->params[':merchant_id'] = $merchant_id;

		$cmd->andWhere('a.flag = :flag');
		$cmd->params[':flag'] = FLAG_NO;

		if (!empty($search)) {
			if (!empty($search['type'])) {
				$cmd->andWhere('b.type = :type');
				$cmd->params[':type'] = $search['type'];
			}

			if (!empty($search['time'])) {
				$times = explode(' - ', $search['time']);
				$start_time = $times[0];
				$end_time = $times[1];

				$start_time = strtotime($start_time);
				$end_time = strtotime($end_time) + 86400;

				$cmd->andWhere('unix_timestamp(a.date) >= :start_time');
				$cmd->andWhere('unix_timestamp(a.date) < :end_time');
				$cmd->params[':start_time'] = $start_time;
				$cmd->params[':end_time'] = $end_time;
			}
		} else {
			$start_time = strtotime(date('Y-m-d', strtotime('-7 days')));
			$end_time = strtotime(date('Y-m-d', time()));

			$cmd->andWhere('unix_timestamp(a.date) >= :start_time');
			$cmd->andWhere('unix_timestamp(a.date) < :end_time');
			$cmd->params[':start_time'] = $start_time;
			$cmd->params[':end_time'] = $end_time;
		}

		$cmd->order('a.create_time DESC');
		$result = $cmd->queryAll();

		return json_encode($result);
	}

	public function codeinit(){
		//无code记录
		$nocode = Coupons::model()->findAll(array(
			'select'=>array('id','code','card_id'),
			'condition' => 'ISNULL(code) and flag = :flag',
			'params' => array(':flag' => FLAG_NO)
		));
		//已存在code记录
		$code_model = Yii::app()->db->createCommand()
			->select('code')
			->from('wq_coupons')
			->where('code != ""')
			->queryAll();

		//已存在code数组
		$code_arr_y = array();
		foreach ($code_model as $k => $v){
			$code_arr_y[] = $v['code'];
		}

		//获取无code数据长度
		$length = count($nocode);
		//生成唯一code数组
		$code_arr_n = array();
		for ($i=1;$i<=$length;$i++) {
			$code = $this->randomSTR(6);

			while (in_array($code, $code_arr_y)) {
				$code = $this->randomSTR(6);
			}

			$code_arr_n[] = $code;
		}
		foreach($nocode as $v){
			$randomkey = array_rand($code_arr_n,1);
			$v->code = $code_arr_n[$randomkey];
			$v->save();
			unset($code_arr_n[$randomkey]);
		}

		var_dump($nocode);

	}

}