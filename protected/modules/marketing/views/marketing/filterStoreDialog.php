<div class="pop_con popStoreSel" id="pop">
    <div class="title">
        <span class="name">选择门店</span>
    </div>
    <div class="pop_content">
        <?php echo CHtml::beginForm('', 'get'); ?>
        <div class="sh-search clearfix">
            <?php echo CHtml::textField('key_word', isset($_GET['key_word']) ? $_GET['key_word'] : '',
                array('class' => 'txt', 'placeholder' => '请输入门店名称', 'style' => 'width:300px')); ?>
            <input type="submit" class="search" value="">
        </div>
        <?php echo CHtml::endForm(); ?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableH">
            <tr class="orderHead">
                <td width="15"><input type="checkbox" id="all_check"></td>
                <td align="left" width="30%">门店名称</td>
                <td align="right">地址</td>
            </tr>

            <?php if (!empty($store_lists)) {
                $count = 0; ?>

                <?php foreach ($store_lists as $k => $v) {
                    $count++; ?>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (in_array($v->id, $store_id_arr)) {
                                echo 'checked';
                            } ?> name="store_id[]" value="<?php echo $v->id ?>">
                        </td>
                        <td align="left">
                            <a href="#"><?php echo empty($v->branch_name) ? $v->name : $v->name."-".$v->branch_name; ?></a>
                        </td>
                        <td align="right"><?php echo $v->address ?></td>
                    </tr>
                <?php } ?>
                <?php if ($count < 5) { ?>
                    <?php for ($i = $count; $i < 5; $i++) { ?>
                        <tr>
                            <td style="font-size: 13px">&nbsp;</td>
                            <td align="left"><a href="#"></a></td>
                            <td align="right"></td>
                        </tr>
                    <?php } ?>

                <?php } ?>
            <?php } ?>

            <?php if (empty($store_lists)) { ?>
                <!--当没有门店的时候显示这一条,没有门店的时候，分页隐藏-->
                <tr>
                    <td colspan="3" align="center" height="100">
                        <div class="">没有门店信息，<a href="#" class="blue">请前往设置</a></div>
                    </td>
                </tr>

            <?php } ?>

        </table>
        <div class="page2">
            <?php $this->widget('CLinkPager', array(
                'pages' => $pages,
                'header' => '共&nbsp;<span class="yellow">' . $pages->getItemCount() . '</span>&nbsp;条&nbsp;',
                'prevPageLabel' => '上一页',
                'nextPageLabel' => '下一页',
                'maxButtonCount' => 8
            )); ?>
        </div>

        <div class="btnWrap">

            <div class="total">已选<span
                    id="change"><?php echo count($store_id_arr) ?></span>家，共<?php echo $pages->getItemCount(); ?>家
            </div>

            <input type="button" value="确认" class="btn_com_blue" onclick="send()">
            <input type="button" value="取消" class="btn_com_gray" onclick="closeWin()">
        </div>
    </div>

</div>


