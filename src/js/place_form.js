'use strict'

function encodeForAjax(data) {
    return Object.keys(data).map(function (k) {
        return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&')
}


// photo upload


let profileFormImage = document.getElementById('img-to-upload');
let imageInput = document.querySelector('input#imageFile_add_place');
//In order to be possible to append childs
let image_block_preview = document.querySelector('#img-upload div');
let image_delete_preview=document.querySelector('#img-delete_place_add');
let img_id=0;
let img_array=new Array()

imageInput.addEventListener('change', function (event) {

    let width_pos=0;
    for (let i = 0; i < event.target.files.length; i++) {
        let reader_inside = new FileReader();
        let f = event.target.files[i];
        reader_inside.readAsDataURL(f);
        
        reader_inside.addEventListener('load', function (event) {
            let image_to_append = document.createElement("img");
            let remove_button=document.createElement("a");
            image_to_append.className = "edit_place_img_small";
            remove_button.className="close";
            remove_button.href=img_id;
            img_array[img_id]=image_to_append;
            img_id++;
            image_to_append.src = event.target.result;
            width_pos+=image_to_append.width
            remove_button.setAttribute('margin-left',width_pos);
            image_block_preview.appendChild(image_to_append);
            image_delete_preview.appendChild(remove_button);
            
            remove_button.addEventListener('click',function(event){
                event.preventDefault();
                let obj=event.target;
                
                let pos_delete_array=obj.href[obj.href.length-1]

                img_array[pos_delete_array].remove()


    
                console.log("Aqui") 
            
            }
            )
        });
    }
});


// remove image button



