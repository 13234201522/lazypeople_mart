(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-Login-Login"],{"042b":function(n,t,e){var i=e("b95a");"string"===typeof i&&(i=[[n.i,i,""]]),i.locals&&(n.exports=i.locals);var a=e("4f06").default;a("8e503ae2",i,!0,{sourceMap:!1,shadowMode:!1})},"14d4":function(n,t,e){n.exports=e.p+"static/img/logo.1ccc03d7.png"},"23e2":function(n,t,e){"use strict";e.r(t);var i=e("6b50"),a=e.n(i);for(var o in i)"default"!==o&&function(n){e.d(t,n,(function(){return i[n]}))}(o);t["default"]=a.a},"6b50":function(n,t,e){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var i={data:function(){return{code:"",openid:"",nickname:"",avatar:"",token:""}},onLoad:function(){},methods:{getUserInfo:function(){var n=this;uni.login({success:function(t){if(t.code){var e=t.code;console.log("微信code",e),uni.request({url:"https://api.weixin.qq.com/sns/jscode2session?appid=wx0f5e743330decdae&secret=1c42b2522ad7389873c8865478f86b6f&js_code=".concat(e),success:function(t){n.openid=t.data.openid,console.log(t.data.openid,"获取openid",n.openid),console.log("用户id",t)}}),setTimeout((function(){uni.getUserInfo({success:function(t){n.getLoginToken(n.openid,t.userInfo.avatarUrl,t.userInfo.nickName)}})}),1e3)}}})},getLoginToken:function(n,t,e){var i=this;console.log(this.openid,n,"idididid"),this.$Request({url:"/api/login/thirdLogin",data:{openid:n,avatar:t,nickname:e}}).then((function(n){200==n.data.code?(i.$toast(n.data.msg,"success"),uni.navigateBack({delta:1})):i.$toast(n.data.msg),uni.setStorageSync("token",n.data.data.token)}))}}};t.default=i},a4af:function(n,t,e){"use strict";var i;e.d(t,"b",(function(){return a})),e.d(t,"c",(function(){return o})),e.d(t,"a",(function(){return i}));var a=function(){var n=this,t=n.$createElement,i=n._self._c||t;return i("v-uni-view",{staticClass:"loginPages"},[i("v-uni-view",{staticClass:"logobox"},[i("v-uni-image",{staticStyle:{width:"200rpx",height:"60rpx"},attrs:{src:e("14d4")}}),i("v-uni-text",[n._v("申请使用")])],1),i("v-uni-view",{staticClass:"logintext"},[i("v-uni-text",[n._v("您的微信头像、昵称、地区和性别信息")])],1),i("v-uni-view",{staticClass:"loginBtn_box"},[i("v-uni-view",{staticClass:"agreeBtn"},[i("v-uni-button",{attrs:{"form-type":"submit","open-type":"getUserInfo",size:"mini"},on:{getuserinfo:function(t){arguments[0]=t=n.$handleEvent(t),n.getUserInfo.apply(void 0,arguments)}}},[n._v("同意")])],1),i("v-uni-view",{staticClass:"nologinBtn"},[i("v-uni-button",{attrs:{type:"default",size:"mini"}},[n._v("拒绝")])],1)],1)],1)},o=[]},afbd:function(n,t,e){"use strict";e.r(t);var i=e("a4af"),a=e("23e2");for(var o in a)"default"!==o&&function(n){e.d(t,n,(function(){return a[n]}))}(o);e("cdaa");var s,r=e("f0c5"),c=Object(r["a"])(a["default"],i["b"],i["c"],!1,null,"57fc07af",null,!1,i["a"],s);t["default"]=c.exports},b95a:function(n,t,e){var i=e("24fb");t=i(!1),t.push([n.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */.loginPages[data-v-57fc07af]{padding-left:%?120?%;padding-top:%?120?%;padding-right:%?112?%}.loginPages .logobox[data-v-57fc07af]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;margin-bottom:%?40?%}.loginPages .logobox uni-text[data-v-57fc07af]{margin-left:%?20?%;font-weight:700}.loginPages .logintext[data-v-57fc07af]{font-size:%?32?%;font-weight:700;margin-bottom:%?50?%}.loginPages .loginType[data-v-57fc07af]{font-size:%?28?%;color:#999;margin-bottom:%?50?%}.loginPages .userMsg[data-v-57fc07af]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;border-top:%?2?% solid #999;border-bottom:%?2?% solid #999;padding:%?2?% 0;margin-bottom:%?120?%}.loginPages .loginBtn_box[data-v-57fc07af]{text-align:center}.loginPages .loginBtn_box uni-button[data-v-57fc07af]{width:%?300?%}.loginPages .loginBtn_box .agreeBtn[data-v-57fc07af]{margin-bottom:%?30?%}',""]),n.exports=t},cdaa:function(n,t,e){"use strict";var i=e("042b"),a=e.n(i);a.a}}]);