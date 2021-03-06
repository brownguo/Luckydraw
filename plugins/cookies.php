<?php
/**
 * Created by PhpStorm.
 * User: guoyexuan
 * Date: 2018/12/11
 * Time: 11:29 AM
 * LastModify:2018年12月13日13:45:04
 */

class cookies extends SQLite3
{
    protected $db_path;
    protected $now_time;
    protected $osx_epoch                = 978307200;
    protected $keychain_pass            = 'FQwydaYjgmz1OZxMTrYrkQ==';
    protected $salt                     = 'saltysalt';
    protected $erncrypt_key_length      = 16;
    protected $erncrypt_key_iterations  = 1003;
    protected $iv                       = '                ';
    protected $get_cookies_url          = 'nike.com';   //如果为空的话获取所有cookies

    public function __construct()
    {
        //初始化chat路径
        $this->db_path  = getenv('HOME').'/Library/Application Support/Google/Chrome/Default/Cookies';
        //初始化当前时间
        $this->now_time = microtime(true);
        //检查环境
        $this->_checkEnv();
        //连接SQLlite
        $this->_connect();
    }

    protected function _checkEnv()
    {
        $pad_length = 26;

        $need_map = array(
            'sqlite3'    =>true,
            'pdo_sqlite' =>true,
            'openssl'    =>true,
            'posix'      =>true,
            'pcntl'      =>true,
        );

        foreach ($need_map as $ext_name=>$must_required)
        {
            $suport = extension_loaded($ext_name);

            if($must_required && !$suport)
            {
                exit($ext_name. " \033[31;40m [NOT SUPORT BUT REQUIRED] \033[0m\n\n\033[31;40mYou have to compile CLI version of PHP with --enable-{$ext_name}\n\n");
            }
        }
    }

    protected function _connect()
    {
        $this->open($this->db_path);

        if($this)
        {
            logger::info('Opened database successfully');
        }
        else
        {
            logger::error('Opened database Fail!','error');
            exit(0);
        }
    }


    public function _getCookies($host_key = null)
    {
        if(!is_null($host_key))
        {
            $host_key = sprintf("host_key like '%s%s%s'",'%',$host_key,'%');
        }
        else
        {
            $host_key = '1=1';
        }
        $sql = sprintf("select * from cookies where %s",$host_key);

        $ret = $this->query($sql);

        while($row = $ret->fetchArray(SQLITE3_ASSOC))
        {
           $cookies[$row['name']] = $row['encrypted_value'];
        }

        $res = $this->decrypt_values($cookies);

        if($res)
        {
            $this->dbh_close();
            return $this->construct_cookies($res);
        }
        else
        {
            logger::notice('Cookies解密失败!');
            exit(0);
        }
    }

    protected function decrypt_values($encrypt_arr)
    {
        if(!is_array($encrypt_arr))
        {
            return false;
        }
        else
        {
            $erncrypt_key = openssl_pbkdf2($this->keychain_pass,$this->salt,
                $this->erncrypt_key_length,
                $this->erncrypt_key_iterations);

            $decrypt_ret = array();

            foreach ($encrypt_arr as $cookie_key => $encrpty_val)
            {
                $encrpty_val = str_replace('v10','',$encrpty_val);
                $decrypt_ret[$cookie_key] = openssl_decrypt($encrpty_val, 'AES-128-CBC',$erncrypt_key,OPENSSL_RAW_DATA,$this->iv);
            }
            return $decrypt_ret;
        }
    }

    protected function construct_cookies($cookies)
    {
        if(!is_array($cookies))
        {
            return false;
        }
        else
        {
            $cookies_str = '';
            foreach ($cookies as $name => $val)
            {
                $cookies_str .= $name.'='.$val.';';
            }

            return trim($cookies_str,';');
        }
    }

    public function dbh_close()
    {
        $this->close();
    }
}
$LoadableModules = array('plugins');

spl_autoload_register(function($name)
{
    global $LoadableModules;

    foreach ($LoadableModules as $module)
    {
        $filename =  './'.$name . '.php';
        if (file_exists($filename))
            require_once $filename;
    }
});