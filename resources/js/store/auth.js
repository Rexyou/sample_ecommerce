import { defineStore } from "pinia";
import axiosInstance from '../settings/axios'

export const useAuthStore = defineStore('auth', {
    state: ()=> ({
        user_data: [],
        token: null,
    }),
    persist: {
        storage: sessionStorage,
    },
    actions: {
        async login(data){
            try {
                await axiosInstance.post(`/user/login`, data)
                .then(async (response)=> {
                    this.user_data = response.data.data
                    this.token = response.data.data.token
                    await this.router.push({ name: 'profile' }); 
                })
                .catch((error)=> {
                    console.log('axios error:')
                    console.log(error)
                })

            } catch (error) {
                console.log("try catch:")
                console.log(error)
            }
        }
    }
});