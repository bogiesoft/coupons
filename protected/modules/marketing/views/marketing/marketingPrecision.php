<style>
    .daterange_picker {
        width: 140px;
    }
</style>
<script type="text/javascript">
    //$(document).ready(main_obj.list_init);
    $(function () {
        $('#birthday').daterangepicker({
            timePicker: false,
            format: 'MM/DD',
            dateLimit: true,
            maxDate: true,
        });
        $('.daterange_picker').daterangepicker({
            timePicker: false,
            format: 'YYYY/MM/DD',
            dateLimit: true,
            maxDate: '<?php echo date('Y/m/d', time()) ?>',
        });
    });
</script>

<body>
<h4 class="g-title">精准营销</h4>
<!--<div class="m-succeed-icon">保存分组成功，自动跳转至发起营销</div>-->
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
        <button type="button" class="btn active">筛选客户群体</button>
        <!--<button type="button" class="btn js-choose-group choosed_group">-->
        <button type="button" class="btn choosed_group">
            <i class="fa fa-plus"></i>选择已有分组
        </button>
    </div>
    <div class="filtrate_group precise-marketing-wrapper mt15" style="display: block;">
        <div class="new_group">
            <div class="new_group_item">
                <h3>基本信息</h3>
                <div class="new_group_item_c">
                    <ul class="filtrate_item">
                        <li class="js_1"><span>性别</span></li>
                        <li class="js_2"><span>年龄</span></li>
                        <!--<li class="js_3"><span>地区</span></li>-->
                        <li class="js_4"><span>生日范围</span></li>
                        <li class="js_5"><span>来源</span></li>
                        <li class="js_6"><span>会员等级</span></li>
                        <!--<li class="js_7"><span>客户价值</span></li>-->
                    </ul>
                </div>
            </div>
            <div class="new_group_item">
                <h3>消费能力</h3>
                <div class="new_group_item_c">
                    <ul class="filtrate_item">
                        <li class="js_8"><span>消费金额</span></li>
                        <li class="js_9"><span>客单价</span></li>
                        <li class="js_10"><span>最高消费</span></li>
                    </ul>
                </div>
            </div>
            <div class="new_group_item">
                <h3>消费频次</h3>
                <div class="new_group_item_c">
                    <ul class="filtrate_item">
                        <li class="js_11"><span>消费次数</span></li>
                        <li class="js_12"><span>消费天数</span></li>
                    </ul>
                </div>
            </div>
            <div class="new_group_item last">
                <h3>流失情况</h3>
                <div class="new_group_item_c">
                    <ul class="filtrate_item">
                        <li class="js_13"><span>未消费时间</span></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- 数据输入区域 -->
        <div class="filtrate_area new_group">
            <div class="new_group_item js_1 filtrate_condition" n="condition_sex">
                <h3>性别</h3>
                <div class="new_group_item_c">
                    <input type="checkbox" name="sex[0]" class="f_children"
                           id="sex_0" <?php echo isset($_GET['sex'][0]) && $_GET['sex'][0] == SEX_MALE ? 'checked="checked"' : '' ?>
                           value="<?php echo SEX_MALE ?>"><label for="sex_0">男</label>
                    <input type="checkbox" name="sex[1]" class="f_children"
                           id="sex_1" <?php echo isset($_GET['sex'][1]) && $_GET['sex'][1] == SEX_FEMALE ? 'checked="checked"' : '' ?>
                           value="<?php echo SEX_FEMALE ?>"><label for="sex_1">女</label>
                    <input type="checkbox" name="sex[2]" class="f_children"
                           id="sex_2" <?php echo isset($_GET['sex'][2]) && $_GET['sex'][2] == '3' ? 'checked="checked"' : '' ?>
                           value="<?php echo '3' ?>"><label for="sex_2">未知</label>
                </div>
                <span class="icon_del"></span>
            </div>
            <div class="new_group_item js_2 filtrate_condition" n="condition_age">
                <h3 class="mt6">年龄</h3>
                <div class="new_group_item_c form-inline">
                    <input type="text" class="form-control fc-v-sm start_age f_children" name="start_age" value="<?php echo isset($_GET['age_min']) ? $_GET['age_min'] : '' ?>"
                           onkeyup="value=value.replace(/[^\d]/g,'')">
                    <em>~</em>
                    <input type="text" class="form-control fc-v-sm end_age f_children" name="end_age" value="<?php echo isset($_GET['age_max']) ? $_GET['age_max'] : '' ?>"
                           onkeyup="value=value.replace(/[^\d]/g,'')"> 岁
                </div>
                <span class="icon_del"></span>
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
                <span class="icon_del"></span>
            </div>-->
            <div class="new_group_item js_4 filtrate_condition filtrate_date" n="condition_birthday">
                <h3 class="mt6">生日范围</h3>
                <div class="new_group_item_c form-inline">
                    <input type="text" class="form-control fc-s-sm f_children" name="birthdayStart" name="birthday" id="birthday" readonly>
                    <!--<em>~</em>
                    <input type="text" class="form-control fc-s-sm f_children" name="birthdayEnd">-->
                </div>
                <span class="icon_del"></span>
            </div>
            <div class="new_group_item js_5 filtrate_condition" n="condition_source">
                <h3>来源</h3>
                <div class="new_group_item_c">
                    <input type="checkbox" id="source_0" name="source[0]" class="f_children" value="<?php echo USER_FROM_WECHAT ?>">
                    <label for="source_0">微信公众号</label>
                    <input type="checkbox" id="source_1" name="source[1]" class="f_children" value="<?php echo USER_FROM_ALIPAY ?>">
                    <label for="source_1">支付宝服务窗</label>
                </div>
                <span class="icon_del"></span>
            </div>
            <div class="new_group_item js_6 filtrate_condition" n="condition_grade">
                <h3>会员等级</h3>
                <div class="new_group_item_c">
                    <?php foreach ($user_grades as $k => $v) { ?>
                        <input type="checkbox" id="level_<?php echo $k ?>" class="f_children"
                               name="level[<?php echo $k ?>]" value="<?php echo $v->id ?>">
                        <label for="level_<?php echo $k ?>"><?php echo $v->name ?></label>
                    <?php } ?>
                </div>
                <span class="icon_del"></span>
            </div>
            <!--<div class="new_group_item js_7 filtrate_condition" n="condition_value">
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
                <span class="icon_del"></span>
            </div>-->
            <div class="new_group_item js_8 filtrate_condition" n="condition_consum_amount">
                <h3>消费金额</h3>
                <div class="new_group_item_c form-inline">
                    <span>时间在<!--<em class="choosed js_datepicker">任意时间</em>-->
                        <input type="text" class="form-control fc-s-sm f_children daterange_picker" value="" readonly title="点击选择时间(不选择则视为任意时间)" placeholder="任意时间">
                    </span>
                    <span>消费地点为
                        <input type="hidden" class="store_ids f_children" value="">
                        <em class="choosed">点击选择门店</em>
                    </span>
                    <span>消费金额在
                        <input type="text" class="form-control fc-v-sm f_children" onkeyup="IntOrFloat(this)">
                        <em> ~ </em>
                        <input type="text" class="form-control fc-v-sm f_children" onkeyup="IntOrFloat(this)"> 元之间的客户
                    </span>
                </div>
                <span class="icon_del"></span>
            </div>
            <div class="new_group_item js_9 filtrate_condition" n="condition_consum_unitprice">
                <h3>客单价</h3>
                <div class="new_group_item_c form-inline">
                    <span>时间在<!--<em class="choosed js_datepicker">任意时间</em>-->
                        <input type="text" class="form-control fc-s-sm f_children daterange_picker" value="" readonly title="点击选择时间(不选择则视为任意时间)" placeholder="任意时间">
                    </span>
                    <span>消费地点为
                        <input type="hidden" class="store_ids f_children" value="">
                        <em class="choosed">点击选择门店</em>
                    </span>
                    <span>客单价在
                        <input type="text" class="form-control fc-v-sm f_children" onkeyup="IntOrFloat(this)">
                        <em> ~ </em>
                        <input type="text" class="form-control fc-v-sm f_children" onkeyup="IntOrFloat(this)"> 元之间的客户
                    </span>
                </div>
                <span class="icon_del"></span>
            </div>
            <div class="new_group_item js_10 filtrate_condition" n="condition_consum_highestprice">
                <h3>最高消费</h3>
                <div class="new_group_item_c form-inline">
                    <span>时间在<!--<em class="choosed js_datepicker">任意时间</em>-->
                        <input type="text" class="form-control fc-s-sm f_children daterange_picker" value="" readonly title="点击选择时间(不选择则视为任意时间)" placeholder="任意时间">
                    </span>
                    <span>消费地点为
                        <input type="hidden" class="store_ids f_children" value="">
                        <em class="choosed">点击选择门店</em>
                    </span>
                    <span>最高消费在
                        <input type="text" class="form-control fc-v-sm f_children" onkeyup="IntOrFloat(this)"><em> ~ </em><input type="text" class="form-control fc-v-sm f_children" onkeyup="IntOrFloat(this)"> 元之间的客户</span>
                </div>
                <span class="icon_del"></span>
            </div>
            <div class="new_group_item js_11 filtrate_condition" n="condition_consum_count">
                <h3>消费次数</h3>
                <div class="new_group_item_c form-inline">
                    <span>时间在<!--<em class="choosed js_datepicker">任意时间</em>-->
                        <input type="text" class="form-control fc-s-sm f_children daterange_picker" value="" readonly title="点击选择时间(不选择则视为任意时间)" placeholder="任意时间">
                    </span>
                    <span>消费地点为
                        <input type="hidden" class="store_ids f_children" value="">
                        <em class="choosed">点击选择门店</em>
                    </span>
                    <span>消费次数在
                        <input type="text" class="form-control fc-v-sm f_children" onkeyup="value=value.replace(/[^\d]/g,'')">
                        <em> ~ </em>
                        <input type="text" class="form-control fc-v-sm f_children" onkeyup="value=value.replace(/[^\d]/g,'')"> 次之间的客户
                    </span>
                </div>
                <span class="icon_del"></span>
            </div>
            <div class="new_group_item js_12 filtrate_condition" n="condition_consum_days">
                <h3>消费天数</h3>
                <div class="new_group_item_c form-inline">
                    <span>时间在<!--<em class="choosed js_datepicker">任意时间</em>-->
                        <input type="text" class="form-control fc-s-sm f_children daterange_picker" value="" readonly title="点击选择时间(不选择则视为任意时间)" placeholder="任意时间">
                    </span>
                    <span>消费地点为
                        <input type="hidden" class="store_ids f_children" value="">
                        <em class="choosed">点击选择门店</em>
                    </span>
                    <span>消费天数在
                        <input type="text" class="form-control fc-v-sm f_children" onkeyup="value=value.replace(/[^\d]/g,'')">
                        <em> ~ </em>
                        <input type="text" class="form-control fc-v-sm f_children" onkeyup="value=value.replace(/[^\d]/g,'')"> 天之间的客户
                    </span>
                </div>
                <span class="icon_del"></span>
            </div>
            <div class="new_group_item js_13 filtrate_condition" n="condition_not_consum_days">
                <h3>最近消费时间</h3>
                <div class="new_group_item_c form-inline">
                    <span>消费地点为
                        <input type="hidden" class="store_ids f_children" value="">
                        <em class="choosed">点击选择门店</em>
                    </span>
                    <span>距今未消费时间在
                        <input type="text" class="form-control fc-v-sm f_children" onkeyup="value=value.replace(/[^\d]/g,'')">
                        <em> ~ </em>
                        <input type="text" class="form-control fc-v-sm f_children" onkeyup="value=value.replace(/[^\d]/g,'')"> 天之间的客户
                    </span>
                </div>
                <span class="icon_del"></span>
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
            <div id="filter_area" style="display:none">
                <div class="new_group_item">
                    <h3 class="mt6">总人数</h3>
                    <div class="new_group_item_c" id="total_user"></div>
                </div>
                <div class="new_group_item">
                    <h3 class="mt10">性别</h3>
                    <div class="new_group_item_c" id="user_sex"></div>
                </div>
                <div class="new_group_item">
                    <h3 class="mt10">年龄</h3>
                    <div class="new_group_item_c" id="user_age"></div>
                </div>
                <div class="new_group_item">
                    <h3 class="mt10">会员等级</h3>
                    <div class="new_group_item_c" id="user_grade"></div>
                </div>
                <!--<div class="new_group_item">
                    <h3>客户价值</h3>
                    <div class="new_group_item_c" id="user_value"></div>
                </div>-->
            </div>
        </div>
        <div class="form-inline m-form-group mt30 text-center">
            <button type="submit" class="btn btn-primary m-btn" id="btn_save_before" disabled style="background:gray">保存</button>
            <button type="button" class="btn btn-default m-btn" onclick="window.location.href='<?php echo $this->createUrl('marketing/CreateActivity') ?>'">取消</button>
        </div>
    </div>
