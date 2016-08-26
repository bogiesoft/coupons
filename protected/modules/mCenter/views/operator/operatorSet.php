<script src="<?php echo GJ_STATIC_JS."gj.js"?>"></script>
<link type="text/css" rel="stylesheet" href="<?php echo GJ_STATIC_STYLES?>jquery-ui-1.10.0.custom.css" />
<script type="text/javascript" src="<?php echo GJ_STATIC_JS?>js_gj/demo.js"></script>
<script type="text/javascript" src="<?php echo GJ_STATIC_JS?>js_gj/docs.js"></script>
<script type="text/javascript" src="<?php echo GJ_STATIC_JS?>js_gj/bootstrap.min.js"></script>
<script src="<?php echo GJ_STATIC_JS?>js_gj/jquery-ui-1.10.0.custom.min.js" type="text/javascript"></script>

<div class="kkfm_r_inner">
    <div class="top">
        <div class="name">操作员设置</div>
    </div>
    <div class="operatorSet">
        <div class="hd">退款权限时间<span>(在收款成功后，操作员可在退款权限时间内给用户退款；最长可设置退款权限时间为90天)</span></div>
        <form action="" method="post">
        <div class="bd">
        	<div class="filed">
            	<div class="label">店长退款时间</div>
                <div class="text">                    
                    <input type="text" class="txt" id="spinner"  name="day" value="<?php echo $operatorset['day']?>" onkeypress="fun()"  onkeyup="value=value.replace(/[^\d]/g,'')"  onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" maxlength="2">天
                    <input type="text" class="txt" id="spinner1" name="hour" value="<?php echo $operatorset['hour']?>" onkeyup="value=value.replace(/[^\d]/g,'')" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" maxlength="2">小时
                    <input type="text" class="txt" id="spinner2" name="clock" value="<?php echo $operatorset['clock']?>" onkeyup="value=value.replace(/[^\d]/g,'')" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" maxlength="2">分钟
                </div>
            </div>
            <div class="filed">
            	<div class="label">店员退款时间</div>
                <div class="text">
                    <input type="text" class="txt" id="spinner3" name="store_day" value="<?php echo $operatorset['store_day']?>" onkeyup="value=value.replace(/[^\d]/g,'')" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" maxlength="2">天
                    <input type="text" class="txt" id="spinner4" name="store_hour" value="<?php echo $operatorset['store_hour']?>" onkeyup="value=value.replace(/[^\d]/g,'')" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" maxlength="2">小时
                    <input type="text" class="txt" id="spinner5" name="store_clock" value="<?php echo $operatorset['store_clock']?>" onkeyup="value=value.replace(/[^\d]/g,'')" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" maxlength="2">分钟
                </div>
            </div>
            <div class="filed">
            	<div class="label"> </div>
                <div class="text">
                	<input type="submit" class="btn_com_gray" value="确 定">
                </div>
            </div>
        </div>
        </form>
    </div>
</div> 
<script>

    var spinner = $( "#spinner" ).spinner({min : 0, max : 90, step : 1});
    var spinner = $( "#spinner1" ).spinner({min : 0, max : 23, step : 1});
    var spinner = $( "#spinner2" ).spinner({min : 0, max : 59, step : 1});
    var spinner = $( "#spinner3" ).spinner({min : 0, max : 90, step : 1});
    var spinner = $( "#spinner4" ).spinner({min : 0, max : 23, step : 1});
    var spinner = $( "#spinner5" ).spinner({min : 0, max : 59, step : 1});
</script>

