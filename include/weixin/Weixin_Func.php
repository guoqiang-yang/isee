<?php

class Weixin_Func extends Base_Func
{
    //获取openid API
    const GET_OPEN_ID_API = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid={{APPID}}&redirect_uri={{REDIRECT_URI}}&response_type=code&scope=snsapi_base&state=haocai111#wechat_redirect';
    //获取penid之后默认跳转url
    const REDIRECT_URI = 'http://m.hhshou.cn/index.php';
    //根据code获取openid API
    const GET_OPEN_ID_BY_CODE_API = 'https://api.weixin.qq.com/sns/oauth2/access_token';
    //获取access token API
    const ACCESS_TOKEN_API = 'https://api.weixin.qq.com/cgi-bin/token';
    //用户access token在kv表中的key
    const ACCESS_TOKEN_KEY = 'wx_access_token';
    //js ticket在kv表中的key
    const JS_TICKET_KEY = 'wx_js_ticket';
    //获取用户信息API
    const USER_INFO_API = 'https://api.weixin.qq.com/cgi-bin/user/info';
    //发送客服消息API
    const SEND_MESSAGE_API = 'https://api.weixin.qq.com/cgi-bin/message/custom/send';
    //预支付API
    const PRE_PAY_API = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
    //支付成功回调URL
    const PAY_NOTIFY_URL = '';
    const TEST_PAY_NOTIFY_URL = '';
    //账单支付成功回调URL
    const BALANCE_NOTIFY_URL = '';
    //获取js ticket API
    const JS_TICKET_API = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=%s&type=jsapi';
    //发送模板消息API
    const SEND_TEMPLATE_MESSAGE_API = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=%s';
    //模板消息相关
    const PAY_NOTICE = 'pay_notice';
    const ALLOC_NOTICE = 'alloc_notice';
    const BACK_NOTICE = 'back_notice';
    //在微信公众平台-》功能-》模板消息-》我的模板 中查看
    private static $TEMPLATE_IDS = array(
//        'pay_notice' => '6rSRd1Uvf9JjxnbUMv5iBUqvDrB7sUa3FN7wh-6eOFk',

    );

    /**
     *
     * @param $callbackUrl
     *
     * 如果用户未授权，跳转到授权页面
     * （在这里采用的是静默授权的方式，所以用户不会看到授权页面，给用户的感觉是，直接跳转到了$callbackUrl指定的路径）
     * 微信文档链接：http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html
     */
    public static function auth($callbackUrl = '')
    {
        empty($callbackUrl) && $callbackUrl = self::REDIRECT_URI;
        $url = str_replace(array(
                               '{{APPID}}',
                               '{{REDIRECT_URI}}'
                           ), array(
                               WX_APP_ID,
                               urlencode($callbackUrl)
                           ), self::GET_OPEN_ID_API);

        header('Location: ' . $url);
    }

    /**
     * @param $code
     *
     * @return bool
     *
     * 用户授权之后，根据返回的code获取openid
     * 微信文档链接：http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html
     */
    public static function getOpenIdByCode($code)
    {
        $url = self::GET_OPEN_ID_BY_CODE_API;
        $params = array(
            'appid' => WX_APP_ID,
            'secret' => WX_APP_SECRET,
            'code' => $code,
            'grant_type' => 'authorization_code',
        );

        $data = self::_curl($url, $params, 'get');
        if (!empty($data))
        {
            $data = json_decode($data, TRUE);
            if ($data['errcode'])
            {
                return FALSE;
            }

            return $data['openid'];
        }

        return FALSE;
    }

    /**
     * 获取access token
     *
     * 微信文档链接：http://mp.weixin.qq.com/wiki/11/0e4b294685f817b95cbed85ba5e82b8f.html
     */
    public static function getAccessToken()
    {
        $url = self::ACCESS_TOKEN_API;
        $params = array(
            'grant_type' => 'client_credential',
            'appid' => WX_APP_ID,
            'secret' => WX_APP_SECRET,
        );

        $data = self::_curl($url, $params, 'get');
        if (!empty($data))
        {
            $data = json_decode($data, TRUE);
            if ($data['errcode'])
            {
                return array();
            }

            return $data;
        }

        return array();
    }

    /**
     * @param $openId
     * @param $accessToken
     *
     * @return bool|mixed
     *
     * 获取用户信息
     * 微信文档链接：http://mp.weixin.qq.com/wiki/14/bb5031008f1494a59c6f71fa0f319c66.html
     */
    public static function getUserInfo($openId, $accessToken)
    {
        $url = self::USER_INFO_API;
        $params = array(
            'access_token' => $accessToken,
            'openid' => $openId,
            'lang' => 'zh_CN',
        );

        $data = self::_curl($url, $params, 'get');
        if (!empty($data))
        {
            $data = json_decode($data, TRUE);
            if ($data['errcode'])
            {
                return FALSE;
            }

            return $data;
        }

        return FALSE;
    }

