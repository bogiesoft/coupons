<?php

/**
 * 门店管理
 *
 */
class StoreController extends mCenterController
{
    
    public function actionIndex(){
        
    }

    /**
     * 门店列表
     */
    public function actionStore()
    {
        $list = array();

        //关键词
        $keyword = '';
        if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
            $keyword = $_GET['keyword'];
        }
        $store = new StoreC();
        $merchant_id = Yii::app()->session['merchant_id'];
        $model = json_decode($store->getStoreList($merchant_id, $keyword), true);
        if ($model['status'] == ERROR_NONE) {
            if (isset($model['data']['list'])) {
                $list = $model['data']['list'];
            }
        }
        $this->render('store', array('list' => $list, 'pages' => $store->page));
    }

    /**
     * 门店信息
     */
    public function actionStoreInfo()
    {
        $pca = new Selector();
        $store = new StoreC();
        $list = array();

        //搜索：关键词
        $keyword = '';
        if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
            $keyword = CHtml::encode(trim($_GET['keyword']));
        }

        //搜索：省市区
        $address = '';
        if (!empty($_GET['Selector']['liveplaceProvince'])) {
            $address = $_GET['Selector']['liveplaceProvince'];
            if (!empty($_GET['Selector']['liveplaceCity'])) {
                $address = $address . ',' . $_GET['Selector']['liveplaceCity'] . ',' . $_GET['Selector']['liveplaceArea'];
            }
            $pca->liveplaceProvince = $_GET['Selector']['liveplaceProvince'];
            $pca->liveplaceCity = $_GET['Selector']['liveplaceCity'];
            $pca->liveplaceArea = $_GET['Selector']['liveplaceArea'];
        }

        $merchant_id = Yii::app()->session['merchant_id'];
        $role = Yii::app()->session['role'];
        if($role == WQ_ROLE_MANAGER){
            $manager_id = Yii::app()->session['manager_id'];
        }else{
            $manager_id = '';
        }
        $model = json_decode($store->getStoreInfo($merchant_id, $address, $keyword,$role,$manager_id), true);
        if ($model['status'] == ERROR_NONE) {
            if (isset($model['data']['list'])) {
                $list = $model['data']['list'];
            }
        } else {
            echo $model['errMsg'];
        }
        //print_r($pca->liveplaceCity);

        $this->render('storeInfo', array(
            'list' => $list,
            'pages' => $store->page,
            'pca' => $pca
        ));
    }

    /**
     * 添加门店
     */
// 	public function actionAddStore() {
// 		$pca = new Selector();
// 		$store = new StoreC();
// 		$model = $store->getAttributes();
// 		$model['area_code'] = '';
// 		$model['day'] = '0000000';
// 		$model['start'] = '9:00';
// 		$model['end'] = '17:00';
// 		$model['lngAndLat'] = '';
// 		if (isset($_POST['Store']) && !empty($_POST['Store'])) {
// 			$post = $_POST['Store'];
// 			//保存提交的数据
// 			$model['name'] = $post['name'];
// 			$model['number'] = $post['number'];
// 			$model['alipay_store_id'] = $post['alipay_store_id'];
// 			$model['alipay_seller_id'] = $post['alipay_seller_id'];
// 			$model['area_code'] = $post['area_code'];
// 			$model['telephone'] = $post['telephone'];
// 			$model['address'] = $post['address'];
// 			$model['lngAndLat'] = $post['lngAndLat'];
// 			$model['start'] = $post['start'];
// 			$model['end'] = $post['end'];
// 			$model['day'] = $post['day'];
// 			$model['image'] = isset($_POST['image']) ? $_POST['image'] : '';
// 			$model['introduction'] = $post['introduction'];
//            // $management_id = Yii::app()->session['management_id'];
//             $merchant_id = Yii::app()->session['merchant_id'];
// 			$flag = false;
// 			if (empty($post['name'])) {
// 				$flag = true;
// 				Yii::app()->user->setFlash('name_error', '请输入门店名称');
// 			}else
//             {
//                 $rs_name=$store->storeInfoValidate($merchant_id, 'name', $post['name']);
//                 if(!empty($rs_name))
//                 {
//                     $flag = true;
//                     Yii::app()->user->setFlash('name_error', $rs_name);
//                 }
//             }

// 			if (empty($post['number'])) {
// 				$flag = true;
// 				Yii::app()->user->setFlash('number_error', '请输入门店编号');
// 			}else
//             {
//                 $rs_number=$store->storeInfoValidate($merchant_id, 'number', $post['number']);
//                 if(!empty($rs_number))
//                 {
//                     $flag = true;
//                     Yii::app()->user->setFlash('number_error', $rs_number);
//                 }
//             }

// 			if (empty($post['area_code']) && empty($post['telephone'])) {
// 				$flag = true;
// 				Yii::app()->user->setFlash('tel_error', '请输入门店电话');
// 			}else {
// 				if (empty($post['telephone'])) {
// 					$flag = true;
// 					Yii::app()->user->setFlash('tel_error', '请输入门店电话');
// 				}else {
// 					if (!empty($post['area_code'])) {
// 						$tel = $post['area_code'].'-'.$post['telephone'];
//                         //正则表达式判断电话号码
//                         if (!preg_match("/^0\d{2,3}-\d{7,8}$/",$tel)) {
//                             $flag = true;
//                             Yii::app()->user->setFlash('tel_error', '请输入正确的联系电话');
//                         }

// 					}else {
// 						$tel = $post['telephone'];
//                         //正则表达式判断手机
//                         if (!preg_match("/^[1][34578][0-9]{9}$/",$tel)) {
//                             $flag = true;
//                             Yii::app()->user->setFlash('tel_error', '请输入正确的联系电话');
//                         }
// 					}
// 				}
// // 				if (!preg_match("/^(((d{3}))|(d{3}-))?((0d{2,3})|0d{2,3}-)?[1-9]d{6,8}$/",$tel) && !preg_match("/(?:13d{1}|15[03689])d{8}$/",$tel)) {
// // 					$flag = true;
// // 					Yii::app()->user->setFlash('tel_error', '请输入正确的联系电话');
// // 				}
// 			}
// 			if (empty($_POST['Selector']['liveplaceProvince'])) {
// 				$flag = true;
// 				Yii::app()->user->setFlash('pca_error', '请选择省市区');
// 			}else {
// 				$pca['liveplaceProvince'] = $_POST['Selector']['liveplaceProvince'];
// 				$pca['liveplaceCity'] = $_POST['Selector']['liveplaceCity'];
// 				$pca['liveplaceArea'] = $_POST['Selector']['liveplaceArea'];
// 			}
// 			if (empty($post['address'])) {
// 				$flag = true;
// 				Yii::app()->user->setFlash('address_error', '请输入详细地址');
// 			}
// 			if (empty($post['lngAndLat'])) {
// 				$flag = true;
// 				Yii::app()->user->setFlash('coor_error', '请输入地图坐标');
// 			}
//             //判断输入时间
//             if(!empty($post['start'])&&!empty($post['end']))
//             {
//                 $start=explode(":",$post['start']);
//                 $end=explode(":",$post['end']);
//                 //如果营业时间不在区间内
//                 if(intval($start[0])>intval($end[0]))
//                 {
//                     $flag = true;
//                     Yii::app()->user->setFlash('time_error', '请输入正确的营业时间');
//                 }
//                 else if(intval($start[0])==intval($end[0])&&intval($start[1])>=intval($end[1]))
//                 {
//                     $flag = true;
//                     Yii::app()->user->setFlash('time_error', '请输入正确的营业时间');
//                 }
//             }
// 			if (!$flag) {

// 				$name = $post['name'];
// 				$number = $post['number'];
// 				$alipay_store_id = empty($post['alipay_store_id']) ? '' : $post['alipay_store_id'];
// 				$alipay_seller_id = empty($post['alipay_seller_id']) ? '' : $post['alipay_seller_id'];
// 				$telephone = $tel;
// 				$address = $_POST['Selector']['liveplaceProvince'].','.$_POST['Selector']['liveplaceCity'].','.$_POST['Selector']['liveplaceArea'].','.$post['address'];
// 				$lngAndLat = explode(',', $post['lngAndLat']);
// 				$lng = $lngAndLat[0] + 0;
// 				$lat = $lngAndLat[1] + 0;
// 				$open_time = $post['day'].','.$post['start'].'-'.$post['end'];
// 				$logo = '';
// 				if(isset($_POST['image']) && !empty($_POST['image'])){
// 					$image = $_POST['image'];
// 				}else{
// 					$image = '';
// 				}
// 				$introduction = $post['introduction'];

// 				$ret = $store->addStore($merchant_id, $name, $number, $telephone, $address, $lng, $lat, $open_time, $logo, $image, $introduction, $alipay_store_id, $alipay_seller_id);
// 				$result = json_decode($ret,true);
// 				if ($result['status'] == ERROR_NONE) {
// 					$this->redirect('storeInfo');
// 				}else {
// 					$status = $result['status'];
// 					$msg = $result['errMsg'];
// 					Yii::app()->user->setFlash('name_error', $msg);
// 				}
// 			}
// 		}
// 		$this->render('addStore',array('model' => $model, 'pca' => $pca));
// 	}

    /**
     * 修改门店
     */
// 	public function actionEditStore() {
// 		if (isset($_GET['id']) && !empty($_GET['id'])) {
// 			$pca = new Selector();
// 			$model = array();
// 			$storeC = new StoreC();

// 			$ret = $storeC->getStoreDetails($_GET['id']);
// 			$result = json_decode($ret,true);
// 			if ($result['status'] == ERROR_NONE) {
// 				$data = $result['data'];
// 				$model['id'] = $data['id'];
// 				$model['name'] = $data['name'];
// 				$model['number'] = $data['number'];
// 				$model['alipay_store_id'] = $data['alipay_store_id'];
// 				$model['alipay_seller_id'] = $data['alipay_seller_id'];
// 				$str = strstr($data['telephone'], "-");
// 				if (empty($str)) {
// 					$model['area_code'] = '';
// 					$model['telephone'] = $data['telephone'];
// 				}else {
// 					$tmp = explode("-", $data['telephone']);
// 					$model['area_code'] = $tmp[0];
// 					$model['telephone'] = $tmp[1];
// 				}

