<script type="text/javascript">
	$(document).ready(main_obj.list_init);
</script>
<link href="<?php echo GJ_STATIC_STYLES?>square/blue.css" rel="stylesheet">
<script src="<?php echo GJ_STATIC_JS?>icheck.js"></script>
<script src="<?php echo GJ_STATIC_JS."gj.js"?>"></script>
<div class="kkfm_r_inner">
     <div class="top">
    	<div class="name"><?php echo isset($albumname) ? $albumname : ''?>-><?php echo isset($albumgroup_name) ? $albumgroup_name : ''?></div>
         <div class="cz"><a href="<?php echo Yii::app()->createUrl('mCenter/Album/PhotoSubclass',array('groupid'=>$albumid))?>" class="btn_comCancel">返回</a></div>
     </div>
    <div class="top" style="border:none">
        <div class="name">
            <a href="javascript:void(0);" class="btn_com_blue"  onclick="showpop()">上传照片</a>
            <a href="javascript:void(0);" id="showop" class="btn_comCancel">批量操作</a>
        </div>
    </div>
    <div class="album">
        <div class="search clearfix" id="check" style="display: none;">
            <input type="checkbox" id="checkall"> 全选
            <?php echo CHtml::dropDownList('move', '', $move, array('prompt' => '移动到','AutoPostBack'=>'true'))?>
            <a href="javascript:;" id="delmore" class="link">删除</a>
            <a href="javascript:;" class="link" id="cancel">取消</a>
        </div>
        <?php if(!empty($list)) { ?>
        <?php foreach ($list as $key => $value) { ?>
     	<div class="item">

        	<div class="img"><img src="<?php echo IMG_GJ_LIST.$value['img']?>"></div>
                <div type="text"><?php echo $value['name']?></div>
                <div class="shade" style="display:none">
                    <a href="<?php echo Yii::app()->createUrl('mCenter/album/DelGroupPhoto',array('id'=>$value['id'],'photo_id'=>$_GET['album_group_id']))?>" onclick="return confirm('确定此操作吗？');" class="del1" style="float: right"></a>
                </div>
            <input type="checkbox" style="display: none" id="checkbox<?php echo $key;?>"/>
            <input type="text"  style="display: none;" id="id<?php echo $key;?>" value="<?php echo $value['id']?>"/>
        </div>
        <?php } } ?>
     </div>
    <input type="text" style="display: none;" id="photo_id" value="<?php echo $_GET['album_group_id']?>"/>
    <input type="text" style="display: none;" id="photo_sum" value="<?php echo count($list);?>">
</div>

<!--图片上传弹出框-->
<div class="popWrap" id="pop" style="width:772px;display: none" >
    <form action="<?php echo Yii::app()->createUrl('mCenter/album/addPhoto',array('album_id'=>$albumid,'type'=>'2'))?>" method="post">
        <div class="pop_con">
            <div style="height:40px;" class="title">
                <span style="float:left;line-height:40px;">上传图片</span>
                <select name='album_group_id' style="display: none;">
                    <option value="<?php echo $_GET['album_group_id']?>"></option>
                </select>
                <span style="float:right;line-height:40px;"><?php echo CHtml::fileField('upload')?></span>
            </div>
            <div style="clear:both;"></div>            
            <div class="pop_content" >
                <div class="hidden"></div>
                <div class="clear"></div>
                <div class="bottom">
                    <input type="submit" value="确定" class="btn_com_blue" style="margin-right:100px">
                    <a href="javascript:;" onclick="hiddenpop()"  class="btn_com_gray">取消</a>
                </div>
            </div>
      	</div>
    </form>
</div>

<script>
    var flag=false;
    $(document).ready(function(){
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue checkbox',
            increaseArea: '20%' // optional
        });
        $('.checkbox').hide();
    });

    $(".item").hover(function(){
    n=$(".item").index(this);
    $(".shade").eq(n).show();
    },function(){
    $(".shade").eq(n).hide();
    });

    function initpop(){
        var _w = $(window).width(); //浏览器窗口宽度
        var _w = $(window).height(); //浏览器窗口高度
        var _offsetW = $('#pop').width(); //获取弹出框的宽度
        var _offsetH = $('#pop').height(); //获取弹出框的高度

        var _left = ($(window).width()-$('#pop').width())/2;
        var _top = ($(window).height()-$('#pop').height())/2;

        $('#pop').css({'left' : _left, 'top' : 80});
    };

    function showpop(){
        initpop();
        $('#pop').show("fast");
    }

    function hiddenpop(){
        $('#pop').hide(300);
    }


    $(function(){
       groupPhotoList_delmore("delmore",'<?php echo Yii::app()->createUrl('mCenter/album/DelSomeGroupPhoto')?>','<?php echo Yii::app()->createUrl('mCenter/album/groupPhotoList')?>')
    });



    $(function(){
        groupPhotoList_upload('upload','<?php echo UPLOAD_TO_PATH?>','<?php echo GJ_STATIC_JS?>',"<?php echo UPLOAD_IMG_TYPE;?>",'<?php echo(IMG_GJ_FOLDER)?>','<?php echo(IMG_JKSC_THUMB)?>','<?php echo IMG_GJ_LIST?>');
    });


    $(function(){
        groupPhotoList_select('<?php echo Yii::app()->createUrl('mCenter/album/movePhoto')?>','<?php echo Yii::app()->createUrl('mCenter/album/groupPhotoList')?>');
    });





    $(document).on('click', '.del', function() {
        $(this).closest(".item").remove();
    });


</script>
