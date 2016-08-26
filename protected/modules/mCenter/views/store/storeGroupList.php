<script type="text/javascript">
	$(document).ready(main_obj.list_init);
</script>

<body>
    <div class="kkfm_r_inner">
        <div class="storeManage">
            <div class="top">
                <div class="name">
                    门店分组
                </div>
            </div>
            <div class="searchWrap">
                <div class="cz">
                    <a href="<?php echo Yii::app()->createUrl('mCenter/store/addGroup'); ?>" class="btn_com_blue">添加分组</a>
                </div>
            </div>
            <div class="bus-contant">
                <table cellspacing="0" cellpadding="0" width="100%">
                    <tr class="thead">
                        <td width="45%">分组信息</td>
                        <td width="40%">收款账号</td>
                        <td align="center">操作</td>
                    </tr>
                    <?php if(!empty($list)){ ?>
                    <?php foreach ($list as $k=>$v){ ?>
                    <tr>
                        <td valign="top" class="tdLeft clearPl">
                            <dl class="list clearfix">
                                <span class="iconPlus"></span>
                                <div class="listGroup">
                                    <dt><em>名称：</em><?php echo $v['name']; ?></dt>
                                    <dt><em>门店：</em><?php echo  $v['countStore']; ?>家</dt>
                                </div>
                            </dl>
                        </td>
                        <td valign="top">
                            <dl class="list">
                                <dt>支付宝收款：
                                   <a href="<?php echo Yii::app()->createUrl('mCenter/store/aliPayMent',array('id' => $v['id'])) ?>"> <ins><?php if(!empty($v['alipay'])){echo $v['alipay'];} ?></ins></a>
                                </dt>
                                <dt><a>微信收款：</a>
                                    <a href="<?php echo Yii::app()->createUrl('mCenter/store/wxPayMent',array('id' => $v['id'])) ?>"> <ins><?php if(!empty($v['wechat'])){echo $v['wechat']; }?></ins></a>
                                </dt>
                            </dl>
                        </td>
                        <td align="center" valign="top">
                            <dl class="operate">
                                <dt>操作</dt>
                                <dd>
                                    <a href="<?php echo Yii::app()->createUrl('mCenter/store/editGroup',array('id'=>$v['id'])); ?>">编辑</a>
                                    <a href="<?php echo Yii::app()->createUrl('mCenter/store/aliPayMent',array('id' => $v['id'])); ?>">收款账号</a>
                                    <?php if(!$v['isHasXJ']){ ?>
                                      <a href="<?php echo Yii::app()->createUrl('mCenter/store/delGroup',array('id'=>$v['id'])); ?>" onclick="return confirm('确定删除吗');">删除</a>
                                    <?php }?>
                                </dd>
                            </dl>
                        </td>
                    </tr>
                    <?php if(!empty($v['xjGroup'])){ ?>
                        <?php foreach ($v['xjGroup'] as $key=>$val){ ?>
                              <tr>
                       <td valign="top" class="clearPl"> 
                           <dl class="list clearfix"> 
                                <span class="icon110"></span>
                               <div class="listGroup">
                                  <dt><em>名称：</em><?php echo $val[$v['id']]['name']; ?></dt>
                                    <dt><em>门店：</em><?php echo $val[$v['id']]['countStore']; ?>家</dt>
                                </div>
                            </dl>
                       </td>
                       <td valign="top">
                            <dl class="list">
                              <dt>支付宝收款：
                                   <a href="<?php echo Yii::app()->createUrl('mCenter/store/aliPayMent',array('id' => $val[$v['id']]['id'])) ?>"> <ins><?php if(!empty($val[$v['id']]['alipay'])){echo $val[$v['id']]['alipay'];} ?></ins> </a>
                                </dt>
                               <dt><a>微信收款：</a>
                                    <a href="<?php echo Yii::app()->createUrl('mCenter/store/wxPayMent',array('id' => $val[$v['id']]['id'])) ?>"> <ins><?php if(!empty($val[$v['id']]['wechat'])){echo $val[$v['id']]['wechat']; }?></ins> </a>
                                </dt>
                            </dl>
                         </td>
                         <td align="center" valign="top">
                            <dl class="operate">
                                 <dt>操作</dt>
                           		<dd>
                                	<a href="<?php echo Yii::app()->createUrl('mCenter/store/editGroup',array('id'=>$val[$v['id']]['id'])); ?>">编辑</a>
                               		<a href="<?php echo Yii::app()->createUrl('mCenter/store/aliPayMent',array('id' => $val[$v['id']]['id'])) ?>">收款账号</a>
                                    <?php if(!$val[$v['id']]['isHasXJ']){ ?>
                                    <a href="<?php echo Yii::app()->createUrl('mCenter/store/delGroup',array('id'=>$val[$v['id']]['id'])); ?>" onclick="return confirm('确定删除吗');">删除</a>
                                   	<?php }?>
                                 </dd>
                            </dl>
                        </td>
                    </tr>
                        <?php }?>
                    <?php }?>
                    <?php }?>
                     <?php }?>
                </table>
                <?php if(empty($list)){ ?>
                    <?php echo '<a href="javascript:;" class="blank-tip">尚未添加分组</a>';?>
                <?php }?>
            </div>
        </div>
    </div>
</body>
<script type="text/javascript">

</script>
