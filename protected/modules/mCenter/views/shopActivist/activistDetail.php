<body>
<script type="text/javascript">
	$(document).ready(main_obj.list_init);
</script>

<div class="kkfm_r_inner">
	 <div class="top">
        <div class="name">订单详情</div>
     </div>
     <div class="orderDetail orderDRight">
     	<div class="search clearfix">
        	<div class="<?php if($list['refund'][0]['if_return']==IF_RETURN_NO){echo "step stepone";}else{echo "step";}?>">
                <!--需要退货-->
                <?php if($list['refund'][0]['if_return']==IF_RETURN_YES){?>
				<ul class="<?php 
				           if($list['status'] == ORDER_REFUND_STATUS_APPLY_REFUND_RETURN){echo 'step01';}
				           else if (($list['refund'][0]['status']==ORDER_REFUND_STATUS_AGREE_RETURN||$list['refund'][0]['status']==ORDER_REFUND_STATUS_REFUSE_REFUND||$list['status']==ORDER_REFUND_STATUS_REFUSE_RECEIPT)){echo 'step02';}
				           else if ($list['status'] == ORDER_REFUND_STATUS_RETURN_ISSUED||$list['status']==ORDER_REFUND_STATUS_RETURN_RECEIPT){echo 'step03';}
				           else if ($list['status'] == ORDER_REFUND_STATUS_REFUND_SUCCESS){echo 'step04';}
				           ?>">
                <!--'第一步是step01；第二步为step02；第三步step03；第四步step04-->  
                	<li>
                    	<span class="stepName <?php if($list['status'] == ORDER_REFUND_STATUS_APPLY_REFUND_RETURN){echo 'color';}?>">买家申请维权</span>
                        <span class="stepTime"><?php echo $list['refund'][count($list['refund'])-1]['create_time'];?></span>
                    </li>
                    <li>
                    	<span class="stepName <?php if($list['status'] == ORDER_REFUND_STATUS_AGREE_RETURN || $list['status'] == ORDER_REFUND_STATUS_REFUSE_REFUND){echo 'color';} ?>">商家处理退货申请</span>
                        <span class="stepTime"><?php if(!empty($list['refund'][0]['refuse_refund_remark'])||count($list['refund'])>1){echo $list['refund'][0]['create_time'];}?></span>
                    </li>
                    <li>
                    	<span class="stepName <?php  if ($list['status'] == ORDER_REFUND_STATUS_RETURN_ISSUED||$list['status']==ORDER_REFUND_STATUS_RETURN_RECEIPT){echo 'color';} ?>">买家退货给商家</span>
                        <span class="stepTime"><?php if ($list['status'] == ORDER_REFUND_STATUS_RETURN_ISSUED||$list['status']==ORDER_REFUND_STATUS_RETURN_RECEIPT){echo $list['refund'][0]['create_time'];}?></span>
                    </li>
                    <li>
                    	<span class="stepName <?php if ($list['status'] == ORDER_REFUND_STATUS_REFUND_SUCCESS){echo 'color';} ?>">退款完成</span>
                        <span class="stepTime"><?php if($list['status'] == ORDER_REFUND_STATUS_REFUND_SUCCESS){echo $list['refund'][0]['agree_refund_time'];}?></span>
                    </li>
                </ul>
                <?php }else if($list['refund'][0]['if_return']==IF_RETURN_NO){?>
                <!--不需要退货-->
                    <ul class="<?php if($list['status']==ORDER_REFUND_STATUS_APPLY_REFUND_NORETURN&&count($list['refund'])<=1){echo 'step01';}
                        else if(count($list['refund'])>1&&$list['status']!=ORDER_REFUND_STATUS_REFUND_SUCCESS){echo 'step02';}
                        else if($list['status']==ORDER_REFUND_STATUS_REFUND_SUCCESS){echo 'step03';}?>">
                        <li>
                            <span class="stepName <?php if($list['status'] == ORDER_REFUND_STATUS_APPLY_REFUND_NORETURN){echo 'color';}?>">买家申请维权</span>
                            <span class="stepTime"><?php echo $list['refund'][count($list['refund'])-1]['create_time'];?></span>
                        </li>
                        <li>
                            <span class="stepName <?php if($list['status'] == ORDER_REFUND_STATUS_RETURN_RECEIPT || $list['status'] == ORDER_REFUND_STATUS_FINANCIAL_PLAY){echo 'color';} ?>">退款处理中</span>
                            <span class="stepTime"><?php echo $list['refund'][0]['create_time'];?></span>
                        </li>
                        <li class="end">
                            <span class="stepName <?php if ($list['status'] == ORDER_REFUND_STATUS_REFUND_SUCCESS){echo 'step04';} ?>">退款完成</span>
                            <span class="stepTime"><?php if(!empty($list['refund'][0]['agree_refund_time'])){echo $list['refund'][0]['agree_refund_time'];}?></span>
                        </li>
                    </ul>
                <?php }?>
            </div>
        </div>
        <div class="orderWarp clearfix">
			<div class="orderL">
            	<div class="title">售后维权</div>
                <dl class="shopPic">
                	<dt><a href="#"><img src="<?php $img=explode(';',$list['shopProduct_img']['img']); echo IMG_GJ_LIST.$img[0]; ?>"></a></dt>
                    <dd>
                    	<h3>商品名称:<?php echo $list['product_name']; ?></h3>
                        <p>颜色:<?php echo $list['color']." "; ?>尺寸:<?php echo $list['size']; ?></p>
                    </dd>
                </dl>
