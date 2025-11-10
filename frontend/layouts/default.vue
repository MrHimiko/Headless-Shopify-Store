<template>
    <div>
        <Navbar />
        <main>
            <slot />
        </main>
        
        <CartDrawer />
        
        <Popup name="account">
            <h3>Account</h3>
            <p>Login/Register functionality coming soon...</p>
        </Popup>
        
        <Popup name="search">
            <h3>Search</h3>
            <p>Search functionality coming soon...</p>
        </Popup>
    </div>
</template>

<script setup>
import CartDrawer from '~/modules/cart/components/CartDrawer.vue'
</script>