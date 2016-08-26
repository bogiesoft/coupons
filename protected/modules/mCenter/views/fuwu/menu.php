<body>
	<script type="text/javascript">
		$(document).ready(main_obj.list_init);
	</script>

		<div class="kkfm_r_inner">
			<div class="top">
	        <div class="name">自定义菜单<span>最多可以添加3个主菜单，每个主菜单最多可以添加5个子菜单</span></div>
	        <!--<div class="cz"><input type="submit" value="添加商户" class="btn_com_blue"></div>-->
	    </div>
	    	<div class="container menu-left">
	    		<div class="title1">
	    			<div class="r">
	    				<a href="javascript:;" class="sort" id="sortb">排序</a>
		  	    		<a href="javascript:;" class="btn_com_blue" id="suresort" style="display: none">完成排序</a>
		    			<a href="javascript:;" class="btn_comCancel" id="cancelsort" style="display: none">取消</a>
	    			</div>
		    		自定义菜单
	    		</div>
	    		
	        	<div id="menu">
	        		<?php foreach ($arr as $key => $value){?>
	        			<dl class="layer tile" data-force="30">
		                	<dt class="tile__name" val="<?php echo $key?>"><?php echo $value['menu_name']?>
		                    	<div class="shade">
		                        	<a href="<?php echo Yii::app()->createUrl('mCenter/Fuwu/Menu', array('id'=>$key))?>" class="edit"></a>
		                            <a href="<?php echo Yii::app()->createUrl('mCenter/Fuwu/delMenu', array('id'=>$key))?>" class="del" onclick="return confirm('确认删除吗？');"></a>
		                        </div>
		                    </dt>
		                    <dd class="tile__list">
			                    <?php if (!empty($value['son'])) { ?>
			                		<?php foreach ($value['son'] as $k => $v){?>
					                	<div class="subMenu" val="<?php echo $k?>"><?php echo $v['menu_name']?>
					                    	<div class="shade">
					                        	<a href="<?php echo Yii::app()->createUrl('mCenter/Fuwu/Menu', array('id'=>$k))?>" class="edit"></a>
					                            <a href="<?php echo Yii::app()->createUrl('mCenter/Fuwu/delMenu', array('id'=>$k))?>" class="del" onclick="return confirm('确认删除吗？');"></a>
					                        </div>
					                    </div>
					                <?php }?>
			                    <?php }?>
			                </dd>
	                    </dl>
	                <?php }?>
	                
	                <div class="btn">
	                	<a href="javascript:;?>" class="btn_com_blue" id="publish">发布菜单</a>
	                </div>
	                <script>
			$(".menu-left dt, .menu-left .subMenu").hover(
				function(){
					$(this).addClass("menuHover");
				},
				function(){
					$(this).removeClass("menuHover");
				}
			)
			
		</script>
	            </div>
	        </div>
	        <div class="menu-mod">
	          <?php echo CHtml::beginForm(Yii::app()->createUrl('mCenter/Fuwu/Menu'), 'post' ,array('name' => 'addmerchant'));?>
	        	<div class="menu-con">
	            	
	            	<span class="title"><?php if(empty($model -> menu_name)){?>添加菜单<?php }else{?>修改菜单<?php }?></span>
	                <div class="filed">
	                	<span class="label">菜单名称：</span>
	                    <span class="text"><?php echo CHtml::textField('Menu[menu_name]',$model->menu_name,array('class' => 'txt')); ?></span>
	                     <font color="red" id="rule_name_error"><?php echo Yii::app()->user->getFlash('empty');?></font>
	                </div>
	                <div class="filed">
	                	<span class="label">添加到：</span>
	                    <span class="text">
	                    	<?php echo CHtml::dropDownList('Menu[parent_id]', $model->parent_id, $node, array('class'=>'menu-list')); ?>
	                    </span>
	                </div>
	                <div class="filed">
	                	<span class="label">菜单类型：</span>
	                    <span class="text">
	                    	<?php echo CHtml::dropDownList('Menu[type]' ,$model->type, $GLOBALS['WQ_MENU_TYPE'], array('class'=>'menu-list')); ?>
	                    </span>
	                </div>
	                <div class="filed">
		                <?php if ($model->type == WQ_MENU_TYPE_WORD || empty($model->type)) { ?>
		            		<div id="Option_rows" style="display : show">
		            	<?php } else { ?>
		            		<div id="Option_rows" style="display : none">
		            	<?php }?>
		                    <span class="label">文字内容：</span>
		                    <span class="text">
		                      <?php echo CHtml::textArea('Menu[content]',$model -> content,array('cols'=>'10', 'rows'=>'5','class'=>'nr')) ?>
		                    </span>
		                </div>
		                
			                <input name="id" value="<?php echo !empty($model -> id)?$model -> id:''?>" style="display: none">
			           <?php if ($model->type == WQ_MENU_TYPE_WWW) { ?>
		            		<div id="Option_rows2" style="display : show">
		            	<?php } else { ?>
		            		<div id="Option_rows2" style="display : none">
		            	<?php }?>
	                    <span class="label">链接网址：</span>
	                    <?php echo CHtml::textField('Menu[content_www]', $model->content,array('class'=>'txt', "name"=>'Menu[content_www]'));?>
	                	</div>
	                	
	                	<?php if ($model->type == WQ_MENU_TYPE_PHOTO) { ?>
		            		<div id="Option_rows1" style="display : show">
		            	<?php } else { ?>
		            		<div id="Option_rows1" style="display : none">
		            	<?php }?>
	                    <span class="label">图文信息：</span>
	                    <?php echo CHtml::dropDownList('Menu[content_photo]', $model['content'], $material, array('class'=>'menu-list')) ?>
	                	</div>
	                	
	                	<?php if ($model->type == WQ_MENU_TYPE_SYSTEM) { ?>
		            		<div id="Option_rows3" style="display : show">
		            	<?php } else { ?>
		            		<div id="Option_rows3" style="display : none">
		            	<?php }?>
	                    <span class="label">系统链接：</span>
	                    <?php echo CHtml::dropDownList('Menu[content_url]', $model['content'], $url, array('class'=>'menu-list')) ?>
	                	</div>
	                </div>
	                
	                <?php if ( Yii::app()->user->hasFlash('errMsg')) { ?>
	                	<script>alert('<?php echo Yii::app()->user->getFlash('errMsg')?>')</script>
	                <?php } ?>
	                
	                <div class="filed">
	                	<span class="label"></span>
	                    <span class="text"><input type="submit" value="保存" class="btn_com_blue"></span>
	                </div>
	            </div>
	        <?php echo CHtml::endForm();?>
	        </div>
	        <div class="clear"></div>
	</div> 
