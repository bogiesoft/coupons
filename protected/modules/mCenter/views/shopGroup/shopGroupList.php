<div class="kkfm_r_inner">
    <!--搜索框 count-top包含的部分-->
    <div class="top">
        <div class="name">商品分组</div>
    </div>
    <div class="shop_menu">
    	<ul>
        	<li>
        		<a href="<?php echo Yii::app()->createUrl('mCenter/ShopProduct/ProductList') ?>">商品管理</a>
        	</li>
        	<li class="cur">
        		<a href="<?php echo Yii::app() -> createUrl('mCenter/ShopGroup/ShopGroupList');?>">商品分组</a>
        	</li>
        </ul>
    </div>
    <div class="contant">
    	<div class="cz"><a href="javascript:;" onclick="pop()" class="btn_com_blue">添加分组</a></div>
        <?php echo CHtml::beginForm(Yii::app()->createUrl($this->route), 'get', array('id' => 'search_form'))?>
        <div class="sh-search clearfix">
            <input type="text" placeholder="请输入分组名称" class="txt" name="groupname" value="">
            <input type="submit" class="search" value="">
        </div>
        <?php echo CHtml::endForm()?>

        <?php echo CHtml::beginForm(Yii::app()->createUrl($this->route), 'get', array('id' => 'list_form'))?>
        <div class="contant freightMode">
        <div class="recharge">
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr class="order-title" >
                <td bgcolor="#f5f5f5">分组名称</td>
                <td bgcolor="#f5f5f5">商品数</td>
                <td bgcolor="#f5f5f5">创建时间</td>
                <td bgcolor="#f5f5f5">操作</td>
            </tr>

            <?php if(isset($data)&&!empty($data)){
                foreach($data as $key=>$value)
                {
            ?>
            <tr>
                <td><?php echo $value['name']?></td>
                <td><?php echo $value['count']?></td>
                <td><?php echo date('Y.m.d H:i:s',strtotime($value['create_time']))?></td>
                <td>
                    <a href="javascript:;" onclick="pop1(<?php echo $value['id']?>,'<?php echo $value["name"]?>')">编辑</a> 
                    <a href="<?php echo Yii::app()->createUrl('mCenter/shopGroup/deleteShopGroup',array('id'=>$value['id'],'gourl'=>Yii::app() -> request -> url));?>" onclick="return confirm('确定此操作吗？');"><span>删除</span></a> 
                    <!--a href="">推广</a-->
                </td>
            </tr>
            <?php }}?>
            	<!-- 分页开始 -->
                <?php if($pages) { ?>
                <tr style="border:none">
                <td style="border:none;text-align:right" colspan="4">
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
                <?php } ?>                
                <!-- 分页结束 -->
        </table>
        </div>
        </div>
        <?php echo CHtml::endForm()?>
    </div>
    <!--添加分组弹出框-->
<div class="popWrap" id="pop" style="width:580px; top:20%; left:40%; margin-left:-200px;<?php if(Yii::app()->user->hasFlash('name')){ } else { echo 'display:none';}?>">
	<div class="pop_con">
        <div class="title">添加分组</div>
        <?php echo CHtml::beginForm();?>
        <div class="pop_content popStored">
            <div class="fz">
            	分组名称:        		
                        <?php echo CHtml::textField('ShopGroup[name]',isset($_POST['ShopGroup']['name']) ? $_POST['ShopGroup']['name'] : '',array('class'=>'txt','style'=>'color:#999; width:120px;'))?>
                        <?php if(Yii::app()->user->hasFlash('name')) { ?>
                            <div class="error"><?php echo Yii::app()->user->getFlash('name'); ?></div>
                        <?php } ?>                        
            </div>           
        </div>  
        <div class="btn">
        	<input type="submit" value="保存" class="btn_com_blue">
                <input type="button" onclick="hiddenpop()" value="取消" class="btn_com_gray">
        </div>
    </div>
    <?php echo CHtml::endForm();?>
</div>
    <!--修改分组弹出框-->
<div class="popWrap" id="pop1" style="width:580px; top:20%; left:40%; margin-left:-200px;<?php if(Yii::app()->user->hasFlash('names')){ } else { echo 'display:none';}?>">
	<div class="pop_con">
        <div class="title">修改分组</div>
        <?php echo CHtml::beginForm();?>
        <div class="pop_content popStored">
            <div class="fz">
            	分组名称:   
                        <input name="id" type="hidden" value="">  
                        <input name="gourl" type="hidden" value="<?php echo Yii::app() -> request -> url?>">
                        <?php echo CHtml::textField('ShopGroup[name]',isset($_POST['ShopGroup']['name']) ? $_POST['ShopGroup']['name'] : '',array('class'=>'ShopGroup_name','style'=>'color:#999; width:120px;'))?>
                        <?php if(Yii::app()->user->hasFlash('names')) { ?>
                            <div class="error"><?php echo Yii::app()->user->getFlash('names'); ?></div>
                        <?php } ?>                        
            </div>           
        </div>  
        <div class="btn">
        	<input type="submit" value="保存" class="btn_com_blue">
                <input type="button" onclick="hiddenpop()" value="取消" class="btn_com_gray">
        </div>
    </div>
    <?php echo CHtml::endForm();?>
</div>
</div>
<script>
    //显示弹出框
    function pop()
    {
        $('#pop').show("slow");
    }
    function pop1(id,name)
    {        
        $('input[name="id"]').val(id);   
        $('input[class="ShopGroup_name"]').val(name);
        $('#pop1').show("slow");
    }
    //隐藏弹出框
    function hiddenpop()
    {
        $('#pop').hide(1000);
        $('#pop1').hide(1000);
    }

</script>