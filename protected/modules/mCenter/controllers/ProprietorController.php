<?php
/**
 * Created by PhpStorm.
 * User: sundi
 * Date: 2016/6/29
 * Time: 9:13
 * -----智慧物业-------
 */
class ProprietorController extends mCenterController
{
    
    public $layout='newMain';
    
    public function actionIndex(){
        
    }
    
    /**
     * 业主列表
     */
    public function actionProprietorList(){

        $communityC = new CommunityC();
        $proprietorC = new ProprietorC();
        $list = '';
        $data = '';

        //根据状态进行搜索
        $verify_status = '';
        if (isset($_GET['verify_status']) && $_GET['verify_status']) {
            $verify_status = $_GET['verify_status'];
        }
        //根据小区进行搜索
        $community_name = '';
        if (isset($_GET['community_name']) && $_GET['community_name']) {
            $community_name = $_GET['community_name'];
        }
        //根据楼号进行搜索
        $building_number = '';
        if (isset($_GET['building_number']) && $_GET['building_number']) {
            $building_number = $_GET['building_number'];
        }
        //根据联系方式进行搜索
        $tel = '';
        if (isset($_GET['tel']) && $_GET['tel']) {
            $tel = trim($_GET['tel']);
        }
        $merchant_id = Yii::app()->session['merchant_id'];
        $result = json_decode($proprietorC->getProprietorList($merchant_id,$verify_status,$community_name,$building_number,$tel), true);

        if ($result['status'] == APPLY_CLASS_SUCCESS) {
            if (isset($result['data'])) {
                $list = $result['data'];
            }
        }

        $community_list = json_decode($communityC -> getCommunityAllList($merchant_id));
        $data = array();
        if($community_list -> status == APPLY_CLASS_SUCCESS){
            $data = $community_list -> data;
        }

        $info = array();
        if (!empty($data)){
            foreach ($data as $k => $v){
                $info[$k]['id'] = $v -> id;
                $info[$k]['name'] = $v -> name;
            }
        }

        $this->render('proprietorList',array(
            'proprietor_list' => $list,
            'community_list' => $info,
            'pages'=>$proprietorC->page
        ));
    }

    /**
     * @param $user_id
     * 删除业主
     */
    public function actionDeleteProprietor($user_id,$proprietor_id){
        $proprietorC = new ProprietorC();

        $result = json_decode($proprietorC->deleteProprietor($user_id,$proprietor_id),true);

        if ($result['status'] == APPLY_CLASS_SUCCESS) {
            $this->redirect('ProprietorList');
        }
    }

    /**
     * 业主详情
     */
    public function actionProprietorInfo($proprietor_id){

        $proprietorC = new ProprietorC();
        $list = '';

        $result = $proprietorC->getProprietorInfo($proprietor_id);
        if ($result['status'] == APPLY_CLASS_SUCCESS) {
            $list = $result['data'];
        }

        $this->render('proprietorInfo',array(
            'proprietor_detail' => $list,
        ));
    }

