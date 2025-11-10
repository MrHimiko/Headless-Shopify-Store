export const usePopup = () => {
    const activePopup = useState('activePopup', () => null)
    
    const openPopup = (name) => {
        activePopup.value = name
        document.body.style.overflow = 'hidden'
    }
    
    const closePopup = () => {
        activePopup.value = null
        document.body.style.overflow = ''
    }
    
    const isOpen = (name) => {
        return activePopup.value === name
    }
    
    return {
        activePopup,
        openPopup,
        closePopup,
        isOpen
    }
}