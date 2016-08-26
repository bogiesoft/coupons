<!--添加分类弹出框-->
<script type="text/javascript">
    $(document).ready(statistics_obj.stat_init);   
</script>
<div class="popWrap" id="pop" style="width:785px; display:">
	<div class="pop_con pIndexEdit">
        <div class="title">
        	<span class="name" style="display:none">商品分类</span><!--这是“首页设置2选择分类”的头部-->
        	<span class="name" style="display:"><!--这是“首页设置-添加链接”的头部-->
            	<a href="<?php echo Yii::app()->createUrl('mCenter/dshopMallHome/dialogHeader',array('shop_type'=>SHOP_TYPE_GROUP,'index'=>$index,'flag'=>$flag)); ?>" class="<?php if(isset($_GET['shop_type'])){echo $_GET['shop_type']==SHOP_TYPE_GROUP?'blue':'';}elseif (empty($_GET['shop_type'])){echo 'blue';} ?>">商品分组</a> |
                <a href="<?php echo Yii::app()->createUrl('mCenter/dshopMallHome/dialogHeader',array('shop_type'=>SHOP_TYPE_PRODUCT,'index'=>$index,'flag'=>$flag,'type'=>$type)); ?>" class="<?php if(isset($_GET['shop_type'])){echo $_GET['shop_type']==SHOP_TYPE_PRODUCT?'blue':'';} ?>">商品</a> |
<!--                 <a href="#" class="">添加商品</a> -->
            </span>
           <!--   <a href="javascript:;" class="close" onclick="closeHeaderWin()">×</a>-->
        </div>
        <div class="pop_content">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" border="0">
                <tr class="orderHead">
                    <td width="50%">标题 <!-- <a href="#" class="blue">刷新</a> --></td>
                    <td>创建时间</td>
                    <!--<td>
                    <?php /*echo CHtml::beginForm(Yii::app()->createUrl('mCenter/shopMallHome/dialogHeader',array('shop_type'=>$shop_type)),'get',array('id'=>'searchForm')); */?>
                    	<div class="sh-search clearfix">
                    	    <?php /*echo CHtml::textField('group_name',isset($_GET['group_name'])?$_GET['group_name']:'',array('class'=>'txt','placeholder'=>'输入商品分组名称')); */?>
                    	   <input type="submit" class="search" value="">
                        </div>
                        <?php /*echo CHtml::endForm(); */?>
                    </td>-->

                    <td>
                        <?php echo CHtml::beginForm(Yii::app()->createUrl('mCenter/dshopMallHome/dialogHeader'),'get',array('id'=>'searchForm')); ?>
                        <div class="sh-search clearfix">
                            <?php if(isset($_GET['shop_type'])){if($_GET['shop_type']==SHOP_TYPE_PRODUCT){?>
                                <?php echo CHtml::textField('group_name',isset($_GET['group_name'])?$_GET['group_name']:'',array('class'=>'txt','placeholder'=>'输入商品名称')); ?>
                            <?php }else{?>
                                <?php echo CHtml::textField('group_name',isset($_GET['group_name'])?$_GET['group_name']:'',array('class'=>'txt','placeholder'=>'输入商品分组名称')); ?>
                            <?php }}else{?>
                            <?php echo CHtml::textField('group_name',isset($_GET['group_name'])?$_GET['group_name']:'',array('class'=>'txt','placeholder'=>'输入商品分组名称')); ?>
                            <?php }?>
                            <input type="text" style="display:none" value="<?php echo $shop_type?>" name="shop_type">
                            <input type="text" style="display:none" value="<?php echo $index?>" name="index">
                            <input type="text" style="display:none" value="<?php echo $flag?>" name="flag">
                            <input type="text" style="display:none" value="<?php echo $type?>" name="type">
                            <input type="submit" class="search" value="">
                        </div>
                        <?php echo CHtml::endForm(); ?>
                    </td>
                </tr>
                <?php if(!empty($list)){$num=0;?>
                <?php foreach ($list as $k=>$v){$num++;?>
                <tr>
                    <td>
                        <a href="#"><?php echo $v['name']; ?></a>   
                    </td>
                    <td><?php echo $v['create_time']; ?></td>
                    <td><a href="javascript:;" onclick="choose(<?php echo $v['id']; ?>,<?php echo $shop_type; ?>,'<?php echo $v['name']; ?>',<?php echo $index; ?>,<?php echo $flag; ?>,'<?php echo $type;?>')" class="btn_com_gray">选取</a></td>
                </tr>
              <?php }if($num<5)for($i=0;$i<5-$num;$i++){?>
                        <tr>
                            <td style="font-size:17px"><a href="javascript:;">&nbsp;</a></td>
                            <td></td>
                            <td style="padding: 3px 10px;"></td>
                        </tr>
              <?php }}else{for($j=0;$j<6;$j++){?>
                    <tr>
                        <td style="font-size:15.3px"><a href="javascript:;">&nbsp;</a></td>
                        <td></td>
                        <td style="padding: 3px 10px;"></td>
                    </tr>
                <?php }}?>
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
        </div> 
    </div>
</div>
 
<script type="text/javascript">
       function choose(id,shop_type,name,index,flag,type)
       {
         if(flag == 1){
    	       window.parent.window.editHeaderUrl(id,shop_type,name,index,type); //调用父窗口的js方法
           }else{
               window.parent.window.addHeaderUrl(id,shop_type,name,index,type);
           }
       }
</script>
