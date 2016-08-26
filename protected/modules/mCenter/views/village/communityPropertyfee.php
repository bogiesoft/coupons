
<h4 class="g-title">设置费用</h4>
<?php echo CHtml::beginForm($this->createUrl('CommunityPropertyfee', array('community_id' => $community_id)), 'post',array('id' => 'createForm'));?>
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
            <a class="nav-link" href="<?php echo $this->createUrl('CommunityParkingfee',array('community_id' => $community_id))?>">停车费</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="<?php echo $this->createUrl('CommunityPropertyfee',array('community_id' => $community_id))?>">物业费</a>
        </li>
    </ul>
    <!--物业设置-->
    <div class="m-estate-box set-cost-box">
        <div class="form-group">
            <div class="estate-title">
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
            <div class="estate-con">
                <input type="text" class="form-control fc-sm" value="一年" disabled>
            </div>
        </div>
        <div class="form-group">
            <div class="estate-title mt6">
                <span>单价：<em class="text-blue">(月.m²)</em></span>
            </div>
            <div class="estate-con">
                <div class="form-inline">
                    <?php echo CHtml::textField('year_price', !empty($community_Propertyfee['year_price']) ? $community_Propertyfee['year_price'] : '', array('class' => 'form-control fc-sm')); ?>
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
    //表单验证
    $(function () {
        $("#createForm").validate({
            groups: {
                nameGroup: "day_price half_year_price year_price"
            },
            rules: {
                day_price: {
                    required: true,
                    number:true
                },
                half_year_price: {
                    required: true,
                    number:true
                },
                year_price: {
                    required: true,
                    number:true
                },
            },
            errorPlacement: function (error, element) {
                if (element.attr("name") == "day_price" || element.attr("name") == "half_year_price")
                    error.insertAfter("#year_price");
                else
                    error.insertAfter(element);
            },
            messages:{
                day_price: {
                    required: "请填写各时限的物业费！" ,
                    number:"必须输入合法的数字"
                },
                half_year_price: {
                    required: "请填写各时限的物业费！" ,
                    number:"必须输入合法的数字"
                },
                year_price: {
                    required: "请填写物业费单价！"
                    ,number:"必须输入合法的数字"
                }
            },
                submitHandler : function(form) {
                    form.submit();
                }
        });
        $(".real_submit").click(function () {
            window.location.href = '<?php echo $this->createUrl('CommunityPropertyfee', array('community_id' => $community_id)) ?>';
        });
    });
</script>
