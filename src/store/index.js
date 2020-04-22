import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)


export default new Vuex.Store({
  state: {
    lang: '',
    facesrc: require("../assets/img/default.svg")
  },
  getters: {

  },
  mutations: {
    chlan(state,payload) {
      state.lang = payload.user
    },
    csrc(state, payload) {
      state.facesrc = payload.url
    }
  },
  actions: {

  }
})
