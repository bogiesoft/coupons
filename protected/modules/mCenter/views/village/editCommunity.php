<h4 class="g-title">编辑小区</h4>
<?php echo CHtml::beginForm(Yii::app()->createUrl('mCenter/Village/EditCommunity',array('community_id'=>$community_id)), 'post',array('id' => 'createForm'));?>
<div class="g-container">
    <div class="m-estate-box">
        <div class="form-group">
            <div class="estate-title mt6">
                <span><em class="text-red">*</em>小区：</span>
            </div>
            <div class="estate-con">
                <?php echo CHtml::textField('name', $community_detail['name'], array('class' => 'form-control fc-sm', 'onblur' => 'checkName()')); ?>
                <label class="error" id="name_error" for="name" style="display: none"></label>
            </div>
        </div>
        <div class="form-group">
            <div class="estate-title mt6">
                <span><em class="text-red">*</em>联系方式：</span>
            </div>
            <div class="estate-con">
                <?php echo CHtml::textField('tel', $community_detail['tel'], array('class' => 'form-control fc-sm')); ?>
            </div>
        </div>
        <div class="form-group">
            <div class="estate-title mt6">
                <span><em class="text-red">*</em>地址：</span>
            </div>
            <div class="estate-con">
                <?php echo CHtml::textField('address', $community_detail['address'], array('class' => 'form-control fc-sm')); ?>
            </div>
        </div>
        <div class="form-group">
            <div class="estate-title">
                <span>备注：</span>
            </div>
            <div class="estate-con">
                <?php echo CHtml::textArea('remark', $community_detail['remark'], array('class' => 'form-control estate-textarea')); ?>
            </div>
        </div>
        <div class="form-inline m-form-group text-left mt45">
            <?php echo CHtml::submitButton('保存', array('class' => 'btn btn-primary m-btn','id' => 'submit')); ?>
            <button type="button" class="btn btn-default m-btn" onclick="javascript:history.back(-1);">取消</button>
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
            rules:{
                name:{
                    required: true,
                    rangelength:[2,15]
                },
                tel:{
                    required: true,
                    telCheck: true
                },
                address:{
                    required: true
                }
            },
            messages:{
                name:{
                    required:"请填写小区名称",
                    rangelength: "请输入2~15个字符"
                },
                tel:{
                    required:"请填写联系方式"
                },
                address:{
                    required:"请填写地址"
                }
            },
            submitHandler : function(form) {
                form.submit();
            }
        });
        $.validator.addMethod("telCheck", function (value, element) {
            var tel = /^((\d{11})|(\d{7,8})|(\d{4}|\d{3})-(\d{7,8}))/; //填写正则
            return this.optional(element) || (tel.test(value));
        }, "请填写正确的联系方式") //报错提示内容
        $(".real_submit").click(function () {
            window.location.href = '<?php echo Yii::app()->createUrl('mCenter/Village/CommunityList') ?>';
        });
    })

    //验证小区名称是否重复
    function checkName() {
        var name = $("#name").val();
        var flag = '';
        if (name != '') {
            $.ajax({
                url: '<?php echo (Yii::app()->createUrl('mCenter/Village/CommunityNameValid'));?>',
                data: {name: name},
                type: 'post',
                async: false,
                dataType: 'json',
                success: function (data) {
                    if (data.error == 'success') {
                        $("#name_error").html('小区名称已存在').attr("style", "display:block");
                        flag = false;
                    } else {
                        $("#name_error").html('').attr("style", "display:none");
                        flag = true;
                    }
                }
            });
            return flag;
        }
    }
</script>