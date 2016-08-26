<script type="text/javascript" src="<?php echo SYT_STATIC_JS?>artDialog/jquery.artDialog.js?skin=simple"></script>
<script type="text/javascript" src="<?php echo SYT_STATIC_JS?>artDialog/plugins/iframeTools.js"></script>
<body>


<div class="kkfm_r_inner">
    <!--搜索框 count-top包含的部分-->
    <div class="top">
        <div class="name">
            添加商品
        </div>
        <div class="cz"><a href="#" class="btn_comCancel">返回</a></div>
    </div>
    <div class="contant shop_indexEdit"><!--添加shop_edit-->
        <div class="status-nav status-nav1 clearfix">
            <ul>
                <li class=""><a href="#">1. 选择商品类目</a></li>
                <li class=""><a href="#">2. 编辑基本信息</a></li>
                <li class="cur"><a href="#">3. 编辑商品详情</a></li>
            </ul>
        </div>
        <div class="body">
            <div class="shopEdit_l">
                <div class="sLine"></div>
                <div class="t">
                    <h1>会员中心</h1>
                </div>
                <div class="m">
                    <div class="area "></div>
                    <div id="shoparea" class="area bgWhite p30" style="height:300px">
                        商品区
                        <div class="actions"></div>
                    </div>
                </div>
                <div class="b"></div>
            </div>

            <div id="showedit" class="shopEdit_r" style="margin-top:244px;display: none;">
                <?php echo CHtml::beginForm('SaveShopEdit')?>

                <div  id="addshopgroup" class="bcgItem clearfix">
                    <a href="javascript:;" onclick="openDialog()" class="addAD">添加一个商品分类</a>
                </div>

                <div class="arrow"></div>
            </div>
            <input style="display: none" id="group_num" name="group_num" value="">
            <div class="btn">
                <input type="submit" class="btn_com_blue" value="保 存">
            </div>
            <?php echo CHtml::endForm();?>
        </div>
    </div>
</div>
</body>

<script type="text/javascript">

    var num=0;//记录添加的条数
   
    $('#shoparea').click(function(){
       $('#showedit').show();
    });

    function openDialog(){
        art.dialog.open(
            '<?php echo Yii::app()->createUrl('mCenter/shopIndex/ShopDialogBody');?>',
            {
                title: '',
                lock: true,
                drag:true,
                width: '655px',
//                 height: '395px',
                id: 'dialog_index',
                background: '#fff'
            }
        );
    }

    $('#closepop').click(function(e){
        $('#pop').hide();
        e.stopPropagation();
        return false;
    });

    function editGroup(num)
    {
        art.dialog.open(
            '<?php echo Yii::app()->createUrl('mCenter/shopIndex/ShopDialogBody');?>'+'?num='+num,
            {
                title: '',
                lock: true,
                drag:true,
                width: '655px',
//                 height: '395px',
                id: 'dialog_index',
                background: '#fff'
            }
        );
    }

    function addGroup(id,name,edit_num)
    {

        if(edit_num=="") {
            num++;//行数加一
            if ($(document).find('.bgBlue').hasClass('bgBlue')) {
                //没有添加过分组
                var html = "<div id='group" + num + "' class='bcgItem clearfix'><div class='text'><dl class='inlineBlock'><dd class='inlineBlock'><div id='grouptext"+num+"' class='bgBlue'>";
                html = html + name + "<a href='#' class='close'></a></div><a href='javascript:;' onclick='editGroup(num)'>修改</a></dd></dl></div><input id='input_groupid"+num+"' style='display: none' name='groupid" + num + "'  value='"+id+"'></div>"
                $(document).find('#group' + (num - 1)).before(html);
            }
            else {
                //添加过了分组
                var html = "<div id='group" + num + "' class='bcgItem clearfix'><div class='text'><dl class='inlineBlock'><dd class='inlineBlock'><div  id='grouptext"+num+"' class='bgBlue'>";
                html = html + name + "<a href='#' class='close'></a></div><a href='javascript:;' onclick='editGroup(num)'>修改</a></dd></dl></div><input id='input_groupid"+num+"' style='display: none' name='groupid" + num + "'  value='"+id+"'></div>"
                $(document).find('#addshopgroup').before(html);
            }

            $('#group_num').val(num);
        }
        else
        {
//            var h=name+"<a href='javascropt:;' id='delrow' onclick='delrow("+edit_num+")' class='close'>";
            $('#grouptext'+edit_num).text(name);
            $('#input_groupid'+edit_num).val(id);
        }
        art.dialog.get("dialog_index").close(); //关闭弹出框
    }
</script>


