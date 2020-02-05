<?php
// require_once(__DIR__ . "/../config/config.php");
// require_once(__DIR__ . "/include/class/wechat.class.php");
// require_once(__DIR__ . "/easy-http/load.php");
header("Content-Type: application/json");


// 验证是否由小程序请求
request_verify();

$ret = array(
    'code' => 200,
    'msg' => 'success'
    );
    
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
    }else if($res['headers']["goauth"] != "ok")
    {
        $ret['code'] = 222;
        $ret['msg'] = "来源不承认此次验证";
    }else if(!isset($res['headers']["goauth"]))
    {
        $ret['code'] = 404;
        $ret['msg'] = "来源君不知道去哪玩了？";
    }
}else{
    $ret['code'] = 233;
    $ret['msg'] = "参数异常";
}

echo json_encode($ret);