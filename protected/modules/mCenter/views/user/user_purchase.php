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
                    <li onClick="window.self.location.href='<?php echo Yii::app()->createUrl('mCenter/user/userInfo', array('id'=>$_GET['id'])) ?>'">用户信息</li>
                    <li onClick="window.self.location.href='<?php echo Yii::app()->createUrl('mCenter/user/userExpenses', array('id'=>$_GET['id'])) ?>'">消费记录</li>
                    <li onClick="window.self.location.href='<?php echo Yii::app()->createUrl('mCenter/user/userPoints', array('id'=>$_GET['id'])) ?>'">积分记录</li>
                    <li onClick="window.self.location.href='<?php echo Yii::app()->createUrl('mCenter/user/userStored', array('id'=>$_GET['id']))?>'">储值记录</li>
                    <li onClick="window.self.location.href='<?php echo Yii::app()->createUrl('mCenter/user/userPurchase', array('id'=>$_GET['id']))?>'" class="active">商城购买记录</li>
                </ul>
            </div>
            <div class="user_detail">
                <ul class="m_record">
                    <li>
                        <p>购买次数<i class="record_icon record_icon_shopcart"></i>
                        </p>
                        <p class="number">
                            <?php echo count($user_shop_orders)?>
                        </p>
                    </li>
                    <li>
                        <p>消费总金额<i class="record_icon record_icon_money"></i>
                        </p>
                        <p class="number">
                        <?php 
                            $total_purchase_money = 0.00;
                            if(!empty($user_shop_orders))
                            {
                                foreach($user_shop_orders as $order)
                                {
                                    $total_purchase_money += $order->order_paymoney;
                                }
                            }
                            echo $total_purchase_money;
                        ?>
                        </p>
                    </li>
                </ul>
                <div class="order-list">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <thead>
                            <tr class="orderHead">
                                <td>商品</td>
                                <td>数量/单价</td>
                                <td>售后</td>
                                <td>买家</td>
                                <td class="order-time">下单时间<i class="sort-icon"></i></td>
                                <td>订单状态</td>
                                <td>实付金额</td>
                            </tr>
                            <tr>
                                <td class="bottom" colspan="7"></td>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <tr class="list-title">
                                <td colspan="6">
                                    <span>订单编号：1234567898722151512</span>
                                    <span>支付宝支付</span>
                                    <span class=" clearfix">支付宝交易号：1236547898745632</span>
                                </td>
                                <td align="right">
                                    <a href="">订单详情</a>
                                </td>
                            </tr>
                            <tr class="orderCon">
                                <td valign="top" width="388">
                                    <a href="#">
                                        <div class="list-img"><img src="http://ooo.0o0.ooo/2016/01/23/56a32340b779f.png" /></div>
                                        <div class="list-name">台湾正品 张君雅甜甜圈 巧克力甜甜圈 张君雅小妹妹系列</div>
                                        <div class="list-info">s</div>
                                    </a>
                                </td>
                                <td class="border">100.00<span>*3</span></td>
                                <td class="border" width="90" rowspan="2">用户提交维权</td>
                                <td class="border" rowspan="2">卖家名称<span>18963259856</span></td>
                                <td class="border" rowspan="2">2015.08.09<span>19:25:20</span></td>
                                <td class="border" rowspan="2">等待买家收货<span></span></td>
                                <td rowspan="2">600<span>(含运费6.00)</span></td>
                                <tr class="orderCon">
                                    <td valign="top" width="388">
                                        <a href="#">
                                            <div class="list-img"><img src="http://ooo.0o0.ooo/2016/01/23/56a32340b779f.png" /></div>
                                            <div class="list-name">台湾正品 张君雅甜甜圈 巧克力甜甜圈 张君雅小妹妹系列</div>
                                            <div class="list-info">s</div>
                                        </a>
                                    </td>
                                    <td class="border">100.00<span>*3</span></td>
                                </tr>
                            </tr>
                            <tr>
                                <td class="remarks" colspan="7">卖家备注：收款机富力水街坊</td>
                            </tr>
                            <tr>
                                <td class="bottom" colspan="7"></td>
                            </tr>
                        </tbody>
                        
                        
                        
                        
                        <?php 
                            if(!empty($user_shop_orders))
                            {
                                foreach($user_shop_orders as $order)
                                {
                                    $order_items = $order->order_sku;
                                    $order_item_count = count($order_items);
                        ?>
                        <tbody>
                            <tr class="list-title">
                                <td colspan="6">
                                    <span>订单编号：<?php echo $order->order_no?></span>
                                    <span><?php echo $terminal_types[$order->terminal_type]?>支付</span>
                                    <span class=" clearfix">支付宝交易号：1236547898745632</span>
                                </td>
                                <td align="right">
                                    <a href="">订单详情</a>
                                </td>
                            </tr>
                            <tr class="orderCon">
                                <td>
                                <?php 
                                    foreach($order_items as $v)
                                    {
                                ?>
                                <td valign="top" width="388">
                                    <a href="#">
                                        <div class="list-img"><img src="http://ooo.0o0.ooo/2016/01/23/56a32340b779f.png" /></div>
                                        <div class="list-name"><?php echo $v->product_name?></div>
                                        <div class="list-info">简介。。。</div>
                                    </a>
                                </td>
                                <td class="border"><?php echo $v->price?><span>*<?php echo $v->num?></span></td>
                                <?php 
                                    }
                                ?>
                                <td>
                                <?php ?>
                                <td class="border" width="90" rowspan="<?php echo $order_item_count?>">用户提交维权</td>
                                <td class="border" rowspan="<?php echo $order_item_count?>">卖家名称<span>18963259856</span></td>
                                <td class="border" rowspan="<?php echo $order_item_count?>"><?php echo date('Y-m-d', strtotime($order->create_time))?><span><?php echo date('H:i:s', strtotime($order->create_time))?></span></td>
                                <td class="border" rowspan="<?php echo $order_item_count?>">等待买家收货<span></span></td>
                                <td rowspan="<?php echo $order_item_count?>"><?php echo $order->order_paymoney?><span>(含运费<?php echo $order->freight_money?$order->freight_money:'0.00'?>)</span></td>
                                <?php ?>
                            </tr>
                            <tr>
                                <td class="remarks" colspan="7"><p>收货地址：<?php echo $order->address?>&nbsp;&nbsp;卖家备注：<?php echo $order->remark?></p></td>
                            </tr>
                            <tr>
                                <td class="bottom" colspan="7"></td>
                            </tr>
                        </tbody>
                        <?php
                                }
                            }
                        ?>
                        <!--
                        <tbody>
                            <tr class="list-title">
                                <td colspan="6">
                                    <span>订单编号：1234567898722151512</span>
                                    <span>支付宝支付</span>
                                    <span class=" clearfix">支付宝交易号：1236547898745632</span>
                                </td>
                                <td align="right">
                                    <a href="">订单详情</a>
                                </td>
                            </tr>
                            <tr class="orderCon">
                                <td valign="top" width="388">
                                    <a href="#">
                                        <div class="list-img"><img src="http://ooo.0o0.ooo/2016/01/23/56a32340b779f.png" /></div>
                                        <div class="list-name">台湾正品 张君雅甜甜圈 巧克力甜甜圈 张君雅小妹妹系列</div>
                                        <div class="list-info">s</div>
                                    </a>
                                </td>
                                <td class="border">100.00<span>*3</span></td>
                                <td class="border" width="90" rowspan="2">用户提交维权</td>
                                <td class="border" rowspan="2">卖家名称<span>18963259856</span></td>
                                <td class="border" rowspan="2">2015.08.09<span>19:25:20</span></td>
                                <td class="border" rowspan="2">等待买家收货<span></span></td>
                                <td rowspan="2">600<span>(含运费6.00)</span></td>
                                <tr class="orderCon">
                                    <td valign="top" width="388">
                                        <a href="#">
                                            <div class="list-img"><img src="http://ooo.0o0.ooo/2016/01/23/56a32340b779f.png" /></div>
                                            <div class="list-name">台湾正品 张君雅甜甜圈 巧克力甜甜圈 张君雅小妹妹系列</div>
                                            <div class="list-info">s</div>
                                        </a>
                                    </td>
                                    <td class="border">100.00<span>*3</span></td>
                                </tr>
                            </tr>
                            <tr>
                                <td class="remarks" colspan="7">卖家备注：收款机富力水街坊</td>
                            </tr>
                            <tr>
                                <td class="bottom" colspan="7"></td>
                            </tr>
                        </tbody>
                        -->
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>