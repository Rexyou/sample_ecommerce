import { defineStore } from "pinia";
import axiosInstance from '../settings/axios'
import { toast } from 'vue3-toastify';
import { useAuthStore } from "./auth";

export const useCartStore = defineStore('cart', {
    state: ()=> ({
        cart_list: [],
        process: false,
        successResponse: false
    }),
    actions: {
        async addToCart(data){
            this.process = true
            this.successResponse = false
            const authStore = useAuthStore()
            try {                
                await axiosInstance.post(`/cart/add`, data, {
                    headers: {
                      'Authorization': `Bearer ${authStore.token}`
                    }
                })
                .then(async (response)=> {
                    this.process = false
                    this.successResponse = true
                    console.log("add to cart: ")
                    console.log(response.data)
                    toast.success("Add to cart success");
                    // this.cart_list = response.data.data
                })
                .catch(async (error)=> {
                    this.process = false
                    this.successResponse = false
                    console.log('axios error:')
                    console.log(error)
                    if(error.response.data.code == 401){
                        console.log("force logout")
                        await authStore.logout()
                    }
                    else {
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
                    else if(error.response.data.code == 422){
                        this.validation_errors = error.response.data.message
                        toast.error("Validation errors")
                    }
                })

            } catch (error) {
                this.process = false
                this.successResponse = false
                console.log("try catch:")
                console.log(error)
            }
        },
    }
});