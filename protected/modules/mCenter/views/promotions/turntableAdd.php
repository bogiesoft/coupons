<body>
	<script type="text/javascript" src="<?php echo SYT_STATIC_JS?>artDialog/jquery.artDialog.js?skin=simple"></script>
	<script type="text/javascript" src="<?php echo SYT_STATIC_JS?>artDialog/plugins/iframeTools.js"></script>
	
	<script type="text/javascript">
		$(document).ready(main_obj.list_init);
	</script>
	
	<div class="kkfm_r_inner">
    	
    	<?php echo CHtml::beginForm() ?>
		<!--搜索框 count-top包含的部分-->
	    <div class="top">
	        <div class="name">添加欢乐大转盘活动</div>
	        <!--<div class="cz"><input type="submit" value="添加商户" class="btn_com_blue"></div>-->
	    </div>
		 <div class="contant">
	         <div class="filed">
	             <span class="label">活动名称</span>
	             <span class="text">
	             	<?php echo CHtml::textField('Activity[name]', $model['name'])?>
	             </span>
	         </div>
	         <div class="filed">
	             <span class="label">活动时间</span>
	             <span class="text">
	             	<?php echo CHtml::textField('Activity[time]', $time)?>
	             </span>
	         </div>
	         <div class="filed">
	             <span class="label">一等奖奖品设置</span>
<!-- 	             <span class="text"> -->
	             	<?php //echo CHtml::textField('Activity[first_prize]', $model['first_prize'])?>
<!-- 	             </span> -->
	             	<span class="text">
		             	<?php echo CHtml::hiddenField('Activity[first_prize]', $model['first_prize'])?>
		             	<a href="javascript:;" onclick="addHeaderHref(<?php echo PRIZE_TYPE_FIRST?>)" id="addFirstHref"><?php echo !empty($title_arr[PRIZE_TYPE_FIRST]['title']) ? $title_arr[PRIZE_TYPE_FIRST]['title'] : "添加卡券链接" ?></a>
		            </span>
	         </div>
	         <div class="filed">
	             <span class="label">一等奖奖品数量</span>
	             <span class="text">
	             	<?php echo CHtml::textField('Activity[first_prize_num]', $model['first_prize_num'])?>
	             </span>
	         </div>
	         <div class="filed">
	             <span class="label">一等奖奖品概率</span>
	             <span class="text">
	             	<?php echo CHtml::textField('Activity[first_prize_probability]', $model['first_prize_probability'])?>
	             </span>
	             <span class="text1">% *百分比，在0-100之间, 支持小数点</span>
	         </div>
	         <div class="filed">
	             <span class="label">二等奖奖品设置</span>
<!-- 	             <span class="text"> -->
	             	<?php //echo CHtml::textField('Activity[second_prize]', $model['second_prize'])?>
<!-- 	             </span> -->
	             	<span class="text">
		             	<?php echo CHtml::hiddenField('Activity[second_prize]', $model['second_prize'])?>
		             	<a href="javascript:;" onclick="addHeaderHref(<?php echo PRIZE_TYPE_SECOND?>)" id="addSecondHref"><?php echo !empty($title_arr[PRIZE_TYPE_SECOND]['title']) ? $title_arr[PRIZE_TYPE_SECOND]['title'] : "添加卡券链接" ?></a>
		             </span>
	         </div>
	         <div class="filed">
	             <span class="label">二等奖奖品数量</span>
	             <span class="text">
	             	<?php echo CHtml::textField('Activity[second_prize_num]', $model['second_prize_num'])?>
	             </span>
	         </div>
	         <div class="filed">
	             <span class="label">二等奖奖品概率</span>
	             <span class="text">
	             	<?php echo CHtml::textField('Activity[second_prize_probability]', $model['second_prize_probability'])?>
	             </span>
	             <span class="text1">% *百分比，在0-100之间, 支持小数点</span>
	         </div>
	         <div class="filed">
	             <span class="label">三等奖奖品设置</span>
<!-- 	             <span class="text"> -->
	             	<?php //echo CHtml::textField('Activity[third_prize]', $model['third_prize'])?>
