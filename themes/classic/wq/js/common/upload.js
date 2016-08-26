//优惠券上传图片
function addCouponLogo_upload(id, path, js, type, folder, list) {

    $(function () {
        $('#' + id).uploadify({
            onInit: function () {
                $(".uploadify-queue").hide();
            },//载入时触发，将flash设置到最小
            uploader: path,// 服务器处理地址
            swf: js + 'lib/uploadify/uploadify.swf',
            buttonText: "",//按钮文字
            height: 34,  //按钮高度
            width: 82, //按钮宽度
            multi: true,
            fileSizeLimit: '3mb',
            fileTypeExts: type,//允许的文件类型
            fileTypeDesc: "请选择图片文件", //文件说明
            formData: {'folder': folder}, //提交给服务器端的参数
            onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
                eval("var jsondata = " + data + ";");
                var key = jsondata['key'];
                var name = file.name;
                var fileName = jsondata['fileName'];
                var imgsrc = list + fileName;
                alert('上传成功');
                //$('.hidden').prepend('<div class="item"><div class="img"><img src="' + imgsrc + '"></div><div class="text"><a href="javascript:;" class="del"></a><input type="hidden" name="imgname[]"  value="' + name + '"><input type="hidden" name="imgsrc[]"  value="' + fileName + '"></div></div>');

                $("input[name='Coupons[logo]']").val(fileName);
                //alert(list + fileName);
                $("#merchant_logo").attr('src',list + fileName);
                $("#left_logo_img").attr('src',list + fileName);
                //$("#upload_img1").html(fileName);
                //$("#img1").attr('src', list + fileName);

            }
        });

    });
}
function addCouponImg_upload(id, path, js, type, folder, list) {
    $(function () {
        $('#' + id).uploadify({
            onInit: function () {
                $(".uploadify-queue").hide();
            },//载入时触发，将flash设置到最小
            uploader: path,// 服务器处理地址
            swf: js + 'lib/uploadify/uploadify.swf',
            buttonText: "",//按钮文字
            height: 34,  //按钮高度
            width: 82, //按钮宽度
            multi: true,
            fileSizeLimit: '1mb',
            fileTypeExts: type,//允许的文件类型
            fileTypeDesc: "请选择图片文件", //文件说明
            formData: {'folder': folder}, //提交给服务器端的参数
            onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
                eval("var jsondata = " + data + ";");
                var key = jsondata['key'];
                var name = file.name;
                var fileName = jsondata['fileName'];
                var imgsrc = list + fileName;
                alert('上传成功');
                //$('.hidden').prepend('<div class="item"><div class="img"><img src="' + imgsrc + '"></div><div class="text"><a href="javascript:;" class="del"></a><input type="hidden" name="imgname[]"  value="' + name + '"><input type="hidden" name="imgsrc[]"  value="' + fileName + '"></div></div>');

                $("input[name='Coupons[cover_img]']").val(fileName);
                //alert(list + fileName);
                $("#left_cover_img").attr('src',list + fileName);
                $("#right_cover_img").attr('src',list + fileName);
                //$("#upload_img1").html(fileName);
                //$("#img1").attr('src', list + fileName);

            }
        });

    });
}
function addCouponImg_upload2(id, path, js, type, folder, list) {
    $(function () {
        $('#' + id).uploadify({
            onInit: function () {
                $(".uploadify-queue").hide();
            },//载入时触发，将flash设置到最小
            uploader: path,// 服务器处理地址
            swf: js + 'lib/uploadify/uploadify.swf',
            buttonText: "",//按钮文字
            height: 34,  //按钮高度
            width: 82, //按钮宽度
            multi: true,
            fileSizeLimit: '1mb',
            fileTypeExts: type,//允许的文件类型
            fileTypeDesc: "请选择图片文件", //文件说明
            formData: {'folder': folder}, //提交给服务器端的参数
            onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
                eval("var jsondata = " + data + ";");
                var key = jsondata['key'];
                var name = file.name;
                var fileName = jsondata['fileName'];
                var imgsrc = list + fileName;
                alert('上传成功');
                //$('.hidden').prepend('<div class="item"><div class="img"><img src="' + imgsrc + '"></div><div class="text"><a href="javascript:;" class="del"></a><input type="hidden" name="imgname[]"  value="' + name + '"><input type="hidden" name="imgsrc[]"  value="' + fileName + '"></div></div>');
                $("input[name='img_text[0]']").val(fileName);
                $("#right_img_text0").attr('src',list + fileName);
            }
        });

    });
}

function addCouponImgList_upload(id, path, js, type, folder, list) {
    $(function () {
        $('#cp-upload' + id).uploadify({
            onInit: function () {
                $(".uploadify-queue").hide();
            },//载入时触发，将flash设置到最小
            uploader: path,// 服务器处理地址
            swf: js + 'lib/uploadify/uploadify.swf',
            buttonText: "",//按钮文字
            height: 34,  //按钮高度
            width: 82, //按钮宽度
            multi: true,
            fileSizeLimit: '1mb',
            fileTypeExts: type,//允许的文件类型
            fileTypeDesc: "请选择图片文件", //文件说明
            formData: {'folder': folder}, //提交给服务器端的参数
            onUploadSuccess: function (file, data, response) {//一个文件上传成功后的响应事件处理
                eval("var jsondata = " + data + ";");
                var key = jsondata['key'];
                var name = file.name;
                var fileName = jsondata['fileName'];
                var imgsrc = list + fileName;
                alert('上传成功');
                $('#cp-upload' + id).parent().find('input').val(fileName);
                $('#right_img_text' + id).attr('src',list + fileName);
            }
        });

    });
}