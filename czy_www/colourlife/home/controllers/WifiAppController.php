<?php
/*
 * @version wifi接口
 * @copyright josen 2015-03-25
 */
class WifiAppController extends CController {
    
    private $_userId=0;
    protected $modelName = 'WifiApp';
//    public $wifiUrl="http://www.colourlife.com/IP/?user-agent=www.colourlife.com";
    
    public function actionIndex() {
        $userid=$this->checkLogin();
        if(!empty($userid)){
            $create_time=  time();
            $sql="insert into user_wifi_log(user_id,create_time) values($this->_userId,$create_time)";
            $execute=Yii::app()->db->createCommand($sql)->execute();
            if($execute){
                $this->renderPartial("search");
            }
        }
    }
//    public function actionCheck(){
//        $userid=$this->checkLogin();
//        $model = new $this->modelName;
//        if(!empty($userid)){
//                $result=$model->checkOpenWifi($userid);
//                if($result){
//                    $create_time=  time();
//                    $sql="insert into wifi_app_log(user_id,create_time) values($this->_userId,$create_time)";
//                    $execute=Yii::app()->db->createCommand($sql)->execute();
//                    if($execute){       
////                            $url = F::getHomeUrl('/WifiApp/check1');
//                            $url=$this->wifiUrl;
//                            $curl = curl_init();
//                            curl_setopt($curl, CURLOPT_URL, $url);
//                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//                            curl_setopt($curl, CURLOPT_HEADER, 1);
//                            curl_setopt($curl, CURLOPT_USERAGENT,'www.colourlife.com');
//                            $data = curl_exec($curl);
//                            curl_close($curl);
//                            echo $data;
//                    }
//                }
//            }
//            return 0;
//    }
//    public function actionCheck1(){
//        if ($_SERVER['HTTP_USER_AGENT'] == 'www.colourlife.com') echo '@#%colourlife@#%';
//        else echo 'no';
//    }
    private function success(){
       $this->renderPartial("success");
    }
    private function error(){
       // $userid=$this->checkLogin();
        $this->renderPartial( "error");
    }
    
    public function actionCheck(){
        $is_ok = Yii::app()->request->getParam('is_ok');
        if ($is_ok=='SUCCESS'){
            $this->success();
        } else{
            $this->error();
        }
    }
    public function actionLoad(){
        $this->renderPartial( "load");
    }


    private function checkLogin(){
        if (empty($_REQUEST['userid']) && empty($_SESSION['userid'])) {
            exit('<h1>用户信息错误，请重新登录</h1>');
        }else {
            $userId=0;
            if(isset($_REQUEST['userid'])){
                $userId=intval($_REQUEST['userid']);
                $_SESSION['userid']=$userId;
            }else if(isset($_SESSION['userid'])){
                $userId=$_SESSION['userid'];
            }
            $customer=Customer::model()->findByPk($userId);
            if(empty($userId) || empty($customer)){
                exit('<h1>用户信息错误，请重新登录</h1>');
            }
            return $this->_userId = $userId;
        }
    }
}