//index.js
//获取应用实例
const app = getApp()

Page({
  data: {
    userInfo: {},
    hasUserInfo: false,
    canIUse: wx.canIUse('button.open-type.getUserInfo'),
    action: "index",
    attention: "",
  },
  //事件处理函数
  bindViewTap: function() {
    wx.navigateTo({
      url: '../logs/logs'
    })
  },
  onLoad: function (query) {
    // scene 需要使用 decodeURIComponent 才能获取到生成二维码时传入的 scene
    const scene = decodeURIComponent(query.scene);
    // const scene = encodeURIComponent();
    // console.log(scene);

    if (scene != undefined && scene.indexOf('@') != -1) {
      // console.log("1");
      //场景处理
      this.handleScene(1047, scene);
      return;
    } else if (scene == undefined)
    {
      // console.log("2");
      this.setData({
        action: "index"
      })
    }

    // console.log(app.globalData.userInfo);

    if (app.globalData.userInfo) {
      this.setData({
        userInfo: app.globalData.userInfo,
        hasUserInfo: true
      })
    } else if (this.data.canIUse){
      // 由于 getUserInfo 是网络请求，可能会在 Page.onLoad 之后才返回
      // 所以此处加入 callback 以防止这种情况
      app.userInfoReadyCallback = res => {
        this.setData({
          userInfo: res.userInfo,
          hasUserInfo: true
        })
      }
    } else {
      // 在没有 open-type=getUserInfo 版本的兼容处理
      wx.getUserInfo({
        success: res => {
          app.globalData.userInfo = res.userInfo
          this.setData({
            userInfo: res.userInfo,
            hasUserInfo: true
          })
        }
      })
    }
  },
  getUserInfo: function(e) {
    // console.log("打印结果");
    // console.log(e)
    app.globalData.userInfo = e.detail.userInfo
    this.setData({
      userInfo: e.detail.userInfo,
      hasUserInfo: true
    })
    this.authorizeOK();
  },
  //点击食用说明按钮
  buInfo: function(e)
  {
    // console.log("查看介绍")
    this.setData ({
      action: "appInfo",
      appContent: "\n登录演示和实例代码可在GoAuth主页(goauth.jysafe.cn)获得\n\n1.阁下需根据GoAuth约定格式-->(domain@sk)<--生成登录授权码所需字符串。\n注：字符串以@分割，其中domain是欲接入应用的域名，sk是登录请求校验码（由阁下随机生成，一般会在应用后台进行记录并设置有过期时间，用于与接收到的登录请求中的sk进行比对），字符串总长不超过32。\n\n2.请求https://api.goauth.jysafe.cn/qrcode?str=domain@sk获得base64格式的登录码。\n\n3.用户打开微信扫一扫，扫描登录请求校验码后，GoAuth后端将向欲接入应用的域名domain发起登录请求（GET  https://domain/goauth?userinfo=***&sk=***，注意https协议和固定的 /goauth 路径），userinfo为用户微信信息，sk为登录请求校验码。\n\n4.阁下的应用后端接收来自GoAuth后端的登录请求后根据sk识别登录请求的真伪，从而选择是否信任登录请求，根据当前登录请求中的用户微信信息完成授权登录。\n\n安全性：应用后端必须使用HTTPS协议，保证传输过程的安全性；GoAuth只负责转发请求，不进行任何形式的数据私自存储；通过请求校验码避免包括GoAuth在内的任何第三方伪造登录。\n\n补充说明：GoAuth是作者根据weauth的实现逻辑创建的，旨在避免weauth停止服务后无法继续使用微信登录的可能性。授权界面图标来源于(阿里巴巴矢量图标库-->__棂婳)本项目将实行开源。\n\n\n\n\n"
    })
  },
  //看完食用指南了
  readOver: function (e) {
    console.log("介绍查看完毕")
    this.setData({
      action: "index",
      appContent: null
    })
  },
  //判断场景所需的操作
  handleScene: function (scene, str) {
    // console.log("开始处理场景" + scene);
    switch (scene) {
      case 1047:
        app.globalData.str = str;
        var temp_arr = str.split('@');
        this.getreferer(temp_arr[0]);
        break;
      default:
        break;
    }
  },
  //获取来源
  getreferer: function(domain)
  {
    wx.request({
      url: 'https://api.goauth.jysafe.cn/getreferer', //获取来源
      data: {
        domain: domain
      },
      header: {
        'content-type': 'application/json', // 默认值
        'request-from': 'GoAuth'
      },
      success: res => {
        // console.log("触发来源请求成功，打印结果：" + res.data.name);
        this.setData({
          action: 'authorize',
          authorizeContent: "来源：" + res.data.name
        })
      }
    })
  },
  //取消授权
  authorizeCancel: function()
  {
    this.setData({
      action: "index"
    })
  },
  //确认授权
  authorizeOK: function()
  {
    this.showBusy();
    // 登录
    wx.login({
      success: res => {
        // console.log("登录成功!");
        app.globalData.jscode = res.code;
      }
    })
    // if(!hasUserInfo)
    //   return;
    // 获取用户信息
    wx.getSetting({
      success: res => {
        if (res.authSetting['scope.userInfo']) {
          // console.log("已授权");
          // 已经授权，可以直接调用 getUserInfo 获取头像昵称，不会弹框
          wx.getUserInfo({
            success: res => {
              // 储存用户信息
              app.globalData.userInfo = res.userInfo
              this.setData({
                userInfo: res.userInfo,
                hasUserInfo: true
              })
              // 发送 res.code 到后台换取 openId, sessionKey, unionId
              wx.request({
                url: 'https://api.goauth.jysafe.cn/login', //登录接口
                data: {
                  jscode: app.globalData.jscode,
                  iv    : res.iv,
                  encryptedData: res.encryptedData
                },
                header: {
                  'content-type': 'application/json', // 默认值
                  'request-from': 'GoAuth'
                },
                success: res => {
                  app.globalData.userInfo.openid = res.data.openid;
                  app.globalData.userInfo.unionid = res.data.unionid;
                  //验证请求
                  var temp_arr = app.globalData.str.split('@');
                  wx.request({
                    url: 'https://api.goauth.jysafe.cn/verify', //验证接口
                    data: {
                      domain: temp_arr[0],
                      sk: temp_arr[1],
                      userinfo: app.globalData.userInfo
                    },
                    header: {
                      'content-type': 'application/json', // 默认值
                      'request-from': 'GoAuth'
                    },
                    success: res => {
                      // console.log(res);
                      if (res.data.code == 200) {
                        this.showSuccess();
                        this.setData({
                          action: "index"
                        })
                      } else {
                        this.showError(res.data.code, res.data.msg);
                      }
                    }
                  })
                }
              })

              // 由于 getUserInfo 是网络请求，可能会在 Page.onLoad 之后才返回
              // 所以此处加入 callback 以防止这种情况
              if (this.userInfoReadyCallback) {
                this.userInfoReadyCallback(res)
              }

              
            }
          })
        }
      }
    })

  },
  //授权提示
  showBusy:  function() {
    wx.showToast({
      title: '正在授权',
      mask: true,
      icon: 'loading',
      duration: 5000
    })

  },
  showSuccess: function() {
    wx.showToast({
      title: '授权成功',
      mask: true,
      icon: 'success'
    })
  },
  showError: function (code, msg) {
    wx.showToast({
      title: msg + '\n错误码' + code,
      mask: true,
      icon: 'none'
    })
  }
})
