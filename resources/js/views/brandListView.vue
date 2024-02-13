<template>
    <div>
        <div class="cover_page_section" :style="{ backgroundImage: `url(${brand_list_page_cover.image_url})` }">
            <h1>Brands</h1>
        </div>
        <div class="brand_list">'
            <div v-if="Object.keys(brand_list).length > 0">
                <div v-for="(data, index) in brand_list" :key="index" class="listing">
                    <div class="title">
                        <h1>{{ index.toUpperCase() }}</h1>
                    </div>
                    <div class="list_data">
                        <div v-for="(brand_data, index2) in data" :key="index2" class="brand_container">
                            <router-link :to="{ name: 'brand', params: { brand_id: brand_data.id }, replace: true }">
                                <div v-if="brand_data.icon_image_url !== null" :style="{ backgroundImage: `url(${brand_data.icon_image_url})` }" class="brand_image"></div>
                                <div v-else class="brand_image_2" :style="{ background: randomColor() }">
                                    <h1>{{ getFirstLetter(brand_data.name) }}</h1>    
                                </div>
                                <span class="brand_title">{{ brand_data.name }}</span>
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
            <h1 v-else>Current no brands available</h1>
        </div>
    </div>
</template>

<script setup>
    import { watch, onMounted, computed, ref } from 'vue'
    import { storeToRefs } from 'pinia'
    import { useCommonStore } from "../store/general";

    const commonStore = useCommonStore()
    onMounted(async()=> {
        commonStore.getComponent('brand_list', 'cover_page');
        commonStore.getBrandsList()
    })
    const { brand_list_page_cover, brand_list } = storeToRefs(commonStore)

    const getFirstLetter = (data) => {
        const array_list = data.split("")
        const alphabet = array_list[0];

        return alphabet.toUpperCase()
    }

    const randomColor = () => {
        var randomColor = Math.floor(Math.random()*16777215).toString(16);
        randomColor = '#'+randomColor

        if(randomColor.length === 6){
            randomColor = randomColor+Math.floor(Math.random() * 10);
        }
        return randomColor
    }
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
        background: rgba(0, 0, 0, 0.3);
    }

    .cover_page_section h1 {
        color: white;
        font-size: 90px;
        position: absolute;
        z-index: 1;
    }

    .brand_list .listing {
        display: flex;
        flex-direction: row;
        justify-content: space-around;
        width: 80%;
        margin: 20px auto;
        padding: 50px 10px;
        border-bottom: 1px solid black;
    }

    .brand_list .listing .title {
        font-size: 45px;
        width: 180px;
        height: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        background: red;
        border-radius: 10px;
        box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px;    
    }

    .brand_list .listing .list_data {
        width: 60%;
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        text-align: center;
        justify-content: space-evenly;
        align-items: center;
    }

    .brand_list .listing .list_data .brand_container {
        width: 150px;
        height: auto;
    }

    .brand_list .listing .list_data .brand_image, 
    .brand_list .listing .list_data .brand_image_2 {
        /* border: 1px solid black; */
        width: 100%;
        height: 150px;
        border-radius: 50%;
        position: relative;
        /* background-attachment: fixed; */
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;
    }

    .brand_list .listing .list_data .brand_image_2 {
        border: none;
    }

    .brand_list .listing .list_data .brand_image_2 h1 {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        font-size: 40px;
        color: white;
    }

    .brand_list .listing .list_data .brand_title {
        display: block;
        font-size: 20px;
        margin: 15px auto;
    }
</style>