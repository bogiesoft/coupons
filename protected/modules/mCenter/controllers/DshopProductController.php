<?php

class DshopProductController extends mCenterController{

	//商品列表
	public function actionProductList()
    {
        $merchant_id = Yii::app()->session['merchant_id'];
        $shop = new DShopC();

        //获取商品分组
        $rs=json_decode($shop->getGroup($merchant_id),true);
        $groupList=array();
        if($rs['status']==ERROR_NONE)
        {
            $groupList=$rs['data'];
        }
        if($_GET){
                $pro_status = empty($_GET['pro_status']) ? "" : $_GET['pro_status'];
            $group_id = empty($_GET['shop_group']) ? "" : $_GET['shop_group'];
            $key_word = empty($_GET['key_word']) ? "" : $_GET['key_word'];
            $arrow = empty($_GET['arrow']) ? "arrowUp" : $_GET['arrow'];
            $arrow_type=empty($_GET['arrow_type']) ? "create_time" : $_GET['arrow_type'];
        }else{
        	$pro_status = empty($_POST['Product']['pro_status']) ? "" : $_POST['Product']['pro_status'];
            $group_id = empty($_POST['Product']['shop_group']) ? "" : $_POST['Product']['shop_group'];
            $key_word = empty($_POST['Product']['key_word']) ? "" : $_POST['Product']['key_word'];
            $arrow = empty($_POST['arrow']) ? "arrowUp" : $_POST['arrow'];
            $arrow_type=empty($_POST['arrow_type']) ? "create_time" : $_POST['arrow_type'];
        }

        
		$get_group_res = $shop->getShopGroup($merchant_id);
		if ($get_group_res['status'] == ERROR_NONE) {
			$group = $get_group_res['data'];
		}
		
		//商品状态
		$product_status_arr = $GLOBALS['SHOP_PRODUCT_STATUS'];
		
		$result = json_decode($shop -> getProductList($merchant_id, $pro_status, $group_id, $key_word,$arrow,$arrow_type));

        if($arrow=='arrowUp'){
            $arrow='arrowDown';
        }else if($arrow=='arrowDown'){
            $arrow='arrowUp';
        }
		if($result -> status == ERROR_NONE){
            $this->render('productList', array(
                'group_id' => $group_id,
            	'pro_status' => $pro_status,
                'key_word' => $key_word,
                'arrow' => $arrow,
                'arrow_type'=>$arrow_type,
                'product' => $result->data->list,
                'pages' => $shop->page,
                'group' => $group,
                'grouplist'=>$groupList,
            	'pro_status_arr' => $product_status_arr,
                'pages'=>$shop->page,
                'merchant_id' => $merchant_id
            ));
		}
	}

    /**
     * 商品改分组
     */
    public function actionChangeGroup()
    {
        $merchant_id = Yii::app()->session['merchant_id'];
        $group_arr=array();
        $shop_arr=array();
        if(!empty($_GET))
        {
            $group_arr=$_GET['group_arr'];
            $shop_arr=$_GET['shop_arr'];
            $group_id='';
            for($i=0;$i<count($group_arr);$i++)
            {
                if(!empty($group_id))
                    $group_id=$group_id.','.$group_arr[$i];
                else
                    $group_id=$group_arr[$i];
            }
            $flag=true;
            for($j=0;$j<count($shop_arr);$j++) {
                $shop = new DShopC();
                $rs = json_decode($shop->ChangeGroup($merchant_id, $group_id, $shop_arr[$j]), true);
                if($rs['status']==ERROR_SAVE_FAIL)
                {
                    $flag=false;
                    echo json_encode('error');
                }
            }
            if($flag)
                echo json_encode('success');
        }
        else
        {
            echo json_encode('error');
        }
    }
	
