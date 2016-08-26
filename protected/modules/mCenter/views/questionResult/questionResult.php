<body>
    <div class="s_header">
        <img src="<?php echo GJ_STATIC_IMAGES; ?>qh_1.jpg">
    </div>
    <div class="s_wrap">
        <div class="s_all">
            <span class="a_all_num">参加调研总人数为<em><?php echo $data -> question_num?></em>人</span>
            <ul class="clearfix">
                <li>
                    <div class="s_list_item">
                        <div class="s_title">
                            目前，支付宝和微信支付注册流程是否顺利？
                        </div>
                        <div class="s_num">
                            <?php if($data -> question_num > 0){echo number_format($data -> q1_num/$data -> question_num*100,2);}else{echo '0';}?>%
                        </div>
                        <div class="s_select">
                            此问题选择是的人数为<?php echo $data -> q1_num?>人
                        </div>
                    </div>
                </li>
                <li>
                    <div class="s_list_item">
                        <div class="s_title">
                            注册流程是否清晰明确？
                        </div>
                        <div class="s_num">
                            <?php if($data -> question_num > 0){echo number_format($data -> q2_num/$data -> question_num*100,2);}else{echo '0';}?>%
                        </div>
                        <div class="s_select">
                            此问题选择是的人数为<?php echo $data -> q2_num?>人
                        </div>
                    </div>
                </li>
                <li>
                    <div class="s_list_item">
                        <div class="s_title">
                            注册中，供应商协助是否及时？
                        </div>
                        <div class="s_num">
                            <?php if($data -> question_num > 0){echo number_format($data -> q3_num/$data -> question_num*100,2);}else{echo '0';}?>%
                        </div>
                        <div class="s_select">
                            此问题选择是的人数为<?php echo $data -> q3_num?>人
                        </div>
                    </div>
                </li>
                <li>
                    <div class="s_list_item">
                        <div class="s_title">
                            供应商对账人及联系方式是否知晓？
                        </div>
                        <div class="s_num">
                            <?php if($data -> question_num > 0){echo number_format($data -> q4_num/$data -> question_num*100,2);}else{echo '0';}?>%
                        </div>
                        <div class="s_select">
                            此问题选择是的人数为<?php echo $data -> q4_num?>人
                        </div>
                    </div>
                </li>
                <li>
                    <div class="s_list_item">
                        <div class="s_title">
                            对收银台功能是否熟悉了解？
                        </div>
                        <div class="s_num">
                            <?php if($data -> question_num > 0){echo number_format($data -> q7_num/$data -> question_num*100,2);}else{echo '0';}?>%
                        </div>
                        <div class="s_select">
                            此问题选择是的人数为<?php echo $data -> q7_num?>人
                        </div>
                    </div>
                </li>
                <li>
                    <div class="s_list_item">
                        <div class="s_title">
                            对支付宝和微信支付是否使用习惯？
                        </div>
                        <div class="s_num">
                            <?php if($data -> question_num > 0){echo number_format($data -> q9_num/$data -> question_num*100,2);}else{echo '0';}?>%
                        </div>
                        <div class="s_select">
                            此问题选择是的人数为<?php echo $data -> q9_num?>人
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="s_table">
            <div class="s_table_title">
                <h3>问卷详细信息<a href="<?php echo Yii::app() -> createUrl('mCenter/QuestionResult/ExportExcel')?>">导出excel</a></h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>联系人</th>
                        <th>所属公司</th>
                        <th>电话</th>
                        <th>目前，支付宝和微信支付注册流程是否顺利？</th>
                        <th>注册流程是否清晰明确？</th>
                        <th>注册中，供应商协助是否及时？</th>
                        <th>供应商对账人及联系方式是否知晓？</th>
                        <th>对账中，存在哪些问题？</th>
                        <th>对对账流程有哪些建议？</th>
                        <th>对收银台功能是否熟悉了解？</th>
                        <th>对收银台功能是否有其他建议？</th>
                        <th>对支付宝和微信支付是否使用习惯？</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($list as $k => $v){?>
                    <tr>
                        <td><?php if(!empty($v -> contacts)){echo $v -> contacts;}?></td>
                        <td><?php if(!empty($v -> branch_company)){echo $v -> branch_company;}?></td>
                        <td><?php if(!empty($v -> tel)){echo $v -> tel;}?></td>
                        <td><?php if(!empty($v -> question1)){echo $v -> question1 == IS_ANSWER_YES?'是':'否';}?></td>
                        <td><?php if(!empty($v -> question2)){echo $v -> question2 == IS_ANSWER_YES?'是':'否';}?></td>
                        <td><?php if(!empty($v -> question3)){echo $v -> question3 == IS_ANSWER_YES?'是':'否';}?></td>
                        <td><?php if(!empty($v -> question4)){echo $v -> question4 == IS_ANSWER_YES?'是':'否';}?></td>
                        <td><?php if(!empty($v -> question5)){echo $v -> question5;}?></td>
                        <td><?php if(!empty($v -> question6)){echo $v -> question6;}?></td>
                        <td><?php if(!empty($v -> question7)){echo $v -> question7 == IS_ANSWER_YES?'是':'否';}?></td>
                        <td><?php if(!empty($v -> question8)){echo $v -> question8;}?></td>
                        <td><?php if(!empty($v -> question9)){echo $v -> question9 == IS_ANSWER_YES?'是':'否';}?></td>
                    </tr>
                    <?php }?>
                    <tr style="border:none">
                    <td style="border:none;text-align:right" colspan="12">
                        <?php $this -> widget('CLinkPager',array(
                            'pages'=>$page,
                            'header'=>'共&nbsp;<span class="yellow">'.$page -> getItemCount().'</span>&nbsp;条&nbsp;',
                            'prevPageLabel' => '上一页',
                            'nextPageLabel'=>'下一页',
                            'maxButtonCount'=>8
                        ));?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="s_footer"></div>
</body>