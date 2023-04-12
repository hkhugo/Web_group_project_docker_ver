<?php
require_once '_route.php';

// Get the task ID and message content from the POST request
$taskid = $_POST['taskid'];
$content = $_POST['post_content'];
$username = $_POST['username'];

// Retrieve the user ID based on the provided username
try {
    $stmt = $db->prepare('SELECT user_id FROM users WHERE user_name = :username');
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $userid = $row['user_id'];
} catch (PDOException $e) {
    error_log('PDOException - ' . $e->getMessage(), 0);
    http_response_code(500);
    die('Error retrieving user ID from database');
}

// Insert the post into the database
try {
    $stmt = $db->prepare('INSERT INTO posts (todo_item_id, post_content, post_date, post_by) VALUES (:task_id, :content, NOW(), :user_id)');
    $stmt->bindParam(':task_id', $taskid);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':user_id', $userid);
    $stmt->execute();
} catch (PDOException $e) {
    error_log('PDOException - ' . $e->getMessage(), 0);
    http_response_code(500);
    die('Error inserting post into database');
}

// Return the new post as JSON
try {
  $stmt = $db->prepare('SELECT posts.*, users.user_name 
  FROM posts 
  JOIN users ON posts.post_by = users.user_id 
  WHERE todo_item_id = :task_id AND post_content = :content');
  $stmt->bindParam(':task_id', $taskid);
  $stmt->bindParam(':content', $content);
  $stmt->execute();
  $temp = $stmt->fetch(PDO::FETCH_ASSOC);

  // Construct the response as a JSON object
  $response = array(
      'user_name' => $temp['user_name'],
      'post_date' => $temp['post_date'],
      'post_content' => $temp['post_content']
  );
  echo json_encode($response);
} catch (PDOException $e) {
  error_log('PDOException - ' . $e->getMessage(), 0);
  http_response_code(500);
  die('Error retrieving post from database');
}
?>