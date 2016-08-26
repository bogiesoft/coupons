<body>
<script type="text/javascript">
// 	$(document).ready(material_obj.material_multi_init);

	var last_id = '';
	function myadd(obj) {
		var ele = $(".add a");
		var num = ele.attr("num");
		material_add(obj, num);
		num = num * 1 + 1;
		ele.attr("num", num);
		parent.callParAutoResize('main', 0);//高度适配
	}

	function mymod(obj) {
// 		type = $(last_id+' input').eq(4).val();
// 		if(type != 3){
// // 			var html = $(window.frames[0].document).find(".cke_show_borders").html();

		$("#title_error").html("");
		$("#img_error").html("");
		$("#content_error").html("");
		
			var html = UE.getEditor('Material_content').getContent();
			$(last_id+' input').eq(3).val(html);
			
// 			var flag = true;
// 			var title = $(last_id+' input').eq(0).val();
// 			var cov_img = $(last_id+' input').eq(2).val();
// 			var content = $(last_id+' input').eq(3).val();
// 			var url = $(last_id+' input').eq(1).val();
// 			var jump_type = $(last_id+' input').eq(5).val();
// 			var length = title.length;
	
// 			if(title == ""){
// 				flag = false;
// 				$("#title_error").html("请填写标题");
// 			}else if(length > 32){
// 				flag = false;
// 				$("#title_error").html("标题长度超出限制");
// 			}else{
// 				$("#title_error").html("");
// 			}
// 			if(cov_img == "" || cov_img =="1"){
// 				flag = false;
// 				$("#img_error").html("请上传图片");
// 			}else{
// 				$("#img_error").html("");
// 			}
// 			if(jump_type == "1"){
// 				if(content == ""){
// 					flag = false;
// 					$("#content_error").html("请填写内容");
// 				}else{
// 					$("#content_error").html("");
// 				}
// 			}else if(jump_type == "2"){
// 				if(url == "" || url =="0"){
// 					flag = false;
// 					$("#content_error").html("请填写内容");
// 				}else{
// 					$("#content_error").html("");
// 				}
// 			}
	
// 			if(flag){
// 				var cur_id='#'+ $(obj).parent().parent().attr('id');
// 				last_id = cur_id;
// 				material_mod(obj,2);
// 			}
// 		}else{
			var cur_id='#'+ $(obj).parent().parent().attr('id');
			last_id = cur_id;
			material_mod(obj,2);
// 		}
	}

	function save(){
// 		var html = $(window.frames[0].document).find(".cke_show_borders").html();
		var html = UE.getEditor('Material_content').getContent();
		$(last_id+' input').eq(3).val(html);

		var flag = true;
		//判断第一个图文判断非空
		var title = $('.imgSet'+' input').eq(0).val();
		var cov_img = $('.imgSet'+' input').eq(2).val();
		var content = $('.imgSet'+' input').eq(3).val();
		var url = $('.imgSet'+' input').eq(1).val();
		var jump_type = $('.imgSet'+' input').eq(5).val();
		var length = title.length;

		if(title == ""){
			flag = false;
			$("#title_error").html("请填写标题");
		}else if(length > 32){
			flag = false;
			$("#title_error").html("标题长度超出限制");
		}else{
			$("#title_error").html("");
		}
		if(cov_img == "" || cov_img =="1"){
			flag = false;
			$("#img_error").html("请上传图片");
		}else{
			$("#img_error").html("");
		}
		if(jump_type == "1" ){
			if(content == ""){
				flag = false;
				$("#content_error").html("请填写内容");
			}else{
				$("#content_error").html("");
			}
		}else if(jump_type == "2"){
			if(url == "" || url =="0"){
				flag = false;
				$("#content_error").html("请填写内容");
			}else{
				$("#content_error").html("");
			}
		}
		if(!flag){
			var obj = $('.imgSet').find(".edit")
			var cur_id='#'+ $(obj).parent().parent().attr('id');
			last_id = cur_id;
			material_mod(obj,2);
		}else{
			//其他图文的非空判断
			$('.imgSet').siblings('.list').each(function() { 
				type = $(this).find('input').eq(4).val();
				if(type != 3){
					var title = $(this).find('input').eq(0).val();
					var cov_img = $(this).find('input').eq(2).val();
					var content = $(this).find('input').eq(3).val();
					var url = $(this).find('input').eq(1).val();
					var jump_type = $(this).find('input').eq(5).val();
					var length = title.length;
					if(title == ""){
						flag = false;
						$("#title_error").html("请填写标题");
					}else if(length > 32){
						flag = false;
						$("#title_error").html("标题长度超出限制");
					}else{
						$("#title_error").html("");
					}
					if(cov_img == "" || cov_img =="1"){
						flag = false;
						$("#img_error").html("请上传图片");
					}else{
						$("#img_error").html("");
					}
					if(jump_type == "1" ){
						if(content == ""){
							flag = false;
							$("#content_error").html("请填写内容");
						}else{
							$("#content_error").html("");
						}
					}else if(jump_type == "2"){
						if(url == "" || url =="0"){
							flag = false;
							$("#content_error").html("请填写内容");
						}else{
							$("#content_error").html("");
						}
					}
		
					if(!flag){
						var obj = $(this).find(".edit")
						var cur_id='#'+ $(obj).parent().parent().attr('id');
						last_id = cur_id;
						material_mod(obj,2);
						return false;
					}
				}
			})
		}
		

		if(flag){
			$("#material_form").submit();
		}
	}
