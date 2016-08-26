<script type="text/javascript">
	$(document).ready(main_obj.list_init);
</script>

<?php $form = $this->beginWidget('CActiveForm')?>
<div class="kkfm_r_inner">
    <div class="face">
    	<div class="top">
    	<div class="name">公众号设置</div>
    </div>
        <div class="title">公众号账号绑定说明</div>
        <div class="title-con">
        	<dl>
            	<dd>1. 用户登录到 <span class="black fw"> 微信公众号平台</span>。</dd>
                <dd>2. 在<span class="black fw"> 公众号设置 </span> 菜单中查看，并将<span class="black fw">  微信原始ID 、微信号</span> 及<span class="black fw">  众号类型 </span>填入下面<span class="black fw">  公众号信息</span> 中</dd>
                <dd>3. 在 <span class="black fw"> 开发者中心</span> 菜单可以进行<span class="black fw">  服务器配置</span> 或者<span class="black fw">  修改配置</span> 操作。 </dd>
                <dd>4. 将下方的<span class="black fw"> 接口URL </span>与 <span class="black fw">开接口TOKEN</span> 复制填写到 微信公众号对应表单中 ，<span class="black fw">消息加解密方式</span> 选择 <span class="black fw">明文模式</span> ，提交保存信息。</dd>
                 <dd>5. 复制 <span class="black fw"> 开发者ID </span>中的 <span class="black fw"> AppId </span>和  <span class="black fw">APPSecret </span>填写到下面的输入框中并提交保存。</dd>
            </dl>
        </div>
        <div class="title">URL(服务器地址) 和 Token(令牌)</div>
        <div class="title-con bg">
        	<div class="filed">
        		<span class="label">接口URL</span>
            	<span class="text" id="interface_url_text"><?php echo INTERFACE_URL.$wechat -> encrypt_id?></span>
                <span class="text1"><input type="button" class="btn_com_gray" id="interface_url" value="复制链接" style="width:92px"></span>
        	</div>
            <div class="filed">
        		<span class="label" style="vertical-align:top" >接口TOKEN</span>
            	<span class="text" id="token_text"><?php echo $wechat -> wechat_token?></span>
                <span class="text1" style="vertical-align:top"><input type="button" class="btn_com_gray" id="token" value="复制TOKEN"></span>
        	</div>
        </div>
         <div class="title">公众号信息</div>
        	<div class="title-con bg">
                <div class="filed">
                    <span class="label" id="wechat_id_txt" style="width:80px;">微信原始ID</span>
                    <span class="text"><em id='wechat_id'><?php if(!empty($wechat -> wechat_id)){echo $wechat -> wechat_id;}?></em><input type="text" name="wechat_id" class="txt" style="width:280px;display:none" value="<?php if(!empty($wechat -> wechat_id)){echo $wechat -> wechat_id;}?>"></span>
                </div>
                <div class="filed">
                    <span class="label" id="wechat_txt" style="width:80px;">微信号</span>
                    <span class="text"><em id='wechat'><?php if(!empty($wechat -> wechat)){echo $wechat -> wechat;}?></em><input type="text" name="wechat" class="txt" style="width:280px;display:none" value="<?php if(!empty($wechat -> wechat)){echo $wechat -> wechat;}?>"></span>
                </div>
                <div class="filed">
                    <span class="label" id="wechat_name_txt" style="width:80px;">公众号名称</span>
                    <span class="text"><em id='wechat_name'><?php if(!empty($wechat -> wechat_name)){echo $wechat -> wechat_name;}?></em><input type="text" name="wechat_name" class="txt" style="width:280px;display:none" value="<?php if(!empty($wechat -> wechat_name)){echo $wechat -> wechat_name;}?>"></span>
                </div>
                <div class="filed">
                    <span class="label" id="wechat_qrcode1">二维码</span>
                    <span class="text" id="wechat_qrcode2">上传微信公众号二维码，用于领券之后推荐用户关注商家公众号，建议尺寸400*400</span>
                </div>
                <div class="filed">
                    <span class="text"><input type="text" style="display:none" name="wechat_qrcode" value="<?php if(!empty($wechat -> wechat_qrcode)){echo $wechat -> wechat_qrcode;}?>"></span>
                </div>
                <div class="filed">
                    <span class="label"></span>
                    <span class="text"><img id="qrcode_img" src="<?php echo IMG_GJ_LIST.$wechat -> wechat_qrcode?>" style="width: 120px;height: 120px;"></span>
                </div>
                <div class="filed">
                	<span class="label"></span>
                	<span class="text" id="wechat_qrcode_txt" style="display:none"><input type="file" id="qrcode_up" value="上传"></span>
                </div>
                <div class="filed">
                    <span class="label" style="width:80px">公众号类型</span>
                    <span class="text"> 
                    	<?php foreach ($GLOBALS['__WECHAT_TYPE'] as $k => $v){?>
                    	<label>
                        <input type="radio" name="wechat_type" <?php if(!empty($wechat -> wechat_type) && $wechat -> wechat_type == $k){?>checked<?php }elseif($k == WECHAT_TYPE_SUBSCRIBE){?>checked<?php }?> value="<?php echo $k?>" id="RadioGroup1_0">
                        <?php echo $v?>
                        </label>
                        <?php }?>
                  	 </span>
                </div>
                 <div class="filed">
                    <span class="label" style="width:80px"></span>
                    <span class="text"></span>
                </div>
                <div class="filed">
                    <span class="label" id="wechat_appid_txt" style="width:80px;">AppId</span>
                    <span class="text"><em id='wechat_appid'><?php if(!empty($wechat -> wechat_subscription_appid)){echo $wechat -> wechat_subscription_appid;}?></em><input type="text" name="wechat_subscription_appid" class="txt" style="width:280px;display:none" value="<?php if(!empty($wechat -> wechat_subscription_appid)){echo $wechat -> wechat_subscription_appid;}?>" <?php if(!empty($wechat -> wechat_subscription_appid)){?>style="dispaly:none"<?php }?>></span>
                </div>
                <div class="filed">
                    <span class="label" id="wechat_appsecret_txt" style="width:80px;">AppSecret</span>
                    <span class="text"><em id='wechat_appsecret'><?php if(!empty($wechat -> wechat_subscription_appsecret)){echo $wechat -> wechat_subscription_appsecret;}?></em><input type="text" name="wechat_subscription_appsecret" class="txt" style="width:280px;display:none" value="<?php if(!empty($wechat -> wechat_subscription_appsecret)){echo $wechat -> wechat_subscription_appsecret;}?>" <?php if(!empty($wechat -> wechat_subscription_appsecret)){?>style="dispaly:none"<?php }?>></span>
                </div>
                <div class="filed">
                    <span class="label" style="width:80px"></span>
                    <span class="text" style="width:210px">
                    <input type="button" class="btn_com_gray" value="修改" id="edit" <?php if(empty($wechat -> wechat_subscription_appid)&&empty($wechat->wechat_subscription_appsecret)&&empty($wechat->wechat_name)&&empty($wechat->wechat)&&empty($wechat->wechat_id)&&empty($wechat -> wechat_qrcode)){?>style="display:none"<?php }?>>
                    <input type="submit" class="btn_com_gray" value="保存" id="save" <?php if(!empty($wechat -> wechat_subscription_appid)||!empty($wechat->wechat_subscription_appsecret)||!empty($wechat->wechat_name)||!empty($wechat->wechat)||!empty($wechat->wechat_id)||!empty($wechat -> wechat_qrcode)){?>style="display:none"<?php }?>>
                    </span>
                </div>  
          </div>
        	<div class="title"></div>	
    </div>
