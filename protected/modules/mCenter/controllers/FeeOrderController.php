<?php
/**
 * Created by PhpStorm.
 * User: cheng
 * Date: 2016/6/28
 * Time: 13:44
 * 智慧物业
 *  费用订单
 */
class FeeOrderController extends mCenterController
{
    public $layout='newMain';

    /**
     * 电费列表
     */
    public function actionEFeeOrderList()
    {
        $order_type = FEEORDER_TYPE_ELECTRICITY_FEE;
        //获取商户id
        $merchant_id = Yii::app()->session['merchant_id'];
        $feeorderC = new FeeOrderC();
        //查询电费信息
        $result = json_decode($feeorderC->getFeeOrderList($order_type,$merchant_id), true);
        //小区信息
        $communityC = new CommunityC();
        $result_community = json_decode($communityC->getCommunityAllList($merchant_id),true);
        if ($result['status'] == APPLY_CLASS_SUCCESS) {
            if (isset($result['data'])) {
                $list = $result['data'];
            }
        }
        
        if ($result_community['status'] == APPLY_CLASS_SUCCESS) {
            if (isset($result_community['data'])) {
                $community_list = $result_community['data'];
            }
        }

        $this->render('electricityFeeOrderList',array(
            'fee_Order_List'=>$list,
            'community_list'=>$community_list,
            'pay_status' => $GLOBALS['ORDER_STATUS_PAY'],
            'pages'=>$feeorderC->page

        ));
    }

    /**
     *电费列表—搜索条件
     */
    public function actionEFeeOrderSelect()
    {
        $order_type = FEEORDER_TYPE_ELECTRICITY_FEE;
        //获取商户id
        $merchant_id = Yii::app()->session['merchant_id'];
        $feeorderC = new FeeOrderC();
        if(isset($_GET)&&!empty($_GET)){

            $info=array();
            //按小区名称搜索
            if(!empty($_GET['community_id'])){
                $info['community_id'] = $_GET['community_id'];
            }
            //按小区楼号搜索
            if(!empty($_GET['building_number'])){
                $info['building_number'] = $_GET['building_number'];
            }
            //按手机号搜索
            if(!empty($_GET['user_account'])){
                $info['user_account'] = $_GET['user_account'];
            }
            //按订单日期搜索
            if(!empty($_GET['time'])){
                $info['time'] = $_GET['time'];
            }
            //按支付状态搜索
            if (!empty($_GET['pay_status'])){
                $info['pay_status'] = $_GET['pay_status'];
            }

            $feeorderC = new FeeOrderC();
            //$merchant_id,$verify_status,$community_name,$building_number,$room_number
            $result = json_decode($feeorderC->getFeeOrderList($order_type,$merchant_id,'','',$info),true);
            //小区信息
            $communityC = new CommunityC();
            $result_community = json_decode($communityC->getCommunityAllList($merchant_id),true);
            if ($result['status'] == APPLY_CLASS_SUCCESS) {
                if (isset($result['data'])) {
                    $list = $result['data'];
                }
            }
            if ($result_community['status'] == APPLY_CLASS_SUCCESS) {
                if (isset($result_community['data'])) {
                    $community_list = $result_community['data'];
                }
            }

            $this->render('electricityFeeOrderList',array(
                'fee_Order_List'=>$list,
                'community_list'=>$community_list,
                'pay_status' => $GLOBALS['ORDER_STATUS_PAY'],
                'pages'=>$feeorderC->page

            ));
        }
    }

    /**
     * 电费详情页
     */
    public function actionEFeeOrderDetail($order_id,$type){
        $feeorderC = new FeeOrderC();
        $communityC = new CommunityC();
        $result = json_decode($feeorderC->getFeeOrderInfo($order_id), true);

        if ($result['status'] == APPLY_CLASS_SUCCESS) {
                $list = $result['data'];
        }
        //获取小区详情
        $community_detail = $communityC -> getCommunityInfo($result['community_id']);

        if(isset($type)&&$type==1){
            $this->render('tariff-detail',array(
                'fee_Order_List'=>$list,
                'community_detail'=>$community_detail,
                //'pages'=>$feeorderC->page,
            ));
        }else{
            $this->render('nonPeakElecFeeDetail',array(
                'fee_Order_List'=>$list,
                'community_detail'=>$community_detail,
                //'pages'=>$feeorderC->page,
            ));
        }
    }

