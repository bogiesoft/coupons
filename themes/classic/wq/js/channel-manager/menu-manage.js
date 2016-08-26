//菜单编辑
function menuValFill(obj) {
    //菜单名称
    $('.menu-manage-r input').filter('[name="name[]"]').val(obj.children("input").eq(0).val());

    //左边id赋给右边
    $('.menu-manage-r input').filter('[name="menuId"]').val(obj.children("input").eq(1).val());

    //菜单内容选项
    $('.menu-manage-r textarea').filter('[name="textarea[]"]').val(obj.children("input").eq(4).val());

    //url
    $('.menu-manage-r input').filter('[name="url[]"]').val(obj.children("input").eq(5).val());
}

//得到所有排序的id
function getString() {
    // var arr = new Array();
    var parentId = childId = "";
    var menuParent = $(".js-parent-menu"); //得到父元素
    //循环父元素，取得id,
    menuParent.each(function () {
        parentId += $(this).children("input").eq(1).val() + ":";
        $(this).find(".menu-child-item").each(function (n) {
            parentId += $(this).children("input").eq(1).val() + ",";
        });
        parentId = parentId.substring(0, parentId.length - 1);
        parentId += ";"
    });
    return parentId;
}
//获得隐藏域的input=name里的序号最大值
function getMaxSeq(obj) {
    var siblings = $(obj).siblings();
    var arr = [];
    var arrMax;

    //把值放入数组里
    if (obj.hasClass("js-add-parent-menu") == true) {
        siblings.each(function () {
            arr.push($(this).children("input").eq(0).attr("name").slice(5, 6));
        })
    } else {
        siblings.each(function () {
            arr.push($(this).children("input").eq(0).attr("name").slice(-8, -7));
        })
    }

    //获取最大值
    arrMax = arr[0];
    for (var i = 1; i < arr.length; i++) {
        if (arrMax < arr[i]) {
            arrMax = arr[i];
        }
    }
    arr = []; //清空数组
    //如果值不存在则赋为0
    if (!arrMax) {
        arrMax = -1;
    }
    return arrMax;
}

/**
 * 删除父级菜单
 */
function delParentMenu() {
    var a = $("#menu-manage .menu-parent-item").length;
    var $sortBtn = $(".js-menu-sort-btn");
    $(".js-add-parent-menu").css('width', (3 - a) * 33.333 + '%');
    a < 2 ? $sortBtn.prop("disabled", true).addClass("disabled")
        : $sortBtn.prop("disabled", false).removeClass("disabled");
}