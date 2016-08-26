<body>
    <div class="kkfm_r_inner">
    <?php echo CHtml::beginForm('','post',array('name' => 'order_form')) ?>
        <div class="storeManage">
            <div class="top">
                <div class="name">
                    续费升级
                </div>
            </div>
            <div class="v-container">
                <div class="v-renew-item">
                    <div class="v-renew-title">
                        <span>开通版本：</span>
                    </div>
                    <div class="v-renew-con">
                        <a href="javascript:;" class="v-btn-renew">营销版</a>
                        <input style="display: none" value="<?php echo STORE_EDITION_MARKETING?>" name="edition">
                    </div>
                </div>
                <div class="v-renew-item">
                    <div class="v-renew-title">
                        <span>门店数量：</span>
                    </div>
                    <div class="v-renew-con">
                        <span id="store_num_show">已选择0家门店<a href="javascript:;" class="v-renew-add" onclick="showSelectStore()">修改</a></span>
                        <input style="display: none" value="" name="store_id_arr" id="store_id_arr">
                        <span class="v-error" id="store_id_arr_error" style="display: none"></span>
                    </div>
                </div>
                <div class="v-renew-item">
                    <div class="v-renew-title">
                        <span>付费模式：</span>
                    </div>
                    <div class="v-renew-con">
                        <div class="v-inline-checkbox">
                            <input type="radio" name="payMode" id="payMode-month" value="<?php echo STORE_ORDER_PAY_TYPE_MONTH?>" checked>
                            <label for="payMode-month" class="v-btn-renew">按月</label>
                        </div>
                        <div class="v-inline-checkbox">
                            <input type="radio" name="payMode" id="payMode-year" value="<?php echo STORE_ORDER_PAY_TYPE_YEAR?>">
                            <label for="payMode-year" class="v-btn-renew">按年</label>
                        </div>
                    </div>
                </div>
                <div class="v-renew-item">
                    <div class="v-renew-title">
                        <span>开通时长：</span>
                    </div>
                    <div class="v-renew-con">
                        <input type="text" name="time" id="time" class="v-form-control v-s-text" onkeyup='this.value=this.value.replace(/\D/gi,"")' value="<?php if(isset($_POST['time']) && !empty($_POST['time'])){echo $_POST['time'];}?>">
                        <span class="v-label">月</span>
                        <span class="v-error" id="time_error" style="display: none"></span>
                    </div>
                </div>
                <div class="v-renew-item">
                    <div class="v-renew-title">
                        <span>费用说明：</span>
                    </div>
                    <div class="v-renew-con">
                        <span>单门店<?php if(strtotime(date('Y-m-d H:i:s'))<=strtotime(STORE_ACTIVITY_END_TIME) && strtotime(date('Y-m-d H:i:s')) >= strtotime(STORE_ACTIVITY_START_TIME)){?>，活动期间可享前三年<em class="wq-orange"><?php echo number_format(STORE_ACTIVITY_YEAR_FEE,2)?></em>元/年，<em class="wq-orange"><?php echo number_format(STORE_ACTIVITY_MONTH_FEE,2)?></em>元/月(活动期间：<?php echo date('Y.m.d',strtotime(STORE_ACTIVITY_START_TIME))?> - <?php echo date('Y.m.d',strtotime(STORE_ACTIVITY_END_TIME))?>)，之后<?php }?><em class="wq-orange"><?php echo number_format(STORE_YEAR_FEE,2)?></em>元/年，<em class="wq-orange"><?php echo number_format(STORE_MONTH_FEE,2)?></em>元/月</span>
                    </div>
                </div>
                <div class="v-renew-item">
                    <div class="v-renew-title">
                        <span>应付金额：</span>
                    </div>
                    <div class="v-renew-con">
                        <span><em class="wq-orange v-fee">0.00</em>元<a href="javascript:;" class="v-fee-detail" onclick="showfeedetails()">费用明细</a></span>
                    </div>
                </div>
                <div class="v-renew-item">
                    <div class="v-renew-title v-label-t">
                        <span>是否发票：</span>
                    </div>
                    <div class="v-renew-con">
                        <div class="v-inline-checkbox">
                            <label class="v-label-invoice">
                                <input type="radio" name="invoice" id="" value="<?php echo STORE_ORDER_IF_INVOICE_NO?>" checked  onclick="hideInvoice()">否</label>
                        </div>
                        <div class="v-inline-checkbox">
                            <label class="v-label-invoice">
                                <input type="radio" name="invoice" id="" value="<?php echo STORE_ORDER_IF_INVOICE_YES?>" onclick="showInvoice()">是</label>
                        </div>
                        <div class="v-invoice-con" <?php if(!isset($_POST['invoice']) || $_POST['invoice'] == STORE_ORDER_IF_INVOICE_NO){?>style="display: none"<?php }?>>
                            <div class="v-invoice-item">
                                <div class="v-renew-title">
                                    <span>发票抬头：</span>
                                </div>
                                <div class="v-renew-con">
                                    <input type="text" name="invoice_header" id="" class="v-form-control v-b-text" placeholder="请输入发票抬头" value="<?php if(isset($_POST['invoice_header']) && !empty($_POST['invoice_header'])){echo $_POST['invoice_header'];}?>">
                                    <span class="v-error" id="invoice_header_error" style="display: none"></span>
                                </div>
                            </div>
                            <div class="v-invoice-item">
                                <div class="v-renew-title">
                                    <span>收件人：</span>
                                </div>
                                <div class="v-renew-con">
                                    <input type="text" name="addressee" id="" class="v-form-control v-b-text" placeholder="请输入收件人姓名" value="<?php if(isset($_POST['addressee']) && !empty($_POST['addressee'])){echo $_POST['addressee'];}?>">
                                    <span class="v-error" id="addressee_error" style="display: none"></span>
                                </div>
                            </div>
                            <div class="v-invoice-item">
                                <div class="v-renew-title">
                                    <span>联系方式：</span>
                                </div>
                                <div class="v-renew-con">
                                    <input type="text" name="tel" id="" class="v-form-control v-b-text" placeholder="请输入请输入手机号或者电话" value="<?php if(isset($_POST['tel']) && !empty($_POST['tel'])){echo $_POST['tel'];}?>">
                                    <span class="v-error" id="tel_error" style="display: none"></span>
                                </div>
                            </div>
                            <div class="v-invoice-item">
                                <div class="v-renew-title v-label-t">
                                    <span>收取地址：</span>
                                </div>
                                <div class="v-renew-con">
                                    <div class="v-address">
                                    <?php
                                        $this->widget('application.modules.mCenter.components.widgets.SelectorWidget', array(
                                            'model' => $pca,
                                            'attributeProvince' => 'liveplaceProvince',
                                            'attributeCity' => 'liveplaceCity',
                                            'attributeArea' => 'liveplaceArea',
                                            'htmlOptions' => array('class' => 'v-form-control')
                                        ));
                                    ?>
                                    </div>
                                    <input type="text" name="address_details" id="" class="v-form-control v-b-text" placeholder="请输入详细地址" value="<?php if(isset($_POST['address_details']) && !empty($_POST['address_details'])){echo $_POST['address_details'];}?>">
                                    <span class="v-error" id="address_details_error" style="display: none"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="v-renew-btn-wrap">
                    <button type="button" class="wq-btn-primary" onclick="submitCheck()">提交订单</button>
                </div>
            </div>
        </div>
        <?php echo CHtml::endForm() ?>
        <!-- 费用明细弹窗 -->
        <div class="wq-pop" id="fee_details" style="display: none">
            <div class="wq-pop-title">
                <h3>费用明细</h3>
                <i class="wq-pop-close fr" onclick="hidefeedetails()">&#10006;</i>
            </div>
            <div class="wq-pop-con">
                <p>费用说明：<?php if(strtotime(date('Y-m-d H:i:s'))<=strtotime(STORE_ACTIVITY_END_TIME) && strtotime(date('Y-m-d H:i:s')) >= strtotime(STORE_ACTIVITY_START_TIME)){?>单门店，活动期间可享前三年<em class="wq-orange"><?php echo number_format(STORE_ACTIVITY_YEAR_FEE,2)?></em>元/年，<em class="wq-orange"><?php echo number_format(STORE_ACTIVITY_MONTH_FEE,2)?></em>元/月(活动期间：<?php echo date('Y.m.d',strtotime(STORE_ACTIVITY_START_TIME))?> - <?php echo date('Y.m.d',strtotime(STORE_ACTIVITY_END_TIME))?>)，之后<?php }?><em class="wq-orange"><?php echo number_format(STORE_YEAR_FEE,2)?></em>元/年，<em class="wq-orange"><?php echo number_format(STORE_MONTH_FEE,2)?></em>元/月</p>
                <p class="total-fee wq-blue">合计应收金额：0.00元</p>
                <div class="v-fee-table">
                    <ul class="thead">
                        <li>门店名称</li>
                        <li>开通时间</li>
                        <li>玩券版本</li>
                        <li>费用</li>
                    </ul>
                    <ul class="tbody">
                    </ul>
                </div>
                <div class="v-fee-btn-wrap">
                    <button type="button" class="wq-btn-primary" onclick="hidefeedetails()">确定</button>
                </div>
            </div>
        </div>
        <!-- 选择门店弹窗 -->
        <div class="wq-pop" id="select_store" style="display: none">
            <div class="wq-pop-title">
                <h3>选择门店</h3>
                <i class="wq-pop-close fr" onclick="hideSelectStore()">&#10006;</i>
            </div>
            <div class="wq-pop-con">
                <div class="v-select-store">
                    <div class="tree1">
                        <i class="rbArrow"></i>
                        <input type="checkbox" class="parentsBox"><i class="iconFolder"></i>全部(<?php echo $total_store_num?>)
                        <div class="comList">
                        <?php foreach ($store_list as $k => $v){?>
                            <dl class="child1 closeFolder">
                                <dt>
                                    <i class="rbArrow"></i>
                                    <input type="checkbox" class="parentBox">
                                    <i class="iconFolder"></i>
                                    <?php echo $v -> name?><?php echo '('.$v -> store_num.')'?>
                                </dt>
                                <?php foreach ($v -> list as $x => $y){?>
                                <dd class="child3">
                                    <input type="checkbox" class="childBox" value="<?php echo $y -> id?>"><i class="iconFile"></i><?php echo $y -> name?><?php if(!empty($y -> branch_name)){echo ' - '.$y -> branch_name;}?>
                                    <span class="meta">当前版本：<?php echo $GLOBALS['__STORE_EDITION'][$y -> edition]?>，<?php echo $y -> edition == STORE_EDITION_MARKETING?$y -> marking_end_time:'长期'?></span>
                                </dd>
                                <?php }?>
                            </dl>
                        <?php }?>
                        
                        <?php foreach ($store_list_arr as $k => $v){?>
                        <dl class="child1">
                            <dt>
                                <input type="checkbox" class="childBox" value="<?php echo $v -> id?>">
                                <i class="iconFile"></i><?php echo $v -> name?><?php if(!empty($v -> branch_name)){echo ' - '.$v -> branch_name;}?>
                                <span class="meta">当前版本：<?php echo $GLOBALS['__STORE_EDITION'][$v -> edition]?>，<?php echo $v -> edition == STORE_EDITION_MARKETING?$v -> marking_end_time:'长期'?></span>
                            </dt>
                        </dl>
                        <?php }?>
                        </div>
                    </div>
                </div>
                <div class="v-selected-num">
                    <span>已选<em class="wq-blue" id="selected_store">0</em>家，共<em class="wq-blue"><?php echo $total_store_num?></em>家</span>
                </div>
                <div class="v-fee-btn-wrap">
                    <button type="button" class="wq-btn-primary" id="selected_store_btn">确定</button>
                </div>
            </div>
        </div>
    </div>
