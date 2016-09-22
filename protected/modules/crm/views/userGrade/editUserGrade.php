<script src="<?php echo GJ_STATIC_JS."gj.js"?>"></script>
<div class="kkfm_r_inner">
    <?php echo CHtml::beginForm();?>
        <div class="top">
            <div class="name">修改会员等级</div>
        </div>
        <div class="contant clearfix membership">
            <div class="coupon_l level_l">
                <div class="title">优惠券</div>
                <div class="hd">
                    <?php $images = explode('/', $model['membercard_img']);
                        $count = count($images);?>
                    <img src="<?php echo $count == 2 ? IMG_GJ_LIST.$model['membercard_img'] : GJ_STATIC_IMAGES.'card/'.$model['membercard_img']?>" id="cover_img">
                    <div class="hd_inner">
                        <h3 id="membership_card_name"><?php echo $model['membership_card_name']?></h3>
                        <span id="dengji_card_name"><?php echo $model['name']?></span>
                    </div>
                </div>
                <div class="barCode">
                    <img src="<?php echo GJ_STATIC_IMAGES?>code.png">
                    <p>123121513123</p>
                </div>
                <div class="detail">
                    <dl>
                        <dt>会员特权</dt>
                        <dd class="discount"><?php echo substr($model['discount']*10,0,3)?>折</dd>
                    </dl>
                    <dl>
                        <dt>使用说明</dt>
                        <div class="dd"><?php echo  str_replace(PHP_EOL, '', $model['discount_illustrate']);?></div>
                    </dl>
                </div>
            </div>
            <div class="coupon_r level_r">
                <div class="bg">
                    <div class="section">
                        <div class="title">会员等级</div>
                    </div>
                    <div class="section">
                        <dl class="clearfix">
                            <dt class="especial">会员等级名称</dt>
                            <dd>
                                <?php echo CHtml::textField('UserGrade[name]',isset($_POST['UserGrade']['name']) ? $_POST['UserGrade']['name'] : $model['name'],array('class'=>'txt','id'=>'addUserGrade_usergradename','placeholder'=>'请输入会员等级名称','maxlength'=>'9'))?>
                                <span class="text1 red" id="name">&nbsp;  
                                    <?php if(Yii::app()->user->hasFlash('name')) { ?>                                                      
                                        <?php echo Yii::app()->user->getFlash('name');?>
                                    <?php } ?>
                                </span>
                                <div class="remark">
                                    填写会员等级名称，最多9个汉字
                                </div>
                            </dd>
                        </dl>
                        <dl class="clearfix">
                            <dt class="especial">会员特权</dt>
                            <dd>
                                <?php echo CHtml::textField('UserGrade[discount]', isset($_POST['UserGrade']['discount']) ? substr($_POST['UserGrade']['discount'],0,3) : substr($model['discount']*10,0,3),array('class'=>'txt','id'=>'usergradediscount','placeholder'=>''))?>
                                <span class="text1 red" id="discount">
                                    <?php if(Yii::app()->user->hasFlash('discount')) { ?>
                                        <?php echo Yii::app()->user->getFlash('discount');?>
                                    <?php } ?>
                                </span>
                                <div class="remark"> 
                                    请输入会员折扣,1到9.9之间，保留一位小数，不填默认为无会员特权                                
                                </div>
                            </dd>
                        </dl>
                        <dl class="clearfix">
                            <dt class="especial">积分规则</dt>
                            <dd>
                                <?php echo CHtml::textField('UserGrade[points_ratio]',isset($_POST['UserGrade']['points_ratio']) ? $_POST['UserGrade']['points_ratio'] : $model['points_ratio'],array('class'=>'txt','id'=>'usergradepoints_ratio','placeholder'=>'','maxlength'=>'10'));?>
                                <span class="text1 red" id="points_ratio">
                                    <?php if(Yii::app()->user->hasFlash('points_ratio')) { ?>
                                    <?php echo Yii::app()->user->getFlash('points_ratio');?>
                                    <?php } ?>
                                </span>
                                <div class="remark">
								请输入该等级的每会员消费1元获得的积分数（大于0，不满1积分不计积分）
                                </div>
                            </dd>
                        </dl>   

                        <dl class="clearfix">
                            <dt class="especial">生日积分</dt>
                            <dd>
                                <?php echo CHtml::textField('UserGrade[birthday_rate]',isset($_POST['UserGrade']['birthday_rate']) ? $_POST['UserGrade']['birthday_rate'] : $model['birthday_rate']*100/100,array('class'=>'txt','id'=>'usergradebirthday_rate','placeholder'=>'','maxlength'=>'10'))?>
                                <span class="text1 red" id="birthday_rate">&nbsp;  
                                    <?php if(Yii::app()->user->hasFlash('birthday_rate')) { ?>
                                        <?php echo Yii::app()->user->getFlash('birthday_rate');?>
                                    <?php } ?>
                                </span>
                                <div class="remark">请输入到达该等级会员生日当天所获得的积分倍率，不填默认为1倍</div>
                            </dd>
                        </dl>                        
                        <dl class="clearfix">
                            <dt>会员等级条件</dt>
                            <dd class="member_level" id="js_level">
                                <label>
                                    <?php echo CHtml::radioButton('UserGrade[rule_type]',0,array('title'=>'累计积分','value'=>'1',  'id'=>'points1','onclick'=>'showlayer()'))?>
                 	          	累计积分</label>
                 	          	<label>
                                    <!--<?php echo CHtml::radioButton('UserGrade[rule_type]',0,array('title'=>'年累计积分','value'=>'2', 'id'=>'points2','onclick'=>'showlayer()'))?>
                 	          	年累计积分</label>
                 	          	<label>
                                    <?php echo CHtml::radioButton('UserGrade[rule_type]',0,array('title'=>'累计消费金额','value'=>'3', 'id'=>'points3','onclick'=>'showlayer()'))?>
                 	          	累计消费金额</label>
                 	          	<label>
                                    <?php echo CHtml::radioButton('UserGrade[rule_type]',0,array('title'=>'年累计消费金额','value'=>'4', 'id'=>'points4','onclick'=>'showlayer()'))?>
                 	          	年累计消费金额</label>-->
                                <div class="layer">
                                    <i class="arrow_top"></i>
                                    <span>客户达到该等级需要<em class="js_title">累计积分</em>达到</span>
									<?php if($_GET['k'] == USER_GRADE_DEFAULT_YES) { ?>
                                		0
                                	<?php } else { ?>
		                                <?php echo CHtml::textField('UserGrade[points_rule]',isset($_POST['UserGrade']['points_rule']) ? $_POST['UserGrade']['points_rule'] : $model['points_rule'],array('class'=>'txt','id'=>'usergradepoints_rule','placeholder'=>'','maxlength'=>'10'))?>
		                                <span class="text1 red" id="points_rule">&nbsp;  
		                                    <?php if(Yii::app()->user->hasFlash('points_rule')) { ?>
		                                        <?php echo Yii::app()->user->getFlash('points_rule');?>
		                                    <?php } ?>
		                                </span>
                                    <p class="tips">达到该等级需满足<em class="js_title"></em>的要求，会员等级有效期为一年</p>
                                </div>
                                <span class="text1 red" id="rule_type">&nbsp;  
                                    <?php if(Yii::app()->user->hasFlash('rule_type')) { ?>
                                        <?php echo Yii::app()->user->getFlash('rule_type');?>
                                    <?php } ?>
                                </span>       
                                <?php }?>                         
                            </dd>
                        </dl>
                        
                    </div>
                    <div class="section">
                        <div class="title">会员卡</div>
                        <dl class="clearfix">
                            <dt class="especial">会员卡名称</dt>
                            <dd>
                                <input type="text" class="txt" maxlength="9" name="card_name" id="card_name" value="<?php echo isset($_POST['card_name']) ? $_POST['card_name'] : $model['membership_card_name']?>" placeholder="请输入会员卡名称">
                                <span class="text1 red" id="span_cardname">
                                    <?php if(Yii::app()->user->hasFlash('card_name')) { ?>
                                        <?php echo Yii::app()->user->getFlash('card_name');?>
                                    <?php } ?>
                                </span>
                            </dd>
                        </dl>
                        <dl class="clearfix">
                            <dt class="especial">会员卡样式</dt>
                            <dd>                                
                                <div class="rank"><a href="javascript:void(0);" onclick="showpop()" class="btn_border" value="选择样式">选择样式</a></div>
                                <div class="rank">
                                    <input type="file" class="upload" id="upload" value="上传照片">
                                    <em>图片建议尺寸 640*320 px</em>                    
                                    <input name="cover" value="" style="display: none"> 
                                    <span class="text1 red">&nbsp;
                                        <?php if(Yii::app()->user->hasFlash('cover')) { ?>
                                            <?php echo Yii::app()->user->getFlash('cover');?>
                                        <?php } ?>
                                    </span>
                                </div>
                            </dd>
                        </dl>
                        <dl class="clearfix">
                            <dt class="especial">效果预览</dt>
                            <dd class="preview">
                                <input type="checkbox" onclick="hideword()" name="if_hideword" id="if_hideword" value="<?php echo IF_HIDEWORD_YES?>" <?php if($model['if_hideword'] == IF_HIDEWORD_YES){?>checked<?php }?>>只显示会员卡样式
                            </dd>
                        </dl>
                        <dl class="clearfix">
                            <dt class="especial">使用说明</dt>
                            <dd>
                                <?php echo CHtml::textArea('UserGrade[discount_illustrate]',isset($_POST['UserGrade']['discount_illustrate']) ? $_POST['UserGrade']['discount_illustrate'] : $model['discount_illustrate'],array('class'=>'usedas','id'=>'illustrate'));?>
                            </dd>
                        </dl>
                    </div>
                </div>
                <div class="btnWrap" style="margin-bottom: 300px">
                    <?php echo CHtml::submitButton('保存',array('class'=>'btn_com_blue'))?>                    
                    <input type="button" class="btn_com_gray" onclick="javascript:history.back();" value="返回">
                </div>
            </div>
        </div>
    <?php echo CHtml::endForm();?>
        <!--会员卡弹出框start-->
        <div class="popWrap new_popWrap" id="pop" style="display:none">
            <div class="pop_con">
                <div class="title">
                    选择会员卡样式
                </div>
                <div class="pop_content">
                    <div class="cardStyle">
                        <?php foreach ($GLOBALS['__XZYS'] as $key => $value) { ?>
                        <div class="item">
                            <div class="img">
                                <div class="img">
                                    <a href="javascript:;" onclick="addUserGrade_downs(<?php echo $key?>)" >
                                        <img src="<?php echo GJ_STATIC_IMAGES.'card/'.$value['img']?>">
                                    </a>
                                </div>
                            </div>
                            <div class="card-ico"></div>
                        </div>
                        <?php } ?>  
                        <script>
                            $(".cardStyle .item").click(
                                function(){
                                        $(".cardStyle .item").removeClass("cardHover")
                                        $(this).addClass("cardHover")
                                })
                        </script>
                    </div>
                    <div class="btn level_r">
                        <input type="submit" class="btn_com_blue" onclick="hiddenpop()" value="确定">
                        <input type="submit" class="btn_com_gray" onclick="card()" value="返回">
                    </div>
                </div>
        </div>
    </div>
    <!--会员卡弹出框end-->
    <script>
        $(document).ready(function(){
      	   window.parent.callParAutoResize("main",$("body").height());
           
            var length=$('#illustrate').val().length;
            $('.dd').html($('#illustrate').val().replace(/\n/g,'<br>'));
            var num = 255 - length;
            $("#word").text(num);
            if($('#if_hideword').attr('checked'))
            {
                $('#membership_card_name').attr('style','display:none');
                $('#dengji_card_name').attr('style','display:none');
            }
//             if($('#points1').attr('checked')){				
//             	showlayer();
//             }else if($('#points2').attr('checked')){
//             	showlayer();
//             }else if($('#points3').attr('checked')){
//             	showlayer();
//             }else if($('#points4').attr('checked')){
//             	showlayer();            	            	
//             }
            $("#js_level #points1").trigger("click");
        });
        //点击取消的操作
        function card(){   
            $("#cover_img").attr('src','<?php echo $count == 2 ? IMG_GJ_LIST.$model['membercard_img'] : GJ_STATIC_IMAGES.'card/'.$model['membercard_img']?>'); 
            $("input[name='cover']").val('');
            $('#pop').hide();
        }
        
        function hideword(){
            if($("#if_hideword").is(":checked")){
                    $("#membership_card_name").hide();
                    $("#dengji_card_name").hide();
            }else{
                    $("#membership_card_name").show();
                    $("#dengji_card_name").show();
            }
        }
		
        /* 会员等级条件跳转 */
        var $js_radio = $("#js_level input[type='radio']"),
            $js_layer = $("#js_level .layer"),
            $js_layer_before = $js_layer.find(".arrow_top"),
            $js_title = $js_layer.find(".js_title"),
            $js_label = $("#js_level label");
            $js_tip = $js_layer.find(".tips");
            s = 0,
            _index=0,
            title="";
            <?php if ($model['rule_type'] == 5){?>
            	current_type = 999;
            <?php } else {?>
            	current_type = <?php echo $type ?> - 1;
            <?php }?>
