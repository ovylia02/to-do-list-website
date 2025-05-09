<?php
// Connect to database
include 'db.php';

// UPDATE functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateId'])) {
    $id = $_POST['updateId'];
    $task = $_POST['updateTask'];
    $priority = $_POST['updatePriority'];

    $update = $connection->prepare("UPDATE tasks SET task = ?, priority = ? WHERE id = ?");
    $update->bind_param("ssi", $task, $priority, $id);

    $update->execute();
    $update->close();

    header('Location: index.php');
    exit;
}

// Disconnect from database
$connection->close();
?>
