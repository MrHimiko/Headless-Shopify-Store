<template>
    <div>
        <Navbar />
        <main>
            <slot />
        </main>
        
        <Popup name="account">
            <h3>Account</h3>
            <p>Login/Register functionality coming soon...</p>
        </Popup>
        
        <Popup name="cart">
            <h3>Shopping Cart</h3>
            <p>Cart is empty</p>
        </Popup>
        
        <Popup name="search">
            <h3>Search</h3>
            <p>Search functionality coming soon...</p>
        </Popup>
    </div>
</template>