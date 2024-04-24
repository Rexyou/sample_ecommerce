<template>
    <div class="address_container">
        <div v-if="user_data.profile?.addresses" class="address_data">
            <div class="address_card" v-for="(address, index) in user_data.profile.addresses" :key="index" 
                :class="{ 'main_card': address.main_tag, 'sub_card': !address.main_tag }">
                <span class="label">{{ address['label'] }}</span>
                <div class="personal_details">
                    <span class="receiver_name">Receiver Name : {{ address.receiver_name }}</span>
                    <span class="phone_number">Phone Number : {{ address.phone }}</span>
                </div>
                <span class="line_1">{{ address.line_1 }}</span>
                <span class="line_2">{{ address.line_2 }}</span>
                <span class="line_3">{{ address.line_3 }}</span>
                <div class="tools">
                    <v-icon name="md-modeeditoutline" @click="changeModalShow('edit', index)" />
                    <v-icon name="md-deleteforever" @click="deleteAddress(index)" />
                </div>
            </div>
        </div>
        <div class="address_card plus_action" v-if="user_data.profile?.addresses == null || user_data.profile.addresses.length < 3">
            <h1>Add Address</h1>
            <v-icon name="hi-plus-circle" @click="changeModalShow('create')" />
        </div>
    </div>
    <ModifyAddress :openModal="openModal" :mode="currentMode" :currentIndex="currentIndex" @showModal="changeModalShow"  />
</template>

<script setup>

    import { watch, ref, reactive, computed, nextTick } from 'vue'
    import { useAuthStore } from '../store/auth';

    import { useVuelidate } from '@vuelidate/core';
    import { helpers, minLength, maxLength, sameAs, required } from "@vuelidate/validators";
    import { storeToRefs } from 'pinia';
    import { toast } from 'vue3-toastify';

    import ModifyAddress from './ModifyAddress.vue'

    const authStore = useAuthStore()
    const { validation_errors, process, user_data } = storeToRefs(authStore)
    const user_data_details = computed(()=> authStore.user_data);
    let current_user_data = user_data_details.value
    let current_addresses_list = current_user_data.profile.addresses

    watch(user_data_details, (newData)=> {
        current_user_data = newData
        if(current_user_data.length !== 0){
            current_addresses_list = current_user_data.profile.addresses
        }
    })

    let openModal = ref(false)
    let currentMode = ref("create")
    let currentIndex = ref("")

    const changeModalShow = (mode, index) => {
        currentMode.value = mode
        currentIndex.value = ""
        if((index === 0 || (index !== 0 && index != "")) && Number.isInteger(index)){
            currentIndex.value = index
        }
        openModal.value = !openModal.value
    }

    const deleteAddress = (index) => {
        console.log("list: ", current_addresses_list)
        console.log("selected: ", current_addresses_list[index])
        if(confirm("Are your sure delete this address `"+current_addresses_list[index]['label']+'`?')){
            if(index > -1){
                current_addresses_list.splice(index, 1)
                console.log("after delete list: ", current_addresses_list)
                authStore.updateProfile({ addresses: current_addresses_list })
            }
        }
    }

</script>

<style scoped>
    .address_data {
        width: 100%;
        height: auto;
        display: grid;
        grid-template-columns: auto auto;
        column-gap: 20px;
        row-gap: 20px;
        margin-bottom: 20px;
    }

    .address_card {
        padding: 20px;
        border-radius: 25px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;
        position: relative;
        height: 220px;
        max-width: 440px;
        min-width: 400px;
    }

    .main_card {
        background: linear-gradient(to right bottom, #e80202, #ff0e0a, #fa2824, #fd3e3b, #fb4a48, #fc5b58, #fd6a68, #fc7977, #fe908e, #fea6a4, #fcbbba, #f9d0d0);
    }

    .sub_card {
        background: linear-gradient(to right bottom, #02a3e8, #0ea2e2, #29ace3, #47afdb, #4caad3, #54afd6, #73bcdb, #7ebcd6, #80b8d0, #9fbecb, #aac4d0, #c7d1d5);
    }

    .label {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .receiver_name,
    .phone_number {
        font-size: 18px;
        margin-bottom: 5px;
        font-weight: 600;
    }

    .personal_details {
        display: flex;
        flex-direction: column;
        margin-bottom: 10px;
    }

    .tools {
        position: absolute;
        right: 20px;
        bottom: 20px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .tools svg {
        width: 30px;
        height: 30px;
        margin-bottom: 5px;
    }

    .plus_action {
        background: white !important;
        box-shadow: none;
        border: 1px solid rgba(0, 0, 0, 0.3);
        align-items: center;
    }

    .plus_action h1 {
        font-size: 25px;
        margin-bottom: 10px;
    }

    .plus_action svg {
        width: 50px;
        height: 50px;
    }
</style>