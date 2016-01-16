<?php
//ini_set('display_errors', 'On');
//error_reporting(E_ALL | E_STRICT);

function checkStrings($string) {
  $explodeAndCheck = array_filter(explode(', ', $string), 'is_numeric'); // explode on ", " and only take the values which are numeric
  $finalParse = array_map('floatval', $explodeAndCheck); // convert from char to float

  $reconstructed = implode(", ", $finalParse); // reconstruct the $finalParse with the reference (", ") rule.

  // Compare the reconstructed and original versions.
  if (strcmp($string, $reconstructed) !== 0) return 0; // Not OK! There's an error: whether the user didn't use the proper ", " rule, or he entered not numeric data.
  else return 1; // Alles gut!
}

include_once 'db_connect.php'; // Setup the connection, including database call.

  //if (!$_POST) die("You did not enter any form!");

  // Prepare basic data.
  $populations_sql = $_POST['populations'];
  $cellspersample_sql = $_POST['cellspersample'];
  $model_sql = mysql_real_escape_string($_POST['model']);
  $email_sql = $_POST['email'];

// Is the final submit button pressed?
if ($_POST['indata'] == "manual") {

  // Prepare additional data.
  $measurements_sql = mysql_real_escape_string($_POST['meritve']);
  $genes_sql = $_POST['geni'];


  // Check if data is written correctly.
  if(checkStrings($measurements_sql) === 1) {

    $sql = mysql_query("INSERT INTO data (genes, measurements, model, populations, sampleCells, email) VALUES ($genes_sql, '$measurements_sql', '$model_sql', $populations_sql, $cellspersample_sql, '$email_sql')");
    if (!$sql) die('Could not enter data. ' . mysql_error());

    echo 'Data was successfully submitted for processing with R. Thank you for using our site.';
  } else {
    echo 'An error occured. Please check whether you entered the data correctly and resubmit the form.';
  };

} else {

   // Prepare additional data.
    $file_ColNames = $_POST['colNam'];
    $file_RowNames = $_POST['rowNam'];
    $file_ColSort = $_POST['genVsam'];
    $file_name = $_POST['filename_sql'] . "_" . $_POST['path'];

    $sql = mysql_query("INSERT INTO data (file_ColNames, file_RowNames, file_ColSort, file_name, model, populations, sampleCells, email) VALUES ('$file_ColNames', '$file_RowNames', '$file_ColSort', '$file_name', '$model_sql',  $populations_sql, $cellspersample_sql, '$email_sql')");
    if (!$sql) die('Could not enter data. ' . mysql_error());

    echo 'Data was successfully submitted for processing with R. Thank you for using our site.';
}

mysql_close($link); // Close the SQL connection.
?>
