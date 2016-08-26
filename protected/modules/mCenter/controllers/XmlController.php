<?php

class XmlController extends mCenterController{
	//解析省市区xml到数据库
	public function actionGetCity(){

		$doc = new DOMDocument();
		$doc -> load('C:\www\WQ\proj\themes\classic\gj\js\city.xml');
		
		
		$transaction = Yii::app()->db->beginTransaction();
		try {
			//读取所有省
			$province = $doc -> getElementsByTagName('province');
			foreach ($province as $v){
				$city = new ShopCity();
				$city -> name = $v -> getAttribute('name');
				$city -> code = $v -> getAttribute('postcode');
				$city -> level = 1;
				$city -> create_time = new CDbExpression('now()');
				if($city -> save()){
					
				}else{
					$transaction->rollback(); //数据回滚
					throw new Exception('保存失败'.$city -> name);
				}
			}
			
			//读取所有市
			$cityd = $doc -> getElementsByTagName('city');
			foreach ($cityd as $v){
				$city = new ShopCity();
				$city -> name = $v -> getAttribute('name');
				$city -> code = $v -> getAttribute('postcode');
				$city -> level = 2;
				$city -> create_time = new CDbExpression('now()');
				if($city -> save()){
						
				}else{
					$transaction->rollback(); //数据回滚
					throw new Exception('保存失败'.$city -> name);
				}
			}
			
			//读取所有区
			$area = $doc -> getElementsByTagName('area');
			foreach ($area as $v){
				$city = new ShopCity();
				$city -> name = $v -> getAttribute('name');
				$city -> code = $v -> getAttribute('postcode');
				$city -> level = 3;
				$city -> create_time = new CDbExpression('now()');
				if($city -> save()){
			
				}else{
					$transaction->rollback(); //数据回滚
					throw new Exception('保存失败'.$city -> name);
				}
			}
			$transaction->commit(); //数据提交
		} catch (Exception $e) {
			$transaction->rollback(); //数据回滚
			print_r($e -> getMessage());
		}
		


// 		foreach ($root as $v){
// 			$province = $v -> getElementsByTagName['province'];
// 			foreach ($province as $k){
// 				$city = $k -> getElementsByTagName['city'];
// 				foreach ($city as $x){
// 					$area = $x -> getElementsByTagName['area'];
// 					foreach ($area as $y){
// 						echo $y -> getAttribute('name');
// 					}
// 				}
// 			}
// 		}
	}
}