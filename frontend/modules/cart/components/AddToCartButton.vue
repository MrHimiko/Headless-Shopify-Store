<template>
    <button 
        @click="handleAddToCart"
        :disabled="disabled || isAdding"
        class="add-to-cart-btn"
        :class="{ 'add-to-cart-btn--adding': isAdding }"
    >
        <span v-if="isAdding">Adding...</span>
        <span v-else>{{ buttonText }}</span>
    </button>
</template>

<script setup>
import { ref } from 'vue'
import { useCart } from '../composables/useCart'

const props = defineProps({
    product: {
        type: Object,
        required: true
    },
    disabled: {
        type: Boolean,
        default: false
    },
    buttonText: {
        type: String,
        default: 'Add to Cart'
    }
})

const { addToCart } = useCart()
const isAdding = ref(false)

const handleAddToCart = async () => {
    isAdding.value = true

    await new Promise(resolve => setTimeout(resolve, 300))

    addToCart({
        variantId: props.product.variantId,
        productId: props.product.productId,
        title: props.product.title,
        variant: props.product.variant || 'Default',
        price: props.product.price,
        image: props.product.image,
        quantity: props.product.quantity || 1,
        handle: props.product.handle
    })

    isAdding.value = false
}
</script>

<style scoped>
.add-to-cart-btn {
    width: 100%;
    background: #000;
    color: #fff;
    border: none;
    padding: 1rem 2rem;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    border-radius: 4px;
    transition: background 0.2s;
}

.add-to-cart-btn:hover:not(:disabled) {
    background: #333;
}

.add-to-cart-btn:disabled {
    background: #999;
    cursor: not-allowed;
}

.add-to-cart-btn--adding {
    background: #666;
}
</style>