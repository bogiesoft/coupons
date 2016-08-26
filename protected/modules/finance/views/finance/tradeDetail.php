
<h4 class="g-title">交易详情</h4>
<div class="g-container">
    <div class="trder-order-status">
        <h4>
            <span class="fw">订单状态：</span>
            <span><?php echo isset($list['detail']) ? (($list['detail']['order_status'] == ORDER_STATUS_NORMAL) ? ($list['detail']['pay_status']==ORDER_STATUS_PAID?'已付款':'待支付'):$GLOBALS['ORDER_STATUS'][$list['detail']['order_status']]):'';?></span>
        </h4>
    </div>
    <div class="m-box trade-data mt20">
        <div class="trade-order-meta">
            <span>订单号：<?php echo isset($list['detail'])?$list['detail']['order_no']:''?></span>
            <span>商户名称：<?php echo isset($list['detail'])?$list['detail']['merchant_name']:''?></span>
            <span>门店名称：<?php echo isset($list['detail'])? (!empty($list['detail']['branch_name'])? $list['detail']['store_name'].'('.$list['detail']['branch_name'].')':$list['detail']['store_name']):''?></span>
            <span title="<?php echo isset($list['detail'])? (empty($list['detail']['operator_number'])? $list['detail']['operator_name'] : $list['detail']['operator_name'].'('.$list['detail']['operator_number'].')'):''?>">操作员：<?php echo isset($list['detail'])? (empty($list['detail']['operator_number'])? $list['detail']['operator_name'] : $list['detail']['operator_name'].'('.$list['detail']['operator_number'].')'):''?></span>
        </div>
        <div class="m-row trade-summary-data">
            <div class="m-col-md text-right">
                <h5 class="fw">订单金额</h5>
                <p class="text-orange amount"><?php echo sprintf('%.2f',isset($list['detail'])?$list['detail']['order_paymoney']:0)?></p>
                <?php if (isset($list['detail']) && $list['detail']['undiscount_paymoney']>0){?>
                <p>不打折金额：<?php echo sprintf('%.2f',$list['detail']['undiscount_paymoney'])?></p>
                <?php }?>
            </div>
            <?php $total_discount_money = isset($list['detail'])?$list['detail']['coupons_money']+$list['detail']['discount_money']+$list['detail']['merchant_discount_money']:0?>
            <div class="m-col-md text-right">
                <h5 class="fw">优惠金额</h5>
                <p class="text-orange amount">-<?php echo sprintf('%.2f',$total_discount_money)?></p>
            </div>
            <div class="m-col-md text-right">
                <h5 class="fw">退款金额</h5>
                <p class="text-orange amount">-<?php echo sprintf('%.2f',isset($list['detail'])?$list['detail']['refund_money']:0)?></p>
            </div>
            <div class="m-col-md text-right">
                <h5 class="fw">实收金额</h5>
                <p class="text-orange amount"><?php echo sprintf('%.2f',isset($list['detail']) && $list['detail']['order_status'] != ORDER_STATUS_REVOKE && $list['detail']['pay_status']!=ORDER_STATUS_UNPAID ? $list['detail']['order_paymoney']-$total_discount_money-$list['detail']['refund_money']:0)?></p>
            </div>
        </div>
    </div>
    <div class="trade-details-title mt20">
        <span class="fw pl20">支付详情</span>
        <?php if (isset($list['detail']) && !empty($list['detail']['user_account'])){?>
        <span>会员账号：<?php echo isset($list['detail'])? !empty($list['detail']['user_name'])? $list['detail']['user_account'].' ('.$list['detail']['user_name'].')':$list['detail']['user_account']:''?></span>
        <span><?php echo !empty($list['detail']['points'])? '本次积分：'.$list['detail']['points']:''?></span>
        <?php }?>
    </div>
    <div class="m-inline-box mt10 trade-pay-detail">
        <div class="m-inline-item first">
        <?php if (isset($list['detail'])){?>
            <ul>
                <?php if (!empty($list['detail']['pay_channel'])){?>
                <li>
                    <span class="trade-title">支付方式：</span>
                    <span>
                    <?php 
                        $pay_channel = $list['detail']['pay_channel'];
                        $pay_channel_str = sprintf('%.2f',$list['detail']['order_paymoney']).'('.$GLOBALS['ORDER_PAY_CHANNEL'][$list['detail']['pay_channel']].')';
                        //是否两种支付方式
                        if ($pay_channel != ORDER_PAY_CHANNEL_STORED && $list['detail']['stored_paymoney'] > 0){
                            if ($pay_channel == ORDER_PAY_CHANNEL_ALIPAY_SM || $pay_channel == ORDER_PAY_CHANNEL_ALIPAY_SM
                                || $pay_channel == ORDER_PAY_CHANNEL_WXPAY_SM || $pay_channel == ORDER_PAY_CHANNEL_WXPAY_TM){
                                //支付宝/微信 + 储值支付
                                $pay_channel_str = sprintf('%.2f',$list['detail']['online_paymoney']).'('.$GLOBALS['ORDER_PAY_CHANNEL'][$list['detail']['pay_channel']].')';
                                $pay_channel_str .= "<br>".sprintf('%.2f',$list['detail']['stored_paymoney']).'(储值支付)';
                            }else if ($pay_channel == ORDER_PAY_CHANNEL_UNIONPAY){
                                //银联 + 储值支付
                                $pay_channel_str = sprintf('%.2f',$list['detail']['unionpay_paymoney']).'('.$GLOBALS['ORDER_PAY_CHANNEL'][$list['detail']['pay_channel']].')';
                                $pay_channel_str .= "<br>".sprintf('%.2f',$list['detail']['stored_paymoney']).'(储值支付)';
                            }else if ($pay_channel == ORDER_PAY_CHANNEL_CASH){
                                //现金 + 储值支付
                                $pay_channel_str = sprintf('%.2f',$list['detail']['cash_paymoney']).'('.$GLOBALS['ORDER_PAY_CHANNEL'][$list['detail']['pay_channel']].')';
                                $pay_channel_str .= "<br>".sprintf('%.2f',$list['detail']['stored_paymoney']).'(储值支付)';
                            }
                        }
                        echo $pay_channel_str;
                    ?>
                    </span>
                </li>
                <?php }?>
                <?php if (!empty($list['detail']['alipay_account'])){?>
                <li>
                    <span class="trade-title">支付账号：</span>
                    <span><?php echo $list['detail']['alipay_account']?></span>
                </li>
                <?php }?>
                <?php if (!empty($list['detail']['trade_no'])){?>
                <li>
                    <span class="trade-title">流水号：</span>
                    <span><?php echo $list['detail']['trade_no']?></span>
                </li>
                <?php }?>
                <?php if (!empty($list['detail']['create_time'])){?>
                <li class="mt30">
                    <span class="trade-title">下单时间：</span>
                    <span><?php echo $list['detail']['create_time']?></span>
                </li>
                <?php }?>
                <?php if (!empty($list['detail']['pay_time'])){?>
                <li>
                    <span class="trade-title">支付时间：</span>
                    <span><?php echo $list['detail']['pay_time']?></span>
                </li>
                <?php }?>
            </ul>
            <?php }?>
        </div>
        <div class="m-inline-item">
            <ul>
                <li>
                    <span class="trade-title fw">优惠情况</span>
                </li>
                <?php if (isset($list['detail']) && $list['detail']['coupons_money']>0){?>
                <li>
                    <span class="trade-title">优惠券优惠：</span>
                    <span>-<?php echo sprintf('%.2f',$list['detail']['coupons_money'])?></span>
                </li>
                <?php }?>
                <?php if (isset($list['detail']) && $list['detail']['discount_money']>0){?>
                <li>
                    <span class="trade-title">会员折扣：</span>
                    <span>-<?php echo sprintf('%.2f',$list['detail']['discount_money'])?></span>
                </li>
                <?php }?>
                <?php if (isset($list['detail']) && $list['detail']['merchant_discount_money']>0){?>
                <li>
                    <span class="trade-title">商家优惠：</span>
                    <span>-<?php echo sprintf('%.2f',$list['detail']['merchant_discount_money'])?></span>
                </li>
                <?php }?>
                <?php if (isset($list['detail']) && $list['detail']['alipay_discount_money']>0){?>
                <li>
                    <span class="trade-title">支付宝优惠：</span>
                    <span><?php echo sprintf('%.2f',$list['detail']['alipay_discount_money'])?></span>
                </li>
                <?php }?>
                <?php if (isset($list['detail']) && $list['detail']['wxpay_discount_money']>0){?>
                <li>
                    <span class="trade-title">微信优惠：</span>
                    <span><?php echo sprintf('%.2f',$list['detail']['wxpay_discount_money'])?></span>
                </li>
                <?php }?>
            </ul>
        </div>
    </div>
    <?php if (!empty($list['list'])){?>
    <div class="trade-details-title mt20">
        <span class="fw pl20">退款信息</span>
        <span>退款笔数：<?php echo isset($list['list'])? count($list['list']):0?>笔</span>
        <span>退款金额：<?php echo sprintf('%.2f',isset($list['detail'])?$list['detail']['refund_money']:0)?></span>
    </div>
    <table class="table g-table g-table-left mt10">
        <thead>
        <tr>
            <th>退款时间</th>
            <th>退款操作员</th>
            <th>授权操作员</th>
            <th>退款金额</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list['list'] as $v){?>
        <tr>
            <td><?php echo $v['refund_time']?></td>
            <td><?php echo !empty($v['operator_name'])?$v['operator_name'].'('.$v['operator_number'].')':''?></td>
            <td><?php echo !empty($v['operator_admin_name'])?$v['operator_admin_name'].'('.$v['operator_admin_number'].')':''?></td>
            <td>-<?php echo sprintf('%.2f',$v['refund_money'])?></td>
        </tr>
        <?php }?>
        </tbody>
    </table>
        <?php if(!empty($pages)) {?>
            <div class="g-pager">
                <div class="fr">
                    <ul class="yiiPager">
                        <?php
                        $this->widget('CLinkPager', array(
                            'pages' => $pages,
                            'header' => '<li>共&nbsp;<strong class="orange">' . $pages->getItemCount() . '</strong>&nbsp;条</li>&nbsp;',
                            'prevPageLabel' => '上一页',
                            'nextPageLabel' => '下一页',
                            'maxButtonCount' => 8
                        ));
                        ?>
                    </ul>
                </div>
            </div>
        <?php }?>
        <?php }?>
</div>
