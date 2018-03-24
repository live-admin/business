<?php

class SmallLoansController extends CController
{
    public function actionIndex(){
        $this->pageTitle = "花样年小贷投资产品";
        //$this->getCommunityId();        
        $info = SmallLoans::model()->searchByIdAndType("XIAODAI",0);
        $this->renderPartial("index",array('info' => $info));
    }
    
}
