(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-shopList-shopList"],{1274:function(t,e,i){"use strict";i("99af"),i("d81d"),i("a9e3"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n={name:"u-dropdown",props:{activeColor:{type:String,default:"#2979ff"},inactiveColor:{type:String,default:"#606266"},closeOnClickMask:{type:Boolean,default:!0},closeOnClickSelf:{type:Boolean,default:!0},duration:{type:[Number,String],default:300},height:{type:[Number,String],default:80},borderBottom:{type:Boolean,default:!1},titleSize:{type:[Number,String],default:28},borderRadius:{type:[Number,String],default:0},menuIcon:{type:String,default:"arrow-down"},menuIconSize:{type:[Number,String],default:26}},data:function(){return{showDropdown:!0,menuList:[],active:!1,current:99999,contentStyle:{zIndex:-1,opacity:0},highlightIndex:99999,contentHeight:0}},computed:{popupStyle:function(){var t={};return t.transform="translateY(".concat(this.active?0:"-100%",")"),t["transition-duration"]=this.duration/1e3+"s",t.borderRadius="0 0 ".concat(this.$u.addUnit(this.borderRadius)," ").concat(this.$u.addUnit(this.borderRadius)),t}},created:function(){this.children=[]},mounted:function(){this.getContentHeight()},methods:{init:function(){this.menuList=[],this.children.map((function(t){t.init()}))},menuClick:function(t){var e=this;if(!this.menuList[t].disabled)return t===this.current&&this.closeOnClickSelf?(this.close(),void setTimeout((function(){e.children[t].active=!1}),this.duration)):void this.open(t)},open:function(t){this.contentStyle={zIndex:11},this.active=!0,this.current=t,this.children.map((function(e,i){e.active=t==i})),this.$emit("open",this.current)},close:function(){this.$emit("close",this.current),this.active=!1,this.current=99999,this.contentStyle={zIndex:-1,opacity:0}},maskClick:function(){this.closeOnClickMask&&this.close()},highlight:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:void 0;this.highlightIndex=void 0!==t?t:99999},getContentHeight:function(){var t=this,e=this.$u.sys().windowHeight;this.$uGetRect(".u-dropdown__menu").then((function(i){t.contentHeight=e-i.bottom}))}}};e.default=n},"1b62":function(t,e,i){var n=i("adda");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var o=i("4f06").default;o("0ea116a4",n,!0,{sourceMap:!1,shadowMode:!1})},"200f":function(t,e,i){"use strict";var n;i.d(e,"b",(function(){return o})),i.d(e,"c",(function(){return a})),i.d(e,"a",(function(){return n}));var o=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",{staticClass:"u-cell-box"},[t.title?i("v-uni-view",{staticClass:"u-cell-title",style:[t.titleStyle]},[t._v(t._s(t.title))]):t._e(),i("v-uni-view",{staticClass:"u-cell-item-box",class:{"u-border-bottom u-border-top":t.border}},[t._t("default")],2)],1)},a=[]},"2ebe":function(t,e,i){"use strict";var n=i("f10b"),o=i.n(n);o.a},"3c8a":function(t,e,i){"use strict";i.r(e);var n=i("200f"),o=i("6bf0");for(var a in o)"default"!==a&&function(t){i.d(e,t,(function(){return o[t]}))}(a);i("457a");var r,s=i("f0c5"),l=Object(s["a"])(o["default"],n["b"],n["c"],!1,null,"0e487a9c",null,!1,n["a"],r);e["default"]=l.exports},"42c7":function(t,e,i){"use strict";i.d(e,"b",(function(){return o})),i.d(e,"c",(function(){return a})),i.d(e,"a",(function(){return n}));var n={uIcon:i("1f47").default},o=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",{staticClass:"u-cell",class:{"u-border-bottom":t.borderBottom,"u-border-top":t.borderTop,"u-col-center":t.center,"u-cell--required":t.required},style:{backgroundColor:t.bgColor},attrs:{"hover-stay-time":"150","hover-class":t.hoverClass},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.click.apply(void 0,arguments)}}},[t.icon?i("u-icon",{staticClass:"u-cell__left-icon-wrap",attrs:{size:t.iconSize,name:t.icon,"custom-style":t.iconStyle}}):i("v-uni-view",{staticClass:"u-flex"},[t._t("icon")],2),i("v-uni-view",{staticClass:"u-cell_title",style:[{width:t.titleWidth?t.titleWidth+"rpx":"auto"},t.titleStyle]},[t.title?[t._v(t._s(t.title))]:t._t("title"),t.label||t.$slots.label?i("v-uni-view",{staticClass:"u-cell__label",style:[t.labelStyle]},[t.label?[t._v(t._s(t.label))]:t._t("label")],2):t._e()],2),i("v-uni-view",{staticClass:"u-cell__value",style:[t.valueStyle]},[t.value?[t._v(t._s(t.value))]:t._t("default")],2),t.$slots["right-icon"]?i("v-uni-view",{staticClass:"u-flex u-cell_right"},[t._t("right-icon")],2):t._e(),t.arrow?i("u-icon",{staticClass:"u-icon-wrap u-cell__right-icon-wrap",style:[t.arrowStyle],attrs:{name:"arrow-right"}}):t._e()],1)},a=[]},"44fd":function(t,e,i){"use strict";i("99af"),i("7db0"),i("a9e3"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n={name:"u-dropdown-item",props:{value:{type:[Number,String,Array],default:""},title:{type:[String,Number],default:""},options:{type:Array,default:function(){return[]}},disabled:{type:Boolean,default:!1},height:{type:[Number,String],default:"auto"}},data:function(){return{active:!1,activeColor:"#2979ff",inactiveColor:"#606266"}},computed:{propsChange:function(){return"".concat(this.title,"-").concat(this.disabled)}},watch:{propsChange:function(t){this.parent&&this.parent.init()}},created:function(){this.parent=!1},methods:{init:function(){var t=this,e=this.$u.$parent.call(this,"u-dropdown");if(e){this.parent=e,this.activeColor=e.activeColor,this.inactiveColor=e.inactiveColor;var i=e.children.find((function(e){return t===e}));i||e.children.push(this),1==e.children.length&&(this.active=!0),e.menuList.push({title:this.title,disabled:this.disabled})}},cellClick:function(t){this.$emit("input",t),this.parent.close(),this.$emit("change",t)}},mounted:function(){this.init()}};e.default=n},"457a":function(t,e,i){"use strict";var n=i("4a4a"),o=i.n(n);o.a},4648:function(t,e,i){"use strict";i.r(e);var n=i("44fd"),o=i.n(n);for(var a in n)"default"!==a&&function(t){i.d(e,t,(function(){return n[t]}))}(a);e["default"]=o.a},"4a4a":function(t,e,i){var n=i("ed42");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var o=i("4f06").default;o("9044ec58",n,!0,{sourceMap:!1,shadowMode:!1})},"50d4":function(t,e,i){var n=i("24fb");e=n(!1),e.push([t.i,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 行为相关颜色 */\n/* 文字基本颜色 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */.top_div[data-v-6495f47e]{width:100%;height:45px;background:-webkit-gradient(linear,left top,left bottom,from(#fedb4c),to(#ffc54d));background:-webkit-linear-gradient(#fedb4c,#ffc54d);background:linear-gradient(#fedb4c,#ffc54d);display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between;-webkit-box-align:center;-webkit-align-items:center;align-items:center}.itemitem[data-v-6495f47e]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between;width:49%;margin-bottom:10px}.mar5px[data-v-6495f47e]{margin:5px 0}.shopitem[data-v-6495f47e]{width:100%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between;margin-bottom:20px}.shopitem_img[data-v-6495f47e]{width:100%;height:%?300?%;-webkit-border-radius:8px;border-radius:8px}.vip[data-v-6495f47e]{background-color:#ffc24d;color:#333;font-size:12px;width:28px;padding:0 4px;-webkit-border-radius:10px;border-radius:10px;margin-left:6px}.shopsName[data-v-6495f47e]{width:%?300?%;overflow:hidden;white-space:nowrap;text-overflow:ellipsis}.hotsearh[data-v-6495f47e]{padding:0 20px 0 20px}.hotsearh .hotsearh_name[data-v-6495f47e]{font-size:%?28?%;color:#999;margin-bottom:%?24?%}.hotsearh .hissearh[data-v-6495f47e]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between}.hotsearh .hotsearh_list[data-v-6495f47e]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:start;-webkit-justify-content:flex-start;justify-content:flex-start;-webkit-flex-wrap:wrap;flex-wrap:wrap}.hotsearh .hotsearh_list .hotsearh_list_item[data-v-6495f47e]{padding:%?16?% %?28?%;background-color:#f5f5f5;font-size:%?28?%;color:#333;-webkit-border-radius:%?20?%;border-radius:%?20?%;margin-bottom:%?20?%;margin-right:2px}.hopsearch[data-v-6495f47e]{-webkit-box-sizing:border-box;box-sizing:border-box;width:100%;overflow-y:auto;-webkit-overflow-scrolling:touch}.hopsearch .list_box[data-v-6495f47e]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-flex-wrap:wrap;flex-wrap:wrap;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between;padding:0 10px 20px 10px}.hopsearch .list_box .item_style[data-v-6495f47e]{width:50%}.hopsearch .search_Box[data-v-6495f47e]{margin:20px 10px;margin-bottom:%?56?%;position:relative;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between;-webkit-box-align:center;-webkit-align-items:center;align-items:center}.hopsearch .search_Box .search_put[data-v-6495f47e]{background-color:#f5f5f5;height:%?72?%;color:#999;padding-left:%?80?%;-webkit-border-radius:%?40?%;border-radius:%?40?%;-webkit-box-flex:1;-webkit-flex:1;flex:1}.hopsearch .search_Box .search_icon[data-v-6495f47e]{position:absolute;top:%?14?%;left:%?32?%}.hopsearch .search_Box .search_btn[data-v-6495f47e]{color:#333;font-size:%?32?%;font-weight:700;margin-left:%?28?%}.hopsearch .search_Box .clearPutVal[data-v-6495f47e]{position:absolute;right:%?110?%}.hopsearch .search_list[data-v-6495f47e]{width:100%}.hopsearch .search_list .Sort_box[data-v-6495f47e]{margin-bottom:%?50?%;color:#333;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-justify-content:space-around;justify-content:space-around}.search_list_item[data-v-6495f47e]{width:50%}',""]),t.exports=e},"5bd2":function(t,e,i){var n=i("50d4");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var o=i("4f06").default;o("c4eee7d4",n,!0,{sourceMap:!1,shadowMode:!1})},"5c24":function(t,e,i){"use strict";i.r(e);var n=i("42c7"),o=i("85df");for(var a in o)"default"!==a&&function(t){i.d(e,t,(function(){return o[t]}))}(a);i("fb78");var r,s=i("f0c5"),l=Object(s["a"])(o["default"],n["b"],n["c"],!1,null,"74b24a0a",null,!1,n["a"],r);e["default"]=l.exports},6062:function(t,e,i){"use strict";var n=i("6d61"),o=i("6566");t.exports=n("Set",(function(t){return function(){return t(this,arguments.length?arguments[0]:void 0)}}),o)},6566:function(t,e,i){"use strict";var n=i("9bf2").f,o=i("7c73"),a=i("e2cc"),r=i("0366"),s=i("19aa"),l=i("2266"),c=i("7dd0"),u=i("2626"),d=i("83ab"),f=i("f183").fastKey,h=i("69f3"),p=h.set,v=h.getterFor;t.exports={getConstructor:function(t,e,i,c){var u=t((function(t,n){s(t,u,e),p(t,{type:e,index:o(null),first:void 0,last:void 0,size:0}),d||(t.size=0),void 0!=n&&l(n,t[c],t,i)})),h=v(e),b=function(t,e,i){var n,o,a=h(t),r=g(t,e);return r?r.value=i:(a.last=r={index:o=f(e,!0),key:e,value:i,previous:n=a.last,next:void 0,removed:!1},a.first||(a.first=r),n&&(n.next=r),d?a.size++:t.size++,"F"!==o&&(a.index[o]=r)),t},g=function(t,e){var i,n=h(t),o=f(e);if("F"!==o)return n.index[o];for(i=n.first;i;i=i.next)if(i.key==e)return i};return a(u.prototype,{clear:function(){var t=this,e=h(t),i=e.index,n=e.first;while(n)n.removed=!0,n.previous&&(n.previous=n.previous.next=void 0),delete i[n.index],n=n.next;e.first=e.last=void 0,d?e.size=0:t.size=0},delete:function(t){var e=this,i=h(e),n=g(e,t);if(n){var o=n.next,a=n.previous;delete i.index[n.index],n.removed=!0,a&&(a.next=o),o&&(o.previous=a),i.first==n&&(i.first=o),i.last==n&&(i.last=a),d?i.size--:e.size--}return!!n},forEach:function(t){var e,i=h(this),n=r(t,arguments.length>1?arguments[1]:void 0,3);while(e=e?e.next:i.first){n(e.value,e.key,this);while(e&&e.removed)e=e.previous}},has:function(t){return!!g(this,t)}}),a(u.prototype,i?{get:function(t){var e=g(this,t);return e&&e.value},set:function(t,e){return b(this,0===t?0:t,e)}}:{add:function(t){return b(this,t=0===t?0:t,t)}}),d&&n(u.prototype,"size",{get:function(){return h(this).size}}),u},setStrong:function(t,e,i){var n=e+" Iterator",o=v(e),a=v(n);c(t,e,(function(t,e){p(this,{type:n,target:t,state:o(t),kind:e,last:void 0})}),(function(){var t=a(this),e=t.kind,i=t.last;while(i&&i.removed)i=i.previous;return t.target&&(t.last=i=i?i.next:t.state.first)?"keys"==e?{value:i.key,done:!1}:"values"==e?{value:i.value,done:!1}:{value:[i.key,i.value],done:!1}:(t.target=void 0,{value:void 0,done:!0})}),i?"entries":"values",!i,!0),u(e)}}},"68ab":function(t,e,i){"use strict";var n=i("5bd2"),o=i.n(n);o.a},"6bf0":function(t,e,i){"use strict";i.r(e);var n=i("c11b"),o=i.n(n);for(var a in n)"default"!==a&&function(t){i.d(e,t,(function(){return n[t]}))}(a);e["default"]=o.a},7614:function(t,e,i){"use strict";var n=i("b661"),o=i.n(n);o.a},"7b6a":function(t,e,i){"use strict";i.r(e);var n=i("d987"),o=i("bcc7");for(var a in o)"default"!==a&&function(t){i.d(e,t,(function(){return o[t]}))}(a);i("7614");var r,s=i("f0c5"),l=Object(s["a"])(o["default"],n["b"],n["c"],!1,null,"1748a5d8",null,!1,n["a"],r);e["default"]=l.exports},"82ce":function(t,e,i){var n=i("24fb");e=n(!1),e.push([t.i,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 行为相关颜色 */\n/* 文字基本颜色 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */',""]),t.exports=e},"85df":function(t,e,i){"use strict";i.r(e);var n=i("9dff"),o=i.n(n);for(var a in n)"default"!==a&&function(t){i.d(e,t,(function(){return n[t]}))}(a);e["default"]=o.a},"87b4":function(t,e,i){"use strict";var n=i("4ea4");i("a630"),i("d81d"),i("4e82"),i("d3b7"),i("6062"),i("3ca3"),i("ddb0"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var o=n(i("985f")),a=n(i("4b10")),r=n(i("4325")),s=n(i("8fec")),l={components:{uniCombox:s.default,msDropdownMenu:a.default,msDropdownItem:r.default,uniCountdown:o.default},data:function(){return{searchVal:"",clearPngFlag:!1,seachFlag:!0,candidates:[{label:"综合排序",value:1},{label:"按销量",value:2},{label:"价格从低到高",value:3},{label:"价格从高到低",value:4}],list2:[],value1:"",value2:"",value3:0,secoundId:0,goodList:[],sort:"normal",idbig:0,d:0,h:0,m:0,s:0,man:"",title1:"综合排序",title2:"全部分类",firstSearch:"",hotWord:[],s_h_list:[],coupon_id:"",page:1,scroll:""}},onReachBottom:function(){console.log("触底")},methods:{del_h_list:function(){var t=this;uni.showModal({title:"提示",content:"确认删除历史记录？",success:function(e){e.confirm?(uni.removeStorageSync("s_h_list"),t.s_h_list=[]):uni.showToast({icon:"none",title:"取消删除"})}})},getOneGood:function(t){this.searchVal=t,this.getSearch("normal"," "," "),this.firstSearch=2},getHot:function(){var t=this;this.$Request({url:"/api/common/hotSearch ",data:{token:uni.getStorageSync("token")}}).then((function(e){console.log("热门搜索结果"),200==e.data.code&&(t.hotWord=e.data.data)}))},change1:function(t){var e=this;console.log(t),this.man?(this.value1=t,this.candidates.map((function(i,n){t==i.value&&(e.title1=i.label)})),1==t&&this.getSearch("normal",this.man.id,""),2==t&&this.getSearch("sales",this.man.id,""),3==t&&this.getSearch("price_low",this.man.id,""),4==t&&this.getSearch("price_high",this.man.id,"")):(this.value1=t,this.candidates.map((function(i,n){t==i.value&&(e.title1=i.label)})),1==t&&this.getSearch("normal",""),2==t&&this.getSearch("sales",""),3==t&&this.getSearch("price_low",""),4==t&&this.getSearch("price_high",""))},change2:function(t){var e=this;this.man?(this.value2=t,this.list2.map((function(i,n){t==i.value&&(e.title2=i.label,e.getSearch("normal",e.man.id,i.id,e.coupon_id))}))):(this.value2=t,this.list2.map((function(i,n){t==i.value&&(e.title2=i.label,e.getSearch("normal"," ",i.id,e.coupon_id))})))},computedTime:function(t,e){console.log(t,e,"传入参数");var i=Date.parse(new Date(t)),n=Date.parse(new Date(e)),o=n-i,a=Math.floor(o/864e5),r=o%864e5,s=Math.floor(r/36e5),l=r%36e5,c=Math.floor(l/6e4),u=l%6e4,d=Math.floor(u/1e3);this.d=a,this.h=s,this.m=c,this.s=d,console.log(this.h,this.s,this.m,s,c,d,"计算后的秒")},getindex:function(t){console.log(t.target)},goItem:function(t){uni.navigateTo({url:"../ShopMsg/ShopMsg?goodsid="+t.id})},getPutVal:function(){""!==this.searchVal?this.clearPngFlag=!0:(this.clearPngFlag=!1,this.firstSearch=1,this.goodList=[],this.getHot(),this.s_h_list=JSON.parse(uni.getStorageSync("s_h_list")))},input:function(t){console.log(t),0==t?this.sort="normal":1==t?this.sort="sales":2==t?this.sort="price_low":3==t?this.sort="price_high":this.secoundId=t,this.getSecound()},ClearPutvals:function(){this.searchVal=""},getSearchVal:function(){var t=this;if(""!=this.searchVal){if(this.secoundId="",this.sort="",uni.getStorageSync("s_h_list")){var e=JSON.parse(uni.getStorageSync("s_h_list"));e.map((function(i,n){t.searchVal!==i&&e.push(t.searchVal)}));var i=Array.from(new Set(e));uni.setStorageSync("s_h_list",JSON.stringify(i))}else{var n=[];n.push(this.searchVal),uni.setStorageSync("s_h_list",JSON.stringify(n))}this.man?this.getSearch("",this.man.id,""):this.getSearch()}},getSearch:function(t,e,i,n){var o=this,a=this;this.$Request({url:"/api/goods/goodsListByCategory",data:{token:uni.getStorageSync("token"),page:this.page,keywords:this.searchVal,fullcut:e||"",sort:t||"normal",category_id:i||"",coupon_id:this.coupon_id||""}}).then((function(t){console.log(t,"索索结构"),o.firstSearch=2,a.goodList=t.data.data.goods}))},getfeilei:function(){var t=this;this.$Request({url:"/api/category/all_category",data:{token:uni.getStorageSync("token")}}).then((function(e){var i=0;e.data.data.list.map((function(e,n){e.son_list.map((function(e,n){var o={label:e.name,value:i++,id:e.id};t.list2.push(o)}))}))}))},getSecound:function(){var t=this;this.$Request({url:"/api/goods/goodsListByCategory",data:{token:uni.getStorageSync("token"),category_id:this.secoundId,keywords:this.searchVal,sort:this.sort,page:1,fullcut:""}}).then((function(e){console.log("跳转后二级数据",e),200==e.data.code&&(t.goodList=e.data.data.goods)}))},getScroll:function(){this.scroll=document.body.scrollTop,console.log(this.scroll,"2222"),console.log("11112222222")}},mounted:function(){window.addEventListener("scroll",this.getScroll())},onHide:function(){this.scroll=document.body.scrollTop,uni.setStorageSync("topscroll",this.scroll),console.log("销毁时",this.scroll),console.log(this.$refs.listbox,"列表组件1111")},beforeDestroy:function(){},onShow:function(){document.body.scrollTop=uni.getStorageSync("topscroll")},onLoad:function(t){console.log(t,"大分类"),t.data&&(this.man=JSON.parse(t.data),this.getSearch("normal",this.man.id,"")),1==t.firstSearch&&(this.getHot(),console.log(t.firstSearch,"didididi"),this.firstSearch=t.firstSearch,this.s_h_list=JSON.parse(uni.getStorageSync("s_h_list"))),this.computedTime(Date.parse(new Date),1e3*this.man.endtime),t.id?(this.secoundId=t.id,this.idbig=t.idbig,this.getSecound(),this.getfeilei()):this.getfeilei(),t.cou_id&&(this.coupon_id=t.cou_id,this.firstSearch=t.firstSearch,this.getSearch("normal","","",this.coupon_id))}};e.default=l},8954:function(t,e,i){"use strict";i.r(e);var n=i("87b4"),o=i.n(n);for(var a in n)"default"!==a&&function(t){i.d(e,t,(function(){return n[t]}))}(a);e["default"]=o.a},"9dff":function(t,e,i){"use strict";i("a9e3"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n={name:"u-cell-item",props:{icon:{type:String,default:""},title:{type:[String,Number],default:""},value:{type:[String,Number],default:""},label:{type:[String,Number],default:""},borderBottom:{type:Boolean,default:!0},borderTop:{type:Boolean,default:!1},hoverClass:{type:String,default:"u-cell-hover"},arrow:{type:Boolean,default:!0},center:{type:Boolean,default:!1},required:{type:Boolean,default:!1},titleWidth:{type:[Number,String],default:""},arrowDirection:{type:String,default:"right"},titleStyle:{type:Object,default:function(){return{}}},valueStyle:{type:Object,default:function(){return{}}},labelStyle:{type:Object,default:function(){return{}}},bgColor:{type:String,default:"transparent"},index:{type:[String,Number],default:""},useLabelSlot:{type:Boolean,default:!1},iconSize:{type:[Number,String],default:34},iconStyle:{type:Object,default:function(){return{}}}},data:function(){return{}},computed:{arrowStyle:function(){var t={};return"up"==this.arrowDirection?t.transform="rotate(-90deg)":"down"==this.arrowDirection?t.transform="rotate(90deg)":t.transform="rotate(0deg)",t}},methods:{click:function(){this.$emit("click",this.index)}}};e.default=n},adda:function(t,e,i){var n=i("24fb");e=n(!1),e.push([t.i,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 行为相关颜色 */\n/* 文字基本颜色 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */.u-cell[data-v-74b24a0a]{\ndisplay:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;flex-direction:row;\n-webkit-box-align:center;-webkit-align-items:center;align-items:center;position:relative;-webkit-box-sizing:border-box;box-sizing:border-box;width:100%;padding:%?26?% %?32?%;font-size:%?28?%;line-height:%?54?%;color:#606266;background-color:#fff;text-align:left}.u-cell_title[data-v-74b24a0a]{font-size:%?28?%}.u-cell__left-icon-wrap[data-v-74b24a0a]{margin-right:%?10?%;font-size:%?32?%}.u-cell__right-icon-wrap[data-v-74b24a0a]{margin-left:%?10?%;color:#969799;font-size:%?28?%}.u-cell__left-icon-wrap[data-v-74b24a0a],\n.u-cell__right-icon-wrap[data-v-74b24a0a]{\ndisplay:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;flex-direction:row;\n-webkit-box-align:center;-webkit-align-items:center;align-items:center;height:%?48?%}.u-cell-border[data-v-74b24a0a]:after{position:absolute;-webkit-box-sizing:border-box;box-sizing:border-box;content:" ";pointer-events:none;border-bottom:1px solid #e4e7ed;right:0;left:0;top:0;-webkit-transform:scaleY(.5);transform:scaleY(.5)}.u-cell-border[data-v-74b24a0a]{position:relative}.u-cell__label[data-v-74b24a0a]{margin-top:%?6?%;font-size:%?26?%;line-height:%?36?%;color:#909399;word-wrap:break-word}.u-cell__value[data-v-74b24a0a]{overflow:hidden;text-align:right;vertical-align:middle;color:#909399;font-size:%?26?%}.u-cell__title[data-v-74b24a0a],\n.u-cell__value[data-v-74b24a0a]{-webkit-box-flex:1;-webkit-flex:1;flex:1}.u-cell--required[data-v-74b24a0a]{overflow:visible;\ndisplay:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;flex-direction:row;\n-webkit-box-align:center;-webkit-align-items:center;align-items:center}.u-cell--required[data-v-74b24a0a]:before{position:absolute;content:"*";left:8px;margin-top:%?4?%;font-size:14px;color:#fa3534}.u-cell_right[data-v-74b24a0a]{line-height:1}',""]),t.exports=e},b661:function(t,e,i){var n=i("fa11");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var o=i("4f06").default;o("6023e728",n,!0,{sourceMap:!1,shadowMode:!1})},bcc7:function(t,e,i){"use strict";i.r(e);var n=i("1274"),o=i.n(n);for(var a in n)"default"!==a&&function(t){i.d(e,t,(function(){return n[t]}))}(a);e["default"]=o.a},c11b:function(t,e,i){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n={name:"u-cell-group",props:{title:{type:String,default:""},border:{type:Boolean,default:!0},titleStyle:{type:Object,default:function(){return{}}}},data:function(){return{index:0}}};e.default=n},cd4e:function(t,e,i){"use strict";i.r(e);var n=i("ffae"),o=i("4648");for(var a in o)"default"!==a&&function(t){i.d(e,t,(function(){return o[t]}))}(a);i("2ebe");var r,s=i("f0c5"),l=Object(s["a"])(o["default"],n["b"],n["c"],!1,null,"2090bd3b",null,!1,n["a"],r);e["default"]=l.exports},d987:function(t,e,i){"use strict";i.d(e,"b",(function(){return o})),i.d(e,"c",(function(){return a})),i.d(e,"a",(function(){return n}));var n={uIcon:i("1f47").default},o=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",{staticClass:"u-dropdown"},[i("v-uni-view",{staticClass:"u-dropdown__menu",class:{"u-border-bottom":t.borderBottom},style:{height:t.$u.addUnit(t.height)}},t._l(t.menuList,(function(e,n){return i("v-uni-view",{key:n,staticClass:"u-dropdown__menu__item",on:{click:function(e){e.stopPropagation(),arguments[0]=e=t.$handleEvent(e),t.menuClick(n)}}},[i("v-uni-view",{staticClass:"u-flex"},[i("v-uni-text",{staticClass:"u-dropdown__menu__item__text",style:{color:e.disabled?"#c0c4cc":n===t.current||t.highlightIndex==n?t.activeColor:t.inactiveColor,fontSize:t.$u.addUnit(t.titleSize)}},[t._v(t._s(e.title))]),i("v-uni-view",{staticClass:"u-dropdown__menu__item__arrow",class:{"u-dropdown__menu__item__arrow--rotate":n===t.current}},[i("u-icon",{attrs:{"custom-style":{display:"flex"},name:t.menuIcon,size:t.$u.addUnit(t.menuIconSize),color:n===t.current||t.highlightIndex==n?t.activeColor:"#c0c4cc"}})],1)],1)],1)})),1),i("v-uni-view",{staticClass:"u-dropdown__content",style:[t.contentStyle,{transition:"opacity "+t.duration/1e3+"s linear",top:t.$u.addUnit(t.height),height:t.contentHeight+"px"}],on:{touchmove:function(e){e.stopPropagation(),e.preventDefault(),arguments[0]=e=t.$handleEvent(e)},click:function(e){arguments[0]=e=t.$handleEvent(e),t.maskClick.apply(void 0,arguments)}}},[i("v-uni-view",{staticClass:"u-dropdown__content__popup",style:[t.popupStyle],on:{click:function(e){e.stopPropagation(),e.preventDefault(),arguments[0]=e=t.$handleEvent(e)}}},[t._t("default")],2),i("v-uni-view",{staticClass:"u-dropdown__content__mask"})],1)],1)},a=[]},ed42:function(t,e,i){var n=i("24fb");e=n(!1),e.push([t.i,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 行为相关颜色 */\n/* 文字基本颜色 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */.u-cell-box[data-v-0e487a9c]{width:100%}.u-cell-title[data-v-0e487a9c]{padding:%?30?% %?32?% %?10?% %?32?%;font-size:%?30?%;text-align:left;color:#909399}.u-cell-item-box[data-v-0e487a9c]{background-color:#fff;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;flex-direction:row}',""]),t.exports=e},ef34:function(t,e,i){"use strict";i.r(e);var n=i("ef38"),o=i("8954");for(var a in o)"default"!==a&&function(t){i.d(e,t,(function(){return o[t]}))}(a);i("68ab");var r,s=i("f0c5"),l=Object(s["a"])(o["default"],n["b"],n["c"],!1,null,"6495f47e",null,!1,n["a"],r);e["default"]=l.exports},ef38:function(t,e,i){"use strict";i.d(e,"b",(function(){return o})),i.d(e,"c",(function(){return a})),i.d(e,"a",(function(){return n}));var n={uniCountdown:i("c697").default,uDropdown:i("7b6a").default,uDropdownItem:i("cd4e").default},o=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-uni-view",{staticClass:"hopsearch"},[n("v-uni-view",{staticStyle:{margin:"10px 0",width:"100%"}},[t.man?n("v-uni-view",{staticClass:"top_div"},[n("v-uni-view",{staticStyle:{"font-weight":"bold","margin-left":"10px"}},[t._v("满"+t._s(t.man.full_price)+"减"+t._s(t.man.cut_price))]),n("uni-countdown",{staticStyle:{"margin-right":"10px"},attrs:{"background-color":"#ffffff","border-color":"#ffffff",color:"#ffffff","show-day":!0,day:t.d,hour:t.h,minute:t.m,second:t.s}})],1):t._e()],1),n("v-uni-view",{staticStyle:{position:"fixed",top:"44px",width:"100%","z-index":"2"}},[n("v-uni-view",{staticClass:"search_Box"},[n("v-uni-input",{staticClass:"search_put",attrs:{type:"text",placeholder:"搜索商家, 商品名称"},on:{input:function(e){arguments[0]=e=t.$handleEvent(e),t.getPutVal.apply(void 0,arguments)}},model:{value:t.searchVal,callback:function(e){t.searchVal=e},expression:"searchVal"}}),n("v-uni-text",{staticClass:"search_btn",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.getSearchVal.apply(void 0,arguments)}}},[t._v("搜索")]),n("v-uni-image",{staticClass:"search_icon",staticStyle:{width:"44rpx",height:"44rpx"},attrs:{src:i("15c4")}})],1)],1),1==t.firstSearch?n("v-uni-view",{staticClass:"hotsearh"},[n("v-uni-view",{staticClass:"hotsearh_name"},[n("v-uni-text",[t._v("热门搜索")])],1),n("v-uni-view",{staticClass:"hotsearh_list",staticStyle:{"margin-top":"36px"}},t._l(t.hotWord,(function(e,i){return n("v-uni-view",{key:i,staticClass:"hotsearh_list_item",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.getOneGood(e.name)}}},[n("v-uni-text",[t._v(t._s(e.name))])],1)})),1),n("v-uni-view",{staticClass:"hotsearh_name hissearh"},[n("v-uni-text",[t._v("历史搜索")]),n("v-uni-image",{staticStyle:{width:"48rpx",height:"48rpx"},attrs:{src:i("2238")},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.del_h_list.apply(void 0,arguments)}}})],1),n("v-uni-view",{staticClass:"hotsearh_list"},t._l(t.s_h_list,(function(e,i){return n("v-uni-view",{key:i,staticClass:"hotsearh_list_item",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.getOneGood(e)}}},[n("v-uni-text",[t._v(t._s(e))])],1)})),1)],1):t._e(),t.firstSearch>1?n("v-uni-view",{staticClass:"search_list"},[n("v-uni-view",{staticClass:"Sort_box"},[n("u-dropdown",{attrs:{"active-color":"#FF4A26"}},[n("u-dropdown-item",{staticStyle:{color:"#FF4A26"},attrs:{title:t.title1,options:t.candidates},on:{change:function(e){arguments[0]=e=t.$handleEvent(e),t.change1.apply(void 0,arguments)}},model:{value:t.value1,callback:function(e){t.value1=e},expression:"value1"}}),n("u-dropdown-item",{staticStyle:{color:"#FF4A26"},attrs:{title:t.title2,options:t.list2},on:{change:function(e){arguments[0]=e=t.$handleEvent(e),t.change2.apply(void 0,arguments)}},model:{value:t.value2,callback:function(e){t.value2=e},expression:"value2"}})],1)],1)],1):t._e(),n("v-uni-view",{staticClass:"list_box",staticStyle:{"overflow-y":"scroll","margin-top":"64px"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.getindex.apply(void 0,arguments)}}},t._l(t.goodList,(function(e,i){return n("v-uni-view",{key:i,staticClass:"itemitem",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.goItem(e)}}},[n("v-uni-image",{staticClass:"shopitem_img mar5px",attrs:{src:e.images[0]}}),n("v-uni-text",{staticClass:"shopsName mar5px"},[t._v(t._s(e.name))]),1==e.vip_switch?n("v-uni-view",{staticClass:"price_box mar5px",staticStyle:{"margin-top":"0px"}},[n("v-uni-text",{staticStyle:{color:"#FF5454","font-size":"12px"}},[t._v("$"),n("v-uni-text",{staticStyle:{"font-size":"16px"}},[t._v(t._s(e.specs_text[0].vip_price))])],1),n("v-uni-text",{staticClass:"vip"},[t._v("VIP")])],1):t._e(),n("v-uni-text",[t._v("$"+t._s(e.specs_text[0].price))])],1)})),1)],1)},a=[]},f10b:function(t,e,i){var n=i("82ce");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var o=i("4f06").default;o("01ab6c06",n,!0,{sourceMap:!1,shadowMode:!1})},fa11:function(t,e,i){var n=i("24fb");e=n(!1),e.push([t.i,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 行为相关颜色 */\n/* 文字基本颜色 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */.u-dropdown[data-v-1748a5d8]{-webkit-box-flex:1;-webkit-flex:1;flex:1;width:100%;position:relative}.u-dropdown__menu[data-v-1748a5d8]{\ndisplay:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;flex-direction:row;\nposition:relative;z-index:11;height:%?80?%}.u-dropdown__menu__item[data-v-1748a5d8]{-webkit-box-flex:1;-webkit-flex:1;flex:1;\ndisplay:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;flex-direction:row;\n-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;-webkit-box-align:center;-webkit-align-items:center;align-items:center}.u-dropdown__menu__item__text[data-v-1748a5d8]{font-size:%?28?%;color:#606266}.u-dropdown__menu__item__arrow[data-v-1748a5d8]{margin-left:%?6?%;-webkit-transition:-webkit-transform .3s;transition:-webkit-transform .3s;transition:transform .3s;transition:transform .3s,-webkit-transform .3s;-webkit-box-align:center;-webkit-align-items:center;align-items:center;\ndisplay:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;flex-direction:row\n}.u-dropdown__menu__item__arrow--rotate[data-v-1748a5d8]{-webkit-transform:rotate(180deg);transform:rotate(180deg)}.u-dropdown__content[data-v-1748a5d8]{position:absolute;z-index:8;width:100%;left:0;bottom:0;overflow:hidden}.u-dropdown__content__mask[data-v-1748a5d8]{position:absolute;z-index:9;background:rgba(0,0,0,.3);width:100%;left:0;top:0;bottom:0}.u-dropdown__content__popup[data-v-1748a5d8]{position:relative;z-index:10;-webkit-transition:all .3s;transition:all .3s;-webkit-transform:translate3D(0,-100%,0);transform:translate3D(0,-100%,0);overflow:hidden}',""]),t.exports=e},fb78:function(t,e,i){"use strict";var n=i("1b62"),o=i.n(n);o.a},ffae:function(t,e,i){"use strict";i.d(e,"b",(function(){return o})),i.d(e,"c",(function(){return a})),i.d(e,"a",(function(){return n}));var n={uCellGroup:i("3c8a").default,uCellItem:i("5c24").default,uIcon:i("1f47").default},o=function(){var t=this,e=t.$createElement,i=t._self._c||e;return t.active?i("v-uni-view",{staticClass:"u-dropdown-item",on:{touchmove:function(e){e.stopPropagation(),e.preventDefault(),arguments[0]=e=t.$handleEvent(e),function(){}.apply(void 0,arguments)},click:function(e){e.stopPropagation(),e.preventDefault(),arguments[0]=e=t.$handleEvent(e),function(){}.apply(void 0,arguments)}}},[t.$slots.default||t.$slots.$default?t._t("default"):[i("v-uni-scroll-view",{style:{height:t.$u.addUnit(t.height)},attrs:{"scroll-y":"true"}},[i("v-uni-view",{staticClass:"u-dropdown-item__options"},[i("u-cell-group",t._l(t.options,(function(e,n){return i("u-cell-item",{key:n,attrs:{arrow:!1,title:e.specs,"title-style":{color:t.value==e.value?t.activeColor:t.inactiveColor}},on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.cellClick(e.value)}}},[t.value==e.value?i("u-icon",{attrs:{name:"checkbox-mark",color:t.activeColor,size:"32"}}):t._e()],1)})),1)],1)],1)]],2):t._e()},a=[]}}]);