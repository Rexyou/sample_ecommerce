<template>
    <div>
        <h1>Register</h1>
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
</script>

<style lang="scss" scoped>

</style>