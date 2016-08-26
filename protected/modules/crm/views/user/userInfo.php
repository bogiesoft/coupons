<div class="kkfm_r_inner">
    <div class="storeManage">
        <div class="top">
            <div class="name">
                用户详情
            </div>
        </div>
        <div class="bus-contant">
            <div class="user_detail_nav">
                <ul class="wq_btn_group">
                    <li onClick="window.self.location.href='<?php echo $this->createUrl('userInfo', array('id' => $_GET['id'])) ?>'"
                        class="active">用户信息
                    </li>
                    <li onClick="window.self.location.href='<?php echo $this->createUrl('userExpenses', array('id' => $_GET['id'])) ?>'">
                        消费记录
                    </li>
                    <li onClick="window.self.location.href='<?php echo $this->createUrl('userPoints', array('id' => $_GET['id'])) ?>'">
                        积分记录
                    </li>
                    <li onClick="window.self.location.href='<?php echo $this->createUrl('userStored', array('id' => $_GET['id'])) ?>'">
                        储值记录
                    </li>
                    <!--<li onClick="window.self.location.href='<?php echo $this->createUrl('userPurchase', array('id' => $_GET['id'])) ?>'">商城购买记录</li>-->
                </ul>
            </div>
            <div class="user_detail">
                <div class="user_detail_l_wrap">
                    <div class="user_detail_l">
                        <div class="user_detail_item">
                            <h3>基本信息</h3>
                            <div class="user_detail_box">
                                <div class="user_info_wrap clearfix">
                                    <div class="avatar">
                                        <?php
                                        $defaultface = GJ_STATIC_IMAGES . 'face_man.png';
                                        if ($user->sex == SEX_MALE)
                                            $defaultface = GJ_STATIC_IMAGES . 'face_man.png';
                                        if ($user->sex == SEX_FEMALE)
                                            $defaultface = GJ_STATIC_IMAGES . 'face_woman.png';
                                        $src = $user->avatar ? $user->avatar : $defaultface;
                                        ?>
                                        <img src="<?php echo $src ?>">
                                    </div>
                                    <div class="user_info">
                                        <p class="user_name">
                                            <span class="real_name"><?php echo $user->name ?></span>
                                            <span class="nick_name"><?php echo $user->nickname ?></span>
                                            <!-- 如果为女性将class修改为female -->
                                            <span class="<?php if ($user->sex == SEX_MALE) echo 'male';
                                            if ($user->sex == SEX_FEMALE) echo 'female'; ?>"></span>
                                        </p>
                                        <p class="phone">
                                            <span>手机号：<?php echo $user->account ?></span><span>会员卡号：<?php echo $user->membership_card_no ?></span>
                                        </p>
                                        <?php if ($user->birthday) { ?>
                                            <p class="birth">
                                            <?php echo $user->birthday ? date('Y.m.d', strtotime($user->birthday)) : '1970.01.01' ?>
                                            </p>
                                        <?php } ?>
                                        <p><span class="user_info_name">地址：<?php echo $user->address ?></span></p>
                                    </div>
                                </div>
                                <ul class="consume_info">
                                    <li>
                                        <span>累计消费金额</span>
                                        <span class="number">
                                        <?php echo $total_store_xf_money ? $total_store_xf_money : 0; ?>
                                        <?php /*echo $user->total_trade ? $user->total_trade : 0; */?>
                                        </span>
                                    </li>
                                    <li>
                                        <span>累计消费次数</span>
                                        <span class="number"><?php echo $total_store_xf_num ?></span>
                                    </li>
                                    <li>
                                        <span>可用积分</span>
                                        <span class="number"><?php echo $user->points ? $user->points : 0 ?></span>
                                    </li>
                                    <li>
                                        <span>累计积分</span>
                                        <span class="number">
                                            <?php echo $user_total_get_score ? $user_total_get_score : 0; ?>
                                            <?php /*echo $user->total_points ? $user->total_points : 0; */?>
                                        </span>
                                    </li>
                                    <li>
                                        <span>储值金额</span>
                                        <span class="number"><?php echo $user->money ? $user->money : 0 ?></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <?php
                        if (!empty($user->grade_name)) {
                            ?>
                            <div class="user_detail_item">
                                <div class="user_detail_box">
                                    <div class="member_header">
                                        <span class="member_level"><?php echo $user->grade_name ?></span>
                                        注册时间：<?php echo $user->regist_time ? $user->regist_time : '' ?>
                                    </div>
                                    <div class="member_card">
                                        <div class="card_preview">
                                            <?php
                                            $membercard_img_src = $user->membercard_img;
                                            if (strpos($membercard_img_src, 'style') === 0)
                                                $membercard_img_src = GJ_STATIC_IMAGES . 'card/' . $membercard_img_src;
                                            else
                                                $membercard_img_src = IMG_GJ_LIST . $membercard_img_src;
                                            ?>
                                            <img src="<?php echo $membercard_img_src ?>">
                                            <h3><?php echo $user->grade_name ?></h3>
                                        </div>
                                        <div class="member_growth">
                                            <h3>会员成长记录</h3>
                                            <?php
                                            if (!empty($user->growupRecord)) {
                                                foreach ($user->growupRecord as $record) {
                                                    ?>
                                                    <p><?php echo $record['user_grade_name'] ?>
                                                        ：<?php echo $record['create_time'] ?></p>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        <div class="user_detail_item">
                            <h3>更多信息</h3>
                            <div class="user_detail_box">
                                <ul class="user_info_more">
                                    <li class="group">所在分组：
                                        <?php
                                        if (!empty($user->group)) {
                                            $arr = array();
                                            foreach ($user->group as $v) {
                                                $group = UserGroup::model()->findAllByPk($v->group_id);
                                                $arr[] = $group[0]->name;
                                            }
                                            echo implode('、', $arr);
                                        } else {
                                            echo '无';
                                        }
                                        ?>
                                    </li>
                                    <li>
                                        <span>公众号关注时间：<?php echo $user->rel_wechat_subscribe_time ? $user->rel_wechat_subscribe_time : '' ?></span>
                                        <span>最后登录时间：<?php echo $user->login_time ? $user->login_time : '' ?></span>
                                    </li>
                                    <li>
                                        <span>服务窗关注时间：<?php echo $user->rel_alipay_subscribe_time ? $user->rel_alipay_subscribe_time : '' ?></span>
                                        <span>最后登录客户端：<?php echo $user->login_client ? $user_login_clients[$user->login_client] : '无' ?></span>
                                    </li>
                                </ul>
                                <!--
                                <div class="more_tag_wrap">
                                    <div class="more_tag">
                                        <span class="more_tag_title">更多标签</span>
                                        <ul class="tag_group">
                                        <?php
                                if (!empty($user->tag)) {
                                    foreach ($user->tag as $tag) {
                                        ?>
                                            <li><?php echo $tag->tag_name ? $tag->tag_name : '标签' ?>：<?php echo $tag->tag_value ?></li>
                                        <?php
                                    }
                                }
                                ?>
                                            <li>星座：白羊座</li>
                                            <li>消费能力：高富帅</li>
                                            <li>兴趣：运动</li>
                                            <li>星座：白羊座</li>
                                            <li>消费能力：高富帅</li>
                                            <li>兴趣：运动</li>
                                            <li>星座：白羊座</li>
                                            <li>消费能力：高富帅</li>
                                            <li>兴趣：运动</li>
                                            <li>星座：白羊座</li>
                                            <li>消费能力：高富帅</li>
                                            <li>兴趣：运动</li>
                                            <li>星座：白羊座</li>
                                            <li>消费能力：高富帅</li>
                                            <li>兴趣：运动</li>
                                        </ul>
                                    </div>
                                </div>
                                -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="user_detail_r">
                    <h3>客户成长</h3>
                    <!-- 代码来自 http://f2e.im/static/pages/timeline/index.html -->
                    <div class="history">
                        <!--
                        <div class="history-date">
                            <ul>
                                <h2 class="first"><a href="javascript:;">2015年</a></h2>
                                <li class="green">
                                    <h3>05.03<span>2015</span></h3>
                                    <dl>
                                        <dt>关注微信公众号
                                        </dt>
                                    </dl>
                                </li>
                                <li class="green">
                                    <h3>05.06<span>2015</span></h3>
                                    <dl>
                                        <dt>注册成为会员
                                        </dt>
                                    </dl>
                                </li>
                            </ul>
                        </div>
                        -->
                        <?php
                        if (!empty($time_line))
                            foreach ($time_line as $k => $v) {
                                ?>
                                <div class="history-date">
                                    <ul>
                                        <h2 class="first"><a href="javascript:;"><?php echo $k ?>年</a></h2>
                                        <?php
                                        foreach ($v as $kk => $vv) {
                                            ?>
                                            <li class="green">
                                                <h3><?php echo date('m-d', strtotime($vv['time'])) ?>
                                                    <span><?php echo $k ?></span></h3>
                                                <dl>
                                                    <dt><?php echo $vv['thing'] ?>
                                                    </dt>
                                                </dl>
                                            </li>
                                            <?php
                                        }
                                        ?>
                                        <!--
                                        <li class="green">
                                            <h3>05.03<span>2016</span></h3>
                                            <dl>
                                                <dt>关注微信公众号
                                                </dt>
                                            </dl>
                                        </li>
                                        <li class="green">
                                            <h3>05.06<span>2016</span></h3>
                                            <dl>
                                                <dt>注册成为会员
                                                </dt>
                                            </dl>
                                        </li>
                                        -->
                                    </ul>
                                </div>
                                <?php
                            }
                        ?>
                        <div class="history-bottom"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>