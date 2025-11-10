<template>
    <button 
        class="mini-cart" 
        @click="toggleCart"
        aria-label="Open shopping cart"
    >
        <svg 
            class="mini-cart__icon" 
            xmlns="http://www.w3.org/2000/svg" 
            fill="none" 
            viewBox="0 0 24 24" 
            stroke="currentColor"
        >
            <path 
                stroke-linecap="round" 
                stroke-linejoin="round" 
                stroke-width="2" 
                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" 
            />
        </svg>
        
        <span v-if="itemCount > 0" class="mini-cart__badge">
            {{ itemCount }}
        </span>
    </button>
</template>

<script setup>
import { useCart } from '../composables/useCart'

const { itemCount, toggleCart } = useCart()
</script>

<style scoped>
.mini-cart {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    background: none;
    border: none;
    cursor: pointer;
    padding: 0.5rem;
    transition: opacity 0.2s;
}

.mini-cart:hover {
    opacity: 0.7;
}

.mini-cart__icon {
    width: 24px;
    height: 24px;
    color: currentColor;
}

.mini-cart__badge {
    position: absolute;
    top: 0;
    right: 0;
    background: #000;
    color: #fff;
    font-size: 0.75rem;
    font-weight: 600;
    min-width: 18px;
    height: 18px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 4px;
}
</style>