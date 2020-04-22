<template>
  <div class="help">
    <div class="box">
      <img src="../assets/img/step2s.svg" alt="" v-if="sf">
      <img src="../assets/img/step2.svg" alt="" v-else>
      <img src="../assets/img/step2s.svg" alt="" class="ps" v-if="ps">
      <img src="../assets/img/step2.svg" alt="" class="ps" v-else>
      <img src="../assets/img/step3s.svg" alt="" class="pt" v-if="pt">
      <img src="../assets/img/step3.svg" alt="" class="pt" v-else>
      <div class="step1 same">
        <img src="../assets/img/1.svg" alt="">
        <span>选择方式</span>
      </div>
      <div class="step2 same">
        <img src="../assets/img/2.svg" alt="">
        <span>邮箱验证</span>
      </div>
      <div class="step3 same">
        <img src="../assets/img/3.svg" alt="">
        <span>账户验证</span>
      </div>
    </div>
    <transition name="move" mode="out-in">
      <div class="fste" v-if="sf" key="fir">
        <h2>请选择以下方式之一:</h2>
        <input type="radio" name="met" v-model="met" value="old">
        <span>找回原来的密码</span>
        <br>
        <input type="radio" name="met" v-model="met" value="new">
        <span>设置新密码</span>
      </div>
      <div class="sste" v-else-if="ps" key="sec">
        <h2>邮箱验证:</h2>
        <span>邮箱</span>
        <input type="email" v-model="ema">
        <!-- <img src="../assets/img/question.svg" alt="">
        <span>反馈</span> -->
        <br>
        <span>验证码</span>
        <input type="text" v-model="code">
        <button @click="sendEmail">发送验证码</button>
      </div>
      <div class="tste" v-else-if="pt" key="thr">
        <h2>账户验证:</h2>
        <span>账户</span>
        <input type="text" v-model="user">
        <br>
        <div v-show="newp">
          <span>新密码</span>
          <input type="text" v-model="pass">
        </div>
      </div>
    </transition>
    <footer>
      <button @click="prePage">上一步</button>
      <button @click="nextPage">{{cpa}}</button>
    </footer>
    <win :dis="vie" :mes="lmes" v-on:hide="vie = false"></win>
    <cir v-show="wait"></cir>
  </div>
</template>

<script>
import win from "./win"
import cir from "./circle"
import {Email} from "../assets/smtp"
export default {
  name: 'help',
  components: {
    win,
    cir
  },
  data() {
    return {
      sf: true,
      ps: false,
      pt: false,
      met: 'old',
      recnum: 1,
      cpa: "下一步",
      ver: null,
      vie: null,
      lmes: '',
      code: '',
      ema: '',
      wait: false,
      newp: null,
      user: "",
      pass: ""
    }
  },
  methods: {
    nextPage,
    prePage,
    sendEmail,
    lastVer
  }
}
function prePage() {
  const nval = this.recnum
  if (nval == 2) {
    this.recnum -= 1
    this.ps = false;
    this.sf = true;
  }
  else if (nval == 3) {
    this.recnum -= 1
    this.pt = false;
    this.ps = true;
    this.cpa = "下一步"
  }
}
function nextPage() {
  this.lastVer()
  const nme = this.recnum
  if (nme == 1) {
    this.recnum += 1;
    this.sf = false
    this.ps = true;
  }
  else if (nme == 2) {
    if (this.ver == null) {
      this.vie = true;
      this.lmes = "请按步骤完成操作!"
      return
    } 
    else if (this.ver != this.code) {
      this.vie = true
      this.lmes = "验证码不正确"
      return
    }
    this.recnum += 1;
    this.ps = false
    this.pt = true;
    this.cpa = "确定"
    this.code = "";
  }
  if (this.met == "old") {
    this.newp = false
  }
  else {
    this.newp = true;
  }
}
function sendEmail() {
  const yan = /^.+@.+$/
  if (!yan.test(this.ema)) {
    this.lmes = "邮箱不符合要求"
    this.vie = true;
  }
  else {
    this.wait = true
    const rand = Math.random().toString().substr(2, 6)
    this.ver = rand;
    Email.send({
      Host: "smtp.qq.com",
      Username: "1981472184@qq.com",
      Password: "nvkbcegedskofbbf",
      To: this.ema,
      From: "1981472184@qq.com",
      Subject: "密码找回",
      Body: "你的验证码为：" + rand
    }).then(
      message => {
      if (message == 'OK') {
        this.wait = false
        this.vie = true;
        this.lmes = "发送成功！"
      }
      else {
        this.wait = false
        this.vie = true
        this.lmes = "发送失败"
      }
    }).catch(
      err => {
        this.wait = false
        this.vie = true;
        this.lmes = "遇到错误，请反馈！"
      }
    )
  }
}
function lastVer() {
  if (this.recnum != 3) return
  if (
    (this.met == "new" && this.pass != "" && this.user != "") ||
    (this.met == "old" && this.user != "")
  ) {
    this.wait = true;
    axios.post('/api/find.php', {
      user: this.user,
      pass: this.pass
    }).then(
      response => {
        this.wait = false;
        if (response.data == "success") {
          this.lmes = "重置密码成功"
          this.vie = true
          this.ver = null;
        }
        else if (response.data == "failed") {
          this.lmes = "用户名不存在"
          this.vie = true;
        }
        else {
          this.lmes = "你的密码为:" + response.data.password
          this.vie = true;
          this.ver = null;
        }
      }
    ).catch(
      err => {
        this.wait = false;
        console.log(err)
      }
    )
  }
  else {
    this.lmes = "账户或密码不能为空"
    this.vie = true;
  }
}



