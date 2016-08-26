<script type="text/javascript" src="<?php echo SYT_STATIC_JS?>artDialog/jquery.artDialog.js?skin=simple"></script>
<script type="text/javascript" src="<?php echo SYT_STATIC_JS?>artDialog/plugins/iframeTools.js"></script>

<body>
<script type="text/javascript">
	$(document).ready(main_obj.list_init);
</script>

<div class="kkfm_r_inner">
    <div class="top">
        <div class="name">群发广播<span>您每周可以向所有用户群组发送1条广播，向分组用户发送5条广播，但用户每周仅能收到您发给ta的第一条广播。</span></div>
    </div>
    <div class="reply broadcastGroup">
        <div class="status-nav clearfix">
            <ul>
                <li class="<?php echo 'broadcast' == $type ? 'bg' : '';?>"><a href="<?php echo Yii::app()->createUrl('mCenter/fuwu/broadcastGroup')?>">新建广播</a></li>
                <li class="<?php echo 'record' == $type ? 'bg' : '';?>"><a href="<?php echo Yii::app()->createUrl('mCenter/fuwu/broadcastRecord')?>">已发送</a></li>
            </ul>
        </div> 
        
        <div class="bcg_bg">
			<?php echo CHtml::beginForm();?>
            <div class="filed clearfix">
            	<div class="label">群发对象</div>
                <div class="text">
                	<select name="Reply[group]">
                        <?php foreach ($group_list as $key => $value ) {?>
		            			<?php if ($key == 0) { ?>
		            				<?php echo '<option value="'.$key.'" selected=selected>'.$value.'</option>'?>
		            			<?php }else{ ?>
		            				<?php echo '<option value="'.$key.'">'.$value.'</option>'?>
		            			<?php } ?>
		            		<?php } ?>
                    </select>
                </div>
            </div>
            
            <div class="nr">
				<div class="top">
					<ul>
						<span class="ico1<?php echo empty($model['material_id']) ? ' bg1' : '' ?>"><a href="#" onclick="textmsg()">文字消息</a></span>
						<span class="ico2<?php echo !empty($model['material_id']) ? ' bg2' : '' ?>"><a href="#" onclick="material()">图文消息</a></span>
					</ul>
				</div>
				<div class="nr-con">
					<?php if(!empty($model['content']) || empty($model['material_id'])) { ?>
						<div id="content" style="display: show">
					<?php }else {?>
						<div id="content" style="display: none">
					<?php }?>
					<?php echo CHtml::textArea('Reply[content]', $model['content'], array())?>
						</div>
					
					<?php if(!empty($model['material_id'])) { ?>
						<div id="material" style="display: show">
					<?php }else {?>
						<div id="material" style="display: none">
					<?php }?>
						<?php echo CHtml::dropDownList('Reply[material_id]', $model['material_id'], $material, array()) ?>
						</div>
					<font color="red" id="empty_error_msg"><?php echo Yii::app()->user->getFlash('empty')?></font>
				</div>
			
				<?php if (Yii::app()->user->hasFlash('error')) { ?>
	    		<script>setTimeout("alert('<?php echo Yii::app()->user->getFlash('error')?>')", 1)</script>
				<?php }?>
			
				<?php if (Yii::app()->user->hasFlash('success')) { ?>
	    		<script>setTimeout("alert('<?php echo Yii::app()->user->getFlash('success')?>')",5)</script>
				<?php }?>
        	</div>
        	<div class="btn">
				<?php echo CHtml::submitButton('群发',array('class'=>'btn_com_blue', 'style'=>"margin-right:30px"));?>
			</div>
			<?php echo CHtml::endForm();?>
        </div>
   	</div>
</div>

<?php echo Yii::app()->user->getFlash('submit');?>

<script type="text/javascript">

	//文字 图文 切换
	function textmsg(){
	$("#content").show();
	$("#material").hide();
	$(".ico1").attr('class', 'ico1 bg1');
	$(".ico2").attr('class', 'ico2');
	$("#Reply_material_id").val('0');
	}
	
	function material(){
	$("#content").hide();
	$("#material").show();
	$(".ico1").attr('class', 'ico1');
	$(".ico2").attr('class', 'ico2 bg2');
	$("#Reply_content").val('');
	}
	
	$(document).ready(function (){
		var _w = $(window).width() //浏览器窗口宽度
		var _w = $(window).height() //浏览器窗口高度
		var _offsetW = $('#pop').width(); //获取弹出框的宽度
		var _offsetH = $('#pop').height(); //获取弹出框的高度
		
		var _left = ($(window).width()-$('#pop').width())/2; 
		var _top = ($(window).height()-$('#pop').height())/2; 
				
		$('#pop').css({'left' : _left, 'top' : 90});
		
	})
	
	function show(reply_id){
		art.dialog.open(
            	'<?php echo Yii::app()->createUrl('mCenter/wechat/saveReply');?>'+'?reply_id='+reply_id,
            	 {
                  	 title: '',
                  	 lock: true,
                  	 drag:true, 
                  	 id: 'dialog',
                  	 background: '#fff',
                  	 cancel:false,
                  	 width:635,
	                 resize: false,
	                 close: function() {
		                 //关闭前执行
	                 }
                 }
        );
	}
	function hide(){
		$('#pop').hide();
		}
	
</script>

</body>
