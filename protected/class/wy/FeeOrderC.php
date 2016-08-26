<?php
/**
 * Created by PhpStorm.
 * User: sundi
 * Date: 2016/6/23
 * Time: 15:36
 */
include_once(dirname(__FILE__).'/../mainClass.php');

//费用订单类
class FeeOrderC extends mainClass
{

    /**
     * 添加费用订单
     */
    public function addFeeOrder($info,$order_type,$efee_type,$user_id,$community_id,$merchant_id){

        $result = '';
        //获取用户手机号
        $user_account = User::model() -> findByPk($user_id) -> account;
        $building_number = Proprietor::model() -> find('user_id=:user_id', array(':user_id'=>$user_id)) -> building_number;

        if ($order_type == FEEORDER_TYPE_ELECTRICITY_FEE){
            if ($efee_type == COMMUNITY_ELECTRICITY_FEE_SET_DAYPARTING){
                $date = $info['date'];
                $peak = $info['peak'];
                $valley = $info['valley'];
                $electricity = $info['electricity'];
                $order_money = $info['order_money'];

                try {
                    //判断月份是否为空
                    if(!isset($date) || empty($date)){
                        throw new Exception('info_date_miss');
                    }

                    //判断峰电量是否为空
                    if(!isset($peak)|| empty($peak)){
                        throw new Exception('info_peak_miss');
                    }

                    //判断谷电量是否为空
                    if(!isset($valley)|| empty($valley)){
                        throw new Exception('info_valley_miss');
                    }

                    //判断本月用电量是否为空
                    if(!isset($electricity)|| empty($electricity)){
                        throw new Exception('info_electricity_miss');
                    }

                    //判断本月电费是否为空
                    if(!isset($order_money)|| empty($order_money)){
                        throw new Exception('info_order_money_miss');
                    }

                    //判断本月电费是否为负
                    if(isset($order_money) && !empty($order_money) && $order_money < 0){
                        throw new Exception('info_order_money_miss');
                    }

                    $transaction = Yii::app()->db->beginTransaction();
                    $model = new FeeOrder();

                    preg_match_all('/\d/',$date,$arr);
                    $timer  =implode('',$arr[0]);
                    $date = substr($timer, 0, 4).'-'.substr($timer, 4).'-'.'01';
                    

                    //保存数据
                    $model['user_id'] = $user_id;
                    $model['user_account'] = $user_account;
                    $model['building_number'] = $building_number;
                    $model['order_no'] = $this->getOrderNo();
                    $model['community_id'] = $community_id;
                    $model['merchant_id'] = $merchant_id;
                    $model['order_type'] = $order_type;
                    $model['date'] = $date;
                    $model['peak'] = $peak;
                    $model['valley'] = $valley;
                    $model['electricity'] = $electricity;
                    $model['order_money'] = $order_money;
                    $model['pay_money'] = $order_money;
                    $model['create_time'] = date('Y-m-d H:i:s', time());
                    $model['last_time'] = date('Y-m-d H:i:s', time());
                    if ($model->save()) {
                        $transaction->commit();
                        $result['status'] = APPLY_CLASS_SUCCESS; //状态码
                        $result['errMsg'] = ''; //错误信息
                        $result['data'] = array('id' => $model->id);
                    } else {
                        $transaction->rollBack();
                        $result['status'] = ERROR_DATA_BASE_ADD;
                        throw new Exception('error_database_add');
                    }

                } catch (Exception $e) {
                    $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
                    $result['errMsg'] = $e->getMessage(); //错误信息
                }
            }else{

                $date = $info['date'];
                $electricity = $info['electricity'];
                $order_money = $info['order_money'];

                try {
                    //判断月份是否为空
                    if(!isset($date)|| empty($date)){
                        throw new Exception('info_date_miss');
                    }

                    //判断本月用电量是否为空
                    if(!isset($electricity)|| empty($electricity)){
                        throw new Exception('info_electricity_miss');
                    }

                    //判断本月电费是否为空
                    if(!isset($order_money)|| empty($order_money)){
                        throw new Exception('info_order_money_miss');
                    }

                    //判断本月电费是否为负
                    if(isset($order_money) && !empty($order_money) && $order_money < 0){
                        throw new Exception('info_order_money_miss');
                    }

                    $transaction = Yii::app()->db->beginTransaction();
                    $model = new FeeOrder();

                    preg_match_all('/\d/',$date,$arr);
                    $timer  =implode('',$arr[0]);
                    $date = substr($timer, 0, 4).'-'.substr($timer, 4).'-'.'01';

                    //保存数据
                    $model['user_id'] = $user_id;
                    $model['user_account'] = $user_account;
                    $model['building_number'] = $building_number;
                    $model['order_no'] = $this->getOrderNo();
                    $model['community_id'] = $community_id;
                    $model['merchant_id'] = $merchant_id;
                    $model['order_type'] = $order_type;


                    $model['date'] = $date;
                    $model['electricity'] = $electricity;
                    $model['order_money'] = $order_money;
                    $model['pay_money'] = $order_money;
                    $model['create_time'] = date('Y-m-d H:i:s', time());
                    $model['last_time'] = date('Y-m-d H:i:s', time());

                    if ($model->save()) {
                        $transaction->commit();
                        $result['status'] = APPLY_CLASS_SUCCESS; //状态码
                        $result['errMsg'] = ''; //错误信息
                        $result['data'] = array('id' => $model->id);
                    } else {
                        $transaction->rollBack();
                        $result['status'] = ERROR_DATA_BASE_ADD;
                        $result['errMsg'] = $model->getErrors(); //错误信息
                        throw new Exception('error_database_add');
                    }

                } catch (Exception $e) {
                    $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
                    $result['errMsg'] = $e->getMessage(); //错误信息
                }
            }
        }

        if ($order_type == FEEORDER_TYPE_WATER_FEE){
            $date = $info['date'];
            $water_ton = $info['water_ton'];
            $order_money = $info['order_money'];

            try {
                //判断月份是否为空
                if(!isset($date)|| empty($date)){
                    throw new Exception('info_date_miss');
                }

                //判断本月用水量是否为空
                if(!isset($water_ton)|| empty($water_ton)){
                    throw new Exception('info_electricity_miss');
                }

                //判断本月水费是否为空
                if(!isset($order_money)|| empty($order_money)){
                    throw new Exception('info_order_money_miss');
                }

                //判断本月水费是否为负
                if(isset($order_money) && !empty($order_money) && $order_money < 0){
                    throw new Exception('info_order_money_miss');
                }

                $transaction = Yii::app()->db->beginTransaction();
                $model = new FeeOrder();

                preg_match_all('/\d/',$date,$arr);
                $timer  =implode('',$arr[0]);
                $date = substr($timer, 0, 4).'-'.substr($timer, 4).'-'.'01';

                //保存数据
                $model['user_id'] = $user_id;
                $model['user_account'] = $user_account;
                $model['building_number'] = $building_number;
                $model['order_no'] = $this->getOrderNo();
                $model['community_id'] = $community_id;
                $model['merchant_id'] = $merchant_id;
                $model['order_type'] = $order_type;
                $model['date'] = $date;
                $model['water_ton'] = $water_ton;
                $model['order_money'] = $order_money;
                $model['pay_money'] = $order_money;
                $model['create_time'] = date('Y-m-d H:i:s', time());
                $model['last_time'] = date('Y-m-d H:i:s', time());
                if ($model->save()) {
                    $transaction->commit();
                    $result['status'] = APPLY_CLASS_SUCCESS; //状态码
                    $result['errMsg'] = ''; //错误信息
                    $result['data'] = array('id' => $model->id);
                } else {
                    $transaction->rollBack();
                    $result['status'] = ERROR_DATA_BASE_ADD;
                    throw new Exception('error_database_add');
                }

            } catch (Exception $e) {
                $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
                $result['errMsg'] = $e->getMessage(); //错误信息
            }
        }

        if ($order_type == FEEORDER_TYPE_PROPERTY_FEE){
            $date = $info['date'];
            $floor_space = $info['floor_space'];
            $order_money = $info['order_money'];

            try {
                //判断月份是否为空
                if(!isset($date)|| empty($date)){
                    throw new Exception('info_date_miss');
                }

                //判断房屋面积是否为空
                if(!isset($floor_space)|| empty($floor_space)){
                    throw new Exception('info_floor_space_miss');
                }

                //判断本年度物业费是否为空
                if(!isset($order_money)|| empty($order_money)){
                    throw new Exception('info_order_money_miss');
                }

                //判断本月水费是否为负
                if(isset($order_money) && !empty($order_money) && $order_money < 0){
                    throw new Exception('info_order_money_miss');
                }

                $transaction = Yii::app()->db->beginTransaction();
                $model = new FeeOrder();

                preg_match_all('/\d/',$date,$arr);
                $timer  =implode('',$arr[0]);
                $date = substr($timer, 0, 4).'-'.'01'.'-'.'01';

                //保存数据
                $model['user_id'] = $user_id;
                $model['user_account'] = $user_account;
                $model['building_number'] = $building_number;
                $model['order_no'] = $this->getOrderNo();
                $model['community_id'] = $community_id;
                $model['merchant_id'] = $merchant_id;
                $model['order_type'] = $order_type;
                $model['date'] = $date;
                $model['floor_space'] = $floor_space;
                $model['property_fee_month_num'] = 12;
                $model['order_money'] = $order_money;
                $model['pay_money'] = $order_money;
                $model['create_time'] = date('Y-m-d H:i:s', time());
                $model['last_time'] = date('Y-m-d H:i:s', time());
                if ($model->save()) {
                    $transaction->commit();
                    $result['status'] = APPLY_CLASS_SUCCESS; //状态码
                    $result['errMsg'] = ''; //错误信息
                    $result['data'] = array('id' => $model->id);
                } else {
                    $transaction->rollBack();
                    $result['status'] = ERROR_DATA_BASE_ADD;
                    throw new Exception('error_database_add');
                }

            } catch (Exception $e) {
                $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
                $result['errMsg'] = $e->getMessage(); //错误信息
            }
        }

        return json_encode($result);
    }

