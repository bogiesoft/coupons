
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
            autoclose: true
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
        		<div class="name">创建优惠券</div>
    		</div>
    		<div class="couponWrap">
    			<div class="title1">优惠券类型</div>
	    		<?php echo CHtml::beginForm();?>
	    		<div class="con">
	            	  <?php echo CHtml::radioButtonList('Coupons[type]',isset($_POST['Coupons']['type']) ? $_POST['Coupons']['type'] : '', $coupon_type,array('separator'=>'')); ?>
	            </div>
	            <div class="title1">基本信息</div>
	            <div class="re-left">
	                <span class="title">发放时间</span> 
	                  <span>  
	                   <?php echo CHtml::textField('Time',isset($_POST['Time']) ? $_POST['Time'] : '',array('class'=>'txt','style'=>'color:#999;','readOnly'=>"true",'placeholder' => '请输入发放时间')); ?>
	                  <font color="red"><?php echo Yii::app()->user->getFlash('time');?></font>
	                  </span>
	                <span class="title">有效时间</span>
	                <span>
	                	<?php echo CHtml::dropDownList('valid_time', isset($_POST['valid_time']) ? $_POST['valid_time'] : '', $valid_time_type,array('id'=>'validTime','class'=>'')); ?> 
	                	<font color="red"><?php echo Yii::app()->user->getFlash('valid_time_type');?></font>
	                </span>
	                
	                <?php if(!isset($_POST['valid_time']) || empty($_POST['valid_time'])){ ?>
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
	               <?php }elseif (isset($_POST['valid_time']) && $_POST['valid_time'] == VALID_TIME_TYPE_RELATIVE){?>
	               <span style="display:none" class="input-daterange">
	               <?php }elseif (isset($_POST['valid_time']) && $_POST['valid_time'] == VALID_TIME_TYPE_FIXED){?>
	               <span  class="input-daterange">
	               <?php }?>
	                  <?php echo CHtml::textField('Birth',isset($_POST['Birth']) ? $_POST['Birth'] : '',array('class'=>'txt','style'=>'color:#999;','placeholder'=>'选择有效日期','readOnly'=>"true")); ?>                  
	               </span>
	               <font color="red"><?php echo Yii::app()->user->getFlash('fixed_time');?></font>
	                <span class="title">每个用户领取的数量</span>
	                <span>
	                <?php echo CHtml::textField('Coupons[receive_num]',isset($_POST['Coupons']['receive_num']) ? $_POST['Coupons']['receive_num'] : '',array('class'=>'txt','style'=>'color:#999;','placeholder' => '请设置用户可领取数量'))?>  
	                <font color="red"><?php echo Yii::app()->user->getFlash('receive_num');?></font>
	                </span>
	                
	                
	            </div>
	            <div class="re-right">
	            	<span class="title">优惠券的名称</span>
	                <span>
	                	<?php echo CHtml::textField('Coupons[name]',isset($_POST['Coupons']['name']) ? $_POST['Coupons']['name'] : '',array('class'=>'txt','style'=>'color:#999;','placeholder' => '请输入优惠券的名字'))?>
	                     <font color="red"><?php echo Yii::app()->user->getFlash('name');?></font>
	                </span>
	                <span id="valueMoney" class="title">券面额</span>
	                <span id="cou_valueMoney">
	                	<?php echo CHtml::dropDownList('value_money', isset($_POST['value_money']) ? $_POST['value_money'] : '', $face_value_type,array('id'=>'valueMoneyList','class'=>'')); ?> 
	                </span>
	                <span id="moneyspan">
	                    <?php echo CHtml::textField('money',isset($_POST['money']) ? $_POST['money'] : '',array('class'=>'txt','style'=>'color:#999;','id'=>'money','placeholder' => '请输入券面额 大于0 最多两位小数'))?>
	                </span>
	                <font color="red"><?php echo Yii::app()->user->getFlash('value_money');?></font>
	                <span style="display:none" class="money">
	                	<?php echo CHtml::textField('start_money',isset($_POST['start_money']) ? $_POST['start_money'] : '',array('class'=>'txt','style'=>'width:100px')); ?>--<?php echo CHtml::textField('end_money',isset($_POST['end_money']) ? $_POST['end_money'] : '',array('class'=>'txt','style'=>'width:100px')); ?> 
	                </span>
	                <span class="title">发放量</span>
	                <span>
	                   <?php echo CHtml::textField('Coupons[num]',isset($_POST['Coupons']['num']) ? $_POST['Coupons']['num'] : '',array('class'=>'txt','style'=>'color:#999;','placeholder' => '请设置发放量'))?>
	                   <font color="red"><?php echo Yii::app()->user->getFlash('num');?></font>
	                </span>
	                
	                 <span id="discount" class="title">折扣数</span>
	                <span>
	                   <?php echo CHtml::textField('Coupons[discount]',isset($_POST['Coupons']['discount']) ? $_POST['Coupons']['discount'] : '',array('class'=>'txt','style'=>'color:#999;','placeholder' => '请输入折扣数 0至10之前 保留一位小数'))?>
	                   <?php if(isset($_POST['Coupons']['type']) && $_POST['Coupons']['type'] == COUPON_TYPE_DISCOUNT){ ?>
	                 <font color="red"><?php echo Yii::app()->user->getFlash('discount');?></font>
	                 <?php }?>
	                </span>
	                
	                
	                
	            </div>
	            <div class="clear"></div>
	            <div class="title1">使用说明</div>
	            <div class="re-left">
	                <span id="min_pay_money" class="title">最低消费</span>
	                <span>
	                  <?php echo CHtml::textField('Coupons[min_pay_money]',isset($_POST['Coupons']['min_pay_money']) ? $_POST['Coupons']['min_pay_money'] : '',array('class'=>'txt','placeholder' => '请输入最低消费金额'));?>
	                </span>
	                <span id="order_use_num" class="title">单个订单使用张数</span>
	                <span><?php echo CHtml::textField('Coupons[order_use_num]',isset($_POST['Coupons']['order_use_num']) ? $_POST['Coupons']['order_use_num'] : '1',array('class'=>'txt','style'=>'color:#999;','placeholder' => '请设置单个订单使用张数')) ?></span>
	                  <span id="if_with_userdiscount"> <?php echo CHtml::checkBox('Coupons[if_with_userdiscount]',isset($_POST['Coupons']['if_with_userdiscount']) ? $_POST['Coupons']['if_with_userdiscount'] : '');?>与会员折扣同用</span>
	                <span class="title">门店限制<font color="red"><?php echo Yii::app()->user->getFlash('use_store');?></font></span>
	                <?php echo CHtml::checkBox('all_check'); ?>全选
	                <span>
	                	<?php echo CHtml::checkBoxList('Coupons[use_store]', isset($_POST['Coupons']['use_store']) ? $_POST['Coupons']['use_store'] : '', $store_array,array('class'=>'','separator'=>'')); ?> 
	                </span>
	                <span id="refund_deal" class="title">退款处理</span>
	                <span id="coup_refund_deal"><?php echo CHtml::checkBox('Coupons[refund_deal]',isset($_POST['Coupons']['refund_deal']) ? $_POST['Coupons']['refund_deal'] : '');?>退款时退还优惠券</span>
	               
	            </div>
	            <div class="re-right">
	            	<span class="title">使用说明</span>
	                <span><?php echo CHtml::textArea('Coupons[use_illustrate]',isset($_POST['Coupons']['use_illustrate']) ? $_POST['Coupons']['use_illustrate'] : '',array('class'=>'txt','style'=>'width:350px;height:150px'));?></span>
	            </div>
	            <div class="clear"></div>
	        </div>
	        <div class="bottom">
        	 	<?php echo CHtml::submitButton('提交保存',array('class'=>'btn_com_blue','style'=>'margin-right:50px'))?>
        	</div>
        	<?php echo CHtml::endForm();?>
        </div>
