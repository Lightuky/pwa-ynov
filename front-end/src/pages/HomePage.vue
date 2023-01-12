<style lang="scss" scoped>
  .mainTitle {
    font-family: "Mulish-ExtraBold", serif;
    color: $baseStrongPurple !important;
    font-size: 64px;
  }

  .subLabel {
    font-family: "Mulish-Regular", serif;
    font-size: 25px;
  }

  .ctaBtn {
    font-family: "Mulish-Bold", serif;
    background-color: $basePurple !important;
  }
</style>

<template>
  <div class="d-flex flex-center h-100">
    <div class="col-5 me-5">
      <div class="mainTitle">Ynov Tasks Manager</div>
      <div class="subLabel text-black my-3">Try out this new lightweight tasks manager for everyday tasks</div>
      <q-btn to="/dashboard" no-caps class="ctaBtn my-2 text-white mdi-border-radius" color="primary" size="md" label="Get started"/>
    </div>
    <img class="col-3" src="~assets/homepage.png" alt="homepage">
  </div>
</template>

<script>
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'HomePage'
})
</script>
