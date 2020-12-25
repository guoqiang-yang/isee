<?php

/**
 * 图片编辑，生成新的图片.
 *
 */

use OSS\OssClient;

class File_Oss_ImgEditer extends File_Oss_Client
{
    /**
     * 图片缩放.
     *
     * @param String $ossFileName
     * @param String $downloadFileName 新图片的存储地址
     * @param array $sizes {width:xxx, height: yyy}
     * @return bool
     */
    public static function resize($ossFileName, $downloadFileName, $sizes)
    {
        if (!empty($sizes['width']) || !empty($sizes['height'])) {
            return false;
        }

        $options = array(
            OssClient::OSS_FILE_DOWNLOAD => $downloadFileName,
            OssClient::OSS_PROCESS => "image/resize,m_fixed,h_{$sizes['height']},w_{'width'}", );

        self::getOssClient()->getObject(File_Oss_Config::OSS_BUCKET, $ossFileName, $options);

        return true;
    }

    /**
     * 图片水印（统一水印模式）.
     * @Doc
     *  https://help.aliyun.com/document_detail/44957.html?spm=5176.10695662.1996646101.searchclickresult.482854dabnWI46
     *
     * @param $ossFileName
     * @param $downloadFileName
     */
    public static function waterMark($ossFileName, $downloadFileName)
    {
        $text = base64_encode('黄老食');
        $process = 'image'.
            "/watermark,text_$text,type_ZmFuZ3poZW5nZmFuZ3Nvbmc,rotate_30,color_808A87,size_20,g_se".
            "/watermark,text_$text,type_ZmFuZ3poZW5nZmFuZ3Nvbmc,rotate_30,color_808A87,size_20,g_center".
            "/watermark,text_$text,type_ZmFuZ3poZW5nZmFuZ3Nvbmc,rotate_30,color_808A87,size_20,g_nw";

        $options = array(
            OssClient::OSS_FILE_DOWNLOAD => $downloadFileName,
            OssClient::OSS_PROCESS => $process);

        self::getOssClient()->getObject(File_Oss_Config::OSS_BUCKET, $ossFileName, $options);
    }
}
