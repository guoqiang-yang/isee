<?php

define('ENV', 'test');
define('IN_DEV', true);
define('HIDE_USELESS', true);

define('NO_MEMCACHE', true);

define('WEB_PROTOCOL', 'http://');


//域名
define('BASE_HOST', '.ynn00.com');
define('PIC_HOST', 'p'. BASE_HOST);
define('WWW_HOST', 'www'. BASE_HOST);

define('ADMIN_HOST', 'sa'. BASE_HOST);
define('ADMIN_CSSJS_HOST', 'sa'. BASE_HOST);
define('ADMIN_IMG_HOST', 'sa'. BASE_HOST);


//路径
define('ROOT_PATH', '/opt/www/isee');
define('CORE_DATA_PATH', ROOT_PATH. '/data');
define('TMP_PATH', '/tmp/isee/');

//config db
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '123456');
define('DB_NAME', 'isee');

define('WX_APP_ID', '');
define('WX_APP_SECRET', '');

define('MEMCACHE_HOST', '127.0.0.1');
define('MEMCACHE_PORT', 11211);
