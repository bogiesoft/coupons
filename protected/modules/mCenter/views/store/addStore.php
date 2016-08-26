<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=HZSlrGaVD5Cca3GbkwtPDXxx"></script>
<script type="text/javascript" src="<?php echo GJ_STATIC_JS?>imgupload.js"></script>
<link type="text/css" rel="stylesheet" href="<?php echo GJ_STATIC_STYLES?>jquery.datetimepicker.css" />
<script type="text/javascript" src="<?php echo GJ_STATIC_JS?>js_gj/jquery.datetimepicker.js"></script> 
<link type="text/css" rel="stylesheet" href="<?php echo GJ_STATIC_STYLES?>master.css" />
<body>
    <script type="text/javascript">
    $(document).ready(main_obj.list_init);
    </script>
    <div class="kkfm_r_inner">
        <div class="top">
            <div class="name">添加门店</div>
            <input type="hidden" id="add_store_err_msg" value="<?php echo Yii::app()->user->hasFlash('other_error') ? Yii::app()->user->getFlash('other_error') : ''; ?>">
        </div>
        <div class="contant">
        <?php echo CHtml::beginForm() ?>
            <div class="basicInfo">
                <span class="title">同步支付宝口碑门店</span>
                <div class="filed">
                    <div class="syncInfo">
                        <input type="checkbox" id="checkboxN" onclick="setcheckboxN()" <?php echo $model['sync_type'] == STORE_ALIPAY_SYNC_TYPE_SYNC ? 'checked' : '';?>>
                        <label for="checkboxN">口碑签约开店一体化</label>
                        <p>开店并同时签约口碑当面付，口碑泛行业可在支付宝手机客户端-口碑栏目中展示门店</p>
                    </div>
                    <div class="syncInfo">
                        <input type="checkbox" id="checkboxY" onclick="setcheckboxY()" <?php echo $model['sync_type'] == STORE_ALIPAY_SYNC_TYPE_RELATION ? 'checked' : '';?>>
                        <label for="checkboxY">关联已有支付宝口碑门店</label>
                        <p>关联门店，请保证门店编号与支付宝口碑中的门店编号一致。</p>
                    </div>
                    <?php echo CHtml::hiddenField('Store[sync_type]', $model['sync_type'])?>
                </div>
            </div>
            <div class="basicInfo">
                <span class="title">基础信息</span>
                <div class="filed">
                    <span class="label"><em class="red">*</em>门店名称</span>
                    <span class="text">
                <?php echo CHtml::textField('Store[name]', $model['name'], array('class' => 'txt txt1', 'placeholder' => '主门店名，例如：海底捞', 'maxlength' => '20'))?>
             </span>
             <span class="text">
             	<?php echo CHtml::textField('Store[branch_name]', $model['branch_name'], array('class' => 'txt txt1', 'placeholder' => '分店名，例如：延安店', 'maxlength' => '20'))?>
             </span>
                    <span class="text1 red">
                    <?php if (Yii::app()->user->hasFlash('name_error')) {
             			echo Yii::app()->user->getFlash('name_error');
             		}?>
                    </span>
                </div>
                <div class="filed">
                    <span class="label"><em class="red">*</em>所属分组</span>
                    <span class="text">
                <select class="choose1" id="management">
                	<option value="">-请选择-</option>
                	<?php foreach ($pList as $k => $v){?>
                    	<option value ="<?php echo $v -> id?>" <?php echo $pid==$v->id? 'selected':''; ?>><?php echo $v -> name?></option>
                    <?php }?>
                </select>
                <select class="choose1" id="submanagement">
                    <option value="">-请选择-</option>
                    <?php foreach ($cList as $k => $v){?>
                    	<option value ="<?php echo $v -> id?>" <?php echo $model['management_id']==$v->id? 'selected':''; ?>><?php echo $v -> name?></option>
                    <?php }?>
                </select>
                <?php echo CHtml::hiddenField('Store[management_id]', $model['management_id'])?>
             </span>
                </div>
                <div class="filed">
                    <span class="label"><em class="red">*</em>门店编号</span>
                    <span class="text">
                <?php echo CHtml::textField('Store[number]', $model['number'], array('class' => 'txt txt1', 'placeholder' => '请输入门店编号'))?>
             </span>
                    <span class="text1 red">
                    <?php if (Yii::app()->user->hasFlash('number_error')) {
             			echo Yii::app()->user->getFlash('number_error');
             		}?>
                    </span>
                </div>
                <div class="filed">
                    <span class="label"><em class="red">*</em>门店电话</span>
                    <span class="text">
                <?php echo CHtml::textField('Store[area_code]', $model['area_code'], array('class' => 'txt', 'placeholder' => '区号', 'style'=>'width:155px;margin-right:10px'))?>
                <?php echo CHtml::textField('Store[telephone]', $model['telephone'], array('class' => 'txt', 'placeholder' => '电话或手机号码', 'style'=>'width:320px'))?>
                
             </span>
                    <span class="text1 red">
                    <?php if (Yii::app()->user->hasFlash('tel_error')) {
             			echo Yii::app()->user->getFlash('tel_error');
             		}?>
                    </span>
                </div>
                <div class="filed">
                    <span class="label"><em class="red">*</em>商户地址</span>
                    <span class="text">
                    <select class="choose choose1" id="province" name="Store[province]">
                    	<option value="">-请选择-</option>
                    	<?php foreach ($province as $k => $v){?>
                    	<option value="<?php echo $v -> code?>" <?php echo $v->code == $model['province'] && !empty($v->code) ? 'selected' : '';?>><?php echo $v-> name?></option>
                    	<?php }?>
                	</select>
                	<select class="choose choose1" id="city" name="Store[city]">
                		<option value="">-请选择-</option>
                		<?php foreach ($city as $k => $v){?>
                    	<option value="<?php echo $v -> code?>" <?php echo $v->code == $model['city'] && !empty($v->code) ? 'selected' : '';?>><?php echo $v-> name?></option>
                    	<?php }?>
                	</select>
                	<select class="choose choose1" id="area" name="Store[area]">
                		<option value="">-请选择-</option>
                		<?php foreach ($area as $k => $v){?>
                    	<option value="<?php echo $v -> code?>" <?php echo $v->code == $model['area'] && !empty($v->code) ? 'selected' : '';?>><?php echo $v-> name?></option>
                    	<?php }?>
                	</select>
                	<?php echo CHtml::hiddenField('Store[detail_address]', $model['detail_address'])?>
             </span>
                    <span class="text1 red">
                    <?php if (Yii::app()->user->hasFlash('pca_error')) {
             			echo Yii::app()->user->getFlash('pca_error');
             		}?>
                    </span>
                </div>
                <div class="filed">
                    <span class="label"><em class="red">*</em>详细地址</span>
                    <span class="text">
                	<?php echo CHtml::textField('Store[address]', $model['address'], array('class' => 'txt', 'placeholder' => '最多50个汉字', 'maxlength' => '150'))?>
             		</span>
             		<span class="text1 red">
                    <?php if (Yii::app()->user->hasFlash('address_error')) {
             			echo Yii::app()->user->getFlash('address_error');
             		}?>
                    </span>
                </div>
                <div class="filed">
                    <span class="label"><em class="red">*</em>地图坐标</span>
                    <span class="text">
                	<?php echo CHtml::textField('Store[lngAndLat]', $model['lngAndLat'], array('class' => 'txt', 'placeholder' => '请点击定位获取地图坐标，或直接输入经纬度（中间用英文逗号隔开）', 'style' => 'width:400px;margin-right:10px'))?>
                	<input type="button" class="btn_com_gray" value="获取坐标" id="getlocation">
             		</span>
             		<span class="text1 red">
                    <?php if (Yii::app()->user->hasFlash('coor_error')) {
             			echo Yii::app()->user->getFlash('coor_error');
             		}?>
                    </span>
                </div>
                <div class="filed">
                    <span class="label"></span>
                    <span class="text" id="map_container"  style="width: 520px; height: 300px"></span>
                </div>
            </div>
            <div class="basicInfo" id="sync_info" <?php echo $model['sync_type']==STORE_ALIPAY_SYNC_TYPE_SYNC || $model['sync_type']==STORE_ALIPAY_SYNC_TYPE_RELATION?'':'style="display:none"'; ?>>
                <span class="title">同步门店信息</span>
                <div class="filed">
                    <span class="label"><em class="red">*</em>支付宝门店编号</span>
                    <span class="text">
                <?php echo CHtml::textField('Store[alipay_store_id]', $model['alipay_store_id'], array('class' => 'txt', 'placeholder' => '请输入门店编号','readonly' => 'readonly'))?>
                <div class="tip" id="tips" <?php echo $model['sync_type']==STORE_ALIPAY_SYNC_TYPE_SYNC?'style="display:none"':'';?>>
                 将上面的的支付宝门店编号填写到 <a href="https://e.alipay.com/shop/query.htm" target="_blank">口碑商家中心-门店管理-我的门店</a> 编号中
             </div>
             </span>
                    <span class="text1 red">
                    <?php if (Yii::app()->user->hasFlash('store_id_error')) {
             			echo Yii::app()->user->getFlash('store_id_error');
             		}?>
                    </span>
                </div>
                <div id="sync_data" <?php echo $model['sync_type']==STORE_ALIPAY_SYNC_TYPE_RELATION?'style="display:none"':''; ?>>
                <div class="filed">
                    <span class="label"><em class="red">*</em>门店首图</span>
                    <span class="text">
                 <div class="tip">大小: 不超过10M。格式: bmp, png, jpeg, jpg, gif。建议尺寸在 2000px*1500px 以上（更容易通过审核）<br>
