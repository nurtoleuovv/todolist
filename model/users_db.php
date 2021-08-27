<?php
    
    function create_user($username, $password, $email){
        global $db;
        $password = md5($password);
        $query = 'INSERT INTO users (username, email, pass)
              VALUES
                 (:username, :email, :pass)';
        $statement = $db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->bindValue(':pass', $password);
        $statement->bindValue(':email', $email);
        $statement->execute();
        $statement->closeCursor();
    }

    function check_pass($usernameLogin, $passLogin){
        global $db;
        $query = 'SELECT * FROM users where USERNAME = :username';
        $statement = $db->prepare($query);
        $statement->bindValue(':username', $usernameLogin);
        $statement->execute();
        $user = $statement->fetch();
        $statement->closeCursor();
        if ($user['PASS'] == md5($passLogin)){
            return true;
        } else {
            return false;
        }
    }