<template>
    <div class="top_selling">
        <h1>Top Selling of The month</h1>
        <div class="top_selling_items">
            <div class="item" v-for="index in 10" :key="index">
                <span>{{ index }}</span>
            </div>
        </div>
    </div>
</template>

<script setup>

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
        background: url('https://images.goodsmile.info/cgm/images/product/20211001/11850/90545/large/b08b8454d89809b63a4379fb7ded804c.jpg');
        grid-column-start: 1;
        grid-column-end: 2;
        grid-row-start: 1;
        grid-row-end: 5;
    }

    .top_selling .top_selling_items .item:nth-child(2) {
        display: grid;
        background: url('https://media.karousell.com/media/photos/products/2023/8/17/kamen_rider_black_sun__hot_toy_1692297493_c39f1fca_progressive');
        grid-column-start: 2;
        grid-column-end: 3;
        grid-row-start: 1;
        grid-row-end: 4;
    }

    .top_selling .top_selling_items .item:nth-child(3) {
        background: url('https://i0.wp.com/artcity.my/wp-content/uploads/2023/08/394890551_329223199694936_1185021093920939537_n.jpg?fit=1080%2C1080&ssl=1');
        grid-column-start: 3;
        grid-column-end: 5;
        grid-row-start: 1;
        grid-row-end: 3;
        background-position-y: 35% !important;
    }

    .top_selling .top_selling_items .item:nth-child(4) {
        background: url('https://cdn1.npcdn.net/images/29f4006eee0a995475ab60bb4d281d85_1707981187.webp?md5id=e521eb05eb8a2e794ac25a62e663e370&new_width=1000&new_height=1000&size=max&w=1608536500&from=jpeg&type=1');
        background-position-y: 35% !important;
    }

    .top_selling .top_selling_items .item:nth-child(5) {
        background: url('https://sm.ign.com/t/ign_me/news/h/hot-toys-r/hot-toys-reveals-21-inches-of-venom-with-new-spider-man-2-fi_gk11.1200.jpg');
        background-position-y: 45% !important;
    }

    .top_selling .top_selling_items .item:nth-child(6) {
        background: url('https://tg-prod-public.oss-ap-southeast-1.aliyuncs.com/tg/media/type/IMAGE/2cb5c4e978d7423ca41690450dcb6196');
    }

    .top_selling .top_selling_items .item:nth-child(7) {
        background: url('https://www.goodsmile.com/gsc-webrevo-sdk-storage-prd/product/image/product/20230602/14454/116145/large/1fd573724aaf289898247d906fcbd25a.jpg');
        background-position-y: 45% !important;
    }

    .top_selling .top_selling_items .item:nth-child(8) {
        background: url('https://i.ebayimg.com/images/g/iHAAAOSwiyBldOEH/s-l1200.jpg');
        background-position-y: 25% !important;
    }

    .top_selling .top_selling_items .item:nth-child(9) {
        background: url('https://i0.wp.com/artcity.my/wp-content/uploads/2023/09/378796818_685660653597059_1317753972162750875_n.jpg?fit=1080%2C1080&ssl=1');
        grid-column-start: 1;
        grid-column-end: 3;
        background-position-y: 30% !important;
    }

    .top_selling .top_selling_items .item:nth-child(10) {
        background: url('https://shibuya-scramble-figure.com/lp/scale/ten-sura/hagun/assets/img/rimuru/modal/03_img_0.jpg');
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