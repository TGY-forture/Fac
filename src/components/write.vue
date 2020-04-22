<template>
  <div class="write">
    <div class="pro">
      <form action="#">
        <ul>
          <li>
            <span>产品序列号：</span>
            <input type="text" v-model="testobj.pro_seq" id="line">
          </li>
          <li>
            <span>产品名称：</span>
            <input type="text" v-model="testobj.pro_name">
          </li>
          <li>
            <span>产品生产日期：</span>
            <input type="text" v-model="testobj.pro_time">
          </li>
          <li>
            <span>产品批次：</span>
            <input type="text" v-model="testobj.pro_count">
          </li>
          <li>
            <span>材料负责人：</span>
            <input type="text" v-model="testobj.mat_name">
          </li>
          <li>
            <span>加工负责人：</span>
            <input type="text" v-model="testobj.process_name">
          </li>
          <li>
            <span>质检责任人：</span>
            <input type="text" v-model="testobj.qual_name">
          </li>
        </ul>
        <button type="button" @click="dataInsert">保存</button>
        <button type="button" @click="createQrcode">生成二维码</button>
      </form>
    </div>
    <div class="code">
      <p>二维码生成区</p>
      <div id="canvas"></div>
      <button @click="saveQrcode">保存到本地</button>
      <router-link to="/home">
        <button>返回首页</button>
      </router-link>
    </div>
    <win :dis="vie" :mes="lmes" v-on:hide="vie = false"></win>
  </div>
</template>

<script>
import QRCode from 'qrcodejs2'
import win from "./win"
export default {
  name: 'write',
  components: {
    win
  },
  data() {
    return {
      testobj: {
        pro_name: "",
        pro_time: "",
        pro_count: "",
        mat_name: "",
        process_name: "",
        qual_name: "",
        pro_seq: ""
      },
      vie: null,
      lmes: '',
      flag: '',
      qrcode: null
    }
  },
  beforeRouteEnter,
  methods: {
    dataInsert,
    createQrcode,
    saveQrcode
  }
}

function dataInsert() {
  axios.post('/api/wsave.php',this.testobj).then(
    response => {
      if (response.data == 'success') {
        this.vie = true;
        this.lmes = "保存成功!"
      }
      else {
        this.vie = true;
        this.lmes = "保存失败！"
      }
    }
  ).catch(
    err => {
      console.log(err)
    }
  )
}

function beforeRouteEnter(to, from, next) {
  if (!to.params.val) {
    next('/infor')
    return
  }
  axios.post('/api/write.php',{seq: to.params.val}).then(
    response => { 
        next((vm) => {
          vm.testobj = response.data
          document.getElementById('line').setAttribute('disabled', true)
        })
    }
  ).catch(
    err => {
      console.log(err)
    }
  )
}
function createQrcode() {
  for (let item in this.testobj) {
    if (this.testobj[item] == '') {
      this.vie = true;
      this.lmes = '信息不全，无法生成二维码。'
      return
    }
  }
  const alqrcode = document.getElementById('canvas').querySelector('img')
  if (alqrcode) {
    this.qrcode.clear()
    this.qrcode.makeCode("http://192.168.0.111:8081/Factory/proj/dist/show/" + this.testobj.pro_seq)
    return
  }
  const el = document.getElementById('canvas')
  const url = {
    width: 300,
    height: 300,
    text: "http://192.168.0.111:8081/Factory/proj/dist/show/" + this.testobj.pro_seq
  };
  this.qrcode = new QRCode(el,url)
  
}
function saveQrcode() {
  const canvas = document.getElementById('canvas').querySelector('img')
  if (!canvas) return
  const imgUrl = canvas.src
  if (window.navigator.msSaveOrOpenBlob) {
    var bstr = atob(imgUrl.split(',')[1])
    var n = bstr.length
    var u8arr = new Uint8Array(n)
    while (n--) {
      u8arr[n] = bstr.charCodeAt(n)
    }
    var blob = new Blob([u8arr])
    window.navigator.msSaveOrOpenBlob(blob, 'QRCode' + '.' + 'png')
  }
  else {
    const tmpa = document.createElement('a');
    tmpa.href = imgUrl;
    tmpa.download = 'QRCode.png'
    tmpa.click()
  }
}


</script>

<style scoped>
#canvas img {
  width: 300px;
  height: 300px;
}
.write {
  width: 1200px;
  margin: 20px auto;
  border: 1px solid grey;
  border-radius: 5px;
  display: flex;
}
.pro {
  width: 50%;
  padding-top: 30px;
  border-right: 1px dashed grey;
}
form {
  width: 400px;
  margin: 0 auto;
}
li {
  margin-top: 30px;
}
input {
  width: 170px;
  height: 30px;
  border: 1px solid #718093;
  border-radius: 5px;
  outline: none;
  margin-left: 30px;
  text-indent: 10px;
}
.code {
  width: 400px;
  height: 400px;
  margin: 60px auto;
  text-align: center;
}
.pro button {
  width: 120px;
  height: 40px;
  border: none;
  border-radius: 5px;
  margin: 25px;
  cursor: pointer;
  outline: none;
  background-color: #8c7ae6;
}
#canvas {
  width: 300px;
  height: 300px;
  margin: 20px auto;
  border: 1px solid black;
}
.code button {
  width: 120px;
  height: 40px;
  background-color: #487eb0;
  border: none;
  border-radius: 5px;
  margin: 10px;
  cursor: pointer;
  outline: none;
}
</style>