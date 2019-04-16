webpackJsonp([8],{"4WfG":function(t,a,e){"use strict";Object.defineProperty(a,"__esModule",{value:!0});var s,n={datasets:[{lineTension:0,backgroundColor:"rgba(255, 99, 132, 0.2)",borderColor:"rgba(255,99,132,1)"}]},i={legend:{display:!1},tooltips:{displayColors:!1},scales:{xAxes:[{type:"time",time:{tooltipFormat:"DD.MM.YY",unitStepSize:1,unit:"day",displayFormats:{hour:"MM.YY",day:"DD.MM.YY",week:"ll",month:"DD.MM.YYYY"}}}]}},r={ready:!1};a.default={props:["productId"],data:function(){return r},computed:{lng:function(){return this.$root.lng}},mounted:function(){s=new Chart(document.getElementById("graph"),{type:"line",data:n,options:i}),this.show_hist(1)},methods:{show_hist:function(t){axios.get("/prod_history",{params:{id:this.productId,currency:window.Laravel.currency.name}}).then(function(a){var e=0,i=a.data.length,r=n.datasets[0].data.length;if(r>0&&r>i&&n.datasets[0].data.splice(0,r),1==t)for(;e<i;e++)n.datasets[0].data[e]={x:a.data[e].date,y:a.data[e].price*a.data[e].currency.rate},n.datasets[0].label="Price";else if(2==t)for(;e<i;e++)n.datasets[0].data[e]={x:a.data[e].date,y:a.data[e].sales},n.datasets[0].label="Sold";s.update()})}}}},"EkG+":function(t,a){t.exports={render:function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("div",[e("div",{staticClass:"panel panel-primary",staticStyle:{margin:"15px 0 0","border-width":"0"}},[e("div",{staticClass:"panel-heading",staticStyle:{padding:"6px 15px"}},[e("i",{staticClass:"fa fa-bar-chart",attrs:{"aria-hidden":"true"}}),t._v(" "),e("span",[t._v(t._s(t.lng.hist_graph)+" ")]),t._v(" "),e("div",{staticClass:"btn-group",attrs:{role:"group","aria-label":"..."}},[e("button",{staticClass:"btn btn-default btn-sm",attrs:{id:"graph_btn1",type:"button"},on:{click:function(a){t.show_hist(1)}}},[t._v(t._s(t.lng.price_hist))]),t._v(" "),e("button",{staticClass:"btn btn-default btn-sm",attrs:{id:"graph_btn2",type:"button"},on:{click:function(a){t.show_hist(2)}}},[t._v(t._s(t.lng.sales_hist))])])]),t._v(" "),t._m(0)])])},staticRenderFns:[function(){var t=this.$createElement,a=this._self._c||t;return a("div",{staticClass:"panel-body",staticStyle:{padding:"4px"}},[a("canvas",{attrs:{height:"70",id:"graph"}})])}]}},nOat:function(t,a,e){var s=e("VU/8")(e("4WfG"),e("EkG+"),!1,null,null,null);t.exports=s.exports}});