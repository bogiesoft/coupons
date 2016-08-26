<script type="text/javascript">
	$(document).ready(main_obj.list_init);
</script>

<div class="kkfm_r_inner">

   <div class="top">
        <div class="name">优惠券明细列表</div>
    </div>

    <div class="recharge">
    	<table width="100%" cellspacing="0" cellpadding="0">
        	
            <tr class="order-title">
                <td align="center" nowrap="nowrap">序号</td>
                <td align="center" nowrap="nowrap">券名</td>
                <td align="center" nowrap="nowrap">用户名</td>
                <td align="center" nowrap="nowrap">手机号</td>
                <td align="center" nowrap="nowrap">使用状态</td>                
                <td align="center" nowrap="nowrap">优惠券开始时间</td>
                <td align="center" nowrap="nowrap">优惠券结束时间</td>
                <td align="center" nowrap="nowrap">使用时间</td>
            </tr>
           
            <?php if (!empty($list)) {  static $i = 0; 
            	foreach ($list as $k => $v) { ?>
		            <tr class='<?php if(($k+1)%2==0){echo "bg";}else{echo "";} ?>' style="border-bottom:1px solid #dedede">
		                <td align="center"><span class="fw"><?php echo ++$i;?></span></td>
		                 <td align="center" valign="middle"><?php echo $v['coupons_name'];?></td>
		                <td align="center" valign="middle">
		                   <?php echo $v['user_name'];?>
		                </td>
		                <td align="center" valign="middle"><?php echo substr_replace($v['account'],'****',3,4);?></td>
		                <td align="center" valign="middle"><?php echo isset($v['status'])?$GLOBALS['COUPONS_USE_STATUS'][$v['status']]:'';?></td>
		                
                                
		                <td align="center" valign="middle"><?php echo $v['start_time'];?></td>
		                <td align="center" valign="middle"><?php echo $v['end_time'];?></td>
		                <td align="center" valign="middle"><?php echo $v['use_time'];?></td>
		            </tr>
            	<?php }
            }else { ?>
				
            <?php }?>
 <!-- 分页开始 -->
            	
                	<tr style="border:none">
                	<td style="border:none;text-align:right" colspan="8">
                    	<?php $this -> widget('CLinkPager',array(
                    			'pages'=>$pages,
                                'header'=>'共&nbsp;<span class="yellow">'.$pages -> getItemcount().'</span>&nbsp;条&nbsp;',
                                'prevPageLabel' => '上一页',
                                'nextPageLabel'=>'下一页',
                                'maxButtonCount'=>8
                    	));?>
                    	</td>
                   	</tr>
               	
          	<!-- 分页结束 -->
        </table> 
        
        <?php if (empty($list)) { ?>
            <?php echo '<div align="center"><font color="red" size="3">没有找到相关信息</font></div>';
        } ?>
  	</div>
</div> 


