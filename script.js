document.addEventListener('DOMContentLoaded', function() {
    // Mobile Menu Toggle
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const nav = document.querySelector('nav');
    
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function() {
            nav.style.display = nav.style.display === 'block' ? 'none' : 'block';
        });
    }
    
    // Modal Functionality
    const loginButton = document.getElementById('login-button');
    const loginModal = document.getElementById('login-modal');
    const closeModal = document.querySelector('.close-modal');
    
    if (loginButton && loginModal) {
        loginButton.addEventListener('click', function(e) {
            e.preventDefault();
            loginModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    }
    
    if (closeModal && loginModal) {
        closeModal.addEventListener('click', function() {
            loginModal.classList.remove('active');
            document.body.style.overflow = 'auto';
        });
    }
    
    // Close modal when clicking outside of it
    window.addEventListener('click', function(e) {
        if (loginModal && e.target === loginModal) {
            loginModal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    });
    
    // Tab Functionality
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            
            // Remove active class from all buttons and tabs
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Add active class to current button and tab
            this.classList.add('active');
            document.getElementById(tabId).classList.add('active');
        });
    });
    


    
    // Shopping Cart Functionality
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
    const cartCount = document.querySelector('.cart-count');
    let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
    
    // Update cart count display
    function updateCartCount() {
        if (cartCount) {
            cartCount.textContent = cartItems.length;
        }
    }
    
    // Initialize cart count on page load
    updateCartCount();
    
    // Add to cart functionality
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const price = parseFloat(this.getAttribute('data-price'));
            
            // Create new cart item
            const newItem = {
                id,
                name,
                price,
                quantity: 1
            };
            
            // Check if item already exists in cart
            const existingItemIndex = cartItems.findIndex(item => item.id === id);
            
            if (existingItemIndex !== -1) {
                // Update quantity if item exists
                cartItems[existingItemIndex].quantity += 1;
            } else {
                // Add new item if it doesn't exist
                cartItems.push(newItem);
            }
            
            // Save to localStorage
            localStorage.setItem('cartItems', JSON.stringify(cartItems));
            
            // Update cart count display
            updateCartCount();
            
            // Show confirmation
            alert(`${name} added to cart!`);
        });
    });
    
    // Render cart items on cart page
    const cartItemsContainer = document.getElementById('cart-items');
    
    function renderCart() {
        if (cartItemsContainer && cartItems.length > 0) {
            // Remove empty cart message if present
            const emptyCartMessage = cartItemsContainer.querySelector('.empty-cart-message');
            if (emptyCartMessage) {
                emptyCartMessage.remove();
            }
            
            // Clear existing items
            cartItemsContainer.innerHTML = '';
            
            let subtotal = 0;
            
            // Add each item to the cart display
            cartItems.forEach((item, index) => {
                const itemTotal = item.price * item.quantity;
                subtotal += itemTotal;
                
                const cartItemElement = document.createElement('div');
                cartItemElement.className = 'cart-item';
                cartItemElement.innerHTML = `
                    <div class="cart-item-image">
                        <i class="fas fa-recycle"></i>
                    </div>
                    <div class="cart-item-details">
                        <div class="cart-item-name">${item.name}</div>
                        <div class="cart-item-description">Waste management service</div>
                        <div class="cart-item-price">$${item.price.toFixed(2)}</div>
                    </div>
                    <div class="cart-item-actions">
                        <div class="quantity-controls">
                            <button class="quantity-btn decrease-btn" data-index="${index}">-</button>
                            <span class="quantity-value">${item.quantity}</span>
                            <button class="quantity-btn increase-btn" data-index="${index}">+</button>
                        </div>
                        <button class="remove-btn" data-index="${index}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
                
                cartItemsContainer.appendChild(cartItemElement);
            });
            
            // Add event listeners for quantity controls and remove buttons
            const decreaseButtons = document.querySelectorAll('.decrease-btn');
            const increaseButtons = document.querySelectorAll('.increase-btn');
            const removeButtons = document.querySelectorAll('.remove-btn');
            
            decreaseButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    if (cartItems[index].quantity > 1) {
                        cartItems[index].quantity -= 1;
                        localStorage.setItem('cartItems', JSON.stringify(cartItems));
                        renderCart();
                        updateCartSummary();
                    }
                });
            });
            
            increaseButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    cartItems[index].quantity += 1;
                    localStorage.setItem('cartItems', JSON.stringify(cartItems));
                    renderCart();
                    updateCartSummary();
                });
            });
            
            removeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    cartItems.splice(index, 1);
                    localStorage.setItem('cartItems', JSON.stringify(cartItems));
                    updateCartCount();
                    renderCart();
                    updateCartSummary();
                    
                    // Show empty cart message if cart is empty
                    if (cartItems.length === 0) {
                        showEmptyCartMessage();
                    }
                });
            });
            
            // Update cart summary
            updateCartSummary();
        } else if (cartItemsContainer && cartItems.length === 0) {
            showEmptyCartMessage();
        }
    }
    
    function showEmptyCartMessage() {
        if (cartItemsContainer) {
            cartItemsContainer.innerHTML = `
                <div class="empty-cart-message">
                    <i class="fas fa-shopping-cart fa-3x"></i>
                    <p>Your cart is empty</p>
                    <a href="index.html#services" class="btn btn-primary">Browse Services</a>
                </div>
            `;
        }
    }
    
    function updateCartSummary() {
        const subtotalElement = document.getElementById('subtotal');
        const discountElement = document.getElementById('discount');
        const taxElement = document.getElementById('tax');
        const totalElement = document.getElementById('total');
        
        if (subtotalElement && discountElement && taxElement && totalElement) {
            let subtotal = 0;
            
            cartItems.forEach(item => {
                subtotal += item.price * item.quantity;
            });
            
            // Calculate discount (example: 10% if subtotal > $100)
            let discount = 0;
            if (subtotal > 100) {
                discount = subtotal * 0.1;
            }
            
            // Calculate tax (example: 8%)
            const tax = (subtotal - discount) * 0.08;
            
            // Calculate total
            const total = subtotal - discount + tax;
            
            // Update display
            subtotalElement.textContent = `$${subtotal.toFixed(2)}`;
            discountElement.textContent = `-$${discount.toFixed(2)}`;
            taxElement.textContent = `$${tax.toFixed(2)}`;
            totalElement.textContent = `$${total.toFixed(2)}`;
        }
    }
    
    // Initialize cart on page load
    if (window.location.pathname.includes('cart.html')) {
        renderCart();
    }
    
    // Promo code functionality
    const applyPromoButton = document.getElementById('apply-promo');
    
    if (applyPromoButton) {
        applyPromoButton.addEventListener('click', function() {
            const promoCodeInput = document.getElementById('promo-code');
            const promoCode = promoCodeInput.value.trim().toUpperCase();
            
            // Example promo codes
            const promoCodes = {
                'ECO25': 0.25, // 25% off
                'GREEN10': 0.10, // 10% off
                'EARTH5': 0.05 // 5% off
            };
            
            if (promoCodes[promoCode]) {
                const discount = promoCodes[promoCode];
                let subtotal = 0;
                
                cartItems.forEach(item => {
                    subtotal += item.price * item.quantity;
                });
                
                const discountAmount = subtotal * discount;
                
                // Update discount display
                const discountElement = document.getElementById('discount');
                if (discountElement) {
                    discountElement.textContent = `-$${discountAmount.toFixed(2)}`;
                }
                
                // Recalculate total
                updateCartSummary();
                
                alert(`Promo code ${promoCode} applied successfully!`);
            } else {
                alert('Invalid promo code. Please try again.');
            }
        });
    }
    
    // Checkout button functionality
    const checkoutButton = document.getElementById('checkout-btn');
    
    if (checkoutButton) {
        checkoutButton.addEventListener('click', function() {
            if (cartItems.length === 0) {
                alert('Your cart is empty. Please add items to your cart before checkout.');
                return;
            }
            
            // Here you would typically redirect to a checkout page
            alert('Proceeding to checkout...');
            // window.location.href = 'checkout.html';
        });
    }

    // Cloud waste management dashboard simulation
    const dashboardLink = document.querySelector('a[href="#dashboard"]');
    
    if (dashboardLink) {
        dashboardLink.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Check if user is logged in (in a real app, this would be a proper auth check)
            const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';
            
            if (isLoggedIn) {
                // Redirect to dashboard (for demonstration purposes)
                alert('Redirecting to waste management dashboard...');
                // window.location.href = 'dashboard.html';
            } else {
                // Show login modal
                loginModal.classList.add('active');
                document.body.style.overflow = 'hidden';
                alert('Please log in to access your waste management dashboard.');
            }
        });
    }
});