<?php

class DuanXinController extends mCenterController
{
    /**
     * 短信充值
     */
    public function actionDuanXinCart()
    {    
        $duanXin    = new DuanXinC();
        $merchantId = Yii::app()->session['merchant_id'];        
        $model      = $duanXin->DuanXinNum($merchantId);
        $num        = json_decode($model,true);
        if($num['status'] == ERROR_NONE)
        {
            $n = $num['data'];
        } else {
            $status = $result['status'];
            $msg    = $result['errMsg'];
        }
        $this->render('duanXinCart',array('num'=>$n));
    }
    
    /**
     * 短信购买
     */
    public function actionDuanXinPay()
    {    
        $duanXin    = new DuanXinC();
        $merchantId = Yii::app()->session['merchant_id'];
        if(isset($_GET['combo']) && $_GET['combo'])
        {            
            $messageNum = $_GET['combo'];
            $num        = 1;
            if($_GET['combo'] == $GLOBALS['__DXTC']['1']['sub']['num']){
                $price = $GLOBALS['__DXTC']['1']['sub']['unit_price'];
                $money = $_GET['combo'] * $GLOBALS['__DXTC']['1']['sub']['unit_price'];
            }
            if($_GET['combo'] == $GLOBALS['__DXTC']['2']['sub']['num']){
                $price = $GLOBALS['__DXTC']['2']['sub']['unit_price'];
                $money = $_GET['combo'] * $GLOBALS['__DXTC']['2']['sub']['unit_price'];
            }
            if($_GET['combo'] == $GLOBALS['__DXTC']['3']['sub']['num']){
                $price = $GLOBALS['__DXTC']['3']['sub']['unit_price'];
                $money = $_GET['combo'] * $GLOBALS['__DXTC']['3']['sub']['unit_price'];
            }
            if(isset($_POST['qty_item_1']) && $_POST['qty_item_1'])
            {
                $num        = $_POST['qty_item_1'];
                $money      = $money*$num;
                $messageNum = $messageNum*$num;
                $ret        = $duanXin->DuanXinPay($merchantId, $messageNum, $price, $money, $num);
                $result     = json_decode($ret,true);  
                if ($result['status'] == ERROR_NONE) 
                {
                    $orderNo     = $result['data']; 
                    $productName = $_GET['combo'];
                    echo("<script>parent.location.href='".Yii::app() -> createUrl('mCenter/Pay/Alipay',array('orderNo'=>$orderNo,'productName'=>$productName,'money'=>$money))."'</script>");
                } else {
                    $status = $result['status'];
                    $msg    = $result['errMsg'];
                }
            }        

        }
        $this->render('duanXinPay',array('price'=>$price));
    }
    
    /**
     * 充值记录
     */
    public function actionDuanXinList()
    {
        $list       = array();
        $duanXin    = new DuanXinC();
        $merchantId = Yii::app()->session['merchant_id'];
        $model      = json_decode($duanXin->DuanXinList($merchantId),true);        
        if ($model['status'] == ERROR_NONE) 
        {
            if (isset($model['data']['list'])) 
            {
                $list = $model['data']['list'];
            }
        }
        $this->render('duanXinList',array(
        		'list'=>$list,
        		'pages' => $duanXin -> page
        ));
    }
    
    
    
}

