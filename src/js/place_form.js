'use strict'

function encodeForAjax(data) {
    return Object.keys(data).map(function (k) {
        return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&')
}


// photo upload



let profileFormImage = document.getElementById('img-to-upload');
let image_block_preview = document.querySelector('#house_form_img_preview');
let imageInput = document.querySelector('input#imageFile_add_place');
//Going to update the sizeof the medium photo
let localImages=document.querySelector('#house_form_img_local .edit_place_img_medium');
//In order to be possible to append childs
let image_delete_preview=document.querySelector('#img-delete_place_add');
let img_id=0;
let img_array=new Array()

//TODO:IMPLEMENT the remove cross
imageInput.addEventListener('change', function (event) {

    //UPDATE THE FIRST LOCAL PHOTO TO SMALL
    localImages.className="edit_place_img_small";

    for (let i = 0; i < event.target.files.length; i++) {
        let reader_inside = new FileReader();
        let f = event.target.files[i];
        reader_inside.readAsDataURL(f);
        
        reader_inside.addEventListener('load', function (event) {
            let image_to_append = document.createElement("img");
            let remove_button=document.createElement("i");
            image_to_append.className = "edit_place_img_medium";
            
            remove_button.className="fas fa-times delete_preview_photo"
            remove_button.href=img_id;
            
            img_array[img_id]=image_to_append;
            img_id++;
            image_to_append.src = event.target.result;
            
            image_block_preview.appendChild(image_to_append);
            //image_delete_preview.appendChild(remove_button);
            
            remove_button.addEventListener('click',function(event){
                event.preventDefault();
                let obj=event.target;
                let pos_delete_array=obj.href[obj.href.length-1]
                img_array[pos_delete_array].remove()
                remove_button.remove()
        
            }
            )
        });
    }
});


// remove image button



