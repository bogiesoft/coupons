<?php
include_once(dirname(__FILE__).'/../mainClass.php');

class MerchantAuthC extends mainClass{
	public function registerEmail($order_id,$email_account,$pwd,$pwd_confirm){
	    $result = array();
	    try {
	        $transaction= Yii::app ()->db->beginTransaction();
	        //订单验证
	        $order = GjOrder::model() -> find('id=:id and flag=:flag',array(
	            ':id' => $order_id,
	            ':flag' => FLAG_NO
	        ));
	        if(!isset($order_id) || empty($order_id)){
	            $result['status'] = ERROR_PARAMETER_MISS;
	            throw new Exception('订单号不能为空');
	        }else if(empty($order)){
                $result['status'] = ERROR_NO_DATA;
                throw new Exception('该订单不存在');
            }
	        //账号验证
	        $merchant = Merchant::model() -> find('account=:account and email_confirm_status = :email_confirm_status and flag = :flag',array(
	            ':account' => $email_account,
	            ':flag' => FLAG_NO,
	            ':email_confirm_status' => MERCHANT_EAMIL_CONFIRM_CONFIRMED
	        ));
	        if(!isset($email_account) || empty($email_account)){
	            $result['status'] = ERROR_PARAMETER_MISS;
	            throw new Exception('邮箱不能为空');
	        }else if(!preg_match(EMAIL_CHECK, $email_account)){
	            $result['status'] = ERROR_PARAMETER_MISS;
	            throw new Exception('邮箱格式错误');
	        }else if(!empty($merchant)){
                $result['status'] = ERROR_DUPLICATE_DATA;
                throw new Exception('邮箱账号已存在');
            }
	        //密码验证
	        if(!isset($pwd) || empty($pwd)){
	            $result['status'] = ERROR_PARAMETER_MISS;
	            throw new Exception('密码不能为空');
	        }else if(strlen($pwd) <= 6 && strlen($pwd) >= 16){
	            $result['status'] = ERROR_PARAMETER_FORMAT;
	            throw new Exception('密码不能少于6位或大于16位');
	        }
	        if(!isset($pwd_confirm) || empty($pwd_confirm)){
	            $result['status'] = ERROR_PARAMETER_FORMAT;
	            throw new Exception('确认密码不能为空');
	        }else if($pwd !== $pwd_confirm){
	            $result['status'] = ERROR_PARAMETER_FORMAT;
	            throw new Exception('两次密码不一致');
	        }
	        
	        //查询对应商户
	        $merchant = Merchant::model() -> find('id =:id and flag =:flag',array(
	            ':id' => $order -> merchant_id,
	            ':flag' => FLAG_NO
	        ));
	        if(!empty($merchant)){
	            $merchant -> account = $email_account;
	            $merchant -> pwd = md5($pwd);
	            $code = $this -> getRandChar(5);
	            $mer = Merchant::model() -> find('merchant_number =:merchant_number and flag=:flag',array(
	                ':merchant_number' => $code,
	                ':flag' => FLAG_NO
	            ));
	            while ($mer){
	                $code = $this -> getRandChar(5);
	                $mer = Merchant::model() -> find('merchant_number =:merchant_number and flag=:flag',array(
	                    ':merchant_number' => $code,
	                    ':flag' => FLAG_NO
	                ));
	            }
	            $merchant -> merchant_number = $code;
	            $merchant -> gj_start_time = new CDbExpression('now()');
	            $merchant -> gj_product_id = $order -> wq_product_id;//玩券管家产品id
	            $merchant -> if_tryout = $order -> if_tryout; //是否试用
	            //商户号（玩券接口对外商户号）
	            if(empty($merchant -> mchid)){
	                $merchant -> mchid = $this->createMchid();
	            }
	            if($order -> if_tryout == IF_TRYOUT_YES){
	                //试用版送短信200条
	                $merchant -> msg_num += 200;
	            }else{
	                //正式版送短信500条
	                $merchant -> msg_num += 500;
	            }
	            $merchant -> tryout_status = TRYOUT_STATUS_NORMAL;//管家试用状态
	            $merchant -> gj_open_status = GJ_OPEN_STATUS_OPEN;//管家开通状态
	             
	            //设置默认相册
	            $album = Album::model() -> findAll('merchant_id =:merchant_id and flag =:flag',array(
	                ':merchant_id' => $merchant -> id,
	                ':flag' => FLAG_NO
	            ));
	            //如果没有服务窗识别码，就创建一个
	            if(empty($merchant -> encrypt_id)){
	                $encrypt_id = $this->getEncryptRandChar(6);
	                $merchant_temp = Merchant::model() -> find('encrypt_id =:encrypt_id and flag =:flag',array(
	                    ':encrypt_id' => $encrypt_id,
	                    ':flag' => FLAG_NO
	                ));
	                while (!empty($merchant_temp)){
	                    $encrypt_id = $this->getEncryptRandChar(6);
	                    $merchant_temp = Merchant::model() -> find('encrypt_id =:encrypt_id and flag =:flag',array(
	                        ':encrypt_id' => $encrypt_id,
	                        ':flag' => FLAG_NO
	                    ));
	                }
	                $merchant -> encrypt_id = $encrypt_id;
	            }
	            if(empty($album)){
	                $album1 = new Album();
	                $album1 -> merchant_id = $merchant -> id;
	                $album1 -> name = '菜品';
	                $album1 -> create_time = new CDbExpression('now()');
	                $album1 -> save();
	                 
	                $album2 = new Album();
	                $album2 -> merchant_id = $merchant -> id;
	                $album2 -> name = '环境';
	                $album2 -> create_time = new CDbExpression('now()');
	                $album2 -> save();
	                 
	                $album3 = new Album();
	                $album3 -> merchant_id = $merchant -> id;
	                $album3 -> name = '其他';
	                $album3 -> create_time = new CDbExpression('now()');
	                $album3 -> save();
	            }
	            if($merchant -> update()){
	                $order -> code_merchant_id = $merchant -> id;
	                if($order -> update()){
	                    $transaction->commit();
	                    $result['data'] = array('encrypt_id' => $merchant -> encrypt_id);
	                    $result['status'] = ERROR_NONE;
	                }else{
	                    $result['status'] = ERROR_SAVE_FAIL;
	                    throw new Exception('订单更新失败');
	                }
	            }else{
	                //商户保存失败
	                $result['status'] = ERROR_SAVE_FAIL;
	                throw new Exception('商户更新失败');
	            }
	        }else{
	            $result['status'] = ERROR_NO_DATA;
	            throw new Exception('该商户不存在');
	        }
        }catch (Exception $e) {
            $transaction->rollBack();
    		$result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
    		$result['errMsg'] = $e->getMessage(); //错误信息
		}
		return json_encode($result);
	}
	//产生六位 数字+字母随机字符串，首位不为0
	private function getEncryptRandChar($length){
	    $str = null;
	    $strPol = "0123456789abcdefghijklmnopqrstuvwxyz";
	    $strPol_nozero = "123456789abcdefghijklmnopqrstuvwxyz";
	    $max = strlen($strPol)-1;
	    for($i=0;$i<$length;$i++){
	        if($i == 0){
	            $str.=$strPol_nozero[rand(0,$max-1)];
	        }else{
	            $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
	        }
	    }
	    return $str;
	}
	//获取定长的随机字符串
	private function getRandChar($length){
	    $str = null;
	    $strPol = "012356789";
	    $strPolnoZero = "12356789";
	    $max = strlen($strPol)-1;
	    for($i=0;$i<$length;$i++){
	        if ($i == 0){
	            $str.=$strPolnoZero[rand(0,$max-1)];
	        }else{
	            $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
	        }
	    }
	    return $str;
	}
	//生成玩券对外接口商户号
	private function createMchid(){
	    $mchid = date('ym').$this->getRandChar(6);
	    $merchant = Merchant::model() -> find('mchid = :mchid and flag = :flag',array(
	        ':mchid' => $mchid,
	        ':flag' => FLAG_NO
	    ));
	    while (!empty($merchant)){
	        $mchid = date('yd').$this->getRandChar(6);
	        $merchant = Merchant::model() -> find('mchid = :mchid and flag = :flag',array(
	        				':mchid' => $mchid,
	        				':flag' => FLAG_NO
	        ));
	    }
	    return $mchid;
	}
	
