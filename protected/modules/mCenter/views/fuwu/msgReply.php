<script type="text/javascript" src="<?php echo SYT_STATIC_JS?>artDialog/jquery.artDialog.js?skin=simple"></script>
<script type="text/javascript" src="<?php echo SYT_STATIC_JS?>artDialog/plugins/iframeTools.js"></script>

<div class="kkfm_r_inner">
   	<div class="top1">
    	<div class="status-nav">
            <ul>
                <li class="<?php echo 'msg' == $type ? 'bg' : '';?>"><a href="<?php echo Yii::app()->createUrl('mCenter/fuwu/msgReply')?>">消息自动回复</a></li>
                <li class="<?php echo 'keyword' == $type ? 'bg' : '';?>"><a href="<?php echo Yii::app()->createUrl('mCenter/fuwu/autoReply')?>">关键词自动回复</a></li>
            </ul>
    	</div>
    </div>
    <div class="reply">
    	<div class="nr">
			<?php echo CHtml::beginForm();?>
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
			<?php echo CHtml::submitButton('确定',array('class'=>'btn_com_blue', 'style'=>"margin-right:30px"));?>
			<input class="btn_com_gray" style="margin-right:30px" type="button" value="删除" onclick="isdel()">
  
  			<!--<a href="/mCenter/fuwu/delAutoReply.html?reply_id=45&amp;type=1" class="btn_com_gray" onclick="return confirm('确认删除吗？');">删除</a>-->
			
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
	$(".ico1").attr('class', 'ico1 bg1');
	$(".ico2").attr('class', 'ico2');
	$("#material").hide();
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
            	'<?php echo Yii::app()->createUrl('mCenter/fuwu/saveReply');?>'+'?reply_id='+reply_id,
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
	function isdel()
	{
		if (confirm('确定删除吗？')) {
			window.self.location.href="<?php echo Yii::app()->createUrl('mCenter/fuwu/delAutoReply', array('reply_id'=>$model['id'], 'type'=>REPLY_TYPE_MSG))?>";
		}
		else
		{
			return false;
		}
	}
</script>