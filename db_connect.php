<?php
$servername = "localhost";
$username = "hitwit";
$password = "blabla";

$database = "hitwit";

$link = mysql_connect($servername, $username, $password);
if (!$link) {
    die('Connection not possible : ' . mysql_error());
}
echo "Connected successfully";

$selected = mysql_select_db($database, $link)
  or die("Could not select examples");

?>
