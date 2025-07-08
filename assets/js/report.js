let productsCurrentPage = 1;
let materialsCurrentPage = 1;
let productsTotalPages = 1;
let materialsTotalPages = 1;

async function loadReport(startDate, endDate, productsSearch = '', materialsSearch = '', productsPage = 1, materialsPage = 1) {
    try {
        const apiUrl = `/4523WebProjectGroup08/api/get_report.php?start_date=${startDate}&end_date=${endDate}&products_search=${encodeURIComponent(productsSearch)}&materials_search=${encodeURIComponent(materialsSearch)}&products_page=${productsPage}&materials_page=${materialsPage}`;
        console.log('Fetching report:', apiUrl); // 調試：確認請求 URL
        const response = await fetch(apiUrl, {
            method: 'GET',
            cache: 'no-store',
            headers: { 'Accept': 'application/json' }
        });
        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`HTTP error! Status: ${response.status}, Response: ${errorText}`);
        }
        const data = await response.json();
        console.log('Report API response:', data); // 調試：檢查響應數據

        if (data.result !== 'success') {
            throw new Error(data.errors.join(', ') || 'Failed to load report');
        }

        // 更新總計
        const totalOrders = document.getElementById('total-orders');
        const totalSalesAmount = document.getElementById('total-sales-amount');
        if (totalOrders) totalOrders.textContent = data.total_orders || 0;
        if (totalSalesAmount) totalSalesAmount.textContent = `$${Number(data.total_sales_amount || 0).toFixed(2)}`;

        // 更新產品表格
        const productsBody = document.getElementById('products-body');
        if (productsBody) {
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
            productsTotalPages = data.products_total_pages || 1;
            productsCurrentPage = data.products_current_page || 1;
            updatePagination('products', productsSearch, materialsSearch);
        }

        // 更新材料表格
        const materialsBody = document.getElementById('materials-body');
        if (materialsBody) {
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
            materialsTotalPages = data.materials_total_pages || 1;
            materialsCurrentPage = data.materials_current_page || 1;
            updatePagination('materials', productsSearch, materialsSearch);
        }

        updateTranslations();
    } catch (error) {
        console.error('Failed to load report:', error);
        alert('Failed to load report: ' + error.message);
    }
}

function updatePagination(type, productsSearch, materialsSearch) {
    const prefix = type === 'products' ? 'products' : 'materials';
    const currentPage = type === 'products' ? productsCurrentPage : materialsCurrentPage;
    const totalPages = type === 'products' ? productsTotalPages : materialsTotalPages;

    const pageInfo = document.getElementById(`${prefix}-page-info`);
    if (pageInfo) {
        const start = (currentPage - 1) * 25 + 1;
        const end = Math.min(currentPage * 25, start + 24);
        pageInfo.textContent = `${start}-${end}`;
    }

    const firstPage = document.getElementById(`${prefix}-first-page`);
    const prevPage = document.getElementById(`${prefix}-prev-page`);
    const nextPage = document.getElementById(`${prefix}-next-page`);
    const lastPage = document.getElementById(`${prefix}-last-page`);

    if (firstPage) firstPage.classList.toggle('disabled', currentPage === 1);
    if (prevPage) prevPage.classList.toggle('disabled', currentPage === 1);
    if (nextPage) nextPage.classList.toggle('disabled', currentPage === totalPages);
    if (lastPage) lastPage.classList.toggle('disabled', currentPage === totalPages);

    if (firstPage) firstPage.onclick = currentPage === 1 ? null : () => loadReport(
        document.getElementById('start-date').value,
        document.getElementById('end-date').value,
        productsSearch,
        materialsSearch,
        type === 'products' ? 1 : productsCurrentPage,
        type === 'materials' ? 1 : materialsCurrentPage
    );
    if (prevPage) prevPage.onclick = currentPage === 1 ? null : () => loadReport(
        document.getElementById('start-date').value,
        document.getElementById('end-date').value,
        productsSearch,
        materialsSearch,
        type === 'products' ? currentPage - 1 : productsCurrentPage,
        type === 'materials' ? currentPage - 1 : materialsCurrentPage
    );
    if (nextPage) nextPage.onclick = currentPage === totalPages ? null : () => loadReport(
        document.getElementById('start-date').value,
        document.getElementById('end-date').value,
        productsSearch,
        materialsSearch,
        type === 'products' ? currentPage + 1 : productsCurrentPage,
        type === 'materials' ? currentPage + 1 : materialsCurrentPage
    );
    if (lastPage) lastPage.onclick = currentPage === totalPages ? null : () => loadReport(
        document.getElementById('start-date').value,
        document.getElementById('end-date').value,
        productsSearch,
        materialsSearch,
        type === 'products' ? totalPages : productsCurrentPage,
        type === 'materials' ? totalPages : materialsCurrentPage
    );
}

