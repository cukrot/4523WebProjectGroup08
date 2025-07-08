let materials = [];

async function loadProduct() {
    console.log('Starting loadProduct()'); // 調試：確認函數執行
    const urlParams = new URLSearchParams(window.location.search);
    const pid = urlParams.get('pid');
    const lang = getLanguage();

    if (!pid || isNaN(pid) || pid <= 0) {
        console.error('Invalid product ID:', pid);
        alert(window.translations[lang].error_invalid_product || 'Invalid or missing product ID.');
        const form = document.getElementById('product-form');
        if (form) {
            form.insertAdjacentHTML('beforebegin', '<p data-i18n="error_invalid_product">No product selected.</p>');
        }
        return;
    }

    try {
        console.log('Fetching product details for pid:', pid); // 調試：確認請求發起
        const response = await fetch(`/4523WebProjectGroup08/api/get_product_detail.php?pid=${pid}`, {
            method: 'GET',
            cache: 'no-store',
            headers: {
                'Accept': 'application/json'
            }
        });
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        const data = await response.json();
        console.log('Product API response:', data); // 調試：檢查響應數據

        if (data.result !== 'success') {
            throw new Error(data.errors.join(', ') || 'Failed to load product details.');
        }

        const product = data.product;
        const pidInput = document.getElementById('pid');
        const pnameInput = document.getElementById('pname');
        const pdescInput = document.getElementById('pdesc');
        const pcostInput = document.getElementById('pcost');
        const imagePreview = document.getElementById('image-preview');

        if (pidInput) pidInput.value = product.pid || '';
        if (pnameInput) pnameInput.value = product.pname || '';
        if (pdescInput) pdescInput.value = product.pdesc || '';
        if (pcostInput) pcostInput.value = product.pcost || '';
        if (imagePreview) {
            if (product.pimage) {
                imagePreview.src = product.pimage;
                imagePreview.style.display = 'block';
            } else {
                imagePreview.style.display = 'none';
            }
        }

        materials = data.materials && Array.isArray(data.materials) ? data.materials.map(m => ({
            mid: m.mid,
            pmqty: m.pmqty,
            mname: m.mname || 'Unknown'
        })) : [];
        console.log('Loaded materials:', materials); // 調試：檢查材料數據
        updateMaterialList();
        updateTranslations();
    } catch (error) {
        console.error('Failed to load product:', error);
        alert('Failed to load product: ' + error.message);
        const form = document.getElementById('product-form');
        if (form) {
            form.insertAdjacentHTML('beforebegin', '<p>Error loading product details: ' + error.message + '</p>');
        }
    }
}

async function loadMaterials() {
    console.log('Starting loadMaterials()'); // 調試：確認函數執行
    try {
        const response = await fetch('/4523WebProjectGroup08/api/get_materials.php', {
            method: 'GET',
            cache: 'no-store',
            headers: {
                'Accept': 'application/json'
            }
        });
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        const materialsData = await response.json();
        console.log('Materials API response:', materialsData); // 調試：檢查響應數據

        const select = $('#mid');
        select.empty();
        select.append('<option value="" data-i18n="select_material">Select Material</option>');
        if (Array.isArray(materialsData)) {
            materialsData.forEach(material => {
                select.append(`<option value="${material.mid}">${material.mname} (ID: ${material.mid})</option>`);
            });
        } else {
            console.error('Materials data is not an array:', materialsData);
            alert('Invalid materials data format.');
        }

        select.select2({
            placeholder: window.translations[getLanguage()].select_material || 'Select Material',
            allowClear: true,
            width: '100%',
            dropdownCssClass: 'select2-dropdown'
        });
        console.log('Select2 initialized for #mid'); // 調試：確認 Select2 初始化
        select.val('').trigger('change');
    } catch (error) {
        console.error('Failed to load materials:', error);
        alert('Failed to load materials: ' + error.message);
    }
}

