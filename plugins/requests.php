<?php
/**
 * Created by PhpStorm.
 * User: guoyexuan
 * Date: 2018/11/29
 * Time: 11:50 AM
 */

class requests
{

    const VERSION = '1.0.1';

    protected static $ch = null;

    public static $timeout = 30;
    public static $headers = array();
    public static $http_info;
    public static $result;
    public static $out_header;

    public static function _init()
    {
        if(!is_resource(self::$ch))
        {
            self::$ch = curl_init();

            curl_setopt(self::$ch, CURLOPT_RETURNTRANSFER,true);
            curl_setopt(self::$ch, CURLOPT_HEADER, false);
            curl_setopt(self::$ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36");
            curl_setopt(self::$ch, CURLOPT_TIMEOUT, self::$timeout);
            curl_setopt(self::$ch, CURLOPT_NOSIGNAL,true);
        }

        return self::$ch;
    }

    public static function request($url,$method,$args,$is_save_cookies,$is_carry_cookies,$cookies=null)
    {
        $method = strtoupper($method);

        if($method == 'GET' && !empty($args))
        {
            $url = $url.(strpos($url, '?') === false ? '?' : '&').http_build_query($args);
        }

        curl_setopt (self::$ch, CURLOPT_REFERER, "https://wx.qq.com/");

        if($method == 'POST')
        {
            if(is_array($args))
            {
                $args = http_build_query($args);
            }

            curl_setopt(self::$ch, CURLOPT_HTTPHEADER, array(
                "ContentType: application/json; charset=UTF-8"
            ));

            curl_setopt(self::$ch, CURLOPT_POST, true);
            curl_setopt(self::$ch, CURLOPT_POSTFIELDS,$args);
        }

        if($is_save_cookies)
        {
            curl_setopt(self::$ch, CURLOPT_COOKIEJAR, '../logs/cookies.tmp');
        }
        if($is_carry_cookies)
        {
            curl_setopt(self::$ch, CURLOPT_COOKIEFILE, '../logs/cookies.tmp');
        }
        if(strpos($url, 'https') !== false)
        {
            curl_setopt(self::$ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt(self::$ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        if(!empty($cookies))
        {
            curl_setopt(self::$ch, CURLOPT_COOKIEFILE,$cookies);
        }

        curl_setopt(self::$ch, CURLOPT_ENCODING,'gzip');
        curl_setopt(self::$ch, CURLOPT_URL, $url);
        curl_setopt(self::$ch, CURLINFO_HEADER_OUT, true);

        self::$result     = curl_exec (self::$ch);

        if(self::$result === false)
        {
            echo 'Curl error: ' . curl_error(self::$ch);
        }

        self::$http_info = curl_getinfo(self::$ch);

        curl_close(self::$ch);
    }

    public static function get($url,$args=null,$is_save_cookies = false,$is_carry_cookies = false)
    {
        self::_init();
        self::request($url,'get',$args,$is_save_cookies,$is_carry_cookies);
        return self::$result;
    }

    public static function post($url,$args,$is_save_cookies = false,$is_carry_cookies = false)
    {
        self::_init();
        self::request($url,'post',$args,$is_save_cookies,$is_carry_cookies);
        return self::$result;
    }

    public static function get_http_info()
    {
        return self::$http_info;
    }

    public static function curl_download($url,$dir)
    {
        $ch = curl_init($url);

        $fp = fopen($dir, "wb");
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        $curl_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if($curl_code == 200)
        {
            curl_close($ch);
            fclose($fp);
            return true;
        }
        else
        {
            return false;
        }
    }
}