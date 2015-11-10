<?php
/*

$admin_config['PAGE_NUM']=20;
$admin_config['TMPL_PARSE_STRING']=array('__SITE__'=>'/');
$admin_config['ALLOWEXTS']=array('jpg', 'gif', 'png', 'jpeg', 'png');
$admin_config['SAVEPATH']=THINK_PATH.'../../Uploads/';
$admin_config['MAXSIZE']='10240000';//最大10兆
*/
return array(
	//数据库配置信息
	'DB_TYPE'   => 'mysql', // 数据库类型
	'DB_HOST'   => '127.0.0.1', // 服务器地址
	'DB_NAME'   => 'yaohe', // 数据库名
	'DB_USER'   => 'root', // 用户名
	'DB_PWD'    => 'root', // 密码
	'DB_PORT'   => 3306, // 端口
	'DB_PREFIX' => 'ht_', // 数据库表前缀 
	'DB_CHARSET'=> 'utf8',
	'PAGE_NUM'	=> 10,
	'VAR_PAGE'  => 'pageNum',
	'TMPL_PARSE_STRING'=>array('__SITE__'=>'/','__PUBLIC__'=>"/Public",'__IMGURL__'=>'http://static.htcheng.com'),
	'ALLOWEXTS'	=> array('jpg', 'gif', 'png', 'jpeg', 'png'),
	'SAVEPATH'	=> '/data1/vhosts/static.htcheng.com/',
	//'SAVEPATH'	=> THINK_PATH.'../../Uploads/',
	'MAXSIZE'	=> '10240000',//最大10兆
	'LOG_RECORD'=>false,
	'URL_MODEL' => 2,
	'MEMCACHE_HOST'=>'127.0.0.1',
	'MEMCACHE_PORT'=>'11211',
	/*'LOAD_EXT_CONFIG' => array(
		'USER' => 'user', //用户配置
		'DB'   => 'database', //数据库配置
	)*/
);
?>