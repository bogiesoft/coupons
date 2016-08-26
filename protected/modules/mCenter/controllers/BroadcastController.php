<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/protected/components/Component.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/protected/class/wechat/Wechat.php';

class BroadcastController extends mCenterController
{
	/**
	 * 群发广播
	 */
	public function actionBroadcasting()
	{
		$wechatc = new WechatC();
		$merchant_id = Yii::app()->session['merchant_id'];
		
		$merchant = Merchant::model()->findByPk($merchant_id);
		
		//获取会员群组
		$group_res = $wechatc->getGroupList($merchant_id);
		if ( $group_res['status'] == ERROR_NONE ) {
			$group_list = $group_res['data'];
		}else {
			$status = $group_res['status'];
			$msg = $group_res['errMsg'];
		}
		
		//统计会员数量
		$user_num_arr = $wechatc->getUserNum($merchant_id);
		
		if (!empty($_POST['broadcast']) && isset($_POST['broadcast'])) {
			
			$post = $_POST['broadcast'];
			$class = $post['class'];
			$wechat_user = !empty($post['wechat_user']) ? $post['wechat_user'] : '';
			$ali_user = !empty($post['ali_user']) ? $post['ali_user'] : '';
			$repeat = $post['repeat'];
			$content = $post['content'];
			$material_id = $post['material_id'];
			$send_type = $post['type'];
			$wechat_user_num = $post['wechat_user_num'];
			
			//参数验证
			if (empty($wechat_user) && empty($ali_user)) {
				Yii::app()->user->setFlash('empty','请选择发送对象');
			}
			
			//微信用户
			if (!empty($wechat_user)) {
				if ($wechat_user_num < 2){
					Yii::app()->user->setFlash('wechat_error','微信群发用户不能少于2人');
				}else{
	// 				$control=Yii::app()->runController(Yii::app()->createUrl('mCenter/wechat/wechatBroadcast', array('id'=>1,'ds'=>2)));
					$this->wechatBroadcast($merchant_id, $content, $material_id, $class, $repeat, $send_type);
				}
			}
			//支付宝用户
			if (!empty($ali_user)) {
				$this->aliBroadcast($merchant_id, $content, $material_id, $class, $repeat, $send_type);
			}
		}
		
		$this->render('broadcasting', array(
				'group_list'=>$group_list, 
				'count_arr'=>$user_num_arr,
		        'merchant'=>$merchant
		));
	}
	
	/**
	 * 选择素材弹出框
	 */
	public function actionMaterialDialog()
	{
		$material = new FuwuC();
		$title = "";
		$merchant_id = Yii::app()->session['merchant_id'];
		
		if (!empty($_GET['search_title']) && isset($_GET['search_title'])){
			$title = $_GET['search_title'];
			$result = $material->getSearchMaterial($merchant_id, $title);
		}else{
			$result = $material->getMaterialList($merchant_id);
		}
		if ($result['status']) {
			$model = $result['data'];
		}
		
		$this->render('materialDialog', array(
				'model'=>$model, 
				'title'=>$title
		));
	}
	
