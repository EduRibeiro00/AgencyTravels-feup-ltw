'use strict'

function encodeForAjax(data) {
	return Object.keys(data).map(function(k){
	  return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
	}).join('&')
}


// photo upload
const reader = new FileReader();
let last_array_pos=0

let profileFormImage = document.getElementById('img-to-upload');
let imageInput = document.querySelector('input#imageFile_add_place');
//In order to be possible to append childs
let image_block_preview=document.querySelector('#img-upload div');

imageInput.addEventListener('change', function(event) {
    const f = event.target.files[0];
    reader.readAsDataURL(f);
});

reader.addEventListener('load', function(event) {
    
    let image_to_append=document.createElement("img");
    image_to_append.src=event.target.result;
    image_block_preview.appendChild(image_to_append);
});

// remove image button
/*
let removeButton = document.getElementById('remove-button');
removeButton.addEventListener('click', function() {
    profileFormImage.src = "../assets/images/users/medium/noImage.png";
    imageInput.value = '';
    imageInput.setAttribute('data-hasFile', "no");
}

);
*/