    /**
     * 水费列表
     */
    public function actionWFeeOrderList(){
        //查询水费信息
        $order_type = FEEORDER_TYPE_WATER_FEE;
        $user_id ='';
        $feeorderC = new FeeOrderC();
        //获取商户id
        $merchant_id = Yii::app()->session['merchant_id'];

        $result = json_decode($feeorderC->getFeeOrderList($order_type,$merchant_id,$user_id), true);

        //商户信息
        $communityC = new CommunityC();
        $result_community = json_decode($communityC->getCommunityAllList($merchant_id),true);
        if ($result['status'] == APPLY_CLASS_SUCCESS) {
            if (isset($result['data'])) {
                $list = $result['data'];
            }
        }
        if ($result_community['status'] == APPLY_CLASS_SUCCESS) {
            if (isset($result_community['data'])) {
                $community_list = $result_community['data'];
            }
        }
        $this->render('waterFeeOrderList',array(
            'fee_Order_List'=>$list,
            'community_list'=>$community_list,
            'pay_status' => $GLOBALS['ORDER_STATUS_PAY'],
            'pages'=>$feeorderC->page
        ));
    }

    /**
     * 水费列表—搜索条件
     */
    public function actionWFeeOrderSelect()
    {
        $order_type = FEEORDER_TYPE_WATER_FEE;
        //获取商户id
        $merchant_id = Yii::app()->session['merchant_id'];
        $feeorderC = new FeeOrderC();
        if(isset($_GET)&&!empty($_GET)){

            $info=array();
            if(!empty($_GET['community_id'])){
                $info['community_id'] = $_GET['community_id'];

            }
            if(!empty($_GET['building_number'])){
                $info['building_number'] = $_GET['building_number'];
            }
            if(!empty($_GET['user_account'])){
                $info['user_account'] = $_GET['user_account'];
            }
            if(!empty($_GET['time'])){
                $info['time'] = $_GET['time'];
            }
            //按支付状态搜索
            if (!empty($_GET['pay_status'])){
                $info['pay_status'] = $_GET['pay_status'];
            }

            $feeorderC = new FeeOrderC();
            //$merchant_id,$verify_status,$community_name,$building_number,$room_number
            $result = json_decode($feeorderC->getFeeOrderList($order_type,$merchant_id,'','',$info),true);
            //小区信息
            $communityC = new CommunityC();
            $result_community = json_decode($communityC->getCommunityAllList($merchant_id),true);
            if ($result['status'] == APPLY_CLASS_SUCCESS) {
                if (isset($result['data'])) {
                    $list = $result['data'];
                }
            }
            if ($result_community['status'] == APPLY_CLASS_SUCCESS) {
                if (isset($result_community['data'])) {
                    $community_list = $result_community['data'];
                }
            }
            $this->render('waterFeeOrderList',array(
                'fee_Order_List'=>$list,
                'community_list'=>$community_list,
                'pay_status' => $GLOBALS['ORDER_STATUS_PAY'],
                'pages'=>$feeorderC->page,

            ));
        }
    }

    /**
     * 水费详情页
     */
    public function actionWFeeOrderDetail($order_id){
        $feeorderC = new FeeOrderC();
        $communityC = new CommunityC();
        $result = json_decode($feeorderC->getFeeOrderInfo($order_id), true);

        if ($result['status'] == APPLY_CLASS_SUCCESS) {
            $list = $result['data'];
        }
        //获取小区详情
        $community_detail = $communityC -> getCommunityInfo($result['community_id']);

            $this->render('water-detail',array(
                'fee_Order_List'=>$list,
                'community_detail'=>$community_detail,
                //'pages'=>$feeorderC->page,
            ));
    }

