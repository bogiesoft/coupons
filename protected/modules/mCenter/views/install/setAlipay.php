<div class="kkfm_r_inner">
	<?php echo CHtml::beginForm() ?>
    <div class="paySet">
        <div class="top newTop">
            <div class="name">
                支付宝收款账号 
            </div>
        </div>
        <div class="unauthorized <?php echo !empty($info['auth_token']) ? 'authorized' : ''; ?>">
            <div class="unauthorizedTip">
                <h3>支付宝授权：<?php echo $info['auth_status']; ?></h3>
                <p>时间：<time><?php echo $info['auth_time']; ?></time></p>
            </div>
        </div>
        <div class="face">
            <div class="bussniessType">
                <div class="title">收款账号</div>
                <div class="title-con">
                    <div class="conL">
                        <div class="type">
                            授权支付宝账号<span class="pidNum">APPID：<?php echo $info['auth_appid']; ?></span>
                        </div>
                        <p>开启时，使用支付宝授权账号；关闭时，需要添加一个支付宝账号。</p>
                    </div>
                    <div class="toggleBtn">
                        <div class="switch text" >
    						<input type="checkbox" <?php echo $info['api_version'] == ALIPAY_API_VERSION_2_AUTH_API ? 'checked' : ''; ?> name="switch"/>			
						</div>
                    </div>
                </div>
            </div>
            <?php echo CHtml::hiddenField('Info[api_version]', $info['api_version'])?>
            <div id="v1-v2" class="bussniessType togglePAY" <?php echo $info['api_version'] == ALIPAY_API_VERSION_2_AUTH_API ? 'style="display: none"' : ''; ?>>
                <div class="title">商户接口版本</div>
                <div class="title-con">
                    <div class="type">
                        <input type="radio" name="busType" value="<?php echo ALIPAY_API_VERSION_1_API?>" <?php echo $info['api_version'] == ALIPAY_API_VERSION_1_API ? 'checked' : ''; ?>> <label for="ordinary">当面付1.0</label>
                        <input type="radio" name="busType" value="<?php echo ALIPAY_API_VERSION_2_API?>" <?php echo $info['api_version'] == ALIPAY_API_VERSION_2_API ? 'checked' : ''; ?>> <label for="special">当面付2.0</label>
                    </div>
                    <p>接口版本根据签约产品进行选择</p>
                </div>
            </div>            
            <div id="v2-gateway-key" class="bussniessType togglePAY" <?php echo $info['api_version'] != ALIPAY_API_VERSION_2_API ? 'style="display: none"' : ''; ?>>
                <div class="title">应用网关和应用公钥</div>
                <div class="title-con">
                    <div class="filed">
                        <span class="label">应用网关</span>
                        <span class="text" id="textgateway"><?php echo DEVELOPER_GATEWAY.$info['encrypt_id']; ?></span>
                        <span class="text1"><input type="button" class="btn_com_gray" id="gateway" value="复制链接"></span>
                    </div>
                    <div class="filed">
                        <span class="label" style="vertical-align:top">应用公钥</span>
                        <span class="text" id="textdeveloper_public_key">MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDdJAQqGm0tHaMs0cgHl29N3gFv9aSsCcKFcK+edI4OQFl0iLt6U4In/st9XXJMQjN2Ltun6JsD3cHEx1iNmE26H2Z+C/AU6usaqnLQwmQnAhvik7XE/wkHAhcNRq55qCm6Xt48yrmE6hkO5NH2y6DQIIdiaYC5XhKNqWb7tezLJQIDAQAB</span>
                        <span class="text1" style="vertical-align:top"><input type="button" class="btn_com_gray" id="developer_public_key" value="复制密钥"></span>
                    </div>
                </div>
            </div>
            <div id="v2-tip1" class="end togglePAY" <?php echo $info['api_version'] != ALIPAY_API_VERSION_2_API ? 'style="display: none"' : ''; ?>>登录<a target="_blank" href="https://open.alipay.com" class="blue">支付宝开放平台（open.alipay.com）</a>。
            在应用详情中可以进行“激活开发者模式”（首次配置）或者在“应用环境”下直接修改操作。将上面的<b>应用网关</b>与<b>应用公钥</b>复制填写到支付宝开放平台对应表单中，提交保存信息。
            </div>
            <div id="payinfo" class="payInfo togglePAY" <?php echo $info['api_version'] == ALIPAY_API_VERSION_2_AUTH_API ? 'style="display: none"' : ''; ?>>
                <div class="title">当面付信息</div>
                <div class="title-con">
                    <div class="filed" id="appid" <?php echo $info['api_version'] != ALIPAY_API_VERSION_2_API ? 'style="display: none"' : ''; ?>>
                        <span class="label">APPID</span>
                        <span class="text">
                        	<?php echo CHtml::textField('Info[appid]', $info['appid'], array('class' => 'txt'))?>
                        	<span class="text1 red">
                        	<?php if (Yii::app()->user->hasFlash('appid')) {
                        		echo Yii::app()->user->getFlash('appid');
            				}?>
                        	</span>
                        </span>
                        <input type="submit" class="btn_save" value="保存">
                    </div>
                    <div class="filed" id="pid" <?php echo $info['api_version'] != ALIPAY_API_VERSION_1_API ? 'style="display: none"' : ''; ?>>
                        <span class="label">合作者身份PID</span>
                        <span class="text">
                        	<?php echo CHtml::textField('Info[pid]', $info['pid'], array('class' => 'txt'))?>
                        	<span class="text1 red">
                        	<?php if (Yii::app()->user->hasFlash('pid')) {
                        		echo Yii::app()->user->getFlash('pid');
            				}?>
                        	</span>
                        </span>
                    </div>
                    <div class="filed" id="key" <?php echo $info['api_version'] != ALIPAY_API_VERSION_1_API ? 'style="display: none"' : ''; ?>>
                        <span class="label">安全校验码KEY</span>
                        <span class="text">
                        	<?php echo CHtml::textField('Info[key]', $info['key'], array('class' => 'txt'))?>
                        	<span class="text1 red">
                        	<?php if (Yii::app()->user->hasFlash('key')) {
                        		echo Yii::app()->user->getFlash('key');
            				}?>
                        	</span>
                        </span>
                        <input type="submit" class="btn_save" value="保存">
                    </div>
                </div>
            </div>
            <div id="v1-tip1" class="end togglePAY" <?php echo $info['api_version'] != ALIPAY_API_VERSION_1_API ? 'style="display: none"' : ''; ?>>登录<a href="https://b.alipay.com" class="blue" target="_blank">支付宝商户平台(b.alipay.com)</a>。 
            在【我的商家服务】菜单中查询PID、Key，并将合作者身份（PID）及安全校验码（Key）填入上面当面付信息中。
            </div>
            <div id="v2-tip2" class="end togglePAY" <?php echo $info['api_version'] != ALIPAY_API_VERSION_2_API ? 'style="display: none"' : ''; ?>>登录<a target="_blank" href="https://open.alipay.com" class="blue">支付宝开放平台（open.alipay.com）</a>。
            管理中心 - 我的应用 - 应用详情中查询APPID，并将APPID填入当面付信息中。
            </div>
        </div>
    </div>
    <?php echo CHtml::endForm() ?>
