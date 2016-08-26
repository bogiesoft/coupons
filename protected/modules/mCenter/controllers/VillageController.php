<?php
/**
 * Created by PhpStorm.
 * User: sundi
 * Date: 2016/6/22
 * Time: 14:47
 * -----智慧物业-------
 */
class VillageController extends mCenterController
{
    public $layout='newMain';
    /**
     * 添加小区
     */
    public function actionAddCommunity(){

        $communityC = new CommunityC();

        //表单数据提交
        if (isset($_POST) && !empty($_POST)) {
            $model = $_POST;
            $merchant_id = Yii::app()->session['merchant_id'];
            
            $result = $communityC -> addCommunity($model,$merchant_id);
            $result = json_decode($result, true);

            if ($result['status'] == APPLY_CLASS_SUCCESS) {
                Yii::app() -> user -> setFlash('info', 'success');
                //$this->redirect(Yii::app()->createUrl('mCenter/Village/CommunityList'));
            }else{
                if($result['errMsg'] == 'info_name_miss'){
                    Yii::app() -> user -> setFlash('info_name_miss', '请填写小区名称');
                }
                if($result['errMsg'] == 'info_name_short'){
                    Yii::app() -> user -> setFlash('info_name_short', '小区名称必须大于2个字');
                }
                if($result['errMsg'] == 'info_name_long'){
                    Yii::app() -> user -> setFlash('info_name_long', '小区名称必须小于15个字');
                }
                if($result['errMsg'] == 'info_name_same'){
                    Yii::app() -> user -> setFlash('info_name_same', '小区名称已存在');
                }
                if($result['errMsg'] == 'info_tel_miss'){
                    Yii::app() -> user -> setFlash('info_tel_miss', '请填写联系方式');
                }
                if($result['errMsg'] == 'info_tel_format'){
                    Yii::app() -> user -> setFlash('info_tel_format', '联系方式必须为数字');
                }
                if($result['errMsg'] == 'info_tel_short'){
                    Yii::app() -> user -> setFlash('info_tel_short', '联系方式必须大于6个数字');
                }
                if($result['errMsg'] == 'info_tel_long'){
                    Yii::app() -> user -> setFlash('info_tel_long', '联系方式必须小于11个数字');
                }
                if($result['errMsg'] == 'info_address_miss'){
                    Yii::app() -> user -> setFlash('info_address_miss', '请填写小区的详细地址');
                }
                if($result['errMsg'] == 'error_database_add'){
                    Yii::app() -> user -> setFlash('error_database_add', '保存失败');
                }
            }
        }

        $this->render('addCommunity');
    }

    /**
     * ajax 验证小区名称是否重复
     */
    public function actionCommunityNameValid(){
        $data = array('error' => 'failure');
        $merchant_id = Yii::app()->session['merchant_id'];
        try{
            $communityC = new CommunityC();
            $name = $_POST['name'];
            $count = $communityC->countName($name,$merchant_id);
            if($count > 0){
                $data['error'] = 'success';
            }
        }catch (Exception $e) {
            $data['msg'] = $e->getMessage();
        }

        echo json_encode($data, true);
    }

    /**
     * 小区列表
     */
    public function actionCommunityList(){
        $communityC = new CommunityC();

        $list = '';
        $merchant_id = Yii::app()->session['merchant_id'];
        $community_list = json_decode($communityC -> getCommunityList($merchant_id));
        if($community_list -> status == APPLY_CLASS_SUCCESS){
            $list = $community_list -> data;
        }

        $this->render('communityList',array(
            'community_list' => $list,
            'pages' => $communityC->page,
        ));
    }

