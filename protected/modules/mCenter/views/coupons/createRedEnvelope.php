<body>
<script type="text/javascript">
	$(document).ready(main_obj.list_init);
	$(document).ready(statistics_obj.stat_init);

	//发放时间
	$(function(){
		var now = new Date();
		$('input[name=Time]').daterangepicker({
        	timePicker: false,
        	format: 'YYYY/MM/DD',
        	dateLimit: true,
        	maxDate: true,
        	minDate:now.toLocaleDateString()
   		});
	});

	//有效时间
	$(function(){
		var now = new Date();
		$('input[name=Birth]').daterangepicker({
            timePicker: false,
            format: 'YYYY/MM/DD',
            dateLimit: true,
            maxDate: true,
            minDate:now.toLocaleDateString()
        });
	});
	

	$(function () {
        $('.input-daterange').datepicker({
            format: "yyyy/mm/dd",
            todayBtn: false,
            keyboardNavigation: true,
            language: "zh-CN",
            forceParse: true,
            orientation: "top auto",
            autoclose: true,
        });
    });

	function convertToStamp(date) {
        var arrDate = date.split('/');
        var d = new Date();
        d.setFullYear(arrDate[0]);
        d.setMonth(arrDate[1]);
        d.setDate(arrDate[2]);
        return Date.parse(d);
    }
</script>

