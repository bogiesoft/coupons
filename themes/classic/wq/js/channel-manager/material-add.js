//素材添加

//左边：
//1. 鼠标滑上去左边控制器显示, 鼠标滑走左边控制器隐藏
//3. 添加后增加一个素材，对应的id+1
//4. 删除的时候要有提示“确定删除此篇图文吗” 第一个素材图文不能删除
//5. 添加后“还可以再添加7篇文章”的数字跟着改变
//6.左边点击某个素材后右边对应的内容也跟着变化
function material(){
    control(); //初始化控件

    $('.image-text').data('cur_id', $('.image-text').attr("id"));  //存入第一个对象的id
    pasteVal();//默认进来右边的值按左边第一个对象来值赋

    //添加选项
    $(".image-text-btn").click(function(){
        var num = $(".image-text").size(); //获得当前的数量
        var reg = /\d+/g;
        var lastId = parseInt(getMaxNum(".image-text"))+1; //获取最大值 并+1

        //大于最大数后不能添加
        if(num>=8){
            alert('你最多只可以加入8篇文章！');
            return false;
        }
        //移除事件处理
        $('.image-text').off();
        //获取最后一个对象的html,并赋值给新的对象，id以0开始的所以赋值当前对象的数量给它，然后插入之前最后一条记录的后面
        $('<div class="image-text" id="imageText'+ lastId +'">'+$('.image-text:last').html()+'</div>').insertAfter($('.image-text:last'));
        //清楚添加对象已有的值
        $('.imageText-t:last').html('标题').parent().prev().children("img").attr("src","");
        //清空所有input的值
        $('.image-text:last input').val('');
        //替换name里data后面的数字
        $('.image-text:last input').each(function(){
            var nameResult = $(this).attr("name").replace(reg,lastId);
            $(this).attr("name",nameResult);
        });
        $('.image-text:last input[name="Material[Data'+ lastId +'][Rate]"]').val(lastId);  //赋值序号
        $(".imageText-num").html(7-num);  //还可以再添加 n 篇文章
        control(); //再次加载下控制
    });

    //左侧点击项后把当前点击对象的id存入到数据里
    $(document).on('click','.image-text',function(){
        //点击的时候把之前的文本编辑器里的内容赋值给左边的val里
        var prevId_num = prev_id.slice(10);  //获取当前层的序号
        $(prev_id).find('input[name="Material[Data'+ prevId_num +'][Content]"]').val(UE.getEditor('right_content').getContent());
        var _id = "#" + $(this).attr("id");
        prev_id = _id; //把当前的对象赋给之前的
        var id_num = $(this).attr("id").slice(9);  //获取当前层的序号

        $('.image-text').data('cur_id', _id);
        $(this).addClass("image-text-cur").siblings().removeClass("image-text-cur");
        pasteVal($(this),id_num); //如果有值就赋值
    });

    //删除选项
    $(document).on('click','a[href=#del]',function(){
        var delId = $("input[name='MaterDelId']");
        var cur_id = $(this).parents('.image-text').attr("id");
        var id_num = cur_id.slice(9);  //获取当前的id里的数字
        var str_delId_val = delId.val(); //获取放置值的input的value值

        if($('#'+cur_id + ' input[name="Material[Data'+ id_num +'][id]"]').val()){
            str_delId_val += $('#'+cur_id + ' input[name="Material[Data'+ id_num +'][id]"]').val() + ","; //转换成字符串
        }
        delId.val(str_delId_val); //赋值给放置input的值里面
        $(this).parents(".image-text").remove();
        control(); //再次加载下控制
        //少了个询问
    });

    //标题输入后，左边变值
    $('#mater-add-r input').filter('[name="Title[]"]').on('keyup change paste blur', function(){
        var cur_id = $('.image-text').data('cur_id');
        var id_num = cur_id.slice(10);  //获取当前层的序号

        if($(this).val() == ""){
            $(cur_id+' input[name="Material[Data'+ id_num +'][Title]"]').val("");
            $(cur_id+' .imageText-t').html("标题");
        }else{
            $(cur_id+' input[name="Material[Data'+ id_num +'][Title]"]').val($(this).val());
            $(cur_id+' .imageText-t').html($(this).val());
        }
        textLimit($(this),"64"); //当输入框改变后，显示实际数字
    });

    //作者
    $('#mater-add-r input').filter('[name="Author[]"]').on('keyup change paste blur', function(){
        var cur_id = $('.image-text').data('cur_id');
        var id_num = cur_id.slice(10);  //获取当前层的序号

        if($(this).val() == ""){
            $(cur_id+' input[name="Material[Data'+ id_num +'][Author]"]').val("");
        }else{
            $(cur_id+' input[name="Material[Data'+ id_num +'][Author]"]').val($(this).val());
        }
        textLimit($(this),"8"); //当输入框改变后，显示实际数字
    });

    //摘要
    $('#mater-add-r textarea').filter('[name="Abstract[]"]').on('keyup change paste blur', function(){
        var cur_id = $('.image-text').data('cur_id');
        var id_num = cur_id.slice(10);  //获取当前层的序号

        if($(this).val() == ""){
            $(cur_id+' input[name="Material[Data'+ id_num +'][Abstract]"]').val("");
        }else{
            $(cur_id+' input[name="Material[Data'+ id_num +'][Abstract]"]').val($(this).val());
        }
        textLimit($(this),"120"); //当输入框改变后，显示实际数字
    });

    //跳转链接
    $('#mater-add-r input').filter('[name="Url[]"]').on('keyup paste blur', function(){
        var cur_id = $('.image-text').data('cur_id');
        var id_num = cur_id.slice(10);  //获取当前层的序号

        if($(this).val() == ""){
            $(cur_id+' input[name="Material[Data'+ id_num +'][Url]"]').val("");
        }else{
            $(cur_id+' input[name="Material[Data'+ id_num +'][Url]"]').val($(this).val());
        }
    });

    $("input[name='coverText']").click(function(){
        var cur_id = $('.image-text').data('cur_id');
        var id_num = cur_id.slice(10);  //获取当前层的序号

        if($(this).prop("checked") == false){
            $(cur_id+' input[name="Material[Data'+ id_num +'][If_show]"]').val("");
        }else{
            $(cur_id+' input[name="Material[Data'+ id_num +'][If_show]"]').val("1");
        }
    });

    //箭头向上点击移动功能
    $(document).on('click','.js-mater-up',function(){
        var obj =  $(this).parents('.image-text');
        materMove(obj,"up");
    });

    //箭头向下点击移动功能
    $(document).on('click','.js-mater-down',function(){
        var obj =  $(this).parents('.image-text');
        materMove(obj,"down");
    });
}

