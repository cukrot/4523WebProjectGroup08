async function loadOrderlineDetail() {
    const urlParams = new URLSearchParams(window.location.search);
    const oid = urlParams.get('oid');
    const pid = urlParams.get('pid');

    if (!oid || isNaN(oid) || oid <= 0 || !pid || isNaN(pid) || pid <= 0) {
        alert(window.translations[getLanguage()].error_invalid_order || 'Invalid or missing order ID or product ID.');
        document.getElementById('order-info').innerHTML = '<p>No order or product selected.</p>';
        return;
    }

    try {
        const response = await fetch(`/4523WebProjectGroup08/api/get_orderline_detail.php?oid=${oid}&pid=${pid}`);
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        const data = await response.json();
        if (data.result !== 'success') {
            throw new Error(data.errors.join(', ') || 'Failed to load orderline details.');
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
            <div class="info-item"><span class="info-label" data-i18n="total_amount">Total Amount</span><span class="info-value">$${order.ocost}</span></div>
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

        // 訂購項目（單行）
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
            `;
            orderedItemsBody.appendChild(row);
        });

        // 使用材料
        const materialsBody = document.getElementById('materials-body');
        materialsBody.innerHTML = '';
        materials.forEach(material => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${material.mid}</td>
                <td>${material.mname}</td>
                <td>${material.munit}</td>
                <td>${material.pmqty}</td>
            `;
            materialsBody.appendChild(row);
        });

        // 材料數量輸入
        const materialInputs = document.getElementById('material-inputs');
        materialInputs.innerHTML = '';
        materials.forEach(material => {
            const div = document.createElement('div');
            div.className = 'form-group';
            div.innerHTML = `
                <label class="form-label" for="material-${material.mid}">${material.mname} (ID: ${material.mid})</label>
                <input type="number" id="material-${material.mid}" name="material-${material.mid}" class="form-input" min="1" value="${material.pmqty}">
            `;
            materialInputs.appendChild(div);
        });

        // 更新數量表單預設值
        const oqtyInput = document.getElementById('oqty');
        oqtyInput.value = order.oqty;

        updateTranslations();
    } catch (error) {
        console.error('Failed to load orderline details:', error);
        alert('Failed to load orderline details: ' + error.message);
        document.getElementById('order-info').innerHTML = '<p>Error loading orderline details.</p>';
    }
}

document.getElementById('modify-quantity-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const lang = getLanguage();
    const urlParams = new URLSearchParams(window.location.search);
    const oid = urlParams.get('oid');
    const pid = urlParams.get('pid');
    const formData = new FormData();
    formData.append('oid', oid);
    formData.append('pid', pid);
    formData.append('oqty', document.getElementById('oqty').value);

    try {
        const response = await fetch('/4523WebProjectGroup08/api/update_orderline.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if (result.result === 'success') {
            alert(window.translations[lang].order_update_success);
            loadOrderlineDetail();
        } else {
            alert('Errors: ' + result.errors.join(', '));
        }
    } catch (error) {
        alert('An error occurred: ' + error.message);
    }
});

document.getElementById('update-material-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const lang = getLanguage();
    const urlParams = new URLSearchParams(window.location.search);
    const oid = urlParams.get('oid');
    const pid = urlParams.get('pid');
    const materials = [];
    document.querySelectorAll('#material-inputs input').forEach(input => {
        const mid = input.id.replace('material-', '');
        const pmqty = input.value;
        if (pmqty) materials.push({ mid, pmqty });
    });

    const formData = new FormData();
    formData.append('oid', oid);
    formData.append('pid', pid);
    formData.append('materials', JSON.stringify(materials));

    try {
        const response = await fetch('/4523WebProjectGroup08/api/update_orderline.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if (result.result === 'success') {
            alert(window.translations[lang].order_update_success);
            loadOrderlineDetail();
        } else {
            alert('Errors: ' + result.errors.join(', '));
        }
    } catch (error) {
        alert('An error occurred: ' + error.message);
    }
});

// 初始化
document.addEventListener('DOMContentLoaded', loadOrderlineDetail);