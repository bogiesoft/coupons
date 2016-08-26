<h4 class="g-title bb-none">优惠券管理</h4>
<nav class="nav m-cp-nav">
    <span class="nav-link active" onclick="window.location.href='<?php echo Yii::app()->createUrl('mCenter/cardCoupons/CouponList') ?>'">优惠券管理</span>
    <span class="nav-link" onclick="window.location.href='<?php echo Yii::app()->createUrl('mCenter/cardCoupons/verification') ?>'">优惠券核销</span>
    <span class="nav-link" onclick="window.location.href='<?php echo Yii::app()->createUrl('mCenter/cardCoupons/CouponCount') ?>'">优惠券统计</span>
</nav>
<div class="g-container">
    <div class="cp-wrapper">
    	<?php echo CHtml::beginForm('','get',array('id'=>'searchForm'))?>
        <div class="m-search">
            <div class="form-inline">
                <div class="form-group">
                    <select name="Coupon[type]" id="search_type"  class="form-control">
                    		<option value="0"
                    		<?php if (!isset($_GET['Coupon']['type'])) {
                                echo 'selected=selected';
                            } ?>>券类型
                            </option>
                            <?php foreach ($coupon_type as $key => $v) { ?>
                                <option value="<?php echo $key ?>"
                                    <?php if (isset($_GET['Coupon']['type']) && $_GET['Coupon']['type'] == $key) {
                                        echo 'selected=selected';
                                    } ?>><?php echo $v ?></option>
                            <?php } ?>
                    </select>
                </div>
                <div  class="form-group ml15">
                    <select name="Coupon[status]" id="search_status" class="form-control">

                        <option value="0" <?php if (!isset($_GET['Coupon']['status'])) {
                                echo 'selected=selected';
                            } ?>>券状态
                        </option>
                        <?php foreach ($status_arr as $key1 => $v1) { ?>
                        	<?php if ($key1 == 'wx'){?>
                        		<?php foreach ($v1 as $key2 => $v2){?>
                                <option value="<?php echo 'wx_'.$key2 ?>"
                                    <?php if (isset($_GET['Coupon']['status']) && $_GET['Coupon']['status'] == $key2) {
                                        echo 'selected=selected';
                                    } ?>><?php echo $v2 ?></option>
                            	<?php }?>
                          	<?php }else if ($key1 == 'tf'){?>
                          		<?php foreach ($v1 as $key3 => $v3){?>
                                <option value="<?php echo 'tf_'.$key3 ?>"
                                    <?php if (isset($_GET['Coupon']['status']) && $_GET['Coupon']['status'] == $key3) {
                                        echo 'selected=selected';
                                    } ?>><?php echo $v3 ?></option>
                            	<?php }?>
                          	<?php }?>
                        <?php } ?>

                    </select>
                </div>
                <div class="input-group ml15 form-inline">
                    <input type="text" name="search_title" value="<?php echo isset($_GET['search_title']) && !empty($_GET['search_title'])?$_GET['search_title']:'';?>" class="form-control fc-sm" placeholder="请输入优惠券名称">
                    <button type="submit" id="submit_search" class="input-group-addon btn-search"><i class="fa fa-search"></i></button>
                </div>
                <?php echo CHtml::endForm();?>
                <div class="form-group fr">
                    <a href="<?php echo Yii::app()->createUrl('mCenter/CardCoupons/CreateCoupon')?>" class="btn btn-primary">创建优惠券</a>
                </div>
            </div>
        </div>
        <table class="table g-table g-table-center mt20 g-table-fixed">
            <thead>
            <tr>
                <th width="7%">券类型</th>
                <th width="12%">优惠券名称</th>
                <th width="8%">券内容</th>
                <th width="20%">有效期</th>
                <th width="8%">领取限制</th>
                <th width="8%">投放状态</th>
                <th width="9%">领取人/次</th>
                <th width="8%">库存</th>
                <th width="20%">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($list)) {?>
                <?php foreach ($list as $k => $v){ ?>
            <tr>
                <td class="text-black"><?php echo isset($v['type'])?$GLOBALS['COUPON_TYPE'][$v['type']]:''; ?></td>
                <td class="text-black">
                <a href="<?php echo Yii::app()->createUrl('mCenter/cardCoupons/coupondetail',array('coupons_id'=>$v['id'])); ?>"><?php echo $v['title']; ?></a>
                <p class="text-gray"><?php echo '微信'.$GLOBALS['WX_CHECK'][$v['status']] ?></p>
                </td>
                <td class="cp-content js-cp-content">
                	<?php if($v['type'] == COUPON_TYPE_CASH){ ?>  <!-- 如果是代金券   券内容是券面额-->
                        <?php echo $v['money']; ?>
                  		<?php }elseif ($v['type'] == COUPON_TYPE_DISCOUNT){?> <!-- 如果是折扣券   券内容是券折扣-->
                        <?php echo 10*$v['discount'].'折'; ?>
                  		<?php }elseif ($v['type'] == COUPON_TYPE_EXCHANGE){?> <!-- 如果是兑换券   券内容是优惠说明-->
                        <?php echo $v['discount_illustrate']; ?>
                 	<?php }?>
                </td>

                <td>
                	 <?php if($v['time_type'] == VALID_TIME_TYPE_FIXED){ ?> <!-- 固定时间 -->
                	      <span id="text<?php echo $v['id']; ?>"><?php echo date('Y.m.d',strtotime($v['start_time'])).'-'.date('Y.m.d',strtotime($v['end_time'])); ?></span>
                	      <?php if($v['end_time'] < date('Y-m-d H:i:s')){ ?>
                	           <?php echo '<br>'.'(已过期)'; ?>
                	      <?php }?>
                	  <?php }else{?> <!-- 相对时间 -->
                	      <?php if($v['start_days'] != 0) {?>
                	      <?php echo '领取后'.$v['start_days'].'天生效有效'.$v['effective_days'].'天'; ?>
                	      <?php }else{?>
                	      <?php echo '领取后当天生效有效'.$v['effective_days'].'天'; ?>
                	      <?php }?>
                	  <?php }?>
                </td>
                <td><?php echo isset($v['receive_num'])?$v['receive_num']:''; ?></td>
                <td id="<?php echo 'td'.$v['code'];?>"><?php echo $GLOBALS['COUPON_RELEASE_STATUS'][$v['release_status']]?></td>
                <td><?php echo $v['receive_per']; ?>/<?php echo $v['get_num']; ?></td>
                <td><?php echo $v['num']-$v['get_num']; ?>
                <?php if($v['if_invalid'] == IF_INVALID_NO){ ?> <!-- 未失效的才有修改 -->
							<div class="inlineBlock couponsModify">
								<i class="fa fa-pencil edit-stock-btn"></i>
							</div>
                <?php }?>
                <?php echo CHtml::beginForm(Yii::app()->createUrl('mCenter/cardCoupons/editNum',array('coupons_id'=>$v['id'])),'post',array('id'=>'editNumForm'.$v['id'])); ?>
					<!-- 修改库存弹出框 -->
					<div class="m-pop stock-pop popNum" style="display: none;">
					    <div class="m-pop-title">
					        <h4>修改库存</h4>
					        <i class="m-pop-close fr fa fa-times"></i>
					    </div>
					    <div class="m-pop-con">
					        <div class="form-group mt20">
					            <div class="radio-inline">
					                <label>

					                    <input type="radio" name="stock_type<?php echo $v['id'];?>" id="increase" value="1" checked>
					                    增加
					                </label>
					            </div>
					            <div class="radio-inline">
					                <label>
					                    <input type="radio" name="stock_type<?php echo $v['id'];?>" id="decrease" value="2">
					                    减少
					                </label>
					            </div>

					        </div>
					        <div class="form-group form-inline">
					            <?php echo CHtml::textField('edit_num'.$v['id'],isset($_POST['edit_num'])?$_POST['edit_num']:'',array('class'=>'form-control fc-m-sm')); ?>
					            <label class="pl10">张</label>
					            <div class="text-red" id="edit_num_error<?php echo $v['id']; ?>"></div>
					            <p class="text-gray mt6 remark">库存不能少于1，当前库存<?php echo $v['num'] - $v['get_num']?>张</p>
					        </div>
					        <div class="form-inline m-form-group text-center mt30">
					        	<input type="button" value="确定" class="btn btn-primary m-btn" onclick="editNumSub(<?php echo $v['id']; ?>,<?php echo $v['num'] - $v['get_num']; ?>)">
								<input type="button" value="取消" class="btn btn-default m-btn btn_com_gray" >
					        </div>
					    </div>
					</div>


                <?php echo CHtml::endForm(); ?>
                </td>
                <td class="m-operate">
                <?php if($v['if_invalid'] == IF_INVALID_NO) {?>
                   <?php if($v['release_status'] == COUPON_RELEASE_STATUS_NOTRELEASE){ ?> <!--  未投放的才有删除 -->
                   <?php if($v['time_type'] == VALID_TIME_TYPE_FIXED && $v['end_time'] < date('Y-m-d H:i:s')){?>


                   		<a href="<?php echo Yii::app()->createUrl('mCenter/cardCoupons/couponedit',array('coupons_id'=>$v['id'])); ?>">编辑</a>
                   		<a href="<?php echo Yii::app()->createUrl('mCenter/cardCoupons/invalid',array('coupons_id'=>$v['id'])); ?>" onclick="return confirm('确定要使券失效？')">使失效</a>
                   		<a href="<?php echo Yii::app()->createUrl('mCenter/cardCoupons/delcoupons',array('coupons_id'=>$v['id'])); ?>" onclick="return confirm('确定要删除卡券吗？')">删除</a>

                   		<?php }else {?>
                   		<?php if($v['num'] - $v['get_num'] <= 0){?>
                   		<a href="javascript:;" onclick="alert('库存不足');">投放</a>
                   		<?php }else{?>
                   		<a class="lunchbtn" href="javascript:;" origin="<?php echo $v['id']?>" WX_status="<?php echo $v['status']?>" coupon_code="<?php echo $v['code']?>">投放</a>
				   		<?php }?>
				   		<a href="<?php echo Yii::app()->createUrl('mCenter/cardCoupons/couponedit',array('coupons_id'=>$v['id'])); ?>">编辑</a>
                   		<a href="<?php echo Yii::app()->createUrl('mCenter/cardCoupons/invalid',array('coupons_id'=>$v['id'])); ?>" onclick="return confirm('确定要使券失效？')">使失效</a>
                   		<a href="<?php echo Yii::app()->createUrl('mCenter/cardCoupons/delcoupons',array('coupons_id'=>$v['id'])); ?>" onclick="return confirm('确定要删除卡券吗？')">删除</a>
                   		<?php }?>

                   <?php }else {?>
                   		<?php if($v['time_type'] == VALID_TIME_TYPE_FIXED && $v['end_time'] < date('Y-m-d H:i:s')){?>


                   		<a href="<?php echo Yii::app()->createUrl('mCenter/cardCoupons/couponedit',array('coupons_id'=>$v['id'])); ?>">编辑</a>
                   		<a href="<?php echo Yii::app()->createUrl('mCenter/cardCoupons/invalid',array('coupons_id'=>$v['id'])); ?>" onclick="return confirm('确定要使券失效？')">使失效</a>
                   		<a href="<?php echo Yii::app()->createUrl('mCenter/cardCoupons/coupondataone',array('code'=>$v['code'])); ?>">报表</a>
                   		<?php }else {?>
                   		<a class="lunchbtn" href="javascript:;" origin="<?php echo $v['id']?>" WX_status="<?php echo $v['status']?>" coupon_code="<?php echo $v['code']?>">投放</a>
				   		<a href="<?php echo Yii::app()->createUrl('mCenter/cardCoupons/couponedit',array('coupons_id'=>$v['id'])); ?>">编辑</a>
                   		<a href="<?php echo Yii::app()->createUrl('mCenter/cardCoupons/invalid',array('coupons_id'=>$v['id'])); ?>" onclick="return confirm('确定要使券失效？')">使失效</a>
                   		<a href="<?php echo Yii::app()->createUrl('mCenter/cardCoupons/coupondataone',array('code'=>$v['code'])); ?>">报表</a>
                   		<?php }?>
                   <?php }?>
                <?php }else {?>
                   <?php echo '<label style="color:red;">已失效</label>'; ?>
                   <a href="<?php echo Yii::app()->createUrl('mCenter/cardCoupons/delcoupons',array('coupons_id'=>$v['id'])); ?>" onclick="return confirm('确定要删除卡券吗？')">删除</a>
                <?php }?>
                </td>
            </tr>

               <?php }?>
           	<?php }?>

            </tbody>

        </table>
        <?php
                if (empty($list)){
                    echo '<a href="javascript:;" class="blank-tip">无数据</a>';
                }
            ?>
        <div class="g-pager">
            <div class="fr">
        <!-- 分页开始 -->
                <?php if($pages -> getItemCount() > 10){?>
                	<tr style="border:none">
                		<td style="border:none;text-align:right" colspan="8">
                    	<?php $this -> widget('CLinkPager',array(
                    			'pages'=>$pages,
                                'header'=>'共&nbsp;<span class="yellow">'.$pages -> getItemCount().'</span>&nbsp;条&nbsp;',
                                'prevPageLabel' => '上一页',
                                'nextPageLabel'=>'下一页',
                                'maxButtonCount'=>8
                    	));?>
                    	</td>
                   	</tr>
                <?php }?>
        <!-- 分页结束 -->
            </div>
        </div>
    </div>
