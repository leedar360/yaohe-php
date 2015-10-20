<?php
ini_set('session.save_handler', 'memcache');
ini_set('session.cookie_domain','.17yaohe.com');
ini_set('session.save_path','tcp://172.16.100.31:11211');
ini_set('display_errors','On');
$lifeTime = 24 * 3600; 
ini_set('session.gc_maxlifetime',24 * 3600);
ini_set('gc_maxlifetime',24 * 3600);
session_set_cookie_params(24 * 3600); 
 //开启调试模式
define('APP_DEBUG', true);
define('APP_PATH','./Apps/');
require '../core/ThinkPHP/ThinkPHP.php';
?>