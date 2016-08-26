<?php $albumId = isset($_GET['groupid']) ? $_GET['groupid'] : ''?>
<script type="text/javascript">
	$(document).ready(main_obj.list_init);
</script>
<script src="<?php echo GJ_STATIC_JS."gj.js"?>"></script>
<div class="kkfm_r_inner">
     <div class="top">
         <div class="name"><?php echo isset($album) ? $album : ''?></div>
         <div class="cz"><a href="<?php echo Yii::app()->createUrl('mCenter/Album/PhotoManagement')?>" class="btn_comCancel">返回</a></div>

     </div>
     <div class="top" style="border:none">
    	<div class="name">            
            <a href="javascript:void(0);" class="btn_com_blue" onclick="showpop()">上传照片</a>
            <a href="javascript:void(0);" onclick="showpop1()" class="btn_comCancel">创建分组</a>
        </div>
     </div>
    <div class="album">
        <?php if(!empty($photosubclass)) { ?>
        <?php foreach ($photosubclass as $key => $value) { ?>
     	<div class="item">
            <div class="img"><a href="<?php echo Yii::app()->createUrl('mCenter/album/groupPhotoList',array('album_group_id'=>$value['id']))?>"><img src="<?php echo isset($value['img']) ? IMG_GJ_LIST : GJ_STATIC_IMAGES?><?php echo isset($value['img']) ? $value['img'] : 'pro.png'?>" ></a></div>
            <div class="text"><?php echo $value['name']?></div>            
            <span class="num"><?php echo $value['sum']?></span>
            <div class="shade" style="display:none">
                    <a href="javascript:;" onclick="showpop2(<?php echo $value['id']?>,<?php echo $_GET['groupid']?>)" class="edit"></a>
                    <a href="<?php echo Yii::app()->createUrl('mCenter/album/delPhotoSubclass',array('id'=>$value['id'],'groupid'=>$_GET['groupid']))?>" onclick="return confirm('确定此操作吗？');" class="del1"></a>
            </div>
        </div>
        <?php } }?>
     </div>
    <input type="text" style="display: none;" id="group_id" value="<?php echo $albumId;?>"/>
</div>
<!--创建分组弹出框-->
<div class="popWrap" id="pop1" style="width:500px;display: none">
    <div class="pop_con">
        <form action="<?php echo Yii::app()->createUrl('mCenter/album/addGroup',array('groupid'=>$albumId))?>" method="post">
        <div class="title">
            创建分组
        </div>
        <div class="pop_content">
            <div class="fz">
                <input type="text" name="group_name" class="txt" placeholder="请输入名称">
                <span class="text1 red">
                <?php if (Yii::app()->user->hasFlash('group_name')) {
                   echo Yii::app()->user->getFlash('group_name');
                }?>
            </span>
            </div>
        </div>  
        <div class="bottom">
            <input type="submit" value="确定" class="btn_com_blue" style="margin-right:100px">
            <a href="javascript:;" onclick="hiddenpop()" class="btn_com_gray">取消</a>
        </div>
        </form>
    </div>
</div>

<!--创建分组弹出框-->
<div class="popWrap" id="pop2" style="width:500px;display: none">
    <div class="pop_con">
        <form action="<?php echo Yii::app()->createUrl('mCenter/album/editPhotoSubclass')?>" method="post">
        <div class="title">
            修改分组
        </div>
            <input name="id" type="hidden" value="">
            <input name="groupid" type="hidden" value="">
        <div class="pop_content">
            <div class="fz">
                <input type="text" name="group_name" class="txt" placeholder="请输入名称">
                <span class="text1 red">
                <?php if (Yii::app()->user->hasFlash('group_name')) {
                   echo Yii::app()->user->getFlash('group_name');
                }?>
            </span>
            </div>
        </div>  
        <div class="btn">
            <input type="submit" value="确定" class="btn_com_blue" style="margin-right:100px">
            <a href="javascript:;" onclick="hiddenpop()"  class="btn_com_gray">取消</a>
        </div>
        </form>
    </div>
</div>

