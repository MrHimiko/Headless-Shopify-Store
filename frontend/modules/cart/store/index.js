import { defineStore } from 'pinia'
import { loadCartFromStorage, saveCartToStorage, clearCartFromStorage } from '../utils/localStorage'

export const useCartStore = defineStore('cart', {
    state: () => ({
        items: [],
        isDrawerOpen: false
    }),

    getters: {
        itemCount: (state) => {
            return state.items.reduce((total, item) => total + item.quantity, 0)
        },

        subtotal: (state) => {
            return state.items.reduce((total, item) => {
                return total + (parseFloat(item.price) * item.quantity)
            }, 0)
        },

        isEmpty: (state) => state.items.length === 0
    },

    actions: {
        initCart() {
            const savedCart = loadCartFromStorage()
            if (savedCart && savedCart.items) {
                this.items = savedCart.items
            }
        },

        addItem(product) {
            const existingItem = this.items.find(item => item.variantId === product.variantId)

            if (existingItem) {
                existingItem.quantity += product.quantity || 1
            } else {
                this.items.push({
                    variantId: product.variantId,
                    productId: product.productId,
                    title: product.title,
                    variant: product.variant || 'Default',
                    price: product.price,
                    image: product.image || null,
                    quantity: product.quantity || 1,
                    handle: product.handle
                })
            }

            this.syncToStorage()
            this.openDrawer()
        },

        removeItem(variantId) {
            this.items = this.items.filter(item => item.variantId !== variantId)
            this.syncToStorage()
        },

        updateQuantity(variantId, quantity) {
            const item = this.items.find(item => item.variantId === variantId)
            if (item) {
                if (quantity <= 0) {
                    this.removeItem(variantId)
                } else {
                    item.quantity = quantity
                    this.syncToStorage()
                }
            }
        },

        clearCart() {
            this.items = []
            clearCartFromStorage()
        },

        syncToStorage() {
            saveCartToStorage({ items: this.items })
        },

        openDrawer() {
            this.isDrawerOpen = true
        },

        closeDrawer() {
            this.isDrawerOpen = false
        },

        toggleDrawer() {
            this.isDrawerOpen = !this.isDrawerOpen
        }
    }
})