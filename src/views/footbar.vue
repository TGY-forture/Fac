<template>
  <div class="foot">
    <span>
      <img src="../assets/img/phone.svg" alt="logo">
      <p>联系电话</p>
    </span> 
    <span>
      <img src="../assets/img/qq.svg" alt="logo">
      <p>QQ</p>
    </span>
    <span>
      <img src="../assets/img/Email.svg" alt="logo">
      <p>电子邮件</p>
    </span>
  </div>
</template>

<script>
export default {
  name: 'footbar'
}
</script>

<style scoped>
.foot {
  width: 1200px;
  height: 80px;
  margin: 10px auto;
  border: 1px solid grey;
  border-radius: 5px;
  text-align: center;
  display: flex;
  align-items: center;
}
img {
  width: 40px;
  height: 40px;
}
span:nth-child(2) {
  margin: 0 90px;
}
span:first-child {
  margin-left: calc((1200px - 348px)/2);
}
</style>