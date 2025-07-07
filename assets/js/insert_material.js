// 清空表單
function resetForm() {
    document.getElementById('material-form').reset();
    document.getElementById('munit').value = '';
}

document.getElementById('material-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const lang = getLanguage();

    const formData = new FormData(e.target);

    try {
        const response = await fetch('../../api/insert_material.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if (result.result === 'success') {
            alert(window.translations[lang].material_success_message);
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
document.addEventListener('DOMContentLoaded', resetForm);