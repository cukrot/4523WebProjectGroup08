let materials = [];

function addMaterial() {
    const mid = document.getElementById('mid').value;
    const pmqty = document.getElementById('pmqty').value;
    const materialSelect = document.getElementById('mid');
    const materialText = materialSelect.options[materialSelect.selectedIndex].text;

    if (!mid || !pmqty || pmqty <= 0) {
        alert('<?php echo $t["error_invalid_input"]; ?>');
        return;
    }

    materials.push({ mid, pmqty, mname: materialText });
    updateMaterialList();

    document.getElementById('mid').value = '';
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
                <button type="button" class="remove-button" onclick="removeMaterial(${index})"><?php echo $t['remove']; ?></button>
            </div>
        `;
        materialItems.appendChild(item);
    });
    document.getElementById('materials').value = JSON.stringify(materials);
}

document.getElementById('product-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    if (materials.length === 0) {
        alert('<?php echo $t["error_no_material"]; ?>');
        return;
    }

    const formData = new FormData(e.target);
    formData.set('materials', JSON.stringify(materials));

    try {
        const response = await fetch('../api/insert_product.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if (result.result === 'success') {
            alert('<?php echo $t["success_message"]; ?>');
            window.location.reload();
        } else {
            alert('Errors: ' + result.errors.join(', '));
        }
    } catch (error) {
        alert('An error occurred: ' + error.message);
    }
});