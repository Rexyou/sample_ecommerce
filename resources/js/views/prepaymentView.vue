<template>
    <div class="calculation_form">
        <div v-if="form.cart_id" class="cart_list">
            <div v-for="(cart, index) in form.cart_id" :key="index" class="cart_details">
                <div class="product_image" v-if="cart.product.icon_image_url != null" :style="{ background: `url('${cart.product.icon_image_url}')` }"></div>
                <div class="product_image" v-if="cart.product.product_images_filter != null" :style="{ background: `url('${cart.product.product_images_filter[0].image_url}')` }"></div>
                <div class="product_details">
                    <h2>{{ cart.product.name }}</h2>
                    <div class="price_range">
                        <div v-if="cart.product_option_details.options != null" class="option_list">
                            <p>Options: </p>
                            <div class="selected_options">
                                <div v-for="(value, key) in cart.product_option_details.options" :key="key">
                                    <span><i>{{ key }} : {{ value }}</i></span>
                                </div>
                            </div>
                        </div>
                        <p>Total Quantity: {{ cart.quantity }}</p>
                        <p>Per Unit Price: {{ cart.per_unit_price }}</p>
                    </div>
                    <p class="total_price">Total Price: {{ cart.total_price }}</p>
                </div>
            </div>
        </div>
        <div v-else class="error_message">
            <v-icon name="fa-skull-crossbones"></v-icon>
            <h1>Cart item not found</h1>
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
    const params = route.params
    console.log("current_route_params: ", params)

    const form = reactive({
        cart_id: [],
        total_price: 0,
    })

    const checkParams = async (params) => {
        const checker = await cartStore.encryptionCartItem("decrypt", params)

        if(checker != undefined){
            Object.values(checker.data).forEach((item)=> {
                form.total_price += item.total_price
                item.total_price = (typeof item.total_price == "number") ? item.total_price+".00" : parseFloat(item.total_price)
            })
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
        min-height: 100vh;
        width: 100%;
        padding-top: 15vh;
    }

    .cart_list {
        width: 90%;
        /* height: auto; */
        /* border: 1px solid red; */
        /* padding: 10px 20px; */
        margin: 10px auto;
    }

    .cart_details {
        width: 100%;
        height: 200px;
        margin: 30px 0px;
        display: flex;
    }

    .product_image {
        background-size: cover !important;
        background-position: center !important;
        width: 30%;
        height: 100%;
    }

    .product_details {
        width: 70%;
        height: 100%;
        border: 1px solid rgba(0, 0, 0, 0.5);
        padding: 30px;
        position: relative;
        border-left: none;
    }

    .product_details h2 {
        font-size: 26px;
    }

    .option_list {
        display: flex;
    }

    .selected_options {
        display: flex;
        flex-direction: column;
        margin-left: 10px;
    }

    .price_range {
        margin: 10px auto;
        color: grey;
        line-height: 25px;
        font-size: 16px;
    }

    .total_price {
        color: red;
        font-size: 24px;
        position: absolute;
        right: 30px;
        bottom: 30px;
    }

    .error_message {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        font-size: 28px;
    }

    .error_message svg {
        height: 56px;
        width: 56px;
        margin-right: 10px;
    }
</style>