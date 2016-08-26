<?php
/**
 * 系统设置
 */
// include_once $_SERVER['DOCUMENT_ROOT'].'/protected/components/Component.php';
include_once(realpath(dirname(__FILE__).'/../../../../protected/components').'/Component.php');

class InstallController extends mCenterController
{
    /**
     * 支付宝当面付收款账号设置
     */
    public function actionSetAlipay() {
    	$merchant_id = Yii::app()->session['merchant_id'];
    	
    	//缺省信息
    	$info = array(
    			'encrypt_id' => '',
    			'auth_status' => '未授权',
    			'auth_time' => '',
    			'auth_appid' => '',
    			'api_version' => ALIPAY_API_VERSION_2_AUTH_API,
    			'pid' => '',
    			'key' => '',
    			'appid' => '',
    	);
    	
    	$merchant = new MerchantC();
    	//获取当面付相关支付信息
    	$ret = $merchant->BackAlipay($merchant_id);
    	$result = json_decode($ret, true);
    	if ($result['status'] == ERROR_NONE) {
    		$info = $result['data'];
    		if (!empty($info['auth_token'])) {
    			$info['auth_status'] = '已授权';
    		}else {
    			$info['auth_status'] = '未授权';
    		}
    		if (empty($info['api_version'])) {
    			$info['api_version'] = ALIPAY_API_VERSION_2_AUTH_API;
    		}
    	}
    	
    	if (isset($_POST['Info']) && !empty($_POST['Info'])) {
    		$post = $_POST['Info'];
    		$api_version = $post['api_version'];
    		$appid = $post['appid'];
    		$pid = $post['pid'];
    		$key = $post['key'];
    		
    		$info['api_version'] = $api_version;
    		$info['pid'] = $pid;
    		$info['key'] = $key;
    		$info['appid'] = $appid;
    		
    		$flag = 0;
    		if ($api_version == ALIPAY_API_VERSION_1_API) {
    			if(empty($pid))
    			{
    				Yii::app() -> user->setFlash('pid','请输入pid');
    				$flag = 1;
    			}
    			else if(ctype_space($pid))
    			{
    				Yii::app() -> user->setFlash('pid','合作身份证(PID)不能全为空格');
    				$flag = 1;
    			}
    			else if(preg_match("/\s/",$pid))
    			{
    				//字符串中有空格去掉空格
    				$pid=preg_replace("/\s|　/","",$pid);
    			}
    			if(empty($key))
    			{
    				Yii::app() -> user->setFlash('key','请输入key');
    				$flag = 1;
    			}
    			else if(ctype_space($key))
    			{
    				Yii::app() -> user->setFlash('key','安全校验码(KEY)不能全为空格');
    				$flag = 1;
    			}
    			else if(preg_match("/\s/",$key))
    			{
    				//字符串中有空格去掉空格
    				$key=preg_replace("/\s|　/","",$key);
    			}
    		}
    		if ($api_version == ALIPAY_API_VERSION_2_API) {
    			if(empty($appid))
    			{
    				Yii::app() -> user->setFlash('appid','请输入appid');
    				$flag = 1;
    			}
    			else if(ctype_space($appid))
    			{
    				Yii::app() -> user->setFlash('appid','APPID不能全为空格');
    				$flag = 1;
    			}
    			else if(preg_match("/\s/",$appid))
    			{
    				//字符串中有空格去掉空格
    				$appid=preg_replace("/\s|　/","",$appid);
    			}
    		}
    		
    		if($flag == 0)
    		{
    			//$model = $ret->SetAlipay($merchantId,$email,$pid,$key,$gateway='',$publickey='',$appid);
    			$ret = $merchant->updateAlipay($merchant_id, $api_version, NULL, $pid, $key, $appid);
    			$result   = json_decode($ret, true);
    			if($result['status'] == ERROR_NONE)
    			{
    				$url = Yii::app()->createUrl('mCenter/Install/SetAlipay');
    				echo "<script>alert('设置成功');window.location.href='$url'</script>";
    			}else{
    				echo '<script>alert('.$result['errMsg'].');</script>';
    			}
    		}
    	}
    	
    	$this->render('setAlipay', array('info' => $info));
    }
    
    /*
     * 设置当面付2.0授权账号收款
     * */
    public function actionSetApiVersion(){
    	if(isset($_POST['version']) && !empty($_POST['version'])){
    		$merchant_id = Yii::app() -> session['merchant_id'];
    		$version = $_POST['version'];
    		$merchantC = new MerchantC();
    		$result = $merchantC -> setAlipayApiVersion($merchant_id,$version);
    		echo $result;
    	}
    }
    
