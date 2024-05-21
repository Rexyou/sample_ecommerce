<template>
    <div class="calculation_form">
        <div v-if="form.cart_id">
            <p>{{ form.cart_id }}</p>
        </div>
        <div v-else>
            <h1>not working</h1>
        </div>
    </div>
</template>

<script setup>
    import { reactive } from 'vue'
    import { useRoute, useRouter } from "vue-router"
    import { useCartStore } from "../store/cart"
    import { toast } from 'vue3-toastify'
    
    const cartStore = useCartStore()
    const route = useRoute()
    const router = useRouter()
    const params = route.params
    console.log("current_route_params: ", params)

    const form = reactive({
        cart_id: [],
        total_price: 0,
    })

    const checkParams = async (params) => {
        const checker = await cartStore.encryptionCartItem("decrypt", params)
        if(!checker.status){
            toast.error(checker.message)
            router.push({ name: 'cart' })
        }

        form.cart_id = checker.data

        console.log("current checker: ", checker.data)
        console.log("current_form: " , form)
    }

    // Check cart item before enter current page
    checkParams(params)

</script>

<style scoped>
    .calculation_form {
        border: 5px solid black;
        min-height: 100vh;
    }
</style>