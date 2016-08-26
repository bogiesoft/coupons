<div class="backstage-l">
    <dl>
        <dt><a href="#">用户管理</a></dt>
        <dd style="display: block">
            <a id="107_01" href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('crm/user/userLists') ?>',this)" class="cur">用户管理</a>
            <a id="107_02" href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('crm/userGrade/setUserGrade') ?>',this)">会员等级管理</a>
            <!-- <a href="javascript:;" onclick="onLeft('<?php //echo Yii::app()->createUrl('mCenter/user/integrationRule') ?>',this)">积分规则</a> -->
            <a id="107_03" href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('crm/userGroup/userGroupList') ?>',this)">用户分组管理</a>
        </dd>
        <!--
        <dt><a href="#">标签管理</a></dt>
        <dd>
            <a href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/label/labelList') ?>',this)">标签管理</a>
        </dd>
        -->
        <dt><a href="#">积分管理</a></dt>
        <dd>
            <a id="107_04" href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('crm/integral/integralSet') ?>',this)">积分管理</a>
        </dd>

        <dt><a id="107_05" href="#">会员认证设置</a></dt>
        <dd>
            <a id="107_06" href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('crm/userSet/authSettings') ?>',this)">会员认证设置</a>
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