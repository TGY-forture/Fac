<template>
  <transition name="fade">
    <div v-show="change" class="win">
      <h3>消息提示</h3>
      <hr>
      <button @click="hide">确定</button>
      <slot><p>{{mes}}</p></slot>
    </div>
  </transition>
</template>

<script>
export default {
  name: 'win',
  props: ['dis','mes'],
  computed: {
    change: {
      get: function(){
        return this.dis;
      }
    }
  },
  methods: {
    hide() {
      this.$emit('hide')
    }
  }
}
</script>

<style scoped>
.fade-enter-active {
  transition: all .3s ease;
}
.fade-leave-active {
  transition: all .3s cubic-bezier(0.785, 0.135, 0.15, 0.86)
}
.fade-enter, .fade-leave-to {
  transform: translateY(-60px);
  opacity: 0;
}
.win {
  width: 320px;
  height: 210px;
  padding-top: 10px;
  position: fixed;
  left: calc((100% - 320px)/2);
  top: calc((100% - 220px)/2);
  background-color: rgb(233, 226, 226);
  border-radius: 4px;
  text-align: center;
  z-index: 4;
}
hr {
  margin-top: 10px;
  border: 1px solid grey;
}
button {
  position: relative;
  top: 130px;
  width: 120px;
  height: 30px;
  outline: none;
  border-radius: 3px;
  border: 1px solid #006266;
  cursor: pointer;
  background-color: #fff;
}
p {
  transform: translateY(30px);
}
</style>