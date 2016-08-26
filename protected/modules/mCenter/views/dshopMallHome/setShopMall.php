

	<script type="text/javascript" src="<?php echo SYT_STATIC_JS?>artDialog/jquery.artDialog.js?skin=simple"></script>
	<script type="text/javascript" src="<?php echo SYT_STATIC_JS?>artDialog/plugins/iframeTools.js"></script>
    <script type="text/javascript" src="<?php echo GJ_STATIC_JS?>plus/jquery.dragsort-0.5.2.min.js"></script>
    <script type="text/javascript" src="<?php echo GJ_STATIC_JS?>plus/jquery.gridly.css"></script>
    <div class="kkfm_r_inner">
		<!--搜索框 count-top包含的部分-->
		<div class="top">
			<div class="name">商品首页</div>
		</div>
		<div class="shop_menu">
    		<ul>
        		<li class="cur"><a href="#">商城首页</a></li>
        <!-- 	<li><a href="<?php //echo Yii::app() -> createUrl('mCenter/ShopFreight/ShopFreightInfo');?>">运费模板</a></li> -->	
        	</ul>
    	</div>

        <div class="contant shop_indexEdit"><!--添加shop_edit-->

            <div class="body">
                <div class="shopEdit_l">
                    <div class="sLine"></div>
                    <div class="t">
                        <h1>商城首页</h1>
                    </div>
                    <div class="m">
                        <div  id="areaWrap">
                            <?php if(!empty($banner)){
                                $shop_search_i=0;
                                $img_nav_i=0;
                                $img_adv_i=0;
                                $shop_carousel_i=0;
                                $shop_group_i=0;
                                $qcord_i=0;
                                $catalog_i=0;
                                foreach($banner as $key=>$value){

                                    ?>
                                    <?php if($value['name']=='shop_search'){?>
                                        <!--搜索-->
                                        <div class="area bgWhite" id="shop_search<?php echo $shop_search_i?>">
                                            <div class="aContent">
                                                <div class="custom-search">
                                                    <form action="/">
                                                        <input type="text" class="txt" placeholder="商品搜索：请输入商品关键字">
                                                        <input type="submit" class="button" value="">
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="actions">
                                                <div class="actionbtn">
                                                    <a href="#" class="del" onclick="del_shop_search_Item(this,<?php echo $shop_search_i?>)">删除</a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php $shop_search_i++;}else if($value['name']=='img_nav'){
                                        //对url循环
                                        $custom=0;
                                        for($img_nav_j=0;$img_nav_j<count($value['url']);$img_nav_j++)
                                        {
                                            if($value['url'][$img_nav_j]!='null')
                                            {
                                                $custom++;
                                            }
                                        }
                                        if($custom==0)
                                            $custom=1;
                                        ?>
                                        <!--图片导航-->
                                        <div class="area bgWhite" id="img_nav<?php echo $img_nav_i?>">
                                            <div class="aContent">
                                                <ul class="custom-nav-<?php echo $custom?>">
                                                    <?php for($img_nav_j=0;$img_nav_j<count($value['url']);$img_nav_j++){?>
                                                        <?php if($value['url'][$img_nav_j]!='null'){?>
                                                            <li><img src="<?php echo IMG_GJ_LIST.$value['url'][$img_nav_j];?>"> </li>
                                                        <?php }?>
                                                    <?php }for($custom_i=0;$custom_i<4-$custom;$custom_i++){?>
                                                        <li><img src=""> </li>
                                                    <?php }?>
                                                </ul>
                                            </div>
                                            <div class="actions">
                                                <div class="actionbtn">
                                                    <!--                                            <a href="#" class="add">添加内容</a>-->
                                                    <a href="#" class="del" onclick="delItem(this,<?php echo $img_nav_i?>)">删除</a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php $img_nav_i++;}else if($value['name']=='img_adv'){?>
                                        <div class="area bgWhite" id="img_adv<?php echo $img_adv_i?>">
                                            <div class="aContent">
                                                <div class="custom-image-swiper">
                                                    <?php if($value['url'][0]!='null'){?>
                                                        <img src="<?php echo IMG_GJ_LIST.$value['url'][0];?>">
                                                    <?php }else{?>
                                                        <img src="">
                                                    <?php }?>
                                                </div>
                                            </div>
                                            <div class="actions">
                                                <div class="actionbtn">
                                                    <!--                                                <a href="#" class="add">添加内容</a>-->
                                                    <a href="#" class="del" onclick="del_img_adv_Item(this,<?php echo $img_adv_i?>)">删除</a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php $img_adv_i++;}else if($value['name']=='shop_carousel'){ ?>
                                        <div class="area bgWhite" id="shop_carousel<?php echo $shop_carousel_i?>">
                                            <div class="aContent">
                                                <div class="custom-image-swiper">
                                                    <?php for($carousel_i=0;$carousel_i<count($value['url']);$carousel_i++){
                                                        if($value['url'][$carousel_i]!='null'){
                                                            ?>
                                                            <img src="<?php echo IMG_GJ_LIST.$value['url'][$carousel_i];?>">
                                                            <?php break;}else{?>
                                                            <img src="">
                                                            <?php break;}?>
                                                    <?php }?>
                                                </div>
                                            </div>
                                            <div class="actions">
                                                <div class="actionbtn">
                                                    <!--                                            <a href="#" class="add">添加内容</a>-->
                                                    <a href="#" class="del" onclick="del_shop_carousel_Item(this,<?php echo $shop_carousel_i;?>)">删除</a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php $shop_carousel_i++;}else if($value['name']=='shop_group'){?>
                                        <!--商品分组-->
                                        <div class="area bgWhite" id="shop_group<?php echo $shop_group_i?>">
                                            <div class="aContent">
                                                <div class="custom-image-swiper">
                                                    <img src="<?php echo GJ_STATIC_IMAGES?>shop_group.png">
                                                </div>
                                                </div>
                                            <div class="actions">
                                                <div class="actionbtn">
<!--                                                                '<a href="#" class="add">添加内容</a>'+-->
                                                    <a href="javascript:;" class="del" onclick="del_shop_group_Item(this,<?php echo $shop_group_i?>)">删除</a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php $shop_group_i++;}else if($value['name']=='qcord'){?>
                                        <div class="area bgWhite" id="qcord<?php echo $qcord_i;?>">
                                            <div class="aContent">
                                                <div class="custom-contact cc-two clearfix" style="background: url(<?php if($value['url'][0]!='null'){echo IMG_GJ_LIST.$value['url'][0];}else{echo GJ_STATIC_IMAGES.'32_02.png';}?>) no-repeat 50% 50% / 100% 100%">
                                                    <div class="cc-li">
                                                        <img src="<?php if($value['url'][1]!='null'){echo IMG_GJ_LIST.$value['url'][1];}else{echo GJ_STATIC_IMAGES.'qcord_defalut.jpg';}?>">
                                                        <p><?php if($value['url'][2]!='null'){echo $value['url'][2];}?></p>
                                                    </div>
                                                    <div class="cc-li">
                                                        <img src="<?php echo GJ_STATIC_IMAGES;?>img_0350.png">
                                                        <p><?php if($value['url'][3]!='null'){echo $value['url'][3];}?></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="actions">
                                                <div class="actionbtn">
<!--                                                    <a href="#" class="add">添加内容</a>-->
                                                    <a href="javascript:;" class="del" onclick="qcord_del_Item(this,<?php echo $qcord_i;?>)">删除</a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php $qcord_i++;}else if($value['name']=='catalog'){?>
                                        <div class="area bgWhite" id="catalog<?php echo $catalog_i;?>">
                                            <div class="aContent">
                                                <ul class="custom-catalog">
                                                    <li>
                                                        <h2 id="left_first_title<?php echo $catalog_i;?>"><?php echo $value['url'][0][0];?> <em></em></h2>
                                                        <ul>
                                                            <?php for($catalog_j=0;$catalog_j<count($value['url'])-1;$catalog_j++){?>
                                                                <li><h2 class="left_secord_title<?php echo $catalog_j;?>"><?php echo $value['url'][$catalog_j+1][0];?></h2><em></em></li>
                                                            <?php }?>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="actions">
                                                <div class="actionbtn">
<!--                                                                <a href="#" class="add">添加内容</a>-->
                                                    <a href="javascript:;" class="del" onclick="catalog_del_Item(this,<?php echo $catalog_i;?>)">删除</a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php $catalog_i++;}?>
                                <?php }}?>
                            <?php //对图片导航进行处理
                            $imgnav_arrtojson=array();
                            $imgnav_href_arrtojson=array();
                            if(!empty($img_nav)) {
                                for ($imgnav_for_i = 0; $imgnav_for_i < count($img_nav); $imgnav_for_i++) {
                                    $imgnav_arrtojson[$imgnav_for_i][0] = $img_nav[$imgnav_for_i]['url'][0];
                                    $imgnav_arrtojson[$imgnav_for_i][1] = $img_nav[$imgnav_for_i]['url'][1];
                                    $imgnav_arrtojson[$imgnav_for_i][2] = $img_nav[$imgnav_for_i]['url'][2];
                                    $imgnav_arrtojson[$imgnav_for_i][3] = $img_nav[$imgnav_for_i]['url'][3];

                                    $imgnav_href_arrtojson[$imgnav_for_i][0] = $img_nav[$imgnav_for_i]['href'][0];
                                    $imgnav_href_arrtojson[$imgnav_for_i][1] = $img_nav[$imgnav_for_i]['href'][1];
                                    $imgnav_href_arrtojson[$imgnav_for_i][2] = $img_nav[$imgnav_for_i]['href'][2];
                                    $imgnav_href_arrtojson[$imgnav_for_i][3] = $img_nav[$imgnav_for_i]['href'][3];
                                }
                            }

                            //对轮播图进行处理
                            $shopcarousel_arrtojson=array();
                            $shopcarousel_href_arrtojson=array();
                            if(!empty($shop_carousel)) {
                                for ($shopcarousel_for_i = 0; $shopcarousel_for_i < count($shop_carousel); $shopcarousel_for_i++) {
                                    $shopcarousel_arrtojson[$shopcarousel_for_i][0] = $shop_carousel[$shopcarousel_for_i]['url'][0];
                                    $shopcarousel_arrtojson[$shopcarousel_for_i][1] = $shop_carousel[$shopcarousel_for_i]['url'][1];
                                    $shopcarousel_arrtojson[$shopcarousel_for_i][2] = $shop_carousel[$shopcarousel_for_i]['url'][2];
                                    $shopcarousel_arrtojson[$shopcarousel_for_i][3] = $shop_carousel[$shopcarousel_for_i]['url'][3];

                                    $shopcarousel_href_arrtojson[$shopcarousel_for_i][0] = $shop_carousel[$shopcarousel_for_i]['href'][0];
                                    $shopcarousel_href_arrtojson[$shopcarousel_for_i][1] = $shop_carousel[$shopcarousel_for_i]['href'][1];
                                    $shopcarousel_href_arrtojson[$shopcarousel_for_i][2] = $shop_carousel[$shopcarousel_for_i]['href'][2];
                                    $shopcarousel_href_arrtojson[$shopcarousel_for_i][3] = $shop_carousel[$shopcarousel_for_i]['href'][3];
                                }
                            }
                            //对图片广告进行处理
                            $imgadv_arrtojson=array();
                            $imgadv_href_arrtojson=array();
                            if(!empty($img_adv)) {
                            for($imgadv_for_i=0;$imgadv_for_i<count($img_adv);$imgadv_for_i++)
                            {
                                $imgadv_arrtojson[$imgadv_for_i][0]=$img_adv[$imgadv_for_i]['url'][0];
                                $imgadv_href_arrtojson[$imgadv_for_i][0]=$img_adv[$imgadv_for_i]['href'][0];
                            }
                            }

                            //对商品分组进行处理
                            $shopgroup_arrtojson=array();
                            $shopgroup_item_arrtojson=array();
                            if(!empty($shop_group))
                            {
                                for($shop_group_for_i=0;$shop_group_for_i<count($shop_group);$shop_group_for_i++)
                                {

                                    for($shop_group_for_j=0;$shop_group_for_j<count($shop_group[$shop_group_for_i]['href']);$shop_group_for_j++)
                                    {
                                        $shopgroup_arrtojson[$shop_group_for_i][$shop_group_for_j]=$shop_group[$shop_group_for_i]['href'][0];
                                        $shopgroup_item_arrtojson[$shop_group_for_i]=$shop_group_for_j;
                                    }
                                }
                            }

                            //对关注微信进行处理
                            $qcord_arrtojson=array();
                            if(!empty($qcord))
                            {
                                for($qcord_for_i=0;$qcord_for_i<count($qcord);$qcord_for_i++)
                                {
                                    $qcord_arrtojson[$qcord_for_i][0]=$qcord[$qcord_for_i]['url'][0];
                                    $qcord_arrtojson[$qcord_for_i][1]=$qcord[$qcord_for_i]['url'][1];
                                    $qcord_arrtojson[$qcord_for_i][2]=$qcord[$qcord_for_i]['url'][2];
                                    $qcord_arrtojson[$qcord_for_i][3]=$qcord[$qcord_for_i]['url'][3];
                                }
                            }

                            //目录链接
                            $catalog_arrtojson=array();
                            if(!empty($catalog))
                            {
                                for($catalog_for_i=0;$catalog_for_i<count($catalog);$catalog_for_i++){
                                    for($catalog_for_j=0;$catalog_for_j<count($catalog[$catalog_for_i]['url']);$catalog_for_j++)
                                    {
                                        $catalog_arrtojson[$catalog_for_i][$catalog_for_j][0]=$catalog[$catalog_for_i]['url'][$catalog_for_j][0];
                                        $catalog_arrtojson[$catalog_for_i][$catalog_for_j][1]=$catalog[$catalog_for_i]['url'][$catalog_for_j][1];
                                    }
                                }
                            }

                            $imgnav_json=json_encode($imgnav_arrtojson);
                            $imgnav_href_json=json_encode($imgnav_href_arrtojson);
                            $shopcarousel_json=json_encode($shopcarousel_arrtojson);
                            $shopcarousel_href_json=json_encode($shopcarousel_href_arrtojson);
                            $imgadv_json=json_encode($imgadv_arrtojson);
                            $imgadv_href_json=json_encode($imgadv_href_arrtojson);
                            $shopgroup_href_json=json_encode($shopgroup_arrtojson);
                            $shopgroup_item_json=json_encode($shopgroup_item_arrtojson);
                            $qcord_json=json_encode($qcord_arrtojson);
                            $catalog_json=json_encode($catalog_arrtojson);

                            $shopsearch_num=count($shop_search);
                            $shopcarousel_num=count($shop_carousel);
                            $imgadv_num=count($img_adv);
                            $imgnav_num=count($img_nav);
                            $shopgroup_num=count($shop_group);
                            $qcord_num=count($qcord);
                            $catalog_num=count($catalog);
                            ?>
                        </div>
                        <div class="areaAdd">
                            <h2>添加内容</h2>
                            <ul class="clearfix">
                                <li><a href="javascript:;" id="shop_search" onclick="shop_search(this,true)">商品搜索</a></li>
                                <li><a href="javascript:;" onclick="img_adv(this,true)">图片广告</a></li>
                                <li><a href="javascript:;" id="img_nav" onclick="img_nav(this,true)">图片导航</a></li>
                                <li><a href="javascript:;" onclick="shop_group(this,true)">商品分组</a></li>
                                <li><a href="javascript:;" onclick="shop_carousel(this,true)">轮播图片</a></li>
                                <li><a href="javascript:;" onclick="qcord(this,true)">关注微信</a></li>
                                <li><a href="javascript:;" onclick="catalog(this,true)">目录链接</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="b"></div>
                </div>


                <div class="shopEdit_r" id="all_area" style="display:none">
                    <!--图片导航编辑区-->
                    <div class="SER_block SREcustom-nav"  id="img_nav_edit" style="display:none ">
                        <div class="bcgItem clearfix" id="img_nav_add1" style="display: none">
                            <div class="htcon">
                                <img src="">
                                <div id="img_add1"><a href="javascript:;" class="addAD">添加图片</a></div>
                                <div id="img_reload1"  style="display:none"><a href="javascript:;" class="shadow" style="display:none" >重新上传</a></div>
                            </div>
                            <div class="text">
                                <dl class="inlineBlock">
                                    <dt class="inlineBlock">链接：</dt>
                                    <dd class="inlineBlock" style="display:none">
                                        <div class="bgBlue">
                                            商品 | 商品名称
                                            <a href="javascript:;" class="close" onclick="img_nav_href_del(this,'1')"></a>
                                        </div>
                                        <a href="javascript:;" onclick="img_nav_edit_href(this,'1')">修改</a>
                                    </dd>
                                    <dd class="inlineBlock">
                                        <a href="javascript:;" onclick="img_nav_add_href(this,'1')">添加链接</a>
                                    </dd>
                                </dl>
                            </div>
                            <a href="javascript:;" class="close" onclick="img_nav_del(this,'1')"></a>
                            <input style="display: none" value="1">
                        </div>
                        <div class="bcgItem clearfix" id="img_nav_add2" style="display: none">
                            <div class="htcon">
                                <img src="">
                                <div id="img_add2"><a href="javascript:;" class="addAD">添加图片</a></div>
                                <div id="img_reload2"  style="display:none"><a href="javascript:;" class="shadow" style="display:none" >重新上传</a></div>
                            </div>
                            <div class="text">
                                <dl class="inlineBlock">
                                    <dt class="inlineBlock">链接：</dt>
                                    <dd class="inlineBlock" style="display:none">
                                        <div class="bgBlue">
                                            商品 | 商品名称
                                            <a href="javascript:;" class="close" onclick="img_nav_href_del(this,'2')"></a>
                                        </div>
                                        <a href="javascript:;" onclick="img_nav_edit_href(this,'2')">修改</a>
                                    </dd>
                                    <dd class="inlineBlock">
                                        <a href="javascript:;" onclick="img_nav_add_href(this,'2')">添加链接</a>
                                    </dd>
                                </dl>
                            </div>
                            <a href="javascript:;" class="close" onclick="img_nav_del(this,'2')"></a>
                            <input style="display: none" value="2">
                        </div>
                        <div class="bcgItem clearfix" id="img_nav_add3" style="display: none">
                            <div class="htcon">
                                <img src="">
                                <div id="img_add3"><a href="javascript:;" class="addAD">添加图片</a></div>
                                <div id="img_reload3"  style="display:none"><a href="javascript:;" class="shadow" style="display:none" >重新上传</a></div>
                            </div>
                            <div class="text">
                                <dl class="inlineBlock">
                                    <dt class="inlineBlock">链接：</dt>
                                    <dd class="inlineBlock" style="display:none">
                                        <div class="bgBlue">
                                            商品 | 商品名称
                                            <a href="javascript:;" class="close" onclick="img_nav_href_del(this,'3')"></a>
                                        </div>
                                        <a href="javascript:;" onclick="img_nav_edit_href(this,'3')">修改</a>
                                    </dd>
                                    <dd class="inlineBlock">
                                        <a href="javascript:;" onclick="img_nav_add_href(this,'3')">添加链接</a>
                                    </dd>
                                </dl>
                            </div>
                            <a href="javascript:;" class="close" onclick="img_nav_del(this,'3')"></a>
                            <input style="display: none" value="3">
                        </div>
                        <div class="bcgItem clearfix" id="img_nav_add4" style="display: none">
                            <div class="htcon">
                                <img src="">
                                <div id="img_add4"><a href="javascript:;" class="addAD">添加图片</a></div>
                                <div id="img_reload4"  style="display:none"><a href="javascript:;" class="shadow" style="display:none" >重新上传</a></div>
                            </div>
                            <div class="text">
                                <dl class="inlineBlock">
                                    <dt class="inlineBlock">链接：</dt>
                                    <dd class="inlineBlock" style="display:none">
                                        <div class="bgBlue">
                                            商品 | 商品名称
                                            <a href="javascript:;" class="close" onclick="img_nav_href_del(this,'4')"></a>
                                        </div>
                                        <a href="javascript:;" onclick="img_nav_edit_href(this,'4')">修改</a>
                                    </dd>
                                    <dd class="inlineBlock">
                                        <a href="javascript:;" onclick="img_nav_add_href(this,'4')">添加链接</a>
                                    </dd>
                                </dl>
                            </div>
                            <a href="javascript:;" class="close" onclick="img_nav_del(this,'4')"></a>
                            <input style="display: none" value="4">
                        </div>
                        <div class="bcgItem clearfix">
                            <a href="javascript:;" class="addAD" id="add_adv">添加一个广告</a>
                        </div>
                    </div>
                    <!--图片导航编辑区-->

                    <!--图片广告-->
                    <div class="SER_block SREcustom-nav"  id="img_adv_edit" style="display:none ">
                        <div class="bcgItem clearfix" id="img_adv" style="display: none">
                            <div class="htcon">
                                <img src="">
                                <div id="img_adv_add"><a href="javascript:;" class="addAD">添加图片</a></div>
                                <div id="img_adv_reload"  style="display:none"><a href="#" class="shadow" style="display:none" >重新上传</a></div>
                            </div>
                            <div class="text">
                                <dl class="inlineBlock">
                                    <dt class="inlineBlock">链接：</dt>
                                    <dd class="inlineBlock" style="display:none">
                                        <div class="bgBlue">
                                            商品 | 商品名称
                                            <a href="javascript:;" class="close" onclick="img_adv_href_del(this)"></a>
                                        </div>
                                        <a href="javascript:;" onclick="img_adv_edit_href(this)">修改</a>
                                    </dd>
                                    <dd class="inlineBlock">
                                        <a href="javascript:;" onclick="img_adv_add_href(this)">添加链接</a>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <!--图片广告-->

                    <!--商品分组-->
                    <div class="SER_block SREcustom-nav"  id="shop_group_edit" style="display:none ">
                        <div id="shop_group_add">

                        </div>
                        <div class="bcgItem clearfix">
                            <a href="javascript:;" class="addAD" id="add_shop_group">添加一个分组</a>
                        </div>
                    </div>
                    <!--商品分组-->


                    <!--轮播图-->
                    <div class="SER_block SREcustom-nav"  id="shop_carousel_edit" style="display:none ">
                        <div class="bcgItem clearfix" id="shop_carousel_add1" style="display: none">
                            <div class="htcon">
                                <img src="">
                                <div id="shop_carousel_img_add1"><a href="javascript:;" class="addAD">添加图片</a></div>
                                <div id="shop_carousel_img_reload1"  style="display:none"><a href="#" class="shadow" style="display:none" >重新上传</a></div>
                            </div>
                            <div class="text">
                                <dl class="inlineBlock">
                                    <dt class="inlineBlock">链接：</dt>
                                    <dd class="inlineBlock" style="display:none">
                                        <div class="bgBlue">
                                            商品 | 商品名称
                                            <a href="javascript:;" class="close" onclick="shop_carousel_href_del(this,'1')"></a>
                                        </div>
                                        <a href="javascript:;" onclick="shop_carousel_edit_href(this,'1')">修改</a>
                                    </dd>
                                    <dd class="inlineBlock">
                                        <a href="javascript:;" onclick="shop_carousel_add_href(this,'1')">添加链接</a>
                                    </dd>
                                </dl>
                            </div>
                            <a href="javascript:;" class="close" onclick="shop_carousel_del(this,'1')"></a>
                            <input style="display: none" value="1">
                        </div>
                        <div class="bcgItem clearfix" id="shop_carousel_add2" style="display: none">
                            <div class="htcon">
                                <img src="">
                                <div id="shop_carousel_img_add2"><a href="javascript:;" class="addAD">添加图片</a></div>
                                <div id="shop_carousel_img_reload2"  style="display:none"><a href="#" class="shadow" style="display:none" >重新上传</a></div>
                            </div>
                            <div class="text">
                                <dl class="inlineBlock">
                                    <dt class="inlineBlock">链接：</dt>
                                    <dd class="inlineBlock" style="display:none">
                                        <div class="bgBlue">
                                            商品 | 商品名称
                                            <a href="javascript:;" class="close" onclick="shop_carousel_href_del(this,'2')"></a>
                                        </div>
                                        <a href="javascript:;" onclick="shop_carousel_edit_href(this,'2')">修改</a>
                                    </dd>
                                    <dd class="inlineBlock">
                                        <a href="javascript:;" onclick="shop_carousel_add_href(this,'2')">添加链接</a>
                                    </dd>
                                </dl>
                            </div>
                            <a href="javascript:;" class="close" onclick="shop_carousel_del(this,'2')"></a>
                            <input style="display: none" value="2">
                        </div>
                        <div class="bcgItem clearfix" id="shop_carousel_add3" style="display: none">
                            <div class="htcon">
                                <img src="">
                                <div id="shop_carousel_img_add3"><a href="javascript:;" class="addAD">添加图片</a></div>
                                <div id="shop_carousel_img_reload3"  style="display:none"><a href="#" class="shadow" style="display:none" >重新上传</a></div>
                            </div>
                            <div class="text">
                                <dl class="inlineBlock">
                                    <dt class="inlineBlock">链接：</dt>
                                    <dd class="inlineBlock" style="display:none">
                                        <div class="bgBlue">
                                            商品 | 商品名称
                                            <a href="javascript:;" class="close" onclick="shop_carousel_href_del(this,'3')"></a>
                                        </div>
                                        <a href="javascript:;" onclick="shop_carousel_edit_href(this,'3')">修改</a>
                                    </dd>
                                    <dd class="inlineBlock">
                                        <a href="javascript:;" onclick="shop_carousel_add_href(this,'3')">添加链接</a>
                                    </dd>
                                </dl>
                            </div>
                            <a href="javascript:;" class="close" onclick="shop_carousel_del(this,'3')"></a>
                            <input style="display: none" value="3">
                        </div>
                        <div class="bcgItem clearfix" id="shop_carousel_add4" style="display: none">
                            <div class="htcon">
                                <img src="">
                                <div id="shop_carousel_img_add4"><a href="javascript:;" class="addAD">添加图片</a></div>
                                <div id="shop_carousel_img_reload4"  style="display:none"><a href="#" class="shadow" style="display:none" >重新上传</a></div>
                            </div>
                            <div class="text">
                                <dl class="inlineBlock">
                                    <dt class="inlineBlock">链接：</dt>
                                    <dd class="inlineBlock" style="display:none">
                                        <div class="bgBlue">
                                            商品 | 商品名称
                                            <a href="javascript:;" class="close" onclick="shop_carousel_href_del(this,'4')"></a>
                                        </div>
                                        <a href="javascript:;" onclick="shop_carousel_edit_href(this,'4')">修改</a>
                                    </dd>
                                    <dd class="inlineBlock">
                                        <a href="javascript:;" onclick="shop_carousel_add_href(this,'4')">添加链接</a>
                                    </dd>
                                </dl>
                            </div>
                            <a href="javascript:;" class="close" onclick="shop_carousel_del(this,'4')"></a>
                            <input style="display: none" value="4">
                        </div>
                        <div class="bcgItem clearfix">
                            <a href="javascript:;" class="addAD" id="add_shop_carousel">添加一个轮播图</a>
                        </div>
                    </div>
                    <!--轮播图-->

                    <!--二维码-->
                    <div class="SER_block SRE-contact" id="qcord_edit" style="display:none">
                        <div class="bcgItem clearfix">
                            <div class="htcon">
                                <img src="">
                                <div id="qcord_bg_img_add"><a href="javascript:;" class="addAD">添加图片</a></div>
                                <div id="qcord_bg_img_reload"  style="display:none"><a href="javascript:;" class="shadow" style="display:none" >重新上传</a></div>
                            </div>
                            <div class="text">
                                <div class="remark">上传背景图，建议尺寸640*320</div>
                            </div>
