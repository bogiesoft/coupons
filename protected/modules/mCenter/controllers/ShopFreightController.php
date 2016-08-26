<?php
/**
 * 商品运费
 */
class ShopFreightController extends mCenterController
{
    /**
     * 运费模板信息页面
     */
    public function actionShopFreightInfo()
    {
        $data=array();
        $merchantId=Yii::app()->session['merchant_id'];
        $freightC=new FreightC();
        $result=json_decode($freightC->queryFreightInfo($merchantId),true);
        if($result['status']==ERROR_NONE)
        {
            $data=$result['data'];
        }
        $this->render('freightList',array('data'=>$data,'pages'=>$freightC->page));
    }

    /**
     * 添加运费模板
     */
    public function actionAddFreight()
    {
        $merchantId=Yii::app()->session['merchant_id'];
        if(isset($_POST['ShopFreight'])&& !empty($_POST['ShopFreight']))
        {
            $post=$_POST['ShopFreight'];
            $merchantId=Yii::app()->session['merchant_id'];
            $freightC=new FreightC();
            //验证填写数据是否正确
            $flag=true;
            //运费模板名称
            $freight_name=$_POST['freight_name'];
            if(!isset($freight_name))
            {
                $flag = false;
            }
            //验证数据是否填写正确
            /*foreach($post as $key=>$value)
            {
                if(empty($value['area']))
                {
                    $flag = false;
                }
                if(empty($value['first_num']))
                {
                    $flag = false;
                }
                if(empty($value['first_freight']))
                {
                    $flag = false;
                }
                if(empty($value['second_num']))
                {
                    $post[$key]['second_num']=0;
                    $post[$key]['second_freight'] = 0;
                }
                if(empty($value['second_freight']))
                {
                    $post[$key]['second_freight'] = 0;
                }
            }*/
            if($flag)
            {
                $result=json_decode($freightC->addFreight($merchantId,$freight_name,$post),true);
                if($result['status']==ERROR_NONE)
                {
                    Yii::app()->user->setFlash('success', '保存成功');
                   // $this->redirect('shopFreightInfo');
                }
                else
                {
                    Yii::app()->user->setFlash('error', '保存失败');
                }
            }
            else
            {
                    Yii::app()->user->setFlash('error', '保存失败');

            }
        }
        $this->render('addFreight');
    }

    /**
     * 修改运费模板
     */
    public function actionUpdateFreight()
    {
        $merchantId=Yii::app()->session['merchant_id'];
        $freightC=new FreightC();
        if(isset($_GET['id'])&&!empty($_GET['id']))
        {
            $id=$_GET['id'];
            //根据id查询运费模板
            $result=json_decode($freightC->queryFreight($id,$merchantId),true);

            if($result['status']==ERROR_NONE)
            {
                if(isset($_POST['ShopFreight'])&&!empty($_POST['ShopFreight']))
                {
                    $freight_name=$_POST['freight_name'];
                    $mobanid=$_POST['mobanid'];
                    $post=$_POST['ShopFreight'];
                    $flag=true;
                    //运费模板名称
                    if(!isset($freight_name))
                    {
                        $flag = false;
                    }
                    else {

                    }
                    /*foreach($post as $key=>$value) {
                        if (empty($value['area'])) {
                            $flag = false;
                        }
                        if (empty($value['first_num'])) {
                            $flag = false;
                        }
                        if (empty($value['first_freight'])) {
                            $flag = false;
                        }
                        if(empty($value['second_num']))
                        {
                            $post[$key]['second_num']=0;
                            $post[$key]['second_freight'] = 0;
                        }
                        if(empty($value['second_freight']))
                        {
                            $post[$key]['second_freight'] = 0;
                        }
                    }*/
                    if($flag)
                    {
                        $rs=json_decode($freightC->updateFreight($id,$merchantId,$freight_name,$post),true);
                        if($rs['status']==ERROR_NONE)
                        {
                            Yii::app()->user->setFlash('success', '修改成功');
                        }
                        else
                        {
                            Yii::app()->user->setFlash('error', '修改失败');
                        }
                    }
                    else
                    {
                        Yii::app()->user->setFlash('error', '保存失败');
                    }
                }
                $this->render('editFreight',array('model'=>$result['data']));
            }
        }
    }


