<script type="text/javascript">
	$(document).ready(main_obj.list_init);
</script>

<div class="kkfm_r_inner">

   <div class="top">
        <div class="name">
        优惠券列表
        </div>
        <div class="cz">
        <?php echo CHtml::beginForm('','get');?>
        	<input type="text" class="txt" name="keyword_name" style="color:#999; width:186px;margin-left:40px;" value="<?php echo isset($_GET['keyword_name'])?$_GET['keyword_name']:'' ?>" placeholder="请输入券名称" />
        	<?php echo CHtml::dropDownList('cou_type', isset($_GET['cou_type'])?$_GET['cou_type']:'', $coupon_type,array('prompt' => '请选择', 'class' => 'txt')); ?>
        	<input type="submit" value="搜索">
       	<?php echo CHtml::endForm()?>
       	</div>
    </div>

    <div class="recharge">
    	<table width="100%" cellspacing="0" cellpadding="0">
        	
            <tr class="order-title">
                <td align="center" nowrap="nowrap">券类型</td>
                <td align="center" nowrap="nowrap">券名称</td>
                <td align="center" nowrap="nowrap">发放时间</td>
                <td align="center" nowrap="nowrap">创建时间</td>
                <td align="center" nowrap="nowrap">操作</td>
            </tr>
            
            <?php if (!empty($list)) {
            	foreach ($list as $k => $v) { ?>
		            <tr class='<?php if(($k+1)%2==0){echo "bg";}else{echo "";} ?>' style="border-bottom:1px solid #dedede">
		                <td align="center" valign="middle"><?php echo isset($v['type'])?$GLOBALS['COUPON_TYPE'][$v['type']]:'';?></td>
		                <td align="center" valign="middle"><?php echo $v['name'];?></td>
		                <td align="center" valign="middle"><?php echo date('Y.m.d',strtotime($v['start_time'])).' - '.date('Y.m.d',strtotime($v['end_time']));?></td>
		                <td align="center" valign="middle"><?php echo $v['create_time'];?></td>
		                <td align="center" valign="middle">
			                <dl class="operate">
			                	<dt>操作</dt>
			                    <dd>
			                    	<span><a href="<?php echo Yii::app()->createUrl('mCenter/coupons/delYhq', array('id' => $v['id']))?>"
			                    	onclick="return confirm('删除后不可恢复，继续吗？');">删除</a></span>
			                    	<span><a href="<?php echo Yii::app()->createUrl('mCenter/coupons/yhqDetails', array('id' => $v['id'])); ?>">详情</a></span>
			                </dl>
		                </td>
		            </tr>
            	<?php }
            }?>
  			<!-- 分页开始 -->
            	
                	<tr style="border:none">
                	<td style="border:none;text-align:right" colspan="5">
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



