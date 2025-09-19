import { defineNuxtPlugin } from '#app'
import axios from 'axios'

export default defineNuxtPlugin(async () => {
    const api = axios.create({
        baseURL: "http://127.0.0.1:8000/api",
        withCredentials: true,
        withXSRFToken: true,
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json"
        }
    });

    await axios.get("/csrf-cookie", {
        withCredentials: true,
    }).catch(() => {})

    return {
        provide: {
            api,
        },
    }
})