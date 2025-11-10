<template>
    <NuxtLink :to="`/products/${product.handle}`" class="c-product-card">
        <div class="c-product-card__image">
            <img 
                v-if="product.image" 
                :src="product.image.url" 
                :alt="product.title"
            />
            <div v-else class="c-product-card__no-image">No Image</div>
        </div>
        
        <div class="c-product-card__content">
            <h3 class="c-product-card__title">{{ product.title }}</h3>
            <p class="c-product-card__price">{{ formatPrice(product.priceRange.minVariantPrice.amount) }}</p>
        </div>
    </NuxtLink>
</template>

<script setup>
const props = defineProps({
    product: {
        type: Object,
        required: true
    }
})

const formatPrice = (price) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(price)
}
</script>

<style src="./style.css"></style>