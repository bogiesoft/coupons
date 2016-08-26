<script type="text/javascript" src="<?php echo SYT_STATIC_JS?>artDialog/jquery.artDialog.js?skin=simple"></script>
<script type="text/javascript" src="<?php echo SYT_STATIC_JS?>artDialog/plugins/iframeTools.js"></script>
	<div class="popWrap material_pop_content">
        <div class="material_pop_top">
            <span>选择素材</span>
<!--             <div class="close_btn"></div> -->
        </div>
        <div class="pop_content">
            <div class="material_pop_search">
                    <input type="text" placeholder="标题" class="text_group" id="search_title" value="<?php echo $title?>">
                    <input type="button" class="icon_search" id="search">
                    <div class="btn_l level_r">
  	                    <a href="<?php echo Yii::app()->createUrl('mCenter/fuwu/materialList')?>" target="_blank">
	                    	<input type="button" value="+ 新建图文信息" class="btn_com_blue">
	                    </a>
                    </div>
            </div>
            <div class="material_pop_box">
                <div class="material_pop_box_inner clearfix" id="material_pop_box_inner">
                
                	<?php if ( !empty($model) ) { ?>
                		<?php foreach ($model as $key => $value) {?>
                    		<div class="material_pop_box_item" value="<?php echo $key?>">
                    			<?php foreach ($value as $k => $v) { ?>
                    				<?php if (!$k) { ?>
                    					<p><?php echo $v['title']?></p>
                    					<div class="cover_img">
				                            <img src="<?php echo (IMG_GJ_LIST.$v['cover_img']) ?>">
				                            <div class="material_title_small">
				                                <a href=""><?php echo $v['abstract']?></a>
				                            </div>
				                        </div>
                    				<?php }else{ ?>
                        				<div class="post_list">
	                    					<div class="post_list_item">
				                                <div class="post_list_title">
				                                    <a href=""><?php echo $v['title']?></a>
				                                </div>
				                                <div class="post_list-cover">
				                                    <img src="<?php echo (IMG_GJ_LIST.$v['cover_img']) ?>">
				                                </div>
				                            </div>
                    					</div>
                    				<?php }?>
                    			<?php } ?>
                    		</div>
                		<?php } ?>
                	<?php }else{ ?>
                		请添加图文信息
                	<?php } ?>
                </div>
            </div>
            <div class="material_pop_footer">
                <div class="btn level_r">
                    <input type="submit" class="btn_com_blue" value="确定" onclick="submitWin()">
            		<input type="button" value="取消" class="btn_com_gray" onclick="closeWin()">
                </div>
            </div>
        </div>
    </div>
    
<script type="text/javascript">

$(function(){
	/*选择素材时的瀑布流排列*/
		$(".material_pop_box_inner").masonry({});
});

	function closeWin()
	{
		//利用共享数据来设置选中素材
		art.dialog.data('mid', 0);
		art.dialog.close();
	}
	
	function submitWin() {
		var mid = $(".icon_card_selected").parent().attr('value');
		if(typeof(mid) == "undefined") {
			alert('请选择素材!');
			return false;
		}
		//利用共享数据来设置选中素材
		art.dialog.data('mid', mid);
		art.dialog.close();
	}

	$('#search').click(function(e){
        var search_title=$('#search_title').val();
        var url='<?php echo Yii::app()->createUrl('mCenter/broadcast/materialDialog')?>?search_title='+search_title;
        window.location=url;
    });

</script>