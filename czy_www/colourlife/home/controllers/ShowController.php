<?php
/**
 * Created by PhpStorm.
 * User: Joy
 * Date: 2016/4/20
 * Time: 18:21
 */
class ShowController extends CController
{
    public function actionProfitNew()
    {
        $this->renderPartial('/v2016/profit/new');
    }

    public function actionProfitJuly()
    {
        $this->renderPartial('/v2016/profit/July');
    }
}