    /**
     * @param $order_type
     * @param string $user_id
     * @param string $order_id
     * @param string $info
     * @return string
     * 订单列表
     * $info--检索条件，数据类型为array，$order_id是订单id，用来查看订单详情，
     * $user_id是用户id，在业主管理中通过user_id来查找对应数据
     * $order_type是订单类型
     */
    public function getFeeOrderList($order_type,$merchant_id,$user_id='',$order_id='',$info=''){
        $result = array();
        if ($order_type == FEEORDER_TYPE_ELECTRICITY_FEE) {
            try {
                $criteria = new CDbCriteria();

                if(isset($user_id)&&!empty($user_id)){
                    $criteria->addCondition("user_id = :user_id");
                    $criteria->params[':user_id'] = $user_id;
                }

                if(isset($merchant_id)&&!empty($merchant_id)){
                    $criteria->addCondition("merchant_id = :merchant_id");
                    $criteria->params[':merchant_id'] = $merchant_id;
                }

                if(isset($order_id)&&!empty($order_id)){
                    $criteria->addCondition("id = :order_id");
                    $criteria->params[':order_id'] = $order_id;
                }

                //根据指定条件搜索
                if(isset($info)&&!empty($info)){
                    //小区名称
                    if(isset($info['community_id'])&&!empty($info['community_id'])){
                        $community_id = $info['community_id'];
                    }
                    //小区楼号
                    if(isset($info['building_number'])&&!empty($info['building_number'])){
                        $building_number = $info['building_number'];
                    }

                    //用户手机号
                    if(isset($info['user_account'])&&!empty($info['user_account'])){
                        $user_account = trim($info['user_account']);
                    }
                    //订单日期
                    if(isset($info['time'])&&!empty($info['time'])){
                        $time = $info['time'];
                    }
                    //支付状态
                    if(isset($info['pay_status']) && !empty($info['pay_status'])){
                        $pay_status = $info['pay_status'];
                    }
                }
                if(isset($community_id)&&!empty($community_id)){
                    $criteria->addCondition("community_id = :community_id");
                    $criteria->params[':community_id'] = $community_id;
                }
                if(isset($building_number)&&!empty($building_number)){
                    $criteria->addCondition("building_number = :building_number");
                    $criteria->params[':building_number'] = $building_number;
                }




                if(isset($user_account)&&!empty($user_account)){
                    $criteria->addCondition("user_account = :user_account");
                    $criteria->params[':user_account'] = $user_account;
                }

                //支付状态
                if(isset($pay_status) && !empty($pay_status)) {
                    $criteria->addCondition('pay_status = :pay_status');
                    $criteria->params[':pay_status'] = $pay_status;
                }

                $criteria->addCondition("flag = :flag");
                $criteria->addCondition("order_type = :order_type");

                $criteria->params[':flag'] = FLAG_NO;
                $criteria->params[':order_type'] = $order_type;

                //根据时间范围进行搜索
                if (isset($time)&&!empty($time)){
                    $Time1 = explode('-', $time);
                    //时间字段类型为timestamp
                    $Time1[0]= date('Y-m-d H-i-s',strtotime($Time1[0].' 00:00:00'));
                    $Time1[1]= date('Y-m-d H-i-s',strtotime($Time1[1].' 23:59:59'));
                    $criteria->addCondition("create_time > :start_time");
                    $criteria->addCondition("create_time < :end_time");
                    $criteria->params[':start_time'] = $Time1[0];
                    $criteria->params[':end_time'] = $Time1[1];
                }

                $criteria->order = "create_time desc";

                $pages = new CPagination(FeeOrder::model()->count($criteria));
                $pages->pageSize = Yii::app()->params['perPage'];
                $pages->applyLimit($criteria);

                $feeOrder = FeeOrder::model()->findAll($criteria);
                $data = array();

                if (!empty($feeOrder)) {
                    foreach ($feeOrder as $k => $v) {
                        $data[$k]['id'] = $v['id'];
                        $data[$k]['user_id'] = $v['user_id'];
                        $data[$k]['account']=$v['user_account'];

                        //获取业主表中的信息--楼号/门牌号
                        $proprietor = Proprietor::model()->find(
                            'user_id=:user_id',
                            array(':user_id'=>$v['user_id'])
                        );
                        $proprietorinfo=$proprietor->attributes;

                        $data[$k]['building_number'] = $proprietorinfo['building_number'];
                        $data[$k]['room_number'] = $proprietorinfo['room_number'];
                        //$data[$k]['community_id'] = $proprietorinfo['community_id'];
                        $info_community_id = $proprietorinfo['community_id'];

                        //获取业主姓名/手机号/储值金额
                        $user = User::model()->find(
                            'id=:user_id',
                            array(':user_id'=>$v['user_id'])
                        );
                        $userinfo=$user->attributes;
                        $data[$k]['name']=$userinfo['name'];
                        $data[$k]['money']=isset($userinfo['money']) ? $userinfo['money'] : '0.00';

                        //获取小区名字/费用设置详情
                        $community = Community::model()->find(
                            'id=:community_id',
                            array(':community_id'=>$info_community_id)
                        );
                        $data[$k]['community_name'] = $community['name'];
                        $electricity_fee_set=json_decode($community['electricity_fee_set'],true);
                        $type = $electricity_fee_set['type'];
                        if(isset($electricity_fee_set['peak_price'])&&isset($electricity_fee_set['trough_price'])){
                            $peak_price = $electricity_fee_set['peak_price'];
                            $trough_price = $electricity_fee_set['trough_price'];
                            $data[$k]['peak_price'] = $peak_price;
                            $data[$k]['trough_price'] = $trough_price;
                        }
                        if(isset($electricity_fee_set['price'])){
                            $price = $electricity_fee_set['price'];
                            $data[$k]['price'] = $price;
                        }

                        $data[$k]['id'] = $v['id'];
                        $data[$k]['user_id'] = $v['user_id'];
                        $data[$k]['date'] =  substr($v['date'],0,7);
                        $data[$k]['peak'] = $v['peak'];
                        $data[$k]['valley'] = $v['valley'];
                        $data[$k]['electricity'] = $v['electricity'];
                        $data[$k]['order_money'] = $v['order_money'];
                        $data[$k]['order_id'] = $v['id'];
                        $data[$k]['pay_status'] = $v['pay_status'];
                        $data[$k]['type'] = $type;
                        $data[$k]['order_type'] = $v['order_type'];
                        $data[$k]['create_time'] = $v['create_time'];


                    }
                }
                $result['status'] = APPLY_CLASS_SUCCESS;
                $result['data'] = $data;
            } catch (Exception $e) {
                $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
                $result['errMsg'] = $e->getMessage(); //错误信息
            }
            $this->page = $pages;

        }elseif($order_type == FEEORDER_TYPE_WATER_FEE){
            try {
                $criteria = new CDbCriteria();
                if(isset($user_id)&&!empty($user_id)){
                    $criteria->addCondition("user_id = :user_id");
                    $criteria->params[':user_id'] = $user_id;
                }

                if(isset($merchant_id)&&!empty($merchant_id)){
                    $criteria->addCondition("merchant_id = :merchant_id");
                    $criteria->params[':merchant_id'] = $merchant_id;
                }

                if(isset($order_id)&&!empty($order_id)){
                    $criteria->addCondition("id = :order_id");
                    $criteria->params[':order_id'] = $order_id;
                }

                //检索--小区和楼栋
                if(isset($info)&&!empty($info)){
                    if(isset($info['community_id'])&&!empty($info['community_id'])){
                        $community_id = $info['community_id'];
                    }
                    if(isset($info['building_number'])&&!empty($info['building_number'])){
                        $building_number = $info['building_number'];
                    }
                    if(isset($info['user_account'])&&!empty($info['user_account'])){
                        $user_account = trim($info['user_account']);
                    }
                    if(isset($info['time'])&&!empty($info['time'])){
                        $time = trim($info['time']);
                    }
                    //支付状态
                    if(isset($info['pay_status']) && !empty($info['pay_status'])){
                        $pay_status = $info['pay_status'];
                    }
                }
                if(isset($community_id)&&!empty($community_id)){
                    $criteria->addCondition("community_id = :community_id");
                    $criteria->params[':community_id'] = $community_id;
                }
                if(isset($building_number)&&!empty($building_number)){
                    $criteria->addCondition("building_number = :building_number");
                    $criteria->params[':building_number'] = $building_number;
                }
                if(isset($user_account)&&!empty($user_account)){
                    $criteria->addCondition("user_account = :user_account");
                    $criteria->params[':user_account'] = $user_account;
                }
                //支付状态
                if(isset($pay_status) && !empty($pay_status)) {
                    $criteria->addCondition('pay_status = :pay_status');
                    $criteria->params[':pay_status'] = $pay_status;
                }

                $criteria->addCondition("flag = :flag");
                $criteria->addCondition("order_type = :order_type");

                //根据时间范围进行搜索
                if (isset($time)&&!empty($time)){
                    $Time1 = explode('-', $time);
                    //时间字段类型为timestamp
                    $Time1[0]= date('Y-m-d H-i-s',strtotime($Time1[0].' 00:00:00'));
                    $Time1[1]= date('Y-m-d H-i-s',strtotime($Time1[1].' 23:59:59'));
                    $criteria->addCondition("create_time > :start_time");
                    $criteria->addCondition("create_time < :end_time");
                    $criteria->params[':start_time'] = $Time1[0];
                    $criteria->params[':end_time'] = $Time1[1];
                    //print_r($criteria);exit;
                }

                $criteria->params[':flag'] = FLAG_NO;
                $criteria->params[':order_type'] = $order_type;

                $criteria->order = "create_time desc";

                $pages = new CPagination(FeeOrder::model()->count($criteria));
                $pages->pageSize = Yii::app()->params['perPage'];
                $pages->applyLimit($criteria);

                $feeOrder = FeeOrder::model()->findAll($criteria);

                $data = array();

                if (!empty($feeOrder)) {
                    foreach ($feeOrder as $k => $v) {
                        $data[$k]['id'] = $v['id'];
                        $data[$k]['user_id'] = $v['user_id'];
                        $data[$k]['account']=$v['user_account'];

                        //获取业主表中的信息--楼号/门牌号
                        $proprietor = Proprietor::model()->find(
                            'user_id=:user_id',
                            array(':user_id'=>$v['user_id'])
                        );
                        $proprietorinfo=$proprietor->attributes;

                        $data[$k]['building_number'] = $proprietorinfo['building_number'];
                        $data[$k]['room_number'] = $proprietorinfo['room_number'];
                        $community_id = $proprietorinfo['community_id'];

                        //获取业主姓名/手机号/储值金额
                        $user = User::model()->find(
                            'id=:user_id',
                            array(':user_id'=>$v['user_id'])
                        );
                        $userinfo=$user->attributes;
                        $data[$k]['name']=$userinfo['name'];
                        $data[$k]['money']=isset($userinfo['money']) ? $userinfo['money'] : '0.00';

                        //获取小区名字/水费设置详情
                        $community = Community::model()->find(
                            'id=:community_id',
                            array(':community_id'=>$community_id)
                        );
                        $data[$k]['community_name'] = $community['name'];
                        $water_fee_set=json_decode($community['water_fee_set'],true);

                        $data[$k]['water_price'] = $water_fee_set['price'];
                        $data[$k]['order_money'] = $v['order_money'];

                        $data[$k]['id'] = $v['id'];
                        $data[$k]['date'] = substr($v['date'],0,7);
                        $data[$k]['create_time'] = $v['create_time'];
                        $data[$k]['water_ton'] = $v['water_ton'];
                        $data[$k]['order_money'] = $v['order_money'];
                        $data[$k]['order_id'] = $v['id'];
                        $data[$k]['order_type'] = $v['order_type'];
                        $data[$k]['pay_status'] = $v['pay_status'];
                    }
                }
                $result['status'] = APPLY_CLASS_SUCCESS;
                $result['data'] = $data;
            } catch (Exception $e) {
                $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
                $result['errMsg'] = $e->getMessage(); //错误信息
            }
            $this->page = $pages;
        }
        elseif($order_type == FEEORDER_TYPE_PARKING_FEE){
            try {
                $criteria = new CDbCriteria();
                if(isset($user_id)&&!empty($user_id)){
                    $criteria->addCondition("user_id = :user_id");
                    $criteria->params[':user_id'] = $user_id;
                }

                if(isset($merchant_id)&&!empty($merchant_id)){
                    $criteria->addCondition("merchant_id = :merchant_id");
                    $criteria->params[':merchant_id'] = $merchant_id;
                }

                if(isset($order_id)&&!empty($order_id)){
                    $criteria->addCondition("id = :order_id");
                    $criteria->params[':order_id'] = $order_id;
                }

                //检索--小区和楼栋
                if(isset($info)&&!empty($info)){
                    if(isset($info['community_id'])&&!empty($info['community_id'])){
                        $community_id = $info['community_id'];
                    }
                    if(isset($info['building_number'])&&!empty($info['building_number'])){
                        $building_number = $info['building_number'];
                    }
                    if(isset($info['user_account'])&&!empty($info['user_account'])){
                        $user_account = trim($info['user_account']);
                    }
                    if(isset($info['pay_status'])&&!empty($info['pay_status'])){
                        $pay_status = $info['pay_status'];
                    }
                    if(isset($info['time'])&&!empty($info['time'])){
                        $time = $info['time'];
                    }
                    //支付状态
                    if(isset($info['pay_status']) && !empty($info['pay_status'])){
                        $pay_status = $info['pay_status'];
                    }
                }
                if(isset($community_id)&&!empty($community_id)){
                    $criteria->addCondition("community_id = :community_id");
                    $criteria->params[':community_id'] = $community_id;
                }
                if(isset($building_number)&&!empty($building_number)){
                    $criteria->addCondition("building_number = :building_number");
                    $criteria->params[':building_number'] = $building_number;
                }
                if(isset($user_account)&&!empty($user_account)){
                    $criteria->addCondition("user_account = :user_account");
                    $criteria->params[':user_account'] = $user_account;
                }
                if(isset($pay_status)&&!empty($pay_status)){
                    $paystatus = ($pay_status == 'GJORDER_PAY_STATUS_NUPAID' ? 1:2);
                    $criteria->addCondition("pay_status = :pay_status");
                    $criteria->params[':pay_status'] = $paystatus;
                }
                //支付状态
                if(isset($pay_status) && !empty($pay_status)) {
                    $criteria->addCondition('pay_status = :pay_status');
                    $criteria->params[':pay_status'] = $pay_status;
                }
                //根据时间范围进行搜索
                if (isset($time)&&!empty($time)){
                    $Time1 = explode('-', $time);
                    //时间字段类型为timestamp
                    $Time1[0]= date('Y-m-d H-i-s',strtotime($Time1[0].' 00:00:00'));
                    $Time1[1]= date('Y-m-d H-i-s',strtotime($Time1[1].' 23:59:59'));
                    $criteria->addCondition("pay_time > :start_time");
                    $criteria->addCondition("pay_time < :end_time");
                    $criteria->params[':start_time'] = $Time1[0];
                    $criteria->params[':end_time'] = $Time1[1];
                }
                $criteria->addCondition("flag = :flag");
                $criteria->addCondition("order_type = :order_type");

                $criteria->params[':flag'] = FLAG_NO;
                $criteria->params[':order_type'] = $order_type;

                $criteria->order = "create_time desc";

                $pages = new CPagination(FeeOrder::model()->count($criteria));
                $pages->pageSize = Yii::app()->params['perPage'];
                $pages->applyLimit($criteria);

                $feeOrder = FeeOrder::model()->findAll($criteria);

                $data = array();

                if (!empty($feeOrder)) {
                    foreach ($feeOrder as $k => $v) {
                        $data[$k]['id'] = $v['id'];
                        $data[$k]['user_id'] = $v['user_id'];
                        $data[$k]['account']=$v['user_account'];

                        //获取业主表中的信息--楼号/门牌号
                        $proprietor = Proprietor::model()->find(
                            'user_id=:user_id',
                            array(':user_id'=>$v['user_id'])
                        );
                        $proprietorinfo=$proprietor->attributes;

                        $data[$k]['building_number'] = $proprietorinfo['building_number'];
                        $data[$k]['room_number'] = $proprietorinfo['room_number'];
                        $community_id = $proprietorinfo['community_id'];

                        //获取业主姓名/手机号/储值金额
                        $user = User::model()->find(
                            'id=:user_id',
                            array(':user_id'=>$v['user_id'])
                        );
                        $userinfo=$user->attributes;
                        $data[$k]['name']=$userinfo['name'];
                        $data[$k]['money']=isset($userinfo['money']) ? $userinfo['money'] : '0.00';

                        //获取小区名字
                        $community = Community::model()->find(
                            'id=:community_id',
                            array(':community_id'=>$community_id)
                        );
                        $data[$k]['community_name'] = $community['name'];

                        /**
                         * 停车费设置形式
                         * {"community_id":"9","day_price":"20","half_year_price":"1000","year_price":"2000"}
                         */

                        $data[$k]['date'] = $v['date'];
                        $data[$k]['pay_time'] = $v['pay_time'];
                        $data[$k]['parking_month_num'] = $v['parking_month_num'];
                        $data[$k]['order_money'] = $v['order_money'];
                        $data[$k]['pay_status'] = $v['pay_status'];
                        $data[$k]['order_type'] = $v['order_type'];
                        $data[$k]['order_id'] = $v['id'];
                        $data[$k]['create_time'] = $v['create_time'];
                    }
                }
                $result['status'] = APPLY_CLASS_SUCCESS;
                $result['data'] = $data;
            } catch (Exception $e) {
                $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
                $result['errMsg'] = $e->getMessage(); //错误信息
            }
            $this->page = $pages;
        }
        elseif($order_type == FEEORDER_TYPE_PROPERTY_FEE){
            try {
                $criteria = new CDbCriteria();
                if(isset($user_id)&&!empty($user_id)){
                    $criteria->addCondition("user_id = :user_id");
                    $criteria->params[':user_id'] = $user_id;
                }

                if(isset($merchant_id)&&!empty($merchant_id)){
                    $criteria->addCondition("merchant_id = :merchant_id");
                    $criteria->params[':merchant_id'] = $merchant_id;
                }

                if(isset($order_id)&&!empty($order_id)){
                    $criteria->addCondition("id = :order_id");
                    $criteria->params[':order_id'] = $order_id;
                }
                //检索--小区和楼栋
                if(isset($info)&&!empty($info)){
                    if(isset($info['community_id'])&&!empty($info['community_id'])){
                        $community_id = $info['community_id'];
                    }
                    if(isset($info['building_number'])&&!empty($info['building_number'])){
                        $building_number = $info['building_number'];
                    }
                    if(isset($info['user_account'])&&!empty($info['user_account'])){
                        $user_account = trim($info['user_account']);
                    }
                    if(isset($info['pay_status'])&&!empty($info['pay_status'])){
                        $pay_status = $info['pay_status'];
                    }
                    if(isset($info['time'])&&!empty($info['time'])){
                        $time = $info['time'];
                    }
                }
                if(isset($community_id)&&!empty($community_id)){
                    $criteria->addCondition("community_id = :community_id");
                    $criteria->params[':community_id'] = $community_id;
                }
                if(isset($building_number)&&!empty($building_number)){
                    $criteria->addCondition("building_number = :building_number");
                    $criteria->params[':building_number'] = $building_number;
                }
                //根据用户手机号进行搜索
                if(isset($user_account)&&!empty($user_account)){
                    $criteria->addCondition("user_account = :user_account");
                    $criteria->params[':user_account'] = $user_account;
                }
                if(isset($pay_status)&&!empty($pay_status)){
                    $paystatus = ($pay_status == 'GJORDER_PAY_STATUS_NUPAID' ? 1:2);
                    $criteria->addCondition("pay_status = :pay_status");
                    $criteria->params[':pay_status'] = $paystatus;
                }
                //根据时间范围进行搜索
                if (isset($time)&&!empty($time)){
                    $Time1 = explode('-', $time);
                    //时间字段类型为timestamp
                    $Time1[0]= date('Y-m-d H-i-s',strtotime($Time1[0].' 00:00:00'));
                    $Time1[1]= date('Y-m-d H-i-s',strtotime($Time1[1].' 23:59:59'));
                    $criteria->addCondition("pay_time > :start_time");
                    $criteria->addCondition("pay_time < :end_time");
                    $criteria->params[':start_time'] = $Time1[0];
                    $criteria->params[':end_time'] = $Time1[1];
                    //print_r($criteria);exit;
                }
                $criteria->addCondition("flag = :flag");
                $criteria->addCondition("order_type = :order_type");

                $criteria->params[':flag'] = FLAG_NO;
                $criteria->params[':order_type'] = $order_type;

                $criteria->order = "create_time desc";

                $pages = new CPagination(FeeOrder::model()->count($criteria));
                $pages->pageSize = Yii::app()->params['perPage'];
                $pages->applyLimit($criteria);

                $feeOrder = FeeOrder::model()->findAll($criteria);

                $data = array();

                if (!empty($feeOrder)) {
                    foreach ($feeOrder as $k => $v) {
                        $data[$k]['id'] = $v['id'];
                        $data[$k]['user_id'] = $v['user_id'];
                        $data[$k]['account']=$v['user_account'];

                        //获取业主表中的信息--楼号/门牌号
                        $proprietor = Proprietor::model()->find(
                            'user_id=:user_id',
                            array(':user_id'=>$v['user_id'])
                        );
                        $proprietorinfo=$proprietor->attributes;

                        $data[$k]['building_number'] = $proprietorinfo['building_number'];
                        $data[$k]['room_number'] = $proprietorinfo['room_number'];
                        $community_id = $proprietorinfo['community_id'];

                        //获取业主姓名/手机号/储值金额
                        $user = User::model()->find(
                            'id=:user_id',
                            array(':user_id'=>$v['user_id'])
                        );
                        $userinfo=$user->attributes;
                        $data[$k]['name']=$userinfo['name'];
                        $data[$k]['money']=isset($userinfo['money']) ? $userinfo['money'] : '0.00';

                        //获取小区名字
                        $community = Community::model()->find(
                            'id=:community_id',
                            array(':community_id'=>$community_id)
                        );
                        $data[$k]['community_name'] = $community['name'];

                        $data[$k]['date'] = substr($v['date'],0,4);
                        $data[$k]['pay_time'] = $v['pay_time'];
                        $data[$k]['property_fee_month_num'] = $v['property_fee_month_num'];
                        $data[$k]['order_money'] = $v['order_money'];
                        $data[$k]['pay_status'] = $v['pay_status'];
                        $data[$k]['order_type'] = $v['order_type'];
                        $data[$k]['order_id'] = $v['id'];
                        $data[$k]['create_time'] = $v['create_time'];
                    }
                }
                $result['status'] = APPLY_CLASS_SUCCESS;
                $result['data'] = $data;
            } catch (Exception $e) {
                $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
                $result['errMsg'] = $e->getMessage(); //错误信息
            }
            $this->page = $pages;
        }
        return json_encode($result);

    }

