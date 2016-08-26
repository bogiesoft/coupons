
<html>
<head>
</head>
<body>

<div class="kkfm_r_inner">
		<div class="top">
        	<div class="name">基本设置</div>
    	</div>
      	 <div class="coupons">
    		<?php echo CHtml::beginForm();?>
            <div class="re-left">
                
                <span class="title">是否开启储值功能:
                <?php echo CHtml::radioButtonList('Merchant[if_stored]', isset($merchant ->if_stored) ?$merchant ->if_stored : IF_STORED_NO, $if_stored_array,array('separator'=>'')); ?>
                </span>
                
               
            <div class="btn">
            <span> <?php echo CHtml::submitButton('提交保存',array('class'=>'btn_com_blue','style'=>'margin-right:50px'))?></span>
        </div></div>
        <?php echo CHtml::endForm();?>
        </div>
     
</div> 
</body>
</html>
<script type="text/javascript">

</script>
