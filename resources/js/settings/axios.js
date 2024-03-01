import axios from "axios";

const host = import.meta.env.VITE_APP_URL;
const port = import.meta.env.VITE_SERVER_PORT;
const url = `${host}:${port}`

const axiosInstance = axios.create({
    baseURL: `${url}/api/v1`
})

export default axiosInstance