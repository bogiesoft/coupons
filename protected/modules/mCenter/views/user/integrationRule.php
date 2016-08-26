<script type="text/javascript">
    $(document).ready(main_obj.list_init);
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
                    <td width="21.25%">动作名称</td>
                    <td width="21.25%">奖励周期</td>
                    <td width="21.25%">奖励次数</td>
                    <td width="21.25%">奖励积分</td>
                    <td align="center">操作</td>
                </tr> 
                <?php if($model) { ?>
                <?php foreach($model as $k => $v) { ?>
                <tr>
                    <td valign="top">
                    	<dl class="list">
                            <dt><?php echo $v['name']?></dt>
                        </dl>
                    </td>
                    <td valign="top">
                    	<dl class="list">
                            <dt><?php echo $v['cycle']?></dt>
                        </dl>
                    </td >
                    <td valign="top">
                        <dl class="list">
                            <dt><?php echo $v['num']?></dt>
                        </dl>
                    </td >
                    <td valign="top">
                        <dl class="list">
                            <dt><?php echo $v['points']?></dt>
                        </dl>
                    </td >
                    <td align="center" valign="top">
                        <dl class="operate">
                            <dt>操作</dt>
                            <dd>
                                <a href="javascript:;" onclick="rationRule(<?php echo $v['id']?>)">编辑</a>
                                <a href="#">停用</a>
                            </dd>                    	
                        </dl>
                    </td>
                </tr>
                <?php } } ?>
            </table>
        </div>
    </div>
    <div class="popWrap new_popWrap rules_pop_content" id="pop" style="display:none">
            <div class="pop_con">
                <div class="title">
                    规则设置
                </div>
                <form action="<?php echo Yii::app()->createUrl('mCenter/user/editIntegrationRule')?>" method="post">
                <div class="pop_content new_pop_content">
                    <div class="setting_content">
                        <div class="setting_item">
                            <span>规则名称</span><label id="name"></label>
                        </div>
                        <div class="setting_item">
                            <span>奖励周期</span>
                            <input type="hidden" name="id" id="id" value="">
                            <input type="radio" name="period" id="single" value="1"><label for="single">一次性</label>
                            <input type="radio" name="period" id="day" value="2"><label for="day" id="days">每天</label>
                            <input type="radio" name="period" id="week" value="3"><label for="week" id="weeks">每周</label>
                            <input type="radio" name="period" id="nolimit" value="4"><label for="nolimit" id="nolimits">不限周期</label>
                        </div>
                         <div class="setting_item" id="setting_item">
                            <span>奖励次数</span><input type="text" name="num" id="num" onkeyup="value=value.replace(/[^\d]/g,'')">
                            <div class="remark">请输入奖励次数，不填默认为不限制次数</div>
                        </div>
                        <div class="setting_item bonus">
                            <span>奖励积分</span>
                            <label id="condition">每储值<input type="text" name="condition" placeholder="金额" class="s_input" onkeyup="value=value.replace(/[^\d]/g,'')">元，</label>
                            <label>奖励<input type="text" name="points" placeholder="积分" class="s_input" onkeyup="value=value.replace(/[^\d]/g,'')"></label>
                            <div class="remark new_remark"><input type="checkbox" name="if_storedpay_get_points" id="points" value=""><label for="points">使用储值付款时，同时享受消费积分</label></div>
                        </div>
                    </div>
                    <div class="btn level_r">
                        <input type="submit" class="btn_com_blue" value="保存">
                        <input type="button" onclick="ruleHide()" class="btn_com_gray" value="取消">
                    </div>
                </div>
                </form>
            </div>
    </div> 
    <script>
        //点击编辑弹出框
        function rationRule(id)
        {            
            <?php if($model) { ?>
                <?php foreach($model as $k => $v) { ?>
                    if(id == '<?php echo $v['id']?>'){
                        $('#id').val(id);
                        $('#name').html('<?php echo $v['name']?>');
                        if('<?php echo $v['name']?>'=='注册会员' || '<?php echo $v['name']?>'=='绑定微信' || '<?php echo $v['name']?>'=='绑定支付宝'){
                            $('#day').hide();
                            $('#days').hide();
                            $('#week').hide();
                            $('#weeks').hide();
                            $('#nolimit').hide();
                            $('#nolimits').hide();
                            //$('#setting_item').hide();
                            $('#condition').hide();
                            $('.new_remark').hide();
                        }
                        if('<?php echo $v['name']?>'=='消费'){
                            $('.new_remark').hide();
                        }
                        if('<?php echo $v['cycle']?>' == 1){
                            $('#single').attr("checked", true);
                        }
                        if('<?php echo $v['cycle']?>' == 2){
                            $('#day').attr("checked", true);                            
                        }
                        if('<?php echo $v['cycle']?>' == 3){
                            $('#week').attr("checked", true);
                        }
                        if('<?php echo $v['cycle']?>' == 4){
                            $('#nolimit').attr("checked", true);
                        }
                        $('#num').val('<?php echo $v['num']?>');
                        $('input[name=condition]').val('<?php echo $v['condition']?>');
                        $('input[name=points]').val('<?php echo $v['points']?>');
                        if('<?php echo $v['if_storedpay_get_points']?>' == 2){
                            $('input[name=if_storedpay_get_points]').attr("checked",true);
                            $('input[name=if_storedpay_get_points]').val(2);
                        } else {
                            $('input[name=if_storedpay_get_points]').val(1);
                        }
                    }
            <?php } }?>
            $('#pop').slideToggle();
        }
        //隐藏弹出框
        function ruleHide()
        {
            $('#pop').slideToggle(reload);            
        }
        //取消刷新
        function reload()
        {
            location.reload();
        }
        //复选框选中与不选中
        $(function(){
            $('input[name=if_storedpay_get_points]').click(function(){
                if($('input[name=if_storedpay_get_points]').prop("checked"))
                {
                    $('input[name=if_storedpay_get_points]').val(2);
                } else {
                    $('input[name=if_storedpay_get_points]').val(1);
                }
            });
        });
    </script>