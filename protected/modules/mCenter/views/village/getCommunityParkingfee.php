
<h4 class="g-title">停车费</h4>
<div class="g-container">
    <div class="set-cost-box">
    <div class="text-right">
        生效时间：<span class="text-orange">立即生效</span>
    </div>
    <table class="table g-table g-table-center mt20 g-table-fixed">
        <thead>
        <tr>
            <th width="20%"></th>
            <th width="20%">一个月</th>
            <th width="20%">半年</th>
            <th width="20%">一年</th>
            <th width="20%">操作</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>地上停车费</td>
            <td><?php echo $community_Parkingfee['over_day_price']?></td>
            <td><?php echo $community_Parkingfee['over_half_year_price']?></td>
            <td><?php echo $community_Parkingfee['over_year_price']?></td>
            <td>
                <div class="m-operate">
                    <button type="button" class="btn btn-primary btn-operate">操作</button>
                    <ul class="m-operate-list">
                        <li><a href="<?php echo $this->createUrl('CommunityParkingfee',array('community_id' => $community_id))?>">编辑</a></li>
                    </ul>
                </div>
            </td>
        </tr>
        <tr>
            <td>地下停车费</td>
            <td class="text-gray">不支持此方式</td>
            <td><?php echo $community_Parkingfee['under_half_year_price']?></td>
            <td><?php echo $community_Parkingfee['under_year_price']?></td>
            <td>
                <div class="m-operate">
                    <button type="button" class="btn btn-primary btn-operate">操作</button>
                    <ul class="m-operate-list">
                        <li><a href="<?php echo $this->createUrl('CommunityParkingfee',array('community_id' => $community_id))?>">编辑</a></li>
                    </ul>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
        </div>
</div>
