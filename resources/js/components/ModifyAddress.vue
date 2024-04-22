<template>
    <div class="modal" v-show="openModal" :class="{ 'active': openModal }">
        <div class="content">
            <h1 v-if="current_mode == 'create'">Create Address</h1>
            <h1 v-if="current_mode == 'edit'">Edit Address</h1>
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
                <button @click.prevent="addAddress" class="create_button" :disabled="process" v-if="current_mode == 'create'">Create</button>
                <button @click.prevent="editAddress(currentIndex)" class="create_button" :disabled="process" v-if="current_mode == 'edit'">Edit</button>
            </div>
        </div>
    </div>
</template>

<script setup>

    import { watch, ref, reactive, computed, nextTick } from 'vue'
    import { useAuthStore } from '../store/auth';

    import { useVuelidate } from '@vuelidate/core';
    import { helpers, minLength, maxLength, sameAs, required } from "@vuelidate/validators";
    import { storeToRefs } from 'pinia';
    import { toast } from 'vue3-toastify';

    const authStore = useAuthStore()
    const { validation_errors, process } = storeToRefs(authStore)

    const props = defineProps({ 
                    openModal: Boolean, 
                    mode: String, 
                    user_data: Object, 
                    currentIndex: Object, 
                    current_response: Boolean 
                })
    let openModal = props.openModal
    let user_data = props.user_data
    let current_mode = props.mode
    let currentIndex = props.currentIndex
    let current_response = props.current_response

    watch(props, (newProps, oldProps)=> {
        openModal = newProps.openModal
        user_data = newProps.user_data
        current_mode = newProps.mode
        currentIndex = newProps.currentIndex
        current_response = newProps.current_response

        if(currentIndex !== "" && Number.isInteger(currentIndex)){
            console.log("gg")
            form.label = current_addresses_list[currentIndex].label
            form.receiver_name = current_addresses_list[currentIndex].receiver_name
            form.phone = current_addresses_list[currentIndex].phone
            form.line_1 = current_addresses_list[currentIndex].line_1
            form.line_2 = current_addresses_list[currentIndex].line_2
            form.line_3 = current_addresses_list[currentIndex].line_3 
            form.main_tag = current_addresses_list[currentIndex].main_tag
        }

        if(current_mode == "create"){
            clearForm()
        }
    })

    const form = reactive({
        label: "",
        receiver_name: "",
        phone: "",
        line_1: "",
        line_2: "",
        line_3: "",
        main_tag: false,
    })

    const clearForm = async() => {
        form.label = ""
        form.receiver_name = ""
        form.phone = ""
        form.line_1 = ""
        form.line_2 = ""
        form.line_3 = ""
        form.main_tag = false
        await nextTick()
        v$.value.$reset()
    }

    const emit = defineEmits(['closeModal'])

    const modalControl = () => {
        emit('showModal', current_mode)
        clearForm()
    }

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

            if(current_addresses_list.length == 3){
                openModal = false
                return toast.error("Addresses reached limit.")
            }

            console.log(current_addresses_list)

            const checker = current_addresses_list.find((value, key)=> {
                if(value.label === form.label || value.label === form.label.toLowerCase()){
                    console.log("current value: ", value.label)
                    console.log("current form value: ", form.label)
                    return true
                }
            })

            if(checker){
                return toast.error("Label exists")
            }

            current_addresses_list.push(form)
            authStore.updateProfile({ addresses: current_addresses_list })

            if(current_response){
                emit('showModal', current_mode)
                clearForm()
            }
        }
    }

    const editAddress = (index) => {
        current_addresses_list[index] = form
        authStore.updateProfile({ addresses: current_addresses_list })
        if(current_response){
            emit('showModal', current_mode)
            clearForm()
        }
    }

</script>

<style scoped>
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