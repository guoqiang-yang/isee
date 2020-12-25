<?php

define('ENV', 'prod');
define('IN_DEV', true);
define('HIDE_USELESS', true);
//define('NO_MEMCACHE', true);

define('WEB_PROTOCOL', 'http://');
define('BASE_HOST', '.shi.hhshou.cn');

//域名
define('PIC_HOST', 'p'. BASE_HOST);
define('WWW_HOST', 'c'. BASE_HOST);

define('H5_HOST', 'm'. BASE_HOST);
define('H5_CSSJS_HOST', 'm'. BASE_HOST);
define('H5_IMG_HOST', 'm'. BASE_HOST);

define('ADMIN_HOST', 'sa'. BASE_HOST);
define('ADMIN_CSSJS_HOST', 'sa'. BASE_HOST);
//define('CSSJS_HOST', 'sa.shi.hhshou.cn');
define('ADMIN_IMG_HOST', 'sa'. BASE_HOST);


//路径
define('ROOT_PATH', '/shi/');
define('CORE_DATA_PATH', '/shi/code/data/');
define('TMP_PATH', '/tmp/shi/');
define('MNP_API_HTDOCS_PATH', '/shou/htdocs_mnp_api');

//config db
define('DB_HOST', 'rm-2ze0ch810d0p30ckc.mysql.rds.aliyuncs.com');
define('DB_USER', 'shi');
define('DB_PASS', 'W^DY2qYX5aTZyi^k');
define('DB_NAME', 'shi');

define('WX_APP_ID', '');
define('WX_APP_SECRET', '');

define('MEMCACHE_HOST', '127.0.0.1');
define('MEMCACHE_PORT', 11211);

