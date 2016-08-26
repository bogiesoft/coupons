
    <div class="kkfm_r_inner">
        <div class="storeManage">
            <div class="top">
                <div class="name">
                    收款通知
                </div>
            </div>
            <div class="searchWrap">
                <p class="wechat-notice-desc">当顾客使用收款二维码向商户付款时，可将支付结果通过玩券微信公众号通知收银员的微信，方便确认支付结果。
                   </p>
                <div class="cz">
                    <input type="submit" value="添加通知对象" class="btn_com_blue">
                </div>
            </div>
            <div class="wechat-notice">
                <table cellspacing="0" cellpadding="0" width="100%">
                    <tr class="thead">
                        <td width="20%">编号</td>
                        <td width="25%">通知对象</td>
                        <td width="20%">所属门店</td>
                        <td align="20%">添加时间</td>
                        <td align="center">操作</td>
                    </tr>
                    <?php if (!empty($list)){
                            foreach ($list as $k => $v){
                    ?>
                    <tr class="tbody cur_count">
                        <td><?php echo $v['index'];?></td>
                        <td class="avatar">
                            <img src="<?php echo $v['avatar'];?>" alt="avatar">
                            <span class="user-name"><?php echo $v['nickname'];?></span>
                        </td>
                        <td><?php echo $v['store_name']?></td>
                        <td><?php echo $v['create_time'];?></td>
                        <td align="center"><a href="<?php echo Yii::app()->createUrl('mCenter/store/deleteNoticeUser')?>?id=<?php echo $v['id'];?>" class="delete_btn" onclick="if(!confirm('确认删除?')){return false;}">删除</a></td>
                    </tr>
                    <?php 
                            }
                        }
                    ?>
                    <?php if ($count > 10){?>
                   <tr class="tbody">
                        <td  align="right" colspan="5">
                         <?php $this -> widget('CLinkPager',array(
                            'pages'=>$pages,
                            'header'=>'共&nbsp;<span class="yellow">'.$pages -> getItemCount().'</span>&nbsp;条&nbsp;',
                            'prevPageLabel' => '上一页',
                            'nextPageLabel'=>'下一页',
                            'maxButtonCount'=>8
                        ));?>
                        </td>
                    </tr>
                    <?php }?>
                </table>
                <?php 
                    if (empty($list)){
                        echo '<a href="javascript:;" class="blank-tip">尚未添加通知对象</a>';
                    }
                ?>
            </div>
                       
            <!-- 点击添加对象后 -->
            <div class="qcode_pop" style="display:none" id="qrcode_pop">
                <div class="qcode_title">添加通知对象</div>
                <span class="close_btn"></span>
                <div class="add-obj">
                    <div class="add-obj-store">
                    <label for="add-obj-store">所属门店</label>
                    <select id="add-obj-store">
                        <option value=''>请选择门店名称</option>
                <?php if (!empty($storeList)){
                        foreach ($storeList as $v){
                ?>
                    <option value="<?php echo $v['ajax_id']?>"><?php echo $v['store_name']?></option>
                <?php 
                        }
                    }
                ?>
                    </select>
                    </div>
                    <!-- <span class="error2">必须选择一个门店</span> -->
                    <div class="add-obj-img">
                        <img src="<?php echo GJ_STATIC_IMAGES."blank.png";?>" alt="qrcode" id="qrcode_url">
                    </div>
                    <p class="add-obj-qrcode-tip">请选择一个门店，并使用微信客户端扫描二维码，接收收款二维码收款通知。</p>
                </div>
            </div>
        </div>
    </div>
<script>
$(function(){
	var count = '<?php echo $count;?>';
	var $elements = $('.cur_count');
	var len = $elements.length;
	var height = 50-len*2;
	$("#qrcode_pop").css('top',height+'%');	//修正弹窗顶部高度
	time_flag = null;
	$("#add-obj-store").change(function(){
		var val = $(this).val();
		if (val != ''){
			$.ajax({
				type:'POST',
			  	url: "<?php echo Yii::app()->createUrl("mCenter/store/ajaxTradeNotice")?>",
				data: {name:val},
				datatype:"Json",
				cache : false, 
				success:function(data){
					if (data){
						$("#qrcode_url").attr('src',$.parseJSON(data));
						if (time_flag == null){
							t1 = window.setInterval("refresh()",1000);
							time_flag = 1;
						}
						
					}
				}
			});
		}else{
			$("#qrcode_url").attr('src','<?php echo GJ_STATIC_IMAGES."blank.png";?>');
		}
	})
	$(".btn_com_blue").click(function(){
		$("#qrcode_pop").show();
	})
	$(".close_btn").click(function(){
		location.reload();
		$("#qrcode_pop").hide();
	})
	
})
function refresh(){
	var store = $("#add-obj-store").val();
	if (store == ''){
        window.clearInterval(t1);
        time_flag = null;
	}else{
		$.ajax({
			type:'POST',
		  	url: "<?php echo Yii::app()->createUrl("mCenter/store/ajaxTradeNotice")?>",
			data: {refresh:'cur',store:store},
			datatype:"Json",
			success:function(data){
				if (data){
					location.reload(true);
				}
			}
		});
	}
	
}
</script>