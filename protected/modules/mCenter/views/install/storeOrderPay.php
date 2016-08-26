<body>
    <div class="kkfm_r_inner">
        <div class="storeManage">
            <div class="top">
                <div class="name">
                    续费升级
                </div>
            </div>
            <div class="v-container">
            
                <div class="v-box mt20">
                    <div class="title">
                        <h3>订单信息</h3>
                    </div>
                    <div class="v-box-con">
                        <div class="meta">
                            <span>玩券版本：营销版</span>
                            <span>门店数量：<?php echo $store_num?></span>
                            <span>开通时间：<?php echo $payMode == STORE_ORDER_PAY_TYPE_MONTH?$time.'月':$time.'年';?></span>
                        </div>
                        <div class="pay-way">
                            <span>请选择支付方式：</span>
                            <span>                               
                                <input type="radio" name="pay_way" id="pay-a" checked value="1">
                                <label class="pay-a" for="pay-a"></label>
                            </span>
<!--                             <span> -->                              
<!--                                 <input type="radio" name="pay-way" id="pay-w"> -->
<!--                                  <label class="pay-w" for="pay-w"></label> -->
<!--                             </span> -->
                        </div>
                        <div class="pay-soon">
                            <span>
                            应付金额：<em class="v-fee">¥<?php echo number_format($total_fee,2)?></em>
                            <a href="javascript:;" class="wq-btn-primary">立即支付</a>
                            <input type="hidden" id="orderNo" value="<?php echo $order_no?>">
                            </span>
                        </div>
                    </div>
                </div>
                <div class="v-box v-box-fee">
                    <div class="title">
                        <h3>订单信息</h3>
                    </div>
                    <div class="wq-pop-con">
                        <p>费用说明：<?php if(strtotime(date('Y-m-d H:i:s'))<=strtotime(STORE_ACTIVITY_END_TIME) && strtotime(date('Y-m-d H:i:s')) >= strtotime(STORE_ACTIVITY_START_TIME)){?>单门店，活动期间可享前三年<em class="wq-orange"><?php echo number_format(STORE_ACTIVITY_YEAR_FEE,2)?></em>元/年，<em class="wq-orange"><?php echo number_format(STORE_ACTIVITY_MONTH_FEE,2)?></em>元/月(活动期间：<?php echo date('Y.m.d',strtotime(STORE_ACTIVITY_START_TIME))?> - <?php echo date('Y.m.d',strtotime(STORE_ACTIVITY_END_TIME))?>)，之后<?php }?><em class="wq-orange"><?php echo number_format(STORE_YEAR_FEE,2)?></em>元/年，<em class="wq-orange"><?php echo number_format(STORE_MONTH_FEE,2)?></em>元/月</p>
                        <p class="total-fee wq-blue">合计应收金额：<?php echo number_format($total_fee,2)?>元</p>
                        <div class="v-fee-table">
                            <ul class="thead">
                                <li>门店名称</li>
                                <li>开通时间</li>
                                <li>玩券版本</li>
                                <li>费用</li>
                            </ul>
                            <ul class="tbody">
                                <?php foreach ($detail as $k => $v){?>
                                <li>
                                    <ul class="tbody-item">
                                        <li><?php echo $v -> store_name?><?php if(!empty($v -> store_branch_name)){echo '-'.$v -> store_branch_name;}?></li>
                                        <li class="wq-blue"><?php echo $v -> pay_mode == STORE_ORDER_PAY_TYPE_MONTH?$v -> time.'月':$v -> time.'年';?></li>
                                        <li><?php echo $v -> edition?></li>
                                        <li class="wq-orange"><?php echo number_format($v -> fee,2)?><?php if($v -> month_num > 0){?><span class="discount">享<?php if($v -> month_num/12 >= 1){echo floor($v -> month_num/12).'年'.$v -> month_num%12;}else{echo $v -> month_num%12;}?>个月优惠</span><?php }?></li>
                                    </ul>
                                </li>
                                <?php }?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 支付弹窗 -->
        <div class="wq-pop" style="display: none">
            <div class="wq-pop-title">
                <h3>支付二维码</h3>
                <i class="wq-pop-close fr">&#10006;</i>
            </div>
            <div class="wq-pop-con wechat-pop-con">
                <i class="wepay-logo"></i>
                <p class="meta">交易金额：<?php echo $total_fee?>元</p>
                <div class="qrcode">
                    <img src="" alt="qrcode-image" id="qr-img">
                    <p>请打开"支付宝"，扫描二维码</p>
                </div>
            </div>
        </div>
    </div>
    <script>
    	$(".wq-btn-primary").click(function() {
        	var orderNo = $("#orderNo").val();
    		$.ajax({
                url: '<?php echo(Yii::app()->createUrl('mCenter/install/alipayQrcode'));?>',
                data: {order_no: orderNo},
                type: 'get',
                dataType: 'json',
                success: function (data) {
                    if(data.error == 'success') {
                        $(".wq-pop").show(0, function() {
                            $("#qr-img").attr('src', data.qrcode);
                            //启动轮询
                    		run();
                        });
                    }else {
    	                alert(data.errMsg);
    	                obj.attr('checked', false);
                    }
                }
            });
    	});
    	$(".fr").click(function() {
        	$(".wq-pop").hide(0, function() {
        		$("#qr-img").attr('src', '');
        	});
    	});

    	//轮询    
        function run(){        
            intervalid = setInterval("ctrlTime()", 5000);        
        }
        /*主函数要使用的函数，进行声明*/ 
        var clock=new clock();  
        /*指向计时器的指针*/  
        var timer;  
        function clocks()
        { 
            clearInterval(timer);
            /*主函数就在每1秒调用1次clock函数中的move方法即可*/ 
            timer=setInterval("clock.move()",1000); 
        }  

        function clock()
        {  
            /*s是clock()中的变量，非var那种全局变量，代表剩余秒数*/  
            this.s=10;  
            this.move=function()
            {  
                /*输出前先调用exchange函数进行秒到分秒的转换，因为exchange并非在主函数window.onload使用，因此不需要进行声明*/ 
                //document.getElementById("timer").innerHTML=exchange(this.s);  
                /*每被调用一次，剩余秒数就自减*/  
                this.s=this.s-1;  
                /*如果时间耗尽，那么，返回clock,重新倒计时*/ 
                if(this.s<0)
                {  
                    this.s=10;
                    return clocks();
                }  
            }  
        }  

        function exchange(time)
        {  
            /*javascript的除法是浮点除法，必须使用Math.floor取其整数部分*/  
            this.m=Math.floor(time/60); 
            /*存在取余运算*/
            this.s=(time%60);
            this.text=this.s;  
            /*传过来的形式参数time不要使用this，而其余在本函数使用的变量则必须使用this*/ 
            return this.text;  
        }  
        //轮询返回结果
        function ctrlTime(){
            var orderNo = $("#orderNo").val();
        	$.ajax({
                url: '<?php echo(Yii::app()->createUrl('mCenter/install/search'));?>',
                data: {order_no: orderNo},
                type: 'get',
                dataType: 'json',
                success: function (data) {
                    if(data.error == 'success') {
                    	clearInterval(intervalid);//结束查询轮询
                    	$(".wq-pop").hide(0, function() {
                    		$("#qr-img").attr('src', '');
                    		location.href = "<?php echo Yii::app()->createUrl('mCenter/install/storeOrderPaySuccess', array('order_no' => $order_no));?>";
                    	});
                    }else if(data.error == 'failure'){
                    	clearInterval(intervalid);//结束查询轮询
                        alert(data.errMsg);
                    }
                }
            });             
        }
    	
    </script>
</body>