<?php

/**
 * 服务窗管理
 */

class FuwuController extends mCenterController
{
	/**
	 * 关键词自动回复
	 */
	public function actionAutoReply()
	{
		$fuwu = new FuwuC();
		$merchant_id = Yii::app()->session['merchant_id'];

		//获取自动回复信息列表
		$list_result = $fuwu->getReplyList($merchant_id);
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
		$fuwu = new FuwuC();
		$merchant_id = Yii::app()->session['merchant_id'];
		
		//获取信息回复
		$result = $fuwu->getMsgReply($merchant_id);
		if ($result['status'] == ERROR_NONE) {
			$model = $result['data'];
		}else {
			$status = $result['status'];
			$msg = $result['errMsg'];
		}
		
		//获取图文素材
		$material_result = $fuwu->getMaterialDownList($merchant_id);
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
				$save_res = $fuwu->saveMsgReply($model, $merchant_id, $content, $material_id);
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
		$fuwu = new FuwuC();
		$merchant_id = Yii::app()->session['merchant_id'];
		
		$reply_id = isset($_GET['reply_id']) ? $_GET['reply_id'] : '';
		//获取需要编辑的关键词model
		$get_res = $fuwu->getReply($reply_id);
		if ($get_res['status'] == ERROR_NONE) {
			$model = $get_res['data'];
		}else {
			$status = $get_res['status'];
			$msg = $get_res['errMsg'];
		}
		
		//获取图文素材
		$material_result = $fuwu->getMaterialDownList($merchant_id);
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
			$key_word = $post['key_word'];
			$content = $post['content'];
			$material_id = $post['material_id'];
		
			if (empty($rule_name)) {
				Yii::app()->user->setFlash('rule_name','请填写规则名');
			}
			if(!$reply_id){
				$check_rule = $fuwu->checkRuleName($merchant_id, $rule_name);
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
			
			$save_res = $fuwu->saveReply($model, $merchant_id, $rule_name, $key_word, $content, $material_id);
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
		$fuwu = new FuwuC();
		$reply_id = isset($_GET['reply_id']) ? $_GET['reply_id'] : '';
		$type = isset($_GET['type']) ? $_GET['type'] : '';
		
		if ($type == REPLY_TYPE_MSG && empty($reply_id)){
			Yii::app()->user->setFlash('success', '删除成功');
			$this->redirect('msgReply');
		}else {
			$result = $fuwu->delAutoReply($reply_id);
			
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
				Yii::app()->user->setFlash('error', $msg);
			}
		}
	}
	
	/**
	 * 图文素材列表
	 */
	public function actionMaterialList()
	{
		$material = new FuwuC();
		$merchant_id = Yii::app()->session['merchant_id'];
	
		$result = $material->getMaterialList($merchant_id);
		if ($result['status']) {
			$model = $result['data'];
		}
		
// 		CVarDumper::dump($model);
// 		exit();
	
		$this->render('materialList', array('model'=>$model));
	}
	
