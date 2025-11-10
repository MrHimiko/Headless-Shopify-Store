import { useCartStore } from '~/modules/cart/store'

export default defineNuxtPlugin(() => {
    if (process.client) {
        const cartStore = useCartStore()
        cartStore.initCart()
    }
})