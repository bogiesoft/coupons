// JavaScript Document
//iframe框架高度自适应
function autoResize(id, a) {
    var objiframe = $('#' + id);
    if (isNaN(a)) {
        a = 0;
    }
    $(objiframe).height(a);
    var finalHeight = $(objiframe).contents().height();
    $(objiframe).height(finalHeight);
}

//弹出框居中方法
function _pop(id) {
    var obj = $('#' + id);

    var _w = $(window).width() //浏览器窗口宽度
    var _h = $(window).height() //浏览器窗口高度

    var _offsetW = obj.width(); //获取弹出框的宽度
    var _offsetH = obj.height(); //获取弹出框的高度

    var _left = (_w - _offsetW) / 2;
    var _top = (_h - _offsetH) / 2;

    obj.css({
        'left': _left,
        'top': _top
    });
}


//当form表单回车后，回车失效
function shadowPop() {
    $("#popShadow").show();
    $("#pop").show();
    _pop("pop");
}


//关闭弹出框
function closePop(id) {
    var obj = $("#" + id);
    $("#popShadow").hide();
    $("#pop").hide();
    $("#barCode").focus();
}

//只允许输入整数和小数点
//调用方式：onkeydown = "onlyNum(this,event)"
//8：退格键; 46：delete; 37-40: 方向键
//48-57:小键盘区的数字； 96-105：主键盘区的数字
//110\190:小键盘区和主键盘区的小数
function onlyNum(el, ev) {
    var event = ev || window.event;
    var currentKey = event.charCode || event.keyCode; //ie、FF下获取键盘码

    //当输入的值为空，按回车是提示输入用户输入金额
    if (ev.keyCode == 13 && el.value == "") {
        alert("请输入收款金额");
    } else if (ev.keyCode == 13 && document.activeElement.id == "amount") { //禁用回车事件
        $("#barCode").focus();
        shadowPop(); //弹出框居中显示方法
        return false; //禁用回车事件          
    }

    //除了48~57,96~105,两个小数点，左右键，退格键外其他的键不能输入
    if (!((currentKey >= 48 && currentKey <= 57) || (currentKey >= 96 && currentKey <= 105) || (currentKey == 110 && el.value.indexOf(".") < 0) || (currentKey == 190 && el.value.indexOf(".") < 0) || currentKey == 37 || currentKey == 39 || currentKey == 8)) {
        if (window.event) {
            event.returnValue = false;
        } else {
            event.preventDefault();
        }
    }

}

//初始化高度自适应
function autoHeight(a, b, c) {
    var obj_a = $("." + a); //头部
    var obj_b = $("." + b); //主体部分
    var obj_c = $("." + c); //尾部

    obj_b.css({
        "min-height": $(document).height() - obj_a.height() - obj_c.outerHeight()
    });
};

$(function() {
    autoHeight('kkf_loginBD', 'kkf_loginBD', 'footer');
    autoHeight('header', 'kkfmain', 'footer');
})



function navClick(obj) {
    if (!document.getElementsByTagName) return false;
    if (!document.getElementById) return false;
    if (!document.getElementById("handles1")) return false;
    obj.className = "current";
    var id;
    var divId;
    var linkid;
    var handle = document.getElementById("handles1");
    var links = handle.getElementsByTagName("a");
    for (i = 1; i <= links.length; i++) {
        id = "nav" + i;
        linkid = document.getElementById(id);
        divid = document.getElementById("b9e43e-" + i);
        if (id != obj.id) {
            linkid.className = "lnk";
            divid.style.display = "none";
        } else {
            divid.style.display = "block";
        }
    }
}

