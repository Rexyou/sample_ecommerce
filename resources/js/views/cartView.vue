<template>
    <div class>
        <div class="cover_page_section" :style="{ backgroundImage: `url(${brand_list_page_cover.image_url})` }">
            <h1>Cart</h1>
        </div>
        <div class="cart_container">
            <div v-if="cartList.length > 0" class="cart_list">
                <div v-for="(cart, index) in cartList" :key="index" class="cart_item">
                    <div class="product_image" v-if="cart.product.icon_image_url != null" :style="{ background: `url('${cart.product.icon_image_url}')` }"></div>
                    <div class="product_image" v-if="cart.product.product_images_filter != null" :style="{ background: `url('${cart.product.product_images_filter[0].image_url}')` }"></div>
                    <div class="product_detail">
                        <h1>{{ cart.product.name }}</h1>
                        <div v-if="cart.product_option_details.options != null" class="selected_option_box">
                            <div v-for="(value, key) in cart.product_option_details.options" :key="key" class="selected_options">
                                <span>{{ key }} : {{ value }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="product_quantity">
                        <button @click.prevent="adjustItemQuantity('minus')">
                            <v-icon name="hi-minus-circle"/>
                        </button>
                        <div class="current_quantity">
                            <span>{{ cart.quantity }}</span>
                        </div>
                        <button @click.prevent="adjustItemQuantity('add')">
                            <v-icon name="hi-plus-circle"/>
                        </button>
                    </div>
                </div>
            </div>
            <div v-else class="cart_list2">
                <h1>Currently dont have item inside cart</h1>
            </div>
            <div class="calculation">
                <div class="calculation_board">
                    <div class="price_list">
                        <span>Total Price</span>
                        <span>{{ form.total_price.toFixed(2) }}</span>
                    </div>
                    <div class="price_list">
                        <span>Total Shipping</span>
                        <span>{{ form.total_shipping.toFixed(2) }}</span>
                    </div>
                    <hr>
                    <div class="price_list">
                        <span>Estimate Price</span>
                        <span>{{ form.estimate_price.toFixed(2) }}</span>
                    </div>
                    <div class="payment_section">
                        <button>Proceed Payment</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { useCommonStore } from "../store/general";
    import { onMounted, computed, reactive, watch } from 'vue'
    import { storeToRefs } from 'pinia'
    import { useCartStore } from "../store/cart";

    const commonStore = useCommonStore()
    const cartStore = useCartStore()
    onMounted(async()=> {
        cartStore.cartList()
        commonStore.getComponent('cart_list', 'cover_page');
    })
    const { brand_list_page_cover } = storeToRefs(commonStore)
    const cartList = computed(()=> cartStore.cart_list)
    const paginationData = computed(()=> cartStore.cart_list_pagination)

    console.log("cart view list: ", cartList.value)
    console.log("pagination data: ", paginationData.value)

    const form = reactive({
        total_price: 0,
        total_shipping: 0,
        estimate_price: 0
    })

    const adjustItemQuantity = (action) => {
        console.log("current_action: ", action)
    }
</script>

<style scoped>
    .cart_container {
        display: flex;
    }

    .cover_page_section {
        height: 50vh;
        width: auto;
        background-attachment: fixed;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .cover_page_section:after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.2);
    }

    .cover_page_section h1 {
        color: white;
        font-size: 90px;
        position: absolute;
        z-index: 1;
    }

    .cart_list,
    .cart_list2 {
        width: 70%;
        height: auto;
        padding: 10px 20px;
    }

    .cart_list2 {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cart_item {
        width: 100%;
        height: 180px;
        margin: 30px auto;
        display: flex;
        box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
    }

    .product_image {
        /* background: url("https://images.unsplash.com/photo-1545127398-14699f92334b?q=80&w=1935&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"); */
        background-size: cover !important;
        background-position: center !important;
        width: 30%;
        height: auto;
    }

    .product_detail {
        width: 50%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        margin-left: 50px;
    }

    .product_detail h1 {
        font-size: 24px;
        text-overflow: ellipsis;
    }

    .selected_option_box {
        margin: 10px 0px;
    }

    .selected_options {
        font-size: 16px;
        margin: 5px 0px;
        color: #888888;
    }

    .product_quantity {
        display: flex;
        align-items: center;
        justify-content: space-evenly;
        border-left: 1px solid rgba(0, 0, 0, 0.2);
        width: 20%;
    }

    .product_quantity button {
        border: none;
        box-shadow: none;
        background: none;
    }

    .product_quantity button svg {
        width: 40px;
        height: 40px;
    }

    .current_quantity {
        font-size: 24px;
    }

    .calculation {
        width: 30%;
        padding: 40px 20px;
        position: relative;
    }

    .calculation_board {
        position: -webkit-sticky; /* Safari */
        position: sticky;
        top: 40px;
        border: 1px solid rgba(0, 0, 0, 0.4);
        padding: 30px 20px;
    }

    .price_list {
        display: flex;
        margin: 25px auto;
        align-items: center;
    }

    .price_list span:nth-child(1)
    {
        font-size: 24px;
        font-weight: 800;
        width: 80%;
    }

    .price_list span:nth-child(2)
    {
        font-size: 28px;
        font-weight: 500;
        width: 20%;
    }

    .payment_section {
        margin: 10px 0px;
    }

    .payment_section button {
        width: 250px;
        height: 50px;
        display: block;
        margin: 10px auto;
        font-size: 20px;
        border: none;
        color: white;
        border-radius: 45px;
        transition: all 0.2s ease-in-out;
        background: rgb(22, 250, 22);
    }

    .payment_section button:hover {
        cursor: pointer;
        background: rgb(0, 197, 0);
    }

    .payment_section button:disabled {
        background: #444444 !important;
    }
</style>