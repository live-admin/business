<?php
/*
 * @version 极光封装类
 * @copyright (c) 2015-03-31, Josen
 */
//require_once 'vendor/autoload.php';
use JPush\Model as M;
use JPush\JPushClient;
use JPush\JPushLog;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use JPush\Exception\APIConnectionException;
use JPush\Exception\APIRequestException;

class JgPush{
    private $app_key='85850e81bcaba45af4f4b6a6';
    private $master_secret = '9815e681d14ea52c73db0e49'; 
    private static $pushType;
    private static $_instance;
    public static function getInstance($pushType = 0)
    {
        
        self::$pushType = $pushType;
        if(! (self::$_instance instanceof self) )
        {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
//    public function getObj(){
//        JPushLog::setLogHandlers(array(new StreamHandler('jpush.log', Logger::DEBUG)));
//        $client = new JPushClient($this->app_key, $this->master_secret);
//        return $client;
//    }
    /*
     * @version 通过别名(手机号码)进行推送
     */
    public function pushMessageByAlias($tag_name='',$title='',$content='') {
        $client = new JPushClient($this->app_key, $this->master_secret);
//        $br = '<br/>';
        try{
             $result = $client->push()
               ->setPlatform(JPush\Model\Platform('android','ios'))
               ->setAudience(JPush\Model\Audience(JPush\Model\alias($tag_name)))
               ->setNotification(JPush\Model\notification($content, JPush\Model\android($content,$title), JPush\Model\ios($content,'default')))
               ->setOptions(JPush\Model\options(123456, 86400, null, TRUE, 0))
               ->send();
           
            if(!empty($result->msg_id) && !empty($result->sendno)){
               return true;
            }else{
                return false;
            }
         }
         catch (APIRequestException $e) {
            // throw new CHttpException("400","极光");
//            echo 'Http Code : ' . $e->httpCode . $br;
//            echo 'code : ' . $e->code . $br;
//            echo 'Error Message : ' . $e->message . $br;
            return false;
        } catch (APIConnectionException $e) {
           // throw new CHttpException("400","1参数错误");
//            echo 'Push Fail: ' . $br;
//            echo 'Error Message: ' . $e->getMessage() . $br;
            return false;
        }
    }
    /*
     * @version 通过标签(选择小区)进行发送
     */
    public function pushMessageByTag($tag_name='',$title='',$content=''){
        $client = new JPushClient($this->app_key, $this->master_secret);
//        $br = '<br/>';
        try{
            $arr=array_chunk($tag_name, 20);
            foreach ($arr as $k=>$v){
                $result = $client->push()
               ->setPlatform(JPush\Model\Platform('android','ios'))
               ->setAudience(JPush\Model\Audience(JPush\Model\Tag($v)))
//               ->setNotification(JPush\Model\notification(JPush\Model\android($content, $title)))
               ->setNotification(JPush\Model\notification($content, JPush\Model\android($content,$title), JPush\Model\ios($content,'default')))
               ->setOptions(JPush\Model\options(123456, 86400, null, TRUE, 0))
               ->send();
            }
            if(!empty($result->msg_id) && !empty($result->sendno)){
               return true;
            }else{
                return false;
            }
         }
         catch (APIRequestException $e) {
//            echo 'Http Code : ' . $e->httpCode . $br;
//            echo 'code : ' . $e->code . $br;
//            echo 'Error Message : ' . $e->message . $br;
            return false;
        } catch (APIConnectionException $e) {
//            echo 'Push Fail: ' . $br;
//            echo 'Error Message: ' . $e->getMessage() . $br;
            return false;
        }
    }
    public function pushMessageByAll($title='',$content=''){
        
        $client = new JPushClient($this->app_key, $this->master_secret);
//        $br = '<br/>';
        try{
            $result = $client->push()
               ->setPlatform(JPush\Model\Platform('android','ios'))
               ->setAudience(JPush\Model\all)
//               ->setNotification(JPush\Model\notification(JPush\Model\android($content, $title)))
                ->setNotification(JPush\Model\notification($content, JPush\Model\android($content,$title), JPush\Model\ios($content,'default')))
               ->setOptions(JPush\Model\options(123456, 86400, null, TRUE, 0))
               ->send();
            if(!empty($result->msg_id) && !empty($result->sendno)){
               return true;
            }else{
                return false;
            }
         }
         catch (APIRequestException $e) {
//            echo 'Http Code : ' . $e->httpCode . $br;
//            echo 'code : ' . $e->code . $br;
//            echo 'Error Message : ' . $e->message . $br;
            return false;
        } catch (APIConnectionException $e) {
//            echo 'Push Fail: ' . $br;
//            echo 'Error Message: ' . $e->getMessage() . $br;
            return false;
        }
    }
    /*
     * @version 通过别名(手机号码)进行推送
     * @return 记录日志信息
     */
    public function pushMessageByAlias2($tag_name='',$title='',$content='') {
        $client = new JPushClient($this->app_key, $this->master_secret);
//        $br = '<br/>';
        try{
             $result = $client->push()
               ->setPlatform(JPush\Model\Platform('android','ios'))
               ->setAudience(JPush\Model\Audience(JPush\Model\alias($tag_name)))
               ->setNotification(JPush\Model\notification($content, JPush\Model\android($content,$title), JPush\Model\ios($content,'default')))
               ->setOptions(JPush\Model\options(123456, 86400, null, TRUE, 0))
               ->send();
           
            if(!empty($result->msg_id) && !empty($result->sendno)){
               Yii::log($tag_name."发送推送消息成功",CLogger::LEVEL_INFO,'colourlife.core.JdSendOrderCommand');
            }else{
                Yii::log($tag_name."发送推送消息失败",CLogger::LEVEL_INFO,'colourlife.core.JdSendOrderCommand');
            }
         }
         catch (APIRequestException $e) {
            // throw new CHttpException("400","极光");
//            echo 'Http Code : ' . $e->httpCode . $br;
//            echo 'code : ' . $e->code . $br;
//            echo 'Error Message : ' . $e->message . $br;
            Yii::log($tag_name."发送推送消息失败",CLogger::LEVEL_INFO,'colourlife.core.JdSendOrderCommand');
        } catch (APIConnectionException $e) {
           // throw new CHttpException("400","1参数错误");
//            echo 'Push Fail: ' . $br;
//            echo 'Error Message: ' . $e->getMessage() . $br;
            Yii::log($tag_name."发送推送消息失败",CLogger::LEVEL_INFO,'colourlife.core.JdSendOrderCommand');
        }
    }
    
    
}

