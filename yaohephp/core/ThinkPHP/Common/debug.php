<?php
return  array(
   
    'LOG_RECORD'=>false,  // 进行日志记录

'LOG_RECORD_LEVEL'       =>  array('EMERG','ALERT','CRIT','ERR','WARN','NOTIC','INFO','DEBUG','SQL'),  // 允许记录的日志级别
    'DB_FIELDS_CACHE'=> false, //数据库字段缓存

'SHOW_RUN_TIME'=>true,          // 运行时间显示
    'SHOW_ADV_TIME'=>true,          // 显示详细的运行时间
    'SHOW_DB_TIMES'=>true,          // 显示数据库查询和写入次数
    'SHOW_CACHE_TIMES'=>true,       // 显示缓存操作次数
    'SHOW_USE_MEM'=>true,           // 显示内存开销
    'SHOW_PAGE_TRACE'=>true,        // 显示页面Trace信息 由Trace文件定义和Action操作赋值
    'APP_FILE_CASE'  =>   true, // 是否检查文件的大小写 对Windows平台有效
);
?>