<body>
<h4 class="g-title">精准营销</h4>
<div class="g-container">
    <ul class="nav nav-inline nav-precise">
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
    <div class="btn-group btn-group-sm m-btn-group js-precise-btn-group">
        <button type="button" class="btn" onclick="window.location.href='<?php echo Yii::app()->createUrl('marketing/marketing/MarketingPrecision') ?>'">筛选客户群体</button>
        <!--<button type="button" class="btn js-choose-group">-->
        <button type="button" class="btn active" onclick="choosedGroup()">
            <i class="fa fa-plus"></i>选择已有分组
        </button>
    </div>
    <ul class="precise-group-info mt15">
        <li class="group-name">
            <?php echo $group_info['name'] ?>
            <i class="fa fa-times precise-del-group-btn" onclick="choosedGroup()"></i>
        </li>
        <li class="group-data">
            <span>人数：<em id="group_num"><?php echo $group_info['num'] ?>人</em></span>
            <span>分组类型：<em><?php echo $GLOBALS['__GROUP_TYPES'][$group_info['type']] ?></em></span>
            <span>刷新时间：<em id="group_last_time"><?php echo $group_info['last_time'] ?></em></span>
        </li>
    </ul>
    <div class="precise-act-wrapper mt15">
        <h5 class="fw title">当前分组已在以下活动中使用：</h5>
        <div class="act-name-wrapper">
            <?php if (!empty($group_marketing)) {
                foreach ($group_marketing as $key => $v) { ?>
                    <a href="<?php echo Yii::app()->createUrl('marketing/marketing/MarketingPrecisionDetail', array('marketing_id' => $key)) ?>"><?php echo $v ?></a>
                <?php }
            } else {
                echo '暂无使用的活动';
            } ?>
        </div>
    </div>
    <div class="filtrate_group precise-marketing-wrapper mt15">
        <!-- 数据输入区域 如果为静态分组本块内容不需要 -->
        <div class="filtrate_area new_group precise-input-area">
            <div class="new_group_item js_1 filtrate_condition" n="condition_sex" style="display: none">
                <h3>性别</h3>
                <div class="new_group_item_c">
                    <input type="checkbox" id="sex_0" class="f_children" name="sex[0]" value="1">
                    <label for="sex_0">男</label>
                    <input type="checkbox" id="sex_1" class="f_children" name="sex[1]" value="2">
                    <label for="sex_1">女</label>
                    <input type="checkbox" id="sex_2" class="f_children" name="sex[2]" value="3">
                    <label for="sex_2">未知</label>
                </div>
            </div>

            <div class="new_group_item js_2 filtrate_condition" n="condition_age"  style="display: none">
                <h3 class="mt6">年龄</h3>
                <div class="new_group_item_c form-inline">
                    <input type="text" class="form-control fc-v-sm start_age f_children" name="start_age">
                    <em>~</em>
                    <input type="text" class="form-control fc-v-sm end_age f_children" name="end_age"> 岁
                </div>
            </div>
            <!--<div class="new_group_item js_3 filtrate_condition" n="condition_area">
                <h3 class="mt6">地区</h3>
                <div class="new_group_item_c">
                    <select class="form-control fc-sm f_children" name="area">
                        <option>全部地区</option>
                        <option>全部地区</option>
                        <option>全部地区</option>
                        <option>全部地区</option>
                    </select>
                </div>
            </div>-->
            <div class="new_group_item js_3 filtrate_condition filtrate_date" n="condition_birthday"  style="display: none">
                <h3 class="mt6">生日范围</h3>
                <div class="new_group_item_c form-inline">
                    <input type="text" class="form-control fc-s-sm f_children" name="birthdayStart">
                    <!--<em>~</em>
                    <input type="text" class="form-control fc-s-sm f_children" name="birthdayEnd">-->
                </div>
            </div>

            <div class="new_group_item js_4 filtrate_condition" n="condition_source"  style="display: none">
                <h3>来源</h3>
                <div class="new_group_item_c">
                    <input type="checkbox" id="source_0" name="source[0]" class="f_children"
                           value="<?php echo USER_FROM_WECHAT ?>">
                    <label for="source_0">微信公众号</label>
                    <input type="checkbox" id="source_1" name="source[1]" class="f_children"
                           value="<?php echo USER_FROM_ALIPAY ?>">
                    <label for="source_1">支付宝服务窗</label>
                </div>
            </div>

            <div class="new_group_item js_5 filtrate_condition" n="condition_grade"  style="display: none">
                <h3>会员等级</h3>
                <div class="new_group_item_c">
                    <?php foreach ($user_grades as $k => $v) { ?>
                        <input type="checkbox" id="level_<?php echo $k ?>" class="f_children"
                               name="level[<?php echo $k ?>]" value="<?php echo $v->id ?>">
                        <label for="level_<?php echo $k ?>"><?php echo $v->name ?></label>
                    <?php } ?>
                </div>
            </div>

            <!--<div class="new_group_item js_6 filtrate_condition" n="condition_value"  style="display: none">
                <h3>客户价值</h3>
                <div class="new_group_item_c">
                    <div>
                        <?php /*foreach ($tag_category_customer_values as $k => $v) {
                            if ($k <= 4) { */?>
                                <input type='checkbox' id='value_<?php /*echo $k */?>' name='value[<?php /*echo $k */?>]' value='<?php /*echo $k */?>' class='f_children'>
                                <label for='value_<?php /*echo $k */?>'><?php /*echo $v */?></label>
                            <?php /*}
                        } */?>
                    </div>
                    <div class="checkbox_group">
                        <?php /*foreach ($tag_category_customer_values as $k => $v) {
                            if ($k > 4) { */?>
                                <input type='checkbox' id='value_<?php /*echo $k */?>' name='value[<?php /*echo $k */?>]' value='<?php /*echo $k */?>' class='f_children'>
                                <label for='value_<?php /*echo $k */?>'><?php /*echo $v */?></label>
                            <?php /*}
                        } */?>
                    </div>
                </div>
            </div>-->

            <div class="new_group_item js_7 filtrate_condition" n="condition_consum_amount"  style="display: none">
                <h3 class="mt6">消费金额</h3>
                <div class="new_group_item_c form-inline">
                    <span>时间在<em class="choosed js_datepicker">任意时间</em>
                        <input type="hidden" class="f_children" value="2015-02-03~2015-02-05">
                    </span>
                    <span>消费地点为 <em class="choosed">牛牛科技餐饮</em>
                        <input type="hidden" value="10" class="f_children"></span>
                    <span>消费金额在
                        <input type="text" class="form-control fc-v-sm f_children">
                        <em> ~ </em>
                        <input type="text" class="form-control fc-v-sm f_children"> 元之间的客户</span>
                </div>
            </div>

            <div class="new_group_item js_8 filtrate_condition" n="condition_consum_unitprice"  style="display: none">
                <h3 class="mt6">客单价</h3>
                <div class="new_group_item_c form-inline">
                    <span>时间在<em class="choosed js_datepicker">任意时间</em>
                        <input type="hidden" class="f_children" value="2015-02-02~2015-02-02">
                    </span>
                    <span>消费地点为<em class="choosed">牛牛科技餐饮</em>
                        <input type="hidden" value="11" class="f_children">
                    </span>
                    <span>客单价在
                        <input type="text" class="form-control fc-v-sm f_children">
                        <em> ~ </em>
                        <input type="text" class="form-control fc-v-sm f_children"> 元之间的客户</span>
                </div>
            </div>

            <div class="new_group_item js_9 filtrate_condition" n="condition_consum_highestprice"  style="display: none">
                <h3 class="mt6">最高消费</h3>
                <div class="new_group_item_c form-inline">
                    <span>时间在<em class="choosed js_datepicker">任意时间</em>
                        <input type="hidden" class="f_children" value="2015-02-02~2015-02-02">
                    </span>
                    <span>消费地点为
                        <em class="choosed">牛牛科技餐饮</em><input type="hidden" value="12" class="f_children"></span>
                    <span>最高消费在
                        <input type="text" class="form-control fc-v-sm f_children">
                        <em> ~ </em>
                        <input type="text" class="form-control fc-v-sm f_children"> 元之间的客户</span>
                </div>
            </div>

            <div class="new_group_item js_10 filtrate_condition" n="condition_consum_count"  style="display: none">
                <h3 class="mt6">消费次数</h3>
                <div class="new_group_item_c form-inline">
                    <span>时间在<em class="choosed js_datepicker">任意时间</em>
                        <input type="hidden" class="f_children" value="2015-02-02~2015-02-02"></span>
                    <span>消费地点为
                        <em class="choosed">牛牛科技餐饮</em>
                        <input type="hidden" value="11" class="f_children">
                    </span>
                    <span>消费次数在
                        <input type="text" class="form-control fc-v-sm f_children">
                        <em> ~ </em>
                        <input type="text" class="form-control fc-v-sm f_children"> 次之间的客户</span>
                </div>
            </div>

            <div class="new_group_item js_11 filtrate_condition" n="condition_consum_days"  style="display: none">
                <h3 class="mt6">消费天数</h3>
                <div class="new_group_item_c form-inline">
                    <span>时间在<em class="choosed js_datepicker">任意时间</em>
                        <input type="hidden" class="f_children" value="2015-02-02~2015-02-02"></span>
                    <span>消费地点为 <em class="choosed">牛牛科技餐饮</em>
                        <input type="hidden" value="12" class="f_children"></span>
                    <span>消费天数在
                        <input type="text" class="form-control fc-v-sm f_children">
                        <em> ~ </em>
                        <input type="text" class="form-control fc-v-sm f_children"> 天之间的客户</span>
                </div>
            </div>

            <div class="new_group_item js_12 filtrate_condition" n="condition_not_consum_days" style="display: none">
                <h3 class="mt6">未消费时间</h3>
                <div class="new_group_item_c form-inline">
                    <span>消费地点为 <em class="choosed">牛牛科技餐饮</em>
                        <input type="hidden" value="12" class="f_children"></span>
                    <span>距今未消费时间在
                        <input type="text" class="form-control fc-v-sm f_children">
                        <em> ~ </em>
                        <input type="text" class="form-control fc-v-sm f_children"> 天之间的客户</span>
                </div>
            </div>
        </div>
        <!-- 计算区域 -->
        <div class="new_group calc_area">
            <div class="new_group_item">
                <input type="submit" class="btn btn-primary m-btn" value="计算人数" id="btn_jisuan" name="btn_jisuan">
                <!--<span class="pl10">点击按钮进行计算……</span>-->
                <span class="pl10 js_loading" style="display: none;">
                    <img src="<?php echo WQ_STATIC_IMAGES ?>ajax-loader.gif" alt="" class="pr10">玩命计算中……
                </span>
            </div>

            <div class="new_group calc_area js_area">
                <div class="new_group_item">
                    <h3 class="mt6">总人数</h3>
                    <div class="new_group_item_c" id="total_user">
                        <?php echo $ret_html_array['total_user'] ?>
                    </div>
                </div>
                <div class="new_group_item">
                    <h3 class="mt10">性别</h3>
                    <div class="new_group_item_c" id="user_sex">
                        <?php echo $ret_html_array['user_sex'] ?>
                    </div>
                </div>
                <div class="new_group_item">
                    <h3 class="mt10">年龄</h3>
                    <div class="new_group_item_c" id="user_age">
                        <?php echo $ret_html_array['user_age'] ?>
                    </div>
                </div>
                <div class="new_group_item">
                    <h3 class="mt10">会员等级</h3>
                    <div class="new_group_item_c" id="user_grade">
                        <?php echo $ret_html_array['user_grade'] ?>
                    </div>
                </div>
                <!--<div class="new_group_item">
                    <h3>客户价值</h3>
                    <div class="new_group_item_c" id="user_value">
                        <?php /*echo $ret_html_array['user_value'] */?>
                    </div>
                </div>-->
            </div>
        </div>
        <div class="form-inline m-form-group mt30 text-center">
            <button type="button" class="btn btn-primary m-btn marketing_precision_add" onclick="marketingPrecisionAdd()">发起营销</button>
            <button type="button" class="btn btn-default m-btn" onclick="window.location.href='<?php echo $this->createUrl('marketing/MarketingPrecision') ?>'">取消</button>
        </div>
    </div>
