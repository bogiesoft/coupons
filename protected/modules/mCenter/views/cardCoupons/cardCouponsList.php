<script type="text/javascript" src="<?php echo SYT_STATIC_JS?>artDialog/jquery.artDialog.js?skin=simple"></script>
	<script type="text/javascript" src="<?php echo SYT_STATIC_JS?>artDialog/plugins/iframeTools.js"></script>
<body>
	<div class="kkfm_r_inner">
		<div class="top">
			<div class="name">优惠券管理</div>
		</div>

		<div class="contant couponsList">
			<div class="cz">
				<a href="<?php echo Yii::app()->createUrl('mCenter/cardCoupons/cardCouponsAdd'); ?>" class="btn_com_blue">添加优惠券</a>
				<a href="<?php echo Yii::app()->createUrl('mCenter/cardCoupons/test'); ?>" class="btn_com_blue">测试</a>
				<a href="<?php echo Yii::app()->createUrl('mCenter/cardCoupons/cardinfo',array('id'=>33)); ?>" class="btn_com_blue">获取卡券信息</a>
			</div>
    		<?php echo CHtml::beginForm('','get'); ?>
    		<div class="sh-search clearfix">
    	    	<?php echo CHtml::textField('key_word',isset($_GET['key_word'])?$_GET['key_word']:'',array('placeholder'=>'请输入优惠券名称','class'=>'txt')); ?>
            	<input type="submit" class="search" value="">
			</div>
        	<?php echo CHtml::endForm(); ?>
      	<div class="recharge">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr class="order-title">
						<td align="left">券类型</td>
						<td align="left">券名称</td>
						<td align="left">券内容</td>
						<td align="left">领取限制</td>
						<td align="left">有效时间</td>
						<td align="left">领取人/次</td>
						<td align="left">使用</td>
						<td class="lastTd">操作</td>
					</tr>
              <?php if(!empty($list)) {?>
                <?php foreach ($list as $k => $v){ ?>
              <tr>
						<td align="left"><?php echo isset($v['type'])?$GLOBALS['COUPON_TYPE'][$v['type']]:''; ?></td>
						<td align="left"><?php echo $v['title']; ?><div class="gray"><?php if($v['if_wechat'] == IF_WECHAT_YES){echo '同步微信卡包'.'('.$GLOBALS['WX_CHECK'][$v['status']].')';} ?></div></td>
						<td align="left">
							<div class="tdCouponCon">
                  		<?php if($v['type'] == COUPON_TYPE_CASH){ ?>  <!-- 如果是代金券   券内容是券面额-->
                     		<?php if($v['money_type'] == FACE_VALUE_TYPE_FIXED){ ?>
                        		<?php echo $v['money']; ?>
                     		<?php }else{?>
                        		<?php $money_arr = explode(',', $v['money_random']); ?>
                        		
                     		<?php }?>
                  		<?php }elseif ($v['type'] == COUPON_TYPE_DISCOUNT){?> <!-- 如果是折扣券   券内容是券折扣-->
                        <?php echo 10*$v['discount'].'折'; ?>
                  		<?php }elseif ($v['type'] == COUPON_TYPE_EXCHANGE){?> <!-- 如果是兑换券   券内容是优惠说明-->
                        <?php echo $v['discount_illustrate']; ?>
                 		<?php }?>
                  			</div>
                		</td>
						<td align="left">
							<div class="inlineBlock gray">限领<?php echo $v['receive_num']; ?>张<br>剩余：<?php echo $v['num']-$v['get_num']; ?></div>
                
                <?php if($v['if_invalid'] == IF_INVALID_NO){ ?> <!-- 未失效的才有修改 -->
							<div class="inlineBlock couponsModify">
								<a href="#" class="blue">修改</a>
							</div>
                <?php }?>
                <?php echo CHtml::beginForm(Yii::app()->createUrl('mCenter/cardCoupons/editNum',array('coupons_id'=>$v['id'])),'post',array('id'=>'editNumForm'.$v['id'])); ?>
                    <div class="popNum" style="display: none">
								<h2>修改剩余量</h2>
                    	<?php echo CHtml::textField('edit_num'.$v['id'],isset($_POST['edit_num'])?$_POST['edit_num']:'',array('class'=>'txt')); ?>
                    	<font color="red"
									id="edit_num_error<?php echo $v['id']; ?>"></font>
								<div class="remark">库存不能少于1</div>
								<div class="btnWrap">
									<input type="button" value="确定" class="btn_com_blue"
										onclick="editNumSub(<?php echo $v['id']; ?>)"> <input
										type="button" value="取消" class="btn_com_gray">
								</div>
							</div>
                <?php echo CHtml::endForm(); ?>
                </td>
						<td align="left">
							<div class="inlineBlock gray">
                	  <?php if($v['time_type'] == VALID_TIME_TYPE_FIXED){ ?> <!-- 固定时间 -->
                	      <span id="text<?php echo $v['id']; ?>"><?php echo date('Y/m/d',strtotime($v['start_time'])).'-'.date('Y/m/d',strtotime($v['end_time'])); ?></span>
                	      <?php if($v['end_time'] < date('Y-m-d H:i:s')){ ?>
                	           <?php echo '<br>'.'已过期'; ?>
                	      <?php }?>
                	  <?php }else{?> <!-- 相对时间 -->
                	      <?php if($v['start_days'] != 0) {?>
                	      <?php echo '领取后'.$v['start_days'].'天生效，有效'.$v['effective_days'].'天'; ?>
                	      <?php }else{?>
                	      <?php echo '领取后当天生效，有效'.$v['effective_days'].'天'; ?>
                	      <?php }?>
                	  <?php }?>
                	</div>
                <?php if($v['if_invalid'] == IF_INVALID_NO && $v['time_type'] == VALID_TIME_TYPE_FIXED){ ?> <!-- 未失效的时间是固定时间的才有延长 -->
							<div class="inlineBlock">
								<a href="javascript:;" class="blue" id="extend<?php echo $v['id']; ?>"
									onclick="extended(<?php echo $v['id']; ?>,'<?php echo date('Y/m/d',strtotime($v['start_time'])); ?>','<?php echo date('Y/m/d',strtotime($v['end_time'])); ?>')">延长</a>
							    <a href="javascript:;" class="blue" style="display:none" id="cancle<?php echo $v['id']; ?>" onclick="cancle(<?php echo $v['id']; ?>)">取消</a>
							</div>
                <?php }?>
                </td>
						<td align="left"><?php echo $v['receive_per']; ?>/<?php echo $v['get_receive_num']; ?></td>
						<td align="left"><?php echo $v['use_receive_num']; ?></td>
						<td align="left" class="lastTd">
                   <?php if($v['if_invalid'] == IF_INVALID_NO && $v['get_num']<=0){ ?> <!-- 未失效的 未被领取的才有编辑 -->
							<a
							href="<?php echo Yii::app()->createUrl('mCenter/cardCoupons/editCoupons',array('coupons_id'=>$v['id'])); ?>">编辑</a>-
                   <?php }?>
                   <?php if($v['if_invalid'] == IF_INVALID_NO){ ?>
                	<a
							href="<?php echo Yii::app()->createUrl('mCenter/cardCoupons/detail',array('coupons_id'=>$v['id'])); ?>">详情</a>
							<?php //if($v['if_wechat'] == IF_WECHAT_YES){ ?> <!-- 同步到微信的才有二维码 -->
							-<a href="#" class="obtainLink">链接</a>
							<?php //}?>
                	 -<a
							href="<?php echo Yii::app()->createUrl('mCenter/cardCoupons/invalid',array('coupons_id'=>$v['id'])); ?>"
							onclick="return confirm('确定要使券失效？')">使失效</a>
                	<?php }else{?> 
                	   <?php echo '<label style="color:red;">已失效</label>'; ?>
                	<?php  }?>
                    		<div class="showCode" style="display: none">
								<dl class="clearfix">
									<dt>使用券链接</dt>
									<dd>
										<input type="text" class="txt" id="url_content"
											value="<?php echo COUPON_RECEIVE_URL.'?coupon_id='.$v['id']; ?>">
											<a href="javascript:;" id="copy" class="btn">复制</a>
									</dd>
								</dl>
								<dl class="clearfix">
									<dt>优惠券二维码</dt>
									<dd>
										<img id="imgQr<?php echo $v['id']; ?>" src="<?php echo Yii::app()->createUrl('mCenter/cardCoupons/createQr',array('getCouponUrl'=>COUPON_RECEIVE_URL.'?coupon_id='.$v['id'],'id'=>$v['id'])); ?>">
										<a href="<?php echo Yii::app()->createUrl('mCenter/cardCoupons/createQr',array('getCouponUrl'=>COUPON_RECEIVE_URL.'?coupon_id='.$v['id'],'id'=>$v['id'])); ?>" class="blue" download="qrcode.png">下载二维码</a>
									</dd>
								</dl>
							</div>
						</td>
					</tr>
           <?php }?>
           <?php }?>
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
           </table>
           <br><br><br><br>
			</div>
			
		</div>
	</div>

	<script type="text/javascript">

