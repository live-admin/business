<?php

class UserProtocolAppController extends BaseHelpController
{
    protected $modelName = 'Help';
    private $_category_id = 0;

    public function actionIndex()
    {   $category_id = 30;
	    $this->layout = 'userProtocol';
    	$this->pageTitle = "用户服务协议-彩之云";
        $category = $this->findCategory();
        $detail = $this->findEssay($category_id);
        $this->_category_id = $category_id;
        $this->render('index', array(
            'right' => $category,
            'detail' => empty($detail) ? null : $detail[0],
        ));
    }
}