    /**
     * 支付宝服务窗设置
     */
    public function actionSetFuWuAlipay()
    {
        $merchantId = Yii::app()->session['merchant_id'];        
        $ret   = new MerchantC();
        $result = json_decode($ret->BackAlipay($merchantId));
        
        if($result -> status == ERROR_NONE){
            $merchant = $result -> data;
        }
        if(isset($_POST) && !empty($_POST)){
        	$flag  = 0;
            $appid     = $_POST['appid'];
            $fuwu_name = $_POST['fuwu_name'];
            $alipay_qrcode = $_POST['alipay_qrcode'];

            if(empty($appid)){
                Yii::app() -> user->setFlash('appid','请输入appid');
                $flag = 1;
            }
            else if(ctype_space($appid)){
                Yii::app() -> user->setFlash('appid','appid不能全为空格');
                $flag = 1;
            }else if(preg_match("/\s/",$appid)){
                //字符串中有空格去掉空格
                $appid=preg_replace("/\s|　/","",$appid);
            }
            if(!isset($fuwu_name)){
            	Yii::app() -> user->setFlash('fuwu_name','请输入服务窗名称');
            	$flag = 1;
            }

            if($flag == 0){
                $gateway   = DEVELOPER_GATEWAY.$merchant -> encrypt_id;
                $publickey = DEVELOPER_PUBLIC_KEY;
                $model     = $ret->SetAlipay($merchantId,$email='',$pid='',$key='',$gateway,$publickey,$appid,$fuwu_name,$alipay_qrcode);
                $set       = json_decode($model,true);
                if($set['status'] == ERROR_NONE){
                    $url = Yii::app()->createUrl('mCenter/Install/SetFuWuAlipay');
                    echo "<script>alert('设置成功');window.location.href='$url'</script>";
                }
            }
        }
        $this->render('setFuWuAlipay',array('merchant'=>$merchant));
    }
    
    //修改密码
    public function actionEditPassWord(){
    	if(isset($_POST['oldpwd']) && !empty($_POST['oldpwd'])){
			$flag = 0;
			//旧密码不能为空
			if(isset($_POST['oldpwd']) && !empty($_POST['oldpwd'])){
				$oldPwd = $_POST['oldpwd'];
			}else{
				Yii::app()->user->setFlash('error1','请填写旧密码');
				$flag = 1;
			}
			//新密码确认
			if(isset($_POST['newpwd']) && !empty($_POST['newpwd'])){
				if(isset($_POST['newpwd2']) && !empty($_POST['newpwd2'])){
					if($_POST['newpwd'] == $_POST['newpwd2']){
						if(strlen($_POST['newpwd']) >= 6){
							$newpwd = $_POST['newpwd'];
						}else{
							Yii::app()->user->setFlash('error2','您输入的密码过短');
							$flag = 1;
						}
					}else{
						Yii::app()->user->setFlash('error3','两次输入的密码不一致');
						$flag = 1;
					}
				}else{
					Yii::app()->user->setFlash('error3','请填写确认新密码');
					$flag = 1;
				}
				
			}else{
				Yii::app()->user->setFlash('error2','请填写新密码');
				$flag = 1;
			}
			
			if($flag == 0){
				$merchantC = new MerchantC();
				$result = json_decode($merchantC -> editMerchantPwd(Yii::app() -> session['merchant_id'],md5($oldPwd),md5($newpwd)));
				if($result -> status == ERROR_NONE){
					$this->redirect(Yii::app()->createUrl('mCenter/Auth/Logout'));
				}else{
					Yii::app()->user->setFlash('error1',$result -> errMsg);
				}
			}
    	}
    	$this->render('editPassWord');
    }

