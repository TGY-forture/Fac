<template>
  <div class="wel" v-show="int">
    <img src="../assets/img/error.svg" alt="clo" @click="shut">
    <img src="../assets/img/welcome.svg" alt="logo">
    <h3>欢迎你,{{view}}。</h3>
  </div>
</template>

<script>

export default {
  name: 'welcome',
  data() {
    return {
      int: true,
    }
  },
  mounted,
  computed: {
    view() {
      return this.$store.state.lang
    }
  },
  methods: {
    shut() {
      setTimeout(() => {
        this.int = !this.int;
      }, 450);
      document.getElementsByClassName('wel')[0].classList.add('exit');
    }
  }
}
function mounted() {
  const hav = sessionStorage.getItem('cuser')
  const cadmin = localStorage.getItem('user')
  if (cadmin) {
    sessionStorage.setItem('cuser',cadmin)
    this.$store.commit({
      type: 'chlan',
      user: cadmin
    })
  }
  if (hav) {
    this.int = false;
  }
}

</script>

<style scoped>
.wel {
  width: 300px;
  height: 350px;
  border: 1px solid gray;
  background-color: #ecf0f1;
  position: fixed;
  left: 50%;
  top: 50%;
  transform: translate(-50%,-50%);
  text-align: center;
  border-radius: 5px;
  animation: zoomIn .5s;
}
.exit {
  animation: zoomOut .5s;
}
img[alt="clo"] {
  width: 30px;
  height: 30px;
  position: absolute;
  left: 302px;
  top: -20px;
  cursor: pointer;
}
img[alt="logo"] {
  margin-top: 40px;
}
h3 {
  margin: 20px 0;
}
</style>