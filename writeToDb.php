<?php
include_once 'db_connect.php'; // Setup the connection, including database call.
echo $_POST['model'];

// Is the final submit button pressed?
if ($_POST) {

  // Prepare data.
  $measurements_sql = mysql_real_escape_string($_POST['meritve']);
  $model_sql = mysql_real_escape_string($_POST['model']);
  $genes_sql = $_POST['geni'];
  $populations_sql = $_POST['populations'];
  $cellspersample_sql = $_POST['cellspersample'];

  $sql = mysql_query("INSERT INTO data (genes, measurements, model, populations, sampleCells) VALUES ($genes_sql, '$measurements_sql', '$model_sql', $populations_sql, $cellspersample_sql)");

  if (!$sql) die('Could not enter data. ' . mysql_error());

} else {
  echo 'Error.';
}


mysql_close($link); // Close the SQL connection.
?>
