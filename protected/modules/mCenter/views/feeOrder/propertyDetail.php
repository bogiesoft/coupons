
<h4 class="g-title">物业费详情</h4>
<div class="g-container">
    <div class="m-estate-box">
        <div class="form-group">
            <div class="estate-title">
                <span>业主：</span>
            </div>
            <div class="estate-con">
                <span><?php echo $property_Detail['name'];?></span>
            </div>
        </div>
        <div class="form-group">
            <div class="estate-title">
                <span>小区：</span>
            </div>
            <div class="estate-con">
                <span><?php echo $property_Detail['community_name'];?></span>
            </div>
        </div>
        <div class="form-group">
            <div class="estate-title">
                <span>手机号：</span>
            </div>
            <div class="estate-con">
                <span><?php echo $property_Detail['account'];?></span>
            </div>
        </div>
        <div class="form-group">
            <div class="estate-title">
                <span>年数：</span>
            </div>
            <div class="estate-con">
                <span><?php echo '一年';?></span>
            </div>
        </div>
        <div class="form-group">
            <div class="estate-title">
                <span>地址：</span>
            </div>
            <div class="estate-con">
                <span>
                    <?php echo $property_Detail['building_number'].'号楼',$property_Detail['room_number'];?>
                </span>
            </div>
        </div>
        <div class="form-group">
            <div class="estate-title">
                <span>物业费：</span>
            </div>
            <div class="estate-con">
                <span><?php echo $property_Detail['order_money'];?></span>
            </div>
        </div>
        <div class="form-inline m-form-group text-center mt45">
            <a href="<?php echo $this->createUrl('PropertyFeeOrderList')?>" class="btn btn-default m-btn" >返回</a>
        </div>
    </div>
</div>