	/**
	 * 添加商品页面
	 */
	public function actionAddProductOfCategory()
	{
		$merchant_id = Yii::app()->session['merchant_id'];
		$shop = new DShopC();
		//商品分类
		$category_one = $GLOBALS['CATEGORY_ONE'];
		$category_two = $GLOBALS['CATEGORY_TWO'];
		//商品规格模板
		$get_standard_res = $shop->getProStandard($merchant_id);
		if ($get_standard_res['status'] == ERROR_NONE) {
			$standard_model = $get_standard_res['data'];
		}
		$standard_id = 0; //默认商品模板
		//商品分组
		$get_group_res = $shop->getShopGroup($merchant_id);
		if ($get_group_res['status'] == ERROR_NONE) {
			$group = $get_group_res['data'];
		}
		//运费模板
		$get_freight_res = $shop->getShopFreight($merchant_id);
		if ($get_freight_res['status'] == ERROR_NONE) {
			$freight = $get_freight_res['data'];
		}
		
		if(!empty($_POST['Product']) && isset($_POST['Product'])){
                   
			$id = "";
			$post = $_POST['Product'];                        
			$category_id = $post['category_one'].','.$post['category_two'];
			$group_id = $post['shop_group'];
			$standard = $post['pro_standard_name'];
			$page_id = $post['standard_model'];
			$type = $post['type'];
			$name = $post['pro_name'];
			$price = $post['pro_price'];
			$img_arr = empty($post['pro_img']) ? "" : $post['pro_img'];
			$freight_type = SHOP_FREIGHT_TYPE_UNITE;
			$freight_money = 0;
			$freight_id = isset($post['freight_id']) ? $post['freight_id'] : "0";
			$if_invoice = $post['invoice'];
			$sku_arr = empty($post['pro']) ? "" : $post['pro'];
			$limit_num = $post['limit_num'];
			$brief_introduction = $post['pro_introduction'];
			$detailed_introduction = $post['pro_detail'];
			$if_show_num  = empty($post['if_show_num']) ? IF_SHOW_YES : $post['if_show_num'];
			$stock_num = $post['all_num'];
                        $third_party_source = $post['third_party_source'];
                        $third_party_product_id = $post['third_party_product_id'];
                        $use_time_type = $post['use_time_type'];
                        $date_num = !empty($post['date_num1']) ? $post['date_num1'] : $post['date_num2'];
			
            $check_time_type = isset($post['check_time_type']) ? $post['check_time_type'] : DMALL_DPRODUCT_CHECK_TIME_TYPE_NO_LIMIT;

			$check_day = isset($post['check_day']) ? $post['check_day'] : 0;

			if($check_time_type == DMALL_DPRODUCT_CHECK_TIME_TYPE_DAY_HOUR_MINUTE){
				$check_hour = isset($post['check_hour1']) ? $post['check_hour1'] : 0;
				$check_minute = isset($post['check_minute1']) ? $post['check_minute1'] : 0;
			}
			if($check_time_type == DMALL_DPRODUCT_CHECK_TIME_TYPE_HOUR_MINUTE){
				$check_hour = isset($post['check_hour2']) ? $post['check_hour2'] : 0;
				$check_minute = isset($post['check_minute2']) ? $post['check_minute2'] : 0;
			}

			$save_pro_res = $shop->saveProduct($id, $merchant_id, $category_id, $group_id, $standard, $page_id, $type, $name, $price, $img_arr, $freight_type, $freight_money, $freight_id, $if_invoice, $sku_arr, $limit_num, $brief_introduction, $detailed_introduction, $if_show_num, $stock_num, $third_party_source, $third_party_product_id, $use_time_type, $date_num, $check_time_type, $check_day, $check_hour, $check_minute);

			if ($save_pro_res['status'] == ERROR_NONE) {
				$this->redirect('productList');
			}else {
				Yii::app()->user->setFlash('error',$save_pro_res['errMsg']);
			}
			
// 			$num = $post['num'];
		}
		$this->render('addProductOfCategory', array(
				'category_one'=>$category_one, 
				'category_two'=>$category_two, 
				'standard_model' => $standard_model, 
				'standard_id' => $standard_id, 
				'group'=>$group, 
				'freight' => $freight
		));
	}
	
