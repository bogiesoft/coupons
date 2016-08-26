<div class="kkfm_r_inner freightAdd">
    <!--搜索框 count-top包含的部分-->
    <div class="top">
        <div class="name">修改运费模板</div>
        <!--<div class="cz"><input type="submit" value="添加商户" class="btn_com_blue"></div>-->
        <div class="cz"><a href="<?php echo Yii::app()->createUrl('mCenter/ShopFreight/ShopFreightInfo');?>" class="btn_comCancel">返回</a></div>
    </div>
    <div class="contant">
        <?php echo CHtml::beginForm('','',array('id'=>'freightupdate')) ?>
        <div class="filed">
            <span class="label"><em class="red">*</em>模板名称</span>
             <span class="text">
             	<?php echo CHtml::textField('freight_name',$model[0]['name'], array('id'=>'freightname','class' => 'txt', 'maxlength' => '32', 'style' => 'width:400px'))?>
             </span>
             <span class="text1 red">
             <?php if (Yii::app()->user->hasFlash('name_error')) {
                 echo Yii::app()->user->getFlash('name_error');
             }?>
             </span>
        </div>
        <div class="filed">
            <span class="label"><em class="red">*</em>模板内容</span>
                <span class="text">
                <table width="100%" cellspacing="0" cellpadding="0" id="tab">
                    <tr class="order-title" >
                        <td align="center" width="40%">可配送至</td>
                        <td align="center">首件（个）</td>
                        <td align="center">运费（元）</td>
                        <td align="center">续件（个）</td>
                        <td align="center">续费（元）</td>
                    </tr>
                    <?php if(isset($model)){foreach($model as $k=>$v){?>

                        <tr id='shengshiqu' class="<?php echo $k;?>">

                        <td align="center" class="area"><!--
                            <ul><li><?php /*echo $v['area'];*/?><li></ul>
                            <div class='btn'><a href='#' id='editarea'>编辑</a> <a href='#' id='deletearea'>删除</a></div>
                        --></td>
                        <td align="center">
                            <input id="first_num<?php echo $k;?>" value="<?php echo $v['first_num'];?>" name='ShopFreight["<?php echo $k;?>"][first_num]'  maxlength = '8' type="text" class="txt">
                        </td>
                        <td align="center">
                            <input id="first_freight<?php echo $k;?>" value="<?php echo $v['first_freight'];?>" name='ShopFreight["<?php echo $k;?>"][first_freight]'  maxlength = '8' type="text" class="txt">
                        </td>
                        <td align="center">
                            <input id="second_num<?php echo $k;?>" value="<?php echo $v['second_num'];?>" name='ShopFreight["<?php echo $k;?>"][second_num]' maxlength = '8' type="text" class="txt">
                        </td>
                        <td align="center">
                            <input id="second_freight<?php echo $k;?>" value="<?php echo $v['second_freight'];?>" name='ShopFreight["<?php echo $k;?>"][second_freight]' maxlength = '8' type="text" class="txt">
                        </td>
                            <td style="display:none"><input id="readarea<?php echo $k;?>"   value="<?php echo $v['area'];?>"  name='ShopFreight["<?php echo $k;?>"][area]' type="text" class="txt"><input id="freight_id<?php echo $k;?>" value="<?php echo $v['id'];?>" name='ShopFreight["<?php echo $k;?>"][id]' type="text" class="txt"></td>
                        </tr>
                    <?php }}?>
                    <input id="mobanid" name="mobanid" value="<?php echo $model[0]['freight_id'];?>" type="text" class="txt" style="display: none">
                    <tr id="setarea">
                        <td colspan="5"><a href="#" class="blue" id='setwhere'>指定可配送区域和运费</a></td>
                    </tr>
                </table>
                    </span>
        </div>
        <div class="filed">
            <span class="label"></span>
             <span class="text">
                <input type="button" class="btn_com_blue" onclick="save()" value="保存">
             </span>
        </div>
    </div>
</div>
<span class="text1 red">
        <?php if (Yii::app()->user->hasFlash('input_error')) {
            echo Yii::app()->user->getFlash('input_error');
        }?>
        </span>

<?php echo CHtml::endForm() ?>

<?php if (Yii::app()->user->hasFlash('success')) { ?>
    <script>alert('<?php echo Yii::app()->user->getFlash('success')?>');location.href='<?php echo Yii::app()->createUrl('mCenter/ShopFreight/ShopFreightInfo')?>'</script>
<?php }?>
<?php if (Yii::app()->user->hasFlash('error')) { ?>
    <script>alert('<?php echo Yii::app()->user->getFlash('error')?>')</script>
<?php }?>


