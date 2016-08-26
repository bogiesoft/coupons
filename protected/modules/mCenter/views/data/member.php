<script type="text/javascript">
	$(document).ready(main_obj.list_init);
        //设置日期控件
	$(document).ready(function() {
		$('input[name=Time]').daterangepicker({
	        format: 'YYYY/MM/DD',
	        dateLimit: true,
	        maxDate: true
	    });
	});
</script>

<div class="kkfm_r_inner">
    <div class="data">
    	<div class="name">会员数据统计</div>        
        <div class="count">
        	<span class="title">昨日新增会员数</span>
            <div class="count-list clearfix">
                <?php 
                $num = 0;
                $max = 0;
                if(!empty($members)) {
                foreach ($members as $key => $value) { 
                    $num = $num + $value['new_user_num'];
                    if($max <= $value['user_num'])
                    {
                        $max = $value['user_num'];
                    }
                } }?>
            	<div class="num">
                	<span class="ico"><?php echo $num?></span>
                    <span>新增会员数</span>
                 </div>
                 <div class="num num2">
                	<span class="ico"><?php echo $max?></span>
                    <span>累计会员数</span>
                 </div>
            </div>
                <div class="data-con">
			<div class="title">
                <?php $form = $this->beginWidget('CActiveForm',array('method'=>'get'))?>
            	<span>新增会员统计 
                    <?php echo CHtml::textField('Time', isset($_GET['Time']) ? $_GET['Time'] : '', array('style' => 'width:140px', 'readonly' => 'true', 'placeholder' => '查询时间段'))?>
                    <a href="<?php echo Yii::app()->createUrl('mCenter/Data/Member',array('day'=>'-7day'))?>" class="btn_border" <?php if(empty($_GET['Time'])) echo isset($_GET['day']) && $_GET['day'] == '-7day' ? 'style="background:#97C7FC"' : ''?>>最近7日</a>
                    <a href="<?php echo Yii::app()->createUrl('mCenter/Data/Member',array('day'=>'-30day'))?>" class="btn_border" <?php if(empty($_GET['Time'])) echo isset($_GET['day']) && $_GET['day'] == '-30day' ? 'style="background:#97C7FC"' : ''?>>最近30日</a>                    
                </span>                
                <span>
                    <input type="submit" class="btn_border" value="查看">
                </span>
                <?php $form = $this->endWidget()?>
            </div>
            <div id="container" class="img" style="min-width:700px;height:400px">图片</div>               
        </div>
         <div class="data-con" style="margin-top:10px">
             <div class="title"><span>会员来源统计</span></div>
            <div id="pie" class="img" style="min-width:700px;height:400px">饼图</div>           
          </div>
                <div class="title"><span>会员性别，年龄，地区分布情况</span><br>
                    <span>会员性别分布情况</span></div>
                <div class="data-con" style="margin-top:10px">
        	<div id="pie1" class="img" style="min-width:700px;height:400px">会员性别分布图</div>
                <div class="title">会员地区分布情况</div>
                <div id="pie2" class="img" style="min-width:700px;height:400px">地区分布饼图</div>
          </div>
        </div>
    </div>
</div> 
<script>
$(function () {
    $('#container').highcharts({
        title: {
            text: '',
            x: -20 //center
        },
        subtitle: {
            text: '',
            x: -20
        },
        xAxis: {
            categories: [<?php if(!empty($members)) { ?>
                <?php foreach ($members as $key => $value) { ?>
                       '<?php echo $value['date']?>',             
                <?php } } ?>]
        },
        yAxis: {
            title: {
                text: '新增会员数'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },  
        series: [{
            name: '新增会员数',
            data: [<?php if(!empty($members)) { ?>
                <?php foreach ($members as $key => $value) { ?>
                       <?php echo $value['new_user_num']?>,             
                <?php } }?>]
        }]
    });
});

$(function () {
    var chart;
    
    $(document).ready(function () {
    	
    	// Build the chart
        $('#pie').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: ''
            },
            tooltip: {
        	    pointFormat: '<b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },
            series: [{
                type: 'pie',
                
                data: [
                    ['支付宝',   <?php echo isset($from['alipay']) ? $from['alipay'] : '0'?>],
                    ['微信',       <?php echo isset($from['wechat']) ? $from['wechat'] : '0'?>],                    
                    ['手机网页',    <?php echo isset($from['wap']) ? $from['wap'] : '0'?>],
                    ['其它',     <?php echo isset($from['other']) ? $from['other'] : '0'?>]                    
                ]
            }]
        });
    });
    
});	

$(function () {
    var chart;
    
    $(document).ready(function () {
    	
    	// Build the chart
        $('#pie2').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: ''
            },
            tooltip: {
        	    pointFormat: '<b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },
            series: [{
                type: 'pie',                
                data: [<?php 
                if(!empty($from['count'])) {
                    $sum = 0;
                    for($j=0;$j<$from['count'];$j++)
                    { 
                        if($from['ar'][$j]) 
                        { 
                             $sum = $sum + $from['addrsum'][$j];
                        }
                    }
                for($i=0;$i<$from['count'];$i++)  { ?>
                    <?php if($from['ar'][$i]) { ?>
                    ['<?php echo isset($from['ar'][$i]) ? $from['ar'][$i] : '0'?> <?php echo isset($from['addrsum'][$i]) ? round($from['addrsum'][$i]/$sum*100,1) : '0'?>%',   <?php echo isset($from['addrsum'][$i]) ? $from['addrsum'][$i] : '0'?>],                    
                <?php } } } ?>]
            }]
        });
    });
    
});