</script>

<div class="kkfm_r_inner">
	<!--搜索框 count-top包含的部分-->
    <div class="top">
        <div class="name">多图文</div>
        <!--<div class="cz"><input type="submit" value="添加商户" class="btn_com_blue"></div>-->
    </div>
    
    <div class="materical">
    <form id="material_form" method="post" action="<?php '?material_id='.$material_id ?>">
    	<div class="left">
            <div class="item mutil">
            	<?php if (!empty($material)) { ?>
            		<?php foreach ($material as $key => $value) { ?>
            			<?php if ($key == 0) { ?>
            				<div class="imgSet" id="big01">
			                    <div class="img"><img id="cover_img" src="<?php echo (IMG_GJ_LIST.$value['cover_img']) ?>" width="240" height="120"></div>
			                    <div class="txt"><?php echo $value['title']?></div>
			                    <div class="control" style="display:none">
			                    	<a href="javascript:;" class="edit" onClick="mymod(this)"></a>
			                    </div>
			                    <input type="hidden" value="<?php echo $value['title']?>" name="MaData[Data<?php echo $key?>][Title]">
			                    <input type="hidden" value="<?php echo $value['link_content']?>" name="MaData[Data<?php echo $key?>][Url]">
			                    <input type="hidden" value="<?php echo $value['cover_img']?>" name="MaData[Data<?php echo $key?>][ImgPath]">
			                    <input type="hidden" value="<?php echo $value['content']?>" name="MaData[Data<?php echo $key?>][inputContent]">
			                    <input type="hidden" value="2" name="MaData[Data<?php echo $key?>][type]">
			                    <input type="hidden" value="<?php echo $value['jump_type']?>" name="MaData[Data<?php echo $key?>][jumpType]">
			                    <input type="hidden" value="<?php echo $value['img_path']?>" name="MaData[Data<?php echo $key?>][ImgAllPath]">
								<input type="hidden" value="<?php echo $value['customize_link']?>" name="MaData[Data<?php echo $key?>][customize_link]">
			                    <input type="hidden" value="<?php echo $value['id']?>" name="MaData[Data<?php echo $key?>][id]">
			                </div>
            			<?php }else{ ?>
	            			<div class="list" id="msg0<?php echo $key?>">
			                    <div class="txt"><?php echo $value['title']?></div>
			                    <div class="img"><img id="cover_img" src="<?php echo (IMG_GJ_LIST.$value['cover_img'])?>" width="72" height="72"></div>
			                    <div class="control">
			                    	<a href="#mod" class="edit" onClick="mymod(this)"></a>
			                        <a href="#del" class="del" onClick="material_del(this,3)"></a>
			                    </div>
			                    <input type="hidden" value="<?php echo $value['title']?>" name="MaData[Data<?php echo $key?>][Title]">
			                    <input type="hidden" value="<?php echo $value['link_content']?>" name="MaData[Data<?php echo $key?>][Url]">
			                    <input type="hidden" value="<?php echo $value['cover_img']?>" name="MaData[Data<?php echo $key?>][ImgPath]">
			                    <input type="hidden" value="<?php echo $value['content']?>" name="MaData[Data<?php echo $key?>][inputContent]">
			                    <input type="hidden" value="4" name="MaData[Data<?php echo $key?>][type]">
			                    <input type="hidden" value="<?php echo $value['jump_type']?>" name="MaData[Data<?php echo $key?>][jumpType]">
			                    <input type="hidden" value="<?php echo $value['img_path']?>" name="MaData[Data<?php echo $key?>][ImgAllPath]">
								<input type="hidden" value="<?php echo $value['customize_link']?>" name="MaData[Data<?php echo $key?>][customize_link]">
			                    <input type="hidden" value="<?php echo $value['id']?>" name="MaData[Data<?php echo $key?>][id]">
			                </div>
            			<?php } ?>
            		<?php } ?>
            	<?php } ?>
                
                <div class="add"><a href="#add" num="<?php echo $count?>" onClick="myadd(this)">&nbsp;</a></div>
                <div class="remark">还可以添加<em id="limitNum"><?php echo 6-$count?></em>篇文章</div>
            </div>
            <div class="btn">
            	<input type="button" class="btn_com_blue" value="提交保存" onclick="save()"/>
                <a href="<?php echo Yii::app()->createUrl('mCenter/fuwu/materialList')?>" class="btn_com_gray">返回</a>
            </div>
        </div>
        <div class="right" style="width:64%">
        
		<?php echo CHtml::beginForm();?>
        	<div class="mod-form">
            	<div class="label" id="productName">
                	<label>标题</label>
                    <?php echo CHtml::textField('Material[title]', '',  array('class'=>'entry', 'placeholder'=>'最多可以输入32个字'))?>
                     <font color="red" id="title_error"></font>
                </div>
                <div class="label" id="ss">
                	<?php echo CHtml::hiddenField('Material[cover_img]')?>
                	<?php echo CHtml::fileField('upload') ?>
                    <div class="clear"></div>
                	<div class="remark">建议尺寸540*270px,支持.jpg .jpeg .png格式，小于3M</div>   
                	<font color="red" id="img_error"></font>                
                </div>
                <div class="label">
                	<label>详情</label><br>
					<?php echo CHtml::radioButtonList('Material[jump_type]', '1', array(MATERIAL_CONTENT_TEXT => $GLOBALS['MATERIAL_JUMP_TYPE'][MATERIAL_CONTENT_TEXT], MATERIAL_CONTENT_URL => $GLOBALS['MATERIAL_JUMP_TYPE'][MATERIAL_CONTENT_URL], MATERIAL_CONTENT_CUSTOMIZE_URL => $GLOBALS['MATERIAL_JUMP_TYPE'][MATERIAL_CONTENT_CUSTOMIZE_URL]), array('separator'=>''))?>
		        </div>
		        
                <div class="label" id="link" style="display:none">
                    <?php echo CHtml::dropDownList('Material[link_content]', '', $url) ?>
                </div>
                <font color="red" id="content_error"></font>
                <div class="label" id="ckeditor">
                	<?php //$this->widget('application.extensions.editor.CKkceditor',array(
