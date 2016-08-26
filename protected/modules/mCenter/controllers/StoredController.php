<?php

/**
 * 储值管理
 *
 */
class StoredController extends mCenterController
{
		
	/**
	 * 储值活动列表   添加储值活动
	 */
	public function actionStoredList()
	{
		$list = array();
		
		$time = '';
		if(isset($_GET['Time']) && !empty($_GET['Time'])){
			$time = $_GET['Time'];
		}
		$stored = new StoredC();
		$merchant_id = Yii::app()->session['merchant_id'];
		$model = $stored -> getStoredList($merchant_id,$time);
		$model = json_decode($model,true);
		if($model['status'] == ERROR_NONE){
			if(isset($model['data']['list'])){
				$list = $model['data']['list'];
			}
		}
       	$flag = 0;
		if(isset($_POST['Stored']) && !empty($_POST['Stored'])){
			$post = $_POST['Stored'];
	       	if($flag == 0){
             	$name=$post['name'];
              	$stored_money = $post['stored_money'];
               	$get_money = $post['get_money'];
              	$start_time = '';
               	$end_time = '';
              	if(isset($_POST['Time']) && !empty($_POST['Time'])){
                   	$Time = array();
                   	$Time =explode("-", $_POST['Time']);
                   	$start_time = $Time[0];
                  	$end_time = $Time[1];
              	}  
              	$re = $stored -> addStored($merchant_id,$name,$stored_money,$get_money,$start_time,$end_time);
              	$result = json_decode($re,true);
              	if ($result['status'] == ERROR_NONE) {
                  	$this->redirect(array('storedList'));
               	}else {
                   	$status = $result['status'];
                  	$msg = $result['errMsg'];                                    
              	}
         	}
     	}
      	//是否开启储值功能
		$if_stored_array = array();
		//$if_stored_array['1'] = '关闭储值功能';
		$if_stored_array['2'] = '开启储值功能';
		$merchantC = new MerchantC();
		$result_merchant = json_decode($merchantC -> getMerchantDetails($merchant_id));
		if($result_merchant -> status == ERROR_NONE){
            $merchant = $result_merchant -> data;
        }
		$this->render('StoredList',array(
				'list'=>$list,
				'pages'=>$stored -> page,
				'if_stored_array'=>$if_stored_array,
				'merchant'=>$merchant
		));
	}
	
	/**
	 * 删除储值活动
	 */
	public function actionDelStored($id)
	{
		$stored = new StoredC();
		$result = $stored -> delStored($id);
		$result = json_decode($result,true);
		if($result['status'] == ERROR_NONE){
			$this -> actionStoredList();
		}else{
			var_dump($result['errMsg']) ;
		}
	}
	
	/**
	 * 储值详情
	 */
	public function actionStoredDetails($id)
	{
		$list = array();
		
		$stored = new StoredC();
		$result = $stored -> getStoredDetails($id);
		$result = json_decode($result,true);
		if($result['status'] == ERROR_NONE){
			if(isset($result['data']['list'])){
				$list = $result['data']['list'];
			}
		}else{
			var_dump($result['errMsg']) ;
		}
		$this->render('storedDetails',array('list'=>$list));
	}
	
	/**
	 * 储值明细列表
	 */
	public function actionStoredOrder()
	{
		$list = array();
		$stored = new StoredC();
		$merchant_id = Yii::app()->session['merchant_id'];
		$result = $stored -> getStoredOrder($merchant_id);
		$result = json_decode($result,true);
		
		if($result['status'] == ERROR_NONE){
			if(isset($result['data']['list'])){
				$list = $result['data']['list'];
			}
		}
		$this -> render('storedOrder',array(
				'list'=>$list,
				'pages' => $stored -> page
		));
	}

    /**
     * 储值活动保存前ajax验证
     */
    public function actionCheckStored()
    {
        $flag=true;
        $stored = new StoredC();
        $merchant_id = Yii::app()->session['merchant_id'];
        $arr=array('name'=>null,'stored_money'=>null,'get_money'=>null,'time'=>null);
        if(!empty($_GET['name']))
        {
            $name=$_GET['name'];
            if(ctype_space($name))
            {
                $flag=false;
                $arr['name']='活动名不能全为空格';
            }
            else
            {
                //判断活动名是否重复
                $rs=json_decode($stored->checkStoredName($merchant_id,$name),true);
                if($rs['status']==ERROR_DUPLICATE_DATA)
                {
                    //有储值活动名称相同
                    $flag = false;
                    $arr['name']='活动名不能有相同';
                }
            }
        }
        else
        {
            $flag=false;
            $arr['name']='活动名不能为空';
        }

        if(!empty($_GET['stored_money']))
        {
            $stored_money=$_GET['stored_money'];
            if($stored_money=='0')
            {
                $flag=false;
                $arr['stored_money']='预存金额不为0';
            }
            if(preg_match('/^[a-zA-Z_\x{4e00}-\x{9fa5}]+$/u',trim($stored_money))){
                $flag=false;
                $arr['stored_money']='预存金额格式不正确';
            } else {
                if($stored_money <= 0)
                {
                    $flag=false;
                    $arr['stored_money']='预存金额不为负数';
                }
            }
            
            $rs_stored=array();
            $stored_money=$_GET['stored_money'];
            $rs_stored=json_decode($stored->checkStoredMoneySame($merchant_id,$stored_money),true);
            if($rs_stored['status']==ERROR_DUPLICATE_DATA)
            {
            	//有储值相同
            	$flag=false;
            	$arr['stored_money']='预存金额不能有相同';
            }
            
        }else{
            $flag=false;
            $arr['stored_money']='预存金额必填';
        }

        //判断储值金额是否相同
        if($flag)
        {
            $rs_stored=array();
            $stored_money=$_GET['stored_money'];
            $rs_stored=json_decode($stored->checkStoredMoneySame($merchant_id,$stored_money),true);
            if($rs_stored['status']==ERROR_DUPLICATE_DATA)
            {
                //有储值相同
                $flag=false;
                $arr['stored_money']='预存金额不能有相同';
            }
        }

        if(empty($_GET['get_money']))
        {
            if($_GET['get_money'] === '0' || $_GET['get_money'] === 0)
            {

            } else {
                $flag=false;
                $arr['get_money']='赠送金额必填';
            }
        }


        if(!empty($_GET['get_money']))
        {
            if(preg_match('/^[a-zA-Z_\x{4e00}-\x{9fa5}]+$/u',trim($_GET['get_money']))){
                $flag=false;
                $arr['get_money']='赠送金额格式不正确';
            } else {
                if($_GET['get_money'] <= 0)
                {
                    $flag=false;
                    $arr['get_money']='赠送金额不为负数';
                }
            }
            
           
            $get_money=$_GET['get_money'];
            $rs_stored=$stored->checkGetMoneySame($merchant_id,$get_money);
            if($rs_stored)
            {
            	//有储值相同
            	$flag=false;
            	$arr['get_money']='赠送金额不能有相同';
            }
        }

        if(empty($_GET['Time']))
        {
            $flag=false;
            $arr['time']='请选择活动时间';
        }

        echo json_encode($arr);
    }
}