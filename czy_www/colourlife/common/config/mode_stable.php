<?php

/**
 * Development configuration
 * Usage:
 * - Local website
 * - Local DB
 * - Show all details on each error
 * - Gii module enabled
 */

return array(

    // Set yiiPath (relative to Environment.php)
    //'yiiPath' => dirname(__FILE__) . '/../../../yii/framework/yii.php',
    //'yiicPath' => dirname(__FILE__) . '/../../../yii/framework/yiic.php',
    //'yiitPath' => dirname(__FILE__) . '/../../../yii/framework/yiit.php',

    // Set YII_DEBUG and YII_TRACE_LEVEL flags
    'yiiDebug' => false,
    'yiiTraceLevel' => 3,

    // Static function Yii::setPathOfAlias()
    'yiiSetPathOfAlias' => array( // uncomment the following to define a path alias
        //'local' => 'path/to/local-folder'
    ),

    // This is the specific Web application configuration for this mode.
    // Supplied config elements will be merged into the main config array.
    'configWeb' => array(

        // Modules
        'modules' => array(
            'gii' => array(
                'class' => 'system.gii.GiiModule',
                'password' => false,
                'generatorPaths' => array(
                    //'bootstrap.gii',
                    'common.gii',
                ),
            ),
        ),

        // Application components
        'components' => array(

            // Database
            'db' => array(
                'connectionString' => 'mysql:host=localhost;dbname=newcolourlife',
                'username' => 'root',
                'password' => '',
                'enableParamLogging' => true,
                'slaves' => array( //slave connection config is same as CDbConnection
                    'read1' => array(
                        'connectionString' => 'mysql:host=localhost;dbname=newcolourlife',
                        'username' => 'root',
                        'password' => '',
                    ),
                ),
            ),

            // Application Log
            'log' => array(
                'routes' => array(
                    // Show log messages on FirePHP
                    'firephp' => array(
                        'class' => 'comext.firephp.SFirePHPLogRoute',
                        'levels' => 'warning, trace',
                    ),
                    // profile log route
                    array(
                        'class' => 'comext.firephp.SFirePHPProfileLogRoute',
                        'report' => 'summary' // or "callstack"
                    ),
                ),
            ),
            
//            'log'=>array(
//                    'class'=>'CLogRouter',
//                    'routes'=>array(
//                    array(
//                          'class'=>'CFileLogRoute',
//                          'levels'=>'error, warning',
//                         ),
//                        // uncomment the following to show log messages on web pages
//
//                        array(
//                          'class'=>'CWebLogRoute',
//                         ),
//                    // 下面显示页面日志
//
//                           array(
//                                   'class'=>'CWebLogRoute',
//                                   'levels'=>'trace',     //级别为trace
//                                   'categories'=>'system.db.*' //只显示关于数据库信息,包括数据库连接,数据库执行语句
//                                ),
//                         ),
//            ),
            
            
            

        ),

    ),

    // This is the Console application configuration. Any writable
    // CConsoleApplication properties can be configured here.
    // Leave array empty if not used.
    // Use value 'inherit' to copy from generated configWeb.
    'configConsole' => array(),

);
