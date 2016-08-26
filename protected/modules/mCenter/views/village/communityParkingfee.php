
<h4 class="g-title">设置费用</h4>
<?php echo CHtml::beginForm($this->createUrl('CommunityParkingfee', array('community_id' => $community_id)), 'post',array('id' => 'createForm'));?>
<div class="g-container">
    <ul class="nav nav-tabs m-nav-tabs">
        <li class="nav-item">
            <a class="nav-link" href="<?php echo $this->createUrl('CommunityEfee',array('community_id' => $community_id))?>">电费</a>
            <!--激活状态增加active-->
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo $this->createUrl('CommunityWfee',array('community_id' => $community_id))?>">水费</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="<?php echo $this->createUrl('CommunityParkingfee',array('community_id' => $community_id))?>">停车费</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo $this->createUrl('CommunityPropertyfee',array('community_id' => $community_id))?>">物业费</a>
        </li>
    </ul>
    <!--停车费设置-->
    <div class="m-estate-box set-cost-box">
        <div class="form-group">
            <button type="button" class="btn btn-default m-btn js-divide-btn">地下</button>
            <button type="button" class="btn btn-primary m-btn ml15 js-undivide-btn">地上</button>
        </div>
        <input style="display: none" value="<?php echo 1 ?>" name="parking_type" id="parking_type">
        <div class="js-undivide-wrapper">
            <div class="form-group">
                <div class="estate-title mt6">
                    <span>小区：</span>
                </div>
                <div class="estate-con">
                    <span><?php echo $community_name['name']?></span>
                </div>
            </div>
            <div class="form-group">
                <div class="estate-title">
                    <span>时间：</span>
                </div>
                <div class="estate-con set-cost-time">
                    <span>一个月</span>
                    <span class="ml15">半年</span>
                    <span class="ml15">一年</span>
                </div>
            </div>
            <div class="form-group">
                <div class="estate-title mt6">
                    <span>停车费：<span class="text-blue pl10">(元)</span></span>
                </div>
                <div class="estate-con">
                    <div class="form-inline">
                        <?php echo CHtml::textField('over_day_price', !empty($community_Parkingfee['over_day_price']) ? $community_Parkingfee['over_day_price'] : '', array('class' => 'form-control fc-v-sm')); ?>
                        <?php echo CHtml::textField('over_half_year_price', !empty($community_Parkingfee['over_half_year_price']) ? $community_Parkingfee['over_half_year_price'] : '', array('class' => 'form-control fc-v-sm ml15')); ?>
                        <?php echo CHtml::textField('over_year_price', !empty($community_Parkingfee['over_year_price']) ? $community_Parkingfee['over_year_price'] : '', array('class' => 'form-control fc-v-sm ml15')); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="js-divide-wrapper" style="display: none;">
            <div class="form-group">
                <div class="estate-title mt6">
                    <span>小区：</span>
                </div>
                <div class="estate-con">
                    <span><?php echo $community_name['name']?></span>
                </div>
            </div>
            <div class="form-group">
                <div class="estate-title">
                    <span>时间：</span>
                </div>
                <div class="estate-con set-cost-time">
                    <span>半年</span>
                    <span class="ml15">一年</span>
                </div>
            </div>
            <div class="form-group">
                <div class="estate-title mt6">
                    <span>停车费：<span class="text-blue pl10">(元)</span></span>
                </div>
                <div class="estate-con">
                    <div class="form-inline">
                        <?php echo CHtml::textField('under_half_year_price', !empty($community_Parkingfee['under_half_year_price']) ? $community_Parkingfee['under_half_year_price'] : '', array('class' => 'form-control fc-v-sm')); ?>
                        <?php echo CHtml::textField('under_year_price', !empty($community_Parkingfee['under_year_price']) ? $community_Parkingfee['under_year_price'] : '', array('class' => 'form-control fc-v-sm ml15')); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-inline m-form-group text-left mt45">
            <?php echo CHtml::submitButton('保存', array('class' => 'btn btn-primary m-btn')); ?>
            <button type="button" class="btn btn-default m-btn" onclick="window.location.href='CommunityList'">取消</button>
            <!--btn-default为白色底 btn-primary为蓝色底-->
        </div>
    </div>
</div>
<?php echo CHtml::endForm(); ?>

<?php if (isset($_POST) && !empty($_POST) && Yii::app()->user->hasFlash('info') == 'success') { ?>
    <div class="m-pop estate-success-pop">
        <div class="m-pop-title">
            <h4>温馨提示：</h4>
        </div>
        <div class="m-pop-con">
            <div class="text-center mt20">
                <i class="fa fa-check-circle"></i>
                <span class="pl15">恭喜您，保存成功！</span>
            </div>
            <div class="text-center mt20">
                <button type="button" class="btn btn-primary m-btn real_submit" id="confirm">确定</button>
            </div>
        </div>
    </div>
<?php }?>

<script>
    $(function () {
        // 停车费设置-分区域地上地下
        $(".js-divide-btn").click(function () {
            $("#parking_type").val(2);
        });
        $(".js-undivide-btn").click(function () {
            $("#parking_type").val(1);
        })
    })

    //表单验证
    $(function () {
        $("#createForm").validate({
            groups: {
                nameGroup1: "over_day_price over_half_year_price over_year_price ",
                nameGroup2: "under_half_year_price under_year_price"
            },
            rules: {
                over_day_price: {
                    required: true,
                    number:true
                },
                over_half_year_price: {
                    required: true,
                    number:true
                },
                over_year_price: {
                    required: true,
                    number:true
                },
                under_half_year_price: {
                    required: true,
                    number:true
                },
                under_year_price: {
                    required: true,
                    number:true
                },
            },
            errorPlacement: function (error, element) {
                if (element.attr("name") == "over_day_price" || element.attr("name") == "over_half_year_price" || element.attr("name") == "over_year_price") {
                    error.insertAfter("#over_year_price");
                }
                else if (element.attr("name") == "under_half_year_price" || element.attr("name") == "under_year_price") {
                    error.insertAfter("#under_year_price");
                } else {
                    error.insertAfter("element");
                }
            },
            messages:{
                over_day_price: {
                    required: "请填写一个月的停车费！" ,
                    number:"必须输入合法的数字"
                },
                over_half_year_price: {
                    required: "请填写半年的停车费！",
                    number:"必须输入合法的数字"
                },
                over_year_price: {
                    required: "请填写一年的停车费！",
                    number:"必须输入合法的数字"
                },
                under_half_year_price: {
                    required: "请填写半年的停车费！",
                    number:"必须输入合法的数字"
                },
                under_year_price: {
                    required: "请填写一年的停车费！",
                    number:"必须输入合法的数字"
                }
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
        $(".real_submit").click(function () {
            window.location.href = '<?php echo $this->createUrl('CommunityParkingfee', array('community_id' => $community_id)) ?>';
        });
    });
</script>
