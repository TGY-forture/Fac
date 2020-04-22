<template>
  <div class="show">
    <ul>
      <li>
        <span>产品名称:</span>
        <span>{{list.pro_name}}</span>
      </li>
      <li>
        <span>生产日期:</span>
        <span>{{list.pro_time}}</span>
      </li>
      <li>
        <span>生产批次:</span>
        <span>{{list.pro_count}}</span>
      </li>
      <li>
        <span>产品序号:</span>
        <span>{{list.pro_seq}}</span>
      </li>
      <li>
        <span>材料负责人:</span>
        <span>{{list.mat_name}}</span>
      </li>
      <li>
        <span>加工负责人:</span>
        <span>{{list.process_name}}</span>
      </li>
      <li>
        <span>质检负责人:</span>
        <span>{{list.qual_name}}</span>
      </li>
    </ul>
    <win :dis="vie" :mes="lmes" v-on:hide="vie = false"></win>
  </div>
</template>

<script>
import win from "../components/win"
export default {
  name: 'show',
  components: {
    win
  },
  data() {
    return {
      list: {
        pro_name: '',
        pro_time: '',
        pro_count: '',
        pro_seq: '',
        mat_name: '',
        process_name: '',
        qual_name: '',
      },
      vie: null,
      lmes: ""
    }
  },
  mounted() {
    this.seldat(this.$route.params.seq)
  },
  methods: {
    seldat
  },
  watch: {
    $route: function(to, from) {
      const nseq = to.params.seq
      this.seldat(nseq)
    }
  }
}
function seldat(p) {
  axios.post('/api/dshow.php',{seq: p}).then(
    response => {
      if (!response.data) {
        this.lmes = "没有该产品"
        this.vie = true;
      }
      else {
        for (let item in response.data) {
          if (!response.data[item]) {
            this.lmes = "该产品信息不全，暂不支持查看"
            this.vie = true;
            return;
          }
        }
        this.list = response.data;
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
.show {
  width: 1200px;
  height: 520px;
  margin: 20px auto;
  border: 1px solid gray;
  border-radius: 5px;
  font-size: 18px;
  text-align: center;
}
ul {
  margin: 80px auto;
}
ul li {
  margin: 30px 0;
}
li span:nth-child(2) {
  width: 200px;
  margin-left: 50px;
}
</style>