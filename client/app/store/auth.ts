import { defineStore } from "pinia";
import type { AxiosResponse } from "axios";

export const useAuthStore = defineStore('auth',  () => {
    const api = useNuxtApp().$api;
    const email = ref<string | null>(null);
    const name = ref<string | null>(null);
    const roles = ref<string[]>([]);
    const isAdmin = ref<boolean>(false);
    const isLoggedIn = computed(() => {
        return !!email.value;
    })

    async function login(emailInput: string, passwordInput: string) {
        await api.get('/csrf-cookie', { withCredentials: true });
        return api.post<{ email: string; name: string; roles: string[];}>('logIn', {
            email: emailInput,
            password: passwordInput,
        }, {withCredentials: true})
            .then((response: AxiosResponse) => {
                email.value = response.data.email;
                name.value = response.data.name;
                isAdmin.value = response.data.roles.includes("admin");
            })
    }

    async function logout() {
        await api.post('logOut', {}, { withCredentials: true });
        return api
            .post('logOut')
            .finally(() => {
                email.value = null;
                name.value = null;
                roles.value = [];
                isAdmin.value = false;
            });
    }
    return { email, name, roles, isLoggedIn, isAdmin, login, logout };
}, {
    persist: {
        storage: piniaPluginPersistedstate.localStorage(),
    },
});