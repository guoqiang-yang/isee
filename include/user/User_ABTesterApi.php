<?php

class User_ABTesterApi
{
    public static function testForUserEffect($uid)
    {
        if (in_array($uid, array(6000, 6001, 6005))) {
            return true;
        }

        return false;
    }
}