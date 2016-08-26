<script type="text/javascript">
	$(document).ready(main_obj.list_init);
</script>

<div class="kkfm_r_inner">

   <div class="top">
        <div class="name">储值明细列表</div>
    </div>

    <div class="recharge">
    	<table width="100%" cellspacing="0" cellpadding="0">
        	
            <tr class="order-title">
                <td align="center" nowrap="nowrap">会员账号</td>
                <td align="center" nowrap="nowrap">储值活动</td>
                <td align="center" nowrap="nowrap">数量</td>
                <td align="center" nowrap="nowrap">实收金额</td>
                <td align="center" nowrap="nowrap">操作员编号</td>
                <td align="center" nowrap="nowrap">操作员姓名</td>
                <td align="center" nowrap="nowrap">交易时间</td>
                <td align="center" nowrap="nowrap">支付渠道</td>
                <td align="center" nowrap="nowrap">状态</td>
            </tr>
            
            <?php if (!empty($list)) {
            	foreach ($list as $k => $v) { ?>
		            <tr class='<?php if(($k+1)%2==0){echo "bg";}else{echo "";} ?>' style="border-bottom:1px solid #dedede">
		                <td align="center" valign="middle">
		                   <?php echo substr_replace($v['user_account'],'****',3,4);?>
		                </td>
		                <td align="center" valign="middle"><?php echo $v['store_name'];?></td>
		                <td align="center" valign="middle"><?php echo $v['num'];?></td>
		                <td align="center" valign="middle"><?php echo $v['money'];?></td>
		                <td align="center" valign="middle"><?php echo $v['operator_number'];?></td>
		                <td align="center" valign="middle"><?php echo $v['operator_name'];?></td>
		                <td align="center" valign="middle"><?php echo $v['create_time'];?></td>
		                <td align="center" valign="middle"><?php echo $v['operator_name']=="线上储值"?'微信线上支付':$GLOBALS['ORDER_PAY_CHANNEL'][$v['pay_channel']];?></td>
		                <?php if($v['order_status'] == ORDER_STATUS_REVOKE){?>
		                <td align="center" valign="middle" class="red">已撤销</td>
		                <?php }else{?>
		                <?php if($v['pay_status'] == ORDER_STATUS_PAID){?>
		                <td align="center" valign="middle">已付款</td>
		                <?php }?>
		                <?php }?>
		            </tr>
            	<?php }
            }?>
            <!-- 分页开始 -->
            	
                	<tr style="border:none">
                	<td style="border:none;text-align:right" colspan="9">
                    	<?php $this -> widget('CLinkPager',array(
                    			'pages'=>$pages,
                                'header'=>'共&nbsp;<span class="yellow">'.$pages -> getItemCount().'</span>&nbsp;条&nbsp;',
                                'prevPageLabel' => '上一页',
                                'nextPageLabel'=>'下一页',
                                'firstPageCssClass' => '',  
                                'lastPageCssClass' => '', 
                                'firstPageLabel' => '首页',    
                                'lastPageLabel' => '末页',
                                'maxButtonCount'=>8
                    	));?>
                    	</td>
                   	</tr>
               	
          	<!-- 分页结束 -->
        </table>
        <?php if (empty($list)) { ?>
            <?php echo '<a href="javascript:;" class="blank-tip">没有找到相关信息</a>';
        } ?>
  	</div>
</div> 