<!--                <div class="filed">-->
<!--                	<span class="label">期望结果：</span>-->
<!--                    <span class="text"><em class="orange">--><?php //echo isset($GLOBALS['ORDER_SKU_HOPE'][$list['refund_hope']])?$GLOBALS['ORDER_SKU_HOPE'][$list['refund_hope']]:''; ?><!--</em></span>-->
<!--                </div>-->
                <div class="filed">
                	<span class="label">退款金额：</span>
                    <span class="text"><em class="orange"><?php echo $list['refund'][0]['refund_money']; ?></em>元</span>
                </div>
                <div class="filed">
                	<span class="label">维权原因：</span>
                    <span class="text"><?php echo isset($GLOBALS['REFUND_REASON'][$list['refund'][0]['refund_reason']])?$GLOBALS['REFUND_REASON'][$list['refund'][0]['refund_reason']]:''; ?></span>
                </div>
                
                <div class="border"></div>
                
            	<div class="filed">
                	<span class="label">订单编号：</span>
                    <span class="text"><em class="blue"><?php echo $list['order_info']['order_no']; ?></em></span>
                </div>
                <div class="filed">
                	<span class="label">付款时间：</span>
                    <span class="text"><?php echo $list['order_info']['pay_time']; ?></span>
                </div>
                <div class="filed">
                	<span class="label">买家：</span>
                    <span class="text"><?php echo $list['refund'][0]['refund_tel']; ?></span>
                </div>
