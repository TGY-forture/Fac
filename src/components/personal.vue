<template>
  <div class="per">
    <div class="sel">
      <button id="admin" @click="trans($event)" :class="{acbtn: ver}">用户信息</button>
      <button id="manage" @click="trans($event)" :class="{acbtn: !ver}">权限设置</button>
    </div>
    <transition name="move" mode="out-in">
      <div class="rig" v-if="mes" key="b1">
        <div class="pri">
          <ul>
            <li>
              <span>账户：</span>
              <input type="text" v-model="mutidat.user" disabled>
            </li>
            <li>
              <span>姓名：</span>
              <input type="text" v-model="mutidat.name">
            </li>
            <li>
              <span>邮箱：</span>
              <input type="text" v-model="mutidat.email">
            </li>
            <li>
              <span>昵称：</span>
              <input type="text" placeholder="请勿超过六个汉字" v-model="mutidat.nickname">
            </li>
            <li>
              <span>住址：</span>
              <input type="text" v-model="mutidat.address">
            </li>
            <li>
              <span>年龄：</span>
              <input type="text" v-model="mutidat.age" placeholder="必填项">
            </li>
            <li>
              <span>员工号：</span>
              <input type="text" v-model="mutidat.worknum" placeholder="员工必填,格式xxxx-xxxx-xxxx">
            </li>
            <li>
              <span>性别：</span>
              <input type="radio" name="sex" value="man" v-model="mutidat.sex"><span class="xin">男</span>
              <input type="radio" name="sex" value="woman" v-model="mutidat.sex"><span class="xin">女</span>
            </li>
          </ul>
          <!-- <button>修改</button> -->
          <button @click="saveProfile">保存</button>
        </div>
        <div class="face">
          <div class="btn">
            <img src="../assets/img/add.svg" alt="logo" @click="upload">
            <input type="file" id="fac" name="pic" @change="load($event)" style="display: none">
            <span>上传头像</span>
          </div>
          <img :src="mutidat.url" alt="" class="dis">
          <button @click="othfun">保存</button>
        </div>
      </div>
      <div class="pow" v-else key="b2">
        <img src="../assets/img/gantanhao.svg" alt="logo">
        <h2>抱歉，您不是管理员！</h2>
      </div>
    </transition>
    <win :dis="vie" :mes="lmes" v-on:hide="vie = false"></win>
  </div>
</template>

