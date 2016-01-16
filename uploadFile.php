<?php
//echo $_FILES['file']['name'];
$upload_dir = "file_uploads/";

$sourcePath = $_FILES['file']['tmp_name'];
$extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

if ($extension != "txt") { echo json_encode(01); die(""); }
if ($_FILES["file"]["size"] > 5000000) { echo json_encode(10); die(""); }


$file_id = rand(0, 1000); // generate a random file id
$file_randName = $file_id . "_" . $_FILES['file']['name'];
$targetPath = $upload_dir . $file_randName; // Target path where file is to be stored


if(move_uploaded_file($sourcePath,$targetPath)) echo json_encode($file_id); // Success.
else echo json_encode(00); //

?>
