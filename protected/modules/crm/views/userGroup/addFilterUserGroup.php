<script type="text/javascript" src="/themes/classic/syt/js/artDialog/jquery.artDialog.js?skin=simple"></script>
<script type="text/javascript" src="/themes/classic/syt/js/artDialog/plugins/iframeTools.js"></script>
<style>
.daterange_picker
{
	width: 140px;
}
</style>
<script type="text/javascript">
	$(document).ready(main_obj.list_init);
	$(function(){
        $('.daterange_picker, #birthday').click(function () {
            window.parent.callParAutoResize("main",$("body").height());
        });
		$('#birthday').daterangepicker({
            timePicker: false,
            format: 'MM/DD',
            dateLimit: true,
            maxDate: true,
        });
        $('.daterange_picker').daterangepicker({
            timePicker: false,
            format: 'YYYY/MM/DD',
            dateLimit: true,
            maxDate: true,
        });
	});
</script>
<div class="kkfm_r_inner">
    <div class="storeManage">
        <div class="top">
            <div class="name">新建分组</div>
        </div>
        <div class="bus-contant new_group_wrap">
            <ul class="wq_btn_group ">
                <li class="active" onClick="window.self.location.href='<?php echo $this->createUrl('addFilterUserGroup') ?>'">根据条件筛选</li>
                <li onClick="window.self.location.href='<?php echo $this->createUrl('addUserGroupSelf'); ?>'">自定义分组</li>
            </ul>
            <!-- 下面是根据条件筛选页面 -->
            <div class="filtrate_group" style="display: block;">
                <div class="new_group">
                    <div class="new_group_item">
                        <h3>基本信息</h3>
                        <div class="new_group_item_c">
                            <ul class="filtrate_item">
                                <li class="js_1"><span>性别</span></li>
                                <li class="js_2"><span>年龄</span></li>
                                <!--<li class="js_3"><span>地区</span></li>-->
                                <li class="js_4"><span>生日范围</span></li>
                                <li class="js_5"><span>来源</span></li>
                                <li class="js_6"><span>会员等级</span></li>
                                <!--<li class="js_7 last"><span>客户价值</span></li>-->
                            </ul>
                        </div>
                    </div>
                    <div class="new_group_item">
                        <h3>消费能力</h3>
                        <div class="new_group_item_c">
                            <ul class="filtrate_item">
                                <li class="js_8"><span>消费金额</span></li>
                                <li class="js_9"><span>客单价</span></li>
                                <li class="js_10"><span>最高消费</span></li>
                            </ul>
                        </div>
                    </div>
                    <div class="new_group_item">
                        <h3>消费频次</h3>
                        <div class="new_group_item_c">
                            <ul class="filtrate_item">
                                <li class="js_11"><span>消费次数</span></li>
                                <!-- <li class="js_12"><span>消费天数</span></li> -->
                            </ul>
                        </div>
                    </div>
                    <div class="new_group_item">
                        <h3>流失情况</h3>
                        <div class="new_group_item_c">
                            <ul class="filtrate_item">
                                <li class="js_13"><span>最近消费时间</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- 数据输入区域 -->
                <div class="new_group filtrate_area">
                    <div class="new_group_item js_1 filtrate_condition" n="condition_sex" id="age_param">
                        <h3>性别</h3>
                        <div class="new_group_item_c">
                            <input type="checkbox" name="sex[0]" class="f_children" id="sex_0" <?php echo isset($_GET['sex'][0]) && $_GET['sex'][0] == SEX_MALE ? 'checked="checked"' : ''?> value="<?php echo SEX_MALE?>"><label for="sex_0">男</label>
                            <input type="checkbox" name="sex[1]" class="f_children" id="sex_1" <?php echo isset($_GET['sex'][1]) && $_GET['sex'][1] == SEX_FEMALE ? 'checked="checked"' : ''?> value="<?php echo SEX_FEMALE?>"><label for="sex_1">女</label>
                            <input type="checkbox" name="sex[2]" class="f_children" id="sex_2" <?php echo isset($_GET['sex'][2]) && $_GET['sex'][2] == '3' ? 'checked="checked"' : ''?> value="<?php echo '3'?>"><label for="sex_2">未知</label>
                        </div>
                        <span class="icon_del"></span>
                    </div>
                    <div class="new_group_item js_2 filtrate_condition" n="condition_age">
                        <h3>年龄</h3>
                        <div class="new_group_item_c">
                            <input type="text" class="text_group text_group_sm start_age f_children" name="age_min" value="<?php echo isset($_GET['age_min']) ? $_GET['age_min'] : ''?>" onkeyup="value=value.replace(/[^\d]/g,'')">
                            <em>~</em>
                            <input type="text" class="text_group text_group_sm end_age f_children" name="age_max" value="<?php echo isset($_GET['age_max']) ? $_GET['age_max'] : ''?>" onkeyup="value=value.replace(/[^\d]/g,'')"> 岁
                        </div>
                        <span class="icon_del"></span>
                    </div>
                    <div class="new_group_item js_4 filtrate_condition filtrate_date" n="condition_birthday">
                        <h3>生日范围</h3>
                        <div class="new_group_item_c">
                            <input type="text" name="birthday" id="birthday" class="text_group text_group_sm f_children" readonly>
                        </div>
                        <span class="icon_del"></span>
                    </div>
                    <div class="new_group_item js_5 filtrate_condition" n="condition_source">
                        <h3>来源</h3>
                        <div class="new_group_item_c">
                            <input type="checkbox" id="source_0" class="f_children" name="source[0]" value="<?php echo USER_FROM_WECHAT?>">
                            <label for="source_0">微信公众号</label>
                            <input type="checkbox" id="source_1" class="f_children" name="source[1]" value="<?php echo USER_FROM_ALIPAY?>">
                            <label for="source_1">支付宝服务窗</label>
                        </div>
                        <span class="icon_del"></span>
                    </div>
                    <div class="new_group_item js_6 filtrate_condition" n="condition_grade">
                        <h3>会员等级</h3>
                        <div class="new_group_item_c">
                        <?php
                            foreach($user_grades as $k=>$v)
                            {
                        ?>
                            <input type="checkbox" id="level_<?php echo $k?>" class="f_children" name="level[<?php echo $k?>]" value="<?php echo $v->id?>">
                            <label for="level_<?php echo $k?>"><?php echo $v->name?></label>
                        <?php
                            }
                        ?>
                        </div>
                        <span class="icon_del"></span>
                    </div>

                    <div class="new_group_item js_7 filtrate_condition" n="condition_value">
                        <h3>客户价值</h3>
                        <div class="new_group_item_c">
                            <div>
                            <?php
                                foreach ($tag_category_customer_values as $k=>$v)
                                {
                                    if($k<=4)
                                        echo "<input type='checkbox' id='value_{$k}' name='value[$k]' value='{$k}' class='f_children'>
                                              <label for='value_{$k}'>{$v}</label>";
                                }
                            ?>
                            </div>
                            <div class="checkbox_group">
                            <?php
                                foreach ($tag_category_customer_values as $k=>$v)
                                {
                                    if($k>4)
                                        echo "<input type='checkbox' id='value_{$k}' name='value[$k]' value='{$k}' class='f_children'>
                                              <label for='value_{$k}'>{$v}</label>";
                                }
                            ?>
                            </div>
                        </div>
                        <span class="icon_del"></span>
                    </div>

                    <div class="new_group_item js_8 filtrate_condition" n="condition_consum_amount">
                        <h3>消费金额</h3>
                        <div class="new_group_item_c">
                            <span>时间在<!--<em class="choosed js_datepicker">任意时间</em>--><input type="text" class="text_group text_group_sm daterange_picker f_children" value="" readonly title="点击选择时间(不选择则视为任意时间)" placeholder="任意时间"></span>
                            <span>消费地点为<input type="hidden" class="store_ids f_children" value=""> <em class="choosed">点击选择门店</em></span>
                            <span>消费金额在 <input type="text" class="text_group text_group_sm f_children" onkeyup="IntOrFloat(this)"><em> ~ </em><input type="text" class="text_group text_group_sm f_children" onkeyup="IntOrFloat(this)"> 元之间的客户</span>
                        </div>
                        <span class="icon_del"></span>
                    </div>
                    <div class="new_group_item js_9 filtrate_condition" n="condition_consum_unitprice">
                        <h3>客单价</h3>
                        <div class="new_group_item_c">
                            <span>时间在<!--<em class="choosed js_datepicker">任意时间</em>--><input type="text" class="text_group text_group_sm daterange_picker f_children" value="" readonly title="点击选择时间(不选择则视为任意时间)" placeholder="任意时间"></span>
                            <span>消费地点为<input type="hidden" class="store_ids f_children" value=""><em class="choosed">点击选择门店</em></span>
                            <span>客单价在 <input type="text" class="text_group text_group_sm f_children" onkeyup="IntOrFloat(this)"><em> ~ </em><input type="text" class="text_group text_group_sm f_children" onkeyup="IntOrFloat(this)"> 元之间的客户</span>
                        </div>
                        <span class="icon_del"></span>
                    </div>
                    <div class="new_group_item js_10 filtrate_condition" n="condition_consum_highestprice">
                        <h3>最高消费</h3>
                        <div class="new_group_item_c">
                            <span>时间在<!--<em class="choosed js_datepicker">任意时间</em>--><input type="text" class="text_group text_group_sm daterange_picker f_children" value="" readonly title="点击选择时间(不选择则视为任意时间)" placeholder="任意时间"></span>
                            <span>消费地点为<input type="hidden" class="store_ids f_children" value=""><em class="choosed">点击选择门店</em></span>
                            <span>最高消费在 <input type="text" class="text_group text_group_sm f_children" onkeyup="IntOrFloat(this)"><em> ~ </em><input type="text" class="text_group text_group_sm f_children" onkeyup="IntOrFloat(this)"> 元之间的客户</span>
                        </div>
                        <span class="icon_del"></span>
                    </div>
                    <div class="new_group_item js_11 filtrate_condition" n="condition_consum_count">
                        <h3>消费次数</h3>
                        <div class="new_group_item_c">
                            <span>时间在<!--<em class="choosed js_datepicker">任意时间</em>--><input type="text" class="text_group text_group_sm daterange_picker f_children" value="" readonly title="点击选择时间(不选择则视为任意时间)" placeholder="任意时间"></span>
                            <span>消费地点为<input type="hidden" class="store_ids f_children" value=""><em class="choosed">点击选择门店</em></span>
                            <span>消费次数在 <input type="text" class="text_group text_group_sm f_children" onkeyup="value=value.replace(/[^\d]/g,'')"><em> ~ </em><input type="text" class="text_group text_group_sm f_children" onkeyup="value=value.replace(/[^\d]/g,'')"> 次之间的客户</span>
                        </div>
                        <span class="icon_del"></span>
                    </div>
                    <div class="new_group_item js_12 filtrate_condition" n="condition_consum_days">
                        <h3>消费天数</h3>
                        <div class="new_group_item_c">
                            <span>时间在<!--<em class="choosed js_datepicker">任意时间</em>--><input type="text" class="text_group text_group_sm daterange_picker f_children" value="" readonly title="点击选择时间(不选择则视为任意时间)" placeholder="任意时间"></span>
                            <span>消费地点为<input type="hidden" class="store_ids f_children" value=""><em class="choosed">点击选择门店</em></span>
                            <span>消费天数在 <input type="text" class="text_group text_group_sm f_children" onkeyup="value=value.replace(/[^\d]/g,'')"><em> ~ </em><input type="text" class="text_group text_group_sm f_children" onkeyup="value=value.replace(/[^\d]/g,'')"> 天之间的客户</span>
                        </div>
                        <span class="icon_del"></span>
                    </div>
                    <div class="new_group_item js_13 filtrate_condition" n="condition_not_consum_days">
                        <h3>最近消费时间</h3>
                        <div class="new_group_item_c">
                            <span>消费地点为<input type="hidden" class="store_ids f_children" value=""><em class="choosed">点击选择门店</em></span>
                            <span>距今未消费时间在 <input type="text" class="text_group text_group_sm f_children" onkeyup="value=value.replace(/[^\d]/g,'')"><em> ~ </em><input type="text" class="text_group text_group_sm f_children" onkeyup="value=value.replace(/[^\d]/g,'')"> 天之间的客户</span>
                        </div>
                        <span class="icon_del"></span>
                    </div>
                </div>
                <!-- 计算区域 -->
                <div class="new_group calc_area">
                    <div class="new_group_item">
                        <div class="btn level_r">
                            <input type="submit" class="btn_com_blue btn_com_calc" id="btn_jisuan" name="btn_jisuan" value="计算人数">
                            <span>点击按钮进行计算……</span>
                        </div>
                    </div>
                    <div id="filter_area" style="display:none">
                        <div class="new_group_item">
                            <h3>总人数</h3>
                            <div class="new_group_item_c" id="total_user">
                                <span>共选中<em class="num">100</em>位客户，占总体<em class="num">10%</em></span>
                            </div>
                        </div>
                        <div class="new_group_item">
                            <h3>性别</h3>
                            <div class="new_group_item_c" id="user_sex">
                                <div class="mb10">
                                    <span class="calc_label">男性：<em class="num">50</em>人</span>
                                    <span class="per per_sex">
                                        <span class="male per per_sex" style="width: 50px;"></span>
                                    </span>
                                    <span>50%</span>
                                </div>
                                <div>
                                    <span class="calc_label">女性：<em class="num">45</em>人</span>
                                    <span class="per_sex per">
                                        <span class="female per per_sex"></span>
                                    </span>
                                    <span>45%</span>
                                </div>
                                <span class="unknow">未知性别占总体<em class="num">5%</em>，共<em class="num">5</em>人</span>
                            </div>
                        </div>
                        <div class="new_group_item">
                            <h3>年龄</h3>
                            <div class="new_group_item_c" id="user_age">
                                <div>
                                    <span class="calc_label">20~30岁：<em class="num">50</em>人</span>
                                    <span class="per per_user">
                                        <span class="per per_user per_user_choosed" style="width: 100px"></span>
                                    </span>
                                    <span>10%</span>
                                </div>
                                <div>
                                    <span class="calc_label">30~40岁：<em class="num">45</em>人</span>
                                    <span class="per_user per">
                                        <span class="per per_user per_user_choosed"></span>
                                    </span>
                                    <span>45%</span>
                                </div>
                                <div>
                                    <span class="calc_label">40~50岁：<em class="num">45</em>人</span>
                                    <span class="per_user per">
                                        <span class="per per_user per_user_choosed"></span>
                                    </span>
                                    <span>45%</span>
                                </div>
                                <div>
                                    <span class="calc_label">其他：<em class="num">1</em>人</span>
                                    <span class="per_user per">
                                        <span class="per per_user per_user_choosed"></span>
                                    </span>
                                    <span>45%</span>
                                </div>
                            </div>
                        </div>
                        <div class="new_group_item">
                            <h3>会员等级</h3>
                            <div class="new_group_item_c" id="user_grade">
                                <div>
                                    <span class="calc_label">普通会员：<em class="num">10</em>人</span>
                                    <span class="per per_user">
                                        <span class="per per_user per_user_choosed" style="width: 100px"></span>
                                    </span>
                                    <span>10%</span>
                                </div>
                                <div>
                                    <span class="calc_label">中等会员：<em class="num">45</em>人</span>
                                    <span class="per_user per">
                                        <span class="per per_user per_user_choosed"></span>
                                    </span>
                                    <span>45%</span>
                                </div>
                                <div>
                                    <span class="calc_label">高级会员：<em class="num">45</em>人</span>
                                    <span class="per_user per">
                                        <span class="per per_user per_user_choosed"></span>
                                    </span>
                                    <span>45%</span>
                                </div>
                            </div>
                        </div>



                    </div>

                </div>
                <div class="btn level_r filtrate_btn_group">
                    <input type="submit" class="btn_com_blue" id="btn_save_before" name="btn_save_before" value="保存" disabled style="background:gray">
                    <input type="submit" class="btn_com_gray" id="btn_back_before" value="取消">
                </div>
            </div>
            <!-- 点击保存之后的弹出框 -->
            <div class="new_popWrap rules_pop_content" id="before_save_pop" style="display: none">
                <div class="pop_con">
                    <div class="title">
                        创建分组
                    </div>
                    <div class="pop_content new_pop_content">
                        <div class="setting_content">
                            <div class="setting_item">
                                <span class="group_title">分组名称</span>
                                <input type="text" name="group_name" class="text_group" placeholder="请输入分组名称，最多16个汉字" maxlength="16">
                            </div>
                            <div class="setting_item">
                                <span>分组类型</span>
                                <ul class="clean_cycle">
                                    <li class="first">
                                        <label>
                                            <input type="radio" name="group_type" value="<?php echo GROUP_TYPE_DYNAMIC?>" id="type_dynamic"><?php echo $group_types[GROUP_TYPE_DYNAMIC]?></label>
                                        <p>分组内成员和数量会根据条件每天变化</p>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="radio" name="group_type" value="<?php echo GROUP_TYPE_STATIC?>" id="type_static"><?php echo $group_types[GROUP_TYPE_STATIC]?></label>
                                        <p>分组内成员为此次筛选出的用户，固定不变</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="btn level_r">
                            <input type="button" id="btn_save" name="btn_save" class="btn_com_blue" value="保存">
                            <input type="button" id="btn_back" name="btn_back" class="btn_com_gray" value="返回">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

