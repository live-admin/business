<?php

defined('YII_ENABLE_EXCEPTION_HANDLER') or define('YII_ENABLE_EXCEPTION_HANDLER', false);
defined('YII_ENABLE_ERROR_HANDLER') or define('YII_ENABLE_ERROR_HANDLER', false);
defined('YII_DEBUG') or define('YII_DEBUG', true);
$_SERVER['SCRIPT_NAME'] = '/' . basename(__FILE__);
$_SERVER['SCRIPT_FILENAME'] = __FILE__;

require_once(dirname(__FILE__) . '/../../vendor/yiisoft/yii/framework/yiit.php');
require_once(dirname(__FILE__) . '/TestApplication.php');
//require_once('PHPUnit/Framework/TestCase.php');

// make sure non existing PHPUnit classes do not break with Yii autoloader
Yii::$enableIncludePath = false;
Yii::setPathOfAlias('tests', dirname(__FILE__));
Yii::setPathOfAlias('components', dirname(__FILE__) . '/../components');
Yii::setPathOfAlias('models', dirname(__FILE__) . '/../models');
Yii::import('tests.*');

class TestCase extends PHPUnit_Framework_TestCase
{
}


class ActiveRecordTestCase extends TestCase
{
}

new TestApplication();
