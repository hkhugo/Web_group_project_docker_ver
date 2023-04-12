<?php
require_once '_route.php';

$taskid = $_GET['id'];

try {
    // Get the task name from the database
    $stmt = $db->prepare('SELECT task_name FROM todo_items WHERE id = :task_id');
    $stmt->bindParam(':task_id', $taskid);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $taskname = $row['task_name'];

    // Get the posts for the task
    $stmt = $db->prepare('SELECT posts.*, users.user_name 
    FROM posts 
    JOIN users ON posts.post_by = users.user_id 
    WHERE todo_item_id = :task_id');
    $stmt->bindParam(':task_id', $taskid);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Construct the response as a JSON object
    $response = array(
        'taskname' => $taskname,
        'posts' => $rows
    );
    echo json_encode($response);
} catch (PDOException $e) {
    error_log('PDOException - ' . $e->getMessage(), 0);
    http_response_code(500);
    die('Error establishing connection with database');
}
?>
