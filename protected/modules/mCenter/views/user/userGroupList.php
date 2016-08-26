<script type="text/javascript">
    $(document).ready(main_obj.list_init);
</script>
<body>
<div class="kkfm_r_inner">
	<div class="storeManage">
        <div class="top">
            <div class="name">
               用户分组管理
            </div>
            <div class="cz btn_r">
            <a href="<?php echo Yii::app()->createurl('mCenter/user/addUserGroupSelf'); ?>" class="btn_com_blue">添加分组</a>
            </div>
        </div>
        <div class="bus-contant rules">
            <table cellspacing="0" cellpadding="0" width="100%">
                <tr class="thead">
                    <td width="28.33%">分组名称</td>
                    <td width="28.33%">人数</td>
                    <td width="28.33%">百分比</td>
                    <td align="center">操作</td>
                </tr>
                <?php if(!empty($list)){ ?> 
                <?php foreach ($list as $k => $v){ ?>       
                <tr>
                    <td valign="top">
                    	<dl class="list">
                        	<dt><?php echo $v['name']; ?></dt>
                        </dl>
                    </td>
                    <td valign="top">
                    	<dl class="list">
                        	<dt><?php echo $v['per_count']; ?></dt>
                        </dl>
                    </td >
                     <td valign="top">
                        <dl class="list">
                            <dt><?php echo $v['percentage_per_count']; ?>%</dt>
                        </dl>
                    </td >
                    <td align="center" valign="top">
                        <dl class="operate">
                            <dt>操作</dt>
                            <dd>
                                <a href="<?php echo Yii::app()->createUrl('mCenter/broadcast/broadcasting',array('userGroupId'=>$v['id'])); ?>">发广播</a>
                                <a href="<?php echo Yii::app()->createUrl('mCenter/user/editUserGroupSelf',array('userGroupId'=>$v['id'])); ?>">修改</a>
                                <a href="<?php echo Yii::app()->createUrl('mCenter/user/delUserGroup',array('userGroupId'=>$v['id'])); ?>" onclick="return confirm('确定删除吗')">删除</a>
                            </dd>                    	
                        </dl>
                    </td>
                </tr>
                 <?php }?>
                 <?php }?>
            </table>
        </div>
    </div>
</div> 
</body>