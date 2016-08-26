<?php
/**
 * 积分管理
 */
class IntegralController extends CrmController
{
	/**
	 * 积分设置
	 */
	public function actionIntegralSet()
	{
		$flag = false;
		if(isset($_GET) && !empty($_GET)){
			
			foreach ($_GET as $k => $v){
				if(preg_match('/^[a-zA-Z_\x{4e00}-\x{9fa5}]+$/u',trim($v))){
					$flag = true;
					Yii::app()->user->setFlash('points_check','积分要求格式不正确');
				}else if($v == 0){
					$flag = true;
					Yii::app()->user->setFlash('points_check','积分不能为0');
				}elseif ($v <= 0){
					$flag = true;
					Yii::app()->user->setFlash('points_check','积分不能为负数');
				}
			}
			if (!$flag){
				$this->redirect(array('integral/editconsume','info'=>$_GET));
			}
		}
		$merchant_id = Yii::app()->session['merchant_id'];
		$userc = new UserC();
		//储值信息
		$result = $userc -> getStoredPoints($merchant_id);
		$stored_points = $result['stored_points'];
		$is_share = $result['if_storedpay_get_points'];
		$storedflag = $result['flag'];
		//会员等级信息
		$result = json_decode($userc -> getSetUserGradeDraft($merchant_id),true);
		$another = array();
		$default = array();
		if($result['status'] == ERROR_NONE){
			if(isset($result['data']['list'])){
				$another = $result['data']['list'];
			}
			if(isset($result['datas']['list'])){
				$default = $result['datas']['list'];
			}
		}
		//积分清理信息
		$result = $userc -> getClearPoints($merchant_id);
		$clean_start_time = str_replace("-", "/", $result['clean_start_time']);
		$clean_date_type = $result['clean_date_type'];
		$clean_date = str_replace("-", "/", $result['clean_date']);
		$clearflag = $result['flag'];
		
		if($clean_date_type == 1){
			$clean_date_type = '当年';
		}elseif($clean_date_type == 2){
			$clean_date_type = '一年前';
		}elseif($clean_date_type == 3){
			$clean_date_type = '二年前';
		}elseif($clean_date_type == 4){
			$clean_date_type = '三年前';
		}
		$this->render('integralSet',array(
				'default'=>$default,
				'another'=>$another,
				'stored_points'=>$stored_points,
				'is_share'=>$is_share,
				'storedflag'=>$storedflag,
				'clearflag'=>$clearflag,
				'clean_start_time'=>$clean_start_time,
				'clean_date_type'=>$clean_date_type,
				'clean_date'=>$clean_date,
				'showflag'=>$flag
		));
		
	}

	/**
	 * 修改消费积分设置
	 */
	public function actioneditConsume()
	{
		$info = $_GET['info'];
		$userc = new UserC();
		$merchant_id = Yii::app()->session['merchant_id'];
		foreach ($info as $k => $v){
			 $userc->editUserGradePoints($k, $v);
		}
		$result = $userc->releaseUserGrade($merchant_id);
		$result = json_decode($result,true);
		if($result['status'] == ERROR_NONE)
		{
			$url = $this->createUrl('integralSet');
		    echo "<script>alert('设置成功');window.location.href='$url'</script>";
		}else{
			$this->redirect(array('integral/integralSet'));
		}
	}

	/**
	 * 设置积分清理规则
	 */
	public function actionsetClearPoints()
	{
		$info = $_GET;
		
		$cycle = POINT_RULE_CYCLE_YEAR;
		$clean_start_time = str_replace("/", "-", $info['execute_time']);
		$clean_date_type = $info['beforeyear'] + 1;
		$clean_date = str_replace("/", "-", $info['beforetime']);
		
		$userc = new UserC();
		$merchant_id = Yii::app()->session['merchant_id'];
		$result = $userc->setClearPoints($merchant_id, $cycle,$clean_start_time,$clean_date_type,$clean_date);
		if($result['status'] == ERROR_NONE)
		{
			$url = $this->createUrl('integralSet');
			echo "<script>alert('设置成功');window.location.href='$url'</script>";
		}else{
			$this->redirect(array('integral/integralSet'));
		}
		
		
		

		
	}
	
	/**
	 * 执行积分清理脚本
	 * param token 验证密钥
	 */
	public function actionClearIntegral()
	{
		$token = 0;
 		$token = $_GET['token'];
		$userc = new UserC();
 		if($token == 'hy8c6gmkds56sdsa3'){
			$results = $userc -> getAllClearPoints();
			foreach ($results as $k => $v){
				$merchant_id = $v['merchant_id'];
				$clean_start_time = $v['clean_start_time'];
				$clean_date_type = $v['clean_date_type'] - 1;
				$clean_date = $v['clean_date'];
				$currentyear = substr(date('Y:M:D'), 0,4);
				$execute_time = $currentyear.'-'.$clean_start_time;
				$clearyear = $currentyear - $clean_date_type;
				$cleartime = $clearyear.'-'.str_replace("/", "-", $clean_date);
	
				//判断当前时间是否为清理执行时间
				if ($execute_time == $currenttime)
				{
					//找出该商户下的所有会员id
					$result = $userc->getUserId($merchant_id);
					if(!empty($result)){
						//循环清理每个会员的积分
						foreach ($result as $k => $v){
							$result = $userc->clearUserPoints($v,$cleartime,$execute_time);
						}
					}

				}
			}
 		}
		
	}

	/**
	 * 设置储值积分
	 */
	public function actionSetStoredPoints()
	{
		$info = $_GET;
		$stored_points = $info['storedpoints'];
		if(!empty($info['is_share'])){
			$if_storedpay_get_points = POINT_RULE_IF_STOREDPAY_GET_POINT_YES;
		}else {
			$if_storedpay_get_points = POINT_RULE_IF_STOREDPAY_GET_POINT_NO;
		}
		$userc = new UserC();
		$merchant_id = Yii::app()->session['merchant_id'];
		$result = $userc->setStoredPoints($merchant_id, $stored_points,$if_storedpay_get_points);
		if($result['status'] == ERROR_NONE)
		{
			$url = $this->createUrl('integralSet');
		    echo "<script>alert('设置成功');window.location.href='$url'</script>";
		}else{
			$this->redirect(array('integral/integralSet'));
		}
	}

	/**
	 * 规则停用
	 */
	public function  actionStop()
	{
		$type = $_GET['type'];
		$userc = new UserC();
		$merchant_id = Yii::app()->session['merchant_id'];
		$result = $userc->stopPointsSet($merchant_id, $type);
		if($result['status'] == ERROR_NONE)
		{
			$url = $this->createUrl('integralSet');
		    echo "<script>alert('已停用');window.location.href='$url'</script>";
		}else{
			$this->redirect(array('integral/integralSet'));
		}
	}

	/**
	 * 规则启用
	 */
	public function  actionUse()
	{
		$type = $_GET['type'];
		$userc = new UserC();
		$merchant_id = Yii::app()->session['merchant_id'];
		$result = $userc->usePointsSet($merchant_id, $type);
		if($result['status'] == ERROR_NONE)
		{
			$url = $this->createUrl('integralSet');
			echo "<script>alert('已启用');window.location.href='$url'</script>";
		}else{
			$this->redirect(array('integral/integralSet'));
		}
	}
	
	
}