//优惠券数量修改
$(".couponsModify").click(function(e) {
	//获取相对于文档的偏移值
	var o = $(this).offset();
	var popNum = $(this).parents("td").find(".popNum");
	var o_left = o.left-100;
	var o_top = o.top;
	popNum.css({"top":o_top,"left":o_left}).show();
});

$(".popNum .btn_com_gray").click(function(e) {
    $(this).parents(".popNum").hide();
});


//你们用插件弹出框的话就不用管这js
$(document).ready(function (){
	var _w = $(window).width(); //浏览器窗口宽度
	var _w = $(window).height(); //浏览器窗口高度
	var _offsetW = $('#extend').width(); //获取弹出框的宽度
	var _offsetH = $('#extend').height(); //获取弹出框的高度
	
	var _left = ($(window).width()-$('#extend').width())/2; 
	var _top = ($(window).height()-$('#extend').height())/2; 
			
	$('#extend').css({'left' : _left, 'top' : _top});
});	

//鼠标滑上去显示获取链接及二维码的内容
$(".obtainLink").hover(
	function(){
		//获取相对于文档的偏移值
		var o = $(this).offset();
		var showCode = $(this).parent().children(".showCode");
		var o_left = o.left-showCode.outerWidth();
		var o_top = o.top;
        //得到当前对象距离底部的距离
        var pos = $(document).height() - o_top;
        //当前对象隐藏的子对象的高度
        var height = showCode.outerHeight();
        //判断假如显示层高度大于该层里底部的距离，则加“oper_hover_up”，否则则加“oper_hover”
        if(height > pos){
            showCode.css({"bottom":0,"left":o_left}).show();
        }else{
            showCode.css({"top":o_top,"left":o_left}).show();
        }

        //复制地址到剪切板
		showCode.find('#copy').zclip({
			path:"<?php echo GJ_STATIC_JS?>ZeroClipboard.swf",
			copy:function(){
				return $(this).parent().find('.txt').val();
			},
			afterCopy:function(){/* 复制成功后的操作 */
		            alert('成功复制到剪切板，Ctrl+V即可粘贴');
		        }
		});
	},
	function(){
		var showCode=$(this).parent().children(".showCode");
		var time = setTimeout( function(){showCode.hide();showCode.find('#copy').unbind();},100);
		
		$(".showCode").hover(
			function(){
				clearTimeout(time);
				$(this).show();
			},
			function(){
				$(this).hide();
				showCode.find('#copy').unbind();
			}
		);
	}
)


		

