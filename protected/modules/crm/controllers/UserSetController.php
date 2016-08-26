<?php

/**
 * 会员认证设置
 */
class UserSetController extends CrmController{
    /**
     * 设置会员认证所需资料填写
     */
    public function actionAuthSettings(){
        $userc = new UserC();
        $merchant_id = Yii::app()->session['merchant_id'];
        $result = $userc->getAuthSet($merchant_id);
        $this->render('authSettings',array('authset'=>$result));
    }

    /**
     * 保存设置会员认证所需资料填写
     */
    public function actionSaveAuthSettings(){
        $info = $_GET;
        $arr = array();
        if(isset($info['cert'])){
            foreach ($info['cert'] as $k => $v){
                if($v == 'on'){
                    $arr[] = $k;
                }
            }
        }
        $authset = implode(',', $arr);
        $userc = new UserC();
        $merchant_id = Yii::app()->session['merchant_id'];
        $result = $userc->saveAuthSet($merchant_id, $authset);
        $result = json_decode($result,true);
        if($result['status'] == ERROR_NONE){
            $url = $this->createUrl('authsettings');
            echo "<script>alert('保存成功');window.location.href='$url'</script>";
        }
    }
}