可首选优质菜品图作为首图，首图会在手机支付宝中重点展示
</div>
                <input type="button" class="btn_com_white" value="上传文件" id="upload_mdst">
                <div class="upImg">
                	<div class="img" <?php echo empty($model['first_img'])?'style="display: none"':''; ?> id="img_mdst"><em class="close" id="del_img_mdst"></em><img src="<?php echo !empty($model['first_img']) ? KOUBEI_IMAGE_URL.$model['first_img'] : '';?>"></div>
                	<?php echo CHtml::hiddenField('Store[first_img]', $model['first_img'])?>
                </div>
             </span>
             		<span class="text1 red">
                    <?php if (Yii::app()->user->hasFlash('main_img_error')) {
             			echo Yii::app()->user->getFlash('main_img_error');
             		}?>
                    </span>
                </div>
                <div class="filed">
                    <span class="label"><em class="red">*</em>门店实景图</span>
                    <span class="text">
                <div class="tip">大小: 不超过10M。格式: bmp, png, jpeg, jpg, gif。建议尺寸在 2000px*1500 px 以上（更容易通过审核）<br>
上传照片中需要包含一张门头照片，两张内景照，如无门头照片将会审核失败
</div>
                <input type="button" class="btn_com_white" value="上传文件" id="upload_mdsjt">
                <div class="upImg" id="upImg_mdsjt">
					<?php if (!empty($audit_image)) {
						foreach ($audit_image as $img) { if(!empty($img)){?>
							<div class="img">
								<em class="close" onclick="delImgMdsjt(this)"></em>
								<img src="<?php echo KOUBEI_IMAGE_URL.$img?>">
							</div>
							<input type="hidden" value="<?php echo $img?>" name="image_mdsjt[]">
						<?php }}?>
					<?php }?>
                </div>

                </span>
                	<span class="text1 red">
                    <?php if (Yii::app()->user->hasFlash('audit_img_error')) {
             			echo Yii::app()->user->getFlash('audit_img_error');
             		}?>
                    </span>
                </div>
                <div class="filed store-regions">
                    <span class="label"><em class="red">*</em>品类</span>
                    <span class="text">
                    	<?php echo CHtml::textField('Store[category]', $model['category'], array('class' => 'txt', 'readonly' => 'readonly'))?>
                    	<?php echo CHtml::hiddenField('Store[category_id]', $model['category_id'])?>
                    	<div class="regions-wrap">
						</div>
                    </span>
                    <span class="text1 red">
                    <?php if (Yii::app()->user->hasFlash('category_error')) {
             			echo Yii::app()->user->getFlash('category_error');
             		}?>
                    </span>
                </div>
                <div class="filed">
                    <span class="label">营业执照号</span>
                    <span class="text">
                <?php echo CHtml::textField('Store[licence_code]', $model['licence_code'], array('class' => 'txt', 'placeholder' => '请输入营业执照号'))?>
             </span>
                </div>
                <div class="filed">
                    <span class="label">营业执照名称</span>
                    <span class="text">
                <?php echo CHtml::textField('Store[licence_name]', $model['licence_name'], array('class' => 'txt', 'placeholder' => '请输入营业执照名称'))?>
             </span>
                </div>
                <div class="filed">
                    <span class="label">营业执照</span>
                    <span class="text">
                <div class="tip">请上传证件清晰彩色扫描件或照片
