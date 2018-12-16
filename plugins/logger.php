<?php
/**
 * Created by PhpStorm.
 * User: guoyexuan
 * Date: 2018/11/29
 * Time: 5:05 PM
 */

class logger
{
    public static $log_file_path = __DIR__.'/../logs/lucky_draw.log';

    public static $foreground_colors = array();
    public static $background_colors = array();

    public static function info($msg)
    {
        self::add($msg,'info');
    }

    public static function error($msg)
    {
        self::add($msg,'error');
    }

    public static function warning($msg)
    {
        self::add($msg,'warning');
    }

    public static function debug($msg)
    {
        self::add($msg,'debug');
    }

    public static function add($msg,$log_type)
    {
        if(!empty($log_type))
        {
            $log_type = strtoupper($log_type);

            $msg = sprintf("%s [%s] %s".PHP_EOL,date('Y-m-d H:i:s',time()),$log_type,$msg);

            file_put_contents(self::$log_file_path,$msg, FILE_APPEND | LOCK_EX);
        }
    }
    public static function getForegroundColors()
    {
        return array_keys(self::$foreground_colors);
    }

    public static function getBackgroundColors()
    {
        return array_keys(self::$background_colors);
    }

    public static function _initBackgroundColors()
    {
        self::$foreground_colors = array(
            'black'     =>'0;30', 'dark_gray'   =>'1;30',
            'blue'      =>'0;34', 'light_blue'  =>'1;34',
            'green'     =>'0;32', 'light_green' =>'1;32',
            'cyan'      =>'0;36', 'light_cyan'  =>'1;36',
            'red'       =>'0;31', 'light_red'   =>'1;31',
            'purple'    =>'0;35', 'light_purple'=>'1;35',
            'brown'     =>'0;33', 'yellow'      =>'1;33',
            'light_gray'=>'0;37', 'white'       =>'1;37',
        );
        self::$background_colors = array(
            'red'=>'41', 'green'    =>'42',
            'yellow'=>'43', 'blue'  =>'44',
            'magenta'=>'45', 'cyan' =>'46',
            'light_gray'=>'46',
        );
    }

    public static function notice($string,$type = 'info',$background_color=null)
    {

        self::_initBackgroundColors();

        switch ($type)
        {
            case 'info':
                $foreground_color = 'green';
                break;
            case 'error':
                $foreground_color = 'red';
                break;
            case 'warning':
                $foreground_color = 'brown';
                break;
            case null:
                $foreground_color = 'green';
                break;
            default:
                $foreground_color = $type;
        }

        $colored_string = "";

        if (isset(self::$foreground_colors[$foreground_color]))
        {
            $colored_string .= "\033[" . self::$foreground_colors[$foreground_color] . "m";
        }
        if (isset(self::$background_colors[$background_color]))
        {
            $colored_string .= "\033[" . self::$background_colors[$background_color] . "m";
        }

        $colored_string .=  $string . "\033[0m";

        echo sprintf(self::getLocalTime().' [%s] '.$colored_string.PHP_EOL,strtoupper($type));
    }

    public static function getLocalTime()
    {
        return date('Y-m-d H:i:s',time());
    }

    public static function clear_command_line()
    {
        $line = array(27, 91, 72, 27, 91, 50, 74);
        foreach ($line as $ascii)
        {
            echo chr($ascii);
        }
    }
    public static function replace_echo($message,$force_clear_lines = NULL)
    {
        static $last_lines = 0;
        if(!is_null($force_clear_lines))
        {
            $last_lines = $force_clear_lines;
        }
        // 获取终端宽度
        $toss = $status = null;
        $term_width = exec('tput cols', $toss, $status);
        if($status || empty($term_width))
        {
            $term_width = 64;
        }
        $line_count = 0;
        //循环清除当前行数,用\n分割
        foreach(explode("\n", $message) as $line)
        {
            $line_count += count(str_split($line, $term_width));
        }
        for($i = 0; $i < $last_lines; $i++)
        {
            echo "\r";
            echo "\033[K";
            echo "\033[1A";
            echo "\r";
            echo "\033[K";
            echo "\r";
        }
        $last_lines = $line_count;
        echo $message."\n";
    }


}