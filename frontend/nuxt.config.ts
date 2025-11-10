export default defineNuxtConfig({
    compatibilityDate: '2025-11-01',
    
    devtools: { enabled: true },
    
    modules: ['@pinia/nuxt'],
    
    app: {
        head: {
            title: 'Shopify Headless Store',
            meta: [
                { charset: 'utf-8' },
                { name: 'viewport', content: 'width=device-width, initial-scale=1' }
            ]
        }
    },

    vite: {
        server: {
            host: '0.0.0.0',
            allowedHosts: ['stagebox.store', 'www.stagebox.store']
        }
    },
    
    runtimeConfig: {
        public: {
            apiBase: process.env.NUXT_PUBLIC_API_BASE || 'https://api.stagebox.store'
        }
    }
})