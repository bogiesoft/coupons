<?php
/**
 * Created by PhpStorm.
 * User: sundi
 * Date: 2016/6/23
 * Time: 15:36
 */
include_once(dirname(__FILE__).'/../mainClass.php');

//业主类
class ProprietorC extends mainClass
{
    /**
     * @param $merchant_id
     * 获取业主列表
     */
    public function getProprietorList($merchant_id,$verify_status='',$community_name,$building_number,$tel){
        $result = array();
        try {
            //分页
            $criteria = new CDbCriteria();
            $criteria->addCondition("flag = :flag");
            $criteria->addCondition("merchant_id = :merchant_id");
            $criteria->params[':flag'] = FLAG_NO;
            $criteria->params[':merchant_id'] = $merchant_id;
            $criteria->order = "create_time desc";

            if ($community_name != '' && $community_name != NULL) {
                $criteria->addCondition("community_id = :community_id");
                $criteria->params[':community_id'] = $community_name;
            }

            if ($building_number != '' && $building_number != NULL) {
                $criteria->addCondition("building_number like :building_number");
                $criteria->params[':building_number'] = '%' . $building_number . '%';
            }

            if (isset($tel)&& !empty($tel)) {
                $res = User::model()->find("account = :account and merchant_id = :merchant_id and flag = :flag" ,array(':account' => $tel,':merchant_id' => $merchant_id,':flag' => FLAG_NO));
                if (isset($res)&& !empty($res)){
                    $user_id = $res->id;
                    $criteria->addCondition("user_id = :user_id");
                    $criteria->params[':user_id'] = $user_id;
                }
            }

            if (isset($verify_status)&&!empty($verify_status)){
                $criteria->addCondition("verify_status = :verify_status");
                $criteria->params[':verify_status'] = $verify_status;
            }

            $pages = new CPagination(Proprietor::model()->count($criteria));
            $pages->pageSize = Yii::app()->params['perPage'];
            $pages->applyLimit($criteria);
            $this->page = $pages;

            $proprietor = Proprietor::model()->findAll($criteria);

//            var_dump($proprietor,$tel);exit;
            $data = array();
            if (!empty($proprietor)) {
                foreach ($proprietor as $k => $v) {
                    $user = User::model()->findByPk($v['user_id']);
                    $community_name = Community::model()->findByPk($v['community_id']);
                    $data[$k]['id'] = $v['id'];
                    $data[$k]['user_id'] = $v['user_id'];
                    $data[$k]['name'] = $user['name'];
                    $data[$k]['tel'] = $user['account'];
                    $data[$k]['access_control_card_no'] = $v['access_control_card_no'];
                    $data[$k]['type'] = $v['type'];
                    $data[$k]['community_id'] = $community_name['name'];
                    $data[$k]['building_number'] = $v['building_number'];
                    $data[$k]['room_number'] = $v['room_number'];
                    $data[$k]['verify_status'] = $v['verify_status'];
                }
            }
            $result['status'] = APPLY_CLASS_SUCCESS;
            $result['data'] = $data;
        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : APPLY_CLASS_FAIL;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }

        return json_encode($result);
    }

