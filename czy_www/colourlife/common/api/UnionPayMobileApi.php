<?php

/**
 * Class UnionPayMobileApi
 *
 * Yii::import('common.api.UnionPayMobileApi');
 * $union = UnionPayMobileApi::getInstance();
 * $xml  = $union->getSubmitXML(....);
 * $union->submit($xml);
 * $xml = $union->getSubmitXML3(...); // 给 app
 *
 * Yii::import('common.api.UnionPayMobileApi');
 * $union = UnionPayMobileApi::getInstance();
 * $return = $union->getNotifyInfo(file_get_contents('php://input')); // 自动回应
 */

class UnionPayMobileApi
{
    static private $instance;
    private $pay;
    private $node; // upomp键值对集合
    private $attr; //

    static public function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {
        $this->pay = Payment::model()->findByCode('unionpay');
    }

    public function getUrl()
    {
        //if (defined('YII_DEBUG'))
        //    return 'http://211.154.166.219/qzjy/MerOrderAction/deal.action';
        return 'http://mobilepay.unionpaysecure.com/qzjy/MerOrderAction/deal.action';
    }

    /**
     * 报备
     *
     * @param $merchantOrderId
     * @param $merchantOrderTime
     * @param $merchantOrderAmt
     * @param $merchantOrderDesc
     * @param $transTimeout
     * @param $backEndUrl
     * @return string
     */
    public function getSubmitXml($merchantOrderId, $merchantOrderTime, $merchantOrderAmt, $merchantOrderDesc, $transTimeout, $backEndUrl)
    {
        $merchantPublicCert = $this->pay->selfPublicKey;
        // echo  $merchantPublicCert;
        $merchantId = $this->pay->account;
        $merchantName = $this->pay->selfName;
        $strForSign = "merchantName=" . $merchantName .
            "&merchantId=" . $merchantId .
            "&merchantOrderId=" . $merchantOrderId .
            "&merchantOrderTime=" . $merchantOrderTime .
            "&merchantOrderAmt=" . $merchantOrderAmt .
            "&merchantOrderDesc=" . $merchantOrderDesc .
            "&transTimeout=" . $transTimeout;
        //echo $strForSign;
        $sign = $this->pay->selfSign($strForSign);

        $this->attr = array(
            'application' => 'SubmitOrder.Req',
            'version' => '1.0.0',
        );
        $this->node = array(
            'merchantName' => $merchantName,
            'merchantId' => $merchantId,
            'merchantOrderId' => $merchantOrderId,
            'merchantOrderTime' => $merchantOrderTime,
            'merchantOrderAmt' => $merchantOrderAmt,
            'merchantOrderDesc' => $merchantOrderDesc,
            'transTimeout' => $transTimeout,
            'backEndUrl' => $backEndUrl,
            'sign' => $sign,
            'merchantPublicCert' => $merchantPublicCert,
        );
        return $this->genXML();
    }

    /**
     * 三要素
     *
     * @param $merchantOrderId
     * @param $merchantOrderTime
     * @return mixed
     */
    public function getSubmitXml3($merchantOrderId, $merchantOrderTime)
    {
        //刚加的公匙
        $merchantPublicCert = $this->pay->selfPublicKey;
        // echo  $merchantPublicCert;
        $merchantId = $this->pay->account;
        $merchantName = $this->pay->selfName;
        $strForSign = 'merchantId=' . $merchantId .
            '&merchantOrderId=' . $merchantOrderId .
            '&merchantOrderTime=' . $merchantOrderTime;
        //echo $strForSign;
        $sign = $this->pay->selfSign($strForSign);

        $this->attr = array(
            'application' => 'SubmitOrder.Req',
            'version' => '1.0.0',
        );
        $this->node = array(
            'merchantId' => $merchantId,
            'merchantOrderId' => $merchantOrderId,
            'merchantOrderTime' => $merchantOrderTime,
            'sign' => $sign,
        );
        return $this->genXML();
    }

