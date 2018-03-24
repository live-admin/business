<?php

class UnionPayment extends Payment
{
    public $configAttributes = array('selfName', 'account', 'unionCert', 'selfCert', 'selfPKey');
    public $selfName;
    public $account;
    public $unionCert, $certFile;
    public $selfCert, $selfPKey, $p12, $p12File;
    public $password;
    private $_unionCertKey, $_selfCertKey, $_selfPrivateKey;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('selfName, account, password', 'safe', 'on' => 'update'),
            array('certFile', 'file', 'safe' => true, 'allowEmpty' => true, 'types' => 'cer', 'on' => 'update'),
            array('p12File', 'file', 'safe' => true, 'allowEmpty' => true, 'types' => 'p12', 'on' => 'update'),
            array('certFile', 'checkCert', 'on' => 'update'),
            array('p12File', 'checkP12', 'on' => 'update'),
        ));
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), array(
            'selfName' => '商户名',
            'account' => '商户账号',
            'unionCert' => '平台公钥',
            'certFile' => '平台公钥文件',
            'selfCert' => '商户公钥',
            'p12File' => '商户私钥文件',
            'password' => '商户私钥密码',
            'unionCertFingerprint' => '平台公钥 SHA1 指纹',
            'selfCertFingerprint' => '商户公钥 SHA1 指纹',
        ));
    }

    protected function initUnionCertFromCertFile($file)
    {
        $pem = file_get_contents($file);
        $pem = chunk_split(base64_encode($pem), 64, "\n");
        $this->unionCert = "-----BEGIN CERTIFICATE-----\n" . $pem . "-----END CERTIFICATE-----\n";
    }

    protected function initSelfCertAndKeyFormP12File($file)
    {
        if (openssl_pkcs12_read(file_get_contents($file), $key, $this->password)) {
            $this->selfCert = $key['cert'];
            $this->selfPKey = $key['pkey'];
        } else {
            $this->selfCert = $this->selfPKey = ''; // 发生错误
        }
    }

    protected function beforeValidate()
    {
        $this->certFile = CUploadedFile::getInstance($this, 'certFile');
        if (!empty($this->certFile))
            $this->initUnionCertFromCertFile($this->certFile->getTempName());
        $this->p12File = CUploadedFile::getInstance($this, 'p12File');
        if (!empty($this->p12File))
            $this->initSelfCertAndKeyFormP12File($this->p12File->getTempName());
        return parent::beforeValidate();
    }

    public function checkCert($attribute, $params)
    {
        if (!$this->hasErrors() && $this->unionCertKey === false) {
            $this->addError($attribute, '平台公钥文件不能为空或格式不正确');
        }
    }

    public function checkP12($attribute, $params)
    {
        if (!$this->hasErrors() && $this->selfCertKey === false) {
            $this->addError($attribute, '商户私钥文件不能为空或格式、私钥密码不正确');
        }
    }

    protected function getPublicKey($cert)
    {
        $cert = explode("\n", $cert);
        foreach ($cert as $k => $line)
            if (@$line{0} == '-')
                unset($cert[$k]);
        return implode($cert);
    }

    protected function getFingerprint($cert)
    {
        if (!empty($cert)) {
            $fingerprint = sha1(base64_decode($this->getPublicKey($cert)));
            return rtrim(chunk_split($fingerprint, 2, '-'), '-');
        }
        return '';
    }

    public function getUnionCertFingerprint()
    {
        return $this->getFingerprint($this->unionCert);
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

    public function getUnionCertKey()
    {
        if (!isset($this->_unionCertKey))
            $this->_unionCertKey = $this->getCertKey($this->unionCert);
        return $this->_unionCertKey;
    }

    public function getSelfCertKey()
    {
        if (!isset($this->_selfCertKey))
            $this->_selfCertKey = $this->getCertKey($this->selfCert);
        return $this->_selfCertKey;
    }

    public function getSelfPrivateKey()
    {
        if (!isset($this->_selfPrivateKey))
            $this->_selfPrivateKey = openssl_pkey_get_private($this->selfPKey);
        return $this->_selfPrivateKey;
    }

    public function selfSign($data)
    {
        $crypted = '';
        $md5 = md5($data, true);
        openssl_private_encrypt($md5, $crypted, $this->selfPrivateKey);
        return base64_encode($crypted);
    }

    protected function checkSign($data, $sign, $cert)
    {
        $crypted = '';
        $md5 = md5($data, true);
        if (openssl_public_decrypt(base64_decode($sign), $crypted, $cert)) {
            //验证签名信息
            if ($crypted == $md5) {
                return "0000";
            } else {
                return "0001";
            }
        } else {
            return "9999";
        }
    }

    public function checkSelfSign($data, $sign)
    {
        return $this->checkSign($data, $sign, $this->selfCertKey);
    }

    public function checkUnionSign($data, $sign)
    {
        return $this->checkSign($data, $sign, $this->unionCertKey);
    }

}
