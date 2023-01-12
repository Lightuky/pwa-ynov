<template>
  <q-layout view="lHh Lpr lFf">
    <HeaderComponent/>
    <q-page-container style="height: calc(100vh - 60px)">
      <router-view :key="$route.fullPath"/>
    </q-page-container>
  </q-layout>
</template>

<script>
import { defineComponent } from 'vue'
import HeaderComponent from '../components/HeaderComponent.vue'

export default defineComponent({
  name: 'MainLayout',

  components: {
    HeaderComponent
  }
})
</script>
