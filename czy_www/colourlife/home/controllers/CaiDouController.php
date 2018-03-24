<?php
/*
 * 彩豆
 */
class CaiDouController extends CController {

    public function actionIndex() {
       $this->renderPartial( "index");
    }
}