    /**
     * 编辑小区
     */
    public function actionEditCommunity($community_id){
        $communityC = new CommunityC();

        $community_detail = $communityC -> getCommunityInfo($community_id);

            //表单数据提交
        if (isset($_POST) && !empty($_POST)) {
            $model = $_POST;

            $result = $communityC->editCommunity($model, $community_id);
            $result = json_decode($result, true);
            
            if ($result['status'] == APPLY_CLASS_SUCCESS) {
                Yii::app() -> user -> setFlash('info', 'success');
                //$this->redirect(Yii::app()->createUrl('mCenter/Village/CommunityList'));
            } else {
                if ($result['errMsg'] == 'info_name_miss') {
                    Yii::app()->user->setFlash('info_name_miss', '请填写小区名称');
                }
                if ($result['errMsg'] == 'info_name_short') {
                    Yii::app()->user->setFlash('info_name_short', '小区名称必须大于2个字');
                }
                if ($result['errMsg'] == 'info_name_long') {
                    Yii::app()->user->setFlash('info_name_long', '小区名称必须小于15个字');
                }
                if($result['errMsg'] == 'info_name_same'){
                    Yii::app() -> user -> setFlash('info_name_same', '小区名称已存在');
                }
                if ($result['errMsg'] == 'info_tel_miss') {
                    Yii::app()->user->setFlash('info_tel_miss', '请填写联系方式');
                }
                if ($result['errMsg'] == 'info_tel_format') {
                    Yii::app()->user->setFlash('info_tel_format', '联系方式必须为数字');
                }
                if ($result['errMsg'] == 'info_tel_short') {
                    Yii::app()->user->setFlash('info_tel_short', '联系方式必须大于6个数字');
                }
                if ($result['errMsg'] == 'info_tel_long') {
                    Yii::app()->user->setFlash('info_tel_long', '小区名称必须小于11个数字');
                }
                if ($result['errMsg'] == 'info_address_miss') {
                    Yii::app()->user->setFlash('info_address_miss', '请填写小区的详细地址');
                }
                if ($result['errMsg'] == 'error_database_add') {
                    Yii::app()->user->setFlash('error_database_add', '保存失败');
                }
            }
        }

        $this->render('editCommunity',array(
            'community_detail' => $community_detail,
            'community_id' => $community_id,
        ));
    }

    /**
     * 删除小区
     */
    public function actionDelCommunity($community_id){

        $communityC = new CommunityC();

        $result = $communityC -> delCommunity($community_id);
        $result = json_decode($result, true);

        if ($result['status'] == APPLY_CLASS_SUCCESS) {
            $this->redirect('communityList');
        }
    }

    /**
     * 查看停车费
     */
    public function actionGetCommunityParkingfee($community_id){

        $communityC = new CommunityC();
        $community_Parkingfee = json_decode($communityC->getCommunityParkingfee($community_id),true);

        $this->render('getCommunityParkingfee',array(
            'community_id' => $community_id,
            'community_Parkingfee' => $community_Parkingfee,
        ));
    }

    /**
     * 查看物业费
     */
    public function actionGetCommunityPropertyfee($community_id){

        $communityC = new CommunityC();
        $community_Propertyfee = json_decode($communityC->getCommunityPropertyfee($community_id),true);

        $this->render('getCommunityPropertyfee',array(
            'community_id' => $community_id,
            'community_Propertyfee' => $community_Propertyfee,
        ));
    }

    /**
     * 小区电费设置
     */
    public function actionCommunityEfee($community_id){

        $communityC = new CommunityC();

        $community_name = $communityC->getCommunityInfo($community_id);

        $community_Efee = json_decode($communityC->getCommunityEfee($community_id),true);

        if (isset($_POST) && !empty($_POST)) {
            $post = $_POST;

            $result = json_decode($communityC->editCommunityEfee($post, $community_id),true);

            if ($result['status'] == APPLY_CLASS_SUCCESS) {
                Yii::app() -> user -> setFlash('info', 'success');
                //$this->redirect($this->createUrl('CommunityEfee', array('community_id' => $community_id)));
            }else{
                if($result['errMsg'] == 'info_name_miss'){
                    Yii::app() -> user -> setFlash('community_name_miss', '请选择小区');
                }
                if($result['errMsg'] == 'info_name_short'){
                    Yii::app() -> user -> setFlash('time_miss', '请填写时间');
                }
                if($result['errMsg'] == 'info_name_long'){
                    Yii::app() -> user -> setFlash('price_miss', '请填写单价');
                }
                if($result['errMsg'] == 'info_tel_miss'){
                    Yii::app() -> user -> setFlash('peak_time_miss', '请填写峰电时间');
                }
                if($result['errMsg'] == 'info_tel_format'){
                    Yii::app() -> user -> setFlash('trough_time_miss', '请填写谷电时间');
                }
                if($result['errMsg'] == 'info_tel_short'){
                    Yii::app() -> user -> setFlash('peak_price_miss', '请填写峰电单价');
                }
                if($result['errMsg'] == 'info_tel_long'){
                    Yii::app() -> user -> setFlash('trough_price_miss', '请填写谷电单价');
                }
            }
        }

            $this->render('communityEfee',array(
            'community_name' => $community_name,
            'community_id' => $community_id,
            'community_Efee' => $community_Efee,
        ));
    }

