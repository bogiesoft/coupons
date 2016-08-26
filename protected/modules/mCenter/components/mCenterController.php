<?php
include_once(realpath(dirname(__FILE__).'/../../../../protected/config').'/constant.php');
include_once(realpath(dirname(__FILE__).'/../../../../protected/class/config').'/constant.php');
include_once(realpath(dirname(__FILE__).'/../../../../protected/class/config').'/constant/gjConstant.php');
include_once(realpath(dirname(__FILE__).'/../../../../protected/class/config').'/constant/urlConstant.php');
include_once(realpath(dirname(__FILE__).'/../../../../protected/class/config').'/codeMsg.php');
include_once(realpath(dirname(__FILE__).'/../../../../protected/class/config/constant').'/generalConstant.php');
class mCenterController extends CController
{
	public $layout='main';
	public $menu=array();
	public $breadcrumbs=array(); 
	public $right_arr = array();
	
	/**
	 * 判断用户是否登录isGuest
	 * 未登录则跳转到登录页面
	 */
	public function init(){
		$right = Yii::app ()->session ['right'];
		if(!empty($right)){
			$right = substr($right,1,strlen($right)-2);
			$this->right_arr = explode(',',$right);
			Yii::app() -> session['right_arr'] = $this->right_arr;
		}else{
			Yii::app() -> session['right_arr'] = $this->right_arr;
		}
		if(!isset(Yii::app() -> session['merchant_id']) || empty(Yii::app() -> session['merchant_id'])){
			echo("<script>parent.location.href='".Yii::app() -> createUrl('mCenter/auth/Login')."'</script>");
			Yii::app() -> end();
		}else{
		    //如果已经登录 判断商户玩券管家版本及是否到期
		    $merchant_id = Yii::app() -> session['merchant_id'];
		    $manager_id = '';
		    $role = '';
    		if(Yii::app() -> session['role'] == WQ_ROLE_MANAGER){
                $manager_id = Yii::app() -> session['manager_id'];
                $role = Yii::app ()->session ['role'];
            }
		    $merchantC = new MerchantC(); 
		    $result = json_decode($merchantC -> getMerchantWqinfo($merchant_id,$role,$manager_id));
		    if($result -> status == ERROR_NONE){
		        //判断是否是营销版
		        Yii::app ()->session ['type_id'] = $result -> data -> gj_product_id;
		        Yii::app ()->session ['type_name'] = $GLOBALS['__WANQUAN_TYPE'][$result -> data -> gj_product_id];
		        Yii::app ()->session ['time_limit'] = $result -> data -> gj_end_time;
		        Yii::app ()->session ['if_tryout'] = $result -> data -> if_tryout;
		        if(isset($result -> data -> right)){
		            Yii::app ()->session ['right'] = $result -> data -> right;
		        }
		    }
		}
		return;
	}
}