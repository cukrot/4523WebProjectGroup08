async function loadOrderDetail() {
    const urlParams = new URLSearchParams(window.location.search);
    const oid = urlParams.get('oid');

    if (!oid || isNaN(oid) || oid <= 0) {
        alert(window.translations[getLanguage()].error_invalid_order || 'Invalid or missing order ID.');
        document.getElementById('order-info').innerHTML = '<p>No order selected.</p>';
        return;
    }

    try {
        const response = await fetch(`/4523WebProjectGroup08/api/get_order_detail.php?oid=${oid}`);
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        const data = await response.json();
        if (data.result !== 'success') {
            throw new Error(data.errors.join(', ') || 'Failed to load order details.');
        }

        const order = data.order;
        const orderedItems = data.ordered_items;
        const materials = data.materials;

        // 訂單信息
        const orderInfo = document.getElementById('order-info');
        orderInfo.innerHTML = `
            <div class="info-item"><span class="info-label" data-i18n="order_id">Order ID</span><span class="info-value">${order.oid}</span></div>
            <div class="info-item"><span class="info-label" data-i18n="order_date">Order Date</span><span class="info-value">${order.odate}</span></div>
            <div class="info-item"><span class="info-label" data-i18n="order_status">Order Status</span><span class="info-value">${order.ostatus}</span></div>
            <div class="info-item"><span class="info-label" data-i18n="total_amount">Total Amount</span><span class="info-value">$${order.total_cost}</span></div>
        `;

        // 客戶信息
        const customerInfo = document.getElementById('customer-info');
        customerInfo.innerHTML = `
            <div class="info-item"><span class="info-label" data-i18n="contact_name">Contact Name</span><span class="info-value">${order.cname}</span></div>
            <div class="info-item"><span class="info-label" data-i18n="contact_number">Contact Number</span><span class="info-value">${order.ctel}</span></div>
        `;

        // 送貨詳情
        const deliveryInfo = document.getElementById('delivery-info');
        deliveryInfo.innerHTML = `
            <div class="info-item"><span class="info-label" data-i18n="delivery_date">Delivery Date</span><span class="info-value">${order.odeliverdate || '-'}</span></div>
            <div class="info-item"><span class="info-label" data-i18n="delivery_address">Delivery Address</span><span class="info-value">${order.caddr}</span></div>
        `;

        // 訂購項目
        const orderedItemsBody = document.getElementById('ordered-items-body');
        orderedItemsBody.innerHTML = '';
        orderedItems.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><img src="${item.pimage || 'placeholder.png'}" alt="Product" style="width: 50px; height: 50px;"></td>
                <td>${item.pid}</td>
                <td>${item.pname}</td>
                <td>${item.oqty}</td>
                <td>$${item.ocost}</td>
                <td><button class="details-button" onclick="window.location.href='orderline_detail.html?oid=${oid}&pid=${item.pid}'" data-i18n="material_details">Material Details</button></td>
            `;
            orderedItemsBody.appendChild(row);
        });

        // 總使用材料
        const materialsBody = document.getElementById('materials-body');
        materialsBody.innerHTML = '';
        materials.forEach(material => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${material.mid}</td>
                <td>${material.mname}</td>
                <td>${material.munit}</td>
                <td>${material.total_pmqty}</td>
            `;
            materialsBody.appendChild(row);
        });

        // 更新送貨日期表單預設值
        const odeliverdateInput = document.getElementById('odeliverdate');
        if (order.odeliverdate) {
            odeliverdateInput.value = order.odeliverdate.replace(' ', 'T').substring(0, 16);
        }

        updateTranslations();
    } catch (error) {
        console.error('Failed to load order details:', error);
        alert('Failed to load order details: ' + error.message);
        document.getElementById('order-info').innerHTML = '<p>Error loading order details.</p>';
    }
}

document.getElementById('update-status-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const lang = getLanguage();
    const oid = new URLSearchParams(window.location.search).get('oid');
    const formData = new FormData();
    formData.append('oid', oid);
    formData.append('ostatus', document.getElementById('ostatus').value);

    try {
        const response = await fetch('/4523WebProjectGroup08/api/update_order.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if (result.result === 'success') {
            alert(window.translations[lang].order_update_success);
            loadOrderDetail();
        } else {
            alert('Errors: ' + result.errors.join(', '));
        }
    } catch (error) {
        alert('An error occurred: ' + error.message);
    }
});

document.getElementById('modify-delivery-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const lang = getLanguage();
    const oid = new URLSearchParams(window.location.search).get('oid');
    const formData = new FormData();
    formData.append('oid', oid);
    formData.append('odeliverdate', document.getElementById('odeliverdate').value);

    try {
        const response = await fetch('/4523WebProjectGroup08/api/update_order.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if (result.result === 'success') {
            alert(window.translations[lang].order_update_success);
            loadOrderDetail();
        } else {
            alert('Errors: ' + result.errors.join(', '));
        }
    } catch (error) {
        alert('An error occurred: ' + error.message);
    }
});

// 初始化
document.addEventListener('DOMContentLoaded', loadOrderDetail);