    /**
     * 授权微信第三方平台
     */
    #/index.php/mCenter/Install/AuthWechat
    public function actionAuthWechat()
    {
        $merchant_id = Yii::app() -> session['merchant_id'];
        $merchant = Merchant::model()->findByPk($merchant_id);
    
        if($merchant->wechat_thirdparty_authorizer_if_auth == 2)
        {
            $merchant['wechat_thirdparty_authorizer_info'] = json_decode($merchant->wechat_thirdparty_authorizer_info, true);
            $merchant['wechat_thirdparty_authorization_info'] = json_decode($merchant->wechat_thirdparty_authorization_info, true);
        }
        else
        {
            $pre_auth_code = Component::getPreAuthCode();
//             $redirect_url = 'http://gj.debug.51wanquan.com/mCenter/Install/AuthWechatRedirect';
            $redirect_url = Yii::app()->request->hostInfo.$this->createUrl('AuthWechatRedirect');
            $clickUrl = 'https://mp.weixin.qq.com/cgi-bin/componentloginpage?component_appid=' . Component::getAppId() . '&pre_auth_code=' . $pre_auth_code . '&redirect_uri='.urlencode($redirect_url);
        }
        $this->render('authWechat', array('merchant'=>$merchant,'clickUrl'=>$clickUrl));
    }
    /**
     * 微信第三方平台授权成功后方法
     */
    public function actionAuthWechatRedirect()
    {
        $auth_code = isset($_GET['auth_code']) ? $_GET['auth_code'] : '';
    
        $arr = json_decode(Component::getApiQueryAuth($auth_code), true);
        //获取授权公众号的信息
        $auth_info = json_decode(Component::getAuthInfo($arr['authorization_info']['authorizer_appid']), true);
        
        if((!$auth_code) || (!isset($arr['authorization_info'])) || (!isset($auth_info['authorizer_info'])))
        {
            echo "<script>alert('参数错误');history.go(-1);</script>";
            exit;
        }
        
        $merchant_id = Yii::app() -> session['merchant_id'];
    
        $merchant = Merchant::model()->findByPk($merchant_id);
    
        //判断最初的授权公众号是否和本次授权一样，防止微信公众号粉丝数据错乱
        $is_authed_appid = $merchant->wechat_thirdparty_authorizer_appid;
        if(!empty($is_authed_appid) && ($is_authed_appid != $arr['authorization_info']['authorizer_appid']))
        {
            echo "<script>alert('您先前已经使用公众号".$is_authed_appid."进行授权了，暂不允许重新授权其他公众号！');window.self.close();</script>";
            exit;
        }
        
        //判断是否授权其他商户
        $merchants = Merchant::model()->findAll('wechat_thirdparty_authorizer_appid=:authorizer_appid and id<>:id', array(':authorizer_appid'=>$arr['authorization_info']['authorizer_appid'], ':id'=>$merchant_id));
        if(count($merchants)>0)
        {
            echo "<script>alert('您先前已经通过其他账户使用该公众号进行授权了，暂不允许使用该公众号对该账户授权！');window.self.close();</script>";
            exit;
        }
    
        $merchant->wechat_thirdparty_authorizer_appid = $arr['authorization_info']['authorizer_appid'];
        $merchant->wechat_thirdparty_authorizer_refresh_token = $arr['authorization_info']['authorizer_refresh_token'];
        $merchant->wechat_thirdparty_authorization_info = json_encode($arr['authorization_info']);
        $merchant->wechat_thirdparty_authorizer_info = json_encode($auth_info['authorizer_info']);
    
        //未授权或者已经取消授权后授权
        if($merchant->wechat_thirdparty_authorizer_if_auth == 1 || $merchant->wechat_thirdparty_authorizer_if_auth == 3){
            $merchant->wechat_thirdparty_authorizer_if_auth = 2;
            $merchant->wechat_thirdparty_authorizer_time = date('Y-m-d H:i:s');
        }
        //已授权更新授权
        if($merchant->wechat_thirdparty_authorizer_if_auth == 2){
            $merchant->wechat_thirdparty_authorizer_refresh_time = date('Y-m-d H:i:s');
        }
    
        $merchant->save();
        echo '恭喜，授权成功！';
        echo "<script>window.opener.location.reload();window.self.close()</script>";
    }
    
    /**
     * 测试
     */
    public function actionTest()
    {
//         $merchant_id = Yii::app() -> session['merchant_id'];
//         $merchant = Merchant::model()->findByPk($merchant_id);
    
//         $auth_appid = $merchant->wechat_thirdparty_authorizer_appid;
//         $auth_refresh_token = $merchant->wechat_thirdparty_authorizer_refresh_token;
    
//         $auth_access_token = Component::getAuthAccessToken($auth_appid, $auth_refresh_token);
//         echo $auth_access_token;
        
        echo Yii::app()->request->hostInfo.$this->createUrl('AuthWechatRedirect');
        echo 'pre_code:'.Component::getPreAuthCode();
    }
    
    public function actionFlush()
    {
        $db = Yii::app()->db;
        $sql = 'UPDATE wq_merchant SET wechat_thirdparty_authorizer_appid = NULL, wechat_thirdparty_authorizer_if_auth = 1, wechat_thirdparty_authorization_info=NULL, wechat_thirdparty_authorizer_refresh_token=NULL, wechat_thirdparty_authorizer_cancel_time=null;';
        $db->createCommand($sql)->query();
        
    }
    
    //微信公众号设置
    public function actionSetWechat(){
    	$merchantId = Yii::app() -> session['merchant_id'];
    	$merchant_wechat = new MerchantC();
    	if(isset($_POST) && !empty($_POST)){
    		if(isset($_POST['wechat_id']) && !empty($_POST['wechat_id'])){
    			$wechat_id = $_POST['wechat_id'];
                if(preg_match("/\s/",$wechat_id)){
                    //字符串中有空格去掉空格
                    $wechat_id=preg_replace("/\s|　/","",$wechat_id);
                }
    		}else{
    			$wechat_id = '';
    		}
    		
    		if(isset($_POST['wechat']) && !empty($_POST['wechat'])){
    			$wechat = $_POST['wechat'];
                if(preg_match("/\s/",$wechat)){
                    //字符串中有空格去掉空格
                    $wechat=preg_replace("/\s|　/","",$wechat);
                }
    		}else{
    			$wechat = '';
    		}
    		//公众号名称
    		if(isset($_POST['wechat_name']) && !empty($_POST['wechat_name'])){
    			$wechat_name = $_POST['wechat_name'];
    		}else{
    			$wechat_name = '';
    		}
    		//公众号类型
    		if(isset($_POST['wechat_type']) && !empty($_POST['wechat_type'])){
    			$wechat_type = $_POST['wechat_type'];
    		}else{
    			$wechat_type = '';
    		}
    		
    		if(isset($_POST['wechat_subscription_appid']) && !empty($_POST['wechat_subscription_appid'])){
    			$wechat_appid= $_POST['wechat_subscription_appid'];
                if(preg_match("/\s/",$wechat_appid)){
                    //字符串中有空格去掉空格
                    $wechat_appid=preg_replace("/\s|　/","",$wechat_appid);
                }
    		}else{
    			$wechat_appid = '';
    		}
    		
    		if(isset($_POST['wechat_subscription_appsecret']) && !empty($_POST['wechat_subscription_appsecret'])){
    			$wechat_appsecret = $_POST['wechat_subscription_appsecret'];
                if(preg_match("/\s/",$wechat_appsecret)){
                    //字符串中有空格去掉空格
                    $wechat_appsecret=preg_replace("/\s|　/","",$wechat_appsecret);
                }
    		}else{
    			$wechat_appsecret = '';
    		}

            if(isset($_POST['wechat_qrcode']) && !empty($_POST['wechat_qrcode'])){
                $wechat_qrcode = $_POST['wechat_qrcode'];
            }else{
                $wechat_qrcode = '';
            }
    		$re = json_decode($merchant_wechat -> saveWechat($merchantId, $wechat_id, $wechat, $wechat_type, $wechat_appid, $wechat_appsecret,$wechat_name,$wechat_qrcode,$wechat_qrcode));
    		if($re -> status == ERROR_NONE){
    			echo "<script>alert('保存成功');</script>";
    		}
    	}
    	$result = json_decode($merchant_wechat -> getWechat($merchantId));
    	if($result -> status == ERROR_NONE){
    		$this -> render('setWechat',array(
    				'wechat' => $result -> data
    		));
    	}
    	
    }

