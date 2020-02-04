<?php
require_once(__DIR__ . '/include/functions.php');

header("Access-Control-Allow-Origin: *");
$urlarr = parse_url($_SERVER["REQUEST_URI"]);

// 路由
switch($urlarr['path'])
{
        //接收小程序传输的用户信息，更新userinfo
    case "/goauth":
        header("Cache-Control: no-store, no-cache, must-revalidate");
        $res = updatesk();
        //应用端对获取到的用户数据进行处理，如创建用户等。。。
        if($res)
            //响应头必须包含   [wechatlogin: ok]
            header("wechatlogin: ok");
        else
            header("wechatlogin: fail");
        die;
        break;

        //生成sk，此生成方法仅供示例使用，生产环境请更改生成方法
    case "/sk":
        header("Cache-Control: no-store, no-cache, must-revalidate");
        $sk = time();
        insert_sk($sk);
        $ret = array(
            "sk" => $sk
            );
        echo json_encode($ret);
        die;
        break;

    // 前端带sk访问请求授权结果
    case "/user":
        // 取得userinfo
        /**
         * 不一定返回userinfo
         * 也可以返回其它信息：比如只表示成功或者失败的字符串，也可以是跳转地址之类的~
         */
        header("Cache-Control: no-store, no-cache, must-revalidate");
        $res = sk2userinfo();
        if($res['userinfo'] != null)
        {
            //删除sk
            /**
             * 问题：
             * 如果没有这个请求，就不会删除sk会造成数据库数据积压
             * 可以使用其它方法使sk具有时间限制
             */
            deletesk();
            $ret = array(
                'code' => 200,
                'user' => $res['userinfo']
                );
        }else
        {
            $ret = array(
                'code' => 404
                );
        }
        echo json_encode($ret);
        die;
        break;
    default:
        echo file_get_contents("page.html");
        break;
}
