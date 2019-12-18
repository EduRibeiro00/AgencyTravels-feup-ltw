<?php
include_once('../database/db_user.php');
include_once('../database/db_places.php');
include_once('../database/db_images.php');

function checkIfImageIsValid($image) {
  // no image
  if($image == "")
    return true;

  // checks if file is really an image
  $return = exif_imagetype($image);

  // accepts GIF, JPG, JPEG and PNG
  return ($return !== false && $return <= IMAGETYPE_PNG);
}


// small - 70 x 70
// medium - max width: 200
function uploadUserImage($userID, $image) {
    // function assumes that the image is valid and verification was done before

    $date = date('Y-m-d H:i:s');
    $imageSalt = random_bytes(5);
    $imageName = hash("sha256", $userID . $date . $imageSalt) . ".png";

    // Generate filenames for original, small and medium files
    $originalFileName = "../assets/images/users/original/$imageName";
    $smallFileName = "../assets/images/users/small/$imageName";
    $mediumFileName = "../assets/images/users/medium/$imageName";

    insertImageForUser($userID, $imageName);

    // Move the uploaded file to its final destination (always converted to png)
    imagepng(imagecreatefromstring(file_get_contents($image)), $originalFileName);

    // Crete an image representation of the original image
    $original = imagecreatefrompng($originalFileName);
    
    $width = imagesx($original);     // width of the original image
    $height = imagesy($original);    // height of the original image
    $square = min($width, $height);  // size length of the maximum square

    // Create and save a small square thumbnail (70 x 70)
    $small = imagecreatetruecolor(70, 70);
    imagecopyresampled($small, $original, 0, 0, ($width>$square)?($width-$square)/2:0, ($height>$square)?($height-$square)/2:0, 70, 70, $square, $square);
    imagepng($small, $smallFileName);

    // Calculate width and height of medium sized image (max width: 200)
    $mediumwidth = $width;
    $mediumheight = $height;
    if ($mediumwidth > 200) {
      $mediumwidth = 200;
      $mediumheight = $mediumheight * ( $mediumwidth / $width );
    }

    // Create and save a medium image
    $medium = imagecreatetruecolor($mediumwidth, $mediumheight);
    imagecopyresampled($medium, $original, 0, 0, 0, 0, $mediumwidth, $mediumheight, $width, $height);
    imagepng($medium, $mediumFileName);

    return true;
}

function deleteUserImage($userID) {
  $oldImageName = getUserImage($userID);
  if($oldImageName == "noImage.png") { // user doesn't have an image already
    return true;
  }

  unlink("../assets/images/users/original/$oldImageName");
  unlink("../assets/images/users/small/$oldImageName");
  unlink("../assets/images/users/medium/$oldImageName");

  deleteImageForUser($userID);

  return true;
}

function updateUserImage($userID, $image) {
  deleteUserImage($userID);
  uploadUserImage($userID, $image);

  return true;
}

// --------------------------------


// small - 70 x 70
// medium - max height: 300
// big - max height: 700
function uploadPlaceImage($placeID, $image) {

  $date = date('Y-m-d H:i:s');
  $imageSalt = random_bytes(5);
  $imageName = hash("sha256", $placeID . $date . $imageSalt) . ".png";

  // Generate filenames for original, small, medium and big files
  $originalFileName = "../assets/images/places/original/$imageName";
  $smallFileName = "../assets/images/places/small/$imageName";
  $mediumFileName = "../assets/images/places/medium/$imageName";
  $bigFileName = "../assets/images/places/big/$imageName";

  insertImageForPlace($placeID, $imageName);

  // Move the uploaded file to its final destination (always converted to png)
  imagepng(imagecreatefromstring(file_get_contents($image)), $originalFileName);

  // Crete an image representation of the original image
  $original = imagecreatefrompng($originalFileName);
  
  $width = imagesx($original);     // width of the original image
  $height = imagesy($original);    // height of the original image
  $square = min($width, $height);  // size length of the maximum square

  // Create and save a small square thumbnail (70 x 70)
  $small = imagecreatetruecolor(70, 70);
  imagecopyresampled($small, $original, 0, 0, ($width>$square)?($width-$square)/2:0, ($height>$square)?($height-$square)/2:0, 70, 70, $square, $square);
  imagepng($small, $smallFileName);

  // Calculate width and height of medium sized image (max height: 300)
  $mediumwidth = $width;
  $mediumheight = $height;
  if ($mediumheight > 300) {
    $mediumheight = 300;
    $mediumwidth = $mediumwidth * ( $mediumheight / $height );
  }

  // Create and save a medium image
  $medium = imagecreatetruecolor($mediumwidth, $mediumheight);
  imagecopyresampled($medium, $original, 0, 0, 0, 0, $mediumwidth, $mediumheight, $width, $height);
  imagepng($medium, $mediumFileName);


  // Calculate width and height of medium sized image (max height: 700)
  $bigwidth = $width;
  $bigheight = $height;
  if ($bigheight > 700) {
    $bigheight = 700;
    $bigwidth = $bigwidth * ( $bigheight / $height );
  }

  // Create and save a medium image
  $big = imagecreatetruecolor($bigwidth, $bigheight);
  imagecopyresampled($big, $original, 0, 0, 0, 0, $bigwidth, $bigheight, $width, $height);
  imagepng($big, $bigFileName);

  return true;
}


function deletePlaceSelectedPhotos($placeID, $images){
  for($i=0;$i<count($images);$i++){
    
    if(removeImageFromPlace($placeID,$images[$i])==true){
      unlink("../assets/images/places/original/$images[$i]");
      unlink("../assets/images/places/small/$images[$i]");
      unlink("../assets/images/places/medium/$images[$i]");
      unlink("../assets/images/places/big/$images[$i]");
    }else{
      return false;
    }
  }
  return true;
}

function deletePlaceAllImages($placeID) {
	$images = getPlaceImages($placeID);

    foreach($images as $image) {
	  removeImageFromPlace($placeID,$image['image']);
      unlink("../assets/images/places/original/" . $image['image']);
      unlink("../assets/images/places/small/" . $image['image']);
      unlink("../assets/images/places/medium/" . $image['image']);
      unlink("../assets/images/places/big/" . $image['image']);
    }
}

?>