$(function () {                                                               
    $('#pie1').highcharts({                                          
        chart: {                                                          
        },                                                                
        title: {                                                          
            text: ''                                     
        },                                                                
        xAxis: {                                                          
            categories: ['18岁以下', '18岁~24岁', '25岁~29岁', '30岁~39岁', '40岁~49岁','50岁~59岁','60岁以上','未知']
        },                                                                
        tooltip: {                                                        
            formatter: function() {                                       
                var s;                                                    
                if (this.point.name) { // the pie chart                   
                    s = ''+                                               
                        this.point.name +': '+ this.y;         
                } else {                                                  
                    s = ''+                                               
                        this.x  +': '+ this.y;                            
                }                                                         
                return s;                                                 
            }                                                             
        },                                                                
        labels: {                                                         
            items: [{                                                     
                html: '',                          
                style: {                                                  
                    left: '40px',                                         
                    top: '8px',                                           
                    color: 'black'                                        
                }                                                         
            }]                                                            
        },                                                                
        series: [{                                                        
            type: 'column',                                               
            name: '男 <?php echo isset($sex['male']) ? round($sex['male']/($sex['male']+$sex['female']+$sex['unsex'])*100,1) : '0'?>%',                                                 
            data: [<?php echo isset($sex['ybman']) ? $sex['ybman'] : '0'?>,<?php echo isset($sex['esman']) ? $sex['esman'] : '0'?>,<?php echo isset($sex['ejman']) ? $sex['ejman'] : '0'?>,<?php echo isset($sex['sjman']) ? $sex['sjman'] : '0'?>,<?php echo isset($sex['xjman']) ? $sex['xjman'] : '0'?>,<?php echo isset($sex['wjman']) ? $sex['wjman'] : '0'?>,<?php echo isset($sex['lsman']) ? $sex['lsman'] : '0'?>,<?php echo isset($sex['unman']) ? $sex['unman'] : '0'?>]                                         
        }, {                                                              
            type: 'column',                                               
            name: '女 <?php echo isset($sex['female']) ? round($sex['female']/($sex['male']+$sex['female']+$sex['unsex'])*100,1) : '0'?>%',                                                 
            data: [<?php echo isset($sex['ybwoman']) ? $sex['ybwoman'] : '0'?>,<?php echo isset($sex['eswoman']) ? $sex['eswoman'] : '0'?>,<?php echo isset($sex['ejwoman']) ? $sex['ejwoman'] : '0'?>,<?php echo isset($sex['sjwoman']) ? $sex['sjwoman'] : '0'?>,<?php echo isset($sex['xjwoman']) ? $sex['xjwoman'] : '0'?>,<?php echo isset($sex['wjwoman']) ? $sex['wjwoman'] : '0'?>,<?php echo isset($sex['lswoman']) ? $sex['lswoman'] : '0'?>,<?php echo isset($sex['unwoman']) ? $sex['unwoman'] : '0'?>]                                          
        }, {                                                              
            type: 'column',                                               
            name: '未知 <?php echo isset($sex['unsex']) ? round($sex['unsex']/($sex['male']+$sex['female']+$sex['unsex'])*100,1) : '0'?>%',                                                  
            data: [<?php echo isset($sex['ybnoman']) ? $sex['ybnoman'] : '0'?>,<?php echo isset($sex['esnoman']) ? $sex['esnoman'] : '0'?>,<?php echo isset($sex['ejnoman']) ? $sex['ejnoman'] : '0'?>,<?php echo isset($sex['sjnoman']) ? $sex['sjnoman'] : '0'?>,<?php echo isset($sex['xjnoman']) ? $sex['xjnoman'] : '0'?>,<?php echo isset($sex['wjnoman']) ? $sex['wjnoman'] : '0'?>,<?php echo isset($sex['lsnoman']) ? $sex['lsnoman'] : '0'?>,<?php echo isset($sex['unnoman']) ? $sex['unnoman'] : '0'?>]                                          
        }, {                                                              
            type: 'pie',                                                  
            name: 'Total consumption',                                    
            data: [{                                                      
                name: '男',                                             
                y: <?php echo isset($sex['male']) ? $sex['male'] : '0'?>,                                                    
                color: Highcharts.getOptions().colors[0] // Jane's color  
            }, {                                                          
                name: '女',                                             
                y: <?php echo isset($sex['female']) ? $sex['female'] : '0'?>,                                                    
                color: Highcharts.getOptions().colors[1] // John's color  
            }, {                                                          
                name: '未知',                                              
                y: <?php echo isset($sex['unsex']) ? $sex['unsex'] : '0'?>,                                                    
                color: Highcharts.getOptions().colors[2] // Joe's color   
            }],                                                           
            center: [100, 50],                                            
            size: 150,                                                    
            showInLegend: false,                                          
            dataLabels: {                                                 
                enabled: false                                            
            }                                                             
        }]                                                                
    });                                                                   
});                                                                       				
</script>
				
