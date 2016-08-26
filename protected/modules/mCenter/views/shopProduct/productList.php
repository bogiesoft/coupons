<body>

<div class="kkfm_r_inner">
    <!--搜索框 count-top包含的部分-->
    <div class="top">
        <div class="name">
            商品管理
        </div>
    </div>
    <div class="shop_menu">
        <ul>
            <li class="cur"><a href="#">商品管理</a></li>
            <li><a href="<?php echo Yii::app()->createUrl('mCenter/ShopGroup/ShopGroupList'); ?>">商品分组</a></li>
        </ul>
    </div>

    <?php $form = $this->beginWidget('CActiveForm', array(
        'enableAjaxValidation' => true,
        'id' => 'addpro',
        'htmlOptions' => array('name' => 'createForm'),
    )); ?>
    <div class="contant shopList">
        <div class="cz">
            <a href="<?php echo Yii::app()->createUrl('mCenter/shopProduct/addProductOfCategory') ?>"
               class="btn_com_blue">添加商品</a>
        </div>
        <div class="sh-search clearfix">
            <?php echo CHtml::dropDownList('Product[shop_product_status]', isset($_POST['Product']['shop_product_status']) ? $_POST['Product']['shop_product_status'] : '', $pro_status_arr, array('id' => 'shop_product_status')) ?>
            <!--            --><?php //echo CHtml::dropDownList('Product[shop_group]', isset($_POST['Product']['shop_group']) ? $_POST['Product']['shop_group'] : '', $group,array('id'=>'shop_group')) ?>
            <?php echo CHtml::dropDownList('Product[shop_group]', isset($group_id) ? $group_id : '', $group, array('id' => 'shop_group')) ?>
            <!--隐藏的input-->
            <input type="text" style="display:none" id="arrow_type" name="arrow_type" value="<?php echo $arrow_type ?>">
            <input type="text" style="display: none;" id="arrow" name="arrow" value="<?php echo $arrow ?>">
            <!--隐藏的input-->
            <input type="text" placeholder="请输入关键字" class="txt" id="key_word" name="Product[key_word]"
                   value="<?php echo isset($_POST['Product']['key_word']) ? $_POST['Product']['key_word'] : '' ?>">
            <input type="button" id="search" class="search" value="">
        </div>

        <input type="text" style="display: none;" id="pro_status" name="pro_status" value="<?php echo $pro_status ?>">
        <input type="text" style="display: none;" id="group_id" name="group_id" value="<?php echo $group_id ?>">
        <input type="text" style="display: none;" id="entered_keyword" name="key_word" value="<?php echo $key_word ?>">
        <div class="recharge">
            <table width="100%" cellspacing="0" cellpadding="0">
                <tr class="order-title">
                    <td width="10"><input type="checkbox" name="ShopProduct_checkAll"></td>
                    <td>商品</td>
                    <td width="25%" class="blue"><span id="price">价格</span></td>
                    <td class="blue"><span id="stock">库存</span></td>
                    <td class="blue"><span id="volume">总销量</span></td>
                    <td class="blue"><span id="create_time">创建时间</span></td>
                    <td class="blue">序号</td>
                    <td>操作</td>
                </tr>
                <?php if (!empty($product)) { ?>
                    <?php foreach ($product as $k => $v) { ?>
                        <tr>
                            <td><input type="checkbox" value="<?php echo $v->id ?>" name="checkatt"></td>
                            <td>
                                <div class="htcon"><img
                                        src="<?php echo !empty($v->ts_product_id) ? $v->img : IMG_GJ_LIST . $v->img ?>">
                                </div>
                            </td>
                            <td>
                                <h3><?php echo $v->name ?></h3>
                                <p class="orange">￥<?php echo number_format($v->price, 2); ?></p>
                            </td>
                            <td><?php echo $v->num ?></td>
                            <td><?php echo $v->sold_num ?></td>
                            <td><?php echo date('Y.m.d H:i:s', strtotime($v->create_time)) ?></td>
                            <td>0</td>
                            <td>
                                <a href="<?php echo Yii::app()->createUrl('mCenter/shopProduct/editProductOfCategory', array('pro_id' => $v->id)) ?>"
                                   class="blue">编辑</a> -
                                <a href="<?php echo Yii::app()->createUrl('mCenter/shopProduct/delProduct', array('pro_id' => $v->id)) ?>"
                                   onclick="return confirm('确认删除吗');" class="blue">删除</a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </table>
            <div class="tfoot">
                <div class="l">
                    <ul>
                        <li>
                            <a href="javascript:;"
                               onclick="productlist_delMore('<?php echo(Yii::app()->createUrl('mCenter/ShopProduct/delMoreProduct')); ?>','<?php echo ERROR_NONE ?>')">批量删除</a>
                        </li>
                        <li><a href="javascript:;" id="changegroup">改分组</a></li>
                        <?php if (!empty($_GET['pro_status']) && $_GET['pro_status'] == SHOP_PRODUCT_STATUS_DOWN) { ?>
                            <li><a href="javascript:;" id="upcarriage">上架</a></li>
                        <?php } else { ?>
                            <li><a href="javascript:;" id="undercarriage">下架</a></li>
                        <?php } ?>
                    </ul>
                </div>
                <div id="page" style="text-align:right">
                    <?php $this->widget('CLinkPager', array(
                        'pages' => $pages,
                        'header' => '', //分页前显示的内容
                        'maxButtonCount' => 20, //显示分页数量
                        'firstPageCssClass' => '',
                        'lastPageCssClass' => '',
                        'firstPageLabel' => '首页',
                        'nextPageLabel' => '下一页',
                        'prevPageLabel' => '上一页',
                        'lastPageLabel' => '末页',
                    )); ?>
                </div>
            </div>
        </div>
    </div>

    <?php $this->endWidget(); ?>
</div>

<div class="popWrap change_group_pop" id="pop" style="display: none">
    <div class="pop_con">
        <div class="title">修改分组
            <a href="<?php echo Yii::app()->createUrl('mCenter/ShopGroup/ShopGroupList') ?>"
               class="manage_btn">管理</a>
        </div>
        <div class="pop_content popStored">
            <ul>
                <?php foreach ($grouplist as $key => $value) { ?>
                    <li><input type="checkbox" name="change_group" id="group1" value="<?php echo $key ?>">
                        <label for="group1"><?php echo $value ?></label>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class="btn">
            <input type="button" value="保存" class="btn_com_blue" id="save">
            <input type="button" onclick="hiddenpop()" value="取消" class="btn_com_gray">
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(
        function (e) {
            var choosed_groupid = $('#group_id').val();//下拉框选中
            var key_word = $('#entered_keyword').val();//搜索输入的值
            var pro_status = $('#pro_status').val();//商品状态下拉框

            if (pro_status != '') {
                $("select#shop_product_status").val(pro_status);
            }
            if (choosed_groupid != '') {
                $("select#shop_group option[value='1']").attr('selected', 'true');
            }
            if (key_word != '') {
                $('#key_word').val(key_word);
            }

            var arrow = $('#arrow').val();
            var arrow_type = $('#arrow_type').val();
            if (arrow_type == 'create_time') {
                $('#create_time').html("创建时间" + "<em class='" + arrow + "'></em>");
            } else if (arrow_type == 'price') {
                $('#price').html("价格" + "<em class='" + arrow + "'></em>");
            } else if (arrow_type == 'stock') {
                $('#stock').html("库存" + "<em class='" + arrow + "'></em>")
            } else if (arrow_type == 'volume') {
                $('#volume').html("总销量" + "<em class='" + arrow + "'></em>")
            }
        }
    );

    $(function () {
        shopproduct_undercarriage('<?php echo(Yii::app()->createUrl('mCenter/ShopProduct/UnderCarriage'));?>', '<?php echo ERROR_NONE?>');
        shopproduct_upcarriage('<?php echo(Yii::app()->createUrl('mCenter/ShopProduct/UpCarriage'));?>', '<?php echo ERROR_NONE?>');
        shopproduct_save('<?php echo Yii::app()->createUrl('mCenter/ShopProduct/ChangeGroup')?>');
        shopproduct_search('<?php echo Yii::app()->createUrl('mCenter/ShopProduct/ProductList')?>');
        shopproduct_volume('<?php echo Yii::app()->createUrl('mCenter/ShopProduct/ProductList')?>');
        shopproduct_stock('<?php echo Yii::app()->createUrl('mCenter/ShopProduct/ProductList')?>');
        shopproduct_create_time('<?php echo Yii::app()->createUrl('mCenter/ShopProduct/ProductList')?>');
        shopproduct_price('<?php echo Yii::app()->createUrl('mCenter/ShopProduct/ProductList')?>');
    });

    function hiddenpop() {
        $('#pop').toggle();
    }

    $('#changegroup').click(function (e) {
        var flag = false;
        var tmp = new Array();
        $(".recharge input:checkbox").each(function () {
            if ($(this).is(":checked")) {
                flag = true;
                tmp.push($(this).val());
            }
        });
        $('.popStored input:checkbox').each(function () {
            $(this).prop('checked', false);
        });
        if (flag) {
            //弹出分组框
            $('#pop').toggle();
        } else {
            alert('请选择商品');
        }
    });


</script>
<script src="<?php echo GJ_STATIC_JS . "gj.js" ?>"></script>
</body>