</div>
                <input type="button" class="btn_com_white" value="上传文件" id="upload_yyzz">
                <div class="upImg">
                	<div class="img" <?php echo empty($model['business_license'])?'style="display: none"':''; ?> id="img_yyzz"><em class="close" id="del_img_yyzz"></em><img src="<?php echo !empty($model['business_license']) ? KOUBEI_IMAGE_URL.$model['business_license'] : '';?>"></div>
                	<?php echo CHtml::hiddenField('Store[business_license]', $model['business_license'])?>
                </div>
                </span>
                </div>
                <div class="filed">
                    <span class="label">经营许可证照片</span>
                    <span class="text">
                <div class="tip">请上传证件清晰彩色扫描件或照片
</div>
                <input type="button" class="btn_com_white" value="上传文件" id="upload_jyxkz">
                <div class="upImg">
                	<div class="img" <?php echo empty($model['business_certificate'])?'style="display: none"':''; ?> id="img_jyxkz"><em class="close" id="del_img_jyxkz"></em><img src="<?php echo !empty($model['business_certificate']) ? KOUBEI_IMAGE_URL.$model['business_certificate'] : '';?>"></div>
                	<?php echo CHtml::hiddenField('Store[business_certificate]', $model['business_certificate'])?>
                </div>
                </span>
                </div>
                <div class="filed">
                    <span class="label">经营许可证有效期</span>
                    <span class="text">
                <?php echo CHtml::textField('Store[business_certificate_expires]', $model['business_certificate_expires'], array('class' => 'txt', 'placeholder' => '请输入经营许可证有效期,格式：2016-01-20'))?>
             </span>
                </div>
                <div class="filed">
                    <span class="label">授权函</span>
                    <span class="text">
                <div class="tip">如门店营业执照与签约账户主体不一致，需上传授权函