    public static function sendTemplateMessage($openid, $accessToken, $type, $params, $redict_url)
    {
        $templateId = self::_getTemplateId($type);
        if (empty($templateId))
        {
            return array();
        }

        $url = self::SEND_TEMPLATE_MESSAGE_API;
        $url = sprintf($url, $accessToken);
        $params = array(
            "touser" => $openid,
            "template_id" => $templateId,
            "url" => $redict_url,
            "data" => $params,
        );

        $data = self::_curl($url, $params, 'post', 'json');
        $data = json_decode($data, TRUE);

        return $data;
    }

    private static function _getTemplateId($type)
    {
        return self::$TEMPLATE_IDS[$type];
    }

    /**
     * @param $openId
     * @param $accessToken
     * @param $content
     *
     * 发送客服文本信息
     * 微信文档链接：http://mp.weixin.qq.com/wiki/7/12a5a320ae96fecdf0e15cb06123de9f.html
     *
     * 注意参数是json格式的
     */
    public static function sendTextMessage($openId, $accessToken, $content)
    {
        $url = self::SEND_MESSAGE_API;
        $url .= '?access_token=' . $accessToken;
        $params = array(
            'touser' => $openId,
            'msgtype' => 'text',
            'text' => array('content' => $content),
        );

        self::_curl($url, $params, 'post', 'json');
    }

    /**
     * @param $openid   用户openid
     * @param $orderId  订单id
     * @param $amount   金额（单位：分）
     *
     * @return Int
     *
     * 预支付
     */
    public static function prePay($openid, $orderId, $amount, $payType)
    {
        $prePayId = 0;
        $error = '';

        $url = self::PRE_PAY_API;
        $params = array(
            'appid' => WX_APP_ID,
            'mch_id' => WX_MCID,
            'nonce_str' => self::getNonceStr(),
            'body' => '好材商城订单',
            'out_trade_no' => $orderId,
            'total_fee' => $amount,
            'spbill_create_ip' => Tool_Ip::getClientIP(),
            'notify_url' => $payType == 'single' ? self::PAY_NOTIFY_URL : self::BALANCE_NOTIFY_URL,
            'trade_type' => 'JSAPI',
            'openid' => $openid,
        );

        $sign = self::sign($params);
        $params['sign'] = $sign;

        $data = self::_curl($url, $params, 'post', 'xml');

        if (!empty($data))
        {
            //返回格式是xml的
            $data = (array)simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);

            //正常
            if ($data['return_code'] == 'SUCCESS')
            {
                if ($data['result_code'] == 'SUCCESS')
                {
                    //校验sign
                    if (self::authentication($data, $data['sign']))
                    {
                        $prePayId = $data['prepay_id'];
                    }
                    else
                    {
                        $error = 'sign error';
                    }
                }
                else
                {
                    $error = 'result code error';
                }
            }
            else
            {
                $error = 'return code error';
            }
        }
        else
        {
            $error = 'curl error';
        }

        //做一个log，记录每次发送的参数和返回的参数
        $desc = "params: " . json_encode($params);
        $desc .= '|response: ' . json_encode($data);
        $desc .= '|error: ' . $error;
        Tool_Log::addFileLog('weixin_prepay/wxprepay_' . date('Ymd'), $desc);

