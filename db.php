<?php
// Database variables
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'todolistapp';

// Connect to xampp database
$connection = new mysqli($host, $user, $pass, $dbname, 3306);
if ($connection->connect_error) {
    echo "Connection failed";
}
?>