    /**
     * 处理支付通知
     *
     * @param $xml
     * @return array
     */
    public function getNotifyInfo($xml)
    {
        //网络获取通知内容
        //$xml = file_get_contents('php://input');
        //file_put_contents('unionpaynotify.txt',$xml);
        //若不想网络环境测试，可打开下行注释，进行单元测试，上面一行会报WARNING,不用理会
        //$xml = "<upomp application=\"TransNotify.Req\"  version=\"1.0.0\" ><transType>01</transType><merchantId>898000000000001</merchantId><merchantOrderId>22201111011010490000000506</merchantOrderId><merchantOrderAmt>1</merchantOrderAmt><settleDate>0420</settleDate><setlAmt>1</setlAmt><setlCurrency>156</setlCurrency><converRate></converRate><cupsQid>201111011016370201232</cupsQid><cupsTraceNum>020123</cupsTraceNum><cupsTraceTime>1101101637</cupsTraceTime><cupsRespCode>00</cupsRespCode><cupsRespDesc>Success!</cupsRespDesc><sign>j22MYWjysAmnRrWyeNFSU2RWQUJJie3K7o/tCEKpEsSgKvdV4aISwngMaBdlaK2GeV/JZBz86TpoD8RYit2pQbmxDdCgw2oXTmlq0lWI8c19JcPDg+hRaLGmNbg7JIjX7/cvOfKn0fkuUUPrIVT4VA8sOmxRyEKhvDkE1Y0wbIo=</sign><respCode></respCode></upomp>";
        // 解析获取到的xml
        $this->node = array();
        $respCode = '9999';
        if ($this->parseXml($xml)) {
            //获取键值对
            $node = $this->node;
            //验签
            $checkIdentifier = 'transType=' . $node['transType'] . '&merchantId=' . $node['merchantId'] .
                '&merchantOrderId=' . $node['merchantOrderId'] . '&merchantOrderAmt=' . $node['merchantOrderAmt'] .
                '&settleDate=' . $node['settleDate'] . '&setlAmt=' . $node['setlAmt'] .
                '&setlCurrency=' . $node['setlCurrency'] . '&converRate=' . $node['converRate'] .
                '&cupsQid=' . $node['cupsQid'] . '&cupsTraceNum=' . $node['cupsTraceNum'] .
                '&cupsTraceTime=' . $node['cupsTraceTime'] . '&cupsRespCode=' . $node['cupsRespCode'] .
                '&cupsRespDesc=' . $node['cupsRespDesc'] . '&respCode=' . $node['respCode'];
            $respCode = $this->pay->checkUnionSign($checkIdentifier, $node['sign']);
            //file_put_contents('unionpaynotify3.txt',$checkIdentifier.'------------'.$nodeArray['sign']);
            $this->attr = array(
                'application' => 'TransNotify.Rsp',
                'version' => '1.0.0',
            );
            $this->node = array(
                'transType' => $node['transType'],
                'merchantId' => $node['merchantId'],
                'merchantOrderId' => $node['merchantOrderId'],
                'merchantOrderAmt' => $node['merchantOrderAmt'],
                'respCode' => $respCode);
            echo $this->genXML();
        } else {
            echo "recieve notify message is xml";
        }
        //file_put_contents('unionpaynotify4.txt',var_export(array_merge($nodeArray, array('respCode'=>$respCode)), true));
        return array_merge($this->node, array('respCode' => $respCode));
    }

    public function submit($xml)
    {
        Yii::log($xml, CLogger::LEVEL_INFO, 'colourlife.code.unionpay.UnionPayMobileApi');
        $curl = Yii::app()->curl;
        $curl->setOption(CURLOPT_HTTPHEADER, array('Content-type:text/plain'));
        try {
            $return = $curl->post($this->getUrl(), urlencode($xml));
        } catch (CException $e) {
            $return = false;
        }
        if ($return !== false)
            return urldecode($return);
        return false;
    }

    protected function parseXml($xml)
    {
        $xml_parser = xml_parser_create();
        if (!xml_parse($xml_parser, $xml, true)) {
            xml_parser_free($xml_parser);
            return false;
        } else {
            // 创建xml对象
            $document = new DOMDocument('1.0', 'utf-8');
            $document->loadXML($xml);
            $document->formatOutput = true;
            // 返回根节点
            $rootElement = $document->documentElement;
            // 根节点子节点集合
            $rootNodeList = $rootElement->childNodes;
            // 获取根节点 及其属性值
            for ($i = 0; $i < $rootElement->attributes->length; $i++) {
                $value = $rootElement->attributes->item($i)->value;
                $key = $rootElement->attributes->item($i)->name;
                // 存放进数组
                $this->attr[$key] = $value;
            }
            for ($i = 0; $i < $rootNodeList->length; $i++) {
                $rootNode = $rootNodeList->item($i);

                if ($rootNode->nodeName == '#text') {
                    continue;
                } else {
                    // 判断子节点是否是叶节点
                    $key = $rootNode->nodeName;
                    $value = $rootNode->nodeValue;
                    // 存放进数组
                    $this->node[$key] = $value;
                }
            }
            return true;
        }
    }

    protected function genXML()
    {
        $document = new DOMDocument('1.0', 'utf-8');
        $document->formatOutput = false;
        // 创建并添加根节点
        $root = $document->createElement('upomp');
        //根节点添加属性
        $id = array_keys($this->attr);
        for ($i = 0; $i < count($id); $i++) {
            $attribute = $document->createAttribute($id[$i]);
            $attribute->appendChild($document->createTextNode($this->attr[$id[$i]]));
            $root->appendChild($attribute);
        }
        $document->appendChild($root);
        //添加子节点
        $id = array_keys($this->node);
        for ($i = 0; $i < count($id); $i++) {
            $element = $document->createElement($id[$i]);
            $element->appendChild($document->createTextNode($this->node[$id[$i]]));
            $root->appendChild($element);
        }
        return $document->saveXML();
    }

}
