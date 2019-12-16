'use strict'

function encodeForAjax(data) {
	return Object.keys(data).map(function (k) {
		return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
	}).join('&')
}

//function to create the element+ container when we add a new image. Auxiliaary function
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

// -------------

let profileForm = document.querySelector('#place_edit_form form');
let array_photos_to_remove = new Array();
let allLocalImageCross = document.querySelectorAll(".delete_image_local");
let errorMessage = document.getElementById('place-form-error');
let profileFormImage = document.getElementById('img-to-upload');
let image_block_preview = document.getElementById('house_form_img_preview');

let labelEditInput = document.getElementById('add_images');
let imagesEditInput = [];
//Going to update the sizeof the medium photo
//In order to be possible to append childs
let image_delete_preview = document.querySelector('#img-delete_place_add');

let img_id = 0;
let number_images = 0;
//THE NUMBER OF IMAGES LOCAL IS THE SAME AS THE NAME OF ORIGINAL DELETE CROSSES
let number_images_local = allLocalImageCross.length;
let img_array = new Array();
let files_array = new Array();

//Establish event listener to all "local"(preexistent)delete buttons
for (let i = 0; i < allLocalImageCross.length; i++) {

	allLocalImageCross[i].addEventListener('click', function (event) {

		if ((number_images + number_images_local - array_photos_to_remove.length) > 1) {

			//Retrevie the hash  custom data header inserted in the PHP to identify the image
			let hash = event.target.dataset.hash;
			//WE STORE IN THIS ARRAY THE HASH NAME TO BE POSSIBLE TO REMOVE FROM THE DATABASE. THAT INFORMATION COME IN THE HTML HEADER OF THIS ELEMENT
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
		}
	});
}

function editImagesOfPlace(event) {

	//UPDATE THE FIRST LOCAL PHOTO TO SMALL
	let localImages = document.getElementById('house_form_img_local img');

	if (localImages != null) {
		localImages.className = "edit_place_img_small";
	}

	//FOR ALL FILES UPLOADED
	for (let i = 0; i < event.target.files.length; i++) {
		//IF WE REMOVE THE ERROR IS RELEATED WITH THE MULTIPLE FILES UPLOAD. BUSY SERVICE
		let reader_inside = new FileReader();
		let f = event.target.files[i];

		//Add files to the files array
		if ((number_images + number_images_local - array_photos_to_remove.length) < 6) {
			files_array.push(f.name);
			number_images++;
			reader_inside.readAsDataURL(f);
		}

		//WHEN THE READASDATAURL IS DONE
		reader_inside.addEventListener('load', function (event) {

			//WE ONLY ADD AN NEW IMAGE IF THE NUMBER OF ELEMENTS IS LESS THAN 6 AT THAT MOMENT

			//CALL THE FUNCTION TO GENERATE AN ELEMENT OF THAT TYPE
			let child_element = generateImgDivContainer(event.target.result);
			image_block_preview.appendChild(child_element);

			let remove_button = child_element.getElementsByClassName("delete_image_preview");

			remove_button[0].addEventListener('click', function (event) {

				event.preventDefault();

				let pos_delete_array = remove_button[0].getAttribute('identifier_local');

				if (pos_delete_array > img_array.length) {
					console.error('DONT TRY TO VIOLATE THE JS ITS USELESS MATE');
					//FORCE A MINIMUM OF 1 IMAGE
				} else if ((number_images + number_images_local - array_photos_to_remove.length) > 0) {
					//REMOVE FROM GUI
					img_array[pos_delete_array].remove();
					//REMOVE JS DATA
					delete img_array[pos_delete_array];
					//REMOVES FILE FROM ARRAY FILES
					delete files_array[pos_delete_array];
					//
					number_images--;
				}

				// I M USING DELETE LEAVES HOLES AND LENGTH REPRESENTS THE LAST ELEMENT NOT THE NUMBER OF ELEMENTS IN JS THIS MUST BE DONE TO CHECK IF THERE ARE ELEMENTS
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
}

//WHEN AN INPUT IS LOADED IT FIRE THIS EVENT
labelEditInput.addEventListener('click', newAddImgInput)

function newAddImgInput() {
	// TODO falta data-hasFile=<?= $hasFile ?>
	// <input class="button" type="file" id="imageFile_add_place" accept="image/*" name="imagePlaceFile[]" multiple >
	labelEditInput.htmlFor = "imageFile_add_place" + (imagesEditInput.length + 1)
	let input = document.createElement('input');
	input.id = labelEditInput.htmlFor
	input.type = "file"
	input.classList.add('button')
	input.accept = "image/*"
	input.name = "imagePlaceFile[]"
	input.multiple = true

	labelEditInput.parentNode.insertBefore(input, labelEditInput.nextSibling);
	input.addEventListener('change', editImagesOfPlace)
	imagesEditInput.push(input)

}

profileForm.addEventListener('submit', function (event) {

	event.preventDefault();

	let request = new XMLHttpRequest();

	request.open("POST", "../api/api_place_edit.php", true)

	request.addEventListener('load', function () {
		console.log(this.responseText);
		let message = JSON.parse(this.responseText).message;

		switch (message) {
			case 'true':
				history.back();
				break;
			case 'user not logged in':
				history.back();
				console.error('YOU ARE NOT LOGGED IN');
				break;
			case 'not house owner':
				history.back();
				console.error('YOU DONT HAVE PERMISSIONS');
				break;
			case 'image not from that place':
				history.back();
				console.warn('There is a problem with your image');
				break;
			case 'invalid image':
				console.warn('There is a problem with your image');
				errorMessage.textContent = "There is a problem with your image";
				errorMessage.style.display = "block";
				break;
			case 'Parameters not validated':
				errorMessage.textContent = "There is a problem with your inputs";
				errorMessage.style.display = "block";
				break;
			case 'Invalid IMAGE uploaded':
				console.warn('There is a problem with your image');
				errorMessage.textContent = "There is a problem with your image";
				errorMessage.style.display = "block";
				break;
			case 'Error removing the photo':
				console.warn('There is a problem with your image');
				break;
			default:
				history.back();
				break;
		}

	});
	let formData = new FormData(profileForm);
	formData.append('imagesToRemoveArray', array_photos_to_remove);
	formData.append('File0', files_array[0]);
	formData.append('File1', files_array[1]);
	formData.append('File2', files_array[2]);
	formData.append('File3', files_array[3]);
	formData.append('File4', files_array[4]);
	formData.append('File5', files_array[5]);


	if(imagesInput.length == 0)
		newAddImgInput()

	request.send(formData);
});

// -----------
