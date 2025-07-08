window.translations = {
    en: {
        error_no_material: 'Please add at least one material.',
        error_invalid_input: 'Please select a material and enter a valid quantity.',
        success_message: 'Product inserted successfully.',
        material_success_message: 'Material inserted successfully.',
        error_invalid_material: 'Please enter valid material information.',
        error_invalid_order: 'Please enter valid order information.',
        order_update_success: 'Order updated successfully.',
        logout: 'Logout',
        confirm_logout: 'Are you sure you want to logout?',
        logout_success: 'Logout confirmed.'
    },
    zh: {
        error_no_material: '請至少添加一種材料。',
        error_invalid_input: '請選擇材料並輸入有效的數量。',
        success_message: '產品插入成功。',
        material_success_message: '材料插入成功。',
        error_invalid_material: '請輸入有效的材料信息。',
        error_invalid_order: '請輸入有效的訂單信息。',
        order_update_success: '訂單更新成功。',
        logout: '登出',
        confirm_logout: '您確定要登出嗎？',
        logout_success: '已確認登出。'
    }
};

function getLanguage() {
    const urlParams = new URLSearchParams(window.location.search);
    let lang = urlParams.get('lang') || localStorage.getItem('lang') || 'en';
    if (lang !== 'zh' && lang !== 'en') lang = 'en';
    localStorage.setItem('lang', lang);
    return lang;
}

async function loadHeader() {
    const headerContainer = document.getElementById('header-container');
    const isStaffPage = window.location.pathname.includes('/staff/');
    const headerUrl = isStaffPage ? '../../includes/staff_header.html' : '../../includes/customer_header.html';
    
    try {
        const response = await fetch(headerUrl);
        if (!response.ok) throw new Error(`Failed to load header: ${response.status}`);
        headerContainer.innerHTML = await response.text();
        await updateTranslations();
    } catch (error) {
        console.error('Failed to load header:', error);
    }
}

async function updateTranslations() {
    const lang = getLanguage();
    try {
        const response = await fetch(`/4523WebProjectGroup08/api/get_translations.php?lang=${lang}`);
        if (!response.ok) throw new Error(`Failed to load translations: ${response.status}`);
        window.translations[lang] = await response.json();
    } catch (error) {
        console.error('Failed to load translations:', error);
    }

    document.querySelectorAll('[data-i18n]').forEach(element => {
        const key = element.getAttribute('data-i18n');
        if (window.translations[lang][key]) {
            element.textContent = window.translations[lang][key];
        }
    });

    document.querySelectorAll('[data-i18n-placeholder]').forEach(element => {
        const key = element.getAttribute('data-i18n-placeholder');
        if (window.translations[lang][key]) {
            element.placeholder = window.translations[lang][key];
        }
    });

    const langSelect = document.querySelector('select');
    if (langSelect) langSelect.value = lang;
}

function switchLanguage(lang) {
    localStorage.setItem('lang', lang);
    const urlParams = new URLSearchParams(window.location.search);
    const currentParams = {};
    urlParams.forEach((value, key) => {
        if (key !== 'lang') currentParams[key] = value;
    });
    currentParams['lang'] = lang;
    const newParams = new URLSearchParams(currentParams);
    window.location.search = newParams.toString();
}

async function confirmLogout() {
    const lang = getLanguage();
    if (confirm(window.translations[lang].confirm_logout)) {
        try {
            const response = await fetch('/4523WebProjectGroup08/pages/staff/logout.php', {
                method: 'POST'
            });
            const result = await response.json();
            if (result.result === 'success') {
                alert(window.translations[lang].logout_success);
                window.location.href = '/4523WebProjectGroup08/customer/home.php';
            } else {
                alert('Logout failed: ' + result.message);
            }
        } catch (error) {
            console.error('Logout error:', error);
            alert('Logout failed: ' + error.message);
        }
    }
}

document.addEventListener('DOMContentLoaded', loadHeader);