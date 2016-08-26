<div class="kkfm_r_inner">
<div class="top">
        <div class="name">红包详情</div>
    </div>
    <div class="contant" style="background:#fafafa;min-height:300px">
         <div class="filed">
               <span class="label">所属商户:</span>
                <?php
                      if(!empty($list['merchant_id'])){
                      $merchant = Merchant::model()->findByPk($list['merchant_id']); 
                      echo $merchant -> name;
                      }else{echo '';}
                ?>
           </div>
            <div class="filed">
                <span class="label">红包名:</span>
                <span class="text"><?php echo $list['name']; ?></span>
             </div>   
            
              <div class="filed">
                <span class="label">发放开始时间:</span>
                <span class="text"><?php echo $list['start_time']; ?></span>
              </div>
   
              <div class="filed">
                <span class="label">发放结束时间:</span>
               <span class="text"> <?php echo $list['end_time']; ?></span>
              </div>
               <div class="filed">
                <span class="label">券面额固定值:</span>
               <span class="text"> <?php echo $list['fixed_value']; ?></span>
              </div>
              <div class="filed">
                <span class="label">券面额自定义值:</span>
               <span class="text"> <?php echo $list['userdefined_value']; ?></span>
              </div>
              <div class="filed">
                <span class="label">有效期或有效天数:</span>
               <span class="text"> 
               <?php  if(!empty($list['validtime_fixed_value']))
                         {echo $list['validtime_fixed_value'].'天';} 
                         else{
                               echo $list['validtime_end'];
                             }
               ?>
               </span>
              </div>
              <div class="filed">
                <span class="label">发放数量:</span>
               <span class="text"> <?php echo $list['num']; ?></span>
              </div>
               <div class="filed">
                <span class="label">每个用户领取数量:</span>
               <span class="text"> <?php echo $list['receive_num']; ?></span>
              </div>
              <div class="filed">
                <span class="label">是否能与会员折扣同用:</span>
               <span class="text"> <?php echo $GLOBALS['IF_WITH_USERDISCOUNT'][$list['if_with_userdiscount']]; ?></span>
              </div>
              <div class="filed">
                <span class="label">是否能与优惠券同用:</span>
               <span class="text"> <?php echo $GLOBALS['IF_WITH_COUPONS'][$list['if_with_coupons']]; ?></span>
              </div>
              <div class="filed">
                <span class="label">单个订单使用数量:</span>
               <span class="text"> <?php echo $list['order_use_num']; ?></span>
              </div>
               <div class="filed">
                <span class="label">门店限制:</span>
               <span class="text"> 
                <?php
                        echo $list['use_store_name'];
                ?>
               </span>
              </div>
               <div class="filed">
                <span class="label">退款处理:</span>
               <span class="text"> <?php echo isset($list['refund_deal'])?$GLOBALS['REFUND_DEAL'][$list['refund_deal']]:''; ?></span>
              </div>
              <div class="filed">
                <span class="label">创建时间:</span>
               <span class="text"> <?php echo $list['create_time']; ?></span>
              </div>
              <div class="filed">
                <span class="label"></span>
               <span class="text"> <a href="<?php echo Yii::app()->createurl('mCenter/coupons/redEnvelopeList'); ?>" style="background:#f63;color:#fff;padding:8px 20px">返回</a>     </span>
              </div>
                    
    </div>
</div>

