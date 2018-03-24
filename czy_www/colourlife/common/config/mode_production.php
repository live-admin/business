<?php

/**
 * Production configuration
 * Usage:
 * - Online website
 * - Production DB
 * - Standard production error pages (404, 500, etc.)
 */

return array(

	// Set yiiPath (relative to Environment.php)
	//'yiiPath' => dirname(__FILE__) . '/../../../yii/framework/yii.php',
	//'yiicPath' => dirname(__FILE__) . '/../../../yii/framework/yiic.php',
	//'yiitPath' => dirname(__FILE__) . '/../../../yii/framework/yiit.php',

	// Set YII_DEBUG and YII_TRACE_LEVEL flags
	'yiiDebug' => false,
	'yiiTraceLevel' => 0,

	// Static function Yii::setPathOfAlias()
	'yiiSetPathOfAlias' => array(
		// uncomment the following to define a path alias
		//'local' => 'path/to/local-folder'
	),

	// This is the specific Web application configuration for this mode.
	// Supplied config elements will be merged into the main config array.
	'configWeb' => array(

		// Application components
		'components' => array(

//			'cache' => array( //needed for schema caching
//				'class' => 'system.caching.CFileCache',
//			),

			// Database
			'db' => array(
				'connectionString' => 'mysql:host=localhost;dbname=colourlife',
				'enableParamLogging' => true,
                'slaves' => array(//slave connection config is same as CDbConnection
                    'read1' => array(
                        'connectionString' => 'mysql:host=localhost;dbname=colourlife',
                    )
                ),
            ),

            'cache' => array(
                'class' => 'system.caching.CApcCache',
            ),

		),

		'params' => array(// this is used in contact page
            'kuaiqianInfoActionUrl' => 'https://www.99bill.com/gateway/recvMerchantInfoAction.htm',
            'neypayInfoActionUrl' => 'https://payment.ChinaPay.com/pay/TransGet',
            'oaUpdatePassword' => true,
            'ios_push_deploy_status'=>2,//1=开发，2=产品
        ),

	),

	// This is the Console application configuration. Any writable
	// CConsoleApplication properties can be configured here.
    // Leave array empty if not used.
    // Use value 'inherit' to copy from generated configWeb.
	'configConsole' => array(

		// Application components
		'components' => array(

			// Application Log
			'log' => 'inherit',

		),

	),

);