</script>

<style scoped>
.move-enter, .move-leave-to {
  opacity: 0;
  transform: translateX(-100px);
}
.move-enter-active, .move-leave-active {
  transition: all .5s ease;
}
.help {
  width: 1200px;
  height: 540px;
  margin: 20px auto;
  border: 1px solid gray;
  border-radius: 5px;
}
.box {
  height: 82px;
  display: flex;
  position: relative;
  margin: 20px 40px;
}
.ps {
  position: relative;
  left: -40px;
  z-index: -1;
}
.pt {
  position: relative;
  left: -80px;
  z-index: -2;
}
.same {
  position: absolute;
  top: 20px;
}
.same img {
  width: 40px;
  height: 40px;
  margin: 0 20px;
  vertical-align: middle;
}
.step2 {
  left: 300px;
}
.step3 {
  left: 560px;
}
.fste {
  margin: 70px 150px;
}
.sste {
  margin: 50px 150px;
}
.fste span {
  margin-left: 10px;
}
h2, input {
  margin: 30px 0;
}
footer {
  text-align: center;
  margin: 40px 0;
}
footer button {
  width: 120px;
  height: 40px;
  border: 1px solid gray;
  border-radius: 4px;
  outline: none;
  cursor: pointer;
}
footer button:nth-child(2) {
  margin-left: 150px;
}
footer button:active {
  background-color: grey;
}
.sste input {
  width: 200px;
  height: 30px;
  margin-left: 10px;
  border-radius: 4px;
  outline: none;
  text-indent: 10px;
  border: 1px solid grey;
}
.sste input[type="text"] {
  width: 100px;
  margin-right: 40px;
}
.sste button {
  width: 100px;
  height: 30px;
  color: black;
  border-radius: 5px;
  border: 1px solid #00a8ff;
  outline: none;
  cursor: pointer;
}
/* .sste img {
  width: 30px;
  height: 30px;
  vertical-align: middle;
  margin-left: 200px;
  margin-right: 5px;
  cursor: pointer;
} */
.tste {
  width: 400px;
  margin: 50px auto;
  text-align: center;
}
.tste input {
  width: 180px;
  height: 30px;
  margin-left: 10px;
  border: 1px solid grey;
  border-radius: 5px;
  outline: none;
  text-indent: 10px;
}
</style>