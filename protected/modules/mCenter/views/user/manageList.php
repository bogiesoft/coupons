<script type="text/javascript">
    $(document).ready(main_obj.list_init);
    $(function(){
        var now = new Date();
        $('input[name=Time]').daterangepicker({
            timePicker: false,
            format: 'YYYY/MM/DD',
            dateLimit: true,
            maxDate: true,
        });
        $('input[name=Time1]').daterangepicker({
            timePicker: false,
            format: 'YYYY/MM/DD',
            dateLimit: true,
            maxDate: true,
            opens: 'left',
        });
        $('input[name=Time2]').daterangepicker({
            timePicker: false,
            format: 'YYYY/MM/DD',
            dateLimit: true,
            maxDate: true,            
        });
    });
</script>
<div class="kkfm_r_inner">
	<div class="storeManage user_list_wrap">
		<div class="top">
			<div class="name">用户管理</div>
		</div>
		<?php echo CHtml::beginForm(Yii::app()->createUrl($this->route), 'get', array('id' => 'search_form'))?>
		<div class="bus-contant user_manage">
			<div class="user_search clearfix">
				<input type="text" placeholder="请输入姓名、昵称或者手机号" class="text_group" name="keyword" value="<?php echo isset($_GET['keyword'])?$_GET['keyword']:'';?>">
				<label class="icon_search" onclick="search()"></label>
				<div class="btn_l level_r">
					<input type="button" value="更多筛选" class="btn_com_blue btn_filtrate">
				</div>
			</div>
			<div class="search_c">
				<div class="search_c_item">
					<span>会员等级</span> <!-- <input type="checkbox" id="fans" name="fans" <?php echo !empty($_GET['fans']) ? 'checked="checked"' : ''?> value="fans"> <label for="fans">粉丝(非会员)</label> -->
                    <?php foreach ($group_list as $k => $v) { ?>                            
                    <input type="checkbox" id="normal_<?php echo $k?>" name="grade[<?php echo $k?>]" <?php echo !empty($_GET['grade'][$k]) ? 'checked="checked"' : ''?> value="<?php echo $v['id']?>"> <label for="normal_<?php echo $k?>"><?php echo $v['name']?></label>
                    <?php }?>
				</div>
				<?php if($group_pu) { ?>
				<div class="search_c_item">
					<span>用户分组</span>
					<?php foreach ($group_pu as $k => $v) { ?>                    
                    <input type="checkbox" id="group_one_<?php echo $k?>" name="groups[<?php echo $k?>]" <?php echo !empty($_GET['groups'][$k]) ? 'checked="checked"' : ''?> value="<?php echo $v['id']?>"> 
                    <label for="group_one_<?php echo $k?>"><?php echo $v['name']?></label>
                    <?php } ?>
				</div>
				<?php } ?>
				<div class="more_area">
					<div class="search_c_item" style="display: none">
						<div class="search_c_item_es search_c_item_t">
							<span>支付宝服务窗</span> 
							<input type="checkbox" name="alipay_status[]" <?php if ( isset($_GET['alipay_status']) && in_array(ALIPAY_USER_SUBSCRIBE, $_GET['alipay_status'])){echo 'checked';}?>  value="<?php echo ALIPAY_USER_SUBSCRIBE?>" id="alipay1" <?php if(isset($_GET['alipay_status']) && $_GET['alipay_status']==ALIPAY_USER_SUBSCRIBE) echo "checked";?>><label for="alipay1">已关注</label> 
							<input type="checkbox" name="alipay_status[]" <?php if ( isset($_GET['alipay_status']) && in_array(ALIPAY_USER_CANCELSUBSCRIBE, $_GET['alipay_status'])){echo 'checked';}?>  value="<?php echo ALIPAY_USER_CANCELSUBSCRIBE?>" id="alipay2" <?php if(isset($_GET['alipay_status']) && $_GET['alipay_status']==ALIPAY_USER_CANCELSUBSCRIBE) echo "checked";?>><label for="alipay2">已取消</label> 
							<input type="checkbox" name="alipay_status[]" <?php if ( isset($_GET['alipay_status']) && in_array(ALIPAY_USER_NOTSUBSCRIBE, $_GET['alipay_status'])){echo 'checked';}?>  value="<?php echo ALIPAY_USER_NOTSUBSCRIBE?>" id="alipay3" <?php if(isset($_GET['alipay_status']) && $_GET['alipay_status']==ALIPAY_USER_NOTSUBSCRIBE) echo "checked";?>> <label for="alipay3">未关注</label>
						</div>
						<div class="search_c_item_es">
							<span>微信公众号</span> 
							<input type="checkbox" name="wechat_status[]" <?php if ( isset($_GET['wechat_status']) && in_array(WECHAT_USER_SUBSCRIBE, $_GET['wechat_status'])){echo 'checked';}?> value="<?php echo WECHAT_USER_SUBSCRIBE?>" id="wechat1" <?php if(isset($_GET['wechat_status']) && $_GET['wechat_status']==WECHAT_USER_SUBSCRIBE) echo "checked";?>> <label for="wechat1">已关注</label> 
							<input type="checkbox" name="wechat_status[]" <?php if ( isset($_GET['wechat_status']) && in_array(WECHAT_USER_CANCELSUBSCRIBE, $_GET['wechat_status'])){echo 'checked';}?> value="<?php echo WECHAT_USER_CANCELSUBSCRIBE?>" id="wechat2" <?php if(isset($_GET['wechat_status']) && $_GET['wechat_status']==WECHAT_USER_CANCELSUBSCRIBE) echo "checked";?>> <label for="wechat2">已取消</label> 
							<input type="checkbox" name="wechat_status[]" <?php if ( isset($_GET['wechat_status']) && in_array(WECHAT_USER_NOTSUBSCRIBE, $_GET['wechat_status'])){echo 'checked';}?> value="<?php echo WECHAT_USER_NOTSUBSCRIBE?>" id="wechat3" <?php if(isset($_GET['wechat_status']) && $_GET['wechat_status']==WECHAT_USER_NOTSUBSCRIBE) echo "checked";?>> <label for="wechat3">未关注</label>
						</div>
					</div>
					<div class="search_c_item">
						<div class="search_c_item_es search_c_item_t">
							<span>性别</span>
							<input type="checkbox" name="sex[male]" id="male" <?php echo isset($_GET['sex']['male']) && $_GET['sex']['male'] == SEX_MALE ? 'checked="checked"' : ''?> value="<?php echo SEX_MALE?>">
                            <label for="male">男</label>
                            <input type="checkbox" name="sex[female]" id="female" <?php echo isset($_GET['sex']['female']) && $_GET['sex']['female'] == SEX_FEMALE ? 'checked="checked"' : ''?> value="<?php echo SEX_FEMALE?>">
                            <label for="female">女</label>
                            <input type="checkbox" name="unknown" id="unknown1" <?php echo isset($_GET['unknown']) && $_GET['unknown'] == 'unknown' ? 'checked="checked"' : ''?> value="<?php echo 'unknown'?>">
                            <label for="unknown1">未知</label>
						</div>
						<div class="search_c_item_es">
							<span>地区</span>
							<select class="iselect user_select" id="province" name="province">
                            <option value="">-请选择省份-</option>
                            <?php foreach ($province as $k => $v){?>
                            <option value="<?php echo $v -> code?>" <?php echo !empty($_GET['province']) && $_GET['province'] == $v -> code ? 'selected' : ''?>><?php echo $v-> name?></option>
                            <?php }?>
                             </select>
                             <select class="iselect user_select" id="city" name="city">
                                <option value="">-请选择城市-</option>
                                        <?php foreach ($city as $k => $v){?>
                                <option value="<?php echo $v -> code?>" <?php echo !empty($_GET['city']) && $_GET['city'] == $v -> code ? 'selected' : ''?>><?php echo $v-> name?></option>
                                <?php }?>
                             </select>
						</div>
					</div>
					<div class="search_c_item">
						<div class="search_c_item_es search_c_item_t">
							<span>注册会员时间</span>
							<?php echo CHtml::textField('Time2',isset($_GET['Time2']) ? $_GET['Time2'] : '',array('class'=>'text_group text_time','readonly'=>"readonly")); ?>
						</div>
						<div class="search_c_item_es">
							<span>年龄</span>
							<label>
                                <input type="text" class="user_text text_group" name="age_min" value="<?php echo isset($_GET['age_min']) ? $_GET['age_min'] : ''?>" onkeyup="value=value.replace(/[^\d]/g,'')"> ~
                                <input type="text" class="user_text text_group" name="age_max" value="<?php echo isset($_GET['age_max']) ? $_GET['age_max'] : ''?>" onkeyup="value=value.replace(/[^\d]/g,'')"> 岁 </label>
                            <span class="red" id="age_old"></span>
						</div>
					</div>
					<div class="search_c_item">
						<div class="search_c_item_es search_c_item_t">
							<span>服务窗关注时间</span> 
							<?php echo CHtml::textField('Time',isset($_GET['Time']) ? $_GET['Time'] : '',array('class'=>'text_group text_time','readonly'=>"readonly")); ?>
						</div>
						<div class="search_c_item_es">
							<span>公众号关注时间</span> 
							<?php echo CHtml::textField('Time1',isset($_GET['Time1']) ? $_GET['Time1'] : '',array('class'=>'text_group text_time','readonly'=>"readonly")); ?>
						</div>
					</div>
				</div>
				<button class="btn_com_lightblue btn_com_search">搜索</button>
			</div>
			<input type="hidden" name="sort" value="<?php echo isset($_GET['sort']) ? $_GET['sort'] : ''?>">
            <input type="hidden" name="integ" value="<?php echo isset($_GET['integ']) ? $_GET['integ'] : ''?>">
            <input type="hidden" name="Focus" value="<?php echo isset($_GET['Focus']) ? $_GET['Focus'] : ''?>">
            <input type="hidden" name="consumption" value="<?php echo isset($_GET['consumption']) ? $_GET['consumption'] : ''?>">
            <input type="hidden" name="Ability" value="<?php echo isset($_GET['Ability']) ? $_GET['Ability'] : ''?>">
            <?php echo CHtml::endForm()?>
			<div class="user_list">
				<div class="user_list_top">
					<input type="checkbox" id="checkall">
					<label for="checkall">全选</label>
					<div class="level_r add_group_btn">
						<input type="button" class="btn_com_blue" id="add_group" value="添加到分组">
                        <input type="button" class="btn_com_blue" id="add_tag" value="打标签">
						<div class="pop_tag" id="pop_tag">
							<div class="pop_arrow"></div>
							<div class="pop_tag_top pop_tag_active">
								<h3>标签</h3>
							</div>
							<div class="pop_tag_con">
							<?php if(!empty($tag)) { ?>
							<?php foreach($tag as $key => $val) { ?>
                            <div class="pop_tag_con_item">
                            	<span><?php echo $val['name']?></span>
                            	<div class="pop_tag_con_item_r">
                            	<ul id="tag_item_<?php echo $key;?>" class="tag_per_ul">
                            		<?php if ($val['value'] != 'null') { 
                            		      $vals = json_decode($val['value']);
                            		      foreach ($vals as $m){
                            		          if ($m){
                            		    ?>
                            		<li>
                            		<input class="tag_item_one" type="checkbox" value="<?php echo $m?>"><label class="tag_item"><?php echo $m?></label>
                            		</li>
                            		
                            		<?php }}}?>
                            	</ul>
                            	</div>
                            </div>
                            <?php }}?>
							</div>
							<div class="pop_tag_footer">
								<div class="btn level_r">
									<input type="button" class="btn_com_blue" id="btn_add_tags_yes" value="确定"> 
									<input type="button" class="btn_com_gray" id="btn_add_tags_cancel" value="取消">
								</div>
							</div>
						</div>
						<div class="pop_tag pop_group" id="pop_group">
							<div class="pop_arrow"></div>
							<div class="pop_tag_top pop_tag_active">
								<h3>添加到分组</h3>
							</div>
							<div class="pop_group_con">
							<?php foreach ($group_pu as $k => $v) { ?>                    
                            <label class="group_item">
                            <input class="group_item_one" type="checkbox" value="<?php echo $v['id']?>">
                            <?php echo $v['name']?></label>
                            <?php } ?>
							</div>
							<div class="pop_tag_footer">
								<div class="btn level_r">
									<input type="button" class="btn_com_blue" id="btn_add_groups_yes" value="确定"> 
									<input type="button" class="btn_com_gray" id="btn_add_groups_cancel" value="取消">
								</div>
							</div>
						</div>
					</div>
					<div class="user_list_order">
						<span class="sort" style="color:black; cursor: default">排序：</span>
                            <?php if(isset($_GET['sort']) && $_GET['sort'] == 'desc') { ?>
                            <span onclick="ageasc()">年龄<i id="ages" style="display: none"></i></span>
                            <?php } else { ?>
                            <span onclick="agedesc()">年龄<i id="ages" style="display: none"></i></span>
                            <?php } ?>
                            <?php if(isset($_GET['integ']) && $_GET['integ'] == 'desc') { ?>
                            <span onclick="integasc()">积分<i id="integs" style="display: none"></i></span>
                            <?php } else { ?>
                            <span onclick="integdesc()">积分<i id="integs" style="display: none"></i></span>
                            <?php } ?>   
                            <?php if(isset($_GET['Focus']) && $_GET['Focus'] == 'desc') { ?>
                            <span onclick="Focusasc()">关注时间<i id="Focuss" style="display: none"></i></span>
                            <?php } else { ?>
                            <span onclick="Focusdesc()">关注时间<i id="Focuss" style="display: none"></i></span>
                            <?php } ?>
                            <?php if(isset($_GET['consumption']) && $_GET['consumption'] == 'desc') { ?>
                            <span onclick="consumptionasc()">消费时间<i id="consumptions" style="display: none"></i></span>
                            <?php } else { ?>
                            <span onclick="consumptiondesc()">消费时间<i id="consumptions" style="display: none"></i></span>
                            <?php } ?>
					</div>
				</div>
				<div class="user_list_top user_list_top_second">
					<ul class="user_list_th">
						<li><span>用户信息</span></li>
						<li><span>会员信息</span></li>
						<li><span>关注时间</span></li>
						<li><span>最近消费</span></li>
						<li><span>操作</span></li>
					</ul>
				</div>
				<?php if (!empty($list)) {
                        foreach ($list as $k => $v) { ?>
				<div name="discuss" class="user_list_middle clearfix">
					<div class="td_item">
						<div class="user_info_checkbox">
							<input type="checkbox" value="<?php echo $v['id'] ?>" id="user<?php echo $v['id'] ?>">
                            <label for="user<?php echo $v['id'] ?>"></label>
						</div>
						<div class="headIcon">
							<?php 
                                if(!empty($v['avatar'])) { 
                                    if(substr($v['avatar'], 0,4) != 'http'){
                                        $img = IMG_GJ_LIST.$v['avatar'];
                                    } else {
                                        $img = $v['avatar'];
                                    }
                                } else {
                                    $img = GJ_STATIC_IMAGES.'face_man.png';
                                    if(!empty($v['sex'])){
                                    if ($GLOBALS['__SEX'][$v['sex']] == '男'){
                                        $img = GJ_STATIC_IMAGES.'face_man.png';
                                    } else {
                                        $img = GJ_STATIC_IMAGES.'face_woman.png';
                                    }
                                    }
                                }
                                ?>
							<img src="<?php echo $img?>" alter="<?php echo isset($v['nickname']) ? $v['nickname'] : '&nbsp;'?>" title="<?php echo isset($v['nickname']) ? $v['nickname'] : '&nbsp;'?>" style="cursor: pointer;" onclick="window.location.href='<?php echo Yii::app()->createUrl('mCenter/user/userDetail',array('id'=>$v['id']))?>'">
						</div>
						<div class="user_info">
							<span class="name" title="<?php echo isset($v['nickname']) ? $v['nickname'] : '&nbsp;'?>"  style="cursor: pointer;" onclick="window.location.href='<?php echo Yii::app()->createUrl('mCenter/user/userDetail',array('id'=>$v['id']))?>'"><?php echo isset($v['nickname']) ? $v['nickname'] : '&nbsp;'?></span>
							<span>
								<em class="sex"><?php echo isset($v['sex']) ? $GLOBALS['__SEX'][$v['sex']] : ''?></em>
                                <?php if(!empty($v['birthday'])){?>
                                <em class="age">
                                    <?php echo $v['birthday'].'岁';?>
                                </em>
                                <?php }?>
                                </span>
                            <span><?php echo $v['province']?><?php echo !empty($v['city']) ? '-'.$v['city'] : ''?></span>
						</div>
					</div>
					<div class="td_item">
						<?php if($v['type'] == USER_TYPE_WANQUAN_MEMBER) {?>
                        <span><?php echo $v['grade_name']?><em class="user_info_grade">（积分<?php echo isset($v['points']) ? $v['points'] : '0'?>）</em></span>
                        <?php } ?>
                        <?php if($v['type'] == USER_TYPE_WECHAT_FANS || $v['type'] == USER_TYPE_ALIPAY_FANS) { ?>
                        <span>粉丝<em class="user_info_grade">（未注册）</em></span>
                        <?php } ?>
                        <span><?php echo isset($v['account']) ? $v['account'] : '&nbsp;'?></span>
                        <span><?php echo !empty($v['regist_time']) ? date('Y.m.d',strtotime($v['regist_time'])) : ''?></span>
					</div>
					<div class="td_item">
						<span>服务窗(<?php if ($v['alipay_status']==ALIPAY_USER_NOTSUBSCRIBE || empty($v['alipay_status'])) {echo '未关注';}if ($v['alipay_status']==ALIPAY_USER_SUBSCRIBE) {echo '已关注'.date('Y.m.d', strtotime($v['alipay_subscribe_time']));}if ($v['alipay_status']==ALIPAY_USER_CANCELSUBSCRIBE) {echo '已取消'.date('Y.m.d', strtotime($v['alipay_cancel_subscribe_time']));}?>)</span>
						<span>公众号(<?php if ($v['wechat_status']==WECHAT_USER_NOTSUBSCRIBE || empty($v['wechat_status'])) {echo '未关注';}if ($v['wechat_status']==WECHAT_USER_SUBSCRIBE) {echo '已关注'.date('Y.m.d', strtotime($v['wechat_subscribe_time']));}if ($v['wechat_status']==WECHAT_USER_CANCELSUBSCRIBE) {echo '已取消'.date('Y.m.d', strtotime($v['wechat_cancel_subscribe_time']));}?>)</span>
					</div>
					<div class="td_item">
						<span><?php echo $v['store_name']?></span>
                            <span><?php echo !empty($v['pay_time']) ? date('Y.m.d H:i:s',strtotime($v['pay_time'])) : ''?></span>
					</div>
					<div class="td_item">
						<dl class="operate">
							<dt onclick="window.self.location.href='<?php echo Yii::app()->createUrl('mCenter/user/userDetail',array('id'=>$v['id']))?>'">详情</dt>
<!-- 							<dd> -->
<!-- 								<a href="">详情</a> -->
<!-- 							</dd> -->
						</dl>
					</div>
				</div>
				<?php } } ?>
			</div>
			<br>
			<div style="float: right">
                <div class="page user_list_page">
                    <?php $this -> widget('CLinkPager',array(
                        'pages'=>$pages,
                        'header'=>'共&nbsp;<strong class="orange">'.$pages -> getItemCount().'</strong>&nbsp;条&nbsp;',
                        'prevPageLabel' => '上一页',
                        'nextPageLabel'=>'下一页',
                        'maxButtonCount'=>8
                    ));?>
                </div>
            </div>
            <br>
		</div>
	</div>
