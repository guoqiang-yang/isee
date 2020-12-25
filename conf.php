<?php

define('ENV', 'test');
define('IN_DEV', true);
define('HIDE_USELESS', true);

define('NO_MEMCACHE', true);

define('WEB_PROTOCOL', 'http://');


//域名
define('BASE_HOST', '.hls.cn');
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
define('ROOT_PATH', '/Users/yangguoqiang/oscar/hls/');
define('CORE_DATA_PATH', '/Users/yangguoqiang/oscar/hls/data/');
define('TMP_PATH', '/tmp/shi/');
define('MNP_API_HTDOCS_PATH', '/shou/htdocs_mnp_api');

//config db
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '123123');
define('DB_NAME', 'shi');

define('WX_APP_ID', '');
define('WX_APP_SECRET', '');

define('MEMCACHE_HOST', '127.0.0.1');
define('MEMCACHE_PORT', 11211);
