
<div class="kkfm_r_inner">
	<!--搜索框 count-top包含的部分-->
    <div class="top">
        <div class="name">添加操作员</div>
        <!--<div class="cz"><input type="submit" value="添加商户" class="btn_com_blue"></div>-->
    </div>
	 <div class="contant operator">
	 <?php echo CHtml::beginForm('','post',array('id'=>'addform')) ?>
         <div class="filed">
             <span class="label"><em class="red">*</em>编号</span>
             <span class="text">
             	<?php echo CHtml::textField('Operator[number]', isset($_POST['Operator']['number'])?$_POST['Operator']['number']:'', array('class' => 'txt', 'placeholder' => '4位或5位数字', 'maxlength' => '5', 'style' => ''))?>
             </span>
             <span id="number_error" class="text1 red">
             <?php if (Yii::app()->user->hasFlash('number_error')) {
             	echo Yii::app()->user->getFlash('number_error');
             }?>
             </span>
         </div>
         <div class="filed">
             <span class="label"><em class="red">*</em>角色</span>
             <span class="text">
             	<?php echo CHtml::radioButtonList('Operator[role]', empty($model['role']) ? OPERATOR_ROLE_NORMAL : $model['role'], array(OPERATOR_ROLE_ADMIN => $GLOBALS['__OPERATOR_ROLE'][OPERATOR_ROLE_ADMIN], OPERATOR_ROLE_NORMAL => $GLOBALS['__OPERATOR_ROLE'][OPERATOR_ROLE_NORMAL]), array('separator'=>''))?>
             </span>
         </div>
         <div class="filed">
             <span class="label"><em class="red">*</em>账号</span>
             <span class="text">
             	<?php echo CHtml::textField('Operator[account]', isset($_POST['Operator']['account'])?$_POST['Operator']['account']:$m_account.$model['account'], array('class' => 'txt', 'readonly' => 'true', 'onkeydown' => 'return noBackspace(event);'))?>
             </span>
         </div>
         <div class="filed">
             <span class="label"><em class="red">*</em>密码</span>
             <span class="text">
             	<?php echo CHtml::PasswordField('Operator[pwd]', isset($_POST['Operator']['pwd'])?$_POST['Operator']['pwd']:'', array('class' => 'txt', 'placeholder' => '8位数字或字母', 'style' => ''))?>
             </span>
             <span class="text1 red">
             <?php if (Yii::app()->user->hasFlash('pwd_error')) {
             	echo Yii::app()->user->getFlash('pwd_error');
             }?>
             </span>
         </div>
         <div class="filed">
             <span class="label"><em class="red">*</em>姓名</span>
             <span class="text">
             	<?php echo CHtml::textField('Operator[name]', isset($_POST['Operator']['name'])?$_POST['Operator']['name']:'', array('class' => 'txt', 'style' => ''))?>
             </span>
             <span class="text1 red">
             <?php if (Yii::app()->user->hasFlash('name_error')) {
             	echo Yii::app()->user->getFlash('name_error');
             }?>
             </span>
         </div>
         <div class="filed">
             <span class="label"><em class="red">*</em>所属门店</span>
             <span class="text">
             	<?php echo CHtml::dropDownList('Operator[store_id]', $model['store_id'], $stores, array('style' => ''))?>
             </span>
         </div>
         <div class="filed" id="admin_pwd">
             <span class="label"><em class="red">*</em>店长密码</span>
             <span class="text">
             	<?php echo CHtml::textField('Operator[admin_pwd]', $model['admin_pwd'], array('class' => 'txt', 'onkeydown' => 'return noBackspace(event);', 'readonly' => 'true', 'title' => '点击刷新密码'))?>
             	<span style="color: #999">点击文本框可重置店长密码</span>
             </span>
             <span class="text1 red">
             <?php if (Yii::app()->user->hasFlash('admin_error')) {
             	echo Yii::app()->user->getFlash('admin_error');
             }?>
             </span>
         </div>
         <div class="filed">
             <span class="label"></span>
             <span class="text">
              	<input type="submit" class="btn_com_blue"  value="保存" >
             </span>
             <span class="text1">
              	<input type="submit" class="btn_com_gray" value="取消" onclick="fixmenu();location.href='<?php echo Yii::app()->createUrl("mCenter/operator/operator")?>';return false;">
             </span>
         </div>
    <?php echo CHtml::endForm() ?>
   	</div>
    <?php if (Yii::app()->user->hasFlash('error')) { ?>
    	<script>alert('<?php echo Yii::app()->user->getFlash('error')?>')</script>
    <?php }?>
</div>
<script>
	$(function() {
		if($("#Operator_role_0").attr("checked")) {
			$("#admin_pwd").show();
		}else {
			$("#admin_pwd").hide();
		}
	});

	function createPwd() {
		$.ajax({
            url: '<?php echo(Yii::app()->createUrl('mCenter/operator/createPwd'));?>',
            type: 'get',
            success: function (data) {
                $("#Operator_admin_pwd").val(data);
            }
        });
	}

	$(function(){
        addOperator_Operator_number('<?php echo $m_account?>','<?php echo(Yii::app()->createUrl('mCenter/operator/CheckOperatorNumber'));?>');
        addOperator_createPwd('<?php echo(Yii::app()->createUrl('mCenter/operator/createPwd'));?>');
    });
	//更改导航位置
    function fixmenu(){
        var obj = window.parent.document.getElementsByClassName('bg');
        var obj2 = obj[0].getElementsByTagName('a');
        for(i=0;i<obj2.length;i++){
            obj2[i].className = '';

        }
        obj2[1].className = 'cur';
    }
</script>
<script src="<?php echo GJ_STATIC_JS."gj.js"?>"></script>