<?php

/**
 * 微信用户
 */
class Weixin_User extends Base_Func
{
    public function add($openId, $info, $change = array())
    {
        assert(!empty($openId));
        assert(!empty($info));

        $arr = Tool_Array::checkCopyFields($info, array(
            'openid',
            'uid',
            'nickname',
            'sex',
            'city',
            'country',
            'province',
            'language',
            'headimgurl',
            'subscribe_time',
            'unionid',
            'remark',
            'groupid',
            'created_at',
            'last_login_at',
            'login_times'
        ));
        $res = $this->one->insert('t_weixin_info', $arr);

        return $res['insertid'];
    }

    public function update($openId, $info, $change = array())
    {
        assert(!empty($openId));
        assert(!empty($info));

        $arr = Tool_Array::checkCopyFields($info, array(
            'nickname',
            'sex',
            'city',
            'country',
            'province',
            'language',
            'headimgurl',
            'subscribe_time',
            'unionid',
            'remark',
            'groupid',
            'last_login_at'
        ));
        $chg = Tool_Array::checkCopyFields($change, array('login_times'));

        $res = $this->one->update('t_weixin_info', $arr, $chg, array('openid' => $openId));

        return $res['affectedrows'];
    }

    public function get($uid, $openId)
    {
        if (empty($openId))
        {
            return array();
        }

        $where = array('openid' => $openId, 'uid' => $uid);
        $data = $this->one->select('t_weixin_info', array('*'), $where);
        if (empty($data['data']))
        {
            return array();
        }

        return $data['data'][0];
    }

    public function getByUid($uid)
    {
        assert(!empty($uid));

        $where = array('uid' => $uid);
        $data = $this->one->select('t_weixin_info', array('*'), $where);
        if (empty($data['data']))
        {
            return array();
        }

        return $data['data'][0];
    }

    public function getWxCustomer($openId)
    {
        assert(!empty($openId));

        $where = array('openid' => $openId);
        $data = $this->one->select('t_weixin_customer', array('*'), $where);
        if (empty($data['data']))
        {
            return array();
        }

        return $data['data'][0];
    }

    public function getOpenIdByCid($cid)
    {
        assert(!empty($cid));

        $where = array('cid' => $cid);
        $data = $this->one->select('t_weixin_customer', array('openid'), $where);

        if (empty($data['data']))
        {
            return '';
        }

        return $data['data'][0]['openid'];
    }

    public function getOpenIdByUid($uid)
    {
        assert(!empty($uid));

        $where = array('uid' => $uid);
        $data = $this->one->select('t_weixin_info', array('openid'), $where);

        if (empty($data['data']))
        {
            return '';
        }

        return $data['data'][0]['openid'];
    }

    public function getCidByOpenid($openid)
    {
        assert(!empty($openid));

        $where = array('openid' => $openid);
        $data = $this->one->select('t_weixin_customer', array('cid'), $where);

        if (empty($data['data']))
        {
            return '';
        }

        return $data['data'][0]['cid'];
    }

    public function addWxCustomer($cid, $openid)
    {
        assert(!empty($cid));
        assert(!empty($openid));

        $where = array('cid' => $cid);
        $data = $this->one->select('t_weixin_customer', array('cid'), $where);
        if (empty($data['data']))
        {
            $arr = array(
                'cid' => $cid,
                'openid' => $openid,
                'ctime' => date('Y-m-d H:i:s'),
            );
            $res = $this->one->insert('t_weixin_customer', $arr);

            return $res['insertid'];
        }
        else
        {
            if ($openid != $data['data'][0]['openid'])
            {
                $res = $this->one->update('t_weixin_customer', array('openid' => $openid), array(), $where);

                return $res['affectedrows'];
            }
        }
    }

    public function getByCids($cids)
    {
        assert(!empty($cids));

        $where = array('cid' => $cids);
        $data = $this->one->select('t_weixin_customer', array('*'), $where);
        if (empty($data['data']))
        {
            return array();
        }

        $list = Tool_Array::list2Map($data['data'], 'cid');

        return $list;
    }
}
