<?php
include_once(dirname(__FILE__) . "/conf.php");
include_once(dirname(__FILE__) . "/common.php");

//autoload
function __autoload($className)
{
	//找到目录
	$pos = strpos($className, '_');
	if(!$pos) {
		return false;
	}

//	$dir = substr($className, 0, $pos);
//	$dir = strtolower($dir);
//	$classFile = INCLUDE_PATH .	$dir . '/' . $className . '.php';

    $classFile = genFilePath($className);
	if (is_file($classFile))
	{
		require_once($classFile);
		return true;
	}
	return false;
}

function genFilePath($className)
{
    //old-model
    $paths = explode('_', $className, 2);
    $dir = strtolower($paths[0]);
    $classFile = INCLUDE_PATH .	'/'. $dir . '/' . $className . '.php';

    if (! file_exists($classFile)) {
        $paths = explode('_', $className, -1);
        $dirs = array_map(function($item){return strtolower($item);}, $paths);
        $classFile = INCLUDE_PATH . '/'. implode('/', $dirs). '/'. $className. '.php';
    }

    return $classFile;
}

spl_autoload_register('__autoload');

function my_assert_handler($file, $line, $code)
{
	if (defined('DEBUG_MODE') && DEBUG_MODE)
	{
		$info = "<!--Assertion Failed:
			File '$file'<br />
			Line '$line'<br />
			Code '$code'<br />";
		$trace = debug_backtrace();
		foreach($trace as &$item) unset($item['object']);
		$info.= var_export($trace, true) . "\n";

		Tool_Log::addFileLog('assert', $info);
	}
	throw new Exception('common:system error');
}

// Set up the callback
assert_options(ASSERT_CALLBACK, 'my_assert_handler');


if (phpversion() >= '7.0') {
    function mysql_escape_string($val)
    {
        $oDb = Data_DB::getDbInstance();
        return mysqli_escape_string($oDb, $val);
    }
}