</body>

<script>
//拖动菜单
function dragSetup() {
	new Sortable(multi, {
		draggable: '.tile',
		handle: '.tile__name'
	});


	[].forEach.call(multi.getElementsByClassName('tile__list'), function (el){
		new Sortable(el, { group: 'photo' });
	});
}
//点击排序按钮
$("#sortb").click(function(){
 	$("#menu").attr("id","multi");
	dragSetup();
	$("#publish").hide();
	
	$("#multi .shade a").hide();
	$("#sortb").hide();
	$("#suresort").show();
	$("#cancelsort").show();
	$("#multi dt, #multi .subMenu").css({"cursor":"move"}).addClass("iconSort");
});

//点击完成排序按钮
$("#suresort").click(function(){
	var str = '';
	$("#multi dt").each(function() {
		var p = $(this).attr("val");
		var son = '';
		var dd = $(this).next("dd");
		$(dd).find("div").each(function(){
			if(!!!$(this).attr("val")) return;
			son += $(this).attr("val") + ",";
		});
		p += ":" + son;
		str += p + ";";
	});
	$.ajax({
		url : "<?php echo Yii::app()->createUrl('mCenter/fuwu/sortMenu')?>",
		data : {srotStr : str},
		type: 'post',
		dataType: "json",
		async : false,
        success : function(res){
            if(res.status == '<?php echo ERROR_NONE?>'){
            	location.reload();
            }else{
            	alert(res.errMsg);
            }
        }
	});
// 	alert(str)
});

//点击取消按钮
$("#cancelsort").click(function(){
	location.reload();
});

//发布菜单按钮
$("#publish").click(function(){
	$.ajax({
        url: "<?php echo Yii::app()->createUrl('mCenter/fuwu/publishMenu') ?>",
        success: function(data) {
            alert(data);
        	//$("#Menu_menu_name").val(data)
        },
	});
});

function showpop(){
	$('.menu-mod').show();
}

function hidepop(){
	$('.menu-mod').hide();
}

function addmainmenu(){
	showpop();
	
}

$("#Menu_type").on("change",function(){
	if($(this).val()=="1"){  //文字信息
		$(this).next().show();      //多选框下方相邻的对象显示
		$("#Option_rows").show();   
		$("#Option_rows1").hide(); 
		$("#Option_rows2").hide();
		$("#Option_rows3").hide();
		$("#Menu_content").val('');
	}else if( $(this).val()=="2" ){               //图文信息
		$(this).next().hide();
		$("#Option_rows").hide();
		$("#Option_rows1").show(); 
		$("#Option_rows2").hide();
		$("#Option_rows3").hide();
		$("#Menu_content").val('');
		$("#Menu_content_photo").val('0');
	}else if( $(this).val()=="3" ){
		$(this).next().hide();
		$("#Option_rows").hide();
		$("#Option_rows1").hide(); 
		$("#Option_rows2").show();
		$("#Option_rows3").hide();
		$("#Menu_content").val('');
		$("#Menu_content_www").val('');
	}else {
		$(this).next().hide();
		$("#Option_rows").hide();
		$("#Option_rows1").hide(); 
		$("#Option_rows2").hide();
		$("#Option_rows3").show();
		$("#Menu_content").val('');
		$("#Menu_content_url").val('0');
	}
})


</script>
