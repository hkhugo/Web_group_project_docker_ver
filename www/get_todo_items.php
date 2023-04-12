<?php
require_once '_route.php';

try {
    $stmt = $db->prepare('SELECT * FROM todo_items');
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($rows);
} catch (PDOException $e) {
    error_log('PDOException - ' . $e->getMessage(), 0);
    http_response_code(500);
    die('Error establishing connection with database');
}
?>
