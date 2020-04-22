<template>
  <div style="position: relative">
    <div class="sign top">
      <h1>注册</h1>
      <p>没有账号？那就赶快注册一个吧！</p>
      <form action="" method="post" @submit.prevent>
        <ul>
          <li>
            <p class="email doub">邮箱</p>
            <input
              type="email"
              id="ema"
              name="eamil"
              v-model="nema"
              required
              placeholder="邮箱必须存在"
              @blur="losePon($event)"
              @focus="getPon($event)"
            />
            <img :src="correcturl" alt="" id="fic">
          </li>
          <li>
            <p class="u-pos">用户名</p>
            <input
              type="text"
              id="user-sign"
              name="username"
              v-model="nuser"
              required
              placeholder="十一位数字"
              @blur="losePon($event)"
              @focus="getPon($event)"
            />
            <img :src="securl" alt="" id="sec">
          </li>
          <li>
            <p class="doub">密码</p>
            <input
              id="inpass"
              type="password"
              name="password"
              v-model="npass"
              required
              autocomplete
              placeholder="八位,包含数字,字母,特殊字符"
            />
            <img src="../assets/img/eyen.svg" alt="" class="eyeu" @click="ctype($event)">
          </li>
          <li>
            <p class="p-pos">确认密码</p>
            <input id="repass" type="password" v-model="renpas" required autocomplete />
            <img src="../assets/img/eyen.svg" alt="" class="eyed" @click="ctype($event)">
          </li>
          <li>
            <button type="submit" @click="testUserInfor">注册</button>
          </li>
          <li>
            <span style="color: grey">已有账户?</span>
            <span class="jump" @click="deliver">登录</span>
          </li>
        </ul>
      </form>
    </div>
    <win :dis="vie" :mes="lmes" v-on:hide="vie = false"></win>
  </div>
</template>

<script>
import { docCookies } from "../assets/cookie";
import right from '../assets/img/correct.svg'
import error from '../assets/img/formerr.svg'
import win from "./win"

export default {
  name: "sign",
  props: ["tmp"],
  components: {
    win
  },
  data() {
    return {
      nat: this.tmp,
      nema: "",
      nuser: "",
      npass: "",
      renpas: "",
      correcturl: " ",
      securl: " ",
      vie: null,
      lmes: ''
    };
  },
  watch: {
    tmp: function(val) {
      this.nat = val;
    }
  },
  methods: {
    deliver() {
      this.$emit("close", !this.nat);
      this.nuser = this.nema = this.npass = this.renpas = "";
      document.getElementById('fic').style.visibility = 'hidden'
      document.getElementById('sec').style.visibility = 'hidden'
    },
    testUserInfor,
    ctype,
    losePon,
    getPon
  }
};
function testUserInfor() {
  const short = {
    user: this.nuser,
    password: this.npass,
    email: this.nema
  };
  const reguser = /^\d{11}$/;
  const regnpass = /^(?=.*[0-9])(?=.*[a-zA-Z])(?=.*[\W]).{8}$/;
  if (this.nema && this.nuser && this.npass && this.renpas) {
    if (this.npass != this.renpas) {
      this.vie = true;
      this.lmes = "密码不匹配"
      return
    }
    else if (!regnpass.test(this.npass)) {
      this.vie = true;
      this.lmes = "密码不符合要求。"
      return
    }
  }
  if (reguser.test(this.nuser) == true && regnpass.test(this.npass) == true && this.renpas === this.npass) {
    axios.post("/api/sign.php", short).then(response => {
        if (response.data == "success") {
          document.getElementById('fic').style.visibility = 'hidden'
          document.getElementById('sec').style.visibility = 'hidden'
          this.vie = true;
          this.lmes = "注册成功"
          this.nuser = this.nema = this.npass = this.renpas = "";  
        } else if (response.data == "exist") {
          this.vie = true;
          this.lmes = "用户名已存在"
        }
      })
      .catch(err => {
        console.log(err);
      });
  }
}
function ctype(e) {
  if (e.target.className == "eyeu") {
    const attr = document.getElementById('inpass').getAttribute('type')
    if (attr == 'password') {
      document.getElementById('inpass').setAttribute('type', 'text')
    }
    else {
      document.getElementById('inpass').setAttribute('type', 'password')
    }
  }
  else {
    const attr = document.getElementById('repass').getAttribute('type')
    if (attr == 'password') {
      document.getElementById('repass').setAttribute('type', 'text')
    }
    else {
      document.getElementById('repass').setAttribute('type', 'password')
    }
  }
}
function losePon(e) {
  let tes;
  if (e.target.id == 'ema') {
    tes = /^.+@.+$/
    if (this.nema) {
      if (tes.test(this.nema)) {
        this.correcturl = right
      }
      else {
        this.correcturl = error
      }
      document.querySelector('ul li:first-child img').style.visibility = 'visible'
    }
  }
  else {
    tes = /^\d{11}$/
    if (this.nuser) {
      if (tes.test(this.nuser)) {
        this.securl = require('../assets/img/correct.svg')
      }
      else {
        this.securl = require('../assets/img/formerr.svg')
      }
      document.querySelector('ul li:nth-child(2) img').style.visibility = 'visible'
    }
  }
  
}
function getPon(e) {
  if (e.target.id == 'ema') {
    document.querySelector('ul li:first-child img').style.visibility = 'hidden'
  }
  else {
    document.querySelector('ul li:nth-child(2) img').style.visibility = 'hidden'
  }
}



</script>

<style scoped>
.top {
  width: 1200px;
  margin: 20px auto;
  padding-top: 40px;
  border: 1px solid gray;
  border-radius: 6px;
  text-align: center;
}
li {
  margin: 25px 0;
}
.doub {
  position: relative;
  left: -135px;
  margin-bottom: 10px;
}
input {
  width: 300px;
  height: 40px;
  border-radius: 6px;
  border: 1px solid grey;
  outline: none;
  text-indent: 1em;
  padding-right: 35px;
  box-sizing: border-box;
}
.email {
  margin: 30px 0 10px 0;
}
.u-pos {
  position: relative;
  left: -130px;
  margin-bottom: 10px;
}
.p-pos {
  position: relative;
  left: -120px;
  margin-bottom: 10px;
}
button {
  width: 150px;
  height: 40px;
  border-radius: 5px;
  border-style: none;
  cursor: pointer;
  background-color: #00a8ff;
  outline: none;
}
.jump {
  margin-left: 40px;
  color: #00a8ff;
  cursor: pointer;
}
.eyeu, .eyed {
  position: relative;
  left: -35px;
  width: 25px;
  height: 25px;
  vertical-align: middle;
  cursor: pointer;
}
.eyeu:active, .eyed:active {
  background-color: grey;
  border-radius: 4px;
}
#fic, #sec {
  vertical-align: middle;
  width: 25px;
  height: 25px;
  visibility: hidden;
}
</style>