</body>

<script>

//显示费用明细弹出框
function showfeedetails(){
	$('#fee_details').show();
}
//关闭费用明细弹出框
function hidefeedetails(){
	$('#fee_details').hide();
}
//显示门店弹出框
function showSelectStore(){
	$('#select_store').show();
}
//关闭门店弹出框
function hideSelectStore(){
	$('#select_store').hide();
}

//确定选中门店
$('#selected_store_btn').click(function(){ 
	var store_arr = ',';
	var selected_num = 0;
	$('.childBox').each(function(){
		if($(this).is(':checked')){
			store_arr += $(this).val()+',';
			selected_num ++;
		}
	});
	if(selected_num == 0){
		$('#store_id_arr_error').text('请选择需要续费升级的门店');
		$('#store_id_arr_error').show();
	}else if(selected_num > 0){
		$('#store_id_arr_error').hide();
	}
	
	$('#store_id_arr').val(store_arr);
	$('#store_num_show').html('已选择'+selected_num+'家门店<a href="javascript:;" class="v-renew-add" onclick="showSelectStore()">修改</a>');
	hideSelectStore();
	createFeeDetails();
});

//付费模式切换
$('#payMode-month').click(function(){
	$('.v-label').text('月');
	createFeeDetails();
});

$('#payMode-year').click(function(){
	$('.v-label').text('年');
	createFeeDetails();
});

