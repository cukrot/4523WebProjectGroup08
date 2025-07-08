let currentPage = 1;
let totalPages = 1;

async function loadProducts(search = '', page = 1) {
    try {
        const apiUrl = '/4523WebProjectGroup08/api/get_products.php';
        const response = await fetch(`${apiUrl}?search=${encodeURIComponent(search)}&page=${page}`);
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}, URL: ${apiUrl}`);
        }
        const data = await response.json();
        if (data.result !== 'success') {
            throw new Error(data.message || 'Failed to load products');
        }

        const products = data.products;
        totalPages = data.total_pages;
        currentPage = data.current_page;

        const tbody = document.getElementById('products-body');
        tbody.innerHTML = '';
        if (products.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" data-i18n="no_products_found">No products found.</td></tr>';
        } else {
            products.forEach(product => {
                const row = document.createElement('tr');
                row.className = 'table-row';
                row.innerHTML = `
                    <td>${product.pid}</td>
                    <td>${product.pname}</td>
                    <td class="description-column" title="${product.pdesc}">${product.pdesc}</td>
                    <td><img src="${product.pimage || '/4523WebProjectGroup08/assets/images/placeholder.png'}" alt="Product" class="product-image"></td>
                    <td>$${product.pcost}</td>
                    <td><button class="details-button" onclick="window.location.href='/4523WebProjectGroup08/pages/staff/edit_product.html?pid=${product.pid}'" data-i18n="edit">Edit</button></td>
                `;
                tbody.appendChild(row);
            });
        }

        updatePagination();
        updateTranslations();
    } catch (error) {
        console.error('Failed to load products:', error);
        alert('Failed to load products: ' + error.message);
        document.getElementById('products-body').innerHTML = '<tr><td colspan="6">Error loading products. Please check the server or API path.</td></tr>';
    }
}

function updatePagination() {
    const pageInfo = document.getElementById('page-info');
    const start = (currentPage - 1) * 25 + 1;
    const end = Math.min(currentPage * 25, start + 24);
    pageInfo.textContent = `${start}-${end}`;

    const firstPage = document.getElementById('first-page');
    const prevPage = document.getElementById('prev-page');
    const nextPage = document.getElementById('next-page');
    const lastPage = document.getElementById('last-page');

    firstPage.classList.toggle('disabled', currentPage === 1);
    prevPage.classList.toggle('disabled', currentPage === 1);
    nextPage.classList.toggle('disabled', currentPage === totalPages);
    lastPage.classList.toggle('disabled', currentPage === totalPages);

    firstPage.onclick = currentPage === 1 ? null : () => loadProducts(document.getElementById('search-input').value, 1);
    prevPage.onclick = currentPage === 1 ? null : () => loadProducts(document.getElementById('search-input').value, currentPage - 1);
    nextPage.onclick = currentPage === totalPages ? null : () => loadProducts(document.getElementById('search-input').value, currentPage + 1);
    lastPage.onclick = currentPage === totalPages ? null : () => loadProducts(document.getElementById('search-input').value, totalPages);
}

document.addEventListener('DOMContentLoaded', () => {
    const searchButton = document.getElementById('search-button');
    const searchInput = document.getElementById('search-input');

    if (searchButton) {
        searchButton.addEventListener('click', () => {
            const search = searchInput.value;
            loadProducts(search, 1);
        });
    }

    if (searchInput) {
        searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                const search = searchInput.value;
                loadProducts(search, 1);
            }
        });
    }

    loadProducts();
});