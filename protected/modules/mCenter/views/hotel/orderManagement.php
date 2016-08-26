<!-- orderManagement ↓ -->
<div class="kkfm_r_inner">
	 <div class="top">
        <div class="name">订单管理</div>
     </div>
     <div class="orderList orderListRoomM">
        <div class="sh-search clearfix">
            <?php echo CHtml::beginForm('','post'); ?>
                <input type="text" name="time" class="txt" placeholder="请选择下单时间段" value="<?php echo isset($_POST['time'])&&!empty($_POST['time'])?$_POST['time']:''?>">
                <input type="text" name="keyword" placeholder="可输入姓名、电话、订单搜索" class="txt" value="<?php echo isset($_POST['keyword']) && !empty($_POST['keyword'])?$_POST['keyword']:'';?>">
                <input type="submit" class="btn_com_blue" value="搜索">
            <?php echo CHtml::endForm()?>
        </div>
        <div class="orderWarp">
            <div class="status-nav clearfix">
                <ul>
                    <li class="cur"><a href="<?php echo Yii::app()->createUrl('mCenter/Hotel/orderManagement')?>">全部</a></li>
                    <li class=""><a href="<?php echo Yii::app()->createUrl('mCenter/Hotel/orderManagement')?>?status=1">待确定</a></li>
                    <li class=""><a href="<?php echo Yii::app()->createUrl('mCenter/Hotel/orderManagement')?>?status=2">已确定</a></li>
                    <li class=""><a href="<?php echo Yii::app()->createUrl('mCenter/Hotel/orderManagement')?>?status=3">已拒绝</a></li>
                    <li class=""><a href="<?php echo Yii::app()->createUrl('mCenter/Hotel/orderManagement')?>?status=4">已取消</a></li>
                    <li class="last"><a href="<?php echo Yii::app()->createUrl('mCenter/Hotel/orderManagement')?>?status=5">已入住</a></li>
                </ul>
                <hr>
            </div>
            <div class="order-list">
            	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                	<thead>
                        <tr class="orderHead">
                            <td width="20%">订单</td>
                            <td>房间类型</td>
                            <td>订房人</td>
                            <td>入住时间</td>
                            <td width="15%">订单状态</td>
                        </tr>
                        <tr><td height="10"></td></tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($order_list)){
                    foreach ($order_list as $k => $v){
                        ?>
                        <tr class="orderCon">
                            <td class="al">
                                <span><?php echo $v['order_no']?></span>
                                <div class="time"><?php echo $v['create_time']?></div>
                            </td>
                            <td><?php echo $v['room_name']." ￥".$v['price']?><br><?php echo $v['num']?>间</td>
                            <td><?php echo $v['person_name']?><br><?php echo $v['person_tel']?></td>
                            <td>
                                <div class="time"><?php echo date('Y.m.d', strtotime($v['check_in_time'])).' -- '.date('Y.m.d', strtotime($v['check_out_time']));?></div>
                                <?php 
                                $time_1 = strtotime(date('Y-m-d', strtotime($v['check_in_time'])));
                                $time_2 = strtotime(date('Y-m-d', strtotime($v['check_out_time'])));
                                $day = ($time_2-$time_1)/3600/24;
                                ?>
                                <span>共<?php echo $day?>晚</span>
                            </td>
                            <td class="border"><span class="blue"><?php echo $GLOBALS['__HOTEL_ORDER_STATUS'][$v['status']]?></span> </td>
                        </tr>
                    <?php }}?>
                  </tbody>
               </table>
                <div class="tfoot">
                    <div class="r">
                         <?php $this -> widget('CLinkPager',array(
                            'pages'=>$pages,
                            'header'=>'共&nbsp;<span class="yellow">'.$pages -> getItemCount().'</span>&nbsp;条&nbsp;',
                            'prevPageLabel' => '上一页',
                            'nextPageLabel'=>'下一页',
                            'maxButtonCount'=>8
                        ));?>
                    </div>
                </div>
            </div>
        </div>	
     </div>
</div>
<script type="text/javascript">
	$(function(){
		_pop("pop");
		var status = "<?php echo empty($_GET['status']) ? '' : $_GET['status']?>";
		if (status != ''){
			$('.status-nav li').removeClass('cur').eq(status).addClass('cur');
		}
	})
	
	$('input[name=time]').daterangepicker({
	        format: 'YYYY/MM/DD',
	        dateLimit: true,
	        maxDate: true,
	        startDate:'<?php echo date('Y/m/d',strtotime('-1 days'));?>',
	    	endDate:'<?php echo date('Y/m/d');?>'
	    });
</script>
<!-- orderManagement ↑ -->