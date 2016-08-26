<div class="kkfm_r_inner">
<div class="top">
        <div class="name">添加储值活动</div>
    </div>
<div class="contant">
    <div class="count-top">
    <?php echo CHtml::beginForm();?>
      <div class="search clearfix">
             <!--  <font color="red"> <?php //echo isset($msg)?$msg:''; ?></font>-->

                <span><label>预存金额：</label>
                <?php echo CHtml::textField('Stored[stored_money]','',array('class'=>'txt','style'=>'color:#999; width:186px;'))?>
               <font color="red"> <?php echo Yii::app()->user->getFlash('stored_money'); ?></font>
               </span>

               <span><label>赠送金额：</label> 
                <?php echo CHtml::textField('Stored[get_money]','',array('class'=>'txt','style'=>'color:#999; width:186px;'))?>
               <font color="red"> <?php echo Yii::app()->user->getFlash('get_money'); ?></font>
               </span>
               <span> 
                 <label>活动时间：</label>	
                <?php echo CHtml::textField('Time','',array('class'=>'txt','style'=>'color:#999; width:186px;')); ?>
                <font color="red"> <?php echo Yii::app()->user->getFlash('time'); ?></font>
                </span>
            
             <span class="btnWap">
                    <?php echo CHtml::submitButton('提交保存',array('class'=>'btnstyle'))?>

                </span>
      </div>
      <?php echo CHtml::endForm();?>
    </div>
   </div>
</div>
    <script>
       $(document).ready(statistics_obj.stat_init);
    </script>
