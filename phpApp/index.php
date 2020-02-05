<?php
header("Access-Control-Allow-Origin: *");
$urlarr = parse_url($_SERVER["REQUEST_URI"]);
include_once 'goauth_server/load.php';

//路由
switch ($urlarr['path']) {

    // 请求小程序码
    case '/qrcode':
        require 'auth_server/qrcode.php';
        exit;
        break;

    // 验证
    case '/verify':
        require 'auth_server/verify.php';
        exit;
        break;

    // Test
    case '/goauth':
        require 'auth_server/test.php';
        exit;
        break;

    // 获取来源域名信息
    case '/getreferer':
        require 'auth_server/getreferer.php';
        exit;
        break;

    // 获取openid
    case '/login':
        require 'auth_server/login.php';
        exit;
        break;
}

echo 'GoAuth SERVER';