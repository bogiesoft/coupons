<?php
/**
 * 相册管理
 */
class HotelController extends mCenterController
{
    public function actionIndex(){
        
    }
    /**
     * 房间管理
     */
    public function actionRoomManagement(){
        $list = array();
        // 获取房间类型列表
        $merchant_id = Yii::app()->session['merchant_id'];
        $hotelC_ob = $this->getHotelC();
        $room_list = json_decode($hotelC_ob->getHotelRoomList($merchant_id),true);
        if ($room_list['status'] == ERROR_NONE){
            $list = $room_list['data']['list'];
            if (!empty($list)){
                foreach ($list as $k=>$v){
                    $list[$k]['room_img'] = explode(',', $v['room_img']);
                }
            }
        }
        $this->render('roomManagement',array(
            'list' => $list,
            'pages' => $hotelC_ob->page
        ));
    }
    
    /**
     * 添加房间
     */
    public function actionAddHotelRoom(){
        
        $store_list = array();
        $room_info = array();
        $room_imgs = array();
        $merchant_id = Yii::app()->session['merchant_id'];
        $storeC_ob = $this->getStoreC();
        $hotelC_ob = $this->getHotelC();
        //获取门店名称、id列表
        $sotre_res = json_decode($storeC_ob->getStoreListAll($merchant_id),true);
        if ($sotre_res['status'] == ERROR_NONE){
            $store_arr = $sotre_res['data']['list'];
            if (!empty($store_arr)){
                foreach ($store_arr as $k => $v){
                    if (empty($v['branch_name'])){
                        $store_list[$v['id']] = $v['name'];
                    }else{
                        $store_list[$v['id']] = $v['name'].'('.$v['branch_name'].')';
                    }
                }
            }
        }
        if (isset($_GET['id']) && !empty($_GET['id']) && empty($_POST)){
            //修改操作
            $room_id = $_GET['id'];
            $room_res = json_decode($hotelC_ob->getHotelRoomInfo($room_id),true);
            if($room_res['status'] == ERROR_NONE){
                $room_info = $room_res['data'];
                $room_imgs = $room_info['room_img'];
            }
        }elseif (isset($_POST) && !empty($_POST)){
            //表单数据提交
            $post = $_POST;
            $store_id = $post['store_id'];
            $name = $post['name'];
            $price = $post['price'];
            if (!empty($post['room_img'])){
                $room_img = implode(',', $post['room_img']);
                $room_imgs = $room_img;
            }else{
                $room_img = array();
            }
            $room_details = $post['room_details'];
            $save_flag = 1;
            if (empty($name)) {
                Yii::app()->user->setFlash('name','房间类型不能为空');
                $save_flag = 0;
            }
            if(mb_strlen($name,'UTF8') > 20){
                Yii::app()->user->setFlash('name','类型名称长度超出限制');
                $save_flag = 0;
            }
            if (!isset($price)) {
                Yii::app()->user->setFlash('price','请填写价格');
                $save_flag = 0;
            }
            if (!is_numeric($price)) {
                Yii::app()->user->setFlash('price','价格格式错误');
                $save_flag = 0;
            }
            if (empty($room_img)) {
                Yii::app()->user->setFlash('room_img','请上传图片');
                $save_flag = 0;
            }
//             if (empty($room_details)) {
//                 Yii::app()->user->setFlash('room_details','请填写详细信息');
//                 $save_flag = 0;
//             }
            if ($save_flag == 1){
                if (!empty($_GET['id'])){
                    $id = $_GET['id'];
                    $save_res = json_decode($hotelC_ob->editHotelRoom($id,$store_id, $merchant_id, $name, $price, $room_img, $room_details),true);
                }else{
                    $save_res = json_decode($hotelC_ob->addHotelRoom($store_id, $merchant_id, $name, $price, $room_img, $room_details),true);
                }
                if ($save_res['status'] == ERROR_NONE) {
                    $this->redirect('RoomManagement');
                }
            }
            $room_info = $post;
        }
        //修改信息或者提交失败,数据处理
        if (!empty($room_info)){
            if (!empty($room_imgs)){
                $room_imgs = explode(',', $room_imgs);
            }
        }
        $this->render('addHotelRoom',array(
            'store_list' => $store_list,
            'room_info' => $room_info,
            'room_imgs' => $room_imgs,
        ));
    }
    
