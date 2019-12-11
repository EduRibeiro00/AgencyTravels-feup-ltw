'use strict'

function encodeForAjax(data) {
	return Object.keys(data).map(function (k) {
		return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
	}).join('&')
}

// -------------

let profileForm = document.querySelector('#place_edit_form form');
let array_photos_to_remove = new Array();
let allLocalImageCross = document.querySelectorAll(".delete_image_local");


for (let i = 0; i < allLocalImageCross.length; i++) {

	allLocalImageCross[i].addEventListener('click', function (event) {
		//Retrevie the hash  custom data header inserted in the PHP to identify the image
		let hash = event.target.dataset.hash;

		array_photos_to_remove.push(hash);
		//Remove the parent node the div where is inserted the image in
		event.currentTarget.parentNode.remove();

		//FORCE THE NEW FIRST SIBLING TO BECOME CLASS MEDIUM SIZE

		let firstContainerWithLocalImagesRemaining = document.querySelector(".img_edit_local_container img");
		let controlIfThereIsNoPreviewImages = document.querySelector(".img_add_preview_container")
		//JUST UPDATE THIS SIZE IF THERE IS NO IMAGE IN PREVIEW
		if (controlIfThereIsNoPreviewImages == null) {
			firstContainerWithLocalImagesRemaining.className = "edit_place_img_medium";
		}

	});


}

profileForm.addEventListener('submit', function (event) {

	event.preventDefault();

	let request = new XMLHttpRequest();

	request.open("POST", "../api/api_place_edit.php", true)

	request.addEventListener('load', function () {
		let message = JSON.parse(this.responseText).message;

		switch (message) {
			case 'true':
				history.back();
				break;
			case 'user not logged in':

				break;
			case 'not house owner':

				break;
			case 'image not from that place':

				break;
			case 'invalid image':
				break;
			case 'Parameters not validated':

				break;
			case 'Invalid IMAGE uploaded':

				break;
			case 'Error removing the photo':

				break;
			default:
				history.back();
				break;
		}

	});
	let formData = new FormData(profileForm);
	formData.append('imagesToRemoveArray', array_photos_to_remove);

	request.send(formData);
});

// -----------