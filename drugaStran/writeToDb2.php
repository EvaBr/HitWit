<?php
    //ini_set('display_errors', 'On');
    //error_reporting(E_ALL | E_STRICT);

  include_once 'db_connect2.php'; // Setup the connection, including database call.

    // Prepare basic data.
    $populations_sql = $_POST['populations'];
    $cellspersample_sql = $_POST['cellspersample'];
    $model_sql = mysql_real_escape_string($_POST['model']);
    $email_sql = $_POST['email'];

    $samples_sql = $_POST['samples'];
    $cluster_sql = $_POST['cluster'];
    $tofile_sql = $_POST['writetofile'];

    // Conditional data.
    $numbermean = $populations_sql;
    $numberstd = $populations_sql;

    if ($model_sql == "expln") {
      $rate_sql = $_POST['rate'];
      $numbermean--;
      $numberstd--;
    } else {
      if ($model=="lnln") {
        $numberstd = 1;
      }
    }
    if ($tofile_sql == "yes"){
      $filename_sql = $_POST['filename'];
    } else {
      $filename_sql = "";
    }


    $first = 0;
    $probabilities = $_POST['popProb'.$first];
    $logmeans = $_POST['logMean'.$first];
    $stddev = $_POST['stdDev'.$first];
    for ($i = 1; $i < $populations_sql; $i++) {
      $probabilities .= "|".$_POST['popProb'.$i];
    }
    for ($i = 1; $i < $numbermean; $i++) {
      $logmeans .= "|".$_POST['logMean'.$i];
    }
    for ($i = 1; $i < $numberstd; $i++) {
      $stddev .= "|".$_POST['stdDev'.$i];
    }

    // Check if data is written correctly.
    if ($model_sql == "expln") {
      $sql = mysql_query("INSERT INTO dataGenerate (model, populations, samples, cellsPerSample, genes, populationProbs, logMeans, rate, stdDeviations, email, writetofile, filename) VALUES ('$model_sql', $populations_sql, $samples_sql, $cellspersample_sql, $cluster_sql, '$probabilities', '$logmeans', $rate_sql, '$stddev', '$email_sql', '$tofile_sql', '$filename_sql')");
      if (!$sql) die('Could not enter data. ' . mysql_error());

      echo '<div style="margin-left: 1.9em color: #555555; font-size: 12pt; font-weight: bolder;">Data was successfully submitted for processing with R. Results will be sent to you shortly. Thank you for using our site.</div>';
    } else {
      $sql = mysql_query("INSERT INTO dataGenerate (model, populations, samples, cellsPerSample, genes, populationProbs, logMeans, stdDeviations, email, writetofile, filename) VALUES ('$model_sql', $populations_sql, $samples_sql, $cellspersample_sql, $cluster_sql, '$probabilities', '$logmeans', '$stddev', '$email_sql', '$tofile_sql', '$filename_sql')");
      if (!$sql) die('Could not enter data. ' . mysql_error());

      echo '<div style="margin-left: 1.9em; color: #555555; font-size: 12pt; font-weight: bolder;">Data was successfully submitted for processing with R. Results will be sent to you shortly. Thank you for using our site.</div>';
    }


mysql_close($link); // Close the SQL connection.
?>
