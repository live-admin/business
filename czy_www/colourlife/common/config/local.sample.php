<?php

/**
 * Local configuration
 * Usage:
 * - Local website
 * - Local DB
 * - Show all details on each error
 * - Gii module enabled
 */

return array(

    // This is the specific Web application configuration for this mode.
    // Supplied config elements will be merged into the main config array.
    'configWeb' => array(
        'components' => array(
            'db' => array(
                'connectionString' => 'mysql:host=localhost;dbname=colourlife',
                'emulatePrepare' => true,
                'username' => 'root',
                'password' => '',
                'enableSlave' => true, //Read write splitting function is swithable.You can specify this value to false to disable it.
                'slaves' => array( //slave connection config is same as CDbConnection
                    'read1' => array(
                        'connectionString' => 'mysql:host=localhost;dbname=colourlife',
                        'username' => 'root',
                        'password' => ''
                    ),
                ),
            ),
//            'chat' => array(
//                'db' => array(
//                    'connectionString' => 'mysql:host=localhost;dbname=openfire',
//                    'username' => 'root',
//                    'password' => ''
//                ),
//            ),
//            'cache' => array(
//                'class' => 'system.caching.CDbCache',
//                'cacheTableName' => 'cache',
//                'connectionID' => 'db',
//            ),
//            'cache' => array(
//                'class' => 'system.caching.CApcCache',
//            ),
//            'cache' => array(
//                'class' => 'system.caching.CFileCache'
//                'cachePath' => dirname(dirname(__FILE__) . '/../../runtime/') . '/cache',,
//            ),
            /*
             * system.base.CModule	系统模块
             * system.caching.CDbCache	缓存
             * system.CModule	模块
             * system.db.ar.CActiveRecord	AR操作
             * system.db.CDbCommand	数据库执行
             * system.db.CDbConnection	数据库连接
             * system.db.CDbTransaction	数据库存储
             * system.web.auth.CDbAuthManager	权限
             * system.web.filters.CFilterChain	过滤器
             */
            // 'log' => array(
            //     'routes' => array(
            //         // Show log messages on FirePHP
            //         'firephp' => array(
            //             'categories' => '*',    // 包含的类别
            //             'except' => '',         // 排除的类别
            //         ),
            //     ),
            // ),
        ),
        'params' => array(// this is used in contact page
        //     'kuaiqianInfoActionUrl' => 'https://www.99bill.com/gateway/recvMerchantInfoAction.htm',
        //     'oaUpdatePassword' => true,
        ),
    ),

    // This is the Console application configuration. Any writable
    // CConsoleApplication properties can be configured here.
    // Leave array empty if not used.
    // Use value 'inherit' to copy from generated configWeb.
    'configConsole' => array(),

);