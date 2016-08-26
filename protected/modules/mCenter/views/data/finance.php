<script type="text/javascript" src="<?php echo GJ_STATIC_JS?>echarts.common.min.js"></script>
<body>
    <div class="kkfm_r_inner">
        <div class="storeManage">
            <div class="top">
                <div class="name">财务统计</div>
            </div>
            <div class="bus-contant finance_count_wrap">
                <div class="finance_count clearfix">
                    <ul class="finance_count_inner">
                        <li class="clearfix">
                            <div class="finance_count_item">门店总数
                                <p><?php echo $store_count?></p></div>
                            <div class="finance_count_item es_item">今日交易总额
                                <p><?php echo number_format($total_money_count,2)?></p>
                            </div>
                        </li>
                        <li class="clearfix">
                            <div class="finance_count_item">今日支付宝交易总额
                                <p><?php echo number_format($alipay_money_count,2);?></p>
                            </div>
                            <div class="finance_count_item es_item">支付宝交易笔数
                                <p><?php echo $alipay_num_count?></p>
                            </div>
                        </li>
                        <li class="clearfix">
                            <div class="finance_count_item">今日微信交易总额
                                <p><?php echo number_format($wechat_money_count,2);?></p>
                            </div>
                            <div class="finance_count_item es_item">微信交易笔数
                                <p><?php echo $wechat_num_count?></p>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="act_form">
                    <div class="act_form_top">
                        <h4>交易趋势分析</h4>
                    </div>
                    <div class="act_form_con">
                        <div class="data_select">
                            <select class="iselect" id="management_first" style="width: 260px">
                                <option>-请选择-</option>
                                <?php if (!empty($management)) {
                                    if (!empty($self_arr)) {
                                        $management_arr = array_keys($self_arr);
                                        foreach ($management as $k => $v) {
                                            if (is_array($management_arr)) {
                                                if (in_array($v->id, $management_arr)) { ?>
                                                    <option
                                                        value="<?php echo $v->id . ',m' ?>"><?php echo $v->name . '(分组)' ?></option>
                                                <?php }
                                            }
                                        }
                                    } else {
                                        foreach ($management as $k => $v) { ?>
                                            <option value="<?php echo $v->id . ',m' ?>"><?php echo $v->name . '(分组)' ?></option>
                                        <?php }
                                    }
                                } ?>
                                <?php  if (!empty($store)) {
                                    if (!empty($self_arr)) {
                                        foreach ($self_arr as $v) {
                                            if (is_array($v['store_id'])) {
                                                foreach ($v['store_id'] as $a) {
                                                    $wqstore_arr[] = $a;
                                                }
                                            }
                                        }
                                        foreach ($store as $k => $v) {
                                            if (is_array($wqstore_arr)) {
                                                if (in_array($v->id, $wqstore_arr)) { ?>
                                                    <option
                                                        value="<?php echo $v->id . ',s' ?>"><?php echo $v->name . '(分店)' ?></option>
                                                <?php }
                                            }
                                        }
                                    } else {
                                        foreach ($store as $k => $v) { ?>
                                            <option value="<?php echo $v->id . ',s' ?>"><?php echo $v->name . '(分店)' ?></option>
                                        <?php }
                                    }
                                } ?>
                            </select>
                        </div>
                        <input name="store_id" style="display: none" id="store_id">
                        <div class="time">
                            <div class="btn_l level_r">
                                <span>时间</span>
                                <input type="button" value="7日" class="btn_com_blue" id="seven">
                                <input type="button" value="14日" class="btn_com_gray" id="fourteen">
                                <input type="button" value="30日" class="btn_com_gray" id="thirty">
                                <input type="text" value="<?php echo date('Y/m/d',strtotime('-7 days')).' - '.date('Y/m/d',strtotime('-1 days'))?>" class="text_data text_group" name="Time">
                                <button class="btn_com_lightblue btn_com_search" onclick="tradetrend()" id="tradetrend">查看</button>
                                <div class="btn_category">
                                    <input type="button" value="实收总额" class="btn_com_blue" id="trade_trend_money">
                                    <input type="button" value="交易订单数" class="btn_com_gray" id="trade_trend_num">
                                </div>
                            </div>
                        </div>
                        <div id="trading_trend_img_money" class="" style="width: 800px;height:400px;">
                            <script type="text/javascript">
                            var myChart_money = echarts.init(document.getElementById('trading_trend_img_money'));
                            option = {
                                tooltip: {
                                    trigger: 'axis'
                                },
                                legend: {
                                    data: ['全部', '支付宝', '微信']
                                },
                                grid: {
                                    left: '3%',
                                    right: '4%',
                                    bottom: '3%',
                                    containLabel: true
                                },
                                color: [
                                    '#fd6303', '#4990de','#2f9833'
                                ],
                                toolbox: {
                                    feature: {

                                    }
                                },
                                xAxis: [{
                                    type: 'category',
                                    nameLocation: 'middle',
                                    boundaryGap: false,
                                    data: [<?php $count=0;foreach ($trade_trend -> date as $k => $v){if($count == 0){echo "'".$v."'";$count++;}else{echo ','."'".$v."'";}}?>]
                                }],
                                yAxis: [{
                                    type: 'value',
                                    name: '金额'
                                }],
                                series: [{
                                    name: '全部',
                                    type: 'line',

                                    data: [<?php $count=0;foreach ($trade_trend -> total as $k => $v){if($count == 0){echo $v;$count++;}else{echo ','.$v;}}?>]
                                }, {
                                    name: '支付宝',
                                    type: 'line',
                
                                    data: [<?php $count=0;foreach ($trade_trend -> alipay as $k => $v){if($count == 0){echo $v;$count++;}else{echo ','.$v;}}?>]
                                }, {
                                    name: '微信',
                                    type: 'line',
                               
                                    data: [<?php $count=0;foreach ($trade_trend -> wechat as $k => $v){if($count == 0){echo $v;$count++;}else{echo ','.$v;}}?>]
                                }]

                            };
                            myChart_money.setOption(option);
                            </script>
                        </div>
                        
                        <div id="trading_trend_img_num" class="" style="width: 800px;height:400px;display:none">
                            <script type="text/javascript">
                            var myChart_num = echarts.init(document.getElementById('trading_trend_img_num'));
                            option = {
                                tooltip: {
                                    trigger: 'axis'
                                },
                                legend: {
                                    data: ['全部', '支付宝', '微信']
                                },
                                grid: {
                                    left: '3%',
                                    right: '4%',
                                    bottom: '3%',
                                    containLabel: true
                                },
                                color: [
                                    '#fd6303', '#4990de','#2f9833'
                                ],
                                toolbox: {
                                    feature: {

                                    }
                                },
                                xAxis: [{
                                    type: 'category',
                                    nameLocation: 'middle',
                                    boundaryGap: false,
                                    data: [<?php $count=0;foreach ($trade_trend -> date as $k => $v){if($count == 0){echo "'".$v."'";$count++;}else{echo ','."'".$v."'";}}?>]
                                }],
                                yAxis: [{
                                    type: 'value',
                                    name: '数量'
                                }],
                                series: [{
                                    name: '全部',
                                    type: 'line',
                                    data: [<?php $count=0;foreach ($trade_trend -> total_num as $k => $v){if($count == 0){echo $v;$count++;}else{echo ','.$v;}}?>]
                                }, {
                                    name: '支付宝',
                                    type: 'line',
                                    data: [<?php $count=0;foreach ($trade_trend -> alipay_num as $k => $v){if($count == 0){echo $v;$count++;}else{echo ','.$v;}}?>]
                                }, {
                                    name: '微信',
                                    type: 'line',
                                    data: [<?php $count=0;foreach ($trade_trend -> wechat_num as $k => $v){if($count == 0){echo $v;$count++;}else{echo ','.$v;}}?>]
                                }]

                            };
                            myChart_num.setOption(option);
                            </script>
                        </div>
                        
                    </div>
                </div>

                <div class="act_form">
                    <div class="act_form_top">
                        <h4>交易业绩分析top10</h4>
                    </div>
                    <div class="act_form_con trading_results_con">
                        <div class="time">
                            <div class="btn_l level_r">
                                <span>时间</span>
                                <input type="button" value="7日" class="btn_com_blue" id="seven2">
                                <input type="button" value="14日" class="btn_com_gray" id="fourteen2">
                                <input type="button" value="30日" class="btn_com_gray" id="thirty2">
                                <input type="text" class="text_group text_data" name="Time2" value="<?php echo date('Y/m/d',strtotime('-7 days')).' - '.date('Y/m/d',strtotime('-1 days'))?>">
                                <button class="btn_com_lightblue btn_com_search" onclick="tradeachievement()">查看</button>
                                <div class="btn_category">
                                    <input type="button" value="实收总额" class="btn_com_blue" id="trade_achievement_money">
                                    <input type="button" value="交易订单数" class="btn_com_gray" id="trade_achievement_num">
                                </div>
                            </div>
                        </div>
                        <div id="trading_results_money_img" style="width: 800px;height:600px;"></div>
                        <script type="text/javascript">
                        var myChart_result_money = echarts.init(document.getElementById('trading_results_money_img'));
                        option = {
                            tooltip: {
                                trigger: 'axis',
                                axisPointer: { // 坐标轴指示器，坐标轴触发有效
                                    type: 'shadow' // 默认为直线，可选为：'line' | 'shadow'
                                }
                            },
                            legend: {
                                data: ['支付宝', '微信', '现金', '储值', '银联']
                            },
                            color: [
                                 '#4990de','#3cb035','#8B8386','#8DB6CD','#ccc' //折线图颜色定制
                            ],
                            grid: {
                                left: '3%',
                                right: '4%',
                                bottom: '3%',
                                containLabel: true
                            },
                            xAxis: [{
                                type: 'value'
                            }],
                            yAxis: [{
                                type: 'category',
                                data: [<?php $count=0;foreach ($trade_achievement -> store_name_by_money as $k => $v){if($count == 0){echo "'".$v."'";$count++;}else{echo ","."'".$v."'";}}?>]
                            }],
                            series: [{
                                name: '支付宝',
                                type: 'bar',
                                stack: '总量',
                                itemStyle: {
                                    normal: {
                                        label: {
                                            show: false,
                                            position: 'insideRight'
                                        }
                                    }
                                },
                                data: [<?php $count=0;foreach ($trade_achievement -> alipay as $k => $v){if($count == 0){echo $v;$count++;}else{echo ",".$v;}}?>]
                            }, {
                                name: '微信',
                                type: 'bar',
                                stack: '总量',
                                itemStyle: {
                                    normal: {
                                        label: {
                                            show: false,
                                            position: 'insideRight'
                                        }
                                    }
                                },
                                data: [<?php $count=0;foreach ($trade_achievement -> wechat as $k => $v){if($count == 0){echo $v;$count++;}else{echo ",".$v;}}?>]
                            }, {
                                name: '现金',
                                type: 'bar',
                                stack: '总量',
                                itemStyle: {
                                    normal: {
                                        label: {
                                            show: false,
                                            position: 'insideRight'
                                        }
                                    }
                                },
                                data: [<?php $count=0;foreach ($trade_achievement -> cash as $k => $v){if($count == 0){echo $v;$count++;}else{echo ",".$v;}}?>]
                            }, {
                                name: '储值',
                                type: 'bar',
                                stack: '总量',
                                itemStyle: {
                                    normal: {
                                        label: {
                                            show: false,
                                            position: 'insideRight'
                                        }
                                    }
                                },
                                data: [<?php $count=0;foreach ($trade_achievement -> stored as $k => $v){if($count == 0){echo $v;$count++;}else{echo ",".$v;}}?>]
                            }, {
                                name: '银联',
                                type: 'bar',
                                stack: '总量',
                                itemStyle: {
                                    normal: {
                                        label: {
                                            show: false,
                                            position: 'insideRight'
                                        }
                                    }
                                },
                                data: [<?php $count=0;foreach ($trade_achievement -> unionpay as $k => $v){if($count == 0){echo $v;$count++;}else{echo ",".$v;}}?>]
                            }]
                        };
                        myChart_result_money.setOption(option);
                        </script>

                        <div id="trading_results_num_img" style="width: 800px;height:600px;display:none"></div>
                        <script type="text/javascript">
                        var myChart_result_num = echarts.init(document.getElementById('trading_results_num_img'));
                        option = {
                            tooltip: {
                                trigger: 'axis',
                                axisPointer: { // 坐标轴指示器，坐标轴触发有效
                                    type: 'shadow' // 默认为直线，可选为：'line' | 'shadow'
                                }
                            },
                            legend: {
                                data: ['支付宝', '微信', '现金', '储值', '银联']
                            },
                            color: [
								 '#4990de','#3cb035','#8B8386','#8DB6CD','#ccc'//折线图颜色定制
                            ],
                            grid: {
                                left: '3%',
                                right: '4%',
                                bottom: '3%',
                                containLabel: true
                            },
                            xAxis: [{
                                type: 'value'
                            }],
                            yAxis: [{
                                type: 'category',
                                data: [<?php $count=0;foreach ($trade_achievement -> store_name_by_num as $k => $v){if($count == 0){echo "'".$v."'";$count++;}else{echo ","."'".$v."'";}}?>]
                            }],
                            series: [{
                                name: '支付宝',
                                type: 'bar',
                                stack: '总量',
                                itemStyle: {
                                    normal: {
                                        label: {
                                            show: false,
                                            position: 'insideRight'
                                        }
                                    }
                                },
                                data: [<?php $count=0;foreach ($trade_achievement -> alipay_num as $k => $v){if($count == 0){echo $v;$count++;}else{echo ",".$v;}}?>]
                            }, {
                                name: '微信',
                                type: 'bar',
                                stack: '总量',
                                itemStyle: {
                                    normal: {
                                        label: {
                                            show: false,
                                            position: 'insideRight'
                                        }
                                    }
                                },
                                data: [<?php $count=0;foreach ($trade_achievement -> wechat_num as $k => $v){if($count == 0){echo $v;$count++;}else{echo ",".$v;}}?>]
                            }, {
                                name: '现金',
                                type: 'bar',
                                stack: '总量',
                                itemStyle: {
                                    normal: {
                                        label: {
                                            show: false,
                                            position: 'insideRight'
                                        }
                                    }
                                },
                                data: [<?php $count=0;foreach ($trade_achievement -> cash_num as $k => $v){if($count == 0){echo $v;$count++;}else{echo ",".$v;}}?>]
                            }, {
                                name: '储值',
                                type: 'bar',
                                stack: '总量',
                                itemStyle: {
                                    normal: {
                                        label: {
                                            show: false,
                                            position: 'insideRight'
                                        }
                                    }
                                },
                                data: [<?php $count=0;foreach ($trade_achievement -> stored_num as $k => $v){if($count == 0){echo $v;$count++;}else{echo ",".$v;}}?>]
                            }, {
                                name: '银联',
                                type: 'bar',
                                stack: '总量',
                                itemStyle: {
                                    normal: {
                                        label: {
                                            show: false,
                                            position: 'insideRight'
                                        }
                                    }
                                },
                                data: [<?php $count=0;foreach ($trade_achievement -> unionpay_num as $k => $v){if($count == 0){echo $v;$count++;}else{echo ",".$v;}}?>]
                            }]
                        };
                        myChart_result_num.setOption(option);
                        </script>
                    </div>
                </div>
            </div>
      	</div>
  	</div>
