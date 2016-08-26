<script type="text/javascript">
	$(document).ready(main_obj.list_init);
</script>

<div class="kkfm_r_inner">

    <div class="top">
        <div class="name">操作员列表</div>
    </div>

    <div class="bus-contant">
    	<?php echo CHtml::beginForm(Yii::app()->createUrl($this->route), 'get')?>
        	<?php echo CHtml::dropDownList('store_id', isset($_GET['store_id']) ? $_GET['store_id'] : '', $stores,array('class'=>'txt','prompt' => '请选择')); ?>
        	<input type="text" class="txt" name="keyword" style="color:#999; width:186px;" value="<?php echo $keyword;?>" placeholder="请输入操作员名称或编号" />
        	<input type="submit" value="搜索">
	        <div class="cz">
		        <input type="button" value="操作员日志" class="btn_com_blue" onclick="location.href='<?php echo Yii::app()->createUrl('mCenter/operator/operatorLog')?>'">
		        <input type="button" value="添加操作员" class="btn_com_blue" onclick="fixmenu();location.href='<?php echo Yii::app()->createUrl('mCenter/operator/addOperator')?>'">
	        </div>
        <?php echo CHtml::endForm()?>
    
    	<table cellspacing="0" cellpadding="0" width="100%" style="margin-top:10px;">
        	
            <tr class="order-title">
                <td align="center" nowrap="nowrap">编号</td>
                <td align="center" nowrap="nowrap">所属门店</td>
                <td align="center" nowrap="nowrap">姓名</td>
                <td align="center" nowrap="nowrap">账号</td>
                <td align="center" nowrap="nowrap">角色</td>
                <td align="center" nowrap="nowrap">状态</td>
                <td align="center" nowrap="nowrap">操作</td>
            </tr>
            
            <?php if (!empty($list)) {
            	foreach ($list as $k => $v) { ?>
		            <tr>
		                <td align="center" valign="middle"><span class="fw"><?php echo $v['number']?></span></td>
		                <td align="center" valign="middle"><?php echo $v['store']?></td>
		                <td align="center" valign="middle"><?php echo $v['name']?></td>
		                <td align="center" valign="middle"><?php echo $v['account']?></td>
		                <td align="center" valign="middle"><?php echo $GLOBALS['__OPERATOR_ROLE'][$v['role']] ?></td>
		                <td align="center" valign="middle"><span class="fw red"><?php echo $GLOBALS['__OPERATOR_STATUS'][$v['status']] ?></span></td>
		                <td align="center" valign="middle">
			                <dl class="operate">
			                	<dt>操作</dt>
			                    <dd>
			                    	<a href="<?php echo Yii::app()->createUrl('mCenter/operator/editOperator', array('id' => $v['id']))?>">编辑</a>
			                    	<a href="<?php echo Yii::app()->createUrl('mCenter/operator/changeStatus', array('id' => $v['id'], 'status' => $v['status']))?>" onclick="if(!confirm('确认操作?')){return false;}">
			                    	<?php echo OPERATOR_STATUS_NORMAL == $v['status'] ? '锁定' : '解锁' ?>
			                    	</a>
			                    	<a href="<?php echo Yii::app()->createUrl('mCenter/operator/deleteOperator', array('id' => $v['id']))?>" onclick="if(!confirm('确认删除?')){return false;}">删除</a>
			                    </dd>                    	
			                </dl>
		                </td>
		            </tr>
        	<?php }
                }
            ?>
			<!-- 分页开始 -->	
                	<tr style="border:none">
                	<td style="border:none;text-align:right" colspan="7">
                    	<?php $this -> widget('CLinkPager',array(
                    			'pages'=>$pages,
                                'header'=>'共&nbsp;<span class="yellow">'.$pages -> getItemCount().'</span>&nbsp;条&nbsp;',
                                'prevPageLabel' => '上一页',
                                'nextPageLabel'=>'下一页',
                                'maxButtonCount'=>8
                    	));?>
                    	</td>
                   	</tr>  	
          	<!-- 分页结束 -->
        </table>
        <?php 
            if (empty($list)){
                echo '<a href="javascript:;" class="blank-tip">尚未添加操作员</a>';
            }
        ?>
       </div>
</div> 
<script>
    //更改导航位置
    function fixmenu(){
        var obj = window.parent.document.getElementsByClassName('bg');
        var obj2 = obj[0].getElementsByTagName('a');
        for(i=0;i<obj2.length;i++){
            obj2[i].className = '';

        }
        obj2[0].className = 'cur';
    }
</script>