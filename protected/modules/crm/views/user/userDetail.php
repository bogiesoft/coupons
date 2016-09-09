<div class="kkfm_r_inner">
        <div class="storeManage">
            <div class="top">
                <div class="name">
                    用户详情
                </div>
            </div>
            <div class="bus-contant">
                <div class="user_detail_item">
                    <h3>基本信息</h3>
                    <div class="user_detail_box clearfix">
                        <div class="avatar">
                        <?php 
                        	   if (!empty($detail['avatar'])){
                        	       $imgSrc = $detail['avatar'];
                        	   } 
                        	   if (!empty($detail['wechat_avatar'])){
                        	       $imgSrc = $detail['wechat_avatar'];
                        	   }
                        	   if (!empty($detail['alipay_avatar'])){
                        	       $imgSrc = $detail['alipay_avatar'];
                        	   }
                        	   if (empty($imgSrc)){
                        	       if ($detail['sex'] == SEX_FEMALE){
                        	           $imgSrc = $img = GJ_STATIC_IMAGES.'face_woman.png';;
                        	       } else {
                        	           $imgSrc = $img = GJ_STATIC_IMAGES.'face_man.png';
                        	       }
                        	   }
                            ?>
                            <img src="<?php echo $imgSrc;?>">
                        </div>
                        <div class="user_info">
                            <p><span><?php echo $detail['nickname']?></span>
                            <span 
                            <?php if($detail['sex'] == SEX_MALE) { ?>
                            class="male"
                            <?php } ?> 
                            <?php if($detail['sex'] == SEX_FEMALE){?>
                            class="female"
                            <?php } ?>>
                            </span></p>
                            <p><span class="user_info_name">地址：<?php echo $detail['address']?></span></p>
                        </div>
                    </div>
                </div>
                <div class="user_detail_item">
                    <h3>会员信息</h3>
                    <?php if ($detail['type'] == USER_TYPE_WANQUAN_MEMBER) {?>
                    <div class="user_detail_box">
                        <div class="use_info_member clearfix">
                            <ul>
                                <li class="es_li">会员手机：<?php echo $detail['account']?></li>
                                <li class="es_li">在线注册时间：<?php echo $detail['regist_time']?></li>
                                <li>会员等级：<?php echo $detail['grade_name']?></li>
                                <li>会员积分：<?php echo $detail['points'] ? $detail['points'] : '0'?></li>
                            </ul>
                        </div>
                    </div>
                    <?php } else {?>
                    <p class="member_rank_status">该用户尚未注册为会员</p>
                    <?php }?>

                    
                <div class="user_detail_item">
                    <h3>更多信息</h3>
                    <div class="user_detail_box">
                        <div class="use_info_other user_info_more clearfix">
                            <ul>
                                <li>所在分组：<?php if(!empty($detail['group'])) { ?>                                   
                                    <?php $groups = implode(',', $detail['group'])?>                           
                                    <?php echo $groups?>
                                <?php } else { ?>
                                    无
                                <?php } ?></li>
                                <li>最后登录时间：<?php echo $detail['login_time']?></li>
                                <li>公众号关注时间：<?php echo isset($detail['wechat_subscribe_time']) ? $detail['wechat_subscribe_time'] : ''?></li>
                                <li>最后登录客户端：<?php echo isset($detail['login_client']) ? $GLOBALS['__USER_LOGIN_CLIENT'][$detail['login_client']] : ''?></li>
                                <li><?php echo isset($detail['alipay_subscribe_time']) ? '服务窗关注时间：'.$detail['alipay_subscribe_time'] : ''?></li>
                            </ul>
                        </div>
                         <div class="more_tag clearfix">
                        <span class="more_tag_title">更多标签：</span>
                        <ul>
                            <?php echo !empty($detail['sex']) ? '<li>'.$GLOBALS['__BOOKSEX'][$detail['sex']].'</li>'  : ''?>
                            <?php echo !empty($detail['marital_status']) ? '<li>'.$GLOBALS['__MARITAL_STATUS'][$detail['marital_status']].'</li>'  : ''?>
                            <?php echo !empty($detail['province']) ? '<li>'.$detail['province'].'</li>'  : ''?>
                            <?php echo !empty($detail['city']) ? '<li>'.$detail['city'].'</li>'  : ''?>
                            <?php if($detail['tag_value']) { ?>
                            <?php foreach($detail['tag_value'] as $m => $val) { ?>
                            <li><?php echo $val?></li>  
                            <?php } } ?>
                        </ul>
                    </div>
                    </div>
                   
                    <div class="btn_l level_r ">
                    	<h3>消费记录</h3>
                         <input type="submit" value="消费记录" class="btn_com_blue">
                         <input type="submit" value="积分记录" class="btn_com_gray">
                    </div>
                    <div class="expense_cal">
                        <span>累计消费金额：<em><?php echo $detail['sum_order']?></em></span>
                        <span>累计消费次数：<em><?php echo $detail['order_count']?></em></span>
                        <span>当前积分：<em><?php echo isset($detail['points']) ? $detail['points'] : '0'?></em></span>
                        <table cellspacing="0" cellpadding="0" width="100%" class="expense_cal_table">
                            <tr class="thead">
                                <td width="28.33%">消费时间</td>
                                <td width="28.33%">门店</td>
                                <td width="28.33%">消费金额</td>
                                <td align="center">消费方式</td>
                            </tr>
                            <?php if(!empty($detail['order'])) {?>
                            <?php foreach($detail['order'] as $k => $v) { ?>
                            <tr>
                                <td valign="top">
                                    <dl class="list">
                                        <dt><?php echo $v['pay_time']?></dt>
                                    </dl>
                                </td>
                                <td valign="top">
                                    <dl class="list">
                                        <dt><?php echo empty($v['store_name']) ? '商城' : $v['store_name']?></dt>
                                    </dl>
                                </td>
                                <td valign="top">
                                    <dl class="list">
                                        <dt><?php echo $v['money']?></dt>
                                    </dl>
                                </td>
                                <td align="center" valign="top">
                                    <dl class="list">
                                        <dt><?php echo $GLOBALS['ORDER_PAY_CHANNEL'][$v['pay_channel']]?></dt>
                                    </dl>
                                </td>
                            </tr>
                            <?php } }?>
                        </table>
                    </div>
                </div>

                
            <br>
                <div style="float: right">
                    <div class="page">                        
                        <?php $this -> widget('CLinkPager',array(
                            'pages'=>$pages,
                            'header'=>'共&nbsp;<strong class="orange">'.$pages -> getItemCount().'</strong>&nbsp;条&nbsp;',
                            'prevPageLabel' => '上一页',
                            'nextPageLabel'=>'下一页',
                            'maxButtonCount'=>8
                        ));?>
        </div>
    </div>
    </div>