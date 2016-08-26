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
    	<div class="name">页面访问量统计</div>
        <div class="data-con">            
            <div class="title">
            	<span>页面访问量统计</span>
                <!--span>
                    <select>
                    	<option>订单类型</option>
                        <option>订单类型</option>
                        <option>订单类型</option>
                        <option>订单类型</option>
                    </select>
                </span-->
                <span>
                   <?php echo CHtml::textField('Time', '', array('style' => 'width:140px', 'readonly' => 'true', 'placeholder' => '查询时间段'))?>
                </span>
                 <span>
                     <input type="submit" class="btn_border" value="查看" onclick="fun()">
                </span>                
            </div>
            <div id="container" class="img" style="min-width:700px;height:400px">pv</div>                
        </div>       
        </div>
</div>
<script>
var date = [<?php if(!empty($members)) { ?>
                <?php foreach ($members as $key => $value) { ?>
                       '<?php echo $value['date']?>',             
                <?php } } ?>];
                    
var pvnum = [<?php if(!empty($members)) { ?>
                <?php foreach ($members as $key => $value) { ?>
                       <?php echo $value['pv']?>,             
                <?php } }?>];
 
function fun()
{     
    $.ajax({
        url:'<?php echo Yii::app()->createUrl('mCenter/data/Search')?>',
        type:'get',
        data:{Time : $('#Time').val()},
        dataType:'json',
        cache: false,
        success: function(data){
            var date = new Array();
            var pvnum = new Array();
            $.each(data, function(n,val){
                date.push(val.date);
                pvnum.push(parseFloat(val.pv));
            });			
            pv(date,pvnum);
        }
    });
}

function pv(date,pvnum) {
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
            categories: date
        },
        yAxis: {
            title: {
                text: 'pv数'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },  
        series: [{
            name: 'pv数',
            data: pvnum
        }]
    });
}
pv(date,pvnum);
</script>