<body>
	<script type="text/javascript">
		$(document).ready(main_obj.list_init);
	</script>
	
	<div class="kkfm_r_inner">
		<?php echo CHtml::beginForm();?>
			<!--搜索框 count-top包含的部分-->
		    <div class="top">
		        <div class="name">单图文广播</div>
		    </div>
			<div class="material">
		     	<div class="material-left">
		        	<div class="pro">
		            	<div class="text" id="titleasd" name="titleasd">
	                    	<?php if(isset($model['title'])) { ?>
	                    		<?php echo $model['title']?>
	                    	<?php }else{ ?>
	                    		标题
	                    	<?php } ?>
		            	</div>
		            	<div class="banner">
		            		<?php if (isset($model['cover_img']) && !empty($model['cover_img'])) { ?>
	                        	<a href="javascript:;"><img id="cover_img" src="<?php echo (IMG_GJ_LIST.$model['cover_img']) ?>" width="100%"/></a>
	                        <?php } else { ?>
	                            <div>封面图片</div>
	                        <?php } ?>
		            	</div>
		                <div class="txt">
			                <?php if (isset($model['abstract'])) { ?>
	                    		<?php echo $model['abstract']?>
	                    	<?php }else { ?>
	                    	<?php } ?>
		                </div>
		                <div class="item" style="border-top:1px solid #ddd">	
		               		<a href="" class="ck">立即查看</a>
		                </div>
		             </div>
		          <!--end pro-->
		          <div class="btn">
		          	<?php echo CHtml::submitButton('保存',array('class'=>'btn_com_blue', 'style'=>"margin-right:20px"));?>
	                <a href="<?php echo Yii::app()->createUrl('mCenter/wechat/materialList')?>" class="btn_com_gray">取消</a>
		          </div>
		        </div>
		        <!--end left-->
		        <div class="material-right">
		        	<div class="title">
		        		<em>标题</em>
		        		<em><?php echo CHtml::textField('Material[title]', $model['title'], array('class'=>'entry', 'placeholder'=>'最多可以输入32个字'))?></em> 
		                <font color="red" id="title_error"><?php echo Yii::app()->user->getFlash('title');?></font>
		            </div>
		            <div class="title">
		        		<em>封面</em>
		                <div class="pic">
		                	<em><?php echo CHtml::hiddenField('Material[cover_img]', $model['cover_img'], array('value'=>isset($_POST['Material']['cover_img'])?$_POST['Material']['cover_img']:''))?></em>     
		                	<em><?php echo CHtml::hiddenField('Material[img_path]', $model['img_path'], array('value'=>isset($_POST['Material']['img_path'])?$_POST['Material']['img_path']:''))?></em>     
                        	<?php echo CHtml::fileField('upload') ?>
                        	<font color="red"><?php echo Yii::app()->user->getFlash('cover_img');?></font>
		                   	<em class="text">建议尺寸540*270，支持jpg,jpeg,png格式，小于3M</em>
		                </div>
		            </div>
		            <div class="title">
		        		<em>摘要（选填）</em>
		        		<em><?php echo CHtml::textArea('Material[abstract]', $model['abstract'], array('class'=>'entry', 'placeholder'=>'最多可以输入120个字', 'value'=>isset($_POST['Material']['abstract'])?$_POST['Material']['abstract']:''))?></em>
		            </div>
		            <div class="title">
		        		<em>详情</em>
		                <em>
		                	<?php echo CHtml::radioButtonList('Material[jump_type]', empty($model['jump_type']) ? MATERIAL_CONTENT_TEXT : $model['jump_type'], array(MATERIAL_CONTENT_TEXT => $GLOBALS['MATERIAL_JUMP_TYPE'][MATERIAL_CONTENT_TEXT], MATERIAL_CONTENT_URL => $GLOBALS['MATERIAL_JUMP_TYPE'][MATERIAL_CONTENT_URL]), array('separator'=>'', 'value'=>isset($_POST['jump_type'])?$_POST['jump_type']:'0'))?>
		                </em>
		                <div class="txt-bj">
		                
				            <?php if($model['jump_type'] == MATERIAL_CONTENT_URL) { ?>
			              		<div id="url" class="txt-bj" style="display: show">
			                <?php }else {?>
			                	<div id="url" class="txt-bj" style="display: none">
			                <?php }?>
			                    <?php echo CHtml::dropDownList('Material[link_content]', $model['link_content'], $url, array('value'=>isset($_POST['link_content'])?$_POST['link_content']:0)) ?>
			                </div>
			                <?php if($model['jump_type'] == MATERIAL_CONTENT_TEXT) { ?>
			              		<div id="text" class="txt-bj" style="display: show">
			                <?php }else {?>
			                	<div id="text" class="txt-bj" style="display: none">
			                <?php }?>
			                	<?php //$this->widget('application.extensions.editor.CKkceditor',array(
