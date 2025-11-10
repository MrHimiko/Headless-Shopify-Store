<template>
    <div>
        <Transition name="overlay">
            <div 
                v-if="isDrawerOpen" 
                class="cart-drawer-overlay"
                @click="closeCart"
            ></div>
        </Transition>

        <Transition name="drawer">
            <aside v-if="isDrawerOpen" class="cart-drawer">
                <div class="cart-drawer__header">
                    <h2 class="cart-drawer__title">Shopping Cart</h2>
                    <button 
                        @click="closeCart"
                        class="cart-drawer__close"
                        aria-label="Close cart"
                    >
                        <svg 
                            xmlns="http://www.w3.org/2000/svg" 
                            fill="none" 
                            viewBox="0 0 24 24" 
                            stroke="currentColor"
                        >
                            <path 
                                stroke-linecap="round" 
                                stroke-linejoin="round" 
                                stroke-width="2" 
                                d="M6 18L18 6M6 6l12 12" 
                            />
                        </svg>
                    </button>
                </div>

                <div v-if="isEmpty" class="cart-drawer__empty">
                    <p>Your cart is empty</p>
                    <button @click="closeCart" class="cart-drawer__continue">
                        Continue Shopping
                    </button>
                </div>

                <div v-else class="cart-drawer__content">
                    <div class="cart-drawer__items">
                        <CartItem 
                            v-for="item in items" 
                            :key="item.variantId"
                            :item="item"
                        />
                    </div>

                    <div class="cart-drawer__footer">
                        <div class="cart-drawer__subtotal">
                            <span>Subtotal</span>
                            <span class="cart-drawer__subtotal-amount">
                                {{ formatPrice(subtotal) }}
                            </span>
                        </div>

                        <p class="cart-drawer__notice">
                            Shipping and taxes calculated at checkout
                        </p>

                        <button 
                            @click="handleCheckout"
                            class="cart-drawer__checkout"
                            :disabled="isCheckingOut"
                        >
                            {{ isCheckingOut ? 'Processing...' : 'Proceed to Checkout' }}
                        </button>

                        <button 
                            @click="closeCart"
                            class="cart-drawer__continue-link"
                        >
                            Continue Shopping
                        </button>
                    </div>
                </div>
            </aside>
        </Transition>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { useCart } from '../composables/useCart'
import { formatPrice } from '../utils/cartHelpers'
import CartItem from './CartItem.vue'

const { 
    items, 
    subtotal, 
    isEmpty, 
    isDrawerOpen, 
    closeCart,
    proceedToCheckout
} = useCart()

const isCheckingOut = ref(false)

const handleCheckout = async () => {
    isCheckingOut.value = true
    
    try {
        const checkoutUrl = await proceedToCheckout()
        
        if (checkoutUrl) {
            window.open(checkoutUrl, '_blank')
            closeCart()
        } else {
            alert('Unable to create checkout. Please try again.')
        }
    } catch (error) {
        console.error('Checkout error:', error)
        alert('An error occurred. Please try again.')
    } finally {
        isCheckingOut.value = false
    }
}
</script>

<style scoped>
.cart-drawer-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 999;
}

.cart-drawer {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    max-width: 480px;
    background: #fff;
    z-index: 1000;
    display: flex;
    flex-direction: column;
    box-shadow: -2px 0 8px rgba(0, 0, 0, 0.1);
}

.cart-drawer__header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #e5e5e5;
}

.cart-drawer__title {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0;
}

.cart-drawer__close {
    background: none;
    border: none;
    cursor: pointer;
    padding: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.cart-drawer__close svg {
    width: 24px;
    height: 24px;
}

.cart-drawer__empty {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    text-align: center;
}

.cart-drawer__empty p {
    font-size: 1.125rem;
    color: #666;
    margin-bottom: 1.5rem;
}

.cart-drawer__continue {
    background: #000;
    color: #fff;
    border: none;
    padding: 1rem 2rem;
    font-size: 1rem;
    cursor: pointer;
    border-radius: 4px;
    transition: background 0.2s;
}

.cart-drawer__continue:hover {
    background: #333;
}

.cart-drawer__content {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.cart-drawer__items {
    flex: 1;
    overflow-y: auto;
    padding: 1.5rem;
}

.cart-drawer__footer {
    border-top: 1px solid #e5e5e5;
    padding: 1.5rem;
    background: #fafafa;
}

.cart-drawer__subtotal {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 1.125rem;
    margin-bottom: 0.5rem;
}

.cart-drawer__subtotal-amount {
    font-weight: 600;
    font-size: 1.25rem;
}

.cart-drawer__notice {
    font-size: 0.875rem;
    color: #666;
    margin: 0.5rem 0 1.5rem;
}

.cart-drawer__checkout {
    width: 100%;
    background: #000;
    color: #fff;
    border: none;
    padding: 1rem;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    border-radius: 4px;
    transition: background 0.2s;
    margin-bottom: 1rem;
}

.cart-drawer__checkout:hover:not(:disabled) {
    background: #333;
}

.cart-drawer__checkout:disabled {
    background: #999;
    cursor: not-allowed;
}

.cart-drawer__continue-link {
    width: 100%;
    background: none;
    border: none;
    color: #666;
    font-size: 0.875rem;
    cursor: pointer;
    text-decoration: underline;
}

.cart-drawer__continue-link:hover {
    color: #000;
}

.overlay-enter-active,
.overlay-leave-active {
    transition: opacity 0.3s;
}

.overlay-enter-from,
.overlay-leave-to {
    opacity: 0;
}

.drawer-enter-active,
.drawer-leave-active {
    transition: transform 0.3s;
}

.drawer-enter-from,
.drawer-leave-to {
    transform: translateX(100%);
}

@media (max-width: 640px) {
    .cart-drawer {
        max-width: 100%;
    }
}
</style>