    /**
     * 微信支付设置
     */
    public function actionSetWechatPay()
    {
        $data=array();
        
        $merchantId = Yii::app() -> session['merchant_id'];
        $merchanat=new MerchantC();
        $rs=$merchanat->getWechatPayInfo($merchantId);
        $merchantData=json_decode($rs,true);
        if($merchantData['status']==ERROR_NONE)
        {
            $data=$merchantData['data'];
            if(empty($data['wechat_apiclient_cert']) && empty($data['wechat_apiclient_key'])){
            	$dir_name = uniqid().rand(1000000, 9999999);
            }else{
            	if(!empty($data['wechat_apiclient_cert'])){
            		$dir_name = $data['wechat_apiclient_cert'];
            	}elseif (!empty($data['wechat_apiclient_key'])){
            		$dir_name = $data['wechat_apiclient_key'];
            	}
            }
        }
        if(isset($_POST) && !empty($_POST))
        {
            $flag  = 0;
            $merchant_type = $_POST['busType'];
            $wechat_appid = $_POST['appid'];
            $wechat_key=$_POST['key'];
            $wechat_appsecret=$_POST['appsecret'];
            $wechat_mchid=$_POST['mchid'];
            $t_mchid = $_POST['t_mchid'];
            $t_appid = $_POST['t_appid'];
            if (empty($merchant_type)) {
            	Yii::app() -> user->setFlash('type','请输入商户类型');
            	$flag = 1;
            }else {
            	if ($merchant_type == WXPAY_MERCHANT_TYPE_SELF) { //普通商户
            		if(empty($wechat_appid))
            		{
            			Yii::app() -> user->setFlash('appid','请输入微信appid');
            			$flag = 1;
            		}
            		else if(ctype_space($wechat_appid))
            		{
            			Yii::app() -> user->setFlash('appid','微信appid不能全为空格');
            			$flag = 1;
            		}
            		else if(preg_match("/\s/",$wechat_appid))
            		{
            			//字符串中有空格去掉空格
            			$wechat_appid=preg_replace("/\s|　/","",$wechat_appid);
            		}
            		
            		if(empty($wechat_key))
            		{
            			Yii::app() -> user->setFlash('key','请输入微信key');
            			$flag = 1;
            		}
            		else if(ctype_space($wechat_key))
            		{
            			Yii::app() -> user->setFlash('key','微信key不能全为空格');
            			$flag = 1;
            		}
            		else if(preg_match("/\s/",$wechat_key))
            		{
            			//字符串中有空格去掉空格
            			$wechat_key=preg_replace("/\s|　/","",$wechat_key);
            		}
            		
            		if(empty($wechat_appsecret))
            		{
            			Yii::app() -> user->setFlash('appsecret','请输入微信appsecret');
            			$flag = 1;
            		}
            		else if(ctype_space($wechat_appsecret))
            		{
            			Yii::app() -> user->setFlash('appsecret','微信appsecret不能全为空格');
            			$flag = 1;
            		}
            		else if(preg_match("/\s/",$wechat_appsecret))
            		{
            			//字符串中有空格去掉空格
            			$wechat_appsecret=preg_replace("/\s|　/","",$wechat_appsecret);
            		}
            		
            		if(empty($wechat_mchid))
            		{
            			Yii::app() -> user->setFlash('mchid','请输入微信mchid');
            			$flag = 1;
            		}
            		else if(ctype_space($wechat_mchid))
            		{
            			Yii::app() -> user->setFlash('mchid','微信mchid不能全为空格');
            			$flag = 1;
            		}
            		else if(preg_match("/\s/",$wechat_mchid))
            		{
            			//字符串中有空格去掉空格
            			$wechat_mchid=preg_replace("/\s|　/","",$wechat_mchid);
            		}
            	}else {
            		if(empty($t_mchid))
            		{
            			Yii::app() -> user->setFlash('t_mchid','请输入微信mchid');
            			$flag = 1;
            		}
            		else if(ctype_space($t_mchid))
            		{
            			Yii::app() -> user->setFlash('t_mchid','微信mchid不能全为空格');
            			$flag = 1;
            		}
            		else if(preg_match("/\s/",$t_mchid))
            		{
            			//字符串中有空格去掉空格
            			$t_mchid=preg_replace("/\s|　/","",$t_mchid);
            		}
            	}
            }
            

            if($flag == 0)
            {
                $ret=new MerchantC();

                $model     = $ret->updateWechatPay($merchantId,$merchant_type,$wechat_appid,$wechat_key,$wechat_appsecret,$wechat_mchid,$t_mchid,$t_appid);
                $set       = json_decode($model,true);
                if($set['status'] == ERROR_NONE)
                {
                    $url = Yii::app()->createUrl('mCenter/Install/SetWechatPay');
                    echo "<script>alert('设置成功');window.location.href='$url'</script>";
                }
            }
        }
        $this->render('setWechatPay',array(
        		'data'=>$data,
        		'dir_name' => $dir_name
        ));
    }

