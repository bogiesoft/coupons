
<title>添加会员等级</title>
<body>
	<script type="text/javascript">
	$(document).ready(main_obj.list_init);
</script>

	<div class="kkfm_r_inner">
		<div class="top">
			<div class="name">订单详情</div>
			<div class="cz">
				<a
					href="<?php echo $goUrl; ?>"
					class="btn_com_gray">返回</a>
			</div>
		</div>
		<div class="orderDetail">
			<div class="search clearfix">
				<div class="step">
					<ul
						class="<?php if($list['pay_status']==ORDER_STATUS_PAID && $list['order_status']==ORDER_STATUS_WAITFORDELIVER){echo 'step02';}
				           elseif ($list['order_status']==ORDER_STATUS_DELIVER){echo 'step03';}elseif ($list['order_status']==ORDER_STATUS_ACCEPT){echo 'step04';}else {echo 'step01';} ?>">
						<!--'第一步是step01；第二步为step02；第三步step03；第四步step04-->
						<li><span class="stepName color">买家下单</span> <span
							class="stepTime"><?php echo $list['create_time']; ?></span></li>
						<li><span
							class="stepName <?php echo $list['pay_status']==ORDER_STATUS_PAID?'color':''; ?>">买家付款</span>
							<span class="stepTime"><?php echo $list['pay_time']; ?></span></li>
						<li><span
							class="stepName <?php echo $list['order_status']==ORDER_STATUS_DELIVER || $list['order_status']==ORDER_STATUS_ACCEPT?'color':''; ?>">商家发货</span>
							<span class="stepTime"><?php echo $list['send_time']; ?></span></li>
						<li><span
							class="stepName <?php echo $list['order_status']==ORDER_STATUS_ACCEPT?'color':''; ?>">完成交易</span>
							<span class="stepTime"><?php echo $list['complete_time']; ?></span>
						</li>
					</ul>
				</div>
			</div>
			<div class="orderWarp clearfix">
				<div class="orderL">
					<div class="title">订单信息</div>
					<div class="filed">
						<span class="label">订单编号：</span> <span class="text"><?php echo $list['order_no']; ?></span>
					</div>
					<div class="filed">
						<span class="label">付款方式：</span> <span class="text"><?php echo isset($list['pay_channel'])?$GLOBALS['ORDER_PAY_CHANNEL'][$list['pay_channel']]:''; ?></span>
					</div>
					<div class="filed">
						<span class="label">买家：</span> <span class="text"><?php echo $list['user_name']; ?></span>
					</div>
					<div class="border"></div>
					<div class="filed">
						<span class="label">配送方式：</span> <span class="text">快递配送</span>
					</div>
					<div class="filed">
						<span class="label">收货信息：</span>
						<div style="display: none">
							<textarea cols="20" rows="10" id="address_content"><?php echo $list['address']; ?></textarea>
						</div>
						<span class="text"><?php echo $list['address']; ?> <a
							href="javascript:;" id="copy-botton">[复制]</a><a
							href="javascript:;" onclick="openWin()">[修改]</a></span>
					</div>
					<div class="filed">
						<span class="label">买家留言：</span> <span class="text"><?php echo $list['remark']; ?></span>
					</div>
				</div>
				<div class="orderR">
					<div class="title">
						<span class="status">订单状态：<?php if($list['pay_status']==ORDER_STATUS_UNPAID){echo '买家已下单,等待买家付款';}
                	elseif ($list['pay_status']==ORDER_STATUS_PAID && $list['order_status']==ORDER_STATUS_WAITFORDELIVER&&!$refund_status){echo '买家已付款，等待卖家发货';}
                	elseif ($list['order_status']==ORDER_STATUS_DELIVER){echo '卖家已发货';}elseif($list['pay_status']==ORDER_STATUS_PAID && $list['order_status']==ORDER_STATUS_ACCEPT){echo '完成交易';}else{echo "商品正在退款或已经取消不能发货";} ?></span>
                	<?php if($list['pay_status']==ORDER_STATUS_PAID && $list['order_status']==ORDER_STATUS_WAITFORDELIVER){ ?>
                  	<span><?php if(!$refund_status){?>买家已付款，请尽快发货，否则买家有权申请退款<?php }else{echo "商品正在退款或已经取消不能发货";}?></span>
                 	<?php }?>
                 </div>
					<div class="con">
                    <?php if($list['order_status'] == ORDER_STATUS_WAITFORDELIVER && $list['pay_status'] == ORDER_STATUS_PAID && $list['isSend'] == true&&!$refund_status){ ?>
                  	    <input type="submit" class="btn_com_blue"
							onclick="send()" value="发货">
                  	<?php }?>
                    <a href="javascript:;" onclick="openSellerRemark()">备注</a>
					</div>
					<div class="border"></div>
					<div class="filed">
						<span class="label">卖家备注：</span> <span class="text"><?php echo $list['seller_remark']; ?></span>
					</div>
				</div>
			</div>
			<!--end orderWarp-->
			<div class="orderContent order-list">
				<table width="100%" border="0" cellspacing="0" cellpadding="0"
					border="0">
					<tr class="orderHead">
						<td>商品</td>
						<td>单价</td>
						<td>数量</td>
						<td>小计</td>
						<td>订单状态</td>
						<td>运费</td>
					</tr>
              
              <?php if(!empty($list['order_sku'])){?>
                  <?php for($i=0;$i<count($list['order_sku']);$i++){ ?>
              <tr class="orderCon">
						<td><a href="#">
								<div class="list-img">
									<img src="<?php $img=explode(';',$list['order_sku'][$i]['product_img']); echo IMG_GJ_LIST.$img[0]; ?>" />
								</div>
								<div class="list-name"><?php echo $list['order_sku'][$i]['product_name']; ?></div>
								<div class="list-info"><?php echo $list['order_sku'][$i]['sku_name']; ?></div>
						</a></td>
						<td><?php echo $list['order_sku'][$i]['price']; ?></td>
						<td><?php echo $list['order_sku'][$i]['num']; ?></td>
						<td><?php echo ($list['order_sku'][$i]['price'])*($list['order_sku'][$i]['num']); ?></td>
						<td class="border">
						<?php echo $list['order_sku'][$i]['if_send'] == IF_SEND_NO?'待发货':'已发货'?>
						<?php //if($list['pay_status'] == ORDER_STATUS_UNPAID){
// 							echo '待付款';
// 						}else{
// 							if($list['order_sku'][$i]['status'] == ORDER_SKU_STATUS_NORMAL){
// 								echo '待发货';
// 							}else{
// 								echo $GLOBALS['ORDER_SKU_STATUS'][$list['order_sku'][$i]['status']];
// 							}
//                 		}?>
						</td>
                <?php if($i==0){ ?>
                  <td rowspan="<?php echo count($list['order_sku']); ?>"><?php echo $list['freight_money']; ?></td>
                <?php }?>
              </tr>
               <?php }?>
             <?php }?>
             
              <tr class="orderHead">
						<td colspan="6" align="right"><span>应收总价：<em><?php echo number_format($list['real_pay']+$list['freight_money'],2,'.',''); ?></em></span></td>
					</tr>
					<tr>
						<td class="bottom" colspan="6"></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<!--修改地址弹出框-->
	<div class="popWrap" id="pop" style="width: 655px; display: none">
  <?php echo CHtml::beginForm(Yii::app()->createUrl('mCenter/shopOrder/editAddress',array('order_id'=>$list['id'])),'get',array('id'=>'editForm')); ?>
	<div class="pop_con pAddress">
			<div class="title">
				<span class="name">修改收货地址</span> <span class="cz"><a
					href="javascript:;" onclick="closeWin()">X</a></span>
			</div>
			<div class="pop_content">
				<div class="filed">
					<span class="label">联系地址：</span> <span class="text">
                	<?php
                    	$this->widget('application.components.widgets.SelectorWidget', array(
							'model' => $selector,
                          	'attributeProvince' => 'liveplaceProvince',
                           	'attributeCity' => 'liveplaceCity',
                          	'attributeArea' => 'liveplaceArea',
                           	'selectSepartor' => '&nbsp;&nbsp;&nbsp;&nbsp;',
							'htmlOptions' => array('class'=>'choose')
                       	));
              	    ?>
                </span> <font color="red" id="address_error"></font>
				</div>
				<div class="filed">
					<span class="label">邮政编码：</span> <span class="text">
                    <?php echo CHtml::textField('postCode',!empty($address_arr)?$address_arr[4]:'',array('class'=>'txt','placeholder'=>'请输入邮政编码')); ?>
                </span> <font color="red" id="postCode_error"></font>
				</div>
				<div class="filed">
					<span class="label stick">街道地址：</span> <span class="text">
                	<?php echo CHtml::textArea('streetAddress',!empty($address_arr)?$address_arr[3]:''); ?>
                </span> <font color="red" id="streetAddress_error"></font>
				</div>
				<div class="filed">
					<span class="label">收件人姓名：</span> <span class="text">
                    <?php echo CHtml::textField('userName',!empty($address_arr)?$address_arr[5]:'',array('class'=>'txt','placeholder'=>'请输入收件人姓名')); ?>
                </span> <font color="red" id="userName_error"></font>
				</div>
				<div class="filed">
					<span class="label">手机号码：</span> <span class="text">
                    <?php echo CHtml::textField('userPhone',!empty($address_arr)?$address_arr[6]:'',array('class'=>'txt','placeholder'=>'请输入电话号码')); ?>
                </span> <font color="red" id="userPhone_error"></font>
				</div>
				<div class="filed">
					<span class="label"></span> <span class="text"> <input
						type="button" value="确定" class="btn_com_blue"
						style="margin-right: 20px" onclick="save()">
                  	<?php echo CHtml::submitButton('取消',array('id'=>'cancle','class'=>'btn_com_gray')); ?>
                </span>
				</div>
			</div>
		</div>
    <?php echo CHtml::endForm(); ?>
