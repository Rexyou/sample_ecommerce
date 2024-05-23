import { defineStore } from "pinia";
import axiosInstance from '../settings/axios'
import { toast } from 'vue3-toastify';
import { useAuthStore } from "./auth";

export const useCartStore = defineStore('cart', {
    state: ()=> ({
        cart_list: [],
        cart_list_pagination: [],
        process: false,
        successResponse: false,
        validation_errors: [],
        current_page: 1,
        selected_cart_item: [],
        selected_cart_details: [],
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
        async cartList(page){
            this.process = true
            const authStore = useAuthStore()
            try {                
                await axiosInstance.get(`/cart/list?page=${page}`, {
                    headers: {
                      'Authorization': `Bearer ${authStore.token}`
                    }
                })
                .then(async (response)=> {
                    this.cart_list.push(...response.data.data.data)
                    this.cart_list_pagination = response.data.data
                    this.current_page = page
                    this.process = false
                })
                .catch(async (error)=> {
                    console.log('axios error:')
                    console.log(error)
                    this.process = false
                    if(error.response.data.code == 401){
                        authStore.user_data = []
                        authStore.token = null
                        await this.router.push({ name: 'login' }); 
                        toast.error(error.response.data.message);
                    }
                    else if(error.response.data.code == 422){
                        this.validation_errors = error.response.data.message
                        toast.error("Validation errors")
                    }
                })

            } catch (error) {
                console.log("try catch:")
                console.log(error)
                this.process = false
            }
        },
        async adjustCartDetail(data){
            const authStore = useAuthStore()
            try {                
                const response = await axiosInstance.post(`/cart/adjustment`, data, {
                    headers: {
                      'Authorization': `Bearer ${authStore.token}`
                    }
                })

                return response.data

            } catch (error) {
                console.log("try catch:")
                console.log(error)
                return error.response.data;
            }
        },
        async removeCartItem(id){
            const authStore = useAuthStore()
            try {                
                const response = await axiosInstance.post(`/cart/remove/${id}`, {}, {
                    headers: {
                      'Authorization': `Bearer ${authStore.token}`
                    }
                })

                return response.data

            } catch (error) {
                console.log("try catch:")
                console.log(error)
                return error.response.data;
            }
        },
        // async getCartList(){
        //     const data = this.selected_cart_item
        //     try {
        //         const authStore = useAuthStore();
        //         await axiosInstance.post('/cart/batch_details', { list: data }, {
        //             headers: {
        //                 'Authorization': `Bearer ${authStore.token}`
        //             }
        //         }).then((response)=> {
        //             console.log("cart_batch_details: ", response.data.data)
        //         })

        //     } catch (error) {
        //         console.log("try catch")
        //         console.log(error)
        //     }
        // },
        deleteItem(index)
        {
            const currentList = this.cart_list
            currentList.splice(index, 1)

            // console.log("current index: ", index)
            // const newList = currentList.filter((item, currentIndex)=> { return currentIndex != index })
            // console.log("new_list: ", newList)
            // this.cart_list = newList
        },
        async encryptionCartItem(mode, data)
        {
          try {

            const authStore = useAuthStore()
            const result = await axiosInstance.post(`/cart/${mode}`, data, {
                headers: {
                    'Authorization': `Bearer ${authStore.token}`
                }
            })

            return result.data
            
          } catch (error) {
            console.log("try catch")
            console.log(error)
            return error
          }  
        },
    }
});