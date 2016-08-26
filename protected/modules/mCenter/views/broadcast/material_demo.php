
	<?php if (!empty($material)) {?>
		<?php foreach ($material as $k => $v) { ?>
			<?php if ($k == 0) { ?>
				<p><?php echo $v['title'] ?></p>
                <div class="cover_img">
					<img src="<?php echo (IMG_GJ_LIST.$v['cover_img']) ?>">
                	<div class="material_title_small">
              			<a href=""><?php echo $v['abstract']?></a>
                   	</div>
               	</div>
            	<div class="post_list">
			<?php }else{ ?>
	        		<div class="post_list_item">
						<div class="post_list_title">
				        	<a href=""><?php echo $v['title']?></a>
		                </div>
		                <div class="post_list-cover">
		                	<img src="<?php echo (IMG_GJ_LIST.$v['cover_img']) ?>">
				        </div>
				    </div>
               </div>
           <?php } ?>
		<?php } ?>
               </div>
	<?php } ?>
<!--  以上是选择后的模块 把material_pop_box_item换成material_pop_box_item_es-->
<a href="javascript:void(0);" class="delete_post" id="del_material">删除</a>
