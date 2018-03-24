<?php

/**
 * Backend Main configuration.
 * All properties can be overridden in mode_<mode>.php files
 */

return array(

    // Static function Yii::setPathOfAlias()
    'yiiSetPathOfAlias' => array(
        'bootstrap' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '../../common/extensions/bootstrap',
    ),

    // This is the main Web application configuration. Any writable
    // CWebApplication properties can be configured here.
    'configWeb' => array(
        'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
        'name' => '彩之云',

        // Autoloading model and component classes
        'import' => array(
            'application.models.*',
            'application.components.*',
            'application.components.helpers.*',
        ),

        'preload' => array(
            'session',
        ),

        // Application components
        'components' => array(
            'request' => array(
                'hostInfo' => 'http://' . HOME_DOMAIN,
            ),

            'bootstrap' => array(
                'class' => 'bootstrap.components.Bootstrap',
                'responsiveCss' => true,
                'fontAwesomeCss' => true,
            ),
            'session' => array(
                'class' => 'system.web.CDbHttpSession', //create table yiisession
                'connectionID' => 'db', //add by insun
                'sessionTableName' => 'customer_session',
                'sessionName'=>'WebsiteSession',
                'cookieMode' =>'allow',
                'cookieParams'=>array(
                    'path'=>'/',
                    'domain'=>(strrpos(BASE_DOMAIN , ':')>0)?substr( BASE_DOMAIN , 0,strrpos(BASE_DOMAIN , ':')):BASE_DOMAIN,//去掉端口
                    'lifetime' => 0//设置为浏览器生命时间
                ),
                'timeout' => 3600,
            ),

            'statePersister'=>array( //指定cookie加密的状态文件
                'class'=>'CStatePersister',//指定类
                'stateFile'=>dirname(__FILE__) . DIRECTORY_SEPARATOR . '../runtime/state.bin',//配置通用状态文件路径，注意，如果你的站点是分布式的，你必须把该文件复制一份到不同服务器上，否则无法跨域。因为里面有个通用密钥，密钥不同则无法验证身份。
            ),

            'user' => array(
                // 'class'=>'CWebUser',//你可以自定义你的Cwebuser
                'identityCookie'=>array('domain' =>(strrpos(BASE_DOMAIN , ':')>0)?substr( BASE_DOMAIN , 0,strrpos(BASE_DOMAIN , ':')):BASE_DOMAIN,'path' => '/'),//配置用户cookie作用域
                //enable cookie-based authentication
                'allowAutoLogin'=>true,//允许同步登录
                'stateKeyPrefix'=>'website',//你的前缀，必须指定为一样的
                'loginUrl'=>'http://'.PASSPORT_DOMAIN.'/site/login',
                'returnUrl'=>'http://' . CUSTOMERBACKEND_DOMAIN.'/',
            ),
            'authManager' => array(
                'class' => 'CDbAuthManager',
                //'class' => 'common.extensions.ECachedDbAuthManager',
                //'cacheID' => 'cache',
                'connectionID' => 'db',
                'defaultRoles' => array('Authenticated', 'Guest'),
                'itemTable' => 'authitem',
                'itemChildTable' => 'authitemchild',
                'assignmentTable' => 'authassignment',
            ),
            
            'urlManager' => array(
                'urlFormat' => 'path',
                'showScriptName' => false,
                //'useStrictParsing' => true,
                'rules' => array(
                    '' => 'index/index',
                    '<_m:(gii)>' => '<_m>/default/index',
                    '<_m:(gii)>/<something:.+>' => '<_m>/<something>',
                    '<_c:\w+>' => '<_c>/index',
                    '<_c:\w+>/<id:\d+>' => '<_c>/view',
                    '<_c:\w+>/<_a:\w+\d*>/<id:.+>' => '<_c>/<_a>',
                    '<_c:\w+>/<_a:\w+>' => '<_c>/<_a>',
                ),
            ),
            'cache' => array(
            		'class' => 'system.caching.CFileCache',
            		//'cachePath' => dirname(dirname(__FILE__) . '/../runtime/') . '/cache',
            ),
            
        ),

        // application-level parameters that can be accessed
        // using Yii::app()->params['paramName']
        'params' => array(
            // this is used in contact page
            'batchCount' => 10,
            'pageSize' => 10,
        ),

    ),

    // This is the Console application configuration. Any writable
    // CConsoleApplication properties can be configured here.
    // Leave array empty if not used.
    // Use value 'inherit' to copy from generated configWeb.
    'configConsole' => array(),

);