    /**
     * 停车费列表
     */
    public function actionParkingFeeOrderList(){
        //查询停车费信息
        $order_type = FEEORDER_TYPE_PARKING_FEE;
        $user_id ='';
        //获取商户id
        $merchant_id = Yii::app()->session['merchant_id'];
        $feeorderC = new FeeOrderC();
        $result = json_decode($feeorderC->getFeeOrderList($order_type,$merchant_id,$user_id), true);

        //商户信息
        $communityC = new CommunityC();
        $result_community = json_decode($communityC->getCommunityAllList($merchant_id),true);
        if ($result['status'] == APPLY_CLASS_SUCCESS) {
            if (isset($result['data'])) {
                $list = $result['data'];
            }
        }
        if ($result_community['status'] == APPLY_CLASS_SUCCESS) {
            if (isset($result_community['data'])) {
                $community_list = $result_community['data'];
            }
        }
        $this->render('parkingFeeOrderList',array(
            'fee_Order_List'=>$list,
            'community_list'=>$community_list,
            'pay_status' => $GLOBALS['ORDER_STATUS_PAY'],
            'pages'=>$feeorderC->page
        ));
    }

    /**
     * 停车费列表—搜索条件
     */
    public function actionParkingFeeOrderSelect()
    {
        $order_type = FEEORDER_TYPE_PARKING_FEE;
        //获取商户id
        $merchant_id = Yii::app()->session['merchant_id'];
        $feeorderC = new FeeOrderC();
        if(isset($_GET)&&!empty($_GET)){
            $info=array();
            if(!empty($_GET['community_id'])){
                $info['community_id'] = $_GET['community_id'];

            }
            if(!empty($_GET['building_number'])){
                $info['building_number'] = $_GET['building_number'];
            }
            if(!empty($_GET['user_account'])){
                $info['user_account'] = $_GET['user_account'];
            }
            if(!empty($_GET['pay_status'])){
                $info['pay_status'] = $_GET['pay_status'];
            }
            if(!empty($_GET['time'])){
                $info['time'] = $_GET['time'];
            }
            //按支付状态搜索
            if (!empty($_GET['pay_status'])){
                $info['pay_status'] = $_GET['pay_status'];
            }

            $feeorderC = new FeeOrderC();
            //$merchant_id,$verify_status,$community_name,$building_number,$room_number
            $result = json_decode($feeorderC->getFeeOrderList($order_type,$merchant_id,'','',$info),true);
            //小区信息
            $communityC = new CommunityC();
            $result_community = json_decode($communityC->getCommunityAllList($merchant_id),true);
            if ($result['status'] == APPLY_CLASS_SUCCESS) {
                if (isset($result['data'])) {
                    $list = $result['data'];
                }
            }
            if ($result_community['status'] == APPLY_CLASS_SUCCESS) {
                if (isset($result_community['data'])) {
                    $community_list = $result_community['data'];
                }
            }
            $this->render('parkingFeeOrderList',array(
                'fee_Order_List'=>$list,
                'community_list'=>$community_list,
                'pay_status' => $GLOBALS['ORDER_STATUS_PAY'],
                'pages'=>$feeorderC->page

            ));
        }
    }

    /**
     * 停车费详情页
     */
    public function actionParkingFeeOrderDetail(){
        //查询停车费详情
        $order_type = FEEORDER_TYPE_PARKING_FEE;
        $user_id ='';
        $merchant_id = '';
        if(isset($_GET)&&!empty($_GET)){
            $order_id=$_GET['order_id'];
        }
        $feeorderC = new FeeOrderC();
        $result = json_decode($feeorderC->getFeeOrderList($order_type,$merchant_id,$user_id,$order_id,''), true);

        if ($result['status'] == APPLY_CLASS_SUCCESS) {
            if (isset($result['data'])) {
                $list = $result['data'];
            }
        }

        $res = json_decode($feeorderC->getFeeOrderInfo($order_id), true);
        if ($res['status'] == APPLY_CLASS_SUCCESS) {
            $info = $res['data'];
        }

        $this->render('parkingDetail',array(
            'parking_Detail'=>$list,
            'car_Detail'=>$info,
        ));
    }