</div>

<!--保存分组弹出层-->
<div class="m-pop save-group-pop" id="before_save_pop" style="display: none;">
    <div class="m-pop-title">
        <h4>保存分组</h4>
        <i class="m-pop-close fr fa fa-times"></i>
    </div>
    <div class="m-pop-con save-group-pop-con">
        <div class="form-inline mt20">
            <label><em class="text-red">*</em>将群体命名为：</label>
            <input type="text" name="group_name" class="form-control fc-m-sm" placeholder="请输入分组名称" maxlength="9">
        </div>
        <div class="row mt20">
            <div class="col-md-5 ">
                <lalebl>
                    <input type="radio" name="group_type" checked value="<?php echo GROUP_TYPE_STATIC?>">
                    保存为静态分组
                </lalebl>
                <p class="mt10 text-gray">分组内成员为此次筛选出的用户，固定不变</p>
            </div>
            <div class="col-md-5 col-md-offset-2">
                <lalebl>
                    <input type="radio" name="group_type" value="<?php echo GROUP_TYPE_DYNAMIC?>">
                    保存为动态分组
                </lalebl>
                <p class="mt10 text-gray">分组内成员和数量根据条件每天可能发生变化</p>
            </div>
        </div>
        <div class="form-inline m-form-group mt20 text-center">
            <button type="button" class="btn btn-primary m-btn" id="btn_save" name="btn_save">确定</button>
            <button type="button" class="btn btn-default m-btn m-pop-cancel-btn" id="btn_back" name="btn_back">取消</button>
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
                <!--<input type="checkbox" class="parentsBox">-->
                <i class="iconFolder"></i>全部(<?php echo isset($count_store) ? $count_store : '0' ?>)
                <div class="comList">
                    <?php if (!empty($store_list)) { ?>
                        <div class="child1">
                            <ul>
                                <?php foreach ($store_list as $k => $v) { ?>
                                    <li class="child3">
                                        <input type="radio" name="store_id" id="store<?php echo $v->id ?>"
                                               info="<?php echo $val['name'] ?>"
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
                                    <!--<input name="store_id" type="radio" class="parentBox" id="childone<?php /*echo $k; */?>">-->
                                    <i class="iconFolder"></i><?php echo $v['name']; ?>
                                </div>
                                <ul class="firstManage<?php echo $k; ?>">
                                    <?php if (!empty($v['firstStore'])) { ?>
                                        <?php foreach ($v['firstStore'] as $ke => $val) { ?>
                                            <li class="child3">
                                                <input type="radio" name="store_id"
                                                       id="store<?php echo $val['id'] ?>"
                                                       info="<?php echo $val['name'] ?>"
                                                       class="storeBox" value="<?php echo $val['id']; ?>">
                                                <i class="iconFile"></i><?php echo $val['name']; ?>
                                            </li>

                                        <?php } ?>
                                    <?php } ?>
                                </ul>
                                <?php if (!empty($v['sub'])) { ?>
                                    <?php foreach ($v['sub'] as $key => $value) { ?>
                                        <div class="childWrap child3">
                                            <i class="rbArrow"></i>
                                            <!--<input type="radio" name="store_id" class="childBox">-->
                                            <i class="iconFolder"></i>
                                            <?php echo $value['name']; ?>
                                        </div>
                                        <ul id="sub<?php echo $value['id']; ?>">
                                            <?php if (!empty($value['store'])) { ?>
                                                <?php foreach ($value['store'] as $ked => $va) { ?>
                                                    <li class="child4">
                                                        <input type="radio" name="store_id"
                                                               id="store<?php echo $va['id'] ?>"
                                                               info="<?php echo $val['name'] ?>"
                                                               class="storeBox" value="<?php echo $val['id']; ?>"
                                                               class="storeBox" value="<?php echo $va['id']; ?>" n="">
                                                        <i class="iconFile"></i><?php echo $va['name']; ?>
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
        <span>已选<em id="haschecked" class="text-blue">0</em>家，共
            <em class="text-blue"><?php echo $count_store ?></em>家
        </span>
        </div>
        <div class="text-center mt20">
            <button type="button" id="store_choose" class="btn btn-primary m-btn">确定</button>
        </div>
    </div>
</div>

</body>

<script>
    $('.choosed_group').click(function () {
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
    });

    //门店弹出
    $('.choosed_store').click(function () {
        popShow($('.store-pop'));
    });

    //门店选择
    $('#store_choose').click(function () {
        var store_id = '';
    });

    // 弹出位置方法
    function popShow(el) {
        //显示位置计算
        var offset_y = $(window.parent.document).scrollTop();
        var offset_x = 0;
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

    //关闭选择分组弹出框
    function closeWinDialogGroup(group_id) {
        layer.closeAll();
        if (group_id != undefined) {
            window.location.href = '<?php echo Yii::app()->createUrl('marketing/marketing/chooseGroup') ?>?group_id=' + group_id;
        }
    }

    //关闭选择门店弹出框
    function closeWinDialogStore() {
        art.dialog.get("dialog_store").close();
    }
</script>

<script>

    var seqJsonStr = '[{"seq":0,"name":"condition_sex","value":"1,2,3,"},{"seq":1,"name":"condition_age","value":{"valuemin":1,"valuemax":99}},{"seq":2,"name":"condition_area","value":"1223,5436,457567"},{"seq":3,"name":"condition_birthday","date":{"datemin":"1970-06-15","datemax":"1970-12-23"}},{"seq":4,"name":"condition_source","value":"1,2,3"},{"seq":5,"name":"condition_grade","value":"382,346465,475"},{"seq":6,"name":"condition_value","value":"1,3,5,7,6,8,2,4"},{"seq":7,"name":"condition_consum_amount","date":{"datemin":"2014-01-12","datemax":"2016-6-19"},"store":"1234,1072,244","value":{"valuemin":"10","valuemax":"990"}},{"seq":8,"name":"condition_consum_unitprice","date":{"datemin":"2014-01-12","datemax":"2016-09-19"},"store":"1234,1072,244","value":{"valuemin":"10","valuemax":"990"}},{"seq":9,"name":"condition_consum_highestprice","date":{"datemin":"2014-01-12","datemax":"2016-09-19"},"store":"1234,1072,24,244","value":{"valuemin":"10","valuemax":"990"}},{"seq":10,"name":"condition_consum_count","date":{"datemin":"2014-01-12","datemax":"2016-09-19"},"store":"1234,1072,244","value":{"valuemin":"1","valuemax":"1"}},{"seq":11,"name":"condition_consum_days","date":{"datemin":"2015-05-01","datemax":"2016-09-01"},"store":"111,234,1072,566","value":{"valuemin":"1","valuemax":"2345"}},{"seq":12,"name":"condition_not_consum_days","store":"23,1072,567","value":{"valuemin":"1","valuemax":"10"}}]';
    // seqJsonStr = '[{"seq":0,"name":"condition_sex","value":"1,2,3,"},{"seq":1,"name":"condition_age","value":{"valuemin":1,"valuemax":99}}]';
    // seqJsonStr = '[{"seq":0,"name":"condition_sex","value":"3,2,1"}]';

    var calculate_users = function (json) {
     	//alert(json);
        //提前置为不可保存，防止错误保存操作
        can_not_calculate();

        $('.js_loading').show();
        $('#btn_jisuan').attr('disabled', 'true');

        $.ajax({
            url: "<?php echo Yii::app()->createUrl('marketing/marketing/JSGroup')?>",
            type: "post",
            async: true,
            data: json,
            success: function (data) {
                //alert(data)
                if (data != '') {
                    var data = $.parseJSON(data);
                    if (data.status == 'ok') {

                        $('#filter_area').css('display', 'block');
                        $('#total_user').html(data.html.total_user);
                        $('#user_age').html(data.html.user_age);
                        $('#user_sex').html(data.html.user_sex);
                        $('#user_grade').html(data.html.user_grade);
                        $('#user_value').html(data.html.user_value);
                        var current_h = $('body').height() > 580 ? $('body').height() : 580;
                        var main_frame = $('#main', window.parent.document);
                        main_frame.css('height', current_h);
                        $('#btn_save_before').removeAttr('style');
                        $('#btn_save_before').removeAttr('disabled');
                        $('.js_loading').hide();
                        $('#btn_jisuan').removeAttr('disabled');
                    }
                    if (data.status == 'error') {
                        parent.window.delayHide('m-danger-warning', data.errMsg, 1500);
                        //tips(data.errMsg);
                        $('.js_loading').hide();
                        $('#btn_jisuan').removeAttr('disabled');
                    }
                }
            },
            error: function () {
                parent.window.delayHide('m-danger-warning', '系统异常', 1500);
                //tips("系统异常!");
                $('.js_loading').hide();
                $('#btn_jisuan').removeAttr('disabled');
            }
        });
    };

    $('#btn_jisuan').click(function () {
        var jsonObj = getValue('filtrate_condition', '.f_children');
        var jsonStr = JSON.stringify(jsonObj);
        console.log(jsonStr);
        if (jsonObj != '') {
            var status = true;
            for (var key in jsonObj) {
                switch (jsonObj[key].name) {
                    case 'condition_sex':
                        if (jsonObj[key].value == '') {
                            status = false;
                            parent.window.delayHide('m-danger-warning', '请勾选性别', 1500);
                            //tips('请勾选性别！');
                        }
                        break;
                    case 'condition_age':
                        if (jsonObj[key].value == '' || (jsonObj[key].value != '' && (jsonObj[key].value.valuemin == '' || jsonObj[key].value.valuemax == ''))) {
                            status = false;
                            parent.window.delayHide('m-danger-warning', '请完整填写年龄区间', 1500);
                            //tips('请完整填写年龄区间！');
                        }
                        if (parseInt(jsonObj[key].value.valuemin) > parseInt(jsonObj[key].value.valuemax)) {
                            status = false;
                            parent.window.delayHide('m-danger-warning', '年龄区间前者必须小于后者', 1500);
                            //tips('年龄区间前者必须小于后者！');
                        }
                        break;
                    case 'condition_birthday':
                        if (jsonObj[key].value == '') {
                            status = false;
                            parent.window.delayHide('m-danger-warning', '请选择生日范围', 1500);
                            //tips('请勾选生日范围！');
                        }
                        break;
                    case 'condition_source':
                        if (jsonObj[key].value == '') {
                            status = false;
                            parent.window.delayHide('m-danger-warning', '请勾选来源', 1500);
                            //tips('请勾选来源！');
                        }
                        break;
                    case 'condition_grade':
                        if (jsonObj[key].value == '') {
                            status = false;
                            parent.window.delayHide('m-danger-warning', '请勾选会员等级', 1500);
                            //tips('请勾选会员等级！');
                        }
                        break;
                    case 'condition_value':
                        if (jsonObj[key].value == '') {
                            status = false;
                            parent.window.delayHide('m-danger-warning', '请勾选客户价值', 1500);
                            //tips('请勾选客户价值！');
                        }
                        break;
                    case 'condition_consum_amount':
                        if (jsonObj[key].value == '' || (jsonObj[key].value != '' && (jsonObj[key].value.valuemin == '' || jsonObj[key].value.valuemax == ''))) {
                            status = false;
                            parent.window.delayHide('m-danger-warning', '请完整填写消费金额区间', 1500);
                            //tips('请完整填写消费金额区间！');
                        }
                        else if (parseInt(jsonObj[key].value.valuemin) > parseInt(jsonObj[key].value.valuemax)) {
                            status = false;
                            parent.window.delayHide('m-danger-warning', '消费金额区间前者必须小于后者', 1500);
                            //tips('消费金额区间前者必须小于后者！');
                        }
                        break;
                    case 'condition_consum_unitprice':
                        if (jsonObj[key].value == '' || (jsonObj[key].value != '' && (jsonObj[key].value.valuemin == '' || jsonObj[key].value.valuemax == ''))) {
                            status = false;
                            parent.window.delayHide('m-danger-warning', '请完整填写客单价金额区间', 1500);
                            //tips('请完整填写客单价金额区间！');
                        }
                        else if (parseInt(jsonObj[key].value.valuemin) > parseInt(jsonObj[key].value.valuemax)) {
                            status = false;
                            parent.window.delayHide('m-danger-warning', '客单价金额区间前者必须小于后者', 1500);
                            //tips('客单价金额区间前者必须小于后者！');
                        }
                        break;
                    case 'condition_consum_highestprice':
                        if (jsonObj[key].value == '' || (jsonObj[key].value != '' && (jsonObj[key].value.valuemin == '' || jsonObj[key].value.valuemax == ''))) {
                            status = false;
                            parent.window.delayHide('m-danger-warning', '请完整填写最高消费金额区间', 1500);
                            //tips('请完整填写最高消费金额区间！');
                        }
                        else if (parseInt(jsonObj[key].value.valuemin) > parseInt(jsonObj[key].value.valuemax)) {
                            status = false;
                            parent.window.delayHide('m-danger-warning', '最高消费金额区间前者必须小于后者', 1500);
                            //tips('最高消费金额区间前者必须小于后者！');
                        }
                        break;
                    case 'condition_consum_count':
                        if (jsonObj[key].value == '' || (jsonObj[key].value != '' && (jsonObj[key].value.valuemin == '' || jsonObj[key].value.valuemax == ''))) {
                            status = false;
                            parent.window.delayHide('m-danger-warning', '请完整填写消费次数区间', 1500);
                            //tips('请完整填写消费次数区间！');
                        }
                        else if (parseInt(jsonObj[key].value.valuemin) > parseInt(jsonObj[key].value.valuemax)) {
                            status = false;
                            parent.window.delayHide('m-danger-warning', '消费次数区间前者必须小于后者', 1500);
                            //tips('消费次数区间前者必须小于后者！');
                        }
                        break;
                    case 'condition_consum_days':
                        if (jsonObj[key].value == '' || (jsonObj[key].value != '' && (jsonObj[key].value.valuemin == '' || jsonObj[key].value.valuemax == ''))) {
                            status = false;
                            parent.window.delayHide('m-danger-warning', '请完整填写消费天数区间', 1500);
                            //tips('请完整填写消费天数区间！');
                        }
                        else if (parseInt(jsonObj[key].value.valuemin) > parseInt(jsonObj[key].value.valuemax)) {
                            status = false;
                            parent.window.delayHide('m-danger-warning', '消费天数区间前者必须小于后者', 1500);
                            //tips('消费天数区间前者必须小于后者！');
                        }
                        break;

                    case 'condition_not_consum_days':
                        if (jsonObj[key].value == '' || (jsonObj[key].value != '' && (jsonObj[key].value.valuemin == '' || jsonObj[key].value.valuemax == ''))) {
                            status = false;
                            parent.window.delayHide('m-danger-warning', '请完整填写未消费天数区间', 1500);
                            //tips('请完整填写未消费天数区间！');
                        } else if (parseInt(jsonObj[key].value.valuemin) > parseInt(jsonObj[key].value.valuemax)) {
                            status = false;
                            parent.window.delayHide('m-danger-warning', '未消费天数区间前者必须小于后者', 1500);
                            //tips('未消费天数区间前者必须小于后者！');
                        }
                        break;
                }
            }
            if (status == true) {
                calculate_users(jsonStr);
            } else {
                can_not_calculate();
            }
        } else {
            parent.window.delayHide('m-danger-warning', '请勾选条件再计算人数', 1500);
            //tips('请勾选条件再计算人数！');
            can_not_calculate();
        }
    });

    //不计算, 重置保存按钮
    var can_not_calculate = function () {
        $('#filter_area').css('display', 'none');
        $('#btn_save_before').css('background', 'gray');
        $('#btn_save_before').attr('disabled', 'true');
    }

    var save_group = function (jsonStr) {
        $('#btn_save').attr('disabled', 'true');
        $('#btn_save').html('保存中');
        $.ajax({
            url: "<?php echo Yii::app()->createUrl('marketing/marketing/saveGroup')?>",
            type: "post",
            async: true,
            data: jsonStr,
            success: function (data) {
                $('#btn_save').removeAttr('disabled');
                $('#btn_save').html('确定');

                if (data == '分组名已存在！') {
                    parent.window.delayHide('m-danger-warning', data, 1500);
                    //tips(data);
                }

                var data = $.parseJSON(data);

                if (data.status == '<?php echo ERROR_NONE ?>') {
                    parent.window.delayHide('m-succeed-icon', '保存分组成功，自动跳转至发起营销', 1500);
                    //tips('保存成功');
                    window.location.href = '<?php echo Yii::app()->createUrl('marketing/marketing/MarketingPrecisionAdd') ?>?group_id=' + data.data;
                }
            },
            error: function () {
                parent.window.delayHide('m-danger-warning', '系统异常', 1500);
                //tips("系统异常!");
                $('#btn_save').removeAttr('disabled');
            }
        });
    };

    $('#btn_save_before').click(function () {
        //$('#before_save_pop').show();
        popShow($('#before_save_pop'));
        $('#before_save_pop').find('input[name=group_name]').val('');
    });

    $('#btn_save').click(function () {

        var group_name = $("input[name='group_name']").val();
        var group_type = $("input[name='group_type']").filter(':checked').val();
        if (group_name && group_type) {
            var jsonStr = '{"group_name":"' + group_name + '", "group_type":"' + group_type + '"}';
            save_group(jsonStr);
        } else {
            if (!group_name) {
                parent.window.delayHide('m-danger-warning', '请填写分组名称', 1500);
                //tips('请填写分组名称！');
                $('input[name=group_name]').focus();
                return;
            }
            if (!group_type) {
                parent.window.delayHide('m-danger-warning', '请选择分组类型', 1500);
                //tips('请选择分组类型！');
                $('input[name=group_type]').focus();
                return;
            }
        }
    });
    $('#btn_back').click(function () {
        $('#before_save_pop').hide();
    });

    $('body').bind('click', function () {
        var current_h = $('body').height() > 580 ? $('body').height() : 580;
        var main_frame = $('#main', window.parent.document);
        main_frame.css('height', current_h);
    });
    function IntOrFloat(obj) {
        if ('' != obj.value.replace(/\d{1,}\.{0,1}\d{0,}/, '')) {
            obj.value = obj.value.match(/\d{1,}\.{0,1}\d{0,}/) == null ? '' : obj.value.match(/\d{1,}\.{0,1}\d{0,}/);
        }
    }

    function addStore(origin) {
        var store_id_str = $("#" + origin).val();
        //JSON.stringify(data)  转化为php识别的数据   $type是添加操作的标示  与 编辑区分开来
        art.dialog.open(
            '/index.php/marketing/marketing/filterStoreDialog' + '?store_id_str=' + store_id_str + '&type=add&origin=' + origin,
            {
                title: '',
                lock: true,
                drag: true,
                width: '700px',
                id: 'dialog_store',
                background: '#fff',
            }
        );
        return false;
    }

    function addStoreInfo(store_arr, origin) {
        var a = $('#' + origin).val();
        var em = $('em.choosed', $('#' + origin).parent());
        if (a) {
            var store_arr = a.split(',');
            store_arr.pop();
            em.html('(已选择' + store_arr.length + '家)');
        } else {
            em.html('点击选择门店');
        }
        art.dialog.get("dialog_store").close(); //关闭弹出框
    }

    function removeStore(id) {
        var store_str = $('#store_str').val();
        var str = '';
        var strId = '';


        $('#' + id).remove();
        var store_arr = new Array();
        store_arr = store_str.split(",");
        store_arr.pop();
        for (var j = 0; j < store_arr.length; j++) {
            if (store_arr[j] != id) {
                str = str + store_arr[j] + ",";
            }
        }

        var str_id = $("#str_id").val();
        var str_id_arr = new Array();
        str_id_arr = str_id.split(",");
        for (var i = 1; i < str_id_arr.length; i++) {
            if (str_id_arr[i] != id) {
                strId = strId + str_id_arr[i] + ",";
            }
        }
        strId = strId.substring(0, strId.length - 1);
        strId = "," + strId;

        $("#str_id").val(strId); //给隐藏域赋值
        $('#store_str').val(str);
    }

    $('.new_group_item span em.choosed').click(function () {
        var store_hidden_input = $('input[type=hidden]', $(this).parent());
        var condition_name = $(this).parent().parent().parent().attr('n');
        var origin_id = condition_name + '_store_id_str';
        store_hidden_input.attr('id', origin_id);
        addStore(origin_id);
    });

    function tips(msg) {
        layer.msg(msg, {icon: 2, time: 2000, shade: false, offset: ['260px', '40%']});
    }

    $(document).on("click", ".icon_del", function () {
        //提前置为不可保存
        can_not_calculate();
    });

    $(".filtrate_item li").click(function () {
        //提前置为不可保存
        can_not_calculate();
    });

</script>