    /**
     * 小区水费设置
     */
    public function actionCommunityWfee($community_id){

        $communityC = new CommunityC();

        $community_name = $communityC->getCommunityInfo($community_id);

        $community_Wfee = json_decode($communityC->getCommunityWfee($community_id),true);

        if (isset($_POST) && !empty($_POST)) {
            $post = $_POST;

            $result = json_decode($communityC->editCommunityWfee($post, $community_id),true);

            if ($result['status'] == APPLY_CLASS_SUCCESS) {
                Yii::app() -> user -> setFlash('info', 'success');
                //$this->redirect($this->createUrl('CommunityWfee', array('community_id' => $community_id)));
            }else{
                if($result['errMsg'] == 'info_name_short'){
                    Yii::app() -> user -> setFlash('time_miss', '请填写时间');
                }
                if($result['errMsg'] == 'info_name_long'){
                    Yii::app() -> user -> setFlash('price_miss', '请填写单价');
                }
            }
        }

        $this->render('communityWfee',array(
            'community_name' => $community_name,
            'community_id' => $community_id,
            'community_Wfee' => $community_Wfee,
        ));
    }

    /**
     * 小区停车费设置
     */
    public function actionCommunityParkingfee($community_id){

        $communityC = new CommunityC();

        $community_name = $communityC->getCommunityInfo($community_id);

        $community_Parkingfee = json_decode($communityC->getCommunityParkingfee($community_id),true);

        if (isset($_POST) && !empty($_POST)) {
            $post = $_POST;

            $result = json_decode($communityC->editCommunityParkingfee($post, $community_id),true);

            if ($result['status'] == APPLY_CLASS_SUCCESS) {
                Yii::app() -> user -> setFlash('info', 'success');
                //$this->redirect($this->createUrl('CommunityParkingfee', array('community_id' => $community_id)));
            }else{
                if($result['errMsg'] == 'info_name_short'){
                    Yii::app() -> user -> setFlash('day_price_miss', '请填写一天停车费');
                }
                if($result['errMsg'] == 'info_name_long'){
                    Yii::app() -> user -> setFlash('half_year_price_miss', '请填写半年停车费');
                }
                if($result['errMsg'] == 'info_name_long'){
                    Yii::app() -> user -> setFlash('year_price_miss', '请填写一年停车费');
                }
            }
        }

        $this->render('communityParkingfee',array(
            'community_name' => $community_name,
            'community_id' => $community_id,
            'community_Parkingfee' => $community_Parkingfee,
        ));
    }

    /**
     * 小区物业费设置
     */
    public function actionCommunityPropertyfee($community_id){

        $communityC = new CommunityC();

        $community_name = $communityC->getCommunityInfo($community_id);

        $community_Propertyfee = json_decode($communityC->getCommunityPropertyfee($community_id),true);

        if (isset($_POST) && !empty($_POST)) {
            $post = $_POST;

            $result = json_decode($communityC->editCommunityPropertyfee($post, $community_id),true);

            if ($result['status'] == APPLY_CLASS_SUCCESS) {
                Yii::app() -> user -> setFlash('info', 'success');
                //$this->redirect($this->createUrl('CommunityPropertyfee', array('community_id' => $community_id)));
            }else{
                if($result['errMsg'] == 'info_name_short'){
                    Yii::app() -> user -> setFlash('day_price_miss', '请填写一天物业费');
                }
                if($result['errMsg'] == 'info_name_long'){
                    Yii::app() -> user -> setFlash('half_year_price_miss', '请填写半年物业费');
                }
                if($result['errMsg'] == 'info_name_long'){
                    Yii::app() -> user -> setFlash('year_price_miss', '请填写一年物业费');
                }
            }
        }

        $this->render('communityPropertyfee',array(
            'community_name' => $community_name,
            'community_id' => $community_id,
            'community_Propertyfee' => $community_Propertyfee,
        ));
    }

}