    /**
     * 添加模板检测模板名称是否重复
     */
    public function actionAddCheckName()
    {
        $merchantId=Yii::app()->session['merchant_id'];
        if(isset($_GET['name'])&&!empty($_GET['name']))
        {
            $freightC=new FreightC();
            $freight_name=$_GET['name'];
            $rs_freightName = json_decode($freightC->checkFreightName($merchantId, $freight_name, 'add'), true);
            if ($rs_freightName['status'] == ERROR_EXCEPTION) {
                echo 'error';
            }
            else
                echo 'success';
        }
    }

    /**
     * 编辑模板检测模板名称是否重复
     */
    public function actionUpdateCheckName()
    {
        $merchantId=Yii::app()->session['merchant_id'];
        if(isset($_GET['name'])&&!empty($_GET['name'])&&isset($_GET['mobanid'])&&!empty($_GET['mobanid']))
        {
            $freightC=new FreightC();
            $freight_name=$_GET['name'];
            $mobanid=$_GET['mobanid'];
            $rs_freightName = json_decode($freightC->checkFreightName($merchantId, $freight_name, 'update',$mobanid), true);
            if ($rs_freightName['status'] == ERROR_EXCEPTION) {
                echo 'error';
            }
            else
                echo 'success';
        }
    }
    /**
     * 删除运费模板
     */
    public function actionDeleteFreight()    {
        $merchantId=Yii::app()->session['merchant_id'];
        if(isset($_GET['id'])&&!empty($_GET['id'])) {
            $id=$_GET['id'];
            $freightC = new FreightC();
            $result = json_decode($freightC->deleleFreight($id,$merchantId), true);//根据模板ID删除模板
            if($result['status']==ERROR_NONE)
            {
                $this->redirect('shopFreightInfo');
            }

        }
    }

    /**
     * 复制模板
     */
    public function actionCopyFreight()
    {
        $merchantId=Yii::app()->session['merchant_id'];
        if(isset($_GET['id'])&&!empty($_GET['id'])) {
            $id=$_GET['id'];
            $freightC = new FreightC();
            $result = json_decode($freightC->copyFreight($id,$merchantId), true);//根据模板ID复制模板
            if($result['status']==ERROR_NONE)
            {
                $this->redirect('ShopFreightInfo');
            }
        }
    }

    /**
     * 删除子模板
     */
    public function actionDeleteSubFreight()    {
        $merchantId=Yii::app()->session['merchant_id'];
        if(isset($_GET['id'])&&!empty($_GET['id'])) {
            $id=$_GET['id'];
            $freight_id=$_GET['freight_id'];
            $freightC = new FreightC();
            $result = json_decode($freightC->deleleSubFreight($id,$freight_id,$merchantId), true);//根据模板ID删除模板
            if($result['status']==ERROR_NONE)
            {
                $this->redirect('ShopFreightInfo');
            }
        }
        else
        {
            $this->redirect('ShopFreightInfo');
        }
    }

    /**
     * 检查该运费模板是否正在被使用
     */
    public function actionFreightIsUsing()
    {
        $merchantId=Yii::app()->session['merchant_id'];
        if(isset($_GET['id'])&&!empty($_GET['id']))
        {
            $freightC=new FreightC();
            $frieghtId=$_GET['id'];
            $rs=json_decode($freightC->FreightIsUsing($merchantId,$frieghtId),true);
            if($rs['status']==ERROR_NONE)
            {
                //该模板没有正在被使用
                echo json_encode('success');
            }else
            {
                echo json_encode('error');
            }
        }
    }
}
?>