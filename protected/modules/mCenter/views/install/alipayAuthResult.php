<body>
    <div class="kkfm_r_inner">
        <div class="storeManage">
            <div class="top">
                <div class="name">
                    支付宝授权
                </div>
            </div>
            <div class="unauthorized authorized">
                <div class="unauthorizedTip">
                    <h3>支付宝授权：已授权</h3>
                    <p>时间：<time><?php echo $auth_time?></time></p>
                </div>
            </div>
            <div class="authoriz clearfix">
                <div class="goauthoriz">
                    <h3>完成支付宝授权，绑定支付宝账号</h3>
                    <p>授权绑定支付宝账号后，玩券能够帮助您完成口碑开店和当面付收款</p>
                    <a target="_blank" href="<?php echo Yii::app() -> createUrl('mCenter/Install/CreateAuthUrl');?>" class="authorize-btn"><span>支付宝账号已签约，马上授权</span></a>
                    <a href="#" onclick="showPop()" class="mob-authorize">手机授权</a>
                </div>
                <div class="permissions">
                    <dl>
                        <dt>授权管理以下权限：</dt>
                        <dd>发放、核销卡券</dd>
                        <dd>开发收银系统</dd>
                        <dd>配置管理服务窗</dd>
                        <dd>配置管理门店信息</dd>
                        <dd>代发服务窗信息</dd>
                        <dd>查询商户支付产品签约状态</dd>
                        <dd>发布管理折扣、代金券等优惠</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
    <div class="authorizPop" style="display: none">
        <div class="authorizPopCon">
            <img src="<?php echo Yii::app() -> createUrl('mCenter/Install/CreateAuthQrcode');?>">
            <p>使用收款账号登录支付宝客户端，扫描完成授权</p>
        </div>
        <div class="popClose"></div>
    </div>
</body>
<script type="text/javascript">
function showPop(){
	$('.authorizPop').show();
}

$('.popClose').click(function(){
	$('.authorizPop').hide();
});
</script>