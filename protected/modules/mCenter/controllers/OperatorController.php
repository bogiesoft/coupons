<?php

/**
 * 操作员管理
 */
class OperatorController extends mCenterController
{
	
	/**
	 * 操作员列表
	 */
	public function actionOperator() {
		$list = array();
		$stores = array();
		
		$manager_id = '';
		if(Yii::app() -> session['role'] == WQ_ROLE_MANAGER){
			$manager_id = Yii::app() -> session['manager_id'];
		}
		//获取门店列表
		$management_id = Yii::app()->session['management_id'];//管理单元id
		$merchant_id = Yii::app()->session['merchant_id'];//商户id
		$store = new StoreC();
		$ret = $store->StoreList($merchant_id,'',$manager_id);//调用StoreList()方法
		$result = json_decode($ret, true);//返回$result值
		if ($result['status'] == ERROR_NONE) {
			if (isset($result['data']['list'])) {
				$list1 = $result['data']['list'];
				foreach ($list1 as $k => $v) {
					$main_name = $v['name'];
					$branch_name = !empty($v['branch_name']) ? '('.$v['branch_name'].')' : '';
					$stores[$v['id']] = $main_name.$branch_name;
				}
			}
		}
		
		//关键词
		$keyword = '';
		if (isset($_GET['keyword'])) {
			$keyword = $_GET['keyword'];
		}
		$right_arr = '';
		if(Yii::app ()->session ['role'] == WQ_ROLE_MANAGER){ //管理员只能操作分配的门店
            $storeId = Yii::app ()->session ['store_id'];
            $right_arr = array();
            if (!empty ( $storeId )) {
            	$storeId = substr ( $storeId, 1, strlen ( $storeId ) - 2 );
            	$right_arr = explode ( ',', $storeId );
            }
        }else {
        	if (isset($_GET['store_id']) && !empty($_GET['store_id'])) {
        		$store_id = $_GET['store_id'];
        		$right_arr = array($store_id);
        	}
        }
		$operator = new OperatorC();
		$result = json_decode($operator -> getOperatorList($merchant_id,$keyword,$right_arr),true);
		if ($result['status'] == ERROR_NONE) {
			$list = $result['data']['list'];
		}
		
		$this->render('operator', array('list' => $list, 'stores' => $stores, 'pages' => $operator -> page , 'keyword'=>$keyword));
	}
	
	/**
	 * 生成店长密码
	 */
	public function actionCreatePwd() {
		$data = '';
		$merchant_id = Yii::app()->session['merchant_id'];
		$operator = new OperatorC();
		$ret = $operator->createAdminPwd($merchant_id);
		$result = json_decode($ret, true);
		if ($result['status'] == ERROR_NONE) {
			$data = isset($result['data']['admin_pwd']) ? $result['data']['admin_pwd'] : '';
		}
		echo $data;
	}
	
	/**
	 * 添加操作员
	 */
	public function actionAddOperator() {
		$stores = array();
		$manager_id = '';
        if(Yii::app() -> session['role'] == WQ_ROLE_MANAGER){
            $manager_id = Yii::app() -> session['manager_id'];
        }
		
		//获取门店列表
		$management_id = Yii::app()->session['management_id'];//管理单元id
		$merchant_id = Yii::app()->session['merchant_id'];//商户id
		$store = new StoreC();
		$ret = $store->StoreList($merchant_id,'',$manager_id);//调用StoreList()方法
		$result = json_decode($ret, true);//返回$result值
		if ($result['status'] == ERROR_NONE) {
			if (isset($result['data']['list'])) {
				$list = $result['data']['list'];
				foreach ($list as $k => $v) {
					$main_name = $v['name'];
					$branch_name = !empty($v['branch_name']) ? '-'.$v['branch_name']: '';
					$stores[$v['id']] = $main_name.$branch_name;
				}
			}
		}else {
			$this->redirect('operator');
		}
		//获取商户信息
		$merchant = new MerchantC();
		$ret1 = $merchant->getMerchantDetails($merchant_id);//调用getMerchantDetails()方法
		$result1 = json_decode($ret1, true);
		if ($result1['status'] == ERROR_NONE) {
			$m_account = $result1['data']['merchant_number'];
		}else {
			$m_account = '';
		}

		$operator = new OperatorC();
		$model = $operator->getAttributes();//调用getAttributes()方法

		if (isset($_POST['Operator']) && !empty($_POST['Operator'])) {
			$post = $_POST['Operator'];
			$flag = false;
			if (empty($post['number'])) {
				$flag = true;
				Yii::app()->user->setFlash('number_error', '请输入编号');
			}else {
				if (!is_numeric($post['number']) || (strlen($post['number']) != 4 && strlen($post['number']) != 5) || strstr($post['number'], '.')) {
					$flag = true;
					Yii::app()->user->setFlash('number_error', '请输入4位或5位纯数字');
				}
			}
			if (empty($post['pwd'])) {
				$flag = true;
				Yii::app()->user->setFlash('pwd_error', '请输入登录密码');
			}
            else if(strlen($post['pwd'])!=8)
            {
                $flag = true;
                Yii::app()->user->setFlash('pwd_error', '密码应设置为8位');
            }
            else if(!ctype_alnum($post['pwd']))
            {
                $flag = true;
                Yii::app()->user->setFlash('pwd_error', '密码只能为数字或字母');
            }

			if (empty($post['name'])) {
				$flag = true;
				Yii::app()->user->setFlash('name_error', '请输入姓名');
			}
			if ($post['role'] == OPERATOR_ROLE_ADMIN && empty($post['admin_pwd'])) {
				$flag = true;
				Yii::app()->user->setFlash('admin_error', '请输入店长密码');
			}
			
			
			if (!$flag) {
				$store_id = $post['store_id'];
				$number = $post['number'];
				$name = $post['name'];
				$account = $post['account'];
				$role = $post['role'];
				$pwd = $post['pwd'];
				$admin_pwd = $post['admin_pwd'];
				$ret = $operator->addOperator($number, $store_id, $name, $account, $role, $pwd, $admin_pwd);
				$result = json_decode($ret, true);

				if ($result['status'] == ERROR_NONE) {
					$this->redirect('operator');
				}else {
					$status = $result['status'];
					$msg = $result['errMsg'];
					Yii::app()->user->setFlash('error', $msg);
				}
			}
		}

		$this->render('addOperator', array('stores' => $stores, 'model' => $model, 'm_account' => $m_account));
	}
	
