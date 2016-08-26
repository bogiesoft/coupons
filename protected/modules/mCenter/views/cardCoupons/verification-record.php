<h4 class="g-title bb-none">
    <a href="" class="text-gray"><i class="fa fa-chevron-circle-left"></i></a>
    <span>优惠券核销 / </span>
    <span>核销记录</span>
</h4>
<nav class="nav m-cp-nav">
    <span class="nav-link" onclick="window.location.href='<?php echo Yii::app()->createUrl('mCenter/cardCoupons/CouponList') ?>'">优惠券管理</span>
    <span class="nav-link active" onclick="window.location.href='<?php echo Yii::app()->createUrl('mCenter/cardCoupons/verification') ?>'">优惠券核销</span>
    <span class="nav-link" onclick="window.location.href='<?php echo Yii::app()->createUrl('mCenter/cardCoupons/CouponCount') ?>'">优惠券统计</span>
</nav>
<nav class="nav m-cp-child-nav">
    <a class="nav-link" href="<?php echo Yii::app()->createUrl('mCenter/CardCoupons/VerificationMode') ?>">核销方式</a>
    <a class="nav-link active" href="<?php echo Yii::app()->createUrl('mCenter/CardCoupons/Verification') ?>">核销记录</a>
    <!--<a class="nav-link" href="#">核销员管理</a>-->
