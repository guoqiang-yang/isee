<?php

/**
 * Class User_Passport
 * @method static User_Reg_Source User_Reg_Source()
 */
class User_Constitution extends Base_Func
{
    public function save($uid, $res, $answers)
    {
        $dao = new Data_Dao('t_user_constitution');
        $info = array(
            'uid' => $uid,
            'main' => $res['main'],
            'secondary' => implode(',', $res['secondary']),
            'vector' => Tool_Array::a2s($res['vector']),
            'answers' => Tool_Array::a2s($answers),
        );
        $update = array('main', 'secondary', 'vector');
        $ret = $dao->add($info, $update);
        return $ret;
    }

}