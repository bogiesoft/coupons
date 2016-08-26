<script type="text/javascript" src="<?php echo SYT_STATIC_JS?>artDialog/jquery.artDialog.js?skin=simple"></script>
<script type="text/javascript" src="<?php echo SYT_STATIC_JS?>artDialog/plugins/iframeTools.js"></script>

<div class="kkfm_r_inner">
   <div class="top1">
    	<div class="status-nav">
            <ul>
                <li class="<?php echo 'msg' == $type ? 'bg' : '';?>"><a href="<?php echo Yii::app()->createUrl('mCenter/wechat/msgReply')?>">消息自动回复</a></li>
                <li class="<?php echo 'keyword' == $type ? 'bg' : '';?>"><a href="<?php echo Yii::app()->createUrl('mCenter/wechat/autoReply')?>">关键词自动回复</a></li>
            </ul>
    	</div>
    </div>
    <div class="reply">
    	<input type="button" class="btn_com_blue" value="创建关键词" onclick="show(0)">
    	<?php if (!empty($list)) { ?>
    		<?php foreach ( $list as $key => $value ) { ?>
		    	<div class="item">
		        	<div class="reply-left">
		            	<div class="filed">
		                	<span class="label">规则名</span>
		                    <span class="text"><?php echo $value['rule_name']?></span>
		                </div>
		                <div class="filed">
		                	<span class="label">关键词</span>
		                    <span class="text">
		                    	<span class="text attr_name">
		                    	<?php foreach ($value['key_word'] as $k => $v) { ?>
		                    		<?php $arr_key_word = explode(',', $v);
		                    			foreach ($arr_key_word as $x => $y){?>
		                    			<?php if(!empty($y)){?>
		                    			<span><?php echo $y?></span>
		                    			<?php }?>
		                    		<?php }?>
		                    	<?php } ?> 
		                    	</span>
		                    </span>
		                   
		                </div>
		                <div class="filed">
		                	<span class="label">回复内容</span>
		                    <span class="text"><?php echo $value['content']?></span>
		                </div>
		            </div>
		            <div class="reply-right" style="display:none">
		            	<a href="#" class="edit" onclick="show('<?php echo $value['id']?>')"></a>
		                <a href="<?php echo Yii::app()->createUrl('mCenter/wechat/delAutoReply', array('reply_id'=>$value['id'], 'type'=>REPLY_TYPE_KEYWORD))?>" class="del" onclick="return confirm('确认删除吗？');"></a>
		                <?php if (Yii::app()->user->hasFlash('error')) { ?>
				    		<script>alert('<?php echo Yii::app()->user->getFlash('error')?>')</script>
						<?php }?> 
		            </div>
		        </div>
		    <?php } ?>
        <?php } ?>
        <script>
        	$(".item").hover(function(){
				n=$(".item").index(this)
				$(".item .reply-right").eq(n).show()
			},function(){
				$(".item .reply-right").eq(n).hide()
			})
		 </script>
    </div>
</div> 
<?php echo Yii::app()->user->getFlash('submit');?>




<script type="text/javascript">

	
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
// 		art.dialog.open(
//             	,
//             	 {
//                   	 title: '',
//                   	 lock: true,
//                   	 drag:true, 
//                   	 id: 'dialog',
//                   	 background: '#fff',
//                   	 cancel:false,
// //                   	 width:700,
// 	                 resize: false,
// 	                 close: function() {
// 		                 //关闭前执行
// 	                 }
//                  }
//         );

            	var index = layer.open({
          		  type: 2,
          		  title: false,
          		  shadeClose: true,
          		  shade: 0,
          		  area: ['649px', '390px'],
          		  content: '<?php echo Yii::app()->createUrl('mCenter/wechat/saveReply');?>'+'?reply_id='+reply_id //iframe的url
          		}); 
            	layer.iframeAuto(index);
	}
	function hide(){
		$('#pop').hide();
		}
	
</script>