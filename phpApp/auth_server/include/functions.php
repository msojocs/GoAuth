<?php
include_once GoAuth_DIR . "/easy-http/load.php";
include_once GoAuth_DIR . "/include/class/wechat.class.php";
include_once GoAuth_DIR . "/decryption/wxBizDataCrypt.php";

/**
 * EasyHttp GET
 * @param $url string 要请求的链接
 * 
 * @return $response array|object 成功返回数组(包含响应头、body等)，失败返回对象
 */
function getUrl($url)
{
    $http = new EasyHttp();
    $response = $http->request($url, array(
        'method' => 'GET',        //	GET/POST
        'timeout' => 5,            //	超时的秒数
        'redirection' => 0
    ));
    return $response;
}

/**
 * miniprogram verify
 */
function request_verify()
{
    if (!isset($_SERVER["HTTP_REQUEST_FROM"]) || $_SERVER["HTTP_REQUEST_FROM"] != "GoAuth") {
        $ret = array(
            'code' => 403,
            'msg' => '权限不足'
        );
        echo json_encode($ret);
        die;
    }
}

/**
 * 取指定GET参数
 * @param $key string 参数名
 * 
 * @return $value string|null 成功返回参数值，失败返回null
 */
function get_query($key)
{
    $value = isset($_GET[$key])?$_GET[$key]:null;
    return $value;
}

/**
 * 用户数据解密
 * @param $iv string 与用户数据一同返回的初始向量
 * @param $encryptedData string 加密的用户数据
 * @param $sessionKey string 根据用户code获取的密钥
 * 
 * @return $ret array 成功返回openid&unionid，失败返回错误信息
 */
function decryptUserData($iv, $encryptedData, $sessionKey)
{
    
    $pc = new WXBizDataCrypt(APP_ID, $sessionKey);
    $errCode = $pc->decryptData($encryptedData, $iv, $data );

    if ($errCode == 0) {
        $data = json_decode($data, true);
        if($data["watermark"]["appid"] === APP_ID)
        {
            $ret = array(
                'code' => 200,
                'openid' => $data['openId'],
                "unionid" => $data['unionId']
                );
        }else{
            $ret = array(
                'code' => 211,
                'msg' => '异常！若频繁出现请联系开发者'
                );
        }
    } else {
        $ret = $errCode;
    }
    return $ret;
}

/**
 * 由jscode提取sessionKey
 * @param $jscode string 小程序发送过来的jscode
 * 
 * @return $ret array 包含session的数组(还附带了openid和unionid)
 */
function jscode2session($jscode)
{
    $url = "https://api.weixin.qq.com/sns/jscode2session?appid=" . APP_ID . "&secret=" . APP_SECRET . "&js_code=$jscode&grant_type=authorization_code";

    // 请求session_key，返回结果
    $ret = getUrl($url);

    if(is_object($ret))
    {
        $ret = array(
            'code' => 201,
            'msg' => "请求发生异常"
        );
        $ret = json_encode($ret);
        $ret = json_decode($ret, true);
    }
    return $ret['body'];
}