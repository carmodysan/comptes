import Vue from "vue";
import VueRouter from "vue-router";
import Home from "../views/Home";
import Test from "../views/Test";
import Months from "../views/Month";
import MonthDetail from "../views/MonthDetail";
import Carrousel from "../views/Carrousel";

Vue.use(VueRouter);

export default new VueRouter({
  mode: "history",
  routes: [
    { path: "/home", component: Home },
    { path: "/test", component: Test },
    { path: "/months", component: Months },
    { path: "/month/:id", component: MonthDetail },
    { path: "/carrousel", component: Carrousel },
    { path: "*", redirect: "/home" }
  ]
});