</nav>
<div class="g-container">

    <form action="<?php echo Yii::app()->createUrl('mCenter/cardCoupons/verification') ?>" id="addpro" method="get">

        <div class="cp-wrapper">
            <div class="m-search">
                <div class="form-inline">
                    <div class="form-group">
                        <select class="form-control" name="Coupon[type]" id="coupon_type">
                            <option value="0" <?php if (!isset($_GET['Coupon']['type'])) {
                                echo 'selected=selected';
                            } ?>>全部优惠券
                            </option>
                            <?php foreach ($coupon_type as $key => $v) { ?>
                                <option value="<?php echo $key ?>"
                                    <?php if (isset($_GET['Coupon']['type']) && $_GET['Coupon']['type'] == $key) {
                                        echo 'selected=selected';
                                    } ?>><?php echo $v ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group ml20">
                        <select class="form-control" id="time_select">
                            <option value="0">不限</option>
                            <option
                                <?php if (empty($_GET['Coupon']['time'])) {
                                    echo 'selected=selected';
                                } else {
                                    $times = explode(' - ', $_GET['Coupon']['time']);
                                    if ((strtotime(date('Y-m-d', time())) - strtotime($times[0])) == 86400 * 6) {
                                        echo 'selected=selected';
                                    }
                                } ?> value="7">最近7天
                            </option>
                            <option
                                <?php if (!empty($_GET['Coupon']['time'])) {
                                    if ((strtotime(date('Y-m-d', time())) - strtotime($times[0])) == 86400 * 14) {
                                        echo 'selected=selected';
                                    }
                                } ?> value="15">最近15天
                            </option>
                            <option
                                <?php if (!empty($_GET['Coupon']['time'])) {
                                    if ((strtotime(date('Y-m-d', time())) - strtotime($times[0])) == 86400 * 29) {
                                        echo 'selected=selected';
                                    }
                                } ?> value="30">最近30天
                            </option>
                        </select>
                        <input type="text" name="Coupon[time]" id="coupon_time" readonly="readonly"
                               value="<?php echo !empty($_GET['Coupon']['time']) ? $_GET['Coupon']['time'] : date('Y-m-d', strtotime('-6 days')) . ' - ' . date('Y-m-d', time()) ?>"
                               class="form-control ml10 fc-m-sm">
                    </div>

                    <div class="form-group ml15">
                        <select class="form-control" name="Coupon[store]" id="coupon_store">
                            <option value="0" <?php if (!isset($_GET['Coupon']['store'])) {
                                echo 'selected=selected';
                            } ?>>全部门店
                            </option>
                            <?php foreach ($store_lists as $key => $v) { ?>
                                <option value="<?php echo $key ?>"
                                    <?php if (isset($_GET['Coupon']['store']) && $_GET['Coupon']['store'] == $key) {
                                        echo 'selected=selected';
                                    } ?>><?php echo empty($v['branch_name']) ? $v['name'] : $v['name']."-".$v['branch_name']?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="input-group ml15">
                        <input name="Coupon[code]" id="coupon_code" type="text" class="form-control" placeholder="请输入券号"
                               value="<?php echo isset($_GET['Coupon']['code']) ? $_GET['Coupon']['code'] : '' ?>">
                        <span class="input-group-addon btn-search"><i class="fa fa-search"></i></span>
                    </div>
                    <div class="form-group fr">
                        <a href="<?php echo Yii::app()->createUrl('mCenter/cardCoupons/ExportExcel', array(
                            'Coupon' => isset($_GET['Coupon']) ? $_GET['Coupon'] : '')) ?>"
                           class="download-table download-icon"></a>
                    </div>
                </div>
            </div>
            <table class="table g-table g-table-center mt20">
                <thead>
                <tr>
                    <th>时间</th>
                    <th>券名</th>
                    <th>核销方式</th>
                    <th>门店</th>
                    <th>核销员</th>
                    <th>实收金额</th>
                    <th>应收金额</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($list)) {
                    foreach ($list as $v) { ?>
                        <tr>
                            <td><?php echo $v['validate_time'] ?></td>
                            <td><?php echo $v['title'] ?></td>
                            <td><?php echo array_key_exists($v['validate_channel'], $GLOBALS['__TERMINAL_TYPE_POS']) ? '收银端核销' : '核销小助手核销' ?></td>
                            <td><?php echo empty($v['store_branch_name']) ? $v['store_name'] : $v['store_name']."-".$v['store_branch_name']?></td>
                            <td><?php echo $v['operator_name'] ?></td>
                            <td><?php echo sprintf("%.2f", $v['order_paymoney'] - $v['coupons_money'] - $v['discount_money'] - $v['merchant_discount_money']) ?></td>
                            <td><?php echo sprintf("%.2f", $v['order_paymoney']) ?></td>
                            <td class="m-operate">
                                <a href="<?php echo Yii::app()->createUrl('mCenter/cardCoupons/verificationDetail', array('id' => $v['id'])) ?>">查看</a>
                            </td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr>
                        <td colspan="8">暂无交易记录</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <div class="g-pager">
                <div class="fr">
                <span>共<strong class="total-num">
                        <?php echo $pages->itemCount ?>
                    </strong>条
                </span>
                    <ul id="yw0" class="yiiPager">
                        <?php $this->widget('CLinkPager', array(
                            'pages' => $pages,
                            'header' => '', //分页前显示的内容
                            'maxButtonCount' => 8, //显示分页数量
                            'firstPageCssClass' => '',
                            'lastPageCssClass' => '',
                            'firstPageLabel' => '首页',
                            'nextPageLabel' => '下一页',
                            'prevPageLabel' => '上一页',
                            'lastPageLabel' => '末页',
                        )); ?>
                    </ul>
                </div>
            </div>
        </div>

    </form>

</div>

<script>
    $('#coupon_type').change(function () {
        $('#addpro').submit();
    });

    //快捷日期选择
    $('#time_select').change(function () {
        var day = $(this).val();
        if (day == 7) {
            var time = '<?php echo date('Y-m-d', strtotime('-6 days')) ?>' + ' - ' + '<?php echo date('Y-m-d', time()) ?>';
            $('#coupon_time').val(time);
            $('#addpro').submit();

            $('#coupon_time').daterangepicker({
                timePicker: false,
                format: 'YYYY-MM-DD',
                dateLimit: true,
                maxDate: '<?php echo date('Y-m-d', time()) ?>',
                startDate: '<?php echo date('Y-m-d', strtotime('-6 days')) ?>',
                endDate: '<?php echo date('Y-m-d', time()) ?>'
            });
        } else if (day == 15) {
            var time = '<?php echo date('Y-m-d', strtotime('-14 days')) ?>' + ' - ' + '<?php echo date('Y-m-d', time()) ?>';
            $('#coupon_time').val(time);
            $('#addpro').submit();

            $('#coupon_time').daterangepicker({
                timePicker: false,
                format: 'YYYY-MM-DD',
                dateLimit: true,
                maxDate: '<?php echo date('Y-m-d', time()) ?>',
                startDate: '<?php echo date('Y-m-d', strtotime('-14 days')) ?>',
                endDate: '<?php echo date('Y-m-d', time()) ?>'
            });
        } else if (day == 30) {
            var time = '<?php echo date('Y-m-d', strtotime('-29 days')) ?>' + ' - ' + '<?php echo date('Y-m-d', time()) ?>';
            $('#coupon_time').val(time);
            $('#addpro').submit();

            $('#coupon_time').daterangepicker({
                timePicker: false,
                format: 'YYYY-MM-DD',
                dateLimit: true,
                maxDate: '<?php echo date('Y-m-d', time()) ?>',
                startDate: '<?php echo date('Y-m-d', strtotime('-29 days')) ?>',
                endDate: '<?php echo date('Y-m-d', time()) ?>'
            });
        }
    });

    //时间选择控件
    $(function () {
        $('#coupon_time').daterangepicker({
            timePicker: false,
            format: 'YYYY-MM-DD',
            dateLimit: true,
            maxDate: '<?php echo date('Y-m-d', time());?>',
            startDate: '<?php echo !empty($_GET['Coupon']['time']) ? $times[0] : date('Y-m-d', strtotime('-6 days')) ?>',
            endDate: '<?php echo !empty($_GET['Coupon']['time']) ? $times[1] :  date('Y-m-d', time());?>'
        });

        $('.btn-success').click(function () {
            var start_time1 = $('input[name=daterangepicker_start]').val();
            var end_time1 = $('input[name=daterangepicker_end]').val();

            var start_time2 = Date.parse(new Date(start_time1 + ' 00:00:00'));
            var start_time3 = start_time2 / 1000;

            var end_time2 = Date.parse(new Date(end_time1 + ' 00:00:00'));
            var end_time3 = end_time2 / 1000;

            var myDate = new Date();
            var now = Date.parse(new Date(myDate.toLocaleDateString() + ' 00:00:00'));
            now = now / 1000;

            if (now - start_time3 == 6 * 86400) {
                $('#time_select').val(7);
                $('#coupon_time').val(start_time1 + ' - ' + end_time1);
                $('#addpro').submit();
            } else if (now - start_time3 == 14 * 86400) {
                $('#time_select').val(15);
                $('#coupon_time').val(start_time1 + ' - ' + end_time1);
                $('#addpro').submit();
            } else if (now - start_time3 == 29 * 86400) {
                $('#time_select').val(30);
                $('#coupon_time').val(start_time1 + ' - ' + end_time1);
                $('#addpro').submit();
            } else {
                $('#time_select').val(0);
                $('#coupon_time').val(start_time1 + ' - ' + end_time1);
                $('#addpro').submit();
            }
        });
    });

    //门店选择
    $('#coupon_store').change(function () {
        $('#addpro').submit();
    });

    //code搜索
    $('.btn-search').click(function () {
        $('#addpro').submit();
    });
</script>