</body>
<script type="text/javascript">
     $('#management_first').change(function(){
    	 $('#submanagement').remove();
         $('#select_store').remove();
         var value = $(this).val();
         var arr = new Array();
         arr = value.split(","); //字符分割 
         if(arr[1] == 'm'){
             var id = arr[0];
	    	 $.ajax({
	  	        url: '<?php echo(Yii::app()->createUrl('mCenter/Data/GetSubManagementAndStore'));?>',
	  	        data: {id: id},
	  	        type: 'post',
	  	        dataType: 'json',
	  	        success: function (data) { 
                    if (data.data.management == undefined) {
                        $('#management_first').after('<select class="iselect" id="submanagement" onchange="selectM()"><option>-请选择-</option></select>');
                        for(var i=0;i<data.data.store.length;i++){
                        	if(data.data.store[i].name!=undefined){
                            	$('#submanagement').append('<option value="'+data.data.store[i].id+',s">'+data.data.store[i].name+'(门店)</option>');
                        	}
                       	}
                    } else {
                        if(data.status == <?php echo ERROR_NONE?> && (data.data.management.length > 0 || data.data.store.length > 0)){
                            $('#management_first').after('<select class="iselect" id="submanagement" onchange="selectM()"><option>-请选择-</option></select>');
                            for(var i=0;i<data.data.management.length;i++){
                                $('#submanagement').append('<option value="'+data.data.management[i].id+',m">'+data.data.management[i].name+'(分组)</option>');
                            }
                            for(var i=0;i<data.data.store.length;i++){
                            	if(data.data.store[i].name!=undefined){
                            		$('#submanagement').append('<option value="'+data.data.store[i].id+',s">'+data.data.store[i].name+'(门店)</option>');
                            	}
                            }
                        }
                    }
	  	        }
	  	    });
         }else{
        	 $('#submanagement').remove();
        	 $('#store_id').val(arr[0]);
         }
  	});

     function selectM(){
    	 $('#select_store').remove();
         var value =  $('#submanagement').val();
         var arr = new Array();
         arr = value.split(","); //字符分割 
         if(arr[1] == 'm'){
             var id = arr[0];
	    	 $.ajax({
	  	        url: '<?php echo(Yii::app()->createUrl('mCenter/Data/GetStoreByManagement'));?>',
	  	        data: {id: id},
	  	        type: 'post',
	  	        dataType: 'json',
	  	        success: function (data) { 
	  	        	
		  	        if(data.status == <?php echo ERROR_NONE?> && data.data.store.length > 0){
			  	        $('#submanagement').after('<select class="iselect" id="select_store" onchange="selectS()"><option>-请选择门店-</option></select');
				  	    for(var i=0;i<data.data.store.length;i++){
				  	    	if(data.data.store[i].name!=undefined){
								$('#select_store').append('<option value="'+data.data.store[i].id+',s">'+data.data.store[i].name+'(门店)</option>');
				  	    	}	
					  	}
			  	    }
	  	        }
	  	    });
         }else{
        	 $('#select_store').remove();
             $('#store_id').val(arr[0]);
         }
     }

     function selectS(){
    	 var value =  $('#select_store').val();
         var arr = new Array();
         arr = value.split(","); //字符分割 
         $('#store_id').val(arr[0]);
  	}

  	//交易趋势分析
    function tradetrend(){
        //所选门店id
        var store_id = $('#store_id').val();
        var val_management_first = $('#management_first').val();
        //时间段
        var time = $('input[name=Time]').val();
        $.ajax({
  	        url: '<?php echo(Yii::app()->createUrl('mCenter/Data/GetTradeTrend'));?>',
  	        data: {store_id:store_id,time:time},
  	        type: 'post',
  	        dataType: 'json',
  	        success: function (data) {
  	  	        var date = data.data.date;
				var total_money = data.data.total;
				var alipay_money = data.data.alipay;
				var wechat_money = data.data.wechat;

				var total_num = data.data.total_num;
				var alipay_num = data.data.alipay_num;
				var wechat_num = data.data.wechat_num;


  	        	option = {
    	                  tooltip: {
    	                      trigger: 'axis'
    	                  },
    	                  legend: {
    	                      data: ['全部', '支付宝', '微信']
    	                  },
    	                  grid: {
    	                      left: '3%',
    	                      right: '4%',
    	                      bottom: '3%',
    	                      containLabel: true
    	                  },
    	                  color: [
    	                      '#fd6303', '#4990de','#2f9833'
    	                  ],
    	                  toolbox: {
    	                      feature: {
    	                      }
    	                  },
    	                  xAxis: [{
    	                      type: 'category',
    	                      nameLocation: 'middle',
    	                      boundaryGap: false,
    	                      data: date
    	                  }],
    	                  yAxis: [{
    	                      type: 'value',
    	                      name: '金额'
    	                  }],
    	                  series: [{
    	                      name: '全部',
    	                      type: 'line',
    	                      data: total_money
    	                  }, {
    	                      name: '支付宝',
    	                      type: 'line',
    	                      data: alipay_money
    	                  }, {
    	                      name: '微信',
    	                      type: 'line',
    	                      data: wechat_money
    	                  }]

    	              };
    	              myChart_money.setOption(option);
    	              
    	              option = {
    	                      tooltip: {
    	                          trigger: 'axis'
    	                      },
    	                      legend: {
    	                          data: ['全部', '支付宝', '微信']
    	                      },
    	                      grid: {
    	                          left: '3%',
    	                          right: '4%',
    	                          bottom: '3%',
    	                          containLabel: true
    	                      },
    	                      color: [
    	                          '#fd6303', '#4990de','#2f9833'
    	                      ],
    	                      toolbox: {
    	                          feature: {

    	                          }
    	                      },
    	                      xAxis: [{
    	                          type: 'category',
    	                          nameLocation: 'middle',
    	                          boundaryGap: false,
    	                          data: date
    	                      }],
    	                      yAxis: [{
    	                          type: 'value',
    	                          name: '数量'
    	                      }],
    	                      series: [{
    	                          name: '全部',
    	                          type: 'line',
    	                          data: total_num
    	                      }, {
    	                          name: '支付宝',
    	                          type: 'line',
    	                          data: alipay_num
    	                      }, {
    	                          name: '微信',
    	                          type: 'line',
    	                          data: wechat_num
    	                      }]

    	                  };
    	                  myChart_num.setOption(option);
  	        }
  	    });
    }

    //时间选择控件
    $(document).ready(function() {
		$('input[name=Time]').daterangepicker({
	        format: 'YYYY/MM/DD',
	        dateLimit: true,
	        maxDate: '<?php echo date('Y/m/d',strtotime('-1 days'));?>',
	        startDate:'<?php echo date('Y/m/d',strtotime('-7 days'));?>',
	    	endDate:'<?php echo date('Y/m/d',strtotime('+1 days'));?>'
	    });
	});	

	//7日
	$('#seven').click(function(){
		$(this).siblings("input[type='button']").addClass("btn_com_gray").removeClass("btn_com_blue");
		$(this).addClass("btn_com_blue").removeClass("btn_com_gray");
		var time = '<?php echo date('Y/m/d',strtotime('-7 days'));?>'+' - '+'<?php echo date('Y/m/d',strtotime('-1 days'));?>';
		$('input[name=Time]').val(time);
		$('input[name=Time]').daterangepicker({
	        format: 'YYYY/MM/DD',
	        dateLimit: true,
	        maxDate: '<?php echo date('Y/m/d',strtotime('-1 days'));?>',
	        startDate:'<?php echo date('Y/m/d',strtotime('-7 days'));?>',
	    	endDate:'<?php echo date('Y/m/d',strtotime('+1 days'));?>'
	    });
	});
	//14日
	$('#fourteen').click(function(){
		$(this).siblings("input[type='button']").addClass("btn_com_gray").removeClass("btn_com_blue");
		$(this).addClass("btn_com_blue").removeClass("btn_com_gray");
		var time = '<?php echo date('Y/m/d',strtotime('-14 days'));?>'+' - '+'<?php echo date('Y/m/d',strtotime('-1 days'));?>';
		$('input[name=Time]').val(time);
		$('input[name=Time]').daterangepicker({
	        format: 'YYYY/MM/DD',
	        dateLimit: true,
	        maxDate: '<?php echo date('Y/m/d',strtotime('-1 days'));?>',
	        startDate:'<?php echo date('Y/m/d',strtotime('-14 days'));?>',
	    	endDate:'<?php echo date('Y/m/d',strtotime('+1 days'));?>'
	    });
	});
	//30日
	$('#thirty').click(function(){
		$(this).siblings("input[type='button']").addClass("btn_com_gray").removeClass("btn_com_blue");
		$(this).addClass("btn_com_blue").removeClass("btn_com_gray");
		var time = '<?php echo date('Y/m/d',strtotime('-29 days'));?>'+' - '+'<?php echo date('Y/m/d',strtotime('-1 days'));?>';
		$('input[name=Time]').val(time);
		$('input[name=Time]').daterangepicker({
	        format: 'YYYY/MM/DD',
	        dateLimit: true,
	        maxDate: '<?php echo date('Y/m/d',strtotime('-1 days'));?>',
	        startDate:'<?php echo date('Y/m/d',strtotime('-30 days'));?>',
	    	endDate:'<?php echo date('Y/m/d',strtotime('+1 days'));?>'
	    });
	});

	/*******************************交易趋势金额与数量切换***************************************/
	$('#trade_trend_money').click(function(){
		$(this).removeClass('btn_com_gray');
		$(this).addClass('btn_com_blue');
		$('#trade_trend_num').removeClass('btn_com_blue');
		$('#trade_trend_num').addClass('btn_com_gray');
		$('#trading_trend_img_num').slideUp(500,function(){$('#trading_trend_img_money').slideDown(500)});		
		});
	$('#trade_trend_num').click(function(){
		$(this).removeClass('btn_com_gray');
		$(this).addClass('btn_com_blue');
		$('#trade_trend_money').removeClass('btn_com_blue');
		$('#trade_trend_money').addClass('btn_com_gray');
		$('#trading_trend_img_money').slideUp(500,function(){$('#trading_trend_img_num').slideDown(500)});
		});

	$('#trade_achievement_money').click(function(){
		$(this).removeClass('btn_com_gray');
		$(this).addClass('btn_com_blue');
		$('#trade_achievement_num').removeClass('btn_com_blue');
		$('#trade_achievement_num').addClass('btn_com_gray');
		$('#trading_results_num_img').slideUp(500,function(){$('#trading_results_money_img').slideDown(500)});		
		});
	$('#trade_achievement_num').click(function(){
		$(this).removeClass('btn_com_gray');
		$(this).addClass('btn_com_blue');
		$('#trade_achievement_money').removeClass('btn_com_blue');
		$('#trade_achievement_money').addClass('btn_com_gray');
		$('#trading_results_money_img').slideUp(500,function(){$('#trading_results_num_img').slideDown(500)});
		});
	

	/*************************************交易业绩top10**********************************************/
	//时间选择控件
    $(document).ready(function() {
		$('input[name=Time2]').daterangepicker({
	        format: 'YYYY/MM/DD',
	        dateLimit: true,
	        maxDate: '<?php echo date('Y/m/d',strtotime('-1 days'));?>',
	        startDate:'<?php echo date('Y/m/d',strtotime('-7 days'));?>',
	    	endDate:'<?php echo date('Y/m/d',strtotime('+1 days'));?>'
	    });
	});	

	//7日
	$('#seven2').click(function(){
		$(this).siblings("input[type='button']").addClass("btn_com_gray").removeClass("btn_com_blue");
		$(this).addClass("btn_com_blue").removeClass("btn_com_gray");
		var time = '<?php echo date('Y/m/d',strtotime('-7 days'));?>'+' - '+'<?php echo date('Y/m/d',strtotime('-1 days'));?>';
		$('input[name=Time2]').val(time);
		$('input[name=Time2]').daterangepicker({
	        format: 'YYYY/MM/DD',
	        dateLimit: true,
	        maxDate: '<?php echo date('Y/m/d',strtotime('-1 days'));?>',
	        startDate:'<?php echo date('Y/m/d',strtotime('-7 days'));?>',
	    	endDate:'<?php echo date('Y/m/d',strtotime('+1 days'));?>'
	    });
	});
	//14日
	$('#fourteen2').click(function(){
		$(this).siblings("input[type='button']").addClass("btn_com_gray").removeClass("btn_com_blue");
		$(this).addClass("btn_com_blue").removeClass("btn_com_gray");
		var time = '<?php echo date('Y/m/d',strtotime('-14 days'));?>'+' - '+'<?php echo date('Y/m/d',strtotime('-1 days'));?>';
		$('input[name=Time2]').val(time);
		$('input[name=Time2]').daterangepicker({
	        format: 'YYYY/MM/DD',
	        dateLimit: true,
	        maxDate: '<?php echo date('Y/m/d',strtotime('-1 days'));?>',
	        startDate:'<?php echo date('Y/m/d',strtotime('-14 days'));?>',
	    	endDate:'<?php echo date('Y/m/d',strtotime('+1 days'));?>'
	    });
	});
	//30日
	$('#thirty2').click(function(){
		$(this).siblings("input[type='button']").addClass("btn_com_gray").removeClass("btn_com_blue");
		$(this).addClass("btn_com_blue").removeClass("btn_com_gray");
		var time = '<?php echo date('Y/m/d',strtotime('-30 days'));?>'+' - '+'<?php echo date('Y/m/d',strtotime('-1 days'));?>';
		$('input[name=Time2]').val(time);
		$('input[name=Time2]').daterangepicker({
	        format: 'YYYY/MM/DD',
	        dateLimit: true,
	        maxDate: '<?php echo date('Y/m/d',strtotime('-1 days'));?>',
	        startDate:'<?php echo date('Y/m/d',strtotime('-30 days'));?>',
	    	endDate:'<?php echo date('Y/m/d',strtotime('+1 days'));?>'
	    });
	});


	//交易业绩top10
    function tradeachievement(){
        //时间段
        var time = $('input[name=Time2]').val();
        $.ajax({
  	        url: '<?php echo(Yii::app()->createUrl('mCenter/Data/GetStoreTrade'));?>',
  	        data: {time:time},
  	        type: 'post',
  	        dataType: 'json',
  	        success: function (data) {
  	  	        if(data.status == <?php echo ERROR_NONE?>){
					var store_name_by_money = data.data.store_name_by_money;
					var store_name_by_num = data.data.store_name_by_num;
					var alipay = data.data.alipay;
					var alipay_num = data.data.alipay_num;
					var wechat = data.data.wechat;
					var wechat_num = data.data.wechat_num;
					var cash = data.data.cash;
					var cash_num = data.data.cash_num;
					var stored = data.data.stored;
					var stored_num = data.data.stored_num;
					var unionpay = data.data.unionpay;
					var unionpay_num = data.data.unionpay_num;
  	  	  	        
					option = {
                            tooltip: {
                                trigger: 'axis',
                                axisPointer: { // 坐标轴指示器，坐标轴触发有效
                                    type: 'shadow' // 默认为直线，可选为：'line' | 'shadow'
                                }
                            },
                            legend: {
                                data: ['支付宝', '微信', '现金', '储值', '银联']
                            },
                            color: [
                                 '#4990de','#3cb035','#8B8386','#8DB6CD','#ccc' //折线图颜色定制
                            ],
                            grid: {
                                left: '3%',
                                right: '4%',
                                bottom: '3%',
                                containLabel: true
                            },
                            xAxis: [{
                                type: 'value'
                            }],
                            yAxis: [{
                                type: 'category',
                                data: store_name_by_money
                            }],
                            series: [{
                                name: '支付宝',
                                type: 'bar',
                                itemStyle: {
                                    normal: {
                                        label: {
                                            show: false,
                                            position: 'insideRight'
                                        }
                                    }
                                },
                                data: alipay
                            }, {
                                name: '微信',
                                type: 'bar',
                                itemStyle: {
                                    normal: {
                                        label: {
                                            show: false,
                                            position: 'insideRight'
                                        }
                                    }
                                },
                                data: wechat
                            }, {
                                name: '现金',
                                type: 'bar',
                                itemStyle: {
                                    normal: {
                                        label: {
                                            show: false,
                                            position: 'insideRight'
                                        }
                                    }
                                },
                                data: cash
                            }, {
                                name: '储值',
                                type: 'bar',
                                itemStyle: {
                                    normal: {
                                        label: {
                                            show: false,
                                            position: 'insideRight'
                                        }
                                    }
                                },
                                data: stored
                            }, {
                                name: '银联',
                                type: 'bar',
                                itemStyle: {
                                    normal: {
                                        label: {
                                            show: false,
                                            position: 'insideRight'
                                        }
                                    }
                                },
                                data: unionpay
                            }]
                        };
                        myChart_result_money.setOption(option);

                        option = {
                                tooltip: {
                                    trigger: 'axis',
                                    axisPointer: { // 坐标轴指示器，坐标轴触发有效
                                        type: 'shadow' // 默认为直线，可选为：'line' | 'shadow'
                                    }
                                },
                                legend: {
                                    data: ['支付宝', '微信', '现金', '储值', '银联']
                                },
                                color: [
                                     '#4990de','#3cb035','#8B8386','#8DB6CD','#ccc' //折线图颜色定制
                                ],
                                grid: {
                                    left: '3%',
                                    right: '4%',
                                    bottom: '3%',
                                    containLabel: true
                                },
                                xAxis: [{
                                    type: 'value'
                                }],
                                yAxis: [{
                                    type: 'category',
                                    data: store_name_by_num
                                }],
                                series: [{
                                    name: '支付宝',
                                    type: 'bar',
                                    stack: '总量',
                                    itemStyle: {
                                        normal: {
                                            label: {
                                                show: false,
                                                position: 'insideRight'
                                            }
                                        }
                                    },
                                    data: alipay_num
                                }, {
                                    name: '微信',
                                    type: 'bar',
                                    stack: '总量',
                                    itemStyle: {
                                        normal: {
                                            label: {
                                                show: false,
                                                position: 'insideRight'
                                            }
                                        }
                                    },
                                    data: wechat_num
                                }, {
                                    name: '现金',
                                    type: 'bar',
                                    stack: '总量',
                                    itemStyle: {
                                        normal: {
                                            label: {
                                                show: false,
                                                position: 'insideRight'
                                            }
                                        }
                                    },
                                    data: cash_num
                                }, {
                                    name: '储值',
                                    type: 'bar',
                                    stack: '总量',
                                    itemStyle: {
                                        normal: {
                                            label: {
                                                show: false,
                                                position: 'insideRight'
                                            }
                                        }
                                    },
                                    data: stored_num
                                }, {
                                    name: '银联',
                                    type: 'bar',
                                    stack: '总量',
                                    itemStyle: {
                                        normal: {
                                            label: {
                                                show: false,
                                                position: 'insideRight'
                                            }
                                        }
                                    },
                                    data: unionpay_num
                                }]
                            };
                            myChart_result_num.setOption(option);
  	  	  	    }
  	        }
        });
    }




	
</script>