var seqJsonStr = '[{"seq":0,"name":"condition_sex","value":"1,2,3,"},{"seq":1,"name":"condition_age","value":{"valuemin":1,"valuemax":99}},{"seq":2,"name":"condition_area","value":"1223,5436,457567"},{"seq":3,"name":"condition_birthday","date":{"datemin":"1970-06-15","datemax":"1970-12-23"}},{"seq":4,"name":"condition_source","value":"1,2,3"},{"seq":5,"name":"condition_grade","value":"382,346465,475"},{"seq":6,"name":"condition_value","value":"1,3,5,7,6,8,2,4"},{"seq":7,"name":"condition_consum_amount","date":{"datemin":"2014-01-12","datemax":"2016-6-19"},"store":"1234,1072,244","value":{"valuemin":"10","valuemax":"990"}},{"seq":8,"name":"condition_consum_unitprice","date":{"datemin":"2014-01-12","datemax":"2016-09-19"},"store":"1234,1072,244","value":{"valuemin":"10","valuemax":"990"}},{"seq":9,"name":"condition_consum_highestprice","date":{"datemin":"2014-01-12","datemax":"2016-09-19"},"store":"1234,1072,24,244","value":{"valuemin":"10","valuemax":"990"}},{"seq":10,"name":"condition_consum_count","date":{"datemin":"2014-01-12","datemax":"2016-09-19"},"store":"1234,1072,244","value":{"valuemin":"1","valuemax":"1"}},{"seq":11,"name":"condition_consum_days","date":{"datemin":"2015-05-01","datemax":"2016-09-01"},"store":"111,234,1072,566","value":{"valuemin":"1","valuemax":"2345"}},{"seq":12,"name":"condition_not_consum_days","store":"23,1072,567","value":{"valuemin":"1","valuemax":"10"}}]';
// seqJsonStr = '[{"seq":0,"name":"condition_sex","value":"1,2,3,"},{"seq":1,"name":"condition_age","value":{"valuemin":1,"valuemax":99}}]';
// seqJsonStr = '[{"seq":0,"name":"condition_sex","value":"3,2,1"}]';

