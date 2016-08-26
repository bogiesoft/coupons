
<h4 class="g-title">停车费详情</h4>
<div class="g-container">
    <div class="m-estate-box">
        <div class="form-group">
            <div class="estate-title">
                <span>业主：</span>
            </div>
            <div class="estate-con">
                <span><?php echo $parking_Detail['0']['name'];?></span>
            </div>
        </div>
        <div class="form-group">
            <div class="estate-title">
                <span>小区：</span>
            </div>
            <div class="estate-con">
                <span><?php echo $parking_Detail['0']['community_name'];?></span>
            </div>
        </div>
        <div class="form-group">
            <div class="estate-title">
                <span>手机号：</span>
            </div>
            <div class="estate-con">
                <span><?php echo $parking_Detail['0']['account'];?></span>
            </div>
        </div>
        <div class="form-group">
            <div class="estate-title">
                <span>月数：</span>
            </div>
            <div class="estate-con">
                <span><?php echo $parking_Detail['0']['parking_month_num'];?></span>
            </div>
        </div>
        <div class="form-group">
            <div class="estate-title">
                <span>地址：</span>
            </div>
            <div class="estate-con">
                <span>
                    <?php echo $parking_Detail['0']['building_number'].'号楼',$parking_Detail['0']['room_number'];?>
                </span>
            </div>
        </div>
        <div class="form-group">
            <div class="estate-title">
                <span>停车费：</span>
            </div>
            <div class="estate-con">
                <span><?php echo $parking_Detail['0']['order_money'];?></span>
            </div>
        </div>
        <div class="form-group">
            <div class="estate-title">
                <span>车辆品牌：</span>
            </div>
            <div class="estate-con">
                <span><?php echo $car_Detail['car_brand'];?></span>
            </div>
        </div>
        <div class="form-group">
            <div class="estate-title">
                <span>车辆号码：</span>
            </div>
            <div class="estate-con">
                <span><?php echo $car_Detail['car_no'];?></span>
            </div>
        </div>
        <div class="form-group">
            <div class="estate-title">
                <span>车前照：</span>
            </div>
            <div class="estate-con">
                <ul class="estate-gallery clearfix">
                    <?php $car_Detail = json_decode($car_Detail['car_img'])?>
                    <?php if(!empty($car_Detail[0])) {?>
                        <li><img src="<?php echo(IMG_BASE_PATH).'wy_images/source/'.$car_Detail[0];?>" alt="estate-image" class="car-img"></li>
                    <?php }?>
                    <?php if(!empty($car_Detail[1])) {?>
                        <li><img src="<?php echo(IMG_BASE_PATH).'wy_images/source/'.$car_Detail[1];?>" alt="estate-image" class="car-img"></li>
                    <?php }?>
                    <?php if(!empty($car_Detail[2])) {?>
                        <li><img src="<?php echo(IMG_BASE_PATH).'wy_images/source/'.$car_Detail[2];?>" alt="estate-image" class="car-img"></li>
                    <?php }?>
                </ul>
            </div>
        </div>
        <div class="form-inline m-form-group text-center mt45">
            <a href="<?php echo $this->createUrl('ParkingFeeOrderList')?>" class="btn btn-default m-btn" >返回</a>
        </div>
    </div>
</div>