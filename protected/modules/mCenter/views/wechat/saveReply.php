<script type="text/javascript" src="<?php echo SYT_STATIC_JS?>artDialog/jquery.artDialog.js?skin=simple"></script>
<script type="text/javascript" src="<?php echo SYT_STATIC_JS?>artDialog/plugins/iframeTools.js"></script>

<!--关键字弹出框-->
<div class="popWrap" id="pop">
	<div class="pop_con">
		<?php echo CHtml::beginForm();?>
	        <div class="title">
	        	自动回复
	        </div>
	        <div class="pop_content">
	            <div class="filed">
	                 <span class="label">规则名称：</span>
	                 <span class="text">
	                 	<?php echo CHtml::textField('Reply[rule_name]', $model['rule_name'], array('class'=>'txt', 'placeholder'=>'请输入规则名', 'style'=>'width:250px'))?>
	                 </span>
	                  <font color="red" id="rule_name_error"><?php echo Yii::app()->user->getFlash('rule_name');?></font>
	            </div>
	            <div class="filed">
                  	<span class="label">关键词：</span>
                   	<span class="text">
                      	<input class="txt" placeholder="请输入关键字" style="width:250px" type="text" id="Reply_key_word">                  
                      	<input type="button" class="btn_com_lightblue btn_com_attr btn_com_keyword" value="添加">
                   	</span>
                 	<font color="red" id="key_word_error"></font>
                  	<div class="attr_name keyword_name">
                     	<!-- <span>订单<i>×</i></span> -->
                  	</div>
                </div>

	            <div class="filed">
	                 <span class="label" style="vertical-align:top">回复内容</span>
	                 <span class="text">
	                 	<div class="nr">
	                    	<div class="top">
	                        	<span class="ico1<?php echo empty($model['material_id']) ? ' bg1' : '' ?>"><a href="#" onclick="textmsg()">文字消息</a></span>
	                            <span class="ico2<?php echo !empty($model['material_id']) ? ' bg2' : '' ?>"><a href="#" onclick="material()">图文消息</a></span>
	                        </div>
	                        <div class="nr-con">
	                        	<?php if(!empty($model['content']) || empty($model['material_id'])) { ?>
	                        		<div id="content" style="display: show">
	                        	<?php }else {?>
	                        		<div id="content" style="display: none">
	                        	<?php }?>
	                        		<?php echo CHtml::textArea('Reply[content]', $model['content'], array())?>
	                        	</div>
	                        	
	                        	<?php if(!empty($model['material_id'])) { ?>
	                        		<div id="material" style="display: show">
	                        	<?php }else {?>
	                        		<div id="material" style="display: none">
	                        	<?php }?>
	                        		<?php echo CHtml::dropDownList('Reply[material_id]', $model['material_id'], $material, array()) ?>
	                        </div>
	                    </div>
	                 </span>
	            </div>
	                 <font color="red" id="content_error"><?php echo Yii::app()->user->getFlash('content');?></font>
	        </div>
	        <div class="btn">
		        <?php echo CHtml::submitButton('确定',array('class'=>'btn_com_blue', 'style'=>"margin-right:100px"));?>
	            <input type="button" value="取消" class="btn_com_gray">
	        </div>
	    <?php echo CHtml::endForm();?>
    </div>
</div>

<script>

//假设这是iframe页
var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
layer.iframeAuto(index);
	//文字 图文 切换
	function textmsg(){
	  $("#content").show();
	  $("#material").hide();
		$(".ico1").attr('class', 'ico1 bg1');
		$(".ico2").attr('class', 'ico2');
	  $("#Reply_material_id").val('0');
	}
	
	function material(){
	  $("#content").hide();
	  $("#material").show();
		$(".ico1").attr('class', 'ico1');
		$(".ico2").attr('class', 'ico2 bg2');
	  $("#Reply_content").val('');
	}

	$(".btn_com_gray").click(function() {
		art.dialog.close();
	});
</script>