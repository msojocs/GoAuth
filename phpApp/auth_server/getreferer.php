<?php

if(!isset($_SERVER["HTTP_REQUEST_FROM"]) || $_SERVER["HTTP_REQUEST_FROM"] != "GoAuth")
    die;

header("Content-Type: application/json");

$domain = isset($_GET['domain'])?$_GET['domain']:null;
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