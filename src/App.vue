<template>
  <div id="app">
    <home></home>
  </div>
</template>
<script>

import home from "./views/home"
export default {
  name: 'App',
  components: {
    home
  }
}
</script>
<style>
@import "./assets/base.css";
</style>