	/**
	 * 添加单图文
	 */
	public function actionSetSingleMaterial()
	{
		$material = new FuwuC();
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
			$abstract = $post['abstract'];
			$jump_type = $post['jump_type'];
			$content = $post['content'];
			$link_content = $post['link_content'];
			$customize_link = $post['customize_link'];
			if (empty($material_id)){
				$material_id = $merchant_id.date('Ymdhis');
			}
			$rate = 0;
				
			if (!isset($title)) {
				Yii::app()->user->setFlash('title','请填写标题');
			}
			if(mb_strlen($title,'UTF8') > 32){
				Yii::app()->user->setFlash('title','标题长度超出限制');
			}
			if (empty($cover_img)) {
				Yii::app()->user->setFlash('cover_img','请上传图片');
			}
				
			$save_res = $material->saveSingleMaterial($model, $merchant_id, $title, $cover_img, $abstract, $jump_type, $content, $link_content, $material_id, $rate, $customize_link );
			
			if ($save_res['status'] == ERROR_NONE) {
				$this->redirect('materialList');
			}else {
				$model = $save_res['data'];
				
// 				CVarDumper::dump($save_res['errMsg']);
// 				exit();
// 				Yii::app()->user->setFlash('save_error','');
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
		$material = new FuwuC();
		
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
						$abstract= "";
						$jump_type = $value['jumpType'];
						$content = $value['inputContent'];
						$rate = $sort++;
						$customize_link = $value['customize_link'];

						$save_res = $material->saveSingleMaterial($model, $merchant_id, $title, $cover_img, $abstract, $jump_type, $content, $link_content, $material_id, $rate , $customize_link);
						
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
		$material = new FuwuC();
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
						$abstract= "";
						$jump_type = $value['jumpType'];
						$content = $value['inputContent'];
						$customize_link = $value['customize_link'];
						$rate = $num;
							
						$save_res = $material->saveSingleMaterial($model, $merchant_id, $title, $cover_img, $abstract, $jump_type, $content, $link_content, $material_id, $rate , $customize_link);
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
						$abstract= "";
						$jump_type = $value['jumpType'];
						$content = $value['inputContent'];
						$customize_link = $value['customize_link'];
						if ( $id ){
							$save_res = $material->editMaterial($id, $title, $link_content, $cover_img, $jump_type, $content, $customize_link);
						}else {
							$model = new Material();
							$rate = $num;
							$save_res = $material->saveSingleMaterial($model, $merchant_id, $title, $cover_img, $abstract, $jump_type, $content, $link_content, $material_id, $rate ,$customize_link);
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
							$msg = $save_res['msg'];
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
			$material = new FuwuC();
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
		$fuwu = new FuwuC();
		$merchant_id = Yii::app()->session['merchant_id'];
	
		$model = new Reply();
	
		//获取会员群组
		$group_res = $fuwu->getGroupList($merchant_id);
		if ( $group_res['status'] == ERROR_NONE ) {
			$group_list = $group_res['data'];
		}else {
			$status = $group_res['status'];
			$msg = $group_res['errMsg'];
		}
	
		//获取图文素材
		$material_result = $fuwu->getMaterialDownList($merchant_id);
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
			$group = $post['group'];

			if (empty($content) && empty($material_id)) {
				Yii::app()->user->setFlash('empty','请填写内容');
			}else {
				$save_res = $fuwu->saveBroadcastReply($model, $merchant_id, $content, $material_id, $group);
				if ($save_res['status'] == ERROR_NONE){         //保存成功
					if ( !$group ){
						//群发广播
						if( !empty($content) ){             //发送文字消息
							$custom_send = $fuwu->setAllBroadcastText($content);
						}elseif( !empty($material_id) ){         //发送图文消息
							$custom_send = $fuwu->setAllBroadcastImageText($material_id);
						}
						// 调用ali接口
						$api = new AliApi('AliApi');
						$response = $api->messageTotalSend($custom_send);
						$msg = $fuwu->responseBroadcastMsg($response);
						
						Yii::app()->user->setFlash('error', $msg);
						$this->redirect('broadcastGroup');
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
							$user = $fuwu -> getGroupUser($merchant_id, $group);
							
							if ( !empty($user) ) {
								foreach ($user as $key => $value){
									$api = new AliApi('AliApi');
									$lable_user_add = $fuwu->lableUserAdd($lable_id, $value);
									$lable_user_add_res = $api->lableUserAdd($lable_user_add);
								}
							}
							
							if( !empty($content) ){             //发送文字消息
								$custom_send = $fuwu->setLableBroadcastText($content, $lable_id);
							}elseif( !empty($material_id) ){         //发送图文消息
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
							
							Yii::app()->user->setFlash('error', $msg);
							$this->redirect('broadcastGroup');
						}else {
							$msg = $add_result['msg'];
							Yii::app()->user->setFlash('error', $msg);
							$this->redirect('broadcastGroup');
						}
					}
					
				}else {
					$model = $save_res['data'];
					$error_msg = $save_res['errMsg'];
					Yii::app()->user->setFlash('error', $error_msg);
				}
			}
		
		}
	
		$this->render('broadcastGroup', array('model'=>$model, 'material'=>$material_list, 'group_list'=>$group_list, 'type'=>'broadcast'));
	}
	
	/**
	 * 群发广播-发送
	 */
	public function sendMessage($type, $data)
	{
	
	}
	
	/**
	 * 群发广播-已发送
	 */
	public function actionBroadcastRecord()
	{
		$fuwu = new FuwuC();
		$merchant_id = Yii::app()->session['merchant_id'];
	
		$result = $fuwu->getBroadcastRecord($merchant_id);
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
		$fuwuc = new FuwuC();
		
		$arr = array(); //菜单列表
		$arr_node = array(); //菜单节点
		$data = $fuwuc -> getMenu($merchant_id);
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
		$url_res = $fuwuc->getSystemUrl($merchant_id);
		if ($url_res['status'] == ERROR_NONE){
			$url = $url_res['data'];
		}
		
		//获取图文素材
		$material_result = $fuwuc->getMaterialDownList($merchant_id);
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
				$result = $fuwuc -> editMenu($merchant_id,$post,'','',$last_time,$_POST['id']);
			}else{
				$result = $fuwuc -> addMenu($merchant_id,$post,$model,$create_time,$last_time);
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
		
		$this->render('menu',array('model'=>$model,'arr' => $arr, 'node' => $arr_node, 'material'=>$material_list, 'url'=>$url));
	}
	
	
	/**
	 * 菜单排序
	 */
	public function actionSortMenu()
	{
		$fuwu = new FuwuC();
		$str = $_POST['srotStr'];
		
		$result = $fuwu->sortMenu($str);
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
		$merchant_id = Yii::app ()->session ['merchant_id'];
		$fuwuc = new FuwuC();
		$fuwuc -> publishMenu($merchant_id);
	}
	
	/**
	 * 删除菜单
	 */
	public function actionDelMenu()
	{
		$id = isset($_GET['id']) ? $_GET['id'] : ''; 
		$merchant_id = Yii::app ()->session ['merchant_id'];
		$fuwuc = new FuwuC();
		$result = $fuwuc -> delMenu($merchant_id,$id);
		$result = json_decode($result,true);
		if($result['status'] == ERROR_NONE){
			$this -> redirect('menu');
		}else{
			var_dump($result['errMsg']) ;
		}
	}
	
	/**
	 * 获取并保存用户信息
	 */
	public function actionGetUserList()
	{
		$api = new AliApi('AliApi');
		$merchant_model = Merchant::model()->findAll();
		foreach ($merchant_model as $key => $value){
			$appid = $value['appid'];
			$biz_content = json_encode(array("nextUserId"=>''));
			if (!empty($appid)) {
				$user_list_api = $api->getUserList($appid, $biz_content);
				$user_ali_arr = json_decode(json_encode($user_list_api), true);
				$user_list_arr = $user_ali_arr['alipay_mobile_public_follow_list_response']['data']['user_id_list']['string'];
				foreach ($user_list_arr as $k => $v){

					CVarDumper::dump($v);
				}
				exit();
			}
		}
	}
	
}
