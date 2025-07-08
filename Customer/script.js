function initTopBanner() {
    const closeBannerBtn = document.querySelector('.close-banner');
    if (closeBannerBtn) {
        closeBannerBtn.addEventListener('click', () => {
            const topBanner = document.querySelector('.top-banner');
            if (topBanner) {
                topBanner.style.display = 'none';
            }
        });
    }
}

function initReviewsCarousel() {
    // Select the required elements
    const reviews = document.querySelectorAll('.review');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    const paginationDots = document.querySelector('.pagination-dots');

    // Check if all necessary elements exist
    if (reviews.length === 0 || !prevBtn || !nextBtn || !paginationDots) {
        console.log('Reviews carousel elements not found. Skipping initialization.');
        return; // Exit the function if elements are missing
    }

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

    // Function to show a specific review
    function showReview(index) {
        reviews.forEach((review, i) => {
            review.classList.remove('active');
            dots[i].classList.remove('active');
        });
        reviews[index].classList.add('active');
        dots[index].classList.add('active');
        currentReview = index;
    }

    // Autoplay functionality
    function startAutoplay() {
        autoplayInterval = setInterval(() => {
            currentReview = (currentReview + 1) % reviews.length;
            showReview(currentReview);
        }, 3000); // Change review every 3 seconds
    }

    function stopAutoplay() {
        clearInterval(autoplayInterval);
    }

    // Add event listeners only if elements exist
    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            stopAutoplay();
            currentReview = (currentReview - 1 + reviews.length) % reviews.length;
            showReview(currentReview);
            startAutoplay();
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            stopAutoplay();
            currentReview = (currentReview + 1) % reviews.length;
            showReview(currentReview);
            startAutoplay();
        });
    }

    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            stopAutoplay();
            showReview(index);
            startAutoplay();
        });
    });

    // Initialize the carousel
    showReview(currentReview);
    startAutoplay();
}

function initNewsletter() {
    const subscribeBtn = document.querySelector('.newsletter-form button');
    if (subscribeBtn) {
        subscribeBtn.addEventListener('click', () => {
            const emailInput = document.querySelector('.newsletter-form input');
            if (emailInput) {
                const email = emailInput.value.trim();
                if (email === '') {
                    alert('Please enter your email address.');
                } else if (!email.includes('@') || !email.includes('.')) {
                    alert('Please enter a valid email address.');
                } else {
                    alert('Thank you for subscribing to our newsletter!');
                    emailInput.value = '';
                }
            }
        });
    }
}

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

function initAddToCart() {
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('add-to-cart')) {
            const pid = e.target.getAttribute('data-id');
            console.log('Add to Cart clicked, pid:', pid);
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `pid=${pid}`
            })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success) {
                        alert('Product has been added to the shopping cart');
                    } else {
                        alert('Error: ' + (data.message || 'Unable to add to the shopping cart'));
                    }
                })
                .catch(error => {
                    console.error('Request failed:', error);
                    alert('Unable to add to shopping cart, please try again later');
                });
        }
    });
}


document.addEventListener('DOMContentLoaded', function () {
const page = document.body.getAttribute('data-page');

if (page === 'home') {
initTopBanner();
initReviewsCarousel();
initNewsletter();
initProductHover();
initAddToCart();
} else if (page === 'product-detail') {
initAddToCart();
// Other functions specific to product detail pages
} else if (page === 'search') {
initAddToCart();
// Other functions specific to search pages
}
});