    /**
     * @param $order_id
     * 获取订单详情
     */
    public function getFeeOrderInfo($order_id){
        try {
            $criteria = new CDbCriteria();
            $criteria->addCondition("id = :order_id");
            $criteria->params[':order_id'] = $order_id;

            $feeOrder = FeeOrder::model()->findAll($criteria);

            $data = array();
            if (!empty($feeOrder)) {
                foreach ($feeOrder as $k => $v) {
                    $data['id'] = $v['id'];
                    $data['community_id'] = $v['community_id'];
                    $data['peak'] = $v['peak'];
                    $data['valley'] = $v['valley'];
                    $data['electricity'] = $v['electricity'];
                    $data['water_ton'] = $v['water_ton'];
                    $data['order_money'] = $v['order_money'];
                    $data['car_id'] = $v['car_id'];
                }
            }
            $car = ProprietorCar::model()->findByPk($data['car_id']);
            $data['car_brand'] = $car->car_brand;
            $data['car_no'] = $car->car_no;
            $data['car_img'] = $car->car_img;

            $result['status'] = APPLY_CLASS_SUCCESS;
            $result['data'] = $data;
            $result['community_id'] = $data['community_id'];

        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }
        return json_encode($result);
    }

    /**
     * @param $order_id
     * 获取车辆详情
     */
    public function getCarInfo($car_id){
        try {
            $criteria = new CDbCriteria();
            $criteria->addCondition("id = :car_id");
            $criteria->params[':car_id'] = $car_id;

            $result = ProprietorCar::model()->find($criteria);
            $car_info = json_decode(CJSON::encode($result), TRUE);

            $data = array();
            $result = array();
            $data['car_brand'] = $car_info['car_brand'];
            $data['car_no'] = $car_info['car_no'];
            $data['car_img'] = json_decode($car_info['car_img']);

            $result['status'] = APPLY_CLASS_SUCCESS;
            $result['data'] = $data;

        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }
        return $result;
    }

