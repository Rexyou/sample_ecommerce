<template>
    <div class="address_data">
        <div class="address_card" v-for="(address, index) in user_data.profile.addresses" :key="index" 
            :class="{ 'main_card': address.main_tag, 'sub_card': !address.main_tag }">
            <span class="label">{{ address.label }}</span>
            <div class="personal_details">
                <span class="receiver_name">Receiver Name : {{ address.receiver_name }}</span>
                <span class="phone_number">Phone Number : {{ address.phone }}</span>
            </div>
            <span class="line_1">{{ address.line_1 }}</span>
            <span class="line_2">{{ address.line_2 }}</span>
            <span class="line_3">{{ address.line_3 }}</span>
            <div class="tools">
                <v-icon name="md-modeeditoutline" />
                <v-icon name="md-deleteforever" @click="deleteAddress(index)" />
            </div>
        </div>
        <div class="address_card plus_action">
            <h1>Add Address</h1>
            <v-icon name="hi-plus-circle" @click="modalControl" />
        </div>
    </div>
    <div class="modal" v-show="openModal" :class="{ 'active': openModal }">
        <div class="content">
            <h1>Create Address</h1>
            <div class="user_input_field">
                <div class="user_input">
                    <label for="label">Label</label>
                    <input type="text" v-model="form.label">
                    <span v-for="error in v$.label.$errors" :key="error.$uid" class="error_message">
                        {{ error.$message }}
                    </span>
                    <span v-for="error in validation_errors?.label" :key="error.$uid" class="error_message">
                        {{ error }}
                    </span>
                </div>
                <div class="user_input">
                    <label for="receiver_name">Receiver Name</label>
                    <input type="text" v-model="form.receiver_name">
                    <span v-for="error in v$.receiver_name.$errors" :key="error.$uid" class="error_message">
                        {{ error.$message }}
                    </span>
                    <div v-if="validation_errors != null">
                        <span v-for="error in validation_errors.receiver_name" :key="error.$uid" class="error_message">
                            {{ error }}
                        </span>
                    </div>
                </div>
                <div class="user_input">
                    <label for="phone">Phone</label>
                    <input type="text" v-model="form.phone">
                    <span v-for="error in v$.phone.$errors" :key="error.$uid" class="error_message">
                        {{ error.$message }}
                    </span>
                    <span v-for="error in validation_errors.phone" :key="error.$uid" class="error_message">
                        {{ error }}
                    </span>
                </div>
                <div class="user_input">
                    <label for="line_1">Address Line 1</label>
                    <input type="text" v-model="form.line_1">
                    <span v-for="error in v$.line_1.$errors" :key="error.$uid" class="error_message">
                        {{ error.$message }}
                    </span>
                    <span v-for="error in validation_errors.line_1" :key="error.$uid" class="error_message">
                        {{ error }}
                    </span>
                </div>
                <div class="user_input">
                    <label for="line_2">Address Line 2</label>
                    <input type="text" v-model="form.line_2">
                    <span v-for="error in v$.line_2.$errors" :key="error.$uid" class="error_message">
                        {{ error.$message }}
                    </span>
                    <span v-for="error in validation_errors.line_2" :key="error.$uid" class="error_message">
                        {{ error }}
                    </span>
                </div>
                <div class="user_input">
                    <label for="line_3">Address Line 3</label>
                    <input type="text" v-model="form.line_3">
                    <span v-for="error in v$.line_3.$errors" :key="error.$uid" class="error_message">
                        {{ error.$message }}
                    </span>
                    <span v-for="error in validation_errors.line_3" :key="error.$uid" class="error_message">
                        {{ error }}
                    </span>
                </div>
                <div class="check_main_tag">
                    <input type="checkbox" class="main_tag" id="main_tag" v-model="form.main_tag">
                    <label for="main_tag">Main Address</label>
                </div>
            </div>
            <div class="form_controller">
                <button @click="modalControl" class="cancel_button">Cancel</button>
                <button @click.prevent="addAddress" class="create_button" :disabled="process">Create</button>
            </div>
        </div>
    </div>
</template>