</div>

<!-- 投放弹出框 -->
<?php echo CHtml::beginForm(Yii::app()->createUrl('mCenter/cardCoupons/lunchCoupons'),'post',array('id'=>'lunchform'));?>
<div class="m-pop launch-cp-pop"  id="lunchcouponpop" style="display: none;">

    <input type="hidden" name="couponid" value="">
    <div class="m-pop-title">
        <h4>投放优惠券</h4>
        <i id="close_lunch_icon" class="m-pop-close fr fa fa-times"></i>
    </div>
    <div class="m-cp-step">
        <span class="f-step active">1、选择券类型</span>
        <span class="s-step">2、投放优惠券</span>
    </div>
    <div class="m-pop-con">
        <div class="js-cp-pop-item" style="display: block;">
            <div class="m-select-type">
                <h4 class="title">选择优惠券类型</h4>
                <div class="m-select-item mb45">
                    <div class="radio">
                        <label>
                            <input type="radio" id="WX_coupon_radio" class="js-launch-cp-radio" name="cp-type" value="1" checked n="1">微信优惠券
                        </label>
                        <span id="WX_unpass" class="wechat-warning">
                            <i class="fa fa-exclamation-circle text-red"></i>
                        </span>
                        <p class="mt10 desc">仅限于微信内部投放，用户领取后自动添加至微信卡包</p>
                    </div>
                </div>
                <div class="m-select-item mb45">
                    <div class="radio">
                        <label>
                            <input type="radio" id="WQ_coupon_radio"class="js-launch-cp-radio" name="cp-type" value="2" n="2">玩券电子券
                        </label>
                    </div>
                    <p class="mt10 desc">可用于全网投放(包含支付宝服务窗)，用户领取后需手动添加至微信卡包</p>
                </div>
            </div>
            <div class="form-inline m-form-group text-center">
                <button type="button" class="btn btn-primary m-btn js-launch-next">下一步</button>
                <button type="button" class="btn btn-default m-btn" id="cancle_lunch">取消</button>
            </div>
        </div>
        <div class="js-cp-pop-item" style="display: none;">
            <div class="m-select-type">
                <h4 class="title">你可以通过以下方式投放卡劵</h4>
                <div class="m-select-item mb20" style="display:none;">
                    <div class="radio">
                        <label>
                            <input type="radio" name="launch-we-cp-type" value="WX1" checked>直接群发卡券
                        </label>
                        <p class="mt15 desc">通过公众号消息，直接投放</p>
                    </div>
                </div>
                <div class="m-select-item mb20" style="display:none;">
                    <div class="radio">
                        <label>
                            <input type="radio" name="launch-we-cp-type" value="WX2" >嵌入图文信息
                        </label>
                    </div>
                    <p class="mt15 desc">将卡劵嵌入图文消息，再由公众号投放</p>
                </div>
                <div class="m-select-item">
                    <div class="radio">
                        <label>
                            <input type="radio" name="launch-we-cp-type" value="WX3" onclick="wxdownradio()">下载二维码
                        </label>
                    </div>
                    <p class="mt15 desc">下载卡劵二维码，通过打印张贴或其他渠道发放该二维<br>
                        码365天内有效，过期将无法使用</p>
                </div>
            </div>
            <!--<p class="mt30 mb30 text-center">此外，也可以通过自定义菜单、<a href="">摇一摇周边、</a><a href="">微信连WiFi、</a><a href="">基础营销活动、</a><a href="">互动游戏</a>等服务进行投放</p>-->
            <div class="form-inline m-form-group text-center mt20">
                <button type="button" class="btn btn-default m-btn js-launch-prev">上一步</button>
                <input type="submit" id="submit1" class="btn btn-primary m-btn" value="投放">
                <a id="WX-qr" n="" style="display: none" href="" class="btn btn-primary m-btn" download="wxqrcode.png">投放</a>
            </div>
        </div>
        <div class="js-cp-pop-item" style="display: none;">
            <div class="m-select-type">
                <h4 class="title">你可以通过以下方式投放卡劵</h4>
                <div class="m-select-item form-inline mb30">
                    <div class="form-group">
                        <label>链接</label>
                        <input type="text" id="WQ_link" name="WQ_link" class="form-control fc-lg ml10" value="">
                        <a href="javascript:;" id="copy" class="pl10">复制</a>
                        <!--<a href="javascript:;" class="pl10">预览</a>-->
                    </div>
                </div>
                <div class="m-select-item mb20" style="display:none;">
                    <div class="radio">
                        <label>
                            <input type="radio" name="launch-cp-type" value="WQ1" checked>嵌入图文信息
                        </label>
                    </div>
                    <p class="mt15 desc">将卡劵嵌入图文消息，再由公众号投放</p>
                </div>
                <div class="m-select-item">
                    <div class="radio">
                        <label>
                            <input type="radio" id="download-qr2" name="launch-cp-type" value="WQ2" onclick="downradio()" >下载二维码
                        </label>
                    </div>
                    <p class="mt15 desc">下载卡劵二维码，通过打印张贴或其他渠道发放该二维<br>
                        码365天内有效，过期将无法使用</p>
                </div>
            </div>
            <!--<p class="mt30 mb30 text-center">此外，也可以通过自定义菜单、<a href="">摇一摇周边、</a><a href="">微信连WiFi、</a><a href="">基础营销活动、</a><a href="">互动游戏</a>等服务进行投放</p>-->
            <div class="form-inline m-form-group text-center">
                <button type="button" id="prev_step" class="btn btn-default m-btn js-launch-prev">上一步</button>
                <input type="submit" id="submit2" class="btn btn-primary m-btn" value="投放">
                <a id="WQ-qr" n="" style="display: none" href="" class="btn btn-primary m-btn" download="qrcode.png">投放</a>
            </div>
        </div>

    </div>

