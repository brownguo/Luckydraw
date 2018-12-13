<?php
/**
 * Created by PhpStorm.
 * User: guoyexuan
 * Date: 2018/12/13
 * Time: 4:09 PM
 */

class system
{
    public static function call_chrome_browser()
    {
        system('open -a "/Applications/Google Chrome.app" "https://www.nike.com/cn/zh_cn/"');
    }
}