<div class="popWrap" id="pop" style="width:650px; top: 3%; left: 180px; display:none">
    <div class="pop_con popFeight">
        <div class="title">选择可配送区域</div>
        <div class="pfArea">
            <dl class="dl01">
                <dt>可选省、市、区</dt>
                <dd id="area1">

                </dd>
            </dl>
            <div class="add"><a href="#">添加</a></div>
            <dl class="dl02">
                <dt>已选省、市、区</dt>
                <dd id="area2">

                </dd>
            </dl>

            <div class="btn">
                <a href="#" class="btn_com_blue" id="submit">确认</a>
                <a href="#" class="btn_com_gray" onclick="dismiss()">取消</a>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var num=<?php echo count($model);?>;//行数
    var provinceStr=new Array();
    var cityStr=new Array();
    var areaStr=new Array();
    var type=0;//type=0表示添加行，type=1表示替换行
    var editrow=num;
    //var area;
    //读取城市xml
    $(document).ready(function(){
        $.ajax({
            url: "<?php echo GJ_STATIC_JS?>city.xml",
            type: "GET",
            async:false,
            dataType: "xml",
            success: function (data) {
                $(data).find('province').each(function () {
                    //读取省份
                    var province = $(this);
                    //province.attr("name");省份名称
                    // province.attr("postcode");省份代码
                    if(province.attr("postcode")=="")
                    {
                        var ul1 = "<ul id='ula' class='" + province.attr("postcode") + "' style='display:none'><li id='lia' class='" + province.attr("postcode") + "'><a href='#' id='a'><span class='icon'>+</span><span id='province'>" + province.attr("name") + "</span></a><ul id='ulaa' style='display: none'>";
                        var ul2 = "<ul id='ula'  class='" + province.attr("postcode") + "' style='display:none'><li id='lia' class='" + province.attr("postcode") + "'><a href='#'  style='display: none' id='a'><span class='icon'>+</span><span class='del' id='delprovince'>×</span><span id='province'>" + province.attr("name") + "</span></a><ul id='ulaa' style='display: none'>";
                    }
                    else {
                        var ul1 = "<ul id='ula' class='" + province.attr("postcode") + "'><li id='lia' class='" + province.attr("postcode") + "'><a href='#' id='a'><span class='icon'>+</span><span id='province'>" + province.attr("name") + "</span></a><ul id='ulaa' style='display: none'>";
                        var ul2 = "<ul id='ula'  class='" + province.attr("postcode") + "'><li id='lia' class='" + province.attr("postcode") + "'><a href='#'  style='display: none' id='a'><span class='icon'>+</span><span class='del' id='delprovince'>×</span><span id='province'>" + province.attr("name") + "</span></a><ul id='ulaa' style='display: none'>";
                    }
                    $(this).find('city').each(function () {
                        //读取城市
                        var city = $(this);
                        //city.attr("name"));城市名称
                        // city.attr("postcode");城市代码
                        var cityul1 = "<li id='liaa' class='"+city.attr("postcode")+"'><a href='#' id='aa'><span class='icon'>+</span><span id='city'>" + city.attr("name") + "</span></a><ul id='ulaaa' style='display:none'>";
                        var cityul2 = "<li id='liaa' class='"+city.attr("postcode")+"'><a href='#'  style='display: none' id='aa'><span class='icon'>+</span><span class='del' id='delcity'>×</span><span id='city'>" + city.attr("name") + "</span></a><ul id='ulaaa' style='display:none'>";
                        $(this).find('area').each(function () {
                            //读取地区
                            var area = $(this);
                            var areaul1 = "<li id='liaaa' class='"+area.attr("postcode")+"'><a href='#' id='aaa'><span id='area'>" + area.attr('name') + "</span></a></li>";
                            var areaul2 = "<li id='liaaa' class='"+area.attr("postcode")+"'><a href='#'  style='display: none' id='aaa'><span class='del' id='delarea'>×</span><span id='area'>" + area.attr('name') + "</span></a></li>";
                            cityul1 += areaul1;
                            cityul2 += areaul2;
                        });
                        var cityend1 = "</ul></li>";
                        cityul1+=cityend1;
                        cityul2+=cityend1;
                        ul1 += cityul1;
                        ul2 += cityul2;
                    });
                    var end1 = "</ul></li></ul>";
                    ul1 += end1;
                    ul2+=end1;
                    $('#area1').append(ul1);
                    $('#area2').append(ul2);
                });

            }
        });
        //将数据库读出的地区存放到数组里面
        for(var i=0;i<num;i++) {
            //省#市$区
            var aProvince = new Array();
            aProvince[0] = new Array();//省
            aProvince[1] = new Array();//市
            aProvince[2] = new Array();//区
            var areaCode = $('#readarea' + i).val();
            var provinceS = areaCode.split('#');
            provinceStr[i] = provinceS[0];
            var cityS = provinceS[1].split('$');
            cityStr[i] = cityS[0];
            areaStr[i] = cityS[1];
            var length = 0;
            //地区代码前两位是省，3.4位是市，后两位是地区，省后4位为0，市后两位为0
            var areaProvince = provinceStr[i].split("|");//存储的省
            var areaCity = cityStr[i].split("|");//存储的市
            var areaArea = areaStr[i].split("|");//存储的区
            var area1 = $(document).find('#area1');
            if (areaProvince[0] != "undefined") {
                for (var jp = 0; jp < areaProvince.length; jp++) {
                    //把同一个省的都划分到aProvince【】里面
                    aProvince[0][length] = areaProvince[jp];
                    aProvince[1][length] = "undefined";
                    aProvince[2][length] = "undefined";
                    length++;
                }
            }
            if (areaCity[0] != "undefined") {

                for (var cp = 0; cp < areaCity.length; cp++) {
                    var flagcity = true;
                    //判断这个市是否在已经存在的省里面
                    for (var cpp = 0; cpp < length; cpp++) {
                        if (aProvince[0][cpp] != "undefined" && areaCity[cp] != "undefined") {
                            //如果省相同
                            if (aProvince[0][cpp].substring(0, 2) == areaCity[cp].substring(0, 2)) {
                                aProvince[1][cpp] = aProvince[1][cpp] + "|" + areaCity[cp];
                                aProvince[2][cpp] = "undefined";
                                flagcity = false;
                            }
                        }
                    }
                    if (flagcity && areaCity[cp] != "undefined") {
                        //省不同,添加省和市
                        aProvince[0][length] = areaCity[cp].substring(0, 2) + "0000";
                        aProvince[1][length] = areaCity[cp];
                        aProvince[2][length] = "undefined";
                        length++;
                    }
                }
            }
            if (areaArea[0] != "undefined") {
                for (var ap = 0; ap < areaArea.length; ap++) {
                    //判断地区是不是在已存在的省里面
                    var flagarea = true;
                    for (var ttt = 0; ttt < length; ttt++) {
                        var provinceHead = aProvince[0][ttt].substring(0, 2);
                        var areaHead = areaArea[ap].substring(0, 2);
                        if (provinceHead == areaHead&&areaArea[ap]!="undefined") {
                            //这个在已存在的省里面
                            //市
                            if (aProvince[1][ttt] == "undefined") {
                                //这个省的市没有,直接添加市
                                aProvince[1][ttt] = areaArea[ap].substring(0, 4) + "00";
                                aProvince[2][ttt] = areaArea[ap];
                            }
                            else {
                                //如果已经这个省已经有市区存在，需要检验，当前区的市是否已经存在，
                                //如果市不存在，添加市，如果存在，不添加，再判断当前市里面区是否重复
                                if(areaArea[ap].substring(0, 4)==aProvince[2][ttt].substring(0, 4)&&aProvince[2][ttt]!="undefined")
                                {
                                    //市区已经存在，只添加区
                                    if(aProvince[2][ttt]!="undefined")
                                    {
                                        //区不为空
                                        aProvince[2][ttt] = aProvince[2][ttt]+"|"+areaArea[ap];
                                    }
                                    else
                                        aProvince[2][ttt] = areaArea[ap];
                                }
                                else
                                {
                                    //不存在
                                    aProvince[1][ttt] =aProvince[1][ttt]+"|"+areaArea[ap].substring(0, 4) + "00";
                                    if(aProvince[2][ttt]!="undefined")
                                    {
                                        //区不为空
                                        aProvince[2][ttt] = aProvince[2][ttt]+"|"+areaArea[ap];
                                    }
                                    else
                                        aProvince[2][ttt] = areaArea[ap];
                                }
                            }
                            flagarea = false;
                        }
                    }
                    if (flagarea && areaArea[ap] != "undefined") {
                        //不在已存在的省里面，添加省市
                        aProvince[0][length] = areaArea[ap].substring(0, 2) + "0000";
                        aProvince[1][length] = areaArea[ap].substring(0, 4) + "00";
                        aProvince[2][length] = areaArea[ap];
                        length++;
                    }
                }
            }
            //将具体省市区显示在页面上
            var h = "<ul>";
            for (var pp = 0; pp < aProvince[0].length; pp++) {
                if (aProvince[0][pp] != "undefined") {
                    var html = "<li>" + area1.find('li.' + aProvince[0][pp]).find('#province').text();
                    //市
                    if (aProvince[1][pp] != "undefined") {
                        var cct = aProvince[1][pp].split("|");
                        for (var yy = 0; yy < cct.length; yy++) {
                            //获取这个城市的前四位
                            var city4=cct[yy].substring(0,4);
                            html = html + "(";
                            html = html + area1.find('li.' + cct[yy]).find('#city').text();
                            //区
                            if (aProvince[2][pp] != "undefined") {
                                //判断这个区是否属于上面这个城市
                                html = html + "<span>";
                                var aat = aProvince[2][pp].split("|");
                                for (var uu = 0; uu < aat.length; uu++) {
                                    //获取地区前四位与城市匹配
                                    var area4=aat[uu].substring(0,4);
                                    if(city4==area4)
                                        html = html + area1.find('li.' + aat[uu]).find('#area').text() + " ";
                                }
                                html = html + "</span>";
                            }
                            html = html + ")";
                        }
                    }
                    html = html + "</li>" + " ";
                    h = h + html;
                }
            }
            h=h+"</ul><div class='btn'><a href='#' id='editarea'>编辑</a> <a href='#' id='deletearea'>删除</a></div>";
            $('#tab').find('.'+i).find('td').eq(0).html(h);
            window.parent.callParAutoResize("main",$("body").height());
        }
    });
    $(document).on('click','#area1 .icon',function(e){
        //获取a相邻的下一个兄弟节点
        var _nextObj = $(this).parent().next();

        //假如这个对象是隐藏的就把它显示出来，然后把当前点击对象a下的class="icon"的内容改成"-"；
        //否则把对象和对象下的所有ul都隐藏掉，并把所有a下的class="icon"的内容改成"+"；
        if(_nextObj.is(":hidden")){
            _nextObj.show();
            $(this).parent().find(".icon").html("-");
        }else{
            $(this).parent().parent().find("ul").hide();
            $(this).parent().parent().find(".icon").html("+");
        }
    });

    $(document).on('click','#area2 .icon',function(e){
        //获取a相邻的下一个兄弟节点
        var _nextObj = $(this).parent().next();

        //假如这个对象是隐藏的就把它显示出来，然后把当前点击对象a下的class="icon"的内容改成"-"；
        //否则把对象和对象下的所有ul都隐藏掉，并把所有a下的class="icon"的内容改成"+"；
        if(_nextObj.is(":hidden")){
            _nextObj.show();
            $(this).parent().find(".icon").html("-");
        }else{
            $(this).parent().parent().find("ul").hide();
            $(this).parent().parent().find(".icon").html("+");
        }
    });

    $(document).on('click','#area1 #province',function(e){
        var a=$(this).parent();
        var ppc= a.parent().attr('class');
        if(a.hasClass('selected'))
        {
            a.attr('class','');
            //城市
            a.next().find('#aa').attr('class','');
            //区
            a.next().find('#aaa').attr('class','');
            //删除省市区
        }
        else
        {
            a.attr('class','selected');
            a.next().find('#aa').attr('class','selected');
            a.next().find('#aaa').attr('class','selected');
            //**********************添加省市区*********************************
        }
    });

    $(document).on('click','#area1 #city',function(e){
        var a=$(this).parent();
        var ppc= a.parent().parent().parent().attr('class');
        var cpc= a.parent().attr('class');

        if(a.hasClass('selected'))
        {
            a.attr('class','');
            a.next().find('#aaa').attr('class','');

            //删除省市区
        }
        else
        {
            a.attr('class','selected');
            a.next().find('#aaa').attr('class','selected');
        }
        var jia=0;
        a.parent().parent().children('#liaa').each(function(e){
            //如果还有没被选择的就加一
            var dis=$(this).find('#aa').attr('style');
            var sdis=dis.indexOf('display');

            if($(this).find('#aa').hasClass('')&&sdis<0)
            {
                jia++;
            }
        });
        if(jia!=0)
        {
            a.parent().parent().parent().find('#a').attr('class','');
        }
        else if(jia==0)
        {
            a.parent().parent().parent().find('#a').attr('class', 'selected');
        }
    });

    $(document).on('click','#area1 #area',function(e){
        var a=$(this).parent();//<a>
        var apc= a.parent().attr('class');
        var cpc= a.parent().parent().parent().attr('class');
        var ppc= a.parent().parent().parent().parent().parent().attr('class');


        if(a.hasClass('selected'))
        {
            a.attr('class','');
            //删除省市区
        }
        else
        {
            a.attr('class','selected');
            //**********************添加省市区*********************************
        }

        var jia=0;
        a.parent().parent().children('#liaaa').each(function(e){
            //如果还有没被选择的就加一
            var dis=$(this).find('#aaa').attr('style');
            var sdis=dis.indexOf('display');

            if($(this).find('#aaa').hasClass('')&&sdis<0)
            {
                jia++;
            }
        });


        if(jia!=0)
        {
            a.parent().parent().parent().find('#aa').attr('class','');
        }
        else if(jia==0)
        {
            a.parent().parent().parent().find('#aa').attr('class','selected');
            //**********************添加省市区*********************************
        }

        var cjia=0;
        a.parent().parent().parent().parent().children('#liaa').each(function(e){
            //如果还有没被选择的就加一
            var cdis=$(this).find('#aa').attr('style');
            var csdis=cdis.indexOf('display');

            if($(this).find('#aaa').hasClass('')&&csdis<0)
            {
                cjia++;
            }
        });
        if(cjia!=0)
        {
            a.parent().parent().parent().parent().parent().find('#a').attr('class','');
        }
        else if(cjia==0)
        {
            a.parent().parent().parent().parent().parent().find('#a').attr('class','selected');
        }
    });

    $(document).on('click','.pfArea .add',function(e){
        var area1=$(document).find('#area1');
        var area2=$(document).find('#area2');
        area1.children('#ula').each(function(e){
            //判断是否有市隐藏
            if($(this).find('#a').hasClass('selected')){
                //整个省被选中
                var province=$(this);
                var ppc=$(this).attr('class');
                //防止重复添加市和区,去掉已经保存过的市和区
                $(this).find('#ulaa').children('#liaa').each(function(e){
                    if($(this).find('#aa').attr('style')=='')
                    {
                        var class_liaa=$(this).attr('class');
                        area2.find('li.'+class_liaa).find('#aa').attr('style','');
                        $(this).find('#ulaaa').children('#liaaa').each(function(e){
                            if($(this).find('#aaa').attr('style')=='')
                            {
                                var class_liaaa=$(this).attr('class');
                                area2.find('li.'+class_liaaa).find('#aaa').attr('style','');
                            }
                        });
                    }
                });
                $(this).find('#a').attr('style','display:none');//隐藏area1省
                $(this).find('#aa').attr('style','display:none');
                $(this).find('#aaa').attr('style','display:none');
                area2.find('li.'+ppc).find('#a').attr('style','');//显示area2省
            }
            else
            {
                //不是整个省被选中$(this)是ula
                $(this).find('#ulaa').children('#liaa').each(function(e){
                    //遍历这个省的每个城市
                    if($(this).find('#aa').hasClass('selected')&&$(this).find('#aa').attr('style').indexOf('display')<0){
                        //这个城市是被选中的且是显示的
                        var cpc=$(this).attr('class');
                        area2.find('li.'+cpc).parent().parent().find('#a').attr('style','');
                        area2.find('li.'+cpc).find('#aa').attr('style','');
                        area2.find('li.'+cpc).parent().attr('style','display:block');
                        $(this).find('#ulaaa').children('#liaaa').each(function(e){
                            if($(this).find('#aaa').attr('style').indexOf('display')<0&&$(this).find('#aaa').hasClass('selected'))
                            {
                                var apost=$(this).attr('class');
                                area2.find('li.'+apost).find('#aaa').attr('style','');
                            }
                        });
                        $(this).find('#aa').attr('style','display:none');
                        $(this).find('#aaa').attr('style','display:none');

                    }
                    else
                    {
                        //如果这个市没有被选中,遍历这个市的区
                        $(this).find('#ulaaa').children('#liaaa').each(function(e){
                            if($(this).find('#aaa').hasClass('selected'))
                            {
                                var apc=$(this).attr('class');
                                $(this).find('#aaa').attr('style','display:none');
                                area2.find('li.'+apc).parent().attr('style','display:block');
                                area2.find('li.'+apc).parent().parent().find('#aa').attr('style','');
                                area2.find('li.'+apc).parent().parent().parent().attr('style','display:block');
                                area2.find('li.'+apc).parent().parent().parent().parent().find('#a').attr('style','');
                                area2.find('li.'+apc).find('#aaa').attr('style','');
                            }
                        });
                    }
                });
            }
        });
    });

    $(document).on('click','#submit',function(e){
        //读取已选省市区
        /*provinceStr[num]="";
         cityStr[num]="";
         areaStr[num]="";*/
        var inputValue="";//隐藏的input的值
        var arrayArea=new Array();
        var area2= $(this).parent().prev().find('#area2');
        var area1= $(document).find('#area1');
        var table=$(document).find('.tab');
        var tip="请选择地区";
        if(type==0)
            var html="<tr id='shengshiqu' class='"+editrow+"'><td align='center' class='area'><ul>";
        else {
            var html = "<td align='center' class='area'><ul>";
            provinceStr[editrow]=undefined;
            cityStr[editrow]=undefined;
            areaStr[editrow]=undefined;
        }
        area2.children('#ula').each(function(e){
            var cnum=0;
            var ps=$(this).find('#a').attr('style');
            var pi=ps.indexOf('display');
            if(pi<0)
            {
                //html添加省
                var pname=$(this).find('#province').text();
                html=html+"<li>"+pname;
                //PI<0这个省显示了
                //判断是显示全市还是部分市
                $(this).find('#ulaa').children('#liaa').each(function(e){
                    var cs=$(this).find('#aa').attr('style');
                    var ci=cs.indexOf('display');
                    if(ci>=0)
                    {
                        cnum++;
                    }
                    $(this).find('#ulaaa').children('#liaaa').each(function(e){
                        var ast=$(this).find('#aaa').attr('style');
                        var cis=ast.indexOf('display');
                        if(cis>=0)
                        {
                            cnum++;
                        }
                    });
                });
                if(cnum>0)
                {
                    //cnum大于0表示部分市显示
                    //判断是否是全区还是部分区
                    $(this).find('#ulaa').children('#liaa').each(function(e){
                        var ccs=$(this).find('#aa').attr('style');
                        var cci=ccs.indexOf('display');
                        if(cci<0)//这个市是显示的，那就遍历这个市
                        {
                            var anum=0;
                            $(this).find('#ulaaa').children('#liaaa').each(function (e) {
                                var as = $(this).find('#aaa').attr('style');
                                var ai = as.indexOf('display');
                                if (ai >= 0) {
                                    anum++;
                                }
                            });
                            if (anum > 0) {
                                //这个市部分区显示只记录区
                                var cname=$(this).find('#city').text();//获取这个市名称
                                html=html+"("+cname+":"+"<span>";
                                $(this).find('#ulaaa').children('#liaaa').each(function (e) {
                                    //遍历这个市，记录显示的区
                                    var as = $(this).find('#aaa').attr('style');
                                    var ai = as.indexOf('display');
                                    if (ai < 0) {
                                        var apc=$(this).attr('class');//地区代码

                                        if(areaStr[editrow]==undefined)
                                        {
                                            areaStr[editrow]=apc;
                                        }
                                        else
                                        {
                                            areaStr[editrow]=areaStr[editrow]+"|"+apc;
                                        }
                                        //html添加地区
                                        var aname=$(this).find('#area').text();
                                        html=html+aname+" ";
                                    }

                                });
                                html=html+"</span>";
                                html=html+")";
                            }
                            else {
                                //这个市全部区显示
                                var cpc=$(this).attr('class');//城市代码
                                if(cityStr[editrow]==undefined)
                                {
                                    cityStr[editrow]=cpc;
                                }
                                else
                                {
                                    cityStr[editrow]=cityStr[editrow]+"|"+cpc;
                                }
                                var ccname=$(this).find('#city').text();//获取这个市名称
                                html=html+"("+ccname;
                                html=html+")";
                            }
                        }
                    });

                }
                else
                {
                    //全部市显示
//                   判断这个市是否是全区显示
                    var cccnum=0;
                    $(this).find('#ulaa').children('#liaa').each(function(e){
                        //遍历这个市的全部区
                        var ccnum=0;
                        $(this).find('#ulaaa').children('#liaaa').each(function(e){
                            var aas=$(this).find('#aaa').attr('style');
                            var aai=aas.indexOf('display');
                            if(aai>=0)
                            {
                                ccnum++;//有不显示的就加一
                                cccnum++;//不是全部区显示
                            }
                        });
                        if(ccnum>0)
                        {
                            //这个市部分区显示那就只记录这些区
                            $(this).find('#ulaaa').children('#liaaa').each(function(e){
                                var aas=$(this).find('#aaa').attr('style');
                                var aai=aas.indexOf('display');
                                var apc=$(this).find('class');
                                if(aai<0)
                                {
                                    if(areaStr[editrow]==undefined)
                                    {
                                        areaStr[editrow]=apc;
                                    }
                                    else
                                    {
                                        areaStr[editrow]=areaStr[editrow]+"|"+apc;
                                    }
                                }
                            });
                        }
                        else
                        {
                            //这个市全部区显示只记录这个市
                            var ccccs=$(this).attr('class');//城市代码
                            if(cityStr[editrow]==undefined)
                            {
                                cityStr[editrow]=ccccs;
                            }
                            else
                            {
                                cityStr[editrow]=cityStr[editrow]+"|"+ccccs;
                            }
                        }
                    });
                    if(cccnum==0)
                    {
                        //全部市全部区显示 记录省
                        var pppc=$(this).find('#lia').attr('class');
                        if(provinceStr[editrow]==undefined)
                        {
                            provinceStr[editrow]=pppc;
                        }
                        else
                        {
                            provinceStr[editrow]=provinceStr[editrow]+"|"+pppc;
                        }
                        //将这个省的市去掉
                        var pppc_two=pppc.substring(0,2);

                        var city_array=cityStr[editrow].split("|");
                        for(var city_arrayi=0;city_arrayi<city_array.length;city_arrayi++)
                        {
                            if(pppc_two==city_array[city_arrayi].substring(0,2))
                            {

                                if(cityStr[editrow].indexOf(city_array[city_arrayi]+"|")>=0) {
                                    var array_str1=cityStr[editrow].split(city_array[city_arrayi]+"|");
                                    cityStr[editrow]=array_str1[0]+array_str1[1];
                                }
                                else if(cityStr[editrow].indexOf(city_array[city_arrayi])>=0&&cityStr[editrow].indexOf(city_array[city_arrayi]+"|")<0)
                                {
                                    var array_str2=cityStr[editrow].split(city_array[city_arrayi]);
                                    if(array_str2[1]==""&&array_str2[0]=="")
                                        cityStr[editrow]=undefined;
                                    else if(array_str2[1]!=""&&array_str2[0]=="")
                                        cityStr[editrow]=array_str2[1];
                                    else if(array_str2[1]==""&&array_str2[0]!="")
                                        cityStr[editrow]=array_str2[0].substring(0,array_str2[0].length-1);
                                }
                            }
                        }
                    }
                    //html添加
                    html=html+"</li>"+" ";
                }
            }

        });


        //遍历结束
        //添加一行
        inputValue=provinceStr[editrow]+"#"+cityStr[editrow]+"$"+areaStr[editrow];
        if(type==0)
        {
            if(provinceStr[editrow]!=undefined||cityStr[editrow]!=undefined||areaStr[editrow]!=undefined)
                html+="</ul><div class='btn'><a href='#' id='editarea'>编辑</a> <a href='#' id='deletearea'>删除</a></div></td>";
            else
                html+="<li id='tip'>"+tip+"</li></ul><div class='btn'><a href='#' id='editarea'>编辑</a> <a href='#' id='deletearea'>删除</a></div></td>";
            html+="<td align='center'><input id='first_num"+editrow+"' name='ShopFreight["+editrow+"][first_num]' maxlength = '8' type='text' class='txt'></td>";//一列
            html+="<td align='center'><input id='first_freight"+editrow+"' name='ShopFreight["+editrow+"][first_freight]' maxlength = '8' type='text' class='txt'></td>";//一列
            html+="<td align='center'><input id='second_num"+editrow+"' name='ShopFreight["+editrow+"][second_num]' maxlength = '8' type='text' class='txt'></td>";//一列
            html+="<td align='center'><input id='second_freight"+editrow+"' name='ShopFreight["+editrow+"][second_freight]' maxlength = '8' type='text' class='txt'></td><td style='display:none'><input id='readarea"+editrow+"' value='"+inputValue+"'  name='ShopFreight["+editrow+"][area]'  type='text' class='txt'><input id='freight_id"+editrow+"' value='new' name='ShopFreight["+editrow+"][id]' type='text' class='txt'></td></tr>";//一列
            //添加行
            $('#setarea').remove();//删除一行
            $('#tab').append(html);//添加一行
            var append="<tr id='setarea'><td colspan='5'><a href='#' class='blue' id='setwhere'>指定可配送区域和运费</a></td> </tr>";
            $('#tab').append(append);
            //行数加一
            num++;
        }
        else if(type==1)
        {
            //替换前的input value值
            var first_num=$('#first_num'+editrow).val();
            var first_freight=$('#first_freight'+editrow).val();
            var second_num=$('#second_num'+editrow).val();
            var second_freight=$('#second_freight'+editrow).val();
            if(provinceStr[editrow]!=undefined||cityStr[editrow]!=undefined||areaStr[editrow]!=undefined)
                html+="</ul><div class='btn'><a href='#' id='editarea'>编辑</a> <a href='#' id='deletearea'>删除</a></div></td>";
            else
                html+="<li id='tip'>"+tip+"</li></ul><div class='btn'><a href='#' id='editarea'>编辑</a> <a href='#' id='deletearea'>删除</a></div></td>";
            html+="<td align='center'><input id='first_num"+editrow+"' value='"+first_num+"' name='ShopFreight["+editrow+"][first_num]' maxlength = '8' type='text' class='txt'></td>";//一列
            html+="<td align='center'><input id='first_freight"+editrow+"' value='"+first_freight+"' name='ShopFreight["+editrow+"][first_freight]' maxlength = '8' type='text' class='txt'></td>";//一列
            html+="<td align='center'><input id='second_num"+editrow+"' value='"+second_num+"' name='ShopFreight["+editrow+"][second_num]' type='text' maxlength = '8' class='txt'></td>";//一列
            html+="<td align='center'><input id='second_freight"+editrow+"' value='"+second_freight+"' name='ShopFreight["+editrow+"][second_freight]' maxlength = '8' type='text' class='txt'></td><td style='display:none'><input id='readarea"+editrow+"'   value='"+inputValue+"'name='ShopFreight["+editrow+"][area]'  type='text' class='txt'><input id='freight_id"+editrow+"' value='new' name='ShopFreight["+editrow+"][id]' type='text' class='txt'></td>";//一列
            //替换行
            $(document).find('#tab').find('.'+editrow).html(html);
        }
        //窗口高度自适应
        window.parent.callParAutoResize("main",$("body").height());
        $('#pop').hide();
        //异步刷新页面
        $.ajax({
            type:"GET",
            url:"<?php echo Yii::app()->createUrl('mCenter/shopFreight/addFreight');?>",
            async:true,
            success:function(data){
                //模拟刷新页面
                var area1=$(document).find('#area1');
                var area2=$(document).find('#area2');
                area1.children('#ula').each(function(e){
                    $(this).find('#a').attr('class','');
                    $(this).find('#a').attr('style','');
                    $(this).find('#a').find('.icon').text('+');
                    $(this).find('#ulaa').attr('style','display:none');//收缩
                    $(this).find('#aa').attr('class','');
                    $(this).find('#aa').attr('style','');
                    $(this).find('#aa').find('.icon').text('+');
                    $(this).find('#ulaaa').attr('style','display:none');//收缩
                    $(this).find('#aaa').attr('class','');
                    $(this).find('#aaa').attr('style','');
                    $(this).find('#aaa').find('.icon').text('+');
                });
                area2.children('#ula').each(function(e){
                    $(this).find('#a').attr('class','');
                    $(this).find('#a').attr('style','display:none');
                    $(this).find('#a').find('.icon').text('+');
                    $(this).find('#ulaa').attr('style','display:none');//收缩
                    $(this).find('#aa').attr('class','');
                    $(this).find('#aa').attr('style','display:none');
                    $(this).find('#aa').find('.icon').text('+');
                    $(this).find('#ulaaa').attr('style','display:none');//收缩
                    $(this).find('#aaa').attr('class','');
                    $(this).find('#aaa').attr('style','display:none');
                    $(this).find('#aaa').find('.icon').text('+');
                });

            }

        });

    });

    function flashHtml()
    {
        //模拟刷新页面
        var area1=$(document).find('#area1');
        var area2=$(document).find('#area2');
        area1.children('#ula').each(function(e){
            $(this).find('#a').attr('class','');
            $(this).find('#a').attr('style','');
            $(this).find('#a').find('.icon').text('+');
            $(this).find('#ulaa').attr('style','display:none');//收缩
            $(this).find('#aa').attr('class','');
            $(this).find('#aa').attr('style','');
            $(this).find('#aa').find('.icon').text('+');
            $(this).find('#ulaaa').attr('style','display:none');//收缩
            $(this).find('#aaa').attr('class','');
            $(this).find('#aaa').attr('style','');
            $(this).find('#aaa').find('.icon').text('+');
        });
        area2.children('#ula').each(function(e){
            $(this).find('#a').attr('class','');
            $(this).find('#a').attr('style','display:none');
            $(this).find('#a').find('.icon').text('+');
            $(this).find('#ulaa').attr('style','display:none');//收缩
            $(this).find('#aa').attr('class','');
            $(this).find('#aa').attr('style','display:none');
            $(this).find('#aa').find('.icon').text('+');
            $(this).find('#ulaaa').attr('style','display:none');//收缩
            $(this).find('#aaa').attr('class','');
            $(this).find('#aaa').attr('style','display:none');
            $(this).find('#aaa').find('.icon').text('+');
        });
    }

    //编辑行
    $(document).on('click','#editarea',function(e){
        //刷新页面
        flashHtml();
        //初始化左边地区
        var area1=$(document).find('#area1');
        //初始化右边地区
        var area2=$(document).find('#area2');
        //第几行
        var rownum=$(this).parent().parent().parent().attr('class');
        //将已经选择的省市区隐藏掉
        if(provinceStr.length>0)
        {
            for(var proStri=0;proStri<provinceStr.length;proStri++)
            {
                if(provinceStr[proStri]!=undefined) {
                    var proArray = provinceStr[proStri].split("|");
                    for (var j = 0; j < proArray.length; j++) {
                        //将整个省都隐藏起来
                        if (proArray[j] != undefined) {
                            area1.find('li.' + proArray[j]).find('#a').attr('style', 'display:none');
                            area1.find('li.' + proArray[j]).find('#aa').attr('style', 'display:none');
                            area1.find('li.' + proArray[j]).find('#aaa').attr('style', 'display:none');
                        }
                    }
                }
            }
            if(provinceStr[rownum]!=undefined) {
                var pro2A = provinceStr[rownum].split("|");
                for (var pro2I = 0; pro2I < pro2A.length; pro2I++) {
                    if (pro2A[pro2I] != undefined) {
                        area2.find('li.' + pro2A[pro2I]).find('#a').attr('style', '');
                        area2.find('li.' + pro2A[pro2I]).find('#aa').attr('style', '');
                        area2.find('li.' + pro2A[pro2I]).find('#aaa').attr('style', '');
                    }
                }
            }
        }
        if(cityStr.length>0)
        {
            for(var cityStri=0;cityStri<cityStr.length;cityStri++)
            {

                if(cityStr[cityStri]!=undefined) {
                    var cityArray = cityStr[cityStri].split("|");

                    for (var jj = 0; jj < cityArray.length; jj++) {
                        //将整个市都隐藏起来
                        if (cityArray[jj] != undefined) {
                            area1.find('li.' + cityArray[jj]).find('#aa').attr('style', 'display:none');
                            area1.find('li.' + cityArray[jj]).find('#aaa').attr('style', 'display:none');
                        }
                    }
                }
            }
            //area2的市和区显示出来
            if(cityStr[rownum]!=undefined) {
                var city2A = cityStr[rownum].split("|");
                for (var city2I = 0; city2I < city2A.length; city2I++) {
                    if (city2A[city2I] != undefined) {
                        area2.find('li.' + city2A[city2I]).parent().parent().find('#a').attr('style', '');
                        area2.find('li.' + city2A[city2I]).find('#aa').attr('style', '');
                        area2.find('li.' + city2A[city2I]).find('#aaa').attr('style', '');
                    }
                }
            }
        }
        if(areaStr.length>0)
        {
            for(var areaStri=0;areaStri<areaStr.length;areaStri++)
            {
                if(areaStr[areaStri]!=undefined) {
                    var areaArray = areaStr[areaStri].split("|");
                    for (var jjj = 0; jjj < areaArray.length; jjj++) {
                        //区都隐藏起来
                        if (areaArray[jjj] != undefined) {
                            area1.find('li.' + areaArray[jjj]).find('#aaa').attr('style', 'display:none');
                        }
                    }
                }
            }
            //area2的区显示出来
            if(areaStr[rownum]!=undefined) {
                var area2A = areaStr[rownum].split("|");
                for (var area2I = 0; area2I < area2A.length; area2I++) {
                    if (area2A[area2I] != undefined) {
                        area2.find('li.' + area2A[area2I]).parent().parent().parent().parent().find('#a').attr('style', '');
                        area2.find('li.' + area2A[area2I]).parent().parent().find('#aa').attr('style', '');
                        area2.find('li.' + area2A[area2I]).find('#aaa').attr('style', '');
                    }
                }
            }
        }
        //去掉内部为空的省市
        area1.children('#ula').each(function(e){
            //如果该省是显示的，判断该省内部是否为空
            if($(this).find('#aa').attr('style')!=''||$(this).find('#aaa').attr('style')!='')
            {
                //该省内部有隐藏,循环遍历该省
                var hide=true;//true该省全部隐藏
                $(this).find('#ulaa').children('#liaa').each(function(e){
                    //如果在这个市内的区全部隐藏，那就隐藏这个市
                    var show_area=true;
                    $(this).find('#ulaaa').children('#liaaa').each(function(e){
                        if($(this).find('#aaa').attr('style')=='')
                            show_area=false;
                    });
                    if(show_area)
                    {
                        $(this).find('#aa').attr('style','display:none');
                    }
                });

                $(this).find('#ulaa').children('#liaa').each(function(e){
                    //再次循环市，所有市都隐藏，就隐藏省
                    if($(this).find('#aa').attr('style')=='')
                    {
                        hide=false;
                    }
                });
                if(hide)
                {
                    $(this).find('#a').attr('style','display:none');
                }
            }
        });
        //替换列
        type=1;
        editrow=rownum;
        //显示页面
        $('#pop').show();
    });

    //删除一行
    $(document).on('click','#deletearea',function(e){
        var n=$(this).parent().parent().parent().attr('class');

        provinceStr[n]=undefined;
        cityStr[n]=undefined;
        areaStr[n]=undefined;
        $(this).parent().parent().parent().remove();
        num--;
    });

    //删除省
    $(document).on('click','#delprovince',function(e){

        var province=$(this).parent().parent().attr('class');

        var area1=$(document).find('#area1');
        var show=area1.find('li.'+province);
        show.find('#a').attr('style','');//显示area1省
        show.find('#a').attr('class','');//去掉选中状态2015年11月12日18:55:13
        $(this).parent().next().children('#liaa').each(function(e){
            if($(this).find('#aa').attr('style')=='')
            {
                var liaa_class=$(this).attr('class');
                show.find('li.'+liaa_class).find('#aa').attr('style','');
                show.find('li.'+liaa_class).find('#aa').attr('class','');//去掉选中状态2015年11月12日18:55:13
                $(this).find('#ulaaa').children('#liaaa').each(function(e){
                    if($(this).find('#aaa').attr('style')=='') {
                        var liaaa_class = $(this).attr('class');
                        show.find('li.' + liaaa_class).find('#aaa').attr('style', '');
                        show.find('li.' + liaaa_class).find('#aaa').attr('class', '');//去掉选中状态2015年11月12日18:55:13
                    }
                });
            }
        });
        //隐藏area2里面的市和区
        $(this).parent().attr('style','display:none');
        $(this).parent().parent().find('#aa').attr('style','display:none');
        $(this).parent().parent().find('#aaa').attr('style','display:none');
    });

    //点击删除市
    $(document).on('click','#delcity',function(e){

        //判断在area2是不是只有一个市
        var i=0;
        $(this).parent().parent().parent().children('#liaa').each(function(e){
            var t=$(this).find('#aa').attr('style');
            var str=t.indexOf("display");
            if(str<0)
            {
                i++;
            }
        });
        var city=$(this).parent().parent().attr('class');
        var area1=$(document).find('#area1');
        var show=area1.find('li.'+city);
        if(i==1)
        {
            //只有一个市
            $(this).parent().parent().parent().parent().find('#a').attr('style','display:none');
            show.parent().parent().find('#a').attr('style','');
            show.find('#aa').attr('style','');
            show.parent().parent().find('#a').attr('class','');//取消选中状态2015年11月12日18:57:39
            show.find('#aa').attr('class','');//取消选中状态2015年11月12日18:57:39
            $(this).parent().next().children('#liaaa').each(function(e){
                if($(this).find('#aaa').attr('style')=='')
                {
                    var classLiaaa=$(this).attr('class');
                    show.find('li.'+classLiaaa).find('#aaa').attr('style','');
                    show.find('li.'+classLiaaa).find('#aaa').attr('class','');//取消选中状态2015年11月12日18:57:39
                }
            });
        }
        else
        {
            var po=show.parent().parent().find('#a').attr('style');
            var pl=po.indexOf("display");
            if(pl>=0)
            {
                //如果是整个省市隐藏的
                show.parent().parent().find('#a').attr('style','');
                show.parent().parent().find('#ulaa').attr('style','display:block');
                show.parent().parent().find('#a').attr('class','');//取消选中状态2015年11月12日18:57:39
            }
            show.find('#aa').attr('style','');
            show.find('#aa').attr('class','');//取消选中状态2015年11月12日18:57:39
            $(this).parent().next().children('#liaaa').each(function(e){
                if($(this).find('#aaa').attr('style')=='')
                {
                    var classLiaaa=$(this).attr('class');
                    show.find('li.'+classLiaaa).find('#aaa').attr('style','');
                    show.find('li.'+classLiaaa).find('#aaa').attr('class','');//取消选中状态2015年11月12日18:57:39
                }
            });
        }
        $(this).parent().attr('style','display:none');
        $(this).parent().parent().find('#aaa').attr('style','display:none');
    });


    //删除地区
    $(document).on('click','#delarea',function(e){
        //判断是否是最后一个区
        var area1=$(document).find('#area1');
        var area=$(this).parent().parent().attr('class');
        var show=area1.find('li.'+area);
        var i=0;
        $(this).parent().parent().parent().children('#liaaa').each(function(e){
            //判断是否是一个市的最后一个区
            var t=$(this).find('#aaa').attr('style');
            var str= t.indexOf('display');
            if(str<0)
            {
                i++;
            }
        });
        var pi=0;//等与1是省的最后一个区
        $(this).parent().parent().parent().parent().parent().children('#liaa').each(function(e){
            //判断是否是省的最后一个区
            $(this).find('#ulaaa').children('#liaaa').each(function(e){
                var pt=$(this).find('#aaa').attr('style');
                var pstr= pt.indexOf('display');
                if(pstr<0)
                {
                    pi++;
                }
            });
        });
        if(pi==1&&i==1)
        {
            //是省的最后一个区
            show.parent().parent().parent().parent().find('#a').attr('style','');
            show.find('#aaa').attr('style','');
            show.parent().parent().parent().parent().find('#a').attr('class','');//去掉选中状态2015年11月12日19:00:13
            show.find('#aaa').attr('class','');//去掉选中状态2015年11月12日19:00:13
            $(this).parent().parent().parent().parent().find('#aa').attr('style','display:none');
            $(this).parent().parent().parent().parent().parent().parent().find('#a').attr('style','display:none');
            $(this).parent().attr('style','display:none');
        }
        else if(pi!=1&&i==1)
        {
            //其中一个市的最后一个区
            show.parent().parent().parent().parent().find('#a').attr('style','');
            show.parent().parent().find('#aa').attr('style','');
            show.find('#aaa').attr('style','');
            show.parent().parent().parent().parent().find('#a').attr('class','');//去掉选中状态2015年11月12日19:00:13
            show.parent().parent().find('#aa').attr('class','');//去掉选中状态2015年11月12日19:00:13
            show.find('#aaa').attr('class','');//去掉选中状态2015年11月12日19:00:13
            $(this).parent().parent().parent().parent().find('#aa').attr('style','display:none');
            $(this).parent().attr('style','display:none');
        }
        else
        {
            //既不是省的最后一个区，也不是市的最后一个区
            show.parent().parent().parent().parent().find('#a').attr('style','');
            show.parent().parent().find('#aa').attr('style','');
            show.find('#aaa').attr('style','');
            show.parent().parent().parent().parent().find('#a').attr('class','');//去掉选中状态2015年11月12日19:00:13
            show.parent().parent().find('#aa').attr('class','');//去掉选中状态2015年11月12日19:00:13
            show.find('#aaa').attr('class','');//去掉选中状态2015年11月12日19:00:13
            $(this).parent().attr('style','display:none');
        }
    });

    $(document).on('click','#setwhere',function(e)
    {
        //刷新页面
        flashHtml();
        var area1=$(document).find('#area1');
        //将已经选择的省市区隐藏掉
        for(var i=0;i<provinceStr.length;i++)
        {
            if(provinceStr[i]!=undefined)
            {
                var provinceArray=provinceStr[i].split("|");
                var x=provinceStr[i].length;
                for(var j=0;j<provinceArray.length;j++)
                {
                    //将整个省都隐藏起来
                    area1.find('li.'+provinceArray[j]).find('#a').attr('style','display:none');
                    area1.find('li.'+provinceArray[j]).find('#aa').attr('style','display:none');
                    area1.find('li.'+provinceArray[j]).find('#aaa').attr('style','display:none');
                }
            }
        }

        for(var ii=0;ii<cityStr.length;ii++)
        {
            if(cityStr[ii]!=undefined)
            {
                var cityArray=cityStr[ii].split("|");
                var xx=cityStr[ii].length;
                for(var jj=0;jj<cityArray.length;jj++)
                {
                    //将整个市都隐藏起来
                    area1.find('li.'+cityArray[jj]).find('#aa').attr('style','display:none');
                    area1.find('li.'+cityArray[jj]).find('#aaa').attr('style','display:none');
                }
            }
        }

        for(var iii=0;iii<areaStr.length;iii++)
        {
            if(areaStr[iii]!=undefined)
            {
                var areaArray=areaStr[iii].split("|");
                var xxx=areaStr[iii].length;
                for(var jjj=0;jjj<areaArray.length;jjj++)
                {
                    //区都隐藏起来
                    area1.find('li.'+areaArray[jjj]).find('a#aaa').attr('style','display:none');
                }
            }
        }

        //去掉内部为空的省市
        area1.children('#ula').each(function(e){
            //如果该省是显示的，判断该省内部是否为空
            if($(this).find('#aa').attr('style')!=''||$(this).find('#aaa').attr('style')!='')
            {
                //该省内部有隐藏,循环遍历该省
                var hide=true;//true该省全部隐藏
                $(this).find('#ulaa').children('#liaa').each(function(e){
                    //如果在这个市内的区全部隐藏，那就隐藏这个市
                    var show_area=true;
                    $(this).find('#ulaaa').children('#liaaa').each(function(e){
                        if($(this).find('#aaa').attr('style')=='')
                            show_area=false;
                    });
                    if(show_area)
                    {
                        $(this).find('#aa').attr('style','display:none');
                    }
                });

                $(this).find('#ulaa').children('#liaa').each(function(e){
                    //再次循环市，所有市都隐藏，就隐藏省
                    if($(this).find('#aa').attr('style')=='')
                    {
                        hide=false;
                    }
                });
                if(hide)
                {
                    $(this).find('#a').attr('style','display:none');
                }
            }
        });
        type=0;
        editrow=num;
        $('#pop').show();
    });

    function dismiss()
    {
        $('#pop').hide();
    }


    //提交表单
    function save()
    {
        var flag_freight_name=true;
        var falg_space_name=true;
        var flag_repeat_name=true;
        var flag_freight_first_num=true;
        var flag_freight_first_freight=true;
        var flag_freight_second_num=true;
        var flag_freight_second_freight=true;
        var flag_empty_area=true;
        var title=$('#freightname');
        var fright_first_num=new Array();
        var fright_first_freight=new Array();
        var fright_second_num=new Array();
        var fright_second_freight=new Array();
        var reg = /\s/;
        if(title.val()=="")
        {
            flag_freight_name=false;
        }
        else if(reg.exec(title.val())!=null)
        {
            falg_space_name=false;
        }
        else
        {
            $.ajax({
                url:"<?php echo Yii::app()->createUrl('mCenter/ShopFreight/UpdateCheckName')?>?name="+title.val()+"&mobanid="+$('#mobanid').val(),
                type:"get",
                async:false,
                success:function(data)
                {
                    if(data=='success') {
                        flag_repeat_name=true;
                    }
                    else {
                        flag_repeat_name=false;
                    }
                }
            });
        }
        if($('#tip').attr('id')=='tip')
            flag_empty_area=false;
        var exp =/^(\d{0,5}|0)(\.[\d]{1,2})?$/;
        var expnum=/^[1-9]{1,8}?$/;
        var xujian_expnum=/^[0-9]{1,8}?$/;
        var tr_num=0;
        $('#tab').find('tbody').children('tr').each(function(e){
            tr_num++;
        });
        for(var i=0;i<tr_num-2;i++)
        {
//            if(provinceStr[i]!=undefined&&cityStr[i]!=undefined&&areaStr[i]!=undefined) {
                fright_first_num[i] = $('#first_freight' + i);
                fright_first_freight[i] = $('#first_num' + i);
                fright_second_num[i] = $('#second_freight' + i);
                fright_second_freight[i] = $('#second_num' + i);
                if (fright_first_num[i].val() == "") {
                    flag_freight_first_num = false;
                }
                if (fright_first_freight[i].val() == "") {
                    flag_freight_first_freight = false;
                }

                if (!exp.test(fright_first_num[i].val())) {
                    flag_freight_first_num = false;
                }
                if (!exp.test(fright_second_num[i].val())) {
                    flag_freight_second_num = false;
                }
                if (!expnum.test(fright_first_freight[i].val())) {
                    flag_freight_first_freight = false;
                }
                if (!xujian_expnum.test(fright_second_freight[i].val())) {
                    flag_freight_second_freight = false;
                }
                if(fright_second_freight[i].val()=="")
                {
                    flag_freight_second_freight=false;
                }
//            }
        }
        if(num==0)
        {
            alert('请至少添加一个子模板');
            location.reload();
        }
        if(flag_freight_first_num&&falg_space_name&&flag_repeat_name&&flag_empty_area&&flag_freight_first_freight&&flag_freight_second_num&&flag_freight_second_freight&&flag_freight_name) {
            $('#freightupdate').submit();
        }
        else
        {
            if(!flag_freight_name) {
                alert('请填写模板名称!');
                return false;
            }
            if(!falg_space_name)
            {
                alert('模板名称不能有空格!');
                return false;
            }
            if(!flag_repeat_name)
            {
                alert('模板名称重复!');
                return false;
            }
            if(!flag_empty_area) {
                alert('请选择地区');
                return false;
            }
            if(!flag_freight_first_freight) {
                alert('首件个数只能为(非0)数字且不能超过8位!');
                return false;
            }
            if(!flag_freight_first_num) {
                alert('首件运费只能为数字且不能超过99999.99元!');
                return false;
            }
            if(!flag_freight_second_freight) {
                alert('续件个数只能为(非0)数字且不能超过8位!');
                return false;
            }
            if(!flag_freight_second_num) {
                alert('续件运费只能为数字且不能超过99999.99元!');
                return false;
            }
        }
    }

</script>