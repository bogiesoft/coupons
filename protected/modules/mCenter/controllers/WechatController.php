<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/protected/class/wechat/Wechat.php';

class WechatController extends mCenterController
{
	/**
	 * 关键词自动回复
	 */
	public function actionAutoReply()
	{
		$wechat = new WechatC();
		$merchant_id = Yii::app()->session['merchant_id'];

		//获取自动回复信息列表
		$list_result = $wechat->getReplyList($merchant_id);
		if ( $list_result['status'] == ERROR_NONE ) {
			$list = $list_result['data'];
		}else {
			$status = $list_result['status'];
			$msg = $list_result['errMsg'];
		}
		
		$this->render('autoReply', array('list'=>$list, 'type'=>'keyword'));
	}
	
	/**
	 * 消息自动回复
	 */
	public function actionMsgReply()
	{
		$wechat = new WechatC();
		$merchant_id = Yii::app()->session['merchant_id'];
		
		//获取信息回复
		$result = $wechat->getMsgReply($merchant_id);
		if ($result['status'] == ERROR_NONE) {
			$model = $result['data'];
		}else {
			$status = $result['status'];
			$msg = $result['errMsg'];
		}
		
		//获取图文素材
		$material_result = $wechat->getMaterialDownList($merchant_id);
		if ( $material_result['status'] == ERROR_NONE ) {
			$material_list = $material_result['data'];
		}else {
			$status = $material_result['status'];
			$msg = $material_result['errMsg'];
		}
		
		//保存数据
		if (isset($_POST['Reply']) && !empty($_POST['Reply'])) {
			$post = $_POST['Reply'];
			//参数设置
			$content = $post['content'];
			$material_id = $post['material_id'];
			
			if (empty($content) && empty($material_id)) {
				Yii::app()->user->setFlash('empty','请填写内容');
			}else {
				$save_res = $wechat->saveMsgReply($model, $merchant_id, $content, $material_id);
				if ($save_res['status'] == ERROR_NONE){
					Yii::app()->user->setFlash('success', '保存成功');
					$this->redirect('msgReply');
				}else {
					$model = $save_res['data'];
					$error_msg = $save_res['errMsg'];
					Yii::app()->user->setFlash('error', $error_msg);
				}
			}
			
		}
		
		$this->render('msgReply', array('model'=>$model, 'material'=>$material_list, 'type'=>'msg'));
	}
	
	/**
	 * 保存自动回复
	 */
	public function actionSaveReply()
	{
		$wechat = new WechatC();
		$merchant_id = Yii::app()->session['merchant_id'];
		
		$reply_id = isset($_GET['reply_id']) ? $_GET['reply_id'] : '';
		//获取需要编辑的关键词model
		$get_res = $wechat->getReply($reply_id);
		if ($get_res['status'] == ERROR_NONE) {
			$model = $get_res['data'];
		}else {
			$status = $get_res['status'];
			$msg = $get_res['errMsg'];
		}
		
		//获取图文素材
		$material_result = $wechat->getMaterialDownList($merchant_id);
		if ( $material_result['status'] == ERROR_NONE ) {
			$material_list = $material_result['data'];
		}else {
			$status = $material_result['status'];
			$msg = $material_result['errMsg'];
		}
		
		//保存数据
		if (isset($_POST['Reply']) && !empty($_POST['Reply'])) {
			$post = $_POST['Reply'];
			//参数设置
			$rule_name = $post['rule_name'];
			$key_word = ',';
			if(!empty($post['key_word'])){
    			foreach ($post['key_word'] as $k => $v){
    				$key_word .= $v.',';
    			}
			}else{
			    Yii::app()->user->setFlash('rule_name','请填写规则名');
			}
			
			$content = $post['content'];
			$material_id = $post['material_id'];
		
			if (empty($rule_name)) {
				Yii::app()->user->setFlash('rule_name','请填写规则名');
			}

			if(!$reply_id){
				$check_rule = $wechat->checkRuleName($merchant_id, $rule_name);
				if (!$check_rule) {
					Yii::app()->user->setFlash('rule_name','该规则名已存在');
				}
			}
			if (empty($key_word)) {
				Yii::app()->user->setFlash('key_word','请填写关键词');
			}
			if (empty($content) && empty($material_id)) {
				Yii::app()->user->setFlash('content','请填写内容');
			}
			
			$save_res = $wechat->saveReply($model, $merchant_id, $rule_name, $key_word, $content, $material_id);

			if ($save_res['status'] == ERROR_NONE){
				Yii::app() -> user -> setFlash('submit','<script>art.dialog.close();var win = art.dialog.open.origin;win.location.reload();</script>');
				$this->redirect('autoReply');
			}else {
				$model = $save_res['data'];
			}
		}
		
		$this->render('saveReply', array('model'=>$model, 'material'=>$material_list));
	}
	
