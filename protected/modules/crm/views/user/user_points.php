<script type="text/javascript">
$(document).ready(main_obj.list_init);
$(function(){
    var now = new Date();
    $('#point_time').daterangepicker({
        timePicker: false,
        format: 'YYYY/MM/DD',
        dateLimit: true,
        maxDate: true,
    });
});
</script>
<div class="kkfm_r_inner">
    <div class="storeManage">
        <div class="top">
            <div class="name">
                用户详情
            </div>
        </div>
        <div class="bus-contant">
            <div class="user_detail_nav">
                <ul class="wq_btn_group">
                <li onClick="window.self.location.href='<?php echo $this->createUrl('userInfo', array('id'=>$_GET['id'])) ?>'">用户信息</li>
                <li onClick="window.self.location.href='<?php echo $this->createUrl('userExpenses', array('id'=>$_GET['id'])) ?>'">消费记录</li>
                <li onClick="window.self.location.href='<?php echo $this->createUrl('userPoints', array('id'=>$_GET['id'])) ?>'" class="active">积分记录</li>
                <li onClick="window.self.location.href='<?php echo $this->createUrl('userStored', array('id'=>$_GET['id']))?>'">储值记录</li>
                <!--<li onClick="window.self.location.href='<?php echo $this->createUrl('userPurchase', array('id'=>$_GET['id']))?>'">商城购买记录</li>-->
            </ul>
        </div>
        <div class="user_detail">
            <ul class="m_record">
                <li>
                    <p>可用积分<i class="record_icon record_icon_point"></i>
                    </p>
                    <p class="number"><?php echo $user->points?$user->points:'0'?></p>
                </li>
                <li>
                    <p>累计积分<i class="record_icon record_icon_point"></i>
                    </p>
                    <p class="number"><?php echo $total_get_points?></p>
                </li>
            </ul>
            <div class="wq_form_inline">
                <?php echo CHtml::beginForm(Yii::app()->createUrl($this->route), 'get', array('id' => 'search_form'))?>
                <input type="hidden" name="id" value="<?php echo $_GET['id']?>">
                <div class="wq_inline_group">
                    <input type="text" name="point_time" id="point_time" class="wq_form_control" value="<?php echo isset($_GET['point_time'])&&!empty($_GET['point_time'])?$_GET['point_time']:''?>" placeholder="请选择时间段…">
                </div>
                <div class="wq_inline_group">
                    <select class="wq_form_control" name="point_type">
                        <option value="0">请选择类型</option>
                        <?php 
                            foreach($point_types as $k=>$v)
                            {
                        ?>
                        <option value="<?php echo $k?>" <?php if($k==(isset($_GET['point_type'])&&!empty($_GET['point_type'])?$_GET['point_type']:0))echo "selected";?>><?php echo $v?></option>
                        <?php        
                            }
                        ?>
                    </select>
                </div>
                <div class="wq_inline_group">
                    <select class="wq_form_control" name="point_from">
                        <option value="0">请选择积分方式</option>
                        <?php 
                            foreach($point_froms as $k=>$v)
                            {
                        ?>
                        <option value="<?php echo $k?>" <?php if($k==(isset($_GET['point_from'])&&!empty($_GET['point_from'])?$_GET['point_from']:0))echo "selected";?>><?php echo $v?></option>
                        <?php         
                            }
                        ?>
                    </select>
                </div>
                <div class="wq_inline_group">
                    <input type="submit" value="" class="wq_form_control search">
                </div>
                <?php echo CHtml::endForm()?>
            </div>
            <table cellspacing="0" cellpadding="0" width="100%" class="consume_table">
                <thead>
                    <tr class="thead">
                        <td>订单号</td>
                        <td>积分时间</td>
                        <td>类型</td>
                        <td>积分方式</td>
                        <td>积分</td>
                    </tr>
                </thead>
                <tbody>
                <!--
                    <tr>
                        <td>2016.5.11 12:00:00</td>
                        <td>1</td>
                        <td>消费</td>
                        <td>10000</td>
                    </tr>
                -->
                <?php 
                    if(!empty($pointDetailes))
                    {
                        foreach($pointDetailes as $v)
                        {
                ?>
                    <tr>
                        <td><?php echo $v->order->order_no?></td>
                        <td><?php echo $v->last_time?></td>
                        <td><?php echo $point_types[$v->balance_of_payments]?></td>
                        <td><?php echo $point_froms[$v->from]?></td>
                        <td><?php echo $v->points?></td>
                    </tr>
                <?php 
                        }
                    }
                    else {
                ?>
                    <tr>
                        <td colspan="5" align="center">无 积分记录信息</td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
            <div class="r">
				<div class="page1">
					<?php $this -> widget('CLinkPager',array(
                        'pages'=>$pages,
                        'header'=>'共&nbsp;<strong class="orange">'.$pages -> getItemCount().'</strong>&nbsp;条&nbsp;',
                        'prevPageLabel' => '上一页',
                        'nextPageLabel'=>'下一页',
                        'maxButtonCount'=>8
                    ));?>
				</div>
			</div>
        </div>
    </div>
</div>
