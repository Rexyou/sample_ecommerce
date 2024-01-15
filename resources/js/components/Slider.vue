<template>
    <div>
        <Flicking :options="{ align: 'prev', circular: true }" @move-end="onMoveEnd" class="carousel" :plugins="plugins">
            <div class="panel" v-for="(item, index) in currentList.carouselList" :key="index" :style="{ backgroundImage: `url(${item.image_url})` }">
                <div class="description">
                    <h1>{{ item.title }}</h1>
                    <p>{{ item.description }}</p>
                    <button class="information_button">More Infomation</button>
                </div>
            </div>
            <template #viewport>
                <div class="flicking-pagination"></div>
            </template>
        </Flicking>
    </div>
</template>

<script setup>

    import { AutoPlay, Pagination } from '@egjs/flicking-plugins'
    import "@egjs/flicking-plugins/dist/flicking-plugins.css";
    import "@egjs/flicking-plugins/dist/pagination.css";

    const plugins = [
        new AutoPlay({ duration: 6000, direction: "NEXT" }),
        new Pagination({ type: 'bullet' })
    ];

    const currentList= defineProps({
        carouselList: Object
    })
</script>

<style>

    .carousel {
        position: relative;
    }

    .carousel .panel {
        width: 100%;
        height: 100vh;
        background-attachment: fixed;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: 50% 50%;
        position: relative;
    }

    .carousel .panel:after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(110deg, rgba(0,0,0,0.2) 25%, rgba(0,0,0,0.4) 35%, rgba(0,0,0,0.8) 55%, rgba(0,0,0,1) 75%);
    }

    .carousel .panel .description {
        width: 500px;
        height: 100%;
        position: absolute;
        color: white;
        z-index: 10;
        right: 50px;
        display: flex;
        flex-direction: column;
        /* align-items: center; */
        justify-content: center;
        /* border: 1px solid yellow; */
        text-align: left;
    }

    .carousel .panel .description h1,
    .carousel .panel .description p {
        width: 100%;
    }

    .carousel .panel .description h1 {
        font-size: 35px;
    }

    .carousel .panel .description p {
        font-size: 18px;
        margin-top: 10px;
        line-height: 25px;
    }

    .carousel .panel .description .information_button {
        background: #e80202;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 15px;
        width: fit-content;
        margin-top: 20px;
    }

    .carousel .flicking-pagination-bullet {
        /* background: white !important; */
        border: 1px solid white;
    }

    .carousel .flicking-pagination-bullet-active {
        background: #e80202 !important;
        border: none !important;
    }
</style>