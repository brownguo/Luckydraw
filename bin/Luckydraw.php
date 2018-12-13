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


    public static function _init()
    {
        logger::notice('程序启动');
        self::$request_url      = configs::request_url();
        self::$request_args     = configs::request_args();
        self::$request_payload  = configs::request_payload();
        //用户登录
        self::_do_login();
    }

    public static function _do_login()
    {
        $url_args      = requests::format_url_args(self::$request_args['login']);
        $url           = self::$request_url['do_login_url'].$url_args;

        $cookies_start = new cookies();
        $cookies_res   = $cookies_start->_getCookies(self::$cookies_domain);

        $login_args    = self::$request_payload['login'][1];    //用户账号

        $header        = configs::do_login_header(self::$request_args['login'],$cookies_res,$login_args);

        $login_res     = requests::post($url,json_encode($login_args),$header,false,false);

        logger::info(print_r($login_res,true));

        $login_res     = json_decode($login_res,true);

        if(!empty($login_res['user_id']) && !empty($login_res['access_token']))
        {
            self::$user_token_info[][$login_res['user_id']] = $login_res;
        }
        else
        {
            //可能是Cookies过期了,需要从新打开一下Chrome生成一下Cookies
            system('open -a "/Applications/Google Chrome.app" "https://www.nike.com/cn/zh_cn/"');
            sleep(2);
            call_user_func_array(array('Luckydraw','_do_login'),array(true));
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