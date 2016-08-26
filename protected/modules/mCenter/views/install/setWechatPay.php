<script type="text/javascript">
    $(document).ready(main_obj.list_init);
</script>
<?php $form = $this->beginWidget('CActiveForm')?>
<div class="kkfm_r_inner">
    <div class="face wxPaySet">
        <div class="top">
            <div class="name">微信支付设置</div>
        </div>
        <div class="title">微信支付设置说明</div>
        <div class="title-con">
            <dl>
                <dd>1. 用户登录到 <span class="black fw"> 微信公众号平台</span>（mp.weixin.qq.com）。</dd>
                <dd>2. 在<span class="black fw"> 开发者中心-开发者ID </span> 菜单中将<span class="black fw">AppID(应用ID)</span>和<span class="black fw">  AppSecret(应用密钥)</span>填写到下方输入框中。</dd>
                <dd>3. 用户登录到 <span class="black fw"> 微信支付商户平台</span>（pay.weixin.qq.com）。</dd>
                <dd>4. 在 <span class="black fw"> 账户概览-基础信息</span>菜单中，将<span class="black fw"> 微信支付商户号</span>填写到下方输入框中。</dd>
                <dd>5. 在<span class="black fw"> 账户设置-API安全 </span> 菜单中，<span class="black fw">API密钥-设置密钥</span>，将<span class="black fw">  API密钥</span>填入下方输入框中，并提交保存。</dd>
            </dl>
        </div>
        <div class="bussniessType">
        	<div class="title">商户类型</div>
            <div class="title-con">
                <div class="type" style="padding: 5px 0 5px 20px;">
                	<input type="radio" name="busType" id="ordinary" value="1" <?php echo $data['wechat_type'] == WXPAY_MERCHANT_TYPE_SELF ? 'checked' : '';?>> <label for="ordinary">普通商户</label>
                    <input type="radio" name="busType" id="special" value="2" <?php echo $data['wechat_type'] == WXPAY_MERCHANT_TYPE_AFFILIATE ? 'checked' : '';?>> <label for="special">特约商户</label>
                </div>
                <p style="padding: 5px 0 5px 20px;">商户类型可在<a target="_blank" href="https://pay.weixin.qq.com/index.php/account/info"><em class="blue">微信支付商户平台</em></a>-账号设置-账户信息中查看</p>
            </div>
        </div>
        <script>
        	$(".bussniessType input").click(function(e) {
				//当选中特约商户的单选框时，证书上传部分隐藏
                if($(this).prop("checked") && $(this).attr("id") == 'special'){
					$(".certUpload").hide();
					$(".payInfo .filed:eq(0)").hide();
					$(".payInfo .filed:eq(1)").hide();
					$(".payInfo .filed:eq(2)").hide();
					$(".payInfo .filed:eq(3)").hide();
					$(".payInfo .filed:eq(4)").show();
					$(".payInfo .filed:eq(5)").show();
				}else{
					$(".payInfo .filed:eq(0)").show();
					$(".payInfo .filed:eq(1)").show();
					$(".payInfo .filed:eq(2)").show();
					$(".payInfo .filed:eq(3)").show();
					$(".payInfo .filed:eq(4)").hide();
					$(".payInfo .filed:eq(5)").hide();
					$(".certUpload").show();
				}
				//刷新高度
                parent.callParAutoResize('main', $("body").height());
            });
        </script>
        
        <div class="certUpload" <?php echo $data['wechat_type'] == WXPAY_MERCHANT_TYPE_AFFILIATE ? 'style="display:none;"' : '';?>>
            <div class="title">证书上传</div>
            <div class="title-con ">
                <div class="filed">
                    <?php if(isset($data['wechat_apiclient_cert'])&&!empty($data['wechat_apiclient_cert'])){?>
                    <span class="label"><a href="#" class="btn_com_gray" id="cert_upload">重新上传</a></span>
                    <span class="text" id="refile_name">apiclient_cert.pem</span>
                    <span class="succeed1" id="file_cert_success_state">已上传</span>
                    <?php }
                    else{?>
                        <span class="label"><a href="#" class="btn_com_gray" id="cert_upload">点击上传</a></span>
                        <span class="text" id="refile_name">apiclient_cert.pem</span>
                        <span class="error1" id="file_cert_error_state">请上传apiclient_cert.pem</span>
                    <?php }?>
                </div>
                <div class="filed">
                    <?php if(isset($data['wechat_apiclient_key'])&&!empty($data['wechat_apiclient_key'])){?>
                        <span class="label"><a href="#" class="btn_com_gray" id="key_upload">重新上传</a></span>
                        <span class="text" id="file_name">apiclient_key.pem</span>
                        <span class="succeed1" id="file_key_success_state">已上传</span>
                    <?php }
                    else{?>
                        <span class="label"><a href="#" class="btn_com_gray" id="key_upload">点击上传</a></span>
                        <span class="text" id="file_name">apiclient_key.pem</span>
                        <span class="error1" id="file_key_error_state">请上传apiclient_key.pem</span>
                    <?php }?>
                </div>
            </div>
        </div>

        <div class="payInfo">
	        <div class="title">微信支付信息</div>
	        <div class="title-con bg">
	
	            <div class="filed" <?php echo $data['wechat_type'] == WXPAY_MERCHANT_TYPE_AFFILIATE ? 'style="display:none;"' : '';?>>
	                <span class="label" style="width:150px">AppID(应用ID)</span>
	            <span class="text">
	            <input type="tel" class="txt" id="appid" name="appid" value="<?php echo isset($_POST['wechat_appid']) ? $_POST['appid']  :  $data['wechat_appid']?>" placeholder="请输入AppID" style="width:500px;">
	            </span>
	        	<span class="text1 red">
	            <?php if (Yii::app()->user->hasFlash('appid')) {
	                echo Yii::app()->user->getFlash('appid');
	            }?>
	            </span>
	            </div>
	
	            <div class="filed" <?php echo $data['wechat_type'] == WXPAY_MERCHANT_TYPE_AFFILIATE ? 'style="display:none;"' : '';?>>
	                <span class="label" style="width:150px">AppSecret(应用密钥)</span>
	            <span class="text">
	            <input type="tel" class="txt" name="appsecret" id="appsecret" value="<?php echo isset($_POST['appsecret']) ? $_POST['appsecret']  :  $data['wechat_appsecret']?>" placeholder="(选填)请输入AppSecret，用于公众号支付" style="width:500px;">
	            </span>
	        	<span class="text1 red">
	            <?php if (Yii::app()->user->hasFlash('appsecret')) {
	                echo Yii::app()->user->getFlash('appsecret');
	            }?>
	            </span>
	            </div>
	
	            <div class="filed" <?php echo $data['wechat_type'] == WXPAY_MERCHANT_TYPE_AFFILIATE ? 'style="display:none;"' : '';?>>
	                <span class="label" style="width:150px">微信支付商户号</span>
	            <span class="text">
	            <input type="tel" class="txt" name="mchid" id="mchid" value="<?php echo isset($_POST['mchid']) ? $_POST['mchid']  :  $data['wechat_mchid']?>" placeholder="请输入微信mchid" style="width:500px;">
	            </span>
	        	<span class="text1 red">
	            <?php if (Yii::app()->user->hasFlash('mchid')) {
	                echo Yii::app()->user->getFlash('mchid');
	            }?>
	            </span>
	            </div>
	            
	            <div class="filed" <?php echo $data['wechat_type'] == WXPAY_MERCHANT_TYPE_AFFILIATE ? 'style="display:none;"' : '';?>>
	                <span class="label" style="width:150px">API密钥</span>
	            <span class="text">
	            <input type="tel" class="txt" name="key" id="key" value="<?php echo isset($_POST['key']) ? $_POST['key']  :  $data['wechat_key']?>" placeholder="请输入微信key" style="width:500px;">
	            </span>
	        	<span class="text1 red">
	            <?php if (Yii::app()->user->hasFlash('key')) {
	                echo Yii::app()->user->getFlash('key');
	            }?>
	            </span>
	            </div>
	            
	            <div class="filed" <?php echo $data['wechat_type'] == WXPAY_MERCHANT_TYPE_SELF ? 'style="display:none;"' : '';?>>
	                <span class="label" style="width:150px">AppID(应用ID)</span>
	            <span class="text">
	            <input type="tel" class="txt" id="t_appid" name="t_appid" value="<?php echo isset($_POST['t_appid']) ? $_POST['t_appid']  :  $data['t_wx_appid']?>" placeholder="请输入AppID（选填）" style="width:500px;">
	            </span>
	        	<span class="text1 red">
	            <?php if (Yii::app()->user->hasFlash('t_appid')) {
	                echo Yii::app()->user->getFlash('t_appid');
	            }?>
	            </span>
	            </div>
	            
	            <div class="filed" <?php echo $data['wechat_type'] == WXPAY_MERCHANT_TYPE_SELF ? 'style="display:none;"' : '';?>>
	                <span class="label" style="width:150px">微信支付商户号</span>
	            <span class="text">
	            <input type="tel" class="txt" name="t_mchid" id="t_mchid" value="<?php echo isset($_POST['t_mchid']) ? $_POST['t_mchid']  :  $data['t_wx_mchid']?>" placeholder="请输入微信mchid" style="width:500px;">
	            </span>
	        	<span class="text1 red">
	            <?php if (Yii::app()->user->hasFlash('t_mchid')) {
	                echo Yii::app()->user->getFlash('t_mchid');
	            }?>
	            </span>
	            </div>
	            
	            <div class="filed">
	                <span class="label" style="width:80px"></span>
	                    <span class="text" style="width:210px">
	                    <input type="submit" class="btn_com_gray" value="保存" id="save">
	                    </span>
	            </div>
	        </div>
        </div>
    </div>
