<?php
header("Access-Control-Allow-Origin: *");
$urlarr = parse_url($_SERVER["REQUEST_URI"]);

switch ($urlarr['path']) {
    case '/qrcode':
        require 'auth_server/qrcode.php';
        exit;
        break;
    case '/verify':
        require 'auth_server/verify.php';
        exit;
        break;
    case '/goauth':
        require 'auth_server/test.php';
        exit;
        break;
    case '/getreferer':
        require 'auth_server/getreferer.php';
        exit;
        break;
    case '/login':
        require 'auth_server/login.php';
        exit;
        break;
}

echo 'WeChatLogin SERVER';