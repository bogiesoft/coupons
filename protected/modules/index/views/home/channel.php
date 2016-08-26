<div class="backstage-l">
		<dl>
			<dt><a href="#" class="cur">支付宝服务窗</a></dt>
			<dd style="display: block">
				<a id="103_01" href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/fuwu/menu') ?>',this)" class="cur">服务窗菜单管理</a>
				<a id="103_02" href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/fuwu/msgReply') ?>',this)">自动回复</a>
			</dd>
			<dt><a href="#" class="cur">微信公众号</a></dt>
			<dd>
				<a id="103_03" href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/wechat/menu') ?>',this)">公众号菜单管理</a>
				<a id="103_04" href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/wechat/msgReply') ?>',this)">自动回复</a>
			</dd>
			<dt><a href="#" class="cur">广播管理</a></dt>
			<dd class="line">
  				<a id="103_05" href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/broadcast/broadcasting') ?>',this)">群发广播</a>
			</dd>
			<dt><a href="#" class="cur">素材管理</a></dt>
			<dd class="line">
	  			<a id="103_06" href="javascript:;" onclick="onLeft('<?php echo Yii::app()->createUrl('mCenter/fuwu/materialList') ?>',this)">图文素材管理</a>
			</dd>
		</dl>
</div>

<script>
	function leftSelect() {
		$("a").removeClass("cur");
		$("dd").hide();
		var id = parent.window.$("#left").attr("src").substr(-6);
		$("#" + id).addClass("cur").parent().show()
	}
	$(function(){leftSelect()});
</script>