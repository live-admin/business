<?php

/**
 *
 * 通过http或https获取数据的工具类
 * 注意：
 * 1.使用Crul需要修改服务器中php.ini文件的设置，找到php_curl.dll去掉前面的";"就行了
 * 2.https访问，不验证服务器证书的合法性.
 * @author dw
 *
 */
class HttpClient
{
	/**
     * 远程获取数据，POST模式
     * @param string $url 指定URL完整路径地址
     * @param array $para 请求的数据
     * return 远程输出的数据
     */
    public static function getHttpResponsePOST($url, $para) 
    { 
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
        
//         curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
//         curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
//         curl_setopt($curl, CURLOPT_CAINFO,$cacert_url);//证书地址

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);  // https请求 不验证证书和hosts
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);  // 宽松模式        
       
        curl_setopt($curl, CURLOPT_POST,true); // post传输数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, $para);// post传输数据
        $responseText = curl_exec($curl);
        //var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
        curl_close($curl);
    
        return $responseText;
    }
    
    /**
     * 远程获取数据，PUT模式
     * @param string $url 指定URL完整路径地址
     * @param array $para 请求的数据
     * return 远程输出的数据
     */
    public static function getHttpResponsePUT($url, $para)
    {
        $putData = http_build_query($para);
        
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
        
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT"); //设置请求方式         
        curl_setopt($curl, CURLOPT_POSTFIELDS, $putData);// PUT传输数据
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("X-HTTP-Method-Override: PUT"));//设置HTTP头信息
        
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);  // https请求 不验证证书和hosts
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);  // 宽松模式
        
        $responseText = curl_exec($curl);
        print_r($responseText);die;
        curl_close($curl);
    
        return $responseText;
    }
    
    /**
     * 远程获取数据，GET模式
     * @param $url 指定URL完整路径地址
     * return 远程输出的数据
     */
    public static function getHttpResponseGET($url) {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
        
//         curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
//         curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
//         curl_setopt($curl, CURLOPT_CAINFO, $cacert_url);//证书地址
        
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);  // https请求 不验证证书和hosts
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);  
        
        $responseText = curl_exec($curl);
        //var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
        curl_close($curl);
    
        return $responseText;
    }
}