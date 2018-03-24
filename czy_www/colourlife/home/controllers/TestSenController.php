<?php

class TestSenController extends CController{
    public function actionGetToken(){
        $tokenService = new GetTokenService();
        $result=$tokenService->getAccessTokenFromPrivilegeMicroService();
        echo $result;
    }
}