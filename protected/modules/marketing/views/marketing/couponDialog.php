<div class="choose-coupon-pop">
    <!--选择分组弹出层-->
    <div class="m-danger-warning choose-coupon-warning" style="display: none"></div>
    <div class="m-pop-con">
        <table class="table g-table g-table-center g-table-fixed mt20">
            <thead>
            <tr>
                <th width="5%"></th>
                <th class="text-left" width="30%">券名称</th>
                <th width="10%">券类型</th>
                <th width="15%">领券限制</th>
                <th width="10%">库存</th>
                <th class="text-left" width="30%">有效期</th>
            </tr>
            </thead>
            <tbody>

            <?php if (!empty($coupon_lists)) {
                foreach ($coupon_lists as $v) { ?>
                    <tr>
                        <td>
                            <input type="radio" name="choose-coupon-radio" value="<?php echo $v['id'] ?>"
                                   data-status="<?php echo $v['status'] ?>" data-name="<?php echo $v['title'] ?>"
                                   data-type="<?php echo $GLOBALS['COUPON_TYPE'][$v['type']] ?>">
                        </td>
                        <td class="text-left text-overflow">
                            <?php echo $v['title'] ?>（<?php echo $GLOBALS['WX_CHECK'][$v['status']] ?>）
                        </td>
                        <td><?php echo $GLOBALS['COUPON_TYPE'][$v['type']] ?></td>
                        <td>限领<?php echo $v['receive_num'] ?>张</td>
                        <td><?php echo $v['num'] - $v['get_num'] ?></td>
                        <td class="text-left">
                            <?php echo $v['time_type'] == VALID_TIME_TYPE_FIXED ? date('Y.m.d', strtotime($v['start_time'])) . '-' . date('Y.m.d', strtotime($v['end_time'])) : '领取后' . (($v['start_days'] == 0 ? '当天' : $v['start_days'] . '天') . '生效，有效' . $v['effective_days'] . '天') ?>
                        </td>
                    </tr>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="6">暂无数据</td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <div class="g-pager">
            <div class="fr">
                <ul id="yw0" class="yiiPager">
                    <?php $this->widget('CLinkPager', array(
                        'pages' => $pages,
                        'header' => '', //分页前显示的内容
                        'maxButtonCount' => 8, //显示分页数量
                        'firstPageCssClass' => '',
                        'lastPageCssClass' => '',
                        'firstPageLabel' => '首页',
                        'nextPageLabel' => '下一页',
                        'prevPageLabel' => '上一页',
                        'lastPageLabel' => '末页',
                    )); ?>
                </ul>
            </div>
        </div>
        <div class="form-inline m-form-group mt20 text-center">
            <button type="button" class="btn btn-primary m-btn" onclick="chooseCoupon()">确定</button>
            <button type="button" class="btn btn-default m-btn" onclick="closeWin()">取消</button>
        </div>
    </div>
</div>

<script>
    function closeWin() {
        window.parent.window.closeWinDialogCoupon();
    }

    function chooseCoupon() {
        var coupon_id = $('input[name=choose-coupon-radio]:checked').val();
        var coupon_name = $('input[name=choose-coupon-radio]:checked').attr('data-name');
        var coupon_type = $('input[name=choose-coupon-radio]:checked').attr('data-type');
        var coupon_status = $('input[name=choose-coupon-radio]:checked').attr('data-status');

        if (coupon_id == undefined) {
            parent.window.parent.window.delayHide('m-danger-warning', '请选择优惠券', 1500);
            return false;
        }
        window.parent.window.closeWinDialogCoupon(coupon_id, coupon_name, coupon_type, coupon_status);
    }

    $('input[name=choose-coupon-radio]').click(function () {
        var status = $(this).attr('data-status');
        if (status == <?php echo WX_CHECK_AUDIT ?>) {
            $('.choose-coupon-warning').html('当前优惠券状态为审核中,投放玩券电子券').show();
        } else if (status == <?php echo WX_CHECK_NOTPASS ?>) {
            $('.choose-coupon-warning').html('当前优惠券状态为审核未通过,投放玩券电子券').show();
        } else {
            $('.choose-coupon-warning').hide();
        }
        parent.layer.iframeAuto(parent.layer.getFrameIndex(window.name)) //自适应iframe高度
    });

    parent.layer.iframeAuto(parent.layer.getFrameIndex(window.name)) //自适应iframe高度

</script>