<!-- 	             </span> -->
	             	<span class="text">
		             	<?php echo CHtml::hiddenField('Activity[third_prize]', $model['third_prize'])?>
		             	<a href="javascript:;" onclick="addHeaderHref(<?php echo PRIZE_TYPE_THIRD?>)" id="addThirdHref"><?php echo !empty($title_arr[PRIZE_TYPE_THIRD]['title']) ? $title_arr[PRIZE_TYPE_THIRD]['title'] : "添加卡券链接" ?></a>
		             </span>
	         </div>
	         <div class="filed">
	             <span class="label">三等奖奖品数量</span>
	             <span class="text">
	             	<?php echo CHtml::textField('Activity[third_prize_num]', $model['third_prize_num'])?>
	             </span>
	         </div>
	         <div class="filed">
	             <span class="label">三等奖奖品概率</span>
	             <span class="text">
	             	<?php echo CHtml::textField('Activity[third_prize_probability]', $model['third_prize_probability'])?>
	             </span>
	             <span class="text1">% *百分比，在0-100之间, 支持小数点</span>
	         </div>
	         <div class="filed">
	             <span class="label"><em class="red">*</em>是否显示奖品数量</span>
	             <span class="text">
	             	<?php echo CHtml::checkBox('Activity[if_show_num]', $model['if_show_num']==SHOW_PRIZE_NUM ? "1" : "0" )?>取消选择后在活动页面中将不会显示奖品数量
	             </span>
	         </div>
	         
	         <div class="filed">
	             <span class="label">每天最多发奖次数</span>
	             <span class="text">
	             	<?php echo CHtml::textField('Activity[everyday_num]', $model['everyday_num'])?>
	             </span>
	         </div>
	         <div class="filed">
	             <span class="label">每人参与的总次数</span>
	             <span class="text">
	             	<?php echo CHtml::textField('Activity[everyone_num]', $model['everyone_num'])?>
	             </span>
	         </div>
	         <div class="filed">
	             <span class="label">每人每天可参与次数</span>
	             <span class="text">
	             	<?php echo CHtml::textField('Activity[everyone_everyday_num]', $model['everyone_everyday_num'])?>
	             </span>
	         </div>
	         <div class="filed">
	             <span class="label">活动说明</span>
	             <span class="text">
	             	<?php echo CHtml::textArea('Activity[illustrate]', $model['illustrate'], array("cols"=>"59", "rows"=>"5"))?>
	             </span>
	         </div>
	      	 
	        
	         <div class="filed">
	             <span class="label"></span>
	             <span class="text">
	              	<input type="submit" class="btn_com_blue"  value="保存">
	             </span>
	             <span class="text1">
	              	<input type="submit" class="btn_com_gray"  value="取消">
	             </span>
	         </div>
	   	</div>
	</div> 
</body>

<script type="text/javascript">

	//添加链接
	function addHeaderHref(prize_type)
	{
		art.dialog.open(
	        	'<?php echo Yii::app()->createUrl('mCenter/promotions/dialogCoupons');?>'+'?prize_type='+prize_type,
	        	 {
	              	 title: '',
	              	 lock: true,
	              	 drag:true,
	                 width: '655px',
// 	              	 height: '446px',
	              	 id: 'dialog_notice',
	              	 background: '#fff'
	             }
	    );
	}

	//添加链接--选取
	function addHrefChoose(prize_type, id, name)
	{
		if(prize_type == <?php echo PRIZE_TYPE_FIRST?>){
			$('#Activity_first_prize').val(id);
			$('#addFirstHref').text(name);
			art.dialog.get("dialog_notice").close(); //关闭弹出框
		}else if(prize_type == <?php echo PRIZE_TYPE_SECOND?>){
			$('#Activity_second_prize').val(id);
			$('#addSecondHref').text(name);
			art.dialog.get("dialog_notice").close(); //关闭弹出框
		}else if(prize_type == <?php echo PRIZE_TYPE_THIRD?>){
			$('#Activity_third_prize').val(id);
			$('#addThirdHref').text(name);
			art.dialog.get("dialog_notice").close(); //关闭弹出框
		}
	}
		
	//调用时间控件
	$('input[name=Activity\\[time\\]]').daterangepicker({
		timePicker:false,
		format:'YYYY/MM/DD',
		dateLimit:true,
		maxDate:true
	});

</script>
