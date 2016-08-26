<div class="kkfm_r_inner">
	<!--搜索框 count-top包含的部分-->
    <div class="top">
        <div class="name">激活码设置</div>
    </div>

    <div class="bus-contant">
    	<div class="code">
        	<div class="con">手机收银台、Exe收银台、POS机收银台，在首次登录的时候需要验证商户并绑定设备，完成激活操作以保证收银坏境的安全。激活时需要激活码，可根据需求限制激活码的使用次数及有效期</div>
            <div class="cz"><input type="button" value="创建激活码" class="btn_com_blue"></div>
        </div>
    	<table cellspacing="0" cellpadding="0" width="100%" style="margin-top:10px;">
            <tr class="order-title">
                <td align="center" nowrap="nowrap">编号</td>
                <td align="center" nowrap="nowrap">创建时间</td>
                <td align="center" nowrap="nowrap">激活码</td>
                <td align="center" nowrap="nowrap">激活终端数</td>
                <td align="center" nowrap="nowrap">状态</td>
              	<td align="center" nowrap="nowrap">有效期</td>
                <td align="center" nowrap="nowrap">操作</td>
            </tr>
            <?php foreach ($code as $k => $v){?>
            <tr>
                <td align="center" valign="middle"><?php echo $k+1?></td>
                <td align="center" valign="middle"><?php echo $v -> create_time?></td>
                <td align="center" valign="middle"><?php echo $v -> code?></td>
                <td align="center" valign="middle"><?php echo $v -> used_num?>/<?php echo $v -> num?></td>
                <td align="center" valign="middle"><?php echo $GLOBALS['ACTIVATION_CODE_STATUS'][$v -> status]?></td>
                <td align="center" valign="middle"><?php echo date('y.m.d',strtotime($v -> start_time))?>-<?php echo date('y.m.d',strtotime($v -> end_time))?></td>
                <td align="center" valign="middle">
                	<dl class="operate">
	                  	<dt>编辑</dt>
	                  	<dd>
	                  		<?php if($v -> status != ACTIVATION_CODE_STATUS_LOCK){?>
	                      	<a href="<?php echo Yii::app() -> createUrl('mCenter/Store/InvalidCode',array('id' => $v -> id));?>">使失效</a>
	                      	<?php }?>
	                       	<a href="<?php echo Yii::app() -> createUrl('mCenter/Store/DelectCode',array('id' => $v -> id));?>">删除</a>
	                  	</dd>
               		</dl>
               	</td>
            </tr>
            <?php }?>


            	<!-- 分页开始 -->            	
                <tr style="border:none">
                <td style="border:none;text-align:right" colspan="7">
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

<!--弹出框-->
<div id="popShadow" style="display:none"></div>
<div class="popWrap" id="pop" style="width:580px; display:none">
	<?php echo CHtml::beginForm(Yii::app()->createUrl('mCenter/Store/CreateActivationCode'),'get',array('id'=>'createCode')); ?>      
    <div class="pop_con code">
        <div class="pop_content">
        	<div class="title">创建激活码</div>
            <div class="filed">
                 <span class="label">可使用次数</span>
                 <span class="text"><input type="tel" class="txt" name="num" onkeyup="this.value=this.value.replace(/\D/g,'');if(this.value != ''){$('#error_num').hide();}"  onafterpaste="this.value=this.value.replace(/\D/g,'')"></span>
                 <span class="remind">请填写激活码可使用次数</span>
                 <span class="error red" style="display: none" id="error_num">请输入可使用次数</span>
                 
             </div>
             <div class="filed">
             	<span class="label">激活码有效期</span>
                 <span class="text"><input type="tel" class="txt" name="Time" readOnly="true" onchange="if(this.value != ''){$('#error_time').hide();}else{$('#error_time').show();}"></span>
                 <span class="remind">请选择激活码有效期，最长为7天</span>
                 <span class="error red" style="display: none" id="error_time">请选择激活码有效期</span>
             </div>
             <div class="bottom">
			 	 <input type="button" class="btn_com" value="确定" id="createCode_btn">
                 <input type="button" class="btn_com_blue" value="取消" id="cancel_btn">
             </div>
        </div>
    </div>   
   	<?php echo CHtml::endForm(); ?>
</div>

<script type="text/javascript">
$(document).ready(statistics_obj.stat_init);

$(document).ready(main_obj.list_init);

function setpopsize(){
	var _left = ($(window).width()-$('#pop').width())/2; 
	var _top = ($(window).height()-$('#pop').height())/2; 
	$('#pop').css({'left' : _left, 'top' : _top - 100});
};	
//显示弹出窗
$('.btn_com_blue').click(function(){
	setpopsize();
	$('.popWrap').show();
});
//关闭弹出窗
$('#cancel_btn').click(function(){
	$('.popWrap').hide();
	$('#error_time').hide();
	$('#error_num').hide();
});
//创建验证码
$('#createCode_btn').click(function(){
	var flag = true;
	if($('input[name=num]').val() == ''){
		$('#error_num').show();
		flag = false;
	}
	if($('input[name=Time]').val() == ''){
		$('#error_time').show();
		flag = false;
	}
	if(flag){
		$('form[id=createCode]').submit();
	}
});

$('input[name=Time]').change(function(){
	if($('input[name=Time]').val() == ''){
		$('#error_time').show();
	}else{
		$('#error_time').hide();
	}
});

$('input[name=num]').change(function(){
	if($('input[name=num]').val() == ''){
		$('#error_num').show();
	}else{
		$('#error_num').hide();
	}
});

//时间选择控件
$(function(){
	var now = new Date();
	var max = new Date(now.getTime()+7*24*60*60*1000);
	$('input[name=Time]').daterangepicker({
        timePicker: false,
        format: 'YYYY/MM/DD',
        dateLimit: true,
        maxDate: max.toLocaleDateString(),
        minDate:now.toLocaleDateString()
    });
});


</script>