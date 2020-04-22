<template>
  <transition name="fade">
    <div class="loading" v-show="show">
      <div class="dot fir"></div>
      <div class="dot sec"></div>
      <div class="dot thr"></div>
      <div class="dot four"></div>
    </div>
  </transition>
</template>

<script>
export default {
  name: 'loading',
  props: ['show']
}
</script>

<style scoped>
@keyframes anleft {
  0% {
    transform: scale(0, 0);
  }
  25% {
    transform: scale(0.25, 0.25);
  }
  50% {
    transform: scale(0.5, 0.5);
  }
  75% {
    transform: scale(0.25, 0.25);
  }
  100% {
    transform: scale(0, 0);
  }
}
.fade-enter-active, .fade-leave-active {
  transition: all .5s ease;
}
.fade-enter, .fade-leave-to {
  transform: translateY(30px);
  opacity: 0;
}
.loading {
  position: fixed;
  left: calc((100% - 320px)/2);
  top: calc((100% - 80px)/2);
  display: flex;
}
.dot {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  animation: anleft .8s infinite linear;
}
.fir {
  background-color: red;
  animation-delay: -0.3s;
}
.sec {
  background-color: #0652DD;
  animation-delay: -0.6s;
}
.thr {
  background-color: #009432;
  animation-delay: -0.9s;
}
.four {
  background-color: orange;
  animation-delay: -1.2s;
}
</style>