<template>
    <div class="top_selling">
        <h1>Top Selling of The month</h1>
        <div class="top_selling_items" v-if="homepage_top_selling.length > 0">
            <div class="item" v-for="(item, index) in homepage_top_selling" :key="index" :style="{ background: `url(${item.image_url})` }">
                <span>{{ index+1 }}</span>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { storeToRefs } from "pinia";
    import { useCommonStore } from "../store/general";

    const commonStore = useCommonStore();
    const { homepage_top_selling } = storeToRefs(commonStore)

    commonStore.getComponent('home', 'top_selling');
</script>

<style scoped>
    .top_selling {
        width: 100%;
        height: 100vh;
        padding: 30px 80px;
        color: white;
        background: rgb(0, 0, 0);
    }

    .top_selling h1 {
        font-size: 45px;
        margin-bottom: 20px;
        text-align: center;
    }

    .top_selling .top_selling_items {
        width: 100%;
        display: grid;
        grid-template-columns: auto auto auto auto;
        gap: 15px;
    }

    .top_selling .top_selling_items .item {
        background-position: top !important;
        background-size: cover !important;
        min-height: 150px;
        filter: grayscale(100%);
        transition: .6s all ease-in-out;
        position: relative;
    }

    .top_selling .top_selling_items .item:hover {
        filter: grayscale(0%);
        cursor: pointer;
    }

    .top_selling .top_selling_items .item span {
        position: absolute;
        left: 0px;
        top: 10px;
        background: #e80202;
        width: 25px;
        height: 25px;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .top_selling .top_selling_items .item:nth-child(1) {
        display: grid;
        grid-column-start: 1;
        grid-column-end: 2;
        grid-row-start: 1;
        grid-row-end: 5;
    }

    .top_selling .top_selling_items .item:nth-child(2) {
        display: grid;
        grid-column-start: 2;
        grid-column-end: 3;
        grid-row-start: 1;
        grid-row-end: 4;
    }

    .top_selling .top_selling_items .item:nth-child(3) {
        grid-column-start: 3;
        grid-column-end: 5;
        grid-row-start: 1;
        grid-row-end: 3;
        background-position-y: 35% !important;
    }

    .top_selling .top_selling_items .item:nth-child(4) {
        background-position-y: 35% !important;
    }

    .top_selling .top_selling_items .item:nth-child(5) {
        background-position-y: 45% !important;
    }

    .top_selling .top_selling_items .item:nth-child(7) {
        background-position-y: 45% !important;
    }

    .top_selling .top_selling_items .item:nth-child(8) {
        background-position-y: 25% !important;
    }

    .top_selling .top_selling_items .item:nth-child(9) {
        grid-column-start: 1;
        grid-column-end: 3;
        background-position-y: 30% !important;
    }

    .top_selling .top_selling_items .item:nth-child(10) {
        grid-column-start: 3;
        grid-column-end: 5;
        background-position-y: 10% !important;
    }

    @media screen and (max-width: 800px) {

        .top_selling {
            padding: 20px 30px;
        }

        .top_selling h1 {
            font-size: 30px;
        }

        .top_selling .top_selling_items {
            grid-template-columns: auto auto;
        }

        .top_selling .top_selling_items .item:nth-child(1) {
            grid-column-start: 1;
            grid-column-end: 5;
            grid-row-start: 1;
            grid-row-end: 2;
        }

        .top_selling .top_selling_items .item:nth-child(2) {
            grid-column-start: 5;
            grid-column-end: 7;
            grid-row-start: 1;
            grid-row-end: 2;
        }

        .top_selling .top_selling_items .item:nth-child(3) {
            grid-column-start: 1;
            grid-column-end: 3;
            grid-row-start: 2;
            grid-row-end: 3;
        }

        .top_selling .top_selling_items .item:nth-child(4) {
            grid-column-start: 3;
            grid-column-end: 5;
        }

        .top_selling .top_selling_items .item:nth-child(5) {
            grid-column-start: 5;
            grid-column-end: 7;
        }

        .top_selling .top_selling_items .item:nth-child(6) {
            grid-column-start: 1;
            grid-column-end: 3;
        }

        .top_selling .top_selling_items .item:nth-child(7) {
            grid-column-start: 3;
            grid-column-end: 5;
        }

        .top_selling .top_selling_items .item:nth-child(8) {
            grid-column-start: 5;
            grid-column-end: 7;
        }

        .top_selling .top_selling_items .item:nth-child(9) {
            grid-column-start: 1;
            grid-column-end: 4;
        }

        .top_selling .top_selling_items .item:nth-child(10) {
            grid-column-start: 4;
            grid-column-end: 7;
        }
    }

    @media screen and (max-width: 500px) {
        .top_selling {
            height: auto;
        }

        .top_selling h1 {
            font-size: 18px;
        }

        .top_selling .top_selling_items .item:nth-child(1) {
            grid-column-start: 1;
            grid-column-end: 7;
        }

        .top_selling .top_selling_items .item:nth-child(2) {
            grid-column-start: 1;
            grid-column-end: 7;
            grid-row-start: 2;
            grid-row-end: 3;
        }

        .top_selling .top_selling_items .item:nth-child(3) {
            grid-column-start: 1;
            grid-column-end: 4;
            grid-row-start: 3;
            grid-row-end: 4;
        }

        .top_selling .top_selling_items .item:nth-child(5),
        .top_selling .top_selling_items .item:nth-child(7) {
            grid-column-start: 1;
            grid-column-end: 4;
        }

        .top_selling .top_selling_items .item:nth-child(4),
        .top_selling .top_selling_items .item:nth-child(6),
        .top_selling .top_selling_items .item:nth-child(8) {
            grid-column-start: 4;
            grid-column-end: 7;
        }
    }
</style>