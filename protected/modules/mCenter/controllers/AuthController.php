<?php

class AuthController extends mCenterController
{
    public $layout='column1';

    public function actionIndex() {
        $this->redirect('auth/login');
    }

    /**
     * 重写init方法， 不执行父类的 未登录就提示登录程序
     */
    public function init() {
    
    }
    

    /**
     * 登录
     */
    public function actionLogin() {
        $merchant_id = Yii::app() -> session['merchant_id'];
        if(!empty($merchant_id)){
            echo("<script>parent.location.href='".Yii::app() -> createUrl('index/home/index')."'</script>");
            Yii::app() -> end();
        }

        if(isset($_POST['username']) && isset($_POST['password'])) {
            try {
                $username = trim($_POST['username']);
                $password = $_POST['password'];
                if(empty($username)) {
                    throw new Exception('请填写账号');
                }
                if(empty($password)) {
                    throw new Exception('请填写密码');
                }

                if (strlen($username) < 11 && !preg_match(EMAIL_CHECK, $username)) {
                    throw new Exception('请填写正确的账号');
                }                
                if (strlen($username) == 11 || preg_match(EMAIL_CHECK, $username)) {
                    //邮箱账号是否激活
                    if (preg_match(EMAIL_CHECK, $username)){
                        $merchant_ob = Merchant::model() -> find('account=:account and flag = :flag and pwd=:pwd',array(
                            ':account' => $username,
                            ':flag' => FLAG_NO,
                            ':pwd' => md5($password)
                        ));
                        if (!empty($merchant_ob)){
                            //未激活
                            if($merchant_ob->email_confirm_status == MERCHANT_EAMIL_CONFIRM_TOBECONFIRM){
                                $to = $username;
                                $encrypt_id = $merchant_ob->encrypt_id;
                                //订单验证
                                $order = GjOrder::model() -> find('code_merchant_id=:code_merchant_id and flag=:flag',array(
                                    ':code_merchant_id' => $merchant_ob->id,
                                    ':flag' => FLAG_NO
                                ));
                                if (!empty($order)){
                                    $code = $order -> invite_code;
                                    $post_res = $this->postEmail($to,$encrypt_id,$code);
                                    if ($post_res){
                                        $this->redirect(Yii::app()->createUrl('mCenter/Auth/postEmailSuccess',array('code'=>$code,'email'=>$to)));
                                    }else{
                                        throw new Exception('邮件发送失败');
                                    }    
                                }else{
                                    throw new Exception('请激活邮箱账号');
                                }

                            }
                        }
                    }
                    //商户登录
                    $loginform = new LoginForm();
                    $loginform -> account = $username;
                    $loginform -> pwd = $password;
                    if ($loginform ->login()) {
                        //进入商户后台
                        if(Yii::app ()->session ['role'] == WQ_ROLE_MERCHANT){
                            $this->redirect(Yii::app() -> createUrl('index/home/index'));
                        }else{ //进入管理员后台
                            //雅戈尔调查问卷---------开始
                            if(Yii::app ()->session ['manager_id'] == 4){
                                $this->redirect(Yii::app() -> createUrl('mCenter/QuestionResult/QuestionResult'));
                            }
                            //雅戈尔调查问卷---------结束
                            $right = Yii::app ()->session ['right'];
                            if (! empty ( $right )) {
                                $right = substr ( $right, 1, strlen ( $right ) - 2 );
                                $right_arr = explode ( ',', $right );
                            }
                            $index = $right_arr[0];  //获取第一个权限
                            $this->redirect(Yii::app() -> createUrl('index/home/index'));
                        }
                    }else {
                        if(Yii::app()->user->hasFlash('login_error')){
                            throw new Exception(Yii::app()->user->getFlash('login_error'));
                        }else{
                            throw new Exception('登录失败');
                        }
                    }
                }else {
                    //操作员登录
                    $loginform = new LoginForm();
                    $loginform -> account = $username;
                    $loginform -> pwd = $password;
                    if ($loginform -> login()) {
                        //进入收银台
                        $this->redirect(Yii::app()->createUrl('syt/index/index'));
                    }else {
                        throw new Exception('登录失败');
                    }
                }
            } catch (Exception $e) {
                Yii::app()->user->setFlash('error', $e->getMessage());
            }
        }
        $this->render('login');
    }

