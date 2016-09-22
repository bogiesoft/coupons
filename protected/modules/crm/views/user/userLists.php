<script type="text/javascript">
    $(document).ready(main_obj.list_init);
    $(function(){
		window.parent.callParAutoResize("main",$("body").height());
        var now = new Date();
        $('#reg_time').daterangepicker({
            timePicker: false,
            format: 'YYYY/MM/DD',
            dateLimit: true,
            maxDate: true,
        });
        $('#fw_time').daterangepicker({
            timePicker: false,
            format: 'YYYY/MM/DD',
            dateLimit: true,
            maxDate: true,
        });
        $('#wx_time').daterangepicker({
            timePicker: false,
            format: 'YYYY/MM/DD',
            dateLimit: true,
            maxDate: true,
            opens: 'left',     
        });
        $('#birth').daterangepicker({
            timePicker: false,
            format: 'MM/DD',
            showDropdowns: false,
            opens: 'left',
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
				<label class="icon_search"  onclick="search()"></label>
				<!--
				<div class="btn_l level_r">
                        <input type="button" value="更多筛选" class="btn_com_blue btn_filtrate">
                </div> 
                -->
			</div>
			<div class="search_c">
				<div class="search_c_item">
					<span>会员等级</span>
					<!-- 
					<input type="checkbox" id="grade_0" name="grade[]"><label for="grade_0">粉丝</label>
					<input type="checkbox" id="grade_1" name="grade[]"><label for="grade_1">普通会员</label>
					<input type="checkbox" id="grade_2" name="grade[]"><label for="grade_2">黄金会员</label>
					<input type="checkbox" id="grade_3" name="grade[]"> <label for="grade_3">钻石会员</label>
					<input type="checkbox" id="grade_4" name="grade[]"> <label for="grade_4">至尊会员</label>
					-->
					<div class='dd'>
					<?php
					   foreach ($user_grades as $k=>$v)
					   {
					       echo '<label for="grade_'.$k.'"><input type="checkbox" id="grade_'.$k.'" name="grade['.$k.']" value="'.$v['id'].'"';
					       if(!empty($_GET['grade'][$k]))
					           echo 'checked';
					       echo '/>'.$v['grade_name'].'</label>';
					   }
					
					?>
					</div>
				</div>
				<?php if(!empty($user_groups)){?>
				<div class="search_c_item">
					<span>客户分组</span>
					<!--
					<input type="checkbox" id="group_0" name="group[]"><label for="group_0">分组一</label>
					<input type="checkbox" id="group_1" name="group[]"><label for="group_1">分组二</label>
					<input type="checkbox" id="group_2" name="group[]"><label for="group_2">分组三</label>
					<input type="checkbox" id="group_3" name="group[]"> <label for="group_3">分组四</label>
					-->
					<?php 
// 					   var_dump(empty($user_groups));
					?>
                    <div class="dd">
					<?php 
					   foreach ($user_groups as $k=>$v)
					   {
					       echo '<label for="group_'.$k.'"><input type="checkbox" id="group_'.$k.'" name="group['.$k.']" value="'.$v['id'].'"';
					       if(!empty($_GET['group'][$k]))
					           echo 'checked';
					       echo '>'.$v['name'].'</label>';
					   }
					?>
                    </div>
				</div>
				<?php }?>
				<div class="search_c_item">
					<div class="search_c_item_es search_c_item_t">
						<span>性别</span>
						<!--
						<input type="checkbox" id="sex_0" name="sex[0]"><label for="sex_0">男</label>
						<input type="checkbox" id="sex_1" name="sex[1]"><label for="sex_1">女</label>
						<input type="checkbox" id="sex_2" name="sex[2]"> <label for="sex_2">未知</label>
						-->
						<input type="checkbox" name="sex[0]" id="sex_0" <?php echo isset($_GET['sex'][0]) && $_GET['sex'][0] == SEX_MALE ? 'checked="checked"' : ''?> value="<?php echo SEX_MALE?>"><label for="sex_0">男</label>
                        <input type="checkbox" name="sex[1]" id="sex_1" <?php echo isset($_GET['sex'][1]) && $_GET['sex'][1] == SEX_FEMALE ? 'checked="checked"' : ''?> value="<?php echo SEX_FEMALE?>"><label for="sex_1">女</label>
                        <input type="checkbox" name="sex[2]" id="sex_2" <?php echo isset($_GET['sex'][2]) && $_GET['sex'][2] == '3' ? 'checked="checked"' : ''?> value="<?php echo '3'?>"><label for="sex_2">未知</label>
						
					</div>
					<div class="search_c_item_es">
						<span>年龄</span>
						<label>
						  <input type="text" class="user_text text_group" name="age_min" value="<?php echo isset($_GET['age_min']) ? $_GET['age_min'] : ''?>" onkeyup="value=value.replace(/[^\d]/g,'')"> 
						  ~ 
						  <input type="text" class="user_text text_group" name="age_max" value="<?php echo isset($_GET['age_max']) ? $_GET['age_max'] : ''?>" onkeyup="value=value.replace(/[^\d]/g,'')"> 岁
						</label>
						<span class="red" >
						</span>
					</div>
				</div>
				<!--
				<div class="search_c_item">
					<span class="tag">地区</span>
					<div class="search_c_item_tag">
						<select class="iselect user_select" id="province">
                            <option value="">-请选择省份-</option>
                            <?php foreach ($province as $k => $v){?>
                            <option value="<?php echo $v -> code?>"><?php echo $v-> name?></option>
                            <?php }?>
                        </select>
                        <select class="iselect user_select" id="city">
                            <option value="">-请选择城市-</option>
                           
                        </select>
						<input type="button" class="tag_btn_default tag-btn_primary" value="确定" onclick="choseArea()"><em class="tips">支持多选</em>
						<div class="area">
							<?php 
							   if(!empty($choseCities))
							   {
							       foreach ($choseCities as $k=>$v)
							       {
							           echo '<button type="button" class="tag_btn_default tag_btn_primary btn_area" onClick="this.parentNode.removeChild(this)">'
                                            .'<em>'.$v['pname'].$v['cname'].'</em><input type="hidden" name="province[]" value="'.$v['pcode'].'">'
                                            .'<input type="hidden" name="city[]" value="'.$v['ccode'].'"></button>';
							       }
							   }
							?>
						</div>
					</div>
				</div>
				-->
				<!--
				<div class="search_c_item">
					<span>客户价值</span>
					<input type="checkbox" id="customer_value_0" name="customer_value[0]"><label for="customer_value_0">高度重要保持</label>
					<input type="checkbox" id="customer_value_1" name="customer_value[1]"><label for="customer_value_1">重要保持</label>
					<input type="checkbox" id="customer_value_2" name="customer_value[2]"><label for="customer_value_2">重要价值</label>
					<input type="checkbox" id="customer_value_3" name="customer_value[3]"><label for="customer_value_3">重要发展</label>
					<input type="checkbox" id="customer_value_4" name="customer_value[4]"> <label for="customer_value_4">重要挽留</label>
					<input type="checkbox" id="customer_value_5" name="customer_value[5]"> <label for="customer_value_5">一般保持</label>
					<input type="checkbox" id="customer_value_6" name="customer_value[6]"> <label for="customer_value_6">一般挽留</label>
					<input type="checkbox" id="customer_value_7" name="customer_value[7]"> <label for="customer_value_7">一般发展</label>
				</div>
				-->
				<div class="more_area">
					<div class="search_c_item">
						<div class="search_c_item_es search_c_item_t">
							<span>支付宝服务窗</span>
							<input type="checkbox" id="alipay_status_0" name="alipay_status[0]" value='<?php echo ALIPAY_USER_SUBSCRIBE?>' <?php echo isset($_GET['alipay_status'][0]) && $_GET['alipay_status'][0] == ALIPAY_USER_SUBSCRIBE ? 'checked="checked"' : ''?>><label for="alipay_status_0">已关注</label>
							<input type="checkbox" id="alipay_status_1" name="alipay_status[1]" value='<?php echo ALIPAY_USER_CANCELSUBSCRIBE?>' <?php echo isset($_GET['alipay_status'][1]) && $_GET['alipay_status'][1] == ALIPAY_USER_CANCELSUBSCRIBE ? 'checked="checked"' : ''?>><label for="alipay_status_1">已取消</label>
							<input type="checkbox" id="alipay_status_2" name="alipay_status[2]" value='<?php echo ALIPAY_USER_NOTSUBSCRIBE?>' <?php echo isset($_GET['alipay_status'][2]) && $_GET['alipay_status'][2] == ALIPAY_USER_NOTSUBSCRIBE ? 'checked="checked"' : ''?>><label for="alipay_status_2">未关注</label>
						</div>
						<div class="search_c_item_es">
							<span>微信公众号</span>
							<input type="checkbox" id="wechat_status_0" name="wechat_status[0]" value='<?php echo WECHAT_USER_SUBSCRIBE?>' <?php echo isset($_GET['wechat_status'][0]) && $_GET['wechat_status'][0] == WECHAT_USER_SUBSCRIBE ? 'checked="checked"' : ''?>><label for="wechat_status_0">已关注</label>
							<input type="checkbox" id="wechat_status_1" name="wechat_status[1]" value='<?php echo WECHAT_USER_CANCELSUBSCRIBE?>' <?php echo isset($_GET['wechat_status'][1]) && $_GET['wechat_status'][1] == WECHAT_USER_CANCELSUBSCRIBE ? 'checked="checked"' : ''?>><label for="wechat_status_1">已取消</label>
							<input type="checkbox" id="wechat_status_2" name="wechat_status[2]" value='<?php echo WECHAT_USER_NOTSUBSCRIBE?>' <?php echo isset($_GET['wechat_status'][2]) && $_GET['wechat_status'][2] == WECHAT_USER_NOTSUBSCRIBE ? 'checked="checked"' : ''?>><label for="wechat_status_2">未关注</label>
						</div>
					</div>
					<div class="search_c_item">
						<div class="search_c_item_es search_c_item_t">
							<span>服务窗关注时间</span>
							<!--<input type="text" class="text_group text_time">-->
							<?php echo CHtml::textField('fw_time',isset($_GET['fw_time']) ? $_GET['fw_time'] : '',array('id'=>'fw_time','class'=>'text_group text_time','readonly'=>"readonly")); ?>
						</div>
						<div class="search_c_item_es">
							<span>公众号关注时间</span>
							<!--<input type="text" class="text_group text_time">-->
							<?php echo CHtml::textField('wx_time',isset($_GET['wx_time']) ? $_GET['wx_time'] : '',array('id'=>'wx_time','class'=>'text_group text_time','readonly'=>"readonly")); ?>
						</div>
					</div>
					<div class="search_c_item">
						<div class="search_c_item_es search_c_item_t">
							<span>注册会员时间</span>
							<!--<input type="text" class="text_group text_time">-->
							<?php echo CHtml::textField('reg_time',isset($_GET['reg_time']) ? $_GET['reg_time'] : '',array('id'=>'reg_time','class'=>'text_group text_time','readonly'=>"readonly")); ?>
						</div>
					</div>
					<div class="search_c_item">
						<div class="search_c_item_es search_c_item_t">
							<span>积分</span> 
							<label> 
						      <input type="text" class="user_text text_group" name="score_min" value='<?php echo isset($_GET['score_min'])?$_GET['score_min']:''?>' onkeyup="value=value.replace(/[^\d]/g,'')"> 
						      ~ 
						      <input type="text" class="user_text text_group"  name="score_max" value='<?php echo isset($_GET['score_max'])?$_GET['score_max']:''?>' onkeyup="value=value.replace(/[^\d]/g,'')"> 积分
							</label>
						</div>
						<div class="search_c_item_es search_c_item_t">
							<span>生日</span>
							<label>
							 <input type="text" class="user_text text_group" style="width:80px" id="birth" name='birth' value='<?php echo isset($_GET['birth'])?$_GET['birth']:''?>'> 
							</label>
						</div>
					</div>
					<div class="search_c_item">
						<span class="tag">最近消费门店</span>
						<div class="search_c_item_tag">
							<a href="javascript:;" class="tag_text" id="store_pop_btn">添加最近的消费门店</a> <em class="tips">支持多选</em>
							<div class="hidden" style="display:hidden">
    							<span id="store_ids"><?php if(!empty($store_ids)) echo implode(',', $store_ids).','?></span>
    				            <span id="store_names"><?php if(!empty($store_names)) echo implode(',', $store_names).','?></span>
							</div>
							
							<div class="consume_area" id="store_area">
							<?php 
							 if(!empty($store_ids))
							 {
							     foreach($store_ids as $k=>$v) {
						     ?>
						     
						     <button type="button" class="tag_btn_default tag_btn_primary btn_area" onclick="this.parentNode.removeChild(this)"><em><?php echo $store_names[$k]?></em> <input type="hidden" name="store_id[<?php echo $k?>]" value="<?php echo $v?>"><input type="hidden" name="store_name[<?php echo $k?>]" value="<?php echo $store_names[$k]?>"></button>
						     <?php 
							     }
							 }
							?>
							</div>
						</div>
					</div>
					<!--
					<div class="search_c_item">
						<span class="tag">标签</span>
						<div class="search_c_item_tag">
							<div class="tag-item">
								<input type="checkbox" id="tag_0" name="tag[0]"><label for="tag_0" class="tag_text">消费能力：</label>
								<input type="checkbox" id="tag_0_0" name="tag[0][0]"><label for="tag_0_0">低消费</label>
								<input type="checkbox" id="tag_0_1" name="tag[0][1]"><label for="tag_0_1">高消费</label>
							</div>
							<div class="tag-item">
								<input type="checkbox" id="tag_1" name="tag[1]"><label for="tag_1" class="tag_text">流失情况：</label>
								<input type="checkbox" id="tag_1_0" name="tag[1][0]"><label for="tag_1_0">未流失</label>
								<input type="checkbox" id="tag_1_1" name="tag[1][1]"><label for="tag_1_1">流失客户</label>
							</div>
							<div class="tag-item">
								<input type="checkbox" id="tag_2" name="tag[2]"><label for="tag_2" class="tag_text">消费频次：</label>
								<input type="checkbox" id="tag_2_0" name="tag[2][0]"><label for="tag_2_0">散客</label>
								<input type="checkbox" id="tag_2_1" name="tag[2][1]"><label for="tag_2_1">常客</label>
							</div>
						</div>
					</div>
					-->
				</div>
				<!--
				<div class="user_search">
					<input type="button" value="更多选项" class="btn_com_blue btn_filtrate">
				</div>
				-->
				<div class="btn_filtrate_wrap">
                    <a href="javascript:;" class="btn_filtrate">更多选项</a>
                </div>
			</div>
			
			<input type="hidden" name="sort" value="<?php echo isset($_GET['sort']) ? $_GET['sort'] : ''?>">
            <input type="hidden" name="integ" value="<?php echo isset($_GET['integ']) ? $_GET['integ'] : ''?>">
            <input type="hidden" name="Focus" value="<?php echo isset($_GET['Focus']) ? $_GET['Focus'] : ''?>">
            <input type="hidden" name="consumption" value="<?php echo isset($_GET['consumption']) ? $_GET['consumption'] : ''?>">
            <input type="hidden" name="regist" value="<?php echo isset($_GET['regist']) ? $_GET['regist'] : ''?>">
            <input type="hidden" name="xf_money" value="<?php echo isset($_GET['xf_money']) ? $_GET['xf_money'] : ''?>">
            <?php echo CHtml::endForm()?>
			<div class="user_list">
				<div class="user_list_top">
					<input type="checkbox" id="checkall"> <label for="checkall">全选</label>
					<div class="level_r add_group_btn">
						<input type="button" class="btn_com_blue disabled" id="add_group" value="添加到分组" disabled>
						<div class="pop_tag pop_group" id="pop_group">
							<div class="pop_arrow"></div>
							<div class="pop_tag_top pop_tag_active">
								<h3>添加到分组</h3>
							</div>
							<div class="pop_group_con" id="pop_group_con" data-count='<?php echo count($user_groups)?>'>
							<?php foreach ($user_groups as $k => $v) { ?>                    
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
						<span class="sort">排序</span><i id="sort"></i>
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
                        <span onclick="Focusasc()">微信关注时间<i id="Focuss" style="display: none"></i></span>
                        <?php } else { ?>
                        <span onclick="Focusdesc()">微信关注时间<i id="Focuss" style="display: none"></i></span>
                        <?php } ?>
                        
                        <?php if(isset($_GET['regist']) && $_GET['regist'] == 'desc') { ?>
                        <span onclick="registasc()">注册时间<i id="Regists" style="display: none"></i></span>
                        <?php } else { ?>
                        <span onclick="registdesc()">注册时间<i id="Regists" style="display: none"></i></span>
                        <?php } ?>
                        <!--
                        <?php if(isset($_GET['consumption']) && $_GET['consumption'] == 'desc') { ?>
                        <span onclick="consumptionasc()">消费时间<i id="consumptions" style="display: none"></i></span>
                        <?php } else { ?>
                        <span onclick="consumptiondesc()">消费时间<i id="consumptions" style="display: none"></i></span>
                        <?php } ?>
                        
                        <?php if(isset($_GET['xf_money']) && $_GET['xf_money'] == 'desc') { ?>
                        <span onclick="xf_moneyasc()">累计消费金额<i id="xf_moneys" style="display: none"></i></span>
                        <?php } else { ?>
                        <span onclick="xf_moneydesc()">累计消费金额<i id="xf_moneys" style="display: none"></i></span>
                        <?php } ?>
                        -->
                        <!--
						<span>年龄<i></i></span> 
						<span>积分<i></i></span>
						<span>关注时间<i></i></span>
						<span>注册时间<i></i></span>
						<span>消费时间<i></i></span>
						<span>累计消费金额<i></i></span>
						-->
					</div>
				</div>
				<div class="user_list_top user_list_top_second">
					<ul class="user_list_th">
						<li><!--<span>用户信息</span>--></li>
						<li><span>用户信息</span></li>
						<li><span>会员信息</span></li>
						<li><span>关注时间</span></li>
						<li><span>最近消费</span></li>
						<!--<li><span>累计消费金额</span></li>-->
					</ul>
				</div>
				
				<?php if (!empty($users)) {
                        foreach ($users as $k => $v) { ?>
				<div name="discuss" class="user_list_middle clearfix">
				    <div class="td_item td_item_checkbox">
                        <div class="user_info_checkbox">
                            <input type="checkbox" value="<?php echo $v['id'] ?>" id="user<?php echo $v['id'] ?>">
                            <label for="user<?php echo $v['id'] ?>"></label>
                        </div>
                    </div>
                    <a href="<?php echo $this->createUrl('userInfo',array('id'=>$v['id']))?>">
					<div class="td_item td_item_head">
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
                                        if ($v['sex'] == SEX_MALE)
                                            $img = GJ_STATIC_IMAGES.'face_man.png';
                                        if($v['sex'] == SEX_FEMALE)
                                            $img = GJ_STATIC_IMAGES.'face_woman.png';
                                    }
                                }
                                ?>
							<img src="<?php echo $img?>" alter="<?php echo isset($v['nickname']) ? $v['nickname'] : '&nbsp;'?>" title="<?php echo $v['nickname'] ? $v['nickname'] : '&nbsp;'?>" style="cursor: pointer;" onclick="window.location.href='<?php echo $this->createUrl('userDetail',array('id'=>$v['id']))?>'">
						</div>
						<div class="user_info">
						<?php
						      $vname = '';
						      if($v['nickname'])
						          $vname = $v['nickname'];
						      if($v['name'])
						          $vname = $v['name'];
						?>
							<span class="name" title="<?php echo $vname ?$vname : '&nbsp;'?>"  style="cursor: pointer;" onclick="window.location.href='<?php echo $this->createUrl('userDetail',array('id'=>$v['id']))?>'"><?php echo $vname?$vname:'&nbsp;'?></span>
							<span>
								<em class="sex"><?php echo isset($v['sex']) ? $GLOBALS['__SEX'][$v['sex']] : ''?></em>
                                <?php if(!empty($v['birthday'])){?>
                                <em class="age">
                                    <?php echo (date('Y-m-d H:i:s')-$v['birthday']).'岁';?>
                                </em>
                                <?php }?>
                                </span>
                            <span>
                                <?php echo !empty($v['province']) ? $v['province'] : '' ?>
                                <?php echo !empty($v['province']) && !empty($v['city']) ? '-' : '' ?>
                                <?php echo !empty($v['city']) ? $v['city'] : '' ?>
                            </span>
						</div>
					</div>
					<div class="td_item">
						<?php if($v['type'] == USER_TYPE_WANQUAN_MEMBER) {?>
                        <span>
                        <?php echo $v['grade_name'];?>
                        <em class="user_info_grade">（积分
                        <?php echo $v['points']?$v['points']:0?>
                                                                        ）</em></span>
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
					<?php
					   if(empty($v['last_trade_store'])) { ?>
					       <span>&nbsp;</span><span>&nbsp;</span>
                       <?php } else { ?>
                           <span><?php echo $store_lists[$v['last_trade_store']]['name'] ?></span>
                           <span><?php echo $v['last_trade_time'] ?></span>
                       <?php } ?>
					</div>
                        <!--<div class="td_item">
                            <span>&nbsp;</span>
                            <span><?php /*echo $v['total_trade']; */?></span>
                        </div>-->
				    </a>
				</div>
				<?php } } ?>
			</div>
			
			<div class="r">
				<div class="page1">
					<?php $this -> widget('CLinkPager',array(
                        'pages'=>$pages,
                        'header'=>'共&nbsp;<strong class="orange">'.$pages -> getItemCount().'</strong>&nbsp;条&nbsp;',
                        'prevPageLabel' => '上一页',
                        'nextPageLabel'=>'下一页',
                        'maxButtonCount'=>8
                    ));?>
				</div>
			</div>
		</div>
	</div>
</div>
<input type="hidden" id="user_list">
    <?php echo CHtml::beginForm($this->createUrl('addtoGroup'), 'post', array('id' => 'group_form'))?>
    	<input type="hidden" id="groups" name="groups" value/>
    	<input type="hidden" id="users-g" name="users" value/>
    <?php echo CHtml::endForm()?>
    <?php echo CHtml::beginForm($this->createUrl('addTag'), 'post', array('id' => 'tag_form'))?>
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

    <?php
    
if (! empty($_GET['store_id']) || ! empty($_GET['fw_time']) || ! empty($_GET['reg_time']) || ! empty($_GET['wx_time']) || ! empty($_GET['wechat_status']) || ! empty($_GET['alipay_status']) || ! empty($_GET['score_min']) || ! empty($_GET['score_max']) || ! empty($_GET['birth'])) {
        ?>
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
        swapBtnAddGroup();				
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
        $('input[name=xf_money]').val('');
        $('input[name=regist]').val('');
        document.getElementById("search_form").submit();            
    }
    //年龄升序
    function ageasc(){
        $('input[name=sort]').val('asc');
        $('input[name=integ]').val('');
        $('input[name=Focus]').val('');
        $('input[name=consumption]').val('');
        $('input[name=xf_money]').val('');
        $('input[name=regist]').val('');
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
        $('input[name=xf_money]').val('');
        $('input[name=regist]').val('');
        document.getElementById("search_form").submit();        
    }
    //积分升序
    function integasc(){
        $('input[name=sort]').val('');
        $('input[name=integ]').val('asc');
        $('input[name=Focus]').val('');
        $('input[name=consumption]').val('');
        $('input[name=xf_money]').val('');
        $('input[name=regist]').val('');
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
        $('input[name=xf_money]').val('');
        $('input[name=regist]').val('');
        document.getElementById("search_form").submit();            
    }
    //关注时间升序
    function Focusasc(){
        $('input[name=sort]').val('');
        $('input[name=integ]').val('');
        $('input[name=Focus]').val('asc');
        $('input[name=consumption]').val('');
        $('input[name=xf_money]').val('');
        $('input[name=regist]').val('');
        document.getElementById("search_form").submit();            
    }

    <?php if(!empty($_GET['regist']) && $_GET['regist'] == 'desc') { ?>
    $('#Regists').show();
    <?php } else if(!empty($_GET['regist']) && $_GET['regist'] == 'asc') { ?>
        $('#Regists').show();
        $('#Regists').toggleClass("arrow_up_active");
        $('#sort').toggleClass("arrow_up_active");
    <?php } ?>
    //注册时间降序
	function registdesc()
	{
		$('input[name=sort]').val('');
        $('input[name=integ]').val('');
        $('input[name=Focus]').val('');
        $('input[name=consumption]').val('');
		$('input[name=xf_money]').val('');
        $('input[name=regist]').val('desc');
        document.getElementById("search_form").submit();  
	}
    //注册时间升序
    function registasc()
    {
    	$('input[name=sort]').val('');
        $('input[name=integ]').val('');
        $('input[name=Focus]').val('');
        $('input[name=consumption]').val('');
		$('input[name=xf_money]').val('');
        $('input[name=regist]').val('asc');
        document.getElementById("search_form").submit(); 
    }


    <?php if(!empty($_GET['xf_money']) && $_GET['xf_money'] == 'desc') { ?>
    $('#xf_moneys').show();
    <?php } else if(!empty($_GET['xf_money']) && $_GET['xf_money'] == 'asc') { ?>
        $('#xf_moneys').show();
        $('#xf_moneys').toggleClass("arrow_up_active");
        $('#sort').toggleClass("arrow_up_active");
    <?php } ?>
    function xf_moneydesc()
    {
    	$('input[name=sort]').val('');
        $('input[name=integ]').val('');
        $('input[name=Focus]').val('');
        $('input[name=consumption]').val('');
		$('input[name=xf_money]').val('desc');
        $('input[name=regist]').val('');
        document.getElementById("search_form").submit();
    }

    function xf_moneyasc()
    {
    	$('input[name=sort]').val('');
        $('input[name=integ]').val('');
        $('input[name=Focus]').val('');
        $('input[name=consumption]').val('');
		$('input[name=xf_money]').val('asc');
        $('input[name=regist]').val('');
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
        $('input[name=xf_money]').val('');
        $('input[name=regist]').val('');
        document.getElementById("search_form").submit();           
    }
    //消费时间升序
    function consumptionasc(){
        $('input[name=sort]').val('');
        $('input[name=integ]').val('');
        $('input[name=Focus]').val('');
        $('input[name=consumption]').val('asc');
        $('input[name=xf_money]').val('');
        $('input[name=regist]').val('');
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
	    			obj = $.ajax({url:"/index.php/mCenter/user/getOneUserGroup?uid="+users[0], async:false});
	    			wapperGroup(obj.responseText);
		    	} else {
		    		initSome();
			    }
	    	}
	    	else
	    	{
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

		function choseArea() 
		{
			var province = {};
			var city = {};
			
			province['code'] = $("#province").val();
			province['name'] = $("#province option[value="+province['code']+"]").text();
			city['code'] = $("#city").val();
			city['name'] = $("#city option[value="+city['code']+"]").text();
			
			if(province['code'] && city['code'])
			{
				var btnHTML = '<button type="button" class="tag_btn_default tag_btn_primary btn_area" onclick="this.parentNode.removeChild(this)"><em>'+province['name']+city['name']+'</em> <input type="hidden" name="province[]" value="'+province['code']+'"><input type="hidden" name="city[]" value="'+city['code']+'"></button>';
				var cityCodes = new Array();
				var i = 0;
				$("input[name='city[]']").each(function(){
					cityCodes[i] = $(this).val();
					i++;
				}
				);

				if($.inArray(city['code'], cityCodes) == -1)
					$(".area").append(btnHTML);
			}
		}

		$(function() {
            $("#store_pop_btn").click(function(){

    			var store_ids = $("#store_ids").html().split(',');
				var param = '?status=1';
    			$.each(store_ids, function(i, item){
    				param += '&store_id[]='+item;
    			});
             
                layer.open({
                    type:2,
                    title: '',
                    maxmin: false,
                    shadeClose: true, //点击遮罩关闭层
                    area : ['680px',"570px"],
                    content: '/crm/user/popStore'+param,
                });
            });
    });
    // 自动调整iframe的高度适应自身高度
    $(".btn_filtrate").click(function() {
    	var more_height = $('.more_area').height();
    	var main_frame	= $('#main', window.parent.document);
    	var status = $(this).html();
    	if(status == '更多选项')
    	{
    		h = main_frame.height()+more_height;
    		main_frame.css("height", h);
    	}
    	if(status == '精简选项')
    	{
    		h = main_frame.height()-more_height;
    		main_frame.css("height", h);
    	}
    });

    function swapBtnAddGroup()
    {
    	var users = getUser();
		//分组数
    	var g_count = $('#pop_group_con').attr('data-count');
    	if(users[0] && g_count!=0){
        	$("#add_group").removeAttr('disabled');
        	$("#add_group").removeClass('disabled');
    	}
    	else
    	{
    		$("#add_group").attr('disabled', 'true');
        	$("#add_group").addClass('disabled');
    	}
    }

    $(".user_info_checkbox input").click(function(){
    	swapBtnAddGroup();
    });
	
</script>