<?php
require_once ( "Channel.class.php" );
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BaiduPush
 *
 * @author wede
 */
class BaiduPush {


    private $apiKey ="1xaY0xs8b84lyLezd6uIwzU8";
    private $secretKey="QgkaT5UN1xtBt9EykDFgUceB8BPxrsXv" ;
    private static $pushType;

    public function BaiduPush(){
        //Yii::app()->config["pushConfig"];
        if(self::$pushType == 0){//业主
            $this->apiKey =  F::getConfig("pushConfig","pushKey");
            $this->secretKey = F::getConfig("pushConfig","secretKey");
        }else{//物业
            $this->apiKey =  F::getConfig("pushConfig","employeeApiKey");
            $this->secretKey = F::getConfig("pushConfig","employeeSectetKey");
        }
    }

    //put your code here
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

    /**
     * 推送移动设备消息
     * @param type $user_id
     * @param String $tag_name
     * @param array $messageArr
     * title
     */
    public function pushMessage($tag_name='') {
        $channel = new Channel($this->apiKey, $this->secretKey);
        $push_type = 2; //推送单播消息
        $optional[Channel::TAG_NAME] = $tag_name;  //如果推送tag消息，需要指定tag_name
        $optional[Channel::DEVICE_TYPE] = 3;
        $optional[Channel::MESSAGE_TYPE] = 1;
        $optional[Channel::DEPLOY_STATUS] = 1;
        $message = '{
			"title": "有新消息",
			"description": "有新消息",
			"notification_basic_style":7,
			"open_type":0,
			"url":"http://www.baidu.com"
 		}';
        //通知类型的内容必须按指定内容发送，示例如下：
        $message_key = "msg_key";
        $ret = $channel->pushMessage($push_type, $message, $message_key, $optional);
        if($ret){
            $return["ios"] = $this->pushMessagesAlert($tag_name);
            $return["android"] = $ret;
            return $return;
        }
        return false;
    }

    /**
     * @param string $tag_name
     * @param array $message
     */
    public function pushMessagesAlert($tag_name=''){
        $channel = new Channel($this->apiKey, $this->secretKey);
        $push_type = 2; //推送单播消息
        $optional[Channel::TAG_NAME] = $tag_name;
        $optional[Channel::DEVICE_TYPE] = 4;
        $optional[Channel::MESSAGE_TYPE] = 1;
        $optional[Channel::DEPLOY_STATUS] = 1;
        //通知类型的内容必须按指定内容发送，示例如下：
        $message = '{
		"aps":{
			"alert":"有新信息",
			"sound":"",
			"badge":0
            }
        }';
        $message_key = "msg_key";
        $ret = $channel->pushMessage($push_type, $message, $message_key, $optional);
        return $ret;
    }

    public function pushAll()
    {
        $channel = new Channel($this->apiKey, $this->secretKey);
        $push_type = 3; //推送全部消息
        $optional[Channel::DEVICE_TYPE] = 3;
        $optional[Channel::MESSAGE_TYPE] = 1;
        $optional[Channel::DEPLOY_STATUS] = 1;
        $message = '{
			"title": "有新消息",
			"description": "有新消息",
			"notification_basic_style":7,
			"open_type":0,
			"url":"http://www.baidu.com"
 		}';
        //通知类型的内容必须按指定内容发送，示例如下：
        $message_key = "msg_key";
        $ret = $channel->pushMessage($push_type, $message, $message_key, $optional);
        if($ret){
            $return["ios"] = $this->pushAllAlert();
            $return["android"] = $ret;
            return $return;
        }
        return false;
    }

    public function pushAllAlert(){
        $channel = new Channel($this->apiKey, $this->secretKey);
        $push_type = 3; //推送单播消息
        $optional[Channel::DEVICE_TYPE] = 4;
        $optional[Channel::MESSAGE_TYPE] = 1;
        $optional[Channel::DEPLOY_STATUS] = 1;
        //通知类型的内容必须按指定内容发送，示例如下：
        $message = '{
		"aps":{
			"alert":"有新信息",
			"sound":"",
			"badge":0
            }
        }';
        $message_key = "msg_key";
        $ret = $channel->pushMessage($push_type, $message, $message_key, $optional);
        return $ret;
    }
}
