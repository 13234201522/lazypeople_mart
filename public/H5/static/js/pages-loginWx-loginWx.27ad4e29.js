(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-loginWx-loginWx"],{"0e00":function(e,t,i){"use strict";i.r(t);var n=i("29ae"),a=i.n(n);for(var o in n)"default"!==o&&function(e){i.d(t,e,(function(){return n[e]}))}(o);t["default"]=a.a},1818:function(e,t,i){"use strict";var n=i("df53"),a=i.n(n);a.a},"29ae":function(e,t,i){"use strict";var n=i("4ea4");i("d3b7"),i("ac1f"),i("25f0"),i("1276"),Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;n(i("9c82"));var a={data:function(){return{code:""}},mounted:function(){if(this.code=this.GetUrlParam("code"),this.code){var e={code:this.code};this.$Request({url:"/api/login/weChatLogin",data:e}).then((function(e){console.log(e,"授权返回"),200==e.data.code&&(uni.showToast({title:"登陆成功"}),uni.setStorageSync("token",e.data.data.token),uni.setStorageSync("firstLogin",1),setTimeout((function(){uni.switchTab({url:"../index/index"})}),2e3))}))}},onLoad:function(){},methods:{goAgreement:function(e){uni.navigateTo({url:"../agreement/agreement?type="+e})},wxlogin:function(){console.log("1111"),this.code||(window.location="https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx77b42ee4be836c7e&redirect_uri=https%3a%2f%2fsite.lazypeoplemart.store%2fH5&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect")},GetUrlParam:function(e){var t=window.location.toString(),i=t.split("?");if(i.length>1){for(var n,a=i[1].split("&"),o=0;o<a.length;o++)if(n=a[o].split("="),null!=n&&n[0]==e)return n[1];return""}return""}}};t.default=a},"49e6":function(e,t,i){"use strict";i.r(t);var n=i("c0d4"),a=i("0e00");for(var o in a)"default"!==o&&function(e){i.d(t,e,(function(){return a[e]}))}(o);i("1818");var r,c=i("f0c5"),l=Object(c["a"])(a["default"],n["b"],n["c"],!1,null,"578a35ee",null,!1,n["a"],r);t["default"]=l.exports},a783:function(e,t,i){var n=i("24fb");t=n(!1),t.push([e.i,".main[data-v-578a35ee]{width:100%;height:70vh}.f_div[data-v-578a35ee]{height:250px}.logo_size[data-v-578a35ee]{width:200px;height:200px}.login_btn[data-v-578a35ee]{width:200px;height:48px;background:-webkit-gradient(linear,left top,left bottom,from(#ffc24d),to(#fee04c));background:-webkit-linear-gradient(#ffc24d,#fee04c);background:linear-gradient(#ffc24d,#fee04c);-webkit-border-radius:24px;border-radius:24px}.wx_size[data-v-578a35ee]{width:27px;height:27px}.wx_text[data-v-578a35ee]{font-size:18px;font-family:PingFang SC Bold,PingFang SC Bold-Bold;font-weight:700;text-align:left;color:#141414}.item_div[data-v-578a35ee]{width:60%}.bot_text[data-v-578a35ee]{font-size:11px;font-family:PingFang SC Regular,PingFang SC Regular-Regular;font-weight:400;text-align:left;color:#999;position:fixed;bottom:30px}.item_color[data-v-578a35ee]{color:#61a0d7}",""]),e.exports=t},b4bd:function(e,t,i){e.exports=i.p+"static/img/logo@2x.ad273cdc.png"},c0d4:function(e,t,i){"use strict";var n;i.d(t,"b",(function(){return a})),i.d(t,"c",(function(){return o})),i.d(t,"a",(function(){return n}));var a=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("v-uni-view",{staticClass:"main flex flex_col flex_row_center ali_item_center"},[n("v-uni-view",{staticClass:"f_div flex flex_col flex_row_between"},[n("v-uni-view",{},[n("v-uni-image",{staticClass:"logo_size",attrs:{src:i("b4bd"),mode:""}})],1),n("v-uni-view",{staticClass:"login_btn flex flex_row_around ali_item_center"},[n("v-uni-view",{staticClass:"item_div flex flex_row_around ali_item_center"},[n("v-uni-image",{staticClass:"wx_size",attrs:{src:i("f69c"),mode:""}}),n("v-uni-view",{staticClass:"wx_text",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.wxlogin.apply(void 0,arguments)}}},[e._v("微信登录")])],1)],1)],1),n("v-uni-view",{staticClass:"bot_text flex"},[e._v("登录代表你已同意平台"),n("v-uni-view",{staticClass:"item_color",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.goAgreement(1)}}},[e._v("《服务协议》")]),e._v("和"),n("v-uni-view",{staticClass:"item_color",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.goAgreement(2)}}},[e._v("《隐私协议》")])],1)],1)},o=[]},df53:function(e,t,i){var n=i("a783");"string"===typeof n&&(n=[[e.i,n,""]]),n.locals&&(e.exports=n.locals);var a=i("4f06").default;a("114161e0",n,!0,{sourceMap:!1,shadowMode:!1})},f69c:function(e,t,i){e.exports=i.p+"static/img/weixin@2x.b6259019.png"}}]);