//1. 选中下面的时候周全部选中
function inputCheckedText(obj) {
    var arr = "";
    var objArr = $(obj + " input[type='checkbox']:checked");
    objArr.each(function () {
        arr += $(this).parent().text() + ",";
    });
    return arr;
}

function partialTime() {
    var partialTimeR = $(".js-partial-time-r");
    var partialDateL = $("#left_available_time");
    var partialTimeL = $("#left_available_hour2");

    $(".js-partial-time-r").click(function () {
        var reg = /^[0-9]*[1-9][0-9]*$/; //判断正整数
        var valDate = inputCheckedText(".js-partial-time-r");
        var valTime = "";
        var startTimeVal = $("input[name='Coupons[begin_hour]']").val();
        var endTimeVal = $("input[name='Coupons[end_hour]']").val();

        if (startTimeVal && !endTimeVal) {
            valTime += startTimeVal + "：00" + " - 24：00";
        }
        if (!startTimeVal && endTimeVal) {
            valTime += "00：00 - " + endTimeVal + "：00";
        }
        if (startTimeVal && endTimeVal) {
            if (startTimeVal <= endTimeVal) {
                valTime += startTimeVal + "：00" + " - " + endTimeVal + "：00";
            }
        }

        if (!startTimeVal && !endTimeVal) {
            valTime += "全天可用";
        }
        partialDateL.html(valDate.slice(0, valDate.length - 1));
        partialTimeL.html(valTime);
        valTime = "";
    });

    //可用时段切换
    $("input[name='Coupons[available_time]']").click(function () {
        if ($(this).val() == "1") {
            partialTimeR.hide();
            partialTimeL.html("全天可用");
            $("input[name='Coupons[begin_hour]']").val("");
            $("input[name='Coupons[end_hour]']").val();
        } else {
            partialTimeR.show();
            $(".js-partial-time-r input[type='checkbox']").each(function () {
                $(this).prop("checked", true)
            });
            partialDateL.html("周一至周日");
            partialTimeL.html("全天可用");
        }
    });

    //可用时段 前时间段改变后,比较前后的大小
    $("input[name='Coupons[begin_hour]").change(function () {
        var startTimeVal = parseInt($(this).val());
        var endTimeVal = parseInt($("input[name='Coupons[end_hour]']").val());
        if (startTimeVal && endTimeVal) {
            if (startTimeVal > endTimeVal) {
                $("#hour_error").show();
            }
        } else {
            $("#hour_error").hide();
        }
    });

    $("input[name='Coupons[end_hour]").change(function () {
        var startTimeVal = parseInt($("input[name='Coupons[begin_hour]']").val());
        var endTimeVal = parseInt($(this).val());
        if (startTimeVal && endTimeVal) {
            if (startTimeVal > endTimeVal) {
                $("#hour_error").show();
            }
        } else {
            $("#hour_error").hide();
        }
    })
}

/**
 * 函数功能：增加及移除 class
 * param {string} 要传入的 DOM 节点
 * param {string} 要移除的 class,例如你要移除 Color82,也可以传入 Color
 * param {string} 要新增加的 class
 * date: 2016/6/27
 */
function addClassFun(el, name, newClass) {
    var classNameArr = el.className.split(" ");
    var re = new RegExp('\\b' + name + '\\w*\\b', 'g');
    var classVal = el.className.match(re).toString();
    for (var i = 0; i < classNameArr.length; i++) {
        if (classNameArr[i] === classVal) {
            classNameArr.splice(i, 1);
            el.className = classNameArr.toString().replace(/,/g, " ");
            el.className += newClass;
        }
    }
}

//弹出位置方法
function popShow(el) {
    //显示位置计算
    var offset_y = $(window.parent.document).scrollTop() + 100;
    var offset_x = 100;
    var browserW = $(window).width();
    var browserH = $(window.parent.parent).height();
    var pop_obj = el;
    var pop_width = pop_obj.width();
    var pop_height = pop_obj.height();
    var x = (browserW - pop_width) / 2 + offset_x;
    var y = (browserH - pop_height) / 2 + offset_y;
    if (x < 410) {
        x = 410;
    }
    if (y < 300) {
        y = 300;
    }
    //设置显示位置
    pop_obj.css({'left': x, 'top': y});
    pop_obj.show();
}

