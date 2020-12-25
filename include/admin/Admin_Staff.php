<?php

/**
 * 管理后台 - 员工相关
 */
class Admin_Staff extends Base_Func
{
    public function add(array $info)
    {
        assert(!empty($info));

        $info['ctime'] = $info['mtime'] = date('Y-m-d H:i:s');
        $res = $this->one->insert('t_staff_user', $info);

        return $res['insertid'];
    }

    public function delete($suid)
    {
        $suid = intval($suid);
        assert($suid > 0);

        $where = array('suid' => $suid);
        $update = array('status' => Conf_Base::STATUS_DELETED);
        $ret = $this->one->update('t_staff_user', $update, array(), $where);

        return $ret['affectedrows'];
    }

    public function update($suid, array $info)
    {
        $suid = intval($suid);
        assert($suid > 0);
        assert(!empty($info));

        $where = array('suid' => $suid);
        $ret = $this->one->update('t_staff_user', $info, array(), $where);

        return $ret['affectedrows'];
    }

    public function get($suid, $status = Conf_Base::STATUS_NORMAL)
    {
        $suid = intval($suid);
        assert($suid > 0);

        $where = " suid=$suid ";
        if ($status != Conf_Base::STATUS_ALL)
        {
            $where .= ' and status = ' . $status;
        }
        $data = $this->one->select('t_staff_user', array('*'), $where);

        return !empty($data['data'])? $data['data'][0]: array();
    }

    public function getByMobile($mobile)
    {
        $mobile = strval($mobile);
        assert(!empty($mobile));

        $where = array('mobile' => $mobile);
        $data = $this->one->select('t_staff_user', array('*'), $where);

        return !empty($data['data'])? $data['data'][0]: array();
    }

    public function getUsers(array $uids, $field = array('*'))
    {
        assert(!empty($uids));

        $where = array('id' => $uids);
        $data = $this->one->select('t_staff_user', $field, $where);

        return $data['data'];
    }

    public function getByWhere($where, $start = 0, $num = 0, $field = array('*'), $order = '')
    {
        assert(!empty($where));

        $data = $this->one->select('t_staff_user', $field, $where, $order, $start, $num);

        return $data['data'];
    }

    public function getTotalByWhere($where)
    {
        assert(!empty($where));

        $data = $this->one->select('t_staff_user', array('count(*)'), $where);

        return intval($data['data'][0]['count(*)']);
    }

    public function getAll($all = false)
    {
        $where = $all ? array() : array('status' => 0);

        // 查询结果
        $order = 'order by id desc';
        $data = $this->one->select('t_staff_user', array('*'), $where, $order, 0, 0);

        if (empty($data['data']))
        {
            return array();
        }

        return Tool_Array::list2Map($data['data'], 'id');
    }

}
