<template>
  <div class="search">
    <div class="nav">
      <h2>筛选:</h2>
      <span>批次:</span>
      <input type="text" placeholder="八位数字" v-model="ncount" />
      <span class="sa">年份:</span>
      <select name="yea" id="year" v-model="lyear">
        <option :value="lis" v-for="lis in year" :key="lis">{{ lis }}</option>
      </select>
      <span class="sa">月份:</span>
      <select name="mon" id="month" v-model="lmonth">
        <option :value="list" v-for="list in month" :key="list">{{
          list
        }}</option>
      </select>
      <button @click="searchInfor">搜索</button>
    </div>
    <div id="mus">
      <table>
        <tr>
          <td v-for="el in text" :key="el">{{ el }}</td>
        </tr>
      </table>
      <div class="cau">
        <img src="../assets/img/empty.svg" alt="logo" />
        <h4>什么也没有哦！</h4>
      </div>
    </div>
    <div class="foo">
      <button @click="chPage($event)" id="pre">上一页</button>
      <input
        type="text"
        name="page"
        id="page"
        placeholder="0/0"
        @focus="doThis($event)"
        @blur="doThat($event)"
        v-model="jump"
      />
      <span @click="quick">跳转</span>
      <button @click="chPage($event)" id="next">下一页</button>
    </div>
    <cir v-show="csh"></cir>
    <win :dis="vie" :mes="lmes" v-on:hide="vie = false"></win>
  </div>
</template>

