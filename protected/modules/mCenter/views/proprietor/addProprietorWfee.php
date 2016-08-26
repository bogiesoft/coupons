<h4 class="g-title">添加水费</h4>
<div class="g-container">
    <?php echo CHtml::beginForm($this->createUrl('AddProprietorWfee', array('proprietor_id' => $proprietor_id,'user_id' => $user_id)), 'post',array('id' => 'createForm'));?>
    <div class="m-estate-box estate-add-fee-box">
        <div class="form-group">
            <div class="estate-title mt6">
                <span>月份：</span>
            </div>
            <div class="estate-con">
                <?php echo CHtml::textField('date',isset($_POST['date']) ? $_POST['date'] : date('Y-m'), array('class' => 'form-control fc-sm','readonly'=> 'readonly')) ?>
            </div>
        </div>
        <div class="form-group">
            <div class="estate-title mt6">
                <span>本月用水量：</span>
            </div>
            <div class="estate-con form-inline">
                <?php echo CHtml::textField('water_ton', isset($_POST['water_ton']) ? $_POST['water_ton'] : '', array('class' => 'form-control fc-sm')); ?>
                <span class="text-blue pl10">每吨水费用：¥<?php echo !empty($wfee_info['price']) ? $wfee_info['price']: ''?></span>
            </div>
        </div>
        <div class="form-group">
            <div class="estate-title mt6">
                <span>本月水费：</span>
            </div>
            <div class="estate-con">
                <?php echo CHtml::textField('order_money', isset($_POST['order_money']) ? $_POST['order_money'] : '', array('class' => 'form-control fc-sm')); ?>
            </div>
        </div>

        <div class="form-inline m-form-group text-left mt45">
            <?php echo CHtml::submitButton('保存', array('class' => 'btn btn-primary m-btn')); ?>
            <button type="button" class="btn btn-default m-btn" onclick="javascript:history.back(-1);">取消</button>
            <!--btn-default为白色底 btn-primary为蓝色底-->
        </div>
    </div>
    <?php echo CHtml::endForm(); ?>
    <div class="mt45">
        <h4>往期水费</h4>
        <table class="table g-table g-table-center mt20">
            <thead>
            <tr>
                <th>月份</th>
                <th>本月用水量</th>
                <th>本月水费</th>
                <th>支付状态</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($feerder_list)) {?>
                <?php foreach ($feerder_list as $k => $v) {?>
            <tr>
                <td><?php echo date('Y年m月', strtotime($v['date']));?></td>
                <td><?php echo $v['water_ton'] ?></td>
                <td><?php echo $v['order_money'] ?></td>
                <td>
                    <span class="text-blue"><?php echo $GLOBALS['ORDER_STATUS_PAY'][$v['pay_status']] ?></span>
                </td>
            </tr>
                <?php }?>
            <?php }?>
            </tbody>
        </table>
    </div>
</div>

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
                water_ton:{
                    required: true,
                    number:true
                },
                order_money:{
                    required: true,
                    number:true
                },
                submitHandler : function(form) {
                    form.submit();
                }
            },
            messages:{
                water_ton:{
                    required:"请填写本月用水量",
                    number:"必须输入合法的数字"
                },
                order_money:{
                    required:"请填写本月水费",
                    number:"必须输入合法的数字"
                },
            },
        });
        $(".real_submit").click(function () {
            window.location.href = '<?php echo $this->createUrl('ProprietorList') ?>';
        });
    });


    //绑定时间插件
    $('input[name=date]').datepicker({
        format: 'yyyy-mm',
        weekStart: 1,
        autoclose: true,
        startView: 1,
        maxViewMode: 1,
        minViewMode: 1,
        forceParse: false,
        language: 'zh-CN',
    });

</script>