</div>
<?php echo CHtml::endForm();?>

<!-- 投放弹出框结束 -->

<script>
$(function(){
	var value1 = "<?php echo !empty($_POST['search_type'])?$_POST['search_type']:'0';?>";
	var value2 = "<?php echo !empty($_POST['search_status'])?$_POST['search_status']:'0';?>";
	$('#search_type').val(value1);
	$('#search_status').val(value2);



});
// 弹出位置方法
function popShow(el) {
    //显示位置计算
    var offset_y = $(window.parent.document).scrollTop() + 100;
    var offset_x = 100;
    var browserW = $(window).width();
    var browserH = $(window.parent.parent).height();
    var pop_obj = el;
    var pop_width = pop_obj.width();
    var pop_height = pop_obj.height();
    var x = (browserW - pop_width) / 2 + offset_x;
    var y = (browserH - pop_height) / 2 + offset_y;
    if (x < 410) {
        x = 410;
    }
    if (y < 300) {
        y = 300;
    }
    //设置显示位置
    pop_obj.css({'left': x, 'top': y});
    pop_obj.show();
}

function lunchpop(coupon_code,status){
	$("input[name=couponid]").val(coupon_code);

	$("#WQ-qr").attr("n",coupon_code);
	$("#WX-qr").attr("n",coupon_code);
	if(status == 1){
		$('#WX_coupon_radio').attr("disabled","disabled");
		$('#WX_coupon_radio').removeAttr("checked");
		$('#WQ_coupon_radio').attr("checked","checked");
		$('#WX_unpass').html('<i class="fa fa-exclamation-circle text-red"></i>微信优惠券审核中');
	}else if(status == 2){
		$('#WX_unpass').hide();
	}else if(status == 3){
		$('#WX_coupon_radio').attr("disabled","disabled");
		$('#WX_coupon_radio').removeAttr("checked");
		$('#WQ_coupon_radio').attr("checked","checked");
		$('#WX_unpass').html('<i class="fa fa-exclamation-circle text-red"></i>微信优惠券未通过');
	}
	popShow($('#lunchcouponpop'));
}
$("a.lunchbtn").click(function(){
	var lunchid = $(this).attr('origin');
	var status = $(this).attr('WX_status');
	var coupon_code = $(this).attr('coupon_code')
	$('#WX_coupon_radio').attr("checked","checked");
	$('#WX_unpass').show();
	$('#WX_coupon_radio').removeAttr("disabled");
	$('#prev_step').click();
	$("input[name='launch-we-cp-type']").removeAttr('checked');
	$("input[name='launch-cp-type']").removeAttr('checked');
	$('#submit1').css("display","inline-block");
	$('#WX-qr').css("display","none");
	$('#submit2').css("display","inline-block");
	$('#WQ-qr').css("display","none");
	var WQ_link_url = '<?php echo WAP_DOMAIN.'/coupon_' ?>'+coupon_code;
	$('#WQ_link').val(WQ_link_url);

	var posturl = '<?php echo Yii::app()->createUrl('mCenter/cardCoupons/checksku'); ?>'+'?coupons_code='+coupon_code;
	$.ajax({
		  url : posturl,
		  data : {coupons_code : coupon_code},
		  type:'post',
		  success:function(data){
			    if(data == 'ok'){
			    	lunchpop(coupon_code,status);

				}else if(data == 'no'){
					alert('库存/(微信库存)不足');
				}

	      }
	  });

});
$("#close_lunch_icon").click(function(){
	$('#lunchcouponpop').hide();
});
$("#cancle_lunch").click(function(){
	$('#lunchcouponpop').hide();
});
$('#copy').click(function(){
	$('#copy').zclip({
		path:"<?php echo GJ_STATIC_JS?>ZeroClipboard.swf",
		copy:function(){
			return $('#WQ_link').val();
		},
		afterCopy:function(){/* 复制成功后的操作 */
	            alert('成功复制到剪切板，Ctrl+V即可粘贴');
	    }
	});
});

