<h4 class="g-title">编辑优惠券</h4>
<div class="g-container">
    <!-- 提交审核之后出现的显示提示框-->
    <div class="m-tip submit-success-tip" id="create_tips" style="display: none">
        <div class="tip-icon fl">
            <i class="fa fa-check-circle"></i>
        </div>
        <div class="tip-content fl">
            <h5>修改成功！</h5>

            <p class="text-gray mt10"><span class="count-down-num">2</span>s自动跳转至优惠券列表</p>
        </div>
    </div>
    <div class="m-tip submit-success-tip" id="createfail_tips" style="display: none">
        <div class="tip-icon fl">
            <i class="fa fa-check-circle"></i>
        </div>
        <div class="tip-content fl">
            <h5>修改失败！</h5>

            <p class="text-gray mt10"><span class="count-down-num">2</span>s自动跳转至优惠券列表</p>
        </div>
    </div>
    <div class="m-cp-step">
        <span class="f-step active">1、填写优惠券信息</span>
        <span class="s-step">2、功能设置</span>
    </div>
    <?php echo CHtml::beginForm('', 'post', array('id' => 'addForm')); ?>
    <div class="m-c-cp js-step-1" style="display: block">
        <div
            class="cp-l <?php echo isset($_POST['Coupons']['color']) ? $_POST['Coupons']['color'] : $model['color']; ?> js-cp-l">
            <img src="<?php echo WQ_STATIC_IMAGES ?>iphone.png" class="title-img">

            <div class="m-cp-preview">
                <div class="m-cp-preview-inner">
                    <div class="row row-cp-1">
                        <div class="col-sm-12 text-center">
                            <img src="<?php echo empty($merchant_logo) ? '' : IMG_GJ_LIST . $model['logo'] ?>" alt=""
                                 class="avatar">
                            <h5 class="store-name mt20"><?php echo isset($merchant_short_name) ? $merchant_short_name : $model['merchant_short_name'] ?></h5>
                        </div>
                        <div class="col-sm-12 text-center mt30">
                            <h2 class="text-black"
                                id="left_title"><?php echo isset($_POST['Coupons']['title']) ? $_POST['Coupons']['title'] : $model['title'] ?></h2>
                        </div>
                        <div class="col-sm-12 text-center mt10">
                            <h6 class="text-black"
                                id="left_vice_title"><?php echo isset($_POST['Coupons']['vice_title']) ? $_POST['Coupons']['vice_title'] : $model['vice_title'] ?></h6>
                        </div>
                        <div class="col-sm-12 text-center mt10">
                            <h5 class="btn-showuse <?php echo $model['color']?$model['color']:'Color082';?>">立即使用</h5>
                        </div>
                        <div class="col-sm-12 text-center mt15">
                            <h6 class="expiry-date">有效期：<label id="left_time">
                                    <?php if (isset($_POST['Coupons']['time_type'])) { ?>
                                        <?php if ($_POST['Coupons']['time_type'] == VALID_TIME_TYPE_FIXED) { ?>
                                            <?php if (!empty($_POST['Time'])) { ?>
                                                <?php $date_arr = explode('-', $_POST['Time']); ?>
                                                <?php echo str_replace("/", ".", date('Y.m.d', strtotime($date_arr[0]))) . '-' . str_replace("/", ".", date('Y.m.d', strtotime($date_arr[1]))); ?>
                                            <?php } else { ?>
                                                <?php echo ''; ?>
                                            <?php } ?>
                                        <?php } else { ?>

                                            <?php if (isset($_POST['Coupons']['start_days']) && $_POST['Coupons']['start_days'] != 0) { ?>
                                                <?php echo '领取后' . $_POST['Coupons']['start_days'] . '天生效，有效' . $_POST['Coupons']['effective_days'] . '天'; ?>
                                            <?php } else { ?>

                                            <?php } ?>

                                        <?php } ?>
                                    <?php } else { ?>
                                        <?php if ($model['time_type'] == VALID_TIME_TYPE_FIXED) { ?>
                                            <?php echo str_replace("-", "/", substr($model['start_time'], 0, 10)) . '-' . str_replace("-", "/", substr($model['end_time'], 0, 10)) ?>
                                        <?php } else { ?>
                                                 领取后，<?php echo $model['start_days'] == 0?"当":$model['start_days'];?>天生效，有效<?php echo $model['effective_days'];?>天
                                        <?php } ?>
                                    <?php } ?>
                                </label></h6>
                        </div>
                        <div class="col-sm-12 text-center mt15">
                            <h6 class="expiry-date">可用时段：<label class="js-partial-time-l" id="left_available_time">
                                    <?php if (isset($_POST['Coupons']['available_time'])) { ?>
                                        <?php if ($_POST['Coupons']['available_time'] == 2) { ?>
                                            <?php if (!empty($_POST['Coupons']['day'])) { ?>
                                                <?php $date_arr = implode('、', $_POST['Coupons']['day']); ?>
                                                <?php echo $date_arr; ?>
                                            <?php } else { ?>
                                                <?php echo '周一至周日'; ?>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <?php echo '周一至周日'; ?>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <?php if (!empty($interval)) { ?>
                                            <?php echo $interval; ?>
                                        <?php } else { ?>
                                            <?php echo '周一至周日'; ?>
                                        <?php } ?>
                                    <?php } ?>
                                </label></h6>
                            <?php if (isset($_POST['Coupons']['begin_hour']) && !empty($_POST['Coupons']['begin_hour'])) { ?>
                                <h6 class="expiry-date">可用时间：<label id="left_available_hour2">
                                        <?php echo isset($_POST['Coupons']['begin_hour']) ? $_POST['Coupons']['begin_hour'] : '' . ' 至 ' . isset($_POST['Coupons']['end_hour']) ? $_POST['Coupons']['end_hour'] : ''; ?>
                                    </label></h6>
                            <?php } else { ?>
                                <?php if (!empty($hour)) { ?>
                                <h6 class="expiry-date">可用时间：<label id="left_available_hour2"><?php echo $hour; ?></label>

                                <?php } else { ?>
                                    <h6 class="expiry-date">可用时间：<label id="left_available_hour2">全部时段</label></h6>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row row-cp-2">
                        <!--<h5 class="entry-1">自定义入口1</h5>-->
                        <div class="cp-cover mt15">
                            <img id="left_cover_img"
                                 src="<?php echo isset($_POST['Coupons']['cover_img']) && !empty($_POST['Coupons']['cover_img']) ? IMG_GJ_LIST . $_POST['Coupons']['cover_img'] : !empty($model['cover_img']) ? IMG_GJ_LIST . $model['cover_img'] : ''; ?>"
                                 alt="">
                            <h5 id="left_cover_title"
                                class="title arrow-left"><?php echo isset($_POST['Coupons']['cover_img_title']) ? $_POST['Coupons']['cover_img_title'] : !empty($model['cover_img_title']) ? IMG_GJ_LIST . $model['cover_img_title'] : ''; ?></h5>
                        </div>
                        <p class="arrow-left coupon-p text-black">适用门店</p>

                        <p class="arrow-left coupon-p text-black">公众号</p>

                    </div>
                    <div class="row row-cp-3">
                        <p id="entrance1" class="arrow-left coupon-p text-black">
                            <?php if (isset($_POST['Coupons']['custom_url_name'])) { ?>
                                <?php echo isset($_POST['Coupons']['custom_url_name']) ? $_POST['Coupons']['custom_url_name'] : '自定义入口'; ?>
                            <?php } else { ?>
                                <?php if (!empty($model_info['custom_url_name'])) { ?>
                                    <?php echo $model_info['custom_url_name']; ?>
                                <?php } else { ?>
                                    <?php echo '自定义入口'; ?>
                                <?php } ?>
                            <?php } ?>
                        </p>

                        <p id="entrance2" class="arrow-left coupon-p text-black">
                            <?php if (isset($_POST['Coupons']['custom_url_name'])) { ?>
                                <?php echo isset($_POST['Coupons']['promotion_url_name']) ? $_POST['Coupons']['promotion_url_name'] : '自定义入口'; ?>
                            <?php } else { ?>
                                <?php if (!empty($model_info['promotion_url_name'])) { ?>
                                    <?php echo $model_info['promotion_url_name']; ?>
                                <?php } else { ?>
                                    <?php echo '自定义入口'; ?>
                                <?php } ?>
                            <?php } ?>

                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="cp-r">
            <section class="m-cp-item">
                <h5 class="title">基本信息</h5>

                <div class="form-group m-cp-info">
                    <div class="cp-title">
                        <span>商户<em>*</em></span>
                    </div>
                    <div class="cp-con">
                        <img src="<?php echo empty($model['logo']) ? '' : IMG_GJ_LIST . $model['logo'] ?>" alt=""
                             class="store-image">
                        <input type="hidden" name="Coupons[logo]"
                               value="<?php echo isset($_POST['Coupons']['logo']) ? $_POST['Coupons']['logo'] : isset($model['logo']) ? $model['logo'] : '' ?>">
                        <input type="text" name="Coupons[merchant_short_name]" id=""
                               value="<?php echo isset($_POST['Coupons']['merchant_short_name']) ? $_POST['Coupons']['merchant_short_name'] : isset($model['merchant_short_name']) ? $model['merchant_short_name'] : '' ?>"
                               class="form-control store-name" readonly placeholder="商家名称">

                        <p class="mt15 text-gray">如商户信息有变更，请在<a
                                href="<?php echo Yii::app()->createUrl('mCenter/ShopManagement/OnlineShop') ?>"
                                class="text-blue">优惠券商户资料</a>更新</p>
                    </div>
                </div>
                <div class="form-group m-cp-info">
                    <div class="cp-title">
                        <span>优惠券类型<em>*</em></span>
                    </div>
                    <div class="cp-con js-cp-type">
                        <div class="radio-inline">
                            <label>
                                <input type="radio" name="Coupons[type]" id="cash_type" value="2" checked> 代金券
                            </label>
                        </div>
                        <div class="radio-inline">
                            <label>
                                <input type="radio" name="Coupons[type]" id="discount_type" value="3"> 折扣券
                            </label>
                        </div>
                        <div class="radio-inline">
                            <label>
                                <input type="radio" name="Coupons[type]" id="" value="4"> 兑换券
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group m-cp-info">
                    <div class="cp-title mt6">
                        <span>优惠券颜色<em>*</em></span>
                    </div>
                    <div class="cp-con">
                        <div class="m-cp-color js-cp-color">
                            <div class="selected-box">
                                <input id="Couponscolor" type="hidden" name="Coupons[color]"
                                       value="<?php echo isset($_POST['Coupons']['color']) ? $_POST['Coupons']['color'] : $model['color'] ?>"/>

                                <div
                                    class="<?php echo isset($_POST['Coupons']['color']) ? $_POST['Coupons']['color'] : $model['color'] ?>"
                                    id="selected-color"></div>
                            </div>
                            <!-- 颜色列表 -->
                            <ul class="color-list clearfix" style="display: none;">
                                <li class="Color082"></li>
                                <li class="Color010"></li>
                                <li class="Color020"></li>
                                <li class="Color030"></li>
                                <li class="Color040"></li>
                                <li class="Color050"></li>
                                <li class="Color102"></li>
                                <li class="Color060"></li>
                                <li class="Color070"></li>
                                <li class="Color080"></li>
                                <li class="Color081"></li>
                                <li class="Color090"></li>
                                <li class="Color100"></li>
                                <li class="Color101"></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="form-group m-cp-info">
                    <div class="cp-title mt6">
                        <span><i class="js-cp-title">代金券</i>标题<em>*</em></span>
                    </div>
                    <div class="cp-con">
                        <?php echo CHtml::textField('Coupons[title]', isset($_POST['Coupons']['title']) ? $_POST['Coupons']['title'] : $model['title'], array('class' => 'form-control fc-sm', 'readonly' => 'readonly', 'placeholder' => '最多9个汉字', 'maxlength' => 9)); ?>

                        <span class="text1 red">
                              <?php if (Yii::app()->user->hasFlash('title_error')) { ?>
                                  <?php echo Yii::app()->user->getFlash('title_error'); ?>
                              <?php } ?>
                        </span>

                        <p class="mt10 text-gray">建议填写优惠券提供的服务或商品名称，描述卡券提供的具体优惠</p>
                    </div>
                </div>
                <div class="form-group m-cp-info">
                    <div class="cp-title mt6">
                        <span><i class="js-cp-title">代金券</i>副标题</span>
                    </div>
                    <div class="cp-con">
                        <?php echo CHtml::textField('Coupons[vice_title]', isset($_POST['Coupons']['vice_title']) ? $_POST['Coupons']['vice_title'] : $model['vice_title'], array('class' => 'form-control fc-sm', 'readonly' => 'readonly', 'placeholder' => '最多18个汉字', 'maxlength' => 18)); ?>
                    </div>
                </div>
                <div class="form-group m-cp-info">
                    <div class="cp-title mt6">
                        <span>有效时间<em>*</em></span>
                    </div>
                    <div class="cp-con js-effective-time">
                        <div class="radio form-inline mb10">
                            <label>
                                <input type="radio" name="Coupons[time_type]" id="effective-time-1" value="1" checked>
                                固定时间
                            </label>
                            <input type="text" name="Coupons[time]"
                                   value="<?php echo isset($_POST['Coupons']['time']) ? $_POST['Coupons']['time'] : !empty($model['start_time']) && !empty($model['start_time']) ? str_replace("-", "/", substr($model['start_time'], 0, 10)) . '-' . str_replace("-", "/", substr($model['end_time'], 0, 10)) : ''; ?>"
                                   class="form-control ml10 fc-sm">
                        </div>
                        <span class="text1 red">
                              <?php if (Yii::app()->user->hasFlash('valid_time_error')) { ?>
                                  <?php echo Yii::app()->user->getFlash('valid_time_error'); ?>
                              <?php } ?>
                        </span>

                        <div class="radio form-inline">
                            <label>
                                <input type="radio" name="Coupons[time_type]" id="effective-time-2" value="2">

                                领取后，
                            </label>

                            <div class="input-group fc-v-sm m-input-group">
                                <?php echo CHtml::dropDownList('Coupons[start_days]', isset($_POST['Coupons']['start_days']) ? $_POST['Coupons']['start_days'] : '', $start_days_arr, array('class' => 'form-control', 'disabled' => 'disabled')); ?>
                                <span class="input-group-addon">天</span>
                            </div>
                            <span class="ml10">生效，有效天数</span>

                            <div class="input-group fc-v-sm m-input-group ml10">

                                <?php echo CHtml::dropDownList('Coupons[effective_days]', isset($_POST['Coupons']['effective_days']) ? $_POST['Coupons']['effective_days'] : '', $effective_days_arr, array('class' => 'form-control', 'disabled' => 'disabled')); ?>
                                <span class="input-group-addon">天</span>

                            </div>
                        </div>
                    </div>

                </div>
                <div class="form-group m-cp-info">
                    <div class="cp-title">
                        <span>可用时段<em>*</em></span>
                    </div>
                    <div class="cp-con js-available-time">
                        <div class="radio form-inline mb30">
                            <label>
                                <input type="radio" name="Coupons[available_time]" id="available-time-1" value="1"
                                       checked>
                                全部时段
                            </label>
                        </div>
                        <div class="radio form-inline checkbox-no-pd">
                            <label>
                                <input type="radio" name="Coupons[available_time]" id="available-time-2" value="2">
                                部分时段
                            </label>
                            <!-- 选择部分时段后才会显示-->
                            <div class="mt20 partial-time js-partial-time-r" style="display: none">
                                <div class="checkbox-inline">
                                    <label>
                                        <input type="checkbox" class="day_checkbox" name="Coupons[day][]" value="周一">周一
                                    </label>
                                </div>
                                <div class="checkbox-inline">
                                    <label>
                                        <input type="checkbox" class="day_checkbox" name="Coupons[day][]" value="周二">周二
                                    </label>
                                </div>
                                <div class="checkbox-inline">
                                    <label>
                                        <input type="checkbox" class="day_checkbox" name="Coupons[day][]" value="周三">周三
                                    </label>
                                </div>
                                <div class="checkbox-inline">
                                    <label>
                                        <input type="checkbox" class="day_checkbox" name="Coupons[day][]" value="周四">周四
                                    </label>
                                </div>
                                <div class="checkbox-inline">
                                    <label>
                                        <input type="checkbox" class="day_checkbox" name="Coupons[day][]" value="周五">周五
                                    </label>
                                </div>
                                <div class="checkbox-inline">
                                    <label>
                                        <input type="checkbox" class="day_checkbox" name="Coupons[day][]" value="周六">周六
                                    </label>
                                </div>
                                <div class="checkbox-inline">
                                    <label>
                                        <input type="checkbox" class="day_checkbox" name="Coupons[day][]" value="周日">周日
                                    </label>
                                </div>
                                <div class="form-inline mt20">
                                    <span class="pl20">时间</span>
                                    <input type="text" name="Coupons[begin_hour]"
                                           value="<?php echo isset($_POST['Coupons']['begin_hour']) ? $_POST['Coupons']['begin_hour'] : !empty($model_info['begin_hour']) ? $model_info['begin_hour'] : ''; ?>"
                                           class="form-control fc-v-sm ml10 ">
                                    <span>至</span>
                                    <input type="text" name="Coupons[end_hour]"
                                           value="<?php echo isset($_POST['Coupons']['end_hour']) ? $_POST['Coupons']['end_hour'] : !empty($model_info['end_hour']) ? $model_info['end_hour'] : ''; ?>"
                                           class="form-control fc-v-sm ">
                                    <span class="pl10 text-gray">不填写则全天可用</span>

                                </div>
                                <span id="hour_error" class="red" style="display:none">
                                        结束时间不可早于开始时间
                                    </span>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="form-group m-cp-info js-cp-voucher">
                    <div class="cp-title mt6">
                        <span>减免金额<em>*</em></span>
                    </div>
                    <div class="cp-con">
                        <div class="input-group fc-sm m-input-group">
                            <?php echo CHtml::textField('Coupons[reduce_cost]', isset($_POST['Coupons']['reduce_cost']) ? $_POST['Coupons']['reduce_cost'] : empty($model['money']) ? '' : $model['money'] * 100 / 100, array('class' => 'form-control', 'readonly' => 'readonly', 'placeholder' => '最多6位数字', 'maxlength' => 6)); ?>
                            <span class="input-group-addon">元</span>
                        </div>
                        <span class="text1 red">
                              <?php if (Yii::app()->user->hasFlash('money_error')) { ?>
                                  <?php echo Yii::app()->user->getFlash('money_error'); ?>
                              <?php } ?>
                        </span>
                    </div>

                </div>
                <div class="form-group m-cp-info js-cp-voucher">
                    <div class="cp-title mt6">
                        <span>减免条件<em>*</em></span>
                    </div>
                    <div class="cp-con">
                        <div class="input-group fc-sm m-input-group">
                            <input type="text" name="Coupons[least_cost]" readonly
                                   value="<?php echo isset($_POST['Coupons']['least_cost']) ? $_POST['Coupons']['least_cost'] : empty($model['mini_consumption']) ? '' : $model['mini_consumption'] * 100 / 100 ?>"
                                   class="form-control">
                            <span class="input-group-addon">元</span>
                        </div>
                        <span class="text1 red">
                              <?php if (Yii::app()->user->hasFlash('least_cost_error')) { ?>
                                  <?php echo Yii::app()->user->getFlash('least_cost_error'); ?>
                              <?php } ?>
                        </span>

                        <p class="mt10 text-gray">消费满多少元可用</p>
                    </div>
                </div>
                <div class="form-group m-cp-info js-cp-discount" style="display: none;">
                    <div class="cp-title mt6">
                        <span>折扣条件</span>
                    </div>
                    <div class="cp-con">
                        <div class="input-group fc-sm m-input-group">
                            <input type="text" name="Coupons[discount_rule]" readonly
                                   value="<?php echo isset($_POST['Coupons']['discount_rule']) ? $_POST['Coupons']['discount_rule'] : empty($model['mini_consumption']) ? '' : $model['mini_consumption'] * 100 / 100 ?>"
                                   class="form-control">
                            <span class="input-group-addon">元</span>
                        </div>
                        <p class="mt10 text-gray">消费满多少元可用,不填写则默认不限制</p>
                    </div>
                </div>
                <div class="form-group m-cp-info js-cp-discount" style="display: none;">
                    <div class="cp-title mt6">
                        <span>折扣额度<em>*</em></span>
                    </div>
                    <div class="cp-con">
                        <div class="input-group fc-sm m-input-group">
                            <input type="text" name="Coupons[discount]" readonly
                                   value="<?php echo isset($_POST['Coupons']['discount']) ? $_POST['Coupons']['discount'] : empty($model['discount']) ? '' : $model['discount'] * 10 ?>"
                                   class="form-control">
                            <span class="input-group-addon">折</span>

                        </div>
                        <span class="text1 red">
                              <?php if (Yii::app()->user->hasFlash('discount_error')) { ?>
                                  <?php echo Yii::app()->user->getFlash('discount_error'); ?>
                              <?php } ?>
                        </span>

                        <p class="mt10 text-gray">请填写1-9.9之间的数字，精确到小数点后1位</p>
                    </div>
                </div>
                <div class="form-group m-cp-info js-cp-exchange" style="display: none;">
                    <div class="cp-title mt6">
                        <span>兑换条件</span>
                    </div>
                    <div class="cp-con">
                        <div class="input-group fc-sm m-input-group">
                            <input type="text" name="Coupons[gift_rule]" readonly
                                   value="<?php echo isset($_POST['Coupons']['gift_rule']) ? $_POST['Coupons']['gift_rule'] : empty($model['mini_consumption']) ? '' : $model['mini_consumption'] * 100 / 100 ?>"
                                   class="form-control">
                            <span class="input-group-addon">元</span>
                        </div>
                        <p class="mt10 text-gray">消费满多少元可用,不填写则默认不限制</p>
                    </div>
                </div>
            </section>
            <section class="m-cp-item">
                <h5 class="title">服务信息</h5>

                <div class="form-group m-cp-info js-cp-use" <?php if($model['type'] == 3){echo 'style="display:none"';} ?>>
                    <div class="cp-title">
                        <span>使用条件</span>
                    </div>
                    <div class="cp-con">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="Coupons[if_with_userdiscount]"
                                    <?php if (isset($_POST['Coupons']['if_with_userdiscount'])) { ?>
                                        <?php echo 'checked'; ?>
                                    <?php } else { ?>
                                        <?php if ($model['if_with_userdiscount'] == 2) { ?>
                                            <?php echo 'checked'; ?>
                                        <?php } else { ?>
                                            <?php echo ''; ?>
                                        <?php } ?>
                                    <?php } ?>
                                    >可与会员折扣同时使用
                            </label>

                        </div>
                    </div>
                </div>
                <div class="form-group m-cp-info">
                    <div class="cp-title">
                        <span>使用须知</span>
                    </div>
                    <div class="cp-con">
                        <textarea class="form-control" name="Coupons[use_illustrate]"
                                  placeholder="请填写使用本优惠劵的注意事项，最多300字" rows="5"></textarea>
                        <span class="text1 red">
                              <?php if (Yii::app()->user->hasFlash('use_illustrate_error')) { ?>
                                  <?php echo Yii::app()->user->getFlash('use_illustrate_error'); ?>
                              <?php } ?>
                        </span>
                    </div>

                </div>
                <div class="form-group m-cp-info js-cp-exchange" style="display: none;">
                    <div class="cp-title">
                        <span>兑换详情<em>*</em></span>
                    </div>
                    <div class="cp-con">
                        <textarea class="form-control" readonly name="Coupons[gift_info]"
                                  placeholder="请填写兑换的具体内容，最多300字" rows="5"></textarea>
                    <span class="text1 red">
                              <?php if (Yii::app()->user->hasFlash('gift_info')) { ?>
                                  <?php echo Yii::app()->user->getFlash('gift_info'); ?>
                              <?php } ?>
                        </span>
                    </div>

                </div>

                <div class="form-group m-cp-info">
                    <div class="cp-title">
                        <span>客服电话</span>
                    </div>

                    <div class="cp-con">
                        <input type="text" id="Coupons_tel" name="Coupons[tel]"
                               value="<?php echo isset($_POST['Coupons']['tel']) ? $_POST['Coupons']['tel'] : empty($model['tel']) ? '' : $model['tel']; ?>"
                               class="form-control fc-sm">
                    </div>
                    
                        <span class="text1 red">      
                        </span>
                </div>
            </section>
            <section class="m-cp-item">
                <h5 class="title">优惠详情</h5>

                <div class="form-group m-cp-info">
                    <div class="cp-title">
                        <span>封面图片</span>
                    </div>
                    <div class="cp-con">
                        <div class="m-upload">
                            <div class="upload-inner">
                                <img alt="" id="right_cover_img"
                                     src="<?php echo isset($_POST['Coupons']['cover_img']) && !empty($_POST['Coupons']['cover_img']) ? IMG_GJ_LIST . $_POST['Coupons']['cover_img'] : isset($model['cover_img']) && !empty($model['cover_img']) ? IMG_GJ_LIST . $model['cover_img'] : ''; ?>">

                                <div class="upload-icon">
                                    <div class="upload-btn">

                                        <input type="text" id="upload" class="file">
                                        <input type="hidden" name="Coupons[cover_img]"
                                               value="<?php echo isset($_POST['Coupons']['cover_img']) ? $_POST['Coupons']['cover_img'] : empty($model_info['abstract']['cover_img']) ? '' : $model_info['abstract']['cover_img'] ?>">
                                    </div>
                                    <!-- <div class="ml10 js-del-imgpost">
                                        <i id="del-first-cover" class="del-imgpost-icon"></i>
                                    </div>-->
                                </div>
                            </div>
                        </div>
                        <p class="mt10 text-gray">图片建议尺寸：850像素*350像素，大小不超过1M）</p>
                    </div>
                </div>
                <div class="form-group m-cp-info">
                    <div class="cp-title mt6">
                        <span>封面简介</span>
                    </div>
                    <div class="cp-con">
                        <input type="text" name="Coupons[cover_img_text]"
                               value="<?php echo isset($_POST['Coupons']['cover_img_text']) ? $_POST['Coupons']['cover_img_text'] : isset($model['cover_title']) && !empty($model['cover_title']) ? $model['cover_title'] : '' ?>"
                               class="form-control fc-sm">
                    </div>
                </div>

                <div class="form-group m-cp-info">
                    <div class="cp-title">
                        <span>图文介绍</span>
                    </div>
                    <div class="cp-con">
                        <?php if (isset($_POST['img_text']) && !empty($_POST['img_text'])) { ?>
                            <?php foreach ($_POST['img_text'] as $k => $v) { ?>
                                <?php if ($k == 0) { ?>
                                    <div class="m-imgpost mb20">
                                        <div class="m-upload">
                                            <div class="upload-inner">
                                                <img alt="" id="right_img_text0"
                                                     src="<?php echo isset($_POST['img_text'][0]) && !empty($_POST['img_text'][0]) ? IMG_GJ_LIST . $_POST['img_text'][0] : '' ?>">

                                                <div class="upload-icon">
                                                    <div class="upload-btn">

                                                        <input type="file" id="img_upload" class="file">
                                                        <input type="hidden" name="img_text[0]"
                                                               value="<?php echo isset($_POST['img_text'][0]) ? $_POST['img_text'][0] : '' ?>">
                                                    </div>
                                                    <!-- <div class="ml10 js-del-imgpost">
                                                        <i id="del-first-text" class="del-imgpost-icon"></i>
                                                    </div> -->
                                                </div>
                                            </div>
                                        </div>
                                        <p class="mt10 text-gray">图片建议尺寸：900像素 * 500像素，大小不超过1M。</p>

                                        <p class="text-gray">至少上传1组图文，最多上传20组。描述最多输入5000字</p>
                                        <textarea class="form-control cover-desc" name="img_text_title[0]"
                                                  placeholder="输入描述,最多5000字" rows="3"></textarea>
                                    </div>
                                <?php } else { ?>
                                    <div class="m-imgpost mb20">
                                        <div class="m-upload">
                                            <div class="upload-inner">
                                                <img alt="" id="<?php echo 'right_img_text[' . $k . ']'; ?>"
                                                     src="<?php echo isset($_POST["img_text"][$k]) && !empty($_POST['img_text'][$k]) ? IMG_GJ_LIST . $_POST['img_text'][$k] : '' ?>">

                                                <div class="upload-icon">
                                                    <div class="upload-btn">

                                                        <input type="file" id="img_upload" class="file">
                                                        <input type="hidden"
                                                               name="<?php echo 'img_text[' . $k . ']'; ?>"
                                                               value="<?php echo isset($_POST['img_text'][$k]) ? $_POST['img_text'][$k] : '' ?>">
                                                    </div>
                                                    <div class="ml10 js-del-imgpost">
                                                        <i class="del-imgpost-icon"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <textarea class="form-control cover-desc" name="img_text_title[0]"
                                                  placeholder="输入描述,最多5000字" rows="3"></textarea>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        <?php } else { ?>
                            <?php if (!empty($model_info['text_image_list'])) { ?>
                                <?php foreach ($model_info['text_image_list'] as $k => $v) { ?>
                                    <?php if ($k == 0) { ?>
                                        <div class="m-imgpost mb20">
                                            <div class="m-upload">
                                                <div class="upload-inner">
                                                    <img alt="" id="right_img_text0"
                                                         src="<?php echo isset($v['image_url']) && !empty($v['image_url']) ? IMG_GJ_LIST . $v['image_url'] : '' ?>">

                                                    <div class="upload-icon">
                                                        <div class="upload-btn">

                                                            <input type="file" id="img_upload" class="file">
                                                            <input type="hidden" name="img_text[0]"
                                                                   value="<?php echo isset($v['image_url']) && !empty($v['image_url']) ? $v['image_url'] : '' ?>">
                                                        </div>
                                                        <!-- <div class="ml10 js-del-imgpost">
                                                            <i id="del-first-text" class="del-imgpost-icon"></i>
                                                        </div>-->
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="mt10 text-gray">图片建议尺寸：900像素 * 500像素，大小不超过1M。</p>

                                            <p class="text-gray">至少上传1组图文，最多上传20组。描述最多输入5000字</p>
                                            <?php echo isset($v['image_url']) && !empty($v['image_url']) ? '<script>$(function(){$("#img_txt0").html("' . $v['text'] . '");});</script>' : ''; ?>
                                            <textarea class="form-control cover-desc" id="img_txt0"
                                                      name="img_text_title[0]" placeholder="输入描述,最多5000字"
                                                      rows="3"></textarea>
                                        </div>
                                    <?php } else { ?>
                                        <div class="m-imgpost mb20">
                                            <div class="m-upload">
                                                <div class="upload-inner">
                                                    <img alt="" id="right_img_text<?php echo $k ?>"
                                                         src="<?php echo isset($v['image_url']) && !empty($v['image_url']) ? IMG_GJ_LIST . $v['image_url'] : '' ?>">

                                                    <div class="upload-icon">
                                                        <div class="upload-btn">

                                                            <input type="file" id="img_upload<?php echo $k; ?>"
                                                                   class="file">
                                                            <input type="hidden" name="img_text[<?php echo $k; ?>]"
                                                                   value="<?php echo isset($v['image_url']) && !empty($v['image_url']) ? $v['image_url'] : '' ?>">
                                                        </div>
                                                        <div class="ml10 js-del-imgpost">
                                                            <i  class="del-imgpost-icon"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php echo isset($v['image_url']) && !empty($v['image_url']) ? '<script>$(function(){$("#img_txt' . $k . '").html("' . $v['text'] . '");});</script>' : ''; ?>
                                            <textarea <?php echo 'id="img_txt' . $k . '"'; ?>
                                                name="img_text_title[<?php echo $k; ?>]" class="form-control cover-desc"
                                                placeholder="输入描述,最多5000字" rows="3"></textarea>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            <?php } else { ?>
                                <div class="m-imgpost mb20">
                                    <div class="m-upload">
                                        <div class="upload-inner">
                                            <img alt="" id="right_img_text0" src="">

                                            <div class="upload-icon">
                                                <div class="upload-btn">

                                                    <input type="file" id="img_upload" class="file">
                                                    <input type="hidden" name="img_text[0]" value="">
                                                </div>
                                                <!-- <div class="ml10 js-del-imgpost">
                                                    <i id="del-first-text" class="del-imgpost-icon"></i>
                                                </div>-->
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt10 text-gray">图片建议尺寸：900像素 * 500像素，大小不超过1M。</p>

                                    <p class="text-gray">至少上传1组图文，最多上传20组。描述最多输入5000字</p>
                                    <textarea class="form-control cover-desc" name="img_text_title[0]"
                                              placeholder="输入描述,最多5000字" rows="3"></textarea>
                                </div>
                            <?php } ?>
                        <?php } ?>
                        <a href="javascript:;" class="m-add-btn" id="js-add-imgpost">新增图文</a>
                    </div>
                </div>
            </section>
            <section class="m-cp-item">
                <h5 class="title">营销入口</h5>

                <div class="panel panel-default m-panel">
                    <div class="panel-heading">
                        <h3 class="panel-title">营销入口一</h3>

                    </div>
                    <div class="panel-body">
                        <div class="form-group m-cp-info">
                            <div class="cp-title mt6">
                                <span>入口名称</span>
                            </div>
                            <div class="cp-con">
                                <input type="text" name="Coupons[custom_url_name]"
                                       value="<?php echo isset($_POST['Coupons']['custom_url_name']) ? $_POST['Coupons']['custom_url_name'] : empty($model_info['custom_url_name']) ? '' : $model_info['custom_url_name'] ?>"
                                       class="form-control fc-sm" placeholder="请填写入口名称">
                            </div>
                        </div>
                        <div class="form-group m-cp-info">
                            <div class="cp-title mt6">
                                <span>提示语</span>
                            </div>
                            <div class="cp-con">
                                <input type="text" name="Coupons[custom_url_sub_title]"
                                       value="<?php echo isset($_POST['Coupons']['custom_url_sub_title']) ? $_POST['Coupons']['custom_url_sub_title'] : empty($model_info['custom_url_sub_title']) ? '' : $model_info['custom_url_sub_title'] ?>"
                                       class="form-control fc-sm" placeholder="请填写提示语">
                            </div>
                        </div>
                        <div class="form-group m-cp-info">
                            <div class="cp-title">
                                <span>跳转链接</span>
                            </div>
                            <div class="cp-con js-jump-link">
                                <!--<div class="radio-inline">
                                    <label>
                                        <input type="radio" name="jump-link" value="1" n="1" > 常用链接
                                    </label>
                                </div>  -->
                                <div class="radio-inline">
                                    <label>
                                        <input type="radio" name="jump-link" value="2" n="2" checked>自定义链接
                                    </label>
                                </div>
                                <div class="form-group mt20 choose-button" style="display: none;">
                                    <button type="button" class="btn btn-default choose_link" onclick="show_link(1)">
                                        选择链接
                                    </button>
                                    <span class="choosed-link pl10"></span>
                                </div>
                                <div class="form-group mt20 link-input">
                                    <input type="text" id="panel1_link" name="Coupons[custom_url]"
                                           value="<?php echo isset($_POST['Coupons']['custom_url']) ? $_POST['Coupons']['custom_url'] : empty($model_info['custom_url']) ? '' : $model_info['custom_url'] ?>"
                                           class="form-control fc-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="panel panel-default m-panel js-entry-tpl" <?php echo empty($model_info['promotion_url_name']) ? 'style="display: none;"' : ''; ?>>
                    <div class="panel-heading">
                        <h3 class="panel-title">营销入口二</h3>
                        <a href="javascript:;" class="fr js-entry-del">删除</a>
                    </div>
                    <div class="panel-body">
                        <div class="form-group m-cp-info">
                            <div class="cp-title mt6">
                                <span>入口名称</span>
                            </div>
                            <div class="cp-con">
                                <input type="text" name="Coupons[promotion_url_name]"
                                       value="<?php echo isset($_POST['Coupons']['promotion_url_name']) ? $_POST['Coupons']['promotion_url_name'] : empty($model_info['promotion_url_name']) ? '' : $model_info['promotion_url_name'] ?>"
                                       class="form-control fc-sm" placeholder="请填写入口名称">
                            </div>
                        </div>
                        <div class="form-group m-cp-info">
                            <div class="cp-title">
                                <span>跳转链接</span>
                            </div>
                            <div class="cp-con js-jump-link">
                                <!-- <div class="radio-inline">
                                    <label>
                                        <input type="radio" name="jump-link-1" value="1" n="1" > 常用链接
                                    </label>
                                </div>-->
                                <div class="radio-inline">
                                    <label>
                                        <input type="radio" name="jump-link-1" value="2" n="2" checked>自定义链接
                                    </label>
                                </div>
                                <div class="form-group mt20 choose-button1" style="display: none;">
                                    <button type="button" class="btn btn-default choose_link" onclick="show_link(2)">
                                        选择链接
                                    </button>
                                    <span class="choosed-link-1 pl10"></span>
                                </div>
                                <div class="form-group mt20 link-input1">
                                    <input type="text" id="panel2_link" name="Coupons[promotion_url]"
                                           value="<?php echo isset($_POST['Coupons']['promotion_url']) ? $_POST['Coupons']['promotion_url'] : empty($model_info['promotion_url']) ? '' : $model_info['promotion_url'] ?>"
                                           class="form-control fc-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="javascript:;" class="m-add-btn add-entry-btn" id="js-add-entry">添加自定义入口</a>
            </section>
            <div class="form-inline m-form-group mt30">
                <button type="button" class="btn btn-primary m-btn" id="js-cp-next">下一步</button>
                <button type="button" class="btn btn-default m-btn" onclick="cancel()">取消</button>
            </div>
        </div>
    </div>
    <!-- 功能设置页面 -->
    <div class="m-fun-set js-step-2" style="display: none;">
        <div class="m-fun-set-item mt30">
            <div class="panel panel-default m-panel">
                <div class="panel-heading">
                    <h3 class="panel-title">使用设置</h3>
                </div>
                <div class="panel-body">
                    <div class="form-group m-cp-info">
                        <div class="cp-title mt6">
                            <span>库存<em>*</em></span>
                        </div>
                        <div class="cp-con">
                            <div class="input-group fc-sm">
                                <input type="text" name="Coupons[num]"
                                       value="<?php echo isset($_POST['Coupons']['num']) ? $_POST['Coupons']['num'] : $model['num'] ?>"
                                       readonly class="form-control">
                                <span class="input-group-addon">份</span>
                            </div>
                            <span class="text1 red">
                              <?php if (Yii::app()->user->hasFlash('num_error')) { ?>
                                  <?php echo Yii::app()->user->getFlash('num_error'); ?>
                              <?php } ?>
                            </span>
                        </div>
                    </div>
                    <div class="form-group m-cp-info">
                        <div class="cp-title mt6">
                            <span>每人限额</span>
                        </div>
                        <div class="cp-con">
                            <div class="input-group fc-sm">
                                <input type="text" name="Coupons[receive_num]"
                                       value="<?php echo isset($_POST['Coupons']['receive_num']) ? $_POST['Coupons']['receive_num'] : empty($model['receive_num']) ? '' : $model['receive_num'] ?>"
                                       class="form-control">
                                <span class="input-group-addon">份</span>
                            </div>
                            <span class="text1 red">
                              <?php if (Yii::app()->user->hasFlash('receive_num_error')) { ?>
                                  <?php echo Yii::app()->user->getFlash('receive_num_error'); ?>
                              <?php } ?>
                            </span>
                            <span class="text-gray get-cp-tips">每个用户领券上限，如不填，则默认为1</span>
                        </div>
                    </div>
                    <div class="form-group m-cp-info">
                        <div class="checkbox-inline">
                            <label>
                                <input type="checkbox" name="Coupons[if_share]"
                                       value="1" <?php echo $model['if_share'] == 1 ? 'checked' : '' ?>>用户可以分享领券链接
                            </label>
                        </div>
                        <div class="checkbox-inline">
                            <label>
                                <input type="checkbox" name="Coupons[if_give]"
                                       value="1" <?php echo $model['if_give'] == 1 ? 'checked' : '' ?>>用户领券后可转增其他好友
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="m-fun-set-item mt30">
            <div class="panel panel-default m-panel">
                <div class="panel-heading">
                    <h3 class="panel-title">门店信息</h3>
                </div>
                <div class="panel-body">
                    <div class="form-group m-cp-info">
                        <div class="cp-title">
                            <span>使用渠道</span>
                        </div>
                        <div class="cp-con">
                            <div class="checkbox-inline">
                                <label>
                                    <input type="checkbox" name="Coupons[channel1]"
                                           value="1" <?php echo $model['use_channel'] == 3 || $model['use_channel'] == 1 ? 'checked' : ''; ?>>线下门店
                                </label>
                            </div>
                            <div class="checkbox-inline">
                                <label>
                                    <input type="checkbox" name="Coupons[channel2]"
                                           value="2" <?php echo $model['use_channel'] == 3 || $model['use_channel'] == 2 ? 'checked' : ''; ?>>线上使用
                                </label>
                            </div>

                            <span class="text-gray pl20">在优惠券在商城上渠道使用时勾选上使用</span>
                        </div>
                    </div>
                    <div class="form-group m-cp-info">
                        <div class="cp-title">
                            <span>适用门店</span>
                        </div>
                        <div class="cp-con js-apply-store">
                            <div class="radio-inline">
                                <label>
                                    <input type="radio" name="applicable-store" value="1" n="1" checked>全部适用
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input type="radio" name="applicable-store" id="limit_type2" value="2" n="2">指定门店适用
                                </label>
                            </div>
                            <a href="javascript:;" id="add_stores" class="m-add-btn ml10"
                               style="display: none;">添加指定门店</a>
                            <input type="text" style="opacity: 0;filter: alpha(opacity=0);" id="str_id" name="Coupons[store_limit]"
                                   value="<?php echo isset($_POST['Coupons']['store_limit']) ? $_POST['Coupons']['store_limit'] : empty($model['store_limit']) ? '' : substr($model['store_limit'], 0, strlen($model['store_limit']) - 1) ?>"/>
                            <table id="store_table" class="table g-table mt20 store-table" style="display: none;">
                                <thead>
                                <tr>
                                    <th>门店名称</th>
                                    <th>门店地址</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($model_info['store_info'])) { ?>
                                    <?php foreach ($model_info['store_info'] as $k => $v) { ?>
                                        <tr>
                                            <td><?php echo $v['name'] ?></td>
                                            <td><?php echo $v['address'] ?></td>
                                            <td><a href="javascript:;"
                                                   onclick="delstore(this,<?php echo $v['id'] ?>)">删除</a></td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="form-group m-cp-info">
                        <div class="cp-title mt6">
                            <span>操作提示<em>*</em></span>
                        </div>
                        <div class="cp-con">
                            <input type="text" name="Coupons[prompt]"
                                   value="<?php echo isset($_POST['Coupons']['prompt']) ? $_POST['Coupons']['prompt'] : $model['prompt'] ?>"
                                   class="form-control fc-sm">
                            <span class="text-gray get-cp-tips">建议引导用户到店出示卡券，由店员完成核销操作</span>
                        <span class="text1 red">
                              <?php if (Yii::app()->user->hasFlash('prompt_error')) { ?>
                                  <?php echo Yii::app()->user->getFlash('prompt_error'); ?>
                              <?php } ?>
                        </span>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="form-inline m-form-group mt30">
            <button type="button" class="btn btn-default m-btn" id="js-cp-prev">上一步</button>
            <button type="submit" class="btn btn-primary m-btn">提交审核</button>
            <button type="button" class="btn btn-default m-btn" onclick="cancel()">取消</button>
        </div>

    </div>
</div>
<!-- 选择常用链接弹出 -->
<div class="m-pop links-pop" style="display:none">
    <input type="hidden" id="panel">

    <div class="m-pop-title">
        <h4>常用链接</h4>
        <i class="m-pop-close fr fa fa-times i_link"></i>
    </div>
    <div class="m-pop-con">
        <p class="links-tip mt20">使用方法：点击选中直接返回对应模块外链代码，或者点击详情选择具体的内容外链</p>
        <h5 class="fw mt20">请选择版块：</h5>

        <div class="links-box mt20">
            <div class="btn-group m-btn-group-sm">
                <button type="button" class="btn btn-default">营销活动链接</button>
                <button type="button" class="btn btn-default">功能链接</button>
                <button type="button" class="btn btn-default">素材链接</button>
                <button type="button" class="btn btn-default">应用链接</button>
            </div>
            <div class="row links-row">
                <div class="col-sm-3">
                    <div class="row links-row-child">
                        <div class="col-sm-8">
                            微信优惠券
                        </div>
                        <div class="col-sm-4">
                            <button type="button" class="btn btn-default">详情</button>
                        </div>
                    </div>
                    <div class="row links-row-child">
                        <div class="col-sm-8">
                            大转盘
                        </div>
                        <div class="col-sm-4">
                            <button type="button" class="btn btn-default">详情</button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="row links-row-child">
                        <div class="col-sm-8">
                            商城主页
                        </div>
                        <div class="col-sm-4">
                            <button type="button" class="btn btn-default choosed">选中</button>
                            <input type="hidden" value="link-1" n="功能链接>商城主页">
                        </div>
                    </div>
                    <div class="row links-row-child">
                        <div class="col-sm-8">
                            我的订单
                        </div>
                        <div class="col-sm-4">
                            <button type="button" class="btn btn-default choosed">选中</button>
                            <input type="hidden" value="link-2" n="功能链接>我的订单">
                        </div>
                    </div>
                    <div class="row links-row-child">
                        <div class="col-sm-8">
                            会员中心
                        </div>
                        <div class="col-sm-4">
                            <button type="button" class="btn btn-default choosed">选中</button>
                            <input type="hidden" value="link-3" n="功能链接>会员中心">
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="row links-row-child">
                        <div class="col-sm-8">
                            图文列表
                        </div>
                        <div class="col-sm-4">
                            <button type="button" class="btn btn-default">详情</button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="row links-row-child">
                        <div class="col-sm-8">
                            官网
                        </div>
                        <div class="col-sm-4">
                            <button type="button" class="btn btn-default choosed" n="应用链接>官网">选中</button>
                            <input type="hidden" value="link-4">
                        </div>
                    </div>
                    <div class="row links-row-child">
                        <div class="col-sm-8">
                            相册

                        </div>
                        <div class="col-sm-4">
                            <button type="button" class="btn btn-default choosed" n="应用链接>相册">选中</button>
                            <input type="hidden" value="link-5">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center">
            <button type="button" id="close_link_pop" class="btn btn-primary m-btn">取消</button>
        </div>
    </div>
</div>
<!-- 选择门店弹出框 -->
<div class="m-pop store-pop" style="display: none;">
    <div class="m-pop-title">
        <h4>选择门店</h4>
        <i class="m-pop-close fr fa fa-times" id="store_close_icon"></i>
    </div>
    <div class="m-pop-con">
        <div class="v-select-store mt20">
            <div class="tree1" id="store_list">
                <i class="rbArrow"></i>
                <input type="checkbox" class="parentsBox"><i
                    class="iconFolder"></i>全部(<?php echo isset($count_store) ? $count_store : '0' ?>)
                <div class="comList">
                    <?php if (!empty($store_list)) { ?>
                        <div class="child1">
                            <ul>
                                <?php foreach ($store_list as $k => $v) { ?>
                                    <li class="child3">
                                        <input type="checkbox" name="store_id[]" id="store<?php echo $v->id ?>"
                                               info="<?php echo $v->name . '&' . $v->address . '&' . $v->id ?>"
                                               class="storeBox" value="<?php echo $v->id; ?>">
                                        <i class="iconFile"></i><?php echo $v->name; ?>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    <?php } ?>

                    <?php if (!empty($manage_arr)) { ?>
                        <?php foreach ($manage_arr as $k => $v) { ?>
                            <div class="child1">
                                <div class="parentWrap">
                                    <i class="rbArrow"></i>
                                    <input type="checkbox" class="parentBox" id="childone<?php echo $k; ?>"><i
                                        class="iconFolder"></i><?php echo $v['name']; ?>
                                </div>
                                <ul class="firstManage<?php echo $k; ?>">
                                    <?php if (!empty($v['firstStore'])) { ?>
                                        <?php foreach ($v['firstStore'] as $ke => $val) { ?>
                                            <li class="child3">
                                                <input type="checkbox" name="store_id[]"
                                                       id="store<?php echo $val['id'] ?>"
                                                       info="<?php echo $val['name'] . '&' . $val['address'] . '&' . $val['id'] ?>"
                                                       class="storeBox" value="<?php echo $val['id']; ?>"><i
                                                    class="iconFile"></i><?php echo empty($val['branch_name']) ? $val['name'] : $val['name']."-".$val['branch_name']; ?>
                                            </li>

                                        <?php } ?>
                                    <?php } ?>
                                </ul>
                                <?php if (!empty($v['sub'])) { ?>

                                    <?php foreach ($v['sub'] as $key => $value) { ?>
                                        <div class="childWrap child3">
                                            <i class="rbArrow"></i>
                                            <input type="checkbox" class="childBox">
                                            <i class="iconFolder"></i>
                                            <?php echo $value['name']; ?>
                                        </div>
                                        <ul id="sub<?php echo $value['id']; ?>">
                                            <?php if (!empty($value['store'])) { ?>
                                                <?php foreach ($value['store'] as $ked => $va) { ?>
                                                    <li class="child4">
                                                        <input type="checkbox" name="store_id[]"
                                                               id="store<?php echo $va['id'] ?>"
                                                               info="<?php echo $val['name'] . '&' . $val['address'] . '&' . $val['id'] ?>"
                                                               class="storeBox" value="<?php echo $val['id']; ?>"
                                                               class="storeBox" value="<?php echo $va['id']; ?>" n=""><i
                                                            class="iconFile"></i><?php echo $va['name']; ?>
                                                    </li>
                                                <?php } ?>
                                            <?php } ?>
                                        </ul>
                                    <?php } ?>
                                <?php } ?>

                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="v-selected-num">
        <span>已选<em id="haschecked" class="text-blue">0</em>家，共<em
                class="text-blue"><?php echo $count_store ?></em>家
        </span>
        </div>
        <div class="text-center mt20">
            <button type="button" id="store_choose" class="btn btn-primary m-btn">确定</button>
        </div>
    </div>
</div>




<?php echo CHtml::endForm(); ?>
<script>
    $(function () {
        var url = '<?php echo Yii::app()->createUrl('mCenter/cardCoupons/CouponList');?>';
        var result = '<?php echo $result;?>';
        if (result == 1) {
            $('#create_tips').show();
            window.setTimeout('window.location.href="' + url + '"', 2000);
        } else if (result == 3) {
            $('#createfail_tips').show();
            window.setTimeout('window.location.href="' + url + '"', 2000);
        } else {

        }
        addCouponImg_upload('upload', '<?php echo UPLOAD_TO_PATH?>', '<?php echo WQ_STATIC_JS?>', "<?php echo UPLOAD_IMG_TYPE;?>", '<?php echo(IMG_GJ_FOLDER)?>', '<?php echo IMG_GJ_LIST?>');
    });


    $(function () {

        addCouponImg_upload2('img_upload', '<?php echo UPLOAD_TO_PATH?>', '<?php echo WQ_STATIC_JS?>', "<?php echo UPLOAD_IMG_TYPE;?>", '<?php echo(IMG_GJ_FOLDER)?>', '<?php echo IMG_GJ_LIST?>');
    });


    var i = 99;
    var idIndex = 100;
    $("#js-add-imgpost").click(function () {
        var imgpostTpl = $('<div class="m-imgpost mb20">' +
        '<div class="m-upload">' +
        '<div class="upload-inner">' +
        '<img alt="" id="right_img_text' + idIndex + '" src="">' +
        '<div class="upload-icon">' +
        '<div class="upload-btn">' +
        '<input type="file" class="file cp-file"' + 'id="cp-upload' + idIndex + '"' + '>' +
        '<input type="hidden" ' + 'name="img_text[' + idIndex + ']"' + '>' +
        '</div>' +
        '<div class="ml10 js-del-imgpost">' +
        '<i class="del-imgpost-icon"></i>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<textarea name="img_text_title[' + idIndex + ']"  class="form-control cover-desc" placeholder="输入描述,最多5000字" rows="3"></textarea>' +
        '</div>');
        idIndex++;
        $(".m-imgpost").last().after(imgpostTpl);

        i++;
        var id = i;
        addCouponImgList_upload(id, '<?php echo UPLOAD_TO_PATH?>', '<?php echo GJ_STATIC_JS?>', "<?php echo UPLOAD_IMG_TYPE;?>", '<?php echo(IMG_GJ_FOLDER)?>', '<?php echo IMG_GJ_LIST?>');
        window.parent.callParAutoResize("main", $("body").height());
    });

    //删除图文
    $(document).on("click", ".js-del-imgpost", function () {
        var imgpostList = $(".m-imgpost");
        console.log(imgpostList.length);
        if (imgpostList.length === 1) {
            return false;
        } else {
            $(this).parents(".m-imgpost").remove();
        }
    });


    var now = new Date();
    //调用时间控件
    $('input[name="Coupons[time]"]').daterangepicker({
        timePicker: false,
        format: 'YYYY/MM/DD',
        dateLimit: true,
        maxDate: true,
        minDate: now.toLocaleDateString(), //默认从今天开始
        opens: 'left'
    });


    // 弹出位置方法
    function popShow(el) {
        //显示位置计算
        var offset_y = $(window.parent.document).scrollTop() + 100;
        var offset_x = 100;
        var browserW = $(window).width();
        var browserH = $(window.parent.parent).height();
        var pop_obj = el;
        var pop_width = pop_obj.width();
        var pop_height = pop_obj.height();
        var x = (browserW - pop_width) / 2 + offset_x;
        var y = (browserH - pop_height) / 2 + offset_y;
        if (x < 410) {
            x = 410;
        }
        if (y < 300) {
            y = 300;
        }
        //设置显示位置
        pop_obj.css({'left': x, 'top': y});
        pop_obj.show();
    }
    //常用链接
    function show_link(panel) {
        $('.links-pop').find("input[id='panel']").val(panel);
        popShow($('.links-pop'));
    }
    $('.add-entry-btn').click(function () {
        window.parent.callParAutoResize("main", $("body").height());
    });

    $('#close_link_pop').click(function () {
        $('.links-pop').hide();
    });

    $('.i_link').click(function () {
        $('.links-pop').hide();
    });

    $('.choosed').click(function () {
        var link = $(this).parent().find('input').val();
        var n = $(this).parent().find('input').attr("n");
        var panel = $('.links-pop').find("input[id='panel']").val();

        if (panel == 1) {
            $('#panel1_link').val(link);
            $('.choosed-link').html(n);
            $('.links-pop').hide();
        } else if (panel == 2) {
            $('#panel2_link').val(link);
            $('.choosed-link-1').html(n);
            $('.links-pop').hide();
        }
    });

    //门店弹出
    $('#add_stores').click(function () {
        popShow($('.store-pop'));
    });

    $('#store_close_icon').click(function () {
        $('.store-pop').hide();
    });

    //已选门店数
    $('#store_list input').click(function () {
        var haschecked = $(".storeBox:checked").length;
        console.log(haschecked);
        $('#haschecked').html(haschecked);
    });
    //已选门店
    var store_ids = '';
    var store_info = new Array();
    $('#store_choose').click(function () {
        store_ids = '';
        store_info = [];
        $('.storeBox').each(function () {

            if ($(this).is(":checked")) {
                store_ids = store_ids + ',' + $(this).val();
                //store_names.push($(this).next().next().html());
                store_info.push($(this).attr("info"));

            } else {

            }
        });

        $('#str_id').val(store_ids);
        $('.store-pop').hide();
        //console.log(store_info);
        //alert($('#str_id').val());
        //生成门店列表
        var tbody = '';
        $.each(store_info, function (n, value) {


            var trs = "";
            trs = "<tr><td>" + value.split("&")[0] + "</td><td>" + value.split("&")[1] + "</td><td><a href='javascript:;' onclick=" + "delstore(this," + value.split("&")[2] + ")" + ">删除</a></td></tr>";
            tbody += trs;

        });
        //console.log(tbody);
        $("#store_table tbody").html(tbody);
        if (store_ids) {
            $("#store_table").show();
            window.parent.callParAutoResize("main", $("body").height());
        }
    });

    //点击删除门店
    function delstore(obj, store_id) {
        var v = $('#str_id').val().replace("," + store_id, "");
        $('#str_id').val(v);
        $(obj).parents("tr").remove();
        if ($("#store_table").find("tbody").html() == "") {
            $("#store_table").hide();
        }
    }

    function cancel() {
        window.location.href = "<?php echo Yii::app()->createUrl('mCenter/cardCoupons/CouponList') ?>";
    }

    $(function () {
        var no = 'none';
        var coupon_type = <?php echo $model['type'] ?>;
        $("input[name='Coupons[type]'][value=" + coupon_type + "]").attr("checked", 'checked');
        $("input[name='Coupons[type]']").not("input:checked").attr("disabled", '');

        if (coupon_type == 2) {
            $('.js-cp-voucher').show();
            $('.js-cp-discount').hide();
            $(".js-cp-exchange").hide();
        } else if (coupon_type == 3) {
            $('.js-cp-discount').show();
            $('.js-cp-voucher').hide();
            $(".js-cp-exchange").hide();
        } else if (coupon_type == 4) {
            $(".js-cp-exchange").show();
            $('.js-cp-voucher').hide();
            $('.js-cp-voucher').hide();
        }


        //$("input[name='Coupons[type]'][value="+coupon_type+"]").onclick();

        var time_type = "<?php echo isset($_POST['Coupons']['time_type'])?$_POST['Coupons']['time_type']:$model['time_type']?>";

        $("input[name='Coupons[time_type]'][value=" + time_type + "]").attr("checked", 'checked');
        if (time_type == '2') {
            $("select[name='Coupons[start_days]']").attr("disabled", false);
            $("select[name='Coupons[effective_days]']").attr("disabled", false);
        }
        //$("input[name='Coupons[time_type]'][value="+time_type+"]").onclick();

        var available_time = "<?php echo isset($_POST['Coupons']['available_time'])?$_POST['Coupons']['available_time']:!empty($model['use_time_interval'])?'2':'1'?>";

        $("input[name='Coupons[available_time]'][value=" + available_time + "]").attr("checked", 'checked');
        if (available_time == 2) {
            $('.js-partial-time-r').show();

        }
        //$("input[name='Coupons[available_time]'][value="+available_time+"]").onclick();

        <?php if(isset($_POST['Coupons']['day']) && !empty($_POST['Coupons']['day'])){?>
        <?php foreach ($_POST['Coupons']['day'] as $k => $v){?>

        $("input[value=<?php echo $v?>]").attr("checked", 'checked');
        <?php }?>

        <?php }else{?>
        <?php if(!empty($model_info['interval'])){?>
        <?php foreach ($model_info['interval'] as $k => $v){?>

        $("input[value=<?php echo $v?>]").attr("checked", 'checked');
        <?php }?>
        <?php }?>
        <?php }?>
        var link1 = "<?php echo isset($_POST['jump-link'])?$_POST['jump-link']:'no'?>";
        if (link1 != 'no') {
            $("input[name='jump-link'][value=" + link1 + "]").attr("checked", 'checked');
            if (link1 == 1) {
                $('.choose-button').show();
                $('.link-input').hide();
            } else if (link1 == 2) {
                $('.choose-button').hide();
                $('.link-input').show();
            }
        }
        var link2 = "<?php echo isset($_POST['jump-link-1'])?$_POST['jump-link-1']:'no'?>";
        if (link2 != 'no') {
            $("input[name='jump-link-1'][value=" + link2 + "]").attr("checked", 'checked');
            if (link2 == 1) {
                $('.choose-button1').show();
                $('.link-input1').hide();
            } else if (link2 == 2) {
                $('.choose-button1').hide();
                $('.link-input1').show();
            }
        }
        var storetype = "<?php echo isset($_POST['applicable-store'])?$_POST['applicable-store']:isset($model['store_limit_type'])?$model['store_limit_type']:''?>";

        $("input[name='applicable-store'][value=" + storetype + "]").attr("checked", 'checked');

        var if_share = "<?php if(isset($_POST['Coupons']['if_share']) || $model['if_share'] == 1){echo 1;}else{echo 2;}?>";
        if (if_share == '1') {
            $("input[name='Coupons[if_share]']").attr("checked", 'checked');
        } else if (if_share == '2') {
            $("input[name='Coupons[if_share]']").attr("checked", false);
        }
        var if_give = "<?php if(isset($_POST['Coupons']['if_give']) || $model['if_give'] == 1){echo 1;}else{echo 2;}?>";
        if (if_give == '1') {
            $("input[name='Coupons[if_give]']").attr("checked", 'checked');
        } else if (if_give == '2') {
            $("input[name='Coupons[if_give]']").attr("checked", false);
        }
        var channel1 = "<?php if(isset($_POST['Coupons']['channel1']) || $model['use_channel'] == 1 || $model['use_channel'] == 3){echo 1;}else{echo 2;}?>";
        if (channel1 == '1') {
            $("input[name='Coupons[channel1]']").attr("checked", 'checked');
        } else if (channel1 == '2') {
            $("input[name='Coupons[channel1]']").attr("checked", false);
        }
        var channel2 = "<?php if(isset($_POST['Coupons']['channel2']) || $model['use_channel'] == 2 || $model['use_channel'] == 3){echo 1;}else{echo 2;}?>";
        if (channel2 == '1') {
            $("input[name='Coupons[channel2]']").attr("checked", 'checked');
        } else if (channel2 == '2') {
            $("input[name='Coupons[channel2]']").attr("checked", false);
        }

        var use_illustrate = "<?php echo $model_info['use_illustrate'] ?>";

        $("textarea[name='Coupons[use_illustrate]']").html(use_illustrate);

        var gift_info = "<?php echo $model_info['gift_info'] ?>";

        $("textarea[name='Coupons[gift_info]']").html(gift_info);

        var day1 = "<?php echo isset($model['start_days']) && !empty($model['start_days'])?$model['start_days']:'0' ?>";

        if (day1 == 0) {
            $(".m-input-group select[name='Coupons[start_days]']").find("option[value='0']").attr("selected", "selected");
            //$(".m-input-group select[name='Coupons[start_days]']").val("当");
        } else {
            $(".m-input-group select[name='Coupons[start_days]']").val(day1);
        }
        var day2 = "<?php echo isset($model['effective_days']) && !empty($model['effective_days'])?$model['effective_days']:'30' ?>";
        $(".m-input-group select[name='Coupons[effective_days]']").val(day2);

        //选中部分门店才弹出
        <?php if(!empty($model_info['store_info']) && $model['store_limit_type'] == 2){?>
        <?php echo '$("#store_table").show();';?>
        <?php }?>

    });

    //代金券标题
    $('#Coupons_title').keyup(function () {
        $('#left_title').html($('#Coupons_title').val());
    });

    //代金券副标题
    $('#Coupons_vice_title').keyup(function () {
        $('#left_vice_title').html($('#Coupons_vice_title').val());
    });

    //日期同步
    $('input[name="Coupons[time]"]').on('hide.daterangepicker', function (ev, picker) {
        if ($('input[name="Coupons\\[time_type\\]"]:checked').val() == <?php echo VALID_TIME_TYPE_FIXED; ?>) {  //如果选择的是固定日期
            var t = $("input[name='Coupons[time]']").val();
            $('#left_time').html(t.replace(/\//g, "."));
            //$('#left_time2').html($('#Time').val());
        } else { //如果选择的是相对日期
//          if($('#Coupons_start_days').val() != 0){
//               $('#left_time').html('领取后'+$("input[name='Coupons[start_days]']").val()+'天生效，有效'+$("input[name='Coupons[effective_days]']").val()+'天');
//               //$('#left_time2').html('领取后'+$('#Coupons_start_days').val()+'天生效，有效'+$('#Coupons_effective_days').val()+'天');
//          }else{
//               $('#left_time').html('领取后当天生效，有效'+$("input[name='Coupons[effective_days]']").val()+'天');
//               //$('#left_time2').html('领取后当天生效，有效'+$('#Coupons_effective_days').val()+'天');
//          }
        }
    });


    //可用时段
    var use_time = '';
    $('.day_checkbox').change(function () {
        use_time = '';
        $('.day_checkbox').each(function () {

            if ($(this).is(":checked")) {
                use_time = use_time + '、' + $(this).val();

            } else {

            }
        });
        $('#left_available_time').html(use_time.substr(1));
    });

    $("input[name='Coupons[available_time]'][value='1']").click(function () {
        $('#left_available_time').html('周一到周日');
    });

    $("input[name='Coupons[available_time]'][value='2']").click(function () {
        $('#left_available_time').html(use_time.substr(1));
    });
    $("input[name='Coupons[end_hour]']").keyup(function () {
        var t1 = parseInt($("input[name='Coupons[begin_hour]']").val());
        var t2 = parseInt($("input[name='Coupons[end_hour]']").val());

        if ($("input[name='Coupons[begin_hour]']").val()) {
            $('#left_available_hour2').html($("input[name='Coupons[begin_hour]']").val() + ':00  至 ' + $("input[name='Coupons[end_hour]']").val() + ':00');
            //$('#left_available_hour').show();
        }
    });

//     $("input[name='Coupons[end_hour]']").blur(function () {
//         var t1 = parseInt($("input[name='Coupons[begin_hour]']").val());
//         var t2 = parseInt($("input[name='Coupons[end_hour]']").val());
//         if (t1 > t2) {

//             $("#hour_error").html("结束时间不可早于开始时间");
//         } else {
//             $("#hour_error").html("");
//         }
//     });
    //营销入口一
    $("input[name='Coupons[custom_url_name]']").keyup(function () {
        $('#entrance1').html($("input[name='Coupons[custom_url_name]']").val());
    });

    //营销入口一
    $("input[name='Coupons[promotion_url_name]']").keyup(function () {
        $('#entrance2').html($("input[name='Coupons[promotion_url_name]']").val());
    });

    $("input[name='Coupons[cover_img_text]']").keyup(function () {
        $('#left_cover_title').html($("input[name='Coupons[cover_img_text]']").val());
    });


    function JqValidate() {
        return $("#addForm").validate({
            debug: false,
            rules: {
                "Coupons[merchant_short_name]": {
                    required: true
                },
                "Coupons[title]": {
                    required: true

                },
                "Coupons[time]": {
                    required: "#effective-time-1:checked"

                },

                "Coupons[start_days]": {
                    required: "#effective-time-2:checked"

                },
                "Coupons[effective_days]": {
                    required: "#effective-time-2:checked"

                },
                "Coupons[begin_hour]": {
                    digits: true,
                    max: 24,
                    min: 1
                },
                "Coupons[end_hour]": {
                    digits: true,
                    max: 24,
                    min: 1
                },
                "Coupons[reduce_cost]": {
                    required: "#cash_type:checked",
                    min: 0.01,
                    twoDecimalCheck: true,
                    number: true
                },
                "Coupons[discount]": {
                    required: "#discount_type:checked",
                    range: [1, 9.9]

                },
                "Coupons[least_cost]": {
                    required: "#cash_type:checked",
                    min: 0,
                    twoDecimalCheck: true,
                    number: true
                },
                "Coupons[discount_rule]": {
                    min: 0,
                    twoDecimalCheck: true,
                    number: true
                },
                "Coupons[gift_rule]": {
                    min: 0,
                    twoDecimalCheck: true,
                    number: true
                },
                "Coupons[use_illustrate]": {
                    maxlength: 300
                },
                "Coupons[cover_img_text]": {
                    maxlength: 12  //(汉字算一个字符)
                },
                "Coupons[custom_url_name]": {
                    maxlength: 5
                },
                "Coupons[custom_url_sub_title]": {
                    maxlength: 6
                },
                "Coupons[promotion_url_name]": {
                    maxlength: 5
                },
                "Coupons[custom_url]": {
                    url: true
                },
                "Coupons[promotion_url]": {
                    url: true
                },
                "Coupons[prompt]": {
                    required: true,
                    maxlength: 12
                },
                "Coupons[num]": {
                    required: true,
                    min: 0,
                    digits: true
                },
                "Coupons[tel]": {
                    isMobile: true,
                    maxlength: 13
                },
                "Coupons[receive_num]": {
                    min: 1,
                    digits: true
                },
                "Coupons[store_limit]": {
                    required: "#limit_type2:checked"
                }
            },
            messages: {
                "Coupons[merchant_short_name]": {
                    required: "商户名不能为空"
                },
                "Coupons[title]": {
                    required: "券标题必填"
                },
                "Coupons[time]": {
                    required: "有效时间必填"
                },
                "Coupons[available_time]": {
                    required: "有效时间必填"
                },
                "Coupons[start_days]": {
                    required: "开始时间必填"

                },
                "Coupons[effective_days]": {
                    required: "有效天数必填"

                },
                "Coupons[reduce_cost]": {

                    min: "减免金额只能是大于0的数字"
                },
                "Coupons[least_cost]": {

                    min: "减免条件只能是大于0的数字"
                },
                "Coupons[begin_hour]": {
                    digits: "请输入整数",
                    max: "请输入1到24之间的时数",
                    min: "请输入1到24之间的时数"
                },
                "Coupons[end_hour]": {
                    digits: "请输入整数",
                    max: "请输入1到24之间的时数",
                    min: "请输入1到24之间的时数"
                },
                "Coupons[prompt]": {

                    maxlength: "提示操作长度不超过12位"
                },
                "Coupons[cover_img_text]": {
                    required:  "这是必填字段",
                    maxlength: "简介长度不超过12个汉字"
                },
                "Coupons[store_limit]": {
                    required: "请选择指定门店"
                }

            },
            errorPlacement: function (error, element) {
                if (element.is(".cover-desc")) {
                    error.appendTo(element.parents(".m-imgpost"));
                } else {
                    error.appendTo(element.parents(".cp-con"));
                }
            }
        });
    }

    $.validator.addMethod("isMobile", function (value, element) {
        var mobilePhone = /(\d{11})|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$/; //填写正则
        return this.optional(element) || (mobilePhone.test(value));
    }, "请填写正确的客服电话"); //报错提示内容 

    $.validator.addMethod("dateCheck", function (value, element) {
        var date = /^\d{4}(\-|\/|\.)\d{1,2}\1\d{1,2}(\s\-\s)\d{4}(\-|\/|\.)\d{1,2}\1\d{1,2}$/; //填写正则
        return this.optional(element) || (date.test(value));
    }, "请填写正确日期"); //报错提示内容

    $.validator.addMethod("twoDecimalCheck", function (value, element) {
        var decimal = /^((?!0)\d+(.\d{1,2})?)$/; //填写正则
        return this.optional(element) || (decimal.test(value));
    }, "小数点之后只能有两位数字"); //报错提示内容     

    //注册表单验证
    $(JqValidate());

    $("#js-cp-next").click(function (e) {
        e.preventDefault();
        if (JqValidate().form()) {

            $(".s-step").addClass("active").siblings().removeClass("active");
            $(".js-step-2").show().siblings(".js-step-1").hide();
            $(window).scrollTop(0);
            $(parent.document).scrollTop(0);
            window.parent.callParAutoResize("main", $("body").height());
        } else {

        }
    });

    //删除封面
    $('#del-first-cover').click(function () {
        $('#right_cover_img').attr("src", "");
        $('#left_cover_img').attr("src", "");
        $('input[name="Coupons[cover_img]"]').attr("value", "");
    });

    //删除第一个图文
    $('#del-first-text').click(function () {
        $('#right_img_text0').attr("src", "");
        $('input[name="img_text[0]"]').attr("value", "");
    });


</script>
