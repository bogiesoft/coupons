<?php
/**
 * 商城首页
 * xyf
 * 2015/09/22
 */
class ShopMallHomeController extends mCenterController{
	
	/**
	 * 商城首页
	 */
	public function actionSetShopMall()
	{
		$list = array();
		$arr = array();//传$arr而不传$result['data']['list']['banner_info']是由于$list为空的时候  js报错
		$merchant_id = Yii::app()->session['merchant_id'];
		
		$mallHomeC = new MallHomeC();
		$result = $mallHomeC -> getShopIndexHeader($merchant_id);
		$result = json_decode($result,true);
		if($result['status'] == ERROR_NONE){
			$list = $result['data']['list'];
			$arr = $result['data']['list']['banner_info'];
		}
		
		
		$shop=new ShopC();
		$rs=json_decode($shop->queryShopBody($merchant_id),true);
		$data=array();
		if($rs['status']==ERROR_NONE)
		{
			$data=$rs['data'];
		}
		$this -> render('setShopMall',array('list'=>$list,'data'=>$data,'arr'=>$arr));
	}
	
	/**
	 * 添加头部弹出框
	 */
	public function actionDialogHeader()
	{
		$list = array();
		$merchant_id = Yii::app()->session['merchant_id'];
		$group_name = '';
		if(isset($_GET['group_name']) && $_GET['group_name']){
			$group_name = $_GET['group_name'];
		}
		
		$shop_type = '';
		if(isset($_GET['shop_type']) && $_GET['shop_type']){
			$shop_type = $_GET['shop_type'];
		}

        $type='';
        if(isset($_GET['type']) && $_GET['type']){
            $type = $_GET['type'];
        }
		
		$mallHomeC = new MallHomeC();
		if($shop_type == SHOP_TYPE_GROUP || $shop_type==''){
			$shop_type = SHOP_TYPE_GROUP;
		    $result = $mallHomeC -> getShopGroupList($merchant_id,$group_name,5);
		}elseif ($shop_type == SHOP_TYPE_PRODUCT){
			//$isSave = 1;
			$result = $mallHomeC -> getShopProductList($merchant_id,$group_name,5);
		}

		$result = json_decode($result,true);
		if($result['status'] == ERROR_NONE){
			$list = $result['data']['list'];
		}
		
		$index = '';
		if(isset($_GET['index'])){
			$index = $_GET['index'];
		}
		
		$flag = '';
		if(isset($_GET['flag'])){
			$flag = $_GET['flag'];
		}
		$this -> render('dialogHeader',array(
				'list'=>$list,
				'pages'=>$mallHomeC->page,
				'shop_type'=>$shop_type,
				'index'=>$index,
				'flag'=>$flag,
                'type'=>$type
		));
	}
	
	/**
	 * 添加数据到wq_shop_index表
	 */
	public function actionAddShopIndex()
	{
		$mallHomeC = new MallHomeC();
		$merchant_id = Yii::app()->session['merchant_id'];
		$data = '';
		if(isset($_POST['data']) && $_POST['data']){
			$data = $_POST['data'];
		}
		$group_id = array();
		if(isset($_POST['group_id']) && $_POST['group_id']){
			$group_id = $_POST['group_id'];
		}
		
		$isData = '';
		if(isset($_POST['isData']) && $_POST['isData']){
			$isData = $_POST['isData'];
		}
		
		$result = $mallHomeC -> addShopIndex($merchant_id,$data,$group_id,$isData);
		$result = json_decode($result,true);
		if($result['status'] == ERROR_NONE){
			echo "操作成功";
		}
	}
	
	/**
	 * 删除操作
	 */
	public function actionDelShopIndex($shop_index_id)
	{
		$mallHomeC = new MallHomeC();
		$result = $mallHomeC -> delShopIndex($shop_index_id);
		$result = json_decode($result,true);
		if($result['status'] == ERROR_NONE){
			$this->redirect(array('setShopMall'));
		}
	}
	
	/**
	 * 删除wq_shop_index表banner字段的第一个对象
	 */
	public function actionDelShopIndexChild($shop_index_id)
	{
		$mallHomeC = new MallHomeC();
		$result = $mallHomeC -> delShopIndexChild($shop_index_id);
		$result = json_decode($result,true);
		if($result['status'] == ERROR_NONE){
			$this->redirect(array('setShopMall'));
		}
	}
	
