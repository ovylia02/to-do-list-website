<?php
// Connect to database
include 'db.php';

// DELETE functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteId'])) {
    $id = $_POST['deleteId'];
    
    $delete = $connection->prepare("DELETE FROM tasks WHERE id = ?");
    $delete->bind_param("i", $id);

    $delete->execute();
    $delete->close();

    header('Location:index.php');
    exit;
}

// Disconnect from database
$connection->close();
?>
