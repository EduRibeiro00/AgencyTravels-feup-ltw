'use strict'

function encodeForAjax(data) {
	return Object.keys(data).map(function(k){
	  return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
	}).join('&')
}

// -------------

function updateMaxBirthDate() {
    let birthDateInput = document.getElementById('birthDate');
    let maxDate = new Date();
    maxDate.setFullYear(maxDate.getFullYear() - 5); // max date is 5 years before actual date
    let dateValue = maxDate.getFullYear()+'-'+('0'+(maxDate.getMonth()+1)).slice(-2)+'-'+('0'+(maxDate.getDate())).slice(-2);
    birthDateInput.setAttribute('max', dateValue);
}

updateMaxBirthDate();

// -----------------

// photo upload
const reader = new FileReader();

let profileFormImage = document.getElementById('img-to-upload');
let imageInput = document.querySelector('input#imageFile');
imageInput.addEventListener('change', function(event) {
    const f = event.target.files[0];
    imageInput.setAttribute('data-hasFile', "yes");
    reader.readAsDataURL(f);
});

reader.addEventListener('load', function(event) {
    profileFormImage.src = event.target.result;
});

// remove image button
let removeButton = document.getElementById('remove-button');
removeButton.addEventListener('click', function() {
    profileFormImage.src = "../assets/images/users/medium/noImage.png";
    imageInput.value = '';
    imageInput.setAttribute('data-hasFile', "no");
});
