<template>
  <div>
    <tabbar></tabbar>
    <transition name="slide-fade"><router-view></router-view></transition>
    <footbar></footbar>
    <welcome></welcome>
    <a href="http://www.beian.miit.gov.cn">渝ICP备20004410号-1</a>
  </div>
</template>

<script>
import welcome from "../components/welcome.vue"
import tabbar from "./tabbar.vue"
import footbar from "./footbar.vue"
import {docCookies} from "../assets/cookie"

export default {
  name: 'home',
  components: {
    tabbar,
    footbar,
    welcome
  },
  mounted() {
    const lookuser = localStorage.getItem('user');
    if (lookuser) {
      axios.post("/api/loadpic.php",{user: lookuser},{responseType: 'blob'}).then(
            response => {
              if (response.data.size == 0) return;
              const blobsrc = new Blob([response.data])
              let imgurl = URL.createObjectURL(blobsrc)
              this.$store.commit({
                type: 'csrc',
                url: imgurl
              })
            }
          ).catch(err => {
            console.log(err);
          })
    }
  }
}

</script>

<style scoped>
@import "../assets/animate.css";
.slide-fade-enter-active {
  transition: all .6s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}
.slide-fade-leave-active {
  transition: all .3s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}
.slide-fade-enter, .slide-fade-leave-to {
  transform: translateY(400px);
  opacity: 0;
}
a {
  text-decoration: none;
  color: #000;
  text-align: center;
  display: block;
  width: auto;
}
</style>