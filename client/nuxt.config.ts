// nuxt.config.ts
export default defineNuxtConfig({
    compatibilityDate: '2025-07-15',
    devtools: { enabled: true },
    modules: [
        '@nuxt/eslint',
        '@nuxt/image',
        '@nuxt/ui',
        '@pinia/nuxt',
        'pinia-plugin-persistedstate/nuxt',
    ],
    piniaPluginPersistedstate: {
        storage: 'cookies',
        cookieOptions: {
            sameSite: 'lax',
        },
        debug: true,
    },
    runtimeConfig: {
        public: {
            apiBase: '127.0.0.1:8000/api',
        },
    },
    components: [{ path: '~/components' }],
    ssr: false,
    routeRules: {
        '/**': {ssr: false}
    },
    css: ['~/assets/css/tailwind.css'],
    devServer: {
        host: '127.0.0.1',
        port: 3000,
    }
})