<script type="text/javascript">
	$(document).ready(main_obj.list_init);
</script>
<?php $form = $this->beginWidget('CActiveForm')?>


<div class="kkfm_r_inner">
    <div class="face">
    <div class="top">
    	<div class="name">服务窗设置</div>
        </div>
		<div class="title">服务窗账号绑定说明</div>
        <div class="title-con">
        	<dl>
            	 <dd>1.用户登录到 <span class="black fw"><a target= "_blank" href="http://open.alipay.com/platform/home.htm">支付宝开放平台</a>（open.alipay.com）。</span></dd>
           <dd>2.在<span class="black fw"> 管理中心 - 我的应用 - 应用详情 </span>中<span class="black fw">查询APPID，</span>并将<span class="black fw">APPID </span>填入下面 输入框 中。 </dd>
           <dd>3. 在<span class="black fw">  应用详情 </span>中可以进行 <span class="black fw">激活开发者模式</span> （首次配置）或者在<span class="black fw">应用环境</span>下直接修改操作。</dd>
           <dd>4. 将下方的 <span class="black fw">应用网关 </span>与 <span class="black fw">应用公钥 </span>复制填写到 支付宝开放平台对应表单中 ，提交保存信息。</dd>
            </dl>
        </div>
        <div class="title">应用网关和应用公钥</div>
        <div class="title-con bg">
        	<div class="filed">
        		<span class="label">应用网关</span>
            	<span class="text" id="textgateway"><?php echo DEVELOPER_GATEWAY.$merchant -> encrypt_id?></span>
                <span class="text1"><input type="button" class="btn_com_gray" value="复制链接" id="gateway"></span>
        	</div>
            <div class="filed">
        		<span class="label" style="vertical-align:top">应用公钥</span>
            	<span class="text" id="textdeveloper_public_key">MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDdJAQqGm0tHaMs0cgHl29N3gFv9aSsCcKFcK+edI4OQFl0iLt6U4In/st9XXJMQjN2Ltun6JsD3cHEx1iNmE26H2Z+C/AU6usaqnLQwmQnAhvik7XE/wkHAhcNRq55qCm6Xt48yrmE6hkO5NH2y6DQIIdiaYC5XhKNqWb7tezLJQIDAQAB</span>
                <span class="text1" style="vertical-align:top"><input type="button" class="btn_com_gray" value="复制密钥" id="developer_public_key"></span>
        	</div>
        </div>
       	<div class="title">APPID</div>
        	<div class="title-con bg">
                <div class="filed">
                    <div class="filed">
                    	<span class="label fw" style="width:80px;<?php if(empty($merchant -> appid)&&!$merchant->all_empty){echo "display:none";}?>">APPID</span>
                    	<span class="text" style="width:210px">
                    		<?php if(!empty($merchant -> appid)){?>
                   			<div id="appid_text"><?php echo $merchant -> appid?></div>
                    		<?php }?>
                    		<input type="text" class="txt" style="width:200px;<?php if(isset($merchant -> appid)&&!$merchant->all_empty){echo "display:none";}?>" name="appid" value="<?php if(isset($merchant -> appid) && !empty($merchant -> appid)){echo $merchant -> appid;}?>">
                    	</span>
                   	</div>
                    <div class="filed">
                    	<span class="label fw" id="fuwu_name_txt" style="width:80px;<?php if(empty($merchant -> fuwu_name)&&!$merchant->all_empty){echo "display:none";}?>" >服务窗名称</span>
                    	<span class="text" style="width:210px">
                    		<?php if(!empty($merchant -> fuwu_name)){?>
                   			<div id="fuwu_name_text"><?php echo $merchant -> fuwu_name?></div>
                    		<?php }?>
                    		<input type="text" class="txt" style="width:200px;<?php if(!$merchant->all_empty){echo "display:none";}?>" name="fuwu_name" value="<?php if(isset($merchant -> fuwu_name) && !empty($merchant -> fuwu_name)){echo $merchant -> fuwu_name;}?>">
                    	</span>
                    </div>
                    <div class="filed">
                        <span class="label fw" id="alipay_qrcode1" style="width:80px;<?php if(isset($merchant -> alipay_qrcode)&&!$merchant->all_empty){echo "display:none";}?>">二维码</span>
                        <span class="text" id="qrcode_txt" style="<?php if(isset($merchant -> alipay_qrcode)&&!$merchant->all_empty){echo "display:none";}?>">上传支付宝服务窗二维码，用于领券之后推荐用户关注商家服务窗，建议尺寸400*400</span>
                    </div>
                    <div class="filed">
                        <span class="label"></span>
                        <span class="text" style="width:210px">
                        	<img id="alipay_img" style="width: 120px;height: 120px;<?php if(isset($merchant -> alipay_qrcode)){echo "display:none";}?>" src="<?php if(isset($merchant -> alipay_qrcode) && !empty($merchant -> alipay_qrcode)&&!$merchant->all_empty){echo IMG_GJ_LIST.$merchant -> alipay_qrcode;}?>">
                        	<input type="text" id="alipay_qrcode" name="alipay_qrcode" style="display: none;" value="<?php if(isset($merchant -> alipay_qrcode) && !empty($merchant -> alipay_qrcode)){echo $merchant -> alipay_qrcode;}?>">
                    	</span>
                    </div>
                    
                    <div class="filed">
                    	<span class="label"></span>
                    	<span class="text" id="alipay_qrcode_txt" style="<?php if(isset($merchant -> alipay_qrcode)&&!$merchant->all_empty){echo "display:none";}?>"><input type="file" id="qrcode_up" value="上传"></span>
                    </div>

                    <div class="filed">
                    	<span class="text1">
                    		<input type="submit" class="btn_com_gray" value="保存" <?php if(!empty($merchant -> appid) || !empty($merchant->fuwu_name)||!empty($merchant -> alipay_qrcode)){?>style="display: none"<?php }?> id="save">
                    		<input type="button" class="btn_com_gray" value="修改" <?php if(empty($merchant -> appid) && empty($merchant -> fuwu_name)&&empty($merchant -> alipay_qrcode)){?>style="display: none"<?php }?> id="edit">
                    	</span>
                    </div>
                    <span class="text1 red">
            		<?php if (Yii::app()->user->hasFlash('appid')) {
               			echo Yii::app()->user->getFlash('appid');
            		}?>
            		<?php if (Yii::app()->user->hasFlash('fuwu_name')) {
               			echo ' '.Yii::app()->user->getFlash('fuwu_name');
            		}?>
            		</span>
            	</div>
       		</div>
        		
    </div>
