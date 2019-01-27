webpackJsonp([2],{

/***/ "./node_modules/babel-loader/lib/index.js?{\"cacheDirectory\":true,\"presets\":[[\"env\",{\"modules\":false,\"targets\":{\"browsers\":[\"> 2%\"],\"uglify\":true}}]],\"plugins\":[\"transform-object-rest-spread\",[\"transform-runtime\",{\"polyfill\":false,\"helpers\":false}]]}!./node_modules/vue-loader/lib/selector.js?type=script&index=0!./resources/assets/js/components/comm1.vue":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

var self,
    pid,
    _data = {
    reply: null,
    comments: [],
    rating: 0,
    message: '',
    lng: {},
    paginator: {
        total: 0,
        take: 30,
        skip: 0,
        func: null
    }
};
var formatter = new Intl.DateTimeFormat([], {
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "numeric",
    minute: "numeric"
});
/* harmony default export */ __webpack_exports__["default"] = ({
    mounted: function mounted() {
        self = this;
        pid = this.$parent.$props.id;
        this.lng = window.lng;
        this.paginator.func = this.show_comments;
        this.show_comments();
        window.socket.send(JSON.stringify({
            "event": "pusher:subscribe",
            "data": {
                "channel": 'chat-product' + pid
            } }));
        window.socket.onmessage = function (event) {
            var res = JSON.parse(event.data);
            if (res.event == 'new-message') {
                self.paginator.total++;
                var comment = JSON.parse(res.data);
                comment.created_at = formatter.format(new Date(comment.created_at + 'Z'));
                self.comments.unshift(comment);
            }
        };
    },

    data: function data() {
        return _data;
    },
    methods: {
        stt: function stt(el) {
            $("#leaveMsg").slideToggle();
            $(el.getElementsByClassName('fa-angle-up')[0]).toggle();
            $(el.getElementsByClassName('fa-angle-down')[0]).toggle();
        },
        comment_like: function comment_like(i, el) {
            var like = el.classList.contains('like');
            if (like || el.classList.contains('dislike')) {
                el.getAttribute('data-check') > 0 ? el.setAttribute('data-check', '0') : el.setAttribute('data-check', '1');
                axios.get('/comment_like?id=' + self.comments[i].id + '&x=' + (like ? 1 : -1)).then(function (response) {
                    self.comments[i] = response.data;
                    self.comments[i].created_at = formatter.format(new Date(self.comments[i].created_at + 'Z'));
                    self.$forceUpdate();
                }).catch(function (error) {});
            }
        },
        show_comments: function show_comments() {
            axios.get('/all_comments?id=' + pid + '&skip=' + self.paginator.skip).then(function (response) {
                self.paginator.total = response.data[0];
                self.comments = response.data[1];
                for (var i = 0; i < self.comments.length; i++) {
                    self.comments[i].created_at = formatter.format(new Date(self.comments[i].created_at + 'Z'));
                }
            }).catch(function (error) {});
        },
        leave_comment: function leave_comment() {
            axios.post('leave_comment', {
                rating: self.rating,
                message: self.message,
                pid: pid,
                cid: 0
            }).catch(function (error) {});
        }
    }
});

/***/ }),

