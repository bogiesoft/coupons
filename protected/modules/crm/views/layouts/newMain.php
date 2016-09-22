<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>玩券管家</title>
    <!-- wy -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="renderer" content="webkit">
    <link rel="stylesheet" type="text/css" href="<?php echo WQ_STATIC_STYLES?>style-bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?php echo WQ_STATIC_STYLES?>style-gj.css">
    <link rel="stylesheet" href="<?php echo WQ_STATIC_MEDIA?>uploadify/uploadify.css">
<!--    <script src="--><?php ///*echo WQ_STATIC_JS*/?><!--lib/jquery.min.js"></script>-->
<<<<<<< .working
    <script type="text/javascript" src="<?php echo WQ_STATIC_JS?>lib/jquery.min.js"></script>
||||||| .merge-left.r5726
    <script src="<?php echo WQ_STATIC_JS?>lib/jquery.min.11468602.js"></script>
=======
    <script src="<?php echo WQ_STATIC_JS?>lib/jquery.min.js"></script>
>>>>>>> .merge-right.r5741
    <script type="text/javascript" src="<?php echo WQ_STATIC_MEDIA?>uploadify/jquery.uploadify.min.js"></script>
    <script src="<?php echo WQ_STATIC_MEDIA?>js/layer/layer.js"></script>
<<<<<<< .working
    <script src="<?php echo WQ_STATIC_JS?>main.js"></script>
    <script src="<?php echo WQ_STATIC_JS?>lib/jquery.validate.js"></script>
    <script src="<?php echo WQ_STATIC_JS?>lib/messages_zh.min.js"></script>
    <script src="<?php echo WQ_STATIC_JS?>lib/echarts.min.js"></script>
    <script src="<?php echo WQ_STATIC_JS?>lib/select2.min.js"></script>
    <script type="text/javascript" src="<?php echo WQ_STATIC_JS?>lib/daterangepicker/moment.min.js"></script>
    <script type="text/javascript" src="<?php echo WQ_STATIC_JS?>lib/daterangepicker/daterangepicker.js"></script>
    <script src="<?php echo WQ_STATIC_JS?>lib/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="<?php echo WQ_STATIC_JS?>lib/bootstrap-datepicker/bootstrap-datepicker.zh-CN.min.js"></script>
||||||| .merge-left.r5726
    <script src="<?php echo WQ_STATIC_JS?>main.59348484.js"></script>
    <script src="<?php echo WQ_STATIC_JS?>lib/jquery.validate.37393e71.js"></script>
    <script src="<?php echo WQ_STATIC_JS?>lib/messages_zh.min.6979ac84.js"></script>
    <script src="<?php echo WQ_STATIC_JS?>lib/echarts.min.a926dbf4.js"></script>
    <script src="<?php echo WQ_STATIC_JS?>lib/select2.min.e87ca4c3.js"></script>
    <script src="<?php echo WQ_STATIC_JS?>lib/daterangepicker/moment.min.8f78519e.js"></script>
    <script src="<?php echo WQ_STATIC_JS?>lib/daterangepicker/daterangepicker.8f3c2c90.js"></script>
    <script src="<?php echo WQ_STATIC_JS?>lib/bootstrap-datepicker/bootstrap-datepicker.cfa0100d.js"></script>
    <script src="<?php echo WQ_STATIC_JS?>lib/bootstrap-datepicker/bootstrap-datepicker.zh-CN.min.d64daf7a.js"></script>
=======
    <script src="<?php echo WQ_STATIC_JS?>main.js"></script>
    <script src="<?php echo WQ_STATIC_JS?>lib/jquery.validate.js"></script>
    <script src="<?php echo WQ_STATIC_JS?>lib/messages_zh.min.js"></script>
    <script src="<?php echo WQ_STATIC_JS?>lib/echarts.min.js"></script>
    <script src="<?php echo WQ_STATIC_JS?>lib/select2.min.js"></script>
    <script src="<?php echo WQ_STATIC_JS?>lib/daterangepicker/daterangepicker.js"></script>
    <script src="<?php echo WQ_STATIC_JS?>lib/daterangepicker/daterangepicker.js"></script>
    <script src="<?php echo WQ_STATIC_JS?>lib/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="<?php echo WQ_STATIC_JS?>lib/bootstrap-datepicker/bootstrap-datepicker.zh-CN.min.js"></script>
