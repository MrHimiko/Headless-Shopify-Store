export const useApi = () => {
    const config = useRuntimeConfig()
    
    const apiBase = 'https://api.stagebox.store/api'
    
    const fetchProducts = async (page = 1) => {
        try {
            const { data } = await useFetch(`${apiBase}/products`, {
                params: { page }
            })
            return data.value
        } catch (error) {
            console.error('Error fetching products:', error)
            return null
        }
    }
    
    const fetchProduct = async (handle) => {
        try {
            const { data } = await useFetch(`${apiBase}/products/${handle}`)
            return data.value
        } catch (error) {
            console.error('Error fetching product:', error)
            return null
        }
    }
    
    return {
        fetchProducts,
        fetchProduct
    }
}