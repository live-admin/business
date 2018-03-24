<?php
/**
 * e能源接入金融平台
 * Created by PhpStorm.
 * User: chenql
 * Date: 2016/9/13
 * Time: 15:43
 */
Yii::import('common.services.BaseService');
class PowerFinanceService extends BaseService {
    private $apiServer = '';
    private $privilegeServer = '';
    private $appKey = '';
    private $appSecret = '';
    private $accessToken = '';
    private $bano = '';
    private $pno = '';



    public function __construct()
    {
        if (defined('YII_DEBUG') && YII_DEBUG == true) {
            $this->appKey = 'tAxpKPC82BbYKgiyfwsa';         //鉴权服务key
            $this->appSecret = 'PF8RPVDcrxQkyOaI3dgveSUoyDnezgPP';  //鉴权服务secret
            $this->pno = '10014921fd1e73a54f20831bceb4d13b';     //e能源账户
            $this->privilegeServer = 'http://neotest.kakatool.cn:8098/';   //鉴权服务地址
            $this->bano = '1001e5ddf5176a514f5fa1cbbb8f4990';    //收款商家账户
        } else {
            $this->pno = '1021583d0ffb247c449fb758a1f9d948';
            $this->appKey = 'YklC77EDi36P1rsHTY1T';
            $this->appSecret = 'kGtrQNMi8VvwGhzdODgw7NMyFFFY37iO';
            $this->privilegeServer = 'http://rules.ice.colourlife.com/';
            $this->bano = '102190d4e27298f34037ae7af628ba03'; //收款商家账户
        }
    }

    /**
     * 检查是否存在
     * @param int $customer_id
     * @return bool
     */
    public function checkIfExsit($customer_id = '',$pano = '')
    {
        if (!$customer_id) {
            return FALSE;
        }
        if (empty($pano)){
            $pano = $this->pno;
        }
        $item = EpowerFinanceCustomerRelation::model()->find('customer_id=:customer_id and pano=:pano', array(':customer_id' => $customer_id,':pano'=>$pano));
        return $item == NULL ? FALSE : TRUE;

    }

    /**
     * 根据用户id获取用户金融账号
     * @param int $customer_id
     * @return array|bool|CActiveRecord|mixed|null
     */
    public function getByCustomerid($customer_id = 0,$pano = '')
    {
        if (!$customer_id) {
            return FALSE;
        }

        if (empty($pano)){
            $pano = $this->bano;
        }
        return EpowerFinanceCustomerRelation::model()->find('customer_id=:customer_id and pano=:pano', array(':customer_id' => $customer_id,':pano' => $pano));
    }

    /**
     * 根据手机号码查询
     * @param string $mobile
     * @return array|bool|CActiveRecord|mixed|null
     */
    public function getByMobile($mobile = '',$pano = '')
    {
        if (!$mobile) {
            return FALSE;
        }

        if (empty($pano)){
            $pano = $this->bano;
        }

        return EpowerFinanceCustomerRelation::model()->find('mobile=:mobile and pano=:pano', array(':mobile' => $mobile,':pano' => $pano));
    }

    /**
     * 增加记录及开通金融平台e能源账户
     * @param $data
     * @return bool
     */
    public function addPowerFinanceCustomerRelation($customer_id)
    {
        $cacheKey = 'cmobile:cache:epower:finance:getAccessToken';
        $this->accessToken = Yii::app()->rediscache->get($cacheKey);
        if(empty($this->accessToken)){
            $this->accessToken = $this->getAccessTokenFromPrivilegeMicroService();
        }
        $customerModel = Customer::model()->findByPk($customer_id);
        $mobile = $customerModel['mobile'];
        $name = $customerModel['name'];
        $gender = $customerModel['gender'];
        $pay_password = $customerModel['pay_password'];
        $pano = $this->pno;
        $bano = $this->bano;

        Yii::import('common.api.IceApi');
        $ice = IceApi::getInstance();
        $account = $ice->openClientAccount($this->accessToken, $pano, $bano, $mobile, $name, $gender, $birthday='', $memo='', $cannegative=1);
        if($account &&!empty($account['client']) && !empty($account['account'])){
            $cno = $account['client']['cno'];
            $cano = $account['account']['cano'];
        }else{
            return false;
        }
        $sql_type = "SELECT `id`,`atid` FROM `finance_pay_type` WHERE status=1 AND pano = '{$pano}'";
        $atid_type = Yii::app()->db->createCommand($sql_type)->queryAll();
        if($atid_type){
            $atid = $atid_type[0]['atid'];
            $fanpiaoid = $atid_type[0]['id'];
        }
        $data = array(
            'pano' => $pano,
            'cno' => $cno,
            'cano' => $cano,
            'customer_id' => $customer_id,
            'mobile' => $mobile,
            'name' => $name,
            'pay_password' => $pay_password,
            'atid' => empty($atid)?21:$atid,
            'fanpiaoid' => empty($fanpiaoid)?7:$fanpiaoid,

        );
        $strKey = '';
        $strValue = '';
        if (isset($data['atid']) && isset($data['fanpiaoid'])){
            $strKey = ',atid,fanpiaoid';
            $strValue = ',:atid,:fanpiaoid';
        }
        $sql = ' INSERT INTO epower_finance_customer_relation (pano,cno,cano,customer_id,mobile,`name`,pay_password'.$strKey.') VALUES(:pano,:cno,:cano,:customer_id,:mobile,:name,:pay_password'.$strValue.') ';

        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(":pano", $data['pano'], PDO::PARAM_STR);
        $command->bindParam(":cno", $data['cno'], PDO::PARAM_STR);
        $command->bindParam(":cano", $data['cano'], PDO::PARAM_STR);
        $command->bindParam(":customer_id", $data['customer_id'], PDO::PARAM_INT);
        $command->bindParam(":mobile", $data['mobile'], PDO::PARAM_STR);
        $command->bindParam(":name", $data['name'], PDO::PARAM_STR);
        $command->bindParam(":pay_password", $data['pay_password'], PDO::PARAM_STR);
        if (!empty($strKey) && !empty($strValue)){
            $command->bindParam(":atid", $data['atid'], PDO::PARAM_INT);
            $command->bindParam(":fanpiaoid", $data['fanpiaoid'], PDO::PARAM_INT);
        }
        $command->execute();

        return TRUE;

    }

