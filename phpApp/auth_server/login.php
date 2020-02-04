<?php
header("Content-Type: application/json");
require_once(__DIR__ . "/config/config.php");
require_once(__DIR__ . "/easy-http/load.php");
include_once __DIR__ . "/decryption/wxBizDataCrypt.php";

// 检测请求来源
if((!isset($_SERVER["HTTP_REQUEST_FROM"]) || $_SERVER["HTTP_REQUEST_FROM"] != "GoAuth"))
{
    header('HTTP/1.1 403 Forbidden');
    die;
}

//取参数
$jscode = get_query('jscode');
$iv = get_query('iv');
$encryptedData = get_query('encryptedData');

if($jscode == null || $iv == null || $encryptedData == null)
{
    header('HTTP/1.1 403 Forbidden');
    die;
}
$url = "https://api.weixin.qq.com/sns/jscode2session?appid=" . APP_ID . "&secret=" . APP_SECRET . "&js_code=$jscode&grant_type=authorization_code";

// 发送请求，返回结果
$ret = getUrl($url);
if(is_object($ret))
{
    $ret = array(
        'code' => 201,
        'msg' => "请求发生异常"
    );
    echo json_decode($ret, true);
    die;
}
$ret = json_decode($ret['body'], true);

$sessionKey = $ret['session_key'];

$pc = new WXBizDataCrypt(APP_ID, $sessionKey);
$errCode = $pc->decryptData($encryptedData, $iv, $data );

if ($errCode == 0) {
    $data = json_decode($data, true);
    $ret = array(
        'code' => 200,
        'openid' => $data['openId'],
        "unionid" => $data['unionId']
        );
    echo json_encode($ret);
} else {
    print($errCode . "\n");
}

function get_query($key)
{
    return isset($_GET[$key])?$_GET[$key]:null;
}

//CURL GET
function getUrl($url) {
    $http = new EasyHttp();
    $response = $http->request($url, array(
        'method' => 'GET',        //	GET/POST
        'timeout' => 5,            //	超时的秒数
        'redirection' => 0
    ));
    return $response;
}

