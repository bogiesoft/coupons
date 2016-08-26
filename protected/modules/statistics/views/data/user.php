<script type="text/javascript" src="<?php echo GJ_STATIC_JS?>echarts.common.min.js"></script>
<body>
    <div class="kkfm_r_inner">
        <div class="storeManage">
            <div class="top">
                <div class="name">
                    用户统计
                </div>
            </div>
            <div class="bus-contant finance_count_wrap">
                <div class="clearfix user_count">
                    <ul class="clearfix">
                        <li>
                            <div class="user_count_wrap clearfix">
                                <div class="user_count_item user_count_item_first">
                                    <span class="count_title">累计用户</span>
                                    <div class="user_count_item_li"><span>累计微信粉丝</span><em><?php echo $head -> total_wechat_fans?></em></div>
                                    <div class="user_count_item_li"><span>累计支付宝粉丝</span><em><?php echo $head -> total_alipay_fans?></em></div>
                                </div>
                                <div class="user_count_item">累计会员数
                                    <em><?php echo $head -> total_member_num?></em>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="user_count_wrap clearfix">
                                <div class="user_count_item user_count_item_first">
                                    <span class="count_title">今日新增用户</span>
                                    <div class="user_count_item_li"><span>新增微信粉丝</span><em><?php echo $head -> wechat_fans_today?></em></div>
                                    <div class="user_count_item_li"><span>新增支付宝粉丝</span><em><?php echo $head -> alipay_fans_today?></em></div>
                                </div>
                                <div class="user_count_item">新增会员数
                                    <em><?php echo $head -> member_num_today?></em>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="user_count_wrap clearfix">
                                <div class="user_count_item">今日到店消费人数
                                    <em><?php echo $head -> purchase_user_num?></em>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="act_form" style="display:none">
                    <div class="act_form_top">
                        <h4>新增用户分析</h4>
                    </div>
                    <div class="act_form_con trading_results_con">
                        <div class="time">
                            <div class="btn_l level_r">
                                <span>时间</span>
                                <input type="button" value="7日" class="btn_com_blue">
                                <input type="button" value="14日" class="btn_com_gray">
                                <input type="button" value="30日" class="btn_com_gray">
                                <input type="button" value="2015.11.08-2015.11.15" class="btn_com_gray">
                                <select class="iselect data_user_select">
                                    <option>用户来源</option>
                                    <option>用户来源</option>
                                    <option>用户来源</option>
                                    <option>用户来源</option>
                                    <option>用户来源</option>
                                </select>
                                <button class="btn_com_lightblue btn_com_search">搜索</button>
                            </div>
                        </div>
                        <div id="new_user_line_img" style="width: 800px;height:400px;">
                            <script type="text/javascript">
                            var myChart = echarts.init(document.getElementById('new_user_line_img'));
                            option = {
                                tooltip: {
                                    trigger: 'axis'
                                },
                                legend: {
                                    data: ['微信粉丝', '支付宝粉丝', '会员']
                                },
                                grid: {
                                    left: '3%',
                                    right: '4%',
                                    bottom: '3%',
                                    containLabel: true
                                },
                                color: [
                                    '#fd6303', '#4990de', '#3cb035'
                                ],
                                toolbox: {
                                    feature: {

                                    }
                                },
                                xAxis: [{
                                    type: 'category',
                                    name: '时间',
                                    nameLocation: 'middle',
                                    boundaryGap: false,
                                    data: ['0', '12.21', '12.22', '12.23', '12.24', '12.25', '12.26', '12.27']
                                }],
                                yAxis: [{
                                    type: 'value',
                                    name: '金额/数量'
                                }],
                                series: [{
                                    name: '微信粉丝',
                                    type: 'line',
                                    stack: '总量',
                                    data: [120, 132, 101, 134, 90, 230, 210, 500]
                                }, {
                                    name: '支付宝粉丝',
                                    type: 'line',
                                    stack: '总量',
                                    data: [120, 182, 191, 234, 290, 330, 310, 500]
                                }, {
                                    name: '会员',
                                    type: 'line',
                                    stack: '总量',
                                    data: [320, 152, 391, 534, 190, 530, 810, 200]
                                }]

                            };
                            myChart.setOption(option);
                            </script>
                        </div>
                    </div>
                </div>
                
                
                <div class="act_form">
                    <div class="act_form_top">
                        <h4>用户分析</h4>
                    </div>
                    <div class="act_form_con">
                        <div class="user_detail_item clearfix">
                            <div class="user_source analyze">
                                <div id="user_source_img" style="width: 400px;height:400px" ;>
                                </div>
                                <script type="text/javascript">
                                var myChart = echarts.init(document.getElementById('user_source_img'));
                                option = {
                                    title: {
                                        text: '用户来源分析',
                                        x: 'center'
                                    },
                                    color: [
                                        '#3cb035', '#4990de', '#ccc'
                                    ],
                                    tooltip: {
                                        trigger: 'item',
                                        formatter: "{a} <br/>{b} : {c} ({d}%)"
                                    },
                                    legend: {
                                        orient: 'vertical',
                                        left: 'left',
                                        data: ['微信', '支付宝', '未知']
                                    },
                                    series: [{
                                        name: '用户来源',
                                        type: 'pie',
                                        radius: '55%',
                                        center: ['50%', '60%'],
                                        data: [{
                                            value: <?php echo $user_from_sex -> total_wechat_from?>,
                                            name: '微信'
                                        }, {
                                            value: <?php echo $user_from_sex -> total_alipay_from?>,
                                            name: '支付宝'
                                        }, {
                                            value: <?php echo $user_from_sex -> total_unknown_from?>,
                                            name: '未知'
                                        }, ],
                                        itemStyle: {
                                            emphasis: {
                                                shadowBlur: 10,
                                                shadowOffsetX: 0,
                                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                                            }
                                        }
                                    }]
                                };
                                myChart.setOption(option);
                                </script>
                            </div>
                            <div class="user_sex analyze">
                                <div id="user_sex_img" style="width: 400px;height:400px" ;>
                                </div>
                                <script type="text/javascript">
                                var myChart = echarts.init(document.getElementById('user_sex_img'));
                                option = {
                                    title: {
                                        text: '用户性别分析',
                                        x: 'center'
                                    },
                                    color: [
                                        '#fd6303', '#4990de', '#ccc'
                                    ],
                                    tooltip: {
                                        trigger: 'item',
                                        formatter: "{a} <br/>{b} : {c} ({d}%)"
                                    },
                                    legend: {
                                        orient: 'vertical',
                                        left: 'left',
                                        data: ['女性', '男性', '未知']
                                    },
                                    series: [{
                                        name: '用户性别',
                                        type: 'pie',
                                        radius: '55%',
                                        center: ['50%', '60%'],
                                        data: [{
                                            value: <?php echo $user_from_sex -> total_female_num?>,
                                            name: '女性'
                                        }, {
                                            value: <?php echo $user_from_sex -> total_male_num?>,
                                            name: '男性'
                                        }, {
                                            value: <?php echo $user_from_sex -> total_unknown_sex_num?>,
                                            name: '未知'
                                        }, ],
                                        itemStyle: {
                                            emphasis: {
                                                shadowBlur: 10,
                                                shadowOffsetX: 0,
                                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                                            }
                                        }
                                    }]
                                };
                                myChart.setOption(option);
                                </script>
                            </div>
                            <div class="user_age analyze">
                                <div id="user_age_img" style="width: 800px;height:400px" ;>
                                </div>
                                <script type="text/javascript">
                                var myChart = echarts.init(document.getElementById('user_age_img'));
                                option = {
                                    title: {
                                        text: '用户年龄段分析',
                                    },
                                    color: [
                                        '#fd6303', '#4990de'
                                    ],
                                    tooltip: {
                                        trigger: 'axis',
                                        axisPointer: { // 坐标轴指示器，坐标轴触发有效
                                            type: 'shadow' // 默认为直线，可选为：'line' | 'shadow'
                                        },
                                        formatter: function(params) {
                                            var tar = params[0];
                                            return tar.name + '<br/>' + tar.seriesName + ' : ' + tar.value;
                                        }
                                    },
                                    grid: {
                                        left: '3%',
                                        right: '4%',
                                        bottom: '3%',
                                        containLabel: true
                                    },
                                    xAxis: [{
                                        type: 'category',
                                        splitLine: {
                                            show: false
                                        },
                                        data: ['0-18岁', '18-30岁', '30-50岁', '50以上','未知']
                                    }],
                                    yAxis: [{
                                        type: 'value'
                                    }],
                                    series: [{
                                        name: '人数',
                                        type: 'bar',
                                        stack: '总量',
                                        itemStyle: {
                                            normal: {
                                                label: {
                                                    show: true,
                                                    position: 'inside'
                                                }
                                            }
                                        },
                                        data: [<?php echo $user_age -> num_0_18?>, <?php echo $user_age -> num_18_30?>, <?php echo $user_age -> num_30_50?>, <?php echo $user_age -> num_50m?>, <?php echo $user_age -> num_unknown?>]
                                    }]
                                };

                                myChart.setOption(option);
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
                
                
                <div class="act_form" style="display:none">
                    <div class="act_form_top">
                        <h4>新增用户分析</h4>
                    </div>
                    <div class="act_form_con">
                        <div class="user_detail_item clearfix">
                            <div class="consume_frequency analyze">
                                <div id="consume_frequency_img" style="width: 400px;height:400px" ;>
                                </div>
                                <ul>
                                    <li>无消费：0次/季度</li>
                                    <li>散客：0次/季度
                                       	＜散客≤1次/季度</li>
                                            <li>常客：1次/季度
                                                	＜常客</li>
                                                    <li><a href="">自定义参数</a></li>
                                </ul>
                                <script type="text/javascript">
                                var myChart = echarts.init(document.getElementById('consume_frequency_img'));
                                option = {
                                    title: {
                                        text: '消费频次分析',
                                        x: 'center'
                                    },
                                    color: [
                                        '#fd6303', '#4990de', '#ccc'
                                    ],
                                    tooltip: {
                                        trigger: 'item',
                                        formatter: "{a} <br/>{b} : {c} ({d}%)"
                                    },
                                    legend: {
                                        orient: 'vertical',
                                        left: 'left',
                                        data: ['散客', '常客', '无消费']
                                    },
                                    series: [{
                                        name: '访问来源',
                                        type: 'pie',
                                        radius: '55%',
                                        center: ['50%', '60%'],
                                        data: [{
                                            value: 600,
                                            name: '散客'
                                        }, {
                                            value: 310,
                                            name: '常客'
                                        }, {
                                            value: 134,
                                            name: '无消费'
                                        }, ],
                                        itemStyle: {
                                            emphasis: {
                                                shadowBlur: 10,
                                                shadowOffsetX: 0,
                                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                                            }
                                        }
                                    }]
                                };
                                myChart.setOption(option);
                                </script>
                            </div>
                            <div class="consume_money analyze">
                                <div id="consume_money_img" style="width: 400px;height:400px" ;>
                                </div>
                                <ul>
                                    <li>无消费：0元/次</li>
                                    <li>低消费：0元/次
                                        	＜低消费≤100元/次</li>
                                            <li>高消费：100元/次
                                                	＜高消费</li>
                                                    <li><a href="">自定义参数</a></li>
                                </ul>
                                <script type="text/javascript">
                                var myChart = echarts.init(document.getElementById('consume_money_img'));
                                option = {
                                    title: {
                                        text: '消费金额分析',
                                        x: 'center'
                                    },
                                    color: [
                                        '#fd6303', '#4990de', '#ccc'
                                    ],
                                    tooltip: {
                                        trigger: 'item',
                                        formatter: "{a} <br/>{b} : {c} ({d}%)"
                                    },
                                    legend: {
                                        orient: 'vertical',
                                        left: 'left',
                                        data: ['低消费', '高消费', '无消费']
                                    },
                                    series: [{
                                        name: '访问来源',
                                        type: 'pie',
                                        radius: '55%',
                                        center: ['50%', '60%'],
                                        data: [{
                                            value: 800,
                                            name: '低消费'
                                        }, {
                                            value: 110,
                                            name: '高消费'
                                        }, {
                                            value: 34,
                                            name: '无消费'
                                        }, ],
                                        itemStyle: {
                                            emphasis: {
                                                shadowBlur: 10,
                                                shadowOffsetX: 0,
                                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                                            }
                                        }
                                    }]
                                };
                                myChart.setOption(option);
                                </script>
                            </div>
                            <div class="leave analyze">
                                <div id="leave_img" style="width: 400px;height:400px" ;>
                                </div>
                                <ul>
                                    <li>未消费：未消费时间
                                        <30天</li>
                                            <li>轻度流失：30天
                                               	 ＜未消费时间≤60天</li>
                                                    <li>重度流失：60天
                                                        	＜未消费时间</li>
                                                            <li><a href="">自定义参数</a></li>
                                </ul>
                                <script type="text/javascript">
                                var myChart = echarts.init(document.getElementById('leave_img'));
                                option = {
                                    title: {
                                        text: '流失情况分析',
                                        x: 'center'
                                    },
                                    color: [
                                        '#fd6303', '#8addff', '#ccc'
                                    ],
                                    tooltip: {
                                        trigger: 'item',
                                        formatter: "{a} <br/>{b} : {c} ({d}%)"
                                    },
                                    legend: {
                                        orient: 'vertical',
                                        left: 'left',
                                        data: ['未流失', '轻度流失', '重度流失']
                                    },
                                    series: [{
                                        name: '访问来源',
                                        type: 'pie',
                                        radius: '55%',
                                        center: ['50%', '60%'],
                                        data: [{
                                            value: 900,
                                            name: '未流失'
                                        }, {
                                            value: 310,
                                            name: '轻度流失'
                                        }, {
                                            value: 234,
                                            name: '重度流失'
                                        }, ],
                                        itemStyle: {
                                            emphasis: {
                                                shadowBlur: 10,
                                                shadowOffsetX: 0,
                                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                                            }
                                        }
                                    }]
                                };
                                myChart.setOption(option);
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>