<!--                            <a href="javascript:;" class="close"></a>-->
                        </div>

                        <div class="bcgItem clearfix">
                            <div class="htcon">
                                <img src="">
                                <div id="qcord_img_add"><a href="javascript:;" class="addAD">添加图片</a></div>
                                <div id="qcord_img_reload"  style="display:none"><a href="javascript:;" class="shadow" style="display:none" >重新上传</a></div>
                            </div>
                            <div class="text">
                                <input type="text" id="qcord_gz_txt" class="txt" maxlength="20" placeholder="关注微信">
                                <div class="remark">上传二维码，建议尺寸400*400</div>
                            </div>
                        </div>

                        <div class="bcgItem clearfix">
                            <div class="text">
                                <input type="text" id="qcord_num_txt" class="txt" maxlength="20" placeholder="">
                                <div class="remark">请正确填写电话号码或手机号!</div>
                            </div>
                        </div>
                    </div>
                    <!--二维码-->

                    <!--目录链接-->
                    <div class="SER_block SRE-catalog" id="catalog_edit" style="display:none">
                        <div class="bcgItem clearfix">
                            <input type="text" class="txt" id="first_title" value="一级标题" placeholder="一级标题">
<!--                            <a href="#" class="close"></a>-->
                        </div>
                        <div id="catalog-wrap">
                            <div class="catalog-list">
                                <div class="bcgItem clearfix">
                                    <input type="text" class="txt" value="二级标题" placeholder="二级标题">
                                    <input class="catalog_count_title" value="0" style="display: none">
                                    <a href="javascript:;" onclick="catalog_title_del(this,0)" class="close"></a>
                                </div>
                            </div>
                        </div>
                        <!--文本编辑器位置,宽度：505px-->
                        <div class="arrow"> </div>
                        <textarea id="catelog_textarea"></textarea>
                        <div class="bcgItem clearfix">
                            <a href="javascript:;" class="addAD" id="add_catalog">添加一个二级标题</a>
                        </div>
                    </div>
                    <!--目录链接-->

                    <!-- 右边添加内容-->
                    <div class="SER_block" id="add_area" style="display:none ">
                        <div class="areaAdd">
                            <h2>添加内容</h2>
                            <ul class="clearfix">
                                <li><a href="javascript:void(0);">商品搜索</a></li>
                                <li><a href="#">图片广告</a></li>
                                <li><a href="#">图片导航</a></li>
                                <li><a href="#">商品分组</a></li>
                                <li><a href="#">关注微信</a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- 右边添加内容-->

                    <div class="arrow"></div>
                    <div class="btn">
                        <input type="submit" class="btn_com_blue" id="img_nav_btn" style="display:none" value="保 存">
                    </div>
                </div>

            </div>
        </div>
	</div>
<?php //var_dump($shopgroup_item_arrtojson);?>


<script type="text/javascript">
    var ue=UE.getEditor('catelog_textarea',{
        initialFrameWidth:525, //初始化宽度
        initialFrameHeight:185
    });
    /**
     * 图片导航
     **/
    //图片导航添加数量
    var img_nav_add_num=0;
    //图片导航数量
    var img_nav_num=<?php echo $imgnav_num?>;
//    var img_nav_num=0;
    //当前显示在右侧的id
    var showing_right_id='';
    //当前选中的左边的id
    var select_left_id='';
    //存放图片导航添加的图片
    var img_nav_arr=<?php echo $imgnav_json?>;
//    var img_nav_arr=new Array();
//    //存放图片导航添加的链接
//    var img_nav_href_arr=<?php //echo $img_nav_href_json?>//;
    var img_nav_href_arr=<?php echo $imgnav_href_json?>;
    /**
     * 商品搜素
     **/
    //商品搜索个数
    var shop_search_num=<?php echo $shopsearch_num?>;
//    var shop_search_num=0;
    /**
     * 图片广告
     */
    //图片广告数量
    var img_adv_num=<?php echo $imgadv_num?>;
    var img_adv_arr=<?php echo $imgadv_json?>;//图片
    var img_adv_href_arr=<?php echo $imgadv_href_json?>;//链接

//    var img_adv_num=0;
//    var img_adv_arr=new Array();
//    var img_adv_href_arr=new Array();

    /**
     * 轮播图
     */
//    var shop_carousel_img_add_num=0;
    var shop_carousel_add_num=<?php echo $shopcarousel_num?>;
    var shop_carousel_img_arr=<?php echo $shopcarousel_json?>;//图片
    var shop_carousel_href_arr=<?php echo $shopcarousel_href_json?>;//链接
//    var shop_carousel_add_num=0;
//    var shop_carousel_img_arr=new Array();
//    var shop_carousel_href_arr=new Array();


    /**
     * 商品分组添加
     */
    var shop_group_num=<?php echo $shopgroup_num?>;
    var shop_group_item_num=<?php echo $shopgroup_item_json?>;
    var shop_group_href_arr=<?php echo $shopgroup_href_json?>;

    /**
     * 关注微信添加
     */
    var qcord_num=<?php echo $qcord_num;?>;
    var qcord_arr=<?php echo $qcord_json;?>;

    /**
     * 目录链接
     */
    var catalog_num=<?php echo $catalog_num;?>;
    var catalog_arr=<?php echo $catalog_json;?>;
    var catalog_title_select=-1;//目前点击的二级标题

    $(document).ready(function(){
        window.parent.autoResize('main');

    });

    function reload()
    {
        window.parent.autoResize('main');
        $("#areaWrap").dragsort("destory");
    }

    $(function() {
        //拖放功能
        $("#areaWrap").dragsort("destory");
    });

    //点击 编辑按钮显示
    $(document).on('click','#areaWrap .area',function(){
        $("#areaWrap .area").removeClass("editing");
        $(this).addClass("editing");
    });

//商品区弹出框
function openDialog(){
    art.dialog.open(
        '<?php echo Yii::app()->createUrl('mCenter/DshopMallHome/shopDialogBody');?>?type='+'add',
        {
            title: '',
            lock: true,
            drag:true,
            width: '766px',
//            height: '335px',
            id: 'dialog_index',
            background: '#fff'
        }
    );
}

