(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-Classification-Classification"],{"0b07":function(t,i,e){var n=e("24fb");i=n(!1),i.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */.fication[data-v-e8a5bec0]{display:-webkit-box;display:-webkit-flex;display:flex;height:100%}.fication .fication_left[data-v-e8a5bec0]{width:%?200?%;color:#333;background-color:#f3f3f3;text-align:center}.fication .fication_left > uni-view[data-v-e8a5bec0]{padding:%?36?% 0}.fication .treeactive[data-v-e8a5bec0]{font-weight:700;background-color:#fff}.fication .treeactive uni-view[data-v-e8a5bec0]{border-left:%?8?% solid #ffc24d}.fication .fication_right[data-v-e8a5bec0]{-webkit-box-flex:1;-webkit-flex:1;flex:1;padding-top:%?36?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-align-content:flex-start;align-content:flex-start;-webkit-flex-wrap:wrap;flex-wrap:wrap;margin:0 %?20?% %?20?%}.fication .fication_right .fication_right_list[data-v-e8a5bec0]{margin-bottom:%?40?%;text-align:center;height:%?160?%;width:33%}.fication .fication_right .fication_right_list > uni-view[data-v-e8a5bec0]:nth-child(2){font-size:%?26?%;color:#888}.fication .fication_right .fication_right_list > uni-view[data-v-e8a5bec0]:nth-child(1){-webkit-border-radius:%?12?%;border-radius:%?12?%;overflow:hidden}',""]),t.exports=i},"516a":function(t,i,e){"use strict";Object.defineProperty(i,"__esModule",{value:!0}),i.default=void 0;var n={data:function(){return{treeActiveNum:0,categoryList:[],arrIndex:0,arr:[]}},onShow:function(t){var i=this;if(uni.getStorageSync("clickIndex")){var e=JSON.parse(uni.getStorageSync("clickIndex"));this.treeActiveNum=e.id,this.arrIndex=e.index;var n=this;this.$Request({url:"/api/category/all_category",data:{token:uni.getStorageSync("token")}}).then((function(t){n.categoryList=t.data.data.list,n.arr=n.categoryList[n.arrIndex].son_list}))}else this.getCategory(),setTimeout((function(){i.arr=i.categoryList[0].son_list}),1e3)},onLoad:function(t){console.log(t,"1233")},methods:{getCategory:function(){var t=this,i=this;this.$Request({url:"/api/category/all_category",data:{token:uni.getStorageSync("token")}}).then((function(e){i.categoryList=e.data.data.list,t.treeActiveNum=e.data.data.list[0].id}))},activeTree:function(t){this.treeActiveNum=t},clickLeft:function(t,i){this.arrIndex=i,this.arr=this.categoryList[this.arrIndex].son_list,this.treeActiveNum=t.id,this.$Request({url:"/api/goods/goodsListByCategory",data:{token:uni.getStorageSync("token"),category_id:t.id,keywords:"",sort:"normal",page:1,fullcut:""}}).then((function(t){console.log("分类下接口数据",t)}))},clickOne:function(t){uni.navigateTo({url:"/pages/Search/Search?id="+t.id+"&idbig="+this.arrIndex})}}};i.default=n},"6fa3":function(t,i,e){"use strict";var n;e.d(i,"b",(function(){return a})),e.d(i,"c",(function(){return r})),e.d(i,"a",(function(){return n}));var a=function(){var t=this,i=t.$createElement,e=t._self._c||i;return e("v-uni-view",{staticClass:"fication"},[e("v-uni-view",{staticClass:"fication_left"},t._l(t.categoryList,(function(i,n){return e("v-uni-view",{key:i.id,class:{treeactive:t.treeActiveNum==i.id}},[e("v-uni-view",{on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.clickLeft(i,n)}}},[t._v(t._s(i.name))])],1)})),1),e("v-uni-view",{staticClass:"fication_right"},t._l(t.arr,(function(i,n){return e("v-uni-view",{key:n,staticClass:"fication_right_list"},[e("v-uni-view",{on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.clickOne(i)}}},[e("v-uni-view",[e("v-uni-image",{staticStyle:{width:"120rpx",height:"120rpx"},attrs:{src:i.image}})],1),e("v-uni-view",[e("v-uni-text",[t._v(t._s(i.name))])],1)],1)],1)})),1)],1)},r=[]},"74b8":function(t,i,e){var n=e("0b07");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var a=e("4f06").default;a("297047a0",n,!0,{sourceMap:!1,shadowMode:!1})},b7af:function(t,i,e){"use strict";var n=e("74b8"),a=e.n(n);a.a},e41c:function(t,i,e){"use strict";e.r(i);var n=e("516a"),a=e.n(n);for(var r in n)"default"!==r&&function(t){e.d(i,t,(function(){return n[t]}))}(r);i["default"]=a.a},e70a:function(t,i,e){"use strict";e.r(i);var n=e("6fa3"),a=e("e41c");for(var r in a)"default"!==r&&function(t){e.d(i,t,(function(){return a[t]}))}(r);e("b7af");var c,o=e("f0c5"),s=Object(o["a"])(a["default"],n["b"],n["c"],!1,null,"e8a5bec0",null,!1,n["a"],c);i["default"]=s.exports}}]);