	/*
	 * 编辑商品页面
	 */
	public function actionEditProductOfCategory()
	{
		// error_reporting(1);
		$shop = new DShopC();
		$id = $_GET['pro_id'];
		$merchant_id = Yii::app()->session['merchant_id'];
		
		//商品分类
		$category_one = $GLOBALS['CATEGORY_ONE'];
		$category_two = $GLOBALS['CATEGORY_TWO'];
		//商品规格模板
		$get_standard_res = $shop->getProStandard($merchant_id);
		if ($get_standard_res['status'] == ERROR_NONE) {
			$standard_model = $get_standard_res['data'];
		}
		//商品分组
		$get_group_res = $shop->getShopGroup($merchant_id);
		if ($get_group_res['status'] == ERROR_NONE) {
			$group = $get_group_res['data'];
		}
		//运费模板
		$get_freight_res = $shop->getShopFreight($merchant_id);
		if ($get_freight_res['status'] == ERROR_NONE) {
			$freight = $get_freight_res['data'];
		}
		//商品信息
		$get_pro = $shop->getProduct($id);
		if ($get_pro['status'] == ERROR_NONE) {
			$list = $get_pro['data'];
			$standard_id = $list['page_id']; //默认商品模板
			// var_dump($list);exit;
		}


		if(!empty($_POST['Product']) && isset($_POST['Product'])){
			$post = $_POST['Product'];
			$id = $post['product_id'];
			$category_id = $post['category_one'].','.$post['category_two'];
			$group_id = $post['shop_group'];
			$standard = $post['pro_standard_name'];
			$page_id = 0;
			$type = $post['type'];
			$name = $post['pro_name'];
			$price = $post['pro_price'];
			$img_arr = empty($post['pro_img']) ? "" : $post['pro_img'];
			$freight_type = SHOP_FREIGHT_TYPE_UNITE;
			$freight_money = 0;
			$freight_id = isset($post['freight_id']) ? $post['freight_id'] : "0";
			$if_invoice = $post['invoice'];
			$sku_arr = empty($post['pro']) ? "" : $post['pro'];
			$limit_num = $post['limit_num'];
			$brief_introduction = $post['pro_introduction'];
			$detailed_introduction = $post['pro_detail'];
			$if_show_num  = empty($post['if_show_num']) ? IF_SHOW_YES : $post['if_show_num'];
			$stock_num = $post['all_num'];
            $third_party_source = $post['third_party_source'];
            $third_party_product_id = isset($post['third_party_product_id']) ? $post['third_party_product_id'] : '';
            $use_time_type = $post['use_time_type'];
            $date_num = !empty($post['date_num1']) ? $post['date_num1'] : $post['date_num2'];

			// 新添加字段 By Jelly 2016-3-18 09:10:03
			$check_time_type = isset($post['check_time_type']) ? $post['check_time_type'] : DMALL_DPRODUCT_CHECK_TIME_TYPE_NO_LIMIT;

			$check_day = isset($post['check_day']) ? $post['check_day'] : 0;

			if($check_time_type == DMALL_DPRODUCT_CHECK_TIME_TYPE_DAY_HOUR_MINUTE){
				$check_hour = isset($post['check_hour1']) ? $post['check_hour1'] : 0;
				$check_minute = isset($post['check_minute1']) ? $post['check_minute1'] : 0;
			}
			if($check_time_type == DMALL_DPRODUCT_CHECK_TIME_TYPE_HOUR_MINUTE){
				$check_hour = isset($post['check_hour2']) ? $post['check_hour2'] : 0;
				$check_minute = isset($post['check_minute2']) ? $post['check_minute2'] : 0;
			}
			

			$save_pro_res = $shop->saveProduct($id, $merchant_id, $category_id, $group_id, $standard, $page_id, $type, $name, $price, $img_arr, $freight_type, $freight_money, $freight_id, $if_invoice, $sku_arr, $limit_num, $brief_introduction, $detailed_introduction, $if_show_num, $stock_num, $third_party_source, $third_party_product_id,$use_time_type,$date_num, $check_time_type, $check_day, $check_hour, $check_minute);

			if ($save_pro_res['status'] == ERROR_NONE) {
				$this->redirect('productList');
			}else {
				$list['id'] = $post['product_id'];
				$list['category_one'] = $post['category_one'];
				$list['category_two'] = $post['category_two'];
				$list['group_id'] = $post['shop_group'];
				$list['page_id'] = 0;
				if (!empty($post['pro'])){
					$num = 0;
					foreach ($post['pro'] as $key => $value){
						$list['pro_sku'][$key]['property'] = $value['att'];
						$list['pro_sku'][$key]['num'] = $value['sku_number'];
						$list['pro_sku'][$key]['price'] = $value['sku_new_prize'];
						$list['pro_sku'][$key]['original_price'] = $value['sku_old_prize'];
						$num += $value['sku_number'];
					}
					$list['num'] = $num;
				}
				$list['img'] = empty($post['pro_img']) ? "" : $post['pro_img'];
				$list['name'] = $post['pro_name'];
	    		$list['price'] = $post['pro_price'];
	    		$list['freight_type'] = $post['freight_type'];
	    		$list['freight_money'] = isset($post['pro_total_inventory']) ? $post['pro_total_inventory'] : "";
	    		$list['freight_id'] = isset($post['freight_id']) ? $post['freight_id'] : "0";
	    		$list['limit_num'] = $post['limit_num'];
	    		$list['if_invoice'] = $post['invoice'];
	    		$list['if_show_num']  = empty($post['if_show_num']) ? IF_SHOW_YES : $post['if_show_num'];
				
				$list['check_time_type'] = $post['check_time_type'];
				$list['check_day'] = $post['check_day'];
				$list['check_hour'] = $post['check_hour'];
				$list['check_minute'] = $post['check_minute'];

				Yii::app()->user->setFlash('error',$save_pro_res['errMsg']);
			}
		}
		
		$this->render('editProductOfCategory',array(
				'category_one'=>$category_one, 
				'category_two'=>$category_two, 
				'standard_model' => $standard_model, 
				'standard_id' => $standard_id, 
				'group'=>$group, 
				'freight' => $freight, 
				'list'=>$list
		));
		// var_dump($list);exit;
	}
	
