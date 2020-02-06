<?php
header("Content-Type: application/json");

// 验证是否由小程序请求
request_verify();
    
$domain = isset($_GET['domain'])?$_GET['domain']:null;
$userinfo = isset($_GET['userinfo'])?$_GET['userinfo']:null;
$sk = isset($_GET['sk'])?$_GET['sk']:null;

if($domain != null && $userinfo != null && $sk != null){
    $sk = urlencode($sk);
    $url = "https://$domain/goauth?userinfo=$userinfo&sk=$sk";
    $res = getUrl($url);
    if(is_object($res))
    {
        $res = json_encode($res);
        $res = json_decode($res, true);
        if($res['errors']["http_request_failed"][0] == "SSL certificate problem: self signed certificate")
        {
            $ret = array(
                'code' => 244,
                'msg' => '服务端通过HTTPS访问来源域名时出错'
                );
        }else{
            $ret = array(
                'code' => 245,
                'msg' => '服务端访问来源域名时出错'
                );
        }
    }else if($res['headers']["goauth"] == "ok")
    {
        $ret = array(
            'code' => 200,
            'msg' => 'success'
            );
    }else
    {
        $ret = array(
            'code' => 222,
            'msg' => '来源不承认此次验证'
            );
    }
}else{
    $ret['code'] = 233;
    $ret['msg'] = "参数异常";
}

echo json_encode($ret);