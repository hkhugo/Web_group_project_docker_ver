<?php

session_start();

if (isset($_SESSION['username'])) {
    $response = array('loggedin' => true, 'username' => $_SESSION['username']);
    echo json_encode($response);
} else {
    $response = array('loggedin' => false);
    echo json_encode($response);
}


?>