    /**
     * 获取用户的支付方式
     * @param unknown $pano
     * @param unknown $customer_id
     * @return boolean|Ambigous <multitype:, mixed>
     */
    public function getPayListByPanoAndCustomerID($pano, $customer_id)
    {
        if (empty($pano) || empty($customer_id)) {
            return false;
        }
        $sql = "select fcr.pano,fcr.atid,fcr.cno,fcr.customer_id,fcr.mobile,fcr.name as username,fcr.cano,fpt.atid,fpt.typeid,fpt.name,fpt.memo from epower_finance_customer_relation fcr left join finance_pay_type fpt on fcr.pano = fpt.pano where fpt.status = 1 and fcr.pano = '{$pano}' and fcr.customer_id = {$customer_id}";
        $query = Yii::app()->db->createCommand($sql);
        $result = $query->queryAll();
        if (!empty($result)) {
            return $result[0];
        }
        return false;
    }

    //快速交易
    public function transaction($sn, $customer_id, $amount, $note, $create_time, $record=0){
        //是否已经开通e能源金融平台账号
        if(!$this->checkIfExsit($customer_id)){
            //开通e能源金融平台账户
            $result = $this->addPowerFinanceCustomerRelation($customer_id);
            if(!$result){
                $model = new PowerFinanceRecord();
                $model->sn = $sn;
                $model->customer_id = $customer_id;
                $model->amount = $amount;
                $model->note = $note;
                $model->create_time = time();
                if(!$model->save()){
                    Yii::log('开通e能源金融平台账号失败，用户id'.$customer_id.' 订单号:'.$sn, CLogger::LEVEL_INFO, 'colourlife.core.addPowerFinanceCustomerRelation');
                    return false;
                }
            }
        }

        $type = $this->getPayListByPanoAndCustomerID($this->pno, $customer_id);
        $atid = $type['atid'];
        $orgaccountno = $type['cano'];

        $cacheKey = 'cmobile:cache:epower:finance:getAccessToken';
        $this->accessToken = Yii::app()->rediscache->get($cacheKey);
        if(empty($this->accessToken)){
            $this->accessToken = $this->getAccessTokenFromPrivilegeMicroService();
        }

        Yii::import('common.api.IceApi');
        $ice = IceApi::getInstance();
        $time = empty($create_time)?time():$create_time;
        $transaction_result = $ice->fastTransaction($this->accessToken, $amount, $sn, $note, $atid, $orgaccountno, $atid, $this->bano, $note,$time, $time+360, $callback='');

        if(!$transaction_result){
            if(!$record){
                $model = new PowerFinanceRecord();
                $model->sn = $sn;
                $model->customer_id = $customer_id;
                $model->amount = $amount;
                $model->note = $note;
                $model->create_time = time();
                if(!$model->save()){
                    Yii::log('e能源金融平台转账及保存记录失败，用户id'.$customer_id.' 订单号:'.$sn, CLogger::LEVEL_INFO, 'colourlife.core.PowerFinanceTransactionRecord');
                    return false;
                }
            }else{
                Yii::log('e能源金融平台转账再次失败，用户id'.$customer_id.' 订单号:'.$sn, CLogger::LEVEL_INFO, 'colourlife.core.PowerFinanceTransaction');
                return false;
            }
        }
        return true;
    }

    /**
     * 获取access_token
     * @return mixed
     * @throws CHttpException
     */
    protected function getAccessTokenFromPrivilegeMicroService()
    {
        $response = json_decode(
            HttpClient::getHttpResponsePOST(
                $this->getPrivelegeUrl('app/auth'),
                array()
            ),
            true
        );

        if (!$response || !isset($response['code'])) {
            throw new CHttpException(501, '获取权限失败');
        }

        if ($response['code'] != 0) {
            throw new CHttpException(
                501,
                sprintf(
                    '获取权限失败: %s[%s]',
                    $response['message'],
                    $response['code']
                )
            );
        }

        if (!isset($response['content']['access_token'])
            || !isset($response['content']['expire'])
        ) {
            throw new CHttpException(502, '获取权限失败');
        }
        $cacheKey = 'cmobile:cache:epower:finance:getAccessToken';
        Yii::app()->rediscache->set($cacheKey, $response['content']['access_token'], 120);

        return $response['content']['access_token'];
    }

    /**
     * 获取权限微服务 url
     * @param string $interface
     * @return string
     */
    protected function getPrivelegeUrl($interface = '')
    {
        $ts = time();
        return sprintf(
            '%s/%s?%s',
            trim($this->privilegeServer, ' /'),
            trim($interface, ' /'),
            http_build_query(array(
                'appkey' => $this->appKey,
                'signature' => md5($this->appKey . $ts . $this->appSecret),
                'timestamp' => $ts,
            ))
        );
    }

}