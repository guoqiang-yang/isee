<?php

/**
 * 后台管理 相关接口
 */
class Admin_Api extends Base_Api
{
    public static function getStaffList($start = 0, $num = 1000, $searchConf='')
    {
        $as = new Admin_Staff();
        $list = $as->getList($total, $start, $num, $searchConf);
        $leaders = Conf_Admin_Role::getLeaders();

        foreach ($list as $k => $v) 
        {
            if($v['leader_suid'])
            {
                $list[$k]['leader'] = $leaders[$v['leader_suid']];
            }
        }

        $hasMore = $total > $start + $num;

        foreach ($list as &$oner)
        {
            $oner['roles'] = explode(',', $oner['roles']);
            foreach ($oner['roles'] as &$role) 
            {
                $tmp = explode(':', $role);
                $role = $tmp[0];
            }

            $oner['_department'] = $oner['department'] ? Conf_Permission::$DEPAREMENT[$oner['department']] : '全部';
        }
        
        return array('list' => $list, 'total' => $total, 'has_more' => $hasMore);
    }

    public static function getStaff($suid, $status=Conf_Base::STATUS_NORMAL)
    {
        $as = new Admin_Staff();
        $info = $as->get($suid, $status);

        return $info;
    }

    public static function getStaffByMobile($mobile)
    {
        $as = new Admin_Staff();
        $userInfo = $as->getByMobile($mobile);

        return $userInfo;
    }

    public static function getStaffs($suids)
    {
        $as = new Admin_Staff();
        $infos = $as->getUsers($suids);

        return $infos;
    }

    public static function getStaffByCityAndRole($city, $role)
    {
        $as = new Admin_Staff();
        $adminList = $as->getByCityAndRole($city, $role);

        return $adminList;
    }

    public static function getStaffByRole($role)
    {
        $as = new Admin_Staff();
        $adminList = $as->getStaffByRole($role);

        return $adminList;
    }

    public static function getDrivers()
    {
        $suids = array(1015,1019,1020,1021,1006,1023,1024,1031,1030);
        $drivers = self::getStaffs($suids);
        return $drivers;
    }

    /**
     * 通过后台系统的各种列表页，返回操作人的姓名,手机号.
     * 
     * @param array $list
     * @param array $fieldNames  array(suid, sales_suid,...)
     * 
     *   filename = {filename}_info
     */
    public static function appendStaffSimpleInfo(&$list, $fieldNames)
    {
        $simpleInfos = array();
        
        $suid = array();
        foreach ($list as $one)
        {
            foreach($fieldNames as $field)
            {
                if (isset($one[$field]) && $one[$field]!=0)
                {
                    $suid[] = $one[$field];
                }
            }
        }
        if (!empty($suid))
        {
            $staffs = Tool_Array::list2Map(self::getStaffs(array_unique($suid)), 'suid');
        }

        foreach ($list as &$one)
        {
            foreach($fieldNames as $field)
            {
                $suid = $one[$field];
                if (array_key_exists($suid, $staffs))
                {
                    $one[$field.'_info'] = array(
                        'name' => $staffs[$suid]['name'],
                        'mobile' => $staffs[$suid]['mobile'],
                    );
                }
            }
        }
    }
    
    public static function addStaff($info)
    {
        $as = new Admin_Staff();
        $suid = $as->add($info);

        return array('suid' => $suid);
    }

    public static function updateStaff($suid, $info)
    {
        $as = new Admin_Staff();
        $as->update($suid, $info);

        return TRUE;
    }

    public static function deleteStaff($suid)
    {
        $as = new Admin_Staff();
        $as->delete($suid);

        return TRUE;
    }

    public static function addActionLog($adminId, $actionType, $params = array())
    {
        $al = new Admin_Log();

        $info = array(
            'admin_id' => $adminId,
            'action_type' => $actionType,
            'params' => json_encode($params),
        );

        return $al->add($info);
    }

