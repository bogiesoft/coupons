<body>
<h4 class="g-title">营销详情</h4>
<div class="g-container">
    <div class="m-estate-box mb10 m-market-box">
        <section class="m-cp-item">
            <div class="form-group m-cp-info">
                <div class="cp-title">
                    <span>活动名称：</span>
                </div>
                <div class="cp-title">
                    <span><?php echo $marketing_info['name'] ?></span>
                </div>
            </div>
            <div class="form-group m-cp-info">
                <div class="cp-title">
                    <span>活动对象：</span>
                </div>
                <div class="cp-con">
                    <?php echo $marketing_info['group_name'] ?>，当前分组人数<span
                        class="text-orange"><?php echo $marketing_info['num'] ?></span>人（刷新时间：<?php echo $marketing_info['last_time'] ?>）
                </div>
            </div>
            <div class="form-group m-cp-info">
                <div class="cp-title">
                    <span>活动内容：</span>
                </div>
                <div class="cp-con">
                    <span class="choosed-link choose_coupon_result"><?php echo $GLOBALS['COUPON_TYPE'][$marketing_info['coupon_type']] . '>'. $marketing_info['coupon_name'] ?></span>
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
                <div class="cp-con">
                    <?php if ($marketing_info['send_channel'] == MARKETING_ACTIVITY_SEND_CHANNEL_WECHAT) { ?>
                            <label>微信公众号</label>
                    <?php } elseif ($marketing_info['send_channel'] == MARKETING_ACTIVITY_SEND_CHANNEL_ALIPAY) { ?>
                            <label>支付宝服务商</label>
                    <?php } elseif ($marketing_info['send_channel'] == MARKETING_ACTIVITY_SEND_CHANNEL_BOTH) { ?>
                            <label>微信公众号</label>
                        <div class="checkbox-inline">
                            <label>支付宝服务商</label>
                        </div>
                    <?php } ?>
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
            <?php if ($marketing_info['send_channel'] != MARKETING_ACTIVITY_SEND_CHANNEL_WECHAT) { ?>
                <div id="img_text">
                    <div class="form-group m-cp-info">
                        <div class="cp-title">
                            <span>封面图片</span>
                        </div>
                        <div class="cp-con">
                            <div class="m-upload">
                                <div class="upload-inner">
                                    <img src="<?php echo IMG_GJ_LIST . $marketing_info['image_text_imageurl'] ?>" alt="" id="cover_img">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-cp-info">
                        <div class="cp-title">
                            <span>图文简介：</span>
                        </div>
                        <div class="cp-con">
                            <?php echo $marketing_info['image_text_title'] ?>
                        </div>
                    </div>
                </div>
            <?php } elseif ($marketing_info['send_channel'] == MARKETING_ACTIVITY_SEND_CHANNEL_WECHAT && $marketing_info['status'] != WX_CHECK_PASS) { ?>
            <div id="img_text">
                <div class="form-group m-cp-info">
                    <div class="cp-title">
                        <span>图文简介：</span>
                    </div>
                    <div class="cp-con">
                        <?php echo $marketing_info['image_text_title'] ?>
                    </div>
                </div>
            </div>
            <?php } ?>
        </section>
    </div>
    <div class="m-estate-box mb10 m-market-box">
        <section class="m-cp-item">
            <div class="form-group m-cp-info">
                <div class="cp-title">
                    <span>活动时间：</span>
                </div>
                <div class="cp-con">
                    <?php if ($marketing_info['time_type'] == MARKETING_ACTIVITY_TIME_TYPE_ONETIME) { ?>
                        <label>一次性活动</label>
                        <?php if ($marketing_info['time_send_type'] == MARKETING_ACTIVITY_SEND_TYPE_TIMING) {
                            echo '定时推送 ' . date('Y.m.d', strtotime($marketing_info['start_time']));
                        } else {
                            echo '即时';
                        }
                    } elseif ($marketing_info['time_type'] == MARKETING_ACTIVITY_TIME_TYPE_PERIODICITY) { ?>
                        <label>周期活动</label>
                        <div class="radio-inline form-inline">
                            <label>
                                <?php if ($marketing_info['time_send_type'] == MARKETING_ACTIVITY_SEND_TYPE_LONG) {
                                    echo '长期';
                                } elseif ($marketing_info['time_send_type'] == MARKETING_ACTIVITY_SEND_TYPE_CRONTAB) {
                                    echo date('Y.m.d', strtotime($marketing_info['start_time'])) . ' - ' .  date('Y.m.d', strtotime($marketing_info['end_time']));
                                } ?></label>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </section>
    </div>

    <div class="form-inline m-form-group mt30">
        <!--<button type="submit" class="btn btn-primary m-btn">保存</button>-->
        <button type="button" class="btn btn-default m-btn" onclick="window.location.href='<?php echo $this->createUrl('marketing/MarketingList') ?>'">返回</button>
    </div>
</div>

</body>
