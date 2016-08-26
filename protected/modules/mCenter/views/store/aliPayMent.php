<body>
<div class="kkfm_r_inner">
	<div class="paySet">
        <div class="top">
            <div class="name">
                收款账号 
            </div>
            <div class="menu clearfix">
            	<span class="cur">支付宝当面付设置</span>
                <span><a href = "<?php echo Yii::app() -> createUrl('mCenter/store/wxPayMent',array('id' => $alipay -> id))?>">微信支付设置</a></span>
            </div> 
        </div>
        <?php $form = $this->beginWidget('CActiveForm')?>
        <input name="id" style="display: none" value="<?php echo $alipay -> id?>">
        <div class="face" id="face">
        	<div class="bussniessType ">
                <div class="title">启用门店收款账号</div>
                <div class="title-con">
                	<div class="conL">
                        <div class="type">
                            启用门店收款账号
                        </div>
                        <p>门店使用独立收款账号时需要开启该功能，若不开启则使用上级收款账号</p>
                    </div>
                    <div class="toggleBtn">
                    	<div class="switch text" >
    						<input type="checkbox" <?php if($alipay -> if_alipay_open == IF_ALIPAY_OPEN_OPEN){?>checked<?php }?> name="switch"/>			
						</div>
                    </div>
                </div>
            </div>
            <div class="remind" <?php if($alipay -> if_alipay_open == IF_ALIPAY_OPEN_OPEN){?>style="display:none"<?php }?>><input type="checkbox" name="use_pro" <?php if($alipay -> alipay_use_pro == IF_USE_PRO_YES){?>checked<?php }?>>使用上级收款账号</div>
            <div class="bussniessType hidepart togglePAY">
                <div class="title">商户类型</div>
                <div class="title-con">
                    <div class="type">
                        <input type="radio" name="busType" value="<?php echo ALIPAY_API_VERSION_1?>" <?php if($alipay -> alipay_api_version == ALIPAY_API_VERSION_1){?>checked<?php }?>> <label for="ordinary">当面付1.0</label>
                        <input type="radio" name="busType" value="<?php echo ALIPAY_API_VERSION_2?>" <?php if($alipay -> alipay_api_version == ALIPAY_API_VERSION_2){?>checked<?php }?>> <label for="special">当面付2.0</label>
                    </div>
                    <p>接口版本根据签约产品进行选择</p>
                </div>
            </div>            
            <div class="bussniessType togglePAY hidepart" <?php if($alipay -> alipay_api_version == ALIPAY_API_VERSION_1){?>style="display: none"<?php }?> id="pay2">
                <div class="title">应用网关和应用公钥</div>
                <div class="title-con">
                    <div class="filed">
                        <span class="label">应用网关</span>
                        <span class="text" id="textgateway">http://gj.51wanquan.com/mCenter/gateway?account=<?php echo $alipay -> encrypt_id?></span>
                        <span class="text1">
                        <input type="button" class="btn_com_gray" value="复制链接" id="gateway" onclick="copygateway()">
                        </span>
                    </div>
                    <div class="filed">
                        <span class="label" style="vertical-align:top">应用公钥</span>
                        <span class="text" id="textdeveloper_public_key">MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDdJAQqGm0tHaMs0cgHl29N3gFv9aSsCcKFcK+edI4OQFl0iLt6U4In/st9XXJMQjN2Ltun6JsD3cHEx1iNmE26H2Z+C/AU6usaqnLQwmQnAhvik7XE/wkHAhcNRq55qCm6Xt48yrmE6hkO5NH2y6DQIIdiaYC5XhKNqWb7tezLJQIDAQAB</span>
                        <span class="text1" style="vertical-align:top">
                        <input type="button" class="btn_com_gray" value="复制密钥" id="developer_public_key" onclick="copydeveloperpublickey()">
                        </span>
                    </div>
                </div>
            </div>
        	<div class="end togglePAY hidepart" id="pay2_text" <?php if($alipay -> alipay_api_version == ALIPAY_API_VERSION_1){?>style="display: none"<?php }?>><a href="https://open.alipay.com" class="blue" target="_Blank">登录支付宝开放平台(open.alipay.com)</a>。在 应用详情 中可以进行 “激活开发者模式 ”(首次配置)或者在“应用环境”下直接修改操作。 
