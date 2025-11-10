/**
 * @typedef {Object} CartItem
 * @property {string} variantId - Shopify variant ID (e.g., "gid://shopify/ProductVariant/123")
 * @property {string} productId - Shopify product ID
 * @property {string} title - Product title
 * @property {string} variant - Variant title (e.g., "Small / Blue")
 * @property {string} price - Price as string (e.g., "29.99")
 * @property {string|null} image - Image URL
 * @property {number} quantity - Quantity in cart
 * @property {string} handle - Product handle for linking
 */

/**
 * @typedef {Object} CartState
 * @property {CartItem[]} items - Array of cart items
 */

/**
 * @typedef {Object} CheckoutLine
 * @property {string} merchandiseId - Shopify variant ID
 * @property {number} quantity - Quantity to purchase
 */

export const CART_STORAGE_KEY = 'shopify_cart'