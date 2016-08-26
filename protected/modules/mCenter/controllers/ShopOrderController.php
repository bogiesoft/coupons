<?php

/**
 * 商城订单控制器
 * @author xyf
 *
 */
class ShopOrderController extends mCenterController
{

    /**
     * 商城订单列表
     */
    public function actionOrderList()
    {
        $list = array();
        $orderMall = new OrderMall();


        //搜索
        $order_no = '';
        if (isset($_GET['Order']['order_no']) && $_GET['Order']['order_no']) {
            $order_no = $_GET['Order']['order_no'];
        }

        $order_type = '';
        if (isset($_GET['Order']['order_type']) && $_GET['Order']['order_type']) {
            $order_type = $_GET['Order']['order_type'];
        }

        $user_name = '';
        if (isset($_GET['Order']['user_name']) && $_GET['Order']['user_name']) {
            $user_name = $_GET['Order']['user_name'];
        }

        $user_phone = '';
        if (isset($_GET['Order']['user_phone']) && $_GET['Order']['user_phone']) {
            $user_phone = $_GET['Order']['user_phone'];
        }

        $time = '';
        if (isset($_GET['Time']) && $_GET['Time']) {
            $time = $_GET['Time'];
        }

        //订单状态
        /*$order_status = $GLOBALS['ORDER_STATUS'];
        //隐藏部分
        unset($order_status[ORDER_STATUS_WAITFORCREATE]);
        unset($order_status[ORDER_STATUS_REVOKE]);
        unset($order_status[ORDER_STATUS_CREATE]);
        unset($order_status[ORDER_STATUS_NORMAL]);
        unset($order_status[ORDER_STATUS_USED]);
        unset($order_status[ORDER_STATUS_HANDLE_REFUND]);
        unset($order_status[ORDER_STATUS_REFUND]);
        unset($order_status[ORDER_STATUS_PART_REFUND]);
        unset($order_status[ORDER_STATUS_PART_COMPLETE]);*/
        $order_status = '';
        if (isset($_GET['order_status']) && $_GET['order_status']) {
            $order_status = $_GET['order_status'];
        }

        $pay_channel = '';
        if (isset($_GET['Order']['pay_channel']) && $_GET['Order']['pay_channel']) {
            $pay_channel = $_GET['Order']['pay_channel'];
        }

        if (!empty($_GET['Order'])) { //如果第一个form表单搜索有值   清空$_GET  防止点击筛选按钮全部按钮样式有误
            unset($_GET['pay_status']);
        }

        //订单付款状态
        $pay_status = '';
        if (empty($order_status)) {
            if (isset($_GET['pay_status']) && $_GET['pay_status']) {
                $pay_status = $_GET['pay_status'];
            }
        }
        $merchant_id = Yii::app()->session['merchant_id'];
        $noPayCount = $orderMall->getNoPayCount($merchant_id); //获取待付款的数量;
        $waitCount = $orderMall->getWaitCount($merchant_id); //获取订单待发货的数量
        $deliverCount = $orderMall->getDeliverCount($merchant_id); //获取已发货的数量
        $acceptCount = $orderMall->getAcceptCount($merchant_id); //获取已收货的数量
        $cancelCount = $orderMall->getCancelCount($merchant_id); //获取已取消的数量
        $result = json_decode($orderMall->getShopOrderList($order_no, $order_type, $user_name, $user_phone, $time, $order_status, $pay_channel, $pay_status, $merchant_id), true);
        if ($result['status'] == ERROR_NONE) {
            $list = $result['data']['list'];
        }
        $this->render('orderList', array(
            'list' => $list,
            'order_status' => $GLOBALS['ORDER_STATUS'],
            'pages' => $orderMall->page,
            'noPayCount' => $noPayCount,
            'waitCount' => $waitCount,
            'deliverCount' => $deliverCount,
            'acceptCount' => $acceptCount,
            'cancelCount' => $cancelCount
        ));
    }


