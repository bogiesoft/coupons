<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>首页</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="stylesheet" type="text/css" href="<?php echo WQ_STATIC_STYLES?>style-bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?php echo WQ_STATIC_STYLES?>style-gj.css">
    <script src="<?php echo WQ_STATIC_JS?>lib/jquery.min.js"></script>
    <script src="<?php echo WQ_STATIC_JS?>lib/jquery.validate.js"></script>
    <script src="<?php echo WQ_STATIC_JS?>lib/messages_zh.min.js"></script>
    <script src="<?php echo WQ_STATIC_JS?>lib/echarts.min.js"></script>
    <script src="<?php echo WQ_STATIC_JS?>lib/jquery-ui.js"></script>
    <script src="<?php echo WQ_STATIC_JS?>lib/select2.min.js"></script>
    <script src="<?php echo WQ_STATIC_MEDIA?>js/layer/layer.js"></script>
    <!--时间插件 开始-->
    <script src="<?php echo WQ_STATIC_JS?>lib/daterangepicker/moment.min.js"></script>
    <script src="<?php echo WQ_STATIC_JS?>lib/daterangepicker/daterangepicker.js"></script>
    <script src="<?php echo WQ_STATIC_JS?>lib/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="<?php echo WQ_STATIC_JS?>lib/bootstrap-datepicker/bootstrap-datepicker.zh-CN.min.js"></script>
    <!--时间插件 结束-->
    <script src="<?php echo WQ_STATIC_JS?>main.js"></script>
    <script src="<?php echo WQ_STATIC_JS?>channelManage.js"></script>

    <script type="text/javascript" src="<?php echo WQ_STATIC_JS?>lib/uploadify/jquery.uploadify.min.js?1"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo WQ_STATIC_JS?>lib/uploadify/uploadify.css?1" />
    <!--[if lt IE 9]>
    <script src="/themes/classic/wq/js/lib/html5shiv.min.js"></script>
    <script src="/themes/classic/wq/js/lib/respond.min.js"></script>
    <script src="/themes/classic/wq/js/lib/selectivizr-min.js"></script>
    <![endif]-->
</head>
<body>

<?php echo $content;?>
<script>
    //iframe菜单跳转
    function indexSrc(leftUrl, mainUrl, mainObj) {
        parent.$('#left').attr('src',leftUrl);
        parent.$('#main').attr('src',mainUrl);

        //显示左边菜单栏
        parent.window.$(".left").show();
        parent.window.$(".backstage").css("border-bottom","1px solid #dedede");
        parent.window.$(".left").next().css({"border":"1px solid #dedede","background":"#fff"});

        mainSelect(mainObj);
    }
    function mainSelect(obj) {
        parent.$('.cur selected').remove('class');
        parent.$('#' + obj).attr('class', 'cur selected');
    }
    $(function(){
        parent.$('li').removeClass('cur selected');
        parent.window.$(".left").hide()
        parent.window.$(".backstage").css("border","none");
        parent.window.$(".left").next().css({"border":"none","background":"transparent"});
    });
</script>
</body>
</html>