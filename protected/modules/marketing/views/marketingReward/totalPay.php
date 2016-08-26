
<h4 class="g-title">累计消费满赠券<span class="g-title-desc">给累计消费满一定金额的客户赠券</span> </h4>
<div class="g-container">

    <form action="<?php echo $this->createUrl('marketingReward/marketingRewardAdd') ?>" id="marketing_form" method="post">
        <div class="m-estate-box m-market-box mb10">
            <section class="m-cp-item">
                <div class="form-group m-cp-info">
                    <div class="cp-title mt6">
                        <span>活动名称：</span>
                    </div>
                    <div class="cp-con">
                        <input type="text" name="Marketing[name]" value="<?php echo $edit_flag ? $marketing_info['name']:'';                    ?>" class="form-control fc-sm" placeholder="请输入活动名称，最多12个汉字">
                    </div>
                </div>
                <div class="form-group m-cp-info">
                    <div class="cp-title">
                        <span>活动对象：</span>
                    </div>
                    <div class="cp-con">
                        首次支付客户
                    </div>
                </div>
                <div class="form-group m-cp-info">
                    <div class="cp-title mt6">
                        <span>活动内容：</span>
                    </div>
                    <div class="cp-con">
                        <a href="javascript:;" class="button-plus js-choose-cp choose_coupon"><span></span></a>
    <span class="choosed-link pl10 choose_coupon_result">
        <?php echo $edit_flag ? $GLOBALS['COUPON_TYPE'][$marketing_info['coupon_type']] . '>'. $marketing_info['coupon_name'] : '' ?>
        <!--券类型>券名称--></span>
                        <input type="text" style="opacity: 0;filter: alpha(opacity=0);" id="marketing_coupon" name="Marketing[coupon]" value="<?php echo $edit_flag ? $marketing_info['coupon_id'] : '' ?>" />
                    </div>
                </div>
                <div class="form-group m-cp-info">
                    <div class="cp-title mt6">
                        <span>赠券条件：</span>
                    </div>
                    <div class="cp-con form-inline">
                        <span >累计消费满</span>
                        <input type="text" name="Marketing[reward_condition]" value="<?php echo $edit_flag ? $marketing_info['reward_condition']:'';?>" class="form-control fc-v-sm">
                        <span>元</span>
                    </div>
                </div>
            </section>
        </div>
        <div class="m-estate-box m-market-box mb10">
            <section class="m-cp-item">
                <div class="form-group m-cp-info">
                    <div class="cp-title">
                        <span>赠券渠道：</span>
                    </div>
                    <div class="cp-con m-input-group">
                        <div class="checkbox-inline">
                            <label>
                                <input name="Marketing[send_channel][]" type="checkbox" <?php if ($edit_flag && ($marketing_info['send_channel'] == MARKETING_ACTIVITY_SEND_CHANNEL_WECHAT || $marketing_info['send_channel'] == MARKETING_ACTIVITY_SEND_CHANNEL_BOTH)) { echo 'checked="checked"'; } elseif (!$edit_flag) { echo 'checked="checked"'; } ?> value="<?php echo MARKETING_ACTIVITY_SEND_CHANNEL_WECHAT ?>">微信公众号
                            </label>
                        </div>
                        <div class="checkbox-inline">
                            <label>
                                <input class="marketing_send_channel" name="Marketing[send_channel][]" type="checkbox" <?php if ($edit_flag && ($marketing_info['send_channel'] == MARKETING_ACTIVITY_SEND_CHANNEL_ALIPAY || $marketing_info['send_channel'] == MARKETING_ACTIVITY_SEND_CHANNEL_BOTH)) { echo 'checked="checked"'; } ?> value="<?php echo MARKETING_ACTIVITY_SEND_CHANNEL_ALIPAY ?>">支付宝服务窗
                            </label>
                        </div>
                        <p class="m-tip-icon text-gray alipay_msg" style="display: none">无权选择此项，您的账户尚未配置服务窗，前去<a target="_blank" href="<?php echo Yii::app()->createUrl('mCenter/index/install') ?>" class="text-blue">配置</a>
                        </p>
                        <label for="Marketing[send_channel][]" class="error" style="display: none"></label>
                    </div>
                </div>
                <!--<div class="form-group m-cp-info">-->
                <!--<div class="cp-title">-->
                <!--<span>预计发送时间：</span>-->
                <!--</div>-->
                <!--<div class="cp-con m-input-group">-->
                <!--<p class="mb10"><span class="text-blue h4 mr20">89</span> <span class="text-gray">由于发送量巨大，个别可能会延迟</span>-->
                <!--</p>-->
                <!--<p class="mb10">微信 <strong>80</strong> 人，<strong>10</strong> 人消息接收数量达到上限，发送人数-->
                <!--<strong>70</strong></p>-->
                <!--<p class="mb10">支付宝 <strong>80</strong> 人，<strong>10</strong> 人消息接收数量达到上限，发送人数-->
                <!--<strong>70</strong></p>-->
                <!--<p class="mb10">会员 <strong>80</strong> 人，<strong>10</strong> 人无法通知，推送人数 <strong>70</strong></p>-->
                <!--</div>-->
                <!--</div>-->

                <div id="img_text" style="display: <?php if ($edit_flag && ($marketing_info['send_channel'] == MARKETING_ACTIVITY_SEND_CHANNEL_ALIPAY || $marketing_info['send_channel'] == MARKETING_ACTIVITY_SEND_CHANNEL_BOTH)) { echo 'block'; } else { echo 'none'; } ?>">
                    <div class="form-group m-cp-info" id="cover_img_div">
                        <div class="cp-title">
                            <span>封面图片</span>
                        </div>
                        <div class="cp-con">
                            <div class="m-upload">
                                <div class="upload-inner">
                                    <img src="<?php echo $edit_flag ? IMG_GJ_LIST . $marketing_info['image_text_imageurl'] : '' ?>" alt="" id="cover_img">
                                    <div class="upload-icon">
                                        <div class="upload-btn">
                                            <input type="text" id="upload" class="file">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt10 text-gray gj-absolute">
                                <p class="mb10">支付宝服务窗每月仅限发5条，微信订阅号每天限发1条，微信服务号每月限发4条。</p>
                                <p>图片建议尺寸：850像素*350像素，大小不超过1M。</p>
                            </div>

                            <label class="error" for="Marketing[cover_img]" style="display: none">请上传封面图片</label>
                            <input type="text" style="opacity: 0;filter: alpha(opacity=0);" name="Marketing[cover_img]" value="<?php echo $edit_flag ? IMG_GJ_LIST . $marketing_info['image_text_imageurl'] : '' ?>" />
                        </div>
                    </div>
                    <div class="form-group m-cp-info" id="activity_msg_div">
                        <div class="cp-title mt6">
                            <span>活动说明：</span>
                        </div>
                        <div class="cp-con">
                            <input type="text" name="Marketing[img_text]" class="form-control fc-sm"
                                   placeholder="请输入活动说明，最多12个汉字" value="<?php echo $edit_flag ? $marketing_info['image_text_title'] : '' ?>" maxlength="12">
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="m-estate-box m-market-box mb10">
            <section class="m-cp-item">
                <div class="form-group m-cp-info">
                    <div class="cp-title mt6">
                        <span>活动时间：</span>
                    </div>
                    <div class="cp-con">
                        <div class="radio-inline form-inline mb10">
                            <label>
                                <input id="time_select" type="radio" checked name="Marketing[time_send_type]" value="<?php echo MARKETING_ACTIVITY_SEND_TYPE_CRONTAB;?>">
                                自定义周期
                            </label>
                            <input type="text" id="custom_period" name="Marketing[custom_period]" class="form-control fc-sm icon-time" value="<?php if ($edit_flag && $marketing_info['time_type'] == MARKETING_ACTIVITY_TIME_TYPE_PERIODICITY && $marketing_info['time_send_type'] == MARKETING_ACTIVITY_SEND_TYPE_CRONTAB) { echo date('Y-m-d', strtotime($marketing_info['start_time'])).' - '. date('Y-m-d', strtotime($marketing_info['end_time'])); } ?>" readonly>
                        </div>
                        <div class="radio-inline form-inline mb10">
                            <label>
                                <input type="radio" name="Marketing[time_send_type]" value="<?php echo MARKETING_ACTIVITY_SEND_TYPE_LONG;?>">
                                长期活动
                            </label>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="form-inline m-form-group mt30" style="margin-bottom: 150px">
            <button type="submit" class="btn btn-primary m-btn">保存</button>
            <button type="button" class="btn btn-default m-btn" onclick="history.go(-1)">取消</button>
            <input type="hidden" name="Marketing_id" value="<?php echo $marketing_info['id'];?>">
            <input type="hidden" name="Marketing[type]" value="<?php echo MARKETING_ACTIVITY_TYPE_BASICS_LJXFMMA;?>">
            <input type="hidden" name="Marketing[time_type]" value="<?php echo MARKETING_ACTIVITY_TIME_TYPE_PERIODICITY;?>">
        </div>
    </form>
