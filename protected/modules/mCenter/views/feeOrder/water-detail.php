<h4 class="g-title">水费详情</h4>
<div class="g-container">
    <table class="table g-table g-table-center">
        <thead>
        <tr>
            <th>本月电量</th>
            <th>水费单价（￥）</th>
            <th>本月水费</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?php echo $fee_Order_List['water_ton'];?></td>
            <td><?php echo json_decode($community_detail['water_fee_set'])->price;?></td>
            <td><?php echo $fee_Order_List['order_money'];?></td>
        </tr>
        </tbody>
    </table>
    <div class="form-inline m-form-group text-center mt45">
        <a href="<?php echo $this->createUrl('WFeeOrderList')?>" class="btn btn-default m-btn" >返回</a>
    </div>
</div>