(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-loginWx-loginWx"],{"0425":function(t,e,i){var n=i("24fb");e=n(!1),e.push([t.i,".main[data-v-17b73d2e]{width:100%;height:70vh}.f_div[data-v-17b73d2e]{height:250px}.logo_size[data-v-17b73d2e]{width:200px;height:200px}.login_btn[data-v-17b73d2e]{width:200px;height:48px;background:-webkit-gradient(linear,left top,left bottom,from(#ffc24d),to(#fee04c));background:-webkit-linear-gradient(#ffc24d,#fee04c);background:linear-gradient(#ffc24d,#fee04c);-webkit-border-radius:24px;border-radius:24px}.wx_size[data-v-17b73d2e]{width:27px;height:27px}.wx_text[data-v-17b73d2e]{font-size:18px;font-family:PingFang SC Bold,PingFang SC Bold-Bold;font-weight:700;text-align:left;color:#141414}.item_div[data-v-17b73d2e]{width:60%}.bot_text[data-v-17b73d2e]{font-size:11px;font-family:PingFang SC Regular,PingFang SC Regular-Regular;font-weight:400;text-align:left;color:#999;position:fixed;bottom:30px}.item_color[data-v-17b73d2e]{color:#61a0d7}",""]),t.exports=e},"066f":function(t,e,i){"use strict";var n=i("4ea4");i("d3b7"),i("ac1f"),i("25f0"),i("1276"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;n(i("6a27"));var a={data:function(){return{code:""}},mounted:function(){if(this.code=this.GetUrlParam("code"),this.code){var t={code:this.code};this.$Request({url:"/api/login/weChatLogin",data:t}).then((function(t){console.log(t,"授权返回"),200==t.data.code&&(uni.showToast({title:"登陆成功"}),uni.setStorageSync("token",t.data.data.token),uni.setStorageSync("firstLogin",1),setTimeout((function(){uni.redirectTo({url:"../index/index"})}),2e3))}))}},onLoad:function(){},methods:{goAgreement:function(t){uni.navigateTo({url:"../agreement/agreement?type="+t})},wxlogin:function(){this.code||(window.location="https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx77b42ee4be836c7e&redirect_uri=https%3a%2f%2fsite.lazypeoplemart.store%2fH5&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect")},GetUrlParam:function(t){var e=window.location.toString(),i=e.split("?");if(i.length>1){for(var n,a=i[1].split("&"),o=0;o<a.length;o++)if(n=a[o].split("="),null!=n&&n[0]==t)return n[1];return""}return""}}};e.default=a},"24ee":function(t,e,i){"use strict";i.r(e);var n=i("066f"),a=i.n(n);for(var o in n)"default"!==o&&function(t){i.d(e,t,(function(){return n[t]}))}(o);e["default"]=a.a},"3c3a":function(t,e,i){t.exports=i.p+"static/img/weixin@2x.b6259019.png"},"8b03":function(t,e,i){"use strict";var n;i.d(e,"b",(function(){return a})),i.d(e,"c",(function(){return o})),i.d(e,"a",(function(){return n}));var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-uni-view",{staticClass:"main flex flex_col flex_row_center ali_item_center"},[n("v-uni-view",{staticClass:"f_div flex flex_col flex_row_between"},[n("v-uni-view",{},[n("v-uni-image",{staticClass:"logo_size",attrs:{src:i("bdae"),mode:""}})],1),n("v-uni-view",{staticClass:"login_btn flex flex_row_around ali_item_center"},[n("v-uni-view",{staticClass:"item_div flex flex_row_around ali_item_center",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.wxlogin.apply(void 0,arguments)}}},[n("v-uni-image",{staticClass:"wx_size",attrs:{src:i("3c3a"),mode:""}}),n("v-uni-view",{staticClass:"wx_text"},[t._v("微信登录")])],1)],1)],1),n("v-uni-view",{staticClass:"bot_text flex"},[t._v("登录代表你已同意平台"),n("v-uni-view",{staticClass:"item_color",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.goAgreement(1)}}},[t._v("《服务协议》")]),t._v("和"),n("v-uni-view",{staticClass:"item_color",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.goAgreement(2)}}},[t._v("《隐私协议》")])],1)],1)},o=[]},b656:function(t,e,i){"use strict";var n=i("d3c7"),a=i.n(n);a.a},bdae:function(t,e,i){t.exports=i.p+"static/img/logo@2x.ad273cdc.png"},c69c:function(t,e,i){"use strict";i.r(e);var n=i("8b03"),a=i("24ee");for(var o in a)"default"!==o&&function(t){i.d(e,t,(function(){return a[t]}))}(o);i("b656");var r,c=i("f0c5"),d=Object(c["a"])(a["default"],n["b"],n["c"],!1,null,"17b73d2e",null,!1,n["a"],r);e["default"]=d.exports},d3c7:function(t,e,i){var n=i("0425");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var a=i("4f06").default;a("7dc3f526",n,!0,{sourceMap:!1,shadowMode:!1})}}]);