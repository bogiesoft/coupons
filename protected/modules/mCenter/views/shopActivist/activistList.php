
<body>
<script type="text/javascript">
    $(document).ready(statistics_obj.stat_init);
	$(document).ready(main_obj.list_init);
</script>

<div class="kkfm_r_inner">
	 <div class="top">
        <div class="name">维权管理</div>
        <!--  <div class="cz"><a href="" class="btn_com_gray">返回</a></div>-->
     </div>
     <div class="shop_menu">
    	<ul>
        	<li><a href="<?php echo Yii::app()->createUrl('mCenter/shopOrder/orderList'); ?>">全部订单</a></li>
        	<li class="cur"><a href="<?php echo Yii::app()->createUrl('mCenter/shopActivist/activistList'); ?>">维权订单</a></li>
        </ul>
     </div>
     <div class="orderList">
        <div class="orderWarp">           
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
                  <?php if(!empty($v['order_sku'])){ ?>
                  <tbody>
                  	<tr class="list-title">
                        <td colspan="6" >
                            <span>订单编号：<?php echo $v['order_no']; ?></span>
                            <span><?php echo isset($v['pay_channel'])?$GLOBALS['ORDER_PAY_CHANNEL'][$v['pay_channel']]:''; ?></span>
                            <span class=" clearfix">支付宝交易号：<?php echo $v['trade_no']; ?></span>
                        </td>
                        <td align="right">
                        	<a href="<?php echo Yii::app()->createUrl('mCenter/shopOrder/orderDetail',array('order_id'=>$v['id'],'goUrl'=>Yii::app()->request->url)); ?>">订单详情</a>
                            <a href="javascript:;" onclick="openSellerRemark(<?php echo $v['id']; ?>)">备注</a>
                        </td>
    				</tr> 
    				
    			<?php if(!empty($v['order_sku'])){?>
                  <?php foreach($v['order_sku'] as $i=>$val){  ?>
                  	<tr class="orderCon">
                        <td valign="top" width="388">
                            <a href="#">
                                <div class="list-img"><img src="<?php $img=explode(';',$val['product_img']); echo IMG_GJ_LIST.$img[0]; ?>" /></div>
                                <div class="list-name"><?php echo $val['product_name']; ?></div>
                                <div class="list-info"><?php echo $val['sku_name']; ?></div>
                            </a>   
                        </td>
                        <td class="border"><?php echo $val['price']; ?><span>*<?php echo $val['num']; ?></span></td>
                        <td class="border" width="90" rowspan=""><a href="<?php echo Yii::app()->createUrl('mCenter/ShopActivist/ActivistDetail',array('order_id'=>$v['id'],'order_sku_id'=>$val['id'],'order_status'=>$v['order_status'])); ?>" class="list-name"><?php echo isset($GLOBALS['ORDER_SKU_STATUS'][$val['status']])?$GLOBALS['ORDER_SKU_STATUS'][$val['status']]:''; ?></a></td>
                        <td class="border" ><!--rowspan="--><?php /*echo count($v['order_sku']); */?>买家名称<span><?php echo $v['user_name']; ?></span></td>
                        <td class="border" ><!--rowspan="--><?php /*echo count($v['order_sku']); */?><?php echo date('Y.m.d',strtotime($v['create_time'])); ?><span><?php echo date('H:i:s',strtotime($v['create_time'])); ?></span></td>
                        <td class="border" ><!--rowspan="--><?php /*echo count($v['order_sku']); */?><?php echo isset($v['order_status'])?$GLOBALS['ORDER_STATUS'][$v['order_status']]:'待付款'; ?></td>
                        <td ><!--rowspan="--><?php /*echo count($v['order_sku']); */?>"<?php echo number_format($val['price'],2,'.',''); ?><span></span></td>
                  	</tr> 
                  <?php }?>
                 <?php }?>
                    
                 <!-- 	 <?php /*if(!empty($v['order_sku'])){*/?>
                  	 <?php /*for($i=1;$i<count($v['order_sku']);$i++){ */?>
                    <tr class="orderCon">
                            <td valign="top" width="388">
                                <a href="#">
                                    <div class="list-img"><img src="<?php /*echo $v['order_sku'][$i]['product_img']; */?>" /></div>
                                    <div class="list-name"><?php /*echo $v['order_sku'][$i]['product_name']; */?></div>
                                    <div class="list-info"><?php /*echo $v['order_sku'][$i]['sku_name']; */?></div>
                                </a>
                            </td>
                             <td class="border"><?php /*echo $v['order_sku'][$i]['price']; */?><span>*<?php /*echo $v['order_sku'][$i]['num']; */?></span></td>
                             <td class="border"><a href="<?php /*echo Yii::app()->createUrl('mCenter/shopActivist/activistDetail',array('order_sku_id'=>$v['order_sku'][$i]['id'],'order_status'=>$v['order_status'])); */?>" class="list-name"><?php /*echo isset($GLOBALS['ORDER_REFUND_STATUS'][$v['order_sku'][$i]['status']])?$GLOBALS['ORDER_REFUND_STATUS'][$v['order_sku'][$i]['status']]:''; */?></a></td>
                    </tr>
                    <?php /*}*/?>
                    --><?php /*}*/?>
                    <?php if(!empty($v['seller_remark'])){?><tr><td class="remarks"  colspan="7">卖家备注：<?php echo $v['seller_remark']; ?></td></tr><?php }?>
                    <tr><td class="bottom"  colspan="7"></td></tr>
                  </tbody>
               <?php }?>
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
<div class="popWrap" id="seller_remark_pop" style="width:550px;display:none">
 <?php echo CHtml::beginForm(Yii::app()->createUrl('mCenter/shopOrder/editSellerRemark',array('type'=>'activistList')),'get',array('id'=>'editRemarkForm')); ?>
<div class="pop_con pop_remark pAddress">
   <div class="title">
       <span class="name">修改卖家备注</span>
       <span class="cz"><a href="javascript:;" onclick="closeSellRemarkWin()">X</a></span>
   </div>
   <div class="pop_content">
      <div class="filed">
  		<span class="label stick">备注：</span>
          <span class="text">
             <?php echo CHtml::textArea('seller_remark','',array('placeholder'=>'请填写备注，字数在250字内')); ?>
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



</body>

<script type="text/javascript">
$(document).ready(function (){
	var _w = $(window).width() //浏览器窗口宽度
	var _w = $(window).height() //浏览器窗口高度
	var _offsetW = $('#seller_remark_pop').width(); //获取弹出框的宽度
	var _offsetH = $('#seller_remark_pop').height(); //获取弹出框的高度
	
	var _left = ($(window).width()-$('#seller_remark_pop').width())/2; 
	var _top = ($(window).height()-$('#seller_remark_pop').height())/2; 
			
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
			
	$('#send_pop').css({'left' : _left, 'top' : _top});
});	

//跳出备注修改弹出框
function openSellerRemark(order_id)
{
	$.ajax({
		url : '<?php echo Yii::app()->createUrl('mCenter/shopOrder/getAjaxData'); ?>',
		data : {order_id : order_id},
		type : 'post',
		async: false,
		success : function(data){
			var data = jQuery.parseJSON(data);
			$("#orderId").val(data.id);
			$("#seller_remark").val(data.seller_remark);
		}
	});
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
</script>



