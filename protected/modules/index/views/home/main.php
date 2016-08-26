<div class="home-container">
    <div class="home-left">
        <!--        <div class="home-warning">-->
        <!--            <h5>提醒：当前玩券营销版本即将于2016年7月14日到期，请进行续费升级</h5>-->
        <!--            <a href="" class="fr home-update-now"><i class="icon-home-update"></i>立即升级</a>-->
        <!--        </div>-->
        <div class="home-left-inner">
            <div class="home-business-info">
                <div class="avatar">
                    <img src="<?php echo !empty($logo_img) ? IMG_GJ_LIST . $logo_img : WQ_STATIC_IMAGES . 'home-logo.jpg' ?>" id="img_logo">
                    <input type="file" id="upload" class="upload">
                </div>
                <div class="business-meta">
                    <h3 class="store-name"><?php echo $model -> wq_m_name ?></h3>
                    <span class="pl40">总门店
                        <?php echo !empty($merchant_data['store_num']) ? $merchant_data['store_num'] : 0 ?>
                        <em class="text-gray">
                            （其中营销版<?php echo !empty($merchant_data['store_marketing_num']) ? $merchant_data['store_marketing_num'] : 0 ?>家，
                            收银版<?php echo !empty($merchant_data['store_cashier_num']) ? $merchant_data['store_cashier_num'] : 0 ?>家）
                        </em></span>
                    <ul class="opened-fn mt20">
                        <li class="first">已开通：</li>
                        <li <?php if ($merchant_data['alipay_version_status'] == IS_NO) { ?> class="active" <?php } ?>>
                            <i class="icon-fn icon-alipay"></i>支付宝支付</li>
                        <li <?php if ($merchant_data['alipay_service_status'] == IS_NO) { ?> class="active" <?php } ?>>
                            <i class="icon-fn icon-alipay-window"></i>支付宝服务窗</li>
                        <li  <?php if ($merchant_data['wxpay_status'] == IS_NO) { ?> class="active" <?php } ?>>
                            <i class="icon-fn icon-wechat-pay"></i>微信支付</li>
                        <li  <?php if ($merchant_data['wechat_status'] == IS_NO) { ?> class="active" <?php } ?>>
                            <i class="icon-fn icon-wechat-account"></i>微信公众号</li>
                        <li  <?php if ($merchant_data['alipay_store_status'] == IS_NO) { ?> class="active" <?php } ?>>
                            <i class="icon-fn icon-kb"></i>口碑开店</li>
                    </ul>
                </div>
            </div>
            <?php if ($model -> if_show_set == IS_NO) { ?>
                <div class="home-main-item">
                    <h4 class="bg-title">系统指南</h4>

                    <div class="home-system-guide mt15">
                        <div class="home-warning">
                            <h5>欢迎使用玩券管家，请根据版本需要完成以下设置</h5>
                            <a href="javascript:;" class="fr fw js-setting">设置完成，不再提醒</a>
                        </div>
                        <div class="row">
                            <div class="col-md-6 first">
                                <h5 class="mb20 fw mt20">收银版</h5>
                                <ul class="version-item">
                                    <li class="<?php echo $merchant_data['alipay_auth_status'] == IS_NO ? '' : 'active'?>">
                                        <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/install', array('leftId' => '106_01')) ?>', '<?php echo Yii::app()->createUrl('mCenter/Install/AlipayAuth') ?>', 106)">支付宝授权</a></li>
                                    <li class="ml20 <?php echo $merchant_data['alipay_version_status'] == IS_NO ? '' : 'active'?>">
                                        <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/install', array('leftId' => '106_02')) ?>', '<?php echo Yii::app()->createUrl('mCenter/Install/SetAlipay') ?>', 106);">支付宝当面付</a></li>
                                    <li class="<?php echo $merchant_data['wxpay_status'] == IS_NO ? '' : 'active'?>">
                                        <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/install', array('leftId' => '106_04')) ?>', '<?php echo Yii::app()->createUrl('mCenter/Install/SetWechatPay') ?>', 106)">微信支付设置</a></li>
                                    <li class="ml20 <?php echo $merchant_data['store_num'] = 0 ? '' : 'active'?>">
                                        <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/store', array('leftId' => '100_03')) ?>', '<?php echo Yii::app()->createUrl('mCenter/store/addStore') ?>', 100)">添加门店</a></li>
                                    <li class="<?php echo $merchant_data['operator_status'] == IS_NO ? '' : 'active'?>">
                                        <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/store', array('leftId' => '106_05')) ?>', '<?php echo Yii::app()->createUrl('mCenter/operator/operator') ?>', 100)">添加操作员</a></li>
                                    <li class="ml20 <?php echo $merchant_data['trade_status'] == IS_NO ? '' : 'active'?>">
                                        <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/store', array('leftId' => '106_10')) ?>', '<?php echo Yii::app()->createUrl('mCenter/store/tradeNoticeList') ?>', 100)">收款通知</a></li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h5 class="mb20 fw mt20">营销版</h5>
                                <ul class="version-item">
                                    <li class="<?php echo $merchant_data['alipay_service_status'] == IS_NO ? '' : 'active'?>">
                                        <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/install', array('leftId' => '106_03')) ?>', '<?php echo Yii::app()->createUrl('mCenter/Install/SetFuWuAlipay') ?>', 106)">支付宝服务窗授权</a></li>
                                    <li class="ml20 <?php echo $merchant_data['alipay_menu_status'] == IS_NO ? '' : 'active'?>">
                                        <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/channel', array('leftId' => '103_01')) ?>', '<?php echo Yii::app()->createUrl('mCenter/fuwu/menu') ?>', 103)">服务窗菜单管理</a></li>
                                    <li class="<?php echo $merchant_data['wechat_auth_status'] == IS_NO ? '' : 'active'?>">
										<a href="javascript:void(0);">微信公众号授权</a></li>
                                <!--        <a href="javascript:void(0);"  onclick="indexSrc('<?php //echo Yii::app()->createUrl('index/home/install', array('leftId' => '106_11')) ?>', '<?php //echo Yii::app()->createUrl('mCenter/Install/AuthWechat') ?>', 106)">微信公众号授权</a></li>-->  
                                   <li class="ml20 <?php echo $merchant_data['wechat_menu_status'] == IS_NO ? '' : 'active'?>">
                                        <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/channel', array('leftId' => '103_03')) ?>', '<?php echo Yii::app()->createUrl('mCenter/wechat/menu') ?>', 103)">公众号菜单管理</a></li>
                                    <li class="<?php echo $merchant_data['level_status'] == IS_NO ? '' : 'active'?>">
                                        <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/crm', array('leftId' => '107_02')) ?>', '<?php echo Yii::app()->createUrl('crm/userGrade/setUserGrade') ?>', 107)">会员等级设置</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="home-main-item">
                <h4 class="bg-title">数据预览</h4>

                <div class="row home-data-overview mt10">
                    <div class="col-md-4 home-trade-data">
                        <ul>
                            <li class="fw">今日交易数据</li>
                            <li class="mt20">
                                <span class="title">交易总额：</span>
                                <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/statistics') ?>', '<?php echo Yii::app()->createUrl('statistics/data/finance',array('day'=>'-30day')) ?>', 105)" class="num"><?php echo $statistics_data['yesterday_new_trade_actual_money'] ?></a>
                            </li>
                            <li class="mt15">
                                <span class="title">交易数量：</span>
                                <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/statistics') ?>', '<?php echo Yii::app()->createUrl('statistics/data/finance',array('day'=>'-30day')) ?>', 105)" class="num"><?php echo $statistics_data['yesterday_new_trade_num'] ?></a>
                            </li>
                            <li class="mt15">
                                <span class="title">每笔平均：</span>
                                <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/statistics') ?>', '<?php echo Yii::app()->createUrl('statistics/data/finance',array('day'=>'-30day')) ?>', 105)" class="num">
                                    <?php if (!empty($statistics_data['yesterday_new_trade_actual_money'])
                                        && !empty($statistics_data['yesterday_new_trade_num'])) { ?>
                                        <?php echo round($statistics_data['yesterday_new_trade_actual_money'] /
                                            $statistics_data['yesterday_new_trade_num'], 2) ?>
                                    <?php } else {echo 0;}  ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-4 home-trade-data">
                        <ul>
                            <li class="fw">本月交易数据</li>
                            <li class="mt20">
                                <span class="title">交易总额：</span>
                                <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/statistics') ?>', '<?php echo Yii::app()->createUrl('statistics/data/finance',array('day'=>'-30day')) ?>', 105)" class="num"><?php echo $statistics_data['month_new_trade_actual_money'] ?></a>
                            </li>
                            <li class="mt15">
                                <span class="title">交易数量：</span>
                                <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/statistics') ?>', '<?php echo Yii::app()->createUrl('statistics/data/finance',array('day'=>'-30day')) ?>', 105)" class="num"><?php echo $statistics_data['month_new_trade_num'] ?></a>
                            </li>
                            <li class="mt15">
                                <span class="title">每笔平均：</span>
                                <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/statistics') ?>', '<?php echo Yii::app()->createUrl('statistics/data/finance',array('day'=>'-30day')) ?>', 105)" class="num">
                                    <?php if (!empty($statistics_data['month_new_trade_actual_money'])
                                        && !empty($statistics_data['month_new_trade_num'])) { ?>
                                        <?php echo round($statistics_data['month_new_trade_actual_money'] /
                                            $statistics_data['month_new_trade_num'], 2) ?>
                                    <?php } else {echo 0;}  ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-4 home-trade-data">
                        <ul>
                            <li class="fw">累计交易数据</li>
                            <li class="mt20">
                                <span class="title">交易总额：</span>
                                <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/statistics') ?>', '<?php echo Yii::app()->createUrl('statistics/data/finance',array('day'=>'-30day')) ?>', 105)" class="num"><?php echo $statistics_data['total_trade_actual_money'] ?></a>
                            </li>
                            <li class="mt15">
                                <span class="title">交易数量：</span>
                                <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/statistics') ?>', '<?php echo Yii::app()->createUrl('statistics/data/finance',array('day'=>'-30day')) ?>', 105)" class="num"><?php echo $statistics_data['total_trade_num'] ?></a>
                            </li>
                            <li class="mt15">
                                <span class="title">每笔平均：</span>
                                <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/statistics') ?>', '<?php echo Yii::app()->createUrl('statistics/data/finance',array('day'=>'-30day')) ?>', 105)" class="num">
                                    <?php if (!empty($statistics_data['total_trade_actual_money'])
                                        && !empty($statistics_data['total_trade_num'])) { ?>
                                        <?php echo round($statistics_data['total_trade_actual_money'] /
                                            $statistics_data['total_trade_num'], 2) ?>
                                    <?php } else {echo 0;} ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row home-data-overview">
                    <div class="col-md-4 home-user-data">
                        <ul>
                            <li class="fw">今日新增</li>
                            <li class="mt20">
                                <span class="title">微信粉丝：</span>
                                <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/statistics') ?>', '<?php echo Yii::app() -> createUrl('statistics/data/user',array('day'=>'-30day')) ?>', 105)" class="num"><?php echo $statistics_data['yesterday_new_wechatfans_num'] ?></a>
                            </li>
                            <li class="mt15">
                                <span class="title">支付宝粉丝：</span>
                                <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/statistics') ?>', '<?php echo Yii::app() -> createUrl('statistics/data/user',array('day'=>'-30day')) ?>', 105)" class="num"><?php echo $statistics_data['yesterday_new_alipayfans_num'] ?></a>
                            </li>
                            <li class="mt15">
                                <span class="title">注册会员：</span>
                                <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/statistics') ?>', '<?php echo Yii::app() -> createUrl('statistics/data/user',array('day'=>'-30day')) ?>', 105)" class="num"><?php echo $statistics_data['yesterday_new_member_num'] ?></a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-4 home-user-data">
                        <ul>
                            <li class="fw">本月新增</li>
                            <li class="mt20">
                                <span class="title">微信粉丝：</span>
                                <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/statistics') ?>', '<?php echo Yii::app() -> createUrl('statistics/data/user',array('day'=>'-30day')) ?>', 105)" class="num"><?php echo $statistics_data['month_new_wechatfans_num'] ?></a>
                            </li>
                            <li class="mt15">
                                <span class="title">支付宝粉丝：</span>
                                <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/statistics') ?>', '<?php echo Yii::app() -> createUrl('statistics/data/user',array('day'=>'-30day')) ?>', 105)" class="num"><?php echo $statistics_data['month_new_alipayfans_num'] ?></a>
                            </li>
                            <li class="mt15">
                                <span class="title">注册会员：</span>
                                <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/statistics') ?>', '<?php echo Yii::app() -> createUrl('statistics/data/user',array('day'=>'-30day')) ?>', 105)" class="num"><?php echo $statistics_data['month_new_member_num'] ?></a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-4 home-user-data">
                        <ul>
                            <li class="fw">累计用户</li>
                            <li class="mt20">
                                <span class="title">微信粉丝：</span>
                                <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/statistics') ?>', '<?php echo Yii::app() -> createUrl('statistics/data/user',array('day'=>'-30day')) ?>', 105)" class="num"><?php echo $statistics_data['total_wechatfans_num'] ?></a>
                            </li>
                            <li class="mt15">
                                <span class="title">支付宝粉丝：</span>
                                <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/statistics') ?>', '<?php echo Yii::app() -> createUrl('statistics/data/user',array('day'=>'-30day')) ?>', 105)" class="num"><?php echo $statistics_data['total_alipayfans_num'] ?></a>
                            </li>
                            <li class="mt15">
                                <span class="title">注册会员：</span>
                                <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/statistics') ?>', '<?php echo Yii::app() -> createUrl('statistics/data/user',array('day'=>'-30day')) ?>', 105)" class="num"><?php echo $statistics_data['total_member_num'] ?></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="home-main-item">
                <h4 class="bg-title">常用功能</h4>

                <div class="row home-common-fn mt10">
                    <div class="m-col-md">
                        <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/finance', array('leftId' => '101_01')) ?>', '<?php echo Yii::app()->createUrl('finance/finance/tradeList') ?>', 101)">
                            <i class="icon-common-fn icon-finance-manage"></i>
                            <span>财务管理</span>
                        </a>
                    </div>
					<?php if(Yii::app ()->session ['type_id'] == 2 && strtotime(Yii::app ()->session ['time_limit']) > time()){?>
                    <div class="m-col-md">
                        <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/crm', array('leftId' => '107_01')) ?>', '<?php echo Yii::app()->createUrl('crm/user/userLists') ?>', 107)">
                            <i class="icon-common-fn icon-user-manage"></i>
                            <span>用户管理</span>
                        </a>
                    </div>
                    <div class="m-col-md">
                        <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/common', array('leftId' => '102_01')) ?>', '<?php echo Yii::app()->createUrl('mCenter/stored/storedList') ?>', 102)">
                            <i class="icon-common-fn icon-value-act"></i>
                            <span>储值活动</span>
                        </a>
                    </div>
                    <div class="m-col-md">
                        <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/channel', array('leftId' => '103_03')) ?>', '<?php echo Yii::app()->createUrl('mCenter/wechat/menu') ?>', 103)">
                            <i class="icon-common-fn icon-wechat-menu"></i>
                            <span>微信菜单</span>
                        </a>
                    </div>
                    <div class="m-col-md">
                        <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/channel', array('leftId' => '103_01')) ?>', '<?php echo Yii::app()->createUrl('mCenter/fuwu/menu') ?>', 103)">
                            <i class="icon-common-fn icon-alipay-menu"></i>
                            <span>服务窗菜单</span>
                        </a>
                    </div>
                </div>
                <div class="row home-common-fn">
                    <div class="m-col-md">
                        <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/statistics') ?>', '<?php echo Yii::app()->createUrl('statistics/data/finance',array('day'=>'-30day')) ?>', 105)">
                            <i class="icon-common-fn icon-finance-count"></i>
                            <span>财务统计</span>
                        </a>
                    </div>
                    <div class="m-col-md">
                        <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/statistics') ?>', '<?php echo Yii::app() -> createUrl('statistics/data/user',array('day'=>'-30day')) ?>', 105)">
                            <i class="icon-common-fn icon-user-count"></i>
                            <span>用户统计</span>
                        </a>
                    </div>
                    <div class="m-col-md">
                        <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/common', array('leftId' => '102_03')) ?>', '<?php echo Yii::app() -> createUrl('mCenter/cardCoupons/CouponList') ?>', 102)">
                            <i class="icon-common-fn icon-coupon"></i>
                            <span>优惠券</span>
                        </a>
                    </div>
                    <div class="m-col-md">
                        <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/channel', array('leftId' => '103_05')) ?>', '<?php echo Yii::app() -> createUrl('mCenter/broadcast/broadcasting') ?>', 103)">
                            <i class="icon-common-fn icon-broadcast"></i>
                            <span>群发广播</span>
                        </a>
                    </div>
                    <div class="m-col-md">
                        <a href="javascript:void(0);" onclick="indexSrc('<?php echo Yii::app()->createUrl('index/home/channel', array('leftId' => '103_06')) ?>', '<?php echo Yii::app() -> createUrl('mCenter/fuwu/materiaList') ?>', 103)">
                            <i class="icon-common-fn icon-material-manage"></i>
                            <span>素材管理</span>
                        </a>
                    </div>
					<?php }?>
                </div>
            </div>
