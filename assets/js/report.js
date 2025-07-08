let productsCurrentPage = 1;
let materialsCurrentPage = 1;
let productsTotalPages = 1;
let materialsTotalPages = 1;

async function loadReport(startDate, endDate, productsPage = 1, materialsPage = 1) {
    try {
        const apiUrl = `/4523WebProjectGroup08/api/get_report.php?start_date=${startDate}&end_date=${endDate}&products_page=${productsPage}&materials_page=${materialsPage}`;
        const response = await fetch(apiUrl, {
            method: 'GET',
            cache: 'no-store',
            headers: { 'Accept': 'application/json' }
        });
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        const data = await response.json();
        if (data.result !== 'success') {
            throw new Error(data.message || 'Failed to load report');
        }

        // 更新總計
        document.getElementById('total-orders').textContent = data.total_orders || 0;
        document.getElementById('total-sales-amount').textContent = `$${Number(data.total_sales_amount || 0).toFixed(2)}`;

        // 更新產品表格
        const productsBody = document.getElementById('products-body');
        productsBody.innerHTML = '';
        if (data.products.length === 0) {
            productsBody.innerHTML = '<tr><td colspan="5" data-i18n="no_products_found">No products found.</td></tr>';
        } else {
            data.products.forEach(product => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${product.pid}</td>
                    <td>${product.pname}</td>
                    <td><img src="${product.pimage || '/4523WebProjectGroup08/assets/images/placeholder.png'}" alt="Product" class="product-image"></td>
                    <td>${product.total_qty}</td>
                    <td>$${Number(product.total_amount).toFixed(2)}</td>
                `;
                productsBody.appendChild(row);
            });
        }
        productsTotalPages = data.products_total_pages;
        productsCurrentPage = data.products_current_page;
        updatePagination('products');

        // 更新材料表格
        const materialsBody = document.getElementById('materials-body');
        materialsBody.innerHTML = '';
        if (data.materials.length === 0) {
            materialsBody.innerHTML = '<tr><td colspan="4" data-i18n="no_materials_found">No materials found.</td></tr>';
        } else {
            data.materials.forEach(material => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${material.mid}</td>
                    <td>${material.mname}</td>
                    <td>${material.munit}</td>
                    <td>${material.total_material}</td>
                `;
                materialsBody.appendChild(row);
            });
        }
        materialsTotalPages = data.materials_total_pages;
        materialsCurrentPage = data.materials_current_page;
        updatePagination('materials');

        updateTranslations();
    } catch (error) {
        console.error('Failed to load report:', error);
        alert('Failed to load report: ' + error.message);
    }
}

function updatePagination(type) {
    const prefix = type === 'products' ? 'products' : 'materials';
    const currentPage = type === 'products' ? productsCurrentPage : materialsCurrentPage;
    const totalPages = type === 'products' ? productsTotalPages : materialsTotalPages;

    const pageInfo = document.getElementById(`${prefix}-page-info`);
    const start = (currentPage - 1) * 25 + 1;
    const end = Math.min(currentPage * 25, start + 24);
    pageInfo.textContent = `${start}-${end}`;

    const firstPage = document.getElementById(`${prefix}-first-page`);
    const prevPage = document.getElementById(`${prefix}-prev-page`);
    const nextPage = document.getElementById(`${prefix}-next-page`);
    const lastPage = document.getElementById(`${prefix}-last-page`);

    firstPage.classList.toggle('disabled', currentPage === 1);
    prevPage.classList.toggle('disabled', currentPage === 1);
    nextPage.classList.toggle('disabled', currentPage === totalPages);
    lastPage.classList.toggle('disabled', currentPage === totalPages);

    firstPage.onclick = currentPage === 1 ? null : () => loadReport(
        document.getElementById('start-date').value,
        document.getElementById('end-date').value,
        type === 'products' ? 1 : productsCurrentPage,
        type === 'materials' ? 1 : materialsCurrentPage
    );
    prevPage.onclick = currentPage === 1 ? null : () => loadReport(
        document.getElementById('start-date').value,
        document.getElementById('end-date').value,
        type === 'products' ? currentPage - 1 : productsCurrentPage,
        type === 'materials' ? currentPage - 1 : materialsCurrentPage
    );
    nextPage.onclick = currentPage === totalPages ? null : () => loadReport(
        document.getElementById('start-date').value,
        document.getElementById('end-date').value,
        type === 'products' ? currentPage + 1 : productsCurrentPage,
        type === 'materials' ? currentPage + 1 : materialsCurrentPage
    );
    lastPage.onclick = currentPage === totalPages ? null : () => loadReport(
        document.getElementById('start-date').value,
        document.getElementById('end-date').value,
        type === 'products' ? totalPages : productsCurrentPage,
        type === 'materials' ? totalPages : materialsCurrentPage
    );
}

document.addEventListener('DOMContentLoaded', () => {
    const startDate = new Date();
    startDate.setDate(1);
    const endDate = new Date();
    endDate.setDate(new Date(endDate.getFullYear(), endDate.getMonth() + 1, 0).getDate());

    document.getElementById('start-date').value = startDate.toISOString().split('T')[0];
    document.getElementById('end-date').value = endDate.toISOString().split('T')[0];

    document.getElementById('filter-button').addEventListener('click', () => {
        const startDate = document.getElementById('start-date').value;
        const endDate = document.getElementById('end-date').value;
        if (startDate && endDate) {
            loadReport(startDate, endDate);
        } else {
            alert(window.translations[getLanguage()].error_invalid_date || 'Please select a valid date range.');
        }
    });

    loadReport(startDate.toISOString().split('T')[0], endDate.toISOString().split('T')[0]);
});