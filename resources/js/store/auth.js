import { defineStore } from "pinia";
import axiosInstance from '../settings/axios'
import { toast } from 'vue3-toastify';

export const useAuthStore = defineStore('auth', {
    state: ()=> ({
        user_data: [],
        validation_errors: [],
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
            this.validation_errors = []
            this.process = true
            try {
                await axiosInstance.post(`/user/register`, data)
                .then(async (response)=> {
                    this.process = false
                    console.log(response)
                    await this.router.push({ name: 'login' }); 
                    toast.success("Register success. Please proceed to login.");
                })
                .catch((error)=> {
                    this.process = false
                    console.log('axios error:')
                    console.log(error)
                    if(error.response.data.code == 422){
                        this.validation_errors = error.response.data.message
                    }
                })

            } catch (error) {
                this.process = false
                console.log("try catch:")
                console.log(error)
            }
        },
        async updateProfile(data){
            this.process = true
            try {                
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
                    this.successResponse = false
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
                this.successResponse = false
                console.log("try catch:")
                console.log(error)
            }
        },
        async updateUser(data){
            this.process = true
            try {                
                await axiosInstance.post(`/user/update_user`, data, {
                    headers: {
                      'Authorization': `Bearer ${this.token}`
                    }
                })
                .then(async (response)=> {
                    this.process = false
                    console.log("update user: ")
                    console.log(response.data)
                    toast.success("Update setting success");
                    this.user_data = response.data.data
                    this.successResponse = true
                })
                .catch(async (error)=> {
                    this.process = false
                    this.successResponse = false
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
                this.successResponse = false
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

        },
        async logout(){
            this.process = true
            console.log(`current token ${this.token}`)
            try {                                
                await axiosInstance.post(`/user/logout`, {}, {
                    headers: {
                      'Authorization': `Bearer ${this.token}`
                    }
                })
                .then(async (response)=> {
                    this.process = false
                    console.log("logout: ")
                    console.log(response.data)
                    this.user_data = []
                    this.token = null
                    await this.router.push({ name: 'login' }); 
                    toast.success("Logout success");
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
                console.log("try catch:")
                console.log(error)
            }
        }
    }
});