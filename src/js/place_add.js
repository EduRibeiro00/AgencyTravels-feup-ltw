'use strict'

//MUST BE EXACLTLY THE SAME ON PLACE EDIT

function encodeForAjax(data) {
	return Object.keys(data).map(function (k) {
		return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
	}).join('&')
}

function deleteFromArray(array) {

	let array_aux = new Array();

	for (let i in array) {
		array_aux.push(array[i]);
	}

	return array_aux;
}

let profileForm = document.querySelector('#place_edit_form form');
let errorMessage = document.getElementById('place-form-error');
let profileFormImage = document.getElementById('img-to-upload');
let image_block_preview = document.getElementById('house_form_img_preview');
let labelInput = document.getElementById('add_images');
let imagesInput = [];

//Going to update the sizeof the medium photo
//In order to be possible to append childs
let image_delete_preview = document.getElementById('img-delete_place_add');

let number_images = 0;
let img_array = new Array();
let files_array = new Array();
let imgId = 0;

function generateImgDivContainer(imgSrc) {
	let div_container = document.createElement("div");
	let image_to_append = document.createElement("img");
	let remove_cross = document.createElement("i");
	//GOING TO IDENTIFY THE IMG POS ON THE ARRAY TO ALLOW DELETE FUNCTIONALITY
	remove_cross.setAttribute('identifier_local', imgId);
	image_to_append.className = "edit_place_img_medium";
	div_container.className = "img_add_preview_container"
	remove_cross.className = "fas fa-times delete_image_preview"
	img_array[imgId] = div_container;
	image_to_append.src = imgSrc;
	imgId++;
	div_container.appendChild(image_to_append);
	div_container.appendChild(remove_cross);
	return div_container;
}

// TODO: ver nome
function addImagesToPlace(event) {
	//UPDATE THE FIRST LOCAL PHOTO TO SMALL
	let localImages = document.getElementById('house_form_img_local img');

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

					img_array = deleteFromArray(img_array);
					//REMOVES FILE FROM ARRAY FILES
					delete files_array[pos_delete_array];

					files_array = deleteFromArray(files_array);
					//
					number_images--;
					imgId--;
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
				let arrayDomElements = document.querySelectorAll('.img_add_preview_container i');
				updateIdentifierLocal(arrayDomElements);
			}
			)

		});
	}
}

function updateIdentifierLocal(array) {
	for (let i = 0; i < imgId; i++) {
		array[i].setAttribute('identifier_local', i);
	}
}

labelInput.addEventListener('click', newAddImgInput)

function newAddImgInput() {
	// TODO falta data-hasFile=<?= $hasFile ?>
	// <input class="button" type="file" id="imageFile_add_place" accept="image/*" name="imagePlaceFile[]" multiple >
	labelInput.htmlFor = "imageFile_add_place" + (imagesInput.length + 1)
	let input = document.createElement('input');
	input.id = labelInput.htmlFor
	input.type = "file"
	input.classList.add('button')
	input.accept = "image/*"
	input.name = "imagePlaceFile[]"
	input.multiple = true

	labelInput.parentNode.insertBefore(input, labelInput.nextSibling);
	input.addEventListener('change', addImagesToPlace)
	imagesInput.push(input)

}


// remove image button

// -------------


let button_Submit = document.getElementById('edit_place_submit');

profileForm.addEventListener('submit', function (event) {

	event.preventDefault();

	let request = new XMLHttpRequest();

	request.open("POST", "../api/api_place_add.php", true)

	request.addEventListener('load', function () {
		let message = JSON.parse(this.responseText).message;

		switch (message) {
			case 'true':
				showDialog('Place added with sucess');
				window.setTimeout(function () { history.back(); }, 3000);
				break;

			case 'user not logged in':
				showDialog('YOU ARE NOT LOGGED IN');
				window.setTimeout(function () { history.back(); }, 3000);
				break;

			case 'invalid image':
				console.warn('There is a problem with your image');
				errorMessage.textContent = "There is a problem with your image";
				errorMessage.style.display = "block";
				break;

			case 'Number of photos invalid':
				errorMessage.textContent = "Number of photos invalid";
				errorMessage.style.display = "block";
				break;

			case 'Title not valid':
				errorMessage.textContent = "Title not Valid";
				errorMessage.style.display = "block";
				break;
			case 'Description not valid':
				errorMessage.textContent = 'Description not valid';
				break;
			case 'Address not valid':
				errorMessage.textContent = 'Address not valid';
				errorMessage.style.display = "block";
				break;
			case 'Number of Bathrooms is not valid':
				errorMessage.textContent = 'Number of Bathrooms is not valid';
				errorMessage.style.display = "block";
				break;
			case 'Number of rooms is not valid':
				errorMessage.textContent = 'Number of rooms is not valid';
				errorMessage.style.display = "block";
				break;
			case 'Capacity is not valid':
				errorMessage.textContent = "Capacity is not valid";
				errorMessage.style.display = "block";
				break;

			case 'GPS Coords of that Address invalid':
				errorMessage.textContent = 'GPS Coords of that Address invalid';
				errorMessage.style.display = "block";
				break;

			case 'Duplicate Images':
				showDialog('Duplicates But inserted');
				window.setTimeout(function () { history.back(); }, 3000)
				break;

			default:
				errorMessage.textContent = "Error adding.";
				errorMessage.style.display = "block";
				button_Submit.style.visibility = "visible";
				break;
		}

	});

	if (imagesInput.length == 0)
		newAddImgInput()

	let formData = new FormData(profileForm);


	button_Submit.style.visibility = "hidden";
	formData.append('File0', files_array[0]);
	formData.append('File1', files_array[1]);
	formData.append('File2', files_array[2]);
	formData.append('File3', files_array[3]);
	formData.append('File4', files_array[4]);
	formData.append('File5', files_array[5]);


	request.send(formData);
});

	// -----------

