<template>
    <div class="products-page">
        <div class="products-page__header">
            <h1>All Products</h1>
        </div>
        
        <div v-if="loading" class="products-page__loading">
            <p>Loading products...</p>
        </div>
        
        <div v-else-if="error" class="products-page__error">
            <p>Error: {{ error }}</p>
        </div>
        
        <div v-else-if="products && products.length" class="products-page__grid">
            <ProductCard 
                v-for="product in products" 
                :key="product.id" 
                :product="product" 
            />
        </div>
        
        <div v-else class="products-page__empty">
            <p>No products found</p>
        </div>
    </div>
</template>

<script setup>
const products = ref([])
const loading = ref(true)
const error = ref(null)

onMounted(async () => {
    try {
        const response = await fetch('https://api.stagebox.store/api/products')
        const result = await response.json()
        
        if (result.success && result.data && result.data.edges) {
            products.value = result.data.edges.map(edge => ({
                id: edge.node.id,
                title: edge.node.title,
                handle: edge.node.handle,
                description: edge.node.description,
                priceRange: edge.node.priceRange,
                image: edge.node.images.edges[0]?.node || null
            }))
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
.products-page {
    max-width: 1400px;
    margin: 0 auto;
    padding: var(--space-2xl) var(--space-lg);
}

.products-page__header {
    margin-bottom: var(--space-2xl);
}

.products-page__header h1 {
    font-size: 2.5rem;
}

.products-page__grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: var(--space-lg);
}

.products-page__loading,
.products-page__empty,
.products-page__error {
    text-align: center;
    padding: var(--space-2xl);
    color: var(--text-secondary);
}
</style>