    /**
     * 停车费/物业费—确认支付状态
     */
    public function actionGetFeeStatus() {
        if (isset($_GET['order_id']) && $_GET['order_id']) {
            $order_id = $_GET['order_id'];
        }

        if (isset($_GET['order_type']) && $_GET['order_type']) {
            $order_type = $_GET['order_type'];
        }
        $feeorderC = new FeeOrderC();
        $result = json_decode($feeorderC -> editFeeOrderPayStatus($order_id, $order_type), true);
        if ($result['status'] == APPLY_CLASS_SUCCESS) {
            if (isset($_GET['goUrl']) && $_GET['goUrl']) {
                $this -> redirect($_GET['goUrl']);
            }
        }
    }

    /**
     * 物业费列表
     */
    public function actionPropertyFeeOrderList(){
        //查询物业费信息
        $order_type = FEEORDER_TYPE_PROPERTY_FEE;
        $user_id ='';
        //获取商户id
        $merchant_id = Yii::app()->session['merchant_id'];
        $feeorderC = new FeeOrderC();

        if(isset($_GET)){
            $info = $_GET;
        }
        $result = json_decode($feeorderC->getFeeOrderList($order_type,$merchant_id,'','',$info),true);

        //商户信息
        $communityC = new CommunityC();
        $result_community = json_decode($communityC->getCommunityAllList($merchant_id),true);
        if ($result['status'] == APPLY_CLASS_SUCCESS) {
            if (isset($result['data'])) {
                $list = $result['data'];
            }
        }
        if ($result_community['status'] == APPLY_CLASS_SUCCESS) {
            if (isset($result_community['data'])) {
                $community_list = $result_community['data'];
            }
        }
        $this->render('propertyFeeOrderList',array(
            'fee_Order_List'=>$list,
            'community_list'=>$community_list,
            'pay_status' => $GLOBALS['ORDER_STATUS_PAY'],
            'pages'=>$feeorderC->page
        ));
    }

    /**
     * 物业费列表—搜索条件
     */
    public function actionPropertyFeeOrderSelect()
    {
        $order_type = FEEORDER_TYPE_PROPERTY_FEE;
        //获取商户id
        $merchant_id = Yii::app()->session['merchant_id'];
        $feeorderC = new FeeOrderC();
        if(isset($_GET)&&!empty($_GET)){

            $info=array();
            if(!empty($_GET['community_id'])){
                $info['community_id'] = $_GET['community_id'];

            }
            if(!empty($_GET['building_number'])){
                $info['building_number'] = $_GET['building_number'];
            }
            if(!empty($_GET['user_account'])){
                $info['user_account'] = $_GET['user_account'];
            }
            if(!empty($_GET['pay_status'])){
                $info['pay_status'] = $_GET['pay_status'];
            }
            if(!empty($_GET['time'])){
                $info['time'] = $_GET['time'];
            }
            $feeorderC = new FeeOrderC();
            //$merchant_id,$verify_status,$community_name,$building_number,$room_number
            $result = json_decode($feeorderC->getFeeOrderList($order_type,$merchant_id,'','',$info),true);
            //var_dump($result_info);exit;
            //小区信息
            $communityC = new CommunityC();
            $result_community = json_decode($communityC->getCommunityAllList($merchant_id),true);
            if ($result['status'] == APPLY_CLASS_SUCCESS) {
                if (isset($result['data'])) {
                    $list = $result['data'];
                }
            }
            if ($result_community['status'] == APPLY_CLASS_SUCCESS) {
                if (isset($result_community['data'])) {
                    $community_list = $result_community['data'];
                }
            }
            $this->render('propertyFeeOrderList',array(
                'fee_Order_List'=>$list,
                'community_list'=>$community_list,
                'pay_status' => $GLOBALS['ORDER_STATUS_PAY'],
                'pages'=>$feeorderC->page

            ));
        }
    }

