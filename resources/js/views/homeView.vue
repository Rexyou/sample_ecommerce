!<template>
    <div>
        <Slider :carousel-list="carouselList" />
    </div>
</template>

<script setup>
    import { onMounted } from 'vue'
    import { useCommonStore } from '../store/general'
    import Slider from '../components/Slider.vue'

    const commonStore = useCommonStore();

    onMounted(async () => {
        await commonStore.homepageCarousel();
    })

    const carouselList = commonStore.homepage_carousel;

</script>

<style lang="scss" scoped>

</style>