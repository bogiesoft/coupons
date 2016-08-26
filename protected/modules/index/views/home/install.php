<div class="backstage-l">
    <dl>
        <dt><a href="#" class="cur">支付宝设置</a></dt>
        <dd class="bg" style="display: block">
            <a id="106_01" href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/Install/AlipayAuth') ?>',this)" class="cur">支付宝授权</a>
            <a id="106_02" href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/Install/SetAlipay') ?>',this)">支付宝当面付设置</a>
            <a id="106_03" href="javascript:;" <?php if(Yii::app()->session['type_name'] == '收银版'){ ?>onclick="alert('当前版本没有该权限，请联系服务商进行版本升级！')"<?php }else{?>onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/Install/SetFuWuAlipay') ?>',this)"<?php }?>>支付宝服务窗设置</a>
        </dd>

        <dt><a href="#" class="cur">微信设置</a></dt>
        <dd>
            <!-- <a id="106_11" href="javascript:;" <?php //if(Yii::app()->session['type_name'] == '收银版'){ ?>onclick="alert('当前版本没有该权限，请联系服务商进行版本升级！')"<?php //}else{?>onclick="onLeft('<?php //echo Yii::app()->createUrl('mCenter/Install/AuthWechat') ?>',this)"<?php //}?>>微信公众号授权</a> -->
            <a id="106_04" href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/Install/SetWechatPay') ?>',this)">微信支付设置</a>
            <a id="106_05" href="javascript:;" <?php if(Yii::app()->session['type_name'] == '收银版'){ ?>onclick="alert('当前版本没有该权限，请联系服务商进行版本升级！')"<?php }else{?>onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/Install/SetWechat') ?>',this)"<?php }?>>微信公众号设置</a>
        </dd>
        <dt><a href="#" class="cur">密码管理</a></dt>
            <dd class="line">
                <a id="106_06" href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/Install/EditPassWord') ?>',this)">修改密码</a>
        </dd>
        <?php if(Yii::app() -> session['role'] == WQ_ROLE_MERCHANT){?>
            <dt><a href="#" class="cur">管理员管理</a></dt>
            <dd class="line">
                <a id="106_07" href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/Install/adminList') ?>',this)">管理员</a>
            </dd>
        <?php }?>
        <?php $tmp_type = Yii::app ()->session ['charge_type']; ?>
        <dt <?php echo $tmp_type != MERCHANT_CHARGE_TYPE_SELF_YES ? 'style="display:none;"' : ''; ?>><a href="javascript:;" class="cur" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/Install/StoreEdition') ?>',this)">玩券版本</a></dt>
        <dd class="line" <?php echo $tmp_type != MERCHANT_CHARGE_TYPE_SELF_YES ? 'style="display:none;"' : ''; ?>>
            <a id="106_08" href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/Install/StoreEdition') ?>',this)">玩券版本</a>
            <a id="106_09" href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/Install/StoreEditionRenewUpgrade') ?>',this)">续费升级</a>
            <a id="106_10" href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/Install/StoreOrderList') ?>',this)">购买记录</a>
        </dd>
    </dl>
</div>

<script>
    function leftSelect() {
        $("a").removeClass("cur");
        $("dd").hide();
        var id = parent.window.$("#left").attr("src").substr(-6);
        $("#" + id).addClass("cur").parent().show()
    }
    $(function(){leftSelect()});
</script>