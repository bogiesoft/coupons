<body>
<div class="kkfm_r_inner">
	<div class="paySet">
        <div class="top">
            <div class="name">
                收款账号 
            </div>
            <div class="menu clearfix">
            	<span><a href="<?php echo Yii::app() -> createUrl('mCenter/store/SetAlipay',array('id' => $wechat -> id));?>">支付宝当面付设置</a></span>
                <span class="cur">微信支付设置</span>
            </div> 
        </div>
        <?php $form = $this->beginWidget('CActiveForm')?>
        <input name="id" style="display: none" value="<?php echo $wechat -> id?>">
        <div class="face wxPaySet">
        	<div class="bussniessType">
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
    						<input type="checkbox" <?php if($wechat -> if_wx_open == IF_ALIPAY_OPEN_OPEN){?>checked<?php }?> name="switch"/>			
						</div>
                    </div>
                </div>
            </div>
            
            <div class="remind" <?php if($wechat -> if_wx_open == IF_ALIPAY_OPEN_OPEN){?>style="display:none"<?php }?>><input type="checkbox" name="use_pro" <?php if($wechat -> wx_use_pro == IF_USE_PRO_YES){?>checked<?php }?>>使用上级收款账号</div>
            <div class="bussniessType hidepart togglePAY">
                <div class="title">商户类型</div>
                <div class="title-con">
                    <div class="type">
                        <input type="radio" name="busType" id="ordinary" value="<?php echo WXPAY_MERCHANT_TYPE_SELF?>" <?php if($wechat -> wx_merchant_type == WXPAY_MERCHANT_TYPE_SELF){?>checked<?php }?>> <label for="ordinary">普通商户</label>
                        <input type="radio" name="busType" id="special" value="<?php echo WXPAY_MERCHANT_TYPE_AFFILIATE?>" <?php if($wechat -> wx_merchant_type == WXPAY_MERCHANT_TYPE_AFFILIATE){?>checked<?php }?>> <label for="special">特约商户</label>
                    </div>
                    <p>商户类型可在<em class="blue">微信支付商户平台</em>-账号设置-账户信息中查看</p>
                </div>
            </div>
            
            <div class="certUpload hidepart togglePAY" id="upload_cert" <?php if($wechat -> wx_merchant_type == WXPAY_MERCHANT_TYPE_AFFILIATE){?>style="display:none"<?php }?>>
                <div class="title">证书上传</div>
                <div class="title-con ">
                    
                  	<div class="filed">
                    	<?php if(isset($wechat -> wx_apiclient_cert)&&!empty($wechat -> wx_apiclient_cert)){?>
                    	<span class="label"><a href="#" class="btn_com_gray" id="cert_upload">重新上传</a></span>
                    	<?php }else{?>
                        <span class="label"><a href="#" class="btn_com_gray" id="cert_upload">点击上传</a></span>
                    	<?php }?>
                    	<span class="text" id="refile_name">apiclient_cert.pem</span>
                        <span class="<?php echo empty($wechat -> wx_apiclient_cert)?'error1':'succeed1';?>" id="file_cert_state" ><?php echo empty($wechat -> wx_apiclient_cert)?'请上传apiclient_cert.pem':'已上传';?></span>
                	</div>
                    
                    <div class="filed">
                    	<?php if(isset($wechat -> wx_apiclient_key)&&!empty($wechat -> wx_apiclient_key)){?>
                        <span class="label"><a href="#" class="btn_com_gray" id="key_upload">重新上传</a></span>
                        <?php }else{?>
                        <span class="label"><a href="#" class="btn_com_gray" id="key_upload">点击上传</a></span>  
                    	<?php }?>
                    	<span class="text" id="file_name">apiclient_key.pem</span>
                        <span class="<?php echo empty($wechat -> wx_apiclient_key)?'error1':'succeed1';?>" id="file_key_state" ><?php echo empty($wechat -> wx_apiclient_key)?'请上传apiclient_key.pem':'已上传';?></span>
                	</div>
                </div>
            </div>
        
        	<div class="payInfo hidepart">
                <div class="title">微信支付信息</div>
                <div class="title-con">
                    <div class="filed" id="appid" <?php if($wechat -> wx_merchant_type == WXPAY_MERCHANT_TYPE_AFFILIATE){?>style="display:none"<?php }?>>
                        <span class="label">AppID(应用ID)</span>
                        <span class="text">
                        <div id="appid_text"><?php if(!empty($wechat -> wx_appid)){echo $wechat -> wx_appid;}?></div>
                        <input type="text" class="txt" name="appid" style="display: none"  value="<?php if(!empty($wechat -> wx_appid)){echo $wechat -> wx_appid;}?>" placeholder="请输入APPID">
                        </span>
                    </div>
                    <div class="filed" id="appsecret" >
                        <span class="label">AppSecret(应用密钥)</span>
                        <span class="text">
                        <div id="appsecret_text"><?php if(!empty($wechat -> wx_appsecret)){echo $wechat -> wx_appsecret;}?></div>
                        <input type="text" class="txt" name="appsecret" style="display: none" value="<?php if(!empty($wechat -> wx_appsecret)){echo $wechat -> wx_appsecret;}?>" placeholder="(选填)请输入AppSecret，用于公众号支付">
                        </span>
                    </div>
                    <div class="filed" id="mchid">
                        <span class="label">微信支付商户号</span>
                        <span class="text">
                        <div id="mchid_text"><?php if(!empty($wechat -> wx_mchid)){echo $wechat -> wx_mchid;}?></div>
                        <input type="text" class="txt" name="mchid" style="display: none" value="<?php if(!empty($wechat -> wx_mchid)){echo $wechat -> wx_mchid;}?>" placeholder="请输入微信mchid">
                        </span>
                    </div>
                    <div class="filed" id="api_key" <?php if($wechat -> wx_merchant_type == WXPAY_MERCHANT_TYPE_AFFILIATE){?>style="display:none"<?php }?>>
                        <span class="label">API密钥</span>
                        <span class="text">
                        <div id="api_key_text"><?php if(!empty($wechat -> wx_api)){echo $wechat -> wx_api;}?></div>
                        <input type="text" class="txt" name="api_key" style="display: none" value="<?php if(!empty($wechat -> wx_api)){echo $wechat -> wx_api;}?>" placeholder="请输入微信key">
                        </span>
                    </div>
                    
                    <div class="filed" id="t_appid" >
                        <span class="label">AppID(应用ID)</span>
                        <span class="text">
                        <div id="t_appid_text"><?php if(!empty($wechat -> t_wx_appid)){echo $wechat -> t_wx_appid;}?></div>
                        <input type="text" class="txt" name="t_appid" style="display: none" value="<?php if(!empty($wechat -> t_wx_appid)){echo $wechat -> t_wx_appid;}?>" placeholder="请输入AppID（选填）">
                        </span>
                    </div>
                    <div class="filed" id="t_mchid">
                        <span class="label">微信支付商户号</span>
                        <span class="text">
                        <div id="t_mchid_text"><?php if(!empty($wechat -> t_wx_mchid)){echo $wechat -> t_wx_mchid;}?></div>
                        <input type="text" class="txt" name="t_mchid" style="display: none" value="<?php if(!empty($wechat -> t_wx_mchid)){echo $wechat -> t_wx_mchid;}?>" placeholder="请输入微信mchid">
                        </span>
                    </div>
                    
                    <div class="filed">
                        <span class="label"></span>
                        <span class="text">
                        <input type="submit" class="btn_com_gray" value="保存" style="display: none" id="save">
                        <input type="button" class="btn_com_gray" value="修改"  id="edit">
                        </span>
                    </div>
                </div>
            </div>
        	<div class="end hidepart togglePAY" id="text_1" <?php if($wechat -> wx_merchant_type == WXPAY_MERCHANT_TYPE_AFFILIATE){?>style="display:none"<?php }?>><a href="https://mp.weixin.qq.com" class="blue">登录微信公众平台(mp.weixin.qq.com)</a>。在 开发者中心-开发者ID 菜单中将AppID(应用ID)和 AppSecret(应用密钥)填写到上方输入框中。
				<a href="https://pay.weixin.qq.com" class="blue">登录微信商户平台(pay.weixin.qq.com)</a>。在 账户概览-基础信息菜单中，将 微信支付商户号填写到上方输入框中。在 账户设置-API安全 菜单中，API密钥-设置密钥，
