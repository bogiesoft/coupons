
<h4 class="g-title">费用列表</h4>
<div class="g-container">
    <ul class="nav nav-tabs m-nav-tabs">
        <li class="nav-item">
            <a class="nav-link" href="<?php echo Yii::app()->createUrl('mCenter/FeeOrder/EFeeOrderList')?>">电费</a>
            <!--激活状态增加active-->
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo Yii::app()->createUrl('mCenter/FeeOrder/WFeeOrderList')?>">水费</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo Yii::app()->createUrl('mCenter/FeeOrder/ParkingFeeOrderList')?>">停车费</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="<?php echo Yii::app()->createUrl('mCenter/FeeOrder/PropertyFeeOrderList')?>">物业费</a>
        </li>
    </ul>
    <div class="excel-operate cost-manage-excel-operate">
        <button type="button" onclick="window.location.href = '<?php echo Yii::app()->createUrl('mCenter/feeOrder/getModels', array(
            'order_type' => FEEORDER_TYPE_PROPERTY_FEE))?>'">EXCEL模板下载</button>
        <?php echo CHtml::fileField('file', '', array('class' => 'ml10')) ?>
        <button type="button" class="ml10" onclick="window.location.href = '<?php echo Yii::app()->createUrl('mCenter/feeOrder/getExcel',array(
            'order_type' => FEEORDER_TYPE_PROPERTY_FEE,
            'keyword' => isset($_GET['pay_status'])?$_GET['pay_status']:'',
            'address' => isset($_GET['time'])?$_GET['time']:'',
            //小区
            'Time2' => isset($_GET['building_number']) ? $_GET['building_number'] : '',
            'transaction_time' => isset($_GET['user_account'])?$_GET['user_account']:'',
        ));?>' ">
            EXCEL导出
        </button>
    </div>
    <div class="m-estate-box set-cost-box cost-manage-box">
        <div class="m-search">
            <div class="form-inline">
                <form method="get" id="eform" action="<?php
                echo Yii::app()->createUrl('mCenter/FeeOrder/PropertyFeeOrderSelect');?>">
                    <div class="form-group">
                            <?php echo CHtml::dropDownList('pay_status', isset($_GET['pay_status']) ? $_GET['pay_status'] : '', $pay_status, array('prompt' => '全部支付状态', 'class' => 'form-control'));?>
                    </div>
                    <div class="form-group ml10">
                        <input style="font-size:13px" type="text" name="time" value="<?php echo isset($_GET['time']) ? $_GET['time'] : '';?>" id="sandbox-container" class="form-control fc-m-sm" placeholder="选择日期范围">
                    </div>
                    <div class="form-group ml10">
                        <select class="form-control" name="community_id">
                            <option name="community" value="">全部小区</option>
                            <?php if (!empty($community_list)) {?>
                                <?php foreach ($community_list as $k => $v) { ?>
                                    <option name="community" <?php if(isset($_GET['community_id']) && $_GET['community_id'] == $v['id']) {?>selected<?php }?> value="<?php echo $v['id']?>"><?php echo $v['name'];?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group ml10">
                        <input type="text" class="form-control building-num" name="building_number" value="<?php echo isset($_GET['building_number']) ? $_GET['building_number'] : '';?>" placeholder="楼栋号">
                    </div>
                    <div class="input-group ml10">
                        <input style="font-size:12px" type="text" name="user_account" class="form-control ticker-num" value="<?php echo isset($_GET['user_account']) ? $_GET['user_account'] : '';?>" placeholder="请输入手机号">
                        <span onclick="subForm()" class="input-group-addon btn-search"><i class="fa fa-search"></i></span>
                    </div>
                </form>
            </div>
        </div>

        <!--物业费-->
        <div class="estate-cost-table">
            <table class="table g-table g-table-center mt20 estate-cost-table">
                <thead>
                <tr>
                    <th>业主</th>
                    <th>手机号</th>
                    <th>小区</th>
                    <th>楼号</th>
                    <th>门牌号</th>
                    <th>创建日期</th>
                    <th>物业费</th>
                    <th>年份</th>
                    <th>支付状态</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($fee_Order_List)) { ?>
                    <?php foreach ($fee_Order_List as $k => $v) { ;?>
                        <tr>
                            <td><?php echo $v['name'];?></td>
                            <td><?php echo $v['account'];?></td>
                            <td><?php echo $v['community_name'];?></td>
                            <td><?php echo $v['building_number'];?></td>
                            <td><?php echo $v['room_number'];?></td>
                            <td><?php echo $v['create_time'];?></td>
                            <td><?php echo $v['order_money'];?></td>
                            <td><?php echo $v['date'];?></td>
                            <td class="text-blue"><?php echo $GLOBALS['ORDER_STATUS_PAY'][$v['pay_status']];?></td>
                            <td>
                                <div class="m-operate">
                                    <button type="button" class="btn btn-primary btn-operate">操作</button>
                                    <ul class="m-operate-list">
                                        <?php if($v['pay_status'] == ORDER_STATUS_UNPAID) {?>
                                            <li><a href="<?php echo Yii::app()->createUrl('mCenter/FeeOrder/GetFeeStatus', array('order_id' => $v['order_id'], 'order_type' => FEEORDER_TYPE_PROPERTY_FEE, 'goUrl' => Yii::app()->request->url));?>">确认</a></li>
                                        <?php }?>
                                        <li><a href="<?php echo Yii::app()->createUrl('mCenter/FeeOrder/DeleteOrder',
                                                array('order_id'=>$v['order_id'],'order_type'=>$v['order_type']));?>" onclick="return confirm('确定要删除吗？');">删除</a></li>
                                        <li><a href="<?php echo Yii::app()->createUrl('mCenter/FeeOrder/PropertyFeeOrderDetail',
                                                array('order_id'=>$v['order_id']));?>">查看详情</a></li>

                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
                </tbody>
            </table>
            <?php if (empty($fee_Order_List)) { ?>
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
        eform.submit();
    }

    $(function () {
        $('#file').uploadify({
            uploader: '<?php echo UPLOAD_TO_PATH?>',// 服务器处理地址
            swf: '<?php echo GJ_STATIC_JS?>' + 'uploadify/uploadify.swf',
            buttonText: "EXCEL导入",//按钮文字
            height: 32,  //按钮高度
            width: 70, //按钮宽度
            fileTypeDesc: "请选择文件", //文件说明
            formData: {'folder': '<?php echo WY_EXCEL_FOLDER?>'}, //提交给服务器端的参数
            onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
                eval("var jsondata = " + data + ";");
                var fileName = jsondata['fileName'];
                $.ajax({
                    url: '<?php echo $this->createUrl('importData3');?>?' + new Date().getTime(),
                    data: {fileName: fileName},
                    type: 'get',
                    async: false,
                    success: function ($rs) {
                        if($rs == 'successs') {
                            alert('数据导入成功');
                            location.reload();
                        }
                    }
                });
            }
        });
    })
</script>