var main_obj = {
    list_init: function() {
        //编辑操作
        $('.operate').hover(
            function() {
                //得到当前对象距离底部的距离
                var pos = $(document).height() - $(this).offset().top;
                //当前对象隐藏的子对象的高度
                var height = $(this).find("dd").height();

                //判断假如显示层高度大于该层里底部的距离，则加“oper_hover_up”，否则则加“oper_hover”
                if (height > pos) {
                    $(this).css({
                        "z-index": 11
                    }).addClass('oper_hover_up');
                } else {
                    $(this).css({
                        "z-index": 11
                    }).addClass('oper_hover');
                }
            },
            function() {
                if ($(this).hasClass("oper_hover_up")) {
                    $(this).css({
                        "z-index": 10
                    }).removeClass('oper_hover_up')
                }
                if ($(this).hasClass("oper_hover")) {
                    $(this).css({
                        "z-index": 10
                    }).removeClass('oper_hover')
                }
            }
        );

        /* $(".operate a").click(function() {
             var html = $(this).html();

             $(this).blur();
             $(this).parent().prev().html(html + '<b></b>');
             $(this).parents("dl").removeClass('oper_hover')
         });*/


    }
}

$(function() {
    $(".gl li").click(function(e) {
        e.stopPropagation();
        $(this).siblings().removeClass("on");
        $(this).addClass("on");
        var _index = $(this).index();
        $(".guide_nav").eq(_index).removeClass("gl_active")
            .siblings().addClass("gl_active");
    })
    $(".koubeiLogo").click(function(e) {
        e.stopPropagation();
        $(".gl").fadeToggle();
    })
    $(document).click(function() {
        $(".gl").fadeOut();
    })
    $(".gl").click(function(e) {
        e.stopPropagation();
        $(this).show();
    })
    $(".btn_sure_next").click(function() {
        $(this).parent().next().removeClass("gl_active")
            .siblings().addClass("gl_active");
        $(".gl li").eq(1).addClass("on").siblings().removeClass("on");
    })

    $(".btn_filtrate").click(function() {
        $(this).toggleClass("active");
        if ($(this).hasClass("active")) {
            $(this).html("精简选项");
        } else {
            $(this).html("更多选项");
        }
        $(".more_area").stop(true, true).slideToggle();
    })

    $(".btn_attr_tag").click(function() {
        $('.new_group_attr').show();
        $('.new_group_term').hide();
        $(this).addClass("btn_com_blue").removeClass("btn_com_gray").siblings().addClass("btn_com_gray").removeClass("btn_com_blue");
    })
    $(".btn_term_tag").click(function() {
        $('.new_group_term').show();
        $('.new_group_attr').hide();
        $(this).addClass("btn_com_blue").removeClass("btn_com_gray").siblings().addClass("btn_com_gray").removeClass("btn_com_blue");
    })

    /*7天、14天、30天按钮切换*/
    $("#new_user_btn input[type='button']").click(function() {
        $(this).siblings("input[type='button']")
            .addClass("btn_com_gray")
            .removeClass("btn_com_blue");
        $(this).addClass("btn_com_blue")
            .removeClass("btn_com_gray");
    })

    //点击删除城市

    /*$(".chosen-select").chosen();*/

    $(".broadcast_box_top .icon1 a").click(function() {
        $(".broadcast_box_con").show();
        $(".broadcast_textarea").hide();
    })

    $(".broadcast_box_top .icon2 a").click(function() {
        $(".broadcast_box_con").hide();
        $(".broadcast_textarea").show();
    })

    /*选择素材时的瀑布流排列*/
    /* $(".material_pop_box_inner").masonry({});*/

    $(".material_pop_top .close_btn").click(function() {
        $(".material_pop_content").hide();
    })

    $(document).on("click", ".btn_add_from", function() {
        $(".material_pop_content").show();
    })

    //素材选中状态

    var $mask = $('<div class="material_pop_mask"></div>');
    var $selected = $('<i class="icon_card_selected"></i>');
    var $_mask = $mask.clone();
    $(".material_pop_box_item").hover(function() {
        $(this).append($mask);
    })

    $(document).on("click", ".material_pop_mask", function() {
        $(this).parent().append($selected);
        $(this).parent().append($_mask);
    });

    $(".user_list_order span").click(function() {
        $(this).find("i").toggleClass("arrow_up_active");
    })

    //回复关键字添加
    $(".btn_com_keyword").click(function() {
        var keyword = $("#Reply_key_word").val();
        if (keyword == "") {
            return false;
        } else {
            $(".keyword_name").append("<span>" + keyword + "<i>×</i><input style='display:none' value=" + keyword + " name='Reply[key_word][]'></span>")
        }
    })

    //回复关键字删除
    $(document).on("click", ".keyword_name span i", function() {
        $(this).parent().remove();
    });

    $(".dropdown_f span,.dropdown_s span").hover(function() {
        $(this).addClass("bg").siblings().removeClass("bg");
    })

    $("#date_num1").click(function(e) {
        e.stopPropagation();
        $(".dropdown_f").show();
        $(".dropdown_s").hide();
    })

    $("#date_num1").bind("input onpropertychange", function() {
        $(".dropdown_f").hide();
    })

    $(".dropdown_f span").click(function() {
        var text = $(this).html();
        $("#date_num1").val(text);
        $(".dropdown_f").hide();
    })

    $("#date_num2").click(function(e) {
        e.stopPropagation();
        $(".dropdown_s").show();
        $(".dropdown_f").hide();
    })

    $("#date_num2").bind("input onpropertychange", function() {
        $(".dropdown_s").hide();
    })

    $(".dropdown_s span").click(function() {
        var text = $(this).html();
        $("#date_num2").val(text);
        $(".dropdown_s").hide();
    })

    $(document).click(function() {
        $(".dropdown_s").hide();
        $(".dropdown_f").hide();
    })

    /* 左侧客服电话 */
    // $(".cellphone").hover(
    //     function() {
    //         $(".phone").show(500)
    //     },
    //     function() {
    //         $(".phone").hide()
    //     }
    // )
    // $(".backToptel .tel").hover(
    //     function() {
    //         $(".tel span").show(250)
    //     },
    //     function() {
    //         $(".tel span").hide()
    //     }
    // )

    /* 用户管理添加分组 打标签*/

    $("#add_tag").click(function() {
        $("#pop_tag").toggle();
    })

    $("#add_group").click(function() {
        $("#pop_group").toggle();
    })

    $(".cancel_btn").click(function() {
        $(this).parents(".pop_tag").hide();
    })

    $(".pop_sure_btn").click(function() {
        $(this).parents(".pop_tag").hide();
        // $("#add_group").removeClass("disabled").prop("disabled","false");
    })

    // $(".user_info_checkbox input").click(function() {
    //     var checkboxList = $(".user_info_checkbox input"),
    //         arr = [];
    //     for (var i = 0; i < checkboxList.length; i++) {
    //         if (checkboxList[i].checked == true) {
    //             $("#add_group").removeClass("disabled").prop("disabled", false);
    //         } else {
    //             arr.push("1");
    //         }

    //         if (arr.length === checkboxList.length) {
    //             $("#add_group").addClass("disabled").prop("disabled", true);
    //             $(".user_list_top #checkall").prop("checked", false);
    //         } else if (arr.length === 0) {
    //             $(".user_list_top #checkall").prop("checked", true);
    //         }else{
    //             $(".user_list_top #checkall").prop("checked", false);
    //         }
    //         console.log(arr);
    //     }
    // })

    // $(".user_list_top #checkall").click(function(){
    //     $(".user_info_checkbox input").prop("checked",$(this).is(":checked"));
    //     $(this).is(":checked") ? $("#add_group").removeClass("disabled").prop("disabled",false) : $("#add_group").addClass("disabled").prop("disabled",true); 
    // })
    /* 酒店图集 */
    $(document).on("mouseover", ".gallery-list li", function() {
        $(this).find(".icon-hotel").addClass("icon-hotel-6");
    });

    $(document).on("mouseout", ".gallery-list li", function() {
        $(this).find(".icon-hotel").removeClass("icon-hotel-6");
    });

    $(document).on("click", ".icon-hotel-6", function() {
        $(this).parent().remove();
    });

    /* 会员等级条件跳转 */
    var $js_radio = $("#js_level input[type='radio']"),
        $js_layer = $("#js_level .layer"),
        $js_layer_before = $js_layer.find(".arrow_top"),
        $js_title = $js_layer.find(".js_title"),
        $js_label = $("#js_level label");
    $js_tip = $js_layer.find(".tips");
    s = 0,
        _index = 0,
        title = "";
    $js_radio.click(function() {
        _index = $js_radio.index($js_radio.filter(":checked")), //获取选中状态的索引值
            title = $(this).attr("title");
        showLayer(_index);
    });

    function showLayer(_index) {
        $js_layer.show();
        $js_radio.eq(_index).prop("checked", true);
        if (_index === 1 || _index === 3) {
            $js_title.html(title);
            $js_tip.show();
        } else {
            $js_tip.find("js_title").html("");
            $js_tip.hide();
        }
        for (var i = 0; i < _index; i++) {
            s = s + $js_label.eq(i).width();
        }
        t_width = s + _index * 20; //获取弹出提示的三角需要偏离的位置
        $js_layer_before.css({
            "left": t_width
        });
        s = 0;
    };

    //默认显示设置
    // showLayer(0);

    // 新建分组-根据条件筛选
    //$(".filtrate_area .new_group_item").find("*").prop("disabled", "true");
    $(".filtrate_item li").click(function () {
        var cls = $(this).attr("class").split(" ")[0];
        if($(this).attr("n")==1){
            $(this).attr("n","");
            $(".filtrate_area .new_group_item").filter("." + cls).hide();
            delItem(cls);
        }else{
            $(this).attr("n","1");
            $(this).addClass("active");
            $(".filtrate_area").show();
            $(".filtrate_area .new_group_item").filter("." + cls).show();
            $(".filtrate_area .new_group_item").filter("." + cls).find("*").removeAttr("disabled");
            window.parent.callParAutoResize("main", $("body").height());
        }
    });
    function delItem(cls) {
        $(".filtrate_item li").filter("." + cls).removeClass("active"); //移除样式
        var list = $(".filtrate_area .new_group_item");
        // 隐藏filtrate_area
        var arr = [];
        for (var i = 0; i < list.length; i++) {
            if (getStyle(list[i], "display") === "none") {
                arr.push("1");
                if (arr.length == list.length) {
                    $(".filtrate_area").hide();
                }
            }
        }
        window.parent.callParAutoResize("main", $("body").height());
    }

    // 获取元素样式
    function getStyle(el, styleProp) {
        if (el.currentStyle)
            var y = el.currentStyle[styleProp];
        else if (window.getComputedStyle)
            var y = getComputedStyle(el, null).getPropertyValue(styleProp);
        // var y = getComputedStyle(el, null)[styleProp]; 同理
        return y;
    }

    $(document).on("click", ".icon_del", function() {
            var cls = $(this).parent().attr("class").split(" ")[1];
            $(this).parent().find("*").prop("disabled", "true");
            $(this).parent().hide();

            $(".filtrate_item li").filter("." + cls).removeClass("active"); //移除样式
            var list = $(".filtrate_area .new_group_item");
            // 隐藏filtrate_area
            var arr = [];
            for (var i = 0; i < list.length; i++) {
                if (getStyle(list[i], "display") === "none") {
                    arr.push("1");
                    if (arr.length == list.length) {
                        $(".filtrate_area").hide();
                    }
                }
            }
        })
        // 选择时间演示，只在点击消费金额时才有效
    $(document).on("click", ".js_datepicker", function() {
        var _this =  $(this);
        var val = layer.open({
            title: '请选择时间',
            btn: ['确定', '取消'],
            type: 1,
            skin: 'layui-layer-demo', //样式类名
            closeBtn: 1, //不显示关闭按钮
            shift: 2,
            shadeClose: true, //开启遮罩关闭
            content: '<div class="filtrate_datepicker_pop"><input type="text" class="text_group text_group_sm"><em> ~ </em><input type="text" class="text_group text_group_sm"></div>',
            yes : function(index,layero) {
                var googleCodeText = layero.find(".text_group").eq(0).val()+"~"+layero.find(".text_group").eq(1).val();
                if (googleCodeText) {
                    _this.html(googleCodeText);
                    _this.next().val(googleCodeText);
                }
                layer.close(index);
            }
        });
    })


    //积分规则

    // $(".btn_com_blue").click(function(){
    //     var inputList = $(".reward_points input"),
    //         re = /^[0-9]*[1-9][0-9]*$/;
    //     for (var i = 0; i < inputList.length; i++) {
    //        inputList[i].index = i;
    //        var result = re.test(inputList[i].value);
    //        if (!result) {
    //         alert("你输入第"+(i+1)+"个的格式有误");
    //        }
    //     }
    // })
})