    /**
     * 添加电费
     */
    public function actionAddProprietorEfee($proprietor_id,$user_id){

        $communityC = new CommunityC();
        $proprietorC = new ProprietorC();
        $feeorderC = new FeeOrderC();
        $community_id = '';
        $order_type = FEEORDER_TYPE_ELECTRICITY_FEE;

        //获取小区id
        $result = $proprietorC->getProprietorInfo($proprietor_id);
        if ($result['status'] == APPLY_CLASS_SUCCESS) {
            $community_id = $result['community_id'];
        }
        //获取小区详情
        $community_detail = $communityC -> getCommunityInfo($community_id);

        //获取电费类型
        $result = json_decode($community_detail->electricity_fee_set,true);
        $efee_info = $result;
        if(empty($efee_info)){
            $this->redirect(Yii::app()->createUrl('mCenter/Village/CommunityEfee',array('community_id' => $community_id)));
        }
        $efee_type = $result['type'];

        $result = json_decode($feeorderC->getFeeOrderList($order_type,$merchant_id='',$user_id), true);

        if ($result['status'] == APPLY_CLASS_SUCCESS) {
            if (isset($result['data'])) {
                $list = $result['data'];
            }
        }

        if (isset($_POST)&&!empty($_POST)){
            $post = $_POST;
            $merchant_id = Yii::app()->session['merchant_id'];

            $result = json_decode($feeorderC->addFeeOrder($post,$order_type,$efee_type,$user_id,$community_id,$merchant_id),true);

            if ($result['status'] == APPLY_CLASS_SUCCESS) {
                Yii::app() -> user -> setFlash('info', 'success');
                //$this->redirect(Yii::app()->createUrl('mCenter/Proprietor/ProprietorList'));
            }else{
                if($result['errMsg'] == 'info_date_miss'){
                    Yii::app() -> user -> setFlash('info_date_miss', '请填写月份');
                }
                if($result['errMsg'] == 'info_peak_miss'){
                    Yii::app() -> user -> setFlash('info_peak_miss', '请填写峰电量');
                }
                if($result['errMsg'] == 'info_valley_miss'){
                    Yii::app() -> user -> setFlash('info_valley_miss', '请填写谷电量');
                }
                if($result['errMsg'] == 'info_electricity_miss'){
                    Yii::app() -> user -> setFlash('info_electricity_miss', '请填写总用电量');
                }
                if($result['errMsg'] == 'info_order_money_miss'){
                    Yii::app() -> user -> setFlash('info_order_money_miss', '请填写订单金额');
                }
                if($result['errMsg'] == 'error_database_add'){
                    Yii::app() -> user -> setFlash('error_database_add', '保存失败');
                }
            }
        }

        $this->render('addProprietorEfee',array(
            'efee_type' => $efee_type,
            'feerder_list' => $list,
            'efee_info' => $efee_info,
            'proprietor_id' => $proprietor_id,
            'user_id' => $user_id,
        ));
    }

    /**
     * 添加水费
     */
    public function actionAddProprietorWfee($proprietor_id,$user_id){

        $communityC = new CommunityC();
        $proprietorC = new ProprietorC();
        $feeorderC = new FeeOrderC();
        $order_type = FEEORDER_TYPE_WATER_FEE;
        $list = '';
        $community_id= '';

        //获取小区id
        $result = $proprietorC->getProprietorInfo($proprietor_id);
        if ($result['status'] == APPLY_CLASS_SUCCESS) {
            $community_id = $result['community_id'];
        }
        //获取小区详情
        $community_detail = $communityC -> getCommunityInfo($community_id);


        //获取水费类型
        $result = json_decode($community_detail->water_fee_set,true);
        $wfee_info = $result;
        if(empty($wfee_info)){
            $this->redirect(Yii::app()->createUrl('mCenter/Village/CommunityWfee',array('community_id' => $community_id)));
        }

        $result = json_decode($feeorderC->getFeeOrderList($order_type,$merchant_id='',$user_id), true);

        if ($result['status'] == APPLY_CLASS_SUCCESS) {
            if (isset($result['data'])) {
                $list = $result['data'];
            }
        }

        if (isset($_POST)&&!empty($_POST)){
            $post = $_POST;
            $merchant_id = Yii::app()->session['merchant_id'];

            $result = json_decode($feeorderC->addFeeOrder($post,$order_type,NULL,$user_id,$community_id,$merchant_id),true);

            if ($result['status'] == APPLY_CLASS_SUCCESS) {
                Yii::app() -> user -> setFlash('info', 'success');
                //$this->redirect(Yii::app()->createUrl('mCenter/Proprietor/ProprietorList'));
            }else{
                if($result['errMsg'] == 'info_date_miss'){
                    Yii::app() -> user -> setFlash('info_date_miss', '请填写月份');
                }
                if($result['errMsg'] == 'info_electricity_miss'){
                    Yii::app() -> user -> setFlash('info_electricity_miss', '请填写本月用水量');
                }
                if($result['errMsg'] == 'info_order_money_miss'){
                    Yii::app() -> user -> setFlash('info_order_money_miss', '请填写本月水费');
                }
                if($result['errMsg'] == 'error_database_add'){
                    Yii::app() -> user -> setFlash('error_database_add', '保存失败');
                }
            }
        }

        $this->render('addProprietorWfee',array(
            'feerder_list' => $list,
            'wfee_info' => $wfee_info,
            'proprietor_id' => $proprietor_id,
            'user_id' => $user_id,
        ));
    }

