<body>
<h4 class="g-title">精准营销</h4>
<div class="g-container">
    <ul class="nav nav-inline nav-precise">
        <li class="nav-item text-blue">
            <i class="fa fa-check-circle-o"></i>
            <span>筛选目标客户</span>
        </li>
        <li class="nav-item nav-item-progress"></li>
        <li class="nav-item">
            <i class="fa fa-circle-o step2"><em>2</em></i>
            <span>发起营销</span>
        </li>
    </ul>
    <div class="btn-group btn-group-sm m-btn-group js-precise-btn-group">
        <button type="button" class="btn" onclick="window.location.href='<?php echo Yii::app()->createUrl('marketing/marketing/MarketingPrecision') ?>'">筛选客户群体</button>
        <!--<button type="button" class="btn active js-choose-group">-->
        <button type="button" class="btn active" onclick="choosedGroup()">
            <i class="fa fa-plus"></i>选择已有分组
        </button>
    </div>
    <ul class="precise-group-info mt15">
        <li class="group-name">
            <?php echo $group_info['name'] ?>
            <i class="fa fa-times precise-del-group-btn" onclick="choosedGroup()"></i>
        </li>
        <li class="group-data">
            <span>人数：<em><?php echo $group_info['num'] ?>人</em></span>
            <span>分组类型：<em><?php echo $GLOBALS['__GROUP_TYPES'][$group_info['type']] ?></em></span>
            <span>刷新时间：<em><?php echo $group_info['last_time'] ?></em></span>
        </li>
    </ul>
    <div class="precise-act-wrapper mt15">
        <h5 class="fw title">当前分组已在以下活动中使用：</h5>
        <div class="act-name-wrapper">
            <?php if (!empty($group_marketing)) {
                foreach ($group_marketing as $key => $v) { ?>
                    <a href="<?php echo Yii::app()->createUrl('marketing/marketing/MarketingPrecisionDetail', array('marketing_id' => $key)) ?>"><?php echo $v ?></a>
                <?php }
            } else {
                echo '暂无使用的活动';
            } ?>
        </div>
    </div>
    <div class="filtrate_group precise-marketing-wrapper mt15">
        <!-- 计算区域 -->
        <div class="new_group calc_area">
            <div class="new_group_item">
                <h3 class="mt6">总人数</h3>
                <div class="new_group_item_c">
                    <?php echo $ret_html_array['total_user'] ?>
                </div>
            </div>
            <div class="new_group_item">
                <h3 class="mt10">性别</h3>
                <div class="new_group_item_c">
                    <?php echo $ret_html_array['user_sex'] ?>
                </div>
            </div>
            <div class="new_group_item">
                <h3 class="mt10">年龄</h3>
                <div class="new_group_item_c">
                    <?php echo $ret_html_array['user_age'] ?>
                </div>
            </div>
            <div class="new_group_item">
                <h3 class="mt10">会员等级</h3>
                <div class="new_group_item_c">
                    <?php echo $ret_html_array['user_grade'] ?>
                </div>
            </div>
            <!--<div class="new_group_item">
                <h3>客户价值</h3>
                <div class="new_group_item_c">
                    <?php /*echo $ret_html_array['user_value'] */?>
                </div>
            </div>-->
        </div>
        <div class="form-inline m-form-group mt30 text-center">
            <button type="button" class="btn btn-primary m-btn" onclick="marketingPrecisionAdd()">发起营销</button>
            <button type="button" class="btn btn-default m-btn" onclick="window.location.href='<?php echo $this->createUrl('marketing/MarketingPrecision') ?>'">取消</button>
        </div>
    </div>
</div>

</body>

<script>
    function choosedGroup() {
        layer.open({
            type: 2,
            title: '选择分组',
            shadeClose: true,
            shade: 0.8,
            offset: '20px',
            area: ['770px', '600px'],
            content: '/index.php/marketing/Marketing/groupDialog' //iframe的url
        });
        return false;
    }

    //关闭弹出框
    function closeWinDialogGroup(group_id) {
        layer.closeAll();
        if (group_id != undefined) {
            window.location.href = '<?php echo Yii::app()->createUrl('marketing/marketing/chooseGroup') ?>?group_id=' + group_id;
        }
    }

    //发起营销
    function marketingPrecisionAdd() {
        var group_id = '<?php echo $group_info['id'] ?>';
        window.location.href = '<?php echo Yii::app()->createUrl('marketing/Marketing/MarketingPrecisionAdd') ?>?group_id=' + group_id;
    }
</script>