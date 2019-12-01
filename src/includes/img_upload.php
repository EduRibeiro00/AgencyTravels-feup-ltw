<?php
include_once('../database/db_user.php');

function uploadUserImage($userID, $image) {

    // checks whether or not the file passed has a valid extension
    $allowed = array('jpeg', 'jpg', 'png', 'gif');
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    if (!in_array($ext, $allowed)) {
        return false;
    }

    insertImageForUser($userID);
    $imageID = getUserImageMaxID();

    // Generate filenames for original, small and medium files
    $originalFileName = "../../assets/images/users/original/$imageID.png";
    $smallFileName = "../../assets/images/users/small/$imageID.png";
    $mediumFileName = "../../assets/images/users/medium/$imageID.png";

    // Move the uploaded file to its final destination (always converted to png)
    imagepng(imagecreatefromstring(file_get_contents($image)), $originalFileName);

    // Crete an image representation of the original image
    $original = imagecreatefrompng($originalFileName);
    
    $width = imagesx($original);     // width of the original image
    $height = imagesy($original);    // height of the original image
    $square = min($width, $height);  // size length of the maximum square

    // Create and save a small square thumbnail
    $small = imagecreatetruecolor(200, 200);
    imagecopyresized($small, $original, 0, 0, ($width>$square)?($width-$square)/2:0, ($height>$square)?($height-$square)/2:0, 200, 200, $square, $square);
    imagepng($small, $smallFileName);

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
    imagepng($medium, $mediumFileName);

    return true;
}


?>