<script type="text/javascript">
function resolve(obj)
{
	var w = window.screen.width;
	var h = window.screen.height;
// 	window.open(obj, '微信公众号授权-微信公众平台', 'height='+h+',width='+w+',top=0,left=0,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no');
	window.open(obj,'big,fullscreen=yes');
}
</script>
<?php 
$service_type_info = array(
    0 => '订阅号',
    1 => '由历史老帐号升级后的订阅号',
    2 => '服务号',
);
$verify_type_info = array(
    -1 => '未认证',
    0 => '微信认证',
    1 => '新浪微博认证',
    2 => '腾讯微博认证',
    3 => '已资质认证通过但还未通过名称认证',
    4 => '已资质认证通过、还未通过名称认证，但通过了新浪微博认证',
    5 => '已资质认证通过、还未通过名称认证，但通过了腾讯微博认证',
);
$business_info = array(
    'open_store'=>'微信门店功能',
    'open_scan'=>'微信扫商品功能',
    'open_pay'=>'微信支付功能',
    'open_card'=>'微信卡券功能',
    'open_shake'=>'微信摇一摇功能',
);
$open_status = array(
    0 => '未开通',
    1 => '已开通',
);


?>


<?php if ($merchant->wechat_thirdparty_authorizer_if_auth == 2){?>
<div class="kkfm_r_inner">
    <div class="face">
    	<div class="top">
    	<div class="name">恭喜您，已经授权完成！</div>
    </div>
         <div class="title">公众号信息</div>
        	<div class="title-con bg">
                <div class="filed">
                    <span class="label" id="wechat_id_txt" style="width:80px;">微信原始ID</span>
                    <span class="text"><em id="wechat_id"><?php echo $merchant['wechat_thirdparty_authorizer_info']['user_name']?></em><input type="text" name="wechat_id" class="txt" style="width:280px;display:none" value="gh_a3b8574d88b4"></span>
                </div>
                <div class="filed">
                    <span class="label" id="wechat_txt" style="width:80px;">微信号</span>
                    <span class="text"><em id="wechat"><?php echo $merchant['wechat_thirdparty_authorizer_info']['alias']?></em></span>
                </div>
                <div class="filed">
                    <span class="label" id="wechat_name_txt" style="width:80px;">公众号名称</span>
                    <span class="text"><em id="wechat_name"><?php echo $merchant['wechat_thirdparty_authorizer_info']['nick_name']?></em><input type="text" name="wechat_name" class="txt" style="width:280px;display:none" value="牛牛科技"></span>
                </div>
                <div class="filed">
                    <span class="label" id="wechat_qrcode1">二维码</span>
                    <span class="text"><img id="qrcode_img" src="http://open.weixin.qq.com/qr/code/?username=<?php echo $merchant['wechat_thirdparty_authorizer_info']['alias']?>" style="width: 120px;height: 120px;"></span>
                </div>
                <div class="filed">
                    <span class="label" style="width:80px">公众号类型</span>
                    <span class="text"><?php echo $service_type_info[$merchant['wechat_thirdparty_authorizer_info']['service_type_info']['id']]?></span>
                </div>
                <div class="filed">
                    <span class="label" style="width:80px">认证详情</span>
                    <span class="text"><?php echo $verify_type_info[$merchant['wechat_thirdparty_authorizer_info']['verify_type_info']['id']]?></span>
                </div>
                <div class="filed">
                    <span class="label" style="width:80px">插件状态</span>
                    <span class="text"><?php 
                    foreach ($merchant['wechat_thirdparty_authorizer_info']['business_info'] as $k => $v)
                    {
                        echo $business_info[$k].'('.$open_status[$v].')、';
                    }
                    ?></span>
                </div>
                <div class="filed">
                    <span class="label" style="width:80px">AppId</span>
                    <span class="text"><?php echo $merchant['wechat_thirdparty_authorization_info']['authorizer_appid']?></span>
                </div>
                <div class="filed">
                    <span class="label" style="width:80px">ReToken</span>
                    <span class="text"><?php echo $merchant->wechat_thirdparty_authorizer_refresh_token?></span>
                </div>
                
                <div class="filed">
                    <span class="label" style="width:80px">授权时间</span>
                    <span class="text"><?php echo $merchant->wechat_thirdparty_authorizer_time?></span>
                </div>
                
          </div>
    </div>
</div>


<?php }else{?>
<div class="kkfm_r_inner">
        <div class="storeManage">
            <div class="top">
                <div class="name">
                    微信授权
                </div>
            </div>
            <div class="unauthorized">
                <div class="unauthorizedTip">
                    <h3>微信授权：未授权</h3>
                    <p>将公众号授权给玩券，开始运营</p>
                </div>
            </div>
            <div class="authoriz clearfix">
                <div class="goauthoriz goauthoriz-wechat">
                    <h3>绑定微信公众号</h3>
                    <p>为保证您在玩券平台功能的正常使用，授权时请保持默认选择，把权限统一授权给玩券。</p>
                    <a href="javascript:void(0)" onclick="javascript:resolve('<?php echo $clickUrl;?>')" class="authorize-btn"><span>微信公众号登录授权</span></a>
                    
                </div>
            </div>
        </div>
    </div>
<?php }
?>