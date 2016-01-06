<?php
function checkStrings($string) {
  $explodeAndCheck = array_filter(explode(', ', $string), 'is_numeric'); // explode on ", " and only take the values which are numeric
  $finalParse = array_map('floatval', $explodeAndCheck); // convert from char to float

  $reconstructed = implode(", ", $finalParse); // reconstruct the $finalParse with the reference (", ") rule.

  // Compare the reconstructed and original versions.
  if (strcmp($string, $reconstructed) !== 0) return 0; // Not OK! There's an error: whether the user didn't use the proper ", " rule, or he entered not numeric data.
  else return 1; // Alles gut!
}

  $measurements_sql = $_POST['meritve'];

  if(checkStrings($measurements_sql) === 1){
    $result = 1;
  } else {
    $result = 0;
  }

  echo json_encode($result);
?>
