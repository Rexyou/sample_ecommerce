<template>
    <div>
        <Slider :carousel-list="homepage_carousel" type="homepage_carousel" />
        <TopSelling />
    </div>
</template>

<script setup>
    import { storeToRefs } from 'pinia'
    import { useCommonStore } from '../store/general'
    import Slider from '../components/Slider.vue'
    import TopSelling from '../components/TopSelling.vue'

    const commonStore = useCommonStore();
    commonStore.getComponent('home', 'carousel');
    const { homepage_carousel } = storeToRefs(commonStore)
</script>

<style lang="scss" scoped>

</style>