function editGroup(num)
{
    art.dialog.open(
        '<?php echo Yii::app()->createUrl('mCenter/DshopMallHome/ShopDialogBody');?>'+'?num='+num+'&type='+'edit'+'&edit_num='+num,
        {
            title: '',
            lock: true,
            drag:true,
            width: '766px',
//            height: '335px',
            id: 'dialog_index',
            background: '#fff'
        }
    );
}

    //图片导航添加链接
    function img_nav_add_href(obj,num)
    {
        var flag = 2; //定义flag=2代表url添加操作
        var type='img_nav';
        var top=$("#all_area").offset().top-200;
        art.dialog.open(
            '<?php echo Yii::app()->createUrl('mCenter/DshopMallHome/dialogHeader');?>'+'?index='+num+'&flag='+flag+'&type='+type+'&shop_group=false',
            {
                title: '',
//                lock: true,
                drag:true,
//                follow:"#img_nav_btn",
                width: '785px',
//                height: '335px',
                top:top,
                id: 'dialog_notice',
                background: '#fff'
            }
        );
    }

    //添加图片导航链接
    function addHeaderUrl(id,shop_type,name,index,type)
    {
        if(type=='img_nav')
        {
            //如果是图片导航的
            var shop_type_name = '';
            if(shop_type == <?php echo SHOP_TYPE_GROUP ?>){
                shop_type_name = '商品分组';
            }else{
                shop_type_name = '商品';
            }
            //添加连接到数组
            var num=select_left_id.substring(7,select_left_id.length);
            img_nav_href_arr[num][index-1]=id+';'+name+';'+shop_type;//id和分组名用分号隔开
            var img_nav_html='<a href="javascript:;" class="close" onclick="img_nav_href_del(this,'+index+')"></a>';
            $('#img_nav_add'+index).find('dd.inlineBlock').find('.bgBlue').html('<span>'+shop_type_name+'|'+name+'</span>'+img_nav_html);
            $('#img_nav_add'+index).find('dd.inlineBlock').show();
            $('#img_nav_add'+index).find('dd.inlineBlock').next().hide();
        }
        else if(type=='img_adv')
        {
            //如果是图片广告
            var adv_shop_type_name = '';
            if(shop_type == <?php echo SHOP_TYPE_GROUP ?>){
                adv_shop_type_name = '商品分组';
            }else{
                adv_shop_type_name = '商品';
            }
            //添加链接到数组
            var adv_num=select_left_id.substring(7,select_left_id.length);
            img_adv_href_arr[adv_num][index]=id+';'+name+';'+shop_type;//id和分组名用分号隔开
            var img_adv_html='<a href="javascript:;" class="close" onclick="img_adv_href_del(this)"></a>';
            $('#img_adv').find('.bgBlue').html('<span>'+adv_shop_type_name+"|"+name+'</span>'+img_adv_html);
            $('#img_adv').find('dt.inlineBlock').next().show();
            $('#img_adv').find('dt.inlineBlock').next().next().hide();
        }
        else if(type=='shop_carousel')
        {
            //轮播图
            var shop_carousel_type_name = '';
            if(shop_type == <?php echo SHOP_TYPE_GROUP ?>){
                shop_carousel_type_name = '商品分组';
            }else{
                shop_carousel_type_name = '商品';
            }
            //添加连接到数组
            var shop_carousel_num=select_left_id.substring(13,select_left_id.length);
            shop_carousel_href_arr[shop_carousel_num][index-1]=id+';'+name+';'+shop_type;//id和分组名用分号隔开
            var shop_carousel_html='<a href="javascript:;" class="close" onclick="shop_carousel_href_del(this,'+index+')"></a>';
            $('#shop_carousel_add'+index).find('dd.inlineBlock').find('.bgBlue').html('<span>'+shop_carousel_type_name+'|'+name+'</span>'+shop_carousel_html);
            $('#shop_carousel_add'+index).find('dd.inlineBlock').show();
            $('#shop_carousel_add'+index).find('dd.inlineBlock').next().hide();
        }
        else if(type='shop_group')
        {
            //商品分组
            var shop_group_type_name = '';
            if(shop_type == <?php echo SHOP_TYPE_GROUP ?>){
                shop_group_type_name = '商品分组';
            }else{
                shop_group_type_name = '商品';
            }
            //添加连接到数组
            var shop_group_num=select_left_id.substring(10,select_left_id.length);
            shop_group_href_arr[shop_group_num][index]=id+';'+name+';'+shop_type;//id和分组名用分号隔开
            var shop_group_html='<a href="javascript:;" class="close" onclick="shop_group_href_del(this,'+index+')"></a>';
            $('#shop_group_item_add'+index).find('dd.inlineBlock').find('.bgBlue').html('<span>'+shop_group_type_name+'|'+name+'</span>'+shop_group_html);
            $('#shop_group_item_add'+index).find('dd.inlineBlock').show();
            $('#shop_group_item_add'+index).find('dd.inlineBlock').next().hide();
        }

        art.dialog.get("dialog_notice").close(); //关闭弹出框
    }

    //删除图片导航链接
    function img_nav_href_del(obj,index)
    {
        if(!confirm('确认要删除吗?'))
            return false;

        //去掉静态页面上的文字
        $(obj).parent().parent().hide();
        $(obj).parent().parent().next().show();
        //删除图片导航链接对应的数组
        var num=select_left_id.substring(7,select_left_id.length);
        img_nav_href_arr[num][index-1]='null';//id和分组名用分号隔开
    }

    //图片导航编辑链接
    function img_nav_edit_href(obj,num)
    {
        var flag = 1; //定义flag=2代表url添加操作
        var type='img_nav';
        var top=$("#all_area").offset().top-200;
        art.dialog.open(
            '<?php echo Yii::app()->createUrl('mCenter/DshopMallHome/dialogHeader');?>'+'?index='+num+'&flag='+flag+'&type='+type+'&shop_group=false',
            {
                title: '',
//                lock: true,
                drag:true,
                width: '785px',
//                height: '335px',
                top:top,
                id: 'dialog_notice',
                background: '#fff'
            }
        );
    }

    //编辑图片导航链接
    function editHeaderUrl(id,shop_type,name,index,type)
    {
        if(type=='img_nav')
        {
            //如果是图片导航的
            var shop_type_name = '';
            if(shop_type == <?php echo SHOP_TYPE_GROUP ?>){
                shop_type_name = '商品分组';
            }else{
                shop_type_name = '商品';
            }
            //修改连接到数组
            var num=select_left_id.substring(7,select_left_id.length);
            img_nav_href_arr[num][index-1]=id+';'+name+';'+shop_type;//id和分组名用分号隔开
            var img_nav_html='<a href="javascript:;" class="close" onclick="img_nav_href_del(this,'+index+')"></a>';
            $('#img_nav_add'+index).find('dd.inlineBlock').find('.bgBlue').html('<span>'+shop_type_name+'|'+name+'</span>'+img_nav_html);
            $('#img_nav_add'+index).find('dd.inlineBlock').show();
            $('#img_nav_add'+index).find('dd.inlineBlock').next().hide();
        }
        else if(type=='img_adv')
        {
            //图片广告
            var adv_shop_type_name = '';
            if(shop_type == <?php echo SHOP_TYPE_GROUP ?>){
                adv_shop_type_name = '商品分组';
            }else{
                adv_shop_type_name = '商品';
            }
            //修改连接到数组
            var adv_num=select_left_id.substring(7,select_left_id.length);
            img_adv_href_arr[adv_num][0]=id+';'+name+';'+shop_type;//id和分组名用分号隔开
            var img_adv_html='<a href="javascript:;" class="close" onclick="img_adv_href_del(this)"></a>';
            $('#img_adv').find('.bgBlue').html('<span>'+adv_shop_type_name+"|"+name+'</span>'+img_adv_html);
            $('#img_adv').find('dt.inlineBlock').next().show();
            $('#img_adv').find('dt.inlineBlock').next().next().hide();
        }
        else if(type=='shop_carousel')
        {
            //轮播图
            var shop_carousel_type_name = '';
            if(shop_type == <?php echo SHOP_TYPE_GROUP ?>){
                shop_carousel_type_name = '商品分组';
            }else{
                shop_carousel_type_name = '商品';
            }
            //添加连接到数组
            var shop_carousel_num=select_left_id.substring(13,select_left_id.length);
            shop_carousel_href_arr[shop_carousel_num][index-1]=id+';'+name+';'+shop_type;//id和分组名用分号隔开
            var shop_carousel_html='<a href="javascript:;" class="close" onclick="shop_carousel_href_del(this,'+index+')"></a>';
            $('#shop_carousel_add'+index).find('dd.inlineBlock').find('.bgBlue').html('<span>'+shop_carousel_type_name+'|'+name+'</span>'+shop_carousel_html);
            $('#shop_carousel_add'+index).find('dd.inlineBlock').show();
            $('#shop_carousel_add'+index).find('dd.inlineBlock').next().hide();
        }
        else if(type=='shop_group')
        {
            var shop_group_type_name = '';
            if(shop_type == <?php echo SHOP_TYPE_GROUP ?>){
                shop_group_type_name = '商品分组';
            }else{
                shop_group_type_name = '商品';
            }
            //添加连接到数组
            var shop_group_num=select_left_id.substring(10,select_left_id.length);
            shop_group_href_arr[shop_group_num][index]=id+';'+name+';'+shop_type;//id和分组名用分号隔开
            var shop_group_html='<a href="javascript:;" class="close" onclick="shop_group_href_del(this,'+index+')"></a>';
            $('#shop_group_item_add'+index).find('dd.inlineBlock').find('.bgBlue').html('<span>'+shop_group_type_name+'|'+name+'</span>'+shop_group_html);
            $('#shop_group_item_add'+index).find('dd.inlineBlock').show();
            $('#shop_group_item_add'+index).find('dd.inlineBlock').next().hide();
        }
        art.dialog.get("dialog_notice").close(); //关闭弹出框
    }

    //删除一个内容
    function delItem(obj,index)
    {
        if(!confirm('确定要删除吗?'))
            return false;
        $(obj).parent().parent().parent().remove();
        if($(obj).next().val()=='img_nav') {
            //清除数组中对应的内容
            for (var img_nav_i = 0; img_nav_i < 4; img_nav_i++)
            {
                img_nav_arr[index][img_nav_i]='null';
                img_nav_href_arr[index][img_nav_i]='null';
            }
            //清空右边静态页面
            $('#img_nav_edit').find('img').attr('src','');
            $('#img_nav_edit').find('dt.inlineBlock').next().hide();
            $('#img_nav_edit').find('dt.inlineBlock').next().next().show();
            var img_nav_num=0;
            $('#img_nav_edit').find('.bgBlue').each(function(){
                var img_nav_html='<a href="javascript:;" class="close" onclick="img_nav_href_del(this,'+img_nav_num+')"></a>';
                $(this).html(img_nav_html);
                img_nav_num++;
            });
            $('#img_nav_edit').hide();
            $('#img_nav_btn').hide();
            $('#all_area').hide();
            img_nav_num--;
        }
        //删除增加的高度
//        $("#areaWrap").dragsort("destory");
        window.parent.autoResize('main',$('body').height()-30);
    }

    //添加一个广告
    $(document).on('click','#add_adv',function(){
        window.parent.autoResize('m');
        var body_height=$('body').height();
        body_height=body_height+1500;
        window.parent.autoResize('main',body_height);
        var count=0;
        $('#img_nav_edit .htcon').each(function(){
            if($(this).parent().is(':visible'))
            {
                count++;
            }
        });
        if(count==4)
        {
            alert('最多添加四张图片');
            return false;
        }
        var num=0;
        $('#img_nav_edit .htcon').each(function(){
            num++;
            if($(this).parent().is(':hidden'))
            {
                $(this).parent().show();
                return false;
            }
        });
        //改变左边的样式
        $('#'+select_left_id).find('ul').attr('class','custom-nav-'+(count+1));
        $('#img_add'+num).uploadify({
            onInit: function () {$(".uploadify-queue").hide();},//载入时触发，将flash设置到最小
            uploader: '<?php echo UPLOAD_TO_PATH?>',// 服务器处理地址
            swf: '<?php echo GJ_STATIC_JS?>'+'uploadify/uploadify.swf',
            buttonText: "点击上传",//按钮文字
            height: 70,  //按钮高度	
            width: 140, //按钮宽度
            fileTypeExts: "<?php echo UPLOAD_IMG_TYPE;?>",//允许的文件类型
            fileTypeDesc: "请选择图片文件", //文件说明
            formData: { 'folder' : '<?php echo(IMG_GJ_FOLDER)?>', 'thumb' : '<?php echo IMG_GJ_THUMB_NAV?>'}, //提交给服务器端的参数
            onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
                eval("var jsondata = " + data + ";");
                var key = jsondata['key'];
                var fileName = jsondata['fileName'];

                $('#img_add'+num).prev().attr('src','<?php echo IMG_GJ_LIST?>'+fileName);
                $('#img_add'+num).parent().find('.shadow').show();//显示重新上传
                $('#img_add'+num).hide();
                $('#img_reload' + num).show();
                $('#img_reload' + num).uploadify({
                    onInit: function () {
                        $(".uploadify-queue").hide();
                    },//载入时触发，将flash设置到最小
                    uploader: '<?php echo UPLOAD_TO_PATH?>',// 服务器处理地址
                    swf: '<?php echo GJ_STATIC_JS?>' + 'uploadify/uploadify.swf',
                    buttonText: "重新上传",//按钮文字
                    buttonClass: 'shadow',
                    height: 30,  //按钮高度
                    width: 140, //按钮宽度
                    fileTypeExts: "<?php echo UPLOAD_IMG_TYPE;?>",//允许的文件类型
                    fileTypeDesc: "请选择图片文件", //文件说明
                    formData: {'folder': '<?php echo(IMG_GJ_FOLDER)?>', 'thumb' : '<?php echo IMG_GJ_THUMB_NAV?>'}, //提交给服务器端的参数
                    onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
                        eval("var jsondata = " + data + ";");
                        var key = jsondata['key'];
                        var fileName = jsondata['fileName'];
                        
                        $('#img_add' + num).prev().attr('src', '<?php echo IMG_GJ_LIST?>' + fileName);
                        $('#img_add' + num).parent().find('.shadow').show();//显示重新上传
                        $('#img_reload' + num).show();
                        //将图片添加到左边
                        $('#' + select_left_id).find('li').eq(num - 1).show();
                        $('#' + select_left_id).find('li').eq(num - 1).find('img').attr('src', '<?php echo IMG_GJ_LIST?>' + fileName);
                        //将图片路径添加到数组里面
                        var nav_num = select_left_id.substring(7, select_left_id.length);
                        img_nav_arr[nav_num][num - 1] = fileName;
                        $('#img_reload' + num).addClass('shadow');                        
                    }
                });
                $('#img_reload'+num).addClass('shadow');
                //将图片添加到左边
                $('#' + select_left_id).find('li').eq(num - 1).show();
                $('#'+select_left_id).find('li').eq(num-1).find('img').attr('src','<?php echo IMG_GJ_LIST?>'+fileName);
                //将图片路径添加到数组里面
                var nav_num=select_left_id.substring(7,select_left_id.length);
                img_nav_arr[nav_num][num-1]=fileName;
            }
        });
        var all_area_top=$('#all_area').offset().top+200;
        var all_area_top_int=parseInt(all_area_top.substring(0,all_area_top.length));
        //$('#all_area').css('top',(all_area_top_int-115)+'px');
        //添加图片加一
        img_nav_add_num++;     
        window.parent.autoResize('m');
        var body_height=$('body').height();
        body_height=body_height+1500;
        window.parent.autoResize('main',body_height);
    });

    //图片导航删除一个广告
    function img_nav_del(obj,index)
    {
        if(!confirm('确认要删除吗?'))
            return false;
        //判断当前要删除的是第几行
        var row=$(obj).next().val();
        //将右边图片地址清除
        $(obj).parent().find('img').attr('src','');
        //将左边的图片地址清除
        $('#'+select_left_id).find('li').eq(row-1).find('img').attr('src','');
//         $('#'+select_left_id).find('li').eq(row-1).hide();
        //将数组中的图片地址清除
        var nav_num=select_left_id.substring(7,select_left_id.length);
        img_nav_arr[nav_num][row-1]='null';
        img_nav_href_arr[nav_num][row-1]='null';
        //将分组链接文字删除
        var href_html='<a href="javascript:;" class="close" onclick="img_nav_href_del(this,'+index+')"></a>';
        $('#img_nav_add'+row).find('.bgBlue').html(href_html);
        //隐藏链接，显示添加链接
        $(obj).parent().find('dt.inlineBlock').next().hide();
        $(obj).parent().find('dt.inlineBlock').next().next().show();
        //判断有几行是显示的
        var count=0;
        $('#img_nav_edit .htcon').each(function(){
            if($(this).parent().is(':visible'))
            {
                count++;
            }
        });
        //左边对应导航样式修改
        if(count!=1)
            count=count-1;
        $('#'+select_left_id).find('ul').attr('class','custom-nav-'+(count));
        //删除一个图片导航
        $(obj).parent().hide();
        //将重新上传隐藏起来
        $('#img_reload'+row).find('a').hide();
        //如果是删除中间的广告条，对数组进行挪动，左边的图片进行挪位,
        //先循环遍历右边四个广告条，将数组输出到广告条上，数组为'null'
        //就对这个广告条至空，左边也一样
        //数组往前走一位
        for(var n=row;n<4;n++)
        {
            //图片链接往前走一位
//             if(img_nav_arr[nav_num][n]=='null'&&n!=3)
//             {
//                 //如果图片链接为空
//                 for(var m=n;m<4;m++)
//                 {
                    //后续的数组往前走一位
                    if(n!=3)
                        img_nav_arr[nav_num][n]=img_nav_arr[nav_num][n+1];
                    else
                        img_nav_arr[nav_num][n]='null';
//                 }

//             }

            //分组链接往前走一位
//             if(img_nav_href_arr[nav_num][n]=='null'&&n!=3)
//             {
//                 //如果图片链接为空
//                 for(var k=n;k<4;k++)
//                 {
                    //后续的数组往前走一位
                    if(n!=3)
                        img_nav_href_arr[nav_num][n]=img_nav_href_arr[nav_num][n+1];
                    else
                        img_nav_href_arr[nav_num][n]='null';
//                 }
//             }
        }
        var nav=1;//当前需要编辑的广告条
        for(var i=0;i<4;i++)
        {
            var flag=false;//防止同时操作
            if(img_nav_arr[nav_num][i]!='null'&&img_nav_arr[nav_num][i]!=undefined)
            {
                //右边处理
                $('#img_nav_add'+nav).show();
                $('#img_nav_add'+nav).find('img').attr('src','<?php echo IMG_GJ_LIST?>'+img_nav_arr[nav_num][i]);
                $('#img_reload'+nav).find('a').show();
                //左边处理
                $('#'+select_left_id).find('li').eq(nav-1).find('img').attr('src','<?php echo IMG_GJ_LIST?>'+img_nav_arr[nav_num][i]);

                flag=true;
            }else{
                //隐藏li标签
            	$('#'+select_left_id).find('li').eq(nav-1).hide();
           	}
            if(img_nav_href_arr[nav_num][i]!='null'&&img_nav_href_arr[nav_num-1][i]!=undefined)
            {
                $('#img_nav_add'+nav).show();
                var str=img_nav_href_arr[nav_num][i].split(';');
                var right_html='<a href="javascript:;" class="close" onclick="img_nav_href_del(this,'+nav+')"></a>';
                var shop_type_name='';
                if(str[2] == <?php echo SHOP_TYPE_GROUP ?>){
                    shop_type_name = '商品分组';
                }else{
                    shop_type_name = '商品';
                }
                $('#img_nav_add'+nav).find('.bgBlue').html(shop_type_name+'|'+str[1]+right_html);
                $('#img_nav_add'+nav).find('.bgBlue').parent().show();
                $('#img_nav_add'+nav).find('.bgBlue').parent().next().hide();

                flag=true;
            }

            if(flag)
                nav++;
        }
//        //对右边进行删除
        $('#img_nav_add'+nav).hide();
        $('#img_nav_add'+nav).find('img').attr('src','');
        $('#img_reload'+nav).hide();
        var href_html_del='<a href="javascript:;" class="close" onclick="img_nav_href_del(this,'+nav+')"></a>';
        $('#img_nav_add'+nav).find('.bgBlue').html(href_html_del);
        $('#img_nav_add'+nav).find('dt.inlineBlock').next().hide();
        $('#img_nav_add'+nav).find('dt.inlineBlock').next().next().show();
        //对左边进行删除
        $('#'+select_left_id).find('li').eq(nav-1).find('img').attr('src','');
        //添加图片减一
//        img_nav_add_num--;
    }

    //点击添加内容
    function add_area(obj)
    {
        $('#'+showing_right_id).hide();
        //设置当前左边选中的id
        select_left_id=$(obj).parent().parent().parent().attr('id');
        //隐藏右边的
        $('.SER_block').hide();
        $('#add_area').show();
        //隐藏保存按钮
        $('#img_nav_btn').hide();
        return true;
    }


    //单个内容点击事件
    $(document).on('click','#areaWrap .area',function(){
        //隐藏右边
        $('.SER_block').hide();
        $('#'+showing_right_id).hide();
        select_left_id=$(this).attr('id');
        if(select_left_id.indexOf('img_nav')>-1)
        {

            //是图片导航
            $('#img_nav_edit').show();
            showing_right_id='img_nav_edit';
            //重置右边
            $('#'+showing_right_id).find('.bcgItem').find('img').attr('src','');
            $('#'+showing_right_id).find('.shadow').hide();
            var img_num=1;
            $('#'+showing_right_id).find('.bgBlue').each(function(){
                $(this).parent().hide();
                $(this).parent().next().show();
                var img_html='<a href="javascript:;" class="close" onclick="img_nav_href_del(this,'+img_num+')"></a>';
                $(this).html(img_html);
                img_num++;
            });
            $('#'+showing_right_id).find('dt.inlineBlock').next().hide();
            $('#'+showing_right_id).find('dt.inlineBlock').next().next().show();
            //重置左边
//            $('#'+select_left_id).find('li').each(function(){
//                $(this).find('img').attr('src','');
//            });
            var row=select_left_id.substring(7,select_left_id.length);
//            alert(img_nav_arr[row]);
//            alert(img_nav_href_arr[row]);
            $('#img_nav_edit').find('.htcon').each(function(){
                var img_flag=false;
                var text_flag=false;
                var right_num=$(this).next().next().next().val();
                if(img_nav_arr[row][right_num-1]!='null') {
                    //根据数组设置左边
                    $('#'+select_left_id).find('li').eq(right_num-1).find('img').attr('src','<?php echo IMG_GJ_LIST?>'+img_nav_arr[row][right_num-1]);
                    //根据数组设置右边
                    $('#img_nav_add' + right_num).find('img').attr('src', '<?php echo IMG_GJ_LIST?>' + img_nav_arr[row][right_num-1]);
                    //图片不为空，显示重新上传
                    $('#img_add' + right_num).parent().find('.shadow').show();//显示重新上传
                    if($('#img_reload'+right_num).css("display")=="none") {
                        $('#img_reload' + right_num).show();
                        $('#img_reload' + right_num).uploadify({
                            onInit: function () {
                                $(".uploadify-queue").hide();
                            },//载入时触发，将flash设置到最小
                            uploader: '<?php echo UPLOAD_TO_PATH?>',// 服务器处理地址
                            swf: '<?php echo GJ_STATIC_JS?>' + 'uploadify/uploadify.swf',
                            buttonText: "重新上传",//按钮文字
                            buttonClass: 'shadow',
                            height: 30,  //按钮高度
                            width: 140, //按钮宽度
                            fileTypeExts: "<?php echo UPLOAD_IMG_TYPE;?>",//允许的文件类型
                            fileTypeDesc: "请选择图片文件", //文件说明
                            formData: {'folder': '<?php echo(IMG_GJ_FOLDER)?>', 'thumb' : '<?php echo IMG_GJ_THUMB_NAV?>'}, //提交给服务器端的参数
                            onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
                                eval("var jsondata = " + data + ";");
                                var key = jsondata['key'];
                                var fileName = jsondata['fileName'];
                                $('#img_add' + right_num).prev().attr('src', '<?php echo IMG_GJ_LIST?>' + fileName);
                                $('#img_add' + right_num).parent().find('.shadow').show();//显示重新上传
                                $('#img_reload' + right_num).show();
                                //将图片添加到左边
                                $('#' + select_left_id).find('li').eq(right_num-1).find('img').attr('src', '<?php echo IMG_GJ_LIST?>' + fileName);
                                //将图片路径添加到数组里面
                                var nav_num = select_left_id.substring(7, select_left_id.length);
                                img_nav_arr[nav_num][right_num-1] = fileName;
                                $('#img_reload' + right_num).addClass('shadow');
                            }
                        });
                        $('#img_reload' + right_num).addClass('shadow');
                    }
                }
                else
                {
                    img_flag=true;
                }
                var img_href_url='<a href="javascript:;" class="close" onclick="img_nav_href_del(this,'+right_num+')"></a>';
                var str=img_nav_href_arr[row][right_num-1].split(';');
                var shop_type_name='';
                if(str[2] == <?php echo SHOP_TYPE_GROUP ?>){
                    shop_type_name = '商品分组';
                }else{
                    shop_type_name = '商品';
                }
                if(img_nav_href_arr[row][right_num-1]!='null') {
                    $('#img_nav_add' + right_num).find('.bgBlue').html(shop_type_name + '|' + str[1] + img_href_url);
                    //如果图片为空，链接不为空，需要给图片添加上传图片控件
                    if($('#img_add'+right_num).find('a')&&img_flag)
                    {
                        $('#img_add'+right_num).uploadify({
                            onInit: function () {$(".uploadify-queue").hide();},//载入时触发，将flash设置到最小
                            uploader: '<?php echo UPLOAD_TO_PATH?>',// 服务器处理地址
                            swf: '<?php echo GJ_STATIC_JS?>'+'uploadify/uploadify.swf',
                            buttonText: "点击上传",//按钮文字
                            height: 70,  //按钮高度
                            width: 140, //按钮宽度
                            fileTypeExts: "<?php echo UPLOAD_IMG_TYPE;?>",//允许的文件类型
                            fileTypeDesc: "请选择图片文件", //文件说明
                            formData: { 'folder' : '<?php echo(IMG_GJ_FOLDER)?>', 'thumb' : '<?php echo IMG_GJ_THUMB_NAV?>'}, //提交给服务器端的参数
                            onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
                                eval("var jsondata = " + data + ";");
                                var key = jsondata['key'];
                                var fileName = jsondata['fileName'];

                                $('#img_add'+right_num).prev().attr('src','<?php echo IMG_GJ_LIST?>'+fileName);
                                $('#img_add'+right_num).parent().find('.shadow').show();//显示重新上传
                                $('#img_add'+right_num).hide();
                                $('#img_reload' + right_num).show();
                                $('#img_reload' + right_num).uploadify({
                                    onInit: function () {
                                        $(".uploadify-queue").hide();
                                    },//载入时触发，将flash设置到最小
                                    uploader: '<?php echo UPLOAD_TO_PATH?>',// 服务器处理地址
                                    swf: '<?php echo GJ_STATIC_JS?>' + 'uploadify/uploadify.swf',
                                    buttonText: "重新上传",//按钮文字
                                    buttonClass: 'shadow',
                                    height: 30,  //按钮高度
                                    width: 140, //按钮宽度
                                    fileTypeExts: "<?php echo UPLOAD_IMG_TYPE;?>",//允许的文件类型
                                    fileTypeDesc: "请选择图片文件", //文件说明
                                    formData: {'folder': '<?php echo(IMG_GJ_FOLDER)?>', 'thumb' : '<?php echo IMG_GJ_THUMB_NAV?>'}, //提交给服务器端的参数
                                    onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
                                        eval("var jsondata = " + data + ";");
                                        var key = jsondata['key'];
                                        var fileName = jsondata['fileName'];

                                        $('#img_add' + right_num).prev().attr('src', '<?php echo IMG_GJ_LIST?>' + fileName);
                                        $('#img_add' + right_num).parent().find('.shadow').show();//显示重新上传
                                        $('#img_reload' + right_num).show();
                                        //将图片添加到左边
                                        $('#' + select_left_id).find('li').eq(right_num - 1).find('img').attr('src', '<?php echo IMG_GJ_LIST?>' + fileName);
                                        //将图片路径添加到数组里面
                                        var nav_num = select_left_id.substring(7, select_left_id.length);
                                        img_nav_arr[nav_num][right_num - 1] = fileName;
                                        $('#img_reload' + right_num).addClass('shadow');
                                    }
                                });
                                $('#img_reload'+right_num).addClass('shadow');
                                //将图片添加到左边
                                $('#'+select_left_id).find('li').eq(right_num-1).find('img').attr('src','<?php echo IMG_GJ_LIST?>'+fileName);
                                //将图片路径添加到数组里面
                                var nav_num=select_left_id.substring(7,select_left_id.length);
                                img_nav_arr[nav_num][right_num-1]=fileName;
                            }
                        });
                    }
                }
                else {
                    $('#img_nav_add' + right_num).find('.bgBlue').html(img_href_url);
                    text_flag=true;
                }

                if(img_flag&&!text_flag)
                {
                    $('#img_nav_add' + right_num).show();
                    $('#img_nav_add' + right_num).find('dt.inlineBlock').next().show();
                    $('#img_nav_add' + right_num).find('dt.inlineBlock').next().next().hide();
                }
                else if(!img_flag&&text_flag)
                {
                    $('#img_nav_add' + right_num).show();
                    $('#img_nav_add' + right_num).find('dt.inlineBlock').next().hide();
                    $('#img_nav_add' + right_num).find('dt.inlineBlock').next().next().show();
                }
                else if(!img_flag&&!text_flag)
                {
                    $('#img_nav_add' + right_num).show();
                    $('#img_nav_add' + right_num).find('dt.inlineBlock').next().show();
                    $('#img_nav_add' + right_num).find('dt.inlineBlock').next().next().hide();
                }
                else if(img_flag&&text_flag)
                {
                    $('#img_nav_add' + right_num).hide();
                }
            });
            $('#all_area').show();
            //显示保存按钮
            $('#img_nav_btn').show();
        }
        else if(select_left_id.indexOf('img_adv')>-1)
        {
            //根据数组内容显示图片和链接
            var img_adv_index=select_left_id.substring(7,select_left_id.length);
            var img_adv_img_flag=false;
            var img_adv_href_flag=false;
            if(img_adv_arr[img_adv_index][0]!='null')
            {

                $('#img_adv').find('img').attr('src','<?php echo IMG_GJ_LIST?>'+img_adv_arr[img_adv_index][0]);
                //添加图片重新上传
                if($('#img_adv_reload').css('display')=='none') {
                    $('#img_adv_reload').show();
                    $('#img_adv_reload').uploadify({
                        onInit: function () {
                            $(".uploadify-queue").hide();
                        },//载入时触发，将flash设置到最小
                        uploader: '<?php echo UPLOAD_TO_PATH?>',// 服务器处理地址
                        swf: '<?php echo GJ_STATIC_JS?>' + 'uploadify/uploadify.swf',
                        buttonText: "重新上传",//按钮文字
                        buttonClass:'shadow',
                        height: 35,  //按钮高度
                        width: 140, //按钮宽度
                        fileTypeExts: "<?php echo UPLOAD_IMG_TYPE;?>",//允许的文件类型
                        fileTypeDesc: "请选择图片文件", //文件说明
                        formData: {'folder': '<?php echo(IMG_GJ_FOLDER)?>', 'thumb' : '<?php echo IMG_GJ_THUMB_600?>'}, //提交给服务器端的参数
                        onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
                            eval("var jsondata = " + data + ";");
                            var key = jsondata['key'];
                            var fileName = jsondata['fileName'];
                            $("#img_adv_reload").show();
                            $("#img_adv_reload").addClass('shadow');
                            //将图片显示在右边
                            $('#img_adv').find('img').attr('src', '<?php echo IMG_GJ_LIST?>' + fileName);
                            //将图片显示在左边
                            $('#' + select_left_id).find('img').attr('src', '<?php echo IMG_GJ_LIST?>' + fileName);
                            //将图片添加到图片广告数组
                            var num = select_left_id.substring(7, select_left_id.length);
                            img_adv_arr[num][0] = fileName;

                        }
                    });
                    $('#img_adv_reload').addClass('shadow');
                }
            }
            else
            {
                $('#img_adv').find('img').attr('src','');//图片清空
                img_adv_img_flag=true;
            }
            if(img_adv_href_arr[img_adv_index][0]!='null')
            {
                var img_adv_str=img_adv_href_arr[img_adv_index][0].split(';');
                var img_adv_shop_type_name='';
                if(img_adv_str[2] == <?php echo SHOP_TYPE_GROUP ?>){
                    img_adv_shop_type_name = '商品分组';
                }else{
                    img_adv_shop_type_name = '商品';
                }
                $('#img_adv').find('.bgBlue').html(img_adv_shop_type_name+"|"+img_adv_str[1]+'<a href="javascript:;" class="close" onclick="img_adv_href_del(this)"></a>');
                if($('#img_adv_add').find('a')&&img_adv_img_flag)
                {
                    $('#img_adv_add').uploadify({
                        onInit: function () {$(".uploadify-queue").hide();},//载入时触发，将flash设置到最小
                        uploader: '<?php echo UPLOAD_TO_PATH?>',// 服务器处理地址
                        swf: '<?php echo GJ_STATIC_JS?>'+'uploadify/uploadify.swf',
                        buttonText: "点击上传",//按钮文字
                        height: 70,  //按钮高度
                        width: 140, //按钮宽度
                        fileTypeExts: "<?php echo UPLOAD_IMG_TYPE;?>",//允许的文件类型
                        fileTypeDesc: "请选择图片文件", //文件说明
                        formData: { 'folder' : '<?php echo(IMG_GJ_FOLDER)?>', 'thumb' : '<?php echo IMG_GJ_THUMB_600?>'}, //提交给服务器端的参数
                        onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
                            eval("var jsondata = " + data + ";");
                            var key = jsondata['key'];
                            var fileName = jsondata['fileName'];
                            $('#img_adv_reload').uploadify({
                                onInit: function () {$(".uploadify-queue").hide();},//载入时触发，将flash设置到最小
                                uploader: '<?php echo UPLOAD_TO_PATH?>',// 服务器处理地址
                                swf: '<?php echo GJ_STATIC_JS?>'+'uploadify/uploadify.swf',
                                buttonText: "重新上传",//按钮文字
                                buttonClass:'shadow',
                                height: 35,  //按钮高度
                                width: 140, //按钮宽度
                                fileTypeExts: "<?php echo UPLOAD_IMG_TYPE;?>",//允许的文件类型
                                fileTypeDesc: "请选择图片文件", //文件说明
                                formData: { 'folder' : '<?php echo(IMG_GJ_FOLDER)?>', 'thumb' : '<?php echo IMG_GJ_THUMB_600?>'}, //提交给服务器端的参数
                                onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
                                    eval("var jsondata = " + data + ";");
                                    var key = jsondata['key'];
                                    var fileName = jsondata['fileName'];
                                    $("#img_adv_reload").show();
                                    $("#img_adv_reload").find('.shadow').show();
                                    $("#img_adv_reload").addClass('shadow');
                                    //将图片显示在右边
                                    $('#img_adv').find('img').attr('src','<?php echo IMG_GJ_LIST?>'+fileName);
                                    //将图片显示在左边
                                    $('#'+select_left_id).find('img').attr('src','<?php echo IMG_GJ_LIST?>'+fileName);
                                    //将图片添加到图片广告数组
                                    var num=select_left_id.substring(7,select_left_id.length);
                                    img_adv_arr[num][0]=fileName;

                                }
                            });
                            //显示重新上传
                            $("#img_adv_reload").show();
                            $("#img_adv_reload").find('.shadow').show();
                            $("#img_adv_reload").addClass('shadow');
                            //将图片显示在右边
                            $('#img_adv').find('img').attr('src','<?php echo IMG_GJ_LIST?>'+fileName);
                            //将图片显示在左边
                            $('#'+select_left_id).find('img').attr('src','<?php echo IMG_GJ_LIST?>'+fileName);
                            //将图片添加到图片广告数组
                            var num=select_left_id.substring(7,select_left_id.length);
                            img_adv_arr[num][0]=fileName;

                        }
                    });
                }
            }
            else
            {
                $('#img_adv').find('.bgBlue').html('<a href="javascript:;" class="close" onclick="img_adv_href_del(this)"></a>');
                img_adv_href_flag=true;
            }

            if(!img_adv_href_flag&&img_adv_href_flag)
            {
                $('#img_adv').find('dt.inlineBlock').next().hide();
                $('#img_adv').find('dt.inlineBlock').next().next().show();
            }
            else if(img_adv_href_flag&&img_adv_href_flag)
            {
                $('#img_adv').find('dt.inlineBlock').next().hide();
                $('#img_adv').find('dt.inlineBlock').next().next().show();
            }
            else if(img_adv_href_flag&&!img_adv_href_flag)
            {
                $('#img_adv').find('dt.inlineBlock').next().show();
                $('#img_adv').find('dt.inlineBlock').next().next().hide();
            }
            else if(!img_adv_href_flag&&!img_adv_href_flag)
            {
                $('#img_adv').find('dt.inlineBlock').next().show();
                $('#img_adv').find('dt.inlineBlock').next().next().hide();
            }
            //图片广告
            $('#all_area').show();
            $('#img_adv_edit').show();
            $('#img_adv').show();
            //显示保存按钮
            $('#img_nav_btn').show();
        }
        else if(select_left_id.indexOf('shop_carousel')>-1)
        {
            //是轮播图
            $('#shop_carousel_edit').show();
            showing_right_id='shop_carousel_edit';
            //重置右边
            $('#'+showing_right_id).find('.bcgItem').find('img').attr('src','');
            $('#'+showing_right_id).find('.shadow').hide();
            var shop_carousel_num=1;
            $('#'+showing_right_id).find('.bgBlue').each(function(){
                $(this).parent().hide();
                $(this).parent().next().show();
                var shop_carousel_html='<a href="javascript:;" class="close" onclick="shop_carousel_href_del(this,'+shop_carousel_num+')"></a>';
                $(this).html(shop_carousel_html);
                shop_carousel_num++;
            });
            $('#'+showing_right_id).find('dt.inlineBlock').next().hide();
            $('#'+showing_right_id).find('dt.inlineBlock').next().next().show();

            var shop_carousel_row=select_left_id.substring(13,select_left_id.length);

            $('#shop_carousel_edit').find('.htcon').each(function(){
                var shop_carousel_i=$(this).next().next().next().val()-1;
                var shop_carousel_img_flag=false;
                var shop_carousel_text_flag=false;
                if(shop_carousel_img_arr[shop_carousel_row][shop_carousel_i]!='null') {
                    //根据数组设置左边
                    $('#'+select_left_id).find('img').attr('src','<?php echo IMG_GJ_LIST?>'+shop_carousel_img_arr[shop_carousel_row][shop_carousel_i]);
                    //根据数组设置右边
                    $('#shop_carousel_add' + (shop_carousel_i + 1)).find('img').attr('src', '<?php echo IMG_GJ_LIST?>' + shop_carousel_img_arr[shop_carousel_row][shop_carousel_i]);
                    //显示重新上传
                    if($('#shop_carousel_img_reload'+(shop_carousel_i + 1)).css('display')=='none') {
                        $('#shop_carousel_img_reload'+(shop_carousel_i + 1)).show();
                        $('#shop_carousel_img_reload' + (shop_carousel_i + 1)).uploadify({
                            onInit: function () {
                                $(".uploadify-queue").hide();
                            },//载入时触发，将flash设置到最小
                            uploader: '<?php echo UPLOAD_TO_PATH?>',// 服务器处理地址
                            swf: '<?php echo GJ_STATIC_JS?>' + 'uploadify/uploadify.swf',
                            buttonText: "重新上传",//按钮文字
                            buttonClass: 'shadow',
                            height: 35,  //按钮高度
                            width: 140, //按钮宽度
                            fileTypeExts: "<?php echo UPLOAD_IMG_TYPE;?>",//允许的文件类型
                            fileTypeDesc: "请选择图片文件", //文件说明
                            formData: {'folder': '<?php echo(IMG_GJ_FOLDER)?>', 'thumb' : '<?php echo IMG_GJ_THUMB_600?>'}, //提交给服务器端的参数
                            onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
                                eval("var jsondata = " + data + ";");
                                var key = jsondata['key'];
                                var fileName = jsondata['fileName'];

                                //显示重新上传
                                $('#shop_carousel_img_reload' + (shop_carousel_i + 1)).show();
                                $('#shop_carousel_img_reload' + (shop_carousel_i + 1)).addClass('shadow');

                                $('#shop_carousel_img_add' + (shop_carousel_i + 1)).prev().attr('src', '<?php echo IMG_GJ_LIST?>' + fileName);
                                //将图片添加到左边
                                $('#' + select_left_id).find('img').attr('src', '<?php echo IMG_GJ_LIST?>' + fileName);
                                //将图片路径添加到数组里面
                                var nav_num = select_left_id.substring(13, select_left_id.length);
                                shop_carousel_img_arr[nav_num][shop_carousel_i] = fileName;
                            }
                        });
                        $('#shop_carousel_img_reload' + (shop_carousel_i + 1)).addClass('shadow');
                    }
                }
                else
                {
                    shop_carousel_img_flag=true;
                }
                var shop_carousel_img_href_url='<a href="javascript:;" class="close" onclick="shop_carousel_href_del(this,'+(shop_carousel_i+1)+')"></a>';
                var shop_carousel_str=shop_carousel_href_arr[shop_carousel_row][shop_carousel_i].split(';');
                var shop_carousel_shop_type_name='';
                if(shop_carousel_str[2] == <?php echo SHOP_TYPE_GROUP ?>){
                    shop_carousel_shop_type_name = '商品分组';
                }else{
                    shop_carousel_shop_type_name = '商品';
                }
                if(shop_carousel_href_arr[shop_carousel_row][shop_carousel_i]!='null') {
                    $('#shop_carousel_add' + (shop_carousel_i + 1)).find('.bgBlue').html(shop_carousel_shop_type_name + '|' + shop_carousel_str[1] + shop_carousel_img_href_url);
                    if($('#shop_carousel_img_add'+(shop_carousel_i + 1)).find('a')&&shop_carousel_img_flag)
                    {
                        $('#shop_carousel_img_add'+(shop_carousel_i + 1)).uploadify({
                            onInit: function () {$(".uploadify-queue").hide();},//载入时触发，将flash设置到最小
                            uploader: '<?php echo UPLOAD_TO_PATH?>',// 服务器处理地址
                            swf: '<?php echo GJ_STATIC_JS?>'+'uploadify/uploadify.swf',
                            buttonText: "点击上传",//按钮文字
                            height: 70,  //按钮高度
                            width: 140, //按钮宽度
                            fileTypeExts: "<?php echo UPLOAD_IMG_TYPE;?>",//允许的文件类型
                            fileTypeDesc: "请选择图片文件", //文件说明
                            formData: { 'folder' : '<?php echo(IMG_GJ_FOLDER)?>', 'thumb' : '<?php echo IMG_GJ_THUMB_600?>'}, //提交给服务器端的参数
                            onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
                                eval("var jsondata = " + data + ";");
                                var key = jsondata['key'];
                                var fileName = jsondata['fileName'];
                                $('#shop_carousel_img_reload'+(shop_carousel_i + 1)).show();
                                //显示重新上传
                                $('#shop_carousel_img_reload'+(shop_carousel_i + 1)).uploadify({
                                    onInit: function () {$(".uploadify-queue").hide();},//载入时触发，将flash设置到最小
                                    uploader: '<?php echo UPLOAD_TO_PATH?>',// 服务器处理地址
                                    swf: '<?php echo GJ_STATIC_JS?>'+'uploadify/uploadify.swf',
                                    buttonText: "重新上传",//按钮文字
                                    buttonClass:'shadow',
                                    height: 35,  //按钮高度
                                    width: 140, //按钮宽度
                                    fileTypeExts: "<?php echo UPLOAD_IMG_TYPE;?>",//允许的文件类型
                                    fileTypeDesc: "请选择图片文件", //文件说明
                                    formData: { 'folder' : '<?php echo(IMG_GJ_FOLDER)?>', 'thumb' : '<?php echo IMG_GJ_THUMB_600?>'}, //提交给服务器端的参数
                                    onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
                                        eval("var jsondata = " + data + ";");
                                        var key = jsondata['key'];
                                        var fileName = jsondata['fileName'];

                                        //显示重新上传
                                        $('#shop_carousel_img_reload'+(shop_carousel_i + 1)).show();
                                        $('#shop_carousel_img_reload'+(shop_carousel_i + 1)).addClass('shadow');

                                        $('#shop_carousel_img_add'+(shop_carousel_i + 1)).prev().attr('src','<?php echo IMG_GJ_LIST?>'+fileName);
                                        //将图片添加到左边
                                        $('#'+select_left_id).find('img').attr('src','<?php echo IMG_GJ_LIST?>'+fileName);
                                        //将图片路径添加到数组里面
                                        var nav_num=select_left_id.substring(13,select_left_id.length);
                                        shop_carousel_img_arr[nav_num][shop_carousel_i]=fileName;
                                    }
                                });
                                $('#shop_carousel_img_reload'+(shop_carousel_i + 1)).addClass('shadow');
                                $('#shop_carousel_img_add'+(shop_carousel_i + 1)).prev().attr('src','<?php echo IMG_GJ_LIST?>'+fileName);
                                //将图片添加到左边
                                $('#'+select_left_id).find('img').attr('src','<?php echo IMG_GJ_LIST?>'+fileName);
                                //将图片路径添加到数组里面
                                var nav_num=select_left_id.substring(13,select_left_id.length);
                                shop_carousel_img_arr[nav_num][shop_carousel_i]=fileName;
                            }
                        });
                    }
                }
                else {
                    $('#shop_carousel_add' + (shop_carousel_i + 1)).find('.bgBlue').html(shop_carousel_img_href_url);
                    shop_carousel_text_flag=true;
                }

                if(shop_carousel_img_flag&&!shop_carousel_text_flag)
                {
                    $('#shop_carousel_add' + (shop_carousel_i + 1)).show();
                    $('#shop_carousel_add' + (shop_carousel_i + 1)).find('dt.inlineBlock').next().show();
                    $('#shop_carousel_add' + (shop_carousel_i + 1)).find('dt.inlineBlock').next().next().hide();
                }
                else if(!shop_carousel_img_flag&&shop_carousel_text_flag)
                {
                    $('#shop_carousel_add' + (shop_carousel_i + 1)).show();
                    $('#shop_carousel_add' + (shop_carousel_i + 1)).find('dt.inlineBlock').next().hide();
                    $('#shop_carousel_add' + (shop_carousel_i + 1)).find('dt.inlineBlock').next().next().show();
                }
                else if(!shop_carousel_img_flag&&!shop_carousel_text_flag)
                {
                    $('#shop_carousel_add' + (shop_carousel_i + 1)).show();
                    $('#shop_carousel_add' + (shop_carousel_i + 1)).find('dt.inlineBlock').next().show();
                    $('#shop_carousel_add' + (shop_carousel_i + 1)).find('dt.inlineBlock').next().next().hide();
                }
                else if(shop_carousel_img_flag&&shop_carousel_text_flag)
                {
                    $('#shop_carousel_add' + (shop_carousel_i + 1)).hide();
                }
            });

            $('#all_area').show();
            //显示保存按钮
            $('#img_nav_btn').show();
        }
        else if(select_left_id.indexOf('shop_search')>-1)
        {
//            $('.SER_block').hide();
            $('#all_area').show();
            $('#img_nav_btn').show();
        }
        else if(select_left_id.indexOf('shop_group')>-1)
        {
            //右边显示
            $('#shop_group_edit').show();
            showing_right_id='shop_group_edit';
            //重置右边
            $('#shop_group_add').find('.bcgItem').remove();
            //左边序号
            var shopgroup_num_str=select_left_id.substring(10,select_left_id.length);
            var shopgroup_num=parseInt(shopgroup_num_str);
            //根据数组添加右边的内容
            for(var shop_group_i=0;shop_group_i<1;shop_group_i++)
            {
                var shop_group_href_str=shop_group_href_arr[shopgroup_num][shop_group_i];
                var shop_group_href_html='';
                var shop_group_text_show='';
                var shop_group_href_show='';
                if(shop_group_href_str!='null') {
                    var group_href_arr = shop_group_href_str.split(';');
                    var shop_group_href_name='';
                    if(group_href_arr[2]==<?php echo SHOP_TYPE_GROUP ?>)
                    {
                        shop_group_href_name='商品分组';
                    }
                    else
                    {
                        shop_group_href_name='商品';
                    }
                    shop_group_href_html=shop_group_href_name+"|"+group_href_arr[1]+'<a href="javascript:;" class="close" onclick="shop_group_href_del(this,'+shop_group_i+')"></a>';
                    shop_group_href_show='display:none';

                    $('#shop_group_add').append('<div class="bcgItem clearfix" id="shop_group_item_add'+shop_group_i+'">'+
                    '<div class="text">'+
                    '<dl class="inlineBlock">'+
                    '<dt class="inlineBlock">链接：</dt>'+
                    '<dd class="inlineBlock" style="'+shop_group_text_show+'">'+
                    '<div class="bgBlue">'+shop_group_href_html+
                    '</div>'+
                    '<a href="javascript:;" onclick="shop_group_edit_href(this,'+shop_group_i+')">修改</a>'+
                    '</dd>'+
                    '<dd class="inlineBlock" style="'+shop_group_href_show+'">'+
                    '<a href="javascript:;" onclick="shop_group_add_href(this,'+shop_group_i+')">添加链接</a>'+
                    '</dd>'+
                    '</dl>'+
                    '</div>'+
                    '<a href="javascript:;" class="close" onclick="shop_group_del(this,'+shop_group_i+')"></a>'+
                    '<input style="display: none" value="'+shop_group_i+'">'+
                    '</div>');
                }
//                else
//                {
//                    shop_group_href_html='<a href="javascript:;" class="close" onclick="shop_group_href_del(this,'+shop_group_i+')"></a>';
//                    shop_group_text_show='display:none';
//                }


            }
            $('#all_area').show();
            //显示保存按钮
            $('#img_nav_btn').show();
        }
        else if(select_left_id.indexOf('qcord')>-1)
        {
            //关注微信第几行
            var qcord_index=select_left_id.substring(5,select_left_id.length);
            if(qcord_arr[qcord_index][0]!='null')
            {
                //背景图不为空
                $('#qcord_edit').find('img').eq(0).attr('src','<?php echo IMG_GJ_LIST?>'+qcord_arr[qcord_index][0]);
                if($('#qcord_bg_img_reload').css('display')=='none')
                {
                    $('#qcord_bg_img_reload').show();
                    $('#qcord_bg_img_reload').uploadify({
                        onInit: function () {$(".uploadify-queue").hide();},//载入时触发，将flash设置到最小
                        uploader: '<?php echo UPLOAD_TO_PATH?>',// 服务器处理地址
                        swf: '<?php echo GJ_STATIC_JS?>'+'uploadify/uploadify.swf',
                        buttonText: "重新上传",//按钮文字
                        buttonClass:'shadow',
                        height: 35,  //按钮高度
                        width: 140, //按钮宽度
                        fileTypeExts: "<?php echo UPLOAD_IMG_TYPE;?>",//允许的文件类型
                        fileTypeDesc: "请选择图片文件", //文件说明
                        formData: { 'folder' : '<?php echo(IMG_GJ_FOLDER)?>'}, //提交给服务器端的参数
                        onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
                            eval("var jsondata = " + data + ";");
                            var key = jsondata['key'];
                            var fileName = jsondata['fileName'];
                            $("#qcord_bg_img_reload").show();
                            $("#qcord_bg_img_reload").find('.shadow').show();
                            $("#qcord_bg_img_reload").addClass('shadow');
                            //将图片显示在右边
                            $('#qcord_edit').children().eq(0).find('img').attr('src','<?php echo IMG_GJ_LIST?>'+fileName);
                            //将图片显示在左边
                            $('#'+select_left_id).find('.custom-contact').css('background-image',"url(<?php echo IMG_GJ_LIST?>"+fileName+")");
                            //将图片添加到图片广告数组
                            var num=select_left_id.substring(5,select_left_id.length);
                            qcord_arr[num][0]=fileName;
                        }
                    });
                    $('#qcord_bg_img_reload').addClass('shadow');
                }
            }

            if(qcord_arr[qcord_index][1]!='null')
            {
                //二维码不为空
                $('#qcord_edit').find('img').eq(1).attr('src','<?php echo IMG_GJ_LIST?>'+qcord_arr[qcord_index][1]);
                if($('#qcord_img_reload').css('display')=='none')
                {
                    $('#qcord_img_reload').show();
                    $('#qcord_img_reload').uploadify({
                        onInit: function () {$(".uploadify-queue").hide();},//载入时触发，将flash设置到最小
                        uploader: '<?php echo UPLOAD_TO_PATH?>',// 服务器处理地址
                        swf: '<?php echo GJ_STATIC_JS?>'+'uploadify/uploadify.swf',
                        buttonText: "重新上传",//按钮文字
                        buttonClass:'shadow',
                        height: 35,  //按钮高度
                        width: 140, //按钮宽度
                        fileTypeExts: "<?php echo UPLOAD_IMG_TYPE;?>",//允许的文件类型
                        fileTypeDesc: "请选择图片文件", //文件说明
                        formData: { 'folder' : '<?php echo(IMG_GJ_FOLDER)?>'}, //提交给服务器端的参数
                        onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
                            eval("var jsondata = " + data + ";");
                            var key = jsondata['key'];
                            var fileName = jsondata['fileName'];
                            $("#qcord_img_reload").show();
                            $("#qcord_img_reload").find('.shadow').show();
                            $("#qcord_img_reload").addClass('shadow');
                            //将图片显示在右边
                            $('#qcord_edit').children().eq(1).find('img').attr('src','<?php echo IMG_GJ_LIST?>'+fileName);
                            //将图片显示在左边
                            $('#'+select_left_id).find('.custom-contact').css('background-image',"url(<?php echo IMG_GJ_LIST?>"+fileName+")");
                            //将图片添加到图片广告数组
                            var num=select_left_id.substring(5,select_left_id.length);
                            qcord_arr[num][1]=fileName;
                        }
                    });
                    $('#qcord_img_reload').addClass('shadow');
                }
            }

            if(qcord_arr[qcord_index][2]!='null')
            {
                //二维码文字
                $('#qcord_gz_txt').val(qcord_arr[qcord_index][2]);

            }

            if(qcord_arr[qcord_index][3]!='null')
            {
                //电话号码
                $('#qcord_num_txt').val(qcord_arr[qcord_index][3]);
            }

            //二维码
            $('#all_area').show();
            $('#qcord_edit').show();
            //显示保存按钮
            $('#img_nav_btn').show();
            window.parent.autoResize('main',$('body').height()+30);
        }else if(select_left_id.indexOf('catalog')>-1){
            //右边清空
            $('#first_title').val("一级标题");
            //删除其余的二级标题
            $('.catalog-list').children().remove();
            $('.catalog-list').append('<div class="bcgItem clearfix">'+
            '<input type="text" class="txt" value="二级标题" placeholder="二级标题">'+
            '<input class="catalog_count_title" value="0" style="display: none">'+
            '<a href="javascript:;" onclick="catalog_title_del(this,0)" class="close"></a>'+
            '</div>');
            //清空UE
            ue.setContent("");
            //获取当前点击的是第几个目录链接
            var catalog_index=select_left_id.substring(7,select_left_id.length);
            for(var catalog_for_i=0;catalog_for_i<catalog_arr[catalog_index].length;catalog_for_i++)
            {
                if(catalog_for_i==0)
                {
                    //设置一级标题
                    $("#first_title").val(catalog_arr[catalog_index][catalog_for_i][0]);
                }
                else if(catalog_for_i==1)
                {
                    //设置第一个二级标题
                    $('#catalog-wrap').find('.bcgItem').eq(0).find('.txt').val(catalog_arr[catalog_index][catalog_for_i][0]);
                }
                else
                {
                    //二级标题边添加边设置
                    $('.catalog-list').append('<div class="bcgItem clearfix">'+
                    '<input type="text" class="txt" value="'+catalog_arr[catalog_index][catalog_for_i][0]+'" placeholder="二级标题">'+
                    '<input class="catalog_count_title" value="'+(catalog_for_i-1)+'" style="display: none">'+
                    '<a href="javascript:;" onclick="catalog_title_del(this,'+(catalog_for_i-1)+')" class="close"></a>'+
                    '</div>');
                }
            }
            //二维码
            $('#all_area').show();
            $('#catalog_edit').show();
            //显示保存按钮
            $('#img_nav_btn').show();
            $('.kkfm_r_inner').height($('body').height()+200);
            window.parent.autoResize('main',$('body').height()+200);

        }
