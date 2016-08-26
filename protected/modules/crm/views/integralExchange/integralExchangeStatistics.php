<h4 class="g-title bb-none">积分兑换</h4>
<nav class="nav m-cp-nav">
    <span id="exchange" class="nav-link">积分兑换</span>
    <span id="statistics1" class="nav-link active">积分换券统计</span>
</nav>
<div class="g-container">
    <div class="row m-data-row integral-data-row">
        <div class="col-sm-3">
            <h3><?php echo isset($rs['exchange_num_point']['0']['e_pv']) ? $rs['exchange_num_point']['0']['e_pv'] : 0 ?></h3>
            <p>浏览次数</p>
        </div>
        <div class="col-sm-3">
            <h3><?php echo isset($rs['exchange_num_point']['0']['e_num']) ? $rs['exchange_num_point']['0']['e_num'] : 0 ?></h3>
            <p>兑换张数</p>
        </div>
        <div class="col-sm-3">
            <h3><?php echo isset($rs['exchange_num_point']['0']['e_point']) ? $rs['exchange_num_point']['0']['e_point'] : 0 ?></h3>
            <p>兑换积分</p>
        </div>
        <div class="col-sm-3">
            <h3><?php echo isset($rs['use_num']['0']['u_num']) ? $rs['use_num']['0']['u_num'] : 0 ?></h3>
            <p>核销次数</p>
        </div>
        <div class="col-sm-3">
            <h3><?php echo isset($rs['exchange_num_point']['0']['e_num']) ? round($rs['use_num']['0']['u_num'] / $rs['exchange_num_point']['0']['e_num'], 2) * 100 . '%' : '0%' ?></h3>
            <p>核销率</p>
        </div>
    </div>
    <form action="<?php $this -> createUrl('integralExchangeStatistics'); ?>" method="get" id="statistic">
        <div class="panel panel-default m-panel mt20">
            <div class="panel-heading">
                <h3 class="panel-title">积分换券</h3>
            </div>
            <div class="panel-body">
                <div class="m-filter form-inline">
                        <div class="form-group">
                            <select class="form-control" id="coupon_id" name="coupon_id">
                                <option value="" <?php echo !isset($_GET['coupon_id']) ? 'selected=selected' : '' ?>>全部可兑换券</option>
                                <?php if (isset($list) && !empty($list)) { ?>
                                <?php foreach ($list as $v) {?>
                                    <option value="<?php echo $v['id'] ?>" <?php echo isset($_GET['coupon_id']) && $_GET['coupon_id'] == $v['id'] ? 'selected=selected' : '' ?>><?php echo $v['ctitle']; ?></option>
                                <?php }} ?>
                            </select>
                        </div>
                        <div class="form-group ml15">
                            <select class="form-control" id="trend" name="trend">
                                <option value="view">浏览趋势</option>
                                <option value="exchange">兑换趋势</option>
                                <option value="use">使用趋势</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <span class="pl20">时间：</span>
                            <button id="days7" type="button" class="btn <?php
                            if (empty($_GET['data_time'])) {
                                echo 'btn-primary';
                            } else {
                                $times = explode(' - ', $_GET['data_time']);
                                if ((strtotime(date('Y-m-d', time())) - strtotime($times[0])) == 86400 * 7) {
                                    echo 'btn-primary';
                                } else{
                                    echo 'btn-default';
                                }
                            } ?> m-btn-sm">7日</button>
                            <button id="days15" type="button" class="btn <?php
                            if (!empty($_GET['data_time'])) {
                                $times = explode(' - ', $_GET['data_time']);
                                if ((strtotime(date('Y-m-d', time())) - strtotime($times[0])) == 86400 * 15) {
                                    echo 'btn-primary';
                                } else {
                                    echo 'btn-default';
                                }
                            } else {
                                echo 'btn-default';
                            } ?> m-btn-sm ml10">15日</button>
                            <button id="days30" type="button" class="btn <?php
                            if (!empty($_GET['data_time'])) {
                                $times = explode(' - ', $_GET['data_time']);
                                if ((strtotime(date('Y-m-d', time())) - strtotime($times[0])) == 86400 * 30) {
                                    echo 'btn-primary';
                                } else {
                                    echo 'btn-default';
                                }
                            } else {
                                echo 'btn-default';
                            } ?> m-btn-sm ml10">30日</button>
                        </div>
                        <div class="form-group ml10">
                            <input type="text" id="data_time" name="data_time" class="form-control fc-m-sm" value="<?php echo !empty($_GET['data_time']) ? $_GET['data_time'] : date('Y-m-d', strtotime('-7 days')) . ' - ' . date('Y-m-d', time() - 86400) ?>">
                        </div>
                </div>
                <div id="coupon-chart" style="width: 100%;height:400px;"></div>
            </div>
        </div>

        <div class="panel panel-default m-panel mt20">
            <div class="panel-heading">
                <h3 class="panel-title">积分换券数据详情</h3>
            </div>
            <div class="panel-body">
                <div class="m-filter form-inline">
                    <div class="form-group">
                        <select class="form-control" id="coupon_id1" name="coupon_id1">
                            <option value="" <?php echo !isset($_GET['coupon_id1']) ? 'selected=selected' : '' ?>>全部可兑换券</option>
                            <?php if (isset($list) && !empty($list)) { ?>
                                <?php foreach ($list as $v) {?>
                                    <option value="<?php echo $v['id'] ?>" <?php echo isset($_GET['coupon_id1']) && $_GET['coupon_id1'] == $v['id'] ? 'selected=selected' : '' ?>><?php echo $v['ctitle']; ?></option>
                                <?php }} ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <span class="pl20">时间：</span>
                        <button id="days71" type="button" class="btn <?php
                        if (empty($_GET['data_time1'])) {
                            echo 'btn-primary';
                        } else {
                            $times = explode(' - ', $_GET['data_time1']);
                            if ((strtotime(date('Y-m-d', time())) - strtotime($times[0])) == 86400 * 7) {
                                echo 'btn-primary';
                            } else{
                                echo 'btn-default';
                            }
                        } ?> m-btn-sm">7日</button>
                        <button id="days151" type="button" class="btn <?php
                        if (!empty($_GET['data_time1'])) {
                            $times = explode(' - ', $_GET['data_time1']);
                            if ((strtotime(date('Y-m-d', time())) - strtotime($times[0])) == 86400 * 15) {
                                echo 'btn-primary';
                            } else {
                                echo 'btn-default';
                            }
                        } else {
                            echo 'btn-default';
                        } ?> m-btn-sm ml10">15日</button>
                        <button id="days301" type="button" class="btn <?php
                        if (!empty($_GET['data_time1'])) {
                            $times = explode(' - ', $_GET['data_time1']);
                            if ((strtotime(date('Y-m-d', time())) - strtotime($times[0])) == 86400 * 30) {
                                echo 'btn-primary';
                            } else {
                                echo 'btn-default';
                            }
                        } else {
                            echo 'btn-default';
                        } ?> m-btn-sm ml10">30日</button>
                    </div>
                    <div class="form-group ml10">
                        <input type="text" id="data_time1" name="data_time1" class="form-control fc-m-sm" value="<?php echo !empty($_GET['data_time1']) ? $_GET['data_time1'] : date('Y-m-d', strtotime('-7 days')) . ' - ' . date('Y-m-d', time() - 86400) ?>">
                    </div>