    public function actionUploadSuccess()
    {
        $data=array();
        $merchantId = Yii::app() -> session['merchant_id'];
        $merchanat=new MerchantC();
        if(isset($_POST['fileName'])&&!empty($_POST['fileName'])&&isset($_POST['type'])&&!empty($_POST['type']))
        {
            $filePath=$_POST['fileName'];
            $type=$_POST['type'];
            $rs=json_decode($merchanat->updateWechatCert($merchantId,$filePath,$type),true);
            if($rs['status']==ERROR_NONE)
            {
                //$this->redirect(Yii::app()->createUrl('mCenter/Install/SetWechatPay'));
                echo ERROR_NONE;

            }
            else
            {
                echo ERROR_SAVE_FAIL;
            }
        }
    }
    
   
    
  
    
   
    
    /**
     * 管理员列表
     */
    public function actionAdminList()
    {
    	$list = array();
    	$merchant_id = Yii::app()->session['merchant_id'];
    	$adminC = new AdminC();
    	$result = $adminC -> getManageList($merchant_id);
    	$result = json_decode($result,true);
    	if($result['status'] == ERROR_NONE){
    		$list = $result['data']['list'];
    	}
    	$this->render('adminList',array('list'=>$list));
    }
    
    /**
     * 添加管理员
     */
    public function actionAddAdmin()
    {
    	$merchant_id = Yii::app()->session['merchant_id'];
    	$adminC = new AdminC();
    	$manage_arr = $adminC->getManageArr($merchant_id);
    	
    	//获取未分组的门店
    	$re = json_decode($adminC -> getMerchantStoreWithoutMamagement($merchant_id));
    	if($re -> status == ERROR_NONE){
    	    $store_list = $re -> data;
    	}
    	
    	if (isset ( $_POST ) && ! empty ( $_POST )) {
			// 门店分配选中的门店
			$store_id = '';
			if (isset ( $_POST ['store_id'] )) {
				$store_id = $_POST ['store_id'];
			}
			
			// 权限分配的权限
			$parent_limit = '';
			if (isset ( $_POST ['parent_limit'] )) {
				$parent_limit = $_POST ['parent_limit'];
			}
			
			// 手机号
			$account = '';
			if (isset ( $_POST ['account'] )) {
				$account = $_POST ['account'];
			}
			
			// 姓名
			$name = '';
			if (isset ( $_POST ['name'] )) {
				$name = $_POST ['name'];
			}
			
			// 密码
			$pwd = '';
			if (isset ( $_POST ['pwd'] )) {
				$pwd = $_POST ['pwd'];
			}
			
			$result = $adminC->addAdmin ( $merchant_id, $store_id, $parent_limit, $account, $name, $pwd );
			$result = json_decode($result,true);
			if($result['status'] == ERROR_NONE){
				$this->redirect(array('adminList'));
			}
		}
    	$this->render('addAdmin',array(
    	    'manage_arr'=>$manage_arr,
    	    'store_list' => $store_list
    	));
    }
    
