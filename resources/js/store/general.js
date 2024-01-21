import { defineStore } from 'pinia'
import axiosInstance from '../settings/axios'

export const useCommonStore = defineStore('common', {
  state: ()=> ({
    homepage_carousel: [],
    brand_list_page_cover: [],
    brand_list: [],
    brand_details: null,
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
    }
  }
})