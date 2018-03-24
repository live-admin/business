<?php


/**
 * 微商圈
 * @author
 */
class MicroBusinessService
{

    public function __construct()
    {
        if (defined('YII_DEBUG') && YII_DEBUG == true) {
            $this->apiServer = 'http://mbd-czytest.colourlife.com/';
        } else {
            $this->apiServer = 'http://mbd.colourlife.com/';
        }
    }

    /**
     * 获取首页微服务 url
     * @param string $interface
     * @return string
     */
    protected function getApiUrl($interface = '')
    {
        return sprintf(
            '%s/%s?access_token=%s',
            trim($this->apiServer, ' /'),
            trim($interface, ' /'),
            $this->getAccessToken()
        );
    }

    public function getAccessToken()
    {
        Yii::import('common.components.GetTokenService');
        $service = new GetTokenService();
        return $service->getAccessTokenFromPrivilegeMicroService();
    }

    protected function handleSpecialCharInPostField($postFields = array())
    {
        if (!$postFields) {
            return $postFields;
        }

        $parsedFields = array();
        foreach ($postFields as $key => $postField) {
            // @ 开头的字段会被认为是文件上传
            // 处理方式，@开头的，先添加空格，然后服务器端去空格
            $pos = strpos($postField, '@');
            if ($pos !== false && $pos == 0) {
                $postField = ' ' . $postField;
            }

            $parsedFields[$key] = $postField;
        }

        return $parsedFields;
    }

    /**
     * 接口转发
     * @param string $interface
     * @param array $postData
     * @return mixed
     * @throws CHttpException
     */
    public function dispatchService($interface = '', $postData = array())
    {
        //echo $this->getApiUrl($interface);exit;


        $response = json_decode(
            Yii::app()->curl->post(
                $this->getApiUrl($interface),
                $this->handleSpecialCharInPostField($postData)
            ),
            true
        );

        Yii::log(
            sprintf(
                '调用微商圈接口: %s, 参数: %s, 返回信息: %s.',
                $this->getApiUrl($interface),
                json_encode($postData),
                json_encode($response)
            ),
            CLogger::LEVEL_ERROR,
            'colourlife.core.api.WSQService.parseQueryResponse'
        );

        if (!$response || !isset($response['code']) ) {
            throw new CHttpException(555, '商圈服务请求失败');
        }

        if ($response['code'] != 0) {
            throw new CHttpException(
                555,
                sprintf('商圈服务请求失败：%s[%s]。请重试。', $response['message'], $response['code'])
            );
        }
        return  $response['content'];
    }


}