将上面的 应用网关 与 应用公钥 复制填写到 支付宝开放平台对应表单中 ，提交保存信息。</div>
        	<div class="payInfo hidepart togglePAY">
                <div class="title">当面付信息</div>
                <div class="title-con">
                    <div class="filed" id="appid" <?php if($alipay -> alipay_api_version == ALIPAY_API_VERSION_1){?>style="display: none"<?php }?>>
                        <span class="label">AppID</span>
                        <span class="text">
                        <div id="appid_text"><?php if(!empty($alipay -> alipay_appid)){echo $alipay -> alipay_appid;}?></div>
                        <input type="text" class="txt" name="appid" value="<?php echo $alipay -> alipay_appid?>" style="display: none" placeholder="请输入APPID">
                        </span>
                    </div>
                    <div class="filed" id="pid" <?php if($alipay -> alipay_api_version == ALIPAY_API_VERSION_2){?>style="display: none"<?php }?>>
                        <span class="label">合作者身份PID</span>
                        <span class="text">
                        <div id="pid_text"><?php if(!empty($alipay -> alipay_pid)){echo $alipay -> alipay_pid;}?></div>
                        <input type="text" class="txt" name="pid" style="display: none" value= "<?php echo $alipay -> alipay_pid?>" placeholder="请输入合作者身份id">
                        </span>
                    </div>
                    <div class="filed" id="key" <?php if($alipay -> alipay_api_version == ALIPAY_API_VERSION_2){?>style="display: none"<?php }?>>
                        <span class="label">安全校验码KEY</span>
                        <span class="text">
                        <div id="key_text"><?php if(!empty($alipay -> alipay_key)){echo $alipay -> alipay_key;}?></div>
                        <input type="text" class="txt" name="key" style="display: none" value="<?php echo $alipay -> alipay_key?>" placeholder="请输入安全校验码KEY">
                        </span>
                    </div>
                    
                    <div class="filed">
                        <span class="label"></span>	
                        <span class="text">
                        <input type="submit" class="btn_com_gray" value="保存" style="display: none" id="save">
                        <input type="button" class="btn_com_gray" value="修改" id="edit">
                        </span>
                    </div>
                </div>
            </div>
        	<div class="end hidepart togglePAY" id ="text_1" <?php if($alipay -> alipay_api_version == ALIPAY_API_VERSION_2){?>style="display:none"<?php }?>><a href="https://b.alipay.com" class="blue" target="_Blank">登录支付宝商户平台(b.alipay.com)</a>。 在【我的商家服务】菜单中 查询PID、Key ，并将 合作者身份（PID）及 安全校验码（Key） 填入上面 当面付信息 中。</div>
        	<div class="end hidepart togglePAY" id ="text_2" <?php if($alipay -> alipay_api_version == ALIPAY_API_VERSION_1){?>style="display:none"<?php }?>><a href="https://b.alipay.com" class="blue" target="_Blank">登录支付宝开放平台(open.alipay.com)</a>。在 管理中心 - 我的应用 - 应用详情 中查询APPID，并将APPID 填入上面 当面付信息 中。</div>
        </div>
        <?php $form = $this->endWidget()?>
    </div>
</div> 
</body>

<script type="text/javascript">
<?php if($alipay -> if_alipay_open == IF_ALIPAY_OPEN_CLOSE){?>
$('.hidepart').hide();
<?php }?>

var status = <?php echo empty($alipay -> alipay_api_version)?'':$alipay -> alipay_api_version?>;

<?php if($alipay -> alipay_api_version == ALIPAY_API_VERSION_1){?>
isempty();
<?php }elseif ($alipay -> alipay_api_version == ALIPAY_API_VERSION_2){?>
t_isempty();
<?php }?>

function isempty(){
	<?php if(empty($alipay -> alipay_pid) && empty($alipay -> alipay_key)){?>
		$('#save').show();
		$('#edit').hide();
		$('#pid_text').hide();
		$('input[name=pid]').show();
		$('#key_text').hide();
		$('input[name=key]').show();
	<?php }else{?>
	if(status == <?php echo ALIPAY_API_VERSION_1?>){
		$('#save').hide();
		$('#edit').show();
	}
	<?php }?>
}

function t_isempty(){
	<?php if(empty($alipay -> alipay_appid)){?>
		$('#save').show();
		$('#edit').hide();
		$('#appid_text').hide();
		$('input[name=appid]').show();
	<?php }else{?>
	if(status == <?php echo ALIPAY_API_VERSION_2?>){
		$('#save').hide();
		$('#edit').show();
	}
	<?php }?>
}

function resize(){
	window.parent.autoResize('main');
}

