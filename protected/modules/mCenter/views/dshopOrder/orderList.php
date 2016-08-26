
<title>添加会员等级</title>
<body>
<script type="text/javascript">
    $(document).ready(statistics_obj.stat_init);
	$(document).ready(main_obj.list_init);
</script>

<div class="kkfm_r_inner">
	 <div class="top">
        <div class="name">订单管理</div>
     </div>
     <div class="shop_menu">
    	<ul>
        	<li class="cur"><a href="<?php echo Yii::app()->createUrl('mCenter/dshopOrder/orderList') ?>">全部订单</a></li>
        	 <li>
        		<a href="<?php echo Yii::app()->createUrl('mCenter/dshopActivist/ActivistList') ?>">维权订单</a>
        	</li>
        </ul>
     </div>
     <div class="orderList">
     <div class="sh-search clearfix"></div>
     <?php echo CHtml::beginForm('','get'); ?>
     	<div class="search clearfix">
        	<div class="filed">
            	<span class="label">订单号</span>
                <span class="text">
                  <?php echo CHtml::textField('Order[order_no]',/*isset($_GET['Order']['order_no'])?$_GET['Order']['order_no']:*/'',array('class'=>'txt','placeholder'=>'请输入订单号')); ?>
                </span>
            </div>
            <div class="filed">
            	<span class="label">订单状态</span>
                <span class="text">
                	<?php echo CHtml::dropDownList('order_status', /*isset($_GET['order_status'])?$_GET['order_status']:*/'', $GLOBALS['ORDER_STATUS'],array('prompt'=>'请选择')); ?>
                </span>
            </div>
             <div class="filed">
            	<span class="label">付款方式</span>
                <span class="text">
                	<?php echo CHtml::dropDownList('Order[pay_channel]', /*isset($_GET['Order']['pay_channel'])?$_GET['Order']['pay_channel']:*/'', $GLOBALS['SHOP_ORDER_PAY_CHANNEL'],array('prompt'=>'请选择')); ?>
                </span>
            </div>
            <div class="clear"></div>
            <div class="filed">
            	<span class="label">下单时间</span>
                <span class="text">
                   <?php echo CHtml::textField('Time',/*isset($_GET['Time'])?$_GET['Time']:*/'',array('class'=>'txt')); ?>
                </span>
            </div>
            
            <div class="filed">
            	<span class="label">收货人姓名</span>
                <span class="text">
                    <?php echo CHtml::textField('Order[user_name]',/*isset($_GET['Order']['user_name'])?$_GET['Order']['user_name']:*/'',array('class'=>'txt','placeholder'=>'请输入姓名')); ?>
                </span>
            </div>
            
            <div class="filed">
            	<span class="label">收货人手机</span>
                <span class="text">
                    <?php echo CHtml::textField('Order[user_phone]',/*isset($_GET['Order']['user_phone'])?$_GET['Order']['user_phone']:*/'',array('class'=>'txt','placeholder'=>'请输入手机号')); ?>
                </span>
            </div>
            <div class="clear"></div>
             <div class="filed">
            	<span class="label"></span>
                <span class="text">
                	<input  type="submit" class="btn_com_blue" value="筛选">
                	<a href="<?php echo Yii::app()->createUrl('mCenter/dshopOrder/exportExcel',
                			array('order_no'=>isset($_GET['Order']['order_no'])?$_GET['Order']['order_no']:'','Time'=>isset($_GET['Time'])?$_GET['Time']:'',
                             'order_type'=>isset($_GET['Order']['order_type'])?$_GET['Order']['order_type']:'','order_status'=>isset($_GET['order_status'])?$_GET['order_status']:'',
                             'pay_channel'=>isset($_GET['Order']['pay_channel'])?$_GET['Order']['pay_channel']:'','user_name'=>isset($_GET['Order']['user_name'])?$_GET['Order']['user_name']:'',
                             'user_phone'=>isset($_GET['Order']['user_phone'])?$_GET['Order']['user_phone']:'','pay_status'=>isset($_GET['pay_status'])?$_GET['pay_status']:'')); ?>" class="btn_com_gray">导出</a>
                </span>
            </div>
        </div>
        <?php echo CHtml::endForm(); ?>
        <div class="orderWarp">
            <div class="status-nav clearfix">
                <ul>
                    <li class="<?php echo (!empty($_GET['order_status'])||isset($_GET['pay_status']))?'':'cur'; ?>"><a href="<?php echo Yii::app()->createUrl('mCenter/dshopOrder/orderList'); ?>">全部</a></li>
                    <li class="<?php if(isset($_GET['pay_status'])){echo ($_GET['pay_status']==ORDER_STATUS_UNPAID && !isset($_GET['order_status']))?'cur':'';} ?>"><a href="<?php echo Yii::app()->createUrl('mCenter/dshopOrder/orderList',array('pay_status'=>ORDER_STATUS_UNPAID)); ?>">待付款<?php if($noPayCount > 0){echo '('.$noPayCount.')';} ?></a></li>
                    <li class="<?php if(isset($_GET['order_status'])){echo $_GET['order_status']==ORDER_STATUS_DELIVER?'cur':'';} ?>"><a href="<?php echo Yii::app()->createUrl('mCenter/dshopOrder/orderList',array('order_status'=>ORDER_STATUS_DELIVER)); ?>">已出票<?php if($deliverCount > 0){echo '('.$deliverCount.')';} ?></a></li>
					<li class="<?php if(isset($_GET['order_status'])){echo $_GET['order_status']==ORDER_STATUS_ACCEPT?'cur':'';} ?>"><a href="<?php echo Yii::app()->createUrl('mCenter/dshopOrder/orderList',array('order_status'=>ORDER_STATUS_ACCEPT)); ?>">确认出票<?php if($acceptCount > 0){echo '('.$acceptCount.')';} ?></a></li>
                    <li class="last <?php if(isset($_GET['order_status'])){echo $_GET['order_status']==ORDER_STATUS_CANCEL?'cur':'';} ?>"><a href="<?php echo Yii::app()->createUrl('mCenter/dshopOrder/orderList',array('order_status'=>ORDER_STATUS_CANCEL)); ?>">已取消<?php if($cancelCount > 0){echo '('.$cancelCount.')';} ?></a></li>
                </ul>
                <hr>
            </div>
            <div class="order-list">
            	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                	<thead>
                      <tr class="orderHead">
                        <td>商品</td>
                        <td>数量/单位</td>
                        <td>售后</td>
                        <td>买家</td>
                        <td>下单时间</td>
                        <td>订单状态</td>
                        <td>实付金额</td>
                      </tr>
                      <tr><td height="10"></td></tr>
                  </thead>
                  <?php if(!empty($list)){ ?>
                  <?php foreach ($list as $k=>$v){ ?>
                  <tbody>
                  	<tr class="list-title">
                        <td colspan="6" >
                            <span>订单编号：<?php echo $v['order_no']; ?></span>
                            <span><?php echo isset($v['pay_channel'])?$GLOBALS['ORDER_PAY_CHANNEL'][$v['pay_channel']]:''; ?></span>
                            <?php if(!empty($v['trade_no'])){?>
                            <span class=" clearfix">交易号：<?php echo $v['trade_no']; ?></span>
                            <?php }?>
                        </td>
                        <td align="right">
                        	<a href="<?php echo Yii::app()->createUrl('mCenter/dshopOrder/orderDetail',array('order_id'=>$v['id'],'goUrl'=>Yii::app()->request->url)); ?>">订单详情</a>
                            <a href="javascript:;" id="seller_remark_btn">备注</a>
                            <input style="display: none" value="<?php echo $v['id']?>" name="order_id_input">
                        </td>
    				</tr> 
    				
    			<?php if(!empty($v['order_sku'])){?>
                  <?php for($i=0;$i<count($v['order_sku']);$i++){ ?>
                  	<tr class="orderCon">
                        <td valign="top" width="388">
                            <a href="#">
                                <div class="list-img"><img src="<?php $img_arr=explode(';', IMG_GJ_LIST.$v['order_sku'][$i]['product_img']);echo $img_arr['0'];?>" /></div>
                                <div class="list-name"><?php echo $v['order_sku'][$i]['product_name']; ?></div>
                                <div class="list-info"><?php echo $v['order_sku'][$i]['sku_name']; ?></div>
                                <?php if(!empty($v['thrid_party_code'])){?><div class="list-info">订单码号：<?php echo $v['thrid_party_code']; ?></div><?php }?>
                            </a>   
                        </td>

                        <td class="border">
                        <span style="font-size: 16px">¥ <?php echo $v['order_sku'][$i]['price']; ?></span><span style="font-size: 16px"> × <?php echo $v['order_sku'][$i]['num']; ?></span>
                        <?php if($v['order_status'] == ORDER_STATUS_DELIVER || $v['order_status'] == ORDER_STATUS_ACCEPT){?><p>已核销：<em class="wq-orange" style="font-size: 20px"><?php echo $v['order_sku'][$i]['consume_num']?></em> 次</p><?php }?>
                        </td>
                        
                        <td class="border" width="90"><?php if($v['order_status'] == ORDER_STATUS_REFUND){echo '用户维权结束';}elseif ($v['if_refund'] == 1){echo '用户提交维权';} ?></td>
                        <?php if($i==0){?>
                        <td class="border" rowspan="<?php echo count($v['order_sku']); ?>"><span><?php echo $v['user_name']; ?></span></td>
                        <td class="border" rowspan="<?php echo count($v['order_sku']); ?>"><?php echo date('Y.m.d',strtotime($v['create_time'])); ?><span><?php echo date('H:i:s',strtotime($v['create_time'])); ?></span></td>

                        <td class="border" rowspan="<?php echo count($v['order_sku']); ?>">
                        <?php if(isset($v['order_status']) && $v['order_status'] == ORDER_STATUS_CANCEL)
                        {
                        	echo '已取消';
                        }elseif(isset($v['order_status']) && $v['order_status'] == ORDER_STATUS_DELIVER){
                        	echo '已出票';
                        }elseif (isset($v['order_status']) && $v['order_status'] == ORDER_STATUS_ACCEPT){
                            echo '已确认';
                        }else {
                            echo isset($v['order_status']) && $v['pay_status'] == ORDER_STATUS_PAID?$GLOBALS['ORDER_STATUS'][$v['order_status']]:'待付款';
                        }
                        ?>
                          <?php if($v['order_status'] == ORDER_STATUS_WAITFORDELIVER && $v['isSend'] > 0 && $v['pay_status'] == ORDER_STATUS_PAID){ ?>
                           <span>
                               <?php if($v['order_sku'][$i]['status']==ORDER_SKU_STATUS_NORMAL){?>
                           		<a href="javascript:;" class="btn_com_gray" onclick="ordersend(<?php echo $v['id']?>,this)">发货</a>
                           		<?php }?>
                                  <input style="display: none" value="<?php echo $v['id']?>">
                           </span>
                          <?php }?>
                        </td>
                        <td rowspan="<?php echo count($v['order_sku']); ?>" style="font-size: 16px">¥ <?php echo number_format($v['real_pay'],2,'.',''); ?><span></span></td>
                  	    <?php }?>
                      </tr>
                  <?php }?>
                 <?php }?>
                    
                  	<!-- <?php /*if(!empty($v['order_sku'])){*/?>
                  	 <?php /*for($i=1;$i<count($v['order_sku']);$i++){ */?>
                    <tr class="orderCon">
                            <td valign="top" width="388">
                                <a href="#">
                                    <div class="list-img"><img src="<?php /*echo IMG_GJ_LIST.$v['order_sku'][$i]['product_img']; */?>" /></div>
                                    <div class="list-name"><?php /*echo $v['order_sku'][$i]['product_name']; */?></div>
                                    <div class="list-info"><?php /*echo $v['order_sku'][$i]['sku_name']; */?></div>
                                </a>
                            </td>
                             <td class="border"><?php /*echo $v['order_sku'][$i]['price']; */?><span>*<?php /*echo $v['order_sku'][$i]['num']; */?></span></td>
                    </tr>
                    <?php /*}*/?>
                    --><?php /*}*/?>
                    <?php if(!empty($v['seller_remark'])){?>
                    <tr><td class="remarks"  colspan="7">卖家备注：<?php echo $v['seller_remark']; ?></td></tr>
                    <?php }?>
                    <tr><td class="bottom"  colspan="7"></td></tr>
                  </tbody>
               <?php }?>
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
     </div>
</div>

<!-- 卖家备注弹出框 -->
<div class="popWrap" id="seller_remark_pop" style="width:550px;display:none;position: fixed">
 <?php echo CHtml::beginForm(Yii::app()->createUrl('mCenter/dshopOrder/editSellerRemark',array('type'=>'list')),'get',array('id'=>'editRemarkForm')); ?>
<div class="pop_con pop_remark pAddress">
   <div class="title">
       <span class="name">修改卖家备注</span>
       <span class="cz"><a href="javascript:;" onclick="closeSellRemarkWin()">X</a></span>
   </div>
   <div class="pop_content">
      <div class="filed">
  		<span class="label stick">备注：</span>
          <span class="text">
          		<textarea rows="3" placeholder='请填写备注，字数在250字内' id="seller_remark" name="seller_remark"></textarea>
          </span>
         <font color="red" id="seller_remark_error"></font>
      </div>
      <input type="hidden" id="orderId" name="order_id">
     <div class="filed">
        <span class="label"></span>
          <span class="text">
              <input type="button" value="确定" class="btn_com_blue" style="margin-right:20px" onclick="saveRemark()">
               <?php echo CHtml::submitButton('取消',array('id'=>'cancleRemark','class'=>'btn_com_gray')); ?>
          </span>
     </div>
  </div>
</div>
 <?php echo CHtml::endForm(); ?>
</div>
<!-- 卖家备注弹出框END -->

<!--发货弹出框-->
<div class="popWrap" id="send_pop" style="width:600px; display:none">
    <div class="pop_con">
		<div class="title">商品发货</div><a href="javascript:;" class="close" onclick="closeSend()">×</a>
		<?php echo CHtml::beginForm(Yii::app()->createUrl('mCenter/dshopOrder/send',array('type'=>'list')),'get',array('id'=>'sendForm')); ?>
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
              
              	<tbody id="tbody">
                    <tr class="list-title" id="tr">
                        
                    </tr>  
              	</tbody>
     
           	</table>
           	<input type="hidden"  id="length">
           	<input type="hidden" id="orderid" name="order_id">
           	<font color="red" id="order_id_error"></font>
           	<dl>
                <dt class="inlineBlock">收货地址：<font id="address_send"></font></dt>
                <dd class="inlineBlock"></dd>
           	</dl>
            <dl>
                <dt class="inlineBlock">发货方式：</dt>
                <dd class="inlineBlock">
                    <?php echo CHtml::radioButtonList('send_type', SEND_TYPE_YESLOGISTICS, $GLOBALS['SEND_TYPE'],array('separator'=>'')); ?>
                </dd>
           	</dl>
            <dl id="express_info">
                <dt class="inlineBlock">物流公司：</dt>
                <dd class="inlineBlock">
                	<?php echo CHtml::dropDownList('express_name', '', $GLOBALS['LOGISTICS_COMPANY'],array('prompt'=>'请选择物流公司')); ?>
                	 <font color="red" id="express_name_error"></font>
                                          快递单号：<?php echo CHtml::textField('express_no','',array('class'=>'txt')); ?>
                  <font color="red" id="express_no_error"></font>
                    <div class="remark">*发货后，10分钟内可修改一次物流信息</div>
                </dd>
           	</dl>
            <div class="btn">
            	<input type="button" value="确定" class="btn_com_blue" onclick="saveSend()">
            </div>
        </div>
        <?php echo CHtml::endForm(); ?>
    </div>   
</div>
<!--发货弹出框END-->

</body>

<script type="text/javascript">

var seller_remark_pop_left = null;
var send_pop_left = null;

$(document).ready(function (){
	var _w = $(window).width() //浏览器窗口宽度
	var _w = $(window).height() //浏览器窗口高度
	var _offsetW = $('#seller_remark_pop').width(); //获取弹出框的宽度
	var _offsetH = $('#seller_remark_pop').height(); //获取弹出框的高度
	
	var _left = ($(window).width()-$('#seller_remark_pop').width())/2; 
	var _top = ($(window).height()-$('#seller_remark_pop').height())/2; 
	seller_remark_pop_left = _left;
	$('#seller_remark_pop').css({'left' : _left, 'top' : _top});
});	

$(document).ready(function (){
	var _w = $(window).width() //浏览器窗口宽度
	var _w = $(window).height() //浏览器窗口高度
	var _offsetW = $('#send_pop').width(); //获取弹出框的宽度
	var _offsetH = $('#send_pop').height(); //获取弹出框的高度

	//var scrolltop = document.body.scrollTop;
	
	var _left = ($(window).width()-$('#send_pop').width())/2; 
	var _top = ($(window).height()-$('#send_pop').height())/2; 
	send_pop_left = _left;		
	$('#send_pop').css({'left' : _left, 'top' : _top});
});	

//跳出备注修改弹出框
	$('[id=seller_remark_btn]').click(function(){
		var order_id = $(this).next('input').val();
		$.ajax({
			url : '<?php echo Yii::app()->createUrl('mCenter/dshopOrder/getAjaxData'); ?>',
			data : {order_id : order_id},
			type : 'post',
			async: false,
			success : function(data){
				var data = jQuery.parseJSON(data);
				$("#orderId").val(data.id);
				$("#seller_remark").val(data.seller_remark);
			}
		});
		var X = $(this).offset().top;
		$('#seller_remark_pop').css({'left' : seller_remark_pop_left, 'top' : X-150});
		$("#seller_remark_pop").show();
	});

//关闭备注修改弹出框
function closeSellRemarkWin()
{
	$("#seller_remark_pop").hide();
}

$("#cancleRemark").click(function(){
	$("#seller_remark_pop").hide();
	return false;
});

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


	function ordersend(order_id,this_btn){
		var count = 0; //定义未发货的商品数量
		$.ajax({
			url : '<?php echo Yii::app()->createUrl('mCenter/dshopOrder/getAjaxDataForSend'); ?>',
			data : {order_id : order_id},
			type : 'post',
			async: false,
			success : function(data){
				var data = jQuery.parseJSON(data);
				$("#address_send").html(data.address);
				$("#orderid").val(data.id);
				$("#tbody").empty();//删除所有节点
				$.each(data.order_sku,function(inx,item){
				 	if(item.if_send == <?php echo IF_SEND_NO; ?> && item.status == <?php echo ORDER_SKU_STATUS_NORMAL; ?>){
						count++; //未发货的商品数量加1
						$("#tbody").append('<tr class="list-title">'+'<td>'+'<input name="order_sku[]" type="checkbox" value="'+item.id+'">'+'</td>'
							+'<td align="left">'+'<a href="#" class="blue">'+item.product_name+'</a>'+'</td>'
							+'<td align="left">'+item.num+'</td>'
							+'<td align="left">'+'</td>'
							+'<td align="left">'+'</td>'+'</tr>');
				 	}
				});
				$("#length").val(count);
			}
		});
		var X = $(this_btn).offset().top;
		$('#send_pop').css({'left' : send_pop_left, 'top' : X-150});
		$("#send_pop").show();
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
function saveSend()
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
    if(sum == $("#length").val()){ //没选中的个数等于未发货订单sku信息数量则提示错误
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
     	}
	});
	var CouponsNum = 0;

	//Jquery 1.7 $("input[name='order_sku[]']").live("click",function(){ }
	//Jquery 1.90以上就要用这种形式 (动态加载的数据)
    $(document).on('click',"input[name='order_sku[]']",function(){
	   	//当某个多选框没选中 及 全选框选中，则把全选框设置成未选中状态
		if(!$(this).prop("checked") && $("#all_check").prop("checked")){
			$("#all_check").prop("checked",false);
		}
		
		//计算所有使用订单sku选中的个数
		CouponsNum = $("input[name='order_sku[]']:checked").length;
/* 		$("input[name='order_sku[]']").each(function(){
			if($(this).prop("checked")){
				CouponsNum ++;
			}
		}); */
		//当选中订单sku的个数和所有订单sku的总数相等的时候，就把全选框选中
		if(CouponsNum == $("input[name='order_sku[]']").length){
			$("#all_check").prop("checked",true);
		}
		CouponsNum = 0; //把值重新初始为0，不然下次计算就会一直叠加上去
    });

</script>


