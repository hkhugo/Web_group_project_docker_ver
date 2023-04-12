<?php

require_once '_route.php';

if (array_key_exists('is_logged', $_SESSION)) {
    header('Location: main.php');
    exit;
}

// If the form has been submitted
if ($_POST && isset($_POST['submit'])) {

    //If complete recaptcha start complete 
    // if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
        //Strip whitespace and filter removes tags
        $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
        $password = trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));
        $passconf = trim(filter_input(INPUT_POST, 'passconf', FILTER_SANITIZE_STRING));

        //Missing required fields
        if (_is_valid($username) === FALSE || _is_valid($password) === FALSE || _is_valid($passconf) === FALSE) {
            echo "Missing required fields
                <p><a href='createUser.html'>Return to create user</a></p>";
            _log_error("Missing required fields");
        }

        // Validate password strength and rule
        $upper = preg_match('@[A-Z]@', $password);
        $lower = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);
        // $specialChars = preg_match('@[^\w]@', $password);

        if (!$upper || !$lower || !$number || strlen($password) < 8) { //|| !$specialChars
            echo "The passwords should be at least 8 characters in length and one upper case letter, one number, and one special character.
            <p><a href='createUser.html'>Return to create user</a></p>";
            _log_error("The passwords should be at least 8 characters in length and one upper case letter, one number, and one special character.");
        }

        //Passwords did not match
        if ($password != $passconf) {
            echo
            "The passwords is not the same.<p><a href='createUser.html'>Return to create user</a></p>";
            _log_error('The passwords is not the same.');
        }

        //search username in db
        $stmt = $db->prepare('SELECT `user_name` FROM `users` WHERE `user_name` = :username LIMIT 1');
        $stmt->execute(array(':username' => $username));
        $stmt->closeCursor();

        $num = $db->query('SELECT FOUND_ROWS()')->fetchColumn();

        //if same username found 
        if ($num > 0) {
            echo "Sorry, the username '{$username}' is already in use. <a href='createUser.html'>Go back</a>.</p>";
            _log_error("Sorry, the username '{$username}' is already in use.");
        }

        //insert to db
        try {
            $stmt = $db->prepare('INSERT INTO `users` (`user_name`, `user_password`) VALUES (:username, :password)');
            $stmt->execute(array(':username' => $username, ':password' => _password_hash($password)));
            $stmt->closeCursor();
        } catch (PDOException $e) {
            $stmt->closeCursor();
            echo "Database insert query failed";
            error_log('Database insert query failed: ' . $e->getMessage());
        } catch (Exception $e) {
            echo "Error";
            error_log('Error: ' . $e->getMessage());
        }
        echo "<h1>Registered Account!</h1>";
        echo "<p>Thank you, you have registered - you can <a href='login.html'>login</a> now.</p>";
    } 
    // else {
    //     //If do not complete recaptcha
    //     // echo "<h1>Please complete recaptcha</h1>";
    // }
// }