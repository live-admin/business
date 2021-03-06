<?php

class AboutController extends BaseHelpController
{

    public $modelName = 'About';
    private $_id; //文章ID
    private $_cate_id;

    public function init(){
        throw new CHttpException(404,"页面不存在");
    }

    public function actionIndex($category_id = 0)
    {
        throw new CHttpException(404,"页面不存在");
    	$this->pageTitle="关于我们-彩之云";
        $category = $this->findCategory();
        if (!empty($category_id) && $category_id != 0) {
        	$category_id=intval($category_id);
            $model = $this->essayList($category_id);
        } else {
            if (!empty($category)) {
                $model = $this->essayList($category[0]->id);
            }
        }
        $this->render('index', array(
            'right' => $category,
            'model' => empty($model) ? '' : $model['list'],
            'num' => $this->getNum($category, $category_id),
            'pages' => empty($model) ? null : $model['pager'],
        ));
    }

    public function actionView($id)
    {
    	$this->pageTitle="关于我们-彩之云";
        //$this->_id = intval($id);
        $id=intval($id);
        $this->_id = $id;
        $model = $this->views($id);
        $category = $this->findCategory();
        $this->_cate_id = $model->category_id;
        $this->render('view', array(
            'right' => $category,
            'model' => $model,
            'num' => $this->getNum($category, $model->category_id),
        ));
    }

    //下一篇
    public function nextJob()
    {
        return Essay::model()->find(array('condition' => 'id>:id and category_id=:category_id', 'params' => array(':id' => $this->_id,
            ':category_id' => $this->_cate_id), 'order' => 'id ASC'));
    }

    //上一篇
    public function prevJob()
    {
        return Essay::model()->find(array('condition' => 'id<:id and category_id=:category_id', 'params' => array(':id' => $this->_id,
            ':category_id' => $this->_cate_id), 'order' => 'id DESC'));
    }
}
