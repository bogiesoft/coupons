<?php
/**
 * 商品管理
 */
class ShopGroupController extends mCenterController
{
    /**
     * 商品分组显示列表
     */
    public function actionShopGroupList()
    {
        $data=array();
        $shopname = '';
        $shop=new ShopC();
        $merchantId=Yii::app()->session['merchant_id'];//商户id
        if(isset($_GET['groupname'])&&!empty($_GET['groupname']))//判断查询字段是否为空
        {
            $shopname=$_GET['groupname'];
        }        
        $result=json_decode($shop->shopGroupList($merchantId,$shopname),true);
        if($result['status']==ERROR_NONE)
        {
            $data=$result['data'];
        }
        $flag = 0;
        if(empty($_POST['id']))//如果id为空为添加分组
        {
            if(isset($_POST['ShopGroup']) && $_POST['ShopGroup'])
            {
                $post = $_POST['ShopGroup'];

                if(empty($post['name']))
                {
                    $flag = 1;
                    Yii::app()->user->setFlash('name','请填写分组名称');
                }
                $merchantId=Yii::app()->session['merchant_id'];
                $name = $post['name'];
                //查询分组名重复
                $rs_repeat=json_decode($shop->ShopNameIsRepeat($merchantId,$name),true);
                if($rs_repeat['status']==ERROR_DUPLICATE_DATA)
                {
                    $flag = 1;
                    Yii::app()->user->setFlash('name','分组名重复');
                }
                if($flag == 0)
                {
                    $rs=json_decode($shop->addShopGroup($merchantId,$name),true);
                    if($rs['status'] == ERROR_NONE)
                    {
                        $this->redirect(array('ShopGroupList'));
                    }
                }
            }
        }
        if(isset($_POST['id']) && !empty($_POST['id']))//如果id不为空为修改分组
        {
            if(isset($_POST['ShopGroup']) && $_POST['ShopGroup'])
            {
                $flag = 0;
                $post = $_POST['ShopGroup'];
                if(empty($post['name']))
                {
                    $flag = 1;                
                    Yii::app()->user->setFlash('names','请填写分组名称');
                }
                $merchantId=Yii::app()->session['merchant_id'];
                $name = $post['name'];
                $id=$_POST['id'];
                //查询分组名重复
                $rs_repeat=json_decode($shop->ShopNameIsRepeat($merchantId,$name,$id),true);
                if($rs_repeat['status']==ERROR_DUPLICATE_DATA)
                {
                    $flag = 1;
                    Yii::app()->user->setFlash('name','分组名重复');
                }
                if($flag == 0)
                {

                    $result=$shop->editShopGroup($id,$merchantId,$name);
                    $rs = json_decode($result,true);
                    if($rs['status'] == ERROR_NONE)
                    {
                        $url = $_POST['gourl'];
                        echo "<script>alert('修改成功');window.location.href='$url'</script>";
                    } else {
                        $url = $_POST['gourl'];
                        echo "<script>alert('修改失败');window.location.href='$url'</script>";
                    }
                }
            }
        }
        $this->render('shopGroupList',array(
        		'data'=>$data,
        		'pages' => $shop -> page
        ));
    }
    

    /**
     * 删除商品分组
     */
    public function actionDeleteShopGroup()
    {
        if(isset($_GET['id'])&&!empty($_GET['id']))
        {
            $id=$_GET['id'];
            $merchantId=Yii::app()->session['merchant_id'];
            $shop=new ShopC();
            $result=$shop->deleteShopGroup($id,$merchantId);
            $rs = json_decode($result,true);
            if($rs['status'] == ERROR_NONE)
            {
                $this->redirect($_GET['gourl']);
            }
        }        
    }
    
}
?>