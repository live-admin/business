<?php

/**
 * Staging configuration
 * Usage:
 * - Online website
 * - Production DB
 * - All details on error
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
    'yiiSetPathOfAlias' => array( // uncomment the following to define a path alias
        //'local' => 'path/to/local-folder'
    ),

    // This is the specific Web application configuration for this mode.
    // Supplied config elements will be merged into the main config array.
    'configWeb' => array(

        // Application components
        'components' => array(

            // Database
            'db' => array(
                'connectionString' => 'mysql:host=192.168.7.203;dbname=trunk',
                'enableParamLogging' => true,
                'slaves' => array( //slave connection config is same as CDbConnection
                    'read1' => array(
                        'connectionString' => 'mysql:host=192.168.7.201;dbname=trunk',
                    ),
                ),
            ),

            'cache' => array(
                'class' => 'system.caching.CApcCache',
            ),

        ),

		'params' => array(// this is used in contact page
        //     'kuaiqianInfoActionUrl' => 'https://www.99bill.com/gateway/recvMerchantInfoAction.htm',
        //    'oaUpdatePassword' => true,
        ),

    ),

    // This is the Console application configuration. Any writable
    // CConsoleApplication properties can be configured here.
    // Leave array empty if not used.
    // Use value 'inherit' to copy from generated configWeb.
    'configConsole' => array(),

);