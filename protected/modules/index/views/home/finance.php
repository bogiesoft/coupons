<div class="backstage-l">
        <dl>
        <dt><a href="#">财务管理</a></dt>                   
            <dd style="display: block">
                <a id="101_01" href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('finance/finance/tradeList') ?>',this)" class="cur">交易明细</a>
                <a id="101_02" href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('finance/finance/storeStatisticsDay') ?>',this)">交易汇总</a>
                <a id="101_03" href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('finance/finance/storeStatistics') ?>',this)">门店交易</a>
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