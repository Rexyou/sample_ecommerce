<template>
    <div class="filter_list">
        <h2 class="filter_list_title">Filter List</h2>
        <div class="search_bar">
            <input type="text" placeholder="Search..." v-model="form.search_input" />
            <v-icon name="bi-search" class="search_icon" />
        </div>
        <div class="type_bar">
            <h3>Type</h3>
            <div class="selection">
                <div class="options">
                    <input type="checkbox" name="type" id="type_1" value="1" v-model="form.type">
                    <label for="type_1">New</label>
                </div>
                <div class="options">
                    <input type="checkbox" name="type" id="type_2" value="2" v-model="form.type">
                    <label for="type_2">Preorder</label>
                </div>
                <div class="options">
                    <input type="checkbox" name="type" id="type_3" value="3" v-model="form.type">
                    <label for="type_3">Second Hand</label>
                </div>
                <div class="options">
                    <input type="checkbox" name="type" id="type_4" value="4" v-model="form.type">
                    <label for="type_4">Flaw</label>
                </div>
                <div class="options">
                    <input type="checkbox" name="type" id="type_5" value="5" v-model="form.type">
                    <label for="type_5">Sale</label>
                </div>
            </div>
        </div>
        <div class="stock_bar">
            <h3>Stock Status</h3>
            <div class="selection">
                <div class="options">
                    <input type="checkbox" name="selling_status" id="selling_status_0" value="0" v-model="form.selling_status">
                    <label for="selling_status_0">Preorder</label>
                </div>
                <div class="options">
                    <input type="checkbox" name="selling_status" id="selling_status_1" value="1" v-model="form.selling_status">
                    <label for="selling_status_1">In Stock</label>
                </div>
                <div class="options">
                    <input type="checkbox" name="selling_status" id="selling_status_2" value="2" v-model="form.selling_status">
                    <label for="selling_status_2">Low Stock</label>
                </div>
                <div class="options">
                    <input type="checkbox" name="selling_status" id="selling_status_3" value="3" v-model="form.selling_status">
                    <label for="selling_status_3">Out Of Stock</label>
                </div>
            </div>
        </div>
        <div class="price_bar">
            <h3>Price</h3>
            <div class="price_range">
                <label for="min_price">Min: </label>
                <input class="form_control_container__time__input" type="number" id="fromInput" value="100" min="0" max="10000" v-model="form.price_min"/>
            </div>
            <div class="price_range">
                <label for="max_price">Max: </label>
                <input class="form_control_container__time__input" type="number" id="toInput" value="3000" min="0" max="10000" v-model="form.price_max"/>
            </div>
            <!-- Slider -->
            <div class="sliders_control">
                <input type="range" class="range" value="100" min="0" max="10000" id="fromSlider"/>
                <input type="range" class="range" value="3000" min="0" max="10000" id="toSlider"/>
            </div>
        </div>
        <div class="search_button_bar">
            <button class="search_button" @click.prevent="filterData">Search</button>
        </div>
    </div>
</template>

<script setup>
    import { onMounted, reactive } from 'vue'
    import { useCommonStore } from '../store/general';

    const props = defineProps({ brand_id: String });
    const brand_id = props.brand_id;
    const commonStore = useCommonStore();
    const params = reactive({
        
    })
    commonStore.getBrandProducts(brand_id, params)

    function controlFromInput(fromSlider, fromInput, toInput, controlSlider) {
        const [from, to] = getParsed(fromInput, toInput);
        fillSlider(fromInput, toInput, '#C6C6C6', '#e80202', controlSlider);
        if (from > to) {
            fromSlider.value = to;
            fromInput.value = to;
        } else {
            fromSlider.value = from;
        }
    }
        
    function controlToInput(toSlider, fromInput, toInput, controlSlider) {
        const [from, to] = getParsed(fromInput, toInput);
        fillSlider(fromInput, toInput, '#C6C6C6', '#e80202', controlSlider);
        setToggleAccessible(toInput);
        if (from <= to) {
            toSlider.value = to;
            toInput.value = to;
        } else {
            toInput.value = from;
        }
    }

    function controlFromSlider(fromSlider, toSlider, fromInput) {
        const [from, to] = getParsed(fromSlider, toSlider);
        fillSlider(fromSlider, toSlider, '#C6C6C6', '#e80202', toSlider);
        if (from > to) {
            fromSlider.value = to;
            // fromInput.value = to;
            form.price_min = to;
        } else {
            // fromInput.value = from;
            form.price_min = from;
        }
    }

    function controlToSlider(fromSlider, toSlider, toInput) {
        const [from, to] = getParsed(fromSlider, toSlider);
        fillSlider(fromSlider, toSlider, '#C6C6C6', '#e80202', toSlider);
        setToggleAccessible(toSlider);
        if (from <= to) {
            toSlider.value = to;
            // toInput.value = to;
            form.price_max = to;
        } else {
            // toInput.value = from;
            form.price_max = from;
            toSlider.value = from;
        }
    }

    function getParsed(currentFrom, currentTo) {
        const from = parseInt(currentFrom.value, 10);
        const to = parseInt(currentTo.value, 10);
        return [from, to];
    }

    function fillSlider(from, to, sliderColor, rangeColor, controlSlider) {
        const rangeDistance = to.max-to.min;
        const fromPosition = from.value - to.min;
        const toPosition = to.value - to.min;
        controlSlider.style.background = `linear-gradient(
        to right,
        ${sliderColor} 0%,
        ${sliderColor} ${(fromPosition)/(rangeDistance)*100}%,
        ${rangeColor} ${((fromPosition)/(rangeDistance))*100}%,
        ${rangeColor} ${(toPosition)/(rangeDistance)*100}%, 
        ${sliderColor} ${(toPosition)/(rangeDistance)*100}%, 
        ${sliderColor} 100%)`;
    }

    function setToggleAccessible(currentTarget) {
        const toSlider = document.querySelector('#toSlider');
        if (Number(currentTarget.value) <= 0 ) {
            toSlider.style.zIndex = 2;
        } else {
            toSlider.style.zIndex = 0;
        }
    }

    onMounted(() => {
        const fromSlider = document.querySelector('#fromSlider');
        const toSlider = document.querySelector('#toSlider');
        const fromInput = document.querySelector('#fromInput');
        const toInput = document.querySelector('#toInput');
        fillSlider(fromSlider, toSlider, '#C6C6C6', '#e80202', toSlider);
        setToggleAccessible(toSlider);

        fromSlider.oninput = () => controlFromSlider(fromSlider, toSlider, fromInput);
        toSlider.oninput = () => controlToSlider(fromSlider, toSlider, toInput);
        fromInput.oninput = () => controlFromInput(fromSlider, fromInput, toInput, toSlider);
        toInput.oninput = () => controlToInput(toSlider, fromInput, toInput, toSlider);  
    })

    const form = reactive({
        search_input: "",
        type: [],
        selling_status: [],
        price_min: 100,
        price_max: 3000,
    })
    
    const filterData = async () => {
        
        let params = "";
        if(form.search_input && form.search_input != ""){
            params += `search_input=${form.search_input}`
        }

        if(form.type.length > 0){
            form.type.forEach((item)=> {
                params += `&type[]=${item}`
            })
        }

        if(form.selling_status.length > 0){
            form.selling_status.forEach((item)=> {
                params += `&selling_status[]=${item}`
            })
        }

        if(form.price_min && form.price_min >= 0){
            params += `&price_min=${form.price_min}`
        }

        if(form.price_max && form.price_max >= 0){
            params += `&price_max=${form.price_max}`
        }

        // Filtering the params
        if(params.charAt(0) == "&")
        {
            params = params.slice(1)
        }

        await commonStore.getBrandProducts(brand_id, params);
    }

