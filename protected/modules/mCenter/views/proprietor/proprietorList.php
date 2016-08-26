<h4 class="g-title">业主管理</h4>
<div class="g-container">
    <form  action="<?php echo $this->createUrl('ProprietorList')?>" method="get" id="proprietorform">
    <div class="m-search">
        <div class="form-inline">
            <div class="form-group">
                <select class="form-control" name="community_name" id="community_name">
                    <option value="" selected = "selected">全部小区</option>
                    <?php if (!empty($community_list)){?>
                    <?php foreach ($community_list as $k => $v){?>
                    <option <?php if(isset($_GET['community_name']) && $_GET['community_name'] == $v['id']) {?>selected<?php }?> value="<?php echo $v['id']?>"><?php echo $v['name']?></option>
                    <?php }?>
                    <?php }?>
                </select>
            </div>
            <div class="form-group ml10">
                <?php echo CHtml::textField('building_number', isset($_GET['building_number']) ? $_GET['building_number'] : '', array('class' => 'form-control building-num','placeholder' => '楼号')); ?>
            </div>
            <div class="input-group ml10">
                <?php echo CHtml::textField('tel', isset($_GET['tel']) ? $_GET['tel'] : '', array('class' => 'form-control fc-lg','placeholder' => '请输入业主联系方式')); ?>
                <input name="verify_status" value="<?php echo isset($_GET['verify_status'])?$_GET['verify_status']:'';?>" type="hidden">
                <span onclick="subForm()" class="input-group-addon btn-search"><i class="fa fa-search"></i></span>
            </div>
            <div class="form-group excel-operate fr">
                <button type="button" onclick="window.location.href = '<?php echo Yii::app()->createUrl('mCenter/proprietor/GetModels');?>'">EXCEL模板下载</button>
