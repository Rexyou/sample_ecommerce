<template>
    <div>
        <Slider :carousel-list="homepage_carousel" type="homepage_carousel" />
        <div><h1>sample</h1></div>
        <div><h1>sample</h1></div>
        <div><h1>sample</h1></div>
        <div><h1>sample</h1></div>
        <div><h1>sample</h1></div>
        <div><h1>sample</h1></div>
        <div><h1>sample</h1></div>
        <div><h1>sample</h1></div>
        <div><h1>sample</h1></div>
        <div><h1>sample</h1></div>
        <div><h1>sample</h1></div>
        <div><h1>sample</h1></div>
        <div><h1>sample</h1></div>
        <div><h1>sample</h1></div>
        <div><h1>sample</h1></div>
        <div><h1>sample</h1></div>
        <div><h1>sample</h1></div>
        <div><h1>sample</h1></div>
        <div><h1>sample</h1></div>
        <div><h1>sample</h1></div>
        <div><h1>sample</h1></div>
    </div>
</template>

<script setup>
    import { storeToRefs } from 'pinia'
    import { useCommonStore } from '../store/general'
    import Slider from '../components/Slider.vue'

    const commonStore = useCommonStore();
    commonStore.homepageCarousel();
    const { homepage_carousel } = storeToRefs(commonStore)
</script>

<style lang="scss" scoped>

</style>