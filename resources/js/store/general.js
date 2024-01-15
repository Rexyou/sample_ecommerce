import { defineStore } from 'pinia'
import axiosInstance from '../settings/axios'

export const useCommonStore = defineStore('common', {
  state: ()=> ({
    homepage_carousel: [],
  }),
  getters: {
    homepage_carousel_data: (state)=> state.homepage_carousel
  },
  persist: {
      storage: sessionStorage,
  },
  actions: {
    async homepageCarousel() {
      try {
        await axiosInstance.get('/page_setting?page_name=home&component=carousel')
        .then(async (response)=> {
            console.log("pinia")
            console.log(response.data.data)
            this.homepage_carousel = response.data.data.data
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
  }
})