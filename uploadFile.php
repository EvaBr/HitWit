<?php
echo $_POST['file'];
if (isset($_FILES['file'])) echo 'haba';
move_uploaded_file(

    // this is where the file is temporarily stored on the server when uploaded
    // do not change this
    $_FILES['file']['tmp_name'],

    // this is where you want to put the file and what you want to name it
    // in this case we are putting in a directory called "uploads"
    // and giving it the original filename
    'file_uploads/' . $_FILES['file']['name']
);

/*$upload_dir = "file_uploads/";

$sourcePath = basename($_FILES['filepath']['name']);
$extension = pathinfo($sourcePath, PATHINFO_EXTENSION);
echo $sourcePath;
echo $extension;

if ($extension != "csv" || $extension != "txt") die("The file extension is not .csv or .txt.");
if ($_FILES["filepath"]["size"] > 5000000) die("The file is bigger than 5 mb.");

echo 'haha';*/
//$targetPath = .$upload_dir.$_FILES['file']['name']; // Target path where file is to be stored
//move_uploaded_file($sourcePath,$targetPath) ;

?>