	/**
	 * 群发广播（微信）
	 */
	public function wechatBroadcast($merchant_id, $content, $material_id, $group, $repeat, $send_type)
	{
		$wechatc = new WechatC();
		$model = new Reply();
		
		$merchant = Wechat::getMerchantById($merchant_id);
	
        //获取access_token
		$access_token = Wechat::getTokenByMerchant($merchant);
		
		if (!$access_token){
			Yii::app()->user->setFlash('wechat_error', "微信：获取access_token失败！");
		}else {
			//保存数据
			// 			if (isset($_POST['Reply']) && !empty($_POST['Reply'])) {
			// 				$post = $_POST['Reply'];
			//参数设置
			// 				$content = $post['content'];
			// 				$material_id = $post['material_id'];
			// 				$group = $post['group'];
	
			if (empty($content) && empty($material_id)) {
				Yii::app()->user->setFlash('empty','请填写内容');
			}else {
				$save_res = $wechatc->saveBroadcastReply($model, $merchant_id, $content, $material_id, $group);
				if ($save_res['status'] == ERROR_NONE){         //保存成功
					//如果是图文消息，上传图文消息至微信服务器
					if( $send_type == 1 ){             //发送文字消息
						$type = urlencode("text");
// 						$data = $content;
						$data = urlencode($content);
						$arr_result = $this->wechatSendMessage($group, $type, $data, $access_token, $merchant_id, $repeat);
					}elseif ( $send_type == 2 ){                              //发送图文消息
						//获取素材信息
						$get_res = $wechatc->getMoreMaterial($material_id);
						if ($get_res['status'] == ERROR_NONE) {
							$material= $get_res['data'];
						}else {
							$status = $get_res['status'];
							$msg = $get_res['errMsg'];
						}
	
						//上传图片
						$img_list = array();
						$img_flag = true;
						foreach ($material as $key => $value){
							$img = WECHAT_UPLOAD_IMG_PATH.$value['cover_img'];
							$img_upload_res = $wechatc->uploadImg($img, $access_token);
							$img_upload_result = json_decode($img_upload_res, true);
	
							if (empty($img_upload_result['media_id'])){
								$img_flag = false;
								break;
							}else {
								$img_list[$value['id']] = $img_upload_result['media_id'];
							}
						}
						if ( $img_flag ) {
							//上传图文信息
							$news_upload_res = $wechatc->uploadNews($material, $img_list, $access_token);
							$news_upload_result = json_decode($news_upload_res, true);
							if (!empty($news_upload_result['media_id'])) {
								$news_media_id = $news_upload_result['media_id'];
								//发送图文消息
								$type = "mpnews";
								$data = $news_media_id;
								$arr_result = $this->wechatSendMessage($group, $type, $data, $access_token, $merchant_id, $repeat);
							}else {
								$arr_result['errcode'] = 1;
								$arr_result['errmsg'] = "图文信息上传失败";
								Yii::app()->user->setFlash('wechat_error', '微信：'.$news_upload_result['errmsg']);
							}
						}else{
							$arr_result['errcode'] = 1;
							$arr_result['errmsg'] = "图片上传失败";
							Yii::app()->user->setFlash('wechat_error', '微信：'.$img_upload_result['errmsg']);
						}
					}
	
					if ( $arr_result['errcode'] == 0){
						Yii::app()->user->setFlash('wechat_success', '微信：'.'消息群发成功');
	
// 						$this->redirect('broadcastGroup');
					}else {
						Yii::app()->user->setFlash('wechat_error', '微信：'.$arr_result['errmsg']);
					}
				}else {
					$model = $save_res['data'];
					$error_msg = $save_res['errMsg'];
					Yii::app()->user->setFlash('wechat_error', '微信：'.$error_msg);
				}
			}
			// 			}
		}
	}
	
	/**
	 * 群发广播-发送(根据openID（订阅号不可用，服务号认证后可用）)(微信)
	 */
	public function wechatSendMessage($group, $type, $data, $access_token, $merchant_id, $repeat)
	{
		$wechatc = new WechatC();
	
		//获取群发用户组用户openId
		$openid_result = $wechatc->getUserOpenId($merchant_id, $group, $repeat);
		if ( $openid_result['status'] == ERROR_NONE ) {
			$openid_list = $openid_result['data'];
		}else {
			$status = $openid_result['status'];
			$errmsg = $openid_result['errMsg'];
		}
		//执行群发
		$msg = array('touser'=>$openid_list);
		$msg['msgtype'] = $type;
	
		switch ($type)
		{
			case 'text' :
				$msg[$type] = array('content'=>$data);
				break;
			case 'image' :
			case 'voice' :
			case 'mpvideo' :
			case 'mpnews' :
				$msg[$type] = array('media_id'=>$data);
				break;
		}
	
		if ($type == "text") {
			$result = $wechatc->massSendGroud($access_token, urldecode(json_encode($msg)));
		}else{
			$result = $wechatc->massSendGroud($access_token, json_encode($msg));
		}

		$arr_result = json_decode($result, true);
	
		return $arr_result;
	}
	
