<template>
    <div>
        <div class="login">
            <div class="background_image">
            </div>
            <div class="login_detail">
                <form class="login_form">
                    <h1>Login</h1>
                    <div class="login_data">
                        <label for="username">Username</label>
                        <input type="text" placeholder="username/email/phone_number" id="username" v-model="form.login">
                        <span v-for="error in v$.login.$errors" :key="error.$uid" class="error_message">
                                {{ error.$message }}
                        </span>
                    </div>
                    <div class="login_data">
                        <label for="password">Password</label>
                        <input type="password" placeholder="password" id="password" v-model="form.password">
                        <span v-for="error in v$.password.$errors" :key="error.$uid" class="error_message">
                            {{ error.$message }}
                        </span>
                    </div>
                    <button @click.prevent="login()">Login</button>
                    <p>If you don't have an account. Please register <router-link :to="{ name: 'register' }">here</router-link></p>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { useAuthStore } from '../store/auth';
    import { reactive, computed } from 'vue'
    import { useVuelidate } from '@vuelidate/core' // plugin
    import { required, email, minLength, maxLength } from '@vuelidate/validators' // property

    const authStore = useAuthStore();

    const form = reactive({
        login: "",
        password: "",
        type: 1,
    })

    const rules = computed(()=> {
        return {
            login: { required, minLength: minLength(8) },
            password: { required, minLength: minLength(8), maxLength: maxLength(16) },
            type: 1
        } 
    })

    const v$ = useVuelidate(rules, form)

    const login = async () => {

        const result = await v$.value.$validate(); // check validation
        if(result){
            console.log("current form: ", form)
            authStore.login(form)
        }
    }
</script>

<style scoped>
    .login {
        width: 100%;
        height: 100vh;
        background-image: #ebeaea;
        display: flex;
        /* padding: 70px 50px; */
        background: url('https://images.unsplash.com/photo-1644898262501-6e73916dce2e?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');
        background-size: cover;
        object-fit: cover;
        background-position: center;
        overflow: hidden;
    }

    .background_image {
        width: 65%;
        height: 100%;
        /* border-radius: 45px; */
    }

    .login_detail {
        width: 35%;
        height: 100%;
        background: rgba(0, 0, 0, 0.75);
        color: white;
    }

    .login_form {
        height: 100%;
        display: flex;
        justify-content: center;
        align-content: center;
        flex-direction: column;
        padding: 40px;
    }

    .login_form h1 {
        font-size: 40px;
         margin: 10px 0px;
    }

    .login_form .login_data {
        display: flex;
        flex-direction: column;
        margin: 10px 0px;
    }

    .login_form .login_data label {
        font-size: 20px;
        margin: 5px 0px;
    }

    .login_form .login_data input {
        font-size: 16px;
        padding: 12px;
    }

    .login_form .login_data .error_message {
        color: red;
        font-size: 16px;
        margin: 10px 0px 0px;
    }

    .login_form button {
        font-size: 24px;
        border: none;
        background: #ff1818;
        padding: 12px 20px;
        margin: 15px 0px;
        color: white;
        transition: all .3s ease-in-out;
        border-radius: 15px;
    }

    .login_form button:hover {
        background: #a70000;
        cursor: pointer;
    }

    .login_form p {
        font-size: 14px;
        text-align: center;
        margin: 10px 0px;
    }

    .login_form p a{
        text-decoration: none;
        color: red;
    }

    @media screen and (max-width: 1000px) {
        .background_image {
            width: 50%;

        }

        .login_detail {
            width: 50%;
        }
    }

    @media screen and (max-width: 800px) {
        .background_image {
            width: 0%;
        }

        .login_detail {
            width: 100%;
        }
    }
</style>