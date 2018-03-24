<?php
/**
 * Created by PhpStorm.
 * User: PHP
 * Date: 2015/9/24
 * Time: 20:13
 */
class MessageController extends CController
{

    public function actionWait()
    {
        $this->renderPartial("wait");
    }

}