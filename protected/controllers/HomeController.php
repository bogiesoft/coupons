<?php
class HomeController extends Controller{
	//跳转到分销系统
	public function actionIndex(){
		$this->redirect(YII::app() -> createUrl('index/home/index'));
	}
}