<?php

/**
 * 用户验证相关
 */
class Admin_Auth_Api extends Base_Api
{
    const SECRET = 'a022c9e4fb1359f65c4174c59453ef91';

    public static function login($mobile, $password)
    {
        // 获取用户信息
        $as = new Admin_Staff();
        $userInfo = $as->getByMobile($mobile);
        if (empty($userInfo) || $userInfo['status'] != Conf_Base::STATUS_NORMAL)
        {
            throw new Exception('user:user not exist');
        }
        $uid = $userInfo['suid'];

        // 检查密码
        $passwordMd5 = self::createPasswdMd5($password, $userInfo['salt']);
        if ($passwordMd5 != $userInfo['password'])
        {
            throw new Exception('user:password wrong');
        }

        return array(
            'suid' => $uid,
            'user' => $userInfo,
            'verify' => self::createVerify($uid, $userInfo['password']),
        );
    }

    public static function chgPassword($uid, $password, $code)
    {
        // 参数检查
        // todo: check code

        // 获取用户信息
        $as = new Admin_Staff();
        $userInfo = $as->get($uid);
        if (empty($userInfo))
        {
            throw new Exception('user:user not exist');
        }
        $uid = $userInfo['id'];

        // 检查密码格式
        if (!Str_Check::checkPassword($password))
        {
            throw new Exception('user:invalid password format');
        }

        // 数据操作
        $passwordMd5 = self::createPasswdMd5($password, $userInfo['salt']);
        $as->update($uid, array('password' => $passwordMd5));

        $ret = array(
            'uid' => $uid, 'verify' => self::createVerify($uid, $userInfo['password'])
        );

        return $ret;
    }

    public static function resetPassword($uid, $password)
    {
        $as = new Admin_Staff();
        $userInfo = $as->get($uid);
        $salt = mt_rand(1000, 9999);

        // 数据操作
        $passwordMd5 = self::createPasswdMd5($password, $salt);
        $info = array('password' => $passwordMd5, 'salt' => $salt);
        $as->update($uid, $info);

        $ret = array(
            'uid' => $uid, 'verify' => self::createVerify($uid, $userInfo['password'])
        );

        return $ret;
    }

    public static function checkVerify($verify, $expiredTime = 86400)
    {
        if (empty($verify))
        {
            return FALSE;
        }

        // 检查verify
        list($hash, $timestamp, $uid) = explode('_', $verify);
        if (empty($uid) || time() - $timestamp > $expiredTime)
        {
            return FALSE;
        }

        $as = new Admin_Staff();
        $userInfo = $as->get($uid);
        if (empty($userInfo) || $userInfo['status'] != Conf_Base::STATUS_NORMAL)
        {
            return FALSE;
        }

        if (!self::onlyCheckVerify($verify, $userInfo['password']))
        {
            return FALSE;
        }

        return $uid;
    }

    public static function createVerify($uid, $password)
    {
        $now = time();
        $verify = md5($now . ':' . $uid . ':' . $password . ':' . self::SECRET) . '_' . $now . '_' . $uid;

        return $verify;
    }


    public static function createPasswdMd5($password, $salt)
    {
        return md5($salt . ':' . $password);
    }

    private static function onlyCheckVerify($verify, $password)
    {
        list($hash, $timestamp, $uid) = explode('_', $verify);
        return md5($timestamp . ':' . $uid . ':' . $password . ':' . self::SECRET) != $hash? false: true;
    }

}
