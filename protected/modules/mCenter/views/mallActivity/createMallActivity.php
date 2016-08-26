
<body>
<div class="kkfm_r_inner">
	<!--搜索框 count-top包含的部分-->
    <div class="top">
        <div class="name">
        <?php if($type == DMALL_ACTIVITY_TYPE_SDLJ){?>
            首单立减活动
            <?php }elseif ($type == DMALL_ACTIVITY_TYPE_ZFL){?>
            粉丝周福利
            <?php }?>
        </div>
        <div class="cz"><a href="<?php echo Yii::app() -> createUrl('mCenter/MallActivity/AddMallActivity')?>" class="btn_comCancel">返回</a></div>
    </div>
    <?php echo CHtml::beginForm('','post',array('id'=>'addform')) ?>
	<div class="contant active_detail activeEle_detail"><!--添加shop_edit-->
        <div class="activeD_hd">
            <p>电子券发放</p>
            <div class="pic" id="img_upload"></div>
            <input style="display: none" value="" name="activity_img">
            <div class="tip">图片格式640*300（像素） 支持jpg、bmp的图片格式</div>
        </div>
        <input style="display: none" value="<?php echo $type?>" name="type">
        <div class="activeD_con">
            <div class="filed">
                <span class="label">活动名称：</span>
                 <span class="text">
                    <input type="text" class="txt" name="activity_name" value="<?php if(isset($_POST['activity_name']) && !empty($_POST['activity_name'])){echo $_POST['activity_name'];} ?>">
                 </span>
                 <?php if(Yii::app() -> user -> hasFlash('activity_name_error')){?>
                <span class="errorpic" id="activity_name_error" ><?php echo Yii::app() -> user -> getFlash('activity_name_error')?></span>
                <?php }?>
                <span class="errorpic" id="activity_name_error" style="display: none"></span>
            </div>
            
            <div class="filed">
                <span class="label">选择电子券：</span>
                 <span class="text">
                    <select class="choose" name="activity_coupon_id">
                    <option value='0'>-请选择-</option>
                    <?php foreach ($couponsList as $k => $v){?>
                        <option value="<?php echo $v -> id?>" <?php if(isset($_POST['activity_coupon_id']) && !empty($_POST['activity_coupon_id']) && $_POST['activity_coupon_id'] == $v -> id){?>selected<?php }?>><?php echo $v -> title?></option>
                        <?php }?>
                    </select>
                    <?php if(Yii::app() -> user -> hasFlash('activity_coupon_id_error')){?>
                    <span class="errorpic"><?php echo Yii::app() -> user -> getFlash('activity_coupon_id_error')?></span>
                    <?php }?>
                 </span>
            </div>
            
            <div class="filed">
                <span class="label">发放数量：</span>
                 <span class="text">
                    <input type="text" readonly class="txt" onkeyup="this.value=this.value.replace(/\D/g,'')"  onafterpaste="this.value=this.value.replace(/\D/g,'')" name="activity_num" value="<?php if(isset($_POST['activity_num']) && !empty($_POST['activity_num'])){echo $_POST['activity_num'];} ?>">
                 </span>
                 <?php if(Yii::app() -> user -> hasFlash('activity_num_error')){?>
                <span class="errorpic" id="activity_num_error"><?php echo Yii::app() -> user -> getFlash('activity_num_error')?></span>
                <?php }?>
                <span class="errorpic" id="activity_num_error" style="display: none"></span>
            </div>
            <div class="filed">
                <span class="label">活动时间：</span>
                 <span class="text">
                    <input type="text" class="txt" name="Time" readonly="readonly" value="<?php if(isset($_POST['Time']) && !empty($_POST['Time'])){echo $_POST['Time'];} ?>">
                 </span>
                 <?php if(Yii::app() -> user -> hasFlash('activity_time_error')){?>
                <span class="errorpic" id="activity_time_error"><?php echo Yii::app() -> user -> getFlash('activity_time_error')?></span>
                <?php }?>
                <span class="errorpic" id="activity_time_error" style="display: none"></span>
            </div>
            
            <div class="filed">
                <span class="label">电子券原价：</span>
                 <span class="text">
                    <input name="original_price" type="text" class="txt" value="<?php if(isset($_POST['original_price']) && !empty($_POST['original_price'])){echo $_POST['original_price'];} ?>" placeholder="例如 88.00">
                    <em class="option" style="display: none">(选填)</em>
                 </span>
                <?php if(Yii::app() -> user -> hasFlash('activity_price_error')){?>
                    <span class="errorpic"><?php echo Yii::app() -> user -> getFlash('activity_price_error')?></span>
                <?php }?>
            </div>
            <div class="filed">
                <span class="label"></span>
                 <span class="text">
                    <input type="submit" class="btn_com_blue"  value="确认添加">
                    <a class="btn_com_gray" href="<?php echo Yii::app() -> createUrl('mCenter/MallActivity/AddMallActivity')?>">取消</a>
                 </span>
            </div>

        </div>
    </div>
    <?php echo CHtml::endForm() ?>
