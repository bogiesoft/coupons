<script type="text/javascript">
	$(document).ready(main_obj.list_init);
</script>

<div class="kkfm_r_inner">	
    <div class="top">
        <div class="name">短信充值</div>        
    </div>
	<div class="contant">
     	<div class="status-nav">
           
    	</div>
      	<div class="recharge">
            <table width="100%" cellspacing="0" cellpadding="0">
                <tr class="order-title" >
                  <td >充值套餐</td>
                  <td>充值数量</td>
                  <td>金额</td>
                  <td>充值时间</td>
                </tr>
                <?php if (!empty($list)) {
                  foreach ($list as $k => $v) { ?>
                    <?php if(!empty($v['pay_time'])) { ?>
                      <tr <?php echo $k%2==1 ? "class='bg'" : ''?> style="border-bottom:1px solid #dedede">
                          <td  valign="middle"><span class="fw"><?php echo $v['product_name']?>条套餐</span></td>
                          <td  valign="middle"><?php echo $v['message_num']/$v['product_name']?></td>
                          <td  valign="middle"><?php echo $v['pay_money']?></td>
                          <td  valign="middle"><?php echo $v['pay_time']?></td>                        
                      </tr>
                  <?php } } 
              }?>
             <!-- 分页开始 -->
            	
                	<tr style="border:none">
                	<td style="border:none;text-align:right" colspan="4">
                    	<?php $this -> widget('CLinkPager',array(
                    			'pages'=>$pages,
                                'header'=>'共&nbsp;<span class="yellow">'.$pages -> getItemCount().'</span>&nbsp;条&nbsp;',
                                'prevPageLabel' => '上一页',
                                'nextPageLabel'=>'下一页',
                                'maxButtonCount'=>8
                    	));?>
                    	</td>
                   	</tr>
               	
          	<!-- 分页结束 -->
            </table>
        </div>
     </div>
</div> 