function updateMaterialList() {
    console.log('Updating material list:', materials); // 調試：檢查材料列表
    const materialItems = document.getElementById('material-items');
    if (!materialItems) {
        console.error('Material items container not found');
        return;
    }
    materialItems.innerHTML = '';
    materials.forEach((material, index) => {
        const item = document.createElement('div');
        item.className = 'list-item';
        item.innerHTML = `
            <div class="item-column">${material.mname}</div>
            <div class="item-column">${material.pmqty}</div>
            <div class="item-column">
                <button type="button" class="remove-button" onclick="removeMaterial(${index})" data-i18n="remove">Remove</button>
            </div>
        `;
        materialItems.appendChild(item);
    });
    const materialsInput = document.getElementById('materials');
    if (materialsInput) {
        materialsInput.value = JSON.stringify(materials);
    }
    updateTranslations();
}

function addMaterial() {
    const mid = document.getElementById('mid')?.value;
    const pmqty = document.getElementById('pmqty')?.value;
    const materialSelect = document.getElementById('mid');
    const materialText = materialSelect && materialSelect.options[materialSelect.selectedIndex]?.text || '';
    const lang = getLanguage();

    if (!mid || !pmqty || pmqty <= 0) {
        alert(window.translations[lang].error_invalid_input);
        return;
    }

    materials.push({ mid, pmqty, mname: materialText });
    updateMaterialList();

    $('#mid').val('').trigger('change');
    const pmqtyInput = document.getElementById('pmqty');
    if (pmqtyInput) pmqtyInput.value = '';
}

function removeMaterial(index) {
    materials.splice(index, 1);
    updateMaterialList();
}

document.addEventListener('DOMContentLoaded', () => {
    console.log('edit_product.js DOMContentLoaded triggered'); // 調試：確認事件觸發
    if (typeof jQuery === 'undefined') {
        console.error('jQuery is not loaded');
        alert('jQuery is not loaded. Please check the script inclusion.');
        return;
    }
    if (typeof $.fn.select2 === 'undefined') {
        console.error('Select2 is not loaded');
        alert('Select2 is not loaded. Please check the script inclusion.');
        return;
    }
    console.log('jQuery and Select2 loaded successfully');

    const form = document.getElementById('product-form');
    if (form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const lang = getLanguage();

            if (materials.length === 0) {
                alert(window.translations[lang].error_no_material);
                return;
            }

            const formData = new FormData(e.target);
            formData.set('materials', JSON.stringify(materials));

            try {
                const response = await fetch('/4523WebProjectGroup08/api/update_product.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                if (result.result === 'success') {
                    alert(window.translations[lang].product_update_success || 'Product updated successfully.');
                    window.location.reload();
                } else {
                    alert('Errors: ' + result.errors.join(', '));
                }
            } catch (error) {
                alert('An error occurred: ' + error.message);
            }
        });
    } else {
        console.error('Product form not found');
    }

    const deleteButton = document.getElementById('delete-button');
    if (deleteButton) {
        deleteButton.addEventListener('click', async () => {
            const lang = getLanguage();
            const pidInput = document.getElementById('pid');
            const pid = pidInput ? pidInput.value : '';
            if (confirm(window.translations[lang].confirm_delete_product || 'Are you sure you want to delete this product?')) {
                try {
                    const response = await fetch(`/4523WebProjectGroup08/api/delete_product.php?pid=${pid}`, {
                        method: 'POST'
                    });
                    const result = await response.json();
                    if (result.result === 'success') {
                        alert(window.translations[lang].product_delete_success || 'Product deleted successfully.');
                        window.location.href = '/4523WebProjectGroup08/pages/staff/view_products.html';
                    } else {
                        alert('Errors: ' + result.errors.join(', '));
                    }
                } catch (error) {
                    alert('An error occurred: ' + error.message);
                }
            }
        });
    } else {
        console.error('Delete button not found');
    }

    const addMaterialButton = document.getElementById('add-material');
    if (addMaterialButton) {
        addMaterialButton.addEventListener('click', addMaterial);
    } else {
        console.error('Add material button not found');
    }

    const pimageInput = document.getElementById('pimage');
    if (pimageInput) {
        pimageInput.addEventListener('change', (e) => {
            const preview = document.getElementById('image-preview');
            if (preview && e.target.files && e.target.files[0]) {
                preview.src = URL.createObjectURL(e.target.files[0]);
                preview.style.display = 'block';
            } else if (preview) {
                preview.style.display = 'none';
            }
        });
    } else {
        console.error('Image input not found');
    }

    loadProduct();
    loadMaterials();
});