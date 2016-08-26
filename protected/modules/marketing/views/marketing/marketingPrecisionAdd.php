<body>
<h4 class="g-title">发起营销</h4>
<div class="g-container">
    <ul class="nav nav-inline nav-precise nav-precise-step2">
        <li class="nav-item text-blue">
            <i class="fa fa-check-circle-o"></i>
            <span>筛选目标客户</span>
        </li>
        <li class="nav-item nav-item-progress"></li>
        <li class="nav-item">
            <i class="fa fa-circle-o step2"><em>2</em></i>
            <span>发起营销</span>
        </li>
    </ul>
    <form action="<?php echo Yii::app()->createUrl('marketing/marketing/MarketingPrecisionAdd') ?>" id="marketing_form" method="post">
        <div class="m-estate-box mb10 m-market-box">
            <section class="m-cp-item">
                <div class="form-group m-cp-info">
                    <div class="cp-title mt6">
                        <span>活动名称：</span>
                    </div>
                    <div class="cp-con">
                        <input type="text" id="marketing_name" name="Marketing[name]" class="form-control fc-sm" placeholder="请输入活动名称,最多12个汉字" maxlength="12" <?php if ($page_flag) { ?>value="<?php echo $marketing_info['name'] ?>"<?php } ?>>
                    </div>
                </div>
                <div class="form-group m-cp-info">
                    <div class="cp-title">
                        <span>活动对象：</span>
                    </div>
                    <div class="cp-con">
                        <?php echo $page_flag ? $marketing_info['group_name'] : $group_info['name'] ?>，当前分组人数<span class="text-orange"><?php  echo $page_flag ? $marketing_info['num'] : $group_info['num'] ?></span>人（刷新时间：<?php echo $page_flag ? $marketing_info['last_time'] : $group_info['last_time'] ?>）
                        <input type="hidden" name="Marketing[group]" id="marketing_group" value="<?php echo $page_flag ? $marketing_info['group_id'] : $group_info['id'] ?>">
                        <?php if (!$page_flag) { ?>
                            <div class="text-gray">如本次不创建活动，您可在已有分组列表中找到本活动对象</div>
                        <?php } ?>
                    </div>
                </div>
                <div class="form-group m-cp-info">
                    <div class="cp-title mt6">
                        <span>活动内容：</span>
                    </div>
                    <div class="cp-con">
                        <a href="javascript:;" class="button-plus js-choose-cp choose_coupon"><span></span></a>
                        <span class="choosed-link pl10 choose_coupon_result"><?php echo $page_flag ? $GLOBALS['COUPON_TYPE'][$marketing_info['coupon_type']] . '>'. $marketing_info['coupon_name'] : '' ?><!--券类型>券名称--></span>
                        <!--<input type="hidden" name="Marketing[coupon]" id="marketing_coupon" value="">-->
                        <input type="text" style="opacity: 0;filter: alpha(opacity=0);" id="marketing_coupon" name="Marketing[coupon]" value="<?php echo $page_flag ? $marketing_info['coupon_id'] : '' ?>" />
                    </div>
                </div>
            </section>
        </div>
        <div class="m-estate-box mb10 m-market-box">
            <section class="m-cp-item">
                <div class="form-group m-cp-info">
                    <div class="cp-title">
                        <span>赠券渠道：</span>
                    </div>
                    <div class="cp-con m-input-group">
                        <div class="checkbox-inline">
                            <label>
                                <input name="Marketing[send_channel][]" type="checkbox" <?php if ($page_flag && ($marketing_info['send_channel'] == MARKETING_ACTIVITY_SEND_CHANNEL_WECHAT || $marketing_info['send_channel'] == MARKETING_ACTIVITY_SEND_CHANNEL_BOTH)) { echo 'checked="checked"'; } elseif (!$page_flag) { echo 'checked="checked"'; } ?> value="<?php echo MARKETING_ACTIVITY_SEND_CHANNEL_WECHAT ?>" id="wechat_send_channel">微信公众号
                            </label>
                        </div>
                        <div class="checkbox-inline">
                            <label>
                                <input class="marketing_send_channel" name="Marketing[send_channel][]" type="checkbox" <?php if ($page_flag && ($marketing_info['send_channel'] == MARKETING_ACTIVITY_SEND_CHANNEL_ALIPAY || $marketing_info['send_channel'] == MARKETING_ACTIVITY_SEND_CHANNEL_BOTH)) { echo 'checked="checked"'; } ?> value="<?php echo MARKETING_ACTIVITY_SEND_CHANNEL_ALIPAY ?>">支付宝服务窗
                            </label>
                        </div>
                        <p class="m-tip-icon text-gray alipay_msg" style="display: none">无权选择此项，您的账户尚未配置服务窗，前去<a target="_blank" href="<?php echo Yii::app()->createUrl('mCenter/index/install') ?>" class="text-blue">配置</a>
                        </p>
                        <p class="m-tip-icon text-gray alipay_group_msg" style="display: none">由于支付宝接口限制 当日新建分组需次日才可使用</p>
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
                <div id="img_text" style="display: <?php if ($page_flag && ($marketing_info['send_channel'] == MARKETING_ACTIVITY_SEND_CHANNEL_ALIPAY || $marketing_info['send_channel'] == MARKETING_ACTIVITY_SEND_CHANNEL_BOTH || ($marketing_info['send_channel'] == MARKETING_ACTIVITY_SEND_CHANNEL_WECHAT && $marketing_info['status'] != WX_CHECK_PASS))) { echo 'block'; } else { echo 'none'; } ?>">
                    <div class="form-group m-cp-info" id="cover_img_div" style="display: <?php if ($marketing_info['send_channel'] == MARKETING_ACTIVITY_SEND_CHANNEL_ALIPAY || $marketing_info['send_channel'] == MARKETING_ACTIVITY_SEND_CHANNEL_BOTH) { echo 'block'; } else { echo 'none'; }?>">
                        <div class="cp-title">
                            <span>封面图片</span>
                        </div>
                        <div class="cp-con">
                            <div class="m-upload">
                                <div class="upload-inner">
                                    <img src="<?php echo $page_flag ? IMG_GJ_LIST . $marketing_info['image_text_imageurl'] : '' ?>" alt="" id="cover_img">
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
                            <input type="text" style="opacity: 0;filter: alpha(opacity=0);" name="Marketing[cover_img]" value="<?php echo $page_flag ? IMG_GJ_LIST . $marketing_info['image_text_imageurl'] : '' ?>" />
                        </div>
                    </div>

                    <div class="form-group m-cp-info" id="activity_msg_div" style="display: <?php if (($marketing_info['send_channel'] == MARKETING_ACTIVITY_SEND_CHANNEL_WECHAT && $marketing_info['status'] != WX_CHECK_PASS) || $marketing_info['send_channel'] == MARKETING_ACTIVITY_SEND_CHANNEL_ALIPAY || $marketing_info['send_channel'] == MARKETING_ACTIVITY_SEND_CHANNEL_BOTH) { echo 'block'; } else { echo 'none'; }?>">
                        <div class="cp-title mt6">
                            <span>活动说明：</span>
                        </div>
                        <div class="cp-con">
                            <input type="text" name="Marketing[img_text]" class="form-control fc-sm"
                                   placeholder="请输入活动说明，最多12个汉字" value="<?php echo $page_flag ? $marketing_info['image_text_title'] : '' ?>" maxlength="12">
                        </div>
                    </div>

                </div>
            </section>
        </div>
        <div class="m-estate-box mb10 m-market-box">
            <section class="m-cp-item">
                <div class="form-group m-cp-info">
                    <div class="cp-title">
                        <span>活动时间：</span>
                    </div>
                    <div class="cp-con">
                        <div class="radio-inline form-inline mb10">
                            <label>
                                <input type="radio" <?php if (($page_flag && $marketing_info['time_type'] == MARKETING_ACTIVITY_TIME_TYPE_ONETIME) || !$page_flag) {
                                    echo 'checked="checked"';
                                } ?> name="Marketing[time_type]" value="<?php echo MARKETING_ACTIVITY_TIME_TYPE_ONETIME ?>">
                                一次性活动
                            </label>
                        </div>
                        <div class="radio-inline form-inline mb10">
                            <label>
                                <input type="radio" <?php if ($page_flag && $marketing_info['time_type'] == MARKETING_ACTIVITY_TIME_TYPE_PERIODICITY) {
                                    echo 'checked="checked"';
                                } ?> name="Marketing[time_type]" value="<?php echo MARKETING_ACTIVITY_TIME_TYPE_PERIODICITY ?>">
                                周期活动
                            </label>
                        </div>
                        <div class="ml20" id="marketing_type_forthwith" style="display: <?php if ($page_flag && $marketing_info['time_type'] == MARKETING_ACTIVITY_TIME_TYPE_PERIODICITY) { echo 'none'; } ?>">
                            <div class="radio form-inline mb10">
                                <label>
                                    <input type="radio" name="Marketing[time_send_type1]" value="<?php echo MARKETING_ACTIVITY_SEND_TYPE_TIMING ?>" <?php if (!$page_flag || ($page_flag && $marketing_info['time_type'] == MARKETING_ACTIVITY_TIME_TYPE_ONETIME && $marketing_info['time_send_type'] == MARKETING_ACTIVITY_SEND_TYPE_TIMING)) {
                                        echo 'checked="checked"';
                                    } ?>>
                                    定时推送
                                </label>
                                <input readonly type="text" id="data_time1" name="Marketing[send_one_time]" class="form-control fc-sm icon-time" value="<?php
                                if ($page_flag && $marketing_info['time_type'] == MARKETING_ACTIVITY_TIME_TYPE_ONETIME && $marketing_info['time_send_type'] == MARKETING_ACTIVITY_SEND_TYPE_TIMING) {
                                    echo date('Y-m-d', strtotime($marketing_info['start_time']));
                                } ?>">
                            </div>
                            <div class="radio form-inline mb10">
                                <label>
                                    <input type="radio" name="Marketing[time_send_type1]" value="<?php echo  MARKETING_ACTIVITY_SEND_TYPE_IMMEDIATELY ?>" <?php if ($page_flag && $marketing_info['time_type'] == MARKETING_ACTIVITY_TIME_TYPE_ONETIME && $marketing_info['time_send_type'] == MARKETING_ACTIVITY_SEND_TYPE_IMMEDIATELY) { echo 'checked="checked"'; } ?>>立即推送
                                </label>
                            </div>
                        </div>

                        <div class="ml20" id="marketing_type_cyc" style="display: <?php if (!$page_flag || $marketing_info['time_type'] == MARKETING_ACTIVITY_TIME_TYPE_ONETIME) { echo 'none'; } ?>">
                            <div class="radio form-inline mb10">
                                <label>
                                    <input type="radio" <?php if (!$page_flag || ($page_flag || ($marketing_info['time_type'] == MARKETING_ACTIVITY_TIME_TYPE_PERIODICITY && $marketing_info['time_send_type'] == MARKETING_ACTIVITY_SEND_TYPE_CRONTAB))) { echo 'checked="checked"'; } ?> name="Marketing[time_send_type2]" value="<?php echo MARKETING_ACTIVITY_SEND_TYPE_CRONTAB ?>">
                                    定义周期
                                </label>
                                <input readonly type="text" name="Marketing[cyc_time]" id="data_time2" class="form-control fc-sm icon-time" value="<?php if ($page_flag && $marketing_info['time_type'] == MARKETING_ACTIVITY_TIME_TYPE_PERIODICITY && $marketing_info['time_send_type'] == MARKETING_ACTIVITY_SEND_TYPE_CRONTAB) { echo date('Y-m-d', strtotime($marketing_info['start_time'])) . ' - ' . date('Y-m-d', strtotime($marketing_info['end_time'])); } ?>">
                            </div>
                            <div class="radio form-inline mb10">
                                <label>
                                    <input type="radio" name="Marketing[time_send_type2]" value="<?php echo  MARKETING_ACTIVITY_SEND_TYPE_LONG ?>" <?php if ($page_flag && $marketing_info['time_type'] == MARKETING_ACTIVITY_TIME_TYPE_PERIODICITY && $marketing_info['time_send_type'] == MARKETING_ACTIVITY_SEND_TYPE_LONG) { echo 'checked="checked"'; } ?>>
                                    长期活动
                                </label>
                            </div>
                        </div>

                        <div class="radio form-inline mb10 text-gray">
                            <label>活动创建成功后的16:00准点推送</label>
                        </div>

                    </div>
                </div>
            </section>
        </div>

        <div class="form-inline m-form-group mt30">
            <button type="submit" class="btn btn-primary m-btn">保存</button>
            <button type="button" class="btn btn-default m-btn" onclick="window.location.href='<?php echo $this->createUrl('marketing/MarketingPrecision') ?>'">取消</button>
            <input type="hidden" name="Marketing[page_flag]" value="<?php echo $page_flag ?>">
            <input type="hidden" name="Marketing[id]" value="<?php echo $marketing_info['id'] ?>">
        </div>
    </form>
