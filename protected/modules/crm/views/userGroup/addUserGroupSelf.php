<script type="text/javascript">
	$(document).ready(main_obj.list_init);
</script>
<div class="kkfm_r_inner">
    <div class="storeManage">
        <div class="top">
            <div class="name">
                新建分组
            </div>
        </div>
        <div class="bus-contant new_group_wrap">
            <ul class="wq_btn_group ">
                <li onClick="window.self.location.href='<?php echo $this->createUrl('addFilterUserGroup') ?>'">根据条件筛选</li>
                <li class="active" onClick="window.self.location.href='<?php echo $this->createUrl('addUserGroupSelf'); ?>'">自定义分组</li>
            </ul>
           <?php echo CHtml::beginForm(Yii::app()->createUrl($this->route),'post'); ?>
             <div class="filtrate_group">
                <div class="new_group">
                    <div class="new_group_item">
                        <h3 class="first_title">分组名称</h3>
                        <div class="new_group_item_c">
                            <?php echo CHtml::textField('UserGroup[name]',isset($_POST['UserGroup']['name'])?$_POST['UserGroup']['name']:'',array('class'=>'text_group','placeholder'=>'请输入分组名称，最多9个汉字')); ?>
                            <?php if(Yii::app()->user->hasFlash('name_error')){ ?>
                            <span class="error1"><?php echo Yii::app()->user->getFlash('name_error'); ?></span>
                            <?php }?>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="btn level_r">
                    <input type="submit" class="btn_com_blue" value="保存">
<!--                         <input type="submit" class="btn_com_gray" value="取消"> -->
                </div>
                <?php echo CHtml::endForm(); ?>
            </div>
        </div>
    </div>
</div>