</div>
<?php if (Yii::app()->user->hasFlash('error')) { ?>
    <script>alert('<?php echo Yii::app()->user->getFlash('error')?>')</script>
<?php }?>
<?php $form = $this->endWidget()?>

<script>
        $(document).ready(function(e){
            var id=$('input[name=wechat_id]').val();
            var wechat=$('input[name=wechat]').val();
            var appid=$('input[name=wechat_subscription_appid]').val();
            var appsecret=$('input[name=wechat_subscription_appsecret]').val();
            var name=$('input[name=wechat_name]').val();
            var qrcode=$('input[name=wechat_qrcode]').val();
            if(id==''&&wechat==''&&appid==''&&appsecret==''&&name==''&&qrcode=='')
            {
                $('input[name=wechat_id]').show();
                $('#wechat_id_txt').show();
                $('#wechat_id').hide();
                $('input[name=wechat]').show();
                $('#wechat_txt').show();
                $('#wechat').hide();
                $('input[name=wechat_subscription_appid]').show();
                $('#wechat_name_txt').show();
                $('#wechat_appid').hide();
                $('input[name=wechat_subscription_appsecret]').show();
                $('#wechat_appid_txt').show();
                $('#wechat_appsecret').hide();
                $('input[name=wechat_name]').show();
                $('#wechat_appsecret_txt').show();
                $('#wechat_name').hide();
//                $('input[name=wechat_qrcode]').show();
                $('#wechat_qrcode11').show();
                $('#wechat_qrcode2').show();
//                $('#wechat_qrcode_txt').show();
            }
        });
        //复制网关
		$('#interface_url').zclip({
			path:"<?php echo GJ_STATIC_JS?>ZeroClipboard.swf",
			copy:function(){
				return $('#interface_url_text').text();
			},
			afterCopy:function(){/* 复制成功后的操作 */
		            alert('成功复制到剪切板，Ctrl+V即可粘贴');
		        }
			});
		//复制开发者公钥
		$('#token').zclip({
			path:"<?php echo GJ_STATIC_JS?>ZeroClipboard.swf",
			copy:function(){
				return $('#token_text').text();
			},
			afterCopy:function(){/* 复制成功后的操作 */
		            alert('成功复制到剪切板，Ctrl+V即可粘贴');
		        }
			});

		$('#edit').click(function(){
			$('#save').show();
			$('#edit').hide();
			$('input[name=wechat_id]').show();
            $('#wechat_id_txt').show();
			$('#wechat_id').hide();
			$('input[name=wechat]').show();
            $('#wechat_txt').show();
			$('#wechat').hide();
			$('input[name=wechat_subscription_appid]').show();
            $('#wechat_name_txt').show();
			$('#wechat_appid').hide();
			$('input[name=wechat_subscription_appsecret]').show();
            $('#wechat_appid_txt').show();
			$('#wechat_appsecret').hide();
			$('input[name=wechat_name]').show();
            $('#wechat_appsecret_txt').show();
			$('#wechat_name').hide();

            $('#wechat_qrcode1').show();
            $('#wechat_qrcode2').show();
            $('#wechat_qrcode_txt').show();
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
                    $("input[name=wechat_qrcode]").val(fileName);
                    $("#qrcode_img").attr('src','<?php echo IMG_GJ_LIST?>'+fileName);
                    $("#qrcode_img").show();
                    //窗口高度自适应
                    window.parent.callParAutoResize("main",$("body").height());
                }
            });
        });

</script> 


