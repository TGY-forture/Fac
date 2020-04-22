<template>
  <div class="tabbar">
    <img src="../assets/img/Factory.svg" alt="" class="logo" title="工厂信息">
      <router-link v-for="item in icons" v-bind:key="item.src" :to="item.path">
        <li>
          <img :src="item.src" alt="logo">
          <p>{{item.text}}</p>
        </li>
      </router-link>
    <div class="sea">
      <input type="text" v-model="snum" placeholder="按序列号搜索">
      <img src="../assets/img/searight.svg" alt="logo" @click="bynum">
    </div>
    <div class="interface">
      <span>欢迎你,{{admin}}</span>
      <img :src="turl" alt="">
      <img src="../assets/img/exit.svg" alt="" title="注销登录" @click="exit">
    </div>
    <win :dis="vie" :mes="lmes" v-on:hide="vie = false"></win>
  </div>
</template>

<script>
import win from "../components/win"
const tem = [
  {
    src: require("../assets/img/home.svg"),
    text: "首页",
    path: "/home"
  },
  {
    src: require("../assets/img/help.svg"),
    text: "帮助",
    path: "/help"
  },
  {
    src: require("../assets/img/inform.svg"),
    text: "信息录入",
    path: "/infor"
  },
  {
    src: require("../assets/img/search.svg"),
    text: "查询",
    path: "/search"
  },
  {
    src: require("../assets/img/Personal.svg"),
    text: "账户",
    path: "/personal"
  }
]
export default {
  name: 'tabbar',
  components: {
    win
  },
  data() {
    return {
      icons: tem,
      snum: '',
      vie: null,
      lmes: ''
    }
  },
  mounted() {
    const refresh = localStorage.getItem('user')
    if (refresh) {
      this.$store.commit({
        type: 'chlan',
        user: refresh
      })
    }
    else {
      this.$store.commit({
        type: 'chlan',
        user: "您暂未登录!"
      })
    }
  },
  computed: {
    admin() {
      return this.$store.state.lang
    },
    turl() {
      return this.$store.state.facesrc
    }
  },
  methods: {
    exit,
    bynum
  }
}
function exit() {
  const eus = localStorage.getItem('user')
  const ess = sessionStorage.getItem('cuser')
  if (eus && ess) {
    localStorage.removeItem('user')
    sessionStorage.removeItem('cuser')
    this.$store.commit({
      type: 'chlan',
      user: "您暂未登录"
    })
    this.$store.commit({
      type: 'csrc',
      url: ""
    })
    if (this.$route.name != 'home') {
      this.$router.push({name: 'home'})
    } 
  }
}
function bynum() {
  const exp = /^\d{9}$/
  if (!exp.test(this.snum)) {
    this.lmes = "序列号不符合要求"
    this.vie = true;
    return
  }
  else {
    this.$router.push('/show/' + this.snum)
    this.snum = ""
  } 
}


</script>

<style scoped>
.tabbar {
  width: 1200px;
  height: 85px;
  margin: 10px auto;
  display: flex;
  align-items: center;
  border: 1px solid gray;
  border-radius: 5px;
}
.logo {
  width: 84px;
  height: 61px;
  margin-left: 20px;
}
li {
  float: left;
  width: 80px;
  text-align: center;
  margin: 0 20px;
  border-radius: 4px;
}
li:hover {
  cursor: pointer;
  background-color: #d2dae2;
}
li img {
  width: 40px;
  height: 40px;
}
.router-link-active li {
  background-color: #c8d6e5;
}
.sea input {
  height: 35px;
  outline: none;
  border-radius: 4px;
  border:1px solid grey;
  text-indent: 10px;
}
.sea input:focus {
  border-color: #3dc1d3;
}
.sea img {
  width: 30px;
  height: 30px;
  vertical-align: middle;
  margin-left: 10px;
  cursor: pointer;
}
.interface img {
  width: 40px;
  height: 40px;
  margin: 0 10px 0 0;
  border-radius: 50%;
  vertical-align: middle;
  cursor: pointer;
}
span {
  margin: 0 10px 0 10px;
}
</style>