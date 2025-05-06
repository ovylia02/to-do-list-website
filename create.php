<?php
// Connect to database
include 'db.php';

// CREATE functionality
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task = $_POST['task'] ?? '';
    $priority = $_POST['priority'] ?? 'neutral';

    $insert = $connection->prepare("INSERT INTO tasks (task, priority, done) VALUES (?, ?, 0)");
    $insert->bind_param("ss", $task, $priority);

    $insert->execute();
    $insert->close();

    header('Location: index.php');
    exit;
}

// Disconnect from database
$connection->close();
?>
