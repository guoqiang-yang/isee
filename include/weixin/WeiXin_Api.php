<?php

class WeiXin_Api extends Base_Api
{
    public static function get($openid)
    {
        $wu = new Weixin_User();

        return $wu->getWxCustomer($openid);
    }

    public static function getByUid($uid)
    {
        $wu = new Weixin_User();

        return $wu->getByUid($uid);
    }

    public static function getByCids($cids)
    {
        $cids = array_filter(array_unique($cids));
        if (empty($cids))
        {
            return array();
        }

        $wu = new Weixin_User();

        return $wu->getByCids($cids);
    }

    public static function isWx()
    {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === FALSE && strpos($_SERVER['HTTP_USER_AGENT'], 'Windows Phone') === FALSE)
        {
            return FALSE;
        }

        return TRUE;
    }

    public static function getOpenIdByCid($cid)
    {
        $wu = new Weixin_User();

        $openid = $wu->getOpenIdByCid($cid);

        return $openid;
    }

    public static function getOpenIdByUid($uid)
    {
        $wu = new Weixin_User();

        return $wu->getOpenIdByUid($uid);
    }

    public static function getCidByOpenid($openid)
    {
        $wu = new Weixin_User();
        $cid = $wu->getCidByOpenid($openid);

        return $cid;
    }

    public static function saveOpenId($cid, $openid)
    {
        $wu = new Weixin_User();
        $wu->addWxCustomer($cid, $openid);
    }

    public static function auth($callbackUrl = '')
    {
        return Weixin_Func::auth($callbackUrl);
    }

    public static function getOpenIdByCode($code)
    {
        return Weixin_Func::getOpenIdByCode($code);
    }

    public static function sendTextMessage($openId, $accessToken, $content)
    {
        Weixin_Func::sendTextMessage($openId, $accessToken, $content);
    }

    public static function getAccessToken()
    {
        $kvdb = new Data_Kvdb();
        $accessTokenInfo = $kvdb->get(Weixin_Func::ACCESS_TOKEN_KEY);
        if (empty($accessTokenInfo) || date('Y-m-d H:i:s') >= $accessTokenInfo['expire_at'])
        {
            $tokenInfo = Weixin_Func::getAccessToken();
            if (!empty($tokenInfo))
            {
                $expireAt = date('Y-m-d H:i:s', time() + $tokenInfo['expires_in'] - 300);
                $kvdb->set(Weixin_Func::ACCESS_TOKEN_KEY, $tokenInfo['access_token'], $expireAt);
            }

            return $tokenInfo['access_token'];
        }

        return $accessTokenInfo['value'];
    }

    public static function getUserInfo($uid, $openId, $accessToken)
    {
        $weixinUser = new Weixin_User();
        $userInfo = $weixinUser->get($uid, $openId);
        $wxUserInfo = Weixin_Func::getUserInfo($openId, $accessToken);
        $date = date('Y-m-d H:i:s');

        if (empty($userInfo))
        {
            $wxUserInfo['created_at'] = $date;
            $wxUserInfo['last_login_at'] = $date;
            $wxUserInfo['login_times'] = 1;
            $wxUserInfo['uid'] = $uid;
            $weixinUser->add($openId, $wxUserInfo);

            return $wxUserInfo;
        }
        else
        {
            $wxUserInfo['last_login_at'] = $date;
            $change = array('login_times' => 1);

            $weixinUser->update($openId, $wxUserInfo, $change);

            $userInfo['login_times'] += 1;
            $userInfo['last_login_at'] = $date;

            return $userInfo;
        }
    }

    public function getJsTicket()
    {
        $kvdb = new Data_Kvdb();
        $jsTicket = $kvdb->get(Weixin_Func::JS_TICKET_KEY);
        if (empty($jsTicket) || time() >= $jsTicket['expire_at'])
        {
            $accessToken = self::getAccessToken();
            $jsTicket = Weixin_Func::getJsTicket($accessToken);
            if (!empty($jsTicket))
            {
                $expireAt = date('Y-m-d H:i:s', time() + $jsTicket['expires_in'] - 300);
                $kvdb->set(Weixin_Func::JS_TICKET_KEY, $jsTicket['ticket'], $expireAt);
            }

            return $jsTicket['ticket'];
        }

        return $jsTicket['value'];
    }

    public static function authentication($params, $sign)
    {
        return Weixin_Func::authentication($params, $sign);
    }

    public static function getPayPackage($openid, $orderid, $amount, $payType = 'single', $useBalance = 0)
    {
        //订单金额会变，但是订单金额一变化，微信就认为是一个新订单，新订单是不能使用老订单的订单号的，
        //所以用订单号-金额组一个新的订单号，金额变化的时候，订单号也会变化
        //如果一个账号在A手机上点击过了detail页面，也就是发过了preopay请求，再去B手机上登录点击支付，
        //会提示“该支付已经被其他账号发起”，所以还得和openid做一个关联
        //而且还可以缓存
        $orderid = $orderid . '-' . $amount . '-' . substr(md5($openid), 8, 16) . '-' . $useBalance . '-' . date('Ymd');
        if (strlen($orderid) > 32)
        {
            $orderid = substr($orderid, 0, 32);
        }
        $prePayId = Data_Memcache::getInstance()->get('wxprepayid-' . $orderid);
        if (empty($prePayId))
        {
            $prePayId = Weixin_Func::prePay($openid, $orderid, $amount, $payType);
            //prepayid有效时间2个小时，7200秒，设置短一点，7000
            Data_Memcache::getInstance()->set('wxprepayid-' . $orderid, $prePayId, 7000);
        }

        $timestamp = time();

        $prePayPackage = array(
            'appId' => WX_APP_ID,
            'timeStamp' => $timestamp,
            // 支付签名时间戳，注意微信jssdk中的所有使用timestamp字段均为小写。但最新版的支付后台生成签名使用的timeStamp字段名需大写其中的S字符
            'nonceStr' => Weixin_Func::getNonceStr(),
            // 支付签名随机串，不长于 32 位
            'package' => 'prepay_id=' . $prePayId,
            // 统一支付接口返回的prepay_id参数值，提交格式如：prepay_id=***）
            'signType' => 'MD5',
            // 签名方式，默认为'SHA1'，使用新版支付需传入'MD5'
        );

        $sign = Weixin_Func::sign($prePayPackage);
        $prePayPackage['paySign'] = $sign; // 支付签名

        return $prePayPackage;
    }

    public static function getSignPackage()
    {
        $jsTicket = self::getJsTicket();

        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $timestamp = time();
        $nonceStr = Weixin_Func::getNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);
        $signPackage = array(
            "appId" => WX_APP_ID,
            "nonceStr" => $nonceStr,
            "timestamp" => $timestamp,
            "url" => $url,
            "signature" => $signature,
            "rawString" => $string
        );

        return $signPackage;
    }

    /**
     * 从微信服务器下载图片到HC服务器.
     *
     * @param string $mediaId
     *
     */
    public function downloadImageFromWX2HC($mediaId)
    {
        $accessToken = self::getAccessToken();
        $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=" . $accessToken . "&media_id=" . $mediaId;

        // file
        $dir = WX_DOWNLOAD_PIC_PATH . date('Ymd');
        if (!file_exists($dir))
        {
            mkdir($dir, 0777, TRUE);
        }

        $fileName = date('YmdHis') . rand(1000, 9999) . '.jpg';
        $tgtPicName = $dir . '/' . $fileName;

        $ch = curl_init($url); // 初始化
        $fp = fopen($tgtPicName, 'wb'); // 打开写入
        curl_setopt($ch, CURLOPT_FILE, $fp); // 设置输出文件的位置，值是一个资源类型
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);

        return $fileName;
    }

    public static function saveLocation($info)
    {
        $wl = new Weixin_Location();

        return $wl->add($info);
    }

    public static function getCustomerRecord($openid)
    {
        if (!empty($openid))
        {
            $wl = new Weixin_Location();

            return $wl->getLatestRecord($openid);
        }

        return array();
    }

    public static function saveCoopworkerOpenid($cuid, $type, $openid)
    {
        $wc = new Weixin_Coopworker();

        $openid = $wc->getOpenIdByCuid($cuid, $type);
        if (empty($openid))
        {
            $info = array(
                'cuid' => $cuid,
                'type' => $type,
                'openid' => $openid,
            );

            return $wc->add($info);
        }

        return 0;
    }

    public static function getCoopworkerOpenid($cuid, $type)
    {
        if (empty($cuid))
        {
            return array();
        }

        $wc = new Weixin_Coopworker();

        return $wc->getOpenIdByCuid($cuid, $type);
    }

    public static function sendWxMessage($openid, $msgType, $data, $url = '')
    {
        $accessToken = self::getAccessToken();

        return Weixin_Func::sendTemplateMessage($openid, $accessToken, $msgType, $data, $url);
    }

    public static function getprePayQrcode($orderid, $amount, $useBalance = 0)
    {
        //订单金额会变，但是订单金额一变化，微信就认为是一个新订单，新订单是不能使用老订单的订单号的，
        //所以用订单号-金额组一个新的订单号，金额变化的时候，订单号也会变化
        //如果一个账号在A手机上点击过了detail页面，也就是发过了preopay请求，再去B手机上登录点击支付，
        //会提示“该支付已经被其他账号发起”，所以还得和openid做一个关联
        //而且还可以缓存
        $orderid = $orderid . '-' . $amount . '-' . 'native' . '-' . $useBalance . '-' . date('Ymd');
        if (strlen($orderid) > 32)
        {
            $orderid = substr($orderid, 0, 32);
        }
        $qrcode = Weixin_Func::prePayQrcode($orderid, $amount);

        return $qrcode;
    }
}