<!--            <div class="home-main-item">-->
<!--                <h4 class="bg-title">营销活动</h4>-->
<!---->
<!--                <div class="row home-common-fn mt10">-->
<!--                    <div class="m-col-md">-->
<!--                        <a href="--><?php //echo Yii::app() -> createUrl('marketing/marketing/marketingPrecision'); ?><!--">-->
<!--                            <i class="icon-common-fn icon-jing"></i>-->
<!--                            <span>精准营销</span>-->
<!--                        </a>-->
<!--                    </div>-->
<!--                    <div class="m-col-md">-->
<!--                        <a href="--><?php //echo Yii::app() -> createUrl('mCenter/fuwu/menu'); ?><!--">-->
<!--                            <i class="icon-common-fn icon-xin"></i>-->
<!--                            <span>新会员赠券</span>-->
<!--                        </a>-->
<!--                    </div>-->
<!--                    <div class="m-col-md">-->
<!--                        <a href="--><?php //echo Yii::app() -> createUrl('mCenter/fuwu/menu'); ?><!--">-->
<!--                            <i class="icon-common-fn icon-lao"></i>-->
<!--                            <span>老会员赠券</span>-->
<!--                        </a>-->
<!--                    </div>-->
<!--                    <div class="m-col-md">-->
<!--                        <a href="--><?php //echo Yii::app() -> createUrl('mCenter/fuwu/menu'); ?><!--">-->
<!--                            <i class="icon-common-fn icon-liu"></i>-->
<!--                            <span>流失客户赠券</span>-->
<!--                        </a>-->
<!--                    </div>-->
<!--                    <div class="m-col-md">-->
<!--                        <a href="--><?php //echo Yii::app() -> createUrl('mCenter/fuwu/menu'); ?><!--">-->
<!--                            <i class="icon-common-fn icon-jie"></i>-->
<!--                            <span>节日赠券</span>-->
<!--                        </a>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
        </div>
    </div>
    <div class="home-right">
        <!--        <div class="home-right-item">-->
        <!--            <div class="home-notice-title">-->
        <!--                <h5 class="fw">公告</h5>-->
        <!--                <a href="" class="fr">更多</a>-->
        <!--            </div>-->
        <!--            <ul class="home-notice-list">-->
        <!--                <li>-->
        <!--                    <a href="">-->
        <!--                        <time>07.19</time>-->
        <!--                        <span class="notice-title">版本更新版本更新版本新版本新版本更新新</span>-->
        <!--                    </a>-->
        <!--                </li>-->
        <!--                <li>-->
        <!--                    <a href="">-->
        <!--                        <time>07.19</time>-->
        <!--                        <span class="notice-title">版本更新版本更新版本更新新</span>-->
        <!--                    </a>-->
        <!--                </li>-->
        <!--                <li>-->
        <!--                    <a href="">-->
        <!--                        <time>07.19</time>-->
        <!--                        <span class="notice-title">版本更新版本更新版本更新新</span>-->
        <!--                    </a>-->
        <!--                </li>-->
        <!--                <li>-->
        <!--                    <a href="">-->
        <!--                        <time>07.19</time>-->
        <!--                        <span class="notice-title">版本更新版本更新版本更新新</span>-->
        <!--                    </a>-->
        <!--                </li>-->
        <!--            </ul>-->
        <!--        </div>-->
        <div class="home-right-item">
            <div class="home-download-title">
                <i class="icon-logo"></i>
                <span>收银台下载</span>
            </div>
            <ul class="home-download-list">
                <li class="mt10">
                    <a href="javascript:;"><i class="icon-android"></i></a>
                    <i class="icon-download-tip android-qrcode"></i>
                </li>
                <li class="mt10">
                    <a href="javascript:;"><i class="icon-ios"></i></a>
                    <i class="icon-download-tip ios-qrcode"></i>
                </li>
                <li class="mt10 mb10">
                    <a href="http://upload.51wanquan.com/exe/wanquan_syt-1.0.3.zip"><i class="icon-pc"></i></a>
                    <i class="icon-download-tip"></i>
                </li>
            </ul>
        </div>
        <div class="home-right-item mt10">
            <ul class="home-contact-list">
                <li>
                    <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=2596727167&site=qq&menu=yes">
                        <i class="icon-qq"></i>
                        <span>在线客服</span>
                    </a>
                </li>
                <!--<li>-->
                <!--<a href="">-->
                <!--<i class="icon-message"></i>-->
                <!--<span>问题反馈</span>-->
                <!--</a>-->
                <!--</li>-->
                <li class="last">
                    <i class="icon-phone"></i>
                    <span>客服电话</span>

                    <p class="phone">400-882-9998</p>
                </li>
            </ul>
        </div>
    </div>