>>>>>>> .merge-right.r5741
    <!--[if lt IE 9]>
    <script src="<?php echo WQ_STATIC_JS?>lib/html5shiv.min.js"></script>
    <script src="<?php echo WQ_STATIC_JS?>lib/respond.min.js"></script>
    <script src="<?php echo WQ_STATIC_JS?>lib/selectivizr-min.js"></script>
    <![endif]-->
    <!-- end -->
</head>
<body class="store">
<?php if ('index' == Yii::app()->getController()->getId() || 'shopIndex' == Yii::app()->getController()->getId()) { ?>

    <div class="head-top">
        <div class="content">
            <!--     	<div class="left">玩券管家：您专属的O2O信息化解决方案</div> -->
            <div class="right">
                欢迎，<?php echo Yii::app() -> session['merchant_name']?>    您当前的版本是<?php echo Yii::app()->session['type_name']?> <?php if(Yii::app()->session['type_id'] == WANQUAN_TYPE_MARKETING){?>(<?php if(Yii::app()->session['if_tryout']== IF_TRYOUT_YES){?>试用版,<?php }?>于<?php echo date('Y年m月d日',strtotime(Yii::app ()->session ['time_limit']));?>到期)<?php }?>，
                <!-- <a href="javascript:;" class="blue">升级</a>
                <a target="_blank" href="<?php echo Yii::app()->createUrl('mCenter/downLoad/downLoad',array('downLoadType'=>DOWNLOAD_TYPE_DOCUMENT))?>" class="blue">帮助中心</a>
                 --><a href="<?php echo Yii::app()->createUrl('mCenter/auth/Logout')?>">安全退出</a>
            </div>
        </div>
    </div>

    <div class="header clearfix">
        <div class="top">
            <div class="logo"><a href="<?php echo Yii::app() -> createUrl('mCenter/index/index')?>"><img src="<?php echo GJ_STATIC_IMAGES?>logo1.png"></img></a></div>
            <div class="header_right">
                <?php $role = Yii::app ()->session ['role'];?>
                <ul>
                    <?php if(($role == WQ_ROLE_MERCHANT) || ($role == WQ_ROLE_MANAGER && in_array('100',Yii::app() -> session['right_arr']))){ ?>
                        <li id="100">
                            <a href="<?php echo Yii::app() -> createUrl('mCenter/index/store')?>">
                                <span class="img"></span>
                                <span class="text">门店管理</span>
                            </a>
                        </li>
                    <?php }?>

                    <?php if(($role == WQ_ROLE_MERCHANT) || ($role == WQ_ROLE_MANAGER && in_array('101',Yii::app() -> session['right_arr']))){ ?>
                        <li id="101">
                            <a href="<?php echo Yii::app() -> createUrl('mCenter/index/finance')?>">
                                <span class="img10"></span>
                                <span class="text">财务管理</span>
                            </a>
                        </li>
                    <?php }?>

                    <?php if(($role == WQ_ROLE_MERCHANT) || ($role == WQ_ROLE_MANAGER && in_array('107',Yii::app() -> session['right_arr']))){ ?>
                        <li id="107">
                            <a <?php if(Yii::app()->session['type_id'] == WANQUAN_TYPE_CASH){ ?>onclick="alert('当前版本没有该权限，请联系服务商进行版本升级！')"<?php }else{?>href="<?php echo Yii::app() -> createUrl('mCenter/index/crm')?>"<?php }?>>
                                <span class="img12"></span>
                                <span class="text">CRM管理</span>
                            </a>
                        </li>
                    <?php }?>

                    <?php if(($role == WQ_ROLE_MERCHANT) || ($role == WQ_ROLE_MANAGER && in_array('102',Yii::app() -> session['right_arr']))){ ?>
                        <li id="102">
                            <a <?php if(Yii::app()->session['type_id'] == WANQUAN_TYPE_CASH){ ?>onclick="alert('当前版本没有该权限，请联系服务商进行版本升级！')"<?php }else{?>href="<?php echo Yii::app() -> createUrl('mCenter/index/common')?>"<?php }?>>
                                <span class="img1"></span>
                                <span class="text">营销管理</span>
                            </a>
                        </li>
                    <?php }?>

                    <?php if(($role == WQ_ROLE_MERCHANT) || ($role == WQ_ROLE_MANAGER && in_array('109',Yii::app() -> session['right_arr']))){ ?>
                        <li id="104">
                            <a <?php if(Yii::app()->session['type_id'] == WANQUAN_TYPE_CASH){ ?>onclick="alert('当前版本没有该权限，请联系服务商进行版本升级！')"<?php }else{?>href="<?php echo Yii::app() -> createUrl('mCenter/index/mall')?>"<?php }?>>
                                <span class="img13"></span>
                                <span class="text">商城管理</span>
                            </a>
                        </li>
                    <?php }?>

                    <?php if(($role == WQ_ROLE_MERCHANT) || ($role == WQ_ROLE_MANAGER && in_array('110',Yii::app() -> session['right_arr']))){ ?>
                        <li id="110">
                            <a <?php if(Yii::app()->session['type_id'] == WANQUAN_TYPE_CASH){ ?>onclick="alert('当前版本没有该权限，请联系服务商进行版本升级！')"<?php }else{?>href="<?php echo Yii::app() -> createUrl('mCenter/index/appmarket')?>"<?php }?>>
                                <span class="img5"></span>
                                <span class="text">应用市场</span>
                            </a>
                        </li>
                    <?php }?>

                    <?php if(($role == WQ_ROLE_MERCHANT) || ($role == WQ_ROLE_MANAGER && in_array('108',Yii::app() -> session['right_arr']))){ ?>
                        <li id="103">
                            <a <?php if(Yii::app()->session['type_id'] == WANQUAN_TYPE_CASH){ ?>onclick="alert('当前版本没有该权限，请联系服务商进行版本升级！')"<?php }else{?>href="<?php echo Yii::app() -> createUrl('mCenter/index/channel')?>"<?php }?>>
                                <span class="img11"></span>
                                <span class="text">渠道管理</span>
                            </a>
                        </li>
                    <?php }?>


                    <?php if(($role == WQ_ROLE_MERCHANT) || ($role == WQ_ROLE_MANAGER && in_array('105',Yii::app() -> session['right_arr']))){ ?>
                        <li id="105">
                            <a <?php if(Yii::app()->session['type_id'] == WANQUAN_TYPE_CASH){ ?>onclick="alert('当前版本没有该权限，请联系服务商进行版本升级！')"<?php }else{?>href="<?php echo Yii::app() -> createUrl('mCenter/index/statistics')?>"<?php }?>>
                                <span class="img4"></span>
                                <span class="text">统计管理</span>
                            </a>
                        </li>
                    <?php }?>
                    <!-- 	             <li> -->
                    <!--	            	<a <?php //if(Yii::app()->session['type_name'] == '收银版'){ ?>onclick="alert('当前版本没有该权限，请联系服务商进行版本升级！')"<?php //}else{?>href="<?php //echo Yii::app() -> createUrl('mCenter/index/duanxin')?>" <?php //}?>>-->
                    <!-- 	                	<span class="img5"></span> -->
                    <!-- 	                    <span class="text">短信管理</span> -->
                    <!-- 	                 </a> -->
                    <!-- 	             </li> -->
                    <?php if(($role == WQ_ROLE_MERCHANT) || ($role == WQ_ROLE_MANAGER && in_array('106',Yii::app() -> session['right_arr']))){ ?>
                        <li id="106">
                            <a href="<?php echo Yii::app() -> createUrl('mCenter/index/install')?>">
                                <span class="img6"></span>
                                <span class="text">系统设置</span>
                            </a>
                        </li>
                    <?php }?>
                </ul>
            </div>
        </div>
    </div>
<?php }?>
<?php echo $content;?>

