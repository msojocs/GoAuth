<?php

// 验证是否由小程序请求
request_verify();

header("Content-Type: application/json");

$domain = get_query('domain');

if($domain === null)
{
    $ret = array(
        'code' => 233,
        'msg' => '参数异常'
        );
    echo json_encode($ret);
    die;
}

$referer = array(
    'name' => null
    );

switch($domain)
{
    case 'www.jysafe.cn':
        $referer['name'] = "祭夜の咖啡馆";
        break;
    case 'goauth.jysafe.cn':
        $referer['name'] = "GoAuth演示站";
        break;
    default:
        $referer['name'] = $domain;
        break;
}

echo json_encode($referer);