	/**
	 * 删除自动回复关键词
	 */
	public function actionDelAutoReply()
	{
		$wechat = new WechatC();
		$reply_id = isset($_GET['reply_id']) ? $_GET['reply_id'] : '';
		$type = isset($_GET['type']) ? $_GET['type'] : '';
		
		if ($type == REPLY_TYPE_MSG && empty($reply_id)){
			Yii::app()->user->setFlash('success', '删除成功');
			$this->redirect('msgReply');
		}else {
			$result = $wechat->delAutoReply($reply_id);
			
			if ($result['status'] == ERROR_NONE) {
				if ($type == REPLY_TYPE_MSG) {
					Yii::app()->user->setFlash('success', '删除成功');
					$this->redirect('msgReply');
				}elseif ($type == REPLY_TYPE_KEYWORD){
					$this->redirect('autoReply');
				}elseif ($type == REPLY_TYPE_BROADCAST){
					$this->redirect('broadcastRecord');
				}
			}else {
				$status = $result['status'];
				$err_msg= $result['errMsg'];
				Yii::app()->user->setFlash('error', $err_msg);
			}
		}
	}
	
	/**
	 * 图文素材列表
	 */
	public function actionMaterialList()
	{
		$material = new WechatC();
		$merchant_id = Yii::app()->session['merchant_id'];
	
		$result = $material->getMaterialList($merchant_id);
		if ($result['status']) {
			$model = $result['data'];
		}
	
		$this->render('materialList', array('model'=>$model));
	}
	
	/**
	 * 添加单图文
	 */
	public function actionSetSingleMaterial()
	{
		$material = new WechatC();
		$material_id = isset($_GET['material_id']) ? $_GET['material_id'] : '';
		//获取图文信息
		$get_res = $material->getSingleMaterial($material_id);
		if ($get_res['status'] == ERROR_NONE) {
			$model = $get_res['data'];
		}else {
			$status = $get_res['status'];
			$msg = $get_res['errMsg'];
		}
	
		//获取链接网址
		$merchant_id = Yii::app()->session['merchant_id'];
		$url_res = $material->getMaterialUrl($merchant_id);
		if ($url_res['status'] == ERROR_NONE){
			$url = $url_res['data'];
		}
	
		//保存数据
		if (isset($_POST['Material']) && !empty($_POST['Material'])) {
			$post = $_POST['Material'];
			//参数判断
			$title = $post['title'];
			$cover_img = $post['cover_img'];
			$img_path = $post['img_path'];
			$abstract = $post['abstract'];
			$jump_type = $post['jump_type'];
			$content = $post['content'];
			$link_content = $post['link_content'];
			if (empty($material_id)){
				$material_id = $merchant_id.date('Ymdhis');
			}
			$rate = 0;
				
			if (!isset($title)) {
				Yii::app()->user->setFlash('title','请填写标题');
			}
			if(strlen($title) > 32){
				Yii::app()->user->setFlash('title','标题长度超出限制');
			}
			if (empty($cover_img)) {
				Yii::app()->user->setFlash('cover_img','请上传图片');
			}
				
			$save_res = $material->saveSingleMaterial($model, $merchant_id, $title, $cover_img, $img_path, $abstract, $jump_type, $content, $link_content, $material_id, $rate );
			if ($save_res['status'] == ERROR_NONE) {
				$this->redirect('materialList');
			}else {
				$model = $save_res['data'];
			}
		}
	
		$this->render('setSingleMaterial', array('model'=>$model, 'url'=>$url));
	}
	
