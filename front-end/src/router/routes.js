const routes = [
  {
    path: '/',
    name: 'baselayout',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      // Base Layout
      { path: '', name: 'homepage', component: () => import('pages/HomePage.vue') },

      // Auth
      { path: '/inscription', name: 'register', component: () => import('pages/RegisterPage') },
      { path: '/connexion', name: 'login', component: () => import('pages/LoginPage') },
      { path: '/mot-de-passe-oublie', name: 'forgotPassword', component: () => import('pages/ForgotPasswordPage') },

      // App
      { path: '/dashboard', name: 'dashboard', component: () => import('pages/DashboardPage') },

      // Taskslists
      { path: '/dashboard/listes-de-taches/ajouter', name: 'taskslistAdd', component: () => import('pages/taskslists/TasksListAdd') },
      { path: '/dashboard/liste-de-taches/:id', name: 'taskslistDetails', component: () => import('pages/taskslists/TasksListDetails') },
      { path: '/dashboard/liste-de-taches/:id/editer', name: 'taskslistEdit', component: () => import('pages/taskslists/TasksListEdit') },
      { path: '/dashboard/liste-de-taches/:id/supprimer', name: 'taskslistDelete', component: () => import('pages/taskslists/TasksListDelete') },

      // Tasks
      { path: '/dashboard/liste-de-taches/:taskslistId/tache/ajouter', name: 'taskAdd', component: () => import('pages/tasks/TaskAdd') },
      { path: '/dashboard/liste-de-taches/:taskslistId/tache/:taskId/editer', name: 'taskEdit', component: () => import('pages/tasks/TaskEdit') },
      { path: '/dashboard/liste-de-taches/:taskslistId/tache/:taskId/supprimer', name: 'taskDelete', component: () => import('pages/tasks/TaskDelete') }
    ]
  },
  { path: '/:catchAll(.*)*', component: () => import('pages/ErrorNotFound.vue') }
]

export default routes
