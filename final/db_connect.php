<?php
$servername = "localhost";
$username = "eva";
$password = "blabla";

$database = "eva";

$link = mysql_connect($servername, $username, $password);
if (!$link) {
    die('Connection not possible : ' . mysql_error());
}

$selected = mysql_select_db($database, $link)
  or die("Could not select examples");

?>