	/**
	 * 邮箱确认激活玩券管家账号
	 * @param unknown $encrypt_id
	 * @param unknown $code
	 * @throws Exception
	 */
	public function editMerchantEmailConfirm($encrypt_id,$code){
	    $result = array();
	    try {
	        $transaction= Yii::app ()->db->beginTransaction();
	        if(!isset($encrypt_id) || empty($encrypt_id)){
	            $result['status'] = ERROR_PARAMETER_MISS;
	            throw new Exception('参数encrypt_id缺失');
	        }
	        if(!isset($code) || empty($code)){
	            $result['status'] = ERROR_PARAMETER_MISS;
	            throw new Exception('参数code缺失');
	        }
	        //账号验证
	        $merchant = Merchant::model() -> find('encrypt_id=:encrypt_id and flag = :flag and email_confirm_status=:email_confirm_status',array(
	            ':encrypt_id' => $encrypt_id,
	            ':flag' => FLAG_NO,
	            ':email_confirm_status' => MERCHANT_EAMIL_CONFIRM_TOBECONFIRM
	        ));
	        if (!empty($merchant)){
	            //订单验证
	            $order = GjOrder::model() -> find('invite_code=:invite_code and code_merchant_id=:code_merchant_id and flag=:flag',array(
	                ':invite_code' => $code,
	                ':code_merchant_id' => $merchant->id,
	                ':flag' => FLAG_NO
	            ));
	            if (!empty($order)){
	                $merchant -> email_confirm_status = MERCHANT_EAMIL_CONFIRM_CONFIRMED;
                    $order -> order_status = GJORDER_STATUS_USED;
	                $order -> code_use_time = new CDbExpression('now()');
	                if($order -> update() && $merchant -> update()){
	                    $transaction->commit();
	                    $result['status'] = ERROR_NONE;
	                }else{
	                    $result['status'] = ERROR_SAVE_FAIL;
	                    throw new Exception('订单或商户更新失败');
	                }
	            }else{
    	            $result['status'] = ERROR_NO_DATA;
    	            throw new Exception('该订单不存在');
    	        }
	        }else{
	            $result['status'] = ERROR_NO_DATA;
	            throw new Exception('该商户不存在');
	        }
	    }catch (Exception $e) {
	        $transaction->rollBack();
	        $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
	        $result['errMsg'] = $e->getMessage(); //错误信息
	    }
	    return json_encode($result);
	}
	