// 								    "model"=>$model,                # Data-Model
// 								    "attribute"=>'content',         # Attribute in the Data-Model
// // 								    "heighst"=>'400px',
// // 								    "width"=>'90%',
// 									"filespath"=>Yii::app()->basePath."/../../upload/".Yii::app()->session['merchant_id']."/",
// 									"filesurl"=>Yii::app()->baseUrl."/../upload/".Yii::app()->session['merchant_id']."/",
// 								) );?>
			                	<?php echo CHtml::textArea('Material[content]', $model['content'], array('style'=>'width:1000px; height:500px')); ?>
                
			                </div>
		                	
		                </div>
		            </div>
		        </div>
		         <!--end right-->
		     </div>
		     </div>
		     </div>
		<?php echo CHtml::endForm();?>
	</div> 
</body>

<script type="text/javascript">

var ue = UE.getEditor('Material_content');
			                	
	//正文 链接 切换
	$("#Material_jump_type input").click(function(){
	    if($(this).prop("checked")){
	        if($(this).val() == 2){
	            $("#url").show();
	            $("#text").hide();
	        }
	        if($(this).val() == 1){
	            $("#text").show();
	            $("#url").hide();
	        }
	    }
	}) 
	                
	//改变标题
	$('.material-right input').filter('[name=Material\\[title\\]]').on('keyup paste blur', function(){
		//var cur_id=$('.single').data('cur_id');  //取出存入的数据值
		$('#titleasd').html($(this).val());
		$("#title_error").empty();
	})
	
	$('#Material_abstract').on('keyup paste blur', function(){
		//var cur_id=$('.single').data('cur_id');  //取出存入的数据值
		$('.pro .txt').html($(this).val());
	})
	
	$(function () {

	    var uploadify_onSelectError = function(file, errorCode, errorMsg) {  
	        var msgText = "上传失败\n";
	        switch (errorCode) {
	            case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
	                msgText += "文件大小超过限制( " + this.settings.fileSizeLimit + " )";
	                break;
	            default:
	                msgText += "错误代码：" + errorCode + "\n" + errorMsg;
	        }
	        alert(msgText);
	    };
	    
	    $('#upload').uploadify({
	        uploader: '<?php echo UPLOAD_TO_PATH?>',// 服务器处理地址
	        swf: '<?php echo GJ_STATIC_JS?>'+'uploadify/uploadify.swf',
	        buttonText: "选择图片",//按钮文字
	        height: 34,  //按钮高度
	        width: 82, //按钮宽度
	        fileTypeExts: "<?php echo UPLOAD_IMG_TYPE;?>",//允许的文件类型
	        fileTypeDesc: "请选择图片文件", //文件说明
	        formData: { 'folder' : '<?php echo(IMG_GJ_FOLDER)?>'}, //提交给服务器端的参数
	        fileSizeLimit : 0,
	        overrideEvents : [ 'onDialogClose', 'onUploadSuccess', 'onUploadError', 'onSelectError' ],
	        onSelectError : uploadify_onSelectError, 
	        onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
	            eval("var jsondata = " + data + ";");
	            var key = jsondata['key'];
	            var fileName = jsondata['fileName'];
	            var filePath = jsondata['filePath'];
				$("input[name='Material\\[cover_img\\]']").val(fileName);
				$("input[name='Material\\[img_path\\]']").val(filePath);
	            //$("#upload_img1").html(fileName);
	            $(".banner").html('<a href="javascript:;"><img id="cover_img" src="<?php echo ($model['cover_img']) ?>" width="240" height="120"/></a>');
	            $("#cover_img").attr('src','<?php echo IMG_GJ_LIST?>'+fileName);
	            $("#htcon1").show();
	            $("#del1").show();
	        }
	    });
	});

</script>

