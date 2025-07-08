const editBtn = document.querySelector('.edit-btn');
const saveBtn = document.querySelector('.save-btn');
const table = document.getElementById('profile-table');

editBtn.addEventListener('click', () => {
  
    table.querySelectorAll('.data').forEach(span => span.style.display = 'none');
    table.querySelectorAll('input').forEach(input => input.style.display = 'block');
    saveBtn.style.display = 'inline-block';
    editBtn.style.display = 'none';
});

saveBtn.addEventListener('click', () => {
    
    document.getElementById('profile-form').submit();
});