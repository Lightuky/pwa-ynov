<style lang="scss" scoped>
  .mainMenu, .sidebar {
    background-color: #F2F2F2;
  }

  .sidebar {
    .sidebarTitle {
      font-family: "Mulish-Bold", serif;
      color: #333 !important;
      font-size: 22px;
    }

    .addListBtn {
      color: $basePurple !important;
      border-color: $basePurple !important;
      border: 2px;
    }

    .listDisclaimer {
      font-family: "Mulish-Regular", serif;
      font-size: 14px;
    }

    .sidebarListName {
      font-family: "Mulish-Bold", serif;
      font-size: 16px;
      text-decoration: none;

      &:hover {
        background-color: #BDBDBD;
      }

      div {
        text-overflow: ellipsis;
        overflow: hidden;
        color: #2C272E;
      }
    }
  }

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

  .q-menu.q-position-engine.scroll {
    overflow-x: hidden;
  }

  .tasksListCardTitle {
    font-family: "Mulish-Bold", serif;
    font-size: 20px;
    color: #2C272E;
    text-overflow: ellipsis;
    overflow: hidden;
  }

  .seeListBtn {
    text-decoration: none;
    font-family: "Mulish-Regular", serif;
    font-size: 20px;
    color: #2C272E;

    &:hover {
      color: #6c2fb7;
    }
  }

  .q-card {
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.14) !important;
    border-radius: 10px !important;
  }

  .tasksListCardHeader {
    background-color: #F2F2F2;
  }

  .taskStatusBox:hover {
    cursor: pointer;
  }

</style>

