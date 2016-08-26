
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
        <span class="lable">新密码</span>
        <span class="text">
           <?php echo CHtml::passwordField('Account[pwd]','',array('placeholder'=>'请输入新密码','class'=>'txt')); ?>
        </span>
        <?php if (Yii::app()->user->hasFlash('pwd_error')) {?>
          <span class="error">
          	<span class="ico"></span>
          	<?php 
                echo Yii::app()->user->getFlash('pwd_error');
           }?>
         </span>
    </div>
     <div class="fild">
        <span class="lable">确认密码</span>
        <span class="text">
           <?php echo CHtml::passwordField('Account[confirm]','',array('placeholder'=>'请输入确认密码','class'=>'txt')); ?>
        </span>
        <?php if (Yii::app()->user->hasFlash('confirm_error')) {?>
          <span class="error">
          	<span class="ico"></span>
          	<?php 
                echo Yii::app()->user->getFlash('confirm_error');
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
          <input type="submit" id="beforeBt" value="上一步" class="btn_com" style="margin-right:30px">
          <?php echo CHtml::submitButton('下一步',array('class'=>'btn_com')) ?>
        </span>
    </div>
</div>
<?php echo CHtml::endForm(); ?>
<div class="footer">
	<p>Copyright@2015仁通科技版权所有 增值电信业务经营许可证：浙B2-20110288</p>
</div>

</body>
<script type="text/javascript">
     $("#beforeBt").click(function(){
        location.href = '<?php echo Yii::app()->createUrl('mCenter/auth/retrieve'); ?>';
        return false;
     });      
</script>


