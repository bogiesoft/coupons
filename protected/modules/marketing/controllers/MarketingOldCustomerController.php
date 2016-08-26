<?php
/**
 * Created by PhpStorm.
 * User: cheng
 * Date: 2016/7/19
 * Time: 9:21
 * 稳固老客户
 */

class MarketingOldCustomerController extends marketingSController
{
    //流失客户，活动新建、编辑页面                         2-1
    public function actionLossingCustomer(){
        $merchantC = new MobileMerchantC();
        $marketingC = new MarketingClass();
        $type = MARKETING_ACTIVITY_TYPE_BASICS_LSKHMA;

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

        $user_info = json_decode($marketingC->getUserInfo($type,$merchant_id),true);
        $refresh_time = time();
        $this->render('lossingCustomer', array(
            'edit_flag' => $edit_flag,
            'alipay_flag' => $alipay_flag,
            'user_info'=>$user_info,
            'refresh_time'=>$refresh_time,
            'marketing_info' => $marketing_info
        ));
    }

    //老会员，活动新建、编辑页面                          2-2
    public function actionOldMember()
    {
        $merchantC = new MobileMerchantC();
        $marketingC = new MarketingClass();
        $type = MARKETING_ACTIVITY_TYPE_BASICS_LHYMA;

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

        $user_info = json_decode($marketingC->getUserInfo($type,$merchant_id),true);
        $refresh_time = time();
        $this->render('oldMember', array(
            'edit_flag' => $edit_flag,
            'alipay_flag' => $alipay_flag,
            'user_info'=>$user_info,
            'refresh_time'=>$refresh_time,
            'marketing_info' => $marketing_info
        ));
    }

    //活跃会员，活动新建、编辑页面                          2-3
    public function actionActiveMember()
    {
        $merchantC = new MobileMerchantC();
        $marketingC = new MarketingClass();
        $type = MARKETING_ACTIVITY_TYPE_BASICS_LHYMA;

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

        $user_info = json_decode($marketingC->getUserInfo($type,$merchant_id),true);
        $refresh_time = time();
        $this->render('activeMember', array(
            'edit_flag' => $edit_flag,
            'alipay_flag' => $alipay_flag,
            'user_info'=>$user_info,
            'refresh_time'=>$refresh_time,
            'marketing_info' => $marketing_info
        ));
    }

    /**
     * 发起营销(老客户客户赠券)
     */
    public function actionMarketingOldCustomerAdd()
    {
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
            $marketing_send_one_time = $post['send_one_time'];
            $marketing_time_send_type = $post['time_send_type'];

            if (empty($marketing_name)) {
                echo "<script>alert('请输入活动名称');history.go(-1);</script>";
                exit();
            }

            if (empty($marketing_coupon)) {
                echo "<script>alert('请选择活动内容');history.go(-1);</script>";
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
                    echo "<script>alert('请填写图文简介');history.go(-1);</script>";
                    exit();
                }
            }

            $data['name'] = $marketing_name;
            $data['marketing_type'] = $marketing_type;
            $data['image_text_imageurl'] = $marketing_cover_img;
            $data['image_text_title'] = $marketing_img_text;
            $data['coupon_id'] = $marketing_coupon;

            if (in_array(MARKETING_ACTIVITY_SEND_CHANNEL_WECHAT, $marketing_send_channel) && !in_array(MARKETING_ACTIVITY_SEND_CHANNEL_ALIPAY, $marketing_send_channel)) { //仅微信渠道
                $data['send_channel'] = MARKETING_ACTIVITY_SEND_CHANNEL_WECHAT;
                $data['image_text_imageurl'] = '';
                if ($coupon_info['status'] == WX_CHECK_PASS) {
                    $data['image_text_title'] = '';
                }
            } elseif (!in_array(MARKETING_ACTIVITY_SEND_CHANNEL_WECHAT, $marketing_send_channel) && in_array(MARKETING_ACTIVITY_SEND_CHANNEL_ALIPAY, $marketing_send_channel)) { //仅支付宝渠道
                $data['send_channel'] = MARKETING_ACTIVITY_SEND_CHANNEL_ALIPAY;
            } elseif (in_array(MARKETING_ACTIVITY_SEND_CHANNEL_WECHAT, $marketing_send_channel) && in_array(MARKETING_ACTIVITY_SEND_CHANNEL_ALIPAY, $marketing_send_channel)) { //全部渠道
                $data['send_channel'] = MARKETING_ACTIVITY_SEND_CHANNEL_BOTH;
            }

