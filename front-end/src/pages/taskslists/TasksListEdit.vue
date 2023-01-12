<style lang="scss" scoped>
  .mainTitle {
    font-family: "Mulish-ExtraBold", serif;
    color: $baseStrongPurple !important;
    font-size: 54px;
  }

  .mainTitleAlt {
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
    <!-- Si connecté -->
    <template v-if="isLoggedIn">
      <!-- Si user connecté possède cette taskslist -->
      <template v-if="taskList !== 'null'">
        <div class="mainTitle mb-3">Modifier la liste</div>
        <q-card class="col-4 formBlock px-3 py-4">
          <q-card-section>
            <q-form class="q-gutter-md" @submit="onSubmit" autocorrect="off" autocapitalize="off" autocomplete="off" spellcheck="false">
              <div class="inputLabel ms-2 mt-0">Nom de la liste</div>
              <q-input outlined v-model="taskList.name" label="Ex: Courses" class="q-mt-sm q-mb-md q-gutter-none ms-2 pb-1" type="text"
                       :rules="[val => val && val.length > 0 || 'Veuillez rentrer un nom']" lazy-rules/>
              <div class="d-flex justify-content-around">
                <q-btn :to="'/dashboard/liste-de-taches/' + taskListId" unelevated size="lg" no-caps no-outline style="color: #646464" label="Annuler"/>
                <q-btn unelevated size="lg" type="submit" no-caps class="submitBtn text-white" label="Modifier"/>
              </div>
            </q-form>
          </q-card-section>
        </q-card>
      </template>
      <!-- Si user connecté ne possède pas cette taskslist -->
      <template v-else>
        <div class="mainTitleAlt mt-5">Attention 🤚</div>
        <div class="subLabel text-black my-4">Vous n'avez pas accès à cette liste de tâches</div>
        <q-btn to="/dashboard" no-caps class="ctaBtn my-2 text-white mdi-border-radius" color="primary" size="lg" label="Tableau de bord"/>
      </template>
    </template>
    <!-- Si non connecté -->
    <template v-else>
      <div class="mainTitleAlt mt-5">Attention 🤚</div>
      <div class="subLabel text-black my-4">Veuillez vous connecter pour pouvoir accéder à l'édition</div>
      <q-btn to="/connexion" no-caps class="ctaBtn my-2 text-white mdi-border-radius" color="primary" size="lg" label="Se connecter"/>
    </template>
  </div>
</template>

<script>
import { defineComponent } from 'vue'
import axios from 'axios'

export default defineComponent({
  name: 'TasksListEdit',
  data () {
    return {
      isLoggedIn: false,
      session: null,
      taskListId: null,
      taskList: null
    }
  },
  methods: {
    getTasksList: async function () {
      this.isLoggedIn = true
      this.session = JSON.parse(localStorage.getItem('token'))
      this.taskList = await axios.get('http://127.0.0.1:8000/api/taskslists/' + this.taskListId, { headers: { 'api-key': this.session.api_token } })
        .then(function (response) {
          return response.data
        })
    },
    onSubmit () {
      const self = this
      axios.post('http://127.0.0.1:8000/api/taskslists/' + this.taskList.id, this.taskList, { headers: { 'api-key': this.session.api_token } })
        .then(function () {
          self.$router.push({ path: '/dashboard/liste-de-taches/' + self.taskListId })
        })
    }
  },
  created () {
    this.taskListId = this.$route.params.id
    if (localStorage.getItem('token')) {
      this.getTasksList()
    }
    this.$watch(
      () => this.$route.params,
      () => {
        this.taskListId = this.$route.params.id
        this.getTasksList()
      }
    )
  }
})
</script>