</div>

</body>

<script>
    var filter_json = '<?php echo $filter_condition ?>';
    //选择分组
    function choosedGroup() {
        layer.open({
            type: 2,
            title: '选择分组',
            shadeClose: true,
            shade: 0.8,
            offset: '20px',
            area: ['770px', '600px'],
            content: '/index.php/marketing/Marketing/groupDialog' //iframe的url
        });
        return false;
    }

    //关闭弹出框
    function closeWinDialogGroup(group_id) {
        layer.closeAll();
        if (group_id != undefined) {
            window.location.href = '<?php echo Yii::app()->createUrl('marketing/marketing/chooseGroup') ?>?group_id=' + group_id;
        }
    }

    //发起营销
    function marketingPrecisionAdd() {
        window.location.href = '<?php echo Yii::app()->createUrl('marketing/Marketing/MarketingPrecisionAdd', array('group_id' => $group_info['id'])) ?>';
    }

    loopGroup($.parseJSON(filter_json)); //启动

    $('#btn_jisuan').click(function () {

        $('.js_area').hide();

        $('.js_loading').show();
        $('#btn_jisuan').attr('disabled', true);
        $('.marketing_precision_add').attr('disabled', true);

        $.ajax({
            url: "<?php echo Yii::app()->createUrl('marketing/marketing/JSGroup', array('group_id' => $group_info['id']))?>",
            type: "post",
            async: true,
            data: filter_json,
            success: function (data) {
                //alert(data)
                if (data != '') {
                    var return_data = $.parseJSON(data);
                    var data = return_data.html;
                    if (data.status == 'ok') {

                        $('.js_area').show();
                        $('#total_user').html(data.html.total_user);
                        $('#user_age').html(data.html.user_age);
                        $('#user_sex').html(data.html.user_sex);
                        $('#user_grade').html(data.html.user_grade);
                        $('#user_value').html(data.html.user_value);
                        var current_h = $('body').height() > 580 ? $('body').height() : 580;
                        var main_frame = $('#main', window.parent.document);
                        main_frame.css('height', current_h);

                        $('.js_loading').hide();
                        $('#btn_jisuan').removeAttr('disabled');
                        $('.marketing_precision_add').removeAttr('disabled');
                        $('#group_last_time').html(return_data.last_time);
                        $('#group_num').html(return_data.group_num + '人');
                    }
                    if (data.status == 'error') {
                        parent.window.delayHide('m-danger-warning', data.errMsg, 1500);
                        //tips(data.errMsg);
                    }
                }
            },
            error: function () {
                parent.window.delayHide('m-danger-warning', '系统异常', 1500);
                //tips("系统异常!");
            }
        });
    });
</script>