</div>

</body>

<script>
    $(function () {
        $('#upload').uploadify({
            onInit: function () {
                $(".uploadify-queue").hide();
            },//载入时触发，将flash设置到最小
            uploader: '<?php echo UPLOAD_TO_PATH ?>',// 服务器处理地址
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
                'Marketing[group]': {
                    required: true
                },
                'Marketing[coupon]': {
                    required: true
                },
                'Marketing[send_channel][]':'required',
                'Marketing[cover_img]': {
                    required: true
                },
                'Marketing[img_text]': {
                    required: true
                },
                'Marketing[send_one_time]': {
                    required: true
                },
                'Marketing[cyc_time]': {
                    required: true
                }
            },
            messages: {
                'Marketing[name]': {
                    required: '请输入活动名称',
                    maxlength: '最多12个汉字'
                },
                'Marketing[group]': {
                    required: '请选择活动对象'
                },
                'Marketing[coupon]': {
                    required: '请选择活动内容'
                },
                'Marketing[send_channel][]': '请选择赠券渠道',
                'Marketing[cover_img]': {
                    required: '请上传封面图片'
                },
                'Marketing[img_text]': {
                    required: '请填写图文简介'
                },
                'Marketing[send_one_time]': {
                    required: '请选择定时时间'
                },
                'Marketing[cyc_time]': {
                    required: '请选择周期时间'
                }
            },
            submitHandler: function(form) {
                var time_send_type1 = $("input[name='Marketing[time_send_type1]']:checked").val();
                if (time_send_type1 == <?php echo MARKETING_ACTIVITY_SEND_TYPE_IMMEDIATELY ?> && $('#wechat_send_channel').attr('checked') == 'checked') {
                    layer.confirm('由于微信接口限制，客户实际接收到消息时间可能存在延迟，确认推送？', {
                        btn: ['确定', '取消'],//按钮
                        offset: '400px'
                    }, function () {
                        form.submit();
                    });
                } else {
                    form.submit();
                }
            }
        });
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
                    $('#cover_img_div').hide();
                    $('#activity_msg_div').show();
                } else {
                    $('#img_text').hide();
                    $('#cover_img_div').hide();
                    $('#activity_msg_div').hide();
                }
            }
        }
    }

    //定时推送时间选择
    $('#data_time1').daterangepicker({
        timePicker: false,
        format: 'YYYY-MM-DD',
        dateLimit: true,
        singleDatePicker:true,
        minDate: '<?php echo date('Y-m-d', time()) ?>',
        startDate: '<?php if ($page_flag && !empty($marketing_info['start_time'])) { echo date('Y-m-d', strtotime($marketing_info['start_time'])); } else { echo date('Y-m-d', time()); } ?>'
    });

    //周期时间选择
    $('#data_time2').daterangepicker({
        timePicker: false,
        format: 'YYYY-MM-DD',
        dateLimit: true,
        minDate: '<?php echo date('Y-m-d', time()) ?>',
        startDate: '<?php if ($page_flag && !empty($marketing_info['start_time']) && !empty($marketing_info['end_time'])) { echo date('Y-m-d', strtotime($marketing_info['start_time'])); } else { echo date('Y-m-d', time()); } ?>',
        endDate: '<?php if ($page_flag && !empty($marketing_info['start_time']) && !empty($marketing_info['end_time'])) { echo date('Y-m-d', strtotime($marketing_info['end_time'])); } else { echo date('Y-m-d', time()); } ?>'
    });


    //选择活动时间类型
    $("input[name='Marketing[time_type]']").click(function () {
        var marketing_time = $(this).val();
        if (marketing_time == <?php echo MARKETING_ACTIVITY_TIME_TYPE_ONETIME ?>) {
            $('#marketing_type_cyc').hide();
            $('#marketing_type_forthwith').show();
            $("input[name='Marketing[cyc_time]']").val('');
            $("input[name='Marketing[time_send_type1]']:first").attr('checked', true);
        } else if (marketing_time == <?php echo MARKETING_ACTIVITY_TIME_TYPE_PERIODICITY ?>) {
            $('#marketing_type_forthwith').hide();
            $('#marketing_type_cyc').show();
            $("input[name='Marketing[send_one_time]']").val('');
            $("input[name='Marketing[time_send_type2]']:first").attr('checked', true);
        }
        window.parent.callParAutoResize("main", $("body").height());
    });

    //选择活动发送时间
    $("input[name='Marketing[time_send_type1]']").click(function () {
        var time_send_type1 = $(this).val();
        if (time_send_type1 == <?php echo MARKETING_ACTIVITY_SEND_TYPE_TIMING ?>) { //选择定时发送
            $("input[name='Marketing[send_one_time]']").val('');
            $("input[name='Marketing[send_one_time]']").removeAttr('disabled');
        } else {
            $("input[name='Marketing[send_one_time]']").val('');
            $("input[name='Marketing[send_one_time]']").attr('disabled', 'disabled');
        }
    });

    $("input[name='Marketing[time_send_type2]']").click(function () {
        var time_send_type1 = $(this).val();
        if (time_send_type1 == <?php echo MARKETING_ACTIVITY_SEND_TYPE_CRONTAB ?>) { //选择定义周期发送
            $("input[name='Marketing[cyc_time]']").val('');
            $("input[name='Marketing[cyc_time]']").removeAttr('disabled');
        } else {
            $("input[name='Marketing[cyc_time]']").val('');
            $("input[name='Marketing[cyc_time]']").attr('disabled', 'disabled');
        }
    });

    //选择优惠券弹出层
    $('.choose_coupon').click(function () {
        layer.open({
            type: 2,
            title: '选择优惠券',
            shadeClose: true,
            shade: 0.8,
            offset: '20px',
            area: ['770px', '560px'],
            content: '/index.php/marketing/Marketing/couponDialog' //iframe的url
        });
        return false;
    });

    //选择支付宝发送渠道
    $('.marketing_send_channel').click(function () {
        if ($('.marketing_send_channel').is(':checked')) {
            var alipay_flag = '<?php echo $alipay_flag ?>';
            if (!alipay_flag) { //如果没有配置支付宝信息
                $('.marketing_send_channel').removeAttr('checked');
                $('.alipay_msg').show();
                //setTimeout('alipay_msg_hide()', 3000);
            } else {
                $('.alipay_group_msg').show();
                $('#img_text').show();
                $('#cover_img_div').show();
                $('#activity_msg_div').show();
                setTimeout('alipay_msg_hide()', 3000);
            }
        } else {
            $('.alipay_msg').hide();
            $('#img_text').hide();
        }
        window.parent.callParAutoResize("main", $("body").height());
    });

    $("input[name='Marketing[send_channel][]']").click(function () {
        if ($(this).attr('checked') == 'checked') {
            $(this).removeAttr('checked');
        } else {
            $(this).attr('checked', true);
        }
    });

    function alipay_msg_hide(){
        //$('.alipay_msg').hide();
        $('.alipay_group_msg').hide();
    }
</script>
