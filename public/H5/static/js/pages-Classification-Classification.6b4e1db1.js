(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-Classification-Classification"],{"139e":function(t,i,e){var n=e("24fb");i=n(!1),i.push([t.i,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 行为相关颜色 */\n/* 文字基本颜色 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */.fication[data-v-d75506ae]{display:-webkit-box;display:-webkit-flex;display:flex}.fication .fication_left[data-v-d75506ae]{width:%?200?%;color:#333;background-color:#f3f3f3;text-align:center;margin-bottom:%?120?%}.fication .fication_left > uni-view[data-v-d75506ae]{padding:%?36?% 0}.fication .treeactive[data-v-d75506ae]{font-weight:700;background-color:#fff}.fication .treeactive uni-view[data-v-d75506ae]{border-left:%?8?% solid #ffc24d}.fication .fication_right[data-v-d75506ae]{width:100%;-webkit-box-flex:1;-webkit-flex:1;flex:1;padding-top:%?36?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-align-content:flex-start;align-content:flex-start;-webkit-flex-wrap:wrap;flex-wrap:wrap;margin:0 %?20?% %?20?%;position:fixed;top:44px;left:%?200?%}.fication .fication_right .fication_right_list[data-v-d75506ae]{margin-bottom:%?40?%;text-align:center;height:%?160?%;width:33%}.fication .fication_right .fication_right_list > uni-view[data-v-d75506ae]:nth-child(2){font-size:%?26?%;color:#888}.fication .fication_right .fication_right_list > uni-view[data-v-d75506ae]:nth-child(1){-webkit-border-radius:%?12?%;border-radius:%?12?%;overflow:hidden}',""]),t.exports=i},1445:function(t,i,e){"use strict";e.r(i);var n=e("e689"),a=e.n(n);for(var r in n)"default"!==r&&function(t){e.d(i,t,(function(){return n[t]}))}(r);i["default"]=a.a},4776:function(t,i,e){"use strict";e.r(i);var n=e("ab57"),a=e("1445");for(var r in a)"default"!==r&&function(t){e.d(i,t,(function(){return a[t]}))}(r);e("99e3");var o,c=e("f0c5"),s=Object(c["a"])(a["default"],n["b"],n["c"],!1,null,"d75506ae",null,!1,n["a"],o);i["default"]=s.exports},"5ef1":function(t,i,e){var n=e("139e");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var a=e("4f06").default;a("3908923e",n,!0,{sourceMap:!1,shadowMode:!1})},"99e3":function(t,i,e){"use strict";var n=e("5ef1"),a=e.n(n);a.a},ab57:function(t,i,e){"use strict";var n;e.d(i,"b",(function(){return a})),e.d(i,"c",(function(){return r})),e.d(i,"a",(function(){return n}));var a=function(){var t=this,i=t.$createElement,e=t._self._c||i;return e("v-uni-view",{staticClass:"fication"},[e("v-uni-view",{staticClass:"fication_left"},t._l(t.categoryList,(function(i,n){return e("v-uni-view",{key:i.id,class:{treeactive:t.treeActiveNum==i.id}},[e("v-uni-view",{on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.clickLeft(i,n)}}},[t._v(t._s(i.name))])],1)})),1),e("v-uni-view",{staticClass:"fication_right"},t._l(t.arr,(function(i,n){return e("v-uni-view",{key:n,staticClass:"fication_right_list"},[e("v-uni-view",{on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.clickOne(i)}}},[e("v-uni-view",[e("v-uni-image",{staticStyle:{width:"120rpx",height:"120rpx"},attrs:{src:i.image}})],1),e("v-uni-view",[e("v-uni-text",[t._v(t._s(i.name))])],1)],1)],1)})),1)],1)},r=[]},e689:function(t,i,e){"use strict";Object.defineProperty(i,"__esModule",{value:!0}),i.default=void 0;var n={data:function(){return{treeActiveNum:0,categoryList:[],arrIndex:0,arr:[]}},onShow:function(t){var i=this;if(uni.getStorageSync("clickIndex")||uni.getStorageSync("left_id"))if(uni.getStorageSync("left_id"))this.treeActiveNum=uni.getStorageSync("active_id"),this.arrIndex=uni.getStorageSync("left_id"),console.log("存在之前点击的分类"),uni.removeStorageSync("active_id"),uni.removeStorageSync("left_id");else{console.log("不存在之前点击的分类");var e=JSON.parse(uni.getStorageSync("clickIndex"));this.treeActiveNum=e.id,this.arrIndex=e.index;var n=this;this.$Request({url:"/api/category/all_category",data:{token:uni.getStorageSync("token")}}).then((function(t){n.categoryList=t.data.data.list,n.arr=n.categoryList[i.arrIndex].son_list}))}else if(uni.getStorageSync("clickIndex")){var a=JSON.parse(uni.getStorageSync("clickIndex"));this.treeActiveNum=a.id,this.arrIndex=a.index;n=this;this.$Request({url:"/api/category/all_category",data:{token:uni.getStorageSync("token")}}).then((function(t){n.categoryList=t.data.data.list,n.arr=n.categoryList[i.arrIndex].son_list}))}else this.getCategory()},onLoad:function(t){console.log(t,"1233")},methods:{getCategory:function(){var t=this,i=this;this.$Request({url:"/api/category/all_category",data:{token:uni.getStorageSync("token")}}).then((function(e){i.categoryList=e.data.data.list,t.treeActiveNum=e.data.data.list[0].id,t.arr=t.categoryList[0].son_list}))},activeTree:function(t){this.treeActiveNum=t},clickLeft:function(t,i){uni.setStorageSync("active_id",t.id),uni.setStorageSync("left_id",i),this.arrIndex=i,this.arr=this.categoryList[this.arrIndex].son_list,this.treeActiveNum=t.id,this.$Request({url:"/api/goods/goodsListByCategory",data:{token:uni.getStorageSync("token"),category_id:t.id,keywords:"",sort:"normal",page:1,fullcut:""}}).then((function(t){console.log("分类下接口数据",t)}))},clickOne:function(t){uni.navigateTo({url:"/pages/Search/Search?id="+t.id+"&idbig="+this.arrIndex})}}};i.default=n}}]);