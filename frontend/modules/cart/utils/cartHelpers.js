export const formatPrice = (amount, currencyCode = 'EUR') => {
    const price = parseFloat(amount)
    
    return new Intl.NumberFormat('de-DE', {
        style: 'currency',
        currency: currencyCode
    }).format(price)
}

export const calculateItemTotal = (price, quantity) => {
    return parseFloat(price) * quantity
}

export const calculateCartSubtotal = (items) => {
    return items.reduce((total, item) => {
        return total + calculateItemTotal(item.price, item.quantity)
    }, 0)
}

export const getCartItemCount = (items) => {
    return items.reduce((total, item) => total + item.quantity, 0)
}