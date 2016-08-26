<body>
    <div class="kkfm_r_inner">
        <div class="storeManage">
            <div class="top">
                <div class="name">
                    购买记录
                </div>
            </div>
            <div class="v-container">
                <table class="wq-table buy-record-table">
                    <thead>
                        <tr>
                            <th>玩券版本</th>
                            <th>时间和门店</th>
                            <th>金额</th>
                            <th>付款方式</th>
                            <th>状态</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($store_order_list as $k => $v){?>
                    <input value='<?php echo $v -> fee_detail?>' style="display: none" id="fee_detail<?php echo $v -> id?>">
                        <tr class="meta">
                            <td  colspan="6">
                                <span>下单时间：<?php echo $v -> create_time?></span>
                                <span>订单号：<?php echo $v -> order_no?></span>
                            </td>                            
                        </tr>
                        <tr>
                            <td class="wq-blue"><?php echo $GLOBALS['__STORE_EDITION'][$v -> edition_type]?></td>
                            <td>
                                <p>购买时间：<?php echo $v ->pay_type == STORE_ORDER_PAY_TYPE_MONTH?$v -> time.'月':$v -> time.'年'?></p>
                                <p>开通门店：<?php echo $v -> store_num?></p>
                            </td>
                            <td class="wq-blue" onclick="showfeedetails(<?php echo $v -> id?>)" id="total_fee"><?php echo number_format($v -> order_money,2)?></td>
                            <td>支付宝</td>
                            <td class="wq-orange"><?php echo $GLOBALS['ORDER_STATUS_PAY'][$v -> pay_status]?></td>
                            <td class="last">
                            <?php if($v -> pay_status == ORDER_STATUS_UNPAID){?>
                                <a href="<?php echo Yii::app() -> createUrl('mCenter/Install/StoreOrderPay',array('order_no' => $v -> order_no));?>" class="wq-btn-primary wq-btn-payment">立即付款</a>
                            <?php }?>
                            </td>
                        </tr>
                        <?php }?>
                    </tbody>
                    <tr style="border:none">
                    <td style="border:none;text-align:right" colspan="6">
                        <?php $this -> widget('CLinkPager',array(
                            'pages'=>$pages,
                            'header'=>'共&nbsp;<span class="yellow">'.$pages -> getItemCount().'</span>&nbsp;条&nbsp;',
                            'prevPageLabel' => '上一页',
                            'nextPageLabel'=>'下一页',
                            'maxButtonCount'=>8
                        ));?>
                    </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
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
</body>

<script>
//显示费用明细弹出框
function showfeedetails(id){
	var data = $('#fee_detail'+id).val();
	var total_fee = $('#total_fee').text();
	if(data != ''){
		data = $.parseJSON(data);
		$('.tbody').empty();
		$('.total-fee').text("合计应收金额："+total_fee+"元");
		for(var i = 0;i<data.length;i++){
			var store_name = '';
			var time = '';
			var edition = data[i].edition;
			var fee = data[i].fee;
			var discount_month_num = '';
      		if(data[i].store_branch_name != null && data[i].store_branch_name != ''){
      			store_name = data[i].store_name+' - '+data[i].store_branch_name;
      		}else{
      			store_name = data[i].store_name;
         	}

         	if(data[i].pay_mode == <?php echo STORE_ORDER_PAY_TYPE_MONTH?>){
         		time = data[i].time+"月";
         	}else{
         		time = data[i].time/12+"年";
         	}

         	if(data[i].month_num/12 >= 1){
             	if(data[i].month_num%12 == 0){
         			discount_month_num = parseInt(data[i].month_num/12)+"年";
             	}else{
             		discount_month_num = parseInt(data[i].month_num/12)+"年"+data[i].month_num%12+'个月';
             	}
         	}else{
         		discount_month_num = data[i].month_num + '个月';
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
	
	$('#fee_details').show();
}
//关闭费用明细弹出框
function hidefeedetails(){
	$('#fee_details').hide();
}     



</script>