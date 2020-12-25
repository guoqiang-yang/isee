<?php

class User_Api extends Base_Api
{
    public static function getUserInfoByUid($uid)
    {
        $uuObj = new User_User();
        $userInfo = $uuObj->getByUid($uid);

        $userInfo['effect'] = json_decode($userInfo['effect'], true);
        $userInfo['_verify'] = User_Passport::createVerify($uid, $userInfo['password']);
        return $userInfo;
    }

    public static function getUserInfoFromVerify($verify, $expireTime)
    {
        return User_Passport::getUserInfoFromVerify($verify, $expireTime);
    }

    public static function getSceneAuthInfo($openId, $source)
    {
        $upObj = new User_Passport();
        return $upObj->getSceneAuthInfo($openId, $source);
    }

    public static function sceneAuthorize($openId, $source)
    {
        $upObj = new User_Passport();
        return $upObj->sceneAuthorize($openId, $source);
    }

    public static function sceneSubscribe($uid, $openId, $source, $userInfo)
    {
        $upObj = new User_Passport();
        return $upObj->sceneSubscribe($uid, $openId, $source, $userInfo);
    }

    public static function getSceneRelationByUid($uid, $source = '')
    {
        $urs = new User_Reg_Source();
        return $urs->getByUid($uid, $source);
    }

    public static function saveUserConstitution($uid, $res, $answers)
    {
        $uc = new User_Constitution();
        $uc->save($uid, $res, $answers);

        $uu = new User_User();
        $uu->saveUserEffect($uid, Conf_Material_Effect::TYPE_CONSTITUTION, $res);

        return true;
    }

    public static function saveUserEffect($uid, $effect)
    {
        $uu = new User_User();
        $uu->saveUserEffect($uid, Conf_Material_Effect::TYPE_EXPECT, $effect);

        return true;
    }
}
