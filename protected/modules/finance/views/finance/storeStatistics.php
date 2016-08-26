<?php 
//提交的已选择门店id数组
$get_store_arr = array();
if (!empty($_GET['store_id'])){
    $get_store_arr = explode(',', $_GET['store_id']);
}
?>
<h4 class="g-title">门店交易</h4>
<div class="g-container">
    <div class="m-box store-trade-search">
        <div class="form-inline m-bg-search">
            <?php echo CHtml::beginForm($this->createUrl('storeStatistics'),'get',array('id'=>"search_form")); ?>
            <div class="m-bg-search-item">
                <div class="form-group">
                    <label>交易时间：</label>
                    <?php echo CHtml::textField('Time', isset($_GET['Time']) ? $_GET['Time'] : date('Y/m/d').' - '.date('Y/m/d'), array('class'=>"form-control fc-m-sm",'readonly'=>"readonly"))?>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-default m-btn-sm ml10" onclick="changeTime(0)" id="time_today">今日</button>
                    <button type="button" class="btn btn-default m-btn-sm ml10" onclick="changeTime(-6)" id="time_week">7日</button>
                    <button type="button" class="btn btn-default m-btn-sm ml10" onclick="changeTime(-29)" id="time_month">30日</button>
                </div>
                <div class="form-group fr mr20">
                    <button type="submit" class="btn m-btn-search m-btn">搜索</button>
                </div>
            </div>
            <div class="m-bg-search-item mt10">
                <div class="form-group">
                    <label>门店：</label>
                    <input type="text" class="form-control fc-s-sm" value="<?php echo empty($get_store_arr)?'请选择门店': (!empty($storeDropdownList['store_count']) && $storeDropdownList['store_count'] == count($get_store_arr) ? '全部门店':'已选'.count($get_store_arr).'家门店')?>" id="store_id" readonly>
                    <input type="hidden" class="form-control fc-s-sm" value="<?php echo empty($_GET['store_id'])?'':$_GET["store_id"]?>" name="store_id">
                </div>
            </div>
            <div class="m-bg-search-item mt10">
                <div class="form-group pay-way-form-group">
                    <label>支付渠道：</label>
                    <select class="js-pay-way-multiple" multiple="multiple"  id="pay_channel">
                        <option value="<?php echo ORDER_PAY_CHANNEL_ALIPAY_TM?>">支付宝条码</option>
                        <option value="<?php echo ORDER_PAY_CHANNEL_ALIPAY_SM?>">支付宝扫码</option>
                        <option value="<?php echo ORDER_PAY_CHANNEL_WXPAY_TM?>">微信条码</option>
                        <option value="<?php echo ORDER_PAY_CHANNEL_WXPAY_SM?>">微信扫码</option>
                        <option value="<?php echo ORDER_PAY_CHANNEL_UNIONPAY?>">银联支付</option>
                        <option value="<?php echo ORDER_PAY_CHANNEL_CASH?>">现金支付</option>
                        <option value="<?php echo ORDER_PAY_CHANNEL_STORED?>">储值支付</option>
                        <option value="<?php echo ORDER_PAY_CHANNEL_NO_MONEY?>">无需付款</option>
                        <option value="<?php echo ORDER_PAY_CHANNEL_GROUP?>">集中收银</option>
                    </select>
                    <input type="hidden" vlaue="<?php echo empty($_GET['pay_channel'])?'':$_GET["pay_channel"]?>" name="pay_channel">
                </div>
            </div>
            <input type="hidden" value="<?php echo empty($_GET['orderby_key'])?'':$_GET["orderby_key"]?>" name="orderby_key">
            <input type="hidden" value="<?php echo empty($_GET['orderby_value'])?'':$_GET["orderby_value"]?>" name="orderby_value">
            <?php echo CHtml::endForm(); ?>
        </div>
    </div>
    <div class="store-trade-data-wrapper mt45">
        <a href="<?php echo $this->createUrl('exportSStatisticsExcel',array('get'=>empty($_GET)?array():$_GET));?>" class="download-icon download fr"></a>
        <div class="m-box trade-data">
            <div class="m-row trade-data-title">
                <div class="m-col-md text-right m-col-md-3">
                    <h5 class="fw fl pl20" onclick="change_sort(1)">门店名称<i class="fa text-blue pl10" id='sort_1' aria-hidden="true" style="display:none;"></i></h5>
                    <h5 class="fw" onclick="change_sort(2)">订单金额<i class="fa text-blue pl10" id='sort_2' aria-hidden="true" style="display:none;"></i></h5>
                </div>
                <div class="m-col-md text-right m-col-md-2">
                    <h5 class="fw" onclick="change_sort(3)">优惠金额<i class="fa text-blue pl10" id='sort_3' aria-hidden="true" style="display:none;"></i></h5>
                </div>
                <div class="m-col-md text-right m-col-md-2">
                    <h5 class="fw" onclick="change_sort(4)">退款金额<i class="fa text-blue pl10" id='sort_4' aria-hidden="true" style="display:none;"></i></h5>
                </div>
                <div class="m-col-md text-right m-col-md-2">
                    <h5 class="fw" onclick="change_sort(5)">实收金额<i class="fa text-blue pl10" id='sort_5' aria-hidden="true" style="display:none;"></i></h5>
                </div>
            </div>
            <?php if (!empty($list)){foreach ($list as $v){?>
            <div class="trade-data-item">
                <div class="m-row trade-summary-data">
                    <div class="m-col-md text-right m-col-md-3">
                        <p class="text-left pl20 fl store-name" title="<?php echo empty($v['branch_name'])?$v['name']:$v['name'].' -'.$v['branch_name']?>"><?php echo empty($v['branch_name'])?$v['name']:$v['name'].' -'.$v['branch_name']?><br><?php echo $v['number']?></p>
                        <p><?php echo sprintf('%.2f',$v['total_trade_money'])?></p>
                        <p><?php echo $v['total_trade_num']?>笔</p>
                    </div>
                    <div class="m-col-md text-right m-col-md-2">
                        <p>-<?php echo sprintf('%.2f',$v['total_discount_money'])?></p>
                    </div>
                    <div class="m-col-md text-right m-col-md-2">
                        <p>-<?php echo sprintf('%.2f',$v['total_refund_money'])?></p>
                        <p><?php echo $v['total_refund_num']?>笔</p>
                    </div>
                    <div class="m-col-md text-right m-col-md-2">
                        <p class="text-orange"><?php echo sprintf('%.2f',($v['total_trade_actual_money'] == 0 ? 0 : $v['total_trade_actual_money']))?></p>
                    </div>
                    <?php if ($v['total_trade_num'] > 0 || $v['total_refund_num'] >0){?>
                    <span class="js-trade-up-down text-blue trade-up-down"><i class="fa fa-angle-double-down" aria-hidden="true"></i>展开</span>
                    <?php }?>
                </div>
                                
                <div class="trade-data-list-wrapper"  style="display: none;">
                    <?php if ($v['alipay_trade_num'] > 0 || $v['alipay_refund_num'] >0){?>
                    <div class="m-row trade-data-list">
                        <div class="m-col-md m-col-md-3">
                            <em class="pl20">支付宝：</em>
                            <em class="fr"><?php echo sprintf('%.2f',$v['alipay_trade_money'])?></em>
                        </div>
                        <div class="m-col-md text-right m-col-md-2">
                            <em class="fl pl10">(<?php echo $v['alipay_trade_num']?>笔)</em>
                            <em>-<?php echo sprintf('%.2f',$v['alipay_discount_money'])?></em>
                        </div>
                        <div class="m-col-md text-right m-col-md-2">
                            <em>-<?php echo sprintf('%.2f',$v['alipay_refund_money'])?></em>
                        </div>
                        <div class="m-col-md text-right m-col-md-2">
                            <em class="fl pl10">(<?php echo $v['alipay_refund_num']?>笔)</em>
                            <em><?php echo sprintf('%.2f',$v['alipay_trade_actual_money'])?></em>
                        </div>
                    </div>
                    <?php }?>
                    <?php if ($v['wechat_trade_num'] > 0 || $v['wechat_refund_num'] >0){?>
                    <div class="m-row trade-data-list">
                        <div class="m-col-md m-col-md-3">
                            <em class="pl20">微信：</em>
                            <em class="fr"><?php echo sprintf('%.2f',$v['wechat_trade_money'])?></em>
                        </div>
                        <div class="m-col-md text-right m-col-md-2">
                            <em class="fl pl10">(<?php echo $v['wechat_trade_num']?>笔)</em>
                            <em>-<?php echo sprintf('%.2f',$v['wechat_discount_money'])?></em>
                        </div>
                        <div class="m-col-md text-right m-col-md-2">
                            <em>-<?php echo sprintf('%.2f',$v['wechat_refund_money'])?></em>
                        </div>
                        <div class="m-col-md text-right m-col-md-2">
                            <em class="fl pl10">(<?php echo $v['wechat_refund_num']?>笔)</em>
                            <em><?php echo sprintf('%.2f',$v['wechat_trade_actual_money'])?></em>
                        </div>
                    </div>
                    <?php }?>
                    <?php if ($v['unionpay_trade_num'] > 0 || $v['unionpay_refund_num'] >0){?>
                    <div class="m-row trade-data-list">
                        <div class="m-col-md m-col-md-3">
                            <em class="pl20">银联：</em>
                            <em class="fr"><?php echo sprintf('%.2f',$v['unionpay_trade_money'])?></em>
                        </div>
                        <div class="m-col-md text-right m-col-md-2">
                            <em class="fl pl10">(<?php echo $v['unionpay_trade_num']?>笔)</em>
                            <em>-<?php echo sprintf('%.2f',$v['unionpay_discount_money'])?></em>
                        </div>
                        <div class="m-col-md text-right m-col-md-2">
                            <em>-<?php echo sprintf('%.2f',$v['unionpay_refund_money'])?></em>
                        </div>
                        <div class="m-col-md text-right m-col-md-2">
                            <em class="fl pl10">(<?php echo $v['unionpay_refund_num']?>笔)</em>
                            <em><?php echo sprintf('%.2f',$v['unionpay_trade_actual_money'])?></em>
                        </div>
                    </div>
                    <?php }?>
                    <?php if ($v['stored_trade_num'] > 0 || $v['stored_refund_num'] >0){?>
                    <div class="m-row trade-data-list">
                        <div class="m-col-md m-col-md-3">
                            <em class="pl20">储值：</em>
                            <em class="fr"><?php echo sprintf('%.2f',$v['stored_trade_money'])?></em>
                        </div>
                        <div class="m-col-md text-right m-col-md-2">
                            <em class="fl pl10">(<?php echo $v['stored_trade_num']?>笔)</em>
                            <em>-<?php echo sprintf('%.2f',$v['stored_discount_money'])?></em>
                        </div>
                        <div class="m-col-md text-right m-col-md-2">
                            <em>-<?php echo sprintf('%.2f',$v['stored_refund_money'])?></em>
                        </div>
                        <div class="m-col-md text-right m-col-md-2">
                            <em class="fl pl10">(<?php echo $v['stored_refund_num']?>笔)</em>
                            <em><?php echo sprintf('%.2f',$v['stored_trade_actual_money'])?></em>
                        </div>
                    </div>
                    <?php }?>
                    <?php if ($v['cash_trade_num'] > 0 || $v['cash_refund_num'] >0){?>
                    <div class="m-row trade-data-list">
                        <div class="m-col-md m-col-md-3">
                            <em class="pl20">现金：</em>
                            <em class="fr"><?php echo sprintf('%.2f',$v['cash_trade_money'])?></em>
                        </div>
                        <div class="m-col-md text-right m-col-md-2">
                            <em class="fl pl10">(<?php echo $v['cash_trade_num']?>笔)</em>
                            <em>-<?php echo sprintf('%.2f',$v['cash_discount_money'])?></em>
                        </div>
                        <div class="m-col-md text-right m-col-md-2">
                            <em>-<?php echo sprintf('%.2f',$v['cash_refund_money'])?></em>
                        </div>
                        <div class="m-col-md text-right m-col-md-2">
                            <em class="fl pl10">(<?php echo $v['cash_refund_num']?>笔)</em>
                            <em><?php echo sprintf('%.2f',$v['cash_trade_actual_money'])?></em>
                        </div>
                    </div>
                    <?php }?>
                    <?php if ($v['nopay_trade_num'] > 0 || $v['nopay_refund_num'] >0){?>
                    <div class="m-row trade-data-list">
                        <div class="m-col-md m-col-md-3">
                            <em class="pl20">无需付款：</em>
                            <em class="fr"><?php echo sprintf('%.2f',$v['nopay_trade_money'])?></em>
                        </div>
                        <div class="m-col-md text-right m-col-md-2">
                            <em class="fl pl10">(<?php echo $v['nopay_trade_num']?>笔)</em>
                            <em>-<?php echo sprintf('%.2f',$v['nopay_discount_money'])?></em>
                        </div>
                        <div class="m-col-md text-right m-col-md-2">
                            <em>-<?php echo sprintf('%.2f',$v['nopay_refund_money'])?></em>
                        </div>
                        <div class="m-col-md text-right m-col-md-2">
                            <em class="fl pl10">(<?php echo $v['nopay_refund_num']?>笔)</em>
                            <em><?php echo sprintf('%.2f',$v['nopay_trade_actual_money'])?></em>
                        </div>
                    </div>
                    <?php }?>
                    <?php if ($v['group_trade_num'] > 0 || $v['group_refund_num'] >0){?>
                    <div class="m-row trade-data-list">
                        <div class="m-col-md m-col-md-3">
                            <em class="pl20">集中收银：</em>
                            <em class="fr"><?php echo sprintf('%.2f',$v['group_trade_money'])?></em>
                        </div>
                        <div class="m-col-md text-right m-col-md-2">
                            <em class="fl pl10">(<?php echo $v['group_trade_num']?>笔)</em>
                            <em>-<?php echo sprintf('%.2f',$v['group_discount_money'])?></em>
                        </div>
                        <div class="m-col-md text-right m-col-md-2">
                            <em>-<?php echo sprintf('%.2f',$v['group_refund_money'])?></em>
                        </div>
                        <div class="m-col-md text-right m-col-md-2">
                            <em class="fl pl10">(<?php echo $v['group_refund_num']?>笔)</em>
                            <em><?php echo sprintf('%.2f',$v['group_trade_actual_money'])?></em>
                        </div>
                    </div>
                    <?php }?>
                </div>
            </div>
            <?php }}?>
        </div>
        <?php if(!empty($pages)) {?>
            <div class="g-pager">
                <div class="fr">
                    <ul class="yiiPager">
                        <?php
                        $this->widget('CLinkPager', array(
                            'pages' => $pages,
                            'header' => '<li>共&nbsp;<strong class="orange">' . $pages->getItemCount() . '</strong>&nbsp;条</li>&nbsp;',
                            'prevPageLabel' => '上一页',
                            'nextPageLabel' => '下一页',
                            'maxButtonCount' => 8
                        ));
                        ?>
                    </ul>
                </div>
            </div>
        <?php }?>
    </div>
    <!-- 选择门店弹出 -->
    <div class="m-pop store-pop" style="display: none;" id="store_select">
    	<div class="m-pop-title">
        	<h4>选择门店</h4>
        	<i class="m-pop-close fr fa fa-times"></i>
    	</div>
    	<div class="m-pop-con">
    	<div class="v-select-store mt20">
        	<div class="tree1">
    	       <?php if (!empty($storeDropdownList)){?>
        	   <i class="rbArrow"></i>
        	   <input type="checkbox" class="parentsBox"><i class="iconFolder"></i>全部(<?php echo $storeDropdownList['store_count']?>)
        	   <div class="comList">
	               <ul>
	               <?php if (!empty($storeDropdownList['store'])){foreach ($storeDropdownList['store'] as $k => $v){?>
    	               <li class="child3">
                    	<input type="checkbox" class="storeBox" <?php if(in_array($v['store_id'], $get_store_arr)){echo 'checked';}?> value='<?php echo $v['store_id']?>'>
                    	<i class="iconFile"></i>
                    	<span class="store-name" title="<?php echo $v['name']?>"><?php echo $v['name']?></span>
                    	<span class="meta" title="<?php echo $v['address']?>"><?php echo $v['address']?></span>
                	   </li>
	               <?php }}?>
	               </ul>
                   	<?php if (!empty($storeDropdownList['group'])){foreach ($storeDropdownList['group'] as $k => $v){?>
                    <div class="child1">
                    	<div class="parentWrap">
                        	<i class="rbArrow"></i>
                        	<input type="checkbox" class="parentBox">
                        	<i class="iconFolder"></i>
                        	<?php echo $v['name']?>
                    	</div>
                    	<ul>
                    	<?php if (!empty($v['store'])){foreach ($v['store'] as $k2 => $v2){?>
                        	<li class="child3">
                        	<input type="checkbox" <?php if(in_array($v2['store_id'], $get_store_arr)){echo 'checked';}?> class="storeBox" value='<?php echo $v2['store_id']?>'>
                        	<i class="iconFile"></i>
                        	<span class="store-name" title="<?php echo $v2['name']?>"><?php echo $v2['name']?></span>
                        	<span class="meta" title="<?php echo $v2['address']?>"><?php echo $v2['address']?></span>
                        	</li>
                        <?php }}?>
                    	</ul>
                    	<?php if (!empty($v['group'])){foreach ($v['group'] as $k3 => $v3){?>
                    	<div class="childWrap">
                            <div class="child3">
                                <i class="rbArrow"></i>
                            	<input type="checkbox" class="childBox">
                            	<i class="iconFolder"></i>
                            	<?php echo $v3['name']?>
                            </div>
                            <ul>
                        	<?php if (!empty($v3['store'])){foreach ($v3['store'] as $k4 => $v4){?>
                            	<li class="child4">
                            	<input type="checkbox" class="storeBox" <?php if(in_array($v4['store_id'], $get_store_arr)){echo 'checked';}?> value='<?php echo $v4['store_id']?>'>
                            	<i class="iconFile"></i>
                            	<span class="store-name" title="<?php echo $v4['name']?>"><?php echo $v4['name']?></span>
                            	<span class="meta" title="<?php echo $v4['address']?>"><?php echo $v4['address']?></span>
                            	</li>
                            <?php }}?>
                        	</ul>
                    	</div>   
                    	<?php }}?>
                    	</div>
                   	<?php }}?>
            	</div>
            	<?php }?>
        	</div>
    	</div>
    	<div class="v-selected-num">
    	   <span>已选<em class="text-blue" id="store_select_count">0</em>家，共<em class="text-blue"><?php echo empty($storeDropdownList['store_count'])?0:$storeDropdownList['store_count']?></em>家</span>
    	</div>
    	<div class="text-center mt20">
    	   <button type="button" class="btn btn-primary m-btn" id="store_query">确定</button>
    	</div>
    	</div>
    </div>