	/**
	 * 添加多图文素材
	 */
	public function actionAddMoreMaterial()
	{
		$num = 2;
		$model = new Material();
		$material = new WechatC();
	
		//获取链接网址
		$merchant_id = Yii::app()->session['merchant_id'];
		$url_res = $material->getMaterialUrl($merchant_id);
		if ($url_res['status'] == ERROR_NONE){
			$url = $url_res['data'];
		}
	
		if ( isset($_POST['MaData']) && !empty($_POST['MaData']) ){
			$sort = 0;
			$post = $_POST['MaData'];
			$material_id = $merchant_id.date('Ymdhis');
			$flag = true;
				
			$transaction = Yii::app()->db->beginTransaction(); //开启事务
			try {
				foreach ($post as $key => $value){
					$type = $value['type'];
					if($type != MATERIAL_TYPE_DEL){
						$model = new Material();
						$title = $value['Title'];
						$link_content = $value['Url'];
						$cover_img = $value['ImgPath'];
						$img_path = $value['ImgAllPath'];
						$abstract= "";
						$jump_type = $value['jumpType'];
						$content = $value['inputContent'];
						$rate = $sort++;
	
						$save_res = $material->saveSingleMaterial($model, $merchant_id, $title, $cover_img, $img_path, $abstract, $jump_type, $content, $link_content, $material_id, $rate );
	
						if ($save_res['status'] == ERROR_NONE) {
								
						}else {
							$flag = false;
							$msg = $save_res['errMsg'];
						}
					}
				}
				$transaction->commit(); //数据提交
			} catch (Exception $e) {
				$transaction->rollback(); //数据回滚
				$data['errMsg'] = $e->getMessage();
				// 					echo json_encode($data);
				// 					return ;
			}
			if ($flag){
				$this->redirect('materialList');
			}
		}
	
		$this->render('addMoreMaterial', array('num'=>$num, 'model'=>$model, 'url'=>$url));
	}
	
	/**
	 * 修改多图文
	 */
	public function actionSetMoreMaterial()
	{
		$material = new WechatC();
		$model = new Material();
		$material_id = isset($_GET['material_id']) ? $_GET['material_id'] : '';
		//获取图文信息
		$get_res = $material->getMoreMaterial($material_id);
		if ($get_res['status'] == ERROR_NONE) {
			$material_mo = $get_res['data'];
			$count = $get_res['num'];
		}else {
			$status = $get_res['status'];
			$msg = $get_res['errMsg'];
		}
		//获取链接网址
		$merchant_id = Yii::app()->session['merchant_id'];
		$url_res = $material->getMaterialUrl($merchant_id);
		if ($url_res['status'] == ERROR_NONE){
			$url = $url_res['data'];
		}
	
	
		if ( isset($_POST['MaData']) && !empty($_POST['MaData']) ){
			$post = $_POST['MaData'];
			$num = 0;
			$flag = true;
			$transaction = Yii::app()->db->beginTransaction(); //开启事务
			try {
				foreach ($post as $key => $value){
					$type = $value['type'];
					if ($type != MATERIAL_TYPE_DEL) {
						$num++;
					}
					//新增
					if($type == MATERIAL_TYPE_ADD){
						$model = new Material();
							
						$title = $value['Title'];
						$link_content = $value['Url'];
						$cover_img = $value['ImgPath'];
						$img_path = $value['ImgAllPath'];
						$abstract= "";
						$jump_type = $value['jumpType'];
						$content = $value['inputContent'];
						$rate = $num;
							
						$save_res = $material->saveSingleMaterial($model, $merchant_id, $title, $cover_img, $img_path, $abstract, $jump_type, $content, $link_content, $material_id, $rate );
						if ($save_res['status'] == ERROR_NONE) {
	
						}else {
							$flag = false;
							$msg = $save_res['errMsg'];
						}
					}elseif ($type == MATERIAL_TYPE_EDIT){    //修改
						$id = isset($value['id'])? $value['id'] : '0';
						$title = $value['Title'];
						$link_content = $value['Url'];
						$cover_img = $value['ImgPath'];
						$img_path = $value['ImgAllPath'];
						$abstract= "";
						$jump_type = $value['jumpType'];
						$content = $value['inputContent'];
	
						if ( $id ){
							$save_res = $material->editMaterial($id, $title, $link_content, $cover_img, $img_path, $jump_type, $content);
						}else {
							$model = new Material();
							$rate = $num;
							$save_res = $material->saveSingleMaterial($model, $merchant_id, $title, $cover_img, $img_path, $abstract, $jump_type, $content, $link_content, $material_id, $rate );
						}
						if ($save_res['status'] == ERROR_NONE) {
	
						}else {
							$flag = false;
							$msg = $save_res['errMsg'];
						}
					}elseif ($type == MATERIAL_TYPE_DEL){    //删除
						$id = $value['id'];
	
						$save_res = $material->delSingleMaterial($id);
	
						if ($save_res['status'] == ERROR_NONE) {
	
						}else {
							$flag = false;
							$msg = $save_res['errMsg'];
						}
					}
				}
				$transaction->commit(); //数据提交
			} catch (Exception $e) {
				$transaction->rollback(); //数据回滚
				$data['errMsg'] = $e->getMessage();
				// 					echo json_encode($data);
				// 					return ;
			}
			if ($flag){
				$this->redirect('materialList');
			}
		}
	
		$this->render('setMoreMaterial', array('material'=>$material_mo, 'model'=>$model, 'url'=>$url, 'material_id' => $material_id, 'count' => $count));
	}
	
