<?php
return array(
		'service' => 'alipay.wap.create.direct.pay.by.user',
		'partner' => '2088121308326873',
		'seller_id' => '2088121308326873',
		'private_key_path' => dirname ( __FILE__ ) . '/key/rsa_private_key.pem',
		'alipay_public_key_path' => dirname ( __FILE__ ) . '/key/alipay_public_key.pem',
		'sign_type' => strtoupper('RSA'),
		'_input_charset' => strtolower('utf-8'),
		'cacert' => getcwd().'\\cacert.pem',
		'transport' => 'http',
		'payment_type' => '1',
);