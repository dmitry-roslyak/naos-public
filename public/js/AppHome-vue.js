(window.webpackJsonp=window.webpackJsonp||[]).push([[8],{tlA3:function(t,s,a){"use strict";a.r(s);var e={catalog:{},items:[],dummyCategory:[]},i={data:function(){return e},computed:{lng:function(){return this.$root.lng},itemPriceResult:function(){var t=this;return function(s){return t.$root.itemPriceResult(s)}}},mounted:function(){this.catalog=window.Laravel.catalog,this.get_random_products()},methods:{get_random_products:function(){var t=this;axios.get("/prod_rnd").then((function(s){t.items=s.data,t.dummyCategory.length=8-Object.keys(t.catalog).length}))}}},r=a("KHd+"),l=Object(r.a)(i,(function(){var t=this,s=t.$createElement,a=t._self._c||s;return a("div",{staticClass:"container"},[a("div",{staticClass:"col-sm-3 col-md-2",staticStyle:{padding:"0px"}},[a("div",{staticClass:"list-group"},[a("a",{staticClass:"list-group-item active"},[a("i",{staticClass:"fa fa-list"}),t._v("\n        "+t._s(t.lng.catalog)+"\n      ")]),t._v(" "),t._l(t.catalog,(function(s,e){return a("router-link",{key:s.id,staticClass:"list-group-item",attrs:{to:{name:"AppProducts",params:{category:e}}}},[t._v("\n        "+t._s(t.lng[e]?t.lng[e]:e)+"\n      ")])})),t._v(" "),t._l(t.dummyCategory,(function(s){return a("a",{key:s,staticClass:"list-group-item"},[t._v(" ")])}))],2)]),t._v(" "),a("div",{staticClass:"col-sm-9 col-md-10"},[a("div",{staticClass:"carousel slide carousel-background-color",attrs:{id:"carousel1","data-ride":"carousel"}},[a("ol",{staticClass:"carousel-indicators"},t._l(t.items,(function(t,s){return a("li",{key:t.id,class:{active:!s},attrs:{"data-target":"#carousel1","data-slide-to":s}})})),0),t._v(" "),a("div",{staticClass:"carousel-inner",attrs:{role:"listbox"}},t._l(t.items,(function(s,e){return a("div",{key:s.id,class:{"item image-wrapper":!0,active:!e}},[a("img",{attrs:{src:"/file/"+s.img_src,alt:s.name}}),t._v(" "),a("div",{staticClass:"carousel-caption carousel-content"},[a("h3",[a("router-link",{attrs:{to:{name:"AppDetail",params:{id:s.id}}}},[t._v(t._s(s.name))])],1),t._v(" "),a("span",{staticStyle:{"font-style":"italic","text-shadow":"0 0 1rem black"}},[t._v(t._s(t.itemPriceResult(s)))])])])})),0),t._v(" "),t._m(0),t._v(" "),t._m(1)])])])}),[function(){var t=this.$createElement,s=this._self._c||t;return s("a",{staticClass:"left carousel-control",attrs:{href:"#carousel1",role:"button","data-slide":"prev"}},[s("span",{staticClass:"icon-prev"}),this._v(" "),s("span",{staticClass:"sr-only"},[this._v("Previous")])])},function(){var t=this.$createElement,s=this._self._c||t;return s("a",{staticClass:"right carousel-control",attrs:{href:"#carousel1",role:"button","data-slide":"next"}},[s("span",{staticClass:"icon-next"}),this._v(" "),s("span",{staticClass:"sr-only"},[this._v("Next")])])}],!1,null,null,null);s.default=l.exports}}]);