    /**
     * 编辑管理员
     */
    public function actionEditManager($id)
    {
    	$model = array();
    	$merchant_id = Yii::app()->session['merchant_id'];
    	$adminC = new AdminC();
    	$manage_arr = $adminC->getManageArr($merchant_id);

    	//获取未分组的门店
    	$re = json_decode($adminC -> getMerchantStoreWithoutMamagement($merchant_id));
    	if($re -> status == ERROR_NONE){
    	    $store_list = $re -> data;
    	}
    	
    	$arr1 = array();
    	foreach ($manage_arr as $key => $value){
    		foreach ($value['firstStore'] as $k => $v){
    			$arr1[] = $v['id'];
    		}
    	}
    	//获取详情
    	$res = $adminC -> getManagerDetail($id);
    	$res = json_decode($res,true);
    	if($res['status'] == ERROR_NONE){
    		$model = $res['data']['list'];
    	}
    	$arr2 = array();
		$arr2 = $model['store_id'];
    	$arr3 = array();
    	$arr3 = array_diff($arr1,$arr2);
    	if(empty($arr3)){
    		$allpick = 1;
    	}else{
    		$allpick = 0;
    	}

    	if (isset ( $_POST ) && ! empty ( $_POST )) {
    		// 门店分配选中的门店
    		$store_id = '';
    		if (isset ( $_POST ['store_id'] )) {
    			$store_id = $_POST ['store_id'];
    		}
    			
    		// 权限分配的权限
    		$parent_limit = '';
    		if (isset ( $_POST ['parent_limit'] )) {
    			$parent_limit = $_POST ['parent_limit'];
    		}
    			
    		// 手机号
    		$account = '';
    		if (isset ( $_POST ['account'] )) {
    			$account = $_POST ['account'];
    		}
    			
    		// 姓名
    		$name = '';
    		if (isset ( $_POST ['name'] )) {
    			$name = $_POST ['name'];
    		}
    			
    		// 密码
    		$pwd = '';
    		if (isset ( $_POST ['pwd'] )) {
    			$pwd = $_POST ['pwd'];
    		}
    			
    		$result = $adminC->editManager ($merchant_id, $id, $store_id, $parent_limit, $account, $name, $pwd );
    		$result = json_decode($result,true);
    		if($result['status'] == ERROR_NONE){
    			$this->redirect(array('adminList'));
    		}
    	}
    	$this->render('editAdmin',array(
    	    'manage_arr'=>$manage_arr,
    	    'model'=>$model,
    	    'allpick'=>$allpick,
    	    'store_list' => $store_list
    	));
    }
    
    /**
     * 删除管理员
     */
    public function actionDelManager($id)
    {
    	$adminC = new AdminC();
    	$result = $adminC -> delManager($id);
    	$result = json_decode($result,true);
    	if($result['status'] == ERROR_NONE){
    		$this->redirect(array('adminList'));
    	}
    }
    
    
    /*********************************************支付宝授权************************************************************/
    //支付宝授权操作
    public function actionAlipayAuth(){
    	$id = Yii::app() -> session['merchant_id'];
    	$merchantC = new MerchantC();
    	$result = json_decode($merchantC -> checkMerchantAuth($id));
    	if($result -> status == STATUS_SUCCESS){
    		//已授权
    		if($result -> data -> if_auth){
    			$this->render('alipayAuthResult',array(
    					'auth_time' => $result -> data -> alipay_auth_time
    			));
    		}else{//未授权
    			$this->render('alipayAuth');
    		}
    	}
    	
    }
    
    //获取授权二维码
    public function actionCreateAuthQrcode(){
    	include 'qrcode/phpqrcode.php';
    	include 'alifuwu/AopConfig.php';
    	
    	//查询商户号
    	$merchant_id = Yii::app()->session['merchant_id'];
    	$merchant = new MerchantC();
    	$result = $merchant->getWqMchid($merchant_id);
    	$mchid = $result['wq_mchid'];
    	//扩展参数，作为回跳页的get参数，用来标识商户身份
    	$extends = $mchid;
    	//授权回跳页
    	$redirect = ALIPAY_AUTH_REDIRECT_URI.'?extends='.$extends;
    	//拼接url
    	$url = ALIPAY_AUTH_URI.'?app_id='.$AopConfig['app_id'].'&redirect_uri='.urlencode($redirect);
    	
    	echo QRcode::png($url,false,'QR_ECLEVEL_L',5);
    }
    
    //获取授权网址
    public function actionCreateAuthUrl(){
    	include 'alifuwu/AopConfig.php';
    	 
    	//查询商户号
    	$merchant_id = Yii::app()->session['merchant_id'];
    	$merchant = new MerchantC();
    	$result = $merchant->getWqMchid($merchant_id);
    	$mchid = $result['wq_mchid'];
    	//扩展参数，作为回跳页的get参数，用来标识商户身份
    	$extends = $mchid;
    	//授权回跳页
    	$redirect = ALIPAY_AUTH_REDIRECT_URI.'?extends='.$extends;
    	//拼接url
    	$url = ALIPAY_AUTH_URI.'?app_id='.$AopConfig['app_id'].'&redirect_uri='.urlencode($redirect);
    	 
    	$this->redirect($url);
    }
    
    
    /*************************玩券版本***********************************/
    //玩券版本
    public function actionStoreEdition(){
        $storeC = new StoreC();
        $merchant_id = Yii::app()->session['merchant_id'];//商户id
        $result = json_decode($storeC -> getStoreNumAndList($merchant_id));
        $store_list = array();
        $store_num = 0;
        $sy_store_num = 0;
        $yx_store_num = 0;
        if($result -> status == ERROR_NONE){
            $store_list = $result -> list;
            $store_num = $result -> store_count;
            $sy_store_num = $result -> sy_store_count;
            $yx_store_num = $result -> yx_store_count;
        }
        $this->render('storeEdition',array(
            'store_list' => $store_list,
            'store_num' => $store_num,
            'sy_store_num' => $sy_store_num,
            'yx_store_num' => $yx_store_num,
            'pages' => $storeC -> page
        ));
    }
    
