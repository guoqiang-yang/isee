<?php
/**
 * Created by PhpStorm.
 * User: qihua
 * Date: 17/8/6
 * Time: 22:44
 */


class Data_Sms
{
    public static function sendVerifyCode($mobile, $code)
    {
        $accessKeyId = "LTAICoh1EPcYi0xX";//参考本文档步骤2
        $accessKeySecret = "eJ2NDarYZ4ZxBwF9wPCowyEJJea9HC";//参考本文档步骤2
        $signName = '好回收';
        $templateCode = 'SMS_82100089';
        $templateParam = "{\"code\":\"$code\"}";

        date_default_timezone_set('GMT');
        $para = array(
            'SignatureMethod' => 'HMAC-SHA1',
            'SignatureNonce' => time(),
            'AccessKeyId' => $accessKeyId,
            'SignatureVersion' => '1.0',
            'Timestamp' => date('Y-m-d') . "T" . date('H:i:s') . "Z",
            'Format' => 'JSON',
            'Action' => 'SendSms',
            'Version' => '2017-05-25',
            'RegionId' => 'cn-hangzhou',
            'PhoneNumbers' => $mobile,
            'SignName' => $signName,
            'TemplateParam' => $templateParam,
            'TemplateCode' => $templateCode,
        );
        unset($para['Signature']);

        ksort($para);

        $query = '';
        foreach ($para as $k => $v)
        {
            $k = self::specialUrlEncode($k);
            $v = self::specialUrlEncode($v);

            $query .= $k . '=' . $v . '&';
        }
        $query = substr($query, 0, strlen($query) - 1);
        $queryStr = 'GET&' . self::specialUrlEncode("/") . '&' . self::specialUrlEncode($query);
        $signStr = self::getSignature($accessKeySecret . "&", $queryStr);
        $queryPara = 'Signature=' . self::specialUrlEncode($signStr) . '&' . $query;

        $ret = Tool_Http::get('http://dysmsapi.aliyuncs.com/', $queryPara);
    }

    private static function specialUrlEncode($str)
    {
        $str = urlencode($str);
        $str = str_replace("+", "%20", $str);
        $str = str_replace("*", "%2A", $str);
        $str = str_replace("%7E", "~", $str);

        return $str;
    }

    private static function getSignature($key, $str)
    {
        $signature = "";
        if (function_exists('hash_hmac'))
        {
            $signature = base64_encode(hash_hmac("sha1", $str, $key, true));
        }
        else
        {
            $blocksize = 64;
            $hashfunc = 'sha1';
            if (strlen($key) > $blocksize)
            {
                $key = pack('H*', $hashfunc($key));
            }
            $key = str_pad($key, $blocksize, chr(0x00));
            $ipad = str_repeat(chr(0x36), $blocksize);
            $opad = str_repeat(chr(0x5c), $blocksize);
            $hmac = pack('H*', $hashfunc(($key ^ $opad) . pack('H*', $hashfunc(($key ^ $ipad) . $str))));
            $signature = base64_encode($hmac);
        }

        return $signature;
    }
}