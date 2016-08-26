<?php
include_once(dirname(__FILE__) . '/../mainClass.php');

/**
 * 用户等级类
 * 姚小磊
 * 2016/7/15
 */
class UserGradeClass extends mainClass
{
    public $page = null;

    /** 根据商户id获取用户等级
     * @param $merchant_id 商户id
     * @return mixed
     */
    public function getUserGradeList($merchant_id)
    {
        $res_grade = UserGrade::model()->findAll('merchant_id = :merchant_id and flag = :flag', array(
            ':merchant_id' => $merchant_id,
            ':flag' => FLAG_NO
        ));

        return $res_grade;
    }
}