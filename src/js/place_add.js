'use strict'


function encodeForAjax(data) {
	return Object.keys(data).map(function (k) {
		return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
	}).join('&')
}

//MUST BE EXACLTLY THE SAME ON PLACE EDIT

let profileFormImage = document.getElementById('img-to-upload');
let image_block_preview = document.querySelector('#house_form_img_preview');
let imageInput = document.querySelector('input#imageFile_add_place');
let imageInput2 = document.querySelector('input#imageFile_add_place2');

//Going to update the sizeof the medium photo
//In order to be possible to append childs
let image_delete_preview = document.querySelector('#img-delete_place_add');

let img_id = 0;
let number_images = 0;
let img_array = new Array();
let files_array = new Array();


function generateImgDivContainer(imgSrc) {
	let div_container = document.createElement("div");
	let image_to_append = document.createElement("img");
	let remove_cross = document.createElement("i");
	//GOING TO IDENTIFY THE IMG POS ON THE ARRAY TO ALLOW DELETE FUNCTIONALITY
	remove_cross.setAttribute('identifier_local', img_id);
	image_to_append.className = "edit_place_img_medium";
	div_container.className = "img_add_preview_container"
	remove_cross.className = "fas fa-times delete_image_preview"

	img_array[img_id] = div_container;
	img_id++;
	image_to_append.src = imgSrc;

	div_container.appendChild(image_to_append);
	div_container.appendChild(remove_cross);
	return div_container;
}


imageInput.addEventListener('change', function (event) {

	for (let i = 0; i < event.target.files.length; i++) {
		let reader_inside = new FileReader();
		let f = event.target.files[i];
		//Add files to the files array

		if (number_images < 6) {
			//image_block_preview = [];
			files_array.push(f.name);
			number_images++;
			reader_inside.readAsDataURL(f);
		}


		reader_inside.addEventListener('load', function (event) {

			let child_element = generateImgDivContainer(event.target.result);
			image_block_preview.appendChild(child_element);

			let remove_button = child_element.getElementsByClassName("delete_image_preview");

			remove_button[0].addEventListener('click', function (event) {

				event.preventDefault();

				let pos_delete_array = remove_button[0].getAttribute('identifier_local');

				if (pos_delete_array > img_array.length) {
					console.error('DONT TRY TO VIOLATE THE JS ITS USELESS MATE');
					//FORCE A MINIMUM OF 1 IMAGE
				} else if (number_images > 1) {
					//REMOVE FROM GUI
					img_array[pos_delete_array].remove();
					//REMOVE JS DATA
					delete img_array[pos_delete_array];
					//REMOVES FILE FROM ARRAY FILES
					delete files_array[pos_delete_array];
					//
					number_images--;
				}

				let is_empty = true;
				//THE INDEX REPRESENT THE INDEX OF LAST ELEMENT. INCOMPATIBLE WITH REMOVE. REMOVE IS NOT AVOIDABLE HERE
				for (let i = 0; i < img_array.length; i++) {
					if (img_array[i] != null) {
						is_empty = false;
					}
				}
				//IF THE INPUT BECOMES EMPTY RESET THE SIZE OF THE FIRST IMAGE
				//!=NULL could be add form there are no local photos
				if (is_empty == true && localImages != null) {
					localImages.className = "edit_place_img_medium";
				}
			}
			)

		});
	}
});

imageInput2.addEventListener('change', function (event) {


	for (let i = 0; i < event.target.files.length; i++) {
		let reader_inside = new FileReader();
		let f = event.target.files[i];
		//Add files to the files array

		if (number_images < 6) {
			//image_block_preview = [];
			files_array.push(f.name);
			number_images++;
			reader_inside.readAsDataURL(f);
		}


		reader_inside.addEventListener('load', function (event) {

			let child_element = generateImgDivContainer(event.target.result);
			image_block_preview.appendChild(child_element);

			let remove_button = child_element.getElementsByClassName("delete_image_preview");

			remove_button[0].addEventListener('click', function (event) {

				event.preventDefault();

				let pos_delete_array = remove_button[0].getAttribute('identifier_local');

				if (pos_delete_array > img_array.length) {
					console.error('DONT TRY TO VIOLATE THE JS ITS USELESS MATE');
					//FORCE A MINIMUM OF 1 IMAGE
				} else if (number_images > 1) {
					//REMOVE FROM GUI
					img_array[pos_delete_array].remove();
					//REMOVE JS DATA
					delete img_array[pos_delete_array];
					//REMOVES FILE FROM ARRAY FILES
					delete files_array[pos_delete_array];
					//
					number_images--;
				}

				let is_empty = true;
				//THE INDEX REPRESENT THE INDEX OF LAST ELEMENT. INCOMPATIBLE WITH REMOVE. REMOVE IS NOT AVOIDABLE HERE
				for (let i = 0; i < img_array.length; i++) {
					if (img_array[i] != null) {
						is_empty = false;
					}
				}
				//IF THE INPUT BECOMES EMPTY RESET THE SIZE OF THE FIRST IMAGE
				//!=NULL could be add form there are no local photos
				if (is_empty == true && localImages != null) {
					localImages.className = "edit_place_img_medium";
				}
			}
			)

		});
	}
});


// remove image button

// -------------

let profileForm = document.querySelector('#place_edit_form form');
let errorMessage = document.getElementById('place-form-error');


profileForm.addEventListener('submit', function (event) {

	event.preventDefault();

	let request = new XMLHttpRequest();

	request.open("POST", "../api/api_place_add.php", true)

	request.addEventListener('load', function () {
		console.log(this.responseText);
		let message = JSON.parse(this.responseText).message;


		switch (message) {
			case 'true':
				history.back();
				break;

			default:
				errorMessage.textContent = "Error adding.";
				errorMessage.style.display = "block";

				break;
		}

	});

	let formData = new FormData(profileForm);
	formData.append('File0', files_array[0]);
	formData.append('File1', files_array[1]);
	formData.append('File2', files_array[2]);
	formData.append('File3', files_array[3]);
	formData.append('File4', files_array[4]);
	formData.append('File5', files_array[5]);

	request.send(formData);


});

	// -----------

