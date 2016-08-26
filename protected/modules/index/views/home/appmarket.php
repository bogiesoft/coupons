<div class="backstage-l">
        	<dl>
                <dt><a href="#">云官网</a></dt> 
                <dd style="display: block">
                	<a href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/ShopManagement/OnlineShop') ?>', this)" class="cur">云官网</a>
                	<a href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/Album/PhotoManagement') ?>', this)">相册</a>
                </dd>
                <dt><a href="#">预约订房</a></dt> 
                <dd style="display: none">
                	  <a href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/Hotel/RoomManagement') ?>', this)" class="cur">房间管理</a>
                	  <a href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/Hotel/OrderManagement') ?>', this)">订单管理</a>
                	  <a href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/Hotel/HotelInformation') ?>', this)">酒店信息</a>
                </dd>
                <dt><a href="#">智慧物业</a></dt>
                <dd style="display: none">
                    <a href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/Village/CommunityList') ?>', this)" class="cur">小区管理</a>
                    <a href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/Proprietor/ProprietorList') ?>', this)">业主管理</a>
                    <a href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/FeeOrder/EFeeOrderList') ?>', this)">费用列表</a>
                    <a href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/Repair/ReportRepairRecordList') ?>', this)">小区报修</a>

                </dd>
<!--                     <dt><a href="#">应用管理</a></dt> -->
<!--                     <dd> -->
          <!--       	<a href="javascript:;" onclick="onLeft('<?php //echo Yii::app()->createUrl('mCenter/promotions/turntableList') ?>', this)">欢乐大转盘</a>--> 
           <!--     	<a href="javascript:;" onclick="onLeft('<?php //echo Yii::app()->createUrl('mCenter/promotions/scratchList') ?>', this)">刮刮卡</a>--> 
<!--                     </dd> -->  
             </dl>
</div>