    /**
     * 删除房间
     */
    public function actionDeleteHotelRoom(){
        if (isset($_GET['id']) && !empty($_GET['id'])){
            $id = $_GET['id'];
            $getHotelC_ob = $this -> getHotelC();
            $res = $getHotelC_ob -> deleteHotelRoom($id);
        }
        $this->redirect('roomManagement');
    }
    
    /**
     * 订单管理
     */
    public function actionOrderManagement(){
        $merchant_id = Yii::app()->session['merchant_id'];
        $order_list = array();
        $hotelC_ob = $this -> getHotelC();
        
        $status = '';
        if (isset($_GET['status']) && !empty($_GET['status'])){
            $status = $_GET['status'];
        }
        $time = '';
        if (isset($_POST['time']) && !empty($_POST['time'])){
            $time = $_POST['time'];
        }
        $keyword = '';
        if (isset($_POST['keyword']) && !empty($_POST['keyword'])){
            $keyword = $_POST['keyword'];
        }
        $order_res = json_decode($hotelC_ob->getHotelOrderListAll($merchant_id,$status,$keyword,$time),true);
        if ($order_res['status'] == ERROR_NONE){
            $order_list = $order_res['data'];
        } 
        $this->render('orderManagement',array(
            'order_list' => $order_list,
            'pages' => $hotelC_ob->page
        ));
    }
    
    /**
     * 酒店信息
     */
    public function actionHotelInformation(){
        $hotel_info = array();
        $room_imgs = array();
        $merchant_id = Yii::app()->session['merchant_id'];
        $hotelC_ob = $this -> getHotelC();
        if (isset($_POST) && !empty($_POST)){
            //表单数据提交
            $post = $_POST;
            
            $address = $post['address'];
            $name = $post['name'];
            $tel = $post['tel'];
            $save_flag = 1;
            $preg_tel = "/^[0-9\-()（）]{7,18}$/";
            if (!empty($post['room_img'])){
                $img = implode(',', $post['room_img']);
                $room_imgs = $post['room_img'];
            }else{
                $img = array();
            }
            if (empty($name)) {
                Yii::app()->user->setFlash('name','房间类型不能为空');
                $save_flag = 0;
            }
            if(mb_strlen($name,'UTF8') > 20){
                Yii::app()->user->setFlash('name','类型名称长度超出限制');
                $save_flag = 0;
            }
            if (!isset($tel)) {
                Yii::app()->user->setFlash('tel','请填写电话');
                $save_flag = 0;
            }
            if (!preg_match($preg_tel, $tel)) {
                Yii::app()->user->setFlash('tel','电话格式错误');
                $save_flag = 0;
            }
            if (empty($img)) {
                Yii::app()->user->setFlash('img','请上传图片');
                $save_flag = 0;
            } 
            if ($save_flag == 1){
                $save_res = json_decode($hotelC_ob->addHotel($merchant_id, $name, $address, $tel, $img),true);
                if ($save_res['status'] == ERROR_NONE) {
                    $url = Yii::app()->createUrl('mCenter/Hotel/HotelInformation');
                    echo "<script>alert('修改成功');window.location.href='$url'</script>";
                }
            }
            $hotel_info = $post;
        }else{
            //获取酒店信息
            $hotel_res = json_decode($hotelC_ob->getHotelInfo($merchant_id),true);
            if ($hotel_res['status'] == ERROR_NONE){
                $hotel_info = $hotel_res['data'];
                if (!empty($hotel_info['img'])){
                    $room_imgs = explode(',', $hotel_info['img']);
                }
            }
        }
        $this->render('hotelInformation',array(
            'hotel_info' => $hotel_info,
            'room_imgs' => $room_imgs,
        ));
    }
    
    public function getHotelC(){
        return HotelC::getInstance();
    }
    public function getStoreC(){
        return StoreC::getInstance();
    }
}

