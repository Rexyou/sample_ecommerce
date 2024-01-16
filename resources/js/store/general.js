import { defineStore } from 'pinia'
import axiosInstance from '../settings/axios'

export const useCommonStore = defineStore('common', {
  state: ()=> ({
    homepage_carousel: [],
    brand_list_page_cover: null,
    brand_list: [],
  }),
  getters: {
    homepage_carousel_data: (state)=> state.homepage_carousel
  },
  persist: {
      storage: sessionStorage,
  },
  actions: {
    async getComponent(page_name, component) {
      try {
        await axiosInstance.get(`/page_setting?page_name=${page_name}&component=${component}`)
        .then(async (response)=> {
            
            if(page_name === "home" && component === "carousel"){
              this.homepage_carousel = response.data.data.data
            }
            else if(page_name === "brand_list" && component === "cover_page"){
              this.brand_list_page_cover = response.data.data
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
    }
  }
})