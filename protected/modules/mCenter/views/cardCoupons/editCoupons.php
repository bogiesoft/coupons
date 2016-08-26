
<body>
<script type="text/javascript" src="<?php echo SYT_STATIC_JS?>artDialog/jquery.artDialog.js?skin=simple"></script>
<script type="text/javascript" src="<?php echo SYT_STATIC_JS?>artDialog/plugins/iframeTools.js"></script>
<script type="text/javascript">
//$(document).ready(statistics_obj.stat_init);
</script>
<style type="text/css">
label.errormsg{
background:url("<?php echo GJ_STATIC_IMAGES ?>error.png") no-repeat 0px 0px;
color:red;
padding-left: 16px;
padding-bottom: 2px;
font-weight: bold;
}
</style>
<div class="kkfm_r_inner">
	<div class="top">
        <div class="name">编辑优惠券</div>
    </div>
    
	<div class="contant clearfix">
    	<div class="coupon_l">
        	<div class="title">优惠券</div>
            <div id="left_bgColor" class="hd <?php echo $model['color']; ?>">
            	<h2><img src="<?php echo IMG_GJ_LIST.$merchant_logo; ?>" width="46"><?php echo $model['merchant_short_name']; ?></h2>
                <div class="name1"><?php echo $model['title']; ?></div>
                <p id="left_vice_title"><?php echo $model['vice_title']; ?></p>
                <p id="left_time1">
                <?php if($model['time_type'] == VALID_TIME_TYPE_FIXED){ ?>
                                             有效期： <?php echo date('Y.m.d',strtotime($model['start_time'])).'-'.date('Y.m.d',strtotime($model['end_time'])) ?>
                <?php }else{?>
                  
                      <?php if($model['start_days'] != 0) {?>
                	      <?php echo '领取后'.$model['start_days'].'天生效，有效'.$model['effective_days'].'天'; ?>
                	      <?php }else{?>
                	      <?php echo '领取后当天生效，有效'.$model['effective_days'].'天'; ?>
                	      <?php }?>
                 
                <?php }?> 
                </p>
            </div>
            <div class="barCode">
            	<img src="<?php echo GJ_STATIC_IMAGES; ?>code.png">
                <p>123121513123</p>
                <p id="left_prompt"><?php echo $model['prompt']; ?></p>
            </div>
            <div class="detail">
            	<h3>优惠券详情</h3>
                <dl class="clearfix">
                	<dt>优惠说明</dt>
                	<dd id="left_discount_illustrate"> 
                	<?php if($model['type'] == COUPON_TYPE_CASH){ ?>
                	  <?php if($model['money_type'] == FACE_VALUE_TYPE_FIXED){ ?>
                	   <?php if(empty($model['mini_consumption'])){ ?>
                	      <?php echo $model['money'] ?>元代金券一张，任意金额可用
                	   <?php }else{?>
                	    <?php echo $model['money'] ?>元代金券一张，满<?php echo $model['mini_consumption'].'元可用'; ?>
                	  <?php }?>
                	  
                	 <?php }else{?>
                	   <?php $arr_moner = explode(',', $model['money_random']); ?>
                	   <?php if(empty($model['mini_consumption'])){ ?>
                	      <?php echo $arr_moner[1].'到'.$arr_moner[2].'元随机券'; ?>一张，任意金额可用
                	   <?php }else{?>
                	    <?php echo $arr_moner[1].'到'.$arr_moner[2].'元随机券'; ?>一张，满<?php echo $model['mini_consumption'].'元可用'; ?>
                	  <?php }?>
                	  <?php }?>
                	 <?php }elseif ($model['type'] == COUPON_TYPE_DISCOUNT){?>
                	     <?php if(empty($model['mini_consumption'])){ ?>
                	      <?php echo $model['discount']*10; ?>折折扣券一张，任意金额可用
                	   <?php }else{?>
                	    <?php echo $model['discount']*10; ?>折折扣券一张，满<?php echo $model['mini_consumption'].'元可用'; ?>
                	  <?php }?>
                	 <?php }else{?>
                	   <?php echo $model['discount_illustrate']; ?>
                	 <?php }?>
                	 </dd>
                </dl>
                <dl class="clearfix">
                	<dt>有效日期</dt>
                	<dd id="left_time2">
                	    <?php if($model['time_type'] == VALID_TIME_TYPE_FIXED){ ?>
                      <p> <?php echo date('Y.m.d',strtotime($model['start_time'])).'-'.date('Y.m.d',strtotime($model['end_time'])) ?></p>
                      <?php }else{?>
                      <p>
                      <?php if($model['start_days'] != 0) {?>
                	      <?php echo '领取后'.$model['start_days'].'天生效，有效'.$model['effective_days'].'天'; ?>
                	      <?php }else{?>
                	      <?php echo '领取后当天生效，有效'.$model['effective_days'].'天'; ?>
                	      <?php }?>
                      </p>
                      <?php }?>
                	</dd>
                </dl>
                <dl class="clearfix">
                	<dt>客服电话</dt>
                	<dd id="left_tel"><?php echo $model['tel']; ?></dd>
                </dl>
                <dl class="clearfix">
                	<dt>使用须知</dt>
                	<dd id="left_use_illustrate">
                	  <p id="left_receive_num"> <?php echo '每人限领'.$model['receive_num'].'张'.'<br>'; ?></p>
                	   
                	  <p id="left_if_with_discount"> <?php if($model['type'] != COUPON_TYPE_EXCHANGE){ ?> <!-- 如果不是兑换券    -->
                	      <?php if($model['if_with_userdiscount'] == IF_WITH_USERDISCOUNT_NO){ ?>
                	        <?php echo '该券不可与会员折扣同时使用'.'<br>'; ?>
                	      <?php }else{?>
                	        <?php echo '该券可与会员折扣同时使用'.'<br>'; ?>
                	      <?php }?>
                	   <?php }?></p>
                	   
                	    <p id="left_store_limit"> <?php echo '适用门店：' ?>
                	     <span id="left_store_list">
                	    
                	   <?php if(!empty($model['store_limit_name'])){ ?>
                	         <?php if($count_store != count($model['store_limit_name'])){ ?>  <!-- 选中一部分     适用门店一个个显示出来 -->
                        	  <?php foreach ($model['store_limit_name'] as $key=>$val){ ?>
                        	    <?php echo $val.'&nbsp;&nbsp;'; ?>
                        	  <?php }?>
                        	  <?php }else{?> <!-- 选中全部     适用门店显示'全部门店' -->
                        	  <?php echo '全部门店'; ?>
                        	  <?php }?>
                        	<?php }?>
                        	</span>
                	   <?php echo '<br>'; ?></p>
                	   
                	  <p id="left_illustrate"> <?php echo str_replace("\n","<br>", $model['use_illustrate']); ?></p>
                	</dd>
                </dl>
                <div class="clear"></div>
            </div>
        </div>
    	<div class="coupon_r">
        	<!--代金券的内容-->
        	<?php echo CHtml::beginForm('','post',array('id'=>'editForm')); ?>
        	<div class="bg" style="display:">
                <div class="section">
                    <div class="title">优惠券类型</div>
                    <div class="typeSelect">
                    	<!--couponRadio[]中括号里有元素的话需要在最下方js改动下-->
                    	<?php echo CHtml::radioButtonList('Coupons[type]', $model['type'], $coupon_type,array('separator'=>'','disabled'=>'disabled')); ?>                  	
                    </div>
                </div>
                <div class="section">
                    <div class="title">基本信息</div>
                    <dl class="clearfix">
                        <dt>商户简称</dt>
                        <dd>
                         <?php echo CHtml::textField('Coupons[merchant_short_name]',$model['merchant_short_name'],array('class'=>'txt','placeholder'=>'最多12个汉字','readonly'=>true)); ?>
                         <div class="error1"><?php echo Yii::app()->user->getFlash('short_name_error');?></div>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>同步发布</dt>
                        <dd>
                            <?php echo CHtml::checkBox('Coupons[if_wechat]',$model['if_wechat'] == 1? '': 2); ?>微信卡包
                            <div class="remark">同步到微信卡包，需要你的公众号开通卡券功能</div>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>券颜色</dt>
                        <dd>
                            <div class="selectMode">
                            	<div class="show">
                            	    <input id="Couponscolor" type="hidden" name="Coupons[color]" value="<?php echo $model['color']; ?>" />
                                	<div id="color" class="<?php echo $model['color']; ?>"></div>
                                    <em></em>
                                </div>
                                <ul class="clearfix">
                                	<li class="Color082"></li>
                                	<li class="Color010"></li>
                                	<li class="Color020"></li>
                                	<li class="Color030"></li>
                                	<li class="Color040"></li>
                                	<li class="Color050"></li>
                                	<li class="Color102"></li>
                                	<li class="Color060"></li>
                                	<li class="Color070"></li>
                                	<li class="Color080"></li>
                                	<li class="Color081"></li>
                                	<li class="Color090"></li>
                                	<li class="Color100"></li>
                                	<li class="Color101"></li>
                                </ul>
                            </div>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>券标题</dt>
                        <dd>
                            <?php echo CHtml::textField('Coupons[title]',$model['title'],array('class'=>'txt','placeholder'=>'最多9个汉字','maxlength'=>9)); ?>
                            <div class="error1"><?php echo Yii::app()->user->getFlash('title_error');?></div>
                            <div class="remark">建议填写优惠券提供的服务或商品名称，描述卡券提供的具体优惠</div>
                        </dd>
                    </dl>
                    <dl class="cMoney clearfix"><!--只有在代金券里才能看到则“只加”这个样式cMoney-->
                        <dt>券金额</dt>
                        <dd>
                            <!-- 固定金额输入框 -->
                            <?php //if($model['money_type'] == FACE_VALUE_TYPE_FIXED){ ?>
                            <span style="display: <?php echo  $model['money_type'] == FACE_VALUE_TYPE_FIXED?'':'none';?>" id="money_id"><?php echo CHtml::textField('Coupons[money]',$model['money'],array('class'=>'txt','style'=>'width:100px')); ?> 元</span>
                         <!--   <div class="error1"><?php //echo Yii::app()->user->getFlash('money_error');?></div>-->
                          <?php //}else{?>
                           <!-- 随机金额输入框 -->
                            <?php $arr = explode(',', $model['money_random']); ?>
                            <span id="start_money_id" style="display:<?php echo  $model['money_type'] == FACE_VALUE_TYPE_FIXED?'none':'';?>"><?php echo CHtml::textField('Coupons[start_money]',isset($arr[1])?$arr[1]:'',array('class'=>'txt','style'=>'width:100px')); ?> </span>
                            <span id="end_money_id" style="display:<?php echo  $model['money_type'] == FACE_VALUE_TYPE_FIXED?'none':'';?>">到 <?php echo CHtml::textField('Coupons[end_money]',isset($arr[2])?$arr[2]:'',array('class'=>'txt','style'=>'width:100px')); ?> 元</span> 
                           <!--  <div class="error1"><?php //echo Yii::app()->user->getFlash('money_random_error');?></div>-->
                           <?php //}?>
                            <?php echo CHtml::checkBox('Coupons[money_type]',$model['money_type']==1?1:''); ?>
                            <span id="money_text">固定金额</span>
                            <div class="remark">同步到微信卡包时，不支持随机面额</div>
                        </dd>
                    </dl>
                    <dl class="cDiscount clearfix" style="display:none"><!--只有在折扣券里才能看到则“只加”这个样式cDiscount-->
                        <dt>券折扣</dt>
                        <dd>
                             <?php echo CHtml::textField('Coupons[discount]',$model['discount']*10,array('class'=>'txt','maxlength'=>3)); ?>折
                            <div class="remark">请填写1-9.9之间的数字，精确到小数点后1位</div>
                            <div class="error1"><?php echo Yii::app()->user->getFlash('discount_error');?></div>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>副标题<div class="gray">（选填）</div></dt>
                        <dd>
                            <?php echo CHtml::textField('Coupons[vice_title]',$model['vice_title'],array('class'=>'txt','placeholder'=>'最多18个汉字','maxlength'=>18)); ?>
                            <div class="remark">建议填写优惠券使用的条件</div>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>提示操作</dt>
                        <dd>
                            <?php echo CHtml::textField('Coupons[prompt]',$model['prompt'],array('class'=>'txt','placeholder'=>'最多16个汉字','maxlength'=>16)); ?>
                            <div class="error1"><?php echo Yii::app()->user->getFlash('prompt_error');?></div>
                            <div class="remark">建议引导用户到店出示卡券，由店员完成核销操作</div>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dd>
                            <?php echo CHtml::checkBox('Coupons[if_share]',$model['if_share']==1?2:''); ?> 用户可以分享领取链接
                        </dd>
                    </dl>
                    <dl class="clearfix" id="if_give">
                        <dd>
                           <?php echo CHtml::checkBox('Coupons[if_give]',$model['if_give']==1?2:''); ?> 用户领券后可以转赠给其他好友
                        </dd>
                    </dl>
                </div>
                
                <div class="section">
                    <div class="title">基本规则</div>
                    <dl class="clearfix">
                        <dt>发行量</dt>
                        <dd> <?php echo CHtml::textField('Coupons[num]',$model['num'],array('class'=>'txt','readonly'=>true)); ?> 份</dd>                        
                    </dl>
                    <div class="error1"><?php echo Yii::app()->user->getFlash('num_error');?></div>
                    <dl class="clearfix">
                        <dt>有效时间</dt>
                        <dd>
                            <div class="block">
                                <input id="valid_time" type="radio" <?php echo $model['time_type']==1?'checked':''; ?> name="Coupons[time_type]" value="1"> 固定时间
                                 <?php echo CHtml::textField('Time',isset($model['start_time'])?date('Y/m/d',strtotime($model['start_time'])).'-'.date('Y/m/d',strtotime($model['end_time'])):'',array('class'=>'txt','readonly'=>"readonly")); ?>
                            </div>
                            <div class="error1"><?php echo Yii::app()->user->getFlash('valid_time_error');?></div>
                            <div class="block">
                                <input type="radio" <?php echo $model['time_type']==2?'checked':''; ?> name="Coupons[time_type]" value="2"> 领取后，
                                <?php echo CHtml::dropDownList('Coupons[start_days]', $model['start_days'], $start_days_arr); ?>
                                生效，有效天数
                                <?php echo CHtml::dropDownList('Coupons[effective_days]', $model['effective_days'], $effective_days_arr); ?>
                            </div>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>领取限制<div class="gray">（选填）</div></dt>
                        <dd>
                            <?php echo CHtml::textField('Coupons[receive_num]',$model['receive_num'],array('class'=>'txt')); ?>
                            <div class="remark">每个用户领券上限，如不填，则默认为1</div>
                        </dd>
                    </dl>
                    <dl class="lowxf clearfix">
                        <dt>最低消费<div class="gray">（选填）</div></dt>
                        <dd>
                            <?php echo CHtml::textField('Coupons[mini_consumption]',$model['mini_consumption'],array('class'=>'txt')); ?>
                            <div class="remark">消费满指定金额才能使用，如不填，则默认为不限制</div>
                        </dd>
                    </dl>
                    <dl class="cMoney clearfix">
                        <dt>使用限制<div class="gray">（选填）</div></dt>
                        <dd>
                            <?php echo CHtml::textField('Coupons[use_restriction]',$model['use_restriction'],array('class'=>'txt')); ?>
                            <div class="error1"><?php echo Yii::app()->user->getFlash('use_restriction_error');?></div>
                            <div class="remark">单笔订单使用张数，如不填，则默认为1</div>
                        </dd>
                    </dl>
                    <dl class="useWithDiscount clearfix">
                        <dd>
                            <?php echo CHtml::checkBox('Coupons[if_with_userdiscount]',$model['if_with_userdiscount']==1?'':'checked'); ?>可与会员折扣同时使用
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>可用门店<div class="gray">（选填）</div></dt>
                        <dd>
                            <input type="hidden" value="<?php echo $store_id_str; ?>" id="str_id" name="Coupons[store_limit]" />
                        	<ul class="storeTag">
                        	<?php if(!empty($model['store_limit_name'])){ ?>
                        	  <?php foreach ($model['store_limit_name'] as $key=>$val){ ?>
                        	    <li id="<?php echo $key; ?>"><?php echo $val; ?></li>
                        	  <?php }?>
                        	<?php }?>
                            </ul>
                            <a href="javascript:void(0);" class="blue" onclick="addStore()">添加门店</a>
                            <div class="remark">请选择可使用的门店，如不选择，则默认为不限制门店</div>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>客服电话<div class="gray">（选填）</div></dt>
                        <dd>
                            <?php echo CHtml::textField('Coupons[tel]',$model['tel'],array('class'=>'txt')); ?>
                        </dd>
                    </dl>
                    <dl class="cExchange clearfix" style="display:none"><!--只有在兑换券里才能看到则“只加”这个样式cDiscount-->
                        <dt>优惠说明</dt>
                        <dd>
                            <?php echo CHtml::textArea('Coupons[discount_illustrate]',$model['discount_illustrate']); ?>
                            <div class="error1"><?php echo Yii::app()->user->getFlash('discount_illustrate_error');?></div>
                           
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>使用须知</dt>
                        <dd>
                           <?php echo CHtml::textArea('Coupons[use_illustrate]',$model['use_illustrate'],array('maxlength'=>300)); ?>
                            <div class="error1"><?php echo Yii::app()->user->getFlash('use_illustrate_error');?></div>
                            <div class="remark">使用须知不能为空且长度不超过300个汉字</div>
                        </dd>
                    </dl>
                </div>
        	</div>
        	
            <input type="hidden" id="store_str" value=""><!-- 定义父窗口隐藏域   存放弹出框选中的门店 -->
            <div class="btnWrap">
            	<input type="submit" value="保存" class="btn_com_blue">
            </div>
            <?php echo CHtml::endForm(); ?>
        </div>
    </div>