    /**
     * 添加物业费
     */
    public function actionAddProprietorProfee($proprietor_id,$user_id){
        $communityC = new CommunityC();
        $proprietorC = new ProprietorC();
        $feeorderC = new FeeOrderC();
        $order_type = FEEORDER_TYPE_PROPERTY_FEE;
        $list = '';
        $community_id= '';

        //获取小区id
        $result = $proprietorC->getProprietorInfo($proprietor_id);
        if ($result['status'] == APPLY_CLASS_SUCCESS) {
            $community_id = $result['community_id'];
        }
        //获取小区详情
        $community_detail = $communityC -> getCommunityInfo($community_id);


        //获取物业费类型
        $result = json_decode($community_detail->property_fee_set,true);
        $property_fee_info = $result;
        if(empty($property_fee_info)){
            $this->redirect(Yii::app()->createUrl('mCenter/Village/CommunityPropertyfee',array('community_id' => $community_id)));
        }

        $result = json_decode($feeorderC->getFeeOrderList($order_type,$merchant_id='',$user_id), true);

        if ($result['status'] == APPLY_CLASS_SUCCESS) {
            if (isset($result['data'])) {
                $list = $result['data'];
            }
        }

        if (isset($_POST)&&!empty($_POST)){
            $post = $_POST;
            $merchant_id = Yii::app()->session['merchant_id'];

            $result = json_decode($feeorderC->addFeeOrder($post,$order_type,NULL,$user_id,$community_id,$merchant_id),true);

            if ($result['status'] == APPLY_CLASS_SUCCESS) {
                Yii::app() -> user -> setFlash('info', 'success');
                //$this->redirect(Yii::app()->createUrl('mCenter/Proprietor/ProprietorList'));
            }else{
                if($result['errMsg'] == 'info_date_miss'){
                    Yii::app() -> user -> setFlash('info_date_miss', '请填写年份');
                }
                if($result['errMsg'] == 'info_electricity_miss'){
                    Yii::app() -> user -> setFlash('info_floor_space_miss', '请填写房屋面积');
                }
                if($result['errMsg'] == 'info_order_money_miss'){
                    Yii::app() -> user -> setFlash('info_order_money_miss', '请填写本年度物业费');
                }
                if($result['errMsg'] == 'error_database_add'){
                    Yii::app() -> user -> setFlash('error_database_add', '保存失败');
                }
            }
        }

        $this->render('addProprietorProfee',array(
            'feerder_list' => $list,
            'property_fee_info' => $property_fee_info,
            'proprietor_id' => $proprietor_id,
            'user_id' => $user_id,
        ));
    }

    /**
     * 通过业主审核
     */
    public function actionProprietorVerifyStatusPass($proprietor_id){

        $proprietorC = new ProprietorC();
        $remark = '';
        $flag = PROPRIETOR_VERIFY_STATUS_PASS;

        if (isset($_POST)&&!empty($_POST)){
            $remark = $_POST['remark'];
        }

        $result = json_decode($proprietorC->editProprietorVerifyStatus($proprietor_id,$flag,$remark),true);

        if ($result['status'] == APPLY_CLASS_SUCCESS) {
            $this->redirect(Yii::app()->createUrl('mCenter/Proprietor/ProprietorList'));
        }
    }

    /**
     * 驳回业主审核
     */
    public function actionProprietorVerifyStatusReject(){

        $proprietorC = new ProprietorC();
        $remark = '';
        $proprietor_id = '';
        $flag = PROPRIETOR_VERIFY_STATUS_REJECT;

        if (isset($_POST)&&!empty($_POST)){
            $remark = $_POST['remark'];
            $proprietor_id = $_POST['id'];
        }

        $result = json_decode($proprietorC->editProprietorVerifyStatus($proprietor_id,$flag,$remark),true);

        if ($result['status'] == APPLY_CLASS_SUCCESS) {
            $this->redirect(Yii::app()->createUrl('mCenter/Proprietor/ProprietorList'));
        }
    }

