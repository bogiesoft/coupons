<div class="backstage-l">
        	<dl>
                    <dt><a href="#">商城管理</a></dt> 
                    <dd style="display: block">
                        <?php if(Yii::app()->session['merchant_id'] == TIANSHI_SHOP_API) { ?>
                        <a href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/DshopMallHome/ShopMallIndex') ?>',this)" class="cur">商城管理</a>
                    	<a href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/DshopProduct/ProductList',array('pro_status'=>SHOP_PRODUCT_STATUS_UP)) ?>',this)">商品管理</a>
                    	<a href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/DshopOrder/orderList') ?>',this)">订单管理</a>
                        <?php } else { ?>
                    	<a href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/shopMallHome/ShopMallIndex') ?>',this)" class="cur">商城管理</a>
                    	<a href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/shopProduct/ProductList',array('pro_status'=>SHOP_PRODUCT_STATUS_UP)) ?>',this)">商品管理</a>
                    	<a href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/shopOrder/orderList') ?>',this)">订单管理</a>                        
                        <?php } ?>
                    </dd>  
                    <?php if(Yii::app()->session['merchant_id'] == TIANSHI_SHOP_API) { ?>
                    <dt><a href="#">活动管理</a></dt> 
                    <dd>
                        <a href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/MallActivity/addMallActivity') ?>',this)">添加活动</a>
                        <a href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/MallActivity/MallActivity') ?>',this)">活动列表</a>
                    </dd>
                    <?php }?>
                    
             </dl>
</div>