<template>
  <div class="row m-0" style="height: calc(100vh - 60px)">
    <div class="sidebar col-2 px-0">
      <div class="d-flex justify-content-between my-5 mx-3 px-2">
        <div class="sidebarTitle">Mes listes</div>
        <q-btn :to="isLoggedIn ? '/dashboard/listes-de-taches/ajouter' : '/connexion'" class="addListBtn" outline color="primary" size="md" icon="add" />
      </div>
      <!-- Si est connecté et possèdes des listes de tâches -->
      <template v-if="isLoggedIn && tasksLists">
        <router-link :to="'/dashboard/liste-de-taches/' + tasksList.id" v-for="tasksList in tasksLists" :key="tasksList.id" style="text-decoration: none">
          <div class="my-2 sidebarListName text-capitalize py-3">
            <div class="mx-3 px-2" :title="tasksList.name">{{ tasksList.name }}</div>
          </div>
        </router-link>
      </template>
      <!-- Si listes de tâches vide ou non connecté -->
      <template v-else>
        <div class="listDisclaimer mx-2 mt-4">Vous n’avez aucune liste, créez-en une première pour commencer en cliquant sur le bouton +</div>
      </template>
    </div>
    <div class="col-10 d-flex justify-content-center">
      <div class="col-7">
        <!-- Si connecté -->
        <template v-if="isLoggedIn">
          <div class="mainTitle mt-5">Hello, <span class="text-capitalize">{{ session.first_name + " " + session.last_name }} 👋</span></div>
          <!-- Si possèdes des listes de tâches -->
          <template v-if="tasksLists">
            <div class="subLabel text-black my-4">Voici les dernières tâches que tu as ajouté</div>

            <q-card v-for="tasksList in tasksLists" :key="tasksList.id" flat class="my-card col-9 my-5">
              <div class="tasksListCardHeader py-3 w-100 row items-center no-wrap ps-4 pe-2 mb-4 q-gutter-none">
                <div class="col px-0">
                  <div class="tasksListCardTitle text-capitalize">{{ tasksList.name }}</div>
                </div>
                <div class="col-auto">
                  <q-btn color="grey-7" round flat icon="more_horiz">
                    <q-menu fit anchor="top right" self="top left" auto-close style="overflow: hidden">
                      <q-item clickable>
                        <q-item-section>
                          <router-link class="text-black" :to="'/dashboard/liste-de-taches/' + tasksList.id" style="text-decoration: none">Voir</router-link>
                        </q-item-section>
                      </q-item>
                      <q-item clickable>
                        <q-item-section>
                          <router-link class="text-black" :to="'/dashboard/liste-de-taches/' + tasksList.id + '/editer'" style="text-decoration: none">Éditer</router-link>
                        </q-item-section>
                      </q-item>
                      <q-item clickable>
                        <q-item-section>
                          <router-link class="text-red" :to="'/dashboard/liste-de-taches/' + tasksList.id + '/supprimer'" style="text-decoration: none">Supprimer</router-link>
                        </q-item-section>
                      </q-item>
                    </q-menu>
                  </q-btn>
                </div>
              </div>

              <!-- Si listes de tâches vide -->
              <template v-if="!tasksList.tasks || !tasksList.tasks.length">
                <q-card-section class="ps-4 py-4">
                  <div>Cette liste ne contient aucune tâche, ajoutez en une depuis la liste</div>
                  <q-btn :to="'/dashboard/liste-de-taches/' + tasksList.id" no-caps
                         class="ctaBtn mb-2 mt-3 text-white mdi-border-radius" color="primary" label="Voir ma liste"/>
                </q-card-section>
              </template>
              <!-- Si listes de tâches possèdes des éléments -->
              <template v-else>
                <template v-for="(task, index) in tasksList.tasks" :key="task.id">
                  <q-card-section class="ps-4 py-2" v-if="index <= 1">
                    <div class="d-flex align-items-center text-capitalize">
                      <q-icon @click="updateTaskStatus(task)" class="taskStatusBox" :name="task.status === 0 ? 'check_box_outline_blank' : 'check_box'"
                              color="" size="lg" :title="task.status === 0 ? 'Marquer comme complétée' : 'Marquer comme à faire'"></q-icon>
                      <div class="ms-4">{{ task.name }}</div>
                    </div>
                  </q-card-section>
                </template>

                <q-separator inset class="bg-dark mt-3" />

                <q-card-actions>
                  <router-link :to="'/dashboard/liste-de-taches/' + tasksList.id" no-caps class="w-auto seeListBtn mx-auto pt-2 pb-3">Voir la liste complête</router-link>
                </q-card-actions>
              </template>
            </q-card>

          </template>
          <!-- Si pas de liste de taches -->
          <template v-else>
            <div class="subLabel text-black my-4">Tu n’as aucune liste de tâches pour le moment, créez-en une en
              cliquant sur le bouton ci-dessous ou dans le menu pour débuter
            </div>
            <q-btn to="/dashboard/liste-de-taches/ajouter" no-caps class="ctaBtn my-2 text-white mdi-border-radius" color="primary" size="lg" label="Créer une liste"/>
          </template>
        </template>
        <!-- Si non connecté -->
        <template v-else>
          <div class="mainTitle mt-5">Hello 👋</div>
          <div class="subLabel text-black my-4">Tu n’as aucune liste de tâches pour le moment, créez-en une en cliquant
            sur le bouton ci-dessous ou dans le menu pour débuter
          </div>
          <q-btn to="/connexion" no-caps class="ctaBtn my-2 text-white mdi-border-radius" color="primary" size="lg" label="Créer une liste"/>
        </template>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent } from 'vue'
import axios from 'axios'

export default defineComponent({
  name: 'DashboardPage',
  data () {
    return {
      isLoggedIn: false,
      session: null,
      tasksLists: null
    }
  },
  methods: {
    getUserTasksLists: async function () {
      this.session = JSON.parse(localStorage.getItem('token'))
      this.isLoggedIn = true
      this.tasksLists = await axios.get('http://127.0.0.1:8000/api/taskslists', { headers: { 'api-key': this.session.api_token } })
        .then(function (response) {
          return response.data
        })
    },
    updateTaskStatus: function (task) {
      task.status = task.status === 1 ? 0 : 1
      axios.put('http://127.0.0.1:8000/api/tasks/' + task.id, task, { headers: { 'api-key': this.session.api_token } })
      this.getUserTasksLists()
    }
  },
  created () {
    if (localStorage.getItem('token')) {
      this.getUserTasksLists()
    }
  }
})
</script>
