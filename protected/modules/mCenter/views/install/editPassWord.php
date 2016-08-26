
<div class="kkfm_r_inner">
    <div class="top">
        <div class="name">修改密码</div>
    </div>
    <?php echo CHtml::beginForm(Yii::app()->createUrl('mCenter/Install/EditPassWord'), 'post');?>
    <div class="contant change_password">
         <div class="filed">
             <span class="label"><em class="red">*</em>旧密码：</span>
             <span class="text">
             	<input name="oldpwd" type="password" class="txt" placeholder="请输入旧密码">
             </span>
             <?php if(Yii::app()->user->hasFlash('error1')){?>
             <span class="text1 red"><?php echo Yii::app()->user->getFlash('error1')?></span>
             <?php }?>
         </div>
      	 <div class="filed">
             <span class="label"><em class="red">*</em>新密码：</span>
             <span class="text">
             	<input name="newpwd" type="password" class="txt" placeholder="请输入新密码（需六位以上数字或字母）">
             </span>
              <?php if(Yii::app()->user->hasFlash('error2')){?>
             <span class="text1 red"><?php echo Yii::app()->user->getFlash('error2')?></span>
             <?php }?>
         </div>
         <div class="filed">
             <span class="label"><em class="red">*</em>确认新密码：</span>
             <span class="text">
             	<input name="newpwd2" type="password" class="txt" placeholder="请确认新密码">
             </span>
             <?php if(Yii::app()->user->hasFlash('error3')){?>
             <span class="text1 red"><?php echo Yii::app()->user->getFlash('error3')?></span>
             <?php }?>
         </div>
         <div class="filed">
             <span class="label"></span>
             <span class="text">
             	<input type="submit" class="btn_com_blue" value="提交">
             </span>
            <!--  <span class="text1"><input type="submit" class="btn_com_blue" value="取消" style="margin-left:30px"></span>--> 
         </div>
    </div>
    <?php echo CHtml::endForm();?>
</div> 
