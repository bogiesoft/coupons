
<h4 class="g-title">活动列表</h4>
<div class="g-container">
    <div class="m-search act-list-search">
        <div class="form-inline">

            <form method="get" id="mform" action="<?php
            echo Yii::app()->createUrl('marketing/marketing/marketingList');?>">

            <div class="form-group">
                <?php echo CHtml::dropDownList('status', isset($_GET['status'])?$_GET['status']:'', $GLOBALS['__MARKETING_ACTIVITY_STATUS'],array('class'=>'form-control','prompt'=>'所有活动状态')); ?>
            </div>
            <div class="form-group ml15">
                <?php echo CHtml::dropDownList('type', isset($_GET['type'])?$_GET['type']:'', $GLOBALS['__MARKETING_ACTIVITY_TYPE'],array('class'=>'form-control','prompt'=>'所有活动类型')); ?>
            </div>
            <div class="form-group">
                <div class="input-group ml15">
                    <?php echo CHtml::textField('name',isset($_GET['name'])?$_GET['name']:'',array('class'=>'form-control','placeholder'=>'请输入活动名称')); ?>
                    <span onclick="subForm()" class="input-group-addon btn-search"><i class="fa fa-search"></i></span>
                </div>
            </div>

            <div class="form-group fr">
                <button type="button" class="btn btn-primary" onclick="window.location.href = '<?php echo Yii::app()->createUrl('marketing/Marketing/createActivity');?>'">创建活动</button>
            </div>
            </form>
        </div>
    </div>
    <table class="table g-table g-table-center mt20 g-table-fixed act-list-table">
        <thead>
        <tr>
            <th width="10%">创建时间</th>
            <th width="15%">活动名称</th>
            <th width="13%">活动类型</th>
            <th width="21%">活动时间</th>
            <th width="7%">状态</th>
            <th width="9%">推送状态</th>
            <th width="8%">浏览次数</th>
            <th width="17%">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($list)){ ?>
        <?php foreach ($list as $k => $v){?>
        <tr>
            <td>
               <?php echo date('Y-m-d',strtotime($v['create_time'])); ?>
            </td>
            <td class="text-blue act-name" title="<?php echo $v['name'];?>">
                <a href="<?php
                if($v['type'] == MARKETING_ACTIVITY_TYPE_BASICS_JZMA){
                    echo $this->createUrl('Marketing/MarketingPrecisionDetail',array('marketing_id'=>$v['id']));
                }else{
                    echo $this->createUrl('Marketing/MarketingDetailElse',array('marketing_id'=>$v['id']));
                }
                ?>"><?php echo $v['name'];?></a>
            </td>
            <td>
                <?php echo isset($GLOBALS['__MARKETING_ACTIVITY_TYPE'][$v['type']])?$GLOBALS['__MARKETING_ACTIVITY_TYPE'][$v['type']]:''; ?>
            </td>
            <td>
                <?php if($v['time_type'] == MARKETING_ACTIVITY_TIME_TYPE_ONETIME){ ?>
                    <?php echo date('Y-m-d',strtotime($v['start_time'])); ?>
                <?php }elseif ($v['time_type'] == MARKETING_ACTIVITY_TIME_TYPE_PERIODICITY){?>
                    <?php echo $v['time_send_type'] == MARKETING_ACTIVITY_SEND_TYPE_CRONTAB ? date('Y-m-d',strtotime($v['start_time'])).'至'.date('Y-m-d',strtotime($v['end_time'])):'长期'; ?>
                <?php }?>
            </td>
            <td <?php if(isset($v['status']) && $v['status']==MARKETING_ACTIVITY_STATUS_STOP){
                echo 'class="text-orange"';
            }?>>
                <?php echo isset($GLOBALS['__MARKETING_ACTIVITY_STATUS'][$v['status']])?$GLOBALS['__MARKETING_ACTIVITY_STATUS'][$v['status']]:''; ?>
            </td>
            <td><?php if($v['send_status'] == 1) {
                    echo '已推送';
                } elseif ($v['send_status'] == 2) {
                    echo '未推送';
                } elseif ($v['send_status'] == 0) {
                    echo '提交失败';
                } ?></td>
            <td><?php echo $v['pv'];?></td>
            <td>
                <div class="inline-operate">
                    <?php if(isset($v['status']) && $v['status']==MARKETING_ACTIVITY_STATUS_NOSTART){?>
                        <a href="
                        <?php echo Yii::app()->createUrl('marketing/marketing/editMarketingActivity',array('id'=>$v['id'],'type'=>$v['type']));?>")">编辑</a>
                    <?php } ?>
                    <?php if(isset($v['status']) && $v['status']==MARKETING_ACTIVITY_STATUS_START){?>
                        <a href="javascript:;" onclick="stop_act(<?php echo $v['id'].','.$v['status'];?>)">停止</a>
                    <?php } ?>
                    <?php if(isset($v['status']) && $v['status']==MARKETING_ACTIVITY_STATUS_STOP){?>
                        <a href="javascript:;" onclick="start_act(<?php echo $v['id'].','.$v['status'].','.$v['type'];?>)">启用</a>
                    <?php } ?>
<!--                    <a href="#">活动报表</a>-->
                    <a href="javascript:;" onclick="del_act(<?php echo $v['id'];?>)">删除</a>
                </div>
            </td>
        </tr>
        <?php }?>
        <?php }?>
        </tbody>
    </table>
    <?php if (empty($list)) { ?>
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
<script>
    //下拉框触发表单提交
    $('#status').change(function() {
        $('#mform').submit();
    });

    $('#type').change(function() {
        $('#mform').submit();
    });

    //停止动作
    function stop_act(ma_id,status)  {
        //停止询问框
        layer.confirm('确认停止该活动？', {
            btn: ['停止','取消'], //按钮
            offset:'200px'
        }, function(){
            //点击确认后执行
            $.ajax({
                url:"<?php echo Yii::app()->createUrl('marketing/marketing/EditMarketingActivityStatus'); ?>",
                type:'get',
                data:'ma_id='+ma_id+'&status='+status,
                async : false, //同步
                success:function(data){
                    //成功后跳转到列表页面
                    location.reload();
                }
            });
        });
    }
    //启用动作
    function start_act(ma_id,status)  {
        //ajax验证 是否有正在进行中的相同类型活动（精准营销活动除外）
        $.ajax({
            url:"<?php echo Yii::app()->createUrl('marketing/marketing/ifMarketingActivity'); ?>",
            type:'get',
            data:'ma_id='+ma_id,
            async : false, //同步
            dataType : 'json',
            success:function(res){
                //判断返回的状态值，弹出两种不同的弹出框
                if(res.status==<?php echo ERROR_NONE;?>){
                    //启用询问框
                    layer.confirm('当前类型活动已存在，启用后正在进行的活动会失效，确认启用？', {
                        btn: ['启用','取消'],//按钮
                        offset:'200px'
                    },function(){
                        //点击确认后执行
                        $.ajax({
                            //修改活动状态（有正在进行中的相同类型活动）
                            url:"<?php echo Yii::app()->createUrl('marketing/marketing/EditMarketingActivityStatus'); ?>",
                            type:'get',
                            data:'ma_id='+ma_id+'&status='+status,
                            async : false, //同步
                            dataType : 'json',
                            success:function(res){
                                //成功后跳转到列表页面
                                location.reload();
                            }
                        });
                    });
                }
                else{
                    //启用询问框
                    layer.confirm('确认启用该活动？', {
                        btn: ['启用','取消'], //按钮
                        offset:'200px'
                    }, function(){
                        //点击确认后执行
                        $.ajax({
                            //修改活动状态（没有正在进行中的相同类型活动）
                            url:"<?php echo Yii::app()->createUrl('marketing/marketing/EditMarketingActivityStatus'); ?>",
                            type:'get',
                            data:'ma_id='+ma_id+'&status='+status,
                            async : false, //同步
                            success:function(data){
                                //成功后跳转到列表页面
                                location.reload();
                            }
                        });
                    });
                }
            }
        });


        }
    //删除动作
    function del_act(id) {
        //删除询问框
        layer.confirm('确定删除该活动？', {
            btn: ['确定','取消'],//按钮
            offset:'200px'
        },function(){
            //点击确认后执行
            $.ajax({
                url:"<?php echo Yii::app()->createUrl('marketing/marketing/delMarketingActivity'); ?>",
                type:'get',
                data:'id='+id,
                async : false, //同步
                success:function(data){
                    //成功后跳转到列表页面
                    location.reload();
                }
            });
        });
    }
    //提交表单
    function subForm()
    {
        mform.submit();
    }
</script>