<?php
/**
 * 统计控制器
 */
class FlowController extends mCenterController
{
    public function init() {}
    /**
     * 统计昨天数据
     */
    public function actionData()
    {
        set_time_limit(0);
        $flag = 0;
        if(empty($_GET['Yesterday']))
        {
            $flag = 1;
        }
        if($flag == 0)
        {
            if(isset($_GET['Yesterday']) && $_GET['Yesterday'])
            {
                if($_GET['Yesterday'] == 'qwertyuiop123456789')
                {
                    $ret   = new StatisticsC();        
                    $today = date('Y-m-d', strtotime('-1 day'));
                    $data  = $ret->Flow($today);
                    $statistics = json_decode($data,true);
                    if($statistics['status'] == ERROR_NONE)
                    {
                        echo $statistics['errMsg'];
                    } else {
                        echo $statistics['errMsg'];
                    }
                }
            }
        }
    }
    
    /**
     * 统计日汇总
     */
    public function actionSummaryDay()
    {
    	set_time_limit(0);
    	if (!isset($_GET['Yesterday']) || $_GET['Yesterday'] != 'Qwerkywriter1800') {
    		exit();
    	}
    	$statistics = new StatisticsC();
		$res = json_decode ( $statistics->summaryDay (), true );
		if ($res ['status'] == ERROR_NONE) {
			echo $res ['errMsg'];
		} else {
			echo $res ['errMsg'];
		}
    }
    
    /**
     * 统计月汇总
     */
    public function actionSummaryMonth()
    {
    	if (!isset($_GET['Yesterday']) || $_GET['Yesterday'] != 'Qwerkywriter1800') {
    		exit();
    	}
    	$statistics = new StatisticsC();
    	$res = json_decode ( $statistics->summaryMonth (), true );
    	if ($res ['status'] == ERROR_NONE) {
    		echo $res ['status'];
    		echo $res ['errMsg'];
    	} else {
    		echo $res ['errMsg'];
    	}
    }
    
    /**
     * 统计所有日汇总（只运行一次）
     */
    public function actionSummaryAllDay()
    {
    	set_time_limit(0);
    	$statistics = new StatisticsC();
    	$res = json_decode ( $statistics->summaryAllDay (), true );
    	if ($res ['status'] == ERROR_NONE) {
    		echo $res ['errMsg'];
    	} else {
    		echo $res ['errMsg'];
    	}
    }
    
    /**
     * 统计所有月汇总（只运行一次）
     */
    public function actionSummaryAllMonth()
    {
    	set_time_limit(0);
    	$statistics = new StatisticsC();
    	$res = json_decode ( $statistics->summaryAllMonth (), true );
    	if ($res ['status'] == ERROR_NONE) {
    		echo $res ['errMsg'];
    	} else {
    		echo $res ['errMsg'];
    	}
    }
    
    /**
     * 新会员赠券--给加入30天内并消费一次的会员发券
     */
    public function actionSendCouponsNewUser()
    {
    	set_time_limit(0);
    	$statistics = new StatisticsC();
    	$res = json_decode ( $statistics->sendCouponsNewUser(), true );
    	if ($res ['status'] == ERROR_NONE) {
    		echo $res ['errMsg'];
    	} else {
    		echo $res ['errMsg'];
    	}
    }
    
    /**
     * 加入未消费会员赠券--给已经是加入但没有消费过的会员赠券
     */
    public function actionSendCouponsNotConsume()
    {
    	set_time_limit(0);
    	$statistics = new StatisticsC();
    	$res = json_decode ( $statistics->sendCouponsNotConsume(), true );
    	if ($res ['status'] == ERROR_NONE) {
    		echo $res ['errMsg'];
    	} else {
    		echo $res ['errMsg'];
    	}
    }
    
    /**
     * 生日赠券--建立会员生日送券活动
     */
    public function actionSendCouponsBirthday()
    {
    	set_time_limit(0);
    	$statistics = new StatisticsC();
    	$res = json_decode ( $statistics->sendCouponsBirthday(), true );
    	if ($res ['status'] == ERROR_NONE) {
    		echo $res ['errMsg'];
    	} else {
    		echo $res ['errMsg'];
    	}
    }
    
    /**
     * 给老会员赠券--给注册会员一个月以上，且60天内消费过一次的会员赠券
     */
    public function actionSendCouponsOld()
    {
    	set_time_limit(0);
    	$statistics = new StatisticsC();
    	$res = json_decode ( $statistics->sendCouponsOld(), true );
    	if ($res ['status'] == ERROR_NONE) {
    		echo $res ['errMsg'];
    	} else {
    		echo $res ['errMsg'];
    	}
    }
    
    /**
     * 会员赠券--给会员发券
     */
    public function actionSendCouponsUser()
    {
    	set_time_limit(0);
    	$statistics = new StatisticsC();
    	$res = json_decode ( $statistics->sendCouponsUser(), true );
    	if ($res ['status'] == ERROR_NONE) {
    		echo $res ['errMsg'];
    	} else {
    		echo $res ['errMsg'];
    	}
    }
    
    /*
     * 合并李仲下面的重复商户
     */
//     public function actionMergeLeeZMerchant(){
//     	$merchantC = new MerchantC();
//     	$re = json_decode($merchantC -> mergeMerchant());
//     	if($re -> status == ERROR_NONE){
//     		echo $re -> data;
//     		print_r($re -> merchant_name);
//     		echo count($re -> merchant_name);
//     	}else{
//     		echo '失败：'.$re -> errMsg;
//     	}
//     }
    
    
}

