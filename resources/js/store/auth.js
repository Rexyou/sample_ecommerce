import { defineStore } from "pinia";
import axiosInstance from '../settings/axios'
import { toast } from 'vue3-toastify';

export const useAuthStore = defineStore('auth', {
    state: ()=> ({
        user_data: [],
        token: null,
        process: false,
        successResponse: false,
    }),
    persist: {
        storage: sessionStorage,
    },
    actions: {
        async login(data){
            this.process = true
            try {
                await axiosInstance.post(`/user/login`, data)
                .then(async (response)=> {
                    this.process = false
                    this.user_data = response.data.data
                    this.token = response.data.data.token
                    await this.router.push({ name: 'profile' }); 
                    toast.success("Login success");
                })
                .catch((error)=> {
                    console.log('axios error:')
                    console.log(error)
                    this.process = false
                    toast.error(error.response.data.message);
                })

            } catch (error) {
                console.log("try catch:")
                console.log(error)
                this.process = false
            }
        },
        async register(data){
            try {
                await axiosInstance.post(`/user/register`, data)
                .then(async (response)=> {
                    console.log(response)
                })
                .catch((error)=> {
                    console.log('axios error:')
                    console.log(error)
                })

            } catch (error) {
                console.log("try catch:")
                console.log(error)
            }
        },
        async updateProfile(data){
            this.process = true
            try {

                console.log("token :", this.token)
                
                await axiosInstance.post(`/user/update_profile`, data, {
                    headers: {
                      'Authorization': `Bearer ${this.token}`
                    }
                })
                .then(async (response)=> {
                    this.process = false
                    console.log("update profile: ")
                    console.log(response.data.data)
                    this.user_data = response.data.data
                    toast.success("Update profile success");
                    this.successResponse = true
                })
                .catch(async (error)=> {
                    this.process = false
                    console.log('axios error:')
                    console.log(error)
                    if(error.response.data.code == 401){
                        this.user_data = []
                        this.token = null
                        await this.router.push({ name: 'login' }); 
                        toast.error(error.response.data.message);
                    }
                })

            } catch (error) {
                this.process = false
                console.log("try catch:")
                console.log(error)
            }
        },
        async getProfile(){

            try {
                
                axiosInstance.get('user/profile', {
                    headers: {
                        'Authorization': `Bearer ${this.token}`
                    }
                })
                .then((response)=> {
                    this.user_data = response.data.data
                })
                .catch(async (error)=> {
                    console.log('axios error:')
                    console.log(error)
                    if(error.response.data.code == 401){
                        this.user_data = []
                        this.token = null
                        await this.router.push({ name: 'login' }); 
                        toast.error(error.response.data.message);
                    }
                })

            } catch (error) {
                console.log("try catch:")
                console.log(error)
            }

        }
    }
});