<template>
    <div>
        <div class="register">
            <div class="background_image">
            </div>
            <div class="register_detail">
                <form class="register_form">
                    <h1>Register</h1>
                    <div class="register_data">
                        <label for="username">Username</label>
                        <input type="text" placeholder="username" id="username" v-model="form.username">
                        <span v-for="error in v$.username.$errors" :key="error.$uid" class="error_message">
                                {{ error.$message }}
                        </span>
                        <span v-for="error in validation_errors.username" :key="error.$uid" class="error_message">
                                {{ error }}
                        </span>
                    </div>
                    <div class="register_data">
                        <label for="email">Email</label>
                        <input type="email" placeholder="email" id="email" v-model="form.email">
                        <span v-for="error in v$.email.$errors" :key="error.$uid" class="error_message">
                                {{ error.$message }}
                        </span>
                        <span v-for="error in validation_errors.email" :key="error.$uid" class="error_message">
                                {{ error }}
                        </span>
                    </div>
                    <div class="register_data">
                        <label for="phone">Phone</label>
                        <input type="phone" placeholder="phone" id="phone" v-model="form.phone">
                        <span v-for="error in v$.phone.$errors" :key="error.$uid" class="error_message">
                                {{ error.$message }}
                        </span>
                        <span v-for="error in validation_errors.phone" :key="error.$uid" class="error_message">
                                {{ error }}
                        </span>
                    </div>
                    <div class="register_data">
                        <label for="password">Password</label>
                        <v-icon name="bi-eye-fill" class="password_watch" @click="switchVisibility()" v-show="showPassword.show"/>
                        <v-icon name="bi-eye-slash-fill" class="password_watch" @click="switchVisibility()" v-show="!showPassword.show"/>
                        <input :type="showPassword.type" placeholder="password" id="password" v-model="form.password">
                        <span v-for="error in v$.password.$errors" :key="error.$uid" class="error_message">
                                {{ error.$message }}
                        </span>
                        <span v-for="error in validation_errors.password" :key="error.$uid" class="error_message">
                                {{ error }}
                        </span>
                    </div>
                    <div class="register_data">
                        <label for="password_confirmation">Password Confirmation</label>
                        <input type="text" placeholder="Password confirmation" id="password_confirmation" v-model="form.password_confirmation">
                        <span v-for="error in v$.password_confirmation.$errors" :key="error.$uid" class="error_message">
                                {{ error.$message }}
                        </span>
                    </div>
                    <button @click.prevent="register()">Register</button>
                    <p>If you already have an account. Please login <router-link :to="{ name: 'login' }">here</router-link></p>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { reactive, computed } from 'vue';
    import { useVuelidate } from '@vuelidate/core';
    import { required, email, helpers, minLength, maxLength, sameAs } from "@vuelidate/validators";
    import { useAuthStore } from '../store/auth';
    import { storeToRefs } from 'pinia';

    const authStore = useAuthStore()
    const { validation_errors } = storeToRefs(authStore)

    const showPassword = reactive({
        show: false,
        type: "password"
    })

    const switchVisibility = () => {
        showPassword.show = !showPassword.show
        if(showPassword.show){
            showPassword.type = "text"
        }
        else {
            showPassword.type = "password"
        }
    }

    const form = reactive({
        username: "",
        email: "",
        phone: "",
        password: "",
        password_confirmation: "",
    })

    const username_format = helpers.regex(/^[\w\d]{8,12}$/)
    const phone_format = helpers.regex(/\+[0-9]{10,14}$/)

    const rules = computed(()=> {
        return {
            username: { 
                        required, 
                        username_format: helpers.withMessage("Username format invalid!", username_format),
                        minLength: minLength(8), maxLength: maxLength(12)
                    },
            email: { required, email, maxLength: maxLength(100) },
            phone: { 
                        required,
                        phone_format: helpers.withMessage("Phone format invalid!", phone_format), 
                        minLength: minLength(10), maxLength: maxLength(14)
                    },
            password: { required, minLength: minLength(8), maxLength: maxLength(16) },
            password_confirmation: { 
                                    required, 
                                    minLength: minLength(8), maxLength: maxLength(16),
                                    sameAs: sameAs(form.password) },
        } 
    })

    const v$ = useVuelidate(rules, form);

    const register = async () => {
        const result = await v$.value.$validate();
        if(result){
            authStore.register(form)
        }
    }
</script>

<style scoped>
    .register {
        width: 100%;
        height: 100vh;
        background-image: #ebeaea;
        display: flex;
        /* padding: 70px 50px; */
        background: url('https://images.unsplash.com/photo-1631797683906-fb7cd87b9c9b?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');
        background-size: cover !important;
        object-fit: cover !important;
        background-position: center !important;
    }

    .background_image {
        width: 65%;
        height: 100%;
        /* border-radius: 45px; */
    }

    .register_detail {
        width: 35%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        color: white;
    }

    .register_form {
        height: 100%;
        display: flex;
        justify-content: center;
        align-content: center;
        flex-direction: column;
        padding: 40px;
    }

    .register_form h1 {
        font-size: 32px;
        margin: 10px 0px;
    }

    .register_form .register_data {
        display: flex;
        flex-direction: column;
        margin: 5px 0px;
        position: relative;
    }

    .register_form .register_data label {
        font-size: 20px;
        margin: 5px 0px;
    }

    .register_form .register_data input {
        font-size: 14px;
        padding: 12px;
    }

    
    .register_form .register_data .error_message {
        color: red;
        font-size: 16px;
        margin: 10px 0px 0px;
    }

    .register_form button {
        font-size: 24px;
        border: none;
        background: #ff1818;
        padding: 12px 20px;
        margin: 15px 0px;
        color: white;
        transition: all .3s ease-in-out;
        border-radius: 15px;
    }

    .register_form button:hover {
        background: #a70000;
        cursor: pointer;
    }

    .register_form p {
        font-size: 14px;
        text-align: center;
        margin: 10px 0px;
    }

    .register_form p a{
        text-decoration: none;
        color: red;
    }

    .password_watch {
        position: absolute;
        bottom: 6px;
        right: 10px;
        width: 30px;
        height: 30px;
        color: black;
    }

    .password_watch:hover {
        cursor: pointer;
    }

    @media screen and (max-width: 1000px) {
        .background_image {
            width: 50%;

        }

        .register_detail {
            width: 50%;
        }
    }

    @media screen and (max-width: 800px) {

        .register {
            background: url('https://images.unsplash.com/photo-1613014510867-a47a0e5bf564?q=80&w=1887&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');
        }

        .background_image {
            width: 0%;
        }

        .register_detail {
            width: 100%;
        }
    }
</style>