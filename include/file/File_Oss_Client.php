<?php

include_once (__DIR__ . '/../vendor/oss-php-sdk-2.3.0/autoload.php');

use OSS\OssClient;
use OSS\Core\OssException;

class File_Oss_Client
{
    protected static function getOssClient()
    {
        try {
            $ossClient = new OssClient(
                File_Oss_Config::OSS_ACCESS_ID,
                File_Oss_Config::OSS_ACCESS_KEY,
                File_Oss_Config::OSS_ENDPOINT,
                false);
        } catch (OssException $e) {
            printf(__FUNCTION__ . "creating OssClient instance: FAILED\n");
            printf($e->getMessage() . "\n");
            return null;
        }
        return $ossClient;
    }
}