	/**
	 * 获取头部子列表操作
	 */
	public function actionGetHeader()
	{
		$merchant_id = Yii::app()->session['merchant_id'];
// 		$shop_index_id = '';
// 		if(isset($_POST['shop_index_id']) && $_POST['shop_index_id']){
// 			$shop_index_id = $_POST['shop_index_id'];
// 		}
		$mallHomeC = new MallHomeC();
		$result = $mallHomeC -> getHeader($merchant_id);
		$result = json_decode($result,true);
		if($result['status'] == ERROR_NONE){
			echo json_encode($result['data']);
		}
	}
	
	/**
	 * 修改头部信息
	 */
	public function actionEditShopIndex()
	{
		$merchant_id = Yii::app()->session['merchant_id'];
		$shop_index_id = '';
		if(isset($_POST['shopIndexId']) && $_POST['shopIndexId']){
			$shop_index_id = $_POST['shopIndexId'];
		}
		
		$data = '';
		if(isset($_POST['data']) && $_POST['data']){
			$data = $_POST['data'];
		}
		$group_id = array();
		if(isset($_POST['group_id']) && $_POST['group_id']){
			$group_id = $_POST['group_id'];
		}
		$mallHomeC = new MallHomeC();
		$result = $mallHomeC -> editShopIndex($shop_index_id,$data,$merchant_id,$group_id);
		$result = json_decode($result,true);
		if($result['status'] == ERROR_NONE){
			echo "操作成功";
		}
	}
	
	/**
	 * 删除子列表
	 */
	public function actionDelShopIndexChildForId()
	{
	    $shop_index_id = '';
		if(isset($_POST['shop_index_id']) && $_POST['shop_index_id']){
			$shop_index_id = $_POST['shop_index_id'];
		}
		
		//要删除的子列表的数组索引
		$delId = '';
		if(isset($_POST['delId']) && $_POST['delId']){
			$delId = $_POST['delId'];
		}
		
		$mallHomeC = new MallHomeC();
		$result = $mallHomeC -> delShopIndexChildForId($shop_index_id,$delId);
		$result = json_decode($result,true);
		if($result['status'] == ERROR_NONE){
			echo "操作成功";
		}
	}

	
	public function actionShopDialogBody()
	{

		$merchantId=Yii::app()->session['merchant_id'];
		$shop=new ShopC();
		$name="";
        $type='add';
        $num=0;
		if(isset($_GET['groupname']))
		{
			$name=$_GET['groupname'];
		}
        if(isset($_GET['type']))
        {
            $type=$_GET['type'];
        }
        if(!empty($_GET['edit_num']))
        {
            $num=$_GET['edit_num'];
        }
		$result=json_decode($shop->shopBodyEditList($merchantId,$name),true);
		$data=array();
		if($result['status']==ERROR_NONE)
		{
			$data=$result['data'];
		}
		$this->render('dialogBody',array('data'=>$data,'edit_num'=>$num,'type'=>$type,'pages'=>$shop->page));
	}
	
	public function actionSaveShopEdit()
	{
		$merchantId=Yii::app()->session['merchant_id'];
		$id_array="";//存放添加的ID
		if(isset($_POST['group_num'])&&!empty($_POST['group_num']))
		{
			$num=$_POST['group_num'];
			for($i=1;$i<=$num;$i++)
			{
			if($i==1)
				$id_array=$_POST['groupid'.$i];
				else
				$id_array=$id_array.",".$_POST['groupid'.$i];
			}
			$shop=new ShopC();
			$rs=json_decode($shop->saveShopBodyEdit($merchantId,$id_array),true);
			if($rs['status']==ERROR_NONE)
			{
				echo '保存成功';
			}
		}else{
			var_dump('保存失败');
		}
	}





