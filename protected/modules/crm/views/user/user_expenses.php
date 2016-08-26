<script type="text/javascript">
$(document).ready(main_obj.list_init);
$(function(){
    var now = new Date();
    $('#pay_time').daterangepicker({
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
                <li onClick="window.self.location.href='<?php echo $this->createUrl('userExpenses', array('id'=>$_GET['id'])) ?>'" class="active">消费记录</li>
                <li onClick="window.self.location.href='<?php echo $this->createUrl('userPoints', array('id'=>$_GET['id'])) ?>'">积分记录</li>
                <li onClick="window.self.location.href='<?php echo $this->createUrl('userStored', array('id'=>$_GET['id']))?>'">储值记录</li>
                <!--<li onClick="window.self.location.href='<?php echo $this->createUrl('userPurchase', array('id'=>$_GET['id']))?>'">商城购买记录</li>-->
            </ul>
        </div>
        <?php 
            $total_money = 0;
            $total_num = 0;
        ?>
        <div class="user_detail">
            <ul class="m_record">
                <li>
                    <p>累计消费金额<i class="record_icon record_icon_money"></i>
                    </p>
                    <p class="number">
                    <?php 
                        echo $user_xf_total_money;
                    ?>
                    
                    </p>
                </li>
                <li>
                    <p>累计消费次数<i class="record_icon record_icon_times"></i>
                    </p>
                    <p class="number"><?php echo $user_xf_total_num?></p>
                </li>
                <li>
                    <p>初次消费时间<i class="record_icon record_icon_time"></i>
                    </p>
                    <p class="number"><?php echo $user_xf_first_time?></p>
                </li>
            </ul>
            <div class="wq_form_inline">
            <?php echo CHtml::beginForm(Yii::app()->createUrl($this->route), 'get', array('id' => 'search_form'))?>
            <input type="hidden" name="id" value="<?php echo $_GET['id']?>"/>
                <div class="wq_inline_group">
                    <input type="text" name="pay_time" id="pay_time" class="wq_form_control" placeholder="请选择时间段…" value="<?php echo isset($_GET['pay_time'])?$_GET['pay_time']:''?>">
                </div>
                <div class="wq_inline_group">
                    <select class="wq_form_control" name='store_id'>
                        <option value="0">请选择消费门店</option>
                        <?php 
                            if(!empty($stores))
                            {
                                foreach($stores as $store)
                                {
                        ?>
                        <option value="<?php echo $store->id?>" <?php if(isset($_GET['store_id']) && $_GET['store_id'] == $store->id) echo "selected";?>><?php echo empty($store->branch_name) ? $store->name : $store->name."-".$store->branch_name?></option>
                        <?php 
                                }
                            }
                        ?>
                    </select>
                </div>
                <div class="wq_inline_group">
                    <select class="wq_form_control" name="order_status">
                        <option value="0">请选择交易类型</option>
                        <option value="1" <?php if(isset($_GET['order_status']) && $_GET['order_status']==1){echo 'selected';}?>>退款</option>
                        <option value="2" <?php if(isset($_GET['order_status']) && $_GET['order_status']==2){echo 'selected';}?>>消费</option>
                        <!--
                        <?php 
                            if(!empty($order_types))
                            {
                                foreach($order_types as $k=>$v)
                                {
                        ?>
                        <option value="<?php echo $k?>" <?php if(isset($_GET['order_type']) && $k==$_GET['order_type']) echo "selected";?>><?php echo $v?></option>
                        <?php 
                                }
                            }
                        ?>
                        -->
                    </select>
                </div>
                <div class="wq_inline_group">
                    <select class="wq_form_control" name="pay_channel">
                        <option value="0">请选择支付方式</option>
                        <?php 
                            if(!empty($pay_channels))
                            {
                                foreach($pay_channels as $k=>$v)
                                {
                        ?>
                        <option value="<?php echo $k?>" <?php if(isset($_GET['pay_channel']) && $k==$_GET['pay_channel']) echo "selected";?>><?php echo $v?></option>
                        <?php 
                                }
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
                        <td>消费时间</td>
                        <td>消费门店</td>
                        <td>订单金额</td>
                        <td>实收金额</td>
                        <td>类型</td>
                        <td>支付方式</td>
                    </tr>
                </thead>
                <tbody>
                <!-- 
                    <tr>
                        <td>2016.5.11 12:00:00</td>
                        <td>711吉祥店</td>
                        <td>100</td>
                        <td>退款消费</td>
                        <td>支付宝</td>
                    </tr>
                -->
                <?php 
                    if(!empty($orders))
                    {
                        foreach ($orders as $order){
                ?>
                    <tr>
                        <td><?php echo $order->order_no;?></td>
                        <td><?php echo $order->create_time?></td>
                        <td><?php echo empty($order->store->branch_name) ? $order->store->name : $order->store->name."-".$order->store->branch_name?></td>
                        <td><?php echo $order->order_paymoney?></td>
                        <td><?php echo $order->order_paymoney - $order->coupons_money - $order->discount_money - $order->merchant_discount_money;?></td>
                        <td><?php if(in_array($order->order_status, $tk_types)) echo '退款'; else echo '消费'?></td>
                        <td><?php echo $pay_channels[$order->pay_channel]?></td>
                    </tr>
                <?php
                        }
                    }
                    else {
                ?>
                    <tr>
                        <td colspan="6" align="center">无订单信息</td>
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