<!--                --><?php //if($list['if_send'] == IF_SEND_YES){ ?>
<!--                <div class="filed">-->
<!--                	<span class="label">物流信息：</span>-->
<!--                    <span class="text">--><?php //echo $list['skuExpress']['name']; ?><!-- --><?php //echo $list['skuExpress']['express_no']; ?><!-- <a href="#" class="blue">查看</a> </span>-->
<!--                </div>-->
<!--                --><?php //}?>
                <div class="filed">
                	<span class="label">运 费：</span>
                    <span class="text"><?php echo $list['order_info']['freight_money']; ?>元</span>
                </div>
                <div class="filed">
                	<span class="label">实收总价：</span>
                    <span class="text"><em class="orange"><?php echo number_format($list['sku_price'],2,'.',''); ?></em>元</span>
                </div>
            </div>
            <div class="orderR">
            	<div class="title">
            	   <?php if($list['status'] == ORDER_REFUND_STATUS_APPLY_REFUND_RETURN){ ?> <!-- 订单sku状态是申请退款需退货 -->
                	  <span class="status">等待商家处理退款申请</span>
                 	  <span>收到买家退款并退货申请，请尽快处理</span>
                   <?php }elseif ($list['status'] == ORDER_REFUND_STATUS_APPLY_REFUND_NORETURN){?> <!-- 订单sku状态是申请退款无需退货 -->
                      <span class="status">等待商家处理退款申请</span>
                 	  <span>收到买家退款申请，请尽快处理</span>
                   <?php }elseif ($list['status'] == ORDER_REFUND_STATUS_AGREE_NORETURN){?>
                      <span class="status">商家已经同意退款申请</span>
                   <?php }elseif ($list['status'] == ORDER_REFUND_STATUS_AGREE_RETURN){?>
                      <span class="status">商家已经同意退款申请,等待用户发货</span>
                   <?php }elseif ($list['status'] == ORDER_REFUND_STATUS_RETURN_ISSUED){?>
                      <span class="status">用户退货已发出</span>
                   <?php }elseif ($list['status'] == ORDER_REFUND_STATUS_RETURN_RECEIPT){?>
                       <span class="status">商家已收所退的货</span>
                      <span>请尽快打款给用户</span>
                   <?php }elseif ($list['status'] == ORDER_REFUND_STATUS_REFUND_SUCCESS){?>
                      <span class="status">退款完成</span>
                   <?php }elseif ($list['status'] == ORDER_REFUND_STATUS_REFUSE_REFUND){?>
                      <span class="status">商家拒绝退款</span>
                   <?php }elseif ($list['status'] == ORDER_REFUND_STATUS_REFUSE_RECEIPT){?>
                       <span class="status">商家拒绝收货</span>
                   <?php }?>
                 </div>
                 <div class="con">
                   <?php if($list['status'] == ORDER_REFUND_STATUS_APPLY_REFUND_RETURN){ ?><!-- 订单sku状态是申请退款需退货 -->
                  	<input type="submit" class="btn_com_blue" value="同意退货，发送退货地址" onclick="agreeReturn()">
                  	<a href="javascript:;" onclick="refuseReturn()" class="btn_comCancel">拒绝退款</a>
                  	<?php }elseif ($list['status'] == ORDER_REFUND_STATUS_APPLY_REFUND_NORETURN){?><!-- 订单sku状态是申请退款无需退货 -->
                  	<a href="<?php echo Yii::app()->createUrl('mCenter/ShopActivist/AgreeRefund',array('order_id'=>$order_id,'order_sku_id'=>$list['id'],'order_status'=>$order_status)); ?>" onclick="return confirm('确定同意退款吗');" class="btn_com_blue">同意退款</a>
                  	<a href="javascript:;" onclick="refuseReturn()" class="btn_comCancel">拒绝退款</a>
                  	<?php }else if($list['status']==ORDER_REFUND_STATUS_RETURN_ISSUED){?>
                        <a href="<?php echo Yii::app()->createUrl('mCenter/ShopActivist/AgreeTake',array('order_id'=>$order_id,'order_sku_id'=>$list['id'],'order_status'=>$order_status)); ?>" onclick="return confirm('确定同意收货吗');" class="btn_com_blue">同意收货</a>
                        <a href="<?php echo Yii::app()->createUrl('mCenter/ShopActivist/RefuseTake',array('order_id'=>$order_id,'order_sku_id'=>$list['id'],'order_status'=>$order_status)); ?>" onclick="return confirm('确定拒绝收货吗');" class="btn_comCancel">拒绝收货</a>
                    <?php }?>
                 </div>
                 <div class="border"></div>
                 <div class="filed">
                	<!--  <span class="label">您还可以：</span>
                    <span class="text blue">已收到退货，同意退款</span>-->
                </div>
            </div>
        </div><!--end orderWarp-->
        <div class="DR_record">
        	<h2>协商记录</h2>
            <?php foreach($list['refund'] as $k=>$v){?>

                <?php if($list['refund'][$k]['status']==ORDER_REFUND_STATUS_FINANCIAL_PLAY||$list['refund'][$k]['status']==ORDER_REFUND_STATUS_REFUND_SUCCESS
                            &&$list['refund'][$k]['status']==ORDER_REFUND_STATUS_REFUND_SUCCESS){?>
                    <dl>
                        <dt><strong>商家</strong><span class="time"><?php echo $list['refund'][$k]['refund_time'];?></span></dt>
                        <dd>
                            <p>同意退款给买家，本次维权结束</p>
                            <div class="filed">
                                <div class="label inlineBlock">退款金额：</div>
                                <div class="text inlineBlock"><?php echo $list['refund'][$k]['refund_money']?></div>
                            </div>
                        </dd>
                    </dl>
                <?php }?>

                <?php if(!empty($list['refund'][$k]['refuse_refund_remark'])){?>
                    <!--拒绝退款理由不为空-->
                    <dl>
                        <dt><strong>商家</strong><span class="time"><?php echo $list['refund'][$k]['create_time'];?></span></dt>
                        <dd>
                            <p>拒绝了本次退款申请,等待买家修改</p>
                            <div class="filed">
                                <div class="label inlineBlock">拒绝理由:</div>
                                <div class="text inlineBlock"><?php echo $list['refund'][$k]['refuse_refund_remark']?></div>
                            </div>
                        </dd>
                    </dl>
                <?php }?>

                <?php if($list['refund'][$k]['status']==ORDER_REFUND_STATUS_REFUSE_RECEIPT){?>
                    <!--拒绝收货-->
                    <dl>
                        <dt><strong>商家</strong><span class="time"><?php echo $list['refund'][$k]['create_time'];?></span></dt>
                        <dd>
                            <p>商家拒绝确认收货</p>
                            <div class="filed">
                                <div class="label inlineBlock">物流名称：</div>
                                <div class="text inlineBlock"><?php echo $GLOBALS['LOGISTICS_COMPANY'][$list['refund'][$k]['user_express']];?></div>
                            </div>
                            <div class="filed">
                                <div class="label inlineBlock">物流编号：</div>
                                <div class="text inlineBlock"><?php echo $list['refund'][$k]['user_express_no'];?></div>
                            </div>
                            <div class="filed">
                                <div class="label inlineBlock">退款说明：</div>
                                <div class="text inlineBlock"><?php echo $list['refund'][$k]['user_remark'];?></div>
                            </div>
                            <div class="filed">
                                <div class="label inlineBlock">联系电话：</div>
                                <div class="text inlineBlock"><?php echo $list['refund'][$k]['user_tel']?></div>
                            </div>
                        </dd>
                    </dl>
                <?php }?>
                <?php if($list['refund'][$k]['status']==ORDER_REFUND_STATUS_RETURN_ISSUED){
                    if($list['refund'][$k]['if_return']==IF_RETURN_YES){?>
                        <dl>
                            <dt><strong>买家</strong><span class="time"><?php echo $list['refund'][$k]['create_time']?></span></dt>
                            <dd>
                                <p>已退货，等待商家确认收货</p>
                                <div class="filed">
                                    <div class="label inlineBlock">物流名称：</div>
                                    <div class="text inlineBlock"><?php echo $GLOBALS['LOGISTICS_COMPANY'][$list['refund'][$k]['user_express']];?></div>
                                </div>
                                <div class="filed">
                                    <div class="label inlineBlock">物流编号：</div>
                                    <div class="text inlineBlock"><?php echo $list['refund'][$k]['user_express_no'];?></div>
                                </div>
                                <div class="filed">
                                    <div class="label inlineBlock">退款说明：</div>
                                    <div class="text inlineBlock"><?php echo $list['refund'][$k]['user_remark'];?></div>
                                </div>
                                <div class="filed">
                                    <div class="label inlineBlock">联系电话：</div>
                                    <div class="text inlineBlock"><?php echo $list['refund'][$k]['user_tel']?></div>
                                </div>
                            </dd>
                        </dl>
                    <?php }}?>
                <?php if(!empty($list['refund'][$k]['refund_address'])&&$list['refund'][$k]['status']==ORDER_REFUND_STATUS_AGREE_RETURN){?>
                    <dl>
                        <dt><strong>商家</strong><span class="time"><?php echo $list['refund'][$k]['create_time']?></span></dt>
                        <dd>
                            <p>已同意退款申请<?php if($list['refund'][$k]['if_return']==IF_RETURN_YES){echo '，等待买家退货';}?></p>
                            <?php if($list['refund'][$k]['if_return']==IF_RETURN_YES){?>
                                <div class="filed">
                                    <div class="label inlineBlock">退货地址：</div>
                                    <div class="text inlineBlock"><?php echo $list['refund'][$k]['refund_address']?></div>
                                </div>
                            <?php }?>
                        </dd>
                    </dl>
                <?php }?>

                <?php if(!empty($list['refund'][$k]['apply_refund_time'])&&($list['refund'][$k]['status']==ORDER_REFUND_STATUS_APPLY_REFUND_NORETURN||$list['refund'][$k]['status']==ORDER_REFUND_STATUS_APPLY_REFUND_RETURN)){?>
                    <dl>
                        <dt><strong>买家</strong><span class="time"><?php echo $list['refund'][$k]['apply_refund_time']?></span></dt>
                        <dd>
                            <p>发起了退款申请，等待商家处理</p>
                            <div class="filed">
                                <div class="label inlineBlock">退款原因：</div>
                                <div class="text inlineBlock"><?php echo $list['refund'][$k]['refund_remark'];?></div>
                            </div>
                            <div class="filed">
                                <div class="label inlineBlock">期望结果：</div>
                                <div class="text inlineBlock">我要退款<?php if($list['refund'][$k]['if_return']==IF_RETURN_YES){?>，并退货<?php }?></div>
                            </div>
                            <div class="filed">
                                <div class="label inlineBlock">退款金额：</div>
                                <div class="text inlineBlock"><?php echo $list['refund'][$k]['refund_money'];?></div>
                            </div>
                            <!--<div class="filed">
                                <div class="label inlineBlock">退款说明：</div>
                                <div class="text inlineBlock"><?php /*echo $list['refund'][$k]['user_remark'];*/?></div>
                            </div>-->
                            <div class="filed">
                                <div class="label inlineBlock">联系电话：</div>
                                <div class="text inlineBlock">
                                    <?php echo $list['refund'][$k]['refund_tel'];?>
                                    <!--<ul>
                                        <li><img src="../images/pro.png"></li>
                                        <li><img src="../images/pro.png"></li>
                                        <li><img src="../images/pro.png"></li>
                                    </ul>-->
                                </div>
                            </div>
                        </dd>
                    </dl>
                <?php }?>
            <?php }?>


        </div>
     </div>
