<?php
class CrabWebController extends CController {

    public function actionIndex(){
        $isGuest=Yii::app ()->user->isGuest;        
        if($isGuest){
            $this->redirect ( Yii::app ()->user->loginUrl );
        }
        $this->renderPartial("indexWeb");
    }

}