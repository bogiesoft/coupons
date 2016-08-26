
<h4 class="g-title">电费详情</h4>
<div class="g-container">
    <table class="table g-table g-table-center">
        <thead>
            <tr>
                <th>本月峰电量</th>
                <th>本月谷电量</th>
                <th>峰电价格（￥）</th>
                <th>谷电价格（￥）</th>
                <th>本月电费</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $fee_Order_List['peak'];?></td>
                <td><?php echo $fee_Order_List['valley'];?></td>
                <td><?php echo json_decode($community_detail['electricity_fee_set'])->peak_price;?></td>
                <td><?php echo json_decode($community_detail['electricity_fee_set'])->trough_price;?></td>
                <td><?php echo $fee_Order_List['order_money'];?></td>
            </tr>
        </tbody>
    </table>
    <div class="form-inline m-form-group text-center mt45">
        <a href="<?php echo $this->createUrl('EFeeOrderList')?>" class="btn btn-default m-btn" >返回</a>
    </div>
</div>
