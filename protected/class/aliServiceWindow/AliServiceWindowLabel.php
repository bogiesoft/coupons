<?php

/**
 * Class AliServiceWindowLabel
 * 服务窗标签管理类
 */
class AliServiceWindowLabel extends AliServiceWindowMain{

    /**
     * 创建标签
     */
    public function addLable($name)
    {
        $response = '';
        $label_id = '';
        //包装参数成JSON
        $lable = array(
            "name" => $name,
        );
        $biz_content = json_encode($lable);

        //获取APPID并发送数据至服务窗接口
        $merchant_id = Yii::app()->session ['merchant_id'];
        $merchant = Merchant::model()->findByPk($merchant_id);
        $custom_send = new AlipayMobilePublicLabelAddRequest();
        $custom_send->setBizContent($biz_content);
        if($GLOBALS['AopConfig']['app_id'] != $merchant['appid'] && !empty($merchant['alipay_auth_token'])){
            $response = without_request_execute($custom_send, $merchant['appid'], '', $merchant['alipay_auth_token']);
        } else {
            $response = without_request_execute($custom_send, $merchant['appid']);
        }

        if($response->alipay_mobile_public_label_add_response->code == 200){
            $label_id = $response->alipay_mobile_public_label_add_response->id;
            if(empty($label_id)){
                //如果支付宝返回标签ID为空或者0，那么调用标签查询接口，获取标签ID
                $aliApi = new AliServiceWindowLabel('');
                $response = $aliApi->queryLable();
                $labelList = json_decode($response->alipay_mobile_public_label_query_response->labels, true);
                foreach ($labelList as $v) {
                    if($v['name'] == $name){
                        $label_id = $v['id'];
                    }
                }
            }
        }

        return array('response' => $response, 'label_id' => $label_id);
    }

    /**
     * 删除标签
     */
    public function delLable($id)
    {
        $response = '';
        //包装参数成JSON
        $lable = array(
            "id" => $id,
        );
        $biz_content = json_encode($lable);

        //获取APPID并发送数据至服务窗接口
        $merchant_id = Yii::app()->session ['merchant_id'];
        $merchant = Merchant::model()->findByPk($merchant_id);
        $custom_send = new AlipayMobilePublicLabelDeleteRequest();
        $custom_send->setBizContent($biz_content);
        if($GLOBALS['AopConfig']['app_id'] != $merchant['appid'] && !empty($merchant['alipay_auth_token'])){
            $response = without_request_execute($custom_send, $merchant['appid'], '', $merchant['alipay_auth_token']);
        } else {
            $response = without_request_execute($custom_send, $merchant['appid']);
        }

        return $response;
    }

    /**
     * 查询标签
     */
    public function queryLable($biz_content = null)
    {
        $response = '';
        $merchant_id = Yii::app()->session ['merchant_id'];
        $merchant = Merchant::model()->findByPk($merchant_id);
        $custom_send = new AlipayMobilePublicLabelQueryRequest();
        $custom_send->setBizContent($biz_content);
        if($GLOBALS['AopConfig']['app_id'] != $merchant['appid'] && !empty($merchant['alipay_auth_token'])){
            $response = without_request_execute($custom_send, $merchant['appid'], '', $merchant['alipay_auth_token']);
        } else {
            $response = without_request_execute($custom_send, $merchant['appid']);
        }

        return $response;
    }

    /**
     * 修改标签
     */
    public function editLabel($name, $label_id){
        $response = '';
        //包装参数成JSON
        $lable = array(
            "id" => $label_id,
            "name" => $name
        );
        $biz_content = json_encode($lable);

        //获取APPID并发送数据至服务窗接口
        $merchant_id = Yii::app()->session ['merchant_id'];
        $merchant = Merchant::model()->findByPk($merchant_id);
        $custom_send = new AlipayMobilePublicLabelUpdateRequest();
        $custom_send->setBizContent($biz_content);
        if($GLOBALS['AopConfig']['app_id'] != $merchant['appid'] && !empty($merchant['alipay_auth_token'])){
            $response = without_request_execute($custom_send, $merchant['appid'], '', $merchant['alipay_auth_token']);
        } else {
            $response = without_request_execute($custom_send, $merchant['appid']);
        }

        return $response;
    }

    /**
     * 增加用户标签
     */
    public function lableUserAdd($lable_id, $user_id)
    {
        $response= '';
        //包装参数成JSON
        $user_lable = array(
            "userId" => $user_id,
            "labelId" => $lable_id,
        );
        $biz_content = json_encode($user_lable);

        //获取APPID并发送数据至服务窗接口
        $merchant_id = Yii::app()->session ['merchant_id'];
        $merchant = Merchant::model()->findByPk($merchant_id);
        $custom_send = new AlipayMobilePublicLabelUserAddRequest();
        $custom_send->setBizContent($biz_content);
        if($GLOBALS['AopConfig']['app_id'] != $merchant['appid'] && !empty($merchant['alipay_auth_token'])){
            $response = without_request_execute($custom_send, $merchant['appid'], '', $merchant['alipay_auth_token']);
        } else {
            $response = without_request_execute($custom_send, $merchant['appid']);
        }

        return $response;
    }

    /**
     * 删除用户标签
     */
    public function lableUserDel($lable_id, $user_id){
        //包装参数成JSON
        $user_lable = array(
            "userId" => $user_id,
            "labelId" => $lable_id,
        );
        $biz_content = json_encode($user_lable);

        //获取APPID并发送数据至服务窗接口
        $merchant_id = Yii::app()->session ['merchant_id'];
        $merchant = Merchant::model()->findByPk($merchant_id);
        $custom_send = new AlipayMobilePublicLabelUserDeleteRequest();
        $custom_send->setBizContent($biz_content);
        return without_request_execute($custom_send, $merchant['appid']);
    }

}