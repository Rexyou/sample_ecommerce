<template>
    <div class="product">
        <div v-if="product_detail.length != 0">
            <div class="product_section">
                <div class="product_image">
                    <productSlider :carousel-list="product_detail.product_images" />
                </div>
                <div class="product_detail">
                    <h1>{{ product_detail.name }}</h1>
                    <h2>{{ product_detail.code_name }}</h2>
                    <p>{{ product_detail.description }}</p>
                    <h3 v-if="token != null && form.per_unit_price==0">RM {{ product_detail.price_range }}</h3>
                    <h3 v-if="form.per_unit_price !== 0">RM {{ form.per_unit_price }}</h3>
                    <div class="option_list" v-for="(options, index) in product_detail.product_options" :key="index">
                        <span>{{ index.charAt(0).toUpperCase() + index.slice(1) }}</span>
                        <div class="option_detail">
                            <button v-for="(option, index2) in options" :key="index2" :id="index+'_'+index2" @click="currentSelectOption(index, index2)">{{ option }}</button>
                        </div>
                    </div>
                    <div class="option_list">
                        <span>Quantity</span>
                        <div class="option_detail2">
                            <button @click.prevent="adjustItemQuantity('minus')">
                                <v-icon name="hi-minus-circle"/>
                            </button>
                            <div class="current_quantity">
                                <span>{{ form.quantity }}</span>
                            </div>
                            <button @click.prevent="adjustItemQuantity('add')">
                                <v-icon name="hi-plus-circle"/>
                            </button>
                            <div class="remaining_items">
                                <span v-if="form.remaining_item == 0">(Remain {{ product_detail.total_quantity }} items)</span>
                                <span v-else>(Remain {{ form.remaining_item }} items)</span>
                            </div>
                        </div>
                    </div>
                    <div class="button_action">
                        <button class="button_add_to_cart" id="button_add_to_cart" @click.prevent="addToCart">
                            <v-icon name="bi-cart"/>
                            <span>Add to Cart</span>
                        </button>
                        <button class="button_save_to_list">
                            <v-icon name="bi-bookmark"/>
                            <span>Save to List</span> 
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            <h1>Product not found</h1>
        </div>
    </div>
</template>

<script setup>
    import { reactive, watch, onActivated, computed } from 'vue'
    import { useRoute } from 'vue-router'
    import { useCommonStore } from '../store/general';
    import productSlider from '../components/ProductSlider.vue'
    import { isEqual } from 'lodash'
    import { useAuthStore } from '../store/auth';
    import { toast } from 'vue3-toastify';

    const route = useRoute()
    const params = route.params;
    const product_id = params.product_id;

    const commonStore = useCommonStore()
    const authStore = useAuthStore()
    commonStore.getProduct(product_id)
    let product_detail = computed(()=> commonStore.product_detail)

    const token_details = computed(()=> authStore.token)
    const token = token_details.value

    watch(token_details, (newToken, oldToken)=> { token = newToken.value })

    const form = reactive({
        product_id: 0,
        option_id: 0,
        quantity: 1,
        remaining_item: 0,
        per_unit_price: 0,
        total_price: 0
    })

    let currentSelection = {};
    const currentSelectOption = (key, value) => {

        if(Object.keys(currentSelection).length > 0 && (key in currentSelection) && currentSelectOption[key] !== value){
            document.getElementById(`${key+'_'+currentSelection[key]}`).classList.remove('active_option')
            form.quantity = 1
        }

        currentSelection[key] = value;

        document.getElementById(`${key+"_"+value}`).classList.add('active_option')

        if(Object.keys(currentSelection).length == Object.keys(product_detail.value.product_options).length){
            const current_option_details = product_detail.value.product_option_details;
            current_option_details.forEach((detail)=> {
                if(isEqual(detail.options, currentSelection)){
                    if(detail.quantity == 0)
                    {
                        document.getElementById("button_add_to_cart").disabled = true;
                    }
                    else {
                        document.getElementById("button_add_to_cart").disabled = false;
                    }

                    form.remaining_item = detail.quantity;
                    form.option_id = detail.id

                    let price = detail.original_price
                    if(token != null){
                        price = detail.member_price
                    }

                    form.per_unit_price = price
                    form.total_price = price * form.quantity
                    form.product_id = detail.id
                }
            })
        }

    }

    const adjustItemQuantity = (action) => {

        if(form.product_id == 0){
            const current_option_details = product_detail.value.product_option_details;
            form.product_id = current_option_details[0].id
        }

        if(form.remaining_item == 0){
            return;
        }

        if(action == "add"){
            if(form.quantity == form.remaining_item){
                return;
            }

            form.quantity += 1;
        }
        else {
            if(form.quantity == 1){
                return
            }

            if(form.quantity != 0){
                form.quantity -= 1;
            }
        }

        form.total_price = form.per_unit_price * form.quantity

    }

    const addToCart = () => {
        if(token == null){
            toast.warning("Please login to continue.")
        }

        console.log("current form: ", form)
    }