$(function () {
    partialTime();  //引用可用时段方法
    // 优惠券内容完整显示
    $(".js-cp-content").each(function () {
        $(this).attr("title", $(this).text());
    });

    //添加自定义入口
    $("#js-add-entry").click(function () {
        var entrytpl = $(".js-entry-tpl");
        entrytpl.show();
        window.parent.callParAutoResize("main", $("body").height());
    });

    //删除营销入口
    $(".js-entry-del").click(function () {
        $(this).parents(".js-entry-tpl").hide();
    });

    //优惠券颜色切换
    var colorWrap = $(".js-cp-color"),
        colorList = colorWrap.find(".color-list"),
        selectBox = colorWrap.find(".selected-box"),
        selectedColor = colorWrap.find("#selected-color")[0],
        cpLeft = $(".js-cp-l")[0],
        showuseBtn = $(".btn-showuse")[0],
        colorListItem = colorWrap.find(".color-list li");

    //颜色选择框显示隐藏
    selectBox.click(function () {
        colorList.toggle();
    });

    //相应的模块背景色改变
    colorListItem.click(function (e) {
        e.stopPropagation();
        var colorNow = $(this).attr("class");
        $("#Couponscolor").val(colorNow);
        colorNow = " " + colorNow;
        colorList.hide();
        addClassFun(cpLeft, "Color", colorNow);
        addClassFun(selectedColor, "Color", colorNow);
        addClassFun(showuseBtn, "Color", colorNow);
    });

    //不同优惠券切换
    var cpTypeRadio = $(".js-cp-type input");
    cpTypeRadio.click(function () {
        var cpVal = $(this).val(); //输出的是字符串类型。在前面加等号转为数值型
        if (+cpVal === 2) {
            $(".js-cp-voucher").show();
            $(".js-cp-discount").hide();
            $(".js-cp-exchange").hide();
            $(".js-cp-use").show();
            $(".js-cp-title").html("代金券");
        } else if (+cpVal === 3) {
            $(".js-cp-voucher").hide();
            $(".js-cp-discount").show();
            $(".js-cp-exchange").hide();
            $(".js-cp-use").hide();
            $(".js-cp-title").html("折扣券");
        } else {
            $(".js-cp-voucher").hide();
            $(".js-cp-discount").hide();
            $(".js-cp-exchange").show();
            $(".js-cp-use").show();
            $(".js-cp-title").html("兑换券");
        }
    });

    // 有效时间
    $(".m-input-group select[name='Coupons[effective_days]']").val("30");
    var startDay;
    var effectiveDay;
    var timeInput = $(".js-effective-time .input-group").find("select");
    $(".js-effective-time input[type='radio']").click(function () {
        startDay = $(".m-input-group select[name='Coupons[start_days]']").val();
        effectiveDay = $(".m-input-group select[name='Coupons[effective_days]']").val();
        if ($(this).val() == 2) {
            timeInput.prop("disabled", false);
            $("input[name='Coupons[time]']").prop("disabled", true);
            if (startDay == 0) {
                $("#left_time").html("领取后，当天生效，有效天数" + effectiveDay + "天");
            } else {
                $("#left_time").html("领取后，" + startDay + "天生效，有效天数" + effectiveDay + "天");
            }
        } else {
            var t = $("input[name='Coupons[time]']").val();
            $('#left_time').html(t.replace(/\//g, "."));
            $("input[name='Coupons[time]']").prop("disabled", false);
            timeInput.prop("disabled", true);
        }
    });

    // 折扣额度
    $("input[name='Coupons[discount]']").on("keyup", function () {
        $(this).val($(this).val().substring(0, 3));
    });

    $(".m-input-group select[name='Coupons[start_days]']").change(function () {
        startDay = $(".m-input-group select[name='Coupons[start_days]']").val();
        effectiveDay = $(".m-input-group select[name='Coupons[effective_days]']").val();
        if (startDay == 0) {
            $("#left_time").html("领取后，当天生效，有效天数" + effectiveDay + "天");
        } else {
            $("#left_time").html("领取后，" + startDay + "天生效，有效天数" + effectiveDay + "天");
        }
    });

    $(".m-input-group select[name='Coupons[effective_days]']").change(function () {
        startDay = $(".m-input-group select[name='Coupons[start_days]']").val();
        effectiveDay = $(".m-input-group select[name='Coupons[effective_days]']").val();
        if (startDay == 0) {
            $("#left_time").html("领取后，当天生效，有效天数" + effectiveDay + "天");
        } else {
            $("#left_time").html("领取后，" + startDay + "天生效，有效天数" + effectiveDay + "天");
        }
    });

    // 可用时段之部分时段显示隐藏
    $(".js-available-time input[type='radio']").click(function () {
        if ($(this).val() == 2) {
            //window.parent.callParAutoResize("main",$("body").height());
            $(".js-partial-time").show();
        } else {
            $(".js-partial-time").hide();
        }
    });

    //下一步
    // $("#js-cp-next").click(function () {
    //     $(".s-step").addClass("active").siblings().removeClass("active");
    //     $(".js-step-2").show().siblings(".js-step-1").hide();
    //     $(window).scrollTop(0);
    //     $(parent.document).scrollTop(0);
    // });

    //上一步
    $("#js-cp-prev").click(function () {
        $(".f-step").addClass("active").siblings().removeClass("active");
        $(".js-step-1").show().siblings(".js-step-2").hide();
        $(window).scrollTop(0);
        $(parent.document).scrollTop(0);
        window.parent.callParAutoResize("main", $("body").height());
    });

    //投放优惠券
    var n = 1;
    var launchCpItme = $(".js-cp-pop-item");

    $(".js-launch-next").click(function () {
        $(".s-step").addClass("active").siblings().removeClass("active");
        n = $(".js-launch-cp-radio").filter(":checked").attr("n");
        if (n == 1) {
            launchCpItme.hide();
            launchCpItme.eq(1).show();
        } else if (n == 2) {
            launchCpItme.hide();
            launchCpItme.eq(2).show();
        }
    });

    $(".js-launch-prev").click(function () {
        $(".f-step").addClass("active").siblings().removeClass("active");
        launchCpItme.hide();
        launchCpItme.eq(0).show();
    });

    // 营销入口跳转链接
    var jumpLinkRadio = $(".js-jump-link").find("input[type='radio']");
    jumpLinkRadio.click(function () {
        n = $(this).attr("n");
        var jumpLink = $(this).parents(".radio-inline").siblings(".form-group");
        if (n == 1) {
            jumpLink.hide();
            jumpLink.eq(0).show();
        } else if (n == 2) {
            jumpLink.hide();
            jumpLink.eq(1).show();
        }
    });

    //适用门店
    var applyStoreRadio = $(".js-apply-store").find("input[type='radio']"),
        applyStoreAdd = $(".js-apply-store").find(".m-add-btn");
    applyStoreRadio.click(function () {
        n = $(this).attr("n");
        if (n == 2) {
            applyStoreAdd.show();
        } else {
            applyStoreAdd.hide();
        }
    });

    //门店选择

    $(".parentsBox").click(function () {
        if ($(this).is(":checked")) {
            $(this).parent().find("input").prop("checked", true);
        } else {
            $(this).parent().find("input").prop("checked", false);
        }
        var haschecked = $(".storeBox:checked").length;
        $('#haschecked').html(haschecked);
    });

    $(".parentBox").click(function () {
        var allInput = $(this).parents(".child1").find("input");
        $(this).is(":checked") ? allInput.prop("checked", true) : allInput.prop("checked", false);
        var haschecked = $(".storeBox:checked").length;
        $('#haschecked').html(haschecked);

    });

    $(".childBox").click(function () {
        var allInput = $(this).parent().siblings().find(".child4 input");
        $(this).is(":checked") ? allInput.prop("checked", true) : allInput.prop("checked", false);
        var haschecked = $(".storeBox:checked").length;
        $('#haschecked').html(haschecked);

    });

    $(".parentWrap .rbArrow").click(function () {
        $(this).parent().toggleClass("closeFolder");
        $(this).parent().siblings().toggle();
    });

    $(".childWrap .rbArrow").click(function () {
        $(this).parent().toggleClass("closeFolder");
        $(this).parent().siblings().find(".child4").toggle();

    });

    // //选择链接弹出框
    $(".js-choose-link").click(function () {
        popShow($(".links-pop"));
    });

    if ($(".js-cp-l").length !== 0) {
        var cpLeftY = $(".js-cp-l").offset().top;
    }

    // 创建优惠券手机版预览滚动悬停
    var iframeParentMain = window.parent.document.getElementById('main');
    if (iframeParentMain) {
        var parentIframeY = iframeParentMain.offsetTop;
    }
    var cpLeftTotalY = cpLeftY + parentIframeY;
    $(window.parent).on("scroll", function () {
        var parentScrollTop = $(this).scrollTop();
        if (parentScrollTop > cpLeftTotalY) {
            $(".js-cp-l").addClass("fixed");
            $(".js-cp-l").css("top", parentScrollTop - parentIframeY);
        } else {
            $(".js-cp-l").removeClass("fixed");
            $(".js-cp-l").css("top", "");
        }
    });
    // 物业设置费用-电费分时段
    function changeBtnClass(el) {
        el.removeClass("btn-default").addClass("btn-primary")
            .siblings().addClass("btn-default").removeClass("btn-primary");
    }

    $(".js-divide-btn").click(function () {
        changeBtnClass($(this));
        $(".js-undivide-wrapper").hide();
        $(".js-divide-wrapper").show();
    });
    $(".js-undivide-btn").click(function () {
        changeBtnClass($(this));
        $(".js-undivide-wrapper").show();
        $(".js-divide-wrapper").hide();
    });

    var registerNavList = $(".register-account-nav-item");
    registerNavList.click(function () {
        var _index = $(this).index();
        $(this).addClass("active").siblings().removeClass("active");
        if (_index === 0) {
            $(".register-account-email").hide();
            $(".register-account-phone").show();
        } else {
            $(".register-account-email").show();
            $(".register-account-phone").hide();
        }
    });

    $('.monthpicker').each(function () {
        new MonthPicker($(this));
    });

    // 新建分组-根据条件筛选
    $(".filtrate_area .new_group_item").find("*").prop("disabled", "true");
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
    $(document).on("click", ".icon_del", function () {
        var cls = $(this).parent().attr("class").split(" ")[1];
        $(this).parent().find("*").prop("disabled", "true");
        $(this).parent().hide();
        delItem(cls);
    });

    /**
     * 按钮组切换
     * @param el
     * @param cls
     */
    function btnGroupSwitch(el, cls) {
        el.addClass(cls).siblings().removeClass(cls);
    }

    // 弹出窗口关闭
    $(".m-pop-close,.m-pop-cancel-btn").click(function () {
        $(this).parents(".m-pop").hide();
    });

    $(".js-choose-group,.precise-del-group-btn").click(function () {
        popShow($(".choose-group-pop"));
        btnGroupSwitch($(this), 'active');
    });

    $(".js-choose-cp").click(function () {
        popShow($(".choose-coupon-pop"));
    });

    $(".js-order-num-search").click(function () {
        $(".order-search-box").show();
    });
    $(".hide-order-search-box").click(function () {
        $(".order-search-box").hide();
    });

    // 支付渠道多选调用
    $(".js-pay-way-multiple").select2();
    $(".js-pay-way-multiple").on('select2:select', function () {
        select2Auto();
    });

    $(".js-pay-way-multiple").on('select2:unselect', function (e) {
        select2Auto();
    });

    // 交易数据展开收起
    $(".js-trade-up-down").click(function () {
        var switchEle = $(this).parents(".trade-summary-data").siblings(".trade-data-list-wrapper");
        if ($(this).find(".fa").hasClass("fa-angle-double-up")) {
            $(this).html('<i class="fa fa-angle-double-down"></i>展开');
            switchEle.hide();
            window.parent.callParAutoResize("main", $("body").height());

        } else {
            $(this).html('<i class="fa fa-angle-double-up"></i>收起');
            switchEle.show();
            window.parent.callParAutoResize("main", $("body").height());
        }
    });

    // 菜单管理发送类型切换
    $(document).on("click",".js-send-type li",function () {
        var curObj = $(".js-parent-menu").data("curObj");
        var curObjChild = $(".menu-child-item").data("curObj");
        btnGroupSwitch($(this), "active");
        var _index = $(this).index();
        $(".menu-content-item").hide();
        $(".menu-content-item").eq(_index).show();
        if(curObj != "undefined"){
            $(curObj).children("input").eq(3).val(_index+1);
        }
        if(curObjChild != "undefined"){
            $(curObjChild).children("input").eq(3).val(_index+1);
        }
    });

    // 图文列表选择效果
    (function () {
        var item = $("#material-waterfall").find(".material-pop-box-item");
        var $mask = $('<div class="material-mask"> ' +
            '<i class="fa fa-check fa-3x icon-card-selected"></i> ' +
            '</div>');
        item.hover(function () {
            if ($(this).hasClass("active")) {
                return false;
            } else {
                $(this).append($mask);
            }
        }, function () {
            if ($(this).hasClass("active")) {
                return false;
            } else {
                $(this).find(".material-mask").remove();
            }
        });
        item.click(function () {
            if ($(this).hasClass("active")) {
                return false;
            } else {
                $(this).append($mask.clone()).addClass("active")
                    .siblings().removeClass("active")
                    .find(".material-mask").remove();
            }
        })
    })();

    // 渠道管理统计表格版本
    (function () {
        var version = $(".js-table-version");
        version.attr("rowspan", version.parent().siblings().length + 1);
        version.css({
            "vertical-align":"middle",
            "border-right":"1px solid #ddd"
        })
    })();

    // 渠道管理的菜单管理
    (function () {
        var $menuManage = $("#menu-manage");
        var $addPrentMenu = $menuManage.find(".js-add-parent-menu");
        var $sortBtn = $menuManage.find(".js-menu-sort-btn");
        var $parentCon = $menuManage.find(".menu-parent-content");
        var $commonCon = $menuManage.find(".menu-common-content");
        var $emptyTip = $menuManage.find(".menu-empty-tip");
        var $nameError = $menuManage.find(".js-menu-name-error");
        var $titleInput = $menuManage.find(".js-title-input");
        var $urlInput = $menuManage.find(".js-url-input");  //右侧url点击对象
        var $textareaInput = $menuManage.find(".js-textarea-input");  //右侧url点击对象
        var $menuName = $menuManage.find(".js-menu-name");
        var $jumpLink = $menuManage.find(".js-menu-con-switch").first();
        /**
         * 子菜单点击执行操作
         * @param el
         */
        function subMenuClick(el) {
            $(".menu-child-item").data("curObj",el); //保存当前点击对象；
            $(".menu-child-item").removeClass("active");
            el.addClass("active");
            $(".js-parent-menu").removeClass("active");
            $parentCon.show();
            $commonCon.show();
            var name = el.find(".menu-child-name").html();
            $menuName.html(name);
            $titleInput.val(name);
            $nameError.hide();
            $emptyTip.hide();
            //$jumpLink.click();
            $(".js-menu-name-tip").html("字数不超过8个汉字或16个字母");
            menuValFill(el);  //给右边的文本赋值
        }

        /**
         * 添加父级的菜单
         * @returns {boolean}
         */
        function addParentMenu(_this) {
            //获取所有父菜单name隐藏域的序号的最大值 并+1
            var seq = parseInt(getMaxSeq(_this))+1;
            var $el = $('<li class="menu-parent-item js-parent-menu">' +
                '<span><i class="fa fa-bars hidden"></i><span class="menu-parent-name">菜单名称</span></span> ' +
                '<div class="menu-child-box" style="display: none;"> ' +
                '<ul class="menu-child sortable js-child-menu"> ' +
                '<li class="add-menu-box unsortable js-add-menu"><i class="fa fa-plus"></i></li> ' +
                '</ul> ' +
                '<i class="arrow arrow_out"></i> ' +
                '<i class="arrow arrow_in"></i> ' +
                '</div>' +
                '<input type="hidden" value="" name="menu[' + seq + '][name]">' +
                '<input type="hidden" value="" name="menu[' + seq + '][id]">' +
                '<input type="hidden" value="" name="menu[' + seq + '][content]">' +
                '<input type="hidden" value="" name="menu[' + seq + '][tag]"> ' +
                '<input type="hidden" value="" name="menu[' + seq + '][text]">' +
                '<input type="hidden" value="" name="menu[' + seq + '][url]">' +
                '<input type="hidden" value="" name="menu[' + seq + '][imagePath]">' +
                '<input type="hidden" value="" name="menu[' + seq + '][code]">' +
                '</li>');

            $el.insertBefore($addPrentMenu);
            var a = $("#menu-manage .menu-parent-item").length;
            if (a === 3) {
                $addPrentMenu.hide();
                return false;
            }
            $addPrentMenu.css('width', (3 - a) * 33.333 + '%');
        }



        /**
         * 显示隐藏三角箭头
         */
        function hideArrow() {
            $(".js-child-menu").each(function () {
                if (!$(this).find(".menu-child-item").length) {
                    $(this).siblings(".arrow").hide();
                }
            })
        }

        // 父级菜单切换
        var sortable = true;
        $(document).on("click", ".js-parent-menu", function () {
            $(".js-parent-menu").data("curObj",$(this)); //保存当前点击对象；
            //$jumpLink.click();
            var name = $(this).find(".menu-parent-name").html();
            $menuName.html(name);
            $titleInput.val(name);
            $nameError.hide();
            $(".menu-child-item").removeClass("active");
            $(this).addClass("active").siblings().removeClass("active");
            $(".js-parent-menu").find(".menu-child-box").hide();
            $(this).find(".menu-child-box").show();
            var childLength = $(this).find(".menu-child-box .menu-child-item").length;
            if (sortable) {
                if (childLength > 0) {
                    $parentCon.show();
                    $commonCon.hide();
                } else {
                    $parentCon.show();
                    $commonCon.show();
                    $emptyTip.hide();
                }
            }
            // 菜单标题切换
//                $(".js-menu-name").html($(this).find(".menu-name").html());
            $(".js-menu-name-tip").html("字数不超过4个汉字或8个字母");
            menuValFill($(this));//给右边的文本赋值
        });

        // 点击排序按钮
        $sortBtn.click(function () {
            var $parentMenu = $menuManage.find(".js-parent-menu");
            var $childMenu = $menuManage.find(".menu-child-item");
            if (sortable) {
                hideArrow();
                $(this).html("完成");
                $(".sortable").sortable({
                    disabled: false,
                    cursorAt: {left: 5, top: 5},
                    items: "li:not(.unsortable)" //进行排序
                });
                $parentMenu.removeClass("active");
                $childMenu.removeClass("active");
                $parentMenu.find(".fa-bars").removeClass("hidden");
                $parentMenu.find(".add-menu-box").hide();
                $parentCon.hide();
                $commonCon.hide();
                $emptyTip.show();
                $emptyTip.find("p").html("请通过拖拽左边的菜单进行排序");
                sortable = false;
                // console.log("111");
                // $(document).on("click", ".js-child-menu .menu-child-item", function (){
                //     return false;
                // });
            } else {
                $("input[name=sortId]").val(getString());
                sortable = true;
                $(this).html("菜单排序");
                $(".sortable").sortable({
                    disabled: true
                });
                $emptyTip.find("p").html("请点击左侧菜单进行编辑操作");
                $parentMenu.find(".fa-bars").addClass("hidden");
                $parentMenu.find(".add-menu-box").show();
                $parentMenu.find(".arrow").show();
            }
        });

        //增加子菜单
        $(document).on("click", ".js-add-menu", function (e) {
            //获取父元素的name隐藏域的序号
            var seqPar = $(this).parents(".menu-child-box").next().attr("name").slice(5,6);
            //获取对应父菜单下的子菜单隐藏域name的序号的最大值
            var seqChild = parseInt(getMaxSeq($(this)))+1;
            e.stopPropagation();
            $(".js-parent-menu").removeClass("active");
            if ($(this).siblings().length >= 5) {
                layer.msg('子菜单已达到上限，不能再添加了!', {
                    offset: 100,
                    time: 2000
                });
                return false;
            }
            var $menuChild = $('<li class="menu-child-item">' +
                '<span><i class="fa fa-bars hidden"></i>' +
                '<span class="menu-child-name">菜单名称</span>' +
                '</span>' +
                '<input type="hidden" value="" name="menu['+ seqPar +'][child]['+ seqChild +'][name]">' +
                '<input type="hidden" value="" name="menu['+ seqPar +'][child]['+ seqChild +'][id]">' +
                '<input type="hidden" value="" name="menu['+ seqPar +'][child]['+ seqChild +'][content]">' +
                '<input type="hidden" value="" name="menu['+ seqPar +'][child]['+ seqChild +'][tag]">' +
                '<input type="hidden" value="" name="menu['+ seqPar +'][child]['+ seqChild +'][text]">' +
                '<input type="hidden" value="" name="menu['+ seqPar +'][child]['+ seqChild +'][url]">' +
                '<input type="hidden" value="" name="menu['+ seqPar +'][child]['+ seqChild +'][imagePath]">' +
                '<input type="hidden" value="" name="menu['+ seqPar +'][child]['+ seqChild +'][code]">' +
                '</li>');
            $menuChild.insertBefore($(this));
            subMenuClick($menuChild);
        });

        //加入它的上个兄弟节点存在，则获取

        // 子菜单点击事件
        $(document).on("click", ".js-child-menu .menu-child-item", function (e) {
            if(sortable){
                e.stopPropagation();
                subMenuClick($(this));
            }
        });

        // 菜单输入事件
        $titleInput.keyup(function () {
            var menuParentActive = $(".menu-parent-item").filter(".active");
            var menuChildActive = $(".menu-child-item").filter(".active");
            var re1 = (/^[A-Za-z]{1,8}$|^[\u4E00-\u9FA5]{1,4}$/g);
            var re2 = (/^[A-Za-z]{1,16}$|^[\u4E00-\u9FA5]{1,8}$/g);
            var name = $(this).val().trim();
            $menuName.html(name);
            if (menuParentActive.length>0) {
                menuParentActive.find(".menu-parent-name").html(name);
                //给左边菜单name赋值
                menuParentActive.children("input").eq(0).val(name);
                checkMenuName(name,re1);
                // if (!re1.test(name)) {
                //     if (name == ""){
                //         $nameError.show();
                //         $nameError.html("菜单名称不能为空！");
                //         return false;
                //     }else{
                //         $nameError.show();
                //         $nameError.html("菜单名称格式错误！");
                //         return false;
                //     }
                // } else {
                //     $nameError.hide();
                // }
            }
            if (menuChildActive.length>0) {
                menuChildActive.find(".menu-child-name").html(name);
                menuChildActive.children("input").eq(0).val(name);
                checkMenuName(name,re2);
                // if (!re2.test(name)) {
                //     if (name == ""){
                //         $nameError.show();
                //         $nameError.html("菜单名称不能为空！");
                //         return false;
                //     }
                //     $nameError.show();
                //     return false;
                // } else {
                //     $nameError.hide();
                // }
            }
        });

        //文字输入事件
        $textareaInput.keyup(function () {
            var menuParentActive = $(".menu-parent-item").filter(".active");
            var menuChildActive = $(".menu-child-item").filter(".active");
            var textarea = $(this).val().trim();
            if (menuParentActive.length) {
                //给左边菜单name赋值
                menuParentActive.children("input").eq(4).val(textarea);
            }
            if (menuChildActive.length) {
                menuChildActive.children("input").eq(4).val(textarea);
            }
        });

        //url输入事件
        $urlInput.keyup(function () {
            var menuParentActive = $(".menu-parent-item").filter(".active");
            var menuChildActive = $(".menu-child-item").filter(".active");
            var url = $(this).val().trim();
            if (menuParentActive.length) {
                //给左边菜单name赋值
                menuParentActive.children("input").eq(5).val(url);
            }
            if (menuChildActive.length) {
                menuChildActive.children("input").eq(5).val(url);
            }
        });

        // 父级菜单增加
        $(".js-add-parent-menu").click(function () {
            addParentMenu($(this));
            delParentMenu();
        });

        // 对话功能开启与关闭
        var $menuParent = $menuManage.find(".menu-parent");

        $("#alipay-talk-fn").change(function () {
            if($(this).is(":checked")){
                $menuParent.addClass("hasTalk");
            }else{
                $menuParent.removeClass("hasTalk");
            }
        })
    })();

    // 菜单管理 -> 菜单内容切换
    boxSwitch($(".js-menu-con-switch"),$(".js-menu-con"));
});

/**
 * 检验菜单管理-> 菜单名称
 * @param name
 * @param re
 * @returns {boolean}
 */
function checkMenuName(name, re) {
    var $nameError = $("#menu-manage").find(".js-menu-name-error");
    if (!re.test(name)) {
        if (name == "") {
            $nameError.show();
            $nameError.html("菜单名称不能为空！");
            return false;
        } else {
            $nameError.show();
            $nameError.html("菜单名称格式错误！");
            return false;
        }
    } else {
        $nameError.hide();
        return true;
    }
}
/**
 * 用于根据radio内容变化
 * @param el1
 * @param el2
 */
function boxSwitch(el1,el2) {
    el1.click(function () {
        var curObj = $(".js-parent-menu").data("curObj");
        var curObjChild = $(".menu-child-item").data("curObj");
        var n = $(this).attr("n");
        el2.hide();
        el2.eq(n).show();
        $("#menuCon-error").hide();
        if(curObj != "undefined"){
            $(curObj).children("input").eq(2).val(parseInt(n)+1);
        }
        if(curObjChild != "undefined"){
            $(curObjChild).children("input").eq(2).val(parseInt(n)+1);
        }
    })
}
/**
 * 设置select2框的宽度
 */
function select2Auto() {
    var s = 0;
    $(".select2-selection__choice").each(function () {
        s = s + $(this).width();
    });
    $(".select2-container").width(160 + s + 'px');
}
//执行几秒后某个对象自动消息
//o:要消失的对象
//text:显示内容
//t:延迟的时间 如：1000（毫秒）
function delayHide(o, text, t) {
    var obj;
    if ($("." + o)) {
        obj = $("." + o);
    } else {
        obj = $("#" + o);
    }
    obj.html(text).show();
    setTimeout(function () {
        obj.fadeOut()
    }, t);
}

/**
 * 获取样式
 * @param el
 * @param styleProp
 * @returns {*|string}
 */
function getStyle(el, styleProp) {
    if (el.currentStyle)
        var y = el.currentStyle[styleProp];
    else if (window.getComputedStyle)
        var y = getComputedStyle(el, null).getPropertyValue(styleProp);
    // var y = getComputedStyle(el, null)[styleProp]; 同理
    return y;
}

/**
 * 组件功能：实现年月的选择
 * 组件使用：在input上增加class「monthpicker」即可调用
 * date: 2016/6/27
 */
function MonthPicker($target) {
    this.init($target);
    this.render();
    this.setData();
    this.bind();
}

MonthPicker.prototype = {
    init: function ($target) {
        this.$target = $target;
        this.defaultMonth = new Date().getMonth();
        this.defaultYear = new Date().getFullYear();
    },
    render: function () {
        var tpl = '<div class="ui-monthpicker">'
            + '<div class="ui-monthpicker-header">'
            + '<h5 class="ui-monthpicker-title">请选择年月</h5>'
            + '<i class="ui-monthpicker-close fa fa-times"></i>'
            + '</div>'
            + '<ul class="ui-monthpicker-content">'
            + '<li>'
            + '<i class="fa fa-arrow-up ui-monthpicker-arrow ui-monthpicker-year-up"></i>'
            + '<span class="ui-monthpicker-year">2016</span>'
            + '<i class="fa fa-arrow-down ui-monthpicker-arrow ui-monthpicker-year-down"></i>'
            + '</li>'
            + '<li>'
            + '<span>年</span>'
            + '</li>'
            + '<li>'
            + '<i class="fa fa-arrow-up ui-monthpicker-arrow ui-monthpicker-month-up"></i>'
            + '<span class="ui-monthpicker-month">5</span>'
            + '<i class="fa fa-arrow-down ui-monthpicker-arrow ui-monthpicker-month-down"></i>'
            + '</li>'
            + '<li>'
            + '<span>月</span>'
            + '</li>'
            + '</ul>'
            + '</div>';
        this.$monthpicker = $(tpl);
        this.$monthpicker.appendTo($("body"))
            .css({
                'left': this.$target.offset().left,
                'top': this.$target.offset().top + this.$target.outerHeight() + 5
            });
    },
    setData: function () {
        this.$monthpicker.find(".ui-monthpicker-year").html(this.defaultYear);
        this.$monthpicker.find(".ui-monthpicker-month").html(this.defaultMonth);
        this.$target.val(this.defaultYear + '年' + this.defaultMonth + '月');
    },
    bind: function () {
        var self = this;
        this.$monthpicker.find(".ui-monthpicker-year-up").on("click", function () {
            if (self.defaultYear < new Date().getFullYear()) {
                self.defaultYear++;
                self.setData();
            } else {
                return false;
            }
        });
        this.$monthpicker.find(".ui-monthpicker-year-down").on("click", function () {
            self.defaultYear--;
            self.setData();
        });
        this.$monthpicker.find(".ui-monthpicker-month-down").on("click", function () {
            self.defaultMonth--;
            if (self.defaultMonth === 0) {
                self.defaultMonth = 12;
            }
            self.setData();
        });
        this.$monthpicker.find(".ui-monthpicker-month-up").on("click", function () {
            self.defaultMonth++;
            if (self.defaultMonth === 13) {
                self.defaultMonth = 1;
            }
            self.setData();
        });
        this.$monthpicker.on('click', function (e) {
            e.stopPropagation();
        });
        this.$monthpicker.find(".ui-monthpicker-close").click(function () {
            self.$monthpicker.hide();
        });
        $(window).on('click', function (e) {
            self.$monthpicker.hide();
        });
        this.$target.on("click", function (e) {
            e.stopPropagation();
            self.$monthpicker.show();
        });
        this.$target.on("keydown", function (e) {
            e.preventDefault();
        })
    }
};

(function () {
    /**
     * 瀑布流组件
     * @param id
     * @param item
     * @constructor
     */
    this.Waterfall = function (id, item) {
        this.parent = document.getElementById(id);
        this.item = item;
        this.wf();
    };

    Waterfall.prototype.wf = function () {
        var oPin = getElementsByClass(this.parent, this.item);
        var oCols = getCols(this.parent, oPin[0]);
        // this.parent.style.cssText = 'width:' + oPin[0].offsetWidth * oCols + 'px;margin:0 auto;'; //设置父级水平居中
        var pinHArr = [];
        for (var i = 0; i < oPin.length; i++) {
            var pinH = oPin[i].offsetHeight + parseInt(getStyle(oPin[0], "margin-bottom"));
            if (i < oCols) {
                pinHArr[i] = pinH;
            } else {
                var min = Math.min.apply(null, pinHArr);//数组pinHArr中的最小值
                var minIndex = getMinIndex(pinHArr, min);
                oPin[i].style.position = 'absolute';//设置绝对位移
                oPin[i].style.top = min + 'px';
                oPin[i].style.left = oPin[minIndex].offsetLeft - parseInt(getStyle(oPin[0], "margin-left")) + 'px';
                pinHArr[minIndex] += oPin[i].offsetHeight + parseInt(getStyle(oPin[0], "margin-bottom"));
            }
        }
    };

    /**
     * 获取相同类的节点
     * @param parent
     * @param cls
     * @returns {Array}
     */
    function getElementsByClass(parent, cls) {
        var clsArr = [];
        var eleAll = parent.getElementsByTagName("*");
        for (var i = 0; i < eleAll.length; i++) {
            if (eleAll[i].className == cls) {
                clsArr.push(eleAll[i]);
            }
        }
        return clsArr;
    }

    /**
     * 获取图片的列数
     * @param el
     * @returns {number}
     */
    function getCols(parent, el) {
        var winWidth = parent.offsetWidth;
        var elWidth = el.offsetWidth;
        return Math.floor(winWidth / elWidth);
    }

    /**
     * 获取数组中最小值的索引值
     * @param arr
     * @param min
     * @returns {string}
     */
    function getMinIndex(arr, min) {
        for (var i in arr) {
            if (arr[i] == min) {
                return i;
            }
        }
    }

    /**
     * 获取浏览器窗口的高度
     * @returns {Number|number}
     */
    function getWinWidth() {
        return window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
    }

})();


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
function getValue(obj, className) {
    //获得所有可见对象
    //循环对象，获取值，把值赋在指定的格式里
    var arr = new Array(); //存放对象值
    var v = "";

    var obj = $("." + obj);

    obj.each(function (index, value) {
        //只对div显示的对象进行操作
        if ($(this).is(":visible")) {
            //对不同一类的索引进行相应的操作
            switch ($(this).attr('n')) {
                case 'condition_sex':
                case 'condition_source':
                case 'condition_grade':
                case 'condition_value':
                    $(this).find(className).each(function (indexC, valueC) {
                        //判断form表单的对象
                        if ($(valueC).is('input') || $(valueC).is('select') || $(valueC).is('textarea')) {
                            if ($(valueC).is('input[type=checkbox]') || $(valueC).is('input[type=radio]')) {
                                if ($(valueC).is(':checked')) {
                                    v += $(valueC).val() + ",";
                                }
                            } else {
                                if ($(valueC).val()) {
                                    v += $(valueC).val() + ",";
                                } else {
//                                    alert("请填写值");
//                                    return false;
                                }
                            }
                        }
                    });
                    /*if(!v)
                     {
                     v = "";
                     break;
                     }*/
                    arr.push({
                        "seq": index,
                        "name": $(value).attr("n"),
                        "value": v.substring(0, v.length - 1)
                    });
                    v = "";
                    break;
                case 'condition_age':
                    $(this).find(className).each(function (indexC, valueC) {
                        if ($(valueC).is('input') || $(valueC).is('select') || $(valueC).is('textarea')) {
                            if ($(valueC).is('input[type=checkbox]') || $(valueC).is('input[type=radio]')) {
                                if ($(valueC).is(':checked')) {
                                    v += $(valueC).val() + ",";
                                }
                            } else {
                                if ($(valueC).val()) {
                                    v += $(valueC).val() + ",";
                                } else {
//                                    alert("请填写值");
//                                    return false;
                                }
                            }
                        }
                    });
                    var valuemin = v.split(",")[0];
                    var valuemax = v.split(",")[1];
                    /* if(!valuemin || !valuemax)
                     {
                     v = "";
                     break;
                     }*/

                    arr.push({
                        "seq": index,
                        "name": $(value).attr("n"),
                        "value": {
                            "valuemin": v.split(",")[0],
                            "valuemax": v.split(",")[1]
                        }
                    });
                    v = "";
                    break;
                case 'condition_birthday':
                    $(this).find(className).each(function (indexC, valueC) {
                        //判断form表单的对象
                        if ($(valueC).is('input') || $(valueC).is('select') || $(valueC).is('textarea')) {
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
                        "seq": index,
                        "name": $(value).attr("n"),
                        "value": v,
                    });
                    v = "";
                    break;
                case 'condition_consum_amount':
                case 'condition_consum_unitprice':
                case 'condition_consum_highestprice':
                case 'condition_consum_count':
                case 'condition_consum_days':
                    $(this).find(className).each(function (indexC, valueC) {
                        if ($(valueC).is('input') || $(valueC).is('select') || $(valueC).is('textarea')) {
                            if ($(valueC).is('input[type=checkbox]') || $(valueC).is('input[type=radio]')) {
                                if ($(valueC).is(':checked')) {
                                    v += '1' + ",";
                                } else {
                                    v += '' + "" + ",";
                                }
                            } else {
                                if ($(valueC).val()) {
                                    v += $(valueC).val() + ",";
                                } else {
//                                    alert("请填写值");
                                    if ($(valueC).hasClass('daterange_picker') || $(valueC).hasClass('store_ids'))
                                        v += $(valueC).val() + ",";
                                    else {

                                    }
//                                		return false;
//                                    return false;
                                }
                            }
                        }
                    });
                    var valuemin = v.split(",")[v.split(",").length - 3];
                    var valuemax = v.split(",")[v.split(",").length - 2];
                    /*if(!valuemin || !valuemax)
                     {
                     v = "";
                     break;
                     }*/

                    arr.push({
                        "seq": index,
                        "name": $(value).attr("n"),
                        "date": v.split(",")[0],
                        "store": v.split(",").slice(1, v.split(",").length - 3),
                        "value": {
                            "valuemin": v.split(",")[v.split(",").length - 3],
                            "valuemax": v.split(",")[v.split(",").length - 2]
                        }
                    });

                    v = "";
                    break;
                case 'condition_not_consum_days':
                    $(this).find(className).each(function (indexC, valueC) {
                        if ($(valueC).is('input') || $(valueC).is('select') || $(valueC).is('textarea')) {
                            if ($(valueC).is('input[type=checkbox]') || $(valueC).is('input[type=radio]')) {
                                if ($(valueC).is(':checked')) {
                                    v += '1' + ",";
                                } else {
                                    v += '' + "" + ",";
                                }
                            } else {
                                if ($(valueC).val()) {
                                    v += $(valueC).val() + ",";
                                } else {
//                                    alert("请填写值");
                                    if ($(valueC).hasClass('daterange_picker') || $(valueC).hasClass('store_ids'))
                                        v += $(valueC).val() + ",";
                                    else {
                                        //return false;
                                    }
                                }
                            }
                        }
                    });
                    var valuemin = v.split(",")[v.split(",").length - 3];
                    var valuemax = v.split(",")[v.split(",").length - 2];
                    /*if(!valuemin || !valuemax)
                     {
                     v = "";
                     break;
                     }*/
                    arr.push({
                        "seq": index,
                        "name": $(value).attr("n"),
                        "store": v.split(",").slice(0, v.split(",").length - 3),
                        "value": {
                            "valuemin": v.split(",")[v.split(",").length - 3],
                            "valuemax": v.split(",")[v.split(",").length - 2]
                        }
                    });

                    v = "";
                    break;
            }
        }
    });
    return arr;
}