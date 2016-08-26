<body class="register">
<div class="header clearfix">
    <div class="top">
        <div class="logo"><a href = "<?php echo Yii::app()->createUrl('mCenter/auth/login'); ?>">
        <img src="<?php echo GJ_STATIC_IMAGES ?>logo.png"></a></div>
        <div class="header_right">
            <ul>
                <li>客服热线：400-882-9998</li>
            </ul>
        </div>
    </div>
</div>
<div class="step">
  
</div>
<div class="Wqmain">
    <div class="success"><img src="<?php echo GJ_STATIC_IMAGES ?>retrieve.png"></div>
    <div class="btn"><input type="submit" value="完成" class="btn_com" onclick="location.href='<?php echo Yii::app()->createUrl('mCenter/auth/login') ?>'"></div>
    <div class="btn"><strong><label id="seconds">5</label></strong>秒后自动跳到登录界面</div>
</div>

</body>
<script>
window.onload=init;
function init()
{
   setTimeout("MyAutoRun()",1000);
}

function MyAutoRun(){
	  var b = document.getElementById("seconds");
	   b.innerHTML = (b.innerHTML) - 1;
	   if(b.innerHTML > 0){
		   init();
	   }else{
	     redirect();
	   }
}

    function redirect()
    {
        window.location.href="<?php echo Yii::app()->createUrl('mCenter/auth/login')?>";
    }
</script>
