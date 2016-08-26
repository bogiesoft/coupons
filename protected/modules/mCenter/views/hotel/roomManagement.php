<div class="kkfm_r_inner">
    <div class="storeManage">
        <div class="top">
            <div class="name">
                房间管理
            </div>
        </div>
        <div class="room-manage">
            <div class="btn_l level_r">
                <a href="<?php echo Yii::app()->createUrl('mCenter/Hotel/AddHotelRoom')?>">
                    <input type="button" value="添加房间" class="btn_com_blue">
                </a>
            </div>
            <div class="recharge">
                <table width="100%" cellspacing="0" cellpadding="0">
                    <tr class="order-title">
                        <td>房间首图</td>
                        <td>房间类型</td>
                        <td>房间价格</td>
                        <td>操作</td>
                    </tr>
                <?php 
                    if (!empty($list)){
                        foreach ($list as $k=>$v){
                ?>
                    <tr>
                        <td><div class="pic"><img src="<?php echo IMG_GJ_LIST.$v['room_img'][0]?>"> </div> </td>
                        <td><?php echo $v['name']?></td>
                        <td><span class="orange fw">￥<?php echo $v['price']?></span> </td>
                        <td>
                            <dl class="operate">
                                <dt>操作<em></em></dt>
                                <dd>
                                    <a href="<?php echo Yii::app()->createUrl('mCenter/Hotel/AddHotelRoom')?>?id=<?php echo $v['id']?>">编辑</a>
                                    <a href="<?php echo Yii::app()->createUrl('mCenter/Hotel/DeleteHotelRoom')?>?id=<?php echo $v['id']?>" onclick="return confirm('确认删除吗？');">删除</a>
                                </dd>
                            </dl>
                        </td>
                    </tr>
                <?php
                    }}
                ?>
                </table>
                <?php 
                if (empty($list)) { 
                    echo '<a href="javascript:;" class="blank-tip">尚未添加房间</a>';
                } 
                ?>
                <div class="tfoot">
                    <div class="r">
                         <?php $this -> widget('CLinkPager',array(
                            'pages'=>$pages,
                            'header'=>'共&nbsp;<span class="yellow">'.$pages -> getItemCount().'</span>&nbsp;条&nbsp;',
                            'prevPageLabel' => '上一页',
                            'nextPageLabel'=>'下一页',
                            'maxButtonCount'=>8
                        ));?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    //操作菜单下拉效果
    $(function(){
        main_obj.list_init();
    })
</script>