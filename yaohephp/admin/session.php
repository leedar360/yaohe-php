<?php
//ini_set('session.save_handler', 'memcache');
//ini_set('session.cookie_domain','.17yaohe.com');
//ini_set('session.save_path','tcp://172.16.100.31:11211');
session_start();
if (!isset($_SESSION['session_time'])) {   
 $_SESSION['session_time'] = time();
}
echo "session_time:".$_SESSION['session_time']."<br />";
echo "now_time:".time()."<br />";
echo "session_id:".session_id()."<br />";
?>