    public function actionLoginValid() {
        $result = array('error' => 'failure');
        try {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);
            if(empty($username)) {
                throw new Exception('请填写账号');
            }
            if(empty($password)) {
                throw new Exception('请填写密码');
            }

            if (strlen($username) < 11 && !preg_match(EMAIL_CHECK, $username)) {
                throw new Exception('请填写正确的账号');
            }

            $merchantAuthC_ob = new MerchantAuthC();
            $res = json_decode($merchantAuthC_ob->checkLogin(trim($username),$password),true);
            if($res['status'] == ERROR_NONE){
                $result['error'] = 'success';
            }else{
                if(Yii::app()->user->hasFlash('login_error')){
                    throw new Exception(Yii::app()->user->getFlash('login_error'));
                }else{
                    throw new Exception('登录失败');
                }
            }
        }catch (Exception $e) {
            $msg = $e->getMessage();
            $result['msg'] = $msg;
        }
        echo json_encode($result);
    }

    /**
     * 登出
     */

	public function actionLogout(){
		Yii::app()->session->clear();
		Yii::app()->session->destroy();
		echo("<script>parent.location.href='".Yii::app() -> createUrl('mCenter/auth/Login')."'</script>");
	}

    /**
     * 邀请码
     */
    public function actionInvitationCode() {
        if (isset($_POST['Code'])) {
            $code = $_POST['Code'];
            if (!empty($code)) {
                //验证邀请码
                $merchant = new MerchantC();
                $result = json_decode($merchant->checkInviteCode($code),true);
                if ($result['status'] == ERROR_NONE) {
                	//跳转到注册页面
                	$this->redirect(array('register', 'code' => $code,'order_id' => $result['data']['order_id']));
                }else {
                	Yii::app()->user->setFlash('error', '无效的验证码');
                }
            }else {
                Yii::app()->user->setFlash('error', '请填写验证码');
            }
        }
        $this->render('invitationCode');
    }

    /**
     * 注册
     */
    public function actionRegister() {
        //激活码验证
    	if (isset($_GET['code']) && !empty($_GET['code'])) {
    		$code = $_GET['code'];
    	}else {
    		$this->redirect('invitationCode');
    	}
    	if (!$this->checkOrder($code)){
    	    $this->redirect('invitationCode');
    	}
    	$orderId = '';
    	if (isset($_GET['order_id']) && !empty($_GET['order_id'])) {
    	    $orderId = $_GET['order_id'];
    	}
    	//注册类型: 1.手机注册 2.邮箱注册
    	$registerType = 1;
    	if (isset($_GET['registerType']) && !empty($_GET['registerType'])) {
    	    $registerType = $_GET['registerType'];
    	}
    	if (isset($_POST['registerType']) && !empty($_POST['registerType'])) {
    	    $registerType = $_POST['registerType'];
    	}
        if (isset($_POST['order_id'])) {
            if ($registerType == 1){
                $account = $_POST['Account'];
                $tel = '';
                $msg = '';
                $pwd = '';
                $confirm = '';
                //手机号验证
                if (isset($account['tel']) && !empty($account['tel'])) {
                    $tel = $account['tel'];
                }else {
                    Yii::app()->user->setFlash('register_error', '请输入手机号');
                }
                //验证码验证
                if (isset($account['msg']) && !empty($account['msg'])) {
                	if($account['msg'] == Yii::app ()->memcache->get($account['tel'])){
                		$msg = $account['msg'];
                		$this-> delMsgPwd($account['tel']);
                	}else{
                		Yii::app()->user->setFlash('register_error', '验证码错误');
                	}
                }else {
                    Yii::app()->user->setFlash('register_error', '请输入验证码');
                }
                if (isset($account['pwd']) && !empty($account['pwd'])) {
                    $pwd = $account['pwd'];
                }else {
                    Yii::app()->user->setFlash('register_error', '请输入密码');
                }
                if (isset($account['confirm']) && !empty($account['confirm'])) {
                    $confirm = $account['confirm'];
                }else {
                    Yii::app()->user->setFlash('register_error', '请输入确认密码');
                }
                
                //密码是否一致
                if ($pwd && $confirm && $pwd != $confirm) {
                    Yii::app()->user->setFlash('register_error', '两次密码输入不一致');
                }elseif ($tel && $pwd && $confirm && $msg){
                    $merchantC = new MerchantC();
                    $result = json_decode($merchantC -> wqRegister($_POST['order_id'], $tel, md5($pwd)));
                    if($result -> status == ERROR_NONE){
                        $this->redirect(Yii::app()->createUrl('mCenter/Auth/Success'));
                    }else{
                        if($result -> errMsg == 'account'){
                            Yii::app()->user->setFlash('register_error', '该手机号已被注册，请更换手机号');
                        }else{
                            echo $result -> errMsg;
                        }
                    }
                }
            }else{
                $account = $_POST['Account'];
                //参数赋值
                $order_id = '';
                if (isset($_POST['order_id']) && !empty($_POST['order_id'])) {
                    $order_id = $_POST['order_id'];
                }
                $email_account = '';
                if (isset($account['email']) && !empty($account['email'])) {
                    $email_account = trim($account['email']);
                }
                $pwd = '';
                if (isset($account['pwd']) && !empty($account['pwd'])) {
                    $pwd = trim($account['pwd']);
                }
                $pwd_confirm = '';
                if (isset($account['confirm']) && !empty($account['confirm'])) {
                    $pwd_confirm = $account['confirm'];
                }
                if ($order_id && $email_account && $pwd && $pwd_confirm){
                    $merchantAuthC_ob = new MerchantAuthC();
                    $result = json_decode($merchantAuthC_ob->registerEmail($order_id,
                        $email_account, $pwd, $pwd_confirm),true);
                    if($result['status'] == ERROR_NONE){
                        $to = $email_account;
                        $encrypt_id = $result['data']['encrypt_id'];
                        $post_res = $this->postEmail($to,$encrypt_id,$code);
                        if ($post_res){
                            $this->redirect(Yii::app()->createUrl('mCenter/Auth/postEmailSuccess',array('code'=>$code,'email'=>$to,'encrypt_id'=>$encrypt_id)));
                        }else{
                            Yii::app()->user->setFlash('register_error','邮件发送失败');
                        }
                    }else{
                        Yii::app()->user->setFlash('register_error',$result['errMsg']);
                    }
                }
            }
        }
        $this->render('register',array(
        		'order_id' => $orderId,
                'registerType' => $registerType,
        ));
    }
    
    /**
     * 验证激活码是否有效
     */
    private function checkOrder($code){
        //订单验证
        $order = GjOrder::model() -> find('invite_code=:invite_code and order_status = :order_status and flag = :flag',array(
            ':invite_code' => $code,
            ':order_status' => GJORDER_STATUS_NUUSE,
            ':flag' => FLAG_NO
        ));
        if (empty($order)) {
            return false;
        }
        return true;
    }
    
    /**
     * 发送邮件
     */
    private function postEmail($to,$encrypt_id,$code){
        //发送邮件
        $subject = '玩券管家账号激活';
        $url = GJ_DOMAIN.Yii::app()->createUrl('mCenter/Auth/merchantEmailConfirm',array('id'=>$encrypt_id,'code'=>$code));
        $body = '<p>尊敬的玩券用户您好：</p><p>请点击下面的链接激活玩券管家账号：</p><p>'.$url.'</p><p>如果您无法直接点击链接地址，请获取地址，拷贝到浏览器地址栏访问。</p><p>为保证您的账号安全，请在24小时内点击该链接。<br/></p><p>如您本人并未发起相关操作，请忽略并删除邮件，也请确认个人信息、资料的安保情况。<br/></p><p>欢迎使用玩券管家，祝您生意兴隆！</p>';
        $emailC_ob = new EmailC();
        $emailC_res = json_decode($emailC_ob->postmail($to, $subject, $body),true);
        if ($emailC_res['status'] == ERROR_NONE){
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * 重新发送邮件
     */
    public function actionPostEmailAgin(){
        $to = '';
        if (isset($_POST['email']) && !empty($_POST['email'])) {
            $to = $_POST['email'];
        }
        $encrypt_id = '';
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $encrypt_id = $_POST['id'];
        }
        $code = '';
        if (isset($_POST['code']) && !empty($_POST['code'])) {
            $code = $_POST['code'];
        }
        //发送邮件
        $res = $this->postEmail($to,$encrypt_id,$code);
        if ($res){
            echo json_encode(true);
        }else{
            echo json_encode(false);
        }
    }
    
    /**
     * 邮件发送成功页面
     */
    public function actionPostEmailSuccess(){
        if (isset($_GET['code']) && !empty($_GET['code'])) {
    		$code = $_GET['code'];
    	}else {
    		$this->redirect('invitationCode');
    	}
    	//订单验证
    	$order = GjOrder::model() -> find('invite_code=:invite_code and order_status = :order_status and flag = :flag',array(
    	    ':invite_code' => $code,
    	    ':order_status' => GJORDER_STATUS_NUUSE,
    	    ':flag' => FLAG_NO
    	));
    	if (empty($order)) {
    	    $this->redirect('invitationCode');
    	}
    	$email = '';
    	if (isset($_GET['email']) && !empty($_GET['email'])) {
    	    $email = $_GET['email'];
    	}
    	$encrypt_id = '';
    	if (isset($_GET['encrypt_id']) && !empty($_GET['encrypt_id'])) {
    	    $encrypt_id = $_GET['encrypt_id'];
    	}
        $this->render('postEmailSuccess',array(
            'order_id' => $order->id,
            'email' => $email,
            'code' => $code,
            'encrypt_id' => $encrypt_id
        ));
    }
    
    /**
     * 确定激活
     */
    public function actionMerchantEmailConfirm(){
        $encrypt_id = '';
        if (isset($_GET['id']) && !empty($_GET['id'])){
            $encrypt_id = $_GET['id'];
        }
        $code = '';
        if (isset($_GET['code']) && !empty($_GET['code'])){
            $code = $_GET['code'];
        }        
        if ($encrypt_id && $code){
            $merchantAuthC_ob = new MerchantAuthC();
            $result = json_decode($merchantAuthC_ob->editMerchantEmailConfirm($encrypt_id,$code),true);            
            if($result['status'] == ERROR_NONE){
                $this->redirect(Yii::app()->createUrl('mCenter/Auth/success'));
            }else{
                $this->redirect(Yii::app()->createUrl('mCenter/Auth/activeSuccess'));
            }
        }
    }
    /**
     * 完成
     */
    public function actionSuccess() {
        $this->render('success');
    }
    /**
     * 完成
     */
    public function actionActiveSuccess() {
        $this->render('activeSuccess');
    }
    /**
     * 将短信验证码保存到缓存中30分钟
     */
    public function saveMsgPwd($phone_num, $msg_pwd)
    {
    	$key = $phone_num;
    	$ckKey = Yii::app ()->memcache->get ( $key );
    	if ($ckKey != null) {
    		Yii::app ()->memcache->delete ( $key );
    		$value = $msg_pwd;
    		$expire = 1800;
    		Yii::app ()->memcache->set ( $key, $value, $expire );
    	} else {
    		$value = $msg_pwd;
    		$expire = 1800;
    		Yii::app ()->memcache->set ( $key, $value, $expire );
    	}
    }
    
    /**
     * 验证手机账号是否存在
     */
    public function actionCheck(){
    	if(isset($_POST['mobile']) && !empty($_POST['mobile'])){
    		$merchant = new MerchantC();
    		if($merchant -> isMobile($_POST['mobile'])){
    			echo '1';
    		}else{
    			echo '2';
    		}
    	}
    }
    
    /**
     * 验证邮箱账号是否存在
     */
    public function actionCheckEmail(){
        if(isset($_POST['email']) && !empty($_POST['email'])){
            $email = $_POST['email'];
            $merchantAuthC_ob = new MerchantAuthC();
            if($merchantAuthC_ob -> isExist($email)){
                echo '1';
            }else{
                echo '2';
            }
        }
    }
    
    /**
     * 获取短信验证码
     */
    public function actionSendMsgPassword()
    {
    	$message = new DuanXin();
    	$user = new UserUC();
    
    	$tel = $_POST['mobile'];
    	//TODO
    	// 		$merchant_id = Yii::app()->session['merchant_id'];
    	$merchant_id = 1;
    
    	$res = $message->Sms($tel);
    	$result = json_decode($res, true);
    
    	if ($result['status'] == ERROR_NONE) {
    		if (isset($result['data'])) {
    			$phone_num = $result['data']['phone_num'];
    			$msg_pwd = $result['data']['msg_pwd'];
    			 
    			// 把短信验证码保存到memcache里保存时间30分钟
    			$this->saveMsgPwd($phone_num, $msg_pwd);
    		}
    	}else {
    		$status = $result['status'];
    		$msg = $result['errMsg'];
    	}
    
    	echo $res;
    }

    /**
     * 验证手机验证码
     */
    public function actionCheckMsgPwd()
    {
        $flag = false;
        $key = '';
        if(isset($_POST['mobile']) && !empty($_POST['mobile'])){
            $key = $_POST['mobile'];
        }
        $code = '';
        if (isset($_POST['code']) && !empty($_POST['code'])){
            $code = $_POST['code'];
        }
        if ($key && $code){
            $ckKey = Yii::app ()->memcache->get ( $key );
            if ($ckKey != null && $ckKey == $code) {
                $flag = true;
            }
        }
        if ($flag){
            echo json_encode(1);
        }else{
            echo json_encode(2);
        }
    }
    
    /**
     * 将缓存中的短信验证码删除
     */
    public function delMsgPwd($phone_num)
    {
    	$key = $phone_num;
    	$ckKey = Yii::app ()->memcache->get ( $key );
    	if ($ckKey != null) {
    		Yii::app ()->memcache->delete ( $key );
    	}
    }
    
    /**
     * 找回密码
     */
    public function actionRetrieve()
    {
    	if(isset($_POST['Account']) && $_POST['Account']){
    		$post = $_POST['Account'];
    		$flag = false;
    		
    		//验证手机号
    		if(empty($post['tel'])){
    			$flag = true;
    			Yii::app()->user->setFlash('tel_error','手机号必填');
    		}else{ //验证手机号是否存在
    			$merchantC = new MerchantC();
    			$isExit = $merchantC -> isMobile($post['tel']);
    			if(!$isExit){ //不存在
    				$flag = true;
    				Yii::app()->user->setFlash('tel_error','手机号不存在');
    			}
    		}
    		
    		//验证码必填
    		if(empty($post['msg'])){
    			$flag = true;
    			Yii::app()->user->setFlash('msg_error','验证码必填');
    		}else{
    			if($post['msg'] == Yii::app ()->memcache->get($post['tel'])){
    				$msg = $post['msg'];
    				$this-> delMsgPwd($post['tel']);
    			}else{
    				$flag = true;
    				Yii::app()->user->setFlash('msg_error', '验证码错误');
    			}
    		}
    		
    		if(!$flag){
    			$this->redirect(Yii::app()->createUrl('mCenter/auth/nextRetrieve',array('mobile'=>$post['tel'])));
    		}
    	}
    	$this->render('retrieve');
    }
    
    /**
     * 找回密码  下一步
     * 
     */
    public function actionNextRetrieve($mobile)
    {
    	
    	if(isset($_POST['Account']) && $_POST['Account']){
    		$post = $_POST['Account'];
    		$flag = false;
    	
    		//验证新密码
    		if(empty($post['pwd'])){
    			$flag = true;
    			Yii::app()->user->setFlash('pwd_error','新密码必填');
    		}
    	
    		//验证确认密码
    		if(empty($post['confirm'])){
    			$flag = true;
    			Yii::app()->user->setFlash('confirm_error','确认密码必填');
    		}
    		
    		//验证两次密码输入不一致
    		if(!empty($post['pwd']) && !empty($post['confirm'])){
    			if($post['pwd'] != $post['confirm']){
    				$flag = true;
    				Yii::app()->user->setFlash('confirm_error','两次密码输入不一致');
    			}
    		}
    	
    		if(!$flag){
    			$merchantC = new MerchantC();
    			$result = $merchantC -> retrieve($mobile,$post['pwd']);
    			$result = json_decode($result,true);
    			
    			if($result['status'] == ERROR_NONE){
    				$this->redirect(Yii::app()->createUrl('mCenter/auth/retrieveSuccess'));
    			}
    		}
    	}
    	$this->render('nextRetrieve');
    }
    
    /**
     * 手机号输入框失去焦点判断
     */
    public function actionIsExitMobile()
    {
    	$result = array();
    	$mobile = $_POST['mobile'];
    	$merchantC = new MerchantC();
    	$isExit = $merchantC -> isMobile($mobile);
    	if(!$isExit){ //不存在
    		$result['status'] = ERROR_NO_DATA;
    		$result['errMsg'] = '手机号不存在';
    	}
    	echo json_encode($result);
    }
    
    /**
     * 找回密码成功界面
     */
    public function actionRetrieveSuccess()
    {
    	$this->render('retrieveSuccess');
    }
}