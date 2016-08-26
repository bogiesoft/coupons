<h4 class="g-title bb-none">优惠券统计</h4>
<nav class="nav m-cp-nav">
    <span class="nav-link" onclick="window.location.href='<?php echo Yii::app()->createUrl('mCenter/cardCoupons/CouponList') ?>'">优惠券管理</span>
    <span class="nav-link" onclick="window.location.href='<?php echo Yii::app()->createUrl('mCenter/cardCoupons/verification') ?>'">优惠券核销</span>
    <span class="nav-link active" onclick="window.location.href='<?php echo Yii::app()->createUrl('mCenter/cardCoupons/CouponCount') ?>'">优惠券统计</span>
</nav>
<div class="g-container">
    <div class="row m-data-row">
        <div class="col-sm-3">
            <h3><?php echo !empty($total_pv) ? $total_pv : 0 ?></h3>
            <p>浏览总量</p>
        </div>
        <div class="col-sm-3">
            <h3><?php echo !empty($total_receive) ? $total_receive : 0 ?></h3>
            <p>领券总量</p>
        </div>
        <div class="col-sm-3">
            <h3><?php echo !empty($total_use) ? $total_use : 0 ?></h3>
            <p>使用总量</p>
        </div>
        <div class="col-sm-3">
            <h3><?php if ($total_receive > 0) {
                    echo round($total_use / $total_receive * 100, 2);
                } else {
                    echo 0;
                } ?>%</h3>
            <p>核销率</p>
        </div>
    </div>

    <form action="<?php echo Yii::app()->createUrl('mCenter/cardCoupons/CouponCount') ?>" id="addpro" method="get">

        <div class="panel panel-default m-panel mt20">
            <div class="panel-heading">
                <h3 class="panel-title">趋势统计图</h3>
            </div>
            <div class="panel-body">
                <div class="m-filter form-inline">
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
                    <div class="form-group ml15">
                        <select class="form-control" id="img_type" name="Coupon[img_type]">
                            <option value="pv" selected="selected">浏览总数趋势图</option>
                            <option value="receive">领取总量趋势图</option>
                            <option value="use">使用总量趋势图</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <span class="pl20">时间：</span>
                        <button type="button" class="btn <?php
                        if (empty($_GET['Coupon']['time'])) {
                            echo 'btn-primary';
                        } else {
                            $times = explode(' - ', $_GET['Coupon']['time']);
                            if ((strtotime(date('Y-m-d', time())) - strtotime($times[0])) == 86400 * 7) {
                                echo 'btn-primary';
                            } else{
                                echo 'btn-default';
                            }
                        } ?> m-btn-sm" id="days7">7日
                        </button>
                        <button type="button" class="btn <?php
                        if (!empty($_GET['Coupon']['time'])) {
                            if ((strtotime(date('Y-m-d', time())) - strtotime($times[0])) == 86400 * 15) {
                                echo 'btn-primary';
                            } else {
                                echo 'btn-default';
                            }
                        } else {
                            echo 'btn-default';
                        } ?> m-btn-sm ml10" id="days15">15日
                        </button>
                        <button type="button" class="btn <?php
                        if (!empty($_GET['Coupon']['time'])) {
                            if ((strtotime(date('Y-m-d', time())) - strtotime($times[0])) == 86400 * 30) {
                                echo 'btn-primary';
                            } else {
                                echo 'btn-default';
                            }
                        } else {
                            echo 'btn-default';
                        } ?> m-btn-sm ml10" id="days30">30日
                        </button>
                    </div>
                    <br>
                    <div class="form-group ml10">
                        <input type="text" name="Coupon[time]" id="data_time" class="form-control fc-sm" readonly="readonly"
                               value="<?php echo !empty($_GET['Coupon']['time']) ? $_GET['Coupon']['time'] : date('Y-m-d', strtotime('-7 days')) . ' - ' . date('Y-m-d', time() - 86400) ?>">
                    </div>
                </div>
                <div id="coupon-chart" style="width: 100%;height:400px;margin-top: -20px;"></div>
            </div>
        </div>
        <table class="table g-table g-table-center mt30">
            <thead>
            <tr>
                <th>时间</th>
                <th>浏览次数</th>
                <th>领取次数</th>
                <th>核销次数</th>
                <th>核销率</th>
                <th>订单金额(元)</th>
                <th>实收金额(元)</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($list)) {
                foreach ($list as $v) { ?>
                    <tr>
                        <td><?php echo date('Y.m.d', strtotime($v['date'])) ?></td>
                        <td><?php echo $v['total_browse_num'] ?></td>
                        <td><?php echo $v['total_receive_num'] ?></td>
                        <td><?php echo $v['total_use_num'] ?></td>
                        <td><?php if ($v['total_receive_num'] > 0) {
                                echo round($v['total_use_num'] / $v['total_receive_num'] * 100, 2);
                            } else {
                                echo 0;
                            } ?>%
                        </td>
                        <td><?php echo sprintf('%.2f', $v['total_order_paymoney']) ?></td>
                        <td><?php echo sprintf('%.2f', $v['total_relpay']) ?></td>
                    </tr>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="7">暂无交易记录</td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <div class="g-pager">
            <div class="fl">可在当日上午8点后，在报表中查看前一日数据</div>
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

    </form>
</div>

<script>
    $(function () {
        var myChart = echarts.init(document.getElementById('coupon-chart'));
        option = {
            tooltip: {
                trigger: 'item',
                formatter: '{a} <br/>{b} : {c}'
            },
            color: [
                "#35ae3a"
            ],
            xAxis: {
                type: 'category',
                name: '',
                splitLine: {show: false},
                data: <?php echo json_encode(array_keys($img_data)) ?>
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            yAxis: {
                type: 'value',
                name: '浏览次数'
            },
            series: [
                {
                    name: '浏览次数',
                    type: 'line',
                    data: <?php
                    foreach ($img_data as $v) {
                        $new_browse_num[] = $v['new_browse_num'];
                    }
                    echo json_encode($new_browse_num) ?>
                }
            ]
        };
        myChart.setOption(option);

        $('#coupon_type').change(function () {
            $('#addpro').submit();
        });

        //快捷日期选择
        $('#days7').click(function () {
            $(this).removeClass('btn-default');
            $(this).addClass('btn-primary');
            $('#days15').removeClass('btn-primary');
            $('#days15').addClass('btn-default');
            $('#days30').removeClass('btn-primary');
            $('#days30').addClass('btn-default');

            var time = '<?php echo date('Y-m-d', strtotime('-7 days')) ?>' + ' - ' + '<?php echo date('Y-m-d', time() - 86400) ?>';
            $('#data_time').val(time);
            $('#data_time').daterangepicker({
                timePicker: false,
                format: 'YYYY-MM-DD',
                dateLimit: true,
                maxDate: '<?php echo date('Y-m-d', time() - 86400) ?>',
                startDate: '<?php echo date('Y-m-d', strtotime('-7 days')) ?>',
                endDate: '<?php echo date('Y-m-d', time() - 86400) ?>'
            });

            $('#addpro').submit();
        });

        $('#days15').click(function () {
            $(this).removeClass('btn-default');
            $(this).addClass('btn-primary');
            $('#days7').removeClass('btn-primary');
            $('#days7').addClass('btn-default');
            $('#days30').removeClass('btn-primary');
            $('#days30').addClass('btn-default');

            var time = '<?php echo date('Y-m-d', strtotime('-15 days')) ?>' + ' - ' + '<?php echo date('Y-m-d', time() - 86400) ?>';
            $('#data_time').val(time);
            $('#data_time').daterangepicker({
                timePicker: false,
                format: 'YYYY-MM-DD',
                dateLimit: true,
                maxDate: '<?php echo date('Y-m-d', time() - 86400) ?>',
                startDate: '<?php echo date('Y-m-d', strtotime('-15 days')) ?>',
                endDate: '<?php echo date('Y-m-d', time() - 86400) ?>'
            });

            $('#addpro').submit();
        });

        $('#days30').click(function () {
            $(this).removeClass('btn-default');
            $(this).addClass('btn-primary');
            $('#days15').removeClass('btn-primary');
            $('#days15').addClass('btn-default');
            $('#days7').removeClass('btn-primary');
            $('#days7').addClass('btn-default');

            var time = '<?php echo date('Y-m-d', strtotime('-30 days')) ?>' + ' - ' + '<?php echo date('Y-m-d', time() - 86400) ?>';
            $('#data_time').val(time);
            $('#data_time').daterangepicker({
                timePicker: false,
                format: 'YYYY-MM-DD',
                dateLimit: true,
                maxDate: '<?php echo date('Y-m-d', time() - 86400) ?>',
                startDate: '<?php echo date('Y-m-d', strtotime('-30 days')) ?>',
                endDate: '<?php echo date('Y-m-d', time() - 86400) ?>'
            });

            $('#addpro').submit();
        });

        //时间选择控件
        $(function () {
            $('#data_time').daterangepicker({
                timePicker: false,
                format: 'YYYY-MM-DD',
                dateLimit: true,
                maxDate: '<?php echo date('Y-m-d', time() - 86400);?>',
                startDate: '<?php echo !empty($_GET['Coupon']['time']) ? $times[0] : date('Y-m-d', strtotime('-7 days')) ?>',
                endDate: '<?php echo !empty($_GET['Coupon']['time']) ? $times[1] : date('Y-m-d', time() - 86400);?>'
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

                if (now - start_time3 == 7 * 86400) {
                    $('#days7').removeClass('btn-default');
                    $('#days7').addClass('btn-primary');

                    $('#days15').removeClass('btn-primary');
                    $('#days15').addClass('btn-default');
                    $('#days30').removeClass('btn-primary');
                    $('#days30').addClass('btn-default');

                    $('#data_time').val(start_time1 + ' - ' + end_time1);
                    $('#addpro').submit();
                } else if (now - start_time3 == 15 * 86400) {
                    $('#days15').removeClass('btn-default');
                    $('#days15').addClass('btn-primary');

                    $('#days7').removeClass('btn-primary');
                    $('#days7').addClass('btn-default');
                    $('#days30').removeClass('btn-primary');
                    $('#days30').addClass('btn-default');

                    $('#data_time').val(start_time1 + ' - ' + end_time1);
                    $('#addpro').submit();
                } else if (now - start_time3 == 30 * 86400) {
                    $('#days30').removeClass('btn-default');
                    $('#days30').addClass('btn-primary');

                    $('#days7').removeClass('btn-primary');
                    $('#days7').addClass('btn-default');
                    $('#days15').removeClass('btn-primary');
                    $('#days15').addClass('btn-default');

                    $('#data_time').val(start_time1 + ' - ' + end_time1);
                    $('#addpro').submit();
                } else {
                    $('#days7').removeClass('btn-primary');
                    $('#days7').addClass('btn-default');
                    $('#days15').removeClass('btn-primary');
                    $('#days15').addClass('btn-default');
                    $('#days30').removeClass('btn-primary');
                    $('#days30').addClass('btn-default');

                    $('#data_time').val(start_time1 + ' - ' + end_time1);
                    $('#addpro').submit();
                }
            });
        });

        $('#img_type').change(function () {
            var img_type = $(this).val();
            if (img_type == 'pv') {
                option = {
                    tooltip: {
                        trigger: 'item',
                        formatter: '{a} <br/>{b} : {c}'
                    },
                    color: [
                        "#35ae3a"
                    ],
                    xAxis: {
                        type: 'category',
                        name: '',
                        splitLine: {show: false},
                        data: <?php echo json_encode(array_keys($img_data)) ?>
                    },
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        containLabel: true
                    },
                    yAxis: {
                        type: 'value',
                        name: '浏览次数'
                    },
                    series: [
                        {
                            name: '浏览次数',
                            type: 'line',
                            data: <?php
                                foreach ($img_data as $v) {
                                    $new_browse_num[] = $v['new_browse_num'];
                                }
                                echo json_encode($new_browse_num) ?>
                        }
                    ]
                };
            } else if (img_type == 'receive') {
                option = {
                    tooltip: {
                        trigger: 'item',
                        formatter: '{a} <br/>{b} : {c}'
                    },
                    color: [
                        "#35ae3a"
                    ],
                    xAxis: {
                        type: 'category',
                        name: '',
                        splitLine: {show: false},
                        data: <?php echo json_encode(array_keys($img_data)) ?>
                    },
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        containLabel: true
                    },
                    yAxis: {
                        type: 'value',
                        name: '领取次数'
                    },
                    series: [
                        {
                            name: '领取次数',
                            type: 'line',
                            data: <?php
                                foreach ($img_data as $v) {
                                    $new_receive_num[] = $v['new_receive_num'];
                                }
                                echo json_encode($new_receive_num) ?>
                        }
                    ]
                };
            } else if (img_type == 'use') {
                option = {
                    tooltip: {
                        trigger: 'item',
                        formatter: '{a} <br/>{b} : {c}'
                    },
                    color: [
                        "#35ae3a"
                    ],
                    xAxis: {
                        type: 'category',
                        name: '',
                        splitLine: {show: false},
                        data: <?php echo json_encode(array_keys($img_data)) ?>
                    },
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        containLabel: true
                    },
                    yAxis: {
                        type: 'value',
                        name: '使用次数'
                    },
                    series: [
                        {
                            name: '使用次数',
                            type: 'line',
                            data: <?php
                                foreach ($img_data as $v) {
                                    $new_use_num[] = $v['new_use_num'];
                                }
                                echo json_encode($new_use_num) ?>
                        }
                    ]
                };
            }

            myChart.setOption(option);
        });
    });
</script>