    //门店玩券版本续费升级
    public function actionStoreEditionRenewUpgrade(){
        $pca = new Selector();
        $storeC = new StoreC();
        $merchant_id = Yii::app()->session['merchant_id'];//商户id
        $result = json_decode($storeC -> getStoreManagementAndStoreList($merchant_id));
        $store_list = array();
        if($result -> status == ERROR_NONE){
            $store_list = $result -> list;
            $store_list_arr = $result -> store_list;
            $total_store_num = $result -> total_store_num;
        }
        
        //创建订单
        if($_POST){
            $edition = $_POST['edition'];//版本
            $store_id_arr = $_POST['store_id_arr'];//选择门店
            $payMode = $_POST['payMode'];//付费模式
            $time = $_POST['time'];//开通时长
            $invoice = $_POST['invoice'];//是否开发票
            $invoice_header = '';
            $addressee = '';
            $tel = '';
            $address = '';
            //需要开发票
            if($invoice == STORE_ORDER_IF_INVOICE_YES){
                $invoice_header = $_POST['invoice_header'];//发票抬头
                $addressee = $_POST['addressee'];//收件人
                $tel = $_POST['tel'];//联系方式
                //省市区
                $address = '';
                if (!empty($_POST['Selector']['liveplaceProvince'])) {
                    $address = $_POST['Selector']['liveplaceProvince'];
                    if (!empty($_POST['Selector']['liveplaceCity'])) {
                        $address = $address . ',' . $_POST['Selector']['liveplaceCity'] . ',' . $_POST['Selector']['liveplaceArea'];
                    }
                    $pca->liveplaceProvince = $_POST['Selector']['liveplaceProvince'];
                    $pca->liveplaceCity = $_POST['Selector']['liveplaceCity'];
                    $pca->liveplaceArea = $_POST['Selector']['liveplaceArea'];
                }
                $address_details = $_POST['address_details'];//收取地址-详细地址
                $address = $address.','.$address_details;
            }
            
            $flag = true;
            //验证参数
            if(empty($time)|| empty($store_id_arr) ||empty($payMode) ||empty($time)){
                $flag = false;
            }
            
            if($invoice == STORE_ORDER_IF_INVOICE_YES){
                if(empty($pca->liveplaceProvince) || empty($pca->liveplaceCity) ||empty($pca->liveplaceArea) ||empty($address_details)||empty($invoice) || empty($invoice_header) || empty($addressee) || empty($tel) ){
                    $flag = false;
                }
            }
            
            
            if($flag){
                $info = array();
                $info['edition'] = $edition;
                $info['store_id_arr'] = $store_id_arr;
                $arr = explode(',', $store_id_arr);
                $count  = 0;
                foreach ($arr as $k => $v){
                    if(!empty($v)){
                        $count ++;
                    }
                }
                $info['store_num'] = $count;
                $info['payMode'] = $payMode;
                $info['time'] = $time;
                $info['invoice'] = $invoice;
                $info['invoice_header'] = $invoice_header;
                $info['addressee'] = $addressee;
                $info['tel'] = $tel;
                $info['address'] = $address;
                //创建订单
                $result = json_decode($storeC -> addStoreOrder($info, $merchant_id),true);
                if($result['status'] == ERROR_NONE){
                    $detail = json_encode($result['data']['fee_details']);
                    $total_fee = $result['data']['total_fee'];
                    $order_no = $result['order_no'];
                    $this->redirect(array('StoreOrderPay','order_no' => $order_no));
                }
            }
        }
        
        $this->render('storeEditionRenewUpgrade',array(
            'store_list' => $store_list,
            'store_list_arr' => $store_list_arr,
            'total_store_num' => $total_store_num,
            'pca' => $pca
        ));
    }
    
    //门店版本续费升级购买记录
    public function actionStoreOrderList(){
        $storeC = new StoreC();
        $merchant_id = Yii::app()->session['merchant_id'];//商户id
        $result = json_decode($storeC -> getStoreOrderList($merchant_id));
        $store_order_list = array();
        if($result -> status == ERROR_NONE){
            $store_order_list = $result -> list;
        }
        $this->render('storeOrderList',array(
            'store_order_list' => $store_order_list,
            'pages' => $storeC -> page,
        ));
    }
    
    //ajax 计算门店续费升级费用明细
    public function actionAjaxForFeeDetails(){
        $store_arr = $_POST['store_arr'];
        $pay_mode = $_POST['pay_mode'];
        $time = $_POST['time'];
        $merchant_id = Yii::app()->session['merchant_id'];
        if(!empty($store_arr) && !empty($pay_mode) && !empty($time)){
            //如果付费模式为按年则转为月数
            if($pay_mode == STORE_ORDER_PAY_TYPE_YEAR){
                $time = $time*12;
            }
            $store_arr = explode(',', $store_arr); 
            $storeC = new StoreC(); 
            echo $storeC -> countStoreOrderMoneyAndDetail($merchant_id,$store_arr,$pay_mode,$time);
        }
    }
    
    
    //门店升级续费订单支付
    public function actionStoreOrderPay(){
        if(isset($_GET['order_no']) && !empty($_GET['order_no'])){
            $order_no = $_GET['order_no'];
            $storeC = new StoreC();
            $result = json_decode($storeC -> getStoreOrderByOrderNo($order_no));
            if($result -> status == ERROR_NONE){
                $this->render('storeOrderPay',array(
                    'total_fee' => $result -> data -> order_money,
                    'detail' => $result -> data -> fee_detail,
                    'edition' => $result -> data -> edition_type,
                    'store_num' => $result -> data -> store_num,
                    'payMode' => $result -> data -> pay_type,
                    'time' => $result -> data -> time,
                    'order_no' => $result -> data -> order_no
                ));
            }
        }
    }
    