	/**
	 * 修改操作员
	 */
	public function actionEditOperator() {
        $stores = array();

        $merchant_id = Yii::app()->session['merchant_id'];
        $storeC = new StoreC();
        $ret = $storeC->StoreList($merchant_id);
        $result = json_decode($ret, true);
        if ($result['status'] == ERROR_NONE) {
            if (isset($result['data']['list'])) {
                $list = $result['data']['list'];
                foreach ($list as $k => $v) {
                	$main_name = $v['name'];
                	$branch_name = !empty($v['branch_name']) ? '('.$v['branch_name'].')' : '';
                	$stores[$v['id']] = $main_name.$branch_name;
                }
            }
        }else {
            $this->redirect('operator');
        }

        if (isset($_GET['id']) && !empty($_GET['id'])) {
            //操作员查询
            $operatorC = new OperatorC();
            $ret = $operatorC->getOperatorDetails($_GET['id']);
            $result = json_decode($ret, true);
            if ($result['status'] == ERROR_NONE) {
                $model = $result['data'];
            }else {
                $this->redirect('operator');
            }
        }else {
            $this->redirect('addOperator');
        }

        if (isset($_POST['Operator']) && !empty($_POST['Operator'])) {
        	$post = $_POST['Operator'];
			$flag = false;
			if (empty($post['name'])) {
				$flag = true;
				Yii::app()->user->setFlash('name_error', '请输入姓名');
			}
			if ($post['role'] == OPERATOR_ROLE_ADMIN && empty($post['admin_pwd'])) {
				$flag = true;
				Yii::app()->user->setFlash('admin_error', '请输入店长密码');
			}
            if (empty($post['pwd'])) {
//                 $flag = true;
//                 Yii::app()->user->setFlash('pwd_error', '请输入登录密码');
            }
            else if(strlen($post['pwd'])!=8)
            {
                $flag = true;
                Yii::app()->user->setFlash('pwd_error', '密码应设置为8位');
            }
            else if(!ctype_alnum($post['pwd']))
            {
                $flag = true;
                Yii::app()->user->setFlash('pwd_error', '密码只能为数字或字母');
            }
			
			if (!$flag) {
				$id = $_GET['id'];
				$store_id = $post['store_id'];
				$role = $post['role'];
				$name = $post['name'];
				$pwd = $post['pwd'];
				$admin_pwd = $post['admin_pwd'];
				$ret = $operatorC->editOperator($id, $store_id, $role, $name, $pwd, $admin_pwd);
				$result = json_decode($ret, true);
				if ($result['status'] == ERROR_NONE) {
					$this->redirect('operator');
				}else {
					$status = $result['status'];
					$msg = $result['errMsg'];
					Yii::app()->user->setFlash('error', $msg);
				}
			}
        }
        $this->render('editOperator', array('stores' => $stores, 'model' => $model));
	}
	
	/**
	 * 删除操作员
	 */
	public function actionDeleteOperator() {
		if (isset($_GET['id']) && !empty($_GET['id'])) {
			$operatorC = new OperatorC();
			$ret = $operatorC->deleteOperator($_GET['id']);
		}
		$this->redirect('operator');
	}
	
	
	/**
	 * 锁定/解锁操作员
	 */
	public function actionChangeStatus() {
		if (isset($_GET['id']) && !empty($_GET['id']) && isset($_GET['status']) && !empty($_GET['status'])) {
			$operatorC = new OperatorC();
			if ($_GET['status'] == OPERATOR_STATUS_NORMAL) {
				$ret = $operatorC->lockOperator($_GET['id']);
			}else {
				$ret = $operatorC->unlockOperator($_GET['id']);
			}
		}
		$this->redirect('operator');
	}
	
