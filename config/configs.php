<?php
/**
 * Created by PhpStorm.
 * User: guoyexuan
 * Date: 2018/11/30
 * Time: 3:31 PM
 */

class configs
{
    protected static $ux_id              = 'com.nike.commerce.snkrs.web';
    protected static $appVersion         = '527';
    protected static $experienceVersion  = '425';
    protected static $locale             = 'zh_CN';
    protected static $backendEnvironment = 'identity';
    protected static $browser            = 'Google Inc.';
    protected static $os                 = 'undefined';
    protected static $mobile             = 'false';
    protected static $native             = 'false';
    protected static $visit              = '1';
    protected static $viewId             = 'unite';
    protected static $atgSync            = 'false';
    protected static $maps = array(
        'a','b','c','d','e','f','g','h',
        'i','j','k','l','m','n','o','p',
        'q','r','s','t','u','v','w','x',
        'y','z','1','2','3','4','5','6',
        '7','8','9','0'
    );

    public static function request_payload()
    {
        $request_payload = array(
            'login' => array(
                array(
                   
                ),
            ) ,
        );

        return $request_payload;
    }
    public static function request_args()
    {
        $visitor_id = '3c70a157-4f73-4448-81e6-ca418c6a5f46';

        $request_args = array(
            'login' => array(
                'appVersion'            =>static::$appVersion,
                'experienceVersion'     =>static::$experienceVersion,
                'uxid'                  =>static::$ux_id,
                'locale'                =>static::$locale,
                'backendEnvironment'    =>static::$backendEnvironment,
                'browser'               =>static::$browser,
                'os'                    =>static::$os,
                'mobile'                =>static::$mobile,
                'native'                =>static::$native,
                'visit'                 =>static::$visit,
                'visitor'               =>$visitor_id,
            ),
            'getuserservice'=>array(
                'appVersion'            =>static::$appVersion,
                'experienceVersion'     =>static::$experienceVersion,
                'uxid'                  =>static::$ux_id,
                'locale'                =>static::$locale,
                'backendEnvironment'    =>static::$backendEnvironment,
                'browser'               =>static::$browser,
                'os'                    =>static::$os,
                'mobile'                =>static::$mobile,
                'native'                =>static::$native,
                'visit'                 =>static::$visit,
                'visitor'               =>$visitor_id,
                'viewId'                =>static::$viewId,
                'atgSync'               =>static::$atgSync,
            ),
            'jcartService'=>array(
                'action'     =>'addItem',
                'lang_locale'=>'zh_CN',
                'country'    =>'CN',
                'catalogId'  =>'1',
                'productId'  =>'12542903',
                'price'      =>'',
                'qty'        =>'1',
                'skuAndSize' =>'22827460:undefined',
                'rt'         =>'json',
                'view'       =>'3',
                '_'          =>time(),
                'skuId'      =>'22827460',
                'displaySize'=>'',
                'merge'      =>'false',
            ),
        );
        return $request_args;
    }

    public static function request_url()
    {
        $login_uri = static::request_args();

        return array(
            'login_url'      => sprintf('https://unite.nike.com/login?%s',http_build_query($login_uri['login'])),
            'get_user_service'  => 'https://unite.nike.com/getUserService?',
            'add_cart'          => 'https://secure-store.nike.com/ap/services/jcartService?',
        );
    }

    public static function login_header($cookies,$args)
    {
        $path = static::request_args();

        $header = array(
            ':authority: unite.nike.com',
            ':method: POST',
            ':path: /login?'.str_replace('+', '%20', http_build_query($path['login'])),
            ':scheme: https',
            'accept: */*',
            'accept-encoding: gzip, deflate, br',
            'accept-language: zh-CN,zh;q=0.9',
            'cache-control: no-cache',
            'content-length: '.strlen(json_encode($args)),
            'content-type: application/json',
            'cookie: '.$cookies,
            'origin: https://store.nike.com',
            'pragma: no-cache',
            'referer: https://www.nike.com/cn/launch/t/air-jordan-33-university-red-black/',
            'user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36'
        );
        return $header;
    }

    public static function getuserservice($path,$access_token,$cookies)
    {
        $header     = array(
            ':authority: unite.nike.com',
            ':method: GET',
            ':path: /login?'.http_build_query($path),
            ':scheme: https',
            'accept: */*',
            'accept-encoding: gzip, deflate, br',
            'accept-language: zh-CN,zh;q=0.9',
            'authorization: Bearer '.$access_token,
            'cache-control: no-cache',
            'cookie: '.$cookies,
            'origin: https://www.nike.com',
            'pragma: no-cache',
            'referer: https://www.nike.com/cn/launch/',
            'user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36'
        );
        return $header;
    }

    public static function jcartService($path,$cookies)
    {
        $header     = array(
            ':authority: secure-store.nike.com',
            ':method: GET',
            ':path: /ap/services/jcartService?'.http_build_query($path),
            ':scheme: https',
            'accept: */*',
            'accept-encoding: gzip, deflate, br',
            'accept-language: zh-CN,zh;q=0.9',
            'cache-control: no-cache',
            'cookie: '.$cookies,
            'origin: https://www.nike.com',
            'pragma: no-cache',
            'referer: https://www.nike.com/cn/launch/t/pg-3-nasa-total-orange/',
            'user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36'
        );
        return $header;
    }

    public static function get_visitor_id()
    {
        $maps = static::$maps;

        shuffle($maps);
        $visitor_maps = '';

        foreach ($maps as $key => $val)
        {
            $visitor_maps .= $val;
        }

        $visitor_id = sprintf('%s-%s-%s-%s-%s',
            substr($visitor_maps, 0,8) ,substr($visitor_maps, 8,4),
            substr($visitor_maps, 12,4),substr($visitor_maps, 16,4),
            substr(str_shuffle($visitor_maps), 0, 12));

        return $visitor_id;
    }

    public static function client_id()
    {
        $client_id = '';
        foreach (static::$maps as $val)
        {
            $client_id .= strtoupper($val);
        }
        $client_id  = str_shuffle($client_id);
        $client_len = strlen($client_id);

        return substr($client_id,0,32);
    }
}