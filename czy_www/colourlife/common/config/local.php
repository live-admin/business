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
                'connectionString' => 'mysql:host=rm-wz9n60n27875y304y.mysql.rds.aliyuncs.com;dbname=colourlife',
                'emulatePrepare' => true,
                'username' => 'colourlife',
                'password' => 'ali_colourlife#0924',
                'enableSlave' => false, //Read write splitting function is swithable.You can specify this value to false to disable it.
//BEGIN:2016.03.15: Added by LIANG ChangTai for SQL Profiling. 2017.03.06: Turn off.
                //'enableProfiling'=>true,
                //'enableParamLogging'=>true,
                'enableProfiling'=>false,
                'enableParamLogging'=>false,
//END:2016.03.15: Added by LIANG ChangTai for SQL Profiling.
                'slaves' => array( //slave connection config is same as CDbConnection
                    'read1' => array(
                        'connectionString' => 'mysql:host=localhost;dbname=colourlife',
                        'username' => 'root',
                        'password' => ''
                    ),
                ),
            ),
        ),
      	'params' => array(
        	'SsoClass' => "SingleSignOn",    //单点登录的具体实现类。 测试类：SingleSignOnMock， 正式类： SingleSignOn
        ),
    ),

    // This is the Console application configuration. Any writable
    // CConsoleApplication properties can be configured here.
    // Leave array empty if not used.
    // Use value 'inherit' to copy from generated configWeb.
    'configConsole' => array(),

);
