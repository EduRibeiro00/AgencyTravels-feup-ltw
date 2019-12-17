
<?php
function buildArrayWithFilesToAdd(){
    $array_fileNames = array();
    $fileName0 = $_POST['File0'];
    $fileName1 = $_POST['File1'];
    $fileName2 = $_POST['File2'];
    $fileName3 = $_POST['File3'];
    $fileName4 = $_POST['File4'];
    $fileName5 = $_POST['File5'];

    //Verifies if all of them are valid and if soo push to an array file names
    if (isset($fileName0) && $fileName0 != "") {
        array_push($array_fileNames, $fileName0);
    }
    if (isset($fileName1) && $fileName1 != "") {
        array_push($array_fileNames, $fileName1);
    }
    if (isset($fileName2) && $fileName2 != "") {
        array_push($array_fileNames, $fileName2);
    }
    if (isset($fileName3) && $fileName3 != "") {
        array_push($array_fileNames, $fileName3);
    }
    if (isset($fileName4) && $fileName4 != "") {
        array_push($array_fileNames, $fileName4);
    }
    if (isset($fileName5) && $fileName5 != "") {
        array_push($array_fileNames, $fileName5);
    }
    return $array_fileNames;
}

function check_File_Integrity($imageName, &$array_fileNames,&$Duplicates)
{
    $arraySize=count($array_fileNames);

    $firstTime=true;

    if(in_array($imageName, $array_fileNames)==true){

        for($i=0;$i<$arraySize;$i++){

            if(strcmp($array_fileNames[$i],$imageName)==0){
                //Remove duplicates
                $array_fileNames[$i]='';
                
                if($firstTime==true){
                    $firstTime=false;
                }else{
                    $Duplicates=true;
                }
            }
        }
        
        return true;
    }
    return false;
}

//TODO: FUNCTION TO TEST THE INPUTS



?>