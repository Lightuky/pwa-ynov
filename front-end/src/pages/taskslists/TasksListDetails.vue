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

      &.TskLstActive {
        background-color: #b9b9b9 !important;
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

  .backBtn {
    border-radius: 10px;
    background: #F2F2F2;
    color: #666666;

    &:before {
      box-shadow: none;
    }
  }

  .currTskLstTitle {
    font-family: "Mulish-ExtraBold", serif;
    font-size: 35px;
    color: #38343A;
  }

  .addTaskBtn {
    color: #613973 !important;
  }

  .currTskLstCountTitle {
    font-family: "Mulish-Bold", serif;
    font-size: 30px;
  }

  .taskBlock {
    background: #F2F2F2;
    border-radius: 10px;

    .taskName {
      color: #434343;
      font-family: "SpaceGrotesk-Regular", serif;
      font-size: 16px;
    }

    .taskDesc {
      color: #828282;
      font-family: "SpaceGrotesk-Regular", serif;
      font-size: 11px;
    }

    .taskStatusBox:hover {
      cursor: pointer;
    }
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
          <div class="my-2 sidebarListName text-capitalize py-3" :class="tasksList.id == taskListId ? 'TskLstActive' : ''">
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
          <!-- Si user connecté possède cette taskslist -->
          <template v-if="tasksLists && currentTaskList">
            <div class="d-flex align-items-center justify-between mt-5">
              <div class="d-flex align-items-center">
                <q-btn class="backBtn" padding="sm" to="/dashboard" no-outline size="md" icon="chevron_left" title="Revenir au tableau de bord"/>
                <div class="currTskLstTitle ms-4 text-capitalize">{{ currentTaskList.name }}</div>
              </div>
              <div>
                <div class="col-auto">
                  <q-btn color="grey-7" round flat icon="more_horiz">
                    <q-menu fit anchor="top right" self="top left" auto-close style="overflow: hidden">
                      <q-item clickable>
                        <q-item-section>
                          <router-link class="text-black" :to="'/dashboard/liste-de-taches/' +  currentTaskList.id + '/editer'"
                                       style="text-decoration: none">Éditer</router-link>
                        </q-item-section>
                      </q-item>
                      <q-item clickable>
                        <q-item-section>
                          <router-link class="text-red" :to="'/dashboard/liste-de-taches/' +  currentTaskList.id + '/supprimer'"
                                       style="text-decoration: none">Supprimer</router-link>
                        </q-item-section>
                      </q-item>
                    </q-menu>
                  </q-btn>
                </div>
              </div>
            </div>
            <q-btn :to="'/dashboard/liste-de-taches/' +  currentTaskList.id + '/tache/ajouter'" outline
                   color="primary" no-caps label="+ Ajouter une tâche" class="addTaskBtn mt-4" />

            <div class="currTskLstCountTitle text-black my-4">Tâches - {{ !currTskListData[0] ? "0" : currTskListData[0] }}</div>
            <template v-for="task in currentTaskList.tasks" :key="task.id">
                <div v-if="task.status === 0" class="taskBlock d-flex align-items-center justify-between my-4 p-3">
                  <div class="d-flex align-items-center text-capitalize">
                    <q-icon @click="updateTaskStatus(task)" class="taskStatusBox" :name="task.status === 0 ? 'check_box_outline_blank' : 'check_box'"
                            color="" size="lg" :title="task.status === 0 ? 'Marquer comme complétée' : 'Marquer comme à faire'"></q-icon>
                    <div class="ms-4">
                      <div class="taskName">{{ task.name }}</div>
                      <div class="taskDesc">{{ task.description }}</div>
                    </div>
                  </div>
                  <div class="col-auto">
                    <q-btn color="grey-7" round flat icon="more_horiz">
                      <q-menu fit anchor="top right" self="top left" auto-close style="overflow: hidden">
                        <q-item clickable>
                          <q-item-section>
                            <router-link :to="'/dashboard/liste-de-taches/' +  currentTaskList.id + '/tache/' + task.id + '/editer'"
                                         class="text-black" style="text-decoration: none">Éditer</router-link>
                          </q-item-section>
                        </q-item>
                        <q-item clickable>
                          <q-item-section>
                            <router-link :to="'/dashboard/liste-de-taches/' +  currentTaskList.id + '/tache/' + task.id + '/supprimer'"
                                         class="text-red" style="text-decoration: none">Supprimer</router-link>
                          </q-item-section>
                        </q-item>
                      </q-menu>
                    </q-btn>
                  </div>
                </div>
            </template>

            <div class="currTskLstCountTitle text-black my-4">Tâches complétées - {{ !currTskListData[1] ? "0" : currTskListData[1] }}</div>
            <template v-for="task in currentTaskList.tasks" :key="task.id">
              <div v-if="task.status === 1" class="taskBlock d-flex align-items-center justify-between my-4 p-3">
                <div class="d-flex align-items-center text-capitalize">
                  <q-icon @click="updateTaskStatus(task)" class="taskStatusBox" :name="task.status === 0 ? 'check_box_outline_blank' : 'check_box'"
                          color="" size="lg" :title="task.status === 0 ? 'Marquer comme complétée' : 'Marquer comme à faire'"></q-icon>
                  <div class="ms-4">
                    <div class="taskName">{{ task.name }}</div>
                    <div class="taskDesc">{{ task.description }}</div>
                  </div>
                </div>
                <div class="col-auto">
                  <q-btn color="grey-7" round flat icon="more_horiz">
                    <q-menu fit anchor="top right" self="top left" auto-close style="overflow: hidden">
                      <q-item clickable>
                        <q-item-section>
                          <router-link :to="'/dashboard/liste-de-taches/' +  currentTaskList.id + '/tache/' + task.id + '/editer'"
                                       class="text-black" style="text-decoration: none">Éditer</router-link>
                        </q-item-section>
                      </q-item>
                      <q-item clickable>
                        <q-item-section>
                          <router-link :to="'/dashboard/liste-de-taches/' +  currentTaskList.id + '/tache/' + task.id + '/supprimer'"
                                       class="text-red" style="text-decoration: none">Supprimer</router-link>
                        </q-item-section>
                      </q-item>
                    </q-menu>
                  </q-btn>
                </div>
              </div>
            </template>

          </template>
          <!-- Si user connecté ne possède pas cette taskslist -->
          <template v-else>
            <div class="mainTitle mt-5">Attention 🤚</div>
            <div class="subLabel text-black my-4">Vous n'avez pas accès à cette liste de tâches</div>
            <q-btn to="/dashboard" no-caps class="ctaBtn my-2 text-white mdi-border-radius" color="primary" size="lg" label="Tableau de bord"/>
          </template>
        </template>
        <!-- Si non connecté -->
        <template v-else>
          <div class="mainTitle mt-5">Attention 🤚</div>
          <div class="subLabel text-black my-4">Veuillez vous connecter pour pouvoir accéder aux pages des détails</div>
          <q-btn to="/connexion" no-caps class="ctaBtn my-2 text-white mdi-border-radius" color="primary" size="lg" label="Se connecter"/>
        </template>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent } from 'vue'
import axios from 'axios'

export default defineComponent({
  name: 'TasksListDetails',
  data () {
    return {
      isLoggedIn: false,
      session: null,
      tasksLists: null,
      taskListId: null,
      currentTaskList: null,
      currTskListData: null
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

      const currentId = this.taskListId
      this.currentTaskList = this.tasksLists.filter(function (obj) {
        return obj.id === parseInt(currentId)
      })
      this.currentTaskList = this.currentTaskList[0]

      const currTskListData = this.currTskListData = {}
      this.currentTaskList.tasks.forEach(function (item) {
        currTskListData[item.status] ? currTskListData[item.status]++ : currTskListData[item.status] = 1
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
      this.taskListId = this.$route.params.id
    }
    this.$watch(
      () => this.$route.params,
      () => {
        this.taskListId = this.$route.params.id
        const currentId = this.taskListId
        this.currentTaskList = this.tasksLists.filter(function (obj) {
          return obj.id === parseInt(currentId)
        })
        this.currentTaskList = this.currentTaskList[0]

        const currTskListData = this.currTskListData = {}
        this.currentTaskList.tasks.forEach(function (item) {
          currTskListData[item.status] ? currTskListData[item.status]++ : currTskListData[item.status] = 1
        })
      }
    )
  }
})
</script>
