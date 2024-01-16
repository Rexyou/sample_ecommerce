import { createRouter, createWebHistory } from 'vue-router'
import aboutView from '../views/aboutView.vue'
import homeView from '../views/homeView.vue'
import productListView from '../views/productListView.vue'
import brandListView from '../views/brandListView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
        path: '/',
        name: 'home',
        component: homeView
    },
    {
        path: '/product_list',
        name: 'product_list',
        component: productListView
    },
    {
      path: '/brand_list',
      name: 'brand_list',
      component: brandListView
    },
    {
      path: '/about',
      name: 'about',
      component: aboutView
    },
  ]
})

export default router