</div>
<!--修改地址弹出框-->
<div class="popWrap" id="pop" style="width:655px; display:none">
	<div class="pop_con pAddress">
        <div class="title">
        	<span class="name">维权处理</span>
            <span class="cz"><a href="javascript:;" onclick="closeWin()">x</a></span>
        </div>
        <?php echo CHtml::beginForm(Yii::app()->createUrl('mCenter/shopActivist/addRefundAddress')); ?>
        <div class="pop_content">

            <div class="filed">
            	<span class="label">退款金额：</span>
                <span class="text"><em class="orange">¥<?php echo $list['refund'][0]['refund_money']; ?></em></span>
            </div>

            <div class="filed">
                <span class="label">退货地址：</span>
                <span class="text"><?php echo CHtml::textField("refund_address",'',array('style'=>'width:350px'));?></span>
            </div>

            <!--是否需要退货-->
            <input style="display: none" name="refund_status" value="<?php
                if($list['status'] == ORDER_REFUND_STATUS_APPLY_REFUND_NORETURN)
                {
                    echo ORDER_REFUND_STATUS_AGREE_NORETURN;
                }else if($list['status'] == ORDER_REFUND_STATUS_APPLY_REFUND_RETURN){
                    echo ORDER_REFUND_STATUS_AGREE_RETURN;
                }
            ?>">
            <!--是否需要退货-->
            <!--order_id-->
            <input style="display: none" name="refuse_orderid" value="<?php echo $order_id;?>">
            <!--order_id-->
            <!--skuid-->
            <input style="display: none" name="refund_skuid" value="<?php echo $list['id'];?>">
            <!--skuid-->

            <!--refund_record表id-->
            <input style="display: none" name="refund_refundid" value="<?php echo $list['refund'][0]['refund_id'];?>">
            <!--refund_record表id-->

            <!--退款金额-->
            <input style="display: none" name="refund_money" value="<?php echo $list['refund'][0]['refund_money'];?>">
            <!--退款金额-->
            <div class="filed">
            	<span class="label"></span>
                <span class="text">
                	<input type="submit" value="同意" class="btn_com_blue" style="margin-right:20px">
            		<input type="button" value="取消" class="btn_com_gray" id="cancle">
                </span>
            </div>
        </div>  
        <?php echo CHtml::endForm(); ?>
    </div>
    
