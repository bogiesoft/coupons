<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>玩券管家</title>
    <link href="<?php echo GJ_STATIC_STYLES?>master.css" type="text/css" rel="stylesheet">
    <link href="<?php echo GJ_STATIC_STYLES?>common.css" type="text/css" rel="stylesheet">
    <script type="text/javascript" src="<?php echo GJ_STATIC_JS?>js_gj/jquery-1.11.0.min.js"></script>
    <script src="<?php echo GJ_STATIC_JS?>common.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo GJ_STATIC_JS?>main.js"></script>
    <script type="text/javascript" src="<?php echo GJ_STATIC_JS?>js_gj/masonry.pkgd.min.js"></script>

    <!-- 气泡 -->
    <script type="text/javascript" src="<?php echo GJ_STATIC_JS?>bubble/js/manhua_bubbletips.js"></script>
    <link type="text/css" rel="stylesheet" href="<?php echo GJ_STATIC_JS ?>bubble/css/manhua_bubbletips.css"/>

    <!-- layer -->
    <script type="text/javascript" src="<?php echo GJ_STATIC_JS?>layer/layer.js?1"></script>
<body class="store"></head>

<?php echo $content?>

<script>
    //左侧菜单展开或收缩操作，并把其他已展开的都隐藏掉
    $("dt").bind("click", function() {
        $(this).next().slideToggle().siblings("dd").hide();
    })
    function SetCwinHeight(){
        var iframeid=document.getElementById("main"); //iframe id
        if (document.getElementById){
            if (iframeid && !window.opera){
                if (iframeid.contentDocument && iframeid.contentDocument.body.offsetHeight){
                    iframeid.height = iframeid.contentDocument.body.offsetHeight;
                }else if(iframeid.Document && iframeid.Document.body.scrollHeight){
                    iframeid.height = iframeid.Document.body.scrollHeight;
                }
            }
        }
    }

    $('.blue').click(function(){
        $.ajax({
            url:'<?php echo Yii::app()->createUrl('mCenter/Index/GetAgent')?>',
            type:'get',
            data:{Time : $('#Time1').val()},
            dataType:'json',
            cache: false,
            success: function(data){
                $('.blue').manhua_bubbletips({position : "t",value : 170,content : "请联系您的服务商进行版本升级！</br>您服务商的联系方式为：</br>联系人："+data.name+"</br>联系电话："+data.contact});
            }
        });
    });

    //iframe菜单跳转
    function indexSrc(leftUrl, mainUrl) {
        parent.$('#left').attr('src',leftUrl);
        parent.$('#main').attr('src',mainUrl);

        //显示左边菜单栏
        parent.window.$(".left").show();
        parent.window.$(".backstage").css("border-bottom","1px solid #dedede");
        parent.window.$(".left").next().css({"border":"1px solid #dedede","background":"#fff"});
    }

	//头部加样式
	$(".header_right li").click(function(){
		$(this).addClass("selected").siblings().removeClass("selected");
	})
</script>
</body>
</html>