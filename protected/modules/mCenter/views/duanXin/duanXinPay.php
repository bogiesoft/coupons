<script type="text/javascript">
	$(document).ready(main_obj.list_init);
</script>
<div class="kkfm_r_inner">
    <div class="top">
        <div class="name">短信购买</div>
        <div class="cz"><a href="javascript:history.back();" class="btn_com_blue">返回</a></div>
    </div>
    <?php $form=$this->beginWidget('CActiveForm')?>
	<div class="contant">
      	  <div class="recharge">
        	<div class="buy-con">
                    <div class="filed">
                        <span class="label">商品名称：</span>
                        <span class="text">
                            <?php  
                                echo $_GET['combo'] == $GLOBALS['__DXTC']['1']['sub']['num'] ? $GLOBALS['__DXTC']['1']['text'] : '';
                                echo $_GET['combo'] == $GLOBALS['__DXTC']['2']['sub']['num'] ? $GLOBALS['__DXTC']['2']['text'] : '';
                                echo $_GET['combo'] == $GLOBALS['__DXTC']['3']['sub']['num'] ? $GLOBALS['__DXTC']['3']['text'] : '';
                            ?>
                        </span>
                    </div>
                    <div class="filed">
                        <span class="label">单价：</span>
                        <span class="text" id="price_item_1"><?php echo $_GET['combo']*$price;?>元/份</span>
                    </div>
                    <div class="filed">
                        <span class="label">数量：</span>
                        <span class="text">
                            <a class="reduce" onClick="setAmount.reduce('#qty_item_1')" href="javascript:void(0)">-</a>
                                <input type="text" name="qty_item_1" value="1" id="qty_item_1" onKeyUp="setAmount.modify('#qty_item_1')" class="txt" placeholder="购买的数量" style="width:100px"/>
                            <a class="add" onClick="setAmount.add('#qty_item_1')" href="javascript:void(0)">+</a>                        
                        </span>
                    </div>
                    <div class="filed">
                        <span class="label">总计：</span>
                        <span class="text"><span class="total-font" id="total_item_1"></span></span>
                    </div>           
                    <div class="filed">
                        <span class="label"></span>
                        <span class="text"><?php echo CHtml::submitButton('去结算',array('class'=>'btn_com_blue'))?></span>
                    </div>                    
              </div>
           </div>
        </div>
     </div>
    <?php $form=$this->endWidget()?>
</div> 

