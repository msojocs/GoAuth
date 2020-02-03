<?php
require_once(__DIR__ . "/config/config.php");
require_once(__DIR__ . "/include/class/wechat.class.php");
header("Content-Type: application/json");

$str = isset($_GET['str'])?$_GET['str']:null;

if($str == null)
{
    $ret = array(
        "code" => 202,
        "msg" => "error"
        );
}else{
    
    $mini = new WeChat_MiNi();
    //获取access token
    $mini->getAccessToken();
    $ret = $mini->codeGetUnlimited($str);
}
echo json_encode($ret);