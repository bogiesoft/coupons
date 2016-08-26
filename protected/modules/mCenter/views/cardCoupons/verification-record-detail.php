<h4 class="g-title bb-none">
    <a href="" class="text-gray"><i class="fa fa-chevron-circle-left"></i></a>
    <span>优惠券核销 / </span>
    <span>核销记录</span>
</h4>
<nav class="nav m-cp-nav">
    <span class="nav-link" onclick="window.location.href='<?php echo Yii::app()->createUrl('mCenter/cardCoupons/CouponList') ?>'">优惠券管理</span>
    <span class="nav-link active" onclick="window.location.href='<?php echo Yii::app()->createUrl('mCenter/cardCoupons/verification') ?>'">优惠券核销</span>
    <span class="nav-link" onclick="window.location.href='<?php echo Yii::app()->createUrl('mCenter/cardCoupons/CouponCount') ?>'">优惠券统计</span>
</nav>
<nav class="nav m-cp-child-nav">
    <a class="nav-link" href="<?php echo Yii::app()->createUrl('mCenter/CardCoupons/VerificationMode') ?>">核销方式</a>
    <a class="nav-link active" href="<?php echo Yii::app()->createUrl('mCenter/CardCoupons/Verification') ?>">核销记录</a>
    <!--<a class="nav-link" href="#">核销员管理</a>-->
</nav>
<div class="g-container">

    <!-- 下面是查看核销记录的页面 -->
    <div class="cp-wrapper">
        <div class="m-ver-detail">
            <div class="m-ver-detail-item">
                <span class="big-title">核销时间</span>
                <div class="m-ver-detail-con">
                    <span class="title">核销时间：</span>
                    <span class="content"><?php echo $data['validate_time'] ?></span>
                </div>
            </div>
            <div class="m-ver-detail-item">
                <span class="big-title">优惠券信息</span>
                <div class="m-ver-detail-con">
                    <span class="title">优惠券类型：</span>
                    <span class="content"><?php echo $GLOBALS['COUPON_TYPE'][$data['type']] ?></span>
                </div>
                <div class="m-ver-detail-con">
                    <span class="title">优惠券名称：</span>
                    <span class="content"><?php echo $data['title'] ?></span>
                </div>
                <div class="m-ver-detail-con">
                    <span class="title">券码：</span>
                    <span class="content"><?php echo $data['code'] ?></span>
                </div>
            </div>
            <div class="m-ver-detail-item">
                <span class="big-title">交易信息</span>
                <div class="m-ver-detail-con">
                    <span class="title">实收金额：</span>
                    <span class="content"><?php echo sprintf("%.2f", $data['order_paymoney'] - $data['coupons_money'] - $data['discount_money'] - $data['merchant_discount_money']) ?>元</span>
                </div>
                <div class="m-ver-detail-con">
                    <span class="title">订单金额：</span>
                    <span class="content"><?php echo sprintf("%.2f", $data['order_paymoney']) ?>元</span>
                </div>
                <div class="m-ver-detail-con">
                    <span class="title">积分：</span>
                    <span class="content"><?php echo $data['points'] ?></span>
                </div>
                <div class="m-ver-detail-con">
                    <span class="title">使用门店：</span>
                    <span class="content"><?php echo $data['store_name'] ?></span>
                </div>
                <div class="m-ver-detail-con">
                    <span class="title">核销员：</span>
                    <span class="content"><?php echo $data['operator_name'] ?></span>
                </div>
                <div class="m-ver-detail-con">
                    <span class="title">核销备注：</span>
                    <span class="content"><?php echo $data['remark'] ?></span>
                </div>
            </div>
            <div class="m-ver-detail-item">
                <span class="big-title">消费者信息</span>
                <div class="m-ver-detail-con">
                    <span class="title">微信昵称：</span>
                    <span class="content"><?php echo $data['wechat_nickname'] ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
