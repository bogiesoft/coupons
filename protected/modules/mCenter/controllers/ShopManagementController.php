<?php

class ShopManagementController extends mCenterController
{
    public function actionIndex(){
        
    }
    /**
     * 在线店铺管理
     */
    public function actionOnlineShop()
    {  
        $show = '';        
        $merchantId = Yii::app()->session['merchant_id'];
         $ret        = new MerchantC();
         
        $storeC = new StoreC();
        $result = json_decode($storeC -> getStoreList($merchantId,'','',''),true);
        if($result['status'] == ERROR_NONE)
        {
            $choose = $result['data']['list']; 
        } 
        
        $see = $ret->Show($merchantId);
        $show = json_decode($see,true);
        $storeId = '';
        $pous = '';
        if($show['status'] == ERROR_NONE)
        {
            $show    = $show['data'];
            $storeId = $show['store_id']; 
            $pous    = explode(',', $show['coupons_id']);
        }
        if(isset($_POST) && $_POST)
        {        
            $store = ',';
            if(isset($_POST['store']) && $_POST['store']){
                foreach ($_POST['store'] as $k => $v){
                    $store .= isset($v)?$v.',' : '';
                }
            }
            $ifBook = isset($_POST['if_book']) ? $_POST['if_book'] : '';
            if(empty($_POST['if_book'])){
                $ifBook = MERCHANT_BOOK_CLOSE;
            }
            
            $introduction = isset($_POST['introduction']) ? $_POST['introduction'] : '';
           	if(empty($_POST['introduction'])){
           		$introduction = '';
           	}

            $ifCoupons = isset($_POST['if_coupons']) ? $_POST['if_coupons'] : '';
            if(empty($_POST['if_coupons'])){
                $ifCoupons = MERCHANT_COUPONS_CLOSE;
            }
            $ifHongbao = isset($_POST['if_hongbao']) ? $_POST['if_hongbao'] : '';
            if(empty($_POST['if_hongbao'])){
                $ifHongbao = MERCHANT_HONGBAO_CLOSE;
            }
            
			if(isset($_POST['cover']) && !empty($_POST['cover'])){
				$img = $_POST['cover'];
			}else{
				$img = '';
			}
			
			if(isset($_POST['coverlogo']) && !empty($_POST['coverlogo'])){
				$logo_img = $_POST['coverlogo'];
			}else{
				$logo_img = '';
			}
			
        	if(isset($_POST['shopname']) && !empty($_POST['shopname'])){
				$name = $_POST['shopname'];
			}else{
				$name = '';
			}
			
            $model  = $ret->addOnlineShop($merchantId,$img,$logo_img,$store,$ifBook,'',$ifCoupons,$ifHongbao,'',$introduction,$name);
            $models = json_decode($model,true);
            if($models['status'] == ERROR_NONE)
            {                
                $url = Yii::app()->createUrl('mCenter/ShopManagement/OnlineShop');
                echo "<script>alert('设置成功');window.location.href='$url'</script>";
            }
        }
        $this->render('onlineShop',array(
            'choose'=>$choose,
            'show'=>$show,
            'storeId'=>$storeId,
            'pous'=>$pous
        ));
    }
    
   
}

