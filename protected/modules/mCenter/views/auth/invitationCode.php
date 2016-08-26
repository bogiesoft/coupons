
<div class="n-header">
    <div class="n-header-inner">
        <a href="<?php echo Yii::app() -> createUrl('mCenter/auth/login')?>"><img src="<?php echo WQ_STATIC_IMAGES?>n-logo.png" alt="logo" class="n-logo"></a>
        <div class="n-header-r">
            <span><i class="icon-phone"></i>客服电话：400-882-9998</span>
        </div>
    </div>
</div>
<div class="register-step register-step-1">
</div>
<div class="register-box input-vcode-wrapper">
    <?php echo CHtml::beginForm('','post',array('id'=>'from')); ?>
    <div class="register-form-group">
        <div class="register-label">
            <i class="register-icon register-icon-vcode"></i>
            <span class="register-label-text">验证码：</span>
        </div>
        <div class="register-input">
            <input type="text" class="form-control" placeholder="请输入验证码" name="Code">
        <?php if (Yii::app()->user->hasFlash('error')) {
                echo '<em class="register-error">'.Yii::app()->user->getFlash("error").'</em>';
            } 
        ?>
                        
        </div>
    </div>
    <input type="submit" class="btn btn-primary btn-lg btn-block-sm block-center" value="下一步">
    <?php echo CHtml::endForm(); ?>
</div>