//执行几秒后某个对象自动消息
//o:要消失的对象
//text:显示内容
//t:延迟的时间 如：1000（毫秒）
function delayHide(o,text,t){
    var obj;
    if($("."+o)){
        obj = $("."+o);
    }else{
        obj = $("#"+o);
    }
    obj.html(text).show();
    setTimeout(function(){obj.fadeOut()},t);
}


/**
 * 精准营销页面---开始
 * 通过数据库读取出来并赋值到页面对应的元素上
 */
//循环所有分组里的对象
function loopGroup(arr) {
    $.each(arr, function (name, value) {
        var seq = value["seq"];
        var obj_arr = $(".js_" + (seq + 1)).find('.f_children'); //获取input对象
        var val = value["value"];  //获取value值
        var store = value["store"]; //获取门店的值
        var date = value["date"];  //获取日期的值

        $(".js_" + (seq + 1)).show();  //数据库里有的显示层

        switch (seq) {
            case 0:
            case 2:
            case 3:
            case 4:
            case 5:
                var val_arr = val.split(',');
                $.each(obj_arr, function (index, obj) {
                    //判断form表单的对象
                    setValue(obj, val_arr[index])
                })
                break;
            case 1:
                var val_arr = transform(val);
                $.each(obj_arr, function (index, obj) {
                    //判断form表单的对象
                    setValue(obj, val_arr[index])
                });
                break;
            case 6:
            case 7:
            case 8:
            case 9:
            case 10:
                var store = store;
                var val_arr = transform(val);  //转换成数组
                var concat_arr = [];  //定义一个数组变量
                concat_arr.push(date);
                concat_arr.push(store); //把store的值存入数组里
                concat_arr = concat_arr.concat(val_arr);  //把val_arr的数组合并到这个数组里面并重新赋值
                $.each(obj_arr, function (index, obj) {
                    //判断form表单的对象
                    setValue(obj, concat_arr[index]);
                    setHtml(obj, concat_arr[index]);
                });
                break;
            case 11:
                var store = store;
                var val_arr = transform(val);  //转换成数组
                var concat_arr = [];  //定义一个数组变量
                concat_arr.push(store); //把store的值存入数组里
                concat_arr = concat_arr.concat(val_arr);  //把val_arr的数组合并到这个数组里面并重新赋值
                $.each(obj_arr, function (index, obj) {
                    //判断form表单的对象
                    setValue(obj, concat_arr[index])
                    setHtml(obj, concat_arr[index]);
                });
                break;
        }
    })
}