<!--                <button type="button" class="ml10" id="file"></button>-->
                <?php echo CHtml::fileField('file', '', array('class' => 'ml10')) ?>
                <button type="button" class="ml10" onclick="window.location.href = '<?php echo Yii::app()->createUrl('mCenter/proprietor/GetProprietorMsg');?>'">
                    EXCEL导出
                </button>
            </div>
        </div>
    </div>
    </form>
    <ul class="nav nav-tabs m-nav-tabs mt20">
        <li class="nav-item">
            <a class="nav-link <?php if (!isset($_GET['verify_status'])) {
                echo 'active';
            } else {
                echo '';
            } ?>" href='<?php echo Yii::app()->createUrl('mCenter/Proprietor/ProprietorList'); ?>'>全部</a>
            <!--激活状态增加active-->
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if (isset($_GET['verify_status']) && $_GET['verify_status'] == PROPRIETOR_VERIFY_STATUS_PENDING_AUDIT) {
                echo 'active';
            } else {
                echo '';
            } ?>" href='<?php echo Yii::app()->createUrl('mCenter/Proprietor/ProprietorList',array('verify_status' => PROPRIETOR_VERIFY_STATUS_PENDING_AUDIT)); ?>'>待审核</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if (isset($_GET['verify_status']) && $_GET['verify_status'] == PROPRIETOR_VERIFY_STATUS_PASS) {
                echo 'active';
            } else {
                echo '';
            } ?>" href='<?php echo Yii::app()->createUrl('mCenter/Proprietor/ProprietorList',array('verify_status' => PROPRIETOR_VERIFY_STATUS_PASS)); ?>'>成功</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if (isset($_GET['verify_status']) && $_GET['verify_status'] == PROPRIETOR_VERIFY_STATUS_REJECT) {
                echo 'active';
            } else {
                echo '';
            } ?>" href='<?php echo Yii::app()->createUrl('mCenter/Proprietor/ProprietorList',array('verify_status' => PROPRIETOR_VERIFY_STATUS_REJECT)); ?>'>不成功</a>
        </li>
    </ul>
    <div class="m-estate-box set-cost-box">
        <!--待审核-->
        <div class="estate-owner-manage-table">
            <table class="table g-table g-table-center mt20">
                <thead>
                <tr>
                    <th>姓名</th>
                    <th>手机号</th>
                    <th>门禁卡号</th>
                    <th>类型</th>
                    <th>小区</th>
                    <th>楼号</th>
                    <th>门牌号</th>
                    <th>状态</th>
                    <th>费用管理</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($proprietor_list)) {?>
                <?php foreach ($proprietor_list as $k => $v){?>
                <tr>
                    <td><?php echo !empty($v['name']) ? $v['name'] : ''?></td>
                    <td><?php echo !empty($v['tel']) ? $v['tel'] : ''?></td>
                    <td><?php echo !empty($v['access_control_card_no']) ? $v['access_control_card_no'] : ''?></td>
                    <td><?php echo !empty($v['type']) ? $GLOBALS['__PROPRIETOR_TYPE'][$v['type']] : ''?></td>
                    <td><?php echo !empty($v['community_id']) ? $v['community_id'] : ''?></td>
                    <td><?php echo !empty($v['building_number']) ? $v['building_number'] : ''?></td>
                    <td><?php echo !empty($v['room_number']) ? $v['room_number'] : ''?></td>
                    <td><span class="text-orange"><?php echo !empty($v['verify_status']) ? $GLOBALS['__PROPRIETOR_VERIFY_STATUS'][$v['verify_status']] : ''?></span></td>
                    <td>
                        <a href="<?php echo Yii::app()->createUrl('mCenter/Proprietor/AddProprietorProfee',array('proprietor_id' => $v['id'],'user_id' => $v['user_id'])); ?>" class="text-blue">添加物业费</a>
                        <a href="<?php echo Yii::app()->createUrl('mCenter/Proprietor/AddProprietorEfee',array('proprietor_id' => $v['id'],'user_id' => $v['user_id'])); ?>" class="text-blue">添加电费</a>
                        <a href="<?php echo Yii::app()->createUrl('mCenter/Proprietor/AddProprietorWfee',array('proprietor_id' => $v['id'],'user_id' => $v['user_id'])); ?>" class="text-blue">添加水费</a>
                    </td>
                    <td>
                        <div class="m-operate">
                            <button type="button" class="btn btn-primary btn-operate">操作</button>
                            <ul class="m-operate-list">
                                <?php if ($v['verify_status'] == PROPRIETOR_VERIFY_STATUS_PENDING_AUDIT){ ?>
                                    <li><a href="<?php echo Yii::app()->createUrl('mCenter/Proprietor/ProprietorVerifyStatusPass',array('proprietor_id' => $v['id'])); ?> " onclick="return confirm('确定通过审核吗？');">通过</a></li>
                                    <li><a href="" class="js-reject-btn" onclick="setProprietorId('<?php echo $v['id']?>')">驳回</a></li>
                                <?php } ?>
                                <li><a href="<?php echo Yii::app()->createUrl('mCenter/Proprietor/DeleteProprietor',array('user_id' => $v['user_id'],'proprietor_id' => $v['id'])); ?> " onclick="return confirm('确定删除吗？');">删除</a></li>
                                <li><a href="<?php echo Yii::app()->createUrl('mCenter/Proprietor/ProprietorInfo',array('proprietor_id' => $v['id'])); ?>">查看详情</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                    <?php }?>
                <?php }?>
                </tbody>
                <!--点击反驳后出现的弹窗-->
                <?php echo CHtml::beginForm(Yii::app()->createUrl('mCenter/Proprietor/ProprietorVerifyStatusReject'), 'post'); ?>
                <div class="m-reject-pop" style="display: none">
                    <h4>输入驳回理由：<i class="fa fa-times fr js-reject-pop-close"></i></h4>
                    <textarea name="remark" class="form-control mt20"></textarea>
                    <button type="submit" class="btn btn-primary m-btn mt20">确定</button>
                    <input type="hidden" value="" id="proprietorId" name="id">
                </div>
                <?php echo CHtml::endForm(); ?>
            </table>
            <?php if (empty($proprietor_list)) { ?>
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
    $(function () {
        $(".js-reject-btn").click(function () {
            $(".m-reject-pop").show();
        });
        $(".js-reject-pop-close").click(function () {
            $(".m-reject-pop").hide();
        });
    })

    /**
     * 驳回 业主id赋值
     * @param val
     */
    function setProprietorId(val) {
        $("#proprietorId").val(val);
    }

    //提交表单
    function subForm()
    {
        proprietorform.submit();
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
                    url: '<?php echo $this->createUrl('ImportData');?>?' + new Date().getTime(),
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