<body>
	<div class="kkfm_r_inner">
		<!--搜索框 count-top包含的部分-->
	    <div class="top">
	        <div class="name">
	        	商品管理
	        </div>
	    </div>
	    <?php $form = $this->beginWidget('CActiveForm', array(
	        'enableAjaxValidation' => true,
	        'htmlOptions' => array('name' => 'createForm'),
	    ));?>
			<div class="contant shopList">
		        <div class="cz"><a href="#" class="btn_com_blue">添加商品</a></div>
		    	<div class="sh-search clearfix">
		        	<?php echo CHtml::dropDownList('ActivityRecord[prize_type]', isset($_POST['ActivityRecord']['prize_type']) ? $_POST['ActivityRecord']['prize_type'] : '', $prize_type_arr,array('id'=>'prize_type')) ?>
		        	 <input type="text" placeholder="请输入手机号" class="txt" id="phone_num" name="ActivityRecord[phone_num]" value="<?php echo isset($_POST['ActivityRecord']['phone_num']) ? $_POST['ActivityRecord']['phone_num'] : ''?>">
		             <input type="submit" class="search" value=""> 
		        </div>
		        <div class="recharge">
		            <table width="100%" cellspacing="0" cellpadding="0">
		              	<tr class="order-title" >
		                    <td width="25%">序号</td>
		                    <td width="25%">奖项</td>
		                    <td width="25%">中奖号码</td>
		                    <td width="25%">抽奖时间</td>
		              	</tr>
		              	<?php if (!empty($record)) { ?>
		              		<?php foreach ($record as $k => $v) { ?>
				              	<tr>
				                    <td>
				                    	<h3><?php echo $k+1?></h3>
				                    </td>
				                    <td>
				                    	<h3><?php echo $GLOBALS['PRIZE_TYPE'][$v['prize_type']]?></h3>
				                    </td>
				                    <td>
				                    	<h3><?php echo $v['user_phone']?></h3>
				                    </td>
				                    <td>
				                    	<h3><?php echo $v['create_time']?></h3>
				                    </td>
				              	</tr>
		              		<?php } ?>
		              	<?php } ?>
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
		<?php $this->endWidget(); ?>
	</div> 
</body>