$.fn.bootstrapSwitch.defaults.onText = '已启用';
$.fn.bootstrapSwitch.defaults.offText = '已关闭';
$('.toggleBtn .switch').on('switchChange.bootstrapSwitch',function(event,state){
	if(state){
		$('.hidepart').slideDown(1000,resize);
		if(status == 2){
			$('#pay2').show();
			$('#pay2_text').show();
			$('#text_2').show();
			$('#text_1').hide();
		}else if(status == 1){
			$('#text_2').hide();
			$('#text_1').show();
			$('#pay2').hide();
			$('#pay2_text').hide();
		}
		$('.remind').hide();
		window.parent.autoResize('main');
		$.ajax({
			url : '<?php echo Yii::app()->createUrl('mCenter/store/SetManagementAlipayOpen'); ?>',
			data : {state : 'true',id:<?php echo $alipay -> id?>},
			type : 'post',
			async : false,
			dataType: 'json',
			success : function(res){
				if(res.status != '<?php echo ERROR_NONE?>'){
					alert(res.errMsg);
				}
			}
		});
	}else{
		$('.hidepart').slideUp(1000,resize);
		$('#pay2').hide();
		$('#pay2_text').hide();
		$('.remind').show();
		window.parent.autoResize('main');
		$.ajax({
			url : '<?php echo Yii::app()->createUrl('mCenter/store/SetManagementAlipayOpen'); ?>',
			data : {state : 'false',id:<?php echo $alipay -> id?>},
			type : 'post',
			async : false,
			dataType: 'json',
			success : function(res){
				if(res.status != '<?php echo ERROR_NONE?>'){
					alert(res.errMsg);
				}
			}
		});
	}

});

$('input[name=use_pro]').change(function(){
	if($(this).is(':checked')) {
		$.ajax({
			url : '<?php echo Yii::app()->createUrl('mCenter/store/SetManagementAlipayUse'); ?>',
			data : {state : 'true',id:<?php echo $alipay -> id?>},
			type : 'post',
			async : false,
			success : function(res){
				res = eval(res);
				if(res.status != <?php echo ERROR_NONE?>){
					alert(res.errMsg);
				}
			}
		});
	}else{
		$.ajax({
			url : '<?php echo Yii::app()->createUrl('mCenter/store/SetManagementAlipayUse'); ?>',
			data : {state : 'false',id:<?php echo $alipay -> id?>},
			type : 'post',
			async : false,
			success : function(res){
				res = eval(res);
				if(res.status != <?php echo ERROR_NONE?>){
					alert(res.errMsg);
				}
			}
		});
	}
});

$('input[name=busType]').change(function(){
	var vision = $(this).val();
	if(vision == 2){
		status = 2;
		$('#pay2').fadeIn(800,resize);
		$('#pay2_text').show();
		$('#appid').show();
		$('#pid').hide();
		$('#key').hide();
		$('#text_1').hide();
		$('#text_2').show();
		window.parent.autoResize('main');
		t_isempty();
	}else if(vision == 1){
		status = 1;
		$('#pay2').fadeOut(800,resize);
		$('#pay2_text').hide();
		$('#appid').hide();
		$('#pid').show();
		$('#key').show();
		$('#text_1').show();
		$('#text_2').hide();
		window.parent.autoResize('main');
		isempty();
	}
});

$('#edit').click(function(){
	if(status == 2){
		$('#save').show();
		$(this).hide();
		$('#appid_text').hide();
		$('input[name=appid]').show();
		
	}else if(status == 1){
		$('#save').show();
		$(this).hide();
		$('#pid_text').hide();
		$('input[name=pid]').show();
		$('#key_text').hide();
		$('input[name=key]').show();
	}
});



	//复制网关
	$('#gateway').zclip({
		path:"<?php echo GJ_STATIC_JS?>ZeroClipboard.swf",
		copy:function(){
			return $('#textgateway').text();
		},
		afterCopy:function(){/* 复制成功后的操作 */
            	alert('成功复制到剪切板，Ctrl+V即可粘贴');
        	}
		});
	
	
	//复制开发者公钥
	$('#developer_public_key').zclip({
		path:"<?php echo GJ_STATIC_JS?>ZeroClipboard.swf",
		copy:function(){
			return $('#textdeveloper_public_key').text();
		},
		afterCopy:function(){/* 复制成功后的操作 */
            	alert('成功复制到剪切板，Ctrl+V即可粘贴');
        }
	});
	 
</script>

