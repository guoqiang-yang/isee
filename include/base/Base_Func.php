<?php
/**
 * Func 基础
 */

class Base_Func
{
    protected $one;
    protected $mc;
    
    protected $response;

    private static $instance = array();
    private static $calledStaticCache = array();

    public function __construct()
    {
        $this->one = Data_One::getInstance();
        $this->mc = Data_Memcache::getInstance();

        // 初始化返回结果; 如果需要其他结构，可以重写$this->response
        $this->response = array(
            'total' => 0,
            'data' => array(),
        );
    }

    public static function getInstance()
    {
        $calledClassName = get_called_class();
        if (empty(self::$instance[$calledClassName])) {
            self::$instance[$calledClassName] = new static();
        }

        return self::$instance[$calledClassName];
    }


    /**
     * 类调用：单例.
     *  使用：在实用类头部声明
     *  eg：
     *     声明：@method static User_Passport User_Passport
     *     使用：self::User_Passport()->getSceneAuthInfo($openId, $source);
     * @param $calledClassName
     * @param $argv
     * @return mixed
     */
    public static function __callStatic($calledClassName, $argv)
    {
        if (empty(self::$calledStaticCache[$calledClassName])) {
            self::$calledStaticCache[$calledClassName] = new $calledClassName($argv);
        }

        return self::$calledStaticCache[$calledClassName];
    }
}