</div>
    <input type="hidden" id="user_list">
    <?php echo CHtml::beginForm(Yii::app()->createUrl('mCenter/user/addtoGroup'), 'post', array('id' => 'group_form'))?>
    	<input type="hidden" id="groups" name="groups" value/>
    	<input type="hidden" id="users-g" name="users" value/>
    <?php echo CHtml::endForm()?>
    <?php echo CHtml::beginForm(Yii::app()->createUrl('mCenter/user/addTag'), 'post', array('id' => 'tag_form'))?>
		<input type="hidden" id="tags" name="tags" value/>
		<input type="hidden" id="users-t" name="users" value/>
    <?php echo CHtml::endForm()?>
    <script>   
        //鼠标点击div之外的区域，隐藏div
        var isOut = true;
        document.onmouseup = function(){
            var num = <?php echo $count+100?>;
            for(var i = 0; i < num; i++) {
                if (isOut && $("#pop_tag_" + i).css('display') == 'block') {
                    $("#pop_tag_" + i).css('display', 'none');
                }
            }
        };
    
        <?php if(!empty($_GET['sex']) || !empty($_GET['Time']) || !empty($_GET['Time1']) || !empty($_GET['Time2']) || !empty($_GET['clients'])
                || !empty($_GET['province']) || !empty($_GET['city']) || !empty($_GET['age_min']) || !empty($_GET['age_max']) || !empty($_GET['unknown'])) { ?>
            $(".user_search .btn_com_blue").toggleClass("up_arrow");
            $(".user_search .btn_com_blue").val("收起筛选");
            $(".more_area").slideToggle(autoResize);
        <?php } ?>
        //重设高度
        function autoResize(){
                window.parent.callParAutoResize("main",$("body").height());
        }
        //全选/全不选
        $("#checkall").click(function() {
                $(".user_info_checkbox input").prop("checked", !!$(this).is(":checked"));
        });
        
        //下拉框触发事件
        $(document).on("change", "select.select", function(e) {
                if($(this).val() == '') {
                        return true;
                }
                //遍历选中会员
                var user = '';
                $(".user_info_checkbox input").each(function() {
                        if($(this).is(":checked")) {
                                if(user == '') {
                                        user = $(this).val();
                                }else {
                                        user += ',' + $(this).val();
                                }
                        }
                });
                //未选中任何会员
                if(user == '') {
                        alert('请选择会员后再操作');
                        $(this).val("");
                        return true;
                }
                var type = $(this).attr("id");
                //确认提示
                var tmp = type == 'add' ? '添加' : '移动';
                var msg = '确定'+tmp+'到该分组下?';
                if(!confirm(msg)) {
                        $(this).val("");
                        return true;
                }
                //保存表单信息
                $("#user").val(user);
                $("#operation").val(type);
                $("#target").val($(this).val());
                //提交表单
                $("#list_form").submit();
        });
        //点击搜索图标
        function search(){
            document.getElementById("search_form").submit();
        }     

        //年龄降序
        function agedesc(){
            $('input[name=sort]').val('desc');
            $('input[name=integ]').val('');
            $('input[name=Focus]').val('');
            $('input[name=consumption]').val('');
            $('input[name=Ability]').val('');
            document.getElementById("search_form").submit();            
        }
        //年龄升序
        function ageasc(){
            $('input[name=sort]').val('asc');
            $('input[name=integ]').val('');
            $('input[name=Focus]').val('');
            $('input[name=consumption]').val('');
            $('input[name=Ability]').val('');
            document.getElementById("search_form").submit();            
        }
        <?php if(!empty($_GET['sort']) && $_GET['sort'] == 'desc') { ?>
        $('#ages').show();
        <?php } else if(!empty($_GET['sort']) && $_GET['sort'] == 'asc') { ?>
            $('#ages').show();
            $('#ages').toggleClass("arrow_up_active");
            $('#sort').toggleClass("arrow_up_active");
        <?php } ?>
        //积分降序
        function integdesc(){
            $('input[name=sort]').val('');
            $('input[name=integ]').val('desc');
            $('input[name=Focus]').val('');
            $('input[name=consumption]').val('');
            $('input[name=Ability]').val('');
            document.getElementById("search_form").submit();        
        }
        //积分升序
        function integasc(){
            $('input[name=sort]').val('');
            $('input[name=integ]').val('asc');
            $('input[name=Focus]').val('');
            $('input[name=consumption]').val('');
            $('input[name=Ability]').val('');
            document.getElementById("search_form").submit();        
        }
        <?php if(!empty($_GET['integ']) && $_GET['integ'] == 'desc') { ?>
        $('#integs').show();
        <?php } else if(!empty($_GET['integ']) && $_GET['integ'] == 'asc') { ?>
            $('#integs').show();
            $('#integs').toggleClass("arrow_up_active");
            $('#sort').toggleClass("arrow_up_active");
        <?php } ?>
        //关注时间降序
        function Focusdesc(){
            $('input[name=sort]').val('');
            $('input[name=integ]').val('');
            $('input[name=Focus]').val('desc');
            $('input[name=consumption]').val('');
            $('input[name=Ability]').val('');
            document.getElementById("search_form").submit();            
        }
        //关注时间升序
        function Focusasc(){
            $('input[name=sort]').val('');
            $('input[name=integ]').val('');
            $('input[name=Focus]').val('asc');
            $('input[name=consumption]').val('');
            $('input[name=Ability]').val('');
            document.getElementById("search_form").submit();            
        }
        <?php if(!empty($_GET['Focus']) && $_GET['Focus'] == 'desc') { ?>
        $('#Focuss').show();
        <?php } else if(!empty($_GET['Focus']) && $_GET['Focus'] == 'asc') { ?>
            $('#Focuss').show();
            $('#Focuss').toggleClass("arrow_up_active");
            $('#sort').toggleClass("arrow_up_active");
        <?php } ?>
        //消费时间降序
        function consumptiondesc(){
            $('input[name=sort]').val('');
            $('input[name=integ]').val('');
            $('input[name=Focus]').val('');
            $('input[name=consumption]').val('desc');
            $('input[name=Ability]').val('');
            document.getElementById("search_form").submit();           
        }
        //消费时间升序
        function consumptionasc(){
            $('input[name=sort]').val('');
            $('input[name=integ]').val('');
            $('input[name=Focus]').val('');
            $('input[name=consumption]').val('asc');
            $('input[name=Ability]').val('');
            document.getElementById("search_form").submit();           
        }
        <?php if(!empty($_GET['consumption']) && $_GET['consumption'] == 'desc') { ?>
        $('#consumptions').show();
        <?php } else if(!empty($_GET['consumption']) && $_GET['consumption'] == 'asc') { ?>
            $('#consumptions').show();
            $('#consumptions').toggleClass("arrow_up_active");
            $('#sort').toggleClass("arrow_up_active");
        <?php } ?>
        //消费能力降序
