<?php
/**
 * Created by PhpStorm.
 * User: guoyexuan
 * Date: 2018/12/11
 * Time: 11:29 AM
 */

class cookies extends SQLite3
{
    protected  $db_path;
    protected  $now_time;
    protected  $osx_epoch = 978307200;

    public function __construct()
    {
        //初始化chat路径
        $this->db_path  = getenv('HOME').'/Library/Application Support/Google/Chrome/Default/Cookies';
        //初始化当前时间
        $this->now_time = microtime(true);
        //检查环境
        self::_checkEnv();
        //连接SQLlite
        self::_connect();
        //读取iMessage
        self::_getCookies();
    }

    public function _checkEnv()
    {
        logger::notice('检查SqlLite3环境');

        $pad_length = 26;

        $need_map = array(
            'sqlite3'    =>true,
            'pdo_sqlite' =>true,
        );

        foreach ($need_map as $ext_name=>$must_required)
        {
            $suport = extension_loaded($ext_name);

            if($must_required && !$suport)
            {
                exit($ext_name. " \033[31;40m [NOT SUPORT BUT REQUIRED] \033[0m\n\n\033[31;40mYou have to compile CLI version of PHP with --enable-{$ext_name}\n\n");
            }
            echo str_pad($ext_name, $pad_length), "\033[32;40m [OK] \033[0m\n";
        }
    }

    public function _connect()
    {
        $this->open($this->db_path);

        if($this)
        {
            logger::notice('Opened database successfully');
        }
        else
        {
            logger::notice('Opened database Fail!','error');
            exit(0);
        }
    }


    public function _getCookies($host_key = 'www.nike.com')
    {
        $sql = sprintf("select * from cookies where host_key = '%s'",$host_key);
        $ret = $this->query($sql);

        while($row = $ret->fetchArray(SQLITE3_ASSOC))
        {
           $chromed[] = $row['encrypted_value'];
        }

        $c=str_replace('v10','',$chromed[0]);
//        $c=$chromed[1];
        $iv = '                ';

        echo $c.PHP_EOL.PHP_EOL;
        $my_pass = 'FQwydaYjgmz1OZxMTrYrkQ==';
        $salt = utf8_encode('saltysalt');
        echo $salt.PHP_EOL;
        $length = 16;
        $iterations = 1003;
//        $d_key = openssl_pbkdf2($my_pass,$salt,$length,$iterations);
        /*

        $a = openssl_decrypt($c,'AES-128-CBC',$d_key,0,$iv);

        var_dump($d_key);
        echo PHP_EOL;
        var_dump($a);
        exit();

        var_dump($a);
        */
        $d_key = hash_pbkdf2('sha1',$my_pass,$salt,$iterations,$length);
        $a =  openssl_decrypt($c, 'AES-128-CBC', $d_key, 1, $iv);
//        $a = openssl_decrypt($c,'AES-128-CBC',$d_key,0,$iv);
        echo $d_key.PHP_EOL;
        var_dump($a);
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
});$start = new cookies();