    /**
     * 商城订单详情
     */
    public function actionOrderDetail($order_id)
    {
        $list = array();
        $address_arr = array();
        $selector = new Selector(); //三级地区
        $orderMall = new OrderMall();
        $result = json_decode($orderMall->getShopOrderDetail($order_id), true);
        if ($result['status'] == ERROR_NONE) {
            $list = $result['data'];
            if (!empty($result['data']['address'])) {
                $address_arr = explode(',', $result['data']['address']); //把订单地址拆分放入数组里
                //获取订单地址的省市区
                $selector->liveplaceProvince = $address_arr[0];
                $selector->liveplaceCity = $address_arr[1];
                $selector->liveplaceArea = $address_arr[2];
            }
        }

        $goUrl = '';
        if (isset($_GET['goUrl']) && $_GET['goUrl']) {
            $goUrl = $_GET['goUrl'];
        }
        $refund_stutus = false;
        for ($i = 0; $i < count($list); $i++) {
            if ($list['order_sku'][$i]['status'] != ORDER_SKU_STATUS_NORMAL) {
                //有商品不处于正常状态不能发货
                $refund_stutus = true;
            }
        }
        $this->render('orderDetail', array(
            'list' => $list,
            'selector' => $selector,
            'address_arr' => $address_arr,
            'goUrl' => $goUrl,
            'refund_status' => $refund_stutus
        ));
    }

    /**
     * 订单发货
     */
    public function actionDelivery($order_id)
    {
        $orderMall = new OrderMall();
        $result = json_decode($orderMall->orderDelivery($order_id), true);
        if ($result['status'] == ERROR_NONE) {
            $this->redirect(array('orderList'));
        }
    }

    /**
     * 订单取消
     */
    public function actionCancel($order_id)
    {
        $orderMall = new OrderMall();
        $result = json_decode($orderMall->orderCancel($order_id), true);
        if ($result['status'] == ERROR_NONE) {
            $this->redirect(array('orderList'));
        }
    }

    /**
     * 修改收货地址
     */
    public function actionEditAddress($order_id)
    {
        $liveplaceProvince = ''; //省份
        $liveplaceCity = ''; //城市
        $liveplaceArea = ''; //区域
        $postCode = ''; //邮政编码
        $streetAddress = ''; //街道地址
        $userName = ''; //收件人姓名
        $userPhone = ''; //手机号码

        $flag = false;

        $orderMall = new OrderMall();

        if (isset($_GET['Selector']['liveplaceProvince']) && $_GET['Selector']['liveplaceProvince']) {
            $liveplaceProvince = $_GET['Selector']['liveplaceProvince'];
        }

        if (isset($_GET['Selector']['liveplaceCity']) && $_GET['Selector']['liveplaceCity']) {
            $liveplaceCity = $_GET['Selector']['liveplaceCity'];
        }

        if (isset($_GET['Selector']['liveplaceArea']) && $_GET['Selector']['liveplaceArea']) {
            $liveplaceArea = $_GET['Selector']['liveplaceArea'];
        }

        if (isset($_GET['postCode']) && $_GET['postCode']) {
            $postCode = $_GET['postCode'];
        }

        if (isset($_GET['streetAddress']) && $_GET['streetAddress']) {
            $streetAddress = $_GET['streetAddress'];
        }

        if (isset($_GET['userName']) && $_GET['userName']) {
            $userName = $_GET['userName'];
        }

        if (isset($_GET['userPhone']) && $_GET['userPhone']) {
            $userPhone = $_GET['userPhone'];
        }

        if (!$flag) {
            $result = $orderMall->editAddress($order_id, $liveplaceProvince, $liveplaceCity, $liveplaceArea,
                $postCode, $streetAddress, $userName, $userPhone);
            $result = json_decode($result, true);
            if ($result['status'] == ERROR_NONE) {
                $this->redirect(Yii::app()->createUrl('mCenter/shopOrder/orderDetail', array('order_id' => $order_id)));
            }
        }
    }


    /**
     * 修改卖家备注
     */
    public function actionEditSellerRemark()
    {
        $orderMall = new OrderMall();

        $order_id = '';
        if (isset($_GET['order_id']) && $_GET['order_id']) {
            $order_id = $_GET['order_id'];
        }

        $seller_remark = '';
        if (isset($_GET['seller_remark']) && $_GET['seller_remark']) {
            $seller_remark = $_GET['seller_remark'];
        }

        $type = '';
        if (isset($_GET['type']) && $_GET['type']) {
            $type = $_GET['type'];
        }

        $result = $orderMall->editSellerRemark($order_id, $seller_remark);
        $result = json_decode($result, true);
        if ($result['status'] == ERROR_NONE) {
            if ($type == 'detail') { //如果是在详情界面修改   则跳到详情界面
                $this->redirect(Yii::app()->createUrl('mCenter/shopOrder/orderDetail', array('order_id' => $order_id)));
            } elseif ($type == 'list') { //如果是在订单列表界面修改   则跳到列表界面
                $this->redirect(Yii::app()->createUrl('mCenter/shopOrder/orderList'));
            } elseif ($type == 'activistList') { //如果是在订单维权列表界面修改   则跳到订单维权列表界面
                $this->redirect(Yii::app()->createUrl('mCenter/shopActivist/activistList'));
            }
        }
    }