<div class="kkfm_r_inner">
      	 <div class="coupons">
         	<div class="top">
        		<div class="name">创建红包</div>
    		</div>
    		<div class="couponWrap">
	    		<?php echo CHtml::beginForm();?>
	    		<div class="title1">基本信息</div>
	            <div class="re-left">
	                <span class="title">红包名称</span>
	                <span>
	                <?php echo CHtml::textField('Coupons[name]',isset($_POST['Coupons']['name']) ? $_POST['Coupons']['name'] : '',array('class'=>'txt','style'=>'color:#999;','placeholder'=>'请输入红包名称'))?>
	                <font color="red"><?php echo Yii::app()->user->getFlash('name');?></font>
	                </span>
	                <span class="title">发放时间</span>
	                <span>
	                <?php echo CHtml::textField('Time',isset($_POST['Time']) ? $_POST['Time'] : '',array('class'=>'txt','style'=>'color:#999;','readOnly'=>"true",'placeholder'=>'请设置发放时间')); ?>
	                <font color="red"><?php echo Yii::app()->user->getFlash('time');?></font>
	                </span>
	                <span class="title">有效时间</span>
	                <span>
	                  <?php echo CHtml::dropDownList('valid_time', isset($_POST['valid_time']) ? $_POST['valid_time'] : '', $valid_time_type,array('id'=>'validTime','class'=>'')); ?> 
	                  <font color="red"><?php echo Yii::app()->user->getFlash('valid_time_type');?></font>
	                </span>
	                
	                <?php if(!isset($_POST['valid_time']) || empty($_POST['valid_time'])){?>
	                <span class="validtime_fixed_value">
	                <?php }elseif (isset($_POST['valid_time']) && $_POST['valid_time'] == VALID_TIME_TYPE_RELATIVE){?>
	                <span class="validtime_fixed_value">
	                <?php }elseif (isset($_POST['valid_time']) && $_POST['valid_time'] == VALID_TIME_TYPE_FIXED){?>
	                <span style="display:none" class="validtime_fixed_value">
	                <?php }?>
	               
	                  <?php echo CHtml::textField('Coupons[validtime_fixed_value]',isset($_POST['Coupons']['validtime_fixed_value']) ? $_POST['Coupons']['validtime_fixed_value'] : '',array('class'=>'txt','style'=>'color:#999;','placeholder' =>'输入有效天数'))?>
	               </span><font color="red"><?php echo Yii::app()->user->getFlash('relative_time');?></font>
	            
	               <?php if(!isset($_POST['valid_time']) || empty($_POST['valid_time'])) {?>
	               <span style="display:none" class="input-daterange">
	              
	               <font color="red"><?php echo Yii::app()->user->getFlash('fixed_time');?></font>
	               <?php }elseif (isset($_POST['valid_time']) && $_POST['valid_time'] == VALID_TIME_TYPE_RELATIVE){?>
	               <span style="display:none" class="input-daterange">
	               
	               <font color="red"><?php echo Yii::app()->user->getFlash('fixed_time');?></font>
	               <?php }elseif (isset($_POST['valid_time']) && $_POST['valid_time'] == VALID_TIME_TYPE_FIXED){?>
	               <span  class="input-daterange">
	               
	               <?php }?>
	           
	            <?php echo CHtml::textField('Birth',isset($_POST['Birth']) ? $_POST['Birth'] : '',array('class'=>'txt','style'=>'color:#999;','placeholder'=>'选择有效日期','readOnly'=>"true")); ?>
	               </span>
	               <font color="red"><?php echo Yii::app()->user->getFlash('fixed_time');?></font>
	               
	            </div>
	            <div class="re-right">
	                <span class="title">券面额</span>
	                <span>
	                	<?php echo CHtml::dropDownList('value_money', isset($_POST['value_money']) ? $_POST['value_money'] : '', $face_value_type,array('id'=>'valueMoney','class'=>'')); ?> 
	                </span>
	                <span id="moneyspan">
	                    <?php echo CHtml::textField('money',isset($_POST['money']) ? $_POST['money'] : '',array('class'=>'txt','style'=>'color:#999;','id'=>'money','placeholder'=>'请输入红包面额'))?>
	                    <br><font color="red"><?php echo Yii::app()->user->getFlash('value_money');?></font>
	                    <?php if(!empty($_POST['value_money'])) {?>
	                        <?php if($_POST['value_money']==FACE_VALUE_TYPE_FIXED) {?>
	                           <font color="red"><?php echo Yii::app()->user->getFlash('money');?></font>
	                       <?php }else{?>
	                           <font color="red"><?php echo Yii::app()->user->getFlash('sj_money');?></font>
	                       <?php }?>
	                    <?php }?>
	                </span>
	                <span style="display:none" class="money">
	                <?php echo CHtml::textField('start_money',isset($_POST['start_money']) ? $_POST['start_money'] : '',array('class'=>'txt','style'=>'width:100px')); ?>--<?php echo CHtml::textField('end_money',isset($_POST['end_money']) ? $_POST['end_money'] : '',array('class'=>'txt','style'=>'width:100px')); ?>
	                 
	                </span>
	                <span class="title">发放量</span>
	                <span><?php echo CHtml::textField('Coupons[num]',isset($_POST['Coupons']['num']) ? $_POST['Coupons']['num'] : '',array('class'=>'txt','style'=>'color:#999;','placeholder'=>'请设置红包发放量'))?>
	                <font color="red"><?php echo Yii::app()->user->getFlash('num');?></font>
	                </span>
	                 <span class="title">每个用户领取的数量</span>
	                <span>
	                <?php echo CHtml::textField('Coupons[receive_num]',isset($_POST['Coupons']['receive_num']) ? $_POST['Coupons']['receive_num'] : '',array('class'=>'txt','style'=>'color:#999;','placeholder'=>'请设置每个用户可领取数量'))?>  
	                <font color="red"><?php echo Yii::app()->user->getFlash('receive_num');?></font>
	                </span>
	            </div>
	            <div class="clear"></div>
	            <div class="title1">使用说明</div>
	            <div class="re-left">
	                 <span id="order_use_num" class="title">单个订单使用张数</span>
	                <span><?php echo CHtml::textField('Coupons[order_use_num]',isset($_POST['Coupons']['order_use_num']) ? $_POST['Coupons']['order_use_num'] : '1',array('class'=>'txt','style'=>'color:#999;','placeholder'=>'请输入单个订单使用数量')) ?>
	                <font color="red"><?php echo Yii::app()->user->getFlash('order_use_num');?></font></span>
	                <!--  <span> <?php //echo CHtml::checkBox('Coupons[allow_many]',isset($_POST['Coupons']['allow_many']) ? $_POST['Coupons']['allow_many'] : '');?>允许多个优惠券同时使用</span>-->
	                <span> <?php echo CHtml::checkBox('Coupons[if_with_userdiscount]',isset($_POST['Coupons']['if_with_userdiscount']) ? $_POST['Coupons']['if_with_userdiscount'] : '');?>与会员折扣同用</span>
	                <span> <?php echo CHtml::checkBox('Coupons[if_with_coupons]',isset($_POST['Coupons']['if_with_coupons']) ? $_POST['Coupons']['if_with_coupons'] : '');?>与优惠券同用</span>
	                <span class="title">门店限制<font color="red"><?php echo Yii::app()->user->getFlash('use_store');?></font></span>
	                <?php echo CHtml::checkBox('all_check'); ?>全选
	                <span>
	                	 <?php echo CHtml::checkBoxList('Coupons[use_store]', isset($_POST['Coupons']['use_store']) ? $_POST['Coupons']['use_store'] : '', $store_array,array('prompt' => '全部','class'=>'','separator'=>'')); ?> 
	                </span>
	                <span class="title">退款处理</span>
	                <span> <?php echo CHtml::checkBox('Coupons[refund_deal]',isset($_POST['Coupons']['refund_deal']) ? $_POST['Coupons']['refund_deal'] : '');?>退款时退还优惠券</span>
	               
	            </div>
	            <div class="re-right">
	            	<span class="title">使用说明</span>
	                <span><?php echo CHtml::textArea('Coupons[use_illustrate]',isset($_POST['Coupons']['use_illustrate']) ? $_POST['Coupons']['use_illustrate'] : '',array('class'=>'txt','style'=>'width:350px;height:150px'));?></span>
	            </div>
	            <div class="clear"></div>
	        </div>
	        <div class="bottom">
            	<span> <?php echo CHtml::submitButton('提交保存',array('class'=>'btn_com_blue','style'=>'margin-right:50px'))?></span>
        	</div>
        	<?php echo CHtml::endForm();?>
        </div>
