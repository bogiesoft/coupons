<div class="backstage-l">
    <dl>
        <dt><a href="#" class="cur">门店管理</a></dt>
        <dd class="bg" style="display: block">
            <!-- <a href="javascript:;" onclick="onLeft('<?php //echo Yii::app()->createUrl('mCenter/store/store') ?>',this)">门店列表</a> -->
            <a id="100_01" href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/store/storeInfo') ?>',this)" class="cur">门店管理</a>
            <?php if(Yii::app ()->session ['role']==1) {?>
                <a id="100_02" href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/store/storeGroupList') ?>',this)">门店分组</a>
            <?php }?>
            <a id="100_03" href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/store/addStore') ?>',this)">添加门店</a>
        </dd>

        <dt><a href="#">操作员管理</a></dt>
        <dd>
            <a id="100_04" href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/operator/addOperator') ?>',this)">添加操作员</a>
            <a id="100_05" href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/operator/operator') ?>',this)">操作员列表</a>
            <a id="100_06" href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/operator/operatorSet') ?>',this)">操作员设置</a>
        </dd>

        <!--                  <dt><a href="#">交易管理</a></dt> -->
        <!--                     <dd class="line"> -->
        <!--                  <a href="javascript:;" onclick="onLeft('<?php //echo Yii::app()->createUrl('mCenter/store/orderList') ?>',this)">交易明细</a>-->
        <!--                     </dd> -->
        <dt><a href="#">收银台管理</a></dt>
        <dd class="line">
            <a id="100_07" href="http://syt.51wanquan.com" target="_blank">登录Web收银台</a>
            <a id="100_08" href="http://upload.51wanquan.com/exe/wanquan_syt-1.0.3.zip">下载收银台</a>
            <a id="100_09" href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/store/SetActivationCode') ?>',this)">激活码设置</a>
            <a id="100_10" href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/store/tradeNoticeList') ?>',this)">收款通知</a>
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