<?php if ('index' == Yii::app()->getController()->getId() || 'shopIndex' == Yii::app()->getController()->getId()) { ?>
    <!--口碑开店指南begin-->
    <div class="guide">
        <div class="gl" style="display: none">
            <ul>
                <li class="on">1.自助签约<span></span></li>
                <li>2.支付宝授权<span ></span></li>
                <li>3.同步口碑开店<span class=""></span></li>
            </ul>
            <div>
                <div class="guide_nav">
                    <span>如何自助签约支付宝当面付？</span>
                    <p>方式一：使用电脑进入口碑官网，自助申请签约。</p>
                    <p>口碑官网：<a href="http://www.koubei.com"  target="_Blank">http://www.koubei.com</a></p>
                    <p class="waySecond">方式二：使用手机，打开手机端【支付宝】→进入【服务窗】→搜索【支付宝未来商业】进行自助签约。</p>
                    <span><a href="https://doc.open.alipay.com/doc2/detail.htm?treeId=107&articleId=103864&docType=1" class="dl" target="_Blank">签约流程说明</a></span>
                    <button class="btn_sure btn_sure_next">确认已完成签约</button>
                </div>
                <div class="guide_nav gl_active">
                    <div class="guide_second">
                        <img src="<?php echo Yii::app() -> createUrl('mCenter/Install/CreateAuthQrcode');?>">
                        <p>使用收款账号登录支付宝客户端，扫描完成授权</p>
                        <span><a target="_blank" href="<?php echo Yii::app() -> createUrl('mCenter/Install/CreateAuthUrl');?>">网页授权</a></span>
                    </div>
                </div>
                <div class="guide_nav gl_active">
                    <p class="waySecond">在【门店管理】-【添加门店】，进行添加门店，选择同步创建支付宝口碑门店。</p>
                    <?php if(Yii::app ()->session ['role'] == WQ_ROLE_MERCHANT || (Yii::app ()->session ['role'] == WQ_ROLE_MANAGER && in_array('100',Yii::app() -> session['right_arr']))){?><button class="btn_sure" id="btn_sure">去开店</button><?php }?>
                </div>
            </div>
        </div>
        <div class="koubeiLogo"></div>
    </div>
    <script>
        $("#btn_sure").click(function(){
            window.location.replace("<?php echo Yii::app()->createUrl('mCenter/index/Store')?>");
        })
    </script>
    <!--口碑开店指南end-->

    <!--点击客服begin-->
    <div class="backTop">
        <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=2596727167&site=qq&menu=yes" class="service"></a>

    </div>
    <div class="backToptel">
        <div class="tel">
            <span>400-882-9998</span><img src="<?php echo GJ_STATIC_IMAGES ?>service1.png"/>
        </div>
        <script>
            $(".backToptel .tel").hover(
                function(){
                    $(".tel span").show(500)
                },
                function(){
                    $(".tel span").hide()
                }
            )
        </script>
    </div>
    <div class="backCellphone">
        <div class="cellphone"><img src="<?php echo GJ_STATIC_IMAGES ?>service2.png"/></div>
        <div class="phone">
            <img src="<?php echo GJ_STATIC_IMAGES?>qrcode_app.png"/><br/>Android版
        </div>
        <script>
            $(".cellphone").hover(
                function(){
                    $(".phone").show(500);
                },
                function(){
                    $(".phone").hide();
                }
            );
        </script>
    </div>
    <!--点击客服end-->
    <script>
        $("dt").bind("click",function(){
            n=$("dt").index(this)
            $(".level").eq(n).slideToggle();
        });

        $(function() {
            var action = '<?php echo Yii::app()->getController()->getAction()->getId() ?>';
            var index = '0';
            var id = 0;
            if('store' == action) {
                index = '0';
                id = '100';
            }
            if('finance' == action) {
                index = '10';
                id = '101';
            }
            if('crm' == action) {
                index = '13';
                id = '107';
            }
            if('common' == action) {
                index = '1';
                id = '102';
            }
            if('channel' == action) {
                index = '11';
                id = '103';
            }
            if('mall' == action) {
                index = '12';
                id = '104';
            }
            if('statistics' == action) {
                index = '4';
                id = '105';
            }
            if('install' == action) {
                index = '6';
                id = '106';
            }
            if('appmarket' == action) {
                index = '5';
                id = '110';
            }



            $('#'+id).addClass("li0");
            //$(".header_right ul li:eq("+t+")").addClass("li0");
            if (0 == index) {
                $('#'+id).find('a span:eq(0)').attr("class", "img0");
                //$(".header_right ul li:eq("+t+") a span:eq(0)").attr("class", "img0");
            }else {
                //$(".header_right ul li:eq("+t+") a span:eq(0)").attr("class", "img0"+index);
                $('#'+id).find('a span:eq(0)').attr("class", "img0"+index);
            }
        });
    </script>
    <script>
        $("dd").click(function(){
            n=$("dd").index(this);
            $("dd").removeClass("bg");
            $("dd").eq(n).addClass("bg");
        });


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

    </script>

    <div class="footer">
        <p>Copyright@2015玩券版权所有 浙ICP备15022147号-1</p>
    </div>
<?php }?>
</body>
</html>