// 						"model"=>$model,                # Data-Model
// 						"attribute"=>'content',         # Attribute in the Data-Model
// // 						"height"=>'400px',
// 						"width"=>'96%',
// 						"filespath"=>Yii::app()->basePath."/../../upload/images/".Yii::app()->session['merchant_id']."/",
// 						"filesurl"=>Yii::app()->baseUrl."/../upload/images/".Yii::app()->session['merchant_id']."/",
// 					) );?>
                      <?php echo CHtml::textArea('Material[content]',isset($_POST['Material']['jump_type'])?$_POST['Material']['jump_type']:'',array('style'=>'width:1000px; height:500px')); ?>
                </div>
				<div class="label" id="customize_link">
					<?php echo CHtml::textField('Material[customize_link]', '', array('style'=>'width:300px;')) ?>
				</div>
                <div class="arrow"></div>
            </div>
		<?php echo CHtml::endForm();?>
        </div>
        <div class="clear"></div> 
    </form>
    </div>
    
</div> 
</body>

<script type="text/javascript">
var ue = UE.getEditor('Material_content');

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
			$("input[name='Material\\[cover_img\\]']").val(fileName);
            //$("#upload_img1").html(fileName);
            //$(".banner").html('<a href="javascript:;"><img id="cover_img" src="<?php echo ($model['cover_img']) ?>" width="320" height="180"/></a>');
           // $(".img").attr('src','<?php echo IMG_GJ_LIST?>'+fileName);
			var cur_id=$('.mutil').data('cur_id');  //取出存入的数据值
            var imgPath = '<?php echo IMG_GJ_LIST?>'+fileName;
            if( cur_id == "#big01" ){
                $(cur_id+' .img').html('<a href="javascript:;"><img id="cover_img" src="'+imgPath+'" width="240" height="120"/></a>');
            }else{
                $(cur_id+' .img').html('<a href="javascript:;"><img id="cover_img" src="'+imgPath+'" width="72" height="72"/></a>');
            }
    		$(cur_id+' input').eq(2).val(fileName);
            $("#htcon1").show();
            $("#del1").show();
        }
    });
});
</script>
