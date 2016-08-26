<?php
class DNotifyController extends mCenterController
{
    public function init(){
        
    }
    
    public function actionDqhNotify(){
        $pid = $_POST['_pid'];
        $sig = $_POST['_sig'];
        $method = $_POST['method'];
        $format = $_POST['format'];
        
        Yii::log('天时推送'.$method);
        if($method == 'validate'){
            //验证通知
            $supplier_id = $_POST['supplier_id'];//验证景区id
            $code = $_POST['code'];//验证码号
            $amount = $_POST['amount'];//验证数量
            $my_orders_id = $_POST['my_orders_id'];//天时订单号
            $another_orders_id = $_POST['another_orders_id'];//订单号
            
            Yii::log('验证结果推送'.$supplier_id.'&'.$code.'&'.$amount.'&'.$my_orders_id.'&'.$another_orders_id,'warning');
            //增加验券数量
            $dOrderMall = new DOrderMall();
            $result = json_decode($dOrderMall -> editOrderSkuConsumeNum($another_orders_id, $amount));
            if($result -> status == ERROR_NONE){
                $json = array(
                    'success' => true,
                    'message' => '成功' 
                );
            }else{
                Yii::log('验证结果推送结果'.$result -> errMsg);
                $json = array(
                    'success' => false,
                    'message' => '失败'
                );
            }
        }elseif ($method == 'send'){
            $orders_id = $_POST['orders_id'];//第三方平台订单ID
            $out_orders_id = $_POST['out_orders_id'];//本平台订单号
            $out_code = $_POST['out_code'];//码号
            $out_money_send = $_POST['out_money_send'];//采购单价
            $out_money_one = $_POST['out_money_one'];//采购发送费
            $out_send_content = $_POST['out_send_content'];//发送内容
            
            Yii::log('码号推送'.$orders_id.'&'.$out_orders_id.'&'.$out_code.'&'.$out_money_send.'&'.$out_money_one.'&'.$out_send_content,'warning');
            //保存码号
            $dOrderMall = new DOrderMall();
            $result = json_decode($dOrderMall -> editOrderThridPartyCode($orders_id, $out_code));
            if($result -> status == ERROR_NONE){
                $json = array(
                    'success' => true,
                    'message' => '成功'
                );
            }else{
                Yii::log('码号推送结果'.$result -> errMsg);
                $json = array(
                    'success' => false,
                    'message' => '失败'
                );
            }
            
        }

        echo json_encode($json);
    }

}