</div>

	<!-- 卖家备注弹出框 -->
	<div class="popWrap" id="seller_remark_pop"
		style="width: 550px; display: none">
 <?php echo CHtml::beginForm(Yii::app()->createUrl('mCenter/shopOrder/editSellerRemark',array('order_id'=>$list['id'],'type'=>'detail')),'get',array('id'=>'editRemarkForm')); ?>
<div class="pop_con pop_remark pAddress">
			<div class="title">
				<span class="name">修改卖家备注</span> <span class="cz"><a
					href="javascript:;" onclick="closeSellRemarkWin()">X</a></span>
			</div>
			<div class="pop_content">
				<div class="filed">
					<span class="label stick">备注：</span> <span class="text">
             <?php echo CHtml::textArea('seller_remark',$list['seller_remark'],array('placeholder'=>'请填写备注，字数在250字内')); ?>
          </span> <font color="red" id="seller_remark_error"></font>
				</div>
				<div class="filed">
					<span class="label"></span> <span class="text"> <input
						type="button" value="确定" class="btn_com_blue"
						style="margin-right: 20px" onclick="saveRemark()">
               <?php echo CHtml::submitButton('取消',array('id'=>'cancleRemark','class'=>'btn_com_gray')); ?>
          </span>
				</div>
			</div>
		</div>
 <?php echo CHtml::endForm(); ?>
