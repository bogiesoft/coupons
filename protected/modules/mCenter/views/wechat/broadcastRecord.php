<body>
<script type="text/javascript">
	$(document).ready(main_obj.list_init);
</script>

<div class="kkfm_r_inner">
	<div class="top">
        <div class="name">群发广播<span>您每周可发送一条群发广播。</span></div>
    </div>
    <!--这部分有变动 start-->
    <div class="reply broadcastGroup">   
    	<div class="status-nav clearfix">
            <ul>
                <li class="<?php echo 'broadcast' == $type ? 'bg' : '';?>"><a href="<?php echo Yii::app()->createUrl('mCenter/wechat/broadcastGroup')?>">新建广播</a></li>
                <li class="<?php echo 'record' == $type ? 'bg' : '';?>"><a href="<?php echo Yii::app()->createUrl('mCenter/wechat/broadcastRecord')?>">已发送</a></li>
            </ul>
        </div>     
        <div class="bus-contant">
            <table cellspacing="0" cellpadding="0" width="100%">            
                <tr class="order-title">
                    <td align="center" nowrap="nowrap">消息类型</td>
                    <td align="center" nowrap="nowrap">发送对象</td>
                    <td align="center" nowrap="nowrap">发送时间</td>
                    <td align="center" nowrap="nowrap">操作</td>
                </tr>
                <?php if ( !empty($record)) { ?>
                	<?php foreach ($record as $key => $value) { ?>
                		<tr>
		                    <td align="center" width="40%">
		                    	<div class="bcgItem clearfix">
		                    		<?php if(!empty($value['content'])) {?>
			                        	<div class="htcon"><img src="<?php echo IMG_GJ_LIST.$value['img']?>"></div>
			                        	<div class="text">
			                            	<h3><?php echo $value['title']?></h3>
			                                <p><?php echo $value['content']?></p>
			                            </div>
			                        <?php }else{ ?>
			                        	<div class="htcon"><img src="<?php echo GJ_STATIC_IMAGES?>data.png"></div>
			                        	<div class="text">
			                            	<h3><?php echo $value['title']?></h3>
			                            </div>
			                        <?php } ?>
			                    </div>
		                    </td>
		                    <td align="center"><?php echo $value['group']?></td>
		                    <td align="center"><?php echo $value['time']?></td>
		                    <td align="center">
		                    	<a href="<?php echo Yii::app()->createUrl('mCenter/wechat/delAutoReply', array('reply_id'=>$key, 'type'=>REPLY_TYPE_BROADCAST))?>" onclick="return confirm('确认删除吗？');"><img src="<?php echo GJ_STATIC_IMAGES?>del.png"></a>
		                    </td>
		                </tr>
                	<?php } ?>
                <?php } ?>
            </table>
        </div>
        
    </div>
    <!--这部分有变动 end-->
</div> 


</body>
</html>