将 API密钥填入上方输入框中，并提交保存</div>
			<div class="end hidepart togglePAY" id="text_2" <?php if($wechat -> wx_merchant_type == WXPAY_MERCHANT_TYPE_SELF){?>style="display:none"<?php }?>><a href="https://pay.weixin.qq.com" class="blue">登录微信商户平台(pay.weixin.qq.com)</a>。在 账户概览-基础信息菜单中，将 微信支付商户号填写到上面微信支付信息中。</div>    
        </div>
        
        <?php $form = $this->endWidget()?>
    </div>
</div> 
</body>
<script>

<?php if($wechat -> if_wx_open == IF_ALIPAY_OPEN_CLOSE){?>
$('.hidepart').hide();
<?php }?>

<?php if($wechat -> wx_merchant_type == WXPAY_MERCHANT_TYPE_AFFILIATE){?>
$('#t_appid').show();
$('#t_mchid').show();
$('#appid').hide();
$('#appsecret').hide();
$('#mchid').hide();
$('#api_key').hide();
<?php }elseif ($wechat -> wx_merchant_type == WXPAY_MERCHANT_TYPE_SELF){?>
$('#t_appid').hide();
$('#t_mchid').hide();
$('#appid').show();
$('#appsecret').show();
$('#mchid').show();
$('#api_key').show();
<?php }?>