</script>

<style scoped>
    .filter_list {
        width: 25%;
        height: auto;
        padding: 30px 45px;
    }

    .filter_list .filter_list_title {
        font-size: 28px;
    }

    .search_bar {
        width: 100%;
        border: 1px solid black;
        border-radius: 45px;
        display: flex;
        align-content: center;
        overflow: hidden;
        position: relative;
        margin: 30px 0px;
    }

    .search_bar input {
        width: 100%;
        padding: 15px;
        border: none;
        font-size: 16px;
    }

    .search_bar input:focus {
        outline: none;
    }

    .search_bar .search_icon {
        position: absolute;
        height: 100%;
        right: 10px;
        bottom: 0;
        top: 0;
        background: white;
    }

    .type_bar h3,
    .stock_bar h3,
    .price_bar h3 {
        font-size: 22px;
    }

    .type_bar .selection,
    .stock_bar .selection {
        display: flex;
        flex-direction: column;
        margin: 10px 0px;
    }

    .type_bar .selection .options,
    .stock_bar .selection .options
    {
        font-size: 18px;
        margin: 2px 0px;
    }

    .type_bar .selection .options label,
    .stock_bar .selection .options label
    {
        margin-left: 5px;
    }

    .type_bar, 
    .stock_bar,
    .price_bar {
        margin: 20px 0px;
    }

    .price_bar .price_range {
        font-size: 18px;
        margin-top: 10px;
        width: 100%;
        display: flex;
        align-items: center;
    }

    .price_bar .price_range label {
        display: inline-block;
        width: 20%;
    }

    .price_bar .price_range input {
        font-size: 16px;
        padding: 5px;
        width: 60%;
    }

    /* Price slider */
    .sliders_control {
        min-height: 50px;
        display: flex;
        align-items: center;
        position: relative;
    }

    .range {
        -webkit-appearance: none; 
        appearance: none;
        height: 2px;
        width: 100%;
        max-width: 300px;
        position: absolute;
        background-color: #C6C6C6;
        pointer-events: none;
    }

    .range::-webkit-slider-thumb {
        -webkit-appearance: none;
        pointer-events: all;
        width: 24px;
        height: 24px;
        background-color: #fff;
        border-radius: 50%;
        box-shadow: 0 0 0 1px #C6C6C6;
        cursor: pointer;
    }

    .range::-moz-range-thumb {
        -webkit-appearance: none;
        pointer-events: all;
        width: 24px;
        height: 24px;
        background-color: #fff;
        border-radius: 50%;
        box-shadow: 0 0 0 1px #C6C6C6;
        cursor: pointer;  
    }

    #fromSlider {
        height: 0;
        z-index: 1;
    }

    .search_button_bar {
        margin: 10px auto;
    }

    .search_button {
        width: 100%;
        height: 50px;
        border-radius: 45px;
        outline: none;
        border: none;
        color: white;
        background: #e80202;
        transition: all .3s ease-in-out;
        opacity: 0.5;
    }

    .search_button:hover {
        opacity: 1;
        cursor: pointer;
    }
</style>