</div>
	<!-- 卖家备注弹出框END -->

	<!--发货弹出框-->
	<div class="popWrap" id="send_pop" style="width: 600px; display: none">
		<div class="pop_con">
			<div class="title">商品发货</div>
			<a href="javascript:;" class="close" onclick="closeSend()">×</a>
		<?php echo CHtml::beginForm(Yii::app()->createUrl('mCenter/shopOrder/send',array('order_id'=>$list['id'],'type'=>'detail')),'get',array('id'=>'sendForm')); ?>
        <div class="pop_content popShopDelivery">
				<table width="100%" cellspacing="0" cellpadding="0" class="tableV">
					<thead>
						<tr>
							<th width="20"><input type="checkbox" id="all_check"></th>
							<th align="left">商品</th>
							<th align="left">数量</th>
							<th align="left">物流公司</th>
							<th align="left">快递单号</th>
						</tr>
					</thead>
              	<?php if(!empty($list['order_sku'])){?><?php $length=0; ?>
                  <?php for($i=0;$i<count($list['order_sku']);$i++){ ?>
                     
                    <?php if($list['order_sku'][$i]['if_send'] == IF_SEND_NO &&($list['order_sku'][$i]['status'] == ORDER_SKU_STATUS_NORMAL || $list['order_sku'][$i]['status'] == ORDER_REFUND_STATUS_APPLY_REFUND_RETURN)){ ?>
                    <?php $length++; ?>
              	<tbody>
						<tr class="list-title">
							<td><input class="sku" type="checkbox" name='order_sku[]'
								value="<?php echo $list['order_sku'][$i]['id']; ?>"></td>
							<td align="left"><a href="#" class="blue"><?php echo  $list['order_sku'][$i]['product_name']; ?></a>


							</td>
							<td align="left"><?php echo  $list['order_sku'][$i]['num']; ?></td>
							<td align="left"></td>
							<td align="left"></td>
						</tr>
					</tbody>
              	<?php }?>
              	<?php }?>
              	<?php }?>
           	</table>
				<font color="red" id="order_id_error"></font>
				<dl>
					<dt class="inlineBlock">收货地址：</dt>
