<?php
require_once '_route.php';

// get task name and details from POST data
$taskName = $_POST['taskName'];
$taskDetails = $_POST['taskDetails'];
$taskCreatedBy = $_POST['taskCreatedBy'];

// insert new task into database
$stmt = $db->prepare('INSERT INTO `todo_items` (task_name, task_details, completed, created_by) VALUES (:taskName, :taskDetails, 0, :taskCreatedBy)');
$stmt->bindParam(':taskName', $taskName);
$stmt->bindParam(':taskDetails', $taskDetails);
$stmt->bindParam(':taskCreatedBy', $taskCreatedBy);
$stmt->execute();

// return ID of new task
echo $db->lastInsertId();
?>
