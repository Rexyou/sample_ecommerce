!<template>
    <div class="navigation_bar" :style="setting.show_navbar ? { display: 'flex' } : { display: 'none' }">
        <div class="logo">
            <img src="https://scontent.fkul15-1.fna.fbcdn.net/v/t39.30808-6/312912063_487774336709363_1010814458414550901_n.jpg?_nc_cat=108&ccb=1-7&_nc_sid=efb6e6&_nc_eui2=AeG3SsNyvkR9EW4DWk4sOKd63tLcvq6Xkxfe0ty-rpeTFw73fkyjWy5EKy-5SnwQbeLEo4DNWk6B8KwjAg7pIViL&_nc_ohc=kmccWQwdYFoAX-9Z4P_&_nc_ht=scontent.fkul15-1.fna&oh=00_AfCZcZeeJ510hc7WZwAl1xb4IeDCdafZqp1h_XqYkbTxrA&oe=65A5C632" alt="">
        </div>
        <div class="menu_bar">
            <router-link :to="{ name: 'home' }" :style="[ menu_change_color ? { color: 'black !important' } : { color: 'white' } ]">Home</router-link>
            <router-link :to="{ name: 'product_list' }" :style="[ menu_change_color ? { color: 'black !important' } : { color: 'white' } ]">Products</router-link>
            <router-link :to="{ name: 'brand_list' }" :style="[ menu_change_color ? { color: 'black !important' } : { color: 'white' } ]">Brands</router-link>
            <router-link :to="{ name: 'about' }" :style="[ menu_change_color ? { color: 'black !important' } : { color: 'white' } ]">About</router-link>
        </div>
        <div class="search_bar">
            <v-icon name="bi-search" :style="[ menu_change_color ? { color: 'black !important' } : { color: 'white' } ]" />
            <div v-if="token !== null">
                <router-link :to="{ name: 'profile' }" :style="[ menu_change_color ? { color: 'black !important' } : { color: 'white' } ]">
                    <span class="profile_image" :style="{ background: setting.randomColor }"></span>
                </router-link>
            </div>
            <router-link :to="{ name: 'login' }" v-else :style="[ menu_change_color ? { color: 'black !important' } : { color: 'white' } ]">
                <v-icon name="bi-people-fill"/>
            </router-link>
            <router-link :to="{ name: 'cart' }" :style="[ menu_change_color ? { color: 'black !important' } : { color: 'white' } ]">
                <v-icon name="bi-cart"/>
            </router-link>
        </div>
    </div>
    <div class="navigation_bar_2" :style="setting.show_home ? { display: 'flex' } : { display: 'none' }">
        <v-icon name="bi-arrow-left-square"/>
        <router-link :to="{ name: setting.previous_page_name }">Back</router-link>
    </div>
</template>

<script setup>
    import { storeToRefs } from "pinia";
    import { useCommonStore } from "../store/general";
    import { useRoute, useRouter } from 'vue-router'
    import { reactive, computed, onMounted } from 'vue'
    import { useAuthStore } from "../store/auth";

    const commonStore = useCommonStore();
    const authStore = useAuthStore();
    const { menu_change_color } = storeToRefs(commonStore);
    const { token } = storeToRefs(authStore);

    const setting = reactive({
        show_navbar: true,
        show_home: false,
        previous_page_name: 'home',
        auth_user: false,
        randomColor: "#"+Math.floor(Math.random()*16777215).toString(16)
    })

    const route = useRoute();
    const router = useRouter()

    router.beforeEach(async (to, from) => {
        if(from.name != undefined && to.name != from.name){
            setting.previous_page_name = from.name
        }
        else {
            setting.previous_page_name = 'home';
        }

        const hideNavigationPage = [ 'login', 'register' ];

        if(hideNavigationPage.includes(to.name)){
            setting.show_navbar= false
            setting.show_home = true
        }
        else {
            setting.show_navbar= true
            setting.show_home = false
        }

        if(to.name == 'profile'){
            authStore.getProfile()
        }
    })

</script>

<style scoped>
    .navigation_bar {
        position: absolute;
        z-index: 20;
        width: 90%;
        margin: 10px auto;
        display: flex;
        justify-content:space-between;
        left:0;
        right:0;
    }

    .navigation_bar .logo {
        width: 10%;
        height: 100px;
    }

    .navigation_bar .logo img {
        width: auto;
        height: 100px;
        display: block;
        margin-left: auto;
    }

    .navigation_bar .menu_bar {
        width: 30%;
        display: flex;
        justify-content: space-around;
        align-items: center;
        height: 100px;
        margin-left: auto;
    }

    .navigation_bar .menu_bar a {
        color: white;
        text-decoration: none;
        font-size: 16px;
    }
    
    .navigation_bar .search_bar {
        min-width: 150px;
        display: flex;
        align-items: center;
        justify-content: space-around;
        color: white;
        margin-left: auto;
    }

    .color_white {
        color: white !important;
    }

    .color_black {
        color: black !important;
    }

    .navigation_bar .search_bar svg {
        width: 30px;
        height: 30px;
    }

    .navigation_bar_2 {
        position: absolute;
        left: 40px;
        top: 40px;
        color: white;
        font-size: 25px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .navigation_bar_2 svg {
        width: 25px;
        height: 25px;
        margin-right: 10px;
    }

    .navigation_bar_2 a {
        text-decoration: none;
        color: white;
    }

    .profile_image {
        display: block;
        width: 30px;
        height: 30px;
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 50%;
    }
</style>