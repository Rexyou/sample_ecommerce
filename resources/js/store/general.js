import { defineStore } from 'pinia'
import axiosInstance from '../settings/axios'

export const useCommonStore = defineStore('common', {
  state: ()=> ({
    menu_change_color: false,
    homepage_carousel: [],
    brand_list_page_cover: [],
    brand_list: [],
    brand_details: [],
    brand_product_list: [],
    brand_product_list_pagination: [],
    paginate: 15,
    sorting: "best_selling",
    product_detail: [],
  }),
  actions: {
    async getComponent(page_name, component) {
      try {
        await axiosInstance.get(`/page_setting?page_name=${page_name}&component=${component}`)
        .then(async (response)=> {
            
            if(page_name === "home" && component === "carousel"){
              this.homepage_carousel = response.data.data.data
            }
            else{
              console.log("working")
              console.log(response.data.data)
              this.brand_list_page_cover = response.data.data
              console.log("cover: ", this.brand_list_page_cover)
            }

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
    async getBrandsList(){
      try {

        await axiosInstance.get('/brands')
        .then((response)=> {
          this.brand_list = response.data.data
        }).catch((error)=> {
          console.log('axios error:')
          console.log(error)
        })
        
      } catch (error) {
        console.log("try catch:")
        console.log(error)
      }
    },
    async getBrand(id, slug=""){
      try {
        
        await axiosInstance.get(`/brands/${id}/${slug}`)
        .then((response)=> {
          console.log("response")
          console.log(response.data)
          this.brand_details = response.data.data
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
    async getBrandProducts(id, params)
    {
      try {
        params += `&paginate=${this.paginate}&sorting=${this.sorting}`
        console.log(params)
        await axiosInstance.get(`/brand/${id}/products?${params}`)
        .then((response)=> {
          console.log("getBrandProduct")
          console.log(response.data.data)
          this.brand_product_list = response.data.data.data;
          this.brand_product_list_pagination = response.data.data;
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
    async getProduct(id){
      try {
        await axiosInstance.get(`product/${id}`)
        .then((response)=> {
          console.log("get product list")
          this.product_detail = response.data.data
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
})