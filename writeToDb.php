<?php
function checkStrings($string) {
  $explodeAndCheck = array_filter(explode(', ', $string), 'is_numeric'); // explode on ", " and only take the values which are numeric
  $finalParse = array_map('floatval', $explodeAndCheck); // convert from char to float

  $reconstructed = implode(", ", $finalParse); // reconstruct the $finalParse with the reference (", ") rule.

  // Compare the reconstructed and original versions.
  if (strcmp($string, $reconstructed) !== 0) return 0; // Not OK! There's an error: whether the user didn't use the proper ", " rule, or he entered not numeric data.
  else return 1; // Alles gut!
}

include_once 'db_connect.php'; // Setup the connection, including database call.
echo $_POST['path'];

// Is the final submit button pressed?
if ($_POST) {

  // Prepare data.
  $measurements_sql = mysql_real_escape_string($_POST['meritve']);
  $model_sql = mysql_real_escape_string($_POST['model']);
  $genes_sql = $_POST['geni'];
  $populations_sql = $_POST['populations'];
  $cellspersample_sql = $_POST['cellspersample'];

  // Check if data is written correctly.
  if(checkStrings($measurements_sql) === 1) {

    $sql = mysql_query("INSERT INTO data (genes, measurements, model, populations, sampleCells) VALUES ($genes_sql, '$measurements_sql', '$model_sql', $populations_sql, $cellspersample_sql)");
    if (!$sql) die('Could not enter data. ' . mysql_error());

    echo 'Data was successfully written to our database. Thank you for your contribution.';
  } else {
    echo 'An error occured. Please check whether you entered the data correctly and resubmit the form.';
  };
} else {
  echo 'There was no form submitted.';
}

mysql_close($link); // Close the SQL connection.
?>
