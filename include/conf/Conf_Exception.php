<?php

/**
 * Exception异常码
 */
class Conf_Exception
{

    public static function getErrorInfo($errmsg)
    {
        $rest = array(
            'errno' => '00999',
            'errmsg' => $errmsg,
        );

        if (array_key_exists($errmsg, self::$exceptions))
        {
            $rest = array(
                'errno' => self::$exceptions[$errmsg][0],
                'errmsg' => self::$exceptions[$errmsg][1],
            );
        }

        return $rest;
    }

	/**
	 * 异常定义
	 *
	 * 格式： 'exception tag' => array(errno, errmsg)
	 */
	public static $exceptions = array(

		//common
		'common:system error' => array('00001', '网络错误，请联系管理员或刷新重试'),
		'common:params error' => array('00002', '参数不全'),
		'common:permission denied' => array('00003', '您没有权限执行该操作'),
		'common:upload pic error' => array('00004', '图片上传错误'),
		'common:read pic info error' => array('00005', '解析图片格式错误'),
		'common:mobile format error' => array('00006', '手机格式不对'),
		'common:to login' => array('00007', '请登录'),
		'common:failure' => array('00010', '操作失败'),
		//customer user
		'user:user not exist' => array('10001', '用户不存在'),
		'user:password wrong' => array('10002', '密码错误'),
		'user:forbidden' => array('10003', '用户被禁用'),
		'user:captcha needed' => array('10004', '需要验证码'),
		'user:captcha wrong' => array('10005', '验证码错误'),
		'user:captcha to fast' => array('10005', '请稍后再重试'),
		'user:email taken' => array('10008', '邮箱已被占用'),
		'user:email format wrong' => array('10009', '邮箱格式错误'),
		'user:password empty' => array('10010', '密码为空'),
		'user:email empty' => array('10011', '邮箱为空'),
		'user:name empty' => array('10012', '用户名为空'),
		'user:password format error' => array('10014', '密码不能小于6个字母或数字'),
		'user:old password wrong' => array('10015', '原密码错误'),
		'user:mobile occupied' => array('10016', '该手机号已注册~'),
		'user:password simple' => array('10017', '密码不能是手机号后六位'),
		'user:modify user info fail' => array('10018', '修改用户信息失败'), //通用
		'user:password not same' => array('10019', '两次密码不一致'),
		'user:mobile format wrong' => array('10020', '手机号码格式不正确'),
		'user:status is not wrong' => array('10021', '用户状态异常'),

		//login/register
		'login: failure' => array('60000', '登陆失败'),
		'login:empty mobile' => array('60001', '手机号不能为空'),
		'login:empty password' => array('60003', '密码输入为空'),
		'login:password format error' => array('60004', '密码不能小于6个字母'),
		'register:shop name format error' => array('60102', '请正确输入门店名称'),
		'register:shop address format error' => array('60103', '请正确输入门店地址'),
		'register:shop assistant name format error' => array('60104', '请正确输入联系人姓名'),
		'register:shop assistant mobile format error' => array('60104', '请正确输入联系人手机'),
		'register:mobile format error' => array('60104', '请正确输入登录手机'),
		'register:mobile taken' => array('60105', '该登录手机号已经被占用'),
		'register:reset code error' => array('60106', '密码重置链接错误或已过期'),
		'register:password is to simple' => array('60107', '密码太简单请重试'),
	);

	const DEFAULT_ERRNO = '999999';
	const DEFAULT_ERRMSG = '内部错误，请联系管理员或稍后重试';
    
    
    public static $PDA_Exception = array(
        
    );
}