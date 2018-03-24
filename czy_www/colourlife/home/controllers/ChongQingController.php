<?php
/**
 * Created by PhpStorm.
 * User: chenql
 * Date: 2016/3/10
 * 重庆互联网产业园区展示
 * Time: 14:10
 */

class ChongQingController extends CController{

    public function actionIndex(){
        $this->renderPartial('index');
    }
}