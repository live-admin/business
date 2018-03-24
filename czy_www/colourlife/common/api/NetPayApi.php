<?php

class NetPayApi
{
    static private $instance;
    private $pay;

    static public function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {
        $this->pay = Payment::model()->findByCode('netpay');
    }

    public function submit($ordId, $transAmt, $transDate, $bgRetUrl, $pageRetUrl, $priv1)
    {
    }

}
