<div class="head-top">
    <div class="content">
        <!--        <div class="left">玩券管家：您专属的O2O信息化解决方案</div>-->
        <div class="right">
            欢迎，<?php echo Yii::app() -> session['merchant_name']?>    您当前的版本是<?php echo Yii::app()->session['type_name']?> <?php if(Yii::app()->session['type_id'] == WANQUAN_TYPE_MARKETING){?>(<?php if(Yii::app()->session['if_tryout']== IF_TRYOUT_YES){?>试用版,<?php }?>于<?php echo date('Y年m月d日',strtotime(Yii::app ()->session ['time_limit']));?>到期)<?php }?>，
            <a href="javascript:;" class="blue">升级</a>
            <a target="_blank" href="<?php echo Yii::app()->createUrl('mCenter/downLoad/downLoad',array('downLoadType'=>DOWNLOAD_TYPE_DOCUMENT))?>" class="blue">帮助中心</a>
            <a href="<?php echo Yii::app()->createUrl('mCenter/auth/Logout')?>">安全退出</a>
        </div>
    </div>
</div>

<div class="header clearfix">
    <div class="top">
        <div class="logo">
            <a href="<?php echo Yii::app() -> createUrl('index/home/index') ?>">
                <img src="<?php echo GJ_STATIC_IMAGES?>logo1.png">
            </a>
        </div>
        <div class="header_right">
            <?php $role = Yii::app ()->session ['role'];?>
            <ul>
                <?php if(($role == WQ_ROLE_MERCHANT) || ($role == WQ_ROLE_MANAGER && in_array('100',Yii::app() -> session['right_arr']))){ ?>
                    <li id="100">
                        <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/store', array('leftId' => '100_01')) ?>', '<?php echo Yii::app()->createUrl('mCenter/store/storeInfo') ?>')">
                            <span class="img0"></span>
                            <span class="text">门店管理</span>
                        </a>
                    </li>
                <?php }?>

                <?php if(($role == WQ_ROLE_MERCHANT) || ($role == WQ_ROLE_MANAGER && in_array('101',Yii::app() -> session['right_arr']))){ ?>
                    <li id="101">
                        <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/finance', array('leftId' => '101_01')) ?>', '<?php echo Yii::app()->createUrl('finance/finance/tradeList') ?>')">
                            <span class="img1"></span>
                            <span class="text">财务管理</span>
                        </a>
                    </li>
                <?php }?>

                <?php if(($role == WQ_ROLE_MERCHANT) || ($role == WQ_ROLE_MANAGER && in_array('107',Yii::app() -> session['right_arr']))){ ?>
                    <li id="107" <?php if(Yii::app()->session['type_id'] == WANQUAN_TYPE_CASH){ ?> class="cashier-version"<?php }?>>
                        <a <?php if(Yii::app()->session['type_id'] == WANQUAN_TYPE_CASH){ ?>onclick="alert('当前版本没有该权限，请联系服务商进行版本升级！')"<?php }else{?>href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/crm', array('leftId' => '107_01')) ?>', '<?php echo Yii::app()->createUrl('crm/user/userLists') ?>')"<?php }?>>
                            <span class="img2"></span>
                            <span class="text">CRM管理</span>
                        </a>
                    </li>
                <?php }?>

                <?php if(($role == WQ_ROLE_MERCHANT) || ($role == WQ_ROLE_MANAGER && in_array('102',Yii::app() -> session['right_arr']))){ ?>
                    <li id="102" <?php if(Yii::app()->session['type_id'] == WANQUAN_TYPE_CASH){ ?> class="cashier-version"<?php }?>>
                        <a <?php if(Yii::app()->session['type_id'] == WANQUAN_TYPE_CASH){ ?>onclick="alert('当前版本没有该权限，请联系服务商进行版本升级！')"<?php }else{?>href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/common', array('leftId' => '102_01')) ?>', '<?php echo Yii::app()->createUrl('mCenter/stored/storedList') ?>')"<?php }?>>
                            <span class="img3"></span>
                            <span class="text">营销管理</span>
                        </a>
                    </li>
                <?php }?>

                <?php if(($role == WQ_ROLE_MERCHANT) || ($role == WQ_ROLE_MANAGER && in_array('109',Yii::app() -> session['right_arr']))){ ?>
                <?php if (in_array(Yii::app()->session['merchant_id'], array(40,35,61,36,13,320,62,440,504,544,45,1004,1012,840))){?>
                    <li id="104" <?php if(Yii::app()->session['type_id'] == WANQUAN_TYPE_CASH){ ?> class="cashier-version"<?php }?>>
                        <a <?php if(Yii::app()->session['type_id'] == WANQUAN_TYPE_CASH){ ?>onclick="alert('当前版本没有该权限，请联系服务商进行版本升级！')"<?php }else{?>href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/mall', array('leftId' => '104_01')) ?>', '<?php echo Yii::app()->createUrl('mCenter/shopMallHome/ShopMallIndex') ?>')"<?php }?>>
                            <span class="img4"></span>
                            <span class="text">商城管理</span>
                        </a>
                    </li>
                <?php }}?>

                <?php if(($role == WQ_ROLE_MERCHANT) || ($role == WQ_ROLE_MANAGER && in_array('110',Yii::app() -> session['right_arr']))){ ?>
                    <li id="110" <?php if(Yii::app()->session['type_id'] == WANQUAN_TYPE_CASH){ ?> class="cashier-version"<?php }?>>
                        <a <?php if(Yii::app()->session['type_id'] == WANQUAN_TYPE_CASH){ ?>onclick="alert('当前版本没有该权限，请联系服务商进行版本升级！')"<?php }else{?>href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/appmarket', array('leftId' => '110_01')) ?>', '<?php echo Yii::app()->createUrl('mCenter/ShopManagement/OnlineShop') ?>')"<?php }?>>
                            <span class="img6"></span>
                            <span class="text">应用市场</span>
                        </a>
                    </li>
                <?php }?>

                <?php if(($role == WQ_ROLE_MERCHANT) || ($role == WQ_ROLE_MANAGER && in_array('108',Yii::app() -> session['right_arr']))){ ?>
                    <li id="103" <?php if(Yii::app()->session['type_id'] == WANQUAN_TYPE_CASH){ ?> class="cashier-version"<?php }?>>
                        <a <?php if(Yii::app()->session['type_id'] == WANQUAN_TYPE_CASH){ ?>onclick="alert('当前版本没有该权限，请联系服务商进行版本升级！')"<?php }else{?>href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/channel', array('leftId' => '103_01')) ?>', '<?php echo Yii::app()->createUrl('mCenter/fuwu/menu') ?>')"<?php }?>>
                            <span class="img5"></span>
                            <span class="text">渠道管理</span>
                        </a>
                    </li>
                <?php }?>

                <?php if(($role == WQ_ROLE_MERCHANT) || ($role == WQ_ROLE_MANAGER && in_array('105',Yii::app() -> session['right_arr']))){ ?>
                    <li id="105" <?php if(Yii::app()->session['type_id'] == WANQUAN_TYPE_CASH){ ?> class="cashier-version"<?php }?>>
                        <a <?php if(Yii::app()->session['type_id'] == WANQUAN_TYPE_CASH){ ?>onclick="alert('当前版本没有该权限，请联系服务商进行版本升级！')"<?php }else{?>href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/statistics', array('leftId' => '105_01')) ?>', '<?php echo Yii::app()->createUrl('statistics/data/user',array('day'=>'-30day')) ?>')"<?php }?>>
                            <span class="img7"></span>
                            <span class="text">统计管理</span>
                        </a>
                    </li>
                <?php }?>

                <?php if(($role == WQ_ROLE_MERCHANT) || ($role == WQ_ROLE_MANAGER && in_array('106',Yii::app() -> session['right_arr']))){ ?>
                    <li id="106">
                        <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/install', array('leftId' => '106_01')) ?>', '<?php echo Yii::app()->createUrl('mCenter/install/AlipayAuth') ?>')">
                            <span class="img8"></span>
                            <span class="text">系统设置</span>
                        </a>
                    </li>
                <?php }?>
            </ul>
        </div>
    </div>
</div>

<div class="backstage"><!--iframe_index这个新加的样式名-->
    <div class="left">
        <iframe src="<?php echo $this->createUrl('left')?>" style="border:none; width:100%;" scrolling="no" id="left" onload="SetCwinHeight()" frameborder="0" height="580"></iframe>
    </div>

    <div class="kkfmain_r">
        <iframe src="<?php echo $this->createUrl('main')?>" style="border:none; width:100%;" scrolling="no" id="main" onload="SetCwinHeight()" frameborder="0"></iframe>
    </div>
</div>

<div class="footer">
    <p>Copyright@<?php echo date('Y', time())?>玩券版权所有 浙ICP备15022147号-1</p>
</div>