	//删除图文素材
	public function actionDelMaterial(){
		if(isset($_GET['material_id']) && !empty($_GET['material_id'])){
			$material = new WechatC();
			$result = json_decode($material -> delMaterial($_GET['material_id']));
			
			if($result -> status == ERROR_NONE){
				$this->redirect('MaterialList');
			}
		}
	}
	
	/**
	 * 群发广播-页面
	 */
	public function actionBroadcastGroup()
	{
		$wechat = new WechatC();
		$merchant_id = Yii::app()->session['merchant_id'];
		
		$model = new Reply();
		
		//获取会员群组
		$group_res = $wechat->getGroupList($merchant_id);
		if ( $group_res['status'] == ERROR_NONE ) {
			$group_list = $group_res['data'];
		}else {
			$status = $group_res['status'];
			$msg = $group_res['errMsg'];
		}
		
		//获取图文素材
		$material_result = $wechat->getMaterialDownList($merchant_id);
		if ( $material_result['status'] == ERROR_NONE ) {
			$material_list = $material_result['data'];
		}else {
			$status = $material_result['status'];
			$msg = $material_result['errMsg'];
		}
		
		$merchant = Wechat::getMerchantById($merchant_id);
		
		//获取assess_token
		$access_token = Wechat::getTokenByMerchant($merchant);
		if (!$access_token){
			Yii::app()->user->setFlash('error', "获取access_token失败！");
		}else {
			//保存数据
			if (isset($_POST['Reply']) && !empty($_POST['Reply'])) {
				$post = $_POST['Reply'];
				//参数设置
				$content = $post['content'];
				$material_id = $post['material_id'];
				$group = $post['group'];
				
				if (empty($content) && empty($material_id)) {
					Yii::app()->user->setFlash('empty','请填写内容');
				}else {
					$save_res = $wechat->saveBroadcastReply($model, $merchant_id, $content, $material_id, $group);
					if ($save_res['status'] == ERROR_NONE){         //保存成功
						//如果是图文消息，上传图文消息至微信服务器
						if( !empty($content) ){             //发送文字消息
							$type = "text";
							$data = $content;
							$arr_result = $this->sendMessage($group, $type, $data, $access_token, $merchant_id);
						}elseif ( !empty($material_id) ){                              //发送图文消息
							//获取素材信息
							$get_res = $wechat->getMoreMaterial($material_id);
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
								$img = $value['img_path'];
								$img_upload_res = $wechat->uploadImg($img, $access_token);
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
								$news_upload_res = $wechat->uploadNews($material, $img_list, $access_token);
								$news_upload_result = json_decode($news_upload_res, true);
								if (!empty($news_upload_result['media_id'])) {
									$news_media_id = $news_upload_result['media_id'];
									//发送图文消息
									$type = "mpnews";
									$data = $news_media_id;
									$arr_result = $this->sendMessage($group, $type, $data, $access_token, $merchant_id);
								}else {
									$arr_result['errcode'] = 1;
									$arr_result['errmsg'] = "图文信息上传失败";
									Yii::app()->user->setFlash('error', $news_upload_result['errmsg']);
								}
							}else{
								$arr_result['errcode'] = 1;
								$arr_result['errmsg'] = "图片上传失败";
								Yii::app()->user->setFlash('error', $img_upload_result['errmsg']);
							}
						}
						
						if ( $arr_result['errcode'] == 0){
							Yii::app()->user->setFlash('success', '消息群发成功');
						
							$this->redirect('broadcastGroup');
						}else {
							Yii::app()->user->setFlash('error', $arr_result['errmsg']);
						}
					}else {
						$model = $save_res['data'];
						$error_msg = $save_res['errMsg'];
						Yii::app()->user->setFlash('error', $error_msg);
					}
				}
				
			}
		}
		
