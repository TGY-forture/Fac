<template>
  <div>
    <transition name="slideright">
    <div class="log" v-show="show">
      <h1>登录</h1>
      <p>请先登录,获取更多操作。</p>
      <form action="" @submit.prevent>
        <ul>
          <li>
            <p class="admin">用户名</p>
            <input
              type="text"
              id="user"
              v-model="luser"
              class="sam"
              autocomplete
              required
              placeholder="用户名为十一位数字"
            />
          </li>
          <li>
            <p class="pas">密码</p>
            <input
              type="password"
              id="pass"
              v-model="lpass"
              class="sam"
              required
              autocomplete
            />
          </li>
          <li class="tips">
            <input type="checkbox" v-model="checked" @change="savepass" />
            <span>记住密码</span>
            <span class="for" @click="toHelp">忘记密码?</span>
          </li>
          <li>
            <button type="submit" @click="cmp">登录</button>
          </li>
          <li>
            <span style="color: grey">您还没有账户?</span>
            <span class="sign" @click="divpage">注册</span>
          </li>
        </ul>
      </form>
    </div>
    </transition>
    <transition name="slideright">
      <sign v-bind:tmp="show" v-show="!show" v-on:close="open"></sign>
    </transition>
    <win :dis="vie" :mes="lmes" v-on:hide="vie = false"></win>
  </div>
</template>

<script>
import { docCookies } from "../assets/cookie";
import sign from "./sign.vue";
import win from "./win"
export default {
  name: "log",
  components: {
    sign,
    win
  },
  data() {
    return {
      show: true,
      luser: "",
      lpass: "",
      checked: false,
      vie: null,
      lmes: ''
    };
  },
  mounted,
  methods: {
    divpage() {
      this.show = !this.show;
    },
    open(p) {
      this.show = p;
    },
    cmp,
    savepass,
    toHelp
  }
};

function cmp() {
  let curruser = localStorage.getItem("user");
  if (this.luser && this.lpass) {
    if (curruser == this.luser) {
      this.vie = true
      this.lmes = "当前用户已登录!"
      return;
    }
    const obj = {
      user: this.luser,
      password: this.lpass
    };
    axios.post("/api/log.php", obj).then(response => {
        if (response.data == this.lpass) {
          localStorage.setItem("user", this.luser);
          sessionStorage.setItem("cuser", this.luser);
          this.lpass = "";
          this.$store.commit({
            type: "chlan",
            user: this.luser
          });
          axios.post("/api/loadpic.php",{user: this.luser},{responseType: 'blob'}).then(
            response => {
              const blobsrc = new Blob([response.data])
              this.vie = true
              this.lmes = "登录成功"
              if (blobsrc.size == 0) {
                return
              }
              let imgurl = URL.createObjectURL(blobsrc)
              this.$store.commit({
                type: 'csrc',
                url: imgurl
              })
            }
          ).catch(err => {
            console.log(err);
          })
        } else {
          this.vie = true
          this.lmes = "用户名不存在或密码错误!"
        }
      })
      .catch(err => {
        console.log(err);
      }); 
  }
}

function mounted() {
  const remp = localStorage.getItem("user");
  let reflag = localStorage.getItem("reflag");
  if (reflag && remp) {
    axios
      .post("/api/log.php", {
        user: remp
      })
      .then(response => {
        this.luser = remp;
        this.lpass = response.data;
        this.checked = true;
      })
      .catch(err => {
        console.log(err);
      });
  }
}

function savepass() {
  if (this.checked) {
    localStorage.setItem("reflag", "yes");
  } else if (localStorage.getItem("reflag")) {
    localStorage.removeItem("reflag");
  }
}
function toHelp() {
  this.$router.push("/help")
}


</script>

<style scoped>
.slideright-enter-active {
  transition: all .7s ease;
}
.slideright-leave-active {
transition: all .4s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}
.slideright-enter, .slideright-leave-to {
  transform: translateX(300px);
  opacity: 0;
}
.log {
  width: 1200px;
  margin: 20px auto;
  border: 1px solid gray;
  border-radius: 5px;
  text-align: center;
}
.admin {
  position: relative;
  left: -124px;
  margin: 10px 0;
}
.pas {
  position: relative;
  left: -134px;
  margin: 10px 0;
}
h1 {
  margin: 30px 0;
}
li {
  margin-top: 45px;
}
ul li:last-child {
  margin: 20px;
}
.sam {
  width: 300px;
  height: 40px;
  background-color: none;
  outline: none;
  text-indent: 10px;
  border: 1px solid grey;
  border-radius: 5px;
}
.sam:focus {
  border-color: #2e86de;
}
button {
  width: 140px;
  height: 40px;
  outline: none;
  background-color: #169bd5;
  border-radius: 4px;
  cursor: pointer;
  border-style: none;
}
.tips {
  margin: 10px 0;
}
.for {
  color: #169bd5;
  margin-left: 50px;
  cursor: pointer;
}
.sign {
  color: #169bd5;
  margin-left: 30px;
  cursor: pointer;
}
</style>
