function updateMaxBirthDate() {
    let birthDateInput = document.getElementById('birthDate');
    let maxDate = new Date();
    maxDate.setFullYear(maxDate.getFullYear() - 5); // max date is 5 years before actual date
    let dateValue = maxDate.getFullYear()+'-'+('0'+(maxDate.getMonth()+1)).slice(-2)+'-'+('0'+(maxDate.getDate())).slice(-2);
    birthDateInput.setAttribute('max', dateValue);
}

updateMaxBirthDate();

// -----------------

// TODO: implementar upload de fotos