var calculate_users = function(json){
// 	alert(json);
	//提前置为不可保存，防止错误保存操作
	can_not_calculate();

	$.ajax({
        url: "<?php echo $this->createUrl('JSGroup')?>",
        type: "post",
        async: true,
        data: json,
        success: function (data) {
//             alert(data)
            if(data!=''){
            	var data = $.parseJSON(data);
            	if(data.status=='ok'){

                  $('#filter_area').css('display', 'block');
                  $('#total_user').html(data.html.total_user);
                  $('#user_age').html(data.html.user_age);
                  $('#user_sex').html(data.html.user_sex);
                  $('#user_grade').html(data.html.user_grade);
                  $('#user_value').html(data.html.user_value);
                  var current_h = $('body').height()>580?$('body').height():580;
            	  var main_frame	= $('#main', window.parent.document);
            	  main_frame.css('height', current_h);
            	  $('#btn_save_before').removeAttr('style');
            	  $('#btn_save_before').removeAttr('disabled');
            	}
            	if(data.status=='error')
                	tips(data.errMsg);
            }
        },
        error: function () {
            tips("系统异常!");
        }
    });
};

$('#btn_jisuan').click(function(){
	var jsonObj = getValue('filtrate_condition','.f_children');
	var jsonStr = JSON.stringify(jsonObj);
	console.log(jsonStr);
	if(jsonObj != '')
	{
		var status = true;
		for(var key in jsonObj)
		{
			switch(jsonObj[key].name)
			{
    			case 'condition_sex':
    				if(jsonObj[key].value=='')
    				{
        				status = false;
        				tips('请勾选性别！');
        			}
    				break;
    			case 'condition_age':
    				if(jsonObj[key].value=='' || (jsonObj[key].value!='' && (jsonObj[key].value.valuemin=='' || jsonObj[key].value.valuemax=='')))
    				{
        				status = false;
        				tips('请完整填写年龄区间！');
        			}
    				if(parseInt(jsonObj[key].value.valuemin) > parseInt(jsonObj[key].value.valuemax))
    				{
        				status = false;
        				tips('年龄区间前者必须小于后者！');
    				}
    				break;
    			case 'condition_birthday':
					if(jsonObj[key].value=='')
					{
						status = false;
						tips('请勾选生日范围！');
					}
					break;
    			case 'condition_source':
    				if(jsonObj[key].value=='')
					{
						status = false;
						tips('请勾选来源！');
					}
					break;
    			case 'condition_grade':
    				if(jsonObj[key].value=='')
					{
						status = false;
						tips('请勾选会员等级！');
					}
					break;
    			case 'condition_value':
					if(jsonObj[key].value=='')
					{
						status = false;
						tips('请勾选客户价值！');
					}
					break;
    			case 'condition_consum_amount':
    				if(jsonObj[key].value=='' || (jsonObj[key].value!='' && (jsonObj[key].value.valuemin=='' || jsonObj[key].value.valuemax=='')))
    				{
        				status = false;
        				tips('请完整填写消费金额区间！');
        			}
    				else if(parseInt(jsonObj[key].value.valuemin) > parseInt(jsonObj[key].value.valuemax))
    				{
        				status = false;
        				tips('消费金额区间前者必须小于后者！');
    				}
					break;
    			case 'condition_consum_unitprice':
    				if(jsonObj[key].value=='' || (jsonObj[key].value!='' && (jsonObj[key].value.valuemin=='' || jsonObj[key].value.valuemax=='')))
    				{
        				status = false;
        				tips('请完整填写客单价金额区间！');
        			}
    				else if(parseInt(jsonObj[key].value.valuemin) > parseInt(jsonObj[key].value.valuemax))
    				{
        				status = false;
        				tips('客单价金额区间前者必须小于后者！');
    				}
					break;
    			case 'condition_consum_highestprice':
    				if(jsonObj[key].value=='' || (jsonObj[key].value!='' && (jsonObj[key].value.valuemin=='' || jsonObj[key].value.valuemax=='')))
    				{
        				status = false;
        				tips('请完整填写最高消费金额区间！');
        			}
    				else if(parseInt(jsonObj[key].value.valuemin) > parseInt(jsonObj[key].value.valuemax))
    				{
        				status = false;
        				tips('最高消费金额区间前者必须小于后者！');
    				}
					break;
    			case 'condition_consum_count':
    				if(jsonObj[key].value=='' || (jsonObj[key].value!='' && (jsonObj[key].value.valuemin=='' || jsonObj[key].value.valuemax=='')))
    				{
        				status = false;
        				tips('请完整填写消费次数区间！');
        			}
    				else if(parseInt(jsonObj[key].value.valuemin) > parseInt(jsonObj[key].value.valuemax))
    				{
        				status = false;
        				tips('消费次数区间前者必须小于后者！');
    				}
					break;
    			case 'condition_consum_days':
    				if(jsonObj[key].value=='' || (jsonObj[key].value!='' && (jsonObj[key].value.valuemin=='' || jsonObj[key].value.valuemax=='')))
    				{
        				status = false;
        				tips('请完整填写消费天数区间！');
        			}
    				else if(parseInt(jsonObj[key].value.valuemin) > parseInt(jsonObj[key].value.valuemax))
    				{
        				status = false;
        				tips('消费天数区间前者必须小于后者！');
    				}
					break;

    			case 'condition_not_consum_days':
    				if(jsonObj[key].value=='' || (jsonObj[key].value!='' && (jsonObj[key].value.valuemin=='' || jsonObj[key].value.valuemax=='')))
    				{
        				status = false;
        				tips('请完整填写未消费天数区间！');
        			}
    				else if(parseInt(jsonObj[key].value.valuemin) > parseInt(jsonObj[key].value.valuemax))
    				{
        				status = false;
        				tips('未消费天数区间前者必须小于后者！');
    				}
        			break;
			}
		}
		if(status == true)
			calculate_users(jsonStr);
		else
		{
			can_not_calculate();
		}
	}
	else
	{
		tips('请勾选条件再计算人数！');
		can_not_calculate();
	}
});

