<?php
class WeChat_MiNi
{
    private $appId = "";
    private $appSecret = "";
    private $accessToken = "";

    public function __construct() {
        $this->appId = APP_ID;
        $this->appSecret = APP_SECRET;
    }

    public function getAccessToken() {
        $url = "https://api.weixin.qq.com/cgi-bin/token";
        //参数
        $param['grant_type'] = "client_credential";
        $param['appid'] = $this->appId;
        $param['secret'] = $this->appSecret;

        $param = http_build_query($param);

        $url = $url . '?' . $param;

        $ret = $this->getUrl($url);
        $ret = json_decode($ret, true);
        $this->accessToken = $ret['access_token'];

        // var_dump($ret);
        return;
    }

    //生成小程序码，可接受页面参数较短，生成个数不受限
    public function codeGetUnlimited($str) {
        $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=" . $this->accessToken;

        //参数
        $param = "{\"scene\": \"$str\"}";

        // $param = http_build_query($param);
        $ret = $this->postUrl($url, $param);
        if (is_null(json_decode($ret))) {
            //不是json数据   有数据流  json_decode($codeinfo)返回值为 null
            $type = getimagesizefromstring($ret)['mime']; //获取二进制流图片格式
            $base64String = 'data:' . $type . ';base64,' . base64_encode($ret);
            
            //格式如：
            // 'data:image/png;base64,iVBORw0...此处省略...RZV0P=';
             
            //输出图片
            // echo "<img src='{$base64String}'>";
            $ret = array(
                "code" => 200,
                "qrcode" => $base64String
                );
        } else {
            //是json数据
            $ret = json_decode($ret, true);
        }
        // var_dump($ret);
        return $ret;
    }

    //CURL GET
    private function getUrl($url) {
        $ch = curl_init($url);
        $headers[] = null;
        curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    //CURL POST
    function postUrl($url,$data) {
        $ch = curl_init ();
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt ($ch, CURLOPT_POST, TRUE);
        $headers[] = null;
        curl_setopt ($ch, CURLOPT_HTTPHEADER,$headers);
        curl_setopt ($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
        $ret = curl_exec ($ch);
        curl_close ($ch);
        return $ret;
    }
}