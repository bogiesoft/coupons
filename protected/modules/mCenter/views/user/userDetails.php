<script type="text/javascript">
	$(document).ready(main_obj.list_init);
</script>

<body>
<div class="kkfm_r_inner managementD">
	<div class="top">
    	<div class="name">会员详情</div>
    </div>
     
	<div class="contant">
    	<div class="mgHead">
        	<div class="bcgItem clearfix">
        	<?php if(!empty($list['alipay_avatar'])){?>
            	<div class="htcon">
                	<a href="#"><img src="<?php echo $list['alipay_avatar']?>"></a>
                	<div class="name">支付宝头像</div>
                </div>
                <?php }?>
                <?php if(!empty($list['avatar'])){?>
                <div class="htcon">
                	<a href="#"><img src="<?php echo $list['avatar']?>"></a>
                	<div class="name">微信头像</div>
                </div>
                <?php }?>
            </div>
            <div class="bcgItem clearfix">
                <div class="text">
                	<p class="phone"><?php echo isset($list['account']) ? $list['account'] : ''?></p>
                    <p class="name"><?php echo isset($list['name']) ? $list['name'] : ''?></p>
                </div>
            </div>
        </div>
        <div class="body">
        	<div class="filed clearfix">
            	<div class="label">昵称：</div>
                <div class="text"><?php echo isset($list['nickname']) ? $list['nickname'] : ''?></div>
            </div>
            <div class="filed clearfix">
            	<div class="label">性别：</div>
                <div class="text"><?php echo isset($list['sex']) ? $GLOBALS['__SEX'][$list['sex']] : ''?></div>
            </div>
            <div class="filed clearfix">
            	<div class="label">生日：</div>
                <div class="text"><?php echo isset($list['birthday']) ? $list['birthday'] : ''?></div>
            </div>
            <div class="filed clearfix">
            	<div class="label">身份证号：</div>
                <div class="text"><?php echo isset($list['social_security_number']) ? $list['social_security_number'] : ''?></div>
            </div>
            <div class="filed clearfix">
            	<div class="label">邮箱：</div>
                <div class="text"><?php echo isset($list['email']) ? $list['email'] : ''?></div>
            </div>
            <div class="filed clearfix">
            	<div class="label">婚姻状况:</div>
                <div class="text"><?php echo isset($list['marital_status']) ? $GLOBALS['__MARITAL_STATUS'][$list['marital_status']] : ''?></div>
            </div>
            <div class="filed clearfix">
            	<div class="label">工作：</div>
                <div class="text"><?php echo isset($list['work']) ? $list['work'] : ''?></div>
            </div>
            <div class="filed clearfix">
            	<div class="label">地址：</div>
                <div class="text"><?php echo isset($list['address']) ? $list['address'] : ''?></div>
            </div>
            <div class="filed clearfix">
            	<div class="label">来源：</div>
                <div class="text"><?php 
            		$tmp = '';
            		$arr = explode(",", $list['from']);
            		foreach ($arr as $k => $v) {
            			if(empty($v)) continue;
						if (empty($tmp)) $tmp = $GLOBALS['__USER_FROM'][$v];
						else $tmp .= '/'.$GLOBALS['__USER_FROM'][$v];
            		}
            		echo $tmp;
            	?></div>
            </div>
            <div class="filed clearfix">
            	<div class="label">注册时间：</div>
                <div class="text"><?php echo isset($list['regist_time']) ? $list['regist_time'] : ''?></div>
            </div>
            <div class="filed clearfix">
            	<div class="label">最后登录时间：</div>
                <div class="text"><?php echo isset($list['login_time']) ? $list['login_time'] : ''?></div>
            </div>
            <div class="filed clearfix">
            	<div class="label">最后登录客户端：</div>
                <div class="text">支付宝客户端</div>
            </div>
        </div>
   	</div>
</div> 
</body>