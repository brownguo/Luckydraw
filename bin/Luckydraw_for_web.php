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
        static::_login();
    }

    public static function _login()
    {
        logger::notice('开始登陆');
        static::$login_count ++;
        static::_do_login(static::$request_payload['login'][0]);
    }


    public static function _do_login($login_args)
    {
        $url           = static::$request_url['login_url'];
        $cookies       = new cookies();
        $cookies_res   = 'guidU=2892aafa-bfd3-4db0-e4c4-2308da0ccec5; dreams_sample=96; RES_TRACKINGID=86329735511668203; _gscu_207448657=30286612fyznse22; _smt_uid=5b365214.3357b5d2; ajs_user_id=null; ajs_group_id=null; NIKE_COMMERCE_CCR=1530286702325; CONSUMERCHOICE_SESSION=t; siteCatalyst_sample=94; dreamcatcher_sample=54; neo_sample=80; ResonanceSegment=1; anonymousId=0155DA52B8546BA0A82CD1F8646F6528; ajs_anonymous_id=%220155DA52B8546BA0A82CD1F8646F6528%22; cto_lwid=a6f63088-12e4-4737-9848-9bf26b15a9d8; bc_nike_japan_triggermail=%7B%22distinct_id%22%3A%20%221660bcfb97c53c-05dd663047bc7-34697809-384000-1660bcfb97d1670%22%7D; gig_hasGmid=ver2; bc_nike_singapore_triggermail=%7B%22distinct_id%22%3A%20%221660ff9c43d94-0c14b1cbe16842-34697809-384000-1660ff9c43f369%22%2C%22ch%22%3A%20%221104230987%22%7D; sq=3; bc_nike_sweden_triggermail=%7B%22distinct_id%22%3A%20%221661ea7e1d42b7-05629b2b614d4-34697809-384000-1661ea7e1d5e1b%22%7D; CONSUMERCHOICE=cn/zh_cn; NIKE_COMMERCE_COUNTRY=CN; NIKE_COMMERCE_LANG_LOCALE=zh_CN; snkrsCoachmarksSeen=true; _ga=GA1.2.950314525.1538183947; NIKE_CART=b:j; fs_uid=rs.fullstory.com`BM7A6`6317435668922368:5629499534213120; cicIntercept=1; AnalysisUserId=222.138.6.159.315171540373009905; neo.experiments=%7B%22main%22%3A%7B%223333-interceptor-cn%22%3A%22a%22%7D%2C%22ocp%22%3A%7B%7D%2C%22snkrs%22%3A%7B%7D%7D; neo.swimlane=25; co_size=8; _gcl_au=1.1.1740781832.1546063117; Hm_lvt_ed406c6497cc3917d06fd572612b4bba=1547270016; _abck=AA0B3E3DFA7C1299A62F6DBBC6E0A37A~0~YAAQbBLSPPETHd5nAQAAL9F7QAHMqSeEJ6UmpjP3md/WS+gkvLoxSpahvnhn41AV0bLSI9V9ZziO4ALWfD5gvLv59OPljAZW6mmrmn6ltlvm2IdDZRrRWW5eDPQ5eOdbJ9DSfR2Da17cAp6/9v6iUKeVVx/VUrGzr01nXWw/tsgsb8lL4+GAM+VN8PoACpnb+BOad85eeJ7Ahs+V+bHBaFPsSF8aeSoJRxp5jntDQ9CWtYodVX4VKDolj/nbHl8dLucJLEcGqLdKaQcfBEQxZG+ZcLqpga4TOCkfkpcSuMHlTJDGloIWEw==~-1~-1~-1; utag_main=_st:1547271884723$ses_id:1547270468006%3Bexp-session; lls=3; llCheck=I1Krrgy6opwqvl1zO9IGXyjfchyJSRBMadUkc7R0h+qdtNQCAoX/fnr6dyWSEyJGE3ZrlDWcaIJ0c9VvoYFRlBhpoi5ouAPeKUuVAsxnKTo1fQaLMRvgDRmTxDtjFrP27VpRMdvro7kxC7Q6RC+pZbSAdaXGpsEsbTmNn8NpIPM=; AMCV_F0935E09512D2C270A490D4D%40AdobeOrg=793872103%7CMCIDTS%7C17912%7CMCMID%7C67691996194980821833961761213955699007%7CMCAAMLH-1547989304%7C11%7CMCAAMB-1548122519%7CRKhpRz8krg2tLO6pguXWp5olkAcUniQYPHaMWWgdJ3xzPWQmdj0y%7CMCOPTOUT-1547384443%7CNONE%7CMCAID%7CNONE%7CvVersion%7C3.1.0; bm_sz=F4BFBE6D0BBBB9D3114DD0128C5F7B3F~QAAQZRLSPAoPeRJoAQAAUX2yUVNXpzNtLkmIt+o09vdM/wRN020i4UTQp/gtenkNs6hyBRlcoN5xmNoMBprZPMxRJxBCwv134geGgDuLdMO3upmrVxwtAzzWMDfNe2rMSDm+AlfaeWsz4RD4R5G88yIyoUIx3+CWdW2nerzMgfJ7ILpmxLDUG1Lsmuua; ak_bmsc=DF71008AF5661FC82707D3AAD8916CA23CD2126515690000DEDF3D5C19150E3B~plHgWxHwUUBHUagWJce3vYUZyELjkOBgV6fmY+/EuqDUG9LgNC+4FejAgtPeU04220CaTKSzeMkS1Zv+JLFekCNp+e0dCyu7cfbEiq+STqAcLGOWgcf6m0rGUxi20t5qAPsAm2uMztRpF0yxqMktaNg/3pjdAymBrU1vZcxNwrzHbgVaQtHkSPRw4WPewpAfn8iKhXLDY/hU0ii4hu1WDSRUsKxNE3OO2QU0pPWX+QAIye/E8CnaPr5UwHnAnPt9Ay; NIKE_CART_SESSION={%22bucket%22:%22jcart%22%2C%22country%22:%22CN%22%2C%22id%22:0%2C%22merged%22:false}; s_cc=true; guidS=2ffee434-1f8b-4cfa-e4a5-cc0c72edfe13; guidSTimestamp=1547558880613|1547558880613; APID=38A5BA35A710D7A1859B30F882335465.sin-328-app-ap-0; CART_SUMMARY=%7B%22profileId%22+%3A%2219101496281%22%2C%22userType%22+%3A%22DEFAULT_USER%22%2C%22securityStatus%22+%3A%220%22%2C%22cartCount%22+%3A0%7D; bm_sv=C346B09BA7B7D5E404883F7340940D7A~KflZ0s2d8QZxkjXmWCkuGFXhTUCz/dbksgDgVTgozdAh4Amo6qlMN8erXkUA0PB4Gi+5YS23T1E9MEU1Wx6UpsgSzgbzFxaITOzIrkldBMjtPlrqyHjqGUockpUHTppvLDeEEH1ES2+EvewF/6ZAGQ==; s_pers=%20c6%3Dno%2520value%7C1547560687148%3B%20c5%3Dno%2520value%7C1547560687159%3B; RT="sl=1&ss=1547558878333&tt=3792&obo=0&sh=1547558882157%3D1%3A0%3A3792&dm=nike.com&si=d0e9fcdd-c666-4214-8ca5-e72acf0dca74&bcn=%2F%2F1288af19.akstat.io%2F&ld=1547558882158&nu=&cl=1547558888395"';

        $header        = configs::login_header($cookies_res,$login_args);

        print_r($header);

        $login_res     = requests::post($url,json_encode($login_args),$header,false,false);



        var_dump($login_res);
        $login_res     = json_decode($login_res,true);

        if(!empty($login_res['user_id']) && !empty($login_res['access_token']))
        {
            static::$user_token_info[][$login_res['user_id']] = $login_res;
        }
        else
        {
            logger::notice('登陆失败','error');
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

    //可能是用客户端ID来区分用户会话的..这个地方需要在修改
    public static function _addCart()
    {
        $args    = static::$request_args['jcartService'];
        $url     = static::$request_url['add_cart'].requests::format_url_args($args);
        $cookies = new cookies();
        $header  = configs::jcartService($args,$cookies->_getCookies(static::$cookies_domain));

        $res     = requests::get($url,null,$header,false,false);
        print_r($res);
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