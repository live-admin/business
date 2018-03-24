<?php
/**
 * ��ʱ����������
 * ============================================================================
 * api˵����
 * init(),��ʼ������Ĭ�ϸ�һЩ����ֵ����cmdno,date�ȡ�
 * getGateURL()/setGateURL(),��ȡ/������ڵ�ַ,�������ֵ
 * getKey()/setKey(),��ȡ/������Կ
 * getParameter()/setParameter(),��ȡ/���ò���ֵ
 * getAllParameters(),��ȡ���в���
 * getRequestURL(),��ȡ����������URL
 * doSend(),�ض��򵽲Ƹ�֧ͨ��
 * getDebugInfo(),��ȡdebug��Ϣ
 *
 * ============================================================================
 *
 */

require("RequestHandler.class.php");
class CheckRequestHandler extends RequestHandler
{

    function __construct()
    {
        $this->CheckRequestHandler();
    }

    function CheckRequestHandler()
    {
        //Ĭ��֧����ص�ַ
        $this->setGateURL("http://mch.tenpay.com/cgi-bin/mchdown_real_new.cgi");
    }

    /**
     * @Override
     *��ʼ������Ĭ�ϸ�һЩ����ֵ����cmdno,date�ȡ�
     */
    function init()
    {

    }

    /**
     * @Override
     *����ǩ��
     */
    function createSign()
    {

        $paraKeys = array("spid", "trans_time", "stamp", "cft_signtype", "mchtype");

        //��֯ǩ��
        $signPars = "";
        foreach ($paraKeys as $k) {
            $v = $this->getParameter($k);
            if ($v != "") {
                $signPars .= $k . "=" . $v . "&";
            }
        }

        $signPars .= "key=" . $this->getKey();


        $sign = strtolower(md5($signPars));

        $this->setParameter("sign", $sign);

        //debug��Ϣ
        $this->_setDebugInfo($signPars . " => sign:" . $sign);

    }

}

?>