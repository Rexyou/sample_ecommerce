<template>
    <div class="product_list">
        <div class="sorting_group">
            <div class="item_show">
                <label for="show_item">Item Show Per Page: </label>
                <select name="show_item" id="show_item" v-model="paginate" @change="changePaginateState">
                    <option value="15">15</option>
                    <option value="30">30</option>
                    <option value="45">45</option>
                </select>
            </div>
            <div class="item_sort">
                <label for="sort_method">Sort By: </label>
                <select name="sort_method" id="sort_method" v-model="sorting" @change="changeSortingState">
                    <option value="best_selling">Best Selling</option>
                    <option value="created_desc">Latest</option>
                    <option value="created_asc">Earlier</option>
                </select>
            </div>
        </div>
        <div v-if="brand_product_list.length > 0">
            <div class="list_items">
                <router-link class="item" v-for="(list, index) in brand_product_list" :key="index" 
                    :style="{ background: `url(${list?.product_images_filter[0].image_url})` }"
                    :to="{ name: 'product', params: { product_id: list.id } }">
                        <div class="name">
                            <span>{{ list.name }}</span>
                        </div>
                        <div class="price">
                            <span v-if="list.price_min.original_price == list.price_max.original_price">MYR {{ list.price_min.original_price }}</span>
                            <span v-else>MYR {{ list.price_min.original_price }} - MYR {{ list.price_max.original_price }}</span>
                        </div>
                        <div class="selling_status" :style="{ background: '#e3e300' }" v-if="list.selling_status == 0">
                            <span>Preorder</span>
                        </div>
                        <div class="selling_status" :style="{ background: 'green' }" v-if="list.selling_status == 1">
                            <span>In Stock</span>
                        </div>
                        <div class="selling_status" :style="{ background: 'orange' }" v-if="list.selling_status == 2">
                            <span>Low Stock</span>
                        </div>
                        <div class="selling_status" :style="{ background: 'black' }" v-if="list.selling_status == 3">
                            <span>Out Of Stock</span>
                        </div>
                </router-link>
            </div>
            <div class="example-one">
                <vue-awesome-paginate
                    :total-items="brand_product_list_pagination.total"
                    :items-per-page="brand_product_list_pagination.per_page"
                    :max-pages-shown="45"
                    v-model="currentPage"
                    :on-click="onClickHandler"
                />
            </div>
        </div>
        <div class="no_items_display" v-else>
            <h1>Currently no products</h1>
        </div>
    </div>
</template>

<script setup>
    import { ref } from 'vue'
    import { storeToRefs } from "pinia";
    import { useCommonStore } from "../store/general";
    import { useRoute } from 'vue-router';

    const route = useRoute()
    const brand_id = route.params.brand_id;

    const commonStore = useCommonStore()
    const { brand_product_list, brand_product_list_pagination, paginate, sorting } = storeToRefs(commonStore);

    const currentPage = ref(1);

    const onClickHandler = async (page) => {
        const params = `page=${page}`
        await commonStore.getBrandProducts(brand_id, params);
        window.scrollTo(0,450);
    };

    const changePaginateState = () => { commonStore.paginate = paginate } 
    const changeSortingState = () => { commonStore.sorting = sorting } 
</script>

<style>
    .product_list {
        width: 75%;
        height: auto;
        padding: 30px 50px;
        min-height: 800px;
    }

    .sorting_group {
        display: flex;
    }

    .sorting_group .item_show,
    .sorting_group .item_sort {
        height: 30px;
        display: flex;
        align-items: center;
        margin-left: auto;
    }

    .sorting_group .item_sort {
        margin-left: 30px;
    }

    .sorting_group .item_show label,
    .sorting_group .item_sort label {
        margin-right: 10px;
        font-size: 16px;
    }

    .sorting_group .item_show select,
    .sorting_group .item_sort select {
        padding: 5px;
        width: 150px;
        font-size: 16px;
    }

    .list_items {
        display: grid;
        grid-template-columns: auto auto auto;
        gap: 50px 40px;
        margin: 30px auto;
    }

    .list_items .item {
        padding: 160px 0px;
        background-position: center !important;
        background-repeat: no-repeat !important;
        background-size: cover !important;
        position: relative;
        border-radius: 20px;
        /* overflow: hidden; */
        box-shadow: rgba(0, 0, 0, 0.17) 0px -23px 25px 0px inset, rgba(0, 0, 0, 0.15) 0px -36px 30px 0px inset, rgba(0, 0, 0, 0.1) 0px -79px 40px 0px inset, rgba(0, 0, 0, 0.06) 0px 2px 1px, rgba(0, 0, 0, 0.09) 0px 4px 2px, rgba(0, 0, 0, 0.09) 0px 8px 4px, rgba(0, 0, 0, 0.09) 0px 16px 8px, rgba(0, 0, 0, 0.09) 0px 32px 16px;
    }

    .list_items .item .name,
    .list_items .item .selling_status,
    .list_items .item .price {
        position: absolute;
        color: white;
        padding: 10px 20px;
    }

    .list_items .item .name {
        left: -5px;
        top: 20px;
        width: 250px;
        background: red;
        z-index: 2;
    }

    .list_items .item .price {
        bottom: 35px;
        right: 0px;
        background: rgba(0, 0, 0, 0.7);
        max-width: 250px;
        padding: 10px 20px;
        text-align: center;
    }

    .list_items .item .selling_status {
        background: black;
        width: 100%;
        left: 0;
        right: 0;
        text-align: center;
        bottom: -20px;
        border-radius: 0px 0px 20px 20px;
    }

    .pagination-container {
        display: flex;
        column-gap: 10px;
        margin-top: 15px;
    }

    .paginate-buttons {
        height: 40px;
        width: 40px;
        border-radius: 20px;
        cursor: pointer;
        background-color: rgb(242, 242, 242);
        border: none;
        color: black;
    }

    .paginate-buttons:hover {
        background-color: #d8d8d8;
    }

    .active-page {
        background-color: #3498db;
        border: 1px solid #3498db;
        color: white;
    }
    
    .active-page:hover {
        background-color: #2988c8;
    }

    .no_items_display {
        min-height: 700px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>