<template>
    <div class="c-product-gallery">
        <div class="c-product-gallery__main">
            <img 
                v-if="images && images.length" 
                :src="activeImage.url" 
                :alt="activeImage.altText || 'Product image'"
            />
            <div v-else class="c-product-gallery__no-image">
                No Image Available
            </div>
        </div>
        
        <div v-if="images && images.length > 1" class="c-product-gallery__thumbnails">
            <button 
                v-for="(image, index) in images" 
                :key="index"
                class="c-product-gallery__thumbnail"
                :class="{ active: activeIndex === index }"
                @click="activeIndex = index"
            >
                <img :src="image.url" :alt="image.altText || `Image ${index + 1}`" />
            </button>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    images: {
        type: Array,
        default: () => []
    }
})

const activeIndex = ref(0)

const activeImage = computed(() => {
    return props.images[activeIndex.value] || { url: '', altText: '' }
})
</script>

<style src="./style.css"></style>