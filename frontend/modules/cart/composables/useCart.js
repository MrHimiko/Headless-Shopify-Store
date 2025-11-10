import { useCartStore } from '../store'
import { storeToRefs } from 'pinia'

export const useCart = () => {
    const cartStore = useCartStore()
    const { items, itemCount, subtotal, isEmpty, isDrawerOpen } = storeToRefs(cartStore)

    const addToCart = (product) => {
        cartStore.addItem(product)
    }

    const removeFromCart = (variantId) => {
        cartStore.removeItem(variantId)
    }

    const updateItemQuantity = (variantId, quantity) => {
        cartStore.updateQuantity(variantId, quantity)
    }

    const clearCart = () => {
        cartStore.clearCart()
    }

    const openCart = () => {
        cartStore.openDrawer()
    }

    const closeCart = () => {
        cartStore.closeDrawer()
    }

    const toggleCart = () => {
        cartStore.toggleDrawer()
    }

    const proceedToCheckout = async () => {
        if (isEmpty.value) {
            return null
        }

        try {
            const config = useRuntimeConfig()
            const apiBase = config.public.apiBase || 'https://api.stagebox.store'

            const lines = items.value.map(item => ({
                merchandiseId: item.variantId,
                quantity: item.quantity
            }))

            const response = await $fetch(`${apiBase}/api/cart/create`, {
                method: 'POST',
                body: { lines }
            })

            if (response.checkoutUrl) {
                return response.checkoutUrl
            }

            return null
        } catch (error) {
            console.error('Checkout error:', error)
            return null
        }
    }

    return {
        items,
        itemCount,
        subtotal,
        isEmpty,
        isDrawerOpen,
        addToCart,
        removeFromCart,
        updateItemQuantity,
        clearCart,
        openCart,
        closeCart,
        toggleCart,
        proceedToCheckout
    }
}