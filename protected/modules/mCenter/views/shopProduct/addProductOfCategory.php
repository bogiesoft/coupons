<body>
	<script type="text/javascript">
		$(document).ready(main_obj.list_init);
	</script>
	<div class="kkfm_r_inner">
		<!--搜索框 count-top包含的部分-->
	    <div class="top">
	        <div class="name">
	        	添加商品
	        </div>
	        <div class="cz"><a href="<?php echo Yii::app()->createUrl('mCenter/shopProduct/productList')?>" class="btn_comCancel">返回</a></div>
	    </div>
		<div class="contant sCategory_add" id="chose_step"><!--添加sCategory_add-->
	    	<div class="status-nav status-nav1 clearfix">
	            <ul>
	                <li class="cur" id="addproduct_category">1. 选择商品类目</li>
	                <li class="" id="addproduct_product">2. 编辑基本信息</li>
               		<li class="" id="addproduct_detail">3. 编辑商品详情</li>
	            </ul>
	        </div>
	        <?php $form = $this->beginWidget('CActiveForm', array(
		        'enableAjaxValidation' => true,
		        'id' => 'addpro',
		        'htmlOptions' => array('name' => 'createForm'),
		    ));?>
		        <div class="body" id="step_one">
		        	<dl id="addproductofcategory_parent">
<!-- 		            	<dt> -->
<!-- 		                	<input type="text" class="txt" placeholder="请输入类目名称"> -->
<!-- 		                    <input type="submit" class="search" value=""> -->
<!-- 		                </dt> -->
		                <dd>
		                	<ul>
		                    	<?php if (empty($_POST['Product']['category_one'])) {?>
		                    		<?php foreach ($category_one as $key => $value) { ?>
		                    			<li val="<?php echo $key?>"><?php echo $value?></li>
		                    		<?php } ?>
		                    	<?php }else{?>
		                    		<?php foreach ($category_one as $key => $value) { ?>
		                    			<?php if ($key == $_POST['Product']['category_one']) { ?>
			                    			<li val="<?php echo $key?>" class="cur"><?php echo $value?></li>
		                    			<?php }else{?>
			                    			<li val="<?php echo $key?>"><?php echo $value?></li>
		                    			<?php }?>
		                    		<?php } ?>
		                    	<?php } ?>
		                    </ul>
		                </dd>
		            </dl>
		            <input type="hidden" value="<?php echo empty($_POST['Product']['category_one']) ? "" : $_POST['Product']['category_one'];?>" name="Product[category_one]">
		            <dl id="addproductofcategory_child">
