<body>
<h4 class="g-title bb-none">
    <a href="<?php echo $this->createUrl('integralExchange/integralExchangeList') ?>" class="text-gray"><i class="fa fa-chevron-circle-left"></i></a>
    <span>积分兑换 / </span>
    <span><?php if ($flag) { ?>添加<?php } else { ?>编辑<?php } ?>可兑换优惠券表</span>
</h4>
<nav class="nav m-cp-nav">
    <span id="exchange" class="nav-link active">积分兑换</span>
    <span id="statistics" class="nav-link" style="display: none;">积分换券统计</span>
</nav>
<div class="g-container">
    <form action="<?php echo $this->createUrl('integralExchange/integralExchangeAdd') ?>" id="form" class="validate-inline-error" method="post">
        <div class="form-group m-cp-info">
            <div class="cp-title">
                <span>选择券<em>*</em>：</span>
            </div>
            <div class="cp-con">
                <a href="#" <?php if (!$flag) { echo 'style="display:none"'; } ?> class="button-plus coupon-a" id="choose-coupon"><span></span></a>
                <a <?php if (!$flag) { echo 'style="display:none"'; } ?> href="<?php echo YII::app()->createUrl('mCenter/CardCoupons/CreateCoupon') ?>" class="pl20 coupon-a" target="_blank">新建优惠券</a>
                <p class="mt15"><input type="text" name="coupon" value="<?php echo $flag ?  '' : $rs->coupon['title'] ?>" class="no-border coupon-rs" placeholder="请选择优惠券" readonly><?php if ($flag) { ?><a id="modify" href="" class="pr10" style="display: none;">修改</a><?php } else { ?><a id="modify" href="" class="pr10" >修改</a><?php } ?></p>
            </div>
        </div>
        <input type="hidden" name="coupon-id" value="<?php echo $flag ? '' : $rs['coupon_id'] ?>">
        <div class="form-group m-cp-info add" <?php if ($flag) { echo 'style="display:none"'; } ?>>
            <div class="cp-title">
                <span>券类型：</span>
            </div>
            <div id="coupon-type" class="cp-con">
                <span><?php echo $flag ?  '未选择' : $GLOBALS['COUPON_TYPE'][$rs->coupon['type']] ?></span>
            </div>
        </div>
        <div class="form-group m-cp-info add" <?php if ($flag) { echo 'style="display:none"'; } ?>>
            <div class="cp-title">
                <span>券领取限制：</span>
            </div>
            <div  class="cp-con">
                <span id="coupon-limit"><?php echo $flag ?  '未选择' : $rs->coupon['receive_num'] ?></span>
            </div>
        </div>
        <div class="form-group m-cp-info add" <?php if ($flag) { echo 'style="display:none"'; } ?>>
            <div class="cp-title">
                <span>券库存：</span>
            </div>
            <div class="cp-con">
                <span id="coupon-num"><?php echo $flag ?  '未选择' : ($rs->coupon['num'] - $rs->coupon['get_num']) ?></span>
            </div>
        </div>
        <div class="form-group m-cp-info add" <?php if ($flag) { echo 'style="display:none"'; } ?>>
            <div class="cp-title">
                <span>券有效期：</span>
            </div>
            <div class="cp-con">
                <span id="coupon-time"><?php echo $flag ?  '未选择' : ($rs->coupon['time_type'] == VALID_TIME_TYPE_FIXED ? date('Y.m.d', strtotime($rs->coupon['start_time'])) . '-' . date('Y.m.d', strtotime($rs->coupon['end_time'])) : '领取后' . (($rs->coupon['start_days'] == 0 ? '当天' : $rs->coupon['start_days'] . '天') . '生效，有效' . $rs->coupon['effective_days'] . '天')) ?></span>
            </div>
        </div>
        <div class="form-group m-cp-info">
            <div class="cp-title mt6">
                <span>所需积分<em>*</em>：</span>
            </div>
            <div class="cp-con">
                <input type="text" class="form-control fc-sm" value="<?php echo $flag ?  '' : $rs['needs_point'] ?>" name="grade">
            </div>
        </div>
        <div class="form-group m-cp-info">
            <div class="cp-title mt6">
                <span>兑换限制：</span>
            </div>
            <div class="cp-con">
                <input type="text" class="form-control fc-sm" value="<?php echo $flag ?  '' : $rs['exchange_limit'] ?>" name="limit">
                <input type="hidden" name="coupon_limit" value="">
            </div>
        </div>
        <div class="form-group m-cp-info">
            <div class="cp-title mt6">
                <span>可兑换库存：</span>
            </div>
            <div class="cp-con">
                <input type="text" class="form-control fc-sm" value="<?php echo $flag ?  '' : min($rs['stock'] - $rs['exchange_num'],$rs->coupon['num'] - $rs->coupon['get_num']) ?>" name="stock">
                <input type="hidden" name="coupon_num" value="">
            </div>
        </div>
        <div class="form-group m-cp-info">
            <div class="cp-title mt6">
                <span>活动时间<em>*</em>：</span>
            </div>
            <div class="cp-con">
                <div class="radio-inline form-inline mb10">
                    <label>
                        <input id="act-time-1" type="radio" <?php if ($flag || (!$flag && ($rs['time_type'] == POINT_ACTIVITY_TIME_TYPE_PERMANENT))) { echo 'checked="checked"'; } ?> name="act-time" value="<?php echo POINT_ACTIVITY_TIME_TYPE_PERMANENT?>">
                        长久有效
                    </label>
                </div>
                <div class="radio-inline form-inline ml60 mb10">
                    <label>
                        <input type="radio" <?php if ( !$flag && ($rs['time_type'] == POINT_ACTIVITY_TIME_TYPE_FIXED)) { echo 'checked="checked"'; } ?> name="act-time" id="act-time-2" value="<?php echo POINT_ACTIVITY_TIME_TYPE_FIXED?>">
                        固定时间
                    </label>
                    <input type="text" value="<?php if ( !$flag && ($rs['time_type'] == POINT_ACTIVITY_TIME_TYPE_FIXED)) { echo date('Y-m-d', strtotime($rs['start_time'])) . ' - ' . date('Y-m-d', strtotime($rs['end_time'])); } ?>" name="time1" class="form-control fc-sm icon-time ml10">
                </div>
                <p class="text-gray">积分换券活动将在优惠券到期后自动下架</p>
            </div>
        </div>
        <div class="form-inline m-form-group mt45 text-center">
            <input type="hidden" name="flag" value="<?php echo $flag ?>">
            <input type="hidden" name="pa_id" value="<?php echo $flag ? '' : $rs['id'] ?>">
            <button type="submit" class="btn btn-primary m-btn">保存</button>
            <a type="button" href="<?php if (isset($prev) && !empty($prev)) { echo $this->createUrl('integralExchange/integralExchangeInfo',array('id'=>$rs->id)); } else { echo $this->createUrl('integralExchange/integralExchangeList'); } ?>" class="btn btn-default m-btn">取消</a>
        </div>
    </form>