    /**
     * 卖家发货
     */
    public function actionSend()
    {
        $orderMall = new OrderMall();

        $order_id = '';
        if (isset($_GET['order_id']) && $_GET['order_id']) {
            $order_id = $_GET['order_id'];
        }

        $order_sku = array();
        if (isset($_GET['order_sku']) && $_GET['order_sku']) {
            $order_sku = $_GET['order_sku'];
        }

        $express_name = '';
        if (isset($_GET['express_name']) && $_GET['express_name']) {
            $express_name = $_GET['express_name'];
        }

        $express_no = '';
        if (isset($_GET['express_no']) && $_GET['express_no']) {
            $express_no = $_GET['express_no'];
        }

        $type = '';
        if (isset($_GET['type']) && $_GET['type']) {
            $type = $_GET['type'];
        }

        $result = $orderMall->orderSend($order_id, $order_sku, $express_name, $express_no);
        $result = json_decode($result, true);
        if ($result['status'] == ERROR_NONE) {
            if ($type == 'detail') { //如果是在详情界面修改   则跳到详情界面
                $this->redirect(Yii::app()->createUrl('mCenter/shopOrder/orderDetail', array('order_id' => $order_id)));
            } elseif ($type == 'list') { //如果是在列表界面修改   则跳到列表界面
                $this->redirect(Yii::app()->createUrl('mCenter/shopOrder/orderList'));
            }
        } else {
            echo $result['errMsg'];
        }
    }

    /**
     * ajax获取数据
     */
    public function actionGetAjaxData()
    {
        $orderMall = new OrderMall();
        $order_id = '';
        $result = array();
        if (isset($_POST['order_id']) && $_POST['order_id']) {
            $order_id = $_POST['order_id'];
        }
        $result = $orderMall->getAjaxData($order_id);
        echo json_encode($result);
    }

    /**
     * 发货:  ajax获取数据
     */
    public function actionGetAjaxDataForSend()
    {
        $orderMall = new OrderMall();
        $order_id = '';
        $result = array();
        if (isset($_POST['order_id']) && $_POST['order_id']) {
            $order_id = $_POST['order_id'];
        }
        $result = $orderMall->getAjaxDataForSend($order_id);
        echo json_encode($result);
    }

    /**
     * 导出excel
     */
    public function actionExportExcel()
    {
        $orderMall = new OrderMall();
        $merchant_id = Yii::app()->session['merchant_id'];
        //搜索
        $order_no = '';
        if (isset($_GET['order_no']) && $_GET['order_no']) {
            $order_no = $_GET['order_no'];
        }

        $order_type = '';
        if (isset($_GET['order_type']) && $_GET['order_type']) {
            $order_type = $_GET['order_type'];
        }

        $user_name = '';
        if (isset($_GET['user_name']) && $_GET['user_name']) {
            $user_name = $_GET['user_name'];
        }

        $user_phone = '';
        if (isset($_GET['user_phone']) && $_GET['user_phone']) {
            $user_phone = $_GET['user_phone'];
        }

        $time = '';
        if (isset($_GET['Time']) && $_GET['Time']) {
            $time = $_GET['Time'];
        }

        //订单状态
        $order_status = '';
        if (isset($_GET['order_status']) && $_GET['order_status']) {
            $order_status = $_GET['order_status'];
        }

        $pay_channel = '';
        if (isset($_GET['pay_channel']) && $_GET['pay_channel']) {
            $pay_channel = $_GET['pay_channel'];
        }

// 		if(!empty($_GET['Order'])){ //如果第一个form表单搜索有值   清空$_GET  防止点击筛选按钮全部按钮样式有误
// 			unset($_GET['pay_status']);
// 		}

        //订单付款状态
        $pay_status = '';
        if (empty($order_status)) {
            if (isset($_GET['pay_status']) && $_GET['pay_status']) {
                $pay_status = $_GET['pay_status'];
            }
        }

        $orderMall->exportExcel($merchant_id, $order_no, $order_type, $user_name, $user_phone,
            $time, $order_status, $pay_channel, $pay_status);
    }


}