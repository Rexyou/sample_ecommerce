<template>
    <div class>
        <div class="cover_page_section" :style="{ backgroundImage: `url(${brand_list_page_cover.image_url})` }">
            <h1>Cart</h1>
        </div>
        <div class="cart_container">
            <div v-if="cartList.length > 0" class="cart_list">
                <h1 class="cart_item_count">Total items ({{ paginationData.total }})</h1>
                <div v-for="(cart, index) in cartList" :key="index" class="cart_item">
                    <div class="cart_checkbox">
                        <input type="checkbox" :id="`checkbox_${cart.id}`" class="cart_checkbox_item" v-model="form.selected_cart_id" :value="cart.id">
                    </div>
                    <div class="cart_details">
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
                            <button @click.prevent="adjustItemQuantity('minus', cart, index)">
                                <v-icon name="hi-minus-circle"/>
                            </button>
                            <div class="current_quantity">
                                <span :id="`current_quantity_${cart.id}`">{{ cart.quantity }}</span>
                            </div>
                            <button @click.prevent="adjustItemQuantity('add', cart, index)">
                                <v-icon name="hi-plus-circle"/>
                            </button>
                        </div>
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
                        <button :disabled="form.process_payment_locker" @click="generateCartKey">Proceed Payment Preview</button>
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
    import { useRoute, useRouter } from "vue-router";
    import { toast } from 'vue3-toastify';

    const commonStore = useCommonStore()
    const cartStore = useCartStore()
    const cartList = computed(()=> cartStore.cart_list)
    const paginationData = computed(()=> cartStore.cart_list_pagination)
    const currentPageData = computed(()=> cartStore.current_page)
    const currentProcess = computed(()=> cartStore.process)
    const route = useRoute()
    const router = useRouter()
    const queryPage = route.query.page
    let currentPage = currentPageData.value

    if(queryPage != undefined && currentPage != queryPage){
        currentPage = queryPage
        cartStore.cart_list = []
        cartStore.cart_list_pagination= []
        cartStore.current_page = queryPage
    }

    for(let i = 1; i <= currentPageData.value; i++)
    {
        cartStore.cartList(i)
    }

    commonStore.getComponent('cart_list', 'cover_page');
    const { brand_list_page_cover } = storeToRefs(commonStore)

    const currentDetail = reactive({
        total_pages: 0,
        total_items: 0,
    })

    watch(paginationData, (newData)=> {
        currentDetail.total_pages = newData.last_page
        currentDetail.total_items = newData.total
    })

    const form = reactive({
        total_price: 0,
        total_shipping: 0,
        estimate_price: 0,
        selected_cart_id: [],
        process_payment_locker: true,
        cart_key: ""
    })

    watch(form, async (newData)=> {
        cartCalculation(newData)
    })

    const adjustItemQuantity = async (action, data, index) => {
        let quantity = data.quantity
        const product_current_quantity = data.product_option_details.quantity
        // const current_id = `current_quantity_${data.id}`
        
        if(quantity == product_current_quantity){
            toast.warning("quantity_reached_limit")
        }

        if(action == "add"){
            quantity += 1
        }
        else {
            quantity -= 1
        }

        if(quantity <= 0){
            return removeCartItem(data, index)
        }

        const result = await cartStore.adjustCartDetail({ id: data.id, quantity })
        if(!result.status || !result){
            let current_error_message = result.message
            if(typeof current_error_message != 'string'){
                current_error_message = "something_went_wrong"
            }
            return toast.error(current_error_message)
        }
        cartList.value[index] = result.data

        cartCalculation(form)
    }

    const removeCartItem = async(data, index) => {
        const result = await cartStore.removeCartItem(data.id)
        if(!result.status || !result){
            let current_error_message = result.message
            if(typeof current_error_message != 'string'){
                current_error_message = "something_went_wrong"
            }
            return toast.error(current_error_message)
        }

        cartStore.deleteItem(index)
        calculateTotal(data)

        return toast.info("remove_item_success")
    }

    const calculateTotal = (currentData) => {

        form.total_price -= parseFloat(currentData.total_price)
        form.estimate_price -= parseFloat(currentData.total_price)

        let newList = form.selected_cart_id.filter((item)=> { return item != currentData.id })
        form.selected_cart_id = newList

        // Current list update check box
        console.log("current selected cart list: ", form.selected_cart_id)

    }

    const cartCalculation = async (form) => {
        let finalTotal = 0
        const currentSelectedItem = form.selected_cart_id
        const currentCartItems = cartList.value
        currentSelectedItem.forEach((item)=> {
            let currentItemDetail = currentCartItems.find((data)=> { return data.id == item })
            finalTotal += parseFloat(currentItemDetail.total_price)
        })
        form.total_price = finalTotal
        form.estimate_price = finalTotal

        if(form.selected_cart_id.length == 0){
            form.process_payment_locker = true
        }
        else {
            form.process_payment_locker = false
        }

        cartStore.selected_cart_item = form.selected_cart_id
    }

    const generateCartKey = async () => {
        const result = await cartStore.encryptionCartItem("encrypt", { cart_id: cartStore.selected_cart_item })
        if(!result.status){
            return toast.error(result.message)
        }

        router.push({ name: 'prepayment', params: { cart_key: result.data } })
    }

    window.onscroll = function(ev) {
        if ((window.innerHeight + Math.round(window.scrollY)) >= document.body.offsetHeight) {
            if(currentPageData.value < currentDetail.total_pages && !currentProcess.value){
                const next_page = currentPageData.value + 1
                cartStore.cartList(next_page)
            }
        }
    };
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

    .cart_item_count {
        margin: 20px 0px 20px 30px
    }

    .cart_item {
        display: flex;
        width: 100%;
        /* justify-content: space-around; */
    }

    .cart_details {
        width: 85%;
        height: 180px;
        margin: 30px 0px;
        display: flex;
        box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
        background: transparent;
    }

    .cart_checkbox {
        width: 10%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cart_checkbox_item {
        accent-color: red;
        width: 18px;
        height: 18px;
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

    .payment_section a {
        text-decoration: none;
    }

    .payment_section button {
        width: 300px;
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