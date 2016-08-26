

<body>
<script type="text/javascript">
	$(document).ready(main_obj.list_init);
</script>

<div class="kkfm_r_inner">
	<!--搜索框 count-top包含的部分-->
    <div class="top">
        <div class="name">活动添加</div>
    </div>
	<div class="contant active_add">
        <div class="add-first">
            <div class="icon"> </div>
            <a href="<?php echo Yii::app() -> createUrl('mCenter/MallActivity/CreateMallActivity',array('type'=>DMALL_ACTIVITY_TYPE_SDLJ));?>" class="link">首单立减活动</a>
        </div>
        <div class="add-zhou">
            <div class="icon"> </div>
            <a href="<?php echo Yii::app() -> createUrl('mCenter/MallActivity/CreateMallActivity',array('type'=>DMALL_ACTIVITY_TYPE_ZFL));?>" class="link">周福利活动</a>
        </div>
    </div>
</div> 
</body>

