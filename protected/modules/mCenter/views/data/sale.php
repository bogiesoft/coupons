<script type="text/javascript">
	$(document).ready(main_obj.list_init);
        //设置日期控件
	$(document).ready(function() {
            $('input[name=Time]').daterangepicker({
                format: 'YYYY/MM/DD',
                dateLimit: true,
                maxDate: true
	    });
            $('input[name=Time1]').daterangepicker({
                format: 'YYYY/MM/DD',
                dateLimit: true,
                maxDate: true
	    });
            $('input[name=Time2]').daterangepicker({
                format: 'YYYY/MM/DD',
                dateLimit: true,
                maxDate: true
	    });
	});
</script>

<div class="kkfm_r_inner">
    <div class="data">
    	<div class="name">促销活动统计</div>
        <div class="data-con">            
            <div class="title">
            	<span>红包活动统计</span>
                <!--span>
                    <select>
                    	<option>活动名称</option>
                        <option>活动名称</option>
                        <option>活动名称</option>
                        <option>活动名称</option>
                    </select>
                </span-->
                <span>
                    <?php echo CHtml::textField('Time', '', array('style' => 'width:140px', 'readonly' => 'true', 'placeholder' => '查询时间段'))?>
                </span>
                 <span>
                     <input type="submit" class="btn_border" value="查看" onclick="fun()">
                </span>               
            </div>
            <div id="container" class="img" style="min-width:700px;height:400px">红包活动统计</div>                
        </div>
        
        <div class="data">    	
        <div class="data-con">            
            <div class="title">
            	<span>优惠券活动统计</span>
                <!--span>
                    <select>
                    	<option>活动名称</option>
                        <option>活动名称</option>
                        <option>活动名称</option>
                        <option>活动名称</option>
                    </select>
                </span-->
                    <?php echo CHtml::textField('Time1', '', array('style' => 'width:140px', 'readonly' => 'true', 'placeholder' => '查询时间段'))?>
                 <span>
                    <input type="submit" class="btn_border" value="查看" onclick="fun1()">
                </span>                
            </div>
            <div id="container1" class="img" style="min-width:700px;height:400px">优惠券活动统计</div>
        </div>
       
        <div class="data">    	
        <div class="data-con">            
            <div class="title">
            	<span>储值统计</span>
                <!--span>
                    <select>
                    	<option>活动名称</option>
                        <option>活动名称</option>
                        <option>活动名称</option>
                        <option>活动名称</option>
                    </select>
                </span-->
                <span>
                    <?php echo CHtml::textField('Time2', '', array('style' => 'width:140px', 'readonly' => 'true', 'placeholder' => '查询时间段'))?>
                </span>
                 <span>
                     <input type="submit" class="btn_border" value="查看" onclick="fun2()">
                </span>                
            </div>
            <div id="container2" class="img" style="min-width:700px;height:400px">储值金额</div>    
            <div id="container3" class="img" style="min-width:700px;height:400px">储值数量</div>
        </div>
        </div>
    </div>
</div> 
</div>
<script>
var date = [<?php if(!empty($members)) { ?>
                <?php foreach ($members as $key => $value) { ?>
                       '<?php echo $value['date']?>',             
                <?php } } ?>];
                
var hongbaonum = [<?php if(!empty($members)) { ?>
                <?php foreach ($members as $key => $value) { ?>
                       <?php echo $value['day_hongbao_num']?>,             
                <?php } }?>];
                    
var hongbaouse = [<?php if(!empty($members)) { ?>
                <?php foreach ($members as $key => $value) { ?>
                       <?php echo $value['day_hongbao_use']?>,             
                <?php } }?>];

var couponsnum = [<?php if(!empty($members)) { ?>
                <?php foreach ($members as $key => $value) { ?>
                       <?php echo $value['day_coupons_num']?>,             
                <?php } }?>];

var couponsuse = [<?php if(!empty($members)) { ?>
                <?php foreach ($members as $key => $value) { ?>
                       <?php echo $value['day_coupons_use']?>,             
                <?php } }?>];
                    
var storedmoney = [<?php if(!empty($members)) { ?>
                <?php foreach ($members as $key => $value) { ?>
                       <?php echo $value['day_stored_money']?>,             
                <?php } }?>];
                    
var storednum = [<?php if(!empty($members)) { ?>
                <?php foreach ($members as $key => $value) { ?>
                       <?php echo $value['day_stored_num']?>,             
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
            var hongbaonum = new Array();
            var hongbaouse = new Array();
            $.each(data, function(n,val){
                date.push(val.date);
                hongbaonum.push(parseFloat(val.day_hongbao_num));
                hongbaouse.push(parseFloat(val.day_hongbao_use));
            });			
            hongbao(date,hongbaonum,hongbaouse);
        }
    });
}

function fun1()
{     
    $.ajax({
        url:'<?php echo Yii::app()->createUrl('mCenter/data/Search')?>',
        type:'get',
        data:{Time : $('#Time1').val()},
        dataType:'json',
        cache: false,
        success: function(data){
            var date = new Array();
            var couponsnum = new Array();
            var couponsuse = new Array();
            $.each(data, function(n,val){
                date.push(val.date);
                couponsnum.push(parseFloat(val.day_coupons_num));
                couponsuse.push(parseFloat(val.day_coupons_use));
            });			
            coupons(date,couponsnum,couponsuse);
        }
    });
}

function fun2()
{     
    $.ajax({
        url:'<?php echo Yii::app()->createUrl('mCenter/data/Search')?>',
        type:'get',
        data:{Time : $('#Time2').val()},
        dataType:'json',
        cache: false,
        success: function(data){
            var date = new Array();
            var storedmoney = new Array();
            var storednum = new Array();
            $.each(data, function(n,val){
                date.push(val.date);
                storedmoney.push(parseFloat(val.day_stored_money));
                storednum.push(parseFloat(val.day_stored_num));
            });			
            stored(date,storedmoney);
            stored1(date,storednum);
        }
    });
}

function hongbao(date,hongbaonum,hongbaouse) {
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
                text: '数量'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },  
        series: [{
            name: '红包领取量',
            data: hongbaonum
        }, {
            name: '红包使用量',
            data: hongbaouse
        }]
    });
}
hongbao(date,hongbaonum,hongbaouse);

function coupons(date,couponsnum,couponsuse){
    $('#container1').highcharts({
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
                text: '数量'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },  
        series: [{
            name: '优惠券领取量',
            data: couponsnum
        }, {
            name: '优惠券使用量',
            data: couponsuse
        }]
    });
}
coupons(date,couponsnum,couponsuse);

function stored(date,storedmoney) {
    $('#container2').highcharts({
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
                text: '储值金额'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },  
        series: [{
            name: '储值金额',
            data: storedmoney
        }]
    });
    
}
stored(date,storedmoney);

function stored1(date,storednum) {
    $('#container3').highcharts({
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
                text: '储值数量'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },  
        series: [{
            name: '储值数量',
            data: storednum
        }]
    });
}
stored1(date,storednum);
</script>    