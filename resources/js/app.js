import router from './routes/index'
import {createApp} from 'vue'
import App from './App.vue'
import { createPinia } from 'pinia'
import Flicking from "@egjs/vue3-flicking";
import "@egjs/vue3-flicking/dist/flicking.css";
// Or, if you have to support IE9
import "@egjs/vue3-flicking/dist/flicking-inline.css";
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
import { OhVueIcon, addIcons } from "oh-vue-icons";
import { BiSearch, BiPeopleFill, BiCart } from "oh-vue-icons/icons";

addIcons(BiSearch, BiPeopleFill, BiCart);

const app = createApp(App)
const pinia = createPinia()

pinia.use(piniaPluginPersistedstate)
app.use(pinia)
app.use(router)

// Gloabal Component
app.component("Flicking", Flicking);    
app.component("v-icon", OhVueIcon);
app.mount("#app")