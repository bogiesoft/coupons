<?php

/**
 * 标签管理
 */
class LabelController extends mCenterController
{
    /**
     * 标签列表
     */
    public function actionLabelList()
    {
        $merchant_id = Yii::app()->session['merchant_id']; //商户id
        $ret = new LabelC();
        $result = $ret ->LabelList($merchant_id);
        $result = json_decode($result);
        if($result -> status == ERROR_NONE){
            $tag = $result -> data;
        } else {
            $tag = array();
        }
        $this->render('labelList',array('tag' => $tag));
    }

    /**
     * 添加属性标签
     */
    public function actionAddLabel()
    {   
        $merchant_id = Yii::app()->session['merchant_id']; //商户id
        if($_POST){
            $label_name = $_POST['label_name'];//标签名称
            $values = json_encode($_POST['values']);//属性值
            $ret = new LabelC();
            $result = $ret ->AddLabel($merchant_id, $label_name, $values);
            $result = json_decode($result);
            if($result -> status == ERROR_NONE){
                $this -> redirect(array('labelList'));
            }
        }
        $this->render('addLabel');
    }
    
    /**
     * 编辑属性标签
     */
    public function actionEditLabel($id)
    {   
        $merchant_id = Yii::app()->session['merchant_id']; //商户id
        $ret = new LabelC();
        if($_POST){
            $label_name = $_POST['label_name'];//标签名称
            $values = json_encode($_POST['values']);//属性值            
            $result = $ret ->EditLabel($merchant_id, $label_name, $values, $id);
            $result = json_decode($result);
            if($result -> status == ERROR_NONE){
                $this -> redirect(array('labelList'));
            }
        }
        //获取显示标签数据
        $getlabel = json_decode($ret->getLabel($merchant_id, $id));
        if($getlabel -> status == ERROR_NONE) {
            $label = $getlabel -> data;
        } else {
            $label = array();
        }
        $this->render('editLabel',array('label'=>$label,'id'=>$id));
    }

    /**
     * 添加条件标签
     */
    public function actionAddCondition()
    {
        
    }
}