<!--                    <a href="" class="fr mt6">下载表格</a>-->
                </div>
                <table class="table g-table g-table-center cm-count-table g-table-fixed">
                    <tbody class="cm-count-table-body">
                    <tr class="heading">

                        <td colspan="2" rowspan="2" class="text-left time fw">可兑换券</td>
                        <td colspan="2" class="fw">浏览</td>
                        <td colspan="2" class="fw">领取</td>
                        <td colspan="2" class="fw">使用</td>
                        <td colspan="2" class="fw">效果</td>
                    </tr>
                    <tr class="heading">
                        <td class="fw">人数</td>
                        <td class="fw">次数</td>
                        <td class="fw">人数</td>
                        <td class="fw">次数</td>
                        <td class="fw">人数</td>
                        <td class="fw">次数</td>
                        <td class="fw">兑换率</td>
                        <td class="fw">使用率</td>
                    </tr>
                    <?php foreach ($rs_statistic as $v) { ?>
                    <tr class="text-right-tr">
                        <td colspan="2" class="text-left text-overflow" title="<?php echo $v['title'] ?>"><?php echo $v['title'] ?></td>
                        <td><?php echo $v['bpn'] ?></td>
                        <td><?php echo $v['bn'] ?></td>
                        <td><?php echo $v['epn'] ?></td>
                        <td><?php echo $v['en'] ?></td>
                        <td><?php echo $v['upn'] ?></td>
                        <td><?php echo $v['un'] ?></td>
                        <td><?php if ($v['bn'] == 0) { echo '0'; } else { echo $v['en'] / $v['bn']; } ?></td>
                        <td><?php if ($v['en'] == 0) { echo '0'; } else { echo $v['un'] / $v['en']; } ?></td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>

            </div>
    </div>
        <div class="g-pager">
            <div class="fr">
                <ul id="yw0" class="yiiPager">
                    <?php $this->widget('CLinkPager', array(
                        'pages' => $pages,
                        'header' => '<span>共<strong class="total-num">' . $pages->getItemCount() . '</strong>条</span>', //分页前显示的内容
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
                "#35ae3a", "#4990de"
            ],
            legend: {
                left: 'center',
                data: ['人数', '次数']
            },
            xAxis: {
                type: 'category',
                name: 'x',
                splitLine: {show: false},
                data: <?php echo json_encode(array_keys($trend)) ?>
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            yAxis: {
                type: 'value',
                name: '人数/次数'
            },
            series: [
                {
                    name: '人数',
                    type: 'line',
                    data: <?php
                    $browse_person_num = array();
                    if (!empty($trend)) {
                        foreach ($trend as $v) {
                            $browse_person_num[] = $v['bpn'];
                        }
                    }
                    echo json_encode($browse_person_num);
                    ?>
                },
                {
                    name: '次数',
                    type: 'line',
                    data: <?php
                    $browse_num = array();
                    if (!empty($trend)) {
                        foreach ($trend as $v) {
                            $browse_num[] = $v['bn'];
                        }
                    }
                    echo json_encode($browse_num);
                    ?>
                }
            ]
        };
        myChart.setOption(option);

        $("#exchange").click(function () {
            window.location.href = '<?php echo $this->createUrl('integralExchange/integralExchangeList') ?>';
        });
        $("#statistics1").click(function () {
            window.location.href = '<?php echo $this->createUrl('integralExchange/integralExchangeStatistics') ?>';
        });

        $("#coupon_id").change(function () {
            $("#statistic").submit();
        });

        $("#coupon_id1").change(function () {
            $("#statistic").submit();
        });

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

            $('#statistic').submit();
        });

        $('#days71').click(function () {
            $(this).removeClass('btn-default');
            $(this).addClass('btn-primary');
            $('#days151').removeClass('btn-primary');
            $('#days151').addClass('btn-default');
            $('#days301').removeClass('btn-primary');
            $('#days301').addClass('btn-default');

            var time = '<?php echo date('Y-m-d', strtotime('-7 days')) ?>' + ' - ' + '<?php echo date('Y-m-d', time() - 86400) ?>';
            $('#data_time1').val(time);
            $('#data_time1').daterangepicker({
                timePicker: false,
                format: 'YYYY-MM-DD',
                dateLimit: true,
                maxDate: '<?php echo date('Y-m-d', time() - 86400) ?>',
                startDate: '<?php echo date('Y-m-d', strtotime('-7 days')) ?>',
                endDate: '<?php echo date('Y-m-d', time() - 86400) ?>'
            });

            $('#statistic').submit();
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

            $('#statistic').submit();
        });

        $('#days151').click(function () {
            $(this).removeClass('btn-default');
            $(this).addClass('btn-primary');
            $('#days71').removeClass('btn-primary');
            $('#days71').addClass('btn-default');
            $('#days301').removeClass('btn-primary');
            $('#days301').addClass('btn-default');

            var time = '<?php echo date('Y-m-d', strtotime('-15 days')) ?>' + ' - ' + '<?php echo date('Y-m-d', time() - 86400) ?>';
            $('#data_time1').val(time);
            $('#data_time1').daterangepicker({
                timePicker: false,
                format: 'YYYY-MM-DD',
                dateLimit: true,
                maxDate: '<?php echo date('Y-m-d', time() - 86400) ?>',
                startDate: '<?php echo date('Y-m-d', strtotime('-15 days')) ?>',
                endDate: '<?php echo date('Y-m-d', time() - 86400) ?>'
            });

            $('#statistic').submit();
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
        });

        $('#days301').click(function () {
            $(this).removeClass('btn-default');
            $(this).addClass('btn-primary');
            $('#days151').removeClass('btn-primary');
            $('#days151').addClass('btn-default');
            $('#days71').removeClass('btn-primary');
            $('#days71').addClass('btn-default');

            var time = '<?php echo date('Y-m-d', strtotime('-30 days')) ?>' + ' - ' + '<?php echo date('Y-m-d', time() - 86400) ?>';
            $('#data_time1').val(time);
            $('#data_time1').daterangepicker({
                timePicker: false,
                format: 'YYYY-MM-DD',
                dateLimit: true,
                maxDate: '<?php echo date('Y-m-d', time() - 86400) ?>',
                startDate: '<?php echo date('Y-m-d', strtotime('-30 days')) ?>',
                endDate: '<?php echo date('Y-m-d', time() - 86400) ?>'
            });

            $('#statistic').submit();
        });



        $(function () {
            $('input[name="data_time"]').daterangepicker({
                timePicker: false,
                format: 'YYYY-MM-DD',
                opens: "left",
                dateLimit: true,
                maxDate: '<?php echo date('Y-m-d', time() - 86400);?>'
            });

            $('input[name="data_time1"]').daterangepicker({
                timePicker: false,
                format: 'YYYY-MM-DD',
                opens: "left",
                dateLimit: true,
                maxDate: '<?php echo date('Y-m-d', time() - 86400);?>'
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
                    $('#days71').removeClass('btn-default');
                    $('#days71').addClass('btn-primary');

                    $('#days15').removeClass('btn-primary');
                    $('#days15').addClass('btn-default');
                    $('#days30').removeClass('btn-primary');
                    $('#days30').addClass('btn-default');

                    $('#days151').removeClass('btn-primary');
                    $('#days151').addClass('btn-default');
                    $('#days301').removeClass('btn-primary');
                    $('#days301').addClass('btn-default');

                    $('#data_time').val(start_time1 + ' - ' + end_time1);
                    $('#data_time1').val(start_time1 + ' - ' + end_time1);
                    $('#statistic').submit();
                } else if (now - start_time3 == 15 * 86400) {
                    $('#days15').removeClass('btn-default');
                    $('#days15').addClass('btn-primary');

                    $('#days7').removeClass('btn-primary');
                    $('#days7').addClass('btn-default');
                    $('#days30').removeClass('btn-primary');
                    $('#days30').addClass('btn-default');

                    $('#data_time').val(start_time1 + ' - ' + end_time1);

                    $('#days151').removeClass('btn-default');
                    $('#days151').addClass('btn-primary');

                    $('#days71').removeClass('btn-primary');
                    $('#days71').addClass('btn-default');
                    $('#days301').removeClass('btn-primary');
                    $('#days301').addClass('btn-default');

                    $('#data_time').val(start_time1 + ' - ' + end_time1);
                    $('#data_time1').val(start_time1 + ' - ' + end_time1);
                    $('#statistic').submit();
                } else if (now - start_time3 == 30 * 86400) {
                    $('#days30').removeClass('btn-default');
                    $('#days30').addClass('btn-primary');

                    $('#days7').removeClass('btn-primary');
                    $('#days7').addClass('btn-default');
                    $('#days15').removeClass('btn-primary');
                    $('#days15').addClass('btn-default');

                    $('#data_time').val(start_time1 + ' - ' + end_time1);

                    $('#days301').removeClass('btn-default');
                    $('#days30').addClass('btn-primary');

                    $('#days71').removeClass('btn-primary');
                    $('#days71').addClass('btn-default');
                    $('#days151').removeClass('btn-primary');
                    $('#days151').addClass('btn-default');

                    $('#data_time1').val(start_time1 + ' - ' + end_time1);
                    $('#statistic').submit();
                } else {
                    $('#days7').removeClass('btn-primary');
                    $('#days7').addClass('btn-default');
                    $('#days15').removeClass('btn-primary');
                    $('#days15').addClass('btn-default');
                    $('#days30').removeClass('btn-primary');
                    $('#days30').addClass('btn-default');

                    $('#days71').removeClass('btn-primary');
                    $('#days71').addClass('btn-default');
                    $('#days151').removeClass('btn-primary');
                    $('#days151').addClass('btn-default');
                    $('#days301').removeClass('btn-primary');
                    $('#days301').addClass('btn-default');

                    $('#data_time').val(start_time1 + ' - ' + end_time1);
                    $('#data_time1').val(start_time1 + ' - ' + end_time1);
                    $('#statistic').submit();
                }
            });
        });

        $('#trend').change(function () {
            var trend_type = $(this).val();
            if (trend_type == 'view') {
                option = {
                    tooltip: {
                        trigger: 'item',
                        formatter: '{a} <br/>{b} : {c}'
                    },
                    color: [
                        "#35ae3a", "#4990de"
                    ],
                    legend: {
                        left: 'center',
                        data: ['人数', '次数']
                    },
                    xAxis: {
                        type: 'category',
                        name: 'x',
                        splitLine: {show: false},
                        data: <?php echo json_encode(array_keys($trend)) ?>
                    },
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        containLabel: true
                    },
                    yAxis: {
                        type: 'value',
                        name: '人数/次数'
                    },
                    series: [
                        {
                            name: '人数',
                            type: 'line',
                            data: <?php
                            $browse_person_num = array();
                            if (!empty($trend)) {
                                foreach ($trend as $v) {
                                    $browse_person_num[] = $v['bpn'];
                                }
                            }
                            echo json_encode($browse_person_num);
                            ?>
                        },
                        {
                            name: '次数',
                            type: 'line',
                            data: <?php
                            $browse_num = array();
                            if (!empty($trend)) {
                                foreach ($trend as $v) {
                                    $browse_num[] = $v['bn'];
                                }
                            }
                            echo json_encode($browse_num);
                            ?>
                        }
                    ]
                };
            } else if (trend_type == 'exchange') {
                option = {
                    tooltip: {
                        trigger: 'item',
                        formatter: '{a} <br/>{b} : {c}'
                    },
                    color: [
                        "#35ae3a", "#4990de"
                    ],
                    legend: {
                        left: 'center',
                        data: ['人数', '次数']
                    },
                    xAxis: {
                        type: 'category',
                        name: 'x',
                        splitLine: {show: false},
                        data: <?php echo json_encode(array_keys($trend)) ?>
                    },
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        containLabel: true
                    },
                    yAxis: {
                        type: 'value',
                        name: '人数/次数'
                    },
                    series: [
                        {
                            name: '人数',
                            type: 'line',
                            data: <?php
                            $browse_person_num = array();
                            if (!empty($trend)) {
                                foreach ($trend as $v) {
                                    $browse_person_num[] = $v['epn'];
                                }
                            }
                            echo json_encode($browse_person_num);
                            ?>
                        },
                        {
                            name: '次数',
                            type: 'line',
                            data: <?php
                            $browse_num = array();
                            if (!empty($trend)) {
                                foreach ($trend as $v) {
                                    $browse_num[] = $v['en'];
                                }
                            }
                            echo json_encode($browse_num);
                            ?>
                        }
                    ]
                };
            } else if (trend_type == 'use') {
                option = {
                    tooltip: {
                        trigger: 'item',
                        formatter: '{a} <br/>{b} : {c}'
                    },
                    color: [
                        "#35ae3a", "#4990de"
                    ],
                    legend: {
                        left: 'center',
                        data: ['人数', '次数']
                    },
                    xAxis: {
                        type: 'category',
                        name: 'x',
                        splitLine: {show: false},
                        data: <?php echo json_encode(array_keys($trend)) ?>
                    },
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        containLabel: true
                    },
                    yAxis: {
                        type: 'value',
                        name: '人数/次数'
                    },
                    series: [
                        {
                            name: '人数',
                            type: 'line',
                            data: <?php
                            $browse_person_num = array();
                            if (!empty($trend)) {
                                foreach ($trend as $v) {
                                    $browse_person_num[] = $v['upn'];
                                }
                            }
                            echo json_encode($browse_person_num);
                            ?>
                        },
                        {
                            name: '次数',
                            type: 'line',
                            data: <?php
                            $browse_num = array();
                            if (!empty($trend)) {
                                foreach ($trend as $v) {
                                    $browse_num[] = $v['un'];
                                }
                            }
                            echo json_encode($browse_num);
                            ?>
                        }
                    ]
                };
            }

            myChart.setOption(option);
        });
    });
</script>