	public function checkLogin($username,$password){
	    $result = array();
	    try {
	        if(!isset($username) || empty($username)){
	            $result['status'] = ERROR_PARAMETER_MISS;
	            throw new Exception('参数username缺失');
	        }
	        if(!isset($password) || empty($password)){
	            $result['status'] = ERROR_PARAMETER_MISS;
	            throw new Exception('参数password缺失');
	        }
	        //账号验证
	        $merchant = Merchant::model() -> find('account=:account and flag = :flag and pwd=:pwd',array(
	            ':account' => $username,
	            ':flag' => FLAG_NO,
	            ':pwd' => md5($password)
	        ));
	        $manager = Manager::model() -> find('account=:account and flag = :flag and pwd=:pwd',array(
	            ':account' => $username,
	            ':flag' => FLAG_NO,
	            ':pwd' => md5($password)
	        ));
	        if (!empty($merchant) || !empty($manager)){
                $result['status'] = ERROR_NONE;
	        }else{
	            $result['status'] = ERROR_NO_DATA;
	            throw new Exception('该商户不存在');
	        }
	    }catch (Exception $e) {
	        $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
	        $result['errMsg'] = $e->getMessage(); //错误信息
	    }
	    return json_encode($result);
	}
	
	/**
	 * 邮箱账号是否存在
	 */
	public function isExist($email){
	    //账号验证
        $merchant = Merchant::model() -> find('account=:account and flag = :flag and email_confirm_status = :email_confirm_status',array(
            ':account' => $email,
            ':email_confirm_status' => MERCHANT_EAMIL_CONFIRM_CONFIRMED,
            ':flag' => FLAG_NO
        ));
	    if (empty($merchant)){
	        return false;
	    }
	    return true;
	}
}