//实际输入的字数显示
function textLimit(obj,maxlength){
    var act_len = $(obj).val().length;
    if(act_len <= maxlength){
        $(obj).prev().find("em").html(act_len);
    }
}

//获得对象id里的最大值
function getMaxNum(obj){
    var arr = new Array();
    var arrMax;

    //把值放入数组里
    $(obj).each(function(){
        arr.push($(this).attr("id").slice(9));
    })

    //获取最大值
    arrMax = arr[0];
    for(var i=1;i<arr.length;i++){
        if(arrMax<arr[i]){
            arrMax=arr[i];
        }
    }
    arr = []; //清空数组
    return arrMax;
}

//鼠标滑上去的效果
function control(){
    var imageText = $('.image-text');
    imageText.each(function(){
        $(this).hover(
            function(){
                //当为第一个的时候，上移箭头隐藏
                $(this).find(".control").show();
            },
            function(){
                $(this).find(".control").hide();
            }
        )
    });
}

//把左边有值的赋给右边的内容
function pasteVal(obj,num){
    //为空的时候就获取第一个对象
    if(!obj){
        obj = $(".image-text:first");
    }
    //为空的时候就为第一个对象
    if(!num){
        num = $(".image-text:first").attr("id").slice(9);
    }

    //标题
    $('#mater-add-r input').filter('[name="Title[]"]').val(obj.find('input[name="Material[Data'+ num +'][Title]"]').val());
    textLimit($('#mater-add-r input').filter('[name="Title[]"]'),"120"); //当输入框改变后，显示实际数字
    //作者
    if($('#mater-add-r input').filter('[name="Author[]"]').val()){
        $('#mater-add-r input').filter('[name="Author[]"]').val(obj.find('input[name="Material[Data'+ num +'][Author]"]').val());
        textLimit($('#mater-add-r input').filter('[name="Author[]"]'),"120"); //当输入框改变后，显示实际数字
    }
    //摘要
    $('#mater-add-r textarea[name="Abstract[]"]').val(obj.find('input[name="Material[Data'+ num +'][Abstract]"]').val());
    textLimit($('#mater-add-r textarea[name="Abstract[]"]'),"120"); //当输入框改变后，显示实际数字
    //连接
    if($('#mater-add-r input').filter('[name="Url[]"]').val()){
        $('#mater-add-r input').filter('[name="Url[]"]').val(obj.find('input[name="Material[Data'+ num +'][Url]"]').val());
    }
    //文本编辑器的值
    UE.getEditor('right_content').setContent(obj.find('input[name="Material[Data'+ num +'][Content]"]').val());

    if(obj.find('input[name="Material[Data'+ num +'][If_show]"]').val() == ""){
        $("input[name='coverText']").prop("checked",false);
    }else{
        $("input[name='coverText']").prop("checked",true);
    }

}

function materMove(obj,direction){
    //移动的对象，方向，
    // 如果向下移动，则复制这个移动对象，放到它的下面
    //如果向上移动，则复制这个移动对象，放到它的上面
    var obj = $(obj);

    if(direction == "down"){
        obj.next().after(obj);
    }else{
        obj.prev().before(obj);
    }
    control(); //再次加载下控制
}