<!--					<dd class="inlineBlock">--><?php //echo $address_arr[0].' '.$address_arr[1].' '.$address_arr[2].' '.$address_arr[3] ;?><!--</dd>-->
				</dl>
				<dl>
					<dt class="inlineBlock">发货方式：</dt>
<!--					<dd class="inlineBlock">-->
<!--                    --><?php //echo CHtml::radioButtonList('send_type', SEND_TYPE_YESLOGISTICS, $GLOBALS['SEND_TYPE'],array('separator'=>'')); ?>
<!--                </dd>-->
				</dl>
				<dl id="express_info">
					<dt class="inlineBlock">物流公司：</dt>
<!--					<dd class="inlineBlock">-->
<!--                	--><?php //echo CHtml::dropDownList('express_name', '', $GLOBALS['LOGISTICS_COMPANY'],array('prompt'=>'请选择物流公司')); ?>
<!--                	 <font color="red" id="express_name_error"></font>-->
<!--                                          快递单号：--><?php //echo CHtml::textField('express_no','',array('class'=>'txt')); ?>
<!--                  <font color="red" id="express_no_error"></font>-->
<!--						<div class="remark">*发货后，10分钟内可修改一次物流信息</div>-->
<!--					</dd>-->
				</dl>
				<div class="btn">
					<input type="button" value="确定" class="btn_com_blue"
						onclick="saveSend(<?php echo $length; ?>)">
				</div>
			</div>
        <?php echo CHtml::endForm(); ?>
    </div>
	</div>
	<!--发货弹出框END-->

	<script type="text/javascript">
	
	$(document).ready(function (){
		var _w = $(window).width() //浏览器窗口宽度
		var _w = $(window).height() //浏览器窗口高度
		var _offsetW = $('#pop').width(); //获取弹出框的宽度
		var _offsetH = $('#pop').height(); //获取弹出框的高度
		
		var _left = ($(window).width()-$('#pop').width())/2; 
		var _top = ($(window).height()-$('#pop').height())/2; 
				
		$('#pop').css({'left' : _left, 'top' : 300});
		
	})
	
	$(document).ready(function (){
		var _w = $(window).width() //浏览器窗口宽度
		var _w = $(window).height() //浏览器窗口高度
		var _offsetW = $('#seller_remark_pop').width(); //获取弹出框的宽度
		var _offsetH = $('#seller_remark_pop').height(); //获取弹出框的高度
		
		var _left = ($(window).width()-$('#seller_remark_pop').width())/2; 
		var _top = ($(window).height()-$('#seller_remark_pop').height())/2; 
				
		$('#seller_remark_pop').css({'left' : _left, 'top' : 250});
	});	

	$(document).ready(function (){
		var _w = $(window).width() //浏览器窗口宽度
		var _w = $(window).height() //浏览器窗口高度
		var _offsetW = $('#send_pop').width(); //获取弹出框的宽度
		var _offsetH = $('#send_pop').height(); //获取弹出框的高度
		
		var _left = ($(window).width()-$('#send_pop').width())/2; 
		var _top = ($(window).height()-$('#send_pop').height())/2; 
				
		$('#send_pop').css({'left' : _left, 'top' : _top});
	});	
	
	//跳出地址修改弹出框
	function openWin()
	{
		$("#pop").show();
	}

	//关闭地址修改弹出框
	function closeWin()
	{
		$("#pop").hide();
	}

	$("#cancle").click(function(){
		$("#pop").hide();
		return false;
	});

	//跳出备注修改弹出框
	function openSellerRemark()
	{
		$("#seller_remark_pop").show();
	}

	//关闭备注修改弹出框
	function closeSellRemarkWin()
	{
		$("#seller_remark_pop").hide();
	}

	$("#cancleRemark").click(function(){
		$("#seller_remark_pop").hide();
		return false;
	});

	//地址修改表单操作
	function save()
	{
		var flag = true;
		var liveplaceProvince = $("#Selector_liveplaceProvince").val(); //省份
		var liveplaceCity = $("#Selector_liveplaceCity").val(); //城市
		var liveplaceArea = $("#Selector_liveplaceArea").val(); //区域
		var postCode = $("#postCode").val(); //邮政编码
		var streetAddress = $("#streetAddress").val(); //街道地址
		var userName = $("#userName").val(); //收件人姓名
		var userPhone = $("#userPhone").val(); //手机号码

		var regPhone = /^(1(([35][0-9])|(47)|[8][0126789]))\d{8}$/; //手机号正则
		var regPostCode =   /^[0-9][0-9]{5}$/; //邮编正则

		if(liveplaceProvince == ''){
			flag = false;
			$("#address_error").html('请选择省份');
		}else{
			$("#address_error").html('');
		}

		if(liveplaceCity == ''){
			flag = false;
			$("#address_error").html('请选择城市');
		}else{
			$("#address_error").html('');
		}

		if(liveplaceArea == ''){
			flag = false;
			$("#address_error").html('请选择区域');
		}else{
			$("#address_error").html('');
		}

		if(postCode == ''){
			flag = false;
			$("#postCode_error").html('请填写邮政编码');
		}else{
			if(!regPostCode.test(postCode)){
				flag = false;
				$("#postCode_error").html('请填写正确的邮政编码');
			}else{
			    $("#postCode_error").html('');
			}
		}

		if(streetAddress == ''){
			flag = false;
			$("#streetAddress_error").html('请填写街道地址');
		}else{
			if(streetAddress.length > 30){
				flag = false;
				$("#streetAddress_error").html('街道地址长度应小于30');
			}else{
			    $("#streetAddress_error").html('');
			}
		}

		if(userName == ''){
			flag = false;
			$("#userName_error").html('请填写收件人姓名');
		}else{
			if(userName.length > 10){
				flag = false;
				$("#userName_error").html('姓名长度应小于10');
			}else{
			    $("#userName_error").html('');
			}
		}

		if(userPhone == ''){
			flag = false;
			$("#userPhone_error").html('请填写手机号码');
		}else{
			if(!regPhone.test(userPhone)){ //正则验证手机号合法性
				flag = false;
				$("#userPhone_error").html('请填写正确的手机号');
			}else{
			    $("#userPhone_error").html('');
			}
		}

		if(flag){
			$("#editForm").submit(); //提交表单
		}
	}

	//卖家备注修改表单操作
	function saveRemark()
	{
		var flag = true;
		var seller_remark = $("#seller_remark").val();

		if(seller_remark == ''){
			flag = false;
			$("#seller_remark_error").html('请填写备注');
		}else{
			if(seller_remark.length > 250){
				flag = false;
				$("#seller_remark_error").html('备注长度不能超过250字');
			}else{
				$("#seller_remark_error").html('');
			}
		}

		if(flag){
			$("#editRemarkForm").submit(); //提交表单
		}
	}


	function send()
	{
		$("#send_pop").show();
	   //_pop("send_pop");
	}

	function closeSend()
	{
		$("#send_pop").hide();
	}

	$("#send_type_0").click(function(){ //需要物流  物流公司  快递单号 显示
		$("#express_info").show();
	});

	$("#send_type_1").click(function(){ //无需物流  物流公司  快递单号隐藏
		$("#express_info").hide();
	});

	//发货表单操作
	function saveSend(length)
	{
		var flag = true;
		var express_name = $("#express_name").val(); //物流公司
		var express_no = $("#express_no").val(); //快递单号

		var sum = 0; //定义未选中数量


		$("input[name='order_sku[]']:checkbox").each(function(){
			if(!$(this).prop("checked")){ //遍历复选框   如果没选中sum加1(注意前面的！)
				sum++;	
			}
		});
        if(sum == length){ //没选中的个数等于未发货订单sku信息数量则提示错误
		    flag = false;
			$("#order_id_error").html('请勾选商品');
		}else{
			 $("#order_id_error").html('');
	    }

        if($("#send_type_0").is(":checked")){ //当选中需要物流的时候才验证
		if(express_name == ''){
			flag = false;
			$("#express_name_error").html('请选择物流公司');
		}else{
			$("#express_name_error").html('');
		}
        }

        if($("#send_type_0").is(":checked")){
		if(express_no == ''){
			flag = false;
			$("#express_no_error").html('请填写快递单号');
		}else{
			$("#express_no_error").html('');
		}
        }

		if(flag){
			$("#sendForm").submit();
		}
	}


	   //全选 复选框 选中状态
	   $("#all_check").click(function(){
		   if($("#all_check").prop("checked")){
			   $("[name='order_sku[]']").prop("checked",true);//全选     
			}else{
			   $("[name='order_sku[]']").prop("checked",false);
			   //$("[name='Coupons[use_store][]']").removeAttr("checked");//取消全选  
	        }
		});
		var CouponsNum = 0;
	   $("[name='order_sku[]']").click(function(){
		   	//当某个多选框没选中 及 全选框选中，则把全选框设置成未选中状态
			if(!$(this).prop("checked") && $("#all_check").prop("checked")){
				$("#all_check").prop("checked",false);
			}

			//计算所有使用订单sku选中的个数
			$("[name='order_sku[]']").each(function(){
				if($(this).prop("checked")){
					CouponsNum ++;
				}
			})
			//当选中订单sku的个数和所有订单sku的总数相等的时候，就把全选框选中
			if(CouponsNum == $("[name='order_sku[]']").length){
				$("#all_check").prop("checked",true);
			}
			CouponsNum = 0; //把值重新初始为0，不然下次计算就会一直叠加上去
	   })


	    //复制地址到剪切板
		$('#copy-botton').zclip({
			path:"<?php echo GJ_STATIC_JS?>ZeroClipboard.swf",
			copy:function(){
				return $('#address_content').val();
			},
			afterCopy:function(){/* 复制成功后的操作 */
		            alert('成功复制到剪切板，Ctrl+V即可粘贴');
		        }
			});

</script>