    /**
     * @param $user_id
     * 删除业主
     */
    public function deleteProprietor($user_id,$proprietor_id){
        try{
            $transaction= Yii::app()->db->beginTransaction();//创建事务
            //保存数据
            $model = User::model()->findByPk($user_id);
            $model->flag = FLAG_YES;
            $model->last_time = date('Y-m-d H:i:s');

            $pro_model = Proprietor::model()->findByPk($proprietor_id);
            $pro_model->flag = FLAG_YES;
            $pro_model->last_time = date('Y-m-d H:i:s');

            if ($model->save()) {
                if ($pro_model->save()) {
                    $transaction->commit();//提交事务
                    $result ['status'] = APPLY_CLASS_SUCCESS; // 状态码
                    $result ['errMsg'] = '';
                }
            } else {
                $transaction->rollback();//回滚事务
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
     * @param $proprietor_id
     * 获取业主详情
     */
    public function getProprietorInfo($proprietor_id){

        $result = array();

        try {
            $proprietor = Proprietor::model()->findByPk($proprietor_id);

            $data = array();
            if (!empty($proprietor)) {
                    $user = User::model()->findByPk($proprietor->user_id);
                    $community_name = Community::model()->findByPk($proprietor->community_id);
                    $data['id'] = $proprietor->id;
                    $data['name'] = $user->name;
                    $data['tel'] = $user->account;
                    $data['access_control_card_no'] = $proprietor->access_control_card_no;
                    $data['type'] = $proprietor->type;
                    $data['community_id'] = $community_name->name;
                    $data['building_number'] = $proprietor->building_number;
                    $data['room_number'] = $proprietor->room_number;
                    $data['family_members'] = $proprietor->family_members;

                $result ['status'] = APPLY_CLASS_SUCCESS; // 状态码
                $result ['community_id'] = $proprietor->community_id;
                $result ['data'] = $data;
                $result ['errMsg'] = '';
            } else {
                $result ['status'] = APPLY_CLASS_FAIL; // 状态码
                $result ['data'] = '';
                throw new Exception('数据保存失败');
            }
        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }

        return $result;
    }

    /**
     * @param $proprietor_id
     * @param $verify_status
     * @param $remark
     * 修改业主审核状态
     */
    public function editProprietorVerifyStatus($proprietor_id,$flag,$remark){

        $result = array();
        try {
        $proprietor = Proprietor::model()->findByPk($proprietor_id);

            if ($flag == PROPRIETOR_VERIFY_STATUS_PASS){
                $proprietor->verify_status = PROPRIETOR_VERIFY_STATUS_PASS;
                $proprietor->remark = $remark;
                $proprietor->last_time = date('Y-m-d H:i:s');
            }else{
                $proprietor->verify_status = PROPRIETOR_VERIFY_STATUS_REJECT;
                $proprietor->remark = $remark;
                $proprietor->last_time = date('Y-m-d H:i:s');
            }
        
        if ($proprietor->save()) {
            $result ['status'] = APPLY_CLASS_SUCCESS; // 状态码
            $result ['errMsg'] = '';
            } else {
            $result ['status'] = APPLY_CLASS_FAIL; // 状态码
            $result ['errMsg'] = '数据保存失败'; // 错误信息
            }
        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }
        return json_encode($result);
    }

    /**
     * @param $proprietor_id
     * 获取业主审核状态
     */
    public function getProprietorVerifyStatus($proprietor_id){

    }

    /**
     * @param $proprietor_id
     * @param $info
     * 修改业主信息
     */
    public function editProprietor($proprietor_id,$info){

    }

    /**
     * @param $merchant_id
     * @param string $verify_status
     * @param $community_name
     * @param $building_number
     * @param $tel
     * @return string
     * 获取导出Excel业主列表信息
     */
    public function getProprietorExcel($merchant_id,$verify_status='',$community_name,$building_number,$tel){
        $result = array();
        try {
            //分页
            $criteria = new CDbCriteria();
            $criteria->addCondition("flag = :flag");
            $criteria->addCondition("merchant_id = :merchant_id");
            $criteria->params[':flag'] = FLAG_NO;
            $criteria->params[':merchant_id'] = $merchant_id;
            $criteria->order = "create_time desc";

            if ($community_name != '' && $community_name != NULL) {
                $criteria->addCondition("community_id = :community_id");
                $criteria->params[':community_id'] = $community_name;
            }

            if ($building_number != '' && $building_number != NULL) {
                $criteria->addCondition("building_number like :building_number");
                $criteria->params[':building_number'] = '%' . $building_number . '%';
            }

            if ($tel != '' && $tel != NULL) {
                $res = User::model()->find("account = :account" ,array(':account' => $tel));
                if ($res != '' && $res != NULL){
                    $user_id = $res -> id;
                    $criteria->addCondition("user_id = :user_id");
                    $criteria->params[':user_id'] = $user_id;
                }
            }

            if (isset($verify_status)&&!empty($verify_status)){
                $criteria->addCondition("verify_status = :verify_status");
                $criteria->params[':verify_status'] = $verify_status;
            }

            $proprietor = Proprietor::model()->findAll($criteria);

            $data = array();
            if (!empty($proprietor)) {
                foreach ($proprietor as $k => $v) {
                    $user = User::model()->findByPk($v['user_id']);
                    $community_name = Community::model()->findByPk($v['community_id']);
                    $data[$k]['id'] = $v['id'];
                    $data[$k]['user_id'] = $v['user_id'];
                    $data[$k]['name'] = $user['name'];
                    $data[$k]['tel'] = $user['account'];
                    $data[$k]['access_control_card_no'] = $v['access_control_card_no'];
                    $data[$k]['type'] = $v['type'];
                    $data[$k]['community_id'] = $community_name['name'];
                    $data[$k]['building_number'] = $v['building_number'];
                    $data[$k]['room_number'] = $v['room_number'];
                    $data[$k]['verify_status'] = $v['verify_status'];
                }
            }
            $result['status'] = APPLY_CLASS_SUCCESS;
            $result['data'] = $data;
        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : APPLY_CLASS_FAIL;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }

        return json_encode($result);
    }

    /**
     * @param $agentId
     * @return string
     * 导出业主Excel表单
     */
    public function getProDetailExcel($list)
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
        $objActSheet->setTitle('智慧物业');
        $objActSheet->mergeCells('A1:P1');

        $objActSheet->setCellValue('A1', '智慧物业_业主信息表');
        $objActSheet->setCellValue('A2', '姓名');
        $objActSheet->setCellValue('B2', '手机号');
        $objActSheet->setCellValue('C2', '门禁卡号');
        $objActSheet->setCellValue('D2', '类型');
        $objActSheet->setCellValue('E2', '小区');
        $objActSheet->setCellValue('F2', '楼号');
        $objActSheet->setCellValue('G2', '门牌号');
        $objActSheet->setCellValue('H2', '审核状态');

        //设置列宽
        $objActSheet->getColumnDimension('A')->setWidth(20);
        $objActSheet->getColumnDimension('B')->setWidth(20);
        $objActSheet->getColumnDimension('C')->setWidth(20);
        $objActSheet->getColumnDimension('D')->setWidth(20);
        $objActSheet->getColumnDimension('E')->setWidth(20);
        $objActSheet->getColumnDimension('F')->setWidth(20);
        $objActSheet->getColumnDimension('G')->setWidth(20);
        $objActSheet->getColumnDimension('H')->setWidth(20);
        //数据添加
        $i = 3;
        foreach ($list as $k => $v) {
            $objActSheet->setCellValue('A'.$i , $v['name']);//业主
            $objActSheet->setCellValue('B'.$i , $v['tel']);//手机号
            $objActSheet->setCellValue('C'.$i , $v['access_control_card_no']);//门禁卡号
            $objActSheet->setCellValue('D'.$i , $GLOBALS['__PROPRIETOR_TYPE'][$v['type']]);//类型
            $objActSheet->setCellValue('E'.$i , $v['community_id']);//小区名称
            $objActSheet->setCellValue('F'.$i , $v['building_number']);//楼号
            $objActSheet->setCellValue('G'.$i , $v['room_number']);//门牌号
            $objActSheet->setCellValue('H'.$i , $GLOBALS['__PROPRIETOR_VERIFY_STATUS'][$v['verify_status']]);//审核状态

            $i++;
        }

        //垂直/水平居中
        $objActSheet->getStyle('A2:P'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objActSheet->getStyle('A2:P'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objActSheet->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        //定义文件名
        $filename = '智慧物业-业主信息表' . date('YmdHis');
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
     * @param $list
     * @throws Exception
     * 导出业主信息EXCEL模板
     */
    public function getExcelModels()
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
        $objActSheet->setTitle('智慧物业');
        $objActSheet->mergeCells('A1:P1');

        $objActSheet->setCellValue('A1', '智慧物业_业主信息表');
        $objActSheet->setCellValue('A2', '姓名');
        $objActSheet->setCellValue('B2', '手机号');
        $objActSheet->setCellValue('C2', '门禁卡号');
        $objActSheet->setCellValue('D2', '类型');
        $objActSheet->setCellValue('E2', '小区');
        $objActSheet->setCellValue('F2', '楼号');
        $objActSheet->setCellValue('G2', '门牌号');
        $objActSheet->setCellValue('H2', '审核状态');

        //设置列宽
        $objActSheet->getColumnDimension('A')->setWidth(20);
        $objActSheet->getColumnDimension('B')->setWidth(20);
        $objActSheet->getColumnDimension('C')->setWidth(20);
        $objActSheet->getColumnDimension('D')->setWidth(20);
        $objActSheet->getColumnDimension('E')->setWidth(20);
        $objActSheet->getColumnDimension('F')->setWidth(20);
        $objActSheet->getColumnDimension('G')->setWidth(20);
        $objActSheet->getColumnDimension('H')->setWidth(20);

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
        //定义文件名
        $filename = '智慧物业-业主信息表' . date('YmdHis');
        $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
        $this->outPut($filename);
        $objWriter->save("php://output");
    }

}