<?php

class File_Oss_Api extends File_Oss_Client
{
    public static function genOssFileNameForShi($localFileName)
    {
        $pathInfo = pathinfo($localFileName);
        if (empty($pathInfo['filename'])) {
            return false;
        }

        $ossFileName = md5($pathInfo['filename']."-E878CF4DBD60F7C9705F71DCD823E47B"). '.'. $pathInfo['extension'];
        return File_Oss_Config::OSS_SHI_DIR_DF. $ossFileName;
    }

    /**
     * 判断图片是否在OSS存在.
     *
     * @param $ossFileName
     * @return bool
     */
    public static function isFileExist($ossFileName)
    {
        return self::getOssClient()->doesObjectExist(File_Oss_Config::OSS_BUCKET, $ossFileName);
    }

    /**
     * 上传文件到OSS：根据文件目录上传.
     *
     * @param $ossFileName
     * @param $localFilePath
     * @throws
     */
    public static function uploadFileByPath($ossFileName, $localFilePath)
    {
        self::getOssClient()->uploadFile(File_Oss_Config::OSS_BUCKET, $ossFileName, $localFilePath);
    }

    /**
     * 上传文件到OSS：根据文件内容上传.
     *
     * @param $ossFileName
     * @param $fileContent
     */
    public static function uploadFileByContent($ossFileName, $fileContent)
    {
        self::getOssClient()->putObject(File_Oss_Config::OSS_BUCKET, $ossFileName, $fileContent);
    }

    /**
     * 删除指定文件.
     *
     * @param $ossFileName
     */
    public static function deleteFile($ossFileName)
    {
        self::getOssClient()->deleteObject(File_Oss_Config::OSS_BUCKET, $ossFileName);
    }

    public static function getImgUrl($localFileName, $flag = 'shi')
    {
        //$flag 预留区分场景
        $ossFileName = self::genOssFileNameForShi($localFileName);
        return File_Oss_Config::OSS_IMG_HOST. '/'. $ossFileName;
    }

    public static function getResizeImgUrl($ossFileName, $width=100, $height=100)
    {
        return File_Oss_Config::OSS_IMG_HOST. '/'. $ossFileName.
                "?x-oss-process=image/resize,m_fixed,h_$height,w_$width";
    }

}