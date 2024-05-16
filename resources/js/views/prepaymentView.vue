<template>
    <div>
        <h1>Prepayment Page</h1>
    </div>
</template>

<script setup>
    import { computed } from 'vue'
    import { useRoute } from "vue-router"
    import { useCartStore } from "../store/cart"
    
    const cartStore = useCartStore()
    cartStore.getCartList()
    const selectedList = computed(()=> cartStore.selected_cart_item)
    

    const route = useRoute()
    console.log("current_route: ", route)
</script>

<style lang="scss" scoped>

</style>