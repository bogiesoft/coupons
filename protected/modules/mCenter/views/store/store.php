<script type="text/javascript">
	$(document).ready(main_obj.list_init);
</script>

<div class="kkfm_r_inner">
    <div class="top">
        <div class="name">
        <?php echo CHtml::beginForm(Yii::app()->createUrl($this->route), 'get')?>
        	<input type="text" class="txt" name="keyword" value="" placeholder="请输入门店名称或编号" />
        	<input type="submit" value="搜索" class="search">
       	<?php echo CHtml::endForm()?>
        </div>
        <div class="cz"><input type="submit" value="添加门店" class="btn_com_blue" onclick="location.href='<?php echo Yii::app()->createUrl('mCenter/store/addStore')?>'"></div>
    </div>

    <div class="bus-contant">
    	<table cellspacing="0" cellpadding="0" width="100%" style="margin-top:10px;">
        	
            <tr class="order-title">
                <td align="center" nowrap="nowrap">编号</td>
                <td align="center" nowrap="nowrap">门店名称</td>
                <td align="center" nowrap="nowrap">城市-区域</td>
                <td align="center" nowrap="nowrap">电话</td>
                <td align="center" nowrap="nowrap">操作</td>
            </tr>
            
            <?php if (!empty($list)) {?>
            	<?php foreach ($list as $k => $v) { ?>
		            <tr>
		                <td align="center" valign="middle"><span class="fw"><?php echo $v['number']?></span></td>
		                <td align="center" valign="middle"><?php echo $v['name']?></td>
		                <td align="center" valign="middle"><?php $add = explode(",", $v['address']); echo $add[0].$add[1].$add[2].$add[3];?></td>
		                <td align="center" valign="middle"><?php echo $v['telephone']?></td>
		                <td align="center" valign="middle">
			                <dl class="operate">
			                	<dt>操作</dt>
			                    <dd>
			                    	<a href="<?php echo Yii::app()->createUrl('mCenter/store/editStore', array('id' => $v['id']))?>">编辑</a>
			                    	<a href="<?php echo Yii::app()->createUrl('mCenter/operator/addOperator')?>">添加操作员</a>
			                    	<a href="<?php echo Yii::app()->createUrl('mCenter/store/deleteStore', array('id' => $v['id']))?>" onclick="if(!confirm('确认删除?')){return false;}">删除</a>
			                    </dd>                    	
			                </dl>
		                </td>
		            </tr>
            	<?php }}else{?>
            	<tr>
					<td colspan="5" align="center">尚未添加门店</td>
				</tr>
            	<?php }?>

                	<tr style="border:none">
                	<td style="border:none;text-align:right" colspan="5">
                    	<?php $this -> widget('CLinkPager',array(
                    			'pages'=>$pages,
                                'header'=>'共&nbsp;<span class="yellow">'.$pages -> getItemCount().'</span>&nbsp;条&nbsp;',
                                'prevPageLabel' => '上一页',
                                'nextPageLabel'=>'下一页',
                                'maxButtonCount'=>8
                    	));?>
                    </td>
                   	</tr>
        </table>
  	</div>
</div> 