document.addEventListener('DOMContentLoaded', () => {
    console.log('report.js DOMContentLoaded triggered'); // 調試：確認事件觸發
    const startDate = new Date();
    startDate.setDate(1);
    const endDate = new Date();
    endDate.setDate(new Date(endDate.getFullYear(), endDate.getMonth() + 1, 0).getDate());

    const startDateInput = document.getElementById('start-date');
    const endDateInput = document.getElementById('end-date');
    const productsSearchButton = document.getElementById('products-search-button');
    const productsSearchInput = document.getElementById('products-search-input');
    const materialsSearchButton = document.getElementById('materials-search-button');
    const materialsSearchInput = document.getElementById('materials-search-input');
    const filterButton = document.getElementById('filter-button');

    if (startDateInput && endDateInput) {
        startDateInput.value = startDate.toISOString().split('T')[0];
        endDateInput.value = endDate.toISOString().split('T')[0];
    } else {
        console.error('Date inputs not found');
    }

    if (productsSearchButton) {
        productsSearchButton.addEventListener('click', () => {
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;
            const productsSearch = productsSearchInput.value;
            const materialsSearch = materialsSearchInput.value;
            if (startDate && endDate) {
                loadReport(startDate, endDate, productsSearch, materialsSearch);
            } else {
                alert(window.translations[getLanguage()].error_invalid_date || 'Please select a valid date range.');
            }
        });
    } else {
        console.error('Products search button not found');
    }

    if (productsSearchInput) {
        productsSearchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                const startDate = startDateInput.value;
                const endDate = endDateInput.value;
                const productsSearch = productsSearchInput.value;
                const materialsSearch = materialsSearchInput.value;
                if (startDate && endDate) {
                    loadReport(startDate, endDate, productsSearch, materialsSearch);
                } else {
                    alert(window.translations[getLanguage()].error_invalid_date || 'Please select a valid date range.');
                }
            }
        });
    } else {
        console.error('Products search input not found');
    }

    if (materialsSearchButton) {
        materialsSearchButton.addEventListener('click', () => {
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;
            const productsSearch = productsSearchInput.value;
            const materialsSearch = materialsSearchInput.value;
            if (startDate && endDate) {
                loadReport(startDate, endDate, productsSearch, materialsSearch);
            } else {
                alert(window.translations[getLanguage()].error_invalid_date || 'Please select a valid date range.');
            }
        });
    } else {
        console.error('Materials search button not found');
    }

    if (materialsSearchInput) {
        materialsSearchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                const startDate = startDateInput.value;
                const endDate = endDateInput.value;
                const productsSearch = productsSearchInput.value;
                const materialsSearch = materialsSearchInput.value;
                if (startDate && endDate) {
                    loadReport(startDate, endDate, productsSearch, materialsSearch);
                } else {
                    alert(window.translations[getLanguage()].error_invalid_date || 'Please select a valid date range.');
                }
            }
        });
    } else {
        console.error('Materials search input not found');
    }

    if (filterButton) {
        filterButton.addEventListener('click', () => {
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;
            const productsSearch = productsSearchInput.value;
            const materialsSearch = materialsSearchInput.value;
            if (startDate && endDate) {
                loadReport(startDate, endDate, productsSearch, materialsSearch);
            } else {
                alert(window.translations[getLanguage()].error_invalid_date || 'Please select a valid date range.');
            }
        });
    } else {
        console.error('Filter button not found');
    }

    loadReport(startDate.toISOString().split('T')[0], endDate.toISOString().split('T')[0]);
});