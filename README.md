# GoAuth
这是一个通过小程序实现微信登录功能的东西

前有`weauth`，不过因为最近的疫情在家闲得慌，就高仿了一下

# 逻辑
懒得写了，自己看图或者下载`logic`目录下的`GoAuth.vsdx`文件使用Visio查看
![实现逻辑](https://raw.githubusercontent.com/jiyeme/GoAuth/master/logic/goauth.png)

# DEMO
暂时只有PHP端，其它平台请自行参照原理编写（其实PHP端也得自己重新写，我写的太差了。。。）
DEMO下的文件全丢到某服务器根目录下，将空数据库上传，配置DEMO中`config/config.php`文件内容

# WxApp
小程序
改一下`project.config.json`中的`appid`

# phpApp
小程序服务端
不含数据库，只要配置一下`config/config.php`就能用


----
结束