var status = <?php echo empty($wechat -> wx_merchant_type)?'':$wechat -> wx_merchant_type?>;
<?php if($wechat -> wx_merchant_type == WXPAY_MERCHANT_TYPE_SELF){?>
isempty();
<?php }elseif ($wechat -> wx_merchant_type == WXPAY_MERCHANT_TYPE_AFFILIATE){?>
t_isempty();
<?php }?>



function isempty(){
<?php if((empty($wechat -> wx_appid) && empty($wechat -> wx_appsecret) && empty( $wechat -> wx_api) && empty( $wechat -> wx_mchid))){?>
	$('#save').show();
	$('#edit').hide();
	$('#appid_text').hide();
	$('input[name=appid]').show();
	$('#appsecret_text').hide();
	$('input[name=appsecret]').show();
	$('#mchid_text').hide();
	$('input[name=mchid]').show();
	$('#api_key_text').hide();
	$('input[name=api_key]').show();
<?php }else{?>
	if(status == <?php echo WXPAY_MERCHANT_TYPE_SELF?>){
		$('#save').hide();
		$('#edit').show();
	}
<?php }?>
}
function t_isempty(){
<?php if(empty($wechat -> t_wx_appid) && empty($wechat -> t_wx_mchid)){?>
	$('#save').show();
	$('#edit').hide();
	$('#t_appid_text').hide();
	$('input[name=t_appid]').show();
	$('#t_mchid_text').hide();
	$('input[name=t_mchid]').show();
<?php }else{?>
	if(status == <?php echo WXPAY_MERCHANT_TYPE_AFFILIATE?>){
		$('#save').hide();
		$('#edit').show();
	}
<?php }?>
}


function resize(){
	window.parent.autoResize('main',$("body").height());
}

