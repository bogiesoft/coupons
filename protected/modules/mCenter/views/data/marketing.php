<body>
    <div class="kkfm_r_inner">
        <div class="storeManage">
            <div class="top">
                <div class="name">
                    营销统计
                </div>
            </div>
            <div class="bus-contant finance_count_wrap marketing_count_wrap">
                <div class="act_form">
                    <div class="act_form_top">
                        <h4>优惠券统计</h4>
                    </div>
                    <div class="act_form_con">
                        <div class="data_select">
                            <select class="iselect">
                                <option>月份</option>
                                <option>月份</option>
                                <option>月份</option>
                                <option>月份</option>
                                <option>月份</option>
                                <option>月份</option>
                            </select>
                             <select class="iselect">
                                <option>月份</option>
                                <option>月份</option>
                                <option>月份</option>
                                <option>月份</option>
                                <option>月份</option>
                                <option>月份</option>
                            </select>
                        </div>
                        <div class="user_detail_item clearfix">
                            <div class="user_source analyze coupon_type">
                                <div class="finance_count clearfix">
                                    <ul>
                                        <li class="clearfix">
                                            <div class="finance_count_item">发券次数<em>27</em></div>
                                            <div class="finance_count_item">发券张数<em>2700</em></div>
                                            <div class="finance_count_item">领取数<em>2700</em></div>
                                        </li>
                                    </ul>
                                </div>
                                <div id="user_source_img" style="width: 400px;height:400px" ;>
                                </div>
                                <script type="text/javascript">
                                var myChart = echarts.init(document.getElementById('user_source_img'));
                                option = {
                                    title: {
                                        text: '发券类型',
                                        x: 'center'
                                    },
                                    color: [
                                        '#fd6303', '#01aaef', '#ccc'
                                    ],
                                    tooltip: {
                                        trigger: 'item',
                                        formatter: "{a} <br/>{b} : {c} ({d}%)"
                                    },
                                    legend: {
                                        orient: 'vertical',
                                        left: 'left',
                                        data: ['代金券', '折扣券', '兑换券']
                                    },
                                    series: [{
                                        name: '访问来源',
                                        type: 'pie',
                                        radius: '55%',
                                        center: ['50%', '60%'],
                                        data: [{
                                            value: 335,
                                            name: '代金券'
                                        }, {
                                            value: 310,
                                            name: '折扣券'
                                        }, {
                                            value: 234,
                                            name: '兑换券'
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
                            <div class="user_age analyze coupon_type">
                                <div class="finance_count clearfix">
                                    <ul>
                                        <li class="clearfix">
                                            <div class="finance_count_item">储值活动数<em>27</em></div>
                                            <div class="finance_count_item">核销率<em>2700</em></div>
                                            <div class="finance_count_item">带动收益<em>2700</em></div>
                                        </li>
                                    </ul>
                                </div>
                                <div id="user_age_img" style="width: 500px;height:400px" ;>
                                </div>
                                <script type="text/javascript">
                                var myChart = echarts.init(document.getElementById('user_age_img'));
                                option = {
                                    title: {
                                        text: '用户年龄段分析(百分比)',
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
                                        data: ['0-18岁', '18-30岁', '30-50岁', '50-100岁']
                                    }],
                                    yAxis: [{
                                        type: 'value'
                                    }],
                                    series: [{
                                        name: '百分比',
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
                                        data: [10, 40, 30, 10, ]
                                    }]
                                };

                                myChart.setOption(option);
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="act_form">
                    <div class="act_form_top">
                        <h4>储值统计</h4>
                    </div>
                    <div class="act_form_con">
                         <div class="data_select">
                            <select class="iselect">
                                <option>月份</option>
                                <option>月份</option>
                                <option>月份</option>
                                <option>月份</option>
                                <option>月份</option>
                                <option>月份</option>
                            </select>
                             <select class="iselect">
                                <option>月份</option>
                                <option>月份</option>
                                <option>月份</option>
                                <option>月份</option>
                                <option>月份</option>
                                <option>月份</option>
                            </select>
                        </div>
                        <div class="finance_count clearfix">
                                    <ul>
                                        <li class="clearfix">
                                            <div class="finance_count_item">储值活动数<em>27</em></div>
                                        </li>
                                        <li class="clearfix">
                                            <div class="finance_count_item">储值次数<em>27</em></div>
                                        </li>
                                        <li class="clearfix">
                                            <div class="finance_count_item">储值人数<em>27</em></div>
                                        </li>
                                        <li class="clearfix">
                                            <div class="finance_count_item">储值金额<em>27</em></div>
                                        </li>
                                    </ul>
                                </div>
                    </div>
                </div>
                <div class="act_form">
                    <div class="act_form_top">
                        <h4>储值统计</h4>
                    </div>
                    <div class="act_form_con">
                        <div class="data_select">
                            <select class="iselect">
                                <option>月份</option>
                                <option>月份</option>
                                <option>月份</option>
                                <option>月份</option>
                                <option>月份</option>
                                <option>月份</option>
                            </select>
                             <select class="iselect">
                                <option>月份</option>
                                <option>月份</option>
                                <option>月份</option>
                                <option>月份</option>
                                <option>月份</option>
                                <option>月份</option>
                            </select>
                        </div>
                        <div class="finance_count clearfix">
                                    <ul>
                                        <li>
                                            <div class="finance_count_item">储值活动数<em>27</em></div>
                                        </li>
                                        <li>
                                            <div class="finance_count_item">参与人数<em>27</em></div>
                                        </li>
                                        <li>
                                            <div class="finance_count_item">带动消费<em>27</em></div>
                                        </li>
                                        <li>
                                            <div class="finance_count_item">储值金额<em>27</em></div>
                                        </li>
                                    </ul>
                                </div>
                                <div id="stored_value_count_img" style="width: 400px;height:400px" ;>
                                </div>
                                <script type="text/javascript">
                                var myChart = echarts.init(document.getElementById('stored_value_count_img'));
                                option = {
                                    title: {
                                        text: '活动类型',
                                        x: 'center'
                                    },
                                    color: [
                                        '#fd6303', '#01aaef' ,'#89ddff','#ccc'
                                    ],
                                    tooltip: {
                                        trigger: 'item',
                                        formatter: "{a} <br/>{b} : {c} ({d}%)"
                                    },
                                    legend: {
                                        orient: 'vertical',
                                        left: 'left',
                                        data: ['发券', '储值', '商品','其他']
                                    },
                                    series: [{
                                        name: '访问来源',
                                        type: 'pie',
                                        radius: '55%',
                                        center: ['50%', '60%'],
                                        data: [{
                                            value: 335,
                                            name: '发券'
                                        }, {
                                            value: 310,
                                            name: '储值'
                                        }, {
                                            value: 234,
                                            name: '商品'
                                        }, {
                                            value: 234,
                                            name: '其他'
                                        }, 

                                        ],
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
</body>