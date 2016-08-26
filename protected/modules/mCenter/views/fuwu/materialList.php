<body>
	<div class="kkfm_r_inner">
		<!--搜索框 count-top包含的部分-->
		<div class="top">
	        <div class="name">图文素材管理</div>
	        <!--<div class="cz"><input type="submit" value="添加商户" class="btn_com_blue"></div>-->
	    </div>
	    
		<div class="material material-list">
	    	<div class="click">
	        	<a href="<?php echo Yii::app()->createUrl('mCenter/fuwu/setSingleMaterial') ?>" class="ico5">+单图文</a>
	            <a href="<?php echo Yii::app()->createUrl('mCenter/fuwu/addMoreMaterial')?>" class="ico6">+多图文</a>
	        </div>
	     	<div class="material-left">
	     		<?php if ( !empty($model) ) { ?>
	     			<?php foreach ( $model as $key => $value ) { ?>
     					<div class="li">
				            <div class="pro">
	     						<?php foreach ( $value as $k => $v ) { ?>
	     								<?php $num = 1;?>
	     							<?php if( 0 == $k ) { ?>
					                    <div class="text"><?php echo $v['title']?></div>
					                    <div class="banner">
					                        <div class="img">
					                        	<img id="cover_img" src="<?php echo (IMG_GJ_LIST.$v['cover_img']) ?>" width="240" height="120"/>
					                        </div>
					                        <div class="text"><?php echo $v['abstract']?></div>
					                    </div>
	     							<?php } else {?>
	     								<?php $num++;?>
					                    <div class="item">
					                       <div class="title"><a href=""><?php echo $v['title']?></a></div>
					                       <div class="img">
					                       <img id="cover_img" src="<?php echo (IMG_GJ_LIST.$v['cover_img']) ?>" width="72" height="72"/>
					                       </div>
					                    </div>
	     							<?php }?>
	     						<?php } ?> 
	     					</div>
					        <!--end pro-->
			                <div class="bottom">
			                	<?php if ($num == 1 ) {?>
				                    <span><a href="<?php echo Yii::app()->createUrl('mCenter/broadcast/broadcasting') ?>">发送</a></span>
				                    <span><a href="<?php echo Yii::app()->createUrl('mCenter/fuwu/setSingleMaterial', array('material_id'=>$key))?>">编辑</a></span>
				                    <span class="no"><a href="<?php echo Yii::app()->createUrl('mCenter/fuwu/DelMaterial', array('material_id'=>$key))?>" onclick="return confirm('确认删除吗？');">删除</a></span>
				                <?php } else { ?>
				                 	<span><a href="<?php echo Yii::app()->createUrl('mCenter/broadcast/broadcasting') ?>">发送</a></span>
				                    <span><a href="<?php echo Yii::app()->createUrl('mCenter/fuwu/setMoreMaterial', array('material_id'=>$key))?>">编辑</a></span>
				                    <span class="no"><a href="<?php echo Yii::app()->createUrl('mCenter/fuwu/DelMaterial', array('material_id'=>$key))?>" onclick="return confirm('确认删除吗？');">删除</a></span>
				                <?php } ?>
			                </div> 
				    	</div>  
	     			<?php } ?>
	     		<?php } ?>
	     		
	        </div>
	        <!--end left-->
	       	
		</div>
	</div> 

	<script>
		/*
		原理：1.把所有的li的高度值放到数组里面
			 2.第一行的top都为0
			 3.计算高度值最小的值是哪个li
			 4.把接下来的li放到那个li的下面
		*/
		var margin = 35;//这里设置间距
		var clickH = parseInt($(".material-list .click").height()) + parseInt($(".material-list .click").css("margin-bottom"))+ parseInt($(".material-list .click").css("margin-top")); //获取单图文，多图文的box的高度和margin值
		var li=$(".li");//这里是区块名称
		var	li_W = li[0].offsetWidth+margin;//取区块的实际宽度（包含间距，这里使用源生的offsetWidth函数，不适用jQuery的width()函数是因为它不能取得实际宽度，例如元素内有pandding就不行了
		function liuxiaofan(){//
			var h=[];//记录区块高度的数组
			var n = document.documentElement.offsetWidth/li_W|0;//窗口的宽度除以区块宽度就是一行能放几个区块
			for(var i = 0;i < li.length;i++) {//有多少个li就循环多少次
				li_H = li[i].offsetHeight;//获取每个li的高度
				if(i < n) {//n是一行最多的li，所以小于n就是第一行了
					h[i]=li_H;//把每个li放到数组里面	
					li.eq(i).css("top",0);//第一行的Li的top值为0
					li.eq(i).css("left",i * li_W);//第i个li的左坐标就是i*li的宽度
					}
				else{
					min_H =Math.min.apply(null,h) ;//取得数组中的最小值，区块中高度值最小的那个
					minKey = getarraykey(h, min_H);//最小的值对应的指针
					h[minKey] += li_H+margin ;//加上新高度后更新高度值
					li.eq(i).css("top",min_H+margin);//先得到高度最小的Li，然后把接下来的li放到它的下面
					
					li.eq(i).css("left",minKey * li_W);	//第i个li的左坐标就是i*li的宽度
				}
				$("h3").eq(i).text("编号："+i+"，高度："+li_H);//把区块的序号和它的高度值写入对应的区块H3标题里面
			}
			
			//循环li的个数
			for(var j = 0;j < li.length;j++) {
				//获取所有li的left值
				var left = li.eq(j).css("left")
				
				//当left==0则表示是每排的第一个元素
				if(parseInt(left) == 0){
					//获取每排第一个元素的top值
					var top = parseInt(li.eq(j).css("top"));
					//把top+clickH赋值给满足条件的li元素
					li.eq(j).css("top",clickH + top)
				}
				//li.eq(n*j).css("top","88px")
			}
			
			$(".material-list").height(Math.max.apply(null,h) + margin + clickH);
		}
		/* 使用for in运算返回数组中某一值的对应项数(比如算出最小的高度值是数组里面的第几个) */
		function getarraykey(s, v) {for(k in s) {if(s[k] == v) {return k;}}}
		/*这里一定要用onload，因为图片不加载完就不知道高度值*/
	
		window.onload = function() {liuxiaofan();};
		/*浏览器窗口改变时也运行函数*/
		window.onresize = function() {liuxiaofan();};
	</script>

</body>
