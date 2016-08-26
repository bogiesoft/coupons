
<h4 class="g-title">物业费</h4>
<div class="g-container">
    <div class="set-cost-box">
    <div class="text-right">
        生效时间：<span class="text-orange">立即生效</span>
    </div>
    <table class="table g-table g-table-center mt20">
        <thead>
        <tr>
            <th></th>
            <th>一年</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>物业费</td>
            <td><?php echo !empty($community_Propertyfee['year_price']) ? $community_Propertyfee['year_price']."/月.平方米" : ''?></td>
            <td>
                <div class="m-operate">
                    <button type="button" class="btn btn-primary btn-operate">操作</button>
                    <ul class="m-operate-list">
                        <li><a href="<?php echo $this->createUrl('CommunityPropertyfee',array('community_id' => $community_id))?>">编辑</a></li>
                    </ul>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
    </div>
</div>
