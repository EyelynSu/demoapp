<?php

require_once __DIR__. "/vendor/autoload.php";


#################################################
define('AOP_CACHE_DIR',__DIR__.'/Cache/');
define('PLUGINS_DIR',__DIR__.'/Plugins/');
define('APPLICATION_NAME','YII-DEMO');
define('APPLICATION_ID','YII-DEMO');
//define("PINPOINT_ENV",'dev');
// change the following paths if necessary
$yii=dirname(__FILE__).'/vendor/yiisoft/yii/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);


function pinpoint_user_class_loader_hook()
{
    Yii::$enableIncludePath = false;
    $loaders = spl_autoload_functions();
    foreach ($loaders as $loader) {
        if(is_array($loader) && is_string($loader[0]) && $loader[0] =='YiiBase'){
            spl_autoload_unregister($loader);
            spl_autoload_register(['Plugins\PinpointYiiClassLoader','autoload'],true,false);
            break;
        }
    }
}

//$app = new yii\web\Application();

require_once($yii);

pinpoint_user_class_loader_hook();

//Yii::createWebApplication($config)->run();
require_once __DIR__. '/vendor/naver/pinpoint-php-aop/auto_pinpointed.php';
$app = Yii::createWebApplication($config);

$app->run();
