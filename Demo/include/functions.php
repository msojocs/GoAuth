<?php

include_once(__DIR__ . '/../config/config.php');
include_once(__DIR__ . '/class/db.mysqli.class.php');

// 向数据库插入生成的sk
function insert_sk($sk)
{
    $sql = "INSERT INTO `demo_wechat` (`id`, `sk`, `userinfo`, `status`) VALUES (NULL, '$sk', NULL, '0');";
    $db = new DB();
    $ret = $db->insert($sql);
    if(!$ret)
    {
        exit("记录sk出错！");
    }
}

// 使用sk向数据库查询userinfo
function sk2userinfo()
{
    $sk = query_get('sk');
    $sql = "SELECT * FROM `demo_wechat` WHERE `sk` = '$sk'";
    $db = new DB();
    $ret = $db->get_row($sql);
    return $ret;
}

// 更新sk的userinfo
function updatesk()
{
    $sk = query_get('sk');
    $userinfo = query_get('userinfo');
    $sql = "UPDATE `demo_wechat` SET `userinfo` = '$userinfo' WHERE `demo_wechat`.`sk` = '$sk';";
    $db = new DB();
    $db->query($sql);
    $res = $db->affected();
    if($res)
        return true;
    else
        return false;
}

//删除指定sk
function deletesk()
{
    $sk = query_get('sk');
    $sql = "DELETE FROM `demo_wechat` WHERE `demo_wechat`.`sk` = '$sk'";
    $db = new DB();
    $db->query($sql);
    $res = $db->affected();
    if($res)
        return true;
    else
        return false;
}

function query_get($name)
{
    return isset($_GET[$name])?$_GET[$name]:null;
}