	/**
	 * 群发广播-服务窗
	 */
	public function aliBroadcast($merchant_id, $content, $material_id, $group, $repeat, $send_type)
	{
		$fuwu = new FuwuC();
		$model = new Reply();
		
		//保存数据
// 		if (isset($_POST['Reply']) && !empty($_POST['Reply'])) {
// 			$post = $_POST['Reply'];
// 			//参数设置
// 			$content = $post['content'];
// 			$material_id = $post['material_id'];
// 			$group = $post['group'];
	
			if (empty($content) && empty($material_id)) {
				Yii::app()->user->setFlash('empty','请填写内容');
			}else {
				$save_res = $fuwu->saveBroadcastReply($model, $merchant_id, $content, $material_id, $group);
				if ($save_res['status'] == ERROR_NONE){         //保存成功
					if ( !$group ){
						//群发广播
						if( $send_type == 1 ){             //发送文字消息
							$custom_send = $fuwu->setAllBroadcastText($content);
						}elseif( $send_type == 2 ){         //发送图文消息
							$custom_send = $fuwu->setAllBroadcastImageText($material_id);
						}
						// 调用ali接口
						$api = new AliApi('AliApi');
						$response = $api->messageTotalSend($custom_send);
						$msg = $fuwu->responseBroadcastMsg($response);
	
						Yii::app()->user->setFlash('ali_error', '支付宝：'.$msg);
// 						$this->redirect('broadcastGroup');
					}else {             //根据分组群发
						//创建标签
						$api = new AliApi('AliApi');
						$name = $group;
						$add_lable = $fuwu->addLable($name);
						$add_res = $api->addLable($add_lable);
						$add_result = $fuwu->responseAddLableMsg($add_res);
	
						if ($add_result['status'] == ERROR_NONE){
							$lable_id = $add_result['lable_id'];
								
							//用户添加标签
							$user = $fuwu -> getGroupUser($merchant_id, $group, $repeat);
								
							if ( !empty($user) ) {
								foreach ($user as $key => $value){
									$api = new AliApi('AliApi');
									$lable_user_add = $fuwu->lableUserAdd($lable_id, $value);
									$lable_user_add_res = $api->lableUserAdd($lable_user_add);
								}
							}
								
							if( $send_type == 1 ){             //发送文字消息
								$custom_send = $fuwu->setLableBroadcastText($content, $lable_id);
							}elseif( $send_type == 2 ){         //发送图文消息
								$custom_send = $fuwu->setLableBroadcastImageText($material_id, $lable_id);
							}
							//调用ali接口
							$api = new AliApi('AliApi');
							$response = $api->messageLabelSend($custom_send);
							$msg = $fuwu->responseBroadcastLableMsg($response);
								
							//删除标签
							$api = new AliApi('AliApi');
							$del_lable = $fuwu->delLable($lable_id);
							$del_res = $api->delLable($del_lable);
								
							Yii::app()->user->setFlash('ali_error', '支付宝：'.$msg);
// 							$this->redirect('broadcastGroup');
						}else {
							$msg = $add_result['msg'];
							Yii::app()->user->setFlash('ali_error', '支付宝：'.$msg);
// 							$this->redirect('broadcastGroup');
						}
					}
						
				}else {
					$model = $save_res['data'];
					$error_msg = $save_res['errMsg'];
					Yii::app()->user->setFlash('ali_error', '支付宝：'.$error_msg);
				}
			}
	
// 		}
	}
	
	public function actionGetMaterialDemo(){
		$material_id = $_GET['material_id'];
		$wechatc = new WechatC();
		$material_res = $wechatc->getMoreMaterial($material_id);
		if ($material_res['status'] == ERROR_NONE) {
			$material_arr = $material_res['data'];
		}
		
		$this->renderPartial('material_demo', array('material'=>$material_arr));
	}

	public function actionCountUserNum(){
		$wechatc = new WechatC();
		$merchant_id = Yii::app()->session['merchant_id'];
		$group_id = $_GET['group_id'];
		
		if ($group_id){
			$user_num_arr = $wechatc->countGroupUserNum($group_id);
		}else{
			$user_num_arr = $wechatc->getUserNum($merchant_id);
		}
		
		echo json_encode($user_num_arr);
	}
	
}