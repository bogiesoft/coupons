<!--添加分类弹出框-->

<div class="popWrap" id="pop" style="width:655px; display:">
	<div class="pop_con pIndexEdit">
        <div class="title">
        	<span class="name">优惠券列表</span><!--这是“首页设置2选择分类”的头部-->
        </div>
        <div class="pop_content">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" border="0">
                <tr class="orderHead">
                    <td width="50%">标题</td>
                    <td>创建时间</td>
                    <td>
                    	<?php echo CHtml::beginForm() ?>
	                    	<div class="sh-search clearfix">
	                            <?php echo CHtml::textField('title',$title,array('placeholder'=>"请输入优惠券名称", 'class'=>"txt"))?>
	                            <input type="submit" class="search" value=""> 
	                        </div>
	                    <?php echo CHtml::endForm() ?>
                    </td>
                </tr>
                <?php if (!empty($coupons)){ ?>
                	<?php foreach ($coupons as $key => $value) { ?>
	                	<tr>
	                		 <td>
		                        <?php echo $value['title']?> 
		                    </td>
		                    <td><?php echo $value['create_time']?></td>
		                    <td><a href="javascript:;" onclick="choose(<?php echo $prize_type;?>, <?php echo $key;?>, '<?php echo $value['title'];?>')" class="btn_com_gray">选取</a></td>
		                </tr>
                	<?php } ?>
                <?php } ?>
                <!-- 分页开始 -->            	
                <tr style="border:none">
	                <td style="border:none;text-align:right" colspan="3">
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
	                </td>
                </tr>           
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
	function choose(prize_type, id, name)
	{
		window.parent.window.addHrefChoose(prize_type, id, name); //调用父窗口的js方法
	}
</script>