// 				$add = explode(",", $data['address']);
// 				$pca->liveplaceProvince = $add[0];
// 				$pca->liveplaceCity = $add[1];
// 				$pca->liveplaceArea = $add[2];
// 				$model['address'] = $add[3];
// 				$model['lngAndLat'] = $data['lng'].','.$data['lat'];
// 				$open = explode(",", $data['open_time']);
// 				$model['day'] = $open[0];
// 				$time = explode("-", $open[1]);
// 				$model['start'] = $time[0];
// 				$model['end'] = $time[1];
// 				//$model['logo'] = $data['logo'];
// 				$model['image'] = $data['image'];
// 				$model['introduction'] = $data['introduction'];
// 			}else {
// 				$status = $result['status'];
// 				$msg = $result['errMsg'];
// 			}

// 			if (isset($_POST['Store']) && !empty($_POST['Store'])) {
// 				$post = $_POST['Store'];
// 				$flag = false;
// 				if (empty($post['name'])) {
// 					$flag = true;
// 					Yii::app()->user->setFlash('name_error', '请输入门店名称');
// 				}
// 				if (empty($post['number'])) {
// 					$flag = true;
// 					Yii::app()->user->setFlash('number_error', '请输入门店编号');
// 				}
// 				if (empty($post['area_code']) && empty($post['telephone'])) {
// 					$flag = true;
// 					Yii::app()->user->setFlash('tel_error', '请输入门店电话');
// 				}else {
// 					if (empty($post['telephone'])) {
// 						$flag = true;
// 						Yii::app()->user->setFlash('tel_error', '请输入门店电话');
// 					}else {
// 						if (!empty($post['area_code'])) {
// 							$tel = $post['area_code'].'-'.$post['telephone'];
// 						}else {
// 							$tel = $post['telephone'];
// 						}
// 					}
// 					// 				if (!preg_match("/^(((d{3}))|(d{3}-))?((0d{2,3})|0d{2,3}-)?[1-9]d{6,8}$/",$tel) && !preg_match("/(?:13d{1}|15[03689])d{8}$/",$tel)) {
// 					// 					$flag = true;
// 					// 					Yii::app()->user->setFlash('tel_error', '请输入正确的联系电话');
// 					// 				}
// 				}
// 				if (empty($_POST['Selector']['liveplaceProvince'])) {
// 					$flag = true;
// 					Yii::app()->user->setFlash('pca_error', '请选择省市区');
// 				}
// 				if (empty($post['address'])) {
// 					$flag = true;
// 					Yii::app()->user->setFlash('address_error', '请输入详细地址');
// 				}
// 				if (empty($post['lngAndLat'])) {
// 					$flag = true;
// 					Yii::app()->user->setFlash('coor_error', '请输入地图坐标');
// 				}
// 				if (!$flag) {
// 					$store = new StoreC();
// 					$id = $post['id'];
// 					$name = $post['name'];
// 					$number = $post['number'];
// 					$alipay_store_id = $post['alipay_store_id'];
// 					$alipay_seller_id = $post['alipay_seller_id'];
// 					$telephone = $tel;
// 					$address = $_POST['Selector']['liveplaceProvince'].','.$_POST['Selector']['liveplaceCity'].','.$_POST['Selector']['liveplaceArea'].','.$post['address'];
// 					$lngAndLat = explode(',', $post['lngAndLat']);
// 					$lng = $lngAndLat[0] + 0;
// 					$lat = $lngAndLat[1] + 0;
// 					$open_time = $post['day'].','.$post['start'].'-'.$post['end'];
// 					$logo = '';
// 					if(isset($_POST['image']) && !empty($_POST['image'])){
// 						$image = $_POST['image'];
// 					}else{
// 						$image = '';
// 					}
// 					$introduction = $post['introduction'];
// 					$ret = $storeC->editStore($id, $name, $number, $telephone, $address, $lng, $lat, $open_time, $logo, $image, $introduction, $alipay_store_id, $alipay_seller_id);
// 					$result = json_decode($ret, true);
// 					if ($result['status'] == ERROR_NONE) {
// 						Yii::app()->user->setFlash('success', '保存成功');
// 					}else {
// 						$status = $result['status'];
// 						$msg = $result['errMsg'];
// 						Yii::app()->user->setFlash('error', '保存失败');
// 					}
// 				}
// 			}

// 			$this->render('addStore', array('model' => $model, 'pca' => $pca));
// 		}else {
// 			$this->redirect('storeInfo');
// 		}
// 	}

    /**
     * 门店信息验证
     */
    public function actionStoreInfoValidate()
    {
        header('Content-type:text/html;charset=utf-8');
        $result = array();
        $name = isset($_GET['store_name']) ? $_GET['store_name'] : '';
        //$number = isset($_GET['store_number']) ? $_GET['store_number'] : '';

        $merchant_id = Yii::app()->session['merchant_id'];
        $store = new StoreC();
        $data = $store->storeInfoValidate($merchant_id, 'name', $name);
        if ($data != '') {
            $result['isValid'] = 'no';
            $result['msg'] = $data;
        } else {
            $result['isValid'] = 'yes';
        }

        echo json_encode($result);
    }

    /**
     * 删除门店
     */
    public function actionDeleteStore()
    {
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $storeC = new StoreC();
            $ret = json_decode($storeC->deleteStore($_GET['id']));
            if($ret -> status == ERROR_NONE){
                
            }
        }
        $this->redirect('storeInfo');
    }

    /**
     * 交易明细
     */
    public function actionOrderList()
    {
        $list = array();
        $arr = array();
        $merchant_id = Yii::app()->session['merchant_id'];

        //关键词
        $keyword = '';
        if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
            $keyword = $_GET['keyword'];
        }
        //操作员id
        $operator_id = '';
        if (isset($_GET['operator_id']) && !empty($_GET['operator_id'])) {
            $operator_id = $_GET['operator_id'];
        }
        //门店id
        $store_id = '';
        if (isset($_GET['store_id']) && !empty($_GET['store_id'])) {
            $store_id = $_GET['store_id'];
        }
        //支付类型
        $pay_channel = '';
        if (isset($_GET['pay_channel']) && !empty($_GET['pay_channel'])) {
            $pay_channel = $_GET['pay_channel'];
        }
        //订单状态
        $order_status = '';
        $pay_status = '';
        if (isset($_GET['order_status'])) {
            $tmp = $_GET['order_status'];
            $order_status = $tmp;
            //已付款
            if ($tmp == ORDER_STATUS_PAID) {
                $pay_status = ORDER_STATUS_PAID; //已付款
                $order_status = ORDER_STATUS_NORMAL; //正常
            }
            //待付款
            if ($tmp == ORDER_STATUS_UNPAID) {
                $pay_status = ORDER_STATUS_UNPAID; //待付款
                $order_status = ORDER_STATUS_NORMAL; // 正常
            }
        }
        //时间段查询
        $start_time = '';
        $end_time = '';
        if (isset($_GET['Time']) && !empty($_GET['Time'])) {
            $time = explode(" - ", $_GET['Time']);
            $start_time = $time[0];
            $end_time = $time[1];
        }
        //操作员列表
        $operator = array();

        //门店列表
        $storeC = new StoreC();
        $ret1 = $storeC->StoreList($merchant_id);
        $result1 = json_decode($ret1, true);

        $operatorC = new OperatorC();
        $store = array();
        if ($result1['status'] == ERROR_NONE) {
            if (isset($result1['data']['list'])) {
                foreach ($result1['data']['list'] as $k1 => $v1) {
                    $store[$v1['id']] = $v1['name'];
                    //操作员列表
                    $ret2 = $operatorC->getOperators($merchant_id, $v1['id']);
                    $result2 = json_decode($ret2, true);
//                    var_dump($result2);
                    if ($result2['status'] == ERROR_NONE) {
                        if (isset($result2['data']['list'])) {
                            foreach ($result2['data']['list'] as $k2 => $v2) {
                                $operator[$v2['id']] = $v2['name'] . ' (' . $v2['number'] . ')';
                            }
                        }
                    }
                }
            }
        }


        $order = new OrderC();
        $ret = $order->getOrderList($merchant_id, $operator_id, $store_id, $pay_channel, $order_status, $start_time, $end_time, $keyword, $pay_status);
        $result = json_decode($ret, true);
        if ($result['status'] == ERROR_NONE) {
            if (isset($result['data']['list'])) {
                $list = $result['data']['list'];
                if (isset($result['arr']['list'])) {
                    $arr = $result['arr']['list'];
                }
            }
        }

        foreach ($list as $k => $v) {
            if ($v['pay_status'] == ORDER_STATUS_PAID && $v['order_status'] == ORDER_STATUS_NORMAL) {
                $list[$k]['status'] = '已付款';
            }
            if ($v['pay_status'] == ORDER_STATUS_UNPAID) {
                $list[$k]['status'] = '待付款';
            }
            if ($v['order_status'] == ORDER_STATUS_REFUND) {
                $list[$k]['status'] = '已退款';
            }
            if ($v['order_status'] == ORDER_STATUS_PART_REFUND) {
                $list[$k]['status'] = '已部分退款';
            }
            if ($v['order_status'] == ORDER_STATUS_REVOKE) {
                $list[$k]['status'] = '已撤销';
            }
            if ($v['order_status'] == ORDER_STATUS_HANDLE_REFUND) {
                $list[$k]['status'] = '退款处理中';
            }
        }
        //支付渠道
        $channel = $GLOBALS['ORDER_PAY_CHANNEL'];
        //隐藏部分
        unset($channel[ORDER_PAY_CHANNEL_ALIPAY]);
        //订单状态
        $status = array();
        $status[ORDER_STATUS_PAID] = $GLOBALS['ORDER_STATUS_PAY'][ORDER_STATUS_PAID];
        $status[ORDER_STATUS_UNPAID] = $GLOBALS['ORDER_STATUS_PAY'][ORDER_STATUS_UNPAID];
        $status[ORDER_STATUS_REFUND] = $GLOBALS['ORDER_STATUS'][ORDER_STATUS_REFUND];
        $status[ORDER_STATUS_PART_REFUND] = $GLOBALS['ORDER_STATUS'][ORDER_STATUS_PART_REFUND];
        $status[ORDER_STATUS_REVOKE] = $GLOBALS['ORDER_STATUS'][ORDER_STATUS_REVOKE];
        $status[ORDER_STATUS_HANDLE_REFUND] = $GLOBALS['ORDER_STATUS'][ORDER_STATUS_HANDLE_REFUND];
        //$status[ORDER_STATUS_CANCEL] = $GLOBALS['ORDER_STATUS'][ORDER_STATUS_CANCEL];

        $this->render('orderList', array(
            'list' => $list,
            'operator' => $operator,
            'store' => $store,
            'channel' => $channel,
            'status' => $status,
            'pages' => $order->page,
            'arr' => $arr
        ));
    }

    /**
     *导出交易明细Excel表
     */
    public function actionExportExcel()
    {
        $storeC = new StoreC();
        $flag = "false";//false为没有搜索过
        //关键词
        $keyword = '';
        if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
            $keyword = $_GET['keyword'];
            $flag = "true";
        }
        //操作员id
        $operator_id = '';
        if (isset($_GET['operator_id']) && !empty($_GET['operator_id'])) {
            $operator_id = $_GET['operator_id'];
            $flag = "true";
        }
        //门店id
        $store_id = '';
        if (isset($_GET['store_id']) && !empty($_GET['store_id'])) {
            $store_id = $_GET['store_id'];
            $flag = "true";
        }
        //支付类型
        $pay_channel = '';
        if (isset($_GET['pay_channel']) && !empty($_GET['pay_channel'])) {
            $pay_channel = $_GET['pay_channel'];
            $flag = "true";
        }
        //订单状态
        $order_status = '';
        $pay_status = '';
        if (isset($_GET['order_status'])) {
            $flag = "true";
            $tmp = $_GET['order_status'];
            $order_status = $tmp;
            //已付款
            if ($tmp == ORDER_STATUS_PAID) {
                $pay_status = ORDER_STATUS_PAID; //已付款
                $order_status = ORDER_STATUS_NORMAL; //正常
            }
            //待付款
            if ($tmp == ORDER_STATUS_UNPAID) {
                $pay_status = ORDER_STATUS_UNPAID; //待付款
                $order_status = ORDER_STATUS_NORMAL; // 正常
            }
        }
        //时间段查询
        $start_time = '';
        $end_time = '';
        if (isset($_GET['Time']) && !empty($_GET['Time'])) {
            $flag = "true";
            $time = explode(" - ", $_GET['Time']);
            $start_time = $time[0];
            $end_time = $time[1];
        }

        $order = new OrderC();
        $list = array();
        $merchant_id = Yii::app()->session['merchant_id'];
        $ret = $order->OrderList($merchant_id, $operator_id, $store_id, $pay_channel, $order_status, $start_time, $end_time, $keyword, $pay_status);
        $result = json_decode($ret, true);
        if ($result['status'] == ERROR_NONE) {
            if (isset($result['data']['list'])) {
                $list = $result['data']['list'];
            }
        }