    //门店续费升级订单支付成功
    public function actionStoreOrderPaySuccess(){
    	if (isset($_GET['order_no']) && !empty($_GET['order_no'])) {
    		$order_no = $_GET['order_no'];
    		
    		$storeC = new StoreC();
    		$ret = $storeC->getStoreOrderByOrderNo($order_no);
    		$result = json_decode($ret, true);
    		if ($result['status'] == ERROR_NONE) {
    			$edition_type = $result['data']['edition_type'];
    			$time = $result['data']['time'];
    			$store_num = $result['data']['store_num'];
    			$pay_type = $result['data']['pay_type'];
    			
    			$this->render('storeOrderPaySuccess', array(
    					'edition_type' => $edition_type, 
    					'time' => $time, 
    					'store_num' => $store_num,
    					'pay_type' => $pay_type,
    			));
    		}
    	}
//         Yii::import('application.extensions.alipay.*');
//         require_once("lib/alipay_notify.class.php");
//         require_once("alipay.config.php");
//         //计算得出通知验证结果
//         $alipayNotify  = new AlipayNotify($alipay_config);
//         $verify_result = $alipayNotify->verifyReturn();
//         if($verify_result)
//         {//验证成功
//             $order_no = $_GET['out_trade_no'];
//             $trade_no = $_GET['trade_no'];
//             $storeC = new StoreC();
//             $result = json_decode($storeC -> editStoreOrderAndStoreEdition($order_no, $trade_no));
//             if($result -> status == ERROR_NONE){
//                 $this->render('storeOrderPaySuccess',array(
//                     'edition_type' => $result -> edition_type,
//                     'time' => $result -> time,
//                     'store_num' => $result -> store_num,
//                     'pay_type' => $result -> pay_type,
//                 ));
//             }else{
//                 echo $result -> errMsg;
//             }
//         }else {
//             echo '验签失败';
//         }
    }
    
    /**
     * 支付宝二维码支付
     */
    public function actionAlipayQrcode() {
    	$data = array('error' => 'failure');
    	
    	if (!isset($_GET['order_no']) || empty($_GET['order_no'])) {
    		$data['errMsg'] = '错误的请求';
    		echo json_encode($data);
    		exit();
    	}
    	$order_no = $_GET['order_no'];
    	
    	//请求支付宝接口获取二维码地址
    	$alipay = new AlipayC();
    	$ret = $alipay->payForStoreOrder($order_no);
    	$result = json_decode($ret, true);
    	if ($result['status'] != ERROR_NONE) {
    		$data['errMsg'] = $result['errMsg'];
    		echo json_encode($data);
    		exit();
    	}
    	$data['error'] = 'success';
    	$data['qrcode'] = $result['data'];
    	
    	echo json_encode($data);
    }
    
    /**
     * 查询支付宝订单状态
     */
    public function actionSearch() {
    	$data = array('error' => 'failure');
    	 
    	if (!isset($_GET['order_no']) || empty($_GET['order_no'])) {
    		$data['errMsg'] = '错误的请求';
    		echo json_encode($data);
    		exit();
    	}
    	$order_no = $_GET['order_no'];
    	 
    	//请求支付宝查询接口
    	$alipay = new AlipayC();
    	$ret = $alipay->alipaySearch($order_no);
    	$result = json_decode($ret, true);
    	if ($result['status'] != ERROR_NONE) {
    		$data['errMsg'] = $result['errMsg'];
    		echo json_encode($data);
    		exit();
    	}
    	$data['error'] = 'wait';
    	if (isset($result['trade_status'])) {
    		if ($result['trade_status'] == SEARCH_TRADE_SUCCESS) {
    			$data['error'] = 'success';
    		}
    	}
    	 
    	echo json_encode($data);
    }
    
    //
    public function actionAlipayURL(){
        if(isset($_GET['order_no']) && !empty($_GET['order_no'])){
            $order_no = $_GET['order_no'];
            //同步通知
            $synNotifyUrl = GJ_DOMAIN.'/mCenter/Install/StoreOrderPaySuccess';
            $asyNotifyUrl = GJ_DOMAIN.'/mCenter/AlipayNotify/NotifyForStoreOrder';
            //支付宝支付
            $alipayC = new AlipayC();
            $alipayC -> ToAlipay($order_no, '门店升级续费', $synNotifyUrl, $asyNotifyUrl);
        }
    }
    
}

