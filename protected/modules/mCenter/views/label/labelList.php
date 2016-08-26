<script type="text/javascript">
    $(document).ready(main_obj.list_init);
</script>
<div class="kkfm_r_inner">
	<div class="storeManage">
        <div class="top">
            <div class="name">
                标签管理
            </div>
            <div class="cz btn_r">
                <a href="<?php echo Yii::app()->createUrl('mCenter/label/addLabel')?>" class="btn_com_blue">添加标签</a>
            </div>
        </div>
        <div class="bus-contant rules">
            <table cellspacing="0" cellpadding="0" width="100%">
                <tr class="thead">
                    <td width="28.33%">标签名</td>
                    <td width="28.33%">标签值</td>
                    <td width="28.33%">类型</td>
                    <td align="center">操作</td>
                </tr>
                <?php if($tag) { ?>
                <?php foreach($tag as $k => $v) { ?>
                <tr>
                    <td valign="top">
                    	<dl class="list">
                        	<dt><?php echo $v -> name?></dt>
                        </dl>
                    </td>
                    <td valign="top">
                    	<dl class="list">
                            <dt>
                                <?php
                                    if($v -> value != 'null') { 
                                        $values = json_decode($v -> value);
                                        $value = implode('、', $values);
                                        echo $value;
                                    }
                                ?>
                            </dt>
                        </dl>
                    </td >
                     <td valign="top">
                        <dl class="list">
                            <dt><?php echo isset($v -> type) ? $GLOBALS['__TAG_TYPE'][$v -> type] : ''?></dt>
                        </dl>
                    </td >
                    <td align="center" valign="top">
                        <dl class="operate">
                            <dt>操作</dt>
                            <dd>
                                <a href="<?php echo Yii::app()->createUrl('mCenter/label/editLabel',array('id'=>$v -> id,'goUrl' => Yii::app()->request->url))?>">编辑</a>
                            </dd>                    	
                        </dl>
                    </td>
                </tr>
                <?php } } ?>                
            </table>
        </div>
    </div>
</div> 