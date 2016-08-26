
<div class="n-header">
    <div class="n-header-inner">
        <a href="<?php echo Yii::app() -> createUrl('mCenter/auth/login')?>"><img src="<?php echo WQ_STATIC_IMAGES?>n-logo.png" alt="logo" class="n-logo"></a>
        <div class="n-header-r">
            <span><i class="icon-phone"></i>客服电话：400-882-9998</span>
        </div>
    </div>
</div>
<div class="register-step register-step-2">
</div>
<div class="register-box register-account-wrapper">
    <div class="register-account-nav">
        <span class="register-account-nav-item <?php echo $registerType == 1 ? 'active':'';?>" onclick="ChangeRegisterType(1)">手机号注册</span>
        <span class="register-account-nav-item <?php echo $registerType == 2 ? 'active':'';?>" onclick="ChangeRegisterType(2)">邮箱注册</span>
    </div>
    <?php echo CHtml::beginForm('','post',array('id'=>'register_form')); ?>
    <input name="order_id" value="<?php echo $order_id?>" type="hidden">
    <input name="registerType" value="<?php echo $registerType?>" type="hidden">
    <div class="register-form-group register-account-phone"  style="<?php echo $registerType == 2 ? 'display: none;':'';?>">
        <div class="register-label">
            <i class="register-icon register-icon-phone"></i>
            <span class="register-label-text">手机号：</span>
        </div>
        <div class="register-input">
            <input type="text" class="form-control" name="Account[tel]" placeholder="请输入手机号" value="<?php echo isset($_POST['Account']['tel'])?$_POST['Account']['tel']:''; ?>" id="phone">
        </div>
    </div>
     <div class="register-form-group mt20 register-account-phone"  style="<?php echo $registerType == 2 ? 'display: none;':'';?>">
        <div class="register-label">
            <i class="register-icon register-icon-vcode"></i>
            <span class="register-label-text">验证码：</span>
        </div>
        <div class="register-input">
            <input type="text" class="form-control" name="Account[msg]" placeholder="请输入验证码" id="code">
            <a href="javascript:;" class="text-orange get-vcode-btn disabled"  id="msg"  onclick="onMobileMsg()" MSG='true'>获取验证码</a>
        </div>
    </div>
     
    <div class="register-form-group register-account-email" style="<?php echo $registerType == 1 ? 'display: none;':'';?>">
        <div class="register-label">
            <i class="register-icon register-icon-email"></i>
            <span class="register-label-text">邮箱地址：</span>
        </div>
        <div class="register-input">
            <input type="text" class="form-control" placeholder="请输入邮箱地址" name="Account[email]" value="<?php echo isset($_POST['Account']['email'])?$_POST['Account']['email']:''; ?>" id="eamil_account">
        </div>
    </div>
    <div class="register-form-group mt20">
        <div class="register-label">
            <i class="register-icon register-icon-pw"></i>
            <span class="register-label-text">登录密码：</span>
        </div>
        <div class="register-input">
            <input type="password" class="form-control" placeholder="请输入密码" onautocomplete="off" name="Account[pwd]" id="password" maxlength="16">
        </div>
    </div>
    <div class="register-form-group mt20">
        <div class="register-label">
            <i class="register-icon register-icon-confirm-pw"></i>
            <span class="register-label-text">确认密码：</span>
        </div>
        <div class="register-input">
            <input type="password" class="form-control" placeholder="请再次输入密码" onautocomplete="off" name="Account[confirm]">
        </div>
    </div>

    <button type="submit" class="btn btn-primary btn-lg btn-block-sm block-center">下一步</button>
    <?php echo CHtml::endForm(); ?>