    /**
     * @param $id
     * @param $order_type
     * @return string
     * 确认支付状态
     */
    public function editFeeOrderPayStatus($id, $order_type) {
        $result = array();
        try {
            $cri = new CDbCriteria();
            $cri -> addCondition('id = :id');
            $cri -> params[':id'] = $id;
            $cri -> addCondition('order_type = :order_type');
            $cri-> params[':order_type'] = $order_type;
            $cri -> addCondition('flag = :flag');
            $cri -> params[':flag'] = FLAG_NO;
            $model = FeeOrder::model() -> find($cri);

            if ($model) {
                //物业费
                if ($model -> order_type == FEEORDER_TYPE_PROPERTY_FEE) {
                    if ($model -> pay_status == ORDER_STATUS_UNPAID) {
                        $model -> pay_status = ORDER_STATUS_PAID;
                    }
                }

                //停车费
                if ($model -> order_type == FEEORDER_TYPE_PARKING_FEE) {
                    if ($model -> pay_status == ORDER_STATUS_UNPAID) {
                        $model -> pay_status = ORDER_STATUS_PAID;
                    }
                }

                if ($model -> update()) {
                    $result['status'] = APPLY_CLASS_SUCCESS;
                    $result['errMsg'] = '';
                }
            }

        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : APPLY_CLASS_FAIL;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }

        return json_encode($result);
    }

    /**
     * @return string
     * 生成订单号
     */
    public function getOrderNo()
    {
        $flag = FLAG_NO;
        do{
            $Code = 'ZH'.date('Ymd').$this -> getNumRandChar(4); //订单号生成规则：ZH（智慧）+年/月/日+4位随机数
            $ModelCode = FeeOrder::model() -> find('order_no = :order_no', array(':order_no' => $Code));
            if(empty($ModelCode)) {
                $flag = FLAG_YES;
            }
        } while($flag == FLAG_NO);

        return $Code;
    }

    /**
     * @param $length
     * @return null|string
     * 生成指定位数随机数（4位）
     */
    private function getNumRandChar($length){
        $str = null;
        $strPol = "0123456789";
        $max = strlen($strPol)-1;

        for($i=0;$i<$length;$i++){
            $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }
        return $str;
    }

