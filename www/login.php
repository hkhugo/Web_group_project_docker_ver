<?php

require_once '_route.php';

if (array_key_exists('is_logged', $_SESSION)) {
    header('Location: index.html');
    exit;
}

// If the form has been submitted
if ($_POST && isset($_POST['submit'])) {
        //Strip whitespace and filter removes tags
        $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
        $password = trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));

        //Check if field is empty
        if (_is_valid($username) === FALSE){
            echo "Username is empty. Please <a href='login.html'>login again</a>.";
            _log_error('Username is empty. Please.');
        }

        if (_is_valid($password) === FALSE){
            echo "Password is empty. Please <a href='login.html'>login again</a>.";
            _log_error('Password is empty. Please.');
        }

        //Check username and password
        $stmt = $db->prepare('SELECT * FROM `users` WHERE `user_name` = :username LIMIT 1');
        $stmt->execute(array(':username' => $username));

        $num = $db->query('SELECT FOUND_ROWS()')->fetchColumn();

        if ($num == 0){
            echo "Incorrect username or password. Please <a href='login.html'>login again</a>.";
            _log_error('Incorrect username or password.');
        }


        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if (password_verify($password, $row['user_password']) === FALSE){
            echo "Incorrect username or password. Please <a href='login.html'>login again</a>.";
            _log_error('Incorrect username or password.');
        }

        //Put the data to cookie when login success
        $_SESSION['is_logged'] = TRUE;
        $_SESSION['userid']  = $row['user_id'];
        $_SESSION['username']  = $username;
        // redirect to index
        header('Location: index.html');
        exit;
}

?>