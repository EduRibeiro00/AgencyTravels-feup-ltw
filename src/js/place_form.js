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

function generateImgDivContainer(imgSrc){
    let div_container = document.createElement("div");
    let image_to_append = document.createElement("img");
    let remove_cross=document.createElement("i");
    //GOING TO IDENTIFY THE IMG POS ON THE ARRAY TO ALLOW DELETE FUNCTIONALITY
    remove_cross.setAttribute('identifier_local',img_id);
    image_to_append.className = "edit_place_img_medium";
    div_container.className="img_add_preview_container"
    remove_cross.className="fas fa-times delete_image_preview"

    img_array[img_id]=div_container;
    img_id++;
    image_to_append.src = imgSrc;

    div_container.appendChild(image_to_append);
    div_container.appendChild(remove_cross);

    return div_container;

}

//TODO:IMPLEMENT the remove cross
imageInput.addEventListener('change', function (event) {

    //UPDATE THE FIRST LOCAL PHOTO TO SMALL
    localImages.className="edit_place_img_small";

    for (let i = 0; i < event.target.files.length; i++) {
        let reader_inside = new FileReader();
        let f = event.target.files[i];
        reader_inside.readAsDataURL(f);
        
        reader_inside.addEventListener('load', function (event) {

            let child_element=generateImgDivContainer(event.target.result);
            image_block_preview.appendChild(child_element);
            let remove_button=child_element.getElementsByClassName("delete_image_preview");    
        
            remove_button[0].addEventListener('click',function(event){
                
                event.preventDefault();
                let pos_delete_array=remove_button[0].getAttribute('identifier_local');
                
                if(pos_delete_array>img_array.length){
                    console.error('DONT TRY TO VIOLATE THE JS ITS USELESS MATE');
                }

                img_array[pos_delete_array].remove();
                delete img_array[pos_delete_array];
                img_array.length--;
                
                //IF THE INPUT BECOMES EMPTY RESET THE SIZE OF THE FIRST IMAGE
                if(img_array.length==0){
                    localImages.className="edit_place_img_medium";
                }
                //remove_button.remove()
                /*
                let obj=event.target;
                let pos_delete_array=obj.href[obj.href.length-1]
                */
        
            }
            )
        });
    }
});


// remove image button


