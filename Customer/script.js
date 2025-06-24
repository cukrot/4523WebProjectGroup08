// Top Banner Functionality
function initTopBanner() {
    const closeBannerBtn = document.querySelector('.close-banner');
    closeBannerBtn.addEventListener('click', () => {
        document.querySelector('.top-banner').style.display = 'none';
    });
}

// Customer Reviews Carousel
function initReviewsCarousel() {
    const reviews = document.querySelectorAll('.review');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    const paginationDots = document.querySelector('.pagination-dots');
    let currentReview = 0;
    let autoplayInterval = null;

    // Create pagination dots
    reviews.forEach((_, index) => {
        const dot = document.createElement('div');
        dot.classList.add('dot');
        if (index === 0) dot.classList.add('active');
        paginationDots.appendChild(dot);
    });

    const dots = document.querySelectorAll('.dot');

    // Show the current review
    function showReview(index) {
        reviews.forEach((review, i) => {
            review.classList.remove('active');
            dots[i].classList.remove('active');
        });
        reviews[index].classList.add('active');
        dots[index].classList.add('active');
        currentReview = index;
    }

    // Start autoplay
    function startAutoplay() {
        autoplayInterval = setInterval(() => {
            currentReview = (currentReview + 1) % reviews.length;
            showReview(currentReview);
        }, 3000); // 3 seconds
    }

    // Stop autoplay
    function stopAutoplay() {
        clearInterval(autoplayInterval);
    }

    // Previous button
    prevBtn.addEventListener('click', () => {
        stopAutoplay();
        currentReview = (currentReview - 1 + reviews.length) % reviews.length;
        showReview(currentReview);
        startAutoplay(); // Resume autoplay after interaction
    });

    // Next button
    nextBtn.addEventListener('click', () => {
        stopAutoplay();
        currentReview = (currentReview + 1) % reviews.length;
        showReview(currentReview);
        startAutoplay(); // Resume autoplay after interaction
    });

    // Pagination dots
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            stopAutoplay();
            showReview(index);
            startAutoplay(); // Resume autoplay after interaction
        });
    });

    // Show the first review initially
    showReview(currentReview);

    // Start autoplay when the page loads
    startAutoplay();
}

// Newsletter Subscription
function initNewsletter() {
    const subscribeBtn = document.querySelector('.newsletter-form button');
    subscribeBtn.addEventListener('click', () => {
        const emailInput = document.querySelector('.newsletter-form input');
        const email = emailInput.value.trim();

        if (email === '') {
            alert('Please enter your email address.');
        } else if (!email.includes('@') || !email.includes('.')) {
            alert('Please enter a valid email address.');
        } else {
            alert('Thank you for subscribing to our newsletter!');
            emailInput.value = '';
        }
    });
}

// Product Hover Effects
function initProductHover() {
    const products = document.querySelectorAll('.product');
    products.forEach(product => {
        product.addEventListener('mouseover', () => {
            product.style.transform = 'scale(1.05)';
            product.style.transition = 'transform 0.3s ease';
        });
        product.addEventListener('mouseout', () => {
            product.style.transform = 'scale(1)';
        });
    });
}

// Add to Cart Functionality
function initAddToCart() {
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', () => {
            const id = parseInt(button.getAttribute('data-id'));
            const name = button.getAttribute('data-name');
            const price = parseFloat(button.getAttribute('data-price'));

            // Load existing cart from localStorage
            let cartItems = JSON.parse(localStorage.getItem('cart')) || [];

            // Check if item already exists in cart
            const existingItem = cartItems.find(item => item.id === id);
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cartItems.push({ id, name, price, quantity: 1 });
            }

            // Save updated cart to localStorage
            localStorage.setItem('cart', JSON.stringify(cartItems));

            alert(`${name} has been added to your cart!`);
        });
    });
}

// Update the init function to include add-to-cart functionality
function init() {
    initTopBanner();
    initReviewsCarousel();
    initNewsletter();
    initProductHover();
    initAddToCart(); // Add this line
}

// Run the initialization
init();