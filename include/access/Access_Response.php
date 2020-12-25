<?php

class Access_Response
{
    private $isAuth;        //是否授权
    private $isSubscribe;   //是否关注
    private $openId;        //open_id
    private $uid;           //uid
    private $verify;        //登录cookie

    public function getIsAuth()
    {
        return $this->isAuth;
    }

    public function getIsSubscribe()
    {
        return $this->isSubscribe;
    }

    public function getOpenId()
    {
        return $this->openId;
    }

    public function getUid()
    {
        return $this->uid;
    }

    public function getVerify()
    {
        return $this->verify;
    }

    public function setAccessResponse($response)
    {
        $this->isAuth = empty($response['is_auth'])? false: $response['is_auth'];
        $this->isSubscribe = empty($response['is_subscribe'])? false: $response['is_subscribe'];
        $this->openId = empty($response['open_id']) ? '': $response['open_id'];
        $this->uid = empty($response['uid']) ? '': $response['uid'];
        $this->verify = empty($response['verify']) ? '': $response['verify'];

        return $this;
    }
}