</div> 
</body>
<script type="text/javascript">
   //有效时间下拉事件
   var select = document.getElementById('validTime');
   select.onchange = function() {
       var index = select.selectedIndex;// selectedIndex代表的是你所选中项的index
       var val = select.options[index].value;
       if (val == <?php echo VALID_TIME_TYPE_FIXED; ?>) {

           $(".validtime_fixed_value").hide();
           $(".input-daterange").show();
       } else {
           $(".validtime_fixed_value").show();
           $(".input-daterange").hide();
       }
   }

   //券面额下拉事件
   var select1 = document.getElementById('valueMoney');
   select1.onchange = function() {
       var index = select1.selectedIndex;// selectedIndex代表的是你所选中项的index
       var val = select1.options[index].value;
       if (val == <?php echo FACE_VALUE_TYPE_FIXED; ?>) {

           $(".money").hide();
           $("#money").show();
           $('#moneyspan').show();
       } else {
           $(".money").show();
           $("#money").hide();
           $('#moneyspan').hide();
       }
   }

   //全选 复选框 选中状态
   $("#all_check").click(function(){
	   if($("#all_check").prop("checked")){
		   $("[name='Coupons[use_store][]']").prop("checked",true);//全选     
		}else{
		   $("[name='Coupons[use_store][]']").prop("checked",false);
		   //$("[name='Coupons[use_store][]']").removeAttr("checked");//取消全选  
        }
	});
	var CouponsNum = 0;
   $("[name='Coupons[use_store][]']").click(function(){
	   	//当某个多选框没选中 及 全选框选中，则把全选框设置成未选中状态
		if(!$(this).prop("checked") && $("#all_check").prop("checked")){
			$("#all_check").prop("checked",false);
		}

		//计算所有使用门店选中的个数
		$("[name='Coupons[use_store][]']").each(function(){
			if($(this).prop("checked")){
				CouponsNum ++;
			}
		})
		//当选中门店的个数和所有门店的总数相等的时候，就把全选框选中
		if(CouponsNum == $("[name='Coupons[use_store][]']").length){
			$("#all_check").prop("checked",true);
		}
		CouponsNum = 0; //把值重新初始为0，不然下次计算就会一直叠加上去
   });
	
</script>
