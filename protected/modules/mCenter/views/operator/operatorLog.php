<script type="text/javascript">
	$(document).ready(main_obj.list_init);
	//设置日期控件
	$(document).ready(function() {
		$('input[name=Time]').daterangepicker({
	        timePicker: true,
	        format: 'YYYY/MM/DD HH:mm:ss',
	        dateLimit: true,
	        maxDate: true
	    });
	});
</script>

<div class="kkfm_r_inner">

    <div class="top">
        <div class="name">
        <?php echo CHtml::beginForm(Yii::app()->createUrl($this->route), 'get')?>
            <?php /*var_dump($operators);*/?>
        	<?php echo CHtml::dropDownList('operator_id', '', $operators, array('style' => 'width:100px;height:30px','prompt' => '选择操作员'))?>
        	<?php echo CHtml::textField('Time', isset($_GET['Time'])?$_GET['Time']:'', array('style' => 'width:220px', 'readonly' => 'true', 'placeholder' => '查询时间段'))?>
        	<input type="submit" value="搜索">
        <?php echo CHtml::endForm()?>
        </div>
    </div>

    <div class="bus-contant">
    	<table cellspacing="0" cellpadding="0" width="100%" style="margin-top:10px;">
        	
            <tr class="order-title">
                <td align="center" nowrap="nowrap">操作员</td>
                <td align="center" nowrap="nowrap">时间</td>
                <td align="center" nowrap="nowrap">操作</td>
                <td align="center" nowrap="nowrap">登录IP</td>
            </tr>
            
            <?php if (!empty($list)) {
            	foreach ($list as $k => $v) { ?>
		            <tr>
		                <td align="center" valign="middle"><span class="fw"><?php echo $v['operator']?></span></td>
		                <td align="center" valign="middle"><?php echo $v['time']?></td>
		                <td align="center" valign="middle"><?php echo $v['operation']?></td>
		                <td align="center" valign="middle"><?php echo $v['ip'] ?></td>
		            </tr>
            	<?php }
            }else { ?>
            	<tr>
					<td colspan="6" align="center">没有操作员日志信息</td>
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