	/**
	 * 操作员日志
	 */
	public function actionOperatorLog() {
		$list = array();
		
		//操作员id
		$operator_id = '';
		if (isset($_GET['operator_id']) && !empty($_GET['operator_id'])) {
			$operator_id = $_GET['operator_id'];
		}
		
		$manager_id = '';
		if(Yii::app() -> session['role'] == WQ_ROLE_MANAGER){
		    $manager_id = Yii::app() -> session['manager_id'];
		}
		
		//时间段查询
		$start_time = '';
		$end_time = '';
		if (isset($_GET['Time']) && !empty($_GET['Time'])) {
			$time = explode(" - ", $_GET['Time']);
			$start_time = $time[0];
			$end_time = $time[1];
		}
		
		$merchant_id = Yii::app()->session['merchant_id'];

		//操作员编号列表
//		$operators = array('0' => '选择操作员');
        $operators=array();
		//门店列表
		$store = new StoreC();
		$ret1 = $store->getStoreList($merchant_id,'','',$manager_id);
		$result1 = json_decode($ret1, true);
		$operator = new OperatorC();
		if ($result1['status'] == ERROR_NONE) {
			if (isset($result1['data']['list'])) {
				foreach ($result1['data']['list'] as $k1 => $v1) {
					//操作员列表
					$ret2 = $operator->getOper($merchant_id,$v1['id']);
					$result2 = json_decode($ret2, true);
					if ($result2['status'] == ERROR_NONE) {
						if (isset($result2['data'])) {
							foreach ($result2['data'] as $k2 => $v2) {
								$operators[$v2['id']] = '操作员'.$v2['number'];
							}
						}
					}
				}
			}
		}
		$ret = $operator->getOperatorLog($merchant_id, $operator_id, $start_time, $end_time,$manager_id);
		$result = json_decode($ret, true);
		if ($result['status'] == ERROR_NONE) {
			if (isset($result['data']['list'])) {
				$list = $result['data']['list'];
			}
		}
		
		$this->render('operatorLog', array('list' => $list, 'operators' => $operators, 'pages' => $operator->page));
	}
	
        /**
         * 操作员设置
         */
        public function actionOperatorSet()
        {
            $operatorC = new OperatorC();
            $merchant_id = Yii::app()->session['merchant_id'];
            if(isset($_POST) && $_POST)//判断是否有post值
            {                
                $flag = 0;
                $day         = isset($_POST['day']) ? $_POST['day'] : 0;
                $hour        = isset($_POST['hour']) ? $_POST['hour'] : 0;
                $clock       = isset($_POST['clock']) ? $_POST['clock'] : 0;
                $store_day   = isset($_POST['store_day']) ? $_POST['store_day'] : 0;
                $store_hour  = isset($_POST['store_hour']) ? $_POST['store_hour'] : 0;
                $store_clock = isset($_POST['store_clock']) ? $_POST['store_clock'] : 0;
                $dzoperator_refund_time   = $day * 86400 + $hour * 3600 + $clock * 60;
                $operator_refund_time = $store_day * 86400 + $store_hour * 3600 + $store_clock * 60;
                if($dzoperator_refund_time < $operator_refund_time)
                {
                    echo "<script>alert('店长退款时间不能小于店员退款时间')</script>";
                    $flag = 1;
                } else {
                    if($dzoperator_refund_time > 7776000)
                    {
                        echo "<script>alert('店长退款时间不能大于90天')</script>";
                        $flag = 1;
                    }
                    if($operator_refund_time > 7776000)
                    {
                        echo "<script>alert('店员退款时间不能大于90天')</script>";
                        $flag = 1;
                    }
                }
                if($flag == 0)
                {
                    $ret = $operatorC->OperatorSet($merchant_id,$operator_refund_time,$dzoperator_refund_time);
                    $result = json_decode($ret, true);
                    if($result['status'] == ERROR_NONE)
                    {
                        $url = Yii::app()->createUrl('mCenter/Operator/OperatorSet');
                        echo "<script>alert('设置成功');window.location.href='$url'</script>";
                    }
                }
            }
            
            $rat = $operatorC ->OperatorSetSee($merchant_id);
            $rot = json_decode($rat,true);
            if($rot['status'] == ERROR_NONE)
            {
                $operatorset = $rot['data'];
            }
            $this->render('operatorSet',array('operatorset'=>$operatorset));
        }

    /**
     * 验证操作员编号是否重复
     */
    public function actionCheckOperatorNumber()
    {
        if(!empty($_GET['account'])&&isset($_GET['account']))
        {
            $account=$_GET['account'];
            $operatorC = new OperatorC();
            $merchant_id = Yii::app()->session['merchant_id'];
            $rs=json_decode($operatorC->checkOperatorNumber($merchant_id,$account),true);
            if($rs['status']==ERROR_DUPLICATE_DATA)
            {
                //操作员编号重复
                echo json_encode('DUPLICATE');
            }
            else
            {
                echo json_encode('NO_DATA');
            }
        }
    }
}