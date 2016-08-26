<body>
    <div class="kkfm_r_inner">
        <div class="storeManage">
            <div class="top">
                <div class="name">
                    玩券版本
                </div>
            </div>
            <div class="v-container">
                <div class="v-pay-success">
                    <i class="icon-money"></i>
                    <h1>恭喜您，支付成功</h1>
                    <div class="meta">
                        <span>版本：<?php echo $edition_type == STORE_EDITION_MARKETING?'营销版':'';?></span>
                        <span>时间：<?php if($pay_type == STORE_ORDER_PAY_TYPE_MONTH){
                            echo $time.'月';
                        }elseif ($pay_type == STORE_ORDER_PAY_TYPE_YEAR){
                            echo $time.'年';
                        }?></span>
                        <span>门店数：<?php echo $store_num?></span>
                    </div>
                    <button type="button" class="wq-btn-primary" onclick="fixmenu2('<?php echo Yii::app()->createUrl('mCenter/install/storeOrderList')?>')">确定</button>
                </div>
            </div>
        </div>
    </div>
    <script>
	    function fixmenu2(url) {
	        var dt = $(".backstage dt:eq(4)", window.parent.document);
			
	    	onLeft(url, dt.next().find('a:eq(2)'));
	    }
    </script>
</body>