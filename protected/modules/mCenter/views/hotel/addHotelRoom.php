<!-- addHotelRoom ↓ -->
<?php     
//默认门店
$store_id = '';
if (!empty($room_info)){
    $store_id = $room_info['store_id'];
}
?>
<style type="text/css">
.room_images{height:100px;width:130px;}
.tip_1{display:none;} 
</style>
<div class="kkfm_r_inner">
	<!--搜索框 count-top包含的部分-->
    <div class="top">
        <div class="name">添加房间</div>
    </div>
	<div class="contant romManager_add">
	<?php echo CHtml::beginForm('','post',array('id'=>'room_form'))?>
        <div class="body">
            <div class="filed">
                <span class="label label01"><em></em>门店选择：</span>
                <span class="text">
                    <select class="select">
                    <?php 
                        foreach ($store_list as $k => $v){
                    ?>
                       <option value="<?php echo $k;?>" <?php echo $k==$store_id ? 'selected="true"' : '';?>><?php echo $v;?></option> 
                    <?php
                        }
                     ?>
                      <input type="hidden" class="txt" name="store_id" value="">
                    </select>
                    
                </span>
            </div>
            <div class="filed">
                <span class="label label02"><em></em>房间类型：</span>
                <span class="text">
                    <input type="text" class="txt" name="name" placeholder="大/双" id="hotel_roomType" maxlength="32" onblur="checkRoomType()" value="<?php echo empty($room_info) ? '' : $room_info['name']?>">
                    <span class="error tip_1" style="display:none;"></span>               
                </span>
            </div>
            <div class="filed">
                <span class="label label03"><em></em>价格：</span>
                <span class="text">
                    <input type="text" class="txt" name="price" id="hotel_price" onblur="checkPrice()" maxlength="10" placeholder="请输入价格" value="<?php echo empty($room_info) ? '' : $room_info['price']?>">
                    <span class="error tip_2" style="display:none;"></span>
                </span>
            </div>
            <div class="filed">
                <span class="label label04"><em></em>房间首图：</span>
                <span class="text">
                    <input type="file" class="" value="上传图片" id="upload">
                    <span class="remark">建议图片尺寸：640*300（像素），最多5张</span>
                    <div class="pic">
                        <div class="explain">共<span class="img_num">0</span>张,还能上传<span class="surplus_img">5</span>张 <span class="gray">(按ctrl可选择多张)</span> </div>
                        <ul class="gallery-list">
                        <?php 
                            if (!empty($room_imgs)){
                                foreach ($room_imgs as $k=>$v){
                        ?>
                            <li>
                                <img src="<?php echo IMG_GJ_LIST.$v?>" class="room_images">
                                <i class="icon-hotel icon-hotel-5"></i>
                                <input type="hidden" name="room_img[<?php echo $k?>]" value="<?php echo $v;?>">
                            </li>
                        <?php
                            }}
                        ?>
                        </ul>
                    </div>
                </span>
            </div>
            <div class="filed">
                <span class="label"><em></em>房间详情：</span>
                <span class="text">
                    <?php echo CHtml::textArea('room_details', empty($room_info['room_details']) ? "" : $room_info['room_details'], array('style'=>'width:auto; height:500px')); ?>
                        <!--文本编辑器-->
                </span>
            </div>
            
            <div class="filed">
                <span class="label"><em></em>&nbsp;</span>
                <span class="text">
                    <div class="btn">
                        <input type="button" class="btn_com_blue" value="保 存" onclick="room_sub()">
                        <a href="<?php echo Yii::app()->createUrl('mCenter/Hotel/RoomManagement')?>" class="btn_com_white">取 消</a>
                    </div>
                </span>
            </div>
        </div>
        <?php echo CHtml::endForm();?>
    </div>
</div> 
<script type="text/javascript">
$(function(){
    tips = {'0' : '房间类型不能为空！',
            '1' : '价格不能为空！',
            '2' : '格式错误,请填写正确的房间类型！',
            '3' : '格式错误,请填写正确的价格！',
        	'4' : '请上传至少一张图片！',
        	'5' : '超过最大上传数量!'
    };
	var ue = UE.getEditor('room_details');
    $(document).on("click",".icon-hotel-5",function(){
    	setTimeout("changeImgCount()",100);
    });
	//绑定上传图片按钮
	hotelroom_uploadImg('upload','cover','cover_img','<?php echo UPLOAD_TO_PATH?>','<?php echo GJ_STATIC_JS?>','<?php echo UPLOAD_IMG_TYPE;?>','<?php echo IMG_GJ_FOLDER?>','<?php echo IMG_GJ_LIST?>');
	//选择门店,修改门店input值
	changeStoreId();
	changeImgCount();
	$(".select").change(function(){
		changeStoreId();
	})
});
function showTip(className,tip_num){
	var tip_class = '.'+className;
	if (arguments[2] == 1){
		$(tip_class).hide();
	}else{
		$(tip_class).attr("style","display:inline-block").html(tips[tip_num]);
	}
}
function checkRoomType(){	//验证酒店名称
	var company = $('#hotel_roomType').val();
	var reg_company = /^[A-Za-z0-9_()（）\-\u4e00-\u9fa5]{1,42}$/;
    if ('' == company) {
        showTip("tip_1",'0');
        return false;
    }
    if(!reg_company.test(company)){
    	showTip("tip_1",'2');
		return false;
    }
    showTip("tip_1",'5','1');
    return true;
}
function checkPrice(){
	var address = $('#hotel_price').val();
	var reg_address = /^(\d*\.\d{1,2}|\d+)$/
    if ('' == address) {
        showTip("tip_2",'1');
        return false;
    }
    if(!reg_address.test(address)){
    	showTip("tip_2",'3');
		return false;
    }
    showTip("tip_2",'5','1');
    return true;
}
/**
 * 显示图片数量
 */
function changeImgCount(){
	var count = $(".room_images").length;	//图片数量
	$(".img_num").html(count);
	$(".surplus_img").html(5-count);
 }
/**
 * 改变门店表单值
 */
function changeStoreId(){
	var store_id = $(".select").val();
	$("input[name=store_id]").val(store_id);
}

/**
 * 上传完成,添加显示图片
 */
function upload_img(fileName){
	var count = $(".room_images").length;	//图片数量
	if (count >= 5){
		alert("超过最大上传数量");
	}else{
		var time = new Date().getTime();
		var src = "<?php echo IMG_GJ_LIST?>";
	    var str = "<li><img src="+src+fileName+" class='room_images'><i class='icon-hotel icon-hotel-5'></i><input type='hidden' name='room_img["+time+"]' value='"+fileName+"'></input></li>";
	    $('.pic ul').append(str);
		  //窗口高度自适应
	    window.parent.callParAutoResize("main",$("body").height());
	    changeImgCount();
	}
}

/**
 * 提交表单
 */
function room_sub(){
	var count = $(".room_images").length;	//图片数量
	if(checkPrice() && checkRoomType()){
		if (count > 5){
			alert(tips['5']);
		}else if(count < 1){
			alert(tips['4']);
		}else{
			$("#room_form").submit();
		}
	}
}

/**
 * 预约订房-绑定房间图片上传插件
 */
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
//                 fileTypeExts: '*.jpg;*.bmp',	//文件格式
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
<!-- addHotelRoom ↑ -->