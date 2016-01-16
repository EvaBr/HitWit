<?php
$servername = "localhost";
$username = "u333095501_hitwi";
$password = "blabla";

$database = "u333095501_hitwi";

$link = mysql_connect($servername, $username, $password);
if (!$link) {
    die('Connection not possible : ' . mysql_error());
}

$selected = mysql_select_db($database, $link)
  or die("Could not select examples");

?>