</script>

<style scoped>
    .product {
        padding-top: 150px;
    }

    .product_section {
        display: flex;
    }

    .product_image {
        width: 60%;
        padding: 10px 30px;
    }

    .product_detail {
        width: 40%;
        line-height: 40px;
        padding: 10px 20px;
    }

    .product_detail h1 {
        font-size: 50px;
        margin-bottom: 20px;
    }

    .product_detail h2 {
        font-size: 25px;
        margin-bottom: 10px;
    }

    .product_detail p {
        font-size: 18px;
        margin-bottom: 20px;
    }

    .product_detail .option_list {
        display: flex;
        margin: 20px 0px;
        width: 100%;
    }

    .product_detail .option_list span {
        width: 20%;
        font-size: 18px;
    }

    .product_detail .option_list .option_detail {
        width: 60%;
        display: grid;
        grid-template-columns: auto auto auto;
        gap: 10px 20px;
    }

    .product_detail .option_list .option_detail2 {
        width: 50%;
        display: grid;
        grid-template-columns: auto auto auto auto;
        align-items: center;
    }
    
    .product_detail .option_list .option_detail button {
        background: none;
        border: 0.5px solid #e80202;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        border-radius: 45px;
    }

    .product_detail .option_detail2 {
        width: auto;
        display: flex;
        align-items: center;
    }

    .product_detail .option_detail2 button {
        border: none;
        box-shadow: none;
        background: none;
    }

    .product_detail .option_detail2 .current_quantity {
        text-align: center;
        font-size: 30px;
    }

    .product_detail .option_detail2 button svg {
        width: 40px;
        height: 40px;
    }

    .product_detail .option_detail2 button:hover {
        cursor: pointer;
    }

    .product_detail .option_detail2 .remaining_items span {
        font-size: 16px;
    }

    .active_option {
        background: #e80202 !important;
        color: white;
    }

    .product_detail .option_list:nth-child(odd) .option_detail button:hover,
    .product_detail .option_list:nth-child(even) .option_detail button:hover {
        cursor: pointer;
    }

    .product_detail .button_action {
        display: flex;
        /* flex-direction: column; */
        justify-content: left;
        align-content: center;
    }

    .product_detail .button_action .button_add_to_cart,
    .product_detail .button_action .button_save_to_list {
        font-size: 20px;
        border: none;
        margin: 10px 10px 10px 0px;
        height: 60px;
        /* width: 200px; */
        flex: 1;
        border-radius: 45px;
        transition: all .3s ease-in-out;
    }

    .product_detail .button_action .button_add_to_cart:hover {
        cursor: pointer;
        background: #18a200;
    }

    .product_detail .button_action .button_save_to_list:hover {
        cursor: pointer;
        background: #cf6201;

    }

    .product_detail .button_action .button_add_to_cart svg,
    .product_detail .button_action .button_save_to_list svg {
        width: 28px;
        height: 28px;
        margin-right: 10px;
    }

    .product_detail .button_action .button_add_to_cart {
        background: #21dd00;
        color: white;   
    }

    .product_detail .button_action .button_add_to_cart:disabled,
    .product_detail .button_action .button_add_to_cart[disabled] {
        background: #808080;
    }

    .product_detail .button_action .button_save_to_list {
        background: #fa7705;
        color: white;
    }
</style>