</div> 
</body>
<script>




$(document).ready(function() {
	$('input[name=Time]').daterangepicker({
        format: 'YYYY/MM/DD H:m',
        dateLimit: true,
        maxDate: true,
        minDate: '<?php echo date('Y/m/d H:i');?>',
        startDate:'<?php echo date('Y/m/d H:i');?>',
    	endDate:'<?php echo date('Y/m/d H:i');?>',
    });

	 
});	
//活动名称验证
$('input[name=activity_name]').blur(function(){
	if($(this).val() == ''){
		$('#activity_name_error').html('<?php echo MESSAGE_DMALL_ACTIVITY_NAME_NONE?>');
		$('#activity_name_error').show();
	}else{
		//判断活动名称是否符合20个的长度限制 判断活动名称中是否存在空格
		if($(this).val().length <= 20 && $(this).val().indexOf(" ") == -1 ){
			$('#activity_name_error').hide();
		}else{
			$('#activity_name_error').html('<?php echo MESSAGE_DMALL_ACTIVITY_NAME_WRONG?>');
			$('#activity_name_error').show();
		}
	}
});

//发放数量验证
$('input[name=activity_num]').blur(function(){
	if($(this).val() == ''){
		$('#activity_num_error').html('<?php echo MESSAGE_DMALL_ACTIVITY_NUM_NONE?>');
		$('#activity_num_error').show();
	}else{
		//判断活动发放数量为阿拉伯数字
		if(/^\d+$/.test($(this).val())){
			$('#activity_num_error').hide();
		}else{
			$('#activity_num_error').html('<?php echo MESSAGE_DMALL_ACTIVITY_NUM_WRONG?>');
			$('#activity_num_error').show();
		}
	}
});

//活动时间验证
// $('input[name=Time]').blur(function(){
// 	if($(this).val() == ''){
//		$('#activity_time_error').html('<?php //echo MESSAGE_DMALL_ACTIVITY_TIME_NONE?>');
// 		$('#activity_time_error').show();
// 	}else{
// 		$('#activity_time_error').hide();
// 	}
// });



$('#img_upload').uploadify({
	onInit: function () {
		$(".uploadify-queue").hide();
		<?php if(isset($_POST['activity_img']) && !empty($_POST['activity_img'])){?>
		var fileName = '<?php echo $_POST['activity_img']?>';
		$('.uploadify-button-text').html('<img style="height:95px;width:165px" src="'+'<?php echo IMG_GJ_LIST?>'+fileName+'">');
		$('input[name=activity_img]').val(fileName);
	   <?php }?>
		},//载入时触发，将flash设置到最小
	uploader: '<?php echo UPLOAD_TO_PATH?>',// 服务器处理地址
	swf: '<?php echo GJ_STATIC_JS?>'+'uploadify/uploadify.swf',
	buttonText: "+",//按钮文字
	height: 95,  //按钮高度
    width: 165, //按钮宽度
    fileTypeExts: "<?php echo UPLOAD_IMG_TYPE;?>",//允许的文件类型
    fileTypeDesc: "请选择图片文件", //文件说明
    formData: { 'folder' : '<?php echo(IMG_GJ_FOLDER)?>'}, //提交给服务器端的参数
    onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
    	//alert('上传图片成功');
        eval("var jsondata = " + data + ";");
        var key = jsondata['key'];
        var fileName = jsondata['fileName'];
        $('.uploadify-button-text').html('<img style="height:95px;width:165px" src="'+'<?php echo IMG_GJ_LIST?>'+fileName+'">');
        $('input[name=activity_img]').val(fileName);
	}
});


//选择市
$('.choose').change(function(){
    var id = $(this).val();
    $.ajax({
        url: '<?php echo(Yii::app()->createUrl('mCenter/MallActivity/SelectCoupon'));?>',
        data: {id:id},
        type: 'post',
        dataType: 'json',
        success: function (data) {
          	if(data.status == <?php echo ERROR_NONE?>){
              	$('input[name=activity_num]').val(data.data);
          	}
        }
    });
	return false;
});




</script>