    public static function getAdminLogList($searchConf, $start = 0, $num = 0)
    {
        $al = new Admin_Log();
        $total = 0;
        $list = $al->getList($searchConf, $total, $start, $num);

        $as = new Admin_Staff();
        $adminList = $as->getList($t, 0, 0);
        $adminListMap = Tool_Array::list2Map($adminList, 'suid');
        foreach ($list as &$info)
        {
            $desc = Conf_Admin_Log::$ACTION_DESC[$info['action_type']];
            if (!empty($info['params']))
            {
                $params = json_decode($info['params']);
                if (!empty($params))
                {
                    foreach ($params as $k => $v)
                    {
                        $desc = str_replace('{' . $k . '}', $v, $desc);
                    }
                }
            }


            $info['desc'] = $desc;
            $info['admin_name'] = $adminListMap[$info['admin_id']]['name'];
            $info['action'] = Conf_Admin_Log::$ACTION_TYPE[$info['action_type']];
        }

        return array('list' => $list, 'total' => $total);
    }

    public static function addOrderActionLog($adminId, $oid, $actionType, $params = array())
    {
        $aol = new Admin_Order_Log();

        $info = array(
            'admin_id' => $adminId,
            'oid' => $oid,
            'action_type' => $actionType,
            'params' => json_encode($params),
        );

        return $aol->add($info);
    }

    public static function getOrderActionLogList($searchConf, $start = 0, $num = 0)
    {
        $aol = new Admin_Order_Log();
        $data = $aol->getList($searchConf, $start, $num);
        $total = $data['total'];
        $list = $data['list'];

        $as = new Admin_Staff();
        $adminList = $as->getList($t, 0, 0);
        $adminListMap = Tool_Array::list2Map($adminList, 'suid');
        foreach ($list as &$info)
        {

            $desc = Conf_Order_Action_Log::$ACTION_DESC[$info['action_type']];

            if (!empty($info['params']))
            {
                $params = json_decode($info['params']);
                if (!empty($params))
                {
                    foreach ($params as $k => $v)
                    {
                        if ($info['action_type'] == Conf_Order_Action_Log::ACTION_CHANGE_FEE)
                        {
                            $desc .= $k . '：' . $v;
                            $desc .= '；';
                        }
                        else if ($info['action_type'] == Conf_Order_Action_Log::ACTION_CHANGE_INFO)
                        {
                            $desc .= $k . '：' . $v;
                            $desc .= "；";
                        }
                        else if ($info['action_type'] == Conf_Order_Action_Log::ACTION_CHANGE_PRODUCTS)
                        {
                            $desc .= $k . '：' . $v;
                            $desc .= "；";
                        }
                        else
                        {
                            $desc = str_replace('{' . $k . '}', $v, $desc);
                        }
                    }
                }
            }
            $info['desc'] = $desc;
            $info['admin_name'] = $adminListMap[$info['admin_id']]['name'];
            $info['action'] = Conf_Order_Action_Log::$ACTION_TYPE[$info['action_type']];
        }

        return array('list' => $list, 'total' => $total);
    }

    public static function getAllStaff($all = false)
    {
        $af = new Admin_Staff();

        return $af->getAll($all);
    }

    public static function getDefaultDcID($staffUser)
    {
        $dcId = 0;
        if (!empty($staffUser['dc_id']))
        {
            $dcId = $staffUser['dc_id'];
        }
        else if ($staffUser['id'] == 1010)
        {
            $dcId = Conf_Dc::DC_BJ_5;
        }
        else if ($staffUser['id'] == 1006)
        {
            $dcId = Conf_Dc::DC_BJ_5;
        }
        else
        {
            $dcId = Conf_Dc::getDefaultDcId();
        }
        return $dcId;
    }

    public static function hasSpecialDc($staffUser)
    {
        return !empty($staffUser['dc_id']);
    }
    public static function getSpecialDc($staffUser)
    {
        return !empty($staffUser['dc_id']) ? $staffUser['dc_id'] : 0;
    }
}