//        $('#Ability').click(function(e){
//            $('input[name=sort]').val('');
//            $('input[name=integ]').val('');
//            $('input[name=Focus]').val('');
//            $('input[name=consumption]').val('');
//            $('input[name=Ability]').val('desc');
//            document.getElementById("search_form").submit();        
//        });
//        <?php //if(!empty($_GET['Ability'])) { ?>
//        $('#Abilitys').show();
//        <?php //} ?>
        //年龄段必须从小到大输入
        var age_min = $('input[name=age_min]').val();
        var age_max = $('input[name=age_max]').val(); 
         
        $(document).ready(function(){
            if(parseInt(age_min) > parseInt(age_max)){
                $('#age_old').html('前者不能大于后者');
            } else {
                $('#age_old').html('');
            }           
        });   
        
        /***********************************选择地址**************************************/
	//选择省 
   	$('#province').change(function(){
            var code = $(this).val();
            $('#city').empty();
            $('#city').append('<option value="">-请选择城市-</option>');
            $('#area').empty();
            $('#area').append('<option value="">-请选择-</option>');
            $.ajax({
            url: '<?php echo(Yii::app()->createUrl('mCenter/Store/GetCity'));?>',
            data: {code:code},
            type: 'post',
            dataType: 'json',
            success: function (data) {
              	for(var i=0 ; i<data.length;i++){
                  	var code = data[i].split(",")[1];
              		var name = data[i].split(",")[0];
                  	var text = '<option value="'+code+'">'+name+'</option>';
                	$('#city').append(text);
             	}
            }
        });
        return false;
 	});

	  //选择市
   	$('#city').change(function(){
            var code = $(this).val();
            $('#area').empty();
            $('#area').append('<option value="">-请选择城市-</option>');
            $.ajax({
            url: '<?php echo(Yii::app()->createUrl('mCenter/Store/GetArea'));?>',
            data: {code:code},
            type: 'post',
            dataType: 'json',
            success: function (data) {
              	for(var i=0 ; i<data.length;i++){
                  	var code = data[i].split(",")[1];
              		var name = data[i].split(",")[0];
                  	var text = '<option value="'+code+'">'+name+'</option>';
                	$('#area').append(text);
             	}
            }
        });
		return false;
 	});
        
        //添加标签弹窗
        function poptag(id)
        {
            $('#pop_tag_'+id).show(1000);
        }
        //隐藏标签弹出框
        function pophiden(id)
        {
            $('#pop_tag_'+id).hide(1000);
        }
        //取消刷新
        function reload()
        {
            location.reload();
        }

        $("#btn_add_tags_yes").click(function(){
        	//遍历选中会员
            var users = '';
            var tags = '';
            $(".user_info_checkbox input").each(function() {
                if($(this).is(":checked")) {
                    if(users == '') {
                        users = $(this).val();
                    }else {
                        users += ',' + $(this).val();
                    }
                }
            });
            $(".tag_per_ul input").each(function() {
				if($(this).is(":checked")) {
					if(tags == '') {
						tags = $(this).val();
					} else {
						tags += ',' + $(this).val();
					}
				}
            });
            //未选中任何会员
            if(users == '') {
                alert('请选择会员后再操作');
                return false;
            }
            if(tags == '') {
                alert('请选择标签后再操作');
                return false;
        }
            
            $("#tags").val(tags);
            $("#users-t").val(users);
            $("#tag_form").submit();
        });
		
		
        $("#btn_add_groups_yes").click(function(){
        	//遍历选中会员
            var users = '';
            var groups = '';
            $(".user_info_checkbox input").each(function() {
                if($(this).is(":checked")) {
                    if(users == '') {
                        users = $(this).val();
                    }else {
                        users += ',' + $(this).val();
                    }
                }
            });
            $(".group_item input").each(function() {
				if($(this).is(":checked")) {
					if(groups == '') {
						groups = $(this).val();
					} else {
						groups += ',' + $(this).val();
					}
				}
            });
            //未选中任何会员
            if(users == '') {
                alert('请选择会员后再操作');
                return true;
            }
            if(groups == '') {
                alert('请选择分组后再操作');
                return true;
        }
            $("#groups").val(groups);
            $("#users-g").val(users);
            $("#group_form").submit();
        });
        
        $("#btn_add_tags_cancel").click(function(){
        	$("#pop_tag").css('display', 'hidden');
// 			alert('点击了打标签取消按钮！');
        });
		$("#btn_add_groups_cancel").click(function(){
			$("#pop_group").css('display', 'hidden');
// 			alert('点击了分组取消按钮！');
        });

		$(".tag_per_ul input[type=checkbox]").click(function(){
			var flag = $(this).attr("checked");
			var pa = $(this).parent().parent();
			pa.find("input[type=checkbox]").prop('checked', false);
			if(flag == "checked"){
				$(this).prop("checked", false);
				$(this).removeAttr("checked");
			} else {
				$(this).prop("checked", true);
				$(this).attr("checked", "checked");
			}
		});

		function getUser(){
			var users = '';
            $(".user_info_checkbox input").each(function() {
                if($(this).is(":checked")) {
                    if(users == '') {
                        users = $(this).val();
                    }else {
                        users += ',' + $(this).val();
                    }
                }
            });
            return users.split(',');
		}
		
		$("#add_tag").click(function(){
			var users = getUser();
			if(users[0]){
				if(users.length==1){
// 					alert('获取用户'+users[0]+"的标签！");
					obj = $.ajax({url:"/index.php/mCenter/user/getOneUserTag?uid="+users[0], async:false});
					wapperTag(obj.responseText);
				} else {
					initSome();
				}
				$("#pop_group").css("display", "none");
	        	$("#pop_tag").toggle();
			}
	    })

	    $("#add_group").click(function(){
	    	var users = getUser();
	    	if(users[0]){
		    	if(users.length==1){
// 	    			alert('获取用户'+users[0]+"的分组！");
	    			obj = $.ajax({url:"/index.php/mCenter/user/getOneUserGroup?uid="+users[0], async:false});
	    			wapperGroup(obj.responseText);
		    	} else {
		    		initSome();
			    }
		    	$("#pop_tag").css("display", "none");
		    	$("#pop_group").toggle();
	    	}
	    })
	    
	    //清空组合标签
	    function initSome()
		{
			$("input[type='checkbox'].group_item_one").each(function(){
					$(this).prop("checked", false);
					$(this).removeAttr("checked");
			});
			$("input[type='checkbox'].tag_item_one").each(function(){
					$(this).prop("checked", false);
					$(this).removeAttr("checked");
			});
		}
	    
		function wapperGroup(obj)
		{
			initSome();
			var groups = $.parseJSON(obj);
			$("input[type='checkbox'].group_item_one").each(function(){
				for(i=0; i<groups.length; i++){
					if($(this).val() == groups[i]){
						$(this).attr("checked", "checked");
						$(this).prop("checked", true);
					}
				}
			});
			
		}

		function wapperTag(obj)
		{
			initSome();
			var tags = $.parseJSON(obj);
			$("input[type='checkbox'].tag_item_one").each(function(){
				for(i=0; i<tags.length; i++){
					if($(this).val() == tags[i]){
						$(this).attr("checked", "checked");
						$(this).prop("checked", true);
					}
				}
			});
			
		}
    </script>
    