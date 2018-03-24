<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
abstract class Controller extends CController
{
    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/main';

    private $_communityID;

    //如果已登录则跳转到用户的小区首页
    public function init()
    {
        if (!Yii::app()->user->isGuest) {//已登录
            $userInfo = Customer::model()->enabled()->findByPk(Yii::app()->user->id);
            if (!empty($userInfo)) {
                $domain = !empty($userInfo->community) ? $userInfo->community->domain :"";
                //跳转
                if (!empty($domain))
                    $this->redirect(F::getFrontendUrl($domain));
            }
        }

        //更新CustomerUserAgent Cookie
        CustomerUserAgent::updateUserAgent();
        //CustomerLoginOper::luckyAdd();

        parent::init();
    }


    public function getCommunityId()
    {
        if (!empty($this->_communityID)) {
            return $this->_communityID;
        }
        list($dom) = explode('.', $_SERVER ['HTTP_HOST']);
        $domain = empty($dom) ? '' : $dom;
        if (!empty($domain)) //存在二级域名
        {
            $community = Community::model()->find('domain=:domain', array(':domain' => $domain));
            if (empty($community)) {
                throw new CHttpException(404, '请求的页面不存在。');
            } else {
                $this->_communityID = $community->id;
            }
        } else {
            throw new CHttpException(404, '请求的页面不存在。');
        }
        return $this->_communityID;
    }

    /**
     * AJax Error Message
     * @param $model
     * @param bool $firstError
     * @return string
     */
    public function errorSummary($model, $firstError = false)
    {
        $content = '';
        if (!is_array($model)) {
            $model = array($model);
        }
        foreach ($model as $m) {
            foreach ($m->getErrors() as $errors) {
                foreach ($errors as $error) {
                    if ($error != '') {
                        $content .= "$error\n";
                    }
                    if ($firstError) {
                        break;
                    }
                }
            }
        }
        return $content;
    }
}

