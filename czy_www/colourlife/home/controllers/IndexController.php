<?php

class IndexController extends Controller
{
    public function actionIndex()
    {
    	$this->pageTitle="彩之云";
        $domain = F::getCommunityDomain();
        if (!empty($domain)) {
            $this->redirect(F::getCommunityUrl());
        }
        
        $model = new CustomerLoginForm;
       
        $criteria = new CDbCriteria;
        $criteria->compare('state', Item::STATE_ON);
        $criteria->compare('is_deleted', Item::DELETE_ON);
        $criteria->order = "alpha ASC";
        //$userIp= Yii::app()->request->userHostAddress;
        
        $userIp =Yii::app()->getRequest()->getUserHostAddress();
        //$userIp = "61.28.9.222";
        //$userIp = "61.49.222.211";
        $url = "http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=".$userIp;
      //  $json=  file_get_contents($url);
        $json = Yii::app()->curl->get($url);
        $area = json_decode($json);
        #$country =
        $province=null;
        $city=null;
        if(isset($area->province)){
            $province = Region::model()->find("`name` like '{$area->province}%' and parent_id =0");//省
        }
        if(isset($area->city) && !empty($province)){
            $city = Region::model()->find("(`name` like '{$area->city}%') and parent_id ={$province->id}");//市
        }
        $communityList = Community::model()->findAll($criteria);
        $this->render('index', array(
            'model' => $model,
            'communityList' => $communityList,
        	'province'=>$province,
        	'city'=>$city,
        ));
    }

    public function actionLogin()
    {
        $model = new CustomerLoginForm;

        if (isset($_POST)) {
            $model->setScenario($this->action->id);
            $model->attributes = $_POST;
            if ($model->validate() && $model->login()) {
                echo CJSON::encode(array('ok' => 1));
            } else {
                echo CJSON::encode(array('ok' => $model->getError('username') . $model->getError('password') . $model->getError('verifyCode')));
            }
        }
    }


    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0x894D8D,
                 'foreColor'=>0xFFFFFF, //字体颜色
                'maxLength' => '4', // 最多生成几个字符
                'minLength' => '4', // 最少生成几个字符
                'height' => '40',
                'transparent' => true, //显示为透明，当关闭该选项，才显示背景颜色
            ),
        );
    }

    public function getBreadcrumbs()
    {

    }

    public function getSubMenu()
    {

    }

}