        return $prePayId;
    }

    /**
     * @param $accessToken
     *
     * @return bool|mixed
     *
     * 获取js接口调用凭证
     */
    public static function getJsTicket($accessToken)
    {
        $url = self::JS_TICKET_API;
        $url = sprintf($url, $accessToken);
        $ret = self::_curl($url, 'get');
        if (!$ret || !($retArray = json_decode($ret, TRUE)))
        {
            return FALSE;
        }

        return $retArray;
    }

    /**
     * @param $params
     *
     * @return string
     *
     * 生成签名
     * a.对所有传入参数按照字段名的 ASCII 码从小到大排序(字典序)后,使用 URL 键值对的 格式(即 key1=value1&key2=value2...)拼接成字符串 string1,注意:值为空的参数不签名;
     * b.在 string1 最后拼接上 key=Key(商户支付密钥)得到 stringSignTemp 字符串,对 stringSignTemp 进行 md5 运算,再将得到的字符串所有字符转换为大写,得到 sign 值 signValue。
     */
    public static function sign($params)
    {
        //对所有传入参数按照字段名的 ASCII 码从小到大排序(字典序)
        ksort($params);
        //值为空的参数丌参不 签名
        $params = array_filter($params);
        //使用 URL 键值对的 格式(即 key1=value1&key2=value2...)拼接成字符串
        $paramsStr = self::_getQueryStr($params);
        //在 string1 最后拼接上 key=Key(商户支付密钥)
        $paramsStr .= '&key=' . WX_MKEY;

        //对 stringSignTemp 进行 md5 运算,再将得到的字符串所有字符转换为大写
        return strtoupper(md5($paramsStr));
    }

    /**
     * @param $params
     * @param $sign
     *
     * @return boolean
     *
     * 验证签名
     */
    public static function authentication($params, $sign)
    {
        unset($params['sign']);
        $realSign = self::sign($params);

        return $realSign == $sign;
    }

    /**
     * 随机字符串
     */
    public static function getNonceStr()
    {
        $length = 20;
        $str = '';
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol) - 1;

        for ($i = 0; $i < $length; $i++)
        {
            $str .= $strPol[rand(0, $max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }

        return $str;
    }

    private static function _curl($url, $params, $type = 'post', $format = 'http_param')
    {
        $ch = curl_init();

        if (is_array($params))
        {
            if ($format == 'json')
            {
                //为了防止汉字被解析成unicode编码，5.4以上可以用参数实现
                $params = self::_url_encode($params);
                $params = json_encode($params);
                $params = urldecode($params);
            }
            else if ($format == 'xml')
            {
                $params = self::_getXml($params);
            }
            else
            {
                //http_build_query参数会encode notify_url 里面的 //, 会造成签名错误
                $params = self::_getQueryStr($params);
            }
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        if ($type == 'post')
        {
            curl_setopt($ch, CURLOPT_POST, 1);
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);       //5秒超时

        $ret = curl_exec($ch);

        curl_close($ch);

        return $ret;
    }

    private static function _url_encode($arr)
    {
        foreach ($arr as $k => $v)
        {
            if (is_array($v))
            {
                $arr[$k] = self::_url_encode($v);
            }
            else
            {
                $arr[$k] = urlencode($v);
            }
        }

        return $arr;
    }

    /**
     * @param $params
     *
     * @return string
     *
     * 将数组转成xml，只支持kv形式的数组，不支持二维以上的数组
     * 另外哪些字段需要解析成CDATA需要人工指定
     */
    private static function _getXml($params)
    {
        $str = '<xml>';
        foreach ($params as $k => $v)
        {
            if (in_array($v, array(
                'body',
                'attach',
                'sign'
            )))
            {
                $v = "![CDATA[$v]]";
            }
            $str .= "<$k>" . $v . "</$k>";
        }
        $str .= '</xml>';

        return $str;
    }

    private static function _getQueryStr($params)
    {
        $str = '';
        foreach ($params as $k => $v)
        {
            if ($str == '')
            {
                $str .= "$k=$v";
            }
            else
            {
                $str .= "&$k=$v";
            }
        }

        return $str;
    }

    public static function prePayQrcode($orderId, $amount)
    {
        $code = '';
        $error = '';

        $url = self::PRE_PAY_API;
        $params = array(
            'appid' => WX_APP_ID,
            'mch_id' => WX_MCID,
            'nonce_str' => self::getNonceStr(),
            'body' => '好材商城订单',
            'out_trade_no' => $orderId,
            'total_fee' => $amount,
            'spbill_create_ip' => '120.26.211.129',
            'notify_url' => ENV == 'online' ? self::PAY_NOTIFY_URL : self::TEST_PAY_NOTIFY_URL,
            'trade_type' => 'NATIVE',
        );

        $sign = self::sign($params);
        $params['sign'] = $sign;

        $data = self::_curl($url, $params, 'post', 'xml');

        if (!empty($data))
        {
            //返回格式是xml的
            $data = (array)simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
            //正常
            if ($data['return_code'] == 'SUCCESS')
            {
                if ($data['result_code'] == 'SUCCESS')
                {
                    //校验sign
                    if (self::authentication($data, $data['sign']))
                    {
                        $code = $data['code_url'];
                    }
                    else
                    {
                        $error = 'sign error';
                    }
                }
                else
                {
                    $error = 'result code error';
                }
            }
            else
            {
                $error = 'return code error';
            }
        }
        else
        {
            $error = 'curl error';
        }

        //做一个log，记录每次发送的参数和返回的参数
        $desc = "params: " . json_encode($params);
        $desc .= '|response: ' . json_encode($data);
        $desc .= '|error: ' . $error;
        Tool_Log::addFileLog('weixin_prepay/wxprepay_' . date('Ymd'), $desc);

        return $code;
    }
}