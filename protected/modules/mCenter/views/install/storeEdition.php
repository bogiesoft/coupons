<body>
    <div class="kkfm_r_inner">
        <div class="storeManage">
            <div class="top">
                <div class="name">
                    玩券版本
                </div>
            </div>
            <div class="v-container">
                <div class="v-version-head">
                    <div class="v-version-count">
                        <span>全部门店数：<?php echo $store_num?></span>
                        <span class="wq-orange">营销版门店数：<?php echo $yx_store_num?></span>
                        <span class="wq-blue">收银版门店数：<?php echo $sy_store_num?></span>
                    </div>
                    <button type="button" class="wq-btn-primary fr" onclick="fixmenu();window.location.href='<?php echo Yii::app()->createUrl('mCenter/Install/StoreEditionRenewUpgrade')?>'">续费升级</button>
                </div>
                <table class="wq-table">
                    <thead>
                        <tr>
                            <th>门店名称</th>
                            <th>玩券版本</th>
                            <th>有效期</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($store_list as $k => $v){?>
                        <tr>
                            <td><?php echo !empty($v -> branch_name)? $v -> name.'-'.$v -> branch_name : $v -> name?></td>
                            <td class="wq-orange"><?php echo $GLOBALS['__STORE_EDITION'][$v -> edition]?></td>
                            <td><?php echo $v -> edition == STORE_EDITION_CASHIER?'长期':date("Y.m.d",strtotime($v -> marking_end_time));?></td>
                        </tr>
                        <?php }?>
                    </tbody>
                    <tr style="border:none">
                    <td style="border:none;text-align:right" colspan="3">
                        <?php $this -> widget('CLinkPager',array(
                            'pages'=>$pages,
                            'header'=>'共&nbsp;<span class="yellow">'.$pages -> getItemCount().'</span>&nbsp;条&nbsp;',
                            'prevPageLabel' => '上一页',
                            'nextPageLabel'=>'下一页',
                            'maxButtonCount'=>8
                        ));?>
                    </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
<script type="text/javascript">
//更改导航位置
function fixmenu(){
    var obj = window.parent.document.getElementsByClassName('bg');
    var obj2 = obj[0].getElementsByTagName('a');
    for(i=0;i<obj2.length;i++){
        obj2[i].className = '';
   	}
    obj2[1].className = 'cur';
}  
</script>                        
                        
                        
                        
                        
                        
                        
                        
                        
                        