</div> 
</body>
<script type="text/javascript">

    window.onload=init;
    function init(){
        var val = '<?php echo isset($_POST['Coupons']['type']) ? $_POST['Coupons']['type'] : ''; ?>';
        if(val == '') {
        	document.getElementById('Coupons_type_0').checked = true;
            $("#discount").hide();
    		$("#Coupons_discount").hide();
    		//$("#max_discount_money").hide();
    		//$("#Coupons_max_discount_money").hide();
        }else {
            $("#Coupons_type input").each(function() {
            	if(val == $(this).val()) {
                	$(this).attr("checked",true);
                	if($(this).val() == <?php echo COUPON_TYPE_CASH; ?>){
            			$("#discount").hide();
            			$("#Coupons_discount").hide();
            			//$("#max_discount_money").hide();
            			//$("#Coupons_max_discount_money").hide();

            			$("#min_pay_money").show();
            			$("#Coupons_min_pay_money").show();
            			$("#refund_deal").show();
            			$("#coup_refund_deal").show();

            			$("#Coupons_order_use_num").show();
            			$("#order_use_num").show();
            			$("#if_with_userdiscount").show();  

            			$("#cou_valueMoney").show();
            			$("#valueMoney").show();
            		}
            		if($(this).val() == <?php echo COUPON_TYPE_DISCOUNT; ?>){
            			$("#discount").show();
            			$("#Coupons_discount").show();
            			//$("#max_discount_money").show();
            			//$("#Coupons_max_discount_money").show();  

            			$("#min_pay_money").show();
            			$("#Coupons_min_pay_money").show();
            			$("#refund_deal").show();
            			$("#coup_refund_deal").show();

            			$("#Coupons_order_use_num").hide();
            			$("#order_use_num").hide();
            			$("#if_with_userdiscount").show();     

            			$("#cou_valueMoney").hide();
            			$("#valueMoney").hide();  
            			$("#money").hide();  
                    }
                    if($(this).val() == <?php echo COUPON_TYPE_EXCHANGE; ?>){
                    	$("#discount").hide();
            			$("#Coupons_discount").hide();
            			//$("#max_discount_money").hide();
            			//$("#Coupons_max_discount_money").hide();

            			$("#min_pay_money").hide();
            			$("#Coupons_min_pay_money").hide();
            			$("#refund_deal").hide();
            			$("#coup_refund_deal").hide();

            			$("#Coupons_order_use_num").hide();
            			$("#order_use_num").hide();
            			$("#if_with_userdiscount").hide();

            			$("#cou_valueMoney").hide();
            			$("#valueMoney").hide();
            			$("#money").hide();  
                    }
            	}
            });
        }
    }
    
	$("#Coupons_type input").click(function() {
		
		if($(this).val() == <?php echo COUPON_TYPE_CASH; ?>){
			$("#discount").hide();
			$("#Coupons_discount").hide();
			//$("#max_discount_money").hide();
			//$("#Coupons_max_discount_money").hide();

			$("#min_pay_money").show();
			$("#Coupons_min_pay_money").show();
			$("#refund_deal").show();
			$("#coup_refund_deal").show();

			$("#Coupons_order_use_num").show();
			$("#order_use_num").show();
			$("#if_with_userdiscount").show();  

			$("#cou_valueMoney").show();
			$("#valueMoney").show();
		}
		if($(this).val() == <?php echo COUPON_TYPE_DISCOUNT; ?>){
			$("#discount").show();
			$("#Coupons_discount").show();
			//$("#max_discount_money").show();
			//$("#Coupons_max_discount_money").show();  

			$("#min_pay_money").show();
			$("#Coupons_min_pay_money").show();
			$("#refund_deal").show();
			$("#coup_refund_deal").show();       

			$("#Coupons_order_use_num").hide();
			$("#order_use_num").hide();
			$("#if_with_userdiscount").show();    

			$("#cou_valueMoney").hide();//券面额
			$("#valueMoney").hide();   

			$("#money").hide(); 
        }
        if($(this).val() == <?php echo COUPON_TYPE_EXCHANGE; ?>){
        	$("#discount").hide();
			$("#Coupons_discount").hide();
			//$("#max_discount_money").hide();
			//$("#Coupons_max_discount_money").hide();

			$("#min_pay_money").hide();
			$("#Coupons_min_pay_money").hide();
			$("#refund_deal").hide();
			$("#coup_refund_deal").hide();

			$("#Coupons_order_use_num").hide();
			$("#order_use_num").hide();
			$("#if_with_userdiscount").hide();

			$("#cou_valueMoney").hide();
			$("#valueMoney").hide();

			$("#money").hide(); 
        }
	});

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
	   var select1 = document.getElementById('valueMoneyList');
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
	   })
</script>

