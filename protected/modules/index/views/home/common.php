<div class="backstage-l">
    <dl>
        <dt><a href="#">储值管理</a></dt>
        <dd style="display: block">
            <a id="102_01" href="javascript:;"
               onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/stored/storedList') ?>',this)"
               class="cur">储值活动列表</a>
            <a id="102_02" href="javascript:;"
               onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/stored/storedOrder') ?>',this)">储值明细列表</a>
        </dd>

        <dt><a href="#">营销工具管理</a></dt>
        <dd>
            <a id="102_03" href="javascript:;" class="navCouponList"
               onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/cardCoupons/CouponList') ?>',this)">优惠券</a>
        </dd>

        <dt><a href="#">营销活动管理</a></dt>
        <dd>
            <a id="102_04" href="javascript:;"
               onclick="onLeft('<?php echo Yii::app()->createUrl('marketing/marketing/marketingList') ?>',this)">活动列表</a>
            <a id="102_05" href="javascript:;"
               onclick="onLeft('<?php echo Yii::app()->createUrl('marketing/marketing/createActivity') ?>',this)">基础营销</a>
            <!--<a href="javascript:;"
               onclick="onLeft('<?php /*echo Yii::app()->createUrl('mCenter/marketing/marketing/createActivity') */?>',this)">互动营销</a>-->
        </dd>
        <!--                     <dt><a href="#">应用管理</a></dt> -->
        <!--                     <dd> -->
        <!--       	<a href="javascript:;" onclick="onLeft('<?php //echo Yii::app()->createUrl('mCenter/promotions/turntableList') ?>',this)">欢乐大转盘</a>-->
        <!--     	<a href="javascript:;" onclick="onLeft('<?php //echo Yii::app()->createUrl('mCenter/promotions/scratchList') ?>',this)">刮刮卡</a>-->
        <!--                     </dd> -->
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