<script setup>

    import { watch, ref, reactive, computed } from 'vue'
    import { useAuthStore } from '../store/auth';

    import { useVuelidate } from '@vuelidate/core';
    import { helpers, minLength, maxLength, sameAs, required } from "@vuelidate/validators";
    import { storeToRefs } from 'pinia';
    import { toast } from 'vue3-toastify';

    const authStore = useAuthStore()
    const { validation_errors, process } = storeToRefs(authStore)
    const current_response_detail = computed(()=> authStore.successResponse)
    let current_response = current_response_detail.value

    let openModal = ref(false)

    const form = reactive({
        label: "",
        receiver_name: "",
        phone: "",
        line_1: "",
        line_2: "",
        line_3: "",
        main_tag: false,
    })

    const modalControl = () => {
        openModal.value = !openModal.value
        form.label = ""
        form.receiver_name = ""
        form.phone = ""
        form.line_1 = ""
        form.line_2 = ""
        form.line_3 = ""
    }

    watch(openModal, (newData, oldData)=> {
        console.log("open modal new: ", newData)
        console.log("open modal old: ", oldData)
        // openModal = newData
    })

    const props = defineProps({ user_data: Object })
    let user_data = props.user_data

    watch(props, (newProps, oldProps)=> {
        user_data = newProps.user_data
    })

    watch(current_response_detail, (newResponse, oldResponse)=> {
        current_response = newResponse
    })

    console.log(user_data.profile.addresses)
    let current_addresses_list = user_data.profile.addresses
    let current_addresses = []

    if(current_addresses_list == null || current_addresses_list.length == 0){
        form.main_tag = true
        current_addresses_list = []
    }

    const label_format = helpers.regex(/^[a-zA-Z]+$/)
    const receiver_name_format = helpers.regex(/^[\w\s.]+$/)
    const phone_format = helpers.regex(/\+[0-9]{10,14}$/)

    const rules = computed(()=> {
        return {
            label: { 
                        required,
                        label_format: helpers.withMessage("Label format invalid!", label_format),
                        minLength: minLength(1), maxLength: maxLength(100)
                    },
            receiver_name: { 
                required,
                label_format: helpers.withMessage("Receiver name format invalid!", receiver_name_format),
                minLength: minLength(1), maxLength: maxLength(255)
            },
            phone: { 
                        required,
                        phone_format: helpers.withMessage("Phone format invalid!", phone_format), 
                        minLength: minLength(10), maxLength: maxLength(14)
                    },
            line_1: { required, minLength: minLength(1), maxLength: maxLength(155) },
            line_2: { maxLength: maxLength(155) },
            line_3: { maxLength: maxLength(155) },
            main_tag: {},
        } 
    })

    const v$ = useVuelidate(rules, form);

    const addAddress = async() => {
        const result = await v$.value.$validate();
        if(result){

            console.log(current_addresses_list)
            console.log(form)

            if(current_addresses_list.length == 3){
                openModal = false
                return toast.error("Addresses reached limit.")
            }

            const checker = current_addresses_list.find((value, key)=> {
                if(value.label === form.label || value.label === form.label.toLowerCase()){
                    console.log("label: ", value.label)
                    console.log("form: ", form.label)
                    return true
                }
            })

            if(checker){
                return toast.error("Label exists")
            }

            current_addresses_list.push(form)
            console.log(current_addresses_list)
            authStore.updateProfile({ addresses: current_addresses_list })

            if(current_response){
                openModal.value = false
            }
        }
    }

    const deleteAddress = (index) => {
        if(confirm("Are your sure delete this address `"+current_addresses_list[index]['label']+'`?')){
            if(index > -1){
                current_addresses_list.splice(index, 1)
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

    .modal {
        position: fixed;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        top: 0;
        left: 0;
        z-index: 1000;
        transition: .6s all ease-in-out;
        opacity: 0;
        display: none;
    }

    .modal.active {
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 1;
    }

    .modal .content {
        width: 60%;
        background: white;
        padding: 30px 50px;
        margin: 50px 0px;
        height: max-content;
    }

    .content h1 {
        font-size: 36px;
        margin-bottom: 15px;
    }

    .user_input_field {
        display: grid;
        grid-template-columns: auto auto;
        row-gap: 30px;
        column-gap: 30px;
        margin: 30px auto;
    }

    .user_input {
        display: flex;
        flex-direction: column;
    }

    .user_input label {
        font-size: 20px;
        font-weight: 700;
    }

    .user_input input {
        padding: 10px;
        margin: 10px 0px;
        font-size: 16px;
        width: 100%;
    }

    .user_input .error_message {
        color: red;
        font-size: 16px;
        margin: 10px 0px 0px;
    }

    .main_tag {
        font-size: 20px;
        margin-right: 10px;
    }

    .form_controller {
        width: 100%;
    }

    .cancel_button,
    .create_button {
        width: 150px;
        height: 50px;
        font-size: 20px;
        border: none;
        color: white;
        border-radius: 45px;
        transition: all 0.2s ease-in-out;
    }

    .cancel_button {
        background: #e80202;
    }

    .create_button {
        background: rgb(13, 250, 13);
        margin-left: 20px;
    }

    .cancel_button:hover {
        cursor: pointer;
        background: #910101;
    }

    .create_button:hover {
        cursor: pointer;
        background: rgb(0, 197, 0);
    }

    .create_button:disabled {
        background: #444444 !important;
    }
</style>