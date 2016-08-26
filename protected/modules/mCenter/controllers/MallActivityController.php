<?php
//商城活动类
class MallActivityController extends mCenterController{
    
    public function actionIndex(){
        
    }
    
    //商城活动列表
    public function actionMallActivity(){
        $merchant_id = Yii::app() -> session['merchant_id'];
        $DshopC = new DShopC();
        $result = json_decode($DshopC -> getDmallActivityList($merchant_id));
        if($result -> status == ERROR_NONE){
            $list = $result -> data;
        }
        $this->render('mallActivity',array(
            'list' => $list,
            'pages' => $DshopC -> page
        ));
    }
    
    //添加商城活动
    public function actionAddMallActivity(){
        $this->render('addMallActivity');
    }
    
    //创建商城活动
    public function actionCreateMallActivity() {
        $merchant_id = Yii::app() -> session['merchant_id'];
        $couponC = new CouponsC();
        $result = json_decode($couponC -> getUseOnlineCouponList($merchant_id));
        if($result -> status == ERROR_NONE){
               $couponsList = $result -> data;
        }
        
        if(isset($_GET['type']) && !empty($_GET['type'])){
            $type = $_GET['type'];
            
            //如果是首单立减
            if($type == DMALL_ACTIVITY_TYPE_SDLJ){
                $mallActivity = new DMallActivity();
                $re = json_decode($mallActivity -> checkSdljActivity($merchant_id));
                if($re -> status == ERROR_NONE){
                    if($re -> data == 1){
                        $url = Yii::app()->createUrl('mCenter/MallActivity/AddMallActivity');
                        echo "<script>alert('已存在此类型活动');window.location.href='$url'</script>";
                        exit;
                    }
                }
            }   
        }
        
        
        if(isset($_POST) && !empty($_POST)){
            $flag = 1;
            //验证活动名称
            if(!isset($_POST['activity_name']) || empty($_POST['activity_name'])){
                $flag = 2;
                Yii::app() -> user -> setflash('activity_name_error',MESSAGE_DMALL_ACTIVITY_NAME_NONE);
            }else{
                //验证活动名称格式是否正确
                if(strpos($_POST['activity_name'], " ") || mb_strlen($_POST['activity_name'],'utf8') > 20){
                    $flag = 2;
                    Yii::app() -> user -> setflash('activity_name_error',MESSAGE_DMALL_ACTIVITY_NAME_WRONG);
                }
            }
            //验证活动发放量
            if(!isset($_POST['activity_num']) || empty($_POST['activity_num'])){
                $flag = 2;
                Yii::app() -> user -> setflash('activity_num_error',MESSAGE_DMALL_ACTIVITY_NUM_NONE);
            }else{
                if(is_int($_POST['activity_num'])){
                    $flag = 2;
                    Yii::app() -> user -> setflash('activity_num_error',MESSAGE_DMALL_ACTIVITY_NUM_WRONG);
                }
            }
            
            //日期验证
            if(!isset($_POST['Time']) || empty($_POST['Time'])){
                $flag = 2;
                Yii::app() -> user -> setflash('activity_time_error',MESSAGE_DMALL_ACTIVITY_TIME_NONE);
            }
            //券验证
            if(!isset($_POST['activity_coupon_id']) || empty($_POST['activity_coupon_id'])){
                $flag = 2;
                Yii::app() -> user -> setflash('activity_coupon_id_error',MESSAGE_DMALL_ACTIVITY_COUPON_ID_NONE);
            }

            //验证图片
            if(!isset($_POST['activity_img']) || empty($_POST['activity_img'])){
                $flag = 2;
                Yii::app() -> user -> setflash('activity_img_error',MESSAGE_DMALL_ACTIVITY_IMG_NONE);
            }
            
            //原价验证
            if(isset($_POST['original_price']) || !empty($_POST['original_price'])){
            	$price = $_POST['original_price'];
            	$tmp = explode(".", $price);
            	if (!is_numeric($price) || count($tmp) > 1 && 	strlen($tmp[1]) > 2) {
            		$flag = 2;
            		Yii::app() -> user -> setflash('activity_price_error',MESSAGE_DMALL_ACTIVITY_PRICE);
            	}
            }

            //验证通过
            if($flag == 1){
                $type = $_POST['type'];
                $name = $_POST['activity_name'];
                $num = $_POST['activity_num'];
                $img = $_POST['activity_img'];
                $time = $_POST['Time'];
                $original_price = $_POST['original_price'];
                $arr = explode('-', $time);
                $start_time = trim($arr[0]);
                $end_time = trim($arr[1]);
                $coupon_id = $_POST['activity_coupon_id'];
                
                $dShopC = new DShopC();
                $result = json_decode($dShopC -> addDmallActivity($merchant_id, $type, $name, $num, $start_time, $end_time, $coupon_id,$img,$original_price));
                if($result -> status == ERROR_NONE){
                    $this -> redirect('MallActivity/MallActivity');
                }
            }
        }
        $this->render('createMallActivity',array(
            'couponsList' => $couponsList,
            'type' => $type
        ));
    }
    
    //删除活动
    public function actionDelDMallActivity(){
        $id = $_GET['id'];
        $dshopC = new DShopC();
        $result = json_decode($dshopC -> delDMallActivity($id));
        if($result -> status  == ERROR_NONE){
            $this -> redirect('MallActivity/MallActivity');
        }
    }
    
    //ajax 选择电子券
    public function actionSelectCoupon(){
        $id = $_POST['id'];
        $dshopC = new DShopC();
        echo $dshopC -> getCouponStock($id);
    }
    
    
    
    
}