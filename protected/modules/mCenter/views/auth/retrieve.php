
<body class="register">
<div class="header clearfix">
	<div class="top">
      <div class="logo">
      <a href = "<?php echo Yii::app()->createUrl('mCenter/auth/login'); ?>">
        <img src="<?php echo GJ_STATIC_IMAGES ?>logo.png"></a>
        </div>
      
      <div class="header_right">
      	<ul>
      		<li>客服热线：400-882-9998</li>
        </ul>
	</div>
   </div>
</div>
<div class="title">找回密码</div>
<?php echo CHtml::beginForm(); ?>
<div class="Wqmain">
	<div class="fild">
        <span class="lable">手机号</span>
        <span class="text">
          <?php echo CHtml::textField('Account[tel]',isset($_POST['Account']['tel'])?$_POST['Account']['tel']:'',array('placeholder'=>'请输入手机号','class'=>'txt')); ?>
        </span>
        <span id="nomobile" class="error" style="display:none"><span class="ico"></span>手机号不存在</span>
          <?php if (Yii::app()->user->hasFlash('tel_error')) {?>
          <span id="tel_error" class="error">
          	<span class="ico"></span>
          	<?php 
                echo Yii::app()->user->getFlash('tel_error');
           }?>
         </span>
    </div>
    <div class="fild">
        <span class="lable">验证码</span>
        <span class="text">
           <?php echo CHtml::textField('Account[msg]',isset($_POST['Account']['msg'])?$_POST['Account']['msg']:'',array('placeholder'=>'请输入验证码','class'=>'yztxt')); ?>
        </span>
        <span class="red"><input type="submit" value="获取验证码" id="msg" class="btn_com" onclick="onMobileMsg()"></span>
          <?php if (Yii::app()->user->hasFlash('msg_error')) {?>
          <span class="error">
          	<span class="ico"></span>
          	<?php 
                echo Yii::app()->user->getFlash('msg_error');
           }?>
         </span>
    </div>
     <div class="fild">
        <span class="lable"></span>
        <span class="text"></span>
    </div>
    <div class="fild">
        <span class="lable"></span>
        <span class="text"></span>
    </div>
    <div class="fild">
        <span class="lable"></span>
        <span class="text">
          <?php echo CHtml::submitButton('下一步',array('id'=>'nextBt','class'=>'btn_com')) ?>
        </span>
    </div>
</div>
<?php echo CHtml::endForm(); ?>

</body>
<script type="text/javascript">
$("#msg").click(function() {
    return false;
});

var mins = 59;
var intervalid;
function ctrlTime()
{
	if(mins == 0){
		clearInterval(intervalid);//取消setInterval()函数设定的定时执行操作
		$("#msg").val('获取验证码');
		$("#msg").removeAttr('disabled');
		$("#msg").attr('class','btn_com');
		mins = 59;
		return;
	}
	$("#msg").val(mins + '秒');
	mins--;
}

function onMobileMsg(){
	var mobile = $('input[name = Account\\[tel\\]').val();
	var reg = /^(13|15|18|14)\d{9}$/;
    if(!reg.test(mobile)) {
    	alert('请填写正确的手机号!');
    }else{
        $.ajax({
            url : '<?php echo Yii::app()->createUrl('mCenter/auth/sendMsgPassword');?>?' + new Date().getTime,
            data : {mobile : mobile},
            type : 'post',
            async : false,
            success : function(res){
            	var res = jQuery.parseJSON(res);
            	if(res.status == '<?php echo ERROR_NONE?>'){
               		intervalid = setInterval("ctrlTime()", 1000);
               		$("#msg").attr('disabled', 'disabled'); 
                    $("#msg").attr('class','btn_com_gray');
                } else {
                    alert(res.errMsg);
                }
            }
        });
    }
}

//手机号输入框失去焦点判断
$("#Account_tel").blur(function(){
	
	$("#nextBt").removeAttr('disabled');
    $("#nextBt").attr('class','btn_com');
    $("#msg").removeAttr('disabled'); 
    $("#msg").attr('class','btn_com');
    $("#nomobile").hide();
    
	var mobile = $('input[name = Account\\[tel\\]').val();
	$.ajax({
		 url : '<?php echo Yii::app()->createUrl('mCenter/auth/isExitMobile');?>',
		 data : {mobile : mobile},
         type : 'post',
         async : false,
         success : function(res){
            var res = jQuery.parseJSON(res);
            if(res.status == '<?php echo ERROR_NO_DATA; ?>'){
            	//不存在手机号的时候
            	$("#nextBt").attr('disabled', 'disabled');
                $("#nextBt").attr('class','btn_com_gray');
            	$("#msg").attr('disabled', 'disabled'); 
                $("#msg").attr('class','btn_com_gray');
                $("#nomobile").show();
                $("#tel_error").hide();
            }
         }
	});
});
    
</script>

