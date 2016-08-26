<h4 class="g-title">优惠券详情</h4>
<div class="g-container">
    <div class="m-cp-step">
        <span class="f-step active">1、填写优惠券信息</span>
        <span class="s-step">2、功能设置</span>
    </div>
    <?php echo CHtml::beginForm('','post',array('id'=>'addForm')); ?>
    <div class="m-c-cp js-step-1" style="display: block">
        <div class="cp-l <?php echo $model['color']; ?> js-cp-l">
            <img src="<?php echo WQ_STATIC_IMAGES?>iphone.png" class="title-img">
            <div class="m-cp-preview">
                <div class="m-cp-preview-inner">
                    <div class="row row-cp-1">
                        <div class="col-sm-12 text-center">
                            <img src="<?php echo empty($merchant_logo)?'':IMG_GJ_LIST.$model['logo']?>" alt="" class="avatar">
                            <h5 class="store-name mt20" ><?php echo isset($merchant_short_name)?$merchant_short_name:$model['merchant_short_name']?></h5>
                        </div>
                        <div class="col-sm-12 text-center mt30">
                            <h2 class="text-black" id="left_title"><?php echo $model['title']?></h2>
                        </div>
                        <div class="col-sm-12 text-center mt10">
                            <h6 class="text-black" id="left_vice_title"><?php echo $model['vice_title']?></h6>
                        </div>
                        <div class="col-sm-12 text-center mt10">
                            <h5 class="btn-showuse <?php echo $model['color']?$model['color']:'Color082';?>">立即使用</h5>
                        </div>
                        <div class="col-sm-12 text-center mt15">
                            <h6 class="expiry-date">有效期：<label id="left_time">
                            
                      	<?php if($model['time_type'] == VALID_TIME_TYPE_FIXED){ ?>
                      	<?php echo str_replace("-","/",substr($model['start_time'],0,10)).'-'.str_replace("-","/",substr($model['end_time'],0,10))?>
                      	<?php }else{?>
                      	领取后，<?php echo $model['start_days'] == 0?"当":$model['start_days'];?>天生效，有效<?php echo $model['effective_days'];?>天
                      	<?php }?>
                            </label></h6>
                        </div>
                        <div class="col-sm-12 text-center mt15">
                            <h6 class="expiry-date">可用时段：<label id="left_available_time">

			                	<?php if(!empty($interval)){?>
				                	             <?php echo $interval;?>
				                	             <?php }else{?>
				                	             <?php echo '周一至周日'; ?>
				                <?php }?>   	
							</label></h6>
                            <?php if(!empty($hour)){?>
                            <?php echo $hour;?>
                            <?php }else{?>
                            <h6 class="expiry-date">可用时间：<label id="left_available_hour2" >全部时段</label></h6>
                            <?php }?>
                        </div>
                    </div>
                    <div class="row row-cp-2">
                        <!--<h5 class="entry-1">自定义入口1</h5>-->
                        <div class="cp-cover mt15">
                            <img id="left_cover_img" src="<?php echo !empty($model['cover_img'])?IMG_GJ_LIST.$model['cover_img']:''; ?>" alt="">
                            <h5 id="left_cover_title" class="title arrow-left"><?php echo !empty($model['cover_title'])?$model['cover_title']:'';?></h5>
                        </div>
                        <p class="arrow-left coupon-p text-black">适用门店</p>
                        <p class="arrow-left coupon-p text-black">公众号</p>
                        
                    </div>
                    <div class="row row-cp-3">
                        <p id="entrance1" class="arrow-left coupon-p text-black" >

                        <?php if (!empty($model_info['custom_url_name'])){?>
                        <?php echo $model_info['custom_url_name'];?>
                        <?php }else {?>
                        <?php echo '自定义入口'; ?>
                        <?php }?>

                        </p>
                        <p id="entrance2" class="arrow-left coupon-p text-black" >

                        <?php if (!empty($model_info['promotion_url_name'])){?>
                        <?php echo $model_info['promotion_url_name'];?>
                        <?php }else {?>
                        <?php echo '自定义入口'; ?>
                        <?php }?>

                        
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="cp-r">
            <section class="m-cp-item">
                <h5 class="title">基本信息</h5>
                <div class="form-group m-cp-info">
                    <div class="cp-title">
                        <span>商户<em>*</em></span>
                    </div>
                    <div class="cp-con">
                        <img src="<?php echo empty($model['logo'])?'':IMG_GJ_LIST.$model['logo']?>" alt="" class="store-image">
                        
                        <input type="text" readonly name="Coupons[merchant_short_name]" id="" value="<?php echo isset($model['merchant_short_name'])?$model['merchant_short_name']:''?>" class="form-control store-name" placeholder="商家名称" >
                        <p class="mt15 text-gray">如商户信息有变更，请在<a href="<?php echo Yii::app()->createUrl('mCenter/ShopManagement/OnlineShop')?>" class="text-blue">优惠券商户资料</a>更新</p>
                    </div>
                </div>
                <div class="form-group m-cp-info">
                    <div class="cp-title">
                        <span>优惠券类型<em>*</em></span>
                    </div>
                    <div class="cp-con js-cp-type">
                        <div class="radio-inline">
                            <label>
                                <input type="radio" checked> <?php if($model['type']==2){echo '代金券';}elseif ($model['type'] == 3){echo '折扣券';}elseif ($model['type'] == 4){echo '兑换券';};?>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group m-cp-info">
                    <div class="cp-title mt6">
                        <span>优惠券颜色<em>*</em></span>
                    </div>
                    <div class="cp-con">
                        <div class="m-cp-color ">


                                <div class="<?php echo $model['color'] ?> colorbox" id="selected-color"></div>


                        </div>
                    </div>
                </div>
                <div class="form-group m-cp-info">
                    <div class="cp-title mt6">
                        <span><i class="js-cp-title">代金券</i>标题<em>*</em></span>
                    </div>
                    <div class="cp-con">
                    	<?php echo CHtml::textField('Coupons[title]',$model['title'],array('class'=>'form-control fc-sm','readonly'=>'readonly','placeholder'=>'最多9个汉字','maxlength'=>18)); ?>
                        
                        <p class="mt10 text-gray">建议填写优惠券提供的服务或商品名称，描述卡券提供的具体优惠</p>
                    </div>
                </div>
                <div class="form-group m-cp-info">
                    <div class="cp-title ">
                        <span><i class="js-cp-title">代金券</i>副标题</span>
                    </div>
                    <div class="cp-con">
                    	<?php echo CHtml::textField('Coupons[vice_title]',empty($model['vice_title'])?'':$model['vice_title'],array('class'=>'form-control fc-sm','readonly'=>'readonly')); ?>
                    </div>
                </div>
                <div class="form-group m-cp-info">
                    <div class="cp-title ">
                        <span>有效时间<em>*</em></span>
                    </div>
                    <div class="cp-con js-effective-time">
                    <?php if($model['time_type'] == VALID_TIME_TYPE_FIXED){?>
                        <div class=" form-inline ">
                            <input type="text"  name="Coupons[time]"  value="<?php echo !empty($model['start_time'])&&!empty($model['start_time'])?str_replace("-","/",substr($model['start_time'],0,10)).'-'.str_replace("-","/",substr($model['end_time'],0,10)):'';?>" class="form-control fc-sm" readonly="readonly">
                        </div>    
                    <?php }else{?>    
                        <div class=" form-inline">
                            
                            <span class="ml10">领取后，<?php echo $model['start_days'] == 0?"当":$model['start_days'];?>天生效，有效<?php echo $model['effective_days'];?>天</span>
                            
                        </div>
                     <?php }?>
                    </div>
                    
                </div>
                <div class="form-group m-cp-info">
                    <div class="cp-title">
                        <span>可用时段<em>*</em></span>
                    </div>
                    <div class="cp-con js-available-time">
                    <?php if(empty($interval)){?>
                        <div class=" pl10 form-inline ">                   
                                		全部时段
                        </div>
                    <?php }else{?>
                        <div class="radio form-inline checkbox-no-pd">

                            <!-- 选择部分时段后才会显示-->
                            <div class="mt20 partial-time js-partial-time" >

                                <div class="form-inline mt20">

                                    <span class="pl10 "><?php echo $interval;?></span>
                                    <span class="pl10 ">
                                    <?php if(!empty($hour)){?>
                            		<?php echo $hour;?>
                            		<?php }?>
                                    </span>
                                </div>

                            </div>
                        </div>
                    <?php }?>
                    </div>
                </div>


                
                <?php if($model['type'] == 2){?>
                <div class="form-group m-cp-info ">
                    <div class="cp-title ">
                        <span>减免金额<em>*</em></span>
                    </div>
                    <div class="cp-con">
                        <span class="pl10 "><?php echo !empty($model['money'])?$model['money']:'';?></span>
                    </div>
                </div>
                <div class="form-group m-cp-info ">
                    <div class="cp-title ">
                        <span>减免条件<em>*</em></span>
                    </div>
                    <div class="cp-con">
                        <span class="pl10 "><?php echo !empty($model['mini_consumption'])?$model['mini_consumption']*100/100:'';?></span>
                    </div>
                </div>
                <?php }elseif ($model['type'] == 3){?>
                <div class="form-group m-cp-info ">
                    <div class="cp-title ">
                        <span>折扣额度<em>*</em></span>
                    </div>
                    <div class="cp-con">
                        <span class="pl10 "><?php echo !empty($model['discount'])?$model['discount']*100/100:'';?></span>
                    </div>
                </div>
                <div class="form-group m-cp-info ">
                    <div class="cp-title ">
                        <span>折扣条件<em>*</em></span>
                    </div>
                    <div class="cp-con">
                        <span class="pl10 "><?php echo !empty($model['mini_consumption'])?$model['mini_consumption']*100/100:'';?></span>
                    </div>
                </div>                    
                <?php }elseif ($model['type'] == 4){?>
                <div class="form-group m-cp-info ">
                    <div class="cp-title ">
                        <span>兑换条件<em>*</em></span>
                    </div>
                    <div class="cp-con">
                        <span class="pl10 "><?php echo !empty($model['mini_consumption'])?$model['mini_consumption']*100/100:'';?></span>
                    </div>
                </div>                   
                <?php }?>
                    
                

            </section>
            <section class="m-cp-item">
                <h5 class="title">服务信息</h5>
                <?php if($model['type'] != 4){?>
                <div class="form-group m-cp-info">
                    <div class="cp-title">
                        <span>使用条件</span>
                    </div>
                    <div class="cp-con">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" readonly name="Coupons[if_with_userdiscount]" 
		                        <?php if(isset($_POST['Coupons']['if_with_userdiscount'])){?>
		                        <?php echo 'checked';?>
		                        <?php }else{?>
		                        <?php if ($model['if_with_userdiscount'] == 2){?>
		                        <?php echo 'checked';?>
		                        <?php }else {?>
		                        <?php echo ''; ?>
		                        <?php }?>
		                        <?php }?>                                
                                >可与会员折扣同时使用
                            </label>
                        
                        </div>                         
                    </div>
                </div>
                <?php }?>
                <div class="form-group m-cp-info">
                    <div class="cp-title">
                        <span>使用须知</span>
                    </div>
                    <div class="cp-con">
                        <textarea class="form-control" name="Coupons[use_illustrate]" readonly placeholder="请填写使用本优惠劵的注意事项，最多300字" rows="5"></textarea>
                    </div>
                                             
                </div>
                <?php if($model['type'] == 4){?>
                <div class="form-group m-cp-info js-cp-exchange" >
                    <div class="cp-title">
                        <span>优惠详情<em>*</em></span>
                    </div>
                    <div class="cp-con">
                        <textarea class="form-control" name="Coupons[gift_info]" readonly placeholder="请填写兑换的具体内容，最多300字" rows="5"></textarea>
                    </div>
                                             
                </div>
                <?php }?>
                <div class="form-group m-cp-info">
                    <div class="cp-title">
                        <span>客服电话</span>
                    </div>
                    
                    <div class="cp-con">
                        <span class="pl10 "><?php echo empty($model['tel'])?'无':$model['tel'];?></span>
                    </div>
                    
                </div>
            </section>
            
            <section class="m-cp-item">
                <h5 class="title">优惠详情</h5>
                <?php if(!empty($model_info['abstract'])){?>
                <div class="form-group m-cp-info">
                    <div class="cp-title">
                        <span>封面图片</span>
                    </div>
                    <div class="cp-con">
                        <div class="m-upload">
                            <div class="upload-inner">
							<img src="<?php echo !empty($model_info['abstract']['cover_img'])?IMG_GJ_LIST.$model_info['abstract']['cover_img']:'';?>" alt="">
                            </div>
                        </div>
                        <p class="mt10 text-gray">图片建议尺寸：850像素*350像素，大小不超过1M）</p>
                    </div>
                </div>
                <div class="form-group m-cp-info">
                    <div class="cp-title mt6">
                        <span>封面简介</span>
                    </div>
                    <div class="cp-con">
                        <input type="text"  readonly value="<?php echo empty($model['cover_title'])?'':$model['cover_title']?>" class="form-control fc-sm">
                    </div>
                </div>
                <?php }?>
                <?php if(!empty($model_info['text_image_list'])){?>
                <div class="form-group m-cp-info">
                    <div class="cp-title">
                        <span>图文介绍</span>
                    </div>
                    <div class="cp-con">
                    	<?php foreach ($model_info['text_image_list'] as $k=>$v){?>
                    	
                        <div class="m-imgpost mb20">
                            <div class="m-upload">
                                <div class="upload-inner">
									<img src="<?php echo isset($v['image_url'])&&!empty($v['image_url'])?IMG_GJ_LIST.$v['image_url']:''?>"  alt="">
                                </div>
                            </div>
                            <?php echo isset($v['image_url'])&&!empty($v['image_url'])?'<script>$(function(){$("#img_txt'.$k.'").html("'.$v['text'].'");});</script>':'';?>
                            <textarea <?php echo 'id="img_txt'.$k.'"';?> class="form-control cover-desc" readonly placeholder="输入描述,最多5000字" rows="3"></textarea>
                        </div>
                        
                        <?php }?>
                    </div>
                </div>
                <?php }?>
            </section>
            
            <section class="m-cp-item">
                <h5 class="title">营销入口</h5>
                <div class="panel panel-default m-panel">
                    <div class="panel-heading">
                        <h3 class="panel-title">营销入口一</h3>
                        
                    </div>
                    <div class="panel-body">
                        <div class="form-group m-cp-info">
                            <div class="cp-title mt6">
                                <span>入口名称</span>
                            </div>
                            <div class="cp-con">
                                <input type="text" name="Coupons[custom_url_name]" readonly value="<?php echo empty($model_info['custom_url_name'])?'':$model_info['custom_url_name'] ?>" class="form-control fc-sm" placeholder="请填写入口名称">
                            </div>
                        </div>
                        <div class="form-group m-cp-info">
                            <div class="cp-title mt6">
                                <span>提示语</span>
                            </div>
                            <div class="cp-con">
                                <input type="text" name="Coupons[custom_url_sub_title]" readonly value="<?php echo empty($model_info['custom_url_sub_title'])?'':$model_info['custom_url_sub_title'] ?>" class="form-control fc-sm" placeholder="请填写提示语">
                            </div>
                        </div>
                        <div class="form-group m-cp-info">
                            <div class="cp-title">
                                <span>跳转链接</span>
                            </div>
                            <div class="cp-con js-jump-link">
                                <div class="radio-inline" style="display:none">
                                    <label>
                                        <input type="radio" name="jump-link" value="1" n="1" > 常用链接
                                    </label>
                                </div>
                                <div class="radio-inline">
                                    <label>
                                        <input type="radio" name="jump-link" value="2" n="2" checked>自定义链接
                                    </label>
                                </div>
                                <div class="form-group mt20 choose-button" style="display: none;">
                                    <button type="button"  class="btn btn-default choose_link" onclick="show_link(1)">选择链接</button>
                                	<span class="choosed-link pl10"></span>
                                </div>
                                <div class="form-group mt20 link-input" >
                                    <input type="text" id="panel1_link" readonly name="Coupons[custom_url]" value="<?php echo empty($model_info['custom_url'])?'':$model_info['custom_url'] ?>" class="form-control fc-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default m-panel js-entry-tpl" <?php echo empty($model_info['promotion_url_name'])?'style="display: none;"':'';?>>
                    <div class="panel-heading">
                        <h3 class="panel-title">营销入口二</h3>
                        <a href="javascript:;" class="fr js-entry-del">删除</a>
                    </div>
                    <div class="panel-body">
                        <div class="form-group m-cp-info">
                            <div class="cp-title mt6">
                                <span>入口名称</span>
                            </div>
                            <div class="cp-con">
                                <input type="text" name="Coupons[promotion_url_name]" readonly value="<?php echo empty($model_info['promotion_url_name'])?'':$model_info['promotion_url_name'] ?>" class="form-control fc-sm" placeholder="请填写入口名称">
                            </div>
                        </div>
                        <div class="form-group m-cp-info">
                            <div class="cp-title">
                                <span>跳转链接</span>
                            </div>
                            <div class="cp-con js-jump-link">
                                <div class="radio-inline" style="display:none">
                                    <label>
                                        <input type="radio" name="jump-link-1" value="1" n="1" > 常用链接
                                    </label>
                                </div>
                                <div class="radio-inline">
                                    <label>
                                        <input type="radio" name="jump-link-1"  value="2" n="2" checked>自定义链接
                                    </label>
                                </div>
                                <div class="form-group mt20 choose-button1" style="display: none;">
                                    <button type="button"  class="btn btn-default choose_link" onclick="show_link(2)">选择链接</button>
                                	<span class="choosed-link-1 pl10"></span>
                                </div>
                                <div class="form-group mt20 link-input1" >
                                    <input type="text" id="panel2_link" readonly name="Coupons[promotion_url]" value="<?php echo empty($model_info['promotion_url'])?'':$model_info['promotion_url'] ?>" class="form-control fc-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="javascript:;" class="m-add-btn add-entry-btn" id="js-add-entry" style="display:none" >添加自定义入口</a>
            </section>
            <div class="form-inline m-form-group mt30" style="margin-bottom:300px">
                <button type="button" class="btn btn-primary m-btn" id="js-cp-next">下一页</button>
                <a  class="btn btn-default m-btn" href="<?php echo Yii::app()->createUrl('mCenter/cardCoupons/CouponList')?>">返回</a>
            </div>
        </div>
    </div>
    <!-- 功能设置页面 -->
    <div class="m-fun-set js-step-2" style="display: none;">
        <div class="m-fun-set-item mt30">
            <div class="panel panel-default m-panel">
                <div class="panel-heading">
                    <h3 class="panel-title">使用设置</h3>
                </div>
                <div class="panel-body">
                    <div class="form-group m-cp-info">
                        <div class="cp-title mt6">
                            <span>库存<em>*</em></span>
                        </div>
                        <div class="cp-con">
                            <div class="input-group fc-sm">
                                <input type="text" name="Coupons[num]" readonly value="<?php echo $model['num'] ?>" class="form-control">
                                <span class="input-group-addon">份</span>
                            </div>

                        </div>
                    </div>
                    <div class="form-group m-cp-info">
                        <div class="cp-title mt6">
                            <span>每人限额</span>
                        </div>
                        <div class="cp-con">
                            <div class="input-group fc-sm">
                                <input type="text" name="Coupons[receive_num]" readonly value="<?php echo empty($model['receive_num'])?'':$model['receive_num'] ?>" class="form-control">
                                <span class="input-group-addon">份</span>
                            </div>

                            <span class="text-gray get-cp-tips">每个用户领券上限，如不填，则默认为1</span>
                        </div>
                    </div>
                    <div class="form-group m-cp-info">
                        <div class="checkbox-inline">
                            <label>
                                <input type="checkbox" name="Coupons[if_share]" value="1" disabled <?php echo $model['if_share']==1?'checked':''?>>用户可以分享领券链接
                            </label>
                        </div>
                        <div class="checkbox-inline">
                            <label>
                                <input type="checkbox" name="Coupons[if_give]" value="1" disabled <?php echo $model['if_give']==1?'checked':''?>>用户领券后可转增其他好友
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="m-fun-set-item mt30">
            <div class="panel panel-default m-panel">
                <div class="panel-heading">
                    <h3 class="panel-title">门店信息</h3>
                </div>
                <div class="panel-body">
                    <div class="form-group m-cp-info">
                        <div class="cp-title">
                            <span>使用渠道</span>
                        </div>
                        <div class="cp-con">
                            <div class="checkbox-inline">
                                <label>
                                    <input type="checkbox" name="Coupons[channel1]" disabled value="1" <?php echo $model['use_channel']==3||$model['use_channel']==1?'checked':'';?>>线下门店
                                </label>
                            </div>
                            <div class="checkbox-inline">
                                <label>
                                    <input type="checkbox" name="Coupons[channel2]" disabled value="2" <?php echo $model['use_channel']==3||$model['use_channel']==2?'checked':'';?>>线上使用
                                </label>
                            </div>
                            
                            <span class="text-gray pl20">在优惠券在商城上渠道使用时勾选上使用</span>
                        </div>
                    </div>
                    <div class="form-group m-cp-info">
                        <div class="cp-title">
                            <span>适用门店</span>
                        </div>
                        <div class="cp-con js-apply-store">
                            <div class="radio-inline">
                                <label>
                                    <input type="radio" name="applicable-store" value="1" n="1" disabled checked>全部适用
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input type="radio" name="applicable-store" value="2" n="2" disabled>指定门店适用
                                </label>
                            </div>
                            <a href="javascript:;" id="add_stores" class="m-add-btn ml10" style="display: none;">添加指定门店</a>
                            
                            <table id="store_table" class="table g-table mt20 store-table" style="display: none;">
                                <thead>
                                <tr>
                                    <th>门店名称</th>
                                    <th>门店地址</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if(!empty($model_info['store_info'])){?>
                                <?php foreach ($model_info['store_info'] as $k => $v){?>
                                <tr>
                                    <td><?php echo $v['name']?></td>
                                    <td><?php echo $v['address']?></td>
                                    <td><a href="javascript:;" onclick="delstore(this,<?php echo $v['address']?>)">删除</a></td>
                                </tr>
                                <?php }?>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="form-group m-cp-info">
                        <div class="cp-title mt6">
                            <span>操作提示<em>*</em></span>
                        </div>
                        <div class="cp-con">
                            <input type="text" name="Coupons[prompt]" readonly value="<?php echo isset($_POST['Coupons']['prompt'])?$_POST['Coupons']['prompt']:$model['prompt'] ?>" class="form-control fc-sm">
                            <span class="text-gray get-cp-tips">建议引导用户到店出示卡券，由店员完成核销操作</span>                         
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="form-inline m-form-group mt30">
            <button type="button" class="btn btn-primary m-btn" id="js-cp-prev">上一页</button>
            <a  class="btn btn-default m-btn" href="<?php echo Yii::app()->createUrl('mCenter/cardCoupons/CouponList')?>">返回</a>
        </div>
        
    </div>
</div>

<?php echo CHtml::endForm();?>
<script>
	var i = 0;
    var idIndex = 1;
    $("#js-add-imgpost").click(function () {
        var imgpostTpl = $('<div class="m-imgpost mb20">' +
            '<div class="m-upload">' +
            '<div class="upload-inner">' +
            '<div class="upload-icon">' +
            '<div class="upload-btn">' +
            '<input type="file" class="file cp-file"' + 'id="cp-upload' + idIndex + '"' + '>' +
            '<input type="hidden" ' + 'name="img_text['+ idIndex + ']"' + '>' +
            '</div>' +
            '<div class="ml10 js-del-imgpost">' +
            '<i class="del-imgpost-icon"></i>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<textarea name="img_text_title['+ idIndex +']"  class="form-control cover-desc" placeholder="输入描述,最多5000字" rows="3"></textarea>' +
            '</div>');
        idIndex++;
        $(".m-imgpost").last().after(imgpostTpl);
 		var path = 'http://upload.51wanquan.com.local/upload.php'
 		i++;
 		var id = 'cp-upload'+i;
 	 	addCouponImgList_upload(id,path,'<?php echo GJ_STATIC_JS?>',"<?php echo UPLOAD_IMG_TYPE;?>",'<?php echo(IMG_GJ_FOLDER)?>','<?php echo IMG_GJ_LIST?>');
 	 	window.parent.callParAutoResize("main",$("body").height());
    });


	$(function(){
		var no = 'none';
		var time_type = "<?php echo isset($_POST['Coupons']['time_type'])?$_POST['Coupons']['time_type']:$model['time_type']?>";
		
			$("input[name='Coupons[time_type]'][value="+time_type+"]").attr("checked",'checked');
			if(time_type == 2){
				$("input[name='Coupons[start_days]']").attr("disabled",false);			
				$("input[name='Coupons[effective_days]']").attr("disabled",false);
			}
			//$("input[name='Coupons[time_type]'][value="+time_type+"]").onclick();
		
		var available_time = "<?php echo isset($_POST['Coupons']['available_time'])?$_POST['Coupons']['available_time']:!empty($model['start_days'])&&!empty($model['effective_days'])?2:''?>";
		
			$("input[name='Coupons[available_time]'][value="+available_time+"]").attr("checked",'checked');
			if(available_time == 2){
				$('.js-partial-time').show();		
					
			}
			//$("input[name='Coupons[available_time]'][value="+available_time+"]").onclick();
		
		<?php if(isset($_POST['Coupons']['day']) && !empty($_POST['Coupons']['day'])){?>
			<?php foreach ($_POST['Coupons']['day'] as $k => $v){?>

			$("input[value=<?php echo $v?>]").attr("checked",'checked');
			<?php }?>

		<?php }elseif (!empty($model_info['interval'])){?>
			<?php foreach ($model_info['interval'] as $k => $v){?>
	
			$("input[value=<?php echo $v?>]").attr("checked",'checked');
			<?php }?>
		<?php }?>
		var link1 = "<?php echo isset($_POST['jump-link'])?$_POST['jump-link']:'no'?>";
		if(link1 != 'no'){
			$("input[name='jump-link'][value="+link1+"]").attr("checked",'checked');
			if(link1 == 1){
				$('.choose-button').show();
				$('.link-input').hide();
			}else if(link1 ==2){
				$('.choose-button').hide();
				$('.link-input').show();
			}
		}
		var link2 = "<?php echo isset($_POST['jump-link-1'])?$_POST['jump-link-1']:'no'?>";
		if(link2 != 'no'){
			$("input[name='jump-link-1'][value="+link2+"]").attr("checked",'checked');
			if(link2 == 1){
				$('.choose-button1').show();
				$('.link-input1').hide();
			}else if(link2 ==2){
				$('.choose-button1').hide();
				$('.link-input1').show();
			}
		}
		var storetype = "<?php echo isset($model['store_limit_type'])?$model['store_limit_type']:''?>";
		
			$("input[name='applicable-store'][value="+storetype+"]").attr("checked",'checked');
		
		var if_share = "<?php if(isset($_POST['Coupons']['if_share']) || $model['if_share'] == 1){echo 1;}else{echo 2;}?>";
		if(if_share == '1'){
			$("input[name='Coupons[if_share]']").attr("checked",'checked');
		}else if(if_share == '2'){
			$("input[name='Coupons[if_share]']").attr("checked",false);
		}
		var if_give = "<?php if(isset($_POST['Coupons']['if_give']) || $model['if_give'] == 1){echo 1;}else{echo 2;}?>";
		if(if_give == '1'){
			$("input[name='Coupons[if_give]']").attr("checked",'checked');
		}else if(if_give == '2'){
			$("input[name='Coupons[if_give]']").attr("checked",false);
		}
		var channel1 = "<?php if(isset($_POST['Coupons']['channel1']) || $model['use_channel'] == 1 || $model['use_channel'] == 3){echo 1;}else{echo 2;}?>";
		if(channel1 == '1'){
			$("input[name='Coupons[channel1]']").attr("checked",'checked');
		}else if(channel1 == '2'){
			$("input[name='Coupons[channel1]']").attr("checked",false);
		}
		var channel2 = "<?php if(isset($_POST['Coupons']['channel2']) || $model['use_channel'] == 2 || $model['use_channel'] == 3){echo 1;}else{echo 2;}?>";
		if(channel2 == '1'){
			$("input[name='Coupons[channel2]']").attr("checked",'checked');
		}else if(channel2 == '2'){
			$("input[name='Coupons[channel2]']").attr("checked",false);
		}

		var use_illustrate = "<?php echo !empty($model['use_illustrate'])?$model['use_illustrate']:''; ?>".replace("\n","");
		
			$("textarea[name='Coupons[use_illustrate]']").html(use_illustrate);

		
		var gift_info = "<?php echo !empty($model['discount_illustrate'])?$model['discount_illustrate']:''; ?>";
		
			$("textarea[name='Coupons[gift_info]']").html(gift_info);

		
		var img_text_title = "<?php echo isset($_POST['img_text_title'][0])?$_POST['img_text_title'][0]:'no'?>";
		if(img_text_title != 'no'){
			$("textarea[name='img_text_title[0]").html(img_text_title);

		}

		
		<?php if(!empty($model_info['store_info']) && $model['store_limit_type'] == 2){?>
		<?php echo '$("#store_table").show();';?>
		<?php }?>
		
		 
	});

	$("#js-cp-next").click(function(e){
        e.preventDefault(); 
        
            
        	$(".s-step").addClass("active").siblings().removeClass("active");
         	$(".js-step-2").show().siblings(".js-step-1").hide();
         	$(window).scrollTop(0);
         	$(parent.document).scrollTop(0); 
         	window.parent.callParAutoResize("main",$("body").height());
        
    });

    




            
    
	
</script>
