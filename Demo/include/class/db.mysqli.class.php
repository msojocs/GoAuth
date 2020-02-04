<?php
//error_reporting(0);
class DB
{
    static $link = null;


    function __construct()
    {
        //die(DB_HOST.'--'.DB_USER.'--'.DB_PASS.'--'.DB_NAME.'--'.DB_PORT);

        $this->$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

        if (!$this->$link) die('Code: 00001  Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());

        //mysqli_select_db($this->$link, $db_name) or die(mysqli_error($this->$link));

        mysqli_query($this->$link, "set sql_mode = ''");
        //字符转换，读库
        mysqli_query($this->$link, "set character set 'utf8'");
        //写库
        mysqli_query($this->$link, "set names 'utf8'");
        return true;
    }

    public function fetch($q)
    {
        return mysqli_fetch_assoc($q);
    }

    public function get_row($q)
    {
        $result = mysqli_query($this->$link, $q);
        return $this->fetch($result);
    }

    public function count($q)
    {
        $result = mysqli_query($this->$link, $q);
        if(!$result)
            return false;
        $count = mysqli_fetch_array($result);
        return $count[0];
    }

    public function query($q)
    {
        return mysqli_query($this->$link, $q);
    }

    public function escape($str)
    {
        return mysqli_real_escape_string($this->$link, $str);
    }

    public function insert($q)
    {
        if (mysqli_query($this->$link, $q))
            return mysqli_insert_id($this->$link);
        return false;
    }

    public function affected()
    {
        return mysqli_affected_rows($this->$link);
    }

    public function insert_array($table, $array)
    {
        $q = "INSERT INTO `$table`";
        $q .= " (`" . implode("`,`", array_keys($array)) . "`) ";
        $q .= " VALUES ('" . implode("','", array_values($array)) . "') ";
        //exit($q);
        if (mysqli_query($this->$link, $q))
            return mysqli_insert_id($this->$link);
        return false;
    }

    public function error()
    {
        $error = mysqli_error($this->$link);
        $errno = mysqli_errno($this->$link);
        return '[' . $errno . '] ' . $error;
    }

    public function close()
    {
        $q = mysqli_close($this->$link);
        return $q;
    }
}
