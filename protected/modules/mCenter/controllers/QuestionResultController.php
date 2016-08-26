<?php
//问卷调查
class QuestionResultController extends mCenterController
{
	public function actionQuestionResult(){
		$merchant_id = 13;
		$merchantC = new MerchantC();
		$re = json_decode($merchantC -> getQuestionResult($merchant_id));
		if($re -> status == ERROR_NONE){
			$data = $re -> data;
		}
		$this->render('questionResult',array(
				'data' => $data,
				'page' => $merchantC -> page,
//				'list' => $re -> list
		));
	}
	
	//导出excel
	public function actionExportExcel(){
		$merchant_id = 13;
		$merchantC = new MerchantC();
		$re = json_decode($merchantC -> exportQuestionResult($merchant_id));
		if($re -> status == ERROR_NONE){
//			$this->getExcel($re -> list);
		}
	}
	/**
	 * 获取excel
	 */
	public function getExcel($model)
	{
		include 'PHPExcel/Reader/Excel2007.php';
		include 'PHPExcel/Reader/Excel5.php';
		include 'PHPExcel/IOFactory.php';
	
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1','联系人')
		->setCellValue('B1','所属公司')
		->setCellValue('C1','电话')
		->setCellValue('D1','目前，支付宝和微信支付注册流程是否顺利？')
		->setCellValue('E1','注册流程是否清晰明确？')
		->setCellValue('F1','注册中，供应商协助是否及时？')
		->setCellValue('G1','供应商对账人及联系方式是否知晓？')
		->setCellValue('H1','对账中，存在哪些问题？')
		->setCellValue('I1','对对账流程有哪些建议？')
		->setCellValue('J1','对收银台功能是否熟悉了解？')
		->setCellValue('K1','对收银台功能是否有其他建议？')
		->setCellValue('L1','对支付宝和微信支付是否使用习惯？');
	
		//设置列宽
		$objActSheet = $objPHPExcel->getActiveSheet();
		$objActSheet->getColumnDimension('A')->setWidth(30);
		$objActSheet->getColumnDimension('B')->setWidth(20);
		$objActSheet->getColumnDimension('C')->setWidth(20);
		$objActSheet->getColumnDimension('D')->setWidth(25);
		$objActSheet->getColumnDimension('E')->setWidth(20);
		$objActSheet->getColumnDimension('F')->setWidth(20);
		$objActSheet->getColumnDimension('G')->setWidth(30);
		$objActSheet->getColumnDimension('H')->setWidth(30);
		$objActSheet->getColumnDimension('I')->setWidth(30);
		$objActSheet->getColumnDimension('J')->setWidth(30);
		$objActSheet->getColumnDimension('K')->setWidth(30);
		$objActSheet->getColumnDimension('L')->setWidth(30);
		//设置sheet名称
		$objActSheet -> setTitle('问卷调查结果');
		//数据添加
		$i=2;
		foreach($model as $k=>$v){
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$i, $v->contacts)
			->setCellValue('B'.$i,$v->branch_company)
			->setCellValueExplicit('C'.$i,$v->tel, PHPExcel_Cell_DataType::TYPE_STRING)
			->setCellValue('D'.$i,$v->question1)
			->setCellValue('E'.$i,$v->question2)
			->setCellValue('F'.$i,$v->question3)
			->setCellValue('G'.$i,$v->question4)
			->setCellValue('H'.$i,$v->question5)
			->setCellValue('I'.$i,$v->question6)
			->setCellValue('J'.$i,$v->question7)
			->setCellValue('K'.$i,$v->question8)
			->setCellValue('L'.$i,$v->question9);
			$i++;
		}
	
		$filename = date('YmdHis');//定义文件名
	
		$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
		//$objWriter->save(str_replace('.php', '.xls', __FILE__));
		$this->outPut($filename);
		$objWriter->save("php://output");
	
	}
	
	
	/**
	 * 到浏览器  浏览器下载excel
	 */
	public function outPut($filename)
	{
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