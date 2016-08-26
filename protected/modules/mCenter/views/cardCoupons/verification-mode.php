<h4 class="g-title bb-none">
    <a href="" class="text-gray"><i class="fa fa-chevron-circle-left"></i></a>
    <span>优惠券核销管理 / </span>
    <span>核销方式</span>
</h4>
<nav class="nav m-cp-nav">
    <span class="nav-link" onclick="window.location.href='<?php echo Yii::app()->createUrl('mCenter/cardCoupons/CouponList') ?>'">优惠券管理</span>
    <span class="nav-link active" onclick="window.location.href='<?php echo Yii::app()->createUrl('mCenter/cardCoupons/verification') ?>'">优惠券核销</span>
    <span class="nav-link" onclick="window.location.href='<?php echo Yii::app()->createUrl('mCenter/cardCoupons/CouponCount') ?>'">优惠券统计</span>
</nav>
<nav class="nav m-cp-child-nav">
    <a class="nav-link active"
       href="<?php echo Yii::app()->createUrl('mCenter/CardCoupons/VerificationMode') ?>">核销方式</a>
    <a class="nav-link"
       href="<?php echo Yii::app()->createUrl('mCenter/CardCoupons/Verification') ?>">核销记录</a>
    <!--<a class="nav-link" href="#">核销员管理</a>-->
</nav>
<div class="g-container">
    <!--<div class="panel panel-default m-panel">
        <div class="panel-heading">
            <h3 class="panel-title">公众号核销</h3>
        </div>
        <div class="panel-body m-inline-box">
            <div class="m-inline-item first">
                <h5 class="title">1. 店员关注“玩券商户助手”公众号</h5>
                <p class="text-gray">店员须关注“玩券商户助手”公众号，才能配置核销权限</p>
                <img src="https://ooo.0o0.ooo/2016/06/12/575d204ce6e12.png" class="qrcode mt20" alt="qrcode">
                <div class="qrcode-tip text-gray">
                    <p>扫描二维码</p>
                    <p class="mt20">关注「玩券商户助手」</p>
                </div>
            </div>
            <div class="m-inline-item">
                <h5 class="title">2. 添加核销权限</h5>
                <p class="text-gray">管理员须在本页面为店员添加核销权限，添加了核销权限的核销员可以在“卡券商户助手”公众号内进行卡券核销、查看核销记录并接收买单、核销通知</p>
                <div class="form-inline text-center m-ver-btn-group mt45">
                    <button type="button" class="btn m-btn-lg btn-wq">
                        <i class="fa fa-plus-circle"></i>添加核销员
                    </button>
                    <button type="button" class="btn m-btn-lg btn-wq ml25">
                        <i class="fa fa-search-plus"></i>查看核销记录
                    </button>
                </div>
            </div>
        </div>
    </div>-->

    <div class="panel panel-default m-panel">
        <div class="panel-heading">
            <h3 class="panel-title">收银台核销</h3>
        </div>
        <div class="panel-body m-inline-box">
            <div class="m-inline-item first">
                <h5 class="title">1. 下载玩券收银端核销</h5>
                <p class="text-gray">玩券收银台支持多种收银方式，包括微信支付、支付宝支付，扫码支付、银联刷卡、现金支付和商圈集中支付等。</p>
                <a href="javascript:;"><img src="<?php echo WQ_STATIC_IMAGES ?>syt-dl.jpg" class="syt-dl-img mt20" alt="syt-dl-img"></a>
            </div>
            <div class="m-inline-item">
                <h5 class="title">2. 通过扫码枪扫描优惠券或者输入会员帐号</h5>
                <p class="text-gray">收银台还具有优惠券核销功能,下载安装收银台,商户通过扫码枪或者输入优惠券详情页的核销码，确认优惠信息，满足条件的优惠券将会被核销。</p>
                <div class="form-inline text-center m-ver-btn-group mt45">
                    <button type="button" class="btn m-btn-lg btn-wq down-soft">
                        <i class="fa fa-arrow-circle-down"></i>下载收银端
                    </button>
                    <!--<button type="button" class="btn m-btn-lg btn-wq ml25">
                        <i class="fa fa-question-circle"></i>使用帮助
                    </button>-->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('.down-soft').click(function () {
        window.location.href = 'http://upload.51wanquan.com/exe/wanquan_syt-1.0.3.zip';
    });
</script>