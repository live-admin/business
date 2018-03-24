<?php
/**
 * Created by PhpStorm.
 * User: chenql
 * Date: 2016/3/15
 * Time: 16:23
 * 彩富人生宣传页
 */

class ColourRichLifeController extends CController
{
    public function actionIndex(){
        $this->renderPartial('index');
    }
}