</div>
<script>
    $(function () {
        $(".js-setting").click(function () {
            var self = $(this);
            layer.confirm('确定已经完成全部设置?', {
                title: "温馨提示",
                offset: 100,
                btn: ['确定', '取消'] //按钮
            }, function () {
                layer.msg('成功隐藏提示！', {offset: 100, time: 1000});
                self.parents(".home-main-item").slideUp();
                $.ajax({
                    'url' : '<?php echo $this -> createUrl('setShow') ?>',
                    'type' : 'post',
                    'data' : {'type' : <?php echo IS_YES ?>},
                    'success' : function () {

                    }
                })
            });

        });
    });

    $(function () {
        $('#upload').uploadify({
            uploader: '<?php echo UPLOAD_TO_PATH ?>',// 服务器处理地址
            swf: '<?php echo WQ_STATIC_JS ?>' + 'lib/uploadify/uploadify.swf',
            buttonText: "",//按钮文字
            height: 34,  //按钮高度
            width: 82, //按钮宽度
            multi: true,
            fileSizeLimit: '3mb',
            fileTypeExts: "<?php echo UPLOAD_IMG_TYPE ?>",//允许的文件类型
            fileTypeDesc: "请选择图片文件", //文件说明
            formData: {'folder': '<?php echo IMG_GJ_FOLDER ?>'}, //提交给服务器端的参数
            onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
                eval("var jsondata = " + data + ";");
                var key = jsondata['key'];
                var name = file.name;
                var fileName = jsondata['fileName'];
                var imgsrc = '<?php echo IMG_GJ_LIST?>' + fileName;
                $.ajax({
                    'url' : '<?php echo $this -> createUrl('saveImg') ?>',
                    'type' : 'get',
                    'data' : {'fileName' : fileName},
                    'success' : function(data) {
                        alert("上传Logo成功");
                        $('#img_logo').attr('src', imgsrc);
                    },
                    'error' : function() {
                        alert('上传Logo失败, 请稍后重试!');
                    }
                });
            },
            onError: function (event, queueId, fileObj, errorObj) {
                alert(event, queueId, fileObj, errorObj);
            }
        });
    })



</script>