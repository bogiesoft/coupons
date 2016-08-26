<?php
/**
 * Created by PhpStorm.
 * User: sundi
 * Date: 2016/6/23
 * Time: 15:36
 */
include_once(dirname(__FILE__).'/../mainClass.php');

//报修记录类
class ReportRepairRecordC extends mainClass
{
    /**
     * @param $merchant_id
     * @param string $id
     * @param string $status
     * @param string $community_id
     * @param string $tel
     * @param string $time
     * @return string
     * 报修记录列表
     */
    public function getRepairList($merchant_id,$id='', $status='', $community_id='',$tel='',$time='')
    {
        $result = array();
        try{
            $criteria = new CDbCriteria();
            if(isset($community_id)&&!empty($community_id)){
                $criteria->addCondition("community_id = :community_id");
                $criteria->params[':community_id'] = $community_id;
            }
            if(isset($id)&&!empty($id)){
                $criteria->addCondition("id = :id");
                $criteria->params[':id'] = $id;
            }
            if(isset($tel)&&!empty($tel)){
                $criteria->addCondition("tel = :tel");
                $criteria->params[':tel'] = $tel;
            }
            if(isset($status)&&!empty($status)){
                $criteria->addCondition("status = :status");
                $criteria->params[':status'] = $status;
            }

            //根据时间范围进行搜索
            if (isset($time)&&!empty($time)){
                $Time1 = explode('-', $time);
                //时间字段类型为timestamp
                $Time1[0]= date('Y-m-d H-i-s',strtotime($Time1[0].' 00:00:00'));
                $Time1[1]= date('Y-m-d H-i-s',strtotime($Time1[1].' 23:59:59'));
                $criteria->addCondition("repair_time > :start_time");
                $criteria->addCondition("repair_time < :end_time");
                $criteria->params[':start_time'] = $Time1[0];
                $criteria->params[':end_time'] = $Time1[1];
            }

            $criteria->addCondition("flag = :flag");
            $criteria->addCondition("merchant_id = :merchant_id");
            $criteria->params[':flag'] = FLAG_NO;
            $criteria->params[':merchant_id'] = $merchant_id;

            $criteria->order = "create_time desc";
            $pages = new CPagination(ReportRepairRecord::model()->count($criteria));
            $pages->pageSize = Yii::app()->params['perPage'];
            $pages->applyLimit($criteria);

            $ReportRepairRecord = ReportRepairRecord::model()->findAll($criteria);

            $data = array();
            if (!empty($ReportRepairRecord)) {

                foreach ($ReportRepairRecord as $k => $v) {
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

                    //获取业主姓名/手机号
                    $user = User::model()->find(
                        'id=:user_id',
                        array(':user_id'=>$v['user_id'])
                    );
                    $userinfo=$user->attributes;
                    $data[$k]['name']=$userinfo['name'];
                    $data[$k]['account']=$userinfo['account'];

                    //获取小区名字
                    $community = Community::model()->find(
                        'id=:community_id',
                        array(':community_id'=>$v['community_id'])
                    );
                    $data[$k]['community_name'] = $community['name'];

                    $data[$k]['id'] = $v['id'];
                    $data[$k]['user_id'] = $v['user_id'];
                    $data[$k]['address'] = $v['address'];
                    $data[$k]['repair_time'] = $v['repair_time'];
                    $data[$k]['area_type'] = $v['area_type'];
                    $data[$k]['repair_id'] = $v['id'];
                    $data[$k]['status'] = $v['status'];
                    $data[$k]['detail'] = $v['detail'];
                    $data[$k]['remark'] = $v['remark'];
                    $data[$k]['img'] = json_decode($v['img']);

                }
            }
            $result['status'] = APPLY_CLASS_SUCCESS;
            $result['data'] = $data;
        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }
        $this->page = $pages;
        return json_encode($result);
    }

    /**
     * 修改报修记录状态
     */
    public function editReportRepairRecordStatus($id) {
        $result = array();

        try {
            $cri = new CDbCriteria();
            $cri -> addCondition('id = :id');
            $cri -> params[':id'] = $id;
            $cri -> addCondition('flag = :flag');
            $cri -> params[':flag'] = FLAG_NO;
            $model = ReportRepairRecord::model() -> find($cri);
            //若报修状态为“待保修”，则更改状态为“已报修”
            if ($model) {
                if ($model -> status == REPORT_REPAIR_RECORD_STATUS_WAITING) {
                    $model -> status = REPORT_REPAIR_RECORD_STATUS_COMPLETE;
                }
                if ($model -> update()) {
                    $result['status'] = APPLY_CLASS_SUCCESS;
                    $result['errMsg'] = '';
                }
            }
        }  catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : APPLY_CLASS_FAIL;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }

