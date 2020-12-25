<?php

class Access_Api
{
    public static function authLogin($redirectUrl = '')
    {
        return Access_AuthFactory::getInstance()
                    ->setRedirectUri($redirectUrl)
                    ->entrance();
    }

    public static function subscribeByScene($uid)
    {
        $isSubScribe = 0;
        $scene = Access_Common::getScene();
        if (!empty($scene)) {
            $sceneRelation = User_Api::getSceneRelationByUid($uid, $scene);

            !empty($sceneRelation) && $isSubScribe = $sceneRelation[0]['subscribe'];
        }

        return array(
            'scene' => $scene,
            'is_subscribe' => $isSubScribe,
        );
    }
}