<body>
    <div class="kkfm_r_inner">
        <div class="storeManage">
            <div class="top">
                <div class="name">
                    图文统计
                </div>
            </div>
            <div class="bus-contant finance_count_wrap marketing_act">
                <div class="data_select">
                    <select class="iselect">
                        <option>图文名称</option>
                        <option>图文名称</option>
                        <option>图文名称</option>
                        <option>图文名称</option>
                        <option>图文名称</option>
                        <option>图文名称</option>
                    </select>
                    <span class="count_time">统计时间：2016.01.27-2016.02.04</span>
                </div>
                <div class="finance_count clearfix">
                    <ul>
                        <li>
                            <div class="finance_count_item">
                                <div class="count_title">图文送达人数</div>
                                <div>微信公众号<em>2121</em></div>
                                <div>支付宝服务窗<em>2121</em></div>
                            </div>
                        </li>
                        <li>
                            <div class="finance_count_item">
                                <div class="count_title">图文阅读人数</div>
                                <div>微信公众号<em>2121</em></div>
                                <div>支付宝服务窗<em>2121</em></div>
                            </div>
                        </li>
                        <li>
                            <div class="finance_count_item">
                                <div class="count_title">图文阅读次数</div>
                                <div>微信公众号<em>2121</em></div>
                                <div>支付宝服务窗<em>2121</em></div>
                            </div>
                        </li>
                        <li>
                            <div class="finance_count_item">
                                <div class="count_title">图文转化率</div>
                                <div>微信公众号<em>20%</em></div>
                                <div>支付宝服务窗<em>10%</em></div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="act_form">
                    <div class="act_form_top">
                        <h4>阅读人数统计</h4>
                    </div>
                    <div class="act_form_con">
                        <div id="read_person_img" class="" style="width: 800px;height:400px;">
                            <script type="text/javascript">
                            var myChart = echarts.init(document.getElementById('read_person_img'));
                            option = {
                                tooltip: {
                                    trigger: 'axis'
                                },
                                legend: {
                                    data: ['支付宝阅读人数', '微信阅读人数']
                                },
                                grid: {
                                    left: '3%',
                                    right: '4%',
                                    bottom: '3%',
                                    containLabel: true
                                },
                                color: [
                                    '#4990de', '#3daf36'
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
                                    name: '支付宝阅读人数',
                                    type: 'line',
                                    stack: '总量',
                                    data: [120, 132, 101, 134, 90, 230, 210, 500]
                                }, {
                                    name: '微信阅读人数',
                                    type: 'line',
                                    stack: '总量',
                                    data: [220, 182, 191, 234, 290, 330, 310, 500]
                                }]

                            };
                            myChart.setOption(option);
                            </script>
                        </div>
                    </div>
                </div>
                <div class="act_form">
                    <div class="act_form_top">
                        <h4>阅读次数统计</h4>
                    </div>
                    <div class="act_form_con">
                        <div id="read_num_img" class="" style="width: 800px;height:400px;">
                            <script type="text/javascript">
                            var myChart = echarts.init(document.getElementById('read_num_img'));
                            option = {
                                tooltip: {
                                    trigger: 'axis'
                                },
                                legend: {
                                    data: ['支付宝阅读次数', '微信阅读次数']
                                },
                                grid: {
                                    left: '3%',
                                    right: '4%',
                                    bottom: '3%',
                                    containLabel: true
                                },
                                color: [
                                    '#4990de', '#3daf36'
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
                                    name: '支付宝阅读次数',
                                    type: 'line',
                                    stack: '总量',
                                    data: [120, 132, 101, 134, 90, 230, 210, 500]
                                }, {
                                    name: '微信阅读次数',
                                    type: 'line',
                                    stack: '总量',
                                    data: [220, 182, 191, 234, 290, 330, 310, 500]
                                }]

                            };
                            myChart.setOption(option);
                            </script>
                        </div>
                    </div>
                </div>
            </div>
</body>
