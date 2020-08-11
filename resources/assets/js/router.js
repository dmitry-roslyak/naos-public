import Vue from "vue";
import VueRouter from "vue-router";

import AppHeader from "./components/AppHeader.vue";
import App404 from "./components/App404.vue";
import starRating from "vue-star-rating/src/star-rating.vue";

Vue.use(VueRouter);

Vue.component("AppHeader", AppHeader);
Vue.component("star-rating", starRating);

const routes = [
  { name: "AppHome", path: "/" },
  { name: "AppProducts", path: "/products/:category", props: true },
  { name: "AppDetail", path: "/detail/:id", props: true },
  { name: "AppCompare", path: "/compare/:category/:ids", props: true },
  { name: "AppCart", path: "/cart/:ids?", props: true },
  { name: "AppUser", path: "/account" },
  { path: "/login" }, //route for back-end view
  { path: "/logout" }, //route for back-end view
  { path: "/register" }, //route for back-end views
  { path: "/password*" }, //route for back-end view
  { path: "/404", component: App404 },
  { path: "*", redirect: "/404" },
];

routes.forEach((route) => {
  if (route.name) {
    route.component = () => import(/* webpackChunkName: "js/[request]" */ `./components/${route.name}.vue`);
  }
});

export default new VueRouter({
  mode: "history",
  routes,
});