$.fn.bootstrapSwitch.defaults.onText = '已启用';
$.fn.bootstrapSwitch.defaults.offText = '已关闭';
$('.toggleBtn .switch').on('switchChange.bootstrapSwitch',function(event,state){
	if(state){
		$('.hidepart').slideDown(1000,resize);
		$('.remind').slideUp(500,resize);
		if(status == <?php echo WXPAY_MERCHANT_TYPE_AFFILIATE?>){
			$('#upload_cert').hide();
			$('#sp').show();
			$('#normal').hide();
			$('#text_1').hide();
			$('#text_2').show();
		}else if(status == <?php echo WXPAY_MERCHANT_TYPE_SELF?>){
			$('#text_1').show();
			$('#text_2').hide();
		}
		window.parent.autoResize('main');
		$.ajax({
			url : '<?php echo Yii::app()->createUrl('mCenter/store/SetWechatOpen'); ?>',
			data : {state : 'true',id:<?php echo $wechat -> id?>},
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
		$('.remind').slideDown(500,resize);
		window.parent.autoResize('main');
		$.ajax({
			url : '<?php echo Yii::app()->createUrl('mCenter/store/SetWechatOpen'); ?>',
			data : {state : 'false',id:<?php echo $wechat -> id?>},
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
			url : '<?php echo Yii::app()->createUrl('mCenter/store/SetWechatUse'); ?>',
			data : {state : 'true',id:<?php echo $wechat -> id?>},
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
			url : '<?php echo Yii::app()->createUrl('mCenter/store/SetWechatUse'); ?>',
			data : {state : 'false',id:<?php echo $wechat -> id?>},
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
		$('#upload_cert').fadeOut(100,resize);
		$('#t_appid').show();
		$('#t_mchid').show();
		$('#appid').hide();
		$('#appsecret').hide();
		$('#mchid').hide();
		$('#api_key').hide();
		$('#text_1').hide();
		$('#text_2').show();
		t_isempty();
	}else if(vision == 1){
		status = 1;
		$('#upload_cert').fadeIn(100,resize);
		$('#t_appid').hide();
		$('#t_mchid').hide();
		$('#appid').show();
		$('#appsecret').show();
		$('#mchid').show();
		$('#api_key').show();
		$('#text_1').show();
		$('#text_2').hide();
		isempty();
	}
});

$('#edit').click(function(){
	if(status == 2){
		$('#save').show();
		$(this).hide();
		$('#t_appid_text').hide();
		$('input[name=t_appid]').show();
		$('#t_mchid_text').hide();
		$('input[name=t_mchid]').show();
	}else if(status == 1){
		$('#save').show();
		$(this).hide();
		$('#appid_text').hide();
		$('input[name=appid]').show();
		$('#appsecret_text').hide();
		$('input[name=appsecret]').show();
		$('#mchid_text').hide();
		$('input[name=mchid]').show();
		$('#api_key_text').hide();
		$('input[name=api_key]').show();
	}
});




$(function () {
    $('#key_upload').uploadify({
    	onInit: function () {$(".uploadify-queue").hide();},//载入时触发，将flash设置到最小
        uploader: '<?php echo UPLOAD_FILE_TO_PATH?>',// 服务器处理地址
        swf: '<?php echo GJ_STATIC_JS?>'+'uploadify/uploadify.swf',
        buttonText: document.getElementById('key_upload').innerText,//按钮文字
        height: 30,  //按钮高度
        width: 70, //按钮宽度
        fileTypeExts: "<?php echo UPLOAD_KEY_FILE_TYPE;?>",//允许的文件类型
        fileTypeDesc: "请选择证件文件", //文件说明
        formData: { 'folder' : "<?php echo $dir_name?>",'filename':document.getElementById('file_name').innerText}, //提交给服务器端的参数
        onSelect:function(file)
        {
            if(file.name!=document.getElementById('file_name').innerText)
            {
                alert('请选择'+document.getElementById('file_name').innerText+'文件');
                $('#key_upload').uploadify('cancel','*');
            }

        },
        onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
            eval("var jsondata = " + data + ";");
            var fileName = jsondata['file_path'];
            $.ajax({
                url:'<?php echo Yii::app()->createUrl('mCenter/store/uploadSuccess');?>',
                cache:false,
                data:{dir_name:"<?php echo $dir_name?>",type:file.name,id:<?php echo $wechat -> id?>},
                type:'post',
                async: false,
                success:function(data){
                    var res=eval(data);
                    if(res==<?php echo ERROR_NONE;?>){
                    	document.getElementById('file_key_state').className = 'succeed1';
                    	document.getElementById('file_key_state').innerText = '已上传';
//                     	$('#key_upload').text('重新上传');
                    }else{
                        alert("上传失败");
                    }
                }
            });
        }
    });
});
$(function () {
    $('#cert_upload').uploadify({
    	onInit: function () {$(".uploadify-queue").hide();},//载入时触发，将flash设置到最小
        uploader: '<?php echo UPLOAD_FILE_TO_PATH?>',// 服务器处理地址
        swf: '<?php echo GJ_STATIC_JS?>'+'uploadify/uploadify.swf',
        buttonText: document.getElementById('cert_upload').innerText,//按钮文字
        height: 30,  //按钮高度
        width: 70, //按钮宽度
        fileTypeExts: "<?php echo UPLOAD_CERT_FILE_TYPE;?>",//允许的文件类型
        fileTypeDesc: "请选择证件文件", //文件说明
        formData: { 'folder' : "<?php echo $dir_name?>",'filename':document.getElementById('refile_name').innerText}, //提交给服务器端的参数
        onSelect:function(file)
        {
            if(file.name!=document.getElementById('refile_name').innerText)
            {
                alert('请选择'+document.getElementById('refile_name').innerText+'文件');
                $('#cert_upload').uploadify('cancel','*');
            }

        },
        onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
            eval("var jsondata = " + data + ";");
            var file_Name = jsondata['file_path'];
            $.ajax({
              url:'<?php echo Yii::app()->createUrl('mCenter/store/uploadSuccess');?>',
              data:{dir_name:"<?php echo $dir_name?>",type:file.name,id:<?php echo $wechat -> id?>},
              type:'post',
              async: false,
                success:function(data){
                    var res=eval(data);
                    if(res==<?php echo ERROR_NONE;?>){
                    	document.getElementById('file_cert_state').className = 'succeed1';
                    	document.getElementById('file_cert_state').innerText = '已上传';
//                     	$('#cert_upload').text('重新上传');
                    }else{
                        alert("上传失败");
                    }
              }
          });
        }

    });
});
</script>