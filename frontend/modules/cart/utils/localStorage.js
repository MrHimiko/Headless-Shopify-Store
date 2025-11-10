const CART_STORAGE_KEY = 'shopify_cart'

export const loadCartFromStorage = () => {
    if (typeof window === 'undefined') {
        return null
    }

    try {
        const stored = localStorage.getItem(CART_STORAGE_KEY)
        return stored ? JSON.parse(stored) : null
    } catch (error) {
        console.error('Error loading cart from storage:', error)
        return null
    }
}

export const saveCartToStorage = (cart) => {
    if (typeof window === 'undefined') {
        return
    }

    try {
        localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cart))
    } catch (error) {
        console.error('Error saving cart to storage:', error)
    }
}

export const clearCartFromStorage = () => {
    if (typeof window === 'undefined') {
        return
    }

    try {
        localStorage.removeItem(CART_STORAGE_KEY)
    } catch (error) {
        console.error('Error clearing cart from storage:', error)
    }
}