    /**
     * 物业费详情页
     */
    public function actionPropertyFeeOrderDetail(){
        //查询停车费详情
        $order_type = FEEORDER_TYPE_PROPERTY_FEE;
        $user_id ='';
        $merchant_id = '';
        if(isset($_GET)&&!empty($_GET)){
            $order_id=$_GET['order_id'];
        }
        $feeorderC = new FeeOrderC();
        $result = json_decode($feeorderC->getFeeOrderList($order_type,$merchant_id,$user_id,$order_id,''), true);
        if ($result['status'] == APPLY_CLASS_SUCCESS) {
            if (isset($result['data'])) {
                $list = $result['data'];
            }
        }

        $this->render('propertyDetail',array(
            'property_Detail'=>$list['0'],
        ));
    }

    /**
     * @param $order_id
     * 删除订单
     */
    public function actionDeleteOrder($order_id,$order_type){
        $feeorderC = new FeeOrderC();

        $result = json_decode($feeorderC->deleteOrder($order_id), true);

        if ($result['status'] == APPLY_CLASS_SUCCESS) {
            if ($order_type == FEEORDER_TYPE_ELECTRICITY_FEE){
                $this->redirect('EFeeOrderList');
            }
            if ($order_type == FEEORDER_TYPE_WATER_FEE){
                $this->redirect('WFeeOrderList');
            }
            if ($order_type == FEEORDER_TYPE_PARKING_FEE){
                $this->redirect('ParkingFeeOrderList');
            }
            if ($order_type == FEEORDER_TYPE_PROPERTY_FEE){
                $this->redirect('PropertyFeeOrderList');
            }
        }
    }

    /**
     * 导出EXCEL
     */
    public function actionGetExcel() {

        if (isset($_GET['order_type']) && $_GET['order_type']) {
            $order_type = $_GET['order_type'];
        }
        //获取商户id
        $merchant_id = Yii::app()->session['merchant_id'];
        $feeorderC = new FeeOrderC();

        $info=array();
        //按小区名称搜索
        if(!empty($_GET['community_id'])){
            $info['community_id'] = $_GET['community_id'];
        }
        //按小区楼号搜索
        if(!empty($_GET['building_number'])){
            $info['building_number'] = $_GET['building_number'];
        }
        //按手机号搜索
        if(!empty($_GET['user_account'])){
            $info['user_account'] = $_GET['user_account'];
        }
        //按订单日期搜索
        if(!empty($_GET['time'])){
            $info['time'] = $_GET['time'];
        }
        //按支付状态搜索
        if (!empty($_GET['pay_status'])){
            $info['pay_status'] = $_GET['pay_status'];
        }

        //查询订单信息
        $result = json_decode($feeorderC->getFeeOrderExcel($order_type,$merchant_id,'','',$info), true);

        //小区信息
        $communityC = new CommunityC();
        $result_community = json_decode($communityC->getCommunityList($merchant_id),true);

        if ($result['status'] == APPLY_CLASS_SUCCESS) {
            if (isset($result['data'])) {
                $list = $result['data'];
            }
        }
        if ($result_community['status'] == APPLY_CLASS_SUCCESS) {
            if (isset($result_community['data'])) {
                $community_list = $result_community['data'];
            }
        }
        $feeorderC -> getDetailExcel($list,$order_type);
    }

