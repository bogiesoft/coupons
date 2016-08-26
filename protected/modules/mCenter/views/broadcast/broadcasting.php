

<body>
	<script type="text/javascript" src="<?php echo SYT_STATIC_JS?>artDialog/jquery.artDialog.js?skin=simple"></script>
	<script type="text/javascript" src="<?php echo SYT_STATIC_JS?>artDialog/plugins/iframeTools.js"></script>
    <div class="kkfm_r_inner">
        <div class="storeManage">
            <div class="top">
                <div class="name">
                    群发广播
                    <?php 
                        $authorizer_info = json_decode($merchant->wechat_thirdparty_authorizer_info);
                        
                        if (($merchant->wechat_thirdparty_authorizer_if_auth == 2 && $authorizer_info->service_type_info->id == 2) || ($merchant->wechat_thirdparty_authorizer_if_auth != 2 && $merchant->wechat_type == 4))
                        {
                            echo '<span>您每天可调用100次，但用户每月只能接收4条群发消息，多于4条的群发将对该用户发送失败（包括公众平台，接口）</span>';
                        }
                        elseif (($merchant->wechat_thirdparty_authorizer_if_auth == 2 && $authorizer_info->service_type_info->id != 2) || ($merchant->wechat_thirdparty_authorizer_if_auth != 2 && $merchant->wechat_type == 2)) 
                        {
                            echo '<span>您每天可成功调用1次，此次群发可选择发送给全部用户或某个分组；</span>';
                        }
                        else 
                        {
                            echo '<span style="color:red;">您的账号貌似存在问题，请联系管理员！</span>';
                        }
                    ?>
                </div>
            </div>
            <div class="bus-contant">
	            <?php echo CHtml::beginForm();?>
	                <div class="broadcast">
	                    <div class="broadcast_item">
	                        <span>群发对象</span>
	                        <select class="iselect" name="broadcast[class]" id="broadcast_class" onchange="countNum(this.value)">
		                        <?php foreach ($group_list as $key => $value ) {?>
			            			<?php if ($key == 0) { ?>
			            				<?php echo '<option value="'.$key.'" selected=selected>'.$value.'</option>'?>
			            			<?php }else{ ?>
			            				<?php echo '<option value="'.$key.'">'.$value.'</option>'?>
			            			<?php } ?>
			            		<?php } ?>
	                        </select>
	                    </div>
	                    <div class="broadcast_item">
	                        <span>发送对象</span>
	                        <?php echo CHtml::checkBox('broadcast[wechat_user]',isset($_POST['broadcast']['wechat_user'])?$_POST['broadcast']['wechat_user']:'',array('id'=>'broadcast_wechat_user')); ?>
	                        <label for="wechat_user">微信用户<em><span id="wechat_user"><?php echo $count_arr['wechat']?>人</span></em></label>
	                        <?php echo CHtml::hiddenField('broadcast[wechat_user_num]', $count_arr['wechat'])?>
	                        
	                        <?php echo CHtml::checkBox('broadcast[ali_user]',isset($_POST['broadcast']['ali_user'])?$_POST['broadcast']['ali_user']:'',array('id'=>'alipay_user')); ?>
	                        <label for="alipay_user">支付宝用户<em><span id="ali_user"><?php echo $count_arr['ali']?>人</span></em></label>
	                    </div>
	                    <div class="broadcast_item">
	                        <span>重复用户</span>
		                    <input type="radio" name="broadcast[repeat]" id="only_wechat" value="1" <?php if(empty($_POST['broadcast']['repeat']) || $_POST['broadcast']['repeat'] == 1) { ?> checked="checked" <?php }?>>
	                        <label for="only_wechat">仅微信</label>
	                        <input type="radio" name="broadcast[repeat]" id="only_alipay" value="2" <?php if(empty($_POST['broadcast']['repeat']) || $_POST['broadcast']['repeat'] == 2) { ?> checked="checked" <?php }?>>
	                        <label for="only_alipay">仅支付宝</label>
	                        <input type="radio" name="broadcast[repeat]" id="sync_post" value="3" <?php if(empty($_POST['broadcast']['repeat']) || $_POST['broadcast']['repeat'] == 3) { ?> checked="checked" <?php }?>>
	                        <label for="sync_post">同时发送</label>
	                        <label>其中重复人数为<em><span id="togher_user"><?php echo $count_arr['togher']?>人</span></em></label>
	                    </div>
	                    <?php echo CHtml::hiddenField('broadcast[material_id]', isset($_POST['broadcast']['material_id'])?$_POST['broadcast']['material_id']:'')?>
	                    <div class="broadcast_item clearfix">
	                        <span class="last_title">消息</span>
	                        <div class="broadcast_box">
	                            <div class="broadcast_box_top">
	                                <em class="icon1"><a href="javascript:;">图文消息</a></em>
	                                <em class="icon2"><a href="javascript:;">文字</a></em>
	                            </div>
	                            <?php echo CHtml::hiddenField('broadcast[type]', isset($_POST['broadcast']['type'])?$_POST['broadcast']['type']:'2')?>
	                    		<div class="broadcast_box_con clearfix">
	                    		
	                            	<div class="material_pop_box_item_es" id="material_demo_area" style="display :none">
	                            	</div>	
	                            	<?php echo CHtml::hiddenField('broadcast[material_demo]', isset($_POST['broadcast']['material_demo'])?$_POST['broadcast']['material_demo']:'')?>
	                    			
	                                <div class="broadcast_add_btn" id="chose_material_area">
	                                    <div class="broadcast_add_btn_inner">
	                                        <a href="javascript:void(0);" onclick="choseMaterial()">
	                                        	<p class="icon_add"></p>
	                                        	<p>从素材中选择</p>
	                                        </a>
	                                    </div>
	                                </div>
	                                <div class="broadcast_add_btn" id="add_material_area">
	                                    <div class="broadcast_add_btn_inner">
	                                    	<a href="<?php echo Yii::app()->createUrl('mCenter/fuwu/materialList')?>" target="_blank">
		                                        <p class="icon_add"></p>
		                                        <p>新建图文信息</p>
		                                    </a>
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="broadcast_textarea">
	                                <?php echo CHtml::textArea('broadcast[content]',isset($_POST['broadcast']['content'])?$_POST['broadcast']['content']:'')?>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="btn_l level_r">
	                        <?php echo CHtml::submitButton('发送',array('class'=>'btn_com_blue'));?>
	                    </div>
	                </div>
                <?php echo CHtml::endForm();?>
            </div>
        </div>
    </div>
    <!-- 实现瀑布流需要引入 masonry.pkgd.min.js-->
