<?php

class HelpController extends BaseHelpController
{
    protected $modelName = 'Help';
    private $_category_id = 0;

    public function init(){
        throw new CHttpException(404,"页面不存在");
    }

    public function actionIndex($category_id = 0)
    {

    	//$this->pageTitle = "标题-分类-彩之云";
    	$this->pageTitle = "帮助-彩之云";
        $category = $this->findCategory();
        $category_id=intval($category_id);
        $detail = $this->findEssay($category_id);
        $this->_category_id = $category_id;
        $this->render('index', array(
            'right' => $category,
            'detail' => empty($detail) ? null : $detail[0],
        ));
    }

    public function getSequence()
    {
        $result = 1;
        if(!empty($this->Items)){
            foreach ($this->Items as $a) {
                if ($a['id'] == $this->_category_id) {
                    $result = $a['num'];
                    break;
                }
            }
        }
        return $result;
    }


}