            if ($marketing_time_type == MARKETING_ACTIVITY_TIME_TYPE_ONETIME) { //一次性活动
                $data['time_type'] = MARKETING_ACTIVITY_TIME_TYPE_ONETIME;
                if ($marketing_time_send_type == MARKETING_ACTIVITY_SEND_TYPE_TIMING) { //定时发送
                    if (date('Y-m-d 16:00:00', strtotime($marketing_send_one_time)) <= date('Y-m-d 16:00:00', time())) { //判断定时时间和计划任务的发送时间
                        $data['status'] = MARKETING_ACTIVITY_STATUS_START;
                    } else {
                        $data['status'] = MARKETING_ACTIVITY_STATUS_NOSTART;
                    }
                    $data['time_send_type'] = MARKETING_ACTIVITY_SEND_TYPE_TIMING;
                    $data['start_time'] = date('Y-m-d 16:00:00', strtotime($marketing_send_one_time));
                } elseif ($marketing_time_send_type == MARKETING_ACTIVITY_SEND_TYPE_IMMEDIATELY) { //即时发送
                    $data['time_send_type'] = MARKETING_ACTIVITY_SEND_TYPE_IMMEDIATELY;
                    $data['start_time'] = date('Y-m-d H:i:s', time());
                    $data['status'] = MARKETING_ACTIVITY_STATUS_START;
                }

                $data['end_time'] = null;
            }
            if (!$edit_flag) { //添加营销活动
            $res_add = json_decode($marketingClass->addMarketingActivityElse($merchant_id, $data), true);
            } else { //编辑营销活动
                $res_add = json_decode($marketingClass->editMarketingActivityElse($marketing_id, $data), true);
            }

            if ($res_add['status'] == ERROR_NONE && $data['time_type'] == MARKETING_ACTIVITY_TIME_TYPE_ONETIME && $data['time_send_type'] == MARKETING_ACTIVITY_SEND_TYPE_IMMEDIATELY) { //当活动为一次性即时活动，立即发送图文消息

                $send_data['merchant_id'] = $merchant_id;
                $send_data['coupon_id'] = $data['coupon_id'];
                $send_data['send_channel'] = $data['send_channel'];
                $send_data['image_text_title'] = $data['image_text_title'];
                $send_data['name'] = $data['name'];
                $send_data['image_text_imageurl'] = $data['image_text_imageurl'];
                $send_data['time_type'] = $data['time_type'];
                $send_data['time_send_type'] = $data['time_send_type'];
                $send_data['marketing_id'] = $res_add['data'];
                $send_data['marketing_type'] = $marketing_type;

                $marketingClass->sendChannelMsgElse($send_data);

                //修改活动状态为已结束
                $marketingClass->setMarketingActivityPrecisionStatus($res_add['data'], MARKETING_ACTIVITY_STATUS_END);
            }

            if ($res_add['status'] == ERROR_NONE && $edit_flag) { //编辑
                echo "<script>parent.window.delayHide('m-succeed-icon', '活动编辑成功', 1500);window.location.href='" . Yii::app()->createUrl('marketing/marketing/MarketingList') . "'</script>";
            } elseif ($res_add['status'] == ERROR_NONE && !$edit_flag) { //创建
                echo "<script>parent.window.delayHide('m-succeed-icon', '活动创建成功', 1500);window.location.href='" . Yii::app()->createUrl('marketing/marketing/MarketingList') . "'</script>";
            }
        }
    }
}