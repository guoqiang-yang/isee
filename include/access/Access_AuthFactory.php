<?php

/**
 * 平台接入工厂类.
 *
 */

class Access_AuthFactory
{
    private $scene;
    private $redirectUri;

    private static $instance = null;
    private function __construct() {}

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri;
        return $this;
    }

    //入口
    public function entrance()
    {
        try{
            $this->scene = Access_Common::getScene();

            if (empty($this->scene)) {
                throw new \Exception('Sorry, You Don\'t Have Permission');
            }

            //读取授权平台
            $sceneObj = null;
            if ($this->scene == Conf_Base::REG_SOURCE_WEIXIN) {
                $sceneObj = new Access_Weixin_AuthApi();
            }
            if (! method_exists($sceneObj, 'auth')) {
                throw new Exception('Sorry, I Don\'t Have Permission');
            }

            //only-auth-test
            $authResponse = $sceneObj->auth($this->redirectUri);

            return $this->formatAccessResponse(0, 'succ', $authResponse);

        } catch (Exception $e) {
            return $this->formatAccessResponse(1, $e->getMessage());
        }
    }

    private function formatAccessResponse($errno, $errmsg, $data=array())
    {
        return array(
            'errno' => $errno,
            'errmsg' => $errmsg,
            'data' => $data,
        );
    }

}