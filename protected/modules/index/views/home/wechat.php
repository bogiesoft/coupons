<div class="backstage-l">
		<dl>
			<dt><a href="#" class="cur">基本设置</a></dt>
            	<dd class="line" style="display: block">
            	<a href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/wechat/menu') ?>',this)" class="cur">菜单管理</a>
            	</dd>

			<dt><a href="#" class="cur">广播管理</a></dt>
<!--             	<dd><a href="javascript:;" onclick="onLeft('<?php //echo Yii::app()->createUrl('mCenter/fuwu/firstWatch') ?>',this)">关注回复</a></dd> -->
            	<dd>
            	<a href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/wechat/msgReply') ?>',this)">自动回复</a>
            	<a href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/wechat/broadcastGroup') ?>',this)">群发广播</a>
            	</dd>
		</dl>
</div>