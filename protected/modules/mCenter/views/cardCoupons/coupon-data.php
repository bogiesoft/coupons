<h4 class="g-title bb-none">
    <a href="" class="text-gray"><i class="fa fa-chevron-circle-left"></i></a>
    <span>优惠券管理 / </span>
    <span>数据详表</span>
</h4>
<nav class="nav m-cp-nav">
    <span class="nav-link active" onclick="window.location.href='<?php echo Yii::app()->createUrl('mCenter/cardCoupons/CouponList') ?>'">优惠券管理</span>
    <span class="nav-link" onclick="window.location.href='<?php echo Yii::app()->createUrl('mCenter/cardCoupons/verification') ?>'">优惠券核销</span>
    <span class="nav-link" onclick="window.location.href='<?php echo Yii::app()->createUrl('mCenter/cardCoupons/CouponCount') ?>'">优惠券统计</span>
</nav>
<div class="g-container">
    <div class="panel panel-default m-panel">
        <div class="panel-heading">
            <h3 class="panel-title">优惠券分析</h3>
        </div>
        <div class="panel-body">
            <div class="m-filter form-inline">
                <div class="form-group">
                    <span class="pr10"><?php echo isset($coupon_info['title'])?$coupon_info['title']:'';?></span>
                </div>
                <!-- <div class="form-group">
                    <select class="form-control">
                        <option>-<?php echo isset($coupon_info['type'])?$coupon_info['type']:'';?>-</option>

                    </select>
                </div>-->
                <div class="form-group head-cp-data">
                    <span>浏览次数：<em><?php echo !empty($coupon_data['total_browse_num'])?$coupon_data['total_browse_num']:'0';?>次</em></span>
                    <span>领券次数：<em><?php echo !empty($coupon_data['total_receive_num'])?$coupon_data['total_receive_num']:'0';?>次</em></span>
                    <span>使用次数：<em><?php echo !empty($coupon_data['total_use_num'])?$coupon_data['total_use_num']:'0';?>次</em></span>
                    <span>核销率：<em>
                            <?php if(!empty($coupon_data['total_receive_num']) && !empty($coupon_data['total_use_num'])){?>
                                <?php echo round($coupon_data['total_use_num']/$coupon_data['total_receive_num'],2)*100?>
                            <?php }else{?>
                                <?php echo '0';?>
                            <?php }?>
                            %</em></span>
                </div>
            </div>
            <div id="coupon-chart-2" style="width: 50%;height:250px;margin-top: -20px;display: inline-block"></div>
            <script>
                var myChart = echarts.init(document.getElementById('coupon-chart-2'));
                option = {
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: {
                            type: 'shadow'
                        }
                    },
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        containLabel: true
                    },
                    xAxis: {
                        type: 'category',
                        splitLine:{
                            show:false
                        },
                        data: ['浏览','领取','使用']
                    },
                    yAxis: {
                        type: 'value',
                        splitLine:{
                            show:false
                        },
                        name: '次数'
                    },
                    series: [
                        {
                            name: '次数',
                            type: 'bar',
                            barWidth: 40,
                            itemStyle: {
                                normal:{
                                    color: function (params) {
                                        var colorList = ["#4990de","#5ecb7c","#f35450"];
                                        return colorList[params.dataIndex];
                                    }
                                }
                            },
                            data: [<?php echo !empty($coupon_data['total_browse_num'])?$coupon_data['total_browse_num']:'0';?>,
                                <?php echo !empty($coupon_data['total_receive_num'])?$coupon_data['total_receive_num']:'0';?>,
                                <?php echo !empty($coupon_data['total_use_num'])?$coupon_data['total_use_num']:'0';?>
                            ]
                        }
                    ]
                };
                myChart.setOption(option);
            </script>
            <div class="trapezoid-data">
                <div class="trapezoid-data-f">
                    <p>领取转化率：</p>
                    <span class="text-blue">
                    <?php if(!empty($coupon_data['total_receive_num']) && !empty($coupon_data['total_browse_num'])){?>
                        <?php echo round($coupon_data['total_receive_num']/$coupon_data['total_browse_num'],2)*100?>
                    <?php }else{?>
                        <?php echo '0';?>
                    <?php }?>
                        %</span>
                </div>
                <div class="trapezoid-data-s">
                    <p>使用转化率：</p>
                    <span class="text-green">
                    <?php if(!empty($coupon_data['total_receive_num']) && !empty($coupon_data['total_use_num'])){?>
                        <?php echo round($coupon_data['total_use_num']/$coupon_data['total_receive_num'],2)*100?>
                    <?php }else{?>
                        <?php echo '0';?>
                    <?php }?>
                        %</span>
                </div>
                <div class="trapezoid-data-t">
                    <p>使用浏览转化率：</p>
                    <span class="text-red">
                    <?php if(!empty($coupon_data['total_browse_num']) && !empty($coupon_data['total_use_num'])){?>
                        <?php echo round($coupon_data['total_use_num']/$coupon_data['total_browse_num'],2)*100?>
                    <?php }else{?>
                        <?php echo '0';?>
                    <?php }?>
                        %</span>
                </div>
            </div>
        </div>
    </div>
    <form action="<?php echo Yii::app()->createUrl('mCenter/cardCoupons/Coupondataone') ?>" id="addpro" method="get">
        <input type="hidden" name="code" value="<?php echo $code;?>">
        <div class="panel panel-default m-panel mt20">
            <div class="panel-heading">
                <h3 class="panel-title">趋势统计图</h3>
            </div>
            <div class="panel-body">
                <div class="m-filter form-inline">
                    <div class="form-group ml15" >
                        <select name="Coupon[type]" id="search_type" class="form-control">


                            <?php if (!isset($_GET['Coupon']['type'])) {?>
                                <option value="1"  <?php echo 'selected=selected';?>> 浏览人数/次数趋势  </option>
                                <option value="2"> 领取人数/次数趋势  </option>
                                <option value="3"> 使用人数/次数趋势  </option>
                            <?php }else { ?>
                                <?php foreach ($type_arr as $key => $v) { ?>
                                    <option value="<?php echo $key ?>"
                                        <?php if ($_GET['Coupon']['type'] == $key) {
                                            echo 'selected=selected';
                                        } ?>><?php echo $v ?>
                                    </option>
                                <?php } ?>
                            <?php }?>
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
                    <div class="form-group ml10">
                        <input type="text" name="Coupon[time]" id="data_time" class="form-control fc-sm" readonly="readonly"
                               value="<?php echo !empty($_GET['Coupon']['time']) ? $_GET['Coupon']['time'] : date('Y-m-d', strtotime('-7 days')) . ' - ' . date('Y-m-d', time() - 86400) ?>">
                    </div>
                </div>
                <div id="coupon-chart-1" style="width: 100%;height:400px;"></div>
                <script>
                    var myChart = echarts.init(document.getElementById('coupon-chart-1'));
                    option = {
                        tooltip: {
                            trigger: 'item',
                            formatter: '{a} <br/>{b} : {c}'
                        },
                        color: [
                            "#35ae3a","#4990de"
                        ],
                        legend: {
                            left: 'center',
                            <?php if (!isset($_GET['Coupon']['type'])) {?>
                            data: ['浏览人数','浏览次数']
                            <?php }else if($_GET['Coupon']['type'] == 1){ ?>
                            data: ['浏览人数','浏览次数']
                            <?php }else if($_GET['Coupon']['type'] == 2){ ?>
                            data: ['领取人数','领取次数']
                            <?php }else if($_GET['Coupon']['type'] == 3){ ?>
                            data: ['使用人数','使用次数']
                            <?php }?>

                        },
                        xAxis: {
                            type: 'category',
                            name: '',
                            splitLine: {show: false},
                            data: <?php echo json_encode(array_keys($coupon_data['trend_data'])) ?>
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
                                <?php if (!isset($_GET['Coupon']['type'])) {?>
                                name: '浏览人数',
                                <?php }else if($_GET['Coupon']['type'] == 1){ ?>
                                name: '浏览人数',
                                <?php }else if($_GET['Coupon']['type'] == 2){ ?>
                                name: '领取人数',
                                <?php }else if($_GET['Coupon']['type'] == 3){ ?>
                                name: '使用人数',
                                <?php }?>
                                type: 'line',
                                data: <?php foreach ($coupon_data['trend_data'] as $k=>$v) {
                                        $person_trend_arr[] = $v['new_browse_person_num'];
                            } echo json_encode($person_trend_arr);  ?>

                            },
                            {
                                <?php if (!isset($_GET['Coupon']['type'])) {?>
                                name: '浏览次数',
                                <?php }else if($_GET['Coupon']['type'] == 1){ ?>
                                name: '浏览次数',
                                <?php }else if($_GET['Coupon']['type'] == 2){ ?>
                                name: '领取次数',
                                <?php }else if($_GET['Coupon']['type'] == 3){ ?>
                                name: '使用次数',
                                <?php }?>
                                type: 'line',
                                data: <?php foreach ($coupon_data['trend_data'] as $k=>$v) {
                                        $num_trend_arr[] = $v['new_browse_num'];
                            } echo json_encode($num_trend_arr);  ?>
                            }
                        ]
                    };
                    myChart.setOption(option);
                </script>
            </div>
        </div>

        <div class="panel panel-default m-panel mt20">
            <div class="panel-heading">
                <h3 class="panel-title">消费分析<span class="panel-sub-title">券消费带来的订单、收入情况</span></h3>
            </div>
            <div class="panel-body">
                <div id="coupon-chart-3" style="width: 50%;height:250px;margin-top: -60px;display: inline-block;"></div>
                <script>
                    var myChart = echarts.init(document.getElementById('coupon-chart-3'));
                    option = {
                        tooltip : {
                            trigger: 'item',
                            formatter: "{a} <br/>{b} : {c} ({d}%)"
                        },
                        color:[
                            "#4990de","#5ecb7c","#f35450"
                        ],
                        series : [
                            {
                                name: '消费分析',
                                type: 'pie',
                                radius : '55%',
                                center: ['50%', '60%'],
                                data:[
                                    {value:<?php echo $coupon_data['total_discount_money']?>, name:'券抵用金额'},
                                    {value:<?php echo $coupon_data['total_cash_money']?>, name:'现金消费'},
                                    {value:<?php echo $coupon_data['total_notcash_money']?>, name:'非现金消费'}
                                ],
                                itemStyle: {
                                    emphasis: {
                                        shadowBlur: 10,
                                        shadowOffsetX: 0,
                                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                                    }
                                }
                            }
                        ]
                    };
                    myChart.setOption(option);
                </script>
                <ul class="consume-analyse-data">
                    <li>
                        <span class="title">用券订单：</span>
                        <span class="num"><?php echo $coupon_data['total_order_num'];?>笔</span>
                    </li>
                    <li>
                        <span class="title">消费金额：</span>
                        <span class="num"><?php echo $coupon_data['total_trade_money'];?>元</span>
                    </li>
                    <li>
                        <span class="title">券抵用金额：</span>
                        <span class="num"><?php echo $coupon_data['total_discount_money'];?>元</span>
                    </li>
                    <li>
                        <span class="title">现金消费：</span>
                        <span class="num"><?php echo $coupon_data['total_cash_money'];?>元</span>
                    </li>
                    <li>
                        <span class="title">非现金消费：</span>
                        <span class="num"><?php echo $coupon_data['total_notcash_money'];?>元</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="panel panel-default m-panel mt20">
            <div class="panel-heading">
                <h3 class="panel-title">历史数据详情</h3>
            </div>
            <div class="panel-body">
                <div class="m-filter form-inline">
                    <div class="form-group">
                        <span class="pl20">时间：</span>
                        <button type="button" class="btn <?php
                        if (empty($_GET['Coupon']['time2'])) {
                            echo 'btn-primary';
                        } else {
                            $times2 = explode(' - ', $_GET['Coupon']['time2']);
                            if ((strtotime(date('Y-m-d', time())) - strtotime($times2[0])) == 86400 * 7) {
                                echo 'btn-primary';
                            } else{
                                echo 'btn-default';
                            }
                        } ?> m-btn-sm" id="2days7">7日
                        </button>
                        <button type="button" class="btn <?php
                        if (!empty($_GET['Coupon']['time2'])) {
                            if ((strtotime(date('Y-m-d', time())) - strtotime($times2[0])) == 86400 * 15) {
                                echo 'btn-primary';
                            } else {
                                echo 'btn-default';
                            }
                        } else {
                            echo 'btn-default';
                        } ?> m-btn-sm ml10" id="2days15">15日
                        </button>
                        <button type="button" class="btn <?php
                        if (!empty($_GET['Coupon']['time2'])) {
                            if ((strtotime(date('Y-m-d', time())) - strtotime($times2[0])) == 86400 * 30) {
                                echo 'btn-primary';
                            } else {
                                echo 'btn-default';
                            }
                        } else {
                            echo 'btn-default';
                        } ?> m-btn-sm ml10" id="2days30">30日
                        </button>
                    </div>

                    <div class="form-group ml10">
                        <input type="text" name="Coupon[time2]" id="data_time_2" class="form-control fc-sm" readonly="readonly"
                               value="<?php echo !empty($_GET['Coupon']['time2']) ? $_GET['Coupon']['time2'] : date('Y-m-d', strtotime('-7 days')) . ' - ' . date('Y-m-d', time() - 86400) ?>">
                    </div>
                </div>
                <table class="table g-table g-table-center">
                    <thead>
                    <tr>
                        <th>时间</th>
                        <th>浏览人数</th>
                        <th>浏览次数</th>
                        <th>领取人数</th>
                        <th>领取次数</th>
                        <th>核销人数</th>
                        <th>核销次数</th>
                        <th>核销率</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($coupon_data['history_data'])){?>
                        <?php foreach ($coupon_data['history_data'] as $k=> $v){?>
                            <tr>
                                <td><?php echo date('Y.m.d', strtotime($v['date']))?></td>
                                <td><?php echo isset($v['new_browse_person_num'])?$v['new_browse_person_num']:''?></td>
                                <td><?php echo $v['new_browse_num']?></td>
                                <td><?php echo isset($v['new_receive_person_num'])?$v['new_receive_person_num']:''?></td>
                                <td><?php echo $v['new_receive_num']?></td>
                                <td><?php echo isset($v['new_consume_person_num'])?$v['new_consume_person_num']:''?></td>
                                <td><?php echo $v['new_use_num']?></td>
                                <td><?php echo $v['new_use_num']!==0 && $v['new_receive_num']!= 0?round($v['new_use_num']/$v['new_receive_num'],2)*100:'0'?>%</td>
                            </tr>
                        <?php }?>
                    <?php }?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 分页开始 -->
        <div class="g-pager">
            <div class="fl">可在当日上午8点后，在报表中查看前一日数据</div>
            <div class="fr">
            <span>共<strong class="total-num">
                    <?php echo $pages->itemCount ?>
                </strong>条
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
            </span>

            </div>
        </div>
        <!-- 分页结束 -->
    </form>
