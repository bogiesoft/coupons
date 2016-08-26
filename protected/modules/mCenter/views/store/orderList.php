<script src="<?php echo GJ_STATIC_JS."gj.js"?>"></script>

<script type="text/javascript">
	$(document).ready(main_obj.list_init);
	//设置日期控件

	$(document).ready(function() {
		$('input[name=Time]').daterangepicker({
	        format: 'YYYY/MM/DD',
	        dateLimit: true,
	        maxDate: true
	    });
	});	
	
</script>

<div class="kkfm_r_inner">

    <div class="top">
        <div class="name">
        <?php echo CHtml::beginForm(Yii::app()->createUrl($this->route), 'get')?>
        	<?php echo CHtml::textField('Time', isset($_GET['Time']) ? $_GET['Time'] : '', array('style' => 'width:140px', 'readonly' => 'true', 'placeholder' => '查询时间段'))?>
        	<?php echo CHtml::dropDownList('store_id', isset($_GET['store_id']) ? $_GET['store_id'] : '', $store, array('id'=>'orderlist_store_id','prompt' => '选择门店', 'style' => 'width:100px;height:30px'))?>
        	<?php echo CHtml::dropDownList('pay_channel', isset($_GET['pay_channel']) ? $_GET['pay_channel'] : '', $channel, array('prompt' => '交易类型', 'style' => 'width:100px;height:30px'))?>
        	<?php echo CHtml::dropDownList('order_status', isset($_GET['order_status']) ? $_GET['order_status'] : '', $status, array('prompt' => '订单状态', 'style' => 'width:100px;height:30px'))?>
        	<?php echo CHtml::dropDownList('operator_id', isset($_GET['operator_id']) ? $_GET['operator_id'] : '', $operator, array('id'=>'operate_id','prompt' => '查询操作员', 'style' => 'width:100px;height:30px'))?>
        	<input type="text" class="txt" name="keyword" value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : ''?>" placeholder="订单号搜索" />
        	<input type="submit" value="搜索">
            <a href="<?php echo Yii::app()->createUrl('mCenter/Store/ExportExcel',array('keyword'=>isset($_GET['keyword']) ? $_GET['keyword'] : '',
                        'operator_id'=>isset($_GET['operator_id']) ? $_GET['operator_id'] : '',
                        'store_id'=>isset($_GET['store_id']) ? $_GET['store_id'] : '',
                        'pay_channel'=>isset($_GET['pay_channel']) ? $_GET['pay_channel'] : '',
                        'order_status'=>isset($_GET['order_status']) ? $_GET['order_status'] : '',
                        'Time'=>isset($_GET['Time']) ? $_GET['Time'] : '',
            ));?>"><input type="button" value="导出"></a>
       	<?php echo CHtml::endForm()?>
        </div>
   </div>
    <?php echo Yii::app()->user->getFlash('error');?>
    
    
     <div class="moneyTotal clearfix">
        	<div class="r">
            	<span>交易金额（<strong class="red"><?php echo isset($arr['successOrderCount'])?$arr['successOrderCount']:'0'; ?></strong>笔）<strong class="red total"><?php echo isset($arr['successOrderMoney'])?number_format($arr['successOrderMoney'],2,'.',''):'0.00'; ?></strong></span>
       			<span>退款金额（<strong class="blue"><?php echo isset($arr['refundRecordCount'])?$arr['refundRecordCount']:'0'; ?></strong>笔）<strong class="blue total"><?php echo isset($arr['refundRecordMoney'])?number_format($arr['refundRecordMoney'],2,'.',''):'0.00'; ?></strong></span>         
            </div>
    </div>
    
    
    <div class="bus-contant">
    	<table cellspacing="0" cellpadding="0" width="100%" style="margin-top:10px;">
        	
            <tr class="order-title">
                <td align="center" nowrap="nowrap">订单号</td>
                <td align="center" nowrap="nowrap">支付宝账号</td>
                <td align="center" nowrap="nowrap">订单金额</td>
                <td align="center" nowrap="nowrap">状态</td>
                <td align="center" nowrap="nowrap">交易类型</td>
                <td align="center" nowrap="nowrap">操作员</td>
                <td align="center" nowrap="nowrap">交易时间</td>
            </tr>
            
            <?php if (!empty($list)) {
            	foreach ($list as $k => $v) {
                    $status="";
                    if ($v['pay_status'] == ORDER_STATUS_PAID && $v['order_status'] == ORDER_STATUS_NORMAL) {
                        $status = '已付款';
                    }
                    if ($v['pay_status'] == ORDER_STATUS_UNPAID) {
                        $status = '待付款';
                    }
                    if ($v['order_status'] == ORDER_STATUS_REFUND) {
                        $status = '已退款';
                    }
                    if ($v['order_status'] == ORDER_STATUS_PART_REFUND) {
                        $status = '已部分退款';
                    }
                    if ($v['order_status'] == ORDER_STATUS_REVOKE) {
                        $status = '已撤销';
                    }
                    if($v['order_status'] == ORDER_STATUS_HANDLE_REFUND){
						$status = '退款处理中';
					}
                    ?>
		            <tr>
		                <td align="center" valign="middle"><span class="fw"><?php echo $v['order_no']?></span></td>
		                <td align="center" valign="middle"><?php echo $v['alipay_account']?></td>
		                <td align="center" valign="middle"><?php echo $v['paymoney']?></td>
		                <td align="center" valign="middle"><?php echo $status?></td>
		                <td align="center" valign="middle"><?php echo $GLOBALS['ORDER_PAY_CHANNEL'][$v['pay_channel']]?></td>
		                <td align="center" valign="middle"><?php echo $v['operator_name']?></td>
		                <td align="center" valign="middle"><?php echo $v['pay_time']?></td>
		            </tr>
            	<?php }
            }else { ?>
            	<tr>
					<td colspan="7" align="center">没有交易明细信息</td>
				</tr>
            <?php }?>
			<!-- 分页开始 -->
                	<tr style="border:none">
                	<td style="border:none;text-align:right" colspan="7">
                    	<?php $this -> widget('CLinkPager',array(
                    			'pages'=>$pages,
                                'header'=>'共&nbsp;<span class="yellow">'.$pages -> getItemCount().'</span>&nbsp;条&nbsp;',
                                'prevPageLabel' => '上一页',
                                'nextPageLabel'=>'下一页',
                                'maxButtonCount'=>8
                    	));?>
                    	</td>
                   	</tr>
               	
          	<!-- 分页结束 -->
        </table>
  	</div>
</div>

<script  type="text/javascript">
    $(function(){
        orderlist_store_id('<?php echo Yii::app()->createUrl('mCenter/Store/ChooseStore')?>');
    });

</script>
