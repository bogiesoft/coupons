<body>
    <div class="kkfm_r_inner">
        <div class="storeManage">
            <div class="top">
                <div class="name">
                    订单统计
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
                    <input type="text" class="text_data text_group">
                    <input type="text" class="text_data text_group">
                    <button class="btn_com_lightblue btn_com_search">查看</button>
                </div>
                <div class="act_form">
                    <div class="act_form_top">
                        <h4>订单数统计</h4>
                    </div>
                    <div class="act_form_con">
                        <div id="order_num_img" class="" style="width: 800px;height:400px;">
                            <script type="text/javascript">
                            var myChart = echarts.init(document.getElementById('order_num_img'));
                            option = {
                                tooltip: {
                                    trigger: 'axis'
                                },
                                legend: {
                                    data: ['', '']
                                },
                                grid: {
                                    left: '3%',
                                    right: '4%',
                                    bottom: '3%',
                                    containLabel: true
                                },
                                color: [
                                    '#fd6303', '#4990de'
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
                                    name: '数量'
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
                        <h4>订单金额统计</h4>
                    </div>
                    <div class="act_form_con">
                        <div id="order_money_img" class="" style="width: 800px;height:400px;">
                            <script type="text/javascript">
                            var myChart = echarts.init(document.getElementById('order_money_img'));
                            option = {
                                tooltip: {
                                    trigger: 'axis'
                                },
                                legend: {
                                    data: ['', '']
                                },
                                grid: {
                                    left: '3%',
                                    right: '4%',
                                    bottom: '3%',
                                    containLabel: true
                                },
                                color: [
                                    '#fd6303', '#4990de'
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
                                    name: '金额'
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
                        <h4>订单客单价统计</h4>
                    </div>
                    <div class="act_form_con">
                        <div id="order_perprice_img" class="" style="width: 800px;height:400px;">
                            <script type="text/javascript">
                            var myChart = echarts.init(document.getElementById('order_perprice_img'));
                            option = {
                                tooltip: {
                                    trigger: 'axis'
                                },
                                legend: {
                                    data: ['', '']
                                },
                                grid: {
                                    left: '3%',
                                    right: '4%',
                                    bottom: '3%',
                                    containLabel: true
                                },
                                color: [
                                    '#fd6303', '#4990de'
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
                                    name: '金额'
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
            </div>
</body>
