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

        self::$request_url      = configs::request_url();
        self::$request_args     = configs::request_args();
        self::$request_payload  = configs::request_payload();

        logger::notice('开始登陆');
        foreach (self::$request_payload['login'] as $key => $val)
        {
            self::$login_count ++;
            self::_do_login($val);

            //每个cookies只能登陆两个账号就失效了,从新获取cookies
//            if(self::$login_count % 2 != 0)
//            {
//                system::call_chrome_browser();
//                sleep(2);
//            }
            sleep(3);
            continue;
        }

        self::_get_user_service();
    }

    public static function _do_login($login_args)
    {
        $url_args      = requests::format_url_args(self::$request_args['login']);
        $url           = self::$request_url['do_login_url'].$url_args;

        $cookies_start = new cookies();
        $cookies_res   = $cookies_start->_getCookies(self::$cookies_domain);

        $header        = configs::do_login_header(self::$request_args['login'],$cookies_res,$login_args);

        //记录用户cookies
        $login_res     = requests::post($url,json_encode($login_args),$header,false,false,$login_args['username']);

        logger::info(print_r($login_res,true));

        $login_res     = json_decode($login_res,true);

        if(!empty($login_res['user_id']) && !empty($login_res['access_token']))
        {
            self::$user_token_info[][$login_res['user_id']] = $login_res;
        }
        else
        {
            return false;
        }
    }

    public static function _get_user_service()
    {
        logger::notice(sprintf('[%s]个账号登陆成功,正在获取用户信息',count(self::$user_token_info)));

        $url_args      = requests::format_url_args(self::$request_args['getuserservice']);
        $url           = self::$request_url['get_user_service'].$url_args;

        foreach (self::$user_token_info as $key => $user_token)
        {
            $cookies_start = new cookies();
            $cookies_res   = $cookies_start->_getCookies(self::$cookies_domain);

            foreach ($user_token as $user_id => $val)
            {
                $header    = configs::getuserservice(self::$request_args['getuserservice'],$val['access_token'],$cookies_res);
                $user_info = requests::get($url,null,$header,false,false);

                logger::info($user_info);
                self::$user_token_info[$key][$user_id]['userservice'] = $user_info;
            }
        }
        print_r(self::$user_token_info);
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
$start = new Luckydraw();
$start->_init();