</div>

<!--拒绝退款理由-->
<div class="popWrap" id="pop_refuse" style="width:450px; display:none">
    <div class="pop_con pAddress">
        <div class="title">
            <span class="name">拒绝退款</span>
            <span class="cz"><a href="javascript:;" onclick="closePop()">x</a></span>
        </div>
        <?php echo CHtml::beginForm(Yii::app()->createUrl('mCenter/shopActivist/RefuseRefund')); ?>
        <div class="pop_content">
            <!--<div class="filed">
            	<span class="label">处理方式：</span>
                <span class="text"><?php /*echo isset($GLOBALS['ORDER_SKU_HOPE'][$list['refund_hope']])?$GLOBALS['ORDER_SKU_HOPE'][$list['refund_hope']]:''; */?></span>
            </div>-->
            <div class="filed">
                <span class="label">拒绝理由：</span>
                <span class="text"><?php echo CHtml::textArea("refuse_refund_remark");?></span>
            </div>
            <!--是否需要退货-->
            <input style="display: none" name="refuse_status" value="<?php
            if($list['status'] == ORDER_REFUND_STATUS_APPLY_REFUND_NORETURN)
            {
                echo ORDER_REFUND_STATUS_AGREE_NORETURN;
            }else if($list['status'] == ORDER_REFUND_STATUS_APPLY_REFUND_RETURN){
                echo ORDER_REFUND_STATUS_AGREE_RETURN;
            }
            ?>">
            <!--是否需要退货-->
            <!--order_id-->
            <input style="display: none" name="refuse_orderid" value="<?php echo $order_id;?>">
            <!--order_id-->
            <!--skuid-->
            <input style="display: none" name="refuse_skuid" value="<?php echo $list['id'];?>">
            <!--skuid-->

            <!--refund_record表id-->
            <input style="display: none" name="refuse_refundid" value="<?php echo $list['refund'][0]['refund_id'];?>">
            <!--refund_record表id-->

            <!--退款金额-->
            <input style="display: none" name="refuse_money" value="<?php echo $list['refund'][0]['refund_money'];?>">
            <!--退款金额-->
            <div class="filed">
                <span class="label"></span>
                <span class="text">
                	<input type="submit" value="确认" class="btn_com_blue" style="margin-right:20px">
            		<input type="button" value="取消" onclick="closePop()" class="btn_com_gray">
                </span>
            </div>
        </div>
        <?php echo CHtml::endForm(); ?>
    </div>

