
<h4 class="g-title">小区管理</h4>
<div class="g-container">
    <div class="text-right">
        <a href="<?php echo Yii::app()->createUrl('mCenter/Village/AddCommunity')?>" class="btn btn-primary m-btn">添加小区</a>
    </div>
    <div class="set-cost-box">
    <table class="table g-table g-table-center mt20">
        <thead>
        <tr>
            <th>小区</th>
            <th>联系方式</th>
            <th>地址</th>
            <th>费用查看</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($community_list)) { ?>
        <?php foreach ($community_list as $k => $v) { ?>
        <tr>
            <td><?php echo $v->name?></td>
            <td><?php echo $v->tel?></td>
            <td><?php echo $v->address?></td>
            <td>
                <a href="<?php echo $this->createUrl('GetCommunityParkingfee',array('community_id' => $v->id))?>" class="text-blue">停车费</a>
                <a href="<?php echo $this->createUrl('GetCommunityPropertyfee',array('community_id' => $v->id))?>" class="text-blue pl20">物业费</a>
            </td>
            <td>
                <div class="m-operate">
                    <button type="button" class="btn btn-primary btn-operate">操作</button>
                    <ul class="m-operate-list">
                        <li><a href="<?php echo Yii::app()->createUrl('mCenter/Village/EditCommunity', array('community_id' => $v->id)); ?>">编辑</a></li>
                        <li><a href="<?php echo Yii::app()->createUrl('mCenter/Village/DelCommunity', array('community_id' => $v->id)); ?>" onclick="return confirm('确定要删除吗？');">删除</a></li>
                        <li><a href="<?php echo Yii::app()->createUrl('mCenter/Village/CommunityEfee', array('community_id' => $v->id)); ?>">设置费用</a></li>
                    </ul>
                </div>
            </td>
        </tr>
            <?php } ?>
        <?php } ?>
        </tbody>
    </table>
        <?php if (empty($community_list)) { ?>
            <?php echo '<span class="none-data">暂无数据</span>'?>
        <?php } ?>
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
    </div>
</div>
