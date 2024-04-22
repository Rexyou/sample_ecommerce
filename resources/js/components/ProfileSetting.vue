<template>
    <div class="user_info_data">
        <div class="user_input_field">
            <div class="user_input">
                <label for="username">Username</label>
                <input type="text" v-model="form.username">
                <span v-for="error in v$.username.$errors" :key="error.$uid" class="error_message">
                    {{ error.$message }}
                </span>
                <span v-for="error in validation_errors.username" :key="error.$uid" class="error_message">
                    {{ error }}
                </span>
            </div>
            <div class="user_input">
                <label for="phone">Phone</label>
                <input type="text" v-model="form.phone">
                <span v-for="error in v$.phone.$errors" :key="error.$uid" class="error_message">
                    {{ error.$message }}
                </span>
                <span v-for="error in validation_errors.phone" :key="error.$uid" class="error_message">
                    {{ error }}
                </span>
            </div>
            <div class="user_input">
                <label for="first_name">Email</label>
                <input type="text" v-model="form.email">
                <span v-for="error in v$.email.$errors" :key="error.$uid" class="error_message">
                    {{ error.$message }}
                </span>
                <span v-for="error in validation_errors.email" :key="error.$uid" class="error_message">
                    {{ error }}
                </span>
            </div>
            <div class="user_input">
                <label for="current_password">Current Password</label>
                <input type="text" v-model="form.current_password">
                <span v-for="error in v$.current_password.$errors" :key="error.$uid" class="error_message">
                    {{ error.$message }}
                </span>
                <span v-for="error in validation_errors.current_password" :key="error.$uid" class="error_message">
                    {{ error }}
                </span>
            </div>
            <div class="user_input">
                <label for="password">New Password</label>
                <input type="text" v-model="form.password">
                <span v-for="error in v$.password.$errors" :key="error.$uid" class="error_message">
                    {{ error.$message }}
                </span>
                <span v-for="error in validation_errors.password" :key="error.$uid" class="error_message">
                    {{ error }}
                </span>
            </div>
            <div class="user_input">
                <label for="password_confirmation">Password Confirmation</label>
                <input type="text" v-model="form.password_confirmation">
                <span v-for="error in v$.password_confirmation.$errors" :key="error.$uid" class="error_message">
                    {{ error.$message }}
                </span>
                <span v-for="error in validation_errors.password_confirmation" :key="error.$uid" class="error_message">
                    {{ error }}
                </span>
            </div>
        </div>
        <div class="user_input_field2">
            <!-- Other setting -->
            <div class="user_input2">
                <input type="checkbox" id="remember_me" v-model="form.preferences.remember_me">
                <label for="remember_me">"<i>Remember me</i>" setting when login.</label>
            </div>
            <div class="user_input2">
                <input type="checkbox" id="receive_news" v-model="form.preferences.receive_news">
                <label for="receive_news">Received latest news and promotion.</label>
            </div>
            <div class="user_input2">
                <input type="checkbox" id="receive_recommandation" v-model="form.preferences.receive_recommandation">
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
    import { toast } from 'vue3-toastify';

    import { useVuelidate } from '@vuelidate/core';
    import { required, email, helpers, minLength, maxLength, sameAs } from "@vuelidate/validators";

    const authStore = useAuthStore()
    const { process, validation_errors } = storeToRefs(authStore)

    let user_data_details = computed(()=> authStore.user_data)
    let user_data = user_data_details.value

    const current_response_detail = computed(()=> authStore.successResponse)
    const current_response = current_response_detail.value

    const form = reactive({
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

    const username_format = helpers.regex(/^[\w\d]{8,12}$/)
    const phone_format = helpers.regex(/\+[0-9]{10,14}$/)

    const rules = computed(()=> {
        return {
            username: { 
                     
                        username_format: helpers.withMessage("Username format invalid!", username_format),
                        minLength: minLength(8), maxLength: maxLength(12)
                    },
            email: { email, maxLength: maxLength(100) },
            phone: { 
                    
                        phone_format: helpers.withMessage("Phone format invalid!", phone_format), 
                        minLength: minLength(10), maxLength: maxLength(14)
                    },
            current_password: { minLength: minLength(8), maxLength: maxLength(16) },
            password: { minLength: minLength(8), maxLength: maxLength(16) },
            password_confirmation: { 
                                    minLength: minLength(8), maxLength: maxLength(16),
                                    sameAs: sameAs(form.password) },
        } 
    })

    const v$ = useVuelidate(rules, form);

    const updateUserInfo = async () => {

        const result = await v$.value.$validate();
        if(result){
            const current_info = Object.entries(form).reduce((a,[k,v]) => {
                if (v !== null && v !== "" && user_data[k] !== v) {
                    a[k] = v;
                }
                return a;
            }, {});

            let count = 0
            Object.keys(current_info.preferences).forEach((item)=> {
                if(user_data.profile.preferences != null && current_info.preferences[item] == user_data.profile.preferences[item]){
                    count++
                }
            })

            if(count == Object.keys(current_info.preferences).length){
                delete current_info.preferences
            }
            
            if(Object.values(current_info).length > 0){
                authStore.updateUser(current_info)
                if(current_response){
                    form.current_password = ""
                    form.password = ""
                    form.password_confirmation = ""
                }
            }
            else {
                toast.warning("Nothing to update")
                authStore.validation_errors = []
            }
        }

    }
</script>

<style scoped>
    .user_info_data {
        width: 100%;
        height: 100%;
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

    .user_input .error_message {
        color: red;
        font-size: 16px;
        margin: 10px 0px 0px;
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