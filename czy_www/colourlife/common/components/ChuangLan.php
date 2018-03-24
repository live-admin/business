<?php

/*
 * @version 创蓝流量接口
 */
class ChuangLan{
    private $key='666888';//唯一key值
    private $noncestr='fedcba';
    private $account='caishenghuo';
    private $url="http://api.liulian253.com/api/send";//获取流量url
    private $urlOther="http://liulian.253.com/api/queryStatus";//查询订单状态url
    private $e_url="http://x.uxuan.info/xsbAdmin/csh/present";//E家政
    /*
     * @version 流量接口POST
     * @param array $liuLiangData(
     *      'account' => '平台账号',(string)
     *      'timestamp' => '时间戳',(string)
     *      'noncestr' => '随机数值',(string)
     *      'mobile' => '待充值的手机号码',(string)
     *      'package' => '流量包大小',(string)
     *      'signature' => '签名摘要',(string)
     *      'key' => '接口密码',(string)
     *      'ext_id' => '商户系统内部的订单号',(string)
     * )
     */
    public function getLiuLiang($liuLiangData){
        if(empty($liuLiangData)){
            return false;
        }
        $list=array(
            'account'=>$this->account,
            'ext_id'=>$liuLiangData['ext_id'],
            'mobile'=>$liuLiangData['mobile'],
            'noncestr'=>$this->noncestr,
            'package'=>$liuLiangData['package'],
            'timestamp'=>time(),
            'key'=>$this->key,
        );
        $str=http_build_query($list);
        $signature =sha1($str);
        $data=array(
            'account'=>$this->account,
            'timestamp'=>time(),
            'noncestr'=>$this->noncestr,
            'mobile'=>$liuLiangData['mobile'],
            'package'=>$liuLiangData['package'],
            'signature'=>$signature,
//            'key'=>$this->key,
            'ext_id'=>$liuLiangData['ext_id'],
        );
        $data=http_build_query($data);
        $returnObject = HttpClient::getHttpResponsePOST($this->url,$data);
        $return = json_decode($returnObject, true);
        return $return;
    }
    /*
     * @version 流量接口GET
     * @param array $liuLiangData(
     *      'account' => '平台账号',(string)
     *      'timestamp' => '时间戳',(string)
     *      'noncestr' => '随机数值',(string)
     *      'mobile' => '待充值的手机号码',(string)
     *      'package' => '流量包大小',(string)
     *      'signature' => '签名摘要',(string)
     *      'key' => '接口密码',(string)
     *      'ext_id' => '商户系统内部的订单号',(string)
     * )
     */
    public function getLiuLiangOther($liuLiangData){
        if(empty($liuLiangData)){
            return false;
        }
        $list=array(
            'account'=>$this->account,
            'ext_id'=>$liuLiangData['ext_id'],
            'mobile'=>$liuLiangData['mobile'],
            'noncestr'=>$this->noncestr,
            'package'=>$liuLiangData['package'],
            'timestamp'=>time(),
            'key'=>$this->key,
        );
        $str=http_build_query($list);
        $signature =sha1($str);
        $data=array(
            'account'=>$this->account,
            'timestamp'=>time(),
            'noncestr'=>$this->noncestr,
            'mobile'=>$liuLiangData['mobile'],
            'package'=>$liuLiangData['package'],
            'signature'=>$signature,
//            'key'=>$this->key,
            'ext_id'=>$liuLiangData['ext_id'],
        );
        $urlStr=http_build_query($data);
        $allUrl=$this->url."?".$urlStr;
        $returnObject = HttpClient::getHttpResponseGET($allUrl);
        $return = json_decode($returnObject, true);
        return $return;
    }
    /*
     * @version 查询订单状态
     * @param array $orderData(
     *      'account' => '平台账号',(string)
     *      'ext_id' => '商户系统内部的订单号',(string)
     *      'signature' => '签名摘要',(string)
     * )
     */
    public function selectOrderStatus($orderData){
        if(empty($orderData)){
            return false;
        }
        $list=array(
            'account'=>$this->account,
            'ext_id'=>$orderData['ext_id'],
            'key'=>$this->key,
        );
        $str=http_build_query($list);
        $signature =sha1($str);
        $data=array(
            'account'=>$this->account,
            'ext_id'=>$orderData['ext_id'],
            'signature'=>$signature,
        );
        $returnObject = HttpClient::getHttpResponsePOST($this->urlOther,$data);
        $return = json_decode($returnObject, true);
        return $return;
    }
    /*
     * @version E家政接口
     * @param array $ejiaData(
     *      'mobile' => '要赠送的手机号',(string)
     *      'couponTypeCode' => '要赠送优惠券类型编码',(string)
     * )
     */
    public function getEJiaZheng($ejiaData){
        if(empty($ejiaData)){
            return false;
        }
        $data=array(
            'mobile'=>$ejiaData['mobile'],
            'couponTypeCode'=>$ejiaData['couponTypeCode'],
        );
        $returnObject = HttpClient::getHttpResponsePOST($this->e_url,$data);
        $return = json_decode($returnObject, true);
        return $return;
    }
}