    /**
     * 导入电费Excel
     */
    public function actionImportData() {
        set_time_limit(0);
        $merchant_id = Yii::app()->session['merchant_id'];
        $fileName = IMG_BASE_PATH.WY_EXCEL_FOLDER.'/source/'.$_GET['fileName'];

        Yii::import('application.extensions.excel.*');
        include 'PHPExcel/Reader/Excel2007.php';//引入PHPExcel文件
        include 'PHPExcel/Reader/Excel5.php';
        include 'PHPExcel/IOFactory.php';

        $PHPReader = new PHPExcel_Reader_Excel5();//2003版本
        //打开excel文件
        $excel = $PHPReader -> load($fileName);
        //读取excel文件第一个工作表内容
        $firstSheet = $excel -> getSheet(0);
        //获取excel表行数
        $maxRow = $firstSheet -> getHighestRow();

        for($currentRow = 3;$currentRow <= $maxRow ;$currentRow ++) { //从第一行开始读取数据
            $currentColomn = 'A'; //读取第一列数据
            $currentColomn1 = 'B'; //读取第二列数据
            $currentColomn2 = 'C'; //读取第三列数据
            $currentColomn3 = 'D'; //读取第四列数据
            $currentColomn4 = 'E'; //读取第五列数据

            $account= $firstSheet -> getCell($currentColomn.$currentRow) -> getValue(); //赋值A1,A2,A3...
            $community = $firstSheet -> getCell($currentColomn1.$currentRow) -> getValue(); //赋值B1,B2,B3...
            $date = $firstSheet -> getCell($currentColomn2.$currentRow) -> getValue();
            $electricity = $firstSheet -> getCell($currentColomn3.$currentRow) -> getValue();
            $pay_money = $firstSheet -> getCell($currentColomn4.$currentRow) -> getValue();

            //判断手机号是否已经注册
            $ckUser = User::model()->find('merchant_id = :merchant_id and account = :account', array(':account' => $account, 'merchant_id' => $merchant_id));
            $communityM = Community::model()->find('merchant_id = :merchant_id and name = :name', array(':merchant_id' => $merchant_id, ':name' => $community));
            if (!empty($ckUser) && !empty($communityM)) {
                $date = date('Y-m-d', strtotime($date));

                $feeOrderC = new FeeOrderC();
                $model = new FeeOrder();
                $model -> merchant_id = $merchant_id;
                $model -> user_id = $ckUser->id; //把$name存到数据库
                $model -> user_account = $account; //把$tel存到数据库
                $model -> community_id = $communityM->id;
                $model -> date = $date;
                $model -> order_type = FEEORDER_TYPE_ELECTRICITY_FEE;
                $model -> order_no = $feeOrderC->getOrderNo();
                $model -> electricity = $electricity;
                $model -> order_money = $pay_money; //把$order_money存到数据库
                $model -> pay_money = $pay_money;
                $model -> flag = 1; //删除标志位，1
                $model->create_time = new CDbExpression('now()'); //创建时间
                if($model->save()){
                    echo 'successs';
                } else {
                    print_r($model->getErrors());
                }
            }
        }
    }

