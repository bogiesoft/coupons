<script type="text/javascript">
	$(document).ready(main_obj.list_init);
	$(document).ready(function() {
		$('#datepicker1').daterangepicker({
		    singleDatePicker: true,
		    format: 'MM/DD'

		},  function(start, end, label) {
			var time1 = start.format('MM');
			var time2 = start.format('DD');
		    $('#changetime').html(time1+'月'+time2+'号');
		  });

	    $('#datepicker2').daterangepicker({
	        singleDatePicker: true,
	        format: 'MM/DD'
	    });

	});
</script>
    <div class="kkfm_r_inner">
        <div class="storeManage">
            <div class="top">
                <div class="name">
                    积分设置
                </div>
            </div>
            <div class="bus-contant rules">
                <table cellspacing="0" cellpadding="0" width="100%">
                    <tr class="thead">
                        <td width="20%">动作名称</td>
                        <td width="20%">奖励周期</td>
                        <td width="20%">奖励次数</td>
                        <td width="25%">奖励积分</td>
                        <td align="center">操作</td>
                    </tr>
<!--                    <tr>
                        <td>注册会员</td>
                        <td>一次性</td>
                        <td>1</td>
                        <td>10</td>
                        <td align="center" class="inline-operate">
                            <a href="#">编辑</a>
                            <a href="#">停用</a>
                        </td>
                    </tr>
                    <tr>
                        <td>完善资料</td>
                        <td>一次性</td>
                        <td>1</td>
                        <td>10</td>
                        <td align="center" class="inline-operate">
                            <a href="#">编辑</a>
                            <a href="#">停用</a>
                        </td>
                    </tr>
                    <tr>
                        <td>签到</td>
                        <td>每天</td>
                        <td>1</td>
                        <td>10</td>
                        <td align="center" class="inline-operate">
                            <a href="#">编辑</a>
                            <a href="#">停用</a>
                        </td>
                    </tr>-->
                    <tr>
                        <td>储值</td>
                        <td>不限周期</td>
                        <td>不限次数</td>
                        <td>每1元奖励<?php echo !empty($stored_points)||isset($stored_points)?$stored_points:'X' ?>积分</td>
                        <td align="center" class="inline-operate">
                        	<?php if ($storedflag == 1){?>
                            	<a href="javascript:void(0);" onclick="showStored()">编辑</a>
                            	<a href="<?php echo $this->createUrl('stop',array('type'=>1)) ?>">停用</a>
                        	<?php }elseif($storedflag == 2){?>
                        		<a href="<?php echo $this->createUrl('use',array('type'=>1)) ?>">启用</a>
                        	<?php }else {?>
                        		<a href="javascript:void(0);" onclick="showStored()">编辑</a>
                        	<?php }?>
                        </td>
                    </tr>
                    <tr>
                        <td>消费</td>
                        <td>不限周期</td>
                        <td>不限次数</td>
                        <td>每1元奖励<?php echo $default['points_ratio']?>积分</td>
                        <td align="center" class="inline-operate">
                            <a href="javascript:void(0);" onclick="showconsume()">编辑</a>
                            <!--<a href="#">停用</a>-->
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><!--<a href="<?php echo Yii::app()->createUrl('mCenter/integral/ClearIntegral') ?>">执行清理</a>--></td>
                        <td align="center" class="inline-operate">
                            <a href="#"></a>
                            <a href="#"></a>
                        </td>
                    </tr>
                    <tr>
                        <td>积分清理</td>
                        <td>每年</td>
                        <td>1次</td>
                        <td>每年<?php echo !empty($clean_start_time)?$clean_start_time:'X月X号' ?>清理<?php echo !empty($clean_date_type)?$clean_date_type:'当年';echo !empty($clean_date)?$clean_date:'X月X号';?>前的积分</td>
                        <td align="center" class="inline-operate">
							<?php if ($clearflag == 1){?>
                            	<a href="javascript:void(0);" onclick="showclearpoints()">编辑</a>
                            	<a href="<?php echo $this->createUrl('stop',array('type'=>2)) ?>">停用</a>
                        	<?php }else if($clearflag == 2){?>
                        		<a href="<?php echo $this->createUrl('use',array('type'=>2)) ?>">启用</a>
                        	<?php }else {?>
                        		<a href="javascript:void(0);" onclick="showclearpoints()">编辑</a>
                        	<?php }?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <!-- 储值弹出框 -->
        <?php echo CHtml::beginForm($this->createUrl('SetStoredPoints'), 'get')?>
        <div class="popWrap new_popWrap rules_pop_content" id="Stored" style="display: none;">
            <div class="pop_con">
                <div class="title">
                    规则设置
                </div>
                <div class="pop_content new_pop_content">
                    <div class="setting_content">
                        <div class="setting_item">
                            <span>规则名称</span>
                            <label>储值</label>
                        </div>
                        <div class="setting_item bonus">
                            <span>奖励积分</span>
                            <label>每储值1元，</label>
                            <label>奖励
                                <input type="text" name="storedpoints" id="stored_input" value="<?php echo $stored_points?>" class="s_input">积分
                            </label>
                            <div class="remark new_remark">
                                <input type="checkbox" name="is_share" <?php if ($is_share == 2){echo 'checked';}?> id="points">
                                <label for="points">使用储值付款时，同时享受消费积分</label>
                            </div>
                        </div>
                    </div>
                    <div class="btn level_r">
                        <input type="submit" class="btn_com_blue" value="保存" onClick='javascript:return storedcheck();'>
                        <input type="button" class="btn_com_gray" value="返回">
                    </div>
                </div>
            </div>
        </div>
        <?php echo CHtml::endForm();?>
        <!-- 积分清理弹出框 -->
        <?php echo CHtml::beginForm($this->createUrl('setClearpoints'), 'get')?>
        <div class="popWrap new_popWrap rules_pop_content" id="clearpoints" style="display: none;">
            <div class="pop_con">
                <div class="title">
                    规则设置
                </div>
                <div class="pop_content new_pop_content">
                    <div class="setting_content">
                        <div class="setting_item">
                            <span>规则名称</span>
                            <label>积分清理</label>
                        </div>
                        <div class="setting_item">
                            <span>清理周期</span>
                            <!--<input type="checkbox" name="period[0]" id="period_2">
                            <label for="week">每周</label>
                            <input type="checkbox" name="period[1]" id="period_1">
                            <label for="day">每月</label>-->
                            <input type="checkbox" name="period[2]" checked="checked" "period_0">
                            <label for="year">每年</label>                            
                        </div>
                        <div class="setting_item">
                            <span>清理时间</span>
                            <input type="text"  name="execute_time" class="s_input" id="datepicker1" value="<?php echo $clean_start_time?>">
                        </div>
                        <div class="setting_item bonus">
                            <span>清理规则</span>
                            <label>每年 <label id="changetime"><?php echo isset($clean_start_time)?$clean_start_time:'X月X号' ?></label>，清理
                                <select class="s_input" name="beforeyear" id="clear_select">
                                	<option value="0">当年</option>
                                    <option value="1">一年前</option>
                                    <option value="2">二年前</option>
                                    <option value="3">三年前</option>
                                </select>
                                <input type="text" name="beforetime" placeholder="" class="s_input" id="datepicker2" value="<?php echo $clean_date?>">
                                之前的积分
                            </label>
                        </div>
                    </div>
                    <div class="btn level_r">
                        <input type="submit" class="btn_com_blue" value="保存">
                        <input type="button" class="btn_com_gray" value="返回">
                    </div>
                </div>
            </div>
        </div>
        <?php echo CHtml::endForm();?>
        <!-- 消费弹出框 -->
        <?php echo CHtml::beginForm($this->createUrl('IntegralSet'), 'get')?>
        <div class="popWrap new_popWrap rules_pop_content" id="consume" style="display: none;">
            <div class="pop_con">
                <div class="title">
                    规则设置
                </div>
                <div class="pop_content new_pop_content">
                    <div class="setting_content">
                        <div class="setting_item">
                            <span>规则名称</span>
                            <label>消费</label>
                        </div>
                        <div class="setting_item">
                            <span>奖励积分</span>
                            <ul class="reward_points">
                                <li>
                                    <label><?php echo $default['name']?><em>每消费1元，奖励</em><input type="text" class="s_input" id="default" name="<?php echo $default['id']?>" value="<?php echo $default['points_ratio']?>">积分</label>
                                </li>
                                <?php foreach ($another as $k => $v){?>
                                <li>
                                    <label><?php echo $v['name']?><em>每消费1元，奖励</em><input type="text" class="s_input" name="<?php echo $v['id']?>" value="<?php echo $v['points_ratio']?>">积分</label>
                                </li>
								<?php }?>

                            </ul>
								<span class="text1 red" id="points_check">
                                    <?php if(Yii::app()->user->hasFlash('points_check')) { ?>
                                    <?php echo Yii::app()->user->getFlash('points_check');?>
                                    <?php } ?>
                                </span>                            
                        </div>
                    </div>
                    <div class="btn level_r">
                        <input type="submit" class="btn_com_blue" value="保存"> 
                        <input type="button" class="btn_com_gray" value="返回">
                    </div>
                </div>
            </div>
        </div>
        <?php echo CHtml::endForm();?>
        