		$this->render('broadcastGroup', array('model'=>$model, 'material'=>$material_list, 'group_list'=>$group_list, 'type'=>'broadcast'));
	}
	
	/**
	 * 群发广播（微信）
	 */
	public function actionWechatBroadcast()
	{
		CVarDumper::dump($_GET);
		exit();
		
		//验证appid appsecret 是否设置
		//todo
		$wechat = new WechatC();
		$merchant_res = $wechat->getMerchant($merchant_id);
		if ($merchant_res['status'] == ERROR_NONE){
			if (empty($merchant_res['data'])) {
				echo "商户信息读取失败";
			}else {
				$appid = $merchant_res['data']['wechat_subscription_appid'];
				$appsecret = $merchant_res['data']['wechat_subscription_appsecret'];
			}
		}
		
		//获取assess_token
		$access_token = $wechat->getAccesstoken($appid, $appsecret);
		if (!$access_token['error']){
			Yii::app()->user->setFlash('error', "请填写正确的AppId与AppSecret");
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
					$save_res = $wechat->saveBroadcastReply($model, $merchant_id, $content, $material_id, $group);
					if ($save_res['status'] == ERROR_NONE){         //保存成功
						//如果是图文消息，上传图文消息至微信服务器
						if( !empty($content) ){             //发送文字消息
							$type = "text";
							$data = $content;
							$arr_result = $this->sendMessage($group, $type, $data, $access_token['access_token'], $merchant_id);
						}elseif ( !empty($material_id) ){                              //发送图文消息
							//获取素材信息
							$get_res = $wechat->getMoreMaterial($material_id);
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
								$img = $value['img_path'];
								$img_upload_res = $wechat->uploadImg($img, $access_token['access_token']);
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
								$news_upload_res = $wechat->uploadNews($material, $img_list, $access_token['access_token']);
								$news_upload_result = json_decode($news_upload_res, true);
								if (!empty($news_upload_result['media_id'])) {
									$news_media_id = $news_upload_result['media_id'];
									//发送图文消息
									$type = "mpnews";
									$data = $news_media_id;
									$arr_result = $this->sendMessage($group, $type, $data, $access_token['access_token'], $merchant_id);
								}else {
									$arr_result['errcode'] = 1;
									$arr_result['errmsg'] = "图文信息上传失败";
									Yii::app()->user->setFlash('error', $news_upload_result['errmsg']);
								}
							}else{
								$arr_result['errcode'] = 1;
								$arr_result['errmsg'] = "图片上传失败";
								Yii::app()->user->setFlash('error', $img_upload_result['errmsg']);
							}
						}
		
						if ( $arr_result['errcode'] == 0){
							Yii::app()->user->setFlash('success', '消息群发成功');
		
							$this->redirect('broadcastGroup');
						}else {
							Yii::app()->user->setFlash('error', $arr_result['errmsg']);
						}
					}else {
						$model = $save_res['data'];
						$error_msg = $save_res['errMsg'];
						Yii::app()->user->setFlash('error', $error_msg);
					}
				}
