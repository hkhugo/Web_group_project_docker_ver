<?php
require_once('_pdo.php');

// get task name and completion status from POST data
$taskName = $_POST['taskName'];
$completed = $_POST['completed'];

// update task completion status in database
$stmt = $db->prepare('UPDATE `todo_items` SET completed = :completed WHERE task_name = :taskName');
$stmt->bindParam(':taskName', $taskName);
$stmt->bindParam(':completed', $completed);
$stmt->execute();

echo "Task updated successfully";
?>
