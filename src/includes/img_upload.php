<?php
include_once('../database/db_user.php');

function uploadUserImage($userID, $image) {

    // checks whether or not the file passed has a valid extension
    $allowed = array('jpeg', 'jpg', 'png');
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    if (!in_array($ext, $allowed)) {
        return false;
    }

    $isPNG = ($ext == "png" ? true : false);

    insertImageForUser($userID);
    $imageID = getUserImageMaxID();

    // Generate filenames for original, small and medium files
    $originalFileName = "../../assets/images/users/original/$imageID.$ext";
    $smallFileName = "../../assets/images/users/small/$imageID.$ext";
    $mediumFileName = "../../assets/images/users/medium/$imageID.$ext";

    // Move the uploaded file to its final destination
    move_uploaded_file($image, $originalFileName);

    // Crete an image representation of the original image
    $original = ($isPNG ? imagecreatefrompng($originalFileName) : imagecreatefromjpeg($originalFileName));
    
    $width = imagesx($original);     // width of the original image
    $height = imagesy($original);    // height of the original image
    $square = min($width, $height);  // size length of the maximum square

    // Create and save a small square thumbnail
    $small = imagecreatetruecolor(200, 200);
    imagecopyresized($small, $original, 0, 0, ($width>$square)?($width-$square)/2:0, ($height>$square)?($height-$square)/2:0, 200, 200, $square, $square);
    if($isPNG) {
        imagepng($small, $smallFileName);
    }
    else {
        imagejpeg($small, $smallFileName);
    }

    // Calculate width and height of medium sized image (max width: 400)
    $mediumwidth = $width;
    $mediumheight = $height;
    if ($mediumwidth > 400) {
      $mediumwidth = 400;
      $mediumheight = $mediumheight * ( $mediumwidth / $width );
    }

    // Create and save a medium image
    $medium = imagecreatetruecolor($mediumwidth, $mediumheight);
    imagecopyresized($medium, $original, 0, 0, 0, 0, $mediumwidth, $mediumheight, $width, $height);
    if($isPNG) {
        imagepng($medium, $mediumFileName);
    }
    else {
        imagejpeg($medium, $mediumFileName);
    }

    return true;
}


?>