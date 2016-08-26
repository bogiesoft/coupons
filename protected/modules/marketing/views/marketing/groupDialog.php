<div class="choose-coupon-pop">
    <!--选择分组弹出层-->
    <div class="m-danger-warning choose-coupon-warning" style="display: none"></div>
    <div class="m-pop-con">
        <form action="<?php echo Yii::app()->createUrl('marketing/marketing/GroupDialog') ?>" id="group_form" method="get">
        <div class="m-search mt30">
            <div class="form-inline">
                <div class="form-group">
                    <select class="form-control" name="type">
                        <option value="">全部类型</option>
                        <?php foreach ($group_type as $key => $v) { ?>
                            <option <?php echo isset($_GET['type']) && ($_GET['type'] == $key) ? 'selected="selected"' : '' ?> value="<?php echo $key ?>">
                                <?php echo $v ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group ml15">
                    <select class="form-control" name="status">
                        <option value="">全部状况</option>
                        <option value="1" <?php echo isset($_GET['status']) && ($_GET['status'] == 1) ? 'selected="selected"' : ''  ?>>已使用</option>
                        <option value="2" <?php echo isset($_GET['status']) && ($_GET['status'] == 2) ? 'selected="selected"' : ''  ?>>未使用</option>
                    </select>
                </div>
                <div class="input-group ml15">
                    <input type="text" name="name" class="form-control" placeholder="请输入分组名称……" value="<?php echo isset($_GET['name']) ? $_GET['name'] : '' ?>">
                    <span class="input-group-addon btn-search group_search"><i class="fa fa-search"></i></span>
                </div>
            </div>
        </div>
        <table class="table g-table g-table-center mt20">
            <thead>
            <tr>
                <th></th>
                <th>名称</th>
                <th>人数</th>
                <th>类型</th>
                <th>刷新时间</th>
                <th>使用状况</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($group_lists)) {
                foreach ($group_lists as $v) { ?>
                    <tr>
                        <td>
                            <input type="radio" name="user-group-radio" value="<?php echo $v['id'] ?>">
                        </td>
                        <td><?php echo $v['name'] ?></td>
                        <td><?php echo $v['num'] ?></td>
                        <td><?php echo $group_type[$v['type']] ?></td>
                        <td><?php echo $v['last_time'] ?></td>
                        <td><?php echo $v['use_num'] > 0 ? '已使用' : '未使用'  ?></td>
                    </tr>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="6">暂无数据</td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <div class="g-pager">
            <div class="fr">
                <ul id="yw0" class="yiiPager">
                    <?php $this->widget('CLinkPager', array(
                        'pages' => $pages,
                        'header' => '', //分页前显示的内容
                        'maxButtonCount' => 8, //显示分页数量
                        'firstPageCssClass' => '',
                        'lastPageCssClass' => '',
                        'firstPageLabel' => '首页',
                        'nextPageLabel' => '下一页',
                        'prevPageLabel' => '上一页',
                        'lastPageLabel' => '末页',
                    )); ?>
                </ul>
            </div>
        </div>
        <div class="form-inline m-form-group mt20 text-center">
            <button type="button" class="btn btn-primary m-btn" onclick="chooseGroup()">确定</button>
            <!--<button type="button" class="btn btn-default m-btn m-pop-cancel-btn">取消</button>-->
            <button type="button" class="btn btn-default m-btn m-pop-cancel-btn" onclick="closeWin()">取消</button>
        </div>
        </form>
    </div>
</div>

<script>
    $('.group_search').click(function () {
        $('#group_form').submit();
    });

    function closeWin() {
        window.parent.window.closeWinDialogGroup();
    }

    function chooseGroup() {
        var group_id = $('input[name=user-group-radio]:checked').val();
        if (group_id == undefined) {
            parent.window.parent.window.delayHide('m-danger-warning', '请选择分组', 1500);
            return false;
        }
        window.parent.window.closeWinDialogGroup(group_id);
    }

    $('select[name=type]').change(function () {
        $('#group_form').submit();
    });

    $('select[name=status]').change(function () {
        $('#group_form').submit();
    });

    parent.layer.iframeAuto(parent.layer.getFrameIndex(window.name)) //自适应iframe高度

</script>