<script type="text/javascript">

	$(document).ready(function() {
		var year = 0;
		<?php if(!empty($clean_date_type)){?>
			year = <?php echo $clean_date_type - 1?>;
		<?php }?>
		if(year == 0){
			$("#clear_select option[value='0']").attr("selected", "selected");
		}else if(year == 1){
			$("#clear_select option[value='1']").attr("selected", "selected");
		}else if(year == 2){
			$("#clear_select option[value='2']").attr("selected", "selected");
		}else if(year == 3){
			$("#clear_select option[value='3']").attr("selected", "selected");
		}
		<?php if(isset($_GET['showstore'])&& $_GET['showstore']== 1) { ?>
			showStored();
		<?php }?>
	});
	$('.btn_com_gray').click(function(){
		hiddenStored();
		hiddenclearpoints();
		hiddenconsume();
		
	});

	function storedcheck(){
		var storedpoints = $('#stored_input').val();
		var reg = new RegExp("^[0-9]*$");
	    if(!reg.test(storedpoints)){
	        alert("积分倍率格式不正确");
	        return false;
	    }else if(!storedpoints){
			alert('请输入获取积分');
			return false;
		}	
	}


		
	<?php if($showflag){?>
		showconsume();
	<?php }?>
	function showStored(){
	    $('#Stored').show();
	}
	function hiddenStored(){
	    $('#Stored').hide();
	}
	function showclearpoints(){
	    $('#clearpoints').show();
	}
	function hiddenclearpoints(){
	    $('#clearpoints').hide();
	}
	function showconsume(){
	    $('#consume').show();
	}
	function hiddenconsume(){
	    $('#consume').hide();
	}
</script>
