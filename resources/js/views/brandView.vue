<template>
    <div>
        <div class="cover_page_section" :style="{ backgroundImage: `url(${brand_details.banner_image_url})` }">
            <h1>{{ brand_details.name }}</h1>
        </div>
        <div class="product_list_view">
            <Filter />
            <List />
        </div>
    </div>
</template>

<script setup>
    import { useRoute } from 'vue-router'
    import { useCommonStore } from '../store/general';
    import { storeToRefs } from 'pinia'
    import Filter from '../components/Filter.vue';
    import List from '../components/List.vue'

    const route = useRoute()
    const brand_id = route.params.brand_id;
    const commonStore = useCommonStore()
    commonStore.getBrand(brand_id)
    const { brand_details } = storeToRefs(commonStore)
</script>

<style scoped>
    .cover_page_section {
        height: 60vh;
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
        background: rgba(0, 0, 0, 0.4);
    }

    .cover_page_section h1 {
        color: white;
        font-size: 90px;
        position: absolute;
        z-index: 1;
    }

    .product_list_view {
        display: flex;
        flex-direction: row;
    }
</style>