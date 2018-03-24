<?php
/**
 * Created by PhpStorm.
 * User: PHP
 * Date: 2015/9/24
 * Time: 20:13
 */
class YuanquController extends CController
{

    public function actionIndex()
    {
        $this->renderPartial("index");
    }

    /**
     * 热烈庆祝重庆北部新区互联网产业园隆重开园
     * @throws CException
     */
    public function actionOpen()
    {
        $this->renderPartial("open");
    }
}