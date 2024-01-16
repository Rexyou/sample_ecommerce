!<template>
    <div class="navigation_bar">
        <div class="logo">
            <img src="https://scontent.fkul15-1.fna.fbcdn.net/v/t39.30808-6/312912063_487774336709363_1010814458414550901_n.jpg?_nc_cat=108&ccb=1-7&_nc_sid=efb6e6&_nc_eui2=AeG3SsNyvkR9EW4DWk4sOKd63tLcvq6Xkxfe0ty-rpeTFw73fkyjWy5EKy-5SnwQbeLEo4DNWk6B8KwjAg7pIViL&_nc_ohc=kmccWQwdYFoAX-9Z4P_&_nc_ht=scontent.fkul15-1.fna&oh=00_AfCZcZeeJ510hc7WZwAl1xb4IeDCdafZqp1h_XqYkbTxrA&oe=65A5C632" alt="">
        </div>
        <div class="menu_bar">
            <router-link :to="{ name: 'home' }">Home</router-link>
            <router-link :to="{ name: 'product_list' }">Products</router-link>
            <router-link :to="{ name: 'brand_list' }">Brands</router-link>
            <router-link :to="{ name: 'about' }">About</router-link>
        </div>
        <div class="search_bar">
            <v-icon name="bi-search"/>
            <v-icon name="bi-people-fill"/>
            <v-icon name="bi-cart"/>
        </div>
    </div>
</template>

<script setup>

</script>

<style scoped>
    .navigation_bar {
        position: absolute;
        z-index: 20;
        width: 90%;
        margin: 10px auto;
        display: flex;
        justify-content:space-between;
        left:0;
        right:0;
    }

    .navigation_bar .logo {
        width: 10%;
        height: 100px;
    }

    .navigation_bar .logo img {
        width: auto;
        height: 100px;
        display: block;
        margin-left: auto;
    }

    .navigation_bar .menu_bar {
        width: 30%;
        display: flex;
        justify-content: space-around;
        align-items: center;
        height: 100px;
        margin-left: auto;
    }

    .navigation_bar .menu_bar a {
        color: white;
        text-decoration: none;
        font-size: 16px;
    }
    
    .navigation_bar .search_bar {
        min-width: 150px;
        display: flex;
        align-items: center;
        justify-content: space-around;
        color: white;
        margin-left: auto;
    }

    .navigation_bar .search_bar svg {
        width: 30px;
        height: 30px;
    }
</style>