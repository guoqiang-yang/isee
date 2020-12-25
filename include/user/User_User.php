<?php

class User_User extends Base_Func
{
    const TABLE = 't_user';

    public function getByUid($uid)
    {
        $dao = new Data_Dao(self::TABLE);
        return $dao->get($uid);
    }

    public function updateByUid($uid, $upData)
    {
        unset($upData['uid']);
        $dao = new Data_Dao(self::TABLE);
        return $dao->update($uid, $upData);
    }

    public function saveUserEffect($uid, $flag, $effectVal)
    {
        $dao = new Data_Dao(self::TABLE);
        $userInfo = $dao->get($uid);
        if (empty($userInfo)) {
            return false;
        }

        $effect = json_decode($userInfo['effect'], true);
        $effect[$flag] = $effectVal;
        $dao->update($uid, array('effect' => json_encode($effect)));

        return true;
    }

    /**
     * 用户注册：常规.
     *
     * @param $userInfo
     * @return int
     */
    public function register($userInfo)
    {
        if (empty($userInfo['mobile']) || empty($userInfo['password'])) {
            return false;
        }

        $dao = new Data_Dao(self::TABLE);
        $uid = $dao->add($this->genStandardUserInfo($userInfo));

        return $uid;
    }

    /**
     * 通过开放平台的注册.
     *
     * @param $source
     * @param $userInfo
     * @return int
     */
    public function registerFormScene($source, $userInfo)
    {
        $allowedPlatform = array(
            Conf_Base::REG_SOURCE_WEIXIN,
        );

        if (empty($source) || !in_array($source, $allowedPlatform)) {
            return false;
        }

        $regInfo = array();
        if ($source == Conf_Base::REG_SOURCE_WEIXIN) {
            $regInfo = $this->genUserInfoFromWeixin($source, $userInfo);
        }

        $dao = new Data_Dao(self::TABLE);
        $uid = $dao->add($regInfo);

        return $uid;
    }


    private function genStandardUserInfo($userInfo)
    {
        $myUserInfo = array();
        empty($userInfo['name'])?: $myUserInfo['name'] = $userInfo['name'];
        empty($userInfo['mobile'])?: $myUserInfo['mobile'] = $userInfo['mobile'];
        empty($userInfo['email'])?: $myUserInfo['email'] = $userInfo['email'];
        empty($userInfo['source'])?: $myUserInfo['source'] = $userInfo['source'];
        empty($userInfo['headimgurl'])?: $myUserInfo['headimgurl'] = $userInfo['headimgurl'];
        empty($userInfo['sex'])?: $myUserInfo['sex'] = $userInfo['sex'];
        empty($userInfo['birthday'])?: $myUserInfo['birthday'] = $userInfo['birthday'];
        empty($userInfo['hometown'])?: $myUserInfo['hometown'] = $userInfo['hometown'];
        empty($userInfo['city'])?: $myUserInfo['city'] = $userInfo['city'];

        $myUserInfo['salt'] = mt_rand(1000, 9999);
        $myUserInfo['password'] = $this->genPassword($userInfo['password'], $myUserInfo['salt']);

        return $myUserInfo;
    }

    private function genUserInfoFromWeixin($source, $userInfo)
    {
        $myUserInfo = array();
        !empty($userInfo['nickname']) && $myUserInfo['name'] = $userInfo['nickname'];
        !empty($userInfo['sex']) && $myUserInfo['sex'] = $userInfo['sex'];
        !empty($userInfo['province']) && $myUserInfo['city'] = $userInfo['province'];

        $myUserInfo['source'] = $source;
        $myUserInfo['salt'] = mt_rand(1000, 9999);
        $myUserInfo['password'] = '';

        return $myUserInfo;
    }


    private function genPassword($password, $salt)
    {
        return md5($salt. '#'. $password);
    }
}