    /**
     * @param $order_type
     * @param string $user_id
     * @param string $order_id
     * @param string $info
     * @return string
     * 获取导出Excel订单列表信息
     */
    public function getFeeOrderExcel($order_type,$merchant_id,$user_id='',$order_id='',$info=''){
        $result = array();
        if ($order_type == FEEORDER_TYPE_ELECTRICITY_FEE) {
            try {
                $criteria = new CDbCriteria();

                if(isset($merchant_id)&&!empty($merchant_id)){
                    $criteria->addCondition("merchant_id = :merchant_id");
                    $criteria->params[':merchant_id'] = $merchant_id;
                }

                if(isset($user_id)&&!empty($user_id)){
                    $criteria->addCondition("user_id = :user_id");
                    $criteria->params[':user_id'] = $user_id;
                }

                if(isset($order_id)&&!empty($order_id)){
                    $criteria->addCondition("id = :order_id");
                    $criteria->params[':order_id'] = $order_id;
                }

                //根据指定条件搜索
                if(isset($info)&&!empty($info)){
                    //小区名称
                    if(isset($info['community_id'])&&!empty($info['community_id'])){
                        $community_id = $info['community_id'];
                    }
                    //小区楼号
                    if(isset($info['building_number'])&&!empty($info['building_number'])){
                        $building_number = $info['building_number'];
                    }

                    //用户手机号
                    if(isset($info['user_account'])&&!empty($info['user_account'])){
                        $user_account = trim($info['user_account']);
                    }
                    //订单日期
                    if(isset($info['time'])&&!empty($info['time'])){
                        $time = $info['time'];
                    }
                    //支付状态
                    if(isset($info['pay_status']) && !empty($info['pay_status'])){
                        $pay_status = $info['pay_status'];
                    }
                }
                if(isset($community_id)&&!empty($community_id)){
                    $criteria->addCondition("community_id = :community_id");
                    $criteria->params[':community_id'] = $community_id;
                }
                if(isset($building_number)&&!empty($building_number)){
                    $criteria->addCondition("building_number = :building_number");
                    $criteria->params[':building_number'] = $building_number;
                }

                if(isset($user_account)&&!empty($user_account)){
                    $criteria->addCondition("user_account = :user_account");
                    $criteria->params[':user_account'] = $user_account;
                }

                //支付状态
                if(isset($pay_status) && !empty($pay_status)) {
                    $criteria->addCondition('pay_status = :pay_status');
                    $criteria->params[':pay_status'] = $pay_status;
                }

                $criteria->addCondition("flag = :flag");
                $criteria->addCondition("order_type = :order_type");

                $criteria->params[':flag'] = FLAG_NO;
                $criteria->params[':order_type'] = $order_type;

                //根据时间范围进行搜索
                if (isset($time)&&!empty($time)){
                    $Time1 = explode('-', $time);
                    //时间字段类型为timestamp
                    $Time1[0]= date('Y-m-d H-i-s',strtotime($Time1[0].' 00:00:00'));
                    $Time1[1]= date('Y-m-d H-i-s',strtotime($Time1[1].' 23:59:59'));
                    $criteria->addCondition("create_time > :start_time");
                    $criteria->addCondition("create_time < :end_time");
                    $criteria->params[':start_time'] = $Time1[0];
                    $criteria->params[':end_time'] = $Time1[1];
                }

                $criteria->order = "create_time desc";

                $feeOrder = FeeOrder::model()->findAll($criteria);
                $data = array();

                if (!empty($feeOrder)) {
                    foreach ($feeOrder as $k => $v) {
                        $data[$k]['id'] = $v['id'];
                        $data[$k]['user_id'] = $v['user_id'];

                        //获取业主表中的信息--楼号/门牌号
                        $proprietor = Proprietor::model()->find(
                            'user_id=:user_id',
                            array(':user_id'=>$v['user_id'])
                        );
                        $proprietorinfo=$proprietor->attributes;

                        $data[$k]['building_number'] = $proprietorinfo['building_number'];
                        $data[$k]['room_number'] = $proprietorinfo['room_number'];
                        //$data[$k]['community_id'] = $proprietorinfo['community_id'];
                        $info_community_id = $proprietorinfo['community_id'];

                        //获取业主姓名/手机号/储值金额
                        $user = User::model()->find(
                            'id=:user_id',
                            array(':user_id'=>$v['user_id'])
                        );
                        $userinfo=$user->attributes;
                        $data[$k]['name']=$userinfo['name'];
                        $data[$k]['account']=$userinfo['account'];
                        $data[$k]['money']=isset($userinfo['money']) ? $userinfo['money'] : '0.00';

                        //获取小区名字/费用设置详情
                        $community = Community::model()->find(
                            'id=:community_id',
                            array(':community_id'=>$info_community_id)
                        );
                        $data[$k]['community_name'] = $community['name'];
                        $electricity_fee_set=json_decode($community['electricity_fee_set'],true);
                        $type = $electricity_fee_set['type'];
                        if(isset($electricity_fee_set['peak_price'])&&isset($electricity_fee_set['trough_price'])){
                            $peak_price = $electricity_fee_set['peak_price'];
                            $trough_price = $electricity_fee_set['trough_price'];
                            $data[$k]['peak_price'] = $peak_price;
                            $data[$k]['trough_price'] = $trough_price;
                        }
                        if(isset($electricity_fee_set['price'])){
                            $price = $electricity_fee_set['price'];
                            $data[$k]['price'] = $price;
                        }

                        $data[$k]['id'] = $v['id'];
                        $data[$k]['user_id'] = $v['user_id'];
                        $data[$k]['date'] = $v['date'];
                        $data[$k]['peak'] = $v['peak'];
                        $data[$k]['valley'] = $v['valley'];
                        $data[$k]['electricity'] = $v['electricity'];
                        $data[$k]['order_money'] = $v['order_money'];
                        $data[$k]['order_id'] = $v['id'];
                        $data[$k]['pay_status'] = $v['pay_status'];
                        $data[$k]['type'] = $type;
                        $data[$k]['create_time'] = $v['create_time'];


                    }
                }
                $result['status'] = APPLY_CLASS_SUCCESS;
                $result['data'] = $data;
            } catch (Exception $e) {
                $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
                $result['errMsg'] = $e->getMessage(); //错误信息
            }
            return json_encode($result);

        }elseif($order_type == FEEORDER_TYPE_WATER_FEE){
            try {
                $criteria = new CDbCriteria();
                if(isset($user_id)&&!empty($user_id)){
                    $criteria->addCondition("user_id = :user_id");
                    $criteria->params[':user_id'] = $user_id;
                }

                if(isset($merchant_id)&&!empty($merchant_id)){
                    $criteria->addCondition("merchant_id = :merchant_id");
                    $criteria->params[':merchant_id'] = $merchant_id;
                }

                if(isset($order_id)&&!empty($order_id)){
                    $criteria->addCondition("id = :order_id");
                    $criteria->params[':order_id'] = $order_id;
                }

                //检索--小区和楼栋
                if(isset($info)&&!empty($info)){
                    if(isset($info['community_id'])&&!empty($info['community_id'])){
                        $community_id = $info['community_id'];
                    }
                    if(isset($info['building_number'])&&!empty($info['building_number'])){
                        $building_number = $info['building_number'];
                    }
                    if(isset($info['user_account'])&&!empty($info['user_account'])){
                        $user_account = trim($info['user_account']);
                    }
                    if(isset($info['time'])&&!empty($info['time'])){
                        $time = trim($info['time']);
                    }
                    //支付状态
                    if(isset($info['pay_status']) && !empty($info['pay_status'])){
                        $pay_status = $info['pay_status'];
                    }
                }
                if(isset($community_id)&&!empty($community_id)){
                    $criteria->addCondition("community_id = :community_id");
                    $criteria->params[':community_id'] = $community_id;
                }
                if(isset($building_number)&&!empty($building_number)){
                    $criteria->addCondition("building_number = :building_number");
                    $criteria->params[':building_number'] = $building_number;
                }
                if(isset($user_account)&&!empty($user_account)){
                    $criteria->addCondition("user_account = :user_account");
                    $criteria->params[':user_account'] = $user_account;
                }
                //支付状态
                if(isset($pay_status) && !empty($pay_status)) {
                    $criteria->addCondition('pay_status = :pay_status');
                    $criteria->params[':pay_status'] = $pay_status;
                }

                $criteria->addCondition("flag = :flag");
                $criteria->addCondition("order_type = :order_type");

                //根据时间范围进行搜索
                if (isset($time)&&!empty($time)){
                    $Time1 = explode('-', $time);
                    //时间字段类型为timestamp
                    $Time1[0]= date('Y-m-d H-i-s',strtotime($Time1[0].' 00:00:00'));
                    $Time1[1]= date('Y-m-d H-i-s',strtotime($Time1[1].' 23:59:59'));
                    $criteria->addCondition("create_time > :start_time");
                    $criteria->addCondition("create_time < :end_time");
                    $criteria->params[':start_time'] = $Time1[0];
                    $criteria->params[':end_time'] = $Time1[1];
                    //print_r($criteria);exit;
                }

                $criteria->params[':flag'] = FLAG_NO;
                $criteria->params[':order_type'] = $order_type;

                $criteria->order = "create_time desc";

                $feeOrder = FeeOrder::model()->findAll($criteria);

                $data = array();

                if (!empty($feeOrder)) {
                    foreach ($feeOrder as $k => $v) {
                        $data[$k]['id'] = $v['id'];
                        $data[$k]['user_id'] = $v['user_id'];

                        //获取业主表中的信息--楼号/门牌号
                        $proprietor = Proprietor::model()->find(
                            'user_id=:user_id',
                            array(':user_id'=>$v['user_id'])
                        );
                        $proprietorinfo=$proprietor->attributes;

                        $data[$k]['building_number'] = $proprietorinfo['building_number'];
                        $data[$k]['room_number'] = $proprietorinfo['room_number'];
                        $community_id = $proprietorinfo['community_id'];

                        //获取业主姓名/手机号/储值金额
                        $user = User::model()->find(
                            'id=:user_id',
                            array(':user_id'=>$v['user_id'])
                        );
                        $userinfo=$user->attributes;
                        $data[$k]['name']=$userinfo['name'];
                        $data[$k]['account']=$userinfo['account'];
                        $data[$k]['money']=isset($userinfo['money']) ? $userinfo['money'] : '0.00';

                        //获取小区名字/水费设置详情
                        $community = Community::model()->find(
                            'id=:community_id',
                            array(':community_id'=>$community_id)
                        );
                        $data[$k]['community_name'] = $community['name'];
                        $water_fee_set=json_decode($community['water_fee_set'],true);

                        $data[$k]['water_price'] = $water_fee_set['price'];
                        $data[$k]['order_money'] = $v['order_money'];

                        $data[$k]['id'] = $v['id'];
                        $data[$k]['date'] = $v['date'];
                        $data[$k]['create_time'] = $v['create_time'];
                        $data[$k]['water_ton'] = round($v['water_ton'],0);
                        $data[$k]['order_money'] = $v['order_money'];
                        //$data[$k]['order_id'] = $v['id'];
                        $data[$k]['pay_status'] = $v['pay_status'];
                    }
                }
                $result['status'] = APPLY_CLASS_SUCCESS;
                $result['data'] = $data;
            } catch (Exception $e) {
                $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
                $result['errMsg'] = $e->getMessage(); //错误信息
            }
            return json_encode($result);
        }
        elseif($order_type == FEEORDER_TYPE_PARKING_FEE){
            try {
                $criteria = new CDbCriteria();
                if(isset($user_id)&&!empty($user_id)){
                    $criteria->addCondition("user_id = :user_id");
                    $criteria->params[':user_id'] = $user_id;
                }

                if(isset($merchant_id)&&!empty($merchant_id)){
                    $criteria->addCondition("merchant_id = :merchant_id");
                    $criteria->params[':merchant_id'] = $merchant_id;
                }

                if(isset($order_id)&&!empty($order_id)){
                    $criteria->addCondition("id = :order_id");
                    $criteria->params[':order_id'] = $order_id;
                }

                //检索--小区和楼栋
                if(isset($info)&&!empty($info)){
                    if(isset($info['community_id'])&&!empty($info['community_id'])){
                        $community_id = $info['community_id'];
                    }
                    if(isset($info['building_number'])&&!empty($info['building_number'])){
                        $building_number = $info['building_number'];
                    }
                    if(isset($info['user_account'])&&!empty($info['user_account'])){
                        $user_account = trim($info['user_account']);
                    }
                    if(isset($info['pay_status'])&&!empty($info['pay_status'])){
                        $pay_status = $info['pay_status'];
                    }
                    if(isset($info['time'])&&!empty($info['time'])){
                        $time = $info['time'];
                    }
                    //支付状态
                    if(isset($info['pay_status']) && !empty($info['pay_status'])){
                        $pay_status = $info['pay_status'];
                    }
                }
                if(isset($community_id)&&!empty($community_id)){
                    $criteria->addCondition("community_id = :community_id");
                    $criteria->params[':community_id'] = $community_id;
                }
                if(isset($building_number)&&!empty($building_number)){
                    $criteria->addCondition("building_number = :building_number");
                    $criteria->params[':building_number'] = $building_number;
                }
                if(isset($user_account)&&!empty($user_account)){
                    $criteria->addCondition("user_account = :user_account");
                    $criteria->params[':user_account'] = $user_account;
                }
                if(isset($pay_status)&&!empty($pay_status)){
                    $paystatus = ($pay_status == 'GJORDER_PAY_STATUS_NUPAID' ? 1:2);
                    $criteria->addCondition("pay_status = :pay_status");
                    $criteria->params[':pay_status'] = $paystatus;
                }
                //支付状态
                if(isset($pay_status) && !empty($pay_status)) {
                    $criteria->addCondition('pay_status = :pay_status');
                    $criteria->params[':pay_status'] = $pay_status;
                }
                //根据时间范围进行搜索
                if (isset($time)&&!empty($time)){
                    $Time1 = explode('-', $time);
                    //时间字段类型为timestamp
                    $Time1[0]= date('Y-m-d H-i-s',strtotime($Time1[0].' 00:00:00'));
                    $Time1[1]= date('Y-m-d H-i-s',strtotime($Time1[1].' 23:59:59'));
                    $criteria->addCondition("pay_time > :start_time");
                    $criteria->addCondition("pay_time < :end_time");
                    $criteria->params[':start_time'] = $Time1[0];
                    $criteria->params[':end_time'] = $Time1[1];
                }
                $criteria->addCondition("flag = :flag");
                $criteria->addCondition("order_type = :order_type");

                $criteria->params[':flag'] = FLAG_NO;
                $criteria->params[':order_type'] = $order_type;

                $criteria->order = "create_time desc";

                $feeOrder = FeeOrder::model()->findAll($criteria);

                $data = array();

                if (!empty($feeOrder)) {
                    foreach ($feeOrder as $k => $v) {
                        $data[$k]['id'] = $v['id'];
                        $data[$k]['user_id'] = $v['user_id'];

                        //获取业主表中的信息--楼号/门牌号
                        $proprietor = Proprietor::model()->find(
                            'user_id=:user_id',
                            array(':user_id'=>$v['user_id'])
                        );
                        $proprietorinfo=$proprietor->attributes;

                        $data[$k]['building_number'] = $proprietorinfo['building_number'];
                        $data[$k]['room_number'] = $proprietorinfo['room_number'];
                        $community_id = $proprietorinfo['community_id'];

                        //获取业主姓名/手机号/储值金额
                        $user = User::model()->find(
                            'id=:user_id',
                            array(':user_id'=>$v['user_id'])
                        );
                        $userinfo=$user->attributes;
                        $data[$k]['name']=$userinfo['name'];
                        $data[$k]['account']=$userinfo['account'];
                        $data[$k]['money']=isset($userinfo['money']) ? $userinfo['money'] : '0.00';

                        //获取小区名字
                        $community = Community::model()->find(
                            'id=:community_id',
                            array(':community_id'=>$community_id)
                        );
                        $data[$k]['community_name'] = $community['name'];

                        /**
                         * 停车费设置形式
                         * {"community_id":"9","day_price":"20","half_year_price":"1000","year_price":"2000"}
                         */

                        $data[$k]['date'] = $v['date'];
                        $data[$k]['pay_time'] = $v['pay_time'];
                        $data[$k]['parking_month_num'] = $v['parking_month_num'];
                        $data[$k]['order_money'] = $v['order_money'];
                        $data[$k]['pay_status'] = $v['pay_status'];
                        $data[$k]['order_id'] = $v['id'];
                        $data[$k]['create_time'] = $v['create_time'];
                    }
                }
                $result['status'] = APPLY_CLASS_SUCCESS;
                $result['data'] = $data;
            } catch (Exception $e) {
                $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
                $result['errMsg'] = $e->getMessage(); //错误信息
            }
            return json_encode($result);
        }
        elseif($order_type == FEEORDER_TYPE_PROPERTY_FEE){
            try {
                $criteria = new CDbCriteria();
                if(isset($user_id)&&!empty($user_id)){
                    $criteria->addCondition("user_id = :user_id");
                    $criteria->params[':user_id'] = $user_id;
                }

                if(isset($merchant_id)&&!empty($merchant_id)){
                    $criteria->addCondition("merchant_id = :merchant_id");
                    $criteria->params[':merchant_id'] = $merchant_id;
                }

                if(isset($order_id)&&!empty($order_id)){
                    $criteria->addCondition("id = :order_id");
                    $criteria->params[':order_id'] = $order_id;
                }
                //检索--小区和楼栋
                if(isset($info)&&!empty($info)){
                    if(isset($info['community_id'])&&!empty($info['community_id'])){
                        $community_id = $info['community_id'];
                    }
                    if(isset($info['building_number'])&&!empty($info['building_number'])){
                        $building_number = $info['building_number'];
                    }
                    if(isset($info['user_account'])&&!empty($info['user_account'])){
                        $user_account = trim($info['user_account']);
                    }
                    if(isset($info['pay_status'])&&!empty($info['pay_status'])){
                        $pay_status = $info['pay_status'];
                    }
                    if(isset($info['time'])&&!empty($info['time'])){
                        $time = $info['time'];
                    }
                }
                if(isset($community_id)&&!empty($community_id)){
                    $criteria->addCondition("community_id = :community_id");
                    $criteria->params[':community_id'] = $community_id;
                }
                if(isset($building_number)&&!empty($building_number)){
                    $criteria->addCondition("building_number = :building_number");
                    $criteria->params[':building_number'] = $building_number;
                }
                //根据用户手机号进行搜索
                if(isset($user_account)&&!empty($user_account)){
                    $criteria->addCondition("user_account = :user_account");
                    $criteria->params[':user_account'] = $user_account;
                }
                if(isset($pay_status)&&!empty($pay_status)){
                    $paystatus = ($pay_status == 'GJORDER_PAY_STATUS_NUPAID' ? 1:2);
                    $criteria->addCondition("pay_status = :pay_status");
                    $criteria->params[':pay_status'] = $paystatus;
                }
                //根据时间范围进行搜索
                if (isset($time)&&!empty($time)){
                    $Time1 = explode('-', $time);
                    //时间字段类型为timestamp
                    $Time1[0]= date('Y-m-d H-i-s',strtotime($Time1[0].' 00:00:00'));
                    $Time1[1]= date('Y-m-d H-i-s',strtotime($Time1[1].' 23:59:59'));
                    $criteria->addCondition("pay_time > :start_time");
                    $criteria->addCondition("pay_time < :end_time");
                    $criteria->params[':start_time'] = $Time1[0];
                    $criteria->params[':end_time'] = $Time1[1];
                }
                $criteria->addCondition("flag = :flag");
                $criteria->addCondition("order_type = :order_type");

                $criteria->params[':flag'] = FLAG_NO;
                $criteria->params[':order_type'] = $order_type;

                $criteria->order = "create_time desc";

                $feeOrder = FeeOrder::model()->findAll($criteria);

                $data = array();

                if (!empty($feeOrder)) {
                    foreach ($feeOrder as $k => $v) {
                        $data[$k]['id'] = $v['id'];
                        $data[$k]['user_id'] = $v['user_id'];

                        //获取业主表中的信息--楼号/门牌号
                        $proprietor = Proprietor::model()->find(
                            'user_id=:user_id',
                            array(':user_id'=>$v['user_id'])
                        );
                        $proprietorinfo=$proprietor->attributes;

                        $data[$k]['building_number'] = $proprietorinfo['building_number'];
                        $data[$k]['room_number'] = $proprietorinfo['room_number'];
                        $community_id = $proprietorinfo['community_id'];

                        //获取业主姓名/手机号/储值金额
                        $user = User::model()->find(
                            'id=:user_id',
                            array(':user_id'=>$v['user_id'])
                        );
                        $userinfo=$user->attributes;
                        $data[$k]['name']=$userinfo['name'];
                        $data[$k]['account']=$userinfo['account'];
                        $data[$k]['money']=isset($userinfo['money']) ? $userinfo['money'] : '0.00';

                        //获取小区名字
                        $community = Community::model()->find(
                            'id=:community_id',
                            array(':community_id'=>$community_id)
                        );
                        $data[$k]['community_name'] = $community['name'];

                        $data[$k]['date'] = $v['date'];
                        $data[$k]['pay_time'] = $v['pay_time'];
                        $data[$k]['property_fee_month_num'] = $v['property_fee_month_num'];
                        $data[$k]['order_money'] = $v['order_money'];
                        $data[$k]['pay_status'] = $v['pay_status'];
                        $data[$k]['order_id'] = $v['id'];
                        $data[$k]['create_time'] = $v['create_time'];
                    }
                }
                $result['status'] = APPLY_CLASS_SUCCESS;
                $result['data'] = $data;
            } catch (Exception $e) {
                $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
                $result['errMsg'] = $e->getMessage(); //错误信息
            }
            return json_encode($result);
        }
    }

