<?php
include_once (dirname ( __FILE__ ) . '/../mainClass.php');

include_once $_SERVER['DOCUMENT_ROOT'].'/protected/components/Component.php';
/*
 * 微信基础类
 */
class WechatBase extends mainClass{
    /**
     * 获取用户openid--使用code获取access_code
     */
    public function getUserAccessCode($code, $wechat_appid, $wechat_appsecret, $merchant_id=0)
    {
        $access_token = array();
        $merchant = Merchant::model()->findByPk($merchant_id);
        
        //授权 模式
        if($merchant->wechat_thirdparty_authorizer_if_auth == 2)
        {
            $access_token_url = 'https://api.weixin.qq.com/sns/oauth2/component/access_token?appid='.$wechat_appid.'&code='.$code.'&grant_type=authorization_code&component_appid='.Component::getAppId().'&component_access_token='.Component::getComponentAccessToken();//获取ACCESS_TOKEN请求的URL地址；
            $res = file_get_contents($access_token_url);
            $result = json_decode($res,true);
            return  $result;
        }
        else
        {
            $access_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$wechat_appid.'&secret='.$wechat_appsecret.'&code='.$code.'&grant_type=authorization_code';//获取ACCESS_TOKEN请求的URL地址；
            $res = file_get_contents($access_token_url);
            $result = json_decode($res,true);
            return  $result;
        }
        
    }
    
    /*
     * 获取商户的appid和appsecret
     */
    public function getMerchantAppidAndAppsecret($merchant_id='',$encrypt_id=''){
        $result = array();
        try {
            //获取商户信息
            if(!empty($merchant_id)){
                $merchant = Merchant::model() -> findByPk($merchant_id);
            }elseif (!empty($encrypt_id)){
                $merchant = Merchant::model() -> find('encrypt_id =:encrypt_id',array(
                    ':encrypt_id' => $encrypt_id
                ));
            }
            
            $result['wechat_subscription_appsecret'] = $merchant -> wechat_subscription_appsecret;
            $result['wechat_subscription_appid'] = $merchant -> wechat_subscription_appid;
            $result['status'] = ERROR_NONE;
            
        } catch (Exception $e) {
            $result['status'] = isset($result['status']) ? $result['status'] : ERROR_EXCEPTION;
            $result['errMsg'] = $e->getMessage(); //错误信息
        }
        return json_encode($result);
    }
    
    /**
     * 获取access token信息
     * @param unknown $appid
     * @param unknown $appsecret
     * @return multitype:boolean unknown mixed
     */
    public function getAccessToken($appid, $appsecret) {
        
    	$access_token = array();
    	if (!empty($appid) && !empty($appsecret)) {
    		$access_token_url = WECHAT_API_URL."token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;//获取ACCESS_TOKEN请求的URL地址；
    		$res = file_get_contents($access_token_url);
    		$result = json_decode($res,true);
    		if (isset($result['access_token'])){
    			$access_token['error'] = true;
    			$access_token['access_token'] = $result['access_token'];
    			$access_token['expires_in'] = $result['expires_in'];
    		}else{
    			$access_token['error'] = false;
    		}
    		return  $access_token;
    	}
    }
    
    /**
     * https请求
     * @param $url
     * @param $data
     */
    protected function https_request($url, $data = null)
    {
    	$curl = curl_init();
    	curl_setopt($curl, CURLOPT_URL, $url);
    	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    	if (!empty($data)){
    		curl_setopt($curl, CURLOPT_POST, 1);
    		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    	}
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    	$output = curl_exec($curl);
    	curl_close($curl);
    	return $output;
    }

    /**
     * post提交数据
     * @param type $url
     * @param type $data
     * @return type
     */
    function  postData($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//跳过SSL证书检查  https方式
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果成功只将结果返回，不自动输出任何内容。如果失败返回FALSE,不加这句返回始终是1
 		//curl_setopt ( $ch, CURLOPT_SAFE_UPLOAD, false);
        curl_setopt($ch, CURLOPT_POST, 1); //启用POST提交
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $file_contents = curl_exec($ch);
        if (curl_errno($ch)) {
            return 'Errno' . curl_error($ch);
        }
        curl_close($ch);

        return $file_contents;
    }

    public function http_post_media($url,$strPOST)
    {
        $oCurl = curl_init ();
        curl_setopt ( $oCurl, CURLOPT_SAFE_UPLOAD, false);
        if (stripos ( $url, "https://" ) !== FALSE) {
            curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYPEER, FALSE );
            curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYHOST, false );
        }

        curl_setopt ( $oCurl, CURLOPT_URL, $url );
        curl_setopt ( $oCurl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $oCurl, CURLOPT_POST, true );
        curl_setopt ( $oCurl, CURLOPT_POSTFIELDS, $strPOST );
        $sContent = curl_exec ( $oCurl );
        $aStatus = curl_getinfo ( $oCurl );
        curl_close ( $oCurl );
        if (intval ( $aStatus ["http_code"] ) == 200) {
            return $sContent;
        } else {
            return false;
        }

    }

}