/***/ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-d629c944\",\"scoped\":false,\"hasInlineConfig\":true}!./node_modules/vue-loader/lib/selector.js?type=styles&index=0!./resources/assets/js/components/comm1.vue":
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__("./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n#leaveMsg{\n    background-color: white;\n    display: none;\n    margin-top: -24px\n}\n.btn-add-comment{\n    width: 100%;\n    margin-bottom: 15px;\n}\n.like-tab {\n    position: absolute;\n    bottom: 15px;\n    right: 60px;\n}\n.like {\n    color: green;\n}\n.like:hover {\n    text-shadow: 0.1em 0.1em 0.5em lightgreen;\n}\n.dislike {\n    color: red;\n}\n.dislike:hover {\n    text-shadow: 0.1em 0.1em 0.5em lightcoral;\n}\n.like,.dislike[data-check=\"1\"]{\n    -webkit-animation: bounce1 0.35s;\n            animation: bounce1 0.35s;\n}\n.dislike,.like[data-check=\"0\"]{\n    -webkit-animation: bounce0 0.35s;\n            animation: bounce0 0.35s;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/vue-loader/lib/template-compiler/index.js?{\"id\":\"data-v-d629c944\",\"hasScoped\":false,\"buble\":{\"transforms\":{}}}!./node_modules/vue-loader/lib/selector.js?type=template&index=0!./resources/assets/js/components/comm1.vue":
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    { staticClass: "container-fluid" },
    [
      _c("h4", { staticStyle: { "padding-left": "8px" } }, [
        _vm._v(_vm._s(_vm.lng.comments + " " + _vm.paginator.total))
      ]),
      _vm._v(" "),
      _c(
        "button",
        {
          staticClass: "btn btn-primary btn-add-comment",
          on: {
            click: function($event) {
              _vm.stt($event.currentTarget)
            }
          }
        },
        [
          _c("i", {
            staticClass: "fa fa-angle-up font1 pull-right",
            staticStyle: { display: "none" },
            attrs: { "aria-hidden": "true" }
          }),
          _vm._v(" "),
          _c("i", {
            staticClass: "fa fa-angle-down font1 pull-right",
            attrs: { "aria-hidden": "true" }
          }),
          _vm._v(" "),
          _c("i", {
            staticClass: "fa fa-comment-o font1",
            attrs: { "aria-hidden": "true" }
          }),
          _vm._v(" " + _vm._s(_vm.lng.to_comment) + "\n    ")
        ]
      ),
      _vm._v(" "),
      _c("div", { staticClass: "thumbnail", attrs: { id: "leaveMsg" } }, [
        _c("div", { staticClass: "caption" }, [
          _c(
            "div",
            { staticClass: "form-group" },
            [
              _c("label", [_vm._v(_vm._s(_vm.lng.to_rate))]),
              _vm._v(" "),
              _c("star-rating", {
                attrs: {
                  "star-size": 16,
                  rating: _vm.rating,
                  "show-rating": false
                },
                on: {
                  "rating-selected": function($event) {
                    _vm.rating = $event
                  }
                }
              })
            ],
            1
          ),
          _vm._v(" "),
          _c(
            "div",
            {
              staticClass: "form-group",
              staticStyle: { "margin-bottom": "0px" }
            },
            [
              _c("label", [_vm._v(_vm._s(_vm.lng.comment))]),
              _vm._v(" "),
              _c("textarea", {
                directives: [
                  {
                    name: "model",
                    rawName: "v-model",
                    value: _vm.message,
                    expression: "message"
                  }
                ],
                staticClass: "form-control",
                attrs: { rows: "3" },
                domProps: { value: _vm.message },
                on: {
                  input: function($event) {
                    if ($event.target.composing) {
                      return
                    }
                    _vm.message = $event.target.value
                  }
                }
              }),
              _vm._v(" "),
              _c(
                "button",
                {
                  staticClass: "btn btn-default",
                  staticStyle: { "margin-top": "6px", width: "100%" },
                  on: {
                    click: function($event) {
                      _vm.leave_comment()
                    }
                  }
                },
                [_vm._v(_vm._s(_vm.lng.to_comment))]
              )
            ]
          )
        ])
      ]),
      _vm._v(" "),
      _vm._l(_vm.comments, function(comment, i) {
        return _c(
          "div",
          { key: comment.id, staticClass: "panel panel-default" },
          [
            _c(
              "div",
              {
                staticClass: "panel-body",
                staticStyle: { position: "relative" }
              },
              [
                _c("span", { staticClass: "text-primary col-xs-12 col-sm-4" }, [
                  _vm._v(_vm._s(comment.user.name))
                ]),
                _vm._v(" "),
                comment.rating
                  ? _c("star-rating", {
                      staticClass: "col-xs-12 col-sm-3",
                      attrs: {
                        rating: +comment.rating,
                        "star-size": 16,
                        "show-rating": false,
                        "read-only": true
                      }
                    })
                  : _vm._e(),
                _vm._v(" "),
                _c(
                  "span",
                  {
                    staticClass: "pull-right",
                    staticStyle: { "margin-right": "15px" }
                  },
                  [_vm._v(_vm._s(comment.created_at))]
                ),
                _vm._v(" "),
                _c(
                  "div",
                  {
                    staticClass: "col-xs-12",
                    staticStyle: { margin: "10px 0 30px" }
                  },
                  [_vm._v(_vm._s(comment.message))]
                ),
                _vm._v(" "),
                _c(
                  "div",
                  {
                    staticClass: "like-tab",
                    on: {
                      click: function($event) {
                        _vm.comment_like(i, $event.target)
                      }
                    }
                  },
                  [
                    (comment.vote
                    ? comment.vote.is_liked > 0
                    : false)
                      ? _c("i", {
                          staticClass: "fa fa-thumbs-up like",
                          attrs: { "data-check": "1" }
                        })
                      : _c("i", {
                          staticClass: "fa fa-thumbs-o-up like",
                          attrs: { "data-check": "0" }
                        }),
                    _vm._v(" "),
                    _c("i", [_vm._v(_vm._s(comment.like))]),
                    _vm._v(" \n                "),
                    (comment.vote
                    ? comment.vote.is_liked < 0
                    : false)
                      ? _c("i", {
                          staticClass: "fa fa-thumbs-down dislike",
                          attrs: { "data-check": "1" }
                        })
                      : _c("i", {
                          staticClass: "fa fa-thumbs-o-down dislike",
                          attrs: { "data-check": "0" }
                        }),
                    _vm._v(" "),
                    _c("i", [_vm._v(_vm._s(comment.dislike))])
                  ]
                )
              ],
              1
            )
          ]
        )
      }),
      _vm._v(" "),
      _c("pagination", {
        staticClass: "col-xs-12",
        model: {
          value: _vm.paginator,
          callback: function($$v) {
            _vm.paginator = $$v
          },
          expression: "paginator"
        }
      })
    ],
    2
  )
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-d629c944", module.exports)
  }
}

