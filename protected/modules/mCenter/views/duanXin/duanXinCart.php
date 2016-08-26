<script type="text/javascript">
	$(document).ready(main_obj.list_init);
</script>

<div class="kkfm_r_inner">
    <div class="data msgWrap">
    	<div class="name">
        	短信充值<span>您当前的短信剩余量为<strong class="red"><?php echo isset($num) ? $num : ''?></strong>条</span>
        </div>
        <div class="msg_con">
        	<h3>请选择短信套餐：<span>套餐没有时间限制，用完为止。</span></h3>
            <ul class="clearfix">
            <?php foreach ($GLOBALS['__DXTC'] as $k => $v){?>
            	<li>
                	<a href="<?php echo $this->createUrl('duanXinPay',array('combo'=>$v['sub']['num']))?>">
                    	<div class="pic"><?php echo $v['text']?></div>
                        <div class="text">
                        	<strong>￥<?php echo $v['sub']['num']*$v['sub']['unit_price']?></strong>
                            <span>单价<?php echo $v['sub']['unit_price']?>元/条</span>
                        </div>
                    </a>
                </li>
                <?php }?>
            </ul>
        </div>
    </div>
</div> 


