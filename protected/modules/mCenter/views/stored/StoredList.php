
<script type="text/javascript">
	$(document).ready(main_obj.list_init);
//        $(document).ready(statistics_obj.stat_init);//时间选择弹出框
    $(function(){
        var now = new Date();
        $('input[name=Time]').daterangepicker({
            timePicker: false,
            format: 'YYYY/MM/DD',
            dateLimit: true,
            maxDate: true,
            minDate:now.toLocaleDateString()
        });
    });

</script>

<div class="kkfm_r_inner">
	<!--搜索框 count-top包含的部分-->
    <div class="top">        
        <form name="stored" action="<?php echo Yii::app()->createUrl('mCenter/user/basicSet')?>" method="post">
        <div class="name">
        	添加储值活动               
                <span><?php echo CHtml::checkBoxlist('Merchant[if_stored]', isset($merchant ->if_stored) ?$merchant ->if_stored : IF_STORED_NO, $if_stored_array,array('onchange'=>'storedlis_fun()')); ?></span>
        </div>
        </form>        
        <div class="cz"><a href="javascript:;" onclick="pop()" class="btn_com_blue">添加储值活动</a></div>
    </div>
	<div class="">
        <div class="recharge">
            <table width="100%" cellspacing="0" cellpadding="0">
              	<tr class="order-title" >
                    <td>活动名</td>
                    <td>预存金额</td>
                    <td>赠送金额</td>
                    <td>活动时间</td>
                    <td>操作</td>
              	</tr>
                <?php if (!empty($list)) {
            	foreach ($list as $k => $v) { ?>
              	<tr class='<?php if(($k+1)%2==0){echo "bg";}else{echo "";} ?>' style="border-bottom:1px solid #dedede">
                    <td><?php echo $v['name'];?></td>
                    <td><?php echo $v['stored_money'];?></td>
                    <td><?php echo $v['get_money'];?></td>
                    <td><?php echo date('Y.m.d',strtotime($v['start_time'])).' - '.date('Y.m.d',strtotime($v['end_time']));?></td>
                    <td><a href="<?php echo Yii::app()->createUrl('mCenter/stored/delStored', array('id' => $v['id']))?>" onclick="return confirm('删除后不可恢复，继续吗？');"><img src="<?php echo GJ_STATIC_IMAGES?>del.png"></a></td>
              	</tr>
                <?php } } ?>
                <!-- 分页开始 -->
                <?php if($pages -> getItemCount() > 10){?>            	
                <tr style="border:none">
                <td style="border:none;text-align:right" colspan="5">
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
                <?php }?>
                <!-- 分页结束 -->
            </table>
            <?php 
                if (empty($list)){
                    echo '<a href="javascript:;" class="blank-tip">尚未添加活动</a>';
                }
            ?>
        </div>
    </div>
</div> 


<!--添加储值弹出框-->
<div class="popWrap" id="pop" style="width:580px; top:20%; left:40%; margin-left:-200px; <?php if(Yii::app()->user->hasFlash('stored_money') || Yii::app()->user->hasFlash('get_money') || Yii::app()->user->hasFlash('time')){ } else { echo 'display:none';}?>">
	<div class="pop_con">
        <div class="title">添加储值活动</div>
        <?php echo CHtml::beginForm('','post',array('id'=>'addform'));?>
        <div class="pop_content popStored">
            <div class="fz">
                活动名
                <?php echo CHtml::textField('Stored[name]',isset($_POST['Stored']['name']) ? $_POST['Stored']['name'] : '',array('class'=>'txt','maxlength'=>'100','style'=>'color:#999; width:315px;'))?>
                    <div id="stored_name" class="error" style="display:none"><?php echo Yii::app()->user->getFlash('name'); ?></div>
            </div>
            <div class="fz">
            	预存金额        		
                        <?php echo CHtml::textField('Stored[stored_money]',isset($_POST['Stored']['stored_money']) ? $_POST['Stored']['stored_money'] : '',array('class'=>'txt','style'=>'color:#999; width:120px;','maxlength'=>'10','onkeyup'=>"", 'onbeforepaste'=>"clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"))?>
                        
                赠送金额
        		<?php echo CHtml::textField('Stored[get_money]',isset($_POST['Stored']['get_money']) ? $_POST['Stored']['get_money'] : '',array('class'=>'txt','style'=>'color:#999; width:120px;','maxlength'=>'10','onkeyup'=>"", 'onbeforepaste'=>"clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"))?>
                            <div id="stored_money" class="error" style="display:none"></div>
            </div>
            <div class="fz">
            	活动时间
        		<?php echo CHtml::textField('Time',isset($_POST['Time']) ? $_POST['Time'] : '',array('class'=>'txt','style'=>'color:#999; width:315px;','readonly'=>"readonly")); ?>
                        <div id="stroed_time" class="error" style="display: none;"></div>
            </div>
        </div>  
        <div class="bottom">
        	<input type="button" onclick="storedlist_sub('<?php echo(Yii::app()->createUrl('mCenter/Stored/CheckStored'));?>');" value="保存" class="btn_com_blue">
                <input type="button" onclick="hiddenpop()" value="取消" class="btn_com_gray">
        </div>
    </div>
    <?php echo CHtml::endForm();?>
</div>

<script>
        //显示弹出框
    function pop()
    {
        if($('#Merchant_if_stored_0').attr('checked'))
        {
            $('#pop').show();
        }else{
            confirm('请先开启储值功能');
        }
    }
    //隐藏弹出框
    function hiddenpop()
    {
    	$('#Stored_name').val('');
        $('#Stored_stored_money').val('');
        $('#Stored_get_money').val('');
        $('#Time').val('');

        $('#stored_name').attr('style','display:none');
        $('#stored_money').attr('style','display:none');
        $('#stroed_time').attr('style','display:none');
        
        $('#pop').hide();
    }


    $(function(){
        storedlist_storedname('<?php echo(Yii::app()->createUrl('mCenter/Stored/CheckStored'));?>');
        storedlist_storedmoney('<?php echo(Yii::app()->createUrl('mCenter/Stored/CheckStored'));?>');
        storedlist_getmoney('<?php echo(Yii::app()->createUrl('mCenter/Stored/CheckStored'));?>');
    });
</script>
<script src="<?php echo GJ_STATIC_JS."gj.js"?>"></script>