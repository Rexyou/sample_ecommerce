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
                        <input type="text" placeholder="username/email/phone_number" id="username">
                    </div>
                    <div class="login_data">
                        <label for="password">Password</label>
                        <input type="password" placeholder="password" id="password">
                    </div>
                    <button @click.prevent="console.log('sample')">Login</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>

</script>

<style scoped>
    .login {
        width: 100%;
        height: 100vh;
        background-image: #ebeaea;
        display: flex;
        /* padding: 70px 50px; */
    }

    .background_image {
        width: 70%;
        height: 100%;
        background: url('https://images.unsplash.com/photo-1644898262501-6e73916dce2e?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');
        background-size: cover;
        object-fit: cover;
        /* border-radius: 45px; */
    }

    .login_detail {
        width: 30%;
        height: 100%;
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
        font-size: 18px;
        padding: 14px;
    }

    .login_form button {
        font-size: 25px;
        border: none;
        background: #ff1818;
        padding: 14px 20px;
        margin: 15px 0px;
        color: white;
        transition: all .3s ease-in-out;
    }

    .login_form button:hover {
        background: #a70000;
        cursor: pointer;
    }
</style>