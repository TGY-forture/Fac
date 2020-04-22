import Vue from "vue";
import VueRouter from "vue-router";

const log = () => import("../components/log.vue");
const help = () => import("../components/help.vue");
const infor = () => import("../components/infor.vue");
const search = () => import("../components/search.vue");
const personal = () => import("../components/personal.vue");
const write = () => import("../components/write.vue");
const show = () => import("../components/show.vue")

Vue.use(VueRouter);

const routes = [
  {
    path: '',
    redirect: '/home'
  },
  {
    path: '/home',
    component: log,
    name: 'home'
  },
  {
    path: '/write',
    name: 'recorde',
    component: write
  },
  {
    path: "/help",
    component: help
  },
  {
    path: "/infor",
    component: infor
  },
  {
    path: "/search",
    component: search
  },
  {
    path: "/personal",
    component: personal
  },
  {
    path: "/show/:seq",
    component: show
  }
];

const router = new VueRouter({
  mode: "history",
  routes
  // base: '/Factory/proj/dist'
});

export default router;
