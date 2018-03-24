<?php
/**
 * Created by PhpStorm.
 * User: Roy
 * Date: 2017/2/15
 * Time: 15:44
 * 介绍页面
 */
class IntroductionController extends CController {

    public function actionCaiGuanJia()
    {
        $this->renderPartial('caiGuanJia');
    }

    public function actionCgjCall()
    {
        $this->renderPartial('/introduction/caiGuanJia/call');
    }

}