<script>
//兼容IE9+  remove()方法
// (function (arr) {
//    arr.forEach(function (item) {
//     if (item.hasOwnProperty('remove')) {
//       return;
//     }
//     Object.defineProperty(item, 'remove', {
//       configurable: true,
//       enumerable: true,
//       writable: true,
//       value: function remove() {
//         this.parentNode.removeChild(this);
//       }
//     });
//   });
// })([Element.prototype, CharacterData.prototype, DocumentType.prototype]);
import win from "./win"
import cir from './circle'
const nam = [
  "产品序号",
  "生产批次",
  "生产日期",
  "产品名称",
  "材料责任人",
  "加工责任人",
  "质检责任人"
];
const nmonth = ["", 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
const nyear = ["", 2010, 2011, 2012, 2013, 2014, 2015, 2016, 2017, 2018, 2019];
export default {
  name: "search",
  components: {
    cir,
    win
  },
  data: function() {
    return {
      text: nam,
      year: nyear,
      month: nmonth,
      ncount: "",
      lyear: "",
      lmonth: "",
      localdat: null,
      localen: null,
      tran: null,
      jump: '',
      csh: false,
      lmes: "",
      vie: null
    };
  },
  methods: {
    searchInfor,
    showData,
    chPage,
    nextDataPro,
    preDatpro,
    quick,
    doThis(event) {
      this.tran = event.target.placeholder;
      event.target.placeholder = "";
    },
    doThat(event) {
      event.target.placeholder = this.tran;
      event.target.value = ''
    }
  }
};
function searchInfor() {
  const rcnt = /^\d{8}$/;
  if (this.ncount == "" && this.lyear == "" && this.lmonth == "") {
    this.vie = true;
    this.lmes = "请至少选择一个条件!"
    return;
  }
  if (!rcnt.test(this.ncount) && this.ncount) {
    this.vie = true;
    this.lmes = "请输入要求的序列号!"
    return;
  }
  deleteTrel();
  this.csh = true
  document.getElementsByClassName('cau')[0].style.display = "none"
  const inforobj = {
    p_year: this.lyear,
    p_count: this.ncount,
    p_month: this.lmonth
  };
  for (let i in inforobj) {
    if (inforobj[i] == "") {
      delete inforobj[i];
    }
  }
  const vm = this;
  axios
    .post("/api/search.php", inforobj)
    .then(response => {
      if (response.data !== "No data!") {
        this.csh = false;
        vm.localdat = response.data;
        vm.localen = response.data.length;
        showData(response.data);
      }
      else {
        this.csh = false;
        document.getElementsByClassName('cau')[0].style.display = "block"
      }
    })
    .catch(err => {
      console.log(err);
    });
}
function showData(dat) {
  const len = dat.length;
  let tlen;
  const pla = document.getElementById("page");
  if (len <= 10) {
    tlen = len;
    pla.placeholder = "1/1";
  } else {
    tlen = 10;
    if (len % 10 == 0) {
      pla.placeholder = "1/" + len / 10;
    } else {
      pla.placeholder = "1/" + Math.floor(len / 10 + 1);
    }
  }
  creTrelement(dat, tlen, 0);
}
function chPage(ev) {
  const nextpla = document.getElementById("page");
  const strpla = nextpla.placeholder.split("/");
  if (ev.target.id == "next") {
    if (strpla[0] == strpla[1] || strpla[1] == "0") return;
    document.getElementById("page").placeholder =
      Number(strpla[0]) + 1 + "/" + strpla[1];
    const learr = this.nextDataPro(strpla[0]);
    deleteTrel();
    creTrelement(this.localdat, learr[1], learr[0]);
  } else {
    if (strpla[0] == "1" || strpla[0] == "0") return;
    document.getElementById("page").placeholder =
      Number(strpla[0]) - 1 + "/" + strpla[1];
    const mr = this.preDatpro(strpla[0]);
    deleteTrel();
    creTrelement(this.localdat, mr[1], mr[0]);
  }
}
function creTrelement(data, length, start) {
  for (let i = start; i < start + length; i++) {
    const rowel = document.createElement("tr");
    rowel.classList.add("tr-flag");
    for (let j = 0; j < 7; j++) {
      let flag = "";
      const tab = document.createElement("td");
      switch (j) {
        case 0:
          flag = "pro_seq";
          break;
        case 1:
          flag = "pro_count";
          break;
        case 2:
          flag = "pro_time";
          break;
        case 3:
          flag = "pro_name";
          break;
        case 4:
          flag = "mat_name";
          break;
        case 5:
          flag = "process_name";
          break;
        case 6:
          flag = "qual_name";
          break;
        default:
          flag = "";
          break;
      }
      tab.innerHTML = data[i][flag];
      tab.style.cssText =
        "border: 1px solid black;" + "width: 110px; height: 30px;";
      rowel.appendChild(tab);
    }
    document.getElementsByTagName("table")[0].appendChild(rowel);
  }
}
function deleteTrel() {
  const fg = document.getElementsByClassName("tr-flag");
  const chang = fg.length;
  if (chang > 0) {
    for (let c = 0; c < chang; c++) {
      fg[0].remove();
    }
  }
}
function nextDataPro(firnum) {
  let arr = [];
  const nelen = this.localen - Number(firnum) * 10;
  arr[0] = Number(firnum) * 10;
  if (nelen > 10) {
    arr[1] = 10;
  } else {
    arr[1] = nelen;
  }
  return arr;
}
function preDatpro(dirnum) {
  let prr = [];
  const count = this.localen - (Number(dirnum) * 10 - 20);
  prr[0] = Number(dirnum) * 10 - 20;
  if (count < 10) {
    prr[1] = count;
  } else {
    prr[1] = 10;
  }
  return prr;
}
function quick() {
  if (!this.jump || !/^\d+$/.test(this.jump)) {
    this.jump = ''
    return
  }
  else {
    const newp = document.getElementById('page').placeholder.split('/')
    const forward = Number(newp[0])
    const backward = Number(newp[1])
    const num = Number(this.jump)
    if (num <= backward && num >= 1) {
      document.getElementById('page').placeholder = this.jump + "/" + backward
      this.jump = ''
      const anum = (num - 1) * 10
      let dist;
      if (num != backward) {
        dist = 10;
      }
      else {
        dist = this.localen - anum;
      }
      deleteTrel()
      creTrelement(this.localdat, dist, anum)
    }
    else {
      this.jump = ""
    }
  }
}
</script>

<style scoped>
.search {
  width: 1200px;
  margin: 0 auto;
  border: 1px solid gray;
  border-radius: 5px;
  margin-top: 20px;
  text-align: center;
}
h2 {
  display: inline;
  margin-right: 20px;
}
.nav {
  margin-top: 50px;
}
.sa {
  margin-left: 40px;
}
.nav button {
  width: 80px;
  height: 30px;
  margin-left: 20px;
  border: 1px solid #b2bec3;
  border-radius: 5px;
  outline: none;
  cursor: pointer;
}
.nav input {
  width: 160px;
  height: 24px;
  outline: none;
  text-indent: 10px;
  border: 1px solid #b2bec3;
  border-radius: 4px;
}
select {
  width: 80px;
  height: 24px;
  outline: none;
  text-indent: 10px;
  border-radius: 4px 4px 0 0;
}
table {
  margin: 40px auto;
  border: 1px solid black;
  border-collapse: collapse;
}
td {
  width: 140px;
  height: 30px;
  border: 1px solid black;
}
.foo button {
  width: 70px;
  height: 30px;
  border: 1px solid #636e72;
  background-color: #f5f6fa;
  border-radius: 4px;
  outline: none;
  cursor: pointer;
}
.foo input {
  width: 100px;
  height: 24px;
  outline: none;
  text-align: center;
  border: 1px solid #636e72;
  border-radius: 4px;
  margin: 20px;
}
.foo span {
  margin-right: 20px;
  color: #2d3436;
  cursor: pointer;
}
#mus {
  padding-top: 10px;
  height: 450px;
}
</style>