function editNumSub(coupons_id)
{
	//由于form表单是动态添加的   所以每个input都必需加个coupons_id做标示
	var edit_num = $('#edit_num'+coupons_id).val();
	var checkNum = /^[0-9]*$/; //验证数字的正则表达式
	var flag = true;

	if(edit_num == ''){
		flag = false;
		$('#edit_num_error'+coupons_id).html('剩余量不能为空');
	}else{
	  if(!checkNum.test(edit_num)){
		  flag = false;
	 	  $('#edit_num_error'+coupons_id).html('剩余量要数字');
      }else{
        if(edit_num < 1){
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

//延长弹出框
function extended(coupons_id,start_time,end_time)
{
	d = new Date(end_time);
    d = +d + 1000*60*60*24;
    d = new Date(d);
    var startDate = d.getFullYear()+"/"+(d.getMonth()+1)+"/"+d.getDate(); //获取结束时间的后一天
   
	  $('#extend'+coupons_id).hide();
	  $('#cancle'+coupons_id).show();
	  //文本变成输入框
	  document.getElementById("text"+coupons_id).innerHTML="<input name='Tim' type='text' value='"+document.getElementById("text"+coupons_id).innerHTML+"' readonly='true'' />";
	  
	  $('input[name="Tim"]').daterangepicker({
			    timePicker: false,
	            format: 'YYYY/MM/DD',
	            startDate : startDate, //初始开始时间
	            minDate : startDate,   // 可选最早时间
	            dateLimit: true,
	            maxDate: true,
	            opens : 'left',
	            singleDatePicker : true
	  });
	  
	  //时间控件显示的时候     自适应高度
	  $('input[name="Tim"]').on('show.daterangepicker',function(ev,picker){
		  window.parent.callParAutoResize("main",$("body").height());
	  });
	  
	  //选中日期的触发事件
	  $('input[name="Tim"]').on('apply.daterangepicker',function(ev,picker){
		  $('input[name="Tim"]').val(start_time + '-' + picker.startDate.format('YYYY/MM/DD'));
		  var val = $('input[name="Tim"]').val();

		  if(end_time >= picker.startDate.format('YYYY/MM/DD')){
			  alert("所选日期要大于原结束时间！");
			  location.reload();
			  return;
		  }
		  
		  $.ajax({
			  url : '<?php echo Yii::app()->createUrl('mCenter/cardCoupons/extendTime'); ?>',
			  data : {date : val,coupons_id : coupons_id},
			  type:'post',
			  success:function(data){
				  if(data == 'success'){
				    alert('修改成功');
				    $("#text"+coupons_id).html(val);  //输入框变成文本
				    $('#extend'+coupons_id).show();
					$('#cancle'+coupons_id).hide();
					location.reload();
				  }else{
					alert('修改失败');
				  }
		      }
		  });
      });

// 	  $('input[name="Tim"]').on('hide.daterangepicker',function(ev,picker){
// 		  $('input[name="Tim"]').val(start_time + '-' + end_time);
// 		  var val = $('input[name="Tim"]').val();
// 		  $("#text"+coupons_id).html(val);  //输入框变成文本
// 		  $('#extend'+coupons_id).show();
// 		  $('#cancle'+coupons_id).hide();
// 	  });
}

//点击取消  刷新
function cancle(coupons_id)
{
	location.reload();
}
</script>


</body>


