<?php
/**
 * Created by PhpStorm.
 * User: guoyexuan
 * Date: 2018/12/10
 * Time: 3:05 PM
 */

include_once "../plugins/requests.php";

$url = 'https://www.kuaidaili.com/free/intr/';

$res = requests::get($url,null,false,false,null);

echo '获取代理IP,代理池地址：'.$url.PHP_EOL;

preg_match_all('/<td data-title="IP">(.*?)<\/td>/si',$res,$proxy);
preg_match_all('/<td data-title="PORT">(.*?)<\/td>/si',$res,$port);

if(!empty($proxy[1]) && !empty($port[1]))
{
    echo '免费代理抓取成功,开始落地测试...'.PHP_EOL;

    foreach ($proxy[1] as $key => $ip)
    {
        $raw_ip[] = 'http://'.$ip.':'.$port[1][$key].PHP_EOL;
    }

    requests::set_proxy($raw_ip);


    foreach ($raw_ip as $k => $ip)
    {
        $url = 'http://pv.sohu.com/cityjson?ie=utf-8';

        print_r(requests::get($url,null,null,false,false,null));

        echo PHP_EOL;

        sleep(1);
    }
}
