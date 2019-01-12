<?php
/**
 * Created by PhpStorm.
 * User: guoyexuan
 * Date: 2018年12月10日
 * Time: 10:05 PM
 */

date_default_timezone_set('PRC');

define('SERVER_BASE', realpath(__dir__ . '/..') . '/');

class Luckydraw
{

    protected static $request_url;
    protected static $request_args;
    protected static $request_payload;
    protected static $cookies_domain = 'nike.com';
    protected static $user_token_info = array();
    protected static $user_services_info = array();
    protected static $login_count = 0;      //登陆账号计数


    public static function _init()
    {
        logger::notice('程序启动');
        static::$request_url      = configs::request_url();
        static::$request_args     = configs::request_args();
        static::$request_payload  = configs::request_payload();
    }
    public static function _run()
    {
        //登陆
        static::_login();
        //获取用户信息
        static::_get_user_service();
    }

    public static function _login()
    {
        logger::notice('开始登陆');
        foreach (static::$request_payload['login'] as $key => $val)
        {

            static::$login_count ++;
            static::_do_login($val);
            sleep(2);
            continue;
        }
    }


    public static function _do_login($login_args)
    {
        $url_args      = requests::format_url_args(self::$request_args['login']);

        $url           = static::$request_url['do_login_url'].$url_args;

        $cookies       = new cookies();
        $cookies_res   = $cookies->_getCookies(static::$cookies_domain);

        $header        = configs::do_login_header(static::$request_args['login'],$cookies_res,$login_args);

        //记录用户cookies
        $login_res     = requests::post($url,json_encode($login_args),$header,false,false,$login_args['username']);

        logger::info(print_r($login_res,true));

        $login_res     = json_decode($login_res,true);

        if(!empty($login_res['user_id']) && !empty($login_res['access_token']))
        {
            static::$user_token_info[][$login_res['user_id']] = $login_res;
        }
        else
        {
            logger::notice('登陆失败','red');
            exit(0);
        }
    }

    public static function _get_user_service()
    {
        logger::notice(sprintf('[%s]个账号登陆成功,正在获取用户信息',count(static::$user_token_info)));

        $url_args      = requests::format_url_args(static::$request_args['getuserservice']);
        $url           = static::$request_url['get_user_service'].$url_args;

        foreach (self::$user_token_info as $key => $user_token)
        {
            $cookies       = new cookies();
            $cookies_res   = $cookies->_getCookies(static::$cookies_domain);

            foreach ($user_token as $user_id => $val)
            {
                $header    = configs::getuserservice(static::$request_args['getuserservice'],$val['access_token'],$cookies_res);
                $user_info = requests::get($url,null,$header,false,false);

                logger::info($user_info);
                static::$user_token_info[$key][$user_id]['userservice'] = $user_info;
            }
        }
        print_r(static::$user_token_info);
    }
}

$LoadableModules = array('config','plugins');

spl_autoload_register(function($name)
{
    global $LoadableModules;

    foreach ($LoadableModules as $module)
    {
        $filename =  SERVER_BASE.$module.'/'.$name . '.php';
        if (file_exists($filename))
            require_once $filename;
    }
});
Luckydraw::_init();
Luckydraw::_run();