//        window.parent.autoResize('m');
//        window.parent.autoResize('main');
        //获取左边选中的高度
        var select_top=$('#'+select_left_id).offset().top-200;
//        var select_next_top = $('#' + select_left_id).next().css('top');
//        var select_top_int = parseInt(select_top.substring(0, select_top.length - 2));
//        var select_next_top_int = parseInt(select_next_top.substring(0, select_next_top.length - 2));
//        //设置右边的高度
//        $('#all_area').css('top', (select_top_int + select_next_top_int) / 2 + 'px');
        $('#all_area').css('top', select_top);
        catalog_title_select=-1;//初始化富文本
        //滑动到指定位置
        $("#areaWrap").animate({scrollTop: $("#"+select_left_id).offset.Top}, 1000);
    });

    //添加内容
    $(document).on('click','#areaWrap .add',function(e){
        $('#img_nav_btn').hide();
        $('#'+showing_right_id).hide();
        select_left_id=$(this).parent().parent().parent().attr('id');
        $("#areaWrap .area").removeClass("editing");
        $('#'+select_left_id).addClass('editing');
        //隐藏右边的
        $('.SER_block').hide();
        $('#add_area').show();
        showing_right_id='add_area';
        e.stopPropagation();
    });



    //图片导航添加事件
    function img_nav(obj,type){
        //隐藏右边
        $('.SER_block').hide();
        var num=img_nav_num;
        //在手机屏幕最低端添加图片导航
        $('#areaWrap').append('<div class="area bgWhite" id="img_nav'+num+'">'+
        '<div class="aContent">'+
        '<ul class="custom-nav-1">'+
        '<li>'+'<img src="<?php echo GJ_STATIC_IMAGES.'c.png'?>">'+'</li>'+
        '<li style="display:none">'+'<img src="">'+'</li>'+
        '<li style="display:none">'+'<img src="">'+'</li>'+
        '<li style="display:none">'+'<img src="">'+'</li>'+
        '</ul>'+
        '</div>'+
        '<div class="actions">'+
        '<div class="actionbtn">'+
//        '<a href="javascript:;" class="add">添加内容</a>'+
        '<a href="javascript:;" class="del" onclick="delItem(this,'+num+')">删除</a>'+
        '<input style="display: none" value="img_nav">'+
        '</div>'+
        '</div>'+
        '</div>');
        window.parent.autoResize('m');
        var body_height=$('body').height();
        body_height=body_height+500;
        window.parent.autoResize('main',body_height);
        //新增一个图片导航数组
        img_nav_arr[num]=new Array();
        img_nav_arr[num][0]='null';
        img_nav_arr[num][1]='null';
        img_nav_arr[num][2]='null';
        img_nav_arr[num][3]='null';
        img_nav_href_arr[num]=new Array();
        img_nav_href_arr[num][0]='null';
        img_nav_href_arr[num][1]='null';
        img_nav_href_arr[num][2]='null';
        img_nav_href_arr[num][3]='null';
        img_nav_num++;
        showing_right_id='img_nav_edit';
        select_left_id='img_nav'+num;
        $('#img_nav_edit').show();
        $('#img_nav_btn').show();
        $('#all_area').show();
        //清空右边内容
        $('#img_nav_edit').find('.htcon').parent().hide();
        $('#img_nav_edit').find('img').attr('src','');
        var img_num=1;
        $('#img_nav_edit').find('.bgBlue').each(function(){
            var reset_html='<a href="javascript:;" class="close" onclick="img_nav_href_del(this,'+img_num+')"></a>';
            $(this).html(reset_html);
            $(this).parent().hide();
            $(this).parent().next().show();
            img_num++;
        });
        //取消当前的选中状态
        $("#areaWrap .area").removeClass("editing");
        //设置添加的内容选择状态
        $('#'+select_left_id).addClass('editing');
//        $("#areaWrap").dragsort("destory");
        //设置右边的位置
        var select_top=$('#'+select_left_id).offset().top-200;
        $('#all_area').css('top',select_top);
    }


    /**
     * 商品搜索
     */
    function shop_search(obj,type){
       var num=shop_search_num;
       if(type)
       {
           //true表示添加在最下面
           $('#areaWrap').append('<div class="area bgWhite" id="shop_search'+num+'">'+
           '<div class="aContent">'+
           '<div class="custom-search">'+
           '<form action="/">'+
           '<input type="text" class="txt" placeholder="商品搜索：请输入商品关键字">'+
           '<input type="submit" class="button" value="">'+
           '</form>'+
           '</div>'+
           '</div>'+
           '<div class="actions">'+
           '<div class="actionbtn">'+
//           '<a href="javascript:;" class="add">添加内容</a>'+
           '<a href="javascript:;" class="del" onclick="del_shop_search_Item(this,'+num+')">删除</a>'+
           '</div>'+
           '</div>'+
           '</div>');
       }
       else
       {

       }
        window.parent.autoResize('m');
//           window.parent.autoResize('main');
        //增加页面总高度
        var body_height=$('body').height();
        body_height=body_height+50;
        window.parent.autoResize('main',body_height);
//        alert($('body').height());
//        alert($(document).find('iframe').height());
//        var body_height=$('body').height();
//        body_height=body_height+112;
//        $('body').height(body_height);
//        $(document).find('iframe').height(body_height);
//        alert($('body').height());
//        alert($(document).find('iframe').height(body_height));
        select_left_id='shop_search'+num;
        //取消当前的选中状态
        $("#areaWrap .area").removeClass("editing");
        //设置添加的内容选择状态
        $('#'+select_left_id).addClass('editing');
        //右边的都隐藏
        $('.SER_block').hide();
        //显示保存按钮
        $('#img_nav_btn').show();
       shop_search_num++;
//       $("#areaWrap").dragsort("destory");
        var select_top=$('#'+select_left_id).offset().top-200;
        $('#all_area').css('top',select_top);
    }

    function del_shop_search_Item(obj,index)
    {
        if(!confirm('确定要删除吗?'))
            return false;
        //左边删除
        $(obj).parent().parent().parent().remove();
        //隐藏保存按钮
        $('#img_nav_btn').hide();
        $('#all_area').hide();
//        shop_search_num--;
//        $("#areaWrap").dragsort("destory");
        window.parent.autoResize('main',$('body').height()-50);
    }


    /**
     * 图片广告
     */
    function img_adv(obj,type)
    {
        var num=img_adv_num;
        if(type)
        {
            //添加在最下面
            $('#areaWrap').append('<div class="area bgWhite" id="img_adv'+num+'">'+
            '<div class="aContent">'+
            '<div class="custom-image-swiper">'+
            '<img src="<?php echo GJ_STATIC_IMAGES?>pic1.jpg">'+
            '</div>'+
            '</div>'+
            '<div class="actions">'+
            '<div class="actionbtn">'+
//            '<a href="javascript:;" class="add">添加内容</a>'+
            '<a href="javascript:;" class="del" onclick="del_img_adv_Item(this,'+num+')">删除</a>'+
            '</div>'+
            '</div>'+
            '</div>');

        }
        var body_height=$('body').height();
        body_height=body_height+70;

        //调整页面总高度
//        var main_height=$(document).height();
//        main_height=main_height+200;
//        $(document).height(main_height);
        img_adv_arr[num]=new Array();
        img_adv_arr[num][0]='null';
        img_adv_href_arr[num]=new Array();
        img_adv_href_arr[num][0]='null';
        select_left_id='img_adv'+num;
        //清空右边图片广告内容
        $('#img_adv_add').prev().attr('src','');
        $("#img_adv_reload").hide();
        $("#img_adv_edit").find('dt.inlineBlock').next().hide();
        $("#img_adv_edit").find('dt.inlineBlock').next().next().show();
        //取消当前的选中状态
        $("#areaWrap .area").removeClass("editing");
        //设置添加的内容选择状态
        $('#'+select_left_id).addClass('editing');
        //右边都隐藏掉，显示图片广告添加
        $('.SER_block').hide();
        $('#img_adv_edit').show();
        $('#img_adv').show();
        $('#img_nav_btn').show();
        $('#all_area').show();
        $('#img_adv_reload').hide();
        img_adv_num++;
//        $("#areaWrap").dragsort("destory");
        window.parent.autoResize('main',body_height);
        //设置右边的位置
        var select_top=$('#'+select_left_id).offset().top-200;
        $('#all_area').css('top',select_top);
    }

    //图片广告删除
    function del_img_adv_Item(obj,num)
    {
        if(!confirm('确定要删除吗?'))
            return false;
        $(obj).parent().parent().parent().remove();
        //删除数组的内容
        img_adv_arr[num][0]='null';
        img_adv_href_arr[num][0]='null';
        //清空右边的内容
        $('.SER_block').hide();
        $('#img_nav_btn').hide();
        $('#img_adv').find('img').attr('src','');
        $('#img_adv').find('.bgBlue').html('<a href="javascript:;" class="close" onclick="img_adv_href_del(this)"></a>');
        $('#img_adv').find('dt.inlineBlock').next().hide();
        $('#img_adv').find('dt.inlineBlock').next().next().show();
        $('#all_area').hide();
        //img_adv_num--;
//        $("#areaWrap").dragsort("destory");
        window.parent.autoResize('main',$('body').height()-70);
    }

    //图片广告添加图片插件
    $('#img_adv_add').uploadify({
        onInit: function () {$(".uploadify-queue").hide();},//载入时触发，将flash设置到最小
        uploader: '<?php echo UPLOAD_TO_PATH?>',// 服务器处理地址
        swf: '<?php echo GJ_STATIC_JS?>'+'uploadify/uploadify.swf',
        buttonText: "点击上传",//按钮文字
        height: 70,  //按钮高度
        width: 140, //按钮宽度
        fileTypeExts: "<?php echo UPLOAD_IMG_TYPE;?>",//允许的文件类型
        fileTypeDesc: "请选择图片文件", //文件说明
        formData: { 'folder' : '<?php echo(IMG_GJ_FOLDER)?>', 'thumb' : '<?php echo IMG_GJ_THUMB_600?>'}, //提交给服务器端的参数
        onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
            eval("var jsondata = " + data + ";");
            var key = jsondata['key'];
            var fileName = jsondata['fileName'];
            $('#img_adv_reload').uploadify({
                onInit: function () {$(".uploadify-queue").hide();},//载入时触发，将flash设置到最小
                uploader: '<?php echo UPLOAD_TO_PATH?>',// 服务器处理地址
                swf: '<?php echo GJ_STATIC_JS?>'+'uploadify/uploadify.swf',
                buttonText: "重新上传",//按钮文字
                buttonClass:'shadow',
                height: 35,  //按钮高度
                width: 140, //按钮宽度
                fileTypeExts: "<?php echo UPLOAD_IMG_TYPE;?>",//允许的文件类型
                fileTypeDesc: "请选择图片文件", //文件说明
                formData: { 'folder' : '<?php echo(IMG_GJ_FOLDER)?>', 'thumb' : '<?php echo IMG_GJ_THUMB_600?>'}, //提交给服务器端的参数
                onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
                    eval("var jsondata = " + data + ";");
                    var key = jsondata['key'];
                    var fileName = jsondata['fileName'];
                    $("#img_adv_reload").show();
                    $("#img_adv_reload").find('.shadow').show();
                    $("#img_adv_reload").addClass('shadow');
                    //将图片显示在右边
                    $('#img_adv').find('img').attr('src','<?php echo IMG_GJ_LIST?>'+fileName);
                    //将图片显示在左边
                    $('#'+select_left_id).find('img').attr('src','<?php echo IMG_GJ_LIST?>'+fileName);
                    //将图片添加到图片广告数组
                    var num=select_left_id.substring(7,select_left_id.length);
                    img_adv_arr[num][0]=fileName;

                }
            });
            //显示重新上传
            $("#img_adv_reload").show();
            $("#img_adv_reload").find('.shadow').show();
            $("#img_adv_reload").addClass('shadow');
            //将图片显示在右边
            $('#img_adv').find('img').attr('src','<?php echo IMG_GJ_LIST?>'+fileName);
            //将图片显示在左边
            $('#'+select_left_id).find('img').attr('src','<?php echo IMG_GJ_LIST?>'+fileName);
            //将图片添加到图片广告数组
            var num=select_left_id.substring(7,select_left_id.length);
            img_adv_arr[num][0]=fileName;

        }
    });

    //图片广告添加链接
    function img_adv_add_href(obj)
    {
        var flag = 2; //定义flag=2代表url添加操作
        var type='img_adv';
        var num=0;
        var top=$("#all_area").offset().top-200;
        art.dialog.open(
            '<?php echo Yii::app()->createUrl('mCenter/DshopMallHome/dialogHeader');?>'+'?index='+num+'&flag='+flag+'&type='+type+'&shop_group=false',
            {
                title: '',
//                lock: true,
                drag:true,
                width: '785px',
//                height: '335px',
                top:top,
                id: 'dialog_notice',
                background: '#fff'
            }
        );
    }

    //图片广告修改链接
    function img_adv_edit_href(obj)
    {
        var flag = 1; //定义flag=2代表url添加操作
        var type='img_adv';
        var num=0;
        var top=$("#all_area").offset().top-200;
        art.dialog.open(
            '<?php echo Yii::app()->createUrl('mCenter/DshopMallHome/dialogHeader');?>'+'?index='+num+'&flag='+flag+'&type='+type+'&shop_group=false',
            {
                title: '',
//                lock: true,
                drag:true,
                width: '785px',
//                height: '335px',
                top:top,
                id: 'dialog_notice',
                background: '#fff'
            }
        );
    }

    //图片广告删除链接
	function img_adv_href_del(obj) {
        $(obj).parent().parent().siblings().show();
        $(obj).parent().parent().hide();
    }

    /**
     * 商品分组添加
     */
    function shop_group(obj,type)
    {
        var num=shop_group_num;
        if(type)
        {
            //添加在最下面
            $('#areaWrap').append('<div class="area bgWhite" id="shop_group'+num+'">'+
            '<div class="aContent">'+
            '<div class="custom-image-swiper">'+
            '<img src="<?php echo GJ_STATIC_IMAGES?>shop_group.png">'+
            '</div>'+
            '</div>'+
            '<div class="actions">'+
            '<div class="actionbtn">'+
//            '<a href="#" class="add">添加内容</a>'+
            '<a href="javascript:;" class="del" onclick="del_shop_group_Item(this,'+num+')">删除</a>'+
            '</div>'+
            '</div>'+
            '</div>');
        }
        var body_height=$('body').height();
        body_height=body_height+400;

        $('.kkfm_r_inner').height(body_height);
        select_left_id='shop_group'+num;
        //数组新建
        shop_group_href_arr[num]=new Array();
        shop_group_href_arr[num][0]='null';
        //右边所有隐藏
        $('.SER_block').hide();
        //右边显示的id
        showing_right_id='shop_group_edit';
        //右边清空
        $('#shop_group_add').find('.bcgItem').remove();
        //右边显示
        $('#all_area').show();
        $('#shop_group_edit').show();
        //左边取消先前的选中状态
        $("#areaWrap .area").removeClass("editing");
        //左边选中状态
        $('#'+select_left_id).addClass('editing');
        //显示保存按钮
        $('#img_nav_btn').show();
//        $("#areaWrap").dragsort("destory");
        //改变右边的位置
        var select_top=$('#'+select_left_id).prev().next().offset().top-200;
        $('#all_area').css('top',select_top);
        //商品分组数量加一
        shop_group_num++;
        window.parent.autoResize('main',body_height);
    }

    /**
     * 添加一个商品分组
     */
    $(document).on('click','#add_shop_group',function(){
        //只能添加一个商品分组
        //判断已经添加了商品分组
        var child_count=$("#shop_group_add").children().length;
        if(child_count>=1)
        {           
            alert('至多添加一个！');
            return true;
        }
        var num_str=select_left_id.substring(10,select_left_id.length);
        var num=parseInt(num_str);
        var group_item_num=0;
		shop_group_item_num[num] = 0;
        if(shop_group_item_num.length!=0)
        {
            var group_item_num_str=shop_group_item_num[num];
            group_item_num=parseInt(group_item_num_str);
            //group_item_num++;
        }
        //动态添加分组
        shop_group_href_arr[num][group_item_num]='null';//初始化
        //在右边添加一行
        $('#shop_group_add').append('<div class="bcgItem clearfix" id="shop_group_item_add'+group_item_num+'">'+
        '<div class="text">'+
        '<dl class="inlineBlock">'+
        '<dt class="inlineBlock">链接：</dt>'+
        '<dd class="inlineBlock" style="display:none">'+
        '<div class="bgBlue">'+
        '<a href="javascript:;" class="close" onclick="shop_group_href_del(this,'+group_item_num+')"></a>'+
        '</div>'+
        '<a href="javascript:;" onclick="shop_group_edit_href(this,'+group_item_num+')">修改</a>'+
        '</dd>'+
        '<dd class="inlineBlock">'+
        '<a href="javascript:;" onclick="shop_group_add_href(this,'+group_item_num+')">添加链接</a>'+
        '</dd>'+
        '</dl>'+
        '</div>'+
        '<a href="javascript:;" class="close" onclick="shop_group_del(this,'+group_item_num+')"></a>'+
        '<input style="display: none" value="'+group_item_num+'">'+
        '</div>');
        
        var all_area_top=$('#all_area').offset().top-200;
        var all_area_top_int=parseInt(all_area_top.substring(0,all_area_top.length));
        //$('#all_area').css('top',(all_area_top_int-65)+'px');
        //分组项加一
        shop_group_item_num[num]=group_item_num;
        var body_height=$('body').height()+30;
        window.parent.autoResize('main',body_height);
    });

    /**
     * 商品分组删除链接
     */
    function shop_group_href_del(obj,index)
    {
        if(!confirm('确认要删除吗?'))
            return false;
        //清空对应的数组
        var num=select_left_id.substring(10,select_left_id.length);
        shop_group_href_arr[num][index]='null';
        //隐藏文字
        $(obj).parent().parent().hide();
        $(obj).parent().parent().next().show();
    }

    /**
     * 商品分组整行删除
     */
    function shop_group_del(obj,index)
    {
        if(!confirm('确认要删除吗?'))
            return false;
        //删除的是第几个商品分组
        var num=select_left_id.substring(10,select_left_id.length);
        //删除数组对应的数据
        shop_group_href_arr[num][index]='null';
        //删除该行
        $(obj).parent().remove();
    }

    /**
     * 整个商品分组删除
     */
    function del_shop_group_Item(obj,index)
    {
        if(!confirm('确认要删除吗?'))
            return false;
        //删除整行数组
        shop_group_href_arr[index]='null';
        //当前静态页面数据
        $(obj).parent().parent().parent().remove();
        //清空右边的页面
        $('#shop_group_add').find('.bcgItem').remove();
        $('#shop_group_edit').hide();
        $('#img_nav_btn').hide();
        $('#all_area').hide();
//        $("#areaWrap").dragsort("destory");
        $('.kkfm_r_inner').height($('body').height()-400);
        window.parent.autoResize('main',$('body').height()-50);
    }

    /**
     * 商品分组添加链接
     */
    function shop_group_add_href(obj,index)
    {
        var flag = 2; //定义flag=2代表url添加操作
        var type='shop_group';
        var top=$("#all_area").offset().top-200;
        var shop_group=true;
        art.dialog.open(
            '<?php echo Yii::app()->createUrl('mCenter/DshopMallHome/dialogHeader');?>'+'?index='+index+'&flag='+flag+'&type='+type+'&shop_group='+shop_group,
            {
                title: '',
//                lock: true,
                drag:true,
                width: '785px',
//                height: '335px',
                top:top,
                id: 'dialog_notice',
                background: '#fff'
            }
        );
    }

    /**
     * 商品分组修改链接
     */
    function shop_group_edit_href(obj,index)
    {
        var flag = 1; //定义flag=2代表url添加操作
        var type='shop_group';
        var top=$("#all_area").offset().top-200;
        var shop_group=true;
        art.dialog.open(
            '<?php echo Yii::app()->createUrl('mCenter/DshopMallHome/dialogHeader');?>'+'?index='+index+'&flag='+flag+'&type='+type+'&shop_group='+shop_group,
            {
                title: '',
//                lock: true,
                drag:true,
                width: '785px',
//                height: '335px',
                top:top,
                id: 'dialog_notice',
                background: '#fff'
            }
        );
    }

    /**
     * 轮播图
     */
    function shop_carousel(obj,type)
    {
        var num=shop_carousel_add_num;
        if(type)
        {
            //添加在最下面
            $('#areaWrap').append('<div class="area bgWhite" id="shop_carousel'+num+'">'+
            '<div class="aContent">'+
            '<div class="custom-image-swiper">'+
            '<img src="<?php echo GJ_STATIC_IMAGES?>shop_carousel.png">'+
            '</div>'+
            '</div>'+
            '<div class="actions">'+
            '<div class="actionbtn">'+
//            '<a href="#" class="add">添加内容</a>'+
            '<a href="javascript:;" class="del" onclick="del_shop_carousel_Item(this,'+num+')">删除</a>'+
            '</div>'+
            '</div>'+
            '</div>');
            window.parent.autoResize('m');
            var body_height=$('body').height();
            body_height=body_height+200;
            window.parent.autoResize('main',body_height);
            //当前选中
            select_left_id='shop_carousel'+num;
            //新建数组
            shop_carousel_img_arr[num]=new Array();
            shop_carousel_img_arr[num][0]='null';
            shop_carousel_img_arr[num][1]='null';
            shop_carousel_img_arr[num][2]='null';
            shop_carousel_img_arr[num][3]='null';
            shop_carousel_href_arr[num]=new Array();
            shop_carousel_href_arr[num][0]='null';
            shop_carousel_href_arr[num][1]='null';
            shop_carousel_href_arr[num][2]='null';
            shop_carousel_href_arr[num][3]='null';
            //右边清空
            //显示上传图片，隐藏重新上传
            for(var hide_i=1;hide_i<5;hide_i++)
            {
                $("#shop_carousel_add"+hide_i).hide();
                $('#shop_carousel_img_add'+hide_i).prev().attr('src','');
//                $('#shop_carousel_img_add'+hide_i).show();
                $('#shop_carousel_img_reload'+hide_i).hide();
                $("#shop_carousel_img_add"+hide_i).parent().next().find('dt.inlineBlock').next().hide();
                $("#shop_carousel_img_add"+hide_i).parent().next().find('dt.inlineBlock').next().next().show();
            }
            //左边变成选中状态
            $('#areaWrap .area').removeClass('editing');
            $('#shop_carousel'+num).addClass('editing');
            //右边所有隐藏
            $('.SER_block').hide();
            //右边添加轮播图显示出来
            $('#all_area').show();
            $('#shop_carousel_edit').show();
            //显示保存按钮
            $('#img_nav_btn').show();
        }


        shop_carousel_add_num++;
//        $("#areaWrap").dragsort("destory");
        var select_top=$('#'+select_left_id).offset().top-200;
        $('#all_area').css('top',select_top);
    }

    $(document).on('click','#add_shop_carousel',function(){
        var count=0;
        $('#shop_carousel_edit .htcon').each(function(){
            if($(this).parent().is(':visible'))
            {
                count++;
            }
        });
        if(count==4)
        {
            alert('最多添加四张轮播图');
            return false;
        }
        var num=0;
        $('#shop_carousel_edit .htcon').each(function(){
            num++;
            if($(this).parent().is(':hidden'))
            {
                $(this).parent().show();
                return false;
            }
        });
        $('#shop_carousel_img_add'+num).uploadify({
            onInit: function () {$(".uploadify-queue").hide();},//载入时触发，将flash设置到最小
            uploader: '<?php echo UPLOAD_TO_PATH?>',// 服务器处理地址
            swf: '<?php echo GJ_STATIC_JS?>'+'uploadify/uploadify.swf',
            buttonText: "点击上传",//按钮文字
            height: 70,  //按钮高度
            width: 140, //按钮宽度
            fileTypeExts: "<?php echo UPLOAD_IMG_TYPE;?>",//允许的文件类型
            fileTypeDesc: "请选择图片文件", //文件说明
            formData: { 'folder' : '<?php echo(IMG_GJ_FOLDER)?>', 'thumb' : '<?php echo IMG_GJ_THUMB_600?>'}, //提交给服务器端的参数
            onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
                eval("var jsondata = " + data + ";");
                var key = jsondata['key'];
                var fileName = jsondata['fileName'];
                $('#shop_carousel_img_reload'+num).show();
                //显示重新上传
                $('#shop_carousel_img_reload'+num).uploadify({
                    onInit: function () {$(".uploadify-queue").hide();},//载入时触发，将flash设置到最小
                    uploader: '<?php echo UPLOAD_TO_PATH?>',// 服务器处理地址
                    swf: '<?php echo GJ_STATIC_JS?>'+'uploadify/uploadify.swf',
                    buttonText: "重新上传",//按钮文字
                    buttonClass:'shadow',
                    height: 35,  //按钮高度
                    width: 140, //按钮宽度
                    fileTypeExts: "<?php echo UPLOAD_IMG_TYPE;?>",//允许的文件类型
                    fileTypeDesc: "请选择图片文件", //文件说明
                    formData: { 'folder' : '<?php echo(IMG_GJ_FOLDER)?>', 'thumb' : '<?php echo IMG_GJ_THUMB_600?>'}, //提交给服务器端的参数
                    onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
                        eval("var jsondata = " + data + ";");
                        var key = jsondata['key'];
                        var fileName = jsondata['fileName'];

                        //显示重新上传
                        $('#shop_carousel_img_reload'+num).show();
                        $('#shop_carousel_img_reload'+num).addClass('shadow');

                        $('#shop_carousel_img_add'+num).prev().attr('src','<?php echo IMG_GJ_LIST?>'+fileName);
                        //将图片添加到左边
                        $('#'+select_left_id).find('img').attr('src','<?php echo IMG_GJ_LIST?>'+fileName);
                        //将图片路径添加到数组里面
                        var nav_num=select_left_id.substring(13,select_left_id.length);
                        shop_carousel_img_arr[nav_num][num-1]=fileName;
                    }
                });
                $('#shop_carousel_img_reload'+num).addClass('shadow');
                $('#shop_carousel_img_add'+num).prev().attr('src','<?php echo IMG_GJ_LIST?>'+fileName);
//                $('#shop_carousel_img_add'+num).parent().find('.shadow').show();//显示重新上传
                //将图片添加到左边
                $('#'+select_left_id).find('img').attr('src','<?php echo IMG_GJ_LIST?>'+fileName);
                //将图片路径添加到数组里面
                var nav_num=select_left_id.substring(13,select_left_id.length);
                shop_carousel_img_arr[nav_num][num-1]=fileName;                
            }
        });
