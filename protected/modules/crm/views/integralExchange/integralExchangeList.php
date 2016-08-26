<h4 class="g-title bb-none">积分兑换</h4>
<nav class="nav m-cp-nav">
    <span id="exchange" class="nav-link active">积分兑换</span>
<!--    <span id="statistics" class="nav-link">积分换券统计</span>-->
</nav>
<div class="g-container">
    <div class="cp-wrapper">
        <div class="m-search">
            <form id="search" action="<?php echo $this->createUrl('integralExchangeList') ?>" method="get">
                <div class="form-inline">
                        <div class="form-group">
                            <select class="form-control" id="time_status" name="time_status">
                                <option>全部活动状态</option>
                                <option <?php if ($time_status == POINT_ACTIVITY_TIME_NOT_START){ echo 'selected'; } ?> value="<?php echo POINT_ACTIVITY_TIME_NOT_START ?>">未开始</option>
                                <option <?php if ($time_status == POINT_ACTIVITY_TIME_STARTING){ echo 'selected'; } ?> value="<?php echo POINT_ACTIVITY_TIME_STARTING ?>">进行中</option>
                                <option <?php if ($time_status == POINT_ACTIVITY_END){ echo 'selected'; } ?> value="<?php echo POINT_ACTIVITY_END ?>">已结束</option>
                            </select>
                        </div>
                        <div class="form-group ml15" id="activity_status">
                            <select class="form-control" id="activity_status" name="activity_status">
                                <option>全部显示状态</option>
                                <option <?php if ($activity_status == POINT_ACTIVITY_STATUS_ON_SHELF){ echo 'selected'; } ?> value="<?php echo POINT_ACTIVITY_STATUS_ON_SHELF ?>">已上架</option>
                                <option <?php if ($activity_status == POINT_ACTIVITY_STATUS_OFF_SHELF){ echo 'selected'; } ?> value="<?php echo POINT_ACTIVITY_STATUS_OFF_SHELF ?>">已下架</option>
                            </select>
                        </div>
                        <div class="input-group ml15">
                                <input type="text" name="coupon_name" value="<?php echo isset($coupon_name) ? $coupon_name : '' ?>" class="form-control fc-sm" placeholder="请输入可换券名称">
                                <button type="submit" class="input-group-addon btn-search"><i class="fa fa-search"></i></button>
                        </div>
                    <div class="form-group fr">
                        <a href="<?php echo $this->createUrl('integralExchangeAdd') ?>" type="button" class="btn btn-primary">添加可兑换优惠券</a>
                    </div>
                </div>
            </form>
        </div>
        <table class="table g-table g-table-center mt20 g-table-fixed">
            <thead>
            <tr>
                <th width="10%">可换券类型</th>
                <th width="17%">可换券名称</th>
                <th width="10%">所需积分</th>
                <th width="10%">可兑换库存</th>
                <th width="11%">已兑换人/次</th>
                <th width="20%">活动时间</th>
                <th width="17%">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($list)) { ?>
            <?php if (!empty($list['normal'])) {?>
            <?php foreach ($list['normal'] as $v) { ?>
            <tr>
                <td><?php echo $GLOBALS['COUPON_TYPE'][$v['type']] ?></td>
                <td class="text-overflow" title=""><?php echo $v['title'] ?></td>
                <td><?php echo $v['needs_point'] ?></td>
                <td><?php echo $v['stock'] ?></td>
                <td><?php echo isset($v['pa_user_num']) ? $v['pa_user_num'] : 0 ?>/<?php echo isset($v['pa_num']) ? $v['pa_num'] : 0  ?></td>
                <?php if ($v['time_type'] == POINT_ACTIVITY_TIME_TYPE_FIXED) { ?>
                <td>
                    <?php echo date('Y.m.d',strtotime($v['start_time'])); ?>-<?php echo date('Y.m.d',strtotime($v['end_time'])); ?>
                </td>
                    <?php } else { ?>
                    <td>永久</td>
                <?php } ?>
                <td class="m-operate expire">

                    <?php if ($v['point_activity_flag']) { ?>
                        <?php if ($v['status'] == POINT_ACTIVITY_STATUS_ON_SHELF){ ?>
                            <a class="offshelf" data-id="<?php echo $v['id'] ?>" href="javascript:;">下架</a>
                        <?php } elseif ($v['status'] == POINT_ACTIVITY_STATUS_OFF_SHELF) { ?>
                            <a class="onshelf" data-id="<?php echo $v['id'] ?>" href="javascript:;">上架</a>
                        <?php } ?>
                    <?php } else { ?>
                        <span class="timeover" data-id="<?php echo $v['id'] ?>" style="cursor: pointer">已下架</span>
                    <?php } ?>
                    
                    <a href="<?php echo $this->createUrl('integralExchange/integralExchangeInfo',array('id'=>$v['id'])) ?>">详情</a>
                    <a href="<?php echo $this->createUrl('integralExchange/integralExchangeAdd',array('id'=>$v['id'])) ?>">编辑</a>
                    <a class="del" data-id="<?php echo $v['id'] ?>" href="javascript:;">删除</a>
                </td>
            </tr>
            <?php }} ?>
            <?php if (!empty($list['abnormal'])) {?>
                <?php foreach ($list['abnormal'] as $v) { ?>
                    <tr>
                        <td><?php echo $GLOBALS['COUPON_TYPE'][$v['type']] ?></td>
                        <td class="text-overflow" title=""><?php echo $v['title'] ?></td>
                        <td><?php echo $v['needs_point'] ?></td>
                        <td><?php echo $v['stock'] ?></td>
                        <td><?php echo isset($v['pa_user_num']) ? $v['pa_user_num'] : 0 ?>/<?php echo isset($v['pa_num']) ? $v['pa_num'] : 0  ?></td>
                        <?php if ($v['time_type'] == POINT_ACTIVITY_TIME_TYPE_FIXED) { ?>
                            <td>
                                <?php echo date('Y.m.d',strtotime($v['start_time'])); ?>-<?php echo date('Y.m.d',strtotime($v['end_time'])); ?>
                            </td>
                        <?php } else { ?>
                            <td>永久</td>
                        <?php } ?>
                        <td class="m-operate">

                            <?php if (!$v['point_activity_flag']) { ?>
                                <span class="timeover" data-id="<?php echo $v['id'] ?>" data-msg="优惠券已过期" style="cursor: pointer">已下架</span>
                            <?php } else { ?>
                                <?php if ($v['status'] == POINT_ACTIVITY_STATUS_ON_SHELF){ ?>
                                    <a class="offshelf" data-id="<?php echo $v['id'] ?>" href="javascript:;">下架</a>
                                <?php } elseif ($v['status'] == POINT_ACTIVITY_STATUS_OFF_SHELF) { ?>
                                    <a class="onshelf" data-id="<?php echo $v['id'] ?>" href="javascript:;">上架</a>
                                <?php } ?>
                            <?php } ?>


                            <a href="<?php echo $this->createUrl('integralExchange/integralExchangeInfo',array('id'=>$v['id'],'flag'=>(int)$v['point_activity_flag'])) ?>">详情</a>
                            <?php if ($v['point_activity_flag']) { ?>
                        <a href="<?php echo $this->createUrl('integralExchange/integralExchangeAdd',array('id'=>$v['id'])) ?>">编辑</a>
                        <?php } ?>
                            <a class="del" data-id="<?php echo $v['id'] ?>" href="javascript:;">删除</a>
                        </td>
                    </tr>
                <?php }}} else { ?>
                <tr>
                    <td colspan="7" height="100" style="vertical-align: middle">暂无相关数据</td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <div class="g-pager">
            <div class="fr">
                <ul id="yw0" class="yiiPager">
                    <?php
                    $this->widget('CLinkPager', array(
                        'pages' => $pages,
                        'header' => '<li>共<strong class="total-num">' . $pages->getItemCount() . '</strong>条</li>',
                        'prevPageLabel' => '上一页',
                        'nextPageLabel' => '下一页',
                        'maxButtonCount' => 8
                    ));
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<script>
    $(function () {
        // 删除时的提示
        $(".del").click(function () {
            var self = $(this);
            layer.confirm('确定要永久删除吗？', {
                title: "温馨提示",
                offset: 100,
                area: '400px',
                btn: ['确定', '取消'] //按钮
            }, function () {
                var id = self.data('id');
                $.ajax({
                    type:'POST',
                    url:'<?php echo $this->createUrl('integralExchange/IntegralExchangeDel') ?>',
                    dataType:'json',
                    data:'id='+id,
                    success:function (data) {
                        layer.closeAll('dialog');
                        location.href = "<?php echo Yii::app() -> request -> url ?>";
                        parent.window.layer.msg('删除成功',{time:1500,area:'200px'});
                    }

                });
            });
        });


        //下架

        $(document).on('click','.offshelf',function(){
            var self = $(this);
            var id = self.data('id');
            layer.confirm('下架后将不再显示给用户该换券活动，确定要下架吗？', {
                title: "温馨提示",
                offset: 100,
                area: '400px',
                btn: ['确定', '取消'] //按钮
            }, function () {

                $.ajax({
                    type:'GET',
                    url:'<?php echo $this->createUrl('integralExchange/OnOffShelf',array('status'=>POINT_ACTIVITY_STATUS_OFF_SHELF)) ?>',
                    dataType:'json',
                    data:'id='+id+'& status='+<?php echo POINT_ACTIVITY_STATUS_OFF_SHELF ?>,
                    success:function (data) {
                        layer.closeAll('dialog');
                        location.href = "<?php echo Yii::app() -> request -> url ?>";
                        parent.window.layer.msg('下架成功',{time:1500,area:'200px'});
                    }

                });
            });
        });

        //上架
        $(document).on('click','.onshelf',function(){
//            alert('123');
            var self = $(this);
            var id = self.attr('data-id');
            $.ajax({
                type:'GET',
                url:'<?php echo $this->createUrl('integralExchange/OnOffShelf',array('status'=>POINT_ACTIVITY_STATUS_ON_SHELF)) ?>',
                dataType:'json',
                data:'id='+id+'& status='+<?php echo POINT_ACTIVITY_STATUS_ON_SHELF ?>,
                success:function (data) {
                    layer.closeAll('dialog');
//                    self.html('下架');
//                    self.attr('class','offshelf');
                    location.href = "<?php echo Yii::app() -> request -> url ?>";
                    parent.window.layer.msg('上架成功',{time:1500,area:'200px'});
                }
            });
        });

        //已下架提示
        $(".timeover").hover(
            function () {
                var self = $(this);
                var msg = $(this).data('msg');
                var id = $(this).data('id');
                $.ajax({
                    type: 'get',
                    url: '<?php echo $this->createUrl('getExpiredReason') ?>',
                    dataType: 'json',
                    data: 'id=' + id,
                    success: function (data) {
                        if (data['reason'] == 1) {
                            //                        parent.window.layer.msg('优惠券已过期',{time:1500,area:'200px'});
                            layer.tips('优惠券已过期', self, {
                                tips: 4,
                                skin:'layer-class',
                            });
                        } else if (data['reason'] == 2) {
                            layer.tips('优惠券无库存', self, {
                                tips: 4,
                                skin:'layer-class',
                            });
//                            parent.window.layer.msg('优惠券无库存',{time:1500,area:'200px'});
                        } else if (data['reason'] == 3) {
                            layer.tips('活动时间已结束', self, {
                                tips: 4,
                                skin:'layer-class',
                            });
//                            parent.window.layer.msg('活动时间已结束',{time:1500,area:'200px'});
                        } else if (data['reason'] == 4) {
                            layer.tips('可兑换优惠券无库存', self, {
                                tips: 4,
                                skin:'layer-class',
                            });
//                            parent.window.layer.msg('可兑换优惠券无库存',{time:1500,area:'200px'});
                        }

                    }
                });

            },
            function () {
                setTimeout(function(){layer.closeAll();},500);
            }
        );

        $("#statistics").click(function () {
            window.location.href = '<?php echo $this->createUrl('integralExchange/integralExchangeStatistics') ?>';
        });
        $("#exchange").click(function () {
            window.location.href = '<?php echo $this->createUrl('integralExchange/integralExchangeList') ?>';
        });

        $("#time_status").change(function () {
            $('#search').submit();
        });

        $("#activity_status").change(function () {
            $('#search').submit();
        });
    })
</script>