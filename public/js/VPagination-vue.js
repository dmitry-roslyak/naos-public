(window.webpackJsonp=window.webpackJsonp||[]).push([[13],{"lSq+":function(e,a,l){"use strict";l.r(a);var u={props:{value:{type:Object,default:function(){return{paginator:{total:0,take:30,skip:0,page:1}}}}},computed:{pageCount:function(){return Math.ceil(this.value.total/this.value.take)||1}},methods:{goTo:function(e){this.value.page=e,this.value.skip=(this.value.page-1)*this.value.take,window.scrollTo(0,0)}}},o=l("KHd+"),v=Object(o.a)(u,(function(){var e=this,a=e.$createElement,l=e._self._c||a;return l("div",[l("ul",{staticClass:"pagination fake-link",staticStyle:{position:"relative",left:"50%","z-index":"1",transform:"translateX(-50%)"}},[l("li",{class:{disabled:1==e.value.page}},[l("a",{on:{click:function(a){e.value.page>1&&e.goTo(e.value.page-1)}}},[e._v("«")])]),e._v(" "),l("li",{directives:[{name:"show",rawName:"v-show",value:1!=e.value.page,expression:"value.page!=1"}]},[l("a",{on:{click:function(a){return e.goTo(1)}}},[e._v("1")])]),e._v(" "),l("li",[l("a",{directives:[{name:"show",rawName:"v-show",value:e.value.page>2&&e.value.page-2!=1&&e.value.page<4,expression:"value.page>2&&value.page-2!=1&&value.page<4"}],on:{click:function(a){return e.goTo(e.value.page-2)}}},[e._v(e._s(e.value.page-2))]),e._v(" "),l("a",{directives:[{name:"show",rawName:"v-show",value:e.value.page>3,expression:"value.page>3"}]},[e._v("...")])]),e._v(" "),l("li",{directives:[{name:"show",rawName:"v-show",value:e.value.page>1&&e.value.page-1!=1,expression:"value.page>1&&value.page-1!=1"}]},[l("a",{on:{click:function(a){return e.goTo(e.value.page-1)}}},[e._v(e._s(e.value.page-1))])]),e._v(" "),l("li",{staticClass:"active"},[l("a",[e._v(e._s(e.value.page))])]),e._v(" "),l("li",{directives:[{name:"show",rawName:"v-show",value:e.value.page+1<e.pageCount,expression:"value.page+1<pageCount"}]},[l("a",{on:{click:function(a){return e.goTo(e.value.page+1)}}},[e._v(e._s(e.value.page+1))])]),e._v(" "),l("li",[l("a",{directives:[{name:"show",rawName:"v-show",value:e.value.page+2<e.pageCount,expression:"value.page+2<pageCount"}],on:{click:function(a){return e.goTo(e.value.page+2)}}},[e._v(e._s(e.value.page+2))]),e._v(" "),l("a",{directives:[{name:"show",rawName:"v-show",value:e.value.page<e.pageCount-3,expression:"value.page<pageCount-3"}]},[e._v("...")])]),e._v(" "),l("li",{directives:[{name:"show",rawName:"v-show",value:e.value.page!=e.pageCount,expression:"value.page!=pageCount"}]},[l("a",{on:{click:function(a){return e.goTo(e.pageCount)}}},[e._v(e._s(e.pageCount))])]),e._v(" "),l("li",{class:{disabled:e.value.page==e.pageCount}},[l("a",{on:{click:function(a){e.value.page<e.pageCount&&e.goTo(e.value.page+1)}}},[e._v("»")])])])])}),[],!1,null,null,null);a.default=v.exports}}]);