<!-- 		            	<dt> -->
<!-- 		                	<input type="text" class="txt" placeholder="请输入类目名称"> -->
<!-- 		                    <input type="submit" class="search" value=""> -->
<!-- 		                </dt> -->
		                <dd>
		                	<ul>
		                		<?php if (!empty($_POST['Product']['category_one']) && !empty($_POST['Product']['category_two'])) { ?>
			                		<?php foreach ($category_two[$_POST['Product']['category_one']] as $key => $value) { ?>
			                    		<?php if ($key == $_POST['Product']['category_two']) { ?>
			                    			<li val="<?php echo $key?>" class="cur"><?php echo $value?></li>
		                    			<?php }else{?>
			                    			<li val="<?php echo $key?>"><?php echo $value?></li>
		                    			<?php }?>
			                    	<?php } ?>
			                    <?php }?>
		                    </ul>
		                </dd>
		            </dl>
		            <input type="hidden" value="<?php echo empty($_POST['Product']['category_two']) ? "" : $_POST['Product']['category_two'];?>" name="Product[category_two]">
		            
		            <div class="clear"></div>
		            <div class="btn"><a href="javascript:;" class="btn_com_blue" id="next_step">下一步</a></div>
		        </div>
		        
		        <div class="body" id="step_two" style="display:none">
		        	<dl>
		            	<dt>基本信息</dt>
		                <dd>
		                	<div class="filed">
		                        <span class="label">商品类目：</span>
		                        <span class="text" id="category_name"></span>
		                    </div>
		                    <div class="filed">
		                        <span class="label">商品分组：</span>
		                        <span class="text">
		                            <?php echo CHtml::dropDownList('Product[shop_group]', '', $group) ?>
		                            <a href="javascript:;" id="addGroup">新建分组</a>
                                    <input type="text" style="display: none" id="group" maxlength="100">
                                    <input type="button" style="display:none" id="group_button" class="btn_com_blue" value="确认">
		                        </span>
		                    </div>
		                    <div class="filed">
		                        <span class="label">商品类型：</span>
		                        <span class="text">
		                            <input type="radio" name="Product[type]" value="<?php echo SHOP_PRODUCT_TYPE_OBJECT?>" <?php if(empty($_POST['Product']['type']) || $_POST['Product']['type'] == SHOP_PRODUCT_TYPE_OBJECT) { ?> checked="checked" <?php }?>>实物商品<em class="gray">（需要物流）</em>
		                            <input type="radio" name="Product[type]" value="<?php echo SHOP_PRODUCT_TYPE_VIRTAL?>" <?php if(!empty($_POST['Product']['type']) && $_POST['Product']['type'] == SHOP_PRODUCT_TYPE_VIRTAL) { ?> checked="checked" <?php }?>>虚拟商品<em class="gray">（无需物流）</em>
		                            <div class="remark">（发布后商品类型不能修改）</div>
		                        </span>
		                    </div>
		                </dd>
		            </dl>
		            
		            <dl>
		            	<dt>库存/规格</dt>
		                <dd>
		                	<div class="filed">
		                        <span class="label">商品规格：</span>
		                        <span class="text" id="pro_standard">
		                        	<ul class="ul01" name="color">
		                            	<li class="first" val="颜色">颜色</li>
		                            	<li><input type="checkbox" value="白色"> 白色</li>
		                                <li><input type="checkbox" value="红色"> 红色</li>
		                                <li><input type="checkbox" value="蓝色"> 蓝色</li>
		                                <li><input type="checkbox" value="紫色"> 紫色</li>
		                                <li><input type="checkbox" value="粉红色"> 粉红色</li>
		                                <li><input type="checkbox" value="绿色"> 绿色</li>
		                                <li><input type="checkbox" value="黑色"> 黑色</li>
		                                <li><input type="checkbox" value="天空蓝"> 天空蓝</li>
		                                <li><input type="checkbox" value="浅绿色"> 浅绿色</li>
		                                <li><input type="checkbox" value="深绿色"> 深绿色</li>
		                                <li><input type="checkbox" value="浅灰色"> 浅灰色</li>
		                                <li><input type="checkbox" value="深灰色"> 深灰色</li>
		                            </ul>
		                            <div id="ULID_686">
		                            
		                            </div>
		                            <input type="hidden" name="Product[pro_standard_name]" value="<?php echo empty($_POST['Product']['pro_standard_name']) ? "" : $_POST['Product']['pro_standard_name'];?>">
		                            <div class="sizeSet" style="display:none" id="standard_form">
		                            	<div class="filed">
		                                    <span class="label">规格名称：</span>
		                                    <span class="text">
		                                    	<input type="text" class="txt" name="Product[standard_name]" value="<?php echo empty($_POST['Product']['standard_name']) ? "" : $_POST['Product']['standard_name'];?>" style="width:200px">
		                                    </span>
		                                </div>
		                                <div class="filed">
		                                    <span class="label">规格属性：</span>
		                                    <span class="text">
		                                    	<input type="text" class="txt" name="Product[standard_attribute]" style="width:200px">
		                                        <a href="javascript:;" class="btn_com_gray btn_com_add" id="standard_add">添加</a>
		                                    </span>
		                                </div>
		                                <div class="filed">
		                                    <span class="label"> </span>
		                                    <span class="text" id="standard_attr">
		                                    	<ul>
		                                        </ul>
		                                    </span>
		                                </div>
		                                <div class="filed">
		                                    <span class="label"> </span>
		                                    <span class="text">
		                                    	<a href="javasript:;" class="btn_com_blue" id="standard_sub">确 认</a>
		                                        <a href="javasript:;" class="btn_com_gray" id="standard_cel">取 消</a>
		                                    </span>
		                                </div>
		                            </div>
		                            
		                            <div class="sizeSet" style="display:none" id="standard_model_form">