<!--图片上传弹出框-->
<div class="popWrap" id="pop" style="width:772px;display: none" >
    <form action="<?php echo Yii::app()->createUrl('mCenter/album/addPhoto',array('album_id'=>$albumId,'type'=>'1'))?>" method="post">
    <div class="pop_con">
        <div style="height:40px;"class="title">
            <span style="float:left;line-height:40px;">上传图片</span>
            <span style="font-weight:normal;float:left;line-height:40px;">上传到 
            <select name='album_group_id'>
                <?php if(!empty($list)) { ?>
                <?php foreach ($list as $key => $value) { ?>
                    <option value="<?php echo $value['id']?>"><?php echo $value['name']?></option>
                <?php } } ?>
            </select></span>
            <span style="float:right;line-height:40px;"><?php echo CHtml::fileField('upload')?></span>
        </div>

        <div style="clear:both;"></div>

        <div class="pop_content" >  
        	<div class="hidden"></div>        
        <div class="clear"></div>
        <div class="btn">           
            <input type="submit" value="确定" class="btn_com_blue" style="margin-right:100px">
            <a href="javascript:;" onclick="hiddenpop()"  class="btn_com_gray">取消</a>
        </div>
    </div>
    </form>
</div>
<script>
   $(".item").hover(function(){
    n=$(".item").index(this)
    $(".shade").eq(n).show()
    },function(){
    $(".shade").eq(n).hide()
    });
    function initpop(){
            var _w = $(window).width() //浏览器窗口宽度
            var _w = $(window).height() //浏览器窗口高度
            var _offsetW = $('#pop').width(); //获取弹出框的宽度
            var _offsetH = $('#pop').height(); //获取弹出框的高度

            var _left = ($(window).width()-$('#pop').width())/2;
            var _top = ($(window).height()-$('#pop').height())/2;

            $('#pop').css({'left' : _left, 'top' : 80});
    };

    function initpop1(){
        var _w = $(window).width() //浏览器窗口宽度
        var _w = $(window).height() //浏览器窗口高度
        var _offsetW = $('#pop1').width(); //获取弹出框的宽度
        var _offsetH = $('#pop').height(); //获取弹出框的高度

        var _left = ($(window).width()-$('#pop1').width())/2;
        var _top = ($(window).height()-$('#pop1').height())/2;

        $('#pop1').css({'left' : _left, 'top' : 200});
    };

    function showpop(){
        initpop();
        $('#pop').show("fast");
    }
    function showpop1(){
        initpop1();
        $('#pop1').show("fast");
    }
    function hiddenpop(){
        $('#pop').hide(300);
        $('#pop1').hide(300);
        $('#pop2').hide(300);
    }
</script>

    
<script>

    $(function(){
       photoSubclass_upload('upload','<?php echo UPLOAD_TO_PATH?>','<?php echo GJ_STATIC_JS?>','<?php echo UPLOAD_IMG_TYPE;?>','<?php echo IMG_GJ_FOLDER?>','<?php echo IMG_JKSC_THUMB?>','<?php echo IMG_GJ_LIST?>');
    });




    function show(img){
             $("#img1").attr('src','<?php echo IMG_GJ_LIST?>'+img);
     $("#htcon1").show();
     $("#del1").show();
    }
    <?php if(isset($show -> img) && !empty($show -> img)){?>
    show(<?php echo $show -> img?>);
    <?php }?>
    $(document).on('click', '.del', function() {
        $(this).closest(".item").remove();
    });


    function initpop2(){
           var _w = $(window).width() //浏览器窗口宽度
           var _w = $(window).height() //浏览器窗口高度
           var _offsetW = $('#pop2').width(); //获取弹出框的宽度
           var _offsetH = $('#pop2').height(); //获取弹出框的高度

           var _left = ($(window).width()-$('#pop2').width())/2; 
           var _top = ($(window).height()-$('#pop2').height())/2; 

           $('#pop2').css({'left' : _left, 'top' : 200});
   };

   function showpop2(id,groupid){
       initpop2();
       $('#pop2').show("slow");
       $('input[name="id"]').val(id);
       $('input[name="groupid"]').val(groupid);
   }


</script>