<?php
require_once('_pdo.php');

// get task name from POST data
$taskName = $_POST['taskName'];

// delete task from database
$stmt = $db->prepare('DELETE FROM `todo_items` WHERE task_name = :taskName');
$stmt->bindParam(':taskName', $taskName);
$stmt->execute();

echo "Task deleted successfully";
?>
