<?php

class User_Reg_Source extends Base_Func
{
    private static $DB_USER_REG_SOURCE = 't_user_3rd_relation';
    private static $DB_USER_3RD_INFO = 't_user_3rd_info';

    //授权
    public function authorize($openId, $source, $uid)
    {
        $dao = new Data_Dao(self::$DB_USER_REG_SOURCE);
        $regSourceData = array(
            'uid' => $uid,
            'open_id' => $openId,
            'source' => $source,
        );
        $dao->add($regSourceData);

        return true;
    }

    //订阅/关注/绑定
    public function subscribe($uid, $openId, $source, $userInfo)
    {
        //更新订阅关系
        $regDao = new Data_Dao(self::$DB_USER_REG_SOURCE);
        $subscribeTime = !empty($userInfo['subscribe_time'])? date('Y-m-d H:i:s', $userInfo['subscribe_time']):
                            date('Y-m-d H:i:s');
        $regUpData = array(
            //'uid' => $uid,
            'subscribe' => 1,
            'subscribe_time' => $subscribeTime,
            'nickname' => isset($userInfo['nickname'])? $userInfo['nickname']: '',
            'headimgurl' => isset($userInfo['headimgurl'])? $userInfo['headimgurl']: '',
        );
        $regUpWhere = array(
            'open_id' => $openId,
            'source' => $source,
        );
        $regDao->updateWhere($regUpWhere, $regUpData);

        //写：信息数据
        $this->addUser3rdInfo($uid, $openId, $source, $userInfo);

        return true;
    }

    /**
     *  通过Openid查询用户.
     *
     * @param $openId
     * @param string $source
     * @return array
     */
    public function getByUniqKey($openId, $source)
    {
        $dao = new Data_Dao(self::$DB_USER_REG_SOURCE);
        $where = array(
            'open_id' => $openId,
            'source' => $source
        );

        $rest = $dao->getListWhere($where, false);

        return !empty($rest)? $rest[0]: array();
    }

    /**
     * 通过uid，获取用户的平台来源.
     *
     * @param $uid
     * @param string $source
     * @return array
     */
    public function getByUid($uid, $source='')
    {
        $dao = new Data_Dao(self::$DB_USER_REG_SOURCE);
        $where = array(
            'uid' => $uid,
        );
        !empty($source) && $where['source'] = $source;

        return $dao->getListWhere($where, false);
    }



    private function addUser3rdInfo($uid, $openId, $source, $platformInfo)
    {
        $dao = new Data_Dao(self::$DB_USER_3RD_INFO);
        $data = array(
            'uid' => $uid,
            'open_id' => $openId,
            'source' => $source,
        );

        if ($source == Conf_Base::REG_SOURCE_WEIXIN) {
            $this->append3rdInfoForWeixin($data, $platformInfo);
        }

        $dao->add($data);
    }


    private function append3rdInfoForWeixin(&$user3rdInfo, $platformInfo)
    {
        empty($platformInfo['sex']) ?: $user3rdInfo['sex'] = $platformInfo['sex'];
        empty($platformInfo['city']) ?: $user3rdInfo['city'] = $platformInfo['city'];
        empty($platformInfo['province']) ?: $user3rdInfo['province'] = $platformInfo['province'];
        empty($platformInfo['country']) ?: $user3rdInfo['country'] = $platformInfo['country'];

        $user3rdInfo['subscribe_scene'] = $this->getSubscribeScene($platformInfo['subscribe_scene']);
    }

    private function getSubscribeScene($flag)
    {
        $mapping = array(
            'ADD_SCENE_PROFILE_CARD' => Conf_Base::SUBSCRIBE_SRC_CARD,     //名片分享
            'ADD_SCENE_QR_CODE' => Conf_Base::SUBSCRIBE_SRC_QR_CODE,       //扫码
            'ADD_SCENE_PROFILE_LINK' => Conf_Base::SUBSCRIBE_SRC_CONTENT,  //公众号内容
            'ADD_SCENE_PROFILE_ITEM' => Conf_Base::SUBSCRIBE_SRC_CONTENT,  //公众号内容
            'ADD_SCENE_PAID' => Conf_Base::SUBSCRIBE_SRC_PAID,             //支付号关注

            'df' => Conf_Base::SUBSCRIBE_SRC_OTHER,
        );

        return array_key_exists($flag, $mapping)? $mapping[$flag]: $mapping['df'];
    }
}