//生成费用明细
function createFeeDetails(){
	var store_arr = $('#store_id_arr').val();
	var pay_mode = $('input[name=payMode]:checked').val();
	var time = $('#time').val();
	if(store_arr != '' && pay_mode != '' && time != ''){
		$.ajax({
           	url: '<?php echo Yii::app()->createUrl('mCenter/Install/AjaxForFeeDetails')?>',
          	type: 'POST',
          	async: false,
          	data: {store_arr: store_arr, pay_mode: pay_mode,time:time},
          	dataType: 'json',
           	success: function (data) {
              	if(data.status == <?php echo ERROR_NONE?>){
              		$('.tbody').empty();
                  	$('.v-fee').text(data.total_fee.toFixed(2));
                  	$('.total-fee').text("合计应收金额："+data.total_fee.toFixed(2)+"元");
                  	for(var i = 0;i<data.fee_details.length;i++){
						var store_name = '';
						var time = '';
						var edition = data.fee_details[i].edition;
						var fee = data.fee_details[i].fee;
						var discount_month_num = '';
                  		if(data.fee_details[i].store_branch_name != null && data.fee_details[i].store_branch_name != ''){
                  			store_name = data.fee_details[i].store_name+' - '+data.fee_details[i].store_branch_name;
                  		}else{
                  			store_name = data.fee_details[i].store_name;
                     	}

                     	if(data.fee_details[i].pay_mode == <?php echo STORE_ORDER_PAY_TYPE_MONTH?>){
                     		time = data.fee_details[i].time+"月";
                     	}else{
                     		time = data.fee_details[i].time/12+"年";
                     	}

                     	if(data.fee_details[i].month_num/12 >= 1){
                         	if(data.fee_details[i].month_num%12 == 0){
                         		discount_month_num = parseInt(data.fee_details[i].month_num/12)+"年";
                         	}else{
                     			discount_month_num = parseInt(data.fee_details[i].month_num/12)+"年"+data.fee_details[i].month_num%12+'个月';
                         	}
                     	}else{
                     		discount_month_num = data.fee_details[i].month_num + '个月';
                     	}

                 		$('.tbody').append(
                          	'<li>'+
                           	'<ul class="tbody-item">'+
                           	'<li>'+store_name+'</li>'+
                           	'<li class="wq-blue">'+time+'</li>'+
                          	'<li>'+edition+'</li>'+
                           	'<li class="wq-orange">'+fee.toFixed(2)+'<span class="discount">享'+discount_month_num+'优惠</span></li>'+
                           	'</ul>'+
                        	'</li>');
                  	}
              	}
           	}
       	});
	}
}



