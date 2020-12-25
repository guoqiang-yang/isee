<?php

abstract class Access_AuthBase
{
    protected $authResponse;

    abstract public function auth();

    protected function setAuthResponse($response)
    {
//        $accessResponseObj = new Access_Response();
//        return $accessResponseObj->setAccessResponse($response);

        return array(
            'scene' => empty($response['scene'])? '': $response['scene'],  //当前环境/平台
            'is_auth' => empty($response['is_auth'])? false: $response['is_auth'],  //授权
            'is_subscribe' => empty($response['is_subscribe'])? false: $response['is_subscribe'], //关注
            'open_id' => empty($response['open_id'])? '': $response['open_id'],    //open_id
            'uid' => empty($response['uid'])? '': $response['uid'],
            'verify' => empty($response['verify'])? '': $response['verify'],
        );
    }

}