</div> 



<script>

     window.onload=init;
     function init(){

     //给隐藏域赋值（数据库存在的门店id）    
     var str = '<?php echo $model['store_limit']; ?>';
     str = str.substr(1); //去掉字符串第一个逗号
     $('#store_str').val(str); //给隐藏域赋值

     	//validate.js验证
         $("#editForm").validate({
             rules : {                
                 "Coupons[title]" : {
                	 required:true
                 },
                 "Coupons[prompt]" : {
                	 required:true
                 },
                 "Coupons[num]" : {
                	 required:true,
                	 number : true,
                	 min : 1,
                	 digits:true
                 },
                 "Coupons[use_illustrate]" : {
                	 required:true
                 },
                 "Coupons[discount_illustrate]" : {
                	 required:true
                 },
                 "Coupons[start_money]" : {
                	 required:true,
                	 number : true,
                	 min : 0
                 },
                 "Coupons[end_money]" : {
                	 required:true,
                	 number : true,
                	// min : 1
                 },
                 "Coupons[money]" : {
                	 required:true,
                	 number : true,
                	 min : 1
                 },
                 "Time" :{
                     required:"#valid_time:checked" //固定时间单选框选中   才验证   
                  	 //required:function(){return $('#valid_time').prop('checked');}	 
                 },
                 "Coupons[discount]" : {
                	 required:true,
                	 number : true,
                	 min : 1,
                	 max:9.9
                 },
                 "Coupons[mini_consumption]" : {
                	 number : true,
                	 min : 1
                 },
                 "Coupons[merchant_short_name]" :{
                     required : true,
                     maxlength : 12
                 }
             },
             messages : {
            	 "Coupons[title]" : {
                	 required:"券标题必填"
                 },
                 "Coupons[prompt]" : {
                	 required:"提示操作必填"
                 },
                 "Coupons[num]" : {
                	 required:"发放量必填",
                	 number:"发放量不合法",
                	 min:"发放量不合法",
                	 digits:"发放量不合法"
                 },
                 "Coupons[use_illustrate]" : {
                	 required:"使用须知必填"
                 },
                 "Coupons[discount_illustrate]" : {
                	 required:"优惠说明必填"
                 },
                 "Coupons[start_money]" : {
                	 required:"随机金额必填",
                	 number:"随机金额不合法",
                	 min:"随机金额不合法"
                 },
                 "Coupons[money]" : {
                	 required:"固定金额必填",
                	 number : "固定金额设置不合法",
                	 min : "固定金额设置不合法"
                 },
                 "Coupons[end_money]" : {
                	 required:"随机金额必填",
                	 number:"随机金额不合法",
                	 min:"随机金额不合法"
                 },
                 "Time":{
                	 required:"固定时间必填" 
                 },
                 "Coupons[discount]" : {
                	 required:"折扣数必填",
                	 number:"折扣设置不合法",
                	 min:"折扣设置不合法",
                	 max:"折扣设置不合法"
                 },
                 "Coupons[mini_consumption]" :{
                	 number:"最低消费不合法",
                	 min : "最低消费不合法"
                 },
                 "Coupons[merchant_short_name]" :{
                     required : "商户简称必填",
                     maxlength : "长度不得超过12个字"
                 }
             }
         });

    	 if($('#Coupons_if_wechat').prop("checked")){//勾选微信卡包
        	 $("#if_give").show();
         }else{
        	 $("#if_give").hide();
         } 
         
    	 var val = '<?php echo $model['type']; ?>';
    	 if(val == '') {
          	document.getElementById('Coupons_type_0').checked = true;
          }else{
        	  $("#Coupons_type input").each(function() {
                	if(val == $(this).val()) {
                    	$(this).attr("checked",true);
                    	if($(this).val() == <?php echo COUPON_TYPE_CASH; ?>){
                    		$(".cMoney").show();
                			$(".cDiscount").hide();
                			$(".cExchange").hide();

                			if($('#Coupons_money_type').prop("checked")){ //勾选固定金额
             		           $('#end_money_id').hide(); // 随机金额结束金额输入框隐藏
             		           $('#start_money_id').hide(); // 随机金额开始金额输入框隐藏
             		           $('#Coupons_start_money').hide(); // 随机金额开始金额输入框隐藏
             		           $('#money_id').show(); // 固定金额输入框显示
             		        }else{ //取消勾选   即随机金额
             		        	$('#end_money_id').show();
             		        	$('#start_money_id').show();
             		            $('#Coupons_start_money').show();
             		            $('#money_id').hide();
             		        }

                			if($('#Coupons_if_wechat').prop("checked")){  //勾选微信卡包
                	       	    $('#end_money_id').hide(); // 随机金额结束金额输入框隐藏
                	            $('#start_money_id').hide(); // 随机金额开始金额输入框隐藏
                	            $('#Coupons_start_money').hide(); // 随机金额开始金额输入框隐藏
                	            $('#money_id').show(); // 固定金额输入框显示
                	            $('#Coupons_money_type').attr("checked",true); //勾选固定金额
                	            $('#Coupons_money_type').attr("disabled","disabled"); //固定（随机）金额类型复选框不可勾选
                	        }else{
                	        	//$('#end_money_id').show();
                	        	//$('#start_money_id').show();
//                 	            $('#Coupons_start_money').show();
//                 	            $('#money_id').show();
//                 	            $('#Coupons_money_type').attr("checked",true);
                	        	if($('#Coupons_money_type').prop("checked")){ //勾选固定金额
                   		           $('#end_money_id').hide(); // 随机金额结束金额输入框隐藏
                   		           $('#start_money_id').hide(); // 随机金额开始金额输入框隐藏
                   		           $('#Coupons_start_money').hide(); // 随机金额开始金额输入框隐藏
                   		           $('#money_id').show(); // 固定金额输入框显示
                   		        }else{ //取消勾选   即随机金额
                   		        	$('#end_money_id').show();
                   		        	$('#start_money_id').show();
                   		            $('#Coupons_start_money').show();
                   		            $('#money_id').hide();
                   		        }
                	            $('#Coupons_money_type').attr("disabled",false);//固定（随机）金额类型复选框可勾选
                	        }
                		}
                		if($(this).val() == <?php echo COUPON_TYPE_DISCOUNT; ?>){
                			$(".cDiscount").show();
                			$(".cExchange").hide();
                			$(".cMoney").hide();
                        }
                        if($(this).val() == <?php echo COUPON_TYPE_EXCHANGE; ?>){
                        	$(".cExchange").show();
                			$(".cDiscount").hide();
                			$(".cMoney").hide();
                			$(".lowxf").hide();//最低消费隐藏
                			$('.useWithDiscount').hide(); //可与会员折扣同时使用隐藏
                        }
                	}
                });
          }

    	 //设置只可以把对应的时间类型   的输入框可以编辑
         if(<?php echo $model['time_type'] ?> == <?php echo VALID_TIME_TYPE_FIXED ?>){ //如果是固定时间
        	 $('#Coupons_start_days').attr("disabled","disabled");
  			 $('#Coupons_effective_days').attr("disabled","disabled");
  			 $('#Time').attr("disabled",false);
         }else{
        	 $('#Time').attr("disabled","disabled");
  			 $('#Coupons_start_days').attr("disabled",false);
  			 $('#Coupons_effective_days').attr("disabled",false);
         }
     }
      
      
	//选择下拉菜单的颜色并关闭下拉菜单
	$(".selectMode li").click(function(e) {
		var className = $(this).attr("class");
		$(this).parent().prev().children("div").removeClass().addClass(className);
		$(this).parent().hide();
		$('#Couponscolor').val(document.getElementById('color').className);
		$('#left_bgColor').attr('class','hd '+$('#Couponscolor').val()); //同步左侧券颜色
	});
	
	//点击显示/隐藏下来菜单
	$(".selectMode .show").click(function(e) {
		var obj =  $(this).next();
		if(obj.is(":visible")){
			obj.hide();
		}else{
			obj.show();
		}                            
	});
	
	//couponRadio[]中括号有内容则改成$("input[name='couponRadio\\[name\\]']")
	$("input[name='Coupons\\[type\\]']").click(function(e) {
        if($(this).val() == "2"){        //当现金券选中后执行
			$(".cMoney").show();
			$(".cDiscount").hide();
			$(".cExchange").hide();
		}else if($(this).val() == "3"){  //当折扣券选中后执行
			$(".cDiscount").show();
			$(".cExchange").hide();
			$(".cMoney").hide();
		}else if($(this).val() == "4"){  //当兑换券选中后执行
			$(".cExchange").show();
			$(".cDiscount").hide();
			$(".cMoney").hide();
		}
    });

	//有效时间单选框选中事件
	$("input[name='Coupons\\[time_type\\]']").click(function(){
		if($(this).val() == <?php echo VALID_TIME_TYPE_FIXED; ?>){ //固定时间
			$('#Coupons_start_days').attr("disabled","disabled");
			$('#Coupons_effective_days').attr("disabled","disabled");
			$('#Time').attr("disabled",false);
		}else{ //相对时间
			$('#Time').attr("disabled","disabled");
			$('#Coupons_start_days').attr("disabled",false);
			$('#Coupons_effective_days').attr("disabled",false);
		}
	});


	//微信卡包复选框勾选
    $('#Coupons_if_wechat').click(function(){
        if($('#Coupons_if_wechat').prop("checked")){  //勾选微信卡包
       	    $('#end_money_id').hide(); // 随机金额结束金额输入框隐藏
            $('#start_money_id').hide(); // 随机金额开始金额输入框隐藏
            $('#Coupons_start_money').hide(); // 随机金额开始金额输入框隐藏
            $('#money_id').show(); // 固定金额输入框显示
            $('#Coupons_money_type').prop("checked",true); //勾选固定金额
            $('#Coupons_money_type').attr("disabled","disabled"); //固定（随机）金额类型复选框不可勾选
            $('#Coupons_if_give').attr("disabled",false); ////用户领券后可以转赠给其他好友  不可勾选
            $("#if_give").show(); //用户领券后可以转赠给其他好友  显示
            window.parent.callParAutoResize("main",$("body").height());
        }else{
        	//$('#end_money_id').show();
        	//$('#start_money_id').show();
            $('#Coupons_start_money').show();
            $('#money_id').show();
            $('#Coupons_money_type').prop("checked",true);
            $('#Coupons_money_type').attr("disabled",false);//固定（随机）金额类型复选框可勾选
            $('#Coupons_if_give').attr("disabled","disabled"); //用户领券后可以转赠给其他好友  不可勾选
            $('#Coupons_if_give').attr("checked",false); //不勾选固定金额
            $("#if_give").hide(); //用户领券后可以转赠给其他好友 隐藏
        }
    });


    //固定（随机）金额类型复选框勾选
    $('#Coupons_money_type').click(function(){
        if($('#Coupons_money_type').prop("checked")){ //勾选固定金额
           $('#end_money_id').hide(); // 随机金额结束金额输入框隐藏
           $('#start_money_id').hide(); // 随机金额开始金额输入框隐藏
           $('#Coupons_start_money').hide(); // 随机金额开始金额输入框隐藏
           $('#money_id').show(); // 固定金额输入框显示
        }else{ //取消勾选   即随机金额
        	$('#end_money_id').show();
        	$('#start_money_id').show();
            $('#Coupons_start_money').show();
            $('#money_id').hide();
        }
    });
	
	
	//你们用插件弹出框的话就不用管这js
	$(document).ready(function (){
		var _w = $(window).width() //浏览器窗口宽度
		var _w = $(window).height() //浏览器窗口高度
		var _offsetW = $('#pop').width(); //获取弹出框的宽度
		var _offsetH = $('#pop').height(); //获取弹出框的高度
		
		var _left = ($(window).width()-$('#pop').width())/2; 
		var _top = ($(window).height()-$('#pop').height())/2; 
				
		$('#pop').css({'left' : _left, 'top' : _top});
		
	})	
	
	function addStore()
	{
		//var a = '<?php //echo json_encode($model['store_limit_name']); ?>'; //已经选中门店json
		var data = [];
		var row = {};
		$('.storeTag').find('li').each(function(){
			row = {'id':$(this).attr('id'),'name':$(this).html()};
			data.push(row);
		});
		//console.log(a);
		art.dialog.open(
	        	'<?php echo Yii::app()->createUrl('mCenter/cardCoupons/addStoreDialog');?>'+'?arr_store='+JSON.stringify(data),
	        	 {
	              	 title: '',
	              	 lock: true,
	              	 drag:true, 
	                 width: '655px',
	              	// height: '413px',
	              	 id: 'dialog_notice',
	              	 background: '#fff'
	             }
	    );
	}

	function addStoreInfo(store_arr)
	{
		if(store_arr.length == 0){ //如果什么都没选     则默认是全部门店
			$('#left_store_list').text('全部门店'); 
		}else{
		    $('#left_store_list').empty();
		}
		$('.storeTag').empty(); //删除所有节点
		var str_id = ''; //定义所有选中门店id的字符串形式
		for(var i = 0;i<store_arr.length;i++){
			str_id = str_id + ',' + store_arr[i];
			
			$.ajax({
				url : '<?php echo Yii::app()->createUrl('mCenter/cardCoupons/loadStoreOnPage'); ?>',
				data : {store_id : store_arr[i]},
				type : 'post',
				success : function(data){
					var data = jQuery.parseJSON(data);
					$('.storeTag').append('<li id="'+data.id+'">'+data.name+'</li>');
					$('#left_store_list').append(data.name+'&nbsp;&nbsp;'); //左侧门店
					window.parent.callParAutoResize("main",$("body").height());

					if(<?php echo $count_store; ?> == store_arr.length){ //如果选择的门店是所有    则显示‘全部门店’
						$('#left_store_list').empty(); 
						$('#left_store_list').text('');
						$('#left_store_list').text('全部门店');
					}
				}
			});
		}

		$("#str_id").val(str_id); //给隐藏域赋值
		art.dialog.get("dialog_notice").close(); //关闭弹出框
	}

	
	if($('#Coupons_if_wechat').prop("checked")){
		
		var time = '<?php echo date('Y/m/d',strtotime($model['end_time'])); ?>';
		var start_time = '<?php echo date('Y/m/d',strtotime($model['start_time'])); ?>';
		d = new Date(time);
	    d = +d + 1000*60*60*24;
	    d = new Date(d);
	    var startDate = d.getFullYear()+"/"+(d.getMonth()+1)+"/"+d.getDate(); //获取结束时间的后一天
		
		  $('input[name="Time"]').daterangepicker({
				    timePicker: false,
		            format: 'YYYY/MM/DD',
		            startDate : startDate, //初始开始时间
		            minDate : startDate,   // 可选最早时间
		            dateLimit: true,
		            maxDate: true,
		            opens : 'left',
		            singleDatePicker : true
		  });		  

		  //选中日期的触发事件
		  $('input[name="Time"]').on('apply.daterangepicker',function(ev,picker){
			  $('input[name="Time"]').val(start_time + '-' + picker.startDate.format('YYYY/MM/DD'));
			  var val = $('input[name="Tim"]').val();			  
	      });
		   
	}else{
		var endtime = '<?php echo date('Y/m/d',strtotime($model['end_time'])); ?>';
		$('input[name="Time"]').daterangepicker({
	 	    timePicker: false,
	 	    format: 'YYYY/MM/DD',
	 	    dateLimit: true,
	 	    maxDate: true,
	 	   // minDate:now.toLocaleDateString(), //默认从今天开始
	 	    opens : 'left'
	 	});

		$('input[name="Time"]').on('apply.daterangepicker',function(ev, picker) {
			if(picker.endDate.format('YYYY/MM/DD') < endtime){
				alert("结束日期只能延长");
				$('#Time').val('<?php echo date('Y/m/d',strtotime($model['start_time'])).'-'.date('Y/m/d',strtotime($model['end_time'])); ?>');
			}
		});
	}

	//关闭弹出框
	function closeWinDialog()
	{
		art.dialog.get("dialog_notice").close();
		location.reload();
	}

	//左侧手机界面同步  
	//keyup是内容改变触发      change是失去焦点触发

	//右侧标题内容变化时
	$('#Coupons_title').keyup(function(){
		$('.name1').html($('#Coupons_title').val());
//		if($('input[name="Coupons\\[type\\]"]:checked').val() != <?php //echo COUPON_TYPE_EXCHANGE ?>)//{ //如果不是兑换券    优惠详情是标题与副标题的结合
//			$('#left_discount_illustrate').html($('#Coupons_title').val() +','+$('#Coupons_vice_title').val());
//		}
		
	});
	$('#Coupons_title').change(function(){
		$('.name1').html($('#Coupons_title').val());
//		if($('input[name="Coupons\\[type\\]"]:checked').val() != <?php //echo COUPON_TYPE_EXCHANGE ?>)//{ //如果不是兑换券    优惠详情是标题与副标题的结合
//			$('#left_discount_illustrate').html($('#Coupons_title').val() +','+$('#Coupons_vice_title').val());
//		}
	});

	//右侧副标题内容变化时
	$('#Coupons_vice_title').keyup(function(){
		$('#left_vice_title').html($('#Coupons_vice_title').val());
//		if($('input[name="Coupons\\[type\\]"]:checked').val() != <?php //echo COUPON_TYPE_EXCHANGE ?>)//{ //如果不是兑换券    优惠详情是标题与副标题的结合
//			$('#left_discount_illustrate').html($('#Coupons_title').val() +','+$('#Coupons_vice_title').val());
//		}
	});
	$('#Coupons_vice_title').change(function(){
		$('#left_vice_title').html($('#Coupons_vice_title').val());
//		if($('input[name="Coupons\\[type\\]"]:checked').val() != <?php //echo COUPON_TYPE_EXCHANGE ?>)//{ //如果不是兑换券    优惠详情是标题与副标题的结合
//			$('#left_discount_illustrate').html($('#Coupons_title').val() +','+$('#Coupons_vice_title').val());
//		}
	});

	//右边提示操作内容变化时
	$('#Coupons_prompt').keyup(function(){
		$('#left_prompt').html($('#Coupons_prompt').val());
	});
	$('#Coupons_prompt').change(function(){
		$('#left_prompt').html($('#Coupons_prompt').val());
	});

	//右边领取限制内容变化时
	$('#Coupons_receive_num').keyup(function(){
		$('#left_receive_num').html('每人限领'+$('#Coupons_receive_num').val()+'张');
    });
	$('#Coupons_receive_num').change(function(){
		$('#left_receive_num').html('每人限领'+$('#Coupons_receive_num').val()+'张');
    });

    //右边可与会员折扣同时使用勾选
    $('#Coupons_if_with_userdiscount').click(function(){
     if($('input[name="Coupons\\[type\\]"]:checked').val() != <?php echo COUPON_TYPE_EXCHANGE ?>){ //如果不是兑换券
        if($('#Coupons_if_with_userdiscount').prop('checked')){
            $('#left_if_with_discount').html('该券可与会员折扣同时使用');
        }else{
        	$('#left_if_with_discount').html('该券不可与会员折扣同时使用');
        }
     }
    });

	//右边使用须知内容变化时
	$('#Coupons_use_illustrate').keyup(function(){
		$('#left_illustrate').html($('#Coupons_use_illustrate').val().replace(/\n/g,'<br>'));
	});
	$('#Coupons_use_illustrate').change(function(){
		$('#left_illustrate').html($('#Coupons_use_illustrate').val().replace(/\n/g,'<br>'));
	});

	//客服电话内容变化时
	$('#Coupons_tel').keyup(function(){
		$('#left_tel').html($('#Coupons_tel').val());
	});
	$('#Coupons_tel').change(function(){
		$('#left_tel').html($('#Coupons_tel').val());
	});

	//优惠说明内容变化时
	$('#Coupons_discount_illustrate').keyup(function(){
		$('#left_discount_illustrate').html($('#Coupons_discount_illustrate').val());
	});
	$('#Coupons_discount_illustrate').change(function(){
		$('#left_discount_illustrate').html($('#Coupons_discount_illustrate').val());
	});

	
	//日期同步
	$('input[name="Time"]').on('hide.daterangepicker',function(ev,picker){
		if($('input[name="Coupons\\[time_type\\]"]:checked').val() == <?php echo VALID_TIME_TYPE_FIXED; ?>){  //如果选择的是固定日期
		  $('#left_time1').html($('#Time').val());
		  $('#left_time2').html($('#Time').val());
		}else{ //如果选择的是相对日期
			if($('#Coupons_start_days').val() != 0){
			     $('#left_time1').html('领取后'+$('#Coupons_start_days').val()+'天生效，有效'+$('#Coupons_effective_days').val()+'天');
			     $('#left_time2').html('领取后'+$('#Coupons_start_days').val()+'天生效，有效'+$('#Coupons_effective_days').val()+'天');
			}else{
				 $('#left_time1').html('领取后当天生效，有效'+$('#Coupons_effective_days').val()+'天');
				 $('#left_time2').html('领取后当天生效，有效'+$('#Coupons_effective_days').val()+'天');
			}
		}
	});

	//折扣券优惠说明
	$('#Coupons_discount').keyup(function(){
		if($('#Coupons_mini_consumption').val()==''){
		    $('#left_discount_illustrate').html($('#Coupons_discount').val()+'折折扣券一张,任意金额可用');
		}else{
			$('#left_discount_illustrate').html($('#Coupons_discount').val()+'折折扣券一张,满'+$('#Coupons_mini_consumption').val()+'元可用');
		}
	});
	$('#Coupons_discount').change(function(){
		if($('#Coupons_mini_consumption').val()==''){
		    $('#left_discount_illustrate').html($('#Coupons_discount').val()+'折折扣券一张,任意金额可用');
		}else{
			$('#left_discount_illustrate').html($('#Coupons_discount').val()+'折折扣券一张,满'+$('#Coupons_mini_consumption').val()+'元可用');
		}
	});


	$('#Coupons_mini_consumption').change(function(){
		if($('#Coupons_type_1').is(":checked")){
		if($('#Coupons_mini_consumption').val()!=''){
			if($('#Coupons_discount').val()!=''){
		        $('#left_discount_illustrate').html($('#Coupons_discount').val()+'折折扣券一张,'+'满'+$('#Coupons_mini_consumption').val()+'元可用');
			}else{
				$('#left_discount_illustrate').html($('#Coupons_discount').val()+'折折扣券一张,'+'满'+$('#Coupons_mini_consumption').val()+'元可用');
			}
		}else{
			if($('#Coupons_discount').val()!=''){
			    $('#left_discount_illustrate').html($('#Coupons_discount').val()+'折折扣券一张,'+'任意金额可用');
			}else{
				$('#left_discount_illustrate').html($('#Coupons_discount').val()+'折折扣券一张,'+'任意金额可用');
			}
		}
		}
	});
	$('#Coupons_mini_consumption').keyup(function(){
		if($('#Coupons_type_1').is(":checked")){
		if($('#Coupons_mini_consumption').val()!=''){
			if($('#Coupons_discount').val()!=''){
		        $('#left_discount_illustrate').html($('#Coupons_discount').val()+'折折扣券一张,'+'满'+$('#Coupons_mini_consumption').val()+'元可用');
			}else{
				$('#left_discount_illustrate').html($('#Coupons_discount').val()+'折折扣券一张,'+'满'+$('#Coupons_mini_consumption').val()+'元可用');
			}
		}else{
			if($('#Coupons_discount').val()!=''){
			    $('#left_discount_illustrate').html($('#Coupons_discount').val()+'折折扣券一张,'+'任意金额可用');
			}else{
				$('#left_discount_illustrate').html($('#Coupons_discount').val()+'折折扣券一张,'+'任意金额可用');
			}
		}
		}
	});

	//代金券优惠说明
    $('#Coupons_start_money').keyup(function(){ //随机金额开始金额触发
       // if($('#Coupons_end_money').val()!=''){
            if($('#Coupons_mini_consumption').val()!=''){
        	    $('#left_discount_illustrate').html($('#Coupons_start_money').val()+'到'+$('#Coupons_end_money').val()+'元随机券一张,'+'满'+$('#Coupons_mini_consumption').val()+'元可用');
            }else{
            	$('#left_discount_illustrate').html($('#Coupons_start_money').val()+'到'+$('#Coupons_end_money').val()+'元随机券一张,'+'任意金额可用');
            }
        //}
    });
    $('#Coupons_start_money').change(function(){ //随机金额开始金额触发
        // if($('#Coupons_end_money').val()!=''){
             if($('#Coupons_mini_consumption').val()!=''){
         	    $('#left_discount_illustrate').html($('#Coupons_start_money').val()+'到'+$('#Coupons_end_money').val()+'元随机券一张,'+'满'+$('#Coupons_mini_consumption').val()+'元可用');
             }else{
             	$('#left_discount_illustrate').html($('#Coupons_start_money').val()+'到'+$('#Coupons_end_money').val()+'元随机券一张,'+'任意金额可用');
             }
         //}
     });

    $('#Coupons_end_money').keyup(function(){ //随机金额结束金额触发
        // if($('#Coupons_end_money').val()!=''){
             if($('#Coupons_mini_consumption').val()!=''){
         	    $('#left_discount_illustrate').html($('#Coupons_start_money').val()+'到'+$('#Coupons_end_money').val()+'元随机券一张,'+'满'+$('#Coupons_mini_consumption').val()+'元可用');
             }else{
             	$('#left_discount_illustrate').html($('#Coupons_start_money').val()+'到'+$('#Coupons_end_money').val()+'元随机券一张,'+'任意金额可用');
             }
         //}
     });
     $('#Coupons_end_money').change(function(){ //随机金额开始金额触发
         // if($('#Coupons_end_money').val()!=''){
              if($('#Coupons_mini_consumption').val()!=''){
          	    $('#left_discount_illustrate').html($('#Coupons_start_money').val()+'到'+$('#Coupons_end_money').val()+'元随机券一张,'+'满'+$('#Coupons_mini_consumption').val()+'元可用');
              }else{
              	$('#left_discount_illustrate').html($('#Coupons_start_money').val()+'到'+$('#Coupons_end_money').val()+'元随机券一张,'+'任意金额可用');
              }
          //}
      });

     $('#Coupons_mini_consumption').change(function(){
    	 if(!$('#Coupons_money_type').prop('checked') && $('#Coupons_type_0').is(":checked")){
    	 if($('#Coupons_mini_consumption').val()!=''){
        	    $('#left_discount_illustrate').html($('#Coupons_start_money').val()+'到'+$('#Coupons_end_money').val()+'元随机券一张,'+'满'+$('#Coupons_mini_consumption').val()+'元可用');
         }else{
            	$('#left_discount_illustrate').html($('#Coupons_start_money').val()+'到'+$('#Coupons_end_money').val()+'元随机券一张,'+'任意金额可用');
         }	
    	 }
  	});
     $('#Coupons_mini_consumption').keyup(function(){
    	 if(!$('#Coupons_money_type').prop('checked') && $('#Coupons_type_0').is(':checked')){
    	 if($('#Coupons_mini_consumption').val()!=''){
        	    $('#left_discount_illustrate').html($('#Coupons_start_money').val()+'到'+$('#Coupons_end_money').val()+'元随机券一张,'+'满'+$('#Coupons_mini_consumption').val()+'元可用');
         }else{
            	$('#left_discount_illustrate').html($('#Coupons_start_money').val()+'到'+$('#Coupons_end_money').val()+'元随机券一张,'+'任意金额可用');
         }	
    	 }
  	});

     $('#Coupons_money').keyup(function(){ //固定金额触发
    	 if($('#Coupons_mini_consumption').val()!=''){
        	    $('#left_discount_illustrate').html($('#Coupons_money').val()+'元代金券一张,'+'满'+$('#Coupons_mini_consumption').val()+'元可用');
            }else{
            	$('#left_discount_illustrate').html($('#Coupons_money').val()+'元代金券一张,'+'任意金额可用');
            }
     });
     $('#Coupons_money').change(function(){ //固定金额触发
    	 if($('#Coupons_mini_consumption').val()!=''){
        	    $('#left_discount_illustrate').html($('#Coupons_money').val()+'元代金券一张,'+'满'+$('#Coupons_mini_consumption').val()+'元可用');
            }else{
            	$('#left_discount_illustrate').html($('#Coupons_money').val()+'元代金券一张,'+'任意金额可用');
            }
     });
     $('#Coupons_mini_consumption').change(function(){
         if($('#Coupons_money_type').prop('checked') && $('#Coupons_type_0').is(":checked")){
    	 if($('#Coupons_mini_consumption').val()!=''){
      	    $('#left_discount_illustrate').html($('#Coupons_money').val()+'元代金券一张,'+'满'+$('#Coupons_mini_consumption').val()+'元可用');
          }else{
          	$('#left_discount_illustrate').html($('#Coupons_money').val()+'元代金券一张,'+'任意金额可用');
          }	
         }
  	});
     $('#Coupons_mini_consumption').keyup(function(){
         if($('#Coupons_money_type').prop('checked') && $('#Coupons_type_0').is(":checked")){
    	 if($('#Coupons_mini_consumption').val()!=''){
      	    $('#left_discount_illustrate').html($('#Coupons_money').val()+'元代金券一张,'+'满'+$('#Coupons_mini_consumption').val()+'元可用');
          }else{
          	$('#left_discount_illustrate').html($('#Coupons_money').val()+'元代金券一张,'+'任意金额可用');
          }	
         }
  	});
</script>

</body>