    /**
     * @param $order_id
     * 删除订单
     */
    public function deleteOrder($order_id){
        try{
            //保存数据
            $model = FeeOrder::model()->findByPk($order_id);
            $model->flag = FLAG_YES;
            $model->last_time = date('Y-m-d H:i:s');

            if ($model->save()) {
                $result ['status'] = APPLY_CLASS_SUCCESS; // 状态码
                $result ['errMsg'] = '';
            } else {
                $result ['status'] = APPLY_CLASS_FAIL; // 状态码
                throw new Exception('数据保存失败');
            }
        }catch (Exception $e){
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }
        return json_encode($result);
    }

    /**
     * @param $agentId
     * @return string
     * 导出Excel表单
     */
    public function getDetailExcel($list, $order_type)
    {
        include 'PHPExcel/Reader/Excel2007.php';
        include 'PHPExcel/Reader/Excel5.php';
        include 'PHPExcel/IOFactory.php';
        //创建一个处理对象实例
        $objPHPExcel = new PHPExcel();
        //创建文件格式写入对象实例(其他版本格式)
        $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
        //设置当前的sheet
        $objPHPExcel->setActiveSheetIndex(0);
        //工作表设置
        $objActSheet = $objPHPExcel->getActiveSheet();
        //设置sheet名称
        $objActSheet->setTitle('智慧物业缴费表');
        $objActSheet->mergeCells('A1:P1');

        //电费
        if ($order_type == FEEORDER_TYPE_ELECTRICITY_FEE) {
            $objActSheet->setCellValue('A1', '智慧物业_电费缴纳表');
            $objActSheet->setCellValue('A2', '业主');
            $objActSheet->setCellValue('B2', '手机号');
            $objActSheet->setCellValue('C2', '小区名称');
            $objActSheet->setCellValue('D2', '楼号');
            $objActSheet->setCellValue('E2', '门牌号');
            $objActSheet->setCellValue('F2', '创建日期');
            $objActSheet->setCellValue('G2', '用电量（度）');
            $objActSheet->setCellValue('H2', '月份');
            $objActSheet->setCellValue('I2', '订单金额');
            $objActSheet->setCellValue('J2', '支付状态');
            //设置列宽
            $objActSheet->getColumnDimension('A')->setWidth(20);
            $objActSheet->getColumnDimension('B')->setWidth(20);
            $objActSheet->getColumnDimension('C')->setWidth(20);
            $objActSheet->getColumnDimension('D')->setWidth(20);
            $objActSheet->getColumnDimension('E')->setWidth(30);
            $objActSheet->getColumnDimension('F')->setWidth(20);
            $objActSheet->getColumnDimension('G')->setWidth(20);
            $objActSheet->getColumnDimension('H')->setWidth(20);
            $objActSheet->getColumnDimension('I')->setWidth(20);
            $objActSheet->getColumnDimension('J')->setWidth(20);
            //数据添加
            $i = 3;
            foreach ($list as $k => $v) {
                $objActSheet->setCellValue('A'.$i , $v['name']);//业主
                $objActSheet->setCellValue('B'.$i , $v['account']);//手机号
                $objActSheet->setCellValue('C'.$i , $v['community_name']);//小区名称
                $objActSheet->setCellValue('D'.$i , $v['building_number']);//楼号
                $objActSheet->setCellValue('E'.$i , $v['room_number']);//门牌号
                $objActSheet->setCellValue('F'.$i , $v['create_time']);//日期
                $objActSheet->setCellValue('G'.$i , $v['electricity']);//用电量
                $objActSheet->setCellValue('H'.$i , substr($v['date'],0,7));  //月份
                $objActSheet->setCellValue('I'.$i , $v['order_money']);//订单金额
                $objActSheet->setCellValue('J'.$i , $GLOBALS['ORDER_STATUS_PAY'][$v['pay_status']]);//支付状态

                $i++;
            }
        }
        //水费
        if ($order_type == FEEORDER_TYPE_WATER_FEE) {
            $objActSheet->setCellValue('A1', '智慧物业_水费缴纳表');
            $objActSheet->setCellValue('A2', '业主');
            $objActSheet->setCellValue('B2', '手机号');
            $objActSheet->setCellValue('C2', '小区名称');
            $objActSheet->setCellValue('D2', '楼号');
            $objActSheet->setCellValue('E2', '门牌号');
            $objActSheet->setCellValue('F2', '创建日期');
            $objActSheet->setCellValue('G2', '用水量（吨）');
            $objActSheet->setCellValue('H2', '每吨水单价');
            $objActSheet->setCellValue('I2', '月份');
            $objActSheet->setCellValue('J2', '订单金额');
            $objActSheet->setCellValue('K2', '支付状态');
            //设置列宽
            $objActSheet->getColumnDimension('A')->setWidth(20);
            $objActSheet->getColumnDimension('B')->setWidth(20);
            $objActSheet->getColumnDimension('C')->setWidth(20);
            $objActSheet->getColumnDimension('D')->setWidth(20);
            $objActSheet->getColumnDimension('E')->setWidth(30);
            $objActSheet->getColumnDimension('F')->setWidth(20);
            $objActSheet->getColumnDimension('G')->setWidth(20);
            $objActSheet->getColumnDimension('H')->setWidth(20);
            $objActSheet->getColumnDimension('I')->setWidth(20);
            $objActSheet->getColumnDimension('J')->setWidth(20);
            $objActSheet->getColumnDimension('K')->setWidth(20);
            //数据添加
            $i = 3;
            foreach ($list as $k => $v) {
                $objActSheet->setCellValue('A'.$i , $v['name']);//业主
                $objActSheet->setCellValue('B'.$i , $v['account']);//手机号
                $objActSheet->setCellValue('C'.$i , $v['community_name']);//小区名称
                $objActSheet->setCellValue('D'.$i , $v['building_number']);//楼号
                $objActSheet->setCellValue('E'.$i , $v['room_number']);//门牌号
                $objActSheet->setCellValue('F'.$i , $v['create_time']);//日期
                $objActSheet->setCellValue('G'.$i , $v['water_ton']);//用水量
                $objActSheet->setCellValue('H'.$i , $v['water_price']);//每吨水单价
                $objActSheet->setCellValue('I'.$i , substr($v['date'],0,7));  //月份
                $objActSheet->setCellValue('J'.$i , $v['order_money']);//订单金额
                $objActSheet->setCellValue('K'.$i , $GLOBALS['ORDER_STATUS_PAY'][$v['pay_status']]);//支付状态

                $i++;
            }
        }
        //停车费
        if ($order_type == FEEORDER_TYPE_PARKING_FEE) {
            $objActSheet->setCellValue('A1', '智慧物业_停车费缴纳表');
            $objActSheet->setCellValue('A2', '业主');
            $objActSheet->setCellValue('B2', '手机号');
            $objActSheet->setCellValue('C2', '小区名称');
            $objActSheet->setCellValue('D2', '楼号');
            $objActSheet->setCellValue('E2', '门牌号');
            $objActSheet->setCellValue('F2', '月数');
            $objActSheet->setCellValue('G2', '停车费');
            $objActSheet->setCellValue('H2', '支付时间');
            $objActSheet->setCellValue('I2', '支付状态');

            //设置列宽
            $objActSheet->getColumnDimension('A')->setWidth(20);
            $objActSheet->getColumnDimension('B')->setWidth(20);
            $objActSheet->getColumnDimension('C')->setWidth(20);
            $objActSheet->getColumnDimension('D')->setWidth(20);
            $objActSheet->getColumnDimension('E')->setWidth(30);
            $objActSheet->getColumnDimension('F')->setWidth(20);
            $objActSheet->getColumnDimension('G')->setWidth(20);
            $objActSheet->getColumnDimension('H')->setWidth(20);
            $objActSheet->getColumnDimension('I')->setWidth(20);
            //数据添加
            $i = 3;
            foreach ($list as $k => $v) {
                $objActSheet->setCellValue('A'.$i , $v['name']);//业主
                $objActSheet->setCellValue('B'.$i , $v['account']);//手机号
                $objActSheet->setCellValue('C'.$i , $v['community_name']);//小区名称
                $objActSheet->setCellValue('D'.$i , $v['building_number']);//楼号
                $objActSheet->setCellValue('E'.$i , $v['room_number']);//门牌号
                $objActSheet->setCellValue('F'.$i , $v['parking_month_num']);//月数
                $objActSheet->setCellValue('G'.$i , $v['order_money']);//停车费
                $objActSheet->setCellValue('H'.$i , $v['pay_time']);  //支付时间
                $objActSheet->setCellValue('I'.$i , $GLOBALS['ORDER_STATUS_PAY'][$v['pay_status']]);//支付状态

                $i++;
            }
        }
        //物业费
        if ($order_type == FEEORDER_TYPE_PROPERTY_FEE) {
            $objActSheet->setCellValue('A1', '智慧物业_物业费缴纳表');
            $objActSheet->setCellValue('A2', '业主');
            $objActSheet->setCellValue('B2', '手机号');
            $objActSheet->setCellValue('C2', '小区名称');
            $objActSheet->setCellValue('D2', '楼号');
            $objActSheet->setCellValue('E2', '门牌号');
            $objActSheet->setCellValue('F2', '创建日期');
            $objActSheet->setCellValue('G2', '物业费');
            $objActSheet->setCellValue('H2', '年份');
            $objActSheet->setCellValue('I2', '支付状态');

            //设置列宽
            $objActSheet->getColumnDimension('A')->setWidth(20);
            $objActSheet->getColumnDimension('B')->setWidth(20);
            $objActSheet->getColumnDimension('C')->setWidth(20);
            $objActSheet->getColumnDimension('D')->setWidth(20);
            $objActSheet->getColumnDimension('E')->setWidth(30);
            $objActSheet->getColumnDimension('F')->setWidth(20);
            $objActSheet->getColumnDimension('G')->setWidth(20);
            $objActSheet->getColumnDimension('H')->setWidth(20);
            $objActSheet->getColumnDimension('I')->setWidth(20);
            //数据添加
            $i = 3;
            foreach ($list as $k => $v) {
                $objActSheet->setCellValue('A'.$i , $v['name']);//业主
                $objActSheet->setCellValue('B'.$i , $v['account']);//手机号
                $objActSheet->setCellValue('C'.$i , $v['community_name']);//小区名称
                $objActSheet->setCellValue('D'.$i , $v['building_number']);//楼号
                $objActSheet->setCellValue('E'.$i , $v['room_number']);//门牌号
                $objActSheet->setCellValue('F'.$i , $v['create_time']);//日期
                $objActSheet->setCellValue('G'.$i , $v['order_money']);//停车费
                $objActSheet->setCellValue('H'.$i , substr($v['date'],0,4));  //年份
                $objActSheet->setCellValue('I'.$i , $GLOBALS['ORDER_STATUS_PAY'][$v['pay_status']]);//支付状态

                $i++;
            }
        }
        //垂直/水平居中
        $objActSheet->getStyle('A2:P'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objActSheet->getStyle('A2:P'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objActSheet->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        //定义文件名
        $filename = '智慧物业缴费表' . date('YmdHis');
        $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
        $this->outPut($filename);
        $objWriter->save("php://output");
    }

    /**
     * (私有方法)
     * 到浏览器  浏览器下载excel
     */
    private function outPut($filename){
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header("Content-Disposition:attachment;filename={$filename}.xls");
        header("Content-Transfer-Encoding:binary");
    }

    /**
     * @param $order_type
     * @throws Exception
     * 导出EXCEL模板
     */
    public function getExcelModels($order_type)
    {
        include 'PHPExcel/Reader/Excel2007.php';
        include 'PHPExcel/Reader/Excel5.php';
        include 'PHPExcel/IOFactory.php';
        //创建一个处理对象实例
        $objPHPExcel = new PHPExcel();
        //创建文件格式写入对象实例(其他版本格式)
        $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
        //设置当前的sheet
        $objPHPExcel->setActiveSheetIndex(0);
        //工作表设置
        $objActSheet = $objPHPExcel->getActiveSheet();
        //设置sheet名称
        $objActSheet->setTitle('智慧物业缴费表');
        $objActSheet->mergeCells('A1:P1');

        //电费
        if ($order_type == FEEORDER_TYPE_ELECTRICITY_FEE) {
            $objActSheet->setCellValue('A1', '智慧物业_电费缴纳表');
            $objActSheet->setCellValue('A2', '手机号');
            $objActSheet->setCellValue('B2', '小区名称');
            $objActSheet->setCellValue('C2', '月份');
            $objActSheet->setCellValue('D2', '本月用电量');
            $objActSheet->setCellValue('E2', '本月电费');
            //设置列宽
            $objActSheet->getColumnDimension('A')->setWidth(20);
            $objActSheet->getColumnDimension('B')->setWidth(20);
            $objActSheet->getColumnDimension('C')->setWidth(20);
            $objActSheet->getColumnDimension('D')->setWidth(20);
            $objActSheet->getColumnDimension('E')->setWidth(30);
        }
        //水费
        if ($order_type == FEEORDER_TYPE_WATER_FEE) {
            $objActSheet->setCellValue('A1', '智慧物业_水费缴纳表');
            $objActSheet->setCellValue('A2', '手机号');
            $objActSheet->setCellValue('B2', '小区名称');
            $objActSheet->setCellValue('C2', '月份');
            $objActSheet->setCellValue('D2', '用水量（吨）');
            $objActSheet->setCellValue('E2', '本月水费');
            //设置列宽
            $objActSheet->getColumnDimension('A')->setWidth(20);
            $objActSheet->getColumnDimension('B')->setWidth(20);
            $objActSheet->getColumnDimension('C')->setWidth(20);
            $objActSheet->getColumnDimension('D')->setWidth(20);
            $objActSheet->getColumnDimension('E')->setWidth(30);
        }
        //停车费
        if ($order_type == FEEORDER_TYPE_PARKING_FEE) {
            $objActSheet->setCellValue('A1', '智慧物业_停车费缴纳表');
            $objActSheet->setCellValue('A2', '手机号');
            $objActSheet->setCellValue('B2', '小区名称');
            $objActSheet->setCellValue('C2', '月数');
            $objActSheet->setCellValue('D2', '停车费');
            //设置列宽
            $objActSheet->getColumnDimension('A')->setWidth(20);
            $objActSheet->getColumnDimension('B')->setWidth(20);
            $objActSheet->getColumnDimension('C')->setWidth(20);
            $objActSheet->getColumnDimension('D')->setWidth(20);
        }
        //物业费
        if ($order_type == FEEORDER_TYPE_PROPERTY_FEE) {
            $objActSheet->setCellValue('A1', '智慧物业_物业费缴纳表');
            $objActSheet->setCellValue('A2', '手机号');
            $objActSheet->setCellValue('B2', '小区名称');
            $objActSheet->setCellValue('C2', '年份');
            $objActSheet->setCellValue('D2', '物业费');
            //设置列宽
            $objActSheet->getColumnDimension('A')->setWidth(20);
            $objActSheet->getColumnDimension('B')->setWidth(20);
            $objActSheet->getColumnDimension('C')->setWidth(20);
            $objActSheet->getColumnDimension('D')->setWidth(20);
        }
        //垂直/水平居中
        $objActSheet->getStyle('A2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objActSheet->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objActSheet->getStyle('B2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objActSheet->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objActSheet->getStyle('C2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objActSheet->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objActSheet->getStyle('D2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objActSheet->getStyle('D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objActSheet->getStyle('E2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objActSheet->getStyle('E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objActSheet->getStyle('F2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objActSheet->getStyle('F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objActSheet->getStyle('G2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objActSheet->getStyle('G2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objActSheet->getStyle('H2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objActSheet->getStyle('H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objActSheet->getStyle('I2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objActSheet->getStyle('I2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objActSheet->getStyle('J2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objActSheet->getStyle('J2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objActSheet->getStyle('k2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objActSheet->getStyle('k2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objActSheet->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        //定义文件名
        $filename = '智慧物业缴费表' . date('YmdHis');
        $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
        $this->outPut($filename);
        $objWriter->save("php://output");
    }


}