//微信下载二维码
function wxdownradio(){
	var coupon_code = $("input[name=couponid]").val();
	if(coupon_code){
		var url = '';
		var posturl = '<?php echo Yii::app()->createUrl('mCenter/cardCoupons/WxQr'); ?>'+'?coupons_code='+coupon_code;
		$.ajax({
			  url : posturl,
			  data : {coupons_code : coupon_code},
			  type:'post',
			  success:function(data){
				  //alert(data);
				    if(data != 'fail'){
						url = '<?php echo Yii::app()->createUrl('mCenter/cardCoupons/createQr'); ?>'+'?getCouponUrl='+data;
						$('#WX-qr').attr("href",url);
						$('#WX-qr').css("display","inline-block");
						$('#submit1').css("display","none");
						$("#td"+coupon_code).html('已投放');
					}

		      }
		  });

	}
}
$('#WX-qr').click(function(){
	var coupon_code = $("input[name=couponid]").val();
	  $.ajax({
		  url : '<?php echo Yii::app()->createUrl('mCenter/cardCoupons/lunchCoupons'); ?>',
		  data : {coupons_code : coupon_code},
		  type:'post',
		  success:function(data){
			    $('#lunchcouponpop').hide();

	      }
	  });
});

//玩券下载二维码
function downradio(){
	var wq_link = $('#WQ_link').val();
	if(wq_link){

		var url = '<?php echo Yii::app()->createUrl('mCenter/cardCoupons/createQr')?>'+'?getCouponUrl='+ wq_link;
		$('#WQ-qr').attr("href",url);
		$('#WQ-qr').css("display","inline-block");
		$('#submit2').css("display","none");
	}
}
$('#WQ-qr').click(function(){
	var coupon_code = $("input[name=couponid]").val();
	  $.ajax({
		  url : '<?php echo Yii::app()->createUrl('mCenter/cardCoupons/lunchCoupons'); ?>',
		  data : {coupons_code : coupon_code},
		  type:'post',
		  success:function(data){
 			    $("#td"+coupon_code).html('已投放');
// 			    $('#extend'+coupons_id).show();
// 				$('#cancle'+coupons_id).hide();
//				location.reload();
			    $('#lunchcouponpop').hide();


	      }
	  });
});

