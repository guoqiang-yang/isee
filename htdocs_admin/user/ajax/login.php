<?php
include_once('../../../global.php');

class App extends App_Admin_Ajax
{
    private $mobile;
    private $password;

    protected function getPara()
    {
        $this->mobile = Tool_Input::clean('p', 'mobile', 'str');
        $this->password = Tool_Input::clean('p', 'password', 'str');
    }

    protected function checkPara()
    {
        if (empty($this->mobile) || empty($this->password))
        {
            throw new \Exception('请输入账号和密码');
        }
    }

    protected function main()
    {
        $loginRet = Admin_Auth_Api::login($this->mobile, $this->password);
        $this->_uid = $loginRet['suid'];

        self::setSessionVerifyCookie($loginRet['verify'], 864000);
    }

    protected function outputBody()
    {
        header('Location: ../../material/material_list.php');
        exit;
    }

    protected function showError($ex)
    {
        $errmsg = Conf_Exception::getErrorInfo($ex->getMessage());
        $queryParams = 'mobile='. $this->mobile. '&login_errmsg='. $errmsg['errmsg'];
        header('Location: ../login.php?'. $queryParams);
        exit;
    }
}

$app = new App('pub');
$app->run();