	/**
	 * 点击多选框 加载商品库存
	 */
	public function actionGetText()
	{
		$content = isset($_GET['content']) ? $_GET['content'] : '';
		$result_arr = array();
		if (!empty($content)){
			foreach ($content as $key => $value){
				$num = 0;
				foreach ($value as $k => $v){
					$tmp_arr = $result_arr;
					if (!empty($tmp_arr)){
						foreach ($tmp_arr as $k2 => $v2){
							foreach ($v as $k1 => $v1){
								if (!empty($v1) && !empty($v2)){
									$result_arr[$num++] = $v2.",".$v1;
								}
							}
						}
					}else {
						foreach ($v as $k1 => $v1){
							$result_arr[$num++] = $v1;
						}
					}
				}
			}
		}else {
		}
		$this->renderPartial('showForm', array('list' => $result_arr));
	}
	
	/**
	 * 下拉框获取商品规格模板
	 */
	public function ActionGetStandard()
	{
		$shop = new DShopC();
		$merchant_id = Yii::app()->session['merchant_id'];
		$standard_id = $_GET['standard_id'];
		$result = $shop->getSandard($standard_id);
		echo $result;
	}
	
	/**
	 * 保存规格模板
	 */
	public function actionAddStandard()
	{
		$name = $_GET['name'];
		$content = $_GET['content'];
		$save_type = $_GET['save_type'];
		$model_id = $_GET['model_id'];
		$merchant_id = Yii::app()->session['merchant_id'];
		
		$shop = new DShopC();
		$result = $shop->saveProStandard($name, $content, $save_type, $model_id, $merchant_id);
		echo $result;
	}
	
	/**
	 * 删除商品
	 */
	public function actionDelProduct()
	{
		$shop = new DShopC();
		$del_arr = array();
		if (!empty($_GET['pro_id'])){
			$id = $_GET['pro_id'];
			$del_arr[] = $id;
		}
		$merchant_id = Yii::app()->session['merchant_id'];
		
		$del_res = $shop->delProduct($merchant_id,$del_arr);
		$del_result = json_decode($del_res,true);
		if ($del_result['status'] == ERROR_NONE) {
			$this->redirect('productList');
		}
	}
	
	/**
	 * 批量删除商品
	 */
	public function actionDelMoreProduct()
	{
		$shop = new DShopC();
		$del_arr = array();
        $merchant_id = Yii::app()->session['merchant_id'];
		if (!empty($_GET['content'])) {
			$del_arr = $_GET['content'];
		}
		$merchant_id = Yii::app()->session['merchant_id'];
	
		$del_result = json_decode($shop->delProduct($merchant_id,$del_arr),true);
		echo json_encode($del_result['status']);
	}

    /**
     * 商品下架
     */
    public function actionUnderCarriage()
    {
        $shop = new DShopC();
        $uc_arr = array();
        $merchant_id = Yii::app()->session['merchant_id'];
        if (!empty($_GET['content'])) {
            $uc_arr = $_GET['content'];
        }
        $merchant_id = Yii::app()->session['merchant_id'];

        $del_result = json_decode($shop->UnderCarriage($merchant_id,$uc_arr),true);
        echo json_encode($del_result['status']);
    }
    
    /**
     * 商品上架
     */
    public function actionUpCarriage()
    {
        $shop = new DShopC();
        $uc_arr = array();
        $merchant_id = Yii::app()->session['merchant_id'];
        if (!empty($_GET['content'])) {
            $uc_arr = $_GET['content'];
        }
        $merchant_id = Yii::app()->session['merchant_id'];

        $del_result = json_decode($shop->UpCarriage($merchant_id,$uc_arr),true);
        echo json_encode($del_result['status']);
    }

    /**
     * 新建分组
     */
    public function actionAddGroup()
    {
        $merchant_id = Yii::app()->session['merchant_id'];
        $shop = new DShopC();
        if(!empty($_GET['groupname']))
        {
            $rs=json_decode($shop->addShopGroup($merchant_id,$_GET['groupname']),true);
            if($rs['status']==ERROR_NONE)
            {
                //保存成功查找全部的分组
                $result=$shop->getShopGroup($merchant_id);
                if($result['status']==ERROR_NONE)
                {
                    $data=array();
                    $data=$result['data'];
                    echo json_encode($data);
                }else {
                    echo json_encode('error');
                }
            }else if($rs['status']==ERROR_DUPLICATE_DATA){
                echo json_encode('duplicate');
            }else
            {
                echo json_encode('error');
            }
        }else
        {
            echo json_encode('error');
        }

    }
	
}