    /**
     * @throws CException
     * @throws Exception
     * 导入水费Excel
     */
    public function actionImportData1() {
        set_time_limit(0);
        $merchant_id = Yii::app()->session['merchant_id'];
        $fileName = IMG_BASE_PATH.WY_EXCEL_FOLDER.'/source/'.$_GET['fileName'];

        Yii::import('application.extensions.excel.*');
        include 'PHPExcel/Reader/Excel2007.php';//引入PHPExcel文件
        include 'PHPExcel/Reader/Excel5.php';
        include 'PHPExcel/IOFactory.php';

        $PHPReader = new PHPExcel_Reader_Excel5();//2003版本
        //打开excel文件
        $excel = $PHPReader -> load($fileName);
        //读取excel文件第一个工作表内容
        $firstSheet = $excel -> getSheet(0);
        //获取excel表行数
        $maxRow = $firstSheet -> getHighestRow();

        for($currentRow = 3;$currentRow <= $maxRow ;$currentRow ++) { //从第一行开始读取数据
            $currentColomn = 'A'; //读取第一列数据
            $currentColomn1 = 'B'; //读取第二列数据
            $currentColomn2 = 'C'; //读取第三列数据
            $currentColomn3 = 'D'; //读取第四列数据
            $currentColomn4 = 'E'; //读取第五列数据

            $account= $firstSheet -> getCell($currentColomn.$currentRow) -> getValue(); //赋值A1,A2,A3...
            $community = $firstSheet -> getCell($currentColomn1.$currentRow) -> getValue(); //赋值B1,B2,B3...
            $date = $firstSheet -> getCell($currentColomn2.$currentRow) -> getValue();
            $water_ton = $firstSheet -> getCell($currentColomn3.$currentRow) -> getValue();
            $pay_money = $firstSheet -> getCell($currentColomn4.$currentRow) -> getValue();

            //判断手机号是否已经注册
            $ckUser = User::model()->find('merchant_id = :merchant_id and account = :account', array(':account' => $account, 'merchant_id' => $merchant_id));
            $communityM = Community::model()->find('merchant_id = :merchant_id and name = :name', array(':merchant_id' => $merchant_id, ':name' => $community));
            if (!empty($ckUser) && !empty($communityM)) {
                $date = date('Y-m-d', strtotime($date));

                $feeOrderC = new FeeOrderC();
                $model = new FeeOrder();
                $model -> user_id = $ckUser->id; //把$name存到数据库
                $model -> merchant_id = $merchant_id;
                $model -> user_account = $account; //把$tel存到数据库
                $model -> community_id = $communityM->id;
                $model -> order_no = $feeOrderC->getOrderNo();
                $model -> date = $date;
                $model -> order_type = FEEORDER_TYPE_WATER_FEE;
                $model -> water_ton = $water_ton;
                $model -> order_money = $pay_money; //把$order_money存到数据库
                $model -> pay_money = $pay_money;
                $model -> flag = 1; //删除标志位，1
                $model->create_time = new CDbExpression('now()'); //创建时间
                if($model->save()){
                    echo 'successs';
                } else {
                    print_r($model->getErrors());
                }
            }
        }
    }

    /**
     * @throws CException
     * @throws Exception
     * 导入停车费Excel
     */
    public function actionImportData2() {
        set_time_limit(0);
        $merchant_id = Yii::app()->session['merchant_id'];
        $fileName = IMG_BASE_PATH.WY_EXCEL_FOLDER.'/source/'.$_GET['fileName'];

        Yii::import('application.extensions.excel.*');
        include 'PHPExcel/Reader/Excel2007.php';//引入PHPExcel文件
        include 'PHPExcel/Reader/Excel5.php';
        include 'PHPExcel/IOFactory.php';

        $PHPReader = new PHPExcel_Reader_Excel5();//2003版本
        //打开excel文件
        $excel = $PHPReader -> load($fileName);
        //读取excel文件第一个工作表内容
        $firstSheet = $excel -> getSheet(0);
        //获取excel表行数
        $maxRow = $firstSheet -> getHighestRow();

        for($currentRow = 3;$currentRow <= $maxRow ;$currentRow ++) { //从第一行开始读取数据
            $currentColomn = 'A'; //读取第一列数据
            $currentColomn1 = 'B'; //读取第二列数据
            $currentColomn2 = 'C'; //读取第三列数据
            $currentColomn3 = 'D'; //读取第四列数据

            $account= $firstSheet -> getCell($currentColomn.$currentRow) -> getValue(); //赋值A1,A2,A3...
            $community = $firstSheet -> getCell($currentColomn1.$currentRow) -> getValue(); //赋值B1,B2,B3...
            $num = $firstSheet -> getCell($currentColomn2.$currentRow) -> getValue();
            $pay_money = $firstSheet -> getCell($currentColomn3.$currentRow) -> getValue();

            //判断手机号是否已经注册
            $ckUser = User::model()->find('merchant_id = :merchant_id and account = :account', array(':account' => $account, 'merchant_id' => $merchant_id));
            $communityM = Community::model()->find('merchant_id = :merchant_id and name = :name', array(':merchant_id' => $merchant_id, ':name' => $community));
            if (!empty($ckUser) && !empty($communityM)) {
                $feeOrderC = new FeeOrderC();

                $model = new FeeOrder();
                $model -> user_id = $ckUser->id; //把$name存到数据库
                $model -> user_account = $account; //把$tel存到数据库
                $model -> community_id = $communityM->id;
                $model -> merchant_id = $merchant_id;
                $model -> parking_month_num = $num;
                $model -> order_no = $feeOrderC->getOrderNo();
                $model -> order_type = FEEORDER_TYPE_PARKING_FEE;
                $model -> order_money = $pay_money; //把$order_money存到数据库
                $model -> pay_money = $pay_money;
                $model -> flag = 1; //删除标志位，1
                $model->create_time = new CDbExpression('now()'); //创建时间
                if($model->save()){
                    echo 'successs';
                } else {
                    print_r($model->getErrors());
                }
            }
        }
    }

