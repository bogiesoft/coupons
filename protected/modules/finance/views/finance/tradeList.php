<?php 
//提交的已选择门店id数组
$get_store_arr = array();
if (!empty($_GET['store_id'])){
    $get_store_arr = explode(',', $_GET['store_id']);
}
?>
<h4 class="g-title">交易明细</h4>
<div class="g-container">
    <div class="m-box trade-detail-search">
        <div class="form-inline m-bg-search">
        <?php echo CHtml::beginForm($this->createUrl('tradeList'),'get',array('id'=>"search_form")); ?>
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
                <div class="form-group ml80">
                    <label>操作员：</label>
                    <?php echo CHtml::dropDownList('operator',isset($_GET['operator'])?$_GET['operator']:'',$operators,array('prompt'=>'全部','class'=>"form-control fc-s-sm"))?>
                </div>
            </div>
            <div class="m-bg-search-item mt10">
                <div class="form-group">
                    <label>交易类型：</label>
                    <?php echo CHtml::dropDownList('trade_type',isset($_GET['trade_type'])?$_GET['trade_type']:'',array(1=>'收款',2=>'退款'),array('prompt'=>'全部','class'=>"form-control fc-s-sm"))?>
                </div>
                <div class="form-group ml80">
                    <label>订单状态：</label>
                    <?php echo CHtml::dropDownList('order_status',isset($_GET['order_status'])?$_GET['order_status']:'',$status,array('prompt'=>'全部','class'=>"form-control fc-s-sm"))?>
                </div>
            </div>
            <div class="m-bg-search-item mt10">
                <div class="form-group pay-way-form-group">
                    <label>支付渠道：</label>
                    <select class="js-pay-way-multiple" multiple="multiple" id="pay_channel">
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
        <?php echo CHtml::endForm(); ?>
        </div>
        <div class="order-num-search js-order-num-search">
            <span><< 订单号查找</span>
        </div>
        <div class="order-search-box" style="<?php echo empty($_GET['order_no'])?'':'display:block';?>">
            <?php echo CHtml::beginForm($this->createUrl('tradeList'),'get'); ?>
            <div class="order-search-box-inner form-inline">
                <div class="form-group">
                    <label>订单号：</label>
                    <input type="text" name="order_no" id="" value="<?php echo empty($_GET['order_no'])?'':$_GET["order_no"]?>" class="form-control fc-sm" placeholder="请输入订单号">
                </div>
                <div class="form-group fr">
                    <button type="submit" class="btn m-btn-search m-btn">搜索</button>
                </div>
            </div>
            <?php echo CHtml::endForm(); ?>
            <div class="hide-order-search-box"></div>
        </div>
    </div>
    <div class="trade-data-wrapper mt20">
        <a href="<?php echo $this->createUrl('exportTradeListExcel',array('get'=>empty($_GET)?array():$_GET));?>" class="download-icon download fr"></a>
        <ul class="nav nav-tabs m-nav-tabs">
            <li class="nav-item">
                <a class="nav-link <?php echo empty($_GET['trade_type'])? 'active':'';?>"  href="javascript:void(0)" onclick="href_location(0)">全部</a>
                <!--激活状态增加active-->
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo isset($_GET['trade_type']) && $_GET['trade_type'] == 1 ? 'active':'';?>" href="javascript:void(0)" onclick="href_location(1)">收款</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo isset($_GET['trade_type']) && $_GET['trade_type'] == 2 ? 'active':'';?>"  href="javascript:void(0)" onclick="href_location(2)">退款</a>
            </li>
        </ul>
        <div class="m-box mt-1 trade-data">
            <div class="m-row trade-summary-data">
                <div class="m-col-md text-right">
                    <h5 class="fw">订单金额</h5>
                    <p class="text-orange amount"><?php echo !empty($list['statistics']) ? sprintf('%.2f',$list['statistics']['total_trade_money']):'0.00';?></p>
                    <p><?php echo !empty($list['statistics']) ? $list['statistics']['total_trade_num']:'0';?>笔</p>
                </div>
                <div class="m-col-md text-right">
                    <h5 class="fw">优惠金额</h5>
                    <p class="text-orange amount">-<?php echo !empty($list['statistics']) ? sprintf('%.2f',$list['statistics']['total_discount_money']):'0.00';?></p>
                </div>
                <div class="m-col-md text-right">
                    <h5 class="fw">退款金额</h5>
                    <p class="text-orange amount">-<?php echo !empty($list['statistics']) ? sprintf('%.2f',$list['statistics']['total_refund_money']):'0.00';?></p>
                    <p><?php echo !empty($list['statistics']) ? $list['statistics']['total_refund_num']:'0';?>笔</p>
                </div>
                <div class="m-col-md text-right">
                    <h5 class="fw">实收金额</h5>
                    <p class="text-orange amount"><?php echo !empty($list['statistics']) ? sprintf('%.2f',$list['statistics']['total_trade_actual_money']):'0.00';?></p>
                </div>
                <?php if (!empty($list['statistics']) && ($list['statistics']['total_trade_num'] > 0 || $list['statistics']['total_refund_num'] >0)){?>
                <span class="js-trade-up-down text-blue trade-up-down"><i class="fa fa-angle-double-down" aria-hidden="true"></i>展开</span>
                <?php }?>
            </div>
            <div class="trade-data-list-wrapper" style="display:none">
                <?php if (isset($list['statistics']) && ($list['statistics']['alipay_trade_num']>0 || $list['statistics']['alipay_refund_num']>0)){?>
                    <div class="m-row trade-data-list">
                        <div class="m-col-md">
                            <em class="pl20">支付宝：</em>
                            <em class="fr"><?php echo sprintf('%.2f',$list['statistics']['alipay_trade_money'])?></em>
                        </div>
                        <div class="m-col-md text-right">
                            <em class="fl pl10">(<?php echo $list['statistics']['alipay_trade_num']?>笔)</em>
                            <em>-<?php echo sprintf('%.2f',$list['statistics']['alipay_discount_money'])?></em>
                        </div>
                        <div class="m-col-md text-right">
                            <em>-<?php echo sprintf('%.2f',$list['statistics']['alipay_refund_money'])?></em>
                        </div>
                        <div class="m-col-md text-right">
                            <em class="fl pl10">(<?php echo $list['statistics']['alipay_refund_num']?>笔)</em>
                            <em><?php echo sprintf('%.2f',$list['statistics']['alipay_trade_actual_money'])?></em>
                        </div>
                    </div>
                <?php }?>
                <?php if (isset($list['statistics']) && ($list['statistics']['wechat_trade_num']>0 || $list['statistics']['wechat_refund_num']>0)){?>
                    <div class="m-row trade-data-list">
                        <div class="m-col-md">
                            <em class="pl20">微信：</em>
                            <em class="fr"><?php echo sprintf('%.2f',$list['statistics']['wechat_trade_money'])?></em>
                        </div>
                        <div class="m-col-md text-right">
                            <em class="fl pl10">(<?php echo $list['statistics']['wechat_trade_num']?>笔)</em>
                            <em>-<?php echo sprintf('%.2f',$list['statistics']['wechat_discount_money'])?></em>
                        </div>
                        <div class="m-col-md text-right">
                            <em>-<?php echo sprintf('%.2f',$list['statistics']['wechat_refund_money'])?></em>
                        </div>
                        <div class="m-col-md text-right">
                            <em class="fl pl10">(<?php echo $list['statistics']['wechat_refund_num']?>笔)</em>
                            <em><?php echo sprintf('%.2f',$list['statistics']['wechat_trade_actual_money'])?></em>
                        </div>
                    </div>
                <?php }?>
                <?php if (isset($list['statistics']) && ($list['statistics']['unionpay_trade_num']>0 || $list['statistics']['unionpay_refund_num']>0)){?>
                    <div class="m-row trade-data-list">
                        <div class="m-col-md">
                            <em class="pl20">银联：</em>
                            <em class="fr"><?php echo sprintf('%.2f',$list['statistics']['unionpay_trade_money'])?></em>
                        </div>
                        <div class="m-col-md text-right">
                            <em class="fl pl10">(<?php echo $list['statistics']['unionpay_trade_num']?>笔)</em>
                            <em>-<?php echo sprintf('%.2f',$list['statistics']['unionpay_discount_money'])?></em>
                        </div>
                        <div class="m-col-md text-right">
                            <em>-<?php echo sprintf('%.2f',$list['statistics']['unionpay_refund_money'])?></em>
                        </div>
                        <div class="m-col-md text-right">
                            <em class="fl pl10">(<?php echo $list['statistics']['unionpay_refund_num']?>笔)</em>
                            <em><?php echo sprintf('%.2f',$list['statistics']['unionpay_trade_actual_money'])?></em>
                        </div>
                    </div>
                <?php }?>
                <?php if (isset($list['statistics']) && ($list['statistics']['stored_trade_num']>0 || $list['statistics']['stored_refund_num']>0)){?>
                    <div class="m-row trade-data-list">
                        <div class="m-col-md">
                            <em class="pl20">储值：</em>
                            <em class="fr"><?php echo sprintf('%.2f',$list['statistics']['stored_trade_money'])?></em>
                        </div>
                        <div class="m-col-md text-right">
                            <em class="fl pl10">(<?php echo $list['statistics']['stored_trade_num']?>笔)</em>
                            <em>-<?php echo sprintf('%.2f',$list['statistics']['stored_discount_money'])?></em>
                        </div>
                        <div class="m-col-md text-right">
                            <em>-<?php echo sprintf('%.2f',$list['statistics']['stored_refund_money'])?></em>
                        </div>
                        <div class="m-col-md text-right">
                            <em class="fl pl10">(<?php echo $list['statistics']['stored_refund_num']?>笔)</em>
                            <em><?php echo sprintf('%.2f',$list['statistics']['stored_trade_actual_money'])?></em>
                        </div>
                    </div>
                <?php }?>
                <?php if (isset($list['statistics']) && ($list['statistics']['cash_trade_num']>0 || $list['statistics']['cash_refund_num']>0)){?>
                    <div class="m-row trade-data-list">
                        <div class="m-col-md">
                            <em class="pl20">现金：</em>
                            <em class="fr"><?php echo sprintf('%.2f',$list['statistics']['cash_trade_money'])?></em>
                        </div>
                        <div class="m-col-md text-right">
                            <em class="fl pl10">(<?php echo $list['statistics']['cash_trade_num']?>笔)</em>
                            <em>-<?php echo sprintf('%.2f',$list['statistics']['cash_discount_money'])?></em>
                        </div>
                        <div class="m-col-md text-right">
                            <em>-<?php echo sprintf('%.2f',$list['statistics']['cash_refund_money'])?></em>
                        </div>
                        <div class="m-col-md text-right">
                            <em class="fl pl10">(<?php echo $list['statistics']['cash_refund_num']?>笔)</em>
                            <em><?php echo sprintf('%.2f',$list['statistics']['cash_trade_actual_money'])?></em>
                        </div>
                    </div>
                <?php }?>
                <?php if (isset($list['statistics']) && ($list['statistics']['nopay_trade_num']>0 || $list['statistics']['nopay_refund_num']>0)){?>
                    <div class="m-row trade-data-list">
                        <div class="m-col-md">
                            <em class="pl20">无需付款：</em>
                            <em class="fr"><?php echo sprintf('%.2f',$list['statistics']['nopay_trade_money'])?></em>
                        </div>
                        <div class="m-col-md text-right">
                            <em class="fl pl10">(<?php echo $list['statistics']['nopay_trade_num']?>笔)</em>
                            <em>-<?php echo sprintf('%.2f',$list['statistics']['nopay_discount_money'])?></em>
                        </div>
                        <div class="m-col-md text-right">
                            <em>-<?php echo sprintf('%.2f',$list['statistics']['nopay_refund_money'])?></em>
                        </div>
                        <div class="m-col-md text-right">
                            <em class="fl pl10">(<?php echo $list['statistics']['nopay_refund_num']?>笔)</em>
                            <em><?php echo sprintf('%.2f',$list['statistics']['nopay_trade_actual_money'])?></em>
                        </div>
                    </div>
                <?php }?>
                <?php if (isset($list['statistics']) && ($list['statistics']['group_trade_num']>0 || $list['statistics']['group_refund_num']>0)){?>
                    <div class="m-row trade-data-list">
                        <div class="m-col-md">
                            <em class="pl20">集中收银：</em>
                            <em class="fr"><?php echo sprintf('%.2f',$list['statistics']['group_trade_money'])?></em>
                        </div>
                        <div class="m-col-md text-right">
                            <em class="fl pl10">(<?php echo $list['statistics']['group_trade_num']?>笔)</em>
                            <em>-<?php echo sprintf('%.2f',$list['statistics']['group_discount_money'])?></em>
                        </div>
                        <div class="m-col-md text-right">
                            <em>-<?php echo sprintf('%.2f',$list['statistics']['group_refund_money'])?></em>
                        </div>
                        <div class="m-col-md text-right">
                            <em class="fl pl10">(<?php echo $list['statistics']['group_refund_num']?>笔)</em>
                            <em><?php echo sprintf('%.2f',$list['statistics']['group_trade_actual_money'])?></em>
                        </div>
                    </div>
                <?php }?>
            </div>
        </div>
        <table class="table g-table g-table-left g-table-fixed mt20">
            <thead>
            <tr>
                <th width="12%">交易时间</th>
                <th width="18%">订单</th>
                <th width="10%">交易类型</th>
                <th class="text-right" width="10%">订单金额</th>
                <th class="text-right" width="12%">收入/支出</th>
                <th width="10%">订单状态</th>
                <th width="20%">门店/操作员</th>
                <th width="8%">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if (isset($list['list'])){foreach ($list['list'] as $v){?>
                <tr>
                    <td>
                        <p><?php echo empty($v['pay_time'])? date('Y-m-d',strtotime($v['create_time'])) : date('Y-m-d',strtotime($v['pay_time']))?></p>
                        <p><?php echo empty($v['pay_time'])? date('H:i:s',strtotime($v['create_time'])) : date('H:i:s',strtotime($v['pay_time']))?></p>
                    </td>
                    <td>
                        <p><?php echo $v['order_no']?></p>
                        <p><?php echo $GLOBALS['ORDER_PAY_CHANNEL'][$v['pay_channel']]?></p>
                    </td>
                    <td><?php echo $v['order_flag'] == FLAG_NO ? '收款':'退款'?></td>
                    <td class="<?php echo $v['order_flag'] == FLAG_NO ? '':'text-gray'?> text-right"><?php echo sprintf('%.2f',$v['order_paymoney'])?></td>
                    <td class="text-right">
                    <p>
                    <?php if ($v['order_flag'] == FLAG_NO){
                        if ($v['order_status'] == ORDER_STATUS_REVOKE || $v['pay_status']==ORDER_STATUS_UNPAID){
                            echo sprintf('%.2f',0);
                        }else{
                            echo sprintf('%.2f',$v['order_paymoney']-$v['coupons_money']-$v['discount_money']-$v['merchant_discount_money']);
                        }
                    }else{
                        echo '-'.sprintf('%.2f',$v['refund_money']);
                    }?>
                    </p>
                    <p>
                    <?php if ($v['alipay_discount_money'] > 0 && $v['order_flag'] == FLAG_NO){
                        echo '补贴：'.$v['alipay_discount_money'];
                    }else if ($v['wxpay_discount_money'] > 0 && $v['order_flag'] == FLAG_NO){
                        echo '补贴：'.$v['wxpay_discount_money'];
                    }
                    ?>
                    </p>
                    </td>
                    <td><?php echo $v['order_status'] == ORDER_STATUS_NORMAL ? ($v['pay_status']==ORDER_STATUS_PAID?'已付款':'待支付'):$GLOBALS['ORDER_STATUS'][$v['order_status']]?></td>
                    <td>
                        <p><?php echo empty($v['branch_name'])? $v['store_name']: $v['store_name'].'('.$v['branch_name'].')'?></p>
                        <p><?php echo empty($v['operator_number']) ? $v['operator_name'] : $v['operator_name'].'('.$v['operator_number'].')'?></p>
                    </td>
                    <td>
                        <a href="<?php echo $this->createUrl('tradeDetail',array('order_no'=>$v['order_no']));?>">详情</a>
                    </td>
                </tr>
            <?php }}?>
            </tbody>
        </table>
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
	//弹窗:门店数量赋值
	showStoreCount();
})
function showStoreCount(){//弹窗:门店数量赋值
	var count = 0;
	var str = '';
	$('.child1').each(function(){
		count = $(this).find('.storeBox').length;
		str = "( "+count+" )"
		$(this).find('.parentWrap').append(str);
	})
	$('.childWrap').each(function(){
		var count = $(this).find('.storeBox').length;
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
function href_location(type){//收款/退款快捷切换
	$('#trade_type').find('option').eq(type).attr('selected','selected').siblings().removeAttr('selected');
	$('#search_form').submit();
}
function pay_channel_select(){ //支付渠道赋值
	var pay_channel_val = $('#pay_channel').val();
	$('input[name=pay_channel]').val(pay_channel_val);
}
function store_select(type){//门店选择
	store_arr=[];
	$(".storeBox").each(function(){
		if($(this).is(":checked")){
			store_arr.push($(this).val());
		}
	})
	var len = store_arr.length;
	var len1 = $(".storeBox").length;
	$('#store_select_count').text(len);
	//确认事件
	if (type == 'query'){
    	if (len == 0){
    		$('#store_id').val('请选择门店');
    	}else if(len == len1){
    		$('#store_id').val('全部门店');
    	}else{
        	$('#store_id').val('已选'+len+'家门店');
    	}
    	$('input[name=store_id]').val(store_arr);
    	//更新操作员下拉列表
    	get_operator_list();
		$('#store_select').hide();
	}
}
//获取操作员下拉列表
function get_operator_list(){
	var stores = $('input[name=store_id]').val();
	$.ajax({
        url: '<?php echo $this->createUrl('ajaxGetOperatorList')?>',
        data: {stores: stores},
        type: 'get',
        dataType: 'json',
        success: function (result) {
        	$('#operator').text('');
            if (result.status == true){
            	$('#operator').append(result.data);
            }else{
            	$('#operator').append("<option value>全部</option>");
            }
        }
    });
}
//时间段查询
function time_search(){
	var val = $('#trade_time').val();
	$('#search_form').submit();
}
//切换时间段查询
function changeTime(day){
	var time = getDateInterval(day,0);
	$('input[name=Time]').val(time);
	time_search();
}
//突显第几日按钮
function checkTimeType(){
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
//获取时间段
function getDateInterval(start_time,end_time){
	var val = getSomeDate(start_time)+' - '+getSomeDate(end_time);
	return val;
}
//获取日期
function getSomeDate(day){
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