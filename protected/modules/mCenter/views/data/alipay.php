<body>
    <div class="kkfm_r_inner">
        <div class="storeManage">
            <div class="top">
                <div class="name">
                    支付宝服务窗菜单统计
                </div>
            </div>
            <div class="bus-contant finance_count_wrap marketing_act">
                <div class="btn_l level_r">
                    <div class="btn_new_category">
                        <input type="button" value="菜单点击次数" class="btn_com_blue">
                        <input type="button" value="菜单点击人数" class="btn_com_gray">
                        <input type="button" value="人均点击次数" class="btn_com_gray">
                    </div>
                </div>
                <div class="act_form">
                    <div class="act_form_top">
                        <div class="time service_menu">
                            <div class="btn_l level_r">
                                <span>时间</span>
                                <input type="button" value="7日" class="btn_com_blue">
                                <input type="button" value="14日" class="btn_com_gray">
                                <input type="button" value="30日" class="btn_com_gray">
                                <select class="iselect">
                                    <option>2016.01.27.01版</option>
                                    <option>2016.01.27.01版</option>
                                    <option>2016.01.27.01版</option>
                                    <option>2016.01.27.01版</option>
                                    <option>2016.01.27.01版</option>
                                    <option>2016.01.27.01版</option>
                                </select>
                                <button class="btn_com_lightblue btn_com_search">查看</button>
                            </div>
                        </div>
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
                                    data: ['菜单名称1', '菜单名称2','菜单名称3']
                                },
                                grid: {
                                    left: '3%',
                                    right: '4%',
                                    bottom: '3%',
                                    containLabel: true
                                },
                                color: [
                                    '#fd6303', '#4990de','#ccc'
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
                                    name: '菜单名称1',
                                    type: 'line',
                                    stack: '总量',
                                    data: [120, 132, 101, 134, 90, 230, 210, 200]
                                }, {
                                    name: '菜单名称2',
                                    type: 'line',
                                    stack: '总量',
                                    data: [220, 182, 191, 234, 290, 330, 310, 100]
                                }, {
                                    name: '菜单名称3',
                                    type: 'line',
                                    stack: '总量',
                                    data: [200, 122, 171, 204, 250, 310, 390, 400]
                                }

                                ]

                            };
                            myChart.setOption(option);
                            </script>
                        </div>
                    </div>
                </div>
                <div class="act_form es_act_form">
                    <div class="act_form_top">
                        <div class="time service_menu">
                            <div class="btn_l level_r">
                                <input type="text" class="text_group text_data">
                            </div>
                        </div>
                    </div>
                </div>
                <table style="width:100%" class="service_menu_table new_member_table">
                        <thead>
                            <tr>
                                <th>版本</th>
                                <th>一级菜单</th>
                                <th>子菜单</th>
                                <th>菜单点击次数</th>
                                <th>菜单点击人数</th>
                                <th>人均点击次数</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td rowspan="3" class="es_td">2016.01.03.01版</td>
                                <td>一级菜单</td>
                                <td>-</td>
                                <td>16</td>
                                <td>16</td>
                                <td>16</td>
                            </tr>
                            <tr>
                                <td>一级菜单</td>
                                <td>-</td>
                                <td>16</td>
                                <td>16</td>
                                <td>16</td>
                            </tr>
                            <tr>
                                <td>一级菜单</td>
                                <td>-</td>
                                <td>16</td>
                                <td>16</td>
                                <td>16</td>
                            </tr>
                            <tr>
                                <td rowspan="6" class="es_td">2016.01.03.01版</td>
                                <td rowspan="3" class="es_td">一级菜单</td>
                                <td>-</td>
                                <td>16</td>
                                <td>16</td>
                                <td>16</td>
                            </tr>
                            <tr>
                               
                                <td>-</td>
                                <td>16</td>
                                <td>16</td>
                                <td>16</td>
                            </tr>
                            <tr>
                                
                                <td>-</td>
                                <td>16</td>
                                <td>16</td>
                                <td>16</td>
                            </tr>
                            <tr>
                                <td>一级菜单</td>
                                <td>-</td>
                                <td>16</td>
                                <td>16</td>
                                <td>16</td>
                            </tr>
                            <tr>
                                <td rowspan="2" class="es_td">一级菜单</td>
                                <td>-</td>
                                <td>16</td>
                                <td>16</td>
                                <td>16</td>
                            </tr>
                            <tr>
                                <td>-</td>
                                <td>16</td>
                                <td>16</td>
                                <td>16</td>
                            </tr>
                        </tbody>
                </table>
            </div>
</body>
