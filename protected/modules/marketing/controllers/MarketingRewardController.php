<?php
/**
 * Created by PhpStorm.
 * User: cheng
 * Date: 2016/7/19
 * Time: 9:24
 * 激励消费
 */
class MarketingRewardController extends marketingSController{

    //单次消费奖励
    public function actionSinglePay()
    {
        $merchantC = new MobileMerchantC();
        $marketingC = new MarketingClass();

        //查询商户是否配置了支付宝参数
        $merchant_id = Yii::app()->session['merchant_id'];
        $merchant = $merchantC->getMerchantInfo($merchant_id);
        if ($merchant['alipay_auth_appid'] != '' || $merchant['appid'] != '') {
            //支付宝参数已配置
            $alipay_flag = true;
        } else {
            //支付宝参数未配置
            $alipay_flag = false;
        }

        if (isset($_GET['marketing_id']) && !empty($_GET['marketing_id'])) { //编辑
            $marketing_id = $_GET['marketing_id'];
            $marketing_info = json_decode($marketingC->getMarketingInfoById($marketing_id),true);
            $edit_flag = true; //编辑模式
        } else { //添加
            $edit_flag = false; //添加模式
        }

        $this->render('singlePay', array(
            'edit_flag' => $edit_flag,
            'alipay_flag' => $alipay_flag,
            'marketing_info' => $marketing_info
        ));
    }

    //累计消费奖励
    public function actionTotalPay()
    {
        $merchantC = new MobileMerchantC();
        $marketingC = new MarketingClass();

        //查询商户是否配置了支付宝参数
        $merchant_id = Yii::app()->session['merchant_id'];
        $merchant = $merchantC->getMerchantInfo($merchant_id);
        if ($merchant['alipay_auth_appid'] != '' || $merchant['appid'] != '') {
            //支付宝参数已配置
            $alipay_flag = true;
        } else {
            //支付宝参数未配置
            $alipay_flag = false;
        }

        if (isset($_GET['marketing_id']) && !empty($_GET['marketing_id'])) { //编辑
            $marketing_id = $_GET['marketing_id'];
            $marketing_info = json_decode($marketingC->getMarketingInfoById($marketing_id),true);
            $edit_flag = true; //编辑模式
        } else { //添加
            $edit_flag = false; //添加模式
        }

        $this->render('totalPay', array(
            'edit_flag' => $edit_flag,
            'alipay_flag' => $alipay_flag,
            'marketing_info' => $marketing_info
        ));
    }

    //消费次数奖励
    public function actionMultiplePay()
    {
        $merchantC = new MobileMerchantC();
        $marketingC = new MarketingClass();

        //查询商户是否配置了支付宝参数
        $merchant_id = Yii::app()->session['merchant_id'];
        $merchant = $merchantC->getMerchantInfo($merchant_id);
        if ($merchant['alipay_auth_appid'] != '' || $merchant['appid'] != '') {
            //支付宝参数已配置
            $alipay_flag = true;
        } else {
            //支付宝参数未配置
            $alipay_flag = false;
        }

        if (isset($_GET['marketing_id']) && !empty($_GET['marketing_id'])) { //编辑
            $marketing_id = $_GET['marketing_id'];
            $marketing_info = json_decode($marketingC->getMarketingInfoById($marketing_id),true);
            $edit_flag = true; //编辑模式
        } else { //添加
            $edit_flag = false; //添加模式
        }

        $this->render('multiplePay', array(
            'edit_flag' => $edit_flag,
            'alipay_flag' => $alipay_flag,
            'marketing_info' => $marketing_info
        ));
    }

