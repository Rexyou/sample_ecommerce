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
                    </div>
                    <div class="register_data">
                        <label for="email">Email</label>
                        <input type="email" placeholder="email" id="email" v-model="form.email">
                    </div>
                    <div class="register_data">
                        <label for="phone">Phone</label>
                        <input type="phone" placeholder="phone" id="phone" v-model="form.phone">
                    </div>
                    <div class="register_data">
                        <label for="password">Password</label>
                        <input type="password" placeholder="password" id="password" v-model="form.password">
                    </div>
                    <div class="register_data">
                        <label for="password_confirmation">Password Confirmation</label>
                        <input type="text" placeholder="Password confirmation" id="password_confirmation" v-model="form.password_confirmation">
                    </div>
                    <button @click.prevent="register()">Login</button>
                    <p>If you already have an account. Please login <router-link :to="{ name: 'login' }">here</router-link></p>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { reactive } from 'vue';
    import { useAuthStore } from '../store/auth';

    const authStore = useAuthStore()
    const form = reactive({
        username: "",
        email: "",
        phone: "",
        password: "",
        password_confirmation: "",
    })

    const register = () => {
        console.log(form)
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
    }

    .register_form .register_data label {
        font-size: 20px;
        margin: 5px 0px;
    }

    .register_form .register_data input {
        font-size: 14px;
        padding: 12px;
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
            background: url('https://s3.amazonaws.com/tf.images/reduced-image_22826_4_1679888413.jpeg');
        }

        .background_image {
            width: 0%;
        }

        .register_detail {
            width: 100%;
        }
    }
</style>