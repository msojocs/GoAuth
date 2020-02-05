<?php
header("Content-Type: application/json");
// require_once(__DIR__ . "/../config/config.php");
// require_once(__DIR__ . "/easy-http/load.php");
// include_once __DIR__ . "/decryption/wxBizDataCrypt.php";

// 验证是否由小程序请求
request_verify();

//取参数
$jscode = queryGET('jscode');
$iv = queryGET('iv');
$encryptedData = queryGET('encryptedData');

if($jscode == null || $iv == null || $encryptedData == null)
{
    $ret = array(
        'code' => 233,
        'msg' => '参数异常'
        );
    echo json_encode($ret);
    die;
}

//由jscode提取session
$session = jscode2session($jscode);
$ret = json_decode($session, true);
$sessionKey = $ret['session_key'];

$ret = decryptUserData($iv, $encryptedData, $sessionKey);

echo json_encode($ret);