    /**
     * 保存商城首页设置
     */
    public function actionSaveShopMall()
    {
        $merchantId=Yii::app()->session['merchant_id'];
        $order=array();
        $img_nav_arr=array();
        $img_nav_href_arr=array();
        $img_adv_arr=array();
        $img_adv_href_arr=array();
        $shop_carousel_img_arr=array();
        $shop_carousel_href_arr=array();
        $shop_group_href_arr=array();
        $json=array();
        if(!empty($_POST['data_order']))
        {
            $order=$_POST['data_order'];
            $img_nav_arr=$_POST['data_img_nav_arr'];
            $img_nav_href_arr=$_POST['data_img_nav_href_arr'];
            $img_adv_arr=$_POST['data_img_adv_arr'];
            $img_adv_href_arr=$_POST['data_img_adv_href_arr'];
            $shop_carousel_img_arr=$_POST['data_shop_carousel_img_arr'];
            $shop_carousel_href_arr=$_POST['data_shop_carousel_href_arr'];
            $shop_group_href_arr=$_POST['data_shop_group_href_arr'];

            $shop_search_arr=array();
            for($order_i=0;$order_i<count($order);$order_i++)
            {
                $order_str=explode(';',$order[$order_i]);//格式:名字;数组列数
                if($order_str[0]=='shop_search')
                {
                    //搜索
                    $json[$order_i]['name']='shop_search';
                    $json[$order_i]['url']='null';
                    $json[$order_i]['href']='null';
                }
                else if($order_str[0]=='img_nav')
                {
                    //图片导航
                    $json[$order_i]['name']='img_nav';
                    $json[$order_i]['url']=$img_nav_arr[intval($order_str[1])];
                    $json[$order_i]['href']=$img_nav_href_arr[intval($order_str[1])];//格式ID;名字;类型

                }
                else if($order_str[0]=='img_adv')
                {
                    //图片广告
                    $json[$order_i]['name']='img_adv';
                    $json[$order_i]['url']=$img_adv_arr[intval($order_str[1])];
                    $json[$order_i]['href']=$img_adv_href_arr[intval($order_str[1])];//格式ID;名字;类型
                }
                else if($order_str[0]=='shop_carousel')
                {
                    //轮播图
                    $json[$order_i]['name']='shop_carousel';
                    $json[$order_i]['url']=$shop_carousel_img_arr[intval($order_str[1])];
                    $json[$order_i]['href']=$shop_carousel_href_arr[intval($order_str[1])];//格式ID;名字;类型
                }else if($order_str[0]=='shop_group'){
                    //商品分组
                    $json[$order_i]['name']='shop_group';
                    $json[$order_i]['url']='null';
                    $json[$order_i]['href']=$shop_group_href_arr[intval($order_str[1])];//格式ID;名字;类型
                }
            }
            $json_encode=json_encode($json);

            $mallHomeC = new MallHomeC();

            $result=json_decode($mallHomeC->SaveShopMall($json_encode,$merchantId),true);
            if($result['status']==ERROR_NONE)
                echo 'success';
            else
                echo 'error';
        }
        else
        {
            echo 'error';
        }
    }

    /**
     * 商城首页
     */
    public function actionShopMallIndex()
    {
        $data=array();
        $banner=array();
        $merchantId=Yii::app()->session['merchant_id'];
        $mallHomeC = new MallHomeC();
        $result=json_decode($mallHomeC->getShopMallIndex($merchantId),true);
        if($result['status']==ERROR_NONE)
        {
            $data=$result['data'];
            $banner=json_decode($data['banner'],true);
        }
        $shop_search=array();
        $img_nav=array();
        $img_adv=array();
        $shop_carousel=array();
        $shop_group=array();

        $shop_search_count=0;
        $img_nav_count=0;
        $img_adv_count=0;
        $shop_carousel_count=0;
        $shop_group_count=0;

        for($i=0;$i<count($banner);$i++)
        {
            if(isset($banner[$i]['name']) && $banner[$i]['name']=='shop_search')
            {
                $shop_search[$shop_search_count]=$banner[$i];
                $shop_search_count++;
            }
            else if(isset($banner[$i]['name']) && $banner[$i]['name']=='img_nav')
            {
                $img_nav[$img_nav_count]=$banner[$i];
                $img_nav_count++;
            }
            else if(isset($banner[$i]['name']) && $banner[$i]['name']=='img_adv')
            {
                $img_adv[$img_adv_count]=$banner[$i];
                $img_adv_count++;
            }
            else if(isset($banner[$i]['name']) && $banner[$i]['name']=='shop_carousel')
            {
                $shop_carousel[$shop_carousel_count]=$banner[$i];
                $shop_carousel_count++;
            }else if(isset($banner[$i]['name']) && $banner[$i]['name']=='shop_group'){
                $shop_group[$shop_group_count]=$banner[$i];
                $shop_group_count++;
            }
        }
//        var_dump($shop_carousel);
//        var_dump($banner);
        $this -> render('setShopMall',array(
            'banner'=>$banner,
            'shop_search'=>$shop_search,
            'img_nav'=>$img_nav,
            'img_adv'=>$img_adv,
            'shop_carousel'=>$shop_carousel,
            'shop_group'=>$shop_group
        ));
    }
}