<!--添加分类弹出框-->
<script type="text/javascript">
    $(document).ready(statistics_obj.stat_init);
</script>
<!--添加分类弹出框-->

<div class="popWrap" id="pop" style="width:766px; display:">
    <div class="pop_con pIndexEdit">
        <div class="title">
            <span class="name">商品分类</span><!--这是“首页设置2选择分类”的头部-->
        	<span class="name" style="display:none"><!--这是“首页设置-添加链接”的头部-->
            	<a href="#" class="blue">商品分组</a> |
                <a href="#">商品</a> |
                <a href="#" class="blue">添加商品</a>
            </span>
           <!-- <a id="closepop" href="" class="close">×</a>-->
        </div>
        <div class="pop_content">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" border="0">
                <tr class="orderHead">
                    <td width="50%">标题 <a href="<?php echo Yii::app()->createUrl('mCenter/dshopMallHome/ShopDialogBody');?>" class="blue">刷新</a></td>
                    <td>创建时间</td>
                    <td>
                        <div class="sh-search clearfix">
                            <?php echo CHtml::beginForm('','get')?>
                            <input name="groupname" type="text" placeholder="请输入分组名称" class="txt"><input type="submit" class="search" value="">
                            <?php echo CHtml::endForm();?>
                        </div>
                    </td>
                </tr>
                <?php if(isset($data)){
                    $num=0;
                    foreach($data as $key=>$value)
                    {
                        $num++;
                ?>
                <tr>
                    <td>
                        <a href="#"><?php echo $value['name']?></a>
                    </td>
                    <td><?php echo $value['create_time']?></td>
                    <td>
                    	<a id="" href="javascript:;" onclick="chooseGroup(<?php echo $value['id']?>,'<?php echo $value['name']?>')" class="btn_com_gray">选取</a>
                    </td>
                </tr>
                <?php }if($num<5&&$num!=0){for($i=0;$i<5-$num;$i++){?>
                    <tr>
                        <td style="font-size:17px"><a href="javascript:;">&nbsp;</a></td>
                        <td></td>
                        <td style="padding: 3px 10px;"></td>
                    </tr>
                <?php }}?>

                <?php }?>

                <!-- 分页开始 -->
                <tr style="border:none">
                    <td style="border:none;text-align:right" colspan="3">
                <?php $this -> widget('CLinkPager',array(
                        'pages'=>$pages,
                        'header'=>'共&nbsp;<span class="yellow">'.$pages -> getItemCount().'</span>&nbsp;条&nbsp;',
                        'prevPageLabel' => '上一页',
                        'nextPageLabel'=>'下一页',
                        'firstPageCssClass' => '',
                        'lastPageCssClass' => '', 
                        'firstPageLabel' => '首页',    
                        'lastPageLabel' => '末页',
                        'maxButtonCount'=>8
                ));?>
                    </td>
                </tr>
                <!-- 分页结束 -->
                </table>
            <input style="display: none" id="type" value="<?php echo $type;?>">
            <input style="display: none" id="num" value="<?php echo $edit_num;?>">
        </div>
    </div>
</div>


<script type="text/javascript">
   
    function chooseGroup(id,name)
    {
        var num=$('#num').val();
        var type=$('#type').val();
        window.parent.window.addGroup(id,name,num,type); //调用父窗口的js方法
    }

</script>
