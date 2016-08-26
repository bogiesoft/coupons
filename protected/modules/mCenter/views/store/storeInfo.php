<script type="text/javascript">
    $(document).ready(main_obj.list_init);
</script>
<div class="kkfm_r_inner">
    <div class="storeManage">
        <div class="top">
            <div class="name">
                门店管理
            </div>
            <input type="hidden" id="store_success_msg" value="<?php echo Yii::app()->user->hasFlash('success_msg') ? Yii::app()->user->getFlash('success_msg') : ''; ?>">
        </div>
        <div class="searchWrap">
            <div class="search">
                <form method="get" action="">
                    <span>
                        <?php
                        $this->widget('application.modules.mCenter.components.widgets.SelectorWidget', array(
                            'model' => $pca,
                            'attributeProvince' => 'liveplaceProvince',
                            'attributeCity' => 'liveplaceCity',
                            'attributeArea' => 'liveplaceArea',
                            'htmlOptions' => array('class' => 'operate', 'style' => 'height:30px')
                        ));
                        ?>
                    </span>
                    <span>
                	<input type="text" name="keyword" value="<?php echo isset($_GET['keyword']) ? CHtml::encode(trim($_GET['keyword'])) : ''; ?>" placeholder="请输入门店名称" class="txt">
                    <input type="submit" class="storeSh" value="">
                </span>
                </form>
            </div>
            <div class="cz"><input type="submit" value="添加门店" class="btn_com_blue" onclick="fixmenu();location.href='<?php echo Yii::app()->createUrl('mCenter/store/addStore')?>'"></div>
        </div>
        <div class="bus-contant">
            <table cellspacing="0" cellpadding="0" width="100%">
                <tr class="thead">
                    <td width="40%">门店信息</td>
                    <td width="35%">收款账号</td>
                    <td width="10%">口碑开店</td>
                    <td align="center">操作</td>
                </tr>

                <?php if(!empty($list)){ ?>
                    <?php foreach($list as $k => $v){ ?>
                        <tr>
                            <td valign="top">
                                <dl class="list">
                                    <dt><em>编号：</em><?php echo $v['number']; ?></dt>
                                    <dt><em>名称：</em><?php echo $v['name'];if(!empty($v['branch_name'])){echo '-'.$v['branch_name'];}?></dt>
                                    <dt><em>电话：</em><?php echo $v['telephone']; ?></dt>
                                    <dt><em>地址：</em><?php
                                        $add = explode(",", $v['address']);
                                        foreach($add as $value){
                                            echo $value;
                                        }
                                        ?></dt>
                                    <dt>
                                        <?php $tmp_type = Yii::app ()->session ['charge_type']; ?>
                                    	<span class="wq-blue"><?php echo $GLOBALS['__STORE_EDITION'][$v['edition']];?>：</span>
                                    	<?php echo $v['end_time'];?><a href="<?php echo Yii::app()->createUrl('mCenter/install/storeEditionRenewUpgrade');?>" class="renew-link" <?php echo $tmp_type != MERCHANT_CHARGE_TYPE_SELF_YES ? 'style="display:none;"' : ''; ?>>续费升级</a>
                                    </dt>
                                </dl>
                            </td>
                            <td valign="top">
                                <dl class="list">
                                    <dt>支付宝收款&nbsp;&nbsp;<a <?php echo $v['sync_type'] == STORE_ALIPAY_SYNC_TYPE_SYNC || $v['sync_type'] == STORE_ALIPAY_SYNC_TYPE_RELATION ? 'target="_blank"' : ''; ?> href="<?php echo $v['sync_type'] == STORE_ALIPAY_SYNC_TYPE_SYNC || $v['sync_type'] == STORE_ALIPAY_SYNC_TYPE_RELATION ? 'https://e.alipay.com/shop/isvPayeeAccount.htm' : Yii::app()->createUrl('mCenter/store/SetAlipay',array('id' => $v['id'])) ?>"><ins><?php if(!empty($v['alipay'])){echo $v['alipay'];} ?></ins></a></dt>
                                    <dt><a>微信收款&nbsp;</a><a href="<?php echo Yii::app()->createUrl('mCenter/store/SetWechatPay',array('id' => $v['id'])) ?>"><ins><?php if(!empty($v['wechat'])){echo $v['wechat']; }?></ins></a></dt>
                                    <dt><a class="obtainLink">收款二维码</a><a href="javascript:;" onclick="popShow('<?php echo $v['id']?>','<?php echo $v['name']?>','<?php echo $v['branch_name']?>')"><ins>查看</ins></a></dt>
                                </dl>
                            </td >
                            <td valign="top">
                        		<dl class="list">
                            		<dt><?php echo $v['koubei_status']; ?></dt>
                        		</dl>
                    		</td >
                            <td align="center" valign="top">
                                <dl class="operate">
                                    <dt>操作</dt>
                                    <dd>
                                        <a href="<?php echo Yii::app()->createUrl('mCenter/store/editStore', array('id' => $v['id']))?>">编辑</a>
                                        <?php if ($v['alipay_sync_type'] == STORE_ALIPAY_SYNC_TYPE_SYNC && $v['alipay_sync_verify_status'] == STORE_ALIPAY_SYNC_STATUS_PASS){ }else{?>
                                        <a href="<?php echo Yii::app()->createUrl('mCenter/store/deleteStore', array('id' => $v['id']))?>" onclick="if(!confirm('确认删除?')){return false;}">删除</a>
                                        <?php }?>
                                        <a <?php echo $v['sync_type'] == STORE_ALIPAY_SYNC_TYPE_SYNC || $v['sync_type'] == STORE_ALIPAY_SYNC_TYPE_RELATION ? 'target="_blank"' : ''; ?> href="<?php echo $v['sync_type'] == STORE_ALIPAY_SYNC_TYPE_SYNC || $v['sync_type'] == STORE_ALIPAY_SYNC_TYPE_RELATION ? 'https://e.alipay.com/shop/isvPayeeAccount.htm' : Yii::app()->createUrl('mCenter/store/SetAlipay',array('id' => $v['id'])) ?>">支付宝收款账号</a>
                                        <a href="<?php echo Yii::app()->createUrl('mCenter/store/SetWechatPay',array('id' => $v['id'])) ?>">微信收款账号</a>
                                        <a href="javascript:;" onclick="fixmenu2('<?php echo Yii::app()->createUrl('mCenter/operator/addOperator')?>')">添加操作员</a>
                                    </dd>
                                </dl>
                            </td>
                        </tr>
                    <?php } ?>
                <?php }?>
                <tr style="border:none">
                    <td style="border:none;text-align:right" colspan="4">
                        <?php $this -> widget('CLinkPager',array(
                            'pages'=>$pages,
                            'header'=>'共&nbsp;<span class="yellow">'.$pages -> getItemCount().'</span>&nbsp;条&nbsp;',
                            'prevPageLabel' => '上一页',
                            'nextPageLabel'=>'下一页',
                            'maxButtonCount'=>8
                        ));?>
                    </td>
                </tr>
            </table>
            <?php 
                if(empty($list)){
                    echo '<a href="javascript:;" class="blank-tip">尚未添加分组</a>';
                }
            ?>
        </div>
		<!--弹出框-->
		<div class="qcode_pop" style="display: none">
		    <div class="qcode_title">门店收款二维码</div>
		    <span class="close_btn"></span>
		    <div class="qcode_pop_con">
				<ul class="wq_btn_group pay_btn_group">
		       		<li class="active">玩券</li>
		       		<li>支付宝</li>
		       	</ul>
		        <p><img src="" class="qcode_img"></p>
		        <p><a href="javascript:;" class="qcode_dl_btn" >下载后可打印</a></p>
		    </div>
		</div>
		<input type="hidden" id="qrcode-hybrid">
		<input type="hidden" id="qrcode-alipay">
		<input type="hidden" id="download-hybrid">
		<input type="hidden" id="download-alipay">
    </div>
    <script>
    	$(document).ready(function() {
    		var msg = $("#store_success_msg").val();
    		if (msg) {
    			parent.layer.msg(msg, {offset: 0,icon: 1,time:5000});
    		}
    	});
    	var _sid = null;
    	var _main = null;
    	var _branch = null;
    	var _index = null;
    	function popShow(sid, main, branch) {
        	_sid = sid;
        	_main = main;
        	_branch = branch;
        	_index = 0;
        	$(".pay_btn_group li:eq(0)").addClass('active').siblings().removeClass();
    		hybridQrcode(function() {
    			//显示位置计算
            	var offset_y = $(window.parent.document).scrollTop() + 100;
            	var offset_x = 100;
            	var browserW = $(window).width();
            	var browserH = $(window.parent.parent).height();
            	var pop_obj = $(".qcode_pop");
            	var pop_width = pop_obj.width();
            	var pop_height = pop_obj.height();
            	var x = (browserW - pop_width) / 2 + offset_x;
            	var y = (browserH - pop_height) / 2 + offset_y;
            	if(x < 410) {
                	x = 410;
            	}
            	if(y < 300) {
                	y = 300;
            	}
            	//设置显示位置
            	pop_obj.css({'left' : x, 'top' : y});

            	pop_obj.show();
    		});
    	}
    	//混合二维码
    	function hybridQrcode(callback) {
    		var obj1 = $("#qrcode-hybrid");
        	var obj2 = $("#download-hybrid");
    		var url = obj1.val();
        	var download = obj2.val();
        	if(url && download) {
        		//设置二维码图片及下载地址
            	$(".qcode_img").attr('src', url);
            	$(".qcode_dl_btn").attr('href', download);
            	return ;
        	}
        	$.ajax({
                url: '<?php echo(Yii::app()->createUrl('mCenter/store/hybridQrcode'));?>',
                data: {sid : _sid, main : _main, branch : _branch},
                dataType: 'json',
                success: function (data) {
                    if(data.error == 'success') {
                    	url = data.img_url;
                    	download = data.download_url;
                    	obj1.val(url);
                    	obj2.val(download);
                    	//设置二维码图片及下载地址
                    	$(".qcode_img").attr('src', url);
                    	$(".qcode_dl_btn").attr('href', download);
                    	if (callback) callback();
                    }else {
                        alert(data.errMsg);
                    }
                }
            });
    	}
		//支付宝二维码
    	function alipayQrcode(callback) {
        	var obj1 = $("#qrcode-alipay");
        	var obj2 = $("#download-alipay");
    		var url = obj1.val();
        	var download = obj2.val();
        	if(url && download) {
        		//设置二维码图片及下载地址
            	$(".qcode_img").attr('src', url);
            	$(".qcode_dl_btn").attr('href', download);
            	return ;
        	}
        	$.ajax({
                url: '<?php echo(Yii::app()->createUrl('mCenter/store/alipayQrcode'));?>',
                data: {sid : _sid, main : _main, branch : _branch},
                dataType: 'json',
                success: function (data) {
                    if(data.error == 'success') {
                    	url = data.img_url;
                    	download = data.download_url;
                    	obj1.val(url);
                    	obj2.val(download);
                    	//设置二维码图片及下载地址
                    	$(".qcode_img").attr('src', url);
                    	$(".qcode_dl_btn").attr('href', download);
                    	if (callback) callback();
                    }else {
                        alert(data.errMsg);
                    }
                }
            });
    	}
		//点击选择二维码样式
    	$(".pay_btn_group li").click(function() {
    		var index = $(".pay_btn_group li").index(this);
    		if(index == _index) {
        		return;
    		}
    		_index = index;
    		$(this).addClass('active').siblings().removeClass();
    		if(index == 0) {
    			hybridQrcode();
    		}
    		if(index == 1) {
    			alipayQrcode();
    		}
    	});
    	
    	$(".close_btn").click(function() {
    		$(".qcode_pop").hide(0, function() {
        		//清除图片链接和下载链接
    			$(".qcode_img").attr('src', '');
            	$(".qcode_dl_btn").attr('href', '');
            	
            	$("#qrcode-hybrid").val('');
            	$("#qrcode-alipay").val('');
            	$("#download-hybrid").val('');
            	$("#download-alipay").val('');
    		});
    	});

        //更改导航位置
        function fixmenu(){
        	var obj = parent.document.getElementById('left').contentWindow.document.getElementsByClassName('bg');
            var obj2 = obj[0].getElementsByTagName('a');
            for(i=0;i<obj2.length;i++){
                obj2[i].className = '';

                }
            obj2[2].className = 'cur';
        }       
        function fixmenu2(url) {
            var dt = $(".backstage dt:eq(1)", window.parent.document);
			
            dt.next().addClass('bg').siblings().removeClass();
        	dt.next().slideDown('normal',function() {
        		onLeft(url, dt.next().find('a:eq(0)'));
        	}).siblings("dd").hide();
        }
    </script>
</div>