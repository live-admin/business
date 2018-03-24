<?php
class Des
 {
     private $mykey = "";
     private $iv = "";

     /**
     * 构造，传递二个已经进行base64_encode的mykey与IV
     *
     * @param string $mykey
     * @param string $iv
     */
     function __construct ($mykey, $iv)
     {
         if (empty($mykey) || empty($iv)) {
             echo 'mykey and iv is not valid';
             exit();
         }
         $this->mykey = $mykey;
         $this->iv = $iv;
     }

     /**
     *加密
     * @param <type> $value
     * @return <type>
     */
     public function encrypt ($value)
     {
         $td = mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_CBC, '');
         $iv = base64_decode($this->iv);
         $value = $this->PaddingPKCS7($value);
         $mykey = base64_decode($this->mykey);
         mcrypt_generic_init($td, $mykey, $iv);
         $ret = base64_encode(mcrypt_generic($td, $value));
         mcrypt_generic_deinit($td);
         mcrypt_module_close($td);
         return $ret;
     }

     /**
     *解密
     * @param <type> $value
     * @return <type>
     */
     public function decrypt ($value)
     {
         $td = mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_CBC, '');
         $iv = base64_decode($this->iv);
         $mykey = base64_decode($this->mykey);
         mcrypt_generic_init($td, $mykey, $iv);
         $ret = trim(mdecrypt_generic($td, base64_decode($value)));
         //$ret = mdecrypt_generic($td, base64_decode($value));
         $ret = $this->UnPaddingPKCS7($ret);
         mcrypt_generic_deinit($td);
         mcrypt_module_close($td);
         return $ret;
     }

     private function PaddingPKCS7 ($data)
     {
         $block_size = mcrypt_get_block_size('tripledes', 'cbc');
         $padding_char = $block_size - (strlen($data) % $block_size);
         $data .= str_repeat(chr($padding_char), $padding_char);
         return $data;
     }

     private function UnPaddingPKCS7($text)
     {
         $pad = ord($text{strlen($text) - 1});
         if ($pad > strlen($text)) {
             return false;
         }
         if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) {
             return false;
         }
         return substr($text, 0, - 1 * $pad);
		 //return $text;
     }
 }
