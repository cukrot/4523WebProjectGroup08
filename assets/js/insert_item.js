let materials = [];

// 初始化材料下拉菜單
async function loadMaterials() {
    try {
        const response = await fetch('../../api/get_materials.php');
        const materialsData = await response.json();
        const select = $('#mid');
        select.empty();
        select.append('<option value="" data-i18n="select_material">Select Material</option>');
        materialsData.forEach(material => {
            select.append(`<option value="${material.mid}">${material.mname} (ID: ${material.mid})</option>`);
        });
        // 初始化Select2
        select.select2({
            placeholder: window.translations[getLanguage()].select_material,
            allowClear: true,
            width: '100%',
            dropdownCssClass: 'select2-dropdown'
        });
    } catch (error) {
        console.error('Failed to load materials:', error);
    }
}

// 清空表單和材料列表
function resetForm() {
    materials = [];
    document.getElementById('product-form').reset();
    document.getElementById('materials').value = '';
    document.getElementById('material-items').innerHTML = '';
    $('#mid').val('').trigger('change');
    document.getElementById('pmqty').value = '';
}

function addMaterial() {
    const mid = document.getElementById('mid').value;
    const pmqty = document.getElementById('pmqty').value;
    const materialSelect = document.getElementById('mid');
    const materialText = materialSelect.options[materialSelect.selectedIndex].text;
    const lang = getLanguage();

    if (!mid || !pmqty || pmqty <= 0) {
        alert(window.translations[lang].error_invalid_input);
        return;
    }

    materials.push({ mid, pmqty, mname: materialText });
    updateMaterialList();

    $('#mid').val('').trigger('change');
    document.getElementById('pmqty').value = '';
}

function removeMaterial(index) {
    materials.splice(index, 1);
    updateMaterialList();
}

function updateMaterialList() {
    const materialItems = document.getElementById('material-items');
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
    document.getElementById('materials').value = JSON.stringify(materials);
    updateTranslations();
}

document.getElementById('product-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const lang = getLanguage();

    if (materials.length === 0) {
        alert(window.translations[lang].error_no_material);
        return;
    }

    const formData = new FormData(e.target);
    formData.set('materials', JSON.stringify(materials));

    try {
        const response = await fetch('../../api/insert_product.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if (result.result === 'success') {
            alert(window.translations[lang].success_message);
            resetForm();
            window.location.reload();
        } else {
            alert('Errors: ' + result.errors.join(', '));
        }
    } catch (error) {
        alert('An error occurred: ' + error.message);
    }
});

// 初始化
document.addEventListener('DOMContentLoaded', () => {
    resetForm();
    loadMaterials();
});