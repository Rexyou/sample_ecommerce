import router from './routes/index'
import { createApp, markRaw } from 'vue'
import App from './App.vue'
import { createPinia } from 'pinia'
import Flicking from "@egjs/vue3-flicking";
import "@egjs/vue3-flicking/dist/flicking.css";
// Or, if you have to support IE9
import "@egjs/vue3-flicking/dist/flicking-inline.css";
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
import { OhVueIcon, addIcons } from "oh-vue-icons";
import { BiSearch, BiPeopleFill, BiCart, BiBookmark, HiMinusCircle, HiPlusCircle, BiArrowLeftSquare, BiEyeFill, BiEyeSlashFill, MdModeeditoutline, MdDeleteforever, FaSkullCrossbones } from "oh-vue-icons/icons";
import VueAwesomePaginate from "vue-awesome-paginate";
import "vue-awesome-paginate/dist/style.css";
import Vue3Toasity from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

addIcons(BiSearch, BiPeopleFill, BiCart, BiBookmark, HiMinusCircle, HiPlusCircle, BiArrowLeftSquare, BiEyeFill, BiEyeSlashFill, MdModeeditoutline, MdDeleteforever, FaSkullCrossbones);

const app = createApp(App)
const pinia = createPinia()

pinia.use(piniaPluginPersistedstate)
pinia.use(({ store }) => {
    store.router = markRaw(router)
})
app.use(pinia)
app.use(router)
app.use(VueAwesomePaginate)
app.use(Vue3Toasity, { autoClose: 3000, limit: 2 })

// Gloabal Component
app.component("Flicking", Flicking);    
app.component("v-icon", OhVueIcon);
app.mount("#app")