    /**
     * @throws CException
     * @throws Exception
     * 导入物业费Excel
     */
    public function actionImportData3() {
        set_time_limit(0);
        $merchant_id = Yii::app()->session['merchant_id'];
        $fileName = IMG_BASE_PATH.WY_EXCEL_FOLDER.'/source/'.$_GET['fileName'];

        Yii::import('application.extensions.excel.*');
        include 'PHPExcel/Reader/Excel2007.php';//引入PHPExcel文件
        include 'PHPExcel/Reader/Excel5.php';
        include 'PHPExcel/IOFactory.php';

        $PHPReader = new PHPExcel_Reader_Excel5();//2003版本
        //打开excel文件
        $excel = $PHPReader -> load($fileName);
        //读取excel文件第一个工作表内容
        $firstSheet = $excel -> getSheet(0);
        //获取excel表行数
        $maxRow = $firstSheet -> getHighestRow();

        for($currentRow = 3;$currentRow <= $maxRow ;$currentRow ++) { //从第一行开始读取数据
            $currentColomn = 'A'; //读取第一列数据
            $currentColomn1 = 'B'; //读取第二列数据
            $currentColomn2 = 'C'; //读取第三列数据
            $currentColomn3 = 'D'; //读取第四列数据

            $account= $firstSheet -> getCell($currentColomn.$currentRow) -> getValue(); //赋值A1,A2,A3...
            $community = $firstSheet -> getCell($currentColomn1.$currentRow) -> getValue(); //赋值B1,B2,B3...
            $num = $firstSheet -> getCell($currentColomn2.$currentRow) -> getValue();
            $pay_money = $firstSheet -> getCell($currentColomn3.$currentRow) -> getValue();

            //判断手机号是否已经注册
            $ckUser = User::model()->find('merchant_id = :merchant_id and account = :account', array(':account' => $account, 'merchant_id' => $merchant_id));
            $communityM = Community::model()->find('merchant_id = :merchant_id and name = :name', array(':merchant_id' => $merchant_id, ':name' => $community));
            if (!empty($ckUser) && !empty($communityM)) {
                $feeOrderC = new FeeOrderC();

                $model = new FeeOrder();
                $model -> user_id = $ckUser->id; //把$name存到数据库
                $model -> user_account = $account; //把$tel存到数据库
                $model -> community_id = $communityM->id;
                $model -> merchant_id = $merchant_id;
                $model -> date = $num.'-01-01 00:00:00';
                $model -> order_no = $feeOrderC->getOrderNo();
                $model -> order_type = FEEORDER_TYPE_PROPERTY_FEE;
                $model -> order_money = $pay_money; //把$order_money存到数据库
                $model -> pay_money = $pay_money;
                $model -> flag = 1; //删除标志位，1
                $model->create_time = new CDbExpression('now()'); //创建时间
                if($model->save()){
                    echo 'successs';
                } else {
                    print_r($model->getErrors());
                }
            }
        }

    }

    /**
     * 导出EXCEL模板
     */
    public function actionGetModels() {
        $feeOrderC = new FeeOrderC();
        if (isset($_GET['order_type']) && $_GET['order_type']) {
            $order_type = $_GET['order_type'];
        }
        $feeOrderC -> getExcelModels($order_type);
    }

}