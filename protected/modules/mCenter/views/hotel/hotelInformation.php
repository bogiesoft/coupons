<!-- hotelInfomation ↓ -->
<style type="text/css">
.error{display:none;} 
</style>
<div class="kkfm_r_inner">
    <div class="storeManage">
        <div class="top">
            <div class="name">
                酒店图集
            </div>
        </div>
        <?php echo CHtml::beginForm('','post',array('id'=>'hotelForm'))?>
        <div class="hotel">
            <div class="hotel-form">
                <div class="hotel-input">
                    <label><i class="icon-hotel icon-hotel-1"></i>酒店名称：</label>
                    <input type="text" class="hotel-text" name="name" id="hotel_name" onblur="checkCompany();" placeholder="请输入酒店名称" value="<?php echo empty($hotel_info['name']) ? '' : $hotel_info['name']?>"></input>
                    <span class="error tip_1"></span>
                </div>
                <div class="hotel-input">
                    <label><i class="icon-hotel icon-hotel-2"></i>酒店地址：</label>
                    <input type="text" class="hotel-text" name="address" id="hotel_address" onblur="checkAddress();" placeholder="请输入酒店地址" value="<?php echo empty($hotel_info['address']) ? '' : $hotel_info['address']?>"></input>
                    <span class="error tip_2"></span>
                </div>
                <div class="hotel-input">
                    <label><i class="icon-hotel icon-hotel-3"></i>客服电话：</label>
                    <input type="text" class="hotel-text" name="tel" id="hotel_tel" placeholder="请输入客服电话" onblur="checkPhonenum();" value="<?php echo empty($hotel_info['tel']) ? '' : $hotel_info['tel']?>"></input>
                    <span class="error tip_3"></span>
                </div>
                <div class="hotel-input">
                    <label><i class="icon-hotel icon-hotel-4"></i>酒店图集：</label><input type="file" class="" value="上传图片" id="upload">
                    <span class="hotel-img-tip">请选择小于5M的jpg、bmp等格式的图片</span>
                </div>
            </div>
            <div class="hotel-gallery clearfix">
                <span class="gallery-title">共 <span class="img_num">0</span> 张，还能上传 <span class="surplus_img">6</span> 张<em>（按 ctrl 可选择多张）</em></span>
                <ul class="gallery-list clearfix">
                <?php 
                    if (!empty($room_imgs)){
                        foreach ($room_imgs as $k=>$v){
                ?>
                    <li>
                        <img src="<?php echo IMG_GJ_LIST.$v?>" name="images[<?php echo $k?>]" class="room_images">
                        <i class="icon-hotel icon-hotel-5"></i>
                        <input type="hidden" name="room_img[<?php echo $k?>]" value="<?php echo $v;?>">
                    </li>
                <?php
                    }}
                ?>
                    <!-- <li>
                        <img src="https://unsplash.it/140/95/?random" alt="hotel-image">
                        <i class="icon-hotel icon-hotel-6"></i>
                    </li>
                    <li class="hotel-img-btn">
                        +
                    </li> -->
                </ul>
                <input type="button" class="btn_com_blue" value="保存"></input>
            </div>
        </div>
        <?php echo CHtml::endForm();?>
    </div>
</div>
<!-- hotelInfomation ↑ -->
<script type="text/javascript">
$(function(){
    tips = {'0' : '电话号码不能为空！',
            '1' : '酒店名称不能为空！',
            '2' : '酒店地址不能为空！',
            '3' : '格式错误,请填写正确的电话号码！',
            '4' : '格式错误,请填写正确的酒店名称！',
            '5' : '格式错误,请填写正确的酒店地址！',
        	'6' : '请上传至少一张图片！'
    };
    changeImgCount();
    $(document).on("click",".icon-hotel-5",function(){
    	setTimeout("changeImgCount()",100);
    });
	//绑定上传图片按钮
	hotelroom_uploadImg('upload','cover','cover_img','<?php echo UPLOAD_TO_PATH?>','<?php echo GJ_STATIC_JS?>','<?php echo UPLOAD_IMG_TYPE;?>','<?php echo IMG_GJ_FOLDER?>','<?php echo IMG_GJ_LIST?>');
	$('.btn_com_blue').click(function(){
		var count = $(".room_images").length;	//图片数量
		if (count < 1){
			alert(tips['6']);
		}
		if (checkCompany() && checkAddress() && checkPhonenum()){
			$('#hotelForm').submit();
		}
	});
});