//非下载二维码投放按钮事件
$('#submit1').click(function(){
	 if(!$('#WX-qr').is(":checked")){
		return false;
	 }
});

$('#submit2').click(function(){
	if(!$('#WQ-qr').is(":checked")){
		return false;
	 }
});

//优惠券数量修改
$(".couponsModify").click(function(e) {
	//获取相对于文档的偏移值
	var o = $(this).offset();
	var popNum = $(this).parents("td").find(".popNum");
	var o_left = o.left-100;
	var o_top = o.top;
	popNum.css({"top":o_top,"left":o_left}).show();
});

$(".btn_com_gray").click(function(e) {
    $(this).parents(".popNum").hide();
});

$(".m-pop-close").click(function(e) {
    $(this).parents(".popNum").hide();
});

function editNumSub(coupons_id,current_num)
{
	//由于form表单是动态添加的   所以每个input都必需加个coupons_id做标示
	var edit_num = $('#edit_num'+coupons_id).val();
	var checkNum = /^[0-9]*$/; //验证数字的正则表达式
	var flag = true;
	var type = $('input[name="stock_type'+coupons_id+'"]:checked').val();


	var least_num = current_num - edit_num;
	if(edit_num == ''){
		flag = false;
		$('#edit_num_error'+coupons_id).html('修改量不能为空');
	}else{
	  if(!checkNum.test(edit_num)){
		  flag = false;
	 	  $('#edit_num_error'+coupons_id).html('修改量要数字');
      }else{
        if(type==2 && least_num < 1){
 		  flag = false;
 		  $('#edit_num_error'+coupons_id).html('剩余量不能少于1');
 	    }else{
 		  $('#edit_num_error'+coupons_id).html('');
	    }
      }
	}

	if(flag){
		$('#editNumForm'+coupons_id).submit();
	}

}

$('#search_type').change(function() {
    $('#searchForm').submit();
});

$('#search_status').change(function() {
    $('#searchForm').submit();
});

</script>