</div>
<script>
$(function () {
	var error = "<?php echo Yii::app()->user->hasFlash('register_error') ? Yii::app()->user->getFlash('register_error') :''?>";
	if (error != ''){
		layer.alert(error);
	}
	mins = 59;
    $("#register_form").validate({
//         debug:true,
        rules:{
        	"Account[tel]":{
                required: true,
                mobilePhoneheck: true,
                //ajax验证
                remote:{
                        url : '<?php echo Yii::app()->createUrl('mCenter/Auth/Check');?>?',
                        data : {mobile : function () {
                                 	return $("#phone").val();
                                 }},
                        dataType: "json",
                        type : 'post',
                        async : false,
                        dataFilter : function(res){
                            if(res == 1){
                                $('#msg').attr("disabled", true);
                                return false;
                            }else{
                            	$("#msg").removeAttr("disabled");
                            	return true;
                            }
                        }
                    }
            },
            "Account[msg]":{
                required: true,
                //ajax验证
                remote:{
                        url : '<?php echo Yii::app()->createUrl('mCenter/Auth/CheckMsgPwd');?>?',
                        data : {mobile : function () {
                            		return $("#phone").val();
                                },code:function () {
                                    return $("#code").val();
                                    }
                                },
                        dataType: "json",
                        type : 'post',
                        async : false,
                        dataFilter : function(res){
                            if(res == 1){
                            	return true;
                            }else{
                                return false;
                            }
                        }
                    }
            },
            "Account[email]":{
            	required: true,
            	email:true, //邮箱
                //ajax验证
                remote:{
                        url : '<?php echo Yii::app()->createUrl('mCenter/Auth/CheckEmail');?>?',
                        data : {email : function () {
                                 	return $("#eamil_account").val();
                                 }},
                        dataType: "json",
                        type : 'post',
                        async : false,
                        dataFilter : function(res){
                            if(res == 1){
                                return false;
                            }else{
                            	return true;
                            }
                        }
                    }
            },
            "Account[pwd]":{
            	required: true,
            	minlength: 6, //最小长度
            	maxlength: 16 
            },
            "Account[confirm]":{
            	required: true,
            	equalTo:"#password"
            }
        },
        errorClass:"register-error",
        errorElement:"em",
        messages:{
        	"Account[tel]":{
        		remote: '该手机号已被注册，请更换手机号'
    		},
    		"Account[msg]":{
    			remote: '验证码错误'
    		},
    		"Account[email]":{
    			remote: '该邮箱已被注册，请更换邮箱'
    		}
    	}
    });
    //自定义正则表达式验证规则
    $.validator.addMethod("mobilePhoneheck", function (value, element) {
        var mobilePhone = /^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/; //填写正则
        return this.optional(element) || (mobilePhone.test(value));
    }, "请填写正确手机号") //报错提示内容
})
function onMobileMsg(){
 	var mobile = $('input[name = Account\\[tel\\]]').val();
  	var status = $("#register_form").validate().element($("#phone"));
    if(!status) {
    	layer.alert('请填写正确的手机号!');
    }else if(msg == 'false'){
    	return;
    }else{
        $.ajax({
            url : '<?php echo Yii::app()->createUrl('mCenter/Auth/sendMsgPassword');?>?' + new Date().getTime(),
            data : {mobile : mobile},
            dataType: "json",
            type : 'post',
            async : false,
            success : function(res){
                if(res.status == '<?php echo ERROR_NONE?>'){
    				$('#msg').attr('MSG','false');
               		intervalid = setInterval("ctrlTime()", 1000);
               		layer.alert('短信发送成功,请注意查收!', {icon: 6});
                } else {
                	layer.alert(res.errMsg, {icon: 5});
                }
            }
        });
    }
}
function ctrlTime() {
    if (mins == 0) {
        clearInterval(intervalid);//取消setInterval()函数设定的定时执行操作
        $('#msg').text('获取验证码');
        $('#msg').attr('MSG','true');
        mins = 59;
        return;
    }
	$('#msg').text(mins + '秒');
    mins--;
}
function ChangeRegisterType(value){
	$('input[name=registerType]').val(value);
}
</script>