<template>
    <Carousel id="gallery" :items-to-show="1" :wrap-around="true" v-model="currentSlide" :autoplay="5000" :transition="500">
        <Slide v-for="slide in carouselList" :key="slide">
            <div class="carousel__item full" :style="{ background: `url(${slide.image_url})` }">
            </div>
        </Slide>
        <template #addons>
            <Navigation />
        </template>
    </Carousel>

    <Carousel
        id="thumbnails"
        :items-to-show="carouselList.length"
        :wrap-around="true"
        v-model="currentSlide"
        ref="carousel"
        class="thumbnails_bar"
    >
        <Slide v-for="(slide, index) in carouselList" :key="slide" class="thumb_container">
            <div class="carousel__item thumb" @click="slideTo(index)" :style="{ background: `url(${slide.image_url})` }"></div>
        </Slide>
    </Carousel>
</template>

<script setup>
    import { ref } from 'vue'
    import { Carousel, Slide, Navigation } from 'vue3-carousel'

    import 'vue3-carousel/dist/carousel.css'

    const currentSlide = ref(0)

    const slideTo = (val) => {
        console.log(currentSlide.value)
        currentSlide.value = val
    }

    const props = defineProps({ carouselList: Object })
    const carouselList = props.carouselList
</script>

<style>
    .thumbnails_bar {
        margin: 20px 0px;
    }

    .thumb_container {
        /* width: auto !important;
        justify-content: left !important;
        overflow-x: visible;
        margin-left: 50px; */
    }

    .carousel__slide--active .thumb {
        opacity: 1 !important;
    }

    .thumb {
        width: 85px;
        height: 85px;
        overflow: hidden;
        background-attachment: fixed !important;
        background-position: center !important;
        background-size: cover !important;
        opacity: 0.4 !important;
        transition: all 10ms ease-in-out;
    }

    .full {
        width: 100%;
        height: auto;
        min-height: 600px;
        background-attachment: fixed !important;
        background-position: center !important;
        background-size: cover !important;
    }

    .carousel__next,
    .carousel__prev {
        color: #e80202 !important;
    }

    .carousel__next:hover,
    .carousel__prev:hover {
        opacity: 0.6 !important;
    }
</style>