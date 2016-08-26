
<body class="register">
<div class="header clearfix">
	<div class="top">
      <div class="logo">
      <a href="<?php echo Yii::app()->createUrl('mCenter/index/index'); ?>">
      <img src="<?php echo GJ_STATIC_IMAGES ?>logo.png">
      </a>
      </div>
      <div class="header_right">
      	<ul>
      		<li>客服热线：400-882-9998</li>
        </ul>
	</div>
   </div>
</div>
<div class="gj-load">
	<div class="status-nav">
         <ul>
            <li class="<?php if(!empty($document_class)){echo 'bg';}else{echo '';} ?>"><a href="<?php echo Yii::app()->createUrl('mCenter/downLoad/downLoad',array('downLoadType'=>DOWNLOAD_TYPE_DOCUMENT)); ?>">文档下载</a></li>
            <li class="<?php if(!empty($video_class)){echo 'bg';}else{echo '';} ?>"><a href="<?php echo Yii::app()->createUrl('mCenter/downLoad/downLoad',array('downLoadType'=>DOWNLOAD_TYPE_VIDEO)); ?>">视频下载</a></li>
         </ul>
    </div>
</div>
<div class="fx-load">
<?php if(!empty($list)){ ?>
<?php foreach ($list as $k=>$v){ ?>
	<div class="item">
    	<div class="img"><img src="<?php echo GJ_STATIC_IMAGES ?>data.png"></div>
        <div class="name">
        	<div class="title"><?php echo $v['title']; ?></div>
            <div class="time"><span><?php echo $v['admin_name']; ?></span><span class="ico"><?php echo $v['create_time']; ?></span></div>
        </div>
        <div class="load"><a target="_blank" href="<?php echo $v['download_url']; ?>"><img src="<?php echo GJ_STATIC_IMAGES ?>download.png"></a></div>
    </div>
     <?php }?>
    <?php }else{?>
      <div style="width:100px;text-align:center;color:#999;margin:0 auto">暂无资源下载</div>
    <?php }?>
</div>
</body>

