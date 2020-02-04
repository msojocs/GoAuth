<?php
require_once(__DIR__ . "/config/config.php");
require_once(__DIR__ . "/include/class/wechat.class.php");
require_once(__DIR__ . "/easy-http/load.php");
header("Content-Type: application/json");

$ret = array(
    'code' => 200,
    'msg' => 'success'
    );
    
if(!isset($_SERVER["HTTP_REQUEST_FROM"]) || $_SERVER["HTTP_REQUEST_FROM"] != "GoAuth")
{
    $ret['code'] = 403;
    $ret['msg'] = "非法请求";
    echo json_encode($ret);
    die;
}

$domain = isset($_GET['domain'])?$_GET['domain']:null;
$userinfo = isset($_GET['userinfo'])?$_GET['userinfo']:null;
$sk = isset($_GET['sk'])?$_GET['sk']:null;

if($domain != null && $userinfo != null && $sk != null){
    $url = "https://$domain/goauth?userinfo=$userinfo&sk=$sk";
    $res = getUrl($url);
    if(is_object($res))
    {
        $res = json_encode($res);
        $res = json_decode($res, true);
        if($res['errors']["http_request_failed"][0] == "SSL certificate problem: self signed certificate")
        {
            $ret['code'] = 244;
            $ret['msg'] = "服务端通过HTTPS访问来源域名时出错";
        }else{
            $ret['code'] = 245;
            $ret['msg'] = "服务端访问来源域名时出错";
        }
        // var_dump($res['errors']["http_request_failed"]);
        // $ret['detail'] = $res;
    }else if($res['headers']["wechatlogin"] != "ok")
    {
        $ret['code'] = 222;
        $ret['msg'] = "来源不承认此次验证";
    }
}else{
    $ret['code'] = 233;
    $ret['msg'] = "参数异常";
}

echo json_encode($ret);

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

