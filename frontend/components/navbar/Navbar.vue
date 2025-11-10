<template>
    <nav class="c-navbar">
        <div class="c-navbar__container">
            <NuxtLink to="/" class="c-navbar__logo">
                <h2>STORE</h2>
            </NuxtLink>
            
            <ul class="c-navbar__links">
                <li><NuxtLink to="/products">Products</NuxtLink></li>
                <li><NuxtLink to="/collections">Collections</NuxtLink></li>
                <li><NuxtLink to="/about">About</NuxtLink></li>
            </ul>
            
            <div class="c-navbar__actions">
                <button @click="openPopup('search')" class="c-navbar__icon">
                    <PhMagnifyingGlass :size="24" />
                </button>
                <button @click="openPopup('account')" class="c-navbar__icon">
                    <PhUser :size="24" />
                </button>
                <button @click="toggleCart" class="c-navbar__icon c-navbar__cart">
                    <PhShoppingCart :size="24" />
                    <span v-if="itemCount > 0" class="c-navbar__cart-badge">
                        {{ itemCount }}
                    </span>
                </button>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { PhMagnifyingGlass, PhUser, PhShoppingCart } from '@phosphor-icons/vue'
import { useCart } from '~/modules/cart/composables/useCart'

const { openPopup } = usePopup()
const { itemCount, toggleCart } = useCart()
</script>

<style src="./style.css"></style>