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
                <button @click="openPopup('cart')" class="c-navbar__icon">
                    <PhShoppingCart :size="24" />
                </button>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { PhMagnifyingGlass, PhUser, PhShoppingCart } from '@phosphor-icons/vue'

const { openPopup } = usePopup()
</script>

<style src="./style.css"></style>