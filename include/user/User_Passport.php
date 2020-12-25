<?php

/**
 * Class User_Passport
 * @method static User_Reg_Source User_Reg_Source()
 */
class User_Passport extends Base_Func
{
    const SECRET = 'a022c9e4fb1359f65c4174c59453ef92';

    /**
     * 获取平台授权信息.
     *
     * @param $openId
     * @param $source
     * @return array
     */
    public function getSceneAuthInfo($openId, $source)
    {
        $regSrcObj = new User_Reg_Source();
        $regSource = $regSrcObj->getByUniqKey($openId, $source);

        return empty($regSource)? array():
            array(
                'uid' => $regSource['uid'],
                'open_id' => $openId,
                'is_auth' => 1,
                'is_subscribe' => $regSource['subscribe'],
            );
    }

    /**
     * 各个开放平台授权使用：读取 or 保存用户信息
     *
     * @param $openId
     * @param $source
     * @return array
     */
    public function sceneAuthorize($openId, $source)
    {
        $regSrcObj = new User_Reg_Source();
        $regSource = $regSrcObj->getByUniqKey($openId, $source);

        if (empty($regSource)) { //新用户, 注册账号
            $uuObj = new User_User();
            $userInfo['nickname'] = '用户-'.rand(1000, 9999);
            $uid = $uuObj->registerFormScene($source, $userInfo);

            $regSrcObj->authorize($openId, $source, $uid);
            $isSubScribe = 0;
        } else {
            $uid = $regSource['uid'];
            $isSubScribe = $regSource['subscribe'];
        }

        return array(
            'uid' => $uid,
            'open_id' => $openId,
            'is_auth' => 1,
            'is_subscribe' => $isSubScribe,
        );
    }

    /**
     * 用户关注.
     *
     * @param $uid
     * @param $openId
     * @param $source
     * @param $userInfo
     * @return bool
     */
    public function sceneSubscribe($uid, $openId, $source, $userInfo)
    {
        //订阅/关注
        $ursObj = new User_Reg_Source();
        $ursObj->subscribe($uid, $openId, $source, $userInfo);

        //用户的昵称更新到t_user表
        if (!empty($userInfo['nickname'])){
            $uuObj = new User_User();
            $uuObj->updateByUid($uid, array('name' => $userInfo['nickname']));
        }

        return true;
    }

    public static function createVerify($uid, $password)
    {
        $now = time();
        $verify = md5($now . ':' . $uid . ':' . $password . ':' . self::SECRET) . '_' . $now . '_' . $uid;

        return $verify;
    }

    public static function getUserInfoFromVerify($verify, $expireTime)
    {
        if (empty($verify)) {
            return array();
        }

        list($hash, $timestamp, $uid) = explode('_', $verify);
        if ($timestamp + $expireTime < time()) {
            return array();
        }

        $uuObj = new User_User();
        $userInfo = $uuObj->getByUid($uid);
        if (empty($userInfo)) {
            return array();
        }
        if (md5($timestamp.':'.$uid.':'.$userInfo['password'].':'. self::SECRET) != $hash) {
            return array();
        }

        return array(
            'uid' => $uid,
            'user_info' => $userInfo,
        );
    }

}