//        window.parent.autoResize('m');
//        var body_height=$('body').height();
//        body_height=body_height+200;
//        window.parent.autoResize('main',body_height);
//        window.parent.callParAutoResize("main",$("body").height());
        var all_area_top=$('#all_area').offset().top-200;
        var all_area_top_int=parseInt(all_area_top.substring(0,all_area_top.length));
        //$('#all_area').css('top',(all_area_top_int-115)+'px');
        //添加图片加一
//        shop_carousel_img_add_num++;
        window.parent.autoResize('main',$('body').height()+100);
    });

    /**
     * 轮播图添加链接
     */
    function shop_carousel_add_href(obj,index)
    {
        var flag = 2; //定义flag=2代表url添加操作
        var type='shop_carousel';
        var top=$("#all_area").offset().top-200;
        art.dialog.open(
            '<?php echo Yii::app()->createUrl('mCenter/DshopMallHome/dialogHeader');?>'+'?index='+index+'&flag='+flag+'&type='+type+'&shop_group=false',
            {
                title: '',
//                lock: true,
                drag:true,
                width: '785px',
//                height: '335px',
                top:top,
                id: 'dialog_notice',
                background: '#fff'
            }
        );
    }

    /**
     *轮播图编辑链接
     */
    function shop_carousel_edit_href(obj,index)
    {
        var flag = 1; //定义flag=2代表url添加操作
        var type='shop_carousel';
        var top=$("#all_area").offset().top-200;
        art.dialog.open(
            '<?php echo Yii::app()->createUrl('mCenter/DshopMallHome/dialogHeader');?>'+'?index='+index+'&flag='+flag+'&type='+type+'&shop_group=false',
            {
                title: '',
//                lock: true,
                drag:true,
                width: '785px',
//                height: '335px',
                top:top,
                id: 'dialog_notice',
                background: '#fff'
            }
        );
    }

    /**
     * 轮播图删除一个图片链接
     */
    function shop_carousel_href_del(obj,index)
    {
        if(!confirm('确认要删除吗?'))
            return false;

        //去掉静态页面上的文字
        $(obj).parent().parent().hide();
        $(obj).parent().parent().next().show();
        //删除图片导航链接对应的数组
        var num=select_left_id.substring(13,select_left_id.length);
        shop_carousel_href_arr[num][index-1]='null';//id和分组名用分号隔开
    }

    /**
     * 删除整个轮播图
     */
    function del_shop_carousel_Item(obj,index)
    {
        if(!confirm('确定要删除吗?'))
            return false;
        $(obj).parent().parent().parent().remove();
        //清除数组中对应的内容
        for (var shop_carousel_i = 0; shop_carousel_i < 4; shop_carousel_i++)
        {
            shop_carousel_img_arr[index][shop_carousel_i]='null';
            shop_carousel_href_arr[index][shop_carousel_i]='null';
        }
        //清空右边静态页面
        $('#shop_carousel_edit').find('img').attr('src','');
        $('#shop_carousel_edit').find('dt.inlineBlock').next().hide();
        $('#shop_carousel_edit').find('dt.inlineBlock').next().next().show();
        var shop_carousel_num=0;
        $('#shop_carousel_edit').find('.bgBlue').each(function(){
            var img_nav_html='<a href="javascript:;" class="close" onclick="shop_carousel_href_del(this,'+shop_carousel_num+')"></a>';
            $(this).html(img_nav_html);
            shop_carousel_num++;
        });
        $('#shop_carousel_edit').hide();
        $('#img_nav_btn').hide();
        $('#all_area').hide();
//        $("#areaWrap").dragsort("destory");
        window.parent.autoResize('main',$('body').height()-130);
    }

    /**
     *删除一行轮播图
     */
    function shop_carousel_del(obj,index)
    {
        if(!confirm('确认要删除吗?'))
            return false;
        //判断当前要删除的是第几行
        var row=$(obj).next().val();
        //将右边图片地址清除
        $(obj).parent().find('img').attr('src','');
        //将左边的图片地址清除
//        $('#'+select_left_id).find('img').attr('src','');
        //将数组中的图片地址清除
        var shop_carousel_num=select_left_id.substring(13,select_left_id.length);
        shop_carousel_img_arr[shop_carousel_num][row-1]='null';
        shop_carousel_href_arr[shop_carousel_num][row-1]='null';
        //将分组链接文字删除
        var href_html='<a href="javascript:;" class="close" onclick="shop_carousel_href_del(this,'+index+')"></a>';
        $('#shop_carousel_add'+row).find('.bgBlue').html(href_html);
        //隐藏链接，显示添加链接
        $(obj).parent().find('dt.inlineBlock').next().hide();
        $(obj).parent().find('dt.inlineBlock').next().next().show();
        //判断有几行是显示的
        var count=0;
        $('#shop_carousel_add .htcon').each(function(){
            if($(this).parent().is(':visible'))
            {
                count++;
            }
        });
        //删除一个图片导航
        $(obj).parent().hide();
        //将重新上传隐藏起来
        $('#shop_carousel_img_reload'+row).find('a').hide();
        //如果是删除中间的广告条，对数组进行挪动，左边的图片进行挪位,
        //先循环遍历右边四个广告条，将数组输出到广告条上，数组为'null'
        //就对这个广告条至空，左边也一样
        //数组往前走一位
        for(var n=0;n<4;n++)
        {
            //图片链接往前走一位
            if(shop_carousel_img_arr[shop_carousel_num][n]=='null'&&n!=3)
            {
                //如果图片链接为空
                for(var m=n;m<4;m++)
                {
                    //后续的数组往前走一位
                    if(m!=3)
                        shop_carousel_img_arr[shop_carousel_num][m]=shop_carousel_img_arr[shop_carousel_num][m+1];
                    else
                        shop_carousel_img_arr[shop_carousel_num][m]='null';
                }

            }

            //分组链接往前走一位
            if(shop_carousel_href_arr[shop_carousel_num][n]=='null'&&n!=3)
            {
                //如果图片链接为空
                for(var k=n;k<4;k++)
                {
                    //后续的数组往前走一位
                    if(k!=3)
                        shop_carousel_href_arr[shop_carousel_num][k]=shop_carousel_href_arr[shop_carousel_num][k+1];
                    else
                        shop_carousel_href_arr[shop_carousel_num][k]='null';
                }
            }
        }
        var nav=1;//当前需要编辑的广告条
        for(var i=0;i<4;i++)
        {
            var flag=false;//防止同时操作
            if(shop_carousel_img_arr[shop_carousel_num][i]!='null'&&shop_carousel_img_arr[shop_carousel_num][i]!=undefined)
            {
                //右边处理
                $('#shop_carousel_add'+nav).show();
                $('#shop_carousel_add'+nav).find('img').attr('src','<?php echo IMG_GJ_LIST?>'+shop_carousel_img_arr[shop_carousel_num][i]);
                $('#shop_carousel_add'+nav).find('a').show();
                //左边处理
//                $('#'+select_left_id).find('img').attr('src','<?php //echo IMG_GJ_LIST?>//'+shop_carousel_img_arr[shop_carousel_num-1][i]);

                flag=true;
            }
            if(shop_carousel_href_arr[shop_carousel_num][i]!='null'&&shop_carousel_href_arr[shop_carousel_num][i]!=undefined)
            {
                $('#shop_carousel_add'+nav).show();
                var str=shop_carousel_href_arr[shop_carousel_num][i].split(';');
                var right_html='<a href="javascript:;" class="close" onclick="shop_carousel_href_del(this,'+nav+')"></a>';
                var shop_type_name='';
                if(str[2] == <?php echo SHOP_TYPE_GROUP ?>){
                    shop_type_name = '商品分组';
                }else{
                    shop_type_name = '商品';
                }
                $('#shop_carousel_add'+nav).find('.bgBlue').html(shop_type_name+'|'+str[1]+right_html);
                $('#shop_carousel_add'+nav).find('.bgBlue').parent().show();
                $('#shop_carousel_add'+nav).find('.bgBlue').parent().next().hide();

                flag=true;
            }

            if(flag)
                nav++;
        }
//        //对右边进行删除
        $('#shop_carousel_add'+nav).hide();
        $('#shop_carousel_add'+nav).find('img').attr('src','');
        $('#shop_carousel_img_reload'+nav).hide();
        var href_html_del='<a href="javascript:;" class="close" onclick="shop_carousel_href_del(this,'+nav+')"></a>';
        $('#shop_carousel_add'+nav).find('.bgBlue').html(href_html_del);
        $('#shop_carousel_add'+nav).find('dt.inlineBlock').next().hide();
        $('#shop_carousel_add'+nav).find('dt.inlineBlock').next().next().show();
        //对左边进行删除
//        $('#'+select_left_id).find('img').attr('src','');
        //添加图片减一
//        img_nav_add_num--;
    }

    /**
     * 添加一个关注微信
     */
    function qcord(obj,type)
    {
        var num=qcord_num;
        if(type)
        {
            //添加在下面
            $('#areaWrap').append('<div class="area bgWhite" id="qcord'+num+'">'+
            '<div class="aContent">'+
            '<div class="custom-contact cc-two clearfix" style="background: url(<?php echo GJ_STATIC_IMAGES?>32_02.png) no-repeat 50% 50% / 100% 100%">'+
            '<div class="cc-li">'+
            '<img src="<?php echo GJ_STATIC_IMAGES?>qcord_defalut.png">'+
            '<p></p>'+
            ' </div>'+
            '<div class="cc-li">'+
            '<img src="<?php echo GJ_STATIC_IMAGES?>img_0350.png">'+
            '<p></p>'+
            '</div>'+
            ' </div>'+
            '</div>'+
            '<div class="actions">'+
            '<div class="actionbtn">'+
//            '<a href="#" class="add">添加内容</a>'+
            '<a href="javascript:;" class="del" onclick="qcord_del_Item(this,'+num+')">删除</a>'+
            '</div>'+
            '</div>'+
            '</div>');
        }
        //初始化数组
        qcord_arr[num]=new Array();
        qcord_arr[num][0]='null';//背景图片
        qcord_arr[num][1]='null';//二维码图片
        qcord_arr[num][2]='null';//二维码文字
        qcord_arr[num][3]='null';//电话号码
        //清空右边背景图片
        $('#qcord_bg_img_add').prev().attr('src','');
        //隐藏背景重新上传
        $('#qcord_bg_img_reload').hide();
        //清空二维码图片
        $("#qcord_img_add").prev().attr('src','');
        //隐藏二维码重新上传
        $('#qcord_img_reload').hide();
        //清空二维码文字
        $('#qcord_gz_txt').val('');
        //清空电话号码
        $('#qcord_num_txt').val('');
        //左边变成选中状态
        $('#areaWrap .area').removeClass('editing');
        $('#qcord'+num).addClass('editing');
        //当前选中
        select_left_id='qcord'+num;
        //右边所有隐藏
        $('.SER_block').hide();
        //右边添加轮播图显示出来
        $('#all_area').show();
        $('#qcord_edit').show();
        //显示保存按钮
        $('#img_nav_btn').show();

        var body_height=$('body').height();
        body_height=body_height+140;
        var inner_height=$('.kkfm_r_inner').height();
        $('.kkfm_r_inner').height(body_height);

        qcord_num++;
//        $("#areaWrap").dragsort("destory");
        var select_top=$('#'+select_left_id).offset().top-200;
        $('#all_area').css('top',select_top);
        window.parent.autoResize('main',body_height);
    }

    /**
     * 删除整个关注微信
     */
    function qcord_del_Item(obj,num)
    {
        if(!confirm("确认要删除吗?"))
            return false;
        //删除数组内容
        qcord_arr[num][0]='null';
        qcord_arr[num][1]='null';
        qcord_arr[num][2]='null';
        qcord_arr[num][3]='null';
        $(obj).parent().parent().parent().remove();
        //清空右边的内容
        $('.SER_block').hide();
        $('#img_nav_btn').hide();
        $('#qcord_edit').find('img').attr('src','');
        $('#qcord_edit').find('input').val('');
        $('#all_area').hide();
        //img_adv_num--;
//        $("#areaWrap").dragsort("destory");
        window.parent.autoResize('main',$('body').height()-140);
    }

    /**
     * 微信背景图图片上传
     */
    $('#qcord_bg_img_add').uploadify({
        onInit: function () {$(".uploadify-queue").hide();},//载入时触发，将flash设置到最小
        uploader: '<?php echo UPLOAD_TO_PATH?>',// 服务器处理地址
        swf: '<?php echo GJ_STATIC_JS?>'+'uploadify/uploadify.swf',
        buttonText: "点击上传",//按钮文字
        height: 70,  //按钮高度
        width: 140, //按钮宽度
        fileTypeExts: "<?php echo UPLOAD_IMG_TYPE;?>",//允许的文件类型
        fileTypeDesc: "请选择图片文件", //文件说明
        formData: { 'folder' : '<?php echo(IMG_GJ_FOLDER)?>'}, //提交给服务器端的参数
        onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
            eval("var jsondata = " + data + ";");
            var key = jsondata['key'];
            var fileName = jsondata['fileName'];
            $('#qcord_bg_img_reload').uploadify({
                onInit: function () {$(".uploadify-queue").hide();},//载入时触发，将flash设置到最小
                uploader: '<?php echo UPLOAD_TO_PATH?>',// 服务器处理地址
                swf: '<?php echo GJ_STATIC_JS?>'+'uploadify/uploadify.swf',
                buttonText: "重新上传",//按钮文字
                buttonClass:'shadow',
                height: 35,  //按钮高度
                width: 140, //按钮宽度
                fileTypeExts: "<?php echo UPLOAD_IMG_TYPE;?>",//允许的文件类型
                fileTypeDesc: "请选择图片文件", //文件说明
                formData: { 'folder' : '<?php echo(IMG_GJ_FOLDER)?>'}, //提交给服务器端的参数
                onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
                    eval("var jsondata = " + data + ";");
                    var key = jsondata['key'];
                    var fileName = jsondata['fileName'];
                    $("#qcord_bg_img_reload").show();
                    $("#qcord_bg_img_reload").find('.shadow').show();
                    $("#qcord_bg_img_reload").addClass('shadow');
                    //将图片显示在右边
                    $('#qcord_edit').children().eq(0).find('img').attr('src','<?php echo IMG_GJ_LIST?>'+fileName);
                    //将图片显示在左边
                    $('#'+select_left_id).find('.custom-contact').css('background-image',"url(<?php echo IMG_GJ_LIST?>"+fileName+")");
                    //将图片添加到图片广告数组
                    var num=select_left_id.substring(5,select_left_id.length);
                    qcord_arr[num][0]=fileName;
                }
            });
            //显示重新上传
            $("#qcord_bg_img_reload").show();
            $("#qcord_bg_img_reload").find('.shadow').show();
            $("#qcord_bg_img_reload").addClass('shadow');
            //将图片显示在右边
            $('#qcord_edit').children().eq(0).find('img').attr('src','<?php echo IMG_GJ_LIST?>'+fileName);
            //将图片显示在左边
            $('#'+select_left_id).find('.custom-contact').css('background-image',"url(<?php echo IMG_GJ_LIST?>"+fileName+")");
            //将图片添加到图片广告数组
            var num=select_left_id.substring(5,select_left_id.length);
            qcord_arr[num][0]=fileName;
        }
    });

    /**
     * 微信二维码上传
     */
    $('#qcord_img_add').uploadify({
        onInit: function () {$(".uploadify-queue").hide();},//载入时触发，将flash设置到最小
        uploader: '<?php echo UPLOAD_TO_PATH?>',// 服务器处理地址
        swf: '<?php echo GJ_STATIC_JS?>'+'uploadify/uploadify.swf',
        buttonText: "点击上传",//按钮文字
        height: 70,  //按钮高度
        width: 140, //按钮宽度
        fileTypeExts: "<?php echo UPLOAD_IMG_TYPE;?>",//允许的文件类型
        fileTypeDesc: "请选择图片文件", //文件说明
        formData: { 'folder' : '<?php echo(IMG_GJ_FOLDER)?>'}, //提交给服务器端的参数
        onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
            eval("var jsondata = " + data + ";");
            var key = jsondata['key'];
            var fileName = jsondata['fileName'];
            $('#qcord_img_reload').uploadify({
                onInit: function () {$(".uploadify-queue").hide();},//载入时触发，将flash设置到最小
                uploader: '<?php echo UPLOAD_TO_PATH?>',// 服务器处理地址
                swf: '<?php echo GJ_STATIC_JS?>'+'uploadify/uploadify.swf',
                buttonText: "重新上传",//按钮文字
                buttonClass:'shadow',
                height: 35,  //按钮高度
                width: 140, //按钮宽度
                fileTypeExts: "<?php echo UPLOAD_IMG_TYPE;?>",//允许的文件类型
                fileTypeDesc: "请选择图片文件", //文件说明
                formData: { 'folder' : '<?php echo(IMG_GJ_FOLDER)?>'}, //提交给服务器端的参数
                onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
                    eval("var jsondata = " + data + ";");
                    var key = jsondata['key'];
                    var fileName = jsondata['fileName'];
                    $("#qcord_img_reload").show();
                    $("#qcord_img_reload").find('.shadow').show();
                    $("#qcord_img_reload").addClass('shadow');
                    //将图片显示在右边
                    $('#qcord_edit').children().eq(1).find('img').attr('src','<?php echo IMG_GJ_LIST?>'+fileName);
                    //将图片显示在左边
                    $('#'+select_left_id).find('.cc-li').eq(0).find('img').attr('src','<?php echo IMG_GJ_LIST?>'+fileName);
                    //将图片添加到图片广告数组
                    var num=select_left_id.substring(5,select_left_id.length);
                    qcord_arr[num][1]=fileName;
                }
            });
            //显示重新上传
            $("#qcord_img_reload").show();
            $("#qcord_img_reload").find('.shadow').show();
            $("#qcord_img_reload").addClass('shadow');
            //将图片显示在右边
            $('#qcord_edit').children().eq(1).find('img').attr('src','<?php echo IMG_GJ_LIST?>'+fileName);
            //将图片显示在左边
            $('#'+select_left_id).find('.cc-li').eq(0).find('img').attr('src','<?php echo IMG_GJ_LIST?>'+fileName);
            //将图片添加到图片广告数组
            var num=select_left_id.substring(5,select_left_id.length);
            qcord_arr[num][1]=fileName;
        }
    });

    /**
     * 二维码文字
     */
    $('#qcord_gz_txt').blur(function(){
        var str="";
        str=$(this).val();
        var num=select_left_id.substring(5,select_left_id.length);
        if(str!="")
        {
            //设置数组文字
            qcord_arr[num][2]=str;
            //设置右边显示
            $('#'+select_left_id).find('.cc-li').eq(0).find('p').text(str);
        }
        else if(str=="")
        {
            //设置数组文字
            qcord_arr[num][2]="";
            //设置右边显示
            $('#'+select_left_id).find('.cc-li').eq(0).find('p').text("");
        }
    });

    /**
     * 微信电话号码
     */
    $('#qcord_num_txt').blur(function(){
        var tel="";
        tel=$(this).val();
        var num=select_left_id.substring(5,select_left_id.length);
        if(tel!="")
        {
            //设置数组文字
            qcord_arr[num][3]=tel;
            //设置右边显示
            $('#'+select_left_id).find('.cc-li').eq(1).find('p').text(tel);
        }
        else if(tel=="")
        {
            //设置数组文字
            qcord_arr[num][3]="";
            //设置右边显示
            $('#'+select_left_id).find('.cc-li').eq(1).find('p').text("");
        }
    });


    /**
     * 目录链接添加
     */
    function catalog(obj,type)
    {
        var num=catalog_num;
        if(type)
        {
            $('#areaWrap').append('<div class="area bgWhite" id="catalog'+num+'">'+
            '<div class="aContent">'+
            '<ul class="custom-catalog">'+
            '<li>'+
            '<h2 id="left_first_title'+num+'">一级标题 <em></em></h2>'+
            '<ul>'+
            '<li><h2 class="left_secord_title0">二级标题</h2><em></em></li>'+
            '</ul>'+
            '</li>'+
            '</ul>'+
            '</div>'+
            '<div class="actions">'+
            '<div class="actionbtn">'+
//            '<a href="#" class="add">添加内容</a>'+
            '<a href="javascript:;" class="del" onclick="catalog_del_Item(this,'+num+')">删除</a>'+
            '</div>'+
            '</div>'+
            '</div>');
        }

        //初始化数组
        catalog_arr[num]=new Array();
        catalog_arr[num][0]=new Array();
        catalog_arr[num][0][0]='一级标题';
        catalog_arr[num][0][1]='null';
        catalog_arr[num][1]=new Array();
        catalog_arr[num][1][0]='二级标题';
        catalog_arr[num][1][1]='null';
        //左边变成选中状态
        $('#areaWrap .area').removeClass('editing');
        $('#catalog'+num).addClass('editing');
        //当前选中
        select_left_id='catalog'+num;
        //右边清空
        $('#first_title').val("一级标题");
        //删除其余的二级标题
        $('.catalog-list').children().remove();
        $('.catalog-list').append('<div class="bcgItem clearfix">'+
        '<input type="text" class="txt" value="二级标题" placeholder="二级标题">'+
        '<input class="catalog_count_title" value="0" style="display: none">'+
        '<a href="javascript:;" onclick="catalog_title_del(this,0)" class="close"></a>'+
        '</div>');
        //清空UE
        ue.setContent("");
        //右边所有隐藏
        $('.SER_block').hide();
        //右边添加显示出来
        $('#all_area').show();
        $('#catalog_edit').show();
        //显示保存按钮
        $('#img_nav_btn').show();
        var body_height=$('body').height();
        body_height=body_height+250;
        var inner_height=$('.kkfm_r_inner').height();
        $('.kkfm_r_inner').height(body_height);

//        $("#areaWrap").dragsort("destory");
        var select_top=$('#'+select_left_id).offset().top-200;
        $('#all_area').css('top',select_top);
        window.parent.autoResize('main',body_height);
        $('#edui1_wordcount').hide();
        catalog_num++;

    }

    /**
     * 删除二级标题
     */
    function catalog_title_del(obj,index)
    {
        if(!confirm("确认要删除吗?"))
            return false;
        //遍历一遍，如果只有一个二级标题就不删除
        if($(obj).parent().parent().children().length==1)
        {
            alert('至少要有一个二级标题!');
            return true;
        }
        else
        {
            //删除数组元素
            var num=select_left_id.substring(7,select_left_id.length);
            //删除数组元素
            catalog_arr[num].splice(index+1,1);
            //删除右边当前行
            $(obj).parent().remove();
            //删除左边当前行
            $('#catalog'+num).find('.left_secord_title'+index).parent().remove();
            //右边数字往前移一位
            var reset_i=0;
            $('#catalog-wrap').find('.bcgItem').each(function(){
                $(this).find('.catalog_count_title').val(reset_i);
                $(this).find('a').remove();
                $(this).append('<a href="javascript:;" onclick="catalog_title_del(this,'+reset_i+')" class="close"></a>');
                reset_i++;
            });

            //左边数字往前移一位
            var reset_j=0;
            $('#left_first_title'+num).next().children().each(function(){
                $(this).html("");
                $(this).html('<h2 class="left_secord_title'+reset_j+'">'+catalog_arr[num][reset_j+1][0]+'</h2><em></em>');
                reset_j++;
            });
            //取消二级标题选择
            catalog_title_select=-1;
//            $("#areaWrap").dragsort("destory");
        }
    }

    /**
     * 添加一个二级标题
     */
    $('#add_catalog').click(function(){
        var num=select_left_id.substring(7,select_left_id.length);
        var secord_title_num=$("#catalog-wrap").find('.catalog-list').children().length;//二级标题个数
        //右边添加
        $("#catalog-wrap").find('.catalog-list').append('<div class="bcgItem clearfix">'+
        '<input type="text" class="txt" value="二级标题" placeholder="二级标题">'+
        '<input class="catalog_count_title" value='+secord_title_num+' style="display: none">'+
        '<a href="javascript:;" onclick="catalog_title_del(this,'+secord_title_num+')" class="close"></a>'+
        '</div>');
        //左边添加
        $('#left_first_title'+num).next().append('<li><h2 class="left_secord_title'+secord_title_num+'">二级标题</h2><em></em></li>');
//        $("#areaWrap").dragsort("destory");
        //初始化第二个数组
        catalog_arr[num][secord_title_num+1]=new Array();
        catalog_arr[num][secord_title_num+1][0]='二级标题';
        catalog_arr[num][secord_title_num+1][1]='null';
        window.parent.autoResize('main',$('body').height()+100);
    });


    /**
     * 一级标题失去焦点
     */
    $('#first_title').blur(function(){
        var str="";
        if($(this).val()=="") {
            str = "一级标题";
            $(this).val("一级标题");
        }
        else
        {
            str=$(this).val();
        }
        var num=select_left_id.substring(7,select_left_id.length);
        $('#'+select_left_id).find('#left_first_title'+num).html(str+" <em></em>");
        //给数组赋值
        catalog_arr[num][0][0]=str;
    });

    /**
     * 二级标题失去焦点
     */
    $(document).on('blur','.catalog-list .txt',function(){
        var str="";
        if($(this).val()=="") {
            str = "二级标题";
            $(this).val("二级标题");
        }
        else
        {
            str=$(this).val();
        }
        var title_num=$(this).next().val();
        var num=select_left_id.substring(7,select_left_id.length);
        $('#'+select_left_id).find('.left_secord_title'+title_num).html(str);
        //给数组赋值
        catalog_arr[num][parseInt(title_num)+1][0]=str;
    });

    /**
     * 二级标题点击
     */
    $(document).on('click','.catalog-list .bcgItem',function(e){
        //获取当前市第几个二级标题
        var index=$(this).find('.catalog_count_title').val();
        var num=select_left_id.substring(7,select_left_id.length);
        if(catalog_arr[num][parseInt(index)+1][1]!='null')
        {
            //设置富文本内容
            ue.setContent(catalog_arr[num][parseInt(index)+1][1]);

        }
        else
        {
            ue.setContent("");
        }
        catalog_title_select=parseInt(index);
    });

    /**
     * 富文本编辑器失去焦点
     */
    ue.addListener('blur',function(){
        if(catalog_title_select!=-1)
        {
            //不等于-1说明有二级标题被点击了
            var num=select_left_id.substring(7,select_left_id.length);
            catalog_arr[num][catalog_title_select+1][1]=ue.getContent();
        }
    });

    /**
     * 删除整个目录链接
     */
    function catalog_del_Item(obj,index)
    {
        if(!confirm("确定要删除吗?"))
            return false;
        //数组清空
        catalog_arr[index]='null';
        //当前静态页面移除
        $(obj).parent().parent().parent().remove();
        //清空右边的内容
        $('.SER_block').hide();
        $('#img_nav_btn').hide();
        //右边清空
        $('#first_title').val("一级标题");
        //删除其余的二级标题
        $('.catalog-list').children().remove();
        $('.catalog-list').append('<div class="bcgItem clearfix">'+
        '<input type="text" class="txt" value="二级标题" placeholder="二级标题">'+
        '<input class="catalog_count_title" value="0" style="display: none">'+
        '<a href="javascript:;" onclick="catalog_title_del(this,0)" class="close"></a>'+
        '</div>');
        //清空UE
        ue.setContent("");
        //重置二级标题选择的
        catalog_title_select=-1;
        $('#all_area').hide();
        //img_adv_num--;
//        $("#areaWrap").dragsort("destory");
        $('.kkfm_r_inner').height($('body').height());
        window.parent.autoResize('main',$('body').height()-250);

    }

    /**
     * 保存
     */
    $('#img_nav_btn').click(function(e){

        //可以保存,遍历左边，得出各个组件的顺序
        var order=new Array();
        var order_test=new Array();
        var order_count=0;
        $('#areaWrap').find('.area').each(function(){

            var top_px=$(this).css('top');
            order_test[order_count]=new Array();
            order_test[order_count][0]=top_px.substring(0,top_px.length-2);
            if($(this).attr('id').indexOf('img_nav')>-1)
            {
                var img_nav_id=$(this).attr('id');
                var img_nav_index=img_nav_id.substring(7,img_nav_id.length);
                order_test[order_count][1]='img_nav;'+img_nav_index;//图片导航
            }
            else if($(this).attr('id').indexOf('img_adv')>-1)
            {
                var img_adv_id=$(this).attr('id');
                var img_adv_index=img_adv_id.substring(7,img_adv_id.length);
                order_test[order_count][1]='img_adv;'+img_adv_index;//图片广告
            }
            else if($(this).attr('id').indexOf('shop_search')>-1)
            {
                var shop_search_id=$(this).attr('id');
                var shop_search_index=shop_search_id.substring(11,shop_search_id.length);
                order_test[order_count][1]='shop_search;'+shop_search_index;//搜索
            }
            else if($(this).attr('id').indexOf('shop_carousel')>-1)
            {
                var shop_carousel_id=$(this).attr('id');
                var shop_carousel_index=shop_carousel_id.substring(13,shop_carousel_id.length);
                order_test[order_count][1]='shop_carousel;'+shop_carousel_index;//轮播图
            }
            else if($(this).attr('id').indexOf('shop_group')>-1)
            {
                var shop_group_id=$(this).attr('id');
                var shop_group_index=shop_group_id.substring(10,shop_group_id.length);
                order_test[order_count][1]='shop_group;'+shop_group_index;//商品分组
            }
            else if($(this).attr('id').indexOf('qcord')>-1)
            {
                var qcord_id=$(this).attr('id');
                var qcord_index=qcord_id.substring(5,qcord_id.length);
                order_test[order_count][1]='qcord;'+qcord_index;//关注微信
            }
            else if($(this).attr('id').indexOf('catalog')>-1)
            {
                var catalog_id=$(this).attr('id');
                var catalog_index=catalog_id.substring(7,catalog_id.length);
                order_test[order_count][1]='catalog;'+catalog_index;//目录链接
            }

            order_count++;
        });
        //冒泡排序
        var pop_i_temp=0;
        var pop_j_temp=0;
        for(var pop_i=0;pop_i<order_count-1;pop_i++)
        {
            for(var pop_j=pop_i+1;pop_j<order_count;pop_j++)
            {
                pop_i_temp=parseInt(order_test[pop_i][0]);
                pop_j_temp=parseInt(order_test[pop_j][0]);
                if(pop_i_temp>pop_j_temp)
                {
                    var pop_int_temp=pop_j_temp;
                    var pop_id_temp=order_test[pop_j][1];
                    order_test[pop_j][0]=order_test[pop_i][0];
                    order_test[pop_j][1]=order_test[pop_i][1];
                    order_test[pop_i][0]=pop_int_temp;
                    order_test[pop_i][1]=pop_id_temp;
                }
            }
        }
        for(var put_i=0;put_i<order_count;put_i++)
        {
            order[put_i]=order_test[put_i][1];
        }
        if(img_nav_arr.length==0)
        {
            img_nav_arr='null';
        }

        if(img_nav_href_arr.length==0)
        {
            img_nav_href_arr='null';
        }

        if(img_adv_arr.length==0)
        {
            img_adv_arr='null';
        }

        if(img_adv_href_arr.length==0)
        {
            img_adv_href_arr='null';
        }

        if(shop_carousel_img_arr.length==0)
        {
            shop_carousel_img_arr='null';
        }

        if(shop_carousel_href_arr.length==0)
        {
            shop_carousel_href_arr='null';
        }

        if(shop_group_href_arr.length==0)
        {
            shop_group_href_arr='null';
        }
        if(qcord_arr.length==0)
        {
            qcord_arr='null';
        }
        if(catalog_arr.length==0)
        {
            catalog_arr='null';
        }
        //ajax保存数据
        $.ajax({
            url:'<?php echo Yii::app()->createUrl('mCenter/DshopMallHome/SaveShopMall')?>',
            data:{data_order:order,
                data_img_nav_arr:img_nav_arr,
                data_img_nav_href_arr:img_nav_href_arr,
                data_img_adv_arr:img_adv_arr,
                data_img_adv_href_arr:img_adv_href_arr,
                data_shop_carousel_img_arr:shop_carousel_img_arr,
                data_shop_carousel_href_arr:shop_carousel_href_arr,
                data_shop_group_href_arr:shop_group_href_arr,
                data_qcord_arr:qcord_arr,
                data_catalog_arr:catalog_arr
            },
            type: 'post',
            async: false,
            success: function(data){
                if (data == 'success') {
                    alert('保存成功');
                    window.location.reload();
                }
                else {
                    alert('保存失败');
                }
            }
        });
//        alert(order);
    });
</script>
