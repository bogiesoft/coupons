<?php
/**
 * Created by PhpStorm.
 * User: cheng
 * Date: 2016/7/1
 * Time: 16:39
 */
class RepairController extends mCenterController
{
    public $layout='newMain';

    /**
     * 业主报修列表（全部）
     */
    public function actionReportRepairRecordList()
    {

        $repairC = new ReportRepairRecordC();
        $communityC = new CommunityC();
        $status = '';
        $community_id = '';
        $tel = '';
        $time = '';
        $id = '';

        //根据状态进行搜索
        if (isset($_GET['status']) && $_GET['status']) {
            $status = $_GET['status'];
        }
        //根据小区进行搜索
        if (isset($_GET['community_id']) && $_GET['community_id']) {
            $community_id = $_GET['community_id'];
        }
        //根据报修id进行搜索
        if (isset($_GET['id']) && $_GET['id']) {
            $id = $_GET['id'];
        }
        //根据手机号搜索
        if(isset($_GET['tel']) &&!empty($_GET['tel'])){
            $tel = trim($_GET['tel']);
        }
        //根据时间进行搜索
        if(!empty($_GET['time'])){
            $time = $_GET['time'];
        }
        $merchant_id = Yii::app()->session['merchant_id'];
        $result = json_decode($repairC->getRepairList($merchant_id,$id, $status, $community_id,$tel,$time), true);
        if ($result['status'] == APPLY_CLASS_SUCCESS) {
            if (isset($result['data'])) {
                $list = $result['data'];
            }
        }

        $community_list = json_decode($communityC->getCommunityAllList($merchant_id));
        if ($community_list->status == APPLY_CLASS_SUCCESS) {
            $data = $community_list->data;
        }
        $info = array();
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $info[$k]['id'] = $v->id;
                $info[$k]['name'] = $v->name;
            }
        }

        $this->render('communityWarranty', array(
            'repair' => $list,
            'community_list' => $info,
            'pages'=>$repairC->page
        ));
    }

    /**
     * 业主报修列表（待报修）
     */
    public function actionCommunityWarrantyWaiting()
    {

        $repairC = new ReportRepairRecordC();
        $communityC = new CommunityC();
        $status = '';
        $community_id = '';
        $tel = '';
        $time = '';
        $id = '';

        //根据状态进行搜索
        $status = $_GET['status'] = REPORT_REPAIR_RECORD_STATUS_WAITING;

        //根据小区进行搜索
        if (isset($_GET['community_id']) && $_GET['community_id']) {
            $community_id = $_GET['community_id'];
        }
        //根据报修id进行搜索
        if (isset($_GET['id']) && $_GET['id']) {
            $id = $_GET['id'];
        }
        //根据手机号搜索
        if(isset($_GET['tel']) &&!empty($_GET['tel'])){
            $tel = trim($_GET['tel']);
        }
        //根据时间进行搜索
        if(!empty($_GET['time'])){
            $time = $_GET['time'];
        }
        $merchant_id = Yii::app()->session['merchant_id'];
        $result = json_decode($repairC->getRepairList($merchant_id,$id, $status, $community_id,$tel,$time), true);
        if ($result['status'] == APPLY_CLASS_SUCCESS) {
            if (isset($result['data'])) {
                $list = $result['data'];
            }
        }

        $community_list = json_decode($communityC->getCommunityAllList($merchant_id));
        if ($community_list->status == APPLY_CLASS_SUCCESS) {
            $data = $community_list->data;
        }
        $info = array();
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $info[$k]['id'] = $v->id;
                $info[$k]['name'] = $v->name;
            }
        }

        $this->render('communityWarrantyWaiting', array(
            'repair' => $list,
            'community_list' => $info,
            'pages'=>$repairC->page
        ));
    }

    /**
     *业主报修列表（已报修）
     */
    public function actionCommunityWarrantyComplete()
    {

        $repairC = new ReportRepairRecordC();
        $communityC = new CommunityC();
        $status = '';
        $community_id = '';
        $tel = '';
        $time = '';
        $id = '';

        //根据状态进行搜索
        $status = REPORT_REPAIR_RECORD_STATUS_COMPLETE;

        //根据小区进行搜索
        if (isset($_GET['community_id']) && $_GET['community_id']) {
            $community_id = $_GET['community_id'];
        }
        //根据报修id进行搜索
        if (isset($_GET['id']) && $_GET['id']) {
            $id = $_GET['id'];
        }
        //根据手机号搜索
        if(isset($_GET['tel']) &&!empty($_GET['tel'])){
            $tel = trim($_GET['tel']);
        }
        //根据时间进行搜索
        if(!empty($_GET['time'])){
            $time = $_GET['time'];
        }
        $merchant_id = Yii::app()->session['merchant_id'];
        $result = json_decode($repairC->getRepairList($merchant_id,$id, $status, $community_id,$tel,$time), true);
        if ($result['status'] == APPLY_CLASS_SUCCESS) {
            if (isset($result['data'])) {
                $list = $result['data'];
            }
        }

        $community_list = json_decode($communityC->getCommunityAllList($merchant_id));
        if ($community_list->status == APPLY_CLASS_SUCCESS) {
            $data = $community_list->data;
        }
        $info = array();
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $info[$k]['id'] = $v->id;
                $info[$k]['name'] = $v->name;
            }
        }

        $this->render('communityWarrantyComplete', array(
            'repair' => $list,
            'community_list' => $info,
            'pages'=>$repairC->page,
        ));
    }

    /**
     * 查看报修详情
     */
    public function actionReportRepairRecordDetail()
    {

        $repairC = new ReportRepairRecordC();
        $communityC = new CommunityC();
        $status = '';
        $community_id = '';
        $tel = '';
        $time = '';
        $id = '';

        //根据报修id进行搜索
        if (isset($_GET['repair_id']) && $_GET['repair_id']) {
            $id = $_GET['repair_id'];
        }
        $merchant_id = Yii::app()->session['merchant_id'];
        $result = json_decode($repairC->getRepairList($merchant_id,$id, $status, $community_id,$tel,$time), true);
        if ($result['status'] == APPLY_CLASS_SUCCESS) {
            if (isset($result['data'])) {
                $list = $result['data'];
            }
        }

        $this->render('warrantyDetail', array(
            'repair_detail' => $list[0],
        ));

    }

    /**
     * 确认报修
     */
    public function actionRepairStatus() {
        if (isset($_GET['repair_id']) && !empty($_GET['repair_id'])) {
            $repairC = new ReportRepairRecordC();
            $result = json_decode($repairC -> editReportRepairRecordStatus($_GET['repair_id']));
            if ($result -> status == APPLY_CLASS_SUCCESS) {
                if (isset($_GET['goUrl'])) {
                    $this -> redirect($_GET['goUrl']);
                }
            }
        }
    }

    /**
     * 导出报修信息EXCEL表
     */
    public function actionGetRepairExcel() {
        $repairC = new ReportRepairRecordC();
        $communityC = new CommunityC();
        $status = '';
        $community_id = '';
        $tel = '';
        $time = '';
        $id = '';

        //根据状态进行搜索
        if (isset($_GET['status']) && $_GET['status']) {
            $status = $_GET['status'];
        }
        //根据小区进行搜索
        if (isset($_GET['community_id']) && $_GET['community_id']) {
            $community_id = $_GET['community_id'];
        }
        //根据报修id进行搜索
        if (isset($_GET['id']) && $_GET['id']) {
            $id = $_GET['id'];
        }
        //根据手机号搜索
        if(isset($_GET['tel']) &&!empty($_GET['tel'])){
            $tel = trim($_GET['tel']);
        }
        //根据时间进行搜索
        if(!empty($_GET['time'])){
            $time = $_GET['time'];
        }
        $merchant_id = Yii::app()->session['merchant_id'];
        $result = json_decode($repairC->getRepairMsg($merchant_id,$id, $status, $community_id,$tel,$time), true);
        if ($result['status'] == APPLY_CLASS_SUCCESS) {
            if (isset($result['data'])) {
                $list = $result['data'];
            }
        }

        $community_list = json_decode($communityC->getCommunityAllList($merchant_id));
        if ($community_list->status == APPLY_CLASS_SUCCESS) {
            $data = $community_list->data;
        }
        $info = array();
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $info[$k]['id'] = $v->id;
                $info[$k]['name'] = $v->name;
            }
        }

        $repairC -> toRepairExcel($list, $status);
    }


}