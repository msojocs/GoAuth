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

    if (scene != undefined && scene.indexOf('@') != -1) {
      //场景处理
      this.handleScene(1047, scene);
      return;
    } else if (scene == undefined)
    {
      this.setData({
        action: "index"
      })
    }

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
    this.setData ({
      action: "appInfo"
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
                  'content-type': 'application/json', 
                  'request-from': 'GoAuth'
                },
                success: res => {
                  if (res.data.code != 200)
                  {
                    this.showError(res.data.code, res.data.msg);
                    return;
                  }
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
                      'content-type': 'application/json', 
                      'request-from': 'GoAuth'
                    },
                    success: res => {
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
    switch(code)
    {
      default:
        break;
    }
    wx.showToast({
      title: msg + '\n错误码' + code,
      mask: true,
      icon: 'none'
    })
  }
})
