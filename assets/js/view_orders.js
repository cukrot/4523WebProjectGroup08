let currentPage = 1;
let totalPages = 1;

async function loadOrders(search = '', page = 1) {
    try {
        const apiUrl = '/4523WebProjectGroup08/api/get_orders.php';
        const response = await fetch(`${apiUrl}?search=${encodeURIComponent(search)}&page=${page}`);
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}, URL: ${apiUrl}`);
        }
        const data = await response.json();
        if (data.result !== 'success') {
            throw new Error(data.message || 'Failed to load orders');
        }

        const orders = data.orders;
        totalPages = data.total_pages;
        currentPage = data.current_page;

        const tbody = document.getElementById('orders-body');
        tbody.innerHTML = '';
        if (orders.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5">No orders found.</td></tr>';
        } else {
            orders.forEach(order => {
                const row = document.createElement('tr');
                row.className = 'table-row';
                row.innerHTML = `
                    <td>${order.oid}</td>
                    <td>${order.odate}</td>
                    <td>${order.odeliverdate || '-'}</td>
                    <td>${order.ostatus}</td>
                    <td><button class="details-button" onclick="window.location.href='order_detail.html?oid=${order.oid}'" data-i18n="details">Details</button></td>
                `;
                tbody.appendChild(row);
            });
        }

        updatePagination();
        updateTranslations();
    } catch (error) {
        console.error('Failed to load orders:', error);
        alert('Failed to load orders: ' + error.message);
        document.getElementById('orders-body').innerHTML = '<tr><td colspan="5">Error loading orders. Please check the server or API path.</td></tr>';
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

    firstPage.onclick = currentPage === 1 ? null : () => loadOrders(document.getElementById('search-input').value, 1);
    prevPage.onclick = currentPage === 1 ? null : () => loadOrders(document.getElementById('search-input').value, currentPage - 1);
    nextPage.onclick = currentPage === totalPages ? null : () => loadOrders(document.getElementById('search-input').value, currentPage + 1);
    lastPage.onclick = currentPage === totalPages ? null : () => loadOrders(document.getElementById('search-input').value, totalPages);
}

document.addEventListener('DOMContentLoaded', () => {
    const searchButton = document.getElementById('search-button');
    const searchInput = document.getElementById('search-input');

    if (searchButton) {
        searchButton.addEventListener('click', () => {
            const search = searchInput.value;
            loadOrders(search, 1);
        });
    }

    if (searchInput) {
        searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                const search = searchInput.value;
                loadOrders(search, 1);
            }
        });
    }

    loadOrders();
});