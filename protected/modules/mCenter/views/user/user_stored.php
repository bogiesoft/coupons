<script type="text/javascript">
    $(document).ready(main_obj.list_init);
    $(function(){
        var now = new Date();
        $('#stored_time').daterangepicker({
            timePicker: false,
            format: 'YYYY/MM/DD',
            dateLimit: true,
            maxDate: true,
        });
    });
</script>
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
                    <li onClick="window.self.location.href='<?php echo Yii::app()->createUrl('mCenter/user/userInfo', array('id'=>$_GET['id'])) ?>'">用户信息</li>
                    <li onClick="window.self.location.href='<?php echo Yii::app()->createUrl('mCenter/user/userExpenses', array('id'=>$_GET['id'])) ?>'">消费记录</li>
                    <li onClick="window.self.location.href='<?php echo Yii::app()->createUrl('mCenter/user/userPoints', array('id'=>$_GET['id'])) ?>'">积分记录</li>
                    <li onClick="window.self.location.href='<?php echo Yii::app()->createUrl('mCenter/user/userStored', array('id'=>$_GET['id']))?>'" class="active">储值记录</li>
                    <!--<li onClick="window.self.location.href='<?php echo Yii::app()->createUrl('mCenter/user/userPurchase', array('id'=>$_GET['id']))?>'">商城购买记录</li>-->
                </ul>
            </div>
            <div class="user_detail">
                <ul class="m_record">
                    <li>
                        <p>当前储值<i class="record_icon record_icon_gift"></i>
                        </p>
                        <p class="number">
                        <?php echo $user->money?$user->money:'0'?>
                        </p>
                    </li>
                    <li>
                        <p>累计储值<i class="record_icon record_icon_gift"></i>
                        </p>
                        <p class="number"><?php echo $total_money_leiji?></p>
                    </li>
                    
                    <li>
                        <p>累计储值(实收)<i class="record_icon record_icon_gift"></i>
                        </p>
                        <p class="number"><?php echo $total_money_shishou?></p>
                    </li>
                </ul>
                <div class="wq_form_inline">
                    <?php echo CHtml::beginForm(Yii::app()->createUrl($this->route), 'get', array('id' => 'search_form'))?>
                    <input type="hidden" name="id" value="<?php echo $_GET['id']?>">
                    <div class="wq_inline_group">
                        <input type="text" name="stored_time" value="<?php echo isset($_GET['stored_time'])?$_GET['stored_time']:''?>" id="stored_time" class="wq_form_control" placeholder="请选择时间段…">
                    </div>
                    <div class="wq_inline_group">
                        <input type="submit" value="" class="wq_form_control search">
                    </div>
                    <?php echo CHtml::endForm()?>
                </div>
                <table cellspacing="0" cellpadding="0" width="100%" class="consume_table">
                    <thead>
                        <tr class="thead">
                            <td>订单号</td>
                            <td>储值活动</td>
                            <td>数量</td>
                            <td>实收金额</td>
                            <td>赠送金额</td>
                            <td>操作员编号</td>
                            <td>操作员姓名</td>
                            <td>交易时间</td>
                            <td>状态</td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                        if(!empty($stored_lists))
                        {
                            foreach($stored_lists as $v)
                            {
                    ?>
                        <tr>
                            <td><?php echo $v->order_no?></td>
                            <td><?php echo $v->stored->name?></td>
                            <td><?php echo $v->num?></td>
                            <td><?php echo ($v->stored->stored_money*$v->num)?></td>
                            <td><?php echo ($v->stored->get_money*$v->num);?></td>
                            <td><?php echo empty($v->operator->number)?'无':$v->operator->number;?></td>
                            <td><?php echo empty($v->operator->name)?'线上支付':$v->operator->name;?></td>
                            <td><?php echo $v->create_time?></td>
                            <td><?php echo $order_status[$v->order_status]?></td>
                        </tr>
                    <?php             
                            }
                        }
                        else 
                        {
                    ?>
                        <tr>
                            <td colspan="9" align="center">无储值记录</td>
                        </tr>
                    <?php         
                        }
                    ?>
                    <!--
                        <tr>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                        </tr>
                    -->
                    </tbody>
                </table>
                <div class="r">
				    <div class="page1">
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
        </div>
    </div>
</div>