        return json_encode($result);
    }

    /**
     * @param $merchant_id
     * @param string $id
     * @param string $status
     * @param string $community_id
     * @param string $tel
     * @param string $time
     * @return string
     * 获取报修记录EXCEL导出信息
     */
    public function getRepairMsg($merchant_id,$id='', $status='', $community_id='',$tel='',$time='')
    {
        $result = array();
        try{
            $criteria = new CDbCriteria();
            if(isset($community_id)&&!empty($community_id)){
                $criteria->addCondition("community_id = :community_id");
                $criteria->params[':community_id'] = $community_id;
            }
            if(isset($id)&&!empty($id)){
                $criteria->addCondition("id = :id");
                $criteria->params[':id'] = $id;
            }
            if(isset($tel)&&!empty($tel)){
                $criteria->addCondition("tel = :tel");
                $criteria->params[':tel'] = $tel;
            }
            if(isset($status)&&!empty($status)){
                $criteria->addCondition("status = :status");
                $criteria->params[':status'] = $status;
            }

            //根据时间范围进行搜索
            if (isset($time)&&!empty($time)){
                $Time1 = explode('-', $time);
                //时间字段类型为timestamp
                $Time1[0]= date('Y-m-d H-i-s',strtotime($Time1[0].' 00:00:00'));
                $Time1[1]= date('Y-m-d H-i-s',strtotime($Time1[1].' 23:59:59'));
                $criteria->addCondition("repair_time > :start_time");
                $criteria->addCondition("repair_time < :end_time");
                $criteria->params[':start_time'] = $Time1[0];
                $criteria->params[':end_time'] = $Time1[1];
            }

            $criteria->addCondition("flag = :flag");
            $criteria->params[':flag'] = FLAG_NO;

            $criteria->order = "create_time desc";

            $ReportRepairRecord = ReportRepairRecord::model()->findAll($criteria);

            $data = array();
            if (!empty($ReportRepairRecord)) {

                foreach ($ReportRepairRecord as $k => $v) {
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

                    //获取业主姓名/手机号
                    $user = User::model()->find(
                        'id=:user_id',
                        array(':user_id'=>$v['user_id'])
                    );
                    $userinfo=$user->attributes;
                    $data[$k]['name']=$userinfo['name'];
                    $data[$k]['account']=$userinfo['account'];

                    //获取小区名字
                    $community = Community::model()->find(
                        'id=:community_id',
                        array(':community_id'=>$v['community_id'])
                    );
                    $data[$k]['community_name'] = $community['name'];

                    $data[$k]['id'] = $v['id'];
                    $data[$k]['user_id'] = $v['user_id'];
                    $data[$k]['address'] = $v['address'];
                    $data[$k]['repair_time'] = $v['repair_time'];
                    $data[$k]['area_type'] = $v['area_type'];
                    $data[$k]['repair_id'] = $v['id'];
                    $data[$k]['status'] = $v['status'];
                    $data[$k]['detail'] = $v['detail'];
                    $data[$k]['remark'] = $v['remark'];
                    $data[$k]['img'] = $v['img'];

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

    /**
     * @param $list
     * @param $status
     * @throws Exception
     * 导出报修Excel表单
     */
    public function toRepairExcel($list, $status)
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

        //全部报修记录
        if ($status == '') {
            $objActSheet->setCellValue('A1', '智慧物业_报修记录表');
            $objActSheet->setCellValue('A2', '姓名');
            $objActSheet->setCellValue('B2', '小区');
            $objActSheet->setCellValue('C2', '手机号');
            $objActSheet->setCellValue('D2', '报修时间');
            $objActSheet->setCellValue('E2', '地址');
            $objActSheet->setCellValue('F2', '区域');
            $objActSheet->setCellValue('G2', '报修内容');
            $objActSheet->setCellValue('H2', '住户反馈');
            $objActSheet->setCellValue('I2', '状态');

            //设置列宽
            $objActSheet->getColumnDimension('A')->setWidth(20);
            $objActSheet->getColumnDimension('B')->setWidth(20);
            $objActSheet->getColumnDimension('C')->setWidth(20);
            $objActSheet->getColumnDimension('D')->setWidth(20);
            $objActSheet->getColumnDimension('E')->setWidth(20);
            $objActSheet->getColumnDimension('F')->setWidth(20);
            $objActSheet->getColumnDimension('G')->setWidth(20);
            $objActSheet->getColumnDimension('H')->setWidth(20);
            $objActSheet->getColumnDimension('I')->setWidth(20);
            //数据添加
            $i = 3;
            foreach ($list as $k => $v) {
                $objActSheet->setCellValue('A'.$i , $v['name']);//业主
                $objActSheet->setCellValue('B'.$i , $v['community_name']);//小区
                $objActSheet->setCellValue('C'.$i , $v['account']);//手机号
                $objActSheet->setCellValue('D'.$i , $v['repair_time']);//报修时间
                $objActSheet->setCellValue('E'.$i , $v['address']);//地址
                $objActSheet->setCellValue('F'.$i , $GLOBALS['REPORT_REPAIR_RECORD_TYPE'][$v['area_type']]);//区域
                $objActSheet->setCellValue('G'.$i , $v['detail']);//报修内容
                $objActSheet->setCellValue('H'.$i , $v['remark']);//住户反馈
                $objActSheet->setCellValue('I'.$i , $GLOBALS['REPORT_REPAIR_RECORD_STATUS'][$v['status']]);//状态

                $i++;
            }
        }

        //待报修记录
        if ($status == REPORT_REPAIR_RECORD_STATUS_WAITING) {
            $objActSheet->setCellValue('A1', '智慧物业_报修记录表');
            $objActSheet->setCellValue('A2', '姓名');
            $objActSheet->setCellValue('B2', '小区');
            $objActSheet->setCellValue('C2', '手机号');
            $objActSheet->setCellValue('D2', '报修时间');
            $objActSheet->setCellValue('E2', '地址');
            $objActSheet->setCellValue('F2', '区域');
            $objActSheet->setCellValue('G2', '报修内容');
            $objActSheet->setCellValue('H2', '住户反馈');
            $objActSheet->setCellValue('I2', '状态');

            //设置列宽
            $objActSheet->getColumnDimension('A')->setWidth(20);
            $objActSheet->getColumnDimension('B')->setWidth(20);
            $objActSheet->getColumnDimension('C')->setWidth(20);
            $objActSheet->getColumnDimension('D')->setWidth(20);
            $objActSheet->getColumnDimension('E')->setWidth(20);
            $objActSheet->getColumnDimension('F')->setWidth(20);
            $objActSheet->getColumnDimension('G')->setWidth(20);
            $objActSheet->getColumnDimension('H')->setWidth(20);
            $objActSheet->getColumnDimension('I')->setWidth(20);
            //数据添加
            $i = 3;
            foreach ($list as $k => $v) {
                $objActSheet->setCellValue('A'.$i , $v['name']);//业主
                $objActSheet->setCellValue('B'.$i , $v['community_name']);//小区
                $objActSheet->setCellValue('C'.$i , $v['account']);//手机号
                $objActSheet->setCellValue('D'.$i , $v['repair_time']);//报修时间
                $objActSheet->setCellValue('E'.$i , $v['address']);//地址
                $objActSheet->setCellValue('F'.$i , $v['area_type']);//区域
                $objActSheet->setCellValue('G'.$i , $v['detail']);//报修内容
                $objActSheet->setCellValue('H'.$i , $v['remark']);//住户反馈
                $objActSheet->setCellValue('I'.$i , $GLOBALS['REPORT_REPAIR_RECORD_STATUS'][$v['status']]);//状态

                $i++;
            }
        }

        //已报修记录
        if ($status == REPORT_REPAIR_RECORD_STATUS_COMPLETE) {
            $objActSheet->setCellValue('A1', '智慧物业_报修记录表');
            $objActSheet->setCellValue('A2', '姓名');
            $objActSheet->setCellValue('B2', '小区');
            $objActSheet->setCellValue('C2', '手机号');
            $objActSheet->setCellValue('D2', '报修时间');
            $objActSheet->setCellValue('E2', '地址');
            $objActSheet->setCellValue('F2', '区域');
            $objActSheet->setCellValue('G2', '报修内容');
            $objActSheet->setCellValue('H2', '住户反馈');
            $objActSheet->setCellValue('I2', '状态');

            //设置列宽
            $objActSheet->getColumnDimension('A')->setWidth(20);
            $objActSheet->getColumnDimension('B')->setWidth(20);
            $objActSheet->getColumnDimension('C')->setWidth(20);
            $objActSheet->getColumnDimension('D')->setWidth(20);
            $objActSheet->getColumnDimension('E')->setWidth(20);
            $objActSheet->getColumnDimension('F')->setWidth(20);
            $objActSheet->getColumnDimension('G')->setWidth(20);
            $objActSheet->getColumnDimension('H')->setWidth(20);
            $objActSheet->getColumnDimension('I')->setWidth(20);
            //数据添加
            $i = 3;
            foreach ($list as $k => $v) {
                $objActSheet->setCellValue('A'.$i , $v['name']);//业主
                $objActSheet->setCellValue('B'.$i , $v['community_name']);//小区
                $objActSheet->setCellValue('C'.$i , $v['account']);//手机号
                $objActSheet->setCellValue('D'.$i , $v['repair_time']);//报修时间
                $objActSheet->setCellValue('E'.$i , $v['address']);//地址
                $objActSheet->setCellValue('F'.$i , $v['area_type']);//区域
                $objActSheet->setCellValue('G'.$i , $v['detail']);//报修内容
                $objActSheet->setCellValue('H'.$i , $v['remark']);//住户反馈
                $objActSheet->setCellValue('I'.$i , $GLOBALS['REPORT_REPAIR_RECORD_STATUS'][$v['status']]);//状态

                $i++;
            }
        }

        //垂直/水平居中
        $objActSheet->getStyle('A2:P'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objActSheet->getStyle('A2:P'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objActSheet->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        //定义文件名
        $filename = '智慧物业-报修记录表' . date('YmdHis');
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

    

}
