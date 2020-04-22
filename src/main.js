import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import "./axios.js"

//同路径路由跳转报错解决方案一
// import Router from 'vue-router'
// const originalPush = Router.prototype.push
// Router.prototype.push = function push(location) {
//   return originalPush.call(this, location).catch(err => err)
// }

Vue.config.productionTip = false;

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')