//不计算, 重置保存按钮
var can_not_calculate = function(){
	$('#filter_area').css('display', 'none');
	$('#btn_save_before').css('background', 'gray');
	$('#btn_save_before').attr('disabled', 'true');
}

var save_group = function (jsonStr) {
    var index = layer.load(10);
    $.ajax({
        url: "<?php echo $this->createUrl('saveGroup')?>",
        type: "post",
        async: true,
        data: jsonStr,
        success: function (data) {
            layer.close(index);
            if (data == '分组名已存在') {
                parent.window.delayHide('m-danger-warning', data, 1500);
            }
            if (data == 'OK') {
                parent.window.delayHide('m-succeed-icon', '保存成功', 1500);
                window.self.location.href = '<?php echo $this->createUrl('userGroupList')?>';
            }
        },
        error: function () {
            parent.window.delayHide('m-danger-warning', '系统异常', 1500);
        }
    });
};

$('#btn_save_before').click(function(){
	$('#before_save_pop').show();
});
$('#btn_back_before').click(function(){
	window.location.href='<?php echo $this->createUrl('userGroupList')?>';
});

$('#btn_save').click(function () {
    var group_name = $("input[name='group_name']").val();
    var group_type = $("input[name='group_type']").filter(':checked').val();
    if (group_name && group_type) {
        var jsonStr = '{"group_name":"' + group_name + '", "group_type":"' + group_type + '"}';
        save_group(jsonStr);
    } else {
        if (!group_name) {
            tips('请填写分组名称！');
            $('input[name=group_name]').focus();
            return;
        }
        if (!group_type) {
            tips('请选择分组类型！');
            $('input[name=group_type]').focus();
            return;
        }
    }
});
$('#btn_back').click(function () {
    $('#before_save_pop').hide();
});

