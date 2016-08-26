<h4 class="g-title">添加电费</h4>
<div class="g-container">
    <!--电费设置-->
    <?php echo CHtml::beginForm($this->createUrl('AddProprietorEfee', array('proprietor_id' => $proprietor_id,'user_id' => $user_id)), 'post',array('id' => 'createForm'));?>
    <div class="m-estate-box estate-add-fee-box">
        <div class="form-group">
            <div class="estate-title mt6">
                <span>月份：</span>
            </div>
            <div class="estate-con">
                <?php echo CHtml::textField('date',isset($_POST['date']) ? $_POST['date'] : date('Y-m'), array('class' => 'form-control fc-sm','readonly'=> 'readonly')) ?>
            </div>
        </div>
        <!--分时段增加下面-->
        <?php if ($efee_type == 1){?>
        <div class="form-group">
            <div class="estate-title mt6">
                <span>峰电量：</span>
            </div>
            <div class="estate-con form-inline">
                <?php echo CHtml::textField('peak', isset($_POST['peak']) ? $_POST['peak'] : '', array('class' => 'form-control fc-sm')); ?>
                <span class="text-blue pl10">峰电：<?php echo !empty($efee_info['peak_time']) ? $efee_info['peak_time']: ''?> 每度电费用： ¥<?php echo !empty($efee_info['peak_price']) ? $efee_info['peak_price'] : ''?></span>
            </div>
        </div>
        <div class="form-group">
            <div class="estate-title mt6">
                <span>谷电量：</span>
            </div>
            <div class="estate-con form-inline">
                <?php echo CHtml::textField('valley', isset($_POST['valley']) ? $_POST['valley'] : '', array('class' => 'form-control fc-sm')); ?>
                <span class="text-blue pl10">谷电：<?php echo !empty($efee_info['trough_time'] )? $efee_info['trough_time'] : ''?> 每度电费用： ¥<?php echo !empty($efee_info['trough_price']) ? $efee_info['trough_price'] : ''?></span>
            </div>
        </div>
        <?php }?>

        <!--不分时段-->

        <div class="form-group">
            <div class="estate-title mt6">
                <span>本月用电量：</span>
            </div>
            <div class="estate-con form-inline">
                <?php echo CHtml::textField('electricity', isset($_POST['electricity']) ? $_POST['electricity'] : '', array('class' => 'form-control fc-sm')); ?>
                <?php if ($efee_type !== 1){?>
                <span class="text-blue pl10"><?php echo !empty($efee_info['price']) ? '每度电费用：¥'.$efee_info['price'] : ''?></span>
                <?php }?>
                <!--分时段上面的提示隐藏-->
            </div>
        </div>
        <div class="form-group">
            <div class="estate-title mt6">
                <span>本月电费：</span>
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
        <h4>往期电费</h4>
        <table class="table g-table g-table-center mt20">
            <thead>
            <tr>
                <th>月份</th>
                <th>白天用电量</th>
                <th>晚上用电量</th>
                <th>本月用电量</th>
                <th>本月电费</th>
                <th>支付状态</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($feerder_list)) {?>
            <?php foreach ($feerder_list as $k => $v) {?>
            <tr>
                <td><?php echo date("Y年m月",strtotime($v['date'])); ?></td>
                <td><?php echo isset($v['peak']) ? $v['peak'] : '0.00' ?></td>
                <td><?php echo isset( $v['valley']) ?  $v['valley'] : '0.00' ?></td>
                <td><?php echo $v['electricity'] ?></td>
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
                peak:{
                    required: true,
                    number:true
                },
                valley:{
                    required: true,
                    number:true
                },
                electricity:{
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
                peak:{
                    required:"请填写峰电量",
                    number:"必须输入合法的数字"
                },
                valley:{
                    required:"请填写谷电量",
                    number:"必须输入合法的数字"
                },
                electricity:{
                    required:"请填写本月用电量",
                    number:"必须输入合法的数字"
                },
                order_money:{
                    required:"请填写本月电费",
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