//设置值
function setValue(obj, val) {
    var obj = $(obj);
    if (obj.is('input') || obj.is('select') || obj.is('textarea')) {
        if (obj.is('input[type=checkbox]')) {
            if (val) {
                obj.parents(".new_group_item_c").find("input").each(function () {
                    if ($(this).val() == val) {
                        $(this).attr('checked', true);
                    }
                })
            }
        } else {
            obj.val(val);

        }
    } else {
        obj.html(val);
    }
}

//给对应的em元素赋值
function setHtml(obj, text) {
    var em = $(obj).prev();

    //判断有class="choosed",但是没有class="js_datepicker"的
    if (em.hasClass("choosed") && em.hasClass("js_datepicker") == false) {
        if (text == "") {
            em.html("全部门店");
        } else {
            em.html("(已有" + (text.length - 1) + "家门店)");
        }
    }

    //判断class="choosed" class="js_datepicker"都有的
    if (em.hasClass("choosed") && em.hasClass("js_datepicker")) {
        if (text == "") {
            em.html("任意时间");
        } else {
            em.html(text);
        }
    }
}

//转换成数组
function transform(obj) {
    var arr = [];
    for (var item in obj) {
        arr.push(obj[item]);
    }
    return arr;
}
/**
 * 精准营销页面---结束
 * 通过数据库读取出来并赋值到页面对应的元素上
 */

