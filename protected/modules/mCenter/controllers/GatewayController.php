<?php

class GatewayController extends mCenterController
{

    public function init(){

    }

    /*
     * ation:验证开发者网关
     * author：li-junyu
     * date:2015-3-10
     */
    public function actionIndex()
    {
		Yii::import("application.extensions.alifuwu.*");
        require_once('AopConfig.php');
        require_once('function.inc.php');
        require_once('HttpRequst.php');
        require_once('AlipaySign.php');
        require_once('Gateway.php');

        Yii::import('application.modules.mCenter.controllers.MessageController');

        if (get_magic_quotes_gpc()) {
            foreach ($_POST as $key => $value) {
                $_POST [$key] = stripslashes($value);
            }
            foreach ($_GET as $key => $value) {
                $_GET [$key] = stripslashes($value);
            }
            foreach ($_REQUEST as $key => $value) {
                $_REQUEST [$key] = stripslashes($value);
            }
        }

        // 日志记录下受到的请求
        writeLog("POST: " . var_export($_POST, true));
        writeLog("GET: " . var_export($_GET, true));
        writeLog("REQUEST: " . var_export($_REQUEST, true));

        $sign = HttpRequest::getRequest("sign");
        $sign_type = HttpRequest::getRequest("sign_type");
        $biz_content = HttpRequest::getRequest("biz_content");
        $service = HttpRequest::getRequest("service");
        $charset = HttpRequest::getRequest("charset");

        if (empty ($sign) || empty ($sign_type) || empty ($biz_content) || empty ($service) || empty ($charset)) {
            echo "some parameter is empty.";
            writeLog("some parameter is empty.");
            exit ();
        }
        // 收到请求，先验证签名
        $as = new AlipaySign ();
        $sign_verify = $as->rsaCheckV2($_REQUEST, $AopConfig ['alipay_public_key_file']);

        if (!$sign_verify) {
            // 如果验证网关时，请求参数签名失败，则按照标准格式返回，方便在服务窗后台查看。
            if (HttpRequest::getRequest("service") == "alipay.service.check") {
                $gw = new Gateway ();
                $gw->verifygw(false);
            } else {
                echo "sign verfiy fail.";
                writeLog("sign verfiy fail.");
            }
            exit ();
        }

        // 验证网关请求
        if (HttpRequest::getRequest("service") == "alipay.service.check") {
            // Gateway::verifygw();
            $gw = new Gateway ();
            $gw->verifygw(true);
        } else if (HttpRequest::getRequest("service") == "alipay.mobile.public.message.notify") {
        	
            //获取用户账号
            $encrypt_account = $_GET['account'];
            writeLog("=====================");
            // 处理收到的消息
            $msg = new MessageController('Message');
            
            $msg->Message($encrypt_account, $biz_content);

            //MessageController::Message( $encrypt_account, $biz_content );
        }
    }
    
    /**
     * 支付宝授权回跳
     */
    public function actionAlipayAuth() {
    	$app_id = isset($_GET['app_id']) ? $_GET['app_id'] : '';
    	$code = isset($_GET['app_auth_code']) ? $_GET['app_auth_code'] : '';
    	$extend = isset($_GET['extends']) ? $_GET['extends'] : '';
    	
    	$merchant = new MerchantC();
    	$result = $merchant->alipayAppAuth($extend, $code);
    }
    
    /**
     * 口碑门店审核回跳
     */
    public function actionAlipayAudit() {
    	Yii::log('门店审核通知：'.json_encode($_POST), 'warning');
    	$apply_id = $_POST['apply_id']; //请求流水号
    	$biz_type = $_POST['biz_type']; //事件类型
    	$audit_status = $_POST['audit_status']; //审核状态
    	$shop_id = isset($_POST['shop_id']) ? $_POST['shop_id'] : ''; //门店id
    	$audit_desc = isset($_POST['result_desc']) ? $_POST['result_desc'] : $_POST['audit_desc']; //审核描述
    	
    	$req_id = $_POST['req_id']; //自定义参数，为门店编号
    	
    	$store = new StoreC();
    	$ret = $store->updateAuditStatus($req_id, $apply_id, $shop_id, $audit_status, $audit_desc);
    	if ($ret['status'] == ERROR_NONE) {
    		echo 'success';
    	}elseif ($ret['status'] == ERROR_NO_DATA) {
    		echo 'success'; //未找到门店数据则不再需要通知重试
    	}
    }
    
    /**
     * ISV门店被动通知
     */
    public function actionStoreNotify() {
    	Yii::log('ISV门店被动通知：'.json_encode($_POST), 'warning');
    	$partner_id = $_POST['partner_id']; //店铺所属商户的PID
    	$notify_pid = $_POST['notify_pid']; //店铺所属系统集成商的PID
    	$shop_id = $_POST['shop_id']; //支付宝门店ID
    	$status = $_POST['status']; //门店状态：PAUSED（暂停）OPEN（开店）CLOSED（关店）FREEZE（冻结）
    	$sign = $_POST['sign']; //签名
    	$notify_type = $_POST['notify_type']; //通知类型：shop_update（门店变更通知）
    	$update_time = $_POST['update_time']; //店铺变动时间
    	//ON_LINE_SHOP：上架OFF_LINE_SHOP：下架MODIFY_SHOP：修改FREEZE_SHOP：冻结UNFREEZE_SHOP：解冻HIDE_SHOP：隐藏SHOW_SHOP：展示
    	$action = $_POST['action'];
    	$notify_id = $_POST['notify_id']; //通知ID
    	
    	if ($action == 'MODIFY_SHOP') {
    		$storeC = new StoreC();
    		$ret = $storeC->updateStoreByNotify($shop_id, $notify_pid, $notify_id);
    		if ($ret['status'] == ERROR_NONE) {
    			echo 'success';
    		}
    	}
    }
    
    
}




