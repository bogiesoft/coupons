
<h4 class="g-title">报修详情</h4>
<div class="g-container">
    <div class="m-estate-box">
        <div class="form-group">
            <div class="estate-title">
                <span>姓名：</span>
            </div>
            <div class="estate-con">
                <span><?php echo $repair_detail['name'];?></span>
            </div>
        </div>
        <div class="form-group">
            <div class="estate-title">
                <span>小区：</span>
            </div>
            <div class="estate-con">
                <span><?php echo $repair_detail['community_name'];?></span>
            </div>
        </div>
        <div class="form-group">
            <div class="estate-title">
                <span>手机号：</span>
            </div>
            <div class="estate-con">
                <span><?php echo $repair_detail['account'];?></span>
            </div>
        </div>
        <div class="form-group">
            <div class="estate-title">
                <span>日期：</span>
            </div>
            <div class="estate-con">
                <span><?php echo $repair_detail['repair_time'];?></span>
            </div>
        </div>
        <div class="form-group">
            <div class="estate-title">
                <span>地址：</span>
            </div>
            <div class="estate-con">
                <span><?php echo $repair_detail['address'];?></span>
            </div>
        </div>
        <div class="form-group">
            <div class="estate-title">
                <span>区域：</span>
            </div>
            <div class="estate-con">
                <span><?php echo $repair_detail['area_type']==REPORT_REPAIR_RECORD_TYPE_OUTDOOR ? '室外':'室内';?></span>
            </div>
        </div>
        <div class="form-group">
            <div class="estate-title">
                <span>图片：</span>
            </div>
            <div class="estate-con">
                <ul class="estate-gallery clearfix">
                    <?php if(!empty($repair_detail['img'][0])) {?>
                    <li><img src="<?php echo(IMG_BASE_PATH).'wy_images/source/'.$repair_detail['img'][0];?>" alt="estate-image"></li>
                    <?php }?>
                    <?php if(!empty($repair_detail['img'][1])) {?>
                        <li><img src="<?php echo(IMG_BASE_PATH).'wy_images/source/'.$repair_detail['img'][1];?>" alt="estate-image"></li>
                    <?php }?>
                    <?php if(!empty($repair_detail['img'][2])) {?>
                        <li><img src="<?php echo(IMG_BASE_PATH).'wy_images/source/'.$repair_detail['img'][2];?>" alt="estate-image"></li>
                    <?php }?>
                </ul>
            </div>
        </div>
        <div class="form-group">
            <div class="estate-title">
                <span>报修内容：</span>
            </div>
            <div class="estate-con">
                <span><?php echo $repair_detail['detail'];?></span>
            </div>
        </div>
        <?php if(!empty($repair_detail['remark'])){?>
            <div class="form-group">
                <div class="estate-title">
                    <span>反馈内容：</span>
                </div>
                <div class="estate-con">
                    <span><?php echo $repair_detail['remark'];?></span>
                </div>
            </div>
        <?php }?>
        <div class="form-inline m-form-group text-center mt45">
            <a href="<?php echo $this->createUrl('ReportRepairRecordList')?>" class="btn btn-default m-btn" >返回</a>
        </div>
    </div>
</div>