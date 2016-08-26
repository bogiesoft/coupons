<?php
/**
 * Created by PhpStorm.
 * User: cheng
 * Date: 2016/7/19
 * Time: 9:22
 * 客户关怀
 */
class MarketingCustomerCareController extends marketingSController
{
    //会员生日，页面展示                         3-1
    public function actionBirthdayCare()
    {
        $this->render('birthdayCare');
    }

    //节日赠券，页面展示                         3-2
    public function actionHolidayCare()
    {
        $this->render('holidayCare');
    }
    //会员升级赠券，页面展示                      3-3
    public function actionUpgradeCare()
    {
        $this->render('upgradeCare');
    }
}