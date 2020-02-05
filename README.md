# GoAuth
这是一个通过小程序实现微信登录功能的东西

前有`weauth`，不过因为最近的疫情在家闲得慌，就高仿了一下

# 逻辑
懒得写了，自己看图或者下载`pic`目录下的`GoAuth.vsdx`文件使用Visio查看
![实现逻辑](https://raw.githubusercontent.com/jiyeme/GoAuth/master/pic/goauth.png)

# 接入说明
 1. 应用开发者根据GoAuth约定格式（`domain@sk`）生成登录授权码所需字符串。
注：字符串以`@`分割，其中`domain`是接入应用的业务域名，`sk`是登录请求校验码（由开发者随机生成，一般会在应用后台进行记录并设置有过期时间，用于和接收到的登录请求中的`sk`进行比对），字符串总长不能超过**32**。

 2. 请求`https://api.goauth.jysafe.cn/qrcode?str=yourdomain.com@sk`获得base64格式的登录授权码。

 3. 应用用户打开微信扫一扫，扫描登录授权码后，GoAuth后端将向应用业务域名domain发起登录请求（GET方法：`https://yourdomain.com/goauth?userinfo=uuu&sk=xxx`，注意https协议和固定的`/goauth`路径），参数`userinfo`是用户微信信息，`sk`为登录请求校验码。

 4. 应用后端接收来自**goauth后端**的登录请求后根据`sk`识别登录请求的真伪，从而选择是否信任登录请求，根据当前登录请求中的用户微信信息完成授权登录。
PS：应用后端若信任请求请在响应头中添加 goauth: ok ；否则请在响应头中添加 goauth: fail；***不可不加goauth头或其值为其它字符串***

 5. 安全性：应用后端必须使用https协议，保证传输过程的安全；goauth只负责转发请求，不进行任何形式的数据私自存储；通过登录请求校验码，避免包括goauth在内的任何第三方伪造登录请求。

# DEMO
暂时只有PHP端，其它平台请自行参照原理编写（其实PHP端也得自己重新写，我写的太差了。。。）
DEMO下的文件全丢到某服务器根目录下，将空数据库上传，配置DEMO中`config/config.php`文件内容

# WxApp
小程序
改一下`project.config.json`中的`appid`

# phpApp
小程序服务端
不含数据库，只要配置一下`config/config.php`就能用


# 小程序服务端状态码(code)说明
| 状态码      | 状态类型    |
| ----------- | ----------- |
| 200         | 正常        |
| 201         | EasyPhp 请求发生异常|
| 211         | 异常！若频繁出现请联系开发者|
| 222         | 来源不承认此次验证|
| 233         | 参数异常|
| 244         | 服务端通过HTTPS访问来源域名时出错|
| 245         | 服务端访问来源域名时出错|
| 403         | 权限不足    |
| 404         | 来源君不知道去哪玩了？|

# 打赏支持~
厚颜无耻 ╮(╯▽╰)╭

| 支付宝 | 微信 |
| ------- | ----- |
|![支付宝打赏](https://raw.githubusercontent.com/jiyeme/GoAuth/master/pic/AliPay.jpg)|![微信打赏](https://raw.githubusercontent.com/jiyeme/GoAuth/master/pic/WeChat.png)|

----
结束
