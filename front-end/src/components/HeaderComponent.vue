<style lang="scss" scoped>
  .mainMenu {
    box-shadow: 0 4px 4px rgba(0, 0, 0, 0.1);
  }

  .loginbtn {
    font-family: "Inter-Medium", serif;
    color: $baseGray !important;
    border-color: $baseGray !important;
  }

  .registerBtn {
    font-family: "Mulish-Bold", serif;
    color: $basePurple !important;
    border-color: $basePurple !important;
  }

  .loggedText {
    color: $baseStrongPurple !important;
    font-family: "Inter-Medium", serif;
  }
</style>

<template>
  <q-header class="mainMenu d-flex justify-between bg-white py-2 px-4">
    <router-link to="/"><img src="~assets/logo.png" alt="logo"></router-link>
    <div class="d-flex">
      <div class="d-flex me-3 align-items-center" v-if="isLoggedIn">
        <div class="loggedText text-capitalize me-3">{{ session.first_name + " " + session.last_name }}</div>
        <q-btn @click="logout" unelevated round color="red" size="sm" icon="logout" title="Déconnexion" style="height: fit-content"/>
      </div>
      <div v-else>
        <q-btn to="/connexion" class="loginbtn my-2 me-4" outline rounded color="primary" size="sm" label="Se connecter" />
        <q-btn to="/inscription" class="registerBtn my-2" outline rounded color="primary" size="sm" label="S’inscrire"/>
      </div>
    </div>
  </q-header>
</template>

<script>
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'HeaderComponent',
  props: ['onData'],
  data () {
    return {
      isLoggedIn: false,
      session: null
    }
  },
  methods: {
    checkIfLoggedIn: function () {
      if (localStorage.getItem('token')) {
        this.session = JSON.parse(localStorage.getItem('token'))
        this.isLoggedIn = true
      }
    },
    logout: function () {
      localStorage.removeItem('token')
      window.location.reload()
    }
  },
  created () {
    this.checkIfLoggedIn()
  },
  watch: {
    $route (to, from) {
      this.checkIfLoggedIn()
    }
  }
})
</script>
