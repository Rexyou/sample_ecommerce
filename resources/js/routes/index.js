import { createRouter, createWebHistory } from 'vue-router'
import aboutView from '../views/aboutView.vue'
import homeView from '../views/homeView.vue'
import productListView from '../views/productListView.vue'
import brandListView from '../views/brandListView.vue'
import brandView from '../views/brandView.vue'
import { useCommonStore } from '../store/general'

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
      path: '/brand/:brand_id/:slug?',
      name: 'brand',
      component: brandView
    },
    {
      path: '/about',
      name: 'about',
      component: aboutView
    },
  ],
  scrollBehavior() {
    return { top: 0, left: 0 }
  }
})

router.beforeEach(async (to, from)=> {
  console.log("from route: ", from)
  console.log("to route: ", to)
  const commonStore = useCommonStore()

  if(to.name == "brand_list"){
    // await commonStore.getBrandsList()
    // await commonStore.getComponent('brand_list', 'cover_page'); 
  }
})

export default router