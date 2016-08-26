
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
<div class="register-box register-active-account-wrapper">
    <h3 class="fw mt30">激活账号</h3>
    <p class="mt15">恭喜，您已成功注册玩券管家，系统已经自动向注册邮箱<span><?php echo $email;?></span>发送了一封激活邮件，请在24小时内登录该邮箱点击邮件中的确认链接后来完成注册。</p>
    <div class="form-inline mt20">
        <button type="button" class="btn btn-primary m-btn-lg" onclick="postEmailAgin()" id="post_agin">重新发送</button>
        <span class="pl10" id="countdown_tip"><time id="countdown">60</time>s后可以重新发送</span>
    </div>
    <h3 class="fw mt30">如果您没有收到激活邮件</h3>
    <ul class="unreceive-email-tips">
        <li>1、请确认邮件地址是否正确，更换<a href="<?php echo Yii::app() -> createUrl('mCenter/auth/register',array('code'=>$code,'order_id'=>$order_id,'registerType'=>2))?>" class="text-orange">邮箱注册</a>；</li>
        <li>2、请查看您的垃圾邮件文件夹中是否有来自玩券管家的邮件;</li>
        <li>3、因网络情况不同，邮件可能延迟收到，若60秒后仍未收到，请点击重新发送激活邮件;</li>
        <li>4、如有更多问题请联系客服400-882-9998。</li>
    </ul>
</div>
<script type="text/javascript">
$(function(){
	mins = 60;
	$("#post_agin").addClass('disabled');
	$("#post_agin").attr('disabled',true);
	intervalid = setInterval("ctrlTime()", 1000);
})
//重新发送邮件
function postEmailAgin(){
	$("#post_agin").addClass('disabled');
	$("#post_agin").attr('disabled',true);
	$("#countdown_tip").show();
	var email = '<?php echo $email?>';
	var id = '<?php echo $encrypt_id?>';
	var code = '<?php echo $code?>';
    $.ajax({
        url : '<?php echo Yii::app()->createUrl('mCenter/Auth/postEmailAgin');?>?' + new Date().getTime(),
        data : {email : email,id : id, code : code},
        dataType: "json",
        type : 'post',
        async : false,
        success : function(res){
        	if(res == true){
           		intervalid = setInterval("ctrlTime()", 1000);
           		layer.alert('邮件发送成功,请注意查收', {icon: 6});
            } else {
            	layer.alert('邮件发送失败', {icon: 5});
            	$("#post_agin").removeClass('disabled');
                $("#post_agin").removeAttr('disabled');
            }
        }
    });
}
//倒计时
function ctrlTime() {
    if (mins == 0) {
        clearInterval(intervalid);//取消setInterval()函数设定的定时执行操作
        $("#post_agin").removeClass('disabled');
        $("#post_agin").removeAttr('disabled');
        $("#countdown_tip").hide();
        mins = 60;
        $('#countdown').text(mins);
        return;
    }
    mins--;
    $('#countdown').text(mins);
}
</script>