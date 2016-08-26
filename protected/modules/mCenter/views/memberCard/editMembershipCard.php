<script type="text/javascript">
	$(document).ready(main_obj.list_init);
</script>
<div class="kkfm_r_inner">
	<!--搜索框 count-top包含的部分--> 
        <?php $form=$this->beginWidget('CActiveForm')?>
	<div class="contant">
      	 <div class="coupons revise-card">
            <div class="re-left">
                <span><input type="text" id="card_name" name="membership_card_name" value="<?php if(!empty($card['membership_card_name'])) echo $card['membership_card_name'];?>" placeholder="请输入会员卡名称" style="width:250px"></span>
                <span class="title">会员等级：<?php if(!empty($card['name'])) echo $card['name'];?></span>
          
          <div class="filed">   
             <span class="text">
             		<div class="upload"><?php echo CHtml::fileField('upload')?></div>
<!--              		<span>图片大小不超过3M</span> -->
              		<div class="clear"></div>
                   	<div class="uploadPic">
                      	<ul>
                         	<li>
                              	<div class="htcon" style="display: none" id="htcon1">
                              	<span id="upload_img1">
                              	</span>
                              	<img alt="图1" src="" id="img1" height="180" >
                              	<?php echo CHtml::button('删除',array('class' => 'del','style' => 'display: none','id' => 'del1'))?>
                              	</div>
                              	<input name="image" value="" style="display: none" >
                          	</li>
                      	</ul>
                   	</div>
               	</span>
         </div>
            </div>
            <div class="re-right card">
            	<span class="title">效果预览 </span>
                <span>
                	<div class="card-box" height="175px" width="285px" style="overflow: hidden">
                        <img src="<?php echo GJ_STATIC_IMAGES?>mcard.png" id="card_img" >
                      	<div class="name">会员卡</div>
                        <div class="card-dj"><?php if(!empty($card['name'])) echo $card['name'];?></div>
                        <div class="card-id">编号：888888888888</div>
                    </div>
                </span>
            </div>
            <div class="clear"></div>
            <div class="btn">
        	<span><?php echo CHtml::submitButton('提交保存',array('class'=>'btn_com','style'=>"margin-right:50px"))?></span>
            <span><a href="javascript:history.back();" class="btn_com_gray">返回</a></span>
        </div>
        </div>
     </div>
    <?php $form=$this->endWidget()?>
</div> 

<script type="text/javascript">
$(function () {
    $('#upload').uploadify({
        uploader: '<?php echo UPLOAD_TO_PATH?>',// 服务器处理地址
        swf: '<?php echo GJ_STATIC_JS?>'+'uploadify/uploadify.swf',
        buttonText: "选择图片",//按钮文字
        height: 34,  //按钮高度
        width: 82, //按钮宽度
        fileTypeExts: "<?php echo UPLOAD_IMG_TYPE;?>",//允许的文件类型
        fileTypeDesc: "请选择图片文件", //文件说明
        formData: { 'folder' : '<?php echo(IMG_GJ_FOLDER)?>'}, //提交给服务器端的参数
        onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
            eval("var jsondata = " + data + ";");
            var key = jsondata['key'];
            var fileName = jsondata['fileName'];
		
			if($("input[name='image']").val() == ''){
				$("input[name='image']").val(fileName);
	            //$("#upload_img1").html(fileName);
	            $("#img1").attr('src','<?php echo IMG_GJ_LIST?>'+fileName);
	            $("#card_img").attr('src','<?php echo IMG_GJ_LIST?>'+fileName);
	            $("#htcon1").show();
	            $("#del1").show();
			}
        }
    });
});

$("#del1").click(function(){
	$("input[name='image']").val('');
    $("#upload_img1").attr('src','');
    $("#htcon1").hide();
    $("#del1").hide();
	});


$("#card_name").blur(function(){
	$(".name").html($(this).val());
});

</script>