</div>
<script>
    $(function () {
        $('#upload').uploadify({
            onInit: function () {
                $(".uploadify-queue").hide();
            },//载入时触发，将flash设置到最小
            uploader: '<?php echo UPLOAD_TO_PATH;?>',// 服务器处理地址
            swf: '<?php echo GJ_STATIC_JS ?>' + 'uploadify/uploadify.swf',
            buttonText: "",//按钮文字
            height: 34,  //按钮高度
            width: 82, //按钮宽度
            multi: true,
            fileSizeLimit: '1mb',
            fileTypeExts: "<?php echo UPLOAD_IMG_TYPE ?>",//允许的文件类型
            fileTypeDesc: "请选择图片文件", //文件说明
            formData: {'folder': '<?php echo IMG_GJ_FOLDER ?>'}, //提交给服务器端的参数
            onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
                eval("var jsondata = " + data + ";");
                var key = jsondata['key'];
                var name = file.name;
                var fileName = jsondata['fileName'];
                var imgsrc = '<?php echo IMG_GJ_LIST?>' + fileName;
                parent.window.delayHide('m-succeed-icon', '上传成功', 1500);
                //action_msg_show('上传成功');
                $("input[name='Marketing[cover_img]'").val(fileName);
                $('#cover_img').attr('src', imgsrc);
            }
        });

        //表单验证
        $('#marketing_form').validate({
            debug: false,
            rules: {
                'Marketing[name]': {
                    required: true,
                    maxlength: 12
                },
                'Marketing[coupon]': {
                    required: true
                },
                'Marketing[reward_condition]': {
                    required: true
                },
                'Marketing[send_channel][]':'required',
                'Marketing[cover_img]': {
                    required: true
                },
                'Marketing[img_text]': {
                    required: true
                },
                'Marketing[custom_period]': {
                    required: "#time_select:checked"
                }
            },
            messages: {
                'Marketing[name]': {
                    required: '请输入活动名称',
                    maxlength: '最多12个汉字'
                },
                'Marketing[coupon]': {
                    required: '请选择活动内容'
                },
                'Marketing[reward_condition]': {
                    required: '请输入赠券条件'
                },
                'Marketing[send_channel][]': '请选择赠券渠道',
                'Marketing[cover_img]': {
                    required: '请上传封面图片'
                },
                'Marketing[img_text]': {
                    required: '请填写图文简介'
                },
                'Marketing[custom_period]': {
                    required: '请选择周期时间'
                }
            },
            errorPlacement: function(error, element) {
                element.parent().after(error);
            }
        });

        jQuery.validator.addMethod('isUploadImg', function(value, element) {
            if ($('.marketing_send_channel').is(':checked')) {
                return true;
            } else {
                return false;
            }
        }, '请上传封面图片');

        //选择优惠券弹出层
        $('.choose_coupon').click(function () {
            layer.open({
                type: 2,
                title: '选择优惠券',
                shadeClose: true,
                shade: 0.8,
                offset: '20px',
                area: ['770px', '75%'],
                content: '<?php echo '/marketing/marketing/couponDialog';?>' //iframe的url
            });
            return false;
        });

    });



    //周期时间选择
    $('#custom_period').daterangepicker({
        timePicker: false,
        format: 'YYYY-MM-DD',
        dateLimit: true,
        opens: 'top',
        minDate: '<?php echo date('Y-m-d', time()) ?>',
        startDate: '<?php if ($edit_flag && !empty($marketing_info['start_time']) && !empty($marketing_info['end_time'])) { echo date('Y-m-d', strtotime($marketing_info['start_time'])); } else { echo date('Y-m-d', time()); } ?>',
        endDate: '<?php if ($edit_flag && !empty($marketing_info['start_time']) && !empty($marketing_info['end_time'])) { echo date('Y-m-d', strtotime($marketing_info['end_time'])); } else { echo date('Y-m-d', time()); } ?>'
    });

    //关闭选择优惠券弹出框
    function closeWinDialogCoupon(coupon_id, coupon_name, coupon_type, coupon_status) {
        layer.closeAll();
        if (coupon_id != undefined) {
            $('.choose_coupon_result').html(coupon_type + '>' + coupon_name);
            $('#marketing_coupon').val(coupon_id);
            if (!$('.marketing_send_channel').is(':checked')) {
                if (coupon_status != <?php echo WX_CHECK_PASS ?>) {
                    $('#img_text').show();
                    $('#cover_img_div').show();
                    $('#activity_msg_div').show();
                } else {
                    $('#img_text').hide();
                    $('#cover_img_div').hide();
                    $('#activity_msg_div').hide();
                }
            }
        }
    }

    //选择活动时间
    $("input[name='Marketing[custom_period]']").click(function () {
        var custom_period = $(this).val();
        if (custom_period == <?php echo MARKETING_ACTIVITY_TIME_TYPE_PERIODICITY ?>) {
            $("input[name='Marketing[custom_period]']").val('');
            $("input[name='Marketing[custom_period]']").attr('disabled', 'disabled');
        } else {
            $("input[name='Marketing[custom_period]']").val('');
            $("input[name='Marketing[custom_period]']").removeAttr('disabled');
        }
    });

    //选择支付宝发送渠道
    $('.marketing_send_channel').click(function () {
        if ($('.marketing_send_channel').is(':checked')) {
            var alipay_flag = '<?php echo $alipay_flag ?>';
            if (!alipay_flag) { //如果没有配置支付宝信息
                $('.marketing_send_channel').removeAttr('checked');
                $('.alipay_msg').show();
                setTimeout('alipay_msg_hide()', 1500);
            } else {
                $('#img_text').show();
                $('#cover_img_div').show();
                $('#activity_msg_div').show();
            }
        } else {
            $('.alipay_msg').hide();
            $('#cover_img_div').hide();
            $('#activity_msg_div').hide();
        }
        window.parent.callParAutoResize("main", $("body").height());
    });


</script>