$('body').bind('click', function () {
    var current_h = $('body').height() > 580 ? $('body').height() : 580;
    var main_frame = $('#main', window.parent.document);
    main_frame.css('height', current_h);
});

function IntOrFloat(obj) {
    if ('' != obj.value.replace(/\d{1,}\.{0,1}\d{0,}/, '')) {
        obj.value = obj.value.match(/\d{1,}\.{0,1}\d{0,}/) == null ? '' : obj.value.match(/\d{1,}\.{0,1}\d{0,}/);
    }
}

function addStore(origin) {
    var store_id_str = $("#" + origin).val();
    //JSON.stringify(data)  转化为php识别的数据   $type是添加操作的标示  与 编辑区分开来
    art.dialog.open(
        '<?php echo $this->createUrl('filterStoreDialog')?>' + '?store_id_str=' + store_id_str + '&type=add&origin=' + origin,
        {
            title: '',
            lock: true,
            drag: true,
            width: '655px',
            id: 'dialog_notice',
            background: '#fff',
        }
    );
    return false;
}

function addStoreInfo(store_arr, origin) {
    var a = $('#' + origin).val();
    var em = $('em.choosed', $('#' + origin).parent());
    if (a) {
        var store_arr = a.split(',');
        store_arr.pop();
        em.html('(已选择' + store_arr.length + '家)');
    } else {
        em.html('点击选择门店');
    }
    art.dialog.get("dialog_notice").close(); //关闭弹出框

}