</div> 
<?php $form = $this->endWidget()?>

<script>
    $(document).ready(function(){
        var alipay_qrcode=$('#alipay_qrcode').val();
        if(alipay_qrcode!='')
        {
            $('#alipay_qrcode1').show();
            $('#qrcode_txt').show();
            $('#alipay_img').show();
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

		$('#edit').click(function(){
			$('#save').show();
			$('#edit').hide();
			$('input[name=appid]').show();
			$('input[name=fuwu_name]').show();
            $('#fuwu_name_txt').show();
            $('#alipay_qrcode_txt').show();
            $('#qrcode_txt').show();
			$('#appid_text').hide();
			$('#fuwu_name_text').hide();
            $('#alipay_qrcode1').show();
            //窗口高度自适应
            window.parent.callParAutoResize("main",$("body").height());
		});


        $(function () {
            $('#qrcode_up').uploadify({
                uploader: '<?php echo UPLOAD_TO_PATH?>',// 服务器处理地址
                swf: '<?php echo GJ_STATIC_JS?>'+'uploadify/uploadify.swf',
                buttonText: '上传二维码',//按钮文字
                multi:false,
                height: 30,  //按钮高度
                width: 70, //按钮宽度
                fileTypeExts: "*.jpg;*.png;*.bmp;*.jpeg",//允许的文件类型
                fileTypeDesc: "请选择二维码图片", //文件说明
                formData: { 'folder' : "<?php echo(IMG_GJ_FOLDER)?>"}, //提交给服务器端的参数
                onUploadSuccess: function (file, data, response){
                    eval("var jsondata = " + data + ";");
                    var key = jsondata['key'];
                    var fileName = jsondata['fileName'];
                    $("input[name=alipay_qrcode]").val(fileName);
                    $("#alipay_img").attr('src','<?php echo IMG_GJ_LIST?>'+fileName);
                    $("#alipay_img").show();
                    //窗口高度自适应
                    window.parent.callParAutoResize("main",$("body").height());
                }
            });
        });
</script>