</div>
<script>
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
            opens : 'left',
            maxDate: '<?php echo date('Y-m-d', time() - 86400);?>',
            startDate: '<?php echo !empty($_GET['Coupon']['time']) ? $times[0] : date('Y-m-d', strtotime('-7 days')) ?>',
            endDate: '<?php echo !empty($_GET['Coupon']['time']) ? $times[1] : date('Y-m-d', time() - 86400);?>'
        });

        $('.btn-success').click(function () {
            var start_time1 = $(this).parent().find('input[name=daterangepicker_start]').val();
            var end_time1 = $(this).parent().find('input[name=daterangepicker_end]').val();

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

    //快捷日期选择2
    $('#2days7').click(function () {
        $(this).removeClass('btn-default');
        $(this).addClass('btn-primary');
        $('#2days15').removeClass('btn-primary');
        $('#2days15').addClass('btn-default');
        $('#2days30').removeClass('btn-primary');
        $('#2days30').addClass('btn-default');

        var time = '<?php echo date('Y-m-d', strtotime('-7 days')) ?>' + ' - ' + '<?php echo date('Y-m-d', time() - 86400) ?>';
        $('#data_time_2').val(time);
        $('#data_time_2').daterangepicker({
            timePicker: false,
            format: 'YYYY-MM-DD',
            dateLimit: true,
            maxDate: '<?php echo date('Y-m-d', time() - 86400) ?>',
            startDate: '<?php echo date('Y-m-d', strtotime('-7 days')) ?>',
            endDate: '<?php echo date('Y-m-d', time() - 86400) ?>'
        });

        $('#addpro').submit();
    });

    $('#2days15').click(function () {
        $(this).removeClass('btn-default');
        $(this).addClass('btn-primary');
        $('#2days7').removeClass('btn-primary');
        $('#2days7').addClass('btn-default');
        $('#2days30').removeClass('btn-primary');
        $('#2days30').addClass('btn-default');

        var time = '<?php echo date('Y-m-d', strtotime('-15 days')) ?>' + ' - ' + '<?php echo date('Y-m-d', time() - 86400) ?>';
        $('#data_time_2').val(time);
        $('#data_time_2').daterangepicker({
            timePicker: false,
            format: 'YYYY-MM-DD',
            dateLimit: true,
            maxDate: '<?php echo date('Y-m-d', time() - 86400) ?>',
            startDate: '<?php echo date('Y-m-d', strtotime('-15 days')) ?>',
            endDate: '<?php echo date('Y-m-d', time() - 86400) ?>'
        });

        $('#addpro').submit();
    });

    $('#2days30').click(function () {
        $(this).removeClass('btn-default');
        $(this).addClass('btn-primary');
        $('#2days15').removeClass('btn-primary');
        $('#2days15').addClass('btn-default');
        $('#2days7').removeClass('btn-primary');
        $('#2days7').addClass('btn-default');

        var time = '<?php echo date('Y-m-d', strtotime('-30 days')) ?>' + ' - ' + '<?php echo date('Y-m-d', time() - 86400) ?>';
        $('#data_time_2').val(time);
        $('#data_time_2').daterangepicker({
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
        $('#data_time_2').daterangepicker({
            timePicker: false,
            format: 'YYYY-MM-DD',
            dateLimit: true,
            opens : 'left',
            maxDate: '<?php echo date('Y-m-d', time() - 86400);?>',
            startDate: '<?php echo !empty($_GET['Coupon']['time2']) ? $times2[0] : date('Y-m-d', strtotime('-7 days')) ?>',
            endDate: '<?php echo !empty($_GET['Coupon']['time2']) ? $times2[1] : date('Y-m-d', time() - 86400);?>'
        });

        $('.btn-success').click(function () {
            var start_time1 = $(this).parent().find('input[name=daterangepicker_start]').val();
            var end_time1 = $(this).parent().find('input[name=daterangepicker_end]').val();

            var start_time2 = Date.parse(new Date(start_time1 + ' 00:00:00'));
            var start_time3 = start_time2 / 1000;

            var end_time2 = Date.parse(new Date(end_time1 + ' 00:00:00'));
            var end_time3 = end_time2 / 1000;

            var myDate = new Date();
            var now = Date.parse(new Date(myDate.toLocaleDateString() + ' 00:00:00'));
            now = now / 1000;

            if (now - start_time3 == 7 * 86400) {
                $('#2days7').removeClass('btn-default');
                $('#2days7').addClass('btn-primary');

                $('#2days15').removeClass('btn-primary');
                $('#2days15').addClass('btn-default');
                $('#2days30').removeClass('btn-primary');
                $('#2days30').addClass('btn-default');

                $('#data_time_2').val(start_time1 + ' - ' + end_time1);
                $('#addpro').submit();
            } else if (now - start_time3 == 15 * 86400) {
                $('#2days15').removeClass('btn-default');
                $('#2days15').addClass('btn-primary');

                $('#2days7').removeClass('btn-primary');
                $('#2days7').addClass('btn-default');
                $('#2days30').removeClass('btn-primary');
                $('#2days30').addClass('btn-default');

                $('#data_time_2').val(start_time1 + ' - ' + end_time1);
                $('#addpro').submit();
            } else if (now - start_time3 == 30 * 86400) {
                $('#2days30').removeClass('btn-default');
                $('#2days30').addClass('btn-primary');

                $('#2days7').removeClass('btn-primary');
                $('#2days7').addClass('btn-default');
                $('#2days15').removeClass('btn-primary');
                $('#2days15').addClass('btn-default');

                $('#data_time_2').val(start_time1 + ' - ' + end_time1);
                $('#addpro').submit();
            } else {
                $('#2days7').removeClass('btn-primary');
                $('#2days7').addClass('btn-default');
                $('#2days15').removeClass('btn-primary');
                $('#2days15').addClass('btn-default');
                $('#2days30').removeClass('btn-primary');
                $('#2days30').addClass('btn-default');

                $('#data_time_2').val(start_time1 + ' - ' + end_time1);
                $('#addpro').submit();
            }
        });
    });

    $('#data_time_2').click(function(){
        window.parent.callParAutoResize("main",$("body").height());
    });

    //趋势统计图的类型
    $('#search_type').change(function() {
        $('#addpro').submit();
    });
</script>