function showTip(className,tip_num){
	var tip_class = '.'+className;
	if (arguments[2] == 1){
		$(tip_class).hide();
	}else{
		$(tip_class).attr("style","display:inline-block").html(tips[tip_num]);
	}
}
function checkCompany(){	//验证酒店名称
	var company = $('#hotel_name').val();
	var reg_company = /^[A-Za-z0-9_()（）\-\u4e00-\u9fa5]{1,42}$/;
    if ('' == company) {
        showTip("tip_1",'1');
        return false;
    }
    if(!reg_company.test(company)){
    	showTip("tip_1",'4');
		return false;
    }
    showTip("tip_1",'5','1');
    return true;
}
function checkAddress(){
	var address = $('#hotel_address').val();
	var reg_address = /^[A-Za-z0-9\#()（）\_\-\u4e00-\u9fa5]{2,60}$/
    if ('' == address) {
        showTip("tip_2",'2');
        return false;
    }
    if(!reg_address.test(address)){
    	showTip("tip_2",'5');
		return false;
    }
    showTip("tip_2",'5','1');
    return true;
}
function checkPhonenum(){
	var phone_num = $('#hotel_tel').val();
	var reg_phone = /^[0-9\-()（）]{7,18}$/;
    if ('' == phone_num) {
        showTip("tip_3",'0');
        return false;
    }
    if(!reg_phone.test(phone_num)){
    	showTip("tip_3",'3');
		return false;
    }
    showTip("tip_3",'5','1');
    return true;
}

function changeImgCount(){	//显示图片数量
	var count = $(".room_images").length;	//图片数量
	$(".img_num").html(count);
	$(".surplus_img").html(6-count);
}

function upload_img(fileName){	//上传完成,添加显示图片
	var count = $(".room_images").length;	//图片数量
	if (count >= 6){
		alert("超过最大上传数量");
	}else{
		var time = new Date().getTime();
		var src = "<?php echo IMG_GJ_LIST?>";
	    var str = "<li><img src="+src+fileName+" class='room_images'><i class='icon-hotel icon-hotel-5'></i><input type='hidden' name='room_img["+time+"]' value='"+fileName+"'></input></li>";
	    $('.gallery-list').append(str);
		  //窗口高度自适应
	    window.parent.callParAutoResize("main",$("body").height());
	    changeImgCount();
	}
}
//预约订房-绑定房间图片上传插件
function hotelroom_uploadImg(id,cover,cover_img,path,static_js,type,folder,list){
    setTimeout(function(){
        $(function () {
            $('#'+id).uploadify({
            	onInit: function () {$(".uploadify-queue").hide();},//载入时触发，将flash设置到最小
                uploader: path,// 服务器处理地址
                swf: static_js+'uploadify/uploadify.swf',
                buttonText: "点击上传",//按钮文字
                height: 34,  //按钮高度
                width: 82, //按钮宽度
                fileTypeExts: type,//允许的文件类型
                fileTypeDesc: "请选择图片文件", //文件说明
                fileSizeLimit:'5MB',  //上传文件的大小限制
//                 fileTypeExts : '*.jpg;*.bmp', // 允许上传文件类型;'*.gif;*.jpg;*.png;*.jpeg'
//                 queueSizeLimit : 1, //上传数量
                formData: { 'folder' : folder,'thumb' : '<?php echo IMG_GJ_THUMB_PRODUCT?>'}, //提交给服务器端的参数
                onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
                    eval("var jsondata = " + data + ";");
                    var key = jsondata['key'];
                    var fileName = jsondata['fileName'];
                    upload_img(fileName);
                }
            });
        });
    },10);
}
</script>