<style lang="scss" scoped>
.mainTitle {
  font-family: "Mulish-ExtraBold", serif;
  color: $baseStrongPurple !important;
  font-size: 54px;
}

.formBlock {
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.14);
  border-radius: 10px;

  .inputLabel {
    font-family: "Mulish-Bold", serif;
    color: $baseStrongPurple !important;
    font-size: 15px;
  }

  .submitBtn {
    font-family: "Inter-SemiBold", serif;
    background-color: $basePurple !important;
  }

  .row > .col {
    min-width: 100%;
  }

  .noAccountText {
    font-family: "Mulish-Regular", serif;
    color: $baseStrongPurple !important;

    a {
      color: #005AE1;
      text-decoration: underline;
    }
  }
}
</style>

<template>
  <div class="d-flex flex-column flex-center align-items-center h-100">
    <div class="mainTitle mb-3">Connectez vous</div>
    <q-card class="col-4 formBlock px-3 py-4">
      <q-card-section>
        <q-form class="q-gutter-md" @submit="onSubmit" autocorrect="off" autocapitalize="off" autocomplete="off" spellcheck="false">
          <div class="inputLabel ms-2 mt-0">Email</div>
          <q-input outlined v-model="form.email" label="Ex: tom.dupont@gmail.com" class="q-mt-sm q-mb-md q-gutter-none ms-2 pb-1"
                   type="email"
                   :rules="[val => val && val.length > 0 || 'Veuillez rentrer un email', val => validateEmail(val) || 'Email invalide']" lazy-rules/>
          <div class="inputLabel ms-2 mt-0">Mot de passe</div>
          <q-input type="password" outlined v-model="form.password" label="*********"
                   class="q-mt-sm q-mb-md q-gutter-none ms-2 pb-1"
                   :rules="[val => val && val.length > 0 || 'Veuillez rentrer un mot de passe', val => val.length >= 4 || 'Minimum 4 caractère']" lazy-rules />
          <q-btn unelevated size="lg" type="submit" no-caps class="full-width submitBtn text-white" label="Connexion" />
        </q-form>
      </q-card-section>
      <q-card-section class="text-center q-pa-none">
        <p class="noAccountText">Vous n'avez pas encore de compte ? <router-link to="/inscription">Inscrivez vous</router-link></p>
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
import { defineComponent } from 'vue'
import axios from 'axios'

export default defineComponent({
  name: 'LoginPage',
  data () {
    return {
      form: {
        email: null,
        password: null
      }
    }
  },
  methods: {
    onSubmit () {
      const self = this
      axios.post('http://127.0.0.1:8000/api/login', this.form)
        .then(function (response) {
          localStorage.setItem('token', JSON.stringify(response.data))
          self.$router.push({ name: 'dashboard' })
        })
        .catch(function (error) {
          localStorage.removeItem('token')
          console.log(error)
        })
    },
    validateEmail (email) {
      const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
      return re.test(String(email).toLowerCase())
    }
  }
})
</script>
