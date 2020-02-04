<?php
class WeChat_MiNi
{
    private $appId = "";
    private $appSecret = "";
    private $accessToken = "";

    /**
     * 构造函数
     * 需要引入config/config.php
     */
    public function __construct() {
        $this->appId = APP_ID;
        $this->appSecret = APP_SECRET;
    }

    /**
     * 取得AccessToken 并赋值给私有成员$accessToken
     */
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

        return;
    }

    /**
     * 生成小程序码，可接受页面参数较短，生成个数不受限
     * @param $str string 小程序内含的参数值
     * 
     * @return $ret array 成功返回含小程序码的数组，失败返回含失败原因数组
     */
    public function codeGetUnlimited($str) {
        $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=" . $this->accessToken;

        //参数
        $param = "{\"scene\": \"$str\"}";

        $ret = $this->postUrl($url, $param);
        if (is_null(json_decode($ret))) {
            //不是json数据   有数据流  json_decode($codeinfo)返回值为 null
            $type = getimagesizefromstring($ret)['mime']; //获取二进制流图片格式
            $base64String = 'data:' . $type . ';base64,' . base64_encode($ret);
            
            $ret = array(
                "code" => 200,
                "qrcode" => $base64String
                );
        } else {
            //是json数据
            $ret = json_decode($ret, true);
        }
        return $ret;
    }

    /**
     * CURL GET
     * @param $url string 网页链接
     * 
     * @return $data string GET请求结果 
     */
    private function getUrl($url) {
        $ch = curl_init($url);
        $headers[] = null;
        curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    /**
     * CURL POST
     * @param $url string 网页链接
     * @param $data jsonString POST数据
     * 
     * @return $data string POST请求结果
     */
    private function postUrl($url,$data) {
        $ch = curl_init ();
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt ($ch, CURLOPT_POST, TRUE);
        curl_setopt ($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
        $ret = curl_exec ($ch);
        curl_close ($ch);
        return $ret;
    }
}