/***/ }),

/***/ "./node_modules/vue-style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-d629c944\",\"scoped\":false,\"hasInlineConfig\":true}!./node_modules/vue-loader/lib/selector.js?type=styles&index=0!./resources/assets/js/components/comm1.vue":
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__("./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-d629c944\",\"scoped\":false,\"hasInlineConfig\":true}!./node_modules/vue-loader/lib/selector.js?type=styles&index=0!./resources/assets/js/components/comm1.vue");
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__("./node_modules/vue-style-loader/lib/addStylesClient.js")("38aacb26", content, false, {});
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../node_modules/css-loader/index.js!../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-d629c944\",\"scoped\":false,\"hasInlineConfig\":true}!../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./comm1.vue", function() {
     var newContent = require("!!../../../../node_modules/css-loader/index.js!../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-d629c944\",\"scoped\":false,\"hasInlineConfig\":true}!../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./comm1.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),

/***/ "./resources/assets/js/components/comm1.vue":
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__("./node_modules/vue-style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-d629c944\",\"scoped\":false,\"hasInlineConfig\":true}!./node_modules/vue-loader/lib/selector.js?type=styles&index=0!./resources/assets/js/components/comm1.vue")
}
var normalizeComponent = __webpack_require__("./node_modules/vue-loader/lib/component-normalizer.js")
/* script */
var __vue_script__ = __webpack_require__("./node_modules/babel-loader/lib/index.js?{\"cacheDirectory\":true,\"presets\":[[\"env\",{\"modules\":false,\"targets\":{\"browsers\":[\"> 2%\"],\"uglify\":true}}]],\"plugins\":[\"transform-object-rest-spread\",[\"transform-runtime\",{\"polyfill\":false,\"helpers\":false}]]}!./node_modules/vue-loader/lib/selector.js?type=script&index=0!./resources/assets/js/components/comm1.vue")
/* template */
var __vue_template__ = __webpack_require__("./node_modules/vue-loader/lib/template-compiler/index.js?{\"id\":\"data-v-d629c944\",\"hasScoped\":false,\"buble\":{\"transforms\":{}}}!./node_modules/vue-loader/lib/selector.js?type=template&index=0!./resources/assets/js/components/comm1.vue")
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources\\assets\\js\\components\\comm1.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-d629c944", Component.options)
  } else {
    hotAPI.reload("data-v-d629c944", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ })

});