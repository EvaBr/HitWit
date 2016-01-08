<?php

echo $_FILES['path'];

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
