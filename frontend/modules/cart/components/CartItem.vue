<template>
    <div class="cart-item">
        <div class="cart-item__image">
            <img 
                v-if="item.image" 
                :src="item.image" 
                :alt="item.title"
            />
            <div v-else class="cart-item__image-placeholder">
                No image
            </div>
        </div>

        <div class="cart-item__details">
            <NuxtLink 
                :to="`/products/${item.handle}`" 
                class="cart-item__title"
            >
                {{ item.title }}
            </NuxtLink>
            
            <div class="cart-item__variant">
                {{ item.variant }}
            </div>

            <div class="cart-item__price">
                {{ formatPrice(item.price) }}
            </div>
        </div>

        <div class="cart-item__actions">
            <div class="cart-item__quantity">
                <button 
                    @click="decreaseQuantity"
                    class="cart-item__qty-btn"
                    aria-label="Decrease quantity"
                >
                    -
                </button>
                
                <input 
                    type="number" 
                    :value="item.quantity"
                    @change="handleQuantityChange"
                    class="cart-item__qty-input"
                    min="1"
                />
                
                <button 
                    @click="increaseQuantity"
                    class="cart-item__qty-btn"
                    aria-label="Increase quantity"
                >
                    +
                </button>
            </div>

            <button 
                @click="remove"
                class="cart-item__remove"
                aria-label="Remove item"
            >
                Remove
            </button>
        </div>

        <div class="cart-item__total">
            {{ formatPrice(itemTotal) }}
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'
import { useCart } from '../composables/useCart'
import { formatPrice, calculateItemTotal } from '../utils/cartHelpers'

const props = defineProps({
    item: {
        type: Object,
        required: true
    }
})

const { updateItemQuantity, removeFromCart } = useCart()

const itemTotal = computed(() => calculateItemTotal(props.item.price, props.item.quantity))

const increaseQuantity = () => {
    updateItemQuantity(props.item.variantId, props.item.quantity + 1)
}

const decreaseQuantity = () => {
    if (props.item.quantity > 1) {
        updateItemQuantity(props.item.variantId, props.item.quantity - 1)
    }
}

const handleQuantityChange = (event) => {
    const newQuantity = parseInt(event.target.value)
    if (newQuantity > 0) {
        updateItemQuantity(props.item.variantId, newQuantity)
    }
}

const remove = () => {
    removeFromCart(props.item.variantId)
}
</script>

<style scoped>
.cart-item {
    display: grid;
    grid-template-columns: 80px 1fr auto auto;
    gap: 1rem;
    padding: 1rem 0;
    border-bottom: 1px solid #e5e5e5;
}

.cart-item__image {
    width: 80px;
    height: 80px;
    background: #f5f5f5;
    border-radius: 4px;
    overflow: hidden;
}

.cart-item__image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.cart-item__image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    color: #999;
}

.cart-item__details {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.cart-item__title {
    font-weight: 500;
    color: #000;
    text-decoration: none;
}

.cart-item__title:hover {
    text-decoration: underline;
}

.cart-item__variant {
    font-size: 0.875rem;
    color: #666;
}

.cart-item__price {
    font-size: 0.875rem;
    color: #666;
}

.cart-item__actions {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    align-items: center;
}

.cart-item__quantity {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    border: 1px solid #e5e5e5;
    border-radius: 4px;
}

.cart-item__qty-btn {
    background: none;
    border: none;
    padding: 0.5rem;
    cursor: pointer;
    font-size: 1rem;
    line-height: 1;
    color: #666;
}

.cart-item__qty-btn:hover {
    color: #000;
}

.cart-item__qty-input {
    width: 40px;
    text-align: center;
    border: none;
    font-size: 0.875rem;
}

.cart-item__qty-input::-webkit-inner-spin-button,
.cart-item__qty-input::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.cart-item__remove {
    background: none;
    border: none;
    color: #999;
    font-size: 0.75rem;
    cursor: pointer;
    text-decoration: underline;
}

.cart-item__remove:hover {
    color: #000;
}

.cart-item__total {
    font-weight: 600;
    align-self: start;
}

@media (max-width: 640px) {
    .cart-item {
        grid-template-columns: 60px 1fr;
        grid-template-rows: auto auto;
    }

    .cart-item__image {
        width: 60px;
        height: 60px;
    }

    .cart-item__actions {
        grid-column: 1 / -1;
        flex-direction: row;
        justify-content: space-between;
    }

    .cart-item__total {
        grid-column: 2;
        grid-row: 1;
        text-align: right;
    }
}
</style>