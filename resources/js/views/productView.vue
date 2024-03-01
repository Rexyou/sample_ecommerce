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
                    <div class="option_list" v-for="(options, index) in product_detail.product_options" :key="index">
                        <span>{{ index.charAt(0).toUpperCase() + index.slice(1) }}</span>
                        <div class="option_detail">
                            <button v-for="(option, index2) in options" :key="index2" :id="index+'_'+index2" @click="currentSelectOption(index, index2)">{{ option }}</button>
                        </div>
                    </div>
                    <div class="button_action">
                        <button class="button_add_to_cart" id="button_add_to_cart">
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
    import { reactive, watch } from 'vue'
    import { storeToRefs } from 'pinia';
    import { useRoute } from 'vue-router'
    import { useCommonStore } from '../store/general';
    import productSlider from '../components/ProductSlider.vue'
    import { isEqual } from 'lodash'

    const route = useRoute()
    const params = route.params;
    const product_id = params.product_id;

    const commonStore = useCommonStore()
    commonStore.getProduct(product_id)
    const { product_detail } = storeToRefs(commonStore)
    console.log(product_detail.value)

    const form = reactive({
        product_id: 0
    })

    let currentSelection = {};
    const currentSelectOption = (key, value) => {

        console.log("length: ", Object.keys(currentSelection).length)
        if(Object.keys(currentSelection).length > 0 && (key in currentSelection) && currentSelectOption[key] !== value){
            document.getElementById(`${key+'_'+currentSelection[key]}`).classList.remove('active_option')
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

                    return form.product_id = detail.id
                }
            })
        }

    }

    watch(form, (newForm, oldForm)=> {
        console.log("current form: ", newForm)
    })

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

    .product_detail .option_list .option_detail button {
        border-radius: 45px;
    }

    .product_detail .option_list .option_detail button {
        background: none;
        border: 0.5px solid #e80202;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
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