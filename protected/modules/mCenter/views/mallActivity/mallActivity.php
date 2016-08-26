<body>
<div class="kkfm_r_inner">
    <!--搜索框 count-top包含的部分-->
    <div class="top">
        <div class="name">活动列表</div>
    </div>

    <div class="contant avtiveList">
        <div class="recharge">
            <table width="100%" cellspacing="0" cellpadding="0">
                <tr class="order-title">
                    <td>活动名称</td>
                    <td>活动状态</td>
                    <td>类型</td>
                    <td>剩余数量</td>
                    <td>总数量</td>
                    <td>活动时间</td>
                    <td>操作</td>
                </tr>
                <?php if (!empty($list)) {
                    foreach ($list as $k => $v) { ?>
                        <tr>
                            <td><?php echo $v->name ?></td>
                            <td><?php echo $GLOBALS['__DMALL_ACTIVITY_STATUS'][$v->status] ?></td>
                            <td><?php echo $GLOBALS['__DMALL_ACTIVITY_TYPE'][$v->type] ?></td>
                            <td><?php echo $v->coupon_num - $v->receive_num ?></td>
                            <td><?php echo $v->num ?></td>
                            <td><?php echo date('Y/m/d H:i', strtotime($v->start_time)) ?>
                                至 <?php echo date('Y/m/d H:i', strtotime($v->end_time)) ?></td>
                            <td>
                                <!--                             <a href="#" class="blue">编辑</a> - -->
                                <a href="<?php echo Yii::app()->createUrl('mCenter/MallActivity/DelDMallActivity', array('id' => $v->id)); ?>"
                                   class="blue">删除</a>
                            </td>
                        </tr>
                    <?php }
                } ?>

                <?php if (!empty($pages)) { ?>
                    <tr style="border:none">
                        <td style="border:none;text-align:right" colspan="7">
                            <?php $this->widget('CLinkPager', array(
                                'pages' => $pages,
                                'header' => '共&nbsp;<span class="yellow">' . $pages->getItemCount() . '</span>&nbsp;条&nbsp;',
                                'prevPageLabel' => '上一页',
                                'nextPageLabel' => '下一页',
                                'maxButtonCount' => 8
                            )); ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>

</body>

