<template>
  <div>
    <notifications position="top center" group="default-top-center"/>
    <router-view></router-view>
  </div>
</template>
<script>
  export default {

  }
</script>
<style lang="scss">
.vue-notification {
  font-size: 15px;
	padding: 10px 15px;
  //.notification-content {}

  //&.success { }
  &.warn { color: #000; }
  //&.info { color: #4a2626; }
  //&.error { }
}
</style>