</div>


<script>
    
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

	//重设高度
	function autoResize(){
		window.parent.callParAutoResize("main",$("body").height());
	}

	//显示1.0设置信息
	function apiVersion1() {
		$("#v1-v2").add("#payinfo").slideDown(500, function() {
			$("#pid").add("#key").add("#v1-tip1").fadeIn(1, function() {
				$("#v2-gateway-key").add("#v2-tip1").add("#appid").add("#v2-tip2").slideUp(500, autoResize);
			});
		});
	}
	//显示2.0设置信息
	function apiVersion2() {
		$("#v1-v2").add("#payinfo").slideDown(1, function() {
			$("#v2-gateway-key").add("#v2-tip1").add("#appid").add("#v2-tip2").slideDown(500, function() {
				$("#pid").add("#key").add("#v1-tip1").fadeOut(1, autoResize);
			});
		});
	}
	//隐藏设置信息
	function apiVersion3() {
		$("#v1-v2").add("#payinfo").add("#v2-gateway-key").add("#v2-tip1").add("#appid").add("#v2-tip2").add("#pid").add("#key").add("#v1-tip1").slideUp(1000, autoResize);
	}

	$.fn.bootstrapSwitch.defaults.onText = '已启用';
	$.fn.bootstrapSwitch.defaults.offText = '已关闭';
	$('.toggleBtn .switch').on('switchChange.bootstrapSwitch',function(event,state){
		if (state) {
			//启用
			apiVersion3();
			var version = '<?php echo ALIPAY_API_VERSION_2_AUTH_API?>';
			$("#Info_api_version").val(version);
			$.ajax({
	            url: '<?php echo(Yii::app()->createUrl('mCenter/install/setApiVersion'));?>',
	            data: {version:version},
	            type: 'post',
	            dataType: 'json',
	            success: function (data) {
	              	if(data.status != '<?php echo ERROR_NONE?>'){
	                  	alert(data.errMsg);
	              	}
	            }
	        });
		}else {
			//不启用
			var appid = $("#Info_appid").val();
			if(!appid) {
				$("input[name=busType][value=<?php echo ALIPAY_API_VERSION_1_API?>]").prop("checked", true);
				apiVersion1();
			}else {
				$("input[name=busType][value=<?php echo ALIPAY_API_VERSION_2_API?>]").prop("checked", true);
				apiVersion2();
			}
			var version = $("input[name=busType]:checked").val();
			$("#Info_api_version").val(version);
			$.ajax({
	            url: '<?php echo(Yii::app()->createUrl('mCenter/install/setApiVersion'));?>',
	            data: {version:version},
	            type: 'post',
	            dataType: 'json',
	            success: function (data) {
	              	if(data.status != '<?php echo ERROR_NONE?>'){
	                  	alert(data.errMsg);
	              	}
	            }
	        });
		}
	});

	$("input[name=busType]").change(function() {
		var version = $(this).val();
		if (version == '<?php echo ALIPAY_API_VERSION_1_API?>') {
			$("#Info_api_version").val(version);
			apiVersion1();
		}
		if (version == '<?php echo ALIPAY_API_VERSION_2_API?>') {
			$("#Info_api_version").val(version);
			apiVersion2();
		}
	});
	
</script>