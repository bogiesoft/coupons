<?php

class HomeController extends IndexController {

    /**
     *  玩券首页 iframe页面
     */
    public function actionIndex() {
        $model = Merchant::model() -> findByPk(Yii::app() -> user -> id);
        $this->render('index', array('model' => $model));
    }

    /**
     * iframe页面 左边
     */
    public function actionLeft() {
        $this->render('left');
    }

    /**
     * 首页内容
     */
    public function actionMain() {
        $this->layout = 'newMain';
        $merchant_id = Yii::app()->session['merchant_id'];
        $class = new MerchantClass();
        //商户信息获取
        $merchant_data = $class -> getMerchantIndex($merchant_id);
        //商户数据统计获取
        $statistics_data = $class -> getMerchantStatistics($merchant_id);
        $model = Merchant::model() -> findByPk($merchant_id);
        //商户logo
        $logo_img = Onlineshop::model() -> find('merchant_id = :merchant_id', array(':merchant_id' => $merchant_id)) -> logo_img;
        $this->render('main', array('merchant_data' => $merchant_data, 'statistics_data' => $statistics_data, 'model' => $model, 'logo_img' => $logo_img));
    }


    //财务管理
    public function actionFinance()
    {
        $this->render('finance');
    }


    //门店管理
    public function actionStore()
    {
        $this->render('store');
    }
    //公共管理
    public function actionCommon()
    {
        $this->render('common');
    }

    //支付宝管理
    public function actionAlipay()
    {
        $this->render('alipay');
    }

    //公众号管理
    public function actionWechat()
    {
        $this->render('wechat');
    }

    //统计管理
    public function actionStatistics()
    {
        $this->render('statistics');
    }

    //短信管理
    public function actionDuanxin()
    {
        $this->render('duanxin');
    }

    //系统设置
    public function actionInstall()
    {
        $this->render('install');
    }

    //CRM管理
    public function actionCrm()
    {
        $this->render('crm');
    }

    //渠道管理
    public function actionChannel()
    {
        $this->render('channel');
    }

    //商城管理
    public function actionMall()
    {
        $this->render('mall');
    }

    //应用市场
    public function actionAppmarket()
    {
        $this->render('appmarket');
    }

    /**
     *  保存用户上传的logo图片
     */
    public function actionSaveImg() {
        $file_name = isset($_GET['fileName']) ? $_GET['fileName'] : '';

        $online = Onlineshop::model() -> find('merchant_id = :merchant_id', array(':merchant_id' => Yii::app() -> user -> id));
        if (!empty($online)) {
            $online -> logo_img = $file_name;
            $online -> save();
        }
        echo 'success';
    }

    /**
     *  修改是否显示提醒设置类型
     */
    public function actionSetShow() {
        $type = isset($_POST['type']) ? $_POST['type'] : IS_NO;

        $merchant = Merchant::model() -> findByPk(Yii::app() -> user -> id);
        if (!empty($merchant)) {
            $merchant -> if_show_set = $type;
            $merchant -> save();
        }
        echo 'success';
    }
}