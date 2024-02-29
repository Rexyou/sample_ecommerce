<template>
    <div>
        <!-- <Flicking :options="{ align: 'prev', circular: true }" @move-end="onMoveEnd" class="carousel2" :plugins="plugins">
            <div class="panel" v-for="(item, index) in currentList.carouselList" :key="index" :style="{ backgroundImage: `url(${item.image_url})` }">
            </div>
            <template #viewport>
                <div class="flicking-pagination"></div>
            </template>
        </Flicking> -->
        <flicking ref="flicking0" :options="{ bounce: 30 }" :plugins="plugins" class="full_group">
            <div class="flicking-panel full has-background-primary">
                <img class="panel-image" src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" />
            </div>
            <div class="flicking-panel full has-background-primary">
                <img class="panel-image" src="https://images.unsplash.com/photo-1548801133-da353ebb1d97?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" />
            </div>
            <div class="flicking-panel full has-background-primary">
                <img class="panel-image" src="https://images.unsplash.com/photo-1582588678413-dbf45f4823e9?q=80&w=1965&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" />
            </div>
            <div class="flicking-panel full has-background-primary">
                <img class="panel-image" src="https://images.unsplash.com/photo-1491553895911-0055eca6402d?q=80&w=1780&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" />
            </div>
        </flicking>
        <flicking ref="flicking1" :options="{ bound: true, bounce: 10, moveType: 'freeScroll' }" class="thumb_group" id="thumb_group">
            <div class="flicking-panel thumb has-background-primary">
                <img class="thumb-image" src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" />
            </div>
            <div class="flicking-panel thumb has-background-primary">
                <img class="thumb-image" src="https://images.unsplash.com/photo-1548801133-da353ebb1d97?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" />
            </div>
            <div class="flicking-panel thumb has-background-primary">
                <img class="thumb-image" src="https://images.unsplash.com/photo-1582588678413-dbf45f4823e9?q=80&w=1965&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" />
            </div>
            <div class="flicking-panel thumb has-background-primary">
                <img class="thumb-image" src="https://images.unsplash.com/photo-1491553895911-0055eca6402d?q=80&w=1780&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" />
            </div>
        </flicking>
    </div>
</template>

<!-- <script setup>
    import { ref, onMounted } from 'vue'
    import Flicking from "@egjs/vue3-flicking";
    import { AutoPlay, Pagination, Sync } from '@egjs/flicking-plugins'
    import "@egjs/flicking-plugins/dist/flicking-plugins.css";
    import "@egjs/flicking-plugins/dist/pagination.css";

    const flicking0 = ref(null);
    const flicking1 = ref(null);
    const plugins = ref([]);

    onMounted(() => {
        plugins.value = [
            new Sync({
                type: "index",
                synchronizedFlickingOptions: [
                    {
                        flicking: flicking0.value,
                        isSlidable: true,
                        activeClass: "active"
                    },
                    {
                        flicking: flicking1.value,
                        isClickable: true,
                        activeClass: "active",
                    },
                ],
            }),
        ];
    });

    const currentList= defineProps({
        carouselList: Object
    })

    const currentIndex = (index) => {
        console.log(index)
    }
</script> -->

<script>
    import { Sync } from "@egjs/flicking-plugins";

    export default {
        data() {
            return {
                plugins: []
            }
        },
        mounted() {
            this.plugins = [new Sync({
            type: "index",
            synchronizedFlickingOptions: [
                {
                    flicking: this.$refs.flicking0,
                    isSlidable: true,
                    activeClass: "active"
                },
                {
                    flicking: this.$refs.flicking1,
                    isClickable: true,
                    activeClass: "active"
                }
            ]
            })];
        }
    }
</script>

<style>

    .carousel2 {
        position: relative;
    }

    .panel-image {
        width: 100%;
        max-height: 600px;
    }

    .thumb_group {
        margin: 25px 0px;
    }

    .thumb {
        margin: 0px 10px;
        opacity: 0.5;
        -webkit-transition: all 300ms ease-in-out;
        -moz-transition: all 300ms ease-in-out;
        -ms-transition: all 300ms ease-in-out;
        -o-transition: all 300ms ease-in-out;
        transition: all 300ms ease-in-out;
        height: fit-content;
        border-radius: 15px;
        overflow: hidden;
    }

    .thumb:hover {
        cursor: pointer;
    }

    .full_group {
        width: 100%;
        margin: 30px 0px;
    }
    
    .full {
        margin: 0px 10px;
        opacity: 0.4;
        -webkit-transition: all 300ms ease-in-out;
        -moz-transition: all 300ms ease-in-out;
        -ms-transition: all 300ms ease-in-out;
        -o-transition: all 300ms ease-in-out;
        transition: all 300ms ease-in-out;
    }

    .thumb-image {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 120px;
    }

    .thumb.active {
        border: 4px solid red;
        opacity: 1;
    }

    .full.active {
        opacity: 1;
    }

</style>