$('.childBox').click(function(){
	var store_count = 0;
	$('.childBox').each(function(){
		if($(this).is(':checked')){
			store_count++;
		}
	});
	$('#selected_store').text(store_count);
});

//开通时长输入款失去焦点事件
$("#time").blur(function(){
	createFeeDetails();
});

//显示发票表单
function showInvoice(){
	$('.v-invoice-con').slideDown(500,function(){
		window.parent.callParAutoResize("main",$("body").height());
	});
}

//隐藏发票表单
function hideInvoice(){
	$('.v-invoice-con').slideUp(500,function(){
		window.parent.callParAutoResize("main",$("body").height());
	});
}

/**************************发表表单验证********************************/
//发票抬头
$('input[name=invoice_header]').blur(function(){
	if($('input[name=invoice]:checked').val() == <?php echo STORE_ORDER_IF_INVOICE_YES?>){
		if($('input[name=invoice_header]').val() == ''){
			$('#invoice_header_error').text('请填写发票抬头');
			$('#invoice_header_error').show();
		}else{
			$('#invoice_header_error').hide();
		}
	}
});
//收件人
$('input[name=addressee]').blur(function(){
	if($('input[name=invoice]:checked').val() == <?php echo STORE_ORDER_IF_INVOICE_YES?>){
		if($('input[name=addressee]').val() == ''){
			$('#addressee_error').text('请填写收件人');
			$('#addressee_error').show();
		}else{
			if($('input[name=addressee]').val().length > 10){
				$('#addressee_error').text('收件人姓名最长10个汉字');
				$('#addressee_error').show();
			}else{
				$('#addressee_error').hide();
			}
		}
	}
});
//联系方式
$('input[name=tel]').blur(function(){
	if($('input[name=invoice]:checked').val() == <?php echo STORE_ORDER_IF_INVOICE_YES?>){
		if($('input[name=tel]').val() == ''){
			$('#tel_error').text('请填写联系方式');
			$('#tel_error').show();
		}else{
			var reg = /^((0\d{2,3}-\d{7,8})|(1[3584]\d{9}))$/;
			if(!reg.test($('input[name=tel]').val())){
				$('#tel_error').text('请输入正确的联系方式');
				$('#tel_error').show();
			}else{
				$('#tel_error').hide();
			}
		}
	}
});