</div>
<script type="text/javascript">
	
	$(document).ready(function (){
        var _w = $(window).width() //浏览器窗口宽度
        var _w = $(window).height() //浏览器窗口高度
        var _offsetW = $('#pop').width(); //获取弹出框的宽度
        var _offsetH = $('#pop').height(); //获取弹出框的高度
        var _offsetW = $('#pop_refuse').width(); //获取弹出框的宽度
        var _offsetH = $('#pop_refuse').height(); //获取弹出框的高度

        var _left = ($(window).width()-$('#pop').width())/2;
        var _top = ($(window).height()-$('#pop').height())/2;
        var _left = ($(window).width()-$('#pop_refuse').width())/2;
        var _top = ($(window).height()-$('#pop_refuse').height())/2;
        $('#pop').css({'left' : _left, 'top' : _top});
        $('#pop_refuse').css({'left' : _left, 'top' : _top});
	});	

	function agreeReturn()
	{
		$('#pop').show();
	}

    function refuseReturn()
    {
        $('#pop_refuse').show();
    }

    function closePop()
    {
        $('#pop_refuse').hide();
    }

	function closeWin()
	{
		$('#pop').hide();
	}
	$("#cancle").click(function(){
		$("#pop").hide();
		return false;
	});

	function save()
	{
		var flag = true;
		var refund_address = $('#refund_address').val();

		if(refund_address == ''){
			flag = false;
			$('#refund_address_error').html('退货地址必填');
		}else{
			$("#refund_address_error").html('');
		}

		if(flag){
			$('#addressForm').submit();
		}
	}
</script>
</body>