<style lang="scss" scoped>
.mainMenu {
  box-shadow: 0 4px 4px rgba(0, 0, 0, 0.1);
  background-color: #b6b6b6 !important;

  .mainTitle {
    font-family: "Mulish-Bold", serif;
    color: $baseGray !important;
    font-size: 30px;
  }
}
</style>

<template>
  <q-header class="mainMenu d-flex justify-center py-2 px-4">
    <img src="~assets/logo.png" alt="logo" height="50" width="50">
    <div class="ms-4 mainTitle">Book Finder App</div>
  </q-header>
</template>

<script>
import { defineComponent } from 'vue'
export default defineComponent({
  name: 'HeaderComponent'
})
</script>