//        var_dump($flag);
        $storeC->ExportExcel($list, $flag);
//        var_dump($tmp);
    }


    /**
     * 选择门店，更改操作员
     */
    public function actionChooseStore()
    {
        $merchant_id = Yii::app()->session['merchant_id'];
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $data = array();
            $store_id = $_GET['id'];
            $operate = new OperatorC();
            $rs = json_decode($operate->ChooseStore($merchant_id, $store_id), true);
            if ($rs['status'] == ERROR_NONE) {
                $data = $rs['data'];
                echo json_encode($data);
            } else if ($rs['status'] == ERROR_NO_DATA) {
                echo json_encode('error');
            }
        } else {
            //门店列表
            $storeC = new StoreC();
            $ret1 = $storeC->getStoreList($merchant_id);
            $result1 = json_decode($ret1, true);
            $operator = array();
            $operatorC = new OperatorC();
            $store = array();
            if ($result1['status'] == ERROR_NONE) {
                if (isset($result1['data']['list'])) {
                    foreach ($result1['data']['list'] as $k1 => $v1) {
                        $store[$v1['id']] = $v1['name'];
                        //操作员列表
                        $ret2 = $operatorC->getOperators($merchant_id, $v1['id']);
                        $result2 = json_decode($ret2, true);
//                    var_dump($result2);
                        if ($result2['status'] == ERROR_NONE) {
                            if (isset($result2['data']['list'])) {
                                foreach ($result2['data']['list'] as $k2 => $v2) {
                                    $operator[$v2['id']] = $v2['name'] . ' (' . $v2['number'] . ')';
                                }
                            }
                        }
                    }
                }
                echo json_encode($operator);
            } else {
                echo json_encode('error');
            }
        }
    }

    /**
     * 验证门店编号
     */
    public function actionCheckStoreNumber()
    {
        $merchant_id = Yii::app()->session['merchant_id'];
        if (empty($_GET['number'])) {
            echo json_encode('error');
        } else {
            echo json_encode('success');
        }
    }

    /**
     * 验证营业时间
     */
    public function actionCheckStoreTime()
    {
        if (!empty($_GET['start']) && !empty($_GET['end'])) {
            $flag = false;
            $start = explode(":", $_GET['start']);
            $end = explode(":", $_GET['end']);
            //如果营业时间不在区间内
            if (intval($start[0]) > intval($end[0])) {
                $flag = true;
            } else if (intval($start[0]) == intval($end[0]) && intval($start[1]) >= intval($end[1])) {
                $flag = true;
            }

            if ($flag)
                echo json_encode('error');
            else
                echo json_encode('success');
        } else {
            echo json_encode('error');
        }
    }


    /*
     * 设置激活码
     * */
    public function actionSetActivationCode()
    {
        $merchant_id = Yii::app()->session['merchant_id'];
        $activationC = new ActivationC();
        $re = json_decode($activationC->getActivationCodeList($merchant_id));
        if ($re->status == ERROR_NONE) {
            $this->render('setActivationCode', array(
                'code' => $re->data,
                'pages' => $activationC->page
            ));
        }
    }

    /*
     * 创建激活码
     * */
    public function actionCreateActivationCode()
    {
        //激活次数
        $num = $_GET['num'];
        //激活码有效期
        $time = explode('-', $_GET['Time']);
        $start_time = $time[0] . ' 00:00:00';
        $end_time = $time[1] . ' 23:59:59';
        $merchant_id = Yii::app()->session['merchant_id'];
        $activationC = new ActivationC();
        $re = json_decode($activationC->createActivationCode($merchant_id, $num, $start_time, $end_time));
        if ($re->status == ERROR_NONE) {
            $this->redirect('SetActivationCode');
        } else {
            echo $re->status . ':' . $re->errMsg;
        }

    }

    /*
     * 激活码使失效
     * */
    public function actionInvalidCode()
    {
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $activationC = new ActivationC();
            $re = json_decode($activationC->invalidCode($_GET['id']));
            if ($re->status == ERROR_NONE) {
                $url = Yii::app()->createUrl('mCenter/Store/SetActivationCode');
                echo "<script>alert('使失效成功');window.location.href='$url'</script>";
            } else {
                $error = $re->status . ':' . $re->errMsg;
                $url = Yii::app()->createUrl('mCenter/Store/SetActivationCode');
                echo "<script>alert('" . $error . "');window.location.href='$url'</script>";
            }
        }
    }

    /*
     * 激活码删除
     * 
     * */
    public function actionDelectCode()
    {
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $activationC = new ActivationC();
            $re = json_decode($activationC->delectCode($_GET['id']));
            if ($re->status == ERROR_NONE) {
                $url = Yii::app()->createUrl('mCenter/Store/SetActivationCode');
                echo "<script>alert('删除成功');window.location.href='$url'</script>";
            } else {
                $error = $re->status . ':' . $re->errMsg;
                $url = Yii::app()->createUrl('mCenter/Store/SetActivationCode');
                echo "<script>alert('" . $error . "');window.location.href='$url'</script>";
            }
        }
    }

    /*
     * 门店设置收款账号(支付宝)
     * */
    public function actionSetAlipay()
    {
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = $_GET['id'];
            $storeC = new StoreC();
            $result = json_decode($storeC->getStoreAlipay($id));
            if ($result->status == ERROR_NONE) {
                $alipay = $result->data;
            } else {
                echo $result->status;
                exit;
            }
            $this->render('setAlipay', array(
                'alipay' => $alipay
            ));
        }

        if (!empty($_POST)) {
            $id = $_POST['id'];
            $vision = $_POST['busType'];
            $pid = '';
            $key = '';
            $appid = '';
            //1.0
            if ($vision == ALIPAY_API_VERSION_1) {
                $pid = $_POST['pid'];
                $key = $_POST['key'];
            } elseif ($vision == ALIPAY_API_VERSION_2) {//2.0
                $appid = $_POST['appid'];
            }

            $storeC = new StoreC();
            $re = json_decode($storeC->setStoreAlipay($id, $vision, $pid, $key, $appid));
            if ($re->status == ERROR_NONE) {
                $url = Yii::app()->createUrl('mCenter/store/SetAlipay', array('id' => $id));
                echo "<script>alert('修改成功');window.location.href='$url'</script>";
            } else {
                $url = Yii::app()->createUrl('mCenter/store/SetAlipay', array('id' => $id));
                echo "<script>alert('" . $re->errMsg . "');window.location.href='$url'</script>";
            }
        }

    }

    /*
         * 门店设置收款账号（微信）
        * */
    public function actionSetWechatPay()
    {
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = $_GET['id'];
            $storeC = new StoreC();
            $result = json_decode($storeC->getStoreWechatPay($id));

            if ($result->status == ERROR_NONE) {
                $wechat = $result->data;
                if (empty($wechat->wx_apiclient_cert) && empty($wechat->wx_apiclient_key)) {
                    $dir_name = uniqid() . rand(1000000, 9999999);
                } else {
                    if (!empty($wechat->wx_apiclient_cert)) {
                        $dir_name = $wechat->wx_apiclient_cert;
                    } elseif (!empty($wechat->wx_apiclient_key)) {
                        $dir_name = $wechat->wx_apiclient_key;
                    }
                }

            } else {
                echo $result->errMsg;
                exit;
            }
            $this->render('setWechatPay', array(
                'wechat' => $wechat,
                'dir_name' => $dir_name
            ));
        }

        if (!empty($_POST)) {
            $id = $_POST['id'];
            $vision = $_POST['busType'];
            $appid = '';
            $appsecret = '';
            $mchid = '';
            $api_key = '';
            $t_appid = '';
            $t_mchid = '';
            //普通商户
            if ($vision == WXPAY_MERCHANT_TYPE_SELF) {
                $appid = $_POST['appid'];
                $appsecret = $_POST['appsecret'];
                $mchid = $_POST['mchid'];
                $api_key = $_POST['api_key'];
            } elseif ($vision == WXPAY_MERCHANT_TYPE_AFFILIATE) {//特约商户
                $t_appid = $_POST['t_appid'];
                $t_mchid = $_POST['t_mchid'];
            }

            $storeC = new StoreC();
            $re = json_decode($storeC->setStoreWechatPay($id, $vision, $appid, $appsecret, $mchid, $api_key, $t_appid, $t_mchid));
            if ($re->status == ERROR_NONE) {
                $url = Yii::app()->createUrl('mCenter/store/SetWechatPay', array('id' => $id));
                echo "<script>alert('修改成功');window.location.href='$url'</script>";
            } else {
                $url = Yii::app()->createUrl('mCenter/store/SetWechatPay', array('id' => $id));
                echo "<script>alert('" . $re->errMsg . "');window.location.href='$url'</script>";
            }
        }

    }


    //设置支付宝开启和关闭收款账号
    public function actionSetAlipayUse()
    {
        if (isset($_POST['state']) && !empty($_POST['state']) && isset($_POST['id']) && !empty($_POST['id'])) {
            $state = $_POST['state'];
            $id = $_POST['id'];
            $storeC = new StoreC();
            $result = $storeC->setStorePayUse($id, $state, 'alipay');
            echo $result;
        }
    }

    //设置支付宝开启和关闭收款账号
    public function actionSetWechatUse()
    {
        if (isset($_POST['state']) && !empty($_POST['state']) && isset($_POST['id']) && !empty($_POST['id'])) {
            $state = $_POST['state'];
            $id = $_POST['id'];
            $storeC = new StoreC();
            $result = $storeC->setStorePayUse($id, $state, 'wechat');
            echo $result;
        }
    }

    //设置支付宝开启和关闭收款账号
    public function actionSetAlipayOpen()
    {
        if (isset($_POST['state']) && !empty($_POST['state']) && isset($_POST['id']) && !empty($_POST['id'])) {
            $state = $_POST['state'];
            $id = $_POST['id'];
            $storeC = new StoreC();
            $result = $storeC->setStorePayOpen($id, $state, 'alipay');
            echo $result;
        }
    }

    //设置支付宝开启和关闭收款账号
    public function actionSetWechatOpen()
    {
        if (isset($_POST['state']) && !empty($_POST['state']) && isset($_POST['id']) && !empty($_POST['id'])) {
            $state = $_POST['state'];
            $id = $_POST['id'];
            $storeC = new StoreC();
            $result = $storeC->setStorePayOpen($id, $state, 'wechat');
            echo $result;
        }
    }

    //微信上传证书
    public function actionUploadSuccess()
    {
        $store = new StoreC();
        if (isset($_POST['dir_name']) && !empty($_POST['dir_name']) && isset($_POST['type']) && !empty($_POST['type'])) {
            $id = $_POST['id'];
            $dir_name = $_POST['dir_name'];
            $type = $_POST['type'];
            $rs = json_decode($store->updateWechatCert($id, $dir_name, $type), true);
            if ($rs['status'] == ERROR_NONE) {
                echo ERROR_NONE;
            } else {
                echo ERROR_SAVE_FAIL;
            }
        }
    }
    /**
     * 门店分组列表
     */
    public function actionStoreGroupList()
    {
        $list = array();
        $merchant_id = Yii::app()->session['merchant_id'];
        $manageMentC = new ManageMentC();
        $result = $manageMentC->getStoreGroupList($merchant_id);
        $result = json_decode($result, true);
        if ($result['status'] == ERROR_NONE) {
            $list = $result['data']['list'];
        }
        $data = $list;
        $data = $this->getTopGroupCount($data);
        $this->render('storeGroupList', array(
            'list' => $data,
        ));
    }

    /**
     * 把下级分组的门店数加至顶级分组
     * @param unknown $list
     * @return unknown
     */
    private function getTopGroupCount($list)
    {
        $arr = array();
        foreach ($list as $k => $v) {
            if ($v['isHasXJ']) {
                foreach ($v['xjGroup'] as $k2) {
                    $v['countStore'] += $k2[intval($v['id'])]['countStore'];
                }
            }
            $arr[$k] = $v['countStore'];
        }
        $nn = count($list);
        for ($i = 0; $i < $nn; $i++) {
            if ($list[$i]['isHasXJ']) {
                $list[$i]['countStore'] = $arr[$i];
            }
        }
        return $list;
    }

    /**
     * 添加分组
     */
    public function actionAddGroup()
    {
        $merchant_id = Yii::app()->session['merchant_id'];
        $manageMentC = new ManageMentC();
        if (isset ($_POST) && !empty ($_POST)) {
            // 包含的门店
            $contain_store = '';
            if (isset ($_POST ['containStore']) && !empty ($_POST ['containStore'])) {
                $contain_store = $_POST ['containStore'];
            }

            // 分组名称
            $manageMent_name = '';
            if (isset ($_POST ['name']) && !empty ($_POST ['name'])) {
                $manageMent_name = $_POST ['name'];
            }

            // 分组类型（1.新建分组 2.添加到 ）
            $group_type = '';
            if (isset ($_POST ['groupType']) && !empty ($_POST ['groupType'])) {
                $group_type = $_POST ['groupType'];
            }

            // 上级分组
            $level_group = '';
            if (isset ($_POST ['gruop']) && !empty ($_POST ['gruop'])) {
                $level_group = $_POST ['gruop'];
            }

            $result = $manageMentC->addGroup($merchant_id, $contain_store, $manageMent_name, $group_type, $level_group);
            $result = json_decode($result, true);
            if ($result ['status'] == ERROR_NONE) {
                $this->redirect(array(
                    'storeGroupList'
                ));
            }
        }
        $groupList = $manageMentC->getFirstGroup($merchant_id);
        $this->render('addGroup', array('groupList' => $groupList));
    }


    /**
     * 编辑分组
     */
    public function actionEditGroup($id)
    {
        $merchant_id = Yii::app()->session['merchant_id'];
        $manageMentC = new ManageMentC();
        if (isset ($_POST) && !empty ($_POST)) {
            // 包含的门店
            $contain_store = '';
            if (isset ($_POST ['containStore']) && !empty ($_POST ['containStore'])) {
                $contain_store = $_POST ['containStore'];
            }

            // 分组名称
            $manageMent_name = '';
            if (isset ($_POST ['name']) && !empty ($_POST ['name'])) {
                $manageMent_name = $_POST ['name'];
            }

            // 分组类型（1.新建分组 2.添加到 ）
            $group_type = '';
            if (isset ($_POST ['groupType']) && !empty ($_POST ['groupType'])) {
                $group_type = $_POST ['groupType'];
            }

            // 上级分组
            $level_group = '';
            if (isset ($_POST ['gruop']) && !empty ($_POST ['gruop'])) {
                $level_group = $_POST ['gruop'];
            }

            $result = $manageMentC->editGroup($id, $merchant_id, $contain_store, $manageMent_name, $group_type, $level_group);
            $result = json_decode($result, true);
            if ($result ['status'] == ERROR_NONE) {
                $this->redirect(array('storeGroupList'));
            }
        }
        $model = array();
        $store_id_str = '';
        $res = $manageMentC->getGroupDetail($id, $merchant_id);
        $res = json_decode($res, true);
        if ($res ['status'] == ERROR_NONE) {
            $model = $res['data']['list'];
            if (!empty($model['store_name'])) {
                foreach ($model ['store_name'] as $key => $val) {
                    $store_id_str = $store_id_str . ',' . $key; //获取数据库已经添加过的门店id（字符串形式(,16)）
                }
            }
        }
        $groupList = $manageMentC->getFirstGroup($merchant_id);
        unset($groupList[$id]); //去除本身（一级分组）

//        $finance = new FinanceC();
//        $res = $finance->getSelfStore();
//        $self_arr = null;
//        if ($res) {
//            $self_arr = $res;
//            foreach ($self_arr as $v) {
//                foreach ($v['store_id'] as $a) {
//                    $wqstore_arr[] = $a;
//                }
//            }
//            foreach ($model['store_name'] as $v) {
//                if (in_array($v['id'], $wqstore_arr)) {
//                    $data[$v['id']] = $v;
//                }
//            }
//            $model['store_name'] = $data;
//        }

        //print_r($model);
        $this->render('editGroup', array(
            'store_id_str' => $store_id_str,
            'model' => $model,
            'groupList' => $groupList

        ));

    }

    /**
     * 删除分组
     */
    public function actionDelGroup($id)
    {
        $merchant_id = Yii::app()->session['merchant_id'];
        $manageMentC = new ManageMentC();
        $result = $manageMentC->delGroup($id, $merchant_id);
        $result = json_decode($result, true);
        if ($result ['status'] == ERROR_NONE) {
            $this->redirect(array('storeGroupList'));
        }
    }

    /**
     * 选则门店弹出框
     */
    public function actionAddStoreDialog()
    {
        $list = array();
        $key_word = '';
        if (isset($_GET['key_word'])) {
            $key_word = $_GET['key_word'];
        }

        $type = '';
        if (isset($_GET['type'])) {
            $type = $_GET['type'];
        }

        if (empty ($type)) { //编辑操作
            $arr_store = array(); // 已经选中的门店
            if (isset ($_GET ['arr_store'])) {
                $arr = json_decode($_GET ['arr_store'], true);
                //var_dump($arr);exit;
                foreach ($arr as $v) {
                    $arr_store[$v['id']] = $v['name'];
                }
            }
        } else { //添加操作
            $arr_store = array(); // 已经选中的门店
            if (isset ($_GET ['arr_store'])) {
                $arr = json_decode($_GET ['arr_store'], true);//var_dump($arr);exit;
                foreach ($arr as $v) {
                    $arr_store[$v['id']] = $v['name'];
                }
            }
        }

        $merchant_id = Yii::app()->session['merchant_id'];
        $cardCouponsC = new CardCouponsC();
        $result = $cardCouponsC->addStore($merchant_id, $key_word);
        $result = json_decode($result, true);
       // var_dump( $result);exit();
        if ($result['status'] == ERROR_NONE) {
            $list = $result['data']['list'];
        }
        $this->render('addStoreDialog', array(
            'list' => $list,
            'pages' => $cardCouponsC->page,
            'arr_store' => $arr_store,
            'type' => $type
        ));
    }

    /**
     * 弹出框选中的门店加载到主页面上
     */
    public function actionLoadStoreOnPage()
    {
        $store_id = '';
        if (isset($_POST['store_id'])) {
            $store_id = $_POST['store_id'];
        }

        $manageMentC = new ManageMentC();
        $result = $manageMentC->getStore($store_id);
        $result = json_decode($result, true);
        if ($result['status'] == ERROR_NONE) {
            echo json_encode($result['data']['list']);
        }
    }

    /**
     * 获取门店列表
     */
    public function actionGetStoreList()
    {
        $list = array();
        $merchant_id = Yii::app()->session['merchant_id'];
        $cardCouponsC = new CardCouponsC();
        $result = $cardCouponsC->getStoreList($merchant_id);
        $result = json_decode($result, true);
        
        if ($result['status'] == ERROR_NONE) {
            $list = $result['data']['list'];
        }
        echo json_encode($list);
    }

    /**
     * 删除已经选中的门店(去掉复选框勾选)
     */
    public function actionDelChooseStore()
    {
        $store_str = ''; //获取已经选中的门店id集
        $delData = ''; //要删除的门店id
        if (isset($_POST['store_str'])) {
            $store_str = $_POST['store_str'];
        }
        if (isset($_POST['delData'])) {
            $delData = $_POST['delData'];
        }

        $store_str_arr = explode(',', $store_str);
        //array_pop($store_str_arr);
        array_splice($store_str_arr, array_search($delData, $store_str_arr), 1); //删除指定元素  并重新排序索引
        echo json_encode($store_str_arr);
    }


    /**
     * 分组设置收款账号(支付宝)
     */
    public function actionAliPayMent()
    {
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = $_GET['id'];
            $manageMentC = new ManageMentC();
            $result = json_decode($manageMentC->getGroupAlipay($id));
            if ($result->status == ERROR_NONE) {
                $alipay = $result->data;
            } else {
                echo $result->status;
                exit;
            }
            $this->render('aliPayMent', array(
                'alipay' => $alipay
            ));
        }

        if (!empty($_POST)) {
            $id = $_POST['id'];
            $vision = $_POST['busType'];
            $pid = '';
            $key = '';
            $appid = '';
            //1.0
            if ($vision == ALIPAY_API_VERSION_1) {
                $pid = $_POST['pid'];
                $key = $_POST['key'];
            } elseif ($vision == ALIPAY_API_VERSION_2) {//2.0
                $appid = $_POST['appid'];
            }


            $manageMentC = new ManageMentC();
            $re = json_decode($manageMentC->setGroupAlipay($id, $vision, $pid, $key, $appid));
            if ($re->status == ERROR_NONE) {
                $url = Yii::app()->createUrl('mCenter/store/aliPayMent', array('id' => $id));
                echo "<script>alert('修改成功');window.location.href='$url'</script>";
            } else {
                $url = Yii::app()->createUrl('mCenter/store/aliPayMent', array('id' => $id));
                echo "<script>alert('" . $re->errMsg . "');window.location.href='$url'</script>";
            }
        }
    }

    /**
     * 分组设置收款账号(微信)
     */
    public function actionWxPayMent()
    {
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = $_GET['id'];
            $manageMentC = new ManageMentC();
            $result = json_decode($manageMentC->getGroupWechatPay($id));
            if ($result->status == ERROR_NONE) {
                $wechat = $result->data;
                if (empty($wechat->wx_apiclient_cert) && empty($wechat->wx_apiclient_key)) {
                    $dir_name = uniqid() . rand(1000000, 9999999);
                } else {
                    if (!empty($wechat->wx_apiclient_cert)) {
                        $dir_name = $wechat->wx_apiclient_cert;
                    } elseif (!empty($wechat->wx_apiclient_key)) {
                        $dir_name = $wechat->wx_apiclient_key;
                    }
                }
            } else {
                echo $result->status;
                exit;
            }
            $this->render('wxPayMent', array(
                'wechat' => $wechat,
                'dir_name' => $dir_name
            ));
        }
        if (!empty($_POST)) {
            $id = $_POST['id'];
            $vision = $_POST['busType'];
            $appid = '';
            $appsecret = '';
            $mchid = '';
            $api_key = '';
            $t_appid = '';
            $t_mchid = '';
            //普通商户
            if ($vision == WXPAY_MERCHANT_TYPE_SELF) {
                $appid = $_POST['appid'];
                $appsecret = $_POST['appsecret'];
                $mchid = $_POST['mchid'];
                $api_key = $_POST['api_key'];
            } elseif ($vision == WXPAY_MERCHANT_TYPE_AFFILIATE) {//特约商户
                $t_appid = $_POST['t_appid'];
                $t_mchid = $_POST['t_mchid'];
            }
            $manageMentC = new ManageMentC();
            $re = json_decode($manageMentC->setGroupWechatPay($id, $vision, $appid, $appsecret, $mchid, $api_key, $t_appid, $t_mchid));
            if ($re->status == ERROR_NONE) {
                $url = Yii::app()->createUrl('mCenter/store/wxPayMent', array('id' => $id));
                echo "<script>alert('修改成功');window.location.href='$url'</script>";
            } else {
                $url = Yii::app()->createUrl('mCenter/store/wxPayMent', array('id' => $id));
                echo "<script>alert('" . $re->errMsg . "');window.location.href='$url'</script>";
            }
        }
    }

    //设置支付宝开启和关闭收款账号
    public function actionSetManagementAlipayUse()
    {
        if (isset($_POST['state']) && !empty($_POST['state']) && isset($_POST['id']) && !empty($_POST['id'])) {
            $state = $_POST['state'];
            $id = $_POST['id'];
            $manageMentC = new ManageMentC();
            $result = $manageMentC->setGroupPayUse($id, $state, 'alipay');
            echo $result;
        }
    }

    //设置支付宝开启和关闭收款账号
    public function actionSetManagementWechatUse()
    {
        if (isset($_POST['state']) && !empty($_POST['state']) && isset($_POST['id']) && !empty($_POST['id'])) {
            $state = $_POST['state'];
            $id = $_POST['id'];
            $manageMentC = new ManageMentC();
            $result = $manageMentC->setGroupPayUse($id, $state, 'wechat');
            echo $result;
        }
    }

    //设置支付宝开启和关闭收款账号
    public function actionSetManagementAlipayOpen()
    {
        if (isset($_POST['state']) && !empty($_POST['state']) && isset($_POST['id']) && !empty($_POST['id'])) {
            $state = $_POST['state'];
            $id = $_POST['id'];
            $manageMentC = new ManageMentC();
            $result = $manageMentC->setGroupPayOpen($id, $state, 'alipay');
            echo $result;
        }
    }

    //设置支付宝开启和关闭收款账号
    public function actionSetManagementWechatOpen()
    {
        if (isset($_POST['state']) && !empty($_POST['state']) && isset($_POST['id']) && !empty($_POST['id'])) {
            $state = $_POST['state'];
            $id = $_POST['id'];
            $manageMentC = new ManageMentC();
            $result = $manageMentC->setGroupPayOpen($id, $state, 'wechat');
            echo $result;
        }
    }

    //微信上传证书
    public function actionUploadSuc()
    {
        $manageMentC = new ManageMentC();
        if (isset($_POST['dir_name']) && !empty($_POST['dir_name']) && isset($_POST['type']) && !empty($_POST['type'])) {
            $id = $_POST['id'];
            $dir_name = $_POST['dir_name'];
            $type = $_POST['type'];
            $rs = json_decode($manageMentC->updateWechatCert($id, $dir_name, $type), true);
            if ($rs['status'] == ERROR_NONE) {
                echo ERROR_NONE;
            } else {
                echo ERROR_SAVE_FAIL;
            }
        }
    }

    /**************************************新规代码************************************************/
    //添加门店 createBy gulei 2016-1-18
    public function actionAddStore()
    {
        $merchant_id = Yii::app()->session['merchant_id'];
        $model['lngAndLat'] = '';
        $store = new StoreC();
        $model = $store->getAttributes();
        $model['area_code'] = '';
        $model['day'] = '0000000';
        $model['start'] = '9:00';
        $model['end'] = '17:00';
        $model['lngAndLat'] = '';
        $model['sync_type'] = '';
        $model['category'] = '';
        $model['province'] = '';
        $model['city'] = '';
        $model['area'] = '';
        $model['detail_address'] = '';
        $audit_image = '';


        $storeC = new StoreC();
        $model['alipay_store_id'] = $storeC->createNewAlipayStoreId();

        $pList = array();
        $cList = array();
        $pid = '';
        $managementC = new ManageMentC();
        $result = json_decode($managementC->getMerchantManagement($merchant_id));
        if ($result->status == STATUS_SUCCESS) {
            $pList = $result->data;
        }
        //设置省市区数据源
        $province = json_decode($store->getProvince());
        $province = $province->data;
        $city = array();
        $area = array();

        if (isset($_POST['Store']) && !empty($_POST['Store'])) {
            $post = $_POST['Store'];
            //保存提交的数据
            $model['name'] = $post['name'];
            $model['number'] = $post['number'];
            $model['alipay_store_id'] = $post['alipay_store_id'];
            $model['area_code'] = $post['area_code'];
            $model['telephone'] = $post['telephone'];
            $model['province'] = $post['province'];
            $model['city'] = $post['city'];
            $model['area'] = $post['area'];
            $model['address'] = $post['address'];
            $model['detail_address'] = $post['detail_address'];
            $model['lngAndLat'] = $post['lngAndLat'];
            $model['start'] = $post['start'];
            $model['end'] = $post['end'];
            $model['day'] = $post['day'];
            $model['auth_letter'] = $post['auth_letter'];
            $model['branch_name'] = $post['branch_name'];
            $model['brand'] = $post['brand'];
            $model['business_license'] = $post['business_license'];
            $model['licence_code'] = $post['licence_code'];
            $model['licence_name'] = $post['licence_name'];
            $model['business_certificate'] = $post['business_certificate'];
            $model['business_certificate_expires'] = $post['business_certificate_expires'];
            $model['category'] = $post['category'];
            $model['category_id'] = $post['category_id'];
            $model['first_img'] = $post['first_img'];
            $model['brand_logo'] = $post['brand_logo'];
            $model['management_id'] = $post['management_id'];
            $model['per_capita'] = $post['per_capita'];
            $model['phone_num'] = $post['phone_num'];
            $model['sync_type'] = $post['sync_type'];
            $image = '';
            $audit_image = isset($_POST['image_mdsjt']) ? $_POST['image_mdsjt'] : array();
            foreach ($audit_image as $img) {
                if (empty($img)) {
                    continue;
                }
                $image .= $img . ',';
            }
            $image = trim($image, ',');

            //设置市列表
            if (!empty($post['province'])) {
                $city = json_decode($store->getCity($post['province']));
                $city = $city->data;
            }
            //设置区列表
            if (!empty($post['city'])) {
                $area = json_decode($store->getArea($post['city']));
                $area = $area->data;
            }

            //设置分组列表
            if (!empty($post['management_id'])) {
                $mid = $post['management_id'];
                $ret = $managementC->getRelateManagement($mid);
                $result = json_decode($ret);
                if ($result->status == STATUS_SUCCESS) {
                    $pList = $result->parent;
                    $cList = $result->child;
                    $pid = $result->pid;
                }
            }

            // $management_id = Yii::app()->session['management_id'];
            $flag = false;

            if (!empty($post['area_code'])) {
                $tel = $post['area_code'] . '-' . $post['telephone'];
            } else {
                $tel = $post['telephone'];
            }
            if (empty($post['name'])) {
                $flag = true;
                Yii::app()->user->setFlash('name_error', '请输入门店名称');
            } else {
                $rs_name = $store->validateStoreName($merchant_id, $post['name'], $post['branch_name']);
                if (!empty($rs_name)) {
                    $flag = true;
                    Yii::app()->user->setFlash('name_error', $rs_name);
                }
            }

            if (empty($post['number'])) {
                $flag = true;
                Yii::app()->user->setFlash('number_error', '请输入门店编号');
            } else {
                $rs_number = $store->storeInfoValidate($merchant_id, 'number', $post['number']);
                if (!empty($rs_number)) {
                    $flag = true;
                    Yii::app()->user->setFlash('number_error', $rs_number);
                }
            }

            if (empty($post['area_code']) && empty($post['telephone'])) {
                $flag = true;
                Yii::app()->user->setFlash('tel_error', '请输入门店电话');
            } else {
                if (empty($post['telephone'])) {
                    $flag = true;
                    Yii::app()->user->setFlash('tel_error', '请输入门店电话');
                } else {
                    if (!empty($post['area_code'])) {
                        $tel = $post['area_code'] . '-' . $post['telephone'];
                        //正则表达式判断电话号码
                        if (!preg_match("/^0\d{2,3}-\d{7,8}$/", $tel)) {
                            $flag = true;
                            Yii::app()->user->setFlash('tel_error', '请输入正确的联系电话');
                        }

                    } else {
                        $tel = $post['telephone'];
                        //正则表达式判断手机
                        if (!preg_match("/^[1][34578][0-9]{9}$/", $tel)) {
                            $flag = true;
                            Yii::app()->user->setFlash('tel_error', '请输入正确的联系电话');
                        }
                    }
                }
            }
            if (empty($post['province']) || empty($post['city']) || empty($post['area'])) {
                $flag = true;
                Yii::app()->user->setFlash('pca_error', '请选择省市区');
            }
            if (empty($post['address'])) {
                $flag = true;
                Yii::app()->user->setFlash('address_error', '请输入详细地址');
            }
            if (empty($post['lngAndLat'])) {
                $flag = true;
                Yii::app()->user->setFlash('coor_error', '请输入地图坐标');
            }
            //判断输入时间
            if (!empty($post['start']) && !empty($post['end'])) {
                $start = explode(":", $post['start']);
                $end = explode(":", $post['end']);
                //如果营业时间不在区间内
                if (intval($start[0]) > intval($end[0])) {
                    $flag = true;
                    Yii::app()->user->setFlash('time_error', '请输入正确的营业时间');
                } else if (intval($start[0]) == intval($end[0]) && intval($start[1]) >= intval($end[1])) {
                    $flag = true;
                    Yii::app()->user->setFlash('time_error', '请输入正确的营业时间');
                }
            }
            //是否关联到口碑
            if ($post['sync_type'] == STORE_ALIPAY_SYNC_TYPE_RELATION) {
                if (empty($post['alipay_store_id'])) {
                    $flag = true;
                    Yii::app()->user->setFlash('store_id_error', '支付宝门店编号错误');
                }
            }
            //是否同步到口碑
            if ($post['sync_type'] == STORE_ALIPAY_SYNC_TYPE_SYNC) {
                if (empty($post['alipay_store_id'])) {
                    $flag = true;
                    Yii::app()->user->setFlash('store_id_error', '支付宝门店编号错误');
                }
                if (empty($post['first_img'])) {
                    $flag = true;
                    Yii::app()->user->setFlash('main_img_error', '请上传门店首图');
                }
                if (empty($image)) {
                    $flag = true;
                    Yii::app()->user->setFlash('audit_img_error', '请上传门店实景图');
                } else {
                    $img_arr = explode(',', $image);
                    if (count($img_arr) < 3) {
                        $flag = true;
                        Yii::app()->user->setFlash('audit_img_error', '请上传至少3张门店实景图');
                    }
                }
                if (empty($post['category']) || empty($post['category_id'])) {
                    $flag = true;
                    Yii::app()->user->setFlash('category_error', '请选择品类');
                }
            }


            if (!$flag) {

                $name = $post['name'];
                $number = $post['number'];
                $alipay_store_id = $post['alipay_store_id'];
                $telephone = $tel;
                $address = $post['province'] . ',' . $post['city'] . ',' . $post['area'] . ',' . $post['address'];
                $detail_address = $post['detail_address'] . ',' . $post['address'];
                $lngAndLat = explode(',', $post['lngAndLat']);
                $lng = $lngAndLat[0] + 0;
                $lat = $lngAndLat[1] + 0;
                $open_time = $post['day'] . ',' . $post['start'] . '-' . $post['end'];
                $brand_logo = $post['brand_logo'];

                $auth_letter = $post['auth_letter'];
                $branch_name = $post['branch_name'];
                $brand = $post['brand'];
                $business_license = $post['business_license'];
                $licence_code = $post['licence_code'];
                $licence_name = $post['licence_name'];
                $certificate = $post['business_certificate'];
                $certificate_expires = $post['business_certificate_expires'];
                $category = $post['category'];
                $category_id = $post['category_id'];
                $first_img = $post['first_img'];
                $management_id = $post['management_id'];
                $per_capita = $post['per_capita'];
                $phone_num = $post['phone_num'];
                $sync_type = $post['sync_type'];

                $ret = $store->addStore(
                    $merchant_id,
                    $sync_type,
                    $name,
                    $number,
                    $telephone,
                    $address,
                    $detail_address,
                    $lng,
                    $lat,
                    $open_time,
                    $brand_logo,
                    $image,
                    $alipay_store_id,
                    $auth_letter,
                    $branch_name,
                    $brand,
                    $business_license,
                    $licence_code,
                    $licence_name,
                    $certificate,
                    $certificate_expires,
                    $category,
                    $category_id,
                    $first_img,
                    $management_id,
                    $per_capita,
                    $phone_num
                );

                $result = json_decode($ret, true);
                if ($result['status'] == ERROR_NONE) {
                    Yii::app()->user->setFlash('success_msg', '门店创建成功');
                    //如果是管理员则添加到该管理员权限下
                    if(Yii::app()->session['role'] == WQ_ROLE_MANAGER){
                        $manage_id = Yii::app()->session['manager_id'];
                        $store_id = $result['store_id'];
                        $adminC = new AdminC();
                        $re = json_decode($adminC -> AddManageStore($manage_id, $store_id));
                        if($re -> status == ERROR_NONE){
                            
                        }
                    }
                    $this->redirect('storeInfo');
                } else {
                    //报错信息
                    Yii::app()->user->setFlash('other_error', htmlspecialchars($result['errMsg']));
                }
            }
        }
        $this->render('addStore', array(
            'pList' => $pList,
            'cList' => $cList,
            'pid' => $pid,
            'model' => $model,
            'province' => $province,
            'city' => $city,
            'area' => $area,
            'audit_image' => $audit_image
        ));
    }

    //ajax 获取下级门店分组
    public function actionGetSubManagement()
    {
        if (isset($_GET['management_id']) && !empty($_GET['management_id'])) {
            $managementC = new ManageMentC();
            $result = $managementC->getMerchantManagement('', $_GET['management_id']);
            echo $result;
        }
    }

    /**
     * 验证商户授权
     */
    public function actionCheckAuthToken()
    {
        $authed = 'no';
        $merchant_id = Yii::app()->session['merchant_id'];
        $merchant = new MerchantC();
        $ret = $merchant->checkMerchantAuth($merchant_id);
        $result = json_decode($ret, true);
        if ($result['status'] == STATUS_SUCCESS) {
            $auth = $result['data']['if_auth'];
            if ($auth) {
                $authed = 'yes';
            }
        }
        echo $authed;
    }

    /**
     * 上传图片
     */
    public function actionUploadImage() {
    	$data = array();
    	$data['error'] = 'failure';
    	
        if (isset($_GET['img_path']) && !empty($_GET['img_path'])) {
            $path = $_GET['img_path'];
            $merchant_id = Yii::app()->session['merchant_id'];
            
            //调用支付宝上传图片接口
            $storeC = new StoreC();
            $result = $storeC->uploadImage($merchant_id, $path);
            if ($result['status'] != ERROR_NONE) {
            	$msg = $result['errMsg'];
            	$data['errMsg'] = $msg;
            	exit(json_encode($data));
            }
            $image_id = $result['image_id']; //图片在商家中心的唯一标识
            $data['image_id'] = $image_id;
            $data['error'] = 'success';
        }else {
        	$data['errMsg'] = '无效的图片路径';
        }
        
        exit(json_encode($data));
    }
    
    /**
     * 获取商家中心图片
     */
    public function actionShowImage() {
    	if (isset($_GET['image_id']) && !empty($_GET['image_id'])) {
    		$image_id = $_GET['image_id'];
    		$url = 'http://dl.django.t.taobao.com/rest/1.0/image?fileIds='.$image_id;
    		
    		//口碑后台的图片访问地址限制了盗链，需要先用curl请求图片内容并显示出来
    		$curl = curl_init();
    		curl_setopt($curl, CURLOPT_URL, $url);
    		curl_setopt($curl, CURLOPT_REFERER, '');
    		curl_setopt($curl, CURLOPT_USERAGENT, 'Baiduspider');
    		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    		$result = curl_exec($curl);
    		header('Content-type: image/JPEG');
    		echo $result;
    	}
    }

    //获取市
    public function actionGetCity()
    {
        if (isset($_POST['code']) && !empty($_POST['code'])) {
            $storeC = new StoreC();
            $city = json_decode($storeC->getCity($_POST['code']));
            $c = array();
            if ($city->status == ERROR_NONE) {
                foreach ($city->data as $k => $v) {
                    $c[$k] = $v->name . ',' . $v->code;
                }
            }
            echo json_encode($c);
        }
    }


    //获取区
    public function actionGetArea()
    {
        if (isset($_POST['code']) && !empty($_POST['code'])) {
            $storeC = new StoreC();
            $area = json_decode($storeC->getArea($_POST['code']));
            $a = array();
            if ($area->status == ERROR_NONE) {
                foreach ($area->data as $k => $v) {
                    $a[$k] = $v->name . ',' . $v->code;
                }
            }
            echo json_encode($a);
        }
    }

    //编辑门店
    public function actionEditStore()
    {
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $store_id = $_GET['id'];
            $merchant_id = Yii::app()->session['merchant_id'];

            $model = array();
            $store = new StoreC();

            //设置默认分组数据源
            $pList = array();
            $cList = array();
            $pid = '';
            $managementC = new ManageMentC();
            $result = json_decode($managementC->getMerchantManagement($merchant_id));
            if ($result->status == STATUS_SUCCESS) {
                $pList = $result->data;
            }
            //设置默认省市区数据源
            $province = json_decode($store->getProvince());
            $province = $province->data;
            $city = array();
            $area = array();

            $ret = $store->getStoreDetails($store_id);
            $result = json_decode($ret, true);
            if ($result['status'] == ERROR_NONE) {
                $data = $result['data'];
                $model['name'] = $data['main_name'];
                $model['number'] = $data['number'];
                $model['alipay_store_id'] = $data['alipay_store_id'];
                $str = strstr($data['telephone'], "-");
                if (empty($str)) {
                    $model['area_code'] = '';
                    $model['telephone'] = $data['telephone'];
                } else {
                    $tmp = explode("-", $data['telephone']);
                    $model['area_code'] = $tmp[0];
                    $model['telephone'] = $tmp[1];
                }
                $address_code_arr = explode(',', $data['address_code']);
                $model['province'] = isset($address_code_arr[0]) ? $address_code_arr[0] : '';
                $model['city'] = isset($address_code_arr[1]) ? $address_code_arr[1] : '';
                $model['area'] = isset($address_code_arr[2]) ? $address_code_arr[2] : '';
                $model['address'] = isset($address_code_arr[3]) ? $address_code_arr[3] : '';
                $model['detail_address'] = '';
                $detail_address_arr = explode(",", $data['address']);
                for ($i = 0; $i < count($detail_address_arr); $i++) {
                    if (count($detail_address_arr) <= 0 || $i >= 3) {
                        break;
                    }
                    $model['detail_address'] .= $detail_address_arr[$i] . ',';
                }
                $model['detail_address'] = trim($model['detail_address'], ',');
                $model['lngAndLat'] = $data['lng'] . ',' . $data['lat'];
                $open = explode(",", $data['open_time']);
                $model['day'] = $open[0];
                $time = explode("-", $open[1]);
                $model['start'] = $time[0];
                $model['end'] = $time[1];

                $model['auth_letter'] = $data['auth_letter'];
                $model['branch_name'] = $data['branch_name'];
                $model['brand'] = $data['brand'];
                $model['business_license'] = $data['business_license'];
                $model['licence_code'] = $data['licence_code'];
                $model['licence_name'] = $data['licence_name'];
                $model['business_certificate'] = $data['business_certificate'];
                $model['business_certificate_expires'] = $data['business_certificate_expires'];
                $model['category'] = $data['category'];
                $model['category_id'] = $data['category_id'];
                $model['first_img'] = $data['first_img'];
                $model['brand_logo'] = $data['brand_logo'];
                $model['management_id'] = $data['management_id'];
                $model['per_capita'] = $data['per_capita'];
                $model['phone_num'] = $data['phone_num'];
                $model['sync_type'] = $data['alipay_sync_type'];
                $model['sync_time'] = $data['alipay_sync_time'];
                $model['verify_status'] = $data['alipay_sync_verify_status'];
                $model['audit_desc'] = $data['audit_desc'];

                //如果该门店没有支付宝门店编号，就使用生成的门店编号
                if (!empty($data['alipay_store_id'])) {
                    $model['alipay_store_id'] = $data['alipay_store_id'];
                } else {
                    $storeC = new StoreC();
                    $model['alipay_store_id'] = $storeC->createNewAlipayStoreId();
                }


                //设置市列表
                if (!empty($model['province'])) {
                    $city = json_decode($store->getCity($model['province']));
                    $city = $city->data;
                }
                //设置区列表
                if (!empty($model['city'])) {
                    $area = json_decode($store->getArea($model['city']));
                    $area = $area->data;
                }

                //设置分组列表
                if (!empty($model['management_id'])) {
                    $mid = $model['management_id'];
                    $ret = $managementC->getRelateManagement($mid);
                    $result = json_decode($ret);
                    if ($result->status == STATUS_SUCCESS) {
                        $pList = $result->parent;
                        $cList = $result->child;
                        $pid = $result->pid;
                    }
                }

                //设置审核实景图
                $image_arr = array();
                if (!empty($data['image'])) {
                    $image_arr = explode(',', $data['image']);
                }
            }

            if (isset($_POST['Store']) && !empty($_POST['Store'])) {
                $post = $_POST['Store'];
                //保存提交的数据
                /*
                $model['name'] = $post['name'];
                $model['number'] = $post['number'];
                $model['alipay_store_id'] = $post['alipay_store_id'];
                $model['area_code'] = $post['area_code'];
                $model['telephone'] = $post['telephone'];
                $model['province'] = $post['province'];
                $model['city'] = $post['city'];
                $model['area'] = $post['area'];
                $model['address'] = $post['address'];
                $model['detail_address'] = $post['detail_address'];
                $model['lngAndLat'] = $post['lngAndLat'];
                $model['start'] = $post['start'];
                $model['end'] = $post['end'];
                $model['day'] = $post['day'];
                $model['auth_letter'] = $post['auth_letter'];
                $model['branch_name'] = $post['branch_name'];
                $model['brand'] = $post['brand'];
                $model['business_license'] = $post['business_license'];
                $model['licence_code'] = $post['licence_code'];
                $model['licence_name'] = $post['licence_name'];
                $model['business_certificate'] = $post['business_certificate'];
                $model['business_certificate_expires'] = $post['business_certificate_expires'];
                $model['category'] = $post['category'];
                $model['category_id'] = $post['category_id'];
                $model['first_img'] = $post['first_img'];
                $model['brand_logo'] = $post['brand_logo'];
                $model['management_id'] = $post['management_id'];
                $model['per_capita'] = $post['per_capita'];
                $model['phone_num'] = $post['phone_num'];
                $model['sync_type'] = $post['sync_type'];
                */
                $image = '';
                $audit_image = isset($_POST['image_mdsjt']) ? $_POST['image_mdsjt'] : array();
                foreach ($audit_image as $img) {
                    if (empty($img)) {
                        continue;
                    }
                    $image .= $img . ',';
                }
                $image = trim($image, ',');


                // $management_id = Yii::app()->session['management_id'];
                $flag = false;

                if (!empty($post['area_code'])) {
                    $tel = $post['area_code'] . '-' . $post['telephone'];
                } else {
                    $tel = $post['telephone'];
                }
                if (empty($post['name'])) {
                    $flag = true;
                    Yii::app()->user->setFlash('name_error', '请输入门店名称');
                }

                if (empty($post['number'])) {
                    $flag = true;
                    Yii::app()->user->setFlash('number_error', '请输入门店编号');
                }

                if (empty($post['area_code']) && empty($post['telephone'])) {
                    $flag = true;
                    Yii::app()->user->setFlash('tel_error', '请输入门店电话');
                } else {
                    if (empty($post['telephone'])) {
                        $flag = true;
                        Yii::app()->user->setFlash('tel_error', '请输入门店电话');
                    } else {
                        if (!empty($post['area_code'])) {
                            $tel = $post['area_code'] . '-' . $post['telephone'];
                            //正则表达式判断电话号码
                            if (!preg_match("/^0\d{2,3}-\d{7,8}$/", $tel)) {
                                $flag = true;
                                Yii::app()->user->setFlash('tel_error', '请输入正确的联系电话');
                            }

                        } else {
                            $tel = $post['telephone'];
                            //正则表达式判断手机
                            if (!preg_match("/^[1][34578][0-9]{9}$/", $tel)) {
                                $flag = true;
                                Yii::app()->user->setFlash('tel_error', '请输入正确的联系电话');
                            }
                        }
                    }
                }
                if (empty($post['province']) || empty($post['city']) || empty($post['area'])) {
                    $flag = true;
                    Yii::app()->user->setFlash('pca_error', '请选择省市区');
                }
                if (empty($post['address'])) {
                    $flag = true;
                    Yii::app()->user->setFlash('address_error', '请输入详细地址');
                }
                if (empty($post['lngAndLat'])) {
                    $flag = true;
                    Yii::app()->user->setFlash('coor_error', '请输入地图坐标');
                }
                //判断输入时间
                if (!empty($post['start']) && !empty($post['end'])) {
                    $start = explode(":", $post['start']);
                    $end = explode(":", $post['end']);
                    //如果营业时间不在区间内
                    if (intval($start[0]) > intval($end[0])) {
                        $flag = true;
                        Yii::app()->user->setFlash('time_error', '请输入正确的营业时间');
                    } else if (intval($start[0]) == intval($end[0]) && intval($start[1]) >= intval($end[1])) {
                        $flag = true;
                        Yii::app()->user->setFlash('time_error', '请输入正确的营业时间');
                    }
                }
                //是否同步到口碑
                if ($model['sync_type'] == STORE_ALIPAY_SYNC_TYPE_SYNC) {
                    if (empty($post['first_img'])) {
                        $flag = true;
                        Yii::app()->user->setFlash('main_img_error', '请上传门店首图');
                    }
                    if (empty($image)) {
                        $flag = true;
                        Yii::app()->user->setFlash('audit_img_error', '请上传门店实景图');
                    } else {
                        $img_arr = explode(',', $image);
                        if (count($img_arr) < 3) {
                            $flag = true;
                            Yii::app()->user->setFlash('audit_img_error', '请上传至少3张门店实景图');
                        }
                    }
                    if (empty($post['category']) || empty($post['category_id'])) {
                        $flag = true;
                        Yii::app()->user->setFlash('category_error', '请选择品类');
                    }
                }


                if (!$flag) {

                    $name = $post['name'];
                    $number = $post['number'];
                    $alipay_store_id = $post['alipay_store_id'];
                    $telephone = $tel;
                    $address = $post['province'] . ',' . $post['city'] . ',' . $post['area'] . ',' . $post['address'];
                    $detail_address = $post['detail_address'] . ',' . $post['address'];
                    $lngAndLat = explode(',', $post['lngAndLat']);
                    $lng = $lngAndLat[0] + 0;
                    $lat = $lngAndLat[1] + 0;
                    $open_time = $post['day'] . ',' . $post['start'] . '-' . $post['end'];
                    $brand_logo = $post['brand_logo'];

                    $auth_letter = $post['auth_letter'];
                    $branch_name = $post['branch_name'];
                    $brand = $post['brand'];
                    $business_license = $post['business_license'];
                    $licence_code = $post['licence_code'];
                    $licence_name = $post['licence_name'];
                    $certificate = $post['business_certificate'];
                    $certificate_expires = $post['business_certificate_expires'];
                    $category = $post['category'];
                    $category_id = $post['category_id'];
                    $first_img = $post['first_img'];
                    $management_id = $post['management_id'];
                    $per_capita = $post['per_capita'];
                    $phone_num = $post['phone_num'];
                    $sync_type = $post['sync_type'];

                    $ret = $store->editStore(
                        $store_id,
                        $sync_type,
                        $name,
                        $number,
                        $telephone,
                        $address,
                        $detail_address,
                        $lng,
                        $lat,
                        $open_time,
                        $brand_logo,
                        $image,
                        $alipay_store_id,
                        $auth_letter,
                        $branch_name,
                        $brand,
                        $business_license,
                        $licence_code,
                        $licence_name,
                        $certificate,
                        $certificate_expires,
                        $category,
                        $category_id,
                        $first_img,
                        $management_id,
                        $per_capita,
                        $phone_num
                    );

                    $result = json_decode($ret, true);
                    if ($result['status'] == ERROR_NONE) {
                        Yii::app()->user->setFlash('success_msg', '门店修改成功');
                        $this->redirect('storeInfo');
                    } else {
                        //报错信息
                        Yii::app()->user->setFlash('other_error', htmlspecialchars($result['errMsg']));
                    }
                }
            }
            $this->render('editStore', array(
                'pList' => $pList,
                'cList' => $cList,
                'pid' => $pid,
                'model' => $model,
                'province' => $province,
                'city' => $city,
                'area' => $area,
                'audit_image' => $image_arr
            ));
        } else {
            $this->redirect('storeInfo');
        }

    }

    /**
     * 支付二维码图片
     */
    public function actionHybridQrcode()
    {
        Yii::import('application.extensions.utility.*');
        require_once "QRcodeCreator.php";
        //Header("Content-type: image/png");

        $data = array('error' => 'failure');

        $store_id = '';
        if (isset($_GET['sid'])) {
            $store_id = $_GET['sid'];
        }

        $string = '';
        if (isset($_GET['main'])) {
            $string = $_GET['main'];
        }

        $branch_string = '';
        if (isset($_GET['branch'])) {
            $branch_string = $_GET['branch'];
            if (!empty($branch_string)) {
                $branch_string = '（' . $branch_string . '）';
            }
        }

        if (empty($store_id)) {
            $data['errMsg'] = '错误的请求';
            echo json_encode($data);
            exit();
        }
        $url = PAY_QRCODE_URL . '?store=' . $store_id;

        //$logo = Yii::app()->basePath . '/../' . GJ_STATIC_IMAGES . 'qrcode_logo.png';
        $logo = false;
        $bg_path = Yii::app()->basePath . '/..' . GJ_STATIC_IMAGES . 'qrcode_bg.jpg';

        $img_name = QRcodeCreator::createWqQrcode($url, $string, $branch_string, $bg_path, $logo);

        $tmp = QRCODE_IMG_URL . basename($img_name, '.png').'_view.jpg';
        $data['error'] = 'success';
        $data['img_url'] = $tmp;
        $data['download_url'] = Yii::app()->createUrl('mCenter/store/download', array('img_name' => $img_name));

        echo json_encode($data);
    }

    /**
     * 支付宝支付二维码图片
     */
    public function actionAlipayQrcode()
    {
    	Yii::import('application.extensions.utility.*');
    	require_once "QRcodeCreator.php";
    	//Header("Content-type: image/png");
    	
    	$data = array('error' => 'failure');
    	
    	$store_id = '';
    	if (isset($_GET['sid'])) {
    		$store_id = $_GET['sid'];
    	}
    	
    	$string = '';
    	if (isset($_GET['main'])) {
    		$string = $_GET['main'];
    	}
    	
    	$branch_string = '';
    	if (isset($_GET['branch'])) {
    		$branch_string = $_GET['branch'];
    		if (!empty($branch_string)) {
    			$branch_string = '（' . $branch_string . '）';
    		}
    	}
    	
    	if (empty($store_id)) {
    		$data['errMsg'] = '错误的请求';
    		echo json_encode($data);
    		exit();
    	}
    	$url = PAY_QRCODE_URL . '?store=' . $store_id;
    	
    	$logo = Yii::app()->basePath . '/..' . GJ_STATIC_IMAGES . 'qrcode_alipay_logo.png';
    	$bg_path = Yii::app()->basePath . '/..' . GJ_STATIC_IMAGES . 'qrcode_bg_alipay.jpg';
    	
    	$img_name = QRcodeCreator::createAliQrcode($url, $string, $branch_string, $bg_path, $logo);
    	
    	$tmp = QRCODE_IMG_URL . basename($img_name, '.png').'_view.jpg';
    	$data['error'] = 'success';
    	$data['img_url'] = $tmp;
    	$data['download_url'] = Yii::app()->createUrl('mCenter/store/download', array('img_name' => $img_name));
    	
    	echo json_encode($data);
    }

    /**
     * 下载图片
     */
    public function actionDownload()
    {
        Yii::import('application.extensions.utility.*');
        require_once "QRcodeCreator.php";

        if (isset($_GET['img_name']) && !empty($_GET['img_name'])) {
            QRcodeCreator::download($_GET['img_name']);
        }
    }

    /**
     * ajax获取门店带参二维码
     */
    public function actionAjaxTradeNotice()
    {
        $notice_ob = $this->getTradeNoticeC();
        if (!empty($_POST['name'])) {
            $store_arr = explode('_', $_POST['name']);
            $store_id = $store_arr[0];
            $Qrcode_url = '';
            $merchant_id = Yii::app()->session["merchant_id"];  //商户id
            $wechat = new WechatBase();
            $result = json_decode($wechat->getMerchantAppidAndAppsecret($merchant_id), true); //获取获取商户的appid和appsecret
            if ($result['status'] == ERROR_NONE) {
//                 $appid = $result['wechat_subscription_appid'];
//                 $appsecret = $result['wechat_subscription_appsecret'];
                $appid = 'wxec84afe11d9da7c4';
                $appsecret = 'd41af6b0d7efdfb5fb925db13b205533';
                $access_token = $wechat->getAccessToken($appid, $appsecret);
                if ($access_token['error'] == true) {
                    $notice_ob = $this->getTradeNoticeC();
                    $ticket = $notice_ob->getQrcodeTicket($access_token['access_token'], $store_id);
                    if (!empty($ticket)) {
                        $Qrcode_url = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=" . urlencode($ticket);
                    }
                }
            }
            echo json_encode($Qrcode_url);
        }
        if (!empty($_POST['refresh'])) {
            $store = $_POST['store'];
            $storeId_arr = explode('_', $store);
            $count_old = $storeId_arr['1'];
            $count_new = json_decode($notice_ob->getStoreNoticeCount($storeId_arr[0]), true);
            if ($count_new['count'] > $count_old) {
                echo json_encode(true);
            } else {
                echo '';
            }
        }
    }

    /**
     * 微信收款通知列表
     */
    public function actionTradeNoticeList()
    {
        $count = '';
        $list = array();
        $merchant_id = Yii::app()->session["merchant_id"];  //商户id
        //获取商户所有的门店列表
        $store_ob = new StoreC();
        $notice_ob = $this->getTradeNoticeC();
        $model = json_decode($store_ob->getStoreListAll($merchant_id), true);
        if ($model['status'] == ERROR_NONE) {
            $storeList = $model['data']['list'];//所有门店数组
            if (!empty($storeList)) {
                $noticeList = array();  //已添加通知的门店
                $storeId_arr = array(); //门店id数组
                foreach ($storeList as $k => $v) {
                    $store_notice_count_re = json_decode($notice_ob->getStoreNoticeCount($v['id']));
                    $storeList[$k]['ajax_id'] = $v['id'] . '_' . $store_notice_count_re->count;
                    $storeId_arr[] = $v['id'];
                    if (empty($v['branch_name'])) {
                        $storeList[$k]['store_name'] = $v['name'];
                    } else {
                        $storeList[$k]['store_name'] = $v['name'] . '(' . $v['branch_name'] . ')';
                    }
                }
                $noticeList_res = json_decode($notice_ob->getTradeNoticeList('', $storeId_arr), true);
                if ($noticeList_res['status'] == ERROR_NONE) {
                    $list = $noticeList_res['data']['list'];
                    $count = $noticeList_res['count'];
                    if (!empty($_GET['page'])) {
                        $page = $_GET['page'];
                        foreach ($list as $k => $v) {
                            $list[$k]['index'] = $v['index'] + 10 * ($page - 1);
                        }
                    }
                }
            }
        }
        $this->render("tradeNoticeList", array(
            'count' => $count,
            'list' => $list,
            'storeList' => $storeList,
            'pages' => $notice_ob->page
        ));
    }

    /**
     *  根据id,删除收款通知对象
     */
    public function actionDeleteNoticeUser()
    {
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = $_GET['id'];
            $notice_ob = $this->getTradeNoticeC();
            $res = $notice_ob->deleteTradeNoticeUser($id);
        }
        $this->redirect('tradeNoticeList');
    }

    public function getTradeNoticeC()
    {
        return TradeNoticeC::getInstance();
    }

    public function getStoreC()
    {
        return StoreC::getInstance();
    }
}