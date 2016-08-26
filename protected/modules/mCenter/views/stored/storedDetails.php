<div class="kkfm_r_inner">
<div class="top">
        <div class="name">储值详情</div>
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
                <span class="label">预存金额:</span>
                <span class="text"><?php echo $list['stored_money']; ?></span>
             </div>   
             
             <div class="filed">
                <span class="label">得到金额:</span>
                <span class="text"><?php echo $list['get_money'];?></span>
              </div>
         
              <div class="filed">
                <span class="label">活动开始时间:</span>
                <span class="text"><?php echo $list['start_time']; ?></span>
              </div>
   
              <div class="filed">
                <span class="label">活动结束时间:</span>
               <span class="text"> <?php echo $list['end_time']; ?></span>
              </div>
              <div class="filed">
                <span class="label"></span>
               <span class="text"> <a href="<?php echo Yii::app()->createurl('mCenter/stored/storedList'); ?>" style="background:#f63;color:#fff;padding:8px 20px">返回</a>     </span>
              </div>
                    
    </div>
</div>
