<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class MIdentity extends CUserIdentity
{
	public $username;
	private $_id;
	private $_role;


	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		if(strlen($this->username) == 11 || preg_match(EMAIL_CHECK, $this->username)){

			//商户登录
			$merchant = new MerchantC();
        	$ret = $merchant->Login($this->username, $this-> password);
        	$result = json_decode($ret, true);
        	if($result['status']  == ERROR_NONE){
        		if ($result ['data'] ['role'] == 1) {  //如果是商户
					$this->errorCode = self::ERROR_NONE;
					$this->_id = $result ['data'] ['id'];
					$this->username = $result ['data'] ['account'];
					// 存session
					Yii::app ()->session ['merchant_id'] = $result ['data'] ['id'];
					// session_start();
					// $_SESSION['merchant_id'] = $result['data']['id'];
					Yii::app ()->session ['merchant_name'] = $result ['data'] ['name'];
					Yii::app ()->session ['type_name'] = $result ['data'] ['type_name'];
					Yii::app ()->session ['type_id'] = $result ['data'] ['type_id'];
					Yii::app ()->session ['time_limit'] = $result ['data'] ['time_limit'];
					Yii::app ()->session ['if_tryout'] = $result ['data'] ['if_tryout'];
					Yii::app ()->session ['role'] = $result ['data'] ['role']; //角色
					Yii::app ()->session ['charge_type'] = $result ['data'] ['charge_type'];
        		}else{ //如果是管理员
        			$this->errorCode = self::ERROR_NONE;
        			$this->_id = $result ['data'] ['id'];
        			$this->username = $result ['data'] ['account'];
        			
        			// 存session
        			Yii::app ()->session ['merchant_id'] = $result ['data'] ['merchant_id'];
        			Yii::app ()->session ['manager_id'] = $result ['data'] ['id'];
        			Yii::app ()->session ['merchant_name'] = $result ['data'] ['merchant_name'];
        			Yii::app ()->session ['type_name'] = $result ['data'] ['merchant_type_name'];
//         			Yii::app ()->session ['type_id'] = $result ['data'] ['merchant_type_id'];
        			Yii::app ()->session ['time_limit'] = $result ['data'] ['merchant_time_limit'];
        			Yii::app ()->session ['if_tryout'] = $result ['data'] ['merchant_if_try_out'];
        			
        			Yii::app ()->session ['role'] = $result ['data'] ['role'];
        			Yii::app ()->session ['right'] = $result ['data'] ['right'];  //权限
        			Yii::app ()->session ['store_id'] = $result ['data'] ['store_id']; //操作门店
        		}
        	}else{
        		Yii::app()->user->setFlash('login_error',$result['errMsg']);
        		$this->errorCode=self::ERROR_USERNAME_INVALID;
        	}
		}
		return $this->errorCode == self::ERROR_NONE;
	}
	
	public function getId(){
		return $this->_id;
	}
	
	public function getName(){
		return $this -> username;
	}
	
}