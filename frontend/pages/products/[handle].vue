<template>
    <div v-if="loading" class="product-loading">
        <p>Loading product...</p>
    </div>
    
    <div v-else-if="error" class="product-error">
        <h2>Product Not Found</h2>
        <p>{{ error }}</p>
        <NuxtLink to="/products">← Back to Products</NuxtLink>
    </div>
    
    <div v-else-if="product" class="product-page">
        <div class="product-page__container">
            <div class="product-page__gallery">
                <ProductGallery :images="product.images" />
            </div>
            
            <div class="product-page__details">
                <div class="product-page__breadcrumb">
                    <NuxtLink to="/products">Products</NuxtLink>
                    <span>/</span>
                    <span>{{ product.title }}</span>
                </div>
                
                <h1 class="product-page__title">{{ product.title }}</h1>
                
                <div class="product-page__price">
                    {{ formatPrice(product.priceRange.minVariantPrice.amount) }}
                </div>
                
                <div class="product-page__description" v-if="product.description">
                    <p>{{ product.description }}</p>
                </div>
                
                <div class="product-page__variants" v-if="product.variants && product.variants.length > 1">
                    <label>Options:</label>
                    <div class="product-page__variant-list">
                        <button 
                            v-for="variant in product.variants" 
                            :key="variant.id"
                            class="product-page__variant-btn"
                            :class="{ active: selectedVariant?.id === variant.id }"
                            @click="selectedVariant = variant"
                        >
                            {{ variant.title }}
                        </button>
                    </div>
                </div>
                
                <div class="product-page__actions">
                    <button class="product-page__add-to-cart" @click="addToCart">
                        <PhShoppingCart :size="20" />
                        Add to Cart
                    </button>
                    <button class="product-page__wishlist">
                        <PhHeart :size="20" />
                    </button>
                </div>
                
                <div class="product-page__meta">
                    <div class="product-page__meta-item">
                        <strong>Availability:</strong>
                        <span v-if="selectedVariant?.availableForSale" class="in-stock">In Stock</span>
                        <span v-else class="out-of-stock">Out of Stock</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { PhShoppingCart, PhHeart } from '@phosphor-icons/vue'

const route = useRoute()
const product = ref(null)
const loading = ref(true)
const error = ref(null)
const selectedVariant = ref(null)

const formatPrice = (price) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'EUR'
    }).format(price)
}

const addToCart = () => {
    console.log('Add to cart:', selectedVariant.value || product.value)
}

onMounted(async () => {
    try {
        const response = await fetch(`https://api.stagebox.store/api/products/${route.params.handle}`)
        const result = await response.json()
        
        if (result.success && result.data) {
            const data = result.data
            
            product.value = {
                id: data.id,
                title: data.title,
                handle: data.handle,
                description: data.description,
                priceRange: data.priceRange,
                images: data.images.edges.map(edge => edge.node),
                variants: data.variants.edges.map(edge => ({
                    id: edge.node.id,
                    title: edge.node.title || 'Default',
                    availableForSale: edge.node.availableForSale
                }))
            }
            
            selectedVariant.value = product.value.variants[0]
        } else {
            error.value = 'Product not found'
        }
    } catch (e) {
        console.error('Fetch error:', e)
        error.value = e.message
    } finally {
        loading.value = false
    }
})
</script>

<style scoped>
.product-page {
    max-width: 1400px;
    margin: 0 auto;
    padding: var(--space-2xl) var(--space-lg);
}

.product-page__container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--space-2xl);
}

.product-page__breadcrumb {
    font-size: 0.875rem;
    color: var(--text-secondary);
    margin-bottom: var(--space-md);
    display: flex;
    gap: var(--space-xs);
}

.product-page__breadcrumb a {
    color: var(--brand-primary);
}

.product-page__breadcrumb a:hover {
    text-decoration: underline;
}

.product-page__title {
    font-size: 2rem;
    margin-bottom: var(--space-md);
}

.product-page__price {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--brand-primary);
    margin-bottom: var(--space-lg);
}

.product-page__description {
    color: var(--text-secondary);
    line-height: 1.7;
    margin-bottom: var(--space-xl);
}

.product-page__variants {
    margin-bottom: var(--space-xl);
}

.product-page__variants label {
    display: block;
    font-weight: 600;
    margin-bottom: var(--space-sm);
}

.product-page__variant-list {
    display: flex;
    gap: var(--space-sm);
    flex-wrap: wrap;
}

.product-page__variant-btn {
    padding: var(--space-sm) var(--space-lg);
    border: 2px solid var(--border-light);
    background: var(--bg-primary);
    border-radius: var(--radius-sm);
    cursor: pointer;
    transition: all var(--transition);
    font-weight: 500;
}

.product-page__variant-btn:hover {
    border-color: var(--brand-primary);
}

.product-page__variant-btn.active {
    border-color: var(--brand-primary);
    background: var(--brand-primary);
    color: white;
}

.product-page__actions {
    display: flex;
    gap: var(--space-md);
    margin-bottom: var(--space-xl);
}

.product-page__add-to-cart {
    flex: 1;
    padding: var(--space-md) var(--space-xl);
    background: var(--brand-primary);
    color: white;
    border: none;
    border-radius: var(--radius-md);
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: background var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-sm);
}

.product-page__add-to-cart:hover {
    background: var(--brand-secondary);
}

.product-page__wishlist {
    padding: var(--space-md);
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-md);
    cursor: pointer;
    transition: all var(--transition);
    display: flex;
    align-items: center;
}

.product-page__wishlist:hover {
    background: var(--bg-tertiary);
}

.product-page__meta {
    padding-top: var(--space-lg);
    border-top: 1px solid var(--border-light);
}

.product-page__meta-item {
    display: flex;
    gap: var(--space-sm);
    margin-bottom: var(--space-sm);
}

.product-page__meta-item strong {
    min-width: 120px;
}

.in-stock {
    color: var(--success);
}

.out-of-stock {
    color: var(--error);
}

.product-loading,
.product-error {
    max-width: 1400px;
    margin: 0 auto;
    padding: var(--space-2xl);
    text-align: center;
}

.product-error h2 {
    margin-bottom: var(--space-md);
}

.product-error a {
    color: var(--brand-primary);
}

@media (max-width: 768px) {
    .product-page__container {
        grid-template-columns: 1fr;
        gap: var(--space-xl);
    }
}
</style>