</div>


				<?php if (Yii::app()->user->hasFlash('empty')) { ?>
	    		<script>setTimeout("alert('<?php echo Yii::app()->user->getFlash('empty')?>')", 1)</script>
				<?php }?>
				
				<?php if (Yii::app()->user->hasFlash('wechat_error')) { ?>
	    		<script>setTimeout("alert('<?php echo Yii::app()->user->getFlash('wechat_error')?>')", 1)</script>
				<?php }?>
			
				<?php if (Yii::app()->user->hasFlash('wechat_success')) { ?>
	    		<script>setTimeout("alert('<?php echo Yii::app()->user->getFlash('wechat_success')?>')",5)</script>
				<?php }?>
				
				<?php if (Yii::app()->user->hasFlash('ali_error')) { ?>
	    		<script>setTimeout("alert('<?php echo Yii::app()->user->getFlash('ali_error')?>')", 1)</script>
				<?php }?>
			
				<?php if (Yii::app()->user->hasFlash('ali_success')) { ?>
	    		<script>setTimeout("alert('<?php echo Yii::app()->user->getFlash('ali_success')?>')",5)</script>
				<?php }?>
</body>

<script type="text/javascript">


<?php if (!empty($_POST)) { ?>
	$(document).ready(function() {
		<?php if( !empty($_POST['broadcast']['material_demo']) ) { ?>
			var html = $("#broadcast_material_demo").val();
			$("#material_demo_area").html(html);
        	$("#material_demo_area").show();
        	$("#chose_material_area").hide();
        	$("#add_material_area").hide();
		<?php }?>
	})
<?php } ?>

	$('.icon1').click(function(){
		$('#broadcast_type').val(2);
		});

	$('.icon2').click(function(){
		$('#broadcast_type').val(1);
	});

function choseMaterial()
{
	art.dialog.open(
        	'<?php echo Yii::app()->createUrl('mCenter/broadcast/materialDialog');?>',
        	 {
              	 title: '',
              	 lock: true,
              	 drag:true, 
                 width: '805px',
//               	 height: '803px',
              	 id: 'dialog_notice',
              	 background: '#fff',
              	 close: function() {
               		//获取共享数据：mid
	                 var mid = art.dialog.data('mid');
		             $('#broadcast_material_id').val(mid);
		             
		             if(mid != 0){
			             $.ajax({
			                    url:'<?php echo Yii::app()->createUrl('mCenter/broadcast/getMaterialDemo')?>',
			                    type:'GET',
			                    data:{material_id:mid},
			                    success:function(data){
			                    	$("#material_demo_area").html(data);
			                    	$("#broadcast_material_demo").val(data);
			                    	$("#material_demo_area").show();
			                    	$("#chose_material_area").hide();
			                    	$("#add_material_area").hide();
			                        $("#main", parent.document).height($(".kkfm_r_inner").outerHeight());
			                    }
			                });
		           	}
                	//删除共享数据
	               	art.dialog.removeData('mid');
              	 }
             }
    );
}

$(document).on("click", "#del_material", function() {
    $('#broadcast_material_id').val('');
    
	$("#material_demo_area").hide();
	$("#chose_material_area").show();
	$("#add_material_area").show();
    $("#main", parent.document).height($(".kkfm_r_inner").outerHeight());
});

function countNum(group_id){
	$.ajax({
        url:'<?php echo Yii::app()->createUrl('mCenter/broadcast/countUserNum')?>',
        type:'GET',
        data:{group_id:group_id},
        dataType:'json',
        success:function(data){
            $('#wechat_user').text(data.wechat+'人');
            $('#ali_user').text(data.ali+'人');
            $('#togher_user').text(data.togher+'人');
            $('#broadcast_wechat_user_num').val(data.wechat);
        }
    });
}

</script>


