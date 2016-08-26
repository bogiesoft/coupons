<body onkeydown="on_return();">
<div>
    <?php echo CHtml::beginForm(Yii::app()->createUrl('mCenter/auth/login'), 'post', array('id' => 'loginForm')); ?>
    <div class="n-error">
        <?php if(Yii::app()->user->hasFlash('error')){
            echo Yii::app()->user->getFlash('error');
        } ?>
    </div>
    <div class="n-header">
        <div class="n-header-inner">
            <a href=""><img src="<?php echo GJ_STATIC_IMAGES?>n-logo.png" alt="logo" class="n-logo"></a>
            <div class="n-header-r">
                <span><i class="icon-phone"></i>客服电话：400-882-9998</span>
            </div>
        </div>
    </div>
    <div class="login-con-wrap">
        <div class="login-con">
            <img src="<?php echo GJ_STATIC_IMAGES?>login-img.png" alt="login-cover" class="login-cover">
            <div class="login-form">
                <span class="login-title">玩券管家登录</span>
                <div class="login-form-con">
                    <div class="login-account">
                        <i class="icon-account login-icon"></i>
                        <input type="text" name="username" id="username" placeholder="请输入您的账号" class="text" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>">
                    </div>
                    <div class="login-pw">
                        <i class="icon-pw login-icon"></i>
                        <input type="password" name="password" id="password" placeholder="请输入您的密码" class="text">
                    </div>
                    <div class="login-remember-pw">
                        <a href="<?php echo Yii::app()->createUrl('mCenter/auth/invitationCode'); ?>";return false; class="signup">验证码注册</a>
                        <a href="<?php echo Yii::app()->createUrl('mCenter/auth/retrieve'); ?>" class="forget-pw">忘记密码？</a>
                    </div>
                    <button id='login' type="button" class="login-btn">登录</button>
                </div>
            </div>
        </div>
    </div>
    <?php echo CHtml::endForm();?>
</div>
</body>

<script>
    $(".login-btn").click(function() {
        var username = $("#username").val();
        var password = $("#password").val();
        $.ajax({
            url: '<?php echo (Yii::app()->createUrl('mCenter/auth/loginValid'));?>',
            data: {username: username, password: password},
            type: 'post',
            dataType: 'json',
            success: function (data) {
                if (data.error == 'success') {
                    $("#loginForm").submit();
                } else {
                    $(".n-error").text(data.msg);
                    $(".n-error").slideDown().delay(1000).slideUp();
                }
            }
        });
    });
    //回车时默认登录
    function on_return() {
        if (window.event.keyCode == 13) {
            if (document.all('login') != null) {
                document.all('login').click();
            }
        }
    }
</script>