// 			}
		}
	}
	
	/**
	 * 群发广播-发送(根据openID（订阅号不可用，服务号认证后可用）)
	 */
	public function sendMessage($group, $type, $data, $access_token, $merchant_id)
	{
		$wechat = new WechatC();
		
		//获取群发用户组用户openId
		$openid_result = $wechat->getUserOpenId($merchant_id, $group);
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
		
		$result = $wechat->massSendGroud($access_token, json_encode($msg));
		$arr_result = json_decode($result, true);
		
		return $arr_result;
	}
	
	/**
	 * 群发广播-已发送
	 */
	public function actionBroadcastRecord()
	{
		$wechat = new WechatC();
		$merchant_id = Yii::app()->session['merchant_id'];
		
		$result = $wechat->getBroadcastRecord($merchant_id);
		if ($result['status'] == ERROR_NONE) {
			$record = $result['data'];
		}else {
			$status = $result['status'];
			$msg = $result['errMsg'];
		}
		
// 		CVarDumper::dump($record);
// 		exit();
		
		$this->render('broadcastRecord', array('record'=>$record, 'type'=>'record'));
	}
	
	
	
	
	//添加/修改自定义菜单页面页面
	function actionMenu()
	{
		$merchant_id = Yii::app ()->session ['merchant_id'];
		$wechat = new WechatC();
		
		$arr = array(); //菜单列表
		$arr_node = array(); //菜单节点
		$data = $wechat -> getMenu($merchant_id);
		$arr = $data['arr'];
		$arr_node = $data['arr_node'];
		
		//判断是新增自定义菜单 还是 修改菜单
		$create_time = '';
		$last_time = '';
		if (isset($_GET['id']) && $_GET['id']) {
			$model = Menu::model()->findByPk($_GET['id']);
			if ( !$model['parent_id'] ){
				$arr_node = array();
				$arr_node[0] = "一级菜单";
			}else {
				//删除该节点的第一节点下拉框中的数据
				unset($arr_node[$_GET['id']]);
			}
			$last_time = date('Y-m-d H:i:s');
		} else{
			$model = new Menu();
			$create_time = date('Y-m-d H:i:s');
		}
		
		//获取系统url
		$merchant_id = Yii::app()->session['merchant_id'];
		$url_res = $wechat->getSystemUrl($merchant_id);
		if ($url_res['status'] == ERROR_NONE){
			$url = $url_res['data'];
		}else{
			echo $url_res['errMsg'];exit;
		}
		
		//获取图文素材
		$material_result = $wechat->getMaterialDownList($merchant_id);
		if ( $material_result['status'] == ERROR_NONE ) {
			$material_list = $material_result['data'];
		}else {
			$status = $material_result['status'];
			$msg = $material_result['errMsg'];
		}
		
		if (isset($_POST['Menu']) && $_POST['Menu']) {
			$post = $_POST['Menu'];
			
			if (empty($post['menu_name'])) {
				Yii::app()->user->setFlash('empty','请填写菜单名');
			}
			
			if(isset($_POST['id']) && !empty($_POST['id'])){
				$result = $wechat -> editMenu($merchant_id,$post,'','',$last_time,$_POST['id']);
			}else{
				$result = $wechat -> addMenu($merchant_id,$post,$model,$create_time,$last_time);
			}
			
		    if ($result ['status'] == ERROR_NONE) {
		    	$this->redirect('menu');
			} else {
				$model = $result['data'];
				$status = $result ['status'];
				$msg = $result ['errMsg'];
				Yii::app()->user->setFlash('errMsg',$msg);
			}
		}
		
		$this->render('menu',array(
				'model'=>$model,
				'arr' => $arr, 
				'node' => $arr_node, 
				'material'=>$material_list, 
				'url'=>$url
		));
	}
	
	/**
	 * 菜单排序
	 */
	public function actionSortMenu()
	{
		$wechat = new WechatC();
		$str = $_POST['srotStr'];
	
		$result = $wechat->sortMenu($str);
		echo $result;
	}
	
	//1菜单创建后，在支付宝钱包客户端是实时生效的
	//2删除原有菜单后，建议开发者至少保留该删除菜单的服务七天以上
	//3最多设置4个一级菜单，每个一级菜单最多设置5个二级菜单，当设置4个一级菜单时，左侧的发送消息按钮将被隐藏
	//4一级菜单最多显示4个汉字，二级菜单最多显示12个汉字
	/*
	 *
	* 发布菜单
	*/
	public function actionPublishMenu()
	{
		//检查access_token是否过期
        //todo


        //验证appid appsecret 是否设置
        //todo
		$wechat = new WechatC();
		$merchant_id = Yii::app()->session['merchant_id'];
		
        //获取assess_token
        $merchant = Wechat::getMerchantById($merchant_id);
        $access_token = Wechat::getTokenByMerchant($merchant);
        
        
        if (!$access_token){
        	echo "获取access_token失败！";	
        }else {
	        //将菜单及内容包装成调用所需的数组
			$arr_menu = array();
	        $install_res = $wechat->installMenu($merchant_id);
	        if ($install_res['status'] == ERROR_NONE) {
	        	$arr_menu = $install_res['data'];
	        }
	        
	        //echo $access_token;
	        //print_r($arr_menu);
	        $json_menu = urldecode(json_encode($arr_menu));
	        
	        //print_r($json_menu);
	        $result = $wechat->publishMenu($access_token, $json_menu);
	        $arr_result = json_decode($result);
	        // echo $result;

	        if ($arr_result->errcode == 0){
				echo "菜单发布成功，24小时后可看到效果，或取消关注再重新关注可即时看到效果";
	        }else {
	       		echo $arr_result->errmsg;
	        }
	        //echo "1111";
	        //$this->redirect('menu');
        }
	}
	
	/**
	 * 删除菜单
	 */
	public function actionDelMenu()
	{
		$id = isset($_GET['id']) ? $_GET['id'] : ''; 
		$merchant_id = Yii::app ()->session ['merchant_id'];
		$wechat = new WechatC();
		$result = $wechat -> delMenu($merchant_id,$id);
		$result = json_decode($result,true);
		if($result['status'] == ERROR_NONE){
			$this -> redirect('menu');
		}else{
			var_dump($result['errMsg']) ;
		}
	}

	/*
	 * 用户信息
	 */
	public function actionGetUserList()
	{
		set_time_limit(0);
		ini_set('memory_limit', '1500M');
		try {
			$wechat = new WechatC();
			$merchant_id = 440;
			$value = Merchant::model()->findByPk($merchant_id);
			echo $value -> wq_m_name;
// 			foreach ($merchant_model as $key => $value){
// 				$wechat_appsecret = $value['wechat_subscription_appsecret'];
// 				$wechat_appid = $value['wechat_subscription_appid'];
// 				echo $wechat_appsecret.$wechat_appid;
// 				if (!empty($wechat_appid) && !empty($wechat_appsecret)) {
					$count = 10000;
					$success_count = 0;
					$next_openid = '';
// 					$access_token = $wechat->getAccesstoken($wechat_appid, $wechat_appsecret);
					$access_token = Wechat::getAppIdByMerchant($value);
					//如果抓取数量为10000，则继续抓取后续用户信息
					while ($count == 10000){
						//获取access_token
						if(empty($next_openid)){
							//获取微信用户列表
							$user_list_api = $wechat->getUserList($access_token);
						}else{
							//获取微信用户列表
							$user_list_api = $wechat->getUserList($access_token,$next_openid);
						}
						$user_list_arr = json_decode($user_list_api, true);
						$total = $user_list_arr['total'];
						$count = $user_list_arr['count'];
						$data = $user_list_arr['data']['openid'];
						$next_openid = $user_list_arr['next_openid'];
						echo '一共'.$total.'个'.'本次取到：'.$count.'个';
						foreach ($data as $k => $v){
							$user_info_api = $wechat->getUserInfos($access_token, $v);
							
							$user_info_arr = json_decode($user_info_api, true);	
							$user_obj = User::model()->find('wechat_id = :wechat_id  and merchant_id=:merchant_id', array(
									'wechat_id'=>$v,
									':merchant_id' => $value -> id
							));
							
							if (!empty($user_obj)) {
								$user_obj['wechat_status'] = 2;
								$user_obj['wechat_nickname'] = $user_info_arr['nickname'];
								$user_obj['wechat_sex'] = $user_info_arr['sex'];
								$user_obj['wechat_country'] = $user_info_arr['country'];
								$user_obj['wechat_province'] = $user_info_arr['province'];
								$user_obj['wechat_city'] = $user_info_arr['city'];
								$user_obj['wechat_language'] = $user_info_arr['language'];
								$user_obj['wechat_headimgurl'] = $user_info_arr['headimgurl'];
								$user_obj['wechat_remark'] = $user_info_arr['remark'];
								$user_obj['wechat_groupid'] = $user_info_arr['groupid'];
								$user_obj['wechat_subscribe_time'] = date('Y-m-d H:i:s',$user_info_arr['subscribe_time']);
								//玩券头像为空赋值	
								if(empty($user_obj['avatar'])){
									$user_obj['avatar'] = $user_info_arr['headimgurl'];
								}
								//玩券昵称为空赋值
								if(empty($user_obj['nickname'])){
									$user_obj['nickname'] = $user_info_arr['nickname'];
								}
								//玩券性别为空
								if(empty($user_obj['sex'])){
									$user_obj['sex'] = $user_info_arr['sex'];
								}
								//玩券国家为空
								if(empty($user_obj['country'])){
									$user_obj['country'] = $user_info_arr['country'];
								}
								
								if(!empty($user_info_arr['province'])){
									$provincetext = $user_info_arr['province'];
									$criteria = new CDbCriteria();
									$criteria->addCondition("name like '%$provincetext%'");
									$criteria->addCondition('level = :level');
									$criteria->params[':level'] = 1;
								
									$city = ShopCity::model() -> find($criteria);
									if(!empty($city)){
										$user_obj['province_code'] = $city -> code;
										echo $city -> code;
									}
								}
								//玩券省份为空
								if(empty($user_obj['province'])){
									$user_obj['province'] = $user_info_arr['province'];
								}
								
								if(!empty($user_info_arr['city'])){
									$citytext = $user_info_arr['city'];
									$criteria = new CDbCriteria();
									$criteria->addCondition("name like '%$citytext%'");
									$criteria->addCondition('level = :level');
									$criteria->params[':level'] = 2;
									$city = ShopCity::model() -> find($criteria);
									if(!empty($city)){
										$user_obj['city_code'] = $city -> code;
										echo $city -> code;
									}
								}

								//玩券城市为空
								if(empty($user_obj['city'])){
									$user_obj['city'] = $user_info_arr['city'];
								}
								if($user_obj->update()){
									$success_count ++;
								}else{ 
									throw new Exception('修改失败'+$user_obj['id']);
								}
							}else{
								$new_user = new User();
								$new_user -> type = 2;
								$new_user -> merchant_id = $value -> id;
								$new_user -> wechat_status = 2;
								$new_user -> wechat_id = $v;
								$new_user -> wechat_nickname = $user_info_arr['nickname'];
								$new_user -> wechat_sex = $user_info_arr['sex'];
								$new_user -> wechat_country = $user_info_arr['country'];
								$new_user -> wechat_province = $user_info_arr['province'];
								$new_user -> wechat_city = $user_info_arr['city'];
								$new_user -> wechat_language = $user_info_arr['language'];
								$new_user -> wechat_headimgurl = $user_info_arr['headimgurl'];
								$new_user -> wechat_remark = $user_info_arr['remark'];
								$new_user -> wechat_groupid = $user_info_arr['groupid'];
								$new_user -> wechat_subscribe_time = date('Y-m-d H:i:s',$user_info_arr['subscribe_time']);
								$new_user -> avatar = $user_info_arr['headimgurl'];
								$new_user -> nickname = $user_info_arr['nickname'];
								$new_user -> sex = $user_info_arr['sex'];
								$new_user -> country = $user_info_arr['country'];
								$new_user -> province = $user_info_arr['province'];
								
								if(!empty($user_info_arr['province'])){
									$provincetext = $user_info_arr['province'];
									$criteria = new CDbCriteria();
									$criteria->addCondition("name like '%$provincetext%'");
									$criteria->addCondition('level = :level');
									$criteria->params[':level'] = 1;
									$city = ShopCity::model() -> find($criteria);
									if(!empty($city)){
										$user_obj['province_code'] = $city -> code;
										echo $city -> code;
									}
								}
								
								$new_user -> city = $user_info_arr['city'];
								
								if(!empty($user_info_arr['city'])){
									$citytext = $user_info_arr['city'];
									$criteria = new CDbCriteria();
									$criteria->addCondition("name like '%$citytext%'");
									$criteria->addCondition('level = :level');
									$criteria->params[':level'] = 2;
									$city = ShopCity::model() -> find($criteria);
									if(!empty($city)){
										$user_obj['city_code'] = $city -> code;
										echo $city -> code;
									}
								}
								
								if($new_user -> save()){
									$success_count ++;
								}else{
									throw new Exception('创建失败'+$v);
								}
							}
						}
					}
					echo '成功数：'.$success_count.'个';
// 				}
// 			}
		} catch (Exception $e) {
			echo $e->getMessage(); //错误信息
		}
		
	}
	
}