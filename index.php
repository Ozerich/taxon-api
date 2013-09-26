<?php
date_default_timezone_set('Europe/Minsk');
set_time_limit(0);

if (strpos($_SERVER['SERVER_ADDR'], '127') === 0) {

    $yii = dirname(__FILE__) . '/framework/yii.php';
    $db_file = dirname(__FILE__) . '/protected/config/db_local.php';

    defined('YII_DEBUG') or define('YII_DEBUG', true);
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);
}
else{
    $db_file = dirname(__FILE__) . '/protected/config/db_prod.php';
    $yii = dirname(__FILE__) . '/framework/yiilite.php';

    defined('YII_DEBUG') or define('YII_DEBUG',false);
}
define("DB_CONFIG",$db_file);

$config=dirname(__FILE__).'/protected/config/main.php';

require_once($yii);
Yii::createWebApplication($config)->run();