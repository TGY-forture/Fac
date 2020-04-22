<template>
  <div class="infor">
    <ul>
      <li><h1>请输入产品序列号</h1></li>
      <li><input id="seq" type="text" v-model="sequence" placeholder="九位数字序列号"></li>
      <li>
        <router-link :to="{name: 'recorde', params: {val: sequence}}">
          <button>确定</button>
        </router-link>
      </li>
    </ul>
    <win :dis="vie" :mes="lmes" v-on:hide="vie = false"></win>
    <loading :show="pro"></loading>
  </div>
</template>

<script>
import win from "./win"
import loading from "./loading"
export default {
  name: 'infor',
  components: {
    win,
    loading
  },
  data() {
    return {
      sequence: null,
      pro: false,
      vie: null,
      lmes: ''
    }
  },
  mounted() {
    const natuser = localStorage.getItem('user')
    axios.post('/api/getwork.php',{
        user: natuser
      }).then(
        response => {
          let set = /^\d{4}-\d{4}-\d{4}$/
          if (!set.test(response.data.worknum)) {
            this.vie = true;
            this.lmes = '你不是员工，无法录入信息。'
            document.getElementById('seq').setAttribute('disabled', true)
          }
        }
      ).catch(
        err => {
          console.log(err)
        }
      )
  },
  methods: {
    xianshi() {
      this.xian = !this.xian
    }
  },
  beforeRouteEnter(to, from, next) {
    const natuser = localStorage.getItem('user') 
    if (!natuser) {
      alert('请先登录')
      return
    }
    else {
      next()
    }
  },
  beforeRouteLeave(to, from, next) {
    if (to.name == 'recorde') {
      const seq = /^\d{9}$/
      if (seq.test(this.sequence)) {
        this.pro = true;
        next()
      }
      else {
        next(false)
        this.vie = true;
        this.lmes = "序列号不满足要求"
      }
    }
    else {
      next()
    }
    
  }
}
</script>

<style scoped>
.infor {
  width: 1200px;
  height: 500px;
  border-radius: 5px;
  border: 1px solid #718093;
  margin: 20px auto;
  text-align: center;
  overflow:hidden;
}
h1 {
  margin-top: 100px;
}
input {
  width: 280px;
  height: 50px;
  font-size: 18px;
  border-radius: 5px;
  border: 1px solid #718093;
  text-indent: 10px;
  margin: 80px 0;
  outline: none;
}
button {
  width: 150px;
  height: 35px;
  border-radius: 5px;
  border: 1px solid #718093;
  outline: none;
  cursor: pointer;
  background-color: #00a8ff;
  margin-bottom: 40px;
}
</style>