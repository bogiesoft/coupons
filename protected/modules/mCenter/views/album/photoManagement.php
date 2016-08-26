<script type="text/javascript">
	$(document).ready(main_obj.list_init);
</script>

<div class="kkfm_r_inner">
     <div class="top">
    	<div class="name">相册管理</div>
     </div>     
    <div class="album">
        <?php if(!empty($list)) { ?>
        <?php foreach ($list as $key => $value) { ?>
     	<div class="item">
            <div class="img"><a href="<?php echo Yii::app()->createUrl('mCenter/album/photoSubclass',array('groupid'=>$value['id']))?>"><img src="<?php echo isset($value['img']) ? IMG_GJ_LIST : GJ_STATIC_IMAGES?><?php echo isset($value['img']) ? $value['img'] : 'pro.png'?>"></a></div>
            <div class="text"><?php echo $value['name']?></div>            
            <span class="num"><?php echo $value['sum']?></span>
            <div class="shade" style="display:none">
                <a href="javascript:;" onclick="showpop(<?php echo $value['id']?>)" class="edit" style="float: right"></a>                   
            </div>
        </div> 
        <?php } } ?>
     </div>
</div> 
<!--创建分组弹出框-->
<div class="popWrap" id="pop" style="width:500px;display: none">
    <div class="pop_con">
        <form action="<?php echo Yii::app()->createUrl('mCenter/album/editPhotoManagement')?>" method="post">
        <div class="title">
        	修改相册名称
        </div>
            <input name="groupid" value="" type="hidden">
        <div class="pop_content">
            <div class="fz">
                <input type="text" class="txt" name="name" placeholder="请输入名称" value="">
            </div>
        </div>  
        <div class="bottom">
            <input type="submit" value="确定" class="btn_com_blue" style="margin-right:100px">            
            <a href="javascript:;" onclick="hiddenpop()"  class="btn_com_gray">取消</a>
        </div>
        </form>
    </div>
</div>
<script type="text/javascript">
function initpop(){
        var _w = $(window).width() //浏览器窗口宽度
        var _w = $(window).height() //浏览器窗口高度
        var _offsetW = $('#pop').width(); //获取弹出框的宽度
        var _offsetH = $('#pop').height(); //获取弹出框的高度

        var _left = ($(window).width()-$('#pop').width())/2;
        var _top = ($(window).height()-$('#pop').height())/2;

        $('#pop').css({'left' : _left, 'top' : _top});

}
function showpop(groupid){
    initpop();
    $('#pop').show("slow");
    $("input[name='groupid']").val(groupid);
}
function hiddenpop(){
    $('#pop').hide(1000);
}
 $(".item").hover(function(){
    n=$(".item").index(this)
    $(".shade").eq(n).show()
    },function(){
    $(".shade").eq(n).hide()
    })
</script>
