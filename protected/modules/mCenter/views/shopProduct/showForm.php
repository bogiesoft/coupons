<table cellspacing="0" cellpadding="0" width="100%" class="typeList">
	<tr class="order-title">
    	<td align="center" nowrap="nowrap">规格</td>
   		<td align="center" nowrap="nowrap">价格</td>
   		<td align="center" nowrap="nowrap">原价（选填）</td>
    	<td align="center" nowrap="nowrap">库存</td>
  		<td align="center" nowrap="nowrap">商品条码</td>
	</tr>      
	<?php if (!empty($list)) { ?>   
		<?php foreach ($list as $key => $value) { ?>   
			<tr class="pro_sku">
				<td align="center"><?php echo $value?></td>
				<input type="hidden" name="Product[pro][<?php echo $key?>][att]" value="<?php echo $value?>">
				<td align="center"><input oninput="findmin()" onpropertychange="findmin()" type="text" class="txt" style="width:50px" name="Product[pro][<?php echo $key?>][sku_new_prize]"></td>
				<td align="center"><input oninput="check()" onpropertychange="check()" type="text" class="txt" style="width:50px" name="Product[pro][<?php echo $key?>][sku_old_prize]"></td>
				<td align="center"><input oninput="amount()" onpropertychange="amount()" type="text" class="txt" style="width:50px" name="Product[pro][<?php echo $key?>][sku_number]"></td>
				<td align="center"><input type="text" class="txt" style="width:50px" name="Product[pro][<?php echo $key?>][sku_code]"></td>
			</tr>
		<?php } ?>
	<?php } ?>
	<tr>
		<td colspan="6" class="line">
			<span id="setMoreName" style="display : inline">批量设置
			<a href="##" id="prize">价格</a>
			<a href="##" id="cost">原价</a>
			<a href="##" id="stock">库存</a>
			</span>
			<span id="setMoreVar" style="display : none">批量设置
				<input type="text" class="txt" name="Set[more_val]" placeholder="1" style="width:68px">
				<input type="hidden" name="Set[more_type]" val="">
		        <a href="##" id="setMore_sub">保存</a>
		        <a href="##" id="setMore_cel">取消</a>
			</span>
		</td>
	</tr>             
</table>