</div>
<input style="display: none" id="wechat_appid" value="<?php echo $data['wechat_appid'];?>">
<input style="display: none" id="wechat_mchid" value="<?php echo $data['wechat_mchid'];?>">
<?php $form = $this->endWidget()?>

<script type="text/javascript">

    $(function () {
        $('#key_upload').uploadify({
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
                    url:'<?php echo Yii::app()->createUrl('mCenter/install/uploadSuccess');?>',
                    cache:false,
                    data:{fileName:"<?php echo $dir_name?>",type:file.name},
                    type:'post',
                    async: false,
                    success:function(data){
                        var res=eval(data);
                        if(res==<?php echo ERROR_NONE;?>)
                        {
                            alert("上传成功");
//                             window.location.reload();
                        }
                        else
                        {
                            if(document.getElementById('file_key_success_state'))
                            {
                                document.getElementById('file_key_success_state').setAttribute('class',"error1");
                                document.getElementById('file_key_success_state').innerText="请上传apiclient_key.pem";
                            }
                            else
                            {
                                document.getElementById('file_key_error_state').innerText="请上传apiclient_key.pem";
                            }
                            alert("上传失败");
                        }
                    }
                });
            }
        });
    });
    $(function () {
        $('#cert_upload').uploadify({
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
                  url:'<?php echo Yii::app()->createUrl('mCenter/install/uploadSuccess');?>',
                  data:{fileName:"<?php echo $dir_name?>",type:file.name},
                  type:'post',
                  async: false,
                    success:function(data){
                        var res=eval(data);
                        if(res==<?php echo ERROR_NONE;?>)
                        {
                            alert("上传成功");
//                             window.location.reload();
                        }
                        else
                        {
                            if(document.getElementById('file_cert_success_state'))
                            {
                                document.getElementById('file_cert_success_state').setAttribute('class',"error1");
                                document.getElementById('file_cert_success_state').innerText="请上传apiclient_cert.pem";
                            }
                            else
                            {
                                document.getElementById('file_cert_error_state').innerText="请上传apiclient_cert.pem";
                            }
                            alert("上传失败");
                        }

                  }
              });
            }

        });
    });
</script>