/*
新建分组判断获取form值
*/
function getValue(obj,className){
    //获得所有可见对象
    //循环对象，获取值，把值赋在指定的格式里
    var arr = new Array(); //存放对象值
    var v = "";

    var obj = $("."+obj);

    obj.each(function(index,value){
        //只对div显示的对象进行操作
        if($(this).is(":visible")){
            //对不同一类的索引进行相应的操作
            switch ($(this).attr('n')){
                case 'condition_sex':
                case 'condition_source':
                case 'condition_grade':
                case 'condition_value':
                    $(this).find(className).each(function(indexC,valueC){
                        //判断form表单的对象
                        if($(valueC).is('input') || $(valueC).is('select') || $(valueC).is('textarea')){
                            if($(valueC).is('input[type=checkbox]') || $(valueC).is('input[type=radio]')){
                                if($(valueC).is(':checked')){
                                    v += $(valueC).val()+ ",";
                                }
                            }else {
                                if ($(valueC).val()) {
                                    v += $(valueC).val()+",";
                                } else {
//                                    alert("请填写值");
//                                    return false;
                                }
                            }
                        }
                    })
                    /*if(!v)
                     {
                     v = "";
                     break;
                     }*/
                    arr.push({
                        "seq"	: index,
                        "name"	: $(value).attr("n"),
                        "value" : v.substring(0,v.length-1)
                    });
                    v = "";
                    break;
                case 'condition_age':
                    $(this).find(className).each(function(indexC,valueC){
                        if($(valueC).is('input') || $(valueC).is('select') || $(valueC).is('textarea')){
                            if($(valueC).is('input[type=checkbox]') || $(valueC).is('input[type=radio]')){
                                if($(valueC).is(':checked')){
                                    v += $(valueC).val()+ ",";
                                }
                            }else {
                                if ($(valueC).val()) {
                                    v += $(valueC).val()+",";
                                } else {
//                                    alert("请填写值");
//                                    return false;
                                }
                            }
                        }
                    })
                    var valuemin = v.split(",")[0];
                    var valuemax = v.split(",")[1];
                    /* if(!valuemin || !valuemax)
                     {
                     v = "";
                     break;
                     }*/

                    arr.push({
                        "seq"	: index,
                        "name"	: $(value).attr("n"),
                        "value" :{
                            "valuemin" : v.split(",")[0],
                            "valuemax" : v.split(",")[1]
                        }
                    });
                    v = "";
                    break;
                case 'condition_birthday':
                    $(this).find(className).each(function(indexC,valueC){
                        //判断form表单的对象
                        if($(valueC).is('input') || $(valueC).is('select') || $(valueC).is('textarea')){
                            if ($(valueC).val()) {
                                v = $(valueC).val();
                            }
                        }
                    })
                    /*if(!v)
                     {
                     v = "";
                     break;
                     }*/

                    arr.push({
                        "seq"	: index,
                        "name"	: $(value).attr("n"),
                        "value" : v,
                    });
                    v = "";
                    break;
                case 'condition_consum_amount':
                case 'condition_consum_unitprice':
                case 'condition_consum_highestprice':
                case 'condition_consum_count':
                case 'condition_consum_days':
                    $(this).find(className).each(function(indexC,valueC){
                        if($(valueC).is('input') || $(valueC).is('select') || $(valueC).is('textarea')){
                            if($(valueC).is('input[type=checkbox]') || $(valueC).is('input[type=radio]')){
                                if($(valueC).is(':checked')){
                                    v += '1'+ ",";
                                } else {
                                    v += ''+""+",";
                                }
                            }else {
                                if ($(valueC).val()) {
                                    v += $(valueC).val()+",";
                                } else {
//                                    alert("请填写值");
                                    if($(valueC).hasClass('daterange_picker') || $(valueC).hasClass('store_ids'))
                                        v += $(valueC).val()+",";
                                    else
                                    {

                                    }
//                                		return false;
//                                    return false;
                                }
                            }
                        }
                    })
                    var valuemin = v.split(",")[v.split(",").length-3];
                    var valuemax = v.split(",")[v.split(",").length-2];
                    /*if(!valuemin || !valuemax)
                     {
                     v = "";
                     break;
                     }*/

                    arr.push({
                        "seq"	: index,
                        "name"	: $(value).attr("n"),
                        "date"	: v.split(",")[0],
                        "store"	: v.split(",").slice(1,v.split(",").length-3),
                        "value"	: {
                            "valuemin" : v.split(",")[v.split(",").length-3],
                            "valuemax" : v.split(",")[v.split(",").length-2]
                        }
                    });

                    v = "";
                    break;
                case 'condition_not_consum_days':
                    $(this).find(className).each(function(indexC,valueC){
                        if($(valueC).is('input') || $(valueC).is('select') || $(valueC).is('textarea')){
                            if($(valueC).is('input[type=checkbox]') || $(valueC).is('input[type=radio]')){
                                if($(valueC).is(':checked')){
                                    v += '1'+ ",";
                                } else {
                                    v += ''+""+",";
                                }
                            }else {
                                if ($(valueC).val()) {
                                    v += $(valueC).val()+",";
                                } else {
//                                    alert("请填写值");
                                    if($(valueC).hasClass('daterange_picker') || $(valueC).hasClass('store_ids'))
                                        v += $(valueC).val()+",";
                                    else
                                    {
                                        //return false;
                                    }
                                }
                            }
                        }
                    })
                    var valuemin = v.split(",")[v.split(",").length-3];
                    var valuemax = v.split(",")[v.split(",").length-2];
                    /*if(!valuemin || !valuemax)
                     {
                     v = "";
                     break;
                     }*/
                    arr.push({
                        "seq"	: index,
                        "name"	: $(value).attr("n"),
                        "store"	: v.split(",").slice(0,v.split(",").length-3),
                        "value"	: {
                            "valuemin" : v.split(",")[v.split(",").length-3],
                            "valuemax" : v.split(",")[v.split(",").length-2]
                        }
                    });

                    v = "";
                    break;
            }
        }
    })
    return arr;
}

var getConValue = function(itemSelector) {
    var itemJq = $(itemSelector),
        name = itemJq.attr("n");
    var value = {},
        date = {},
        store = {};
    switch (name) {
        case "condition_sex":
            var _sex = '';
            $(".filtrate_area .js_1 input").each(function() {
                if ($(this).prop("checked")) {
                    _sex += ((_sex == '') ? '' : ',') + $(this).val();
                }
            })
            value.valuemin = _sex;
            break;
        case "condition_age":
            value.valuemin = $('.filtrate_area .js_2 .start_age').val();
            value.valuemax = $('.filtrate_area .js_2 .end_age').val();
            break;
    }
}

$(function() {
    $(".btn_com_calc").click(function() {
        var filterList = $(".filtrate_area .new_group_item").filter(":visible");
        var requestData = []; //发送后台数据
        filterList.each(function(i) {
            var name = $(this).attr('n');
            requestData[i] = $.extend({
                seq: i,
                name: name
            }, getConValue($(this)));
        });
        // console.log(requestData[1].value);
    })
})