</div>
                <input type="button" class="btn_com_white" value="上传文件" id="upload_sqh"><label><a href="http://pan.baidu.com/s/1gf0RttD" target="_blank">下载授权函</a></label>
                <div class="upImg">
                	<div class="img" <?php echo empty($model['auth_letter'])?'style="display: none"':''; ?> id="img_sqh"><em class="close" id="del_img_sqh"></em><img src="<?php echo !empty($model['auth_letter']) ? KOUBEI_IMAGE_URL.$model['auth_letter'] : '';?>"></div>
                	<?php echo CHtml::hiddenField('Store[auth_letter]', $model['auth_letter'])?>
                </div>
                </span>
                </div>
                <div class="filed divider">
                    <span class="label">更多信息(选填)</span>
                    <span class="text">
                    <span class="line"></span>
                    </span>
                </div>
                <div class="filed">
                    <span class="label">营业时间</span>
                    <span class="text">
                <ul class="time">
                    <li><a href="javascript:;">周一</a></li>
                    <li><a href="javascript:;">周二</a></li>
                    <li><a href="javascript:;">周三</a></li>
                    <li><a href="javascript:;">周四</a></li>
                    <li><a href="javascript:;">周五</a></li>
                    <li><a href="javascript:;">周六</a></li>
                    <li><a href="javascript:;">周日</a></li>
                    <?php echo CHtml::hiddenField('Store[day]', $model['day'])?>
                    <li class="lastLi">            
                    	<?php echo CHtml::textField('Store[start]', $model['start'], array('style' => 'width:35px','name'=>'start_time','id'=>"datetimepicker1"))?>                       
                    	<?php echo CHtml::textField('Store[end]', $model['end'], array('style' => 'width:35px','name'=>'end_time','id'=>"datetimepicker2"))?>                   
                    </li>
                </ul>
             </span>
                </div>
                <div class="filed">
                    <span class="label">品牌名</span>
                    <span class="text">
                    <?php echo CHtml::textField('Store[brand]', $model['brand'], array('class' => 'txt', 'placeholder' => '不填默认为其他品牌'))?>
             </span>
                </div>
                <div class="filed">
                    <span class="label">品牌 logo</span>
                    <span class="text">
                <div class="tip">大小: 不超过10M。格式: bmp, png, jpeg, jpg, gif。建议尺寸在 500px*500px 以上。不填默认使用门店首图
</div>
                <input type="submit" class="btn_com_white" value="上传文件" id="upload_logo">
                <div class="upImg">
                	<div class="img" <?php echo empty($model['logo'])?'style="display: none"':''; ?> id="img_logo"><em class="close" id="del_img_logo"></em><img src="<?php echo !empty($model['logo']) ? KOUBEI_IMAGE_URL.$model['logo'] : '';?>"></div>
                	<?php echo CHtml::hiddenField('Store[brand_logo]', $model['logo'])?>
                </div>
                </span>
                </div>
                <div class="filed">
                    <span class="label">手机号</span>
                    <span class="text">
                	<?php echo CHtml::textField('Store[phone_num]', $model['phone_num'], array('class' => 'txt'))?>
                <div class="tip">用于接收门店状态变更通知，收款成功通知等通知消息， 不在客户端展示</div>
             </span>
                </div>
                <div class="filed">
                    <span class="label">人均消费</span>
                    <span class="text">
                <?php echo CHtml::textField('Store[per_capita]', $model['per_capita'], array('class' => 'txt'))?>
             </span>
                </div>
            </div>
            </div>
            <div class="basicInfo" id="lastbasicInfo">
                <div class="filed">
                    <span class="label"></span>
                    <span class="text">
                <input type="submit" class="btn_com_blue"  value="保存">
                <input type="button" class="btn_com_gray"  value="取消"
                       onclick="fixmenu();location.href='<?php echo Yii::app()->createUrl('mCenter/store/storeInfo')?>';">
             </span>
                </div>
            </div>
        <?php echo CHtml::endForm() ?>
        </div>
    </div>
</body>

