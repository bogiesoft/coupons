<body>
	<div class="kkfm_r_inner">
		<!--搜索框 count-top包含的部分-->
	    <div class="top">
	        <div class="name">
	        	欢乐大转盘
	        </div>
	    </div>
		<div class="contant shopList">
	        <div class="cz"><a href="<?php echo Yii::app()->createUrl('mCenter/promotions/turntableAdd')?>" class="btn_com_blue">新增活动</a></div>
	    	<div class="sh-search clearfix">
	        	
	        </div>
	        <div class="recharge">
	            <table width="100%" cellspacing="0" cellpadding="0">
	              	<tr class="order-title" >
	                    <td width="25%">活动名称</td>
	                    <td width="25%">活动时间</td>
	                    <td width="25%">状态</td>
	                    <td width="25%">操作</td>
	              	</tr>
	              	<?php if (!empty($list)) { ?>
	              		<?php foreach ($list as $k => $v) { ?>
	              			<tr>
		              			 <td>
			                    	<h3><?php echo $v['name']?></h3>
			                    </td>
			                    <td><?php echo date('Y.m.d',strtotime($v['start_time'])).'--'.date('Y.m.d',strtotime($v['end_time']))?></td>
			                    <td>
			                    	<?php if(strtotime($now) < strtotime($v['start_time'])) { ?>
			                    		未开始
			                    	<?php }elseif (strtotime($now) > strtotime($v['end_time'])) {?>
			                    		已结束
			                    	<?php }else{ ?>
			                    		进行中
			                    	<?php } ?>
			                    </td>
			                    <td>
			                    	<a href="<?php echo Yii::app()->createUrl('mCenter/promotions/turntableAdd', array('id'=>$v['id']))?>" class="blue">编辑</a> -
			                    	<a href="<?php echo Yii::app()->createUrl('mCenter/promotions/turntableDel', array('id'=>$v['id']))?>" onclick="return confirm('确认删除吗？');" class="blue">删除</a>
			                    	<a href="<?php echo Yii::app()->createUrl('mCenter/promotions/playRecord', array('id'=>$v['id']))?>" class="blue">抽奖纪录</a>
			                    </td>
	              			</tr>
	              		<?php } ?>
	              	<?php } ?>
	              	</tr>
	          	</table>
	          	<div class="tfoot">
	                <div class="r">
	                	<div class="page1">
	                        <?php $this->widget('CLinkPager', array(
		                        	'pages' => $pages,
		                        	'header' => '', //分页前显示的内容
		                        	'maxButtonCount' => 8, //显示分页数量
		                        	'firstPageCssClass' => '',
		                        	'lastPageCssClass' => '',
		                        	'firstPageLabel' => '首页',
		                        	'nextPageLabel' => '下一页',
		                        	'prevPageLabel' => '上一页',
		                        	'lastPageLabel' => '末页',
		                    	));?>
	                    </div>
	                </div>
	            </div>
	        
	        </div>
	    </div>
	</div> 
</body>
