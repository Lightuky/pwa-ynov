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
    background-color: #C10707 !important;
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

  .deleteDisclaimer {
    font-family: "Mulish-Regular", serif;
    font-size: 16px;
  }
}
</style>

<template>
  <div class="d-flex flex-column flex-center align-items-center h-100">
    <!-- Si connecté -->
    <template v-if="isLoggedIn">
      <!-- Si user connecté possède la taskslist -->
      <template v-if="taskList !== 'null'">
        <div class="mainTitle mb-3">Supprimer la liste</div>
        <q-card class="col-4 formBlock px-3 py-4">
          <q-card-section>
            <div class="deleteDisclaimer py-4 text-black">Vous êtes sur le point de supprimer votre liste êtes vous sûr de vouloir faire ça ?</div>
            <div class="d-flex justify-content-around">
              <q-btn :to="'/dashboard/liste-de-taches/' + taskListId" unelevated size="lg" no-caps no-outline style="color: #646464" label="Annuler"/>
              <q-btn unelevated size="lg" type="submit" no-caps class="submitBtn text-white" label="Supprimer"/>
            </div>
          </q-card-section>
        </q-card>
      </template>
      <!-- Si user connecté ne possède pas la taskslist -->
      <template v-else>
        <div class="mainTitleAlt mt-5">Attention 🤚</div>
        <div class="subLabel text-black my-4">Vous n'avez pas accès à cette liste de tâches</div>
        <q-btn to="/dashboard" no-caps class="ctaBtn my-2 text-white mdi-border-radius" color="primary" size="lg" label="Tableau de bord"/>
      </template>
    </template>
    <!-- Si non connecté -->
    <template v-else>
      <div class="mainTitleAlt mt-5">Attention 🤚</div>
      <div class="subLabel text-black my-4">Veuillez vous connecter pour pouvoir accéder à la suppression</div>
      <q-btn to="/connexion" no-caps class="ctaBtn my-2 text-white mdi-border-radius" color="primary" size="lg" label="Se connecter"/>
    </template>
  </div>
</template>

<script>
import { defineComponent } from 'vue'
import axios from 'axios'

export default defineComponent({
  name: 'TasksListDelete',
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
      this.taskList = await axios.get('http://127.0.0.1:8000/api/taskslists/' + this.taskListId, { headers: { 'api-key': this.session.api_token } })
        .then(function (response) {
          return response.data
        })
    },
    onSubmit () {
      const self = this
      axios.delete('http://127.0.0.1:8000/api/taskslists/' + this.taskList.id, { headers: { 'api-key': this.session.api_token } })
        .then(function () {
          self.$router.push({ path: '/dashboard/liste-de-taches/' + self.taskListId })
        })
    }
  },
  created () {
    this.taskListId = this.$route.params.taskslistId

    if (localStorage.getItem('token')) {
      this.isLoggedIn = true
      this.session = JSON.parse(localStorage.getItem('token'))
      this.getTasksList()
    }
    this.$watch(
      () => this.$route.params,
      () => {
        this.taskListId = this.$route.params.taskslistId
        this.getTasksList()
      }
    )
  }

})
</script>
