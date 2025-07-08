function validateForm() {
    const cardNumber = document.querySelector('input[name="card_number"]').value;
    const expiryDate = document.querySelector('input[name="expiry_date"]').value;
    const cvv = document.querySelector('input[name="cvv"]').value;

    // Check card number (16 digits)
    if (!/^\d{16}$/.test(cardNumber)) {
        alert('Credit Card Number must be 16 digits.');
        return false;
    }

    // Check expiration date (4 digits, MMYY format)
    if (!/^(0[1-9]|1[0-2])\d{2}$/.test(expiryDate)) {
        alert('Expiration Date must be in MMYY format.');
        return false;
    }

    // Check CVV (3 digits)
    if (!/^\d{3}$/.test(cvv)) {
        alert('CVV must be 3 digits.');
        return false;
    }

    return true; // Allow form submission
}