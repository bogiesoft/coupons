
<body>
    <div class="kkfm_r_inner">
        <div class="storeManage">
            <div class="top">
                <div class="name">
                    管理员
                </div>
            </div>
            <div class="searchWrap">
                <div class="cz">
                   <a href="<?php echo Yii::app()->createUrl('mCenter/install/addAdmin'); ?>" class="btn_com_blue">添加管理员</a>
                </div>
            </div>
            <div class="bus-contant">
                <table cellspacing="0" cellpadding="0" width="100%">
                    <tr class="thead">
                        <td width="20%">账号</td>
                        <td width="20%">姓名</td>
                        <td width="45%">门店</td>
                        <td align="center">操作</td>
                    </tr>
                    <?php if(!empty($list)){ ?>
                    <?php foreach ($list as $k => $v){ ?>
                    <tr class="tbody">
                        <td><?php echo $v['account']; ?></td>
                        <td><?php echo $v['name']; ?></td>
                        <td><?php echo $v['countStore'].'家'; ?></td>
                        <td align="center">
                           <a href="<?php echo Yii::app()->createUrl('mCenter/install/editManager',array('id'=>$v['id'])); ?>" class="edit_btn">编辑</a>
                           <a href="<?php echo Yii::app()->createUrl('mCenter/install/delManager',array('id'=>$v['id'])); ?>" onclick="return confirm('确定删除？');" class="delete_btn">删除</a>
                        </td>
                    </tr>
                    <?php }?>
                    <?php }?>
                </table>
                 <?php if(empty($list)){ ?>
                    <?php echo '<a href="javascript:;" class="blank-tip">尚未添加管理员</a>';?>
                <?php }?>
            </div>
        </div>
    </div>
</body>