<script type="text/javascript">

    $(function () {
        var i = 0;
        $.ajax({
            url: '<?php echo Yii::app()->createUrl('marketing/marketing/getStoreList'); ?>',
            type: 'post',
            async: false, //给全局变量赋值，需要改为同步操作   即加上async : false
            success: function (data) {
                var res = jQuery.parseJSON(data);
                $.each(res, function (inx, item) {
                    i++;
                });
            }
        });

        var store_str = window.parent.$("#<?php echo $_GET['origin']?>").val();
        var store_arr = new Array();
        store_arr = store_str.split(",");
        store_arr.pop();
        store_arr = unique(store_arr); //去掉重复的元素
        for (var j = 0; j < store_arr.length; j++) {
            //由于关闭弹出框     再打开    之前勾选的会变成不勾选(不勾选的变成勾选)    所以要再次赋值
            $("input[name='store_id[]']:checkbox").each(function () {
                if ($(this).attr("value") == store_arr[j]) {
                    if (!$(this).prop("checked")) {
                        $(this).prop("checked", true);
                    } else {
                        //$(this).prop("checked",false);
                    }
                }
            });
        }
        $('#change').html(store_arr.length);

        if (i == <?php echo count($store_id_arr) ?>) {
            $("#all_check").prop("checked", true);
        }
        if (i == store_arr.length) {
            $("#all_check").prop("checked", true);
            //全选了，把没有勾选的勾选上
            $("input[name='store_id[]']:checkbox").each(function () {
                if (!$(this).prop("checked")) {
                    $(this).prop("checked", true);
                }
            });
        }
    });

    //全选 复选框 选中状态
    $("#all_check").click(function () {
        if ($("#all_check").prop("checked")) {
            $("[name='store_id[]']").prop("checked", true);//全选
            var i = 0;
            $.ajax({
                url: '<?php echo Yii::app()->createUrl('marketing/marketing/getStoreList'); ?>',
                type: 'post',
                async: false, //给全局变量赋值，需要改为同步操作   即加上async : false
                success: function (data) {
                    var res = jQuery.parseJSON(data);
                    $.each(res, function (inx, item) {
                        i++;
                    });
                }
            });
            $('#change').html(i); //已选门店个数
            $.ajax({
                url: '<?php echo Yii::app()->createUrl('marketing/marketing/getStoreList'); ?>',
                type: 'post',
                async: false, //给全局变量赋值，需要改为同步操作   即加上async : false
                success: function (data) {
                    //alert(data)
                    var res = jQuery.parseJSON(data);
                    $.each(res, function (inx, item) {
                        if (item.id) {
                            var old_store_id_str = window.parent.$("#<?php echo $_GET['origin']?>").val();
                            //alert(old_store_id_str);
                            var old_store_id_arr = old_store_id_str.split(',');
                            old_store_id_arr.pop();
                            if ($.inArray(item.id, old_store_id_arr) == -1) {
                                window.parent.$("#<?php echo $_GET['origin']?>").val(window.parent.$("#<?php echo $_GET['origin']?>").val() + item.id + ',');
                            }
                        }
                    });
                }
            });
        } else {
            $("[name='store_id[]']").prop("checked", false);
            $('#change').html(0);

            var store_str = window.parent.$("#<?php echo $_GET['origin']?>").val();
            var store_arr = new Array();
            store_arr = store_str.split(',');

            store_arr.pop();// 移除最後一元素(最后一个元素为空)
            store_arr = unique(store_arr); //去掉重复的元素
            store_str = '';
            for (var j = 0; j < store_arr.length; j++) {
                store_str = store_str + store_arr[j] + ',';
            }
            $.ajax({
                url: '<?php echo Yii::app()->createUrl('marketing/marketing/getStoreList'); ?>',
                type: 'post',
                async: false, //给全局变量赋值，需要改为同步操作   即加上async : false
                success: function (data) {
                    var res = jQuery.parseJSON(data);
                    $.each(res, function (inx, item) {

                        $.ajax({
                            url: '<?php echo Yii::app()->createUrl('marketing/marketing/delChooseStore'); ?>',
                            data: {store_str: store_str, delData: item.id},
                            type: 'post',
                            async: false, //给全局变量赋值，需要改为同步操作   即加上async : false
                            success: function (data) {
                                var res = jQuery.parseJSON(data);
                                window.parent.$("#<?php echo $_GET['origin']?>").val(res);
                                //数据删除后重新赋值
                                store_str = window.parent.$("<?php echo $_GET['origin']?>").val();
                            }
                        });
                    });
                }
            });
        }
    });
    //var CouponsNum = 0;

    //Jquery 1.7 $("input[name='order_sku[]']").live("click",function(){ }
    //Jquery 1.90以上就要用这种形式 (动态加载的数据)
    $(document).on('click', "input[name='store_id[]']", function () {
        //当某个多选框没选中 及 全选框选中，则把全选框设置成未选中状态
        if (!$(this).prop("checked") && $("#all_check").prop("checked")) {
            $("#all_check").prop("checked", false);
        }

        if (!$(this).prop("checked")) {
            $("#all_check").prop("checked", false);

            var store_str = window.parent.$("#<?php echo $_GET['origin']?>").val();
            var store_arr = new Array();
            store_arr = store_str.split(',');

            store_arr.pop();// 移除最後一元素(最后一个元素为空)
            store_arr = unique(store_arr); //去掉重复的元素
            store_str = '';
            for (var j = 0; j < store_arr.length; j++) {
                store_str = store_str + store_arr[j] + ',';
            }
            //取消勾选    则把取消的门店id从原数据删除
            $.ajax({
                url: '<?php echo Yii::app()->createUrl('marketing/marketing/delChooseStore'); ?>',
                data: {store_str: store_str, delData: $(this).val()},
                type: 'post',
                async: false,
                success: function (data) {
                    var res = jQuery.parseJSON(data);
                    window.parent.$("#<?php echo $_GET['origin']?>").val(res); //数据删除后重新赋值
                }
            });
        } else { //勾选复选框
            window.parent.$("#<?php echo $_GET['origin']?>").val(window.parent.$("#<?php echo $_GET['origin']?>").val() + $(this).val() + ','); //把勾选门店id值赋值给父窗口的隐藏域
        }

        var i = 0; //定义总门店总数
        $.ajax({
            url: '<?php echo Yii::app()->createUrl('marketing/marketing/getStoreList'); ?>',
            type: 'post',
            async: false, //给全局变量赋值，需要改为同步操作   即加上async : false
            success: function (data) {
                var res = jQuery.parseJSON(data);
                $.each(res, function (inx, item) {
                    i++; //总门店总数+1
                });
            }
        });

        var store_str = window.parent.$("#<?php echo $_GET['origin']?>").val();
        var store_arr = new Array();
        store_arr = store_str.split(",");
        store_arr.pop();

        //计算所有使用订单sku选中的个数
        //CouponsNum = $("input[name='store_id[]']:checked").length;
        $('#change').html(store_arr.length);

        //当选中门店的个数和所有门店的总数相等的时候，就把全选框选中
        /*if (CouponsNum == $("input[name='store_id[]']").length) {
         $("#all_check").prop("checked", true);
         }*/

        if (store_arr.length == i) { //已经选中个数等于总个数     全选勾上
            $("#all_check").prop("checked", true);
        }
        //CouponsNum = 0; //把值重新初始为0，不然下次计算就会一直叠加上去
    });

    function send() {
        var store_str = window.parent.$("#<?php echo $_GET['origin']?>").val();
        var store_arr = new Array();
        var i = 0;
        store_arr = store_str.split(",");
        store_arr.pop();// 移除最後一元素(最后一个元素为空)
        store_arr = unique(store_arr); //去掉重复的元素
        window.parent.window.addStoreInfo(store_arr, '<?php echo $_GET['origin']?>'); //调用父窗口的js方法
    }

    //去掉数组重复的元素
    function unique(arr) {
        var result = [], hash = {};
        for (var i = 0, elem; (elem = arr[i]) != null; i++) {
            if (!hash[elem]) {
                result.push(elem);
                hash[elem] = true;
            }
        }
        return result;
    }

    function closeWin() {
        window.parent.window.closeWinDialogStore();
    }
</script>
