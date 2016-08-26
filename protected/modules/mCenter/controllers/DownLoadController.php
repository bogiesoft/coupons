<?php
/*
 * 管家资料下载
 * xyf  2015/07/29
 */
class DownLoadController extends mCenterController
{
	public $layout = 'column1'; 
	public function actionDownLoad($downLoadType)
	{
		$list = array();
		$downLoadC = new DownLoadC();
		$result = $downLoadC -> dataList($downLoadType,PUBLIC_TO_GJ);
		$result = json_decode($result,true);
		if($result['status'] == ERROR_NONE){
			if(isset($result['data'])){
				$list = $result['data'];
			}
		}
		$document_class = '';
		$video_class = '';
		if($downLoadType == DOWNLOAD_TYPE_DOCUMENT){ //点击资料下载的按钮样式变化
			$document_class = 'bg';
		}
		
		if($downLoadType == DOWNLOAD_TYPE_VIDEO){ //点击视频下载的按钮样式变化
			$video_class = 'bg';
		}
		
		$this->render('downLoad',array('list'=>$list,'document_class'=>$document_class,'video_class'=>$video_class));
	}
}