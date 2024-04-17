<template>
    <div class="user_info_data">
        <div class="user_current_info">
            <div class="user_image" v-if="user_data.profile.image_url" :style="{ background: `url(${user_data.profile.image_url})` }"></div>
            <div class="user_image" v-else style="background: url('https://plus.unsplash.com/premium_photo-1711136314731-8c3fe1831672?q=80&w=1887&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');"></div>
            <div class="user_details">
                <h1>{{ user_data.username }}</h1>
            </div>
        </div>
        <div class="user_input_field">
            <div class="user_input">
                <label for="first_name">First Name</label>
                <input type="text" v-model="user_current_info.first_name" :readonly="!setting.edit_mode">
            </div>
            <div class="user_input">
                <label for="last_name">Last Name</label>
                <input type="text" v-model="user_current_info.last_name" :readonly="!setting.edit_mode">
            </div>
            <div class="user_input">
                <label for="gender">Gender</label>
                <input type="text" :value="filterOptions(user_current_info.gender, 'gender')" readonly v-if="!setting.edit_mode">
                <select name="gender" id="gender" v-model="user_current_info.gender" v-if="setting.edit_mode">
                    <option v-for="(gender, index) in gender_options" 
                            :key="index" :disabled="gender.value == ''" 
                            :value="gender.value" 
                            :selected="user_current_info.gender == gender.value"
                    >
                        {{ gender.label }}
                    </option>
                </select>
            </div>
            <div class="user_input">
                <label for="country">Country</label>
                <input type="text" :value="filterOptions(user_current_info.country, 'country')" readonly v-if="!setting.edit_mode">
                <select name="country" id="country" v-model="user_current_info.country" v-if="setting.edit_mode">
                    <option v-for="(country, index) in country_options" 
                            :key="index" :disabled="country.value == ''" 
                            :value="country.value" 
                            :selected="user_current_info.country == country.value"
                    >
                        {{ country.label }}
                    </option>
                </select>
            </div>
            <div class="user_input">
                <label for="state">State</label>
                <input type="text" :value="filterOptions(user_current_info.state, 'state')" readonly v-if="!setting.edit_mode">
                <select name="state" id="state" v-model="user_current_info.state" v-if="setting.edit_mode">
                    <option v-for="(state, index) in state_options" 
                            :key="index" :disabled="state.value == ''" 
                            :value="state.value" 
                            :selected="user_current_info.state == state.value"
                    >
                        {{ state.label }}
                    </option>
                </select>
            </div>
            <div class="user_input">
                <label for="city">City</label>
                <input type="text" :value="filterOptions(user_current_info.city, 'city')" readonly v-if="!setting.edit_mode">
                <select name="city" id="city" v-model="user_current_info.city" v-if="setting.edit_mode">
                    <option v-for="(city, index) in state_options" 
                            :key="index" :disabled="city.city_value == ''" 
                            :value="city.city_value" 
                            :selected="user_current_info.city == city.city_value"
                    >
                        {{ city.city_label }}
                    </option>
                </select>
            </div>
            <div class="user_input">
                <label for="dob">Date of Birth</label>
                <input type="date" v-model="user_current_info.dob" :readonly="!setting.edit_mode">
            </div>
        </div>
        <button class="edit_button" @click="editUserInfo()">{{ setting.edit_wording }}</button>
        <button class="save_button" v-if="setting.edit_mode" @click="updateUserInfo()" :disabled="process">Save</button>
    </div>
</template>

