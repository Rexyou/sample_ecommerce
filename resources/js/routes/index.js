import { createRouter, createWebHistory } from 'vue-router'
import aboutView from '../views/aboutView.vue'
import homeView from '../views/homeView.vue'
import productListView from '../views/productListView.vue'
import brandListView from '../views/brandListView.vue'
import brandView from '../views/brandView.vue'
import productView from '../views/productView.vue'
import loginView from '../views/loginView.vue'
import profileDetailView from '../views/profileDetailView.vue';
import registerView from '../views/registerView.vue';
import cartView from '../views/cartView.vue';
import errorView from '../views/errorView.vue';
import { useCommonStore } from '../store/general';
import { useAuthStore } from '../store/auth';

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
      path: '/brand/:brand_id?/:slug?',
      name: 'brand',
      component: brandView
    },
    {
      path: '/product/:product_id?/:slug?',
      name: 'product',
      component: productView
    },
    {
      path: '/about',
      name: 'about',
      component: aboutView
    },
    {
      path: '/login',
      name: 'login',
      component: loginView
    },
    {
      path: '/register',
      name: 'register',
      component: registerView
    },
    {
      path: '/profile',
      name: 'profile',
      component: profileDetailView
    },
    {
      path: '/cart',
      name: 'cart',
      component: cartView
    },
    { 
      path: '/:pathMatch(.*)*', 
      name: 'error',
      component: errorView 
    },
  ],
  scrollBehavior() {
    return { top: 0, left: 0 }
  }
})

router.beforeEach(async (to, from)=> {
  const commonStore = useCommonStore()
  const authStore = useAuthStore();
  const authUserData = authStore.user_data;
  const token = authStore.token;

  const blackFontPage = [ 'product', 'product_list', 'login', 'profile' ];
  const authPage = [ 'profile', 'cart' ];
  const guestPage = [ 'login', 'register' ];
  
  if(authPage.includes(to.name) && Object.keys(authUserData).length == 0 && token == null){
    return { name: 'login' }
  }
  else if(guestPage.includes(to.name) && Object.keys(authUserData).length != 0 && token != null){
    return { name: 'profile' }
  }

  if(blackFontPage.includes(to.name)){
    commonStore.menu_change_color = true;
  }
  else {
    commonStore.menu_change_color = false;
  }

  if((to.name == "brand" && to.params.brand_id == "") || (to.name == "product" && to.params.product_id == "")){
    return { name: 'error' }
  }
})

export default router