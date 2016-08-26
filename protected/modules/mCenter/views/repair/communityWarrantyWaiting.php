
<h4 class="g-title">小区报修</h4>
<div class="g-container">
    <div class="m-search">
        <div class="form-inline">
            <form method="get" id="repairform">
            <div class="form-group">
                <select class="form-control" name="community_id">
                    <option name="community" value="">全部小区</option>
                        <?php if (!empty($community_list)) {?>
                        <?php foreach ($community_list as $k => $v) { var_dump($v);?>
                            <option name="community" <?php if(isset($_GET['community_id']) && $_GET['community_id'] == $v['id']) {?>selected<?php }?> value="<?php echo $v['id']?>"><?php echo $v['name'];?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group ml10">
                <input name="time" type="text" value="<?php echo isset($_GET['time']) ? $_GET['time'] : ''?>" class="form-control fc-m-sm" placeholder="选择日期范围">
            </div>
            <div class="input-group ml10">
                <input type="text" name="tel" value="<?php echo isset($_GET['tel']) ? $_GET['tel'] : '';?>" class="form-control fc-m-sm" placeholder="请输入手机号">
                <span onclick="subForm()" class="input-group-addon btn-search"><i class="fa fa-search"></i></span>
            </div>
                <div class="form-group excel-operate fr">
                    <button type="button" onclick="window.location.href = '<?php echo Yii::app()->createUrl('mCenter/repair/GetRepairExcel', array('status' => REPORT_REPAIR_RECORD_STATUS_WAITING));?>'">
                        EXCEL导出
                    </button>
                </div>
            </form>
        </div>
    </div>
    <ul class="nav nav-tabs m-nav-tabs mt20">
        <li class="nav-item">
            <a class="nav-link" href="<?php
            echo Yii::app()->createUrl('mCenter/Repair/ReportRepairRecordList');?>">全部</a>
            <!--激活状态增加active-->
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="<?php
            echo Yii::app()->createUrl('mCenter/Repair/CommunityWarrantyWaiting');?>">待报修</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php
            echo Yii::app()->createUrl('mCenter/Repair/communityWarrantyComplete');?>">已报修</a>
        </li>
    </ul>
    <div class="m-estate-box set-cost-box cost-manage-box">
        <table class="table g-table g-table-center mt20">
            <thead>
            <tr>
                <th>姓名</th>
                <th>小区</th>
                <th>手机号</th>
                <th>报修时间</th>
                <th>地址</th>
                <th>区域</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
            </thead>
            <?php if (!empty($repair)) { ?>
            <tbody>
            <?php foreach ($repair as $k => $v) { ?>
            <tr>
                <td><?php echo $v['name'];?></td>
                <td><?php echo $v['community_name'];?></td>
                <td><?php echo $v['account'];?></td>
                <td><?php echo $v['repair_time'];?></td>
                <td><?php echo $v['address'];?></td>
                <td><?php echo $v['area_type']==REPORT_REPAIR_RECORD_TYPE_OUTDOOR ? '室外':'室内';?></td>
                <td><?php echo $v['status']== REPORT_REPAIR_RECORD_STATUS_WAITING ? '待报修':'已报修';?></td>
                <td>
                    <div class="m-operate">
                        <button type="button" class="btn btn-primary btn-operate">操作</button>
                        <ul class="m-operate-list">
                            <?php if($v['status'] == REPORT_REPAIR_RECORD_STATUS_WAITING){?>
                                <li>
                                    <a href="<?php echo Yii::app()->createUrl('mCenter/Repair/RepairStatus', array('repair_id' => $v['id'], 'goUrl' => Yii::app()->request->url));?>">确认</a>
                                </li>
                            <?php }?>
                            <li><a href="<?php echo Yii::app()->createUrl('mCenter/Repair/ReportRepairRecordDetail', array('repair_id'=>$v['id']));?>">查看详情</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
            <?php } ?>
            <?php }?>
            </tbody>
        </table>
        <?php if (empty($repair)) { ?>
            <?php echo '<span class="none-data">暂无数据</span>'?>
        <?php } ?>
        <?php if(!empty($pages)) {?>
            <div class="g-pager">
                <div class="fr">
                    <ul class="yiiPager">
                        <?php
                        $this->widget('CLinkPager', array(
                            'pages' => $pages,
                            'header' => '<li>共&nbsp;<strong class="orange">' . $pages->getItemCount() . '</strong>&nbsp;条</li>&nbsp;',
                            'prevPageLabel' => '上一页',
                            'nextPageLabel' => '下一页',
                            'maxButtonCount' => 8
                        ));
                        ?>
                    </ul>
                </div>
            </div>
        <?php }?>
    </div>
</div>
<script>

    //设置时间控件
    //$(document).ready(statistics_obj.stat_init);
    $(document).ready(function() {

        $('input[name="time"]').daterangepicker({
            "format":'YYYY/MM/DD',
            "timePicker": false,
            "timePickerSeconds": false,
            "opens": "right"
        });
    });

</script>
<script>
    //提交表单
    function subForm()
    {
        repairform.submit();
    }
</script>