    /**
     * 导出业主信息
     */
    public function actionGetProprietorMsg() {
        $communityC = new CommunityC();
        $proprietorC = new ProprietorC();
        $list = '';
        $data = '';

        //根据状态进行搜索
        $verify_status = '';
        if (isset($_GET['verify_status']) && $_GET['verify_status']) {
            $verify_status = $_GET['verify_status'];
        }
        //根据小区进行搜索
        $community_name = '';
        if (isset($_GET['community_name']) && $_GET['community_name']) {
            $community_name = $_GET['community_name'];
        }
        //根据楼号进行搜索
        $building_number = '';
        if (isset($_GET['building_number']) && $_GET['building_number']) {
            $building_number = $_GET['building_number'];
        }
        //根据联系方式进行搜索
        $tel = '';
        if (isset($_GET['tel']) && $_GET['tel']) {
            $tel = $_GET['tel'];
        }

        $merchant_id = Yii::app()->session['merchant_id'];
        $result = json_decode($proprietorC->getProprietorExcel($merchant_id,$verify_status,$community_name,$building_number,$tel), true);
;
        if ($result['status'] == APPLY_CLASS_SUCCESS) {
            if (isset($result['data'])) {
                $list = $result['data'];
            }
        }

        $community_list = json_decode($communityC -> getCommunityAllList($merchant_id));
        $data = array();
        if($community_list -> status == APPLY_CLASS_SUCCESS){
            $data = $community_list -> data;
        }

        $info = array();
        if (!empty($data)){
            foreach ($data as $k => $v){
                $info[$k]['id'] = $v -> id;
                $info[$k]['name'] = $v -> name;
            }
        }

        $proprietorC -> getProDetailExcel($list);
    }

    /**
     * 导出业主信息EXCEL模板
     */
    public function actionGetModels() {
        $proprietorC = new ProprietorC();
        $proprietorC -> getExcelModels();
    }

    /**
     * 导入业主
     */
    public function actionImportData() {
        set_time_limit(0);
        //商户id
        $merchant_id = Yii::app()->session['merchant_id'];
        $fileName = IMG_BASE_PATH.WY_EXCEL_FOLDER.'/source/'.$_GET['fileName'];
        include('PHPExcel.php');
        //2003版本
        $PHPReader = new PHPExcel_Reader_Excel5();
        //打开excel文件
        $excel = $PHPReader -> load($fileName);
        //读取excel文件第一个工作表内容
        $firstSheet = $excel -> getSheet(0);
        //获取excel表行数
        $maxRow = $firstSheet -> getHighestRow();
        for ($currentRow = 3; $currentRow <= $maxRow; $currentRow++) {
            $currentColomn = 'A';
            $currentColomn1 = 'B';
            $currentColomn2 = 'C';
            $currentColomn3 = 'D';
            $currentColomn4 = 'E';
            $currentColomn5 = 'F';
            $currentColomn6 = 'G';

            $name = $firstSheet -> getCell($currentColomn.$currentRow) -> getValue();
            $account = $firstSheet -> getCell($currentColomn1.$currentRow) -> getValue();
            $access_control_card_no = $firstSheet -> getCell($currentColomn2.$currentRow) -> getValue();
            $type = $firstSheet -> getCell($currentColomn3.$currentRow) -> getValue();
            $community = $firstSheet -> getCell($currentColomn4.$currentRow) -> getValue();
            $building_number = $firstSheet -> getCell($currentColomn5.$currentRow) -> getValue();
            $room_number = $firstSheet -> getCell($currentColomn6.$currentRow) -> getValue();

            //判断手机号是否已经注册
            $ckUser = User::model()->exists('merchant_id = :merchant_id and account = :account and flag = :flag', array(':account' => $account, ':merchant_id' => $merchant_id, ':flag' => 1));
            //查找小区
            $communityM = Community::model()->find('merchant_id = :merchant_id and name = :name', array(':merchant_id' => $merchant_id, ':name' => $community));
            if (!$ckUser && !empty($communityM)) {
                $user_model = new User();
                $user_model->merchant_id = $merchant_id;
                $user_model->account = $account;
                $user_model->pwd = md5(123456);
                $user_model->name = $name;
                $user_model->regist_time = date('Y-m-d H:i:s');
                $user_model->create_time = date('Y-m-d H:i:s');
                if ($user_model->save()) {
                    //保存业主信息
                    $model = new Proprietor();
                    $model->user_id = $user_model -> id;
                    $model->merchant_id = $merchant_id;
                    $model->access_control_card_no = $access_control_card_no;
                    $model->verify_status = PROPRIETOR_VERIFY_STATUS_PASS;
                    $model->type = $type == '业主' ? PROPRIETOR_TYPE_OWNER : PROPRIETOR_TYPE_TENEMENT;
                    $model->community_id = $communityM->id;
                    $model->building_number = $building_number;
                    $model->room_number = $room_number;
                    $model->create_time = date('Y-m-d H:i:s');
                    $model->last_time = date('Y-m-d H:i:s');
                    if ($model->save()) {
                        echo 'successs';
                    }
                }
            }
        }
    }

}