<?php

class KuaiQianPayment extends Payment
{
    public $configAttributes = array('account', 'kuaiqianCert', 'selfCert', 'accountReconciliationCert');
    public $account;
    public $kuaiqianCert, $certFile;
    public $selfCert, $pem, $pemFile;
    public $accountReconciliationCert;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('account, accountReconciliationCert', 'safe', 'on' => 'update'),
            array('certFile', 'file', 'safe' => true, 'allowEmpty' => true, 'types' => 'cer', 'on' => 'update'),
            array('pemFile', 'file', 'safe' => true, 'allowEmpty' => true, 'types' => 'pem', 'on' => 'update'),
        ));
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), array(
            'account' => '商户账号',
            'password' => '密码',
            'kuaiqianCert' => '平台公钥',
            'certFile' => '平台公钥文件',
            'selfCert' => '商户公钥',
            'pemFile' => '商户私钥文件',
            'kuaiqianCertFingerprint' => '平台公钥 SHA1 指纹',
            'selfCertFingerprint' => '商户公钥 SHA1 指纹',
            'accountReconciliationCert' => '对帐密钥',

        ));
    }

    protected function initKuaiqianCertFromCertFile($file)
    {
        $this->kuaiqianCert = file_get_contents($file);
    }

    protected function initSelfCertAndKeyFormP12File($file)
    {
        $this->selfCert = file_get_contents($file);
    }

    protected function beforeValidate()
    {
        $this->certFile = CUploadedFile::getInstance($this, 'certFile');
        if (!empty($this->certFile))
            $this->initKuaiqianCertFromCertFile($this->certFile->getTempName());
        $this->pemFile = CUploadedFile::getInstance($this, 'pemFile');
        if (!empty($this->pemFile))
            $this->initSelfCertAndKeyFormP12File($this->pemFile->getTempName());
        return parent::beforeValidate();
    }

    protected function getPublicKey($cert)
    {
        $in = explode("\n", $cert);
        $out = array();
        $cat = false;
        foreach ($in as $line) {
            if ($line == '-----BEGIN CERTIFICATE-----') {
                $cat = true;
            } else if ($line == '-----END CERTIFICATE-----') {
                $cat = false;
            } else if ($cat) {
                $out[] = $line;
            }
        }
        return implode($out);
    }

    protected function getFingerprint($cert)
    {
        if (!empty($cert)) {
            $fingerprint = sha1(base64_decode($this->getPublicKey($cert)));
            return rtrim(chunk_split($fingerprint, 2, '-'), '-');
        }
        return '';
    }

    public function getKuaiqianCertFingerprint()
    {
        return $this->getFingerprint($this->kuaiqianCert);
    }

    public function getSelfCertFingerprint()
    {
        return $this->getFingerprint($this->selfCert);
    }

    public function getSelfPublicKey()
    {
        return $this->getPublicKey($this->selfCert);
    }

    protected function getCertKey($cert)
    {
        return openssl_pkey_get_public($cert);
    }

    //发送参数加密
    public function send_sign($data)
    {
        $pkeyid = $this->selfCert;
        openssl_sign($data, $signMsg, $pkeyid, OPENSSL_ALGO_SHA1);

        $signMsg = base64_encode($signMsg);
        return $signMsg;
		//return 1;
    }

    /**
     * 接收参数解密
     * $signMsg 为返回的密钥
     * $data    接收的数据
     * return  bool
     **/
    public function recieve_sign($signMsg, $data)
    {
        $mac = base64_decode($signMsg);
        openssl_get_publickey($this->kuaiqianCert);
        return openssl_verify($data, $mac, openssl_get_publickey($this->kuaiqianCert));
    }

}