//            var change_type = <?php #echo $change_type ?>;
         //   change_type = change_type - 1;
            $js_radio.click(function() {
                _index = $js_radio.index($js_radio.filter(":checked")), //获取选中状态的索引值
                title = $(this).attr("title");
                showLayer(_index);
//				if(_index != change_type){	
//	                if(_index != current_type ){
//	                	var index = layer.open({
//	                		offset: '300px'
//	                		  ,content: '确定修改会员等级条件吗？<br/>修改会员等级条件会使原有会员等级条件失效，需要重新设置并发布'
//	                		  ,btn: ['确定', '取消']
//	                		  ,yes: function(){
//	                		    //按钮【按钮一】的回调
//	                  		    clear_type('<?php #echo($this->createUrl('ClearType'));?>');
//	                  		  	layer.close(index);
//	                		  },btn2: function(){
//	                		    //按钮【按钮二】的回调
//	                			  showLayer(current_type);
//	                		  }
//	                		  ,cancel: function(){ 
//	                		    //右上角关闭回调
//	                			  showLayer(current_type);
//	                		  }
//	                	});
//	                }
//				}
            });
        
        function showLayer(_index){
            $js_layer.show();
            $js_radio.eq(_index).prop("checked",true);
            if (_index === 1 || _index === 3) {
                $js_title.html(title);
                $js_tip.show();
            } else {
                $js_tip.find("js_title").html("");
                $js_tip.hide();
            }
            for (var i = 0; i < _index; i++) {
                s = s + $js_label.eq(i).width();
            }
            t_width = s + _index * 20; //获取弹出提示的三角需要偏离的位置
            $js_layer_before.css({
                "left": t_width
            });
            s = 0;
        };
        <?php if ($model['rule_type'] != 5){?>
		var type = "<?php echo $model['rule_type']?>";
		type = type-1;
        //默认显示设置
        showLayer(type);
    	<?php }?>


        function showpop(){
            $('#pop').show();
        }
        function hiddenpop(){
            $('#pop').hide();
        }
        function showlayer(){
            $('.layer').show();
        }
        $(function(){
            addUserGrade_upload("upload",'<?php echo UPLOAD_TO_PATH?>','<?php echo GJ_STATIC_JS?>',"<?php echo UPLOAD_IMG_TYPE;?>",'<?php echo(IMG_GJ_FOLDER)?>','<?php echo IMG_GJ_LIST?>');
        });

        function addUserGrade_downs(key)
        { 
             <?php foreach ($GLOBALS['__XZYS'] as $key => $value) { ?>
                     if(key == <?php echo $key?>){
                         imgs = "<?php echo $value['img']?>";
                     }
             <?php } ?>        
            $("input[name='cover']").val(imgs);
            $("#cover_img").attr('src','<?php echo GJ_STATIC_IMAGES.'card/'?>'+imgs);
        }
        $('input[id=card_name]').change(function() { $('#membership_card_name').html($('#card_name').val()); });
        $('input[id=addUserGrade_usergradename]').change(function() { $('#dengji_card_name').html($('#addUserGrade_usergradename').val()); });        
        $('#illustrate').change(function(){$('.dd').html($('#illustrate').val().replace(/\n/g,'<br>'));});
        $('input[id=usergradediscount]').change(function(){
            var discount = $('input[id=usergradediscount]').val();
            if(discount > 9.9 || discount <= 0){

            } else {
                $('.discount').html(discount+'折');
            }        
        });

        //
        function clear_type(url) {
	        $.ajax({
	            url: url,
	            type: 'GET',
	            data: {data: 1},
	            dataType: 'json',
	            success: function (data) {
	            	layer.msg(data.v,{offset: '300px'});
	            }
	        });
		}
		
        $(function(){
            addUserGrade_usergradename('<?php echo($this->createUrl('CheckUserGrade'));?>','<?php echo $id?>');//会员等级名称输入框失去焦点判断输入是否正确
            addUserGrade_usergradediscount('<?php echo($this->createUrl('CheckUserGrade'));?>');//会员特权
            addUserGrade_usergradepoints_rule('<?php echo($this->createUrl('CheckUserGrade'));?>');//积分要求
            addUserGrade_usergradepoints_ratio('<?php echo($this->createUrl('CheckUserGrade'));?>');//积分规则
            addUserGrade_usergradebirthday_rate('<?php echo($this->createUrl('CheckUserGrade'));?>');//生日积分
            addUserGrade_card_name('<?php echo($this->createUrl('CheckUserGrade'));?>');//会员卡名称
        });
    </script>