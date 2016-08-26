<h4 class="g-title bb-none">
    <a href="" class="text-gray"><i class="fa fa-chevron-circle-left"></i></a>
    <span>积分兑换 / </span>
    <span>可兑换优惠券详情</span>
</h4>
<nav class="nav m-cp-nav">
    <span class="nav-link active">积分兑换</span>
    <span class="nav-link" style="display: none;">积分换券统计</span>
</nav>
<div class="g-container">
    <form action="" id="form" class="validate-inline-error">
        <div class="form-group m-cp-info">
            <div class="cp-title mt6">
                <span>选择券<em>*</em>：</span>
            </div>
            <div class="cp-con">
                <a style="display: none;" href="javascript:;" class="button-plus" id="choose-coupon" ><span></span></a>
                <a style="display: none;"  href="" class="pl20">新建优惠券</a>
                <p class="mt15"><input type="text" name="coupon" value="<?php echo $rs->coupon['title'] ?>" class="no-border" readonly><a href="javascript:;" class="pr10" style="display: none;">修改</a></p>
            </div>
        </div>
        <div class="form-group m-cp-info">
            <div class="cp-title">
                <span>券类型：</span>
            </div>
            <div class="cp-con">
                <span><?php echo $GLOBALS['COUPON_TYPE'][$rs->coupon['type']] ?></span>
            </div>
        </div>
        <div class="form-group m-cp-info">
            <div class="cp-title">
                <span>券领取限制：</span>
            </div>
            <div class="cp-con">
                <span><?php echo $rs->coupon['receive_num'] ?></span>
            </div>
        </div>
        <div class="form-group m-cp-info">
            <div class="cp-title">
                <span>券库存：</span>
            </div>
            <div class="cp-con">
                <span><?php echo $rs->coupon['num'] - $rs->coupon['get_num'] ?></span>
            </div>
        </div>
        <div class="form-group m-cp-info">
            <div class="cp-title">
                <span>券有效期：</span>
            </div>
            <div class="cp-con">
                <span><?php echo $rs->coupon['time_type'] == VALID_TIME_TYPE_FIXED ? date('Y.m.d', strtotime($rs->coupon['start_time'])) . '-' . date('Y.m.d', strtotime($rs->coupon['end_time'])) : '领取后' . (($rs->coupon['start_days'] == 0 ? '当天' : $rs->coupon['start_days'] . '天') . '生效，有效' . $rs->coupon['effective_days'] . '天') ?></span>
            </div>
        </div>
        <div class="form-group m-cp-info">
            <div class="cp-title mt6">
                <span>所需积分<em>*</em>：</span>
            </div>
            <div class="cp-con">
                <input type="text" class="form-control fc-sm" value="<?php echo $rs['needs_point'] ?>" name="grade" disabled readonly>
            </div>
        </div>
        <div class="form-group m-cp-info">
            <div class="cp-title mt6">
                <span>兑换限制：</span>
            </div>
            <div class="cp-con">
                <input type="text" class="form-control fc-sm" value="<?php echo $rs['exchange_limit'] ?>" name="limit" disabled readonly>
            </div>
        </div>
        <div class="form-group m-cp-info">
            <div class="cp-title mt6">
                <span>可兑换库存：</span>
            </div>
            <div class="cp-con">
                <input type="text" class="form-control fc-sm" value="<?php echo min($rs['stock'] - $rs['exchange_num'],$rs->coupon['num'] - $rs->coupon['get_num']) ?>" name="stock" disabled readonly>
            </div>
        </div>
        <div class="form-group m-cp-info">
            <div class="cp-title mt6">
                <span>活动时间<em>*</em>：</span>
            </div>
            <div class="cp-con">
                <div class="radio-inline form-inline mb10">
                    <label>
                        <input type="radio" <?php if ($rs['time_type'] == POINT_ACTIVITY_TIME_TYPE_PERMANENT) { echo 'checked="checked"'; } ?> disabled name="act-time">
                        长久有效
                    </label>
                </div>
                <div class="radio-inline form-inline ml60 mb10">
                    <label>
                        <input type="radio" <?php if ( $rs['time_type'] == POINT_ACTIVITY_TIME_TYPE_FIXED) { echo 'checked="checked"'; } ?> disabled name="act-time" id="act-time-2">
                        长期活动
                    </label>
                    <input type="text" name="time1" value="<?php if ($rs['time_type'] == POINT_ACTIVITY_TIME_TYPE_FIXED) { echo date('Y-m-d', strtotime($rs['start_time'])) . ' - ' . date('Y-m-d', strtotime($rs['end_time'])); } ?>" class="form-control fc-sm icon-time ml10" disabled readonly>
                </div>
                <p class="text-gray">积分换券活动将在优惠券到期后自动下架</p>
            </div>
        </div>
        <div class="form-inline m-form-group mt45 text-center">
            <?php if ($flag) { ?><a type="button" href="<?php echo $this->createUrl('integralExchange/integralExchangeAdd',array('id'=>$rs->id,'diff'=>'info')) ?>" class="btn btn-default m-btn">编辑</a><?php } else { ?><a type="button" href="javascript:;" disabled="" class="btn btn-default m-btn">编辑</a><?php } ?>
            <a type="button" href="<?php echo $this->createUrl('integralExchange/integralExchangeList') ?>" class="btn btn-default m-btn">返回</a>
        </div>
    </form>
</div>