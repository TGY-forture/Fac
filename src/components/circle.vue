<template>
  <div class="load">
    <div class="circle">
      <p></p>
      <p></p>
      <p></p>
      <p></p>
    </div>
    <div class="circle">
      <p></p>
      <p></p>
      <p></p>
      <p></p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'cir'
}
</script>

<style scoped>
.load {
  width: 80px;
  height: 80px;
  position: fixed;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
}
.circle {
  width: 80px;
  height: 80px;
  position: absolute;
}
.circle p {
  width: 15px;
  height: 15px;
  background-color: #0984e3;
  position: absolute;
  border-radius: 50%;
  animation: move 1s infinite linear;
}
@keyframes move {
  from {
    transform: scale(0, 0);
  }
  50% {
    transform: scale(1.2, 1.2);
  }
  to {
    transform: scale(0, 0);
  }
}
.circle p:nth-of-type(1) {
  top: 0;
  left: 0;
  animation-delay: -0.1s;
}
.circle p:nth-of-type(2) {
  top: 0;
  right: 0;
  animation-delay: -0.5s;
}
.circle p:nth-of-type(3) {
  bottom: 0;
  left: 0;
  animation-delay: -0.9s;
}
.circle p:nth-of-type(4) {
  bottom: 0;
  right: 0;
  animation-delay: -1.3s;
}
.circle:nth-of-type(2) p:nth-of-type(1) {
  animation-delay: -1.5s;
}
.circle:nth-of-type(2) p:nth-of-type(2) {
  animation-delay: -0.3s;
}
.circle:nth-of-type(2) p:nth-of-type(3) {
  animation-delay: -0.7s;
}
.circle:nth-of-type(2) p:nth-of-type(4) {
  animation-delay: -1.1s;
}
.circle:nth-of-type(2) {
  transform: rotate(45deg);
}
</style>