<?php

bcscale(0);

class NetPayment extends Payment
{
    const DES_KEY = 'SCUBEPGW';
    const HASH_PAD = '0001ffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff003021300906052b0e03021a05000414';

    public $configAttributes = array('account', 'publicKey', 'privateKey');
    public $account;
    public $publicKey, $publicKeyFile;
    public $privateKey, $privateKeyFile;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('account', 'safe', 'on' => 'update'),
            array('publicKeyFile, privateKeyFile', 'file', 'safe' => true, 'allowEmpty' => true, 'types' => 'key', 'on' => 'update'),
            array('publicKeyFile', 'checkPublicKey', 'on' => 'update'),
            array('privateKeyFile', 'checkPrivateKey', 'on' => 'update'),
        ));
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), array(
            'account' => '商户账号',
            'publicKey' => '平台公钥',
            'publicKeyFile' => '平台公钥文件',
            'privateKey' => '商户私钥',
            'privateKeyFile' => '商户私钥文件',
        ));
    }

    protected function initPublicKey($file)
    {
        $key = parse_ini_file($file);
        if ($key !== false && array_key_exists('pubkeyS', $key)) {
            //$hex = substr($key['pubkeyS'], 48);
            //$this->publicKey = bin2hex(substr($this->hex2bin($hex), 0, 128));
            $this->publicKey = substr($key['pubkeyS'], 48, 256);
        } else
            $this->publicKey = false;
    }

    protected function initPrivateKey($file)
    {
        $key = parse_ini_file($file);
        if ($key !== false && array_key_exists('prikeyS', $key)) {
            $cipher = mcrypt_module_open(MCRYPT_DES, '', 'cbc', '');
            $this->privateKey = '';
            $bin = $this->hex2bin(substr($key['prikeyS'], 80));
            $iv = str_repeat("\x00", 8);
            $prime1 = substr($bin, 384, 64);
            mcrypt_generic_init($cipher, self::DES_KEY, $iv);
            $enc = mdecrypt_generic($cipher, $prime1); //$enc = mcrypt_cbc(MCRYPT_DES, self::DES_KEY, $prime1, MCRYPT_DECRYPT, $iv);
            mcrypt_generic_deinit($cipher);
            $this->privateKey .= bin2hex($enc); //$p = $this->bin2int($enc);

            $prime2 = substr($bin, 448, 64);
            mcrypt_generic_init($cipher, self::DES_KEY, $iv);
            $enc = mdecrypt_generic($cipher, $prime2); //$enc = mcrypt_cbc(MCRYPT_DES, self::DES_KEY, $prime2, MCRYPT_DECRYPT, $iv);
            mcrypt_generic_deinit($cipher);
            $this->privateKey .= bin2hex($enc); //$q = $this->bin2int($enc);

            $prime_exponent1 = substr($bin, 512, 64);
            mcrypt_generic_init($cipher, self::DES_KEY, $iv);
            $enc = mdecrypt_generic($cipher, $prime_exponent1); //$enc = mcrypt_cbc(MCRYPT_DES, self::DES_KEY, $prime_exponent1, MCRYPT_DECRYPT, $iv);
            mcrypt_generic_deinit($cipher);
            $this->privateKey .= bin2hex($enc); //$dP = $this->bin2int($enc);

            $prime_exponent2 = substr($bin, 576, 64);
            mcrypt_generic_init($cipher, self::DES_KEY, $iv);
            $enc = mdecrypt_generic($cipher, $prime_exponent2); //$enc = mcrypt_cbc(MCRYPT_DES, self::DES_KEY, $prime_exponent2, MCRYPT_DECRYPT, $iv);
            mcrypt_generic_deinit($cipher);
            $this->privateKey .= bin2hex($enc); //$dQ = $this->bin2int($enc);

            $coefficient = substr($bin, 640, 64);
            mcrypt_generic_init($cipher, self::DES_KEY, $iv);
            $enc = mdecrypt_generic($cipher, $coefficient); //$enc = mcrypt_cbc(MCRYPT_DES, self::DES_KEY, $coefficient, MCRYPT_DECRYPT, $iv);
            mcrypt_generic_deinit($cipher);
            $this->privateKey .= bin2hex($enc); //$u = $this->bin2int($enc);
        } else
            $this->privateKey = false;
    }

    protected function beforeValidate()
    {
        $this->publicKeyFile = CUploadedFile::getInstance($this, 'publicKeyFile');
        if (!empty($this->publicKeyFile))
            $this->initPublicKey($this->publicKeyFile->getTempName());
        $this->privateKeyFile = CUploadedFile::getInstance($this, 'privateKeyFile');
        if (!empty($this->privateKeyFile))
            $this->initPrivateKey($this->privateKeyFile->getTempName());
        return parent::beforeValidate();
    }

    public function checkPublicKey($attribute, $params)
    {
        if (!$this->hasErrors() && $this->publicKey === false) {
            $this->addError($attribute, '平台公钥文件不能为空或格式不正确');
        }
    }

    public function checkPrivateKey($attribute, $params)
    {
        if (!$this->hasErrors() && $this->privateKey === false) {
            $this->addError($attribute, '商户私钥文件不能为空或格式不正确');
        }
    }

    protected function padstr($src, $len = 256, $chr = '0', $d = 'L')
    {
        $ret = trim($src);
        $padlen = $len - strlen($ret);
        if ($padlen > 0) {
            $pad = str_repeat($chr, $padlen);
            if (strtoupper($d) == 'L') {
                $ret = $pad . $ret;
            } else {
                $ret = $ret . $pad;
            }
        }
        return $ret;
    }

    protected function bin2int($bindata)
    {
        $hexdata = bin2hex($bindata);
        return $this->bchexdec($hexdata);
    }

    protected function bchexdec($hexdata)
    {
        $ret = '0';
        $len = strlen($hexdata);
        for ($i = 0; $i < $len; $i++) {
            $hex = substr($hexdata, $i, 1);
            $dec = hexdec($hex);
            $exp = $len - $i - 1;
            $pow = bcpow('16', $exp);
            $tmp = bcmul($dec, $pow);
            $ret = bcadd($ret, $tmp);
        }
        return $ret;
    }

    protected function bcdechex($decdata)
    {
        $s = $decdata;
        $ret = '';
        while ($s != '0') {
            $m = bcmod($s, '16');
            $s = bcdiv($s, '16');
            $hex = dechex($m);
            $ret = $hex . $ret;
        }
        return $ret;
    }

    protected function hex2bin($data)
    {
        $len = strlen($data);
        return pack('H' . $len, $data);
    }

    protected function sha1_128($string)
    {
        $hash = sha1($string);
        $sha_bin = $this->hex2bin($hash);
        $sha_pad = $this->hex2bin(self::HASH_PAD);
        return $sha_pad . $sha_bin;
    }

    protected function encrypt($input)
    {
        $p = $this->bchexdec(substr($this->privateKey, 0, 128));
        $q = $this->bchexdec(substr($this->privateKey, 128, 128));
        $dP = $this->bchexdec(substr($this->privateKey, 256, 128));
        $dQ = $this->bchexdec(substr($this->privateKey, 384, 128));
        $u = $this->bchexdec(substr($this->privateKey, 512, 128));
        $c = $this->bin2int($input);
        $cp = bcmod($c, $p);
        $cq = bcmod($c, $q);
        $a = bcpowmod($cp, $dP, $p);
        $b = bcpowmod($cq, $dQ, $q);
        if (bccomp($a, $b) >= 0) {
            $result = bcsub($a, $b);
        } else {
            $result = bcsub($b, $a);
            $result = bcsub($p, $result);
        }
        $result = bcmod($result, $p);
        $result = bcmul($result, $u);
        $result = bcmod($result, $p);
        $result = bcmul($result, $q);
        $result = bcadd($result, $b);
        $ret = $this->bcdechex($result);
        $ret = strtoupper($this->padstr($ret));
        return (strlen($ret) == 256) ? $ret : false;
    }

    protected function decrypt($input)
    {
        $check = $this->bchexdec($input);
        $modulus = $this->bchexdec($this->publicKey);
        $exponent = $this->bchexdec('010001');
        $result = bcpowmod($check, $exponent, $modulus);
        $rb = $this->bcdechex($result);
        return strtoupper($this->padstr($rb));
    }

    public function sign($msg)
    {
        return $this->encrypt($this->sha1_128($msg));
    }

    public function verify($plain, $check)
    {
        if (strlen($check) != 256) {
            return false;
        }
        $hb = $this->sha1_128($plain);
        $hbhex = strtoupper(bin2hex($hb));
        $rbhex = $this->decrypt($check);
        return $hbhex == $rbhex ? true : false;
    }

    function signOrder($merid, $ordno, $amount, $curyid, $transdate, $transtype)
    {
        if (strlen($merid) != 15)
            return false;
        if (strlen($ordno) != 16)
            return false;
        if (strlen($amount) != 12)
            return false;
        if (strlen($curyid) != 3)
            return false;
        if (strlen($transdate) != 8)
            return false;
        if (strlen($transtype) != 4)
            return false;
        $plain = $merid . $ordno . $amount . $curyid . $transdate . $transtype;
        return $this->sign($plain);
    }

    function verifyTransResponse($merid, $ordno, $amount, $curyid, $transdate, $transtype, $ordstatus, $check)
    {
        if (strlen($merid) != 15)
            return false;
        if (strlen($ordno) != 16)
            return false;
        if (strlen($amount) != 12)
            return false;
        if (strlen($curyid) != 3)
            return false;
        if (strlen($transdate) != 8)
            return false;
        if (strlen($transtype) != 4)
            return false;
        if (strlen($ordstatus) != 4)
            return false;
        if (strlen($check) != 256)
            return false;
        $plain = $merid . $ordno . $amount . $curyid . $transdate . $transtype . $ordstatus;
        return $this->verify($plain, $check);
    }

}
