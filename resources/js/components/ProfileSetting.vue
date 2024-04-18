<template>
    <div class="user_info_data">
        <div class="user_input_field">
            <div class="user_input">
                <label for="username">Username</label>
                <input type="text" v-model="user_current_info.username">
            </div>
            <div class="user_input">
                <label for="phone">Phone</label>
                <input type="text" v-model="user_current_info.phone">
            </div>
            <div class="user_input">
                <label for="first_name">Email</label>
                <input type="text" v-model="user_current_info.email">
            </div>
            <div class="user_input">
                <label for="current_password">Current Password</label>
                <input type="text" v-model="user_current_info.current_password">
            </div>
            <div class="user_input">
                <label for="password">New Password</label>
                <input type="text" v-model="user_current_info.password">
            </div>
            <div class="user_input">
                <label for="password_confirmation">Password Confirmation</label>
                <input type="text" v-model="user_current_info.password_confirmation">
            </div>
        </div>
        <div class="user_input_field2">
            <!-- Other setting -->
            <div class="user_input2">
                <input type="checkbox" id="remember_me" v-model="user_current_info.preferences.remember_me">
                <label for="remember_me">"<i>Remember me</i>" setting when login.</label>
            </div>
            <div class="user_input2">
                <input type="checkbox" id="receive_news" v-model="user_current_info.preferences.receive_news">
                <label for="receive_news">Received latest news and promotion.</label>
            </div>
            <div class="user_input2">
                <input type="checkbox" id="receive_recommandation" v-model="user_current_info.preferences.receive_recommandation">
                <label for="receive_recommandation">Received order related recommandation.</label>
            </div>
        </div>
        <button class="save_button" @click="updateUserInfo()" :disabled="process">Save</button>
    </div>
</template>

<script setup>
    import { useAuthStore } from '../store/auth';
    import { reactive, computed, watch } from 'vue';
    import { storeToRefs } from 'pinia';
    import { toast } from 'vue3-toastify'

    const authStore = useAuthStore()
    const props = defineProps({ user_data: Object })
    let user_data = props.user_data
    const { process } = storeToRefs(authStore)

    const current_response_detail = computed(()=> authStore.successResponse)
    const current_response = current_response_detail.value

    watch(props, (newProps, oldProps)=> {
        user_data = newProps.user_data
        console.log("watch user_data: ", user_data.profile.preferences)
    })

    const user_current_info = reactive({
        username: user_data.username,
        phone: user_data.phone,
        email: user_data.email,
        current_password: "",
        password: "",
        password_confirmation: "",
        preferences: {
            remember_me: user_data.profile.preferences?.remember_me == true ? true : false,
            receive_news: user_data.profile.preferences?.receive_news == true ? true : false,
            receive_recommandation: user_data.profile.preferences?.receive_recommandation == true ? true : false,
        }
    })

    const updateUserInfo = () => {

        const current_info = Object.entries(user_current_info).reduce((a,[k,v]) => {
             if (v !== null && v !== "" && user_data[k] !== v) {
                a[k] = v;
            }
            return a;
        }, {});

        let count = 0
        Object.keys(current_info.preferences).forEach((item)=> {
            if(current_info.preferences[item] == user_data.profile.preferences[item]){
                count++
            }
        })

        if(count == Object.keys(current_info.preferences).length){
            delete current_info.preferences
        }
        
        if(Object.values(current_info).length > 0){
            console.log("current_data: ", current_info)
            authStore.updateUser(current_info)
            if(current_response){
                user_current_info.current_password = ""
                user_current_info.password = ""
                user_current_info.password_confirmation = ""
            }
        }
        else {
            toast.warning("Nothing to update")
        }

    }
</script>

<style scoped>
    .user_info_data {
        width: 100%;
        height: 100%;
        overflow-y: scroll;
    }

    .user_input_field {
        display: grid;
        grid-template-columns: auto auto;
        row-gap: 15px;
    }

    .user_input_field2 {
        margin: 20px 0px;
        display: block;
    }

    .user_input {
        display: flex;
        flex-direction: column;
    }

    .user_input label {
        font-size: 20px;
        font-weight: 700;
    }

    .user_input input {
        padding: 10px;
        margin: 10px 0px;
        font-size: 16px;
        width: 300px;
    }

    .user_input2 {
        margin: 15px 0px;
    }

    .user_input2 input {
        margin-right: 10px;
        font-size: 20px;
    }

    .user_input2 label {
        margin-right: 10px;
        font-size: 18px;
        font-weight: 700;
    }

    .save_button {
        width: 250px;
        height: 50px;
        margin-top: 40px;
        font-size: 20px;
        border: none;
        color: white;
        border-radius: 45px;
        transition: all 0.2s ease-in-out;
        background: rgb(13, 250, 13);
    }

    .save_button:hover {
        cursor: pointer;
        background: rgb(0, 197, 0);
    }

    .save_button:disabled {
        background: #444444 !important;
    }
</style>