function removeStore(id) {
    var store_str = $('#store_str').val();
    var str = '';
    var strId = '';

    $('#' + id).remove();
    var store_arr = new Array();
    store_arr = store_str.split(",");
    store_arr.pop();
    for (var j = 0; j < store_arr.length; j++) {
        if (store_arr[j] != id) {
            str = str + store_arr[j] + ",";
        }
    }

    var str_id = $("#str_id").val();
    var str_id_arr = new Array();
    str_id_arr = str_id.split(",");
    for (var i = 1; i < str_id_arr.length; i++) {
        if (str_id_arr[i] != id) {
            strId = strId + str_id_arr[i] + ",";
        }
    }
    strId = strId.substring(0, strId.length - 1);
    strId = "," + strId;

    $("#str_id").val(strId); //给隐藏域赋值
    $('#store_str').val(str);
}

//关闭弹出框
function closeWinDialog() {
    art.dialog.get("dialog_notice").close();
// 	location.reload() 
}

$('.new_group_item span em.choosed').click(function () {
    var store_hidden_input = $('input[type=hidden]', $(this).parent());
    var condition_name = $(this).parent().parent().parent().attr('n');
    var origin_id = condition_name + '_store_id_str';
    store_hidden_input.attr('id', origin_id);
    addStore(origin_id);
});

function tips(msg) {
    layer.msg(msg, {icon: 2, time: 2000, shade: false, offset: ['260px', '40%']});
}

$(document).on("click", ".icon_del", function() {
	//提前置为不可保存
	can_not_calculate();
});

$(".filtrate_item li").click(function() {
	//提前置为不可保存
	can_not_calculate();
})
</script>