<script>
import win from "./win"
let mutidat = {
  user: '',
  name: '',
  email: '',
  nickname: '',
  address: '',
  age: '',
  worknum: '',
  sex: '',
  url: ''
}
export default {
  name: 'personal',
  components: {
    win
  },
  data() {
    return {
      mes: true,
      ver: true,
      mutidat,
      src: '',
      lmes: "",
      vie: null
    }
  },
  watch: {
    'vie': function() {
      if (this.vie == false && !localStorage.getItem('user')) {
        this.$router.push('/home')
      }
    }
  },
  mounted,
  methods: {
    othfun,
    saveProfile,
    upload() {
      document.getElementById('fac').click();
    },
    load(p) {
      let file = p.target.files[0];
      document.getElementsByClassName('dis')[0].src = window.URL.createObjectURL(new Blob([file]));
    },
    trans(ev) {
      if (ev.target.id == 'admin') {
        this.mes = this.ver = true
      }
      else {
        this.mes = this.ver =false
      } 
    }
  }
}
function saveProfile() {
  axios.post('/api/profile.php',this.mutidat).then(
    response => {
      if (response.data == 'success') {
        this.lmes = "保存成功"
        this.vie = true;
      }
      else {
        this.lmes = "保存失败，请检查信息"
        this.vie = true
      }
    }
  ).catch(
    err => {
      console.log(err)
    }
  )
}
function mounted() {
  const vi = this
  const cuse = localStorage.getItem('user')
  if (!cuse) {
    this.vie = true;
    this.lmes = "检测到你没有登录，请前去登录!"
    return
  }
  function reqfir() {
    return axios.post('/api/loadat.php',{ user: cuse})
  }
  function reqsec() {
    return axios.post('/api/loadpic.php',{ user: cuse },{ responseType: 'blob'})
  }
  axios.all([reqfir(), reqsec()]).then(
    axios.spread((res1, res2) => {
      vi.mutidat = res1.data
      const blobsrc = new Blob([res2.data])
      let imgurl = URL.createObjectURL(blobsrc)
      this.mutidat.url = imgurl;
    })
  ).catch(
    err => {
      console.log(err)
    }
  )
}
function savePicture() {  //将图片装换为base64格式保存
  const filereader = new FileReader();
  let file = document.getElementById('fac').files[0]
  filereader.readAsDataURL(file);
  let base64url;
  let user;
  if (localStorage.getItem('user')) {
    user = localStorage.getItem('user')
  }
  filereader.onload = (e) => {
    base64url = e.target.result
    axios.post('/api/uploadpic.php',{
      url: base64url,
      user: user
    }).then(
      response => {
        console.log(response.data)
      }
    ).catch(
      err => {
        console.log(err)
      }
    )
  } 
}
function othfun() {  //直接存储图片二进制数据
  let formins = new FormData()
  const file = document.getElementById('fac').files[0]
  if (!file) return;
  formins.append('pic',file)
  formins.append('user', this.mutidat.user)
  const config = {
    header: {'Content-Type': 'multipart/form-data'}
  }
  axios.post('/api/formdat.php',formins,config).then(
    response => {
      if (response.data == "success") {
        const dire = new Blob([file])
        let img = URL.createObjectURL(dire)
        this.$store.commit({
          type: 'csrc',
          url: img
        })
        this.lmes = "设置成功"
        this.vie = true; 
      }
      else {
        this.lmes = "设置失败"
        this.vie = true;
      }  
    }
  ).catch(
    err => {
      console.log(err)
    }
  )
}

</script>

<style scoped>
.move-enter-active, .move-leave-active {
  transition: all 0.2s cubic-bezier(0.6, 0.04, 0.98, 0.335);
}
.move-enter, .move-leave-to {
  opacity: 0;
  transform: scale(0.2, 0.2);
}
.acbtn {
  background-color: #0abde3;
}
.per {
  width: 1200px;
  margin: 20px auto;
  border: 1px solid gray;
  border-radius: 5px;
}
.sel {
  margin: 40px 0 0 160px;
}
.sel button {
  width: 100px;
  height: 40px;
  border: none;
  cursor: pointer;
  outline: none;
}
.sel button:first-child {
  border-right: 1px solid #0abde3;
  border-radius: 4px 0 0 4px;
}
.sel button:nth-child(2) {
  border-radius: 0 4px 4px 0;
}
.pri {
  margin: 20px 0 0 160px;
  width: 500px;
  padding-top: 20px;
}
li {
  margin-bottom: 20px;
}
li input[type="text"] {
  width: 240px;
  height: 30px;
  margin-left: 15px;
  border: 1px solid gray;
  border-radius: 5px;
  outline: none;
  text-indent: 10px;
}
li input[type="radio"] {
  margin-left: 20px;
}
.pri button {
  width: 120px;
  height: 30px;
  border-radius: 5px;
  border: 1px solid grey;
  outline: none;
  cursor: pointer;
  margin: 20px 0 30px 90px;
  background-color: #CAD3C8;
}
.xin {
  vertical-align: text-bottom;
}
.rig {
  display: flex;
}
.face {
  margin: 80px 0 0 100px;
  width: 300px;
  height: 200px;
  text-align: center;
}
.btn img {
  width: 30px;
  height: 30px;
  vertical-align: middle;
  margin-right: 10px;
}
.dis {
  display: block;
  width: 100px;
  height: 100px;
  margin: 10px auto;
  border-radius: 50%;
  border: 1px solid gray;
}
.face button {
  width: 100px;
  height: 25px;
  border: 1px solid grey;
  outline: none;
  border-radius: 4px;
  cursor: pointer;
  margin-top: 10px;
}
.pow {
  text-align: center;
  width: 300px;
  margin: 100px auto;
}
.pow img {
  display: block;
  margin: 0 auto;
}
</style>