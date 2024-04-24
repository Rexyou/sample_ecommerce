<template>
    <div>
        <div class="tabs">
            <div class="tab_items">
                <div class="title" v-for="(item, index) in tab_items" :key="index" :class="{ 'active': setting.current_tab == index }" @click="changeTab(index)" :id="index">
                    <b>{{ item }}</b>
                </div>
                <div class="title logout" @click="logout" :disabled="process">
                    <b>Logout</b>
                </div>
            </div>
            <div class="tab_content" v-for="(item, index) in tab_items" :key="index" :class="{ 'active': setting.current_tab == index }" :id="index">
                <ProfileUserInfo v-if="setting.current_tab == 'user_info'" :user_data="user_data" />
                <ProfileOrders v-if="setting.current_tab == 'orders'" />
                <ProfileFavorites v-if="setting.current_tab == 'favorite'" />
                <ProfileAddress v-if="setting.current_tab == 'address'" />
                <ProfileSetting v-if="setting.current_tab == 'setting'" />
            </div>
        </div>
    </div>
</template>

<script setup>
    import { useAuthStore } from '../store/auth';
    import { reactive, onMounted, computed, watch } from 'vue'
    import { storeToRefs } from 'pinia';
    import ProfileUserInfo from '../components/ProfileUserInfo.vue';
    import ProfileOrders from '../components/ProfileOrders.vue';
    import ProfileFavorites from '../components/ProfileFavorites.vue';
    import ProfileAddress from '../components/ProfileAddress.vue';
    import ProfileSetting from '../components/ProfileSetting.vue';

    const authStore = useAuthStore()
    authStore.getProfile();
    authStore.validation_errors = []
    const { process } = storeToRefs(authStore);
    const user_data_detail = computed(()=> authStore.user_data)
    let user_data = user_data_detail.value

    const tab_items = { user_info: 'User Info', orders: 'Orders', favorite: 'Favorites', address: 'Addresses', setting: 'Setting' }

    const setting = reactive({
        current_tab: 'user_info',
        edit_mode: false,
        edit_wording: 'Edit'
    })

    onMounted(() => {
        if(location.hash){
            const locationHash = location.hash
            const hash = locationHash.slice(1) // remove hashtag
            setting.current_tab = hash
            document.getElementById(hash).classList.add('active')
        }
    })

    const changeTab = (tab_key) => {
        setting.current_tab = tab_key
        location.hash = tab_key;
    }

    const logout = () => { authStore.logout() }
</script>

<style scoped>
    .tab_container {
        padding-top: 150px;
    }

    .tabs {
        padding-top: 130px;
        padding-bottom: 50px;
        display: flex;
        width: 90%;
        height: 100vh;
        margin: 0px auto;
    }
    
    .tab_items {
        width: 15%;
        font-size: 20px;
        border-right: 1px solid rgba(139, 139, 139, 0.5);
    }

    .tab_items .title {
        margin: 25px 0px;
        font-size: 20px;
        padding: 10px;
        color: rgba(0, 0, 0, 0.4);
        transition: all .3s ease-in-out;
    }

    .tab_items .title.logout {
        background: #e80202;
        color: white;
        border-right: 7px solid white;
        transition: .3s all ease-in-out;
    }

    .tab_items .title.logout:hover {
        background: #be0101;
    }

    .tab_items .title:hover {
        cursor: pointer;
    }

    .tab_items .title.active {
        border-right: 7px solid red;
        color: rgba(0, 0, 0, 1);
    }

    .tab_content {
        display: none;
        width: 0%;
        overflow: hidden;
        padding: 20px;
        margin-left: 80px;
    }

    .tab_content.active {
        display: block;
        width: 85%;
    }
</style>