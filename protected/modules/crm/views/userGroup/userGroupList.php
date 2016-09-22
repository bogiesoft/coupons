<script type="text/javascript">
    //$(document).ready(main_obj.list_init);
</script>
<body>
<div class="kkfm_r_inner">
    <div class="storeManage">
        <div class="top">
            <div class="name">
                用户分组管理
            </div>
            <div class="cz btn_r">
                <a href="<?php echo $this->createurl('addUserGroupSelf'); ?>" class="btn_com_blue">添加分组</a>
            </div>
        </div>
        <div class="bus-contant rules">
            <table cellspacing="0" cellpadding="0" width="100%">
                <tr class="thead">
                    <td width="35%">分组名称</td>
                    <td width="15%">类型</td>
                    <td width="15%">人数</td>
                    <td width="15%">百分比</td>
                    <td align="center">操作</td>
                </tr>
                <?php if(!empty($list)){ ?>
                    <?php foreach ($list as $k => $v){ ?>
                        <tr>
                            <td>
                                <?php echo $v['name']; ?>
                            </td>
                            <td>
                                <?php echo $group_types[$v['type']]; ?>
                            </td>
                            <td>
                                <?php echo $v['num']; ?>
                            </td >
                            <td>
                                <?php echo $v['num_bfb']; ?>%
                            </td >
                            <td align="center" class="inline-operate">
                                <a href="<?php echo Yii::app()->createUrl('mCenter/broadcast/broadcasting',array('userGroupId'=>$v['id'])); ?>">发广播</a>
                                <?php if($v['type'] == GROUP_TYPE_CUSTOM) {?>
                                    <a href="<?php echo $this->createUrl('editUserGroupSelf',array('userGroupId'=>$v['id'])); ?>">修改</a>
                                <?php } else {?>
                                    <a href="<?php echo $this->createUrl('editFilterUserGroup',array('userGroupId'=>$v['id'])); ?>">修改</a>
                                <?php }?>

                                <a href="<?php echo $this->createUrl('delUserGroup',array('userGroupId'=>$v['id'])); ?>" onclick="return confirm('确定删除吗')">删除</a>
                            </td>
                        </tr>
                    <?php }?>
                <?php }?>
            </table>
            <?php if(!empty($list)){ ?>
                <div class="r">
                    <div class="page1">
                        <?php $this -> widget('CLinkPager',array(
                            'pages'=>$pages,
                            'header'=>'共&nbsp;<strong class="orange">'.$pages -> getItemCount().'</strong>&nbsp;条&nbsp;',
                            'prevPageLabel' => '上一页',
                            'nextPageLabel'=>'下一页',
                            'maxButtonCount'=>8
                        ));?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
</body>