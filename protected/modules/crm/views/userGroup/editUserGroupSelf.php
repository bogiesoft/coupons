
<body>
    <div class="kkfm_r_inner">
        <div class="storeManage">
            <div class="top">
                <div class="name">
                    修改分组
                </div>
            </div>
            <div class="bus-contant new_group_wrap">
                <div class="btn_l level_r">
                  <!--<a href="<?php echo $this->createUrl('addUserGroupSearch'); ?>" class="btn_com_gray btn_gray_group">根据条件筛选</a>-->
                  <a href="<?php //echo Yii::app()->createUrl('mCenter/user/addUserGroupSelf'); ?>" class="btn_com_blue btn_blue_group">修改分组</a>
                </div>
               <?php echo CHtml::beginForm('','post'); ?>
                 <div class="filtrate_group">
                    <div class="new_group">
                        <div class="new_group_item">
                            <h3 class="first_title">分组名称</h3>
                            <div class="new_group_item_c">
                                <?php echo CHtml::textField('UserGroup[name]',$model['name'],array('class'=>'text_group','placeholder'=>'请输入分组名称，最多9个汉字')); ?>
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
</body>