<script setup>
    import { reactive, onMounted, computed, watch } from 'vue'
    import { storeToRefs } from 'pinia';
    import { toast } from 'vue3-toastify';
    import { useAuthStore } from '../store/auth';

    const authStore = useAuthStore()

    const { process } = storeToRefs(authStore);
    const user_data_detail = computed(()=> authStore.user_data)
    const current_response_detail = computed(()=> authStore.successResponse)
    let user_data = user_data_detail.value
    const current_response = current_response_detail.value

    const gender_options = { default: { value: '', label: 'Please select gender' }, male: { value: 1, label: 'Male' }, female: { value: 2, label: 'Female' } }
    const country_options = { default: { value: '', label: 'Please select country' }, my: { value: 'MY', label: 'Malaysia' } }
    const state_options = { 
                            default: { value: '', label: 'Please select state', city_value: '', city_label: 'Please select city' }, 
                            johor: { value: 'johor', label: 'Johor', city_value: 'johor_bahru', city_label: 'Johor Bahru' },
                            kedah: { value: 'kedah', label: 'Kedah', city_value: 'alor_setar', city_label: 'Alor Setar' },
                            kelantan: { value: 'kelantan', label: 'Kelantan', city_value: 'kota_bahru', city_label: 'Kota Bahru' },
                            malacca: { value: 'malacca', label: 'Malacca (Melaka)', city_value: 'malacca_city', city_label: 'Malacca City' },
                            negeri_sembilan: { value: 'negeri_sembilan', label: 'Negeri Sembilan', city_value: 'seremban', city_label: 'Seremban' },
                            pahang: { value: 'pahang', label: 'Pahang', city_value: 'kuantan', city_label: 'Kuantan' },
                            penang: { value: 'penang', label: 'Penang', city_value: 'george_town', city_label: 'George Town' },
                            perak: { value: 'perak', label: 'Perak', city_value: 'ipoh', city_label: 'Ipoh' },
                            perlis: { value: 'perlis', label: 'Perlis', city_value: 'kangar', city_label: 'Kangar' },
                            sabah: { value: 'sabah', label: 'Sabah', city_value: 'kota_kinabalu', city_label: 'Kota Kinabalu' },
                            sarawak: { value: 'sarawak', label: 'Sarawak', city_value: 'kuching', city_label: 'Kuching' },
                            selangor: { value: 'selangor', label: 'Selangor', city_value: 'shah_alam', city_label: 'Shah Alam' },
                            terengganu: { value: 'terengganu', label: 'Terengganu', city_value: 'kuala_terengganu', city_label: 'Kuala Terengganu' },
                        }

    const setting = reactive({
        current_tab: 'user_info',
        edit_mode: false,
        edit_wording: 'Edit'
    })

    const user_current_info = reactive({
        first_name: user_data.profile.first_name,
        last_name: user_data.profile.last_name,
        gender: user_data.profile.gender ? user_data.profile.gender : "",
        country: user_data.profile.country ? user_data.profile.country : "",
        state: user_data.profile.state ? user_data.profile.state : "",
        city: user_data.profile.city ? user_data.profile.city : "",
        dob: user_data.profile.dob,
    })
    
    const editUserInfo = () => {
        setting.edit_mode = !setting.edit_mode
        if(setting.edit_mode){
            setting.edit_wording = "Cancel"
        }
        else {
            setting.edit_wording = "Edit"
        }
    }

    // Replace old data
    watch(user_data_detail, (newData, oldData)=> {
        user_data = newData
    })

    const updateUserInfo = () => {

        const current_info = Object.entries(user_current_info).reduce((a,[k,v]) => (v == '' || v == null || user_data.profile[k] == v ? a : (a[k]=v, a)), {});
        authStore.successResponse = false

        if(Object.values(current_info).length > 0){
            authStore.updateProfile(current_info)
            if(current_response){
                setting.edit_mode = false;
                setting.edit_wording = "Edit"
            }
        }
        else {
            toast.warning("Nothing to update")
        }

    }

    const filterOptions = (value, type) => {
        let current_type = ""
        switch (type) {
            case 'gender':
                current_type = gender_options
                break;

            case 'country':
                current_type = country_options
                break;
            
            case 'state':
            case 'city':
                current_type = state_options
                break;
        }

        const answer = Object.values(current_type).find((option)=> { 
            if(type == "city"){
                return option.city_value == value
            }
            else {
                return option.value == value
            }
        })
        
        return type == 'city' ? answer.city_label : answer.label;
    }
</script>

<style scoped>
    .user_info_data {
        width: 100%;
        height: 100%;
    }

    .user_current_info {
        display: flex;
        /* justify-content: center; */
        align-items: center;
        margin-bottom: 40px;
    }

    .user_details {
        margin-left: 20px;
    }

    .user_input_field {
        display: grid;
        grid-template-columns: auto auto auto;
        column-gap: 50px;
        row-gap: 15px;
    }

    .user_input {
        display: flex;
        flex-direction: column;
    }

    .user_input label {
        font-size: 20px;
        font-weight: 700;
    }

    .user_input input,
    .user_input select {
        padding: 10px;
        margin: 10px 0px;
        font-size: 16px;
    }

    .user_info_data .user_image {
        width: 150px;
        height: 150px;
        overflow: hidden;
        background-size: cover !important;
        border-radius: 50%;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
    }

    .edit_button,
    .save_button {
        width: 250px;
        height: 50px;
        margin-top: 40px;
        font-size: 20px;
        border: none;
        color: white;
        border-radius: 45px;
        transition: all 0.2s ease-in-out;
    }

    .edit_button {
        background: #e80202;
    }

    .save_button {
        background: rgb(13, 250, 13);
        margin-left: 20px;
    }

    .edit_button:hover {
        cursor: pointer;
        background: #910101;
    }

    .save_button:hover {
        cursor: pointer;
        background: rgb(0, 197, 0);
    }

    .save_button:disabled {
        background: #444444 !important;
    }
</style>