    //消费激励赠券，活动添加
    public function actionMarketingRewardAdd(){
        $marketingClass = new MarketingClass();
        $couponClass = new CouponClass();
        if (isset($_POST['Marketing_id']) && !empty($_POST['Marketing_id'])) { //编辑
            $marketing_id = $_POST['Marketing_id'];
            $edit_flag = true; //编辑模式
        } else { //添加
            $edit_flag = false; //添加模式
        }

        //查询商户是否配置了支付宝参数
        $merchant_id = Yii::app()->session['merchant_id'];
        $merchantC = new MobileMerchantC();
        $merchant = $merchantC->getMerchantInfo($merchant_id);

        if ($merchant['alipay_auth_appid'] != '' || $merchant['appid'] != '') {
            //支付宝参数已配置
            $alipay_flag = true;
        } else {
            //支付宝参数未配置
            $alipay_flag = false;
        }
        if ($_POST) {
            $post = $_POST['Marketing'];
            $marketing_type = $post['type'];
            $marketing_name = $post['name'];
            $marketing_coupon = $post['coupon'];
            $marketing_send_channel = $post['send_channel'];
            $marketing_cover_img = $post['cover_img'];
            $marketing_img_text = $post['img_text'];
            $marketing_time_type = $post['time_type'];
            $marketing_reward_condition = $post['reward_condition'];
            $marketing_time_send_type = $post['time_send_type'];
            $custom_period = $post['custom_period'];
            if (empty($marketing_name)) {
                echo "<script>alert('请输入活动名称');history.go(-1);</script>";
                exit();
            }

            if (empty($marketing_coupon)) {
                echo "<script>alert('请选择活动内容');history.go(-1);</script>";
                exit();
            }

            if (empty($marketing_reward_condition)) {
                echo "<script>alert('请输入赠券条件');history.go(-1);</script>";
                exit();
            }

            if (empty($marketing_send_channel)) {
                echo "<script>alert('请选择赠券渠道');history.go(-1);</script>";
                exit();
            }

            if (in_array(MARKETING_ACTIVITY_SEND_CHANNEL_ALIPAY, $marketing_send_channel)) { //勾选了支付宝
                if (empty($marketing_cover_img)) {
                    echo "<script>alert('请上传封面图片');history.go(-1);</script>";
                    exit();
                }

                if (empty($marketing_img_text)) {
                    echo "<script>alert('请填写图文简介');history.go(-1);</script>";
                    exit();
                }
            }

            //获取优惠券信息
            $coupon_info = $couponClass->getCouponInfo($marketing_coupon);
            if ($coupon_info['status'] != WX_CHECK_PASS) { //优惠券在微信没有通过审核，则要判断活动说明是否填写
                if (empty($marketing_img_text)) {
                    echo "<script>alert('请完善优惠券图文简介');history.go(-1);</script>";
                    exit();
                }
            }
            $data['name'] = $marketing_name;
            $data['marketing_type'] = $marketing_type;

            $data['image_text_imageurl'] = $marketing_cover_img;
            $data['image_text_title'] = $marketing_img_text;
            $data['coupon_id'] = $marketing_coupon;
            if($marketing_type == MARKETING_ACTIVITY_TYPE_BASICS_DCXFMMA){
                $reward_condition = array(
                    'single_pay' => $marketing_reward_condition
                );
            }elseif($marketing_type == MARKETING_ACTIVITY_TYPE_BASICS_LJXFMMA){
                $reward_condition = array(
                    'total_pay' => $marketing_reward_condition
                );
            }elseif($marketing_type == MARKETING_ACTIVITY_TYPE_BASICS_XFCSMMA){
                $reward_condition = array(
                    'multiple_pay' => $marketing_reward_condition
                );
            }
            $marketing_condition = json_encode($reward_condition,true);
            $data['condition'] = $marketing_condition;

            if (in_array(MARKETING_ACTIVITY_SEND_CHANNEL_WECHAT, $marketing_send_channel) && !in_array(MARKETING_ACTIVITY_SEND_CHANNEL_ALIPAY, $marketing_send_channel)) { //仅微信渠道
                $data['send_channel'] = MARKETING_ACTIVITY_SEND_CHANNEL_WECHAT;
                if ($coupon_info['status'] == WX_CHECK_PASS) {
                    $data['image_text_imageurl'] = '';
                    $data['image_text_title'] = '';
                }
            } elseif (!in_array(MARKETING_ACTIVITY_SEND_CHANNEL_WECHAT, $marketing_send_channel) && in_array(MARKETING_ACTIVITY_SEND_CHANNEL_ALIPAY, $marketing_send_channel)) { //仅支付宝渠道
                $data['send_channel'] = MARKETING_ACTIVITY_SEND_CHANNEL_ALIPAY;
            } elseif (in_array(MARKETING_ACTIVITY_SEND_CHANNEL_WECHAT, $marketing_send_channel) && in_array(MARKETING_ACTIVITY_SEND_CHANNEL_ALIPAY, $marketing_send_channel)) { //全部渠道
                $data['send_channel'] = MARKETING_ACTIVITY_SEND_CHANNEL_BOTH;
            }

           if ($marketing_time_type == MARKETING_ACTIVITY_TIME_TYPE_PERIODICITY) { //周期活动
                $data['time_type'] = MARKETING_ACTIVITY_TIME_TYPE_PERIODICITY;
                if ($marketing_time_send_type == MARKETING_ACTIVITY_SEND_TYPE_CRONTAB) { //定义周期
                    $times = explode(' - ', $custom_period);
                    $start_time = $times[0];
                    $end_time = $times[1];
                    if ($start_time > date('Y-m-d', time())) { //开始时间大于当前时间
                        $data['status'] = MARKETING_ACTIVITY_STATUS_NOSTART;
                    } else {
                        $data['status'] = MARKETING_ACTIVITY_STATUS_START;
                    }

                    $data['time_send_type'] = MARKETING_ACTIVITY_SEND_TYPE_CRONTAB;
                    $data['start_time'] = date('Y-m-d 00:00:00', strtotime($start_time));
                    $data['end_time'] = date('Y-m-d 23:59:59', strtotime($end_time));
                } elseif ($marketing_time_send_type == MARKETING_ACTIVITY_SEND_TYPE_LONG) {
                    $data['time_send_type'] = MARKETING_ACTIVITY_SEND_TYPE_LONG;
                    $data['start_time'] = date('Y-m-d H:i:s', time());
                    $data['end_time'] = null;
                    $data['status'] = MARKETING_ACTIVITY_STATUS_START;
                }
            }
            if (!$edit_flag) { //添加营销活动
                $res_add = json_decode($marketingClass->addMarketingActivityElse($merchant_id, $data), true);
            } else { //编辑营销活动
                $res_add = json_decode($marketingClass->editMarketingActivityElse($marketing_id, $data), true);
            }

            if ($res_add['status'] == ERROR_NONE && $edit_flag) { //编辑
                echo "<script>parent.window.delayHide('m-succeed-icon', '活动编辑成功', 1500);window.location.href='" . Yii::app()->createUrl('marketing/marketing/MarketingList') . "'</script>";
            } elseif ($res_add['status'] == ERROR_NONE && !$edit_flag) { //创建
                echo "<script>parent.window.delayHide('m-succeed-icon', '活动创建成功', 1500);window.location.href='" . Yii::app()->createUrl('marketing/marketing/MarketingList') . "'</script>";
            }
        }
    }
}