<script type="text/javascript">
	$(document).ready(function() {
		var msg = $("#add_store_err_msg").val();
		if (msg) {
			parent.layer.msg(msg, {offset: 0,icon: 0,time:5000});
		}
	});
    //选择同步创建口碑门店
	function setcheckboxN(){
		if($('#checkboxN').is(':checked')){
			$.ajax({
	            url: '<?php echo(Yii::app()->createUrl('mCenter/Store/checkAuthToken'));?>',
	            type: 'get',
	            success: function (data) {
	              	if(data == 'yes') {
	              		$('#checkboxY').removeAttr("checked");
	        			$("#sync_data").show();
	        			$("#sync_info").show();
	        			$("#tips").hide(autoResize);
	        			$("#Store_sync_type").val('1');
	              	}else {
	              		$('#checkboxN').prop('checked', false);
		              	alert('您还未进行授权操作');
	              	}
	            }
	        });
		}else {
			if(!$('#checkboxY').is(':checked')) {
				$("#Store_sync_type").val('3');
				$("#sync_info").hide(autoResize);
			}
		}
	}
	//选择关联已有门店
	function setcheckboxY(){
		if($('#checkboxY').is(':checked')){
			$('#checkboxN').removeAttr("checked");
			$("#sync_info").show();
			$("#tips").show();
			$("#sync_data").hide(autoResize);
			$("#Store_sync_type").val('2');
		}else {
			if(!$('#checkboxN').is(':checked')) {
				$("#Store_sync_type").val('3');
				$("#sync_info").hide(autoResize);
			}
		}
	}

	//选择门店分组
	$('#management').change(function(){
		var management = $(this).val();
		$('#Store_management_id').val(management);
		$("#submanagement").empty();
		$("#submanagement").append('<option value="">-请选择-</option>');
		$.ajax({
	            url:'<?php echo Yii::app()->createUrl('mCenter/Store/GetSubManagement')?>',
	            data:{management_id:management},
	            type:'GET',
	            dataType:'json',
	            success:function(data){
// 	            	alert(JSON.stringify(data));
	                if(data.status==<?php echo STATUS_SUCCESS?>){
		                for(var i=0;i<data.data.length;i++){
			                $("#submanagement").append("<option value='"+data.data[i]['id']+"'>"+data.data[i]['name']+"</option>");
			            }
	                }
	            }
	  	});
	});
	$('#submanagement').change(function() {
		var mid = $(this).val();
		$('#Store_management_id').val(mid);
	});
	

	/****************baidu map *********************/

	var map = new BMap.Map("map_container");
	var p_lng = '<?php if(isset($model['lngAndLat'])&&$model['lngAndLat']){ $tmp=explode(",", $model['lngAndLat']);echo $tmp[0];}else{echo 116.404;}?>';
	var p_lat = '<?php if(isset($model['lngAndLat'])&&$model['lngAndLat']){ $tmp=explode(",", $model['lngAndLat']);echo $tmp[1];}else{echo 39.915;}?>';
	var point = new BMap.Point(p_lng, p_lat);
	var marker = new BMap.Marker(point); // 创建标注
	map.addOverlay(marker); // 将标注添加到地图中
	map.centerAndZoom(point, 15);
	map.enableScrollWheelZoom(true);//启用滚轮放大缩小
	map.addControl(new BMap.NavigationControl());
	
	map.addEventListener("click", showInfo);
	//点击地图获得经纬度
	function showInfo(e) {
	    map.clearOverlays();    //清除地图上所有覆盖物
	    point = new BMap.Point(e.point.lng, e.point.lat);
	    marker = new BMap.Marker(point); // 创建标注
	    map.addOverlay(marker); // 将标注添加到地图中
	    //map.removeEventListener("click", showInfo);
	    updateCoordinate(point.lng,point.lat); //更新坐标
	}
	
	// 百度地图API功能
	function G(id) {
	    return document.getElementById(id);
	}
	

	
	var myValue;

	
	function setPlace() {
	    map.clearOverlays();    //清除地图上所有覆盖物
	    function myFun() {
	        var pp = local.getResults().getPoi(0).point;    //获取第一个智能搜索的结果
	        updateCoordinate(pp.lng,pp.lat); //更新坐标
	        map.centerAndZoom(pp, 18);
	        map.addOverlay(new BMap.Marker(pp));    //添加标注
	    }
	
	    var local = new BMap.LocalSearch(map, { //智能搜索
	        onSearchComplete: myFun
	    });
	    local.search(myValue);
	}

	//点击定位按钮
    $('#getlocation').click(function () {
        if ($("#area").find("option:selected").val() == '') {
            alert('请选择省市区');
            return false;
        }
        if ($("#Store_address").val() == '') {
            alert('详细地址不能为空');
            return false;
        }
        //获取地址
        myValue = $("#province").find("option:selected").text() + $("#city").find("option:selected").text() + $("#area").find("option:selected").text() + $("#Store_address").val();
		//定位
        setPlace();
        return false;
    });

	//更新输入框坐标信息
	function updateCoordinate(lng,lat) {
		$("#Store_lngAndLat").val(lng + "," + lat);
	}

	/********************end*************************/

	//营业时间加载
	$(function() {
		var day = '<?php echo $model['day']?>';
		var arr = day.split('');
		$(".time li").each(function(n) {
			if(n < 7) {
				if(arr[n] == '1') {
					$(this).addClass('li0');
				}
			}
		});
	});
	//营业时间选择
	$(".time li").click(function() {
		var p = $(this);
		if(p.children("a").length == 0) {
			return;
		}
		if(p.hasClass('li0')) {
			p.removeClass();
		}else {
			p.addClass('li0');
		}
		var day = '';
		$(".time li").each(function(n) {
			if(n < 7) {
				if($(this).hasClass('li0')) {
					day += '1';
				}else {
					day += '0';
				}
			}
		});
		$("#Store_day").val(day);
	});

    //营业时间
	$('#datetimepicker1').datetimepicker({
		datepicker:false,
		format:'H:i',
		step:5
	});
	$('#datetimepicker2').datetimepicker({
		datepicker:false,
		format:'H:i',
		step:5
	});

	//类目选择
    var category_data = <?php echo json_encode($GLOBALS['__ALIPAY_KOUBEI_STORE_ALL_CATEGORY']);?>;
    $('#Store_category').click(function(event) {
		event.stopImmediatePropagation(); //阻止不隐藏
		var txt = $(this).val();
		var child = $('.regions-wrap ul').length;
		if(!txt || !child) {
			var list = '<ul class="regions" data-ul="0">';
			$.each(category_data, function(index, category) {
				var item = '<li data-id="'+category.id+'"><a href="javascript:;">'+category.name+'</a></li>';
				list += item;
			});
			list += '</ul>';
			$('.regions-wrap').html(list);
		}
		$('.regions-wrap').show();
	});

	//点击其他区域隐藏
	$(document).on('click',function() {
		$('.regions-wrap').hide();
	});

	$(document).on('click', '.regions-wrap ul li', function(event) {
		event.stopImmediatePropagation(); //阻止不隐藏
		var id = $(this).attr('data-id');
		var cname = '';
		var indexArr = (getPicker(category_data, id)+",-1,-2").split(",", 3);
		//var indexArr = indexStr.split(",");

		var html = '';
		var data = category_data;
		var next = '';
		for (var i=0; i<indexArr.length; i++) {
			if(indexArr[i] == '-2' || !data) {
				break;
			}
			//节点列表
			var list = '<ul class="regions" data-ul="0">';
			$.each(data, function(index, category) {
				var curClass = '';
				if(indexArr[i] == index) {
					data = category.categoryList;
					cname += category.name + '，';
					curClass = 'cur';
				}
				var item = '<li data-id="'+category.id+'" class="'+curClass+'"><a href="javascript:;">'+category.name+'</a></li>';
				list += item;
			});
			list += '</ul>';

			html += list;
		}
		$('.regions-wrap').html(html);
		//选定类目
		if(!data) {
			cname = cname.substring(0,cname.length-1);
			$("#Store_category").val(cname);
			$("#Store_category_id").val(id);
			$('.regions-wrap').hide();
		}
	});

	function getPicker(data, id) {
		var grade = '';
		for(var i=0; i<data.length; i++) {
			if(data[i].id == id) {
				grade = i;
				break;
			}
			var sub = data[i].categoryList;
			if (!sub) {
				continue;
			}
			var index = getPicker(sub, id);
			if(index !== '') {
				grade += i+','+index;
			}
		}
		return grade;
	}

	/************************图片上传*************************************/
	var mdst_count = 0;//门店首图上传图片数量
	var mdsjt_count = 0;//门店实景图上传图片数量
	var yyzz_count = 0;//营业自找上传数量
	var sqh_count = 0;//授权函上传数量
	var logo_count = 0;//品牌logo上传数量
	//上传图片到口碑商家中心
	function uploadToAlipay(path, callback) {
		$.ajax({
            url: '<?php echo(Yii::app()->createUrl('mCenter/store/uploadImage'));?>',
            data: {img_path: path},
            dataType: 'json',
            success: function (data) {
                if(data.error == 'success') {
                    image_id = data.image_id;
                    callback(image_id);
                }else {
                	alert(data.errMsg);
                }
            }
        });
	}
	
	 $(function () {
		 //门店首图上传 1张
		    $('#upload_mdst').uploadify({
		    	onInit: function () {$(".uploadify-queue").hide();},//载入时触发，将flash设置到最小
		        uploader: '<?php echo UPLOAD_TO_PATH?>',// 服务器处理地址
		        swf: '<?php echo GJ_STATIC_JS?>'+'uploadify/uploadify.swf',
		        buttonText: "选择图片",//按钮文字
		        height: 34,  //按钮高度
		        width: 82, //按钮宽度
		        fileTypeExts: "<?php echo UPLOAD_IMG_TYPE;?>",//允许的文件类型
		        fileTypeDesc: "请选择图片文件", //文件说明
		        formData: { 'folder' : '<?php echo(IMG_GJ_FOLDER)?>'}, //提交给服务器端的参数
		        onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
              		//alert('上传图片成功');
		            eval("var jsondata = " + data + ";");
		            var key = jsondata['key'];
		            var fileName = jsondata['fileName'];
		            uploadToAlipay(fileName, function(image_id) {
			            //设置图片地址，设置图片加载完成后高度自适应
		            	$('#img_mdst').find('img').attr({src:'<?php echo KOUBEI_IMAGE_URL;?>'+image_id, onload:'autoResize()'});
			            $('#img_mdst').show();
			            $('#Store_first_img').val(image_id);
		            });
		        }
		    });
			//门店实景图上传 3张
		    $('#upload_mdsjt').uploadify({
		    	onInit: function () {$(".uploadify-queue").hide();},//载入时触发，将flash设置到最小
		        uploader: '<?php echo UPLOAD_TO_PATH?>',// 服务器处理地址
		        swf: '<?php echo GJ_STATIC_JS?>'+'uploadify/uploadify.swf',
		        buttonText: "选择图片",//按钮文字
		        height: 34,  //按钮高度
		        width: 82, //按钮宽度
		        fileTypeExts: "<?php echo UPLOAD_IMG_TYPE;?>",//允许的文件类型
		        fileTypeDesc: "请选择图片文件", //文件说明
		        formData: { 'folder' : '<?php echo(IMG_GJ_FOLDER)?>'}, //提交给服务器端的参数
		        onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
              		//alert('上传图片成功');
		            eval("var jsondata = " + data + ";");
		            var key = jsondata['key'];
		            var fileName = jsondata['fileName'];
		            uploadToAlipay(fileName, function(image_id) {
		            	var contant = '<div class="img"><em class="close" onclick="delImgMdsjt(this)"></em><img src="'+'<?php echo KOUBEI_IMAGE_URL?>'+image_id+'" onload="autoResize()"></div>'+
			            '<input name="image_mdsjt[]" value="'+image_id+'" style="display: none">';
	    				$('#upImg_mdsjt').append(contant);
	    				//setTimeout(autoResize, 1000);
		            });
		        }
		    });
			//营业执照上传 1张
	 		$('#upload_yyzz').uploadify({
		    	onInit: function () {$(".uploadify-queue").hide();},//载入时触发，将flash设置到最小
		        uploader: '<?php echo UPLOAD_TO_PATH?>',// 服务器处理地址
		        swf: '<?php echo GJ_STATIC_JS?>'+'uploadify/uploadify.swf',
		        buttonText: "选择图片",//按钮文字
		        height: 34,  //按钮高度
		        width: 82, //按钮宽度
		        fileTypeExts: "<?php echo UPLOAD_IMG_TYPE;?>",//允许的文件类型
		        fileTypeDesc: "请选择图片文件", //文件说明
		        formData: { 'folder' : '<?php echo(IMG_GJ_FOLDER)?>'}, //提交给服务器端的参数
		        onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
		    		//alert('上传图片成功');
			        eval("var jsondata = " + data + ";");
			        var key = jsondata['key'];
			        var fileName = jsondata['fileName'];
			        uploadToAlipay(fileName, function(image_id) {
		            	$('#img_yyzz').find('img').attr({src:'<?php echo KOUBEI_IMAGE_URL;?>'+image_id, onload:'autoResize()'});
			            $('#img_yyzz').show(autoResize);
			            $('#Store_business_license').val(image_id);
		            });
	        	}
	    	});
	 		//经营许可证上传 1张
	 		$('#upload_jyxkz').uploadify({
		    	onInit: function () {$(".uploadify-queue").hide();},//载入时触发，将flash设置到最小
		        uploader: '<?php echo UPLOAD_TO_PATH?>',// 服务器处理地址
		        swf: '<?php echo GJ_STATIC_JS?>'+'uploadify/uploadify.swf',
		        buttonText: "选择图片",//按钮文字
		        height: 34,  //按钮高度
		        width: 82, //按钮宽度
		        fileTypeExts: "<?php echo UPLOAD_IMG_TYPE;?>",//允许的文件类型
		        fileTypeDesc: "请选择图片文件", //文件说明
		        formData: { 'folder' : '<?php echo(IMG_GJ_FOLDER)?>'}, //提交给服务器端的参数
		        onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
		    		//alert('上传图片成功');
			        eval("var jsondata = " + data + ";");
			        var key = jsondata['key'];
			        var fileName = jsondata['fileName'];
			        uploadToAlipay(fileName, function(image_id) {
		            	$('#img_jyxkz').find('img').attr({src:'<?php echo KOUBEI_IMAGE_URL;?>'+image_id, onload:'autoResize()'});
			            $('#img_jyxkz').show(autoResize);
			            $('#Store_business_certificate').val(image_id);
		            });
	        	}
	    	});
			//授权函上传 1张
	 		$('#upload_sqh').uploadify({
		    	onInit: function () {$(".uploadify-queue").hide();},//载入时触发，将flash设置到最小
		        uploader: '<?php echo UPLOAD_TO_PATH?>',// 服务器处理地址
		        swf: '<?php echo GJ_STATIC_JS?>'+'uploadify/uploadify.swf',
		        buttonText: "选择图片",//按钮文字
		        height: 34,  //按钮高度
		        width: 82, //按钮宽度
		        fileTypeExts: "<?php echo UPLOAD_IMG_TYPE;?>",//允许的文件类型
		        fileTypeDesc: "请选择图片文件", //文件说明
		        formData: { 'folder' : '<?php echo(IMG_GJ_FOLDER)?>'}, //提交给服务器端的参数
		        onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
		    		//alert('上传图片成功');
			        eval("var jsondata = " + data + ";");
			        var key = jsondata['key'];
			        var fileName = jsondata['fileName'];
			        uploadToAlipay(fileName, function(image_id) {
		            	$('#img_sqh').find('img').attr({src:'<?php echo KOUBEI_IMAGE_URL;?>'+image_id, onload:'autoResize()'});
			            $('#img_sqh').show(autoResize);
			            $('#Store_auth_letter').val(image_id);
		            });
	        	}
	    	});
			//品牌logo上传 1张
	 		$('#upload_logo').uploadify({
		    	onInit: function () {$(".uploadify-queue").hide();},//载入时触发，将flash设置到最小
		        uploader: '<?php echo UPLOAD_TO_PATH?>',// 服务器处理地址
		        swf: '<?php echo GJ_STATIC_JS?>'+'uploadify/uploadify.swf',
		        buttonText: "选择图片",//按钮文字
		        height: 34,  //按钮高度
		        width: 82, //按钮宽度
		        fileTypeExts: "<?php echo UPLOAD_IMG_TYPE;?>",//允许的文件类型
		        fileTypeDesc: "请选择图片文件", //文件说明
		        formData: { 'folder' : '<?php echo(IMG_GJ_FOLDER)?>'}, //提交给服务器端的参数
		        onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
		    		//alert('上传图片成功');
			        eval("var jsondata = " + data + ";");
			        var key = jsondata['key'];
			        var fileName = jsondata['fileName'];
			        uploadToAlipay(fileName, function(image_id) {
		            	$('#img_logo').find('img').attr({src:'<?php echo KOUBEI_IMAGE_URL;?>'+image_id, onload:'autoResize()'});
			            $('#img_logo').show(autoResize);
			            $('#Store_logo').val(image_id);
		            });
	        	}
	    	});
	});

		//门店首图删除
	 	$('#del_img_mdst').click(function(){
		 	$(this).next().attr('src', '');
		 	$(this).parent().hide();
		 	$('#Store_first_img').val('');
		 	autoResize();
		});
		//删除门店实景图
	 	function delImgMdsjt(obj){
		 	$(obj).parent().next().remove();
		 	$(obj).parent().remove();
			autoResize();
	 	}
	 	//营业执照删除
	 	$('#del_img_yyzz').click(function(){
		 	$(this).next().attr('src', '');
		 	$(this).parent().hide();
		 	$('#Store_business_license').val('');
		 	autoResize();
		});
		//经营许可证删除
	 	$('#del_img_jyxkz').click(function(){
		 	$(this).next().attr('src', '');
		 	$(this).parent().hide();
		 	$('#Store_business_certificate').val('');
		 	autoResize();
		});
	 	//授权函删除
	 	$('#del_img_sqh').click(function(){
		 	$(this).next().attr('src', '');
		 	$(this).parent().hide();
		 	$('#Store_auth_letter').val('');
		 	autoResize();
		});
	 	//品牌logo
	 	$('#del_img_logo').click(function(){
		 	$(this).next().attr('src', '');
		 	$(this).parent().hide();
		 	$('#Store_logo').val('');
		 	autoResize();
		});


		//重设高度
		function autoResize(){
			window.parent.callParAutoResize("main",$("body").height());
		}
		

		/***********************************选择地址**************************************/
		//选择省 
	   	$('#province').change(function(){
			var code = $(this).val();
			$('#city').empty();
			$('#city').append('<option value="">-请选择-</option>');
			$('#area').empty();
			$('#area').append('<option value="">-请选择-</option>');
			$.ajax({
	            url: '<?php echo(Yii::app()->createUrl('mCenter/Store/GetCity'));?>',
	            data: {code:code},
	            type: 'post',
	            dataType: 'json',
	            success: function (data) {
	              	for(var i=0 ; i<data.length;i++){
	                  	var code = data[i].split(",")[1];
	              		var name = data[i].split(",")[0];
	                  	var text = '<option value="'+code+'">'+name+'</option>';
	                	$('#city').append(text);
	             	}
	            }
	        });
	        return false;
	 	});

		  //选择市
	   	$('#city').change(function(){
			var code = $(this).val();
			$('#area').empty();
			$('#area').append('<option value="">-请选择-</option>');
			$.ajax({
	            url: '<?php echo(Yii::app()->createUrl('mCenter/Store/GetArea'));?>',
	            data: {code:code},
	            type: 'post',
	            dataType: 'json',
	            success: function (data) {
	              	for(var i=0 ; i<data.length;i++){
	                  	var code = data[i].split(",")[1];
	              		var name = data[i].split(",")[0];
	                  	var text = '<option value="'+code+'">'+name+'</option>';
	                	$('#area').append(text);
	             	}
	            }
	        });
			return false;
	 	});

	 	$("#area").change(function() {
	 		var pca = $("#province").find("option:selected").text() +','+ $("#city").find("option:selected").text() +','+ $("#area").find("option:selected").text();
          	$("#Store_detail_address").val(pca);
	 	});

        //更改导航位置
        function fixmenu(){
        	var obj = parent.document.getElementById('left').contentWindow.document.getElementsByClassName('bg');
            var obj2 = obj[0].getElementsByTagName('a');
            for(i=0;i<obj2.length;i++){
                obj2[i].className = '';

                }
            obj2[1].className = 'cur';
        }  
	
</script>