</div>
</body>
<script>
    $(function () {
        $("#exchange").click(function () {
            window.location.href = '<?php echo $this->createUrl('integralExchange/integralExchangeList') ?>';
        });
        $("#statistics").click(function () {
            window.location.href = '<?php echo $this->createUrl('integralExchange/integralExchangeStatistics') ?>';
        });


        $('input[name="time1"]').daterangepicker({
            timePicker: false,
            format: 'YYYY/MM/DD',
            dateLimit: true,
            minDate : moment(),
            opens:'top'

//            maxDate: moment(),
//            minDate: moment().subtract(3, 'month') // 可选择的跨度为三个月
        });

        $("#choose-coupon").click(function () {
            layer.open({
                type: 2,
                title: '选择优惠券',
                shadeClose: true,
                shade: 0.8,
                offset: '20px',
                area: ['770px', '600px'],
                content: '<?php echo $this->createUrl('integralExchange/integralExchangeDialog') ?>' //iframe的url
            });
            return false;
        });

        $("#modify").click(function () {
            layer.open({
                type: 2,
                title: '修改优惠券',
                shadeClose: true,
                shade: 0.8,
                offset: '20px',
                area: ['770px', '600px'],
                content: '<?php echo $this->createUrl('integralExchange/integralExchangeDialog') ?>' //iframe的url
            });
            return false;
        });

        $("#form").validate({
            debug: false,
            rules: {
                coupon: {
                    required: true
                },
                grade: {
                    required: true,
                    digits: true,
                    checkGrade:true
                },
                limit:{
                    digits: true,
                    checkLimit:true,
                },
                stock:{
                    digits: true,
                    checkStock:true
                },
                time1: {
                    required: "#act-time-2:checked"
                },
            },
            messages: {
                'coupon':{
                    required: '请选择优惠券'
                },
                'grade': {
                    required: '积分不能为空',
                    digits: "积分不能为空且必须为大于0的整数",
                },
                'limit': {
                    digits: "只能输入大于0的整数"
                },
                'stock': {
                    digits: "只能输入大于0的整数"
                },
                'time1': {
                    required: '活动时间不能为空'
                }
            },
            errorPlacement: function (error, element) {
                error.appendTo(element.parent());
            }
        });

        $.validator.addMethod("checkLimit",function (value,element) {
            var limit = parseInt($('#coupon-limit').html()); // 需要传入值
            return this.optional(element) || (value<=limit && value>0);
        },'兑换限制必须大于0且小于等于券领取限制');

        $.validator.addMethod("checkGrade",function (value,element) {
            var limit = 10000000; // 需要传入值
            return this.optional(element) || (value<=limit && value>0);
        },'所需积分必须大于0且不能超过10000000');

        $.validator.addMethod("checkStock",function (value,element) {
            var stock = parseInt($('#coupon-num').html()); // 需要传入值
            return this.optional(element) || (value>0 && value<=stock);
        },'可兑换库存必须大于0且小于等于券库存');
    });

    function closeWinDialogCoupon(coupon_id, coupon_name, coupon_type, coupon_limit, coupon_num, coupon_time) {
        layer.closeAll();
        if (coupon_id != undefined) {
            $('.coupon-rs').val(coupon_name);
            $('#coupon-type span').html(coupon_type);
            $('#coupon-limit').html(coupon_limit);
            $('#coupon-num').html(coupon_num);
            $('#coupon-time').html(coupon_time);
            $("input[name='coupon-id']").val(coupon_id);
            $("input[name='limit']").val(coupon_limit);
            $("input[name='stock']").val(coupon_num);
            $("input[name='coupon_limit']").val(coupon_limit);
            $("input[name='coupon_num']").val(coupon_num);
            $("#modify").show();
            $(".coupon-a").hide();
            $(".add").show();
        }
    }
</script>