<!-- 		                            	<div class="filed"> -->
<!-- 		                                    <span class="label">模板名称：</span> -->
<!-- 		                                    <span class="text"> -->
<!-- 		                                    	<input type="text" class="txt" name="Smodel[name]" style="width:200px"> -->
<!-- 		                                    </span> -->
<!-- 		                                </div> -->
		                                <div class="filed">
					                        <span class="label">模板名称：</span>
					                        <span class="text">
					                        	<div class="block">
					                            	<input type="radio" name="Smodel[save_type]" value="<?php echo SHOP_STANDARD_SAVE_NEW?>" checked="checked"> 创建为新模板 
					                            	<input type="text" class="txt" name="Smodel[name]" style="width:108px" >
					                            </div>
					                            <div class="block">
					                            	<input type="radio" name="Smodel[save_type]" value="<?php echo SHOP_STANDARD_SAVE_OLD?>"> 保存到已有模板
					                            	<?php echo CHtml::dropDownList('Smodel[model]', '', $standard_model, array("disabled"=>"disabled")) ?>
					                            </div>
					                        </span>
					                    </div>
		                                <div class="filed">
		                                    <span class="label"> </span>
		                                    <span class="text">
		                                    	<input type="button" class="btn_com_blue" id="model_sub" value="确 认">
		                                        <a href="javascript:;" class="btn_com_gray" id="model_cel">取 消</a>
		                                    </span>
		                                </div>
		                            </div>
		                            
		                            <div class="block">
		                            	<a href="javascript:;" class="blue" id="addproduct_add_standard">添加规格</a>
		                                <label>选择模板</label>
		                                <select name="Product[standard_model]" onChange="changeStandard('<?php echo(Yii::app()->createUrl('mCenter/ShopProduct/getStandard'));?>',<?php echo ERROR_NONE?>,)">
		                                	<?php if(!empty($standard_model)) { ?>
		                                		<?php if (empty($_POST['Product']['standard_model'])) {?>
		                                			<?php foreach ($standard_model as $key => $value) {?>
			                                			<?php if ($key == $standard_id) { ?>
								            				<?php echo '<option value="'.$key.'" selected=selected>'.$value.'</option>'?>
								            			<?php }else{ ?>
								            				<?php echo '<option value="'.$key.'">'.$value.'</option>'?>
								            			<?php } ?>
								            		<?php } ?>
								            	<?php }else{?>
								            		<?php echo $_POST['Product']['standard_model']?>
								            		<?php foreach ($standard_model as $key => $value) {?>
			                                			<?php if ($key == $_POST['Product']['standard_model']) { ?>
								            				<?php echo '<option value="'.$key.'" selected=selected>'.$value.'</option>'?>
								            			<?php }else{ ?>
								            				<?php echo '<option value="'.$key.'">'.$value.'</option>'?>
								            			<?php } ?>
								            		<?php } ?>
		                                		<?php } ?>
		                                	<?php } ?>
		                                </select>
		                                <a href="javascript:;" class="blue" id="save_standard">保存为模板</a>
		                            </div>
		                        </span>
		                    </div>
		                    <div class="filed">
		                        <span class="label">价格库存：</span>
		                        <span class="text" id="PEPId_6312">
									<?php if (!empty($_POST['Product']['pro'])) { ?>   
			                        	<table cellspacing="0" cellpadding="0" width="100%" class="typeList">
											<tr class="order-title">
										    	<td align="center" nowrap="nowrap">规格</td>
										   		<td align="center" nowrap="nowrap">价格</td>
										   		<td align="center" nowrap="nowrap">原价（选填）</td>
										    	<td align="center" nowrap="nowrap">库存</td>
										  		<td align="center" nowrap="nowrap">商品条码</td>
											</tr>      
											<?php foreach ($_POST['Product']['pro'] as $key => $value) { ?>
												<tr class="pro_sku">
													<td align="center"><?php echo $value['att']?></td>
													<input type="hidden" name="Product[pro][<?php echo $key?>][att]" value="<?php echo $value['att']?>">
													<td align="center"><input oninput="findmin()" onpropertychange="findmin()" type="text" class="txt" style="width:50px" value="<?php echo $value['sku_new_prize']?>" name="Product[pro][<?php echo $key?>][sku_new_prize]"></td>
													<td align="center"><input oninput="check()" onpropertychange="check()" type="text" class="txt" style="width:50px" value="<?php echo $value['sku_old_prize']?>" name="Product[pro][<?php echo $key?>][sku_old_prize]"></td>
													<td align="center"><input oninput="amount()" onpropertychange="amount()" type="text" class="txt" style="width:50px" value="<?php echo $value['sku_number']?>" name="Product[pro][<?php echo $key?>][sku_number]"></td>
													<td align="center"><input type="text" class="txt" style="width:50px" name="Product[pro][<?php echo $key?>][sku_code]"></td>
												</tr>
											<?php } ?>
											<tr>
												<td colspan="6" class="line">
													<span id="setMoreName" style="display : inline">批量设置
													<a href="javascript:;" id="prize">价格</a>
													<a href="javascript:;" id="cost">原价</a>
													<a href="javascript:;" id="stock">库存</a>
													</span>
													<span id="setMoreVar" style="display : none">批量设置
														<input type="text" class="txt" name="Set[more_val]" placeholder="1" style="width:68px">
														<input type="hidden" name="Set[more_type]" val="">
												        <a href="javascript:;" id="setMore_sub">保存</a>
												        <a href="javascript:;" id="setMore_cel">取消</a>
													</span>
												</td>
											</tr>             
										</table>
									<?php } ?>
		                        </span>
		                    </div>
		                    <div class="filed">
		                        <span class="label">总库存：</span>
		                        <span class="text">
		                            <input type="text" class="txt" name="Product[num]" value="0" disabled="true" style="width:50px">
		                            <input type="hidden" name="Product[all_num]" value="0">
		                            <input type="checkbox" name="Product[if_show_num]" value="<?php echo IF_SHOW_NO?>">页面不显示商品库存
		                            <div class="remark">总库存为0时，会上架到[已售罄的商品]列表里</div>
		                        </span>
		                    </div>
		                </dd>
		            </dl>
		            
		            <dl>
		            	<dt>商品信息</dt>
		                <dd>
		                	<div class="filed">
		                        <span class="label">商品名称：</span>
		                        <span class="text">
		                        	<input type="text" class="txt" name="Product[pro_name]" value="<?php echo empty($_POST['Product']['pro_name']) ? "" : $_POST['Product']['pro_name'];?>">
		                        </span>
		                    </div>
		                	<div class="filed">
		                        <span class="label">商品价格：</span>
		                        <span class="text">
		                        	<input type="text" class="txt" name="Product[pro_min_price]" value="0" disabled="true">
		                            <input type="hidden" name="Product[pro_price]" value="<?php echo empty($_POST['Product']['pro_price']) ? "0" : $_POST['Product']['pro_price'];?>">
		                        </span>
		                    </div>
		                    <div class="filed">
		                        <span class="label">商品图片：</span>
		                        <span class="text">
	                            	<?php echo CHtml::fileField('upload') ?>
				                    <ul class="ul02" id="ShowPic">
				                    	<?php if (!empty($_POST['Product']['pro_img'])) { ?>
				                    		<?php foreach ($_POST['Product']['pro_img'] as $key => $value) {?>
				                    			<li><img src="<?php echo(IMG_GJ_LIST.$value)?>" width="62px" height="62px"/><a href="##" class="close">×</a><input type="hidden" name="Product[pro_img][]" value="<?php echo $value?>"/></li>
				                    		<?php } ?>
				                    	<?php } ?>
		                            </ul>
		                            <div class="remark">建议尺寸：640×640像素</div>
		                        </span>
		                    </div>
		                </dd>
		            </dl>
		            
		            <dl>
		            	<dt>物流/其他</dt>
		                <dd>
		                	<div class="filed">
		                        <span class="label">运费设置：</span>
		                        <span class="text">
		                        	<div class="block">
		                            	<input type="radio" name="Product[freight_type]" value="<?php echo SHOP_FREIGHT_TYPE_UNITE?>" <?php if(empty($_POST['Product']['freight_type']) || $_POST['Product']['freight_type'] == SHOP_FREIGHT_TYPE_UNITE) { ?> checked="checked" <?php }?> > 统一运费 
		                            	<input type="text" class="txt" name="Product[pro_total_inventory]" value="<?php echo empty($_POST['Product']['pro_total_inventory']) ? "" : $_POST['Product']['pro_total_inventory'];?>" style="width:108px" >
		                            </div>
		                            <div class="block">
		                            	<input type="radio" name="Product[freight_type]" value="<?php echo SHOP_FREIGHT_TYPE_MODEL?>" <?php if(!empty($_POST['Product']['freight_type']) && $_POST['Product']['freight_type'] == SHOP_FREIGHT_TYPE_MODEL) { ?> checked="checked" <?php }?> > 运费模板 
		                            	<?php echo CHtml::dropDownList('Product[freight_id]', empty($_POST['Product']['freight_id']) ? "" : $_POST['Product']['freight_id'], $freight, array("disabled"=>"disabled")) ?>
		                            </div>
		                        </span>
		                    </div>
		                	<div class="filed">
		                        <span class="label">每人限购：</span>
		                        <span class="text">
		                        	<input type="text" value="<?php echo empty($_POST['Product']['limit_num']) ? "" : $_POST['Product']['limit_num'];?>" name="Product[limit_num]" class="txt" style="width:50px">
		                            <em class="gray">0代表不限制</em>
		                        </span>
		                    </div>
		                    <div class="filed">
		                        <span class="label">发票：</span>
		                        <span class="text">
		                            <input type="radio" name="Product[invoice]" value="<?php echo SHOP_INVOICE_YES?>" <?php if(empty($_POST['Product']['invoice']) || $_POST['Product']['invoice'] == SHOP_INVOICE_YES) { ?> checked="checked" <?php }?> > 无
		                            <input type="radio" name="Product[invoice]" value="<?php echo SHOP_INVOICE_NO?>" <?php if(!empty($_POST['Product']['invoice']) && $_POST['Product']['invoice'] == SHOP_INVOICE_NO) { ?> checked="checked" <?php }?> > 有
		                        </span>
		                    </div>
		                </dd>
		            </dl>
		            
		            <input type="hidden" name="Product[pro_standard_id]" value='<?php echo empty($_POST['Product']['standard_model']) ? "" : $_POST['Product']['standard_model'];?>'>
		            <input type="hidden" name="Product[pro_standard_att]" value='<?php echo empty($_POST['Product']['pro_standard_att']) ? "" : $_POST['Product']['pro_standard_att'];?>'>
		            
		            <div class="btn">
		            	<input type="button" class="btn_com_blue" value="下一步" onclick="addproduct_save()">
		            </div>
		        </div>	
		        
		        <div class="body" id="step_three" style="display:none">
		        	<div class="shopEdit_l">
		            	<div class="sLine"></div>
		            	<div class="t">
		                	<h1>会员中心</h1>
		                </div>
		                <div class="m">
		                	<div class="area">
		                        <p>基本信息区</p>
		                        <p>固定样式，显示商品主图，价格等信息</p>
		                    </div>
		                    <div class="area">
		                    	<div class="block ">
		                            <h1>商品详情区</h1>
		                        </div>
		                        <div class="actions">
		                            <span class="edit">编辑</span>
		                        </div>
		                    </div>
		                </div>
		                <div class="b"></div>
		            </div> 
		            <div class="shopEdit_r">
		                <div class="block">
		                	<h2>商品简介（选填，微信分享给好友时会显示这里的文案）</h2>
                    		<textarea class="textarea" name="Product[pro_introduction]"></textarea>
		                </div>
		                <div class="block textEditing">
		                	<!--文本编辑器位置,宽度：505px-->
		                    <div class="arrow">
		                    </div>
		                    <?php echo CHtml::textArea('Product[pro_detail]',isset($_POST['Product']['pro_detail'])?$_POST['Product']['pro_detail']:''); ?>
		                    
		                </div>
		            </div>             
		            
		            <div class="btn">
		            	<input type="submit" class="btn_com_blue" value="确 定">
		            </div>
		        </div>
		            
		            <?php if (Yii::app()->user->hasFlash('error')) { ?>
			    		<script>alert('<?php echo Yii::app()->user->getFlash('error')?>')</script>
					<?php }?> 
		            
    		<?php $this->endWidget(); ?>
	    </div>
	</div>
	<script>
		var ue = UE.getEditor('Product_pro_detail',{
			initialFrameWidth:500, //初始化宽度
			initialFrameHeight:185, //初始化高度
		});
	
		//一级分类
        var imgCount = 0;
		var arr_one = <?php echo json_encode($category_one)?>;
		var arr_two = <?php echo json_encode($category_two)?>;

		//提交失败是的初始化
		<?php if (!empty($_POST)) { ?>
			$(document).ready(function() {
				//运费模块
				var ftype = $('input[name="Product[freight_type]"]:checked').val();
		       	if(ftype == <?php echo SHOP_FREIGHT_TYPE_UNITE?>){
		      		$('input[name="Product[pro_total_inventory]"]').removeAttr("disabled"); 
		      		$('select[name="Product[freight_id]"]').attr("disabled", "true"); 
		      		$('select[name="Product[freight_id]"]').val("0"); 
		       	}else if(ftype == <?php echo SHOP_FREIGHT_TYPE_MODEL?>){
		      		$('select[name="Product[freight_id]"]').removeAttr("disabled"); 
		      		$('input[name="Product[pro_total_inventory]"]').attr("disabled", "disabled"); 
		       	}
		       	
		       	//商品规格
                <?php if (!empty($_POST['Product']['pro_standard_att'])) {?>
                	var html = $('input[name="Product[pro_standard_att]"]').val();
                	$('#pro_standard').html(html);
                	var standard_id = $('input[name="Product[pro_standard_id]"]').val();
                	$('select[name="Product[standard_model]"]').val(standard_id);
                <?php }?>

                //总库存
                amount();
                //商品价格
                findmin();

				//自定义高度
                $("#main", parent.document).height($(".kkfm_r_inner").outerHeight());
			})
		<?php } ?>

		$(function(){
            product_checkbox('<?php echo Yii::app()->createUrl('mCenter/ShopProduct/getText');?>');
            product_model_sub(<?php echo SHOP_STANDARD_SAVE_NEW?>,<?php echo SHOP_STANDARD_SAVE_OLD?>,
                '<?php echo(Yii::app()->createUrl('mCenter/ShopProduct/addStandard'));?>',<?php echo ERROR_NONE?>
            );
            product_upload('<?php echo UPLOAD_TO_PATH?>','<?php echo GJ_STATIC_JS?>', "<?php echo UPLOAD_IMG_TYPE;?>",
                '<?php echo(IMG_GJ_FOLDER)?>',"<?php echo(IMG_GJ_LIST) ?>",'<?php echo IMG_GJ_THUMB_PRODUCT?>')
            product_freight_type(<?php echo SHOP_FREIGHT_TYPE_UNITE?>,<?php echo SHOP_FREIGHT_TYPE_MODEL?>);
        });


        //商品类型单选框触发事件
        $('input[name="Product[type]"]').change(function(){
            var type=$('input[name="Product[type]"]:checked').val();
            if(type==<?php echo SHOP_PRODUCT_TYPE_OBJECT?>)
            {
                //选中实物
                $('input[name="Product[freight_type]"][value="<?php echo SHOP_FREIGHT_TYPE_MODEL?>"]').removeAttr('checked');
                $('input[name="Product[freight_type]"][value="<?php echo SHOP_FREIGHT_TYPE_UNITE?>"]').attr('checked','checked');
                $('input[name="Product[freight_type]"][value="<?php echo SHOP_FREIGHT_TYPE_UNITE?>"]').click();
                $('input[name="Product[freight_type]"]').removeAttr('disabled');//设置运费不可选择
                $('input[name="Product[pro_total_inventory]"]').val('');//设置运费为0.00元
                $('input[name="Product[pro_total_inventory]"]').removeAttr('disabled');//设置输入框不可编辑
                $('select[name="Product[freight_id]"]').attr("disabled", "true");//设置下拉框不可选择
                $('select[name="Product[freight_id]"]').val("0");
            }
            else if(type==<?php echo SHOP_PRODUCT_TYPE_VIRTAL?>)
            {
                //选择虚拟物品Product[freight_type]
                $('input[name="Product[freight_type]"][value="<?php echo SHOP_FREIGHT_TYPE_MODEL?>"]').removeAttr('checked');
                $('input[name="Product[freight_type]"][value="<?php echo SHOP_FREIGHT_TYPE_UNITE?>"]').attr('checked','checked');
                $('input[name="Product[freight_type]"][value="<?php echo SHOP_FREIGHT_TYPE_UNITE?>"]').click();
                $('input[name="Product[freight_type]"]').attr('disabled',true);//设置运费不可选择
                $('input[name="Product[pro_total_inventory]"]').val('0.00');//设置运费为0.00元
                $('input[name="Product[pro_total_inventory]"]').attr('disabled','true');//设置输入框不可编辑
                $('select[name="Product[freight_id]"]').attr("disabled", "true");//设置下拉框不可选择
                $('select[name="Product[freight_id]"]').val("0");
            }
        });

     	//保存模板单选框触发事件
     	$('input[name="Smodel[save_type]"]').change(function(){
         	var ftype = $('input[name="Smodel[save_type]"]:checked').val();
         	if(ftype == <?php echo SHOP_STANDARD_SAVE_NEW?>){
        		$('input[name="Smodel[name]"]').removeAttr("disabled"); 
        		$('select[name="Smodel[model]"]').attr("disabled", "true"); 
        		$('select[name="Smodel[model]"]').val("0"); 
         	}else if(ftype == <?php echo SHOP_STANDARD_SAVE_OLD?>){
        		$('select[name="Smodel[model]"]').removeAttr("disabled"); 
        		$('input[name="Smodel[name]"]').attr("disabled", "disabled"); 
         	}
     	}); 

     	//修改sku库存，总库存跟着变
     	function amount(){
         	var num = 0;
         	$(".pro_sku").each(function() {
				var sku_num = $(this).find("td:eq(3) input").val();
				if(isNaN(sku_num)){
					alert("请输入数字");
					return false;
				}else{
					num = Number(sku_num)+Number(num);
				}
			})
			$('input[name="Product[num]"]').val(num);
			$('input[name="Product[all_num]"]').val(num);
     	};

     	//修改sku价格，商品价格跟着变
     	function findmin(){
     		var min_price = 0;
         	$(".pro_sku").each(function() {
				var sku_price = $(this).find("td:eq(1) input").val();
				if(isNaN(sku_price)){
					alert("请输入数字");
					return false;
				}else{
					if(sku_price != ""){
						if(min_price == 0){
							min_price = sku_price;
						}else{
							 if( Number(sku_price) < Number(min_price) ) {
								min_price = sku_price;
							 }
						}
					}
					
				}
			})
			$('input[name="Product[pro_min_price]"]').val(min_price);
			$('input[name="Product[pro_price]"]').val(min_price);
     	}

     	//判断价格 ，是否为数字
     	function check(){
         	var num = 0;
         	$(".pro_sku").each(function() {
				var old_price = $(this).find("td:eq(1) input").val();
				var new_price = $(this).find("td:eq(2) input").val();
				if(isNaN(old_price) || isNaN(new_price)){
					alert("请输入数字");
					return false;
				}
			})
     	}

     	//提交保存
     	function addproduct_save(){
     		var pro_group = $('#Product_shop_group').val();
         	var pro_name = $('input[name="Product[pro_name]"]').val().replace(/^\s+|\s+$/g,"");
         	var pro_price = $('input[name="Product[pro_price]"]').val().replace(/^\s+|\s+$/g,"");
         	var ftype = $('input[name="Product[freight_type]"]:checked').val();
         	var pro_total_inventory = $('input[name="Product[pro_total_inventory]"]').val().replace(/^\s+|\s+$/g,"");
         	var freight_id = $('select[name="Product[freight_id]"]').val();
         	var img = $('input[name="Product[pro_img][]"]').val();
         	var limit_num = $('input[name="Product[limit_num]"]').val().replace(/^\s+|\s+$/g,"");
            var all_num=$('input[name="Product[all_num]"]').val();
         	var flag = true;
         	if(pro_name == ""){
             	if($("#addproduct_category").attr("class") == "cur"){
                 	flag = false;
					alert("清一步步填写页面所需的信息");
             	}else{
	             	flag = false;
	             	alert("请输入商品名称");
             	}
         	}else if(pro_group == 0){
         		flag = false;
             	alert("请输入商品分组");
            }else if(pro_price == ""){
             	flag = false;
             	alert("请输入商品价格");
         	}else if(isNaN(pro_price)){
             	flag = false;
             	alert("商品价格必须为数字");
         	}else if(isNaN(limit_num)){
             	flag = false;
             	alert("限购数量必须为数字");
         	}else if(typeof(img) == "undefined"){
             	flag = false;
             	alert("请至少上传一张图片");
         	}else if(ftype == <?php echo SHOP_FREIGHT_TYPE_UNITE?>){
             	if(pro_total_inventory == ""){
                 	flag = false;
                 	alert("请设置运费");
             	}
             	if(isNaN(pro_total_inventory)){
                 	flag = false;
                 	alert("运费必须是数字");
             	}
         	}else if(ftype == <?php echo SHOP_FREIGHT_TYPE_MODEL?>){
             	if(freight_id == 0){
                 	flag = false;
                 	alert("请设置运费");
             	}
         	}
            if(parseInt(all_num) < parseInt(limit_num)){
                flag=false;
                alert('限购量必须小于总库存');
            }

            var img_num=0;
            var img_arr=new Array();
            $('input[name="Product[pro_img][]"]').each(function(){
                img_arr[img_num]=$(this).val();
                img_num++;
            });
         	var pro_standard_att_html = $('#pro_standard').html();
         	$('input[name="Product[pro_standard_att]"]').val(pro_standard_att_html);

         	if(flag){
    			$("#step_one").hide();
    			$("#step_two").hide();
    			$("#step_three").show();
    			$("#chose_step").attr("class","contant shop_edit");
    			$("#addproduct_detail").attr("class","cur");
    			$("#addproduct_product").removeClass();
    			$("#addproduct_category").removeClass();
                $("#main", parent.document).height($(".kkfm_r_inner").outerHeight());
         	}
     	}

        //新建分组
        $('#addGroup').click(function(){
            $('#group').show();
            $('#group_button').show();
        });

        $('#group_button').click(function(){
            var group_name=$('#group').val();
            if(group_name=='')
            {
                alert('请先填写分组名');
            }else if(group_name.indexOf(" ")>=0)
            {
                alert('分组名不能含有空格');
            }
            else
            {
                $.ajax({
                    url:'<?php echo Yii::app()->createUrl('mCenter/ShopProduct/AddGroup')?>',
                    type:'GET',
                    data:{groupname:group_name},
                    dataType:'json',
                    success:function(data){
                        if(data=='error')
                        {
                            alert(data);
                        }else if(data=='duplicate'){
                            alert('该分组名已存在');
                        } else {
                            //新建分组成功,刷新下拉框
                            $('#Product_shop_group').html('');
                            $.each(data, function (n, value) {
                                if(value==group_name)
                                    $('#Product_shop_group').append("<option value='"+n+"' selected='selected'>"+value+"</option>");
                                else
                                    $('#Product_shop_group').append("<option value='"+n+"'>"+value+"</option>");
                            });

                            //隐藏输入框和确认按钮
                            $('#group').hide();
                            $('#group_button').hide();
                            alert('保存成功');
                        }
                    }
                });
            }
        });
		
	</script>
    <script src="<?php echo GJ_STATIC_JS."gj.js"?>"></script>
</body>
