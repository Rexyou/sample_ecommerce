import { createRouter, createWebHistory } from 'vue-router'
import aboutView from '../views/aboutView.vue'
import homeView from '../views/homeView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
        path: '/',
        name: 'home',
        component: homeView
    },
    {
      path: '/about',
      name: 'about',
      component: aboutView
    },
  ]
})

export default router