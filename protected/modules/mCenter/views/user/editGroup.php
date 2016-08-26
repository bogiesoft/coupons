<script type="text/javascript">
	$(document).ready(main_obj.list_init);
</script>
<?php $form = $this->beginWidget('CActiveForm')?>
<div class="kkfm_r_inner">
    <div class="contant">
        <div class="filed">
            <span class="label"><em class="red">*</em>分组名称</span>
            <span class="text">
                <input id="d2a25" type="text" class="Wdate" name="group_name" value="<?php echo isset($seegroup['group_name']) ? $seegroup['group_name'] : ''?>"/> 
            <span class="text1 red">
                <?php if (Yii::app()->user->hasFlash('group_name')) {
                   echo Yii::app()->user->getFlash('group_name');
                }?>
            </span> 
         </div>
         <div class="filed">
             <span class="label"></span>
             <span class="text">
                 <?php echo CHtml::submitButton('修改',array('class'=>'btn_com_blue'))?>              	
             </span>
         </div>
    </div>
</div> 
<?php $form = $this->endWidget()?>