</div>

<script type="text/javascript">
$(function(){
	//绑定时间插件
    $('input[name=Time]').daterangepicker({
	        timePicker: false,
	        format: 'YYYY/MM/DD',
	        dateLimit: true,
	        maxDate: moment(),

	});
	//门店选择
	$("#store_id").click(function(){
		var val = $('input[name=store_id]').val();
		var val_arr = val.split(',');
		$('.v-select-store input[type=checkbox]').prop('checked',false);
		$(".storeBox").each(function(){
			var storeBoxVal = $(this).val();
			for(var i = 0; i < val_arr.length; i++){
				if(val_arr[i] == storeBoxVal){
					$(this).prop("checked",true);
				}
			}	
    	})
		$('#store_select_count').text($('.storeBox:checked').length);
		popShow($("#store_select"));
	})
	var store_arr = [];
	$(".parentsBox").change(function(){
		store_select();
	})
	$(".parentBox").change(function(){
		store_select();
	})
	$(".childBox").change(function(){
		store_select();
	})
	$(".storeBox").change(function(){
		store_select();
	})
	//门店确认
	$('#store_query').click(function(){
		store_select('query');
	});
	$(".storeBox").find('')
	//支付渠道赋值
	$('#pay_channel').change(function(){
		pay_channel_select();
	})
	var pay_channel_str = '<?php echo empty($_GET['pay_channel'])?'':json_encode(explode(',', $_GET['pay_channel']))?>';
	if (pay_channel_str != ''){
		var pay_channel_arr = JSON.parse(pay_channel_str);
		$('#pay_channel').val(pay_channel_arr).trigger('change');
		select2Auto();
		pay_channel_select();
	}
	//突显当前时间段按钮
	checkTimeType();
	//突显当前排序
	show_cur_sort();
	//弹窗:门店数量赋值
	showStoreCount();
})
function showStoreCount(){	//弹窗:门店数量赋值
	var count = 0;
	var str = '';
	$('.child1').each(function(){
		count = $(this).find('.storeBox').length;
		str = "( "+count+" )"
		$(this).find('.parentWrap').append(str);
	})
	$('.childWrap').each(function(){
		count = $(this).find('.storeBox').length;
		str = "( "+count+" )"
		$(this).find('.child3').append(str);
	})
}
function getSomeDate(day){//获取日期
	var d = new Date();
	var time = Date.parse(d);
	var new_time = time+day*24*60*60*1000;	
	d.setTime(new_time)
	var Y = d.getFullYear();
	var M = d.getMonth()+1;
	var Days = d.getDate();
	if (M < 10){
		M = '0'+M;
	}
	if (Days < 10){
		Days = '0'+Days;
	}
    var date_str = Y+'/'+M+'/'+Days;
	return date_str;
}
//显示当前排序
function show_cur_sort(){
	var cur_key = "<?php echo empty($_GET['orderby_key']) ? '':$_GET['orderby_key']?>";
	var cur_val = "<?php echo empty($_GET['orderby_value']) ? '':$_GET['orderby_value']?>";
	if (cur_key != '' && cur_val != ''){
		if (cur_val == 2){
			$('#sort_'+cur_key).addClass('fa-long-arrow-down');
		}else{
			$('#sort_'+cur_key).addClass('fa-long-arrow-up');
		}
		$('#sort_'+cur_key).show();
	}else{
		$('#sort_1').addClass('fa-long-arrow-up');
		$('#sort_1').show();
	}
}
//切换排序
function change_sort(key){
	var cur_key = "<?php echo empty($_GET['orderby_key']) ? '':$_GET['orderby_key']?>";
	var cur_val = "<?php echo empty($_GET['orderby_value']) ? '':$_GET['orderby_value']?>";
	var type = 2;
	if (key == 1 && cur_key != key && cur_key != ''){
		type = 1;
	}else if (cur_key == key && cur_val == 2){
		type = 1;
	}
	$('input[name=orderby_key]').val(key);
	$('input[name=orderby_value]').val(type);
	$('#search_form').submit();
}