//收取地址
$('input[name=address_details]').blur(function(){
	if($('input[name=invoice]:checked').val() == <?php echo STORE_ORDER_IF_INVOICE_YES?>){
		if($('input[name=address_details]').val() == ''){
			$('#address_details_error').text('请填写详细地址');
			$('#address_details_error').show();
		}else{
			$('#address_details_error').hide();
		}
	}
});

//开通时长
$('input[name=time]').blur(function(){
	if($(this).val() == ''){
		$('#time_error').text('请填写开通时长');
		$('#time_error').show();
	}else{
		$('#time_error').hide();
	}
});

//表单提交验证
function submitCheck(){
	var flag = true;
	if($('input[name=time]').val() == ''){
		$('#time_error').text('请填写开通时长');
		$('#time_error').show();
		flag = false;
	}else{
		$('#time_error').hide();
	}

	if($('#store_id_arr').val() == '' || $('#store_id_arr').val() == ','){
		$('#store_id_arr_error').text('请选择需要续费升级的门店');
		$('#store_id_arr_error').show();
		flag = false;
	}else{
		$('#store_id_arr_error').hide();
	}

	if($('input[name=invoice]:checked').val() == <?php echo STORE_ORDER_IF_INVOICE_YES?>){
		if($('input[name=address_details]').val() == ''){
			$('#address_details_error').text('请填写详细地址');
			$('#address_details_error').show();
			flag = false;
		}else{
			$('#address_details_error').hide();
		}
	}

	if($('input[name=invoice]:checked').val() == <?php echo STORE_ORDER_IF_INVOICE_YES?>){
		if($('input[name=invoice_header]').val() == ''){
			$('#invoice_header_error').text('请填写发票抬头');
			$('#invoice_header_error').show();
			flag = false;
		}else{
			$('#invoice_header_error').hide();
		}
	}

	if($('input[name=invoice]:checked').val() == <?php echo STORE_ORDER_IF_INVOICE_YES?>){
		if($('input[name=addressee]').val() == ''){
			$('#addressee_error').text('请填写收件人');
			$('#addressee_error').show();
			flag = false;
		}else{
			if($('input[name=addressee]').val().length > 10){
				$('#addressee_error').text('收件人姓名最长10个汉字');
				$('#addressee_error').show();
				flag = false;
			}else{
				$('#addressee_error').hide();
			}
		}
	}

	if($('input[name=invoice]:checked').val() == <?php echo STORE_ORDER_IF_INVOICE_YES?>){
		if($('input[name=tel]').val() == ''){
			$('#tel_error').text('请填写联系方式');
			$('#tel_error').show();
			flag = false;
		}else{
			var reg = /^((0\d{2,3}-\d{7,8})|(1[3584]\d{9}))$/;
			if(!reg.test($('input[name=tel]').val())){
				$('#tel_error').text('请输入正确的联系方式');
				$('#tel_error').show();
				flag = false;
			}else{
				$('#tel_error').hide();
			}
		}
	}

	if(flag){
		$('form[name=order_form]').submit();
	}
	
}



</script>