function pay_channel_select(){	//支付渠道赋值
	var pay_channel_val = $('#pay_channel').val();
	$('input[name=pay_channel]').val(pay_channel_val);
}
function store_select(type){	//门店选择
	store_arr=[];
	$(".storeBox").each(function(){
		if($(this).is(":checked")){
			store_arr.push($(this).val());
		}
	})
	var len = store_arr.length;
	var len1 = $(".storeBox").length;
	$('#store_select_count').text(len);
	if (type == 'query'){	//确认事件
    	if (len == 0){
    		$('#store_id').val('请选择门店');
    	}else if(len == len1){
    		$('#store_id').val('全部门店');
    	}else{
        	$('#store_id').val('已选'+len+'家门店');
    	}
    	$('input[name=store_id]').val(store_arr);
		$('#store_select').hide();
	}
}
function time_search(){	//时间段查询
	var val = $('#trade_time').val();
	$('#search_form').submit();
}
function changeTime(day){	//切换时间段查询
	var time = getDateInterval(day,0);
	$('input[name=Time]').val(time);
	time_search();
}
function checkTimeType(){	//突显第几日按钮
	var val = $('input[name=Time]').val();
	switch (val){
    	case getDateInterval(0,0):
    		$('#time_today').addClass('btn-primary');
    		break;
    	case getDateInterval(-6,0):
    		$('#time_week').addClass('btn-primary');
    		break;
    	case getDateInterval(-29,0):
    		$('#time_month').addClass('btn-primary');
    		break;
	}
}
function getDateInterval(start_time,end_time){	//获取时间段
	var val = getSomeDate(start_time)+' - '+getSomeDate(end_time);
	return val;
}
function getSomeDate(day){	//获取日期
	var d = new Date();
	var time = Date.parse(d);
	var new_time = time+day*24*60*60*1000;	
	d.setTime(new_time)
	var Y = d.getFullYear();
	var M = d.getMonth()+1;
	var Days = d.getDate();
	if (M < 10){
		M = '0'+M;
	}
	if (Days < 10){
		Days = '0'+Days;
	}
    var date_str = Y+'/'+M+'/'+Days;
	return date_str;
}
</script>